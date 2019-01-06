<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/30
 * Time: 19:44
 */

namespace app\index\controller;

use app\ai\controller\Html;

class Index
{
    // TODO 访问首页
    public function index()
    {
        //定义文件路径
        $doc_root=$_SERVER["DOCUMENT_ROOT"];
        $index=$doc_root.'/index.html';
        // 判断根目录下index.html 是否存在
        if(!file_exists($index))
        {
            $html=new Html();
            $html->init_attr();
            $html->init_td();
            $index_data=$html->html_index(true);
            // 如果不存在生成
            $is_f=file_put_contents($index,$index_data);
            if(($is_f===false) || (!file_exists($index)))
            {
                return $html->html_index();
            }
        }
        // 载入 文件
       require $index;
    }

    public function test()
    {
    /*    $sql=new DbSql();
        $data=$sql->index('a','utf8');
        dump($data);*/
    }
}