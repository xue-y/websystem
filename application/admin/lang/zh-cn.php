<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-13
 * Time: 下午4:21
 */
$view=[
    "menu_sort"=>"排序只应用于左菜单列表",
    "top_power"=>"顶级权限",
    "mc_name_unique"=>"模块/控制器名称重复",
    "power_del_alter"=>"删除所选权限时子级也将一并删除",
    "is_sys_info"=>"如果父级设置了不是内置，子级设置内置，删除父级的时候子级也将一并删除，设置后不可修改",
    //user
    "default_pass"=>"如果密码为空使用默认密码 %s",
    "role_select"=>"请选择角色",
];
$root_path=\think\facade\Env::get('root_path');
$validator = file_get_contents($root_path.'public/static/validator/js/language/zh_CN.jsonp',1);
$validator=substr($validator,strlen('cback('),-1);
$validator=json_decode($validator,true);
return array_merge($view,$validator);