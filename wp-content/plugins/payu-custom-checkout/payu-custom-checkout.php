<?php
/*
Plugin Name:            WooCommerce Payu Custom checkout
Description:            WooCommerce Payu Custom checkout

Author:					Nicolas Lopez

Version:				0.0.1

License: GPLv2 or later
*/
add_action('plugins_loaded', 'load_plugin');

function load_plugin()
	{
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;
	if (!class_exists('WC_Payment_Gateway'))
		{
		return;
		}

	define('PAYU_PLUGIN_ROOT', ABSPATH . 'lib/');
	function wpse_autoload_payu()
		{
		include_once (PAYU_PLUGIN_ROOT . 'PayU/PayU.php');

		}

	// register function for autoloading required classes

	spl_autoload_register('wpse_autoload_payu');
	include_once (dirname(__FILE__) . '/' . 'includes/helper.php');

	$methods = glob(dirname(__FILE__) . '/' . 'includes/methods/*.php');
	$frontend = glob(dirname(__FILE__) . '/' . 'includes/frontend/*.php');
	$importClass = array_merge($methods, $frontend);
	foreach($importClass as $filename)
		{
		include_once ($filename);

		}

	$methods = dirname(__FILE__) . '/' . 'includes/frontend/*.php';
	foreach(glob($methods) as $filename)
		{
		include_once ($filename);

		}

	function wc_offline_add_to_gateways($gateways)
		{
		$gateways[] = 'WC_PayU_Tc_Payment_Gateway';
		$gateways[] = 'WC_PayU_Efectivo_Payment_Gateway';
		$gateways[] = 'WC_PayU_Trasgerencia_Bancaria_Payment_Gateway';
		return $gateways;
		}

	add_filter('woocommerce_payment_gateways', 'wc_offline_add_to_gateways');
	//remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
	//add_action('woocommerce_after_order_notes', 'woocommerce_checkout_payment', 20);
	}

add_filter('manage_edit-shop_order_columns', 'add_payment_method_column', 20);

function add_payment_method_column($columns)
	{
	$new_columns = array();
	foreach($columns as $column_name => $column_info)
		{
		$new_columns[$column_name] = $column_info;
		if ('order_total' === $column_name)
			{
			$new_columns['order_payment'] = __('Metodo de pago', 'my-textdomain');
			}
		}

	return $new_columns;
	}

add_action('manage_shop_order_posts_custom_column', 'add_payment_method_column_content');

function add_payment_method_column_content($column)
	{
	global $post;
	if ('order_payment' === $column)
		{
		$order = wc_get_order($post->ID);
		echo $order->payment_method_title;
		}
	}



/*add_filter('woocommerce_email_enabled_new_order', function($enabled, $order) {

    if ($order instanceof WC_Order) {
        if ($order->get_status() !=  'processing') {
            return false;
        }

    }

    return $enabled;

}, 10, 2);*/


add_filter( 'wc_order_statuses', 'wc_renaming_order_status' );
function wc_renaming_order_status( $order_statuses ) {
    foreach ( $order_statuses as $key => $status ) {
        if ( 'wc-processing' === $key ) 
            $order_statuses['wc-processing'] = _x( 'Pagado', 'Order status', 'woocommerce' );
    }
    return $order_statuses;
}

 


/**
 * Filter the cart template path to use our payment.php template instead of the theme's
 */
function custom_payment_method_style( $template, $template_name, $template_path ) {

	$basename = basename( $template );

	if (is_checkout()) {
		
		if( $basename == 'payment-method.php' ) {
			$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/templates/payment-method.php';
		}
		
		if( $basename == 'payment.php' ) {
			$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/templates/payment.php';
		}

		if( $basename == 'review-order.php' ) {
			$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/templates/review-order.php';
		}
	}
	
	return $template;
   }
   add_filter( 'woocommerce_locate_template', 'custom_payment_method_style', 10, 3 );



function pietergoosen_theme_setup() {
  register_nav_menus( array( 
    'checkout-footer' => 'Checkout footer', 

  ) );
 }

add_action( 'after_setup_theme', 'pietergoosen_theme_setup' );


add_filter( 'woocommerce_reports_order_statuses', 'my_custom_order_status_for_reports', 10, 1 );
function my_custom_order_status_for_reports($order_statuses){
    $order_statuses = array('processing','completed'); // your order statuses for reports

    return $order_statuses;
}