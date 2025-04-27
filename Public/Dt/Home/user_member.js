/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/user/member?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}

var configuser=function(id)
{
    if(confirm('是否确认开通帐户！'))
    {
    $.ajax({
        type: "POST",
        url: "/user/ajax",
        data: {'action':'configuser','id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('开通成功！');
                location.href='/user/member.html';
            }

            if(data==2)
            {
                alert('激活码不足，请联系客户充值激活码！');
                $('#yzje').focus();
                $('#button').prop('disabled',false);

            }


        }
    })
    }
}