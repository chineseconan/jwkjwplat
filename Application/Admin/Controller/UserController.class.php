<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController {
public $roleid;

    function _initialize(){
        $this->roleid = C('PROFESSERID');
    }
    /**
     * 获取后台用户列表
     */

    public function getUserLists(){
        $model = M('sysuser');
        $search = I('post.');
        $search = trim($search['data']['q']);
        $data = $model->field("user_id id,(user_realusername || '-' || user_name) as text")
            ->where("（user_realusername like '%s' or user_name like '%s'） and user_issystem != '是'", "%$search%","%$search%")
            ->select();
        echo json_encode(array('q' => $search, 'results' => $data));
    }

    /**
     * 根据密级搜索用户
     */
    public function getUsersBySecretLevel(){
        $model = M('sysuser');
        $search = I('post.');
        $search = explode(',',trim($search['data']['q']));
        $searchUser = $search[0];
        $secretLevel = $search[1];
        if(empty($secretLevel)) echo json_encode(array('q' => $search, 'results' => []));

        $secretStr = D('Dictionary')->getSecretLevelDataByLevel($secretLevel, true,true);
        $data = $model->field("user_id id,(user_realusername || '-' || user_name) as text")
            ->where("（user_realusername like '%s' or user_name like '%s'） and user_issystem != '是' and user_secretlevel in ($secretStr)", "%$searchUser%","%$searchUser%")
            ->select();
        echo json_encode(array('q' =>$searchUser , 'results' => $data));
    }

    /**
     * 根据风险密级获取用户
     */
    public function getUsersByRiskSecret(){
        $data = I('post.');
        $search = explode(',',trim($data['data']['q']));
        $model = M('sysuser');
        $searchUser = $search[0];
        $riskId = $search[1];
        if(empty($riskId)) exit(json_encode(array('q' => $searchUser, 'results' => [])));
        $riskSecretLevel = M('projrisk')->where("risk_id = '$riskId'")->field('risk_secretlevel')->find();
//        dump($riskSecretLevel);
        $secretStr = D('Dictionary')->getSecretLevelDataByLevel($riskSecretLevel['risk_secretlevel'], true,true);
        $data = $model->field("user_id id,(user_realusername || '-' || user_name) as text")
            ->where("（user_realusername like '%s' or user_name like '%s'） and user_issystem != '是' and user_secretlevel in ($secretStr)", "%$searchUser%","%$searchUser%")
            ->select();
//        echo $model->_sql();
        exit(json_encode(array('q' =>$searchUser , 'results' => $data)));
    }

    /**
     * 用户管理
     */
    public function index(){
//        $dicType = D('Dictionary')->getDicType();
//        $this->assign('dictionaryType', $dicType);
        //$this->addLog('','用户访问日志','','访问用户管理','成功');
        $group = D("Dictionary")->getDicValueByName("分组");
         $this->assign("group", $group);
        $this->display();
    }

    /**
     * 获取用户列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);

        $realName = trim($queryParam['real_name']);
        $userName=trim($queryParam['user_name']);
        $userClass=trim($queryParam['user_class']);

        $where['user_issystem'][]=[['neq','是'],['exp','is null'],'or'];
        $where['user_isdelete']=['neq',1];
        $where['user_role']       = ['eq',$this->roleid];
        if(!empty($realName))
        {
            //$this->addLog('','用户访问日志','','访问用户管理,查询用户名称关键字:'.$realName,'成功');
            $where['user_realusername']=['like',"%$realName%"];
        }
        if(!empty($userName))
        {
            $where['user_name']=['like',"%$userName%"];
        }
        if(!empty($userClass))
        {
            $where['user_class']=['like','%'.$userClass.'%'];
        }


        $model = M('sysuser');
        $data = $model->field('user_id,user_password,user_zhiwu,user_zhicheng,user_mobile,user_orgid,user_realusername,user_class,user_name,user_secretlevel,user_createtime,user_lastmodifytime,user_secretlevelcode')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
        //echo $model->_sql();die;
       // print_r($data);die;
        $count = $model->where($where)->count();

        foreach($data as &$value){
            if($value['user_createtime']!=null)
            $value['user_createtime'] = date('Y-m-d H:i:s',$value['user_createtime']);
            if($value['user_lastmodifytime']!=null)
            $value['user_lastmodifytime'] = date('Y-m-d H:i:s',$value['user_lastmodifytime']);
            //md5(session('user_id').trim(I('post.user_secretlevel')));
        }
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }
    /**
     * 导出
     */
    public function export(){
        $queryParam = I('get.');

        $realName = trim($queryParam['real_name']);
        $userName=trim($queryParam['user_name']);
        $where['user_issystem'][]=[['neq','是'],['exp','is null'],'or'];
        $where['user_isdelete']=['neq',1];
        if(!empty($realName))
        {
            //$this->addLog('','用户访问日志','','访问用户管理,查询用户名称关键字:'.$realName,'成功');
            $where['user_realusername']=['like',"%$realName%"];
        }
        if(!empty($userName))
        {
            $where['user_name']=['like',"%$userName%"];
        }
        $model = M('sysuser');
        $data = $model
            ->field('user_realusername,user_name,user_orgid,user_zhiwu,user_mobile,user_class')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->select();
//        echo $model->_sql();die;

        foreach($data as &$value){
            if($value['user_createtime']!=null)
            $value['user_createtime'] = date('Y-m-d H:i:s',$value['user_createtime']);
            if($value['user_lastmodifytime']!=null)
            $value['user_lastmodifytime'] = date('Y-m-d H:i:s',$value['user_lastmodifytime']);
        }
        $this->addLog('','对象修改日志','','导出用户列表','成功');
        $header = array('姓名','账号','单位','职务/职称','联系方式','分组');
        $width = Array('10',"15","15",'15','15','20','15');
        echo excelExport($header, $data, true,$width);
    }
    /**
     * 用户添加或修改
     */
    public function add(){
        $id = trim(I('get.id'));
        if(!empty($id)){
            $model = M('sysuser');
            $data = $model
                ->field('user_id,user_zhiwu,user_password,user_zhicheng,user_mobile,user_class,user_realusername,user_name,user_orgid,user_secretlevel')
                ->where("user_id='%s'", $id)
                ->find();
            $this->assign('data', $data);
        }
        $mijilist=D('dictionary')->getDicValueByName('密级');
        $group=D('dictionary')->getDicValueByName('分组');

//        print_r($orgList);die;
        $this->assign('group',$group);
        $this->assign('mijilist', $mijilist);
        $this->display();
    }

    /**
     * 用户添加修改
     */
    public function addSysUser(){
        $id = trim(I('post.id'));
        $data['user_realusername'] = trim(I('post.real_name'));
        $data['user_orgid'] = trim(I('post.org_id'));
        $data['user_class'] = trim(I('post.user_class'));
        $data['user_name'] = trim(I('post.user_name'));
        $data['user_zhiwu'] = I("post.user_zhiwu");
        $data['user_zhicheng'] = I("post.user_zhicheng");
        $data['user_mobile'] = I("post.user_mobile");
        $data['user_password'] = I("post.user_password");

        //if(empty($data['role_name']))  exit(makeStandResult(-1,'请输入姓名'));
        $model = M('sysuser');
        $modelrole = M('sysrole');
        $xmMoel = M("xmps_xm");
        $xmMap['xm_status'] = Array("eq","正常");
        $user_class   = $data['user_class'];
        $user_class   = str_replace('，',',',$user_class);
        $data['user_class'] = $user_class;
        $user_class = explode(',',$user_class);
        $xmMap['xm_class'] = Array("in",$user_class);
        $xmData = $xmMoel->where($xmMap)->select();
        $relation = M("xmps_xmrelation");
        //为空则添加
        if(empty($id)){
            $zhuanjia=$modelrole->where("role_name='专家'")->find();
            $map['user_name'] = $data['user_name'];
            $map['user_password'] = $data['user_password'];
            $map['user_isdelete'] = 0;
            $isexist= $model->where($map)->find();
            if(!empty($isexist))
            {
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'账号已存在'));
            }
            $data['user_name'] = trim(I('post.user_name'));
            $data['user_createtime'] = time();
            $data['user_id'] = makeGuid();
            $data['user_createuser'] = session('user_id');
            $data['user_enable']='启用';
            $data['user_issystem']='否';
            $data['user_role']=$zhuanjia["role_id"];
            $res = $model->add($data);
            foreach($xmData as $xm){
                $item['xr_id']      = makeGuid();
                $item['xr_status']  = "进行中";
                $item['xr_user_id'] = $data['user_id'];
                $item['xr_xm_id']   = $xm['xm_id'];
                $relation->add($item);
            }
            $adduserauth=array();
            $adduserauth["ua_id"]=makeGuid();
            $adduserauth["ua_roleid"]=$zhuanjia["role_id"];
            $adduserauth["ua_userid"]= $data['user_id'];
            $adduserauth["ua_createtime"]=time();
            $adduserauth["ua_createuser"]= $data['safe'];
            M("userauth")->add($adduserauth);
            if(empty($res)){
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'添加失败'));
            }else{
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'成功');
                exit(makeStandResult(1,'添加成功'));
            }
        }else{
            $data['user_lastmodifytime'] = time();
            $data['user_lastmodifyuser'] = session('user_id');
            $data['user_secretlevelcode']=md5($id.trim(I('post.user_secretlevel')));
            $data['user_enable']='启用';
            $map['user_name'] = $data['user_name'];
            $map['user_password'] = $data['user_password'];
            $map['user_isdelete'] = 0;
            $isexist= $model->where($map)->find();
            if($id!=$isexist["user_id"]&&!empty($isexist))
            {
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'账号已存在'));
            }
            $res = $model->where("user_id='%s'", $id)->save($data);
            if($data['user_class']!=$isexist['user_class']){
                $relation->where("xr_user_id='$id'")->delete();
                foreach($xmData as $xm){
                    $item['xr_id'] = makeGuid();
                    $item['xr_status'] = "进行中";
                    $item['xr_user_id'] = $id;
                    $item['xr_xm_id'] = $xm['xm_id'];
                    $relation->add($item);
                }
            }

            if(empty($res)){
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'修改失败'));
            }else{
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'成功');
                exit(makeStandResult(1,'修改成功'));
            }
        }
    }

    /**
     * 删除模块
     */
    public function delSysUser(){
        $ids = I('post.ids');
        if(empty($ids)) exit(makeStandResult(-1,'参数缺少'));
        $id = explode(',', $ids);
        $idStr = "'".implode("','", $id)."'";
        $model = M('sysuser');
        $userauth=M('userauth');
        foreach($id as $key=>$val)
        {
            $tem= $model->where("user_id='%s'",$val)->find();
            if(!empty($tem))
            {
                $this->addLog('sysuser','三员操作日志','delete','删除用户'.$tem['user_realusername'],'成功');
            }
        }
        $userauth->where("ua_userid in ($idStr)")->delete();
        $temp['user_isdelete']=1;
        $res = $model -> where("user_id in ($idStr)")->delete();
        $res = M('xmps_xmrelation')-> where("xr_user_id in ($idStr)")->delete();
        if(empty($res)){
            exit(makeStandResult(-1,'删除失败'));
        }else{
            exit(makeStandResult(1,'删除成功'));
        }
    }

    //选择项目页面
    public function setXM(){
        $id = I("get.id");
        $Model = M("sysuser");
        $user_class = $Model->where("user_id='$id'")->getField("user_class");
        $this->getDic();
        $this->assign("user_class",$user_class);
        $this->assign("userId",$id);
        $Model = M("xmps_xm");
        $data = $Model->field("xm_id,xm_code,xm_name,xm_company,xm_class,xm_year")->where("xm_status!='删除'")->select();
        $this->assign("xmdata",$data);
        $this->display("remote");
    }

    //备选项目
    public function getAlternativeData()
    {
        $param = json_decode(file_get_contents("php://input"), true);
        if($param['name']!=""){
            $map['xm_code'] = Array("eq",$param['name']);
        }
        $Model = M("xmps_xm");
        $xr = D("xmps_xmrelation");
        $notIn = "";
        $xr_xm_id = $xr->field("xr_xm_id")->where("xr_user_id='".$param["userId"]."'")->select();
        if(empty($param['xm_id']) && !$param['delete']){
            foreach($xr_xm_id as $val){
                $notIn .= $val['xr_xm_id'].",";
            }
        }else{
            foreach($param['xm_id'] as $val1){
                $notIn .= $val1['xm_id'].",";
            }
        }
        $notIn = rtrim($notIn,",");
        $map['xm_id'] = Array("not in",$notIn);
        $map['xm_class'] = Array("like",'%'.$param['class'].'%');
        $map['xm_status'] = Array("eq","正常");
        $data = $Model->field("xm_id,xm_code,xm_name,xm_company,xm_class,xm_year")->where($map)->order("xm_code")->select();
        $this->ajaxReturn($data);
    }
    //已选项目
    public function selectedData(){
        $data=array();
        $param = json_decode(file_get_contents("php://input"),true);
        $Model = D("xmps_xmrelation");
        if($param['name']!=""){
            $map['xm_code'] = Array("eq",$param['name']);
        }
        $map['xr_user_id'] = Array("eq",$param['userId']);
        $map['xm_status'] = Array("eq","正常");
        if($param["delete"]==true) {
            if ($param['name'] != "") {
                foreach($param['xm_id'] as $key=>$da){
                    if(in_array($param['name'],$da)) {
                        $data[count($data)]=$da;
                    }
                }
            }else{
                $data =$param['xm_id'];
            }
        }else {
            $data = $Model
                ->alias("t")
                ->where($map)
                ->field("xm_id,xm_code,xm_name,xm_company,xm_class,xm_year,xr_id")
                ->join("left join xmps_xm m on t.xr_xm_id=m.xm_id")
                ->order("xm_code")
                ->select();
        }
        $this->ajaxReturn($data);
    }
    public function saveXMData()
    {
        $param = I("post.");
        $XR = M("xmps_xmrelation");
        $xrData = $XR->field("xr_id")->where("xr_user_id='" . $param['user_id'] . "'")->select();
        $postxmdata = explode(",", $param['xmid']);
        $postxrdata = explode(",", $param['xrid']);
        foreach ($postxmdata as $key => $val) {
            if ($postxrdata[$key] == "") {
                $data['xr_xm_id'] = $val;
                $data['xr_user_id'] = $param['user_id'];
                $data['xr_id'] = makeGuid();
                $data['xr_status'] = "进行中";
                $XR->add($data);
            } else {
                foreach ($xrData as $k => $item) {
                    if ($postxrdata[$key] == $item['xr_id']) {
                        unset($xrData[$k]);
                    }
                }
            }
        }
        foreach ($xrData as $delete) {
            $XR->where("xr_id='" . $delete['xr_id'] . "'")->delete();
        }
    }

    private function getDic()
    {
        $Dic = D("Dictionary");
        $year = $Dic->getDicValueByName("年度");
        $group = $Dic->getDicValueByName("分组");
        $this->assign("group", $group);
        $this->assign("year", $year);
    }

    public function import(){
        if(IS_POST){
            $Model = M("sysuser");
            $file = uploadFile("file");
            $path = "./Public/".$file['message'];
            $import = excelImport($path);
            $str = "";
            $modelrole = M('sysrole');
            $column = Array("姓名","账号",'密码',"单位",'职务/职称','联系方式',"分组");
            $len = count($column);
            for($i=0;$i<$len;$i++){
                if($column[$i]!=$import['column'][$i]){
                    die("请按照模板填写导入数据，保持列头一致");
                }
            }
            $zhuanjia=$modelrole->where("role_name='专家'")->find();
            foreach($import['data'] as $val){
                if($val['B']!=NULL && $val['C']!=NULL && $val['A']!=NULL && $val['D']!=NULL){
                    $re = $Model->where("user_name='".$val['B']."' and user_password='". $val['C']."' and user_isdelete!='1'")->find();
                    if(empty($re)){
                        $data['user_name']         = $val['B'];
                        $data['user_realusername'] = $val['A'];
                        $data['user_orgid']        = $val['D'];
                        $data['user_zhiwu']        = $val['E'];
                        $data['user_mobile']       = $val['F'];
                        $data['user_password']    = $val['C'];
                        $data['user_class']       = $val['G'];
                        $data['user_createtime']  = time();
                        $data['user_id']          = makeGuid();
                        $data['user_createuser']  = session('user_id');
                        $data['user_enable']      ='启用';
                        $data['user_issystem']    ='否';
                        $data['user_role']        = $zhuanjia["role_id"];
                        // 处理user_class
                        $user_class               = $data['user_class'];
                        $user_class               = str_replace('，',',',$user_class);
                        $data['user_class']       = $user_class;
                        $user_class               = explode(',',$user_class);
                        $Model->add($data);
                        $xmMoel             = M("xmps_xm");
                        $xmMap['xm_status'] = Array("eq","正常");
                        $xmMap['xm_class']  = Array("in",$user_class);
                        $xmData             = $xmMoel->where($xmMap)->select();
                        $relation = M("xmps_xmrelation");
                        foreach($xmData as $xm){
                            $item['xr_id'] = makeGuid();
                            $item['xr_status'] = "进行中";
                            $item['xr_user_id'] = $data['user_id'];
                            $item['xr_xm_id'] = $xm['xm_id'];
                            $relation->add($item);
                        }
                        $adduserauth=array();
                        $adduserauth["ua_id"]=makeGuid();
                        $adduserauth["ua_roleid"]= $zhuanjia["role_id"];
                        $adduserauth["ua_userid"]= $data['user_id'];
                        $adduserauth["ua_createtime"]=time();
                        $adduserauth["ua_createuser"]= $data['safe'];
                        M("userauth")->add($adduserauth);
                    }else{
                        $str .= $re['user_name'].",";
                    }
                }
            }
            $str = rtrim($str,",");
            if($str==""){
                echo "ok";
            }else{
                echo "账号：".$str."已存在";
            }
        }else{
            $this->display();
        }
    }


}