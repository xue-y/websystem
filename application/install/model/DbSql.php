<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/6
 * Time: 20:48
 * 安装系统 sql
 */

namespace app\install\model;

class DbSql
{
    private $sql=array();
    public function index($prefix,$charset)
    {
        $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_user` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '".lang('admin_user_name')."',
  `pass` varchar(40) NOT NULL COMMENT '".lang('admin_user_pass')."',
   `r_id` tinyint(3) unsigned NOT NULL COMMENT '".lang('role')."',
  `email` varchar(40) NOT NULL COMMENT '".lang('email')."',
  PRIMARY KEY (`id`),
   UNIQUE KEY `n` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_user')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_role` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `r_n` varchar(20) NOT NULL COMMENT '".lang('admin_role')['r_n']."',
  `r_d` varchar(20) NOT NULL COMMENT '".lang('admin_role')['r_d']."',
  `powers` varchar(255) NOT NULL COMMENT '".lang('admin_role')['powers']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_role')."' AUTO_INCREMENT=1 ;" ;

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}admin_power` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `mc_name` varchar(20) NOT NULL COMMENT '".lang('admin_power')['mc_name']."',
  `biaoshi_name` varchar(20) NOT NULL COMMENT '".lang('admin_power')['biaoshi_name']."',
  `pid` tinyint(3) unsigned NOT NULL COMMENT '".lang('admin_power')['pid']."',
  `icon` varchar(20) NOT NULL COMMENT '".lang('admin_power')['icon']."',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '".lang('sort')."',
  `is_sys` varchar(1) NOT NULL DEFAULT '0' COMMENT '".lang('admin_power')['is_sys']."',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mc` (`mc_name`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_admin_power')."' AUTO_INCREMENT=1 ;";


     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}log_login` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '".lang('log_login')['uid']."',
  `t` datetime NOT NULL COMMENT '".lang('log_login')['t']."',
  `shebie` varchar(100) NOT NULL COMMENT '".lang('log_login')['shebie']."',
  `ip` varchar(20) NOT NULL COMMENT '".lang('log_login')['ip']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_log_login')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}log_operate` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` tinyint(3) unsigned NOT NULL COMMENT '".lang('log_operate')['uid']."',
  `t` datetime NOT NULL COMMENT '".lang('log_operate')['t']."',
  `behavior` tinyint(4)  unsigned  NOT NULL COMMENT '".lang('log_operate')['behavior']."',
  `details` varchar(255) NOT NULL COMMENT '".lang('log_operate')['details']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_log_operate')."' AUTO_INCREMENT=1 ;";


        $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}sys_stype` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `systype` varchar(30) NOT NULL COMMENT '".lang('sys_stype')['systype']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_sys_stype')."' AUTO_INCREMENT=1 ;";

        $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}sys_sset` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `systid` tinyint(3) unsigned NOT NULL COMMENT '".lang('sys_sset')['systid']."',
  `syskey` varchar(20) NOT NULL COMMENT '".lang('sys_sset')['syskey']."',
  `sysval` varchar(255) NOT NULL COMMENT '".lang('sys_sset')['sysval']."',
  `notes` varchar(50) NOT NULL COMMENT '".lang('sys_sset')['notes']."',
  `is_sys` varchar(1) NOT NULL DEFAULT '0' COMMENT '".lang('sys_sset')['is_sys']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET={$charset} COMMENT='".lang('menu_sys_sset')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}ai_nav` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nav_name` varchar(20) NOT NULL COMMENT '".lang('ai_nav')['nav_name']."',
  `nav_biaoshi` varchar(20) NOT NULL COMMENT '".lang('ai_nav')['nav_biaoshi']."',
  `keyword` varchar(20) NOT NULL COMMENT '".lang('keyword')."',
  `description` varchar(50) NOT NULL COMMENT '".lang('description')."',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '".lang('sort')."',
  `is_show` varchar(1) NOT NULL DEFAULT '1' COMMENT '".lang('ai_nav')['is_show']."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_ai_nav')."' AUTO_INCREMENT=1 ;";

     $this->sql[]="CREATE TABLE IF NOT EXISTS `{$prefix}ai_page` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `tit` varchar(20) NOT NULL COMMENT '".lang('ai_page')['tit']."',
  `keyword` varchar(20) NOT NULL COMMENT '".lang('keyword')."',
  `description` varchar(50) NOT NULL COMMENT '".lang('description')."',
  `t` datetime NOT NULL COMMENT '".lang('ai_page')['t']."',
  `con` text NOT NULL COMMENT '".lang('ai_page')['t']."',
  `sort` tinyint(3) unsigned NOT NULL COMMENT '".lang('sort')."',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET={$charset} COMMENT='".lang('menu_ai_page')."' AUTO_INCREMENT=1 ;";


        $this->sql[]="INSERT INTO `{$prefix}admin_power` (`mc_name`, `biaoshi_name`, `pid`, `icon`, `is_sys`) VALUES
('admin', '".lang('menu_admin')."', 0,'icon-user', '1'),
( 'back', '".lang('menu_back')."', 0, 'icon-home', '1'),
( 'sys', '".lang('menu_sys')."', 0, 'icon-settings', '1'),
( 'ai', '".lang('menu_ai')."', 0, 'icon-wrench', '1'),
( 'log', '".lang('menu_log')."', 0, 'icon-calendar', '1'),
( 'admin/User', '".lang('menu_admin_user')."', 1, '', '1'),
( 'admin/Role', '".lang('menu_admin_role')."', 1, '', '1'),
( 'admin/Power', '".lang('menu_admin_power')."', 1, '', '1'),
( 'back/Index', '".lang('menu_back_index')."', 2, '', '1'),
( 'back/Uinfo', '".lang('menu_back_uinfo')."', 2, 'icon-user-follow','1'),
( 'back/Login', '".lang('menu_log_login')."', 2, '', '1'),
( 'back/Operate', '".lang('menu_log_operate')."', 2, '', '1'),
( 'back/LockScreen', '".lang('menu_back_lockScreen')."', 2, 'icon-screen-desktop','1'),
( 'sys/Stype', '".lang('menu_sys_Stype')."', 3, '', '1'),
('sys/Sset', '".lang('menu_sys_Sset')."', 3, '', '1'),
( 'ai/Nav', '".lang('menu_ai_nav')."', 4, '', '1'),
('ai/Page', '".lang('menu_ai_page')."', 4, '', '1'),
( 'ai/Html', '".lang('menu_ai_html')."', 4, '', '1'),
('log/Login', '".lang('menu_log_login')."', 5, '',  '1'),
( 'log/Operate', '".lang('menu_log_operate')."', 5, '', '1');";

        $admin_power=json_encode(config('admin_power'));

        $this->sql[]="INSERT INTO `{$prefix}admin_role` (`id`, `r_n`, `r_d`,  `powers`) VALUES
(1, '".lang('administrator')."', '".lang('all_permissions')."','".$admin_power."');";

        $this->sql[]="INSERT INTO `{$prefix}sys_stype` (`id`, `systype`) VALUES
            (1, '".lang('set_back')."'),
            (2, '".lang('set_time')."'),
            (3, '".lang('set_email')."'),
            (4, '".lang('set_home')."');";

        $this->sql[]="INSERT INTO `{$prefix}sys_sset` (`id`, `systid`, `syskey`, `sysval`, `notes`, `is_sys`) VALUES
(1, 1, 'user_only_sign', '0', '".lang('user_only_sign')."', '1'),
(2, 1, 'back_top_nav', '10,13', '".lang('back_top_nav')."', '1'),
(6, 3, 'smtp_server', '', '".lang('smtp_server')."', '1'),
(7, 3, 'smtp_server_port', '25', '".lang('smtp_server_port')."', '1'),
(8, 3, 'smtp_user_email', '', '".lang('smtp_user_email')."', '1'),
(10, 3, 'smtp_pass', '', '".lang('smtp_pass')."', '1'),
(3, 1, 'pass_error_num', '5', '".lang('pass_error_num')."', '1'),
(17,4, 'web_title', '".lang('menu_ai')."', '".lang('web_title')."', '1'),
(4, 2, 'email_interval_t', '120', '".lang('email_interval_t')."', '1'),
(19, 1, 'email_send_c', '50', '".lang('email_send_c')."', '1'),
(20, 2, 'lock_t', '86400', '".lang('lock_t')."', '1'),
(21, 2, 'email_t', '86400', '".lang('email_t')."', '1'),
(22, 2, 'email_activate_t', '1800', '".lang('email_activate_t')."', '1'),
(18, 4, 'web_foot', '', '".lang('web_foot')."', '1'),
(27, 4, 'img_word_imgsize', '4194304', '".lang('img_word_imgsize')."', '1'),
(28, 4, 'img_word_imgtype', '".lang('img_word_imgtype')."', '', '1')";

        return $this->sql;
    }

}