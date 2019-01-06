<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 21:00
 */

namespace app\common\validate;


use think\Validate;

class SysSset extends Validate
{
   // message 字段验证信息与 前端 jsonp 语言包变量名一致
    protected $message =[
        'systid.require' =>'systid_empty',
        'syskey.require' =>'syskey_empty',
        'syskey.max' => 'syskey',
        'sysval.require' => 'sysval_empty',
        'sysval.regex'=>'sysval',
        'notes.max'=>'notes',
     //   '__token__'=>'form_token'
    ];
    protected  $rule=[
        'systid' => 'require',
        'syskey' => 'require|max:20',
        'sysval' => 'require|length:1,200',
        'notes' => 'max:50',
        '__token__'=>'require|token'
    ];
}