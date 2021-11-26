<?php

global $hubwoo;

if( isset( $_POST[ 'hubwoo_save_selected_fields' ] ) ) {

	unset( $_POST[ 'hubwoo_save_selected_fields' ] );

	if( !isset( $_POST[ 'selected_properties' ] ) ) {

		$_POST[ 'selected_properties' ] = array();
	}

	update_option( 'hubwoo_starter_selected_properties', $_POST[ 'selected_properties' ] );

	$message = __( 'Fields saved successfully.' , 'hubwoo' );

	Hubwoo::hubwoo_notice( $message, 'success' );
}
?>

<div class="hubwoo-fields-header hubwoo-common-header">
	<h2><?php _e("HubSpot Custom Properties","hubwoo") ?></h2>
</div> 
<?php
	$user_choice = $hubwoo->hubwoo_user_field_choice();

	if( !$hubwoo->is_field_setup_completed() ) {
?>
		<div class="hubwoo-fields-container">
			<?php

				if( empty( $user_choice ) ) {

					$display = "block";
				}
				else {
					$display = "none";
				}
			?>
			<div class="hubwoo_pop_up_wrap" style="display: <?php echo $display; ?>">
				<div class="pop_up_sub_wrap">
					<p>
						<?php _e("Before we start with Custom Properties setup, would you like to filter the properties before creating them? Filtering the properties allows you to select them which you want to be created in your HubSpot account.","hubwoo") ?>
					</p>
					<div class="button_wrap">
						<a href="javascript:void(0);" class="hubwoo_fields_go_with_integration"><?php _e("I would like to go with the extension.",'hubwoo'); ?></a>
						<a href="javascript:void(0);" class="hubwoo_starter_select_fields"><?php _e('Yes, allow me to filter','hubwoo'); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-fields-on-user-choice">
				<?php 

					if( $user_choice == "no" ) {

						?>
							<div class="hubwoo-fields-display">
								<p><?php _e( "These are the custom fields which will be created by the extension on your HubSpot account or if you want, can change your decision.","hubwoo") ?></p>
								<?php

									$hubwoo_groups = $hubwoo->hubwoo_get_final_groups();
									
									if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

										foreach( $hubwoo_groups as $single_group_info ) {

											if( isset( $single_group_info['status'] ) && $single_group_info['status'] == 'created' ) {

												?>
												<div class="hubwoo_groups">
													<table class="form-table">
														<tbody>
															<tr valign="top">
																<th scope="row" class="titledesc">
																	<p class="hubwoo_group_name">
																		<?php echo $single_group_info["detail"]["displayName"] ?>
																	</p>
																</th>
																<td class="forminp forminp-text">
																	<table>
																    	<?php 

																    	$hubwoo_starterperties = HubWooContactProperties::get_instance()->_get( 'properties', $single_group_info["detail"]["name"] );

																    	if( is_array( $hubwoo_starterperties ) && count( $hubwoo_starterperties ) ) {

																    		foreach( $hubwoo_starterperties as $single_property ) {

																	    			?>
																		    			<tr>
																		    				<td><?php echo $single_property["label"] ?></td>
																	    				</tr>
																	    			<?php
																	    		}
															    			}
															    		?>
														    		</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<?php
											}
									 	}
								 	}	
								?>
							</div>
						<?php
					}
					else {
						?>
						<form action="" method="post">
							<div class="hubwoo-fields-select">
								<p><?php _e( "Properties already marked are required for HubSpot Smart Lists. Please do not remove or uncheck them..","hubwoo") ?></p>
								<div class="hubwoo-actions">
									<a href="javascript:void(0);" class="hubwoo-action-field" id="hubwoo-select-all-fields"><?php _e("Select all")?></a>
									<a href="javascript:void(0);" class="hubwoo-action-field" id="hubwoo-clear-all-fields"><?php _e("Clear")?></a>
								</div>
								<?php
									$hubwoo_groups = $hubwoo->hubwoo_get_final_groups();
									
									if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

										foreach( $hubwoo_groups as $key => $single_group ) {

											if( $single_group['status'] == 'created' ) {

												?>
												<div class="hubwoo_groups">

													<table class="form-table">

														<tbody>

															<tr valign="top">

																<th scope="row" class="titledesc">

																	<p class="hubwoo_group_name">

																		<?php echo $single_group['detail']["displayName"] ?>

																	</p>

																</th>

																<td class="forminp forminp-text">

																	<table>

																    	<?php 

																    		$hubwoo_starterperties = HubWooContactProperties::get_instance()->_get( 'properties', $single_group['detail']["name"] );

																    		$hubwoo_selected_properties = hubwoo::hubwoo_user_selected_fields();

																    		$required_properties = $hubwoo->hubwoo_list_required_properties();
																    	
																	    	if( is_array( $hubwoo_starterperties ) && count( $hubwoo_starterperties ) ) {

																	    		foreach( $hubwoo_starterperties as $single_property ) {

																	    			if( in_array( $single_property["name"], $hubwoo_selected_properties ) ) {
																	    				
																	    				if( in_array( $single_property["name"], $required_properties ) ) {
																		    				?>
																			    			<tr>
																			    				<td><?php echo $single_property["label"] ?></td>
																			    				<td style="text-align:left;" class="hubwoo_tooltip_checkbox_td"><input data-id="<?php echo $key ?>" class="hubwoo_common_checkbox hubwoo_tooltip_checkbox" type="checkbox" checked onclick="return false;" name="selected_properties[]" value="<?php echo $single_property["name"] ?>">
																			    				<div class="hubwoo_tooltip"><span class="hubwoo_tooltip_span"><?php _e("Required Property","hubwoo")?></span></div></td>
																		    				</tr>
																			    			<?php
																			    		}
																			    		else {

																			    			?>
																			    			<tr>
																			    				<td><?php echo $single_property["label"] ?></td>
																			    				<td><input data-id="<?php echo $key ?>" class="hubwoo_select_property hubwoo_common_checkbox" type="checkbox" checked name="selected_properties[]" value="<?php echo $single_property["name"] ?>"></td>
																		    				</tr>
																			    			<?php
																			    		}
																		    		}
																		    		else {

																		    			if( in_array( $single_property["name"], $required_properties ) ) {
																			    			?>

																			    			<tr>
																			    				<td><?php echo $single_property["label"] ?></td>
																			    				<td style="text-align:left;" class="hubwoo_tooltip_checkbox_td"><input data-id="<?php echo $key ?>" class="hubwoo_common_checkbox hubwoo_tooltip_checkbox" type="checkbox" checked onclick="return false;" name="selected_properties[]" value="<?php echo $single_property["name"] ?>">
																			    				<div class="hubwoo_tooltip"><span class="hubwoo_tooltip_span"><?php _e("Required Property","hubwoo")?></span></div></td>
																		    				</tr>

																			    			<?php
																			    		}
																			    		else {

																			    			?>

																			    			<tr>
																			    				<td><?php echo $single_property["label"] ?></td>
																			    				<td><input data-id="<?php echo $key ?>" class="hubwoo_select_property hubwoo_common_checkbox" type="checkbox" name="selected_properties[]" value="<?php echo $single_property["name"] ?>"></td>
																		    				</tr>

																			    			<?php
																			    		}
																		    		}
																    			}
																    		}
															    		?>
														    		</table>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<?php
											}
									 	}
								 	}	
								?>
								<p class="submit">
									<input type="submit" class="button button-primary" name="hubwoo_save_selected_fields" value="<?php echo __("Save Changes","hubwoo") ?>">
								</p>
							</div>
						</form>
						<?php
					}
				?>
					<?php add_thickbox(); ?>
					<div id="hubwoo-setup-process" style="display: none;">
						<div class="popupwrap">
				          <p> <?php _e('We are setting up, Please do not navigate or reload the page before our confirmation message.', 'hubwoo')  ?></p>
					      <div class="progress">
					        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					        </div>
					      </div>
					        <div class="hubwoo-message-area">
					        </div>
					    </div>
				    </div>
				<div class="hubwoo-fields-setup">
					<?php if( hubwoo::is_fields_to_create() ): ?>
						<a class="hubwoo-run-fields-setup" href="javascript:void(0);"><?php _e("Start Setup","hubwoo") ?></a>
					<?php endif;?>
					<a class="hubwoo-run-fields-decision" href="javascript:void(0);"><?php _e("Change Decision","hubwoo") ?></a>
				</div>
			</div>
		</div>
<?php
	} 
else {

	$final_properties = $hubwoo->hubwoo_get_final_fields();
	
	?>
	<div class="hubwoo-fields-created">
		<div class="hubwoo-fields-created-list">
			<p><?php _e("Fields overview to see which are on your HubSpot account. You can create new fields directly from here")?></p>
			<table>
				<thead>
					<tr>
						<th><?php _e('Property Name','hubwoo') ?></th>
						<th><?php _e('Action','hubwoo') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if( is_array( $final_properties ) && count( $final_properties ) ) {

							foreach( $final_properties as $single_property ) {

								if( !empty( $single_property['status'] ) && $single_property['status'] == 'created' ) {
									?>
									<tr>
										<td>
											<?php echo $single_property['label']?>
										</td>
										<td>
											<div class="hubwoo-field-checked">
												<img src="<?php echo HUBWOO_STARTER_URL;?>admin/images/checked.png">
											</div>
										</td>
									</tr>
									<?php
								}
								else {
									?>
									<tr>
										<td>
											<?php echo $single_property['label']?>
										</td>
										<td>
											<a href="javascript:void(0);" class="button button-primary hubwoo-create-single-field" data-group ="<?php echo $single_property['group']?>" data-name="<?php echo $single_property['name'] ?>"><?php echo __('Create','hubwoo') ?></a>
										</td>
									</tr>
									<?php
								}                               
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}
?>