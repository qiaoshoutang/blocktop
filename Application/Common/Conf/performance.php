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
	    'newsContent/:type/:content_id' =>'Home/Index/newsContent',
	    'antmap/:class_id' =>'Home/Index/antMap',
	    'activit/:state/:time' =>'Home/Index/activity',
	    'activitContent/:content_id' =>'Home/Index/activityContent',
	    'search/[:keyword]' =>'Home/Index/searchPage',
	    'Apply' =>'Home/Index/apply',
	    'Columns/[:id]' =>'Home/Index/column',
	    'Author/[:author_id]' =>'Home/User/authorPage',
	    
	    
	    
	    'home_m/[:class_id]'          =>  'Home/Mobile/index',
	    'topmap_m/:class_id'=>  'Home/Mobile/topmap',
	    'message_m'         =>  'Home/Mobile/message',
	    'columns_list_m'   =>'Home/Mobile/column_list',
	    'columns_m/[:id]'   =>'Home/Mobile/column',
	    'aboutus_m'         =>  'Home/Mobile/aboutus',
	    
	    'newsContent_m/:type/:content_id' =>'Home/Mobile/newsContent',


	    
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