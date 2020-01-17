<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{

    //展示登陆页面
    public function login()
    {
        session(null);
        cookie(null);

        $this->display();
    }

    //登陆
    public function dologin()
    {
        $username = trim(I('post.username'));
        $password = trim(I('post.password'));

        if (empty($username)) exit(makeStandResult(-2, '请输入账号'));
//        if (empty($password)) exit(makeStandResult(-2, '请输入密码'));

        $model = M('sysuser');
        //获取角色信息
        $data = $model->field('user_id,user_realusername,user_password,user_name,user_secretlevel,user_orgid,user_enable,user_issystem,user_secretlevelcode,user_passworderrornum,user_frozentime,user_lastmodifytime,user_passworderrortime')
            ->where("user_isdelete !='1' and user_name='".$username."' and user_password='".$password."'")
            ->find();
        $userId = $data['user_id'];
        if (!empty($data)) {
            $isunclassified = M('sysconfig')->field('sc_itemvalue')->where("sc_itemcode='SEC_CHANGEPWD'")->find()['sc_itemvalue'];
            if ($isunclassified == 1) {
                if (!empty($data['user_lastmodifytime'])) {
                    if (time() - $data['user_lastmodifytime'] >= 7 * 24 * 3600) $isedit = true;
                }
            }
            //查询用户角色
            $roleids = M('userauth')->field('ua_roleid')->where("ua_userid='%s'", $userId)->select();
            $rolearray = removeArrKey($roleids, 'ua_roleid');
            if ($data['user_issystem'] == '否') {
                $rolearray[] = C('COMMONUSERID');
                $rolearray = array_unique($rolearray);
            }
            //密码正确
            if ($data['user_enable'] === '冻结' || empty($data['user_enable'])) {
                if ($roleids[0]['ua_roleid'] == C('SAFEMANAGERID')) { //安全管理员30分钟自动解冻账号
                    $frozenTime = intval($data['user_frozentime']);
                    $FROZEN_SAFEMANAGER_TIME = M('sysconfig')->field('sc_itemvalue')->where("sc_itemcode='SEC_LOCKTIME'")->find()['sc_itemvalue'];
                    $unfrozenTime = $frozenTime + $FROZEN_SAFEMANAGER_TIME;

                    if (time() > $unfrozenTime) {
                        $arr['user_enable'] = '启用';
                        $arr['user_frozentime'] = 0;
                        $model->where("user_id='%s'", $userId)->save($arr);
                    } else {
                        $this->addLog('', '用户登录日志', '', '账号(' . $username . ')登录,账户被冻结或不可用', '失败');
                        exit(makeStandResult(-4, '该账户将在' . date('Y-m-d H:i:s', $unfrozenTime) . '解冻'));
                    }
                } else {
                    $this->addLog('', '用户登录日志', '', '账号(' . $username . ')登录,账户被冻结或不可用', '失败');
                    exit(makeStandResult(-4, '账号被冻结或不可用请联系安全管理员'));
                }
            }
        } else {
            //密码错误
            $data = $model->field('user_id,user_realusername,user_password,user_name,user_secretlevel,user_orgid,user_enable,user_issystem,user_secretlevelcode,user_firstuse,user_passworderrornum,user_frozentime,user_lastmodifytime,user_passworderrortime')
            ->where("user_isdelete !='1' and user_name='".$username."'")
            ->find();
            if(empty($data)){
                $this->addLog('', '用户登录日志', '', '账号(' . $username . ')登录,帐号不存在', '失败');
                exit(makeStandResult(-6, '账号不存在，请检查后重新输入！'));
            }
            $this->addLog('', '用户登录日志', '', '账号(' . $username . ')登录,密码错误', '失败');
            $data['user_passworderrortime'] = time();
            if (empty($data['user_passworderrornum'])) $data['user_passworderrornum'] = 0;

            $data['user_passworderrornum']++;
            if ($data['user_passworderrornum'] >= 5) {
                if ($data['user_passworderrornum'] == 5) {
                    $data['user_enable'] = '冻结';
                    $data['user_frozentime'] = time();
                    $model->where("user_name='%s'", $username)->save($data);
                }
                exit(makeStandResult(-5, '密码错误超过5次,账号被冻结请联系安全管理员'));
            } else {
                $model->where("user_name='%s'", $username)->save($data);
                // echo $model->_sql();die;
                exit(makeStandResult(-5, '密码错误,还可以输入' . (5 - $data['user_passworderrornum']) . '次'));
            }
        }
//        $tmp = md5($userId.$data['user_secretlevel']);
//        if($tmp != $data['user_secretlevelcode']) {
//            $this->addLog('', '用户登录日志', '', '账号('.$username. ')登录,密级被篡改', '失败');
//            exit(makeStandResult(-4, '密级被篡改,不可登录请联系安全管理员'));
//        }
        $user_sessionid = $model->where("user_id='%s'", $userId)->getField('user_sessionid');
        $user_ip = $model->where("user_id='%s'", $userId)->getField('user_ip');
        $userip = ($_SERVER['HTTP_VAL']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $userip = ($userip) ? $userip : $_SERVER['REMOTE_ADDR'];

        if ($user_ip != '') {
            if ($userip != $user_ip) {
                if ($user_sessionid != "") {
                    if ($user_sessionid != session_id()) {
                        exit(makeStandResult(-5, '账号已登录'));
                        //           unlink($filename);
                    }
                }
            }
        }

        session('user_id', $userId);
        $model->where("user_id='%s'", $userId)->setField('user_sessionid', session_id());
        $model->where("user_id='%s'", $userId)->setField('user_ip', $userip);

        $now = time();
        session('operatetime', $now);
        session('user_account', $data['user_name']);
        session('realusername', $data['user_realusername']);
        session('roleids', implode(',', $rolearray));

        $num['user_passworderrornum'] = 0;
        $model->where("user_id='%s'", $userId)->save($num);
        $this->addLog('', '用户登录日志', '', '账号(' . $username . ')登录', '成功');
        exit(makeStandResult(1000, '登录成功'));
    }

    //检测登陆
    public function checkLogin()
    {
        $userId = session('user_id');
        if (!empty($userId)) {
            exit(makeStandResult(1, '已登录'));
        } else {
            exit(makeStandResult(-1, '未登录'));
        }
    }

  //检测登陆
    public function checkLoginExpire()
    {
        $logintime   = session('operatetime');
        $now       = time();
        $model=M("sysconfig");
        //获取超时登录开关和时间
        $sc_is=$model->field('sc_itemvalue')->where("sc_itemcode='SEC_LOGINTIMEOUTCHECK'")->find();
        $sc_value=$model->field('sc_itemvalue')->where("sc_itemcode='SEC_LOGINTIMEOUTTIME'")->find();
        $time=$sc_value['sc_itemvalue'];
        if($sc_is['sc_itemvalue']==1)
        {
            if($now-$logintime > $time){
                $sign = 1;
                session(null);
            }else{
                $sign = 0;
            }
        }
        exit(makeStandResult($sign,''));
    }
    //检测密码复杂度开关
    public  function  updatePassword()
    {
        $model=M('sysconfig');
        $this->assign('isunclassified', $model->field('sc_itemvalue')->where("sc_itemcode='SEC_PWDCHECK'")->find()['sc_itemvalue']);
        $this->display();
    }
    //修改密码
    public function updatePsd()
    {
        $old =  trim(I('post.old'));
        $new = trim(I('post.new1'));
        $user_name = session('user_account');
        $model = M('sysuser');
        $data = $model->where("user_name='$user_name' and user_password='$old'")->find();
        if (empty($data)) {
            exit(makeStandResult(-1, '原密码错误'));
        }
        $data['user_password'] = $new;
        $data['user_lastmodifytime'] = time();
        $data['user_lastmodifyuser'] = $data['user_id'];
        if (empty($data['user_firstuse'])||$data['user_firstuse']==='是') {
            $data['user_firstuse'] = '否';
            $this->addLog('', '用户登录日志', '', '账号('.$user_name. ')首次登录', '成功');
        }else
        {
            $this->addLog('', '用户登录日志', '', '账号('.$user_name. ')登录', '成功');
        }
        $data['user_passworderrornum'] = 0;
        $now = time();
        session('operatetime',$now);
        $res = $model->where("user_id='%s'", $data['user_id'])->save($data);
        if (empty($res)) {
            $this-> addLog('sysuser', '用户登录日志', 'update', '账号('.$user_name. ')修改密码', '失败');
            exit(makeStandResult(-1, '修改失败'));
        } else {
            $this->addLog('sysuser', '用户登录日志', 'update', '账号('.$user_name. ')修改密码', '成功');
            session('user_id', $data['user_id']);

            exit(makeStandResult(1, '修改成功'));
        }
    }

    //验证码生成
    public function verify()
    {
        $config = array(
            'fontSize' => 30,    // 验证码字体大小
            'length' => 3,     // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
     * 生成操作日志内容
     * @param $table 操作数据库
     * @param $logType 日志类型
     * @param $operationType 操作类型
     * @param $content 日志内容
     * @param $result 结果
     * @return string 日志内容
     */
    public function addLog($table, $logType, $operationType, $content, $result)
    {

        $model = M('oplog');
        $data = [
            'opl_id' => makeGuid(),
            'opl_user' => session('realusername') . '(' . session('user_account') . ')',
            'opl_ip' => get_client_ip(),
            'opl_time' => time(),
            'opl_logtype' => $logType,
            'opl_object' => $table,
            'opl_firstcontent' => $content,
            'opl_result' => $result
        ];
        $res = $model->add($data);
        return $res;
    }

    public function checkClassid(){
        // 查询是否是专家并且属于多个分组
        $userId = session('user_id');
        $roleId = session('roleids');
        $classId = session('classid');
        if(empty($classId)){
            // 检查用户是否是专家
            $roleIds = explode(',',$roleId);
            $roleIds = implode("','",$roleIds);
            $rolename = M('sysrole')->field('role_name')->where("role_id in ('".$roleIds."')")->select();
            $rolename = removeArrKey($rolename,'role_name');
            $isExpert = in_array('专家',$rolename);
            if($isExpert){
                session("isExpert",1);
                // 用户是否有多个分组
                $classids = M('sysuser')->where("user_id = '".$userId."'")->getField('user_class');
//                        print_r($classids);die;
                if((strpos($classids,',') !== false) || (strpos($classids,'，') !== false)){
                    if(strpos($classids,',') !== false){
                        $classids = explode(',',$classids);
                    }else{
                        $classids = explode('，',$classids);
                    }
                    $classids = implode(',',$classids);
                    exit(makeStandResult(1,$classids));
                }else{
                    session('classid',$classids);
                    exit(makeStandResult(0,''));
                }
            }
        }else{
            exit(makeStandResult(0,''));
        }
    }


}