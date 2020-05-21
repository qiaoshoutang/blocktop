<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
/**
 * 栏目管理
 */
class UserController extends AdminController
{
	
	
    /**
     * 当前模块参数
     */
    public function _infoModule()
    {
        $data = array(
        	'info' => array(
        		'name' => '用户管理',
                'description' => '管理网站所有用户信息',
            ),
            'menu' => array(
                array(
                	'name' => '用户列表',
                    'url' => U('Admin/User/index'),
                    'icon' => 'list',
                ),
				array(
					'name' => '添加用户',
                    'url' => U('Admin/User/add'),
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
		$role=I('request.role',0,'intval');

        $breadCrumb = array('用户列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
 
        
		$pageMaps = array();

        $pageMaps['keyword'] = $keyword;
        $pageMaps['role']  = $role;
		$where = array();

		if(!empty($keyword)){
			$where['_string'] = '(nickname like "%'.$keyword.'%") OR (phone = "'.$keyword.'") OR (top_num = "'.$keyword.'")';
        }
       
        if(!empty($role)){
            $where['role'] = $role;
        }
 
        $order = "id desc";
		
        $count = D('Users')->countList($where);
        $limit = $this->getPageLimit($count,20);
		
        $list = D('Users')->loadList($where,$limit,$order);

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
            $breadCrumb = array('用户列表'=>U('index'),'用户添加'=>U());
            $this->assign('breadCrumb',$breadCrumb);
            $this->assign('name','添加');

            $this->adminDisplay('info');
        }else{			
            if(D('Users')->saveData('add')){
                $this->success('用户添加成功！');
            }else{
                $msg = D('Users')->getError();
                if(empty($msg)){
                    $this->error('用户添加失败');
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
    		$breadCrumb = array('用户列表' => U('index'),'修改用户'=>U());
    		$this->assign('breadCrumb', $breadCrumb);
    		$userId = I('get.id','','intval');
    	    if(empty($userId)) $this->error('参数不能为空！');
    	    //获取记录
    	    $info = D ( 'Users' )->getUserInfo( ['id'=>$userId]);

    		if(!$info) $this->error('无数据！');
    	    
    		$this->assign('info',$info);
    		$this->assign('name','修改');
    		$this->adminDisplay('info');
    	}else{

    		if(D('Users')->saveData('edit')){
    			$this->success('修改成功！');
    		}else{
    			$msg = D('Users')->getError();
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
       	$res = M("Users") -> where("id = ".$uid) -> delete();
		if($res){
			$this->success('用户删除成功！');
		}else{
			$this->error('用户删除失败！');
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
			$res = M("Users") -> where("id in(".$ids.")") -> delete();
			if($res){
			
				$this->success('用户删除成功！');
			}else{
				$this->error('用户删除失败！');
			}			
		}
	}
	
	
}

