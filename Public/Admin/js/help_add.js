
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    if ($.trim($('#typename').val()) == '')
    {

        alert("请输入标题！");
        $('#typename').focus();
        return false;


    }

   

    var stem = CKEDITOR.instances.editor.getData();
    if ($.trim(stem) == '')
    {

        alert("请输入内容！");
        $('#editor').focus();
        return false;


    }





}
