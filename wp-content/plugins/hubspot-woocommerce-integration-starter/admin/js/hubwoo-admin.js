(function( $ ) {
	'use strict';

	// i18n variables
	var ajaxUrl 					= hubwooi18n.ajaxUrl;
	var hubwooOverviewTab 			= hubwooi18n.hubwooOverviewTab;
	var hubwooWentWrong 			= hubwooi18n.hubwooWentWrong;
	var hubwooSuccess 				= hubwooi18n.hubwooSuccess;
	var hubwooCreatingGroup 		= hubwooi18n.hubwooCreatingGroup;
	var hubwooCreatingProperty 		= hubwooi18n.hubwooCreatingProperty;
	var hubwooSetupCompleted 		= hubwooi18n.hubwooSetupCompleted;
	var hubwooMailSuccess 			= hubwooi18n.hubwooMailSuccess;
	var hubwooMailFailure 			= hubwooi18n.hubwooMailFailure;
	var hubwooSecurity 				= hubwooi18n.hubwooSecurity;
	var hubwooAccountSwitch 		= hubwooi18n.hubwooAccountSwitch;
	var hubwooNoUsersFound 			= hubwooi18n.hubwooNoUsersFound;
	var hubwooBatchUpdate 			= hubwooi18n.hubwooBatchUpdate;
	var hubwooUserSyncComplete 		= hubwooi18n.hubwooUserSyncComplete;
	var hubwooLicenseUpgrade 		= hubwooi18n.hubwooLicenseUpgrade;
	var hubwooRollback 				= hubwooi18n.hubwooRollback;
	var hubwooUpdateSuccess 		= hubwooi18n.hubwooUpdateSuccess;
	var hubwooUpdateFail 			= hubwooi18n.hubwooUpdateFail;
	var hubwooConnectTab 			= hubwooi18n.hubwooConnectTab;
	var hubwooGroupSetupCompleted 	= hubwooi18n.hubwooGroupSetupCompleted;
	var hubwooCreatingList 			= hubwooi18n.hubwooCreatingList;
	var hubwooGroupExists 			= hubwooi18n.hubwooGroupExists;
	var hubwooPropertyExists 		= hubwooi18n.hubwooPropertyExists;
	var hubwooListExists 			= hubwooi18n.hubwooListExists;
	var hubwooOrdersSyncComplete 	= hubwooi18n.hubwooOrdersSyncComplete;
	var hubwooNoOrdersFound 		= hubwooi18n.hubwooNoOrdersFound;
	
	jQuery(document).ready(function(){

		jQuery('a#hubwoo-get-started').on("click",function(e){
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_get_started_call', 'hubwooSecurity' : hubwooSecurity }, function( status ) {
				window.location.href = hubwooConnectTab;
			});
		});

		jQuery('a.hubwoo-tab-disabled').on("click", function(e) {
			return false;
		});


		jQuery('.hubwoo_tracking').on('click',function(){
			jQuery('.hub_pop_up_wrap').show();
		});

		jQuery('a#hubwoo-refresh-token').on('click', function(e){
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response) {
				var oauth_response = jQuery.parseJSON( response );
				var oauthMessage = oauth_response.message;
				alert( oauthMessage );
				location.reload();
			});
		});

		jQuery('a.hubwoo-change-group-decision').on('click', function(e) {

			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_clear_user_group_choice', 'hubwooSecurity' : hubwooSecurity }, function(response) {
				location.reload();
			});
		});

		jQuery('a.hubwoo-run-fields-decision').on('click', function(e) {

			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_clear_user_field_choice', 'hubwooSecurity' : hubwooSecurity }, function(response) {
				location.reload();
			});
		});

		jQuery( 'a.hubwoo_starter_select_groups').on( 'click', function(e) {
			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_group_choice', 'hubwooSecurity' : hubwooSecurity, 'choice' : 'yes' }, function( status ){
				location.reload();
			});
		});

		jQuery( 'a.hubwoo_groups_go_with_integration').on( 'click', function(e) {
			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_group_choice', 'hubwooSecurity' : hubwooSecurity, 'choice' : 'no' }, function( status ){
				location.reload();
			});
		});

		jQuery('a.hubwoo_starter_select_lists').on('click', function(e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_list_choice', 'hubwooSecurity' : hubwooSecurity, 'choice' : 'yes' }, function( status ){
				location.reload();
			});
		});

		jQuery('a.hubwoo_lists_go_with_integration').on('click', function(e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_list_choice', 'hubwooSecurity' : hubwooSecurity, 'choice' : 'no' }, function( status ){
				location.reload();
			});
		});

		jQuery('.hubwoo-change-list-decision').on('click', function(e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_clear_user_list_choice', 'hubwooSecurity' : hubwooSecurity }, function( status ){
				location.reload();
			});
		});

		jQuery('a#hubwoo-select-all-fields').on('click', function(e){
			jQuery('input.hubwoo_select_property').attr('checked', true);
		});

		jQuery('a#hubwoo-clear-all-fields').on('click', function(e){
			jQuery('input.hubwoo_select_property').attr('checked', false);
		});

		jQuery('a#hubwoo-select-all-groups').on('click', function(e){
			jQuery('input.hubwoo_select_group').attr('checked', true);
		});

		jQuery('a#hubwoo-clear-all-groups').on('click', function(e){
			jQuery('input.hubwoo_select_group').attr('checked', false);
		});

		jQuery('#hubwoo_starter_upgrade').on( 'click', function(e) {

			if( confirm( hubwooLicenseUpgrade ) ) {

				jQuery("form#hubwoopro").submit();
			}
			else {

				e.preventDefault();
				return false;
			}
		});

		jQuery('#hubwoo_starter_switch').on( 'click', function(e) {

			if( confirm( hubwooAccountSwitch ) ) {

				jQuery("form#hubwoopro").submit();
				location.reload();
			}
			else {

				e.preventDefault();
				return false;
			}
		});

		jQuery('#hubwoo_starter_rollback').on( 'click', function(e) {

			if( confirm(hubwooRollback) ) {

				jQuery("form#hubwoo_rollback_form").submit();
			}
			else {

				e.preventDefault();
				return false;
			}
		});

		jQuery('.hubwoo_tracking').on('click',function() {
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_clear_mail_choice', 'hubwooSecurity' : hubwooSecurity }, function(response){
				location.reload();
			});
		});

		jQuery('.hubwoo_later').on('click',function(){

			jQuery('#hubwoo_loader').show();

			jQuery.post( ajaxUrl, {'action' : 'hubwoo_suggest_later', 'hubwooSecurity' : hubwooSecurity }, function(response){
				location.reload();
			});
		});

		jQuery('.hubwoo_accept').on('click',function(){

			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {
					
					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_suggest_accept', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

						if( response != null ) {

							var data_status = response;

							if( data_status == 'failure' ) {

								alert(hubwooMailFailure);
								location.reload();
							}
						}
						else {
							// close the popup and show the error.
							alert( hubwooWentWrong );
							location.reload();
						}
					});
				}
				else {
					// close the popup and show the error.
					alert( hubwooWentWrong );
					location.reload();
				}
			});
		});

		jQuery('.hubwoo_starter_select_fields').on('click', function(e){

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_field_choice', 'hubwooSecurity' : hubwooSecurity, async: false, 'choice' : 'yes' }, function( status ) {
				location.reload();
			});
		});

		jQuery('.hubwoo_fields_go_with_integration').on('click', function(e){

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_save_user_field_choice', 'hubwooSecurity' : hubwooSecurity, async: false, 'choice' : 'no' }, function( status ) {
				location.reload();
			});
		});

		//run groups setup
		jQuery( "a.hubwoo-run-groups-setup" ).on( 'click', function(e) {

			jQuery( '#hubwoo_loader' ).show();

			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {

					jQuery( '#hubwoo_loader' ).hide();
					jQuery( '#hubwoo-setup-process' ).show();
					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_get_groups_to_create', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

						if( response!= null ) {

							var groups = jQuery.parseJSON(response);
							var group_count = groups.length;
							var group_progress = parseFloat(100/group_count);
							var current_progress = 0;

							jQuery.each( groups, function( index, group_details ) {

								var displayName = group_details.displayName;

								current_progress += group_progress;

								jQuery('.progress-bar').css('width',current_progress+'%');

								jQuery.ajax( { url: ajaxUrl, type: 'POST', data : { 'action' : 'hubwoo_create_group_and_property', 'groupDetails' : group_details, 'createNow': 'group', 'hubwooSecurity' : hubwooSecurity }, async: false } ).done( function ( propResponse ) {

									var response = jQuery.parseJSON( propResponse );
									var errors = response.errors;
									var hubwooMessage = "";

									if( !errors ) {

										var responseCode = response.status_code;

										if( responseCode == 200 ) {

											hubwooMessage = "<div class='notice updated'><p> "+ hubwooCreatingGroup + " <strong>" + displayName +"</strong></p></div>";
										}
										else {

											var hubwooResponse = response.response;

											if( hubwooResponse != null && hubwooResponse != "" ) {

												hubwooResponse = jQuery.parseJSON( hubwooResponse );

												hubwooMessage = "<div class='notice error'><p> "+ hubwooResponse.message +"</p></div>";
											}
											else {

												hubwooMessage = "<div class='notice error'><p> "+ responseCode +"</p></div>";
											}
										}
									}
									else {

										hubwooMessage = "<div class='notice error'><p> "+ errors +"</p></div>";
									}

									jQuery(".hubwoo-message-area").append( hubwooMessage );
								});
							});
						}
						else {

							alert( hubwooWentWrong );
							return false;
						}

						jQuery.post( ajaxUrl, { 'action': 'hubwoo_group_setup_completed', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

							alert( hubwooGroupSetupCompleted );

							location.reload();
						});
					});
				}
				else {
					
					alert( hubwooWentWrong );
					jQuery('#hubwoo_loader').hide();
					return false;
				}
			});
		});

		//create single group
		jQuery('a.hubwoo-create-single-group').on('click', function(e) {

			var name = $(this).data("name");
			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;

				if( oauth_status ) {

					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_create_single_group', 'name' : name, 'hubwooSecurity' : hubwooSecurity }, function( response ) {

						var proresponse = jQuery.parseJSON( response );
						var proerrors = proresponse.errors;
						var prohubMessage = "";

						if( !proerrors ){

							var proresponseCode = proresponse.status_code;

							if( proresponseCode == 200 ) {

								alert( hubwooCreatingGroup );
							}
							else if( proresponseCode == 409 ) {

								alert( hubwooGroupExists );
							}
							else {
								
								alert(hubwooWentWrong);
							}

							location.reload();
						}
					});
				}
			});
		});

		// run the setup.
		jQuery(".hubwoo-run-fields-setup").on( 'click', function() {
			
			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {
					
					jQuery('#hubwoo_loader').hide();
					jQuery('#hubwoo-setup-process').show();
					// show the loader and current processing state.
					jQuery.post( ajaxUrl, {'action' : 'hubwoo_get_groups', 'hubwooSecurity' : hubwooSecurity }, function(response){
						
						if( response != null ) {

							// get all groups
							var groups = jQuery.parseJSON(response);
							var group_count = groups.length;

							var group_progress = parseFloat(100/group_count);
							var current_progress = 0;
							var allProperties_progress = 0;

							// loop all groups
							jQuery.each( groups, function( index, group_details ) {
									
								//let's create the group property.
								var getProperties = { action : 'hubwoo_get_group_properties', groupName: group_details.name, 'hubwooSecurity' : hubwooSecurity };
								
								jQuery.ajax({ url: ajaxUrl, type: 'POST', data : getProperties, async: false }).done(function( propResponse ){

									if( propResponse != null ) {
										// parse all properties.
										var allProperties = jQuery.parseJSON( propResponse );
										var allProperties_count = allProperties.length;
										
										allProperties_progress = parseFloat(group_progress/allProperties_count);
										
										jQuery.each( allProperties, function( i, propertyDetails ) {

											current_progress+= allProperties_progress;
											jQuery('.progress-bar').css('width',current_progress+'%');
											var createProperties = { action : 'hubwoo_create_group_property', groupName: group_details.name, propertyDetails: propertyDetails, 'hubwooSecurity' : hubwooSecurity };

											jQuery.ajax({ url: ajaxUrl, type: 'POST', data : createProperties, async: false }).done(function( propertyResponse ){

												var proresponse = jQuery.parseJSON( propertyResponse );
												var proerrors = proresponse.errors;
												var prohubwooMessage = "";

												if( !proerrors ) {

													var proresponseCode = proresponse.status_code;

													if( proresponseCode == 200 ){

														prohubwooMessage = "<div class='notice updated'><p> "+ hubwooCreatingProperty + " <strong>" + propertyDetails.name +"</strong></p></div>";

													}
													else{

														var prohubwooResponse = proresponse.response;

														if( prohubwooResponse != null && prohubwooResponse != "" ){

															prohubwooResponse = jQuery.parseJSON( prohubwooResponse );

															prohubwooMessage = "<div class='notice error'><p> "+ prohubwooResponse.message +"</p></div>";
														}
														else{

															prohubwooMessage = "<div class='notice error'><p> "+ proresponseCode +"</p></div>";
														}
													}
												}
												else {

													prohubwooMessage = "<div class='notice error'><p> "+ proerrors +"</p></div>";
												}
												
												jQuery(".hubwoo-message-area").append( prohubwooMessage );
											});
										});
									}
								});	
							});
						}
						else
						{
							// close the popup and show the error.
							alert( hubwooWentWrong );

							return false;
						}

						// mark the process as completed.
						jQuery.post(ajaxUrl, {'action': 'hubwoo_setup_completed', 'hubwooSecurity' : hubwooSecurity}, function( response ){

							alert( hubwooSetupCompleted );

							location.reload();
						});
					}); 
				}
				else {
					// close the popup and show the error.
					alert( hubwooWentWrong );
					jQuery('#hubwoo_loader').hide();
					return false;
				}
			});
		});

		jQuery("a.hubwoo-run-lists-setup").on( 'click', function() {

			jQuery('#hubwoo_loader').show();
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){

				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;

				if( oauth_status ) {

					jQuery('#hubwoo_loader').hide();
					jQuery('#hubwoo-setup-process').show();

					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_get_lists', 'hubwooSecurity' : hubwooSecurity }, function(response){

						if( response != null ) {

							var lists = jQuery.parseJSON(response);
							var list_count = lists.length;

							var list_progress = parseFloat(100/list_count);
							var current_progress = 0;

							// loop all lists
							jQuery.each(lists, function(index,list_details){
								
								var displayName = list_details.name;
								
								var listData = {
									'action' : 'hubwoo_create_list',
									'listDetails': list_details,
									'hubwooSecurity' : hubwooSecurity
								};

								current_progress += list_progress;

								jQuery('.progress-bar').css('width',current_progress+'%');

								jQuery.ajax({ url: ajaxUrl, type: 'POST', data : listData, async: false }).done(function(response){
									
									if( response != null ) {

										var response = jQuery.parseJSON( response );
										var errors = response.errors;
										var hubwooMessage = "";

										if( !errors ) {

											var responseCode = response.status_code;

											if( responseCode == 200 ){

												hubwooMessage = "<div class='notice updated'><p> "+ hubwooCreatingList + " <strong>" + displayName +"</strong></p></div>";

											}
											else{

												var hubwooResponse = response.response;

												if( hubwooResponse != null && hubwooResponse != "" ){

													hubwooResponse = jQuery.parseJSON( hubwooResponse );

													hubwooMessage = "<div class='notice error'><p> "+ hubwooResponse.message +"</p></div>";
												}
												else{

													hubwooMessage = "<div class='notice error'><p> "+ responseCode +"</p></div>";
												}
											}
										}
										else{

											hubwooMessage = "<div class='notice error'><p> "+ errors +"</p></div>";
										}

										jQuery(".hubwoo-message-area").append( hubwooMessage );
									}
									else {

										// close the popup and show the error.
										alert( hubwooWentWrong );

										return false;
									}
								});
							});
						}
						else {

							// close the popup and show the error.
							alert( hubwooWentWrong );

							return false;
						}

						jQuery.post(ajaxUrl, {'action': 'hubwoo_lists_setup_completed', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

							alert( hubwooSetupCompleted );

							location.reload();
						});
					});
				}
			});
		});

		jQuery('.hubwoo-create-single-list').on( 'click', function(e){

			var name = $(this).data("name");

			jQuery('#hubwoo_loader').show();

			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;

				if( oauth_status ) {

					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_create_single_list', 'name' : name, 'hubwooSecurity' : hubwooSecurity }, function( response ) {

						var proresponse = jQuery.parseJSON( response );
						var proerrors = proresponse.errors;
						var prohubMessage = "";

						if( !proerrors ) {

							var proresponseCode = proresponse.status_code;

							if( proresponseCode == 200 ) {

								alert( hubwooCreatingList );
							}
							else if( proresponseCode == 409 ) {

								alert( hubwooListExists );
							}
							else {
								
								alert( hubwooWentWrong );
							}

							location.reload();
						}
					});
				}
			});
		});

		jQuery('a#hubwoo_starter_up_date').on( 'click', function(){
		
			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status )
				{
					$.ajax({
					    type:'POST',
					    url :ajaxUrl,
					    data:{ action : 'hubwoo_starter_update_properties', hubwooSecurity : hubwooSecurity },
					    success:function( data ) {

		    				jQuery('#hubwoo_loader').hide();

		    				if( data ) {

		    					alert( hubwooUpdateSuccess );
		    					location.reload();
		    				}
		    				else {

		    					alert( hubwooUpdateFail );
		    					location.reload();
		    				}
		    			}
					})
				}
			});
		});

		jQuery('#hubwoo-customers-run-setup').on( 'click', function(){

			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {

					jQuery.post( ajaxUrl, {'action' : 'hubwoo_customer_get_count', 'hubwooSecurity' : hubwooSecurity }, function( count ){

						if( count > 0 ) {

							jQuery('#hubwoo_loader').hide();
							jQuery('#hubwoo-customer-setup-process').show();
							var total_users = count;
							var offset = 0;
							var hubwooMessage = "";

							while( offset < total_users ){

								jQuery.ajax({url:ajaxUrl,type:'POST',async: false, data:{'action' : 'hubwoo_customer_sync', 'offset' : offset,'hubwooSecurity' : hubwooSecurity } }).done(function(message){

									message = jQuery.parseJSON(message);

									if( message != null ) {

										if( message.status_code == 202 ) {

											hubwooMessage = "<div class='notice updated'><p> "+ hubwooBatchUpdate + " </p></div>";
										}
										else {

											hubwooMessage = "<div class='notice error'><p> "+ message.response + " </p></div>";
										}

										jQuery(".hubwoo-customer-message-area").append( hubwooMessage );
									}
								});

								offset += 50;
							}

							alert( hubwooUserSyncComplete );

							location.reload();
						}
						else {

							alert( hubwooNoUsersFound );
							location.reload();
						}
					});
				}
			});
		});

		//searching order statuses on ajax request
		jQuery('#hubwoo-order-statuses').select2({
	  		ajax:{
	    			url: ajaxurl,
	    			dataType: 'json',
	    			delay: 200,
	    			data: function (params) {
	      				return {
	        				q: params.term,
	        				action: 'hubwoo_search_for_order_status'
	      				};
	    			},
	    			processResults: function( data ) {
					var options = [];
					if ( data ) 
					{
						$.each( data, function( index, text )
						{
							options.push( { id: text[0], text: text[1]  } );
						});
					}
					return {
						results:options
					};
				},
				cache: true
			},
		});

		// creating single property on admin call
		jQuery('.hubwoo-create-single-field').on( 'click', function(e){

			var name = $(this).data("name");
			var group = $(this).data("group");

			jQuery('#hubwoo_loader').show();

			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;

				if( oauth_status ) {

					jQuery.post( ajaxUrl, { 'action' : 'hubwoo_create_single_property', 'name' : name, 'group' : group, 'hubwooSecurity' : hubwooSecurity }, function( response ) {

						var proresponse = jQuery.parseJSON( response );
						var proerrors = proresponse.errors;
						var prohubMessage = "";

						if( !proerrors ) {

							var proresponseCode = proresponse.status_code;

							if( proresponseCode == 200 ) {

								alert( hubwooCreatingProperty );
							}
							else if( proresponseCode == 409 ) {

								alert( hubwooPropertyExists );
							}
							else {
								
								alert( hubwooWentWrong );
							}

							location.reload();
						}
					});
				}
			});
		});

		jQuery('#hubwoo-selected-user-roles').select2({
	  		ajax:{
	    			url: ajaxurl,
	    			dataType: 'json',
	    			delay: 200,
	    			data: function (params) {
	      				return {
	        				q: params.term,
	        				action: 'hubwoo_search_for_user_roles'
	      				};
	    			},
	    			processResults: function( data ) {
					var options = [];
					if ( data ) 
					{
						$.each( data, function( index, text )
						{
							options.push( { id: text[0], text: text[1]  } );
						});
					}
					return {
						results:options
					};
				},
				cache: true
			},
		});

		jQuery('#hubwoo_old_properties_update').on('click', function(e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {

					$.ajax({
					    type:'POST',
					    url :ajaxUrl,
					    async: false,
					    data:{ action : 'hubwoo_starter_update_old_properties', hubwooSecurity : hubwooSecurity },
					    success:function( data ) {

		    				jQuery('#hubwoo_loader').hide();

		    				if( data ) {

		    					jQuery.post( ajaxUrl, {'action' : 'hubwoo_add_update_option', 'hubwooSecurity' : hubwooSecurity }, function(response){
		    						alert( hubwooUpdateSuccess );
		    						window.location.href = hubwooOverviewTab;
		    					});
		    				}
		    				else {

		    					alert( hubwooUpdateFail );
		    					location.reload();
		    				}
		    			}
					})
				}
			});
		});

		jQuery('img.order-list-rule-del').on("click",function(e){
    		e.preventDefault();
	    	var btn_id = $(this).data("id");
			jQuery(".hubwoo-add-more-order-list-actions tr[data-id='" + btn_id + "']").remove();
		});

		jQuery('a.hubwoo-add-order-list-action').on('click', function(e) {

			e.preventDefault(); 
			jQuery('#hubwoo_loader').show();
			var count = jQuery('.hubwoo-add-more-order-list-actions tr.order-list-rule').length;
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_get_order_list_action_html', 'key' : count }, function( data ){
				jQuery('#hubwoo_loader').hide();
			    jQuery("table.hubwoo-add-more-order-list-actions").append(data);
			    jQuery('img.order-list-rule-del').on("click",function(e){
		    		e.preventDefault();
			    	var btn_id = $(this).data("id");
					jQuery(".hubwoo-add-more-order-list-actions tr[data-id='" + btn_id + "']").remove();
				});
			});
		});

		jQuery('img.customer-list-rule-del').on("click",function(e){
    		e.preventDefault();
	    	var btn_id = $(this).data("id");
			jQuery(".hubwoo-add-more-customer-list-actions tr[data-id='" + btn_id + "']").remove();
		});

		jQuery('a.hubwoo-add-customer-list-action').on('click', function(e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			var count = jQuery('.hubwoo-add-more-customer-list-actions tr.customer-list-rule').length;
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_get_customer_list_action_html', 'key' : count }, function(data){
				jQuery('#hubwoo_loader').hide();
			    jQuery("table.hubwoo-add-more-customer-list-actions").append(data);
			    jQuery('img.customer-list-rule-del').on("click",function(e){
		    		e.preventDefault();
			    	var btn_id = $(this).data("id");
					jQuery(".hubwoo-add-more-customer-list-actions tr[data-id='" + btn_id + "']").remove();
				});
			});
		});

		jQuery('.hubwoo-date-picker').datepicker( { dateFormat: "dd-mm-yy", maxDate: 0, changeMonth: true, changeYear: true } );

		//one click orders sync
		jQuery('#hubwoo-run-order-ocs').on( 'click', function() {

			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, {'action' : 'hubwoo_check_oauth_access_token', 'hubwooSecurity' : hubwooSecurity }, function(response){
				
				var oauth_response = jQuery.parseJSON( response );
				var oauth_status = oauth_response.status;
				var oauthMessage = oauth_response.message;
				
				if( oauth_status ) {
					
					jQuery.post( ajaxUrl, {'action' : 'hubwoo_get_orders_count', 'hubwooSecurity' : hubwooSecurity }, function(count){

						if( count > 0 ) {

							jQuery('#hubwoo_loader').hide();
							jQuery('#hubwoo-customer-setup-process').show();
							var total_orders = count;
							var offset = 0;
							var hubwooMessage = "";

							while( offset < total_orders ) {

								jQuery.ajax( { url:ajaxUrl, type:'POST', async: false, data : {'action' : 'hubwoo_order_sync', 'offset' : offset, 'hubwooSecurity' : hubwooSecurity } }).done(function(message){

									message = jQuery.parseJSON(message);

									if( message != null ) {

										if( message.status_code == 202 ) {

											hubwooMessage = "<div class='notice updated'><p> "+ hubwooBatchUpdate + " </p></div>";
										}
										else {

											hubwooMessage = "<div class='notice error'><p> "+ message.response + " </p></div>";
										}

										jQuery(".hubwoo-customer-message-area").append( hubwooMessage );
									}
								});

								offset += 50;
							}

							alert( hubwooOrdersSyncComplete );

							location.reload();
						}
						else {

							alert( hubwooNoOrdersFound );
							location.reload();
						}
					});
				}
			});
		});

		jQuery('#hubwoo-starter-email-logs').on( 'click', function (e) {

			e.preventDefault();
			jQuery('#hubwoo_loader').show();
			
			jQuery.post( ajaxUrl, { 'action' : 'hubwoo_email_the_error_log', 'hubwooSecurity' : hubwooSecurity }, function( response ) {

				if( response != null ) {

					if( response == "success" ) {

						alert(hubwooMailSuccess);
						location.reload();
					}
					else {

						alert(hubwooMailFailure);
						location.reload();
					}
				}
				else {
					// close the popup and show the error.
					alert( hubwooMailFailure );
					location.reload();
				}
			});
		});
	});
})( jQuery );