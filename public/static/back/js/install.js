/**
 * Created by Administrator on 17-9-17.
 * 安装部分 验证文字 多语言--缺少
 */
$(document).ready(function(e) {
    var sta=1;
    $('#dbname').blur(function(){
        for(var i=0;i<5;i++)
        {
            if($('input.form-control').eq(i).val()=='')
            {
                  return;
            }
        }
        var num=$(this).siblings('.glyphicon-ok').length;//如果字符验证正确  验证数据库信息是否正确
        if(num==1)
        {
            //发送ajax请求
            $.ajax({
                type:"POST",
                url:"/Install/Index/validb",
                data:$('form').serialize(),
                success: function(data,status,xhr){
                    data=data.replace(/\<\?php/g,"");
                    console.log(data);
                    sta=data;
                    if(data=='table_data')
                    {
                        if (confirm("数据库存在数据，请问确定删除吗？")) {
                            $('.table_data').val(1);
                            sta="db_exis";
                            $('#dbname').siblings('.help-block').eq(0).css('display','none');
                         //   $('#db').focus();
                        }
                        else {
                            alert("请先清空数据库再安装，如有数据请先备份");
                            $('.table_data').val(0);
                        //    $('#db').focus();
                        }
                    }

                    if(data=='server_error')
                        $('#dbname').siblings('.help-block').eq(0).css({'display':'block','color':'#f00'}).html("连接服务器失败");
                    if(data=="db_ok")
                        $('#dbname').siblings('.help-block').eq(0).css({'display':'block','color':'#f00'}).html("数据库不存在请手动创建并授予权限");
                    if(data=="db_exis")
                        $('#dbname').siblings('.help-block').eq(0).css({'display':'block'}).html("信息正确，已存在此数据库，连接成功");
                },
                error: function(xhr,status,errorinfo){
                    console.log(xhr.responseText);
					alert(status+': '+errorinfo);
                },
                timeout:2000
            });
        }
    });
    $('.b_from').on('success.form.bv',function(e){
        if(sta==="db_exis")
        {
            e.preventDefault();
            $.ajax({
                type:"POST",
                url:"/Install/Index/install",
                data:$('form').serialize(),
                beforeSend: function(){
					$("#modal-normal").show();
					if(!$("#modal-normal .modal-content").hasClass("hidden"))
					{
						$("#modal-normal .modal-content").addClass('hidden').siblings('i').removeClass('hidden');
					}
                },
                success: function(data,status,xhr){
					// 加载 图片 消失
					$("#modal-normal .modal-content").removeClass('hidden').siblings('i').addClass('hidden');
					console.log(data);

                    if(typeof data=='string')
                    {
                        data=data.replace(/\<\?php/g,"");
						// 模态提示框显示（中型）						
						$("#modal-normal .modal-body").text("error: "+data);
						return false;
                    }else
                    {
						$("#modal-normal .modal-body").text(":) 安装成功进入系统");
                        $("#modal-normal").delay("100").fadeOut();;
                         t=setTimeout(function(){window.location.href=data.url;clearTimeout(t);},150)
                    }

                },
                error: function(xhr,status,errorinfo){
                    console.log(xhr.responseText);
					// 模态提示框显示（中型）
					$("#modal-normal .modal-content").removeClass('hidden').siblings('i').addClass('hidden');
                    $("#modal-normal .modal-body").text("(: "+status+': '+errorinfo);
                },
                timeout:5000
            });
        }
        else
        {
            $("#modal-normal .modal-body").text("error: "+'连接数据库信息错误');
        }
    });
});
