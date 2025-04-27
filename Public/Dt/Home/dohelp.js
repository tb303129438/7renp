/**
 * Created by Administrator on 2016/12/20.
 */
$(function(){



})
var sign=false;//防止重复提交
//修改基本资料
function Check()
{

    $("#button").prop("disabled",true);

    if ($.trim($('#yzje').val()) == '')
    {

        alert("请输入援助金额！");
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

    if (parseInt($.trim($('#yzje').val())) < parseInt(edubegin))
    {
        alert("援助金额不能低于"+edubegin+"！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }
    if (parseInt($.trim($('#yzje').val())) > parseInt(eduend))
    {
        alert("援助金额不能超过"+eduend+"！");
        $('#yzje').focus();
        $('#button').prop('disabled',false);
        return false;
    }

    if (parseInt($.trim($('#yzje').val())%1000) > 0)
    {
        alert("援助金额只能填写1000的整数倍！");
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

    if(confirm('请确认提交排单？')) {

        if (sign == false) {
            sign = true;
            $.ajax({
                type: "POST",
                url: "/user/dodohelp",
                data: {'price': price, 'paypassword': paypassword, 'total': total},
                async: false,
                //dataType: "json",
                success: function (data) {

                    if (data == 1) {
                        sign = false;
                        alert('提供帮助订单提交成功！');
                        location.href = '/user/ldohelp.html';

                    }
                    if (data == 2) {
                        alert('支付密码不正确，请重新输入！');
                        $('#paypassword').focus();
                        $('#button').prop('disabled', false);

                    }
                    if (data == 3) {
                        alert('排金币余额不足，请先联系客服充值！');
                        $('#yzje').focus();
                        $('#button').prop('disabled', false);

                    }
                    if (data == 4) {
                        alert('提供帮助金额不能超过会员额度！');
                        $('#yzje').focus();
                        $('#button').prop('disabled', false);
                    }
                    if (data == 5) {
                        alert('报歉，今日额度已经排完！');
                        $('#yzje').focus();
                        $('#button').prop('disabled', false);
                    }
                    if(data==0)
                    {
                        alert('登录已过期或者在其它设备登录，请重新登录。');
                        location.href='/';
                        return false;

                    }


                }
            })
            return false;
        }
        else {
            alert('您已经提交过了，不能重复提交，请耐心等候处理!');
            return false;
        }
    }
}


var changeprice=function(price)
{
    $('#total').val(price*0.01);
}

