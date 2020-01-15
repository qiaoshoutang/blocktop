<?php
namespace Article\Controller;
use Admin\Controller\AdminController;
/**
 * 快讯后台控制器
 */

class MessagController extends AdminController {
    /**
     * 当前模块参数
     */
    protected function _infoModule(){
        return array(
            'info'  => array(
                'name' => '内容管理',
                'description' => '管理网站所有内容',
             ),
            'menu' => array(
                    array(
                        'name' => '新闻列表',
                        'url' => U('AdminContent/index'),
                        'icon' => 'list',
                    ),  
                    array(
                        'name' => '添加新闻',
                        'url' => U('AdminContent/add'),
                        'icon' => 'plus',
                    ),
                    array(
                        'name' => '快讯列表',
                        'url' => U('index'),
                        'icon' => 'list',
                    ),
                    array(
                        'name' => '添加快讯',
                        'url' => U('add'),
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
        $state = I('request.state',0,'intval');
        if(!empty($keyword)){
            $where['title'] = array('like','%'.$keyword.'%');
        }

        if(!empty($state)){
            $where['state'] = $state;
        }
        //URL参数
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        $pageMaps['state'] = $state;


        //查询数据
        $messageMod=D('Message');
        $count = $messageMod->countList($where);

        $limit = $this->getPageLimit($count,20);
        $list = $messageMod->loadList($where,$limit);

        //位置导航
        $breadCrumb = array('快讯列表'=>U());
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
            $breadCrumb = array('快讯添加'=>U());
            $time=time();
            $this->assign('time',$time);
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->adminDisplay('info');
        }else{

			$id=D('Message')->saveData('add');

			if($id){
                
                $this->success('快讯添加成功！');

            }else{

                $this->error('快讯添加失败');

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
            $model = D('Message');
            $info = $model->getInfo($id);
		
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('新闻列表'=>U('index'),'新闻修改'=>U('',array('id'=>$id)));
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('info',$info);
            $this->adminDisplay('info');
        }else{
            
            if(D('Message')->saveData('edit')){
                
               
                $this->success('新闻修改成功！',true);
                
            }else{
                $msg = D('ContentArticle')->getError();
                if(empty($msg)){
                    $this->error('新闻修改失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }
    

    /**
     * 删除
     */
    public function del(){
		
        $contentId = I('post.data',0,'intval');
        if(empty($contentId)){
            $this->error('参数不能为空！');
        }
        if(D('Message')->delData($contentId)){
            $this->success('快讯删除成功！');
        }else{
            $this->error('快讯删除失败！');
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
                D('Message')->delData($id);
            }
            $this->success('批量操作执行完毕！');
        }


    }
    
    /**
     * 新闻审核
     */
    public function message_review(){
        $id = I('post.id','','intval');
        $operation = I('post.operation','','intval');
        
        
        $re=M('message')->where(array('id'=>$id))->setField('state',$operation);
        
        if($re){
            $data['code']=1;
            $data['msg']='操作成功';
            $data['operation']=$operation;
        }else{
            $data['code']=0;
            $data['msg']='操作失败';
        }
        $this->ajaxReturn($data);
        
    }   

}

