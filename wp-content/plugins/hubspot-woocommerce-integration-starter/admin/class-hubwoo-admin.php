<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package   hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Hubwoo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// let's modularize our codebase, all the admin actions in one function. 
		$this->admin_actions();
	}

	/**
	 * all admin actions.
	 * 
	 * @since 1.0.0
	 */
	public function admin_actions() {

		// add submenu hubspot in woocommerce top menu.
		add_action( 'admin_menu', array( &$this, 'add_hubwoo_submenu' ) );
	}

	/**
	 * add hubspot submenu in woocommerce menu..
	 *
	 * @since 1.0.0
	 */
	public function add_hubwoo_submenu() {

		add_submenu_page( 'woocommerce', __('HubSpot', 'hubwoo'), __('HubSpot', 'hubwoo'), 'manage_woocommerce', 'hubwoo', array(&$this, 'hubwoo_configurations') );
	}

	/**
	 * all the configuration related fields and settings.
	 * 
	 * @return html  all the settings and configuration options for hubspot.
	 * @since 1.0.0
	 */
	public function hubwoo_configurations() {

		include_once HUBWOO_STARTER_ABSPATH . 'admin/templates/hubwoo-main-template.php';
	}

	/**
	 * General setting tab fields.
	 * 
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_general_settings() {

		$basic_settings = array();
		$basic_settings[] = array(
			'title' => __('Connect With HubSpot', 'hubwoo'),  
			'id'	=> 'hubwoo_starter_settings_title', 
			'type'	=> 'title'	
		);
		$basic_settings[] = array(
			'title' => __('Enable/Disable', 'hubwoo'),
			'id'	=> 'hubwoo_starter_settings_enable', 
			'desc'	=> __('Turn on/off the integration', 'hubwoo'),
			'type'	=> 'checkbox'
		);
		$url = '<a target="_blank" href="' . admin_url( "admin.php?page=wc-status&tab=logs" ) . '">' . __( "Here", "hubwoo" ) . '</a>';
		$basic_settings[] = array(
			'title' 	=> __('Enable/Disable', 'hubwoo'),
			'id'		=> 'hubwoo_starter_log_enable', 
			'desc'		=> sprintf( __('Enable logging of the requests. You can view HubSpot log file from %s', 'hubwoo' ), $url ),
			'type'		=> 'checkbox',
			'default'	=> 'yes',
		);
		$basic_settings[] = array(
			'type' 	=> 'sectionend',
	        'id' 	=> 'hubwoo_starter_settings_end'
		);
		return $basic_settings;
	}

	/**
	 * General setting after setup of plugin.
	 * 
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_more_general_settings() {

		$basic_settings = array();
		$basic_settings[] = array(
			'title' => __('Plugin related settings', 'hubwoo'),  
			'id'	=> 'hubwoo_starter_settings_title', 
			'type'	=> 'title'	
		);
		$basic_settings[] = array(
			'title' => __('Enable/Disable', 'hubwoo'),
			'id'	=> 'hubwoo_starter_settings_enable', 
			'desc'	=> __('Turn on/off the integration', 'hubwoo'),
			'type'	=> 'checkbox'
		);
		if ( Hubwoo::hubwoo_subs_active() ) { 
			$basic_settings[] = array(
				'title' => __('Enable/Disable', 'hubwoo'),
				'id'	=> 'hubwoo_starter_subs_settings_enable', 
				'desc'	=> __('Turn on/off the Subscriptions Data', 'hubwoo'),
				'type'	=> 'checkbox',
				'default' => "yes",
			);
		}
		$basic_settings[] = array(
			'title' => __('Enable/Disable', 'hubwoo'),
			'id'	=> 'hubwoo_starter_guest_sync_enable', 
			'desc'	=> __( "Enable realtime sync of guest orders to HubSpot", "hubwoo" ),
			'default' => "yes",
			'type'	=> 'checkbox'
		);
		$url = '<a target="_blank" href="' . admin_url( "admin.php?page=wc-status&tab=logs" ) . '">' . __( "Here", "hubwoo" ) . '</a>';
		$basic_settings[] = array(
			'title' => __('Enable/Disable', 'hubwoo'),
			'id'	=> 'hubwoo_starter_log_enable', 
			'desc'	=> sprintf( __('Enable logging of the requests. You can view HubSpot log file from %s', 'hubwoo' ), $url ),
			'type'	=> 'checkbox'
		);
		$basic_settings[] = array(
			'type' => 'sectionend',
	        'id' => 'hubwoo_starter_settings_end'
		);
		return $basic_settings;
	}

	/**
	 * Checkout Checkbox settings
	 * 
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */
	public static function hubwoo_checkout_optin_settings() {

		$basic_settings = array();

		$basic_settings[] = array(
			'title' 	=> __( 'CheckOut & Registeration (my-account page) Optin Settings', 'hubwoo' ),  
			'id'		=> 'hubwoo_checkout_optin_title', 
			'type'		=> 'title'	
		);

		$basic_settings[] = array(
			'title' 	=> __( 'Show/Hide', 'hubwoo' ),
			'id'		=> 'hubwoo_checkout_optin_enable', 
			'desc'		=> __( 'Show/Hide the optin checkbox on Checkout Page', 'hubwoo' ),
			'type'		=> 'checkbox',
			'default' 	=> 'no',
		);

		$basic_settings[] = array(
			'title' 	=> __( 'Checkbox Label on Checkout Page', 'hubwoo' ),
			'id'		=> 'hubwoo_checkout_optin_label', 
			'desc'		=> __( 'Label to show for the checkbox', 'hubwoo' ),
			'type'		=> 'text',
			'default'	=> __( "Subscribe", "hubwoo"),
		);

		$basic_settings[] = array(
			'title' 	=> __( 'Show/Hide', 'hubwoo' ),
			'id'		=> 'hubwoo_registeration_optin_enable', 
			'desc'		=> __( 'Show/Hide the optin checkbox on My account Page (registeration form)', 'hubwoo' ),
			'type'		=> 'checkbox',
			'default' 	=> 'no',
		);

		$basic_settings[] = array(
			'title' 	=> __( 'Checkbox Label on My account Page', 'hubwoo' ),
			'id'		=> 'hubwoo_registeration_optin_label', 
			'desc'		=> __( 'Label to show for the checkbox', 'hubwoo' ),
			'type'		=> 'text',
			'default'	=> __( "Subscribe", "hubwoo"),
		);

		$basic_settings[] = array(
			'type' 	=> 'sectionend',
	        'id' 	=> 'hubwoo_checkout_optin_end'
		);

		return $basic_settings;
	}


	/**
	 * General setting tab fields for hubwoo old customers sync
	 * 
	 * @return array  woocommerce_admin_fields acceptable fields in array.
	 * @since 1.0.0
	 */

	public static function hubwoo_customers_sync_settings() {

		$settings = array();

		if ( ! function_exists( 'get_editable_roles' ) ) {
			
		    require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		global $wp_roles;

		$user_roles = !empty( $wp_roles->role_names )?$wp_roles->role_names:array();

		if( is_array( $user_roles ) && count( $user_roles ) ) {

			foreach ( $user_roles as $role => $role_info ) {

				$role_label = !empty( $role_info ) ? $role_info : $role;

				$exiting_user_roles[$role] = $role_label;
			}
		}
		
		$settings[] = array(
			'title' => __('Export your old users and customers to HubSpot', 'hubwoo'),  
			'id'	=> 'hubwoo_starter_customers_settings_title', 
			'type'	=> 'title'	
		);

		$settings[] = array(
			'title' => __('Enable/Disable', 'hubwoo'),
			'id'	=> 'hubwoo_starter_customers_settings_enable', 
			'desc'	=> __('Turn on/off the feature', 'hubwoo'),
			'type'	=> 'checkbox'
		);

		$settings[] = array(
			'title' 		=> __('Select User Role', 'hubwoo'),
			'id'			=> 'hubwoo_starter_customers_role_settings', 
			'type'			=> 'select',
			'desc'			=> __( "Select a user role from the dropdown", "hubwoo"),
			'options' 		=> $exiting_user_roles,
			'desc_tip'		=> true,
		);

		$settings[] = array(
			'title'			=> __( "Users registered from date", "hubwoo" ),
			'id'			=> 'hubwoo-users-from-date',
			'type'			=> 'text',
			'placeholder'	=> "dd-mm-yyyy",
			'default'		=> date("d-m-Y"),
			'desc'			=> __( "From which date you want to sync the users, select that", "hubwoo" ),
			'desc_tip'		=> true,
			'class'			=> 'hubwoo-date-picker',
		);

		$settings[] = array(
			'title'			=> __( "Users registered upto date", "hubwoo" ),
			'id'			=> 'hubwoo-users-upto-date',
			'type'			=> 'text',
			'default'		=> date("d-m-Y"),
			'placeholder'	=> "dd-mm-yyyy",
			'desc'			=> __( "Upto which date you want to sync the users, select that date", "hubwoo" ),
			'desc_tip'		=> true,
			'class'			=> 'hubwoo-date-picker',
		);

		$settings[] = array(
			'type' 	=> 'sectionend',
	        'id' 	=> 'hubwoo_starter_customers_settings_end'
		);

		return $settings;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();
		
        if( isset( $screen->id ) && $screen->id == 'woocommerce_page_hubwoo' ) {

			wp_enqueue_style( "hubwoo-admin-style", plugin_dir_url( __FILE__ ) . 'css/hubwoo-admin.css', array(), $this->version, 'all' );
			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );

			wp_enqueue_style( 'woocommerce_admin_menu_styles' );
				
			wp_enqueue_style( 'woocommerce_admin_styles' );

			wp_enqueue_style( 'hubwoo_jquery_ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();
		
        if( isset( $screen->id ) && $screen->id == 'woocommerce_page_hubwoo' ) {
        	
        	wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ), WC_VERSION );
			$locale  = localeconv();
			$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
			$params = array(
				'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', "hubwoo" ), $decimal ),
				'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'hubwoo' ), wc_get_price_decimal_separator() ),
				'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'hubwoo' ),
				'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', 'hubwoo' ),
				'decimal_point'                     => $decimal,
				'mon_decimal_point'                 => wc_get_price_decimal_separator(),
				'strings' => array(
					'import_products' => __( 'Import', 'hubwoo' ),
					'export_products' => __( 'Export', 'hubwoo' ),
				),
				'urls' => array(
					'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
					'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
				),
			);
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
			wp_enqueue_script( 'woocommerce_admin' );
			wp_register_script( 'hubwoo_admin_script', plugin_dir_url( __FILE__ ) . 'js/hubwoo-admin.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'hubwoo_admin_script', 
				'hubwooi18n', array( 
					'ajaxUrl' 					=> admin_url( 'admin-ajax.php' ),
					'hubwooSecurity' 			=> wp_create_nonce( 'hubwoo_security' ), 
					'hubwooWentWrong' 			=> __( 'Something went wrong, please try again later!', 'hubwoo' ), 
					'hubwooSuccess' 			=> __('Setup is completed successfully!', 'hubwoo' ),
					'hubwooCreatingGroup' 		=> __('Created group', 'hubwoo' ),
					'hubwooCreatingList' 		=> __('Created list', 'hubwoo' ),
					'hubwooCreatingProperty' 	=> __('Created property', 'hubwoo' ),
					'hubwooSetupCompleted' 		=> __('Setup completed!', 'hubwoo'),
					'hubwooMailSuccess'			=> __('Mail Sent Successfully. We will get back to you soon.', 'hubwoo'),
					'hubwooMailFailure' 		=> __('Mail not sent', 'hubwoo'),
					'hubwooCreatingList' 		=> __('Created List', 'hubwoo' ),
					'hubwooAccountSwitch' 		=> __('Want to continue to switch to new HubSpot account? This cannot be reverted and will require running the whole setup again.','hubwoo'),
					'hubwooUserSyncComplete' 	=> __('Users Sync Complete!', 'hubwoo'),
					'hubwooOrdersSyncComplete' 	=> __('Orders Sync Complete!', 'hubwoo'),

					'hubwooBatchUpdate' 		=> __('Contacts Batch Updated over HubSpot','hubwoo'),
					'hubwooNoUsersFound' 		=> __('No users found. Try later','hubwoo'),
					'hubwooNoOrdersFound'		=> __('No Orders found. Try later', 'hubwoo'),
					'hubwooUpdateFail' 			=> __('Error while updating properties. Check the logs and try again.','hubwoo'),
					'hubwooUpdateSuccess' 		=> __('All properties updated successfully.','hubwoo'),
					'hubwooRollback' 			=> __('Doing rollback will require running the whole setup again. Continue?'),
					'hubwooConnectTab' 			=> admin_url() . 'admin.php?page=hubwoo&hubwoo_tab=hubwoo_connect',
					'hubwooLicenseUpgrade' 		=> __('This will redirect you to License Activation Panel and your current license will be deleted. For license change or upgrade, use the new license key received after the full purchase of the extension.','hubwoo'),
					'hubwooGroupSetupCompleted' => __('Groups setup completed','hubwoo'),
					'hubwooCreatingList' 		=> __('Created List','hubwoo'),
					'hubwooOverviewTab' 		=> admin_url() .'admin.php?page=hubwoo&hubwoo_tab=hubwoo_overview',
					'hubwooPropertyExists' 		=> __('The Property already exists','hubwoo'),
					'hubwooListExists' 			=> __('The List already exists','hubwoo'),
					'hubwooGroupExists' 		=> __('The group already exists','hubwoo'),
				)
			);

			wp_enqueue_script( 'hubwoo_admin_script' );
		}
	}

	/**
	 * Update schedule data with custom time.
	 *
	 * @since    1.0.0
	 * @param      string    $schedules       Schedule data.
	 */
	public function hubwoo_set_cron_schedule_time( $schedules ) {

	    if( !isset( $schedules[ "mwb-hubwoo-starter-5min" ] ) ) {

	        $schedules["mwb-hubwoo-starter-5min"] = array(
	            'interval' => 5*60,
	            'display' => __( 'Once every 5 minutes', 'hubwoo' )
	        );
	    }

	    return $schedules;
	}

	/**
	 * Schedule Executes when user data is update.
	 *
	 * @since    1.0.0
	 * @param      string    $schedules       Schedule data.
	 */
	public function hubwoo_starter_cron_schedule() {

		global $hubwoo;

		$args['meta_query'] = array(

			array(
				'key' 		=> 'hubwoo_starter_user_data_change',
				'value' 	=> 'yes',
				'compare' 	=> '=='
			)
		);

		$args['role__in'] = get_option( "hubwoo-selected-user-roles", array() );
		$args['number'] = 20;

		$hubwoo_updated_user = get_users( $args );
		
		$hubwoo_users = apply_filters( 'hubwoo_users', $hubwoo_updated_user );

		$hubwoo_unique_users = array();

		if( is_array( $hubwoo_users ) && count( $hubwoo_users ) ) {

			foreach( $hubwoo_users as $key => $value ) {

				if( in_array( $value->ID, $hubwoo_unique_users ) ) {

					continue;
				}
				else {

					$hubwoo_unique_users[]= $value->ID;
				}
			}
		}
		
		if( isset( $hubwoo_unique_users ) && $hubwoo_unique_users != null  && count( $hubwoo_unique_users ) ) {

			foreach ( $hubwoo_unique_users as $key => $ID ) {

				$hubwoo_customer = new HubWooCustomer( $ID );
				$email = $hubwoo_customer->get_email();
				if ( empty( $email ) ) {
					delete_user_meta( $ID, 'hubwoo_starter_user_data_change' );
					continue;
				}
				$properties = $hubwoo_customer->get_contact_properties();

				$fName = get_user_meta( $ID, 'first_name', true );
				if ( !empty( $fName ) ) {
					$properties[] = array( 'property' => 'firstname', 'value' => $fName );
				}
				
				$lName = get_user_meta( $ID, 'last_name', true );
				if ( !empty( $lName ) ) {
					$properties[] = array( 'property' => 'lastname', 'value' => $lName );
				}

				$cName = get_user_meta( $ID, 'billing_company', true );
				if ( !empty( $cName ) ) {
					$properties[] = array( 'property' => 'company', 'value' => $cName );
				}

				$phone = get_user_meta( $ID, 'billing_phone', true );
				if ( !empty( $phone ) ) {
					$properties[] = array( 'property' => 'mobilephone', 'value' => $phone );
					$properties[] = array( 'property' => 'phone', 'value' => $phone );
				}

				$city = get_user_meta( $ID, 'billing_city', true );
				if ( !empty( $city ) ) {
					$properties[] = array( 'property' => 'city', 'value' => $city );
				}

				$state = get_user_meta( $ID, 'billing_state', true );
				if ( !empty( $state ) ) {
					$properties[] = array( 'property' => 'state', 'value' => $state );
				}

				$country = get_user_meta( $ID, 'billing_country', true );
				if ( !empty( $country ) ) {
					$properties[] = array( 'property' => 'country', 'value' => $country );
				}
				
				$address1 = get_user_meta( $ID, 'billing_address_1', true );
				$address2 = get_user_meta( $ID, 'billing_address_2', true );

				if ( !empty( $address1 ) || !empty( $address2 ) ) {
					$address = $address1 . " " . $address2;
					$properties[] = array( 'property' => 'address', 'value' => $address );
				}

				$postCode = get_user_meta( $ID, 'billing_postcode', true );
				if ( !empty( $postCode ) ) {
					$properties[] = array( 'property' => 'zip', 'value' => $postCode );
				}

				$properties = apply_filters( 'hubwoo_map_new_properties', $properties, $ID );

				$properties_data = array( 'email' => $email, 'properties' => $properties );

				$contacts[] = $properties_data;

				delete_user_meta( $ID, 'hubwoo_starter_user_data_change' );

				if ( self::hubwoo_check_for_cart( $properties ) ) {
					update_user_meta( $ID, "hubwoo_pro_user_cart_sent", "yes" );
				}
			}
			
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

					$response  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $contacts );
					if ( ( count( $contacts ) > 1 ) && isset( $response['status_code'] ) && $response['status_code'] == 400 ) {
						$response = self::hubwoo_split_contact_batch( $contacts );
					}
				}
			}	
		}

		unset( $hubwoo_unique_users );
		unset( $contacts );

		$args['meta_query'] = array(
			array(
				'key' 		=> 'hubwoo_starter_user_in_list',
				'compare' 	=> 'EXISTS',
			)
		);

		$args['role__in'] = get_option( "hubwoo-selected-user-roles", array() );
		$args['number'] = 20;
		$hubwoo_user_enrollments = get_users( $args );
		$hubwoo_user_enrollments = apply_filters( 'hubwoo_user_list_enrollments', $hubwoo_user_enrollments );
		$hubwoo_unique_users = array();
		if( is_array( $hubwoo_user_enrollments ) && count( $hubwoo_user_enrollments ) ) {
			foreach( $hubwoo_user_enrollments as $key => $value ) {
				if( in_array( $value->ID, $hubwoo_unique_users ) ) {
					continue;
				}
				else {
					$hubwoo_unique_users[] = $value->ID;
				}
			}
		}
	
		if( count( $hubwoo_unique_users ) ) {

			foreach( $hubwoo_unique_users as $key => $ID ) {
				$user = get_user_by( 'id', $ID );
				$customer_email = $user->data->user_email;
				if ( empty( $customer_email ) ) {
					delete_user_meta( $ID, 'hubwoo_starter_user_in_list' );
					continue;
				}
				$list_id = get_user_meta( $ID, 'hubwoo_starter_user_in_list', true );
				if( !empty( $list_id ) && "select" !== $list_id ) {
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
							$contact_properties = array();
							$contact_properties[] = array( "property" => "email", "value" => $customer_email );
							$contact_details = array( "properties" => $contact_properties );
							$response = HubWooConnectionMananager::get_instance()->create_single_contact( $contact_details );
							if ( !empty( $response["status_code"] ) && ( 200 == $response["status_code"] || 409 == $response["status_code"] ) ) {
								HubWooConnectionMananager::get_instance()->list_enrollment( $customer_email, $list_id );
							}
						}
					}
				}
				delete_user_meta( $ID, 'hubwoo_starter_user_in_list' );
			}
		}

		$hubwoo_guest_orders = get_posts(
			array(
				'posts_per_page' =>  20,
				'post_type'      =>  'shop_order',
				'post_status'    =>  'any',
				'meta_key'       =>  'hubwoo_starter_guest_order',
				'meta_value'     =>  'yes',
				'orderby'        =>  'ID',
				'order'          =>  'ASC'
			)
		);

		$hubwoo_orders = apply_filters( 'hubwoo_guest_orders', $hubwoo_guest_orders );

		if( isset( $hubwoo_orders ) && $hubwoo_orders != null && count( $hubwoo_orders ) ) {

			foreach( $hubwoo_orders as $single_order_key => $single_order ) {

				if( isset( $single_order->ID ) ) {

					$hubwoo_guest_order = wc_get_order( $single_order->ID );

					if( $hubwoo_guest_order instanceof WC_Order ) {

						$guest_email = get_post_meta( $single_order->ID, '_billing_email', true );

						if( empty( $guest_email ) ) {
							delete_post_meta( $single_order->ID, 'hubwoo_starter_guest_order' );
							continue;
						}

						$guest_order_callback = new HubwooGuestOrdersManager( $single_order->ID );

						$guest_user_properties = $guest_order_callback->get_order_related_properties( $single_order->ID, $guest_email );

						$guest_user_properties = $hubwoo->hubwoo_filter_contact_properties( $guest_user_properties );

						$fName = get_post_meta( $single_order->ID, '_billing_first_name', true );
						if ( !empty( $fName ) ) {
							$guest_user_properties[] = array( 'property' => 'firstname', 'value' => $fName );
						}
						
						$lName = get_post_meta( $single_order->ID, '_billing_last_name', true );
						if ( !empty( $lName ) ) {
							$guest_user_properties[] = array( 'property' => 'lastname', 'value' => $lName );
						}

						$cName = get_post_meta( $single_order->ID, '_billing_company', true );
						if ( !empty( $cName ) ) {
							$guest_user_properties[] = array( 'property' => 'company', 'value' => $cName );
						}

						$city = get_post_meta( $single_order->ID, '_billing_city', true );
						if ( !empty( $city ) ) {
							$guest_user_properties[] = array( 'property' => 'city', 'value' => $city );
						}
						
						$state = get_post_meta( $single_order->ID, '_billing_state', true );
						if ( !empty( $state ) ) {
							$guest_user_properties[] = array( 'property' => 'state', 'value' => $state );
						}
						
						$country = get_post_meta( $single_order->ID, '_billing_country', true );
						if ( !empty( $country ) ) {
							$guest_user_properties[] = array( 'property' => 'country', 'value' => $country );
						}
					
						$address1 = get_post_meta( $single_order->ID, '_billing_address_1', true );
						$address2 = get_post_meta( $single_order->ID, '_billing_address_2', true );
						if ( !empty( $address1 ) || !empty( $address2 ) ) {
							$address = $address1 . " " . $address2;
							$guest_user_properties[] = array( 'property' => 'address', 'value' => $address );
						}

						$zip = get_post_meta( $single_order->ID, '_billing_postcode', true );
						if ( !empty( $zip ) ) {
							$guest_user_properties[] = array( 'property' => 'zip', 'value' => $zip );
						}

						$guest_phone = get_post_meta( $single_order->ID, '_billing_phone', true );

						if ( !empty( $guest_phone ) ) {
							$guest_user_properties[] = array( 'property' => 'mobilephone', 'value' => $guest_phone );
							$guest_user_properties[] = array( 'property' => 'phone', 'value' => $guest_phone );
						}

						if( !empty( $guest_user_properties ) ) {

							foreach( $guest_user_properties as $key => $single_property ) {

								if( isset( $single_property['property'] ) && $single_property['property'] == "customer_new_order" ) {

									$guest_user_properties[$key]["value"] = "yes";
									break;
								}
							}	
						}

						$guest_user_properties_data = array( 'email' => $guest_email, 'properties' => $guest_user_properties );

						$guest_user_properties_data[] = array( 'property' => 'customer_new_order', 'value' => "yes" );

						$guest_contacts[] = $guest_user_properties_data;

						delete_post_meta( $single_order->ID, 'hubwoo_starter_guest_order' );
					}
				}
			}

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

					$response  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $guest_contacts );
					if ( ( count( $guest_contacts ) > 1 ) && isset( $response['status_code'] ) && $response['status_code'] == 400 ) {

						$response = self::hubwoo_split_contact_batch( $guest_contacts );
					}
				}
			}
		}

		$order_statuses = get_option( "hubwoo-selected-order-status", array() );
	    if( empty( $order_statuses ) ) {

			$order_statuses = array_keys( wc_get_order_statuses() );
		}
		$hubwoo_order_in_lists = get_posts(
			array(
				'posts_per_page' =>  20,
				'post_type'      =>  'shop_order',
				'post_status'    =>  $order_statuses,
				'meta_key'       =>  'hubwoo_starter_order_in_list',
				'orderby'        =>  'ID',
				'order'          =>  'ASC'
			)
		);

		$hubwoo_order_in_lists = apply_filters( 'hubwoo_order_in_lists', $hubwoo_order_in_lists );	

		if( !empty( $hubwoo_order_in_lists ) ) {

			foreach( $hubwoo_order_in_lists as $single_order ) {

				if( !empty( $single_order->ID ) ) {

					$list_id = get_post_meta( $single_order->ID, 'hubwoo_starter_order_in_list', true );
					$user_id = get_post_meta( $single_order->ID, '_customer_user', true );
					$customer_email = get_post_meta( $single_order->ID, '_billing_email', true );

					if( $user_id != 0 && $user_id > 0 ) {
						$user = get_user_by( 'id', $user_id );
						$customer_email = !empty( $user->data->user_email ) ? $user->data->user_email : "";
					}
					else {
						$customer_email = get_post_meta( $single_order->ID, '_billing_email', true );
					}
					
					if ( empty( $customer_email ) ) {
						delete_post_meta( $single_order->ID, 'hubwoo_starter_order_in_list' );
						continue;
					}

					if( !empty( $list_id ) && "select" != $list_id ) {

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

								$contact_properties = array();
								$contact_properties[] = array( "property" => "email", "value" => $customer_email );
								$contact_details = array( "properties" => $contact_properties );
								$response = HubWooConnectionMananager::get_instance()->create_single_contact( $contact_details );
								if ( !empty( $response["status_code"] ) && ( 200 == $response["status_code"] || 409 == $response["status_code"] ) ) {

									HubWooConnectionMananager::get_instance()->list_enrollment( $customer_email, $list_id );
								}
							}
						}
					}

					delete_post_meta( $single_order->ID, 'hubwoo_starter_order_in_list' );
				}
			}
		}

		$hubwoo_guest_cart = get_option( "mwb_hubwoo_guest_user_cart", array() ); 
		
		$guest_abandoned_carts = array();

		if ( !empty ( $hubwoo_guest_cart ) ) {

			foreach ( $hubwoo_guest_cart as $key => &$single_cart ) {
				
				if ( isset( $single_cart["email"] ) ) {

					if ( !empty( $single_cart["sent"] ) && "yes" == $single_cart["sent"] ) {
						if ( empty( $single_cart["cartData"] ) || empty( $single_cart["cartData"]["cart"] ) ) {
							unset( $hubwoo_guest_cart[$key] );
						}
						continue;
					}

					$guest_user_properties = apply_filters( "hubwoo_pro_track_guest_cart", array(), $single_cart["email"] );

					if ( self::hubwoo_check_for_cart( $guest_user_properties ) ) {

						$single_cart["sent"] = "yes";
					}
					elseif ( !self::hubwoo_check_for_cart( $guest_user_properties ) && self::hubwoo_check_for_cart_contents( $guest_user_properties ) ) {

						$single_cart["sent"] = "yes";
					}

					$guest_abandoned_carts[] = array( 'email' => $single_cart["email"], 'properties' => $guest_user_properties );
				}
			}

			update_option( "mwb_hubwoo_guest_user_cart", $hubwoo_guest_cart );
		}
		
		if ( count( $guest_abandoned_carts ) ) {

			$chunked_array = array_chunk( $guest_abandoned_carts, 50, false );

			if ( !empty( $chunked_array ) ) {

				foreach ( $chunked_array as $single_chunk ) {

					if( Hubwoo::is_valid_client_ids_stored() ) {

						$flag = true;

						if( Hubwoo::is_access_token_expired() ) {

							$hapikey = HUBWOO_STARTER_CLIENT_ID;
							$hseckey = HUBWOO_STARTER_SECRET_ID;
							$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey );

							if( !$status ) {

								$flag = false;
							}
						}

						if( $flag ) {

							$response = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $single_chunk );
							if ( ( count( $single_chunk ) > 1 ) && isset( $response['status_code'] ) && $response['status_code'] == 400 ) {

								$response = self::hubwoo_split_contact_batch( $single_chunk );
							}
						}
					}
				}
			}
		}
	}

	/*
		* check if the user has get cart as abandoned
	*/
	public static function hubwoo_check_for_cart ( $properties ) {
	
		$flag = false;

		if ( !empty( $properties ) ) {

			foreach ( $properties as $single_record ) {

				if ( !empty( $single_record["property"] ) ) {

					if ( "current_abandoned_cart" == $single_record["property"] ) {

						$flag = ( "yes" == $single_record["value"] ) ? true : false;
						break;
					}
				}
			}
		}

		return $flag;
	}

	/*
		* check if the user has empty cart
	*/
	public static function hubwoo_check_for_cart_contents ( $properties ) {

		$flag = false;

		if ( !empty( $properties ) ) {

			foreach ( $properties as $single_record ) {

				if ( !empty( $single_record["property"] ) ) {

					if ( "abandoned_cart_products" == $single_record["property"] ) {

						if ( empty( $single_record["value"] ) ) {

							$flag = true;
							break;
						}
					}
				}
			}
		}

		return $flag;
	}

	/**
	 * Generating access token
	 *
	 * @since    1.0.0
	 */

	public function hubwoo_redirect_from_hubspot() {
		
		
		if( isset( $_GET['code'] ) ) {

			$hapikey = HUBWOO_STARTER_CLIENT_ID;
			$hseckey = HUBWOO_STARTER_SECRET_ID;

			if( $hapikey && $hseckey ) {
				
				if( !Hubwoo::is_valid_client_ids_stored() ) {

					$response = HubWooConnectionMananager::get_instance()->hubwoo_fetch_access_token_from_code( $hapikey, $hseckey);
				}

				$oauth_message = get_option('hubwoo_starter_oauth_success', false );

				if( !isset( $oauth_message ) || !$oauth_message ) {

					$response = HubWooConnectionMananager::get_instance()->hubwoo_fetch_access_token_from_code( $hapikey, $hseckey );
				}

				wp_redirect( admin_url().'admin.php?page=hubwoo&hubwoo_tab=hubwoo_connect' );
			}
		}
	}

	/**
	 * Checking license daily
	 *
	 * @since    1.0.0
	 */

	public function hubwoo_starter_check_licence_daily() {

		global $hubwoo;
		
		$license_key = get_option( 'hubwoo_starter_license_key', false );
		
		$api_params = array(
            'slm_action' 		=> 'slm_check',
            'secret_key' 		=> HUBWOO_STARTER_ACTIVATION_SECRET_KEY,
            'license_key' 		=> $license_key,
            '_registered_domain' => $_SERVER['SERVER_NAME'],
            'item_reference' 	=> urlencode( HUBWOO_STARTER_ITEM_REFERENCE ),
            'product_reference'	=> 'MWBPK-10877'
        );

		$hubwoo->verify_license($api_params);
	}

	/**
	 * Adding more groups and properties for add-on
	 *
	 * @since    1.0.0
	 */
	
	public function hubwoo_update_new_addons_groups_properties() {

		if ( Hubwoo::is_setup_completed() ) {
			
			$new_grp = get_option( 'hubwoo_starter_newgroups_saved', false );
			$hubwoo_lock = get_option( 'hubwoo_lock', false );

			if( $new_grp && !$hubwoo_lock ) {

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

						update_option( "hubwoo_lock", true );

						$groups = array();
						$properties = array();

						$groups = apply_filters( "hubwoo_new_contact_groups", $groups );

						foreach ( $groups as $key => $value ) {

							HubWooConnectionMananager::get_instance()->create_group( $value );

							$properties = apply_filters( "hubwoo_new_active_group_properties", $properties, $value['name'] ); 

							foreach ( $properties as $key1 => $value1 ) {

								$value1[ 'groupName' ] = $value['name'];

								HubWooConnectionMananager::get_instance()->create_property(  $value1 );
							}
						}
						
						update_option( 'hubwoo_starter_newgroups_saved', false );
						update_option( 'hubwoo_lock', false );
					}
				}
			}
		}
	}


	/**
	 * admin alert notice on getting 40X error
	 * 
	 * @since 1.0.0
	 */
	public function hubwoo_dashboard_alert_notice() {

		if ( empty( $_GET['page'] ) || ( !empty( $_GET['page'] && "hubwoo" != $_GET['page']) ) ) {
			return;
		}
		
		if( Hubwoo::is_valid_client_ids_stored() ) {

			$hubwoo_setup = get_option( 'hubwoo_starter_setup_completed', false );
			$hubwoo_alert = get_option( 'hubwoo_starter_alert_param_set', false );

			if( $hubwoo_setup && $hubwoo_alert ) {

				$message = __( "Something went wrong with HubSpot WooCommerce Integration. Send us the generated HubSpot logs via email for better error tracking. ", "hubwoo" );

				if( isset( $_GET['page'] ) && $_GET['page'] == 'hubwoo' ) {

					$message .= '<a class="button-primary" href="javascript:void(0);" id="hubwoo-starter-email-logs">'.__("Email the Error Log", "hubwoo").'</a>';
				}
				else {

					$message .= '<a class="button-primary" href="' . admin_url('admin.php?page=hubwoo') . '">'.__("Click Here", "hubwoo").'</a>';
				}

				Hubwoo::hubwoo_notice( $message );
			}
		}
	}

	/**
	 * new active groups for subscriptions
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_subs_groups( $values ) {

		$values[] = array( 'name' => 'subscriptions_details', 'displayName' => __('Subscriptions Details','hubwoo') );

		return $values;
	}

	/**
	 * new active groups for subscriptions
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_active_subs_groups( $active_groups ) {

		$active_groups[] = 'subscriptions_details';

		return $active_groups;
	}


	/**
	 * updating users/orders list to be updated on hubspot on order status transition
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_update_order_changes( $order_id ) {

		if( !empty( $order_id ) ) {

			$user_id = (int)get_post_meta( $order_id, '_customer_user', true );

			if( $user_id != 0 && $user_id > 0 ) {

				update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
			}
			else {
				$guest_sync_enable = get_option( "hubwoo_starter_guest_sync_enable", "yes" );
				if ( "yes" == $guest_sync_enable ) {
					update_post_meta( $order_id, 'hubwoo_starter_guest_order', 'yes' );
				}
			}
		}
	}

	/**
	 * updating users list to be updated on hubspot when admin changes the role forcefully.
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_add_user_toUpdate( $user_id, $role, $old_roles ) {

		if( !empty( $user_id ) ) {

			update_user_meta( $user_id, 'hubwoo_starter_user_data_change', 'yes' );
		}
	}

	public function hubwoo_order_status_list_enrollment( $order_id ) {
		
		if( !empty( $order_id ) ) {

			$action = current_action();

			$order_actions = get_option( "hubwoo_list_enrollment_order_actions", array() );
			$lists = get_option( "hubwoo_enrolled_order_lists", array() );
			$list_id = '';

			if( count( $order_actions ) ) {

				foreach( $order_actions as $key => $single_action ) {

					if( $single_action == $action ) {

						$list_id = $lists[$key];
						break;
					}
				}
			}

			update_post_meta( $order_id, 'hubwoo_starter_order_in_list', $list_id );
		}
	}


	/**
	 * checking realtime cron in every hour
	 * 
	 * @since 1.0.0
	 */

	public static function hubwoo_starter_check_realtime_cron() {

		if ( !wp_next_scheduled ( 'hubwoo_starter_cron_schedule' ) ) {

            wp_schedule_event( time(), 'mwb-hubwoo-starter-5min', 'hubwoo_starter_cron_schedule' );
        }
	}

	/**
	 * getting next execution for realtime cron, priorities task for old users
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_cron_notification() {

		if ( empty( $_GET['page'] ) || ( !empty( $_GET['page'] && "hubwoo" != $_GET['page']) ) ) {
			return;
		}
		
		$realtime_cron = wp_get_schedule( "hubwoo_starter_cron_schedule" );

		if( !empty( $realtime_cron ) && ( $realtime_cron == "mwb-hubwoo-starter-5min" ) ) {

			$next_run = wp_next_scheduled( "hubwoo_starter_cron_schedule" );

			if( !empty( $next_run ) ) {

				$next_run = date_i18n( 'm/d/Y h:i:s', $next_run, true );

				$message = sprintf( __( "Next Execution of HubSpot WooCommerce Integration Realtime Cron at : %s", "hubwoo" ), "<strong>$next_run</strong>" );
				Hubwoo::hubwoo_notice( $message, 'update' );
			}
		}
		else {

			$message = __( "Real-time User Activity Cron of 5 minutes for HubSpot WooCommerce Integration was not scheduled correctly. Please check working of cron jobs on your site.", "hubwoo" );
			Hubwoo::hubwoo_notice( $message, 'error' );
		}
	}

	public function hubwoo_starter_reauthorize() {

		if( isset( $_GET["action"] ) && $_GET["action"] == "reauth" ) {

			delete_option( "hubwoo_starter_oauth_success" );

			$url = 'https://app.hubspot.com/oauth/authorize';
			$hapikey = HUBWOO_STARTER_CLIENT_ID;
			$hubspot_url = add_query_arg( array(
			    'client_id'			=> $hapikey,
			    'optional_scope'	=> 'integration-sync%20e-commerce',
			    'scope' 			=> 'oauth%20contacts',
			    'redirect_uri' 		=> admin_url().'admin.php'
			), $url );
			
			wp_redirect( $hubspot_url );
			exit();
		}
	}

	/**
	 * fetching total order available and returning count. Also excluding orders that have been synced
	 *
	 * @since 1.0.0
	 */

	public static function hubwoo_count_old_orders() {

		$since_date = get_option( "hubwoo-order-ocs-since-date", date('d-m-Y') );
		$upto_date = get_option( "hubwoo-order-ocs-upto-date", date('d-m-Y') );
		$selected_order_status = get_option( "hubwoo-order-ocs-selected-status", 'wc-completed' );

		$old_orders = get_posts(
			array(
		        'numberposts' 	=> -1,
		        'post_type'   	=> 'shop_order',
		        'post_status' 	=> array( $selected_order_status ),
		        'date_query' 	=> array(
		            'after' 	=> date( 'd-m-Y', strtotime( $since_date ) ),
		            'before' 	=> date( 'd-m-Y', strtotime( $upto_date . ' +1 day' ) ),
		            'inclusive'	=> true, 
		        )
	    	)
    	);

	    $orders_count = 0;

	    if ( is_array( $old_orders ) && !empty( $old_orders ) ) {

	    	$orders_count = count( $old_orders );
	    }

	    return $orders_count;
	}


	/**
	 * woocommerce privacy policy
	 * 
	 * @since 1.0.0
	 */

	public function hubwoo_starter_add_privacy_message() {

		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {

			$content = '<p>' . __( 'We use your email to send your Orders related data over HubSpot.','hubwoo' ) . '</p>';

			$content .='<p>' . __( 'HubSpot is an inbound marketing and sales platform that helps companies attract visitors, convert leads, and close customers.', 'hubwoo' ) . '</p>';

			$content .= '<p>' .  __( 'Please see the ', 'hubwoo' ) . '<a href="https://www.hubspot.com/data-privacy/gdpr" target="_blank" >' . __( 'HubSpot Data Privacy', 'hubwoo' ) . '</a>' .  __( ' for more details.', 'hubwoo' ) . '</p>';

			if ( $content ) {

				wp_add_privacy_policy_content( __( 'HubSpot WooCommerce Integration Starter', 'hubwoo' ), $content );
			}
		}
	}

	public static function hubwoo_split_contact_batch( $contacts ) {

		$contacts_chunk = array_chunk( $contacts, ceil( count( $contacts ) / 2 ) );

		$response_chunk = array();

		if( Hubwoo::is_valid_client_ids_stored() ) {

			$flag = true;

			if( Hubwoo::is_access_token_expired() ) {

				$hapikey = HUBWOO_STARTER_CLIENT_ID;
				$hseckey = HUBWOO_STARTER_SECRET_ID;
				$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey );

				if( !$status ) {

					$flag = false;
				}
			}

			if( $flag ) {

				if( isset( $contacts_chunk[0] ) ) {
					$response_chunk  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $contacts_chunk[0] );
					if ( ( count( $contacts_chunk[0] > 1 ) ) && isset( $response_chunk['status_code'] ) && $response_chunk['status_code'] == 400 ) {
						$response_chunk = self::hubwoo_single_contact_upload( $contacts_chunk[0] );
					}
				}
				if( isset( $contacts_chunk[1] ) ) {
					$response_chunk = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $contacts_chunk[1] );
					if( ( count( $contacts_chunk[1] ) > 1 ) && isset( $response_chunk['status_code'] ) && $response_chunk['status_code'] == 400 ) {
						$response_chunk = self::hubwoo_single_contact_upload( $contacts_chunk[1] );
					}
				}
			}
		}

		return $response_chunk;
	}

	public static function hubwoo_single_contact_upload( $contacts ) {

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
		}

		if ( $flag ) {

			foreach( $contacts as $single_contact ) {

				$response  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( array( $single_contact ) );
			}
		}

		return $response;
	}
}