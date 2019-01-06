<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/27
 * Time: 14:16
 */

namespace app\back\controller;

use app\common\model\AdminUser;
use app\common\model\SysSset;
use app\common\validate\AdminUser as VAdminUser;
use think\Controller;

class Uinfo extends Controller
{
    //TODO 视图页面
    public function index()
    {
        $admin_user=new AdminUser();

        $sys_sset=new SysSset();
       // $assign['email_interval_t']=$sys_sset->key_val('email_interval_t');
        $assign['email_interval_t']=$sys_sset->id_sysval(4);
        // 取得邮箱
        $assign['email']=$admin_user->uid_field(request()->user_id,'email');
        return view('/Uinfo',$assign);
    }

    //TODO 绑定解绑邮件
    public function send_email()
    {
        $post=input("post.");
        if(empty($post))
        {
            $data['token']=request()->token();
            $data['error']=lang('reemail_empty');
            return $data;
        }

        // 判断是否超过发送邮件总次数
        $sys_sset=new SysSset();
        $email_send_c=$sys_sset->id_sysval(19); // 发送邮件总次数
        $email_send_num=cache('email_send_num_'.request()->user_id);
        if(is_numeric($email_send_num) && ($email_send_num>=$email_send_c))
        {
            $email_t=$sys_sset->id_sysval(21);
            $data['token']=request()->token();
            $data['error']=lang('email_send_c',[$email_send_c,$email_t]);
            return $data;
        }

        // 验证数据
        $validate =new VAdminUser();
        $result = $validate->scene('b_email')->check($post);
        if(!$result){
            // 生成新的token;
            $data['token']=request()->token();
            $data['error']=$validate->getError();
            return $data;
        }

        $data['name']=request()->user_name;
        $data['id']=request()->user_id;
        $data['email']=$post['email'];
        $data['behavior']=$post['behavior'];

        // 执行发送邮件
      // return $this->executee_send($data,$sys_sset);
        return action('pub/Activate/executee_send',['post'=>$data,'sys_sset'=>$sys_sset]);
    }

    // TODO 更新用户信息
    public function save()
    {
        $post=input("post.");
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        // 验证数据
        $validate=new VAdminUser();
        if (!$validate->scene('update')->check($post)) {
            $this->error($validate->getError());
        }

        $admin_user=new AdminUser();
        $id=request()->user_id;
        $data=[];
        // 修改密码
        if(!empty($post['oldpass']) && !empty($post['pass']))
        {
            // 原密码与新密码一致
            if($post['oldpass']===$post['pass'])
            {
                $this->error(lang('oldpass_pass'));
            }
            // 判断原密码是否正确
            $old_pass=$admin_user->uid_field($id,'pass');
            if($old_pass!=encry($post['oldpass']))
            {
                $this->error(lang('oldpass_error'));
            }
            $data["pass"]=encry($post["pass"]);
        }

        // 判断是否修改用户名
        if($post['name']!==request()->user_name)
        {
            $data['name']=$post['name'];
        }

        if(!empty($data))
        {
           $is_save=$admin_user->save($data,['id'=>$id]);
           if($is_save==true)
           {
               $this->error(lang('edit_success').'-'.lang('name_TakeEffect'));
           }else
           {
               $this->error(lang('edit_fail'));
           }
        }
        $this->redirect('index');
    }

}