<?php
namespace Admin\Model;
use Think\Model;
/**
 * 内容操作
 */
class ContentModel extends Model {


    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $limit = 0, $order = 'time desc,content_id desc'){
        $list = $this->where($where)
                    ->field('content_id,title,description,image')
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
        return $this->table("__CONTENT__ as A")
                    ->join('__CATEGORY__ as B ON A.class_id = B.class_id')
                    ->where($where)
                    ->count();
    }
    
    /**
     * 获取信息
     * @param array $where 条件
     * @return array 信息
     */
    public function getWhereInfo($where){
        return $this->table("__CONTENT__ as A")
                    ->join('__CATEGORY__ as B ON A.class_id = B.class_id')
                    ->field('A.*,B.name as class_name,B.app,B.urlname as class_urlname,B.image as class_image')
                    ->where($where)
                    ->find();
    }


}
