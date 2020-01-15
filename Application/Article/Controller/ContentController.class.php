<?php
namespace Article\Controller;
use Home\Controller\SiteController;
/**
 * 栏目页面
 */

class ContentController extends SiteController {

	/**
     * 栏目页
     */
    public function index()
    {
        $contentId = I('get.content_id',0,'intval');
		
		
		if (!$this->is_weixin()) {
			//exit('please open in weixin');
		}
		
        $urlTitle = I('get.urltitle');
        if (empty($contentId)&&empty($urlTitle)) {
            $this->error404();
        }
        $model = D('ContentArticle');
        //获取内容信息
        if(!empty($contentId)){
            $contentInfo=$model->getInfo($contentId);
        }else if(!empty($urlTitle)){
            $contentInfo=$model->getInfoUrl($urlTitle);
        }else{
            $this->error404();
        }

        $contentId = $contentInfo['content_id'];
        //信息判断
        if (!is_array($contentInfo)){
            $this->error404();
        }
        //获取栏目信息
        $modelCategory = D('CategoryArticle');
        $categoryInfo=$modelCategory->getInfo($contentInfo['class_id']);
        if (!is_array($categoryInfo)){
            $this->error404();
        }
        if($categoryInfo['app']<>MODULE_NAME){
            $this->error404();
        };
        //判断跳转
        if (!empty($contentInfo['url']))
        {
            ob_start();
            $this->show($contentInfo['url']);
            $link = ob_get_clean();
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$link."");
            exit;
        }
        //位置导航
        $crumb = D('DuxCms/Category')->loadCrumb($contentInfo['class_id']);
        //查询上级栏目信息
        $parentCategoryInfo = $modelCategory->getInfo($categoryInfo['parent_id']);
        //获取顶级栏目信息
        $topCategoryInfo = $modelCategory->getInfo($crumb[0]['class_id']);
        //更新访问计数
        $viewsData=array();
        $viewsData['views'] = array('exp','views+1');
        $viewsData['content_id'] = $contentInfo['content_id'];
        D('DuxCms/Content')->editData($viewsData);
        //内容处理
        $contentInfo['content'] = html_out($contentInfo['content']);
        //上一篇
        $prevWhere = array();
        $prevWhere['A.status'] = 1;
        $prevWhere['A.time'] = array('lt',$contentInfo['time']);
        $prevWhere['C.class_id'] = $categoryInfo['class_id'];
        $prevInfo=$model->getWhereInfo($prevWhere,' A.time DESC,A.content_id DESC');
        if(!empty($prevInfo)){
            $prevInfo['aurl']=$model->getUrl($prevInfo,$appConfig);
            $prevInfo['curl']=$modelCategory->getUrl($prevInfo,$appConfig);
        }
        //下一篇
        $nextWhere = array();
        $nextWhere['A.status'] = 1;
        $nextWhere['A.time'] = array('gt',$contentInfo['time']);
        $nextWhere['C.class_id'] = $categoryInfo['class_id'];
        $nextInfo=$model->getWhereInfo($nextWhere,' A.time ASC,A.content_id ASC');
        if(!empty($nextInfo)){
            $nextInfo['aurl']=$model->getUrl($nextInfo,$appConfig);
            $nextInfo['curl']=$modelCategory->getUrl($nextInfo,$appConfig);
        }
        //MEDIA信息
        $media = $this->getMedia($contentInfo['title'],$contentInfo['keywords'],$contentInfo['description']);
        //模板赋值
		 if ($categoryInfo['fieldset_id'] > 0) {//扩展字段处理   2015-3-27 by shanmao.me
        	$kztable = D("DuxCms/Fieldset") -> getInfo($categoryInfo['fieldset_id']);
        	if ($kztable['table']) {
        		$mod1 = D("DuxCms/FieldData");
        		$mod1 -> setTable($kztable['table']);
        		$kzinfo = $mod1 -> getInfo($contentId);
        		if (is_array($kzinfo))
        			$contentInfo = array_merge($contentInfo, $kzinfo);
        	}
        
        }
		
			if($contentInfo['weixin_img_arr']){
				$weixin_img_arr = unserialize($contentInfo['weixin_img_arr']);
				foreach($weixin_img_arr as $key22=>$val22){
					foreach($val22 as $key33=>$val33){
					 if($key33=='title'){
					 	$val33=str_replace('.jpg','',$val33);
					 	$val33=str_replace('.gif','',$val33);
					 	$weixin_img_arr[$key22]['title']=str_replace('.png','',$val33);
						
						$arrdiy[] =$val33;
					 }else{
					 	$arrdiy2[] =$val33;
					 }
				  }
				
				}
				$weixin_arr['title']=implode("','",$arrdiy);
				$weixin_arr['url']=implode("','",$arrdiy2);
				
				$contentInfo['weixin_img_arr'] =$weixin_arr; 
			}

			$login=A('Home/login');
			$codeId=$login->getCode($contentInfo['content_id']);
			$codeMod=D('Admin/QrCode');
			$codeInfo=$codeMod->getInfo($codeId);

			$contentInfo['qr_code']=$codeInfo['qr_code_url'];
		//A("Home/Api")->yqapi('wxb2204a7945fc8cd0','1002d8c41595576446338f3627889a76');

		A("Home/Api")->yqapi('wxff79eee620f67cc6','0c88c81ed50b3111f1b111d40dfb2bd1');
		
		$contentInfo['diydesc'] = html_out($contentInfo['diydesc']);	
        $this->assign('contentInfo', $contentInfo);
        $this->assign('categoryInfo', $categoryInfo);
        $this->assign('parentCategoryInfo', $parentCategoryInfo);
        $this->assign('topCategoryInfo', $topCategoryInfo);
        $this->assign('crumb', $crumb);
        $this->assign('count', $count);
        $this->assign('page', $page);
        $this->assign('media', $media);
        $this->assign('prevInfo', $prevInfo);
        $this->assign('nextInfo', $nextInfo);

		
        $this->assign('res64', $res);
        $this->assign('res64_share', $res_share);
		
		if($_GET['back']==1){
			
			header('Access-Control-Allow-Origin:*');
            header("Accept-Ranges: bytes");
			if($contentInfo['tpl']){
			
        	$this->siteDisplay($contentInfo['tpl']);
        }
			
			exit;
		}
		
        if($contentInfo['tpl']){

        	$this->siteDisplay($contentInfo['tpl']);
        }else{
			
            $this->siteDisplay($categoryInfo['content_tpl']);
        }
    }
	
	
    /**
     * 栏目页
     */
    public function index4()
    {
   
    	$contentId = I('get.content_id',0,'intval');
    	$urlTitle = I('get.urltitle');
    	if (empty($contentId)&&empty($urlTitle)) {
    		$this->error404();
    	}
    	$model = D('ContentArticle');
    	//获取内容信息
    	if(!empty($contentId)){
    		$contentInfo=$model->getInfo($contentId);
    	}else if(!empty($urlTitle)){
    		$contentInfo=$model->getInfoUrl($urlTitle);
    	}else{
    		$this->error404();
    	}
    	$contentId = $contentInfo['content_id'];
    	//信息判断
    	if (!is_array($contentInfo)){
    		$this->error404();
    	}
    	//获取栏目信息
    	$modelCategory = D('CategoryArticle');
    	$categoryInfo=$modelCategory->getInfo($contentInfo['class_id']);
    	if (!is_array($categoryInfo)){
    		$this->error404();
    	}
    	if($categoryInfo['app']<>MODULE_NAME){
    		$this->error404();
    	};
    	//判断跳转
    	if (!empty($contentInfo['url']))
    	{
    		ob_start();
    		$this->show($contentInfo['url']);
    		$link = ob_get_clean();
    		header("HTTP/1.1 301 Moved Permanently");
    		header("Location: ".$link."");
    		exit;
    	}
    	//位置导航
    	$crumb = D('DuxCms/Category')->loadCrumb($contentInfo['class_id']);
    	//查询上级栏目信息
    	$parentCategoryInfo = $modelCategory->getInfo($categoryInfo['parent_id']);
    	//获取顶级栏目信息
    	$topCategoryInfo = $modelCategory->getInfo($crumb[0]['class_id']);
    	//更新访问计数
    	$viewsData=array();
    	$viewsData['views'] = array('exp','views+1');
    	$viewsData['content_id'] = $contentInfo['content_id'];
    	D('DuxCms/Content')->editData($viewsData);
    	//内容处理
    	$contentInfo['content'] = html_out($contentInfo['content']);
    	//上一篇
    	$prevWhere = array();
    	$prevWhere['A.status'] = 1;
    	$prevWhere['A.time'] = array('lt',$contentInfo['time']);
    	$prevWhere['C.class_id'] = $categoryInfo['class_id'];
    	$prevInfo=$model->getWhereInfo($prevWhere,' A.time DESC,A.content_id DESC');
    	if(!empty($prevInfo)){
    		$prevInfo['aurl']=$model->getUrl($prevInfo,$appConfig);
    		$prevInfo['curl']=$modelCategory->getUrl($prevInfo,$appConfig);
    	}
    	//下一篇
    	$nextWhere = array();
    	$nextWhere['A.status'] = 1;
    	$nextWhere['A.time'] = array('gt',$contentInfo['time']);
    	$nextWhere['C.class_id'] = $categoryInfo['class_id'];
    	$nextInfo=$model->getWhereInfo($nextWhere,' A.time ASC,A.content_id ASC');
    	if(!empty($nextInfo)){
    		$nextInfo['aurl']=$model->getUrl($nextInfo,$appConfig);
    		$nextInfo['curl']=$modelCategory->getUrl($nextInfo,$appConfig);
    	}
    	//MEDIA信息
    	$media = $this->getMedia($contentInfo['title'],$contentInfo['keywords'],$contentInfo['description']);
    
    	//模板赋值
    	if ($categoryInfo['fieldset_id'] > 0) {//扩展字段处理   2015-3-27 by shanmao.me
    		$kztable = D("DuxCms/Fieldset") -> getInfo($categoryInfo['fieldset_id']);
    		if ($kztable['table']) {
    			$mod1 = D("DuxCms/FieldData");
    			$mod1 -> setTable($kztable['table']);
    			$kzinfo = $mod1 -> getInfo($contentId);
    			if (is_array($kzinfo))
    				$contentInfo = array_merge($contentInfo, $kzinfo);
    		}
    
    	}
    	//分享
    	A("Home/Api")->yqapi('wxb2204a7945fc8cd0','1002d8c41595576446338f3627889a76');
    	
    	//A("Home/Api")->yqapi('wxff79eee620f67cc6','0c88c81ed50b3111f1b111d40dfb2bd1');
    	$this->assign('contentInfo', $contentInfo);
    	$this->assign('categoryInfo', $categoryInfo);
    	$this->assign('parentCategoryInfo', $parentCategoryInfo);
    	$this->assign('topCategoryInfo', $topCategoryInfo);
    	$this->assign('crumb', $crumb);
    	$this->assign('count', $count);
    	$this->assign('page', $page);
    	$this->assign('media', $media);
    	$this->assign('prevInfo', $prevInfo);
    	$this->assign('nextInfo', $nextInfo);
    	if($contentInfo['tpl']){
    		$this->siteDisplay($contentInfo['tpl']);
    	}else{
    		$this->siteDisplay($categoryInfo['content_tpl']);
    	}
    }
    
	
	
	function strencode($string,$mmm){
    	$string = base64_encode ( $string );
    	$key = md5 ( $mmm);
    	$len = strlen ( $key );
    	$code = '';
    	for($i = 0; $i < strlen ( $string ); $i ++) {       
            $k = $i % $len;       
            $code .= $string [$i] ^ $key [$k];
        }
        return base64_encode ( $code );   
    }
    



    
	
	
	
}