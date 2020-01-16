<?php
namespace Admin\Controller;
use Think\Controller;
class OrgController extends BaseController {

    /**
     * 获取后台用户列表
     */

    public function getUserLists(){
        $model = M('sysuser');
        $search = I('post.');
        $search = trim($search['data']['q']);
        $data = $model->field("user_id id,(user_realusername || '-' || user_name) as text")->where("user_realusername like '%s' or user_name like '%s'", "%$search%","%$search%")->select();
        echo json_encode(array('q' => $search, 'results' => $data));
    }

    /**
     * 用户管理
     */
    public function index(){
//        $dicType = D('Dictionary')->getDicType();
//        $this->assign('dictionaryType', $dicType);
        $this->addLog('','用户访问日志','','访问部门管理','成功');
        $this->display();
    }

    /**
     * 获取部门列表
     */
    public function getData(){
        $queryParam = json_decode(file_get_contents( "php://input"), true);

        $chooseMenu = trim($queryParam['choosemenu']);

        $whereStr = ' 1=1';
        $orgName = trim($queryParam['org_name']);
        if(!empty($orgName)) $whereStr .= " and s.org_name like '%$orgName%' ";
        if(!empty($chooseMenu)) $whereStr .=" and   s.org_id in (select org_id from org start with org_id= '$chooseMenu' connect by prior org_id =org_pid)";
        $model = M('org s');
        $data = $model->field('s.org_id,s.org_name,s.org_fullname,s.org_pid,s.org_createtime,s.org_lastmodifytime,p.org_name org_pname')
            ->where($whereStr)
            ->join('org p on s.org_pid = p.org_id','left')
//            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->limit($queryParam['offset'], $queryParam['limit'])
            ->select();
        $count = $model->where($whereStr)->count();

        foreach($data as &$value){
            if($value['org_createtime']!=null)
            $value['org_createtime'] = date('Y-m-d H:i:s',$value['org_createtime']);
            if($value['org_lastmodifytime']!=null)
            $value['org_lastmodifytime'] = date('Y-m-d H:i:s',$value['org_lastmodifytime']);
        }
        echo json_encode(array( 'total' => $count,'rows' => $data));
    }

    /**
     * 导出
     */
    public function export(){
        $queryParam = I('get.');

        $where = [];
        $orgName = trim($queryParam['org_name']);
        if(!empty($orgName)) {
            //$this->addLog('','用户访问日志','','访问部门管理,查询部门名称关键字:'.$orgName,'成功');
            $where['s.org_name'] = array('like' ,"%$orgName%");
        }

        $model = M('org s');
        $data = $model->field('s.org_name,s.org_fullname,p.org_name org_pname,s.org_createtime,s.org_lastmodifytime')
            ->where($where)
            ->join('org p on s.org_pid = p.org_id','left')
            ->order("$queryParam[sort] $queryParam[sortOrder]")
            ->select();
        $count = $model->where($where)->count();

        foreach($data as &$value){
            if($value['org_createtime']!=null)
            $value['org_createtime'] = date('Y-m-d H:i:s',$value['org_createtime']);
            if($value['org_lastmodifytime']!=null)
            $value['org_lastmodifytime'] = date('Y-m-d H:i:s',$value['org_lastmodifytime']);
        }
        $this->addLog('','对象修改日志','','导出部门列表','成功');
        $header = array('名称','全称','父级部门','创建时间','上次修改时间');
        if( $count > 1000){
            csvExport($header, $data, true);
        }else{
            excelExport($header, $data, true);

        }
    }
    /**
     * 用户添加或修改
     */
    public function add(){
        $id = trim(I('get.id'));
//        $orgList = M('org')->field('org_id id,org_name, org_pid pid,org_fullname')->order('org_fullnum desc')->select();
        $orgList= M('org')->field('org_id id,org_name, org_pid pid,org_fullname')->order('org_fullnum desc')->select();
        if(!empty($id)){
            $model = M('org');
            $data = $model->field('org_id,org_name,org_fullname,org_pid')->where("org_id='%s'", $id)->find();
            $this->assign('data', $data);
//            $orgList = M('org')->field('org_id id,org_name, org_pid pid,org_fullname')->where("org_id!='%s'",$id)->order('org_fullnum desc')->select();
            $orgList= M('org')->field('org_id id,org_name, org_pid pid,org_fullname')->where("org_id!='%s'",$id)->order('org_fullnum desc')->select();
            //$this->addLog('','用户访问日志','','访问'.$data['org_name'].'部门修改','成功');
        }
//        else
//        {
//            $this->addLog('','用户访问日志','','访问部门添加','成功');
//        }
//        $orgList = D('org')->getOrgList();
//        print_r($orgList);die;
        $orgList = getLevelData($orgList, '0');
        foreach($orgList as &$value){
            $value['org_name'] = str_repeat( '&nbsp;&nbsp;&nbsp',$value['level']).$value['org_name'];
        }
        $deptId = trim(I('get.deptid'));
        $this->assign('deptId', $deptId);
        $this->assign('orglist', $orgList);
        $this->display();
    }

    /**
     * 用户添加修改
     */
    public function addOrg(){
        $id = trim(I('post.id'));
        $data['org_name'] = trim(I('post.org_name'));
        $data['org_fullname'] = trim(I('post.org_fullname'));
        $data['org_pid'] = trim(I('post.org_pid'));
        if(empty($data['org_pid'])) $data['org_pid'] = '0';
        $model = M('org');
        //为空则添加
        if(empty($id)){
            $tem= $model->where("org_name='%s'",$data['org_name'])->find();
            if(!empty($tem))
            {
                $this->addLog('org','三员操作日志','add','新增部门'.$data['org_name'],'失败');
                exit(makeStandResult(-1,'部门已存在'));
            }
            $data['org_createtime'] = time();
            $data['org_id'] = makeGuid();
            $data['org_createuser'] = session('user_id');
            $res = $model->add($data);
            if(empty($res)){
                $this->addLog('org','三员操作日志','add','添加部门'.$data['org_name'],'失败');
                exit(makeStandResult(-1,'添加失败'));
            }else{
                $this->addLog('org','三员操作日志','add','添加部门'.$data['org_name'],'成功');
                exit(makeStandResult(1,'添加成功'));
            }
        }else{
            $data['org_lastmodifytime'] = time();
            $data['org_lastmodifyuser'] = session('user_id');
            $tem= $model->where("org_name='%s'",$data['org_name'])->find();
            if($tem['org_id']!=$id&&!empty($tem))
            {
                $this->addLog('org','三员操作日志','update','修改部门'.$data['org_name'],'失败');
                exit(makeStandResult(-1,'部门已存在'));
            }
            $res = $model->where("org_id='%s'", $id)->save($data);
            if(empty($res)){
                $this->addLog('org','三员操作日志','update','修改部门'.$data['org_name'],'失败');
                exit(makeStandResult(-1,'修改失败'));
            }else{
                $this->addLog('org','三员操作日志','update','修改部门'.$data['org_name'],'成功');
                exit(makeStandResult(1,'修改成功'));
            }
        }
    }

    /**
     * 删除模块
     */
    public function delOrg(){
        $ids = I('post.ids');
        if(empty($ids)) exit(makeStandResult(-1,'参数缺少'));
        $id = explode(',', $ids);
        $idStr = "'".implode("','", $id)."'";
        $model = M('org');

        //不能含有子部门
        $children = $model->where("org_pid in ($idStr)")->find();
        if(!empty($children))  exit(makeStandResult(-1,'要删除的部门下还有子部门，不可删除'));

        //部门下不能有用户
        $users = M('sysuser')->where("user_orgid in ($idStr)")->find();
        if(!empty($users))  exit(makeStandResult(-1,'要删除的部门下还有用户，不可删除'));

        foreach($id as $key=>$val) {
            $tem= $model->where("org_id='%s' or org_pid='%s'",$val,$val)->find();
            if(!empty($tem)) {
                $this->addLog('org','三员操作日志','delete','删除部门'.$tem['org_name'],'成功');
            }
        }
        $res = $model -> where("org_id in ($idStr)")->delete();
        $model->where("org_pid in ($idStr)")->delete();
        if(empty($res)){
            exit(makeStandResult(-1,'删除失败'));
        }else{
            exit(makeStandResult(1,'删除成功'));
        }
    }


    /**
     * 获取部门树
     */
    public function getOrgTree(){
        $orgName = trim(I('org_name'));
        $model = D('org');
        $data = $model->getOrgList(false);

        //如果有搜索，查出结果后反向递归
        if(!empty($orgName)){
            $orgModel = M('org');
            $result = [];
            foreach ($data as $key => $value) {
                $sql = "select org_id id,org_name, org_pid pid,org_fullname from org start with (org_name like '%$orgName%'  ) connect by prior org_pid=org_id  order by org_createtime asc";
                $res = array_reverse($orgModel->query($sql));
                $result = array_merge($res, $result);
            }
            $data = uniqueArr($result, true);
        }
        $initData = [];
        if(empty($initData)) $initData = [];
        foreach($data as &$value){
            $value['name'] = $value['org_name'];
            $value['open'] = 'true';
            $value['icon'] = __ROOT__.'/Public/vendor/zTree_v3/css/zTreeStyle/img/diy/10.png';
            $initData[] = $value;
        }
        echo json_encode($data);
    }
}