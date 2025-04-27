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
				
				<div class="main_title">
					激活帐号
				</div>
				
					<div class="tsy_box">
						<i class="tsy_ico"></i>注意：激活会员将扣取您的注册积分，激活会员时请务必需谨慎
					</div>
				<div class="form_box">
					<form action="/pc/wo/dojhzh" method="post">
						
						<div class="inp_row">
							<p class="text_title">您的会员账号</p>
							<p class="input_box"><input type="text" name="" id="" value="<?php echo (session('username')); ?>" placeholder="" disabled="true "/></p>
						</div>
						<div class="inp_row">
							<p class="text_title">注册积分余额</p>
							<p class="input_box"><input type="text" name="" id="" value="<?php echo ($integral["integral2"]); ?>" placeholder=""disabled="true "/></p>
						</div>
						<div class="inp_row">
							<p class="text_title">激活会员帐号</p>
							<p class="input_box"><input type="text" name="name" id="name" value="<?php echo ($data["username"]); ?>" placeholder=""  readOnly="true"/></p>
						</div>
						<div id="showUserBtn"></div>
						<div class="inp_row">
							<p class="text_title">所属组长</p>
							<p class="input_box"><input type="text" name="team" id="team" value="" placeholder="" /></p>
						</div>
						<!-- <div class="inp_row">
							<p class="text_title">安全密码</p>
							<p class="input_box"><input type="password" name="password" id="password" value="" placeholder="" /></p>
						</div> -->
						<div class="inp_row">
							<p class="text_title">所需注册积分</p>
							<p class="input_box"><input type="text" name="price" id="price" value="<?php echo ($pdata[18]['number']); ?>" placeholder="" disabled="true "/></p>
						</div>
						<div class="btn_box text_r">
						<input type="hidden" id="id" name="id" value="<?php echo ($data["id"]); ?>">
							<button type="submit" class="btn" >确认提交</button>			<!--onclick="return Check();"-->
						</div>
					</form>
				</div>
				
			</div>
			
		</div>
		
	</body>
</html>
<script>
function showUser(){
	var name = $("#name").val();
	$.ajax({
		url:"<?php echo U('showUsers');?>",
		data:{'name':name},
		dataType:'json',
//		async:false,
		type:'post',
		success:function(data){
			if(data.status==0){
				$("#showUserBtn").html("<font color='red'>您输入的用户不存在</font>");
			}else if(data.status==1){
				$("#showUserBtn").html("<font color='green'>"+data.realname+"</font>");
			}
		}
	});
}
</script>