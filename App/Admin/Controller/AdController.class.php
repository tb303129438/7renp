<?php
namespace Admin\Controller;
use Think\Controller;

class AdController extends \Common\Controller\AdminController{
    public function index(){
      
      $D=M('ad');
      $data=$D->order('id asc')->select();

      $this->assign('room',$data);
      $this->display();
    }
    public function clist()
    {
        $keyword=trim(I('get.k'));

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




        $sqlw.=' and (card like "%'.$keyword.'%" )';

        $room = M('card');





        $info=$room->order(" id desc")->where($sqlw)->limit(($page-1)*$pagesize,$pagesize)->select();

        $sql = "select count(*) as total from tm_card where ".$sqlw;


        $query = $room->query($sql);





        $pageurl="/admin/card/clist?";


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
    public function add()
    {


  
        $D=M('ad');
        $arr=array();

       
        for($i=0;$i<=6;$i++)
        {
            $arr[$i]['img']=I('post.pic'.$i.'url');
            $arr[$i]['url']=I('post.url'.$i);
        }

        foreach($arr as $key=>$vo)
        {

            $arr1=array(
                'img'=>$vo['img'],
                'url'=>$vo['url']
            );
            $i=$key+1;
            $D->where('number='.$i)->save($arr1);
        }


        $pdata=$D->order('id asc')->select();
        F('ad',$pdata);




        echo "<script>alert('广告修改成功！');location.href='/admin/ad/index.html';</script>";
        die();

        //$data=$D->select();
        //$this->assign('room',$data);
   
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function import()
    {

      $name='Excel';
       $D=M('card');
       $data=$D->select();

       vendor('PHPExcel');
        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        

 /*以下是一些设置 ，什么作者  标题啊之类的*/
         $objPHPExcel->getProperties()->setCreator("尚品家人")
                               ->setLastModifiedBy("尚品家人")
                               ->setTitle("导出充值卡")
                               ->setSubject("导出充值卡")
                               ->setDescription("导出充值卡")
                               ->setKeywords("excel")
                              ->setCategory("result file");

            // $objPHPExcel->setActiveSheetIndex(0) ->setCellValue(0.'1', '卡号');
            // $objPHPExcel->setActiveSheetIndex(0) ->setCellValue(1.'1', '密码');
            // $objActSheet = $objPHPExcel->getActiveSheet();

         /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
         foreach($data as $k => $v){

             $num=$k+1;
             $objPHPExcel->setActiveSheetIndex(0)
                         //Excel的第A列，uid是你查出数组的键值，下面以此类推
                          ->setCellValue('A'.$num, $v['card'])
                          ->setCellValue('B'.$num, $v['pwd']);
           }

            $objPHPExcel->getActiveSheet()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
             header('Content-Type: application/vnd.ms-excel');
             header('Content-Disposition: attachment;filename="'.$name.'.xls"');
             header('Cache-Control: max-age=0');
             $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
             $objWriter->save('php://output');
             exit;


    }

  

    public function ajax()
    {
        $action=I('post.action');

        switch($action)
        {
            case 'create':
              $cardbegin=I('post.cardbegin');
              $cardnum=I('post.cardnum');
              $price=I('post.price');
              $createtime=time();
              $D=M('card');
              for($i=0;$i<$cardnum;$i++)
              {
                  $pwd=rand(100000,999999);
                  
                  //$card=$this->NumToStr($cardbegin);
                  $card=$cardbegin;
                  $arr=array('card'=>$card,'pwd'=>$pwd,'status'=>0,'createtime'=>$createtime,'price'=>$price);
                  $id=$D->add($arr);
                  $cardbegin=$cardbegin+1;
                  
              }
              echo $cardnum;
              die();
              
            break;
            case 'del':
               $id=i('post.id');
               $D=M('card');
               $D->where('id='.$id)->delete();
               echo 1;
               die();

            break;
        }
    }


    //科学计数法，转换成字符串
    public function NumToStr($num){ 
     if (stripos($num,'e')===false) return $num; 
     $num = trim(preg_replace('/[=\'"]/','',$num,1),'"');//出现科学计数法，还原成字符串 
    $result = ""; 
     while ($num > 0){ 
         $v = $num - floor($num / 10)*10; 
         $num = floor($num / 10); 
         $result   =   $v . $result; 
     }
     return $result; 
    }

    

}