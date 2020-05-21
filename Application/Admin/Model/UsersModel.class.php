<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class UsersModel extends Model {
	
    protected $_auto = array (
        array('nickname','htmlspecialchars',3,'function'),
        array('top_num','getNum',1,'callback'),
        array('password','getpwd',3,'callback') ,
        array('password','',2,'ignore'),
        array('create_time','time',1,'function'),
    );
    
    protected $_validate = array(

        array('phone','','手机号已经存在！',1,'unique',3), // 在新增的时候验证name字段是否唯一
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致

    );
    public function getNum(){
        return getTopnum();
    }
    
    //密码加密
    public  function  getpwd(){
        $password   =   I('post.password');

        if(!$password) return false;
        $pwd   =  md5('blocktop_'.$password);

        return $pwd;
    }
    
    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getUserInfo($where,$field='')
    {
        
        $data = $this->where($where)->field($field)->find();
        if($data['nickname']){
            $data['nickname'] = htmlspecialchars_decode($data['nickname']);
        }
        return $data;
    }
	 /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){

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
	
	/**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){

        
        //分表处理
        $data = $this->create();
		
        if(!$data){

            return false;
        }
        if($type == 'add'){
			
            $status = $this -> add($data);

            return $status;
        }
        if($type == 'edit'){ 
            $status = $this->where('id='.$data['id'])->save($data);
            if($status === false){

                return false;
            }

            return true;
        }
        $this->rollback();
        return false;
    }
	
	public function delData($contentId){
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
	

}