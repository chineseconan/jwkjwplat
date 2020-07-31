<?php
namespace Admin\Controller;
use Think\Controller;
class DataCollectController extends BaseController {

    /**
     * 字典管理
     */
    public function index(){
        $this->display();
    }

    public function create()
    {
        header("Cache-Control:max-age=0");
        $relationModel = M("xmps_xmrelation");
        $Model         = M("xmps_xm");
        $Modelsetting  = M("votesetting");
//        $markField     = $this->getAllMarkFieldFormat();
//        $markField     = implode(",",$markField);
        // 所有xmps_xmrelation联查sysuser， xr_status 为完成的数据
        $data          = $relationModel->alias('a')->field("
        xr_id,
        xr_user_id,
        xr_xm_id,
        xr_status,
        0+cast(ps_item1 as char) as ps_item1,
        0+cast(ps_item2 as char) as ps_item2,
        0+cast(ps_item3 as char) as ps_item3,
        0+cast(ps_item4 as char) as ps_item4,
        0+cast(ps_item5 as char) as ps_item5,
        0+cast(ps_item6 as char) as ps_item6,
        0+cast(ps_item7 as char) as ps_item7,
        0+cast(ps_item8 as char) as ps_item8,
        0+cast(ps_item9 as char) as ps_item9,
        0+cast(ps_item10 as char) as ps_item10,
        ps_zz,
        ps_detail,
        ps_time,
        0+cast(ps_total as char) as ps_total,
        user_class,
        user_zhiwu,
        user_zhicheng,
        user_mobile,
        user_realusername,
        user_name,
        user_orgid,
		user_password,
		user_role,
		user_enable,
		user_issystem,
		user_passworderrornum,
		user_createtime,
		user_createuser,
		user_lastmodifytime,
		user_lastmodifyuser,
        ishuibi,
        vote1,
        vote2,
        vote3,
        vote1status,
        vote2status,
        vote3status,
        0+cast(avgvalue as char) as avgvalue,
        0+cast(vote1rate as char) as vote1rate,
        0+cast(vote2rate as char) as vote2rate,
        0+cast(vote3rate as char) as vote3rate")
            ->join("left join sysuser b on a.xr_user_id=b.user_id")
            ->where("xr_status='完成'")->select();
        // 查询 xr_status 状态为完成的项目信息
        $xmdata          = $Model->field("xm_id,vote1option,vote2option,vote3option,xm_type,xm_group")->where("xm_id in(select xr_xm_id from xmps_xmrelation where xr_status='完成')")->select();
//        dump($xmdata);die;
        $votesettingdata = $Modelsetting->field("v_id,class,round,maxnum,xmtype,xmgroup,status")->select();
        $html=json_encode(array(xmdata=>$xmdata,votesetting=>$votesettingdata,data=>$data));
        $html=base64_encode($html);
        //下载xml
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Accept-Length:" . strlen($html));
        header("Content-Disposition:attachment;filename=" . time().".data");
        echo $html;
        die;
    }

    public function import()
    {
        $this->display();
    }

    public function importSubmit()
    {
        $file = uploadFile("file");
        if($file['state']!= 'success') {
            echo "文件上传失败";
            die;
        }
        $filesuffix=explode('.',$file["message"]);
        if($filesuffix[count($filesuffix)-1]!='data') {
            echo "文件格式不正确";
            die;
        }
        $Model         = M("xmps_xm");
        $relationModel = M("xmps_xmrelation");
        $sysuserModel  = M("sysuser");
        $Modelsetting  = M("votesetting");
        $html          = file_get_contents("./public/".$file["message"]);
        $data          = base64_decode($html, true);
        $data          = json_decode($data, true);
        $xmdata          = $data["xmdata"];
        $votesettingdata = $data["votesetting"];
        $data            = $data["data"];
        if($data==null){
            echo "文件读取失败";
            die;
        }
        $relationModel->startTrans();
        try {
            // xmps_xm 表修改
            foreach($xmdata as $x){
                $Model->where("xm_id='".$x["xm_id"]."'")->save($x);
            }
            // votesetting有则改，无则新增
            foreach($votesettingdata as $x) {
                $settingd = $Modelsetting->where("v_id='" . $x["v_id"] . "'")->find();
                if (empty($settingd))
                    $Modelsetting->add($x);
                else
                    $Modelsetting->where("v_id='" . $x["v_id"] . "'")->save($x);
            }
            //
            $xr_xm_id = Array();
            foreach($data as $v){
                array_push($xr_xm_id,$v['xr_xm_id']);
            }
            $xr_xm_id = array_unique($xr_xm_id);
            foreach($xr_xm_id as $delete){
                $relationModel->where("xr_xm_id='" . $delete . "' and xr_status='进行中'")->delete();
            }
            // xmps_xmrelation有则改，无则新增,sysuser修改
            foreach ($data as $d) {
                $ha = $relationModel->where("xr_id='" . $d["xr_id"] . "'")->find();
                $redata = array(
                    xr_id => $d["xr_id"],
                    xr_user_id => $d["xr_user_id"],
                    xr_xm_id => $d["xr_xm_id"],
                    xr_status => $d["xr_status"],
                    ps_item1 => $d["ps_item1"],
                    ps_item2 => $d["ps_item2"],
                    ps_item3 => $d["ps_item3"],
                    ps_item4 => $d["ps_item4"],
                    ps_item5 => $d["ps_item5"],
                    ps_item6 => $d["ps_item6"],
                    ps_item7 => $d["ps_item7"],
                    ps_item8 => $d["ps_item8"],
                    ps_item9 => $d["ps_item9"],
                    ps_item10 => $d["ps_item10"],
                    ps_zz => $d["ps_zz"],
                    ps_detail => $d["ps_detail"],
                    ps_time => $d["ps_time"],
                    ps_total => $d["ps_total"],
                    ishuibi => $d["ishuibi"],
                    avgvalue => $d["avgvalue"],
                    vote1 => $d["vote1"],
                    vote2 => $d["vote2"],
                    vote3 => $d["vote3"],
                    vote1status => $d["vote1status"],
                    vote2status => $d["vote2status"],
                    vote3status => $d["vote3status"],
                    vote1rate => $d["vote1rate"],
                    vote2rate => $d["vote2rate"],
                    vote3rate => $d["vote3rate"]
                );
                if (empty($ha)) {
                    $relationModel->add($redata);
                } else {
                    $relationModel->save($redata);
                }
				$ha = $sysuserModel->where("user_id='" . $d["xr_user_id"] . "'")->find();
                $sysuserData = array(
                    user_id => $d["xr_user_id"],
                    user_class => $d["user_class"],
                    user_zhiwu => $d["user_zhiwu"],
                    user_zhicheng => $d["user_zhicheng"],
                    user_mobile => $d["user_mobile"],
                    user_realusername => $d["user_realusername"],
                    user_name => $d["user_name"],
                    user_orgid => $d["user_orgid"],
                    user_password => $d["user_password"],
					user_role => $d["user_role"],
					user_enable => $d["user_enable"],
					user_issystem => $d["user_issystem"],
					user_passworderrornum => $d["user_passworderrornum"],
					user_createtime => $d["user_createtime"],
					user_createuser => $d["user_createuser"],
					user_lastmodifytime => $d["user_lastmodifytime"],
					user_lastmodifyuser => $d["user_lastmodifyuser"],
                );
                if (empty($ha)) {
                    $sysuserModel->add($sysuserData);
                } else {
                    $relationModel->where("user_id='" . $d["xr_user_id"] . "'")->save($sysuserData);
                }
            }
            // 写入平均分
//            dump($xr_xm_id);die;
            foreach($xr_xm_id as $xmid){

                $xmtotal = $relationModel->where("xr_xm_id='".$xmid."' and ps_total is not null and ishuibi=0")->order("ps_total")->select();
//            dump($xmtotal);die;
                $xmcount = count($xmtotal);
                if($xmcount>2) {
                    unset($xmtotal[0]);
                    unset($xmtotal[$xmcount - 1]);
                    $total = 0;
                    foreach ($xmtotal as $t) {
                        $total += floatval($t["ps_total"]);
                    }
                    $relationModel->where("xr_xm_id='".$xmid."'")->setField("avgvalue",  number_format($total / ($xmcount - 2),3, '.', ''));
                }
            }
            $relationModel->commit();
            echo "ok";
        }catch(Exception $e){
            $relationModel->rollback();
            echo "导入数据失败";
            die;
        }
    }

}