<?php

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller {
	
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
        $czmcsy = CONTROLLER_NAME . ACTION_NAME;//Indexlogin
		$czmc = ACTION_NAME; //login
		// echo $czmc;die;
		
		if (empty($_SESSION['username'])){
			
			
			$this->success('請先登錄','/admin/Login/login');die;
		}
			
		
		
		if($_SESSION ['adminqx']<>'2'){
				
			if($czmc<>'index'&&$czmc<>'index1'&&$czmc<>'top'&&$czmc<>'left'&&$czmc<>'adminxgmm'&&$czmc<>'admin_xg'&&$czmc<>'admintable'&&$czmc<>'usertable'&&$czmc<>'tree'&&$czmc<>'usertable2'&&$czmc<>'ordertable'&&$czmc<>'txtable'&&$czmc<>'sptable'&&$czmc<>'lytable'&&$czmc<>'newstable'&&$czmc<>'ajax'){
				die("<script>alert('您暂无权限操作！');parent.location.reload();</script>");
				// $this->error('您暂无权限操作!','#');die;
			}
				
		}
		// dump($_SESSION);die;
		
	    //超过10分钟，自动退出！
		$this->checkAdminSession();
	
		

	}
	// public function islogin()
	// {

	// 	if (empty($_SESSION['adminname'])){
			
			
	// 		$this->success('請先登錄','/admin/index/login');die;
	// 	}
	// }

	public function checkAdminSession() {
		//设置超时为10分
//		$nowtime = time();
//		$s_time = $_SESSION['admintime'];
//		if (($nowtime - $s_time) > 600) {
//		session_unset();
//  	session_destroy();
//			$this->error('当前用户登录超时，请重新登录', '/admin/Login/login');
//		} else {
//			$_SESSION['admintime'] = $nowtime;
//		}
	}

	//检验 验证码
    function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
    }
    
    //检测是否存在账号
    protected function check_admin($name) {
      $res =  M('admin')->where(array('name'=>$name))->count();
      if($res>0){
          return TRUE;
      }else{
          return FALSE;
      }
    }
    
    //检测邮箱是否被占用
    public function checkemail($email){
        $model = M("admin");
        $result = $model->where(array('email'=>$email))->count();
        if($result>0){
           $this->error($this->showRegError(-8));
        } else {
            return true;
        }
        
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

    /**
     * 密码解密
     */
    function _dePasswd($passwd)
    {
        return $passwd;
    }

    
    /**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在16个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '用户名被占用！'; break;
			case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
			case -5:  $error = '邮箱格式不正确！'; break;
			case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
			case -7:  $error = '邮箱被禁止注册！'; break;
			case -8:  $error = '邮箱被占用！'; break;
			case -9:  $error = '手机格式不正确！'; break;
			case -10: $error = '手机被禁止注册！'; break;
			case -11: $error = '手机号被占用！'; break;
			default:  $error = '未知错误';
		}
		return $error;
	}

	public function uploadFace() {
	
		//if (!$this->isPost()) {
		//	$this->error('页面不存在');
		//}
		//echo 'asdfsaf';die;
		$upload = $this->_upload('Pic');
		// dump($upload);die;
		$this->ajaxReturn($upload);
	}
	
	Private function _upload ($path) {
		import('ORG.Net.UploadFile');	//引入ThinkPHP文件上传类
		$obj = new \Think\Upload();	//实例化上传类
		$obj->maxSize = 2000000;	//图片最大上传大小
		$obj->savePath =  $path . '/';	//图片保存路径
		$obj->saveRule = 'uniqid';	//保存文件名
		$obj->uploadReplace = true;	//覆盖同名文件
		$obj->allowExts = array('jpg','jpeg','png','gif');	//允许上传文件的后缀名
	
		$obj->autoSub = true;	//使用子目录保存文件
		$obj->subType = 'date';	//使用日期为子目录名称
		$obj->dateFormat = 'Y_m';	//使用 年_月 形式
		//$obj->upload();die;
		$info   =   $obj->upload();
		dump($info);exit;
		if (!$info) {
			return array('status' => 0, 'msg' => $obj->getErrorMsg());
		} else {
			foreach($info as $file){
				$pic = $file['savepath'].$file['savename'];
			}
			//$pic =  $info[0][savename];
			//echo $pic;die;
			return array(
					'status' => 1,
					'path' => $pic
			);
		}
	}

	
	
	
	
	
	
	


	
	
}