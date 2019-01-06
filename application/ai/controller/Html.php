<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/29
 * Time: 11:09
 * 生成静态文件 --- 只判断模板是否过期，没有判断模板中引入的文件是否过期
 * 可以选择强制更新缓存
 */

namespace app\ai\controller;

use app\common\model\AiNav;
use app\common\model\AiPage;
use app\common\model\SysSset;
use think\Controller;
use think\facade\Env;

class Html extends Controller
{
    //前置方法
    protected $beforeActionList = [
        'init_td'=>['only'=>'html,html_index,html_nav,html_page'],
        'init_attr'=>['only' => 'html,dir_mkdir,write_nav,write_page']
    ];
    //定义静态文件目录 public 目录下
    public $htmlRoot='/',
            $navDir='/nav/',
            $pageDir='/page/';
    // 默认选中的导航
    private $active_nav_id=27;

    //TODO 初始化属性
    public function init_attr()
    {
        $doc_root=$_SERVER["DOCUMENT_ROOT"];
        $this->htmlRoot=$doc_root.$this->htmlRoot;
        $this->navDir=$doc_root.$this->navDir;
        $this->pageDir=$doc_root.$this->pageDir;
    }

    //TODO 初始化模板数据
     public function init_td()
    {
        // 临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        $sys_sset=new SysSset();
        $assgin['web_title']=$sys_sset->id_sysval('17');
        $assgin['web_foot']=$sys_sset->id_sysval('18');
        $assgin['tit']='';
        $assgin['keyword']='';
        $assgin['description']='';
        $assgin['id']=27;
        $assgin['htmlRoot']=$this->htmlRoot;
        $assgin['navDir']=$this->navDir;
        $assgin['pageDir']=$this->pageDir;

        $sys_sset=new SysSset();
        $img_size=max(1024*1024,$sys_sset->id_sysval(27));
        $img_size/=(1024*1024);
        $assgin['img_size']=$img_size;
        $assgin['img_type']=$sys_sset->id_sysval(28);

        // 取得所有导航
        $ai_nav=new AiNav();
        $assgin['nav']=$ai_nav->nav_list();
        // 取得所有文档
        $ai_page=new AiPage();
        $assgin['page']=$ai_page->page_list();
        $this->assign($assgin);
    }

    //TODO 生成静态文件视图页面
    public function index()
    {
        // 取得所有导航
        $ai_nav=new AiNav();
        $assgin['nav']=$ai_nav->nav_list();
        // 取得所有文档
        $ai_page=new AiPage();
        $assgin['page']=$ai_page->page_list();
        $this->assign($assgin);
        return $this->fetch();
    }

    //TODO 生成静态文件
    public function html()
    {
        // 创建静态文件目录
        $this->dir_mkdir();

        // 提交的数据-- 去除空值
        $post=input('post.');
        $post=array_filter($post);

        if(empty($post))
        {
            $this->error(lang('page_error'));
        }

        // 如果没有栏目或文章
        $type=$post['type'];
        if(($type=='nav' || $type=='page') && (!isset($post[$type])))
        {
            $this->error(lang('file_not'));
        }

        // 生成文件存放数据数组
        $file_arr=[];
        switch ($type)
        {
            case 'index':
                $arr['name']=$this->htmlRoot.'index';
                $arr['data']=$this->html_index(true);
                $arr['templet']='html_index';
                array_push($file_arr,$arr);
                break;
            case 'nav':
                $arr=$this->write_nav($post['nav']);
                $file_arr=array_merge($file_arr,$arr);
                break;
            case 'page':
                $arr=$this->write_page($post['page']);
                $file_arr=array_merge($file_arr,$arr);
                break;
            default:
                $arr['name']=$this->htmlRoot.'index';
                $arr['data']=$this->html_index(true);
                $arr['templet']='html_index';
                array_push($file_arr,$arr);

                // 取得所有导航
                $ai_nav=new AiNav();
                $nav_id=$ai_nav->nav_id_list();
                if(!empty($nav_id))
                {
                    $arr1=$this->write_nav($nav_id);
                    $file_arr=array_merge($file_arr,$arr1);
                }

                // 取得所有文档
                $ai_page=new AiPage();
                $page_id=$ai_page->page_id_list();
                if(!empty($page_id))
                {
                    $arr2=$this->write_page($post['page']);
                    $file_arr=array_merge($file_arr,$arr2);
                }
        }
        // 是否强制更新缓存
        if(isset($post['update']))
        {
            // 写入文件  // 返回错误信息，如果未空，成功，不为空失败
            $error=$this->write_html($file_arr,true);
        }else
        {
            // 写入文件  // 返回错误信息，如果未空，成功，不为空失败
            $error=$this->write_html($file_arr);
        }

        if($error=='')
        {
            $this->success(lang('write_success'));
        }else
        {
            $this->error(lang('write_fail').PHP_EOL.$error.lang('file_error_log'));
        }
    }

    //TODO 首页
    //$html boolean 是否是静态页面，fasle动态，true静态
    public function html_index($html=false)
    {
        if($html==true)
        {
            $assgin['html']=$html;
            $this->assign($assgin);
        }
        return $this->fetch('ai@html/html_index');
    }

    //TODO 栏目
    /**
     * @param $id int 栏目id
     * @param $html boolean 是否是静态页面，fasle动态，true静态
     * @return  boolean|array  静态boolean|动态array
     * */
    public function html_nav($id=null,$html=false)
    {
        if(strtolower($this->request->method())!=='post')
        {
            $id=$this->is_id();
        }
        // 数据库查询导航
        $ai_nav=new AiNav();
        $data=$ai_nav->id_fields($id,'nav_name,nav_biaoshi,keyword,description');
        if(empty($data))
        {
            if($html==true)
                return false;
            else
                $this->error(lang('id_error'));
        }
        $assgin['html']=$html;
        $assgin['id']=$id;
        $assgin['nav_name']=$data['nav_name'];
        $assgin['tit']=$data['nav_biaoshi'].'--';
        $assgin['keyword']=$data['keyword'];
        $assgin['description']=$data['description'];
        $this->assign($assgin);
        // 默认选中的栏目
        if($id==$this->active_nav_id)
        {
            $data['nav_name']='html_index';
        }
        return $this->fetch($data['nav_name']);
    }

    //TODO 单页文档
    /**
     * @param $id 栏目id
     * @param $html 是否是静态页面，fasle动态，true静态
     * @return  boolean|array  静态boolean|动态array
     * */
    public function html_page($id=null,$html=false)
    {
        if(strtolower($this->request->method())!=='post')
        {
            $id=$this->is_id();
        }
        // 数据库查询导航
        $ai_page=new AiPage();
        $data=$ai_page->id_fields($id,'tit,con,keyword,description');
        if(empty($data))
        {
            if($html==true)
                return false;
            else
                $this->error(lang('id_error'));
        }
        // 数据赋值到模板
        $assgin['html']=$html;
        $assgin['con']=$data['con'];
        $assgin['tit']=$data['tit'].'--';
        $assgin['keyword']=$data['keyword'];
        $assgin['description']=$data['description'];
        $this->assign($assgin);
        return $this->fetch('html_page');
    }

    //TODO 判断id 是否存在
    private function  is_id()
    {
        $id=input('param.id/d');
        if(empty($id))
        {
            $this->error(lang('id_error'));
        }
        return $id;
    }

    //TODO 判断目录是否有写入权限
    private function dir_chmod($dir)
    {
        if((!is_dir($dir)) && (!mkdir($dir,0755)))
        {
            return $dir;
        }else if(!is_writable($dir) && (!chmod($dir,0755)))
        {
            return $dir;
        }else
        {
            return true;
        }
    }

    //TODO 生成目录
    private function dir_mkdir()
    {
        // 判断目录是否生成
        $is_dir=$this->dir_chmod($this->navDir);
        if($is_dir!=true)
        {
            $this->error($this->navDir.'<br/>'.lang('dir_chmod'));
        }
        $is_dir=$this->dir_chmod($this->pageDir);
        if($is_dir!=true)
        {
            $this->error($this->pageDir.'<br/>'.lang('dir_chmod'));
        }
    }

    //TODO 写入栏目静态页面
    private function write_nav($nav_id)
    {
        if(!is_array($nav_id))
            $nav_id=explode(',',$nav_id);
        $arr=[];
        foreach ($nav_id as $k=>$v)
        {
            $arr[$k]['name']=$this->navDir.$v;
            $arr[$k]['data']=$this->html_nav($v,true);
            $arr[$k]['templet']='html_nav';
        }
        return $arr;
    }

    //TODO 写入单页文档静态文件
    private function write_page($page_id)
    {
        if(!is_array($page_id))
            $page_id=explode(',',$page_id);
        $arr=[];
        foreach ($page_id as $k=>$v)
        {
            $arr[$k]['name']=$this->pageDir.$v;
            $arr[$k]['data']=$this->html_page($v,true);
            $arr[$k]['templet']='html_page';
        }
        return $arr;
    }

    //TODO 写入文件
    /**
     *@param  $arr 写入文件的数组
     *@param $is_update boolean true强制更新缓存,默认false,不更新缓存
     *@return  string error
     * */
    private function write_html($arr,$is_update=false)
    {
        $error='';
        // 模板路径
        $temp_dir=str_replace('\\','/',Env::get('module_path'));
        $temp_dir.='view/html/';
        //文件名后缀
        $file_suffix='.'.config('url_html_suffix');
        // 判断是否开启缓冲

        // 循环数组
        foreach ($arr as  $k=>$v)
        {
            $v['name'].=$file_suffix;
            if($v['data']===false)
            {
                $error.=$v['name'].PHP_EOL;
                trace($v['name'].lang('file_data_empty'),config('log.web_level'));
                continue;
            }
            // 判断静态文件是否存在
            if(($is_update!=true) && (file_exists($v['name'])))
            {
                // 判断文件是否过期
                $html_f_t=filemtime($v['name']);
                $temp_f_t=filemtime($temp_dir.$v['templet'].$file_suffix);
                if($html_f_t>=$temp_f_t)
                {
                    clearstatcache();
                    continue;
                }
            }
            $is_f=file_put_contents($v['name'],$v['data']);
            if(($is_f===false) || (!file_exists($v['name'])))
            {
                $error.=$v['name'].PHP_EOL;
                trace($v['name'].lang('file_data_empty'),config('log.web_level'));
                continue;
            }
        }
        return $error;
    }
}