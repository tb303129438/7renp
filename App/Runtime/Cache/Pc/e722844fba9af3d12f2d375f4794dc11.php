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
					明细列表
				</div>
				
				<div class="table_box">
					
					<div class="tab_body">
						
						<div class="table-responsive">
							<table class="table" border="0" cellspacing="0" cellpadding="0">
								<tr>
									
									<th>记录说明</th>
									<th>金额变动</th>
									<th>当时余额</th>
									<th>时间</th>
									
								</tr>
								
								
								<?php if(is_array($hh)): $i = 0; $__LIST__ = $hh;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
										
										<td><?php echo ($vo["title"]); ?></td>
										<?php if($vo["status"] == 1 ): ?><td>+<?php echo ($vo["price"]); ?></td>
										<?php else: ?>
											<td>-<?php echo ($vo["price"]); ?></td><?php endif; ?>
										<td><?php echo ($vo["total"]); ?></td>
										<td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>
									
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</table>
						</div>
					</div>
					
					<div style="float: right;">
						
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
            <?php if($count < 5): $__FOR_START_1440024689__=0;$__FOR_END_1440024689__=$count;for($i=$__FOR_START_1440024689__;$i < $__FOR_END_1440024689__;$i+=1){ if($page == $i+1): ?><li class="active"><a ><?php echo ($i+1); ?></a></li>
                        <?php else: ?>
                        <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i+1); echo ($search); ?>"><?php echo ($i+1); ?></a></li><?php endif; } ?>
                <?php else: ?>
                <?php if($page <= 2): $__FOR_START_2115789137__=1;$__FOR_END_2115789137__=6;for($i=$__FOR_START_2115789137__;$i < $__FOR_END_2115789137__;$i+=1){ if(($i) == $page): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($page); echo ($search); ?>" ><?php echo ($page); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php elseif($count-$page < 5): ?>
                    <?php $__FOR_START_271842865__=$count-4;$__FOR_END_271842865__=$count+1;for($i=$__FOR_START_271842865__;$i < $__FOR_END_271842865__;$i+=1){ if(($page) == $i): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>" ><?php echo ($i); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php else: ?>
                    <?php $__FOR_START_54040403__=$page-2;$__FOR_END_54040403__=$page+3;for($i=$__FOR_START_54040403__;$i < $__FOR_END_54040403__;$i+=1){ if(($page) == $i): ?><li  class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li>
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
</script>
					</div>
					
				</div>
				
			</div>
			
		</div>
		
	</body>
</html>