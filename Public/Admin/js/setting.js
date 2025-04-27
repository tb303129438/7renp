
/**
 * Created by Administrator on 2016/11/23.
 */
var Check=function() {

    var web_config,tohelp,gethelp;

    web_config=$('input[name=web_config]:checked').val();
    tohelp=$('input[name=tohelp]:checked').val();
    gethelp=$('input[name=gethelp]:checked').val();



    $.ajax({
        type: "POST",
        url: "/admin/index/dosetting",
        data: {'web_config':web_config,'tohelp':tohelp,'gethelp':gethelp},
        async:false,
        //dataType: "json",
        success: function(data){

            if(data==1)
            {
                alert('设置开关成功！');
            }



        }
    })
    return false;



}


