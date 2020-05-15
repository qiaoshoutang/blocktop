<?php
namespace Home\Controller;

require '/vendor/autoload.php';

use Home\Controller\SiteController;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
/**
 * 站点首页
 */

class UserController extends SiteController {
    
    
    /*
     * 发送验证码
     */
    public function send_code(){
        $phone = I('post.phone',0,'intval');
        $type  = I('post.type',0,'intval');  //1注册验证码   2登录验证码   3忘记密码验证码
        if(empty($phone)){
            $rdata['code'] = 2;
            $rdata['info'] = '手机号不能为空';
            $this->ajaxReturn($rdata);
        }
        if(empty($type)){
            $rdata['code'] = 2;
            $rdata['info'] = '验证码类型不能为空';
            $this->ajaxReturn($rdata);
        }
        if(S('yzm_ban_'.$type.'_'.$phone)){  //验证码间隔60之内 不重复发送
            $rdata['code'] = 2;
            $rdata['info'] = '验证码发送太过频繁';
            $this->ajaxReturn($rdata);
        }
        $code = rand(1,9).rand(1,9).rand(1,9).rand(1,9);

//         $alibabaCloud = new AlibabaCloud();
//         $result = send_sms_code($alibabaCloud,$phone,$code);
//         $resultArr = json_decode($result,true);
        $resultArr['Message'] = 'OK';
        if($resultArr['Message'] == 'OK'){
            S('yzm_ban_'.$type.'_'.$phone,1,60);
            S('yzm_'.$type.'_'.$phone,$code,300);
            $rdata['code'] = 1;
            $rdata['info'] = '验证码发送成功！';
            $rdata['data'] = $code;
        }else{
            $rdata['code'] = 3;
            $rdata['info'] = '接口调用错误，请联系技术人员';
        }
        $this->ajaxReturn($rdata);
    }
    

    //注册ajax提交
    public function register(){

        $phone = I('post.phone',0,'intval');
        $code = I('post.code',0,'intval');
        $password = I('post.password','','trim');
        $repassword = I('post.repassword','','trim');
        
        if(empty($phone)||empty($password)||empty($repassword)||empty($code)){
            $this->ajaxReturn(['code'=>2,'info'=>'必填项不能为空']);
        }

        if($password != $repassword){
            $this->ajaxReturn(['code'=>2,'info'=>'两次输入的密码不一致']);
        }
        if(empty(S('yzm_1_'.$phone))){
            $this->ajaxReturn(['code'=>2,'info'=>'验证码已失效']);
        }
        if($code != S('yzm_1_'.$phone)){
            $this->ajaxReturn(['code'=>2,'info'=>'验证码错误']);
        }
        
        $userMod = D('Users');
        
        $exist = $userMod->where(['phone'=>$phone])->find('id');
        if($exist){
            $this->ajaxReturn(['code'=>2,'info'=>'该手机号已注册！']);
        }
        $userInfo['phone'] = $userInfo['nickname'] = $phone;
        $userInfo['top_num'] = $this->getTopnum();
        $userInfo['password'] = md5('blocktop_'.$password);
        $userInfo['regip'] = $_SERVER['REMOTE_ADDR'];
        $userInfo['create_time'] = time();
        $userInfo['status'] = 1;
        $res = $userMod->add($userInfo);
        if($res){
            S('yzm_1_'.$phone,null);
            $userMod->setLogin($userInfo['id']);
            $rdata['code'] = 1;
            $rdata['info'] = '注册成功！';
            $rdata['data'] = $userInfo;
        }else{
            $rdata['code'] = 2;
            $rdata['info'] = '注册失败！';
        }
        
        $this->ajaxReturn($rdata);
    }


    //登录ajax提交
    public function accountLogin(){

        $phone = I('post.phone',0,'trim');
        $password = I('post.password','','trim');
        $code = I('post.code','','trim');
        $type = I('post.type','','intval');  //1密码登陆   2验证码登录

        if(empty($phone)){
            $this->ajaxReturn(['code'=>2,'info'=>'参数不能为空']);
        }
        
        $userMod = D('Users');
        
        $userInfo = $userMod->where(['phone'=>$phone])->find();
        if(empty($userInfo)){
            $this->ajaxReturn(['code'=>2,'info'=>'该账号不存在']);
        }
        if($type == 1){  //密码登陆
            if(md5('blocktop_'.$password) != $userInfo['password']){
                $this->ajaxReturn(['code'=>2,'info'=>'密码错误']);
            }
        }elseif($type == 2){  //验证码登录
            if(empty(S('yzm_2_'.$phone))){
                $this->ajaxReturn(['code'=>2,'info'=>'验证码已过期']);
            }

            if($code != S('yzm_2_'.$phone)){
                $this->ajaxReturn(['code'=>2,'info'=>'验证码错误']);
            }
        }

        $res = $userMod->setLogin($userInfo['id']);
        if($res){
            S('yzm_2_'.$phone,null);
            $rdata['code'] = 1;
            $rdata['info'] = '登录成功';
            $rdata['data'] = $userInfo;
        }else{
            $rdata['code'] = 2;
            $rdata['info'] = '登录失败';
        }
        
        $this->ajaxReturn($rdata);
    }
    
    public function test(){
        dump(C('sss'));
    }
    //找回密码ajax提交
    public function getPassword(){
        $phone = I('post.phone',0,'trim');
        $password = I('post.password','','trim');
        $repassword = I('post.repassword','','trim');
        $code = I('post.code','','intval');
        
        if(empty($phone)||empty($password)||empty($repassword)||empty($code)){
            $this->ajaxReturn(['code'=>2,'info'=>'必填项不能为空']);
        }
        
        if($password != $repassword){
            $this->ajaxReturn(['code'=>2,'info'=>'两次输入的密码不一致']);
        }
        if(empty(S('yzm_3_'.$phone))){
            $this->ajaxReturn(['code'=>2,'info'=>'验证码已失效']);
        }
        if($code != S('yzm_3_'.$phone)){
            $this->ajaxReturn(['code'=>2,'info'=>'验证码错误']);
        }
        
        $userMod = D('Users');
        
        $userInfo = $userMod->where(['phone'=>$phone])->find('id');
        if(empty($userInfo)){
            $this->ajaxReturn(['code'=>2,'info'=>'该手机号还未注册！']);
        }
        $sdata['id']       = $userInfo['id'];
        $sdata['password'] = md5('blocktop_'.$password);

        $res = $userMod->add($sdata);
        if($res){
            S('yzm_3_'.$phone,null);
            $userMod->setLogin($userInfo['id']);
            $rdata['code'] = 1;
            $rdata['info'] = '密码重置成功！';
            $rdata['data'] = $userInfo;
        }else{
            $rdata['code'] = 2;
            $rdata['info'] = '密码重置失败！';
        }
        
        $this->ajaxReturn($rdata);
    }
    

    //获取头条编号
    private function getTopnum(){
        $arr = ['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','x','t','u','v','w','x','y','z'];
        shuffle($arr);
        $code = 'top'.implode(array_slice($arr,0,7));
        $userMod = D('Users');
        $res = $userMod->where(['top_num',$code])->find();
        while($res){
            shuffle($arr);
            $code = 'top'.implode(array_slice($arr,0,7));
            $res = $userMod->where(['top_num',$code])->find();
        }
        
        return $code;
    }
    
}