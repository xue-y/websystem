<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/22
 * Time: 10:21
 * 限制不可添加超级管理员用户
 * 不可将用户角色修改为超级管理员
 * 不可删除角色是超级管理员用户
 * 当前用户、超级管理员用户不可修改自己的角色，修改密码需要原密码
 */

namespace app\admin\controller;

use app\common\model\AdminRole;
use app\common\model\AdminUser;
use app\common\validate\AdminUser as VAdminUser;
use think\Controller;

class User extends Controller
{
    //TODO 列表
    public function index()
    {

        $admin_role=new AdminRole();
        $assign['role']=$admin_role->list_data(); // 所有角色
        $assign['all_rid']=$admin_role->all_rid();; // 超级管理员角色ID
        // 所有用户
        $admin_user=new AdminUser();
        $assign['list']=$admin_user->list_data();
        $this->assign($assign);
        return $this->fetch();
    }

    //TODO 添加
    public function create()
    {
        $admin_role=new AdminRole();
        $assign['role']=$admin_role->list_data(); // 所有角色

        // 限制不可添加超级管理员角色---
        $all_rid=$admin_role->all_rid();
        unset($assign['role'][$all_rid]);

        $this->assign($assign);
        return $this->fetch();
    }

    //TODO 执行添加
    public function save()
    {
        $admin_user=new AdminUser();
        $post=$this->form_vail($admin_user,'create');
        //密码判断
        if(empty($post["pass"]))
        {
            $post["pass"]=encry(config('default_pass'));
        }else
        {
            $post["pass"]=encry($post["pass"]);
        }

        $is_save=$admin_user->save($post);
        $this->jump_page($is_save,$admin_user->id,'create');
    }

    //TODO 进入修改
    public function edit()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        $admin_user=new AdminUser();

        // 当前用户自己的不可以修改角色-- 如果修改密码需要原密码
       if($id==$this->request->user_id)
        {
            $field='name';
            $assign['is_notedit']=true;
        }else
        {
            //取得用户名与角色id
            $field='name,r_id';
        }
        $data=$admin_user->id_fields($id,$field);
        if(empty($data))
        {
            $this->error(lang('id_error'));
        }

        // 判断是不是超级管理员、-- 如果添加多个超级管理员限制超级管理员的角色不可修改
        $admin_role=new AdminRole();
        $all_rid=$admin_role->all_rid(); // 超级管理员角色ID
        if(isset($data['r_id']) && ($data['r_id']==$all_rid))
        {
            unset($data['r_id']);
            $assign['is_notedit']=true;
        }

        crypt_id('user_id',$id);
        $assign['data']=$data;

        $assign['role']=$admin_role->list_data(); // 所有角色
        unset($assign['role'][$all_rid]); // 移除超级管理员

        $this->assign($assign);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        $id=crypt_id('user_id',null,false);

        //判断修改的用户是不是自己-- 如果后期分配权限时排除的模块取消，用于判断其他用户
        $admin_user=new AdminUser();
        if(($id==$this->request->user_id))
        {
            $oldpass=true;
            $post=$this->form_vail($admin_user,'update',$id);
        }else
        {
            // 判断是不是超级管理员---  根据用户原来的角色判断
            $u_rid=$admin_user->uid_field($id,'r_id');// 用户原来的角色
            $admin_role=new AdminRole();
            $all_rid=$admin_role->all_rid(); // 超级管理员角色ID

            if($u_rid==$all_rid)
            {
                $oldpass=true;  // 如果修改的是超级管理员角色
                 $post=$this->form_vail($admin_user,'update',$id);
            }else
            {
                $oldpass=false;
                $post=$this->form_vail($admin_user,'create',$id);
            }
        }

        // 判断密码
        if($oldpass===true)
        {
            // 如果修改的是当前自己的或者 超级管理员的
            if(isset($post['r_id']))
                unset($post['r_id']);

            // 需要原密码
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
                $post["pass"]=encry($post["pass"]);
            }else
            {
                unset($post["pass"]);
            }
        }else
        {
           if(!empty($post['pass']))
               $post["pass"]=encry($post["pass"]);
           else
               unset($post["pass"]);
        }

        $is_save=$admin_user->save($post,['id'=>$id]);
        $this->jump_page($is_save,$id,'edit');
    }

    //TODO 删除
    public function delete()
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        $admin_role=new AdminRole();
        $all_rid=$admin_role->all_rid(); // 超级管理员角色ID

        $admin_user=new AdminUser();

        if(is_array($id))
        {
            $id=array_unique(array_filter($id));
            if(empty($id))
                $this->redirect('index');

            // 取得所有用户的角色id
            $del_id=$admin_user->id_fields_num($id);
            foreach ($del_id as $k=>$v)
            {
                if($v==$all_rid)
                    unset($del_id[$k]);  // 如果是超级管理移除
            }
            if(empty($del_id))
                $this->redirect('index');

            $id=implode(',',array_keys($del_id));

        }else
        {
            // 判断删除的是不是超级管理员
            $u_rid=$admin_user->uid_field($id,'r_id'); // 用户的角色
            if($u_rid==$all_rid)
                $this->redirect('index');
        }

        $is_del=AdminUser::destroy($id);
        $this->jump_page($is_del,$id,'del');
    }

    //TODO 收集验证数据
    private function form_vail($module,$scene,$id=null)
    {
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }

        // 验证数据
        $validate=new VAdminUser();
        if (!$validate->scene($scene)->check($post)) {
            $this->error($validate->getError());
        }

        // 限制不可添加/修改为 超级管理员角色
        $admin_role=new AdminRole();
        if(isset($post['r_id']) && ($post['r_id']==$admin_role->all_rid()))
        {
            $this->error(lang('role_select'));
        }

        //判断用户名是否唯一
        $n_unique=$module->n_unique($post['name'],$id);
        if($n_unique>=1)
        {
            $this->error(lang('n_unique'));
        }

        // 过滤字段
        if(isset($post['id']))
            unset($post['id']);

         return $post;
    }


    /**操作成功失败 跳转页面
     * @param $is_save 执行结果
     * @param $id 操作完成的ID
     * @return void 跳转页面
     * */
    private function jump_page($is_save,$id,$meg)
    {
        if($is_save!==true)
        {
            $this->error(lang($meg.'_fail'));
        }
        // 如果写入成功记录操作记录
        $this->request->record=1;
        $this->request->operation_id=$id;
        $this->success(lang($meg.'_success'),url('index'));
    }

}