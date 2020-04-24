<?php
namespace Home\Controller;
use Home\Controller\SiteController;
use Think\AipSpeech;
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
    
    public function test(){
        $this -> siteDisplay('test');
    }
    
}