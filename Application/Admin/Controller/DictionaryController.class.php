<?php
namespace Admin\Controller;
use Think\Controller;
class DictionaryController extends BaseController {

    /**
     * 字典管理
     */
    public function index(){
        $this->addLog('','用户访问日志','','访问字典管理','成功');
        $dicType = D('Dictionary')->getDicType();
        $this->assign('dictionaryType', $dicType);
        $this->display();
    }

    /**
     * 获取字典列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);

        $where = [];
        $dicName = trim($queryParam['dic_name']);
        if(!empty($dicName)) $where['dic_name'] = array('like' ,"%$dicName%");
        $dicTye = trim($queryParam['search_type']);
        if(!empty($dicTye)) $where['dic_type'] = array('eq' ,"$dicTye");

        $model = M('dic');
        $data = $model->field('dic_id,dic_value,dic_name,dic_createtime,dic_createuser,type_name as dic_type')
                    ->join('dic_type on dic.dic_type=dic_type.dic_type_id')
                    ->where($where)
                    ->order(' dic_createtime asc')
                    ->limit($queryParam['offset'], $queryParam['limit'])
                    ->select();
        $count = $model->where($where)->count();

        foreach($data as &$value){
            $value['dic_createtime'] = date('Y-m-d H:i:s',$value['dic_createtime']);
        }
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }

    /**
     * 字典添加或修改
     */
    public function add(){
        $id = trim(I('get.id'));
        $choseDicType = trim(I('get.dic_type'));
        if(!empty($id)){
            $model = M('dic');
            $data = $model->field('dic_id,dic_value,dic_name,dic_type')->where("dic_id='%s'", $id)->find();
            $this->assign('data', $data);
        }
        $this->addLog('','用户访问日志','','字典添加\修改页面','成功');
        $dicType = D('Dictionary')->getDicType();
        $this->assign('dictionaryType', $dicType);
        $this->assign('choseDicType', $choseDicType);
        $this->display();
    }

    /**
     * 字典添加修改
     */
    public function addDictionary(){
        $id = trim(I('post.id'));
        $data['dic_name'] = trim(I('post.dic_name'));
        $data['dic_type'] = trim(I('post.dic_type'));
        $data['dic_value'] = trim(I('post.dic_value'));
        if(empty($data['dic_name']))  exit(makeStandResult(-1,'请输入字典名称'));
        if(empty($data['dic_type']))  exit(makeStandResult(-1,'请输入字典类型'));
        $model = M('dic');
        //为空则添加
        if(empty($id)){
            $data['dic_createtime'] = time();
            $data['dic_id'] = makeGuid();
            $data['dic_createuser'] = session('user_id');
            $res = $model->add($data);
            if(empty($res)){
                $this->addLog('dic', '对象修改日志', 'add', '添加字典=>'.$data['dic_name']. '失败', '失败');
                exit(makeStandResult(-1,'添加失败'));
            }else{
                $this->addLog('dic', '对象修改日志', 'add', '添加字典=>'.$data['dic_name']. '成功','成功');
                exit(makeStandResult(1,'添加成功'));
            }
        }else{
            $data['dic_lastmodifytime'] = time();
            $data['dic_lastmodifyuser'] = session('user_id');
            $res = $model->where("dic_id='%s'", $id)->save($data);
            if(empty($res)){
                $this->addLog('dic', '对象修改日志', 'update', '修改字典=>'.$data['dic_name']. '失败', '失败');
                exit(makeStandResult(-1,'修改失败'));
            }else{
                $this->addLog('dic', '对象修改日志', 'update', '修改字典=>'.$data['dic_name']. '成功','成功');
                exit(makeStandResult(1,'修改成功'));
            }
        }
    }

    /**
     * 删除字典
     */
    public function delDictionary(){
        $ids = I('post.ids');
        if(empty($ids)) exit(makeStandResult(-1,'参数缺少'));
        $id = explode(',', $ids);
        $idStr = "'".implode("','", $id)."'";
        $model = M('dic');
        $names = $model ->field('dic_name')-> where("dic_id in ($idStr)")->select();

        $names = implode(',',removeArrKey($names, 'dic_name'));
        $res = $model -> where("dic_id in ($idStr)")->delete();
        if(empty($res)){
            $this->addLog('dic', '对象修改日志', 'delete', '删除字典=>('.$names. ')成功', '成功');
            exit(makeStandResult(-1,'删除失败'));
        }else{
            $this->addLog('dic', '对象修改日志', 'delete', '删除字典=>('.$names. ')失败','失败');
            exit(makeStandResult(1,'删除成功'));
        }
    }

    public function import(){
        if(IS_POST){
            $Model = M("dic_type");
            $file = uploadFile("file");
            $path = "./Public/".$file['message'];
            $import = excelImport($path);
            $str = "";
            $dic = M('dic');
            $column = Array("字典类型","字典项名称",'字典值');
            $len = count($column);
            for($i=0;$i<$len;$i++){
                if($column[$i]!=$import['column'][$i]){
                    die("请按照模板填写导入数据，保持列头一致");
                }
            }
            foreach($import['data'] as $val){
                if($val['B']!=NULL &&  $val['C']!=NULL && $val['A']!=NULL ){
                    $re = $Model->where("type_name='".$val['A']."'")->find();
                    if(!empty($re)){
                        $flag = $dic->where("(dic_name='".$val['B']."' or dic_value='".$val['C']."') and dic_type='".$re['dic_type_id']."'")->find();
                        if(empty($flag)){
                            $data['dic_createtime'] = time();
                            $data['dic_id'] = makeGuid();
                            $data['dic_createuser'] = session('user_id');
                            $data['dic_name'] = $val['B'];
                            $data['dic_value'] = $val['C'];
                            $data['dic_type'] = $re['dic_type_id'];
                            $dic->add($data);
                        }
                    }else{
                        $str .= $val['A'].",";
                    }
                }
            }
            $str = rtrim($str,",");
            if($str==""){
                echo "ok";
            }else{
                echo "字典项：".$str."不存在";
            }
        }else{
            $this->display();
        }
    }


}