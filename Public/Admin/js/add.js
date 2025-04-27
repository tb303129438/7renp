/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    // if ($.trim($('#username').val()) == '')
    // {
    //     alert('请先输入会员帐户');
    //     $('#username').focus();
    //     return false;
    //
    // }


    // if(!checkusername($('#username').val()))
    // {
    //    alert('会员帐户已经被使用，请输入新的会员帐户');
    //   $('#username').focus();
    //  return false;
    // }
     if ($.trim($('#mobile').val()) == '')
     {
     alert('请先输入手机号码');
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



    if ($.trim($('#tusername').val()) == '')
    {
        alert('请先输入推荐人帐户');
        $('#tusername').focus();
        return false;

    }

    if(!checkmobile($('#mobile').val()))
    {
     alert('手机号码已经被使用，请输入新的手机号码');
     $('#mobile').focus();
     return false;
    }


    if ($.trim($('#password').val()) == '')
    {
        alert('请先输入会员密码');
        $('#password').focus();
        return false;

    }
    if($.trim($('#password').val()).length<6)
    {
        alert("密码不能小于6位数！");
        $('#password').focus();
        return false;
    }

    if ($.trim($('#paypassword').val()) == '')
    {
        alert('请先输入交易密码');
        $('#paypassword').focus();
        return false;

    }
 


}