$(function(){
	
	$('.nav_box > dl > dt').hover(
		
		function(){
			
			$(this).children('.fenlei').show().find('li').hover(
				
				function(){
					
					$(this).children('.sp_list').show();
					
				},function(){
					
					$(this).children('.sp_list').hide();
					
				}
				
			);
			
		},function(){
			
			$(this).children('.fenlei').hide();
			
		}
	)
	
	$('.fenlei_list > li').hover(
		function(){
			
			$(this).addClass('act');
		
		},function(){
			
			$(this).removeClass('act')
			
		})
	
	
})



$(function(){
	
	var len = $('.sile_box > div').length;
	var num = 0;
	var timer;
	
	$('.prve').click(function(){
		
		num - 1 < 0 ? num = len-1 : num -= 1;
		
		$('.sile_box > div').fadeOut(500).eq(num).fadeIn(500);
		
		$('.sile_box > ul li').eq(num).addClass('act').siblings().removeClass('act');
		
	})
	
	$('.next').click(function(){
		
		clckNext();
		
	})
	
	$('.sile_box > ul li').click(function(){
		
		num = $(this).index();
		
		$('.sile_box > div').fadeOut(500).eq(num).fadeIn(500);
		
		$(this).addClass('act').siblings().removeClass('act');
		
	})
	
	function clckNext(){
		
		num + 1 >= len ? num = 0 : num += 1;
		
		$('.sile_box > div').fadeOut(500).eq(num).fadeIn(500);
		
		$('.sile_box > ul li').eq(num).addClass('act').siblings().removeClass('act');
		
	}
	
	timer = setInterval(clckNext,3000);
	
	$('.sile_box').hover(function(){
		
		clearInterval(timer);
		
	},function(){
		
		timer = setInterval(clckNext,3000);
		
	})
})


    

$(function(){
	//主图切换
	$('.sm_img_list li').mouseover(function(){
		
		var src = $(this).find('img').attr('src');
		
		$(this).addClass('bor-color').siblings().removeClass('bor-color')
	
		$('.xq_big_img').find('img').attr('src',src);
		
	})
	//颜色类型选择
	$('ul[nctyle="ul_sign"]').children('.sp-img').click(function(){
		
		$(this).children('a').addClass('hovered').parent('li').siblings().children('a').removeClass('hovered')
		
	})
	
	
	
})
function tab(obj,aim){
		
	$(obj).children('li').click(function(){
		
		var Inx = $(this).index();
		
		$(this).addClass('current').siblings().removeClass('current');
		
		$(aim).children('div').eq(Inx).show().siblings().hide();
		
	})
	
}