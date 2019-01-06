<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/16
 * Time: 12:05
 */

namespace app\ai\controller;

use app\common\validate\AiPage as VAiPage;
use app\common\model\AiPage;
use think\Controller;

class Page extends Controller
{
    //TODO 列表页
    public function index()
    {
        $ai_page=new AiPage();
        $list=$ai_page->list_data();
        $this->assign('list',$list);
        return $this->fetch();
    }

    //TODO 添加文档
    public function create()
    {
        return $this->fetch();
    }

    //TODO 执行添加
    public function save()
    {
        $data=$this->form_vail();

        if(empty($data["t"]))
        {
            $data["t"]=date("Y-m-d");
        }

        $ai_page=new AiPage();
        $ai_page->save($data);

        if(!isset($ai_page->id))
        {
            $this->error(lang('create_fail'));
        }else
        {
            // 如果写入成功记录操作记录
            $this->request->record=1;
            $this->request->ai_nav_id=$ai_page->id;
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
        $ai_page=new AiPage();
        $data=$ai_page->id_data($id);
        if(empty($data))
        {
            $this->error(lang('id_error'));
        }
        crypt_id('ai_nav_id',$id);
        $data["con"]=stripslashes($data["con"]);
        $this->assign($data);
        return $this->fetch();
    }

  public function update()
  {
      $data=$this->form_vail();
      $ai_page=new AiPage();
      $id=crypt_id('ai_nav_id','',false);
      $is_update=$ai_page->save($data,['id' => $id]);
      if($is_update!==true)
      {
          $this->error(lang('edit_fail'));
      }else
      {
          // 如果写入成功记录操作记录
          $this->request->record=1;
          $this->request->ai_nav_id=$id;
          $this->success(lang('edit_success'),url('index'));
      }
  }

  //TODO 批量排序
    public function sort()
    {
        $post=request()->post('sort');
        if(!empty($post))
        {
            $ai_page=new AiPage();
            $post=array_filter($post);
            $is_sort=$ai_page->update_sort($post);
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
        $is_del=AiPage::destroy($id);
        if($is_del==true)
        {
            // 如果操作成功记录操作记录
            $this->request->record=1;
            $this->request->ai_nav_id=$id;
            $this->success(lang('del_success'),url('index'));
        }else
        {
            $this->error(lang('del_fail'));
        }
    }

    //TODO 表单验证
    private function form_vail()
    {
        //con 不需要过滤
        $con=request()->post('con','','addslashes');
        $post=request()->post();
        if(empty($post))
        {
            $this->error(lang('page_error'));
        }
        $post["con"]=$con;
        if(isset($post["id"]))
            unset($post["id"]);

        $validate = new VAiPage();
        $result = $validate->check($post);
        if(!$result){
            $this->error($validate->getError());
        }
        return $post;
    }
}