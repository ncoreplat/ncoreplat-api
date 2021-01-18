<?php
    /*
    Plugin Name: nCorePlat API
    Description: nCore Plat API Plugin
    Version: 0.1.0
	Plugin URI: https://www.ncorehr.com/
    Author: nCore
    Author URI: https://www.ncorehr.com/
	Text Domain: ncoreplat-api
	Domain Path: /languages
	
    */

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

// the core plugin
	define( 'NCORE_PLAT_API_VERSION', '0.1.0' );
	define( 'NCORE_PLAT_API_PLUGIN_DIR', __DIR__ . '/' );
	define( 'NCORE_PLAT_API_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
	define( 'NCORE_PLAT_API_PLUGIN_FILE', __FILE__ );
	define( 'NCORE_PLAT_API_PLUGIN_BASENAME', dirname( plugin_basename( __FILE__ ) ) );

// traduzioni
add_action('plugins_loaded', 'ncoreplatApiInitTranslation');
function ncoreplatApiInitTranslation() {
	load_plugin_textdomain( 'ncoreplat-api', false, NCORE_PLAT_API_PLUGIN_BASENAME . '/languages/' );
}

// frontend
class ncoreplat_api_asset {

    public function __construct () {
		add_action( 'the_posts', array( $this, 'check_shortcode_ncoreplat_api' ), 99 );
    }

	function check_shortcode_ncoreplat_api($posts) {
		if ( empty($posts) )
			return $posts; 
		// false because we have to search through the posts first
		$found = false;
		// search through each post
		foreach ($posts as $post) {
			// check the post content for the short code
			if ( stripos($post->post_content, 'ncoreplat-api') )
				// we have found a post with the short code
				$found = true;
				// stop the search
				break;
			}
		// caricare eventuali script o css del plugin
		if ($found){
			wp_enqueue_style( 'nCorePlatApiStyle', NCORE_PLAT_API_PLUGIN_URL . "inc/css/nCorePlat-api.css", array(), NCORE_PLAT_API_VERSION);
			wp_enqueue_script( 'nCorePlatApiScript', NCORE_PLAT_API_PLUGIN_URL . "inc/js/nCorePlat-api.js", array('jquery'), NCORE_PLAT_API_VERSION);
			wp_localize_script( 'nCorePlatApiScript', 'nCorePlatApiAjax', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));
		}
		return $posts;
	}		
	
}
$ncoreplat_script = new ncoreplat_api_asset();


// api
require_once NCORE_PLAT_API_PLUGIN_DIR . 'inc/api_functions.php';

// templates
require_once NCORE_PLAT_API_PLUGIN_DIR . 'inc/templates_functions.php';

// shortcode
require_once NCORE_PLAT_API_PLUGIN_DIR . 'inc/shortcode_functions.php';

// save form
require_once NCORE_PLAT_API_PLUGIN_DIR . 'inc/save_functions.php';

// settings admin page
require_once NCORE_PLAT_API_PLUGIN_DIR . 'inc/settings_functions.php';

// shortcode
class ncoreplat_api_shortcode {
 
    public function __construct () {
		add_shortcode( 'ncoreplat-api', array( $this, 'nCorePlat_api_func' ));
    }
	
    // Render the shortcode
    // @param array $atts shortcode attributes
	
	public function nCorePlat_api_func($atts) {
		
		define( 'BASE_ENDPOINT', ncoreplat_api_plugin_setting_get_value('base_endpoint') );
		
		$username = ncoreplat_api_plugin_setting_get_value('username');
		$password = ncoreplat_api_plugin_setting_get_value('password');
		
		$ajax_content = ncoreplat_api_plugin_setting_get_value('ajax_content');
		$primary_color = ncoreplat_api_plugin_setting_get_value('primary_color');
		$main_color = ncoreplat_api_plugin_setting_get_value('main_color');
		$main_text_color = ncoreplat_api_plugin_setting_get_value('main_text_color');
		$btn_color = ncoreplat_api_plugin_setting_get_value('btn_color');
		$btn_text_color = ncoreplat_api_plugin_setting_get_value('btn_text_color');
	
		$a = shortcode_atts( array(
			'id_node'			=> '',
			'id_position'		=> '',
			'ajax_content'		=> $ajax_content,
			'primary-color'		=> $primary_color,
			// opzioni nascoste
			'first-color'		=> '',
			'second-color'		=> '',
			'form-text-color'	=> '',
			'form-field-color'	=> '',
			// custom colors
			'main-color'		=> $main_color,
			'main-text-color'	=> $main_text_color,
			'btn-color'			=> $btn_color,
			'btn-text-color'	=> $btn_text_color
		), $atts );
		// questa funzione deve fare il render sia della lista posizioni sia il dettaglio della posizione con form
		// testing => [ncoreplat-api id_node="151" id_position="737"]
		$primary_color = $a['primary-color'] ? esc_attr($a['primary-color']) : '';
		// opzioni nascoste
		$first_color = $a['first-color'] ? '--first-color:' . ncoreplat_api_color_value($a['first-color']) . ';' : '';
		$second_color = $a['second-color'] ? '--second-color:' . ncoreplat_api_color_value($a['second-color']) . ';' : '';
		$form_text_color = $a['form-text-color'] ? '--form-text-color:' . ncoreplat_api_color_value($a['form-text-color']) . ';' : '';
		$form_field_color = $a['form-field-color'] ? '--form-field-color:' . ncoreplat_api_color_value($a['form-field-color']) . ';' : '';
		// opzioni visibili
		$main_color = $a['main-color'] ? '--main-color:' . ncoreplat_api_color_value($a['main-color']) . ';' : '';
		$main_text_color = $a['main-text-color'] ? '--main-text-color:' . ncoreplat_api_color_value($a['main-text-color']) . ';' : '';
		$btn_color = $a['btn-color'] ? '--btn-color:' . ncoreplat_api_color_value($a['btn-color']) . ';' : '';
		$btn_text_color = $a['btn-text-color'] ? '--btn-text-color:' . ncoreplat_api_color_value($a['btn-text-color']) . ';' : '';
		
		$style_nCorePlat_api = $main_color . $main_text_color . $btn_color . $btn_text_color;
		if($primary_color == 'custom'){
			$style_nCorePlat_api = $first_color . $second_color . $form_text_color . $form_field_color;	
		}
		if($style_nCorePlat_api){
			$style_nCorePlat_api = ' style="' . $style_nCorePlat_api . '"';
		}
		
		$html_nCorePlat_api = '<div class="ncore-plat-api-container ncore-plat-api-color-' . $primary_color . '"' . $style_nCorePlat_api . '>';
		$token = getToken($username,$password);
		$id_node = $a['id_node'] ? esc_attr($a['id_node']) : '';
		$id_position = $a['id_position'] ? esc_attr($a['id_position']) : '';
		$ajax_content = $a['ajax_content'];
		if(!empty($id_node) && !empty($id_position)) {
			$jsonPosition = json_decode(getPositionDetail($token, $id_node, $id_position));
			$jsonTemplates = json_decode(getPositionTemplates($token, $id_node, $id_position));
			$html_nCorePlat_api .= templatePositionDetail($jsonPosition, $jsonTemplates, $a);
		} else if(!empty($id_node)) {
			$jsonNode = json_decode(getPositionList($token, $id_node));
			$positions = $jsonNode->positions;
			$html_nCorePlat_api .= templatePositionList($positions, $a);
		} else {
			$jsonAllNodes = json_decode(getNodeList($token));
		}
		$html_nCorePlat_api .= '</div>';
		return $html_nCorePlat_api;
	}
	
}
$nCorePlat_shortcode = new ncoreplat_api_shortcode();

// update
require_once NCORE_PLAT_API_PLUGIN_DIR . 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://www.ncorehr.com/download/update/ncore-plat-plugin-api.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'nCorePlat-api'
);

?>