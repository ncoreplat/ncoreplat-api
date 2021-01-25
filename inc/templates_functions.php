<?php

	// Prevent direct file access
	defined( 'ABSPATH' ) or exit;
	
	/**
	* function template position list
	**/
	function templatePositionList($positions, $a) {
		$ajax_content = $a['ajax_content'];
		$html_nCorePlat_positions = "";
		if($positions){
			foreach($positions as $position){
				$position_id = $position->id;
				$position_title = $position->title;
				$position_subtitle = $position->subtitle;
				$position_description_html = $position->description_html;
				$position_description_txt = $position->description_txt;
				$position_opening_date = $position->opening_date;
				$position_closing_date = $position->closing_date;
				$position_node = $position->node;
				$position_node_id = $position->node_id;
				$position_resource_url = $position->resource_url;
				$position_web_url = $position->web_url;
				$position_subtitle_html = "";
				if($position_subtitle) {
					$position_subtitle_html = '<h4 class="ncore-plat-api-position-subtitle ncore-plat-api-text-color">'.$position_subtitle.'</h4>';
				}
				$positon_btn_text = _x( "Apply Now" , "ncoreplat-api-node" , "ncoreplat-api" );
				$positon_btn_attr = 'class="ncore-plat-api-position-web-url" href="'.$position_web_url.'" target="_blank"';
				if($ajax_content){
					$positon_btn_attr = 'id="ncorePlatPositionAction_' . $position_node_id . '_' . $position_id . '" class="ncore-plat-api-position-resource-url" data-position="' . $position_id . '" data-node="' . $position_node_id . '" data-config=\'' . json_encode($a) . '\' href="javascript:void(0);"';
				}
				 
				$html_nCorePlat_positions .= '<div id="ncorePlatPositionNode_' . $position_node_id . '_' . $position_id . '" class="ncore-plat-api-position-node">
					<div id="ncorePlatPosition_' . $position_id . '" class="ncore-plat-api-position">
						<h3 class="ncore-plat-api-position-title">
							' . $position_title . '
						</h3>
						' . $position_subtitle_html . '
						<div class="ncore-plat-api-position-description ncore-plat-api-text-color">
							' . $position_description_html . '
						</div>
						<div class="ncore-plat-api-position-btn">
							<a ' . $positon_btn_attr . '>
								<span>
									' . $positon_btn_text . '
								</span>
							</a>
						</div>
					</div>
					<div class="ncore-plat-api-position-last">></div>
				</div>';
			}
		}
		return $html_nCorePlat_positions;
	}
	
	function templatePositionDetail($position,$templates,$a) {
		$position_node_id = $a['id_node'] ? esc_attr($a['id_node']) : '';
		$call_to_action = $templates->intro_call_to_action;
		$condition = $templates->terms_condition_web;
		$condition_check = $templates->intro_personal_data_treatment_short;
		$privacy = $templates->privacy_policy;
		$privacy_check = $templates->accept_privacy_policy;
		$html_nCorePlat_position = "";
		if($position){
			$html_nCorePlat_position_info = "";
			$html_nCorePlat_position_form = "";
			$position_id = $position->id;
			$position_title = $position->title;
			$position_subtitle = $position->subtitle;
			$position_description_html = $position->description_html;
			$position_description_txt = $position->description_txt;
			$position_opening_date = $position->opening_date;
			$position_closing_date = $position->closing_date;
			$position_web_url = $position->web_url;
			$position_location = $position->location;
			$position_fieldsets = $position->fieldsets;
			$position_subtitle_html = "";
			if($position_subtitle) {
				$position_subtitle_html = '<h4 class="ncore-plat-api-position-subtitle">' . $position_subtitle . '</h4>';
			}
			$html_nCorePlat_position_info .= '<div id="ncorePlatPositionInfo_' . $position_id . '" class="ncore-plat-api-position-info">
				<div id="ncorePlatPosition_' . $position_id . '" class="ncore-plat-api-position">
					<h3 class="ncore-plat-api-position-title">
						' . $position_title . '
					</h3>
					' . $position_subtitle_html . '
					<div class="ncore-plat-api-position-description ncore-plat-api-text-color">
						' . $position_description_html . '
					</div>
				</div>
				<div class="ncore-plat-api-position-last"></div>
			</div>';
			$call_to_action_title = _x( "Apply Now" , "ncoreplat-api-position" , "ncoreplat-api" );
			if(property_exists($call_to_action,'title')){
				$call_to_action_title = $call_to_action->title;
			}
			$call_to_action_text = _x( "Fill in the form below and send your application." , "ncoreplat-api-position" , "ncoreplat-api" );
			if(property_exists($call_to_action,'text')){
				$call_to_action_text = $call_to_action->text;
			}
			$html_nCorePlat_position_form .= '<div id="ncorePlatPositionForm_' . $position_id . '" class="ncore-plat-api-position-form">
				<div id="ncorePlatPosition_' . $position_id . '" class="ncore-plat-api-position">
					<h3 class="ncore-plat-api-position-title">
						' . $call_to_action_title . '
					</h3>
					<div class="ncore-plat-api-position-description ncore-plat-api-text-color">
						' . $call_to_action_text . '
					</div>
					<form name="form" method="post" action="'. admin_url( 'admin-post.php' ) .'?ncore-node=' . $position_node_id . '&ncore-position=' . $position_id . '">';
						foreach($position_fieldsets as $key => $fieldsets){
							foreach($fieldsets as $field){
								$html_nCorePlat_position_field = "";
								$field_id = 'form_'.$key.'_'.$field->canonical_field_name;
								$field_canonical_name = 'form['.$key.']['.$field->canonical_field_name.']';
								$field_name = $field->field_name;
								$field_type = $field->field_type;
								$field_required = $field->required;
								$html_field_required = "";
								$html_checkbox_required_class = "";
								if($field_required){
									$html_checkbox_required_class = " ncore-plat-api-checkbox-required";
									$html_field_required = 'required="required"';
									if(strpos($field_canonical_name, 'fieldset_')){
										$field_name = $field_name . ' *';
									}
								}
								$field_multiple = $field->is_multiple;
								$html_field_multiple = "";
								$html_choice_first_option = '<option value="">' . _x( "Select" , "ncoreplat-api-form" , "ncoreplat-api" ) . '</option>';
								if($field_multiple){
									$html_field_multiple = 'multiple="multiple"';
									$html_choice_first_option = "";
									$field_canonical_name = $field_canonical_name . '[]';
								}
								$field_expanded = $field->expanded;
								if ($field_type == 'textarea'){
									$html_nCorePlat_position_field = '<textarea id="' . $field_id . '" name="' . $field_canonical_name . '" '. $html_field_required .' placeholder="' . $field_name . '" class="ncore-plat-api-textarea"></textarea>';
								} else if ($field_type == 'choice'){
									$choices = json_decode($field->choices);
									$option = $html_choice_first_option;
									$checkbox = "";
									$radio = '<fieldset id="' . $field_id . '" class="ncore-plat-api-fieldset-radio">';
									foreach($choices as $choice_key => $choice_value){
										$option .= '<option value="'. htmlentities($choice_value) .'">'. $choice_value .'</option>';
										$checkbox .= '<label for="' . $field_id . '_' . $choice_key . '" class="ncore-plat-api-checkbox' . $html_checkbox_required_class . '">
											<input type="checkbox" value="' . htmlentities($choice_value) . '" id="' . $field_id . '_' . $choice_key . '" name="' . $field_canonical_name . '" '. $html_field_required .'>
											<span class="ncore-plat-api-text-color">' . $choice_value . '</span>
										</label>';
										$radio .= '<label for="' . $field_id . '_' . $choice_key . '" class="ncore-plat-api-radio">
											<input type="radio" value="' . htmlentities($choice_value) . '" id="' . $field_id . '_' . $choice_key . '" name="' . $field_canonical_name . '" '. $html_field_required .'>
											<span class="ncore-plat-api-text-color">' . $choice_value . '</span>
										</label>';
									}
									$radio .= '</fieldset>';
									if ($field_expanded && $field_multiple) {
										$html_nCorePlat_position_field = '<div class="ncore-plat-api-text-color">' . $field_name . '</div>' . $checkbox;
									} else if ($field_expanded) {
										$html_nCorePlat_position_field = '<div class="ncore-plat-api-text-color">' . $field_name . '</div>' . $radio;
									} else {
										$html_nCorePlat_position_field = '<div class="ncore-plat-api-text-color">' . $field_name . '</div>
										<select id="' . $field_id . '" name="' . $field_canonical_name . '" '. $html_field_required .'  '. $html_field_multiple .' class="ncore-plat-api-field">
											'. $option .'
										</select>';
									}
								} else if ($field_type == 'date'){
									$html_nCorePlat_position_field = '<label for="' . $field_id . '" class="ncore-plat-api-label">
										<div class="ncore-plat-api-text-color">' . $field_name . '</div>
										<input type="' . $field_type . '" placeholder="yyyy-mm-dd" id="' . $field_id . '" name="' . $field_canonical_name . '" '. $html_field_required .' class="ncore-plat-api-field">
									</label>';
									
								} else if ($field_type != 'file'){
									$html_nCorePlat_position_field = '<label for="' . $field_id . '" class="ncore-plat-api-label">
										<input type="' . $field_type . '" placeholder="' . $field_name . '" id="' . $field_id . '" name="' . $field_canonical_name . '" '. $html_field_required .' class="ncore-plat-api-field">
									</label>';
								}
								if($html_nCorePlat_position_field) {
									$html_nCorePlat_position_form .= '<div class="ncore-plat-api-position-form-group">' . $html_nCorePlat_position_field . '</div>';
								}
							}
						}
						$token = wp_create_nonce( 'application_submition' );
						$html_nCorePlat_position_form .= '<input type="hidden" name="ncore_plat_api__token" value="' . $token . '">';
						$html_nCorePlat_position_form .= '<input type="hidden" name="action" value="ncore_plat_api_send_data">';
						/* Start logica per policy */
						if(property_exists($condition,'title')){
							$html_nCorePlat_position_form .= '<div class="ncore-plat-api-position-form-group">
								<a class="ncore-plat-api-open-modal ncore-plat-api-text-color" href="javascript:void(0);"
									data-modaltitle="' . $condition->title . '" data-modaltext="' . $condition->text . '">
									<span class="ncore-plat-api-text-color">' . strip_tags($condition_check->text) . '</span>
								</a>
								<label for="ncore_plat_api_terms_and_conditions" class="ncore-plat-api-checkbox ncore-plat-api-check-policy">
									<input id="ncore_plat_api_terms_and_conditions" name="ncore_plat_api_terms_and_conditions" type="checkbox" required="required">
									<span class="ncore-plat-api-text-color"> ' . $condition_check->title . '</span>
								</label>
							</div>';
						}
						if(property_exists($privacy,'title')){
							$html_nCorePlat_position_form .= '<div class="ncore-plat-api-position-form-group">
								<a class="ncore-plat-api-open-modal ncore-plat-api-text-color" href="javascript:void(0);"
									data-modaltitle="' . $privacy->title . '" data-modaltext="' . $privacy->text . '">
									<span class="ncore-plat-api-text-color">' . strip_tags($privacy_check->text) . '</span>
								</a>
								<label for="ncore_plat_api_privacy_policy" class="ncore-plat-api-checkbox ncore-plat-api-check-policy">
									<input id="ncore_plat_api_privacy_policy" name="ncore_plat_api_privacy_policy" type="checkbox" required="required">
									<span class="ncore-plat-api-text-color"> ' . $privacy_check->title . '</span>
								</label>
							</div>';
						}
						/* End logica per policy */
						$html_nCorePlat_position_form .= '<button type="submit" class="ncore-plat-api-position-submit">
							' . _x( "Continue" , "ncoreplat-api-form" , "ncoreplat-api" ) . 
						'</button>';
					$html_nCorePlat_position_form .= '</form>
				</div>
				<div class="ncore-plat-api-position-last"></div>
			</div>';
			$html_nCorePlat_position .= $html_nCorePlat_position_info;
			$html_nCorePlat_position .= $html_nCorePlat_position_form;
		}
		return $html_nCorePlat_position;
	}