<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/16
 * Time: 14:02
 */

namespace app\common\validate;


use think\Validate;

class AiPage extends Validate
{
    // message 字段验证信息与 前端 jsonp 语言包变量名一致
    protected $message =[
        'tit.require' =>'tit_empty',
        'tit.length' =>'tit',
        'keyword.max'=>'keyword',
        'description.max'=>'description',
        '__token__'=>'form_token'
    ];
    protected  $rule=[
        'tit' => 'require|length:2,20',
        'keyword' => 'max:20',
        'description' => 'max:50',
        'con'=>'length:10,10000',
        'sort'=>'integer',
        't'=>'date',
        '__token__'=>'require|token'
    ];
}