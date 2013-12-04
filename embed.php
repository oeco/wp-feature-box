<?php

/*
 * WP Feature Box
 * Embed feature
 */

if(!class_exists('WP_Feature_Embed')) {
	
	class WP_Feature_Embed extends WP_Feature_Box {
		
		var $ajax_action = 'wp_feature_box_embed';
		
		public function __construct() {
			
			add_action('init', array($this, 'print_embed_script'));
			
			add_action('wp_ajax_' . $this->ajax_action, array($this, 'get_embed'));
			add_action('wp_ajax_no_priv_' . $this->ajax_action, array($this, 'get_embed'));
			
		}
		
		public function get_embed() {
			
			if(!isset($_REQUEST['ids']) || !is_array($_REQUEST['ids']) || empty($_REQUEST['ids']))
				$this->ajax_response(array('error' => __('Missing feature box IDs', 'wp-feature-box')));
			
			$ids = $_REQUEST['ids'];
			$embed = array();
			
			if(count($ids) > 1) {

				$embed['html'] = $this->get_feature_box_slider($ids);

			} else {
				
				$embed['html'] = $this->get_feature_box(array_shift($ids));
				
			}
			
			$this->ajax_response($embed);
			
		}
		
		public function ajax_response($response) {
			
			header('Content-type: application/javascript');
			header('Access-Control-Allow-Origin: *');
			echo $_REQUEST['callback']. '(' . json_encode($response) . ')';
			exit;
			
		}
		
		public function get_embed_settings() {
			
			global $wp_feature_box;
			
			$settings = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'action' => $this->ajax_action,
				'css' => $this->get_dir() . 'css/feature-box.css',
				'scripts' => array(
					array(
						'srcUrl' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
						'varName' => 'jQuery'
					),
					array(
						'srcUrl' => $this->get_dir() . 'js/sly.min.js',
						'varName' => 'Sly'
					),
					array(
						'srcUrl' => $this->get_dir() . 'js/slider.js',
						'varName' => 'wpFeatureBoxSlider'
					)
				)
			);
			
			return $settings;
			
		}
		
		public function print_embed_script() {
			
			global $wp_feature_box;
			
			if(isset($_REQUEST['wp_feature_embed'])) {
				
				echo 'var wpFeatureEmbedSettings = ' . json_encode($this->get_embed_settings()) . ';';
				
				echo file_get_contents($this->get_path() . '/js/embed.min.js');

				header('Content-type: application/javascript');
				exit;

			}
			
		}
		
	}
	
}

if(class_exists('WP_Feature_Embed')) {
	
	$wp_feature_embed = new WP_Feature_Embed();
	
}