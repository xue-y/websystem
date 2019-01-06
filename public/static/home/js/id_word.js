$(function () {
    $(".filePicker .file").hover(function(){
        $(".filePicker label").css("backgroundColor",'#00a2d4');
    },function(){
        $(".filePicker label").css("backgroundColor",'#00b7ee');
    }); // -----------------上传按钮

    var but_h= $(".filePicker").outerHeight(); // 按钮高度

    $('input[type=file]').on('change',function() {

        // 加载动画
        $("#load_img").fadeIn(30);

        // 判断文件类型 文件大小限制 这里要与配置文件一致
        var size=this.files[0].size/1024/1024;
        if(size > 4)
        {
            alter_box("上传图片大于4MB");
            return false;
        }
        if(!/image\/\w+/.test(this.files[0].type) || (this.files[0].type=='image/gif'))
        {
            alter_box("请确保上传文件为图像类型PNG、JPG、JPEG、BMP",1500);
            return false;
        }

        // 如果浏览器支持 FileReader
        if(typeof FileReader != "undefined"){

            var reader = new FileReader();
            // 读取文件内容，结果用data:url的字符串形式表示
            reader.readAsDataURL(this.files[0]);
            reader.onload = function (e) {
                $("#load_img").fadeOut('slow');
                img_show(reader.result)
                //或者 e.target.result都是一样的，都是base64码
            };
            reader.onerror=function(e){
                $("#load_img").fadeOut(50);
                return false;
            }
        }
        else {   // ----------------------------------------不支持 FileReader
            // ajax 上传到后端验证 并返回 图片 base 值
            var formData = new FormData();
            var avatarImg = $("input[type='file']")[0].files[0];
            formData.append('file', avatarImg);
            $.ajax({
                type:"POST",
                url:'/index/Api/img_base',
                data:formData,
                processData: false,  // 不处理数据
                contentType: false,  // 不设置内容类型
                success: function(data){
                  if(typeof data=="object")
                  {
                      img_show(data.data);
                  }else
                  {
                      alter_box(data,1500);
                      return false;
                  }
                },
                complete:function () {
                    $("#load_img").fadeOut(50);
                },
                error: function(xhr,status,errorinfo) {
                    alter_box(xhr.responseText,3000);
                }
            });
        }

        // 确认上传按钮 可以点击
        $(".btu-upload").attr("disabled",false);

        var win_w=$(window).width();
        if(win_w>=975)
        {
            // pc 端按钮 居右
            $(".filePicker").eq(0).addClass("text-center");

            if($(".warp-img").is(':hidden')){
                $(".filePicker label").text("重新上传图片");
                $('.btu-upload').delay(200).show(100);
                $(".warp-img").show(300,function(){
                    var top=$(".warp-img").outerHeight(true)-but_h;
                    $(".warp_btu").css('top',top);
                });
            }else
            {
                var top=$(".warp-img").outerHeight(true)-but_h;
                $(".warp_btu").css('top',top);
            }
        }else
        {
            if($(".warp-img").is(':hidden')){
                $(".filePicker label").text("重新上传图片");
                $('.btu-upload').delay(200).show(50);
                $(".warp-img").show(300,function(){
                    $(".warp_btu").css('top',15);
                });
            }else
            {
                $(".warp_btu").css('top',15);
            }
        }// -----------------pc wap 端布局转换

    });

    // 当窗口改变时--- 改变提交图片按钮位置
    $(window).resize(function(){
        if($(".warp-img").is(':hidden'))
            return false;

        var win_w=$(window).width();
        if(win_w>=975)
        {
            $(".filePicker").eq(0).addClass("text-center");
            var top=$(".warp-img").outerHeight(true)-but_h;
            $(".warp_btu").css('top',top);

        }else
        {
            $(".filePicker").eq(0).removeClass("text-center");
            $(".warp_btu").css('top',15);
        }
    });

    // 点击提交
    $('.btu-upload').on('click',function() {
        var img_data = $(".warp-img img").attr("src");
        var id_card_side;
        if($("input[type='radio']:checked").length==1)
        {
            id_card_sidee=$("input[type='radio']:checked").val();
        }

        // 加载动画
        $("#load_img").fadeIn(30);

        $.ajax({
            type: "POST",
            url: "/index/Api/id_word",
            data:{"img_data":img_data,"id_card_side":id_card_sidee,},
            success: function (data) {
               // console.log(data);
                 if(typeof data=="object")
                {
                    $(window).scrollTop($('textarea').offset().top);
                    copy_text();
                    $('textarea').val(data.word);
                }else
                {
                    alter_box(data,1500);
                    return false;
                }
            },
            complete: function () {
                $("#load_img").fadeOut(50);
            },
            error: function (xhr, status, errorinfo) {
            //   console.log(xhr.responseText);
                alter_box(xhr.responseText,3000);
            }
        });
    });

});