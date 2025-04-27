


$(function(){
	
	$('.nav_list').click(function(){
		
		var hvsb = $(this).find('.towlave').css('display');
		
		if (hvsb == 'block') {
			
			$(this).find('.towlave').slideUp();
			
		} else{
			
			$(this).find('.towlave').slideDown().parents('.nav_list').siblings().find('.towlave').slideUp();
			
		}
		
	})
	
	
})


