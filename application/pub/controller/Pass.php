<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/28
 * Time: 12:28
 */

namespace app\pub\controller;

use app\common\model\AdminUser;
use app\common\model\SysSset;
use app\common\validate\AdminUser as VAdminUser;
use think\Controller;
use think\Db;

class Pass extends Controller
{
    //TODO 找回密码
    public function index()
    {
        // 进入这个页面自动清除登录信息
        if((session('?token')))
        {
            session('token', null);
        }
        $sys_sset=new SysSset();
        $assign['email_interval_t']=$sys_sset->id_sysval(4);
        return view('/pass',$assign);
    }

    //TODO 验证数据
    public function send_email()
    {
        // 收集数据
        $post=input("post.");
        if(empty($post))
        {
            $data['token']=request()->token();
            $data['error']=lang('page_error');
            return $data;
        }

        // 验证数据
        $validate=new VAdminUser();
        if (!$validate->scene('re_pass')->check($post)) {
            $data['token']=request()->token();
            $data['error']=$validate->getError();
            return $data;
        }

        // 数据库验证用户名+邮箱 取得用户id
        $w[]=['name','=',$post['name']];
        $w[]=['email','=',$post['reemail']]; // 前端验证  -- 名称冲突，重新命名
        $id=Db::name('admin_user')->where($w)->field('id')->find();
        if(empty($id))
        {
            $data['token']=request()->token();
            $data['error']=lang('no_user');
            return $data;
        }
        $data['id']=$id['id'];
        $data['name']=$post['name'];
        $data['email']=$post['reemail'];
        $data["behavior"]='pub_pass';
        // 发送邮件
        return action('pub/Activate/executee_send',['post'=>$data,'sys_sset'=>null]);
    }

    public function repass()
    {
        // 发送邮件后跳转到的页面 取得id
       if(!session('?email_info_t'))
       {
            $this->error(lang('page_error'),'index');
       }
       return $this->fetch('/repass');
    }

    //TODO 重置密码
    public function repass2()
    {
        // 收集数据
        $post=input('post.');
        if(empty($post))
        {
            $this->error(lang('page_error'),'index');
        }

        // 发送邮件后跳转到的页面 取得id
        if(!session('?email_info_t'))
        {
            $this->error(lang('page_error'),'index');
        }

        // 取得加密key-- 解密 email_id 从邮箱传递的ID
        $t=session('email_info_t');
        $crypt_id=session('email_id');
        $u_id=crypt_str($crypt_id,$t,false);
        if(!is_numeric($u_id))
        {
            $this->error(lang('id_error'),'index');
        }

        $data['pass']=$post['newpass'];
        $data['pass2']=$post['newpass2'];
        $data['__token__']=$post['__token__'];

        // 验证数据
        $validate =new VAdminUser();
        $result = $validate->scene('reset_pass')->check($data);
        if(!$result){
            $this->error($validate->getError());
        }

        // 取得原密码
       $admin_user=new AdminUser();
       $old_pass=$admin_user->uid_field($u_id,'pass');
       if($old_pass===$data['pass'])
       {
           $this->success(lang('edit_success'),'Login/index');
       }

        // 更新数据
        $is_up=$admin_user->where('id',$u_id)
            ->setField('pass',encry($data['pass']));

        if($is_up==1)
        {
            $this->success(lang('edit_success'),'Login/index');
        }else
        {
            session('email_info_t',null);
            session('email_id',null);
            $this->error(lang('edit_fail'),'index');
        }
    }
}