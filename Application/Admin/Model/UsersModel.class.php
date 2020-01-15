<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class UsersModel extends Model {
	
	protected $_auto = array (
			array('user_login','htmlspecialchars',3,'function'),
			array('user_nicename','htmlspecialchars',3,'function'),
			//array('user_email','htmlspecialchars',3,'function'),
			//array('user_status','2'),			
	        array('user_pass','getpwd',3,'callback') ,
		    array('user_pass','',2,'ignore'),
	        array('create_time','time',1,'function'), 
	        array('last_login_time','time',1,'function'), 
	        );
	
	protected $_validate = array(   
			 // array('verify','require','验证码必须！'), //默认情况下用正则进行验证   
			array('user_login','','帐号名称已经存在！',1,'unique',1), // 在新增的时候验证name字段是否唯一   
			  //    array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内  
			//array('user_pass2','user_pass','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致   
			//array('user_pass','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式  
			);
	 /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){
        //$where['C.app'] = 'Admin';
        return $this->where($where)
                    ->order($order)
                    ->count();
    }
	
	public function loadList($where = array(),$limit=0,$order='id desc'){
		return $this->where($where)
                    ->order($order)
                    ->limit($limit)
					->select();
	}
	
	public function getfensicount($pid){
		return $this->where("parent_id=".$pid)->count();
	}
	
	/**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){
        //事务总表处理
        $this->startTrans();
        
        //分表处理
        $data = $this->create();
		
        if(!$data){
            $this->rollback();
            return false;
        }
        if($type == 'add'){
			
            //$this->content_id = $contentId;
            $status = $this -> add();
            if($status){
                $this->commit();
            }else{
                $this->rollback();
            }
            return $status;
        }
        if($type == 'edit'){ 
            $status = $this->where('id='.$data['id'])->save();
            if($status === false){
                $this->rollback();
                return false;
            }
            $this->commit();
            return true;
        }
        $this->rollback();
        return false;
    }
	
	 public function delData($contentId)
    {
        $this->startTrans();       
        $map = array();
        $map['_string'] = 'id in('.$contentId.')';
        $status = $this->where($map)->delete();
        if($status){
            $this->commit();
        }else{
            $this->rollback();
        }
        return $status;
    }
   
    //密码加密
    public  function  getpwd(){
    	$pwd   =   I('post.user_pass');
    	$username = I('post.user_login');
    	if(!$pwd) return false;
    	$pwd   =   md5 ( $username . $pwd  . C ( 'PWD_SALA' ) );
    	return $pwd;
    }
   
	//idmd5
	public function getidmd5(){		
		$username = I('post.user_login');
		$idmd5 = md5('jnooo'.$username.time());
		return $idmd5;
	}   
   
   
   
    
  public function countPhotoList($where = array()){
        //$where['C.app'] = 'Admin';
        $mod = M('UserPhoto');
        return $mod->where($where)
                    ->order($order)
                    ->count();
    }
	
	public function loadPhotoList($where = array(),$limit=0,$order='a.photoid desc'){
		$mod = M('UserPhoto');
		
		return $mod->table('__USER_PHOTO__ as a')
		            ->join('__USERS__ as b  ON a.uid = b.id')
		            ->field('a.*,b.user_nicename,b.user_login') 
		            ->where($where)
                    ->order($order)
                    ->limit($limit)
					->select();
	}
    
	public function delPhoto($Id)
	{
		$mod= M("UserPhoto");
		$mod->startTrans();
		$map = array();
		$map['_string'] = "photoid in(".$Id.")";
		$status = $mod->where($map)->delete();
		if($status){
			$mod->commit();
		}else{
			$mod->rollback();
		}
		return $status;
	}
	
	public function operatePhoto($Id,$name,$status){
		
		$data[$name]=$status;
	
		$mod= M("UserPhoto");
		$mod->startTrans();
		$map = array();
		$map['_string'] = "photoid in(".$Id.")";
		
		$re = $mod->where($map)->save($data);
		
		if($re){
			$this->payMoney($Id, $name, $status);
			$mod->commit();
			
		}else{
			$mod->rollback();
		}
		return $re;

		
	}
	
	
	//上传照片返利
	private function payMoney($Id,$name,$status){
		
		 if(C('photo_flag')>0) {
		 if($name=="flag"&&$status==1&&$Id){
		 $list = M('UserPhoto')->field('photoid,uid,payMoney,timeline,phototype')->where('photoid in ('.$Id.')')->select();
		 	foreach ($list as $k =>$v){
		 		if($v['payMoney']==0){
		 			
		 			$this->setPhotoMoney($v['photoid'],$v['uid'],$v['phototype'],1,$v['timeline']);		 			
		 		}
		 	  }
		   }
		 }
		return  false;
	}
	
	private function setPhotoMoney($id,$uid,$uptype,$image_count,$timeline){
		$timeline =  date("Ymd",$timeline);
	    $name = 'count_'.$uptype.'_upPhoto_'.$timeline.'_uid_'.$uid;
		
		$UserPhotoMod = M('UserPhoto');
		if(!cookie($name)){
		if(S($name)){
			$count = intval(S($name));
		}else{
			$where['uid'] = $uid;
			$where['phototype'] = $uptype;
			$where['flag'] = 1;
			$where['payMoney'] = 1;
			$where['_string'] = "FROM_UNIXTIME( timeline, '%Y%m%d' ) =".$timeline;
			$count = $UserPhotoMod->where($where)->count();
		}
		$cif_num = $uptype?C('up_photo_sm_num'):C('up_photo_gk_num');
		$cif_money = $uptype?C('up_photo_sm'):C('up_photo_gk');
		$num = 0;
         $tolcount = ($count+$image_count);
		
		if($tolcount <=  $cif_num){
			$num =  $image_count;
		}else{
			if($count < $cif_num){
				$num =  $cif_num - $count;
			}
		}

		if($num){
			$typeName = $uptype?'私密照':'公开照';
			$desc ="上传".$num."张".$typeName.",获取".C('money_name');
			$re = A('Common/Base')->changemoney($uid, $num*$cif_money,0,$desc,'photo','',0,get_client_ip(),0,9);

			if($re >= 0){
				A('Common/Base')->tongjiarr($uid, array('photofmoney'=>$num*$cif_money,'photonum'=>$image_count));
			    $UserPhotoMod->where('photoid = '.$id)->setField('payMoney',1);
			    S($name,$count+1,24*3600);
			}
		}else{
			A('Common/Base')->tongji($uid, 'photonum',$image_count);
          		cookie($name,'max',24*3600);
        }
	}else{
		A('Common/Base')->tongji($uid, 'photonum',$image_count);
	}
	
	}
	
	//获取昵称等
	public function getNicename($ids = array()){
		if(!$ids) return false;
		$re = $this -> table("__USERS__") ->field('id,user_nicename,user_login') -> where('id in('.$ids.')') -> select();
		if(!$re){
			return false;
		} 
		$arr = array();
	  	foreach($re as $k =>$v){
	  		$arr[$v['id']] = $v['user_nicename'] ? $v['user_nicename'] : $v['user_login'] ;
	  	}		
		return $arr;
	}
	
	//获取照片   (非头像)
	public function getPhoto($ids = array()){
		$re = $this -> table("__USER_PHOTO__") ->field('photoid,thumbfiles') -> where('photoid in('.$ids.')') -> select();
		if(!$re){
			return false;
		} 
		$arr = array();
	  	foreach($re as $k =>$v){
	  		$arr[$v['photoid']] = $v['thumbfiles'];
	  	}		
		return $arr;
	}
	

}