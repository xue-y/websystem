<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 16:38
 */

namespace app\common\validate;
use think\Validate;

class AdminUser extends Validate
{
    // message 字段验证信息与 前端 jsonp 语言包变量名一致  默认登录
    protected $message =[
        'name.require' =>'name_empty',
        'name.length' =>'name',
        'repass.regex' =>'pass',
        'v_code.require' => 'v_code_empty',
        'v_code.regex' => 'v_code',
        'v_code.captcha'=>'captcha',
        '__token__'=>'form_token',
        'oldpass.regex' =>'pass',
        'pass' =>'pass',
        'pass.confirm'=>'admin_user_pass_confirm',
        'r_id'=>'r_n_empty',
        'reemail.require'=>'reemail_empty',
        'reemail.email'=>'reemail',

    ];
    protected  $rule=[
        'name' => 'require|length:2,20',
        'repass' => 'require|regex:[\w\.\@\-]{6,20}',
        '__token__'=>'require|token',
        'v_code'=>'require|regex:[\w]{5}|captcha',
        'oldpass' => 'regex:[\w\.\@\-]{6,20}',
        'pass' => 'confirm:pass2|regex:[\w\.\@\-]{6,20}',
        'r_id'=>'require|integer',
        'email'=>'email',
        'reemail'=>'require|email'

    ];
    protected $scene = [
       'login'=>['name','__token__','repass','v_code'],
       'create' => ['name','__token__','pass','pass2','r_id'],
       'update'=>['name','__token__','pass','pass2','oldpass'],
       'unlock'=>['__token__','repass'],
        're_pass'=>['__token__','reemail','name','v_code']
    ];

    //TODO 邮箱绑定解绑
    public function sceneB_email()
    {
        return $this->only(['__token__','email'])->append('email', 'require');
    }

    //TODO 重设密码
    public function sceneReset_pass()
    {
        return $this->only(['__token__','pass','pass2'])->append('pass', 'require');
    }
}