<?php

/* 
 * WP Feature Box
 * Admin settings
 */

if(!class_exists('WP_Feature_Box_Admin')) {
	
	class WP_Feature_Box_Admin extends WP_Feature_Box {
		
		var $settings_id = 'wfb_settings';
		
		var $settings_group = 'wfb_settings_group';
		
		function __construct() {
			
			add_action('admin_menu', array($this, 'add_plugin_page'));
			add_action('admin_init', array($this, 'page_init'));
			
		}
		
		function add_plugin_page() {
			add_submenu_page('edit.php?post_type=' . $this->post_type, __('Settings', 'wp-feature-box'), __('Settings', 'wp-feature-box'), 'manage_options', $this->settings_id, array($this, 'create_admin_page'));
		}
		
		function create_admin_page() {
			?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2><?php _e('Feature Box Settings', 'wp-feature-box'); ?></h2>
				<form method="post">
					<?php
					// This prints out all hidden setting fields
					settings_fields($this->settings_group);
					do_settings_sections($this->settings_id);
					?>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}
		
		function page_init() {
			
			register_setting($this->settings_group, $this->settings_id, array($this, 'check_data'));
			
			/* 
			 * Embed
			 */
			
			add_settings_section(
				'wfb_embed',
				__('Embed options', 'wp-feature-box'),
				array($this, 'embed_info'),
				$this->settings_id
			);
			
			add_settings_field(
				'allow_embed',
				__('Allow embed', 'wp-feature-box'),
				array($this, 'embed_allow_input'),
				$this->settings_id,
				'wfb_embed'
			);
			
		}
		
		function check_data($input){
			error_log('checking');
			if(get_option('wfb_settings') === false)
				add_option('wfb_settings', $input);
			else
				update_option('wfb_settings', $input);
			
		}
		
		function embed_info() {
			echo '<p>' . __('Set your embed options:', 'wp-feature-box') , '</p>';
		}
		
		function embed_allow_input() {
			$options = get_option('wfb_settings');
			?><input type="checkbox" id="wfb_allow_embed" name="<?php echo $this->settings_id; ?>[allow_embed]" value="1" <?php if($option['allow_embed']) echo 'checked'; ?> /><?php
		}
		
	}
	
}

if(class_exists('WP_Feature_Box_Admin')) {
	
	$wp_feature_box_admin = new WP_Feature_Box_Admin();
	
}