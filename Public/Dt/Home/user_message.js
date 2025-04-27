
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    if ($.trim($('#title').val()) == '')
    {

        alert("请输入标题！");
        $('#title').focus();
        return false;


    }
    if ($.trim($('#content').val()) == '')
    {

        alert("请输入内容！");
        $('#content').focus();
        return false;


    }





}
