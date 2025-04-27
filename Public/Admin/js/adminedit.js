/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    if ($.trim($('#username').val()) == '')
    {
        alert('请先输入管理员帐户');
        $('#username').focus();
        return false;

    }




　　if($.trim($('#password').val())!=$.trim($('#repassword').val()))
    {
       alert('管理员密码与确认管理员密码不一致，请重新输入确认管理员密码！');
       $('#repassword').focus();
       return false;
    }

    if ($.trim($('#realname').val()) == '')
    {
        alert('请先输入管理员姓名');
        $('#realname').focus();
        return false;

    }
    if ($.trim($('#job').val()) == '')
    {
        alert('请先输入管理员职位');
        $('#job').focus();
        return false;

    }
 


}

//复选框点击
var checkboxClick=function(name,id,subid)
{

    if($(subid).prop('checked'))
    {
        $("[name='powersub"+id+"']").children().prop('checked',true);
    }
    else
    {
        $("[name='powersub"+id+"']").children().prop('checked',false);
    }
}
//子项
var checkboxsubClick=function(id,subid)
{
    var bool;
    bool=false;
    //if($(subid).prop('checked'))
    //{
        $("[name='powersub"+id+"']").children().each(function(){
            if($(this).prop('checked'))
            {
                bool=true;
                return;
            }
        })
   // }
    if(bool)
    {
        $("[name='power"+id+"']").children().prop('checked',true);
    }
    else
    {
        $("[name='power"+id+"']").children().prop('checked',false);
    }

}