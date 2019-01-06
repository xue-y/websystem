<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 14:11
 */
$view=[
    "nav_name_unique"=>"导航名称已存在",
    "write_html"=>"生成静态文件",
    "write_nav"=>"所有栏目",
    "write_page"=>"所有文档",
    "write_module"=>"当前模块下所有文件",
    "select_file"=>"您没有选择要生成的文件",
    "write_fail"=>"文件生成失败",
    "write_success"=>"文件生成完成",
    "dir_chmod"=>"目录不存在或没有写入权限",
    "file_not"=>"不存在需要生成的文件",
    "file_data_empty"=>"文件数据为空",
    "file_error_log"=>"详细信息请查看日志",
    "is_update_cahce"=>"是否强制更新缓存",
    "page_con_len"=>"内容10 到 10000 个字符之间",
    "home_index"=>"访问动态站点首页",

];
$root_path=\think\facade\Env::get('root_path');
$validator = file_get_contents($root_path.'public/static/validator/js/language/zh_CN.jsonp',1);
$validator=substr($validator,strlen('cback('),-1);
$validator=json_decode($validator,true);
return array_merge($view,$validator);