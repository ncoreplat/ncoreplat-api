(function($) {
	
	$(document).ready(function() {
		
		$('.ncore-plat-api-position-resource-url').on('click',function() {
			let id_node = $(this).data('node');
			let id_position = $(this).data('position');
			let config = $(this).data('config');
			change_postion_shortcode(id_node,id_position,config);
		});
		
		function change_postion_shortcode(id_node,id_position,config) {
			let action_element = $('#ncorePlatPositionAction_'+id_node+'_'+id_position+'');
			if (action_element.length){
				let replace_element = action_element.parents('.ncore-plat-api-container');
				config.action = 'get_shortcode_ncore_plat_api_content';
				config.node = id_node;
				config.position = id_position;
				let queryString = window.location.search;
				if (queryString.indexOf('ncore-node') >= 1 && queryString.indexOf('ncore-position') >= 1) {
					replace_element.hide();
				}
				$.ajax({
					url: nCorePlatApiAjax.ajax_url,
					type: "post",
					data: config,
					success: function (response) {
						history.pushState("", "", "?ncore-node="+id_node+"&ncore-position="+id_position);
						replace_element.replaceWith(response);
						replace_element.show();					
					}
				});
			}
		}
		
		function checkLocation(history) {
			let queryString = window.location.search;
			let urlParams = new URLSearchParams(queryString);
			if (queryString.indexOf('nCoreUrl') >= 1) {
				let nCoreUrl = urlParams.get('nCoreUrl');
				let decondeUrl = atob(nCoreUrl);
				window.location.replace(decondeUrl);
			} else if (queryString.indexOf('ncore-node') >= 1 && queryString.indexOf('ncore-position') >= 1) {
				let id_node = urlParams.get('ncore-node');
				let id_position = urlParams.get('ncore-position');
				let action_element = $('#ncorePlatPositionAction_'+id_node+'_'+id_position+'');
				let config = action_element.data('config');
				change_postion_shortcode(id_node,id_position,config);
			} else {
				if (history){
					location.reload(); 
				}
			}
		}
		
		checkLocation(false);
		
		window.onpopstate = function(event) {
			checkLocation(true);
		};
		
		$('.ncore-plat-api-checkbox-required input').each(function() {
			checkRequired($(this));
		});
		
		$(document).on('change','.ncore-plat-api-checkbox-required input',function(){
			checkRequired($(this));
		});
		
		function checkRequired(element){
			let name = element.attr('name');
			let allCheckboxes = $('input[name="'+name+'"]');
			let checked = element.is(':checked');
			let someoneChecked = false;
			allCheckboxes.each(function(){
				if(checked){
					$(this).removeAttr('required');
				} else if(!someoneChecked){
					if($(this).is(':checked')){
						someoneChecked = true;
					}
				}
			});
			if(!checked && !someoneChecked){
				allCheckboxes.each(function(){
					$(this).attr('required', 'required');
				});
			}
		}
		
		// jQuery modal
		$('body').on('click', '.ncore-plat-api-open-modal', function() {
			$('body').addClass('ncore-plat-api-open-modal-body');
			let modaltitle = $(this).data('modaltitle');
			let modaltext = $(this).data('modaltext');
			let modal_element = $(this).parents('.ncore-plat-api-open-modal-body').find('.ncore-plat-api-modal');
			modal_element.find('.ncore-plat-api-modal-title').html(modaltitle);
			modal_element.find('.ncore-plat-api-modal-text').html(modaltext);
			modal_element.show(150);
		});
		
		$('body').on('click', '.ncore-plat-api-close-modal', function() {
			$(this).parents('.ncore-plat-api-modal').hide(150);
			$('body').removeClass('ncore-plat-api-open-modal-body');
		});

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			let modal_element = $('.ncore-plat-api-modal');
			if (event.target == modal_element[0]) {
				modal_element.hide(150);
				$('body').removeClass('ncore-plat-api-open-modal-body');
			}
		}
		
	});
	
})( jQuery );