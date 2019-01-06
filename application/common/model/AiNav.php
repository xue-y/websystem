<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 15:46
 */

namespace app\common\model;


use think\Model;

class AiNav extends Model
{
    // TODO is_show 获取器
    /*public function getIsShowAttr($value)
    {
        $status = [0=>lang('hidden'),1=>lang('show')];
        return $status[$value];
    }*/

    //TODO 取得列表数据
    public function list_data()
    {
        return $this->select();
    }

    //TODO 根据ID 取得一条数据
    public function id_data($id)
    {
        return $this->field('id',true)->find($id)->toArray();
    }

    //TODO 更新排序key=id val=sort_val
    public function update_sort($data)
    {
        $prefix=config('database.prefix');
        $id=implode(",",array_keys($data));
        $where="";
        foreach ($data as $k=>$v)
        {
            $where.=" WHEN ".$k. " THEN ".$v;
        }
        $sql="UPDATE `{$prefix}ai_nav` 
            SET sort = CASE id 
                $where
            END
            WHERE id IN ($id)";
       return $this->execute($sql);
    }

    //TODO 导航名是否唯一
    public function nav_name_unique($nav_name,$id=null)
    {
      if(isset($id))
      {
        return $this->where([["nav_name","=",$nav_name],["id","<>",$id]])->count();
      }
      return $this->where("nav_name","=",$nav_name)->count();
    }

    //TODO 根据ID 取得指定多个字段
    public function id_fields($id,$fields)
    {
        return $this->field($fields)->find($id);
    }

    //TODO 栏目列表
    public function nav_list()
    {
        // is_show 1 显示， 0 隐藏
        return $this->where('is_show','=',1)->order('sort,id')->column('nav_biaoshi as nav','id');
    }

    //TODO 取得所有栏目id
    public function nav_id_list()
    {
        return  $this->where('is_show','=',1)->column('id');
    }

}