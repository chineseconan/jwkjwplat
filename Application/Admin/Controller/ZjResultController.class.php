<?php
namespace Admin\Controller;
use Think\Controller;
class ZjResultController extends BaseController
{

    /**
     * 字典管理
     */
    public function index()
    {
        $this->addLog('', '用户访问日志', '', '打分结果统计', '成功');
        $this->display();
    }

    /**
     * 获取字典列表
     */
    public function getData()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $model      = M('xmps_xm');
        $where      = [];
        $user_id    = session("user_id");
        $data       = $model->field('xm_id,
        xm_code,
        xm_tmfs,
        xm_name,
        xm_company,
        xm_createuser,
        xm_class,
        xm_type,
        xm_ordernum,
        0+cast(ps_total as char) as ps_total,
        ishuibi,
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
            //本人打分
            ->join("inner join (select xr_xm_id,ps_total,ishuibi from xmps_xmrelation where xr_user_id = '$user_id') c on xmps_xm.xm_id=c.xr_xm_id")
            ->where($where)
            ->order($queryParam['sort'] . " " . $queryParam['sortOrder'])
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
        $count = $model->where($where)->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }
}