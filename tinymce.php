<?php

/*
 * WP Feature Box
 * TinyMCE input
 */

if(!class_exists('WP_Feature_Box_TinyMCE')) {

	class WP_Feature_Box_TinyMCE extends WP_Feature_Box {

		function __construct() {
			add_action('init', array($this, 'init'));
		}

		function init() {
			add_filter('mce_external_plugins', array($this, 'add_buttons'));
			add_filter('mce_buttons', array($this, 'register_buttons'));
			if((current_user_can('edit_posts') || current_user_can('edit_pages')) && 'true' == get_user_option('rich_editing')) {
				wp_enqueue_script( array ( 'wpdialogs' ) );
				wp_enqueue_style('wp-jquery-ui-dialog');
				add_action('admin_footer', array($this, 'builder'));
			}
		}

		function add_buttons($plugins) {

			$plugins['wpfeaturebox'] = $this->get_dir() . 'js/tinymce.js';
			return $plugins;

		}

		function register_buttons($buttons) {

			$buttons[] = 'wpfeaturebox';
			return $buttons;

		}

		function builder() {
			$feature_boxes = get_posts(array('post_type' => $this->post_type, 'posts_per_page' => -1));
			?>
			<div style="display: none;">
				<div id="wp-feature-box-builder">
					<h3><?php _e('Add feature boxes to your post', 'wp-feature-box'); ?></h3>
					<?php if($feature_boxes) : ?>
						<p><?php _e('Add existing feature boxes to your post by selecting the options below. You can add a single feature box or create a slider with multiple boxes.', 'wp-feature-box'); ?></p>
						<p><a href="<?php echo admin_url('post-new.php?post_type=' . $this->post_type); ?>" target="_blank"><?php _e('Clique here to create a new feature box.', 'wp-feature-box'); ?></a></p>
						<h4><?php _e('Single feature box', 'wp-feature-box'); ?></h4>
						<div>
							<select class="single-feature-box">
								<?php foreach($feature_boxes as $box) : ?>
									<option value="<?php echo $box->ID; ?>"><?php echo get_the_title($box->ID); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<p><button class="add-single button-primary"><?php _e('Add feature box', 'wp-feature-box'); ?></button></p>
						<h4><?php _e('Create feature box slider', 'wp-feature-box'); ?></h4>
						<div class="slider-feature-box-select">
							<div class="slider-select-item">
								<small><?php _e('Available', 'wp-feature-box'); ?></small>
								<select class="slider-feature-box-available" multiple>
									<?php foreach($feature_boxes as $box) : ?>
										<option value="<?php echo $box->ID; ?>"><?php echo get_the_title($box->ID); ?></option>
									<?php endforeach; ?>
								</select>
								<p><a class="button include-item"><?php _e('Add selected', 'landquest'); ?></a></p>
							</div>
							<div class="slider-select-item">
								<small><?php _e('Selected', 'wp-feature-box'); ?></small>
								<select class="slider-feature-box-selected" multiple>
								</select>
								<p><a class="button exclude-item"><?php _e('Remove selected', 'landquest'); ?></a></p>
							</div>
						</div>
						<p><button class="add-slider button-primary"><?php _e('Create slider', 'wp-feature-box'); ?></button></p>
					<?php else : ?>
						<p><?php _e('You haven\'t created any feature box yet.', 'wp-feature-box'); ?></p>
						<p><a href="<?php echo admin_url('post-new.php?post_type=' . $this->post_type); ?>" target="_blank"><?php _e('Clique here to create your first!', 'wp-feature-box'); ?></a></p>
					<?php endif; ?>
				</div>
			</div>
			<style>
				#wp-feature-box-builder {
					padding: 10px 20px;
				}
				#wp-feature-box-builder select {
					width: 100%;
					display: block;
				}

				#wp-feature-box-builder .slider-feature-box-select {
					width: 100%;
				}

				#wp-feature-box-builder .slider-select-item {
					display: table-cell;
					width: 1%;
					padding: 0 10px 0 0;
				}
			</style>
			<?php
		}


	}

}

if(class_exists('WP_Feature_Box_TinyMCE')) {

	$wp_feature_box_tinymce = new WP_Feature_Box_TinyMCE();

}