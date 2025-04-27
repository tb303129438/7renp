
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {


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

    var password,newpassword,id;
    password=$.trim($('#password').val());
    newpassword=$.trim($('#newpassword').val());
    id=$.trim($('#id').val());


    $.ajax({
        type: "POST",
        url: "/admin/index/ajax",
        data: {'action':'password','newpassword':newpassword,'id':id},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改登录密码成功！');

                $('#newpassword').val('');
                $('#renewpassword').val('');
            }


        }
    })
    return false;



}


var CheckPayPassword=function() {


    if ($.trim($('#newpaypassword').val()) == '')
    {

        alert("请输入新安全密码！");
        $('#newpaypassword').focus();
        return false;


    }
    if($.trim($('#newpaypassword').val()).length<6)
    {
        alert("新安全密码不能小于6位数！");
        $('#newpaypassword').focus();
        return false;
    }
    if($.trim($('#paypassword').val())==$.trim($('#newpaypassword').val()))
    {
        alert("新安全密码不能与原安全密码相同，请重新输入！");
        $('#newpaypassword').focus();
        return false;
    }


    if ($.trim($('#renewpaypassword').val()) == '')
    {

        alert("请输入确认新安全密码！");
        $('#renewpaypassword').focus();
        return false;


    }
    if($.trim($('#renewpaypassword').val()).length<6)
    {
        alert("确认新安全密码不能小于6位数！");
        $('#renewpaypassword').focus();
        return false;
    }
    if($.trim($('#newpaypassword').val())!=$.trim($('#renewpaypassword').val()))
    {
        alert("新安全密码与确认新安全密码不一致，请重新输入！");
        $('#renewpaypassword').focus();
        return false;
    }

    var paypassword,newpaypassword,id;
    paypassword=$.trim($('#paypassword').val());
    newpaypassword=$.trim($('#newpaypassword').val());
    id=$.trim($('#id').val());



    $.ajax({
        type: "POST",
        url: "/admin/index/ajax",
        data: {'action':'paypassword','id':id,'newpaypassword':newpaypassword},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改安全密码成功！');
                $('#newpaypassword').val('');
               
            }



        }
    })
    return false;



}
