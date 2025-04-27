$(function(){
	
	$('.nav_title').click(function(){
		
		var zt = $(this).siblings('.lave_gruop').css('display');
		
		if(zt == 'block'){
			
			$(this).siblings('.lave_gruop').slideUp();
			
		}else{
			
			$(this).siblings('.lave_gruop').slideDown().parents('.nav_list').siblings().find('.lave_gruop').slideUp();
			
		}
		
	})
	
	
	
	$('.nav_btn').click(function(event){
		
		event.stopPropagation();
		
		var zt = $("#nav").attr('zt');
		var navW = $("#nav").width();
		
		if (zt == 0) {
			
			$('#nav').attr('zt','1');
			
			$('#nav,.mobile_contai').animate(
				
				{
					marginLeft:navW
					
				},300,function(){
						
						$('body').addClass('overhide').find('.zgb').show();
						
						$('body').click(function(){
							
							navHide()
							
						})
				
					});
			
		} else{
			
			navHide()
			
		}
		
		
	})
	
	
	$('#nav').click(function(event){
		
		event.stopPropagation();
		
	})
	
	
	
	
	$('.tree_list').click(function(){
		
		var zt = $(this).siblings('.next_chider').css('display');
		
		if (zt == 'none') {
			
			$(this).siblings('.next_chider').slideDown();	
			
			
		} else{
			
			$(this).siblings('.next_chider').slideUp().find('.next_chider').hide();	
			
			
		}
		
	})
	
	
	
	$('.tab_title li').click(function(){
		
		var inx = $(this).index();
		
		$(this).addClass('active').siblings().removeClass('active');
		
		$('.tab_contai > div').eq(inx).show().siblings().hide();
		
	})
	
	
//	$('.wtjg').scrollLeft(240)
	scrollAuto()
	function scrollAuto(){
		var bWidth = $('.wtjg_big_box').width();
		var sWidth = $('.wtjg').width();
		$('.wtjg').scrollLeft((bWidth-sWidth)/2);
	}

	
})


function navHide(){
	
	$('#nav').attr('zt','0');
			
	$('#nav,.mobile_contai').animate(
		
		{
			marginLeft:0
			
		},300,function(){
			
			$('body').removeClass('overhide').find('.zgb').hide();
			
		}
	)
	
}

