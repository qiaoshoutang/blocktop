<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 站点首页
 */

class MobileController extends SiteController {
    
    
    public function __construct() {
        parent::__construct ();
        header("Content-Type:text/html; charset=utf-8");
        C('TPL_NAME','mobile');
    }
    
    //首页
    public function index(){
        
        $this->redirect('/news_m/all');
        $this -> siteDisplay('index');
    }
    
    //快讯列表
    public function message(){
        $where['state'] = 2;
        $messageMod = D('Article/Message');
        
        $messageCount = $messageMod->countList($where);
        $limit = $this->getPageLimit($messageCount,10);
        $messageList = $messageMod->loadList($where,$limit);
        
        $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
        
        foreach($messageList as $key=>$val){
            $messageList[$key]['timeH'] = $weekname[date('w',$val['time'])].' '.date('Y-m-d H:i');
            $messageList[$key]['content'] =html_out($val['content']);
        }
        $this->assign('messageList',$messageList);
        $this -> siteDisplay('message');
    }
    //新闻列表
    public function news(){
        
        $class_id = I('request.class_id','all');
        
        $where['status'] = 2;
        if($class_id != 'all'){
            $where['class_id'] = $class_id;
        }
        //热门新闻
        $newsList = M('content')->where($where)->field('content_id,title,description,image,time,views,author')->limit(10)
                                ->order('sequence desc,content_id desc')->select();
        
        foreach($newsList as $key=>$val){
            $newsList[$key]['description'] = html_out($val['description']);
            if($val['time']>(time()-3600)){ //一小时内
                $newsList[$key]['time'] = ceil((time()-$val['time'])/60).'分钟前';
            }else{
                $newsList[$key]['time'] = date('m-d H:i',$val['time']);
            }
        }
        
        if($newsList){
            $newsFirst = array_shift($newsList);
            $this->assign('newsFirst',$newsFirst);
        }

        $this->assign('class_id',$class_id);
        $this->assign('newsCate',M('category')->where(['show'=>1])->order('sequence asc')->select());
        $this->assign('newsList',$newsList);
        $this -> siteDisplay('news');
    }
    
    //新闻详情
    public function newsContent(){
        
        $content_id = I('request.content_id',0);
        
        M('content')->where(['content_id'=>$content_id])->setInc('views'); //浏览自增1
        
        $contentMod = D('Article/ContentArticle');
        
        $contentInfo = $contentMod->getInfo($content_id);
        $contentInfo['content'] = html_out($contentInfo['content']);
//         dd($contentInfo);
        $this->assign('contentInfo',$contentInfo);
        $this -> siteDisplay('newsContent');
    }
    
    //蚂蚁导航
    public function antMap(){
        
        $class_id = I('request.class_id','recom');
        
        $naviMod = D('Admin/Navi');
        
        $where['state'] = 1;
        if($class_id == 'recom'){
            
            $where['recom'] = 1;
            
            //行情
            $where['class_id'] = 1;
            $naviList1 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //资产管理
            $where['class_id'] = 2;
            $naviList2 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //应用
            $where['class_id'] = 3;
            $naviList3 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //钱包
            $where['class_id'] = 4;
            $naviList4 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //矿池
            $where['class_id'] = 5;
            $naviList5 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //媒体
            $where['class_id'] = 6;
            $naviList6 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //技术服务
            $where['class_id'] = 7;
            $naviList7 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            //社区
            $where['class_id'] = 8;
            $naviList8 = $naviMod->where($where)->limit(0,8)->order('order_id desc,id desc')->select();
            
            
            $this->assign('naviList1',$naviList1);
            $this->assign('naviList2',$naviList2);
            $this->assign('naviList3',$naviList3);
            $this->assign('naviList4',$naviList4);
            $this->assign('naviList5',$naviList5);
            $this->assign('naviList6',$naviList6);
            $this->assign('naviList7',$naviList7);
            $this->assign('naviList8',$naviList8);

        }else{
            $where['class_id'] = $class_id;
            
            $naviList = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            $this->assign('naviList',$naviList);
        }
        
        
        $cateList = M('navi_category')->where(['state'=>1])->select();
        
        $this->assign('cateList',$cateList);
        
        $this->assign('class_id',$class_id);
        
        $this -> siteDisplay('antmap');
    }
    
    
    //蚂蚁活动
    public function activity(){

        $activityMod = D('Admin/Activity');
        
        $activityList = $activityMod->loadList($where,9,'order_id desc,id desc');

        $this->assign('activityList',$activityList);
        $this -> siteDisplay('activity');
    }
    //蚂蚁活动详情
    public function activityContent(){

        $content_id = I('request.content_id',0);
        
        $activityMod = D('Admin/Activity');
        
        $activityMod->where(['id'=>$content_id])->setInc('views'); //浏览自增1
        
        $activityInfo = $activityMod->getInfoById($content_id);
        $activityInfo['content'] = html_out($activityInfo['content']);
//         dd($activityInfo);
        $this->assign('activityInfo',$activityInfo);
        $this -> siteDisplay('activityContent');
    }
    
    //联盟活动相册
    public function alliance_act_details(){
        
        $where = array();
        
        $activity_id= I('request.activity_id',0,'intval');
        
        $activityMod=D('Admin/Activity');
        
        if(!$activity_id){ //活动id为空  跳转至活动主页
            header("Location:".U('alliance_act'));
            exit;
        }
        $activity_info = $activityMod->getInfoById($activity_id);
        
        $imageMod  = D('Admin/Image');
        
        
        $where['activity_id'] = $activity_id;
        $where['status'] = 1;
        $where['fid']    = 0;
        
//         $coutnNum = $imageMod->countList($where);
        
//         $limit = $this->getPageLimit($coutnNum,6);
        
        $image_list = $imageMod->loadList($where,0,'order_id asc');

        
        foreach($image_list as $key=>$val){
            
            $record = $imageMod->getWhereInfo(array('fid'=>$val['id']));
            if($record){ //有子相册
                $image_list[$key]['sub'] = true;
            }else{
                $image_list[$key]['sub'] = false;
            }
        }
        
        
        $this->assign('activity_info',$activity_info);
        $this->assign('image_list',$image_list);
        $this -> siteDisplay('alliance_act_details');
    }
    
    //联盟活动相册详情
    public function alliance_album_details(){
        
        $where = array();
        
        $album_id= I('request.album_id',0,'intval');
        
        if(!$album_id){ //活动id为空  跳转至活动主页
            header("Location:".U('alliance_act'));
            exit;
        }
        
        $imageMod  = D('Admin/Image');
        $albumInfo = $imageMod->getInfoById($album_id);
        
        $activity_id = $albumInfo['activity_id'];
        
        $activityMod=D('Admin/Activity');
        

        $activity_info = $activityMod->getInfoById($activity_id);
        
        $imageMod  = D('Admin/Image');
        
        
        $where['status'] = 1;
        $where['fid']    = $album_id;
        
        $coutnNum = $imageMod->countList($where);
        
        $limit = $this->getPageLimit($coutnNum,6);
        
        $image_list = $imageMod->loadList($where,$limit,'order_id asc');
        
        $this->assign('activity_info',$activity_info);
        $this->assign('albumInfo',$albumInfo);
        $this->assign('image_list',$image_list);
        $this -> siteDisplay('alliance_album_details');
    }
    
    
    
    //业务结束
    public function business(){

        $this -> siteDisplay('business');
    }
    
    //团队结束
    public function team(){

        $this -> siteDisplay('team');
    }
    
    //关于我们
    public function aboutus(){

        $this -> siteDisplay('aboutus');
    }
    
    //采集
    public function collection(){
        $this->article_collection();
        $this->twitter_collection();
        $this->weibo_collection();
    }
    
   
    
    /**
     *
     *
     * @return string|boolean|array
     */
    public function ajax_add(){
        
        
        $type=I('request.type',0,'intval');
        $gzh_code=I('request.gzh_code',0,'trim');
        $page_num=I('request.p',0,'intval');
        
        $info['gzh_code']=$gzh_code;
        
        $_GET['p']=$page_num+1;
        
        switch ($type) {
            case 1:{ //快讯
                
                $where['A.status']=1;
                $contentMod=D('Article/ContentArticle');
                $count = $contentMod->countList($where);
                $limit = $this->getPageLimit($count,20);
                
                $article_list = $contentMod->loadList($where,$limit);
                $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
                foreach($article_list as $key=>$val){
                    $article_list[$key]['content']==html_out($val['content']);
                    $article_list[$key]['time_top']=date('m.d',$val['time']).' '.$weekname[date('w',$val['time'])];
                }
                $this->assign('info',$info);
                $this->assign('article_list',$article_list);
                $article=$this->fetch('article_model');
                
                if($article_list){
                    $data['code']=1;
                    $data['msg']='加载成功';
                    $data['page_p']= $_GET['p'];
                    $data['article']=$article;
                }else{
                    $data['code']=0;
                    $data['msg']='加载失败';
                }
                
                $this->ajaxReturn($data);
                
                break;
            }
            case 2:{//动态
                
                $where['status']=1;
                $twitterMod=D('Admin/Twitter');
                $count = $twitterMod->countList($where);
                $limit = $this->getPageLimit($count,20);
                
                $twitter_list = $twitterMod->loadList($where,$limit);
                
                foreach($twitter_list as $key=>$val){
                    $twitter_list[$key]['content']==html_out($val['content']);
                }
                
                $this->assign('twitter_list',$twitter_list);
                $twitter=$this->fetch('twitter_model');
                
                if($twitter_list){
                    $data['code']=1;
                    $data['msg']='加载成功';
                    $data['page_p']= $_GET['p'];
                    $data['article']=$twitter;
                }else{
                    $data['code']=0;
                    $data['msg']='加载失败';
                }
                
                $this->ajaxReturn($data);
                
                break;
            }
            case 3:{//微博
                
                $where['status']=1;
                $weiboMod=D('Admin/Weibo');
                $count = $weiboMod->countList($where);
                $limit = $this->getPageLimit($count,20);
                
                $weibo_list = $weiboMod->loadList($where,$limit);
                foreach($weibo_list as $key=>$val){
                    $weibo_list[$key]['content']==html_out($val['content']);
                }
                
                $this->assign('weibo_list',$weibo_list);
                $weibo=$this->fetch('weibo_model');
                
                if($weibo_list){
                    $data['code']=1;
                    $data['msg']='加载成功';
                    $data['page_p']= $_GET['p'];
                    $data['article']=$weibo;
                }else{
                    $data['code']=0;
                    $data['msg']='加载失败';
                }
                
                $this->ajaxReturn($data);
                
                break;
            }
        }
    }
    
    /**
     *动态采集
     */
    public function twitter_collection(){
        header("Content-Type:text/html; charset=utf-8");
        
        $url="https://www.jinse.com/ajax/twitters/getList?flag=down&id=0";
        $allInfo=$this->curl_get_contents($url);
        
        $allInfo=json_decode($allInfo,true);
        
        $list=array_reverse($allInfo['data']);
        
        $twitterMod=D('Admin/Twitter');
        
        foreach($list as $val){
            
            $re=$twitterMod->getUniqueNum(array('unique_num'=>$val['id']));
            if($re){//已存在该记录  跳过
                continue;
            }
            
            $_POST['auth_avatar']=$val['user']['avatar'];
            $_POST['auth_name']=$val['user']['name'];
            $_POST['unique_num']=$val['id'];
            $_POST['time']=strtotime($val['published_at']);
            $_POST['content']=preg_replace("/<(\/?a.*?)>/si","",$val['content']);//过滤所有a标签
            $_POST['content_zh']=preg_replace("/<(\/?a.*?)>/si","",$val['chinese']);//过滤所有a标签
            $_POST['country']=$val['user']['country_name'];
            $_POST['type']=$val['user']['type_name'];
            $_POST['status']=1;
            $_POST['remark']=json_encode($val);
            
            $re=$twitterMod->saveData('add');
            if($re){
                echo $val['id'].'动态采集成功<br>';
                $status=1;
            }
            
        }
        if(!$status){
            echo '无新动态';
        }
    }
    
    /**
     *微博采集
     */
    public function weibo_collection(){
        header("Content-Type:text/html; charset=utf-8");
        
        $url="https://www.jinse.com/ajax/weibo/getList?flag=down&id=0";
        $allInfo=$this->curl_get_contents($url);
        
        $allInfo=json_decode($allInfo,true);
        
        $list=array_reverse($allInfo['data']);
        
        $weiboMod=D('Admin/Weibo');
        
        foreach($list as $val){
            
            $re=$weiboMod->getUniqueNum(array('unique_num'=>$val['id']));
            
            if($re){//已存在该记录  跳过
                continue;
            }
            
            $_POST['unique_num']=$val['id'];
            $_POST['auth_name']=$val['user']['name'];
            $_POST['auth_avatar']=$val['user']['avatar'];
            
            $_POST['content']=$val['content'];
            $_POST['time']=strtotime($val['published_at']);
            $_POST['status']=1;
            
            $re=$weiboMod->saveData('add');
            
            if($re){
                echo $val['id'].'微博采集成功<br>';
                $status=1;
            }
        }
        if(!$status){
            echo '无新微博';
        }
        
    }
    
    
    //采集快讯
    public function article_collection(){
        header("Content-Type:text/html; charset=utf-8");
        
        $url='https://api.jinse.com/v3/live/list?limit=2&flag=down&id=0';
        
        $allInfo=$this->curl_get_contents($url);
        
        $allInfo=json_decode($allInfo,true);
        
        $allList=array_reverse($allInfo['list']);
        
        $contentMod=D('Article/ContentArticle');
        
        $content2Mod=D('DuxCms/Content');
        
        foreach($allList as $val){
            $date=$val['date'];
            $list=array_reverse($val['lives']);
            
            foreach($list as $valb){
                $re=$content2Mod->getUniqueNum(array('unique_num'=>$valb['id']));
                if($re){//已存在该记录  跳过
                    continue;
                }
                
                $isjscj=stripos($valb['content'],'金色财经');
                
                $_POST['unique_num']=$valb['id'];
                $_POST['class_id']=5;
                $_POST['title']=$this->getNeedBetween($valb['content'],'【','】');
                $_POST['time']=date('Y/m/d H:i:s',$valb['created_at']);
                
                if($isjscj){//如果包含‘金色财经’,则需要后台审核
                    $_POST['status']=0;
                }else{
                    $_POST['status']=1;
                }
                
                $_POST['content']=$this->getNeedAfter($valb['content'],'】');
                
                $re=$contentMod->saveData('add');
                if($re){
                    echo $valb['id'].'快讯采集成功<br>';
                    $status=1;
                }
            }
        }
        if(!$status){
            echo '无新快讯';
        }
        
        
    }
    
    //获取两个字符间的 字符
    public function getNeedBetween($kw,$mark1,$mark2){
        
        $st =stripos($kw,$mark1);
        $ed =stripos($kw,$mark2);
        
        if(($ed==false)||$st>=$ed)
            return 0;
            $kw_re=substr($kw,($st),($ed-$st+3));
            return $kw_re;
    }
    
    //获取两个字符间的 字符
    public function getNeedAfter($kw,$mark){
        
        
        $kw_r=strstr($kw,$mark);
        $kw_re=substr($kw_r,'3');
        return $kw_re;
    }
    
}