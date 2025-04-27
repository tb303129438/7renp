<?php
namespace Common\Controller;
use Think\Controller;
class InitController extends Controller
{
    protected function _initialize()
    {
	    header("Content-Type: text/html;charset=utf-8");
        $this->checkLoginSession();
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
	   
        //设置超时为1小时
        $nowtime = time();  
        $s_time = $_SESSION['admintime'];  
        if (($nowtime - $s_time) > 3600) {
            unset($_SESSION['admintime']);  
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			redirect('/admin/login/login',2,'当前用户未登录或登录超时，请重新登录');  
        } else {  
            $_SESSION['admintime'] = $nowtime;  
        }  
    }  
	 public function checkLoginSession() {  
	 
        //设置超时为10分  
        $nowtime = time();  
        $s_time = $_SESSION['admintime'];  
		if(empty($_SESSION['admintime'])||empty($_SESSION['mname']))
		{
            unset($_SESSION['admintime']);  
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			redirect('/admin/login/login',2,'当前用户未登录或登录超时，请重新登录'); 
        }
    }  
}
?>