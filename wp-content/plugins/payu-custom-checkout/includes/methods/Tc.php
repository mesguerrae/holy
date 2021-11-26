<?php


class WC_PayU_Tc_Payment_Gateway extends WC_Payment_Gateway {
    /**
     * Constructor for the gateway.
     *
     * @return void
     */

    protected $helper;

    public function __construct() {

    	$plugin_dir = plugin_dir_url(__FILE__);

        global $woocommerce;

        $this->id             = 'payu_tc';
        $this->icon           = apply_filters( 'woocommerce_payu_tc_icon', $plugin_dir.'assets/icons8-credit-card-48.png' );
        $this->has_fields     = true;
        $this->method_title   = __( 'Payu Tarjeta de Credito', 'payu_tc' );
        // Load the form fields.
        $this->init_form_fields();
        // Load the settings.
        $this->init_settings();
        // Define user set variables.
        $this->title          = $this->settings['title'];
        $this->description    = $this->settings['description'];
		$this->instructions       = $this->get_option( 'instructions' );
		$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
		$this->testmode = $this->settings['testmode'];
        // Actions.
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) )
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
        else
            add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
        $this->helper = new payu_helper();

        Environment::setPaymentsCustomUrl($this->settings['payment_url']);

        PayU::$apiKey = $this->settings['api_key'];

		PayU::$apiLogin = $this->settings['api_login'];

		PayU::$merchantId = $this->settings['merchant_id'];

		PayU::$language = SupportedLanguages::ES;

		PayU::$isTest = ($this->testmode == "yes") ? true : false;

		add_action( 'woocommerce_api_tcnotification', array( $this, 'webhook' ) );


    }

    public function init_form_fields(){
    	$this->form_fields = array(
			'enabled' => array(
				'title'       => 'Enable/Disable',
				'label'       => 'Enable PayU TC',
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => 'Title',
				'type'        => 'text',
				'description' => 'This controls the title which the user sees during checkout.',
				'default'     => 'Tarjeta de crédito',
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => 'Description',
				'type'        => 'textarea',
				'description' => 'This controls the description which the user sees during checkout.',
				'default'     => 'Pay with your credit card via our super-cool payment gateway.',
			),
			'testmode' => array(
				'title'       => 'Test mode',
				'label'       => 'Enable Test Mode',
				'type'        => 'checkbox',
				'description' => 'Place the payment gateway in test mode using test API keys.',
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'payment_url' => array(
				'title'       => 'Url',
				'type'        => 'text'
			),
			'api_key' => array(
				'title'       => 'Api Key',
				'type'        => 'text'
			),
			'api_login' => array(
				'title'       => 'Api Login',
				'type'        => 'text',
			),
			'merchant_id' => array(
				'title'       => 'Merchant id',
				'type'        => 'text'
			),

			'account_id' => array(
				'title'       => 'Account id',
				'type'        => 'text'
			)
		);
 	}

    public function payment_fields() {


		// I will echo() the form, but you can close PHP tags and print it directly in HTML
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

		// Add this action hook if you want your custom gateway to support it
		do_action( 'woocommerce_credit_card_form_start', $this->id );

		// I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
		echo '<div class="tc-form"><div class="card-wrapper"></div><br>
			<p class="form-row form-row-first"><label>N&uacute;mero de tarjeta <span class="required">*</span></label>
				<input id="number" name="number" type="tel" autocomplete="off" required>
			</p>
			<p class="form-row form-row-last">
				<label>Nombre de la tarjeta<span class="required">*</span></label>
				<input id="misha_name" name="tc_name" type="text" autocomplete="off" placeholder="" required>
			</p>
			<p class="form-row form-row-first">
				<label>fecha de expiraci&oacute;n <span class="required">*</span></label>
				<input id="misha_expdate" name="expiry" type="tel" autocomplete="off" placeholder="MM / YY" required>
			</p>
			<p class="form-row form-row-last">
				<label>Codigo de seguridad (CVC) <span class="required">*</span></label>
				<input id="misha_cvv" name="cvc" type="tel" autocomplete="off" placeholder="CVC" required>
			</p>
			<p class="form-row form-row-wide">
				<label>Cuotas<span class="required">*</span></label>
				<select class="" id="installments" name="installments"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="12">12</option><option value="18">18</option><option value="24">24</option><option value="36">36</option><option value="48">48</option></select>
			</p>
			<p class="form-row form-row-wide id_type_codensa" style="display:none;">
				<label>Tipo de documento<span class="required">*</span></label>
				<select id="id_type" name="id_type"><option value="">Seleccione un tipo de documento</option><option value="CC" >Cédula de ciudadanía.</option>
					<option value="CE">Cédula de extranjería.</option>
					<option value="NIT">Número de Identificación Tributario.</option>
					<option value="TI">Tarjeta de Identidad.</option>
					<option value="PP">Pasaporte.</option>
					<option value="IDC">Identificador único de cliente, para el caso de ID’s únicos de clientes/usuarios de servicios </option>públicos.
					<option value="CEL">En caso de identificarse a través de la línea del móvil.</option>
					<option value="RC">Registro civil de nacimiento.</option>
					<option value="DE">Documento de identificación extranjero.</option>
				</select>
			</p>

			<p class="form-row form-row-wide no_document">
				<label>No de documento <span class="required">*</span></label>
				<input id="no_document" name="id_number" type="text" autocomplete="off" placeholder="" required>
			</p>
			<input type="hidden" name="franchise" value="" id="franchise">
			<div class="clear"></div></div>
			<script>


		    </script>';

		do_action( 'woocommerce_credit_card_form_end', $this->id );

		echo '<div class="clear"></div></fieldset>';
	}

	public function payment_scripts(){

		wp_enqueue_script( 'my-custom-script', plugins_url( 'card.js', '/payu-custom-checkout/public/js/card.js?v=0.0.1'), array( 'jquery' ) );

		wp_enqueue_script( 'client-js', 'https://gateway.payulatam.com/ppp-web-gateway/javascript/PayU.js', array( 'jquery' ), '3.3.0', true );


		wp_enqueue_style ('theme-style', plugins_url( 'card.css', '/payu-custom-checkout/public/css/card.css'));

	}

	public function validate_fields(){

		if( empty( $_POST[ 'number' ]) ) {
			wc_add_notice(  'Numero de tarjeta no valido', 'error' );
			return false;
		}

		if( empty( $_POST[ 'expiry' ]) ) {
			wc_add_notice(  'Fecha de expiracion invalida', 'error' );
			return false;
		}


		if (!preg_match('/(\d)(\d)(\/)(\d)(\d)/is', str_replace(' ', '', $_POST[ 'expiry' ]))){

		  	wc_add_notice(  'Formato de expiracion invalido, el formato debe ser MM/YY', 'error' );
			return false;
		}

		if( empty( $_POST[ 'tc_name' ]) ) {
			wc_add_notice(  'Nombre invalido', 'error' );
			return false;
		}

		if( empty( $_POST[ 'cvc' ]) ) {
			wc_add_notice(  'Codigo de seguridad invalido', 'error' );
			return false;
		}

		if( strlen( $_POST[ 'cvc' ]) > 4 ||  strlen( $_POST[ 'cvc' ]) < 3) {
			wc_add_notice(  'El codigo de seguridad debe tener entre 3 y 4 digitos', 'error' );
			return false;
		}

		if( empty( $_POST[ 'installments' ]) ) {
			wc_add_notice(  'Numero de cuotas invalido', 'error' );
			return false;
		}

		if( empty( $_POST[ 'franchise' ]) ) {
			wc_add_notice(  'Verifique la tarjeta de credito', 'error' );
			return false;
		}elseif ( $_POST[ 'franchise' ] == 'CODENSA') {
			if( empty( $_POST[ 'id_type' ]) ) {
				wc_add_notice(  'Seleccione un tipo de documento', 'error' );
				return false;
			}

			if( empty( $_POST[ 'id_number' ]) ) {
				wc_add_notice(  'Digite su numero de documento', 'error' );
				return false;
			}
		}

		return true;

	}

    /* Process the payment and return the result. */
	function process_payment ($order_id) {

		global $woocommerce;


		$order = new WC_Order( $order_id );

		$reference = "order_tc".time().'_'.$order_id;

		$value = $order->get_total();

		$expirationDate = explode('/', $_POST['expiry']);

		$expirationDate = str_replace(' ', '', '20'.$expirationDate[1] .'/'. $expirationDate[0]);

		$currentToken = (wp_get_session_token() == '') ? $_COOKIE['woocommerce_cart_hash'] : wp_get_session_token();

		$parameters = array(

			// Enter the account’s identifier here.

			PayUParameters::ACCOUNT_ID => $this->settings['account_id'],

			// Enter the reference code here.

			PayUParameters::REFERENCE_CODE => $reference,

			// Enter the description here.

			PayUParameters::DESCRIPTION => "Compra de producto en holycosmetics",

			// -- Values --
			// Enter the value here.

			PayUParameters::VALUE => $value,

			// Enter the value of the VAT (Value Added Tax only valid for Colombia) of the transaction,
			// if no VAT is sent, the system will apply 19% automatically. It can contain two decimal digits.
			// Example 19000.00. In case you have no VAT you should fill out 0.

			PayUParameters::TAX_VALUE => 0,

			// Enter the value of the base value on which VAT (only valid for Colombia) is calculated.
			// If you do not have VAT should be sent to 0.

			PayUParameters::TAX_RETURN_BASE => 0,

			// Enter the currency here.

			PayUParameters::CURRENCY => "COP",

			// -- Buyer --
			// Enter the buyer Id here.

			PayUParameters::BUYER_NAME => $order->billing_first_name." ".$order->billing_last_name ,

			// Enter the buyer's email here.

			PayUParameters::BUYER_EMAIL => $order->billing_email,

			// Enter the buyer's contact phone here.

			PayUParameters::BUYER_CONTACT_PHONE => $order->billing_phone,

			// Enter the buyer's contact document here.

			PayUParameters::BUYER_DNI => $order->billing_cedula,

			// Enter the buyer's address here.

			PayUParameters::BUYER_STREET => $order->billing_address_1,
			PayUParameters::BUYER_STREET_2 => "",
			PayUParameters::BUYER_CITY => $order->billing_city,
			PayUParameters::BUYER_STATE => $order->billing_state,
			PayUParameters::BUYER_COUNTRY => "CO",
			PayUParameters::BUYER_POSTAL_CODE => "",
			PayUParameters::BUYER_PHONE => $order->billing_phone,

			// -- Payer --
			// Enter the payer's name here.

			PayUParameters::PAYER_NAME => $order->billing_first_name." ".$order->billing_last_name,

			// Enter the payer's email here.

			PayUParameters::PAYER_EMAIL => $order->billing_email,

			// Enter the payer's contact phone here.

			PayUParameters::PAYER_CONTACT_PHONE => $order->billing_phone,

			// Enter the payer's contact document here.

			PayUParameters::PAYER_DNI => ($_POST['id_number'] == '') ? $order->billing_cedula : $_POST['id_number'],

			PayUParameters::PAYER_DNI_TYPE => $_POST['id_type'],

			// Enter the payer's address here.

			PayUParameters::PAYER_STREET => $order->billing_address_1,
			PayUParameters::PAYER_STREET_2 => "",
			PayUParameters::PAYER_CITY => $order->billing_city,
			PayUParameters::PAYER_STATE => $order->billing_state,
			PayUParameters::PAYER_COUNTRY => "CO",
			PayUParameters::PAYER_POSTAL_CODE => "",
			PayUParameters::PAYER_PHONE => $order->billing_phone,

			// -- Credit card data --
			// Enter the number of the credit card here

			PayUParameters::CREDIT_CARD_NUMBER => str_replace(' ', '', $_POST['number']),

			// Enter expiration date of the credit card here

			PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $expirationDate,

			// Enter the security code of the credit card here

			PayUParameters::CREDIT_CARD_SECURITY_CODE => $_POST['cvc'],

			// Enter the name of the credit card here
			// VISA||MASTERCARD||AMEX||DINERS

			PayUParameters::PAYMENT_METHOD => $_POST['franchise'],

			// Enter the number of installments here.

			PayUParameters::INSTALLMENTS_NUMBER => $_POST['installments'],


			PayUParameters::CREDIT_CARD_NAME => $_POST['tc_name'],

			// Enter the name of the country here.

			PayUParameters::COUNTRY => "CO",

			// Session id del device.

			PayUParameters::DEVICE_SESSION_ID => session_id(),

			// Payer IP

			PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],

			// Cookie of the current session.

			PayUParameters::PAYER_COOKIE => $currentToken,

			// User agent of the current session.

			PayUParameters::USER_AGENT => $_SERVER['HTTP_USER_AGENT'],

			PayUParameters::NOTIFY_URL => get_site_url().'/wc-api/enotification/',
		);

		$response = PayUPayments::doAuthorizationAndCapture($parameters);

		if ($response){

			if ($response->transactionResponse->state == "PENDING" && ($response->transactionResponse->responseCode == 'PENDING_TRANSACTION_REVIEW' || $response->transactionResponse->responseCode == 'PENDING_TRANSACTION_CONFIRMATION')){

				$responseCode = $response->transactionResponse->responseCode;

				$order->update_status('on-hold', __( 'PAYU_TC: Orden con pago en revision, id de transaccion: '.$response->transactionResponse->transactionId, 'woocommerce' ));

				$order->save();

				$woocommerce->cart->empty_cart();

				update_post_meta($order->ID, 'payu_tc_pending_transaction_response', json_encode($response->transactionResponse));

				if (!isset($_POST['woocommerce_pay'])) {

					return array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
					);
				}else{

					wp_safe_redirect( wp_get_referer() );
				}



			}elseif ($response->transactionResponse->state == "PENDING"){

				$responseCode = $response->transactionResponse->responseCode;

				$reason = $this->helper->getErrorText($responseCode);

				$order->add_order_note( __( 'PAYU_TC: El pago esta en revisión por:'.$reason. " con id de transaccion:".$response->transactionResponse->transactionId));

				$order->update_status('pending', __( 'PAYU_TC: Orden con pago pendiente: '.$response->transactionResponse->transactionId, 'woocommerce' ));

				$order->save();

				$woocommerce->cart->empty_cart();

				update_post_meta($order->ID, 'payu_tc_pending_transaction_response', json_encode($response->transactionResponse));

				if (!isset($_POST['woocommerce_pay'])) {

					return array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
					);
				}else{

					wp_safe_redirect( $this->get_return_url( $order ));
				}

			}elseif ($response->transactionResponse->state == "DECLINED") {

				$responseCode = $response->transactionResponse->responseCode;

				$reason = $this->helper->getErrorText($responseCode);

				$order->add_order_note( __( 'PAYU_TC: El pago fue rechazado por:'.$reason. " con id de transaccion:".$response->transactionResponse->transactionId));

				$order->save();

				update_post_meta($order->ID, 'payu_tc_failed_transaction_response', json_encode($response->transactionResponse));

				if (!isset($_POST['woocommerce_pay'])) {

					throw new Exception($this->helper->getErrorText($responseCode), 1 );

				}else{

					wc_add_notice(  $this->helper->getErrorText($responseCode), 'error' );

					$location = $_SERVER['HTTP_REFERER'];
			        wp_safe_redirect($location);
			        exit();

				}



			}elseif ($response->transactionResponse->state == "APPROVED") {

				update_post_meta($order->ID, 'payu_tc_approved_transaction_response', json_encode($response->transactionResponse));
				// Mark as on-hold
				$order->update_status('processing', __( 'PAYU_TC: Orden confirmada con id de transaccion: '.$response->transactionResponse->transactionId, 'woocommerce' ));
				// Reduce stock levels
				$order->reduce_order_stock();
				// Remove cart
				$woocommerce->cart->empty_cart();
				// Return thankyou redirect



				/*if (!isset($_POST['woocommerce_pay'])) {*/

					return array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order ),
						'order_id' => $order_id 
					);


				/*}else{

					wp_safe_redirect( $this->get_return_url( $order ));
				}*/
			}else{

				throw new Exception("Ocurrio un error en el pago, vuelve a intentarlo.", 1);

			}

		}

		exit;

	}
    /* Output for the order received page.   */
	function thankyou() {
		echo $this->instructions != '' ? wpautop( $this->instructions ) : '';
	}

	public function webhook() {

		$posted = $_REQUEST;

  		$order = $this->get_payulatam_order( $posted );

    	$codes=array(
    		'1' => 'CAPTURING_DATA' ,
    		'2' => 'NEW' ,
    		'101' => 'FX_CONVERTED' ,
    		'102' => 'VERIFIED' ,
    		'103' => 'SUBMITTED' ,
    		'4' => 'APPROVED' ,
    		'6' => 'DECLINED' ,
    		'104' => 'ERROR' ,
    		'7' => 'PENDING' ,
    		'5' => 'EXPIRED'
    	);

    	$state=$posted['state_pol'];

    	// We are here so lets check status and do actions
        switch ( $codes[$state] ) {
            case 'APPROVED' :
            case 'PENDING' :

            	// Check order not already completed
            	if ( $order->status == 'completed' || $order->status == 'processing') {

            		$order->add_order_note( __('Se intento confirmar orden por notificiacion.') );

            		$order->save();

            		exit;
            	}

				// Validate Amount
			    if ( $order->get_total() != $posted['value'] ) {

			    	$order->update_status( 'on-hold', sprintf( __( 'Error notificacion: El monto de payu no corresponde con el valor de la orden: (valor recibido: %s).', 'payu-latam-woocommerce'), $posted['value'] ) );

			    }

                if ( $codes[$state] == 'APPROVED' ) {
                	$order->add_order_note( __( 'PayU Latam aprobo el pago por notificacion con id:'.$posted['transaction_id'], 'payu-latam-woocommerce') );

                	$order->payment_complete();

                } else {
                	$order->update_status( 'on-hold', sprintf( __( 'Pago pendiente por notificacion: %s', 'payu-latam-woocommerce'), $codes[$state] ) );
                }


            break;
            case 'DECLINED' :
            case 'EXPIRED' :
            case 'ERROR' :
            case 'ABANDONED_TRANSACTION':
                // Order failed
                $order->update_status( 'failed', sprintf( __( 'PayU Latam rechazo el pago. tipo de error: %s', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );

            break;
            default :
                $order->update_status( 'failed', sprintf( __( 'PayU Latam rechazo el pago', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );

            break;
        }

        update_post_meta( $order->id, __('payu_notification_response', 'payu-latam-notification-response'), json_encode($posted) );

        $order->save();
	}

	function get_payulatam_order( $posted ) {

  		$reference_code = ($posted['referenceCode'])?$posted['referenceCode']:$posted['reference_sale'];

		$order_id = str_replace('order_', '', $reference_code);

		$order = new WC_Order( $order_id );

		if ( ! isset( $order->id ) ) {

			throw new Exception("La orden no enviada por payu no existe, OBJETO:".implode("\n",$posted), 1);

		}

        return $order;
	}


}
