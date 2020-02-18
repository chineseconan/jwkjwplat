<?php
namespace Admin\Controller;
use Think\Controller;
class MxQueryController extends BaseController
{

    /**
     * 字典管理
     */
    public function index()
    {
        $xmcode = I('get.xmcode','');
        $this->assign("xmcode", $xmcode);
        $model = M('xmps_xm');

        // 项目信息
        $where = [];
        $data = $model->field('xm_id,xm_name,xm_code')->where($where)->order("xm_code asc")->select();
        $this->assign("xmdata", $data);
        // 用户信息
        $userModel = M("sysuser");
        $where = [];
        $where['user_isdelete'] = ['eq', '0'];
        $where['user_issystem'] = ['eq', '否'];
        $data = $userModel->field('user_id,user_realusername')->where($where)->select();
        $this->assign("user", $data);

        $markOption = $this->getAllMarkField();
        $this->assign('markOption',$markOption);
        $this->getDic();
        $this->display();
    }

    /**
     * 获取字典列表
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
        if (!empty($queryParam['xm_user'])) {
            $where['xr_user_id'] = ['eq', $queryParam['xm_user']];
        }
        if (!empty($queryParam['xm_code'])) {
            $where['xm_code'] = ['eq', $queryParam['xm_code']];
        }
        $model = M('xmps_xm');
        $field = "xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_class,xm_type,xr_id,xr_status,ps_zz,ps_detail,0+cast(ps_total as char) as ps_total,user_realusername,vote1,vote2,vote3,vote1status,vote2status,vote3status";
        $allMarkField = $this->getAllMarkFieldFormat();
        $allMarkField = implode(",",$allMarkField);
        $field       .= ",".$allMarkField;
//        dump($allMarkField);die;
        $data = $model
            ->field($field)
            ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
            ->where($where)
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        $count = $model->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")->where($where)->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }

    public function export()
    {
        $queryParam = I('post.');
        $xm_id      = trim($queryParam['xm_id']);
        $where = [];
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_class'])) {
            $where['xm_class'] = ['eq', $queryParam['xm_class']];
        }
        if (!empty($queryParam['xm_user'])) {
            $where['xr_user_id'] = ['eq', $queryParam['xm_user']];
        }
        if (!empty($queryParam['xm_code'])) {
            $where['xm_code'] = ['eq', $queryParam['xm_code']];
        }
        if (!empty($queryParam['xm_type'])) {
            $where['xm_type'] = ['eq', $queryParam['xm_type']];
        }
        if (!empty($queryParam['xm_group'])) {
            $where['xm_group'] = ['like', '%'.$queryParam['xm_group'].'%'];
        }
        $model     = M('xmps_xm');
        $psType    = trim($queryParam['psType']);  // 投票类型
        $TOUPIAO   = trim($queryParam['TOUPIAO']); // 是否投票
        // 获取投票项
        if($TOUPIAO == 1){
            $voteOptions = C('vote.REMARK_OPTION')[$queryParam['xm_type']]['VOTE_OPTION'];
            $voteOption  = [];
            foreach ($voteOptions as $items){
                $voteOption[$items['value']] = $items['text'];
            }
        }
        $markInfo  = C('mark.REMARK_OPTION')[$queryParam['xm_type']]['评价内容'];
        $markField = removeArrKey($markInfo,'field');// 打分字段
        if($psType == 'huiping'){
            $zzTitle   = "与战斗力关联程度";
            $isZD      = C('isZD');                    // 是否有与战斗力关联
            $markTitle = removeArrKey($markInfo,'brief');
        }else{
            $zzTitle   = "定性评价";
            $isZD      = 1;
            $markTitle = removeArrKey($markInfo,'mainpoints');
        }
        // 查询字段
        $field = ['xm_code','xm_name','xm_class','user_realusername','ps_total'];
        $field = array_merge($field,$markField);
        // 导出表头
        $header = ['项目编号','项目名称',"分组",'评审专家','总分'];
        $header = array_merge($header,$markTitle);
        // 导出宽度
        $width = ["5","10","20","8","10","8"];
        $width  = array_merge($width,array_fill(count($width),count($markTitle),'12'));
        // 拼接是否与战斗力关联
        if($isZD == 1){
            array_push($field,'ps_detail');
            array_push($header, $zzTitle);
            array_push($width, '10');
        }
        // 拼接投票信息
        if($TOUPIAO == 1){
            $field = array_merge($field,["xr_status",
                "vote1","vote2","vote3","vote1option","vote2option","vote3option",
//                "case vote1 when '-1' then '回避' when '0' then '不支持' when '1' then '支持' when '-2' then '不参与本轮投票' else vote1 end vote1",
                "vote1status",
//                "case vote2 when '-1' then '回避' when '0' then '不支持' when '1' then '支持' when '-2' then '不参与本轮投票' else vote2 end vote2",
                "vote2status",
//                "case vote3 when '-1' then '回避' when '0' then '不支持' when '1' then '支持' when '-2' then '不参与本轮投票' else vote3 end vote3",
                "vote3status"
            ]);
            $header = array_merge($header ,["打分状态","第1轮投票","第1轮状态","第2轮投票","第2轮状态","第3轮投票","第3轮状态"]);
            $width  = array_merge($width ,['15','15','15','15','8','8','8']);
        }else{
            array_push($field,'xr_status');
            array_push($header,'打分状态');
            array_push($width,'15');
        }
        // 拼接投票信息，不需要时直接注释
        $data = $model->field($field)
            ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
            ->where($where)
            ->order("xm_class,xm_ordernum,user_realusername")
            ->select();
        foreach($data as $keys=>$item){
            if($item['ps_total'] == -1){
                $data[$keys]['ps_total'] = '回避';
                foreach($markField as $fields) $data[$keys][$fields] = '回避';
                if($TOUPIAO == 1){
                    $data[$keys]['vote1'] = '回避';
                    $data[$keys]['vote2'] = '回避';
                    $data[$keys]['vote3'] = '回避';
                    unset($data[$keys]['vote1option']);
                    unset($data[$keys]['vote2option']);
                    unset($data[$keys]['vote3option']);
                }
            }else if($TOUPIAO == 1) {
                if ($item['vote1option'] == 0) {
                    $data[$keys]['vote1'] = '不参与本轮投票';
                }else{
                    $data[$keys]['vote1'] = $voteOption[$data[$keys]['vote1']];
                }
                unset($data[$keys]['vote1option']);
                if ($item['vote2option'] == 0) {
                    $data[$keys]['vote2'] = '不参与本轮投票';
                }else{
                    $data[$keys]['vote2'] = $voteOption[$data[$keys]['vote2']];
                }
                unset($data[$keys]['vote2option']);
                if ($item['vote3option'] == 0) {
                    $data[$keys]['vote3'] = '不参与本轮投票';
                }else{
                    $data[$keys]['vote3'] = $voteOption[$data[$keys]['vote3']];
                }
                unset($data[$keys]['vote3option']);
            }
        }
        $title = C('mark.REMARK_OPTION')[$queryParam['xm_type']]['exportTitle'];
        echo excelExport($header, $data, true,$width,true,true,true,$title);
    }

}