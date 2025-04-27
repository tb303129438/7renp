/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/admin/index/message?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}

var changestatus=function(id,str)
{


    $.ajax({
        type: "POST",
        url: "/admin/index/status",
        data: {'status':str,'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改成功！');
                location.href='/admin/index/member.html';
            }


        }
    })



}
