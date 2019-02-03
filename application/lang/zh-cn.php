<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午4:09
 * 需要载入 验证类 公共 jsonp 语言文件，没有的使用 tp 默认提示信息；
 * 邮箱，两次密码是否一致 提示，ip 值为空，前后端没有自定义错误提示信息使用默认的
 * sql 字段注释与视图页面字段一致，左菜单，顶部导航，【页面标题（pub模块）】
 */

$admin_role=[
    "r_n"=>"角色名称",
    "r_d"=>"角色描述",
    "powers"=>"角色权限",
];

$admin_power=[
    "mc_name"=>"模块/控制器名称",
    "biaoshi_name"=>"模块/控制器标识名",
    "pid"=>"权限父级",
    "icon"=>"图标",
    "is_sys"=>"是否系统内置;系统内置不可删除；1不删除,0可以删除"
];
// log_login
$log_login=[
    "uid"=>"登录用户ID",
    "t"=>"登录时间",
    "shebie"=>"登录设备",
    "ip"=>"登录IP",
];
// log_operate
$log_operate=[
    "uid"=>"管理员ID",
    "t"=>"操作时间",
    "behavior"=>"操作行为",
    "details"=>"操作详情",
];

$sys_sset=[
    "systid"=>"设置项类型",
    "syskey"=>"设置项名称",
    "sysval"=>"设置项值;多个值中间用英文逗号分隔",
    "notes"=>"设置项说明",
    "is_sys"=>"是否系统内置;系统内置不可删除；1不删除,0可以删除；添加后不可修改"
];

$sys_stype=[
    "systype"=>"分类名称",
];

$ai_nav=[
    "nav_name"=>"导航名称",
    "nav_biaoshi"=>"导航标识名",
    "is_show"=>"是否显示;1显示,0不显示"
];

$ai_page=[
    "tit"=>"标题",
    "t"=>"时间",
    "con"=>"文章内容"
];

return [
    // 公共部分
    "web_tit"=>"WEB管理系统",
    "close"=>"关闭",
    "cancel"=>"取消",
    "confirm"=>"确认",
    "del_model"=>"您确认要删除吗？",
    "key_word"=>"关键字",
    "describe"=>"描述",
    "sort"=>"排序",
    "role"=>"角色",
    "prompt_message"=>"提示信息",
    "del_file_fail"=>'删除文件失败',
    "page_auto"=>"页面自动",
    "jump"=>"跳转",
    "captcha"=>"验证码错误",
    "form_token"=>"表单提交超时请重新提交",
    "warning"=>"警告！",
    "no_access_rights"=>"您没有访问权限",
    "show"=>"显示",
    "hidden"=>"隐藏",
    "operation_write_fail"=>"操作记录写入失败: 行为 ",
    "submit_save"=>"保存提交",
    "url_back"=>"返回上一页",
    "page_error"=>"页面错误或过期",
    // 页面视图字段
    "admin_role"=>$admin_role,
    "admin_power"=>$admin_power,
    "log_login"=>$log_login,
    "log_operate"=>$log_operate,
    "sys_sset"=>$sys_sset,
    "sys_stype"=>$sys_stype,
    "ai_nav"=>$ai_nav,
    "ai_page"=>$ai_page,
    //方法名称
    "index"=>"首页",
    "main"=>"",
    "create"=>"添加",
    "save"=>"添加",
    "edit"=>"修改",
    "update"=>"修改",
    "delete"=>"删除",
    "deletes"=>"批量删除",
    "operation"=>"操作",
    "update_sort"=>"更新排序",
    "search"=>"搜索",
    //控制器名称
    /*"user"=>"用户",
    "role"=>"角色",
    "power"=>"权限",
    "nav"=>"导航",
    "page"=>"文档",*/
    // 控制器页面
    "id_error"=>"您访问的页面不存在",
    "create_fail"=>"添加失败",
    "create_success"=>"添加成功",
    "edit_fail"=>"修改失败",
    "edit_success"=>"修改成功",
    "update_sort_fail"=>"更新排序失败",
    "update_sort_success"=>"更新排序成功",
    "del_fail"=>"删除失败",
    "delete_fail"=>"删除失败或系统内置不可删除",
    "del_success"=>"删除成功",
    // view  字段
    "fail"=>"失败",
    "success"=>"成功",
    // 公共模块下控制器
    "install"=>"后台管理系统安装向导",
    "pub_login"=>"登录",
    "pub_pass"=>"找回密码",
    "pub_repass"=>"重置密码",
    "pub_activate"=>"激活邮箱重置密码",
    "menu_back_index"=>"后台首页",
    "menu_log_login"=>"登陆记录",
    "login_out"=>"退出登录",
    // log 操作记录
    "log_start_t"=>"开始时间",
    "log_end_t"=>"结束时间",
    "log_n_search"=>"按用户名称",
    "log_data_c"=>"总数据条数：",
    "log_page_c"=>"共 %s 页",
    "log_list"=>"点击查看全部",
    "log_name"=>"登录用户",
    "log_user_del"=>"ID:%s 用户已删除",
    "log_shebei"=>"点击弹出/隐藏详细信息",
    // web LockScreen
    "unlock_screen"=>"请输入登录密码解锁",
    "unlock_error_max"=>"登录密码次数已达上限次数，自动封锁，%d小时候自动解锁",
    "unlock_residue_degree"=>"密码错误，您还有 %s 次机会",
    // admin user
    "n_unique"=>"用户名已存在",
    "admin_user_name"=>"用户名",
    "admin_user_oldpass"=>"原密码",
    "admin_user_pass"=>"密码",
    "admin_user_pass2"=>"确认密码",
    "admin_user_pass_confirm"=>"两次密码不一致",
    "oldpass_pass"=>"原密码与新密码不可一致",
    "new_pass"=>"新密码",
    ""=>"",
    ""=>"",
    "email_send"=>"发送邮件",
    "oldpass_error"=>"原密码错误",
    "pass_empty_info"=>"密码为空默认不修改密码",
    "name_TakeEffect"=>"如果修改当前用户名下次登录生效",
    "admin_user_email"=>"邮箱",
    "admin_user_bemail"=>"绑定邮箱",
    "admin_user_ubemail"=>"解除绑定",
    "email_activate"=>"用户你好，您在 %s，请点此链接 %s 激活邮箱，如果非本人操作请不要点击此链接",
    "email_send_fail"=>"邮件发送失败",
    "email_again_send"=>"重新发送",
    "email_send_success"=>"进入邮箱",
    "email_expire"=>"邮件已失效，请重新绑定激活",
    "email_send_c"=>"今日发送邮件已达到最大限度 %d 次，请 %d 小时后在继续操作",
    "email_alter_info"=>"如果收件箱没有收到邮件请稍后查看或查看垃圾箱"
];