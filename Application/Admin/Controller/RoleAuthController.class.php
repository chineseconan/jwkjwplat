<?php
namespace Admin\Controller;

use Think\Controller;

class RoleAuthController extends BaseController
{

    /**
     * 角色授权
     */
    public function index()
    {
        $this->addLog('', '用户访问日志', '', '访问模块授权管理', '成功');
        $this->display();
    }

    /**
     * 根据角色获取模块
     */
    public function getDataByRole()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $roleName = trim($queryParam['real_name']);
//        if(!empty($roleName))
//            $this->addLog('','用户访问日志','','访问模块授权管理,查询角色关键字:'.$roleName,'成功');
//        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_name'][]=['like',"%$roleName%"];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_id,role_name,role_createtime,role_createuser,role_lastmodifytime')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->limit($queryParam['offset'], $queryParam['limit'])->select();
        $count = $model->where($where)->count();
        $roleAuthModel = M('roleauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['role_id'];
            $models = $roleAuthModel->field('mi_name')->where("ra_roleid = '$id' and mi_isdefault!='是'")->join('modelinfo on  roleauth.ra_miid = modelinfo.mi_id')->select();
            $models = implode(',', removeArrKey($models, 'mi_name'));
            $value['models'] = $models;
        }
        echo json_encode(array('total' => $count, 'rows' => $data));
    }
    /**
     * 根据角色获取模块 导出
     */
    public function exportByRole()
    {
        $queryParam = I('get.');
        $roleName = trim($queryParam['real_name']);
//        if(!empty($roleName))
//            $this->addLog('','用户访问日志','','访问模块授权管理,查询角色关键字:'.$roleName,'成功');
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_id,role_name')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->select();
        $count = $model->where($where)->count();
        $roleAuthModel = M('roleauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['role_id'];
            unset( $value['role_id']);
            $models = $roleAuthModel->field('mi_name')->where("ra_roleid = '$id' and mi_isdefault!='是'")->join('modelinfo on  roleauth.ra_miid = modelinfo.mi_id')->select();
            $models = implode(',', removeArrKey($models, 'mi_name'));
            $value['models'] = $models;
        }
        $this->addLog('','对象修改日志','','导出模块授权列表','成功');
        $header = array('角色','模块');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }
    /**
     * 根据模块获取角色
     */
    public function getDataByModel()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $modelName = trim($queryParam['real_name']);
        if (!empty($modelName)) {
            $where['mi_name'] = array('like', "%$modelName%");
            //$this->addLog('','用户访问日志','','访问模块授权管理,查询模块名称关键字:'.$modelName,'成功');
        }
//        $where['mi_issystem'] = array('exp', 'is null');
//        $where['mi_type'][] = [['eq', '功能类型'], ['exp', 'is null'], 'or'];
//        $where['mi_isdefault']=['neq','是'];
        $model = M('modelinfo');
        $data = $model->field('mi_id,mi_name,mi_createtime,mi_createuser,mi_lastmodifytime')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->limit($queryParam['offset'], $queryParam['limit'])->select();
        $count = $model->where($where)->count();
        $roleAuthModel = M('roleauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['mi_id'];
            $roles = $roleAuthModel->field('role_name')->where("ra_miid = '$id' and role_name!='系统管理员' and role_name!='安全管理员' and role_name!='审计管理员' and role_isdefault!='是'")->join('sysrole on  roleauth.ra_roleid = sysrole.role_id')->select();
            $roles = implode(',', removeArrKey($roles, 'role_name'));
            $value['roles'] = $roles;
        }
        echo json_encode(array('total' => $count, 'rows' => $data));
    }

    /**
     * 根据模块获取角色 导出
     */
    public function exportByModel()
    {
        $queryParam = I('get.');
        $where = [];
        $modelName = trim($queryParam['real_name']);
        if (!empty($modelName)) {
            $where['mi_name'] = array('like', "%$modelName%");
            //$this->addLog('','用户访问日志','','访问模块授权管理,查询模块名称关键字:'.$modelName,'成功');
        }
        $where['mi_issystem'] = array('exp', 'is null');
        $where['mi_type'][] = [['eq', '功能类型'], ['exp', 'is null'], 'or'];
        $where['mi_isdefault']=['neq','是'];
        $model = M('modelinfo');
        $data = $model->field('mi_id,mi_name')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->limit($queryParam['offset'], $queryParam['limit'])->select();
        $count = $model->where($where)->count();
        $roleAuthModel = M('roleauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['mi_id'];
            unset($value['mi_id']);
            $roles = $roleAuthModel->field('role_name')->where("ra_miid = '$id' and role_name!='系统管理员' and role_name!='安全管理员' and role_name!='审计管理员' and role_isdefault!='是'")->join('sysrole on  roleauth.ra_roleid = sysrole.role_id')->select();
            $roles = implode(',', removeArrKey($roles, 'role_name'));
            $value['roles'] = $roles;
        }
        $this->addLog('','对象修改日志','','导出模块授权列表','成功');
        $header = array('模块','角色');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }

    public function editByRole()
    {
        $roleid = trim(I('get.roleid'));
        $roleauth = M('roleauth');
        if (!empty($roleid)) {
            $modelid = $roleauth->field('ra_miid')->where("ra_roleid='$roleid'")->select();
            $this->assign('modelids', json_encode(removeArrKey($modelid, 'ra_miid')));
            $this->assign('roleid', $roleid);
        }
        //$this->addLog('', '用户访问日志', '', '访问模块授权编辑——角色', '成功');
        $this->display();
    }

    public function editByModel()
    {
        $modelid = trim(I('get.modelid'));
        $roleauth = M('roleauth');

        if (!empty($modelid)) {
            $roleid = $roleauth->field('ra_roleid')->where("ra_miid='$modelid'")->select();
            $this->assign('roleids', json_encode(removeArrKey($roleid, 'ra_roleid')));
            $this->assign('modelid', $modelid);
        }
        //$this->addLog('', '用户访问日志', '', '访问角色授权编辑——模块', '成功');
        $this->display();
    }

    public function addByRole()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $role_name=trim($queryParam['role_name']);
//        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员']];
        $where['role_isdefault']=['neq','是'];
        if(!empty($role_name))
        {
            $where['role_name'][]=['like',"%$role_name%"];
        }
        $model = M('sysrole');
        $data = $model->field('role_id,role_name')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        $count = $model->count();
        echo json_encode($data);
    }

    public function addByModel()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where['mi_issystem'] = array('exp', 'is null');
        $where['mi_isdefault']=['neq','是'];
        $where['mi_type'][] = [['eq', '功能类型'],['eq', '页面类型'], ['exp', 'is null'], 'or'];
        $mi_name=trim($queryParam['mi_name']);
        if(!empty($mi_name))
        {
            $where['mi_name'][]=['like',"%$mi_name%"];
        }
        $model = M('modelinfo');
        $data = $model->field('mi_id,mi_name')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
//        echo $model->_sql();die;
//        $count = $model->count();
        echo json_encode($data);
    }


    /**
     * 添加模块
     */
    public function addRoleAuthByModel()
    {
        $modelid = I('post.modelid');
        $roleids = I('post.roleids');
        if (empty($modelid)) exit(makeStandResult(-1, '参数缺少'));

        $roleid = explode(',', $roleids);
        $roleidStr = "'" . implode("','", $roleid) . "'";

        $model = M('roleauth');
        $mo = M('modelinfo');
        $ro = M('sysrole');
        $rolenames = $model->field('role_name')->where("ra_miid='$modelid'")->join('sysrole on sysrole.role_id=roleauth.ra_roleid')->select();
//        dump($rolenames);die;
        $rolenames = implode(',', removeArrKey($rolenames, 'role_name'));

        $this->addLog('roleauth', '三员操作日志', 'delete', '删除角色(' . $rolenames . ')的' . $mo->field('mi_name')->where("mi_id='$modelid'")->find()['mi_name'] . '模块权限', '成功');
        $model->where("ra_miid='$modelid' and ra_miid!='%s' and ra_miid!='%s' and ra_roleid!='%s' and ra_roleid!='%s' and ra_roleid!='%s' ",C('DESK'),C('RISKMANAGE'),C('COMMONUSERID'),C('RISKMANAGERID'),C('SYSSETTINGMANAGERID'))->delete();
        foreach ($roleid as $key => $val) {
            $data['ra_createtime'] = time();
            $data['ra_id'] = makeGuid();
            $data['ra_createuser'] = session('user_id');
            $data['ra_miid'] = $modelid;
            $data['ra_roleid'] = $val;
            $res = $model->add($data);
            if (empty($res)) {
                $this->addLog('roleauth', '三员操作日志', 'add', '给角色(' . $ro->field('role_name')->where("role_id='$val'")->find()['role_name'] . ')赋予' . $mo->field('mi_name')->where("mi_id='$modelid'")->find()['mi_name'] . '的模块权限', '失败');
            } else {
                $this->addLog('roleauth', '三员操作日志', 'add', '给角色(' . $ro->field('role_name')->where("role_id='$val'")->find()['role_name'] . ')赋予' . $mo->field('mi_name')->where("mi_id='$modelid'")->find()['mi_name'] . '的模块权限', '成功');
            }
        }
        exit(makeStandResult(2, '操作完成'));
    }

    public function addRoleAuthByRole()
    {
        $modelids = I('post.modelids');
        $roleid = trim(I('post.roleid'));
        if (empty($roleid)) exit(makeStandResult(-1, '参数缺少'));
        $modelid = explode(',', $modelids);
        $modelidStr = "'" . implode("','", $modelid) . "'";
        $model = M('roleauth');
        $mo = M('modelinfo');
        $ro = M('sysrole');
        $modelnames = $model->field('mi_name')->where("ra_roleid='$roleid'")->join('modelinfo on modelinfo.mi_id=roleauth.ra_miid')->select();
        $modelnames = implode(',', removeArrKey($modelnames, 'mi_name'));
        $this->addLog('roleauth', '三员操作日志', 'delete', '删除模块(' . $modelnames . ')的' . $ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'] . '角色权限', '成功');
        $model->where("ra_roleid='$roleid' and ra_miid!='%s' and ra_miid!='%s' and ra_roleid!='%s' and ra_roleid!='%s' and ra_roleid!='%s'",C('DESK'),C('RISKMANAGE'),C('COMMONUSERID'),C('RISKMANAGERID'),C('SYSSETTINGMANAGERID'))->delete();
        foreach ($modelid as $key => $val) {
            $data['ra_createtime'] = time();
            $data['ra_id'] = makeGuid();
            $data['ra_createuser'] = session('user_id');

            $data['ra_roleid'] = $roleid;
            $data['ra_miid'] = $val;

            $res = $model->add($data);
            if (empty($res)) {
                $this->addLog('roleauth', '三员操作日志', 'add', '给角色(' . $ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'] . ')赋予' . $mo->field('mi_name')->where("mi_id='$val'")->find()['mi_name'] . '的模块权限', '失败');
            } else {
                $this->addLog('roleauth', '三员操作日志', 'add', '给角色(' . $ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'] . ')赋予' . $mo->field('mi_name')->where("mi_id='$val'")->find()['mi_name'] . '的模块权限', '成功');
            }
        }
        exit(makeStandResult(2, '操作完成'));
    }
}