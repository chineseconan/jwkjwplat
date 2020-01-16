<?php
namespace Admin\Controller;
use Think\Controller;
class UserSafeController extends BaseController {

    /**
     * 安全管理
     */
    public function index(){
        $this->addLog('','用户访问日志','','访问安全管理','成功');
        $this->display();
    }

    /**
     * 获取用户列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);

        $where = [];
        $realName = trim($queryParam['real_name']);
        $userName=trim($queryParam['user_name']);
        $user_enable=trim($queryParam['user_enable']);
        if(!empty($realName))
        {
           // $this->addLog('','用户访问日志','','访问安全管理,查询用户名称关键字:'.$realName,'成功');
            $where['user_realusername'] = array('like' ,"%$realName%");
        }
        if(!empty($userName))
        {
            $where['user_name'] = array('like' ,"%$userName%");
        }
        if(!empty($user_enable))
        {
            $where['user_enable'] = array('eq' ,$user_enable);
        }

        $model = M('sysuser');
        $where['user_isdelete']=['neq',1];
        $data = $model->field('user_id,user_realusername,user_enable,user_name,user_issystem,user_secretlevel,user_secretlevelcode')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
        $count = $model->where($where)->count();
        foreach($data as &$value){
            $tmp = md5($value['user_id'].$value['user_secretlevel']);
            if($tmp == $value['user_secretlevelcode']){
                $value['secsign'] = 0;
            }else{
                $value['secsign'] = 1;
            }
            //md5(session('user_id').trim(I('post.user_secretlevel')));
        }
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }

    /**
     * 导出
     */
    public function export(){
        $queryParam = I('get.');

        $where = [];
        $realName = trim($queryParam['real_name']);
        $userName=trim($queryParam['user_name']);
        $user_enable=trim($queryParam['user_enable']);
        if(!empty($realName))
        {
            // $this->addLog('','用户访问日志','','访问安全管理,查询用户名称关键字:'.$realName,'成功');
            $where['user_realusername'] = array('like' ,"%$realName%");
        }
        if(!empty($userName))
        {
            $where['user_name'] = array('like' ,"%$userName%");
        }
        if(!empty($user_enable))
        {
            $where['user_enable'] = array('eq' ,$user_enable);
        }

        $model = M('sysuser');
        $where['user_isdelete']=['neq',1];
        $data = $model->field('user_realusername,user_name,user_enable')
            ->where($where)
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->select();
        $count = $model->where($where)->count();
        $this->addLog('','对象修改日志','','导出用户状态列表','成功');
        $header = array('姓名','账号','是否冻结');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }
    /**
     * 编辑模块
     */
    public function editSysUser(){
        $ids = I('post.ids');
        $status=I('post.status');
        if(empty($ids)||empty($status)) exit(makeStandResult(-1,'参数缺少'));

        $id = explode(',', $ids);
        if(in_array(session('user_id'),$id))
            exit(makeStandResult(-1,'不能操作自己的账号'));
        $idStr = "'".implode("','", $id)."'";
        $model = M('sysuser');
        if($status==='freeze')
        {
            $operation='冻结:';
            $data['user_enable']='冻结';
        }
        if($status==='unfreeze')
        {
            $operation='解冻:';
            $data['user_enable']='启用';
            $data['user_passworderrornum']=0;
        }
        if($status==='reset')
        {
            $operation='重置密码:';
            $data['user_password']= 'Guanli'.date('Y');
            $data['user_passworderrornum']=0;
            $data['user_firstuse']='是';
        }
        $data['user_lastmodifytime'] = time();
        $data['user_lastmodifyuser'] = session('user_id');
        $res = $model -> where("user_id in ($idStr)")->save($data);
        $user_names=removeArrKey($model->field('user_name')->where("user_id in ($idStr)")->select(),'user_name');
        $user_names=implode(',', $user_names);
        if(empty($res)){
            $this->addLog('sysuser','三员操作日志','update',$operation.'账号('.$user_names.')','失败');
            exit(makeStandResult(-1,'操作失败'));
        }else{
            $this->addLog('sysuser','三员操作日志','update',$operation.'账号('.$user_names.')','成功');
            exit(makeStandResult(1,'操作成功'));
        }
    }
}