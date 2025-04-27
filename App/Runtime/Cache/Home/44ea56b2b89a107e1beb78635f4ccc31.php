<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1" />
		<title>江西陌车</title>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/css/css/base.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/css/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Mc/libs/iconfont/iconfont.css"/>
		<script type="text/javascript" src="/Public/Mc/libs/jQuery/jquery-1.9.1.min.js" ></script>
		<script type="text/javascript" src="/Public/Mc/home/js/jxmc.js" ></script>
		
		
		
		<link rel="stylesheet" type="text/css" href="/Public/Mc/libs/swiper/css/swiper.min.css"/>
		<script type="text/javascript" src="/Public/Mc/libs/swiper/js/swiper.jquery.min.js" ></script>
	</head>
	<body>
		<div class="zgb"></div>
		<!--<div class="nav_btn"><i class="icon iconfont icon-daohang"></i></div>-->
		<div class="mobile_contai">
			<div class="buy_selct">
				<div class="selct_box">
					<form action="" method="post">
						<input type="text" name="" id="" value="" placeholder="想找什么车型呢？" />
						<button class="btn"><i class="icon iconfont icon-fangdajing"></i></button>
					</form>
				</div>
			</div>
			<div class="banner_box">
				<div class="swiper-container">
				  	<div class="swiper-wrapper">
				    	<div class="swiper-slide">
				    		<a href="/Home/index/xq.html?id=73"><img src="<?php echo ($room[1]["img"]); ?>"/></a>
				    	</div>
				    	<div class="swiper-slide">
				    		<a href="/Home/index/xq.html?id=73"><img src="<?php echo ($room[2]["img"]); ?>"/></a>
				    	</div>
				    	<div class="swiper-slide">
				    		<a href="/Home/index/xq.html?id=71"><img src="<?php echo ($room[3]["img"]); ?>"/></a>
				    	</div>
				  	</div>
				  	<div class="swiper-pagination"></div>
				</div>
			</div>
			<div class="buyCar_box">
				<div class="byCAr_title">
					<a href="#" class="title_href">查看全部 ></a>
				</div>
				<ul class="byCAr_gruop">
					<li class="byCAr_list">
						<a href="/home/index/xq">
							 <?php if(is_array($jr)): $i = 0; $__LIST__ = $jr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="imgBox">
								<a href="/Home/index/xq.html?id=<?php echo ($vo["id"]); ?>"><img src="<?php echo ($vo["pic"]); ?>"/></a>
								<!--<span class="tjyj"></span>-->
							</div>
							<div class="buy_ziliao">
								<p class="info"><?php echo ($vo["productname"]); ?></p>
						        <p class="mony">￥<?php echo ($vo["price"]); ?></p>
							</div><?php endforeach; endif; else: echo "" ;endif; ?>
						</a>
					</li>
				</ul>
			</div>
			
		</div>
		<footer class="footer_nav">
			<div class="footer_nav_box text_c">
				<a href="/home/index/index.html">
					<p><i class="icon iconfont icon-shouye"></i></p>
					<p>首页</p>
				</a>
				<!--<a href="/home/index/buyCar.html" class="active">
					<p><i class="icon iconfont icon-qiche"></i></p>
					<p>车型</p>
				</a>-->
				<!--<a href="chanpin.html">
					<p><i class="icon iconfont icon-chanpin"></i></p>
					<p>产品</p>
				</a>-->
				<?php if($_SESSION['username']== ''): ?><a href="/home/index/login.html">
						<p><i class="icon iconfont icon-denglu"></i></p>
						<p>登陆</p>
					</a>
				<?php else: ?>
					<a href="/home/wo/wo.html">
						<p><i class="icon iconfont icon-denglu"></i></p>
						<p>个人中心</p>
					</a><?php endif; ?>
			</div>
		</footer>
	</body>
	<script type="text/javascript">
	    var swiper = new Swiper('.banner_box .swiper-container', {
	    	autoplay: 3000,
	    	loop : true,
	        pagination: '.swiper-pagination',
	        paginationClickable: true
	    });
	</script>
</html>