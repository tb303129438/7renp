/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){
    var captcha_img = $('#captcha-container').find('img')
    var verifyimg = captcha_img.attr("src");
    captcha_img.attr('title', '点击刷新');
    captcha_img.click(function(){
        if( verifyimg.indexOf('?')>0){
            $(this).attr("src", verifyimg+'&random='+Math.random());
        }else{
            $(this).attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
        }
    });
})


function checkuser()
{
    if($.trim($("#username").val())=="")
    {
        alert("请输入会员帐号！");
        $("#username").focus();
        return false;
    }
    if($.trim($("#password").val())=="")
    {
        alert("请输入会员密码！");
        $("#password").focus();
        return false;
    }
    if($.trim($("#verify").val())=="")
    {
        alert("请输入验证码！");
        $("#verify").focus();
        return false;
    }
    $("#form").submit();
}
