<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 16:45
 */

namespace app\sys\controller;

use app\common\model\SysSset;
use app\common\model\SysStype;
use app\common\validate\SysSset as VSysSset;
use think\Controller;

class Sset extends Controller
{
    public function index()
    {
        // 取出所有分类
        $sys_stype=new SysStype();
        $type_list=$sys_stype->list_data();

        // 默认选中第一个
        $tid=max(1,input('param.tid/d'));

        // 取得当前分类的设置项
        $sys_sset=new SysSset();
        $list=$sys_sset->tid($tid);

        $this->assign(['type_list'=>$type_list,"tid"=>$tid,'list'=>$list]);
        return $this->fetch();
    }

    // TODO 进入修改页面
    public function create()
    {
        // 取出所有分类
        $sys_stype=new SysStype();
        $type_list=$sys_stype->list_data();
        $is_sys=explode(";",lang('sys_sset')['is_sys']);
        $sysval=explode(";",lang('sys_sset')['sysval']);
        $tid=input('param.tid/d');
        $this->assign(['type_list'=>$type_list,'is_sys'=>$is_sys,"sysval"=>$sysval,"tid"=>$tid,]);
        return $this->fetch();
    }

    //TODO 执行添加页面
    public function save()
    {
        // 收集数据 验证数据
        $post=$this->data_collect();
        // 执行添加数据
        $sys_sset=new SysSset();
        // 使用 insert 插入数据字段必须与数据库字段匹配
        $is_save=$sys_sset->save($post);

        if($is_save!==true)
        {
            $this->error(lang('create_fail'));
        }else
        {
            $this->success(lang('create_success'),url('index',['tid'=>$post['systid']]));
        }
    }

    //TODO 进入修改页面
    public function edit()
    {
        $id=input('param.id/d');
        $tid=input('param.tid/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        // 查询指定ID 数据
        $sys_sset=new SysSset();
        $data=$sys_sset->id_data($id);
        if(empty($data))
        {
            $this->error(lang('page_error'));
        }
        // 隐藏域传递id
        $data["id"]=$id;

        // 取出所有分类
        $sys_stype=new SysStype();
        $type_list=$sys_stype->list_data();

        $sysval=explode(";",lang('sys_sset')['sysval']);
        $this->assign(['type_list'=>$type_list,"tid"=>$tid,'data'=>$data,"sysval"=>$sysval]);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        // 收集数据 验证数据
        $post=$this->data_collect(true);
        // 执行修改数据
        $sys_sset=new SysSset();
        $is_update=$sys_sset->save($post,['id' =>$post["_id"]]);

        if($is_update!==true)
        {
            $this->error(lang('edit_fail'));
        }else
        {
            $this->success(lang('edit_success'),url('index',['tid'=>$post['systid']]));
        }

    }

    //TODO 删除
    public function delete()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }

        // 判断是否系统内置
        $sys_sset=new SysSset();
        $id_is_sys=$sys_sset->id_sysval($id,'is_sys');

        if(!isset($id_is_sys))
        {
            $this->error(lang('is_sys_null'));
        }
        if($id_is_sys!=0)
        {
            $this->error(lang('is_sys_nodel'));
        }

        $tid=input('param.tid/d');
        if(empty($id))
        {
            $this->error(lang('page_error'));
        }

        $is_del=SysSset::destroy($id);
        if($is_del!==true)
        {
            $this->error(lang('del_fail'),url('index',["tid"=>$tid]));
        }else
        {
            $this->success(lang('del_success'),url('index',["tid"=>$tid]));
        }
    }

    //TODO 收集数据
    /**
     * @param $is_edit 是否修改 false 添加/true 修改数据
     * */
    private function data_collect($is_edit=false)
    {
        // 获取验证数据
        $sysval=request()->post('sysval','','addslashes');
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        $post['sysval']=$sysval;

        if($is_edit==true)
        {
            $post["_id"]=$post["id"];
            // 排除敏感字段
            unset($post["id"]);
            if(isset($post["is_sys"]))
                unset($post["is_sys"]);
        }else
        {
            if(isset($post["id"]))
                unset($post["id"]);
        }

        // 验证数据
        $validate = new VSysSset();
        $result = $validate->check($post);
        if(!$result){
            $this->error($validate->getError());
        }
        return $post;
    }

}