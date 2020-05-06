<?php
namespace Home\Controller;
use Home\Controller\SiteController;
use Think\AipSpeech;
use Org\Workerman\Worker;
use Org\WebSocket\Client;


require_once '/ThinkPHP/Library/Org/Workerman/Autoloader.class.php';
/**
 * 爬虫采集
 */

class WordToVoiceController extends SiteController {
    
    
    

    //百度语音转换
    public function baidu(){
        dd('dd');
        $client = new AipSpeech('19565576','g6nuKrel2B0LyrqbDTcdqmuE','Aph9f046ghj2B8UCrCNTjLiqB7ue8db7');
        
        $text = '近期，火爆币圈的Atis又传来了劲爆的消息，主流媒体铺天盖地地宣传Atis即将上线ATDF，官方也给出了肯定的答复，这消息一时间传播全网，成为当前市场的热搜话题。Atis上线ATDF，这意味着其将全面开启新篇章，为我们开辟了一条全新的通向财富自由的道路，也意味着Atis拥有了更多的可能性。Atis作为一条真正的公链，具有独特的战略玩法，完美的制度模式，吸引了不少用户的关注，如今，Atis即将上线ATDF，势必会吸引更多人入场，Atis也会变得更为强大，毋庸置疑，Atis未来可期。';
        $result = $client->synthesis($text, 'zh', 1, array(
            'vol' => 5,
        ));
        
        // 识别正确返回语音二进制 错误则返回json 参照下面错误码
        if(!is_array($result)){
            file_put_contents('/Uploads/voice/audio.mp3', $result);
        }
        dd($result);
    }
    public function xfyun(){

        dd($_SERVER['HTTP_HOST']);
        //科大讯飞  文字转语音接口  未调试成功
        
        header('content-type:text/html;charset=utf-8');
        //         header('request-line:GET /v2/tts HTTP/1.1;');
        
        $APPID = '5ea14fa7';
        $APIKey    = '2755cb2227f34113a8ead5f6517d3afc';
        $APISecret = 'd63291623fb38236728f89783c65ede8';
        
        
        $url = 'wss://tts-api.xfyun.cn/v2/tts';
        $date = gmstrftime("%a, %d %b %Y %H:%M:%S",time()).' GMT';
        //         dd($date);
        $date = 'Wed, 06 May 2020 06:10:32 GMT';
        $host = 'ws-api.xfyun.cn';
        
        $temp = 'GET /v2/tts HTTP/1.1';
        $signature_origin = "host: $host\ndate: $date\n$temp";
        
        //         dump($signature_origin);
        //         exit;
        
        $signature_sha =  hash_hmac('sha256',$signature_origin,$APISecret,false);
        
        //         $signature_sha = '5d99a940edb2a2eaf716ae95752acab59ea5d92bf97696872c96b22c0d9c02d4';
        //         var_dump($signature_sha);
        $signature = base64_encode($signature_sha);
        
        //         dd($signature);
        $authorization_origin = 'api_key="'.$APIKey.'",algorithm="hmac-sha256",headers="host date request-line",signature="'.$signature.'"';
        //         dd($authorization_origin);
        $authorization = base64_encode($authorization_origin);
        
        $url_all = $url."?authorization=$authorization&date=$date&host=$host";
        //         $date = urlencode($date);
        
        //         $worker  = new Worker($url_all);
        
        //         exit;
        
        //         dd($url_all);
        $this ->assign('url',$url_all);
        
        $this -> siteDisplay('xfyun');
    }
    
    public function test(){

        //科大讯飞

        header('content-type:text/html;charset=utf-8');
//         header('request-line:GET /v2/tts HTTP/1.1;');
        
        $APPID = '5ea14fa7';
        $APIKey    = '2755cb2227f34113a8ead5f6517d3afc';
        $APISecret = 'd63291623fb38236728f89783c65ede8';

        
        $url = 'wss://tts-api.xfyun.cn/v2/tts';
        $date = gmstrftime("%a, %d %b %Y %H:%M:%S",time()).' GMT';
//         dd($date);
        $date = 'Wed, 06 May 2020 06:10:32 GMT';
        $host = 'tts-api.xfyun.cn';

        $temp = 'GET /v2/tts HTTP/1.1';
        $signature_origin = "host: $host\ndate: $date\n$temp";
        
//         dump($signature_origin);
//         exit;
       
        $signature_sha =  hash_hmac('sha256',$signature_origin,$APISecret,false);

//         $signature_sha = '5d99a940edb2a2eaf716ae95752acab59ea5d92bf97696872c96b22c0d9c02d4';
//         var_dump($signature_sha);
        $signature = base64_encode($signature_sha);

//         dd($signature);
        $authorization_origin = 'api_key="'.$APIKey.'",algorithm="hmac-sha256",headers="host date request-line",signature="'.$signature.'"';
//         dd($authorization_origin);
        $authorization = base64_encode($authorization_origin);
        
        $url_all = $url."?authorization=$authorization&date=$date&host=$host";
//         $date = urlencode($date);

//         $worker  = new Worker($url_all);
        
//         exit;
        
//         dd($url_all);
        $this ->assign('url',$url_all);
        
        $this -> siteDisplay('test');
    }
    
}