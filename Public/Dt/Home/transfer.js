/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){

})

//修改基本资料
function Check()
{
    $('#button').prop('disabled',true);


    if ($.trim($('#yzje').val()) == '')
    {

        alert("请输入要转帐的金额！");
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

    if ($.trim($('#yzje').val()) < 500)
    {
        alert("转帐的金额不能低于500！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }
    if (parseInt($.trim($('#yzje').val())%100) > 0)
    {
        alert("转帐的金额只能填写100的整数倍！");
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

    var price,paypassword;
    price=$.trim($('#yzje').val());
    paypassword=$.trim($('#paypassword').val());



    $.ajax({
        type: "POST",
        url: "/user/dotransfer",
        data: {'price':price,'paypassword':paypassword},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
              alert('转帐成功！');
                location.href='/user/income.html';
            }
            if(data==2)
            {
                alert('支付密码不正确，请重新输入！');
                $('#paypassword').focus();
                $('#button').prop('disabled',false);

            }
            if(data==3)
            {
                alert('动态帐户余额不足！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);

            }
           

        }
    })
    return false;
}


