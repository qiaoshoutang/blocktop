<?php
namespace Common\Controller;
use Think\Controller;
/**
 * 前台公共类
 */
class BaseController extends Controller {

    public function __construct()
    {
        parent::__construct();
      
        //判断安装程序
        $lock = realpath('./') . DIRECTORY_SEPARATOR . 'install.lock';
        if(!is_file($lock)){
            $this->redirect('Install/Index/index');
        }
        $this->setCont();
    }

    protected function setCont(){
    	
        // 读取站点配置
        $siteConfig = D('Admin/Config')->getInfo();
        C($siteConfig);
		$this->assign('config',$siteConfig);

    }

    /**
     * 页面不存在
     * @return array 页面信息
     */
    protected function error404()
    {
        $this->error('页面不存在！');
    }

    /**
     * 通讯错误
     */
    protected function errorBlock(){
        $this->error('通讯发生错误，请稍后刷新后尝试！');
    }

    /**
     * 获取分页数量
     * @param int $count 数据总数
     * @param int $listRows 每页数量
     */
    protected function getPageLimit($count,$listRows,$reduce=0,$reduce2=0) {

        $this->pager = new \Think\Page($count,$listRows);
        $start_num = $this->pager->firstRow -$reduce;
        $record_num = $this->pager->listRows -$reduce2;
        return $start_num.','.$record_num;

    }

    /**
     * 分页显示
     * @param array $map 分页附加参数
     */
    protected function getPageShow($map = '',$is_route) {
        if(!empty($map)){
            $map = array_filter($map);
            $this->pager->parameter = $map;
        }
        return $this->pager->show($is_route);
    }
    
    //活动入口二维码
    //参数：1模板图片  2水印文字  3二维码图片  4合成后图片
    public function makeActIn($mould,$word,$ewm_img,$result){ // by tony
        
        $image=new \Think\Image(1);

        $image->open($mould)
        ->text($word,'./Public/font/msyh.ttf','14','#000', 1,array(120,40))
        ->text($word,'./Public/font/msyh.ttf','14','#000', 1,array(121,40))

        ->text($word,'./Public/font/msyh.ttf','14','#000', 1,array(120,41))
        ->water($ewm_img,array(35,120),100)
        ->save($result);
    }

    
}


