<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/18
 * Time: 9:51
 * 权限的修改、删除只联动 1 级
 */

namespace app\admin\controller;

use app\common\model\AdminPower;
use app\common\validate\AdminPower as VAdminPower;
use think\Controller;

class Power extends Controller
{
    //TODO 列表
    public function index()
    {
        $admin_power=new AdminPower();
        $list=$admin_power->list_data();
        $list=$this->getTree($list);
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 进入添加页面
    public function create()
    {
        $is_sys=explode(";",lang('admin_power')['is_sys']);

        // 取得所有级别
        $admin_power=new AdminPower();
        $list=$admin_power->list_select();
        $list=$this->getTree($list);

        $pid=input('param.pid/d');

        $this->assign(['list'=>$list,'is_sys'=>$is_sys,"pid"=>$pid,]);
        return $this->fetch();
    }

    // TODO 执行添加
    public function save()
    {
        $admin_power=new AdminPower();

        // 获取验证数据
        $data=$this->form_vail($admin_power);

        if(isset($data["id"]))
            unset($data["id"]);

        // 执行添加数据 使用 insert 插入数据字段必须与数据库字段匹配
        $is_save=$admin_power->save($data);

        if($is_save!==true)
        {
            $this->error(lang('create_fail'));
        }else
        {
            // 如果操作成功记录操作记录
            $this->request->record=1;
            $this->request->operation_id=$admin_power->id;
            $this->success(lang('create_success'),url('index'));
        }
    }

    //TODO 进入修改页面
    public function edit()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        // 查询当前数据
        $admin_power=new AdminPower();
        $data=$admin_power->id_data($id);

        if(empty($data))
        {
            $this->error(lang('id_error'));
        }
        $data["id"]=$id;
        if($data["pid"]!==0)
            $data['mc_name']=trim(strrchr($data['mc_name'], '/'),'/');

        // 取得所有级别
        $admin_power=new AdminPower();
        $list=$admin_power->list_select();
        $list=$this->getTree($list);

        $this->assign(['list'=>$list,'data'=>$data]);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        $admin_power=new AdminPower();
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        // 获取验证数据
        $post=$this->form_vail($admin_power,$id);

        // 根据id 判断是否系统内置  根据是否内置排除字段
        $is_sys=$admin_power->id_field($post["id"],'is_sys');

        $data["sort"]=$post["sort"];
        $data["icon"]=$post["icon"];
        $data["biaoshi_name"]=$post["biaoshi_name"];

        if($is_sys!=config('sys_val_inner'))
        {
            $data["pid"]=$post["pid"];
            $data["mc_name"]=$post["mc_name"];
        }
        // 执行修改
        $is_save=$admin_power->save($data,["id"=>$id]);
        if($is_save!==true)
        {
            $this->error(lang('edit_fail'));
        }

        // 更改时 联动更新子级
        $update_sublevel=$admin_power->update_sublevel($id,$post["mc_name"]);
        if(!isset($update_sublevel))
        {
            $this->error(lang('edit_fail'));
        }

        // 如果操作成功记录操作记录
        $this->request->record=1;
        $this->request->operation_id=$id;
        $this->success(lang('edit_success'),url('index'));
    }

    //TODO 排序
    public function sort()
    {
        $post=request()->post('sort');
        if(empty($post))
        {
            $this->redirect("index");
        }
        $post=array_filter($post);

        $admin_power=new AdminPower();
        $is_sort=$admin_power->update_sort($post);
        if(gettype($is_sort)!="integer")
        {
            $this->error(lang('update_sort_fail'));
        }else
        {
            $this->success(lang('update_sort_success'));
        }
    }

    //TODO 删除
    public function delete()
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }
        if(is_array($id))
        {
            $id=implode(",",array_unique(array_filter($id)));
        }

        // 删除数据
        $admin_power=new AdminPower();
        $is_del=$admin_power->del_data($id);
        if($is_del<=0)
        {
            $this->error(lang('delete_fail'));
        }else
        {
            // 如果操作成功记录操作记录
            $this->request->record=1;
            $this->request->operation_id=$id;
            $this->success(lang('del_success'),url('index'));
        }

    }

    //TODO 数组形式 --- 树形结构
    /** 所有的分类
     * @param $array 数组
     * @param $pid ，最高级别,默认为0，输出从pid 级别的数据
     * @param $level 层级，默认0
     * */
    private function getTree($array, $pid =0, $level = 0)
    {
        // 空数组 不在执行
        if(empty($array))
            return;

        //声明静态数组,避免递归调用时,多次声明导致数组覆盖
        static $list = [];
        foreach ($array as $key => $value){

            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['pid'] == $pid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $flg = str_repeat('|一一',$level);
                // 更新 名称值
                $value['biaoshi_name'] = $flg.$value['biaoshi_name'];
                $value['mc_name'] = $flg.$value['mc_name'];

                //把数组放到list中
                $list[] = $value;

                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);

                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getTree($array, $value['id'], $level+1);
            }
        }
        return $list;
    }

    //TODO 表单数据处理
    private function form_vail($module,$id=null)
    {
        // 获取数据并验证
        $data=request()->post();
        if(empty($data))
        {
            $this->error(lang('page_error'));
        }
        $validate = new VAdminPower();

        // 有时提示表单令牌失效，但数据可以修改成功,页面直接跳转到列表
        // session_cache_limiter('private');
        $result = $validate->check($data);
        if(!$result){
            $this->error($validate->getError());
        }

        if(empty($data["biaoshi_name"]))
        {
            $data["biaoshi_name"]=$data['mc_name'];
        }

        // 模块控制器名称单独处理--- 修改系统内置模块控制器是 pid =-1 不在验证
        if(intval($data["pid"])>= 0)
        {
            if($data["pid"]==="0")
            {
                $data['mc_name']=strtolower($data['mc_name']);
            }else
            {
                //分割字符 - 与静态页面一致
                $arr=explode("-",$data["pid"]);
                $data["pid"]=$arr[0];
                $data['mc_name']=$arr[1].'/'.ucwords($data['mc_name']);
            }
        }

        // 判断mc_name 是否唯一
        $is_unique=$module->mc_name_unique($data['mc_name'],$id);
        if($is_unique>0)
        {
            $this->error(lang("mc_name_unique"));
        }

        return $data;
    }
}