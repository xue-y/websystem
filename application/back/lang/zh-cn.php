<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 8:48
 */
$view=[
    "login_user_info"=>"此账号 %s 在其他设备终端登录，如果不是本人，请修改密码 查看",
    "login_down_info"=>"登录超时请从新登录",
    "lock_error"=>"解屏密码错误",
    "unlock"=>"开锁",
    "home_index"=>"AI站点首页",
    "welcome_back"=>"欢迎回来",
];
$root_path=\think\facade\Env::get('root_path');
$validator = file_get_contents($root_path.'public/static/validator/js/language/zh_CN.jsonp',1);
$validator=substr($validator,strlen('cback('),-1);
$validator=json_decode($validator,true);
return array_merge($view,$validator);