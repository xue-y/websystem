<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/7
 * Time: 15:24
 */

namespace app\pub\controller;

use app\common\model\AdminUser;
use app\common\model\SysSset;
use app\common\validate\AdminUser as VAdminUser;
use Crypt\Base64;
use think\Controller;
use think\Request;


class Login extends Controller
{
   //TODO 登录视图页面
   public function index()
   {
       $assign=[];
       // 被迫下线提醒，不允许同一账号多设备登录
       if(!empty(cookie('user_down_info')))
       {
           $user_down_info=cookie('user_down_info');
           $assign['user_down_info']=$user_down_info;
           cookie('user_down_info',null);
       }

       // 登录信息丢失
       if(!empty(cookie('user_login_fail')))
       {
           $user_login_fail=cookie('user_login_fail');
           $assign['user_login_fail']=$user_login_fail;
           cookie('user_login_fail',null);
       }

       // 判断是否登录 如果已经登录直接跳转首页
       $token=session('token');
       if(!empty($token))
       {
         return  redirect(config('back_default_index'));
       }

       // 判断是否记住用户名
       $assign['name']=crypt_web_name();

       return view('/login',$assign);
   }

   //TODO 登录验证页面
   public function sign(Request $request)
   {
       // 验证数据
       $post=$request->post();
       if(empty($post))
       {
           return  redirect(config('login_url'));
       }

       $data = [
           'name' => $post['name'],
           'repass' =>$post['repass'],
           '__token__' => $post['__token__'],
           'v_code' =>$post['v_code'],
       ];
       $validate =new VAdminUser();
       $result = $validate->scene('login')->check($data);
       if(!$result){
           $this->error($validate->getError());
       }

      //数据查询是否有此用户
       $admin_user=new AdminUser();
       $data=$admin_user->name_user($post['name']);
	   if(empty($data))
	   {
		   $this->error(lang('sign_fail'));
	   }

	   // 判断是否锁屏
       action('back/LockScreen/set_lock');
       $new_pass=encry($post['repass']);

       if($data['pass']!==$new_pass)
       {
           // 记录密码错误次数
            action('back/LockScreen/pass_error_num',[$data['id'],false]);
       }else
       {
           // 删除缓存数据
           cache('lockscreen_'.$data['id'],null);
           cache('open_lockscreen_'.$data['id'],null);
       }

       //判断是否记住用户名
       $name_key=Base64::encrypt('name',config('secret_key'));
       $name_val=Base64::encrypt($post['name'],$name_key);

       if(isset($post['re_name']))
       {	   
		  $cookie_prefix_n=config('cookie.cookie_user_n');
          cookie($name_key,$name_val,['expire'=>config('cookie.cookie_user_t'),'prefix'=>$cookie_prefix_n]);
        }else
       {
           cookie($name_key,$name_val);
       }

        //判断用户是否设置同一账号多个设备登录限制
        $time=time();
        $sys_sset=new SysSset();
        $user_only_sign=$sys_sset->id_sysval(1);
        // 值为1 ，一个账号允许多设备终端登录；0 禁止
        if($user_only_sign!=config('user_only_sign'))
        {
            // 先判断是否有人已经登录了
           $login_user_old=cache('login_time'.$data['id']);
           cookie('login_user_old',$login_user_old);
           cookie('login_time',$time);
           cache('login_time'.$data['id'],$time);
        }

       // 存储登录信息
       my_crypt('id',$time,$data);
       my_crypt('r_id',$time,$data);

       session('token',$time);

       // 记录登录操作数据
       $ip=$request->ip(0,true);
       $agent=new \Agent();
       $shebei=$agent->is_Mobile(true);
       if(empty($shebei))
       {
           $shebei='unknown device';
       }
       $data = ['uid' => $data['id'], 't' => date('Y-m-d H:i:s',$time),'shebie'=>$shebei,'ip'=>$ip];
       $is_insert=db('log_login')->insert($data);
       if(empty($is_insert))
       {
           trace(lang('login_user_fecord').implode(' | ',$data),'web_notice');
       }
       // 页面跳转后台首页
       return  redirect(config('back_default_index'));
   }

    //TODO 管理员退出
   public function out()
   {
       $id=my_crypt("id",session('token'),null,null,false);
       cache('login_time'.$id,NULL);
       $cookie_prefix=config('cookie.prefix');
       cookie(null,$cookie_prefix);
       session(null);
       return redirect(config('login_url'));
   }

}