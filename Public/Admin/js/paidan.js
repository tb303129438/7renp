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

        alert("请输入要充值排单币帐户！");
        $('#username').focus();
        $('#button').prop('disabled',false);
        return false;


    }
    if ($.trim($('#yzje').val()) == '')
    {

        alert("请输入充值的排单币！");
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

    if ($.trim($('#yzje').val()) < 1)
    {
        alert("充值排单币不能低于1！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }


    var price,username,gotegary;
    price=$.trim($('#yzje').val());
    username=$.trim($('#username').val());
    gotegary=$('input[name=gotegary]:checked').val();


    $.ajax({
        type: "POST",
        url: "/admin/index/dopaidan",
        data: {'price':price,'username':username,'gotegary':gotegary},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                if(gotegary==1)
                   alert('充值排单币成功！');
                else
                    alert('扣除排单币成功！');
                location.href="/admin/index/lpaidan.html";
            }
            if(data==2)
            {
                alert('充值帐户不存在,请重新输入帐户！');
                $('#username').focus();
                $('#button').prop('disabled',false);
            }
            if(data==3)
            {
                alert('排单币不足，不能扣除！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);
            }


        }
    })
    return false;
}


