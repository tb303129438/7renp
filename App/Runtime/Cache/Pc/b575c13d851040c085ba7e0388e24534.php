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
					网体结构
				</div>
				
				<div class="table_box">
					<form action="/pc/wo/wtjg" method="post"> 
					<div class="table_select">
						
						<div class="table_select_gruop">
							
							<input type="text" placeholder="请输入会员名称" name="name" id="name" value=""/>
							
						</div>
						
						<div class="table_select_btn">
							
							<button class="sele_btn" >搜索</button>		<!--onclick="showUser()"-->
							
						</div>
					</div>
					</form>
					<div id="showUserBtn"></div>
					<div class="wtjg_big_box">
						<div class="wtjg_box">
							
							<div class="usea_box mar_auto bottom_solid">
								<ul class="user_gruop text_c">
									<li class="user_list">
										<p class="title_name">编号：<?php echo ($us["username"]); ?>(<?php echo ($us["realname"]); ?>)</p>
									</li>
									<li class="user_list">
										<p class="title_name"><?php echo ($us["realname"]); ?></p>
									</li>
									<li class="user_list">
										<p>推荐：<?php if($us["tusername"] == ''): ?>无<?php else: echo ($as["username"]); ?>(<?php echo ($as["realname"]); ?>)<?php endif; ?></p>
									</li>
									<li class="user_list clear_fix">
										<div class="zige float_l">资格</div>
										<div class="tow_name float_l">
											<p><?php if($a01["username"] == ''): ?>无<?php else: echo ($a01["username"]); endif; ?></p>
										</div>
										<div class="tow_name float_l">
											<p><?php if($a01["username"] == ''): ?>无<?php else: echo ($a02["username"]); endif; ?></p>
										</div>
									</li>
									<li class="user_list">
										<p>
											<?php if($us["createtime"] != ''): echo (date('Y-m-d',$us["createtime"])); endif; ?>
										</p>
									</li>
								</ul>
							</div>
							<div class="tow_wt mar_auto">
								<div class="usea_box float_l bottom_solid ">
									<ul class="user_gruop text_c">
										<li class="user_list">
											<p class="title_name">编号：<?php echo ($tu1["username"]); ?>(<?php echo ($tu1["realname"]); ?>)</p>
										</li>
										<li class="user_list">
											<p>推荐：<?php echo ($tu1["tusername"]); ?>(<?php echo ($tuu1["realname"]); ?>)</p>
										</li>
										<li class="user_list clear_fix">
											<div class="zige float_l">资格</div>
											<div class="tow_name float_l">
												<p><?php echo ($a11["username"]); ?></p>
											</div>
											<div class="tow_name float_l">
												<p><?php echo ($a12["username"]); ?></p>
											</div>
										</li>
										<li class="user_list">
											<p>
												<?php if($tu1["createtime"] != ''): echo (date('Y-m-d',$tu1["createtime"])); endif; ?>
											</p>
										</li>
									</ul>
								</div>
								<div class="usea_box float_r bottom_solid">
									<ul class="user_gruop text_c">
										<li class="user_list">
											<p class="title_name">编号：<?php echo ($tu2["username"]); ?>(<?php echo ($tu2["realname"]); ?>)</p>
										</li>
										<li class="user_list">
											<p>推荐：<?php echo ($tu2["tusername"]); ?>(<?php echo ($tuu2["realname"]); ?>)</p>
										</li>
										<li class="user_list clear_fix">
											<div class="zige float_l">资格</div>
											<div class="tow_name float_l">
												<p><?php echo ($a21["username"]); ?></p>
											</div>
											<div class="tow_name float_l">
												<p><?php echo ($a22["username"]); ?></p>
											</div>
										</li>
										<li class="user_list">
											<p>
												<?php if($tu2["createtime"] != ''): echo (date('Y-m-d',$tu2["createtime"])); endif; ?>
											</p>
										</li>
									</ul>
								</div>
							</div>
							<div class="tree_wt clear_fix">
								<div class="tree_left float_l">
									<div class="usea_box float_l">
										<ul class="user_gruop text_c">
											<li class="user_list">
												<p class="title_name">编号：<?php echo ($tu3["username"]); ?>(<?php echo ($tu3["realname"]); ?>)</p>
											</li>
											<li class="user_list">
												<p>推荐：<?php echo ($tu3["tusername"]); ?>(<?php echo ($tuu3["realname"]); ?>)</p>
											</li>
											<li class="user_list clear_fix">
												<div class="zige float_l">资格</div>
												<div class="tow_name float_l">
													<p><?php echo ($a31["username"]); ?></p>
												</div>
												<div class="tow_name float_l">
													<p><?php echo ($a32["username"]); ?></p>
												</div>
											</li>
											<li class="user_list">
												<p>
													<?php if($tu3["createtime"] != ''): echo (date('Y-m-d',$tu3["createtime"])); endif; ?>
												</p>
											</li>
										</ul>
									</div>
									<div class="usea_box float_r">
										<ul class="user_gruop text_c">
											<li class="user_list">
												<p class="title_name">编号：<?php echo ($tu4["username"]); ?>(<?php echo ($tu4["realname"]); ?>)</p>
											</li>
											<li class="user_list">
												<p>推荐：<?php echo ($tu4["tusername"]); ?>(<?php echo ($tuu4["realname"]); ?>)</p>
											</li>
											<li class="user_list clear_fix">
												<div class="zige float_l">资格</div>
												<div class="tow_name float_l">
													<p><?php echo ($a41["username"]); ?></p>
												</div>
												<div class="tow_name float_l">
													<p><?php echo ($a42["username"]); ?></p>
												</div>
											</li>
											<li class="user_list">
												<p>
												<?php if($tu4["createtime"] != ''): echo (date('Y-m-d',$tu4["createtime"])); endif; ?>
												</p>
											</li>
										</ul>
									</div>
								</div>
								<div class="tree_right float_r">
									<div class="usea_box float_l">
									<ul class="user_gruop text_c">
										<li class="user_list">
											<p class="title_name">编号：<?php echo ($tu5["username"]); ?>(<?php echo ($tu5["realname"]); ?>)</p>
										</li>
										<li class="user_list">
											<p>推荐：<?php echo ($tu5["tusername"]); ?>(<?php echo ($tuu5["realname"]); ?>)</p>
										</li>
										<li class="user_list clear_fix">
											<div class="zige float_l">资格</div>
											<div class="tow_name float_l">
												<p><?php echo ($a51["username"]); ?></p>
											</div>
											<div class="tow_name float_l">
												<p><?php echo ($a52["username"]); ?></p>
											</div>
										</li>
										<li class="user_list">
											<p>
												<?php if($tu5["createtime"] != ''): echo (date('Y-m-d',$tu5["createtime"])); endif; ?>
											</p>
										</li>
									</ul>
								</div>
								<div class="usea_box float_r">
									<ul class="user_gruop text_c">
										<li class="user_list">
											<p class="title_name">编号：<?php echo ($tu6["username"]); ?>(<?php echo ($tu6["realname"]); ?>)</p>
										</li>
										<li class="user_list">
											<p>推荐：<?php echo ($tu6["tusername"]); ?>(<?php echo ($tuu6["realname"]); ?>)</p>
										</li>
										<li class="user_list clear_fix">
											<div class="zige float_l">资格</div>
											<div class="tow_name float_l">
												<p><?php echo ($a61["username"]); ?></p>
											</div>
											<div class="tow_name float_l">
												<p><?php echo ($a62["username"]); ?></p>
											</div>
										</li>
										<li class="user_list">
											<p>
												<?php if($tu6["createtime"] != ''): echo (date('Y-m-d',$tu6["createtime"])); endif; ?>
											</p>
										</li>
									</ul>
								</div>
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

	scrollAuto();

</script>
</html>