<?php
namespace Admin\Controller;

use Think\Controller;

class BaseController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $userId = session('user_id');
        if (empty($userId)) {
            if (IS_GET) {
                echo "<script>top.location.href='".U('Admin/Index/index')."';</script>";die;
            } else {
                if (IS_POST || IS_AJAX) {
                    exit(makeStandResult(-1001, '请先登录'));
                }
            }
        }
        $now = time();
        session('operatetime',$now);
//        $nowtime=time();
//        $logintime=session('logintime');
//        if($nowtime-$logintime>1)
//        {
//            session('user_id',null);
//            session('logintime',null);
//            echo "<script>parent.location.reload();</script>";
//            return false;
//        }else
//        {
//            session('logintime',time());
//        }
    }

    /**
     * 如果需要导出数据需要只需访问数据获取的方法加上WithExport，导出逻辑需要自己在方法中增加
     * @param $method
     */
    public function _empty($method)
    {
        if (file_exists_case($this->view->parseTemplate())) {
            $this->display();
        } else {
            $prefix = substr($method, -10, 10);
            if (strtolower($prefix) == 'withexport') {
                $checkMethod = substr($method, 0, -10);
                if (method_exists($this, $checkMethod)) {
                    $this->$checkMethod(true);
                } else {
                    exit("<script>alert('页面未找到');history.go(-1);</script>");
                }
            } else {
                exit("<script>alert('页面未找到');history.go(-1);</script>");
            }
        }
    }

    function addLog($table, $logType, $operationType, $content, $result)
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
        $data['opl_logcode']=md5($data['opl_id'].$data['opl_user'].$data['opl_ip'].$data['opl_time'].$data['opl_logtype'].$data['opl_object'].$data['opl_firstcontent'].$data['opl_result']);
        $res = $model->add($data);
        return $res;
    }
}