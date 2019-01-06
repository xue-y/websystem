<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/15
 * Time: 14:04
 */

namespace app\ai\controller;
use app\common\model\AiNav;
use app\common\validate\AiNav as VAiNav;
use think\Controller;

class Nav extends Controller
{
    //TODO 列表页
    public function index()
    {
        $ai_nav=new AiNav();
        $list=$ai_nav->list_data();
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 添加导航
    public function create()
    {
        $assign=explode(";",lang('ai_nav')['is_show']);
        $assign_tem['tit']=current($assign);
        $this->assign($assign_tem);
        return $this->fetch();
    }

    //TODO 执行添加
    public function save()
    {
        $ai_nav=new AiNav();
        $data=$this->form_vail($ai_nav);
        $ai_nav->save($data);

        if(!isset($ai_nav->id))
        {
            $this->error(lang('create_fail'));
        }else
        {
            // 如果写入成功记录操作记录
            $this->request->record=1;
            $this->request->operation_id=$ai_nav->id;
            $this->success(lang('create_success'),url('index'));
        }
    }

    //TODO 进入修改页面
    public function edit()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
           $this->error(lang('id_error'));
        }
        $ai_nav=new AiNav();
        $data=$ai_nav->id_data($id);
        if(empty($data))
        {
            $this->error(lang('id_error'));
        }
        $assign=explode(";",lang('ai_nav')['is_show']);
        $data['tit']=current($assign);

        crypt_id('operation_id',$id);
        $this->assign($data);
        return $this->fetch();
    }

    //TODO 执行修改
    public function update()
    {
        $ai_nav=new AiNav();
        $id=crypt_id('operation_id','',false);
        $data=$this->form_vail($ai_nav,$id);
        $is_update=$ai_nav->save($data,['id' => $id]);
       // $is_update=$ai_nav::update($data,['id'=>$id]); // 返回模型实例
        if($is_update!==true)
        {
            $this->error(lang('edit_fail'));
        }else
        {
            // 如果写入成功记录操作记录
            $this->request->record=1;
            $this->request->operation_id=$id;
            $this->success(lang('edit_success'),url('index'));
        }
    }

    //TODO 批量排序
    public function sort()
    {
        // 更新排序
        $post=request()->post('sort');
        if(!empty($post))
        {
            $ai_nav=new AiNav();
            $post=array_filter($post);
            $is_sort=$ai_nav->update_sort($post);
            if(gettype($is_sort)!="integer")
            {
                $this->error(lang('update_sort_fail'));
            }else
            {
                $this->success(lang('update_sort_success'));
            }
        }
         $this->redirect("index");
    }

    //TODO 删除
    public function delete()
    {
        $id=input('param.id');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }
        if(is_array($id))
        {
            $id=implode(",",array_unique(array_filter($id)));
        }
        $this->del($id);
    }

    //TODO 执行删除
    private function del($id)
    {
        $is_del=AiNav::destroy($id);
        if($is_del==true)
        {
            // 如果操作成功记录操作记录
            $this->request->record=1;
            $this->request->operation_id=$id;
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }

    //TODO 表单验证
    private function form_vail($ai_nav,$id=null)
    {
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        if(isset($post["id"]))
            unset($post["id"]);

        $validate = new VAiNav();
        $result = $validate->check($post);
        if(!$result){
            $this->error($validate->getError());
        }
        // 判断导航名称是否唯一----网站访问路径
        if(!isset($id))
        {
            $is_unique=$ai_nav->nav_name_unique($post['nav_name']);

        }else
        {
            $is_unique=$ai_nav->nav_name_unique($post['nav_name'],$id);
        }
        if($is_unique>0)
        {
            $this->error(lang('nav_name_unique'));
        }

        if(empty($post['nav_biaoshi']))
            $post['nav_biaoshi']=$post['nav_name'];

        return $post;
    }

}