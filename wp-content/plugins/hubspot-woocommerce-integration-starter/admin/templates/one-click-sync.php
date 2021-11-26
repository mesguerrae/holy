
<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-pro
 * @subpackage hubspot-woocommerce-integration-pro/admin/partials
 */
?>
<?php

global $hubwoo;

$ocs_active_tab = isset( $_GET['hubwoo_ocs_tab'] ) ? $_GET['hubwoo_ocs_tab'] : 'hubwoo_customer_ocs';

?>

<div class="hubwoo-ocs-tabs">
	<?php
		$ocs_default_tabs = array( "hubwoo_customer_ocs" => __("Registered Customers", "hubwoo"), "hubwoo_orders_ocs" => __("Guest Orders (as contacts)", "hubwoo") );

		if( is_array( $ocs_default_tabs ) && count( $ocs_default_tabs ) ) {

			foreach( $ocs_default_tabs as $ocs_tab_key => $ocs_single_tab ) {

				$ocs_tab_classes = "hubwoo-nav-tab ";
				
				if( !empty( $ocs_active_tab ) && $ocs_active_tab == $ocs_tab_key ) {
					
					$ocs_tab_classes .= "nav-tab-active";
				}
				?>
					<div class="hubwoo-new-tabs"><a class="<?php echo $ocs_tab_classes; ?>" id="<?php echo $ocs_tab_key; ?>" href="<?php echo admin_url('admin.php').'?page=hubwoo&hubwoo_tab=one-click-sync&hubwoo_ocs_tab='.$ocs_tab_key; ?>"><?php echo $ocs_single_tab; ?></a></div>
				<?php
			}
		}
	?>
</div>

<div class="hubwoo-ocs-template">
	<?php
		// if submenu is directly clicked on woocommerce.
		if( empty( $ocs_active_tab ) ){

			$ocs_active_tab = "hubwoo_customer_ocs";
		}
		
		// look for the path based on the tab id in the admin templates.
		$tab_content_path = 'admin/templates/'.$ocs_active_tab.'.php';

		$hubwoo->load_template_view( $tab_content_path );
	?>
</div>