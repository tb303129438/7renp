<?php
namespace Common\Controller;
use Think\Controller;
class DemoController extends \Common\Controller\CheckController
{
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
                                   
								                     
                                        //出局
                                        $this->userout($userid,$arr[0]);
                                        $D->where('id='.$userid)->setinc('rounds',1);//轮数加１
                                   
                                }

                                
                        }
                    }
                   
                }
    }


    //判断是否出局
    public function userout($id,$number)
    {
       //判断$number子节点下会有没有人
       
       $D=M('user');
       $P=M('parameter');
       $pdata=$P->order('id asc')->select();


       $price=$pdata[18]['number'];
       $price1=$pdata[18]['number']+$pdata[20]['number'];
       $price2=$pdata[24]['number'];//推荐奖
        //出局奖
       $I=M('integral');
      
       $title='出局奖';
       $createtime=time();
      
       $I->where('userid='.$id)->setinc('integral1',$price1);
       $this->Pay_history($id,$title,$price1,$createtime,1,1);
       $title='复投';
      
       $I->where('userid='.$id)->setdec('integral1',$price);
	 	   $this->Pay_history($id,$title,$price,$createtime,0,1);

        //复投推荐人推荐奖2000
		$updata = M('user')->where("id=".$id)->find();
		
		$data = M('user')->where("username='".$updata['tusername']."'")->find();
		
        if(!empty($data))
        {
    		    $title=$updata['username'].'复投推荐人奖金';
           
            $I->where('userid='.$data['id'])->setinc('integral1',$price2);
            $this->Pay_history($data['id'],$title,$price2,$createtime,1,1);
        }
    

       //出局奖
       $this->userleft($id,$number);

    
       //出局后转换
       $room=$D->where('id='.$id)->find();
       


       if($room['username']=='MJ000001')
       {
           $this->usernext($id,$number);
       }
       else
       {
          $this->getteam($id);
       }
    }

    //递归判断
    public function usernext($id,$number)
    {
        $numberl=$number*2;
        $numberr=$number*2+1;
        $T=M('tree');
        $tdata=$T->where('number='.$numberl)->find();
		
		    $pdata = $T->where("userid=".$id)->order("id desc")->limit(1)->find();



		
        $array = array(
          'out'=>1
        );  
        $T->where('number='.$pdata['number'])->save($array);
        
        
        if(empty($tdata))
        {
            $array = array(
              'userid'=>$id,
              'number'=>$numberl,
              'out'=>0,
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
              'out'=>1,
            );  
            $nid = M('tree')->add($array);

           
            return;
        }
        $this->usernext($id,$numberl);


    }
    
    //取得小组图谱
    public function getteam($userid)
    {
      


        $D=M('user');
        $data=$D->where('id='.$userid)->find();
        $pdata=$D->where('username="'.$data['tusername'].'"')->find();
        $T=M('tree');

       
        $tdata=$T->where('userid='.$pdata['id'])->order('id desc')->limit(1)->find();
        
        $this->gettusername($userid);
        //$this->addtree($userid,$tdata['number']);
    }




      // 右边出局奖
    public function userleft($id,$number)
    {

       $P=M('parameter');
       $pdata=$P->order('id asc')->select();

        
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
        $price=$pdata[19]['number'];
        $createtime=time();
        $U=M('user');
        $my=$U->where('id='.$id)->find();
        

        //第一个推荐人
        $data=$U->where('id=(select userid from tm_tree where number='.$numberl.')')->find();
        if(!empty($data))
        {
            $data1=$U->where('username="'.$data['tusername'].'"')->find();
            if(!empty($data1))
			{
				$I=M('integral');
				$I->where('userid='.$data1['id'])->setinc('integral4',$price);
				$title='会员'.$my['username'].'出局,'.$data['username'].'会员产生消费积分';
				$createtime=time();
				$this->Pay_history($data1['id'],$title,$price,$createtime,1,4);
			}
        }
       
       //第二个推荐人
        $data=$U->where('id=(select userid from tm_tree where number='.$numberr.')')->find();
        if(!empty($data))
        {
            $data1=$U->where('username="'.$data['tusername'].'"')->find();
			if(!empty($data1))
			{
				$I=M('integral');
				$I->where('userid='.$data1['id'])->setinc('integral4',$price);
				$title='会员'.$my['username'].'出局,'.$data['username'].'会员产生消费积分';
				$createtime=time();
				$this->Pay_history($data1['id'],$title,$price,$createtime,1,4);
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


    //$number当前树的编号
    //$userid 用户ＩＤ
    public function addtree($userid,$number)
    {
        $T=M('tree');
         

       $num=$number;
            
       $n1=intval($number/4);
       $n2=intval($number/2);
       
       $data=$T->where('(number='.$n1.' or number='.$n2.') and `out`=0')->order('id asc')->select();
       
       if(empty($data))
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
       else
       {

            if(count($data)==2)
            {
               
                        $nn1=4*$data[0]['number'];
                        $nn2=4*$data[0]['number']+1;
                        $nn3=4*$data[0]['number']+2;
                        $nn4=4*$data[0]['number']+3;
                       $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                                //出局人
                       $udata=$T->where('number='.$number)->find();
                                //判断出局
                       $this->treeout($udata['userid']);
            }               
            elseif(count($data)==1)
            {
                    $nn1=4*$data[0]['number'];
                    $nn2=4*$data[0]['number']+1;
                    $nn3=4*$data[0]['number']+2;
                    $nn4=4*$data[0]['number']+3;
                    $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                                //出局人
                    $udata=$T->where('number='.$number)->find();
                                //判断出局
                    $this->treeout($udata['userid']);

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
                       //找推荐人,放到推荐人下面
                       //$this->gettusername($userid);
                       $arr=array('userid'=>$userid,
                        'number'=>$nn['number']+1,
                        );
                       $T->add($arr);
                    }
    }
     
   //放到推荐人下面
   public function gettusername($userid)
   {
       
       
       $D=M('user');
       $T=M('tree');

       $pdata = $T->where("userid=".$userid)->order("id desc")->limit(1)->find();
       $array = array(
          'out'=>1
       );  
       $T->where('number='.$pdata['number'])->save($array);

       $data=$D->where('id='.$userid)->find();
       $tdata=$T->where('userid=(select id from tm_user where username="'.$data['tusername'].'")')->order('id desc')->limit(1)->find();

      

       $number=$tdata['number'];


       $n1=intval($number/4);
       $n2=intval($number/2);
       
       $data=$T->where('(number='.$n1.' or number='.$n2.') and `out`=0')->order('id asc')->select();
       
       if(empty($data))
       {
        
           $nn1=4*$number;
           $nn2=4*$number+1;
           $nn3=4*$number+2;
           $nn4=4*$number+3;

         if($this->isout($userid)==1)//是否满五轮，有推荐人继续复投
          {
                                       
           $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                    //出局人
           $udata=$T->where('number='.$number)->find();
                    //判断出局
           $this->treeout($udata['userid']);
         }
       }
       else
       {

            if(count($data)==2)
            {
               $nn1=4*$data[0]['number'];
               $nn2=4*$data[0]['number']+1;
               $nn3=4*$data[0]['number']+2;
               $nn4=4*$data[0]['number']+3;
                     

                  if($this->isout($userid)==1)//是否满五轮，有推荐人继续复投
                   {
                       $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                                //出局人
                       $udata=$T->where('number='.$data[0]['number'])->find();
                                //判断出局
                       $this->treeout($udata['userid']);
                   }
            }               
            elseif(count($data)==1)
            {
                    
                   $nn1=4*$data[0]['number'];
                   $nn2=4*$data[0]['number']+1;
                   $nn3=4*$data[0]['number']+2;
                   $nn4=4*$data[0]['number']+3;
                     
                   
                   if($this->isout($userid)==1)//是否满五轮，有推荐人继续复投
                   {
                    $this->inserttree($userid,$nn1,$nn2,$nn3,$nn4);
                                //出局人
                    $udata=$T->where('number='.$data[0]['number'])->find();
                                //判断出局
                    $this->treeout($udata['userid']);
                  }

            }



           
        
    }

   }


}
?>