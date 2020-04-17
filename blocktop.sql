/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : blocktop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-04-17 16:15:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sm_activity
-- ----------------------------
DROP TABLE IF EXISTS `sm_activity`;
CREATE TABLE `sm_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(124) NOT NULL COMMENT '名称',
  `cover_url` varchar(124) NOT NULL COMMENT '封面图',
  `position` varchar(255) DEFAULT NULL COMMENT '活动地点',
  `duration` varchar(255) DEFAULT NULL COMMENT '活动期间',
  `views` smallint(5) unsigned DEFAULT NULL COMMENT '浏览量',
  `content` text COMMENT '活动内容',
  `master_name_1` varchar(63) DEFAULT NULL COMMENT '主办方一名字',
  `master_descrip_1` varchar(255) DEFAULT NULL COMMENT '主办方一简介',
  `master_icon_1` varchar(255) DEFAULT NULL COMMENT '主办方一图标',
  `master_target_1` varchar(255) DEFAULT NULL COMMENT '主办方一链接',
  `master_name_2` varchar(63) DEFAULT NULL COMMENT '主办方二名字',
  `master_descrip_2` varchar(255) DEFAULT NULL COMMENT '主办方二简介',
  `master_icon_2` varchar(255) DEFAULT NULL COMMENT '主办方二图标',
  `master_target_2` varchar(255) DEFAULT NULL COMMENT '主办方二链接',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '报名二维码',
  `state` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '1报名中  2已结束',
  `time` int(11) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sm_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `sm_admin_group`;
CREATE TABLE `sm_admin_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `base_purview` text,
  `menu_purview` text,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`group_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台管理组';

-- ----------------------------
-- Table structure for sm_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `sm_admin_log`;
CREATE TABLE `sm_admin_log` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  `ip` varchar(250) DEFAULT NULL,
  `app` varchar(250) DEFAULT '1',
  `content` text,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 COMMENT='后台操作记录';

-- ----------------------------
-- Table structure for sm_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `sm_admin_user`;
CREATE TABLE `sm_admin_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户IP',
  `group_id` int(10) NOT NULL DEFAULT '1' COMMENT '用户组ID',
  `username` varchar(20) NOT NULL COMMENT '登录名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `nicename` varchar(20) DEFAULT NULL COMMENT '昵称',
  `email` varchar(50) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
  `level` int(5) DEFAULT '1' COMMENT '等级',
  `reg_time` int(10) DEFAULT NULL COMMENT '注册时间',
  `last_login_time` int(10) DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) DEFAULT '未知' COMMENT '登录IP',
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`),
  KEY `group_id` (`group_id`) USING BTREE,
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台管理员';

-- ----------------------------
-- Table structure for sm_banner
-- ----------------------------
DROP TABLE IF EXISTS `sm_banner`;
CREATE TABLE `sm_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` smallint(5) unsigned DEFAULT NULL COMMENT '权重  越大越靠前',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `target` varchar(255) DEFAULT NULL COMMENT '轮播跳转地址',
  `path` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `state` varchar(255) DEFAULT '1' COMMENT '1显示 2不显示',
  `time` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sm_category
-- ----------------------------
DROP TABLE IF EXISTS `sm_category`;
CREATE TABLE `sm_category` (
  `class_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT '0',
  `show` tinyint(1) unsigned DEFAULT '1',
  `sequence` int(10) DEFAULT '0',
  `name` varchar(250) DEFAULT NULL,
  `subname` varchar(250) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`class_id`),
  KEY `pid` (`parent_id`),
  KEY `sequence` (`sequence`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='栏目基础信息';

-- ----------------------------
-- Table structure for sm_config
-- ----------------------------
DROP TABLE IF EXISTS `sm_config`;
CREATE TABLE `sm_config` (
  `name` varchar(250) NOT NULL,
  `data` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站配置';

-- ----------------------------
-- Table structure for sm_content
-- ----------------------------
DROP TABLE IF EXISTS `sm_content`;
CREATE TABLE `sm_content` (
  `content_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `class_id` int(10) DEFAULT NULL COMMENT '栏目ID',
  `title` varchar(250) DEFAULT NULL COMMENT '标题',
  `author` varchar(64) NOT NULL COMMENT '作者',
  `urltitle` varchar(250) DEFAULT NULL COMMENT 'URL路径',
  `description` varchar(250) DEFAULT NULL COMMENT '描述',
  `keyword` varchar(255) NOT NULL COMMENT '关键字',
  `time` int(10) DEFAULT NULL COMMENT '更新时间',
  `image` varchar(250) DEFAULT NULL COMMENT '封面图',
  `url` varchar(250) DEFAULT NULL COMMENT '跳转',
  `sequence` int(10) DEFAULT NULL COMMENT '排序',
  `status` int(10) DEFAULT '1' COMMENT '1草稿 2通过 3不通过',
  `views` int(10) DEFAULT '0' COMMENT '浏览数',
  `unique_num` int(11) NOT NULL COMMENT '采集文章唯一标识',
  PRIMARY KEY (`content_id`),
  KEY `title` (`title`) USING BTREE,
  KEY `class_id` (`class_id`) USING BTREE,
  KEY `time` (`time`) USING BTREE,
  KEY `unique_num` (`unique_num`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 COMMENT='内容基础';

-- ----------------------------
-- Table structure for sm_content_article
-- ----------------------------
DROP TABLE IF EXISTS `sm_content_article`;
CREATE TABLE `sm_content_article` (
  `content_id` int(10) unsigned DEFAULT NULL,
  `content` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章内容信息';

-- ----------------------------
-- Table structure for sm_file
-- ----------------------------
DROP TABLE IF EXISTS `sm_file`;
CREATE TABLE `sm_file` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT NULL,
  `original` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `ext` varchar(250) DEFAULT NULL,
  `size` int(10) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  PRIMARY KEY (`file_id`),
  KEY `ext` (`ext`),
  KEY `time` (`time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=816 DEFAULT CHARSET=utf8 COMMENT='上传文件';

-- ----------------------------
-- Table structure for sm_message
-- ----------------------------
DROP TABLE IF EXISTS `sm_message`;
CREATE TABLE `sm_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '快讯标题',
  `content` text COMMENT '快讯内容',
  `image` varchar(255) NOT NULL COMMENT '快讯图片',
  `qr_code` varchar(255) NOT NULL COMMENT '快讯二维码',
  `up` smallint(6) DEFAULT NULL COMMENT '利好',
  `down` smallint(255) DEFAULT NULL COMMENT '利空',
  `state` tinyint(4) DEFAULT NULL COMMENT '1草稿 2通过 3不通过',
  `time` int(11) DEFAULT NULL,
  `unique_num` int(11) NOT NULL COMMENT '金色财经唯一标示',
  PRIMARY KEY (`id`),
  KEY `title` (`title`) USING BTREE,
  KEY `unique` (`unique_num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10473 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sm_navi
-- ----------------------------
DROP TABLE IF EXISTS `sm_navi`;
CREATE TABLE `sm_navi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` smallint(5) unsigned DEFAULT '1' COMMENT '权重  越大越靠前',
  `class_id` tinyint(4) DEFAULT NULL COMMENT '分类',
  `name` varchar(255) DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `target` varchar(255) NOT NULL COMMENT '目标链接',
  `recom` tinyint(3) unsigned DEFAULT '2' COMMENT '1推荐 2不推荐',
  `state` tinyint(3) unsigned DEFAULT '1' COMMENT '1展示 2隐藏 3待审核 4不通过',
  `time` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sm_navi_category
-- ----------------------------
DROP TABLE IF EXISTS `sm_navi_category`;
CREATE TABLE `sm_navi_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '1显示 2 隐藏',
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sm_users
-- ----------------------------
DROP TABLE IF EXISTS `sm_users`;
CREATE TABLE `sm_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录ip',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `regip` varchar(22) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `son_num` tinyint(4) NOT NULL DEFAULT '0' COMMENT '推广用户数',
  `openid` varchar(32) NOT NULL,
  `act_id` int(11) NOT NULL COMMENT '活动id',
  `imtoken` varchar(50) NOT NULL COMMENT 'token钱包',
  `candy` varchar(8) NOT NULL COMMENT '糖果码',
  `candy_ver` tinyint(4) NOT NULL DEFAULT '0' COMMENT '糖果码验证 0 未验证  1 已验证',
  `money_reg` decimal(10,2) NOT NULL COMMENT '用户注册获得金额',
  `money_inv` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '用户推广获得金额',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `candy` (`candy`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for trade_record
-- ----------------------------
DROP TABLE IF EXISTS `trade_record`;
CREATE TABLE `trade_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int(10) unsigned DEFAULT NULL COMMENT '用户的交易所id',
  `oper_type` tinyint(3) unsigned DEFAULT '1' COMMENT '1-股票买入  2-股票卖出 3-股票分红 4-股息扣税',
  `price` decimal(10,3) unsigned DEFAULT '0.000' COMMENT '卖出单价',
  `number` int(10) DEFAULT '0' COMMENT '交易数量（买入为正，卖出为负）',
  `trade_charge` decimal(10,3) unsigned DEFAULT '0.000' COMMENT '交易手续费',
  `oper_time` int(10) unsigned DEFAULT '0' COMMENT '交易时间',
  `add_time` int(10) unsigned DEFAULT '0' COMMENT '记录添加时间',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1-正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_exchange
-- ----------------------------
DROP TABLE IF EXISTS `user_exchange`;
CREATE TABLE `user_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `exchange_name` varchar(63) DEFAULT '0' COMMENT '交易所名称',
  `commission` decimal(5,2) DEFAULT '0.00' COMMENT '佣金',
  `transfer_fee` decimal(5,2) unsigned DEFAULT '0.00' COMMENT '过户费率',
  `stamp_tax` decimal(5,2) unsigned DEFAULT '0.00' COMMENT '印花税率',
  `add_time` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_info
-- ----------------------------
DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` tinyint(3) unsigned DEFAULT NULL COMMENT '1-支付宝 2-微信',
  `alipay_uid` varchar(18) DEFAULT '0' COMMENT '支付宝用户id',
  `alipay_token` varchar(45) DEFAULT '0' COMMENT '支付宝token',
  `wechat_openid` varchar(63) DEFAULT '0' COMMENT '微信标识',
  `phone` varchar(15) DEFAULT '0' COMMENT '电话号码',
  `status` tinyint(4) DEFAULT '1' COMMENT '1-正常 ',
  `reg_time` int(10) unsigned DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`wechat_openid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user_property
-- ----------------------------
DROP TABLE IF EXISTS `user_property`;
CREATE TABLE `user_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `exchange_id` int(10) unsigned DEFAULT NULL COMMENT '交易所ID',
  `stock_code` varchar(16) DEFAULT NULL COMMENT '股票代码',
  `stock_name` varchar(32) DEFAULT NULL COMMENT '股票名称',
  `stock_cost` decimal(7,3) DEFAULT NULL COMMENT '成本单价',
  `stock_price` decimal(7,2) unsigned DEFAULT '0.00' COMMENT '股价',
  `stock_num` int(10) unsigned DEFAULT '0' COMMENT '股票数量',
  `history_profit` decimal(10,2) unsigned DEFAULT NULL COMMENT '历史交易盈利',
  `oper_time` int(10) unsigned DEFAULT '0' COMMENT '最新变动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
