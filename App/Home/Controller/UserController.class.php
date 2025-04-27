<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends \Common\Controller\UserController {
    public function index(){
        $domain=$_SERVER['SERVER_NAME'];//取得域名


        $domain=$_SERVER['HTTP_HOST'];
        $this->assign('domain',$domain);

        $userid=session('userid');
        $D=M('user');
        $data=$D->where('id='.$userid)->find();
        $this->assign('room',$data);
        $username=session('username');

        //直推数量
        $sql = "select count(1) as total from tm_user where tusername='".$username."'";
        $query =$D->query($sql);
        $anum=$query[0]['total'];

        $this->assign('anum',$anum);

        $sql = "select count(1) as total from tm_user where locate(',".$userid.",',path)>0 and id!=".$userid;
        $query =$D->query($sql);
        $bnum=$query[0]['total'];


        $this->assign('bnum',$bnum);


        $T=M('tohelp');
        $G=M('gethelp');
        $tdata=$T->where('userid='.$userid.' and (status>=0 and status<3)')->order('status desc,id desc')->select();
        $this->assign('tdata',$tdata);
        $gdata=$G->where('userid='.$userid.' and (status>=0 and status<3)')->order('status desc,id desc')->select();
        $this->assign('gdata',$gdata);


        $N=M('news');
        $news=$N->order('id desc')->limit(1)->find();
        $this->assign('news',$news);
        //得到当天的开放额度
        //$this->GetTodayPrice();
        //计算当前级别
        $this->GetGotegary();
        //解冻奖金
        $this->dofreeze();

        //统计动静态帐户
        $F=M('freeze');
        //静态冻结
        $fdata=$F->field('sum(integral1) as integral1')->where('status=0 and gotegary=1 and userid='.$userid)->select();
        //动态冻结
        $fdata1=$F->field('sum(integral1) as integral1')->where('status=0 and gotegary=2 and userid='.$userid)->select();
        $this->assign('f1',$fdata[0]['integral1']);
        $this->assign('f2',$fdata1[0]['integral1']);
        $this->display();
     }

    

    //计算当前的级别
    private function GetGotegary()
    {
        $D=M('user');
        $data=$D->field('count(1) as number')->where('tusername="'.session('username').'" and status=1 and (select count(1) from tm_tohelp where userid=tm_user.id and status=3)>0')->select();


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
    //我的钱包
    public function integral(){
        $userid=session('userid');
        $D=M('integral');
        $room=$D->where('userid='.$userid)->find();
        $this->assign('room',$room);
        $this->display();
    }

    //注册新会员
    public function add(){


        $userid=session('userid');
        $U=M('user');
        $room=$U->where('id='.$userid)->find();


        $this->assign('room',$room);

        $I=M('integral');
        $int=$I->where('userid='.$userid)->find();
        $this->assign('int',$int);





        $this->display();
    }
    //返回１：用户名称已经存在
    //返回２：手机号码已经存在
    public function doadd()
    {


        $username = I('post.username');
        $password = md5(I('post.password'));
        $paypassword = md5(I('post.paypassword'));

        $password=$this->md5pw($password);
        $paypassword=$this->md5pw($paypassword);

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


        $userid = session('userid');
        $createtime = time();



        //判断支付密码是否正确
        $D = M('user');
        $data = $D->where('id=' . $userid)->find();
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
        //判断身份证号码
        $data3 = $D->where('paper="' . $paper . '"')->find();
        if (!empty($data3)) {
            echo 6;//手机号码已经存在，
            die();
        }
        //判断支付宝号码
        if(!empty($alipay))
        {
            $data4 = $D->where('alipay="' . $alipay . '"')->find();
            if (!empty($data4)) {
                echo 7;//支付宝已经存在，
                die();
            }
        }



        $arr=array(
            'username'=>$username,
            'password'=>$password,
            'paypassword'=>$paypassword,
            'tusername'=>$tusername,
            'realname'=>$realname,
            'mobile'=>$mobile,
            'createtime'=>$createtime,
            'gotegary'=>0,
            'status'=>0,

            'paper'=>$paper,
            'bankname'=>$bankname,
            'bank'=>$bank,
            'bankcode'=>$bankcode,
            'weixin'=>$weixin,
            'alipay'=>$alipay,
            'address'=>$address,

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

    //ajax提交数据
    public function ajax()
    {
        $action=I('post.action');
        switch($action) {
            case 'doprofile':

            break;
            case 'checkusername':
                $username = I('post.username');
                $D = M('user');
                $data = $D->field('id')->where("username='" . $username . "'")->find();
                if (empty($data))
                    echo '1';
                else
                    echo '0';
                break;
            case 'checkmobile':
                $mobile = I('post.mobile');
                $D = M('user');
                $data = $D->field('id')->where("mobile='" . $mobile . "'")->find();
                if (empty($data))
                    echo '1';
                else
                    echo '0';
                break;
            case 'deladdress':
                $id = I('post.id');
                $D = M('user_address');
                $D->where('id=' . $id)->delete();
                echo 1;
                die();
                break;
            case 'setdefault':
                $id = I('post.id');
                $D = M('user_address');
                $arr1=array('def'=>0);
                $D->where('1=1')->save($arr1);
                $arr=array('def'=>1);
                $D->where('id='.$id)->save($arr);
                echo 1;
                die();
                break;
            case 'tree':
                $userid = I('post.id');
                $D = M('user');
                $data = $D->where('id=' . $userid)->find();
                $room = array();
                $room[0] = $data;


                if (!empty($data['lid'])) {
                    $data1 = $D->where('id=' . $data['lid'])->find();
                    $room[1] = $data1;
               }

                if (!empty($data['rid'])) {
                    $data2 = $D->where('id=' . $data['rid'])->find();
                    $room[2] = $data2;
                }
                if (!empty($data1['lid'])) {
                    $data3 = $D->where('id=' . $data1['lid'])->find();
                    $room[3] = $data3;
                }
                if (!empty($data1['rid'])) {
                    $data4 = $D->where('id=' . $data1['rid'])->find();
                    $room[4] = $data4;
                }
                if (!empty($data2['lid'])) {
                    $data5 = $D->where('id=' . $data2['lid'])->find();
                    $room[5] = $data5;
                }
                if (!empty($data2['rid'])) {
                    $data6 = $D->where('id=' . $data2['rid'])->find();
                    $room[6] = $data6;
                }


                $this->ajaxReturn($room);
                die();
                break;
            case'tree_search':
                $username = I('post.username');
                $D = M('user');
                $data = $D->field('id')->where('username="' . $username . '"')->find();
                if (empty($data)) {
                    echo 0;
                } else {

                echo $data['id'];
                }
                die();

                break;
            case'get_count'://得到统计数据
                $id=I('post.id');
                $U=M('user');
                $udata=$U->field('id,username,createtime,(select levelname from tm_level where level=tm_user.level) as level,lid,rid,(select price from tm_touch where userid=tm_user.id) as price')->where('id='.$id)->find();
                $INC=M('income');
                //左边总业绩
                $data1=$INC->field('sum(price) as total')->where('sid in(select id from tm_user where locate(",'.$udata['lid'].',",path)>0) and reg=1')->select();

                if(empty($data1[0]['total']))
                    $lall=0;
                else
                   $lall=$data1[0]['total'];


               //右边总业绩
                $data2=$INC->field('sum(price) as total')->where('sid in (select id from tm_user where locate(",'.$udata['rid'].',",path)>0) and reg=1')->select();
                if(empty($data2[0]['total']))
                    $rall=0;
                else
                    $rall=$data2[0]['total'];

                //总业绩，减去对碰的业绩
                $lnew=$lall-$udata['price'];//左边新增业绩
                $rnew=$rall-$udata['price'];//右边新增业绩

                $arr=array(
                    'username'=>$udata['username'],
                    'id'=>$udata['id'],
                    'level'=>$udata['level'],
                    'createtime'=>date('Y-m-d',$udata['createtime']),
                    'lnew'=>$lnew,
                    'rnew'=>$rnew,
                    'lall'=>$lall,
                    'rall'=>$rall,

                );
                $this->ajaxReturn($arr);
                break;
            case 'concelcash':
                $id=I('post.id');
                $userid=session('userid');
                $D=M('cash');
                $data=$D->where('id='.$id)->find();
                $I=M('integral');
                $I->where('userid='.$userid)->setinc('integral1',$data['price']);
                $D->where('id='.$id)->delete();
                $title='撤消提现';
                $price=$data['price'];
                $createtime=time();
                $status=1;
                $this->Pay_history($userid,$title,$price,$createtime,$status,1,0,0);
                echo '1';
                break;
            case 'orderok':
                $id=I('post.id');
                $D=M('order');
                $arr=array('status'=>4);
                $D->where('id='.$id)->save($arr);
                echo 1;
                die();
                break;
            case 'deletecart':
                $id=I('post.id');
                $D=M('cart');
                $D->where('id='.$id)->delete();
                echo 1;
                die();
                break;
            case 'deleteallcart':

                $D=M('cart');
                $userid=session('userid');
                $D->where('userid='.$userid)->delete();
                echo 1;
                die();
                break;
            case 'checkinfo':

                $userid=session('userid');
                $mobile=I('post.mobile');
                $weixin=I('post.weixin');
                $alipay=I('post.alipay');
                $address=I('post.address');
                $arr=array(
                    'mobile'=>$mobile,
                    'weixin'=>$weixin,
                    'alipay'=>$alipay,
                    'address'=>$address,
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

                $userid=session('userid');
                $realname=I('post.realname');
                $paper=I('post.paper');

                $arr=array(
                    'realname'=>$realname,
                    'paper'=>$paper,

                );
                $D=M('user');
                $data=$D->where('paper="'.$paper.'" and id!='.$userid)->find();
                if(!empty($data))
                {
                    echo 2;
                    die();
                }



                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'checkbank'://修改开户行

                $userid=session('userid');
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
            case 'password'://修改登录密码

                $userid=session('userid');
                $password=I('post.password');
                $newpassword=md5(I('post.newpassword'));
                $newpassword=$this->md5pw($newpassword);

                $D=M('user');

                $password=md5($password);

                $data=$D->field('password')->where('id='.$userid)->find();


                if(!$this->_checkPwd($password,$data['password']))
                {
                    echo 2;
                    die();
                }


                $arr=array(
                    'password'=>$newpassword,

                );

                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'paypassword'://修改安全密码

                $userid=session('userid');
                $paypassword=I('post.paypassword');
                $newpaypassword=md5(I('post.newpaypassword'));
                $newpaypassword= $this->md5pw($newpaypassword);

                $D=M('user');

                $paypassword=md5($paypassword);
                $data=$D->field('paypassword')->where('id='.$userid)->find();


                if(!$this->_checkPwd($paypassword,$data['paypassword']))
                {
                    echo 2;
                    die();
                }


                $arr=array(
                    'paypassword'=>$newpaypassword,

                );

                $D->where('id='.$userid)->save($arr);
                echo 1;
                die();
                break;
            case 'pipeiok'://已经打款

//                if(session('admin_login')==0)
//                {
//                    $ld=F('login');
//                    foreach($ld as $vo)
//                    {
//                        if($vo['userid']==session('userid'))
//                        {
//                            if($vo['session_id']!=session_id())
//                            {
//                                echo '0';//重新登录
//                                die();
//                            }
//                        }
//                    }
//                }
                $id=I('post.id');
                $domain=$_SERVER['SERVER_NAME'];//取得域名
                // if($domain!='www.xxxs888.com')
                // {
                //     echo 3;
                //     die();
                // }
                // if($id!=session('userid'))
                // {
                //     echo 3;
                //     die();
                // }
                $pwd=md5(I('post.pwd'));
                $U=M('user');
                $user=$U->field('paypassword')->where('id='.session('userid'))->find();



                if(!$this->_checkPwd($pwd,$user['paypassword']))
                {
                    echo 4;
                    die();
                }


                $id1=I('post.id1');//toid
                $id2=I('post.id2');//getid
                $pic=I('post.pic');
                $T=M('toget');
                $D=M('tohelp');
                $G=M('gethelp');
                $tcount=$T->field('count(1) as number')->where('toid='.$id1)->select();
                $number=$tcount[0]['number']; //计算多少个配置
                if($number==1)//只有一个匹配的
                {
                    //得到帮助有多少个
                    $gcount=$T->field('count(1) as number')->where('getid='.$id2)->select();
                    $gnumber=$gcount[0]['number']; //计算多少个配置
                    if($gnumber==1)
                    {
                        $arr=array('status'=>2,
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

                    $arr=array('status'=>2,
					'pic'=>$pic);

                    $T->where('getid='.$id2.' and toid='.$id1)->save($arr);
                    $arrg=array(
                        'status'=>2,
                        'pic'=>$pic);
                    $G->where('id='.$id2)->save($arrg);
                }




                $this->lishi($id1,$id2);


                //确认已打款
                $udata=$G->field('(select username from tm_user where id=tm_gethelp.userid) as username,(select mobile from tm_user where id=tm_gethelp.userid) as mobile')->where('id='.$id2)->find();
                //暂时屏蔽
                $this->sms($udata['mobile'],$udata['username'],1);
                //发送提醒短信

                echo 1;
                die();

                break;
            case 'pipeifinish':

//
//                if(session('admin_login')==0)
//                {
//                    $ld=F('login');
//                    foreach($ld as $vo)
//                    {
//                        if($vo['userid']==session('userid'))
//                        {
//                            if($vo['session_id']!=session_id())
//                            {
//                                echo '0';//重新登录
//                                die();
//                            }
//                        }
//                    }
//                }

                $id=I('post.id');
                $domain=$_SERVER['SERVER_NAME'];//取得域名
               // if($domain!='www.xxxs888.com')
               //  {
               //      echo 3;
               //      die();
               //  }
               //  if($id!=session('userid'))
               //  {
               //      echo 3;
               //      die();
               //  }

                $pwd=md5(I('post.pwd'));
                $U=M('user');
                $user=$U->field('paypassword')->where('id='.session('userid'))->find();

                if(!$this->_checkPwd($pwd,$user['paypassword']))
                {
                    echo 4;
                    die();
                }


                $id=I('post.id1');//getid
                $id2=I('post.id2');//toid
                $T=M('tohelp');
                $G=M('gethelp');
                $D=M('toget');

                //$tcount=$T->field('count(1) as number')->where('toid='.$id)->select();

                //$number=$tcount[0]['number']; //计算多少个配置

                $data=$D->where('getid='.$id.' and toid='.$id2.' and status!=3')->find();
                $tdata=$T->where('id='.$data['toid'].' and status!=3')->find();
                $gdata=$G->where('id='.$id.'  and status!=3')->find();


                if(empty($data)||empty($tdata)||empty($gdata))
                {
                  echo 2;
                  die();
                }

                $arr=array('status'=>3);


                //提供帮助与得到帮助相等的情况
                if($gdata['price']==$tdata['price'])
                {
                    $G->where('id='.$id)->save($arr);

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

                    $T->where('id='.$data['toid'])->save($arr);


                }
                elseif($gdata['price']>$tdata['price'])//帮助金额骨大于提现金额
                {
//                    if($gdata['surplus']==0)
//                    {
//                        $G->where('id='.$id)->setinc('surplus',$gdata['price']-$tdata['price']);
//
//                    }
//                    elseif($gdata['surplus']<$gdata['price'])
//                    {
//                        if(($tdata['price']+$gdata['surplus'])<$gdata['price'])
//                        {
//                            $G->where('id='.$id)->setinc('surplus',$tdata['price']);
//                        }
//                        elseif(($tdata['price']+$gdata['surplus'])==$gdata['price'])
//                        {
//                             $G->where('id='.$id)->save($arr);
//                        }
//                    }
//                    else
//                    {
//                        $G->where('id='.$id)->save($arr);
//                    }



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

                    $T->where('id='.$data['toid'])->save($arr);



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

                //确认已打款
                $udata=$T->field('(select username from tm_user where id=tm_tohelp.userid) as username,(select mobile from tm_user where id=tm_tohelp.userid) as mobile')->where('id='.$id2)->find();
                //暂时屏蔽
                $this->sms($udata['mobile'],$udata['username'],2);


                echo 1;
                die();
                break;


            case 'configuser':
                $id=I('post.id');
                $userid=session('userid');


                $I=M('integral');
                $D=M('user');

                $data=$D->where('id='.$id)->find();

                if($data['status']==0)
                {
                    $idata=$I->where('userid='.$userid)->find();
                    if(empty($idata['integral4']))
                    {
                        echo 2;
                        die();
                    }
                    $arr=array('integral4'=>$idata['integral4']-1);
                    $I->where('userid='.$userid)->save($arr);
                    $arr=array('status'=>1);
                    $D->where('id='.$id)->save($arr);

                    $this->Pay_history($userid,'使用激活币',1,time(),0,4);

                    echo 1;
                    die();
                }
                else
                {
                    echo 1;
                    die();
                }
                break;

        }
    }

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
                $template = '394904';//提供帮助
                break;
            case 3:
                $template = '396004';//解冻
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
    //动态收益
    //静态奖
    //１，5小时内打款利息20%,5-12小时利息15——超时不打款，封号处理，只有一次解冻机会500元
    //2，静态收益扣10%,返商城重复消费积分

    //动态奖
    //1，领导奖：第一代 4%(普通会员) 第三代 2%直接３人（银卡会员）第五代　０.５，直推５人　（金卡会员）


    //用户动态奖金
//    public function incomefrom($userid,$price)
//    {
//
//        $D=M('user');
//        $data=$D->where('id='.$userid)->find();
//        //第一代
//        $data1=$D->where('username="'.$data['tusername'].'"')->find();
//
//
//        if(!empty($data1))
//        {
//            //$this->doincomefrom($data['username'],$data1['id'],$price,0.04);
//            //第三代
//
//
//            $str1=substr($data['path'],0,strlen($data['path'])-1);
//
//            $str=explode(',',$str1);
//
//
//            $num=count($str);
//
//
//            $n1=$num-1;
//            $j=1;
//            for($i=$n1;$i>1;$i--)
//            {
//
//                switch($j)
//                {
//                    case 1:
//                        $this->doincomefrom($data['username'],$str[$i-1],$price,0.04);
//
//                        break;
//                    case 3:
//                       if($data['gotegary']>=1) {
//                            $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.02);
//                        }
//                        break;
//                    case 5:
//                        if($data['gotegary']>=2) {
//                            $this->doincomefrom($data['username'], $str[$i - 1], $price, 0.005);
//
//                        }
//                        break;
//                    case 6:case 7:case 8:case 9:
//                       if($data['gotegary']>=3)
//                        {
//                            $this->doincomefrom($data['username'],$str[$i-1],$price,0.001);
//                        }
//                        break;
//                }
//                $j++;
//
//
//            }
//
//
//        }
//
//    }



    //$toid 来源订单
    //public function incomefrom()
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

        //判断是否已经产生过奖金，如果已经产生，就不再产生，没有产生就重新产生



        $to=$T->where('id='.$toid)->find();

        $F=M('freeze');
        $order=$F->where('userid='.$userid.' and orderid="'.$to['orderid'].'"')->find();
        if(!empty($order))
        {
            return;
        }
        $temp=$T->field('sum(price) as total')->where('orderid="'.$to['orderid'].'"')->select();
        $price=$temp[0]['total'];

        if($from['price']>=$price)
        {
            $price=$price;
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');
        $username=session('username');



        $sqlw.=' and (number like "%'.$keyword.'%" or username like "%'.$keyword.'%" or mobile like "%'.$keyword.'%" or realname like "%'.$keyword.'%" ) and tusername="'.$username.'"';

        $room = M('user');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_user where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/member?";


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
    //完善资料
    public function profile()
    {
    
        $userid=session('userid');
        $D=M('user');
        $data=$D->where('id='.$userid)->find();

        $this->assign('room',$data);
        $this->display();

    }
    //修改资料
    public function doprofile()
    {
        $mobile=I('post.mobile');

        $pr=I('post.pr');
        $ci=I('post.ci');
        $di=I('post.di');
        $address=I('post.address');

        $U=M('user');
        $id=session('userid');
        $data=$U->where('mobile="'.$mobile.'" and id!='.$id)->find();

        if(!empty($data))
        {
              echo 2;
              die();
        }

        $realname=I('realname');
        
        $arr=array(
            'mobile'=>$mobile,
            'realname'=>$realname,
            'province'=>$pr,
            'city'=>$ci,
            'dist'=>$di,
            'address'=>$address,
        );
        $D=M('user');
        $D->where('id='.$id)->save($arr);
        echo 1;
        die();


    }
    //修改密码
    public function password()
    {
        $this->display();

    }
    public function dopassword()
    {
        $password=md5(I('post.password'));
        $newpassword=md5(I('post.newpassword'));

        $newpassword=$this->md5pw($newpassword);
        $userid=session('userid');
        $D=M('user');
        $data=$D->where('id='.$userid)->find();
        if(!$this->_checkPwd($password,$data['password']))
        {
            echo"<script>alert('原密码不正确，请输入正确的原密码！');history.go(-1);</script>";
            die();

        }
        else
        {
           $arr=array(
               'password'=> $newpassword,
           );
            $D->where('id='.$userid)->save($arr);
            echo"<script>alert('修改密码成功！');history.go(-1);</script>";
            die();

        }
        $this->display();
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




    //生成20位订单号
    private function Createorderid($t)
    {
        $orderid= date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8).rand(1000,9999);
        //1充值　２订单支付　３注册支付
        $orderid=$t.$orderid;
        return $orderid;

    }

//    //购买排单币
//    public function paidan()
//    {
//        $userid=session('userid');
//        $D=M('integral');
//        $data=$D->where('userid='.$userid)->find();
//        $this->assign('room',$data);
//        $this->display();
//    }
    //处理购买排单币　
//    public function dopaidan()
//    {
//        $userid=session('userid');
//        $price=I('post.price');
//        $paypassword=md5(I('post.paypassword'));
//        $D=M('user');
//        $I=M('integral');
//        $idata=$I->where('userid='.$userid)->find();
//        $data=$D->field('paypassword')->where('id='.$userid)->find();
//        if($paypassword!=$data['paypassword'])
//        {
//            echo 2;
//            die();
//        }
//        if($idata['integral1']<$price)
//        {
//            echo 3;
//            die();
//        }
//        $arr=array(
//            'integral1'=>$idata['integral1']-$price,
//            'integral3'=>$idata['integral3']+$price,
//        );
//        $I->where('userid='.$userid)->save($arr);
//        //支入记录
//        $title='购买排单币';
//        $createtime=time();
//        $this->Pay_history($userid,$title,$price,$createtime,0,1);
//        //充值记录
//        $this->case_history($userid,$price,1,1,$createtime,$title);
//        echo 1;
//        die();
//    }

    //购买排单币
    public function tpaidan()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();
        $this->assign('room',$data);
        $this->display();
    }
    //处理购买排单币　
    public function dotpaidan()
    {
        $userid=session('userid');
        $price=I('post.price');
        $username=I('post.username');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $room=$D->field('id')->where('username="'.$username.'"')->find();
        if(empty($room))
        {
            echo 4;//帐户不存在
            die();
        }


        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();

        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            echo 2;//支付密码不对
            die();
        }
        if($idata['integral3']<$price)
        {
            echo 3;//余额不足
            die();
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
        $title='从'.session('username').'转入排单币';
        $this->case_history($room['id'],$price,2,1,$createtime,$title);
        echo 1;
        die();
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
    //排单币对帐
    public function lpaidan()
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and userid='.$userid.' and gotegary=1';

        $room = M('buy_history');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/lpaidan?";


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



//    //购买激活码
//    public function jihuoma()
//    {
//        $userid=session('userid');
//        $D=M('integral');
//        $data=$D->where('userid='.$userid)->find();
//        $this->assign('room',$data);
//        $this->display();
//    }
//    //处理购买激活码
//    public function dojihuoma()
//    {
//        $userid=session('userid');
//        $total=I('post.total');
//        $price=I('post.price');
//        $paypassword=md5(I('post.paypassword'));
//        $D=M('user');
//        $I=M('integral');
//        $idata=$I->where('userid='.$userid)->find();
//        $data=$D->field('paypassword')->where('id='.$userid)->find();
//        if($paypassword!=$data['paypassword'])
//        {
//            echo 2;//安全密码不对
//            die();
//        }
//        if($idata['integral1']<$total)
//        {
//            echo 3;//余额不足
//            die();
//        }
//        $arr=array(
//            'integral1'=>$idata['integral1']-$total,
//            'integral4'=>$idata['integral4']+$price,
//        );
//        $I->where('userid='.$userid)->save($arr);
//        //支入记录
//        $title='购买'.$price.'激活码';
//        $createtime=time();
//        $this->Pay_history($userid,$title,$total,$createtime,0,1);
//        //激活码记录
//        $this->case_history($userid,$price,1,2,$createtime,$title);
//        echo 1;
//        die();
//    }

    //购买激活码
    public function tjihuoma()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();
        $this->assign('room',$data);
        $this->display();
    }
    //处理激活码转帐　
    public function dotjihuoma()
    {
        $userid=session('userid');
        $price=I('post.price');
        $username=I('post.username');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $room=$D->field('id')->where('username="'.$username.'"')->find();
        if(empty($room))
        {
            echo 4;//帐户不存在
            die();
        }


        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            echo 2;//支付密码不对
            die();
        }
        if($idata['integral4']<$price)
        {
            echo 3;//余额不足
            die();
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
        $title='从'.session('username').'转入激活码';
        $this->case_history($room['id'],$price,2,2,$createtime,$title);
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and userid='.$userid.' and gotegary=2';

        $room = M('buy_history');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_buy_history where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/ljihuoma?";


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

    //奖金转换
    public function transfer()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();
        $this->assign('room',$data);
        $this->display();
    }
    //处理奖金转换
    public function dotransfer()
    {
        $userid=session('userid');
        $price=I('post.price');
        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            echo 2;//安全密码不对
            die();
        }
        if($idata['integral2']<$price)
        {
            echo 3;//余额不足
            die();
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

        echo 1;
        die();
    }
    //收支明细
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and userid='.$userid;

        $room = M('income');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_income where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/income?";


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
    //推荐图谱
    public function team()
    {


        $str='';
        $username=session('username');
        $str.=$this->getsubuser($username);
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

    //排单-提供帮助
    public function dohelp()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();
        $this->assign('room',$data);

        //设置开关
        $S=M('setting');
        $sdata=$S->field('tohelp')->where('id=1')->find();

        $this->assign('sdata',$sdata);


        $U=M('user');
        $udata=$U->field('tohelp')->where('id='.$userid)->find();
        $this->assign('tohelp',$udata['tohelp']);

        $para=F('para');

        $this->assign('para',$para);
        $this->display();
    }
    //处理-提供帮助
    public function dodohelp()
    {

//        if(session('admin_login')==0)
//        {
//            $ld=F('login');
//            foreach($ld as $vo)
//            {
//                if($vo['userid']==session('userid'))
//                {
//                    if($vo['session_id']!=session_id())
//                    {
//                        echo '0';//重新登录
//                        die();
//                    }
//                }
//            }
//        }


        $userid=session('userid');
        $price=I('post.price');
        $total=I('post.total');




        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            echo 2;//安全密码不对
            die();
        }
        if($idata['integral3']<$total)
        {
            echo 3;//余额不足
            die();
        }
        if($price>session('limit'))
        {
            echo 4;//余额不足
            die();
        }

        $T=M('tohelp');

        //$tda=$T->field('count(1) as total')->where('status=0 and userid='.$userid)->select();
       // if($tda[0]['total']>0)
       // {
          //  echo 5;
          //  die();
       // }




//        $date= date('Y-m-d',time());
//        $datebegin=strtotime($date);
//        $dateend=$datebegin+24*3600;
//        $tdata=$T->field('sum(price) as total')->where('createtime>='.$datebegin.' and createtime<'.$dateend)->select();
//        $P=M('parameter');
//        $arr=$P->where('id=3')->find();
//
//        $todaytohelpprice=$arr['number']-$tdata[0]['total'];




        $P=M('parameter');
        $arr=$P->where('id=3')->find();

        $todaytohelpprice=$arr['number'];


        if($todaytohelpprice<$price)
        {
            echo 5;
            die();
        }


        $createtime=time();

        $interest=$price*0.2;
        $replay=$interest*0.1;
        $interest=$interest-$replay;



        $td=$T->where('userid='.$userid.' and status=0 and price='.$price)->find();
        if(empty($td))
        {
            //写入排单
            $arr=array(
                'userid'=>$userid,
                'price'=>$price,
                'status'=>0,
                'begintime'=>$createtime,
                'createtime'=>$createtime,
                'interest'=> $interest,
                'replay'=> $replay,
            );

            $id=$T->add($arr);
            $orderid=$this->dodohelpnumber($id);
            $arr=array(
                'orderid'=>$orderid,
            );
            $T->where('id='.$id)->save($arr);


            $arr=array(
                'integral3'=>$idata['integral3']-$total,

            );
            $I->where('userid='.$userid)->save($arr);

            $P->where('id=3')->setdec('number',$price);

            //支入记录
            $title='排单使用排单币'.$total;

            $this->Pay_history($userid,$title,$total,$createtime,0,3);
            //激活码记录
            $this->case_history($userid,$total,3,1,$createtime,$title);
            session('tohelp',1);
        }
        else
        {
            echo 5;
            die();
        }

        //减去额度


        echo 1;
        die();
    }

    //提供帮助列表
    public function vdohelp()
    {
       $id=I('get.id');
       $sqlw=' id= '.$id;
        $userid=session('userid');
       $room = M('tohelp');
       $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id='.$userid.') as username')->where($sqlw)->find();
       $T=M('toget');
        $U=M('user');

         $tdata=$T->field('tm_gethelp.id,tm_gethelp.orderid,tm_gethelp.userid,tm_gethelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_gethelp.begintime,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname,(select tusername from tm_user where id=tm_gethelp.userid) as tusername')->join('tm_gethelp on tm_gethelp.id=tm_toget.getid')->where('tm_toget.toid='.$info['id'])->select();

        for($j=0;$j<count($tdata);$j++)
        {
            $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
            $tdata[$j]['trealname']=$tdata1['realname'];
            $tdata[$j]['tmobile']=$tdata1['mobile'];
        }


                $info['sub']=$tdata;

        $I=M('integral');
        $integral=$I->where('userid='.$userid)->find();
        $this->assign("integral",$integral);
        $this->assign("vo",$info);

        $this->display();
    }


    //提供帮助列表
    public function ldohelp()
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and userid='.$userid;

        $room = M('tohelp');





        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,interest,surplus,replay,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();



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


        $this->assign("search",$search);

        $this->assign("pageurl",$pageurl);
        $this->assign('pagesize',$pagesize);


        $this->assign('begintime',$begintime);
        $this->assign('endtime',$endtime);


        $this->display();
    }

//生成会员编号
    private function dodohelpnumber($id)
    {

        $number='B'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }


    //排单-提供帮助
    public function gethelp()
    {
        $userid=session('userid');
        $D=M('integral');
        $data=$D->where('userid='.$userid)->find();

        //设置开关
        $S=M('setting');
        $sdata=$S->field('gethelp')->where('id=1')->find();

        $this->assign('sdata',$sdata);

        $U=M('user');
        $udata=$U->field('tohelp')->where('id='.$userid)->find();
        $this->assign('tohelp',$udata['tohelp']);

        $para=F('para');
        if(session('username')=='001' or session('username')=='5188'  or session('username')=='huijiao888' or session('username')=='G8888' or session('username')=='7829' or session('username')=='huijiao888' or session('username')=='168168' or session('username')=='008' or session('username')=='5528' or session('username')=='fy999')
        {
            session('gethelp',0);
        }

        $this->assign('para',$para);
        $this->assign('room',$data);
        $this->display();
    }
    //处理-提供帮助
    public function dogethelp()
    {

//        if(session('admin_login')==0)
//        {
//            $ld=F('login');
//            foreach($ld as $vo)
//            {
//                if($vo['userid']==session('userid'))
//                {
//                    if($vo['session_id']!=session_id())
//                    {
//                        echo '0';//重新登录
//                        die();
//                    }
//                }
//            }
//        }

        $userid=session('userid');
        $price=I('post.price');
        //$total=I('post.total');

        $paypassword=md5(I('post.paypassword'));
        $D=M('user');
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        $data=$D->field('paypassword')->where('id='.$userid)->find();
        if(!$this->_checkPwd($paypassword,$data['paypassword']))
        {
            echo 2;//安全密码不对
            die();
        }
//        if($idata['integral3']<$total)
//        {
//            echo 3;//余额不足
//            die();
//        }
        if($price>36000)
        {
            echo 4;//提供帮助不能超过36000
            die();
        }

        if($price>$idata['integral1'])
        {
            echo 5;//静态帐户余额不足
            die();
        }
        $createtime=time();
        //写入排单
        $arr=array(
            'userid'=>$userid,
            'price'=>$price,
            'status'=>0,
            'begintime'=>$createtime,
            'createtime'=>$createtime,
        );
        $T=M('gethelp');
        $id=$T->add($arr);
        $orderid=$this->dogethelpnumber($id);
        $arr=array(
            'orderid'=>$orderid,
        );
        $T->where('id='.$id)->save($arr);


        $arr=array(
            'integral1'=>$idata['integral1']-$price,

        );
        $I->where('userid='.$userid)->save($arr);
        //支入记录
        $title='得到帮助提现'.$price;

        $this->Pay_history($userid,$title,$price,$createtime,0,1);
        //激活码记录
        //$this->case_history($userid,$total,3,1,$createtime,$title);
        if(session('username')=='001' or session('username')=='5188'  or session('username')=='huijiao888' or session('username')=='G8888' or session('username')=='7829' or session('username')=='huijiao888' or session('username')=='168168' or session('username')=='008' or session('username')=='5528' or session('username')=='fy999')
        {
            session('gethelp',0);
        }
        else
        {
           session('gethelp',1);
        }
        echo 1;
        die();
    }

    //得到帮助列表
    public function lgethelp()
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and userid='.$userid;

        $room = M('gethelp');





        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,pic,surplus,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_gethelp where ".$sqlw;


        $query = $room->query($sql);

        $U=M('user');
        $T=M('toget');
        for($i=0;$i<count($info);$i++)
        {
            $tdata=$T->field('tm_tohelp.id,tm_tohelp.orderid,tm_tohelp.userid,tm_tohelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_tohelp.begintime,(select username from tm_user where id=tm_tohelp.userid) as username,(select alipay from tm_user where id=tm_tohelp.userid) as alipay,(select mobile from tm_user where id=tm_tohelp.userid) as mobile,(select bank from tm_user where id=tm_tohelp.userid) as bank,(select bankname from tm_user where id=tm_tohelp.userid) as bankname,(select bankcode from tm_user where id=tm_tohelp.userid) as bankcode,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select tusername from tm_user where id=tm_tohelp.userid) as tusername')->join('tm_tohelp on tm_tohelp.id=tm_toget.toid')->where('tm_toget.getid='.$info[$i]['id'])->select();



            for($j=0;$j<count($tdata);$j++)
            {
                $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
                $tdata[$j]['trealname']=$tdata1['realname'];
                $tdata[$j]['tmobile']=$tdata1['mobile'];

            }



            $info[$i]['sub']=$tdata;
        }





        $pageurl="/user/lgethelp?";


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


    //得到帮助列表
    public function vgethelp()
    {
	 $id=I('get.id');






        $userid=session('userid');


        $sqlw=' id='.$id;

        $room = M('gethelp');





        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,pic,surplus,(select username from tm_user where id='.$userid.') as username,(select username from tm_user where id=tm_gethelp.userid) as username,(select alipay from tm_user where id=tm_gethelp.userid) as alipay,(select mobile from tm_user where id=tm_gethelp.userid) as mobile,(select bank from tm_user where id=tm_gethelp.userid) as bank,(select bankname from tm_user where id=tm_gethelp.userid) as bankname,(select bankcode from tm_user where id=tm_gethelp.userid) as bankcode,(select realname from tm_user where id=tm_gethelp.userid) as realname')->where($sqlw)->find();




        $T=M('toget');
        $U=M('user');

            $tdata=$T->field('tm_tohelp.id,tm_tohelp.orderid,tm_tohelp.userid,tm_tohelp.price,tm_toget.createtime,tm_toget.status,tm_toget.pic,tm_tohelp.begintime,(select username from tm_user where id=tm_tohelp.userid) as username,(select alipay from tm_user where id=tm_tohelp.userid) as alipay,(select mobile from tm_user where id=tm_tohelp.userid) as mobile,(select bank from tm_user where id=tm_tohelp.userid) as bank,(select bankname from tm_user where id=tm_tohelp.userid) as bankname,(select bankcode from tm_user where id=tm_tohelp.userid) as bankcode,(select realname from tm_user where id=tm_tohelp.userid) as realname,(select tusername from tm_user where id=tm_tohelp.userid) as tusername')->join('tm_tohelp on tm_tohelp.id=tm_toget.toid')->where('tm_toget.getid='.$info['id'])->select();

        for($j=0;$j<count($tdata);$j++)
        {
            $tdata1=$U->field('username,mobile,realname')->where('username="'.$tdata[$j]['tusername'].'"')->find();
            $tdata[$j]['trealname']=$tdata1['realname'];
            $tdata[$j]['tmobile']=$tdata1['mobile'];

        }



        $info['sub']=$tdata;








        $this->assign("vo",$info);

        $this->display();
    }

    //生成会员编号
    private function dogethelpnumber($id)
    {

        $number='C'.str_pad($id,9,"0",STR_PAD_LEFT);
        return $number;
    }

    //得到帮助列表
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



        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;
        $userid=session('userid');




        $sqlw.=' and status=-1';

        $room = M('gethelp');





        $info=$room->field('id,orderid,userid,price,createtime,status,begintime,(select username from tm_user where id='.$userid.') as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_gethelp where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/qiangdan?";


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

    //商品列表
    public function product()
    {
        $D=M('product');
        $data=$D->select();
        $this->assign('room',$data);
        $userid=session('userid');
        $I=M('integral');
        $integral=$I->where('userid='.$userid)->find();
        $this->assign('integral',$integral);
        $this->display();
    }
    //商品详情页
    public function product_view()
    {
        $id=I('get.id');
        if(empty($id))
        {
            echo"<script>alert('没有相应的商品！');history.go(-1);</script>";
        }
        $D=M('product');
        $data=$D->where('id='.$id)->find();
        $this->assign('room',$data);
        $this->display();
    }
    //商品详情页
//  public function product_order()
//  {
//      $id=I('get.id');
//      $num=I('get.num');
//      if(empty($id))
//      {
//          echo"<script>alert('没有相应的商品！');history.go(-1);</script>";
//      }
//      $userid=session('userid');
//      $U=M('user');
//      $udata=$U->where('id='.$userid)->find();
//
//      $D=M('product');
//      $data=$D->where('id='.$id)->find();
//      $this->assign('room',$data);
//      $this->Get_User();//得到会员用户
//      //$A=M('user_address');
//      //$add=$A->where('userid='.$userid)->order('def desc')->select();
//      //$this->assign('add',$add);
//      $I=M('integral');
//      $int=$I->where('userid='.$userid)->find();
//      $this->assign('int',$int);
//      $this->assign('num',$num);
//      $this->assign('user',$udata);
//      $this->display();
//  }
    //提交订单
    public function doproduct_order()
    {
        $id=I('post.id');
        $address=I('post.address');
        $realname=I('post.realname');
        $mobile=I('post.mobile');
        $pr=I('post.pr');
        $ci=I('post.ci');
        $di=I('post.di');

        $number=I('post.num');

        $pay1=I('post.pay1');
        $pay2=I('post.pay2');
        $userid=session('userid');
        $P=M('product');
        $pdata=$P->where('id='.$id)->find();
        $U=M('user');
        $udata=$U->where('id='.$userid)->find();
        $I=M('integral');
        $idata=$I->where('userid='.$userid)->find();
        //$A=M('user_address');
        //$adata=$A->where('id='.$address)->find();


        $createtime=time();

        $orderid=$this->Createorderid(2);//商品订单

        $arr=array(
            'userid'=>$userid,
            'orderid'=>$orderid,
            'productname'=>$pdata['productname'],
            'price'=>$pdata['price']*$number,
            'number'=>$number,
            'createtime'=>$createtime,
            'status'=>2,
            'province'=>$pr,
            'city'=>$ci,
            'dist'=>$di,
            
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


               if($idata['integral1']<$pdata['price']*$number)
                {
                    echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
                    die();
                }

                if($idata['integral5']<$pdata['ticket']*$number)
                {
                    echo"<script>alert('券余额不足，请充值！');history.go(-1);</script>";
                    die();
                }

           

                 $I->where('userid='.$userid)->setdec('integral1',$pdata['price']*$number);
                 $sarr=array('status'=>2);//改变支付状态
                 $O->where('id='.$id)->save($sarr);
                 $title='购买商品';
                 $this->Pay_history($userid,$title,$pdata['price']*$number,$createtime,0,1);//支出记录;
                 $I->where('userid='.$userid)->setinc('integral5',$pdata['ticket']*$number);
                  $title='购买商品用券';
                 $this->Pay_history($userid,$title,$pdata['ticket']*$number,$createtime,0,5);//支出记录;
      


     
        echo"<script>alert('订单提交成功，请等候发货！');location.href='/user/order.html';</script>";
        die();

    }



//得到会员帐户
    private function Get_User()
    {
        $userid=session('userid');
        $U=M('user');
        $user=$U->where('id='.$userid)->find();
        $this->assign('user',$user);
    }
    //购物车列表
    public function cart()
    {
        $userid=session('userid');

        $this->Get_User();//得到会员用户
        $A=M('user_address');
        $add=$A->where('userid='.$userid)->order('def desc')->select();
        $this->assign('add',$add);
        $I=M('integral');
        $int=$I->where('userid='.$userid)->find();
        $this->assign('int',$int);


        $other=empty($_REQUEST['o'])?0:$_REQUEST['o'];
        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $pagesize =10;

        $sqlw='1=1 ';


        $sqlw.=' and userid='.$userid;

        $room = M('cart');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        // $memberid=session('userid');




        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_cart where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/cart.html?";


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
    //加入购物车
    public function docart()
    {
        $productid=I('get.id');
        $userid=session('userid');
        $D=M('cart');
        $P=M('product');
        $pdata=$P->where('id='.$productid)->find();

        $arr=array('userid'=>$userid,
            'productname'=>$pdata['productname'],
            'price'=>$pdata['price'],
            'number'=>1,
            'productid'=>$productid,
            'createtime'=>time(),
        );
        $D->add($arr);
        echo "<script>alert('已加入购物车！');history.go(-1);</script>";
        die();

    }
    //清空购物车
    public function deletecart(){
        $D=M('cart');
        $userid=session('userid');
        $D->where('userid='.$userid)->delete();
        echo "<script>alert('购物车已清空！');location.href='/user/cart.html';</script>";
        die();
    }


    //提交购物车订单
    public function docartorder()
    {
        $id=I('post.id');
        $number=0;
        $address=I('post.address');
        $realname=I('post.realname');
        $mobile=I('post.mobile');
        $pay=I('post.pay');
        $userid=session('userid');
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
        //$A=M('user_address');
        //$adata=$A->where('id='.$address)->find();


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

                if($idata['integral5']<$totalprice)
                {
                    echo"<script>alert('余额不足，请充值！');history.go(-1);</script>";
                    die();
                }
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

        echo"<script>alert('订单提交成功，请等候发货！');location.href='/user/order.html';</script>";
        die();
    }

    //订单列表
    public function order()
    {

        $userid=session('userid');


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
        $sqlw.=' and userid='.$userid;

        $room = M('order');
        //$info = $room->where("status=0")->limit(($page-1)*$page_size,$page_size)->select();
        //$info = $room->join('left join tm_member on tm_task.memberid = tm_member.memberid ')->limit(($page-1)*$page_size,$page_size)->select();
        // $memberid=session('userid');




        $info=$room->field('tm_order.id,tm_order.userid,tm_order.orderid,tm_order.productname,tm_order.price,tm_order.number,tm_order.createtime,tm_order.status,tm_order_address.kdgs,tm_order_address.kddh')->join('tm_order_address on tm_order.id=tm_order_address.orderid')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_order where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/user/order.html?";


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
    //订单详情
    public function view()
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

    public function message()
    {
        $this->display();
    }
    public function domessage()
    {
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
        echo"<script>alert('留言反馈添加成功！');location.href='/user/message.html';</script>";
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





        $pageurl="/user/news.html?";


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
    //生成二维码
    public function qrcode($level=3,$size=4)
    {
        $u=I('get.url');
        $url='http://'.$u;
        Vendor('phpqrcode');
        $errorCorrectionLevel =intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        //生成二维码图片
        $object = new \QRcode();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
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
          $D = M('freeze');

          $T=M('tohelp');
          $tdata=$T->field('orderid')->where('id='.$toid)->find();
          //先删除,再插入
          $D->where('userid='.$userid.' and toid='.$toid.' and fromuserid='.$fromuserid.' and gotegary='.$gotegary.' and integral1='.$integral1.' and integral2='.$integral2.' and title="'.$title.'"')->delete();
             $arr = array(
                'userid' => $userid,
                'toid' => $toid,
                'orderid' => $tdata['orderid'],
                'fromuserid' => $fromuserid,
                'gotegary' => $gotegary,
                'begintime' => $createtime,
                'integral1' => $integral1,
                'integral2' => $integral2,
                'title' => $title,
            );
            $D->add($arr);

    }

    //解冻奖金
    public function dofreeze()
    {
        $userid=session('userid');
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
            $this->sms($user['mobile'],$user['username'],3);
            return;
        }

        
        //动态解冻
//        $arr=$this->currorder();
//
//        if($arr[0]==0)
//        {
//            return;
//        }

        $gotegary=2;
        $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
        $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);

        $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
        $gotegary=5;
        $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);

        //$this->sms($user['mobile'],$user['username'],3);


//        //烧伤
//        $total=$this->dofire($arr[1]);
//
//        if($total>=$arr[2])//奖金多过投资金额
//        {
//            $title='奖金烧伤扣除';
//            $gotegary=2;
//            $this->Pay_history($data['userid'],$title,$data['integral1'],$begintime,0,$gotegary);
//            $gotegary=5;
//            $this->Pay_history($data['userid'],$title,$data['integral2'],$begintime,0,$gotegary);
//
//            return;
//        }
//        else//奖金少过投资金额
//        {
//            if(($total+$data['integral1'])<=$arr[2])
//            {
//                //写入收支记录
//                //支付记录
//                //$userid 会员ID
//                //$title 内容
//                //$createtime 创建时间
//                //$price 消费价格
//                //$status 消费状态 1 收入，0 支出
//                //$gotegary 1  静态帐户，2动态帐户，3 排单币，4激活币 5  消费帐户
//
//
//                    $gotegary=2;
//                    $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
//                    $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);
//
//                    $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
//                    $gotegary=5;
//                    $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);
//            }
//            else
//            {
//                    $data['integral1']= $arr[2]-$total;
//                    $gotegary=2;
//                    $I->where('userid='.$data['userid'])->setinc('integral2',$data['integral1']);
//                    $I->where('userid='.$data['userid'])->setinc('integral5',$data['integral2']);
//
//                    $this->Pay_history($data['userid'],$data['title'],$data['integral1'],$begintime,$status,$gotegary);
//                    $gotegary=5;
//                    $this->Pay_history($data['userid'],$data['title'],$data['integral2'],$begintime,$status,$gotegary);
//            }
//
//        }


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





        $sqlw='1=1';

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
        $userid=session('userid');




        $sqlw.=' and userid='.$userid;

        $room = M('freeze');





        $info=$room->field('id,userid,toid,integral1,integral2,gotegary,title,status,begintime,(select username from tm_user where id=tm_freeze.fromuserid) as username')->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_freeze where ".$sqlw;


        $query = $room->query($sql);
        $sql = "select sum(integral1+integral2) as total from tm_freeze where ".$sqlw;

        $integraltotal=$room->query($sql);

        $this->assign('integraltotal',$integraltotal[0]['total']);

        $pageurl="/user/freeze?";


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
    public function currorder()
    {
        $userid=session('userid');
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



    //加数据
    public function redofreeze()
    {


        $i1=I('get.p');
        $i2=$i1*0.2;

        $id2=I('get.id');//toid
        $T=M('tohelp');

//        echo $i1;
//        echo '<br>';
//        echo $id2;
//        die();
        //$tcount=$T->field('count(1) as number')->where('toid='.$id)->select();

        //$number=$tcount[0]['number']; //计算多少个配置


        $tdata=$T->where('id='.$id2)->find();


        $title='订单交易完成';
        $createtime=$tdata['createtime'];
        // $status=1;
        //$gotegary=1;
        //$this->Pay_history($tdata['userid'],$title,$integral1,$createtime,$status,$gotegary);
        //$gotegary=5;
        // $this->Pay_history($tdata['userid'],$title,$integral5,$createtime,$status,$gotegary);

        $integral5=$i2*0.1;//10%返消费积分
        $integral1=$i1+$i2-$integral5;
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

        echo '成功';
    }

    //退出
    public function logout()
    {
        //session(null);
        session('admin_login',null);
        session('userid',null);
        session('username',null);
        session('status',null);//0  未开通，１已开通　２休眠　３冻结　
        session('limit',null);//投资额度
        session('fire',null);//是否烧伤0 不烧伤，１烧伤
        session('logintime',null);//登录时间
        echo"<script>location.href='/login.html'</script>";

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


    public function docard()
    {
        $card=I('post.card');
        $pwd=I('post.pwd');
        $D=M('card');


        $data=$D->where('card="'.$card.'" and pwd="'.$pwd.'"')->find();
        if(empty($data))
        {
            echo 2; //卡号不存在或者已经被使用
            die();
        }
        if($data['status']==1)
        {
            echo 3;
            die();
        }


        
        $I=M('integral');
        $userid=session('userid');
        $price=1500;
        $I->where('userid='.$userid)->setinc('integral5',$price);
        $title='充值送券';
        $this->Pay_history($userid,$title,$price,time(),1,4);


      
        //产生奖励
        //１合伙人30
        //2区域经理，最近一个红理拿10
        //３推荐人100
        $this->cardtouser();

        $D->where('card="'.$card.'" and pwd="'.$pwd.'"')->setinc('status',1);
        echo 1;
        die();


    }

    //计算奖金
    public function cardtouser()
    {
        //产生奖励
        //１合伙人30
        //2区域经理，最近一个经理拿10
        //３推荐人100
        //
        //
       
        $userid=session('userid');
        $createtime=time();
        $U=M('user');
        $I=M('integral');
        $data=$U->where('id='.$userid)->find();


        if(!empty($data))
        {
            $data1=$U->where('username="'.$data['tusername'].'"')->find();
            if(!empty($data1))
            {
                $I->where('userid='.$data1['id'])->setinc('integral1',100);
                $title='推荐人奖';
                $this->Pay_history($data1['id'],$title,100,$createtime,1,1);
            }
         
        }


     
        if(!empty($data['path']))
        {
            $path=substr($data['path'],0,strlen($data['path'])-1);
            

            $data2=$U->where('id in('.$path.') and gotegary=2')->order('id desc')->find();
            
            if(!empty($data2))
            {
                $I->where('id='.$data2['id'])->setinc('integral1',30);
                $title='合伙人奖';
                $this->Pay_history($data2['id'],$title,30,$createtime,1,1);
            }



            //经理人奖
            $data3=$U->where('id in('.$path.') and gotegary=1')->order('id desc')->find();
            if(!empty($data2))
            {
                $I->where('id='.$data3['id'])->setinc('integral1',10);
                $title='经理人奖';
                $this->Pay_history($data3['id'],$title,10,$createtime,1,1);
            }
        }

    }


}