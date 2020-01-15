<?php
namespace Admin\Model;
use Think\Model;
/**
 * 内容操作
 */
class ActivityModel extends Model {

    protected $_auto = array (
        array('time','time',3,'function'),  //状态
  
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 50, $order = 'id desc'){
        
        $list = $this->where($where)
                    ->limit($limit)
                    ->order($order)
                    ->select();

        return $list;
    }

    /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){
        return $this->where($where)
                    ->count();
    }
    
    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getWhereInfo($where){
        return $this->where($where)
                    ->find();
    }
    
    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getInfoById($id){
        $map['id'] = $id;
        return $this->where($map)
                    ->find();
    }
    
    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getNameList(){
        
        return $this->field('id,name')
                    ->select();
    }
    
    
    
    
    /**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){
        
//         $data=I('post.');
//         var_dump($data);
        

        $data=$this->create();
//         var_dump($data);
//         exit;
        if(!$data){
            return false;
        }
        if($type == 'add'){
            //保存基本信息
            $activity_id = $this->add($data);
            if(!$activity_id){
                return false;
            }

            return $activity_id;
        }
        if($type == 'edit'){
            if(empty($data['id'])){
                return false;
            }
            $status = $this->save();
            if($status === false){
                return false;
            }

            return true;
        }
        return false;
    }



    /**
     * 删除信息
     * @param int $contentId ID
     * @return bool 删除状态
     */
    public function delData($id){
        $map = array();
        $map['id'] = $id;

        $status = $this->where($map)->delete();
        if(!$status){
            return false;
        }
        return $status;
    }







}
