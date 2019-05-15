<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
//应用公共文件

/** @param $str 要转义的字符
 *  @return  返回转义后的字符
 * */
function trim_str($str)
{
    if(!is_array($str))
    {
        if(!get_magic_quotes_gpc())
        {
            return strip_tags(addslashes(trim($str)));
        }else
        {
            return strip_tags(trim($str));
        }
    }
    foreach($str as $k=>$v)
    {
       $str[$k]=trim_str($v);
    }
    return $str;
}
/*addslashes 反转义字符
 * */
function str_slashes($str)
{
    if(!is_array($str))
    {
      return  stripslashes($str);
    }
    foreach($str as $k=>$v)
    {
        $str[$k]=str_slashes($v);
    }
    return $str;
}

// 密码加密处理
function encry($pass)
{
	// 可以自定义自己的加密方式，字段长度不要超过40
    return md5($pass);
}
//
/**数组数据加密解密
 * @param $secret_name 秘钥名称/标识
 * @param $secret_key 加密秘钥
 * @param $data 加密数据数组值
 * @param $mode 加密方式
 * @param $behavior bool加密TRUE解密FALSE
 * @return  加密void解密返回值
 */
function my_crypt($secret_name,$secret_key,$data,$mode=null,$behavior=true)
{
    switch ($mode)
    {
		// 自定义加密解密方式
        case 'mode':
            if($behavior==true)
            {
                $k=base64_encode($secret_name.$secret_key);
			    $v=base64_encode($data[$secret_name]);
                cookie($k,$v);
            }else
            {
				$k=base64_encode($secret_name.$secret_key);
                $v=cookie($k);
				return base64_decode($v);
            }
        break;
        default:
            if($behavior==true)
            {
                $k=base64_encode($secret_key.$secret_name);
			    $v=base64_encode($data[$secret_name]);
                cookie($k,$v);
            }else
            {
				$k=base64_encode($secret_key.$secret_name);
                $v=cookie($k);
				return base64_decode($v);
            }
    }
}

// 管理员用户名解密
function crypt_web_name()
{
    $n_key=\Crypt\Base64::encrypt("name",config('secret_key'));
    $cookie_prefix_n=config('cookie.cookie_user_n');

	if(Cookie::has($n_key,$cookie_prefix_n))
    {
        $n_val=Cookie::get($n_key,$cookie_prefix_n); // 登录是记住用户名
    }else
    {
        $n_val=Cookie::get($n_key); // 登录时没有记住用户名
    }
	
    if(empty($n_val))
    {
        return null;
    }
    return \Crypt\Base64::decrypt($n_val,$n_key);
}

/** 一次性加密id 值
 * @param  $key 加密字段名
 * @param  $val 加密值
 * @param bool $behavior加密TRUE,解密FALSE默认加密
 * @return 加密void解密值
 */
function crypt_id($key,$val,$behavior=true)
{
    if($behavior===true)
    {
		// 自定义自己的加密方式
        cookie($key,$val);
    }else
    {
		// 自定义自己的解密方式
        $v=cookie($key);
        cookie($key,null);
        return $v;
    }
}

// 加密解密返回字符串
function crypt_str($str,$key,$behavior=true)
{
    if($behavior==true)
    {
         return \Crypt\Think::encrypt($str,$key);

    }else
    {
        return \Crypt\Think::decrypt($str,$key);
    }
}

// 权限数组排序 二维数组 多维不起作用
function arr_sort($data)
{
    foreach ($data as $key => $row)
    {
        $sort[$key] = $row['sort'];
        $id[$key]  = $row['id'];
    }
    array_multisort($sort, SORT_ASC,$id, SORT_ASC,  $data);
    return $data;
}

/** 返回指定api组
 * @param $img_type api组名称
 * @return array
 * */
function img_type_api($img_type)
{
    $img_word=new ImgWord();
    return $img_word->img_type($img_type);
}
