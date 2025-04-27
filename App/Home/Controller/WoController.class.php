<?php
namespace Home\Controller;
use Think\Controller;
class WoController extends \Common\Controller\DemoController{
    public function wo()
    {
    	header("Content-Type: text/html;charset=utf-8");

    	
    	//$this->Api();
    	
        //判断是否是新密码
        $userid=session('id');
        $password='111111';
        $U=M('user');
        $udata=$U->where('id='.$userid)->find();
        if($this->_checkPwd($password,$udata['password']))
        {
    
                    echo"<script>alert('请先修改登录密码及交易密码！');location.href='/pc/wo/mmxg';</script>";
                    die();
        }


    	if(session('id')!=""){
    		$D=M('integral');
    		$integral=$D->where('userid='.session('id'))->find();
    		$this->assign('integral',$integral);

    		
    		$I=M('income');
            $ppd=$I->field('sum(price) as total')->where('userid='.session('id').' and status=1 and (gotegary=4 or gotegary=1)')->select();

            $ppdtotal=$ppd[0]['total'];
            
            $ppdtotal=number_format($ppdtotal, 2, '.', '');//保留两位小数
            $this->assign("ppdtotal",$ppdtotal);
    		
    		$bbe = time();
    		$tid = M('type')->field('count(1) as total')->where($bbe.'-createtime<24*3600')->select();
    			
    		$this->assign('rew',$tid[0]['total']);
    		
    		//反馈消息
    		$qqc = M('message');
    		$id = $_SESSION['id'];
    		$res=M('message')->where('status=2 and userid='.$id)->count();
			$this->assign('res',$res);
    		//系统公告
    		$xtgg = M('type')->order('typeid desc')->select();
    		$this->assign('xtgg',$xtgg);
    		$this->assign('ppd',$ppd);
    		
    		$ret = D('user')->where("id={$_SESSION['id']}")->find();
      }else{
        echo"<script>location.href='/Home/index/login.html';</script>";
        die();
      }
      
    //dump($ret);die;
    	
    	$this->assign('ret',$ret);
    	
		$this->panduan();



        //判断是否出局
        //$this->treeout();


		$this->display();
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
    
    
    //银行信息
   public function yhxx()
   {
    $ret = M('user')->where("id={$_SESSION['id']}")->find();

    $this->assign("ret",$ret);
   	$this->display();
   }
   public function doyhxx()
   {
   	$bank = I('post.bank');
   	$bankname=I('post.bankname');	//真实姓名
   	$bankcode=I('post.bankcode');	//银行卡号
   	$bankaddress=I('post.bankaddress');		//开户地址
   
   
   	
   	$D = M('user');
   	$res = $D->where("id={$_SESSION['id']}")->find();
   	$arr = array(
   				'bank'=>$bank,
   				'bankname'=>$bankname,
				'bankcode'=>$bankcode,
				'bankaddress'=>$bankaddress,	
	);
	$bool=$D->where('id='.session('id'))->save($arr);

	if($bool){
			echo 1;
	}else{
			echo 2;
	}
   }
   
    //个人信息
   public function grxx()
   {
   	$user = M("user")->where("id=".session('id'))->find();
   	$this->assign('user',$user);
   	$this->display();
   }
   public function dogrxx()
   {
   	$realname = I('post.realname');
   	$mobile = I('post.mobile');
   	$paper = I('post.paper');
   	
   	
   	$Mc=M('user');
   	$ret = $Mc->where("id={$_SESSION['id']}")->find();
	
	
	 //判断输入是否正确
        $D = M('user');
        $data1 = $D->where('realname="' . $realname . '"')->find();
        if (!empty($data1)) {
            echo 2;		//用户名已经存在，
            die();
        }
        //判断手机号码
        $data2 = $D->where('mobile="' . $mobile . '"')->find();
        if (!empty($data2)) {
            echo 4;//手机号码已经存在，
            die();
        }
        //判断身份证号码
        $data3 = $D->where('paper="' . $paper . '"')->find();
        if (!empty($data3)) {
            echo 6;//身份证号码已经存在，
            die();
        }
        
		$arr = array('realname'=>$realname,
				'mobile'=>$mobile,
				'paper'=>$paper,
				'id'=>$_SESSION['id']
		);
		M("User")->save($arr);
		echo 1;
   }
   
   //密码修改
   public function mmxg()
   {
   		$this->display();
   }

   	public function dommxg()
   	{
   	 	$password=md5(I('post.password'));
      $newpassword=md5(I('post.newpassword'));
   	 	$newpassword= $this->md5pw($newpassword);
		
   	 	$D = M('user');
   	 	$ret=$D->field('password')->where("id={$_SESSION['id']}")->find();

		if(!$this->_checkPwd($password,$ret['password'])){
			echo 2;
			die;
		}else{
			$arr=array('password'=> $newpassword);
		}
  		$D->where("id={$_SESSION['id']}")->save($arr);
  		echo 1;
   }

  	 public function doaqmmxg()
   	 {
   		$paypassword=md5(I('post.paypassword'));
      $newpaypassword=md5(I('post.newpaypassword'));
   	 	$newpaypassword= $this->md5pw($newpaypassword);
		
   	 	$D = M('user');
   	 	$rec=$D->field('paypassword')->where("id={$_SESSION['id']}")->find();

		if(!$this->_checkPwd($paypassword,$rec['paypassword'])){
			echo 2;
			die;
		}else{
			$arr=array('paypassword'=>$newpaypassword);
		}
  		$D->where("id={$_SESSION['id']}")->save($arr);
  		echo 1;
   	}
   
   //推荐列表
	public function tjlb()
	{
		header("Content-Type: text/html;charset=utf-8");
		//查询自己下的推荐人（一级）
		$rqt = M('user')->where("tusername='".session('username')."'")->select();	//查询所有推荐人为登录帐号的数据
		$rdc = M('user')->where("tusername='".session('username')."'")->count();	//查询总条数
		$this->assign('dv',$dv);
//		
        
        for($i=0;$i<$rdc;$i++)
        {
        	$vo = M('tree')->where("userid=".$rqt[$i]['id'].' and `out`=0')->order('id desc')->limit(1)->find();
        	if($vo['number']%2==0){
				$number=$vo['number']/2;
				if($number%2==0){
					$n1=$number+1;
				}else{
					$n1=$number-1;
				}
				$vo1=M('tree')->alias('t')->JOIN('LEFT JOIN tm_user as u on u.id=t.userid')->where("t.number=".$n1)->find();
				$rqt[$i]['ss'] = $vo1['id'];
				if(!empty($vo1))
				{
					$rqt[$i]['number1']=$vo1['username'];
				}
			}else{
				$number=$vo['number']-1;
				$bkq = $number/2;
				if($bkq%2==0){
					$n1 = $bkq+1;
				}else{
					$n1 = $bkq-1;
				}
				$vo1=M('tree')->alias('t')->JOIN('LEFT JOIN tm_user as u on u.id=t.userid')->where("t.number=".$n1)->find();
				$rqt[$i]['ss'] = $vo1['id'];
				if(!empty($vo1))
				{
					$rqt[$i]['number1'] = $vo1['username'];
				}
			}
			
        }
        
        
		$this->assign('rqt',$rqt);
		$this->assign('rdc',$rdc);
		$this->display();
	}
   //资金明细
   public function jdmx()
   {
   		$id = I('get.id');
		$user = M('user');
    	$rbb = $user->where("id={$_SESSION['id']}")->find();
		//报单积分
		$data = M("income")->alias("c")->field("c.gotegary as cg,c.createtime,c.userid,u.username,c.status,c.price,c.title,c.gotegary,b.integral1,b.integral2,b.integral3")->join("LEFT Join __USER__ as u on c.userid=u.id")->join("LEFT JOIN __INTEGRAL__ as b on b.userid=u.id")->where("u.id={$_SESSION['id']} and c.gotegary=2")->order("c.id desc")->select();
		//用户积分
		$datq = M("income")->alias("c")->field("c.gotegary as cg,c.createtime,c.userid,u.username,c.status,c.price,c.title,c.gotegary,b.integral1,b.integral2,b.integral3")->join("LEFT Join __USER__ as u on c.userid=u.id")->join("LEFT JOIN __INTEGRAL__ as b on b.userid=u.id")->where("u.id={$_SESSION['id']} and c.gotegary=1")->order("c.id desc")->select();
		//销售积分
		$dath = M("income")->alias("c")->field("c.gotegary as cg,c.createtime,c.userid,u.username,c.status,c.price,c.title,c.gotegary,b.integral1,b.integral2,b.integral3")->join("LEFT Join __USER__ as u on c.userid=u.id")->join("LEFT JOIN __INTEGRAL__ as b on b.userid=u.id")->where("u.id={$_SESSION['id']} and c.gotegary=4")->order("c.id desc")->select();
		
		$this->assign('id',$id);
      	$this->assign('dath',$dath);
		$this->assign('datq',$datq);
   		$this->assign('data',$data);
   		$this->assign('rbb',$rbb);
   		$this->display();
   }
   //个人中心
   public function grzx()
   {
   	$this->display();
   }
   //转账
   public function recharge()
   {
    $res = M('user')->where("id={$_SESSION['id']}")->find();

   	$D=M('integral');
   	$recharge = $D->where("userid={$_SESSION['id']}")->find();
   	$this->assign('recharge',$recharge);
    $this->assign('res',$res);
   	$this->display();
   }
public function dorecharge()
   {	
	   	header("Content-Type: text/html;charset=utf-8");
	   	$recharge = I('post.recharge');
	    $name = I('post.name');
	    if($name==""){
	    	$name=session('username');
	    }
	    $zz = I('post.zz');
	    $zzlx = I('post.zzlx');
	    $paypassword = md5(I('post.paypassword'));
	    $createtime = time();
		$admin=M('user');
		$info = $admin->where("id={$_SESSION['id']}")->find();
	
		$data = M('user')->where('username="'.$name.'"')->find();
	
		if($this->_checkPwd($paypassword,$info['paypassword'])){
	            
	    }else{
	        echo"<script>alert('用户密码有误，请重新输入');location.href='/Home/wo/recharge.html'</script>";
	         die();
	    }
      	if (!$data) {
          	echo"<script>alert('转账账户错误，请核查');location.href='/Home/wo/recharge.html'</script>";
    	}else{
      		if($zzlx==0){
      			echo "<script>alert('请选择账户类型！');location.href='/Home/wo/recharge.html'</script>";
      		}else{
		        if($zzlx==1){
		          	if($name==session('username')){
		            	if ($zz==2){
		            		$D=M('integral');
		                	$res = $D->where("userid={$_SESSION['id']}")->find();
		            		if($recharge>$res['integral1']){
		                    	echo"<script>alert('转账失败,余额不足！');location.href='/Home/wo/recharge.html'</script>";
		                	}else{
		                		$total = M('integral')->where("userid=".session('id'))->find();
		                		$rew = $res['integral1']-$recharge;
			                  	$update = array('integral1' =>$rew,'userid' =>session('id'));
			                  	M('integral')->where("userid={$_SESSION['id']}")->save($update);
			                  	$arr = array(
				                   	'userid'=>$_SESSION['id'],
				                   	'username'=>$_SESSION['username'],
				                   	'title'=>'转账到'.$name.'会员，消耗推广积分',
				                  	'price'=>$recharge,
				                    'createtime'=>$createtime,
				                    'total'=>$total['integral1'],
				                  	'status'=>0,
				                    'gotegary'=>1
			                  	);
			                  	M('income')->add($arr);
								
								$total = M('integral')->where("userid=".session('id'))->find();
				                $ree = $D->where("userid=".$data['id'])->find();
				                $rex = $ree['integral2'] + $recharge;
				                $update1 = array('integral2' =>$rex,'userid' =>$data['id']);
				                $D->where("userid=".$data['id'])->save($update1);
				                $arr = array(
				                    'userid'=>$data['id'],
				                    'username'=>$data['username'],
				                    'title'=>$name.'收到'.$_SESSION['username'].'会员转账积分并自动转为注册积分',
				                    'price'=>$recharge,
				                    'createtime'=>$createtime,
				                    'total'=>$total['integral2'],
				                    'status'=>1,
				                    'gotegary'=>2
			                  	   );
			                  	M('income')->add($arr);
			                  	echo"<script>alert('转账成功！');location.href='/Home/wo/recharge.html';</script>";
			                  	die;
		            		}
		            	}
	          		}else{
	          			echo "<script>alert('推广积分只能转到自身账户下的注册积分！');location.href='/Home/wo/recharge.html'</script>";
	          		}	              
		                
		        }elseif($zzlx==2){    //类型
		        	//这里可加判断对方是否是服务中心
		        	if ($zz==2){
	            		$D=M('integral');
	                	$res = $D->where("userid={$_SESSION['id']}")->find();
	            		if($recharge>$res['integral2']){
	                    	echo"<script>alert('转账失败,余额不足！');location.href='/Home/wo/recharge.html'</script>";
	                	}else{
	                		$total = M('integral')->where("userid=".session('id'))->find();
	                		$rew = $res['integral2']-$recharge;
		                  	$update = array('integral2' =>$rew,'userid' =>session('id'));
		                  	M('integral')->where("userid={$_SESSION['id']}")->save($update);
		                  	$arr = array(
			                   	'userid'=>$_SESSION['id'],
			                   	'username'=>$_SESSION['username'],
			                   	'title'=>'转账到'.$name.'会员，消耗注册积分',
			                  	'price'=>$recharge,
			                    'createtime'=>$createtime,
			                    'total'=>$total['integral2'],
			                  	'status'=>0,
			                    'gotegary'=>2
		                  	);
		                  	M('income')->add($arr);
			                  
			                $total = M('integral')->where("userid=".$data['id'])->find();
		          			$yuer = M('integral')->where("userid='".$data['id']."'")->find();
			                $ree = $D->where("userid=".$data['id'])->find();
			                $rex = $ree['integral2'] + $recharge;
			                $update1 = array('integral2' =>$rex,'userid' =>$data['id']);
			                $D->where("userid=".$data['id'])->save($update1);
			                $arr = array(
			                    'userid'=>$data['id'],
			                    'username'=>$data['username'],
			                    'title'=>$name.'收到'.$_SESSION['username'].'会员转账的注册积分',
			                    'price'=>$recharge,
			                    'createtime'=>$createtime,
			                    'total'=>$total['integral2'],
			                    'status'=>1,
			                    'gotegary'=>2
		                  	   );
		                  	M('income')->add($arr);
		                  	echo"<script>alert('转账成功！');location.href='/Home/wo/recharge.html';</script>";
		                  	die;
	            		}
	            	}
            	//到这里
	        	}elseif($zzlx==3){
	        		$xf = M("bycar")->where("userid=".session('id'))->order('id desc')->limit(1)->find();
	        		
                    if(empty($xf))
                    {
                    	$sb = M('integral')->where("userid=".session('id'))->find();
                    	if($sb['integral4']>=12000){
					               if($recharge>($sb['integral4']-12000))
					               { 
					               	 echo"<script>alert('转账失败,余额不足！');location.href='/Home/wo/recharge.html''</script>";
					               	}
					               	else
				                	{
				                		
			                	    	$rew = $sb['integral4']-$recharge;
					                  	$update = array('integral4' =>$rew,'userid' =>session('id'));
					                  	M('integral')->where("userid={$_SESSION['id']}")->save($update);
					                  	$arr = array(
						                   	'userid'=>$_SESSION['id'],
						                   	'username'=>$_SESSION['username'],
						                   	'title'=>'转账到'.$name.'会员，消耗消费积分',
						                  	'price'=>$recharge,
						                    'createtime'=>$createtime,
						                    'total'=>$sb['integral4'],
						                  	'status'=>0,
						                    'gotegary'=>4
					                  	);
					                  	M('income')->add($arr);
						                  
					          			
						                $ree = $D->where("userid=".$data['id'])->find();
						                $kkk = $recharge*0.2;
						                $sjjg = $recharge-$kkk;
						                $rex = $ree['integral1'] + ($recharge*0.8);
						                $update1 = array('integral1' =>$rex,'userid' =>$data['id']);
						                $D->where("userid=".$data['id'])->save($update1);
						                $arr = array(
						                    'userid'=>$data['id'],
						                    'username'=>$data['username'],
						                    'title'=>'收到'.$_SESSION['username'].'会员转账的'.$recharge.'消费积分',
						                    'price'=>$sjjg,
						                    'createtime'=>$createtime,
						                    'total'=>$sb['integral1'],
						                    'status'=>1,
						                    'gotegary'=>1
					                  	   );
					                  	M('income')->add($arr);
					                  	echo"<script>alert('转账成功！');location.href='/Home/wo/recharge.html'</script>";
					                  	die;
				            		}
			          			}else
			          			{
			          				if($recharge>$res['integral4'])
			          				{
				                    	echo"<script>alert('转账失败,余额不足！');location.href='/Home/wo/recharge.html'</script>";
				                	}else
				                	{
				                		
			                	    	$rew = $sb['integral4']-$recharge;
					                  	$update = array('integral4' =>$rew,'userid' =>session('id'));
					                  	M('integral')->where("userid={$_SESSION['id']}")->save($update);
					                  	$arr = array(
						                   	'userid'=>$_SESSION['id'],
						                   	'username'=>$_SESSION['username'],
						                   	'title'=>'转账到'.$name.'会员，消耗消费积分',
						                  	'price'=>$recharge,
						                    'createtime'=>$createtime,
						                    'total'=>$sb['integral4'],
						                  	'status'=>0,
						                    'gotegary'=>4
					                  	);
					                  	M('income')->add($arr);
						                  
					          			
						                $ree = $D->where("userid=".$data['id'])->find();
						                $kkk = $recharge*0.2;
						                $sjjg = $recharge-$kkk;
						                $rex = $ree['integral1'] + ($recharge*0.8);
						                $update1 = array('integral1' =>$rex,'userid' =>$data['id']);
						                $D->where("userid=".$data['id'])->save($update1);
						                $arr = array(
						                    'userid'=>$data['id'],
						                    'username'=>$data['username'],
						                    'title'=>'收到'.$_SESSION['username'].'会员转账的'.$recharge.'消费积分',
						                    'price'=>$sjjg,
						                    'createtime'=>$createtime,
						                    'total'=>$sb['integral1'],
						                    'status'=>1,
						                    'gotegary'=>1
					                  	   );
					                  	M('income')->add($arr);
					                  	echo"<script>alert('转账成功！');location.href='/Home/wo/recharge.html'</script>";
					                  	die;
				            		}
			          			}
	        		}
        		}
        	}
    	}
      
   }
   
    
     //公告详情
    public function ggxq()
    {
    	$id=I('get.typeid');
    	$D = M('type');
    	$types=$D->where('typeid='.$id)->find();
		$this->assign('types',$types);
    	$this->display();
    }
     //系统通告
    public function xtgg()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$gg = M('type');
    	$res=$gg->select();
    	
		$this->assign('res',$res);
    	$this->display();
    }
    
    //反馈消息
    public function znxj()
    {
    	$D = M('message');
    	$bbb = $D->where('userid='.$_SESSION['id'])->select();
    	$this->assign('bbb',$bbb);
    	$this->display();
    }
    public function fkxq()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$id=I('get.id');
    	$D = M('message');
    	$update = array('status'=>1,'id'=>$id);
    	M('message')->save($update);
    	$ccc = $D->where('id='.$id )->select();
    	$this->assign('ccc',$ccc);
    	$this->display();
    }
    public function sqfk()
    {
    	$this->display();
    }
    public function dosqfk()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$title = I('post.title');
    	$content = I('post.content');
    	$createtime = time();
    	
    	$B = M('message');
    	
    	$arr = array('title'=>$title,'content'=>$content,'userid'=>$_SESSION['id'],'createtime'=>time());
   		$data = $B->where("userid={$_SESSION['id']}")->add($arr);
   		if($data){
   			echo 1;
   		}
    }
    
    //激活
    public function activation()
    {
    	
    	header("Content-Type: text/html;charset=utf-8");
//  	echo $_SESSION['id'];die;
		
		$id = I('get.id');
		$D = M('integral');
		$AA = M('user');
		
		$boo = $D->where("userid={$_SESSION['id']}")->find();
		
		if($boo['integral3'] >= 10000){
			$ddb = $boo['integral3'] - 10000;
			
			$ac = $AA->max('number');
			$bbs = $ac+1;
			
			
			$uc = $AA->where("id=".$id)->find();
			$number = $uc['number'] + $bbs;
			$status=$uc['status'] =1;
			
			$update1 = array('number'=>$number,'status'=>$status);
			$update = array('integral3' =>$ddb,'userid' =>$_SESSION['id']);
			
   			$D->where("userid={$_SESSION['id']}")->save($update);
   			$AA->where("id=".$id)->save($update1);
			
			
			$oop = $boo['integral1'] + 8000;
			$update2 = array('integral1' =>$oop,'userid' =>$_SESSION['id']);
			$D->where("userid={$_SESSION['id']}")->save($update2);
			
			$ddm = M('samsara');
			$update3 = array('userid'=>$id,'usernameid'=>$_SESSION['id'],'samsara'=>1);
   			$ddm->where("userid={$_SESSION['id']}")->add($update3);
   			
			echo "<script>alert('激活成功');location.href='/Home/wo/wtjg.html'</script>";
			
			
		}else{
			echo "<script>alert('激活失败,余额不足，请进行充值');location.href='/Home/wo/tjlb.html'</script>";
		}
    }
    
 //网体结构
    public function wtjg()
    {
    	$D=M('user');
        //网络结构图
        $T=M('tree');
    	$name = I('post.name');
    	$id = I('get.id');
    	if($name != ""){
    		$us = M('user')->where("username='".$name."' and status=1")->find();
            if(empty($us))
            {
            	echo"<script>alert('没有相应的帐户');history.go(-1);</script>";
            	die();
            }
    		//$us = M("user")->where("id='".$jj['id'] ."'")->find();
    		$userid=$us['id'];
    	}else{
    		if($id==''){
	    		$us = M("user")->where("username='".session('username') ."'")->find();
	    		$userid=session('id');
	    	}else{
	    		$us = M("user")->where("id='".$id ."'")->find();
	    		$userid=$id;
	    	}
    	}
    	
    	
    	
    	
    	
    	
        //查询最顶端的数据
        $tdata=$T->where('userid='.$us['id'].' and `out`=0')->order('id desc')->limit(1)->find();
        if($tdata['number']%2==0){
        	$ab = $tdata['number']/2;
			$cd = $tdata['number']/4;
        	$tdata1=$T->where("(number=".$ab." or number=".$cd.") and `out`=0 and number!=1")->order(' id asc')->limit(1)->find();
			
        	if(!empty($tdata1))
        	{
        		$userid=$tdata1['userid'];
        	}
        	
	    }elseif($tdata['number']%2==1){
			$ab = ($tdata['number']-1)/2;
			$cd = ($tdata['number']-1)/4;
			$tdata1=$T->where("(number=".$ab." or number=".$cd.") and `out`=0  and number!=1")->order(' id asc')->limit(1)->find();
        	if(!empty($tdata1))
        	{
        		$userid=$tdata1['userid'];
        	}
		}
        

        
        
        
    	
       
        //$tu=$T->where('userid='.$us['id'])->order('id desc')->limit(1)->find();
        
        $tu=$T->where('userid='.$userid)->order('id desc')->limit(1)->find();
        $us = $D->where("id=".$tu['userid'])->find();


       
        //dump($tu);exit;
        $arr=array();
        $arr[0]=$tu['number'];
        $arr[1]=$tu['number']*2;
        $arr[2]=$tu['number']*2+1;
        $arr[3]=$tu['number']*4;
        $arr[4]=$tu['number']*4+1;
        $arr[5]=($tu['number']*2+1)*2;
        $arr[6]=($tu['number']*2+1)*2+1;

        if($arr[0]>1)
        {
            if($arr[0]%2==0)
        	  $tur=$arr[0]+1;
        	else
        	  $tur=$arr[0]-1;
        	$a0l=$tur*2;
        	$a0r=$tur*2+1;
        	$a01= $D->where('id=(select userid from tm_tree where number='.$a0l.')')->find();
        	$a02= $D->where('id=(select userid from tm_tree where number='.$a0r.')')->find();
        	$this->assign('a01',$a01);
        	$this->assign('a02',$a02);
        }
        $this->assign('us',$us);

//      
        $as = M('user')->where("username='".$us['tusername']."'")->find();

    	$this->assign('as',$as);
       
		
        //得到第2个用户
        $tu1= $D->where('id=(select userid from tm_tree where number='.$arr[1].')')->find();
        $tuu1 = M('user')->where("username='".$tu1['tusername']."'")->find();
        if(!empty($tu1))
        {
           if($arr[1]%2==0)
        	  $tur1=$arr[1]+1;
        	else
        	  $tur1=$arr[1]-1;
        	$a1l=$tur1*2;
        	$a1r=$tur1*2+1;
        	$a11= $D->where('id=(select userid from tm_tree where number='.$a1l.')')->find();
        	$a12= $D->where('id=(select userid from tm_tree where number='.$a1r.')')->find();
        	$this->assign('a11',$a11);
        	$this->assign('a12',$a12);
        }
         $this->assign('tu1',$tu1);
         $this->assign('tuu1',$tuu1);
         
         //得到第3个用户
        $tu2= $D->where('id=(select userid from tm_tree where number='.$arr[2].')')->find();
        $tuu2 = M('user')->where("username='".$tu2['tusername']."'")->find();
        if(!empty($tu2)){
            if($arr[2]%2==0)
        	  $tur2=$arr[2]+1;
        	else
        	  $tur2=$arr[2]-1;
        	$a2l=$tur2*2;
        	$a2r=$tur2*2+1;
        	$a21= $D->where('id=(select userid from tm_tree where number='.$a2l.')')->find();
        	$a22= $D->where('id=(select userid from tm_tree where number='.$a2r.')')->find();
        	$this->assign('a21',$a21);
        	$this->assign('a22',$a22);
        }
         $this->assign('tu2',$tu2);	
         $this->assign('tuu2',$tuu2);	
         //得到第4个用户
        $tu3= $D->where('id=(select userid from tm_tree where number='.$arr[3].')')->find();
        $tuu3 = M('user')->where("username='".$tu3['tusername']."'")->find();
        if(!empty($tu3)){
             if($arr[3]%2==0)
              $tur3=$arr[3]+1;
        	else
        	  $tur3=$arr[3]-1;
        	$a3l=$tur3*2;
        	$a3r=$tur3*2+1;
        	$a31= $D->where('id=(select userid from tm_tree where number='.$a3l.')')->find();
        	$a32= $D->where('id=(select userid from tm_tree where number='.$a3r.')')->find();
        	$this->assign('a31',$a31);
        	$this->assign('a32',$a32);
        }
         $this->assign('tu3',$tu3);
         $this->assign('tuu3',$tuu3);		
           //得到第5个用户
        $tu4= $D->where('id=(select userid from tm_tree where number='.$arr[4].')')->find();
        $tuu4 = M('user')->where("username='".$tu4['tusername']."'")->find();
        if(!empty($tu4))
        {
            if($arr[4]%2==0)
        	  $tur4=$arr[4]+1;
        	else
        	  $tur4=$arr[4]-1;
        	$a4l=$tur4*2;
        	$a4r=$tur4*2+1;
        	$a41= $D->where('id=(select userid from tm_tree where number='.$a4l.')')->find();
        	$a42= $D->where('id=(select userid from tm_tree where number='.$a4r.')')->find();
        	$this->assign('a41',$a41);
        	$this->assign('a42',$a42);
        }
         $this->assign('tu4',$tu4);	
         $this->assign('tuu4',$tuu4);
             //得到第6个用户
        $tu5= $D->where('id=(select userid from tm_tree where number='.$arr[5].')')->find();
        $tuu5 = M('user')->where("username='".$tu5['tusername']."'")->find();
        if(!empty($tu5))
        {
            if($arr[5]%2==0)
        	  $tur5=$arr[5]+1;
        	else
        	  $tur5=$arr[5]-1;
        	$a5l=$tur5*2;
        	$a5r=$tur5*2+1;
        	$a51= $D->where('id=(select userid from tm_tree where number='.$a5l.')')->find();
        	$a52= $D->where('id=(select userid from tm_tree where number='.$a5r.')')->find();
        	$this->assign('a51',$a51);
        	$this->assign('a52',$a52);
        }
         $this->assign('tu5',$tu5);
         $this->assign('tuu5',$tuu5);

              //得到第7个用户
        $tu6= $D->where('id=(select userid from tm_tree where number='.$arr[6].')')->find();
        $tuu6 = M('user')->where("username='".$tu6['tusername']."'")->find();
      
        if(!empty($tu6))
        {
           if($arr[6]%2==0)
        	  $tur5=$arr[6]+1;
        	else
        	  $tur5=$arr[6]-1;
        	$a6l=$tur6*2;
        	$a6r=$tur6*2+1;
        	$a61= $D->where('id=(select userid from tm_tree where number='.$a6l.')')->find();
        	$a62= $D->where('id=(select userid from tm_tree where number='.$a6r.')')->find();
        	$this->assign('a61',$a61);
        	$this->assign('a62',$a62);
        }    
        	
         $this->assign('tu6',$tu6);
         $this->assign('tuu6',$tuu6);		

    	$this->display();
    }
    // 取到三叉权三层用户
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
	
	//服务中心
	public function fwzx()
	{
    $D = M('user')->where("id={$_SESSION['id']}")->find();
    $this->assign("D",$D);
		$this->display();
	}
	//申请报单中心
	public function sqbdzx()
	{
		$user = M('user')->where('id='.session('id'))->find();		
		$this->assign('user',$user);
		$this->display();
	}
	public function dosqbdzx()
	{
		$username = I('post.username');
		$tusername = I('post.tusername');
		$city = I('post.city');
		
		$arr = array(
			'userid'=>session('id'),
			'username'=>$username,
			'tusername'=>$tusername,
			'city'=>$city,
			'createtime'=>time()
		);
		M('bd')->add($arr);
		echo "<script>alert('申请成功，我们会尽快处理');location.href='/home/wo/wo.html';</script>";
	}
	//报单列表
	public function bdlb()
	{
	   	header("Content-Type: text/html;charset=utf-8");
	   	
		$jhm = M('user')->where("fwzx='".session('username')."'")->order('status asc,id desc')->select();
        $number=count($jhm);
        $this->assign('number',$number);
		$this->assign('jhm',$jhm);
		$this->display();
	}
	 public function xjb()
    {
      
      $this->display();
    }
    public function doxjb()
    {
      header("Content-Type: text/html;charset=utf-8");
      $name = I('post.name');
      $czje = I('post.czje');
      $paypassword = md5(I('post.paypassword'));
      $admin=M('user');
      $info = $admin->where("id={$_SESSION['id']}")->find();
      $datc = $admin->where('newnumber="' . $name . '"')->find();
      if($datc){
        
      }else{
        echo"<script>alert('转账账户错误，请核查')</script>";
      }
      if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo"<script>alert('用户密码有误，请重新输入');location.href='/Admin/jh/xjb.html'</script>";
             die();
        }
        
      $D=M('integral');
      $res = $D->where("userid={$_SESSION['id']}")->find();
      $rew = $res['integral1']-$czje;
      
      if($czje>$res['integral1']){
         echo"<script>alert('转账失败,余额不足！');location.href='/Admin/index/xjb.html'</script>";
      }else{
        $upd = $D->where("userid={$_SESSION['id']}")->find();
      $update = array('integral1'=>$rew,'userid'=>$upd);
      $rbk = $D->where("userid={$_SESSION['id']}")->save($update);
        
        
        $ccs = $D->where('userid='.$datc['id'])->find();
        $rdt = $ccs['integral1']+$czje;
        $update1 = array('integral1'=>$rdt,'userid'=>$ccs);
        $rck = $D->where("userid=".$ccs['userid'])->save($update1);
        echo"<script>alert('充值到注册积分成功！');location.href='/Admin/index/xjb.html'</script>";
      }
    }

    public function gcjjtx()
    {
      $zj = M('integral')->where("userid={$_SESSION['id']}")->find();
      $this->assign('zj',$zj);
      $this->display();
    }
    public function dogcjjtx()
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
            $fq = M('integral')->where("userid={$_SESSION['id']}")->find();

          if($this->_checkPwd($password,$info['paypassword'])){
                    
            }else{
                echo"<script>alert('用户密码有误，请重新输入');location.href='/Home/wo/gcjjtx.html'</script>";
                 die();
            }


          if($price>=2000){
               if($fq['integral4']<$price){
                     echo "<script>alert('剩余余额不足');location.href='/Home/wo/gcjjtx.html'</script>";
          }else{
             if($price%100==0){  
          if($pay==0){
            $jg = $price-($price*0.2);
            $sxf = $jg-$price;
                $arr = array(
                        'pay'=>$alipay,
                        'price'=>$price,
                        'sjje'=>$jg,
                        'sxf'=>$sxf,
                        'phone'=>$phone,
                        'status'=>0,
                        'createtime'=>$createtime,
                        'userid'=>$_SESSION['id']
                );
                M('tx')->where("userid={$_SESSION['id']}")->add($arr);
                //扣费
                $kf = M('integral')->where("userid={$_SESSION['id']}")->find();
                $txfy = $kf['integral4']-$price;
                $arr1 = array(
                        'integral4'=>$txfy
                    );
                M('integral')->where("userid={$_SESSION['id']}")->save($arr1);
                $arr = array(
                    'userid'=>$_SESSION['id'],
                    'username'=>$_SESSION['username'],
                    'title'=>$_SESSION['username'].'会员，提现'.$price,
                    'price'=>$price,
                    'createtime'=>$createtime,
                    'status'=>0,
                    'gotegary'=>4
                    );
                M('income')->add($arr);
                echo"<script>alert('提现申请成功！');location.href='/Home/wo/wo.html'</script>";
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
                $txfy = $kf['integral4']-$price;
                $arr1 = array(
                        'integral4'=>$txfy
                    );
                M('integral')->where("userid={$_SESSION['id']}")->save($arr1);
                $arr = array(
                    'userid'=>$_SESSION['id'],
                    'username'=>$_SESSION['username'],
                    'title'=>$_SESSION['username'].'会员，提现',
                    'price'=>$price,
                    'createtime'=>$createtime,
                    'status'=>0,
                    'gotegary'=>4
                    );
                M('income')->add($arr);
                 echo"<script>alert('提现申请成功！');location.href='/Home/wo/wo.html'</script>";
            }
            }else{
             echo "<script>alert('提现最低2000起，每次最少100的倍数，例如(2100，2200)！');location.href='/Home/wo/gcjjtx.html'</script>";
          }

            }
          }else{
              echo"<script>alert('提现积分不能低于2000！');location.href='/Home/wo/wo.html'</script>";
            }
    }

    
	//服务中心现金币提现
	public function tx()
    {
      $zj = M('integral')->where("userid={$_SESSION['id']}")->find();
      $tx = M('user')->where('id='.$zj['userid'])->find();
      $this->assign('tx',$tx);
      $this->assign('zj',$zj);
      $this->display();
    }

    public function dofwzxxjbtx()
    {
    	    header("Content-Type: text/html;charset=utf-8");
            $price = I('post.price');
            $password = md5(I('post.password'));
            $datetime = time();
            $admin=M('user');
          	$info = $admin->where("id={$_SESSION['id']}")->find();
            $cd = M('integral');
            $fq = $cd->where("userid={$_SESSION['id']}")->find();

          	if($this->_checkPwd($password,$info['paypassword'])){
                    
            }else{
                echo"<script>alert('用户密码有误，请重新输入');location.href='/Home/wo/fwzxxjbtx.html'</script>";
                 die();
            }
            
            if($info['bank']==""){
	            echo "<script>alert('您未填写开户银行,请先补齐您的资料才可进行提现');location.href='/Home/wo/yhxx.html'</script>";
	        }else if($info['bankname']==""){
	            echo "<script>alert('您的开户姓名为空,请先补齐您的资料才可进行提现');location.href='/Home/wo/yhxx.html'</script>";
	        }else if($info['bankcode']==""){
	            echo "<script>alert('您的银行卡号为空,请先补齐您的资料才可进行提现');location.href='/Home/wo/yhxx.html'</script>";
	        }
            
            
          	if($price>=2000){
                if($fq['integral1']<$price){
                   echo "<script>alert('剩余余额不足');location.href='/Home/wo/fwzxxjbtx.html'</script>";
                }else{
                  if($price%100==0){
                  $jg = $price-($price*0.02);
                  $sxf = $jg-$price;
                      $arr = array(
                        'price'=>$price,
                        'sxf'=>$sxf,
                        'sjje'=>$jg,
                        'zt'=>0,
                        'datetime'=>$datetime,
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
                    'createtime'=>$datetime,
                    'status'=>0,
                    'gotegary'=>1
                    );
                M('income')->add($arr);
                echo"<script>alert('提现申请成功！');location.href='/Home/wo/wo.html'</script>";
            }else{
              echo "<script>alert('提现最低2000起，每次最少100的倍数，例如(2100，2200)！');location.href='/Home/wo/fwzxxjbtx.html'</script>";
            }
          }
            die;
          }else{
            echo"<script>alert('提现积分不能低于2000！');location.href='/Home/wo/wo.html'</script>";
          }
    }
    public function xjbtx()
    {
      $zj = M('integral')->where("userid={$_SESSION['id']}")->find();
      $this->assign('zj',$zj);
      $this->display();
    }

    public function doxjbtx()
    {
            header("Content-Type: text/html;charset=utf-8");
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
            $cd = M('integral');
            $fq = $cd->where("userid={$_SESSION['id']}")->find();
          
          if($this->_checkPwd($password,$info['paypassword'])){
                    
          }else{
                echo"<script>alert('用户密码有误，请重新输入');location.href='/Home/wo/xjbtx.html'</script>";
                 
          }
          if($price>=2000){
                if($fq['integral1']<$price){
                   echo "<script>alert('剩余余额不足');location.href='/Home/wo/xjbtx.html'</script>";
                }else{
                   if($price%100==0){
                if($pay==0){
                  $jg = $price-($price*0.05);
                  $sxf = $jg-$price;
                      $arr = array(
                            'pay'=>$alipay,
                            'price'=>$price,
                            'sxf'=>$sxf,
                            'sjje'=>$jg,
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
                    'gotegary'=>3
                    );
                M('income')->add($arr);
                echo"<script>alert('提现申请成功！');location.href='/Home/wo/wo.html'</script>";
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
                $kf = M('integra1')->where("userid={$_SESSION['id']}")->find();
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
                    'gotegary'=>3
                    );
                M('income')->add($arr);
                echo"<script>alert('提现申请成功！');location.href='/Home/wo/wo.html'</script>";
            }

          }else{
              echo "<script>alert('提现最低2000起，每次最少100的倍数，例如(2100，2200)！');location.href='/Home/wo/xjbtx.html'</script>";
          }

        }
    }else{
       echo"<script>alert('提现积分不能低于2000！');location.href='/Home/wo/xjbtx.html'</script>";
    }

  }
//购车基金转账
   public function gcjjzz()
   {
    $D=M('integral');
    $recharge = $D->where("userid={$_SESSION['id']}")->find();
    $this->assign('recharge',$recharge);
    $this->display();
   }
    public function dogcjjzz()
   {  
      header("Content-Type: text/html;charset=utf-8");
      $recharge = I('post.recharge');
      $name = I('post.name');
      $zz = I('post.zz');
      $password = md5(I('post.password'));
      $admin=M('user');
      $info = $admin->where("id={$_SESSION['id']}")->find();
      $data = M('user')->where('username="'.$name.'"')->find();

      $adc = M('user')->where('tohelp=1')->select();

      if($this->_checkPwd($password,$info['paypassword'])){
                
        }else{
            echo"<script>alert('用户密码有误，请重新输入');location.href='/Home/wo/gcjjzz.html'</script>";
             die();
        }
      if (!$data) {
          echo"<script>alert('转账账户错误，请核查');location.href='/Home/wo/gcjjzz.html'</script>";
      }else{
          if($data['tohelp']==1){
               if ($zz==1) {
          $D=M('integral');
          $res = $D->where("userid={$_SESSION['id']}")->find();
         
          if($recharge>$res['integral4']){
             echo"<script>alert('转账失败,余额不足！');location.href='/Home/wo/gcjjzz.html'</script>";
          }else{
              $rew = $res['integral4']-$recharge;
              $update = array('integral4' =>$rew,'userid' =>$_SESSION['id']);
              M('integral')->where("userid={$_SESSION['id']}")->save($update);

              $arr = array(
                'userid'=>$_SESSION['id'],
                'username'=>$_SESSION['username'],
                'title'=>'转账'.$name.'会员，消耗销售积分',
                'price'=>$recharge,
                'createtime'=>$createtime,
                'status'=>0,
                'gotegary'=>1
               );
              M('income')->add($arr);

              $ree = $D->where("userid=".$data['id'])->find();
              $rex = $ree['integral2'] + $recharge;
              $update1 = array('integral2' =>$rex,'userid' =>$data['id']);
              $D->where("userid=".$data['id'])->save($update1);
              $arr = array(
                'userid'=>$data['id'],
                'title'=>'收到转帐款'.$name.'会员，收到注册积分',
                'price'=>$recharge,
                'createtime'=>$createtime,
                'status'=>1,
                'gotegary'=>3
               );
              M('income')->add($arr);
             
          echo"<script>alert('转账成功！');location.href='/Home/wo/wo.html'</script>";
          }
        }

      }else{
        echo "<script>alert('填写的用户必须是服务中心！');location.href='/Home/wo/gcjjzz.html'</script>";
      }
     
      
    }
      
   }
   	public function showUs(){
   		$username = I("post.name");

        $res = D("User")->where(array("username"=>$username))->find();
        if($res){
          $realname = $res['realname'];
          $mobile = $res['mobile'];
          echo json_encode(array('status'=>1,'realname'=>$realname,'mobile'=>$mobile));
        }else{
          echo json_encode(array('status'=>0));
        }
   	}
   	
  	 public function showUsers(){

        $username = I("post.name");

        $res = D("User")->where(array("username"=>$username))->find();
        if($res){
          $realname = $res['realname'];
          echo json_encode(array('status'=>1,'realname'=>$realname));
        }else{
          echo json_encode(array('status'=>0));
        }
   }

   private function gouche($username)   //购车基金转账判断是否同一条线
   {
        $I = M('integral');
        $D = M('user');

      $room=$D->field('id,path')->where('username="'.$username.'"')->find();
        $idata=$I->where('userid='.$_SESSION['id'])->find();
        $data=$D->field('id,paypassword,path')->where('id='.$_SESSION['id'])->find();
            
            $str=$data['path'];
            $arr=explode(',',$str);
            $str1=$room['path'];
            $arr1=explode(',',$str1);
            if(!in_array($room['id'],$arr)&&!in_array($data['id'],$arr1))
             {
                     echo "<script>alert('只有相同队伍才能互转！');location.href='/Home/wo/recharge.html'</script>";//不在同一条线上
                     die(); 
             }
   }
   //积分记录
   public function jjjl()
    {
//    	$jl = M('user')->where('id='.session('id'))->select();
//  	$nap['userid'] = $jl[0]['id'];
		$nap['userid'] = session(id);
        $nap['title']=array('like','%奖励%');
        $yy = M('income')->where($nap)->select();
        $this->assign('yy',$yy);
		$this->display();
     }
     
     public function jh()
     {
     	
     	header("Content-Type: text/html;charset=utf-8");
     	$id = I('get.id');
     	$jh = M('user')->where('id='.$id)->find();
     	$username=$jh['username'];
     	$ac = M('user')->where("id='".session('id')."'")->find();
     	$D = M('user');
     	$I=M('integral');
     	$createtime=time();
     	if($ac['tohelp']==1){
     		$bq = M('integral')->where("userid='".session('id')."'")->find();
     		if($bq['integral2']>=10000){
     			//加入关系表
     			$T = M('tree');
                //取推荐人的number
                $pdata = M('user')->where("username='".$jh['tusername']."'")->find();
                $tu=$T->where('userid='.$pdata['id'])->order('id desc')->limit(1)->find();
               
     			 //分配新增用户的number
     			$this->addtree($id,$tu['number']);
     			
     			$update = array('status'=>1,'id'=>$jh['id']);
		     	M('user')->save($update);
		     	
		     	$reb=$bq['integral2']-10000;
				$updata = array('integral2'=>$reb);
			    M('integral')->where("userid={$_SESSION['id']}")->save($updata);
			    
			     $this->Pay_history($_SESSION['id'],'注册'.$username.'会员，消耗注册积分',10000,time(),1,2);
                //推荐人奖励２０００
                $eq = M('integral')->where('userid='.$pdata['id'])->find();
	    	        $bc = $eq['integral1']+2000;
		        	$ge = array('integral1'=>$bc);
		        	$I->where("userid=".$pdata['id'])->save($ge);
		        	
		            //$P=M('parameter');
       				//$pdata=$P->order('id asc')->select();
		            $this->Pay_history($pdata['id'],'推荐'.$username.'会员，奖励推广积分',2000,$createtime,1,1);
                    //服务中心500
		            $bbq = M('integral')->where("userid={$_SESSION['id']}")->find();
				    $ccq = $bbq['integral1']+500;
		            $updata2 = array('integral1'=>$ccq);
				    $bb = $I->where("userid={$_SESSION['id']}")->save($updata2);
				    
				    $this->Pay_history($_SESSION['id'],'激活'.$username.'会员，奖励推广积分',500,$createtime,1,1);
				    //极差奖
				    $this->userall($username,$pdata['username'],0,0,0);
				    
		     	echo "<script>alert('账号激活成功！');location.href='/Home/wo/zhjh.html'</script>";
     		}else{
     			echo "<script>alert('报单积分低于10000无法激活！');location.href='/Home/wo/tjlb.html'</script>";
     		}
     		
     	}else{
     		echo "<script>alert('暂时只能由报单中心方可激活！');location.href='/Home/wo/tjlb.html'</script>";
     	}
     	
     }
     
     
  //极差奖
    //$username推荐人
    //$curr 当前业绩，
    //$pre1 当前点数
    //$pre  剩余点数
    public function userall($username,$tusername,$curr,$pre,$pre1)
    {
        $D=M('user');
        $room=$D->where('username="'.$tusername.'"')->find();
       

        //判断是否是服务中心
        if($room['tohelp']==1)
        {
        	$total=$room['limit'];
	        $P=M('parameter');
	        $pdata=$P->order('id asc')->select();
	        $userid=$room['id'];
	        $temp=0;
	        $total=0;

	        $data=$D->field('count(1) as total')->where('locate(",'.$userid.',",path)>0')->select();


	        if($data[0]['total']<intval($pdata[0]['number']/10000))
	        {
                 

                //会员后台增加业绩
		        if($room['limit']/10000>=intval($pdata[0]['number']/10000)&&$room['limit']/10000<intval($pdata[2]['number']/10000))
		        {
		        	if($curr==0)
		        	{
		        		$price=10000*$pdata[1]['number'];
			            $pre=$pre+$pdata[1]['number'];
			            $pre1=$pdata[1]['number'];
		        	}
		        	else
		        	{
			        	if($room['limit']>=$curr)
			        	{
				        	$price=10000*($pdata[1]['number']-$pre1);
				            $pre=$pre+$pdata[1]['number'];
				            $pre1=$pdata[1]['number'];
			            }
			            else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[2]['number'];


		        }
		        elseif($room['limit']/10000>=intval($pdata[2]['number']/10000)&&$room['limit']/10000<intval($pdata[4]['number']/10000))
		        {
                    if($curr==0)
                    {
			            $price=10000*$pdata[3]['number'];
			            $pre=$pre+$pdata[3]['number'];
				        $pre1=$pdata[3]['number'];
		            }
		            else
		            {
		            	if($room['limit']>=$curr)
		        	    {
                           
	                           $price=10000*($pdata[3]['number']-$pre1);
				               $pre=$pre+($pdata[3]['number']-$pre);
				               $pre1=$pdata[3]['number'];

		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[4]['number'];


		        }elseif($room['limit']/10000>=intval($pdata[4]['number']/10000)&&$room['limit']/10000<intval($pdata[6]['number']/10000))
		        {
                    if($curr==0)
                    {
			            $price=10000*$pdata[5]['number'];
			            $pre=$pre+$pdata[5]['number'];
			            $pre1=$pdata[5]['number'];
			        }
		            else
		            {
		            	if($room['limit']>=$curr)
		        	    {

	                           $price=10000*($pdata[5]['number']-$pre1);
				               $pre=$pre+($pdata[5]['number']-$pre);
				               $pre1=$pdata[5]['number'];
		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
                    $total=$pdata[6]['number'];

		        }elseif($room['limit']/10000>=intval($pdata[6]['number']/10000)&&$room['limit']/10000<intval($pdata[8]['number']/10000))
		        {
		            if($curr==0)
                    {
			            $price=10000*$pdata[7]['number'];
			            $pre=$pre+$pdata[7]['number'];
			            $pre1=$pdata[7]['number'];
		            }
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {
                          
	                           $price=10000*($pdata[7]['number']-$pre1);
				               $pre=$pre+($pdata[7]['number']-$pre);
				               $pre1=$pdata[7]['number'];
		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }

                $total=$pdata[8]['number'];
		        }elseif($room['limit']/10000>=intval($pdata[8]['number']/10000)&&$room['limit']/10000<intval($pdata[10]['number']/10000))
		        {
		            if($curr==0)
                    {
			            $price=10000*$pdata[9]['number'];
			            $pre=$pre+$pdata[9]['number'];
			            $pre1=$pdata[9]['number'];
		            }
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {
                           
	                           $price=10000*($pdata[9]['number']-$pre1);
				               $pre=$pre+($pdata[9]['number']-$pre);
				               $pre1=$pdata[9]['number'];
		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		        $total=$pdata[10]['number'];
		        }elseif($room['limit']/10000>=intval($pdata[10]['number']/10000)&&$room['limit']/10000<intval($pdata[12]['number']/10000))
		        {
		            if($curr==0)
                    {
			            $price=10000*$pdata[11]['number'];
			            $pre=$pre+$pdata[11]['number'];
			            $pre1=$pdata[11]['number'];
		            }
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {

	                           $price=10000*($pdata[11]['number']-$pre1);
				               $pre=$pre+($pdata[11]['number']-$pre);
				               $pre1=$pdata[11]['number'];

		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		        $total=$pdata[12]['number'];
		        }elseif($room['limit']/10000>=intval($pdata[12]['number']/10000)&&$room['limit']/10000<intval($pdata[14]['number']/10000))
		        {
		            if($curr==0)
                    {
			            $price=10000*$pdata[13]['number'];
			            $pre=$pre+$pdata[13]['number'];
			            $pre1=$pdata[13]['number'];
		            } 
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {

	                           $price=10000*($pdata[13]['number']-$pre1);
				               $pre=$pre+($pdata[13]['number']-$pre);
				               $pre1=$pdata[13]['number'];

		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		        $total=$pdata[14]['number'];
		        }elseif($room['limit']/10000>=intval($pdata[14]['number']/10000)&&$room['limit']/10000<intval($pdata[16]['number']/10000))
		        {
		            if($curr==0)
                    {
			            $price=10000*$pdata[15]['number'];
			            $pre=$pre+$pdata[15]['number'];
			            $pre1=$pdata[15]['number'];
		            }
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {


	                           $price=10000*($pdata[15]['number']-$pre1);
				               $pre=$pre+($pdata[15]['number']-$pre);
				               $pre1=$pdata[15]['number'];
			     
		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		        $total=$pdata[16]['number'];
		        }elseif($room['limit']/10000>=intval($pdata[16]['number']/10000))
		        {
		           if($curr==0)
                    {
			            $price=10000*$pdata[17]['number'];
			            $pre=$pre+$pdata[17]['number'];
			            $pre1=$pdata[17]['number'];
			        }
                    else
		            {
		            	if($room['limit']>=$curr)
		        	    {

	                           $price=10000*($pdata[17]['number']-$pre1);
				               $pre=$pre+($pdata[17]['number']-$pre);
				               $pre1=$pdata[17]['number'];

		        	    }
		        	     else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$room['limit'];
		        }

          
                if(!empty($price))
                {
			        if($room['limit']/10000>=intval($pdata[0]['number']/10000))
			        {
			            $I=M('integral');
			            $I->where('userid='.$userid)->setinc('integral1',$price);
			            $title='会员'.$username.'领导奖';
			            $createtime=time();
			            $this->Pay_history($userid,$title,$price,$createtime,1,1);
			        }  
		        }  
	        }
	        elseif($data[0]['total']>=intval($pdata[0]['number']/10000)&&$data[0]['total']<intval($pdata[2]['number']/10000))
	        {
	                if($curr==0)
		        	{
		        		$price=10000*$pdata[1]['number'];
			            $pre=$pre+$pdata[1]['number'];
			            $pre1=$pdata[1]['number'];
		        	}
		        	else
		        	{
			        	if($data[0]['total']*10000>=$curr)
			        	{
				        	$price=10000*($pdata[1]['number']-$pre1);
				            $pre=$pre+$pdata[1]['number'];
				            $pre1=$pdata[1]['number'];
			            }
			            else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=intval($pdata[2]['number']/10000);
	        }elseif($data[0]['total']>=intval($pdata[2]['number']/10000)&&$data[0]['total']<intval($pdata[4]['number']/10000))
	        {
	              if($curr==0)
                    {
			            $price=10000*$pdata[3]['number'];
			            $pre=$pre+$pdata[3]['number'];
			            $pre1=$pdata[3]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[3]['number']-$pre1);
				               $pre=$pre+($pdata[3]['number']-$pre);
				               $pre1=$pdata[3]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[4]['number'];
	        }elseif($data[0]['total']>=intval($pdata[4]['number']/10000)&&$data[0]['total']<intval($pdata[6]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[5]['number'];
			            $pre=$pre+$pdata[5]['number'];
			            $pre1=$pdata[5]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[5]['number']-$pre1);
				               $pre=$pre+($pdata[5]['number']-$pre);
				               $pre1=$pdata[5]['number'];

		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[6]['number'];
	        }elseif($data[0]['total']>=intval($pdata[6]['number']/10000)&&$data[0]['total']<intval($pdata[8]['number']/10000))
	        {
	           if($curr==0)
                    {
			            $price=10000*$pdata[7]['number'];
			            $pre=$pre+$pdata[7]['number'];
			            $pre1=$pdata[7]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[7]['number']-$pre1);
				               $pre=$pre+($pdata[7]['number']-$pre);
				               $pre1=$pdata[7]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[8]['number'];
	        }elseif($data[0]['total']>=intval($pdata[8]['number']/10000)&&$data[0]['total']<intval($pdata[10]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[9]['number'];
			            $pre=$pre+$pdata[9]['number'];
			            $pre1=$pdata[9]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[9]['number']-$pre1);
				               $pre=$pre+($pdata[9]['number']-$pre);
				               $pre1=$pdata[9]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[10]['number'];
	        }elseif($data[0]['total']>=intval($pdata[10]['number']/10000)&&$data[0]['total']<intval($pdata[12]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[11]['number'];
			            $pre=$pre+$pdata[11]['number'];
			            $pre1=$pdata[11]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[11]['number']-$pre1);
				               $pre=$pre+($pdata[11]['number']-$pre);
				               $pre1=$pdata[11]['number'];
  	        	        }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[12]['number'];
	        }elseif($data[0]['total']>=intval($pdata[12]['number']/10000)&&$data[0]['total']<intval($pdata[14]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[13]['number'];
			            $pre=$pre+$pdata[13]['number'];
			            $pre1=$pdata[13]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[13]['number']-$pre1);
				               $pre=$pre+($pdata[13]['number']-$pre);
				               $pre1=$pdata[13]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[14]['number'];
	        }elseif($data[0]['total']>=intval($pdata[14]['number']/10000)&&$data[0]['total']<intval($pdata[16]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[15]['number'];
			             $pre=$pre+$pdata[15]['number'];
			            $pre1=$pdata[15]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[15]['number']-$pre1);
				               $pre=$pre+($pdata[15]['number']-$pre);
				               $pre1=$pdata[15]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }
		            $total=$pdata[16]['number'];
	        }elseif($data[0]['total']>=intval($pdata[16]['number']/10000))
	        {
	            if($curr==0)
                    {
			            $price=10000*$pdata[17]['number'];
			             $pre=$pre+$pdata[17]['number'];
			            $pre1=$pdata[17]['number'];
		            }
		            else
		            {
		            	if($data[0]['total']*10000>=$curr)
		        	    {

	                           $price=10000*($pdata[17]['number']-$pre1);
				               $pre=$pre+($pdata[17]['number']-$pre);
				               $pre1=$pdata[17]['number'];
		        	    }
		        	    else
			            {
			            	if(!empty($room['tusername']))
                               $this->userall($username,$room['tusername'],$curr,$pre,$pre1);
			            }
		            }

		            $total=$data[0]['total'];
	            
	        }
	        if(!empty($price))
            {
		        if($data[0]['total']>=intval($pdata[0]['number']/10000))
		        {
		            $I=M('integral');
		            $I->where('userid='.$userid)->setinc('integral1',$price);
		            $title='会员'.$username.'领导奖';
		            $createtime=time();
		            $this->Pay_history($userid,$title,$price,$createtime,1,1);
		        }    
	        }
        }
        else
        {
        	$total=$curr;
        }
        if(!empty($room['tusername']))
            $this->userall($username,$room['tusername'],$total,$pre,$pre1);
       

    }
   
    /**
     * 前台搜索
     * @return json 返回搜索数据
     */
    public function swtjg()
    {
    	$keyword=I('post.k');
        $search='';


        if(!empty($keyword))
        {
            $search='_k='.$keyword;
        }
        $sqlw='1=1 ';

        $sqlw.=' and (username like "%'.$keyword.'%")';

        $room = M('user');
        $info=$room->order(" id desc")->where($sqlw)->find();
//      $sql = "select count(*) as total from tm_user where ".$sqlw;

//      $query = $room->query($sql);

	    echo json_encode($info);
    }
    
    public function zhjh()
     {
     	header("Content-Type: text/html;charset=utf-8");
     	$zi = M('user')->where("id=".session('id'))->find();
     	if($zi['tohelp']==1){
     		$us = M("user")->where("id=".session('id'))->find();
     		$wjh = M('user')->where("status = 0 and fwzx='".$us['username']."'")->count();
     		
     		$jh = M('user')->where("fwzx='".session('username')."'")->order("status asc,id desc")->select();
     		$this->assign('jh',$jh);
     		$this->assign('wjh',$wjh);
     		$this->display();
     	}else{
     		echo "<script>alert('暂时只能由报单中心方可激活!');location.href='/home/wo/wo.html'</script>";
     	}
     }

      public function jhzh()
     {
     	$id=I('get.id');

     	$I=M('integral');
     	$D=M('user');
     	$data=$D->where('id='.$id)->find();

     	$userid=session('id');
     	$integral=$I->where('userid='.$userid)->find();
     	$this->assign('integral',$integral);
     	$this->assign('data',$data);
     	$this->display();
     }

     public function dojhzh()
     {
     	$password=I('post.password');
     	$team=I('post.team');
     	$U=M('user');
     	$T=M('tree');
     	$I=M('integral');
     	$id=I('post.id');
     	$room=$U->where('id='.$id)->find();
     	$username=$room['username'];
     	$troom=$U->where('username="'.$room['tusername'].'"')->find();
     	$udata=$U->where('username="'.$team.'"')->find();
     	if(empty($udata))
     	{
     		echo"<script>alert('帐户不存在');history.go(-1);</script>";
     		die();
     	}
     	//判断是否是组长
     	$tdata=$T->where('userid='.$udata['id'].' and `out`=0')->find();
     	if(empty($tdata))
     	{
     			echo"<script>alert('帐户不是组长');history.go(-1);</script>";
     	  	    die();
     	}


     	$tdata1=$T->where('number='.intval($tdata['number']/2).' and `out`=0')->find();

     	if(!empty($tdata1))
     	{
     		echo"<script>alert('帐户不是组长');history.go(-1);</script>";
     		die();
     	}
     	$idata=$I->where('userid='.session('id'))->find();
     	if($idata['integral2']<10000)
     	{
     		echo"<script>alert('帐户注册积分不足，请联系客户充值');history.go(-1);</script>";
     		die();
     	}
        
        $number=$tdata['number'];


     	//判断六个位置是否为空
     	$arr=array();
     	$arr[0]=2*$number;
     	$arr[1]=2*$number+1;
     	$arr[2]=4*$number;
     	$arr[3]=4*$number+1;
     	$arr[4]=4*$number+2;
     	$arr[5]=4*$number+3;

        
        $t1=$T->field('count(1) as total')->where('number in('.$arr[0].','.$arr[1].','.$arr[2].','.$arr[3].','.$arr[4].','.$arr[5].')')->select();

        
        if(empty($t1))
        {
        	$nubmer=$arr[0];
        }
        else
        {
        	$number=$t1[0]['total'];
        	$number=$arr[$number];
        }
         

        $arr1=array('userid'=>$id,
                         'number'=>$number,
        );
        $T->add($arr1);
        $createtime=time();
        //生成奖金
        $update = array('status'=>1,'id'=>$id);
		     	M('user')->save($update);
		     	
		     	$reb=$idata['integral2']-10000;
				$updata = array('integral2'=>$reb);
			    M('integral')->where("userid={$_SESSION['id']}")->save($updata);
			   
                 $this->Pay_history($_SESSION['id'],'注册'.$username.'会员，消耗注册积分',10000,$createtime,0,2);


                //推荐人奖励２０００
                $eq = M('integral')->where('userid='.$troom['id'])->find();
	    	        $bc = $eq['integral1']+2000;
		        	$ge = array('integral1'=>$bc);
		        	$I->where("userid=".$troom['id'])->save($ge);
		        	$this->Pay_history($troom['id'],'推荐'.$username.'会员，奖励推广积分',2000,$createtime,1,1);
                    //服务中心500
		            $bbq = M('integral')->where("userid={$_SESSION['id']}")->find();
				    $ccq = $bbq['integral1']+500;
		            $updata2 = array('integral1'=>$ccq);
				    $bb = $I->where("userid={$_SESSION['id']}")->save($updata2);
				   
				    $this->Pay_history($_SESSION['id'],'激活'.$username.'会员，奖励推广积分',500,$createtime,1,1);
				    
				    //极差奖
				    $this->userall($username,$pdata['username'],0,0,0);
				    //判断是否出局
				    $this->treeout($udata['id']);
		     	echo "<script>alert('账号激活成功！');location.href='/Home/wo/zhjh.html'</script>";
     }



       //购车申请
     public function gcsq()
     {
     	$U=M('user');
     	$udata=$U->where('id='.session('id'))->find();
     	$this->assign('room',$udata);
     	$this->display();
     }
     public function dogcsq()
     {
     	header("Content-Type: text/html;charset=utf-8");
     	$username = I('post.username');
     	$realname = I('post.realname');
     	$by = I('post.by');
     	$time = I('post.time');
     	$mobile = I('post.mobile');
     	$createtime = time();


     	$I=M('integral');
     	$idata=$I->where('userid='.session('id'))->find();
     	if($idata['integral4']<12000)
     	{
     		echo "<script>alert('消费积分不足，不能提车!');history.go(-1);</script>";
     		die();
     	}else{
     		$ret = $idata['integral4']-12000;
     		$acc = array("integral4"=>$ret);
     		$I->where('userid='.session('id'))->save($acc);
     		$arr1 = array(
     			'username'=>session('username'),
     			'userid'=>session('id'),
     			'title'=>'成功提交购车申请，扣取12000消费积分',
     			'price'=>12000,
     			'createtime'=>time(),
     			'status'=>0,
     			'gotegary'=>4,
     			'total'=>$idata['integral4'],
     		);
     		$xh = M('income')->add($arr1);
     		
	     	$arr = array(
	     		"userid"=>session('id'),
	     		"username"=>$username,
	     		"realname"=>$realname,
	     		"by"=>$by,
	     		"time"=>$time,
	     		"mobile"=>$mobile,
	     		'status'=>0,
	     		'createtime'=>$createtime
	     	);
	     	M('bycar')->add($arr);
	     	echo "<script>alert('提交申请成功，我们会尽快为您审核!');location.href='/home/wo/wo.html'</script>";
     	}
     }
}
