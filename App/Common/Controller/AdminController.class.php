<?php
namespace Common\Controller;
use Think\Controller;
class AdminController extends Controller
{
    protected function _initialize()
    {
	    header("Content-Type: text/html;charset=utf-8");
        $this->checkLoginSession();
        $this->checkAdminSession();


    }
	/*
	public function __construct() {  
        parent::__construct();  
		echo("test");
        $this->checkAdminSession();  
    }  
    */

	public function checkLoginSession() {  
	 
        //设置超时为10分  
        $nowtime = time();  
        $s_time = $_SESSION['adminname'];
		if(empty($_SESSION['adminname'])||empty($_SESSION['adminid']))
		{
            unset($_SESSION['adminname']);
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			//redirect('/home/index/login',2,'当前用户未登录或登录超时，请重新登录');
			echo "<script>alert('用户未登录，请先登录！');location.href='/admin.php'</script>";
			die();
        }
    }
    public function checkAdminSession() {

        //设置超时为10分
        $nowtime = time();
        $s_time = $_SESSION['adminlogintime'];
        if (($nowtime - $s_time) > 3600) {
            unset($_SESSION['adminlogintime']);
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1);
            echo "<script>alert('用户登录超时，请重新登录！');location.href='/admin.php'</script>";
            die();
        } else {
            $_SESSION['adminlogintime'] = $nowtime;
        }
    }
   





 

}
?>