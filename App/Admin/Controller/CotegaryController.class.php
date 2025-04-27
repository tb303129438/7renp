<?php
namespace Admin\Controller;
use Think\Controller;

class CotegaryController extends \Common\Controller\AdminController{
    public function index(){
        $str = '';
        $username = session('username');

        $D = M('cotegary');
        $data = $D->where('parentid=0')->select();
        if(!empty($data))
        {
            $str .= '<ul id="tree" class="treeview">';
            foreach ($data as $vo) {

                $str .= '<li><span>'.$vo['title'].'</span>  <a style="xq_box_a" id="'.$vo['id'].'">增加小类</a>  <span style="font-size:10px;color:#666;cursor:pointer" onclick="del('.$vo['id'].');">删除</span>';
                $str .= $this->getsub($vo['id']);
                $str .= '</li>';
            }
             $str .= '</ul>';
            //$str.=$this->getsubuser($username);

            $this->assign('str',$str);
        }
       $this->display();
    }
    

    public function add()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

  

    public function ajax()
    {
        $action=I('post.action');

        switch($action)
        {
            case 'add':
               $pid=I('post.pid');
               $title=I('post.title');
               $D=M('cotegary');
               if(empty($pid))
               {
                $path="|0|";
               }
               else
               {
                  $data=$D->field('path')->where('id='.$pid)->find();
                  $path=$data['path'].$pid.'|';
               }

              
               $arr=array(
                  'title'=>$title,
                  'parentid'=>$pid,
                  'path'=>$path,
                );
                $D->add($arr);
                echo 1;
                die();
            break;
            case 'del':
              $id=I('post.id');
              $D=M('cotegary');
              $D->where('locate("|'.$id.'|",path)>0 or id='.$id)->delete();
              echo 1;
              die();
            break;
        }
    }

    //得到子项
    public function getsub($pid)
    {
        $D=M('cotegary');

        $room=$D->field('id,title,parentid')->where('parentid='.$pid)->select();


        $str='';
        if(!empty($room))
        {
            $str.='<ul>';
            foreach($room as $vo)
            {
                //$arr['username']=
                $str.='<li><span>'.$vo['title'].' </span> <a style="xq_box_a" id="'.$vo['id'].'">增加小类</a>  <span style="font-size:10px;color:#666;cursor:pointer" onclick="del('.$vo['id'].');">删除</span>';
                $str.=$this->getsub($vo['id']);
                $str.='</li>';


            }
            $str.='</ul>';
        }


        return $str;



    }

}