<?php
namespace Home\Model;
use Think\Model;

class UsersModel extends Model {
	
    public function countWhere($map){// 统计满足条件的数量
        
        return $this->where($map)->count();
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

		$auth = array(
				'user_id' => $userId,
		);
		 
		session('home_user', $auth);
// 		session('home_user_sign', data_auth_sign($auth));
		return true;
	}
	
	


	//获取用户信息
	public function getUser($user_login){
		$where['user_login'] =$user_login;
		$user = $this->where($where)->find();
		return $user;
	}
	
	
	
}

?>