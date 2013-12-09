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
			add_action('init', array($this, 'get_embed'));
			add_action('wp_ajax_' . $this->ajax_action, array($this, 'get_embed'));
			add_action('wp_ajax_no_priv_' . $this->ajax_action, array($this, 'get_embed'));
			add_filter('wp_feature_box_options', array($this, 'embed_options'), 1, 1);
			add_filter('wp_feature_box_js_settings', array($this, 'get_embed_settings'));

		}
		
		public function get_embed() {
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == $this->ajax_action) {
			
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
			
		}
		
		public function embed_options($settings) {
			$options = array(
				'allow_embed' => 1,
				'default_embed_text' => __('View more at', 'wp-feature-box') . ' ' . get_bloginfo('name'),
				'default_embed_link' => home_url('/')
			);

			return array_merge($settings, $options);
		}
		
		public function ajax_response($response) {
			
			header('Content-type: application/javascript');
			echo $_REQUEST['callback'] . '(' . str_replace(array('\n', '\t', '\r'), '', json_encode($response)) . ');';
			exit;

		}
		
		public function get_footer_text() {
			
			$options = $this->get_options();
			
			return '<footer class="wp-feature-box-footer"><p><a href="' . $options['default_embed_link'] . '" target="_blank" rel="external">' . $options['default_embed_text'] . '</a></p></footer>'; 
			
		}
		
		public function get_embed_tool() {

			ob_start();
			?>
			<div class="wp-feature-box-embed-action">
				<a class="embed-icon" href="#" title="<?php _e('Share', 'wp-feature-box'); ?>"><?php _e('Embed', 'wp-feature-box'); ?></a>
				<div class="embed-box">
					<div class="embed-box-content">
						<p><?php _e('Copy and paste the code below to embed this content on your page', 'wp-feature-box'); ?></p>
						<textarea></textarea>
						<a class="close-embed-tool" href="#"><?php _e('Close', 'wp-feature-box'); ?></a>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();

		}
		
		public function get_embed_settings($settings) {
			
			$options = $this->get_options();
			
			$embed_settings = array(
				'allowEmbed' => intval($options['allow_embed']),
				'embedTool' => $this->get_embed_tool(),
				'action' => $this->ajax_action,
				'css' => $this->get_dir() . 'css/feature-box.css',
				'scripts' => array(
					array(
						'srcUrl' => $this->get_dir() . 'js/sly.min.js',
						'varName' => 'Sly'
					),
					array(
						'srcUrl' => $this->get_dir() . 'js/feature-box.min.js',
						'varName' => 'wpFeatureBox'
					)
				),
				'footer' => $this->get_footer_text()
			);
			
			return array_merge($settings, $embed_settings);

		}
		
		public function print_embed_script() {
	
            header('Content-type: application/javascript');
				
			if(isset($_REQUEST['wp_feature_embed'])) {
			
				$options = $this->get_options();
				
				if($options['allow_embed']) {
					
					echo 'var wpFeatureBoxSettings = ' . str_replace(array('\r', '\n', '\t'), '', json_encode($this->get_feature_box_js_settings())) . ';';
					
					echo file_get_contents($this->get_path() . '/js/embed.min.js');
					exit;
	
				} else {

					echo "console.log('" . __('Embed is disabled on', 'wp-feature-box') . " " . get_bloginfo('name') . "');";
					exit;

				}
				
			}
			
		}
		
	}
	
}

if(class_exists('WP_Feature_Embed')) {
	
	$wp_feature_embed = new WP_Feature_Embed();
	
}