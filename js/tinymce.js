(function($) {

	tinymce.create('tinymce.plugins.wpFeatureBox', {
		/**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
		init : function(ed, url) {

			ed.addCommand('wpfeaturebox', function() {
				ed.windowManager.open({
					id : 'wp-feature-box-builder',
					title: 'WP Feature Box',
					width : 600 + parseInt(ed.getLang('example.delta_width', 0)),
					height : 500 + parseInt(ed.getLang('example.delta_height', 0)),
					wpDialog : true
				}, {
					plugin_url : url
				});
			});

			ed.addButton('wpfeaturebox', {
				title : 'WP Feature Box',
				cmd : 'wpfeaturebox',
				image : url + '/../img/icon-tinymce.png'
			});

		},

		/**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
		createControl : function(n, cm) {
			return null;
		},

		/**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
		getInfo : function() {
			return {
				longname : 'WP Feature Box',
				author : 'Miguel Peixe',
				authorurl : 'http://ecolab.oeco.org.br',
				infourl : 'http://github.com/oeco/wp-feature-box',
				version : "0.1.3"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('wpfeaturebox', tinymce.plugins.wpFeatureBox);

	function setupBox() {

		var box = $('#wp-feature-box-builder');

		box.find('.add-single').click(function() {

			var selected = $('.single-feature-box option:selected');

			if(selected.length) {
				tinymce.execCommand('mceInsertContent', 0, '[wp-feature-box id="' + selected.val() + '"]');
			}

			tinymce.activeEditor.windowManager.close();

			return false;

		});

		box.find('.include-item').click(function() {

			var selected = $('.slider-feature-box-available option:selected');

			if(selected.length) {
				selected.appendTo($('.slider-feature-box-selected'));
			}

			return false;

		});

		box.find('.exclude-item').click(function() {

			var selected = $('.slider-feature-box-selected option:selected');

			if(selected.length) {
				selected.appendTo($('.slider-feature-box-available'));
			}

			return false;

		});

		box.find('.add-slider').click(function() {

			var selected = $('.slider-feature-box-selected option');

			var vals = [];

			selected.each(function() {
				vals.push($(this).val());
			});

			if(vals.length) {
				if(vals.length === 1) {
					alert('You must select at least 2 items');
				} else {
					selected.appendTo($('.slider-feature-box-available'));
					tinymce.execCommand('mceInsertContent', 0, '[wp-feature-box ids="' + vals.join(',') + '"]');
					tinymce.activeEditor.windowManager.close();
				}
			} else {
				alert('You must select at least 2 items');
			}

			return false;

		});

	}

	jQuery(document).ready(setupBox);

})(jQuery);