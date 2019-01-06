<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午4:09
 * 安装视图 控制器返回信息 语言包
 */
return [
    // install view
    "explain"=>"安装前请手动创建空数据库，同时系统会自动清空运行目录 %s
                    语言会自动根据浏览器自动选择，如果列表中不存在本地语言，自动选择简体中文 %s
                    安装时使用的什么语言，数据表中的数据就是什么语言（数据，注释） %s
                    系统用户密码 如果未空 使用默认密码 : %s",
    "lang"=>"系统语言",
    "host"=>"主机名称",
    "port"=>"数据库端口",
    "dbuser"=>"数据库用户名",
    "dbpass"=>"数据库密码",
    "dbname"=>"数据库名称",
    "prefix"=>"数据库表前缀",
    "charset"=>"数据库字符编码",
    "name"=>"系统用户名",
    "pass"=>"系统用户密码",
    "pass2"=>"用户确认密码",
    "install_alter"=>"安装提示信息",
    "default_collation"=>"默认校对集",
    "install_submit"=>"安装系统",
    // 左菜单权限
    "menu_admin"=>"管理员管理",
    "menu_back"=>"后台管理",
    "menu_sys"=>"系统管理",
    "menu_ai"=>"智能小工具",
    "menu_log"=>"日志管理",
    "menu_admin_user"=>"管理员用户",
    "menu_admin_role"=>"管理员角色",
    "menu_admin_power"=>"管理员权限",
    "menu_back_uinfo"=>"个人信息",
    "menu_back_operate"=>"操作记录",
    "menu_back_lockScreen"=>"锁屏",
    "menu_sys_stype"=>"设置分类",
    "menu_sys_custom"=>"自定义设置",
    "menu_sys_sset"=>"系统设置",
    "menu_ai_nav"=>"AI导航",
    "menu_ai_page"=>"AI文档",
    "menu_ai_html"=>"静态文件",
    /*"menu_log_login"=>"登录记录",*/
    "menu_log_operate"=>"操作记录",
    // 数据 设置变量
    "administrator"=>"超级管理员",
    "all_permissions"=>"超级管理员拥有全部权限",
    "user_only_sign"=>"是否允许同一账号多设备终端同时登陆1允许0不允许",
    "back_top_nav"=>"后台顶部导航ID",
    "smtp_server"=>"新浪SMTP邮箱服务器",
    "smtp_server_port"=>"SMTP服务器端口",
    "smtp_user_email"=>"SMTP服务器的用户邮箱账号",
    "smtp_pass"=>"SMTP服务器的用户密码",
    "pass_error_num"=>"管理员登录密码错误次数，超过限制自动封锁，lock_t 时间后自动解锁",
    "web_title"=>"AI 站点网站名称",
    "email_interval_t"=>"发送邮件时间间隔，时间单位秒",
    "email_send_c"=>"email_t 时间内可发送的邮件次数",
    "lock_t"=>"登录密码错误解封时间",
    "email_t"=>"邮件发送超过最大限额多长时间后可以再次发送，单位是秒",
    "email_activate_t"=>"发送邮件后邮件有效时间内激活，单位是秒",
    "web_foot"=>"网站底部",
    "img_word_imgsize"=>"图片提取文字上传图片的大小限制，单位字节",
    "img_word_imgtype"=>"图片提取文字允许的图片类型",
    // 设置类型
    "set_back"=>"后端",
    "set_time"=>"时间设置",
    "set_email"=>"邮件服务器配置",
    "set_home"=>"AI前端",
    ""=>"",
    ""=>"",
    ""=>"",
    ""=>"",
    ""=>"",
    ""=>"",
    ""=>"",
    ""=>"",
];