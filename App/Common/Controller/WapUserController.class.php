<?php
namespace Common\Controller;
use Think\Controller;
class WapUserController extends Controller
{
    protected function _initialize()
    {
	    header("Content-Type: text/html;charset=utf-8");
        $this->checkLoginSession();
		//$this->checkvip();
		//$this->checkAdminSession();  
    }
	/*
	public function __construct() {  
        parent::__construct();  
		echo("test");
        $this->checkAdminSession();  
    }  
    */
    public function checkAdminSession() {  
	   
        //设置超时为10分  
        $nowtime = time();  
        $s_time = $_SESSION['admintime'];  
        if (($nowtime - $s_time) > 600) {  
            unset($_SESSION['admintime']);  
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			//redirect('/home/index/login',2,'当前用户未登录或登录超时，请重新登录');
			
			echo "<script>alert('当前用户未登录或登录超时，请重新登录');location.href='/home/index/login'</script>"; 
			die();  
        } else {  
            $_SESSION['admintime'] = $nowtime;  
        }  
    }  
	 public function checkLoginSession() {  
	 
        //设置超时为10分  
        $nowtime = time();  
        $s_time = $_SESSION['uid'];  
		if(empty($_SESSION['uid']))
		{
            unset($_SESSION['uid']);  
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			//redirect('/home/index/login',2,'当前用户未登录或登录超时，请重新登录');
			echo "<script>location.href='/wap/index/login.html'</script>"; 
			die();
        }
    }  
	//检测vip用户
	public function checkvip()
	{
	    $user=M('member');
		$time=mktime();
		$info=$user->where('memberid='.$_SESSION['userid'])->find();
		if($info["vipetime"]<$time)
		{
		   $data['grade']=1;
		   $user->where('memberid='.$_SESSION['userid'])->save($data);
		}
	}
}
?>