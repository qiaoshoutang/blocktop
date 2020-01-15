<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 公告控制器
 */
class NoticeController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '公告列表',
                'description' => '厦门区块链协会公告',
            ),
            'menu' => array(
                array(
                    'name' => '公告列表',
                    'url' => U('Admin/Notice/index'),
                    'icon' => 'plus',
                ),
                array(
                    'name' => '公告添加',
                    'url' => U('Admin/Notice/add'),
                    'icon' => 'plus',
                ),
            )
        );
        return $data;
    }
	
    /**
     * 公告列表
     */
    public function index(){

        $pageMaps = array();
        //筛选条件
        $where = array();
        
        $keyword = I('request.keyword','');
        if(!empty($keyword)){
            $where['_string'] = '(title like "%'.$keyword.'%")';
            $pageMaps['keyword'] = $keyword;
        }
        
        $state = I('request.state','');
        if(!empty($state)){
            $where['state'] = $state;
            $pageMaps['state'] = $state;
        }
        

        $noticeMod=D('Admin/Notice');
        
        $count = $noticeMod->countList($where);
        $limit = $this->getPageLimit($count,20);
        $list = $noticeMod->loadList($where,$limit);
        

        $this->assign('stateArr',array(1=>'显示',2=>'不显示'));
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

            $this->assign('name','添加');
            

            $this->adminDisplay('info');
        }else{

            $_POST['time'] = strtotime($_POST['time']);
            $noticeMod=D('Admin/Notice');
            $re=$noticeMod->saveData('add');
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
        
        $noticeMod=D('Admin/Notice');
        
        if(!IS_POST){
            $id=I('request.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            
            $info=$noticeMod->getInfo($id);
            $info['time'] = date('Y/m/d H:i',$info['time']);
            $this->assign('name','编辑');
            $this->assign('info',$info);

            $this->adminDisplay('info');
        }else{
            $id=I('post.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            $_POST['time'] = strtotime($_POST['time']);
            $re=$noticeMod->saveData('edit');
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
        $noticeMod = D('Admin/Notice');
        $res=$noticeMod->delData($id);
        
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
            $res = M("Notice") -> where("id in(".$ids.")") -> delete();
            if($res){
                
                $this->success('批量删除成功！');
            }else{
                $this->error('批量删除失败！');
            }
        }
    }
}

