<?php

	// Prevent direct file access
	defined( 'ABSPATH' ) or exit;
	
	add_action( 'admin_post_nopriv_ncore_plat_api_send_data', 'ncore_plat_api_save_data' );
	add_action( 'admin_post_ncore_plat_api_send_data', 'ncore_plat_api_save_data' );
 
	function ncore_plat_api_save_data() {
		define( 'BASE_ENDPOINT', ncoreplat_api_plugin_setting_get_value('base_endpoint') );
			
		$username = ncoreplat_api_plugin_setting_get_value('username');
		$password = ncoreplat_api_plugin_setting_get_value('password');
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['form']) && wp_verify_nonce($_REQUEST['ncore_plat_api__token'], 'application_submition')){
			unset($_POST['ncore_plat_api__token']);
			if(array_key_exists('ncore_plat_api_terms_and_conditions', $_POST)) {
				unset($_POST['ncore_plat_api_terms_and_conditions']);
			}
			if(array_key_exists('ncore_plat_api_privacy_policy', $_POST)) {
				unset($_POST['ncore_plat_api_privacy_policy']);
			}
			
			$form_data = $_POST['form'];
			$form_data['mail_confirm'] = true;
			$token = getToken($username,$password);
			if($token){
				$nodeId = $_GET['ncore-node'];
				$positionId = $_GET['ncore-position'];
				$result = createApplication($token, $nodeId, $positionId, $form_data);
				if ($result) {
					header("Location:" . $result);
					die();
				} else {
					header("Location:" . $_SERVER['HTTP_REFERER']);
					die();
				}
			}
		}
		
		header("Location:" . $_SERVER['HTTP_REFERER']);
		die();
	}
	