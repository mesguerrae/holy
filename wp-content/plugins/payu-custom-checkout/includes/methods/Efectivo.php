<?php


    class WC_PayU_Efectivo_Payment_Gateway extends WC_Payment_Gateway {
        /**
         * Constructor for the gateway.
         *
         * @return void
         */

        protected $helper;

        public function __construct() {

            $plugin_dir = plugin_dir_url(__FILE__);

            global $woocommerce;
            $this->id             = 'payu_efectivo';
            $this->icon           = apply_filters( 'woocommerce_payu_tc_icon', $plugin_dir.'assets/get-money.png' );
            $this->has_fields     = true;
            $this->method_title   = __( 'Payu efectivo', 'payu_efectivo' );
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

            add_action( 'woocommerce_api_enotification', array( $this, 'webhook' ) );

        }

        public function init_form_fields(){

            $this->form_fields = array(
                                       'enabled' => array(
                                                          'title'       => 'Enable/Disable',
                                                          'label'       => 'Enable PayU Efectivo',
                                                          'type'        => 'checkbox',
                                                          'description' => '',
                                                          'default'     => 'no'
                                                          ),
                                       'title' => array(
                                                        'title'       => 'Title',
                                                        'type'        => 'text',
                                                        'description' => 'This controls the title which the user sees during checkout.',
                                                        'default'     => 'Credit Card',
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
                                                             ),
                                       );
        }

        public function payment_fields() {

            $plugin_dir = plugin_dir_url(__FILE__).'assets/';


            // I will echo() the form, but you can close PHP tags and print it directly in HTML
            echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

            // Add this action hook if you want your custom gateway to support it
            do_action( 'woocommerce_credit_card_form_start', $this->id );

            // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
            echo '<div class="tc-form">
            <select name="metodo_efectivo" id="efectivo-select">
            <option value="BALOTO" data-img-src="'.apply_filters( 'woocommerce_payu_baloto_icon', $plugin_dir.'baloto.png' ).'"></option>
            <option value="EFECTY" data-img-src="'.apply_filters( 'woocommerce_payu_efecty_icon', $plugin_dir.'efecty.png' ).'"></option>
            <option value="BANK_REFERENCED_1" data-img-src="'.apply_filters( 'woocommerce_payu_efecty_icon', $plugin_dir.'bancodebogota.png' ).'"></option>
            <option value="BANK_REFERENCED_2" data-img-src="'.apply_filters( 'woocommerce_payu_efecty_icon', $plugin_dir.'bancolombia.png' ).'"></option>
            <option value="OTHERS_CASH" data-img-src="'.apply_filters( 'woocommerce_payu_efecty_icon', $plugin_dir.'su-red.png' ).'"></option>
            </select>
            <div class="clear"></div>
            </div>
            <script>

            </script>';




            do_action( 'woocommerce_efectivo_form_end', $this->id );

            echo '<div class="clear"></div></fieldset>';
        }

        public function payment_scripts(){

            wp_enqueue_script( 'image-picker-script', plugins_url( 'image-picker.min.js', '/payu-custom-checkout/public/js/image-picker.min.js'), array( 'jquery' ) );

            wp_enqueue_style ('image-picker-css', plugins_url( 'image-picker.css', '/payu-custom-checkout/public/css/image-picker.css'));

        }

        public function validate_fields(){

            if( empty( $_POST[ 'metodo_efectivo' ]) ) {
                wc_add_notice(  'Selecciona un metodo de pago', 'error' );
                return false;
            }

            if($this->get_order_total() < 30000){
                wc_add_notice(  'el monto minimo en efectivo son $30.000', 'error' );
                return false;
            }


            /*if( $_POST[ 'metodo_efectivo' ] != 'EFECTY' && $_POST[ 'metodo_efectivo' ] != 'BALOTO') {
             wc_add_notice(  'Metodo de pago invalido', 'error' );
             return false;
             }*/



            return true;

        }

        /* Process the payment and return the result. */
        function process_payment ($order_id) {

            global $woocommerce;

            $order = new WC_Order( $order_id );

            $reference = "order_e".time().'_'.$order_id;

            $value = $order->get_total();

            $date = strtotime("+3 day");

            $date =  date('Y-m-d', $date);

            if (strpos($_POST['metodo_efectivo'], 'BANK_REFERENCED') !== false) {

                $method = 'BANK_REFERENCED';
            }else{

                $method = $_POST['metodo_efectivo'];

            }

            $parameters = array(
                                //Ingrese aquí el identificador de la cuenta.
                                PayUParameters::ACCOUNT_ID => $this->settings['account_id'],
                                //Ingrese aquí el código de referencia.
                                PayUParameters::REFERENCE_CODE => $reference,
                                //Ingrese aquí la descripción.
                                PayUParameters::DESCRIPTION => "Compra de producto en holycosmetics",

                                // -- Valores --
                                //Ingrese aquí el valor de la transacción.
                                PayUParameters::VALUE => $value,
                                //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
                                //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
                                //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
                                PayUParameters::TAX_VALUE => 0,
                                //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
                                //En caso de que no tenga IVA debe enviarse en 0.
                                PayUParameters::TAX_RETURN_BASE => 0,
                                //Ingrese aquí la moneda.
                                PayUParameters::CURRENCY => "COP",

                                //Ingrese aquí el email del comprador.
                                PayUParameters::BUYER_EMAIL => $order->billing_email,
                                //Ingrese aquí el nombre del pagador.
                                PayUParameters::PAYER_NAME => $order->billing_first_name." ".$order->billing_last_name,
                                //Ingrese aquí el documento de contacto del pagador.
                                PayUParameters::PAYER_DNI=> $order->billing_cedula,

                                //Ingrese aquí el nombre del método de pago
                                PayUParameters::PAYMENT_METHOD => strtoupper($method), //EFECTY

                                //Ingrese aquí el nombre del pais.
                                PayUParameters::COUNTRY => PayUCountries::CO,

                                //Ingrese aquí la fecha de expiración.
                                PayUParameters::EXPIRATION_DATE => $date.'T00:00:00',
                                //IP del pagadador
                                PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],

                                PayUParameters::NOTIFY_URL => get_site_url().'/wc-api/enotification/'
                                );

            $response = PayUPayments::doAuthorizationAndCapture($parameters);


            if ($response){

                $response->transactionResponse->orderId;

                $response->transactionResponse->transactionId;

                $response->transactionResponse->state;

                if ($response->transactionResponse->state == "PENDING"){

                    $responseCode = $response->transactionResponse->responseCode;

                    $reason = $this->helper->getErrorText($responseCode);

                    $order->update_status('on-hold', __(  "PAYU_EFECTIVO: ".$reason. " con id de transaccion:".$response->transactionResponse->transactionId, 'woocommerce' ));
                    $order->save();

                    $woocommerce->cart->empty_cart();

                    update_post_meta($order->ID, 'pending_transaction_response', json_encode($response->transactionResponse));

                    update_post_meta($order->ID, 'pdf', str_replace('"', '', $response->transactionResponse->extraParameters->URL_PAYMENT_RECEIPT_PDF));

                    //if (!isset($_POST['woocommerce_pay'])) {

                        return array(
                                     'result'     => 'success',
                                     'redirect'    => $this->get_return_url( $order ),
                                      'order_id' => $order_id 
                                     );
                    /*}else{

                        wp_safe_redirect( $this->get_return_url( $order ));
                    }*/


                }elseif ($response->transactionResponse->state == "DECLINED") {

                    $responseCode = $response->transactionResponse->responseCode;

                    $reason = $this->helper->getErrorText($responseCode);

                    $order->update_status('pending', __( 'PAYU_EFECTIVO: El pago fue rechazado por:'.$reason. " con id de transaccion:".$response->transactionResponse->transactionId, 'woocommerce' ));

                    $order->save();

                    update_post_meta($order->ID, 'declined_transaction_response', json_encode($response->transactionResponse));

                    if (!isset($_POST['woocommerce_pay'])) {

                        throw new Exception($this->helper->getErrorText($responseCode), 1 );

                    }else{

                        wc_add_notice(  $this->helper->getErrorText($responseCode), 'error' );

                        $location = $_SERVER['HTTP_REFERER'];
                        wp_safe_redirect($location);
                        exit();

                    }


                }else{

                    throw new Exception("Ocurrio un error en el pago.", 1);

                }

            }

            exit;

        }
        /* Output for the order received page.   */


        public function webhook() {

            global $wpdb;

            $posted = $_REQUEST;

            $signature = (isset($posted['sign'])) ? $posted['sign'] : '';

            $api_key = $this->settings['api_key'];

            $merchantId = $this->settings['merchant_id'];

            $referenceCode = (isset($posted['reference_sale'])) ? $posted['reference_sale'] : null;

            $value = (isset($posted['value'])) ? $posted['value'] : null;

            $currency =  (isset($posted['currency'])) ? $posted['currency'] : null;

            $transactionState = (isset($posted['state_pol'])) ? $posted['state_pol'] : null;

            $split = explode('.', $value);

            $decimals = $split[1];

            if ($decimals % 10 == 0) {

                $value = number_format($value, 1, '.', '');
            }

            $signature_local = $api_key . '~' . $merchantId . '~' . $referenceCode . '~' . $value . '~' . $currency . '~' . $transactionState;

            $signature_md5 = md5($signature_local);

            if (strtoupper($signature) == strtoupper($signature_md5)) {
                $order = $this->get_payulatam_order( $posted );

                $state=$posted['state_pol'];

                // Check order not already completed
                if ( $order->status == 'completed' || $order->status == 'processing') {

                    $order->add_order_note( __('Se intento cambiar estado de orden confirmada.') );

                    $order->save();

                    exit;
                }

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

                update_post_meta( $order->id, __('payu_notification_response', 'payu-latam-notification-response'), json_encode($posted) );
                // We are here so lets check status and do actions
                switch ( $codes[$state] ) {
                    case 'APPROVED' :
                    case 'PENDING' :
                        // Validate Amount
                        if ( $order->get_total() != $posted['value'] ) {

                            $order->update_status( 'failed', sprintf( __( 'Error notificacion: El monto de payu no corresponde con el valor de la orden: (valor recibido: %s).', 'payu-latam-woocommerce'), $posted['value'] ) );

                            break;

                        }

                        if ( $codes[$state] == 'APPROVED' ) {

                            $order->update_status( 'processing', sprintf(  __( 'PayU Latam aprobo el pago por notificacion con id:'.$posted['transaction_id']) ));


                        } else {

                            $order->update_status( 'on-hold', sprintf( __( 'Pago pendiente por notificacion: %s', 'payu-latam-woocommerce'), $codes[$state] ) );
                        }


                        break;
                    case 'DECLINED' :
                    case 'EXPIRED' :
                    case 'ERROR' :
                    case 'ABANDONED_TRANSACTION':
                        // Order failed
                        $order->update_status( 'pending', sprintf( __( 'PayU Latam rechazo el pago. tipo de error: %s', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );

                        break;
                    default :
                        $order->update_status( 'pending', sprintf( __( 'PayU Latam rechazo el pago', 'payu-latam-woocommerce'), ( $codes[$state] ) ) );

                        break;
                }

                $order->save();

                echo "listo";
            }


        }

        function get_payulatam_order( $posted ) {

            $reference_code = (isset($posted['reference_sale'])) ? $posted['reference_sale'] : null;

            if (!is_null($reference_code)) {

                $refCode = explode('_', $reference_code);

                $order_id = (isset($refCode[2])) ? $refCode[2] : false;

                if ($order_id){

                    $order = new WC_Order( $order_id );

                    if ( ! isset( $order->id ) ) {

                        exit;

                        return;

                    }

                    return $order;

                }else{

                  $order_id = str_replace('order_', '', $reference_code);

                  $order = new WC_Order( $order_id );

                  if ( ! isset( $order->id ) ) {

                        exit;

                        return;

                  }

                  return $order;

                }


            }else{

                exit;
            }


        }
    }
