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
    <script type="text/javascript" src="/Public/Dt/lib/jedate/jedate.min.js" ></script>
    <div class="main">
        <div class="index_cont">


            <div class="table-responsive table_box">
                <div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#panel-32409" data-toggle="tab">会员列表</a>
                        </li>
                    </ul>
                </div>
                <div class="selct">
                   <!--  <form class="form-inline" role="form">
                       
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">关键字</div>
                                <input id="k" name="k" class="form-control " type="text" placeholder="输入关键字" value="<?php echo ($keyword); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">开始时间</div>
                                <input id="b" name="b" class="form-control stu1" type="text" placeholder="输入开始日期" value="<?php echo (date('Y-m-d',$begintime)); ?>" onfocus="getbigtime();" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">结束时间</div>
                                <input id="e" name="e" class="form-control stu2" type="text" placeholder="输入结束日期" value="<?php echo (date('Y-m-d',$endtime)); ?>" onfocus="getsmalltime();" readonly >
                            </div>
                        </div>
                        <script type="text/javascript">
                            jeDate({
                                dateCell:".stu1",
                                format:"YYYY-MM-DD",
                                isTime:false,
                                minDate:"1900-1-1",
                                maxDate:"2099-12-12"
                            });
                            jeDate({
                                dateCell:".stu2",
                                format:"YYYY-MM-DD",
                                isTime:false,
                                minDate:"1900-1-1",
                                maxDate:"2099-12-12"
                            });

                            

                        </script>
                        <input type="button" class="btn btn-default" name="button" id="button" value="查询"  onclick="searchuser();" />
                    </form> -->
                </div>
                
                <table class="table">
                    <tr>

                        <!-- <th>会员编号</th> -->
                        <th>会员帐户</th>
                        <th>姓名</th>
                        <th>手机</th>
                        <th>所购车辆</th>
                        <th>提车时间</th>
                        <th>申请时间</th>
                        <th>操作</th>
                    </tr>
                    <?php if(is_array($room)): $i = 0; $__LIST__ = $room;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

                        <!-- <td><?php echo ($vo["id"]); ?></td> -->
                        <td><?php echo ($vo["username"]); ?></td>
                        <td><?php echo ($vo["realname"]); ?></td>
                        <td><?php echo ($vo["mobile"]); ?></td>
                        <td><?php echo ($vo["by"]); ?></td>
                        <th><?php echo ($vo["time"]); ?></th>
                        <td><?php echo (date("Y-m-d H:i:s",$vo["createtime"])); ?></td>

                        <td>
                            <a href="/admin/index/gcsqcx.html?id=<?php echo ($vo["id"]); ?>">查看</a> |
                            
                            <?php if($vo["status"] == 0): ?><a href="/admin/index/gcsqcx_edit?id=<?php echo ($vo["id"]); ?>">审核通过</a> |
                            <a href="/admin/index/gcsqcx_sb?id=<?php echo ($vo["id"]); ?>">审核失败</a>
                            <?php else: ?>
                            审核通过<?php endif; ?> |
                            <a href="/admin/index/delgcsq.html?id=<?php echo ($vo["id"]); ?>">删除</a> 
                           <br>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                	
                	
                </table>
				<?php if(($total) > $pagesize): ?><div>

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
            <?php if($count < 5): $__FOR_START_1906762529__=0;$__FOR_END_1906762529__=$count;for($i=$__FOR_START_1906762529__;$i < $__FOR_END_1906762529__;$i+=1){ if($page == $i+1): ?><li class="active"><a ><?php echo ($i+1); ?></a></li>
                        <?php else: ?>
                        <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i+1); echo ($search); ?>"><?php echo ($i+1); ?></a></li><?php endif; } ?>
                <?php else: ?>
                <?php if($page <= 2): $__FOR_START_1558496305__=1;$__FOR_END_1558496305__=6;for($i=$__FOR_START_1558496305__;$i < $__FOR_END_1558496305__;$i+=1){ if(($i) == $page): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($page); echo ($search); ?>" ><?php echo ($page); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php elseif($count-$page < 5): ?>
                    <?php $__FOR_START_1340875512__=$count-4;$__FOR_END_1340875512__=$count+1;for($i=$__FOR_START_1340875512__;$i < $__FOR_END_1340875512__;$i+=1){ if(($page) == $i): ?><li class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>" ><?php echo ($i); ?></a></li>
                            <?php else: ?>
                            <li ><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li><?php endif; } ?>
                    <?php else: ?>
                    <?php $__FOR_START_1274875159__=$page-2;$__FOR_END_1274875159__=$page+3;for($i=$__FOR_START_1274875159__;$i < $__FOR_END_1274875159__;$i+=1){ if(($page) == $i): ?><li  class="active"><a href="<?php echo ($pageurl); ?>page=<?php echo ($i); echo ($search); ?>"><?php echo ($i); ?></a></li>
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
</script><?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>