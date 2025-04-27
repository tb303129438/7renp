/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/admin/index/member?';
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


var changetohelp=function(id1,id,str)
{


    $.ajax({
        type: "POST",
        url: "/admin/index/tohelp",
        data: {'status':str,'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改成功！');
                if(str==1)
                {
                    $(id1).prop('onclick','changestatus(this,'+id+',0)');
                    $(id1).html('关闭排单');
                }
                else
                {
                    $(id1).prop('onclick','changestatus(this,'+id+',1)');
                    $(id1).html('充许排单');
                }
            }


        }
    })



}



var deleteuser=function(id)
{
   if(confirm('确认是否删除此会员，会员删除后无法恢复！'))
   {

    $.ajax({
        type: "POST",
        url: "/admin/index/delete",
        data: {'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('删除会员成功！');
                location.href='/admin/index/member.html';

            }


        }
    })
   }


}


var djeuser=function(id)
{
   if(confirm('确认是否冻结此会员'))
   {

    $.ajax({
        type: "POST",
        url: "/admin/index/djeuser",
        data: {'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('冻结会员成功！');
                location.href='/admin/index/member.html';

            }


        }
    })
   }


}