<?php
//$action = $_GET['act'];
//if($action=='delimg'){
//	$filename = $_POST['imagename'];
//	if(!empty($filename)){
//		unlink('/Public/upload/files/'.$filename);
//		echo '1';
//	}else{
//		echo '删除失败.';
//	}
//}else{
//if(!empty($_SSESION('userid'))||!empty($_SSESION('adminid')))
//{
	$picname = $_FILES['mypic']['name'];
	$picsize = $_FILES['mypic']['size'];
	if ($picname != "") {
		if ($picsize > 1024000) {
			echo '图片大小不能超过1M';
			exit;
		}
		$type = strstr($picname, '.');
		if ($type != ".gif" && $type != ".jpg"&& $type != ".png") {
			echo '图片格式不对！';
			exit;
		}
		$rand = rand(100, 999);
		$pics = time() . $rand . $type;
		//上传路径
		$pic_path = "files/". $pics;
		move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
	}
	$size = round($picsize/1024,2);
	$arr = array(
		'name'=>$picname,
		'pic'=>$pics,
		'size'=>$size
	);
	echo json_encode($arr);
//}
//}
?>