<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 站点首页
 */

class IndexController extends SiteController {
    
    
    public function __construct() {

        parent::__construct ();
        header("Content-Type:text/html; charset=utf-8");
        $detect = new \Common\Util\Mobile_Detect();
        $ishttps = 0;

        if($_SERVER['REQUEST_SCHEME']=='https'){
            $ishttps=1;
        }
            
        if ($detect->isMobile()){           
            if($ishttps){
                redirect('https://'.$_SERVER['HTTP_HOST'].'/home_m');
            }else{
                redirect('http://'.$_SERVER['HTTP_HOST'].'/home_m');
            }
        } 
        
        $cateList = M('navi_category')->where(['state'=>1])->select();
        $this->assign('cateList',$cateList);
        
    }

    //首页
    public function index(){
        $this->redirect('/news/all');
        $this -> siteDisplay('index');
    }
    
    //关于我们  简介
    public function description(){
        $this -> siteDisplay('description');
    }
    
    //关于我们  团队
    public function aboutUs(){
        
        $this -> siteDisplay('aboutus');
    }
    
    //关于我们  联系我们
    public function contactUs(){
        
        $this -> siteDisplay('contactus');
    }
    
    //快讯
    public function shortMessage(){
        
        //快讯
        $where['state'] = 2;
        $messageMod = D('Article/Message');
       
        $messageCount = $messageMod->countList($where);
        $limit = $this->getPageLimit($messageCount,10);
        $messageList = $messageMod->loadList($where,$limit);
        
        //热门新闻
        $newsList = M('content')->where(['status'=>2])->field('content_id,title,image,time')->limit('0,5')->order('sequence desc,content_id desc')->select();
        
        if($newsList){
            $newsFirst = array_shift($newsList);
            $this->assign('newsFirst',$newsFirst);
        }

        //推荐导航
        $naviList = D('Admin/Navi')->loadList(['recom'=>1],'0,5');
        
        
        $this->assign('newsList',$newsList);
        $this->assign('messageList',$messageList);
        $this->assign('naviList',$naviList);
        $this -> siteDisplay('shortmessage');
    }
    //新闻动态
    public function news(){
        
        $class_id = I('request.class_id','all');

        $where['A.status'] = 2;
        if($class_id != 'all'){
            $where['A.class_id'] = $class_id;
        }
        //热门新闻
        $newsList =D('Article/ContentArticle')
                   ->loadList($where,'content_id,title,description,image,time,views,author,author_id,U.nickname as author_name',10,'is_top desc,time desc,sequence desc');

        foreach($newsList as $key=>$val){
            $newsList[$key]['description'] = html_out($val['description']);
        }
        //轮播列表
        $bannerList = M('banner')->where(['state'=>1])->order('sequence desc')->select();
        
        //快讯
        $map['state'] = 2;
        $messageMod = D('Article/Message');
        
        $messageList = $messageMod->loadList($map,'0,3');
        
        //推荐导航
        $naviList = D('Admin/Navi')->loadList(['recom'=>1],'0,6');
        
        // 专栏列表
        $columnMod = D('Admin/Column');
        $columnList  = $columnMod->where(['state'=>1])->order('order_id desc')->limit(10)->select();

        $this->assign('class_id',$class_id);
        $this->assign('newsCate',M('category')->where(['show'=>1])->order('sequence asc')->select());
        $this->assign('newsList',$newsList);
        $this->assign('bannerList',$bannerList);
        $this->assign('messageList',$messageList);
        $this->assign('columnList',$columnList);
        $this->assign('naviList',$naviList);
        $this -> siteDisplay('news');
    }
    
    //新闻详情
    public function newsContent(){
        
        $content_id = I('request.content_id',0);
        
        $contentMod = D('Article/ContentArticle');
        
        M('content')->where(['content_id'=>$content_id])->setInc('views',rand(5,10)); //浏览自增1
        
        $contentInfo = $contentMod->getInfo($content_id);
//         dump($contentInfo);
//         exit;
        $contentInfo['content'] = html_out($contentInfo['content']);
        
        //如果用户登录  添加浏览历史
        $home_user = session('home_user');

        if($home_user){
            $historMod = M('history');
            $hdata['user_id'] = $home_user['user_id'];
            $hdata['article_id'] = $content_id;
            $exist = $historMod->where($hdata)->field('id')->find();
            if($exist){ //浏览记录已存在   更新浏览时间
                $historMod->save(['id'=>$exist['id'],'time'=>time()]);
            }else{      //无浏览记录 则新增记录
                $hdata['time'] = time();
                $historMod->add($hdata);
            }
        }

        //热门新闻
        $newsList = M('content')->where(['status'=>2])->field('content_id,title,description,image,time,views')->limit(6)
                    ->order('sequence desc,content_id desc')->select();
        if($newsList){
            $newsFirst = array_shift($newsList);
            $this->assign('newsFirst',$newsFirst);
        }        
        
        //快讯
        $map['state'] = 2;
        $messageMod = D('Article/Message');
        $messageList = $messageMod->loadList($map,3);
        
        //推荐导航
        $naviList = D('Admin/Navi')->loadList(['recom'=>1],'0,5');
        
        $this->assign('newsList',$newsList);
        $this->assign('messageList',$messageList);
        $this->assign('naviList',$naviList);
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
            
            $where['class_id'] = 1;
            $naviList1 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 2;
            $naviList2 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 3;
            $naviList3 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 4;
            $naviList4 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 5;
            $naviList5 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 6;
            $naviList6 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 7;
            $naviList7 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
            $where['class_id'] = 8;
            $naviList8 = $naviMod->where($where)->limit(0,20)->order('order_id desc,id desc')->select();
            
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
        
        $this->assign('class_id',$class_id);
        $this -> siteDisplay('antmap');
    }
    
    //活动
    public function activity(){
        $state = I('request.state','all');
        $time = I('request.time','all');

        if($state != 'all'){
            $where['state'] = $state;
        }
        if($time != 'all'){
            switch ($time){
                case 1:{
                    $where['time'] = array('gt',strtotime('-7 days'));
                    break;
                }
                case 2:{
                    $where['time'] = array('gt',strtotime('-30 days'));
                    break;
                }
                case 3:{
                    $where['time'] = array('gt',strtotime('-183 days'));
                    break;
                }
                case 4:{
                    $where['time'] = array('lt',strtotime('-183 days'));
                    break;
                }
            }
        }
        
        $activityMod = D('Admin/Activity');
        
        $acitvityList = $activityMod->loadList($where,9,'order_id desc,id desc');
        $this->assign('state',$state);
        $this->assign('time',$time);
        $this->assign('acitvityList',$acitvityList);
        $this -> siteDisplay('activity');
    }
    
    //活动详情
    public function activityContent(){
        $content_id = I('request.content_id',0);
        
        $activityMod = M('activity');
        
        $activityMod->where(['id'=>$content_id])->setInc('views'); //浏览自增1
        
        $activityInfo = $activityMod->where(['id'=>$content_id])->find();
        $activityInfo['content'] = html_out($activityInfo['content']);
        
        
        //推荐导航
        $naviList = D('Admin/Navi')->loadList(['recom'=>1],'0,5');
        
        $this->assign('contentInfo',$activityInfo);
        $this->assign('naviList',$naviList);
        $this ->siteDisplay('activityContent');
    }

    //搜索
    public function searchPage(){
        $keyword =  I('request.keyword','');

        $contentMod = M('content');
        $messageMod = M('message');
        
        //搜索内容
        $newsList = $contentMod->where(['status'=>2,'title'=>['like','%'.$keyword.'%']])->order('sequence desc,content_id desc')->select();
        $messageList = $messageMod->where(['state'=>2,'title'=>['like','%'.$keyword.'%']])->order('id desc')->select();
        $activityList = M('activity')->where(['name'=>['like','%'.$keyword.'%']])->order('id desc')->select();

        //推荐导航
        $naviList = D('Admin/Navi')->loadList(['recom'=>1],'0,5');
        
        //最新快讯
        $messageList2 = $messageMod->where(['state'=>2])->limit(0,3)->order('id desc')->select();
        
        //热门新闻
        $newsList2 = $contentMod->where(['status'=>2])->limit(0,5)->order('sequence desc,content_id desc')->select();
        if($newsList2){
            $newsFirst = array_shift($newsList2);
            $this->assign('newsFirst',$newsFirst);
        }
        
        $this->assign('keyword',$keyword);
        $this->assign('newsList',$newsList);
        $this->assign('newsList2',$newsList2);
        $this->assign('messageList',$messageList);
        $this->assign('messageList2',$messageList2);
        $this->assign('activityList',$activityList);
        $this->assign('naviList',$naviList);
        $this ->siteDisplay('searchPage');
        
    }
    
    //申请收录
    public function apply(){
        $list = M('navi_category')->where(['state'=>1])->select();
        $classList = [];
        foreach($list as $val){
            $classList[$val['id']] = $val['name'];
        }

        $this->assign('classList',$classList);
        $this ->siteDisplay('apply');
    }

    //专栏
    public function column(){
        $id = I('request.id','');
        $columnMod = D('Admin/Column');
        
        if($id){
            $info = $columnMod->getInfo($id);
        }else{
            
            $info = $columnMod->where(['state'=>1])->order('order_id desc')->find();
        }
        
        //新闻列表

        $newsList =D('Article/ContentArticle')
                    ->loadList(['A.column_id'=>$info['id']],'content_id,title,description,image,time,views,author,author_id,U.nickname as author_name',10,'time desc,sequence desc');
        
        foreach($newsList as $key=>$val){
            $newsList[$key]['description'] = html_out($val['description']);
            $newsList[$key]['time'] = format_time($val['time'],2);
        }
        // 专栏列表
        $columnList  = $columnMod->where(['state'=>1])->order('order_id desc')->limit(10)->select();
        
        $this->assign('info',$info);
        $this->assign('newsList',$newsList);
        $this->assign('columnList',$columnList);
        $this -> siteDisplay('specialColumn');
    }
    
    /**********************************************************************/
    
    //采集
    public function collection(){
        $this->article_collection();
    }
    
    //快讯
    public function message(){
        header("Content-Type:text/html; charset=utf-8");
        
        $gzh_num=I('request.gzh_num',1,'intval');
        $info=array();
        
        switch ($gzh_num){  //海报默认二维码
            
            case 1 : {
                $info['gzh_code']=C('qr_code_a');
                break;
            }
            case 2 : {
                $info['gzh_code']=C('qr_code_b');
                break;
            }
            case 3 : {
                $info['gzh_code']=C('qr_code_c');
                break;
            }
        }
        
        
        //快讯列表
        $where_a['A.status']=1;
        $where_a['A.class_id']=5;
        $contentMod=D('Article/ContentArticle');
        $count = $contentMod->countList($where_a);
        $limit = $this->getPageLimit($count,20);
        
        $article_list = $contentMod->loadList($where_a,$limit);
        $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
        
        foreach($article_list as $key=>$val){
            $article_list[$key]['content']=html_out($val['content']);
            $article_list[$key]['time_top']=date('m.d',$val['time']).' '.$weekname[date('w',$val['time'])];
        }
        
        //动态列表
        $where_t['status']=1;
        $twitterMod=D('Admin/Twitter');
        $count = $twitterMod->countList($where_t);
        $limit = $this->getPageLimit($count,20);
        
        $twitter_list = $twitterMod->loadList($where_t,$limit);
        
        foreach($twitter_list as $key=>$val){
            $twitter_list[$key]['content']=html_out($val['content']);
        }
        
        //微博列表
        $where_w['status']=1;
        $weiboMod=D('Admin/Weibo');
        $count = $weiboMod->countList($where_w);
        $limit = $this->getPageLimit($count,20);
        
        $weibo_list = $weiboMod->loadList($where_w,$limit);
        foreach($weibo_list as $key=>$val){
            $weibo_list[$key]['content']=html_out($val['content']);
        }
        
        
        $this->assign('page_num','1');
        
        $this->assign('article_list',$article_list);
        $this->assign('twitter_list',$twitter_list);
        $this->assign('weibo_list',$weibo_list);
        
        $this->assign('info',$info);
        $this -> siteDisplay('message');
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