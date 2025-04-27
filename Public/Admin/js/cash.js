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

        alert("请输入要充值帐户！");
        $('#username').focus();
        $('#button').prop('disabled',false);
        return false;


    }
    if ($.trim($('#yzje').val()) == '')
    {

        alert("请输入充值的金额！");
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

    if ($.trim($('#yzje').val()) <= 0)
    {
        alert("充值的金额不能低于0！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }


    var price,username,integral,gotegary;
    price=$.trim($('#yzje').val());
    username=$.trim($('#username').val());
    integral=$.trim($('input[name=pay]:checked').val());
    gotegary=$('input[name=gotegary]:checked').val();

   
    $.ajax({
        type: "POST",
        url: "/admin/index/docash",
        data: {'price':price,'username':username,'integral':integral,'gotegary':gotegary},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                if(gotegary==1)
                   alert('充值成功！');
                else
                    alert('扣除成功！');
                location.href="/admin/index/lcash.html";
            }
            if(data==2)
            {
                alert('充值帐户不存在,请重新输入帐户！');
                $('#username').focus();
                $('#button').prop('disabled',false);
            }
            if(data==3)
            {
                alert('靓态帐户余额不足，不能扣除！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);
            }
            if(data==3)
            {
                alert('去态帐户余额不足，不能扣除！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);
            }


        }
    })
    return false;
}


