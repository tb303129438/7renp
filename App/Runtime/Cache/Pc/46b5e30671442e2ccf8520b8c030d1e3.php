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
					推荐列表
				</div>
				
					<div class="tsy_box">
						<i class="tsy_ico"></i>推荐会员：总共有<?php echo ($rdc); ?>条记录
					</div>
					<!-- <div class="table_select">
						
						<div class="table_select_gruop">
							
							<input type="text" placeholder="请输入会员名称" name="name" id="name" value=""/>
							
						</div>
						
						<div class="table_select_btn">
							
							<button class="sele_btn" onclick="showUser()">搜索</button>
							
						</div>
						
					</div>
					<div id="showUserBtn"></div> -->
					<div class="tab_body">
						<div class="table-responsive">
							<table class="table" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<th>会员账号</th>
									<th>会员姓名</th>
									<th>手机号码</th>
									<th>分盘轮数</th>
									<th>分盘详情</th>
									<th>资格所属</th>
									<th>注册日期</th>
								</tr>
										<?php if(is_array($rqt)): $i = 0; $__LIST__ = $rqt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
											<td><?php echo ($vo["username"]); ?></td>
											<td><?php echo ($vo["realname"]); ?></td>
											<td><?php echo ($vo["mobile"]); ?></td>
											<td><?php echo ($vo["rounds"]); ?></td>
											<td>
												<a href="/pc/wo/wtjg/id/<?php echo ($vo["id"]); ?>" style="color: #007AFF;">详情</a>
												
											</td>
											<td >
												<a href="/pc/wo/wtjg?id=<?php echo ($vo[ss]); ?>" style="color: brown;"><?php echo ($vo["number1"]); ?></a>
												<!--<a href="/pc/wo/wtjg?id=<?php echo ($vo[sss]); ?>" style="color: brown;"><?php echo ($vo["number2"]); ?></a>-->
											</td>
											<td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
										<!--<tr>
											<td>mc123456</td>
											<td>admin</td>
											<td>13800138000</td>
											<td>6</td>
											<td>111</td>
											<td>111</td>
											<td>1989-09-05 13:00:00</td>
										</tr>
										<tr>
											<td>mc123456</td>
											<td>admin</td>
											<td>13800138000</td>
											<td>6</td>
											<td>111</td>
											<td>111</td>
											<td>1989-09-05 13:00:00</td>
										</tr>-->
							</table>
						</div>
					</div>
<!--					
<div>

    <nav>
        <ul class="pagination pagination-sm pull-right">

            <li>
                <a href="<?php echo ($pageurl); ?>page=1<?php echo ($search); ?>">首页</a>

            </li>
            <li>
                <?php if($page == 1): ?><a href="<?php echo ($pageurl); ?>page=1<?php echo ($search); ?>">上一页</a>
                    <?php else: ?>
                    <a href="<?php echo ($pageurl); ?>page=<?php echo ($page-1); echo ($search); ?>">上一页</a><?php endif; ?>
            </li>
            <?php if($count < 5): $__FOR_START_657287601__=0;$__FOR_END_657287601__=$count;for($i=$__FOR_START_657287601__;$i < $__FOR_END_657287601__;$i+=1){ if($page == $i+1): ?><li class="active"><a ><?php echo ($i+1); ?></a></li>
                        <?php else: ?>
                        <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i+1); echo ($search); ?>"><?php echo ($i+1); ?></a></li><?php endif; } ?>
                <?php else: ?>
                <?php if($page <= 2): $__FOR_START_2028985562__=1;$__FOR_END_2028985562__=6;for($i=$__FOR_START_2028985562__;$i < $__FOR_END_2028985562__;$i+=1){ if(($i) == $page): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($page); echo ($search); ?>" ><?php echo ($page); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php elseif($count-$page < 5): ?>
                    <?php $__FOR_START_99457657__=$count-4;$__FOR_END_99457657__=$count+1;for($i=$__FOR_START_99457657__;$i < $__FOR_END_99457657__;$i+=1){ if(($page) == $i): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>" ><?php echo ($i); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php else: ?>
                    <?php $__FOR_START_164853878__=$page-2;$__FOR_END_164853878__=$page+3;for($i=$__FOR_START_164853878__;$i < $__FOR_END_164853878__;$i+=1){ if(($page) == $i): ?><li  class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } endif; endif; ?>
            <li>
                <?php if($page == $count): ?><a href="#">下一页</a>
                    <?php else: ?>
                    <a href="<?php echo ($pageurl); ?>page=<?php echo ($page+1); echo ($search); ?>">下一页</a><?php endif; ?>
            </li>
            <li>
                <a href="<?php echo ($pageurl); ?>page=<?php echo ($count); echo ($search); ?>">尾页</a>
            </li>
            <li>
                <a ><input type="text" style="width:40px;margin: 0px;padding: 0px;height:16px;border-color:#337ab7"; id="topage"></a>
            </li>
            <li>
               <a href="javascript:gopage()"> 跳转</a>
            </li>
            <li>
                <input type="hidden" id="pagecount" name="pagecount" value="<?php echo ($count); ?>">
                <a >共<?php echo ($count); ?>页<?php echo ($total); ?>条记录</a>

            </li>
        </ul>

    </nav>

</div>
<script>
    var gopage=function()
    {
        if($.trim($('#topage').val())=='')
        {
            alert('请输入要跳转的页数');
            $('#topage').focus();
            return;
        }
        var reg = new RegExp("^[0-9]*$");
        if(!reg.test($.trim($('#topage').val()))){
            alert("请输入整数!");
            $('#topage').focus();
            return;
        }
        if(parseInt($.trim($('#topage').val()),10)>parseInt($.trim($('#pagecount').val()),10))
        {
            alert('输入页数大于总页数');
            $('#topage').focus();
            return;
        }



        location.href='<?php echo ($pageurl); ?>page='+$('#topage').val()+'<?php echo ($search); ?>';
    }
</script>-->
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