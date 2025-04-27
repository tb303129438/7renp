/**
 * Created by Administrator on 2016/12/20.
 */

//修改基本资料
function Check()
{
 
    
    if ($.trim($('#username').val()) == '')
    {
        alert('请先输会员账号！');
        $('#username').focus();
        return false;
    }
    if ($.trim($('#tusername').val()) == '')
    {
        alert('中心名称不能为空！');
        $('#tusername').focus();
        return false;
    }
    
    save();
    return false;
}
var save=function()
{


    var username,tusername,city;
    username=$.trim($('#username').val());
    tusername=$.trim($('#tusername').val());
    city=$.trim($('#city').val());
   

    $.ajax({
        type: "POST",
        url: "/Home/wo/dosqbdzx",
        data: {'username':username,'tusername':tusername,'city':city},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('提交成功！');
                location.href='/Home/wo/wo.html';
            }
            if(data==2)
            {
                alert('填写的会员帐号有误,请核查！');
                $('#username').focus();
                return false;
            }
            
        }
    })
}

