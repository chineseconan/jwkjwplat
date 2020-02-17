<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/7
 * Time: 14:29
 */
namespace Admin\Controller;

use Think\Controller;
use Think\Exception;

class VoteSettingController extends BaseController
{
    public function index()
    {
        $this->getDic();
        $this->display();
    }
//
//    private function getDic()
//    {
//        $Model = M('xmps_xm');
//        $group = $Model->field('distinct xm_class')->select();
//        $this->assign("group", $group);
//    }

    /**
     * 获取当前类别的技术方向
     */
    public function getGroupByType(){
        $type     = I('post.type');
        $classid = I('post.classid');
        $allgroup = M('xmps_xm m')
            ->field('xm_group')
            ->where("m.xm_status!='删除' and m.xm_class='".$classid."' and m.xm_type = '$type'")
            ->group('xm_group')
            ->select();
//        echo M('xm_group')->_sql();die;
        echo json_encode($allgroup);
    }

    /**
     * 保存设置的投票
     */
    public function setVoteSetting()
    {
        $classid  = I("post.classid");
        $xmtype   = I("post.xm_type");
        $maxnum   = I("post.maxnum");
        $round    = I("post.round");
        $status   = I("post.status");
        $ids      = I("post.ids",'');
//        dump(I('post.'));die;
        if(empty($classid) || empty($round)|| empty($xmtype)|| empty($maxnum)) exit(makeStandResult(1,'参数缺失，请重试'));
        $idArr   = explode(',',$ids);
        $ids     = implode("','",$idArr);
        $roundnum = "vote".$round."option";
        $Model = M("votesetting");
        try{
            $Model->startTrans();
            if($status ==1) {
                // 查询本轮是否有其他已开启的投票
                $hasOther = $Model->where("class = '" . $classid . "' and status = '1' and round != '" . $round . "' and xmtype = '$xmtype'")->find();
                if (!empty($hasOther)) exit(makeStandResult(1, '请先关闭当前分组下其他轮次的投票'));
                // 若为开启第一轮投票，需判断是否打完分
                if($round == 1){
                    $xr_status = M('xmpsXmrelation')->field('xr_status')->join("xmps_xm on xmps_xm.xm_id = xmps_xmrelation.xr_xm_id")
                        ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
                        ->where("xmps_xm.xm_class = '$classid' and xr_status != '完成' and xm_type = '$xmtype'")->select();
                    if(!empty($xr_status)){
                        exit(makeStandResult(2,'当前分组下有未提交打分的专家，不能开启当前轮次投票！'));
                    }
                }
                // 若为开启差额轮投票，需判断当前类别是否所有投票结束
//                if($round == 4) {
//                    $hasOther = $Model->where("class = '" . $classid . "' and status = '1' and round != '" . $round . "' and xmtype = '$xmtype'")->find();
//                    // echo $Model->_sql();die;
//                    if (!empty($hasOther)) exit(makeStandResult(1, '请先关闭当前类别下其他轮次的投票'));
//                }

                // 删除当前轮投票的voterate
                $rate       = 'vote'.$round.'rate';
                $xr_ids     = M('xmpsXmrelation')->field('xr_id')->join("xmps_xm on xmps_xm.xm_id = xmps_xmrelation.xr_xm_id")->where("xmps_xm.xm_class = '$classid' and xm_type = '$xmtype'")->select();
                $xr_ids     = removeArrKey($xr_ids,'xr_id');
                $xr_ids     = implode("','",$xr_ids);
                M('xmpsXmrelation')->where("xr_id in ('$xr_ids')")->save([$rate=>null]);

                // 设置relation表状态为进行中
                $votestatus = 'vote'.$round.'status';
                $xmData = [];
                $xmData[$votestatus] = '进行中';
                M('xmpsXmrelation')->where("xr_xm_id in ('$ids')")->save($xmData);

            }else{
                // 若为关闭第 $round 轮投票，需判断是否投完票
                if($round !=4){
                    $xr_status = M('xmpsXmrelation')->field('xr_status')->join("xmps_xm on xmps_xm.xm_id = xmps_xmrelation.xr_xm_id")
                        ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
                        ->where("xmps_xm.xm_class = '$classid' and vote".$round."status != '已完成' and xm_type = '$xmtype'")->select();
                    if(!empty($xr_status)){
                        exit(makeStandResult(2,'当前分组下有未提交投票的专家，不能开关闭当前轮次投票！'));
                    }
                }else{ // 差额轮
                    $xr_status = M('xmpsXmrelation')->field('xr_status')->join("xmps_xm on xmps_xm.xm_id = xmps_xmrelation.xr_xm_id")
                        ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
                        ->where("xmps_xm.xm_class = '$classid' and vote".$round."status != '已完成' and xm_type = '$xmtype'")->select();
                    if(!empty($xr_status)){
                        exit(makeStandResult(2,'当前类别下有未提交投票的专家，不能开关闭当前轮次投票！'));
                    }
                }

                // 写入当前轮投票的voterate
                $vote = 'vote'.$round;
                $votestatus = 'vote'.$round.'status';
                $voteNum = M('xmpsXmrelation')->field("count(*) count,xr_xm_id")->where("xr_xm_id in ('$ids') and $vote = '1' and $votestatus = '已完成'")->group('xr_xm_id')->select(); //参加当前投票的项目的所有得票数
                $voteNums = [];
                foreach($voteNum as $val){
                    $voteNums[$val['xr_xm_id']] = $val['count'];
                }
                $allNum  = M('xmpsXmrelation')->field("count(*) count,xr_xm_id")->where("xr_xm_id in ('$ids') and ($vote != '-1' or $vote is null or $vote = 0) and $votestatus = '已完成'")->group('xr_xm_id')->select(); //参加当前投票的项目的所有可得票数
                $allNums = [];
                foreach($allNum as $val){
                    $allNums[$val['xr_xm_id']] = $val['count'];
                }
                foreach($idArr as $xm_id) {
                    $voterate = 'vote' . $round . 'rate';
                    $rateData = [];
                    $rateData[$voterate] = 0;
                    if (isset($voteNums[$xm_id])) {
                        $voteCount = floatval($voteNums[$xm_id]);
                        $allCount = floatval($allNums[$xm_id]);
                        $rateData[$voterate] = round(floatval($voteCount / $allCount), 5) * 100;
                    }
                    M('xmpsXmrelation')->where("xr_xm_id ='$xm_id'")->save($rateData);
                }
            }
            // 更新xmps_xm,votesetting表数据
//            if($round != 4){
                $oldsetting = $Model->where("class = '".$classid."' and round = '".$round."' and xmtype = '$xmtype'")->getField('v_id');
//                echo $Model->_sql();die;
//            }else if($round == 4){
//                $oldsetting = $Model->where("class = '".$classid."' and round = '".$round."' and xmtype = '$xmtype'")->getField('v_id');
//            }
            if(!empty($oldsetting)){
                $xmData[$roundnum] = 0;
                M('xmps_xm')->where("xm_class = '".$classid."' and xm_type = '$xmtype'")->setField($xmData);
                $xmData[$roundnum] = 1;
                M('xmps_xm')->where("xm_id in ('".$ids."') and xm_type = '$xmtype'")->setField($xmData);
                $data = [];
                $data['status'] = $status;
                $data['maxnum'] = $maxnum;
                $Model->where("v_id = '".$oldsetting."'")->save($data);
            }else{
                $data = [];
                $data['v_id']    = makeGuid();
                $data['class']   = $classid;
                $data['xmtype']  = $xmtype;
                $data['round']   = $round;
                $data['status']  = $status;
                $data['maxnum']  = $maxnum;
                $Model->add($data);
                $xmData[$roundnum] = 1;
                M('xmps_xm')->where("xm_id in ('".$ids."')")->setField($xmData);
            }
            $Model->commit();
            exit(makeStandResult(0,'保存成功'));
        }catch (Exception $e){
            $Model->rollback();
            exit(makeStandResult(1,'保存失败:$e'));
        }
    }

    /**
     * 获取投票设置的信息
     */
    public function getSettingInfo()
    {
        $param   = I("post.classid");
        $setData = M('votesetting')->where("class = '".$param."'")->select();
        $setDatas = [];
        foreach($setData as $key=>$val){
            $keys = $val['round'].'-'.$val['xmtype'];
            $setDatas[$keys]['maxnum'] = $val['maxnum'];
            $setDatas[$keys]['status'] = $val['status'];
        }
        echo json_encode($setDatas);
    }


    private function _filter($param)
    {
        $map = Array();
        if ($param['class'] != "") {
            $map['xm_class'] = Array("eq", $param['class']);
        }
        if ($param['xm_type'] != "") {
            $map['xm_type'] = Array("eq", $param['xm_type']);
        }
        if ($param['xm_group'] != "") {
            $map['xm_group'] = Array("eq", $param['xm_group']);
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

    public function getVoteXMData()
    {
        $param = json_decode(file_get_contents("php://input"), true);
        $map = $this->_filter($param);
        $Model = D("xmps_xm x");
        $data = $Model->field("x.*,r.avgvalue")->join("inner join xmps_xmrelation r on x.xm_id = r.xr_xm_id")->where($map)->group("xm_id")->order(["avgvalue"=>"desc"])->select();
//        echo $Model->_sql();die;
        $count = $Model->where($map)->count();
        $this->ajaxReturn(array( 'total' => $count,'rows' => $data));
    }
}