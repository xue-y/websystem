项目说明
===============  
#### 框架说明     
	核心框架：       framework:                 5.1.28
	助手函数：       think-helper:              1.0.6
	验证码：         think-captcha:             2.0.2
	后端分页插件:    Datatable:                 1.10.7  
	后端模板：       AdminLTE
	数据前端验证:    BootstrapValidator         0.5.3	
	其他一些插件: 
		chart-js 图表     
		summernote 编辑器  
		bootstrap-datepicker 时间	

#### 项目功能 
    application     应用目录  
    ├─install 	系统安装  
    ├─pub           后台基本模块         
    │  ├────Login   登录（登录密码错误超过，设置项变量中定义的pass_error_num 的次数自动锁屏 lock_t 秒）   
    │  ├────Pass    找回密码   
    │  ├──Activate  邮箱管理   
    ├─back          管理员基本操作     
    │  ├────Index   后台首页  
    │  ├────Login   登录记录  
    │  ├────Operate 操作记录  
    │  ├─LockScreen 系统锁屏            
    │  ├────Uinfo   个人信息   
    ├─admin         管理员（RBAC）管理    
    ├─ai            网站管理           
    │  ├────Nav     栏目导航 
    │  ├────Page    网站单页文档
    │  ├────Html    生成静态页面
    ├─log           日志管理
    │  ├────Login   登录记录  
    │  ├────Operate 操作记录    
    ├─sys           系统设置
    │  ├────Stype   设置分类 
    │  ├────Sset    设置项变量定义 
    ├─common        公共模块
    ├─http          中间件
    │  ├─middleware 系统中间件 
    ├─error         错误页面跳转    
    ├─index         前端网站             
    │  ├────Index   网站首页 
    │  ├────Api     前端网站请求百度 Api 文字识别（OCR）接口
  
>各个模块分开独立，方便后期扩展其他模块    
>前后台页面均是自适应，网页布局使用 Bootstrap框架,后台管理系统基于AdminLTE模板，去掉一些不需要的插件，加快了页面加载速度	   

#### 项目运行环境   
配置项中开启了 【自动作转换URL中的控制器和操名】，Win 环境正常访问，如果 Liunx 环境建议改为 false         
php5.6/ php7.0 + Apache/Ngingx 均正常访问，数据库MySql5.5.53，其他版本未测试  
	
#### 项目安装     
直接访问项目目录下的 instll 模块即可，如果安装完成后，再次访问此模块自动跳转到网站前台首页   
域名指向根目录的public目录

#### 后台字段限制   
+ 用户名 name 唯一
+ 模块/控制器名称 mc_name 唯一
+ AI模块导航名称 nav_name 唯一,mc_name 为栏目模板文件名

#### 多语言说明
多语言中的字段分割符统一使用 英文分号 ;           
文件变量中调用全部使用多语言调用，默认是使用的中文语言文件；    
如果添加多语言在/lang/ 文件夹，各个模块下，以及/public/*/js/* 文件夹下添加对应的语言文件即可     
下列文件除外（没有使用多语言）
- public\static\back\js\install.js   
- public\static\home\js\index.js    
- ai\html\view\*.html  

#### 网站前端（AI模块） 
生成前端静态页面可以修改 `application/ai/controller/Html` 生成指定路径，默认文件存放位置 public 根目录下  
Api 接口，请登录注册百度账号 `https://ai.baidu.com/docs#/OCR-API/top` , 在 `extend/ImgWord.php` 文件中配置上自己的` Api Key、Secret Key `  
下列是 ai 导航栏目数据，nav_name 值与源码文件中字段一致，不建议修改，只需根据自己的数据库表前缀更改 `w_ai_nav` 即可    
    
	INSERT INTO `w_ai_nav` ( `nav_name`, `nav_biaoshi`, `keyword`, `description`, `sort`, `is_show`) VALUES
	('id_word', '身份证识别', '', '', 5, '1'),
	('img_word', '图片提取文字', '', '', 1, '1'),
	('cert_word', '证件号码识别', '证件号码识别', '证件号码识别', 4, '1'),
	('bill_word', '票据识别', '票据识别', '', 3, '1'),
	('net_work', '网络图片提取文字', '网络图片提取文字', '网络图片提取文字', 2, '1');  
	
**注:**  如果百度Api 接口有所变化，需要相应调整 ai 栏目模板页面，api 调用页面 application/index/controller/Api，api 扩展类文件 extend/ImgWord.php    


[ThinkPHP 5.1框架](https://github.com/top-think/think)
