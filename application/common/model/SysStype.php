<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/17
 * Time: 10:43
 */

namespace app\common\model;


use think\Model;

class SysStype extends Model
{
    public function list_data()
    {
        return $this->select()->toArray();
    }

    // 根据id 取值
    public function ids_data($ids)
    {
        return $this->all($ids);
    }

    // systype 值是否重复
    public function is_unique($id,$systype)
    {
        return $this->where([['systype','=',$systype],['id','<>',$id]])->count();
    }

}