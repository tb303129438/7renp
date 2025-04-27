<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends \Common\Controller\AdminController{
    public function index()
    {
        if($_SESSION['adminid']){


        $arr=array();

        $date=date('Y-m-d',time());
        $date=strtotime($date);
        $D=M('user');
        $udata=$D->field('count(1) as total')->select();
        $udata1=$D->field('count(1) as total')->where('status=1')->select();//已开通用户
        $udata2=$D->field('count(1) as total')->where('status=0')->select();//未开通用户
        $udata3=$D->field('count(1) as total')->where('createtime>'.$date)->select();//今日新增用户
        $arr['usertotal']=$udata[0]['total'];
        $arr['usertotal1']=$udata1[0]['total'];
        $arr['usertotal2']=$udata2[0]['total'];
        $arr['usertotal3']=$udata3[0]['total'];
        $I=M('income');
        $data=$I->field('sum(price) as total')->where('status=1 and gotegary=2')->select();
        $arr['income']=$data[0]['total'];

         $P=M('parameter');
         $pdata=$P->order('id asc')->select();
         $arr['usertotal4']=$udata3[0]['total']*$pdata[18]['number'];
          
         
        $udata4=$I->field('sum(price) as total')->where('status=1 and gotegary=1 and createtime>'.$date)->select();
        $udata5=$I->field('sum(price) as total')->where('status=1 and gotegary=4 and createtime>'.$date)->select();

        if(empty($udata4[0]['total']))
        {
             $arr['usertotal5']=0;
        }
        else
        {
             $arr['usertotal5']=$udata4[0]['total'];
        }
        if(empty($udata5[0]['total']))
        {
             $arr['usertotal6']=0;

        }
        else
        {
             $arr['usertotal6']=$udata5[0]['total'];
        }
        $this->assign('room',$arr);

        // $this->islogin();
           $this->display(); 
        }else{
            echo "<script>alert('请先登录');location.href='/Admin/login/index.html'</script>";
        }

        
    }

    //添加会员
    public function add()
    {
        $this->display();
    }

    //保存会员
    public function doadd()
    {
        $username=I('post.username');
        $password=md5(I('post.password'));
        $paypassword=md5(I('post.paypassword'));

        $password=$this->md5pw($password);
        $paypassword=$this->md5pw($paypassword);

        $tusername=I('post.tusername');
        $paper=I('post.paper');
        $weixin=I('post.weixin');
        $alipay=I('post.alipay');
        $mobile=I('post.mobile');
        $bank=I('post.bank');
        $bankname=I('post.bankname');
        $bankcode=I('post.bankcode');
        $createtime=time();

        $D=M('user');
        $arr=array(
            'username'=>$username,
            'password'=>$password,
            'paypassword'=>$paypassword,
            'tusername'=>$tusername,
            'paper'=>$paper,
            'weixin'=>$weixin,
            'alipay'=>$alipay,
            'mobile'=>$mobile,
            'bank'=>$bank,
            'bankname'=>$bankname,
            'bankcode'=>$bankcode,
            'createtime'=>$createtime,
            'status'=>1,
            'number'=>'',
            'gotegary'=>0,
        );
        $id=$D->add($arr);

        $data=$D->where('username="'.$tusername.'"')->find();
        if(empty($data))
        {
            $path='0,'.$id.',';
        }
        else
        {
            $path=$data['path'].$id.',';
        }

        $number=$this->donumber($id);
        $arr=array(
            'number'=>$number,
            'path'=>$path,
        );
        $D->where('id='.$id)->save($arr);
        echo"<script>alert('会员注册成功！');history.go(-1);</script>";
        die();

    }
    //生成会员编号
    private function donumber($id)
    {

        $number='A'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }

    //添加管理勋章
    public function adminadd()
    {
        $D=M('power');
        $data=$D->select();
        $arr=array();

        foreach($data as $key=>$vo)
        {
            if($vo['parentid']==0)
            {
                $arr[$key]=$vo;
            }
        }
       for($i=0;$i<count($arr);$i++)
       {
           $arr1=array();
           $j=0;//数据从零开始
           foreach($data as $key=>$vo) {


               if($vo['parentid']==$arr[$i]['id']&&$vo['parentid']!=0) {


                   $arr1[$j]=$vo;
                   $j++;


               }
           }


           $arr[$i]['sub']=$arr1;

       }


      $this->assign('room',$arr);



        $this->display();
    }


    //添加管理勋章
    public function adminedit()
    {

       
        $id=I('get.id');
        $A=M('admin');
        $adata=$A->where('id='.$id)->find();

        
        
        $str=$adata['power'];


        $D=M('power');
        $data=$D->order('id asc')->select();
        $arr=array();


        for($i=0;$i<count($data);$i++)
        {
            $sub=substr($str,$i,1);
            $data[$i]['power']=$sub;
        }

        foreach($data as $key=>$vo)
        {
            if($vo['parentid']==0)
            {
                $arr[$key]=$vo;
            }
        }
        for($i=0;$i<count($arr);$i++)
        {
            $arr1=array();
            $j=0;//数据从零开始
            foreach($data as $key=>$vo) {


                if($vo['parentid']==$arr[$i]['id']&&$vo['parentid']!=0) {


                    $arr1[$j]=$vo;
                    $j++;


                }
            }


            $arr[$i]['sub']=$arr1;

        }


        $this->assign('room',$arr);



        $this->assign('adata',$adata);

        $this->display();
    }

    //生成管理员
    public function doadminadd()
    {
        $power=I('post.power');
        $username=I('post.username');
        $password=I('post.password');
        $password=$this->md5pw($password);


        $realname=I('post.realname');
        $job=I('post.job');
        $U=M('admin');
        $udata=$U->field('id')->where('adminname="'.$username.'"')->find();
        if(!empty($udata))
        {
            echo"<script>alert('用户已经存在，请重新输入用户帐户！');history.go(-1);</script>";
            die();
        }


        $D=M('power');
        $data=$D->field('count(1) as total')->select();
        $total=$data[0]['total'];
        $str='';
        for($i=0;$i<$total;$i++)
        {
           $str.='0';
        }
        foreach($power as $vo)
        {
             $str=substr_replace($str,'1',$vo-1,1);
        }
        $arr=array(
            'adminname'=>$username,
            'password'=>$password,
            'realname'=>$realname,
            'job'=>$job,
            'power'=>$str,
            'createtime'=>time()
        );
        $U->add($arr);
        echo"<script>alert('添加管理员成功。');location.href='/admin/index/admin.html';</script>";
        die();


    }
    //修改管理员
    public function doadminedit()
    {
        $id=I('post.id');
        $power=I('post.power');
        $username=I('post.username');

        $password=I('post.password');

        $realname=I('post.realname');
        $job=I('post.job');
        $U=M('admin');
        $udata=$U->field('id,password')->where('id='.$id)->find();
        if(!empty($password))
        {
            $password=$this->md5pw($password);
        }
        else
        {
            $password=$udata['password'];
        }


        $D=M('power');
        $data=$D->field('count(1) as total')->select();
        $total=$data[0]['total'];
        $str='';
        for($i=0;$i<$total;$i++)
        {
            $str.='0';
        }
        foreach($power as $vo)
        {
            $str=substr_replace($str,'1',$vo-1,1);
        }
        $arr=array(
            'adminname'=>$username,
            'password'=>$password,
            'realname'=>$realname,
            'job'=>$job,
            'power'=>$str,
        );
        $U->where('id='.$id)->save($arr);
        echo"<script>alert('修改管理员成功。');location.href='/admin/index/admin.html';</script>";
        die();


    }

    //管理员列表
    public function admin()
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

            $search='&b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='&e='.$endtime;
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




        $sqlw.=' and (adminname like "%'.$keyword.'%" )';

        $room = M('admin');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_admin where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/admin?";


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

        $this->assign('keyword',$keyword);
        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }
    //修改密码
    public function password()
    {
        $this->display();
    }
    //处理修改密码
    public function dopassword()
    {
        $password=I('post.password');
        $newpassword=I('post.newpassword');

        $newpassword=$this->md5pw($newpassword);
        $userid=session('adminid');
        $D=M('admin');
        $data=$D->where('id='.$userid)->find();

        if(!$this->_checkPwd($password,$data['password']))
        {
            echo 2;
            die();

        }
        else
        {
            $arr=array(
                'password'=>$newpassword,
            );
            $D->where('id='.$userid)->save($arr);
            echo 1;
            die();

        }
        $this->display();
    }

    //修改密码
    public function user_password()
    {
        $id=I('get.id');
        $this->assign('id',$id);
        $this->display();
    }
    public function setting()
    {
        $D=M('setting');

        $data=$D->where('id=1')->find();
        $this->assign('setting',$data);
        $this->display();
    }
    public function dosetting()
    {
        $D=M('setting');
        $web_config=I('post.web_config');
        $tohelp=I('post.tohelp');
        $gethelp=I('post.gethelp');
        $arr=array('web_config'=>$web_config,
            'tohelp'=>$tohelp,
            'gethelp'=>$gethelp,
            );
        $D->where('id=1')->save($arr);
        echo 1;
        die();
    }

    //参数
    public function parameter()
    {
        $D=M('parameter');
        $data=$D->order('id asc')->select();
        $this->assign('room',$data);
        $this->display();
    }
    //处理参数
    public function doparameter()
    {
        $D=M('parameter');
        $arr=array();
        for($i=0;$i<=24;$i++)
        {
            $arr[$i]=I('post.number'.$i);
        }

        foreach($arr as $key=>$vo)
        {

            $arr1=array(
                'number'=>$vo
            );
            $i=$key+1;
            $D->where('id='.$i)->save($arr1);
        }


        $pdata=$D->order('id asc')->select();
        F('para',$pdata);




        echo "<script>alert('修改奖金参数成功！');history.go(-1);</script>";
        die();

        //$data=$D->select();
        //$this->assign('room',$data);
    }
    //注册新会员
	public function douseradd()
    {
            $lx = I('post.lx');
            $username = I('post.username');
            $username = "Mc".$username;
            $password = I('post.password');
            $paypassword = md5(I('post.paypassword'));
            $password=$this->md5pw(md5($password));
            $paypassword=$this->md5pw($paypassword);
            
            $fwzx = I('post.fwzx');
            $tusername = I('post.tusername');
            $realname = I('post.realname');
            $mobile = I('post.mobile');
            $paper = I('post.paper');
            
            $bankname = I('post.bankname');
            $bank = I('post.bank');
            $bankcode = I('post.bankcode');
            $weixin = I('post.weixin');
            $alipay = I('post.alipay');
            $address = I('post.address');
            $createtime = time();
            //判断支付密码是否正确
            $D = M('user');

            $data1 = $D->where('username="' . $username . '"')->find();
            if (!empty($data1)) {
                echo 2;//用户名已经存在，
                die();
            }
            //取得推荐人ＩＤ
            $pdata = $D->where('username="' . $tusername . '"')->find();    //获取推荐人
            if($tusername!=""){
                   if(!empty($pdata)){
                
                   }else{
                    echo 3;//推荐人不存在，
                    die();
                   }
            }
            $res = rand(1,9999);
            //判断手机号码
               $data2 = $D->where('mobile="' . $mobile . '"')->find();
               if (!empty($data2)) {
                   echo 4;//手机号码已经存在，
                   die();
               }
               
            if($lx==2)  
            {
                if(empty($fwzx))
                {
                    echo 10;    //报单中心不存在，
                    die();
                }
                //报单中心不存在
                if($fwzx!=""){
                	$fwzx1 = $D->where('username="' . $fwzx . '"')->find();	//获取服务中心
    		        if($fwzx1['tohelp'] != 1){
    		        	echo 10;	//服务中心不已经存在，
    		            die();
    		        }   
                }
            } 


            $T=M('tree');
            if($tusername=='')
            {
                
                $tdata=$T->select();
                if(!empty($tdata))
                 {
                    echo 3;
                    die();
                }

            }
            
            //判断身份证号码
            $I=M('integral');

            if($lx==1){
            	$ace = $D->max('number');
            	$number=$ace+1;
                $tohelp=1;
                $status=1;
            }else{
                $ace = $D->max('number');
                $number=$ace+1;
                $tohelp=0;
                $status=1;
                

            }
             if($tusername=='')
            {
                $status=1;
            }
            else
            {
                $status=0;
            }

            $arr=array(
                'username'=>$username,
                'password'=>$password,
                'paypassword'=>$paypassword,
                'tusername'=>$tusername,
                'realname'=>$realname,
                'mobile'=>$mobile,
                'gotegary'=>0,
                'status'=>$status,
                'paper'=>$paper,
                'number'=>$number,
                'createtime'=>$createtime,
                'bank'=> $bank,
                'bankname' => $bankname,
                'bankcode' => $bankcode,
                'weixin' => $weixin,
                'rounds'=>0,
                'alipay' => $alipay,
                'address' => $address,
                'fwzx'=>$fwzx,
                'tohelp'=>$tohelp
            );
            $id = $D->add($arr);

            //加入积分表
             $arri=array(
                'userid'=>$id,
                'integral1'=>0,
                'integral2'=>0,
                'integral3'=>0,
                'integral4'=>0,
                'integral5'=>0,
            );
            $I->add($arri);
            
            if($tusername=='')
            {
                   
                   $path='0,'.$id.',';
                   $arr=array('path'=>$path);
                   $D->where('id='.$id)->save($arr);
            }
            else
            {

                   $path=$pdata['path'].$id.',';
                   $arr=array('path'=>$path);
                   $D->where('id='.$id)->save($arr);
            }
            //加入记录表
            //
            //
             
         
            if($tusername=='')
            {
                
                $tdata=$T->select();
                if(empty($tdata))
                {
                    //添加关系表            
                    $array = array(
                        'userid'=>$id,
                    	'number'=>1
                    );  
                    $nid = M('tree')->add($array);
                }
            }
            else
            {
 
                // //推荐人奖励２０００
                //  $eq = M('integral')->where('userid='.$pdata['id'])->find();
                //     $bc = $eq['integral1']+2000;
                //     $ge = array('integral1'=>$bc);
                //     $I->where("userid=".$pdata['id'])->save($ge);
                //     $arra = array(
                //             'userid'=>$pdata['id'],
                //             'username'=>$pdata['username'],
                //             'title'=>'推荐'.$username.'会员，奖励推广积分',
                //             'price'=>2000,
                //             'createtime'=>$createtime,
                //             'status'=>1,
                //             'gotegary'=>1
                //             );

                //     M('income')->add($arra);
                   

                //     if(!empty($fwzx))
                //     {
                //         //服务中心500
                //         $bbq = M('integral')->where("userid=".$fwzx1['id'])->find();
                //         $ccq = $bbq['integral1']+500;
                //         $updata2 = array('integral1'=>$ccq);
                //         $bb = $I->where("userid=".$fwzx1['id'])->save($updata2);
                //         $arr = array(
                //                 'userid'=>$fwzx1['id'],
                //                 'username'=>$fwzx1['username'],
                //                 'title'=>'激活'.$username.'会员，奖励推广积分',
                //                 'price'=>500,
                //                 'createtime'=>$createtime,
                //                 'status'=>1,
                //                 'gotegary'=>1
                //                 );
                //         M('income')->add($arr);
                //     }


                     //极差奖
                    //$this->userall($pdata['username']);
           

                //取推荐人的number
                //$tu=$T->where('userid='.$pdata['id'].'  and `out`=0')->order('id desc')->limit(1)->find();


                 //分配新增用户的number
                //$this->addtree($id,$tu['number']);
                
            }
            
            echo 1;
    }

         //$number当前树的编号
    //$userid 用户ＩＤ
    public function addtree($userid,$number)
    {
        $T=M('tree');
         
            
        //判断上级是否出局
        if($number%2==0)//双数情况
        {

            $n1=intval($number/4);
            $n2=intval($number/2);
        }
        else
        {
            $n1=intval(($number-1)/4);
            $n2=intval(($number-1)/2);
        }   
        
        if($number==1)
        {

             
            $nn=$T->order('id desc')->limit(1)->find();
           
            if(empty($nn))
            {
                      $arr=array('userid'=>$userid,
                         'number'=>$number,
                        );
                       $T->add($arr);
                       $this->treeout($nn['userid']);
            }
            else
            {
                       $arr=array('userid'=>$userid,
                         'number'=>$nn['number']+1,
                        );
                       $T->add($arr);
                       
                       $this->treeout($nn['userid']);

            }
        }
        else
        {
           
           
            $data=$T->where('number='.$n1.' or number='.$n2)->order('id asc')->select();
            if($data[0]['out']==0)//第三层
            {
                
                $nn1=$number+1;
                $nn2=$number+2;
                $nn3=$number+3;
                $nn4=$nn1;
                $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                
                
                //判断出局
                $this->treeout($data[0]['userid']);

            }
            else
            {
                if($data[1]['out']==0)//第二层
                {
                    $nn1=2*$number;
                    $nn2=2*$number+1;
                    $nn3=2*$number+2;
                    $nn4=2*$number+3;
                    $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                    //判断出局
                    $this->treeout($data[1]['userid']);
                }
                else//第一层
                {
                    $nn1=4*$number;
                    $nn2=4*$number+1;
                    $nn3=4*$number+2;
                    $nn4=4*$number+3;

                    $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);

                     //出局人
                    $udata=$T->where('number='.$number)->find();
                    //判断出局
                    $this->treeout($udata['userid']);
                  
                    
                }
            }
        }
    }
           //判断出局
    public function treeout($userid)
    {
        
        $D=M('user');
        $T=M('tree');
        //取推荐人的number
                $tu=$T->where('userid='. $userid.' and `out`=0')->order('id desc')->limit(1)->find();
                
                if(!empty($tu))
                {
                    $arr[0]=$tu['number'];
                    $arr[1]=$tu['number']*2;
                    $arr[2]=$tu['number']*2+1;
                    $arr[3]=$tu['number']*4;
                    $arr[4]=$tu['number']*4+1;
                    $arr[5]=($tu['number']*2+1)*2;
                    $arr[6]=($tu['number']*2+1)*2+1;
                    
                   
                    for($i=0;$i<7;$i++)
                    {
                         
                        $temp=$T->where('number='.$arr[$i])->find();

                        if(empty($temp))
                        {
                            break;
                               
                        }
                        else
                        {


                               if($i==6)
                                {
                                   
                                   if($this->isout()==1)//是否满五轮，有推荐人继续复投
                                    {

                                        //出局
                                        $this->userout($userid,$arr[0]);
                                        $D->where('id='.$userid)->setinc('rounds',1);//轮数加１
                                    }
                                }

                                
                        }
                    }
                   
                }
    }
    public function inserttree($userid,$nn1,$nn2,$nn3,$nn4)
    {
        $T=M('tree');
        $nn=$T->where('number='.$nn1.' or number='.$nn2.' or number='.$nn3.' or number='.$nn4)->order('id desc')->find();
                    if(empty($nn))
                    {
                      $arr=array('userid'=>$userid,
                         'number'=>$nn1,
                        );
                       $T->add($arr);
                    }
                    else
                    {
                       $arr=array('userid'=>$userid,
                         'number'=>$nn['number']+1,
                        );
                       $T->add($arr);
                    }
    }
     

    //判断是否出局
    public function userout($id,$number)
    {
       //判断$number子节点下会有没有人
       $this->usernext($id,$number);
       $price=12000;
        //出局奖
       $I=M('integral');
       $I->where('userid='.$id)->setinc('integral1',$price);
       $title='出局奖';
       $createtime=time();
       $this->Pay_history($id,$title,$price,$createtime,1,1);

       $I->where('userid='.$id)->setdec('integral1',10000);
       $title='复投';
       $createtime=time();
       $this->Pay_history($id,$title,10000,$createtime,0,1);
       //左右推荐人出局奖
       //
       $this->userleft($id,$number);
    }

    //递归判断
    public function usernext($id,$number)
    {
        $numberl=$number*2;
        $numberr=$number*2+1;
        $T=M('tree');
        $tdata=$T->where('number='.$numberl)->find();

       
        if(empty($tdata))
        {
            $array = array(
              'userid'=>$id,
              'number'=>$numberl,
            );  
            $nid = M('tree')->add($array);
            return;
        }
        $tdata=$T->where('number='.$numberr)->find();

        if(empty($tdata))
        {
            $array = array(
              'userid'=>$id,
              'number'=>$numberr,
            );  
            $nid = M('tree')->add($array);

           
            return;
        }
        $this->usernext($id,$numberl);

    }
    
    // 右边出局奖
    public function userleft($id,$number)
    {
        
        if($number%2==0)
        {
            $number=$number+1;
        }
        else
        {
            $number=$number-1;
        }

        $numberl=$number*2;
        $numberr=$number*2+1;
        $price=6000;
        $createtime=time();
        $U=M('user');
        $my=$U->where('id='.$id)->find();
        
        $data=$U->where('id=(select userid from tm_tree where number='.$numberl.')')->find();
        if(!empty($data))
        {
            $data1=$U->where('username="'.$data['tusername'].'"')->find();
            $I=M('integral');
            $I->where('userid='.$data1['id'])->setinc('integral4',$price);
            $title='会员'.$my['username'].'出局,'.$my['username'].'会员产生消费积分';
            $createtime=time();
            $this->Pay_history($data1['id'],$title,$price,$createtime,1,4);
        }
        $data=$U->where('id=(select userid from tm_tree where number='.$numberr.')')->find();
        if(!empty($data))
        {
           
            $data1=$U->where('username="'.$data['tusername'].'"')->find();
            $I=M('integral');
            $I->where('userid='.$data1['id'])->setinc('integral4',$price);
            $title='会员'.$my['username'].'出局,'.$my['username'].'会员产生消费积分';
            $createtime=time();
            $this->Pay_history($data1['id'],$title,$price,$createtime,1,4);
        }

    }

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


    //我的团队
    public function member()
    {	
        $t=I('get.t');

        $keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';


        if(!empty($t))
        {
            $search='&t='.$t;
        }
        else
        {
            $search='&t=1';
        }

        if(!empty($keyword))
        {
            $search='&k='.$keyword;
        }

        if(!empty($begintime))
        {

            $search='&b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='&e='.$endtime;
            $endtime=strtotime($endtime);
        }




        if(empty($t))
        {
             $sqlw='1=1 and status>=1';
        }
        else
        {
            if($t==1)
               $sqlw='1=1 and tohelp=0 and status=1';
            elseif($t==2)
               $sqlw='1=1 and tohelp=1 and status=1';
            elseif($t==3)
                $sqlw='1=1 and status=0';
        }

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




        $sqlw.=' and (number like "%'.$keyword.'%" or username like "%'.$keyword.'%" or mobile like "%'.$keyword.'%" or realname like "%'.$keyword.'%" )';

        $room = M('user');



        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
  
        $sql = "select count(*) as total from tm_user where ".$sqlw;


        $query = $room->query($sql);




        $pageurl="/admin/index/member.html?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);


		$ben = M('user')->select();
		$this->assign('ben',$ben);
        $this->assign("page",$page);


        $this->assign("search",$search);

        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);

        $this->assign('keyword',$keyword);
        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }

    public function status()
    {
        $id=I('post.id');
        $status=I('post.status');
        $D=M('user');
        $arr=array('status'=>$status);
        $D->where('id='.$id)->save($arr);
        //得到帮助返回匹配例表
        if($status==3)
        {
            $TO=M('tohelp');
            $G=M('gethelp');
            $tdata=$TO->where('userid='.$id.' and status=1')->find();
            if(!empty($tdata))
            {
                $T=M('toget');
                $data=$T->where('toid='.$tdata['id'].' and status=1')->select();
                if(!empty($data))
                {

                    foreach($data as $vo)
                    {
                        $arr=array('status'=>0);
                        $G->where('id='.$vo['getid'])->save($arr);
                    }
                    $T->where('toid='.$tdata['id'].' and status=1')->delete();
                }
                $TO->where('userid='.$id.' and status=1')->delete();
            }
        }
        echo 1;
        die();
    }
	
	public function djeuser()
    {
        $id=I('post.id');
        $arr = array('status'=>0);
       	M('user')->where('id='.$id)->save($arr);
        echo 1;
        die();
    }


    public function tohelp()
    {
        $id=I('post.id');
        $status=I('post.status');
        $D=M('user');
        $arr=array('tohelp'=>$status);
        $D->where('id='.$id)->save($arr);
        $user=$D->field('mobile,username')->where('id='.$id)->find();

        //解冻短信
        if($status==1)
           $this->sms($user['mobile'],$user['username'],6);
        echo 1;
        die();


    }
    //推荐图谱
    public function team(){
        $str = '';
        $username = session('username');

        $D = M('user');
        $data = $D->where('tusername="0"')->select();

        $str .= '<ul id="tree" class="treeview">';
        foreach ($data as $vo) {

            $str .= '<li><span>'.$vo['username'].'</span>';
            $str .= $this->getsubuser($vo['username']);
            $str .= '</li>';
        }
         $str .= '</ul>';
        //$str.=$this->getsubuser($username);

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
                //$arr['username']=
                $str.='<li><span>'.$vo['username'].'</span>';
                $str.=$this->getsubuser($vo['username']);
                $str.='</li>';


            }
            $str.='</ul>';
        }


        return $str;



    }


    //购买排单币
    public function paidan()
    {

        $this->display();
    }
    //处理购买排单币　
    public function dopaidan()
    {


        $username=I('post.username');
        $price=I('post.price');
        $gotegary=I('post.gotegary');
        $D=M('user');
        $data=$D->where('username="'.$username.'"')->find();
        if(empty($data))
        {
            echo 2;
            die();
        }

        $I=M('integral');
        $idata=$I->where('userid='. $data['id'])->find();

        if($gotegary==1)
        {
            $arr=array(
                'integral3'=>$idata['integral3']+$price,
            );
            $I->where('userid='.$data['id'])->save($arr);
            //支入记录
            $title=$username.'充值'.$price.'排单币';
            $createtime=time();
            $this->Pay_history($data['id'],$title,$price,$createtime,1,3);
            //充值记录
            $this->case_history($data['id'],$price,1,1,$createtime,$title);
        }
        else {
            if ($idata['integral3'] < $price)
            {
                echo 3;
                die();
            }
            else
            {
                $arr=array(
                    'integral3'=>$idata['integral3']-$price,
                );
                $I->where('userid='.$data['id'])->save($arr);
                //支入记录
                $title=$username.'扣除'.$price.'排单币';
                $createtime=time();
                $this->Pay_history($data['id'],$title,$price,$createtime,0,3);
                //充值记录
                $this->case_history($data['id'],$price,4,1,$createtime,$title);
            }
        }
        echo 1;
        die();
    }
    //排单币对帐
    public function lpaidan()
    {



        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



        if(!empty($begintime))
        {

            $search='&b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='&e='.$endtime;
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
        $userid=session('userid');




        $sqlw.=' and gotegary=1';

        $room = M('buy_history');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/lpaidan?";


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }
    //购买激活码
    public function jihuoma()
    {

        $this->display();
    }
    //处理购买激活码
    public function dojihuoma()
    {
        $username = I('post.username');
        $total = I('post.total');
        $price = I('post.price');

        $gotegary = I('post.gotegary');

        $D = M('user');
        $data = $D->where('username="' . $username . '"')->find();
        if(empty($data))
        {
            echo 2;
            die();
        }


        $I = M('integral');
        $idata = $I->where('userid=' . $data['id'])->find();
        $userid = $data['id'];

        if ($gotegary == 1) {
            $arr = array(

                'integral4' => $idata['integral4'] + $price,
            );
            $I->where('userid=' . $userid)->save($arr);
            //支入记录
            $title = $username . '充值' . $price . '激活码';
            $createtime = time();
            $this->Pay_history($userid, $title, $total, $createtime, 1, 4);
            //激活码记录
            $this->case_history($userid, $price, 1, 2, $createtime, $title);
       }
        else
        {

            if($idata['integral4']<$price)
            {
                echo 3;
                die();
            }
            $arr = array(

                'integral4' => $idata['integral4'] - $price,
            );
            $I->where('userid=' . $userid)->save($arr);
            //支入记录
            $title = $username . '扣除' . $price . '激活码';
            $createtime = time();
            $this->Pay_history($userid, $title, $total, $createtime, 0, 4);
            //激活码记录
            $this->case_history($userid, $price, 4, 2, $createtime, $title);
        }
        echo 1;
        die();
    }
//激活码对帐
    public function ljihuoma()
    {



        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



        if(!empty($begintime))
        {

            $search='&b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='&e='.$endtime;
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
        $userid=session('userid');




        $sqlw.=' and gotegary=2';

        $room = M('buy_history');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/ljihuoma?";


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }


    //支付记录
    //$userid 会员ID
    //$title 内容
    //$createtime 创建时间
    //$price 消费价格
    //$status 消费状态 1 收入，0 支出
    //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户



    public function Pay_history($userid,$title,$price,$createtime,$status,$gotegary)
    {
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        switch($gotegary)
        {
           case 1:
             
                $total=$idata['integral1'];
            
           break;
           case 2:
            
                $total=$idata['integral2'];
            
           break;
           case 4:
            
                $total=$idata['integral4'];
            
           break;
        }
        $D=M('income');
        $arr=array(
            'userid'=>$userid,
            'title'=>$title,
            'price'=>$price,
            'createtime'=>$createtime,
            'status'=>$status,
            'gotegary'=>$gotegary,
            'total'=>$total,

        );
        $D->add($arr);
    }

    //购买记录
    //userid 用户名
    //price 花费价格
    //title 内容
    //status １购买　２转帐　３使用 4扣除
    //gotegary 1  排单币 2 激活码 3 静态用户　4动态用户


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



    public function pipei()
    {
        $T=M('tohelp');
        $G=M('gethelp');
        //１控制时间；
        $tdata=$T->field('id,userid,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select count(1) from tm_toget where toid=tm_tohelp.id) as number')->where('status=0')->limit(5)->select();

        for($i=0;$i<count($tdata);$i++)
        {

            //剩余金额的
            if($tdata[$i]['surplus']>0&&!empty($tdata[$i]['surplus']))
            {

                $pricet=$tdata[$i]['surplus'];
            }
            else
            {
                $pricet=$tdata[$i]['price'];
            }


            //相同金额的
            $gd=$G->field('id,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname')->where('price='.$pricet.' and status=0 and userid!='.$tdata[$i]['userid'])->limit(1)->select();

            if(empty($gd))
            {

                //不同金额的
                $gd=$G->field('id,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname')->where('price<'.$pricet.' and status=0 and userid!='.$tdata[$i]['userid'])->select();

                $price=0;

                foreach($gd as $vo)
                {
                    $price= $price + $vo['price'];


                    if($price > $tdata[$i]['price'])
                    {

                        break;
                    }

                }


            }
            else
            {

                $tdata[$i]['sub']=$gd;

            }
        }
      

        $gdata=$G->field('id,orderid,userid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select count(1) from tm_toget where getid=tm_gethelp.id) as number')->where('status=0')->limit(5)->select();
        for($i=0;$i<count($gdata);$i++)
        {
            //剩余金额的
            if($gdata[$i]['surplus']>0&&!empty($gdata[$i]['surplus']))
            {
                $priceg=$gdata[$i]['surplus'];
            }
            else
            {
                $priceg=$gdata[$i]['price'];
            }
            //相同金额的
            $gd1=$T->field('id,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname')->where('price='.$priceg.' and status=0 and userid!='.$gdata[$i]['userid'])->limit(1)->select();

            if(empty($gd1))
            {

                //不同金额的
                $gd1=$T->field('id,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname')->where('price<'.$priceg.' and status=0 and userid!='.$gdata[$i]['userid'])->select();

                $price=0;

                foreach($gd1 as $vo)
                {
                    $price= $price + $vo['price'];


                    if($price > $gdata[$i]['price'])
                    {

                        break;
                    }

                }


            }
            else
            {

                $gdata[$i]['sub']=$gd1;

            }
        }


        $this->assign('tdata',$tdata);
        $this->assign('gdata',$gdata);
        $this->display();
    }

    //处理匹配
    public function dopipei(){
        $action=I('post.action');
        switch($action)
        {
            case 1;
                $id=I('post.id');
                $str=I('post.str');
                $str=substr($str,0,strlen($str)-1);


                $arr=explode(",",$str);



                $D=M('toget');
                $createtime=time();
                $D->where('toid='.$id)->delete();


                $T=M('tohelp');
                $arrt=array('status'=>1,'begintime'=>$createtime);
                $T->where('id='.$id)->save($arrt);

                $G=M('gethelp');
                $arrg=array('status'=>1,'begintime'=>$createtime);



                //发送短信
                //提供帮助
                $udata=$T->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$id)->find();
                $this->sms($udata['mobile'],$udata['username'],1);
                //得到帮助


                foreach($arr as $vo)
                {
                    if(empty($vo))
                    {
                        break;
                    }
                    $G->where('id='.$vo)->save($arrg);
                    $arr1=array(
                        'toid'=>$id,
                        'getid'=>$vo,
                        'createtime'=>$createtime,
                        'status'=>1,
                    );
                    $D->add($arr1);

                    //发送短信
                    $gdata=$G->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$vo)->find();
                    $this->sms($gdata['mobile'],$gdata['username'],2);
                }
                echo 1;
                break;
            case 2:
                $id=I('post.id');
                $str=I('post.str');
                $str=substr($str,0,strlen($str)-1);

                $arr=explode(",",$str);

                $D=M('toget');
                $createtime=time();
                $D->where('getid='.$id)->delete();
                $T=M('gethelp');

                //if($gdata[0]['total']==$data['price'])
               // {
                    $arrt=array('status'=>1,'begintime'=>$createtime);

               // }
               // else
               // {
                  //  $arrt=array('surplus'=>$data['price']-$gdata[0]['total']);
               // }
                $T->where('id='.$id)->save($arrt);

                $G=M('tohelp');
                $arrg=array('status'=>1,'begintime'=>$createtime);


                //发送短信
                //提供帮助
                //发送短信
                $udata=$T->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$id)->find();

                $this->sms($udata['mobile'],$udata['username'],2);


                foreach($arr as $vo) {
                    if (empty($vo)) {
                        break;
                    }
                    $G->where('id=' . $vo)->save($arrg);
                    $arr1 = array(
                        'toid' =>  $vo,
                        'getid' =>$id,
                        'createtime' => $createtime,
                        'status' => 1,
                    );
                    $D->add($arr1);

                    //发送短信
                    $gdata=$G->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$vo)->find();
                    $this->sms($gdata['mobile'],$gdata['username'],1);

                }





                echo 1;
                break;
        }

    }
   //充值
    public function cash()
    {
        $this->display();
    }
    //处理　
    public function docash()
    {


        $username=I('post.username');
        $price=I('post.price');
        $integral=I('post.integral');
        $gotegary=I('post.gotegary');

        $D=M('user');
        $data=$D->where('username="'.$username.'"')->find();
        if(empty($data))
        {
            echo 2;
            die();
        }

        $I=M('integral');
        $idata=$I->where('userid='. $data['id'])->find();

        if($gotegary==1)
        {
            if($integral==1)
            {
                $arr=array(
                    'integral1'=>$idata['integral1']+$price,
                );
            }
            elseif($integral==2)
            {
                $arr=array(
                    'integral2'=>$idata['integral2']+$price,
                );
            }
            $I->where('userid='.$data['id'])->save($arr);
            //支入记录
            $createtime=time();
            if($integral==1) {
                $title = $username.'充值静态帐户'.$price.'成功';
                $this->Pay_history($data['id'],$title,$price,$createtime,1,1);
                //充值记录
                $this->case_history($data['id'],$price,1,3,$createtime,$title);
            }
            elseif($integral==2){
                $title = $username.'充值动态帐户'.$price.'成功';
                $this->Pay_history($data['id'],$title,$price,$createtime,1,2);
                //充值记录
                $this->case_history($data['id'],$price,1,4,$createtime,$title);
            }

        }
        else
        {




            if($integral==1)
            {

                if($idata['integral1']<$price)
                {
                    echo 3;
                    die();
                }
                $arr=array(
                    'integral1'=>$idata['integral1']-$price,
                );
            }
            elseif($integral==2)
            {
                if($idata['integral2']<$price)
                {
                    echo 4;
                    die();
                }
                $arr=array(
                    'integral2'=>$idata['integral2']-$price,
                );
            }
            $I->where('userid='.$data['id'])->save($arr);
            //支入记录
            $createtime=time();
            if($integral==1) {
                $title = $username.'扣除静态帐户'.$price.'成功';
                $this->Pay_history($data['id'],$title,$price,$createtime,0,1);
                //充值记录
                $this->case_history($data['id'],$price,4,3,$createtime,$title);
            }
            elseif($integral==2){
                $title = $username.'扣除动态帐户'.$price.'成功';
                $this->Pay_history($data['id'],$title,$price,$createtime,0,2);
                //充值记录
                $this->case_history($data['id'],$price,4,4,$createtime,$title);
            }
        }

        echo 1;
        die();
    }

    //静态与动态帐户充值对帐
    public function lcash()
    {



        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



        if(!empty($begintime))
        {

            $search='&b='.$begintime;
            $begintime=strtotime($begintime);
        }
        if(!empty($endtime))
        {

            $search.='&e='.$endtime;
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
        $userid=session('userid');




        $sqlw.=' and (gotegary=3 or gotegary=4)';

        $room = M('buy_history');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/lcash?";


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }


    //匹配记录
    public function record()
    {

        $keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



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
            $sqlw.=' and tm_tohelp.createtime>='.$begintime.' and tm_tohelp.createtime<'.($endtime+24*60*60);
        }
        elseif($begintime!='' and $endtime=='')
        {
            $sqlw.=' and tm_tohelp.createtime>='.$begintime.' and tm_tohelp.createtime<'.($begintime+24*60*60);
        }
        elseif($begintime=='' and $endtime!='')
        {
            $sqlw.=' and tm_tohelp.createtime>='.$endtime.' and tm_tohelp.createtime<'.($endtime+24*60*60);
        }



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;





        //$sqlw.=' and gotegary=2';

        $room = M('tohelp');


        $sqlw.=' and tm_tohelp.status>0 and ((select username from tm_user where id=tm_tohelp.userid) like "%'.$keyword.'%" or (select realname from tm_user where id=tm_tohelp.userid) like "%'.$keyword.'%")';


        //$info=$room->field('id,userid,orderid,price,begintime,status,createtime,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname')->order(" createtime desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();



        $info=$room->field('tm_tohelp.id,tm_tohelp.userid,tm_tohelp.orderid,tm_tohelp.price,tm_tohelp.status,tm_tohelp.createtime,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname,tm_gethelp.id as id1,tm_gethelp.userid as userid1,tm_gethelp.orderid as orderid1,tm_gethelp.price as price1,tm_gethelp.begintime as begintime1,tm_gethelp.status as status1,tm_gethelp.createtime as createtime1,tm_gethelp.pic,(select username from tm_user where id=tm_gethelp.userid) as username1,(select realname from tm_user where id=tm_gethelp.userid) as realname1,(select createtime from tm_toget where toid=tm_tohelp.id and getid=tm_gethelp.id) as begintime')->join('tm_toget on tm_tohelp.id=tm_toget.toid')->join('tm_gethelp on tm_toget.getid=tm_gethelp.id')->order(" tm_toget.createtime desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_tohelp where ".$sqlw;


        $query = $room->query($sql);

        $pageurl="/admin/index/record?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }

    //提供帮助列表
    public function record_view()
    {
       $id=I('get.id');
        $sqlw='1=1 ';
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $sqlw.=' and id='.$id;

        $room = M('tohelp');





        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id=tm_tohelp.userid) as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();



        $sql = "select count(*) as total from tm_tohelp where ".$sqlw;


        $query = $room->query($sql);

        $T=M('toget');
        $U=M('user');
        for($i=0;$i<count($info);$i++)
        {
            $tdata=$T->field('tm_gethelp.id,tm_gethelp.orderid,tm_gethelp.userid,tm_gethelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_gethelp.begintime,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select tusername from tm_user where id=tm_gethelp.userid) as tusername')->join('tm_gethelp on tm_gethelp.id=tm_toget.getid')->where('tm_toget.toid='.$info[$i]['id'])->select();
            for($j=0;$j<count($tdata);$j++)
            {
                $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
                $tdata[$j]['trealname']=$tdata1['realname'];
                $tdata[$j]['tmobile']=$tdata1['mobile'];
            }



            $info[$i]['sub']=$tdata;
        }








        $pageurl="/user/ldohelp?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);

        $this->assign("page",$page);




        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);





        $this->display();
    }

    //提供帮助
    public function tpipei()
    {

        $keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



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





        //$sqlw.=' and gotegary=2';

        $room = M('tohelp');


        $sqlw.=' and ((select username from tm_user where id=tm_tohelp.userid) like "%'.$keyword.'%" or (select realname from tm_user where id=tm_tohelp.userid) like "%'.$keyword.'%")';


        $info=$room->field('id,userid,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select count(1) from tm_toget where toid=tm_tohelp.id) as number')->order(" createtime desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_tohelp where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/tpipei?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }


    //得到帮助
    public function gpipei()
    {

        $keyword=I('get.k');

        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';



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



        $sqlw.=' and ((select username from tm_user where id=tm_gethelp.userid) like "%'.$keyword.'%" or (select realname from tm_user where id=tm_gethelp.userid) like "%'.$keyword.'%")';


        //$sqlw.=' and gotegary=2';

        $room = M('gethelp');





        $info=$room->field('id,userid,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select count(1) from tm_toget where getid=tm_gethelp.id) as number')->order("createtime desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_gethelp where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/index/gpipei?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }

    //手动匹配
    public function dpipei()
    {


        $id=I('get.id');
        $t=I('get.t');
        $this->assign('t',$t);
        $o=I('post.o');
        $this->assign('o',$o);

        if($t==1)
        {
            $T=M('tohelp');
        }
        else
        {
            $T=M('gethelp');
        }
        $data=$T->where('id='.$id)->find();
        $this->assign('data',$data);








        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =30;
        //$userid=session('userid');


        $sqlw='1=1 ';
        $sqlw.=' and price<='.$data['price'];

        $sqlw.=' and status=0 and userid!='.$data['userid'];



        if($t==1)
        {
            $room = M('gethelp');
            $info=$room->field('id,userid,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select count(1) from tm_toget where getid=tm_gethelp.id) as number')->order(" createtime asc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

            $sql = "select count(*) as total from tm_gethelp where ".$sqlw;
        }
        else
        {
            $room = M('tohelp');
            $info=$room->field('id,userid,orderid,price,begintime,status,createtime,surplus,(select username from tm_user where id=tm_tohelp.userid) as username,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select count(1) from tm_toget where toid=tm_tohelp.id) as number')->order(" createtime asc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

            $sql = "select count(*) as total from tm_tohelp where ".$sqlw;
        }







        $query = $room->query($sql);





        $pageurl="/admin/index/dpipei.html?id=". $id."&t=".$t."&";


        $count = $query[0]['total'];

        $this->assign("room",$info);


        $this->assign("total",$count);

        if($count%$pagesize==0)
            $this->assign("count",$count/$pagesize);
        else
            $this->assign("count",(int)floor($count/$pagesize)+1);

        $this->assign("page",$page);




        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);




        $this->display();
    }

    //处理手动匹配
    public function dodpipei()
    {
        $id=I('post.id');
        $subid=I('post.subid');


        $str='';
        foreach($subid as $vo)
        {
            $str.=$vo.',';
        }

        $str=substr($str,0,strlen($str)-1);



        $t=I('post.t');
        $o=I('post.o');

        $o=str_replace('_','&',$o);


        if($t==1)
        {
            $T=M('tohelp');
            $data=$T->where('id='.$id)->find();
            $G=M('gethelp');
			
			if($data['surplus']==0)
			{
				
				
				$gdata=$G->field('sum(price) as total')->where('id in('.$str.')')->select();
	
				if($gdata[0]['total']>$data['price'])
				{
					echo("<script>alert('匹配金额大于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
					die();
				}
                if($gdata[0]['total']<$data['price'])
                {
                    echo("<script>alert('匹配金额小于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
                    die();
                }
				$D=M('toget');
				$createtime=time();
				$D->where('toid='.$id)->delete();
	
	
	
				if($gdata[0]['total']==$data['price'])
				{
					$arrt=array('status'=>1,
                        'begintime'=>$createtime);

	
				}
				else
				{
					$arrt=array('surplus'=>$data['price']-$gdata[0]['total'],
                        'begintime'=>$createtime
                        );
				}
				$T->where('id='.$id)->save($arrt);
	
	
				$arrg=array('status'=>1);
	
	
				//发送短信
				//提供帮助
				$udata=$T->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$id)->find();
				$this->sms($udata['mobile'],$udata['username'],4);
	
	
				foreach($subid as $vo) {
					if (empty($vo)) {
						break;
					}
					$G->where('id=' . $vo)->save($arrg);
					$arr1 = array(
						'toid' => $id,
						'getid' => $vo,
						'createtime' => $createtime,
						'status' => 1,
					);
					$D->add($arr1);
	
					//发送短信
					$gdata=$G->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$vo)->find();
					$this->sms($gdata['mobile'],$gdata['username'],5);
				}
	
	
	
	
	
	
				echo("<script>alert('匹配成功！');location.href='/admin/index/pipei.html?".$o."';</script>");
				die();
			
		   }
		    else
			{
			    				
				
				$gdata=$G->field('sum(price) as total')->where('id in('.$str.')')->select();
	
				if($gdata[0]['total']>$data['surplus'])
				{
					echo("<script>alert('匹配金额大于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
					die();
				}
                if($gdata[0]['total']<$data['surplus'])
                {
                    echo("<script>alert('匹配金额小于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
                    die();
                }
				$D=M('toget');
				$createtime=time();
				//$D->where('toid='.$id)->delete();
	
	
	
				if($gdata[0]['total']==$data['surplus'])
				{
					$arrt=array('status'=>1,
					'surplus'=>0,
                        'begintime'=>$createtime
					);
	
				}
				else
				{
					$arrt=array('surplus'=>$data['surplus']-$gdata[0]['total'],
                        'begintime'=>$createtime
                        );
				}
				$T->where('id='.$id)->save($arrt);
	
	
				$arrg=array('status'=>1,
                    'begintime'=>$createtime
                    );
	
	
				//发送短信
				//提供帮助
				$udata=$T->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$id)->find();
				$this->sms($udata['mobile'],$udata['username'],4);
	
	
				foreach($subid as $vo) {
					if (empty($vo)) {
						break;
					}
					$G->where('id=' . $vo)->save($arrg);
					$arr1 = array(
						'toid' => $id,
						'getid' => $vo,
						'createtime' => $createtime,
						'status' => 1,
					);
					$D->add($arr1);
	
					//发送短信
					$gdata=$G->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$vo)->find();
					$this->sms($gdata['mobile'],$gdata['username'],5);
				}
	
	
	
	
	
	
				echo("<script>alert('匹配成功！');location.href='/admin/index/pipei.html?".$o."';</script>");
				die();
	
				
				
			}


        }
        else
        {


            $T=M('gethelp');
            $data=$T->where('id='.$id)->find();
            $G=M('tohelp');
			
			
			
			if($data['surplus']==0)
			{
				
				
				$gdata=$G->field('sum(price) as total')->where('id in('.$str.')')->select();
				
				
				
				if($gdata[0]['total']>$data['price'])
				{
					echo("<script>alert('匹配金额大于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
					die();
				}
                if($gdata[0]['total']<$data['price'])
                {
                    echo("<script>alert('匹配金额小于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
                    die();
                }
	
	
				$D=M('toget');
				$createtime=time();
				$D->where('getid='.$id)->delete();
	
	
				if($gdata[0]['total']==$data['price'])
				{
					$arrt=array('status'=>1,
					'surplus'=>0,
                        'begintime'=>$createtime);
	
				}
				else
				{
					$arrt=array('surplus'=>$data['price']-$gdata[0]['total'],'begintime'=>$createtime);
				}
				$T->where('id='.$id)->save($arrt);
	
	
				$arrg=array('status'=>1,'begintime'=>$createtime);
	
	
				//发送短信
				//提供帮助
				//发送短信
				$udata=$T->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$id)->find();
	
				$this->sms($udata['mobile'],$udata['username'],4);
	
	
				foreach($subid as $vo) {
					if (empty($vo)) {
						break;
					}
					$G->where('id=' . $vo)->save($arrg);
					$arr1 = array(
						'toid' =>  $vo,
						'getid' =>$id,
						'createtime' => $createtime,
						'status' => 1,
					);
					$D->add($arr1);
					//发送短信
					$gdata=$G->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$vo)->find();
					$this->sms($gdata['mobile'],$gdata['username'],5);
	
	
				}
				echo("<script>alert('匹配成功！');location.href='/admin/index/pipei.html?".$o."';</script>");
				die();
			}
			else
			{
								
				$gdata=$G->field('sum(price) as total')->where('id in('.$str.')')->select();
				if($gdata[0]['total']>$data['surplus'])
				{
					echo("<script>alert('匹配金额大于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
					die();
				}

                if($gdata[0]['total']<$data['surplus'])
                {
                    echo("<script>alert('匹配金额大于待匹配金额，请重新选择匹配金额！');history.go(-1);</script>");
                    die();
                }
	
	
				$D=M('toget');
				$createtime=time();
				//$D->where('getid='.$id)->delete();
	
	
				if($gdata[0]['total']==$data['surplus'])
				{
					$arrt=array('status'=>1,
					'surplus'=>0,
                        'begintime'=>$createtime
					);
	
				}
				else
				{
					$arrt=array('surplus'=>$data['surplus']-$gdata[0]['total'],'begintime'=>$createtime);
				}
				$T->where('id='.$id)->save($arrt);
	
	
				$arrg=array('status'=>1,'begintime'=>$createtime);
	
	
				//发送短信
				//提供帮助
				//发送短信
				$udata=$T->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$id)->find();
	
				$this->sms($udata['mobile'],$udata['username'],4);
	
	
				foreach($subid as $vo) {
					if (empty($vo)) {
						break;
					}
					$G->where('id=' . $vo)->save($arrg);
					$arr1 = array(
						'toid' =>  $vo,
						'getid' =>$id,
						'createtime' => $createtime,
						'status' => 1,
					);
					$D->add($arr1);
					//发送短信
					$gdata=$G->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$vo)->find();
					$this->sms($gdata['mobile'],$gdata['username'],5);
	
	
				}
				echo("<script>alert('匹配成功！');location.href='/admin/index/record.html';</script>");
				die();

				}




        }
    }


    //折单
    public function caidan()
    {
        $id=I('get.id');
        $t=I('get.t');
        $o=I('get.o');
        if($t==1)
        {
            $D=M('tohelp');
            $data=$D->where('id='.$id)->find();

        }
        else
        {
            $D=M('gethelp');
            $data=$D->where('id='.$id)->find();
        }


        $this->assign('room',$data);
        $this->assign('t',$t);
        $this->assign('o',$o);
        $this->display();
    }
    //处理折单
    public function docaidan()
    {
        $t=I('post.t');
        $id=I('post.id');
        $o=I('post.o');
        $price=I('post.price');
        $pricedec=0;
        if($t==1)
        {
            $D=M('tohelp');
            $data=$D->where('id='.$id)->find();


            $pricedec=$data['price']-$price;
            $arr=array('price'=>$price);
            $D->where('id='.$id)->save($arr);
            $arr=array('orderid'=>$data['orderid'],
                'userid'=>$data['userid'],
                'price'=>$pricedec,
                'createtime'=>$data['createtime'],
                'status'=>$data['status'],
                'begintime'=>$data['begintime'],
             );
            $D->add($arr);
        }
        else
        {
            $D=M('gethelp');
            $data=$D->where('id='.$id)->find();
            $pricedec=$data['price']-$price;
            $arr=array('price'=>$price);
            $D->where('id='.$id)->save($arr);
            $arr=array('orderid'=>$data['orderid'],
                'userid'=>$data['userid'],
                'price'=>$pricedec,
                'createtime'=>$data['createtime'],
                'status'=>$data['status'],
                'begintime'=>$data['begintime'],
            );
            $D->add($arr);
        }

        $o=str_replace('_','&',$o);

        if($t==1)
        {
            echo("<script>alert('拆分成功');location.href='/admin/index/tpipei.html?".$o."';</script>");
        }
        else
        {
            echo("<script>alert('拆分成功');location.href='/admin/index/gpipei.html?".$o."';</script>");
        }
        die();

    }
    //提供订单删除
    public function tpipeidel()
    {
        $id=I('get.id');
        $o=I('get.o');
        $D=M('tohelp');
        $D->where('id='.$id)->delete();
        echo("<script>alert('删除成功');location.href='/admin/index/tpipei.html?".$o."';</script>");
        die();
    }

    //提供订单删除
    public function gpipeidel()
    {
        $id=I('get.id');
        $o=I('get.o');
        $o=str_replace('_','&',$o);

        $D=M('gethelp');
        $D->where('id='.$id)->delete();
        echo("<script>alert('删除成功');location.href='/admin/index/gpipei.html?".$o."';</script>");
        die();
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





        $pageurl="/admin/index/news.html?";


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
        $this->assign('room',$room);
        $this->display();

    }


    public function message()
    {
    	
		$room = M('message')->field('id,title,content,createtime,status,hfcontent,(select username from tm_user where id=tm_message.userid) as username')->where('id')->select();

		$this->assign('room',$room);
        $this->display();
//      $t=$_REQUEST['t'];
//
//      $search='';
//
//      if(!empty($mobile))
//          $search.='&t='.$t;
//
//
//      $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
//      $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
//      $pagesize =10;
//
//      $sqlw='1=1 and ("'.$t.'"="" or title like "%'.$t.'%")';
//
//      $room = M('message');
//      //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
//      //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
//      //$memberid=session('userid');
//
//
//
//
//      $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();
//
//      $sql = "select count(*) as total from tm_message where ".$sqlw;
//
//
//      $query = $room->query($sql);
//
//
//
//
//
//      $pageurl="/admin/index/message.html?";
//
//
//      $count = $query[0]['total'];
//
//      $this->assign("room",$info);
//
//
//      $this->assign("total",$count);
//
//      if($count%$pagesize==0)
//          $this->assign("count",$count/$pagesize);
//      else
//          $this->assign("count",(int)floor($count/$pagesize)+1);
//
//      $this->assign("page",$page);
//
//
//      $this->assign("search",$search);
//
//      $this->assign("pageurl",$pageurl);
//      $this->assign('pagesize',$pagesize);
//
//
//      $this->assign('t',$t);

	

    }
    
    
    public function delmessage()
    {
    	$id=I('get.id');
        $D=M('message');
        $D->where('id='.$id)->delete();
        echo "<script>alert('删除成功！');location.href='/admin/index/message.html';</script>";
        die();
    }
    public function donews_add()
    {
        $title=I('post.title');
       // $content=htmlspecialchars(I('post.editor'));

        $content=I('post.editor');

        $D=M('news');
        $arr=array('title'=>$title,
            'content'=>$content,
            'createtime'=>time(),
            );
        $D->add($arr);
        echo("<script>alert('发布公告成功');location.href='/admin/index/news.html'</script>");
        die();
    }
    public function delnews()
    {
        $id=I('post.id');
        $D=M('news');
        $D->where('id='.$id)->delete();
        echo 1;
        die();
    }
    public function news_edit()
    {
        $id=I('get.id');
        $D=M('news');
        $data=$D->where('id='.$id)->find();
        $this->assign('room',$data);
        $this->display();
    }
    public function donews_edit()
    {
        $id=I('post.id');
        $title=I('post.title');
       // $content=htmlspecialchars(I('post.editor'));
        $content=I('post.editor');

        $D=M('news');
        $arr=array('title'=>$title,
            'content'=>$content,

        );
        $D->where('id='.$id)->save($arr);
        echo("<script>alert('修改公告成功');location.href='/admin/index/news.html'</script>");
        die();
    }


    public function product_add()
    {

        $D=M('cotegary');
        $str='{"citylist":[';
        $data=$D->where('parentid=0')->select();
        $num=count($data);

       
        for($i=0;$i<$num;$i++)
        {
             $str.=' {"p":"'.$data[$i]['title'].'"';
             $datasub=$this->cotegarysub($data[$i]['id']);
             if(!empty($datasub))
             {
                  $numsub=count($datasub);
                  $str.=',"c":[';
                  for($j=0;$j<$numsub;$j++)
                  {
                       $str.='{"n":"'.$datasub[$j]['title'].'"';
                       $datasubsub=$this->cotegarysub($datasub[$j]['id']);
                       if(!empty($datasubsub))
                       {
                           $numsubsub=count($datasubsub);
                           $str.=',"a":[';
                           for($h=0;$h<$numsubsub;$h++)
                           {
                             $str.='{"s":"'.$datasubsub[$h]['title'].'"},';
                           }
                           $str=substr($str,0,strlen($str)-1);
                           $str.=']';
                       }
                       $str.='},';
                  }
                  $str=substr($str,0,strlen($str)-1);
                  $str.=']';
             }
             $str.='},';
        }
        $str=substr($str,0,strlen($str)-1);
        //  $str.=']';

        //{"citylist":[ 
        //{"p":"日用品","c":[{"n":"洗衣粉","a":[{"s":"蓝白}]},{"n":"洗发水","a":[{"s":"润发},{"s":"美发}]}]}, 
        //{"p":"家居用品","c":[{"n":"沙发"},{"n":"餐桌"}]}]}
      
        // {"p":"前端课程","c":[{"n":"HTML5"},{"n":"CSS3","a":[{"s":"HTML"},{"s":"AJAX"}]},{"n":"JSON"}]},
        // {"p":"编程语言","c":[{"n":"C"},{"n":"C++"},{"n":"Python"},{"n":"PHP"},{"n":"JAVA"}]},
        // {"p":"数据库","c":[{"n":"Mysql"},{"n":"SqlServer"},{"n":"Oracle"},{"n":"Mssql"}]},

         $str.=']}';

        

        $this->assign('str',$str);
        $this->display();

    }

    public function cotegarysub($id)
    {
        $D=M('cotegary');
       
        $data=$D->where('parentid='.$id)->select();

        if(empty($data))
        {
            return '';
        }
        else
        {
            return $data;
        }
    }

    public function doproduct_add()
    {
        $productname=I('post.productname');
        $number=I('post.number');
        $mypic=I('post.img1url');
        $price=I('post.price');
        $origin=I('post.origin');
        $one=I('post.one');
        $horsepower=I('post.horsepower');
        $color=I('post.color');
        $pailiang=I('post.pailiang');
        $c1=I('post.c1');
        $c2=I('post.c2');
        $c3=I('post.c3');

        $content=$_POST['editorValue'];

        if($one==0){
             $D=M('product');
             $arr=array('productname'=>$productname,
            'number'=>$number,
            'pic'=>$mypic,
            'price'=>$price,
            'origin'=>$origin,
            'color'=>$color,
            'pailiang'=>$pailiang,
            'horsepower'=>$horsepower,
            'content'=>$content,
            'createtime'=>time(),
            'c1'=>$c1,
            'c2'=>$c2,
            'c3'=>$c3,
        );
            $D->add($arr);
           echo("<script>alert('添加产品成功!');location.href='/admin/index/product.html'</script>");
        }else{
            if($one==1){
                 $D=M('product');
                 $arr=array('productname'=>$productname,
                'number'=>$number,
                'pic'=>$mypic,
                'price'=>$price,
                'origin'=>$origin,
                'color'=>$color,
                'pailiang'=>$pailiang,
                'syqf' => 1,
                'horsepower'=>$horsepower,
                'content'=>$content,
                'createtime'=>time(),
                'c1'=>$c1,
                'c2'=>$c2,
                'c3'=>$c3,
                );
                $D->add($arr);
                echo("<script>alert('添加产品成功!');location.href='/admin/index/product.html'</script>");
            }elseif($one==2){
                $D=M('product');
                 $arr=array('productname'=>$productname,
                'number'=>$number,
                'pic'=>$mypic,
                'price'=>$price,
                'origin'=>$origin,
                'color'=>$color,
                'pailiang'=>$pailiang,
                'syqf' => 2,
                'horsepower'=>$horsepower,
                'content'=>$content,
                'createtime'=>time(),
                'c1'=>$c1,
                'c2'=>$c2,
                'c3'=>$c3,
                );
                $D->add($arr);
                echo("<script>alert('添加产品成功!');location.href='/admin/index/product.html'</script>");
            }
        }
       
        
    }


    public function product()
    {

//获取网址参数

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

        $this->assign("room",$info);


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


        $this->display();

    }
    public function product_edit()
    {
        $id=I('get.id');
        $o=I('get.o');
        $this->assign('o',$o);
        $D=M('product');
        $data=$D->where('id='.$id)->find();
        $this->assign('vo',$data);



        $D=M('cotegary');

        $str='{"citylist":[';
        $data=$D->where('parentid=0')->select();
        $num=count($data);

       
        for($i=0;$i<$num;$i++)
        {
             $str.=' {"p":"'.$data[$i]['title'].'"';
             $datasub=$this->cotegarysub($data[$i]['id']);
             if(!empty($datasub))
             {
                  $numsub=count($datasub);
                  $str.=',"c":[';
                  for($j=0;$j<$numsub;$j++)
                  {
                       $str.='{"n":"'.$datasub[$j]['title'].'"';
                       $datasubsub=$this->cotegarysub($datasub[$j]['id']);
                       if(!empty($datasubsub))
                       {
                           $numsubsub=count($datasubsub);
                           $str.=',"a":[';
                           for($h=0;$h<$numsubsub;$h++)
                           {
                             $str.='{"s":"'.$datasubsub[$h]['title'].'"},';
                           }
                           $str=substr($str,0,strlen($str)-1);
                           $str.=']';
                       }
                       $str.='},';
                  }
                  $str=substr($str,0,strlen($str)-1);
                  $str.=']';
             }
             $str.='},';
        }
        $str=substr($str,0,strlen($str)-1);

         $str.=']}';

        

        $this->assign('str',$str);





        $this->display();
    }

    public function doproduct_edit()
    {
        $id=I('post.id');
        $one = I('post.one');
        $productname=I('post.productname');
        $number=I('post.number');
        $mypic=I('post.img1url');
        $price=I('post.price');
        $origin=I('post.origin');
        $horsepower=I('post.horsepower');
        $color=I('post.color');
        $pailiang=I('post.pailiang');

        $o=I('post.o');
        $c1=I('post.c1');
        $c2=I('post.c2');
        $c3=I('post.c3');


        $o=str_replace('_','&',$o);


        $content=$_POST['editorValue'];

        $D=M('product');
        $arr=array('productname'=>$productname,
        	'syqf'=>$one,
            'number'=>$number,
            'pic'=>$mypic,
            'price'=>$price,
            'origin'=>$origin,
            'color'=>$color,
            'pailiang'=>$pailiang,
            'horsepower'=>$horsepower,
            'content'=>$content,
            'createtime'=>time(),
            'c1'=>$c1,
            'c2'=>$c2,
            'c3'=>$c3,
        );
        $D->where('id='.$id)->save($arr);
        echo("<script>alert('修改产品成功!');location.href='/admin/index/product.html?".$o."'</script>");
        die();
    }
    public function delproduct()
    {
        $id=I('post.id');
        $D=M('product');
        $D->where('id='.$id)->delete();
        echo 1;
        die();
    }

     public function delorder()
    {
        $id=I('post.id');
        $D=M('order_product');
        $D->where('orderid='.$id)->delete();
        $D=M('order_address');
        $D->where('orderid='.$id)->delete();
        $D=M('order');
        $D->where('id='.$id)->delete();
        echo 1;
        die();
    }

    //完善资料
    public function profile()
    {

        $userid=I('get.id');
        $D=M('user');
        $data=$D->where('id='.$userid)->find();
        
        $data1=$D->field('count(1) as total')->where('locate(",'.$userid.',",path)>0 and id!='.$userid)->select();
        $total=$data1[0]['total']*10000;
        $this->assign('total',$total);
        $this->assign('room',$data);
        $this->display();

    }

    public function doprofile()
    {
        $mobile=I('post.mobile');
        $email=I('post.email');
        $tel=I('post.tel');
        $address=I('post.address');
        $sex=I('post.sex');
        $real=I('real');
        $id=session('userid');
        $arr=array(
            'mobile'=>$mobile,
            'email'=>$email,
            'tel'=>$tel,
            'address'=>$address,
            'sex'=>$sex,
            'realname'=>$real,
        );
        $D=M('user');
        $D->where('id='.$id)->save($arr);
        echo"<script>alert('修改资料成功！');history.go(-1);</script>";
        die();


    }
    //ajax提交数据
    public function ajax()
    {
        $action=I('post.action');
        switch($action) {

            case 'checkinfo':

                $userid=I('id');
                $mobile=I('post.mobile');
                $weixin=I('post.weixin');
                $alipay=I('post.alipay');
                $address=I('post.address');
                $limit=I('post.limit');
                $arr=array(
                    'mobile'=>$mobile,
                    'weixin'=>$weixin,
                    'alipay'=>$alipay,
                    'address'=>$address,
                    'limit'=>$limit,
                );
                $D=M('user');
                $data=$D->field('mobile')->where('mobile='.$mobile.' and id!='.$userid)->find();
                if(!empty($data))
                {
                    echo 2;
                    die();
                }


                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'checkpaper':

                $userid=I('id');
                $realname=I('post.realname');
                $paper=I('post.paper');

                $arr=array(
                    'realname'=>$realname,
                    'paper'=>$paper,

                );
                $D=M('user');
                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'checkbank'://修改开户行

                $userid=I('post.id');
                $bankname=I('post.bankname');
                $bank=I('post.bank');
                $bankcode=I('post.bankcode');

                $arr=array(
                    'bankname'=>$bankname,
                    'bank'=>$bank,
                    'bankcode'=>$bankcode,

                );
                $D=M('user');
                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'admindelete'://修改开户行

                $userid=I('post.id');
                $D=M('admin');
                $D->where('id='.$userid)->delete();
                echo 1;
                die();
                break;
            case 'password'://修改登录密码

                $userid=I('post.id');

                $newpassword=md5(I('post.newpassword'));
                $D=M('user');

                $arr=array(
                    'password'=>$this->md5pw($newpassword),

                );

                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'paypassword'://修改安全密码

                $userid=I('post.id');

                $newpaypassword=md5(I('post.newpaypassword'));
                $D=M('user');

                $arr=array(
                    'paypassword'=>$this->md5pw($newpaypassword),

                );

                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;


        }
    }
    //会员登录
    public function dologin()
    {
    	
        $id=I('get.id');
        $D=M('user');
        $data=$D->where('id='.$id)->find();
        if(empty($data))
        {
            echo("<script>alert('无效参数！');history.go(-1);</script>");
            die();
        }
        else
        {
            session('admin_login',1);//0前台登录，1后台登录
            session('id',$data['id']);
            session('username',$data['username']);
            session('status',$data['status']);//0  未开通，１已开通　２休眠　３冻结　
            session('limit',$data['limit']);//投资额度
            session('logintime',time());//登录时间
            echo("<script>location.href='/user/';</script>");

            die();
        }
    }


    //冻结记录
    public function freeze()
    {
        $begintime=I('get.b');
        $endtime=I('get.e');
        $search='';

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





        $sqlw='1=1 and status=0  ';

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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;







        $room = M('freeze');





        $info=$room->field('id,userid,toid,integral1,integral2,gotegary,title,status,begintime,(select username from tm_user where id=tm_freeze.fromuserid) as fromusername,(select username from tm_user where id=tm_freeze.userid) as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_freeze where ".$sqlw;


        $query = $room->query($sql);
        $sql = "select sum(integral1+integral2) as total from tm_freeze where ".$sqlw;

        $integraltotal=$room->query($sql);

        $this->assign('integraltotal',$integraltotal[0]['total']);

        $pageurl="/admin/index/freeze?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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


        $this->assign("search",$search);

        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }

    //解冻奖金
    public function dofreeze()
    {
        $id=I('get.id');
        $o=I('get.o');
        $o=str_replace('_','&',$o);

        $D=M('freeze');
        $begintime=time();
        $endtime=15*24*3600;
        $data=$D->where('id='.$id)->find();


        if(!empty($data))
        {

                //解冻奖金，奖金到帐
                $this->freezein($data);
                //已经解冻,改变奖态
                $D->where('id='.$data['id'])->setinc('status',1);

        }
        echo "<script>alert('解冻成功！');location.href='/admin/index/freeze.html?".$o."'</script>";
        die();


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
        if($data['gotegary']==1)
        {
            $gotegary=1;
            $I->where('userid='.$data['userid'])->setinc('integral1',$data['integral1']);
            $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);
        }
        else
        {
            $gotegary=2;
            $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
            $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);
        }
        $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
        $gotegary=5;
        $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);

        $U=M('user');
        $user=$U->field('username,mobile')->where('id='.$data['userid'])->find();
        //解冻短信
        $this->sms($user['mobile'],$user['username'],3);

    }
    //发送短信
    //$mobile 手机号码，
    //$username 用户名
    //$gotegary 1 提供帮助，２得到帮助
    //发送短信
    public function sms($mobile,$username,$gotegary)
    {
        vendor('smsapi');

        //用户账号
        $uid = 'xxxs';
        //MD5密码
        $pwd = 'Nanyiwangji11';

        $api = new \SmsApi($uid,$pwd);


        $contentParam = array(

            'name'	=> $username
        );

//变量模板ID


        switch($gotegary)
        {
            case 1:
                $template = '394903';//提供帮助
                break;
            case 2:
                $template = '394903';//提供帮助
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
            case 6:
                $template = '396768';//审核排单
                break;
        }


//发送变量模板短信
        $result = $api->send($mobile,$contentParam,$template);

    }


     //删除会员
    public function delete()
    {
        $id=I('post.id');
        $U=M('user');
        $I=M('integral');
        $I->where('userid='.$id)->delete();
        $U->where('id='.$id)->delete();
        echo 1;
        die();
    }
    //删除记录
    public function deletehistory()
    {
        $id=I('post.id');
        $U=M('buy_history');
        $U->where('id='.$id)->delete();
        echo 1;
        die();
    }

    //清除缓存
    public function deldir() {

        header("Content-type: text/html; charset=utf-8");
        //清文件缓存
        $dirs = array('App/Runtime/');
        @mkdir('Runtime',0777,true);
        //清理缓存
        foreach($dirs as $value) {
            $this->rmdirr($value);
        }
        $success= '系统缓存清除成功！';
        $this->assign('success',$success);
        $this->display();
        
    }


    //处理方法
    public function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return @unlink($dirname);
        }
        $dir = dir($dirname);
        if($dir){
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }


    /**
     * 密码加密
     */
    function md5pw($passwd)
    {
        $ph = new \Think\PasswordHash();
        return $ph->HashPassword($passwd);

    }

    /**
     * 检测密码是否正确
     * @param $getPwd
     * @param $dataPasswd
     */
    function _checkPwd($getPwd,$dataPasswd)
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

    //订单列表
    public function order()
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



        $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;

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
       

        $room = M('order');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        // $memberid=session('userid');




        $info=$room->field('tm_order.id,tm_order.userid,tm_order.orderid,tm_order.productname,tm_order.price,tm_order.number,tm_order.createtime,tm_order.status,tm_order_address.kdgs,tm_order_address.kddh,(select pic from tm_product where id=(select productid from tm_order_product where tm_order_product.orderid=tm_order.id)) as pic,(select ticket from tm_product where id=(select productid from tm_order_product where tm_order_product.orderid=tm_order.id)) as ticket')->join('tm_order_address on tm_order.id=tm_order_address.orderid')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_order where ".$sqlw;


        $query = $room->query($sql);




        $pageurl="/admin/index/order.html?";


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


        $this->assign('begintime',date('Y-m-d',$begintime));

        $this->assign('endtime',date('Y-m-d',$endtime));



        $this->display();

    }

    public function order_view()
    {
         $id=I('get.id');
        $D=M('order');
        $data=$D->where('id='.$id)->find();
        $P=M('order_product');
        $pdata=$P->where('orderid='.$id)->find();
  
        $R=M('product');
        $product=$R->where('id='.$pdata['productid'])->find();
        $this->assign('product',$product);

        $A=M('order_address');
        $adata=$A->where('orderid='.$id)->find();

        $this->assign('data',$data);
        $this->assign('pdata',$pdata);
        $this->assign('adata',$adata);

        $this->display();
    }

    //退出
    public function logout()
    {
        session(null);
        echo"<script>location.href='/admin.php'</script>";
        die();

    }
    
	public function help_add()
	{
        $this->display();
    }
    
	public function dohelp_add()
    {
        $typename=I('post.typename');
        $fid=I('post.fid');
        $content=$_POST['editorValue'];
        $D=M('type');
         $arr=array(
            'fid'=>$fid,
            'typename'=>$typename,
            'content'=>$content,
            'createtime'=>time(),

        );
        $D->add($arr);
        echo("<script>alert('发表成功!');location.href='/admin/index/help.html'</script>");
        die();
    }
	

	 public function help()
    {
        $type = M('type');
        $num = $type->count();
        $pageSize = 10;
        $page = new \Think\Page($num, $pageSize);
        $start = $page->firstRow;
        $array = $type->limit("$start,$pageSize")->select();
        $this->assign('page',$page->show());
        $this->assign('array',$array);
        $this->display();
    }

	  //提供订单删除
    public function delhelp()
    {
        $id=I('post.typeid');
        $D=M('type');
        $D->where('typeid='.$id)->delete();
        echo 1;
        die();
    }

	    
     public function help_edit()
    {
        $id=I('get.typeid');
        $typename = I('get.typename');
        $D=M('type');
        $data=$D->where('typeid='.$id)->find();
        
        $type = M('type');
	    $array = $type->where("fid=0")->select();
	    $this->assign('array',$array);
	    
        $this->assign('room',$data);
        $this->display();
    }
    
    
    
     public function dohelp_edit()
    {
        $typeid=I('post.typeid');
        $fid=I('post.fid');
        $typename=I('post.typename');

        // $content=htmlspecialchars(I('post.editor'));
        $content=I('post.editor');

        $D=M('type');
        $arr=array(
            'fid'=>$fid,
            'typename'=>$typename,
            'content'=>$content,
            'datetime'=>$datetime,
        );
        $D->where('typeid='.$typeid)->save($arr);
        echo("<script>alert('修改公告成功');location.href='/admin/index/help.html'</script>");
        die();
    }
    
    public function cz()
    {
    	
    	$this->display();
    }
    public function czbdb()
    {
    	$this->display();
    }
    public function doczbdb()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$name=I('post.name');
    	$czbdb = I('post.czbdb');
    	$paypassword = md5(I('post.paypassword'));
   		$admin=M('user');
   		$info = $admin->where("id={$_SESSION['adminid']}")->find();
   		$datc = $admin->where('newnumber="' . $name . '"')->find();
// 		$apq = $datc['realname'];
//		$this->assign('apq',$apq);
		
   		if($datc){
   			
   		}else{
   			echo"<script>alert('注册积分账户填写错误，请核查');location.href='/Admin/index/czbdb.html'</script>";
   			die;
   		}
   		if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo"<script>alert('注册积分用户密码有误，请重新输入');location.href='/Admin/index/czbdb.html'</script>";
             die();
        }
        
   		$D=M('integral');
   		$res = $D->where("userid={$_SESSION['adminid']}")->find();
// 		$this->assign('res',$res);
   		
   		
   		$rew = $res['integral3']-$czbdb;
   		$update = array('integral3' =>$rew,'userid' =>$_SESSION['adminid']);
   		$D->where("userid={$_SESSION['adminid']}")->save($update);
   			
   		$ree = $D->where("userid=".$datc['id'])->find();
   		$rex = $ree['integral3'] + $czbdb;
   		$update1 = array('integral3' =>$rex,'userid' =>$datc['id']);
   		$D->where("userid=".$datc['id'])->save($update1);
   			
   			echo"<script>alert('充值到注册积分成功！');location.href='/Admin/index/czbdb.html'</script>";
    }
    public function docz()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$name=I('post.name');
    	$czje = I('post.czje');

        $chongzhi = I('post.chongzhi');
    	$createtime=time();
   		$admin=M('user');
   		$info = $admin->where("id={$_SESSION['adminid']}")->find();
   		$datc = $admin->where('username="' . $name . '"')->find();
// 		$apq = $datc['realname'];
//		$this->assign('apq',$apq);
		
   		if($datc){
   			
   		}else{
   			echo"<script>alert('账户填写错误，请核查');location.href='/Admin/index/cz.html'</script>";
   			die;
   		}

        if ($czje <= 0){
            if($chongzhi==1){
                $D=M('integral');
                $ree = $D->where("userid=".$datc['id'])->find();
                $rex = $ree['integral1'] + $czje;
                $update1 = array('integral1' =>$rex,'userid' =>$datc['id']);
                $D->where("userid=".$datc['id'])->save($update1);
                $arr = array(
                      'userid'=>$datc['id'],
                      'username'=>$datc['username'],
                      'title'=>$datc['username'].'会员，推广积分成功扣除',
                      'price'=>abs($czje),
                      'createtime'=>$createtime,
                      'status'=>0,
                      'gotegary'=>1,
                      'total'=>$rex,
                );
                M('income')->add($arr);
                 echo"<script>alert('扣除到推广积分成功！');location.href='/Admin/index/cz.html'</script>";
                }elseif($chongzhi==3){
                	$qqc = M('user')->where('username='.$name)->find();
                	if($qqc['tohelp']!=1){
                		echo "<script>alert('抱歉,普通会员暂不支持注册积分！');location.href='/Admin/index/cz.html'</script>";
                	}
                    $D=M('integral');
                    $ree = $D->where("userid=".$datc['id'])->find();
                    $rex = $ree['integral2'] + $czje;
                    $update1 = array('integral2' =>$rex,'userid' =>$datc['id']);
                    $D->where("userid=".$datc['id'])->save($update1);
                    $arr = array(
                        'userid'=>$datc['id'],
                        'username'=>$datc['username'],
                        'title'=>$datc['username'].'会员，注册积分成功扣除',
                        'price'=>abs($czje),
                        'createtime'=>$createtime,
                        'status'=>0,
                        'gotegary'=>2,
                         'total'=>$rex,
                        );
                    M('income')->add($arr);
                    echo"<script>alert('扣除注册积分成功！');location.href='/Admin/index/cz.html'</script>";
                }elseif($chongzhi==2){
                    $D=M('integral');
                    $ree = $D->where("userid=".$datc['id'])->find();
                    $rex = $ree['integral4'] + $czje;
                    $update1 = array('integral4' =>$rex,'userid' =>$datc['id']);
                    $D->where("userid=".$datc['id'])->save($update1);
                    $arr = array(
                        'userid'=>$datc['id'],
                        'username'=>$datc['username'],
                        'title'=>$datc['username'].'会员，消费积分成功扣除',
                        'price'=>abs($czje),
                        'createtime'=>$createtime,
                        'status'=>0,
                        'gotegary'=>4,
                         'total'=>$rex,
                    );
                    M('income')->add($arr);
                    echo"<script>alert('扣除消费积分成功！');location.href='/Admin/index/cz.html'</script>"   ;
                } 
            }else{
                if($chongzhi==1){
                    $D=M('integral');  
                    $ree = $D->where("userid=".$datc['id'])->find();
                    $rex = $ree['integral1'] + $czje;
                    $update1 = array('integral1' =>$rex,'userid' =>$datc['id']);
                    $D->where("userid=".$datc['id'])->save($update1);
                    $arr = array(
                        'userid'=>$datc['id'],
                        'username'=>$datc['username'],
                        'title'=>$datc['username'].'会员，成功充值推广积分',
                        'price'=>$czje,
                        'createtime'=>$createtime,
                        'status'=>1,
                        'gotegary'=>1,
                        'total'=>$rex,
                    );
                    M('income')->add($arr);
                    echo"<script>alert('充值到推广积分成功！');location.href='/Admin/index/cz.html'</script>";
                }elseif($chongzhi==3){
//              	$qqc = M('user')->where('username='.$name)->find();
//              	if($qqc['tohelp']!=1){
//              		echo "<script>alert('抱歉,普通会员暂不支持报单积分！');location.href='/Admin/index/cz.html'</script>";
//              	}
                    $D=M('integral');
                    $ree = $D->where("userid=".$datc['id'])->find();
                    $rex = $ree['integral2'] + $czje;
                    $update1 = array('integral2' =>$rex,'userid' =>$datc['id']);
                    $D->where("userid=".$datc['id'])->save($update1);
                    $arr = array(
                        'userid'=>$datc['id'],
                        'username'=>$datc['username'],
                        'title'=>$datc['username'].'会员，成功充值注册积分',
                        'price'=>$czje,
                        'createtime'=>$createtime,
                        'status'=>1,
                        'gotegary'=>2,
                        'total'=>$rex,
                        );
                    M('income')->add($arr);
                    echo"<script>alert('充值到注册积分成功！');location.href='/Admin/index/cz.html'</script>";
                }elseif($chongzhi==2){
                    $D=M('integral');
                    $ree = $D->where("userid=".$datc['id'])->find();
                    $rex = $ree['integral4'] + $czje;
                    $update1 = array('integral4' =>$rex,'userid' =>$datc['id']);
                    $D->where("userid=".$datc['id'])->save($update1);
                    $arr = array(
                        'userid'=>$datc['id'],
                        'username'=>$datc['username'],
                        'title'=>$datc['username'].'会员，成功充值消费积分',
                        'price'=>$czje,
                        'createtime'=>$createtime,
                        'status'=>1,
                        'gotegary'=>4,
                        'total'=>$rex,
                    );
                    M('income')->add($arr);
                    echo"<script>alert('充值到消费积分成功！');location.href='/Admin/index/cz.html'</script>";
                }
            }
        
    }
    
    public function xj()
    {
    	$this->display();
    }
    public function doxj()
    {
        header("Content-Type: text/html;charset=utf-8");
        $name = I('post.name');
        $zzje = I('post.zzje');
        $paypassword = md5(I('post.paypassword'));
        $admin=M('user');
        $info = $admin->where("id={$_SESSION['adminid']}")->find();
        $datc = $admin->where('newnumber="' . $name . '"')->find();
        if($datc){
            
        }else{
            echo"<script>alert('转账账户错误，请核查');location.href='/Admin/index/gcjj.html'</script>";
            die;
        }
        if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo "<script>alert('交易密码错误，请核查');location.href='/Admin/index/gcjj.html'</script>";
             die();
        }
        $bbc=$admin->where('tohelp=1')->find();
        
        if($name==$bbc['newnumber']){
            $D=M('integral');
            $res = $D->where("userid={$_SESSION['adminid']}")->find();
            $rew = $res['integral1']-$zzje;
            if($zzje>$res['integral1']){
             echo "<script>alert('余额不足，请核查');location.href='/Admin/index/gcjj.html'</script>";
            }else{
            $update = array('integral1' =>$rew,'userid' =>$_SESSION['adminid']);
            $D->where("userid={$_SESSION['adminid']}")->save($update);
        
//          
            $reo = $D->where("userid={$_SESSION['adminid']}")->find();
            $rey = $reo['integral3']+$zzje;
            $update3 = array('integral3' =>$rey,'userid' =>$_SESSION['adminid']);
            $D->where("userid={$_SESSION['adminid']}")->save($update3);
            
            echo "<script>alert('转账成功，请核查');location.href='/Admin/index/gcjj.html'</script>";
            die;
            }
        }else{
            
        
            $D=M('integral');
            $res = $D->where("userid={$_SESSION['adminid']}")->find();
            $rew = $res['integral4']-$zzje;
            
            if($zzje>$res['integral4']){
                 echo "<script>alert('余额不足，请核查');location.href='/Admin/index/gcjj.html'</script>";
            }else{
                $update = array('integral4' =>$rew,'userid' =>$_SESSION['adminid']);
                $D->where("userid={$_SESSION['adminid']}")->save($update);
                
                $ccs = $D->where('userid='.$datc['id'])->find();
                $rdt = $ccs['integral4']+$zzje;
                $update1 = array('integral4'=>$rdt,'userid'=>$ccs);
                $rck = $D->where("userid=".$ccs['userid'])->save($update1);
            
            echo "<script>alert('转账成功，请核查');location.href='/Admin/index/gcjj.html'</script>";
            die;
            }
        }
    }
    public function gcjj()
    {
    	$D = M('integral');
    	$wrr = $D->where("userid={$_SESSION['adminid']}")->find();
    	$this->assign('wrr',$wrr);
    	$this->display();
    }
    public function dogcjj()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$name = I('post.name');
    	$zzje = I('post.zzje');
    	$paypassword = md5(I('post.paypassword'));
   		$admin=M('user');
   		$info = $admin->where("id={$_SESSION['adminid']}")->find();
   		$datc = $admin->where('newnumber="' . $name . '"')->find();
   		if($datc){
   			
   		}else{
   			echo"<script>alert('转账账户错误，请核查');location.href='/Admin/index/gcjj.html'</script>";
   			die;
   		}
   		if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo "<script>alert('交易密码错误，请核查');location.href='/Admin/index/gcjj.html'</script>";
             die();
        }
		$bbc=$admin->where('tohelp=1')->find();
		
		if($name==$bbc['newnumber']){
			$D=M('integral');
   			$res = $D->where("userid={$_SESSION['adminid']}")->find();
   			$rew = $res['integral4']-$zzje;
   			if($zzje>$res['integral4']){
   			 echo "<script>alert('余额不足，请核查');location.href='/Admin/index/gcjj.html'</script>";
   			}else{
   			$update = array('integral4' =>$rew,'userid' =>$_SESSION['adminid']);
   			$D->where("userid={$_SESSION['adminid']}")->save($update);
   		
// 			
   			$reo = $D->where("userid={$_SESSION['adminid']}")->find();
   			$rey = $reo['integral3']+$zzje;
   			$update3 = array('integral3' =>$rey,'userid' =>$_SESSION['adminid']);
   			$D->where("userid={$_SESSION['adminid']}")->save($update3);
   			
   			echo "<script>alert('转账成功，请核查');location.href='/Admin/index/gcjj.html'</script>";
   			die;
   			}
		}else{
			
        
	   		$D=M('integral');
	   		$res = $D->where("userid={$_SESSION['adminid']}")->find();
	   		$rew = $res['integral4']-$zzje;
	   		
	   		if($zzje>$res['integral4']){
	   			 echo "<script>alert('余额不足，请核查');location.href='/Admin/index/gcjj.html'</script>";
	   		}else{
	   			$update = array('integral4' =>$rew,'userid' =>$_SESSION['adminid']);
	   			$D->where("userid={$_SESSION['adminid']}")->save($update);
	   			
	   			$ccs = $D->where('userid='.$datc['id'])->find();
	   			$rdt = $ccs['integral4']+$zzje;
	   			$update1 = array('integral4'=>$rdt,'userid'=>$ccs);
	   			$rck = $D->where("userid=".$ccs['userid'])->save($update1);
   			
   			echo "<script>alert('转账成功，请核查');location.href='/Admin/index/gcjj.html'</script>";
   			die;
   			}
		}
		
   		
    	
    }
    //现金币
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
   		$info = $admin->where("id={$_SESSION['adminid']}")->find();
   		$datc = $admin->where('newnumber="' . $name . '"')->find();
   		if($datc){
   			
   		}else{
   			echo"<script>alert('转账账户错误，请核查')</script>";
   		}
   		if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo"<script>alert('用户密码有误，请重新输入');location.href='/Admin/index/xjb.html'</script>";
             die();
        }
        
   		$D=M('integral');
   		$res = $D->where("userid={$_SESSION['adminid']}")->find();
   		$rew = $res['integral1']-$czje;
   		
   		if($czje>$res['integral1']){
   			 echo"<script>alert('转账失败,余额不足！');location.href='/Admin/index/xjb.html'</script>";
   		}else{
   			$upd = $D->where("userid={$_SESSION['adminid']}")->find();
			$update = array('integral1'=>$rew,'userid'=>$upd);
			$rbk = $D->where("userid={$_SESSION['adminid']}")->save($update);
   			
   			
   			$ccs = $D->where('userid='.$datc['id'])->find();
   			$rdt = $ccs['integral1']+$czje;
   			$update1 = array('integral1'=>$rdt,'userid'=>$ccs);
   			$rck = $D->where("userid=".$ccs['userid'])->save($update1);
   			echo"<script>alert('充值到注册积分成功！');location.href='/Admin/index/xjb.html'</script>";
   		}
    }
	//报单币
	public function bdb()
	{
		$this->display();
	}
    public function dobdb()
    {
    	header("Content-Type: text/html;charset=utf-8");
    	$name = I('post.name');
    	$zzje = I('post.zzje');
    	$paypassword = md5(I('post.paypassword'));
   		$admin=M('user');
   		$info = $admin->where("id={$_SESSION['adminid']}")->find();
   		$datc = $admin->where('newnumber="' . $name . '"')->find();
   		if($datc){
   			
   		}else{
   			echo"<script>alert('转账账户错误，请核查');location.href='/Admin/index/bdb.html'</script>";
   			die;
   		}
   		if($this->_checkPwd($paypassword,$info['paypassword'])){ 
        }else{
            echo"<script>alert('用户密码有误，请重新输入');location.href='/Admin/index/bdb.html'</script>";
             die();
        }
        
   		$D=M('integral');
   		$res = $D->where("userid={$_SESSION['adminid']}")->find();
   		$rew = $res['integral3']-$zzje;
   		
   		if($zzje>$res['integral3']){
   			 echo"<script>alert('转账失败,余额不足！');location.href='/Admin/index/bdb.html'</script>";
   		}else{
   			$upd = $D->where("userid={$_SESSION['adminid']}")->find();
			$update = array('integral3'=>$rew,'userid'=>$upd);
			$rbk = $D->where("userid={$_SESSION['adminid']}")->save($update);
   			
   			
   			$ccs = $D->where('userid='.$datc['id'])->find();
   			$rdt = $ccs['integral3']+$zzje;
   			$update1 = array('integral3'=>$rdt,'userid'=>$ccs);
   			$rck = $D->where("userid=".$ccs['userid'])->save($update1);
   			echo"<script>alert('转账到注册积分成功！');location.href='/Admin/index/bdb.html'</script>";
   		}
    }
    //提现记录
    public function txjl()
    {
      
       
        $keyword=I('get.k');
        $begintime=I('get.b');
        $endtime=I('get.e');
        $zl = I("get.zl");//值
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
        if($zl==0){
            $sqlw .= '';
        }else{
            $sqlw .= ' and gotegary='.$zl;
        }
        
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




        //$sqlw.=' and (userid like "%'.$keyword.'%" or title like "%'.$keyword.'%")';


        $room = M('tx');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        //$memberid=session('userid');




        $info=$room->alias('s')->field('s.*,u.username,u.bankcode,u.bank,u.bankaddress,u.mobile,u.realname')->JOIN('left join tm_user as u on s.userid=u.id')->order(" s.id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

       

        $sql = "select count(*) as total from tm_tx where ".$sqlw;

        $query = $room->query($sql);





        $pageurl="/admin/index/txjl.html?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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
        $this->display();
    }
    public function dotxjl_edit()
    {
        $userid = I('get.userid');
        $update = array('zt'=>1);
		
        M('tx')->where('id='.$userid)->save($update);
        echo "<script>alert('审核成功');location.href='/Admin/index/txjl.html'</script>";
    }
    public function deltxjl()
    {
        $id=I('post.id');
        $D=M('tx');
        $D->where('id='.$id)->delete();
        echo 1;
        die();
    }
    public function jjjl()
    {
		$keyword=I('get.k');
        $begintime=I('get.b');
        $endtime=I('get.e');
        $zl = I("get.zl");//值
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
        if($zl==0){
        	$sqlw .= '';
        }else{
        	$sqlw .= ' and gotegary='.$zl;
        }
		
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




        $sqlw.=' and (userid like "%'.$keyword.'%" or title like "%'.$keyword.'%")';


        $room = M('income');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        //$memberid=session('userid');




        $info=$room->field('tm_income.*,(select tm_user.username from tm_user where tm_user.id=tm_income.userid) as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_income where ".$sqlw;

        $query = $room->query($sql);





        $pageurl="/admin/index/jjjl.html?";


        $count = $query[0]['total'];

        $this->assign("room",$info);


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
        $this->display();
     }


     public function login()
     {
        $id = I('get.id');
        $D=M('user');
        $info=$D->where('id='.$id)->find();
        session('id',$info['id']);
        session('username',$info['username']);
        session('realname',$info['realname']);
        session('logintime',time());//登录时间
		echo("<script>location.href='/pc/index/index.html';</script>");
     }
     
     public function fkxq()
     {
     	$id = I('get.id');
//		$bc = M('message')->field('m.id as mid')->alias('m')->join('LEFT JOIN tm_message as c on c.id=m')->join('LEFT JOIN tm_user as u on m.userid=u.id')->where('m.id='.$id)->find();
     	$bc = M('message')->field('id,title,content,createtime,status,hfcontent,(select username from tm_user where id=tm_message.userid) as username')->where('id='.$id)->find();
     	$this->assign('bc',$bc);
     	$this->display();
     }
     public function hffkxq()
    {
    	$id = I('post.id');
        $hfcontent = I('post.hfcontent');
        $update = array('hfcontent'=>$hfcontent,'status'=>2,'id'=>$id);
        M('message')->save($update);
        echo "<script>alert('回复成功');location.href='/Admin/index/message.html'</script>";
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
   
   	public function showMe()
   	{
   		$zl = I('post.zl');
   		$k = I("post.k");
   		$arr = array();
   		if($zl==0){
   			$ret = D('income')->where(array('username'=>$k))->select();
   		}else{
   			$ret = D('income')->where(array('gotegary'=>$zl,'username'=>$k))->select();
   		}
//		if(isset($k)){
//			$arr = array('gotegary'=>$zl,'username'=>$k);
//		}
		
		$ret = D('income')->where($arr)->select();
   		echo json_encode($ret);
   		
   	}


    public function sqbd()
    {
        $bd = M('bd')->where("tohelp=0")->order("createtime desc")->select();
        $this->assign('bd',$bd);
        $this->display();
    }
    public function dosqbd()
    {
        $id = I('get.id');
        $mm = M('bd')->where("id=".$id)->find();
        $ol = M('user')->where("id=".$mm['userid'])->find();
      

        $arr = array("tohelp"=>1);
        $ii = M('user')->where("id=".$ol['id'])->save($arr);
        

        $arr1 = array("tohelp"=>1,"id"=>$ol['id']);
        M('bd')->where("userid=".$mm['userid'])->save($arr1);
        
        echo "<script>alert('已审核成为报单中心');location.href='/admin/index/sqbd.html';</script>";
    }
    
    public function qxbd()
    {
        $id = I('get.id');
        $ll = M('user')->where("id=".$id)->find();
        $arr = array("tohelp"=>0);
        M('user')->where("id=".$id)->save($arr);
        
        echo "<script>alert('已改为普通会员');location.href='/admin/index/member.html?t=1';</script>";
    }
    public function ktbd()
    {
        $id = I('get.id');
        $ll = M('user')->where("id=".$id)->find();
        $arr = array("tohelp"=>1);
        M('user')->where("id=".$id)->save($arr);
        echo "<script>alert('已升级为报单中心');location.href='/admin/index/member.html?t=2';</script>";
    }
	   //购车申请
    public function gcsq()
    {
        $room = M("bycar")->select();
        $this->assign("room",$room);
        $this->display();
    }
    public function gcsqcx()
    {
        $id = I('get.id');
        $date = M('bycar')->where("id=".$id)->find();
        $this->assign('date',$date);
        $this->display();
    }
   public function gcsqcx_edit()
    {
        $pid = I('get.id');
        $arr = array("status"=>1);
        M('bycar')->where('id='.$pid)->save($arr);
        echo "<script>alert('审核成功');location.href='/Admin/index/gcsq';</script>";
    }
    public function gcsqcx_sb()
    {
        
            $gid = I('get.id');
        
            $arr = array("status"=>2);
            M('bycar')->where('id='.$gid)->save($arr);
            $data=M('bycar')->where('id='.$gid)->find();

            $user = M('user')->where('id='.$data['userid'])->find();

            $kf = M('integral')->where('userid='.$data['userid'])->find();
            $res = $kf['integral4']+16000;
            $arr = array('integral4'=>$res);
            M('integral')->where("userid=".$data['userid'])->save($arr);
            $arr1 = array(
                'username'=>$user['username'],
                'userid'=>$data['userid'],
                'title'=>'购车申请审核失败，收到16000购车基金',
                'price'=>16000,
                'createtime'=>time(),
                'status'=>1,
                'gotegary'=>4,
                'total'=>$kf['integral4'],
            );
            M('income')->add($arr1);
       
        echo "<script>alert('审核失败，积分已退还客户账户');location.href='/Admin/index/gcsq';</script>";
        die();
    }

   	public function delgcsq()
   	{
   		$id = I('get.id');
   		M('bycar')->where('id='.$id)->delete();
   		echo "<script>alert('删除成功');location.href='/admin/index/gcsq';</script>";
   	}
}
