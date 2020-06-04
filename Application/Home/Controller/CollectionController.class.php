<?php
namespace Home\Controller;
use Home\Controller\SiteController;
use Think\AipSpeech;
/**
 * 爬虫采集
 */

class CollectionController extends SiteController {

	
	
	public function collection(){
	    $this->jinse_collection();  //金色 财经文章采集
	    $this->huoxing_collection();  //火星 财经文章采集
	}


	//火星财经 采集文章
	public function huoxing_collection(){

	    header("Content-Type:text/html; charset=utf-8"); 

	    $base_path = 'https://www.huoxing24.com';
	    
	    $json_param =  get_huoxing_sign();
	    $href = $base_path.'/info/news/shownews';
	    
	    $header = array('Sign-Param:'.$json_param);
	    $listInfo=$this->curl_post($href,$header,['pageSize'=>5]);
	    
	    $listArr = json_decode($listInfo,true);
	    if($listArr['code'] != 1){
	        echo '接口返回错误';
	        exit;
	    }
// 	    dd($listArr['obj']['inforList']);
	    $is_collect = false;
	    foreach($listArr['obj']['inforList'] as $article){
// 	        dd($article);
	        $info = M('content')->where(['source'=>2,'unique_num'=>substr($article['id'],4,10)])->field('content_id')->find(); //文章是否已存在
	        if($info){
	            continue;
	        }
	        $info = M('content')->where(['title'=>$article['title']])->field('content_id')->find(); //文章是否已存在
	        if($info){
	            continue;
	        }
	        
	        $json_param = get_huoxing_sign();
	        
	        $header = array('Sign-Param:'.$json_param);
	        $cover = json_decode($article['coverPic'],true);

	        $articleInfo = $this->curl_post($base_path.'/info/news/getbyid',$header,['id'=>$article['id']]);
	        $articleArr = json_decode($articleInfo,true);
	        if($articleArr['code'] != 1){
	            echo '获取文章id='.$article['id'].'的详情失败<br>'; 
	        }
	        
	        $publishTime = date('Y-m-d H:i:s',$article['publishTime']/1000);

	        $_POST = array('class_id'=>5,'title'=>$article['title'],'description'=>$article['synopsis'],'content'=>$articleArr['obj']['current']['content'],
	            'author'=>$article['author'],'image'=>$cover['pc'],'time'=>$publishTime,'unique_num'=>substr($article['id'],4,10),
	            'views'=>rand(60,120),'status'=>2,'source'=>2);
	        

	        $content_id=D('Article/ContentArticle')->saveData('add');

	        echo '采集文章成功--'.$article['id'].'<br>';
	        $is_collect = true;
	    }
	    if($is_collect){
	        echo '采集完成';
	    }else{
	        echo '没有待采集的最新文章';
	    }
	    
	}

	//金色财经 采集文章
	public function jinse_collection(){

	    header("Content-Type:text/html; charset=utf-8"); 

	    
	    $accessKey = '871c11ab99339f7dd22bdd4ff7ab7a8e';
	    $secretKey = 'b7da1812738a081a';
	    
	    $httpParams = array(
	        'access_key' => $accessKey,
	        'secret_key' => $secretKey,
	        'date'      => time()
	    );
	    ksort($httpParams);
	    $signString = http_build_query($httpParams);
	    $httpParams['sign'] = strtolower(md5($signString));
	    
	    $par = http_build_query($httpParams);
	    $href = "http://api.coindog.com/topic/list?".$par;
	    
	    $listInfo=$this->curl_get_contents($href);
	    
	    $listInfo = json_decode($listInfo,true);
// 	            dd($listInfo);
        if(!$listInfo){
            echo '接口数据返回错误';
            return;
        }
        $is_collect = false;
        foreach($listInfo as $article){

            $info = M('content')->where(['source'=>1,'unique_num'=>$article['id']])->field('content_id')->find(); //文章是否已存在
            if($info){
                continue;
            }
            
            $info = M('content')->where(['title'=>$article['title']])->field('content_id')->find(); //文章是否已存在
            if($info){
                continue;
            }

            $_POST = array('class_id'=>5,'title'=>$article['title'],'description'=>$article['summary'],'content'=>$article['content'],
                     'author'=>$article['author'],'image'=>$article['thumbnail'],'time'=>$article['published_time'],'unique_num'=>$article['id'],
                'views'=>rand(60,120),'status'=>2,'source'=>1);
            
            
            $content_id=D('Article/ContentArticle')->saveData('add');
            echo '采集文章成功--'.$article['id'].'<br>';
            $is_collect = true;
        }
        if($is_collect){
            echo '采集完成';
        }else{
            echo '没有待采集的最新文章';
        }
	    
	}
	
	//语音
	public function test(){
	    dd('dd');
	    $client = new AipSpeech('19565576','g6nuKrel2B0LyrqbDTcdqmuE','Aph9f046ghj2B8UCrCNTjLiqB7ue8db7');
	    
	    $text = '近期，火爆币圈的Atis又传来了劲爆的消息，主流媒体铺天盖地地宣传Atis即将上线ATDF，官方也给出了肯定的答复，这消息一时间传播全网，成为当前市场的热搜话题。Atis上线ATDF，这意味着其将全面开启新篇章，为我们开辟了一条全新的通向财富自由的道路，也意味着Atis拥有了更多的可能性。Atis作为一条真正的公链，具有独特的战略玩法，完美的制度模式，吸引了不少用户的关注，如今，Atis即将上线ATDF，势必会吸引更多人入场，Atis也会变得更为强大，毋庸置疑，Atis未来可期。';
	    $result = $client->synthesis($text, 'zh', 1, array(
	        'vol' => 5,
	    ));
	    
	    // 识别正确返回语音二进制 错误则返回json 参照下面错误码
	    if(!is_array($result)){
	        file_put_contents('audio.mp3', $result);
	    }
	    dd($result);
	}
    
}