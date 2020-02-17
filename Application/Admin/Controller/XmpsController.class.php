<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class XmpsController extends BaseController {

    /**
     * 专家打分页面(会评)
     */
    public function index(){
        $model = M('xmps_xmrelation');
        $xmType = I('get.type','');
        if($xmType == ''){ // 若地址栏获取的课题分类为空，则取mark_config文件配置的第一个课题分类内容
            $xmType = array_keys(C('mark.REMARK_OPTION'))[0];
        }else if($xmType == 'da'){
            $xmType = "重大";
        }else if($xmType != 'da'){
            $xmType = "重点";
        }
        $where = [];
        $where['xr_user_id'] = ['eq',session("user_id")];
        $where['xr_status']  = ['eq','进行中'];
        if($xmType) $where['xm_type']  = ['eq',$xmType];
        $relationdata = $model
            ->alias('t')
            ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
            ->where($where)
            ->count();
//        echo $model->_sql();die;
        $markOption = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $markRule   = C('mark.REMARK_OPTION')[$xmType]['注意事项'];
        if(!empty($markRule) && !empty($markRule[0])){
            $markRule = '<p class="tips">'.implode('</p><p class="tips">',$markRule)."</p>";
        }else{
            $markRule = '';
        }
        $markField  = removeArrKey($markOption,'field');
        $this->assign('markRule',$markRule);
        $this->assign('markOption',$markOption);
        $this->assign('markField',$markField);
        $this->assign('markOptionJson',json_encode(array_values($markOption)));
        $this->assign('markFieldJson',json_encode($markField));
        $this->assign("xmType",$xmType);
        if($relationdata>0){
            $this->display('score');
        }else{
            $this->display('index');
        }
    }

    /**
     * 专家打分页面（函评）
     */
    public function markIndex(){
        $this->display();
    }

    /**
     * 获取列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);
        $where = [];
//        $where['xm_class']  =['eq',session('classid')];
        if($queryParam['xr_status']) $where['xr_status'] = ['eq',$queryParam['xr_status']];
        if($queryParam['xm_type']){ // 会评表格列表方式
            $where['xm_type']  = ['eq',$queryParam['xm_type']];
            $markOption  = C('mark.REMARK_OPTION')[$queryParam['xm_type']]['评价内容'];
            $markField   = removeArrKey($markOption,'field');
            // 去掉小数点后面的多余的尾0
            foreach($markField as $key=>$val){
                $markField[$key] = "0+cast(".$val." as char) as ".$val;
            }
        }else{ // 函评页面方式
            $markField  = $this->getAllMarkFieldFormat();
        }
        $markFields   = implode(",",$markField);

        $where['xr_user_id'] = array('eq' ,session("user_id"));
        $model = M('xmps_xm');
        $data = $model->field('xm_id,xm_ordernum,xm_tmfs,xm_code,ishuibi,xm_name,'.$markFields.',xm_company,xm_createuser,xm_class,xm_oldfenzu,xm_oldrank,xm_oldscore,xm_oldcommand,xr_id,xr_status,ps_zz,xm_type,xm_group,0+cast(ps_total as char) as ps_total')
            ->join('xmps_xmrelation on xr_xm_id = xmps_xm.xm_id')
            ->where($where)
            ->order($queryParam['sort']." ".$queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        // 会评没有小数点
//        foreach ($data as $key=>$val){
//            foreach ($markField as $field){
//                $data[$key][$field] = round($val[$field]);
//            }
//        }
        $count = $model->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')->where($where)->count();
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }

    /**
     * 项目打分页面（函评）
     */
    public function marking(){
        $id         = I("get.id");
        $model      = M('xmps_xm');
        $markField  = $this->getAllMarkFieldFormat();
        $markField  = implode(",",$markField);
        $data  = $model->field('xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_type,xr_id,'.$markField.',ps_zz,ps_detail,0+cast(ps_total as char) as ps_total')
            ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->where("xr_id='".$id."'")
            ->find();
        if($data['xm_type']){
            $xm_type = trim($data['xm_type']);
            $remarkConfig = C('mark.REMARK_OPTION')[$xm_type];
            if($remarkConfig){
                $this->assign("title",$remarkConfig['title']);// 标题
                $this->assign("standrad",$remarkConfig['评价内容']);// 评价内容
                // 打分字段
                $markField = removeArrKey($remarkConfig['评价内容'],'field');
                $this->assign('markField',json_encode($markField));

                $this->assign("notice","<p>".implode("</p><p>",$remarkConfig['注意事项'])."</p>");// 注意事项
                $this->assign("advise",$remarkConfig['评审意见']);// 评审意见
                $ziZhu = empty($remarkConfig['定性评价'])?0:1;
                $this->assign("isZiZhu",$remarkConfig['定性评价']);// 定性评价
//                dump($remarkConfig['评价内容']);die;
            }
        }
        $this->assign("data",$data);
        $this->assign("offset",I("get.offset"));
        $this->assign("limit",I("get.limit"));
        $this->display();
    }

    /**
     * 项目打分页面查看结果（函评）
     */
    public function showmarking(){
        $id    = I("get.id");
        $model = M('xmps_xm');
        $markField  = $this->getAllMarkFieldFormat();
        $markField  = implode(",",$markField);
        $data = $model->field('xm_id,xm_code,xm_name,xm_company,xm_createuser,xm_type,xr_id,'.$markField.',ps_zz,ps_detail,ishuibi,0+cast(ps_total as char) as ps_total')
            ->join('xmps_xmrelation on xr_xm_id=xmps_xm.xm_id')
            ->where("xr_id='".$id."'")
            ->find();
        if($data['xm_type']){
            $xm_type = trim($data['xm_type']);
            $remarkConfig = C('mark.REMARK_OPTION')[$xm_type];
//            dump($remarkConfig);die;
            if($remarkConfig){
                $this->assign("title",$remarkConfig['title']);// 标题
                $this->assign("standrad",$remarkConfig['评价内容']);// 评价内容
                $this->assign("notice","<p>".implode("</p><p>",$remarkConfig['注意事项'])."</p>");// 注意事项
                $this->assign("advise",$remarkConfig['评审意见']);// 评审意见
                $ziZhu = empty($remarkConfig['定性评价'])?0:1;
                $this->assign("isZiZhu",$remarkConfig['定性评价']);// 定性评价
                // 过滤数据取小数点
                $ps_total = 0;
                foreach($remarkConfig['评价内容'] as $key=>$val){
                    if($val['type'] == 'input' && $data[$val['field']] != null){
                        $decimalpoint = isset($val['decimalpoint'])?intval($val['decimalpoint']):0;
                        $data[$val['field']] = round($data[$val['field']],$decimalpoint);
                        $ps_total += $data[$val['field']];
                    }
                }
                $data['ps_total'] = $ps_total;
            }
        }
//        $data["ps_detail"]=str_replace("$","\n",$data["ps_detail"]);
        $this->assign("data",$data);
        $this->assign("offset",I("get.offset"));
        $this->assign("limit",I("get.limit"));
        $this->display();
    }

    /**
     * 保存评审结果（函评）
     */
    public function saveps(){
        $data = I("post.");
        foreach($data as $key=>$d) {
            if ($d == "")
                $data[$key] = null;
        }
        // 获取所有打分字段求和
        $remarkConfig = C('mark.REMARK_OPTION')[trim($data['xm_type'])]['评价内容'];
        $markField    = removeArrKey($remarkConfig,'field');
        $ps_total = 0;
        foreach($markField as $field){
            if(!isset($data[$field])) continue;
            $ps_total += $data[$field];
        }
        $data["ps_total"] = $ps_total;
        $data["ps_time"]  = time();
        try {
            $model = M('xmps_xmrelation');
            $model->where("xr_id='" . $data["xr_id"] . "'")->save($data);
            // 求平均
            $xmid    = $data["xm_id"];
            $xmtotal = $model->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0")->order("ps_total")->select();
//            echo $model->_sql();
//            dump($xmtotal);die;
            $xmcount = count($xmtotal);
            if($xmcount>2) {
                unset($xmtotal[0]);
                unset($xmtotal[$xmcount - 1]);
                $total = 0;
                foreach ($xmtotal as $t) {
                    $total += floatval($t["ps_total"]);
                }
                $model->where("xr_xm_id='".$xmid."'")->setField("avgvalue",  number_format($total / ($xmcount - 2),3, '.', ''));
            }
            exit(makeStandResult(0,''));
        }catch (Exception $e){
            exit(makeStandResult(1,'错误信息'.$e));
        }
    }

    /**
     * 设置回避项目（函评）
     */
    public function setHuibi(){
        $xr_id  = I('post.xrid');
        $xmType = I('post.xmType');
        if(empty($xr_id)) exit(makeStandResult(1,'参数缺失，请刷新重试！'));
        $xr_status = M('xmps_xmrelation')->where("xr_id='".$xr_id."'")->getField('xr_status');
        if($xr_status == '完成') exit(makeStandResult(2,'该项目已提交，请刷新页面！'));
        // 获取所有打分字段
        $remarkConfig = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $markField    = removeArrKey($remarkConfig,'field');
        $data              = [];
        foreach($markField as $field){
            $data[$field] = null;
        }
        $data['ps_zz']     = null;
        $data['ps_total']  = null;
        $data['ps_detail'] = null;
        $data['ishuibi']   = I('post.ishuibi',0);
        M('xmps_xmrelation')->where("xr_id='".$xr_id."'")->setField($data);
        exit(makeStandResult(0,''));
    }

    /**
     * 打分逐条保存(会评)
     */
    public function saveScore(){
        $data        = I("post.data");
        $xmType      = I('post.xmType','');
        $quxiaohuibi = I('post.quxiaohuibi','0');
        $markOption  = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $markField   = removeArrKey($markOption,'field');
        $M           = M('xmps_xmrelation');
        try{
            $M->startTrans();
            $res = $M->where($data)->count();
            if($res>0){
                exit(makeStandResult(0,'数据已保存'));
            }
            if($quxiaohuibi == 1){
                foreach($markField as $field){
                    $data[$field] = null;
                }
                $data['ps_total']  = null;
                $data['ps_detail'] = null;
                $data['ps_zz']     = null;
                $data['vote1']     = null;
                $data['vote2']     = null;
                $data['vote3']     = null;
            }else if($data['ishuibi'] == 1){
                foreach($markField as $field){
                    $data[$field] = -1;
                }
                $data['ps_total']  = -1;
                $data['ps_detail'] = -1;
                $data['ps_zz']     = -1;
                $data['vote1']     = -1;
                $data['vote2']     = -1;
                $data['vote3']     = -1;
            }else{
                if(empty($xmType)) exit(makeStandResult(3,'参数缺失，请刷新页面重试！'));
                $markOption     = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
                $allRemarkField = $this->getAllMarkField();
                $markField      = removeArrKey($markOption,'field');
                $total          = 0;
                foreach($allRemarkField as $field){
                    // 不存在的字段销毁
                    if(!in_array($field,$markField) && $data[$field] == ''){
                        unset($data[$field]);
                    }
                    if(in_array($field,$markField) && $data['ishuibi'] != 1){
                        // 计算total值
                        $total += round($data[$field]);
                        // 判断范围
                        if($data[$field] == '' || round($data[$field])>round($markOption[$field]['maxVal']) || round($data[$field])<round($markOption[$field]['minVal'])){
                            exit(makeStandResult(2,'项目'.$data['xm_name']."：".$markOption[$field]['title'].'（分数值：'.$data[$field].'）<br/>不在取值范围内（'.$markOption[$field]['minVal'].'-'.$markOption[$field]['maxVal'].'），请刷新页面重新填写！'));
                        }
                    }
                }
                if($total != $data['ps_total'] && $data['ps_total'] != -1){ // 验证总分
                    exit(makeStandResult(1,'项目'.$data['xm_name'].'总分计算有误，请刷新页面重试！'.$total."---".$data['ps_total']));
                }
            }
            // 保存
            $M->where("xr_id='".$data['xr_id']."'")->save($data);
            // 写入平均分
            $xmid    = $data["xm_id"];
            $xmtotal = $M->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0")->order("ps_total")->select();
            $xmcount = count($xmtotal);
            if($xmcount>2) {
                unset($xmtotal[0]);
                unset($xmtotal[$xmcount - 1]);
                $total = 0;
                foreach ($xmtotal as $t) {
                    $total += intval($t["ps_total"]);
                }
                $M->where("xr_xm_id='".$xmid."'")->setField("avgvalue",  number_format($total / ($xmcount - 2),3, '.', ''));
            }
            // 将是否与战斗关联写入ps_detail
            if(C('isZD') != 0){
                $ps_zz = $M->field('ps_zz')->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0 ")->order('ps_zz asc')->select();
                $ps_zz     = removeArrKey($ps_zz,'ps_zz',false);
                $ps_detail = implode('',$ps_zz);
                if(!empty($ps_zz)){
                    $M->where("xr_xm_id='".$xmid."'")->setField("ps_detail",  $ps_detail);
//                    echo $M->_sql();die;
                }
            }
            $M->commit();
            exit(makeStandResult(0,'保存成功'));
        }catch (Exception $e){
            exit(makeStandResult(9,'保存失败，错误详情：'.$e));
            $M->rollback();
        }

    }

    /**
     * 全部保存
     */
    public function saveScoreAll(){
        $updateData = I("post.updateData");
        $xmType     = I('post.xmType');
        if(empty($xmType)) exit(makeStandResult(3,'参数缺失，请刷新页面重试！'));
        $M = M('xmps_xmrelation');
        // 该课题分类的评审打分字段
        $markOption     = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $markField      = removeArrKey($markOption,'field');
        // 所有的评审打分字段
        $allRemarkField = $this->getAllMarkField();
        try{
            $M->startTrans();
            foreach($updateData as $data){
                $res = $M->where($data)->count();
                if($res>0){
                    continue;
                }
                if($data['ishuibi'] == 1){
                    foreach($markField as $field){
                        $data[$field] = -1;
                    }
                    $data['ps_total']  = -1;
                    $data['ps_detail'] = -1;
                    $data['ps_zz']     = -1;
                    $data['vote1']     = -1;
                    $data['vote2']     = -1;
                    $data['vote3']     = -1;
                }else{
                    $total = 0;
                    foreach($allRemarkField as $field){
                        // 不存在的字段销毁
                        if(!in_array($field,$markField) && $data[$field] == ''){
                            unset($data[$field]);
                        }
                        if(in_array($field,$markField) && $data['ishuibi'] != 1){
                            // 计算total值
                            $total += round($data[$field]);
                            // 判断范围
                            if($data[$field] == '' || round($data[$field])>round($markOption[$field]['maxVal']) || round($data[$field])<round($markOption[$field]['minVal'])){
                                exit(makeStandResult(2,'项目'.$data['xm_name']."：".$markOption[$field]['title'].'（分数值：'.$data[$field].'）<br/>不在取值范围内（'.$markOption[$field]['minVal'].'-'.$markOption[$field]['maxVal'].'），请刷新页面重新填写！'));
                            }
                        }
                    }
                    if($total != $data['ps_total'] && $data['ps_total'] != -1){
                        exit(makeStandResult(1,'项目'.$data['xm_name'].'总分计算有误，请刷新页面重试！'.$total."---".$data['ps_total']));
                    }
                }
                $M->where("xr_id='".$data['xr_id']."'")->save($data);
                // 写入平均分
                $xmid    = $data["xm_id"];
                $xmtotal = $M->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0")->order("ps_total")->select();
                $xmcount = count($xmtotal);
                if($xmcount>2) {
                    unset($xmtotal[0]);
                    unset($xmtotal[$xmcount - 1]);
                    $total = 0;
                    foreach ($xmtotal as $t) {
                        $total += intval($t["ps_total"]);
                    }
                    $M->where("xr_xm_id='".$xmid."'")->setField("avgvalue",  number_format($total / ($xmcount - 2),3, '.', ''));
                }
                // 将是否与战斗关联写入ps_detail
                if(C('isZD') != 0){
                    $ps_zz = $M->field('ps_zz')->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0 ")->order('ps_zz asc')->select();
                    $ps_zz     = removeArrKey($ps_zz,'ps_zz',false);
                    $ps_detail = implode('',$ps_zz);
                    if(!empty($ps_zz)){
                        $M->where("xr_xm_id='".$xmid."'")->setField("ps_detail",  $ps_detail);
                    }
                }
//            echo $M->_sql();die;
            }
            $M->commit();
            exit(makeStandResult(0,'保存成功'));
        }catch (\Exception $e){
            $M->rollback();
            exit(makeStandResult(1,'错误信息'.$e));
        }
    }

    /**
     * 判断某一课题分类下专家打分是否已完成（会评）
     */
    public function panduanpswancheng()
    {
        $xmType = I('post.xmType');
        if(empty($xmType)){
            exit(makeStandResult(1,'参数缺失，请刷新页面重试'));
        }
        $model = M('xmps_xmrelation');
        $relationdata = $model
            ->alias('t')
            ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
            ->where("xr_user_id='" . session("user_id") . "' and xr_status='进行中' and m.xm_type = '$xmType'")
            ->select();
//        echo $model->_sql();die;
//        dump($relationdata);die;
        $data       = [];
        $markOption = C('mark.REMARK_OPTION')[$xmType]['评价内容'];
        $markField  = removeArrKey($markOption,'field');
        $tips = [];
        foreach ($relationdata as $rd) {
            if($rd['ishuibi']!='1'){
                foreach($markField as $field){
                    if(!in_array('<li>'.$rd['xm_code']. '</li>',$tips) && ($rd[$field] == "")){
                        $tips[] ='<li>'.$rd['xm_code'] . '</li>';
                    }
                }
            }
        }
        $tips = implode('',$tips);
        if ($tips != "") {
            exit(makeStandResult(2,'<p>以下项目尚未评审完毕，请评审完毕后再提交</p><ul>'.$tips .'</ul>'));
        }
        exit(makeStandResult(0,''));
    }

    /**
     * 判断专家打分是否已完成（函评）
     */
    public function panduanpswanchenghan()
    {
        $model = M('xmps_xmrelation');
        $relationdata = $model
            ->alias('t')
            ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
            ->where("xr_user_id='" . session("user_id") . "' and xr_status='进行中' and ishuibi != 1")
            ->select();
        $data         = [];
        $remarkConfig = C('mark.REMARK_OPTION');
        $str          = "";
        foreach ($relationdata as $rd) {
            $markField = [];
            $xm_type   = $rd['xm_type'];
            if(C('mark.REMARK_OPTION')[$xm_type]['评价内容']){
                $markField = C('mark.REMARK_OPTION')[$xm_type]['评价内容'];
                $markField = array_column($markField, 'field');
            }else{
                exit(makeStandResult(1,$xm_type."类型下评价内容缺失"));
            }
            $isZiZhu   = empty(C('mark.REMARK_OPTION')[$xm_type]['定性评价'])?0:1;
            $isDetail  = C("isDetail");
            if($isZiZhu) array_push($markField,'ps_zz');
            if($isDetail) array_push($markField,'ps_detail');
            foreach($markField as $field){
                if($rd[$field] == ""){
                    $str .='<li>'.$rd['xm_code'] . '</li>';
                    break;
                }
            }
        }
        $str = rtrim($str, ',');
        if ($str != "") {
            exit(makeStandResult(2,'<p>以下项目尚未评审完毕，请评审完毕后再提交</p><ul>'.$str .'</ul>'));
        }
        exit(makeStandResult(0,''));
    }
    /**
     * 专家打分提交（改状态）(函评)
     */
    public function savepswanchenghan()
    {
        $model = M('xmps_xmrelation');
        $model->startTrans();
        try {
            $relationdata = $model
                ->alias('t')
                ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
                ->where("xr_user_id='" . session("user_id") . "' and xr_status='进行中'")
                ->select();
            $xr_ids = removeArrKey($relationdata,'xr_id');
            $data["xr_status"] = "完成";
            $model->where(["xr_id"=>['in',$xr_ids]])->save($data);
            $model->commit();
            exit(makeStandResult(0,''));
        } catch (Exception $e) {
            $model->rollback();
            exit(makeStandResult(1,'错误信息'.$e));
        }
    }

    /**
     * 专家打分提交（改状态）（会评）
     */
    public function savepswancheng()
    {
        $xmType = I('post.xmType');
        if(empty($xmType)){
            exit(makeStandResult(1,'参数缺失，请刷新页面重试'));
        }
        $model = M('xmps_xmrelation');
        $model->startTrans();
        try {
            $relationdata = $model
                ->alias('t')
                ->join('left join xmps_xm m on m.xm_id=t.xr_xm_id')
                ->where("xr_user_id='" . session("user_id") . "' and xr_status='进行中' and m.xm_type = '$xmType'")
                ->select();
            $xr_ids = removeArrKey($relationdata,'xr_id');
            $data["xr_status"] = "完成";
            $model->where(["xr_id"=>['in',$xr_ids]])->save($data);
            $model->commit();
            exit(makeStandResult(0,''));
        } catch (Exception $e) {
            $model->rollback();
            exit(makeStandResult(2,'提交失败，错误详情：'.$e));
        }
    }

    public function showfile()
    {
        $xm_name=I("get.xm_name");
        $filepath=C("xmfilepath");
        $xm_name=iconv('utf-8','gb2312',$xm_name);
        $html = file_get_contents($filepath . $xm_name);
        //下载xml
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:" . strlen($html));
        header("Content-Disposition:attachment;filename=" . $xm_name);
        echo $html;
        die;
    }

    /**
     * 保存设置的投票
     */
    public function setVoteData()
    {
//        print_r(I('post.'));die;
        $voteData = I("post.voteData");
        $round    = I("post.round",'0');
        $type     = I("post.type");
        if($round == 0) exit(makeStandResult(1,'参数缺失，请重试'));

        // 查询投票是否还在当前轮次
        $class = session('classid');
        $nowround = 0; // 当前投票进行的轮次
        $roundInfo = M('votesetting')->where("class = '".$class."'")->select();
        if($roundInfo){
            foreach($roundInfo as $key=>$val){
                if($val['status'] == 1){
                    $nowround = $val['round'];
                }
            }
        }
        if($nowround != $round) exit(makeStandResult(1,'当前投票已结束，请刷新页面'));
        $voteround = 'vote'.$round;
        // 投票数是否超出最大数
        $maxnum = M('votesetting')->where("class = '".$class."' and round = '".$round."' and status =1")->getField('maxnum');
        $totalvotenum = 0;
        foreach($voteData as $key=>$val){
            $voteres  = $val[$voteround];
            if($voteres == '1') $totalvotenum++;
        }
        if(intval($totalvotenum) > intval($maxnum)) exit(makeStandResult(1,'投票数已超过最大投票数，请修改！'));
        $Model = M("xmps_xmrelation");
        try{
            $Model->startTrans();
            $votestatus = 'vote'.$round.'status';
//            echo $votestatus;die;
            foreach($voteData as $key=>$val){
                $voteres  = $val[$voteround];
                $data     = [];
                $xr_id    = $val['xr_id'];
                if($voteres != '-1'){
                    if($voteres){
                        $data[$voteround] = 1;
                    }else if($voteres === '0'){
                        $data[$voteround] = 0;
                    }else{
                        $data[$voteround] = '-2';
                    }
                }
                if($type == 'submit'){
                    $data[$votestatus] = '已完成';
                }
                if(!empty($data)){
                    $Model->where("xr_id = '".$xr_id."'")->setField($data);
//                    echo $Model->_sql();die;
                }
            }
            $Model->commit();
            exit(makeStandResult(0,'保存成功'));
        }catch (Exception $e){
            $Model->rollback();
            exit(makeStandResult(1,'保存失败:$e'));
        }
    }

    public function getVoteData()
    {
//        $where['xm_status']=['neq','删除'];
        $where['xm_class']=['eq',session('classid')];
        $where['xr_status']=['eq','完成'];

        $where['xr_user_id'] = array('eq' ,session("user_id"));
        $model = M('xmps_xm x');
        $data = $model->field('xm_id,xm_ordernum,xm_tmfs,xm_code,ishuibi,xm_name,ps_item2,ps_item3,ps_item5,xm_company,xm_createuser,xm_class,xm_oldfenzu,xm_oldrank,xm_oldscore,xm_oldcommand,xr_id,xr_status,ps_zz,ps_total,vote1,vote2,vote3,vote1option,vote2option,vote3option,avgvalue,vote1status,vote2status,vote3status')
            ->join('xmps_xmrelation r on r.xr_xm_id=x.xm_id')
            ->where($where)
            ->order("avgvalue desc,ps_total desc")
            ->select();
//        if($data){
//            foreach($data as $key=>$val){
//                $info = '项目编号：'.$val['xm_code']."<br/>";
//                $info .= '项目名称：'.$val['xm_name']."<br/>";
//                $info .= '依托单位：'.$val['xm_company']."<br/>";
//                $info .= '申请人：'.$val['xm_createuser']."<br/>";
//                $info .= '推荐方式：'.$val['xm_tmfs']."<br/>";
//                $data[$key]['xm_info'] = $info;
//            }
//        }
        $count = $model->join('xmps_xmrelation on xr_xm_id=x.xm_id')->where($where)->count();
        $this->ajaxReturn(array( 'total' => $count,'rows' => $data));
    }

}