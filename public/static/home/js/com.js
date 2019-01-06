$(function () {
    $(window).scroll(function(){
        var top=$(document).scrollTop();
        if(top>100)
            $('.navbar-fixed-top').addClass('animated').css({'animationName':'bounce '});
        else
            $('.navbar-fixed-top').removeClass('animated').removeAttr('style');
    });	 // 固定导航

    $("i.nav_bar").click(function(){
        if($(this).hasClass('fa-bars'))
        {
            $(this).removeClass('fa-bars').addClass(' fa-times')
        }
        else
        {
            $(this).removeClass('fa-times').addClass('fa-bars');
        }
    });// 切换导航

    //导航点击下拉框
    $('a.dropdown-toggle').click(function(){
        $('.dropdown>.dropdown-menu').slideToggle();
    });

    // 清空文本
    $("button.reset").click(function(){
        $("[data-toggle='tooltip']").removeAttr("data-original-title");
        $("[data-toggle='tooltip']").removeAttr("title");
        $(this).siblings("button").attr('disabled',true);
        $('textarea').val('');
    });

    // 手动输入时可以点击按钮
    $("textarea").on('input  propertychange',function() {
        if ($(this).val() != '')
        {
            $("form").find("button").attr('disabled',false);
        }
    });

});

// 文本框复制文本
function copy_text()
{
    $("form").find("button").attr('disabled',false);

    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        // 工具提示框
        $("[data-toggle='tooltip']").attr("data-original-title",'copy success');
        $("[data-toggle='tooltip']").attr("title",'copy success');
        $('[data-toggle="tooltip"]').tooltip('show');
    });
    clipboard.on('error', function(e) {
        // 工具提示框
        $("[data-toggle='tooltip']").attr("data-original-title",'copy fail');
        $("[data-toggle='tooltip']").attr("title",'copy fail');
        $('[data-toggle="tooltip"]').tooltip('show');
    });
}

// 显示预览图片
function img_show(base)
{
    $(".warp-img img").attr("src",base);
    $("#modal-large .modal-body img").attr("src",base);
}


// 图片格式错误提示框
function alter_box(info,t)
{
   if(t == undefined)
   {
        t=1000;
   }
    $("#load_img").children('i').addClass('hidden');
    $('#load_img').find('#alert span').text(info);
    $('#load_img').find('#alert').removeClass('hidden');
    $("#load_img").delay(t).fadeOut(function () {
        $("#load_img").children('i').removeClass('hidden');
        $('#load_img').find('#alert').addClass('hidden');
    });
}