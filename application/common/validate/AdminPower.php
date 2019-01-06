<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/18
 * Time: 14:21
 */

namespace app\common\validate;


use think\Validate;

class AdminPower extends Validate
{
   // message 字段验证信息与 前端 jsonp 语言包变量名一致
    protected $message =[
        'pid.require' =>'pid',
        'mc_name.require' => 'mc_name_empty',
        'mc_name.regex'=>'mc_name',
        'biaoshi_name.length'=>'biaoshi_name',
        'icon.length'=>'icon',
        '__token__'=>'form_token'
    ];
    protected  $rule=[
        'pid' => 'require',
        'mc_name' => 'require|regex:[a-zA-Z\/]{2,20}',
        'biaoshi_name' => 'length:2,20',
        'icon' => 'length:2,20',
        'sort' => 'number',
        '__token__'=>'require|token'
    ];
}