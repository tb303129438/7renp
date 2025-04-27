/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/user/lgethelp?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}
//确认收款
var pipeifinish=function(id1,id2)
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
    if(confirm('请确认已经收到款？')) {
       
           
            $.ajax({
                type: "POST",
                url: "/user/ajax",
                data: {'action': 'pipeifinish', 'id1': id1, 'id2': id2,'id':id,'pwd':$('#password'+id1+'_'+id2).val()},
                async: true,
                //dataType: "json",
                success: function (data) {

                    if (data == 1) {
                        sign = false;
                        alert('确认打款成功！');
                        $('#pull' + id1 + '_' + id2).html('已经确认打款，得到帮助完成。');
                        return false;

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
                        alert('安全密码不正确，请重新输入！');
                        $('#password'+id1+'_'+id2).focus();
                        $('#button' + id1 + '_' + id2).prop("disabled", false);
                        
                        return false;

                    }

                }
            })
        }
       
}
