<?php
namespace Pc\Controller;
use Think\Controller;
use Think\Verify;


class IndexController extends Controller{
	
	public function index()
    {
        header("Content-Type: text/html;charset=utf-8");
    	//判断是否是新密码
        if(session('id')!='')
        {
            $userid=session('id');
            $password='111111';
            $U=M('user');
            $udata=$U->where('id='.$userid)->find();
            if($this->_checkPwd(md5($password),$udata['password']))
            {
        
                        echo"<script>alert('请先修改登录密码及交易密码！');location.href='/pc/wo/mmxg';</script>";
                        die();
            }
        }



       

        $domain=$_SERVER['REQUEST_URI'];
        // if(isMobile()){
            
           
        //     if(stripos($domain,'/Pc/') !== false)
        //     {
        //         $domain=str_ireplace('/Pc/','/Home/',$domain);
        //          echo "<script>location.href='".$domain."';</script>";
        //          die();
        //     }
        // }
        // else
        // {
           
        //     if(stripos($domain,'/Home/') !== false)
        //     {
              
        //         $domain=str_ireplace('/Home/','/Pc/',$domain);
              
        //         echo '<script>location.href="'.$domain.'";</script>';
        //         die();
                
        //     }
        //  }
		//$this->Api();
       	if(session('id')!=""){
	        $D=M('ad');
	        $data=$D->order('id asc')->select();
	        $this->assign('room',$data);
	        $T=M('setting');
	        $tdata=$T->where('id=1')->find();
	        
	        //系统公告
			$xtgg = M('type')->order('typeid desc')->select();
			$this->assign('xtgg',$xtgg);
			
			//查询积分余额
			$jfy = M('integral')->where("userid=".session('id'))->find();
			$this->assign('jfy',$jfy);	
			
			//奖金总数
			$D=M('integral');
    		$ppd=$D->where('userid='.session('id'))->find();
      
            $I=M('income');
            $ppd=$I->field('sum(price) as total')->where('userid='.session('id').' and status=1 and (gotegary=4 or gotegary=1)')->select();

            $ppdtotal=$ppd[0]['total'];
            
            $ppdtotal=number_format($ppdtotal, 2, '.', '');//保留两位小数
            $this->assign("ppdtotal",$ppdtotal);
            
            $bbe = time();
    		$tid = M('type')->field('count(1) as total')->where($bbe.'-createtime<24*3600')->select();
    			
    		$this->assign('rew',$tid[0]['total']);
            
            
//	        if($tdata['web_config']==1)
//	        {
//	            echo'系统正在升级中，请稍候再访问，为此对您造成不便，敬请原谅！';
//	            die();
//	        }
	
	      	$this->ad();
	        //分类
			$this->panduan();

	 		//判断是否出局
		    //$this->treeout();	
	       		
       	}else{
        	echo"<script>location.href='/pc/index/login.html';</script>";
            die();
      	}
      	$this->display();	
    }
	
    private function Createorderid($t)
    {
        $orderid= date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(1000,9999);
        //1充值　２订单支付　３注册支付
        $orderid=$t.$orderid;
        return $orderid;

    }
    public function ddgl()
     {
		if($_SESSION['id']){
			$p=M('order');
			if($_SESSION['id']){
				$order = $p->where("userid={$_SESSION['id']}")->select();
			}
	//		$asp = M('product');
	//		$ppp=$asp->where("id")->select();
	//		$this->assign('ppp',$ppp);
			$this->assign('roomoom',$order);
			$this->display();
		}else{
			echo"<script>location.href='/pc/index/login.html';</script>";
		}
     }
    public function product()
    {
    	if(!empty($_SESSION['id'])){
//  		 echo('<script>location.href="/pc/index/login.html"</script>');
    	}
        $t=$_REQUEST['t'];
        $c1=$_REQUEST['c1'];
        $c2=$_REQUEST['c2'];
        $c3=$_REQUEST['c3'];

        $search='';

        if(!empty($mobile))
            $search.='&t='.$t;
        if(!empty($c1))
        {
            $search.='&c1='.$c1;
        }
        else
        {
            $c1=0;
        }

        if(!empty($c2))
        {
            $search.='&c2='.$c2;
        }
        else
        {
            $c2=0;
        }
        if(!empty($c3))
        {
            $search.='&c3='.$c3;
        }
        else{
              $c3=0;
        }
        $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =12;

        $sqlw='1=1 and ("'.$t.'"="" or productname like "%'.$t.'%")';

        if(!empty($c3))
        {
           $sqlw.=' and c3='.$c3;
        }
        else
        { 
             if(!empty($c2))
             {
                 $sqlw.=' and c2='.$c2;
             }
             else
             {
                if(!empty($c1))
                 {
                     $sqlw.=' and c2='.$c1;
                 }
             }
        } 
        $room = M('product');
        switch($other)
        {
            case '0':
              $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
              break;
            case '1':
              $info=$room->order(" price desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
              break;
            case '2':
              $info=$room->order(" price asc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
              break;
        }
        
        $sql = "select count(*) as total from tm_product where ".$sqlw;
		

        $query = $room->query($sql);
        
		

        if($other==1)
        {
            $other=2;
        }
        else
        {
            if($other==2)
            {
                $other=1;
            }
        }
        $this->assign('o',$other);


        $pageurl="/?";

        $count = $query[0]['total'];

        $this->assign("product",$info);
 
        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);


        $jr = M('product')->order(" id desc")->where("syqf=1")->limit(6)->select();
        $jx = M('product')->order(" id desc")->where("syqf=2")->limit(6)->select();
        $this->assign("jr",$jr);        //今日推荐
        $this->assign("jx",$jx);        //精选优车
        $this->assign("page",$page);
        $this->assign("search",$search);
        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);


        $this->assign('t',$t);



    }


     //取得分类
     public function cotegary()
     {
        $D=M('cotegary');
        $data=$D->where('parentid=0')->select();
        $num=count($data);
        for($i=0;$i<$num;$i++)
        {
            $data1=$D->where('parentid='.$data[$i]['id'])->select();

            $num1=count($data1);
              
              for($j=0;$j<$num1;$j++)
              {
                $data2=$D->where('parentid='.$data1[$j]['id'])->select();
                $data1[$j]['sub']=$data2;
              }



              $data[$i]['sub']=$data1;
        }

    
        $this->assign('cotegary',$data);

     }
     public function login()
     {
	   
        $domain=$_SERVER['REQUEST_URI'];
    
        // if(isMobile()){
        //       if($domain!='/Home/index/login.html')
        //          echo "<script>location.href='/Home/index/login.html';</script>";
        // }else{
        //      if($domain!='/Pc/index/login.html')
        //          echo "<script>location.href='/Pc/index/login.html';</script>";
        // }
          $this->display();
     }
	 public function dologin()
    {
    	header("Content-Type: text/html;charset=utf-8");
        $username = I('post.username');
        $mobile_code = I('post.mobile_code');
        $password = md5(I('post.password'));
        $code = I('post.verify');
		
		
        if(!$this->check_verify($code, ''))
        {
            echo"<script>alert('输入的验证码有误，请重新输入！');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
            die();
        }
        else
        {
            session(null);
        }
        
        if(!empty($username)){
            $data['username'] = $username;
            $admin = M('user');
            $iofo = $admin->where("username='".$data['username']."'")->find();
            if(!empty($iofo))
            {
               
                if($iofo['status']==0)
                {
                    echo"<script>alert('帐户未激活，请联系报单中心激活！');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
                    die();
                }
                if($this->isout($iofo['id'])==0)
                {
                    echo"<script>alert('帐户已经出局！');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
                    die();
                }
                if($this->_checkPwd($password,$iofo['password']))
                {
                session('id',$iofo['id']);
                session('username',$iofo['username']);
                session('realname',$iofo['realname']);
                session('logintime',time());
                redirect('/pc/index/index.html');
                }
                else
                {
                    echo"<script>alert('用户密码有误，请重新输入');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
                    die();
                }
            }
            else
            {
                echo"<script>alert('用户帐户有误，请重新输入');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
                die();
            }
            exit;
        }else{

            echo"<script>alert('会员帐户为空，请重新输入');location.href='/pc/index/login?u=".$username."&v=".$code."'</script>";
            die();
        }
    }
 public function dologinajax(){


        $username=I('post.username');
        $password=I('post.password');


        
        //检测验证码
        $code = I('post.verify');
        if(!$this->check_verify($code, ''))
        {

            echo 5;
            die();
        }
        else
        {
            session(null);
        }

        if(!empty($username)){

            $data['username'] = $username;
            $password = md5($password);
          
            $admin = M('user');
            $info = $admin->where("username='".$data['username']."' or mobile='".$username."'")->find();

            if(!$this->_checkPwd($password,$info['password']))
            {
                  echo 2;
                  die();
            }


            if(!empty($info))
            {
                if($info['status']==3)
                {
                    echo 3;
                    die();
                }
                if($info['status']==0)
                {
                    echo 4;
                    die();

                }

                session('userid',$info['id']);
                session('username',$info['username']);
                session('status',$info['status']);//0  未开通，１已开通　２休眠　３冻结　
                session('logintime',time());//登录时间
                session('category',$info['gotegary']);//0  未开通，１已开通　２休眠　３冻结　
                
                
                echo 1;
                die();

            }
            else
            {

                echo 2;
                die();
            }
            exit;
        }else{

        }
        	echo 1;
        	die();
    }
      public function verify(){
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->fontSize = 16;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
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


     public function reg()
    {
        header("Content-Type: text/html;charset=utf-8");
        $tusername=I('get.id');

        $D=M('user');
        $data=$D->field('tusername')->where('username="'.$tusername.'"')->find();
        if(empty($data))
        {
            echo("<script>alert('推荐人不存在！请输入正确的网址！');</script>");
            die();
        }


        $this->assign('tusername',$tusername);

        $this->display();
    }
    public function doreg()
    {


        $username = I('post.username');
        $password = md5(I('post.password'));
        $paypassword = md5(I('post.paypassword'));
        $password=$this->md5pw($password);
        $paypassword =$this->md5pw($paypassword);


        $tusername = I('post.tusername');
       
        $mobile = I('post.mobile');
        


        $D = M('user');

        $createtime = time();


        //取得推荐人ＩＤ
        $data1 = $D->where('username="' . $username . '"')->find();
        if (!empty($data1)) {
            echo 2;//用户名已经存在，
            die();
        }

        $pdata = $D->where('username="' . $tusername . '"')->find();
        if (empty($pdata)) {
            echo 3;//推荐人不存在，
            die();
        }
        //判断手机号码
        $data2 = $D->where('mobile="' . $mobile . '"')->find();
        if (!empty($data2)) {
            echo 4;//手机号码已经存在，
            die();
        }



        // //判断手机号码
        // $data3 = $D->where('paper="' . $paper . '"')->find();
        // if (!empty($data3)) {
        //     echo 6;//手机号码已经存在，
        //     die();
        // }

        // if(!empty($alipay))
        // {
        // $data4 = $D->where('alipay="' . $alipay . '"')->find();
        // if (!empty($data4)) {
        //     echo 7;//支付宝已经存在，
        //     die();
        // }
        // }
        

        // //输入验证码不正确
        // if($mobile_code!=session('mobile_code'))
        // {
        //     echo 5;//
        //     die();
        // }


        $arr=array(
            'username'=>$username,
            'password'=>$password,
            'paypassword'=>$paypassword,
            'tusername'=>$tusername,
            'mobile'=>$mobile,
            'createtime'=>$createtime,
            'gotegary'=>0,
            'status'=>1,
           

        );



        $id=$D->add($arr);//添加会员
        $path=$pdata['path'].$id.',';
        $number=$this->donumber($id);
        $arr=array('path'=>$path,
            'number'=>$number,
        );
        $D->where('id='.$id)->save($arr);


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
        echo 1;
        die();


    }
    //生成会员编号
    private function donumber($id)
    {

        $number='A'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }





    public function ad()
    {


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
    //商品详情
    public function view()
    {
         header("Content-Type: text/html;charset=utf-8");
        $id=I('get.id');
        if(!is_numeric($id))
        {
              echo('<script>alert("无效参数");location.href="/"</script>');
              die();
        }


    $this->ad();


        $P=M('product');
        $product=$P->where('id='.$id)->find();
        $this->assign('product',$product);
        $this->display();

    }

    public function order()
    {
        header("Content-Type: text/html;charset=utf-8");
        if(session('userid')=='')
        {
            echo "<script>alert('请先登录');location.href='/login.html'</script>";
            die();
        }

        // $num=I('get.num');
        // $id=I('get.id');

    }

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




        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_news where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/news.html?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);

        $this->assign("page",$page);


        $this->assign("search",$search);

        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);


        $this->assign('t',$t);

        $this->ad();


        $this->display();

    }
    public function news_view()
    {
        $id=I('get.id');
        if(empty($id))
        {
            echo"<script>alert('没有相应的公告！');history.go(-1);</script>";
            die();
        }
        $D=M('news');
        $room=$D->where('id='.$id)->find();
        $this->ad();
        $this->assign('room',$room);
        $this->display();

    }
	//留言反馈
    public function domessage()
    {
         header("Content-Type: text/html;charset=utf-8");
        $title=I('post.title');
        $content=I('post.content');
        $createtime=time();
        $userid=session('userid');
        $D=M('message');
        $arr=array('title'=>$title,
            'content'=>$content,
            'createtime'=>$createtime,
            'userid'=>$userid);
        $D->add($arr);
        echo"<script>alert('留言反馈添加成功！');location.href='/message.html';</script>";
        die();
    }

    public function message()
    {
        $this->ad();
        $this->display();
    }
    public function join()
    {
        $this->ad();
        $this->display();
    }
	
	public function help()
    {
		$D=M('type');
		$data1=$D->where('fid=1')->select();
		$data2=$D->where('fid=2')->select();
		$data3=$D->where('fid=3')->select();
		$data4=$D->where('fid=4')->select();
		$this->assign('data1',$data1);
		$this->assign('data2',$data2);
		$this->assign('data3',$data3);
		$this->assign('data4',$data4);

//		$data1=
    }
     public function help_view()
    {
        $id=I('get.id');
        if(empty($id))
        {
            echo"<script>alert('没有相应的信息！');history.go(-1);</script>";
            die();
        }
        $D=M('type');
        $room=$D->where('typeid='.$id)->find();
        
        $this->assign('room',$room);
        $this ->display();
    }
    //注册
    public function registered()
    {
    	$aq = M('user')->where('id='.session('id'))->find();
        $D=M('user');
        $data=$D->order('id desc')->limit(1)->find();

        $hhh=rand(100000,999999);

    	$this->assign('aq',$aq);
        $this->assign('hhh',$hhh);
        $this->display();
    }
    public function doregistered()
    {		
	        $username = I('post.username');
			$username = "MJ".$username;
            $password = I('post.password');
            $paypassword = md5(I('post.paypassword'));
            $password=$this->md5pw(md5($password));
            $paypassword=$this->md5pw($paypassword);
            $tusername = I('post.tusername');
            
            $fwzx = I('post.fwzx');
            $realname = I('post.realname');
            $mobile = I('post.mobile');
            $paper = I('post.paper');
            $createtime = time();
            
            $aq = M('user')->where('id='.session('id'))->find();


    		$this->assign('aq',$aq);
	        //判断支付密码是否正确
	        $D = M('user');

	        $data1 = $D->where('username="' . $username . '"')->find();
	        if (!empty($data1)) {
                $arr['flag']=2;
                $arr['id']='';
                $this->ajaxReturn($arr);
	            die();
	        }
	        $data2 = $D->where('paper="' . $paper . '"')->find();
	        if(!empty($data2)){
	        	$arr['flag']=6;
                $arr['id']='';
                $this->ajaxReturn($arr);
                die();

	        }
			//取得推荐人ＩＤ
	        $pdata = $D->where('username="' . $tusername . '"')->find();	//获取推荐人

//          if($pdata['tohelp']==1){
//              echo 18;
//              die;
//          }
            if($tusername!=""){
	        	if(!empty($pdata)){
	        	
	        }else{
	        	$arr['flag']=3;
                $arr['id']='';
                $this->ajaxReturn($arr);
                die();
	        	}
	        }
	       	if($aq['tohelp'] !=1){
	       		$fwzx1 = $D->where('username="' . $fwzx . '"')->find();	//获取服务中心
		        if($fwzx1['tohelp'] != 1){
		        	$arr['flag']=10;
                    $arr['id']='';
                    $this->ajaxReturn($arr);
                    die();
		        }
	       	}
	        
	        $res = rand(1,9999);
	        // //判断手机号码
	        // $data2 = $D->where('mobile="' . $mobile . '"')->find();
	        // if (!empty($data2)) {
	        //     $arr['flag']=4;
         //        $arr['id']='';
         //        $this->ajaxReturn($arr);
         //        die();
	        // }
	        //判断身份证号码
	        $I=M('integral');
	        $rbc=$I->where("userid=".session('id'))->find();
	        $user = M('user')->where('id='.session(id))->find();
        	if($user['tohelp']==1){
        		if($rbc['integral2'] >= 10000)
        		{
	    	        $arr=array(
	    	            'username'=>$username,
	    	            'password'=>$password,
	    	            'paypassword'=>$paypassword,
	    	            'tusername'=>$tusername,
	    	            'realname'=>$realname,
	    	            'mobile'=>$mobile,
	    	            'gotegary'=>0,
	    	            'status'=>0,
	    	            'paper'=>$paper,
	    	            'rounds'=>0,
	    	            'createtime'=>$createtime,
                        'fwzx'=>session('username'),
	    	        );
		           	$id = $D->add($arr);//插入用户 

					//加入记录表
          			//$T = M('tree');
		         
		                //取推荐人的number
		            //$tu=$T->where('userid='.$pdata['id'].' and `out`=0')->order('id desc')->limit(1)->find();

                    
		            //分配新增用户的number
                    //$this->addtree($id,$tu['number']);

                    
			        
				  
	    		   if($pdata['path']===0){
                      $path='0,'.$id.',';
	               }else{
                      $path=$pdata['path'].$id.',';
	               }
                   
	    	       $arr=array('path'=>$path);
	    	       $D->where('id='.$id)->save($arr);

	    	       $bbq = $D->field('path')->where('id='.$id)->find();
	    	       $num = substr_count($bbq['path'],',');
	    		   $acc = $D->field('number')->where('id='.$id)->find();
		        
	    	        $arri=array(
	    	            'userid'=>$id,
	    	            'integral1'=>0,
	    	            'integral2'=>0,
	    	            'integral3'=>0,
	    	            'integral4'=>0,
	    	            'integral5'=>0,
	    	        );
	    	        $I->add($arri);//加入积分表

                    $arr['flag']=11;
                    $arr['id']=$id;
                    $this->ajaxReturn($arr);
                    die();
			        //判断位置
			        
					// $reb=$rbc['integral2']-10000;
					// $updata = array('integral2'=>$reb);
				 //    M('integral')->where("userid={$_SESSION['id']}")->save($updata);
				 //    $arr = array(
				 //    		'userid'=>$_SESSION['id'],
		   //                  'username'=>$_SESSION['username'],
				 //    		'title'=>'注册'.$username.'会员，消耗注册积分',
				 //    		'price'=>10000,
				 //    		'createtime'=>$createtime,
				 //    		'status'=>3,
				 //    		'gotegary'=>2,
				 //    		'user'=>$username
				 //    		);
				 //    M('income')->add($arr);
				    
				 //    $bbq = M('integral')->where("userid={$_SESSION['id']}")->find();
				 //    $ccq = $bbq['integral1']+500;
		   //          $updata2 = array('integral1'=>$ccq);
				 //    $bb = $I->where("userid={$_SESSION['id']}")->save($updata2);
				 //    $arr = array(
				 //    		'userid'=>$_SESSION['id'],
		   //                  'username'=>$_SESSION['username'],
				 //    		'title'=>'注册'.$username.'会员，奖励推广积分',
				 //    		'price'=>500,
				 //    		'createtime'=>$createtime,
				 //    		'status'=>1,
				 //    		'gotegary'=>1
				 //    		);
				 //    M('income')->add($arr);
		        	
		   //      	$eq = M('integral')->where('userid='.$pdata['id'])->find();
		   //      	$bc = $eq['integral3']+2000;
		   //      	$ge = array('integral3'=>$bc);
		   //      	$I->where("userid=".$pdata['id'])->save($ge);
		   //      	$arra = array(
		   //                  'userid'=>$pdata['id'],
		   //                  'username'=>$pdata['username'],
		   //                  'title'=>'推荐'.$username.'会员，奖励推广积分',
		   //                  'price'=>2000,
		   //                  'createtime'=>$createtime,
		   //                  'status'=>1,
		   //                  'gotegary'=>1
		   //                  );
		   //          M('income')->add($arra);
				}else{
					$arr['flag']=9;
                    $arr['id']='';
                    $this->ajaxReturn($arr);
                    die();
				}		
			}
        		if($user['tohelp']==0){
	    	        $arr=array(
	    	            'username'=>$username,
	    	            'password'=>$password,
	    	            'paypassword'=>$paypassword,
	    	            'tusername'=>$tusername,
	    	            'fwzx'=>$fwzx,
	    	            'realname'=>$realname,
	    	            'mobile'=>$mobile,
	    	            'gotegary'=>0,
	    	            'status'=>0,
	    	            'rounds'=>0,
	    	            'paper'=>$paper,
	    	            'createtime'=>$createtime,
                        
	    	        );
		           $id = $D->add($arr);//插入用户
		           
			       $data=$D->where('username="'.$tusername.'"')->find();

			       $this->userall($pdata['username']);

	    		   if($data['path']===0){
	                    $path='0,'.$id.',';
	               }else{
	                    $path=$data['path'].$id.',';
	               }
	    	       $arr=array('path'=>$path);
	    	       $D->where('id='.$id)->save($arr);
	    	       $bbq = $D->field('path')->where('id='.$id)->find();
	    	       $num = substr_count($bbq['path'],',');
	    		   $acc = $D->field('number')->where('id='.$id)->find();
				
		        
		        
	    	        $arri=array(
	    	            'userid'=>$id,
	    	            'integral1'=>0,
	    	            'integral2'=>0,
	    	            'integral3'=>0,
	    	            'integral4'=>0,
	    	            'integral5'=>0,
	    	        );
	    	        
	    	        $I->add($arri);//加入积分表
//	    	       	$eq = M('integral')->where('userid='.$pdata['id'])->find();
//	    	        $bc = $eq['integral3']+2000;
//		        	$ge = array('integral3'=>$bc);
//		        	$I->where("userid=".$pdata['id'])->save($ge);
//		        	$arra = array(
//		                    'userid'=>$pdata['id'],
//		                    'username'=>$pdata['username'],
//		                    'title'=>'推荐'.$username.'会员，奖励用户积分',
//		                    'price'=>2000,
//		                    'createtime'=>$createtime,
//		                    'status'=>1,
//		                    'gotegary'=>4
//		                    );
//		            M('income')->add($arra);

        		}
		       $arr['flag']=1;
               $arr['id']='';
               $this->ajaxReturn($arr);
               die();
}
    
    //登陆
	 //极差奖
    public function userall($username)
    {
        $D=M('user');
        $room=$D->where('username="'.$username.'"')->find();
        $P=M('parameter');
        $pdata=$P->order('id asc')->select();

        $userid=$room['id'];
        $data=$D->field('count(1) as total')->where('locate(",'.$userid.',",path)>0')->select();
        if($data[0]['total']>=intval($pdata[0]['number']/10000)&&$data[0]['total']<intval($pdata[2]['number']/10000))
        {
            $price=10000*$pdata[1]['number'];
        }elseif($data[0]['total']>=intval($pdata[2]['number']/10000)&&$data[0]['total']<intval($pdata[4]['number']/10000))
        {
            $price=10000*$pdata[3]['number'];
        }elseif($data[0]['total']>=intval($pdata[4]['number']/10000)&&$data[0]['total']<intval($pdata[6]['number']/10000))
        {
            $price=10000*$pdata[5]['number'];
        }elseif($data[0]['total']>=intval($pdata[6]['number']/10000)&&$data[0]['total']<intval($pdata[8]['number']/10000))
        {
            $price=10000*$pdata[7]['number'];
        }elseif($data[0]['total']>=intval($pdata[8]['number']/10000)&&$data[0]['total']<intval($pdata[10]['number']/10000))
        {
            $price=10000*$pdata[9]['number'];
        }elseif($data[0]['total']>=intval($pdata[10]['number']/10000)&&$data[0]['total']<intval($pdata[12]['number']/10000))
        {
            $price=10000*$pdata[11]['number'];
        }elseif($data[0]['total']>=intval($pdata[12]['number']/10000)&&$data[0]['total']<intval($pdata[14]['number']/10000))
        {
            $price=10000*$pdata[13]['number'];
        }elseif($data[0]['total']>=intval($pdata[14]['number']/10000)&&$data[0]['total']<intval($pdata[16]['number']/10000))
        {
            $price=10000*$pdata[15]['number'];
        }elseif($data[0]['total']>=intval($pdata[16]['number']/10000))
        {
            $price=10000*$pdata[17]['number'];
            
        }
        if($data[0]['total']>=intval($pdata[0]['number']/10000))
        {
            $I=M('integral');
            $I->where('userid='.$userid)->setinc('integral1',$price);
            $title='会员'.$username.'极差奖';
            $createtime=time();
            $this->Pay_history($userid,$title,$price,$createtime,1,1);
        }    
        if(!empty($room['tusername']))
            $this->userall($room['tusername']);

    }
   
  
    
    //激活账号
   public function activation()
   {
   	$this->display();
   }
   
   
   //资金提现
   public function jjtx()
   {
    $zj = M('integral')->where("userid={$_SESSION['id']}")->find();
    $this->assign('zj',$zj);
   	$this->display();
   }
   public function dojjtx()
   {
       

            $price = I('post.price');
            $pay = I('post.pay');
            $phone = I('post.phone');
            $alipay = I('post.alipay');

            $zhanghao = I('post.zhanghao');
            $bankaddress = I('post.bankaddress');
            $bank = I('post.bank');
            $password = md5(I('post.password'));
            $createtime = time();
            $admin=M('user');
       		$info = $admin->where("id={$_SESSION['id']}")->find();
       		

       		if($this->_checkPwd($password,$info['password'])){
                    
            }else{
                echo"<script>alert('用户密码有误，请重新输入');location.href='/pc/index/jjtx.html'</script>";
                 die();
            }
       		if($pay==0){
                $arr = array(
                        'pay'=>$alipay,
                        'price'=>$price,
                        'phone'=>$phone,
                        'status'=>0,
                        'createtime'=>$createtime,
                        'userid'=>$_SESSION['id']
                );
                M('tx')->where("userid={$_SESSION['id']}")->add($arr);
                //扣费
                $kf = M('integral')->where("userid={$_SESSION['id']}")->find();
                $txfy = $kf['integral1']-$price;
                $arr1 = array(
                        'integral1'=>$txfy
                    );
                M('integral')->where("userid={$_SESSION['id']}")->save($arr1);
                $arr = array(
                    'userid'=>$_SESSION['id'],
                    'username'=>$_SESSION['username'],
                    'title'=>$_SESSION['username'].'会员，提现',
                    'price'=>$price,
                    'createtime'=>$createtime,
                    'status'=>0,
                    'gotegary'=>1
                    );
                M('income')->add($arr);
                echo"<script>alert('提现申请成功！');location.href='/pc/index/index.html'</script>";
            }elseif($pay==1){
                $arr = array(
                        'price'=>$price,
                        'zhanghao'=>$zhanghao,
                        'bank'=>$bank,
                        'phone'=>$phone,
                        'status'=>0,
                        'bankaddress'=>$bankaddress,
                        'userid'=>$_SESSION['id']
                    );
                M('tx')->where("userid={$_SESSION['id']}")->add($arr);
                //扣费
                $kf = M('integral')->where("userid={$_SESSION['id']}")->find();
                $txfy = $kf['integral1']-$price;
                $arr1 = array(
                        'integral1'=>$txfy
                    );
                M('integral')->where("userid={$_SESSION['id']}")->save($arr1);
                $arr = array(
                    'userid'=>$_SESSION['id'],
                    'username'=>$_SESSION['username'],
                    'title'=>$_SESSION['username'].'会员，提现',
                    'price'=>$price,
                    'createtime'=>$createtime,
                    'status'=>0,
                    'gotegary'=>1
                    );
                M('income')->add($arr);
                 echo"<script>alert('提现申请成功！');location.href='/pc/index/index.html'</script>";
            }

   }
   
   
   
   
	
	//提交订单
	public function tjdd()
	{
		if(!$_SESSION['id']){
			echo "<script>alert('登录后购车！');location.href='/pc/index/login.html';</script>";
		}
		$id=I('get.id');
		$P=M('product');
//		$product=$P->where('id='.$id and 'userid='.$_SESSION['id'])->find();
		$product=$P->where('id='.$id)->find();
		$this->assign('product',$product);
		$this->display();
		
	}
	public function dotjdd()
	{
		
//		dump($_POST);die;
		header("Content-Type: text/html;charset=utf-8");
		$id=I('post.id');
        $ci=I('post.city');			//购车城市
        
        $c1 = I('post.c1');
        $c2 = I('post.c2');
        $c3 = I('post.c3');
        $di1 = I('post.di1');
        $di2 = I('post.di2');
        $di3 = I('post.di3');
        
        $di=I('post.dist');			//上牌城市
        $cr=I('post.cr');			//颜色
        $price=I('post.price');
        $pic=I('post.pic');
        
        $gc_fs=I('post.gc_fs');
		$remark=I('post.remark');	//备注
        $number=I('post.number');

//      $pay1=I('post.pay1');
//      $pay2=I('post.pay2');
        $userid=session('userid');
        $P=M('product');
        $pdata=$P->where('id='.$id)->find();
        $o=M('order');
        $odata=$o->where("userid={$_SESSION['id']}")->find();
        $U=M('user');
        $udata=$U->where("id={$_SESSION['id']}")->find();
        $I=M('integral');
        $idata=$I->where("userid={$_SESSION['id']}")->find();
        //$A=M('user_address');
        //$adata=$A->where('id='.$address)->find();


        $createtime=time();

        $orderid=$this->Createorderid(2);//商品订单

        $arr66 = array('di1'=>$di1,'di2'=>$di2,'di3'=>$di3);
        //$data = json_decode($ret['dist']);			数据解码
        $ress = json_encode($arr66);
        $arr=array(
            'userid'=>$_SESSION['id'],
            'orderid'=>$orderid,
            'productname'=>$pdata['productname'],
            'price'=>$pdata['price'],
            'number'=>$number,
            'createtime'=>$createtime,
            'status'=>2,
            'province'=>$pr,
            'city'=>$ci,
            'dist'=>$di,
            'gc_fs'=>$gc_fs,
            'remark'=>$remark,
            'cr'=>$cr,
            'c1'=>$c1,
            'c2'=>$c2,
            'c3'=>$c3,
            'dist'=>$ress
            
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
             'province'=>$pr,
            'city'=>$ci,
            'dist'=>$di,
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


//             if($idata['integral1']<$pdata['price']*$number)
//              {
//                  echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
//                  die();
//              }
//
//              if($idata['integral5']<$pdata['ticket']*$number)
//              {
//                  echo"<script>alert('券余额不足，请充值！');history.go(-1);</script>";
//                  die();
//              }
//
//         
//
//               $I->where('userid='.$userid)->setdec('integral1',$pdata['price']*$number);
//               $sarr=array('status'=>2);//改变支付状态
//               $O->where('id='.$id)->save($sarr);
//               $title='购买商品';
//               $this->Pay_history($userid,$title,$pdata['price']*$number,$createtime,0,1);//支出记录;
//               $I->where('userid='.$userid)->setinc('integral5',$pdata['ticket']*$number);
//                $title='购买商品用券';
//               $this->Pay_history($userid,$title,$pdata['ticket']*$number,$createtime,0,5);//支出记录;
//    


     
        echo"<script>alert('订单提交成功，请等候发货！');location.href='".U('zf',array('id'=>$id))."';</script>";
        die();
	}
	
	//详情
	public function xq()
	{
		
        header("Content-Type: text/html;charset=utf-8");
        $id=I('get.id');
        if(!is_numeric($id))
        {
              echo('<script>alert("无效参数");location.href="/"</script>');
              die();
        }
		

        $P=M('product');
        $product=$P->where('id='.$id)->find();
        
        $this->assign('product',$product);
        $this->display();
	}
	
	public function search()
	{
		$keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';


        if(!empty($keyword))
        {
            $search='_k='.$keyword;
        }

        if(!empty($begintime))
        {

            $search='_b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='_e='.$endtime;
            $endtime=strtotime($endtime);
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

        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;

        $sqlw.=' and (number like "%'.$keyword.'%" or productname like "%'.$keyword.'%")';


        $room = M('product');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        //$memberid=session('userid');




        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_product where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/product.html?";


        $count = $query[0]['total'];

        $this->assign("roombbb",$info);


        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);

        $this->assign("page",$page);



        $o= 'page='.$page.$search;
        $this->assign("o",$o);


        $search= str_replace('_','&',$search);

        $this->assign("search",$search);

        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);


        $this->assign('keyword',$keyword);
        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);

        //$this->assign('t',$t);
    }
    //退出
    public function logout()
    {
    	session(null);
        echo"<script>location.href='/pc/index/login.html'</script>";
        die();
    }
    
    //购物
    public function shopcar()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$keyword=I('post.k');
        $search='';


        if(!empty($keyword))
        {
            $search='_k='.$keyword;
        }

        $sqlw='1=1 ';
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;




        $sqlw.=' and (number like "%'.$keyword.'%" or productname like "%'.$keyword.'%")';
		

        $room = M('product');
   



        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
		if($info){
			
		}else{
			echo "抱歉。您输入的词汇有误，请正确输入，比如宝马";
//			$pp = $room->where()->limit(0,5)->select();
		}
        $sql = "select count(*) as total from tm_product where ".$sqlw;


        $query = $room->query($sql);


        $pageurl="/pc/index/shopcar.html?";


        $count = $query[0]['total'];

        $this->assign("roombbb",$info);
		
        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);

        $this->assign("page",$page);

        $o= 'page='.$page.$search;
        $this->assign("o",$o);
        $search= str_replace('_','&',$search);
        $as = M('product');
        $price = $as->where()->order('price desc')->select();
        $abs = $as->where()->order('price asc')->select();
        $this->assign('abs',$abs);
		$this->assign('price',$price);
        $this->assign("search",$search);
        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);
        $this->assign('keyword',$keyword);
        $this->display();
    }
    //支付
    public function zf()
    {
    	$id =  I("get.id");
    	$D=M('Order');
      	$order = $D->alias("o")->join("__PRODUCT__ as p on p.number=o.number")->where("o.userid={$_SESSION['id']} and p.id={$id}")->find();
		//dump($order);exit;
    	$this->assign('order',$order);
    	$this->display();
    }
    public function dozf()
    {
    	echo"<script>alert('程序猿正在努力开发！');location.href='/pc/index/index.html';</script>";
    }
    
     private function GetUser()
    {
        $userid=$_SESSION['id'];
        $D=M('user');
        $data=$D->field('number')->where('id='.$userid)->find();
        $num=$data['number'];

        $arr=array();
        if($num==1)
        {
            for($i=0;$i<7;$i++)
            {
                $arr[$i]=$i+1;
            }
        }
        else{
        	$arr[0]=$num;
           //第一层
            $arr[1]=$num*2;
            $arr[2]=$num*2+1;
            
            //第二层
            $arr[3]=$arr[1]*2;
            $arr[4]=$arr[1]*2+1;

            $arr[5]=$arr[2]*2;
            $arr[6]=$arr[2]*2+1;
           
        }
        
        return $arr;
    }
	
	//忘记密码
	 public function szmm()
	 {
	 	$this->display();
	 }
	 
	 public function doszmm()
	 {
	 	$username = I('post.username');
	 	$paper = I('post.paper');
	 	$newpassword = I('post.newpassword');
	 	$newpassword=$this->md5pw(md5($newpassword));
	 	
	 	$user = M('user')->where('username="'.$username.'"')->find();
	 	if(empty($user)){
	 		echo "<script>alert('您输入的会员帐号不存在！');location.href='/pc/index/szmm.html'</script>";
	 	}
	 	$pap = M('user')->where('paper="'.$paper.'"')->find();
	 	if(empty($pap)){
	 		echo "<script>alert('抱歉，身份证号码不正确！');location.href='/pc/index/szmm.html'</script>";
	 	}
	 	$update = array('password'=>$newpassword,'id'=>$user['id']);
	 	M('user')->save($update);
	 	echo "<script>alert('密码修改成功！');location.href='/pc/index/login.html'</script>";
	 }
	 
	 //判断五轮是否有推荐人，没有的话就出局
	public function panduan()
	{
		$samsara = M('samsara');
		$user = M('user');
    	$RGP=$samsara->where("userid={$_SESSION['id']}")->find();
    	if($RGP!=""){
    		if($RGP['samsara']==5){
			$rdc = D('User')->alias('t')->join("LEFT Join tm_user as m on t.tusername=m.username")->where("m.id={$_SESSION['id']}")->count();
			if(empty($rdc)){
				$samsara->where("userid={$_SESSION['id']}")->delete($RGP);
				$update = array('number'=>0,'status'=>0);
				$user->where("id={$_SESSION['id']}")->save($update);
				echo "<script>alert('您好，您在五局游戏中均无推荐人，无法继续游戏！谢谢')</script>";
				}
			}
    	}
	}
     //判断是否满５轮
    public function isout($userid)
    {
        
        $D=M('user');
        $room=$D->where('id='.$userid)->find();
        
        if($room['rounds']==5)
        {
            $data=$D->field('count(1) as total')->where('locate(",'.$userid.',",path)>0 and id!='.$userid)->select();

            
            if(empty($data[0]['total']))
            {
               return 0;  
            }
        }

        return 1;
    }

     public function changepath()
     {
         $D=M('user');
         $data=$D->order('id asc')->select();
         foreach($data as $vo)
         {   
             
             if($vo['tusername']!='')
             {
                $data1=$D->field('path')->where('username="'.$vo['tusername'].'"')->find();
                $arr=array('path'=>$data1['path'].$vo['id'].',');
                $D->where('id='.$vo['id'])->save($arr);
                echo $vo['id'].'<br>';
             }
         }
     }
	 
}




