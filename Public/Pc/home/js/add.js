/**
 * Created by Administrator on 2016/12/20.
 */

//修改基本资料
function Check()
{
       if ($.trim($('#username').val()) == '')
       {
           alert('请先输入会员账号！');
           $('#username').focus();
           return false;
      
       }
    var reg = /^[0-9]{6}$/;
    if(!reg.test($.trim($('#username').val())))
    {
        alert('会员帐户由MJ+6位数字组成，MJ系统默认生成');
        $('#username').focus();
        return false;
    }
    if ($.trim($('#realname').val()) == '')
    {
        alert('请先输入姓名！');
        $('#realname').focus();
        return false;
    }
 if ($.trim($('#paper').val()) == '')
 {
     alert('请先输入身份证号码！');
     $('#paper').focus();
     return false;
 }

 var reg = /^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/;
 if(!reg.test($.trim($('#paper').val())))
 {
     alert("身份证号码格式不对！");
     $('#paper').focus();
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
     
	if ($.trim($('#tusername').val()) == '')
    {
        alert('请填写推荐会员 ！');
        $('#tusername').focus();
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
//  var checkbox = document.getElementById('save');//
//		if(checkbox.checked){
//  		//选中了
//  		 
// 		}else{
//  		 //没选中
//  		 alert('请先阅读并同意用户协议！');
//  		 return false;
//		}
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
    
    save();
    return false;
}

//提交会员注册
var save=function()
{


    var username,password,paypassword,tusername,realname,mobile,paper,fwzx;
    username=$.trim($('#username').val());
    password=$.trim($('#password').val());
    paypassword=$.trim($('#paypassword').val());
    tusername=$.trim($('#tusername').val());
    realname=$.trim($('#realname').val());
    mobile=$.trim($('#mobile').val());
    paper=$.trim($('#paper').val());
    fwzx=$.trim($('#fwzx').val());

    $.ajax({
        type: "POST",
        url: "/pc/index/doregistered",
        data: $("#formssss").serialize(),
       // async:false,
        dataType: "json",
        success: function(data){
            
           
            if(data.flag==1)
            {
                alert('注册会员成功！');
                location.href='/pc/wo/wtjg.html';
            }
            if(data.flag==2)
            {
                alert('会员帐户已经被使用！');
                $('#username').focus();
                return false;
            }
            if(data.flag==3)
            {
                alert('推荐人不存在！');
                $('#tusername').focus();
                return false;
            }
            if(data.flag==4)
            {
                alert('手机已经被使用！');
                $('#tusername').focus();
                return false;
            }
            if(data.flag==6)
            {
                alert('身份证号码已经被使用,请重新输入！');
                $('#paper').focus();
                return false;
            }
            if(data.flag==9)
            {
                alert('余额不足，请进行充值！');
                $('#11').focus();
                return false;
            }
            if(data.flag==10)
            {
                alert('输入的服务中心不存在！');
                $('#fwzx').focus();
                return false;
            }
            if(data.flag==11)
            {
                if(confirm("注册成功，是否要现在激活？"))
                {
                    location.href="/pc/wo/jhzh.html?id="+data.id;
                }
                else
                {
                    location.href='/pc/wo/wtjg.html';
                }
            }
        }
    })
}