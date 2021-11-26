<?php 
/**
 * Manage all contact properties.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * Manage all contact properties.
 *
 * Provide a list of functions to manage all the information
 * about contacts properties and lists along with option to 
 * change/update the mapping field on hubspot.
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */

class HubWooContactProperties{

	/**
	 * Contact Property Groups.
	 *
	 * @access private
	 * @since 1.0.0
	 */
	private $groups;

	/**
	 * Contact Properties.
	 *
	 * @access private
	 * @since 1.0.0
	 */
	private $properties;

	/**
	 * Contact Lists.
	 *
	 * @access private
	 * @since 1.0.0
	 */
	private $lists;


	/**
	 * HubWooContactProperties Instance.
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main HubWooContactProperties Instance.
	 *
	 * Ensures only one instance of HubWooContactProperties is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return HubWooContactProperties - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Define the contact prooperties related functionality.
	 *
	 * Set the contact groups and properties that we are going to use
	 * for creating/updating the contact information for our tacking purpose
	 * and providing other developers to add there field and group for tracking
	 * too by simply using our hooks.
	 *
	 * @access public
	 * @since    1.0.0
	 */
	public function __construct(){

		$this->groups 		= $this->_set( 'groups' );
		$this->properties 	= $this->_set( 'properties' );
		$this->lists 		= $this->_set( 'lists' );
	}

	/**
	 * get groups/properties.
	 * 
	 * @param  string 		  groups/properties
	 * @return array          Array of groups/properties information.
	 */
	public function _get( $option, $groupName="" ) {

		if( $option == "groups" ) {

			return $this->groups;
		}
		elseif( $option == "properties" ) {
			
			if( !empty( $groupName ) && isset( $this->properties[ $groupName ] ) ) {

				return $this->properties[ $groupName ];
			}

			return $this->properties;
		}
		elseif( $option == "lists" ) {

			return $this->lists;
		}
	}

	/**
	 * get an array of required option.
	 * 
	 * @param  String   	$option  		the identifier.
	 * @return Array  		An array of values.	
	 * @since 1.0.0		     
	 */
	private function _set( $option ) {

		$values = array();

		//if we are looking for groups, let us add our predefined groups.
		if( $option == 'groups' ) {

			// customer details
			$values[] = array( 'name' => 'customer_group', 'displayName' => __( 'Customer Group', 'hubwoo' ) );
			// shopping cart details.
			$values[] = array( 'name' => 'shopping_cart_fields', 'displayName' =>  __('Shopping Cart Information', 'hubwoo' ) );
			// order details.
			$values[] = array( 'name' => 'order', 'displayName' => __( 'Order Information', 'hubwoo' ) );
			// products bought details
			$values[] = array( 'name' => 'last_products_bought', 'displayName' => __( 'Products Bought', 'hubwoo' ) );
			// categories bought details
			$values[] = array( 'name' => 'categories_bought', 'displayName' => __( 'Categories Bought', 'hubwoo' ) );
			// RFM details.
			$values[] = array( 'name' => 'rfm_fields', 'displayName' => __( 'RFM Information', 'hubwoo' ) );
			//skus bought details
			$values[] = array( 'name' => 'skus_bought', 'displayName' => __('SKUs Bought', 'hubwoo' ) );			
		// if we are looking for properties.
		}
		elseif( $option == 'properties' ) {

			// let's check for all active tracking groups and get there associated properties.
			$values = $this->get_all_active_groups_properties();
		}
		elseif( $option == 'lists' ) {

			$values = $this->get_all_active_lists();
		}

		// add your values to the either groups or properties.
		return apply_filters( 'hubwoo_contact_' . $option, $values );
	}

	/*
		woocommerce subscription groups 
	*/
	public static function _get_subs_groups( $values = array() ) {

		$values[] = array( 'name' => 'subscriptions_details', 'displayName' => __('Subscriptions Details','hubwoo' ) );
		return $values;
	}

	/**
	 * check for the active groups and get there properties.
	 * 
	 * @return Array Properties array with there associated group.
	 * @since 1.0.0
	 */
	private function get_all_active_groups_properties(){

		$active_groups_properties = array();
		//get all the active groups.
		$active_groups = $this->get_active_groups();

		//check if we get active groups in the form of array, and has groups.
		if( is_array( $active_groups ) && count( $active_groups ) ){

			foreach( $active_groups as $active_group ){

				if( !empty( $active_group ) && !is_array( $active_group ) ){

					$active_groups_properties[ $active_group ] = $this->_get_group_properties( $active_group );
				}
			}
		}
		// add your active group properties if you want.
		return apply_filters( 'hubwoo_active_groups_properties', $active_groups_properties );
	}

	/**
	 * Filter extra properties to avaoid error on hubspot
	 * 
	 * @return only created properties
	 * @since 1.0.0
	 */

	public function hubwoo_get_filtered_properties() {

		$filtered_properties = array();

		$all_filtered_properties = array();

		$active_groups = $this->get_active_groups();

		if( is_array( $active_groups ) && count( $active_groups ) ) {

			foreach( $active_groups as $active_group ) {

				if( !empty( $active_group ) && !is_array( $active_group ) ) {

					$active_groups_properties[ $active_group ] = $this->_get_group_properties( $active_group );
				}
			}
		}

		if( !empty( $active_groups_properties ) ) {

			$group_name = '';

			$created_properties = get_option( "hubwoo-starter-properties-created", array() );

			foreach( $active_groups_properties as $group_name_key => $single_group_property ) {

				$group_name = $group_name_key;
				
				$filtered_properties = array();

				foreach( $single_group_property as $single_property ) {

					if( isset( $single_property['name'] ) && in_array( $single_property['name'], $created_properties ) ) {

						$filtered_properties[] = $single_property;
					}
				}

				$all_filtered_properties[$group_name] = $filtered_properties;
			}
		}

		return apply_filters( 'hubwoo_active_groups_properties', $all_filtered_properties );
	}  


	/**
	 * Filter for active groups only.
	 * 
	 * @return Array active group names.
	 * @since 1.0.0
	 */
	private function get_active_groups(){

		$active_groups = array();

		$all_groups = $this->_get( 'groups' );

		if( is_array( $all_groups ) && count( $all_groups ) ){

			foreach( $all_groups as $group_details ){

				$group_name = isset( $group_details[ 'name' ] ) ? $group_details[ 'name' ] : '';

				if( !empty( $group_name ) ){

					$created_groups = get_option( "hubwoo-starter-groups-created", array() );

					$is_active = false;

					if( in_array( $group_name, $created_groups ) ) {

						$is_active = true;
					}

					if( $is_active ) {

						$active_groups[] = $group_name;
					}
				}
			}
		}
		// let's developer manage there groups seperately if they want.
		return apply_filters( 'hubwoo_active_groups', $active_groups );
	}


	/**
	 * get all the groups properties.
	 * 
	 * @param   string     $group_name     name of the existed valid hubspot contact properties group.
	 * @return  Array      Properties array.
	 * @since 1.0.0
	 */
	private function _get_group_properties( $group_name ) {

		$group_properties = array();
		
		if( !empty( $group_name ) ) {

			if ( $group_name == "customer_group" ) {

				$group_properties[] = array(
					"name" 		=> "customer_group",
					"label" 	=> __( 'Customer Group/ User role', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "newsletter_subscription",
					"label" 	=> __( 'Accepts Marketing', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => true,   
					"options" 	=> $this->get_user_marketing_action()
				);

				$group_properties[] = array(
					"name" 		=> "marketing_newsletter",
					"label" 	=> __( 'Marketing Newsletter', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "checkbox",  
					"formField" => true,   
					"options" 	=> $this->get_user_marketing_sources(),
				);

				$group_properties[] = array(
					"name" 		=> "shopping_cart_customer_id",
					"label" 	=> __( 'Shopping Cart ID', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,   
				);
			}
			elseif ( $group_name == "shopping_cart_fields" ) {

				$group_properties[] = array(
					"name" 		=> "shipping_address_line_1",
					"label" 	=> __( 'Shipping Address Line 1', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);
				
				$group_properties[] = array(
					"name" 		=> "shipping_address_line_2",
					"label" 	=> __( 'Shipping Address Line 2', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "shipping_city",
					"label" 	=> __( 'Shipping City', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "shipping_state",
					"label" 	=> __( 'Shipping State', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "shipping_postal_code",
					"label" 	=> __( 'Shipping Postal Code', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "shipping_country",
					"label" 	=> __( 'Shipping Country', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_address_line_1",
					"label" 	=> __( 'Billing Address Line 1', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_address_line_2",
					"label" 	=> __( 'Billing Address Line 2', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_city",
					"label" 	=> __( 'Billing City', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_state",
					"label" 	=> __( 'Billing State', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_postal_code",
					"label" 	=> __( 'Billing Postal Code', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);

				$group_properties[] = array(
					"name" 		=> "billing_country",
					"label" 	=> __( 'Billing Country', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text", 
					"formField" => true, 
				);
			}
			elseif ( $group_name == "last_products_bought" ) {

				$group_properties[] = array(
					"name" 		=> "last_product_bought",
					"label" 	=> __( 'Last Product Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_product_types_bought",
					"label" 	=> __( 'Last Product Types Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought",
					"label" 	=> __( 'Last Products Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_html",
					"label" 	=> __( 'Last Products Bought HTML', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,  
				);

				$group_properties[] = array(
					"name" 		=> "last_total_number_of_products_bought",
					"label" 	=> __( 'Last Total Number Of Products Bought', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false, 
				);

				$group_properties[] = array(
					"name" 		=> "product_types_bought",
					"label" 	=> __( 'Product Types Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "products_bought",
					"label" 	=> __( 'Products Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "total_number_of_products_bought",
					"label" 	=> __( 'Total Number Of Products Bought', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false, 
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_1_image_url",
					"label" 	=> __( 'Last Products Bought Product 1 Image URL', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_1_name",
					"label" 	=> __( 'Last Products Bought Product 1 Name', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 					=> "last_products_bought_product_1_price",
					"label" 				=> __( 'Last Products Bought Product 1 Price', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",
					"showCurrencySymbol" 	=> true,  
					"formField" 			=> false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_1_url",
					"label" 	=> __( 'Last Products Bought Product 1 Url', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_2_image_url",
					"label" 	=> __( 'Last Products Bought Product 2 Image URL', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_2_name",
					"label" 	=> __( 'Last Products Bought Product 2 Name', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false, 
				);

				$group_properties[] = array(
					"name" 					=> "last_products_bought_product_2_price",
					"label" 				=> __( 'Last Products Bought Product 2 Price', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true    
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_2_url",
					"label" 	=> __( 'Last Products Bought Product 2 Url', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_3_image_url",
					"label" 	=> __( 'Last Products Bought Product 3 Image URL', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_3_name",
					"label" 	=> __( 'Last Products Bought Product 3 Name', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 					=> "last_products_bought_product_3_price",
					"label" 				=> __( 'Last Products Bought Product 3 Price', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true      
				);

				$group_properties[] = array(
					"name" 		=> "last_products_bought_product_3_url",
					"label" 	=> __( 'Last Products Bought Product 3 Url', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);
			}
			elseif ( $group_name == "order" ) {

				$group_properties[] = array(
					"name" 		=> "last_order_status",
					"label" 	=> __( 'Last Order Status', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,   
					"options" 	=> $this->get_order_statuses()
				);

				$group_properties[] = array(
					"name" 		=> "last_order_fulfillment_status",
					"label" 	=> __( 'Last Order Fulfillment Status', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,   
					"options" 	=> $this->get_order_statuses()
				);

				$group_properties[] = array(
					"name" 		=> "last_order_tracking_number",
					"label" 	=> __( 'Last Order Tracking Number', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_order_tracking_url",
					"label" 	=> __( 'Last Order Tracking URL', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_order_shipment_date",
					"label" 	=> __( 'Last Order Shipment Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_order_order_number",
					"label" 	=> __( 'Last Order Number', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "last_order_currency",
					"label" 	=> __( 'Last Order Currency', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "text",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "total_number_of_current_orders",
					"label" 	=> __( 'Total Number of Current Orders', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,   
				);
			}
			elseif ( $group_name == "rfm_fields" ) {

				$group_properties[] = array(
					"name" 					=> "total_value_of_orders",
					"label" 				=> __( 'Total Value of Orders', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true,   
				);

				$group_properties[] = array(
					"name" 					=> "average_order_value",
					"label" 				=> __( 'Average Order Value', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true,     
				);

				$group_properties[] = array(
					"name" 		=> "total_number_of_orders",
					"label" 	=> __( 'Total Number of Orders', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 					=> "first_order_value",
					"label" 				=> __( 'First Order Value', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true,     
				);

				$group_properties[] = array(
					"name" 		=> "first_order_date",
					"label" 	=> __( 'First Order Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 					=> "last_order_value",
					"label" 				=> __( 'Last Order Value', 'hubwoo' ),
					"type" 					=> "number",  
					"fieldType" 			=> "number",  
					"formField" 			=> false,
					"showCurrencySymbol" 	=> true,      
				);

				$group_properties[] = array(
					"name" 		=> "last_order_date",
					"label" 	=> __( 'Last Order Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "average_days_between_orders",
					"label" 	=> __( 'Average Days Between Orders', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "account_creation_date",
					"label" 	=> __( 'Account Creation Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,   
				);

				$group_properties[] = array(
					"name" 		=> "monetary_rating",
					"label" 	=> __( 'Monetary Rating', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options" 	=> $this->get_rfm_rating(),
				);

				$group_properties[] = array(
					"name" 		=> "order_frequency_rating",
					"label" 	=> __( 'Order Frequency Rating', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options" 	=> $this->get_rfm_rating(),   
				);

				$group_properties[] = array(
					"name" 		=> "order_recency_rating",
					"label" 	=> __( 'Order Recency Rating', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options" 	=> $this->get_rfm_rating(),   
				);
			}
			elseif ( $group_name == "categories_bought" ) {

				$group_properties[] = array(
					"name" 		=> "last_categories_bought",
					"label" 	=> __( 'Last Categories Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,  
				);

				$group_properties[] = array(
					"name" 		=> "categories_bought",
					"label" 	=> __( 'Categories Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,  
				);
			}
			elseif ( $group_name == "skus_bought" ) {

				$group_properties[] = array(
					"name" 		=> "last_skus_bought",
					"label" 	=> __( 'Last SKUs Bought', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "skus_bought",
					"label" 	=> __( 'SKUs Bought', 'hubwoo' ),
					"type"		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false, 
				);	
			}
			elseif ( $group_name == "subscriptions_details" ) {

				$group_properties[] = array(
					"name" 		=> "last_subscription_order_number",
					"label" 	=> __( 'Last Subscription Order Number', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_parent_order_number",
					"label" 	=> __( 'Last Subscription Parent Order Number', 'hubwoo' ),
					"type" 		=> "number",  
					"fieldType" => "number",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_order_status",
					"label" 	=> __( 'Last Subscription Order Status', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options" 	=> $this->get_subscription_status_options() 
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_order_creation_date",
					"label" 	=> __( 'Last Subscription Order Creation Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_order_paid_date",
					"label" 	=> __( 'Last Subscription Order Paid Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_order_completed_date",
					"label" 	=> __( 'Last Subscription Order Completed Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "related_last_order_creation_date",
					"label" 	=> __( 'Related Last Order Creation Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "related_last_order_paid_date",
					"label" 	=> __( 'Related Last Order Paid Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "related_last_order_completed_date",
					"label" 	=> __( 'Related Last Order Completed Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_trial_end_date",
					"label" 	=> __( 'Last Subscription Trial End Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_next_payment_date",
					"label" 	=> __( 'Last Subscription Next Payment Date', 'hubwoo' ),
					"type" 		=> "date",  
					"fieldType" => "date",  
					"formField" => false,
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_billing_period",
					"label" 	=> __( 'Last Subscription Billing Period', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options"   => $this->get_subscriptions_billing_period(),
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_billing_interval",
					"label" 	=> __( 'Last Subscription Billing Interval', 'hubwoo' ),
					"type" 		=> "enumeration",  
					"fieldType" => "select",  
					"formField" => false,
					"options"   => $this->get_subscriptions_billing_interval(),
				);

				$group_properties[] = array(
					"name" 		=> "last_subscription_products",
					"label" 	=> __( 'Last Subscription Products', 'hubwoo' ),
					"type" 		=> "string",  
					"fieldType" => "textarea",  
					"formField" => false,
				);
			}
		}

		return apply_filters( 'hubwoo_group_properties', $group_properties, $group_name );
	}

	/**
	 * get all active lists for hubspot
	 * @since 1.0.0
	 */

	private function get_all_active_lists() {

		$lists = array();

		$lists[] = array(

			"name" 		=> __("Best Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Big Spenders","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Loyal Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Churning Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 5,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Low Value Lost Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("New Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Customers needing attention","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 3,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "EQ",
						"value" 	=> 3,
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "SET_ANY",
						"value" 	=> implode( ';', array( 1, 2 ) ),
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("About to Sleep","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "SET_ANY",
						"value" 	=> implode( ';', array( 1,2 ) ),
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "SET_ANY",
						"value" 	=> implode( ';', array( 1,2 ) ),
						"property" 	=> "order_frequency_rating",
						"type" 		=> "enumeration",
					),
					array(
						"operator" 	=> "SET_ANY",
						"value" 	=> implode( ';', array( 1,2 ) ),
						"property" 	=> "order_recency_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Mid Spenders","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 3,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Low Spenders","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "monetary_rating",
						"type" 		=> "enumeration",
					)
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Newsletter Subscriber","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 'yes',
						"property" 	=> "newsletter_subscription",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("One time purchase customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 1,
						"property" 	=> "total_number_of_orders",
						"type" 		=> "number",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Two time purchase customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 2,
						"property" 	=> "total_number_of_orders",
						"type" 		=> "number",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Three time purchase customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 3,
						"property" 	=> "total_number_of_orders",
						"type" 		=> "number",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Bought four or more times","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> 4,
						"property" 	=> "total_number_of_orders",
						"type" 		=> "number",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Leads","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> "lead",
						"property" 	=> "lifecyclestage",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Marketing Qualified Leads","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> "marketingqualifiedlead",
						"property" 	=> "lifecyclestage",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 	=> "EQ",
						"value" 	=> "customer",
						"property" 	=> "lifecyclestage",
						"type" 		=> "enumeration",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("Engaged Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"operator" 				=> "WITHIN_TIME",
						"withinLastTime" 		=> 60,
						"withinLastTimeUnit" 	=> "DAYS",
						"withinLastDays" 		=> 60,
						"withinTimeMode" 		=> "PAST",
						"property" 				=> "last_order_date",
						"type" 					=> "date",
					),
				),
			)
		);

		$lists[] = array(

			"name" 		=> __("DisEngaged Customers","hubwoo"),
			"dynamic" 	=> true,
			"filters" 	=> array(
				array(
					array(
						"withinLastTime" 			=> 60,
						"withinLastTimeUnit" 		=> "DAYS",
						"reverseWithinTimeWindow" 	=> true,
						"withinLastDays" 			=> 60,
						"withinTimeMode" 			=> "PAST",
						"type" 						=> "date",
						"operator" 					=> "WITHIN_TIME",
						"property" 					=> "last_order_date",
					),
					array(
						"withinLastTime" 			=> 180,
						"withinLastTimeUnit" 		=> "DAYS",
						"withinLastDays" 			=> 180,
						"withinTimeMode" 			=> "PAST",
						"type" 						=> "date",
						"operator" 					=> "WITHIN_TIME",
						"property" 					=> "last_order_date",
					),
				),
			)
		);

		$lists[] = array(
			"name" 		=> __("Repeat Buyers","hubwoo"),
			"dynamic"	=> true ,
			"filters"	=> array(
				array(
					array(
						"type" 		=> "number",
						"operator" 	=> "GTE",
						"property" 	=> "total_number_of_orders",
						"value" 	=> 5,
					),
					array(
						"type" 		=> "number",
						"operator" 	=> "LTE",
						"property" 	=> "average_days_between_orders",
						"value" 	=> 30
					),
				),
			),
		);
		return $lists;
	}


	/**
	 * get subscriptions billing period for hubspot
	 * @since 1.0.0
	 */

	public static function get_subscriptions_billing_period() {

		$values = array();

		$values[] = array( 'label' =>__('Day', 'hubwoo' ), 'value'=> 'day' );
		$values[] = array( 'label' =>__('Week', 'hubwoo' ), 'value'=> 'week' );
		$values[] = array( 'label' =>__('Month', 'hubwoo' ), 'value'=> 'month' );
		$values[] = array( 'label' =>__('Year', 'hubwoo' ), 'value'=> 'year' );

		$values = apply_filters( 'hubwoo_subscriptions_period', $values );

		return $values;
	}

	/**
	 * get subscriptions billing interval for hubspot
	 * @since 1.0.0
	 */

	public static function get_subscriptions_billing_interval(){

		$values = array();

		$values[] = array( 'label' =>__('Every', 'hubwoo' ), 'value'=> 1 );
		$values[] = array( 'label' =>__('Every Second', 'hubwoo' ), 'value'=> 2 );
		$values[] = array( 'label' =>__('Every Third', 'hubwoo' ), 'value'=> 3 );
		$values[] = array( 'label' =>__('Every Fourth', 'hubwoo' ), 'value'=> 4 );
		$values[] = array( 'label' =>__('Every Fifth', 'hubwoo' ), 'value'=> 5 );
		$values[] = array( 'label' =>__('Every Sixth', 'hubwoo' ), 'value'=> 6 );

		$values = apply_filters( 'hubwoo_subscriptions_interval', $values );

		return $values;
	}


	/**
	 * get all available woocommerce order statuses
	 * 
	 * @return JSON Order statuses in the form of enumaration options.
	 * @since 1.0.0
	 */

	public static function get_order_statuses() {

		$all_wc_statuses = array();

		//get all statuses
		$all_status = wc_get_order_statuses();
		
		//if status available
		if( is_array( $all_status ) && count( $all_status ) ){

			foreach( $all_status as $status_id => $status_label ){

				$all_wc_statuses[] = array( 'label' => $status_label, 'value' => $status_id );
			}
		}
		$all_wc_statuses = apply_filters( 'hubwoo_order_status_options', $all_wc_statuses );

		return $all_wc_statuses;
	}

	/**
	 * get all available woocommerce order statuses for subscriptions
	 * 
	 * @return JSON Order statuses in the form of enumaration options.
	 * @since 1.0.0
	 */

	public static function get_subscription_status_options() {

		$all_wc_subs_status = array();

		//get all statuses
		$all_status = wcs_get_subscription_statuses();
		
		//if status available
		if( is_array( $all_status ) && count( $all_status ) ){

			foreach( $all_status as $status_id => $status_label ){

				$all_wc_subs_status[] = array( 'label' => $status_label, 'value' => $status_id );
			}
		}
		
		$all_wc_subs_status = apply_filters( 'hubwoo_order_status_options', $all_wc_subs_status );

		return $all_wc_subs_status;

	}

	/**
	 * get ratings for RFM analysis
	 * 
	 * @return ratings for RFM analysis
	 * @since 1.0.0
	 */

	public function get_rfm_rating() {

		$rating = array();

		$rating[] = array( 'label' =>__('5', 'hubwoo' ), 'value'=> 5 );
		$rating[] = array( 'label' =>__('4', 'hubwoo' ), 'value'=> 4 );
		$rating[] = array( 'label' =>__('3', 'hubwoo' ), 'value'=> 3 );
		$rating[] = array( 'label' =>__('2', 'hubwoo' ), 'value'=> 2 );
		$rating[] = array( 'label' =>__('1', 'hubwoo' ), 'value'=> 1 );

		$rating = apply_filters( 'hubwoo_rfm_ratings', $rating );

		return $rating;
	}

	/**
	 * get user actions for marketing
	 * 
	 * @return array  marketing actions for users
	 * @since 1.0.0
	 */
	
	public function get_user_marketing_action() {
		
		$user_actions = array();
		$user_actions[] = array( 'label' => __('Yes', 'hubwoo' ), 'value' => 'yes' );
		$user_actions[] = array( 'label' => __('No', 'hubwoo' ), 'value' => 'no' );
		$user_actions = apply_filters( 'hubwoo_user_marketing_actions', $user_actions );

		return $user_actions;
	}

	public function get_user_marketing_sources () {

		$sources = array();
		$sources[] = array( 'label' => __( 'Checkout', 'hubwoo' ), 'value' => 'checkout' );
		$sources[] = array( 'label' => __( 'Registeration', 'hubwoo' ), 'value' => 'registeration' );
		$sources[] = array( 'label' => __( 'Others', 'hubwoo' ), 'value' => 'other' );
		$sources = apply_filters( 'hubwoo_user_marketing_sources', $sources );
		return $sources;
	}

	/**
	 * last order products html for hubspot
	 * @since 1.0.0
	 */

	public function hubwoo_last_order_html( $last_order_id = "" ) {

		$products_html = "";

		if( !empty( $last_order_id ) ) {

			$order = new WC_Order( $last_order_id );

			$key = 0;

			$last_order_products = array();

			if( !empty( $order ) || !is_wp_error( $order ) ) {

				$order_items 	= $order->get_items();
				
				if( is_array( $order_items ) && count( $order_items ) ) {

					foreach( $order_items as $item_id_1 => $WC_Order_Item_Product ) {

						if( !empty( $WC_Order_Item_Product ) && $WC_Order_Item_Product instanceof WC_Order_Item ) {

							$item_id = $WC_Order_Item_Product->get_variation_id();

							if ( empty( $item_id ) ) {
								$item_id = $WC_Order_Item_Product->get_product_id();
							}

							$product = wc_get_product( $item_id );

							if( get_post_status( $item_id ) == "trash" || get_post_status( $item_id ) == false ) {

								continue;
							}

							if ( $product instanceof WC_Product ) {

								$attachment_src = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
								$last_order_products[$key]["price"] = $product->get_price();
							}

							$last_order_products[$key]["image"] = !empty( $attachment_src[0] ) ? $attachment_src[0] : ""; 
							$last_order_products[$key]["name"] 	= get_the_title( $item_id );
							$last_order_products[$key]["url"] 	= get_permalink( $item_id );
							$last_order_products[$key]["qty"] 	= $WC_Order_Item_Product->get_quantity();
							$key++;
						}
					}
				}
			}

			if( count( $last_order_products ) ) {

				$products_html = '<div><hr></div><!--[if mso]><center><table width="100%" style="width:600px;"><![endif]--><table style="font-size: 14px; font-family: Arial, sans-serif; line-height: 20px; text-align: left; table-layout: fixed;" width="100%"><thead><tr><th style="text-align: center;word-wrap: unset;">' . __( "Image", "hubwoo" ) . '</th><th style="text-align: center;word-wrap: unset;">' . __( "Item", "hubwoo" ) . '</th><th style="text-align: center;word-wrap: unset;">' . __( "Qty", "hubwoo" ) . '</th><th style="text-align: center;word-wrap: unset;">' . __( "Price", "huwboo" ) . '</th><th style="text-align: center;word-wrap: unset;">' . __( "Total", "hubwoo" ) .'</th></tr></thead><tbody>';

				foreach( $last_order_products as $single_product ) {

					$total = $single_product["price"] * $single_product["qty"];
					$products_html .= '<tr><td style="max-width: 20%;width: 100%; text-align: center;"><img height="50" width="50" src="' . $single_product["image"] . '"></td><td style="max-width: 50%;width: 100%; text-align: center; font-weight: normal;font-size: 12px;word-wrap: unset;"><a style="display: inline-block;" target="_blank" href="' . $single_product["url"] . '"><strong>' . $single_product["name"] . '</strong></a></td><td style="max-width: 10%;width: 100%;text-align: center;">' . $single_product["qty"] . '</td><td style="max-width: 10%;width: 100%;text-align: center; font-size: 10px;">' . wc_price( $single_product["price"], array( 'currency' => get_post_meta( $last_order_id, "_order_currency", true ) ) ) . '</td><td style="max-width: 10%;width: 100%;text-align: center; font-size: 10px;">' . wc_price( $total, array( 'currency' => get_post_meta( $last_order_id, "_order_currency", true ) ) ) .'</td></tr>';
				}

				$products_html .= '</tbody></table><!--[if mso]></table></center><![endif]--><div><hr></div>';
			}
		}

		return $products_html;
	}
}