<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           hubspot-woocommerce-integration-starter
 *
 * @wordpress-plugin
 * Plugin Name:       	HubSpot WooCommerce Integration STARTER
 * Plugin URI:        	makewebbetter.com/hubspot-woocommerce-integration-pro
 * Description:       	A very powerful plugin to integrate your WooCommerce store with HubSpot seamlessly.
 * Version:           	2.0.1
 * Requires at least: 	4.4.0
 * Tested up to: 		5.2.0
 * WC requires at least:	3.0.0
 * WC tested up to: 		3.6.2
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           MakeWebBetter License
 * License URI:       https://makewebbetter.com/license-agreement.txt
 * Text Domain:       hubwoo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	
	die;
}

$hubwoo_starter_activated = true;
$hubwoo_starter_flag = 1;

/**
 * Checking if WooCommerce is active
 **/

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	$hubwoo_starter_activated = false;
	$hubwoo_starter_flag = 0;
}
/**
 * Checking if HubSpot WooCommerce Integration other version is activated
 **/
elseif ( in_array( 'hubwoo-integration/hubspot-woocommerce-integration.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'hubspot-woocommerce-integration-pro/hubspot-woocommerce-integration-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'hubspot-woocommerce-integration-complimentary/hubspot-woocommerce-integration-complimetary.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	$hubwoo_starter_activated = false;
	$hubwoo_starter_flag = -1;
}

if ( $hubwoo_starter_activated && $hubwoo_starter_flag ) {

	/**
	 * The code that runs during plugin activation.
	 */
	if( !function_exists( 'activate_hubwoo_starter' ) ) {

		function activate_hubwoo_starter() {   		
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-hubwoo-activator.php';
			Hubwoo_Activator::activate();
		}
	}

	/**
	 * The code that runs during plugin deactivation.
	 */
	if( !function_exists( 'deactivate_hubwoo_starter' ) ) {

		function deactivate_hubwoo_starter() {
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-hubwoo-deactivator.php';
			Hubwoo_Deactivator::deactivate();
		}
	}

	register_activation_hook( __FILE__, 'activate_hubwoo_starter' );
	register_deactivation_hook( __FILE__, 'deactivate_hubwoo_starter' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-hubwoo.php';

	/**
	 * define HubWoo constants.
	 *
	 * @since 1.0.0
	*/
	function hubwoo_starter_define_constants() {

		hubwoo_starter_define( 'HUBWOO_STARTER_ABSPATH', dirname( __FILE__ ) . '/' );
		hubwoo_starter_define( 'HUBWOO_STARTER_URL', plugin_dir_url( __FILE__ ) . '/' );
		hubwoo_starter_define( 'HUBWOO_STARTER_VERSION', '2.0.1' );
		hubwoo_starter_define( 'HUBWOO_STARTER_ACTIVATION_SECRET_KEY', '59f32ad2f20102.74284991' );
		hubwoo_starter_define( 'HUBWOO_STARTER_LICENSE_SERVER_URL', 'https://makewebbetter.com' );
		hubwoo_starter_define( 'HUBWOO_STARTER_ITEM_REFERENCE', 'HubWoo Starter' );
		hubwoo_starter_define( 'HUBWOO_STARTER_CLIENT_ID', '769fa3e6-79b1-412d-b69c-6b8242b2c62a' );
		hubwoo_starter_define( 'HUBWOO_STARTER_SECRET_ID', '2893dd41-017e-4208-962b-12f7495d16b0' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 * @since 1.0.0
	*/
	function hubwoo_starter_define( $name, $value ) {

		if ( ! defined( $name ) ) {
			
			define( $name, $value );
		}
	}

	/**
	 * Setting Page Link
	 * @since    1.0.0
	 * @author  MakeWebBetter
	 * @link  http://makewebbetter.com/
	 */

	function hubwoo_starter_admin_settings( $actions, $plugin_file ) {

		static $plugin;

		if ( !isset( $plugin ) ) {
	
			$plugin = plugin_basename ( __FILE__ );
		}

		if ( $plugin == $plugin_file ) {

			$settings = array (
				'settings' => '<a href="' . admin_url ( 'admin.php?page=hubwoo' ) . '">' . __ ( 'Settings', 'hubwoo' ) . '</a>',
			);

			$actions = array_merge ( $settings, $actions );
		}

		return $actions;
	}
	
	//add link for settings
	add_filter ( 'plugin_action_links','hubwoo_starter_admin_settings', 10, 5 );

	/**
	 * Auto Redirection to settings page after plugin activation
	 * @since    1.0.0
	 * @author  MakeWebBetter
	 * @link  https://makewebbetter.com/
	 */

	function hubwoo_starter_activation_redirect( $plugin ) {

	    if( $plugin == plugin_basename( __FILE__ ) ) {

	        exit( wp_redirect( admin_url( 'admin.php?page=hubwoo' ) ) );
	    }
	}
	//redirect to settings page as soon as plugin is activated
	add_action( 'activated_plugin', 'hubwoo_starter_activation_redirect' );
	
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_hubwoo_starter() {

		//define contants if not defined..
		hubwoo_starter_define_constants();

		$Hubwoo = new Hubwoo();
		$Hubwoo->run();

		$GLOBALS['hubwoo'] = $Hubwoo;

	}
	run_hubwoo_starter();
}
elseif( !$hubwoo_starter_activated && $hubwoo_starter_flag === 0 ) {

 	add_action( 'admin_init', 'hubwoo_starter_plugin_deactivate' );  
 
 	/**
 	 * Call Admin notices
 	 * 
 	 * @author MakeWebBetter<webmaster@makewebbetter.com>
 	 * @link https://www.makewebbetter.com/
 	 */ 	
  	function hubwoo_starter_plugin_deactivate() {

	   deactivate_plugins( plugin_basename( __FILE__ ) );
	   add_action( 'admin_notices', 'hubwoo_starter_plugin_error_notice' );
	}

	/**
	 * Show warning message if woocommerce is not install
	 * @since 1.0.0
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */

	function hubwoo_starter_plugin_error_notice() {

 		?>
 		 <div class="error notice is-dismissible">
 			<p><?php _e( 'WooCommerce is not activated, Please activate WooCommerce first to install HubSpot WooCommerce Integration STARTER.', 'hubwoo' ); ?></p>
   		</div>
   		<style>
   		#message{display:none;}
   		</style>
   		<?php 
 	}
}
elseif( !$hubwoo_starter_activated && $hubwoo_starter_flag === -1 ) {

	/**
	 * Show warning message if HubSpot WooCommerce Integration free version is activated
	 * @since 1.0.0
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */

	function hubwoo_starter_plugin_basic_error_notice() {

 		?>
 		 <div class="error notice is-dismissible">
 			<p><?php _e("Oops! You tried activating the HubSpot WooCommerce Integration STARTER without deactivating the another version of the integration. Kindly deactivate the other version of HubSpot WooCommerce Integration and then try again.", 'hubwoo' ); ?></p>
   		</div>
   		<style>
   		#message{display:none;}
   		</style>
   		<?php 
 	}

 	add_action( 'admin_init', 'hubwoo_starter_plugin_deactivate_dueto_basicversion' );  
 
 	
 	/**
 	 * Call Admin notices
 	 * 
 	 * @author MakeWebBetter<webmaster@makewebbetter.com>
 	 * @link https://www.makewebbetter.com/
 	 */ 	
  	function hubwoo_starter_plugin_deactivate_dueto_basicversion() {

	   deactivate_plugins( plugin_basename( __FILE__ ) );
	   add_action( 'admin_notices', 'hubwoo_starter_plugin_basic_error_notice' );
	}
}

$hubwoo_starter_license_key = get_option( "hubwoo_starter_license_key", "" );
define( 'HUBWOO_STARTER_LICENSE_KEY', $hubwoo_starter_license_key );
define( 'HUBWOO_STARTER_BASE_FILE', __FILE__ );
$hubwoo_starter_update_check = "https://makewebbetter.com/pluginupdates/hubspot-woocommerce-integration-starter/update.php";
require_once( 'mwb-hubwoo-starter-update.php' );