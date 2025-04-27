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
					帐号激活
				</div>
				
				<div class="table_box">
					
					<div class="table_select">
						
						<div class="table_select_gruop">
							
							<input type="text" placeholder="请输入会员名称" name="name" id="name" value=""/>
							
						</div>
						
						<div class="table_select_btn">
							
							<button class="sele_btn" onclick="showUser()">搜索</button>
							
						</div>
						
					</div>
					<div id="showUserBtn"></div>
					
					<div class="tab_body">
						<div class="table-responsive">
						
							<table class="table" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<th>会员帐号</th>
									<th>姓名</th>
									<th>电话号码</th>
									<th>时间</th>
									<th>操作</th>
								</tr>
									<?php if(is_array($jh)): $i = 0; $__LIST__ = $jh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
											<td><?php echo ($vo["username"]); ?></td>
											<td><?php echo ($vo["realname"]); ?></td>
											<!--<td>-</td>-->
											<td><?php echo ($vo["mobile"]); ?></td>
											<td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>
											<td>
												<div>
	
										
													<?php if($vo["status"] == 0 ): ?><a href="/pc/wo/jhzh.html?id=<?php echo ($vo["id"]); ?>" class="caozuo_btn">激活</a>
														<!-- <a href="/pc/wo/jh?id=<?php echo ($vo["id"]); ?>" class="caozuo_btn">激活</a> -->
													<?php else: ?>
														<a class="caozuo_btn ck_btn">已激活</a><?php endif; ?>
												</div>
											</td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</table>
						</div>
						
					</div>
					<div class="tab_fy clear_fix">
						<div class="float_r">
							<div class="fy_box">
								<?php echo ($page); ?>
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
			
		</div>
		
	</body>
<script>
	function showUser(){
	var name = $("#name").val();
	$.ajax({
		url:"<?php echo U('showUs');?>",
		data:{'name':name},
		dataType:'json',
//		async:false,
		type:'post',
		success:function(data){
			if(data.status==0){
				$("#showUserBtn").html("<font color='red'>您输入的用户不存在</font>");
			}else if(data.status==1){
				$("#showUserBtn").html("<font color='green'>"+'姓名:'+data.realname+'<br/>'+'电话:'+data.mobile+"</font>");
			}
		}
	});
}
</script>
</html>