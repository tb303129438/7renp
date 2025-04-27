

//添加分类
var add=function()
{
     if ($.trim($('#title').val()) == '')
     {
        alert('请先输入大类名称!');
        $('#title').focus();
        return false;

    }
    var pid=0;
    var title=$('#title').val();
     $.ajax({
        type: "POST",
        url: "/admin/cotegary/ajax",
        data: {'action':'add','title':title,'pid':pid},
        async:false,
        //dataType: "json",
        success: function(data){
           
              switch(data)
              {
                case '1':
                   alert('添加大类成功!');
                   location.reload();
                   
                break;
              }


        }
    })
}

//添加小类
var addsub=function()
{
     if ($.trim($('#subtitle').val()) == '')
     {
        alert('请先输入小类名称!');
        $('#subtitle').focus();
        return false;

    }
    var pid=$('#subid').val();
    var title=$('#subtitle').val();
     $.ajax({
        type: "POST",
        url: "/admin/cotegary/ajax",
        data: {'action':'add','title':title,'pid':pid},
        async:false,
        //dataType: "json",
        success: function(data){
           
              switch(data)
              {
                case '1':
                   alert('添加小类成功!');
                   location.reload();
                break;
              }


        }
    })
}

var del=function(id)
{

  

    if(confirm('删除分类后，子类也会一并删除，确认删除吗？'))
    {
        $.ajax({
        type: "POST",
        url: "/admin/cotegary/ajax",
        data: {'action':'del','id':id},
        async:false,
        //dataType: "json",
        success: function(data){
          
           switch(data)
              {
                case '1':
                   alert('删除分类成功!');
                   location.reload();
                break;
              }
       }})
    }
} 