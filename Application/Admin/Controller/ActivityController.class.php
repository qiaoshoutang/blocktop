<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 运营
 */
class ActivityController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '活动管理',
                'description' => '活动相关数据',
            ),
            'menu' => array(
                array(
                    'name' => '活动列表',
                    'url' => U('Admin/Activity/activityList'),
                    'icon' => 'list',
                ),
                array(
                    'name' => '活动添加',
                    'url' => U('Admin/Activity/activityAdd'),
                    'icon' => 'plus',
                ),

            )
        );
        return $data;
    }
    


    /*****************************************************************************************/
    
    /**
     * 活动列表
     */
    public function activityList(){

        $pageMaps = array();
        //筛选条件
        $where = array();
        
        $state=I('request.state',0,'intval');
        if($state){
            $pageMaps['state'] = $state;
            $where['state']  = $state;
        }
        
        $keyword = I('request.keyword','','trim');
        if(!empty($keyword)){
            $where['_string'] = '(name like "%'.$keyword.'%") OR (id = '.$keyword.')';
            $pageMaps['keyword'] = $keyword;
        }

        $activityMod=D('Admin/Activity');
        
        $count = $activityMod->countList($where);
        $limit = $this->getPageLimit($count,20);
        $list = $activityMod->loadList($where,$limit,'id desc');

        $this->assign('stateArr',array(1=>'报名中',2=>'已结束'));
        $this -> assign('pageMaps',$pageMaps);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('list',$list);
        
        $this->adminDisplay('activityList');
    }
    
    /**
     * 新增
     */
    public function activityAdd(){ 
        
        if(!IS_POST){

            $this->assign('name','添加');

            $this->adminDisplay('activityInfo');
        }else{
            $activityMod=D('Admin/Activity');
            $re=$activityMod->saveData('add');
            if($re){
                $this->success('添加成功',true);
            }else{
                $this->error('添加失败');
            } 
        }
    }
    /**
     * 编辑
     */
    public function activityEdit(){
        
        $activityMod=D('Admin/Activity');
        
        if(!IS_POST){
            $id=I('request.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            
            $activityInfo=$activityMod->getInfoById($id);
            
            $this->assign('name','编辑');
            $this->assign('info',$activityInfo);

            $this->adminDisplay('activityInfo');
        }else{
            $id=I('post.id','','intval');
            if(!$id){
                return '参数不能未空';
            }
            $re=$activityMod->saveData('edit');
            if($re){
                $this->success('修改成功',true);
            }else{
                $this->error('修改失败');
            }
        } 
    }
    
    /**
     * 删除
     */
    public function activityDel(){
        
        $id=I('post.data',0,'intval');

        if(!$id){
            return '参数不能未空';
        }
        $activityMod=D('Admin/Activity');
        $res=$activityMod->delData($id);
        
        if($res){

            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
        
    }
    

}

