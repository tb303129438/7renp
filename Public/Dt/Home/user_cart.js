
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {


    if(parseInt($('#totalprice').val())<=0)
    {
        alert("请先选择要下单的商品！");
        return false;
    }


    if ($.trim($('#realname').val()) == '')
    {

        alert("请先添加联系人！");
        $('#realname').focus();
        return false;


    }
    if ($.trim($('#mobile').val()) == '')
    {

        alert("请先添加联系手机！");
        $('#mobile').focus();
        return false;


    }
    var reg = /^1[3-9][0-9]\d{4,8}$/;
    if(!reg.test($.trim($('#mobile').val())))
    {
        alert("手机号码格式不对！");
        $('#mobile').focus();
        return false;
    }
    if ($.trim($('#address').val()) == '')
    {

        alert("请先添加收货地址！");
        $('#address').focus();
        return false;


    }



}
//减数量
var SetNumberdec=function(id)
    {

        if($.trim($('#number'+id).val())=='')
        {
            $('#number'+id).focus();
            $('#number'+id).val(1);
            return;

        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test($.trim($('#number'+id).val()))){
            alert('商品数量只能是数值');
            $('#number'+id).focus();
            $('#number'+id).val(1);
            return;
        }
        if($.trim($('#number'+id).val())<=1)
        {


            alert('商品数量不能低于1件');
            $('#number'+id).val(1);
            $('#number'+id).focus();
            return;

        }
        $('#number'+id).val( parseInt($('#number'+id).val())-1);
        $('#total'+id).val(parseFloat(parseInt($('#number'+id).val())*parseInt($('#price'+id).html())).toFixed(2));
        
        if($('#id'+id).prop('checked'))
           $('#totalprice').val((parseFloat($('#totalprice').val())-(parseInt($('#price'+id).html())).toFixed(2)).toFixed(2));

    }
    //加数量
    var SetNumberinc=function(id)
    {

        if($.trim($('#number'+id).val())=='')
        {
            $('#number'+id).focus();
            $('#number'+id).val(1);
            return;

        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test($.trim($('#number'+id).val()))){
            alert('商品数量只能是数值');
            $('#number'+id).focus();
            $('#number'+id).val(1);
            return;
        }
        if($.trim($('#number'+id).val())>100)
        {

            alert('商品数量不能超过100件');
            $('#number'+id).focus();
            $('#number'+id).val(1);
            return;

        }
        $('#number'+id).val( parseInt($('#number'+id).val())+1);
        $('#total'+id).val(parseFloat(parseInt($('#number'+id).val())*parseInt($('#price'+id).html())).toFixed(2));
        if($('#id'+id).prop('checked'))
            $('#totalprice').val((parseFloat($('#price'+id).html())+parseFloat($('#totalprice').val())).toFixed(2));
    }
//从购物车里移除
var DeleteCart=function(id)
{
    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {
            'id': id,
            'action': 'deletecart',

        },
        async: true,

        success: function (data) {

        if(data==1)
        {
            alert('移除商品成功!');
            location.href='/user/cart.html';
        }

        }
    });
}

//从购物车里移除
var DeleteAllCart=function()
{
    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {

            'action': 'deleteallcart',

        },
        async: true,

        success: function (data) {

            if(data==1)
            {
                alert('清空购物车成功!');
                location.href='/user/cart.html';
            }

        }
    });
}

//选择购物车
var ChooseProduct=function(obj,id)
{

    if($(obj).prop('checked'))
       $('#totalprice').val((parseFloat($('#total'+id).val())+parseFloat($('#totalprice').val())).toFixed(2));
    else
        $('#totalprice').val((parseFloat($('#totalprice').val())-parseFloat($('#total'+id).val())).toFixed(2));
}

var product=function()
{
    location.href='/user/product.html';
}

