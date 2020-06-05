<?php
namespace Article\Model;
use Think\Model;
/**
 * 内容操作
 */
class VideoModel extends Model {

    protected $_auto=[
        ['time','strtotime',3,'function'],
    ];
    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $field='',$limit = 0, $order = 'A.id desc'){

        $pageList = $this->table("__VIDEO__ as A")
                        ->join('__USERS__ as U ON A.author_id = U.id','left')
                        ->field($field)
                        ->where($where)
                        ->order($order)
                        ->limit($limit)
                        ->select();

        return $pageList;
    }

    /**
     * 获取数量
     * @return int 数量
     */
    public function countList($where = array()){

        return $this->table("__VIDEO__ as A")
                    ->join('__USERS__ as U ON A.author_id = U.id','left')
                    ->where($where)
                    ->order($order)
                    ->count();
    }

    /**
     * 获取信息
     * @param int $contentId ID
     * @return array 信息
     */
    public function getInfo($id)
    {
        $map = array();
        $map['A.id'] = $id;
        $info = $this->getWhereInfo($map);
        if(empty($info)){
            $this->error = '视频不存在！';
        }
        return $info;
    }

    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getWhereInfo($where,$order = '')
    {
        return $this->table("__VIDEO__ as A")
                    ->join('__USERS__ as U ON A.author_id = U.id','left')
                    ->where($where)
                    ->field('A.*,U.nickname as author_name')
                    ->order($order)
                    ->find();
    }
    /**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){

        $data = $this->create();
//         dd($data);
        if(!$data){
            return false;
        }
        if($type == 'add'){

            $id = $this->add($data);

            if($id){
                return $id;
            }else{
                return false;
            }
        }
        if($type == 'edit'){
            
            $status = $this->where('id='.$data['id'])->save();
            if($status === false){
                return false;
            }

            return true;
        }
    }

    /**
     * 删除信息
     * @param int $contentId ID
     * @return bool 删除状态
     */
    public function delData($id)
    {
        $map['id'] = $id;
        $status = $this->where($map)->delete();
        if($status){
            return $status;
        }else{
            return false;
        }
        
    }
    
}
