<?php
namespace Article\Controller;
use Admin\Controller\AdminController;
/**
 * 视频后台控制器
 */

class VideoController extends AdminController {
    /**
     * 当前模块参数
     */
    protected function _infoModule(){
        return array(
            'info'  => array(
                'name' => '视频管理',
                'description' => '管理网站所有视频',
             ),
            'menu' => array(
                    array(
                        'name' => '视频列表',
                        'url' => U('Video/index'),
                        'icon' => 'list',
                    ),  
                    array(
                        'name' => '添加视频',
                        'url' => U('Video/add'),
                        'icon' => 'plus',
                    ),
                )
            );
    }
	/**
     * 列表
     */
    public function index(){

        //筛选条件
        $where = array();
        $keyword = I('request.keyword','');
        $state = I('request.status',0,'intval');
        if(!empty($keyword)){
            $where['title'] = array('like','%'.$keyword.'%');
        }

        if(!empty($status)){
            $where['status'] = $status;
        }
        //URL参数
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        $pageMaps['status'] = $status;


        //查询数据
        $videoMod=D('Video');
        $count = $videoMod->countList($where);

        $limit = $this->getPageLimit($count,20);
        $list = $videoMod->loadList($where,$limit);

        //位置导航
        $breadCrumb = array('视频列表'=>U());
        //模板传值
        $this->assign('breadCrumb',$breadCrumb);
        $this->assign('list',$list);
        $this->assign('page',$this->getPageShow($pageMaps));
        $this->assign('statusArr',array(1=>'草稿',2=>'通过',3=>'不通过'));
        $this->assign('pageMaps',$pageMaps);

        $this->adminDisplay();
    }

    /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('视频添加'=>U());
            $time=time();
            $this->assign('time',$time);
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('columnList',M('column')->where(['type'=>2])->order('order_id desc')->select());
            $this->assign('authorList',M('users')->where(['role'=>2])->field('id,nickname')->order('id desc')->select());
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{

			$id=D('Video')->saveData('add');

			if($id){
                
                $this->success('视频添加成功！');

            }else{

                $this->error('视频添加失败');

            }
        }
    }
    

    /**
     * 修改
     */
    public function edit(){
        if(!IS_POST){
            $id = I('get.id','','intval');
            if(empty($id)){
                $this->error('参数不能为空！');
            }
		
            //获取记录
            $model = D('Video');
            $info = $model->getInfo($id);
		
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('视频列表'=>U('index'),'视频修改'=>U('',array('id'=>$id)));
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('columnList',M('column')->where(['type'=>2])->order('order_id desc')->select());
            $this->assign('authorList',M('users')->where(['role'=>2])->field('id,nickname')->order('id desc')->select());
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            
            if(D('Video')->saveData('edit')){
                
               
                $this->success('视频修改成功！',true);
                
            }else{

                $this->error('视频修改失败');

            }
        }
    }
    

    /**
     * 删除
     */
    public function del(){
		
        $id = I('post.data',0,'intval');
        if(empty($id)){
            $this->error('参数不能为空！');
        }
        if(D('Video')->delData($id)){
            $this->success('视频删除成功！');
        }else{
            $this->error('视频删除失败！');
        }
    }

    /**
     * 批量操作
     */
    public function batchAction(){

        $type = I('post.type',0,'intval');
        $ids = I('post.ids');
        if(empty($type)){
            $this->error('请选择操作！');
        }
        if(empty($ids)){
            $this->error('请先选择操作记录！');
        }
        
        //删除
        if($type == 4){
            foreach($ids as $id){
                D('Video')->delData($id);
            }
            $this->success('批量操作执行完毕！');
        }
    } 

}

