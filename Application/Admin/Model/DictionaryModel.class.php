<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/05
 * Time: 10:15
 */
namespace Admin\Model;
use Think\Model;
class DictionaryModel extends Model{
    Protected $autoCheckFields = false;


    /**
     * 获取字典类型
     */
    public function  getDicType(){
        $dicType = M('dic_type')->field('dic_type_id, type_name')->select();
        return $dicType;
    }

    /**
     * 根据字典类型获取该类型的字典值
     * @param string $dicType
     * @return array
     */
    public function getDicValueByName($dicType = ''){
        $model = M('dic');
        $data = $model->field('dic_value as val,dic_name')
            ->where("type_name = '$dicType'" )
            ->join('dic_type on dic.dic_type=dic_type.dic_type_id')
            ->order('dic_order asc')
            ->select();
        return $data;
    }

    /**
     * 根据传入的密级获取小于等于该级别的密级数据
     * @param string $level
     * @param bool $isSql 如果是要拼接sql查询用户可以查看的密级，传入true
     * @param bool $litterOrBiggger 小于还是大于，默认小于
     * @return array
     */
    public function getSecretLevelDataByLevel($level, $isSql = false, $litterOrBiggger = false){
        $data = [];
        if(empty($level)) return $data;
        $secretLevelData = $this->getDicValueByName('密级');

        if($litterOrBiggger == true){
            $secretLevelData = array_reverse($secretLevelData);
        }
        foreach($secretLevelData as $key=>$value){
            if($level != $value['dic_name']){
                $data[] = $value;
            }else{
                $data[] = $value;
                break;
            }
        }
        if($isSql == false){
            return $data;
        }else{
            $data = removeArrKey($data, 'dic_name');
            return  "'".implode("','", $data)."'";
        }
    }

    /**
     * 传入一个密级判断当前登录用户是否有权查看
     * @param $level
     * @return bool
     */
    public function judgeSecretLevel($level){
        $userSecretLevel = D('User')->getUserSecretLevel(session('user_id'));
        $secretData = $this ->getSecretLevelDataByLevel($level, false, true); //获取大于等于该风险的密级数据

        $secretData = removeArrKey($secretData, 'dic_name');
        if(in_array($userSecretLevel, $secretData)){
            return true;
        }else{
            return false;
        }
    }
}
