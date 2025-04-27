<?php
namespace Common\Controller;
use Think\Controller;
class CheckController extends Controller
{
    protected function _initialize()
    {
	    header("Content-Type: text/html;charset=utf-8");
       
        $this->checkLoginSession();
        $this->checkAdminSession();
        $domain=$_SERVER['REQUEST_URI'];
        // if(isMobile()){
            
           
        //     if(stripos($domain,'/Pc/') !== false)
        //     {
        //         $domain=str_ireplace('/Pc/','/Home/',$domain);
        //          echo "<script>location.href='".$domain."';</script>";
        //     }
        // }
        // else
        // {
           
        //     if(stripos($domain,'/Home/') !== false)
        //     {
              
        //         $domain=str_ireplace('/Home/','/Pc/',$domain);
              
        //         echo '<script>location.href="'.$domain.'";</script>';
                
        //     }
        //  }
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
        $s_time = $_SESSION['username'];  
        if($_SESSION['username']==''||$_SESSION['id']=='')
        {
            unset($_SESSION['username']);  
            if(isMobile()){
                echo "<script>alert('用户未登录，请先登录！');location.href='/Home/index/login.html'</script>";
                die();
            }else{
                 echo "<script>alert('用户未登录，请先登录！');location.href='/Pc/index/login.html'</script>";
                 die();
            }
           
            
        }
 
    }
    public function checkAdminSession() {

        //设置超时为10分
        $nowtime = time();
        if($_SESSION['logintime']=='')
        {
            
            echo "<script>alert('用户登录超时，请重新登录！');location.href='/Home/index/login.html'</script>";
                die();
        }else
        {

            $s_time = $_SESSION['logintime'];
            if (($nowtime - $s_time) > 3600) {
                unset($_SESSION['logintime']);
            
                echo "<script>alert('用户登录超时，请重新登录！');location.href='/Home/index/login.html'</script>";
                die();
                // if(isMobile()){
                //     echo "<script>alert('用户登录超时，请重新登录！');location.href='/Home/index/login.html'</script>";
                //     die();
                // }else{
                //      echo "<script>alert('用户登录超时，请重新登录！');location.href='/Pc/index/login.html'</script>";
                //      die();
                // }

            } else {
                $_SESSION['logintime'] = $nowtime;
            }
        }
    }

   





 

}
?>