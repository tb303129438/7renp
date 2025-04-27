/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){

})

//修改基本资料
function Check()
{
    $('#button').prop('disabled',true);

    if ($.trim($('#username').val()) == '')
    {

        alert("请输入要转入的帐户！");
        $('#username').focus();
        $('#button').prop('disabled',false);
        return false;


    }
    if ($.trim($('#yzje').val()) == '')
    {

        alert("请输入要转帐的激活码！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;


    }
    var reg = new RegExp("^[0-9]*$");
    if(!reg.test($.trim($('#yzje').val()))){
        alert("请输入整数!");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }

    if ($.trim($('#yzje').val()) <0)
    {
        alert("转帐的激活码不能低于0！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }
    if ($.trim($('#paypassword').val()) == '')
    {

        alert("请输入安全密码！");
        $('#paypassword').focus();
        $('#button').prop('disabled',false);
        return false;


    }

    var price,paypassword,username;
    username=$.trim($('#username').val());
    price=$.trim($('#yzje').val());
    paypassword=$.trim($('#paypassword').val());



    $.ajax({
        type: "POST",
        url: "/user/dotjihuoma",
        data: {'username':username,'price':price,'paypassword':paypassword},
        async:false,
        //dataType: "json",
        success: function(data){


            if(data==1)
            {
              alert('激活码转帐成功！');
                location.href='/user/ljihuoma.html';
            }
            if(data==2)
            {
                alert('支付密码不正确，请重新输入！');
                $('#paypassword').focus();
                $('#button').prop('disabled',false);

            }
            if(data==3)
            {
                alert('激活码余额不足！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);

            }
            if(data==4)
            {
                alert('要转入的帐户不存在！');
                $('#username').focus();
                $('#button').prop('disabled',false);

            }

        }
    })
    return false;
}


