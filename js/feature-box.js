(function($) {

	var featureBox = window.wpFeatureBox,
		boxes;
	
	featureBox = function() {
	
		$(document).ready(function() {
	
			boxes = $('.wp-feature-box');
			
			$(window).resize(responsiveClasses).resize();
	
		});
		
		function responsiveClasses() {
	
			boxes.each(function() {
	
				if($(this).width() <= 400) {
	
					$(this).addClass('wp-feature-box-small');
	
				} else {
	
					$(this).removeClass('wp-feature-box-small');
					
				}
	
			});
	
		}
		
	}();
	
})(jQuery);