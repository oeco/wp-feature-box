(function($) {

	var sliders;
	var boxes;
	
	var featureSlider = window.wpFeatureBoxSlider = function() {

		sliders = $('.wp-feature-box-slider');
		boxes = $('.wp-feature-box').filter(function() {
			return !$(this).parents('.wp-feature-box-slider').length;
		});
		
		sliders.each(function() {
			setupSlider($(this));
		});

	};

	if($.isReady)
		featureSlider();
	else
		$(document).ready(featureSlider);
	
	function setupSlider(slider) {

		var boxes = slider.find('.wp-feature-box-items > li');
		
		var options = {
				horizontal: 1,
				itemNav: 'basic',
				itemSelector: boxes,
				smart: 1,
				activateOn: 'click',
				mouseDragging: 1,
				touchDragging: 1,
				pagesBar: slider.find('.wp-feature-box-selectors'),
				activatePageOn: 'click',
				releaseSwing: 1,
				startAt: 0,
				scrollBy: 0,
				speed: 300,
				keyboardNavBy: null
		};
		
		var sly = new Sly(slider.find('.wp-feature-box-slider-content'), options);
		
		var fixHeight = function() {
			
			boxes.width(slider.width());
			
			var height = 0;
			
			boxes.each(function() {
				if($(this).find('.wp-feature-box-container').innerHeight() > height)
					height = $(this).find('.wp-feature-box-container').innerHeight();
			});
			
			slider.find('.wp-feature-box-selectors').css({
				'margin-left': -slider.find('.wp-feature-box-selectors').width()/2
			});
		
			// increase height for selectors
			height += 60;
			
			boxes.height(height);

			if(sly && sly.initialized)
				sly.reload();

		};
		
		$(window).resize(fixHeight).resize();
		
		sly.init();
		
	}
	
})(jQuery);