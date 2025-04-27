$(function(){
	function nav(obj){
		$(obj).click(function(){
			if ($(this).find("ul").length==1) {
				var a = $(this).find("ul").css("display");
				if(a == "none"){
					$(this).siblings().find("ul").hide();
					$(this).find("ul").slideDown(200);
					$(this).addClass("bg_color").find("a").addClass("act");
					$(this).siblings().removeClass("bg_color").find("a").removeClass("act");
				}else{
					$(this).find("ul").slideUp("fast");
				};
			};
		});
	}
	nav(".moblie_nav>ul>li");
	nav(".pc_nav_box>ul>li");
	$(".pc_nav_box>ul>li ul a").click(function(event){
		event.stopPropagation();
	})
	$(".moblie_but").click(function(){
		$(".moblie_nav").animate({right: '0px'}, "slow");
	})
	$(".nav_close").click(function(){
//		alert(1)
		$(".moblie_nav").animate({right: '-100%'}, "slow");
	})
	$(".ksxz a").click(function(){
		var num = parseInt($(this).text());
		$(this).addClass("border_jin").parent().siblings().find("a").removeClass("border_jin");
		$("#yzje").val(num);
	})
	$(".wjmm").click(function(){
		$(this).parent().parent().parent().hide().siblings().show();
	})
	$(".qxzh").click(function(){
		$(".zhmm").hide().siblings().show()
	})
})
$(function(){
	function shiz(){
		var shi,
		fen,
		miao;
		var oDate = new Date();
		var hours = oDate.getHours() < 10 ? "0" + oDate.getHours() : oDate.getHours();
		var minutes = oDate.getMinutes() < 10 ? "0" + oDate.getMinutes() : oDate.getMinutes();
		var seconds = oDate.getSeconds() < 10 ? "0" + oDate.getSeconds() : oDate.getSeconds();
		$(".time span").eq(0).text(parseInt(hours/10));
		$(".time span").eq(1).text(hours%10);
		$(".time span").eq(2).text(parseInt(minutes/10));
		$(".time span").eq(3).text(minutes%10);
		$(".time span").eq(4).text(parseInt(seconds/10));
		$(".time span").eq(5).text(seconds%10);
	}
	setInterval(shiz,1000);
})

$(function(){
	function clian(obj){
		$(obj).click(function(){
			var bh = $(this).parent().parent().parent().siblings().css("display");
			if(bh == "block"){
				$(this).parent().parent().parent().siblings().slideUp();
			}else{
				$(this).parent().parent().parent().siblings().slideDown();
			}
		})
	}
	clian(".shouq");
})

