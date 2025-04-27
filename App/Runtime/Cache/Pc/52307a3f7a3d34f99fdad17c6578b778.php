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
					资金转账
				</div>
				
				<div class="tsy_box" style="color: red;">
					<i class="tsy_ico"></i>注意：转换成其他类型积分后将不能转回，请务必考虑清楚后转换，以免造成资金损失！
					<br>
					推广积分：<?php echo ($recharge["integral1"]); ?> &nbsp;注册积分：<?php echo ($recharge["integral2"]); ?>  &nbsp;消费积分：<?php echo ($recharge["integral4"]); ?>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<div class="form_box">
								<form action="/pc/wo/dorecharge" method="post">
									<div class="inp_row">
										<p class="text_title">账户类型</p>
										<p class="input_box">
											<select name="zzlx" id="zzlx" onchange="showBtn()" class="slect">
												<option value="0" checked="checked">请选择转账类型</option>
												<option value="1">推广积分</option>
												<option value="2">注册积分</option>
												<option value="3">消费积分(税费20%)</option>
											</select>
										</p>
									</div>
									<div class="inp_row">
										<p class="text_title">金额</p>
										<p class="input_box"><input type="text" placeholder="请输入你要转让的积分" id="recharge" name="recharge"></p>
									</div>
									
									<div class="inp_row" style="display:none">
										<p class="text_title" id="hhhh">收款人</p>
										<p class="input_box">
											<input type="text" placeholder="请输入你要转让的用户" id="name" name="name" onblur="showUser()" >
										</p>
									</div>
									<div id="showUserBtn"></div>
									
									<div class="inp_row">
										<label>
											<input type="radio"  name="zz" id="xjb" value="1" checked="checked">推广积分&nbsp;&nbsp;
										</label>
										<label style="display:none">
											<input type="radio"  name="zz" id="bdb" value="2">注册积分&nbsp;&nbsp;
										</label>
										<label style="display:none">
											<input type="radio"  name="zz" id="gcjj" value="3">消费积分&nbsp;&nbsp;
										</label>
									</div>
									<div class="inp_row">
										<p class="text_title">密码</p>
										<p class="input_box"><input type="password" placeholder="" id="paypassword" name="paypassword"></p>
									</div>
									<div class="btn_box text_r">
										<button type="submit" class="btn" onclick="return Check();">确认提交</button>
									</div>
								</form>
							</div>
						</div>
					</div>
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
	 function Check()
{
	
	 if ($.trim($('#recharge').val()) == '')
	       {
	           alert('请先输入积分！');
	           $('#recharge').focus();
	           return false;
	       }
	 
//   if ($.trim($('#name').val()) == '')
//     {
//         alert('请输入转让用户！');
//         $('#name').focus();
//         return false;
//     }


     if ($.trim($('#paypassword').val()) == '')
       {
           alert('请输入交易密码！');
           $('#paypassword').focus();
           return false;
       }
 }

function showBtn(){
	if($("#zzlx").val()==0){
		$("#hhhh").parent().show();
	}else if($("#zzlx").val()==1){
		$("#xjb").parent().hide();
		$("#bdb").parent().show();
		$("#gcjj").parent().hide();
		$("#hhhh").parent().hide();
		$("#bdb").prop("checked",true);
	}else if($("#zzlx").val()==2){
		$("#xjb").parent().hide();
		$("#bdb").parent().show();
		$("#gcjj").parent().hide();
		$("#hhhh").parent().show();
		$("#bdb").prop("checked",true);
	}else if($("#zzlx").val()==3){
		$("#xjb").parent().show();
		$("#bdb").parent().hide();
		$("#gcjj").parent().hide();
		$("#hhhh").parent().hide();
		$("#xjb").prop("checked",true);
	}
}

</script>