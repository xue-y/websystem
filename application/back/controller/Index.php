<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/8
 * Time: 10:49
 * 后台系统首页
 */

namespace app\back\controller;

use app\common\model\AdminPower;
use app\common\model\AdminRole;
use app\common\model\SysSset;
use think\Controller;

class Index extends Controller
{
    //TODO 后台系统主页
    public function main()
    {
       $assign=[];
        // 左边菜单
       $admin_power=new AdminPower();
       $admin_role=new AdminRole();

       // 根据不同权限显示不同的左菜单
       $power=$admin_role->id_power(input('param.r_id'));
       $com_menu=$admin_power->user_com_modular();
       if(empty($power))
       {
           $left_menu=$com_menu;
       }else if($power==config('admin_power'))
       {
           $left_menu=$admin_power->menu();
           $left_menu=array_merge($com_menu,$left_menu);
       }else
       {
           $left_menu=$admin_power->menu($power);
           $left_menu=array_merge($com_menu,$left_menu);
       }
       $assign['menu']=$this->getTree($left_menu);

       $assign['name']=input('param.user_name');
       $sys_sset=new SysSset();
       $assign['back_nav']=$admin_power->ids_power($sys_sset->id_sysval(2));

       // 此账号其他设备终端登录提醒
        $login_user_old=cookie('login_user_old');
        if(!empty($login_user_old))
        {
            $assign['login_user']=date('Y-m-d H:i:s',$login_user_old);
            cookie('login_user_old',null);
            // 取得当前用户ID
            $assign['id']=input('param.user_id');
        }

       return view('/main',$assign);
    }

    //TODO 后台系统 子首页
    public function index()
    {
        return view('/index');
    }

    //TODO 菜单二级分类
    /**层叠嵌套式无限极分类 问题：如果是数据量过大，超出php 内存
     * @param  $list arr
     * @param $pid 数组起始pid
     * @return array 有子级的数组
     * */
    private function getTree($list, $pid = 0)
    {
        $tree = [];
        if (!empty($list)) {
            $newList = [];

            foreach ($list as $k => $v) {
                $newList[$v['id']] = $v;
            }
            foreach ($newList as $value ) {
                if ($pid == $value['pid']) {
                    $tree[] = &$newList[$value['id']];
                } elseif (isset($newList[$value['pid']]))
                {
                    $newList[$value['pid']]['items'][] = &$newList[$value['id']];
                }
            }
            // 如果没有子节点不显示父节点， 此方法只有应用顶级菜单
            foreach ($tree as $k=>$v)
            {
                if(!isset($v['items']) && ($v['pid']<1))
                    unset($tree[$k]);
            }
        }
        $tree=arr_sort($tree);
        return $tree;
    }
}