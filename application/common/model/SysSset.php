<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 19:23
 * 用户自定义配置
 */

namespace app\common\model;


use think\Model;

class SysSset extends Model
{
    // 根据id 取得单个字段值-- 设置项
    public function id_sysval($id,$field='sysval')
    {
        return $this->getFieldById($id,$field);
    }

    // 判断是否有设置正在是此分类
    public function is_del($ids)
    {
        $c=strlen($ids);
        if($c==1)
        {
            return $this->where("systid",'=',$ids)->count();
        }else
        {
            $tid=$this->where("systid",'in',$ids)->field("systid as tid")->group("tid")->select()->toArray();
            return implode(",",array_column($tid, 'tid'));
        }
    }

    // 取得某个分类下的设置项
    public function tid($tid)
    {
        return $this->where("systid",'=',$tid)->field('systid',true)->select();
    }

    // 取得某个分类下的设置项指定字段
    public function tid_field($tid,$key,$val)
    {
        return $this->where("systid",'=',$tid)->column($val,$key);
    }

    // 根据ID 取得一条数据
    public function id_data($id)
    {
        return $this->field('id,is_sys',true)->get($id);
    }

    //根据一个字段的值取得另一个字段值
    public function key_val($key,$val='sysval')
    {
        return $this->getFieldBySyskey($key,$val);
    }

}