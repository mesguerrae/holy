<?php

global $hubwoo;

if( isset( $_POST[ 'hubwoo_save_selected_groups' ] ) ) {

	unset( $_POST[ 'hubwoo_save_selected_groups' ] );

	if( !isset( $_POST[ 'selected_groups' ] ) ) {

		$_POST[ 'selected_groups' ] = array();
	}

	update_option( 'hubwoo_starter_selected_groups', $_POST[ 'selected_groups' ] );

	$message = __( 'Groups saved successfully.' , 'hubwoo' );

	$hubwoo::hubwoo_notice( $message, 'success' );
}
?>

<div class="hubwoo-fields-header hubwoo-common-header">
	<h2><?php _e("HubSpot Custom Groups","hubwoo") ?></h2>
</div>

<?php
	$user_choice = $hubwoo::hubwoo_user_group_choice();

	if( !$hubwoo->is_group_setup_completed() ) {
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
						<?php _e("Before we start with HubSpot Groups setup, would you like to filter the groups before creating them? Filtering the groups allows you to select those one which you want to create on your HubSpot account.","hubwoo") ?>
					</p>
					<div class="button_wrap">
						<a href="javascript:void(0);" class="hubwoo_groups_go_with_integration"><?php _e( "I would like to go with the extension.", 'hubwoo' ); ?></a>
						<a href="javascript:void(0);" class="hubwoo_starter_select_groups"><?php _e( 'Yes, allow me to filter', 'hubwoo' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-fields-on-user-choice">
				<?php 

					if( $user_choice == "no" ) {

						?>
							<div class="hubwoo-fields-display">
								<p><?php _e( "These are the Custom Groups which will be created by the extension on your HubSpot account or if you want, can change your decision.","hubwoo") ?></p>
								<?php

									$hubwoo_groups = HubWooContactProperties::get_instance()->_get( 'groups' );

									if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

										foreach( $hubwoo_groups as $key => $single_group ) {
											?>

											<div class="hubwoo_groups">
												<table class="form-table">
													<tbody>
														<tr valign="top">
															<th scope="row" class="titledesc">
																<p class="hubwoo_group_name">
																	<?php
																		$group = sprintf( __( 'Group %s', 'hubwoo' ), $key + 1 );
																		echo $group;
																	?>
																</p>
															</th>
															<td class="forminp forminp-text">
																<table>
														    			<tr>
														    				<td><?php echo $single_group["displayName"] ?>
														    				</td>
													    				</tr>		
													    		</table>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<?php
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
								<p><?php _e( "Groups already marked are required for some custom properties. Please do not remove or uncheck them.","hubwoo") ?></p>
								<div class="hubwoo-actions">
									<a href="javascript:void(0);" class="hubwoo-action-field" id="hubwoo-select-all-groups"><?php _e("Select all","hubwoo")?></a>
									<a href="javascript:void(0);" class="hubwoo-action-field" id="hubwoo-clear-all-groups"><?php _e("Clear","hubwoo")?></a>
								</div>
								<?php
									$hubwoo_groups = HubWooContactProperties::get_instance()->_get( 'groups' );
									
									if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

										foreach( $hubwoo_groups as $key => $single_group ) {

											?>
											<div class="hubwoo_groups">

												<table class="form-table">

													<tbody>

														<tr valign="top">

															<th scope="row" class="titledesc">

																<p class="hubwoo_group_name">

																	<?php 
																		$group = sprintf( __( 'Group %s', 'hubwoo' ), $key + 1 );
																		echo $group;
																	?>
																</p>
															</th>

															<td class="forminp forminp-text">

																<table>

															    	<?php 

																    	$hubwoo_selected_groups = $hubwoo::hubwoo_user_selected_groups();

																    	$required_groups = $hubwoo->hubwoo_list_required_groups();

																    	if( in_array( $single_group["name"], $hubwoo_selected_groups ) ) {

																    		if( in_array( $single_group["name"], $required_groups ) ) { 
																    			
															    				?>
																	    			<tr>
																	    				<td><?php echo $single_group["displayName"] ?></td>
																	    				<td class="hubwoo_tooltip_checkbox_td"><input data-id="<?php echo $key ?>" class="hubwoo_select_group hubwoo_tooltip_checkbox" type="checkbox" checked onclick="return false;" name="selected_groups[]" value="<?php echo $single_group["name"] ?>">
																	    				<div class="hubwoo_tooltip"><span class="hubwoo_tooltip_span"><?php _e("Required Group","hubwoo")?></span></div>
																	    				</td>
																    				</tr>
																    			<?php
																    		}
																    		else {

																    			?>
																	    			<tr>
																	    				<td><?php echo $single_group["displayName"] ?></td>
																	    				<td style="text-align:center;"><input data-id="<?php echo $key ?>" class="hubwoo_select_group " type="checkbox" checked name="selected_groups[]" value="<?php echo $single_group["name"] ?>"></td>
																    				</tr>
																    			<?php
																    		}
																	    }
															    		else {

															    			if( in_array( $single_group["name"], $required_groups ) ) {
																    			?>
																	    			<tr>
																	    				<td><?php echo $single_group["displayName"] ?></td>
																	    				<td class="hubwoo_tooltip_checkbox_td"><input data-id="<?php echo $key ?>" class="hubwoo_tooltip_checkbox" type="checkbox" checked onclick = "return false;" name="selected_groups[]" value="<?php echo $single_group["name"] ?>"><div class="hubwoo_tooltip"><span class="hubwoo_tooltip_span"><?php _e("Required Group","hubwoo")?></span></div></td>
																    				</tr>
																    			<?php
																    		}
																    		else {

																    			?>

																	    			<tr>
																	    				<td><?php echo $single_group["displayName"] ?></td>
																	    				<td style="text-align:center;"><input data-id="<?php echo $key ?>" class="hubwoo_select_group " type="checkbox" name="selected_groups[]" value="<?php echo $single_group["name"] ?>"></td>
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
								?>
								<p class="submit">
									<input type="submit" class="button button-primary" name="hubwoo_save_selected_groups" value="<?php echo __("Save Changes","hubwoo") ?>">
								</p>
							</div>
						</form>
						<?php
					}
				?>
					<?php add_thickbox(); ?>
					<div id="hubwoo-setup-process" style="display: none;">
						<div class="popupwrap">
				          <p> <?php _e('We are setting up custom groups for contacts on HubSpot, Please do not navigate or reload the page before our confirmation message.', 'hubwoo')  ?></p>
					      <div class="progress">
					        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					        </div>
					      </div>
					        <div class="hubwoo-message-area">
					        </div>
					    </div> 
				    </div>
				<div class="hubwoo-fields-setup">
					<?php if( $hubwoo->is_groups_to_create() ): ?>
						<a class="hubwoo-run-groups-setup" href="javascript:void(0);"><?php _e("Start Setup","hubwoo") ?></a>
					<?php endif;?>
					<a class="hubwoo-change-group-decision" href="javascript:void(0);"><?php _e("Change Decision","hubwoo") ?></a>
				</div>
			</div>
		</div>
<?php
	} 
else {

	$final_groups = $hubwoo->hubwoo_get_final_groups();
	
	?>
	<div class="hubwoo-fields-created">
		<div class="hubwoo-fields-created-list">
			<p><?php _e("Groups overview to see which are on your HubSpot account. You can create new group directly from here","hubwoo")?></p>
			<table>
				<thead>
					<tr>
						<th><?php _e('Group Name','hubwoo') ?></th>
						<th><?php _e('Action','hubwoo') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if( is_array( $final_groups ) && count( $final_groups ) ) {

							foreach( $final_groups as $single_group ) {

								if( isset( $single_group['detail'] ) && !empty( $single_group['status'] ) && $single_group['status'] == 'created' ) {
									?>
									<tr>
										<td>
											<?php echo $single_group['detail']['displayName']?>
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
											<?php echo $single_group['detail']['displayName']?>
										</td>
										<td>
											<a href="javascript:void(0);" class="button button-primary hubwoo-create-single-group" data-name="<?php echo $single_group['detail']['name'] ?>"><?php echo __('Create') ?></a>
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