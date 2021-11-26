<?php 
/**
 * All api GET/POST functionalities.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 */

/**
 * Handles all hubspot api reqests/response related functionalities of the plugin.
 *
 * Provide a list of functions to manage all the requests
 * that needs in our integration to get/fetch data
 * from/to hubspot.
 *
 * @package    hubspot-woocommerce-integration-starter
 * @subpackage hubspot-woocommerce-integration-starter/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */

class HubWooConnectionMananager{

	/**
	 * The single instance of the class.
	 *
	 * @since 	1.0.0
	 * @access 	protected 
	 * @var HubWooConnectionMananager 	The single instance of the HubWooConnectionMananager
	 */
	protected static $_instance = null;

	/**
	 * Base url of hubspot api.
	 *
	 * @since 1.0.0
	 * @access Private
	 */
	private $baseUrl  = "https://api.hubapi.com";


	/**
	 * Main HubWooConnectionMananager Instance.
	 *
	 * Ensures only one instance of HubWooConnectionMananager is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return HubWooConnectionMananager - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Refreshing access token from refresh token.
	 * @since 1.0.0
	 */
	public function hubwoo_refresh_token( $hapikey, $hseckey ) {

		$endpoint = '/oauth/v1/token';
		$refresh_token = get_option( 'hubwoo_starter_refresh_token', false);
		$data = array(  
			'grant_type'    => 'refresh_token',
			'client_id'     => $hapikey,
		    'client_secret' => $hseckey,
		    'refresh_token' => $refresh_token,
		    'redirect_uri'  => admin_url().'admin.php',
		);
		$body = http_build_query( $data );
		return $this->hubwoo_oauth_post_api( $endpoint, $body, 'refresh');
	}

	/**
	 * Fetching access token from code.
	 * @since 1.0.0
	 */
	public function hubwoo_fetch_access_token_from_code( $hapikey, $hseckey) {

		$endpoint = '/oauth/v1/token';
		$data = array(  
			'grant_type'    => 'authorization_code',
			'client_id'     => $hapikey,
            'client_secret' => $hseckey,
            'code'          => $_GET['code'],
            'redirect_uri'  => admin_url().'admin.php',
        );
		$body = http_build_query( $data );
        return $this->hubwoo_oauth_post_api( $endpoint, $body, 'access');
	}

	/**
	 * Returning saved access token.
	 * @since 1.0.0
	 */
	public static function hubwoo_get_access_token() {

		return get_option( 'hubwoo_starter_access_token', false );
	}

	/**
	 * post api for oauth access and refresh token.
	 * @since 1.0.0
	 */
	public function hubwoo_oauth_post_api( $endpoint, $body, $action ) {

		$headers = array(
        	'Content-Type: application/x-www-form-urlencoded;charset=utf-8'
        );
        if( $action == 'refresh' ) {
        	$access_token = $this->hubwoo_get_access_token();
        	$default = array('Authorization: Bearer '.$access_token);
        	$headers = wp_parse_args($access_token, $default );
        }
		$response  = $this->_post( $endpoint, $body, $headers );
        if ( !is_wp_error( $response ) ) {
        	$status_code = $response['status_code'];
        	$api_body = json_decode($response['response'], true);
        	$message = '';
			if( $status_code == 200 ){
            	if( isset( $api_body[ 'refresh_token' ] ) && isset( $api_body[ 'access_token' ] ) && $api_body[ 'expires_in' ] ) {
     
            		update_option( 'hubwoo_starter_access_token', $api_body[ 'access_token' ]);
            		update_option( 'hubwoo_starter_refresh_token', $api_body[ 'refresh_token' ]);
            		update_option( 'hubwoo_starter_token_expiry', time() + $api_body[ 'expires_in' ]);
            		update_option( 'hubwoo_starter_valid_client_ids_stored', true );
            		update_option( 'hubwoo_ecomm_bridge_enabled', true );
            		
            		$message = __('Fetching and refreshing access token', 'hubwoo');
					$this->create_log( $message, $endpoint, $response );
					$this->hubwoo_starter_get_access_token_info();
					update_option( 'hubwoo_starter_send_suggestions', true );
					update_option( 'hubwoo_starter_oauth_success', true );
            		return true;
            	}
			}
			elseif( $status_code == 400 ) {
				
				$message = $api_body['message'];
			}
			elseif( $status_code == 403 ) {
				$message = __('You are forbidden to use this scope', 'hubwoo' );
			}
			update_option( 'hubwoo_starter_send_suggestions', false );
			update_option( 'hubwoo_starter_api_validation_error_message', $message );
			update_option( 'hubwoo_starter_valid_client_ids_stored', false );
			$this->create_log( $message, $endpoint, $response );
        }
        else {
        	$message = __('Something went wrong.', 'hubwoo' );
        	update_option( 'hubwoo_starter_api_validation_error_message', $message );
        	update_option( 'hubwoo_starter_valid_client_ids_stored', false );
        }

        return false;
	}

	/*
		* get scopes for ecommerce pipeline
	*/

	public function hubwoo_starter_get_access_token_info() {
		
		$access_token =  $this->hubwoo_get_access_token();
		$endpoint = '/oauth/v1/access-tokens/' . $access_token;
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token
		);
		$response = $this->_get( $endpoint, $headers );
		$status_code = !empty( $response['status_code'] ) ? $response['status_code'] : "";
		$return_response = !empty( $response['response'] ) ? $response['response'] : "";
		if( !empty( $return_response ) ) {
			$api_body = json_decode( $return_response , true );
		}
       	
       	if( !empty( $api_body['scopes'] ) ) {
       		update_option( "hubwoo_starter_account_scopes", $api_body['scopes'] );
       	}
       	$message = __( "Getting access token information", "hubwoo" );
       	$this->create_log( $message, $endpoint, $response );
	}


	/**
	 * sending details of hubspot.
	 * @since 1.0.0
	 */
	public function send_clients_details() {

		$send_status = get_option('hubwoo_starter_send_suggestions', false);

		if( $send_status ) {

			$url = '/owners/v2/owners';
			$access_token = $this->hubwoo_get_access_token();
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);

			$response = $this->_get( $url, $headers );
			
			if ( !is_wp_error( $response ) ) {

				$status_code = $response['status_code'];

				if( $status_code == 200 ) {

					$api_body = json_decode($response['response'], true);
					
					if( isset( $api_body ) && isset( $api_body[0] ) ) {

						$to 		= 'integrations@makewebbetter.com';
						$subject  	= 'HubSpot Starter Customers Details';
						$headers  	= array('Content-Type: text/html; charset=UTF-8');
						$message  	= 'portalId: '.$api_body[0]['portalId'].'<br/>';
						$message 	.= 'ownerId: '.$api_body[0]['ownerId'].'<br/>';
						$message 	.= 'type: '.$api_body[0]['type'].'<br/>';
						$message 	.= 'firstName: '.$api_body[0]['firstName'].'<br/>';
						$message 	.= 'lastName: '.$api_body[0]['lastName'].'<br/>';
						$message 	.= 'email: '.$api_body[0]['email'].'<br/>';
						$status 	= wp_mail( $to, $subject, $message, $headers);
						return $status;
					}
				}
			}
		}
		return false;
	}

	/**
	 * get hubspot owner email
	 *
	 * @since 1.0.0
	 */

	public function hubwoo_get_owners_info() {

		$url = '/owners/v2/owners';
		$access_token = $this->hubwoo_get_access_token();
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token
		);

		$response = $this->_get( $url, $headers );

		$portalId = '';

		if ( !is_wp_error( $response ) ) {

			$status_code = $response['status_code'];

			if( $status_code == 200 ) {

				$api_body = json_decode($response['response'], true);
				
				if( isset( $api_body ) && isset( $api_body[0] ) ) {

					$portalId = $api_body[0]['portalId'];
				}
			}
		}

		return $portalId;
	}

	/**
	 * Get requests to hubspot.
	 * 
	 * @param  string 	$url    	Complete url endpoint.
	 * @param  array  	$params 	get parameters in the array.
	 * @return array         response in the form of array or bool if false.
	 */
	private function _get( $endpoint, $headers ){

		$url = $this->baseUrl.$endpoint;

		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_POST, false);
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = @curl_exec($ch);
		$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_errors = curl_error($ch);
		@curl_close($ch);
		return array( 'status_code' => $status_code, 'response' => $response, 'errors' => $curl_errors );
	}

	/**
	 * send post and format the response to hubspot.
	 * 
	 * @param  string     $url     request endpoint
	 * @param  array      $body    body parameters
	 * @return array      foramatted response from hubspot.
	 * @access private
	 * @since 1.0.0
	 */
	private function _post( $endpoint, $post_params, $headers ){
		
		$url = $this->baseUrl.$endpoint;

		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_POST, true);
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_POSTFIELDS,  $post_params  );
		@curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = @curl_exec($ch);
		$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_errors = curl_error($ch);
		@curl_close($ch);
		return array( 'status_code' => $status_code, 'response' => $response, 'errors' => $curl_errors );
	}

	/**
	 * send post and format the response to hubspot.
	 * 
	 * @param  string     $url     request endpoint
	 * @param  array      $body    body parameters
	 * @return array      foramatted response from hubspot.
	 * @access private
	 * @since 1.0.0
	 */
	private function _put( $endpoint, $post_params, $headers ){
		
		$url = $this->baseUrl.$endpoint;
		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params );
		@curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$response = @curl_exec($ch);
		$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_errors = curl_error($ch);
		@curl_close($ch);
		return array( 'status_code' => $status_code, 'response' => $response, 'errors' => $curl_errors );
	}
	
	/**
	 * create group on hubspot.
	 * @since 1.0.0
	 */
	public function create_group( $group_details ){

		if ( is_array( $group_details ) ) {

			if( isset( $group_details[ 'name' ] ) && isset( $group_details[ 'displayName' ] ) ) {
				
				$url = '/properties/v1/contacts/groups';
				$access_token = $this->hubwoo_get_access_token();
				$headers = array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$access_token
				);
		        $group_details = json_encode( $group_details );
				$response = $this->_post( $url, $group_details, $headers );
				$message = __('Creating Groups','hubwoo');
				$this->create_log( $message, $url, $response );
				return $response;
			}
		}	
	}

	/**
	 * create property on hubspot.
	 * @since 1.0.0
	 */
	
	public function create_property( $prop_details ) {

		if ( is_array( $prop_details ) ) {
			
			if( isset( $prop_details[ 'name' ] ) && isset( $prop_details[ 'groupName' ] ) ) {
				
				$url = '/properties/v1/contacts/properties';
				$access_token = $this->hubwoo_get_access_token();
				$headers = array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$access_token
				);
		        $prop_details = json_encode($prop_details);
				$response = $this->_post( $url, $prop_details, $headers );
				$message = __('Creating Properties','hubwoo');
				$this->create_log( $message, $url, $response );
				return $response;
			}
		}
	}

	/**
	 * update property on hubspot.
	 * @since 1.0.0
	 */
	public function update_property( $prop_details ){
		// check if in the form of array.
		if( is_array( $prop_details ) ){
			//check for name and groupName.
			if( isset( $prop_details[ 'name' ] ) && isset( $prop_details[ 'groupName' ] ) ){

				// let's update
				$url = '/properties/v1/contacts/properties/named/'.$prop_details[ 'name' ];

				$access_token = $this->hubwoo_get_access_token();

				$headers = array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$access_token
				);
		        $prop_details = json_encode($prop_details);
				$response = $this->_put( $url, $prop_details, $headers );
				
				$message = __('Updating Properties','hubwoo');

				$this->create_log( $message, $url, $response );

				return $response;
			}
		}
	}

	public function create_single_contact( $contact ) {

		if ( is_array( $contact ) ) {

			$url = '/contacts/v1/contact';
			$access_token = $this->hubwoo_get_access_token();
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);
	        $contact = json_encode( $contact );
			$response = $this->_post( $url, $contact, $headers );
			$message = __( 'Creating Single Contact', 'hubwoo' );
			$this->create_log( $message, $url, $response );
			return $response;
		}
	}

	/**
	 * create or update contacts.
	 * 
	 * @param  array    $contacts    hubspot acceptable contacts array.
	 * @access public
	 * @since 1.0.0
	 */
	public function create_or_update_contacts( $contacts ) {

		if ( is_array( $contacts ) ) {
			$url = '/contacts/v1/contact/batch/';
			$access_token = $this->hubwoo_get_access_token();
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);
	        $contacts = json_encode($contacts);
			$response = $this->_post( $url, $contacts, $headers );
			if ( !empty( $response['response'] ) ) {
				$decoded_response = json_decode( $response['response'] );
				if ( !empty( $decoded_response->invalidEmails ) ) {
					$savedinvalidEmails = get_option( "hubwoo_starter_invalid_emails", array() );
					foreach ( $decoded_response->invalidEmails as $single_email ) {
						if ( !in_array( $single_email, $savedinvalidEmails ) ) {
							$savedinvalidEmails[] = $single_email;
						}
					}
				}
			}
			if ( !empty( $savedinvalidEmails ) ) {
				update_option( "hubwoo_starter_invalid_emails", $savedinvalidEmails );
			}
			$message = __( 'Updating or Creating users data', 'hubwoo' );
			$this->create_log( $message, $url, $response );
			return $response;
		}
	}
	
	/**
	 * create list on hubspot.
	 * @since 1.0.0
	 */
	
	public function create_list( $list_details ) {

		if ( is_array( $list_details ) ) {
			if( isset( $list_details[ 'name' ] ) ) {
				$url = '/contacts/v1/lists';
				$access_token = $this->hubwoo_get_access_token();
				$headers = array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$access_token
				);
		        $list_details = json_encode($list_details);
				$response = $this->_post( $url, $list_details, $headers );
				$message = __('Creating Lists','hubwoo');
				$this->create_log( $message, $url, $response );
				return $response;
			}
		}	
	}

	public function get_static_list() {

		$lists = array();
		$lists["select"] = __("--Please Select a Static List--", "hubwoo");
		$url = '/contacts/v1/lists/static?count=250';
		$access_token = $this->hubwoo_get_access_token();
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$access_token
		);
		$response = $this->_get( $url, $headers );
		$message = __('Get Static Lists','hubwoo');
		$this->create_log( $message, $url, $response );
		if( isset( $response['response'] ) ) {

			$response = json_decode( $response['response'] );
		}

		if( !empty( $response->lists ) ) {

			foreach( $response->lists as $single_list ) {

				if( isset( $single_list->name ) && isset( $single_list->listId ) ) {

					$lists[$single_list->listId] = $single_list->name;
				}
			}
		}

		return $lists;
	}

	public function list_enrollment( $email, $list_id ) {
		
		if( !empty( $email ) && !empty( $list_id ) ) {
		
			$url = '/contacts/v1/lists/'.$list_id.'/add';
			$access_token = $this->hubwoo_get_access_token();
			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);
			$emails = array();
			$emails[] = $email;
			$request = array( "emails" => $emails );
			$request = json_encode( $request );
			$response = $this->_post( $url, $request, $headers );
			$message = __('Enrolling in Static List','hubwoo');
			$this->create_log( $message, $url, $response );
		}
	}

	/**
	 * create log of requests.
	 * 
	 * @param  string    $message     hubspot log message.
	 * @param  string    $url         hubspot acceptable url.
	 * @param  array     $response    hubspot response array.
	 * @access public
	 * @since 1.0.0
	 */
	public function create_log( $message, $url, $response) {

		if( $response[ 'status_code' ] == 400 || $response[ 'status_code' ] == 404 || $response[ 'status_code' ] == 401 ) {

			update_option( "hubwoo_starter_alert_param_set", true );
			$error_apis = get_option( "hubwoo-starter-error-api-calls", 0 );
			$error_apis += 1;
			update_option( "hubwoo-starter-error-api-calls", $error_apis );
		}
		elseif( $response[ 'status_code' ] == 200 || $response[ 'status_code' ] == 202 || $response[ 'status_code' ] == 201 ) {

			$success_apis = get_option( "hubwoo-starter-success-api-calls", 0 );
			$success_apis += 1;
			update_option( "hubwoo-starter-success-api-calls", $success_apis );
			update_option( "hubwoo_starter_alert_param_set", false );
		}
		else {

			update_option( "hubwoo_starter_alert_param_set", false );
		}
		
		if( $response[ 'status_code' ] == 200 ) {

			$final_response[ 'status_code' ] = 200;
		}
		elseif( $response[ 'status_code' ] == 202 ) {

			$final_response[ 'status_code' ] = 202;
		}
		else {
			
			$final_response = $response;
		}

		$log_enable = get_option( 'hubwoo_starter_log_enable', 'no' );

		if( $log_enable == "yes" ) {

			$log_dir = WC_LOG_DIR.'hubwoo-starter-logs.log';

			if (!is_dir($log_dir)) {

	  			@fopen( WC_LOG_DIR.'hubwoo-starter-logs.log', 'a' );
	 		}

	 		$log  = "Website: ".$_SERVER['REMOTE_ADDR'].PHP_EOL.
			 		'Time: '.current_time("F j, Y  g:i a").PHP_EOL.
			 		"Process: ".$message.PHP_EOL.
			 		"URL: ".$url.PHP_EOL.
			 		"Response: ".json_encode($final_response).PHP_EOL.
			 		"-----------------------------------".PHP_EOL;

	 		file_put_contents($log_dir, $log, FILE_APPEND);
	 	}
	}

	/**
	 * getting all hubspot properties.
	 * @since 1.0.0
	 */
	public function get_all_hubspot_properties() {

		$response = '';

		$flag = false;

		if( Hubwoo::is_valid_client_ids_stored() ) {

			$flag = true;

			if( Hubwoo::is_access_token_expired() ) {

				$hapikey = HUBWOO_STARTER_CLIENT_ID;
				$hseckey = HUBWOO_STARTER_SECRET_ID;
				$status =  HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey);

				if( !$status ) 
				{
					$flag = false;
				}
			}
		}

		if( $flag ) {

			$url = '/properties/v1/contacts/properties';

			$access_token = $this->hubwoo_get_access_token();

			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);

			$response = $this->_get( $url, $headers );
			
			$message = __('Fetching all Contact Properties','hubwoo');
			$this->create_log( $message, $url, $response );

			if( isset( $response[ "status_code" ] )  && $response[ "status_code" ] == 200 ) {

				if( isset( $response["response"] ) ) {

					$response = json_decode( $response["response"] );
				}
			}
		}

		return $response;
	}

	/**
	 * getting all contact lists
	 * @since 1.0.0
	 */
	public function get_all_contact_lists( $count, $offset ) {

		$response = array();

		$flag = false;

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

		if( $flag ) {

			$url = '/contacts/v1/lists?count='.$count.'&offset='.$offset;

			$access_token = $this->hubwoo_get_access_token();

			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);

			$response = $this->_get( $url, $headers );
			
			$message = __( 'Fetching Contact Lists', 'hubwoo' );
			$this->create_log( $message, $url, $response );
			if( isset( $response[ "status_code" ] )  && $response[ "status_code" ] == 200 ) {

				if( isset( $response["response"] ) ) {

					$response = json_decode( $response["response"] );
				}
			}
		}

		return $response;
	}

	/**
	 * getting all contacts in a list
	 * @since 1.0.0
	 */
	public function get_contacts_in_list( $list_id, $offset ) {

		$response = array();

		$flag = false;

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

		if( $flag ) {

			$url = '/contacts/v1/lists/'.$list_id.'/contacts/all?count=50&vidOffset='.$offset;

			$access_token = $this->hubwoo_get_access_token();

			$headers = array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$access_token
			);

			$response = $this->_get( $url, $headers );
			
			$message = __( 'Fetching Contacts from List', 'hubwoo' );
			$this->create_log( $message, $url, $response );
			
			if( isset( $response[ "status_code" ] )  && $response[ "status_code" ] == 200 ) {

				if( isset( $response["response"] ) ) {

					$response = json_decode( $response["response"] );
				}
			}
		}

		return $response;
	}
}