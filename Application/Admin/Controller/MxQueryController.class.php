<?php
namespace Admin\Controller;
use Think\Controller;
class MxQueryController extends BaseController
{
    public $professorid;

    function _initialize(){
        $this->professorid = C('PROFESSERID');
    }

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
        $where['user_role']     = ['eq', C('PROFESSERID')];
        $data = $userModel->field('user_id,user_realusername')->where($where)->select();
        $this->assign("user", $data);

        $markOption = $this->getAllMarkField();
        $this->assign('markOption',$markOption);
        $this->getDic();
        $this->display();
    }

    /**
     * 获取列表
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
        // 拼接所有要查的字段
        $field = "xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_class,xm_type,xr_id,xr_status,ishuibi,ps_zz,ps_detail,0+cast(ps_total as char) as ps_total,user_realusername,vote1,vote2,vote3,vote1status,vote2status,vote3status";
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

    /**
     * 导出专家签字表选择专家页面
     */
    public function expertexport()
    {
        $userModel = M("sysuser");
        $where = [];
        $where['user_isdelete'] = ['eq', '0'];
        $where['user_issystem'] = ['eq', '否'];
        $where['user_role']     = ['eq',$this->professorid];
        $data = $userModel->field('user_id,user_name,user_realusername')->where($where)->select();
        $this->assign("user", $data);
        $this->display();
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
            $zzTitle   = "与战斗力关联程度";                // ps_zz列表头设置
            $isZD      = C('isZD');                       // 是否有与战斗力关联
            $markTitle = removeArrKey($markInfo,'brief'); // 会评时打分列表头
        }else{
            $zzTitle   = "定性评价";                      // ps_zz列表头设置
            $isZD      = 1;                              // 是否有与战斗力关联
            $markTitle = removeArrKey($markInfo,'mainpoints');// 函评时打分列表头
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
                "vote1status",
                "vote2status",
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
            if($item['ps_total'] == -1){ // 回避时投票字段需设置为回避
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
            }else if($TOUPIAO == 1) { // 不参与投票时需设置
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

    /**
     * 签字表导出
     * @throws \PHPExcel_Writer_Exception
     */
    public function exportForExpert()
    {
        $queryParam = I('post.');
        $xm_id = trim($queryParam['xm_id']);
        $where = [];
        if (!empty($xm_id)) {
            $where['xm_id'] = ['eq', $xm_id];
        }
        if (!empty($queryParam['xm_class'])) {
            $where['xm_class'] = ['like', '%'.$queryParam['xm_class'].'%'];
        }
        if (!empty($queryParam['xm_user'])) {
            $where['xr_user_id'] = ['eq', $queryParam['xm_user']];
        }
        $map['user_id'] = Array("eq",$queryParam['xm_user']);
        $username = M("sysuser")->where($map)->find();
        $model = M('xmps_xm');// 专家是否打完分
        $isFinish = M('xmps_xmrelation')
            ->alias('t')
            ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
            ->where("xr_user_id='%s' and xr_status='进行中'",$queryParam['xm_user'])
            ->count();
        if($isFinish != 0) exit(makeStandResult(0,'当前专家未提及，请等专家提交后再导出签字表！'));

        // 拼接列
        $fields = ['xm_code','xm_name','xm_company','xm_createuser','xm_type'];
        if(C('isZZ')){
            $fields[] = "case when ishuibi = 0 then ps_zz else '回避' end ps_zz";
        }
        $fields = array_merge($fields,["case when ishuibi = 0 then ps_total else '回避' end ps_total","case when ishuibi = 0 then ps_detail else '回避' end ps_detail"]);

        $data = $model->field($fields)
            ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->join("sysuser on user_id=xmps_xmrelation.xr_user_id and user_isdelete='0'")
            ->join("left join xmps_typeorder o on o.type_name = xmps_xm.xm_type")
            ->where($where)
            ->order("xm_type,xm_ordernum asc,ps_total desc,xm_code asc")
            ->select();
        $this->addLog('','明细查询','','导出','成功');
        // 拼表头，列宽
        $header = ['项目编号','项目名称','单位','申请人','申报类别'];
        $width  = ["5","10","20","10","10","15"];
        if(C('isZZ')){
            $header[] = '资助意见';
            $width[]  = '10';
        }
        $header = array_merge($header,['总分','评审意见']);
        $width  = array_merge($width,['5','45']);

        // $header = array(,'申报类别','总分','评审意见');
        // $width = Array(,'10',"5",'50');
        $title = C('ExportTitle')."专家评审意见汇总表";

        echo excelExport($header, $data, true,$width,true,true,true,$title,"分组：".$username["user_class"]."          评审专家：".$username["user_realusername"],array(1,3),$username["user_realusername"]);
    }

}