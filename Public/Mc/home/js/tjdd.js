	var Check=function() {
//  if ($.trim($('#city').val()) == '')
//  {
//      alert("请选择购车城市！");
//      $('#city').focus();
//      return false;
//  }
//  if($.trim($('#dist').val()) == '')
//  {
//      alert("请选择上牌城市！");
//      $('#dist').focus();
//      return false;
//  }

    if ($.trim($('#cr').val()) == '')
    {

        alert("请选择颜色！");
        $('#cr').focus();
        return false;
    }
    if($.trim($('#gc_fs').val()) == '')
    {
        alert("请选择购车方式！");
        $('#gc_fs').focus();
        return false;
    }
}
