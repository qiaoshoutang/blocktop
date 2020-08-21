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
        
        $cateList = M('navi_category')->where(['state'=>1])->select();
        $this->assign('cateList',$cateList);

        $tiket = A('Home/Wechat')->set_signature();
//         dd($tiket);

        C('TPL_NAME','mobileNew');
    }
    
    //首页
    public function index(){
//         dd( C('TPL_NAME'));
        $class_id = I('request.class_id','all');
        
        $where['A.status'] = 2;
        if($class_id != 'all'){
            $where['A.class_id'] = $class_id;
        }
       
        //热门新闻
        $newsList =D('Article/ContentArticle')
        ->loadList($where,'content_id,title,description,image,time,views,author,author_id,look,U.nickname as author_name','0,10','is_top desc,time desc,sequence desc');
        
        foreach($newsList as $key=>$val){
            $newsList[$key]['description'] = html_out($val['description']);
        }
//         $number = count($newsList);
//         for($i=0;$i<$number-1;$i++){   //新闻列表根据浏览量 降序排列
//             for($j=0;$j<$number-$i-1;$j++){
//                 if($newsList[$j]['views']<$newsList[$j+1]['views']){
//                     $temp = $newsList[$j];
//                     $newsList[$j] = $newsList[$j+1];
//                     $newsList[$j+1] = $temp;
//                 }
//             }
//         }
        
        //轮播列表
        $bannerList = M('banner')->where(['state'=>1,'position'=>1])->order('sequence desc')->select();

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
        $this->assign('newsCate',M('category')->where(['show'=>1])->order('sequence desc')->select());
        $this->assign('newsList',$newsList);
        $this->assign('bannerList',$bannerList);
        $this->assign('messageList',$messageList);
        $this->assign('columnList',$columnList);
        $this->assign('naviList',$naviList);
        $this->assign('navi_id',1);
        
        $this -> siteDisplay('index');
    }
    
    //头条导航
    public function topMap(){
        
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
        $this->assign('navi_id',4);
        $this -> siteDisplay('topmap');
    }
    
    //快讯列表
    public function message(){
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
        $this->assign('navi_id',2);
        $this -> siteDisplay('message');
    }
    
    //头条专栏列表
    public function column_list(){
        

        $columnMod = D('Admin/Column');
        // 专栏列表
        $columnList  = $columnMod->where(['state'=>1])->order('order_id desc')->limit(10)->select();
        

        $this->assign('columnList',$columnList);
        $this->assign('navi_id',3);
        $this -> siteDisplay('columnList');

    }
    //头条专栏
    public function column(){
        
        $id = I('request.id','');
        $columnMod = D('Admin/Column');
        
        if($id){
            $info = $columnMod->getInfo($id);
        }else{
            
            $info = $columnMod->where(['state'=>1])->order('order_id desc')->find();
        }
        
        if(empty($info)){
            $this->error('找不到该栏目信息');
        }
        //文章类型栏目
        if($info['type'] == 1){
            //新闻列表
            $newsList =D('Article/ContentArticle')
            ->loadList(['A.column_id'=>$info['id']],'content_id,title,description,image,time,views,author,author_id,U.nickname as author_name','0,10','sequence desc,time desc');
            
            foreach($newsList as $key=>$val){
                $newsList[$key]['description'] = html_out($val['description']);
                $newsList[$key]['time'] = format_time($val['time'],2);
                $newsList[$key]['url'] = '/newsContent_m/1/'.$val['content_id'];
            }

            $this->assign('info',$info);
            $this->assign('newsList',$newsList);
            $this -> siteDisplay('column');
        }
        //视频类型栏目
        if($info['type'] == 2){
            
            $newsList =D('Article/Video')
            ->loadList(['A.column_id'=>$info['id']],'A.id as content_id,title,A.desc,image,time,views,author,author_id,U.nickname as author_name','0,10','sequence desc,time desc');
            
            foreach($newsList as $key=>$val){
                $newsList[$key]['description'] = html_out($val['desc']);
                $newsList[$key]['time'] = format_time($val['time'],2);
                $newsList[$key]['url'] = '/newsContent_m/2/'.$val['content_id'];
            }
            
            $this->assign('info',$info);
            $this->assign('newsList',$newsList);
            $this->assign('navi_id',3);
            $this -> siteDisplay('column');
        }
    }
    
    //关于我们
    public function aboutus(){
        $this -> siteDisplay('aboutus');
    }

    
    //新闻详情
    public function newsContent(){
        
        $type = I('request.type',1);
        $content_id = I('request.content_id',0);
        
        if(empty($type)){
            $this->error('参数不能为空');
        }
        if($type == 1){ //文章类型
            $contentMod = D('Article/ContentArticle');
            
            M('content')->where(['content_id'=>$content_id])->setInc('views',rand(5,10)); //浏览自增
            $contentInfo = $contentMod->getInfo($content_id);
            $contentInfo['content'] = html_out($contentInfo['content']);
        }
        if($type == 2){ //视频类型
            $contentMod = D('Article/Video');
            
            M('video')->where(['id'=>$content_id])->setInc('views',rand(5,10)); //浏览自增
            $contentInfo = $contentMod->getInfo($content_id);
        }
        
        if($contentInfo['image']&&(strpos($contentInfo['image'],'http') === false)){  //检验缩略图 是否全路径
            if($_SERVER['HTTPS']=='on'){
                $contentInfo['image'] = 'https://'.$_SERVER['HTTP_HOST'].$contentInfo['image'];
            }else{
                $contentInfo['image'] = 'http://'.$_SERVER['HTTP_HOST'].$contentInfo['image'];
            }
        }
//         dd($contentInfo);
        //如果用户登录  添加浏览历史
        $home_user = session('home_user');
        
        if($home_user){
            $historMod = M('history');
            $hdata['user_id'] = $home_user['user_id'];
            $hdata['article_id'] = $content_id;
            $hdata['type'] = $type;
            $exist = $historMod->where($hdata)->field('id')->find();
            if($exist){ //浏览记录已存在   更新浏览时间
                $historMod->save(['id'=>$exist['id'],'time'=>time()]);
            }else{      //无浏览记录 则新增记录
                $hdata['time'] = time();
                $historMod->add($hdata);
            }
        }
        
        $this->assign('contentInfo',$contentInfo);
        $this->assign('type',$type);
        $this -> siteDisplay('newsContent');
    }
    
    
}