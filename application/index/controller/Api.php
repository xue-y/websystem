<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2018/12/30
 * Time: 18:11
 */

namespace app\index\controller;


use app\common\model\SysSset;
use Think\ImgWord;

class Api
{
    // 字段分割符
    private $field_separator=PHP_EOL;

    // TODO 取得图片 base64
    public function img_base()
    {
        $file=$_FILES["file"];
        if(empty($file))
        {
            exit(lang('img_up_empty'));
        }
        if($file["error"]!==0)
        {
            $error=$this->file_up_error($file["error"]);
            exit($error);
        }

        $sys_sset=new SysSset();
        $img_size=max(1024*1024,$sys_sset->id_sysval(27));
        if($file["size"]>$img_size)
        {
            exit(lang('img_size_max',[$img_size/1024/1024]));
        }
        // 这里验证的要与 配置文件的一致
        $img_mine=[
            'image/jpeg','image/bmp','image/png'
        ];

        if(!in_array($file['type'],$img_mine))
        {
            exit(lang('img_mime_error'));
        }

        $image_file=$_FILES['file']['tmp_name'];
        $image_info = getimagesize($image_file);

        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        //ajax 返回
        $data['code']='ok';
        $data['data']=$base64_image;
        return $data;
    }

    //TODO 上传图片错误信息
    private function file_up_error($f_error)
    {
        switch($f_error){
            case '1':
                $error =lang('img_up_error_1') ;
                break;
            case '2':
                $error =lang('img_up_error_2');
                break;
            case '3':
                $error =lang('img_up_error_3');
                break;
            case '4':
                $error = lang('img_up_error_4');
                break;
            case '6':
                $error = lang('img_up_error_6');
                break;
            case '7':
                $error = lang('img_up_error_7');
                break;
            case '8':
                $error =lang('img_up_error_8');
                break;
            default:
                $error = lang('img_up_error_9');
        }
        return $error;
    }

    // 通用图片提取
    public function img_word()
    {
        $post=input('post.');
        $img_data=preg_replace('/data\:image\/[\w]+\;base64\,/','',$post['img_data']);
        if(empty($img_data))
        {
            exit(lang('re-upload_picture'));
        };

        $img_word=new \ImgWord();
        if(!empty($post['img_type']) && !empty($post['img_group']))
        {
            $img_type=$img_word->img_type($post['img_group']);
            if(!is_array($img_type) || (!in_array($post['img_type'],array_keys($img_type))))
            {
               exit(lang('img_type_error'));
            }else
            {
                $img_type=$post['img_type'];
            }
        }else
        {
            $img_type=$img_word->img_type_default;
        }

        $img_arr_data['image']=$img_data;
        $result=$this->img_result($img_arr_data,$img_type);

        // 根据img_type 处理返回的数据
        // 判断是不是通用类型
        $img_type_default=$img_word->img_type('default');

        if(in_array($img_type,$img_type_default))
        {
            return $this->result_format($result);
        }
        else{
            $fn_name='result_format_'.$post['img_group'];
            return $this->$fn_name($result['words_result'],$img_type);
        }
    }

    //网络图片提取
    public function net_word()
    {
        $img_data['url']=input('post.url');
        if(empty($img_data))
        {
            exit(lang('re-upload_picture'));
        };
        $result=$this->img_result($img_data,'webimage');
        return $this->result_format($result);
    }

    // 身份证提取
    public function id_word()
    {
        $post=input('post.');
        $img_data['image']=preg_replace('/data\:image\/[\w]+\;base64\,/','',$post['img_data']);
        if(empty($img_data))
        {
            exit(lang('re-upload_picture'));
        };
        $img_data['id_card_side']=$post['id_card_side'];
        $result=$this->img_result($img_data,'idcard');
        return $this->result_format_id($result['words_result']);
    }

    // 图片api 处理请求结果数据
    private function img_result($img_arr_data,$img_type)
    {
        $img_word=new \ImgWord();
        $result=$img_word->img_data($img_arr_data,$img_type);
        $content=json_decode($result['content'],true);
        // 判断是否错误
        if(isset($content['error_code']))
        {
            $error=$img_word->error_meg($content);
            exit(lang($error));
        }
        return $content;
    }

    /** 通用型api 返回数据格式化处理
     * */
    private function result_format($content)
    {
        // 判断是否为空
        if($content["words_result_num"]<1)
        {
            exit(lang('no_text'));
        }
        $words=$content["words_result"];
        $words=array_column($words,'words');
        $data['word']=implode($this->field_separator,$words);
        return $data;
    }

    /** 身份证与其他证件格式化
    */
    private function result_format_id($result,$img_type=null)
    {
        $new_result['word']='';
        foreach ($result as $k=>$v)
        {
            $new_result['word'].=$k.'： '.$v['words'].$this->field_separator;
        }
        return $new_result;
    }

    /** 票据类型返回数据格式化处理
     * */
    private function result_format_bill($result,$img_type)
    {
        $img_word=new \ImgWord();
        // 取得字段名称--转换成文字
        $field_name=$img_word->bill_field_word($img_type);
        $new_result['word']='';
        foreach ($result as $k=>$v)
        {
            if(empty($v))
                continue;
            // $v 数据类型 object[]
            if(is_array($v))
            {
                $child_v='';
                foreach ($v as $kk=>$vv)
                {
                    $child_v.=$this->field_separator.'-----'.$vv['word'].$this->field_separator;
                }
                $v=$child_v;
            }
            //$v 数据类型 string
            $k_name=isset($field_name[$k])?$field_name[$k]:$k;
            $new_result['word'].=$k_name.'： '.$v.$this->field_separator;
        }
        return $new_result;
    }

}