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

	        'Content' => array(

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
	                )
	            )
	        ),
	    
		    'Activity' => array(
		        'name' => '活动',
		        'order' => 4,
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
		        'order' => 10,
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
		        'order' => 12,
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
		        'order' => 12,
		        'menu' => array(
		            array(
		                'name' => '专栏列表',
		                'url' => U('Admin/Column/index'),
		                'order' => 1
		            ),
		            array(
		                'name' => '轮播添加',
		                'url' => U('Admin/Column/add'),
		                'order' => 2
		            ),
		        )
		    ),
		    
            'system' => array(
                'name' => '系统',
                'icon' => 'bars',
                'order' => 16,
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
