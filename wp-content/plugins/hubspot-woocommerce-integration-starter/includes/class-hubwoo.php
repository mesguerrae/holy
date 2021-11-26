<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Hubwoo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hubwoo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'HUBWOO_STARTER_VERSION' ) ) {

			$this->version = HUBWOO_STARTER_VERSION;
		}
		else {

			$this->version = '2.0.1';
		}

		$this->plugin_name = 'hubwoo-starter';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hubwoo_Loader. Orchestrates the hooks of the plugin.
	 * - Hubwoo_i18n. Defines internationalization functionality.
	 * - Hubwoo_Admin. Defines all hooks for the admin area.
	 * - Hubwoo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hubwoo-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hubwoo-public.php';

		$this->loader = new Hubwoo_Loader();

		/**
		 * The class responsible for all api actions with hubspot.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-connection-manager.php';

		/**
		 * The class contains all the information related to customer groups and properties.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-contact-properties.php';

		/**
		 * The class contains are readymade contact details to send it to 
		 * hubspot.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-customer.php';

		/**
		 * The class responsible for property values.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-property-callbacks.php';

		/**
		 * The class responsible for handling ajax requests.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-ajax-handler.php';

		/**
		 * The class responsible for rfm configuration settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-rfm-settings.php';

		/**
		 * The class responsible for manging guest orders.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubwoo-guest-orders-manager.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hubwoo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hubwoo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	private function define_admin_hooks() {

		$plugin_admin = new Hubwoo_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'hubwoo_redirect_from_hubspot' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'hubwoo_starter_add_privacy_message' );
		$this->loader->add_action( 'hubwoo_starter_check_licence_daily', $plugin_admin, 'hubwoo_starter_check_licence_daily' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'hubwoo_set_cron_schedule_time' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'hubwoo_starter_reauthorize' );
		
		$callname_lcne = Hubwoo::$lcne_callback_function;
		
		if ( Hubwoo::$callname_lcne() ) {

			if ( $this->is_plugin_enable() == 'yes' ) {

				if( $this->is_setup_completed() ) {

					$order_list_actions = get_option( "hubwoo_list_enrollment_order_actions", array() );

					if( !empty( $order_list_actions ) ) {

						foreach( $order_list_actions as $key => $single_list_action ) {

							$this->loader->add_action( $single_list_action, $plugin_admin, 'hubwoo_order_status_list_enrollment' );
						}
					}

					$this->loader->add_action( 'hubwoo_starter_cron_schedule', $plugin_admin, 'hubwoo_starter_cron_schedule' );
					$this->loader->add_action( 'admin_notices', $plugin_admin, 'hubwoo_cron_notification' );
					$this->loader->add_action( 'hubwoo_starter_check_realtime_cron', $plugin_admin, 'hubwoo_starter_check_realtime_cron' );
					$this->loader->add_action( 'admin_notices', $plugin_admin, 'hubwoo_dashboard_alert_notice' );
					$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_admin, 'hubwoo_update_order_changes' );
					$this->loader->add_action( 'woocommerce_update_order', $plugin_admin, 'hubwoo_update_order_changes' );
					$this->loader->add_action( 'woocommerce_process_shop_order_meta', $plugin_admin, 'hubwoo_update_order_changes' );
					$this->loader->add_action( 'set_user_role', $plugin_admin, 'hubwoo_add_user_toUpdate', 10, 3 );
				}
		
				$this->loader->add_action( 'admin_init', $plugin_admin, 'hubwoo_update_new_addons_groups_properties' );

				if( $this->hubwoo_subs_active() ) {

					$this->loader->add_filter( 'hubwoo_contact_groups', $plugin_admin, 'hubwoo_subs_groups' );
					$this->loader->add_filter( 'hubwoo_active_groups', $plugin_admin, 'hubwoo_active_subs_groups' );
				}
			}
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	
	private function define_public_hooks() {

		$plugin_public = new Hubwoo_Public( $this->get_plugin_name(), $this->get_version() );

		$callname_lcne = Hubwoo::$lcne_callback_function;

		if ( Hubwoo::$callname_lcne() ) {
			if ( $this->is_plugin_enable() == 'yes' ) {
				$customer_list_actions = get_option( "hubwoo_list_enrollment_customer_actions", array() );
				if( !empty( $customer_list_actions ) ) {
					foreach( $customer_list_actions as $key => $single_action ) {
						$this->loader->add_action( $single_action, $plugin_public, 'hubwoo_customer_activity_list_enrollment' );
					}
				}
				$this->loader->add_action( 'profile_update', $plugin_public, 'hubwoo_woocommerce_save_account_details' );
				$this->loader->add_action( 'user_register', $plugin_public, 'hubwoo_woocommerce_save_account_details' );
				$this->loader->add_action( 'woocommerce_customer_save_address', $plugin_public, 'hubwoo_woocommerce_save_account_details' );
				$this->loader->add_action( 'woocommerce_checkout_update_user_meta', $plugin_public, 'hubwoo_woocommerce_save_account_details' ); 
				$guest_sync_enable = get_option( "hubwoo_starter_guest_sync_enable", "yes" );
				if ( "yes" == $guest_sync_enable ) {
					$this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'hubwoo_starter_woocommerce_guest_orders' );
				}
				if ( "yes" == get_option( "hubwoo_checkout_optin_enable", "no" ) ) {
					$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_public, 'hubwoo_starter_checkout_field' );
					$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_public, 'hubwoo_starter_process_checkout_optin' );
				}
				if ( "yes" == get_option( "hubwoo_registeration_optin_enable", "no" ) ) {
					$this->loader->add_action( 'woocommerce_register_form', $plugin_public, 'hubwoo_starter_register_field' );
					$this->loader->add_action( 'woocommerce_created_customer', $plugin_public, 'hubwoo_save_register_optin' );
				}
				$subs_enable = get_option( 'hubwoo_starter_subs_settings_enable', 'yes' );
				if( $subs_enable == 'yes' && $this->is_setup_completed() && $this->hubwoo_subs_active() ) {
					$this->loader->add_action( 'woocommerce_renewal_order_payment_complete', $plugin_public, 'hubwoo_starter_save_renewal_orders' );
					$this->loader->add_action( 'woocommerce_scheduled_subscription_payment', $plugin_public, 'hubwoo_starter_save_renewal_orders' );
					$this->loader->add_action( 'woocommerce_subscription_renewal_payment_complete', $plugin_public, 'hubwoo_starter_update_subs_changes' );
					$this->loader->add_action( 'woocommerce_subscription_payment_failed', $plugin_public, 'hubwoo_starter_update_subs_changes' );
					$this->loader->add_action( 'woocommerce_subscription_renewal_payment_failed', $plugin_public, 'hubwoo_starter_update_subs_changes' );
					$this->loader->add_action( 'woocommerce_subscription_payment_complete', $plugin_public, 'hubwoo_starter_update_subs_changes' );
					$this->loader->add_action( 'woocommerce_subscription_status_updated', $plugin_public, 'hubwoo_starter_update_subs_changes' );
					$this->loader->add_action( 'woocommerce_customer_changed_subscription_to_cancelled', $plugin_public, 'hubwoo_save_changes_in_subs' );
					$this->loader->add_action( 'woocommerce_customer_changed_subscription_to_active', $plugin_public, 'hubwoo_save_changes_in_subs' );
					$this->loader->add_action( 'woocommerce_customer_changed_subscription_to_on-hold', $plugin_public, 'hubwoo_save_changes_in_subs' );
					$this->loader->add_action( 'init', $plugin_public, 'hubwoo_subscription_switch' );
				}
			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hubwoo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static $lcne_callback_function = 'hubwoo_check_key_validity';

	/**
	 * predefined default hubwoo tabs.
	 * @since     1.0.0
	 * @return 	Array 		An key=>value pair of hubspot tabs.
	 */
	public function hubwoo_default_tabs() {

		$default_tabs = array();

		$default_tabs['hubwoo_overview'] 		= array( "name" => __( 'Overview', 'hubwoo' ), "dependency" => array() );
		$default_tabs['hubwoo_connect'] 		= array( "name" => __( 'Connect', 'hubwoo' ), "dependency" => array( "hubwoo_starter_get_started" ) );
		$default_tabs['hubwoo_groups'] 			= array( "name" => __( 'Custom Groups', 'hubwoo' ), "dependency" => array( "hubwoo_starter_get_started", "is_oauth_success", "is_valid_client_ids_stored" ) );
		$default_tabs['hubwoo_properties'] 		= array( "name" => __( 'Custom Properties', 'hubwoo' ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_group_setup_completed" ) );
		$default_tabs['hubwoo_lists'] 			= array( "name" => __( "Smart Lists", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );
		$default_tabs['one-click-sync'] 		= array( "name" => __( "One-Click Sync", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );
		$default_tabs['hubwoo_rfm_settings'] 	= array( "name" => __( "RFM Settings", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );
		$default_tabs['general-settings'] 		= array( "name" => __( "General Settings", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );
		$default_tabs['advanced-settings'] 		= array( "name" => __( "Advanced Settings", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );
		$default_tabs['error-management'] 		= array( "name" => __( "Error Tracking", "hubwoo" ), "dependency" => array( "is_oauth_success", "is_valid_client_ids_stored", "is_field_setup_completed" ) );

		return $default_tabs;
	}


	/**
	 * checking dependencies for tabs
	 * @since     1.0.0
	 */
	public function check_dependencies( $dependency = array() ) {

		$flag = true;

		global $hubwoo;

		if( count( $dependency ) ) {

			foreach( $dependency as $single_dependency ) {

				$flag &= $hubwoo->$single_dependency();
			}
		}

		return $flag;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since 	1.0.0
	 */

	public function load_template_view( $path, $params=array() ) {

		$file_path = HUBWOO_STARTER_ABSPATH.$path;

		if( file_exists( $file_path ) ) {

			include $file_path;
		}
		else {

			$notice = sprintf( __( 'Unable to locate file path at location "%s" some features may not work properly in HubSpot WooCommerce Integration Starter, please contact us!', 'hubwoo' ) , $file_path );

			$this->hubwoo_notice( $notice, 'error' );
		}
	}

	/**
	 * show admin notices.
	 * @param  string 	$message 	Message to display.
	 * @param  string 	$type    	notice type, accepted values - error/update/update-nag
	 * @since  1.0.0
	 */

	public static function hubwoo_notice( $message, $type='error' ) {

		$classes = "notice ";

		switch( $type ) {

			case 'update':
				$classes .= "updated";
				break;

			case 'update-nag':
				$classes .= "update-nag";
				break;

			case 'success':
				$classes .= "notice-success is-dismissible";
				break;

			default:
				$classes .= "error";
		} 

		$notice = '<div style="margin:10px" class="'. $classes .'">';
		$notice .= '<p>'. $message .'</p>';
		$notice .= '</div>';

		echo $notice;	
	}

	/**
	 * check if access token is expired.
	 * @since     1.0.0
	 * @return boolean true/false
	 */

	public static function is_access_token_expired() {

		$get_expiry = get_option( 'hubwoo_starter_token_expiry', false );
		
		if( $get_expiry ) {

			$current_time = time();

			if( $current_time > $get_expiry ) {

				return true;
			}
		}

		return false;
	}

	/**
	 * check if valid hubspot client Ids is stored.
	 * @since  1.0.0 
	 * @return boolean true/false
	 */

	public static function is_valid_client_ids_stored() {

		$hapikey = HUBWOO_STARTER_CLIENT_ID;
		$hseckey = HUBWOO_STARTER_SECRET_ID;

		if( $hapikey && $hseckey ){

			return get_option( 'hubwoo_starter_valid_client_ids_stored' , false );
		}

		return false;
	}

	public static function hubwoo_starter_get_started() {

		return get_option( 'hubwoo_starter_get_started', false );
	}

	/**
	 * check if hubspot oauth has been successful
	 * @since  1.0.0 
	 * @return boolean true/false
	 */

	public function is_oauth_success() {

		return get_option( "hubwoo_starter_oauth_success", false );
	}

	/**
	 * check if plugin feature is enbled or not.
	 * @since  1.0.0 
	 * @return boolean true/false
	 */

	public function is_plugin_enable() {

		return get_option( "hubwoo_starter_settings_enable", "no" );
	}

	/**
	 * check for suggestion popup
	 * @since  1.0.0 
	 * @return boolean true/false
	 */

	public function is_display_suggestion_popup() {

		$suggest = get_option( 'hubwoo_starter_send_suggestions', false );

		if( $suggest ) {

			$success = get_option( 'hubwoo_starter_suggestions_sent', false );

			if( !$success ) {

				$later = get_option( 'hubwoo_starter_suggestions_later', false );

				if( !$later ) {

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * verify if the hubspot setup is completed.
	 *
	 * @since 1.0.0
	 * @return boolean true/false
	 */

	public static function is_setup_completed() {

		return get_option( 'hubwoo_starter_setup_completed', false );
	}

	
	/**
	 * Check the license.
	 *
	 * @since 1.0.0
	 * @return boolean true/false
	 */

	public static function hubwoo_check_key_validity() {

		return get_option( 'hubwoo_starter_valid_license', false );
	}

	/**
	 * Check whether subscriptions are active or not
	 *
	 * @since 1.0.0
	 * @return boolean true/false
	 */

	public static function hubwoo_subs_active() {

		$flag = false;

		if( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			$flag = true;
		}

		return $flag;
	}

	/**
	 * fetch owner email info from HubSpot
	 *
	 * @since 1.0.0
	 * @return boolean true/false
	 */

	public function hubwoo_owners_email_info() {

		$owner_email = get_option( "hubwoo_starter_hubspot_id", "" );

		if( empty( $owner_email ) ) {

			if( self::is_valid_client_ids_stored() ) {

				$flag = true;

				if( self::is_access_token_expired() ) {

					$hapikey = HUBWOO_STARTER_CLIENT_ID;
					$hseckey = HUBWOO_STARTER_SECRET_ID;
					$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey);

					if( !$status ) {

						$flag = false;
					}
				}

				if( $flag ) {

					$owner_email = HubWooConnectionMananager::get_instance()->hubwoo_get_owners_info();

					if( !empty( $owner_email ) ) {

						update_option( "hubwoo_starter_hubspot_id", $owner_email );
					}
				}
			}
		}

		return $owner_email;
	}

	/**
	 * Check the user choice for fields setup
	 *
	 * @since 1.0.0
	 * @return yes/no
	 */

	public static function hubwoo_user_group_choice() {

		return get_option( "hubwoo_starter_select_groups", "" );
	}

	/**
	 * Check the user choice for fields setup
	 *
	 * @since 1.0.0
	 * @return yes/no
	 */

	public static function hubwoo_user_field_choice() {

		return get_option( "hubwoo_starter_select_fields", "" );
	}

	/**
	 * Check the user choice for list setup
	 *
	 * @since 1.0.0
	 * @return yes/no
	 */

	public static function hubwoo_user_list_choice() {

		return get_option( "hubwoo_starter_select_lists", "" );
	}


	/**
	 * returns the user selected fields for setup
	 *
	 * @since 1.0.0
	 * @return array of selected contact properties
	 */

	public static function hubwoo_user_selected_fields() {

		return get_option( "hubwoo_starter_selected_properties", array() );
	}

	/**
	 * returns the user selected groups for setup
	 *
	 * @since 1.0.0
	 * @return array of selected contact groups
	 */

	public static function hubwoo_user_selected_groups() {

		return get_option( "hubwoo_starter_selected_groups", array() );
	}

	/**
	 * returns the user selected lists for setup
	 *
	 * @since 1.0.0
	 * @return array of selected contact lists
	 */

	public static function hubwoo_user_selected_lists() {

		return get_option( "hubwoo_starter_selected_lists", array() );
	}

	/**
	 * getting whether any group selected to be created or not
	 *
	 * @since 1.0.0
	 */

	public function is_groups_to_create() {

		$choice = self::hubwoo_user_group_choice();
		$status = false;

		if( $choice == 'no' ) {

			$status = true;
		}
		elseif( $choice == 'yes' ) {

			$hubwoo_selected_groups = self::hubwoo_user_selected_groups();

			if( is_array( $hubwoo_selected_groups ) && count( $hubwoo_selected_groups ) ) {

				$status = true;
			}
		}

		return $status;
	}

	/**
	 * getting the final groups after the setup
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_final_groups() {

		$final_groups = array();

		$hubwoo_groups = HubWooContactProperties::get_instance()->_get( 'groups' );

		$add_groups = get_option( "hubwoo-starter-groups-created", array() );

		if( self::hubwoo_user_group_choice() == 'no' ) {

			if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

				foreach( $hubwoo_groups as $single_group ) {

					if( in_array( $single_group['name'], $add_groups ) ) {

						$final_groups[] = array( 'detail' => $single_group, 'status' => 'created' );
					}
					else {

						$final_groups[] = array( 'detail' => $single_group, 'status' => 'false' );
					}
				}
			}
		}
		else {

			$hubwoo_selected_groups = self::hubwoo_user_selected_groups();

			if( is_array( $hubwoo_groups ) && count( $hubwoo_groups ) ) {

				foreach( $hubwoo_groups as $single_group ) {

					if( in_array( $single_group["name"], $hubwoo_selected_groups ) && in_array( $single_group["name"], $add_groups ) ) {

						$final_groups[] = array( 'detail' => $single_group, 'status' => 'created' );
					}
					else {

						$final_groups[] = array( 'detail' => $single_group, 'status' => 'false' );
					}
				}
			}
		}

		return $final_groups;
	}


	/**
	 * getting whether any fields selected to be created or not
	 *
	 * @since 1.0.0
	 */

	public function is_fields_to_create() {

		$choice = self::hubwoo_user_field_choice();

		$status = false;

		if( $choice == 'no' ) {

			$status = true;
		}
		elseif( $choice == 'yes' ) {

			$hubwoo_selected_properties = self::hubwoo_user_selected_fields();

			if( is_array( $hubwoo_selected_properties ) && count( $hubwoo_selected_properties ) ) {

				$status = true;
			}
		}

		return $status;
	}

	/**
	 * getting final fields after setup
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_final_fields() {

		global $hubwoo;

		$final_fields = array();

		$hubwoo_final_groups = $hubwoo->hubwoo_get_final_groups();

		$hubwoo_created_properties = get_option( "hubwoo-starter-properties-created", array() );

		if( self::hubwoo_user_field_choice() == 'no' ) {

			if( is_array( $hubwoo_final_groups ) && count( $hubwoo_final_groups ) ) {

				foreach( $hubwoo_final_groups as $single_group_info ) {
					
					if( isset( $single_group_info['status'] ) && $single_group_info['status'] == 'created' ) {

						$hubwoo_starterperties = HubWooContactProperties::get_instance()->_get( 'properties', $single_group_info["detail"]["name"] );
						
						if( is_array( $hubwoo_starterperties ) && count( $hubwoo_starterperties ) ) {

							foreach( $hubwoo_starterperties as $single_property ) {

								if( in_array( $single_property['name'], $hubwoo_created_properties ) ) {

									$field = array( 'name' => $single_property['name'], 'label' => $single_property['label'], 'status' => 'created', 'group' => $single_group_info["detail"]["name"] );
								}
								else {

									$field = array( 'name' => $single_property['name'], 'label' => $single_property['label'], 'status' => 'false', 'group' => $single_group_info["detail"]["name"] );
								}

								$final_fields[] = $field;
							}
						}
					}
				}
			}
		}
		else {

			$hubwoo_selected_fields = self::hubwoo_user_selected_fields();

			if( is_array( $hubwoo_final_groups ) && count( $hubwoo_final_groups ) ) {

				foreach( $hubwoo_final_groups as $single_group_info ) {
					
					if( isset( $single_group_info['status'] ) && $single_group_info['status'] == 'created' ) {

						$hubwoo_starterperties = HubWooContactProperties::get_instance()->_get( 'properties', $single_group_info["detail"]["name"] );
						
						if( is_array( $hubwoo_starterperties ) && count( $hubwoo_starterperties ) ) {

							foreach( $hubwoo_starterperties as $single_property ) {

								if( in_array( $single_property['name'], $hubwoo_selected_fields ) && in_array( $single_property['name'], $hubwoo_created_properties ) ) {

									$field = array( 'name' => $single_property['name'], 'label' => $single_property['label'], 'status' => 'created', 'group' => $single_group_info["detail"]["name"] );
								}
								else {

									$field = array( 'name' => $single_property['name'], 'label' => $single_property['label'], 'status' => 'false', 'group' => $single_group_info["detail"]["name"] );
								}

								$final_fields[] = $field;
							}
						}
					}
				}
			}
		}

		return $final_fields;
	}

	/**
	 * get only created fields on HubSpot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_created_fields() {

		global $hubwoo;

		$final_fields = $hubwoo->hubwoo_get_final_fields();

		$created_fields = array();

		if( is_array( $final_fields ) && count( $final_fields ) ) {

			foreach( $final_fields as $single_field ) {

				if( isset( $single_field['status'] ) && $single_field['status'] == "created" ) {

					$created_fields[] = isset( $single_field['name'] ) ? $single_field['name'] : "";
				}
			}
		}

		return $created_fields;
	}

	/**
	 * filter contact properties with the help of created properties
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_filter_contact_properties( $properties = array()) {

		$filtered_properties = array();

		global $hubwoo;

		$created_properties = $hubwoo->hubwoo_get_created_fields();

		if( !empty( $properties ) && count( $properties ) )  {

			foreach( $properties as $single_property ) {

				if( !empty( $single_property['property'] ) ) {

					if( in_array( $single_property['property'], $created_properties ) ) {

						$filtered_properties[] = $single_property;
					}
				}
			}
		}

		return $filtered_properties;
	} 

	/**
	 * checking the lists to be created or not
	 *
	 * @since 1.0.0
	 */

	public function is_lists_to_create() {

		$choice = self::hubwoo_user_list_choice();
		
		$status = false;

		global $hubwoo;

		$list_count = 0;

		if( $choice == 'no' ) {

			$hubwoo_lists = HubWooContactProperties::get_instance()->_get( 'lists' );

			if( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

				foreach( $hubwoo_lists as $key => $single_list ) {

					$list_filter_created = false;

					$list_filter_created = $hubwoo->is_list_filter_created( $single_list['filters'] );

					if( $list_filter_created ) {

						$list_count += 1;
					}
				}
			}

			if( $list_count ) {

				$status = true;
			}
		}
		elseif( $choice == 'yes' ) {

			$hubwoo_selected_lists = self::hubwoo_user_selected_lists();

			if( is_array( $hubwoo_selected_lists ) && count( $hubwoo_selected_lists ) ) {

				$status = true;
			}
		}

		return $status;
	}

	/**
	 * get final lists to be created on HubSpot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_final_lists() {

		$final_lists = array();

		$hubwoo_lists = HubWooContactProperties::get_instance()->_get( 'lists' );

		if( self::hubwoo_user_list_choice() == 'no' ) {

			if( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

				foreach( $hubwoo_lists as $single_list ) {

					$list_filter_created = self::is_list_filter_created( $single_list['filters'] );

					if( $list_filter_created ) {

						$final_lists[] = $single_list;
					}
				}
			}
		}
		else {

			if( is_array( $hubwoo_lists ) && count( $hubwoo_lists ) ) {

				foreach( $hubwoo_lists as $single_list ) {

					$list_filter_created = self::is_list_filter_created( $single_list['filters'] );

					if( $list_filter_created ) {

						$final_lists[] = $single_list;
					}
				}
			}
		}
		
		if( count( $final_lists ) ) {

			$add_lists = get_option( "hubwoo-starter-lists-created", array() );

			$filtered_final_lists = array();

			foreach( $final_lists as $single_list ) {

				if( in_array( $single_list['name'], $add_lists ) ) {

					$filtered_final_lists[] = array( 'detail' => $single_list, 'status' => 'created' );
				}
				else {

					$filtered_final_lists[] = array( 'detail' => $single_list, 'status' => 'false' );
				}
			}
		}

		return $filtered_final_lists;
	}

	/**
	 * checking the list filter to be created
	 *
	 * @since 1.0.0
	 */

	public function is_list_filter_created( $filters ) {

		$status = true;

		if( is_array( $filters ) && count( $filters ) ) {

			foreach( $filters as $key => $single_filter ) {

				foreach( $single_filter as $single_filter_detail ) {

					if( isset( $single_filter_detail['property'] ) ) {

						$status &= self::check_field_existence( $single_filter_detail['property'] );
					}
				}
			}
		}

		return $status;
	}

	/**
	 * checking field existense for the lists
	 *
	 * @since 1.0.0
	 */

	public static function check_field_existence( $field = '' ) {

		$status = false;

		if( $field == 'lifecyclestage' ) {

			return true;
		}

		global $hubwoo;

		$hubwoo_fields = $hubwoo->hubwoo_get_final_fields();

		if( is_array( $hubwoo_fields ) && count( $hubwoo_fields ) ) {

			foreach( $hubwoo_fields as $single_field ) {

				if( isset( $single_field['name'] ) && $single_field['name'] == $field ) {
				
					if( isset( $single_field['status'] ) && $single_field['status'] == 'created' ) {

						$status = true;
						break;
					}
					else {

						$status = false;	
					}
				}
				else {

					$status = false;
				}
			}
		}

		return $status;
	}


	/**
	 * checking the group setup status
	 *
	 * @since 1.0.0
	 */

	public function is_group_setup_completed() {

		return get_option( "hubwoo_starter_groups_setup_completed", false );
	}

	/**
	 * checking the properties setup status
	 *
	 * @since 1.0.0
	 */

	public function is_field_setup_completed() {

		return get_option( "hubwoo_starter_fields_setup_completed", false );
	}

	/**
	 * checking the lists setup status 
	 *
	 * @since 1.0.0
	 */

	public function is_list_setup_completed() {

		return get_option( "hubwoo_starter_lists_setup_completed", false );
	}

	/**
	 * get array of all user roles of wordpress
	 *
	 * @since 1.0.0
	 */

	public static function hubwoo_get_user_roles() {

		global $wp_roles;

		$exiting_user_roles = array();

		$user_roles = !empty( $wp_roles->role_names )?$wp_roles->role_names:array();

		if( is_array( $user_roles ) && count( $user_roles ) ) {

			foreach ( $user_roles as $role => $role_info ) {

				$role_label = !empty( $role_info ) ? $role_info : $role;

				$exiting_user_roles[$role] = $role_label;
			}
		}

		return $exiting_user_roles;
	}

	public function hubwoo_list_required_properties() {

		$required_fields = array();

		$required_fields[] = "newsletter_subscription";
		$required_fields[] = "total_number_of_orders";
		$required_fields[] = "last_order_date";
		$required_fields[] = "average_days_between_orders";
		$required_fields[] = "monetary_rating";
		$required_fields[] = "order_frequency_rating";
		$required_fields[] = "order_recency_rating";

		return $required_fields;
	}

	public function hubwoo_list_required_groups() {

		$required_groups = array( "rfm_fields", "customer_group" );

		return $required_groups;
	}

	/**
	 * get all added actions for order activity list enrollment
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_order_lists_and_actions() {

		$actions = get_option( "hubwoo_list_enrollment_order_actions", array() );
		$lists = get_option( "hubwoo_enrolled_order_lists", array() );
		$html = '';

		$all_lists = self::hubwoo_get_all_static_lists_id_name();

		update_option( "hubwoo_starter_all_lists", $all_lists );

		if( count( $actions ) && count( $lists ) ) {

			foreach ( $actions as $key => $value ) {
				
				$html .= '<tr data-id="' . $key . '" class="order-list-rule"><td class="forminp forminp-text"><select name="hubwoo_list_enrollment_order_actions[]">'.self::hubwoo_get_selected_order_action($value).'</select></td><td class="forminp forminp-text"><select name="hubwoo_enrolled_order_lists[]">'.self::hubwoo_get_selected_list( $lists[$key], $all_lists ).'</select></td><td><img class="order-list-rule-del" data-id="' . $key . '" height="20px" width="20px" src="' . HUBWOO_STARTER_URL . 'admin/images/delete.png"/></td></tr>';
			}
		}

		return $html;
	}

	/**
	 * get all added actions for customer activity list enrollment
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_get_customer_lists_and_actions() {

		$actions = get_option( "hubwoo_list_enrollment_customer_actions", array() );
		$lists = get_option( "hubwoo_enrolled_customer_lists", array() );
		$html = '';

		$all_lists = get_option( "hubwoo_starter_all_lists", array() );

		if( count( $actions ) && count( $lists ) ) {

			foreach ( $actions as $key => $value ) {
				
				$html .= '<tr data-id="' . $key . '" class="customer-list-rule"><td class="forminp forminp-text"><select name="hubwoo_list_enrollment_customer_actions[]">'.self::hubwoo_get_selected_customer_action($value).'</select></td><td class="forminp forminp-text"><select name="hubwoo_enrolled_customer_lists[]">'.self::hubwoo_get_selected_list($lists[$key], $all_lists ).'</select></td><td><img class="customer-list-rule-del" data-id="' . $key . '" height="20px" width="20px" src="' . HUBWOO_STARTER_URL . 'admin/images/delete.png"/></td></tr>';
			}
		}

		return $html;
	}

		/**
	 * get selected order action
	 *
	 * @since 1.0.0
	 */
	public static function hubwoo_get_selected_order_action( $action = '' ) {

		$actions_for_lists = self::hubwoo_order_actions_for_lists();

		$option = '';

		if( !empty( $actions_for_lists ) ) {

			foreach ( $actions_for_lists as $key => $value ) {
				
				if( $key == $action ) {

					$option .= '<option selected value="'.$key.'">'.$value.'</option>';
				}
				else {

					$option .= '<option value="'.$key.'">'.$value.'</option>';	
				}
			}
		}

		return $option;
	}

	/**
	 * get selected customer action
	 *
	 * @since 1.0.0
	 */
	public static function hubwoo_get_selected_customer_action( $action = '' ) {

		$actions_for_lists = self::hubwoo_customer_actions_for_lists();

		$option = '';

		if( !empty( $actions_for_lists ) ) {

			foreach ( $actions_for_lists as $key => $value ) {
				
				if( $key == $action ) {

					$option .= '<option selected value="'.$key.'">'.$value.'</option>';
				}
				else {

					$option .= '<option value="'.$key.'">'.$value.'</option>';	
				}
			}
		}

		return $option;
	}

	/**
	 * get array of order status transition actions
	 *
	 * @since 1.0.0
	 */
	public static function hubwoo_order_actions_for_lists() {

		$actions = array();

		$actions["woocommerce_order_status_completed"] = __("When order status changes to Completed", "hubwoo");
		$actions["woocommerce_order_status_processing"] = __("When order status changes to Processing", "hubwoo");
		$actions["woocommerce_order_status_failed"] = __("When order status changes to Failed", "hubwoo");
		$actions["woocommerce_order_status_on-hold"] = __("When order status changes to On-hold", "hubwoo");
		$actions["woocommerce_order_status_refunded"] = __("When order status changes to Refunded", "hubwoo");
		$actions["woocommerce_order_status_cancelled"] = __("When order status changes to Cancelled", "hubwoo");

		return $actions;
	}

	/**
	 * get array of customer activity actions
	 *
	 * @since 1.0.0
	 */
	public static function hubwoo_customer_actions_for_lists() {

		$actions = array();

		$actions["user_register"] = __(" New User Registeration ", "hubwoo");
		$actions["profile_update"] = __(" User Profile Update ", "hubwoo");

		return $actions;
	}

	public static function hubwoo_get_selected_list( $list = '', $all_lists ) {

		$option = '';

		if( !empty( $all_lists ) ) {

			foreach ( $all_lists as $key => $value ) {
				
				if( $key == $list ) {

					$option .= '<option selected value="'.$key.'">'.$value.'</option>';
				}
				else {

					$option .= '<option value="'.$key.'">'.$value.'</option>';	
				}
			}
		}

		return $option;
	}


	/**
	 * get array of all static lists with id and name
	 *
	 * @since 1.0.0
	 */

	public static function hubwoo_get_all_static_lists_id_name() {

		$all_lists = array();

		if( Hubwoo::is_valid_client_ids_stored() ) {

			$flag = true;

			if( Hubwoo::is_access_token_expired() ) {

				$hapikey = HUBWOO_STARTER_CLIENT_ID;
				$hseckey = HUBWOO_STARTER_SECRET_ID;
				$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey);

				if( !$status ) {

					$flag = false;
				}
			}

			if( $flag ) {

				$all_lists = HubWooConnectionMananager::get_instance()->get_static_list();
			}
		}

		return $all_lists;
	}

	public function hubwoo_switch_account() {

		delete_option( "hubwoo_starter_get_started" );
		delete_option( "hubwoo_starter_valid_client_ids_stored" );
		delete_option( "hubwoo_starter_send_suggestions" );
		delete_option( "hubwoo_starter_suggestions_later" );
		delete_option( "hubwoo_starter_oauth_success" );
		delete_option( "hubwoo_starter_setup_completed" );
		delete_option( "hubwoo_starter_version" );
		delete_option( "hubwoo_starter_hubspot_id" );
		delete_option( "hubwoo_starter_select_groups" );
		delete_option( "hubwoo_starter_selected_groups" );
		delete_option( "hubwoo-starter-groups-created" );
		delete_option( "hubwoo_starter_groups_setup_completed" );
		delete_option( "hubwoo_starter_groups_version" );
		delete_option( "hubwoo_starter_select_fields" );
		delete_option( "hubwoo_starter_selected_properties" );
		delete_option( "hubwoo_starter_fields_setup_completed" );
		delete_option( "hubwoo-starter-properties-created" );
		delete_option( "hubwoo_starter_select_lists" );
		delete_option( "hubwoo-starter-lists-created" );
		delete_option( "hubwoo_starter_selected_lists" );
		delete_option( "hubwoo_starter_lists_setup_completed" );
		delete_option( "hubwoo-starter-success-api-calls" );
		delete_option( "hubwoo-starter-error-api-calls" );
		delete_option( "hubwoo_starter_alert_param_set" );
	    wp_redirect( admin_url('admin.php').'?page=hubwoo' );
	    exit();
	}

	/*
		* get full name for woocommerce country
	*/

	public static function map_country_by_abbr ( $value ) {

		if ( !empty( $value ) ) {
			if ( class_exists( 'WC_Countries' ) ) {
				$wc_countries = new WC_Countries();
				$countries = $wc_countries->__get( "countries" );
			}
			if ( !empty( $countries ) ) {
				foreach ( $countries as $abbr => $country_name ) {
					if ( $value == $abbr ) {
						$value = $country_name;
						break;
					}
				}
			}	
		}	
		return $value;
	}

	/*
		* get full name for stats by country
	*/
	public static function map_state_by_abbr ( $value, $country ) {

		if ( !empty( $value ) && !empty( $country ) ) {
			if ( class_exists( 'WC_Countries' ) ) {
				$wc_countries = new WC_Countries();
				$states = $wc_countries->__get( "states" );
			}
			if ( !empty( $states ) ) {
				foreach ( $states as $country_abbr => $country_states ) {
					if ( $country == $country_abbr ) {
						foreach ( $country_states as $state_abbr => $state_name ) {
							if ( $value == $state_abbr ) {
								$value = $state_name;
								break;
							}
						}
						break;
					}
				}
			}	
		}	
		return $value;
	}

	/**
	 * activate the license key.
	 *
	 * @since 1.0.0
	 */
	
	public function activate_license( $api_params = array() ) {

		$query = esc_url_raw( add_query_arg( $api_params, HUBWOO_STARTER_LICENSE_SERVER_URL ) );
		
        $response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

        if ( is_wp_error( $response ) ) {

            $this->hubwoo_notice( __('An unexpected error occured. Please try again later.','hubwoo'), 'error' );
        }
        else {

	        $license_data = json_decode( wp_remote_retrieve_body( $response ) );

	        $message = __( 'An unexpected error occured. Please try again after sometime.', 'hubwoo' );

	        if( isset( $license_data->message ) && !empty( $license_data->message ) ) {

	        	$message = $license_data->message;
	        }

	        if( isset( $license_data->result ) && $license_data->result == 'success' ) {

	        	if( !empty( $license_data->date_expiry ) ) {

	        		update_option( "hubwoo_starter_lic_expiry", $license_data->date_expiry );
	        	}
	            update_option( 'hubwoo_starter_valid_license', true ); 
	            update_option( 'hubwoo_starter_license_key', $api_params['license_key'] ); 
	            wp_redirect( admin_url() . 'admin.php?page=hubwoo' );
	        }
	        else {

				$this->hubwoo_notice( $message, 'error' );
	        }
	    }
	}

	/**
	 * verify the license key.
	 *
	 * @since 1.0.0
	 */
	
	public function verify_license( $api_params = array() ) {

		$query = esc_url_raw( add_query_arg( $api_params, HUBWOO_STARTER_LICENSE_SERVER_URL ) );
		
        $response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

        if ( is_wp_error( $response ) ) {

            return;
        }
        else {

	        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
	      	
	        if( isset( $license_data->result ) &&  $license_data->result == 'success' ) {

	            if ( isset( $license_data->status ) &&  $license_data->status == 'active' ) {

	            	update_option('hubwoo_starter_valid_license', true);
	            }
	            else {

	            	delete_option('hubwoo_starter_valid_license');
	            }
	        }
	        elseif( isset( $license_data->result ) && $license_data->result == 'error' && isset( $license_data->error_code ) && $license_data->error_code == 60 ) {
	        	
	        	delete_option('hubwoo_starter_valid_license');
	        }
	    }
	}
}