<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 专栏控制器
 */
class ColumnController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '专栏列表',
                'description' => '区块链头条官网专栏列表',
            ),
            'menu' => array(
                array(
                    'name' => '专栏列表',
                    'url' => U('Admin/Column/index'),
                    'icon' => 'list',
                ),
                array(
                    'name' => '专栏添加',
                    'url' => U('Admin/Column/add'),
                    'icon' => 'plus',
                ),
            )
        );
        return $data;
    }
	
    /*
     * 专栏列表
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
        

        $columnMod=D('Admin/Column');
        
        $count = $columnMod->countList($where);
        
        $limit = $this->getPageLimit($count,20);
        $list  = $columnMod->loadList($where,$limit);

        
        $this->assign('stateArr',array(1=>'展示',2=>'隐藏'));
        $this -> assign('pageMaps',$pageMaps);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('list',$list);
        
        $this->adminDisplay('list');
    }
    
    /**
     * 新增专栏
     */
    public function add(){ 
        
        if(!IS_POST){

            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{
            $columnMod=D('Admin/Column');
            $re=$columnMod->saveData('add');
            if($re){
                $this->success('添加成功',true);
            }else{
                $this->error('添加失败');
            } 
        }
    }
    /**
     * 修改专栏
     */
    public function edit(){
        
        $columnMod=D('Admin/Column');
        
        if(!IS_POST){
            $id=I('request.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            
            $info=$columnMod->getInfo($id);
            
            $this->assign('name','编辑');
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            $id=I('post.id','','intval');
            if(!$id){
                return '参数不能为空';
            }
            $re=$columnMod->saveData('edit');
            if($re){
                $this->success('修改成功',true);
            }else{
                $this->error('修改失败');
            }
        } 
    }
    
    /**
     * 删除专栏
     */
    public function del(){
        
        $id=I('post.data',0,'intval');

        if(!$id){
            return '参数不能未空';
        }
        $columnMod=D('Admin/Column');
        $res=$columnMod->delData($id);
        
        if($res){

            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        } 
    } 
}

