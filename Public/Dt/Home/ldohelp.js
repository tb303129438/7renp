/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/user/ldohelp?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}
var sign=false;//防止重复提交

//确认打款
var pipeiok=function(id1,id2)
{
    var id;
    id=$('#id').val();


    if($('#password'+id1+'_'+id2).val()=='')
    {
        alert('请输入安全密码!');
        $('#password'+id1+'_'+id2).focus();
        return false;
    }
     $('#button' + id1 + '_' + id2).prop("disabled", true);


   if(confirm('请确认已经打款？'))
   {
       
           var pic;

           pic = $('#img' + id2 + 'url').val();

           //if(pic=='')
           //{
           // alert('请先上传打款凭证！');
           // return false;
           //}

          

           $.ajax({
               type: "POST",
               url: "/user/ajax",
               data: {'action': 'pipeiok', 'id1': id1, 'id2': id2, 'pic': pic,'id':id,'pwd':$('#password'+id1+'_'+id2).val()},
               async: true,
               //dataType: "json",
               success: function (data) {

                   if (data == 1) {
                       sign = false;
                       alert('打款成功！');
                       $('#pull' + id1 + '_' + id2).html('已经打款!');

                   }
                   if(data==2)
                   {
                       alert('订单已完成，请不要重复提交！');
                   }
                   if(data==0)
                   {
                       alert('登录已过期或者在其它设备登录，请重新登录。');
                       location.href='/';
                       return false;

                   }
                   if (data == 3) {
                       alert('非法操作。');
                       location.href='/';
                       return false;

                   }
                   if (data == 4) {
                       alert('安全密码不正确，请重新输入。');
                       ('#password'+id1+'_'+id2).focus();
                        $('#button' + id1 + '_' + id2).prop("disabled", false);
                       return false;

                   }


               }
           })
       }
       
   
}
