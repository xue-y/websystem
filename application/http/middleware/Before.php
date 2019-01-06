<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 13:43
 */

namespace app\http\middleware;


use app\common\model\AdminPower;
use app\common\model\AdminRole;
use app\common\model\SysSset;

class Before
{
    //TODO  检验登录、权限
    public function handle($request, \Closure $next)
    {
        // 当前模块名称
       $current_m=$request->module();
       $code='captcha';

       if($current_m==='index')
       {
           return $next($request);
       }

        // 如果访问的是安装模块，并且已经定义了路由代表已经安装过了
        if($current_m==='install' && (!empty($request->routeInfo())))
        {
            return redirect('index/index/index');
        }

        if($current_m==='install' &&  empty($request->routeInfo()))
        {
            $request->view_tit=lang("install");
            return $next($request);
        }

       // 不需要登录的模块
       $pub_module=['pub','error'];
       if(in_array($current_m,$pub_module) || $code==$request->path())
       {
           $c=lcfirst($request->controller());
           $request->view_tit=lang('web_tit').'_'.lang($current_m."_".$c);
           return $next($request);
       }

        // 判断是否登录
        $cookie_prefix=config('cookie.prefix');

        if(empty(session('token')))
        {
            // 清除登录信息
            cookie(null,$cookie_prefix);
            session(null);
            cookie('user_login_fail',1);
            return redirect(config('login_url'));
        }

        $request->r_id=my_crypt('r_id',session('token'),null,null,false);
        $request->user_id=my_crypt("id",session('token'),null,null,false);

        // 管理员用户名单独处理
        $request->user_name=crypt_web_name();

        if( empty($request->user_name) || empty($request->r_id) || empty($request->user_id))
        {
            // 清除登录信息
            cookie(null,$cookie_prefix);
            session(null);
            cookie('user_login_fail',1);
            // 如果不存在 false
            cache('login_time'.$request->user_id,NULL);// 如果session 到期清除登录时间记录
            return redirect(config('login_url'));
        }

        // 判断是否允许多用户登录
        $sys_sset=new SysSset();
        $user_only_sign=$sys_sset->id_sysval(1);

        // 值为1 ，一个账号允许多设备终端登录；0 禁止
        if($user_only_sign!=config('user_only_sign'))
        {
            // 先判断是否有人已经登录了
            $login_user=cache('login_time'.$request->user_id);

            if(!empty($login_user) && ($login_user!=session('token')))
            {
                // 清除登录信息
                cookie(null,$cookie_prefix);
                session(null);
                cookie('user_down_info',1);
                return redirect(config('login_url'));
            }
        }

        // 是否锁屏
        $lock=cache('open_lockscreen_'.$request->user_id); // 开启锁屏

        if(!empty($lock) )
        {
            if(!($request->controller()==='LockScreen' && $current_m==='back'))
            return redirect('back/LockScreen/index');
        }

        // 判断是不是超级管理员
        $admin_role=new AdminRole();
        $admin_power=$admin_role->id_power($request->r_id);

        // 是否有权限访问 如果访问的不是公共权限  并且不是超级管理员身份
		  if($current_m!==config('user_com_modular') && (config('admin_power')!==$admin_power))
		  {
            // ["controller"] => string(9) "User.test"  多层控制器
            // 当前控制器 模块名，拼接取得权限id
            $power=$current_m.'/'.str_replace('.','/',$request->controller());

            $admin_power=new AdminPower();
            $is_power=$admin_power->is_mc($power,$request->r_id);//
		
            // 如果 用户访问当前地址 数据库中不存在
            if(empty($is_power))
            {
              return $this->error_url_red($request);
            }
			
            // 判断用户是否有访问权限
            $is_power=$is_power[0];
           // $power_ids=explode(',',$is_power['powers']);
			$power_ids=json_decode($is_power['powers'],true);	

            // 权限id 不在角色权限集合中， 并且不是超级管理员
            if(!in_array($is_power["id"],$power_ids) && ($is_power['powers']!==config('admin_power')))
            {
                return $this->error_url_red($request);
            }
         }

        // 当前位置
        $admin_power=new AdminPower();
        // 当前模块
        $current_m_b=$admin_power->mc_info($current_m);
        $postion_nav['m']=$current_m_b["biaoshi_name"];

        // 当前控制器
        $power=$current_m.'/'.str_replace('.','/',$request->controller());
        $current_c_b=$admin_power->mc_info($power);
        $postion_nav['c']=$current_c_b["biaoshi_name"];
        $postion_nav['c_url']=$power;

        // 当前方法
        $postion_nav['a']=lang($request->action());

        $request->postion_nav=$postion_nav;

        $request->view_tit=$current_m_b["biaoshi_name"].'_'.$current_c_b["biaoshi_name"].$postion_nav['a'];

        // 添加中间件执行代码
        return $next($request);
    }

    /* 错误页面跳转*/
    private function error_url_red($request)
    {
        // 错误提示标识
        cookie('no_access_rights',1);
        return  redirect('error/Error/_empty');
    }
}