
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    if ($.trim($('#productname').val()) == '')
    {

        alert("请输入产品标题！");
        $('#productname').focus();
        return false;


    }

    if ($.trim($('#price').val()) == '')
    {

        alert("请输入产品价格！");
        $('#price').focus();
        return false;


    }
    var reg = new RegExp("^[0-9]*$");
    if(!reg.test($.trim($('#price').val()))){
        alert("请输入整数!");
        $('#price').focus();

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
