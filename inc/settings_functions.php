<?php
	// Prevent direct file access
	defined( 'ABSPATH' ) or exit;

	function ncoreplat_api_settings_page() {
		add_options_page(
			esc_attr_x('nCore Plat API Settings', 'ncoreplat-api-settings', 'ncoreplat-api'),
			esc_attr_x('nCore Plat API', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'manage_options',
			'ncore-plat-api-plugin-settings',
			'ncoreplat_api_render_plugin_settings_page' );
	}
	add_action( 'admin_menu', 'ncoreplat_api_settings_page' );

	function ncoreplat_api_render_plugin_settings_page() {
		?>
		<h1>nCore Plat API Settings</h1>
		<form action="options.php" method="post">
			<?php 
				settings_fields( 'ncoreplat_api_plugin_settings_options' );
				do_settings_sections( 'ncore_plat_api_plugin_settings_login' );
				do_settings_sections( 'ncore_plat_api_plugin_advanced_settings' );
				do_settings_sections( 'ncore_plat_api_plugin_settings_colors' );
				do_settings_sections( 'ncore_plat_api_plugin_settings_shortcode' );
			?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
	}

	function ncoreplat_api_register_settings() {
		register_setting(
			'ncoreplat_api_plugin_settings_options',
			'ncoreplat_api_plugin_settings_options',
			'ncoreplat_api_plugin_options_validate'
		);
		
		add_settings_section(
			'ncoreplat_api_settings_login',
			esc_attr_x('Login settings', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_section_login_text',
			'ncore_plat_api_plugin_settings_login'
		);

		add_settings_field(
			'ncoreplat_api_plugin_setting_base_endpoint',
			esc_attr_x('Base Endpoint', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_base_endpoint',
			'ncore_plat_api_plugin_settings_login',
			'ncoreplat_api_settings_login',
			array(
				'field_class' => 'regular-text',
				'type'	=> 'url',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'base_endpoint',
				'label_for' => 'ncoreplat_api_plugin_setting_base_endpoint',
				'default_value' => ''
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_username',
			esc_attr_x('Username', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_username',
			'ncore_plat_api_plugin_settings_login',
			'ncoreplat_api_settings_login',
			array(
				'field_class' => 'regular-text',
				'type'	=> 'text',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'username',
				'label_for' => 'ncoreplat_api_plugin_setting_username',
				'default_value' => ''
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_password',
			esc_attr_x('Password', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_password',
			'ncore_plat_api_plugin_settings_login',
			'ncoreplat_api_settings_login',
			array(
				'field_class' => 'regular-text',
				'type'	=> 'password',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'password',
				'label_for' => 'ncoreplat_api_plugin_setting_password',
				'default_value' => ''
			)
		);
		
		add_settings_section(
			'ncoreplat_api_advanced_settings',
			esc_attr_x('Advanced Setting', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_advanced_settings_text',
			'ncore_plat_api_plugin_advanced_settings'
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_ajax_content',
			esc_attr_x('Ajax Content', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_ajax_content',
			'ncore_plat_api_plugin_advanced_settings',
			'ncoreplat_api_advanced_settings',
			array(
				'field_class' => '',
				'type'	=> 'checkbox',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'ajax_content',
				'label_for' => 'ncoreplat_api_plugin_setting_ajax_content',
				'default_value' => false
			)
		);
		
		add_settings_section(
			'ncoreplat_api_settings_colors',
			esc_attr_x('Graphic settings', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_section_colors_text',
			'ncore_plat_api_plugin_settings_colors'
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_primary_color',
			esc_attr_x('Primary color', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_primary_color',
			'ncore_plat_api_plugin_settings_colors',
			'ncoreplat_api_settings_colors',
			array(
				'field_class' => '',
				'type'	=> 'select',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'primary_color',
				'label_for' => 'ncoreplat_api_plugin_setting_primary_color',
				'default_value' => 'black',
				'option_value' => [
					'black' => esc_attr_x('Black', 'ncoreplat-api-settings', 'ncoreplat-api'),
					'white' => esc_attr_x('White', 'ncoreplat-api-settings', 'ncoreplat-api')
				],
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_main_color',
			esc_attr_x('Secondary color', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_main_color',
			'ncore_plat_api_plugin_settings_colors',
			'ncoreplat_api_settings_colors',
			array(
				'field_class' => '',
				'type'	=> 'color',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'main_color',
				'label_for' => 'ncoreplat_api_plugin_setting_main_color',
				'default_value' => '#a78b6b'
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_main_text_color',
			esc_attr_x('Secondary text color', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_main_text_color',
			'ncore_plat_api_plugin_settings_colors',
			'ncoreplat_api_settings_colors',
			array(
				'field_class' => '',
				'type'	=> 'color',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'main_text_color',
				'label_for' => 'ncoreplat_api_plugin_setting_main_text_color',
				'default_value' => '#ffffff'
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_btn_color',
			esc_attr_x('Button color', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_btn_color',
			'ncore_plat_api_plugin_settings_colors',
			'ncoreplat_api_settings_colors',
			array(
				'field_class' => '',
				'type'	=> 'color',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'btn_color',
				'label_for' => 'ncoreplat_api_plugin_setting_btn_color',
				'default_value' => '#5cb85c'
			)
		);
		
		add_settings_field(
			'ncoreplat_api_plugin_setting_btn_text_color',
			esc_attr_x('Button font color', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_setting_btn_text_color',
			'ncore_plat_api_plugin_settings_colors',
			'ncoreplat_api_settings_colors',
			array(
				'field_class' => '',
				'type'	=> 'color',
				'option_group' => 'ncoreplat_api_plugin_settings_options',
				'name' => 'btn_text_color',
				'label_for' => 'ncoreplat_api_plugin_setting_btn_text_color',
				'default_value' => '#ffffff'
			)
		);
		
		add_settings_section(
			'ncoreplat_api_settings_shortcode',
			esc_attr_x('Use Shortcode', 'ncoreplat-api-settings', 'ncoreplat-api'),
			'ncoreplat_api_plugin_section_shortcode_text',
			'ncore_plat_api_plugin_settings_shortcode'
		);
		
	}
	add_action( 'admin_init', 'ncoreplat_api_register_settings' );

	function ncoreplat_api_plugin_options_validate( $input ) {
		$newinput = $input;
		return $newinput;
	}

	function ncoreplat_api_plugin_render_field($args) {
		$options = get_option($args['option_group']);
		$value = (empty($options[$args['name']])) ? esc_attr( $args['default_value'] ) : esc_attr( $options[$args['name']] );
		if ($args['type'] == "checkbox") {
			$checked = "";
			if($value){
				$checked = " checked";
			}
			echo "<fieldset>
				<label for='".$args['label_for']."'>
					<input id='".$args['label_for']."' class='".$args['field_class']."' name='".$args['option_group']."[".$args['name']."]' type='".$args['type']."'".$checked." />
				</label>
			</fieldset>";
		} else if ($args['type'] == "select") {
			$option_value = "";
			foreach($args['option_value'] as $choice_key => $choice_value){
				$selected = "";
				if($value == $choice_key){
					$selected = " selected";
				}
				$option_value .= '<option value="'. htmlentities($choice_key) .'"'.$selected.'>'. $choice_value .'</option>';
			}
			echo "<select id='".$args['label_for']."' class='".$args['field_class']."' name='".$args['option_group']."[".$args['name']."]' type='".$args['type']."'>". $option_value ."</select>";
		} else {
			echo "<input id='".$args['label_for']."' class='".$args['field_class']."' name='".$args['option_group']."[".$args['name']."]' type='".$args['type']."' value='".$value."' />";
		}
	}

	function ncoreplat_api_plugin_section_login_text() {
		$description = _x('API information login', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$description."</p>";
	}

	function ncoreplat_api_plugin_setting_base_endpoint($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_username($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_password($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_advanced_settings_text() {
		$description = _x('', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$description."</p>";
	}

	function ncoreplat_api_plugin_setting_ajax_content($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_section_colors_text() {
		$description = _x('Elements colors', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$description."</p>";
	}

	function ncoreplat_api_plugin_setting_primary_color($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_main_color($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_main_text_color($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_btn_color($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_setting_btn_text_color($args) {
		ncoreplat_api_plugin_render_field($args);
	}

	function ncoreplat_api_plugin_section_shortcode_text() {
		$description = _x('Shortcode example', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$description."</p>";
		$instructions_node = _x('Use this shortcode on your page and replace the "id_node" value with your node ID', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$instructions_node."</p>";
		echo '<p><code>[ncoreplat-api id_node="123"]</code></p>';
		$instructions_position = _x('To render in your page a specific position, replace also the value "id_positon" with your position ID', 'ncoreplat-api-settings', 'ncoreplat-api');
		echo "<p>".$instructions_position."</p>";
		echo '<p><code>[ncoreplat-api id_node="123" id_position="456"]</code></p>';
	}

	// return value to shortcode
	function ncoreplat_api_plugin_setting_get_value($args_name) {
		$options = get_option('ncoreplat_api_plugin_settings_options');
		$result_value = esc_attr( $options[$args_name] );	
		return $result_value;
	}
	// return value for colors
	function ncoreplat_api_color_value($color_value) {
		$color_value = esc_attr($color_value);
		if($color_value){
			list($r, $g, $b) = sscanf($color_value, "#%02x%02x%02x");
			$color_value = "$r, $g, $b";
		}
		return $color_value;
	}