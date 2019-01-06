<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/10
 * Time: 15:16
 */

namespace app\admin\controller;

use app\common\model\AdminPower;
use app\common\model\AdminRole;
use think\Controller;
use think\facade\Validate;

class Role extends Controller
{
    //TODO 列表
    public function index()
    {
        $admin_role=new AdminRole();
        $list=$admin_role->list_data();
        $this->assign('all_rid',$admin_role->all_rid());
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 进入添加页面
    public function create()
    {
        $admin_power=new AdminPower();
        $powers=$this->getTree($admin_power->role_power());
        $this->assign('powers',$powers);
        return $this->fetch();
    }

    //TODO 执行添加页面
    public function save()
    {
       $data=$this->form_vail();
       if(isset($data['id']))
           unset($data['id']);

        $admin_role=new AdminRole();
        $is_save=$admin_role->save($data);    //->allowField(true) 过滤字段
        $this->jump_page($is_save,$admin_role->id,'create');
    }

    //TODO 进入修改页面
    public function edit()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }
        $admin_role=new AdminRole();
        $data=$admin_role->id_data($id);
        if(empty($data))
        {
            $this->error(lang('id_error'));
        }

        // 排除修改自己的--  如果后期分配权限时排除的模块取消 增加一个判断
        if(($data['powers']!==config('admin_power')) && ($data['id']!=$this->request->r_id))
        {
            $admin_power=new AdminPower();
            $powers=$this->getTree($admin_power->role_power(),0,$data['powers']);
            $this->assign('powers',$powers);
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        $data=$this->form_vail();
        if(!isset($data['id']))
        {
            $this->error(lang('id_error'));
        }
        // 判断是不是修改的是不是超级管理员角色
        $admin_role=new AdminRole();
        $all_rid=$admin_role->all_rid();
        if((($data['id']==$all_rid)  || ($data['id']==$this->request->r_id)) && (isset($data['powers'])))
        {
            unset($data['powers']);
        }

        $is_save=$admin_role->save($data,['id'=>$data['id']]);
        $this->jump_page($is_save,$admin_role->id,'edit');
    }

    //TODO 删除
    public function delete()
    {
        $rid=input('param.id');
        if(empty($rid))
        {
            $this->error(lang('id_error'));
        }
        // 移除超级管理 角色ID
        $admin_role=new AdminRole();
        $all_rid=$admin_role->all_rid();
        if(!is_array($rid) && ($rid==$all_rid))
        {
            $this->redirect('index');
        }

        if(is_array($rid))
        {
            $rid=array_unique(array_filter($rid));
            $key = array_search($all_rid, $rid);
            if ($key !== false)
                array_splice($rid, $key, 1);

            if(empty($rid))
            {
                $this->redirect('index');
             }
             $rid=implode(',',$rid);
         }
         $is_del=AdminRole::destroy($rid);
         $this->jump_page($is_del,$rid,'del');
     }

    //TODO 表单数据验证
    public function form_vail()
    {
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        $msg = [
            'r_n.require' => '{%r_n_empty}',
            'r_n.length' => '{%r_n}',
            'r_d.max' => '{%r_d}',
            '__token__'=>'form_token'
        ];
        $validate = Validate::make([
            'r_n' => 'require|length:2,20',
            'r_d' => 'max:20',
            '__token__'=>'require|token'
        ],$msg);

        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }

        if(!empty($post['powers']))
        {
            $post['powers']=array_unique(array_filter($post['powers']));
        }
         return $post;
    }

    // TODO 层级嵌套
    private function getTree($array, $pid =0,$check=[])
    {
        // 空数组 不在执行
        if(empty($array))
            return;

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $html;
        $html.="<ul>";
        foreach ($array as $key => $value){

            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){

                if(!empty($check) && in_array($value['id'],$check))
                {
                    $cheackbox='checked';
                }else
                {
                    $cheackbox='';
                }
                $html.=$temp="<li><input type=\"checkbox\" name=\"powers[]\" value='".$value['id']."' ".$cheackbox ." >".$value['n'];

                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $vv=$this->getTree($array, $value['id'],$check);

                // 如果顶级分类下没有一个下级，删除此分类，此步骤可以省略
                if(empty($vv) && ($pid<1))
                {
                    $html=str_replace($temp,'',$html);
                }
                $html.="</li>\r\n";
            }
        }
        $html.="</ul>\r\n";
        // 删除多余的 ul 标签
        $html=str_replace("<ul></ul>",'',$html);
        return $html;
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