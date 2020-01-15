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
		$status = I('request.status',0,'intval');
		$subscribe=I('request.subscribe',0,'intval');
	
        $breadCrumb = array('用户列表' => U());
        $this->assign('breadCrumb', $breadCrumb);
 
        
		$pageMaps = array();

        $pageMaps['keyword'] = $keyword;
        $pageMaps['status'] = $status;
        $pageMaps['subscribe'] = $subscribe;
		$where = array();

		$where['user_status'] =array('neq',3);
		
		if(!empty($keyword)){
			$where['_string'] = '(user_login like "%'.$keyword.'%") OR (id = "'.$keyword.'") OR (user_nicename like "%'.$keyword.'%")';
        }
        $pid =I('get.pid');
        $pageMaps['pid'] = $pid;
        if(!empty($pid)){
        	$where['parent_id'] = $pid;
        }	
		
		if(!empty($status)){
            switch ($status) {
                case '1': //正常
                    $where['user_status'] = 1;
                    break;
                case '2': //未验证
                    $where['user_status'] = 2;
                    break;
				case '3': //禁止
                    $where['user_status'] = 0;
                    break;
				case '4': //禁言
					$where['_string'] = 'user_status > '.time();
					break;
            }
        }
       
        if(!empty($subscribe)){
         	
         	switch ($subscribe) {
                case '1':
                    $where['subscribe'] = 1;
                    break;
                case '2':
                    $where['subscribe'] = 0;
                    break;				
            }     
        }
		
        $order = "id desc";
		
        $count = D('Users')->countList($where);
        $limit = $this->getPageLimit($count,20);
		
	    
        $list = D('Users')->loadList($where,$limit,$order);
        
        foreach ($list as $key => $val){
        	$list[$key]['fscount']=	D("Users")->where(array('parent_id'=>$val['id']))->count();
        }
       
        
        
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
    		$info = M ( 'Users' )->where ( "id=$userId" )->find ();
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
	
	
	public function sendaccount(){
	  	$mtype =I('post.mtype','','intval');
	  	$moeny =I('post.moeny');
	  	$uid = I('post.uid','','intval');
	  	$type = I('post.type','','intval');
  	
  		/* if($mtype == 1){
  	   		$jifen = $this->changejifen($moeny,$type,I("post.desc"),$uid);
  	   		if($jifen>=0){
  	   			exit("操作成功！");
  	   		}
  		} */
	  	if($mtype == 2){
	  		$res =  $this->changemoney($uid,$moeny,$type,I("post.desc").'+'.$moeny,0,0,1,0,0,10);
	  		
	  		if($res['status']==1){
	  		    $this->ajaxReturn(array('status'=>1,'msg'=>'成功','money'=>$res['money']));	  			
	  		}else{
	  			$this->ajaxReturn(array('status'=>-1,'msg'=>$res['msg']));	  			
	  		}
	  	}
  		exit('操作失败');
  	}
	
	
	//删除
	public function del(){
		$uid = I('post.data',0,'intval');
        if(empty($uid)){
            $this->error('参数不能为空！');
        }
       	$res = M("Users") -> where("id = ".$uid) -> delete();
		if($res){
			M("AccountMoneyLog")->where("uid = ".$ids)->delete();
					
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
	
	

	
	
  
  
  	public function sendhbs(){// http://shanmao.me
  		$openid = I("post.openid");
  		$title = I("post.hbtitle");
  		$body = I("post.hbbody");
  		$fee = I("post.fee");
  		$type = I("post.type"); 		
  		exit($this->sendhb($openid,$title,$body,$fee,$type));
  	}
/*  
  	public function sendzz(){
	  	$Transfers = new \Org\Util\Transfers();	
		$re = $Transfers->dozz(I("post.openid"),I("post.fee"),I("post.desc"));
		if($re['result_code']=='SUCCESS'){ // 正确返回
			$this->log_money1(I("post.openid"),I("post.fee"),I("post.desc"),-1);
	 		exit("转账成功！");
		}else{//返回错误信息	 
			exit($re['return_msg']);
		} 
  	}
  */

  
  
  
   	public function log_money1($openid=0,$fee=0,$desc=0,$type=0){//记录现金记录  	
		$data['time']=time();
	  	$data['openid']=$openid;
	  	$data['uid']=M("Users")->where("weixin='{$openid}'")->getField('id');
	  	$data['fee']=$fee;
	  	$data['body']=$desc;
	  	$data['type']=$type;
	  	M("moeny_log")->add($data);
  	}
   
   
    /**
     * 微信红包
     */
    public function  userWx(){
    	$uid  = I('get.id');
    	$info = D('Users')->find($uid);
    	$this->assign('info',$info);  	 
    	$this->adminDisplay('userWx');
    }
    

	
	
	
}

