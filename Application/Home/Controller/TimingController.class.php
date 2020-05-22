<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 定时任务
 */

class TimingController extends SiteController {
    
    //文章置顶是否到期  每五分钟执行一次
    public function top_operation(){
        header("Content-Type:text/html; charset=utf-8");
        $contentMod = M('content');
        $where['is_top'] = 2;
        $where['top_time'] = array('lt',time());
        $articleList = $contentMod->where($where)->field('content_id,top_time')->select();
        if(empty($articleList)){
            echo '没有文章需要处理';
            exit;
        }
        foreach($articleList as $val){

            $res = $contentMod->where(['content_id'=>$val['content_id']])->setField('is_top',1);
            if(empty($res)){
                echo "文章".$val['content_id']."置顶截止时间已过，修改状态失败!";
                break;
            }else{
                echo "文章".$val['content_id']."取消置顶状态成功！";
            }
        }
    }
    
    //banner是否到期  每五分钟执行一次
    public function banner_operation(){
        header("Content-Type:text/html; charset=utf-8");
        $bannerMod = M('banner');
        $where['state'] = 1;
        $where['dead_time'] = array('lt',time());
        $bannerList = $bannerMod->where($where)->field('id,dead_time')->select();
        if(empty($bannerList)){
            echo '没有banner需要处理';
            exit;
        }
        foreach($bannerList as $val){
            
            $res = $bannerMod->where(['id'=>$val['id']])->setField('state',2);
            if(empty($res)){
                echo "轮播图".$val['id']."有效期已过，修改状态失败!";
                break;
            }else{
                echo "轮播图".$val['id']."有效期已过，修改状态成功！";
            }
        }
    }
    
   
    
}