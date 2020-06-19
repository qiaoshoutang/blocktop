<?php
namespace Admin\Service;
/**
 * 后台菜单接口
 */
class MenuService{
	/**
	 * 获取菜单结构
	 */
	public function getAdminMenu(){
		return array(
            'index' => array(
                'name' => '首页',
                'icon' => 'home',
                'order' => 0,
                'menu' => array(
                    array(
                        'name' => '管理首页',
                        'url' => U('Admin/Index/index'),
                        'order' => 0
                    )
                )
            ),
		    'Wechat' => array(
		        'name' => '采集管理',
		        'order' => 10,
		        'menu' => array(
		            array(
		                'name' => '公众号列表',
		                'url' => U('Admin/Wechat/index'),
		                'order' => 1
		            ),
		            array(
		                'name' => '添加公众号',
		                'url' =>  U('Admin/Wechat/add'),
		                'order' => 3
		            )
		        )
		    ),


		    'Content' => array(
		        'name' => '内容管理',
		        'icon' => 'home',
		        'order' => 20,
	            'menu' => array(
	                array(
	                    'name' => '新闻列表',
	                    'url' => U('Article/AdminContent/index'),
	                    'order' => 1
	                ),
	                array(
	                    'name' => '添加新闻',
	                    'url' =>  U('Article/AdminContent/add'),
	                    'order' => 3
	                ),
	                array(
	                    'name' => '快讯列表',
	                    'url' =>  U('Article/Messag/index'),
	                    'order' => 5
	                ),
	                array(
	                    'name' => '添加快讯',
	                    'url' =>  U('Article/Messag/add'),
	                    'order' => 7
	                ),
	                array(
	                    'name' => '视频列表',
	                    'url' =>  U('Article/Video/index'),
	                    'order' => 9
	                ),
	                array(
	                    'name' => '添加视频',
	                    'url' =>  U('Article/Video/add'),
	                    'order' => 11
	                )
	            )
	        ),
	        'Users' => array(
	            'name' => '用户管理',
	            'order' => 30,
	            'menu' => array(
	                array(
	                    'name' => '用户列表',
	                    'url' => U('Admin/User/index'),
	                    'order' => 1
	                ),
	                array(
	                    'name' => '添加用户',
	                    'url' =>  U('Admin/User/add'),
	                    'order' => 3
	                )
	            )
	        ),
	    
		    'Activity' => array(
		        'name' => '活动管理',
		        'order' => 40,
		        'menu' => array(
		            array(
		                'name' => '活动列表',
		                'url' => U('Admin/Activity/activityList'),
		                'order' => 1
		            ),
		            array(
		                'name' => '活动添加',
		                'url' => U('Admin/Activity/activityAdd'),
		                'order' => 2
		            )
		        )
		    ),

		    'Council' => array(
		        'name' => '蚂蚁导航',
		        'order' => 50,
		        'menu' => array(
		            array(
		                'name' => '导航列表',
		                'url' => U('Admin/Navi/index'),
		                'order' => 1
		            ),
		            array(
		                'name' => '导航添加',
		                'url' => U('Admin/Navi/add'),
		                'order' => 3
		            ),
		        )
		     ),
		    
		    'Banner' => array(
		        'name' => '轮播管理',
		        'order' => 60,
		        'menu' => array(
		            array(
		                'name' => '轮播列表',
		                'url' => U('Admin/Banner/index'),
		                'order' => 1
		            ),
		            array(
		                'name' => '轮播添加',
		                'url' => U('Admin/Banner/add'),
		                'order' => 2
		            ),
		        )
		    ),
		    'Column' => array(
		        'name' => '专栏管理',
		        'order' => 70,
		        'menu' => array(
		            array(
		                'name' => '专栏列表',
		                'url' => U('Admin/Column/index'),
		                'order' => 1
		            ),
		            array(
		                'name' => '专栏添加',
		                'url' => U('Admin/Column/add'),
		                'order' => 2
		            ),
		        )
		    ),
		    
            'system' => array(
                'name' => '系统',
                'icon' => 'bars',
                'order' => 80,
                'menu' => array(
                    array(
                        'name' => '系统设置',
                        'url' => U('Admin/Setting/site'),
                        'order' => 0,
                        'divider' => true,
                    ),
                    array(
                        'name' => '用户管理',
                        'url' => U('Admin/AdminUser/index'),
                        'order' => 7,
                        'divider' => true,
                    ),
                    array(
                        'name' => '用户组管理',
                        'url' => U('Admin/AdminUserGroup/index'),
                        'order' => 8,
                    )
                )
            ),
        );
	}
	


}
