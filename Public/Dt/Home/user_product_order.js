
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {


    
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
var SetNumberdec=function()
    {

        if($.trim($('#number').val())=='')
        {
            $('#number').focus();
            $('#number').val(1);
            return;

        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test($.trim($('#number').val()))){
            alert('商品数量只能是数值');
            $('#number').focus();
            $('#number').val(1);
            return;
        }
        if($.trim($('#number').val())<=1)
        {


            alert('商品数量不能低于1件');
            $('#number').val(1);
            $('#number').focus();
            return;

        }
        $('#number').val( parseInt($('#number').val())-1);
        $('#totalprice').html(parseFloat(parseInt($('#number').val())*parseInt($('#price').html())).toFixed(2));

    }
    //加数量
    var SetNumberinc=function()
    {

        if($.trim($('#number').val())=='')
        {
            $('#number').focus();
            $('#number').val(1);
            return;

        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test($.trim($('#number').val()))){
            alert('商品数量只能是数值');
            $('#number').focus();
            $('#number').val(1);
            return;
        }
        if($.trim($('#number').val())>100)
        {

            alert('商品数量不能超过100件');
            $('#number').focus();
            $('#number').val(1);
            return;

        }
        $('#number').val( parseInt($('#number').val())+1);
        $('#totalprice').html(parseFloat(parseInt($('#number').val())*parseInt($('#price').html())).toFixed(2));
    }






