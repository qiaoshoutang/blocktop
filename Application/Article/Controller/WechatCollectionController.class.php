<?php
namespace Article\Controller;
use Home\Controller\SiteController AS Controller;


class WechatCollectionController extends Controller {
    
    
    public function __construct() {
        parent::__construct ();
        
        header("Content-Type:text/html; charset=utf-8");
    }
    public function test(){
        dd('ge333ge');
    }
/******************* 文章采集 *********************/
    //定时任务 5分钟执行一次
    public function wechat_collection(){
        
        $wechatMod = M('wechat');
        
        $where['status'] = 1;
        $where['last_time'] = ['lt',time()-43200]; //每个公众号  半天采集一次
        $wechatInfo = $wechatMod->where($where)->order('last_time asc,id asc')->find();

        if(empty($wechatInfo)){
            echo '没有公众号需要采集';
            exit;
        }
        $wechatMod->where(['id'=>$wechatInfo['id']])->setField('last_time',time());
        
        $resArr = $this->wechat_collection_one($wechatInfo['name'],$wechatInfo['id'],true);
        
        if($resArr['code'] !=1){
            echo $wechatInfo['name'].'采集失败，原因是：'.$resArr['info'];
            exit;
        }
        echo $wechatInfo['name'].'采集成功';
        exit;
    }
    
    //用API采集公众号最新的一篇文章
    public function wechat_collection_one($wechat_name,$wechat_id,$cache='true'){
        
        $wechat_name=I('get.wechat_name',$wechat_name);
        
        if(!$wechat_name){
            $rdata['code']=0;
            $rdata['msg']='公众号名称不能为空';
            return $rdata;
        }
        
        if($cache){
            $res=S("wechat_name_".$wechat_name);
        }
        
        if($res){ 
            $resArr= json_decode($res,true);
        }else{  //无缓存  则请求接口
            $url = 'https://api.wangduoyun.com/?appid=9f9388f9b33eacf83dce549946aa2107&scan='.$wechat_name;
            
            $res = $this->curl_get_contents($url);


            $resArr= json_decode($res,true);
            if($resArr['error_code'] == 0){ //返回正常
                S("wechat_name_".$wechat_name,$res,3600);
            }else{
                return ['code'=>2,'info'=>'文章列表接口返回错误'];
            }
        }

        $articleInfo = $resArr['data']['articles'][0];

        if(empty($articleInfo)){
            return ['code'=>2,'info'=>'文章内容为空'];
        }
        $_POST = array();
        $_POST['image'] = $articleInfo['article_thumbnail'];
        $_POST['description'] = $articleInfo['article_brief'];
 
            

        if($cache){
            $res = S('wechat_article_'.$wechat_name);
        }else{
            $res = '';
        }
        
        if($res){
            $resArr = json_decode($res,true);
        }else{
            $article_url = urlencode($articleInfo['article_url']);
            $article_url = "https://api.wangduoyun.com/?appid=8e172beaf7b5c27eacd8daf8b1607522&url=".$article_url;
            
            $res =$this->curl_get_contents($article_url);

            $resArr = json_decode($res,true);
            if($resArr['error_code'] == 0){
                S('wechat_article_'.$wechat_name,$res,3600);
            }else{
                return ['code'=>2,'info'=>'文章详情接口返回错误'];
            }
        }

//         dd($resArr);
        $where = array();
        $where['source'] = 4;
        $where['author'] = $resArr['data']['weixin_nickname'];
        $where['title'] =$resArr['data']['article_title'];

        $article_num = M('content')->where($where)->count();
        if($article_num){
            return ['code'=>1,'info'=>'文章已存在'];
        }
        
        $content = str_replace("data-src","src",$resArr['data']['article_content']);
        
        $_POST['class_id'] = 5;
        $_POST['author'] = $resArr['data']['weixin_nickname'];
        $_POST['title'] =$resArr['data']['article_title'];
        $_POST['content']  = $content;
        $_POST['time']  =date('Y-m-d H:i:s',$resArr['data']['article_publish_time']);
        $_POST['status'] =2;
        $_POST['views'] =rand(5,20);
        $_POST['sequence'] =0;
        $_POST['source'] = 4;
//         dump($_POST);
//         exit;
        $articleMod = D('ContentArticle');
        $re=$articleMod->saveData('add'); //文章入库

        if(empty($re)){
            return ['code'=>3,'info'=>'文章入库失败'];
        }
        return ['code'=>1,'info'=>'文章采集成功'];
        
    }
	


	
	
	/**
	* 快速采集
	* 2017-7-26
*/
	public function q_cai($k2=0){
	    
		$k = $k2?$k2:I("get.keyw",'','trim');
		if(!$k) return false;
		
		$keywdb = M("Service_user_keyword");	
		$planMod=D('Home/ArticlePlan');
		$where['plan_media_id'] = $k;        
        $planInfo = $planMod->getOneInfo($where,'plan_id asc');
		if($planInfo){
			$keywdb->where("keyword_collection_wxh='".$k."'")->save(["keyword_collection_state_weixin"=>1,'keyword_updatetime'=>time()]);
			exit("已存在此公众号采集源");
		}
		unset($where);
		$url = 'https://api.shenjian.io/?appid=5a941459cd57570a2b2881ebe27eed52&weixinId='.$k;
		$res=S("res0726".$k);
		if(!$res){
			$res = $this->curlg($url);
			if($res)
		    S("res0726".$k,$res,3600);
		    $res= json_decode($res,true);
		}
		
		$platform_id=2;
		$data['name']=trim($res["data"]['weixin_nickname']);
		$data['description']=$res["data"]['weixin_brief'];
		$data['href']=md5($data['name'].$platform_id);
		$data['media_id']=$res["data"]['weixin_id'];
		$data['avatar_url']=$res["data"]['weixin_avatar'];
		
		dump($res);
		$re3 = $this->regandplan($data);
		if($re3){
			
		$where['keyword_collection_allow']=1;
		$where['keyword_name']=$data['name'];		
		$re4 = $keywdb->where($where)->find();
		if($re4){
			$keywdb->where($where)->save(["keyword_collection_state_weixin"=>1,'keyword_updatetime'=>time()]);
		}else{
			$data2['keyword_collection_state_weixin']=1;
			$data2['keyword_collection_allow']=1;
			$data2['keyword_name']=$data['name'];
			$data2['keyword_addtime']=time();
			$data2['keyword_updatetime']=time();
			$keywdb->add($data2);
		}
		
		echo '入库成功,加入采集计划成功！';
		exit;
		}
		dump($re3);
		echo '入库失败，采集失败！';
	}
	
/**   新用户注册+添加计划表
	* @param undefined $val
	* @param undefined $user_type  3头条  2公众号
	* 
    */
	private function regandplan($val,$user_type=2){

	    if(!$val) {
	        return false;		
	    }
	    $name = $val['name'];
		$description = $val['description'];
		$platform_uid = $val['href'];
		$media_id = $val['media_id'];

		$userMod=D('Common/ServiceUser');
        $password='pt'.(substr(md5(time()), 0, 16)); 
		$unique_id = md5($name.$user_type);
		
		$result=$this->add_plan_db($user_type,$unique_id,$platform_uid, $name,$media_id);
		
		$imgid = $this->getAvatar($val['avatar_url']);


	  $uinfo = $userMod->createAccount($unique_id,$password,$name,'',$user_type,$unique_id,$description,$imgid,2);
	  
	  if(!$uinfo && $result){
	  	 $planMod=D('Home/ArticlePlan');
		 $planMod->del($result);
	  	 $this->log('注册失败删除 plan');
	  }

	  if($uinfo){
	      return true;
	  }else{
	      return false;
	  }
	}
	
	//添加到采集计划表
	public function add_plan_db($platform_id,$user_unique,$platform_uid,$name,$media_id=0){
	    
	    $planMod=D('Home/ArticlePlan');
	    
	    $map['plan_user_unique']=$user_unique;
	    $result=$planMod->getOneInfo($map);
	    
		if(!$result['plan_media_id'])
		$planMod->where($map)->setField("plan_media_id",$media_id);
		$planMod->where($map)->setField("plan_status",1);
		
	    if($result){
	        echo '已存在'.$name.'的采集计划<br>';
			
	        return false;
	    }
	    
	    $data['plan_platform_id'] = $platform_id;
	    $data['plan_user_unique'] = $user_unique;
	    $data['plan_platform_uid'] = $platform_uid;
	    $data['plan_user_name'] = $name;
	    $data['plan_media_id'] = $media_id;
	    $data['plan_update'] = time();
	    $data['plan_status'] = 1;
	    
	    $re=$planMod->saveData($data,'add');
	    
	    if($re){

	        return $re;
	    }else{
	        
	        return false;
	    }
	}
	


    
  /************************************************************************************************************************************/
    

     //定时执行采集计划接口
    //如果有待采集文章，则先采集文章   没有待采集文章，则执行  可以执行的采集计划  api采集
    //
    public function do_collection_plan2(){

        
        $type = 2;
        
        S('close_wechat',1,rand(1,3));//随机关闭时间
     

		//优先采集文章
		$collection_re = $this->collection_article();
// 		dd($collection_re);
		if($collection_re){ //执行了采集
		    echo '<br>采集文章结果：<br>';
		    dump($collection_re);
		    exit;
		}
		

		
        $planMod=D('Home/ArticlePlan');

        if(S('wchat_collection_plan_id')){ //如果上一个采集计划有采集到内容  则对内容的缩略图  进行过滤处理
            $plan_id = S('wchat_collection_plan_id');
            $planInfo=$planMod->getOneInfo(array('plan_id'=>$plan_id),'',array('plan_id','plan_user_unique','plan_userid'));
            if($planInfo){
                $res = $this->operationArticle($planInfo);
            }

            S('wchat_collection_plan_id',null);
            return ;
        }
        
        
        $where['plan_platform_id']=$type;
        $where['plan_status']=1;

        $where['plan_update']=array('lt',time());
       
        $planInfo=$planMod->getOneInfo($where,'plan_update asc');
        if($planInfo){
            
            if(($planInfo['plan_userid'] == 0) &&($planInfo['plan_user_unique'])){
                
                $userInfo = M('service_user')->where(array('user_unique_id'=>$planInfo['plan_user_unique']))->field('user_id')->find();
                if($userInfo['user_id']){
                    $planMod->where(array('plan_id'=>$planInfo['plan_id']))->setField('plan_userid',$userInfo['user_id']);
                }
            }
            echo '执行采集计划：';
            
            dump($planInfo);
            $planMod->where(array('plan_id'=>$planInfo['plan_id']))->setInc('plan_update',3600);//先增加下次采集时间，防止多台服务器执行相同的采集计划
            $result=$this->collection_plan2($planInfo['plan_id']);
            //如果执行了一个微信采集计划，则关闭采集30~60秒
            S('close_wechat',1,rand(3,6));                         //随机关闭时间

        }else{
            $result['code']=0;
            $result['msg'] = '没有待执行的采集计划';
        }

       dump($result);
        
    }
   
	
	

	
	
	
	
    //采集公众号 单个用户文章标题 链接 到待采集表  用api
    public function collection_user_wechat2($apidata,$uid,$plan_id){

       
        $platform_type=2; //平台id
       
        $pendingMod=D('Home/ArticlePending');

      
        $articleMod = D('Common/ServiceArticleCollection');
        $articleArr = array();
        foreach($apidata['data']['articles'] as $val){
            
            $title_exist_num = $pendingMod->where(array('pending_title_md5'=>md5(htmlspecialchars($val['article_title']))))->count();
            if($title_exist_num>=2){  //该标题文章已存在两篇以上
                logs('已存在两篇该标题文章');
                continue;
            }
            $unique=createArticleUnique($val['article_title'],$uid);
            if(!$unique){
                logs('微信采集单篇文章采集，生成唯一标识失败');
                continue;
            }
            
            $exist=$pendingMod->isUnique($unique);
            
            if($exist){  //待采集文章列表中已存在该文章  跳过
                continue;
            }
          
            $data['pending_plan_id']=$plan_id;
            $data['pending_uid']=$uid;
            $data['pending_unique'] = $unique;
            $data['pending_platform_type'] = $platform_type;
            $data['pending_title'] = $val['article_title'];
            $data['pending_title_md5'] = md5($val['article_title']);
            $data['pending_href'] = $val['article_url'];
            $data['pending_status'] = 0;
            $data['pending_publish_time'] = $val['article_publish_time']?$val['article_publish_time']:time();
            $data['pending_time'] = time();
            $data['pending_cover'] = 0;

            $re=$pendingMod->saveData($data,'add');

            if($re){
                $articleArr[]=$re;
            }
        }
        
        return $articleArr;
        exit;
    }
	
 
    //单篇文章采集
    public function collection_article(){
        
        $pendingMod=D('Home/ArticlePending');
        
        $where['pending_status']=0;
        $where['pending_platform_type']=2;
        
        $pendingInfo=$pendingMod->getOneInfo($where);

        if(!$pendingInfo){ //没有待采集文章  返回空，继续执行采集计划
            return false;
        }
        $pending_id=$pendingInfo['pending_id'];
//         $pending_id=59426;
//         M()->startTrans();
        
        if(!$pending_id){
            $rdata['code'] = 0;
            $rdata['error'] ='待采集文章id 不能未空';
            
            return $rdata;
        }
        $planMod = M('service_article_plan');
        $planInfo = $planMod->where(array('plan_id'=>$pendingInfo['pending_plan_id']))->field('plan_collection_num')->find();
        
        if(($pendingInfo['pending_publish_time']<=time()-86400*7)&&($planInfo['plan_collection_num']>10)){
			 $rdata['code'] = 0;
            $rdata['error'] ='文章发布时间超过一周paddingid:'.$pendingInfo['pending_id'];
            $pendingMod->where("pending_id=".$pendingInfo['pending_id'])->setField("pending_status",4);
            return $rdata;
		}
		
        $pendingMod->where(array('pending_id'=>$pending_id))->setField('pending_status',3);//置状态-采集中

        echo '采集的文章是：';
        var_dump($pendingInfo['pending_title']);
        $unique = $pendingInfo['pending_unique'];
        $title_md5 = $pendingInfo['pending_title_md5'];

        $article_collection=D('Home/ArticleCollection');
        
        
        $title_exist_num = $article_collection->where(array('collection_title_md5'=>$title_md5))->count();
        if($title_exist_num >= 2){  //该标题文章已存在两篇以上
            $data1['pending_id']=$pending_id;
            $data1['pending_status']=8;
            $pendingMod->saveData($data1,'edit');
            
            $rdata['code'] = 0;
            $rdata['error'] ='该标题文章已经存在两篇，请勿重复添加';
            
            return $rdata;
        }
        
        $exist=$article_collection->isUnique($unique);
        echo '<br>是否已存在：';
        var_dump($exist);
        if($exist){

			$data1['pending_id']=$pending_id;
            $data1['pending_status']=8;
			$pendingMod->saveData($data1,'edit');
			
            $rdata['code'] = 0;
            $rdata['error'] ='文章已存在$pending_id:'.$pending_id;
        
            return $rdata;
        }


        $result=$this->collection_article_wechat($pendingInfo['pending_href'],$pendingInfo['pending_uid'],$pendingInfo['pending_publish_time']);

        if($result['code']==1){
            $data['pending_id']=$pendingInfo['pending_id'];
            $data['pending_status']=1;

            $planMod->where(array('plan_id'=>$pendingInfo['pending_plan_id']))->setInc('plan_collection_num'); //采集计划中 采集文章数+1
			
			//M('service_article_plan')->where(array('plan_id'=>$pendingInfo['pending_plan_id']))->setField('plan_userid',$pendingInfo['pending_uid']);
            M('service_user')->where(array('user_id'=>$pendingInfo['pending_uid']))->save(['user_state'=>1,'user_article_num'=>array('exp','user_article_num+1')]); 
        
        }elseif($result['code']==9){ //采集类型外 内容
            $data['pending_id']=$pendingInfo['pending_id'];
            $data['pending_status']=9;
        }else{
            $data['pending_id']=$pendingInfo['pending_id'];
            $data['pending_status']=2;
            
        }
        $re=$pendingMod->saveData($data,'edit');

        return $result;
  
    }
    
    
    //公众号单篇文章采集
    public function collection_article_wechat($url,$userid,$publish_time){
        
        $data=$this->autowxcj2($url);  

        if($data['error']){ //采集写入有错误  则返回
            
            $rdata['code']=0;
            $rdata['error']=$data['error'];
            return $rdata;
        }
        if(empty($data['title'])){
            $rdata['code']=9;
            $rdata['msg']='文章标题为空';
            return $rdata;
        }
        
        $articleMod = D('Common/ServiceArticleCollection');

        $title = trim($data['title']);
        $body = trim($data['body']);

       
        if(empty($body)){
            $rdata['code']=0;
            $rdata['msg']='文章内容为空';
            return $rdata;
        }

        $status = $articleMod->createArticle($title,$body,$userid, $state = 1,$publish_time,null,true);

        if($status){
            if($data['original'] == '是'){
                $original = 1;
            }else{
                $original = 2;
            }
            $articleMod->where(array('collection_id'=>$status))->setField('collection_original',$original);
            
            $rdata['code'] =1;
            $rdata['msg']  ='文章采集成功:'.$title;
            $rdata['data'] =$status;
        }else{
            $rdata['code']=0;
            $sql= M()->_sql();
            $rdata['msg'] ='文章保存失败';
            $rdata['data'] =$sql;
        }
        
        return $rdata;
    }
	

    
}


