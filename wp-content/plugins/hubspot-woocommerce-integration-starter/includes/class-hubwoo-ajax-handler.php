<?php

/**
 * Handles all admin ajax requests.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * Handles all admin ajax requests.
 *
 * All the functions required for handling admin ajax requests
 * required by the plugin.
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class HubWooAjaxHandler {

	/**
	 * construct.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		//check oauth access token
		add_action( 'wp_ajax_hubwoo_check_oauth_access_token', array( &$this, 'hubwoo_check_oauth_access_token' ) );
		// get all groups request handler.
		add_action( 'wp_ajax_hubwoo_get_groups', array( &$this, 'hubwoo_get_groups' ) );
		//create a group request handler.
		add_action( 'wp_ajax_hubwoo_create_group_and_property', array( &$this, 'hubwoo_create_group' ) );
		//get group properties.
		add_action( 'wp_ajax_hubwoo_get_group_properties', array( &$this, 'hubwoo_get_group_properties' ) );
		// create property.
		add_action( 'wp_ajax_hubwoo_create_group_property', array( &$this, 'hubwoo_create_group_property' ) );
		//mark setup as completed.
		add_action( 'wp_ajax_hubwoo_setup_completed', array( &$this, 'hubwoo_setup_completed' ) );
		//send mail later.
		add_action( 'wp_ajax_hubwoo_suggest_later', array( &$this, 'hubwoo_suggest_later' ) );
		//send mail.
		add_action( 'wp_ajax_hubwoo_suggest_accept', array( &$this, 'hubwoo_suggest_accept' ) );
		//update properties any time
		add_action( 'wp_ajax_hubwoo_starter_update_properties', array( &$this, 'hubwoo_starter_update_properties' ) );
		//get groups for subscriptions product details
		add_action( 'wp_ajax_hubwoo_get_subs_groups', array( &$this, 'hubwoo_get_subs_groups' ) );
		//get total count of specified users
		add_action( 'wp_ajax_hubwoo_customer_get_count', array( &$this, 'hubwoo_customer_get_count' ) );
		//sync the customers on ajax request
		add_action( 'wp_ajax_hubwoo_customer_sync', array( &$this, 'hubwoo_customer_sync' ) );
		//get started on admin call
		add_action( 'wp_ajax_hubwoo_get_started_call', array( &$this, 'hubwoo_get_started_call' ) );
		//set mail setting by admin
		add_action( "wp_ajax_hubwoo_clear_mail_choice", array( &$this, 'hubwoo_clear_mail_choice' ) );
		//save user choice for group
		add_action( 'wp_ajax_hubwoo_save_user_group_choice', array( &$this, 'hubwoo_save_user_group_choice' ) );
		//clear user choice for group
		add_action( 'wp_ajax_hubwoo_clear_user_group_choice', array( &$this, 'hubwoo_clear_user_group_choice') );
		//get final groups to be created
		add_action( 'wp_ajax_hubwoo_get_groups_to_create', array( &$this, 'hubwoo_get_groups_to_create' ) );
		//mark group setup completed
		add_action( 'wp_ajax_hubwoo_group_setup_completed', array( &$this, 'hubwoo_group_setup_completed' ) );
		//save user choice for fields
		add_action( 'wp_ajax_hubwoo_save_user_field_choice', array( &$this, 'hubwoo_save_user_field_choice' ) );
		//create single single group on admin call
		add_action( 'wp_ajax_hubwoo_create_single_group', array( &$this, 'hubwoo_create_single_group' ) );
		//save user choice for creating properties on HubSpot
		add_action( 'wp_ajax_hubwoo_clear_user_field_choice', array( &$this, 'hubwoo_clear_user_field_choice' ) );
		//save user choice for creating lists
		add_action( 'wp_ajax_hubwoo_save_user_list_choice', array( &$this, 'hubwoo_save_user_list_choice' ) );
		//clear user choice for creating lists
		add_action( 'wp_ajax_hubwoo_clear_user_list_choice', array( &$this, 'hubwoo_clear_user_list_choice' ) );
		//get final lists to be created 
		add_action( 'wp_ajax_hubwoo_get_lists', array( &$this, 'hubwoo_get_lists_to_create' ) );
		//create bulk lists
		add_action( 'wp_ajax_hubwoo_create_list', array( &$this, 'hubwoo_create_list' ) );
		//set list setup completed
		add_action( 'wp_ajax_hubwoo_lists_setup_completed', array( &$this, 'hubwoo_lists_setup_completed' ) );
		//search for order statuses
		add_action( 'wp_ajax_hubwoo_search_for_order_status', array( &$this, 'hubwoo_search_for_order_status' ) );
		//create single property on admin call
		add_action( 'wp_ajax_hubwoo_create_single_property', array( &$this, 'hubwoo_create_single_property' ) ) ;
		//create single list on admin call
		add_action( 'wp_ajax_hubwoo_create_single_list', array( &$this, 'hubwoo_create_single_list' ) );
		//search for user roles
		add_action( 'wp_ajax_hubwoo_search_for_user_roles', array( &$this, 'hubwoo_search_for_user_roles' ) );

		add_action( 'wp_ajax_hubwoo_get_order_list_action_html', array( &$this, 'hubwoo_get_order_list_action_html' ) );

		add_action( 'wp_ajax_hubwoo_get_customer_list_action_html', array( &$this, 'hubwoo_get_customer_list_action_html' ) );

		add_action( 'wp_ajax_hubwoo_get_orders_count', array( &$this, 'hubwoo_get_orders_count' ) );

		add_action( 'wp_ajax_hubwoo_order_sync', array( &$this, 'hubwoo_order_sync' ) );

		add_action( 'wp_ajax_hubwoo_email_the_error_log', array( &$this, 'hubwoo_email_the_error_log' ) );
	}

	/**
	 * send tracking data later
	 * @since 1.0.0
	 */

	public function hubwoo_suggest_later() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( 'hubwoo_starter_suggestions_later', true );
		return true;
	}

	/**
	 * send tracking data now
	 * @since 1.0.0
	 */

	public function hubwoo_suggest_accept() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		$status =  HubWooConnectionMananager::get_instance()->send_clients_details();
		
		if( $status ) {
			update_option( 'hubwoo_starter_suggestions_sent', true );
			echo "success";
		}
		else {
			update_option( 'hubwoo_starter_suggestions_later', true);
			echo "failure";
		}
		wp_die();
	}

	/**
	 * checking access token validity
	 * @since 1.0.0
	 */
	public function hubwoo_check_oauth_access_token() {

		$response = array('status'=>true, 'message'=>__('Success', 'hubwoo') );

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		
		if( Hubwoo::is_access_token_expired() ) {
			
			$hapikey = HUBWOO_STARTER_CLIENT_ID;
			$hseckey = HUBWOO_STARTER_SECRET_ID;
			$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey );
			
			if( !$status ) {

				$response['status'] = false;
				$response['message'] = __( 'Something went wrong, please check your API Keys', 'hubwoo' );
			}
		}

		echo json_encode( $response );
		wp_die();
	}

	/**
	 * get all groups.
	 * 
	 * @return [type] [description]
	 */
	public function hubwoo_get_groups() {

		global $hubwoo;

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		$groups = $hubwoo->hubwoo_get_final_groups();
		$filtered_groups = array();

		if( !empty( $groups ) && count( $groups ) ) {

			foreach( $groups as $single_group ) {

				if( !empty( $single_group['status'] ) && $single_group['status'] == 'created' ) {

					$filtered_groups[] = $single_group['detail'];
				}
			}
		}

		echo json_encode( $filtered_groups );
		wp_die();
	}

	/**
	 * create a group on ajax request.
	 */
	public function hubwoo_create_group() {
		
		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		
		if( isset( $_POST[ 'createNow' ] ) && isset( $_POST[ 'groupDetails' ] ) ){
			
			$createNow = $_POST[ 'createNow' ];
			
			if( $createNow == "group" ){
				
				$groupDetails = $_POST[ 'groupDetails' ];
				
				$response = HubWooConnectionMananager::get_instance()->create_group( $groupDetails );

				if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 ) ) {

					$add_groups = get_option( "hubwoo-starter-groups-created", array() );
					$add_groups[] = $groupDetails['name'];
					update_option( "hubwoo-starter-groups-created", $add_groups );
				}

				echo json_encode( $response );
				wp_die();
			}
		}
	}


	/**
	 * create an group property on ajax request.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_create_group_property() {

		// check the nonce sercurity.
		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		
		if ( isset( $_POST[ 'groupName' ] ) && isset( $_POST[ 'propertyDetails' ] ) ) {

			$propertyDetails = $_POST[ 'propertyDetails' ];

			$propertyDetails[ 'groupName' ] = $_POST[ 'groupName' ];

			$response = HubWooConnectionMananager::get_instance()->create_property( $propertyDetails );

			if( !empty( $response ) ) {

				if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 || $response['status_code'] == 201 ) ) {

					$add_properties = get_option( "hubwoo-starter-properties-created", array() );
					$add_properties[] = $propertyDetails['name'];
					update_option( "hubwoo-starter-properties-created", $add_properties );
				}
			}

			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * get hubwoo group properties by group name.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_get_group_properties() {
		
		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		if( isset( $_POST[ 'groupName' ] ) ) {

			$groupName = $_POST[ 'groupName' ];

			$properties = HubWooContactProperties::get_instance()->_get( 'properties', $groupName );

			$property_user_choice = Hubwoo::hubwoo_user_field_choice();

			if( $property_user_choice == "yes" ) {

				$user_selected_properties = Hubwoo::hubwoo_user_selected_fields();

				$filtered_properties = array();

				if( !empty( $properties ) && count( $properties ) ) {

					foreach( $properties as $single_property ) {

						if( in_array( $single_property['name'], $user_selected_properties ) ) {

							$filtered_properties[] =  $single_property;
						}
					}
				}

				$properties = $filtered_properties;
			}

			echo json_encode( $properties );
		}
		
		wp_die();
	}


	/**
	 * mark setup is completed.
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_setup_completed() {
		// check the nonce sercurity.
		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( 'hubwoo_starter_setup_completed', true );
		update_option( 'hubwoo_starter_version', HUBWOO_STARTER_VERSION );
		update_option( 'hubwoo_starter_fields_setup_completed', true );
		return true;
		wp_die();
	}

	/**
	 * get all the contact lists
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_get_contact_lists() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		$lists = HubWooContactProperties::get_instance()->_get( 'lists' );
		echo json_encode($lists);
		wp_die();
	}

	/**
	 * create a contact list on ajax request
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_create_contact_list(){

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		if( isset( $_POST[ 'createNow' ] ) && isset( $_POST[ 'listDetails' ] ) ){
			// what we have to create
			$createNow = $_POST[ 'createNow' ];
			// if we have to create a list.
			if( $createNow == "list" ){
				// collect the list details.
				$listDetails = $_POST[ 'listDetails' ];
				// let's create the contact list.
				echo json_encode( HubWooConnectionMananager::get_instance()->create_list( $listDetails ) );
				wp_die();
			}
		}
	}

	/**
	 * updating the properties on admin call
	 * @since 1.0.0
	 */
	public function hubwoo_starter_update_properties() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		$update_property = array();

		$group_properties = array(
			"name" 		=> "customer_group",
			"label" 	=> __('Customer Group/ User role', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,   
		);
		$group_properties['groupName'] = "customer_group"; 
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_product_types_bought",
			"label" 	=> __( 'Last Product Types Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = "last_products_bought";
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "product_types_bought",
			"label" 	=> __( 'Product Types Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = "last_products_bought";
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_order_status",
			"label" 	=> __( 'Last Order Status', 'hubwoo' ),
			"type" 		=> "enumeration",  
			"fieldType" => "select",  
			"formField" => false,   
			"options" 	=> HubWooContactProperties::get_instance()->get_order_statuses()
		);
		$group_properties['groupName'] = "order";
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_order_fulfillment_status",
			"label" 	=> __( 'Last Order Fulfillment Status', 'hubwoo' ),
			"type" 		=> "enumeration",  
			"fieldType" => "select",  
			"formField" => false,   
			"options" 	=> HubWooContactProperties::get_instance()->get_order_statuses()
		);
		$group_properties['groupName'] = "order";
		$update_property[] = $group_properties;

		if( Hubwoo::hubwoo_subs_active() ) {

			$group_properties = array(
				"name" 		=> "last_subscription_order_status",
				"label" 	=> __( 'Last Subscription Order Status', 'hubwoo' ),
				"type" 		=> "enumeration",  
				"fieldType" => "select",  
				"formField" => false,
				"options" 	=> HubWooContactProperties::get_instance()->get_subscription_status_options()
			);
			$group_properties[ 'groupName' ] = 'subscriptions_details';
			$update_property[] = $group_properties;
		}

		$group_properties = array(
			"name" 		=> "last_product_bought",
			"label" 	=> __( 'Last Product Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_products_bought",
			"label" 	=> __( 'Last Products Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_products_bought_html",
			"label" 	=> __( 'Last Products Bought HTML', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,  
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "products_bought",
			"label" 	=> __( 'Products Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;

		$group_properties = array( 
			"name" 		=> "last_product_types_bought",
			"label" 	=> __( 'Last Product Types Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "product_types_bought",
			"label" 	=> __( 'Product Types Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'last_products_bought';
		$update_property[] = $group_properties;
		
		$group_properties = array(
			"name" 		=> "last_categories_bought",
			"label" 	=> __( 'Last Categories Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,  
		);
		$group_properties['groupName'] = 'categories_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "categories_bought",
			"label" 	=> __( 'Categories Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,  
		);
		$group_properties['groupName'] = 'categories_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "last_skus_bought",
			"label" 	=> __( 'Last SKUs Bought', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'skus_bought';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "skus_bought",
			"label" 	=> __( 'SKUs Bought', 'hubwoo' ),
			"type"		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false, 
		);
		$group_properties['groupName'] = 'skus_bought';
		$update_property[] = $group_properties;
		
		$group_properties = array(
			"name" 		=> "last_subscription_products",
			"label" 	=> __( 'Last Subscription Products', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'subscriptions_details';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "customer_group",
			"label" 	=> __( 'Customer Group/ User role', 'hubwoo' ),
			"type" 		=> "string",  
			"fieldType" => "textarea",  
			"formField" => false,
		);
		$group_properties['groupName'] = 'customer_group';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "newsletter_subscription",
			"label" 	=> __( 'Accepts Marketing', 'hubwoo' ),
			"type" 		=> "enumeration",  
			"fieldType" => "select",  
			"formField" => true,   
			"options" 	=> HubWooContactProperties::get_instance()->get_user_marketing_action()
		);
		$group_properties['groupName'] = 'customer_group';
		$update_property[] = $group_properties;

		$group_properties = array(
			"name" 		=> "marketing_newsletter",
			"label" 	=> __( 'Marketing Newsletter', 'hubwoo' ),
			"type" 		=> "enumeration",  
			"fieldType" => "checkbox",  
			"formField" => true,   
			"options" 	=> HubWooContactProperties::get_instance()->get_user_marketing_sources()
		);
		$group_properties['groupName'] = 'customer_group';
		$update_property[] = $group_properties;

		$filtered_update_property = array();

		if( !empty( $update_property ) && count( $update_property ) ) {

			global $hubwoo;

			$final_created_fields = $hubwoo->hubwoo_get_created_fields();
			
			foreach( $update_property as $single_update_property ) {

				if( in_array( $single_update_property['name'], $final_created_fields ) ) {

					$filtered_update_property[] = $single_update_property;
				}
			}
		}

		if( get_option( "hubwoo_abncart_added", false ) ) {

			$group_properties = array(
				"name" 		=> "abandoned_cart_products",
				"label" 	=> __( 'Abandoned Cart Products', 'hubwoo' ),
				"type" 		=> "string",
				"fieldType" => "textarea",
				"formfield" => false,
			);
			$group_properties[ 'groupName' ] = 'abandoned_cart';
			$filtered_update_property[] = $group_properties;
		
			$group_properties = array(
				"name" 		=> "abandoned_cart_products_categories",
				"label" 	=> __( 'Abandoned Cart Products Categories', 'hubwoo' ),
				"type" 		=> "string",
				"fieldType" => "textarea",
				"formfield" => false,
			);
			$group_properties[ 'groupName' ] = 'abandoned_cart';
			$filtered_update_property[] = $group_properties;
		
			$group_properties = array(
				"name" 		=> "abandoned_cart_products_skus",
				"label" 	=> __( 'Abandoned Cart Products SKUs', 'hubwoo' ),
				"type" 		=> "string",
				"fieldType" => "textarea",
				"formfield" => false,
			);
			$group_properties[ 'groupName' ] = 'abandoned_cart';
			$filtered_update_property[] = $group_properties;
		}

		$success = true;

		if( count( $filtered_update_property ) ) {

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

					foreach( $filtered_update_property as $single_property ) {

						$success = false;

						$response = HubWooConnectionMananager::get_instance()->update_property( $single_property );

						if( $response[ 'status_code' ] == 200 ) {

							$success = true;
						}
					}
				}
			}
		}

		echo $success;
		
		wp_die();
	}

	/**
	 * getting total count of users based on role set
	 * @since 1.0.0
	 */

	public function hubwoo_customer_get_count() {

		$user_role = get_option( "hubwoo_starter_customers_role_settings", "" );
		$from_date = get_option( "hubwoo-users-from-date", date('d-m-Y') );
		$upto_date = get_option( "hubwoo-users-upto-date", date('d-m-Y') );
		$args = array (
		    'role'          => $user_role,
		    'date_query'    => array(
		        array(
		            'after'     => date( 'd-m-Y', strtotime( $from_date ) ),
		            'before'	=> date( 'd-m-Y', strtotime( $upto_date . ' +1 day' ) ),
		            'inclusive' => true,
		        ),
		    ),
		    'orderby'		=> 'user_registered',
		);
		$user_query = get_users( $args );
		echo json_encode( count( $user_query ) );
		wp_die();
	}


	/**
	 * syncing customers with pre-defined offset
	 * @since 1.0.0
	 */

	public function hubwoo_customer_sync() {

		$offset = isset( $_POST['offset'] ) ? $_POST['offset'] : 0;
		$user_role = get_option( "hubwoo_starter_customers_role_settings", "administrator" );
		$from_date = get_option( "hubwoo-users-from-date", date('d-m-Y') );
		$upto_date = get_option( "hubwoo-users-upto-date", date('d-m-Y') );
		
		$args = array (
		    'role'          => $user_role,
		    'date_query'    => array(
		        array(
		            'after'     => date( 'd-m-Y', strtotime( $from_date ) ),
		            'before'	=> date( 'd-m-Y', strtotime( $upto_date . ' +1 day' ) ),
		            'inclusive' => true,
		        ),
		    ),
		    'offset' 		=> $offset,
		    'number' 		=> 50,
		    'orderby'		=> 'user_registered',
		);

		$user_query = get_users( $args );
		$success = false;		
		$contacts = array();
		
		global $hubwoo;

		if( is_array( $user_query ) && count( $user_query ) ) {

			foreach( $user_query as $single_user ) {

				$hubwoo_customer = new HubWooCustomer( $single_user->ID );
				$email = $hubwoo_customer->get_email();
				if ( empty( $email ) ) {
					continue;
				} 
				$properties = $hubwoo_customer->get_contact_properties();
				$fName = get_user_meta( $single_user->ID, 'first_name', true );
				if ( !empty( $fName ) ) {
					$properties[] = array( 'property' => 'firstname', 'value' => $fName );
				}
				
				$lName = get_user_meta( $single_user->ID, 'last_name', true );
				if ( !empty( $lName ) ) {
					$properties[] = array( 'property' => 'lastname', 'value' => $lName );
				}

				$cName = get_user_meta( $single_user->ID, 'billing_company', true );
				if ( !empty( $cName ) ) {
					$properties[] = array( 'property' => 'company', 'value' => $cName );
				}

				$phone = get_user_meta( $single_user->ID, 'billing_phone', true );
				if ( !empty( $phone ) ) {
					$properties[] = array( 'property' => 'mobilephone', 'value' => $phone );
					$properties[] = array( 'property' => 'phone', 'value' => $phone );
				}

				$city = get_user_meta( $single_user->ID, 'billing_city', true );
				if ( !empty( $city ) ) {
					$properties[] = array( 'property' => 'city', 'value' => $city );
				}

				$state = get_user_meta( $single_user->ID, 'billing_state', true );
				if ( !empty( $state ) ) {
					$properties[] = array( 'property' => 'state', 'value' => $state );
				}

				$country = get_user_meta( $single_user->ID, 'billing_country', true );
				if ( !empty( $country ) ) {
					$properties[] = array( 'property' => 'country', 'value' => $country );
				}
				
				$address1 = get_user_meta( $single_user->ID, 'billing_address_1', true );
				$address2 = get_user_meta( $single_user->ID, 'billing_address_2', true );

				if ( !empty( $address1 ) || !empty( $address2 ) ) {
					$address = $address1 . " " . $address2;
					$properties[] = array( 'property' => 'address', 'value' => $address );
				}

				$postCode = get_user_meta( $single_user->ID, 'billing_postcode', true );
				if ( !empty( $postCode ) ) {
					$properties[] = array( 'property' => 'zip', 'value' => $postCode );
				}
				$properties_data = array( 'email' => $email, 'properties' => $properties );
				$contacts[] = $properties_data;
			}
		}

		$flag = true;

		$response = array( 'response' => __( 'Sorry, something went wrong or no users find for specified role. Please try again', 'hubwoo' ) );
			
		if( is_array( $contacts ) && count( $contacts ) ) {

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
					
					$success = false;
					$response  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $contacts );

					if ( ( count( $contacts ) > 1 ) && isset( $response['status_code'] ) && $response['status_code'] == 400 ) {

						$response = Hubwoo_Admin::hubwoo_split_contact_batch( $contacts );
					}
				}
			}
		}

		echo json_encode( $response );

		wp_die();
	}

	/**
	 * get started on admin call
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_started_call() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_get_started", true );
		return true;
	}

	/**
	 * clear mail sending choice
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_clear_mail_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		delete_option( "hubwoo_starter_suggestions_later" );
		return true;
	}

	/**
	 * save user choice for plugin development mail
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_save_user_group_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_select_groups", $_POST["choice"] );
		return true;
	}

	/**
	 * clear user choice for creating groups on hubspot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_clear_user_group_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		delete_option( "hubwoo_starter_select_groups" );
		return true;
	}

	/**
	 * get groups to be created on HubSpot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_groups_to_create() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		$groups = HubWooContactProperties::get_instance()->_get( 'groups' );

		$hubwoo_group_choice = Hubwoo::hubwoo_user_group_choice();

		$filtered_groups = array();

		if( $hubwoo_group_choice == "yes" ) {

			$hubwoo_selected_groups = Hubwoo::hubwoo_user_selected_groups();

			if( count( $groups ) ) {

				foreach( $groups as $single_group ) {

					if( isset( $single_group["name"] ) ) {

						if( in_array( $single_group["name"], $hubwoo_selected_groups ) ) {

							$filtered_groups[] = $single_group;
						}
					}
				}

				echo json_encode( $filtered_groups );
			}
		}
		else {

			echo json_encode( $groups );
		}

		wp_die();
	}

	/**
	 * get lists to be created on husbpot 
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_lists_to_create() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		$lists = HubWooContactProperties::get_instance()->_get( 'lists' );

		$hubwoo_list_choice = Hubwoo::hubwoo_user_list_choice();

		$filtered_lists = array();

		global $hubwoo;

		if( $hubwoo_list_choice == "yes" ) {

			$hubwoo_selected_lists = Hubwoo::hubwoo_user_selected_lists();

			if( count( $lists ) ) {

				foreach( $lists as $single_list ) {

					if( isset( $single_list["name"] ) ) {

						if( in_array( $single_list["name"], $hubwoo_selected_lists ) ) {

							$filtered_lists[] = $single_list;
						}
					}
				}
			}
		}
		else {

			if( is_array( $lists ) && count( $lists ) ) {

				foreach( $lists as $key => $single_list ) {

					$list_filter_created = $hubwoo->is_list_filter_created( $single_list['filters'] );

					if( $list_filter_created ) {

						$filtered_lists[] = $single_list;
					}
				}
			}
		}

		echo json_encode( $filtered_lists );
		wp_die();
	}

	/**
	 * mark group setup as completed
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_group_setup_completed() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_groups_setup_completed", true );
		update_option( "hubwoo_starter_groups_version", HUBWOO_STARTER_VERSION );
		return true;
	}

	/**
	 * save user choice for creating properties on HubSpot
	 *
	 * @since 1.0.0
	 */
	public function hubwoo_save_user_field_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_select_fields", $_POST["choice"] );
		return true;
	}

	/**
	 * clear user choice for creating properties
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_clear_user_field_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		delete_option( "hubwoo_starter_select_fields" );
		return true;
	}

	/**
	 * create single group on hubspot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_create_single_group() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		$groupName = $_POST["name"];
		$groups = HubWooContactProperties::get_instance()->_get( 'groups' );
		$groupDetails = '';

		if( is_array( $groups ) && count( $groups ) ) {

			foreach( $groups as $single_group ) {

				if( $single_group['name'] == $groupName ) {

					$groupDetails = $single_group;
					break;
				}
			}
		}

		if( !empty( $groupDetails ) ) {

			$response = HubWooConnectionMananager::get_instance()->create_group( $groupDetails );
		}

		if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 ) ) {
				
			$pre_created_groups = Hubwoo::hubwoo_user_selected_groups();
			$pre_created_groups[] = $groupName;
			update_option( 'hubwoo_starter_selected_groups', $pre_created_groups );

			$add_groups = get_option( "hubwoo-starter-groups-created", array() );
			$add_groups[] = $groupDetails['name'];
			update_option( "hubwoo-starter-groups-created", $add_groups );
		}

		echo json_encode( $response );
		wp_die();
	}

	/**
	 * save user choice for creating lists.
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_save_user_list_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_select_lists", $_POST["choice"] );
		return true;
	}

	/**
	 * clear user choice for creating lists on HubSpot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_clear_user_list_choice() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		delete_option("hubwoo_starter_select_lists");
		return true;
	}

	/**
	 * create bulk lists on hubspot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_create_list() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		if( isset( $_POST['listDetails'] ) ) {

			$listDetails = $_POST['listDetails'];

			$response = HubWooConnectionMananager::get_instance()->create_list( $listDetails );

			if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 ) ) {
				
				$pre_created_lists = Hubwoo::hubwoo_user_selected_lists();
				$pre_created_lists[] = $listDetails["name"];
				update_option( 'hubwoo_starter_selected_lists', $pre_created_lists );

				$add_lists = get_option( "hubwoo-starter-lists-created", array() );
				$add_lists[] = $listDetails['name'];
				update_option( "hubwoo-starter-lists-created", $add_lists );
			}
		}

		echo json_encode( $response );
		wp_die();
	}

	/**
	 * mark list setup as completed
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_lists_setup_completed() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );
		update_option( "hubwoo_starter_lists_setup_completed", true );
		return true;
	}

	/**
	 * ajax search for order statuses
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_search_for_order_status() {

		$order_statuses = wc_get_order_statuses();

		$return = array();

		if( !empty( $order_statuses ) ) {

			foreach ( $order_statuses as $status_key => $single_status ) {

				$return[] = array( $status_key, $single_status );
			}
		}

		echo json_encode( $return );

		wp_die();
	}

	/**
	 * create single property on HubSpot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_create_single_property() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		if( isset( $_POST["name"] ) && $_POST["group"] ) {

			$groupName = $_POST["group"];
			$propertyName = $_POST["name"];

			$properties = HubWooContactProperties::get_instance()->_get( 'properties', $groupName );

			if( !empty( $properties ) && count( $properties ) ) {

				foreach( $properties as $single_property ) {

					if( !empty( $single_property["name"] ) && $single_property["name"] == $propertyName ) {

						$propertyDetails = $single_property;
						break;
					}
				}	
			}

			if( !empty( $propertyDetails ) ) {

				$propertyDetails['groupName'] = $groupName;

				$response = HubWooConnectionMananager::get_instance()->create_property(  $propertyDetails );
			}

			if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 ) ) {
				
				$pre_created_fields = Hubwoo::hubwoo_user_selected_fields();
				$pre_created_fields[] = $propertyName;
				update_option( 'hubwoo_starter_selected_properties', $pre_created_fields );

				$add_properties = get_option( "hubwoo-starter-properties-created", array() );
				$add_properties[] = $propertyDetails['name'];
				update_option( "hubwoo-starter-properties-created", $add_properties );
			}

			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * create single list on hubspot
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_create_single_list() {

		check_ajax_referer( 'hubwoo_security', 'hubwooSecurity' );

		if( isset( $_POST["name"] ) ) {

			$listName = $_POST["name"];

			$lists = HubWooContactProperties::get_instance()->_get( 'lists' );

			if( !empty( $lists ) && count( $lists ) ) {

				foreach( $lists as $single_list ) {

					if( !empty( $single_list["name"] ) && $single_list["name"] == $listName ) {

						$listDetails = $single_list;
						break;
					}
				}	
			}

			if( !empty( $listDetails ) ) {

				$response = HubWooConnectionMananager::get_instance()->create_list(  $listDetails );
			}

			if( isset( $response['status_code'] ) && ( $response['status_code'] == 200 || $response['status_code'] == 409 ) ) {
				
				$pre_created_lists = Hubwoo::hubwoo_user_selected_lists();
				$pre_created_lists[] = $propertyName;
				update_option( 'hubwoo_starter_selected_lists', $pre_created_lists );

				$add_lists = get_option( "hubwoo-starter-lists-created", array() );
				$add_lists[] = $listName;
				update_option( "hubwoo-starter-lists-created", $add_lists );
			}

			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * ajax search for search for user roles
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_search_for_user_roles() {

		global $hubwoo;

		$user_roles = $hubwoo->hubwoo_get_user_roles();

		$return = array();

		if( !empty( $user_roles ) ) {

			foreach ( $user_roles as $user_key => $single_role ) {

				$return[] = array( $user_key, $single_role );
			}
		}

		echo json_encode( $return );

		wp_die();
	}

	/**
	 * html for order activity based enrollment
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_order_list_action_html() {

		global $hubwoo;
		$all_lists = get_option( "hubwoo_starter_all_lists", array() );
		$key = $_POST["key"];
		$html = '<tr data-id="' . $key . '" class="order-list-rule"><td class="forminp forminp-text"><select name="hubwoo_list_enrollment_order_actions[]">' . $hubwoo::hubwoo_get_selected_order_action() . '</select></td><td class="forminp forminp-text"><select name="hubwoo_enrolled_order_lists[]">' . $hubwoo::hubwoo_get_selected_list( '', $all_lists ) . '</select></td><td><img class="order-list-rule-del" data-id="' . $key . '" height="20px" width="20px" src="' . HUBWOO_STARTER_URL . 'admin/images/delete.png"/></td></tr>';
		echo $html;
		wp_die();
	}

	/**
	 * html for customer activity based enrollment
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_customer_list_action_html() {

		global $hubwoo;
		$all_lists = get_option( "hubwoo_starter_all_lists", array() );
		$key = $_POST["key"];
		$html = '<tr data-id="' . $key . '" class="customer-list-rule"><td class="forminp forminp-text"><select name="hubwoo_list_enrollment_customer_actions[]">'.$hubwoo::hubwoo_get_selected_customer_action().'</select></td><td class="forminp forminp-text"><select name="hubwoo_enrolled_customer_lists[]">'.$hubwoo::hubwoo_get_selected_list('', $all_lists).'</select></td><td><img class="customer-list-rule-del" data-id="' . $key . '" height="20px" width="20px" src="' . HUBWOO_STARTER_URL . 'admin/images/delete.png"/></td></tr>';

		echo $html;
		wp_die();
	}

	/**
	 * get total order count
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_orders_count() {

		$order_count = Hubwoo_Admin::hubwoo_count_old_orders();
		echo $order_count;
		wp_die();
	}

	public function hubwoo_order_sync() {

		$offset = isset( $_POST['offset'] ) ? $_POST['offset'] : 0;
		$since_date = get_option( "hubwoo-order-ocs-since-date", date('d-m-Y') );
		$upto_date = get_option( "hubwoo-order-ocs-upto-date", date('d-m-Y') );

		$selected_order_status = get_option( "hubwoo-order-ocs-selected-status", 'wc-completed' );

		$orders = get_posts(
			array(
		        'numberposts' => 50,
		        'post_type'   => 'shop_order',
		        'post_status' => array( $selected_order_status ),
		        'date_query'  => array(
		            'after'  	=> date( 'd-m-Y', strtotime( $since_date ) ),
		            'before' 	=> date( 'd-m-Y', strtotime( $upto_date . ' +1 day' ) ),
		            'inclusive'	=> true,
		        ),
		        'offset' 	=> $offset,
	    	)
    	);
		
		$success = false;		
		$contacts = array();
		$guest_user_properties = '';
		$properties = '';
		
		global $hubwoo;

		if( is_array( $orders ) && count( $orders ) ) {

	    	foreach( $orders as $key => $wc_order_object ) {

	    		if( !isset( $wc_order_object->ID ) || empty( $wc_order_object->ID ) ) {

	    			continue;
	    		}
	    		else {

	    			$order_id = $wc_order_object->ID;

	    			if( !empty( $order_id ) ){

	    				$user_id = get_post_meta( $order_id, '_customer_user', true );

						if( $user_id != 0 && $user_id > 0 ) {

							$hubwoo_customer = new HubWooCustomer( $user_id );
							$email = $hubwoo_customer->get_email();
							if ( empty( $email ) ) {
								continue;
							}
							$properties = $hubwoo_customer->get_contact_properties();
							$fName = get_user_meta( $user_id, 'first_name', true );
							if ( !empty( $fName ) ) {
								$properties[] = array( 'property' => 'firstname', 'value' => $fName );
							}
							
							$lName = get_user_meta( $user_id, 'last_name', true );
							if ( !empty( $lName ) ) {
								$properties[] = array( 'property' => 'lastname', 'value' => $lName );
							}

							$cName = get_user_meta( $user_id, 'billing_company', true );
							if ( !empty( $cName ) ) {
								$properties[] = array( 'property' => 'company', 'value' => $cName );
							}

							$phone = get_user_meta( $user_id, 'billing_phone', true );
							if ( !empty( $phone ) ) {
								$properties[] = array( 'property' => 'mobilephone', 'value' => $phone );
								$properties[] = array( 'property' => 'phone', 'value' => $phone );
							}

							$city = get_user_meta( $user_id, 'billing_city', true );
							if ( !empty( $city ) ) {
								$properties[] = array( 'property' => 'city', 'value' => $city );
							}

							$state = get_user_meta( $user_id, 'billing_state', true );
							if ( !empty( $state ) ) {
								$properties[] = array( 'property' => 'state', 'value' => $state );
							}

							$country = get_user_meta( $user_id, 'billing_country', true );
							if ( !empty( $country ) ) {
								$properties[] = array( 'property' => 'country', 'value' => $country );
							}
							
							$address1 = get_user_meta( $user_id, 'billing_address_1', true );
							$address2 = get_user_meta( $user_id, 'billing_address_2', true );

							if ( !empty( $address1 ) || !empty( $address2 ) ) {
								$address = $address1 . " " . $address2;
								$properties[] = array( 'property' => 'address', 'value' => $address );
							}

							$postCode = get_user_meta( $user_id, 'billing_postcode', true );
							if ( !empty( $postCode ) ) {
								$properties[] = array( 'property' => 'zip', 'value' => $postCode );
							}
							$properties = apply_filters( 'hubwoo_map_new_properties', $properties, $user_id );
							$properties_data = array( 'email' => $email, 'properties' => $properties );
							$contacts[] = $properties_data;
						}
						else {

							$order = new WC_Order( $order_id );
							$customer_email = $order->get_billing_email();
							if ( empty( $customer_email ) ) {
								continue;
							}
							$guest_order_callback = new HubwooGuestOrdersManager( $order_id );
							$guest_user_properties = $guest_order_callback->get_order_related_properties( $order_id, $customer_email );
							$guest_user_properties = $hubwoo->hubwoo_filter_contact_properties( $guest_user_properties );
							$fName = get_post_meta( $order_id, '_billing_first_name', true );
							if ( !empty( $fName ) ) {
								$guest_user_properties[] = array( 'property' => 'firstname', 'value' => $fName );
							}
							
							$lName = get_post_meta( $order_id, '_billing_last_name', true );
							if ( !empty( $lName ) ) {
								$guest_user_properties[] = array( 'property' => 'lastname', 'value' => $lName );
							}

							$cName = get_post_meta( $order_id, '_billing_company', true );
							if ( !empty( $cName ) ) {
								$guest_user_properties[] = array( 'property' => 'company', 'value' => $cName );
							}

							$city = get_post_meta( $order_id, '_billing_city', true );
							if ( !empty( $city ) ) {
								$guest_user_properties[] = array( 'property' => 'city', 'value' => $city );
							}
							
							$state = get_post_meta( $order_id, '_billing_state', true );
							if ( !empty( $state ) ) {
								$guest_user_properties[] = array( 'property' => 'state', 'value' => $state );
							}
							
							$country = get_post_meta( $order_id, '_billing_country', true );
							if ( !empty( $country ) ) {
								$guest_user_properties[] = array( 'property' => 'country', 'value' => $country );
							}
						
							$address1 = get_post_meta( $order_id, '_billing_address_1', true );
							$address2 = get_post_meta( $order_id, '_billing_address_2', true );
							if ( !empty( $address1 ) || !empty( $address2 ) ) {
								$address = $address1 . " " . $address2;
								$guest_user_properties[] = array( 'property' => 'address', 'value' => $address );
							}

							$zip = get_post_meta( $order_id, '_billing_postcode', true );
							if ( !empty( $zip ) ) {
								$guest_user_properties[] = array( 'property' => 'zip', 'value' => $zip );
							}

							$guest_phone = get_post_meta( $order_id, '_billing_phone', true );

							if ( !empty( $guest_phone ) ) {
								$guest_user_properties[] = array( 'property' => 'mobilephone', 'value' => $guest_phone );
								$guest_user_properties[] = array( 'property' => 'phone', 'value' => $guest_phone );
							}
							
							$guest_user_properties_data = array( 'email' => $customer_email, 'properties' => $guest_user_properties );
							$contacts[] = $guest_user_properties_data;
						}
	    			}
	    		}
	    	}
	    }

		$flag = true;

		$response = array( 'response' => __( 'Sorry, something went wrong or no users find for specified role. Please try again', 'hubwoo' ) );
			
		if( is_array( $contacts ) && count( $contacts ) ) {

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
					
					$success = false;
					
					$response  = HubWooConnectionMananager::get_instance()->create_or_update_contacts( $contacts );

					if ( ( count( $contacts ) > 1 ) && isset( $response['status_code'] ) && $response['status_code'] == 400 ) {
						
						$response = Hubwoo_Admin::hubwoo_split_contact_batch( $contacts );
					}
				}
			}
		}

		echo json_encode( $response );

		wp_die();
	}

	public function hubwoo_email_the_error_log() {

		$log_dir = WC_LOG_DIR.'hubwoo-starter-logs.log';
		$attachments = array( $log_dir );
		$to 		= 'integrations@makewebbetter.com';
		$subject  	= 'HubSpot Starter/Basic Error Logs';
		$headers  	= array('Content-Type: text/html; charset=UTF-8');
		$message  	= 'admin email: ' . get_option( "admin_email", "" ) . '<br/>';
		$status 	= wp_mail( $to, $subject, $message, $headers, $attachments );

		if( $status == 1 ) {

			$status = 'success';
		}
		else {

			$status = 'failure';
		}

		update_option( "hubwoo_starter_alert_param_set", false );
		echo $status;
		wp_die();
	}  
}

new HubWooAjaxHandler();