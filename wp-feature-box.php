<?php
/*
Plugin Name: WP Feature Box
Plugin URI: http://cardume.art.br/wp-feature-box
Description: A simple WordPress feature box plugin
Version: 0.1.3
Author: Miguel Peixe
Author URI: http://ecolab.oeco.org.br/
License: GPLv3
*/
/*
Copyright 2012 Miguel Peixe  (email : miguel@cardume.art.br)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!class_exists('WP_Feature_Box')) {

	class WP_Feature_Box {

		var $fields_prefix = '_wp_feature_box_';

		var $post_type = 'wp-feature-box';

		var $links_amount = 4;

		public function __construct() {

			/*
			 * Allow symbolic linked plugin
			 */
			require_once(sprintf("%s/includes/class-symbolic-press.php", dirname(__FILE__)));
			new Symbolic_Press(__FILE__);

			/*
			 * Include ACF if not already registered as plugin
			 */
			if(!class_exists('acf')) {

				/*
				 * Include Advanced Custom Fields
				 * More info: http://advancedcustomfields.com
				 */
				add_filter('acf/helpers/get_dir', array($this, 'acf_dir'));

				require_once(sprintf("%s/includes/acf/acf.php", $this->get_path()));
			}

			/*
             * Include ACF Website Field
             */
			if(!class_exists('acf_website_field_plugin')) {
				require_once(sprintf("%s/includes/acf/add-ons/acf-website-field/acf-website_field.php", $this->get_path()));
			}

			/*
			 * Register post type
			 */
			add_action('init', array($this, 'register_post_type'));

			/*
			 * Register scripts and styles
			 */
			add_action('wp_enqueue_scripts', array($this, 'scripts'), 11);

			/*
             * Register ACF field groups
             */
			add_action('init', array($this, 'register_field_group'));

			/*
			 * Register shortcode
			 */
			add_shortcode('wp-feature-box', array($this, 'shortcode'));

			/*
 			 * Admin CSS
			 */
			add_action('admin_head', array($this, 'admin_css'));

		}

		public static function activate() {

			// Do nothing

		}

		public static function deactivate() {

			// Do nothing

		}

		public function get_dir() {
			return apply_filters('wp_feature_box_dir', plugin_dir_url(__FILE__));
		}

		public function get_path() {

			return apply_filters('wp_feature_box_dir', dirname(__FILE__));

		}

		public function get_options() {

			$settings = array();
			return apply_filters('wp_feature_box_options', $settings);

		}

		public function acf_dir() {
			return $this->get_dir() . 'includes/acf/';
		}

		public function register_post_type() {

			$labels = array(
				'name' => __('Feature box', 'wp-feature-box'),
				'singular_name' => __('Feature item', 'wp-feature-box'),
				'add_new' => __('Add feature item', 'wp-feature-box'),
				'add_new_item' => __('Add new feature item', 'wp-feature-box'),
				'edit_item' => __('Edit feature item', 'wp-feature-box'),
				'new_item' => __('New feature item', 'wp-feature-box'),
				'view_item' => __('View feature item', 'wp-feature-box'),
				'search_items' => __('Search feature item', 'wp-feature-box'),
				'not_found' => __('No feature item found', 'wp-feature-box'),
				'not_found_in_trash' => __('No feature item found in the trash', 'wp-feature-box'),
				'menu_name' => __('Feature box', 'wp-feature-box')
			);

			$args = array(
				'labels' => $labels,
				'hierarchical' => false,
				'description' => __('Feature Box Items', 'wp-feature-box'),
				'supports' => array('title'),
				'public' => false,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_menu' => true,
				'has_archive' => false,
				'rewrite' => false
			);

			register_post_type($this->post_type, $args);

		}

		public function admin_css() {
			?>
			<style type="text/css" media="screen">
				#menu-posts-wp-feature-box .wp-menu-image:before {
					content: '' !important;
				}
				#menu-posts-wp-feature-box .wp-menu-image {
					background: url(<?php echo $this->get_dir(); ?>/img/icon-small.png) center no-repeat !important;
				}
				#icon-edit.icon32-posts-wp-feature-box {background: url(<?php echo $this->get_dir(); ?>/img/icon.png) no-repeat;}
			</style>
			<?php
		}

		public function scripts() {

			wp_register_script('sly', $this->get_dir() . 'js/sly.min.js', array('jquery'), '1.2.2');

			wp_enqueue_style('wp-feature-box', $this->get_dir() . 'css/feature-box.css', array(), '0.1.0');
			wp_enqueue_script('wp-feature-box', $this->get_dir() . 'js/feature-box.js', array('jquery'), '0.1.0');
			wp_enqueue_script('wp-feature-box-slider', $this->get_dir() . 'js/slider.js', array('jquery', 'sly', 'wp-feature-box'), '0.1.0');

			wp_localize_script('wp-feature-box', 'wpFeatureBoxSettings', $this->get_feature_box_js_settings());

		}

		public function register_field_group() {

			$field_group = array(
				'id' => 'acf_wp-feature-box-settings',
				'title' => __('Feature Box Settings', 'wp-feature-box'),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => $this->post_type,
							'order_no' => 0,
							'group_no' => 0
						)
					)
				),
				'options' => array(
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array(),
				),
				'menu_order' => 0
			);

			/*
             * Basic fields
             */
			$fields = array(
				array (
					'key' => 'field_wp_feature_box_description',
					'label' => __('Description', 'wp-feature-box'),
					'name' => $this->fields_prefix . 'description',
					'type' => 'textarea',
					'instructions' => __('In few words, describe the content inside this feature box', 'wp-feature-box'),
					'default_value' => '',
					'placeholder' => '',
					'maxlength' => '',
					'formatting' => 'br',
				),
				array (
					'key' => 'field_wp_feature_box_image',
					'label' => __('Background image', 'wp-feature-box'),
					'name' => $this->fields_prefix . 'background_image',
					'type' => 'image',
					'instructions' => __('Upload an image to be used as the feature box background', 'wp-feature-box'),
					'save_format' => 'object',
					'preview_size' => 'thumbnail',
					'library' => 'all',
				),
				array(
					'key' => 'field_wp_feature_box_link',
					'label' => __('Main link', 'wp-feature-box'),
					'name' => $this->fields_prefix . 'link',
					'type' => 'website',
					'website_title' => 0,
					'internal_link' => 1,
					'output_format' => 1,
					'default_value' => '',
				)
			);

			/*
             * Merge link group fields
             */
			foreach($this->get_link_groups() as $key => $title) {
				$fields = array_merge($fields, $this->link_group_fields($key, $title));
			}

			$field_group['fields'] = $fields;

			// register on acf
			register_field_group($field_group);

		}

		public function get_link_groups() {

			$link_groups = array(
				'first_link_group' => __('First link group', 'wp-feature-box'),
				'second_link_group' => __('Second link group', 'wp-feature-box'),
				'third_link_group' => __('Third link group', 'wp-feature-box')
			);

			return apply_filters('wp_feature_box_link_groups', $link_groups);

		}

		protected function link_group_fields($key, $title) {

			$field = array(
				array(
					'key' => 'field_wp_feature_box_link_group_' . $key,
					'label' => $title,
					'name' => '',
					'type' => 'tab',
				),
				array(
					'key' => 'field_wp_feature_box_link_group_' . $key . '_title',
					'label' => __('Link group title', 'wp-feature-box'),
					'name' => $this->fields_prefix . $key . '_title',
					'type' => 'text',
					'instructions' => __('Enter a title to appear on top of the links', 'wp-feature-box')
				)
			);

			for($i = 1; $i <= $this->links_amount; $i++) {

				$field[] = array(
					'key' => 'field_wp_feature_box_link_group_' . $key . '_link_' . $i,
					'label' => __('Link', 'wp-feature-box'),
					'name' => $this->fields_prefix . $key . '_link_' . $i,
					'type' => 'website',
					'website_title' => 1,
					'internal_link' => 1,
					'output_format' => 1,
					'default_value' => '',
				);

			}

			return $field;
		}

		public function get_feature_box_field($id, $field) {
			return get_field($this->fields_prefix . $field, $id);
		}

		/*
		 * Get feature box description
		 */
		public function get_feature_box_description($id) {
			global $post;
			$id = $id ? $id: $post->ID;

			return $this->get_feature_box_field($id, 'description');
		}

		/*
		 * Get feature box background image
		 */
		public function get_feature_box_image($id) {
			global $post;
			$id = $id ? $id: $post->ID;

			return $this->get_feature_box_field($id, 'background_image');
		}

		/*
		 * Get feature box main link
		 */
		public function get_feature_box_main_link($id) {
			global $post;
			$id = $id ? $id: $post->ID;

			return $this->get_feature_box_field($id, 'link');
		}

		/*
		 * Get feature box links
		 */
		public function get_feature_box_links($id) {
			global $post;
			$id = $id ? $id : $post->ID;

			$link_groups = $this->get_link_groups();
			$links_amount = $this->links_amount;

			$links = array();

			foreach($link_groups as $key => $title) {

				$link_group = array();

				$link_group['title'] = get_field($this->fields_prefix . $key . '_title', $id);
				$link_group['links'] = array();

				for($i = 1; $i <= $links_amount; $i++) {

					$link = get_field($this->fields_prefix . $key . '_link_' . $i, $id);
					if($link)
						$link_group['links'][] = $link;

				}

				if(!empty($link_group['links']))
					$links[] = $link_group;

			}

			$links = apply_filters('wp_feature_box_links', (!empty($links) ? $links : false), $id);

			return $links;

		}

		public function get_feature_box_js_settings() {

			$settings = array(
				'baseurl' => apply_filters('wp_feature_box_base_url', home_url('/'))
			);

			return apply_filters('wp_feature_box_js_settings', $settings);

		}

		/*
         * Get feature box item
         */
		public function get_feature_box($id) {

			$feature_box = false;

			if($id && get_post_type($id) == $this->post_type) {

				global $post;
				$post = get_post($id);
				setup_postdata($post);
				ob_start();
				include($this->get_path() . '/templates/feature-box.php');
				$feature_box = ob_get_clean();
				wp_reset_postdata();

			}

			return $feature_box;

		}


		/*
         * Get feature box slider
         */
		public function get_feature_box_slider($ids = array(), $options = array()) {

			if(empty($ids) || !is_array($ids)) {
				$items = get_posts(array('post_type' => $this->post_type, 'posts_per_page' => 6));
				if($items) {
					$ids = array();
					foreach($items as $item) {
						$ids[] = $item->ID;
					}
				} else {
					return false;
				}
			}

			$settings = array(
				'autorotate' => 0
			);

			$settings = array_merge($settings, $options);

			$attrs = '';

			if($settings['autorotate'])
				$attrs .= ' data-autorotate="true" ';

			$slider = '';
			$slider .= '<div class="wp-feature-box-slider" ' . $attrs . '>';
			$slider .= '<div class="wp-feature-box-slider-content">';
			$slider .= '<ul class="wp-feature-box-items">';

			$selector = '';
			$selector .= '<ul class="wp-feature-box-selectors">';

			foreach($ids as $id) {
				$slider .= '<li data-sliderid="' . $id . '">' . $this->get_feature_box($id) . '</li>';
				$selector .= '<li data-sliderid="' . $id . '" title="' . get_the_title($id). '">' . get_the_title($id) . '</li>';
			}

			$selector .= '</ul>';

			$slider .= '</ul>';

			$slider .= $selector;

			$slider .= '</div>';

			$slider .= '</div>';

			return $slider;

		}

		/*
		 * Shortcodes
		 */
		public function shortcode($atts) {

			extract(shortcode_atts(array(
				'id' => 0,
				'ids' => '',
				'autorotate' => 0
			), $atts));

			$ids = explode(',', $ids);

			if($id) {
				return $this->get_feature_box($id);
			}

			if(!empty($ids)) {

				if(count($ids) == 1) {
					return $this->get_feature_box(array_shift($ids));
				} else {
					$options = array(
						'autorotate' => $autorotate
					);
					return $this->get_feature_box_slider($ids, $options);
				}

			}
		}

	}

}

if(class_exists('WP_Feature_Box')) {

	register_activation_hook(__FILE__, array('WP_Feature_Box', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Feature_Box', 'deactivate'));

	$wp_feature_box = new WP_Feature_Box();

}

include_once($wp_feature_box->get_path() . '/admin.php');
include_once($wp_feature_box->get_path() . '/embed.php');
include_once($wp_feature_box->get_path() . '/tinymce.php');

function get_feature_box($id) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box($id);
}

function get_feature_box_slider($ids = array(), $options = array()) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box_slider($ids, $options);
}

function get_feature_box_description($id = false) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box_description($id);
}

function get_feature_box_image($id = false) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box_image($id);
}

function get_feature_box_main_link($id = false) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box_main_link($id);
}

function get_feature_box_links($id = false) {
	global $wp_feature_box;
	return $wp_feature_box->get_feature_box_links($id);
}