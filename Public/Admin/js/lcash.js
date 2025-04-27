/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/admin/index/lcash?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}

var deletehistory=function(id,url)
{
    if(confirm('确认是否删除此记录，记录删除后无法恢复！'))
    {

        $.ajax({
            type: "POST",
            url: "/admin/index/deletehistory",
            data: {'id':id},
            async:false,
            //dataType: "json",
            success: function(data){

                if(data==1)
                {
                    alert('删除成功！');
                    location.href=url;

                }


            }
        })
    }


}
