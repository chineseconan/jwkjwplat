<?php
namespace Admin\Controller;
use Think\Controller;
class RoleInfoController extends BaseController {

    /**
     * 字典管理
     */
    public function index(){
//        $dicType = D('Dictionary')->getDicType();
//        $this->assign('dictionaryType', $dicType);
        $this->addLog('','用户访问日志','','访问角色管理','成功');
        $this->display();
    }

    /**
     * 获取字典列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);

        $roleName = trim($queryParam['role_name']);
        if(!empty($roleName))
        {
           // $this->addLog('','用户访问日志','','访问角色管理,查询角色名称关键字:'.$roleName,'成功');
        }
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_id,role_name,role_createtime,role_createuser,role_lastmodifytime')
                    ->where($where)
                    ->order("$queryParam[sort] $queryParam[sortOrder]")
                    ->limit($queryParam['offset'], $queryParam['limit'])
                    ->select();
        $count = $model->where($where)->count();

        foreach($data as &$value){
            if($value['role_createtime']!=null)
            $value['role_createtime'] = date('Y-m-d H:i:s',$value['role_createtime']);
            if($value['role_lastmodifytime']!=null)
            $value['role_lastmodifytime'] = date('Y-m-d H:i:s',$value['role_lastmodifytime']);
        }
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }
    /**
     *导出
     */
    public function export(){
        $queryParam = I('get.');

        $roleName = trim($queryParam['role_name']);
        if(!empty($roleName))
        {
            // $this->addLog('','用户访问日志','','访问角色管理,查询角色名称关键字:'.$roleName,'成功');
        }
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_name,role_createtime,role_lastmodifytime')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->select();
        $count = $model->where($where)->count();

        foreach($data as &$value){
            if($value['role_createtime']!=null)
            $value['role_createtime'] = date('Y-m-d H:i:s',$value['role_createtime']);
            if($value['role_lastmodifytime']!=null)
            $value['role_lastmodifytime'] = date('Y-m-d H:i:s',$value['role_lastmodifytime']);
        }
        $this->addLog('','对象修改日志','','导出角色列表','成功');
        $header = array('角色名称','创建时间','上次修改时间');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);
        }
    }
    /**
     * 模块添加或修改
     */
    public function add(){
        $id = trim(I('get.id'));
        if(!empty($id)){
            $model = M('sysrole');
            $data = $model->field('role_id,role_name')->where("role_id='%s' and role_isdefault!='是'", $id)->find();
            //$this->addLog('','用户访问日志','','访问'.$data['role_name'].'角色修改','成功');
            $this->assign('data', $data);
        }
        //$this->addLog('','用户访问日志','','访问角色添加','成功');
        $this->display();
    }

    /**
     * 模块添加修改
     */
    public function addSysRole(){
        $id = trim(I('post.id'));
        $data['role_name'] = trim(I('post.role_name'));
        if(empty($data['role_name']))  exit(makeStandResult(-1,'请输入模块名称'));
        $model = M('sysrole');
        //为空则添加
        if(empty($id)){
            $isexist= $model->where("role_name='%s'",$data['role_name'])->find();
            if(!empty($isexist))
            {
                $this->addLog('sysrole','三员操作日志','add','新增角色'.$data['role_name'],'失败');
                exit(makeStandResult(-1,'角色已存在'));
            }
            $data['role_createtime'] = time();
            $data['role_id'] = makeGuid();
            $data['role_createuser'] = session('user_id');
            $res = $model->add($data);
            if(empty($res)){
                $this->addLog('sysrole','三员操作日志','add','新增角色'.$data['role_name'],'失败');
                exit(makeStandResult(-1,'添加失败'));
            }else{
                $this->addLog('sysrole','三员操作日志','add','新增角色'.$data['role_name'],'成功');
                exit(makeStandResult(1,'添加成功'));
            }
        }else{
            $data['role_lastmodifytime'] = time();
            $data['role_lastmodifyuser'] = session('user_id');
            $tem= $model->where("role_name='%s'",$data['role_name'])->find();
            if($tem['role_id']!=$id&&!empty($tem))
            {
                $this->addLog('sysrole','三员操作日志','update','修改角色'.$data['role_name'],'失败');
                exit(makeStandResult(-1,'角色已存在'));
            }
            $res = $model->where("role_id='%s'", $id)->save($data);
            if(empty($res)){
                $this->addLog('sysrole','三员操作日志','update','修改角色'.$data['role_name'],'失败');
                exit(makeStandResult(-1,'修改失败'));
            }else{
                $this->addLog('sysrole','三员操作日志','update','修改角色'.$data['role_name'],'成功');
                exit(makeStandResult(1,'修改成功'));
            }
        }
    }

    /**
     * 删除角色
     */
    public function delSysRole(){
        $ids = I('post.ids');
        if(empty($ids)) exit(makeStandResult(-1,'参数缺少'));
        $id = explode(',', $ids);
        $idStr = "'".implode("','", $id)."'";
        $model = M('sysrole');
        $roleauth=M('roleauth');
        $userauth=M('userauth');
        foreach($id as $key=>$val)
        {
            $tem= $model->where("role_id='%s'",$val)->find();
            if(!empty($tem))
            {
                $this->addLog('sysrole','三员操作日志','delete','删除角色'.$tem['role_name'],'成功');
            }
        }
        $roleauth->where("ra_roleid in ($idStr)")->delete();
        $userauth->where("ua_roleid in ($idStr)")->delete();
        $res = $model -> where("role_id in ($idStr)")->delete();
        if(empty($res)){
            exit(makeStandResult(-1,'删除失败'));
        }else{
            exit(makeStandResult(1,'删除成功'));
        }
    }

}