<?php
namespace Article\Model;
use Think\Model;
/**
 * 内容操作
 */
class ContentArticleModel extends Model {
    //验证
    protected $_validate = array(
        array('content','empty', '请填写文章内容', 1 ,'function',3),
    );

    /**
     * 获取列表
     * @return array 列表
     */
    public function loadList($where = array(), $field='',$limit = 0, $order = 'A.content_id desc'){

        $pageList = $this->table("__CONTENT__ as A")
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

        return $this->table("__CONTENT__ as A")
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
    public function getInfo($contentId)
    {
        $map = array();
        $map['A.content_id'] = $contentId;
        $info = $this->getWhereInfo($map);
        if(empty($info)){
            $this->error = '文章不存在！';
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
        return $this->table("__CONTENT__ as A")
                    ->join('__CONTENT_ARTICLE__ as B ON A.content_id = B.content_id')
                    ->join('__USERS__ as U ON A.author_id = U.id','left')
                    ->field('A.*,B.content,U.nickname as author_name')
                    ->where($where)
                    ->order($order)
                    ->find();
    }

    /**
     * 更新信息
     * @param string $type 更新类型
     * @return bool 更新状态
     */
    public function saveData($type = 'add'){
//         $data=I('post.');
//         var_dump($data);
//         exit;
        if(empty($_POST['keyword'])){
            $_POST['keyword'] ='区块链,区块链头条,区块链头条官网';
        }

        //事务总表处理
        $this->startTrans();
        $contentId = D('DuxCms/Content')->saveData($type);

        if(!$contentId){
            $this->error = D('DuxCms/Content')->getError();
            return false;
        }
        //分表处理
        $data = $this->create();
        if(!$data){
            $this->rollback();
            return false;
        }

        if($type == 'add'){
            $this->content_id = $contentId;
            $status = $this->add();

            if($status){
                $this->commit();
            }else{
                $this->rollback();
            }
          
            return $contentId;
        }
        if($type == 'edit'){
            
            $status = $this->where('content_id='.$data['content_id'])->save();
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

    /**
     * 删除信息
     * @param int $contentId ID
     * @return bool 删除状态
     */
    public function delData($contentId)
    {
        $this->startTrans();
        $model = D('DuxCms/Content');
        $status = $model->delData($contentId);
        if(!$status){
            $this->error = $model->getError();
            $this->rollback();
            return false;
        }
        $map = array();
        $map['content_id'] = $contentId;
        $status = $this->where($map)->delete();
        if($status){
            $this->commit();
        }else{
            $this->rollback();
        }
        return $status;
    }

    /**
     * 获取内容URL
     * @param int $info 栏目信息
     * @return bool 删除状态
     */
    public function getUrl($info)
    {
        return U('Article/Content/index',array('content_id'=>$info['content_id']));
    }

    
    /*************获取content_article表中的图片****************/
    public function getImg($id){
    
    	$result = M('content_article')->field('content')->where('content_id='.$id)->find();
    	$pageContents =  htmlspecialchars_decode($result['content']);
    	$pageContents =  str_replace('&lt;', '<', $pageContents);
    	$pageContents =  str_replace('&quot;', '"', $pageContents);
    	$pageContents =  str_replace('&gt;', '>', $pageContents);
    	preg_match_all('/<img.*?src="(.*?)".*?>/is',$pageContents,$results);//正则表达式
    	return $results[1];
    }
    /*****************************/
    
    
}
