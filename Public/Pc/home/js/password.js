
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    if ($.trim($('#password').val()) == '')
    {
        alert("请输入原登录密码！");
        $('#password').focus();
        return false;
    }

    if($.trim($('#password').val()).length<6)
    {
        alert("原登录密码不能小于6位数！");
        $('#password').focus();
        return false;
    }

    if ($.trim($('#newpassword').val()) == '')
    {

        alert("请输入新登录密码！");
        $('#newpassword').focus();
        return false;


    }
    if($.trim($('#newpassword').val()).length<6)
    {
        alert("新登录密码不能小于6位数！");
        $('#newpassword').focus();
        return false;
    }
    if($.trim($('#password').val())==$.trim($('#newpassword').val()))
    {
        alert("新登录密码不能与原登录密码相同，请重新输入！");
        $('#newpassword').focus();
        return false;
    }


    if ($.trim($('#renewpassword').val()) == '')
    {

        alert("请输入确认新登录密码！");
        $('#renewpassword').focus();
        return false;


    }
    if($.trim($('#renewpassword').val()).length<6)
    {
        alert("确认新登录密码不能小于6位数！");
        $('#renewpassword').focus();
        return false;
    }
    if($.trim($('#newpassword').val())!=$.trim($('#renewpassword').val()))
    {
        alert("新登录密码与确认新登录密码不一致，请重新输入！");
        $('#renewpassword').focus();
        return false;
    }

    var password,newpassword;
    password=$.trim($('#password').val());
    newpassword=$.trim($('#newpassword').val());


    $.ajax({
        type: "POST",
        url: "/pc/wo/dommxg",
        data: {'action':'password','password':password,'newpassword':newpassword},
//      async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改登录密码成功！');
                $('#password').val('');
                $('#newpassword').val('');
                $('#renewpassword').val('');
            }
            if(data==2)
            {
                alert('原登录密码不正确，请输入正确的原登录密码！');
                $('#password').focus();
            }


        }
    })
    return false;

}


