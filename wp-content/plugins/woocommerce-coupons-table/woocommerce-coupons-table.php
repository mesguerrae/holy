<?php
/*
Plugin Name: Coupons Table
Plugin URI: https://akismet.com/
Description: coupons table for marketing people
Version: 1.0
Author: Automattic
License: GPLv2 or later
Text Domain: Nicolas
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'admin-table.php';


#MENU LOGIC
add_action( 'plugins_loaded', 'new_menu_item' );

function new_menu_item() {
    add_action( 'admin_menu', 'plugin_menu' , 99);
}


function plugin_menu() 
{
    add_submenu_page(
        'woocommerce', 
        'Order Coupons',
        'Order Coupons',
        'manage_options',
        'order-with-coupons', 
        'order_coupons_table'
    );
}


function order_coupons_table() {

    ?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Orders with coupon', 'admin-table-tut' ); ?></h2>
		<?php

            $wp_list_table = new WC_Admin_List_Orders_Table_Orders();
            $wp_list_table->prepare_items();
            $wp_list_table->display();
		?>
	</div>
	<?php
   
}


#PROFILE LOGIC

add_action( 'show_user_profile', 'crf_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'crf_show_extra_profile_fields' );

function crf_show_extra_profile_fields( $user ) {
	$current_coupon_id = get_the_author_meta( 'selected_admin_coupon', $user->ID );
	$selected_coupons = explode(',', $current_coupon_id);
    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'title',
        'order'            => 'asc',
        'post_type'        => 'shop_coupon'
    );
        
    $coupons = get_posts( $args );

	?>
	<h3><?php esc_html_e( 'Coupon', 'crf' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="selected_admin_coupon"><?php esc_html_e( 'Admin Coupon', 'crf' ); ?></label></th>
			<td>
                <select name="selected_admin_coupon[]" id="selected_admin_coupon" multiple size="30">
                    <?php foreach($coupons as $coupon): ?>
                        <option value="<?= $coupon->ID ?>" <?php if(in_array($coupon->ID, $selected_coupons)) echo "selected"; ?>><?= $coupon->post_title ?></option>
                
                    <?php endforeach; ?>
                 
                </select>
			</td>
		</tr>
	</table>

	<?php
}



add_action( 'personal_options_update', 'crf_update_profile_fields' );
add_action( 'edit_user_profile_update', 'crf_update_profile_fields' );

function crf_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	echo "<pre>";
	if ( ! empty( $_POST['selected_admin_coupon'] )  ) {
		update_user_meta( $user_id, 'selected_admin_coupon', implode( ',' ,$_POST['selected_admin_coupon'] ) );
	}
}


#REMOVE DASHBOARD WOOCOMMERCE STATUS
function remove_dashboard_widgets() {
 	remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal');    
}
add_action('wp_user_dashboard_setup', 'remove_dashboard_widgets', 20);
add_action('wp_dashboard_setup', 'remove_dashboard_widgets', 20);


#ADD STYLES
add_action( 'admin_enqueue_scripts', 'load_admin_styles' );
function load_admin_styles() {

	wp_enqueue_style( 'admin_css_coupon_table', plugins_url().'/woocommerce-coupons-table/assets/css/style.css', false, '1.1' );
	wp_enqueue_script( 'admin_js_coupon_table', plugins_url().'/woocommerce-coupons-table/assets/js/script.js', false, '1.1' );

} 

//add the needed scripts and styles
add_action('admin_enqueue_scripts', 'wpse_46028_enqueue_admin_scripts');
function wpse_46028_enqueue_admin_scripts() {
    wp_enqueue_style('wp-pointer');
    wp_enqueue_script('wp-pointer');

}

