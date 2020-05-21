<?php
namespace Home\Controller;
use Common\Controller\BaseController;
/**
 * 前台公共类
 */
class SiteController extends BaseController {

    public function __construct()
    {
//         if($_SERVER['HTTP_HOST'] != 'mayi.org'){
//             redirect($_SERVER['REQUEST_SCHEME'].'://mayi.org');
//         }
        parent::__construct();
        $this->initialize();
    }

    /**
     * 控制器初始化
     */
    protected function initialize(){
        //设置手机版参数
        if(MOBILE){
            C('TPL_NAME' , C('MOBILE_TPL'));
        }
        
        $isLogin = $this->isLogin();
        if($isLogin){
            $loginInfo = D('Users')->getUserInfo(['id'=>$isLogin['user_id']]);
            $this->assign('loginInfo',$loginInfo);
        }
   
    }

    /**
     * 前台模板显示 调用内置的模板引擎显示方法
     * @access protected
     * @param string $name 模板名
     * @param bool $type 模板输出
     * @return void
     */
    protected function siteDisplay($name='',$type = true) {
        C('TAGLIB_PRE_LOAD','Dux');
        C('TAGLIB_BEGIN','<!--{');
        C('TAGLIB_END','}-->');
        C('VIEW_PATH','./themes/');
        $data = $this->view->fetch(C('TPL_NAME').'/'.$name);
        //模板包含
        if(preg_match_all('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/', $data, $matches)){
            foreach ($matches[1] as $k => $v) {
                $ext=explode('.', $v);
                $ext=end($ext);
                $file=substr($v, 0, -(strlen($ext)+1));
                $phpText = $this->view->fetch(C('TPL_NAME').'/'.$file);
                $data = str_replace($matches[0][$k], $phpText, $data);
            }
        }
        //替换资源路径
        $tplReplace=array(
            //普通转义
            'search' => array(
                //转义路径
                "/<(.*?)(src=|href=|value=|background=)[\"|\'](images\/|img\/|css\/|js\/|style\/)(.*?)[\"|\'](.*?)>/",
            ),
            'replace' => array(
                "<$1$2\"".__ROOT__."/themes/".C('TPL_NAME')."/"."$3$4\"$5>",
            ),      
        );
        $data = preg_replace(  $tplReplace['search'] , $tplReplace['replace'] , $data);
        if($type){
            echo $data;
        }else{
            return $data;
        }
        
    }

    /**
     * 页面Meda信息组合
     * @return array 页面信息
     */
    protected function getMedia($title='',$keywords='',$description='')
    {
        if(empty($title)){
            $title=C('SITE_TITLE').' - '.C('SITE_SUBTITLE');
        }else{
            $title=$title.' - '.C('SITE_TITLE').' - '.C('SITE_SUBTITLE');
        }
        if(empty($keywords)){
            $keywords=C('SITE_KEYWORDS');
        }
        if(empty($description)){
            $description=C('SITE_DESCRIPTION');
        }
        return array(
            'title'=>$title,
            'keywords'=>$keywords,
            'description'=>$description,
        );
    }
    
    /**
     * 检测用户是否登录
     * @return int 用户IP
     */
    protected function isLogin(){
    	$user = session('home_user');
    	if (empty($user)) {
    		return 0;
    	} else {
    	    return $user;
    	}
    }

    protected function get_token($appid,$appsecret,$new=0){
    	//dump(S('access_tokens'));
    	if(S('access_tokens'.$appid) && $new==0) return S('access_tokens'.$appid);
    
    	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    	$ret_json = $this->curl_get_contents($url);
    	$ret = json_decode($ret_json);

    	if($ret -> access_token){
    		 
    		S('access_tokens'.$appid,$ret -> access_token,7000);
    		return $ret -> access_token;
    	}
    }
    
    
    protected function is_weixin(){
    	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false) {
    		return true;
    	}
    	return false;
    }
    
    
  
    public function curl_get_contents($url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    	curl_setopt($ch, CURLOPT_USERAGENT, "IE 6.0");
    	$r = curl_exec($ch);
    	curl_close($ch);
    	if($r===false) return file_get_contents($url);
    	return $r;
    }
    
    protected function curlp($post_url,$xjson){//php post
    	$ch = curl_init($post_url);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    	curl_setopt($ch, CURLOPT_POSTFIELDS,$xjson);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Content-Length: ' . strlen($xjson))
    	);
    	$respose_data = curl_exec($ch);
    	return $respose_data;
    }

    
    /**
     * 检查是否登录
     */
    protected function check_is_login(){
    	if( !USER_ID && ( MODULE_NAME <> 'Home' || CONTROLLER_NAME <> 'Login' )){
    		cookie('gourl',get_url());
    		redirect(U('Home/Login/index'));
    	}
    	//获取支付号openid
    	$this->saveOpenid();
    }
    
    
    /**
     * 更新支付openid
     */
    protected  function saveOpenid(){
    	if(!$this->loginUserInfo['wxpay']&&C('OtherAppid')){
    		S('user_'.USER_ID,null);
    		$wxpay = D('Users')->where(array('id'=>USER_ID))->getField('wxpay');
    		if(!$wxpay){
    			$this->getPayOpenid();
    		}
    	}
    }
    	public function siteBuildHtml($htmlfile='',$htmlpath='',$templateFile=''){
		C('TAGLIB_PRE_LOAD','Dux');
        C('TAGLIB_BEGIN','<!--{');
        C('TAGLIB_END','}-->');
		C('VIEW_PATH', './themes/');
		        
		$content    =   $this->fetch($templateFile);
		//模板包含
        if(preg_match_all('/<!--#include\s*file=[\"|\'](.*)[\"|\']-->/', $content, $matches)){
            foreach ($matches[1] as $k => $v) {
                $ext=explode('.', $v);
                $ext=end($ext);
                $file=substr($v, 0, -(strlen($ext)+1));
                $phpText = $this->view->fetch(C('TPL_NAME').'/'.$file);
                $content = str_replace($matches[0][$k], $phpText, $content);
            }
        }
        $htmlpath   =   !empty($htmlpath)?$htmlpath:HTML_PATH;
        $htmlfile   =   $htmlpath.$htmlfile.C('HTML_FILE_SUFFIX');
		$Storage = new \Think\Storage();
        $Storage::put($htmlfile,$content,'html');
        return $content;
	}
	
protected function getRandStr($length){
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randString = '';
	$len = strlen($str)-1;
	for($i = 0;$i < $length;$i ++){
		$num = mt_rand(0, $len);
		$randString .= $str[$num];
	}
	return $randString;
}



protected function sitefetch($name='') {
	 C('TAGLIB_PRE_LOAD','Dux');
        C('TAGLIB_BEGIN','<!--{');
        C('TAGLIB_END','}-->');
        C('VIEW_PATH','./themes/');
	return $this->fetch(C('TPL_NAME').'/'.$name);
  
}

    /**
     *  量子金活动海报生成
     * @param  $mould 海报模板地址
     * @param  $ewm_img  海报上的二维码图片地址
     * @param  $path  海报的地址
     * @param  $position array() 水印的位置,距离左上角
     * @return  返回生成海报图片地址
     */
    
    public function makePosterLsz($mould,$water_img,$path,$position){
        
        $image=new \Think\Image(1);
        
        $image->open($mould)
        
        ->water($water_img,$position,100)
        ->save($path);
        
        return $path;
        
    }
    public function imgThumb($imgUrl,$width,$thumbUrl){
        
        $image=new \Think\Image(1);

        $image->open($imgUrl)
        ->thumb($width,$width)
        ->save($thumbUrl);
    }
    
    
}