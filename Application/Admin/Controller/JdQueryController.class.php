<?php
namespace Admin\Controller;
use Think\Controller;
class JdQueryController extends BaseController
{

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
        case when wanchengcount is null then 0 else wanchengcount end wanchengcount,
        case when allcount is null then 0 else allcount end allcount,
        0+cast(num as char) as num,
        ps_detail
        /* 投票字段*/
        ,case when wanchengvote1count is null then 0 else wanchengvote1count end wanchengvote1count,
        case when vote1num is null then 0 else vote1num end vote1num,
        vote1rate,
        case when wanchengvote2count is null then 0 else wanchengvote2count end wanchengvote2count,
        case when vote2num is null then 0 else vote2num end vote2num,
        vote2rate,
        case when wanchengvote3count is null then 0 else wanchengvote3count end wanchengvote3count,
        case when vote3num is null then 0 else vote3num end vote3num,
        vote3rate
        ')
            ->join("left join (select xr_xm_id,count(xr_id) wanchengcount,max(avgvalue) as num,max(ps_detail) ps_detail from xmps_xmrelation a,sysuser b where b.user_id=a.xr_user_id and user_isdelete='0' and xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id")
            ->join("left join (select xr_xm_id,count(xr_id) allcount  from xmps_xmrelation a,sysuser b where b.user_id=a.xr_user_id and user_isdelete='0' group by xr_xm_id) b on xmps_xm.xm_id=b.xr_xm_id")
            // 第一轮投票信息
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote1count, sum(vote1) as vote1num,max(vote1rate) vote1rate from xmps_xmrelation a, sysuser b where b.user_id = a.xr_user_id and user_isdelete = '0' and vote1status = '已完成' group by xr_xm_id) v1 on xmps_xm.xm_id = v1.xr_xm_id")
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote2count, sum(vote2) as vote2num,max(vote2rate) vote2rate from xmps_xmrelation a, sysuser b where b.user_id = a.xr_user_id and user_isdelete = '0' and vote2status = '已完成' group by xr_xm_id) v2 on xmps_xm.xm_id = v2.xr_xm_id")
            ->join("left join (select xr_xm_id, count(xr_id) wanchengvote3count, sum(vote3) as vote3num,max(vote3rate) vote3rate from xmps_xmrelation a, sysuser b where b.user_id = a.xr_user_id and user_isdelete = '0' and vote3status = '已完成' group by xr_xm_id) v3 on xmps_xm.xm_id = v3.xr_xm_id")
            ->where($where)
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        $count = $model->where($where)->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
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

    //大表 明细导出
    public function exportxx()
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
        $relationmodel = M('xmps_xmrelation');
        $getmax = "select max(x) num
                from (
                    select count(*) x,xr_xm_id from xmps_xmrelation group by xr_xm_id
                ) a ";
        $num = 0;
        $maxdata = $model->query($getmax);
        if (!empty($maxdata))
            $num = intval($maxdata[0]["num"]);
        $data = $model->field('xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_class')
            ->where($where)
            ->order('xm_code asc')
            ->select();
        foreach ($data as $key => $d) {
            $relationdata = $relationmodel->field("ps_total,user_realusername,ps_zz,ps_detail")
                ->join("left join sysuser on xr_user_id=user_id")
                ->where("xr_xm_id='" . $d["xm_id"] . "'")
                ->select();
            $sum_zz = Array();
            $sum = Array();
            foreach ($relationdata as $rdata) {
                array_push($data[$key], $rdata["user_realusername"]);
                array_push($data[$key], $rdata["ps_total"]);
                array_push($data[$key], $rdata["ps_zz"]);
                array_push($data[$key], $rdata["ps_detail"]);
                array_push($sum_zz, $rdata["ps_zz"]);
                array_push($sum, $rdata["ps_total"]);

            }
            sort($sum_zz);
            $sum_zz = implode($sum_zz);
            array_push($data[$key], $sum_zz);
            array_push($data[$key], array_sum($sum));
            array_push($data[$key], number_format(array_sum($sum) / count($sum), 2));
            unset($data[$key]["xm_id"]);
        }
        $this->addLog('', '明细查询', '', '导出', '成功');
        $header = array('项目编号', '项目名称', '单位', '申请人', '分组');
        $width = Array("5", "10", "30", "15", "15", "15");

        for ($i = 0; $i < $num; $i++) {
            array_push($header, "专家" . ($i + 1));
            array_push($width, "10");
            array_push($header, "分数");
            array_push($width, "10");
            array_push($header, "资助意见");
            array_push($width, "10");
            array_push($header, "评审意见");
            array_push($width, "30");
        }
        array_push($header, '综合评审意见');
        array_push($width, '30');
        array_push($header, '总分');
        array_push($width, '20');
        array_push($header, '平均分');
        array_push($width, '20');
        echo excelExport($header, $data, true, $width);
    }

    /**
     * 明细数据页
     */
    public function getpingshen()
    {
        $queryParam = I('get.');
        $xmType = M('xmps_xm')->where("xm_id = '%s'",$queryParam["xm_id"])->getField('xm_type');
        $this->assign('xm_id', $queryParam["xm_id"]);
        $this->assign('status', $queryParam["status"]);
        $this->assign('type', $queryParam["type"]);

        $markOption = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $this->assign('markOption',$markOption);
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
        $field = ["user_realusername","xr_id","ps_zz","ps_detail","ps_total","xr_status","ishuibi"];
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
        $xmType   = I("post.xmType");
        $type     = I("post.type");
        $model   = M('xmps_xmrelation');
        try {
            $model->startTrans();
            if($type == ''){ // 打分回退
                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where xm_type = '$xmType') and xr_status='完成'")->select();
                $data = array();
                foreach ($relationdata as $rd) {
                    $data["xr_status"] = "进行中";
                    $data["xr_id"] = $rd["xr_id"];
                    $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                }
            }else if($type == 'vote1'){ // 第一轮投票回退
                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where and xm_type = '$xmType') and vote1status='已完成'")->select();
                $data = array();
                foreach ($relationdata as $rd) {
                    $data["vote1status"] = "进行中";
                    $data["xr_id"] = $rd["xr_id"];
                    $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                }
            }else if($type == 'vote2'){ // 第二轮投票回退
                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where and xm_type = '$xmType') and vote2status='已完成'")->select();
                $data = array();
                foreach ($relationdata as $rd) {
                    $data["vote2status"] = "进行中";
                    $data["xr_id"] = $rd["xr_id"];
                    $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
                }
            }else if($type == 'vote3'){ // 第三轮投票回退
                $relationdata = $model->where("xr_user_id='" . $user_id . "' and xr_xm_id in (select xm_id from xmps_xm where and xm_type = '$xmType') and vote3status='已完成'")->select();
                $data = array();
                foreach ($relationdata as $rd) {
                    $data["vote3status"] = "进行中";
                    $data["xr_id"] = $rd["xr_id"];
                    $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
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
     * 结果表导出
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
     public function resultexport()
    {
        $queryParam = I("post.");
        if (!empty($queryParam['xm_class'])) {
            $psType    = trim($queryParam['psType']);  // 投票类型
            $TOUPIAO   = trim($queryParam['TOUPIAO']); // 是否打分
            // 是否有与战斗力关联
            if($psType == 'huiping'){
                $zzTitle   = "与战斗力关联程度";
                $isZD      = C('isZD');
            }else{
                $zzTitle   = "定性评价";
                $isZD      = 1;
            }
            $where['xm_class']  = ['eq', $queryParam["xm_class"]];
            $where['xm_type']   = ['eq', $queryParam["xm_type"]];
            $model = M('xmps_xm');
            $field = ["xm_code","xm_name","xm_company","xm_createuser","avgvalue"];
            $width = [7, 15, 25, 30, 15, 15];
            $title = ["序号", "项目编号", "项目名称", "依托单位", "申请人", "平均分"];
            if($isZD == 1){
                array_push($field,'ps_detail');
                array_push($title,$zzTitle);
                array_push($width,15);
            }
            array_push($field,'null as remark');
            array_push($title,'备注');
            $data = $model->field($field)
                ->join("left join (select xr_xm_id,max(avgvalue) as avgvalue,max(ps_detail) as ps_detail from xmps_xmrelation where xr_status='完成' group by xr_xm_id) a on xmps_xm.xm_id=a.xr_xm_id")
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
            $excel->getActiveSheet()->setCellValue('A2', "分组名称：" . $queryParam['xm_class'])->mergeCells('A2:C2');
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