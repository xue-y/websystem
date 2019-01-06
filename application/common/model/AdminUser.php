<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 18:16
 */

namespace app\common\model;


use think\Model;

class AdminUser extends Model
{
    // 根据用户名查询是否存在此用户
    public function name_user($name)
    {
       return $this->where('name','=',$name)->find();
    }

    // 取得name,或字段名取得 一列数据
    public function id_field($field='name')
    {
      return  $this->column($field,'id');
    }

    // 取得所有用户
    public function list_data()
    {
        return $this->field('id,name,r_id')->select();
    }

    //判断用户名是否唯一
    public function n_unique($n,$id=null)
    {
        $w[]=['name','=',$n];
        if(isset($id))
            $w[]=['id','<>',$id];
        return $this->where($w)->count();
    }

    // 根据用户id 取得一个、多个字段 一条数据
    public function id_fields($id,$field)
    {
        return $this->field($field)->find($id);
    }

    // 根据多个id 取得单个字段 返回多条数据
    public function id_fields_num($ids,$field='r_id')
    {
        return $this->whereIn('id',$ids)->column($field,'id');
    }

    // 根据用户id 取得单个字段
    public function uid_field($uid,$field)
    {
       return $this->getFieldById($uid,$field);
    }

}