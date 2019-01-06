<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/16
 * Time: 12:09
 */

namespace app\common\model;


use think\Model;

class AiPage extends Model
{
    public function list_data()
    {
        // 这里一次性取出所有数据，如果数据量超过100 条 改用分页取出
        return $this->field('id,tit,sort')->select();
    }

    // 根据id 取得一条数据
    public function id_data($id)
    {
        return $this->field("id",true)->find($id)->toArray();
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
        $sql="UPDATE `{$prefix}ai_page` 
            SET sort = CASE id 
                $where
            END
            WHERE id IN ($id)";
        return $this->execute($sql);
    }

    //TODO 根据ID 取得指定多个字段
    public function id_fields($id,$fields)
    {
        return $this->field($fields)->find($id);
    }

    //TODO 文章列表
    public function page_list()
    {
        return $this->column('tit','id');
    }

    //TODO 取得所有文章id
    public function page_id_list()
    {
        return  $this->column('id');
    }
}