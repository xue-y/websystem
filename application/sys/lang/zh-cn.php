<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 11:41
 */
$view=[
    "systype_length"=>"分类名称长度2-20之间",
    "systype_unique"=>" 值重复",
    "systype_unique2"=>" 值数据库已存在",
    "systype_children"=>"ID： %s 分类有设置项在使用不可删除, 如要删除请先删除分类下的设置项",
    "val_default"=>"如果值为空默认不设置",
    "is_sys_null"=>"设置项不存在",
    "is_sys_nodel"=>"系统内置不可删除",
    "type"=>"请选择类型"
];
$root_path=\think\facade\Env::get('root_path');
$validator = file_get_contents($root_path.'public/static/validator/js/language/zh_CN.jsonp',1);
$validator=substr($validator,strlen('cback('),-1);
$validator=json_decode($validator,true);
return array_merge($view,$validator);