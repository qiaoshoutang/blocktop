<?php
return array(
    /* 性能设置 */
    'TMPL_CACHE_ON'=>false, // 模板缓存
    'HTML_CACHE_ON'=>false, // 静态缓存
    'DB_SQL_BUILD_CACHE'=>false, // SQL查询缓存
    'URL_MODEL'=>2, // URL访问模式
    'URL_ROUTER_ON'=>true, // URL路由
	'URL_ROUTE_RULES'=>array(

	    'anthome'=>'Home/Index/index',
	    'description'=>'Home/Index/description',
	    'aboutus'=>'Home/Index/aboutUs',
	    'contactus'=>'Home/Index/contactUs',
	    'message' =>'Home/Index/shortMessage',
	    'news/:class_id' =>'Home/Index/news',
	    'newsContent/:content_id' =>'Home/Index/newsContent',
	    'antmap/:class_id' =>'Home/Index/antMap',
	    'activit/:state/:time' =>'Home/Index/activity',
	    'activitContent/:content_id' =>'Home/Index/activityContent',
	    'search/[:keyword]' =>'Home/Index/searchPage',
	    'Apply' =>'Home/Index/apply',
	    'Columns/[:id]' =>'Home/Index/column',
	    
	    
	    
	    'home_m'          =>  'Home/Mobile/index',
	    'message_m'       =>  'Home/Mobile/message',
	    'news_m/:class_id' =>  'Home/Mobile/news',
	    'newsContent_m/:content_id' =>'Home/Mobile/newsContent',
	    'antmap_m/:class_id'=>  'Home/Mobile/antmap',
	    'activit_m'        =>  'Home/Mobile/activity',
	    'activitContent_m/:content_id'=>  'Home/Mobile/activityContent',
	    
	    
	    'aboutus_m'         =>  'Home/Mobile/aboutus',
	    'team_m'          =>  'Home/Mobile/team',
	    
	    'gocontent/:gzh_num'=>'Home/Short/message',
	    
	    'xfyun' =>'Home/WordToVoice/xfyun', //科大讯飞 文字转语音接口
	    'test' =>'Home/WordToVoice/test',

	),
    'URL_HTML_SUFFIX'=>'html', // 伪静态后缀
	'URL_CASE_INSENSITIVE' =>false,
	'DATA_CACHE_TYPE'       =>  'File',
// 	'MEMCACHE_HOST'  => 'm-j6cd698260a0b4c4.memcache.rds.aliyuncs.com',
// 	'MEMCACHE_PORT'  => '11211',
	'DATA_CACHE_TIME' => '986400',
	'DATA_CACHE_SUBDIR'     =>  true,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  2,        
);