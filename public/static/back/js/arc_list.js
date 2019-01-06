// JavaScript Document
function in_order()
{
	$('.in_order').each(function(i){
		
		$('.in_order').eq(i).focus(function()
		{
            $(this).css('borderColor','#ddd');
		})
		$('.in_order').eq(i).blur(function()
		{
            $(this).css('borderColor','transparent');
		})
	})
}
in_order()//-------------------排序框 文本框

//----------------------------------------------------------------------全选
check();
function check()
{
    var len;
    var T=$('input[type=checkbox]').eq(0);
    T.after("<span style='margin: 10px 5px;'>全选</span>");
    var change_info=T.siblings('span');


    $('input[type=checkbox]').each(function (i) {
        if(i<1)
        {
            $(this).on('click',function(){
                change_info=T.siblings('span');
                var status= $('input[type=checkbox]').eq(0).prop('checked');
                len=$('input[type=checkbox]').length-1;
                quxuan(change_info,len,status);
            });
        }else
        {
            $(this).click(function()
            {
                option=$(change_info).text();
                if(option=='未选中'  || option=="全选")
                {	j=0; }// 取消全选 | 第一次选中
                else
                {
                    option=option.replace(/[^0-9]/ig,"");
                    option=parseInt(option);
                    j=option;
                }

                if($(this).prop('checked')==true || $(this).attr('checked')=="checked")
                {
                    ++j;
                }
                else
                {
                    --j;
                }
                if($(this).parents('tr').hasClass("checked")){
                    $(this).parents('tr').removeClass('checked');
                } else {
                    $(this).parents('tr').addClass('checked');
                }

                if(j>=1)
                    $(change_info).text('选中'+(j)+'项');
                else
                    $(change_info).text('未选中');
            });
        }
    });
}
function quxuan(change_info,len,status)
{
    if(status=="checked" || status==true)
    {
        var t_num='选中'+len+'项';

        $(change_info).text(t_num);

        $('input[type=checkbox]').each(function(i){
            if(!$(this).parents('tr').hasClass("checked") && i>=1){
                $(this).parents('tr').addClass('checked');
            }
            $('input[type=checkbox]').eq(i).prop('checked',true);

        });
    }
    else
    {
        $(change_info).text('未选中');
        $('input[type=checkbox]').each(function (i) {

            $('input[type=checkbox]').eq(i).removeAttr('checked');
            $('input[type=checkbox]').eq(i).prop('checked',false);
            if($(this).parents('tr').hasClass("checked") && i>=1){
                $(this).parents('tr').removeClass('checked');
            }
        });
    }
}//全选

//----------------------------------------------------------------------全选删除
// 批量操作
function operation(tis,info,url) {
    var Checkbox=false;
    $("input[name^='id']").each(function(){
        if (this.checked==true) {
            Checkbox=true;
        }
    });
    if (Checkbox){
        tis.find('input').attr("type","submit");
        $("form").attr("action",url);
        return true;
    }
    else{
        if($('#alert').is(':hidden'))
        {
            $('#alert').children('span.text').text('请选择您要'+info+'的内容!');
            $('#alert').stop(true).slideDown().delay(3000).slideUp();
        }
        return false;
    }
}

// 单个删除弹出框
function del(t)
{
    $('#del_model').modal();
    $('#url').val(t.attr("data-href"));//给会话中的隐藏属性URL赋
    /*var tt=confirm("您确认要"+info+"选中的内容吗？");
    if (tt)
    {
       var href=t.attr("data-href");
        t.attr("href",href);
        return;
    }
    else
    {
        return false;
    }*/
}

// 执行删除跳转
function del_submit() {
    var url=$.trim($("#del_model #url").val());//获取会话中的隐藏属性URL
    window.location.href=url;
}


//功能：根据用户输入的Email跳转到相应的电子邮箱首页
function gotoEmail(mail) {
    $t = mail.split('@')[1];
    $t = $t.toLowerCase();
    if ($t == '163.com') {
        return 'mail.163.com';
    } else if ($t == 'vip.163.com') {
        return 'vip.163.com';
    } else if ($t == '126.com') {
        return 'mail.126.com';
    } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
        return 'mail.qq.com';
    } else if ($t == 'gmail.com') {
        return 'mail.google.com';
    } else if ($t == 'sohu.com') {
        return 'mail.sohu.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'vip.sina.com') {
        return 'vip.sina.com';
    } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
        return 'mail.sina.com.cn';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
        return 'mail.cn.yahoo.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yeah.net') {
        return 'www.yeah.net';
    } else if ($t == '21cn.com') {
        return 'mail.21cn.com';
    } else if ($t == 'hotmail.com') {
        return 'www.hotmail.com';
    } else if ($t == 'sogou.com') {
        return 'mail.sogou.com';
    } else if ($t == '188.com') {
        return 'www.188.com';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else if ($t == '189.cn') {
        return 'webmail15.189.cn/webmail';
    } else if ($t == 'wo.com.cn') {
        return 'mail.wo.com.cn/smsmail';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else {
        return '';
    }
};
//--------------------------------------------------------------

// tab 菜单切换
//tab_toggle($("#data .tab-nav li"),$(".panel"));
function tab_toggle(nav,con)
{
    nav.each(function(i,ele){
        $(this).click(function(){
            con.eq(i).addClass("on").siblings().removeClass("on");
            $(this).addClass("on").siblings().removeClass("on");
        });
    })
}
//采用正则表达式获取地址栏参数
function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null) return r[2];
}

// 桌面提醒
// 调用 showNotice("请去您的[个人信息]页面查看","现在有 1 条反馈信息")；
function showNotice(title,msg){
    var bool=true;
    var Notification = window.Notification || window.mozNotification || window.webkitNotification;
    if(Notification){
        Notification.requestPermission(function(status){
            //status默认值'default'等同于拒绝 'denied' 意味着用户不想要通知 'granted' 意味着用户同意启用通知
            if("granted" != status){
                return;
            }else{
                var tag = "sds"+Math.random();
                var notify = new Notification(
                    title,
                    {
                        dir:'auto',
                        lang:'zh-CN',
                        tag:tag,//实例化的notification的id
                        //  icon:'http://www.yinshuajun.com/static/img/favicon.ico',//通知的缩略图,//icon 支持ico、png、jpg、jpeg格式
                        body:msg //通知的具体内容
                    }
                );
                notify.onclick=function(){
                    //如果通知消息被点击,通知窗口将被激活
                    window.focus();
                },
                    notify.onerror = function () {
                        console.log("HTML5桌面消息出错！！！");
                    };
                notify.onshow = function () {
                    setTimeout(function(){
                        notify.close();
                    },50000)
                };
                notify.onclose = function () {
                    console.log("HTML5桌面消息关闭！！！");
                };
            }
        });
    }else{
        console.log("您的浏览器不支持桌面消息");
    }
}; // 桌面提醒结束


/*添加复制文本框*/
function add_group(t,t_parent)
{
    var len=$("body").find(t_parent).length;
    if(len>10)
    {
        alert("一次最多添加10个");
        return false;
    }
    var inp=$("body").find(t_parent).eq(0).clone(true);
    $("body").find(t_parent).eq(0).find('input').attr("placeholder","第"+len+"个");
    $("body").find(t_parent).eq(0).eq(0).after(inp);
}

/*删除文本框
*t 当前操作的元素
* t_parent 此元素只为页面操作元素的父级
* */
function del_group(t,t_parent)
{
  // 删除点击的下一个  t_parent 元素
  var del_inp=$("body").find(t_parent).eq(0).nextAll(t_parent);
  var del_num=del_inp.length-1; // 一个提交按钮 元素 去掉
  if(del_inp.length>1)
        del_inp.eq(0).remove();
   else
       alert('至少一个元素');
}

table_sort();
function table_sort()
{
    // 不使用 table sort
    if($("table").hasClass('no-table') || ($('table').length<1))
    {
        return false;
    }

    // 排除最后一个 操作
    var column=$('table').find('thead>tr>th').length-1;

    $('table').DataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 0 ,column ] }],
        "aaSorting": [[ 1, "asc" ]],
        "pageLength": 5, //使用分页时，单页显示的数据条数
        //设置左上角那个下拉框（默认在左上角），默认值为 [ 10, 25, 50, 100 ]
        "lengthMenu":  [5,10, 25, 50, "所有" ],
        "language": {
            "url": "/static/back/plugins/datatables/zh_CN.json"
        },
        "searching": true, // 开启搜索
        "sPaginationType": "full_numbers", // 分页样式
        //打开状态保存
        stateSave: true,
        //根据Datatables的实例id来读取和储存页面上保存的状态:
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        }
    });
}


// 更新排序--动态输入框需要监听
$("#sort").on('click','input',function(){
    var len=$("input[name^='sort']").length;
    if(len<1)
    {
        if($('#alert').is(':hidden'))
        {
            $('#alert');
            $('#alert').children('span.text').text('请选择您要排序的内容!');
            $('#alert').slideDown().delay(3000).slideUp();
        }
    }else
    {// this-->input
        var url=$(this).attr("data-href");
        $("form").attr("action",url);
        $(this).attr("type","submit");
    }
});
sort();
function sort() {
    if($(".sort").length<1)
        return false;
    // 修改排序
    $(".sort a").each(function (i,ele) {
        var _this=$(this);
        var id=_this.parent('td').siblings('.id').text();
        _this.click(function () {
            var old_v=_this.siblings('span').text();
            _this.siblings('span').html('<input type="number" name="sort['+id+']" value="'+old_v+'" class="text-center" min="1">')
        });
    });

}