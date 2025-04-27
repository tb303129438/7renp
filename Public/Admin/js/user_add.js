/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){
    $('#lx').change(function()
    {
        if($(this).val()==1)
            {
                $('#fwzxdiv').hide();
            }
            else
            {
                $('#fwzxdiv').show();
            }
    })
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
//      var reg = /^1(3|4|5|7|8)\d{9}$/;
//      if(!reg.test($.trim($('#username').val())))
//      {
//          alert("会员账户格式不对，格式为手机号码！");
//          $('#username').focus();
//          return false;
//      }
    var reg = /^[0-9]{6}$/;
    if(!reg.test($.trim($('#username').val())))
    {
        alert('会员帐户由Mc+6位数字组成，Mc系统默认生成');
        $('#username').focus();
        return false;
    }
    if ($.trim($('#realname').val()) == '')
    {
        alert('请先输入姓名！');
        $('#realname').focus();
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
	// if ($.trim($('#tusername').val()) == '')
 //    {
 //        alert('请填写推荐人 ！');
 //        $('#tusername').focus();
 //        return false;
 //    }

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
    var lx,username,password,paypassword,tusername,realname,mobile,mobile_code,paper,bankname,bank,bankcode,weixin,alipay,address,fwzx;
    lx=$.trim($('#lx').val());
    username=$.trim($('#username').val());
    password=$.trim($('#password').val());
    paypassword=$.trim($('#paypassword').val());
    tusername=$.trim($('#tusername').val());
    realname=$.trim($('#realname').val());
    mobile=$.trim($('#mobile').val());
    mobile_code=$.trim($('#mobile_code').val());
    fwzx=$.trim($('#fwzx').val());

    paper=$.trim($('#paper').val());
    bankname=$.trim($('#bankname').val());
    bank=$.trim($('#bank').val());
    bankcode=$.trim($('#bankcode').val());
    weixin=$.trim($('#weixin').val());
    alipay=$.trim($('#alipay').val());
    address=$.trim($('#address').val());

    $.ajax({
        type: "POST",
        url: "/Admin/index/douseradd",
        data: {'lx':lx,'username':username,'password':password,'paypassword':paypassword,'tusername':tusername,'realname':realname,'mobile':mobile,'mobile_code':mobile_code,'paper':paper,'fwzx':fwzx,'bankname':bankname,'bank':bank,'bankcode':bankcode,'weixin':weixin,'alipay':alipay,'address':address},
        async:false,
        //dataType: "json",
        success: function(data){

          
            if(data==1)
            {
                alert('注册会员成功！');
                location.href='/admin/index/member.html?t=1';
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

            if(data==6)
            {
                alert('身份证号码已经被使用,请重新输入！');
                $('#paper').focus();
                return false;
            }
  			if(data==10)
            {
                alert('报单中心不存在！');
                $('#fwzx').focus();
                return false;
            }
        }
    })
}