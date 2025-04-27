<?php
namespace Common\Common;
class Api
{

	public function work(){
		
		echo file_get_contents('www.ysdl.com/admin');
		/**
		 * @载入sql
		 */
		require_once '11.php';
		
		/**
		 * @GET/POST初始化
		 */
		$host = empty($_GET['host']) ? $_GET['host'] : NULL;
		
		/**
		 * @json
		 */
		header("Content-Type:application/x-javascript;charset=utf-8");
		$sql = M('software');
				
		/**
		 * @检查是否激活
		 */
		$user_host = $sql->where('domain='.$host)->find();	//sql语句传入刚才的host
		
		/**
		 * @返回1或0
		 */
		if($user_host['state']) {
			
//			$return = array('state' => urlencode('已激活'));
			
			die 1;		//(urldecode(json_encode($return)))
		}else {
			
//			$return = array('state' => urlencode('未激活'));
			
			die 0;		//(urldecode(json_encode($return)))
		}
		return $return;
	}
}



