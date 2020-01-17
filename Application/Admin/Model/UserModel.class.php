<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/05
 * Time: 10:15
 */
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{
    Protected $autoCheckFields = false;

    /**
     * 获取用户信息
     * @param array $ids
     * @return mixed
     */
    public function getUsers($ids = array()){
        $model = M('sysuser');

        $where = '';
        if(!empty($ids)) {
            $idStr = "'".implode("','",$ids)."'";
            $where .= " and  user_id in ($idStr)";
        }
        $data = $model->field("user_id id,(user_realusername || '-' || user_name) as text")
                    ->where("user_issystem != '是'and user_isdelete = '0'  and user_enable ='启用'  $where")
                    ->select();
        return $data;
    }


    /**
     * 判断是否是风险管理员
     * @param $userId
     * @return bool
     */
   public function judgeRiskManager($userId){
       $model = M('project');
       $sql = "select proj_id
  from project
 where proj_id in
       (select prm_projid from projriskmanager where prm_riskmanager = '$userId')
       or (proj_specialduty = '$userId' or proj_duty = '$userId' or proj_zhushen = '$userId' or proj_leader = '$userId' or  proj_taskduty ='$userId' ) ";
       $data = $model->query($sql);
       if(!empty($data)){
           return true;
       }else{
           return false;
       }
   }

    /**
     * 获取用户的密级
     * @param $userId
     * @return string
     */
    public function getUserSecretLevel($userId){
        $level = cookie('user_secretlevel');
        if(!empty($level)) return $level;

        $model = M('sysuser');
        $data = $model->where("user_id = '$userId'")->field('user_secretlevel')->find();
        cookie('user_secretlevel', $data['user_secretlevel']);
        return $data['user_secretlevel'];
    }

    /**
     * 获取当前登录用户可以查看的密级并拼接成sql
     * @return string
     */
    public function getRiskSecretLevelStr(){
        $userId = session('user_id');
        $userSecretLevel = $this->getUserSecretLevel($userId);
        $secretLevelStr = D('Dictionary')->getSecretLevelDataByLevel($userSecretLevel, true);
        return " and risk_secretlevel in ($secretLevelStr)";
    }


}