<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/10
 * Time: 11:23
 * 权限集合中不存储用户基本权限id
 */

namespace app\common\model;


use think\Model;

class AdminRole extends Model
{
    // 设置json类型字段
    protected $json = ['powers'];
    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    // 根据角色id 取得权限id集合
    public function id_power($r_id)
    {
      $power=$this->field('powers')->get($r_id);
      return  $power['powers'];
    }

    public function list_data()
    {
        return $this->column('r_n','id');
    }

    // 根据id 查询一条数据
    public function id_data($id)
    {
        return $this->find($id);
    }

    // 取得超级管理角色ID
    public function all_rid()
    {
        $admin_power=json_encode(config('admin_power'));
        return $this->getFieldByPowers($admin_power,'id');
    }





}