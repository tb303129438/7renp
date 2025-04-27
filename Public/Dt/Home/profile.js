/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){

})

//修改基本资料
function Checkinfo()
{
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
    var mobile,weixin,alipay,address;
    mobile=$.trim($('#mobile').val());
    weixin=$.trim($('#weixin').val());
    alipay=$.trim($('#alipay').val());
    address=$.trim($('#address').val());


    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {'action':'checkinfo','mobile':mobile,'weixin':weixin,'alipay':alipay,'address':address},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
              alert('修改基本资料成功！')
            }
            if(data==2)
            {
                alert('手机号码已经被使用，请换另一个手机号码！')
            }

        }
    })
    return false;
}



//修改身份
function Checkpaper()
{
    if ($.trim($('#realname').val()) == '')
    {
        alert('请1先输入姓名！');
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

    var realname,paper;
    realname=$.trim($('#realname').val());
    paper=$.trim($('#paper').val());


    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {'action':'checkpaper','realname':realname,'paper':paper},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改实名认证资料成功！')
            }
            if(data==2)
            {
                alert('身份证号码已经使用！')
            }


        }
    })
    return false;
}


//修改银行资料
function Checkbank()
{
    if ($.trim($('#bankname').val()) == '')
    {
        alert('请先输入开户姓名！');
        $('#bankname').focus();
        return false;

    }
    if ($.trim($('#bank').val()) == '')
    {
        alert('请先输入开户银行！');
        $('#bank').focus();
        return false;

    }
    if ($.trim($('#bankcode').val()) == '')
    {
        alert('请先输入银行帐号！');
        $('#bankcode').focus();
        return false;

    }

    var reg = /^[1-9][0-9]\d{14}|[1-9][0-9]\d{18}$/;
    if(!reg.test($('#bankcode').val()))
    {
        alert("银行帐号格式不对！");
        $('#bankcode').focus();
        return false;
    }

    var bankname,bank,bankcode;
    bankname=$.trim($('#bankname').val());
    bank=$.trim($('#bank').val());
    bankcode=$.trim($('#bankcode').val());


    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {'action':'checkbank','bankname':bankname,'bank':bank,'bankcode':bankcode},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改实名认证资料成功！')
            }


        }
    })
    return false;
}
