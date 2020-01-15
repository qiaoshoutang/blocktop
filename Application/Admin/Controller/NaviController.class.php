<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 运营
 */
class NaviController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '导航列表',
                'description' => '蚂蚁联盟导航列表',
            ),
            'menu' => array(
                array(
                    'name' => '导航列表',
                    'url' => U('Admin/Navi/index'),
                    'icon' => 'list',
                ),
                array(
                    'name' => '导航添加',
                    'url' => U('Admin/Navi/add'),
                    'icon' => 'plus',
                ),
            )
        );
        return $data;
    }
	
    /**
     * 币配置列表
     */
    public function index(){

        $pageMaps = array();
        //筛选条件
        $where = array();
        
        $keyword = I('request.keyword','');
        if(!empty($keyword)){
            $where['_string'] = '(name like "%'.$keyword.'%")';
            $pageMaps['keyword'] = $keyword;
        }
        
        $state = I('request.state','');
        if(!empty($state)){
            $where['state'] = $state;
            $pageMaps['state'] = $state;
        }
        $recom = I('request.recom','');
        if(!empty($recom)){
            $where['recom'] = $recom;
            $pageMaps['recom'] = $recom;
        }
        $class = I('request.class_id','');
        if(!empty($class)){
            $where['class_id'] = $class;
            $pageMaps['class_id'] = $class;
        }
        

        $naviMod=D('Admin/Navi');
        
        $count = $naviMod->countList($where);
        
        $limit = $this->getPageLimit($count,20);
        $list = $naviMod->loadList($where,$limit);

        $classList  =M('navi_category')->where(['state'=>1])->select();
        
        $classArr = [];
        foreach($classList as $val){
            $classArr[$val['id']] = $val['name'];
        }
        $this->assign('classArr',$classArr);
        
        $this->assign('stateArr',array(1=>'展示',2=>'隐藏',3=>'待审核',4=>'不通过'));
        $this -> assign('pageMaps',$pageMaps);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('list',$list);
        
        $this->adminDisplay('list');
    }
    
    /**
     * 编辑
     */
    public function add(){ 
        
        if(!IS_POST){

            $classList  =M('navi_category')->where(['state'=>1])->select();
 
            $classArr = [];
            foreach($classList as $val){
                $classArr[$val['id']] = $val['name'];
            }
            $this->assign('name','添加');
            $this->assign('classArr',$classArr);
            

            $this->adminDisplay('info');
        }else{
            $councilMod=D('Admin/Navi');
            $re=$councilMod->saveData('add');
            if($re){
                $this->success('添加成功',true);
            }else{
                $this->error('添加失败');
            } 
        }
    }
    /**
     * 二维码编辑
     */
    public function edit(){
        
        $naviMod=D('Admin/Navi');
        
        if(!IS_POST){
            $id=I('request.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            
            $info=$naviMod->getInfo($id);
            
            $classList  =M('navi_category')->where(['state'=>1])->select();
            
            $classArr = [];
            foreach($classList as $val){
                $classArr[$val['id']] = $val['name'];
            }
            $this->assign('classArr',$classArr);
            $this->assign('name','编辑');
            $this->assign('info',$info);

            $this->adminDisplay('info');
        }else{
            $id=I('post.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            $re=$naviMod->saveData('edit');
            if($re){
                $this->success('修改成功',true);
            }else{
                $this->error('修改失败');
            }
        } 
    }
    
    /**
     * 二维码删除
     */
    public function del(){
        
        $id=I('post.data',0,'intval');

        if(!$id){
            return '参数不能未空';
        }
        $naviMod=D('Admin/Navi');
        $res=$naviMod->delData($id);
        
        if($res){

            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
        
    }
    
    //批量操作
    public function batchAction(){
        $ids  = I('post.ids',''); //接收所选中的要操作id
        $type = I('post.type');//接收要操作的类型   如删除。。。
        
        if(empty($ids)||empty($type)){
            $this -> error('参数不能为空');
        }
        
        $ids = count($ids) > 1 ? implode(',', $ids) : $ids[0];
        
        //删除
        if($type == 1){
            $res = M("navi") -> where("id in(".$ids.")") -> delete();
            if($res){
                
                $this->success('批量删除成功！');
            }else{
                $this->error('批量删除失败！');
            }
        }
    }
}

