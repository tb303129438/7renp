/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/user/freeze?';
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;

}
