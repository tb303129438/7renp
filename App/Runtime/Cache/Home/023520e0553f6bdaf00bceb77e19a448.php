<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1" />
		<title>汽车分销平台</title>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/css/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/css/css/login.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/libs/iconfont/iconfont.css"/>
		<script type="text/javascript" src="/Public/Mc/lib/bootstrap/js/jquery.min.js" ></script>
		<script type="text/javascript" src="/Public/Mc/lib/bootstrap/js/bootstrap.min.js" ></script>
	</head>
	<body>
		<div class="login_bg"></div>
		<div class="mobile_contai">
			<div class="login_box">
				<div class="logo_box">
					<img src="/Public/Mc/image/logo.png"/>
				</div>
				<div class="form_box">
					<form action="/Home/index/dologin" method="post">
						<div class="input_gruop">
							<label><i class="icon iconfont icon-zhanghu"></i>账　号</label>
							<input type="text" name="username" id="username" value="<?php echo ($username); ?>" placeholder="请输入账号" />
						</div>
						<div class="input_gruop">
							<label><i class="icon iconfont icon-mima"></i>密　码</label>
							<input type="password" name="password" id="password" placeholder="" />
						</div>
						<div class="input_gruop">
							<label><i class="icon iconfont icon-yanzhengma"></i>验证码</label>
							<input type="text" name="verify" id="verify" value="<?php echo ($code); ?>" placeholder="输入验证码" />
							<div class="yzm_box">
								<a href="javascript:;">
									<dd id="captcha-container">
										<img  width="120"  style="cursor: hand;margin-right:14px; cursor:pointer;"  height="40px" alt="验证码" src="/Admin/login/verify" title="点击刷新">
									</dd>
								</a>
							</div>
						</div>
						<div class="btn_gruop">
							<input type="submit" name="" class="btn" id="" value="登 录" />
						</div>
						<div class="text_r">
							<a href="/home/index/szmm">忘记密码？</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<script type="text/javascript" src="/Public/Admin/js/login.js" ></script>
<script>
	var captcha_img = $('#captcha-container').find('img')
    var verifyimg = captcha_img.attr("src");
    captcha_img.attr('title', '点击刷新');
    captcha_img.click(function(){
        if( verifyimg.indexOf('?')>0){
            $(this).attr("src", verifyimg+'&random='+Math.random());
        }else{
            $(this).attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
        }
    });
</script>