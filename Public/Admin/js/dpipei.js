/**
 * Created by Administrator on 2016/11/24.
 */
var searchuser=function()
{
    var str='/admin/index/dpipei?';
    if($.trim($('#k').val())!='')
       str+='k='+$('#k').val();
    if($.trim($('#b').val())!='')
        str+='&b='+$('#b').val();
    if($.trim($('#e').val())!='')
        str+='&e='+$('#e').val();
    location.href=str;


}


var Check=function()
{
    var boolstr=false;
    $(':checkbox').each(function(){

        if($(this).prop('checked')==true)
            boolstr=true;
    })
    if(boolstr==false)
    {
      alert('请先选择匹配的对象！');
      return false;
    }
    else
    {
        return true;
    }
}


