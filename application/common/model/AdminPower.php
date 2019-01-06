<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/9
 * Time: 10:00
 */

namespace app\common\model;

use think\Model;

class AdminPower extends Model
{
    /** 取得权限, 默认取得除基本权限所有的权限 这里只取 2 层 --- 后台系统左菜单
     * @param $powers str 默认null ,如果传权限id，根据id 查询
     * @return array
     * */
    public function menu($powers=null)
    {
        $com_id=$this->user_com_modular(true);
        if(!empty($powers))
        {
            return  $this->where('id','in',$powers)->field('is_sys',true)->select()->toArray();
        }else
        {
            return  $this->where([['id','<>',$com_id],['pid','<>',$com_id]])->field('is_sys',true)->select()->toArray();
        }

    }
    // 根据字符串形式 id 取得权限
    public function ids_power($ids)
    {
        return $this->where("id",'in',$ids)->field('mc_name,biaoshi_name,icon')->select();
    }

    /**取得用户登录即可有的权限
     * @param $mid 是否返回 模块id, 默认返回模块所有的权限，true 值返回 模块id
     * @return 模块下所有的权限数据
     * */
    public function user_com_modular($mid=false)
    {
        $m=config('user_com_modular');

        if($mid===true)
        {
            $id=$this->where('mc_name','=',$m)->field('id')->find();
            return $id->id;
        }
        $m_data=$this->where('mc_name','=',$m)->field('is_sys',true)->find()->toArray();

        $c_data=$this->where("pid",'=',$m_data["id"])->field('is_sys',true)->select()->toArray();
        array_unshift($c_data,$m_data);
        return $c_data;
    }

    /** 根据当前的请求 判断当前用户是否有访问权限
     * */
    public function is_mc($mc_name,$r_id)
    {
        $prefix=config('database.prefix');
        $sql="SELECT  `p`.`id` ,  `r`.`powers` 
                FROM  `{$prefix}admin_power`  `p` 
                INNER JOIN  `{$prefix}admin_role`  `r` 
                WHERE  `p`.`mc_name` = '$mc_name'
                AND  `r`.`id` =  '$r_id'
                LIMIT 1";
        return $this->query($sql);
    }

    /** 根据控制器、模块名称取得标识名称或其他字段的值
     * */
    public function mc_info($mc_name,$field='biaoshi_name')
    {
       return $this->where('mc_name','=',$mc_name)->field($field)->find();
    }

    // 权限列表
    public function list_data()
    {
        return $this->field('icon',true)->select()->toArray();
    }

    // 下拉框显示权限列表
    public function list_select()
    {
        return $this->field('biaoshi_name,mc_name,id,pid')->select()->toArray();
    }

    // 根据id 取得数据-- 修改
    public function id_data($id)
    {
        return $this->field('id',true)->find($id);
    }

    // 根据id 查询字段
    public function id_field($id,$field)
    {
        $data=$this->field($field)->find($id);
        return $data[$field];
    }

    //
    /**判断mc_name 在数据表中是否唯一
     * @param $mc_name 需要验证的值
     * @param $id 排除主键ID默认null
     * @return int 查询个数
     * */
    public function mc_name_unique($mc_name,$id=null)
    {
        if(isset($id))
            $c=$this->where([['mc_name','=',$mc_name],['id','<>',$id]])->count();
        else
            $c=$this->where('mc_name','=',$mc_name)->count();
        return $c;
    }

    // 根据ID删除数据
    public function del_data($id)
    {
        $sys_val_inner=config("sys_val_inner");
        // 排除系统内置的id
        $id_arr=$this->where([['is_sys','<>',$sys_val_inner],['id','in',$id]])->field('id')->select()->toArray();
        if(empty($id_arr))
            return -1;
       $id=array_column($id_arr,"id");
       $c=count($id);
        // 删除所选以及所选子级 这里 联动 1 级
       $del_c=$this->whereOr([['id','in',$id],['pid','in',$id]])->delete(); // 返回删除条数
       return $c==$del_c?1:0;
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
        $sql="UPDATE `{$prefix}admin_power` 
            SET sort = CASE id 
                $where
            END
            WHERE id IN ($id)";

        //如果数据非法或者查询错误则返回 false ，否则返回影响的记录数
        return $this->execute($sql);
    }

    // 更新子级mc_name
    public function update_sublevel($id,$new_mc_name)
    {
        // 查询所有的子级 1 级联动
        $sub_level=$this->where('pid','=',$id)->field('mc_name,id')->select()->toArray();
        if(empty($sub_level))
        {
            return true;
        }

        // 如果没有修改模块
       $old_mc_name=substr($sub_level[0]["mc_name"],0,strripos($sub_level[0]["mc_name"],'/'));
        if($old_mc_name==$new_mc_name)
            return true;

        //替换字符
        $w="";
        foreach ($sub_level as $v)
        {
            // 删除原模块
            $mc_arr=explode('/',$v["mc_name"]);
            $action_name=array_pop($mc_arr);

            $v["mc_name"]=$new_mc_name.'/'.$action_name;
            $w.=" WHEN ".$v["id"]. " THEN '".$v["mc_name"]."'";
            $sub_id[]=$v["id"];
        }

        $sub_id=implode(",",$sub_id);
        $prefix=config('database.prefix');
        $sql="UPDATE `{$prefix}admin_power` 
            SET mc_name = CASE id 
                $w
            END
            WHERE id IN ($sub_id)";

        //如果数据非法或者查询错误则返回 false ，否则返回影响的记录数
        return $this->execute($sql);
    }

    /** 取得记录 操作记录模块的 单个字段 与 ID
     * @return array
     * */
    public function top_m_column($filed)
    {
        $operate_module=config('operate_module');
        return $this->where([['pid','=','0'],['mc_name','in',$operate_module]])->column($filed,'id');
    }

    /**取得可以分配的权限
     * @return array
     * */
    public function role_power()
    {
        // 排除公共默认权限 --- 排除不分配的权限
        $com_modular=config('user_com_modular');
        $except_module=config('except_module');

         array_push($except_module,$com_modular);
         $id=$this->mc_id($except_module);
         return $this->field('biaoshi_name as n,id,pid')->whereNotIn('id',$id)->select();
    }

    // 根据模块名称取得id
    public function mc_id($mc_name)
    {
        if(is_array($mc_name))
        {
        $id=$this->where('mc_name','in',$mc_name)->field('id')->select();
        }else
        {
         $id=$this->where('mc_name','=',$mc_name)->field('id')->select();
        }
        if(empty($id))
            return $id;
        $id=$id->toArray();
        return array_column($id,'id');
    }

}