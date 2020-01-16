<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    /**
     * 后台主页
     */
    public function index(){
        $clear = intval(I('get.clearLoginInfo'));  //是否清除登录信息
        if($clear == 1){
            $model = M('sysuser');
            $userId = session('user_id');
            $model->where("user_id='%s'",$userId)->setField('user_sessionid','');
            session(null);
            cookie(null);
            $this->assign('openWindowMark', 'false');
        }
        $userId = session('user_id');
        if(!empty($userId)){
            $model = session('model');
            if(!empty($model)){
                $mode = unserialize($model);
            }else{
                $roleId = session('roleids');
                if(stripos($roleId, ',') !== false){
                    $roleId = explode(',',$roleId);
                    $roleId = "'".implode("','", $roleId)."'";
                }else{
                    $roleId = "'$roleId'";
                }
                //根据角色id获取用户可见菜单
                $mode = M('roleauth')->where("ra_roleid in ($roleId)")
                    ->field('mi_name,mi_url,mi_id,mi_sort')
                    ->join("left join modelinfo on roleauth.ra_miid=modelinfo.mi_id")
                    ->group('mi_name,mi_url, mi_id ,mi_sort ')
                    ->order('mi_sort asc')
                    ->select();
                $operateViews = [];
                $authModel = M('modelinfo');
                //根据用户可见菜单获取用户可操作页面
                foreach($mode as $key=>$value){
                    $id = $value['mi_id'];
                    $data = $authModel->where("mi_pid = '$id'")->field('mi_url')->select();
                    $operateViews = array_merge($data, $operateViews);
                }

                $operateViews = array_unique(removeArrKey($operateViews,'mi_url'));
                cookie('operate_view', $operateViews);  //可操作页面存入cookie

                if(!empty($mode)) {
                    session('model', serialize($mode));
                }
            }
            $this->assign('data', $mode);
            $this->assign('openWindowMark', 'false');
        }else{
            if(empty($clear)){
                $this->assign('openWindowMark', 'true');
            }else{
                $this->assign('openWindowMark', 'false');
            }
        }
        $noReopen = intval(I('get.noReopen'));
        if($noReopen!=0){
            $this->display();die;
        }else{
            $url =  U('Index/index').'?noReopen=1';
//            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
//                exit( "<script>
//                if(window.ActiveXObject || 'ActiveXObject' in window){
//                    var fulls = 'left=0,screenX=0,top=0,screenY=0,scrollbars=1';
//                    if(window.screen){
//                        var ah = screen.availHeight-30;
//                        var aw = screen.availWidth-10;
//                        fulls += ',height='+ah;
//                        fulls += ',width='+aw;
//                        fulls += ',innerWidth='+aw;
//                        fulls += ',resizable';
//                    }else{
//                        fulls += ',resizable';
//                    }
//
//                    window.open('" . $url. "','mywindow',fulls);
//                    window.close();
//                }
//                </script>"
//                );
//            }else{
                $this->display();
 //           }
        }
    }

    /**
     * 专家选择分组
     */
    public function chooseClass(){
        $classids = I('get.classids');
//        print_r($classids);die;
        $this->assign('classids',explode(',',$classids));
        $this->display();
    }

    /**
     * 保存分组
     */
    public function saveClass(){
        $classid = I('post.classid');
//        print_r($classid);die;
        if($classid){
            session('classid',$classid);
            session('classids',null);
            exit(makeStandResult(0,'保存选择'));
        }
    }

}