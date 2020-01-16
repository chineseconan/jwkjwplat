<?php
namespace Admin\Controller;

use Think\Controller;

class UserAuthController extends BaseController
{

    /**
     * 角色授权
     */
    public function index()
    {
        $this->addLog('','用户访问日志','','访问用户授权管理','成功');
        $this->display();
    }

    /**
     * 根据角色获取用户
     */
    public function getDataByRole()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $roleName = trim($queryParam['real_name']);
//        if(!empty($roleName))
//            $this->addLog('','用户访问日志','','访问用户授权管理,查询角色名称关键字:'.$roleName,'成功');
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_id,role_name,role_createtime,role_createuser,role_lastmodifytime')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->limit($queryParam['offset'], $queryParam['limit'])->select();
        $count = $model->where($where)->count();
        $userAuthModel = M('userauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['role_id'];
            $users = $userAuthModel->field('user_realusername')->where("ua_roleid = '$id' and user_isdelete!=1")->join('sysuser on  userauth.ua_userid = sysuser.user_id')->select();
            $users = implode(',', removeArrKey($users, 'user_realusername'));
            $value['users'] = $users;
        }
        echo json_encode(array('total' => $count, 'rows' => $data));
    }
    /**
     * 根据角色获取用户 导出
     */
    public function exportByRole()
    {
        $queryParam = I('get.');
        $roleName = trim($queryParam['real_name']);
//        if(!empty($roleName))
//            $this->addLog('','用户访问日志','','访问用户授权管理,查询角色名称关键字:'.$roleName,'成功');
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员'],['like',"%$roleName%"]];
        $where['role_isdefault']=['neq','是'];
        $model = M('sysrole');
        $data = $model->field('role_id,role_name')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->select();
        $count = $model->where($where)->count();
        $userAuthModel = M('userauth');
        foreach ($data as &$value) {
//            $value['role_createtime'] = date('Y-m-d H:i:s', $value['role_createtime']);
//            $value['role_lastmodifytime'] = date('Y-m-d H:i:s', $value['role_lastmodifytime']);
            $id = $value['role_id'];
            unset($value['role_id']);
            $users = $userAuthModel->field('user_realusername')->where("ua_roleid = '$id'")->join('sysuser on  userauth.ua_userid = sysuser.user_id')->select();
            $users = implode(',', removeArrKey($users, 'user_realusername'));
            $value['users'] = $users;
        }
        $this->addLog('','对象修改日志','','导出用户授权列表','成功');
        $header = array('角色','用户');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }
    /**
     * 根据用户获取角色
     */
    public function getDataByUser()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $where = [];
        $userName = trim($queryParam['real_name']);
        if (!empty($userName))
        {
            //$this->addLog('','用户访问日志','','访问用户授权管理,查询用户名称关键字:'.$userName,'成功');
            $where['user_name'][] = array('like', "%$userName%");
        }
        $where['user_issystem']=['neq','是'];
        $where['user_isdelete']=['neq',1];
//        $where['user_name'][] = [['neq', 'sysadmin'], ['neq', 'sysadmin2'], ['neq', 'secadmin'], ['neq', 'secadmin2'], ['neq', 'auditadmin'], ['neq', 'auditadmin2']];
        $model = M('sysuser');
        $data = $model->field('user_id,user_realusername,user_createtime,user_createuser,user_lastmodifytime')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->limit($queryParam['offset'], $queryParam['limit'])->select();
        $count = $model->where($where)->count();
        $userAuthModel = M('userauth');
        foreach ($data as &$value) {
            $id = $value['user_id'];
            $roles = $userAuthModel->field('role_name')->where("ua_userid = '$id' and role_name!='安全管理员' and role_name!='审计管理员' and role_isdefault!='是'")->join('sysrole on  userauth.ua_roleid = sysrole.role_id')->select();
            $roles = implode(',', removeArrKey($roles, 'role_name'));
            $value['roles'] = $roles;
        }
        echo json_encode(array('total' => $count, 'rows' => $data));
    }
    /**
     * 根据用户获取角色 导出
     */
    public function exportByUser()
    {
        $queryParam = I('get.');
        $where = [];
        $userName = trim($queryParam['real_name']);
        if (!empty($userName))
        {
            //$this->addLog('','用户访问日志','','访问用户授权管理,查询用户名称关键字:'.$userName,'成功');
            $where['user_name'][] = array('like', "%$userName%");
        }
        $where['user_issystem']=['neq','是'];
//        $where['user_name'][] = [['neq', 'sysadmin'], ['neq', 'sysadmin2'], ['neq', 'secadmin'], ['neq', 'secadmin2'], ['neq', 'auditadmin'], ['neq', 'auditadmin2']];
        $where['user_isdelete']=['neq',1];
        $model = M('sysuser');
        $data = $model->field('user_id,user_realusername')->where($where)->order("$queryParam[sort] $queryParam[sortOrder]")->select();
        $count = $model->where($where)->count();
        $userAuthModel = M('userauth');
        foreach ($data as &$value) {
            $id = $value['user_id'];
            unset($value['user_id']);
            $roles = $userAuthModel->field('role_name')->where("ua_userid = '$id' and role_name!='系统管理员' and role_name!='安全管理员' and role_name!='审计管理员' and role_isdefault!='是'")->join('sysrole on  userauth.ua_roleid = sysrole.role_id')->select();
            $roles = implode(',', removeArrKey($roles, 'role_name'));
            $value['roles'] = $roles;
        }
        $this->addLog('','对象修改日志','','导出用户授权列表','成功');
        $header = array('用户','角色');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }

    public function editByRole()
    {
        $roleid = trim(I('get.roleid'));
        $userauth = M('userauth');
        if (!empty($roleid)) {
            $userids = $userauth->field('ua_userid')->where("ua_roleid='$roleid'")->select();
            $this->assign('userids', json_encode(removeArrKey($userids, 'ua_userid')));
            $this->assign('roleid', $roleid);
        }
        //$this->addLog('','用户访问日志','','访问用户授权编辑——角色','成功');
        $this->display();
    }

    public function editByUser()
    {
        $userid = trim(I('get.userid'));
        $userauth = M('userauth');

        if (!empty($userid)) {
            $roleids = $userauth->field('ua_roleid')->where("ua_userid='$userid'")->select();
            $this->assign('roleids', json_encode(removeArrKey($roleids, 'ua_roleid')));
            $this->assign('userid', $userid);
        }
        //$this->addLog('','用户访问日志','','访问用户授权编辑——用户','成功');
        $this->display();
    }

    public function addByRole()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $role_name=trim($queryParam['role_name']);
        $where['role_name'][]=[['neq','系统管理员'],['neq','安全管理员'],['neq','审计管理员']];
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
        $count = $model->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }

    public function addByUser()
    {
        $queryParam = json_decode(file_get_contents("php://input"), true);
        $user_name=trim($queryParam['user_name']);
        $where="user_issystem ='否' and user_isdelete!=1";
        if(!empty($user_name))
        {
//            $where['user_name']=['like',"%$user_name%"];
            $where=$where." and (user_name like '%$user_name%' or user_realusername like '%$user_name%')";
        }
        $model = M('sysuser');

//        $where['user_name'][] = [['neq', 'sysadmin'], ['neq', 'sysadmin2'], ['neq', 'secadmin'], ['neq', 'secadmin2'], ['neq', 'auditadmin'], ['neq', 'auditadmin2']];
        $data = $model->field('user_id,user_name,user_realusername')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
        $count = $model->count();
        echo json_encode(array('total' => $count, 'rows' => $data));
    }


    /**
     * 添加用户
     */
    public function addUserAuthByUser()
    {
        $userid = I('post.userid');
        $roleids = I('post.roleids');
        if (empty($userid)) exit(makeStandResult(-1, '参数缺少'));

        $roleid = explode(',', $roleids);
        $roleidStr = "'" . implode("','", $roleid) . "'";

        $model = M('userauth');
        $us=M('sysuser');
        $ro=M('sysrole');
        $usernames = $model->field('user_realusername')->where("ua_roleid='$roleid'")->join('sysuser on sysuser.user_id=userauth.ua_userid')->select();
        $usernames = implode(',', removeArrKey($usernames, 'user_realusername'));
        $this->addLog('userauth', '三员操作日志', 'delete', '删除用户(' . $usernames . ')的' . $ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'] . '角色权限', '成功');
        $model->where("ua_userid='$userid' and ua_roleid!='%s' and ua_roleid!='%s' and ua_roleid!='%s'",C('COMMONUSERID'),C('RISKMANAGERID'),C('SYSSETTINGMANAGERID'))->delete();
        foreach ($roleid as $key => $val) {
            $data['ua_createtime'] = time();
            $data['ua_id'] = makeGuid();
            $data['ua_createuser'] = session('user_id');
            $data['ua_userid'] = $userid;
            $data['ua_roleid'] = $val;
            $res = $model->add($data);
            if (empty($res)) {
                $this->addLog('userauth','三员操作日志','add','给角色('.$ro->field('role_name')->where("role_id='$val'")->find()['role_name'].')赋予'.$us->field('user_realusername')->where("user_id='$userid'")->find()['user_realusername'].'的用户权限','失败');
            } else {
                $this->addLog('userauth','三员操作日志','add','给角色('.$ro->field('role_name')->where("role_id='$val'")->find()['role_name'].')赋予'.$us->field('user_realusername')->where("user_id='$userid'")->find()['user_realusername'].'的用户权限','成功');
            }
        }
        exit(makeStandResult(2, '操作完成'));
    }

    public function addUserAuthByRole()
    {
        $userids = I('post.userids');
        $roleid = trim(I('post.roleid'));
        if (empty($roleid)) exit(makeStandResult(-1, '参数缺少'));
        $userid = explode(',', $userids);
        $useridStr = "'" . implode("','", $userid) . "'";
        $model = M('userauth');
        $us=M('sysuser');
        $ro=M('sysrole');
        $rolenames = $model->field('role_name')->where("ua_userid='$userid'")->join('sysrole on sysrole.role_id=userauth.ua_roleid')->select();
        $rolenames = implode(',', removeArrKey($rolenames, 'role_name'));
        $this->addLog('roleauth', '三员操作日志', 'delete', '删除角色(' . $rolenames . ')的' . $us->field('user_realusername')->where("user_id='$userid'")->find()['user_realusername'] . '用户权限', '成功');
        $model->where("ua_roleid='$roleid' and ua_roleid!='%s' and ua_roleid!='%s' and ua_roleid!='%s'",C('COMMONUSERID'),C('RISKMANAGERID'),C('SYSSETTINGMANAGERID'))->delete();

        foreach ($userid as $key => $val) {
            $data['ua_createtime'] = time();
            $data['ua_id'] = makeGuid();
            $data['ua_createuser'] = session('user_id');

            $data['ua_roleid'] = $roleid;
            $data['ua_userid'] = $val;
            $res = $model->add($data);
            if (empty($res)) {
                $this->addLog('userauth','三员操作日志','update','给角色('.$ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'].')赋予'.$us->field('user_realusername')->where("user_id='$val'")->find()['user_realusername'].'的用户权限','失败');
            } else {
                $this->addLog('userauth','三员操作日志','update','给角色('.$ro->field('role_name')->where("role_id='$roleid'")->find()['role_name'].')赋予'.$us->field('user_realusername')->where("user_id='$val'")->find()['user_realusername'].'的用户权限','成功');
            }
        }
        exit(makeStandResult(2, '操作完成'));
    }
}