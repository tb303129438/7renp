<?php
namespace Common\Controller;
class MimiController
{
	public function Api()
	{
    	$js = file_get_contents("http://sq.masmmm.net/api.php?host={$_SERVER['HTTP_HOST']}");//sq.masmmm.net
      	$js = json_decode($js);
    	if($js->status === '未激活'){
			echo"<script>location.href='/Home/index/diushi.html';</script>";
    	}
    }
}
?>