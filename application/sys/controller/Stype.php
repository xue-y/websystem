<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 10:37
 */

namespace app\sys\controller;

use app\common\model\SysSset;
use app\common\model\SysStype;
use think\Controller;
use think\facade\Validate;

class Stype extends Controller
{
    public function index()
    {
        $sys_stype=new SysStype();
        $list=$sys_stype->list_data();
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 进入添加
    public function create()
    {
        return $this->fetch();
    }

    //TODO 执行添加
    public function save()
    {
        $data=$this->data_vail();
        $data=array_unique(array_filter($data));
        foreach($data as $k=>$v)
        {
            if(mb_strlen(trim($v))<2 || mb_strlen(trim($v))>20)
            {
                $this->error(lang('systype_length'));
            }
            $sysdata[]['systype']=$v;
        }
        $sys_stype=new SysStype();
        $is_insert=$sys_stype->insertAll($sysdata);
        if(!isset($is_insert))
        {
            $this->error(lang('create_fail'));
        }else
        {
            $this->success(lang('create_success'),'index');
        }
    }

    //TODO 进入修改页面
    public function edit()
    {
        // js 方式进入页面 修改失败返回页面 ERR_CACHE_MISS
        session_cache_limiter('private');
        // 获取ID 数据
        $id=$this->get_data();

        // 判断数据是否存在
        $sys_stype=new SysStype();
        $data=$sys_stype->ids_data($id);
        if(empty($data))
        {
            $this->error(lang('page_error'));
        }

        $this->assign("data",$data);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        //验证数据
        $data=$this->data_vail();
        $new_data=array_unique(array_filter($data));

        // 判断本次提交的是否重复
        $repeat_arr = array_diff_assoc ($data,$new_data);
        if(!empty($repeat_arr))
        {
            $v=array_values($repeat_arr);
            $this->error($v[0].lang('systype_unique'));
        }

        //验证数据是否合法
        foreach($new_data as $k=>$v)
        {
            if(mb_strlen(trim($v))<2 || mb_strlen(trim($v))>20)
            {
                $this->error(lang('systype_length'));
            }
            $sysdata[$k]['systype']=$v;
            $sysdata[$k]['id']=$k;
        }

        // 验证数据库中的是否重复
        $sys_stype=new SysStype();
        foreach ($sysdata as $k=>$v)
        {
          $is_unique=$sys_stype->is_unique($k,$v["systype"]);
          if($is_unique>0)
          {
              $this->error($v["systype"].lang('systype_unique2'));
          }
        }
        // 批量修改
        $save_all=$sys_stype->saveAll($sysdata);
        if(empty($save_all))
        {
            $this->error(lang('edit_fail'));
        }else
        {
            $this->success(lang('edit_success'),'index');
        };
    }

    //TODO 删除
    public function delete()
    {
        // 获取数据
        $id=$this->get_data();

        // 判断是否有设置项正在使用此分类
        $sys_sset=new SysSset();

        $is_del=$sys_sset->is_del($id);
        if(!empty($is_del))
        {
           $this->error(lang('systype_children',[$is_del]));
        }

        // 执行删除
        $is_del=SysStype::destroy($id);
        if($is_del==true)
        {
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }

    /**@param 验证数据
     * @return array
     * */
    private function data_vail()
    {
        $data=request()->post();
        if(empty($data))
        {
            $this->error(lang('page_error'));
        }

        $is_v=Validate::token($data['__token__'],null,$data);
        if(!$is_v)
        {
            $this->error(lang('form_token'));
        }
        return $data["id"];
    }

    /**@param 获取数据判断
     * @return string
     * */
    private function get_data()
    {
        $id=input('param.id/a');
        if(empty($id))
        {
            $this->error(lang('page_error'));
        }
        return implode(",",$id);
    }
}