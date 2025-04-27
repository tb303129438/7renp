/**
 * Created by Administrator on 2016/12/20.
 */

//修改基本资料
function Check()
{
    

    
   
    if ($.trim($('#mobile').val()) == '')
    {
        alert('请先输入手机号码！');
        $('#mobile').focus();
        return false;

    }



    var reg = /^1(3|4|5|7|8)\d{9}$/;
    if(!reg.test($.trim($('#mobile').val())))
    {
        alert("手机号码格式不对！");
        $('#mobile').focus();
        return false;
    }


    if ($.trim($('#realname').val()) == '')
    {
        alert('请先输入姓名！');
        $('#realname').focus();
        return false;

    }





    save();
    return false;
}


//提交会员注册
var save=function()
{
    var username,tusername,mobile,realname,pr,ci,di,address;
    username=$.trim($('#mobile').val());
   
    tusername=$.trim($('#tusername').val());
    
    mobile=$.trim($('#mobile').val());
    realname=$.trim($('#realname').val());
 
    pr=$("#pr").val();
    ci=$("#ci").val();
    di=$("#di").val();
    address=$("#address").val();
   

    $.ajax({
        type: "POST",
        url: "/user/doprofile",
        data: {'username':username,'tusername':tusername,'mobile':mobile,'realname':realname,'pr':pr,'ci':ci,'di':di,'address':address},
        async:false,
        //dataType: "json",
        success: function(data){

            
            if(data==1)
            {
                alert('修改成功！');
                location.reload();
            }
            
            
            if(data==2)
            {
                alert('手机已经被使用！');
                $('#tusername').focus();
                return false;
            }
            
            

        }
    })
    return false;
}
