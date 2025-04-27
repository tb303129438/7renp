<?php
namespace Common\Controller;
use Think\Controller;
class UserController extends Controller
{
    protected function _initialize()
    {
	    header("Content-Type: text/html;charset=utf-8");
        $this->checkLoginSession();
        $this->checkAdminSession();
        $this->GetUserIntegral();
        //$this->get_news();//取得公告
        //$this->get_cart();//取得购物车
		//$this->checkvip();

        if(F('ad')==null)
        {
            $D=M('ad');
            $data=$D->select();
            F('ad',$data);
            $f=F('ad');
        }
        else
        {
            $f=F('ad');

        }

        $this->assign('ad',$f);

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
		if(empty($_SESSION['adminname'])||empty($_SESSION['id']))
		{
            unset($_SESSION['adminname']);  
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1); 
			//redirect('/home/index/login',2,'当前用户未登录或登录超时，请重新登录');
			echo "<script>location.href='/login.html'</script>";
			die();
        }
        else
        {
//            echo session('admin_login').'<br>';
//            echo session_id();
//            var_dump(F('login'));
//            die();
            //前台登录
//            if(session('admin_login')==0)
//            {
//                $data=F('login');
//                if(!empty($data))
//                {
//                    foreach(F('login') as $vo) {
//                        if($vo['userid']==session('userid'))
//                        {
//                            if($vo['session_id']!=session_id()) {
//                                if($vo['login_time']+24*60>time()) {
//                                    session(null);
//                                    echo"<script>alert('用户已经在其它设备登录，请重新登录。');location.href='/'</script>";
//                                    die();
//                                    break;
//                                }
//                                else
//                                {
//                                    session(null);
//                                    echo"<script>location.href='/'</script>";
//                                    die();
//                                }
//                            }
//                        }
//                    }
//                }
//            }
        }
    }
    public function checkAdminSession() {

        //设置超时为10分
        $nowtime = time();
        $s_time = $_SESSION['logintime'];
        if (($nowtime - $s_time) > 3600) {
            unset($_SESSION['logintime']);
            //$this->error('当前用户未登录或登录超时，请重新登录','admin/login/login',1);
            echo "<script>alert('用户登录超时，请重新登录！');location.href='/login.html'</script>";
            die();
        } else {
            $_SESSION['logintime'] = $nowtime;
        }
    }


    //获取公告
    public function get_news()
    {
        $D=M('news');
        $data=$D->order('id desc')->select();
        $news_number=count($data);
        $this->assign('news_list',$data);
        $this->assign('news_number',$news_number);
    }

    //获取购物车
    public function get_cart()
    {
        $D=M('cart');
        $data=$D->where('userid='.session('userid'))->select();
        $cart_number=count($data);

        $this->assign('cart_number',$cart_number);
    }

    private function GetUserIntegral()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();
        $this->assign('integral',$data);

    }

    //每天的额度
    public function GetTodayPrice()
    {
        $D=M('tohelp');
        $date= date('Y-m-d',time());
        $datebegin=strtotime($date);
        $dateend=$datebegin+24*3600;
        $data=$D->field('sum(price) as total')->where('createtime>='.$datebegin.' and createtime<'.$dateend)->select();
        $arr=F('para');

        $todaytohelpprice=$arr[2]['number']-$data[0]['total'];
        $this->assign('todaytohelpprice',$todaytohelpprice);
        $D=M('gethelp');
        $data=$D->field('sum(price) as total')->where('createtime>='.$datebegin.' and createtime<'.$dateend)->select();
        $todaygethelpprice=$arr[2]['number']-$data[0]['total'];
        $this->assign('todaygethelpprice',$todaygethelpprice);


    }

//超过12小时不打款冻结帐户
    private function offuser()
    {
        $TG=M('toget');
        $T=M('tohelp');
        $U=M('user');
        $I=M('integral');
        $tdata=$T->where('status=1')->select();
        $time=time();
        $hour12=3600*12;//12小时
        foreach($tdata as $vo)
        {
            $lasttime=$time-$vo['begintime'];

            if($lasttime>$hour12)
            {
                //冻结帐户
                $arr=array('status'=>3);
                $U->where('id='.$vo['userid'])->save($arr);

                //扣除推荐人300
                //$udata=$U->field('tusername')->where('id='.$vo['userid'])->find();
                //$udata1=$U->field('id')->where('username="'.$udata['tusername'].'"')->find();
                //$I->where('userid='.$udata1['id'])->setdec('integral2',300);


            }
        }

    }
 

}
?>