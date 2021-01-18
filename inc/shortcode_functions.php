<?php

	// Prevent direct file access
	defined( 'ABSPATH' ) or exit;

	add_action( 'wp_ajax_get_shortcode_ncore_plat_api_content', 'get_shortcode_ncore_plat_api_position' );
	add_action( 'wp_ajax_nopriv_get_shortcode_ncore_plat_api_content', 'get_shortcode_ncore_plat_api_position' );

	function get_shortcode_ncore_plat_api_position() {
		$id_node = $_POST["node"];
		$id_position = $_POST["position"];
		$shortcode = '[ncoreplat-api ';
		foreach($_POST as $key => $config) {
			if($key == 'id_position') {
				$config = $id_position;
			}
			if($key == 'id_node') {
				$config = $id_node;
			}
			$shortcode .= $key.'="'.$config.'" ';
		}
		$shortcode .= ']';
		echo do_shortcode($shortcode);
		wp_die();
	}
	
	
	add_action('wp_footer', 'ncore_plat_api_modal');
	function ncore_plat_api_modal(){
		global $post;
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ncoreplat-api') ) {
			$html_nCorePlat_modal = '<div class="ncore-plat-api-modal">
				<div class="ncore-plat-api-modal-content">
					<div class="ncore-plat-api-modal-header">
						<span class="ncore-plat-api-close-modal ncore-plat-api-close-modal-times">&times;</span>
						<div class="ncore-plat-api-modal-title"></div>
					</div>
					<div class="ncore-plat-api-modal-body">
						<div class="ncore-plat-api-modal-text"></div>
					</div>
					<div class="ncore-plat-api-modal-footer">
						<button type="button" class="ncore-plat-api-close-modal  ncore-plat-api-close-modal-btn">
							' . _x( "Close" , "ncoreplat-api-position-modal" , "ncoreplat-api" ) . '
						</button>
					</div>
				</div>
			</div>';
			echo $html_nCorePlat_modal;
		}
	};