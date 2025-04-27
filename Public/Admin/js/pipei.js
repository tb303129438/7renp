/**
 * Created by Administrator on 2016/12/29.
 */

/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {
    
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
        url: "/admin/index/dopassword",
        data: {'action':'password','password':password,'newpassword':newpassword},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('修改登录密码成功！');
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
//匹配
var pipei=function(id)
{
    var str;
    str='';
    $('input[name=T'+id+']:checked').each(function(){
        str+=$(this).val()+',';

    })
    $('#buttont'+id).prop('disabled',true);


    $.ajax({
        type: "POST",
        url: "/admin/index/dopipei",
        data: {'action':1,'id':id,'str':str},
        async:false,
        //dataType: "json",
        success: function(data){



            if(data==1)
            {
                alert('匹配成功！');
                //$('#button'+id).prop('disabled',false);
                location.href='/admin/index/pipei.html';

            }



        }
    })
}


//匹配
var pipeiget=function(id)
{
    var str;
    str='';
    $('input[name=T'+id+']:checked').each(function(){
        str+=$(this).val()+',';

    })
    $('#buttong'+id).prop('disabled',true);

    $.ajax({
        type: "POST",
        url: "/admin/index/dopipei",
        data: {'action':2,'id':id,'str':str},
        async:false,
        //dataType: "json",
        success: function(data){



            if(data==1)
            {
                alert('匹配成功！');
                //$('#button'+id).prop('disabled',false);
                location.href='/admin/index/pipei.html';

            }



        }
    })
}



var caidan=function(id,t)
{
    location.href='/admin/index/dpipei.html?id='+id+'&t='+t;
}

