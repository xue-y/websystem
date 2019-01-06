-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2019 �?01 �?04 �?11:07
-- 服务器版本: 5.5.53
-- PHP 版本: 7.0.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `w_admin_power`
--

CREATE TABLE IF NOT EXISTS `w_admin_power` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `mc_name` varchar(20) NOT NULL COMMENT '模块/控制器名称',
  `biaoshi_name` varchar(20) NOT NULL COMMENT '模块/控制器标识名',
  `pid` tinyint(3) unsigned NOT NULL COMMENT '权限级别',
  `icon` varchar(20) NOT NULL COMMENT '图标',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  `is_sys` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mc` (`mc_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员权限' AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `w_admin_power`
--

INSERT INTO `w_admin_power` (`id`, `mc_name`, `biaoshi_name`, `pid`, `icon`, `sort`, `is_sys`) VALUES
(1, 'admin', '管理员管理', 0, 'icon-user', 2, '1'),
(2, 'back', '后台管理', 0, 'icon-home', 1, '1'),
(3, 'sys', '系统管理', 0, 'icon-settings', 10, '1'),
(5, 'ai', '智能小工具', 0, 'icon-wrench', 3, '1'),
(6, 'admin/User', '管理员用户', 1, '', 1, '1'),
(7, 'admin/Role', '管理员角色', 1, '', 2, '1'),
(8, 'admin/Power', '管理员权限', 1, '', 3, '1'),
(9, 'back/Index', '后台首页', 2, '', 5, '1'),
(10, 'back/Uinfo', '个人信息', 2, 'icon-user-follow', 4, '1'),
(11, 'back/Login', '登陆记录', 2, '', 0, '1'),
(12, 'back/Operate', '操作记录', 2, '', 0, '1'),
(13, 'back/LockScreen', '锁屏', 2, 'icon-screen-desktop', 2, '1'),
(14, 'sys/Stype', '设置分类', 3, '', 1, '1'),
(16, 'sys/Sset', '系统设置', 3, '', 0, '1'),
(18, 'ai/Nav', 'AI导航', 5, '', 11, '1'),
(19, 'ai/Page', 'AI文档', 5, '', 0, '1'),
(33, 'ai/Html', '静态文件', 5, '', 5, '0'),
(32, 'test', '测试模块', 0, 'icon-film', 255, '0'),
(31, 'log/Login', '登录记录', 29, 'login', 5, '1'),
(29, 'log', '日志管理', 0, 'icon-calendar', 10, '1'),
(34, 'test/Ceshib', 'ceshib', 32, '', 7, '0'),
(40, 'test/Ceshib/Aa', 'aa', 34, '', 15, '0'),
(36, 'test/CesChaildren', 'cesChaildren', 32, '', 5, '0'),
(37, 'test/Ceshib/Ceshib', 'cesccbbb', 34, '', 5, '0'),
(39, 'log/Operate', '操作记录', 29, '', 5, '1');

-- --------------------------------------------------------

--
-- 表的结构 `w_admin_role`
--

CREATE TABLE IF NOT EXISTS `w_admin_role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `r_n` varchar(20) NOT NULL COMMENT '角色名称',
  `r_d` varchar(20) NOT NULL COMMENT '角色描述',
  `powers` varchar(255) NOT NULL COMMENT '角色权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员角色' AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `w_admin_role`
--

INSERT INTO `w_admin_role` (`id`, `r_n`, `r_d`, `powers`) VALUES
(1, '超级管理员', '超级管理员拥有全部权限', '["all"]'),
(8, '第三方的说法', '第三方的说法', '["3","14","16","5","18","19","32","33","36","34","40","37"]');

-- --------------------------------------------------------

--
-- 表的结构 `w_admin_user`
--

CREATE TABLE IF NOT EXISTS `w_admin_user` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '用户名',
  `pass` varchar(40) NOT NULL COMMENT '系统用户密码',
  `r_id` tinyint(3) unsigned NOT NULL COMMENT '角色',
  `email` varchar(40) NOT NULL COMMENT '邮箱',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员用户' AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `w_admin_user`
--

INSERT INTO `w_admin_user` (`id`, `name`, `pass`, `r_id`, `email`) VALUES
(1, 'admin', 'a160e10adc3949ba59abbe56e057f20f883edd60', 1, 'php.develop@qq.com'),
(3, 'abcd', 'a160e10adc3949ba59abbe56e057f20f883edd60', 1, ''),
(15, 'aaa', 'a1604297f44b13955235245b2497399d7a93dd60', 20, ''),
(16, '15254', 'a16025f9e794323b453885f5181f1b624d0bdd60', 8, ''),
(13, 'abc', 'a160e10adc3949ba59abbe56e057f20f883edd60', 19, '');

-- --------------------------------------------------------

--
-- 表的结构 `w_ai_nav`
--

CREATE TABLE IF NOT EXISTS `w_ai_nav` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(20) NOT NULL COMMENT '导航名称',
  `nav_biaoshi` varchar(20) NOT NULL COMMENT '导航标识名',
  `keyword` varchar(20) NOT NULL COMMENT '关键字',
  `description` varchar(50) NOT NULL COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  `is_show` varchar(1) NOT NULL DEFAULT '1' COMMENT '是否显示;1显示，0不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='AI导航' AUTO_INCREMENT=32 ;

--
-- 转存表中的数据 `w_ai_nav`
--

INSERT INTO `w_ai_nav` (`id`, `nav_name`, `nav_biaoshi`, `keyword`, `description`, `sort`, `is_show`) VALUES
(31, 'id_word', '身份证识别', '', '', 5, '1'),
(27, 'img_word', '图片提取文字', '', '', 1, '1'),
(25, 'cert_word', '证件号码识别', '证件号码识别', '证件号码识别', 4, '1'),
(28, 'bill_word', '票据识别', '票据识别', '', 3, '1'),
(30, 'net_work', '网络图片提取文字', '网络图片提取文字', '网络图片提取文字', 2, '1');

-- --------------------------------------------------------

--
-- 表的结构 `w_ai_page`
--

CREATE TABLE IF NOT EXISTS `w_ai_page` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `tit` varchar(20) NOT NULL COMMENT '标题',
  `keyword` varchar(20) NOT NULL COMMENT '关键字',
  `description` varchar(50) NOT NULL COMMENT '描述',
  `t` date NOT NULL COMMENT '时间',
  `con` text NOT NULL COMMENT '时间',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='AI文档' AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `w_ai_page`
--

INSERT INTO `w_ai_page` (`id`, `tit`, `keyword`, `description`, `t`, `con`, `sort`) VALUES
(17, '测试文章2', '', '', '2018-12-29', '<span style=\\"font-weight: bold;\\">测试文章内容</span>', 1),
(14, '测试文章', '测试文章', '测试文章', '2018-12-18', '<blockquote>本站属于个人学习交流网站，用于智能提取转换图像、音频，为工作、学习提供便捷</blockquote>', 45),
(18, '修改测试文章', '', '', '2018-12-30', '<span style=\\"background-color: yellow;\\">1234</span>', 1),
(19, '网站介绍', '', '', '2018-12-30', '网站功能介绍，使用介绍', 1),
(20, 'ai 介绍', '', '', '2018-12-30', '<span style=\\"font-weight: bold;\\">图片提取使用说明</span>', 1);

-- --------------------------------------------------------

--
-- 表的结构 `w_log_login`
--

CREATE TABLE IF NOT EXISTS `w_log_login` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '登录用户ID',
  `t` datetime NOT NULL COMMENT '登录时间',
  `shebie` varchar(100) NOT NULL COMMENT '登录设备',
  `ip` varchar(20) NOT NULL COMMENT '登录IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='登陆记录' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `w_log_login`
--

-- --------------------------------------------------------

--
-- 表的结构 `w_log_operate`
--

CREATE TABLE IF NOT EXISTS `w_log_operate` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '管理员ID',
  `t` datetime NOT NULL COMMENT '操作时间',
  `behavior` tinyint(4) unsigned NOT NULL COMMENT '操作行为',
  `details` varchar(255) NOT NULL COMMENT '操作详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='操作记录' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `w_log_operate`
--

-- --------------------------------------------------------

--
-- 表的结构 `w_sys_sset`
--

CREATE TABLE IF NOT EXISTS `w_sys_sset` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `systid` tinyint(3) unsigned NOT NULL COMMENT '设置项类型',
  `syskey` varchar(20) NOT NULL COMMENT '设置项名称',
  `sysval` varchar(255) NOT NULL COMMENT '设置项值;多个值中间用英文逗号分隔',
  `notes` varchar(50) NOT NULL COMMENT '设置项说明',
  `is_sys` varchar(1) NOT NULL DEFAULT '1' COMMENT '是否系统内置;系统内置不可删除；1不删除,0可以删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统设置' AUTO_INCREMENT=29 ;

--
-- 转存表中的数据 `w_sys_sset`
--

INSERT INTO `w_sys_sset` (`id`, `systid`, `syskey`, `sysval`, `notes`, `is_sys`) VALUES
(1, 1, 'user_only_sign', '0', '是否允许同一账号多设备终端同时登陆；1允许，0不允许', '1'),
(2, 1, 'back_nav', '10,13', '后台顶部显示导航ID', '1'),
(6, 3, 'smtp_server', 'smtp.sina.com', '新浪SMTP邮箱服务器', '1'),
(7, 3, 'smtp_server_port', '25', 'SMTP服务器端口', '1'),
(8, 3, 'smtp_user_email', '', 'SMTP服务器的用户邮箱账号', '1'),
(10, 3, 'smtp_pass', '', 'SMTP服务器的用户密码', '1'),
(3, 1, 'pass_error_num', '5', '管理员登录密码错误次数，超过限制自动封锁，lock_t 时间后自动解锁', '1'),
(17, 18, 'web_title', '智能小工具', 'AI 站点网站名称', '0'),
(4, 2, 'email_interval_t', '120', '发送邮件时间间隔，时间单位秒', '1'),
(19, 1, 'email_send_c', '50', 'email_t 时间内可发送的邮件次数', '1'),
(20, 2, 'lock_t', '0', '登录密码错误解封时间', '1'),
(21, 2, 'email_t', '86400', '邮件发送超过最大限额多长时间后可以再次发送，单位是秒', '1'),
(22, 2, 'email_activate_t', '1800', '发送邮件后邮件有效时间内激活', '1'),
(18, 18, 'web_foot', '个人学习交流网站<br/>有问题请联系 <a href=\\"https://mail.qq.com\\">php.develop@qq.com</a>', '网站底部', '0'),
(24, 21, '111', '1111', '', '0'),
(26, 21, 'dsfs', 'dsfds', 'cese', '0'),
(27, 18, 'img_word_imgsize', '4194304', '图片提取文字上传图片的大小限制，单位字节', '0'),
(28, 18, 'img_word_imgtype', 'PNG,JPG,JPEG,BMP', '图片提取文字允许的图片类型', '0');

-- --------------------------------------------------------

--
-- 表的结构 `w_sys_stype`
--

CREATE TABLE IF NOT EXISTS `w_sys_stype` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `systype` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='设置分类' AUTO_INCREMENT=22 ;

--
-- 转存表中的数据 `w_sys_stype`
--

INSERT INTO `w_sys_stype` (`id`, `systype`) VALUES
(1, '后端'),
(2, '时间限制'),
(21, '4445'),
(3, '邮件服务器配置'),
(18, 'AI前端');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
