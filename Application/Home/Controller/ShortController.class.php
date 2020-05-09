<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 站点首页
 */

class ShortController extends SiteController {

	
	
	public function collection(){
	    $this->article_collection();
	}
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
        $where_a['state']=2;

	    $messageMod=D('Article/Message');

	    $count = $messageMod->countList($where_a);
// 	    dd($count);
	    $limit = $this->getPageLimit($count,20);

	    $article_list = $messageMod->loadList($where_a,$limit);
	    $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
	    
	    foreach($article_list as $key=>$val){  
// 	        $article_title=str_replace('【','', $val['title']);
// 	        $article_title=str_replace('】','', $article_title);
	        $article_list[$key]['title']=$val['title'];
	        $article_list[$key]['content']=html_out($val['content']);
	        $article_list[$key]['time_top']=date('m月d日',$val['time']).' '.$weekname[date('w',$val['time'])];
	    }
	    

	    
	    
	    $this->assign('page_num','1');

	    $this->assign('article_list',$article_list);
	    $this->assign('twitter_list',$twitter_list);
	    $this->assign('weibo_list',$weibo_list);
	    
	    $this->assign('info',$info);
        $this -> siteDisplay('message');
	}
	
	//预览版 快讯
	public function messagePreview(){
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
	    
	    
	    //快讯列表  草稿
	    $where_a['A.status']=0;
	    $where_a['A.class_id']=5;
	    $contentMod=D('Article/ContentArticle');
	    $count = $contentMod->countList($where_a);
	    $limit = $this->getPageLimit($count,20);
	    
	    $article_list = $contentMod->loadList($where_a,$limit);
	    $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
	    
	    foreach($article_list as $key=>$val){
// 	        $article_title=str_replace('【','', $val['title']);
// 	        $article_title=str_replace('】','', $article_title);
	        $article_list[$key]['title']=$val['title'];
	        $article_list[$key]['content']=html_out($val['content']);
	        $article_list[$key]['time_top']=date('m月d日',$val['time']).' '.$weekname[date('w',$val['time'])];
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
	    $this -> siteDisplay('message_preview');
	}
	
/**
	 * 
	 * 
	 * @return string|boolean|array
	 */
	public function ajax_add(){
	    
	    

	    $page_num=I('request.p',0,'intval');

	    $_GET['p']=$page_num+1;
	    
	    $where['state'] = 2;
	    $messageMod=D('Article/Message');
	    $count = $messageMod->countList($where);
	    $limit = $this->getPageLimit($count,20);
	    
	    $article_list = $messageMod->loadList($where,$limit);
	    $weekname=array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
	    foreach($article_list as $key=>$val){
	        $article_list[$key]['content']=html_out($val['content']);
	        $article_list[$key]['time_top']=date('m.d',$val['time']).' '.$weekname[date('w',$val['time'])];
	    }

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
	    
	}

	//采集快讯
	public function article_collection(){

	    header("Content-Type:text/html; charset=utf-8");
	    
	    $url='https://api.jinse.com/v3/live/list?limit=25&flag=down&id=0';
	    
	    $allInfo=$this->curl_get_contents($url);
	    
	    $allInfo=json_decode($allInfo,true);

	    $allList=array_reverse($allInfo['list']);
// 	    dd($allList);
	    $messageMod=D('Article/Message');
	    
	    
	    foreach($allList as $val){
	        $date=$val['date'];
	        $list=array_reverse($val['lives']);
	        
	        foreach($list as $valb){
	            if($valb['content']==' '){
	                continue;
	            }
	            $re=$messageMod->getUniqueNum(array('unique_num'=>$valb['id']));
	            if($re){//已存在该记录  跳过
	                continue;
	            }
	            
	            $isjscj=stripos($valb['content'],'金色财经');
	            $title_temp =explode('|',$this->getNeedBetween($valb['content'],'【','】'));
	            $_POST['unique_num']=$valb['id'];
	            $_POST['class_id']=5;
	            $_POST['title']=$title_temp[1]?$title_temp[1]:$title_temp[0];
	            $_POST['time']=date('Y/m/d H:i:s',$valb['created_at']);
	            $_POST['up']=rand(30,50);
	            $_POST['down']=rand(1,10);
	            
	            
	            if($isjscj){//如果包含‘金色财经’,则需要后台审核
	                $_POST['state']=1;
	            }else{
	                $_POST['state']=2;
	            }
	            
	            $_POST['content']=$this->getNeedAfter($valb['content'],'】');
	            if(!$_POST['content']){
	                continue;
	            }

	            $re=$messageMod->saveData('add');
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
	    
	    if(($ed==false)||$st>=$ed){
	        return 0;
	    }
	        
        $kw_re=substr($kw,($st+3),($ed-$st-3));
        return $kw_re;
	}
	
	//获取两个字符间的 字符
	public function getNeedAfter($kw,$mark){
	    
	    
	    $kw_r=strstr($kw,$mark);
	    $kw_re=substr($kw_r,'3');
	    return $kw_re;
	}
	
    
}