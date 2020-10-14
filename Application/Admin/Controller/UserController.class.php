<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class UserController extends BaseController {
public $roleid;
public $viewid;

    function _initialize(){
        $this->roleid = C('PROFESSERID');
        $this->viewid = C('VIEWUSERID');
    }

    /**
     * 用户管理
     */
    public function index(){
        $this->getDic();
        $this->display();
    }

    /**
     * 获取用户列表
     */
    public function getData($isExport = false,$queryParam = []){
        if(!$isExport){
            $queryParam = json_decode(file_get_contents( "php://input"), true);
        }

        $realName   = trim($queryParam['real_name']);
        $userClass  = trim($queryParam['user_class']);

        $where['user_issystem'][] = [['neq','是'],['exp','is null'],'or'];
        $where['user_isdelete']   = ['neq',1];
        $where['user_role']       = ['eq',$this->roleid];

        if(!empty($realName)) $where['user_realusername']=['like',"%$realName%"];
        if(!empty($userClass)) $where['user_class']=['eq',$userClass];

        $model = M('sysuser');
        if(!$isExport){
            $data = $model->field('user_id,user_password,user_zhiwu,user_zhicheng,user_mobile,user_orgid,user_realusername,user_class,user_name,user_secretlevel,user_createtime,user_lastmodifytime,user_secretlevelcode,user_sessionid')
                ->where($where)
                ->order("$queryParam[sort] $queryParam[sortOrder]")
                ->limit($queryParam['offset'], $queryParam['limit'])
                ->select();
            //echo $model->_sql();die;
            $count = $model->where($where)->count();

            foreach($data as &$value){
                if($value['user_createtime']!=null)
                    $value['user_createtime'] = date('Y-m-d H:i:s',$value['user_createtime']);
                if($value['user_lastmodifytime']!=null)
                    $value['user_lastmodifytime'] = date('Y-m-d H:i:s',$value['user_lastmodifytime']);
            }
            exit(json_encode(array( 'total' => $count,'rows' => $data)));
        }else{
            $data = $model->field('user_realusername,user_name,user_orgid,user_zhiwu,user_mobile,user_class')
                ->where($where)
                ->order("$queryParam[sort] $queryParam[sortOrder]")
                ->select();
            foreach($data as &$value){
                if($value['user_createtime']!=null)
                    $value['user_createtime'] = date('Y-m-d H:i:s',$value['user_createtime']);
                if($value['user_lastmodifytime']!=null)
                    $value['user_lastmodifytime'] = date('Y-m-d H:i:s',$value['user_lastmodifytime']);
            }
            return $data;
        }
    }

    /**
     * 根据专家域账号清除登录存储的session
     */
    function clearLogin(){
        $userId = I('post.id');
        if(empty($userId)) exit(makeStandResult(1,'参数缺失，请刷新重试'));
        M('sysuser')->where("user_id='%s'",$userId)->setField(['user_sessionid'=>null]);
        exit(makeStandResult(0,'操作成功'));
    }

    /**
     * 用户添加或修改
     */
    public function add(){
        $id = trim(I('get.id'));
        if(!empty($id)){ // 如果get接收到id,则为修改需查询用户信息
            $model = M('sysuser');
            $data = $model
                ->field('user_id,user_zhiwu,user_password,user_zhicheng,user_mobile,user_class,user_realusername,user_name,user_orgid,user_secretlevel')
                ->where("user_id='%s'", $id)
                ->find();
            $this->assign('data', $data);
        }

        $this->display();
    }

    /**
     * 用户添加修改
     */
    public function addSysUser(){
//        dump(I('post.'));die;
        $id = trim(I('post.id'));
        $data['user_realusername'] = trim(I('post.real_name'));
        $data['user_orgid']        = trim(I('post.user_orgid'));
        $data['user_class']        = trim(I('post.user_group'));
        $data['user_name']         = trim(I('post.user_name'));
        $data['user_zhiwu']        = I("post.user_zhiwu");
        $data['user_mobile']       = I("post.user_mobile");
        $data['user_password']     = I("post.user_password");

        if(empty($data['user_realusername']))  exit(makeStandResult(1,'请输入姓名'));
        if(empty($data['user_name']))  exit(makeStandResult(1,'请输入帐号'));
        if(empty($data['user_password']))  exit(makeStandResult(1,'请输入密码'));
        $model     = M('sysuser');
        $xmMoel    = M("xmps_xm");

        // 若专家属于多个分组，用英文逗号分隔
        $user_class         = $data['user_class'];
        $user_class         = str_replace('，',',',$user_class);
        $data['user_class'] = $user_class;

        // 查询所有与专家同分组的项目（编辑关联表使用）
        $user_class         = explode(',',$user_class);
        $xmMap['xm_class']  = ["in",$user_class];
        $xmMap['xm_status'] = ["eq","正常"];
        $xmData = $xmMoel->where($xmMap)->select();
        $relation = M("xmps_xmrelation");
//        echo $xmMoel->_sql();die;
        //为空则添加
        if(empty($id)){
            $map['user_name']     = $data['user_name'];
            $map['user_password'] = $data['user_password'];
            $map['user_isdelete'] = 0;
            // 查询是否已存在相同用户名用户
            $isexist= $model->where($map)->find();
            if(!empty($isexist)) {
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(2,'账号已存在'));
            }
            $data['user_name']       = trim(I('post.user_name'));
            $data['user_createtime'] = time();
            $data['user_id']         = makeGuid();
            $data['user_createuser'] = session('user_id');
            $data['user_enable']     = '启用';
            $data['user_issystem']   = '否';
            $data['user_role']       = $this->roleid;
            $res = $model->add($data);
            // 关联表新增数据
            foreach($xmData as $xm){
                $item['xr_id']      = makeGuid();
                $item['xr_status']  = "进行中";
                $item['xr_user_id'] = $data['user_id'];
                $item['xr_xm_id']   = $xm['xm_id'];
                $relation->add($item);
            }
            // 用户角色表新增用户专家角色数据
            $adduserauth                  = [];
            $adduserauth["ua_id"]         = makeGuid();
            $adduserauth["ua_roleid"]     = $this->roleid;
            $adduserauth["ua_userid"]     = $data['user_id'];
            $adduserauth["ua_createtime"] = time();
            $adduserauth["ua_createuser"] = $data['safe'];
            M("userauth")->add($adduserauth);
            if(empty($res)){
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'添加失败'));
            }else{
                $this->addLog('sysuser','三员操作日志','add','新增用户'.$data['user_realusername'],'成功');
                exit(makeStandResult(0,'添加成功'));
            }
        }else{ // 修改用户信息
            $data['user_lastmodifytime']  = time();
            $data['user_lastmodifyuser']  = session('user_id');
            $data['user_secretlevelcode'] = md5($id.trim(I('post.user_secretlevel')));
            $data['user_enable']          = '启用';
            $map['user_name']             = $data['user_name'];
            $map['user_password']         = $data['user_password'];
            $map['user_isdelete']         = 0;

            // 查询是否已存在相同用户名用户
            $isexist = $model->where($map)->find();
            if($id!=$isexist["user_id"]&&!empty($isexist))
            {
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(3,'账号已存在'));
            }
            $res = $model->where("user_id='%s'", $id)->save($data);
            // 若修改了用户分类，则关联表删除旧数据，重新新增数据
            if($data['user_class']!=$isexist['user_class']){
                $relation->where("xr_user_id='$id'")->delete();
                foreach($xmData as $xm){
                    $item['xr_id']      = makeGuid();
                    $item['xr_status']  = "进行中";
                    $item['xr_user_id'] = $id;
                    $item['xr_xm_id']   = $xm['xm_id'];
                    $relation->add($item);
                }
            }

            if(empty($res)){
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'失败');
                exit(makeStandResult(-1,'修改失败'));
            }else{
                $this->addLog('sysuser','三员操作日志','update','修改用户'.$data['user_realusername'],'成功');
                exit(makeStandResult(0,'修改成功'));
            }
        }
    }

    /**
     * 导出
     */
    public function export(){
        $queryParam = I('get.');
        $data = $this->getData(true,$queryParam);
//        dump($data);die;
        $header = array('姓名','账号','单位','职务/职称','联系方式','分组');
        $width = Array('10',"15","15",'15','15','20','15');
        echo excelExport($header, $data, true,$width);
    }

    /**
     * 专家导入
     */
    public function import(){
        if(IS_POST){
//            dump($_FILES);die;
            $Model  = M("sysuser");
            $file   = uploadFile("file");
            $path   = "./Public/".$file['message'];
            $import = excelImport($path);
            $str    = "";// 帐号密码重复人员
            $column = ["姓名","账号",'密码',"单位",'职务/职称','联系方式',"分组"];
            $len    = count($column);
            for($i=0;$i<$len;$i++){
                if($column[$i]!=$import['column'][$i]){
                    exit("请按照模板填写导入数据，保持列头一致");
                }
            }
            foreach($import['data'] as $val){
                if($val['B']!=NULL && $val['C']!=NULL && $val['A']!=NULL && $val['D']!=NULL){ // 判断前四列不为空时新增用户
                    // 查询是否已存在相同用户名、密码用户
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
                        $data['user_role']        = $this->roleid;
                        // 处理user_class
                        $user_class               = $data['user_class'];
                        $user_class               = str_replace('，',',',$user_class);
                        $data['user_class']       = $user_class;
                        $user_class               = explode(',',$user_class);
                        $Model->add($data);
                        // 关联表新增数据
                        $xmMoel                   = M("xmps_xm");
                        $xmMap['xm_status']       = Array("eq","正常");
                        $xmMap['xm_class']        = Array("in",$user_class);
                        $xmData                   = $xmMoel->where($xmMap)->select();
                        $relation = M("xmps_xmrelation");
                        foreach($xmData as $xm){
                            $item['xr_id'] = makeGuid();
                            $item['xr_status'] = "进行中";
                            $item['xr_user_id'] = $data['user_id'];
                            $item['xr_xm_id'] = $xm['xm_id'];
                            $relation->add($item);
                        }
                        // 用户角色表新增用户专家角色数据
                        $adduserauth                  = array();
                        $adduserauth["ua_id"]         = makeGuid();
                        $adduserauth["ua_roleid"]     = $this->roleid;
                        $adduserauth["ua_userid"]     = $data['user_id'];
                        $adduserauth["ua_createtime"] = time();
                        $adduserauth["ua_createuser"] = $data['safe'];
                        M("userauth")->add($adduserauth);
                    }else{
                        $str .= $re['user_name'].",";
                    }
                }else{
                    exit("姓名,账号,密码,单位不能为空");
                }
            }
            $str = rtrim($str,",");
            if($str == ""){
                exit(0);
            }else{
                exit("账号：".$str."已存在");
            }
        }else{
            $this->display();
        }
    }

    /**
     * 删除专家
     */
    public function delSysUser(){
        $ids      = I('post.ids');
        if(empty($ids)) exit(makeStandResult(1,'参数缺少'));
        $id       = explode(',', $ids);
        $idStr    = "'".implode("','", $id)."'";
        $model    = M('sysuser');
        $userauth = M('userauth');
        try{
            $model->startTrans();
            foreach($id as $key=>$val)
            {
                $tem= $model->where("user_id='%s'",$val)->find();
                if(!empty($tem))
                {
                    $this->addLog('sysuser','三员操作日志','delete','删除用户'.$tem['user_realusername'],'成功');
                }
            }
            $userauth->where("ua_userid in ($idStr)")->delete(); // 删除用户权限
            $model -> where("user_id in ($idStr)")->delete();    // 删除用户
            M('xmps_xmrelation')-> where("xr_user_id in ($idStr)")->delete(); // 删除关联表信息

            $model->commit();
            exit(makeStandResult(0,'删除成功'));
        }catch (Exception $e){
            $model->rollback();
            exit(makeStandResult(2,'删除失败，错误详情'.$e));
        }
    }

    /**
     * 添加浏览专家
     */
    public function addViewExpert(){
        $num = trim(I('post.number'));
        $model = M('sysuser');
        try{
            $model->startTrans();
            // 先删除现有的浏览专家
            $model->where("user_role = '%s'",$this->viewid)->delete();
            M("userauth")->where("ua_roleid = '%s'",$this->viewid)->delete();
            for($i=1;$i<=$num;$i++){
                // 用户表新增数据
                $data['user_name']         = "L".$i;
                $data['user_realusername'] = "浏览专家".$i;
                $data['user_password']     = "1";
                $data['user_createtime']   = time();
                $data['user_id']           = makeGuid();
                $data['user_createuser']   = session('user_id');
                $data['user_enable']       = '启用';
                $data['user_issystem']     = '否';
                $data['user_role']         = $this->viewid;
                $res = $model->add($data);

                // 用户角色表新增浏览专家角色数据
                $adduserauth                  = [];
                $adduserauth["ua_id"]         = makeGuid();
                $adduserauth["ua_roleid"]     = $this->viewid;
                $adduserauth["ua_userid"]     = $data['user_id'];
                $adduserauth["ua_createtime"] = time();
                $adduserauth["ua_createuser"] = $data['safe'];
                M("userauth")->add($adduserauth);
            }
            $model->commit();
            exit(makeStandResult(0,'添加成功'));
        }catch (Exception $e){
            $model->rollback();
            exit(makeStandResult(1,'添加失败,错误信息：'.$e));
        }
    }

    /**
     * 选择项目展示页面
     */
    public function setXM(){
        $id      = I("get.id");
        $xmModel = M('xmps_xm');
        // 查询所有项目
        $xmdata = $xmModel->field("xm_id,xm_code,xm_name")
            ->order("xm_class,xm_ordernum")
            ->select();

        $Model = D("xmps_xmrelation");
        $map   = [];
        $map['xr_user_id']    = Array("eq", $id);
        // 查询已选择项目
        $selectedData = $Model
            ->alias("t")
            ->where($map)
            ->field("xr_user_id,xm_id,xm_code,xm_name,xr_id,xm_company,xm_class")
            ->join("left join xmps_xm m on t.xr_xm_id=m.xm_id")
            ->order("xm_class,xm_ordernum")
            ->select();
//        dump($selectedData);die;

        $this->assign("userId",$id);
        $this->assign("data",json_encode($selectedData));
        $this->assign("xmdata",$xmdata);
        $this->getDic();
        $this->display();
    }

    //备选项目（已不用）
    public function getAlternativeData()
    {
        $param = json_decode(file_get_contents("php://input"), true);
        if($param['name']!=""){
            $map['xm_code'] = ["eq",$param['name']];
        }
        if($param['class'] != ''){
            $map['xm_class'] = ["eq",trim($param['class'])];
        }
        $Model = M("xmps_xm");
        $data = $Model->field("xm_id,xm_code,xm_name,xm_company,xm_class")->where($map)->order("xm_class,xm_ordernum")->select();
        $this->ajaxReturn($data);
    }

    /**
     * 保存选择的项目
     */
    public function saveZhuanjiaData()
    {
        $param = I("post.");
        $XR    = M("xmps_xmrelation");
        try{
            $XR->startTrans();
            $oldRelation = $XR->field("xr_id,xr_xm_id")->where("xr_user_id='%s'",trim($param['user_id']))->select(); // 查找旧关联表信息
			$oldRelations = [];
			foreach($oldRelation as $key => $val){
				$oldRelations[$val['xr_xm_id']] = $val['xr_id'];
			}
            $xmIds = I('post.xmid');
            foreach ($xmIds as $key => $val) {
				if(array_key_exists($val,$oldRelations)){
					unset($oldRelations[$val]);
				}else{
					$data['xr_user_id'] = $param['user_id'];
					$data['xr_xm_id']   = $val;
					$data['xr_id'] = makeGuid();
					$data['xr_status'] = "进行中";
					$XR->add($data);
				}
            }
			if(!empty($oldRelations)) $XR->where(['xr_id'=>['in',$oldRelations]])->delete(); // 删除旧关联表信息
            $XR->commit();
            exit(makeStandResult(0,'保存成功'));
        }catch(\Exception $e){
            $XR->rollback();
            exit(makeStandResult(1,'保存失败，错误信息：'.$e));
        }
    }
}