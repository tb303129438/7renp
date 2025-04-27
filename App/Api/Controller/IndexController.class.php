<?php
namespace Api\Controller;
use Think\Controller;
use Think\Verify;

class IndexController extends Controller {

    //会员登录
    public function login(){
        header("Content-Type: text/html;charset=utf-8");
        $username=I('post.username');
        $password=I('post.password');
        //检测验证码
        // $code = I('post.verify');
        // $username='test';
        // $password='123456';
        // //检测验证码
        // $code = '1234';
        // if(!$this->check_verify($code, ''))
        // {

        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'error',
        //     );

        //     $this->ajaxReturn($arr);die;
        // }

        //超过12小时不打款，冻结帐户
        // $this->offuser();

        if(!empty($username)){

            $data['username'] = $username;
            $password = md5($password);
            $admin = M('user');
            // $info = $admin->where("(username='".$data['username']."' or mobile='".$username."') and password = '".$password."'")->find();
            $info = $admin->where("username='".$data['username']."' or mobile='".$username."'")->find();
            if(!$this->_checkPwd($password,$info['password']))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'密码错误，请重新输入，谢谢！',
                    // 'retMsg'=>'error',
                );

                $this->ajaxReturn($arr);die;
            }

            $userid=$info['id'];
          
            if(!empty($info))
            {
                if($info['status']==3)
                {
                    $arr=array(
                        'errnum'=>0,
                        'retMsg'=>'帐户已经被冻结，如有疑问请联系客服，谢谢！',
                        // 'retMsg'=>'error',
                    );

                    $this->ajaxReturn($arr);die;

                }
                if($info['status']==0)
                {
                    $arr=array(
                        'errnum'=>0,
                        'retMsg'=>'帐户还没有开通，请联系推荐人开通后再登录，谢谢！',
                        // 'retMsg'=>'error',
                    );

                    $this->ajaxReturn($arr);die;

                }
                
                session('userid',$info['id']);
                session('username',$info['username']);
                session('status',$info['status']);//0  未开通，１已开通　２休眠　３冻结　
                session('limit',$info['limit']);//投资额度
                session('fire',$info['fire']);//是否烧伤0 不烧伤，１烧伤
                session('logintime',time());//登录时间


                //计算当前级别
                $this->GetGotegary($info['id'],$info['username']);
                //解冻奖金
                $this->dofreeze($info['id']);

                //每天只能排一个单
                //提供帮助，完成后才能排单，每次只能一个在线
                //查询提供帮助状态，有一条没完成，不能排单
                //session('tohelp')=0 可以排单 session('tohelp')=1 不能排单

                $tohelp=M('tohelp')->field('count(1) as number')->where('status<4 and status>=0 and userid='.$userid)->select();

                if(empty($tohelp[0]['number']))
                {
                   session('tohelp',0);
                }
                else
                {
                    session('tohelp',1);
                }

                $today=date('Y-m-d',time());
                $today=strtotime($today);
                $gethelp=M('gethelp')->field('count(1) as number')->where('userid='.$userid.' and createtime>'.$today.' and createtime<'.($today+24*3600))->select();

       
                if(empty($gethelp[0]['number']))
                {
                    session('gethelp',0);
                }
                else
                {
                    session('gethelp',1);
                }

                // redirect('/index/');

                $arr=array(
                    'errnum'=>0,
                    // 'retMsg'=>'登录成功！',
                    'retMsg'=>'success',
                    'retData'=>$_SESSION,
                );

                $this->ajaxReturn($arr);die;

            }
            else
            {
                $arr=array(
                    'errnum'=>0,
                    // 'retMsg'=>'账户或密码错误！',
                    'retMsg'=>'账户错误！',
                    // 'retMsg'=>'error',
                );

                $this->ajaxReturn($arr);die;
            }
            exit;
        }else{
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请填写用户名！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }

    }
    // public function verify(){
    //     ob_clean();
    //     $Verify = new \Think\Verify();
    //     $Verify->fontSize = 16;
    //     $Verify->length   = 4;
    //     $Verify->useNoise = false;
    //     $Verify->codeSet = '0123456789';
    //     $Verify->imageW = 120;
    //     $Verify->imageH = 40;
    //     //$Verify->expire = 600;
    //     $Verify->entry();

    // }
    // //检测验证码
    // public function check_verify($code, $id = ''){
    //     $verify = new Verify();
    //     return $verify->check($code, $id);
    // }

    //计算当前的级别
    private function GetGotegary($userid,$username)
    {
        session('username',$username);
        session('userid',$userid);

        $D=M('user');
        $data=$D->field('count(1) as number')->where('tusername="'.session('username').'" and status=1 and (select count(1) from tm_tohelp where userid=tm_user.id and status=3)>0')->select();

// echo session('username');die;
        $number=$data[0]['number'];

        $idata=$D->field('limit')->where('id='.session('userid'))->find();

        switch($number)
        {
            case 0:
                $arr=array(
                    'gotegary'=>0,
                    'limit'=>5000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
            case 1:
                $arr=array(
                    'gotegary'=>0,
                    'limit'=>10000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
            case 2:
                $arr=array(
                    'gotegary'=>0,
                    'limit'=>15000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
            case 3:
                $arr=array(
                    'gotegary'=>1,
                    'limit'=>20000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
            case 4:
                $arr=array(
                    'gotegary'=>1,
                    'limit'=>25000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
            case 5:
                $arr=array(
                    'gotegary'=>2,
                    'limit'=>30000,
                );
                $D->where('id='.session('userid'))->save($arr);
                session('limit',$arr['limit']);
                break;
        }



        if($number>=6)
        {
            $arr=array('gotegary'=>2,
                'limit'=>30000,
               );
            $D->where('id='.session('userid'))->save($arr);

        }
        //金卡人数
        $tdata=$D->field('count(1) as number')->where('tusername="'.session('username').'" and status=1 and gotegary>=2')->select();
        //团队人数
        $tdata1=$D->field('count(1) as number')->where('locate(",'.session('userid').',",path)>0')->select();



        if($tdata[0]['number']>=5&&$tdata1[0]['number']>=100)
        {
            $arr=array('gotegary'=>3);
            $D->where('id='.session('userid'))->save($arr);
        }

    }

    //超过12小时不打款冻结帐户
    private function offuser()
    {
        $TG=M('toget');
        $T=M('tohelp');
        $U=M('user');
        $I=M('integral');
        // $tdata=$T->where('status=1')->select();
        $tdata=$TG->where('status=1')->select();
        $time=time();
        $hour12=3600*12;//12小时
        foreach($tdata as $vo)
        {
            // $lasttime=$time-$vo['begintime'];
            $lasttime=$time-$vo['createtime'];

            if($lasttime>$hour12)
            {
                //冻结帐户
                $arr=array('status'=>3);

                $tgbzuserid=$T->where('id='.$vo['toid'])->find();
                $U->where('id='.$tgbzuserid['userid'])->save($arr);
                //扣除推荐人300
                $udata=$U->field('tusername')->where('id='.$tgbzuserid['userid'])->find();
                if($udata['tusername']<>''){
                    $udata1=$U->field('id')->where('username="'.$udata['tusername'].'"')->find();
                    $I->where('userid='.$udata1['id'])->setdec('integral2',300);
                }

                // $U->where('id='.$vo['userid'])->save($arr);

                //扣除推荐人300
                // $udata=$U->field('tusername')->where('id='.$vo['userid'])->find();
                // $udata1=$U->field('id')->where('username="'.$udata['tusername'].'"')->find();
                // $I->where('userid='.$udata1['id'])->setdec('integral2',300);


            }
        }

    }

    //解冻奖金
    public function dofreeze($userid)
    {
        // $userid=session('userid');
        $D=M('freeze');
        $begintime=time();
        $endtime=15*24*3600;
        $data=$D->where('('.$begintime.'-begintime)>='.$endtime.' and userid='.$userid.' and status=0')->select();
        if(!empty($data))
        {
            foreach($data as $vo)
            {
                //解冻奖金，奖金到帐
               $this->freezein($vo);
                //已经解冻,改变奖态
                $D->where('id='.$vo['id'])->setinc('status',1);
            }
        }


    }

    //写入收入记录
    public function freezein($data)
    {

        $I=M('integral');


        //写入收支记录
        //支付记录
        //$userid 会员ID
        //$title 内容
        //$createtime 创建时间
        //$price 消费价格
        //$status 消费状态 1 收入，0 支出
        //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户
        $begintime=time();
        $status=1;
        $U=M('user');
        $user=$U->field('username,mobile')->where('id='.$data['userid'])->find();
        //静态解冻
        if($data['gotegary']==1)
        {
            $gotegary=1;
            $I->where('userid='.$data['userid'])->setinc('integral1',$data['integral1']);
            $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);

            $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
            $gotegary=5;
            $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);

            //解冻短信
            $this->qrsms($user['mobile'],$user['username'],3);
            return;
        }
        //动态解冻
        // $arr=$this->currorder($data['userid']);

        // if($arr[0]==0)
        // {
        //     return;
        // }

        $gotegary=2;
        $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
        $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);

        $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
        $gotegary=5;
        $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);

        // $this->qrsms($user['mobile'],$user['username'],3);



        // //烧伤
        // $total=$this->dofire($arr[1]);

        // if($total>=$arr[2])//奖金多过投资金额
        // {
        //     return;
        // }
        // else//奖金少过投资金额
        // {
        //     if(($total+$data['integral1'])<=$arr[2])
        //     {
        //         //写入收支记录
        //         //支付记录
        //         //$userid 会员ID
        //         //$title 内容
        //         //$createtime 创建时间
        //         //$price 消费价格
        //         //$status 消费状态 1 收入，0 支出
        //         //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户


        //             $gotegary=2;
        //             $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
        //             $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);

        //             $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
        //             $gotegary=5;
        //             $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);
        //     }
        //     else
        //     {
        //             $data['integral1']= $arr[2]-$total;
        //             $gotegary=2;
        //             $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
        //             $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);

        //             $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
        //             $gotegary=5;
        //             $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);
        //     }

        // }
        
    }

    //计算烧伤奖
    //判断动态奖金有多少
    public function dofire($begintime)
    {
        //当前订单

        $F=M('freeze');
        $fdata=$F->field('sum(integral1) as total')->where('createtime+'.(15*3600).'>'.$begintime.' and status=1 and gotegary=2')->select();
       if(empty($fdata[0]['total']))
       {
           return 0;
       }else
       {
            return $fdata[0]['total'];
        }
    }



    //取得倒计时时间
    public function gettime($begintime)
    {
        $time=time();
        $s=12*3600-($time-$begintime);
        if($s<0)
        {
            $s=0;
        }
        return $s;


    }

    //烧伤奖,判断是否烧伤
    public function fire()
    {
        //1第一次不烧伤
        //第二次开始烧伤
        //动态奖金排单状态下产生，超过本钱后，烧伤（动态奖金不再收入）
        $userid=session('userid');
        $U=M('user');
        $D=M('tohelp');
        $data=$D->field('count(1) as total')->where('userid='.$userid)->select();
        {
            if($data[0]['total']>1)
            {
                session('fire',1);//是否烧伤
                $U->where('id='.$userid)->setinc('fire',1);
            }
        }


    }

    //判断是否有正在进行的订单
    public function currorder($userid)
    {
        // $userid=session('userid');
        $T=M('tohelp');
        $tdata=$T->where('status>0 and status<3 and userid='.$userid)->find();
        $arr=array();
        if(empty($tdata))
        {
            $arr[0]=0;//没有订单正在进行，不产生奖金
            $arr[1]=0;//没有订单正在进行，不产生奖金
            $arr[2]=0;
        }
        else
        {
            $arr[0]=1;//有订单正在进行，不产生奖金
            $arr[1]=$tdata['createtime'];//有订单正在进行，不产生奖金
            $arr[2]=$tdata['price'];//有订单正在进行，不产生奖金
        }
    }



    //修改登录密码
    public function changepassword(){

        // if (empty($_SESSION['username'])){
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'没有SESSION！',
        //             // 'retMsg'=>'error',
        //     );

        //     $this->ajaxReturn($arr);die;
        // }
        
        $D=M('user');

        $userid=I('post.userid');//会员id
       
        $password=I('post.password');//原登录密码
        
        $newpassword=I('post.newpassword');//新密码
        
        $qrnewpassword=I('post.qrnewpassword');//确认新密码
        
        if($newpassword!=$qrnewpassword)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'新密码和确认新密码不一致！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }
        // $userid=1;
        // $password='123';
        // $newpassword='123456';
        
        $password=md5($password);
        $data=$D->field('password')->where('id='.$userid)->find();
        if($data<>''){
            
            // if($password!=$data['password'])
            // {
            //     $arr=array(
            //         'errnum'=>0,
            //         'retMsg'=>'原密码输入错误！',
            //         // 'retMsg'=>'error',
            //     );

            //     $this->ajaxReturn($arr);die;
            // }
            if(!$this->_checkPwd($password,$data['password']))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'原密码输入错误！',
                    // 'retMsg'=>'error',
                );

                $this->ajaxReturn($arr);die;
            }

            $newpassword=md5(I('post.newpassword'));
            $newpassword=$this->md5pw($newpassword);
            $map=array(
                'password'=>$newpassword,

            );

            // if($password==$data['password'])
            // {
            //     $map=array(
            //         'password'=>md5($newpassword)
            //     );
            // }
            

            $D->where('id='.$userid)->save($map);
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );
            $this->ajaxReturn($arr);die;

        }else{

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        
        
    }

    //修改安全密码
    public function changepaypassword(){

        $D=M('user');

        $userid=I('post.userid');//会员id
       
        $paypassword=I('post.paypassword');//原支付密码
        
        $newpassword=I('post.newpassword');//新密码
        
        $qrnewpassword=I('post.qrnewpassword');//确认新密码

        if($newpassword!=$qrnewpassword)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'新密码和确认新密码不一致！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }
        // $userid=1;
        // $password='123';
        // $newpassword='123456';
        
        $password=md5($paypassword);
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if($data<>''){
            
            // if($password!=$data['paypassword'])
            // {
            //     $arr=array(
            //         'errnum'=>0,
            //         'retMsg'=>'原密码输入错误！',
            //         // 'retMsg'=>'error',
            //     );

            //     $this->ajaxReturn($arr);die;
            // }
            if(!$this->_checkPwd($password,$data['paypassword']))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'原密码输入错误！',
                    // 'retMsg'=>'error',
                );

                $this->ajaxReturn($arr);die;
            }

            $newpassword=md5(I('post.newpassword'));
            $newpassword=$this->md5pw($newpassword);
            $map=array(
                'paypassword'=>$newpassword,

            );

            // if($password==$data['paypassword'])
            // {
            //     $map=array(
            //         'paypassword'=>md5($newpassword),
            //     );
            // }
            
            $D->where('id='.$userid)->save($map);
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );
            $this->ajaxReturn($arr);die;

        }else{

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

    }

    //获取密码
    public function getpassword(){
        $D=M('user');

        if(I('get.userid')){
            $userid=I('get.userid');
            $data=$D->field('password,paypassword')->where('id='.$userid)->find();
            if($data<>''){
        
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'success',
                    'retData'=>$data,
                );

            }else{

                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'用户不存在！',
                    // 'retMsg'=>'error',
                );
            }
            $this->ajaxReturn($arr);
        }
    }

    //会员详情
    public function index(){

        $userid=I('get.userid');
        $D=M('user');
        $data=$D->where('id='.$userid)->find();

        //统计动静态帐户
        $F=M('freeze');
        //静态冻结
        $fdata=$F->field('sum(integral1) as integral1')->where('status=0 and gotegary=1 and userid='.$userid)->select();
        //动态冻结
        $fdata1=$F->field('sum(integral1) as integral1')->where('status=0 and gotegary=2 and userid='.$userid)->select();
        // $this->assign('f1',$fdata[0]['integral1']);
        // $this->assign('f2',$fdata1[0]['integral1']);

        if($data<>''){
            $username=$data['username'];

            //直推数量
            $sql = "select count(1) as total from tm_user where tusername='".$username."'";
            $query =$D->query($sql);
            $anum=$query[0]['total'];
            //团队数量
            $sql = "select count(1) as total from tm_user where locate(',".$userid.",',path)>0 and id!=".$userid;
            $query =$D->query($sql);
            $bnum=$query[0]['total'];
            //钱包
            $integral=M('integral')->where('userid='.$userid)->find();

            $data['zhituinum']=$anum;//直推数量
            $data['tuanduinum']=$bnum;//团队数量
            $data['integral4']=$integral['integral4'];//激活码
            $data['integral3']=$integral['integral3'];//排单币
            $data['integral1']=$integral['integral1'];//静态账户
            $data['integral2']=$integral['integral2'];//动态账户
            $data['integral5']=$integral['integral5'];//消费账户
            $data['jingtaidj']=$fdata[0]['integral1'];//静态冻结
            $data['dongtaidj']=$fdata1[0]['integral1'];//动态冻结
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
                'retData'=>$data,
            );

        }else{
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
        }
        

        $this->ajaxReturn($arr);
        
    }

    //修改资料
    public function myinfo(){
        
        $userid=I('post.userid');//会员id
        
        if(I('post.username')){
            $username=I('post.username');
        }
        if(I('post.mobile')){
            $map['mobile']=I('post.mobile');
            $mobile=I('post.mobile');
        }
        if(I('post.weixin')){
            $map['weixin']=I('post.weixin');
        }
        if(I('post.alipay')){
            $map['alipay']=I('post.alipay');
        }
        if(I('post.address')){
            $map['address']=I('post.address');
        }
        if(I('post.realname')){
            $map['realname']=I('post.realname');
        }
        if(I('post.paper')){
            $map['paper']=I('post.paper');
        }
        if(I('post.bankname')){
            $map['bankname']=I('post.bankname');
        }
        if(I('post.bank')){
            $map['bank']=I('post.bank');
        }
        if(I('post.bankcode')){
            $map['bankcode']=I('post.bankcode');
        }
        
        $D=M('user');
        $data=$D->field('mobile')->where('mobile='.$mobile.' and id!='.$userid)->find();
        if(!empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'此手机号已注册，请换一个！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        if($D->where('id='.$userid)->save($map)){
            $arr=array(
                'errnum'=>0,
                // 'retMsg'=>'修改成功',
                'retMsg'=>'success',
            );

        }else{
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'未修改资料，无须保存！',
                // 'retMsg'=>'error',
            );
        }

        $this->ajaxReturn($arr);

    }

    //注册新会员
    public function useradd(){
        
        $username = I('post.username');
        $password = I('post.password');
        $qrpassword=I('post.qrpassword');
        if($password != $qrpassword){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'确认登录密码不一致',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $paypassword = I('post.paypassword');
        $qrpaypassword=I('post.qrpaypassword');
        if($paypassword != $qrpaypassword){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'确认安全密码不一致',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $password = md5($password);
        $password=$this->md5pw($password);
        $paypassword = md5($paypassword);
        $paypassword=$this->md5pw($paypassword);

        $tusername = I('post.tusername');
        $realname = I('post.realname');
        $mobile = I('post.mobile');
        $weixin = I('post.weixin');
        $alipay = I('post.alipay');
        $address = I('post.address');
        $paper=I('post.paper');
        $bankname = I('post.bankname');
        $bank = I('post.bank');
        $bankcode = I('post.bankcode');

        // $userid = session('userid');
        $createtime = time();

        //判断支付密码是否正确
        $D = M('user');
        // $data = $D->where('id=' . $userid)->find();
        //取得推荐人ＩＤ
        $data1 = $D->where('username="' . $username . '"')->find();
        if (!empty($data1)) {  //用户名已经存在，
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户名已经存在',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        // $zshxm = $D->where('realname="' . $realname . '"')->find(); 
        // if (!empty($zshxm)) {  //用户名已经存在，
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'此真实姓名已注册！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;
        // }

        $pdata = $D->where('username="' . $tusername . '"')->find();
        if (empty($pdata)) {  //推荐人不存在，
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'推荐人不存在',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        //判断手机号码
        $data2 = $D->where('mobile="' . $mobile . '"')->find();
        if (!empty($data2)) {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'手机号码已经存在',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die; //手机号码已经存在，
            
        }
        $data3 = $D->where('paper="' . $paper . '"')->find();
        if (!empty($data3)) {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'该身份证号已被注册！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die; //手机号码已经存在，
            
        }

        $map=array(
            'username'=>$username,
            // 'password'=>md5($password),
            // 'paypassword'=>md5($paypassword),
            'password'=>$password,
            'paypassword'=>$paypassword,
            'tusername'=>$tusername,
            'realname'=>$realname,
            'paper'=>$paper,
            'mobile'=>$mobile,
            'weixin'=>$weixin,
            'alipay'=>$alipay,
            'address'=>$address,
            'createtime'=>$createtime,
            'gotegary'=>0,
            'status'=>0,
            'bankname'=>$bankname,
            'bank'=>$bank,
            'bankcode'=>$bankcode,

        );

        $id=$D->add($map);//添加会员
        $path=$pdata['path'].$id.',';
        $number=$this->donumber($id);//生成会员编号
        $map2=array(
            'path'=>$path,
            'number'=>$number,
        );
        $D->where('id='.$id)->save($map2);

        $I=M('integral');
        $arri=array(
            'userid'=>$id,
            'integral1'=>0,
            'integral2'=>0,
            'integral3'=>0,
            'integral4'=>0,
            'integral5'=>0,
        );
        $I->add($arri);//加入积分表
        $arr=array(
                'errnum'=>0,
                // 'retMsg'=>'注册成功',
                'retMsg'=>'success',
            );

        $this->ajaxReturn($arr);

    }

    //生成会员编号
    private function donumber($id)
    {

        $number='A'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }

    //我的团队
    public function member()
    {
        $keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($keyword))
        {
            $search='&k='.$keyword;
        }

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        
        $username=$data['username'];
        // $username=I('get.username');

        $sqlw.=' and (number like "%'.$keyword.'%" or username like "%'.$keyword.'%" or mobile like "%'.$keyword.'%" or realname like "%'.$keyword.'%" ) and tusername="'.$username.'"';

        $room = M('user');

        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_user where ".$sqlw;

        // $query = $room->query($sql);

        // $pageurl="/api/index/member?";

        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }
            
        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //开通账户
    public function jihuo()
    {
        $userid=I('get.userid');
        $tusername=I('get.tusername');
        $D=M('user');
        $tid=$D->where('username="'.$tusername.'"')->find();
        $wjh=$D->where('id='.$userid)->find();

        $I=M('integral');

        $idata=$I->where('userid='.$tid['id'])->find();
        if($idata['integral4']<1)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'激活币余额不足，请充值！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }
        

        $map['status']=1;

        if($D->where('id='.$userid)->save($map)){
            $map3=array(
                'integral4'=>$idata['integral4']-1,
            );
            $I->where('userid='.$tid['id'])->save($map3);

            $B=M('buy_history');
            $title='激活'.$wjh['username'].'消耗激活币';
            $arr2=array(
                'userid'=>$tid['id'],
                'price'=>1,
                'status'=>3,
                'gotegary'=>2,
                'createtime'=>time(),
                'title'=>$title
                );
            $B->add($arr2);

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );
        }else{
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'已开通！',
                // 'retMsg'=>'error',
            );
        }

        $this->ajaxReturn($arr);

    }

    //推荐图谱
    // public function tree()
    // {
    //     $str='';
        // $userid=I('get.userid');
        // $data=M('user')->where('id='.$userid)->find();
        // if(!$data){
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;
        // }
        
        // $username=$data['username'];
    //     $str.=$this->getsubuser($username);
    //     $arr=array(
    //         'errnum'=>0,
    //         'retMsg'=>'success',
    //         'retData'=>$str,
    //     );

    //     $this->ajaxReturn($arr);

    // }
    public function tree()
    {


        $str='';
        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        $username=$data['username'];
        
        // $username=session('username');
        $str.=$this->getsubuser($username);

        $this->assign('username',$username);
        $this->assign('str',$str);
        $this->display();


    }
    //得到子项
    public function getsubuser($username)
    {
        $D=M('user');

        $room=$D->field('username,tusername')->where('tusername="'.$username.'"')->select();

        $str='';
        if(!empty($room))
        {
            $str.='<ul>';
            foreach($room as $vo)
            {
                $str.='<li><span>'.$vo['username'].'</span>';
                $str.=$this->getsubuser($vo['username']);
                $str.='</li>';

            }
            $str.='</ul>';
        }

        return $str;
    }

    //提供帮助
    public function tgbz()
    {
        //设置开关
        $S=M('setting');
        $sdata=$S->field('tohelp')->where('id=1')->find();
        if($sdata['tohelp']==1){

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'提供帮助排单已经暂时关闭，请稍候再来！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对

        }

        $userid=I('post.userid');
        $price=I('post.price');
        $total=I('post.total');
        // $userid=1;
        // $price=20000;
        // $total=200;
        // $paypassword=md5('123456');

        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword,limit,tohelp')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'支付密码不正确，请重新输入！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
        // if($paypassword!=$data['paypassword'])
        // {
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'支付密码不正确，请重新输入！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;//安全密码不对
        // }
        if($data['tohelp']==0)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'该账户还不允许排单，请联系客服！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
        if($idata['integral3']<$total)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'排单币余额不足！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }
        if($price>$data['limit'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'提供帮助金额不能大于会员额度！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }

        if($price<1000)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'提供帮助金额不能低于1000！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }

        if($price>30000)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'提供帮助金额不能大于30000！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }

        if($price%100>0)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'提供帮助金额只能是百的倍数！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//余额不足
        }



        $T=M('tohelp');

        $tda=$T->field('count(1) as total')->where('status<3 and userid='.$userid)->select();
        if($tda[0]['total']>0)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'您还有单没完成，完成订单后再来排单吧！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        // $date= date('Y-m-d',time());
        // $datebegin=strtotime($date);
        // $dateend=$datebegin+24*3600;
        // // $data=$T->field('sum(price) as total')->where('createtime>='.$datebegin.' and createtime<'.$dateend)->select();
        // $tdata=$T->field('sum(price) as total')->where('createtime>='.$datebegin.' and createtime<'.$dateend)->select();

        $P=M('parameter');
        $arr=$P->where('id=3')->find();

        // $todaytohelpprice=$arr['number']-$tdata[0]['total'];
        $todaytohelpprice=$arr['number'];

        if($todaytohelpprice<1000){

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'今天的开放金额已不足1000，无法排单，请明天再来吧！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        // $arr=F('para');

        // $todaytohelpprice=$arr[2]['number']-$data[0]['total'];
        if($todaytohelpprice<$price)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'今天的开放金额剩余'.$todaytohelpprice.',提供帮助金额请勿大于剩余金额！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $createtime=time();

        $interest=$price*0.2;
        $replay=$interest*0.1;
        $interest=$interest-$replay;
        //写入排单
        $map=array(
            'userid'=>$userid,
            'price'=>$price,
            'status'=>0,
            'begintime'=>$createtime,
            'createtime'=>$createtime,
            'interest'=> $interest,
            'replay'=> $replay,
        );

        $id=$T->add($map);
        $orderid=$this->dodohelpnumber($id);//生成会员编号
        $map2=array(
            'orderid'=>$orderid,
        );
        $T->where('id='.$id)->save($map2);


        $map3=array(
            'integral3'=>$idata['integral3']-$total,

        );
        $I->where('userid='.$userid)->save($map3);

        $P->where('id=3')->setdec('number',$price);

        //支入记录
        $title='排单使用排单币'.$total;

        $this->Pay_history($userid,$title,$total,$createtime,0,3);
        //激活码记录
        $this->case_history($userid,$total,3,1,$createtime,$title);
        session('tohelp',1);

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);

    }
    //生成会员编号
    private function dodohelpnumber($id)
    {

        $number='B'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }
    //支付记录
    //$userid 会员ID
    //$title 内容
    //$createtime 创建时间
    //$price 消费价格
    //$status 消费状态 1 收入，0 支出
    //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户

    private function Pay_history($userid,$title,$price,$createtime,$status,$gotegary)
    {
        $D=M('income');
        $arr=array(
            'userid'=>$userid,
            'title'=>$title,
            'price'=>$price,
            'createtime'=>$createtime,
            'status'=>$status,
            'gotegary'=>$gotegary,

        );
        $D->add($arr);
    }
    //购买记录
    //userid 用户名
    //price 花费价格
    //title 内容
    //status １购买　２转帐　３使用
    //gotegary 1  排单币 2 激活码


    public function case_history($userid,$price,$status,$gotegary,$createtime,$title)
    {
       $D=M('buy_history');
        $arr=array('userid'=>$userid,
            'price'=>$price,
            'status'=>$status,
            'gotegary'=>$gotegary,
            'createtime'=>$createtime,
            'title'=>$title
            );
        $D->add($arr);

    }

    //提供帮助列表
    public function tgbzlb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $sqlw.=' and userid='.$userid;

        $room = M('tohelp');

        // $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_tohelp where ".$sqlw;


        // $query = $room->query($sql);

        $T=M('toget');
        $U=M('user');
        for($i=0;$i<count($info);$i++)
        {
            $tdata=$T->field('tm_gethelp.id,tm_gethelp.orderid,tm_gethelp.userid,tm_gethelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_gethelp.begintime,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select weixin from tm_user where id=tm_gethelp.userid) as weixin,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select tusername from tm_user where id=tm_gethelp.userid) as tusername')->join('tm_gethelp on tm_gethelp.id=tm_toget.getid')->where('tm_toget.toid='.$info[$i]['id'])->select();
            for($j=0;$j<count($tdata);$j++)
            {
                $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
                $tdata[$j]['trealname']=$tdata1['realname'];
                $tdata[$j]['tmobile']=$tdata1['mobile'];

                if($info[$i]['price']>$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$tdata[$j]['price'];

                }
                if($info[$i]['price']==$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$tdata[$j]['price'];

                }
                if($info[$i]['price']<$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$info[$i]['price'];

                }
            }

            $info[$i]['sub']=$tdata;
            $z_price +=$info[$i]['price'];
        }

        // $pageurl="/api/index/tgbzlb?";

        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'num'=>count($info),
            'z_price'=>$z_price,
            'endtime'=>12,
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //确认打款
    public function qrdakuan(){

        $id1=I('post.id1');//toid
        $id2=I('post.id2');//getid

        $pas=I('post.paypassword');
        if(empty($pas))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请输入安全密码！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        // $id1=8;//toid
        // $id2=7;//getid
        // $pic=I('post.pic');

    $picname = $_FILES['pic']['name'];
    $picsize = $_FILES['pic']['size'];

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
        $pics = date("YmdHis") . $rand . $type;
        //上传路径
        $pic_path = "./Public/upload/files/". $pics;
        move_uploaded_file($_FILES['pic']['tmp_name'], $pic_path);
    }
    $size = round($picsize/1024,2);

    // $arr = array(
    //     'name'=>$picname,
    //     'pic'=>$pics,
    //     'size'=>$size
    // );
    // echo json_encode($arr);

        $pic=$pic_path;

        $T=M('toget');
        $D=M('tohelp');
        $G=M('gethelp');

        $tgbz=$D->where('id='.$id1)->find();

        $paypassword=md5(I('post.paypassword'));
        
        $mima=M('user')->field('paypassword')->where('id='.$tgbz['userid'])->find();
        if(!$this->_checkPwd($paypassword,$mima['paypassword']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'安全密码不正确，请重新输入！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
        // if($paypassword!=$mima['paypassword'])
        // {
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'安全密码不正确，请重新输入！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;//安全密码不对
        // }

        // $ddzt=1;
        // $ppdd=$T->where('getid='.$id2.' and toid='.$id1.' and status='.$ddzt)->find();
        // $tgbz=$D->where('id='.$id1.' and status='.$ddzt)->find();
        // $jsbz=$G->where('id='.$id2.' and status='.$ddzt)->find();
        // dump($jsbz);die;
        // if(!$ppdd){
        //     $map=array(
        //         'errnum'=>0,
        //         'retMsg'=>'error',
        //     );

        //     $this->ajaxReturn($map);die;
        // }


        $tcount=$T->field('count(1) as number')->where('toid='.$id1)->select();
        $number=$tcount[0]['number']; //计算多少个配置
        if($number==1)//只有一个匹配的
        {
            //得到帮助有多少个
            $gcount=$T->field('count(1) as number')->where('getid='.$id2)->select();
            $gnumber=$gcount[0]['number']; //计算多少个配置
            if($gnumber==1)
            {
                $arr=array(
                    'status'=>2,
                    'pic'=>$pic,
                    );
                $T->where('getid='.$id2.' and toid='.$id1)->save($arr);
                $D->where('id='.$id1)->save($arr);
                $arrg=array(
                    'status'=>2,
                    'pic'=>$pic);
                $G->where('id='.$id2)->save($arrg);
            }
            else
            {
                $tc=$T->field('count(1) as number')->where('getid='.$id2.' and status=1')->select();

                $num=$tc[0]['number'];



                if(empty($num)||$num==1)//还剩下多少个没打款,没有就改状态
                {
                    $arr=array(
                        'status'=>2,
                        'pic'=>$pic);
                    $G->where('id='.$id2)->save($arr);
                }
                else
                {
                    $arr=array(
                        'status'=>1,
                        'pic'=>$pic);
                    $G->where('id='.$id2)->save($arr);
                }

                $arrg=array(
                    'status'=>2,
                    'pic'=>$pic
                );

                $T->where('getid='.$id2.' and toid='.$id1)->save($arrg);
                $D->where('id='.$id1)->save($arrg);
            }
        }
        else//多个配置的
        {
            $tc=$T->field('count(1) as number')->where('toid='.$id1.' and status=1')->select();

            $num=$tc[0]['number'];



            if(empty($num)||$num==1)//还剩下多少个没打款,没有就改状态
            {
                $arr=array('status'=>2);
                $D->where('id='.$id1)->save($arr);
            }
            else
            {
                $arr=array('status'=>1);
                $D->where('id='.$id1)->save($arr);
            }

            $arr=array(
                'status'=>2,
                'pic'=>$pic
                );

            $T->where('getid='.$id2.' and toid='.$id1)->save($arr);
            $arrg=array(
                'status'=>2,
                'pic'=>$pic);
            $G->where('id='.$id2)->save($arrg);
        }

        $this->lishi($id1,$id2); //利息

        //确认已打款
        $udata=$G->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$id2)->find();
        $this->qrsms($udata['mobile'],$udata['username'],1);
        //发送提醒短信

        $map=array(
            'errnum'=>0,
            // 'retMsg'=>'确认成功！',
            'retMsg'=>'success',
        );

        $this->ajaxReturn($map);
    }


//利息

//$id1 toid
//$id2 getid
    public function lishi($id1,$id2)
    {
        $D=M('tohelp');
        $T=M('toget');
        $G=M('gethelp');
        $data=$D->where('id='.$id1)->find();
        $gdata=$G->where('id='.$id2)->find();

        if($data['price']>$gdata['price'])
        {
            $total=$G->field('sum(price) as total')->where('id in(select getid from tm_toget where toid='.$id1.')')->select();
            $surplus=$total[0]['total'];
        }
        else
        {
            $surplus=$data['price'];
        }

        //超出时间：
        //$i=floor((time()-$gdata['createtime'])/3600);
        //if($i<5)
           // $interest=$data['price']*0.2;//利息
        //if($i>5&&$i<12)
            //$interest=$data['price']*0.15;//利息

        $interest=$data['price']*0.2;//利息
        $replay=$interest*0.1;//重复消费


        $arr=array(
            'surplus'=>$data['price']-$surplus,
            'interest'=>$interest,
            'replay'=>$replay,
        );
        $D->where('id='.$id1)->save($arr);


    }


    //得到帮助 默认静态帐户提现
    public function jsbz()
    {
        $S=M('setting');
        $sdata=$S->field('gethelp')->where('id=1')->find();
        if($sdata['gethelp']==1){

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'得到帮助排单已经暂时关闭，请稍候再来！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对

        }


        $userid=I('post.userid');
        $price=I('post.price');
        //$total=I('post.total');

        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword,tohelp')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'支付密码不正确，请重新输入！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
        // if($paypassword!=$data['paypassword'])
        // {
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'支付密码不正确，请重新输入！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;//安全密码不对
        // }
        if($data['tohelp']==0)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'暂时不允许排单，系统审核中，请耐心等待！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
//        if($idata['integral3']<$total)
//        {
//            echo 3;//余额不足
//            die();
//        }
        if($price<500)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'援助金额不能低于500!',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//得到帮助不能超过30000
        }
        if($price%100>0)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'援助金额只能填写100的整数倍！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//得到帮助不能超过30000
        }
        if($price>36000)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'得到帮助金额不能超过36000',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//得到帮助不能超过30000
        }

        if($price>$idata['integral1'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'静态帐户余额不足',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//静态帐户余额不足
        }
        
        $T=M('gethelp');

        $todayStart= date('Y-m-d 00:00:00', time()); //2016-12-10 00:00:00 当天开始时间
        $day1=strtotime($todayStart);
        $todayEnd= date('Y-m-d 23:59:59', time()); //2016-12-10 23:59:59 当天结束时间
        $day2=strtotime($todayEnd);
        $tda1=$T->where('userid='.$userid.' and createtime>='.$day1.' and createtime<'.$day2)->select();
        $tda2=$T->where('userid='.$userid.' and createtime>'.$day1.' and createtime<='.$day2)->select();
        $dedao1=count($tda1);
        $dedao2=count($tda2);
        if($dedao1>=1)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'您今天已得到帮助过一次，请明天再来！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        if($dedao2>=1)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'您今天已得到帮助过一次，请明天再来！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $createtime=time();
        //写入排单
        $map=array(
            'userid'=>$userid,
            'price'=>$price,
            'status'=>0,
            'begintime'=>$createtime,
            'createtime'=>$createtime,
        );
        
        $id=$T->add($map);
        $orderid=$this->dogethelpnumber($id);
        $arr2=array(
            'orderid'=>$orderid,
        );
        $T->where('id='.$id)->save($arr2);


        $arr3=array(
            'integral1'=>$idata['integral1']-$price,

        );
        $I->where('userid='.$userid)->save($arr3);
        //支入记录
        $title='得到帮助提现'.$price;

        $this->Pay_history($userid,$title,$price,$createtime,0,1);
        //激活码记录
        //$this->case_history($userid,$total,3,1,$createtime,$title);
        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);
    }

    //生成会员编号
    private function dogethelpnumber($id)
    {

        $number='C'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }

    //得到帮助列表
    public function jsbzlb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $sqlw.=' and userid='.$userid;

        $room = M('gethelp');

        // $info=$room->field('id,orderid,userid,price,createtime,status,begintime,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,pic,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname')->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_gethelp where ".$sqlw;

        // $query = $room->query($sql);

        $U=M('user');
        $T=M('toget');
        for($i=0;$i<count($info);$i++)
        {
            $tdata=$T->field('tm_tohelp.id,tm_tohelp.orderid,tm_tohelp.userid,tm_tohelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_tohelp.begintime,(select username from tm_user where id=tm_tohelp.userid) as username,(select alipay from tm_user where id=tm_tohelp.userid) as alipay,(select weixin from tm_user where id=tm_tohelp.userid) as weixin,(select mobile from tm_user where id=tm_tohelp.userid) as mobile,(select bank from tm_user where id=tm_tohelp.userid) as bank,(select bankname from tm_user where id=tm_tohelp.userid) as bankname,(select bankcode from tm_user where id=tm_tohelp.userid) as bankcode,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select tusername from tm_user where id=tm_tohelp.userid) as tusername')->join('tm_tohelp on tm_tohelp.id=tm_toget.toid')->where('tm_toget.getid='.$info[$i]['id'])->select();

            for($j=0;$j<count($tdata);$j++)
            {
                $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
                $tdata[$j]['trealname']=$tdata1['realname'];
                $tdata[$j]['tmobile']=$tdata1['mobile'];

                if($info[$i]['price']>$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$tdata[$j]['price'];

                }
                if($info[$i]['price']==$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$tdata[$j]['price'];

                }
                if($info[$i]['price']<$tdata[$j]['price']){

                    $tdata[$j]['pipeije']=$info[$i]['price'];

                }

            }

            $info[$i]['sub']=$tdata;
            $z_price +=$info[$i]['price'];
        }

        // $pageurl="/api/index/jsbzlb?";

        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'num'=>count($info),
            'z_price'=>$z_price,
            'endtime'=>12,
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //确认收款
    public function qrshokuan()
    {
        $id=I('post.id1');//getid
        $id2=I('post.id2');//toid

        $pas=I('post.paypassword');
        if(empty($pas))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请输入安全密码！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        // $id=4;//getid
        // $id2=5;//toid
        $T=M('tohelp');
        $G=M('gethelp');
        $D=M('toget');

        // $ddzt=2;
        // $ppdd=$D->where('getid='.$id.' and toid='.$id2.' and status='.$ddzt)->find();
        // $tgbz=$T->where('id='.$id2.' and status='.$ddzt)->find();
        // $jsbz=$G->where('id='.$id.' and status='.$ddzt)->find();
        // // dump($jsbz);die;
        // if(!$ppdd || !$tgbz || !$jsbz){
        //     $map=array(
        //         'errnum'=>0,
        //         'retMsg'=>'提供帮助会员还未打款！',
        //         // 'retMsg'=>'error',
        //     );

        //     $this->ajaxReturn($map);die;
        // }

        $data=$D->where('getid='.$id.' and toid='.$id2.' and status!=3')->find();
        $tdata=$T->where('id='.$data['toid'].' and status!=3')->find();
        $gdata=$G->where('id='.$id.'  and status!=3')->find();

        $paypassword=md5(I('post.paypassword'));
        
        $mima=M('user')->field('paypassword')->where('id='.$gdata['userid'])->find();
        if(!$this->_checkPwd($paypassword,$mima['paypassword']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'安全密码不正确，请重新输入！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }
        // if($paypassword!=$mima['paypassword'])
        // {
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'安全密码不正确，请重新输入！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($arr);die;//安全密码不对
        // }
        if(empty($data)||empty($tdata)||empty($gdata))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'您已经提交过了，不能重复提交，请耐心等候处理!',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;//安全密码不对
        }


        $arr=array('status'=>3);

        // if($gdata['price']>=$tdata['price'])
        //提供帮助与得到帮助相等的情况
        if($gdata['price']==$tdata['price'])
        {
            $G->where('id='.$id)->save($arr);
            $T->where('id='.$data['toid'])->save($arr);
            $D->where('getid='.$id.' and toid='.$id2)->save($arr);

            //静态奖
            $I=M('integral');

            //换时间来区分
            //５小时内20% 5-12小时 15%;

            $currtime=time();
            if($currtime-$tdata['begintime']<5*3600)
            {
                $interest=$gdata['price']*0.2;
            }
            else
            {
                $interest=$gdata['price']*0.15;
            }
            $integral1=$gdata['price']+$interest;//
            $integral5=$interest*0.1;//10%返消费积分
            $integral1=$integral1-$integral5;

            $tarr=array('interest'=>$interest,
                        'replay'=>$integral5);
            $T->where('id='.$id2)->save($tarr);
            // $I->where('userid='.$tdata['userid'])->setinc('integral1',$integral1);
            // $I->where('userid='.$tdata['userid'])->setinc('integral5',$integral5);


            //写入收支记录
            //支付记录
            //$userid 会员ID
            //$title 内容
            //$createtime 创建时间
            //$price 消费价格
            //$status 消费状态 1 收入，0 支出
            //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户
            $title='订单交易完成';
            $createtime=$tdata['createtime'];//订单开始时间算
            //$status=1;
            //$gotegary=1;
            // $this->Pay_history($tdata['userid'],$title,$integral1,$createtime,$status,$gotegary);
            /// $gotegary=5;
            //$this->Pay_history($tdata['userid'],$title,$integral5,$createtime,$status,$gotegary);

            //冻结奖金
            //$userid 得到奖金用户ID
            //$toid 提现ＩＤ
            //$integral1动态或者静态奖 奖金
            //$integral2返消费积分 奖金
            //$fromuserid 来自奖金用户
            //$gotegary 1静态奖，2动态奖
            //$createtime 创建时间
            //$title 内容
            $this->getfreeze($tdata['userid'],$id2,$integral1,$integral5,$tdata['userid'],1,$createtime,$title);
            //动态奖
            $this->incomefrom($tdata['userid'],$gdata['price'],$id2);


        }
        elseif($gdata['price']>$tdata['price'])//得到帮助金额大于提供帮助金额
        {
            // // if(empty($gdata['surplus']))
            // if($gdata['surplus']==0)
            // {
            //     // $G->where('id='.$id)->setinc('surplus',$tdata['price']);
            //     $G->where('id='.$id)->setinc('surplus',$gdata['price']-$tdata['price']);
            // }
            // elseif($gdata['surplus']<$gdata['price'])
            // {
            //     if(($tdata['price']+$gdata['surplus'])<$gdata['price'])
            //     {
            //         $G->where('id='.$id)->setinc('surplus',$tdata['price']);
            //     }
            //     elseif(($tdata['price']+$gdata['surplus'])==$gdata['price'])
            //     {
            //             $G->where('id='.$id)->save($arr);
            //     }
            // }
            // else
            // {
            //     $G->where('id='.$id)->save($arr);
            // }


            $T->where('id='.$data['toid'])->save($arr);
            $D->where('getid='.$id.' and toid='.$id2)->save($arr);
            //所有匹配订单是否完成
            $dall=$D->field('sum((select price from tm_tohelp where id=tm_toget.toid)) as price')->where('getid='.$id.' and status=3')->select();

            //已经完成
            if($dall[0]['price']==$gdata['price'])
            {
                $G->where('id='.$id)->save($arr);
            }


            //静态奖
            $I=M('integral');

            $currtime=time();
            if($currtime-$tdata['begintime']<5*3600)
            {
                $interest=$tdata['price']*0.2;
            }
            else
            {
                $interest=$tdata['price']*0.15;
            }
            $integral1=$tdata['price']+$interest;//

            $integral5=$interest*0.1;//10%返消费积分
            $integral1=$integral1-$integral5;


            $tarr=array('interest'=>$interest,
                        'replay'=>$integral5);
            $T->where('id='.$id2)->save($tarr);

            $title='订单交易完成';
            $createtime=$tdata['createtime'];
            $this->getfreeze($tdata['userid'],$id2,$integral1,$integral5,$tdata['userid'],1,$createtime,$title);
            //动态奖
            $this->incomefrom($tdata['userid'],$tdata['price'],$id2);



        }
        else
        {
            $G->where('id='.$id)->save($arr);

            $D->where('getid='.$id.' and toid='.$id2)->save($arr);
            //所有匹配订单是否完成
            $dall=$D->field('sum((select price from tm_gethelp where id=tm_toget.getid)) as price')->where('toid='.$id2.' and status=3')->select();




            //已经完成
            if($dall[0]['price']==$tdata['price'])
            {




                //静态奖
                $I=M('integral');
                $currtime=time();
                if($currtime-$tdata['begintime']<5*3600)
                {
                    $interest=$tdata['price']*0.2;
                }
                else
                {
                    $interest=$tdata['price']*0.15;
                }
                $integral1=$tdata['price']+$interest;//

                $integral5=$interest*0.1;//10%返消费积分
                $integral1=$integral1-$integral5;


                $tarr=array('interest'=>$interest,
                            'replay'=>$integral5);
                $T->where('id='.$id2)->save($tarr);


                //$I->where('userid='.$tdata['userid'])->setinc('integral1',$integral1);
                // $I->where('userid='.$tdata['userid'])->setinc('integral5',$integral5);
                //写入收支记录
                //支付记录
                //$userid 会员ID
                //$title 内容
                //$createtime 创建时间
                //$price 消费价格
                //$status 消费状态 1 收入，0 支出
                //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户
                $title='订单交易完成';
                $createtime=$tdata['createtime'];
                // $status=1;
                //$gotegary=1;
                //$this->Pay_history($tdata['userid'],$title,$integral1,$createtime,$status,$gotegary);
                //$gotegary=5;
                // $this->Pay_history($tdata['userid'],$title,$integral5,$createtime,$status,$gotegary);


                //冻结奖金
                //$userid 得到奖金用户ID
                //$toid 提现ＩＤ
                //$integral1动态或者静态奖 奖金
                //$integral2返消费积分 奖金
                //$fromuserid 来自奖金用户
                //$gotegary 1静态奖，2动态奖
                //$createtime 创建时间
                //$title 内容
                $this->getfreeze($tdata['userid'],$id2,$integral1,$integral5,$tdata['userid'],1,$createtime,$title);



                //动态奖
                $this->incomefrom($tdata['userid'],$tdata['price'],$id2);

                $T->where('id='.$data['toid'])->save($arr);
            }


        }
        session('tohelp',1);
        session('gethelp',1);

        //确认收款
        $udata=$T->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$id2)->find();
        $this->qrsms($udata['mobile'],$udata['username'],2);

        $map=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($map);

    }

    //发送短信
    public function qrsms($mobile,$username,$gotegary)
    {
        vendor('smsapi');

        //用户账号
        $uid = 'xxxs';
        //MD5密码
        // $pwd = '135812984444';
        $pwd = 'Nanyiwangji11';

        $api = new \SmsApi($uid,$pwd);


        $contentParam = array(

            'name'  => $username
        );

//变量模板ID
        // if($gotegary==1)
        // {
        //     $template = '394903';//提供帮助
        // }
        // else
        // {
        //     $template = '394904';//得到帮助
        // }

        switch($gotegary)
        {
            case 1:
                $template = '394903';//提供帮助
                break;
            case 2:
                $template = '394904';//提供帮助
                break;
            case 3:
                $template = '396004';//提供帮助
                break;
            case 4:
                $template = '393517';//提供帮助匹配成功
                break;
            case 5:
                $template = '394712';//得到帮助匹配成功
                break;
        }

//发送变量模板短信
        $result = $api->send($mobile,$contentParam,$template);

    }

    //用户动态奖金
    // public function incomefrom($userid,$price)
    // {

    //     $D=M('user');
    //     $data=$D->where('id='.$userid)->find();
    //     //第一代
    //     $data1=$D->where('username="'.$data['tusername'].'"')->find();


    //     if(!empty($data1))
    //     {
    //         //$this->doincomefrom($data['username'],$data1['id'],$price,0.04);
    //         //第三代


    //         $str1=substr($data['path'],0,strlen($data['path'])-1);

    //         $str=explode(',',$str1);


    //         $num=count($str);


    //         $n1=$num-1;
    //         $j=1;
    //         for($i=$n1;$i>1;$i--)
    //         {

    //             switch($j)
    //             {
    //                 case 1:
    //                     $this->doincomefrom($data['username'],$str[$i-1],$price,0.04);

    //                     break;
    //                 case 3:
    //                     if($data1['gotegary']>=1) {
    //                         $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.02);
    //                     }
    //                     break;
    //                 case 5:
    //                     if($data1['gotegary']>=2) {
    //                         $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.005);

    //                     }
    //                     break;
    //                 case 6:case 7:case 8:case 9:
    //                    if($data1['gotegary']>=3)
    //                     {
    //                         $this->doincomefrom($data['username'],$str[$i-1],$price,0.001);
    //                     }
    //                     break;
    //             }
    //             $j++;


    //         }


    //     }

    // }

    //$toid 来源订单
    public function incomefrom($userid,$price,$toid)
    {
        $D=M('user');
        $data=$D->where('id='.$userid)->find();
        //第一代
        $data1=$D->where('username="'.$data['tusername'].'"')->find();


        if(!empty($data1))
        {
            //$this->doincomefrom($data['username'],$data1['id'],$price,0.04);
            //第三代
            $str1=substr($data['path'],0,strlen($data['path'])-1);

            $str=explode(',',$str1);


            $num=count($str);


            $n1=$num-1;
            $j=1;
            for($i=$n1;$i>=1;$i--)
            {

                switch($j)
                {
                    case 1:
                        $this->doincomefrom($data['username'],$str[$i-1],$price,0.06,$toid);

                        break;
                    case 3:

                        $dataid=$D->field('gotegary')->where('id='.$str[$i - 1])->find();//得到奖金的权限
                        if($dataid['gotegary']>=1) {
                            $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.03,$toid);
                        }
                        break;
                    case 5:
                        $dataid=$D->field('gotegary')->where('id='.$str[$i - 1])->find();//得到奖金的权限
                        if($dataid['gotegary']>=2) {
                            $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.01,$toid);

                        }
                        break;
                    // case 6:case 7:case 8:case 9:
                    // $dataid=$D->field('gotegary')->where('id='.$str[$i - 1])->find();//得到奖金的权限
                    // if($dataid['gotegary']>=3)
                    // {
                    //     $this->doincomefrom($data['username'],$str[$i-1],$price,0.001,$toid);
                    // }
                    // break;
                }
                $j++;


            }


        }

    }

    //会员动态奖
    //$pre百分比
    //$username 产生订单的人
    //$toid 来源订单
    public function doincomefrom($username,$userid,$price,$pre,$toid)
    {
        $D=M('user');
        $data=$D->where('id='.$userid)->find();


        //烧伤
        $T=M('tohelp');
        $from=$T->where('userid='.$userid.' and status>=0 and status<3')->find();


        $temp=$T->field('sum(price) as total')->where('orderid="'.$from['orderid'].'"')->select();
        $from['price']=$temp[0]['total'];

        if(empty($from))
        {
            return;
        }
        $to=$T->where('id='.$toid)->find();


        if($from['price']>=$to['price'])
        {
            $price=$to['price'];
        }
        else
        {
            $price=$from['price'];
        }



        $I=M('integral');
        $integral2=$price*$pre;//4%返消费积分
        $integral5=$integral2*0.2;
        $integral2=$integral2-$integral5;

       // $I->where('userid='.$data['id'])->setinc('integral2',$integral2);
        //$I->where('userid='.$data['id'])->setinc('integral5',$integral5);


        $title='会员'.$username.'领导奖';
        //来源ＩＤ
        $fromuser=$D->field('id')->where('username="'.$username.'"')->find();
        $createtime=time();
       // $status=1;
       // $gotegary=2;
       // $this->Pay_history($userid,$title,$integral2,$createtime,$status,$gotegary);
        //$gotegary=5;
        //$this->Pay_history($userid,$title,$integral5,$createtime,$status,$gotegary);

        //冻结奖金
        //$userid 得到奖金用户ID
        //$toid 提现ＩＤ
        //$integral1动态或者静态奖 奖金
        //$integral2返消费积分 奖金
        //$fromuserid 来自奖金用户
        //$gotegary 1静态奖，2动态奖
        //$createtime 创建时间
        //$title 内容
        $this->getfreeze($data['id'],$toid,$integral2,$integral5,$fromuser['id'],2,$createtime,$title);




    }

    //冻结奖金
    //$userid 得到奖金用户ID
    //$toid 提现ＩＤ
    //$integral1动态或者静态奖 奖金
    //$integral2返消费积分 奖金
    //$fromuserid 来自奖金用户
    //$gotegary 1静态奖，2动态奖
    //$createtime 创建时间
    //$title 内容
    public function getfreeze($userid,$toid,$integral1,$integral2,$fromuserid,$gotegary,$createtime,$title)
    {
        $D=M('freeze');
        //先删除,再插入
        $D->where('userid='.$userid.' and toid='.$toid.' and fromuserid='.$fromuserid.' and gotegary='.$gotegary.' and integral1='.$integral1.' and integral2='.$integral2.' and title="'.$title.'"')->delete();
        $arr=array(
            'userid'=>$userid,
            'toid'=>$toid,
            'fromuserid'=>$fromuserid,
            'gotegary'=>$gotegary,
            'begintime'=>$createtime,
            'integral1'=>$integral1,
            'integral2'=>$integral2,
            'title'=>$title,
        );
        $D->add($arr);
    }

    //会员动态奖
    //$pre百分比
    //$username 产生订单的人
    // public function doincomefrom($username,$userid,$price,$pre)
    // {




    //     $D=M('user');
    //     $data=$D->where('id='.$userid)->find();


    //     $I=M('integral');
    //     $integral2=$price*$pre;//4%返消费积分
    //     $integral5=$integral2*0.2;
    //     $integral2=$integral2-$integral5;

    //     $I->where('userid='.$data['id'])->setinc('integral2',$integral2);
    //     $I->where('userid='.$data['id'])->setinc('integral5',$integral5);


    //     $title='会员'.$username.'领导奖';
    //     $createtime=time();
    //     $status=1;
    //     $gotegary=2;
    //     $this->Pay_history($userid,$title,$integral2,$createtime,$status,$gotegary);
    //     $gotegary=5;
    //     $this->Pay_history($userid,$title,$integral5,$createtime,$status,$gotegary);

    // }

    //抢单列表
    public function qiangdan()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $sqlw.=' and status=-1';

        $room = M('gethelp');


        // $info=$room->field('id,orderid,userid,price,createtime,status,begintime,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_gethelp where ".$sqlw;


        // $query = $room->query($sql);


        // $pageurl="/api/index/qiangdan?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//总单数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //排单币转账
    public function paidanbizz()
    {
        $userid=I('post.userid');
        $zcname=M('user')->field('username')->where('id="'.$userid.'"')->find();
        if(empty($zcname))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'帐户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//帐户不存在
        }
        $price=I('post.price');
        $username=I('post.username');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $room=$D->field('id')->where('username="'.$username.'"')->find();
        if(empty($room))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'帐户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//帐户不存在
        }
        if($userid==$room['id'])
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'排单币转账不可以转给自己！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//排单币转账不可以转给自己
        }


        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'支付密码不对！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//支付密码不对
        }
        // if($paypassword!=$data['paypassword'])
        // {
        //     $map=array(
        //         'errnum'=>0,
        //         'retMsg'=>'支付密码不对！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($map);die;//支付密码不对
        // }
        if($idata['integral3']<$price)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'余额不足！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//余额不足
        }
        $arr=array(
            'integral3'=>$idata['integral3']-$price,
        );
        $I->where('userid='.$userid)->save($arr);
        //转出记录
        $title='转出排单币给'.$username;
        $createtime=time();
        $this->case_history($userid,$price,2,1,$createtime,$title);
        //转入记录
        $iroom=$I->where('userid='.$room['id'])->find();
        $arr=array(
            'integral3'=>$iroom['integral3']+$price,
        );
        $I->where('userid='.$room['id'])->save($arr);
        $title='从'.$zcname['username'].'转入排单币';
        $this->case_history($room['id'],$price,2,1,$createtime,$title);

        $map=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );
        $this->ajaxReturn($map);
    }

    //排单币对帐
    public function paidanbilb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $sqlw.=' and userid='.$userid.' and gotegary=1';

        $room = M('buy_history');


        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        // $query = $room->query($sql);


        // $pageurl="/api/index/paidanbilb?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);
    }

    //激活码转账
    public function jihuomazz()
    {
        $userid=I('post.userid');
        $zcname=M('user')->field('username')->where('id="'.$userid.'"')->find();
        if(empty($zcname))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'帐户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//帐户不存在
        }
        $price=I('post.price');
        $username=I('post.username');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $room=$D->field('id')->where('username="'.$username.'"')->find();
        if(empty($room))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'帐户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//帐户不存在
        }
        if($userid==$room['id'])
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'激活码转账不可以转给自己！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//激活码转账不可以转给自己
        }


        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'支付密码不对！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//支付密码不对
        }
        // if($paypassword!=$data['paypassword'])
        // {
        //     $map=array(
        //         'errnum'=>0,
        //         'retMsg'=>'支付密码不对！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($map);die;//支付密码不对
        // }
        if($idata['integral4']<$price)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'余额不足，请充值！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//余额不足
        }
        $arr=array(
            'integral4'=>$idata['integral4']-$price,
        );
        $I->where('userid='.$userid)->save($arr);
        //转出记录
        $title='转出激活码给'.$username;
        $createtime=time();
        $this->case_history($userid,$price,2,2,$createtime,$title);
        //转入记录
        $iroom=$I->where('userid='.$room['id'])->find();
        $arr=array(
            'integral4'=>$iroom['integral4']+$price,
        );
        $I->where('userid='.$room['id'])->save($arr);
        $title='从'.$zcname['username'].'转入激活码';
        $this->case_history($room['id'],$price,2,2,$createtime,$title);
        
        $map=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );
        $this->ajaxReturn($map);
    }

    //激活码对帐
    public function jihuomalb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $sqlw.=' and userid='.$userid.' and gotegary=2';

        $room = M('buy_history');


        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        // $query = $room->query($sql);


        // $pageurl="/api/index/jihuomalb?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);
    }

    //奖金转换
    public function jiangjinzhh()
    {
        $userid=I('post.userid');
        $zcname=M('user')->field('username')->where('id="'.$userid.'"')->find();
        if(empty($zcname))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'会员帐户不存在!',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//帐户不存在
        }
        $price=I('post.price');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'安全密码输入错误！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//安全密码不对
        }
        // if($paypassword!=$data['paypassword'])
        // {
        //     $map=array(
        //         'errnum'=>0,
        //         'retMsg'=>'安全密码输入错误！',
        //         // 'retMsg'=>'error',
        //     );
        //     $this->ajaxReturn($map);die;//安全密码不对
        // }
        if($price<500)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'转账金额不能低于500！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//余额不足
        }
        if($price%100>0)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'转账金额只能填写100的整数倍！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//余额不足
        }
        if($idata['integral2']<$price)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'动态账户余额不足！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;//余额不足
        }
        $arr=array(
            'integral1'=>$idata['integral1']+$price,
            'integral2'=>$idata['integral2']-$price,
        );
        $I->where('userid='.$userid)->save($arr);
        //支入记录
        $title='转帐';
        $createtime=time();
        $this->Pay_history($userid,$title,$price,$createtime,1,1);
        $this->Pay_history($userid,$title,$price,$createtime,0,2);

        $map=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );
        $this->ajaxReturn($map);
    }

    //财务明细
    public function income()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }


        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $sqlw.=' and userid='.$userid;

        $room = M('income');

        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();

        // $sql = "select count(*) as total from tm_income where ".$sqlw;


        // $query = $room->query($sql);


        // $pageurl="/api/index/income?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);
    }

    //商品展示
    public function product()
    {
        if(I('get.userid')){
            $userid=I('get.userid');
            $zcname=M('user')->field('username')->where('id="'.$userid.'"')->find();
            if(empty($zcname))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'用户不存在！',
                    // 'retMsg'=>'error',
                );
                $this->ajaxReturn($arr);die;//帐户不存在
            }

            $I=M('integral');
            $integral=$I->where('userid='.$userid)->find();
            $integral5=$integral['integral5'];
        }

        $D=M('product');
        $data=$D->select();

        for($i=0;$i<count($data);$i++){

            $data[$i]['content']=htmlspecialchars_decode($data[$i]['content']);

        }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'integral5'=>$integral5,
            'retData'=>$data,
        );

        $this->ajaxReturn($arr);

    }

    //商品详情
    public function product_view()
    {
        if(I('get.userid')){
            $userid=I('get.userid');
            $zcname=M('user')->where('id="'.$userid.'"')->find();
            if(empty($zcname))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'用户不存在！',
                    // 'retMsg'=>'error',
                );
                $this->ajaxReturn($arr);die;//帐户不存在
            }

            $I=M('integral');
            $integral=$I->where('userid='.$userid)->find();
            $integral5=$integral['integral5'];
        }

        $id=I('get.id');
        
        $D=M('product');
        $data=$D->where('id='.$id)->find();

        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'不存在此商品！',
                // 'retMsg'=>'error',
            );
        }else{
            $data['content']=htmlspecialchars_decode($data['content']);
            $data['integral5']=$integral5;
            $data['realname']=$zcname['realname'];
            $data['mobile']=$zcname['mobile'];
            $data['address']=$zcname['address'];
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
                'retData'=>$data,
            );
        }

    
        $this->ajaxReturn($arr);

    }

    //立即购买提交订单
    public function doproduct_order()
    {
        $id=I('post.id');
        $address=I('post.address');
        $realname=I('post.realname');
        $mobile=I('post.mobile');

        $number=I('post.number');
        $pay=I('post.pay');

        $userid=I('post.userid');


        $P=M('product');
        $pdata=$P->where('id='.$id)->find();
        $U=M('user');
        $udata=$U->where('id='.$userid)->find();
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        //$A=M('user_address');
        //$adata=$A->where('id='.$address)->find();
        if($idata['integral5']<$pdata['price']*$number)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'余额不足，请充值！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($map);die;//余额不足
        }


        $createtime=time();

        $orderid=$this->Createorderid(2);//商品订单

        $arr=array(
            'userid'=>$userid,
            'orderid'=>$orderid,
            'productname'=>$pdata['productname'],
            'price'=>$pdata['price']*$number,
            'number'=>$number,
            'createtime'=>$createtime,
            'status'=>1,
            'pay'=>$pay,
        );
        $O=M('order');
        $oid=$O->add($arr);
        $arr1=array(
            'orderid'=>$oid,
            'mobile'=>$mobile,
            'contact'=>$realname,
            //'tel'=>$adata['tel'],
           // 'post'=>$adata['post'],
            'address'=>$address,
        );
        $OA=M('order_address');
        $OA->add($arr1);

        $OP=M('order_product');
        $arr2=array(
            'productid'=>$id,
            'orderid'=>$oid,
            'number'=>$number,
            'price'=>$pdata['price']*$number,
            'productname'=>$pdata['productname'],
        );
        $OP->add($arr2);

        //判断是否付款
        switch($pay)
        {
            case 1://帐户余额支付

                // if($idata['integral5']<$pdata['price']*$number)
                // {
                //     echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
                //     die();
                // }
                $integral1= $idata['integral5']-$pdata['price']*$number;


                $payarr=array(
                    'integral5'=>$integral1,

                );
                $I->where('userid='.$userid)->save($payarr);
                $sarr=array('status'=>2);//改变支付状态
                $O->where('id='.$oid)->save($sarr);
                $title='购买商品';
                $this->Pay_history($userid,$title,$pdata['price']*$number,$createtime,0,1,0,0);//支出记录;
                
                break;

        }

        $map=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );

        $this->ajaxReturn($map);




    }

    //生成20位订单号
    private function Createorderid($t)
    {
        $orderid= date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(1000,9999);
        //1充值　２订单支付　３注册支付
        $orderid=$t.$orderid;
        return $orderid;

    }

    //加入购物车
    public function docart()
    {
        $productid=I('get.id');
        $userid=I('get.userid');
        $D=M('cart');
        $P=M('product');
        $pdata=$P->where('id='.$productid)->find();
        if(!$pdata){
            $map=array(
                'errnum'=>0,
                'retMsg'=>'不存在此商品！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;
        }
        $udata=M('user')->where('id='.$userid)->find();
        if(!$udata){
            $map=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;
        }

        $arr=array(
            'userid'=>$userid,
            'productname'=>$pdata['productname'],
            'price'=>$pdata['price'],
            'number'=>1,
            'productid'=>$productid,
            'createtime'=>time(),
        );
        $D->add($arr);

        $map=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );

        $this->ajaxReturn($map);

    }

    //购物车列表
    public function cart()
    {
        $userid=I('get.userid');
        $U=M('user');
        $user=$U->where('id='.$userid)->find();
        if(!$user){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $I=M('integral');
        $int=$I->where('userid='.$userid)->find();
        $integral5=$int['integral5'];

        // $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $sqlw='1=1 ';

        $sqlw.=' and userid='.$userid;

        $room = M('cart');

        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();
        for($i=0;$i<count($info);$i++){

            $productid=$info[$i]['productid'];
            $pdata=M('product')->where('id='.$productid)->find();
            $info[$i]['pic']=$pdata['pic'];
            $info[$i]['content']=htmlspecialchars_decode($pdata['content']);

        }

        // $sql = "select count(*) as total from tm_cart where ".$sqlw;

        // $query = $room->query($sql);

        // $pageurl="/api/index/cart.html?";

        // $count = $query[0]['total'];


        // // $this->assign("total",$count);
        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'realname'=>$user['realname'],
            'mobile'=>$user['mobile'],
            'address'=>$user['address'],
            'integral5'=>$integral5,
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //清除购物车中单个物品
    public function deletecart_one(){
        $D=M('cart');
        $id=I('get.id');
        $userid=I('get.userid');
        $data=$D->where('id='.$id.' and userid='.$userid)->find();
        if(empty($data)){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'购物车中不存在此商品！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        $D->where('id='.$id.' and userid='.$userid)->delete();

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);
    }

    //清空购物车
    public function deletecart(){
        $D=M('cart');
        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(empty($data)){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $D->where('userid='.$userid)->delete();

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);
    }

    //提交购物车订单
    public function docartorder()
    {
        $id=I('post.id');
        // dump($id);die;
        $number=0;
        $address=I('post.address');
        $realname=I('post.realname');
        $mobile=I('post.mobile');
        $pay=I('post.pay');

        $userid=I('post.userid');
        // $userid=session('userid');

        $totalprice=I('post.totalprice');//合计总积分
        foreach($id as $vo)
        {
            $number+=I('post.number'.$vo);
        }
        $C=M('cart');

        $productid=$C->field('productid')->where('id='.$id[0])->find();


        $P=M('product');
        $pdata=$P->where('id='.$productid['productid'])->find();
        $U=M('user');
        $udata=$U->where('id='.$userid)->find();
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        // $A=M('user_address');
        // $adata=$A->where('id='.$address)->find();

        if($idata['integral5']<$totalprice)
        {
            $map=array(
                'errnum'=>0,
                'retMsg'=>'余额不足，请充值！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($map);die;//余额不足
        }


        $createtime=time();

        $orderid=$this->Createorderid(2);//商品订单

        $arr=array(
            'userid'=>$userid,
            'orderid'=>$orderid,
            'productname'=>$pdata['productname'],
            'price'=>$totalprice,
            'number'=>$number,
            'createtime'=>$createtime,
            'status'=>1,
            'pay'=>$pay,
        );
        $O=M('order');
        $oid=$O->add($arr);
        $arr1=array(
            'orderid'=>$oid,
            'mobile'=>$mobile,
            'contact'=>$realname,
            //'tel'=>$adata['tel'],
            // 'post'=>$adata['post'],
            'address'=>$address,
        );
        $OA=M('order_address');
        $OA->add($arr1);

        //判断是否付款
        switch($pay)
        {
            case 1://帐户余额支付

                // if($idata['integral5']<$totalprice)
                // {
                //     echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
                //     die();
                // }
                $integral1= $idata['integral5']-$totalprice;


                $payarr=array(
                    'integral5'=>$integral1,

                );
                $I->where('userid='.$userid)->save($payarr);
                $sarr=array('status'=>2);//改变支付状态
                $O->where('id='.$oid)->save($sarr);
                $title='购买商品';
                $this->Pay_history($userid,$title,$totalprice,$createtime,0,1,0,0);//支出记录;
                //产生收益
                //$this->order_pay($udata['pid'],$pdata['price'],$createtime);//

                //送积分
                //修改支付状态
                //超过金额自动升级
                //$this->pay_history();//支出记录;
                //产生收益
                //$this->pay();
                break;
//            case 2:
//                if($idata['integral2']<$totalprice)
//                {
//                    echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
//                    die();
//                }
//                $integral1= $idata['integral2']-$totalprice;
//                $payarr=array(
//                    'integral2'=>$integral1,
//
//                );
//                $I->where('userid='.$userid)->save($payarr);
//                $sarr=array('status'=>2);//改变支付状态
//                $O->where('id='.$oid)->save($sarr);
//                $title='购买商品';
//                $this->Pay_history($userid,$title,$totalprice,$createtime,0,1,0,0);//支出记录;　　
//                break;
//            case 3:
//
//                break;
        }


        $PO=M('order_product');


        foreach($id as $vo)
        {
            $pid=$C->field('productid')->where('id='.$vo)->find();
            $pname=$P->field('productname')->where('id='.$pid['productid'])->find();
            $arr=array(
                'productid'=>$vo,
                'orderid'=>$oid,
                'number'=>I('post.number'.$vo),
                'price'=>I('post.total'.$vo),
                'productname'=>$pname['productname'],

            );
            $PO->add($arr);
            $C->where('id='.$vo)->delete();//移除购物车

        }

        $map=array(
                'errnum'=>0,
                'retMsg'=>'success',
            );

        $this->ajaxReturn($map);

    }

    //订单列表
    public function order()
    {

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }


        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';


        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }



        // $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $sqlw='1=1 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }
        $sqlw.=' and userid='.$userid;

        $room = M('order');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        // $memberid=session('userid');




        // $info=$room->field('tm_order.id,tm_order.userid,tm_order.orderid,tm_order.productname,tm_order.price,tm_order.number,tm_order.createtime,tm_order.status,tm_order_address.kdgs,tm_order_address.kddh')->join('tm_order_address on tm_order.id=tm_order_address.orderid')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('tm_order.id,tm_order.userid,tm_order.orderid,tm_order.productname,tm_order.price,tm_order.number,tm_order.createtime,tm_order.status,tm_order_address.kdgs,tm_order_address.kddh')->join('tm_order_address on tm_order.id=tm_order_address.orderid')->order(" id desc")->where($sqlw)->select();

        for($i=0;$i<count($info);$i++){

            $name=M('product')->where(array('productname' => $info[$i]['productname']))->find();
            $info[$i]['pic']=$name["pic"];

        }

        // $sql = "select count(*) as total from tm_order where ".$sqlw;


        // $query = $room->query($sql);





        // $pageurl="/api/index/order.html?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //订单详情
    public function view()
    {
        if(I('get.userid')){
            $userid=I('get.userid');
            $zcname=M('user')->where('id="'.$userid.'"')->find();
            if(empty($zcname))
            {
                $arr=array(
                    'errnum'=>0,
                    'retMsg'=>'用户不存在！',
                    // 'retMsg'=>'error',
                );
                $this->ajaxReturn($arr);die;//帐户不存在
            }

            $I=M('integral');
            $integral=$I->where('userid='.$userid)->find();
            $integral5=$integral['integral5'];
        }else{
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请输入会员id',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $id=I('get.id');

        if(!I('get.id')){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请输入订单id',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $P=M('order_product');
        $pdata=$P->field('id,productid,number,price,productname')->where('orderid='.$id)->select();
        for($i=0;$i<count($pdata);$i++){

            $productid=$pdata[$i]['productid'];
            $spdata=M('product')->where('id='.$productid)->find();
            $pdata[$i]['pic']=$spdata['pic'];

        }

        $A=M('order_address');
        $adata=$A->where('orderid='.$id)->find();

        $D=M('order');
        $data=$D->where('id='.$id.' and userid='.$userid)->find();
        
        $data['mobile']=$adata['mobile'];
        $data['contact']=$adata['contact'];
        $data['tel']=$adata['tel'];
        $data['post']=$adata['post'];
        $data['address']=$adata['address'];
        $data['kdgs']=$adata['kdgs'];
        $data['kddh']=$adata['kddh'];
        // $data['fhtime']=$adata['fhtime'];//发货时间
        $data['order_product']=$pdata;

       $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'retData'=>$data,
        );

        $this->ajaxReturn($arr);
    }

    //网站公告
    public function news()
    {

        $t=$_REQUEST['t'];

        $search='';

        if(!empty($mobile))
            $search.='&t='.$t;


        $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;

        $sqlw='1=1 and ("'.$t.'"="" or title like "%'.$t.'%")';

        $room = M('news');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        //$memberid=session('userid');


        // $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->order(" id desc")->where($sqlw)->select();

        for($i=0;$i<count($info);$i++){

            $info[$i]['content']=htmlspecialchars_decode($info[$i]['content']);

        }

        // $sql = "select count(*) as total from tm_news where ".$sqlw;


        // $query = $room->query($sql);


        // $pageurl="/api/index/news.html?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 't'=>$t,//分页路径
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);


    }


    //公告详情
    public function news_view()
    {
        $id=I('get.id');
        if(empty($id))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'不存在此公告！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }
        $D=M('news');
        $room=$D->where('id='.$id)->find();

        $room['content']=htmlspecialchars_decode($room['content']);


        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'retData'=>$room,
        );

        $this->ajaxReturn($arr);

    }

    //在线留言
    public function domessage()
    {
        $title=I('post.title');
        $content=I('post.content');
        $createtime=time();

        $userid=I('post.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $map=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($map);die;
        }

        $D=M('message');
        $arr=array(
            'title'=>$title,
            'content'=>$content,
            'createtime'=>$createtime,
            'userid'=>$userid
        );
        $D->add($arr);

        $map=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($map);
    }

    //冻结记录
    public function freeze()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }


       $sqlw='1=1 and status=0 ';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and begintime>='.$begintime.' and begintime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and begintime>='.$begintime.' and begintime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and begintime>='.$endtime.' and begintime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $sqlw.=' and userid='.$userid;

        $room = M('freeze');

        // $info=$room->field('id,userid,toid,integral1,integral2,gotegary,title,status,begintime,(select username from tm_user where id=tm_freeze.fromuserid) as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('id,userid,toid,integral1,integral2,gotegary,title,status,begintime,(select username from tm_user where id=tm_freeze.fromuserid) as username')->order(" id desc")->where($sqlw)->select();

        $sql = "select count(*) as total from tm_freeze where ".$sqlw;


        $query = $room->query($sql);
        $sql = "select sum(integral1+integral2) as total from tm_freeze where ".$sqlw;

        $integraltotal=$room->query($sql);


        // $pageurl="/api/index/freeze?";


        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            // 'keyword'=>$keyword,//关键字
            'begintime'=>$begintime,//开始时间
            'endtime'=>$endtime,//结束时间
            'integraltotal'=>$integraltotal[0]['total'],//冻结总金额
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //退出
    public function logout()
    {
        session(null);
        // echo"<script>location.href='/'</script>";
        // if($_SESSION<>''){
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'error',
        //     );
        // }else{
        //     $arr=array(
        //         'errnum'=>0,
        //         'retMsg'=>'success',
        //     );
        // }
        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );
        
        $this->ajaxReturn($arr);

    }

    //短信验证码接口
    public function sms()
    {
        header("Content-type:text/html; charset=UTF-8");
//获取手机号
        $mobile = I('post.mobile');
//获取验证码
        // $send_code = I('post.send_code');
        $uname=I('post.uname');

        //验证用户名称是否正确
        $D=M('user');
        $data=$D->field('username,mobile')->where('username="'.$uname.'"')->find();

        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'会员名错误！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//会员名错误
        }
        if(empty($data['mobile']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'帐户没有绑定手机！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        if($mobile!=$data['mobile'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'手机号码不正确！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//手机号码不正确
        }


        vendor('smsapi');

        //用户账号
        $uid = 'xxxs';
        //MD5密码
        // $pwd = '135812984444';
        $pwd = 'Nanyiwangji11';

        $api = new \SmsApi($uid,$pwd);


        $contentParam = array(
            'code'      => $api->randNumber(),
            'username'  => '您好',
            'name'=>$uname
        );

//变量模板ID
        $template = '394713';

//发送变量模板短信
        $result = $api->send($mobile,$contentParam,$template);

        if($result['stat']=='100')
        {

            session('mobile',$mobile);
            session('mobile_code', $contentParam['code']);
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
                'retData'=>$contentParam['code'],
            );

            $this->ajaxReturn($arr);die;
        }
        else
        {

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'同一手机号验证码短信发送超出5条！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//同一手机号验证码短信发送超出5条
        }



    }

    //下一步
    public function dosms()
    {
        $mobile = I('post.mobile');
//获取验证码

        $uname=I('post.uname');
        $mobile_code=I('post.mobile_code');
        $hqmobile_code=I('post.hqmobile_code');

        //验证用户名称是否正确
        $D=M('user');
        $data=$D->field('username,mobile')->where('username="'.$uname.'"')->find();
        //帐户为空
        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'帐户为空！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }
        //没有绑定手机号
        if(empty($data['mobile']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'没有绑定手机号！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入手机号与绑定号不符
        if($mobile!=$data['mobile'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入手机号与绑定号不符！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入验证码不正确
        // if($mobile_code!=session('mobile_code'))
        if($mobile_code!=$hqmobile_code)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入验证码不正确！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//输入验证码不正确
        }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'retData'=>$uname,
            'retCode'=>$hqmobile_code,
        );

        $this->ajaxReturn($arr);die;

    }

    //重置登录密码
    public function resetpwd()
    {
        $mobile = I('post.mobile');
        $uname=I('post.uname');
        $mobile_code=I('post.mobile_code');
        $hqmobile_code=I('post.hqmobile_code');
        $newpwd=I('post.newpwd');
        $qrnewpwd=I('post.qrnewpwd');
        if($newpwd=='')
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请先输入新登录密码！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//新密码与确认新密码不一致
        }
        if($newpwd!=$qrnewpwd)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'新密码与确认新密码不一致！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//新密码与确认新密码不一致
        }
        //验证用户名称是否正确
        $D=M('user');
        $data=$D->field('username,mobile')->where('username="'.$uname.'"')->find();
        //帐户为空
        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'帐户为空！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }
        //没有绑定手机号
        if(empty($data['mobile']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'没有绑定手机号！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入手机号与绑定号不符
        if($mobile!=$data['mobile'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入手机号与绑定号不符！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入验证码不正确
        // if($mobile_code!=session('mobile_code'))
        if($mobile_code!=$hqmobile_code)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入验证码不正确',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }

        $newpwd=md5($newpwd);
        $newpwd=$this->md5pw($newpwd);
        $map=array('password'=>$newpwd);

        // $map=array('password'=>md5($newpwd));
        $D->where('username="'.$uname.'"')->save($map);

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);die;

    }

    //修改安全密码短信验证码接口
    public function paysms()
    {
        header("Content-type:text/html; charset=UTF-8");
//获取手机号
        $mobile = I('post.mobile');
//获取验证码
        // $send_code = I('post.send_code');
        $uname=I('post.uname');

        //验证用户名称是否正确
        $D=M('user');
        $data=$D->field('username,mobile')->where('username="'.$uname.'"')->find();

        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'会员名错误！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//会员名错误
        }
        if(empty($data['mobile']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'帐户没有绑定手机！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        if($mobile!=$data['mobile'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'手机号码不正确！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//手机号码不正确
        }


        vendor('smsapi');

        //用户账号
        $uid = 'xxxs';
        //MD5密码
        // $pwd = '135812984444';
        $pwd = 'Nanyiwangji11';

        $api = new \SmsApi($uid,$pwd);


        $contentParam = array(
            'code'      => $api->randNumber(),
            'username'  => '您好',
            'name'=>$uname
        );

//变量模板ID
        $template = '396416';

//发送变量模板短信
        $result = $api->send($mobile,$contentParam,$template);

        if($result['stat']=='100')
        {

            session('mobile',$mobile);
            session('mobile_code', $contentParam['code']);
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'success',
                'retData'=>$contentParam['code'],
            );

            $this->ajaxReturn($arr);die;
        }
        else
        {

            $arr=array(
                'errnum'=>0,
                'retMsg'=>'同一手机号验证码短信发送超出5条！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//同一手机号验证码短信发送超出5条
        }



    }

    //重置安全密码
    public function resetpaypwd()
    {
        $mobile = I('post.mobile');
        $uname=I('post.uname');
        $mobile_code=I('post.mobile_code');
        $hqmobile_code=I('post.hqmobile_code');
        $newpwd=I('post.newpwd');
        $qrnewpwd=I('post.qrnewpwd');
        if($newpwd=='')
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'请先输入新安全密码！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//新密码与确认新密码不一致
        }
        if($newpwd!=$qrnewpwd)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'新密码与确认新密码不一致！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//新密码与确认新密码不一致
        }
        //验证用户名称是否正确
        $D=M('user');
        $data=$D->field('username,mobile')->where('username="'.$uname.'"')->find();
        //帐户为空
        if(empty($data))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'帐户为空！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }
        //没有绑定手机号
        if(empty($data['mobile']))
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'没有绑定手机号！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入手机号与绑定号不符
        if($mobile!=$data['mobile'])
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入手机号与绑定号不符！',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;//帐户没有绑定手机
        }
        //输入验证码不正确
        // if($mobile_code!=session('mobile_code'))
        if($mobile_code!=$hqmobile_code)
        {
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'输入验证码不正确',
                // 'retMsg'=>'error',
            );

            $this->ajaxReturn($arr);die;
        }

        $newpwd=md5($newpwd);
        $newpwd=$this->md5pw($newpwd);
        $map=array('paypassword'=>$newpwd);

        // $map=array('paypassword'=>md5($newpwd));
        $D->where('username="'.$uname.'"')->save($map);

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
        );

        $this->ajaxReturn($arr);die;

    }

    //直推会员提供帮助列表
    public function zt_tgbzlb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 and';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $map['tusername']=$data['username'];
        $room = M('tohelp');
        $zt_data=M('user')->where($map)->select();

        for($a=0;$a<count($zt_data);$a++)
        {
            $zt_id=$zt_data[$a]['id'];
            $sqlw.=' userid='.$zt_id;
            if($a<count($zt_data)-1){
                $sqlw.=' or ';
            }

        }

// echo $sqlw;
        
        // $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->select();
        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id=tm_tohelp.userid) as username')->order(" id desc")->where($sqlw)->select();
        $z_price=$room->where($sqlw)->sum('price');
        
        // for($i=0;$i<count($info);$i++)
        // {
        //     $username=M('user')->field('username,mobile,realname')->where('id='.$info[$i]['userid'])->find();
        //     $info[$i]['username']=$username['username'];
        // }
// dump($info);
// echo $z_price;
// die;

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'num'=>count($info),
            'z_price'=>$z_price,
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

    }

    //直推会员得到帮助列表
    public function zt_jsbzlb()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

        if(!empty($begintime))
        {
            $begintime=strtotime($begintime);
            $search='&b='.$begintime;
        }
        if(!empty($endtime))
        {
            $endtime=strtotime($endtime);
            $search.='&e='.$endtime;
        }

        $sqlw='1=1 and';

        if($begintime!='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and createtime>='.$begintime.' and createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and createtime>='.$endtime.' and createtime<'.($endtime+24*60*60);
        }

        // $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        // $pagesize =10;

        $userid=I('get.userid');
        $data=M('user')->where('id='.$userid)->find();
        if(!$data){
            $arr=array(
                'errnum'=>0,
                'retMsg'=>'用户不存在！',
                // 'retMsg'=>'error',
            );
            $this->ajaxReturn($arr);die;
        }

        $map['tusername']=$data['username'];
        $room = M('gethelp');
        $zt_data=M('user')->where($map)->select();

        for($a=0;$a<count($zt_data);$a++)
        {
            $zt_id=$zt_data[$a]['id'];
            $sqlw.=' userid='.$zt_id;
            if($a<count($zt_data)-1){
                $sqlw.=' or ';
            }

        }

        // $sqlw.=' and userid='.$userid;

        // $room = M('gethelp');

        // $info=$room->field('id,orderid,userid,price,createtime,status,begintime,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,pic,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname')->order(" id desc")->where($sqlw)->select();
        $z_price=$room->where($sqlw)->sum('price');

        // $sql = "select count(*) as total from tm_gethelp where ".$sqlw;

        // $query = $room->query($sql);

        // $U=M('user');
        // $T=M('toget');
        // for($i=0;$i<count($info);$i++)
        // {
        //     $tdata=$T->field('tm_tohelp.id,tm_tohelp.orderid,tm_tohelp.userid,tm_tohelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_tohelp.begintime,(select username from tm_user where id=tm_tohelp.userid) as username,(select alipay from tm_user where id=tm_tohelp.userid) as alipay,(select weixin from tm_user where id=tm_tohelp.userid) as weixin,(select mobile from tm_user where id=tm_tohelp.userid) as mobile,(select bank from tm_user where id=tm_tohelp.userid) as bank,(select bankname from tm_user where id=tm_tohelp.userid) as bankname,(select bankcode from tm_user where id=tm_tohelp.userid) as bankcode,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select tusername from tm_user where id=tm_tohelp.userid) as tusername')->join('tm_tohelp on tm_tohelp.id=tm_toget.toid')->where('tm_toget.getid='.$info[$i]['id'])->select();

        //     for($j=0;$j<count($tdata);$j++)
        //     {
        //         $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
        //         $tdata[$j]['trealname']=$tdata1['realname'];
        //         $tdata[$j]['tmobile']=$tdata1['mobile'];

        //         if($info[$i]['price']>$tdata[$j]['price']){

        //             $tdata[$j]['pipeije']=$tdata[$j]['price'];

        //         }
        //         if($info[$i]['price']==$tdata[$j]['price']){

        //             $tdata[$j]['pipeije']=$tdata[$j]['price'];

        //         }
        //         if($info[$i]['price']<$tdata[$j]['price']){

        //             $tdata[$j]['pipeije']=$info[$i]['price'];

        //         }

        //     }

        //     $info[$i]['sub']=$tdata;
        //     $z_price +=$info[$i]['price'];
        // }

        // $pageurl="/api/index/jsbzlb?";

        // $count = $query[0]['total'];

        // if($count%$pagesize==0){
        //     $z_ye=$count/$pagesize;
        // }else{
        //     $z_ye=(int)floor($count/$pagesize)+1;
        // }

        $arr=array(
            'errnum'=>0,
            'retMsg'=>'success',
            'num'=>count($info),
            'z_price'=>$z_price,
            // 'endtime'=>12,
            // 'keyword'=>$keyword,//关键字
            // 'begintime'=>$begintime,//开始时间
            // 'endtime'=>$endtime,//结束时间
            // 'pageurl'=>$pageurl,//分页路径
            // 'search'=>$search,//查询路径
            // 'page'=>$page,//当前页数
            // 'pagesize'=>$pagesize,//每页显示的数量
            // 'total'=>$count,//团队总人数
            // 'count'=>$z_ye,//总页数
            'retData'=>$info,
        );

        $this->ajaxReturn($arr);

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