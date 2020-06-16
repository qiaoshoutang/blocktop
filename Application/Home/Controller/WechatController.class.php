<?php
namespace Home\Controller;
use Home\Controller\SiteController;


/**
 * 站点首页
 */

class WechatController extends SiteController {
    
    
    public function __construct() {

        parent::__construct ();
        header("Content-Type:text/html; charset=utf-8");
    }
    
    public function set_signature(){
        $signArr['jsapi_ticket'] = $this->get_jsapi_ticket();
        $signArr['noncestr'] = $this->getRandStr(8);
        $signArr['timestamp'] = time();
        $signArr['url'] = get_url();
        
        $string = '';
        foreach($signArr as $key=>$val){
            $string.=$key.'='.$val.'&';
        }
        $string = trim($string,'&');
        $signature = sha1($string);
//         dd($signature);
        $this->assign('appid',C('APPID'));
        $this->assign('timestamp',$signArr['timestamp']);
        $this->assign('nonceStr',$signArr['noncestr']);
        $this->assign('signature',$signature);
        $this->assign('url',$signArr['url']);
        return true;
        
    }
    public function get_jsapi_ticket(){
//         return 'erffd';
        $access_token = $this->get_token(C('APPID'),C('SECRET'));
        if(empty($access_token)){
            return false;
        }
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi";
        
        $res = $this->curl_get_contents($url);
        $resArr = json_decode($res,true);
//         dd($resArr);
        if($resArr['errmsg'] == 'ok'){
            S('ticket_'.C('APPID'),$resArr['ticket'],7000);
            return $resArr['ticket'];
        }else{
            return false;
        }
        
    }
}