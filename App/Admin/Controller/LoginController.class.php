<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends Controller {
	
    public function index(){
        $this->Api();
        $username=I('get.u');
        $code=I('get.v');
        $this->assign('username',$username);
        $this->assign('code',$code);
        $this->display();
    }
    //用户登录
    public function dologin()
    {

        header("Content-Type: text/html;charset=utf-8");
        $username = I('post.username');
        $mobile_code = I('post.mobile_code');
        $password = I('post.password');
        
        //检测验证码
        $code = I('post.verify');

        // if (empty($mobile_code))
        // {
        //     echo"<script>alert('请输入的手机验证码！');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
        //     exit;
        // }


        // if ($mobile_code!=session('login_mobile_code'))
        // {
        //     echo"<script>alert('输入的手机验证码有误，请重新输入！');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
        //     exit;
        // }



        if(!$this->check_verify($code, ''))
        {

            echo"<script>alert('输入的验证码有误，请重新输入！');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
            exit;
        }
        else
        {
            session(null);
        }
        if(!empty($username)){

            $data['username'] = $username;

            $admin = M('admin');
            $info = $admin->where("adminname='".$data['username']."'")->find();


            if(!empty($info))
            {

                if($this->_checkPwd($password,$info['password']))
                {


                session('adminid',$info['id']);
                session('adminname',$info['adminname']);
                session('adminpower',$info['power']);
                session('adminlogintime',time());//登录时间





                //系统初始设置
                //$this->Setting();
                //$this->TaskAutoClose();//定时自动删除

                redirect('/admin/index/index.html');
                }
                else
                {
                    //$this->error('用户帐户或者密码有误，请重新输入','/',2);
                    echo"<script>alert('用户密码有误，请重新输入');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
                    die();
                }

                //echo "<script>window.parent.location.reload();<//script>";
                //echo "<script>window.parent.Windows.openApp(2,\"个人中心\",\"/home/user/index\",\"user.png\",\"\",1100,500);window.parent.Windows.closeElseWindow(2);<//script>";
            }
            else
            {

                //$this->error('用户帐户或者密码有误，请重新输入','/',2);
                echo"<script>alert('用户帐户有误，请重新输入');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
                die();
            }
            exit;
        }else{

            echo"<script>alert('会员帐户为空，请重新输入');location.href='/admin/login/index?u=".$username."&v=".$code."'</script>";
            die();
        }
    }
    public function verify(){

    
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->fontSize = 16;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        //$Verify->codeSet = '0123456789a-z';
        $Verify->imageW = 120;
        $Verify->imageH = 40;
        //$Verify->expire = 600;
        $Verify->entry();

    }
    //检测验证码
    public function check_verify($code, $id = ''){
        $verify = new Verify();
        return $verify->check($code, $id);
    }


    //短信验证码接口
    public function smslogin()
    {


        header("Content-type:text/html; charset=UTF-8");
//获取手机号
        //$mobile = '13581298444';
       $mobile = '18665594829';
        //$temp=

//获取验证码
        $send_code = I('post.send_code');



        vendor('smsapi');

        //用户账号
        $uid = 'xxxs';
        //MD5密码
        $pwd = 'Nanyiwangji11';

        $api = new \SmsApi($uid,$pwd);


        $contentParam = array(
            'code'		=> $api->randNumber(),
            'name'	=> '',

        );

        //变量模板ID
        //$template = '394713';
        $template='397443';


//发送变量模板短信
        $result = $api->send($mobile,$contentParam,$template);

        if($result['stat']=='100')
        {
            session('mobile',$mobile);
            session('login_mobile_code', $contentParam['code']);
            echo 4;
            die();
        }
        else
        {

            echo 7;
            die();
        }



    }
    /**
     * 密码加密
     */
   public function md5pw($passwd)
    {
        $ph = new \Think\PasswordHash();
        return $ph->HashPassword($passwd);

    }

    /**
     * 检测密码是否正确
     * @param $getPwd
     * @param $dataPasswd
     */
   public function _checkPwd($getPwd,$dataPasswd)
    {
        $ph = new \Think\PasswordHash();
        if($ph->CheckPassword($getPwd,$dataPasswd))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}