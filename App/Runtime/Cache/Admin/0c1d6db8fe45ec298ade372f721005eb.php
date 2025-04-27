<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>后台管理系统</title>
    <link href="/Public/Dt/image/favicon.ico" rel="icon" type="image/x-icon"/>
    <!--bootstrap-css-->
    <link rel="stylesheet" type="text/css" href="/Public/Dt/lib/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Dt/css/base.css" />
    <!--[if lte IE 9]>
    <script type="text/javascript" src="/Public/Dt/lib/html5shiv/html5shiv.min.js" ></script>
    <script type="text/javascript" src="/Public/Dt/lib/html5shiv/html5shiv-printshiv.min.js" ></script>
    <![endif]-->
    <script type="text/javascript" src="/Public/Dt/js/jquery-1.9.1.min.js" ></script>


    <script type="text/javascript" src="/Public/Dt/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/Public/Dt/js/index.js" ></script>
</head>

<body>

<div id="left" class="hidden-xs">
    <div class="logo">
        <h1>
            <a href="#">
                <img src="/Public/Dt/image/admin-logo.jpg"/>
            </a>
        </h1>
    </div>
    <div class="time">
        <div>
            <span>0</span><span>6</span>:<span>2</span><span>6</span>:<span>1</span><span>2</span>
        </div>
    </div>
    <div class="pc_nav clearfix">
        <div class="pc_nav_box">
            <ul>
                <li <?php if(checknav('admin/index/index.html') == 1 ): ?>class="bg_color"<?php endif; ?>>
                <span class="ico1"></span>
                <a href="/admin/index/index.html">后台首页</a>
                </li>


                <li  <?php if(checknav('/password.html') == 1 or checknav('/setting.html') == 1 or checknav('/parameter.html') == 1 or checknav('/deldir.html') == 1 or checknav('admin/ad/index.html') == 1): ?>class="bg_color"<?php endif; ?> <?php if(power(1) == 0): ?>style="display:none"<?php endif; ?>>
                <span class="ico4"></span>
                <a href="javascript:;">系统配置</a>
                    <ul >

                        <li  <?php if(power(8) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/password.html"  <?php if(checknav('/password.html') == 1): ?>class="fous"<?php endif; ?>>修改密码</a>
                        </li>
                       
 
                        <!--<li <?php if(power(9) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/setting.html"  <?php if(checknav('/setting.html') == 1): ?>class="fous"<?php endif; ?>>开关设置</a>
                        </li>-->


                        <li >
                            <a href="/admin/index/parameter.html" <?php if(checknav('/parameter.html') == 1): ?>class="fous"<?php endif; ?>>参数设置</a>
                        </li>
 
                        <li <?php if(power(9) == 0): ?>style="display:none"<?php endif; ?>>
                        <a href="/admin/index/deldir.html" <?php if(checknav('/deldir.html') == 1): ?>class="fous"<?php endif; ?>>清除缓存</a>
                        </li>


                        


                    </ul>
                </li>



                <li <?php if(checknav('/adminadd.html') == 1 or checknav('/adminedit.html') == 1 or checknav('/admin.html') == 1): ?>class="bg_color"<?php endif; ?> <?php if(power(2) == 0): ?>style="display:none"<?php endif; ?>>
                    <span class="ico2"></span>
                    <a href="javascript:;">管理员管理</a>
                    <ul >

                        <li <?php if(power(11) == 0): ?>style="display:none"<?php endif; ?>>

                            <a href="/admin/index/adminadd.html"  <?php if(checknav('/adminadd.html') == 1): ?>class="fous"<?php endif; ?>>添加管理员</a>
                        </li>


                        <li <?php if(power(12) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/admin.html" <?php if(checknav('/admin.html') == 1 or checknav('/adminedit.html') == 1): ?>class="fous"<?php endif; ?>>管理员列表</a>
                        </li>

                    </ul>
                </li>


                <li <?php if(checknav('/user_add.html') == 1 or checknav('/member.html') == 1 or checknav('/team.html') == 1 or checknav('/sqbd.html') == 1 or checknav('/gcsq.html') == 1): ?>class="bg_color"<?php endif; ?> <?php if(power(3) == 0): ?>style="display:none"<?php endif; ?>>
                <span class="ico3"></span>
                    <a href="javascript:;">会员管理中心</a>
                    <ul >

                      <!--   <li <?php if(power(13) == 0): ?>style="display:none"<?php endif; ?>>

                            <a href="/admin/index/user_add.html" <?php if(checknav('/user_add.html') == 1): ?>class="fous"<?php endif; ?>>新会员注册</a>
                        </li>
 -->

                        <li <?php if(power(14) == 0): ?>style="display:none"<?php endif; ?>>
                        	<a href="/admin/index/member.html?t=3" <?php if(checknav('/member.html?t=3') == 1 ): ?>class="fous"<?php endif; ?>>未激活会员</a>
                            <a href="/admin/index/member.html?t=1" <?php if(checknav('/member.html?t=1') == 1 ): ?>class="fous"<?php endif; ?>>已激活会员</a>
                            <a href="/admin/index/member.html?t=2" <?php if(checknav('/member.html?t=2') == 1): ?>class="fous"<?php endif; ?>>服务中心</a>
                        </li>
                        <li <?php if(power(15) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/sqbd.html" <?php if(checknav('/sqbd.html') == 1): ?>class="fous"<?php endif; ?>>申请报单中心</a>
                        </li>
     					<li <?php if(power(16) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/gcsq.html" <?php if(checknav('/gcsq.html') == 1): ?>class="fous"<?php endif; ?>>购车申请审核</a>
                        </li>
                    </ul>
                </li>


               



                <li <?php if(checknav('admin/index/cz.html') == 1 or checknav('admin/index/txjl.html') == 1 or checknav('admin/index/jjjl.html') == 1): ?>class="bg_color"<?php endif; ?> <?php if(power(5) == 0): ?>style="display:none"<?php endif; ?>>
                <span class="ico5"></span>
                    <a href="javascript:;">财务管理</a>
                    <ul >
                        <li <?php if(power(15) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/cz.html" <?php if(checknav('/cz.html') == 1): ?>class="fous"<?php endif; ?>>充值</a>
                        </li>
                        <li>
                            <a href="/admin/index/txjl.html"  <?php if(checknav('admin/index/txjl.html') == 1): ?>class="fous"<?php endif; ?>>提现记录</a>
                        </li>
                        <li>
                            <a href="/admin/index/jjjl.html"  <?php if(checknav('admin/index/jjjl.html') == 1): ?>class="fous"<?php endif; ?>>积分记录</a>
                        </li>
                    </ul>
                </li>

               <!--  <li <?php if(checknav('/product_add.html') == 1 or checknav('/product_edit.html') == 1 or checknav('/product.html') == 1 or checknav('admin/cotegary/index.html') == 1 or checknav('admin/index/order.html') == 1): ?>class="bg_color"<?php endif; ?> <?php if(power(6) == 0): ?>style="display:none"<?php endif; ?>>
                <span class="ico6"></span>
                    <a href="javascript:;">产品管理</a>
                    <ul >
                      <li  <?php if(power(27) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/cotegary/index.html" <?php if(checknav('admin/cotegary/index.html') == 1): ?>class="fous"<?php endif; ?>>产品分类</a>
                        </li>
                        <li  <?php if(power(27) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/product_add.html" <?php if(checknav('/product_add.html') == 1): ?>class="fous"<?php endif; ?>>添加产品</a>
                        </li>
                        <li <?php if(power(28) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/product.html" <?php if(checknav('/product_edit.html') == 1 or checknav('/product.html') == 1): ?>class="fous"<?php endif; ?>>产品管理</a>
                        </li>

                       <!--  <li <?php if(power(28) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/order.html" <?php if(checknav('admin/index/order.html') == 1 or checknav('admin/index/order.html') == 1): ?>class="fous"<?php endif; ?>>订单管理</a>
                        </li> -->
                        
                        <!-- <li <?php if(power(28) == 0): ?>style="display:none"<?php endif; ?>>
                            <a href="/admin/index/models.html" <?php if(checknav('admin/index/models.html') == 1 or checknav('admin/index/models.html') == 1): ?>class="fous"<?php endif; ?>>车型管理</a>
                        </li> 

                    </ul>
                </li> -->


                <li <?php if(checknav('/news.html') == 1 or checknav('/news_add.html') == 1 or checknav('/message.html') == 1 ): ?>class="bg_color"<?php endif; ?> <?php if(power(7) == 0): ?>style="display:none"<?php endif; ?>>
                    <span class="ico7"></span>
                    <a href="javascript:;">交流中心</a>
                    <ul >

                        <!--<li <?php if(power(29) == 0): ?>style="display:none"<?php endif; ?>><a href="/admin/index/news_add.html"  <?php if(checknav('/news_add.html') == 1): ?>class="fous"<?php endif; ?>>添加公告</a></li>-->

                        <!--<li <?php if(power(30) == 0): ?>style="display:none"<?php endif; ?>><a href="/admin/index/news.html"  <?php if(checknav('/news.html') == 1): ?>class="fous"<?php endif; ?>>网站公告</a></li>-->

                        <li <?php if(power(30) == 0): ?>style="display:none"<?php endif; ?>><a href="/admin/index/help_add.html"  <?php if(checknav('/help_add.html') == 1): ?>class="fous"<?php endif; ?>>添加公告</a></li>
                        
                        <li <?php if(power(30) == 0): ?>style="display:none"<?php endif; ?>><a href="/admin/index/help.html"  <?php if(checknav('/help.html') == 1): ?>class="fous"<?php endif; ?>>公告列表</a></li>

                            <li <?php if(power(31) == 0): ?>style="display:none"<?php endif; ?>><a href="/admin/index/message.html"  <?php if(checknav('/message.html') == 1): ?>class="fous"<?php endif; ?>>留言反馈</a></li>

                    </ul>
                </li>

            </ul>
            <a href="/admin/index/logout.html" class="exit">
               <img src="/Public/Dt/image/exit.png"/>
                安全退出
            </a>
        </div>
    </div>
</div>

<!--手机导航-->

<div class="moblie_nav visible-xs-block">
    <p class="moblie_nav_top clearfix">
        <span class="glyphicon glyphicon-remove nav_close pull-right"></span>
    </p>
    <ul>
        <li class="bg_color">
            <a href="/admin/index/index.html">后台首页</a>
        </li>


        <li>
            <a href="javascript:;">系统配置</a>
            <ul style="display: none;">
                <li >

                    <a href="/admin/index/password.html" >修改密码</a>
                </li>
                <li>
                    <a href="/admin/ad/index.html" >广告管理</a>
                </li>
                <!-- <li>
                    <a href="/admin/index/parameter.html">积分参数设置</a>

                </li> -->
                <li>
                    <a href="/admin/index/deldir.html" >清除缓存</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;">会员管理中心</a>
            <ul style="display: none;">


                   <li >
                            <a href="/admin/index/member.html?t=3"  >未激活会员</a>
                            <a href="/admin/index/member.html?t=1" >已激活会员</a>
                            <a href="/admin/index/member.html?t=2"  >服务中心</a>
                        </li>
                        <li  >
                            <a href="/admin/index/sqbd.html"  >申请报单中心</a>
                        </li>
                        <li  >
                            <a href="/admin/index/gcsq.html" >购车申请审核</a>
                        </li>

                    

            </ul>
        </li>
        <li>
            <a href="javascript:;">财务管理</a>
            <ul style="display: none;">
                <!--  <li>
                    <a href="/admin/index/xj.html" >用户积分钱包</a>
                 </li>
                 <li>
                     <a href="/admin/index/gcjj.html" >销售奖钱包</a>
                </li>
                <li> -->
                        <!-- <a href="/admin/index/team.html">推荐网络</a> -->
                        <a href="/admin/index/cz.html" >充值</a>
                    </li>
                    <li>
                        <a href="/admin/index/txjl.html" >提现记录</a>
                    </li>
                     <li>
                        <a href="/admin/index/jjjl.html" >积分记录</a>
                    </li>

            </ul>
        </li> 
       <!--  <li>
            <a href="javascript:;">产品管理</a>
            <ul style="display: none;">
                <li>
                    <a href="/admin/cotegary/list.html">产品分类</a>
                </li>
                <li>
                    <a href="/admin/index/product_add.html">添加产品</a>
                </li>
                <li>
                    <a href="/admin/index/product.html">产品管理</a>
                </li>

                <!-- <li>
                    <a href="/admin/index/order.html">订单管理</a>
                </li> 

            </ul>
        </li> -->
        <li>
            <a href="javascript:;">交流中心</a>
            <ul style="display: none;">
                <!--<li><a href="/admin/index/news_add.html">添加公告</a></li>-->
                <!--<li><a href="/admin/index/news.html">网站公告</a></li>-->
                <li><a href="/admin/index/help_add.html">添加公告</a></li>
                <li><a href="/admin/index/help.html" >公告列表</a></li>
                <li><a href="/admin/index/message.html">留言反馈</a></li>
            </ul>
        </li>
    </ul>
</div>


<div id="right">
    <div class="top">
    <div class="pull-right moblie_but visible-xs-block">
        <span class="glyphicon glyphicon-align-justify"></span>
    </div>
    <div class="pull-right">

        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php echo (session('adminname')); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">

                <li>
                    <a href="/admin/index/password.html">更改密码</a>
                </li>

                <li class="divider"></li>
                <li>
                    <a href="/admin/index/logout.html">安全退出</a>
                </li>
            </ul>
        </div>
    </div>
</div>
    <!--主要内容块-->
    <div class="main">


        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="tabbable" id="tabs-184023">
                        <div>
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#panel-32409" data-toggle="tab">清除缓存</a>
                                </li>
                            </ul>
                        </div>
                        <div class="wxts2">
                            <p class="zhuyi"><?php echo ($success); ?></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--手机导航-->



</div>


</body>
</html>



<script src="/Public/Admin/js/setting.js" type="text/javascript" charset="utf-8"></script>