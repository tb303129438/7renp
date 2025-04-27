/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){

})

//修改基本资料
function Check()
{
    if ($.trim($('#username').val()) == '')
    {
        alert('请先输入会员帐户！');
        $('#username').focus();
        return false;
    
    }
    var reg = /^[0-9a-zA-Z\u4e00-\u9fa5_]{3,16}$/;
    if(!reg.test($.trim($('#username').val())))
    {
        alert('会员帐户由3-16位汉字、字母、数字、下划线组成！');
        $('#username').focus();
        return false;
    }
    if ($.trim($('#mobile').val()) == '')
    {
        alert('请先输入手机号码！');
        $('#mobile').focus();
        return false;

    }



    var reg = /^1(3|4|5|7|8)\d{9}$/;
    if(!reg.test($.trim($('#mobile').val())))
    {
        alert("手机号码格式不对！");
        $('#mobile').focus();
        return false;
    }

    // if ($.trim($('#mobile_code').val()) == '')
    // {

    //     alert("请输入手机验证码！");
    //     $('#mobile_code').focus();
    //     return false;


    // }

    // if ($.trim($('#realname').val()) == '')
    // {
    //     alert('请先输入姓名！');
    //     $('#realname').focus();
    //     return false;

    // }

    if ($.trim($('#password').val()) == '')
    {

        alert("请输入登录密码！");
        $('#password').focus();
        return false;


    }
    if($.trim($('#password').val()).length<6)
    {
        alert("登录密码不能小于6位数！");
        $('#password').focus();
        return false;
    }

    if ($.trim($('#repassword').val()) == '')
    {

        alert("请输入确认登录密码！");
        $('#repassword').focus();
        return false;


    }
    if($.trim($('#repassword').val()).length<6)
    {
        alert("确认登录密码不能小于6位数！");
        $('#repassword').focus();
        return false;
    }


    if($.trim($('#password').val())!=$.trim($('#repassword').val()))
    {
        alert("登录密码与确认登录密码不一致，请重新输入！");
        $('#repassword').focus();
        return false;
    }

    if ($.trim($('#paypassword').val()) == '')
    {

        alert("请输入交易密码！");
        $('#paypassword').focus();
        return false;


    }
    if($.trim($('#paypassword').val()).length<6)
    {
        alert("交易密码不能小于6位数！");
        $('#paypassword').focus();
        return false;
    }

    if ($.trim($('#repaypassword').val()) == '')
    {

        alert("请输入确认交易密码！");
        $('#repaypassword').focus();
        return false;


    }
    if($.trim($('#repaypassword').val()).length<6)
    {
        alert("确认交易密码不能小于6位数！");
        $('#repaypassword').focus();
        return false;
    }


    if($.trim($('#paypassword').val())!=$.trim($('#repaypassword').val()))
    {
        alert("安全密码与确认交易密码不一致，请重新输入！");
        $('#repaypassword').focus();
        return false;
    }
    // if ($.trim($('#paper').val()) == '')
    // {
    //     alert('请先输入身份证号码！');
    //     $('#paper').focus();
    //     return false;

    // }

    // var reg = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
    // if(!reg.test($.trim($('#paper').val())))
    // {
    //     alert("身份证号码格式不对！");
    //     $('#paper').focus();
    //     return false;
    // }

    

    // if ($.trim($('#bankname').val()) == '')
    // {
    //     alert('请先输入开户姓名！');
    //     $('#bankname').focus();
    //     return false;

    // }
    // if ($.trim($('#bank').val()) == '')
    // {
    //     alert('请先输入开户银行！');
    //     $('#bank').focus();
    //     return false;

    // }
    // if ($.trim($('#bankcode').val()) == '')
    // {
    //     alert('请先输入银行帐号！');
    //     $('#bankcode').focus();
    //     return false;

    // }

    // var reg = /^[1-9][0-9]\d{14}|[1-9][0-9]\d{18}$/;
    // if(!reg.test($('#bankcode').val()))
    // {
    //     alert("银行帐号格式不对！");
    //     $('#bankcode').focus();
    //     return false;
    // }


    // if ($.trim($('#weixin').val()) == '')
    // {
    //     alert('请先输入微信帐户！');
    //     $('#weixin').focus();
    //     return false;
    //
    // }
    // if ($.trim($('#alipay').val()) == '')
    // {
    //     alert('请先输入支付宝帐户！');
    //     $('#alipay').focus();
    //     return false;
    //
    // }
    // if ($.trim($('#address').val()) == '')
    // {
    //     alert('请先输入收货地址！');
    //     $('#address').focus();
    //     return false;
    //
    // }




    save();
    return false;
}


//提交会员注册
var save=function()
{
    var username,password,paypassword,tusername,mobile;
    username=$.trim($('#mobile').val());
    password=$.trim($('#password').val());
    paypassword=$.trim($('#paypassword').val());
    tusername=$.trim($('#tusername').val());
    
    mobile=$.trim($('#mobile').val());
 

   

    $.ajax({
        type: "POST",
        url: "/home/index/doreg",
        data: {'username':username,'password':password,'paypassword':paypassword,'tusername':tusername,'mobile':mobile},
        async:false,
        //dataType: "json",
        success: function(data){

            
            if(data==1)
            {
                alert('注册会员成功！');
                location.href='/login.html';
            }
            if(data==2)
            {
                alert('会员名称已经被使用！');
                $('#username').focus();
                return false;
            }
            if(data==3)
            {
                alert('推荐人不存在！');
                $('#tusername').focus();
                return false;
            }
            if(data==4)
            {
                alert('手机已经被使用！');
                $('#tusername').focus();
                return false;
            }
            if(data==5)
            {
                alert('手机验证码不正确,请重新输入！');
                $('#mobile_code').focus();
                return false;
            }
            

        }
    })
    return false;
}



var changeuser=function(this)
{

}