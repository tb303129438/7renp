



$(function(){
	
	$('.nav_list').click(function(){
		
		var hvsb = $(this).find('.towlave').css('display');
		
		if (hvsb == 'block') {
			
			$(this).find('.towlave').slideUp();
			
		} else{
			
			$(this).find('.towlave').slideDown().parents('.nav_list').siblings().find('.towlave').slideUp();
			
		}
		
	})
	
	
	
	$('.tab_title li').click(function(){
		var iNx = $(this).index();
		$(this).addClass('active').siblings().removeClass('active');
		
		$('.tab_contai .tab_contai_1').eq(iNx).addClass('active').siblings().removeClass('active');
		
		
	})
	
	
	
	$('.nav_btn').click(function(){
		$('.zhegai').show();
		$('body').addClass('over_hide');
		
		$('.right_box').animate({marginLeft:'150px'},50);
		$('.left_box').animate({left:'0'},50,function(){
			
			$('.zhegai').click(function(){
				
				$('.left_box').animate({left:'-180px'},50);
				$('.right_box').animate({marginLeft:0},50);
				$(this).hide();
				$('body').removeClass('over_hide');
				
			})
			
		})
		
	})
	
	
	
})


function scrollAuto(){
	
	var sWidth = $('.wtjg_big_box').width();
	var bWidth = $('.wtjg_box').innerWidth();
	
	$('.wtjg_big_box').scrollLeft((bWidth-sWidth)/2);
	
}
