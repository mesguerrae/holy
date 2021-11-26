<?php

global $hubwoo;

if( isset( $_POST[ 'hubwoo_save_selected_lists' ] ) ) {

	unset( $_POST[ 'hubwoo_save_selected_lists' ] );

	if( !isset( $_POST[ 'selected_lists' ] ) ) {

		$_POST[ 'selected_lists' ] = array();
	}

	update_option( 'hubwoo_starter_selected_lists', $_POST[ 'selected_lists' ] );

	$message = __( 'Lists saved successfully.' , 'hubwoo' );

	$hubwoo::hubwoo_notice( $message, 'success' );
}
?>

<div class="hubwoo-fields-header hubwoo-common-header">
	<h2><?php _e("HubSpot Smart Lists","hubwoo") ?></h2>
</div>

<?php
	$user_choice = $hubwoo::hubwoo_user_list_choice();

	if( !$hubwoo->is_list_setup_completed() ) {
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
						<?php _e("Before we start with HubSpot Lists setup, would you like to filter them before creating them? Filtering the lists allows you to select those one which you want to create on your HubSpot account.","hubwoo") ?>
					</p>
					<div class="button_wrap">
						<a href="javascript:void(0);" class="hubwoo_lists_go_with_integration"><?php _e("I would like to go with the extension.",'hubwoo'); ?></a>
						<a href="javascript:void(0);" class="hubwoo_starter_select_lists"><?php _e('Yes, allow me to filter','hubwoo'); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-fields-on-user-choice">
				<?php 

					if( $user_choice == "no" ) {

						?>
							<div class="hubwoo-fields-display">
								
								<?php

									$hubwoo_lists = HubWooContactProperties::get_instance()->_get( 'lists' );

									$list_counter = 0;

									if( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

										foreach( $hubwoo_lists as $key => $single_list ) {

											$list_filter_created = $hubwoo->is_list_filter_created( $single_list['filters'] );

											if( $list_filter_created ) {

												$list_counter += 1;

												?>

												<div class="hubwoo_groups">
													<table class="form-table">
														<tbody>
															<tr valign="top">
																<th scope="row" class="titledesc">
																	<p class="hubwoo_group_name">
																		<?php
																			$list = sprintf( __( 'List %s', 'hubwoo' ), $key + 1 );
																			echo $list;
																		?>
																	</p>
																</th>
																<td class="forminp forminp-text">
																	<table>
															    			<tr>
															    				<td><?php echo $single_list["name"] ?>
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
								 	}

								 	if ( !$list_counter ) {
								 	?>
							 			<div class="hubwoo_segments hubwoo_groups">
							 				<img src="<?php echo HUBWOO_STARTER_URL;?>admin/images/sad-face.png"/>
							 				<p class="hubwoo_no_segments">
							 					<?php _e("Sorry, we haven't found any custom property to use it as filter in our HubSpot Smart Lists. Please try creating more properties like:","hubwoo");?>
							 				</p>
							 				<ul>
							 					<li>
							 						<?php _e("Order Monetary Rating","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Order Recency Rating","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Order Frequency Rating","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Last Order Status","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Last Order Value","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Accepts Marketing","hubwoo")?>
							 					</li>
							 					<li>
							 						<?php _e("Total Number Of Orders","hubwoo")?>
							 					</li>
							 				</ul>
							 			</div>
								 	<?php
								 	}	
								?>
							</div>
						<?php
					}
					else { 
						?>
						<form action="" method="post">
							<div class="hubwoo-fields-select">
								<?php
									$hubwoo_lists = HubWooContactProperties::get_instance()->_get( 'lists' );
									$hubwoo_selected_lists = $hubwoo::hubwoo_user_selected_lists();

									$list_counter = 0;

									if( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

										foreach( $hubwoo_lists as $key => $single_list ) {

											$list_filter_created = $hubwoo->is_list_filter_created( $single_list['filters'] );

											if( $list_filter_created ) {

												$list_counter += 1;

												?>
												<div class="hubwoo_groups">

													<table class="form-table">

														<tbody>

															<tr valign="top">

																<th scope="row" class="titledesc">

																	<p class="hubwoo_group_name">

																		<?php 
																			$list = sprintf( __( 'List %s', 'hubwoo' ), $key + 1 );
																			echo $list;
																		?>
																	</p>
																</th>

																<td class="forminp forminp-text">

																	<table>

																    	<?php 

																	    	if( in_array( $single_list["name"], $hubwoo_selected_lists ) )
																	    	{
																	    			
															    				?>
																	    			<tr>
																	    				<td><?php echo $single_list["name"] ?></td>
																	    				<td><input data-id="<?php echo $key ?>" class="hubwoo_select_list" type="checkbox" checked name="selected_lists[]" value="<?php echo $single_list["name"] ?>"></td>
																    				</tr>
																    			<?php
																		    }
																    		else {

																    			?>

																	    			<tr>
																	    				<td><?php echo $single_list["name"] ?></td>
																	    				<td><input data-id="<?php echo $key ?>" class="hubwoo_select_list" type="checkbox" name="selected_lists[]" value="<?php echo $single_list["name"] ?>"></td>
																    				</tr>
																    			<?php
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

									if ( !$list_counter ) {
									 	?>
								 			<div class="hubwoo_segments hubwoo_groups">
								 				<img src="<?php echo HUBWOO_STARTER_URL;?>admin/images/sad-face.png"/>
								 				<p class="hubwoo_no_segments">
								 					<?php _e("Sorry, we haven't found any custom property to use it as filter in our HubSpot Smart Lists. Please try creating more properties like:","hubwoo");?>
								 				</p>
								 				<ul>
								 					<li>
								 						<?php _e("Order Monetary Rating","hubwoo")?>
								 					</li>
								 					<li>
								 						<?php _e("Order Recency Rating","hubwoo")?>
								 					</li>
								 					<li>
								 						<?php _e("Order Frequency Rating","hubwoo")?>
								 					</li>
								 					<li>
								 						<?php _e("Last Order Status","hubwoo")?>
								 					</li>
								 					<li>
								 						<?php _e("Last Order Value","hubwoo")?>
								 					</li>
								 					<li>
								 						<?php _e("Accepts Marketing","hubwoo")?>
								 					</li>
								 				</ul>
								 			</div>
									 	<?php
									 }
								?>
								<?php if( $list_counter ):?>
									<p class="submit">
										<input type="submit" class="button button-primary" name="hubwoo_save_selected_lists" value="<?php echo __("Save Changes","hubwoo") ?>">
									</p>
								<?php endif; ?>
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
					<?php if( $hubwoo->is_lists_to_create() ): ?>
						<a class="hubwoo-run-lists-setup" href="javascript:void(0);"><?php _e("Start Setup","hubwoo") ?></a>
					<?php endif;?>
					<a class="hubwoo-change-list-decision" href="javascript:void(0);"><?php _e("Change Decision","hubwoo") ?></a>
				</div>
			</div>
		</div>
<?php
	} 
else {

	$final_lists = $hubwoo->hubwoo_get_final_lists();
	
	?>
	<div class="hubwoo-fields-created">
		<div class="hubwoo-fields-created-list">
			<p><?php _e("Groups overview to see which are on your HubSpot account. You can create new group directly from here","hubwoo")?></p>
			<table>
				<thead>
					<tr>
						<th><?php _e('List Name','hubwoo') ?></th>
						<th><?php _e('Action','hubwoo') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if( is_array( $final_lists ) && count( $final_lists ) ) {

							foreach( $final_lists as $single_list ) {

								if( isset( $single_list['detail'] ) && !empty( $single_list['status'] ) && $single_list['status'] == 'created' ) {
									?>
									<tr>
										<td>
											<?php echo $single_list['detail']['name']?>
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
											<?php echo $single_list['detail']['name']?>
										</td>
										<td>
											<a href="javascript:void(0);" class="button button-primary hubwoo-create-single-list" data-name="<?php echo $single_list['detail']['name'] ?>"><?php echo __('Create','hubwoo') ?></a>
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