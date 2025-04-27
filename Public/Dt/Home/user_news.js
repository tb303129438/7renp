/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/user/news.html?';

    if($.trim($('#t').val())!='')
        str+='t='+$('#t').val();

    location.href=str;

}
