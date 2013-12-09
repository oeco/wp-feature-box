<?php

/* 
 * WP Feature Box
 * Admin settings
 */

if(!class_exists('WP_Feature_Box_Admin')) {
	
	class WP_Feature_Box_Admin extends WP_Feature_Box {
		
		var $settings_id = 'wfb_settings';
		
		var $settings_group = 'wfb_settings_group';
		
		var $settings_page = 'wfb-settings';
		
		function __construct() {
			
			add_action('admin_menu', array($this, 'add_plugin_page'));
			add_action('admin_init', array($this, 'page_init'));
			add_filter('wp_feature_box_options', array($this, 'filter_options'));

		}
		
		function add_plugin_page() {
			add_submenu_page('edit.php?post_type=' . $this->post_type, __('Settings', 'wp-feature-box'), __('Settings', 'wp-feature-box'), 'manage_options', $this->settings_page, array($this, 'create_admin_page'));
		}
		
		function create_admin_page() {
			$this->store_data();
			?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2><?php _e('Feature box settings', 'wp-feature-box'); ?></h2>
				<form method="post">
					<?php
					// This prints out all hidden setting fields
					settings_fields($this->settings_group);
					do_settings_sections($this->settings_page);
					submit_button(); 
					?>
				</form>
				<form method="post">
					<input type="hidden" name="wfb_settings_reset" value="1" />
					<?php submit_button(__('Restore default settings', 'wp-feature-box'), 'secondary'); ?>
				</form>
			</div>
			<?php
		}
		
		function page_init() {
			
			/* 
			 * Embed
			 */
			
			add_settings_section(
				'wfb_embed',
				__('Embed options', 'wp-feature-box'),
				array($this, 'embed_info'),
				$this->settings_page
			);
			
			add_settings_field(
				'allow_embed',
				__('Allow embed', 'wp-feature-box'),
				array($this, 'embed_allow_input'),
				$this->settings_page,
				'wfb_embed'
			);
			
			add_settings_field(
				'default_embed_text',
				__('Default footer text (embed credits)', 'wp-feature-box'),
				array($this, 'embed_default_text_input'),
				$this->settings_page,
				'wfb_embed'
			);
			
			add_settings_field(
				'default_embed_link',
				__('Default footer text link', 'wp-feature-box'),
				array($this, 'embed_default_link_input'),
				$this->settings_page,
				'wfb_embed'
			);
			
		}
		
		function store_data($input = false) {
			
			if(isset($_REQUEST['wfb_settings_reset'])) {
				delete_option($this->settings_id);
				return;
			}

			$input = $input ? $input : $_POST[$this->settings_id];
			
			if(isset($input)) {
				if(get_option($this->settings_id) === false)
					add_option($this->settings_id, $input);
				else
					update_option($this->settings_id, $input);
			}

		}
		
		function filter_options($settings) {

			$options = get_option($this->settings_id);
			
			if($options)
				$settings = array_merge($settings, $options);
			
			return $settings;

		}
		
		function embed_info() {
			echo '<p>' . __('Set your embed options:', 'wp-feature-box') , '</p>';
		}
		
		function embed_allow_input() {
			$options = $this->get_options();
			?>
			<input type="radio" id="wfb_allow_embed_yes" name="<?php echo $this->settings_id; ?>[allow_embed]" value="1" <?php if($options['allow_embed']) echo 'checked'; ?> /> <label for="wfb_allow_embed_yes"><?php _e('Yes', 'wp-feature-box'); ?></label>
			<input type="radio" id="wfb_allow_embed_no" name="<?php echo $this->settings_id; ?>[allow_embed]" value="0" <?php if(!$options['allow_embed']) echo 'checked'; ?> /> <label for="wfb_allow_embed_no"><?php _e('No', 'wp-feature-box'); ?></label>
			<?php
		}
		
		function embed_default_text_input() {
			$options = $this->get_options();
			?>
			<input type="text" id="wfb_default_embed_text" size="80" name="<?php echo $this->settings_id; ?>[default_embed_text]" value="<?php echo $options['default_embed_text']; ?>" />
			<?php
		}
		
		function embed_default_link_input() {
			$options = $this->get_options();
			?>
			<input type="text" id="wfb_default_embed_link" size="80" name="<?php echo $this->settings_id; ?>[default_embed_link]" value="<?php echo $options['default_embed_link']; ?>" />
			<?php
		}
		
	}
	
}

if(class_exists('WP_Feature_Box_Admin')) {
	
	$wp_feature_box_admin = new WP_Feature_Box_Admin();
	
}