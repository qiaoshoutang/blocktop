<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台菜单
 */
class WechatModel extends Model {
	
    protected $_auto = array (
        array('time','time',1,'function'),
        array('last_time','strtotime',3,'function'), //置顶时间
    );
    
    protected $_validate = array(
        array('name','','公众号已存在',1,'unique',3), // 在新增的时候验证name字段是否唯一
    );

    
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