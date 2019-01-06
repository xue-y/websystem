<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 15:19
 */

namespace app\common\validate;


use think\Validate;

class AiNav extends Validate
{
// message 字段验证信息与 前端 jsonp 语言包变量名一致
    protected $message =[
        'nav_name.require' =>'nav_name_empty',
        'nav_name.regex' =>'nav_name',
        'nav_biaoshi.max' => 'nav_biaoshi',
        'keyword.max'=>'keyword',
        'description.max'=>'description',
        '__token__'=>'form_token'
    ];
    protected  $rule=[
        'nav_name' => 'require|regex:[a-zA-Z\_\-]{2,20}',
        'nav_biaoshi' => 'max:20',
        'keyword' => 'max:20',
        'description' => 'max:50',
        '__token__'=>'require|token'
    ];

}