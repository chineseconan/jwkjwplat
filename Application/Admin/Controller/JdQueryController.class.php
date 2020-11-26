<?php
namespace Admin\Controller;
use Think\Controller;
class JdQueryController extends BaseController
{

    function _initialize(){
        $this->professorid = C('PROFESSERID');
    }

    /**
     * 进度查询页
     */
    public function index()
    {
        $model = M('xmps_xm');
        $where = [];
        $data = $model->field('xm_id,xm_name,xm_code')->order("xm_code asc")->where($where)->select();
        $this->assign("xmdata", $data);
        $this->getDic();
        $this->display();
    }
    /**
     * 投票统计(相同xm_group合并显示)
     */
    public function voteIndex()
    {
        $model = M('xmps_xm');
        $where = [];
        $data = $model->field('xm_id,xm_name,xm_code,xm_createuser')->order("xm_code asc")->where($where)->select();
        foreach($data as $key=>$val){
            $data[$key]['xm_codes'] = $val['xm_code'] . '-' . $val['xm_createuser'];
            $data[$key]['xm_names'] = $val['xm_name'] . '-' . $val['xm_createuser'];
        }
        $this->assign("xmdata", $data);
        $this->display();
    }

    /**
     * 进度查询页
     */
    public function view()
    {
        $model = M('xmps_xm');
        $where = [];
        $data = $model->field('xm_id,xm_name,xm_code')->order("xm_code asc")->where($where)->select();
        $this->assign("xmdata", $data);
        $this->getDic();
        $this->display();
    }

    /**
     * 专家进度查询页
     */
    public function viewExpert()
    {
        $userModel = M("sysuser");
        $where = [];
        $where['user_isdelete'] = ['eq', '0'];
        $where['user_issystem'] = ['eq', '否'];
        $where['user_role']     = ['eq', C("PROFESSERID")];
        $data = $userModel->field('user_id,user_name,user_realusername')->where($where)->order("user_class asc")->select();
        $this->assign("user", $data);
        $this->getDic();
        $this->display();
    }

    /**
     * 获取专家进度查询列表
     */
    public function getExportData()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where      = [];
        $xm_user    = trim($queryParam['xm_user']);
        if (!empty($xm_user)) {
            $where['xr_user_id'] = ['eq', $xm_user];
        }
        $model = M('xmps_xmrelation');
        $data = $model->field('
            user_id,user_realusername,user_name,xr_status
        ')
            ->join("inner join sysuser u on xmps_xmrelation.xr_user_id=u.user_id")
            ->where($where)
            ->group("user_id,user_realusername,user_name,xr_status")
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        $count = $model->field("count(*) c")
        ->where($where)
        ->group("xr_user_id")
        ->select();
        echo json_encode(array('total' => count($count), 'rows' => $data));
    }
    /**
     * 获取进度列表
     */
    public function getData()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $xm_id = trim($queryParam['xm_id']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_class'])) {
            $where['xm_class'] = ['eq', $queryParam['xm_class']];
        }
        if (!empty($queryParam['xm_type'])) {
            $where['xm_type'] = ['eq', $queryParam['xm_type']];
        }
        if (!empty($queryParam['xm_group'])) {
            $where['xm_group'] = ['like', '%'.$queryParam['xm_group'].'%'];
        }
        if (!empty($queryParam['xm_code'])) {
            $where['xm_code'] = ['eq', $queryParam['xm_code']];
        }
        $model = M('xmps_xm');
        $data = $model->field('xm_id,
        xm_code,
        xm_tmfs,
        xm_name,
        xm_company,
        xm_createuser,
        xm_class,
        xm_type,
        vote1option,
        vote2option,
        vote3option,
        case when wanchengcount is null then 0 else wanchengcount end wanchengcount,
        case when allcount is null then 0 else allcount end allcount,
        0+cast(num as char) as num,
        ps_detail
        /* 投票字段*/
        ,case when wanchengvote1count is null then 0 else wanchengvote1count end wanchengvote1count,
        case when vote1num is null then 0 else vote1num end vote1num,
        0+cast(vote1rate as char) as vote1rate,
        case when wanchengvote2count is null then 0 else wanchengvote2count end wanchengvote2count,
        case when vote2num is null then 0 else vote2num end vote2num,
        0+cast(vote2rate as char) as vote2rate,
        case when wanchengvote3count is null then 0 else wanchengvote3count end wanchengvote3count,
        case when vote3num is null then 0 else vote3num end vote3num,
        0+cast(vote3rate as char) as vote3rate
        ')
            ->join("left join (select xr_xm_id,count(xr_id) wanchengcount from xmps_xmrelation a where xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id")
            ->join("left join (select xr_xm_id,count(xr_id) allcount,max(avgvalue) as num,max(ps_detail) ps_detail,max(vote1rate) vote1rate,max(vote2rate) vote2rate,max(vote3rate) vote3rate from xmps_xmrelation a group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id")
            // 投票信息
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote1count, sum(vote1) as vote1num from xmps_xmrelation a where  vote1status = '已完成' group by xr_xm_id) v1 on xmps_xm.xm_id = v1.xr_xm_id")
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote2count, sum(vote2) as vote2num from xmps_xmrelation a where vote2status = '已完成' group by xr_xm_id) v2 on xmps_xm.xm_id = v2.xr_xm_id")
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote3count, sum(vote3) as vote3num from xmps_xmrelation a where vote3status = '已完成' group by xr_xm_id) v3 on xmps_xm.xm_id = v3.xr_xm_id")
            ->where($where)
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        $count = $model->where($where)->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }

    /**
     * 获取投票统计列表
     */
    public function getDataVote()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $xm_id = trim($queryParam['xm_id']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_code'])) {
            $where['xm_id'] = ['eq', $queryParam['xm_code']];
        }
        $model = M('xmps_xm');
        $xmData = $model->field("xm_group,group_concat(xr_status) xrstatusall,group_concat(vote1status) vote1statusall")
            ->join("left join (select xr_xm_id,xr_status,vote1status from xmps_xmrelation) t on xm_id = t.xr_xm_id ")
            ->where($where)
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->group('xm_group')
            ->select();
//        echo $model->_sql();
//        dump($xmData);die;
        $count = $model->field("xm_group")->where($where)->group('xm_group')->select();
        foreach($xmData as $key=>$xmInfo){
            $xm_group = $xmInfo['xm_group'];
            $where['xm_group'] = ['eq', $xm_group];
            $data = $model->field("xm_id,xm_code,xm_tmfs,xm_name,xm_company,xm_createuser,xm_class,voteoptionbx,
        case when wanchengcount is null then 0 else wanchengcount end wanchengcount,
        case when allcount is null then 0 else allcount end allcount,
        case when agreecount is null then 0 else agreecount end agreecount,
        voterate,avgvalue")
                ->join("left join (select xr_xm_id,count(xr_id) wanchengcount from xmps_xmrelation where xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id") // 查询投票完成数量
                ->join("left join (select xr_xm_id,count(xr_id) allcount,max(voterate) as voterate,max(avgvalue) as avgvalue from xmps_xmrelation group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id") // 查询全部专家任务数量、得票率、平均分
                ->join("left join (select xr_xm_id,count(xr_id) agreecount  from xmps_xmrelation where ps_zz = 1 group by xr_xm_id) c on xmps_xm.xm_id=c.xr_xm_id") // 查询同意票数
                ->where($where)
                ->order("agreecount desc,avgvalue desc,xm_ordernum asc")
                ->select();
//            echo $data;die;
//            并行投票拼接列
            foreach($data as $k=>$item){
                if($item['voteoptionbx'] == '1'){ // 若开启了并行投票
                    $wheres = $where;
                    $wheres['xm_id'] = ['eq',$item['xm_id']];
                    $bxDetail = $model->field("
        case when wanchengcountbx is null then 0 else wanchengcountbx end wanchengcountbx,
        case when agreecountbx is null then 0 else agreecountbx end agreecountbx,
        vote1rate,vote1status,bxtime1count,bxtime2count")
                        ->join("left join (select xr_xm_id,count(xr_id) wanchengcountbx from xmps_xmrelation a where vote1status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id") // 查询投票完成数量
                        ->join("left join (select xr_xm_id,max(vote1rate) as vote1rate,group_concat(vote1status) vote1status from xmps_xmrelation a group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id") // 查询得票率
                        ->join("left join (select 
                                xr_xm_id,
                                count(xr_id) agreecountbx,
                                sum(CASE WHEN bx_time = 1 THEN 1 ELSE 0 END) bxtime1count,  
                                sum(CASE WHEN bx_time = 2 THEN 1 ELSE 0 END) bxtime2count
                            from xmps_xmrelation a where vote1 = '1' group by xr_xm_id) c on xmps_xm.xm_id=c.xr_xm_id") // 查询同意“并行”票数，支持时间得票数
                        ->where($wheres)
                        ->find();
//                    echo $model->_sql();die;
                    $data[$k] = $data[$k]+$bxDetail;
                }
            }

            $xmData[$key]['mxData'] = $data;
        }
        echo json_encode(array('total' => count($count), 'rows' => $xmData));
    }

    /**
     * 选择并行投票的项目（投票）
     */
    public function votechoosexm()
    {
        $xmGroup = I('get.xmGroup');
        if(empty($xmGroup)) exit(makeStandResult(2,'参数缺失，请刷新页面重试！'));
        $this->assign('xmGroup', $xmGroup);
        $this->display();
    }

    /**
     * 选择并行投票的项目列表数据（投票）
     */
    public function getvotexmdata()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $where['xm_group']   = ['eq', trim($queryParam['xmgroup'])];
        $model = M('xmps_xm');
        if($queryParam['sort'] == 'agreecount' && $queryParam['sortOrder'] == 'desc'){
            $order = "agreecount desc,avgvalue desc";
        }else{
            $order = $queryParam['sort'] . " " . $queryParam['sortOrder'];
        }
        $data = $model->field("xm_id,xm_code,xm_tmfs,xm_name,xm_ordernum,xm_company,xm_createuser,xm_class,agreecount,avgvalue")
            ->join("inner join (select xr_xm_id,max(avgvalue) as avgvalue from xmps_xmrelation where xr_status = '完成' group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id")
            ->join("left join (select xr_xm_id,count(xr_id) agreecount from xmps_xmrelation where ps_zz = 1 and xr_status = '完成' group by xr_xm_id) c on xmps_xm.xm_id=c.xr_xm_id") // 查询同意票数
            ->where($where)
            ->order($order)
            ->select();
//        echo $data;die;
        echo json_encode(array('total' => count($data), 'rows' => $data));
    }


    /**
     * 开启并行投票（重点）
     */
    public function beginVoteBX(){
        $xmGroup = I('post.xmGroup');
        $data    = I('post.data');
        if(empty($xmGroup) || empty($data)) exit(makeStandResult(1,"参数缺失，请联系管理员！"));
        $model   = M('xmps_xmrelation');
        try {
            $model->startTrans();
            // 验证是否已经开启过投票
            $where = [];
            $where['xm_group']    = ['eq', $xmGroup];
//            $where['vote_res']    = ['eq', '并行投票'];
            $where['xr_status']   = ['eq', '完成'];
            $where['vote1status'] = ['neq', '未开始'];
            $isStartBX = $model->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
            if ($isStartBX != 0) {
                $model->commit();
                exit(makeStandResult(2, '该项目编号的项目已开启过并行投票，不能重复开启！'));
            }
            // 验证是否打分完成
            $where = [];
            $where['xm_group']    = ['eq', $xmGroup];
            $where['xr_status']   = ['neq', '完成'];
            $isStartBX = $model->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
            if ($isStartBX != 0) {
                $model->commit();
                exit(makeStandResult(3, '该项目编号的项目有未提交打分的专家，不能开启并行投票！'));
            }
            // 设置relation表状态为进行中
            $xm_ids = array_column($data,'xm_id');
            $xmData = [];
            $xmData['vote1status'] = '进行中';
            $model->where(['xr_xm_id'=>['in',$xm_ids]])->setField($xmData);
            M('xmps_xm')->where(['xm_id'=>['in',$xm_ids]])->setField(['voteoptionbx'=>'1']);

            // 设置relation表 已回避的专家 状态为 完成
            $xmData = [];
            $xmData['vote1status'] = '完成';
            $model->where(['xr_xm_id'=>['in',$xm_ids],'ishuibi'=>['eq','1']])->setField($xmData);
//            dump($xmCode);

            $model->commit();
            exit(makeStandResult(0,''));
        } catch (\Exception $e) {
            $model->rollback();
            exit(makeStandResult(3,'开启失败，错误信息：'.$e));
        }
    }

    //表格导出
    public function export()
    {
        $queryParam = I('post.');
        $xm_id = trim($queryParam['xm_id']);
        $where = [];
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_class'])) {
            $where['xm_class'] = ['like', '%' . $queryParam['xm_class'] . '%'];
        }
        $model = M('xmps_xm');
        $data = $model->field('xm_code,xm_name,xm_company,xm_createuser,xm_class,num')
            ->join("left join (select xr_xm_id,max(avgvalue) as num from xmps_xmrelation where xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id")
            ->where($where)
            ->order('xm_code asc')
            ->select();
        $this->addLog('', '明细查询', '', '导出', '成功');
        $header = array('项目编号', '项目名称', '单位', '申请人', '分组', '已评平均分');
        $width = Array("15", "15", "30", "15", "15", "15", '15');
        excelExport($header, $data, true, $width,true);
    }

    /**
     * 明细数据页
     */
    public function getpingshen()
    {
        $queryParam = I('get.');
        $xmType = M('xmps_xm')->where("xm_id = '%s'",$queryParam["xm_id"])->getField('xm_type'); // 获取课题分类
        $this->assign('xm_id', $queryParam["xm_id"]);   // 项目id
        $this->assign('status', $queryParam["status"]); // 查看类型：all,所有；ok,已完成
        $this->assign('type', $queryParam["type"]);     // 是否查看投票：‘’，不看；vote1/vote2/vote3，查看投票

        $markOption = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $this->assign('markOption',$markOption);
        $iszz       = C('mark.REMARK_OPTION')[$xmType]['定性评价'];
        $this->assign('iszz',$iszz);

        // 投票项
        $voteOption = C('vote.REMARK_OPTION')[$xmType]['VOTE_OPTION'];
        $this->assign('voteOption',json_encode($voteOption));
        $this->getDic();
        $this->display("pingshendetail");
    }

    /**
     * 明细数据列表查询
     */
    public function getpingshendata()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $xm_id = trim($queryParam['xm_id']);
        $where['xr_xm_id'] = ['eq', $xm_id];
        $status = trim($queryParam['status']);
        $type   = trim($queryParam['type']);
        if ($status == 'ok') {
            $where['xr_status'] = ['eq', '完成'];
            if($type != ''){
                if($type == 'vote1') $where['vote1status'] = ['eq', '已完成'];
                if($type == 'vote2') $where['vote2status'] = ['eq', '已完成'];
                if($type == 'vote3') $where['vote3status'] = ['eq', '已完成'];
            }
        }
        $model = M('xmps_xmrelation');
        $field = ["user_realusername","xr_id","ps_zz","ps_detail","0+cast(ps_total as char) as ps_total","xr_status","ishuibi"];
        $allMarkField = $this->getAllMarkFieldFormat();
        $field = array_merge($field,$allMarkField);
        if($type != ''){
            $field = array_merge($field,['vote1','vote2','vote3','vote1rate','vote2rate','vote3rate']);
        }
        $data = $model->field($field)
            ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
            ->where($where)
            ->select();
//        echo $model->_sql();die;
        $count = $model->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")->where($where)->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }

    /**
     * 回退选择页面
     */
    public function huitui()
    {
        $userModel = M("sysuser");
        $where = [];
        $where['user_isdelete'] = ['eq', '0'];
        $where['user_issystem'] = ['eq', '否'];
        $where['user_role']     = ['eq', C("PROFESSERID")];
        $data = $userModel->field('user_id,user_name,user_realusername')->where($where)->order("user_class asc")->select();
        $this->assign("user", $data);
        $this->getDic();
        $this->display();
    }

    /**
     * 根据用户获取分组
     */
    public function getClass()
    {
        $userid = I("post.user_id");
        $class = M('sysuser')->where("user_id='" . $userid . "'")->getField("user_class");
        $classData=explode(",",$class);
        echo json_encode($classData);
        die;
    }

    /**
     * 回退提交处理
     */
    public function huituiSubmit()
    {
        $user_id  = I("post.user_id");
//        $classid  = I("post.classid");
        $xmType   = I("post.xmType"); // 课题分类
        $type     = I("post.type");   // 回退类型
        $model   = M('xmps_xmrelation');
        try {
            $model->startTrans();
            if($type == ''){ // 打分回退
                $where = [];
                $where['xr_user_id'] = ['eq',$user_id];
                $where['xr_status']  = ['eq','完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["xr_status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["xr_status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote1'){ // 第一轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote1status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote1status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote1status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote2'){ // 第二轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote2status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
//                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where xm_type = '$xmType') and vote2status='已完成'")->select();
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote2status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote2status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote3'){ // 第三轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote3status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
//                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where xm_type = '$xmType') and vote3status='已完成'")->select();
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote3status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote3status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }
            $model->commit();
            exit(makeStandResult(0,''));
        } catch (\Exception $e) {
            $model->rollback();
            exit(makeStandResult(1,'回退失败，错误详情：'.$e));
        }
    }

    /**
     * 回退提交处理（并行）
     * 验证若已开启过并行投票则不能回退
     */
    public function huituiSubmitbx()
    {
        $user_id  = I("post.user_id");
//        $classid  = I("post.classid");
        $xmType   = I("post.xmType"); // 课题分类
        $type     = I("post.type");   // 回退类型
        $model   = M('xmps_xmrelation');
        try {
            $model->startTrans();
            if($type == ''){ // 打分回退

                // 验证若已开启过并行投票则不能回退
                $where = [];
                $where['xr_status']   = ['eq','完成'];
                $where['vote1status'] = ['neq','未开始'];
                $isStartBX = $model->join("inner join xmps_xm on xm_id = xr_xm_id")->where($where)->count();
                if($isStartBX != 0){
                    $model->commit();
                    exit(makeStandResult(1,'所选项目中有项目已开启过并行投票，不能回退！'));
                }

                $where = [];
                $where['xr_user_id'] = ['eq',$user_id];
                $where['xr_status']  = ['eq','完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["xr_status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["xr_status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote1'){ // 第一轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote1status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote1status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote1status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote2'){ // 第二轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote2status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
//                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where xm_type = '$xmType') and vote2status='已完成'")->select();
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote2status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote2status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }else if($type == 'vote3'){ // 第三轮投票回退
                $where = [];
                $where['xr_user_id']  = ['eq',$user_id];
                $where['vote3status'] = ['eq','已完成'];
                if($xmType){
                    $where['xr_xm_id'] = ['exp'," in (select xm_id from xmps_xm where xm_type = '".$xmType."')"];
                }
//                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where xm_type = '$xmType') and vote3status='已完成'")->select();
                $relationdata = $model->where($where)->select();
                $data = [];
                if(count($relationdata)<1000){
                    $xr_ids = removeArrKey($relationdata,'xr_id');
                    $data["vote3status"] = "进行中";
                    if($xr_ids) $model->where(['xr_id'=>['in',$xr_ids]])->setField($data);
                }else{
                    foreach ($relationdata as $rd) {
                        $data["vote3status"] = "进行中";
                        $data["xr_id"] = $rd["xr_id"];
                        $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                    }
                }
            }
            $model->commit();
            exit(makeStandResult(0,''));
        } catch (\Exception $e) {
            $model->rollback();
            exit(makeStandResult(1,'回退失败，错误详情：'.$e));
        }
    }

    /**
     * 评审专家意见汇总表格导出（重点）
     * @throws \PHPExcel_Writer_Exception
     */
    public function huizongexportvote()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $xm_id = trim($queryParam['xm_id']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        // 获取指南方向
        $model   = M('xmps_xm');
        $xmGroup = $model->field("distinct xm_group")
            ->where($where)
            ->find();
        $xmGroup = $xmGroup['xm_group'];

        // 验证评审完成
        $where = [];
        $where['xm_group']  = $xmGroup;
        $where['xr_status'] = '进行中';
        $Sum = M('xmps_xmrelation r')->where($where)->count();
        if($Sum>0){
            exit(makeStandResult('2','当前有未提交的专家,请等专家提交后再导出结果表！'));
        }
//        echo $model->_sql();

        // 汇总数据查询
        $where = [];
        $where['xm_group'] = ['eq', $xmGroup];
        $data = $model->field("xm_id,xm_code,xm_tmfs,xm_name,xm_company,xm_createuser,xm_class,xm_group,
    case when youxiaocount is null then 0 else youxiaocount end youxiaocount,
    case when allcount is null then 0 else allcount end allcount,
    case when agreecount is null then 0 else agreecount end agreecount,
    case when disagreecount is null then 0 else disagreecount end disagreecount,
    voterate,avgvalue")
            ->join("left join (select xr_xm_id,
                    count(xr_id) youxiaocount,
                    sum(CASE WHEN ishuibi = '1' then 0 when ps_zz = 1 THEN 1 ELSE 0 END) agreecount,  
                    sum(CASE WHEN ishuibi = '1' then 0 when ps_zz = 0 THEN 1 ELSE 0 END) disagreecount,  
                    sum(CASE WHEN bx_time = 2 THEN 1 ELSE 0 END) bxtime2count
                from xmps_xmrelation where xr_status='完成' and ishuibi = '0' group by xr_xm_id
            ) a on xmps_xm.xm_id=a.xr_xm_id") // 查询投票完成数量，同意票数，不同意票数
            ->join("left join (select xr_xm_id,count(xr_id) allcount,max(voterate) as voterate,max(avgvalue) as avgvalue from xmps_xmrelation group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id") // 查询全部专家任务数量、得票率、平均分
            ->where($where)
            ->order("agreecount desc,avgvalue desc,xm_ordernum asc")
            ->select();
//            echo $data;die;
//            拼接专家分数列
        foreach($data as $k=>$item){
            $wheres = $where;
            $wheres['xm_id'] = ['eq',$item['xm_id']];
            $mxDetail = $model->field("
case when ishuibi = '1' then '回避' else 0+cast(ps_total as char) end ps_total,
case when ishuibi = '1' then '回避' when ps_zz = '0' then '建议不立项'  when ps_zz = '1' then '建议立项' else ps_zz end ps_zz,
case when ishuibi = '1' then '回避' else ps_detail end ps_detail")
                ->join("inner join xmps_xmrelation a on xmps_xm.xm_id=a.xr_xm_id")
                ->where($wheres)
                ->order("ishuibi asc,ps_total+0 desc")// 评分从高到低，回避排最后
                ->select();
//                    echo $model->_sql();die;
            $data[$k]['mxdata'] = $mxDetail;
        }
//        dump($data);
//        dump($mxDetail);
        import("Vendor.PHPWord.PHPWord");
        $count      = count($data);
//        dump($data);dump($count);die;
        $PHPWord = new \PHPWord();
//        $document = $PHPWord->loadTemplate('Public/template/template10.docx');
        $document = $PHPWord->loadTemplate('Public/template/huizong'.$count.'.docx');
        $pathInfo = [];
        foreach($data as $key=>$val){
            $number  = $key+1;
            $document->setValue('xm_name'.$number, $val['xm_name']);
            $document->setValue('xm_code'.$number, $val['xm_code']);
            $document->setValue('xm_type'.$number, $val['xm_group']);
            $document->setValue('xm_company'.$number, $val['xm_company']);
            $document->setValue('xm_createuser'.$number, $val['xm_createuser']);
            $document->setValue('allcount'.$number, $val['allcount']);
            $document->setValue('youxiaocount'.$number, $val['youxiaocount']);
            $document->setValue('agreecount'.$number, $val['agreecount']);
            $document->setValue('disagreecount'.$number, $val['disagreecount']);

            foreach($val['mxdata'] as $k=>$v){
                $document->setValue('ps_total'.$k.$number, $v['ps_total']);
                $document->setValue('ps_zz'.$k.$number, $v['ps_zz']);

                if(($k == count($val['mxdata'])-1) && ($k<18)){
                    for($i=$k+1;$i<19;$i++){
                        $document->setValue('ps_total'.$i.$number, '');
                        $document->setValue('ps_zz'.$i.$number, '');
                    }
                }
            }

            $ps_detail = array_column($val['mxdata'],'ps_detail');
            $ps_detail = implode("<w:br />",$ps_detail);
            $document->setValue('ps_detail'.$number, $ps_detail);
//            dump($ps_detail);die;
        }
        $filename = "评审专家意见汇总表.doc";
        $savePath = 'Public/upload/word/' . date('Y-m-d');
        if (!is_dir($savePath)) mkdir($savePath, 0777, true);
        $filePath = $savePath . '/' . $filename;
        if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
            $filePath = iconv('UTF-8','gbk',$filePath);
        }
        $document->save($filePath);
        $fileRootPath = getWebsiteRootPath();
        $filePath = $savePath . '/' . $filename;
        exit(json_encode(array('code' => 1, 'message' => $fileRootPath . $filePath)));
//        exit(json_encode(array('code' => 1, 'message' => $filePath)));
    }

    /**
     * 评审结果排序表导出(重点)
     * @throws \PHPExcel_Writer_Exception
     */
    public function resultsortexportvote()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $xm_id = trim($queryParam['xm_id']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        // 获取指南方向
        $model   = M('xmps_xm');
        $xmGroup = $model->field("distinct xm_group")
            ->where($where)
            ->find();
        $xmGroup = $xmGroup['xm_group'];

        // 验证评审完成
        $where = [];
        $where['xm_group']  = $xmGroup;
        $where['xr_status'] = '进行中';
        $Sum = M('xmps_xmrelation r')->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
        if($Sum>0){
            exit(makeStandResult('2','当前有未提交的专家,请等专家提交后再导出评审结果排序表！'));
        }
//        echo M('xmps_xmrelation r')->_sql();die;

        // 汇总数据查询
        $where = [];
        $where['xm_group'] = ['eq', $xmGroup];
        $data = $model->field("xm_id,xm_code,xm_tmfs,xm_name,xm_company,xm_createuser,xm_class,xm_group,
    case when youxiaocount is null then 0 else youxiaocount end youxiaocount,
    case when allcount is null then 0 else allcount end allcount,
    case when agreecount is null then 0 else agreecount end agreecount,
    case when disagreecount is null then 0 else disagreecount end disagreecount,
    voterate,0+cast(avgvalue as char) as avgvalue")
            ->join("left join (select xr_xm_id,
                    count(xr_id) youxiaocount,
                    sum(CASE WHEN ishuibi = '1' then 0 when ps_zz = 1 THEN 1 ELSE 0 END) agreecount,  
                    sum(CASE WHEN ishuibi = '1' then 0 when ps_zz = 0 THEN 1 ELSE 0 END) disagreecount,  
                    sum(CASE WHEN bx_time = 2 THEN 1 ELSE 0 END) bxtime2count
                from xmps_xmrelation where xr_status='完成' and ishuibi = '0' group by xr_xm_id
            ) a on xmps_xm.xm_id=a.xr_xm_id") // 查询投票完成数量，同意票数，不同意票数
            ->join("left join (select xr_xm_id,count(xr_id) allcount,max(voterate) as voterate,max(avgvalue) as avgvalue from xmps_xmrelation group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id") // 查询全部专家任务数量、得票率、平均分
            ->where($where)
            ->order("agreecount desc,avgvalue desc,xm_ordernum asc")
            ->select();
//            dump($data);die;
        import("Vendor.PHPWord.PHPWord");
        $count      = count($data);
//        dump($data);dump($count);die;
        $PHPWord = new \PHPWord();
//        $document = $PHPWord->loadTemplate('Public/template/template10.docx');
        $document = $PHPWord->loadTemplate('Public/template/resultsort.docx');
//            拼接专家分数列
        $document->setValue('xm_group', $xmGroup);
        $document->setValue('date', date("Y年m月d日"));
        foreach($data as $key=>$val){
            $number  = $key+1;
            $document->setValue('xm_code'.$number, $val['xm_code']);
            $document->setValue('xm_name'.$number, $val['xm_name']);
            $document->setValue('xm_createuser'.$number, $val['xm_createuser']);
            $document->setValue('xm_company'.$number, $val['xm_company']);
            $document->setValue('agreecount'.$number, $val['agreecount']);
            $document->setValue('disagreecount'.$number, $val['disagreecount']);
            $document->setValue('avgvalue'.$number, $val['avgvalue']);

            if(($key == count($data)-1) && ($key<9)){
                for($i=$key+1;$i<19;$i++){
                    $document->setValue('xm_code'.$i, '');
                    $document->setValue('xm_name'.$i, '');
                    $document->setValue('xm_createuser'.$i, '');
                    $document->setValue('xm_company'.$i, '');
                    $document->setValue('agreecount'.$i, '');
                    $document->setValue('disagreecount'.$i, '');
                    $document->setValue('avgvalue'.$i, '');
                }
            }
        }
        $filename = "评审结果排序表.doc";
        $savePath = 'Public/upload/word/' . date('Y-m-d');
        if (!is_dir($savePath)) mkdir($savePath, 0777, true);
        $filePath = $savePath . '/' . $filename;
        if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
            $filePath = iconv('UTF-8','gbk',$filePath);
        }
        $document->save($filePath);
        $fileRootPath = getWebsiteRootPath();
        $filePath = $savePath . '/' . $filename;
        exit(json_encode(array('code' => 1, 'message' => $fileRootPath . $filePath)));
//        exit(json_encode(array('code' => 1, 'message' => $filePath)));
    }

    /**
     * 导出专家签字表选择专家页面
     */
    public function expertexport()
    {
        $type      = I('get.type');
        $userModel = M("sysuser");
        $where     = [];
        $where['user_isdelete'] = ['eq', '0'];
        $where['user_issystem'] = ['eq', '否'];
        $where['user_role']     = ['eq',$this->professorid];
        $data = $userModel->field('user_id,user_name,user_realusername')->where($where)->select();
        $this->assign("user", $data);
        $this->assign("type", $type);
        $this->display();
    }
    /**
     * 答辩评审专家个人意见表导出（重点）
     * @throws \PHPExcel_Writer_Exception
     */
    public function yijianexportvote()
    {
        $queryParam = I("post.");
        $where   = [];
        $xm_id   = trim($queryParam['xm_id']);
        $user_id = trim($queryParam['xm_user']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        // 获取指南方向
        $model   = M('xmps_xm');
        $xmGroup = $model->field("distinct xm_group")
            ->where($where)
            ->find();
        $xmGroup = $xmGroup['xm_group'];

        // 验证评审完成
        $where = [];
        $where['xm_group']  = $xmGroup;
        $where['xr_status'] = '进行中';
        if (!empty($user_id)) {
            $where['xr_user_id'] = ['eq', $user_id];
        }
        $Sum = M('xmps_xmrelation r')->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
        if($Sum>0){
            exit(makeStandResult('2','当前有未提交的项目,请等专家提交后再导出专家个人意见表！'));
        }
//        echo $model->_sql();
        // 查询专家
        $where = [];
        $where['xm_group']  = $xmGroup;
        $where['xr_status'] = '完成';
        if (!empty($user_id)) {
            $where['xr_user_id'] = ['eq', $user_id];
        }
        $userInfo = M('xmps_xmrelation r')
            ->field("distinct user_realusername,user_id")
            ->join("xmps_xm on xm_id = xr_xm_id")
            ->join("inner join sysuser u on u.user_id = r.xr_user_id")
            ->where($where)->select();
//        echo  M('xmps_xmrelation r')
//            ->_sql();
//        dump($userInfo);die;
        $header = ['项目名称','指南方向','申报编号',"项目\r负责人",'研究内容',"目标设置\r与技术路\r线","任务分解\r和进度安\r排","研发团队\r及工作基\r础","预期成果\r与风险分\r析","总分","评审结论\r意见","评审意见"];
        $width  = ["5","15","8","10","7","8","9","9","9","9","6","15","11"];
        $title  = "先进技术答辩评审专家个人意见表";
        $pathInfo = [];
        foreach($userInfo as $key=>$userData){
            // 汇总数据查询
            $where = [];
            $where['xm_group']   = ['eq', $xmGroup];
            $where['xr_user_id'] = ['eq', $userData['user_id']];
//            $markField  = $this->getAllMarkFieldFormat();
//            $markField  = implode(",",$markField);
            $data = $model->field("xm_name,xm_group,xm_code,xm_createuser,
        case when ishuibi = 0 then 0 + cast(ps_item1 AS CHAR) else '回避' end ps_item1,
	    case when ishuibi = 0 then 0 + cast(ps_item2 AS CHAR) else '回避' end ps_item2,
	    case when ishuibi = 0 then 0 + cast(ps_item3 AS CHAR) else '回避' end ps_item3,
	    case when ishuibi = 0 then 0 + cast(ps_item4 AS CHAR) else '回避' end ps_item4,
	    case when ishuibi = 0 then 0 + cast(ps_item5 AS CHAR) else '回避' end ps_item5,
        case when ishuibi = 0 then 0+cast(ps_total as char) else '回避' end ps_total,
        case when ishuibi = 1 then '回避' when ps_zz = 1 then '建议立项'  when ps_zz = 0 then '建议不立项' else ps_zz end ps_zz,
        case when ishuibi = 0 then ps_detail else '回避' end ps_detail")
                ->join("left join xmps_xmrelation a on xmps_xm.xm_id=a.xr_xm_id")
                ->where($where)
                ->order("xm_ordernum desc")
                ->select();
//            echo $model->_sql();
            $res = excelExport($header, $data, true,$width,true,true,"(".$data[0]['xm_group'].")    (".$userData["user_realusername"].")    "."专家签字：                                                        日期：".date('Y年m月d日')."&R &P",$title,"",[],$userData["user_realusername"]);
//            echo $res;
            $pathInfo[] = $res;
        }
//        dump($pathInfo);
//        die;
        exit(json_encode(array('code' => 0, 'message' => $pathInfo)));
    }

    /**
     * 评审专家补充意见表导出（并行）
     * @throws \PHPExcel_Writer_Exception
     */
    public function buchongexportvote()
    {
        $queryParam = I("post.");
        $where   = [];
        $xm_id   = trim($queryParam['xm_id']);
        $user_id = trim($queryParam['xm_user']);
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        // 获取指南方向
        $model   = M('xmps_xm');
        $xmGroup = $model->field("distinct xm_group")
            ->where($where)
            ->find();
        $xmGroup = $xmGroup['xm_group'];

        // 验证评审完成
        $where = [];
        $where['xm_group']     = ['eq',$xmGroup];
        $where['vote1status']  = ['neq','未开始'];
        $Sum = M('xmps_xmrelation r')->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
//        echo M('xmps_xmrelation r')->_sql();die;
        if($Sum == 0){
            exit(makeStandResult('2','并行投票还未开始！'));
        }
        $where = [];
        $where['xm_group']     = $xmGroup;
        $where['voteoptionbx'] = '1';
        $where['vote1status']  = '进行中';
        if (!empty($user_id)) {
            $where['xr_user_id'] = ['eq', $user_id];
        }
        $Sum = M('xmps_xmrelation r')->join("xmps_xm on xm_id = xr_xm_id")->where($where)->count();
        if($Sum>0){
            exit(makeStandResult('2','当前有未提交并行投票的项目,请等专家提交后再导出补充意见表！'));
        }

        // 查询专家
        $where = [];
        $where['xm_group']  = $xmGroup;
        $where['xr_status'] = '完成';
        if (!empty($user_id)) {
            $where['xr_user_id'] = ['eq', $user_id];
        }
        $userInfo = M('xmps_xmrelation r')
            ->field("distinct user_realusername,user_id")
            ->join("xmps_xm on xm_id = xr_xm_id")
            ->join("inner join sysuser u on u.user_id = r.xr_user_id")
            ->where($where)->select();
//dump($userInfo);die;
        vendor("PHPExcel.PHPExcel");
        $pathInfo = [];
        foreach($userInfo as $key=>$userData){
            // 查询数据
            $where      = [];
            $where['xr_user_id']   = array('eq' ,$userData['user_id']);
            $where['voteoptionbx'] = array('eq' ,'1');
            $data = $model->field('xm_name,xm_company,xm_createuser,xm_group,ishuibi,vote1,bx_detail,bx_time,0+cast(vote1rate as char) as vote1rate，avgvalue')
                ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
                ->join("left join (select xr_xm_id,count(xr_id) agreecount from xmps_xmrelation where ps_zz = 1 group by xr_xm_id) c on xmps_xm.xm_id=c.xr_xm_id") // 查询同意票数
                ->where($where)
                ->order("agreecount desc,avgvalue desc")
                ->select();
//            dump($data);

            $excel = new \PHPExcel();
            $titleForExport = C('mark.REMARK_OPTION')[$queryParam["xm_type"]]['title'];
            $name = "同一指南方向下不同技术路线项目\r\n择优同时支持的专家补充意见表"; // 导出表头
            $letter = ['A','B'];
            $excel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $styleArrayLeft = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $styleArray['alignment'] = array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            );
            $styleArrayLeft['alignment'] = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            );
            $styleArrayTop = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                "alignment"=>[
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_TOP
                ]
            );
            $titleStyle = Array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $titleStyleLeft = Array(
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
//            dump($field);
//            dump($letter[count($field)-1]);die;
            // 设置列宽
            $excel->getActiveSheet()->getStyle('A')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $excel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
            $excel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
            // 标题行样式设置
            $excel->getActiveSheet()->setCellValue('A1', $name)->mergeCells('A1:B1');
            $excel->getActiveSheet()->getStyle('A1:B1')->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('A1:B1')->getFont()->setName("黑体")->setSize(16)->setBold(true);
            $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);


            // 第二行样式设置，显示值设置
            $excel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
            $excel->getActiveSheet()->setCellValue('A2', "重点专项");
            $excel->getActiveSheet()->getStyle('A2:A6')->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('A2:A6')->getFont()->setName("仿宋")->setSize(13)->setBold(true);
            $excel->getActiveSheet()->setCellValue('B2', "先进技术");
            $excel->getActiveSheet()->getStyle('B2:B6')->applyFromArray($styleArray);
            $excel->getActiveSheet()->getStyle('B2:B6')->getFont()->setName("仿宋")->setSize(13);

            // 第三行显示值设置
            $excel->getActiveSheet()->getRowDimension(3)->setRowHeight(30);
            $excel->getActiveSheet()->setCellValue('A3', "指南方向");
            $excel->getActiveSheet()->setCellValue('B3',$xmGroup);

            // 第四行显示值设置
            $index = 4;
//            dump($data);die;
            foreach ($data as $key=>$val) {
                $titlepx = '';
                if($index == 4){
                    $titlepx = "排序第一的项目名称及申报单位";
                }else if($index == 5){
                    $titlepx = "排序第二的项目名称及申报单位";
                }else if($index == 6){
                    $titlepx = "排序第三的项目名称及申报单位";
                }else if($index == 7){
                    $titlepx = "排序第四的项目名称及申报单位";
                }
//                echo $index.'-'.$titlepx;
                $excel->getActiveSheet()->setCellValue('A'.$index, $titlepx);
                $excel->getActiveSheet()->setCellValue('B'.$index, $val['xm_name']."\r".$val['xm_company']);
                $index ++;
            }
//            die;
            $excel->getActiveSheet()->getStyle('A4:A'.$index)->applyFromArray($titleStyleLeft);
            $excel->getActiveSheet()->getStyle('B4:B'.$index)->applyFromArray($styleArrayLeft);

            $start = $index;
            //技术路线判断
            $excel->getActiveSheet()->setCellValue('A'.$index, "技术路线判断");
            if($data[0]['ishuibi'] == 1){
                $excel->getActiveSheet()->setCellValue('B'.$index,"回避");
            }else if($data[0]['vote1'] == 1){
                $excel->getActiveSheet()->setCellValue('B'.$index,"A □ 技术路线基本一致，不建议同时支持\r\nB ■ 技术路线差异较大，建议同时支持");
            }else if($data[0]['vote1'] == 0){
                $excel->getActiveSheet()->setCellValue('B'.$index,"A ■ 技术路线基本一致，不建议同时支持\r\nB □ 技术路线差异较大，建议同时支持");
            }
            $excel->getActiveSheet()->getStyle('A'.$index)->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('A'.$index)->getFont()->setName("仿宋")->setSize(13)->setBold(true);
            $excel->getActiveSheet()->getStyle('B'.$index)->applyFromArray($styleArrayLeft);
            $excel->getActiveSheet()->getStyle('B'.$index)->getFont()->setName("仿宋")->setSize(13);
            $index ++;
            $content = "如果选“B”，请说明技术路线差异较大之处：\r\n".$data[0]['bx_detail'];
            $excel->getActiveSheet()->setCellValue('A'.$index,$content)->mergeCells('A'.$index.':B'.$index);
            $excel->getActiveSheet()->getRowDimension($index)->setRowHeight(60);
            $excel->getActiveSheet()->getStyle('A'.$index.':B'.$index)->applyFromArray($styleArrayTop);
            $excel->getActiveSheet()->getStyle('B'.$index)->getFont()->setName("仿宋")->setSize(13);
            $index ++;
            // 择优时间建议
            $excel->getActiveSheet()->setCellValue('A'.$index, "当选择B时，二次评估\r\n择优时间建议");
            if($data[0]['ishuibi'] == 1){
                $excel->getActiveSheet()->setCellValue('B'.$index,"回避");
            }else if($data[0]['bx_time'] == 1){
                $excel->getActiveSheet()->setCellValue('B'.$index,"A ■ 立项后1年\r\nB □ 立项后2年");
            }else if($data[0]['bx_time'] == 2){
                $excel->getActiveSheet()->setCellValue('B'.$index,"A □ 立项后1年\r\nB ■ 立项后2年");
            }
            $excel->getActiveSheet()->getStyle('A'.$index)->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('A'.$index)->getFont()->setName("仿宋")->setSize(13)->setBold(true);
            $excel->getActiveSheet()->getStyle('B'.$index)->applyFromArray($styleArrayLeft);
            $excel->getActiveSheet()->getStyle('B'.$index)->getFont()->setName("仿宋")->setSize(13);
            $index ++;
            $content = "专家签名\r\n\r\n\r\n                                                                  ".date("Y年m月d日");
            $excel->getActiveSheet()->setCellValue('A'.$index,$content)->mergeCells('A'.$index.':B'.$index);
            $excel->getActiveSheet()->getRowDimension($index)->setRowHeight(60);
            $excel->getActiveSheet()->getStyle('A'.$index.':B'.$index)->applyFromArray($styleArrayTop);
            $excel->getActiveSheet()->getStyle('B'.$index)->getFont()->setName("仿宋")->setSize(13);
            // 设置页脚
            $excel->getActiveSheet()->getHeaderFooter()->setOddFooter("第&P页/共&N页")->setAlignWithMargins(true);
            $excel->getActiveSheet()->getPageMargins()->setFooter("0.5");
//            $excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
            $write = new \PHPExcel_Writer_Excel2007($excel);

            $filename = $userData['user_realusername']."-专家补充意见表";

            $filename = $filename . '.xlsx';
            $savePath = 'Public/upload/excel/' . date('Y-m-d');
            if (!is_dir($savePath)) mkdir($savePath, 0777, true);
            $filePath = $savePath . '/' . $filename;
            if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
                $filePath = iconv('UTF-8','gbk',$filePath);
            }
//            dump($filePath);die;
            $write->save($filePath);
            $fileRootPath = getWebsiteRootPath();
            $filePath = $savePath . '/' . $filename;
            $pathInfo[] = $fileRootPath.$filePath;
//        dump($pathInfo);
//            die;
        }
        exit(json_encode(array('code' => 0, 'message' => $pathInfo)));
    }

    /**
     * 结果表导出（会评）
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
     public function resultexport()
    {
        $queryParam = I("post.");
        if (!empty($queryParam['xm_class'])) {
            $isZD      = C('isZD');                    // 是否有与战斗力关联
            $where['xm_class']  = ['eq', $queryParam["xm_class"]];
            $where['xm_type']   = ['eq', $queryParam["xm_type"]];
            $model = M('xmps_xm');
            $field = ["xm_code","xm_name","xm_company","xm_createuser","avgvalue"];
            $width = [7, 15, 25, 30, 15, 15];
            $title = ["序号", "项目编号", "项目名称", "依托单位", "申请人", "平均分"];
            if($isZD == 1){
                array_push($field,"ps_detail");
                array_push($title,"与战斗力关联程度");
                array_push($width,15);
            }
            array_push($field,'null as remark');
            array_push($title,'备注');
            $data = $model->field($field)
                ->join("left join (select xr_xm_id,max(avgvalue) as avgvalue,max(ps_detail) as ps_detail,max(ps_zz) as ps_zz from xmps_xmrelation where xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id")
                ->where($where)
                ->order('avgvalue desc')
                ->select();
            // 获取所有平均值，相同的加粗
            $avgvalues = removeArrKey($data,'avgvalue');
            $avgvalues = array_count_values($avgvalues);
            $avgvalueRepeat = [];
            foreach($avgvalues as $values=>$countNum){
                if($countNum>1) $avgvalueRepeat[] = $values;
            }
            vendor("PHPExcel.PHPExcel");
            $excel = new \PHPExcel();
            $titleForExport = C('mark.REMARK_OPTION')[$queryParam["xm_type"]]['title'];
            $name = date("Y", time()) . "年度".$titleForExport."评审结果统计表"; // 导出表头

            $excel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $styleArrayLeft = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $styleArray['alignment'] = array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            );
            $styleArrayLeft['alignment'] = array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            );
            $titleStyle = Array(
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $titleStyleLeft = Array(
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $letter    = getEnglishLetter(); //获取excel列名
            $endLetter = $letter[count($field)-1];
//            dump($field);
//            dump($letter[count($field)-1]);die;
            // 标题行样式设置
            $excel->getActiveSheet()->setCellValue('A1', $name)->mergeCells('A1:'.$endLetter.'1');
            $excel->getActiveSheet()->getStyle('A1:'.$endLetter.'1')->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('A1:'.$endLetter.'1')->getFont()->setName("黑体")->setSize(16)->setBold(true);
            $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
            $index = 0;
            for ($index; $index < 8; $index++) {
                $excel->getActiveSheet()->getStyle($letter[$index])->getAlignment()->setWrapText(true);
                $excel->getActiveSheet()->getColumnDimension($letter[$index])->setWidth($width[$index]);
            }
            // 第二行样式设置，显示值设置
            $excel->getActiveSheet()->getRowDimension(2)->setRowHeight(30);
            $excel->getActiveSheet()->setCellValue('A2', "分组名称：" . $queryParam['xm_class']."；课题分类：".$queryParam['xm_type'])->mergeCells('A2:C2');
            $excel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($titleStyleLeft);
            $excel->getActiveSheet()->getStyle('A2:C2')->getFont()->setName("楷体")->setSize(13);
            $excel->getActiveSheet()->setCellValue('D2', "本组项目数：" . count($data) . "个");
            $excel->getActiveSheet()->getStyle('D2')->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('D2')->getFont()->setName("楷体")->setSize(13);
            $excel->getActiveSheet()->setCellValue('E2', "日期：" . date("Y-m-d", time()))->mergeCells('E2:'.$endLetter.'2');
            $excel->getActiveSheet()->getStyle('E2:'.$endLetter.'2')->applyFromArray($titleStyle);
            $excel->getActiveSheet()->getStyle('E2:'.$endLetter.'2')->getFont()->setName("楷体")->setSize(13);
            // 第三行表头样式设置，显示值设置
            $index = 0;
            for ($index; $index < 7; $index++) {
                $excel->getActiveSheet()->setCellValue($letter[$index] . '3', $title[$index]);
                $excel->getActiveSheet()->getStyle($letter[$index] . '3')->applyFromArray($styleArray);
                $excel->getActiveSheet()->getStyle($letter[$index] . '3')->getFont()->setBold(true);
            }
            $key = 4;
//            $field = array('xm_code', 'xm_name', 'xm_company', 'xm_createuser', 'avgvalue');
//            if(C('isZD') == 1){
//                array_push($field,'ps_detail');
//            }
//            array_push($field,'remark');
            $titlecount = count($title);
            foreach ($data as $d) {
                if ($d["num"] == null)
                    $d["num"] = '-';
                else
                    $d["num"] = $d["num"];
            
                $excel->getActiveSheet()->setCellValue($letter[0] . $key, $key - 3);
                $excel->getActiveSheet()->getStyle($letter[0] . $key)->applyFromArray($styleArray);
                if(in_array($d['avgvalue'],$avgvalueRepeat)){
                    $excel->getActiveSheet()->getStyle($letter[0] . $key)->getFont()->setBold(true);
                }
                $index = 1;
                for ($index; $index < $titlecount; $index++) {
                    $excel->getActiveSheet()->setCellValue($letter[$index] . $key, $d[$field[$index - 1]]);
                    if($letter[$index] == 'C' || $letter[$index] == 'D'){// 项目名称，依托单位 列靠左对齐
                        $excel->getActiveSheet()->getStyle($letter[$index] . $key)->applyFromArray($styleArrayLeft);
                    }else{
                        $excel->getActiveSheet()->getStyle($letter[$index] . $key)->applyFromArray($styleArray);
                    }
                    if(in_array($d['avgvalue'],$avgvalueRepeat)){ // 平均分相同加粗
                        $excel->getActiveSheet()->getStyle($letter[$index] . $key)->getFont()->setBold(true);
                    }
                }
                $key++;
            }
            // 设置页脚
            $excel->getActiveSheet()->getHeaderFooter()->setOddFooter("专家组组长签字：&R第&P页/共&N页")->setAlignWithMargins(true);
            $excel->getActiveSheet()->getPageMargins()->setFooter("0.5");
            $excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);
            $write = new \PHPExcel_Writer_Excel2007($excel);
            if($name!=""){
                $filename = $name;
            }else{
                $filename = date('Ymd') . time() . rand(0, 1000);
            }
            $filename = $filename . '.xlsx';
            $savePath = 'Public/upload/excel/' . date('Y-m-d');
            if (!is_dir($savePath)) mkdir($savePath, 0777, true);
            $filePath = $savePath . '/' . $filename;
            if(!@rename('测试编码.txt',  '测试编码修改.txt') && !@rename('测试编码修改.txt',  '测试编码.txt')){
                $filePath = iconv('UTF-8','gbk',$filePath);
            }
            $write->save($filePath);
            $fileRootPath = getWebsiteRootPath();
            $filePath = $savePath . '/' . $filename;
            exit(json_encode(array('code' => 1, 'message' => $fileRootPath . $filePath)));
        }
    }


    /**
     * 函评导出（不区分课题分类）
     */
    public function exportxx()
    {
        $queryParam = I('post.');
        $xm_id      = trim($queryParam['xm_id']);
        $where      = [];
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_class'])) {
            $where['xm_class'] = ['eq', $queryParam['xm_class']];
        }
        if (!empty($queryParam['xm_type'])) {
            $where['xm_type'] = ['eq', $queryParam['xm_type']];
            // 是否有定性评价(有课题分类取对应课题分类的)
            $isDXPJ = C('mark.REMARK_OPTION')[$where['xm_type']]['定性评价']; 
        }else{
            // 是否有定性评价(没有课题分类取配置文件的第一个课题分类的)
            $isDXPJ = C('mark.REMARK_OPTION')[array_keys(C('mark.REMARK_OPTION'))[0]]['定性评价']; 
        }
        $model         = M('xmps_xm');
        $relationmodel = M('xmps_xmrelation');
        // 查询关联表共有几个项目参与打分
        $getmax        = "select max(x) num from (select count(*) x,xr_xm_id from xmps_xmrelation group by xr_xm_id) a ";
        $maxdata       = $model->query($getmax);
        $num           = 0;
        if (!empty($maxdata)) $num = intval($maxdata[0]["num"]);
        // 查询所有项目信息
        $data = $model->field('xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_class,xm_type')
            ->where($where)
            ->order('xm_class,xm_type,xm_code asc')
            ->select();
        // 查询一个项目最多有几个专家打分
        $maxpsnum = $relationmodel->field("count(*) c")
            ->join("inner join xmps_xm on xr_xm_id = xm_id")
            ->where($where)
            ->group("xr_xm_id")
            ->order("c desc")
            ->find();
        $maxpsnum = $maxpsnum['c'];
        foreach ($data as $key => $d) {
            $relationdata = $relationmodel->field("
            case when ishuibi = 0 then 0+cast(ps_total as char) else '回避' end ps_total,
            user_realusername,
            case when ishuibi = 0 then ps_zz else '回避' end ps_zz,
            case when ishuibi = 0 then ps_detail else '回避' end ps_detail,
            0+cast(avgvalue as char) as avgvalue")
                ->join("left join sysuser on xr_user_id=user_id")
                ->where("xr_xm_id='" . $d["xm_id"] . "'")
                ->select();
            $sum_zz = [];
            $sum    = [];
            foreach ($relationdata as $rdata) {
                array_push($data[$key], $rdata["user_realusername"]);
                array_push($data[$key], $rdata["ps_total"]);
                if($isDXPJ){
                    array_push($data[$key], $rdata["ps_zz"]);
                }
                array_push($data[$key], $rdata["ps_detail"]);
                array_push($sum_zz, $rdata["ps_zz"]);
                array_push($sum, $rdata["ps_total"]);
            }
            if(count($relationdata)<$maxpsnum){
                $short = intval($maxpsnum-count($relationdata));
                for($i=0;$i<$short;$i++){
                    array_push($data[$key], null);
                    array_push($data[$key], null);
                    array_push($data[$key], null);
                    if($isDXPJ){
                        array_push($data[$key], null);
                    }
                }
            }
            sort($sum_zz);
            $sum_zz = implode($sum_zz);
            //array_push($data[$key], $sum_zz);         // 综合评审意见
            array_push($data[$key], array_sum($sum)); // 总分
            array_push($data[$key], $rdata["avgvalue"]); // 平均分
            unset($data[$key]["xm_id"]);
        }
        $header = array('项目编号', '项目名称', '单位', '申请人', '分组','课题分类');
        $width = Array("5", "10", "30", "15", "15", "15", "15");

        for ($i = 0; $i < $num; $i++) {
            array_push($header, "专家" . ($i + 1));
            array_push($width, "10");
            array_push($header, "分数");
            array_push($width, "10");
            if($isDXPJ){
                array_push($header, "定性评价");
                array_push($width, "10");
            }
            array_push($header, "评审意见");
            array_push($width, "30");
        }
        //array_push($header, '综合评审意见');
        //array_push($width, '30');
        array_push($header, '总分');
        array_push($width, '20');
        array_push($header, '平均分');
        array_push($width, '20');
        echo excelExport($header, $data, true, $width);
    }

    /**
     * 结果表导出选择分组，课题分类页面
     */
    public function checkclass()
    {
        $this->getDic();
        $this->display();
    }

    /**
     * 查询某分组下是否有未提交的专家
     **/
    public function checkResultExportFinish(){
        $xm_class = I('post.xm_class');
        $xm_type  = I('post.xm_type');
        if(!$xm_class) exit(makeStandResult('1','参数缺失，请重试！'));
        if(!$xm_type) exit(makeStandResult('1','参数缺失，请重试！'));
        $where = [];
        $where['xm_class']  = $xm_class;
        $where['xm_type']   = $xm_type;
        $where['xr_status'] = '进行中';
        $Sum = M('xmps_xmrelation r')->join('left join xmps_xm x on x.xm_id=r.xr_xm_id')->where($where)->count();
        if($Sum>0){
            exit(makeStandResult('2','当前分组下有未提交打分的专家,请等专家提交后再导出结果表！'));
        }else{
            exit(makeStandResult('0',''));
        }
    }

}