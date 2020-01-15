<?php
namespace Home\Controller;
use Home\Controller\SiteController;
/**
 * 
 */
class ActivityController extends SiteController {
    
    public function test(){
        
        echo $_SERVER['HTTP_HOST'];
        
    }
    
    
    public function index(){
//         $act_id=90;
//         $domainPool = D('DomainPool/DomainPool');
//         $where['type']=2;//中转 登录域名
//         $where['is_use']=1;
//         $domainInfo = $domainPool->getWhereInfo($where);

//         $ldy_url="http://".$domainInfo['domain']."/index.php?s=/Home/Hm/login/act_id/".$act_id;
//         $ldy_url="http://127.0.0.6/index.php?s=/Home/Hm/login";
//         redirect($ldy_url);
    }
    
    //用户登录
    public function login(){
       
        
        
        header("Content-Type:text/html; charset=utf-8");
        $act_id=I('request.act_id');
        $pid=I('request.pid');
        


//         redirect(U('Home/Activity/redirectShow',array('act_id'=>$act_id,'uid'=>2)));
        
        $login=A('Home/login');
        $login->dowxlogin($act_id,$pid);

    }
    
    //跳转至落地展示页面
    public function redirectShow(){
        
        $act_id=I('request.act_id');
        $uid=I('request.uid');
        

        $domainPool = D('DomainPool/DomainPool');
        $where['type']=3;//落地  展示域名
        $where['is_use']=1;
        $where['act_id']=$act_id;
        $domainInfo = $domainPool->getWhereInfo($where);
        
//         $show_url="http://".$domainInfo['domain'].U('Home/Activity/show',array('act_id'=>$act_id,'uid'=>$uid));
//         echo $show_url;
//         redirect($show_url);
        
        $show_url="http://".$domainInfo['domain']."/show/".$uid;
        redirect($show_url);

    }
    
    //落地展示页面,填写imtoken页面  
    public function show(){
        header("Content-Type:text/html; charset=utf-8");

//         $act_id = I('get.act_id',0,'intval');
        $uid = I('get.uid',0,'intval');
        

        

        
        if (empty($uid)) {
            exit('缺少uid');
            $this->error404();
        }
        $userMod=D('Home/Users');
        $uinfo=$userMod->getUserInfo(array('id'=>$uid));
        

        
        if (empty($uinfo['act_id'])){
            exit('缺少act_id');
            $this->error404();
        }
        $domian=$_SERVER['HTTP_HOST'];
        
        $rule_url="http://".$domian."/rule/".$uinfo['act_id'];
            
        if($uinfo['imtoken']){//已经填写了imtoken
            
            redirect('http://'.$domian.'/showCandy/'.$uid);
//             redirect(U('Home/Activity/showCandy',array('uid'=>$uid)));
        }

        $actInfo=$this->getContentInfo($uinfo['act_id']);

        
        $tpl=$actInfo['tpl'];//活动对应的模板
        $tpl =trim($tpl);
        if(!$tpl){
            $tpl='index';
        }

        $this->assign('uid',$uid);
        $this->assign('rule_url',$rule_url);

        $this->assign('actInfo',$actInfo);
        $this -> siteDisplay($tpl);
    }

    //落地展示页面,糖果页面      
    public function showCandy(){
        header("Content-Type:text/html; charset=utf-8");
        
        $uid = I('request.uid',0,'intval');
        
        if(!$uid){
            exit('参数错误');
        }

        $userMod=D('Home/Users');
        $uinfo=$userMod->getUserInfo(array('id'=>$uid));
        
        if(!$uinfo){
            exit('无该用户');
        }
        
        $actInfo=$this->getContentInfo($uinfo['act_id']);

        $tpl=trim($actInfo['tpl']);//活动对应的模板

        if(!$tpl){
            $tpl='index_2';
        }else{
            $tpl=$tpl.'_2';//第二个页面
        }
        
        $this->assign('uinfo',$uinfo);
        $this->assign('actInfo',$actInfo);
        
        $this -> siteDisplay($tpl);
    }
    
    //活动规则
    public function rule(){
        
        $act_id = I('get.act_id',0,'intval');
        
        $actInfo=$this->getContentInfo($act_id);
        
        
        $tpl=$actInfo['tpl'];//活动对应的模板
        $tpl =trim($tpl);
        if(!$tpl){
            $tpl='index_3';
        }else{
            $tpl=$tpl.'_3';//第三个页面
        }
        
        $this -> siteDisplay($tpl);
    }
    
    //获取完整的活动内容。包括扩展字段。
    public function getContentInfo($content_id){
        //活动详情
        $contentMod = D('Article/ContentArticle');
        $contentInfo=$contentMod->getInfo($content_id);
        
        $modelCategory = D('Article/CategoryArticle');
        $categoryInfo=$modelCategory->getInfo($contentInfo['class_id']);
        if (!is_array($categoryInfo)){
            $this->error404();
        }
        
        if ($categoryInfo['fieldset_id'] > 0) {//扩展字段处理   2015-3-27 by shanmao.me
            $kztable = D("DuxCms/Fieldset") -> getInfo($categoryInfo['fieldset_id']);
            
            if ($kztable['table']) {
                $mod1 = D("DuxCms/FieldData");
                $mod1 -> setTable($kztable['table']);
                $kzinfo = $mod1 -> getInfo($content_id);
                
                if (is_array($kzinfo))
                    $contentInfo = array_merge($contentInfo, $kzinfo);
            }
            
        }
        return $contentInfo;
    }
    
    //ajax 请求验证Imtoken地址 是否存在，如果不存在，则立即写入到用户表，并生成糖果码写入
    public function ver_imtoken(){
        
        $uid=I('request.uid',0,'intval');
        $imtoken=I('request.imtoken','',trim);
        
        
        if(empty($uid)||empty($imtoken)){
            $this->ajaxReturn(array('code'=>0,'msg'=>'参数不可为空'));
        }
        
        $userMod=D('Users');
        
        $where['imtoken']=$imtoken;
        $re=$userMod->getUserInfo($where);
        if($re){  //已存在该地址
            $this->ajaxReturn(array('code'=>0,'msg'=>'地址已存在'));
        }else{  //改地址不存在  则写入该地址
            
            $status=$userMod->where(array('id'=>$uid))->setField('imtoken',$imtoken);
            $candy=$this->getCandy();
            $uinfo=$userMod->getUserInfo(array('id'=>$uid));
            $actInfo=$this->getContentInfo($uinfo['act_id']);
            
            $udata['id']=$uid;
            $udata['imtoken']=$imtoken;
            $udata['candy']=$candy;
            $udata['money_reg']=$actInfo['money_reg'];

            $status=$userMod->save($udata);
            
            if($status){//写入成功
                $this->ajaxReturn(array('code'=>1,'msg'=>'imtoken地址写入成功'));
               
            }else{  //写入失败
                $this->ajaxReturn(array('code'=>0,'msg'=>'imtoken地址写入失败'));
            }
            
        }
    }
    //生成数据库中  未存在的糖果码
    public function getCandy(){
        
        $unique=0;
        $candy='';
        $userMod=D('Users');
        while(!$unique){
            $candy=$this->getRandStr(6);//生成随机的糖果码

            $where['candy']=$candy;
            $re=$userMod->getUserInfo($where);
            if(!$re){//用户表中 无该candy 立即写入该candy  跳出循环
                $unique=1;
            }
        }
        return $candy;
    }
    

    public function getMyPoster(){
        
        $uid=I('post.uid', 0 ,'intval');
        $act_id=I('post.act_id',0,'intval');
        
        if(!$uid||!$act_id){
            $this->ajaxReturn(array('code'=>0,'msg'=>'缺少参数'));
        }
        
        $domainPool = D('DomainPool/DomainPool');
        $where['type']=1;//落地  展示域名
        $where['is_use']=1;
        $where['act_id']=$act_id;
        $domainInfo = $domainPool->getWhereInfo($where);
        
        if(!$domainInfo){
            $this->ajaxReturn(array('code'=>0,'msg'=>'未配置展示域名'));
        }
        
        $url="http://".$domainInfo['domain'].'/goact/'.$act_id.'/'.$uid;
        
        $moule='./Public/mould/mould_lzj.png';
        
        $path='./Uploads/'.date('Y-m-d').'/';
        if(!is_dir($path)){
            mkdir($path);
        }
        $qrImg=$path.'qr_'.$act_id.'_'.$uid.'.png';
        qrcode($url,$qrImg,20);
        $qrImgThumb=$path.'qr_'.$act_id.'_'.$uid.'_thumb.png';
        
        $this->imgThumb($qrImg,'320',$qrImgThumb);
        
        $postImg=$path.'poster_'.$act_id.'_'.$uid.'.png';
        $this->makePosterLsz($moule,$qrImgThumb,$postImg,array(215,816));
        
        $path2='/Uploads/'.date('Y-m-d').'/';
        $postImg2=$path2.'poster_'.$act_id.'_'.$uid.'.png';//海报的绝对路径
        
        $this->ajaxReturn(array('code'=>1,'msg'=>'生成海报成功','poster'=>$postImg2));
        
        
    }
    
    
    
    
    
    
    
    
    
}