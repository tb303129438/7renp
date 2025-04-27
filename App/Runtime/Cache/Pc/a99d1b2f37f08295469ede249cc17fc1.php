<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>汽车分销平台</title>
		<link rel="stylesheet" type="text/css" href="/Public/Pc/libs/bootstrap/css/bootstrap.min.css"/>
		
		<link rel="stylesheet" type="text/css" href="/Public/Pc/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Pc/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Pc/css/mobile.css"/>
		
		<script type="text/javascript" src="/Public/Pc/libs/jq/jquery-1.9.1.min.js" ></script>
		<script type="text/javascript" src="/Public/Pc/libs/bootstrap/js/bootstrap.min.js" ></script>
		<script type="text/javascript" src="/Public/Pc/home/js/jxmc.js" ></script>
		
		
	</head>
	<body>
		<div class="left_box">
			<div class="logo_box">
				<img src="/Public/Pc/img/pcLogo.png"/>
			</div>
			<div class="user_box">
				<!--头像-->
				<div class="tx_img">
					<img src="/Public/Pc/img/touxiang.png"/>
				</div>
				<div class="text_l user_info">
					<p><?php echo (session('username')); ?></p>
					<?php $user = M('user')->where("id=".session('id'))->find();?>
					<p>(<?php echo ($user["realname"]); ?>)</p>
					<?php if($user["tohelp"] == 0): ?><p style="color: #00A2D4;">普通会员</p>
					<?php else: ?>
						<p style="color: #00A2D4">报单中心</p><?php endif; ?>

				</div>
			</div>
			<div class="nav_box">
				<ul class="nav_contai">
					<li class="nav_list">
						<a href="/pc/index/index.html"><i class="nav_icon nav_shouye"></i>首页</a>
					</li>
					<li class="nav_list">
						<a href="javascript:;"><i class="nav_icon nav_tdgl"></i>团队管理<i class="jt"></i></a>
						<dl class="towlave">
							<dd><a href="/pc/wo/zhjh.html">帐号激活</a></dd>
							<dd><a href="/pc/wo/tjlb.html">推荐列表</a></dd>
							<dd><a href="/pc/wo/wtjg.html">网体结构</a></dd>
						</dl>
					</li>
					<li class="nav_list">
						<a href="javascript:;"><i class="nav_icon nav_ywgl"></i>业务管理<i class="jt"></i></a>
						<dl class="towlave">
							<dd><a href="/pc/index/registered">注册会员</a></dd>
							<?php $se = M("user")->where("id=".session('id'))->find();?>
							<?php if($se["tohelp"] == 0): ?><dd><a href="/pc/wo/bdzx">报单中心</a></dd>
							<?php else: endif; ?>
							<dd><a href="/pc/wo/bdlb">报单列表</a></dd>
						</dl>
					</li>
					<li class="nav_list">
						<a href="javascript:;"><i class="nav_icon nav_cwgl"></i>财务管理<i class="jt"></i></a>
						<dl class="towlave">
							<dd><a href="/pc/wo/jdmx.html">积分明细</a></dd>
							<dd><a href="/pc/wo/recharge">资金转账</a></dd>
							<dd><a href="/pc/wo/tx">资金兑现</a></dd>
							<dd><a href="/pc/wo/gcsq">购车申请</a></dd>
						</dl>
					</li>
					<li class="nav_list">
						<a href="javascript:;"><i class="nav_icon nav_grzx"></i>个人设置<i class="jt"></i></a>
						<dl class="towlave">
							<dd><a href="/pc/wo/grxx">个人信息</a></dd>
							<dd><a href="/pc/wo/yhxx">银行信息</a></dd>
							<dd><a href="/pc/wo/mmxg">修改密码</a></dd>
						</dl>
					</li>
					<li class="nav_list">
						<a href="/pc/wo/gg"><i class="nav_icon nav_xtgg"></i>系统公告<i class="new">news</i></a>
					</li>
					<!-- <li class="nav_list">
						<a href="/pc/wo/znxj"><i class="nav_icon nav_xtgg"></i>在线留言<i class="new">news</i></a>
					</li> -->
					<!--<li class="nav_list">
						<a href="javascript:;"><i class="nav_icon nav_znxx"></i>站内消息<i class="new">100</i></a>
						<dl class="towlave">
							<dd><a href="">form表单</a></dd>
							<dd><a href="">网体结构</a></dd>
							<dd><a href="">明细列表</a></dd>
							<dd><a href="">公告详情</a></dd>
						</dl>
					</li>-->
				</ul>
				<div class="exit_box text_c">
					<a href="/pc/index/logout">
						<p class="exit_icon"><img src="/Public/Pc/img/exit.png"/></p>
						<p>安全退出</p>
					</a>
				</div>
			</div>
		</div>
		
		<div class="right_box">
			
			<div class="topbar">
	<div class="topbar_user">
		<div class="user_box">
			<!--头像
			<div class="tx_img">
				<img src="/Public/Pc/img/touxiang.png">
			</div>-->
			<div class="text_l user_info">
				<!--<p><?php echo (session('username')); ?></p>-->
				欢迎来到汽车分销平台
			</div>
		</div>
	</div>
	<div class="nav_btn visible-xs-block visible-sm-block">
		<a href="javascript:;"><i class="glyphicon glyphicon-align-justify"></i></a>
	</div>
</div>
<div class="zhegai"></div>
			
			<div class="right_main">
				
				<div class="main_title xgmm">
					<div class="tab_title_box">
						<ul class="tab_title clear_fix">
							<li class="active">修改登录密码</li>
							<li>修改交易密码</li>
						</ul>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<div class="tsy_box">
								<i class="tsy_ico"></i>注意：请务必正确填写，确保您的提现尽快到账
							</div>
							<div class="form_box">
								<div class="tab_contai">
									<div class="tab_contai_1 active">
										
										<form action="" method="post">
											
											<div class="inp_row">
												<p class="text_title">原始登录密码</p>
												<p class="input_box"><input type="password" name="password" id="password" value="" /></p>
											</div>
											<div class="inp_row">
												<p class="text_title">新的登录密码</p>
												<p class="input_box"><input type="password" name="newpassword" id="newpassword" value="" /></p>
											</div>
											<div class="inp_row">
												<p class="text_title">确认登录密码</p>
												<p class="input_box"><input type="password" name="renewpassword" id="renewpassword" value="" /></p>
											</div>
											<div class="btn_box text_r">
												<button type="submit" class="btn" onclick="return Check();">确认提交</button>
											</div>
											
										</form>
										
									</div>
									<div class="tab_contai_1">
										
										<form action="" method="post">
											
											<div class="inp_row">
												<p class="text_title">原始安全密码</p>
												<p class="input_box"><input type="password" name="paypassword" id="paypassword" value="" /></p>
											</div>
											<div class="inp_row">
												<p class="text_title">新的安全密码</p>
												<p class="input_box"><input type="password" name="newpaypassword" id="newpaypassword" value="" /></p>
											</div>
											<div class="inp_row">
												<p class="text_title">确认安全密码</p>
												<p class="input_box"><input type="password" name="renewpaypassword" id="renewpaypassword" value="" /></p>
											</div>
											<div class="btn_box text_r">
												<button type="submit" class="btn" onclick="return editpwd();">更改密码</button>
											</div>
											
										</form>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
		
	</body>
</html>
<script type="text/javascript" src="/Public/Pc/home/js/password.js" ></script>
<script>

	//修改密码
	var editpwd=function()
	{
		if ($.trim($('#paypassword').val()) == '')
		{
			alert('请先输入原始安全密码');
			$('#paypassword').focus();
			return false;
		}
		if($.trim($('#newpaypassword').val()).length<6)
		{
			alert("新安全密码不能小于6位数！");
			$('#newpaypassword').focus();
			return false;
		}
		if ($.trim($('#newpaypassword').val()) == '')
		{
			alert('请先输入新安全密码');
			$('#newpaypassword').focus();
			return false;

		}

		if ($.trim($('#renewpaypassword').val()) == '')
		{
			alert('请先输入确认新安全密码');
			$('#renewpaypassword').focus();
			return false;

		}
		if ($.trim($('#newpaypassword').val()) != $.trim($('#renewpaypassword').val()))
		{
			alert('新安全密码与确认新安全密码不一致，请重新输入！');
			$('#renewpaypassword').focus();
			return false;

		}
	var paypassword,newpaypassword,renewpaypassword;
    paypassword=$.trim($('#paypassword').val());
    newpaypassword=$.trim($('#newpaypassword').val());



		$.ajax({
        type: "POST",
        url: "/pc/wo/doaqmmxg",
        data: {'paypassword':paypassword,'newpaypassword':newpaypassword},
        // 'action':'paypassword','newpaypassword':newpaypassword
//      async:false,
        //dataType: "json",
        success: function(data){
            if(data==1)
            {
                alert('修改安全密码成功！');
                $('#newpaypassword').val('');
                
            }
            if(data==2)
            {
                alert('原安全密码不正确，请输入正确的原安全密码！');
                $('#paypassword').focus();
            }
        }
    })
    return false;

}

</script>