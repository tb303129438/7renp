/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){

})

//修改基本资料
function Check()
{
    // if ($.trim($('#username').val()) == '')
    // {
    //     alert('请先输入会员帐户！');
    //     $('#username').focus();
    //     return false;
    //
    // }
    // var reg = /^[0-9a-zA-Z\u4e00-\u9fa5_]{3,16}$/;
    // if(!reg.test($.trim($('#username').val())))
    // {
    //     alert('会员帐户由3-16位汉字、字母、数字、下划线组成！');
    //     $('#username').focus();
    //     return false;
    // }
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

//  if ($.trim($('#repassword').val()) == '')
//  {
//
//      alert("请输入确认登录密码！");
//      $('#password').focus();
//      return false;
//
//
//  }
//  if($.trim($('#repassword').val()).length<6)
//  {
//      alert("确认登录密码不能小于6位数！");
//      $('#repassword').focus();
//      return false;
//  }
//
//
//  if($.trim($('#password').val())!=$.trim($('#repassword').val()))
//  {
//      alert("登录密码与确认登录密码不一致，请重新输入！");
//      $('#repassword').focus();
//      return false;
//  }

    if ($.trim($('#paypassword').val()) == '')
    {

        alert("请输入安全密码！");
        $('#paypassword').focus();
        return false;


    }
    if($.trim($('#paypassword').val()).length<6)
    {
        alert("安全密码不能小于6位数！");
        $('#paypassword').focus();
        return false;
    }

//  if ($.trim($('#repaypassword').val()) == '')
//  {
//
//      alert("请输入确认安全密码！");
//      $('#paypassword').focus();
//      return false;
//
//
//  }
//  if($.trim($('#repaypassword').val()).length<6)
//  {
//      alert("确认安全密码不能小于6位数！");
//      $('#repaypassword').focus();
//      return false;
//  }


//  if($.trim($('#paypassword').val())!=$.trim($('#repaypassword').val()))
//  {
//      alert("安全密码与确认安全密码不一致，请重新输入！");
//      $('#repaypassword').focus();
//      return false;
//  }


	if ($.trim($('#username').val()) == '')
    {
        alert('请先输会员账号！');
        $('#username').focus();
        return false;

    }
    if ($.trim($('#realname').val()) == '')
    {
        alert('请先输入姓名！');
        $('#realname').focus();
        return false;

    }
  

    save();
    return false;
}


//提交会员注册
var save=function()
{


    var username,password,paypassword,tusername,realname,mobile,mobile_code,paper;
    username=$.trim($('#username').val());
    password=$.trim($('#password').val());
    paypassword=$.trim($('#paypassword').val());
    tusername=$.trim($('#tusername').val());
    realname=$.trim($('#realname').val());
    mobile=$.trim($('#mobile').val());
    mobile_code=$.trim($('#mobile_code').val());
   

    $.ajax({
        type: "POST",
        url: "/pc/index/doregistered",
        data: {'username':username,'password':password,'paypassword':paypassword,'tusername':tusername,'realname':realname,'mobile':mobile,'mobile_code':mobile_code},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('注册会员成功，请联系推荐人激活！');
                location.href='/user/member.html';
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
            
            if(data==7)
            {
                alert('支付宝已经被使用,请重新输入！');
                $('#alipay').focus();
                return false;
            }
        }
    })
}