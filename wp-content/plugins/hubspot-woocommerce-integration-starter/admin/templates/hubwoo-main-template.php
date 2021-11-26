<?php

if ( ! defined ( 'ABSPATH' ) ) {

	exit (); // Exit if accessed directly
}
?>

<?php
	global $hubwoo;
	$callname_lcne = $hubwoo::$lcne_callback_function;
	$active_tab = isset( $_GET['hubwoo_tab'] ) ? $_GET['hubwoo_tab'] : 'hubwoo_overview';
	$default_tabs = $hubwoo->hubwoo_default_tabs();
?>

<div class="hubwoo-main-template">
	<div class="hubwoo-header-template">
		<div class="hubwoo-hubspot-icon">
			<img width="90px" height="90px" src="<?php echo HUBWOO_STARTER_URL . 'admin/images/hubspot-icon.png' ?>" class=""/>
		</div>
		<div class="hubwoo-header-text">
			<h2><?php _e( "HubSpot WooCommerce Integration Starter", "hubwoo" ) ?></h2>
		</div>
		<div class="hubwoo-woo-icon">
			<img width="90px" height="90px" src="<?php echo HUBWOO_STARTER_URL . 'admin/images/woo-icon.png' ?>" class=""/>
		</div>
	</div>
	<div class="hubwoo-body-template">
		<div class="hubwoo-navigator-template">
			<div class="hubwoo-navigations">
				<?php
					if( is_array( $default_tabs ) && count( $default_tabs ) ) {

						foreach( $default_tabs as $tab_key => $single_tab ) {

							$tab_classes = "hubwoo-nav-tab ";

							$dependency = $single_tab["dependency"];
							
							if( !empty( $active_tab ) && $active_tab == $tab_key ) {
								
								$tab_classes .= "nav-tab-active";
							}

							if( !empty( $dependency ) && !$hubwoo->check_dependencies( $dependency ) ) {

								$tab_classes .= "hubwoo-tab-disabled";
								?>
									<div class="hubwoo-tabs"><a class="<?php echo $tab_classes; ?>" id="<?php echo $tab_key; ?>" href="javascript:void(0);"><?php echo $single_tab["name"]; ?></a></div>
								<?php
							}
							else {
								?>
									<div class="hubwoo-tabs"><a class="<?php echo $tab_classes; ?>" id="<?php echo $tab_key; ?>" href="<?php echo admin_url('admin.php?page=hubwoo').'&hubwoo_tab='.$tab_key; ?>"><?php echo $single_tab["name"]; ?></a></div>
								<?php
							}
						}
					}
				?>
			</div>
		</div>
		<div class="hubwoo-content-template">
			<div class="hubwoo-content-container">
				<?php
					// if submenu is directly clicked on woocommerce.
					if( empty( $active_tab ) ){

						$active_tab = "hubwoo_overview";
					}
					
					// look for the path based on the tab id in the admin templates.
					$tab_content_path = 'admin/templates/'.$active_tab.'.php';
					$license_path = 'admin/templates/hubwoo_license.php';

					if( Hubwoo::$callname_lcne() ) {

						$hubwoo->load_template_view( $tab_content_path );
					}
					else {

						$hubwoo->load_template_view( $license_path );
					}
				?>
			</div>
		</div>
	</div>
	<div style="display: none;" class="loading-style-bg" id="hubwoo_loader">
		<img src="<?php echo HUBWOO_STARTER_URL;?>admin/images/loader.gif">
	</div>
</div>