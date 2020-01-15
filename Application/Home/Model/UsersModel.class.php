<?php
namespace Home\Model;
use Think\Model;

class UsersModel extends Model {
	
    public function countWhere($map){// 统计满足条件的数量
        
        return $this->where($map)->count();
    }
	public function wreuval($openid,$field='id'){
	
		$uid = M('Wx_openid')->where("openid='{$openid}'")->getField('uid');
		if($uid){
			return $this->where("id='{$uid}'")->getField($field);
		}
		
		return $this->where("weixin='{$openid}'")->getField($field);
	}
	
	public function idreinfo($id,$field='weixin'){//id��ȡ info
	$re = $this->find($id);
	return $re[$field];
	} 

	public function qd(){
		
	}
	
	public function myfens($uid){
		return $this->where("parent_id='{$uid}'")->count();
	}
	
	public function checkuser($log="",$pwd=""){
		$user = $this->checktel($log);
		if($user){
			if($user['user_status']==0){
				$this->error="该用户已被禁止登陆";
				return -2;
			} 
			
			$userlog = getSessionCookie('usererror');
			if($userlog==-1){
				$this->error="您输入错误密码太多，一个小时候将无法登入";
				return -2;
			}
			if(md5 ( $user['user_login'] . $pwd . C ( 'PWD_SALA' ) ) != $user ['user_pass']){
				$userlog = isset($userlog)?$userlog-1:4;
				setSessionCookie('usererror', $userlog,3600);
				if($userlog==-1){
					$this->error="您输入错误密码太多，一个小时候将无法登入";
				} 
				else{
					I('post.type')=='wxbind'? $this->error='密码错误，还有'.$userlog.'次机会!您可以下载app找回密码':
					$this->error='密码错误，还有'.$userlog.'次机会,您可以<a href="'.U('Home/Public/getpwd').'" class="zhmm">找回密码</a>';
				}
					
				return -2;
			}
			return 1;
		}else
			$this->error="该用户不存在";
		return -1;
	}
	
	//手机号码是否已经存在
	public function checktel($tel){
		$where['user_tel'] =$tel;
		$user = $this->field('user_login,user_pass,user_status')->where($where)->order('weixin desc')->find();

		return $user;
	}
	//纯手机注册
	public function reg($log="",$pwd=""){
		$arr ['user_login'] = $log;
		$arr ['last_login_ip'] = $arr ['regip'] = get_client_ip ();
		$arr ['create_time'] = time ();
		$arr ['user_tel'] = $log;
		$arr ['last_login_time'] = time ();
		$arr ['user_pass'] = md5 ( $log . $pwd . C ( 'PWD_SALA' ) );
		
		$data = A('Home/Site')->get_address($log);
		if($data){
			$arr ['b_pro'] = $data['b_pro'];
			$arr ['b_city'] = $data['b_city'];
			$arr ['b_fws'] = $data['b_fws'];
		}
	    if(cookie('appCode')) $arr ['appCode'] = cookie('appCode');
	    

	    	
		$res = M ( 'users' )->add ( $arr );
		if($res) {
			if(C('MobRegMoney')>0)
			A('Home/Site')->changemoney(C('MobRegMoney'),'18','手机注册奖励',$res,0);
			return $res;
			}
		return false;
	}
	
	/**
	 * 获取信息
	 * @param array $where 条件
	 * @return array 信息
	 */
	public function getUserInfo($where)
	{
		return $this
		->where($where)
		->find();
	}
	
	public function getWhereInfo($where)
	{
	    return $this->where($where)
	               ->select();
	}
	
	/**
	 * 获取code_id
	 * @param array $where 条件
	 * @return array 信息
	 */
	public function getUserCode($where)
	{
	    return $this->where($where)
	    ->field('id,code_id')
	    ->find();
	}
	
	/**
	 * 注销当前用户
	 * @return void
	 */
	public function logout(){
		session('home_user', null);
		session('home_user_sign', null);
	}
	
	/**
	 * 登录用户
	 * @param int $userId ID
	 * @return bool 登录状态
	 */
	public function setLogin($userId)
	{
		// 更新登录信息
		$data = array(
				'id' => $userId,
// 				'last_login_time' => NOW_TIME,
				'last_login_ip' => get_client_ip(),
		);
		$this->save($data);
		//写入系统记录
		//api('Admin','AdminLog','addLog','登录系统');
		//设置cookie
		$auth = array(
				'user_id' => $userId,
		);
		 
		session('home_user', $auth);
		session('home_user_sign', data_auth_sign($auth));
		return true;
	}
	
	
	//微信注册
	public function wxreg($openid,$rearr2){
		
		if(!$openid||!$rearr2) return false;
		
		$data['user_login']=$data['weixin']=$data['user_pass']=$openid;
		$data['avatar']=$rearr2["headimgurl"]?$rearr2["headimgurl"]:0;
		$data['user_nicename']=$rearr2["nickname"]?$rearr2["nickname"]:'sm'.date('ymdHis',time()).rand(1111,9999);
		$data['unionid']=$rearr2['unionid']?$rearr2['unionid']:0;
		$data['subscribe']=$rearr2['subscribe']?$rearr2['subscribe']:0;
		$data['country']=$rearr2['country'];
		$data['province']=$rearr2['province'];
		$data['city']=$rearr2['city'];
		$data['subscribe_time']=$rearr2['subscribe_time']?$rearr2['subscribe_time']:'';
		$data['sex']=$rearr2['sex'];
		$data['create_time']=time();
		$data['regip']= get_client_ip ();
		$_COOKIE["yq"]?$data['parent_id']=$_COOKIE["yq"]:'';
		$data ['user_pass'] = md5 ( $data ["user_login"] . $data ["user_pass"] . C ( 'PWD_SALA' ) );
		$data['act_id'] = $rearr2['act_id']?$rearr2['act_id']:0;
		$data['code_id'] = $rearr2['code_id']?$rearr2['code_id']:0;

		$re = $this->add($data);

		return $re;
		
	}
	//微信注册
	public function wxregopenid($openid,$rearr2){
	    
	    if(!$openid||!$rearr2) return false;
	    
	    

	    $data['create_time']=time();
	    $data['regip']= get_client_ip ();
	    $data['openid']=$openid;

	    $data['act_id'] = $rearr2['act_id']?$rearr2['act_id']:0;
	    $data['parent_id'] = $rearr2['pid']?$rearr2['pid']:0;
	    
	    $re = $this->add($data);
	    
	    return $re;
	    
	}
	
	//微信修改用户
	public function wx_edituser($openid,$uid,$rearr2){
		if(!$openid||!$rearr2) return false;
		$data['weixin']=$openid;
		$data['avatar']=$rearr2["headimgurl"]?$rearr2["headimgurl"]:0;
		$data['user_nicename']=$rearr2["nickname"]?$rearr2["nickname"]:'sm'.date('ymdHis',time()).rand(1111,9999);
		$data['unionid']=$rearr2['unionid']?$rearr2['unionid']:0;
		$data['subscribe']=$rearr2['subscribe'];
		$data['country']=$rearr2['country'];
		$data['province']=$rearr2['province'];
		$data['city']=$rearr2['city'];
		$data['subscribe_time']=$rearr2['subscribe_time'];
		$data['sex']=$rearr2['sex'];
		$data['regip']= get_client_ip ();
		$w['id'] = $uid;
		return $this->where($w)->save($data);
	}
	
	
	
	public function app_returnuinfo($unionid){
		$u_where['openid'] =$unionid;
		$uid = M('User_bind')->where($u_where)->getField('uid');
		$re = '';
		if($uid){
		    $re = $this->where(" id= ".$uid)->find();
		}else{
			$where['unionid'] = $unionid;
			$re = $this->where($where)->find();
		}
		$appCode = cookie('appCode');
        if($re&&$appCode){
        	if($re['appCode'] != $appCode)  $this->where('id = '.$re['id'])->setField('appCode',$appCode);       	
        }
		
		return $re;
		
	}
	
	
	
	
	
	//第三方获取用户数据
	public function returnuinfo($openid=0,$unionid=0,$code='weixin'){
		if(!$openid) return false;
		$wxoid = $openid;
		if($unionid){
			$u_where['openid'] =$unionid;
			$uid = M('User_bind')->where($u_where)->getField('uid');	
		}
		if(!$uid){
			
			$u_where['openid'] =$openid;
			$uid = M('User_bind')->where($u_where)->getField('uid');
		}		
		if($uid){
			$re = $this->where(" id= ".$uid)->find();
			
			if(!$re){
				M('User_bind')->where($u_where)->delete();
				M('Wx_openid')->where("openid ='".$openid."'")->delete();
			}
			if($re){
				//检查openid
				if(C('gzhtype')!=2){
					$this->checkWxopenid($re['id'],$wxoid);
				}
				$this->checkWxunionid($uid, $unionid);
			}
			

			return $re; 
		}else{
			if($unionid){
				$where['unionid'] = $unionid;
				$re = $this->where($where)->find();
				if($re) $openid =$unionid;
			}
			if(!$re){
				$w['weixin'] = $openid;
				$re = $this->where($w)->find();
				if($re&&$unionid){
				   $res =$this->where($w)->setField('unionid',$unionid);	
				   if($res) $openid =$unionid;
				} 
			}			
		    if($re){
		      $this->checkWxunionid($re['id'], $unionid);
		      $res = M('User_bind')->add(array('uid'=>$re['id'],'openid'=>$openid,'code'=>$code));
		      if(C('gzhtype')!=2){
		      	M('Wx_openid')->add(array('uid'=>$re['id'],'openid'=>$wxoid,'gowxurl'=>C('gowxurl')?C('gowxurl'):999));
		      }
		      return $re;
		    }
		}

		return false;
	}
	
	
	//限制请求次数
	public function sms_limit(){
		$userlog = getSessionCookie('telerror');
		$userlog = isset($userlog)?$userlog-1:100;
		
		if($userlog==-1){
			return false;
		}
		setSessionCookie('telerror', $userlog,24*3600);
		return true;
	}
	
	//获取用户信息
	public function getUser($user_login){
		$where['user_login'] =$user_login;
		$user = $this->where($where)->find();
		return $user;
	}
	
	
	//获取微信openid
	public function getWxOpenid($uid,$openid){
		$gowxurl = C('gowxurl')?C('gowxurl'):999;

		$w['uid'] = $uid;
		$w['gowxurl'] = $gowxurl;
		$wxid = M('Wx_openid')->where($w)->getField('openid');
        if($wxid)
		return $wxid;
        else 
        return $openid;
		
	}
	
	//检查微信openid
	public function checkWxopenid($uid,$openid){
	  // $wxid = $this->getWxOpenid($uid,$openid);
		
		
		
		$gowxurl = C('gowxurl')?C('gowxurl'):999;

		$w['uid'] = $uid;
		$w['gowxurl'] = $gowxurl;
		$wxdata = M('Wx_openid')->field('openid')->where($w)->find();

		
		if(!$wxdata){
		   $re = M('Wx_openid')->add(array('uid'=>$uid,'openid'=>$openid,'gowxurl'=>$gowxurl));
		   if(!$re){
		   	M('Wx_openid')->where("openid = '".$openid."'")->delete();
		   	$re = M('Wx_openid')->add(array('uid'=>$uid,'openid'=>$openid,'gowxurl'=>$gowxurl));
		   } 
		 
		}

		if($wxdata&&$wxdata!=$openid){
			M('Wx_openid')->where($w)->setField('openid',$openid);
			
		}
		$this->set_weixin($uid);
		return true;
	}
	
	public function Z_checkWxopenid($uid,$openid){
		$gowxurl = 999;
		
		$w['uid'] = $uid;
		$w['gowxurl'] = $gowxurl;
		$wxdata = M('Wx_openid')->field('openid')->where($w)->find();
		
		if(!$wxdata){
			$re = M('Wx_openid')->add(array('uid'=>$uid,'openid'=>$openid,'gowxurl'=>$gowxurl));
			if(!$re){
				M('Wx_openid')->where("openid = '".$openid."'")->delete();
				$re = M('Wx_openid')->add(array('uid'=>$uid,'openid'=>$openid,'gowxurl'=>$gowxurl));
			}
			
		}

		if($wxdata&&$wxdata!=$openid){
			M('Wx_openid')->where($w)->setField('openid',$openid);	
		}
		$this->set_weixin($uid);
		return true;
	}
	
	
	public function get_z_openid($uid,$openid){
		$w['uid'] = $uid;
		$w['gowxurl'] = 999;
		$wxid = M('Wx_openid')->where($w)->getField('openid');
		if($wxid)
			return $wxid;
		else
			return $openid;
	}
	
	
	public function set_weixin($uid){
		$w =array('gowxurl'=>999,'uid'=>$uid);
		$openid =  M('Wx_openid')->where($w)->getField('openid');
		$weixin =  M('Users')->where('id = '.$uid)->getField('weixin');
		if($openid&&$openid!=$weixin){
			M('Users')->where('id = '.$uid)->setField('weixin',$openid);
		}
		
	}
	
	
	//检查微信unionid 检查手机地址
	public function checkWxunionid($uid,$unionid){
		 $re = '';
	     $Ounionid = $this->field('unionid,user_tel,b_pro')->where('id = '.$uid )->find();
		 if($Ounionid['unionid'] !=$unionid){
			$re = $this->where('id = '.$uid )->setField('unionid',$unionid);
		}
		if($Ounionid['user_tel']&&(!$Ounionid['b_pro']||$Ounionid['b_pro']=='未知')){
			$data = A('Home/Site')->get_address($Ounionid['user_tel']);
			if($data){
				$arr ['b_pro'] = $data['b_pro'];
				$arr ['b_city'] = $data['b_city'];
				$arr ['b_fws'] = $data['b_fws'];
			}
			$re = $this->where('id = '.$uid )->save($arr);
		}
		
		
	     
		return $re;
	}

	
	
	public  function getAppMoney($userinfo){
		
		if(C('SetAppLoginMoney')>0){
			if(cookie('appCode')&&!$userinfo['appMoneyStatus']){
				$appMoneyStatus =  $this->where('id = '.$userinfo['id'])->getField('appMoneyStatus');
				if(!$appMoneyStatus){
				    $re2 =  $this->where('id = '.$userinfo['id'])->setField('appMoneyStatus',1);
					 if($re2){
						$re =  A('Home/Site')->changemoney(C('SetAppLoginMoney'),'17','首次登录app奖励金钱',$userinfo['id'],1); 
					 }
					return $re;
				}
				
			}
		
		}
	}
	
	//定义一个方法，找出 给定用户的     三级后代   的id
	public function getSubIds($uid){
		$arr=array();		
		$uids = $uid;
		for($i=1;$i<4;$i++){													
			$data = $this->field("id")->where("parent_id in(".$uids.")")->select();				
			if($data){															
				$uids = '';						
				foreach($data as $v){
					$uids = $uids?$uids.','.$v['id']:$v['id'];
					$arr[$v['id']]=$i;
				}	
			}else{
				break;
			}
		}
		return $arr;					
	}
	
	
}

?>