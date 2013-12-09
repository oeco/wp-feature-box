(function($, settings) {

	var featureBox = window.wpFeatureBox,
		allBoxes,
		sliders,
		boxes;
	
	featureBox = function() {
	
		$(document).ready(function() {
	
			allBoxes = $('.wp-feature-box');
			sliders = $('.wp-feature-box-slider');
			boxes = allBoxes.filter(function() {
				return !$(this).parents('.wp-feature-box-slider').length;
			});
			
			sliders.data('boxType', 'slider');
			boxes.data('boxType', 'single');
			
			$(window).resize(dimensions).resize();
			
			if(parseInt(settings.allowEmbed)) {
				setupEmbedTool();
			}
	
		});
		
		function dimensions() {
			
			allBoxes.each(function() {

				var container = $(this).find('.wp-feature-box-container');
				
				/*
				 * Responsive
				 */
				
				var responsiveClasses = {
					'small': 'wp-feature-box-small'
				}
	
				if($(this).width() <= 400) {
	
					$(this).addClass(responsiveClasses.small);
	
				} else {
	
					$(this).removeClass(responsiveClasses.small);
					
				}
			
				/*
				 * Vertically centering
				 */
				
				container.css({
					'margin-top': ($(this).innerHeight()/2) - (container.innerHeight()/2)
				});
	
			});
			
		}
		
		function setupEmbedTool() {

			if(settings.embedTool) {
				
				var tool = $(settings.embedTool);
			
				var setup = function(box) {
	
					var boxTool = tool.clone();
					var id;
					
					if(box.data('boxType') == 'slider') {
						
						id = [];
						
						box.find('.wp-feature-box').each(function() {
							id.push($(this).attr('data-itemid'));	
						});
						
						id = id.join(',');
						
					} else if(box.data('boxType') == 'single') {

						id = box.attr('data-itemid');
						
					}
					
					var code = '<div class="wp-feature-box-embed" data-ids="' + id + '"></div><script type="text/javascript" src="' + settings.baseurl + '?wp_feature_embed"></script>';
					
					boxTool.find('textarea').text(code);
					
					boxTool.find('textarea').click(function() {
						$(this).select();
						return false;
					});
					
					boxTool.find('.embed-box').hide();
					
					boxTool.find('.embed-icon').click(function() {
						boxTool.find('.embed-box').show();
						return false;
					});
					
					boxTool.find('.close-embed-tool').click(function() {
						boxTool.find('.embed-box').hide();
						return false;
					});
					
					box.append(boxTool);
	
				}

				sliders.each(function() {
					setup($(this));
				});
				boxes.each(function() {
					setup($(this));
				});
				
			}

		}
		
	}();
	
})(jQuery, wpFeatureBoxSettings);