/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/home/index/shopcar.html?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}

var del=function(id)
{


    $.ajax({
        type: "POST",
        url: "/admin/index/delproduct",
        data: {'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('删除成功！');
                location.href='/admin/index/product.html';
            }


        }
    })



}
