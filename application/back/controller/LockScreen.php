<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 16:55
 */

namespace app\back\controller;

use app\common\model\AdminUser;
use app\common\model\SysSset;
use app\common\validate\AdminUser as VAdminUser;
use think\Controller;
use think\facade\Cache;

class LockScreen extends Controller
{
    public function index()
    {
        // 开启锁屏
        $this->set_lock();
        return view('/lock',['name'=>input('param.user_name')]);
    }

    //TODO 解锁
    public function unlock()
    {
        // 收集数据
        $post=input("post.");
        if(empty($post))
        {
            $data['token']=request()->token();
            $data['error']=lang('lock_error');
            $this->error($data);
        }
        // 验证数据
        $validate =new VAdminUser();
        $result = $validate->scene('unlock')->check($post);
        if(!$result){
            // 生成新的token;
            $data['token']=request()->token();
            $data['error']=$validate->getError();
            $this->error($data);
        }
        // 取得原数据
        $u_id=request()->user_id;
        $admin_user=new AdminUser();
        $data_pass=$admin_user->uid_field($u_id,'pass');
        if(empty($data_pass))
        {
            $data['token']=request()->token();
            $data['error']=lang('lock_error');
            $this->error($data);
        }
        //验证密码是否正确
        $new_pass=encry($post['repass']);
        if($data_pass!==$new_pass)
        {
            $this->pass_error_num($u_id);
        }else
        {
            // 清除缓存
            Cache::rm('lockscreen_'.$u_id); // 锁屏次数
            Cache::rm('open_lockscreen_'.$u_id); //锁屏标记
            exit('ok');
        }
    }

    /** 记录登录密码错误次数
     * @param  $id 用户ID
     * @param  $json_format true json 数据false string
     * @return void
     * */
    public function pass_error_num($u_id,$json_format=true)
    {
        $sys_sset=new SysSset();
        // 记录在缓存文件中
        $error_num=Cache::get('lockscreen_'.$u_id);
        if(empty($error_num))
        {
          //  $lock_t=$sys_sset->key_val('lock_t');
            $lock_t=$sys_sset->id_sysval(20);
            Cache::set('lockscreen_'.$u_id,1,$lock_t);
        }else
        {
            Cache::inc('lockscreen_'.$u_id);
        }

        // 从配置数据中查询配置项
        $set_val=$sys_sset->id_sysval(3);//pass_error_num
        // 锁屏时间
        $lock_t=intval($sys_sset->id_sysval(20));
        $lock_t=max(3600,$lock_t)/3600;

        if(($error_num+1)>=$set_val)
        {
            if($json_format!=true)
                $this->error(lang('unlock_error_max',[$lock_t]));
            else
            {
                $data['token']=request()->token();
                $data['error']=lang('unlock_error_max',[$lock_t]);
                $this->error($data);
            }
        }else
        {
            $n=$set_val-($error_num+1);
            if($json_format!=true)
                $this->error(lang('unlock_residue_degree',[$n]));
            else
            {
                $data['token']=request()->token();
                $data['error']=lang('unlock_residue_degree',[$n]);
                $this->error($data);
            }
        }
    }

    // 设置锁屏
    public function set_lock()
    {
        if(!Cache::has('open_lockscreen_'.request()->user_id))
        {
            Cache::set('open_lockscreen_'.request()->user_id,config('lock_screen_val'),config('cache.lock_t'));
        }
    }
}