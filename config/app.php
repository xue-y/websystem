<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 场景配置
    'app_status'=>'home',
    // 应用名称
    'app_name'               => '',
    // 应用地址
    'app_host'               => '',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,
    // 定义404页面
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 => '404.html',
        // 还可以定义其它的HTTP status
    ],
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'         => true,
    //允许访问的语言列表
    'lang_list'              =>['en-us','zh-cn'],
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'strip_tags,addslashes,trim',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common','http'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空模块名
    'empty_module'           => 'error',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法前缀
    'use_action_prefix'      => false,
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => true,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // HTTPS代理标识
    'https_agent_name'       => '',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'         => false,
    // 开启路由
    'url_route_on'  =>  true,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 合并路由规则
    'route_rule_merge'       => false,
    // 路由是否完全匹配
    'route_complete_match'   => false,
    // 使用注解路由
    'route_annotation'       => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动作转换URL中的控制器和操名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => Env::get('APP_PATH') . 'common/view/tips.html',
    'dispatch_error_tmpl'    => Env::get('APP_PATH') . 'common/view/tips.html',

    // 异常页面的模板文件
    'exception_tmpl'         => Env::get('think_path') . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    //自定义样式路径常量
    'com_img'   =>'/static/img/',
    'com_css'   =>'/static/css/',
    'com_js'    =>'/static/js/',
    'back_img'      =>'/static/back/images/',
    'back_css'      =>'/static/back/css/',
    'back_js'      =>'/static/back/js/',
    'back_plus'      =>'/static/back/plugins/',
    'home_img'      =>'/static/home/img/',
    'home_css'      =>'/static/home/css/',
    'home_js'      =>'/static/home/js/',
    // 自定义配置
    'user_only_sign'=>1,//允许多设备终端登录,1 允许，0 禁止
    'lock_screen_val'=>1,// 1 用户锁屏
    'sys_val_inner'=>1, // 系统设置变量是否内置，1 内置不可修改删除

    // 自定义日志路径
     //'log_dir'   =>'D:/phpStudy/WWW/tp/runtime/log/'
    'log_dir'=>Env::get('runtime_path') . 'log/',

    // 默认密码
    'default_pass'=>'123456',
    'default_char'=>'utf8',
    // 加密秘钥
    'secret_key'=>'*./?(&%(*!~',
    //后台系统登录地址
    'login_url'=>'pub/Login/index',
    // 后台首页地址
    'back_default_index'=>'back/Index/main',
    // 后台锁屏地址-->request->path()路由地址    原地址 /back/Lock  ---> /back/LockScreen/index
    'lock_screen'=>'back/Lock',
    // 用户登录即可访问的模块,不需要分配权限
    'user_com_modular'=>'back',
    // 超级管理员 权限集合
    'admin_power'=>["all"],
    //tp 每页数据条数10
    'list_rows'=>10,
    // 记录操作的模块
    'operate_module'=>['admin','ai'],
    // 分配权限时排除的模块
    'except_module'=>['admin'],

];
