<?php
namespace Article\Controller;
use Admin\Controller\AdminController;
/**
 * 文章列表
 */

class AdminContentController extends AdminController {
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
                        'url' => U('index'),
                        'icon' => 'list',
                    ),  
                    array(
                        'name' => '添加新闻',
                        'url' => U('add'),
                        'icon' => 'plus',
                    ),
                    array(
                        'name' => '快讯列表',
                        'url' => U('Messag/index'),
                        'icon' => 'list',
                    ),
                    array(
                        'name' => '添加快讯',
                        'url' => U('Messag/add'),
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
        $status = I('request.status',0,'intval');
        if(!empty($keyword)){
            $where['A.title'] = array('like','%'.$keyword.'%');
        }


        if(!empty($status)){
            $where['A.status'] = $status;
        }
        //URL参数
        $pageMaps = array();
        $pageMaps['keyword'] = $keyword;
        $pageMaps['status'] = $status;


        //查询数据
        $contentMod=D('ContentArticle');
        $count = $contentMod->countList($where);

        $limit = $this->getPageLimit($count,20);
        $list = $contentMod->loadList($where,$limit);
//         dump($contentMod->_sql());
//         dump($list);
//         exit;

        //位置导航
        $breadCrumb = array('新闻列表'=>U());
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
            $breadCrumb = array('新闻添加'=>U());
            $time=time();
            $this->assign('time',$time);
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');
            $this->assign('categoryList',M('category')->where(['show'=>1])->order('sequence asc')->select());
            $this->assign('columnList',M('column')->order('order_id desc')->select());
            $this->assign('authorList',M('users')->where(['role'=>2])->field('id,nickname')->order('id desc')->select());
            $this->adminDisplay('info');
        }else{

			$adminuid = $_SESSION['admin_user']['user_id'];
			$_POST['user_id']=$adminuid;
			$content_id=D('ContentArticle')->saveData('add');

			if($content_id){
                
                $this->success('新闻添加成功！');

            }else{
                $msg = D('ContentArticle')->getError();
                if(empty($msg)){
                    $this->error('新闻添加失败');
                }else{
                    $this->error($msg);
                }
            }
        }
    }
    

    /**
     * 修改
     */
    public function edit(){
        if(!IS_POST){
            $contentId = I('get.content_id','','intval');
            if(empty($contentId)){
                $this->error('参数不能为空！');
            }
			$adminuid = $_SESSION['admin_user']['user_id'];
		
            //获取记录
            $model = D('ContentArticle');
            $info = $model->getInfo($contentId);
		
            if(!$info){
                $this->error($model->getError());
            }
            $breadCrumb = array('新闻列表'=>U('index'),'新闻修改'=>U('',array('content_id'=>$contentId)));
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','修改');
            $this->assign('info',$info);
            $this->assign('categoryList',M('category')->where(['show'=>1])->order('sequence asc')->select());
            $this->assign('columnList',M('column')->order('order_id desc')->select());
            $this->adminDisplay('info');
        }else{
            
            if(D('ContentArticle')->saveData('edit')){
                
               
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
        if(D('ContentArticle')->delData($contentId)){
            $this->success('新闻删除成功！');
        }else{
            $this->error('新闻删除失败！');
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
                D('ContentArticle')->delData($id);
            }
            $this->success('批量操作执行完毕！');
        }


    }
    
    /**
     * 新闻审核
     */
    public function article_review(){
        $id = I('post.id','','intval');
        $operation = I('post.operation','','intval');
        
        
        $re=M('content')->where(array('content_id'=>$id))->setField('status',$operation);
        
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

