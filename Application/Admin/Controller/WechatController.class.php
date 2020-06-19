<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 公众号采集管理
 */
class WechatController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '公众号管理',
                'description' => '管理网站公众号采集',
            ),
            'menu' => array(
                array(
                	'name' => '公众号列表',
                    'url' => U('Admin/Wechat/index'),
                    'icon' => 'list',
                ),
				array(
					'name' => '添加公众号',
                    'url' => U('Admin/Wechat/add'),
                    'icon' => 'plus',
                ),
                //$contentMenu
            )
        );
        return $data;
    }
    
   
    
    /**
     * 用户列表
     */
    public function index(){
		
		$keyword = I('request.keyword','');

        $breadCrumb = array('公众号列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
 
        
		$pageMaps = array();

        $pageMaps['keyword'] = $keyword;

		$where = array();

		if(!empty($keyword)){
			$where['_string'] = '(name like "%'.$keyword.'%")';
        }
       
 
        $order = "id asc";
        $wechatMod = D('Wechat');
        $count = $wechatMod->countList($where);
        $limit = $this->getPageLimit($count,20);
		
        $list =$wechatMod->loadList($where,$limit,$order);

	    $this->assign('pageMaps',$pageMaps);

		$this->assign('page',$this->getPageShow($pageMaps));
		$this->assign('list',$list);
        $this->adminDisplay();
    }
	 /**
     * 增加
     */
    public function add(){
        if(!IS_POST){
            $breadCrumb = array('公众号列表'=>U('index'),'公众号添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');

            $this->adminDisplay('info');
        }else{			
            if(D('Wechat')->saveData('add')){
                $this->success('公众号添加成功！');
            }else{
                $msg = D('Wechat')->getError();
                if(empty($msg)){
                    $this->error('公众号添加失败');
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
    		$breadCrumb = array('公众号列表' => U('index'),'修改公众号'=>U());
    		$this->assign('breadCrumb', $breadCrumb);
    		$id = I('get.id','','intval');
    		if(empty($id)) $this->error('参数不能为空！');
    	    //获取记录
    	    $info = D( 'Wechat' )->where( ['id'=>$id])->find();

    		if(!$info) $this->error('无数据！');
    	    
    		$this->assign('info',$info);
    		$this->assign('name','修改');
    		$this->adminDisplay('info');
    	}else{

    		if(D('Wechat')->saveData('edit')){
    			$this->success('修改成功！');
    		}else{
    			$msg = D('Wechat')->getError();
    			if(empty($msg)){
    				$this->error('修改失败');
    			}else{
    				$this->error($msg);
    			}
    		}
    	}
    }
	
	//删除
	public function del(){
		$uid = I('post.data',0,'intval');
        if(empty($uid)){
            $this->error('参数不能为空！');
        }
       	$res = M("Wechat") -> where("id = ".$uid) -> delete();
		if($res){
			$this->success('用户删除成功！');
		}else{
			$this->error('用户删除失败！');
		}
	}

	
	
}

