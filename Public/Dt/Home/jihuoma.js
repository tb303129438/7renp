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

        alert("请输入购买的激活码数量！");
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
        alert("购买激活码数量不能低于1！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }
    if(parseInt($('#integral1').val())<parseInt($('#total').val()))
    {
        alert('靓态帐户余额不足！');
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

    var price,paypassword,total;
    price=$.trim($('#yzje').val());
    paypassword=$.trim($('#paypassword').val());
    total=$.trim($('#total').val());


    $.ajax({
        type: "POST",
        url: "/user/dojihuoma",
        data: {'price':price,'paypassword':paypassword,'total':total},
        async:false,
        //dataType: "json",
        success: function(data){
            

            if(data==1)
            {
              alert('购买激活码成功！')
            }
            if(data==2)
            {
                alert('支付密码不正确，请重新输入！');
                $('#paypassword').focus();
                $('#button').prop('disabled',false);

            }
            if(data==3)
            {
                alert('静态帐户余额不足！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);

            }
           

        }
    })
    return false;
}

var changeprice=function(price)
{
    $('#total').val(price*200);
}

