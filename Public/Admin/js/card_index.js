

//添加分类
var create=function()
{
     if ($.trim($('#cardbegin').val()) == '')
     {
        alert('请先输入开始卡号!');
        $('#cardbegin').focus();
        return false;

    }


    if ($.trim($('#cardnum').val()) == '')
     {
        alert('请先输入生成的数量!');
        $('#cardnum').focus();
        return false;

    }
    var r = /^\+?[1-9][0-9]*$/;　　//正整数
  
    if(!r.test($('#cardnum').val()))
    {
         alert('数量值必须为数值!');
         $('#cardnum').focus();
         return false;
    }

   if ($.trim($('#price').val()) == '')
     {
        alert('请先输入生成的充值卡的价值!');
        $('#price').focus();
        return false;

    }
    
  
    if(!r.test($('#price').val()))
    {
         alert('数量值必须为数值!');
         $('#price').focus();
         return false;
    }


    $('#createcard').hide();
    $('#createresuft').show();
    var num=0;
    var cardbegin=$('#cardbegin').val();
    var cardnum=$('#cardnum').val();
    var price=$('#price').val();
     $.ajax({
        type: "POST",
        url: "/admin/card/ajax",
        data: {'action':'create','cardbegin':cardbegin,'cardnum':cardnum,'price':price},
        async:false,
        //dataType: "json",
        success: function(data){
             
           
              num=parseInt(num,10)+parseInt(data,10);
             
             alert('生成卡号成功');
             location.reload();



        }
    })
}

