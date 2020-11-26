<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/7
 * Time: 14:29
 */
namespace Admin\Controller;

use Think\Controller;

class XMController extends BaseController
{
    /**
     * 项目管理页面
     */
    public function index()
    {
        $this->getDic();
        $this->display();
    }

    /**
     * 项目新增编辑页面
     */
    public function editXm()
    {
        $id = I("get.id");
        if($id){
            $Task = M("xmps_xm");
            $data = $Task->where("xm_id='$id'")->find();
            $this->assign("xmData", $data);
            $this->assign("xmId", $id);
        }
        $this->display();
    }

    /**
     * 项目选择专家保存方法（旧）
     */
    public function saveXMDataOld()
    {
        $param = I("post.");
        $XR = M("xmps_xmrelation");
        $xrData = $XR->field("xr_id")->where("xr_xm_id='" . $param['xm_id'] . "'")->select();
        $postuserdata = explode(",", $param['userid']);
        $postxrdata   = explode(",", $param['xrid']);
        foreach ($postuserdata as $key => $val) {
            if ($postxrdata[$key] == "") {
                $data['xr_user_id'] = $val;
                $data['xr_xm_id'] = $param['xm_id'];
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

    /**
     * 项目选择专家保存方法
     */
    public function saveXMData()
    {
        $param = I("post.");
        $XR    = M("xmps_xmrelation");
        try{
            $XR->startTrans();
            $oldRelation = $XR->field("xr_id,xr_user_id")->where("xr_xm_id='%s'",trim($param['xm_id']))->select(); // 查找旧关联表信息
			$oldRelations = [];
			foreach($oldRelation as $key => $val){
				$oldRelations[$val['xr_user_id']] = $val['xr_id'];
			}
            $userIds = I('post.userid');
            foreach ($userIds as $key => $val) {
				if(array_key_exists($val,$oldRelations)){
					unset($oldRelations[$val]);
				}else{
					$data['xr_user_id'] = $val;
					$data['xr_xm_id']   = $param['xm_id'];
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

    /**
     * 项目信息保存方法
     */
    public function saveXm(){
        $param         = I("post.");
        $Model         = M("xmps_xm");
        $relationModel = M("xmps_xmrelation");
        $userModel     = M("sysuser");
        // 查询是否存在相同的项目编号
        $xm_code = $Model
            ->field("xm_code,xm_id")
            ->where("xm_code='".$param['xm_code']."' and xm_id!='".$param['xm_id']."'")
            ->find();
        if(!empty($xm_code)){
            echo makeStandResult("1", "已存在相同的项目编号");die;
        }
        // 查询同分类下的专家
        $userMap['is_delete']   = Array("eq","0");
        $userMap['is_enable']   = Array("eq","启用");
        $userMap['is_issystem'] = Array("eq","否");
        $userMap['user_class']  = Array("like","%".$param['xm_class']."%");
        $userData = $userModel->where($userMap)->select();
        try{
            $Model->startTrans();
            if (!$param['xm_id']) { // 新增
                $param['xm_id'] = makeGuid();
                $Model->add($param);
                foreach($userData as $user){
                    $item['xr_id'] = makeGuid();
                    $item['xr_user_id'] = $user['user_id'];
                    $item['xr_xm_id'] = $param['xm_id'];
                    $item['xr_status'] = "进行中";
                    $relationModel->add($item);
                }
            } else { // 修改
                $xm_class = $Model->where("xm_id='".$param['xm_id']."'")->getField("xm_class");
                $Model->where("xm_id='".$param['xm_id']."'")->save($param);
                if($xm_class!=$param['xm_class']){ // 若修改了项目分类
                    $relationModel->where("xr_xm_id='".$param['xm_id']."'")->delete();
                    foreach($userData as $user){
                        $item['xr_id'] = makeGuid();
                        $item['xr_user_id'] = $user['user_id'];
                        $item['xr_xm_id'] = $param['xm_id'];
                        $item['xr_status'] = "进行中";
                        $relationModel->add($item);
                    }
                }
            }
            // 查询同分组下是否答辩顺序重复
            $count = $Model
                ->where("xm_class='".$param['xm_class']."' and xm_ordernum='".$param['xm_ordernum']."' and xm_type = '".$param['xm_type']."'")
                ->count();
            $isSame = 0;
            if($count>1) $isSame = 1;
            $Model->commit();
            if($isSame == 0){
                echo makeStandResult("0", "保存成功!");die;
            }else{
                echo makeStandResult("99", "保存成功，当前分组下答辩顺序重复");die;
            }
        }catch(Exception $e){
            $Model->rollback();
            echo makeStandResult("2", "保存失败,请关闭重试");die;
        }
    }

    /**
     * 项目信息保存方法(不验证是否存在相同的项目编号)
     */
    public function saveXmSameCode(){
        $param         = I("post.");
        $Model         = M("xmps_xm");
        $relationModel = M("xmps_xmrelation");
        $userModel     = M("sysuser");
        // 查询同分类下的专家
        $userMap['is_delete']   = Array("eq","0");
        $userMap['is_enable']   = Array("eq","启用");
        $userMap['is_issystem'] = Array("eq","否");
        $userMap['user_class']  = Array("like","%".$param['xm_class']."%");
        $userData = $userModel->where($userMap)->select();
        try{
            $Model->startTrans();
            if (!$param['xm_id']) { // 新增
                $param['xm_id'] = makeGuid();
                $Model->add($param);
                foreach($userData as $user){
                    $item['xr_id'] = makeGuid();
                    $item['xr_user_id'] = $user['user_id'];
                    $item['xr_xm_id'] = $param['xm_id'];
                    $item['xr_status'] = "进行中";
                    $relationModel->add($item);
                }
            } else { // 修改
                $xm_class = $Model->where("xm_id='".$param['xm_id']."'")->getField("xm_class");
                $Model->where("xm_id='".$param['xm_id']."'")->save($param);
                if($xm_class!=$param['xm_class']){ // 若修改了项目分类
                    $relationModel->where("xr_xm_id='".$param['xm_id']."'")->delete();
                    foreach($userData as $user){
                        $item['xr_id'] = makeGuid();
                        $item['xr_user_id'] = $user['user_id'];
                        $item['xr_xm_id'] = $param['xm_id'];
                        $item['xr_status'] = "进行中";
                        $relationModel->add($item);
                    }
                }
            }
            // 查询同分组下是否答辩顺序重复
            $count = $Model
                ->where("xm_class='".$param['xm_class']."' and xm_ordernum='".$param['xm_ordernum']."' and xm_type = '".$param['xm_type']."'")
                ->count();
            $isSame = 0;
            if($count>1) $isSame = 1;
            $Model->commit();
            if($isSame == 0){
                echo makeStandResult("0", "保存成功!");die;
            }else{
                echo makeStandResult("99", "保存成功，当前分组下答辩顺序重复");die;
            }
        }catch(Exception $e){
            $Model->rollback();
            echo makeStandResult("2", "保存失败,请关闭重试");die;
        }
    }

    /**
     * 项目选择评委页面
     */
    public function setExt(){
        $id = I("get.id");
        $this->getDic();
        $model = M('sysuser');
        // 查询所有专家
        $userdata = $model->field("user_id,user_realusername")
            ->where(" user_issystem != '是' and user_isdelete ='0' and user_role = '".C('PROFESSERID')."'")
            ->order("user_realusername")
            ->select();
        $Model = D("xmps_xmrelation");
        $map   = [];
        $map['xr_xm_id']      = Array("eq", $id);
        $map['user_enable']   = Array("eq", "启用");
        $map['user_issystem'] = Array("eq", "否");
        $map['user_isdelete'] = Array("neq", "1");
        $map['user_role']     = Array("eq", C('PROFESSERID'));
        // 查询已选择专家
        $selectedData = $Model
            ->alias("t")
            ->where($map)
            ->field("user_id,user_realusername,user_class,user_orgid,xr_id")
            ->join("left join sysuser m on t.xr_user_id=m.user_id")
            ->select();

        $this->assign("xmId", $id);
        $this->assign("userdata", $userdata);
        $this->assign("data", json_encode($selectedData));
        $this->display();
    }

    private function _filter($param)
    {
        $map = Array();
        if ($param['year'] != "") {
            $map['xm_year'] = Array("eq", $param['year']);
        }
        if ($param['class'] != "") {
            $map['xm_class'] = Array("eq",$param['class']);
        }
        if ($param['group'] != "") {
            $map['xm_group'] = Array("like",'%'.$param['group'].'%');
        }
        if ($param['type'] != "") {
            $map['xm_type'] = Array("eq",$param['type']);
        }
        if ($param['name'] != "") {
            $map['xm_name'] = Array("like", "%" . $param['name'] . "%");
        }
        if($param['code']!=""){
            $map['xm_code'] = Array("like", "%".$param['code']."%");
        }
        if($param['createuser']!=""){
            $map['xm_createuser'] = Array("like", "%".$param['createuser']."%");
        }
        if($param['company']!=""){
            $map['xm_company'] = Array("like", "%".$param['company']."%");
        }
        return $map;
    }

    public function getData()
    {
        $param = json_decode(file_get_contents("php://input"), true);
        $map = $this->_filter($param);
        $Model = D("xmps_xm");
        $data = $Model->where($map)->limit($param['offset'],$param['limit'])->order($param['sort']." ".$param['sortOrder'])->select();
//        echo $Model->_sql();die;
        $count = $Model->where($map)->count();
        $this->ajaxReturn(array( 'total' => $count,'rows' => $data));
    }

    //未选评委
    public function getAlternativeData(){
        $param = json_decode(file_get_contents("php://input"), true);
        if ($param['name'] != "") {
            $map['user_realusername'] = Array("like", "%" . $param['name'] . "%");
        }
        $map['user_enable']   = ["eq", "启用"];
        $map['user_issystem'] = ["eq", "否"];
        if($param['class'] != '') $map['user_class']    = ["eq",trim($param['class'])];
        $map['user_isdelete'] = ["neq","1"];
        $map['user_role']     = Array("eq", C('PROFESSERID'));
        $Model = M("sysuser");
        $data = $Model->field("user_id,user_realusername,user_class,user_orgid")->where($map)->select();
//        echo $Model->_sql();die;
        $this->ajaxReturn($data);
    }

    //已选评委
    public function selectedData()
    {
        $param = json_decode(file_get_contents("php://input"), true);
        $data=array();
        $Model = D("xmps_xmrelation");
        $Modeluser = D("sysuser");
        if ($param['name'] != "") {
            $map['user_realusername'] = Array("like", "%" . $param['name'] . "%");
        }
        $map['xr_xm_id']      = Array("eq", $param['xmId']);
        $map['user_enable']   = Array("eq", "启用");
        $map['user_issystem'] = Array("eq", "否");
        $map['user_isdelete'] = Array("neq", "1");
        $data = $Model
            ->alias("t")
            ->where($map)
            ->field("user_id,user_realusername,user_class,user_orgid,xr_id")
            ->join("left join sysuser m on t.xr_user_id=m.user_id")
            ->select();
        $this->ajaxReturn($data);
    }

    /**
     * 项目导入
     */
    public function import(){
        if(IS_POST){
            $Model = M("xmps_xm");
            try{
                $file = uploadFile("file",1024*1024*10);
                $path = "./Public/".$file['message'];
                $import = excelImport($path);

                $column = Array("项目编号","项目名称","依托单位","申请人","分组","类别","技术方向","推荐方式","答辩顺序","初审分组","初审得分","初审排名");
                $len = count($column);
                for($i=0;$i<$len;$i++){
                    if($column[$i]!=$import['column'][$i]){
                        die("请按照模板填写导入数据，保持列头一致");
                    }
                }
                $str  = "";

                foreach($import['data'] as $val){
                    if($val['B']!=NULL && $val['A']!=NULL  ){
                        $xm_code = $Model
                            ->field("xm_code,xm_id")
                            ->where("xm_code='".$val['A']."'")
                            ->getField("xm_code");
                        if($xm_code!=""){
                            $str .= $val['A'].",";
                        }else{
                            $data['xm_id'] = makeGuid();
                            $data['xm_code'] = $val['A'];
                            $data['xm_name'] = $val['B'];
                            $data['xm_company'] = $val['C'];
                            $data['xm_createuser'] = $val['D'];
                            $data['xm_class'] = $val['E'];
                            $data['xm_type'] = $val['F'];
                            $data['xm_group'] = $val['G'];
                            $data['xm_tmfs'] = $val['H'];
                            $data['xm_ordernum'] = $val['I'];
                            $data['xm_oldfenzu'] = $val['J'];
                            $data['xm_oldscore'] = $val['K'];
                            $data['xm_oldrank'] = $val['L'];
                            $data['xm_year'] = date("Y",time());
                            $Model->add($data);

                            $userModel = M("sysuser");
                            $userMap['is_delete']   = Array("eq","0");
                            $userMap['is_enable']   = Array("eq","启用");
                            $userMap['is_issystem'] = Array("eq","否");
                            $userMap['user_class']  = Array("eq",$data['xm_class']);
                            $userData = $userModel->where($userMap)->select();

                            $relationModel = M("xmps_xmrelation");
                            foreach($userData as $user){
                                $item['xr_id']       = makeGuid();
                                $item['xr_user_id'] = $user['user_id'];
                                $item['xr_xm_id']   = $data['xm_id'];
                                $item['xr_status']  = "进行中";
                                $relationModel->add($item);
                            }
                        }
                    }
                }
                if($str!=""){
                    echo "已存在的项目编号:".rtrim($str,",");
                }else{
                    echo "ok";
                }
            }catch(\Exception $e){
                echo "fail:$e";
            }
        }else{
            $this->display();
        }
    }
    /**
     * 项目导入（不验证是否存在相同的项目编号）
     */
    public function importSameCode(){
        if(IS_POST){
            $Model = M("xmps_xm");
            try{
                $file = uploadFile("file",1024*1024*10);
                $path = "./Public/".$file['message'];
                $import = excelImport($path);

                $column = Array("项目编号","项目名称","申报单位","申请人","分组","指南方向","答辩顺序");
                $len = count($column);
                for($i=0;$i<$len;$i++){
                    if($column[$i]!=$import['column'][$i]){
//                        dump($column[$i]);
//                        dump($import['column'][$i]);
                        die("请按照模板填写导入数据，保持列头一致");
                    }
                }
                $str  = "";

                foreach($import['data'] as $val){
                    if($val['B']!=NULL && $val['A']!=NULL  ){
//                        $xm_code = $Model
//                            ->field("xm_code,xm_id")
//                            ->where("xm_code='".$val['A']."'")
//                            ->getField("xm_code");
//                        if($xm_code!=""){
//                            $str .= $val['A'].",";
//                        }else{
                        $data['xm_id'] = makeGuid();
                        $data['xm_code'] = $val['A'];
                        $data['xm_name'] = $val['B'];
                        $data['xm_company'] = $val['C'];
                        $data['xm_createuser'] = $val['D'];
                        $data['xm_class'] = $val['E'];
                        $data['xm_type'] = '默认分类';
                        $data['xm_group'] = $val['F'];
//                            $data['xm_tmfs'] = $val['F'];
                        $data['xm_ordernum'] = $val['G'];
                        $data['xm_year'] = date("Y",time());
                        $Model->add($data);

                        $userModel = M("sysuser");
                        $userMap['is_delete']   = Array("eq","0");
                        $userMap['is_enable']   = Array("eq","启用");
                        $userMap['is_issystem'] = Array("eq","否");
                        $userMap['user_class']  = Array("eq",$data['xm_class']);
                        $userData = $userModel->where($userMap)->select();

                        $relationModel = M("xmps_xmrelation");
                        foreach($userData as $user){
                            $item['xr_id']       = makeGuid();
                            $item['xr_user_id'] = $user['user_id'];
                            $item['xr_xm_id']   = $data['xm_id'];
                            $item['xr_status']  = "进行中";
                            $relationModel->add($item);
                        }
//                        }
                    }
                }
//                if($str!=""){
//                    echo "已存在的项目编号:".rtrim($str,",");
//                }else{
                echo "ok";
//                }
            }catch(\Exception $e){
                echo "fail:$e";
            }
        }else{
            $this->display();
        }
    }

    /**
     * 项目导出
     */
    public function export(){
        $Model = D("xmps_xm");
        $param = I('get.');
        $map = $this->_filter($param);
        $data = $Model->field("xm_name,xm_code,xm_class,xm_type,xm_group,xm_company")->where($map)->order($param['sort']." ".$param['sortOrder'])->select();
        $column = Array(
            "项目名称",
            "项目编号",
            "项目分组",
            "项目类别",
            "项目技术方向",
            "依托单位",
        );
        $width = Array("10","40","20",'15','15','15','15');
        echo excelExport($column,$data,true,$width);
    }

    /**
     * 删除项目
     */
    public function deleteXm(){
        $ids   = I("post.ids");
        $Model = D("xmps_xm");
        $map['xm_id']     = Array("in",$ids);
        $mapR['xr_xm_id'] = Array("in",$ids);
        try{
            $Model->startTrans();
            $Model->where($map)->delete();
            M("xmps_xmrelation")->where($mapR)->delete();
            $Model->commit();
            echo makeStandResult("0", "删除成功");
        }catch (\Exception $e){
            $Model->rollback();
            echo makeStandResult("1", "删除失败,错误信息：$e");
        }
    }

    /**
     * 项目预览
     * 多个文件（包含在项目编号名称的文件夹中依次为文件名包含“指南”、“预算申报书”、“项目申报书”的展示）
     */
    public function listindexzd()
    {
        $xm_id    = I("get.xm_id");
        $model = M('xmps_xm');
        $xmdata=$model->where("xm_id='".$xm_id."'")->find();
        $xm_code        = trim($xmdata["xm_code"]);

        $parent_getpath = './Public/'. C("xmfilepath");
        $parent_pathcha = $parent_getpath;
        if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
            $parent_pathcha = iconv('UTF-8','gb2312',$parent_getpath);
        }
        $parent_file = scandir($parent_pathcha);
        foreach($parent_file as $key=>$val){
            if($val!='.' && $val!='..') {
                $encode = mb_detect_encoding($val, array("UTF-8", "GB2312"));
                if ($encode != "UTF-8") {
                    $val = iconv('gbk', 'utf-8', $val);
                }
                // 文件路径为文件夹名称等于项目编号
                if($val===$xm_code){
                    $path     = './Public/'. C("xmfilepath")."/".$val;
                    break;
                }
            }
        }

        $pathcha = $path;
        if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
            $pathcha = iconv('UTF-8','gb2312',$path);
        }

        $file = scandir($pathcha);
        $menu=array();
        $relativePath = $_SERVER['SCRIPT_NAME'];
        array_multisort($file);
        $hetongshu=array();
        $jianyishu=array();
        $xiangmujihua=array();
        foreach($file as $key=>$val){
            if($val!='.' && $val!='..') {
                $encode = mb_detect_encoding($val, array("UTF-8", "GB2312"));
                if ($encode != "UTF-8") {
                    $val = iconv('gbk', 'utf-8', $val);
                }

                if(strpos(strtolower($val),'.pdf')!==false){
                    if(strpos(trim($val),'指南')!==false){
                        $child = array(
                            'title' => '指南', //标题
                            'icon' => '&#xe63c;',//图标
                            'href' => '.' . $path . "/" . $val,//链接
                        );
                        array_push($hetongshu,$child);
                    }else if(strpos(trim($val),'申报')!==false){
                        $child = array(
                            'title' => '项目申报书', //标题
                            'icon' => '&#xe63c;',//图标
                            'href' => '.' . $path . "/" . $val,//链接
                        );
                        array_push($jianyishu,$child);
                    }else if(strpos(trim($val),'预算')!==false){
                        $child = array(
                            'title' => '预算申报书', //标题
                            'icon' => '&#xe63c;',//图标
                            'href' => '.' . $path . "/" . $val,//链接
                        );
                        array_push($xiangmujihua,$child);
                    }
                }
            }else{
                unset($file[$key]);
            }
        }
        $menu=array_merge($hetongshu,$xiangmujihua);
        $menu=array_merge($menu,$jianyishu);
        $this->assign('relativePath',$relativePath);
        $this->assign('ds_menu', json_encode($menu));
        $this->assign('showlable', $xmdata["xm_createuser"]."(".$xmdata["xm_company"].")".$xmdata["xm_code"]."_".$xmdata["xm_name"]);
        $this->display('listindex');
    }

    /**
     * 项目预览
     * 多个文件（包含在项目编号名称的文件夹中所有文件的展示，C("defaultfilename")置顶）
     */
    public function listindexs()
    {
        $xm_id    = I("get.xm_id");
        $model    = M('xmps_xm');
        $xmdata   = $model->where("xm_id='".$xm_id."'")->find();
        $path     = './Public/'. C("xmfilepath")."/".$xmdata["xm_code"];
        $file     = scandir($path);
        $filesort = []; // 排序文件用数组
        foreach($file as $fileSortName){
            if($fileSortName == '.' || $fileSortName == '..'){
                $filesort[] = $fileSortName;
            }else{
                if(strpos($fileSortName,'_') !== false){
                    // 若文件名称中有数字，按数字从小到大然后中文的顺序排序
                    $fileSortNames = explode('_',$fileSortName);
                    preg_match('/[0-9]+/',$fileSortNames[0],$fileSortNum);
                    if(!empty($fileSortNum)){
                        $filesort[] = $fileSortNum[0];
                    }else{
                        $filesort[] = $fileSortName;
                    }
                }else{
                    $filesort[] = $fileSortName;
                }
            }
        }

        array_multisort($filesort,$file);  // 根据刚刚保存的排序字符串排序
        $menu         = [];                      // 发送到前台的列表数组
        $relativePath = $_SERVER['SCRIPT_NAME']; // '/jwkjwplat/index.php' 入口文件地址
        $jianyishu    = [];                      // 建议书名称数组（用于置顶）
        $other        = [];                      // 其他文件名称数组
        $defname      = strtolower(C("defaultfilename")); // 配置文件配置的建议书文件名（用于置顶）
        foreach($file as $key=>$val){
            if($val!='.' && $val!='..') {
                // 检测文件编码，转码
                $encode = mb_detect_encoding($val, array("UTF-8", "GB2312"));
                if ($encode != "UTF-8") {
                    $val = iconv('gb2312', 'utf-8', $val);
                }
                // 项目建议书单独拿出来，一会用array_merge放最上面
                if(strtolower($val)==$defname){
                    array_push($jianyishu,$val);
                    unset($file[$key]);
                }else{
                    array_push($other,$val);
                }
            }else{
                unset($file[$key]);
            }
        }
        $file = array_merge($jianyishu,$other);
        // 生成页面左侧列表数组
        foreach($file as $val) {
            $temparr = explode(".", $val);
            $houzhui = $temparr[count($temparr) - 1];
            $title = str_replace('.' . $houzhui, '', $val);
            $child = array(
                'title' => $title, //标题
                'icon' => '&#xe63c;',//图标
                'href' => '.' . $path . "/" . $val,//链接
            );
            array_push($menu, $child);
        }
        $this->assign('relativePath',$relativePath);
        $this->assign('ds_menu', json_encode($menu));
        $this->assign('showlable', $xmdata["xm_createuser"]."(".$xmdata["xm_company"].")".$xmdata["xm_code"]."_".$xmdata["xm_name"]);
        $this->display('listindex');
    }

    /**
     * 项目预览
     * 单个文件（展示pdf格式且文件名称为项目名称的文件）
     */
    public function listindex()
    {
        $xm_id        = I("get.xm_id");
        $model        = M('xmps_xm');
        $xmdata       = $model->where("xm_id='".$xm_id."'")->find();
        $path         = './Public/'. C("xmfilepath");
        $file         = scandir($path);
        $menu         = [];                      // 发送到前台的列表数组
        $relativePath = $_SERVER['SCRIPT_NAME']; // '/jwkjwplat/index.php' 入口文件地址
        array_multisort($file);            // 排序
        $other        = [];                      // 文件名称数组
        foreach($file as $key=>$val){
            if($val!='.' && $val!='..') {
                // 检测文件编码，转码
                $encode = mb_detect_encoding($val, array("UTF-8", "GB2312"));
                if ($encode != "UTF-8") {
                    $val = iconv('gb2312', 'utf-8', $val);
                }
                array_push($other,$val);
            }else{
                unset($file[$key]);
            }
        }
        // 生成页面左侧列表数组
        foreach($other as $val) {
            $temparr = explode(".", $val);
            if($temparr[0] == $xmdata['xm_name'] && $temparr[1] == 'pdf'){
                $houzhui = $temparr[count($temparr) - 1];
                $title = str_replace('.' . $houzhui, '', $val);
                $child = array(
                    'title' => $title, //标题
                    'icon' => '&#xe63c;',//图标
                    'href' => '.' . $path . "/" . $val,//链接
                );
                array_push($menu, $child);
            }
        }
        $this->assign('relativePath',$relativePath);
        $this->assign('ds_menu', json_encode($menu));
        $this->assign('showlable', $xmdata["xm_createuser"]."(".$xmdata["xm_company"].")".$xmdata["xm_code"]."_".$xmdata["xm_name"]);
        $this->display();
    }

    /**
     * 拖动排序页面
     */
    function sortByClass(){
        $class = I('get.classid');
        if(empty($class)) echo "<script>alert('参数缺失，请重试');var index = parent.layer.getFrameIndex(window.name);parent.layer.close(index);</script>";
        $map = [];
        $map['xm_class']  = ['eq',$class];
        $data = M('xmpsXm')->where($map)->order(['xm_ordernum'=>'asc'])->select();
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 拖动排序保存
     */
    function saveOrder(){
        $orderData = I('post.data');
        $Model     = M('xmps_xm');
        if($orderData){
            try{
                $Model->startTrans();
                // 查询数据是否已改变
//                $ids = array_keys($orderData);
//                $ids = implode("','",$ids);
//                $orders = $Model->field('xm_class')->where("xm_id in ('".$ids."')")->select();
//                $orders = removeArrKey($orders,'xm_class');
//                $orders = array_unique($orders);
//                if(count($orders) != 1){
//                    exit(makeStandResult(1,'项目分组已更改，请刷新后再进行分组排序！'));
//                }
                // 遍历接收到的数组挨个保存排序号
                foreach($orderData as $id=>$order){
                    $data = [];
                    $data['xm_ordernum'] = $order;
                    $Model->where("xm_id = '".$id."'")->setField($data);
                }
                $Model->commit();
                exit(makeStandResult(0,'操作成功！'));
            }catch(Exception $e){
                $Model->rollback();
                exit(makeStandResult(1,'操作失败：$e'));
            }
        }
    }
}