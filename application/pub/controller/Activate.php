<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/27
 * Time: 19:30
 * 从邮箱点击链接进入的页面---邮件激活处理-- 邮箱绑定解除、找回密码
 */
namespace app\pub\controller;

use think\Controller;
use app\common\model\SysSset;
use think\Db;
use think\facade\Cache;

class Activate extends  Controller
{
    /**执行发送邮件
     * @param $post array 传递数据
     * @param $sys_sset 设置变量模型
     * @return array
     * */
    public function executee_send($post,$sys_sset=null)
    {
        // 邮件行为：admin_user_bemail，admin_user_ubemail，pub_pass
        if(empty($post))
        {
            $data['token']=request()->token();
            $data['error']=lang('page_error');
            return $data;
        }

        if(empty($sys_sset))
        {
            $sys_sset=new SysSset();
        }

        // 从设置项中取得smtp 配置数据  这里指定 tid == 3
        $smtp_config=$sys_sset->tid_field(3,'syskey','sysval');

        $smtp_server = $smtp_config["smtp_server"];//SMTP服务器
        $smtp_server_port =intval($smtp_config["smtp_server_port"]);//SMTP服务器端口
        $smtp_user_email = $smtp_config["smtp_user_email"];//SMTP服务器的用户邮箱
        $smtp_email_to =$post['email'];//发送给谁
        $smtp_user = $smtp_config["smtp_user_email"];
        //SMTP服务器的用户帐号(或填写new2008oh@126.com，这项有些邮箱需要完整的)
        $smtp_pass = $smtp_config ["smtp_pass"];//SMTP服务器的用户密码

        $email_title = lang($post['behavior']);//邮件主题
        $link="<a href='".request()->domain()."'>".lang('web_tit').' </a> ['.lang($post['behavior']) .']';

        // 加密信息
        $time=time();
        session('email_info_t',$time); // 用于标识当前用户
        $email_info['id']=$post['id'];
        $email_info['email']=$post["email"];
        $email_info["behavior"]=$post["behavior"];
        $token=crypt_str(json_encode($email_info),$time);
        $tp_url=url('pub/Activate/email_vail',['token'=>$token]);
        $url=request()->domain().$tp_url;
        $con="<a href='".$url."'>".$url."</a>";
        $email_content = $post['name'].lang('email_activate',[$link,$con]);//邮件内容

        $email_type = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        $smtp = new \Smtp($smtp_server,$smtp_server_port,true,$smtp_user,$smtp_pass);
        $smtp->debug = true;//是否显示发送的调试信息,true 显示错误，false 不显示
        $state = $smtp->sendmail($smtp_email_to, $smtp_user_email, $email_title, $email_content, $email_type);

        if($state!==true)
        {
            $data['token']=request()->token();
           // $data['error']=lang('email_send_fail');
            $data['error']=$smtp->error_message;
            return  $data;
        };

        // 开始计时---激活邮件有效期
        $email_activate_t=$sys_sset->id_sysval(22);
        Cache::set('email_activate_'.$post['id'],1,$email_activate_t);

        // 记录发送email_t 时间内邮件次数
        $email_send_num=Cache::get('email_send_num_'.$post['id']);
        if(isset($email_send_num) && !empty($email_send_num))
        {
            Cache::inc('email_send_num_'.$post['id']);
        }else
        {
            $email_t=$sys_sset->id_sysval(21);
            Cache::set('email_send_num_'.$post['id'],1,$email_t);
        }
        $data['token']=request()->token();
        $data['error']='ok';
        return $data;
    }

    // TODO 邮件信息验证
    public function email_vail()
    {
        $history_url=config('login_url');
        // 判断参数
        $token=input('param.token');
        if(empty($token) || (!session('?email_info_t')))
        {
            $this->error(lang('page_error'),$history_url);
            exit;
        }

        // 取得加密key-- 解密
        $t=session('email_info_t');
        $email_info=crypt_str($token,$t,false);
        $email_info=json_decode($email_info,true);
        if(empty($email_info))
        {
            $this->error(lang('page_error'),$history_url);
            exit;
        }

        //根据行为判断邮件过期跳转地址
        if($email_info["behavior"]!=='pub_pass')
        {
            $history_url.='#/back/Uinfo/index';
        }

        // 判断激活邮件有效期
        $u_id=$email_info['id'];
        $email_activate_t=cache('email_activate_'.$u_id);
        if(!isset($email_activate_t) || empty($email_activate_t))
        {
            $this->error(lang('email_expire'),$history_url);
        }
        // 邮件激活时间有效期 删除;
        cache('email_activate_'.$u_id,null);

        // 判断发送邮件次数
        $email_send_num=cache('email_send_num_'.$u_id);
        $sys_sset=new SysSset();
        $email_send_c=$sys_sset->id_sysval(19); // 发送邮件总次数
        $email_t=$sys_sset->id_sysval(21);
        $email_t/=3600;
        if($email_send_num>$email_send_c)
        {
            $this->error(lang('email_send_c',[$email_send_c,$email_t]),$history_url);
            exit;
        }

        // 写入数据库---成功清除缓存
        // 根据行为写入不同的数据 // 邮件行为：admin_user_bemail，admin_user_ubemail，pub_pass
        if($email_info["behavior"] == 'pub_pass' )
        {
            // 跳转重设密码页面
            cache('email_send_num_'.$u_id,null);
            // 加密id 值
            session('email_id',crypt_str($u_id,$t));
            $this->redirect('Pass/repass');
            exit;
        }

        //session　删除
        session('email_info_t',null);

        if($email_info["behavior"] == 'admin_user_bemail')
        {
            $email_val=$email_info['email'];
        }else if($email_info["behavior"] == 'admin_user_ubemail')
        {
            $email_val='';
        }else
        {
            $this->error(lang('page_error'),$history_url);
            exit;
        }

        // 更新数据
        $is_up=Db::name('admin_user')->where('id',$u_id)
            ->setField('email',$email_val);
        if($is_up==1)
        {
            cache('email_send_num_'.$u_id,null);
            $this->success(lang($email_info["behavior"]).lang('success'),$history_url);
        }else
        {
            $this->error(lang($email_info["behavior"]).lang('fail'),$history_url);
        }
        exit;
    }

}