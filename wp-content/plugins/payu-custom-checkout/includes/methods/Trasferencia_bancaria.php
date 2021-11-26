<?php


    class WC_PayU_Trasgerencia_Bancaria_Payment_Gateway extends WC_Payment_Gateway {
        /**
         * Constructor for the gateway.
         *
         * @return void
         */

        protected $helper;

        public function __construct() {

            $plugin_dir = plugin_dir_url(__FILE__);

            global $woocommerce;
            $this->id             = 'payu_trasferencia_bancaria';
            $this->icon           = apply_filters( 'woocommerce_trasferencia_bancaria_icon', $plugin_dir.'assets/logonpd.png' );
            $this->has_fields     = true;
            $this->method_title   = __( 'Payu trasferencia bancaria', 'payu_trasferencia_bancaria' );
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

            add_action( 'woocommerce_api_tbnotification', array( $this, 'webhook' ) );

        }

        public function init_form_fields(){
            $this->form_fields = array(
                                       'enabled' => array(
                                                          'title'       => 'Enable/Disable',
                                                          'label'       => 'Enable PayU trasferencia bancaria',
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
                                                             )
                                       );
        }

        public function getBanks(){

            $banks = array();

            //Ingrese aquí el nombre del medio de pago
            $parameters = array(
                                //Ingrese aquí el identificador de la cuenta.
                                PayUParameters::PAYMENT_METHOD => "PSE",
                                //Ingrese aquí el nombre del pais.
                                PayUParameters::COUNTRY => PayUCountries::CO,
                                );

            $array = PayUPayments::getPSEBanks($parameters);

            if (isset($array->banks)) {

                $banks = $array->banks;
            }

            return $banks;
        }

        public function payment_fields() {

            $plugin_dir = plugin_dir_url(__FILE__).'assets/';


            $banks = $this->getBanks();

            // I will echo() the form, but you can close PHP tags and print it directly in HTML
            echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-bank-transfer-form wc-payment-form" style="background:transparent;">';

            // Add this action hook if you want your custom gateway to support it
            do_action( 'woocommerce_credit_card_form_start', $this->id );

            // I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
            echo '<div class="bank-transfer-form"><br>
            <p class="form-row form-row-first">
            <label>Nombre de titular <span class="required">*</span></label>
            <input id="bank_transfer_name" name="bt_name" type="text" autocomplete="off" onselectstart="return false" ondrop="return false" placeholder="" required>
            </p>

            <p class="form-row form-row-last">
            <label>Tipo de cliente<span class="required">*</span></label>
            <select class="" id="bt_client_type" name="bt_client_type">
            <option value="N">Persona natural</option>
            <option value="J">Persona juridica</option>
            </select>
            </p>
            <p class="form-row form-row-first" >
            <label>Tipo de documento<span class="required">*</span></label>
            <select id="bt_id_type" name="bt_id_type"><option value="">Seleccione un tipo de documento</option><option value="CC" >Cédula de ciudadanía.</option>
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
            <p class="form-row form-row-last ">
            <label>No de documento <span class="required">*</span></label>
            <input id="bt_id_number" name="bt_id_number" type="tel" autocomplete="off" placeholder="" required>
            </p>

            <p class="form-row form-row-wide ">
            <label>Telefono<span class="required">*</span></label>
            <div class="input-phone"></div>
            </p>
            </p>
            <br>
            <p class="form-row form-row-wide">
            <label>Banco<span class="required">*</span></label>
            <select class="" id="bank" name="bank">';
            foreach ($banks as $bank) {

                echo '<option value="'.$bank->pseCode.'">'.$bank->description.'</option>';
            }
            echo'    </select>
            </p>



            ';

            echo    '<div class="clear"></div>
            </div>
            <script>

            </script>';




            do_action( 'woocommerce_efectivo_form_end', $this->id );

            echo '<div class="clear"></div></fieldset>';
        }

        public function payment_scripts(){

            wp_enqueue_script( 'phone-js-script', plugins_url( 'intlInputPhone.min.js', '/payu-custom-checkout/public/js/intlInputPhone.min.js'), array( 'jquery' ) );

            wp_enqueue_style ('phone-js-css', plugins_url( 'intlInputPhone.min.css', '/payu-custom-checkout/public/css/intlInputPhone.min.css'));

        }

        public function validate_fields(){


            if( empty( $_POST[ 'bank' ]) ) {
                wc_add_notice(  'Seleccione un banco', 'error' );
                return false;
            }

            if( empty( $_POST[ 'bt_name' ]) ) {
                wc_add_notice(  'Ingrese su nombre', 'error' );
                return false;
            }

            if( empty( $_POST[ 'bt_client_type' ]) ) {
                wc_add_notice(  'Seleccione un tipo de cliente', 'error' );
                return false;
            }

            if($_POST[ 'bt_client_type' ] != 'N' && $_POST[ 'bt_client_type' ] != 'J' ) {
                wc_add_notice(  'Tipo de cliente invalido', 'error' );
                return false;
            }

            if( empty( $_POST[ 'bt_id_type' ]) ) {
                wc_add_notice(  'Seleccione un tipo de documento', 'error' );
                return false;
            }

            if( empty( $_POST[ 'bt_id_number' ]) ) {
                wc_add_notice(  'Ingrese su numero de documento', 'error' );
                return false;
            }
            if( empty( $_POST[ 'phoneNumber' ]) ) {
                wc_add_notice(  'Ingrese su numero de telefono', 'error' );
                return false;
            }

            if( strlen((string)$_POST[ 'phoneNumber' ]) < 10 ) {
                wc_add_notice(  'Ingrese un numero de celular valido', 'error' );
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

            PayU::$apiKey = $this->settings['api_key'];

            PayU::$apiLogin = $this->settings['api_login'];

            PayU::$merchantId = $this->settings['merchant_id'];

            PayU::$language = SupportedLanguages::ES;

            PayU::$isTest = ($this->testmode == "yes") ? true : false;

            $reference = "order_tb".time().'_'.$order_id;

            $value = $order->get_total();

            $currentToken = (wp_get_session_token() == '') ? $_COOKIE['woocommerce_cart_hash'] : wp_get_session_token();

            $parameters = array(

                                PayUParameters::VALUE => $value,
                                //Ingrese aquí el identificador de la cuenta.
                                PayUParameters::ACCOUNT_ID => $this->settings['account_id'],
                                //Ingrese aquí el código de referencia.
                                PayUParameters::REFERENCE_CODE => $reference,
                                //Ingrese aquí la descripción.
                                PayUParameters::DESCRIPTION => "Compra de producto en holycosmetics",

                                PayUParameters::CURRENCY => "COP",

                                //Ingrese aquí el email del comprador.
                                PayUParameters::BUYER_EMAIL => $order->billing_email,
                                //Ingrese aquí el nombre del pagador.
                                PayUParameters::PAYER_NAME => $order->billing_first_name." ".$order->billing_last_name,
                                //Ingrese aquí el email del pagador.
                                PayUParameters::PAYER_EMAIL => $order->billing_email,
                                //Ingrese aquí el teléfono de contacto del pagador.
                                PayUParameters::PAYER_CONTACT_PHONE=> $_POST['phoneNumber'],

                                // -- infarmación obligatoria para PSE --
                                //Ingrese aquí el código pse del banco.
                                PayUParameters::PSE_FINANCIAL_INSTITUTION_CODE => $_POST['bank'],
                                //Ingrese aquí el tipo de persona (N natural o J jurídica)
                                PayUParameters::PAYER_PERSON_TYPE => $_POST['bt_client_type'],
                                //Ingrese aquí el documento de contacto del pagador.
                                PayUParameters::PAYER_DNI => ($_POST['bt_id_number'] == '') ? $order->billing_cedula : $_POST['bt_id_number'],
                                //Ingrese aquí el tipo de documento del pagador: CC, CE, NIT, TI, PP,IDC, CEL, RC, DE.
                                PayUParameters::PAYER_DOCUMENT_TYPE => $_POST['bt_id_type'],

                                //Ingrese aquí el nombre del método de pago
                                PayUParameters::PAYMENT_METHOD => "PSE",

                                //Ingrese aquí el nombre del pais.
                                PayUParameters::COUNTRY => PayUCountries::CO,

                                //IP del pagadador
                                PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],
                                //Cookie de la sesión actual.
                                PayUParameters::PAYER_COOKIE=> $currentToken,
                                //Cookie de la sesión actual.
                                PayUParameters::USER_AGENT=>  $_SERVER['HTTP_USER_AGENT'],

                                //Página de respuesta a la cual será redirigido el pagador.
                                PayUParameters::RESPONSE_URL=> get_site_url().'/wc-api/tbnotification/',

                                PayUParameters::NOTIFY_URL => get_site_url().'/wc-api/enotification/',


                                );

            $response = PayUPayments::doAuthorizationAndCapture($parameters);

            if($response){

                if($response->transactionResponse->state=="PENDING"){

                    $response->transactionResponse->pendingReason;

                    $response->transactionResponse->extraParameters->BANK_URL;

                    if (isset($response->transactionResponse->extraParameters->BANK_URL)) {

                        /*if (!isset($_POST['woocommerce_pay'])) {

                            $order->add_order_note( __('PAYU_TB: Se redirecciona a PSE para pagar.') );

                            $order->save();*/

                            return array(
                                         'result' => 'success',
                                         'redirect' => $response->transactionResponse->extraParameters->BANK_URL,
                                         'order_id' => $order_id 
                                         );

                        /*}else{

                            $order->add_order_note( __('PAYU_TB: Se redirecciona a PSE para pagar.') );

                            $order->save();

                            wp_redirect($response->transactionResponse->extraParameters->BANK_URL);
                            exit();

                        }*/



                    }else{

                        $order->add_order_note( __('PAYU_TB: La redireccion a PSE fallo.') );

                        $order->save();

                        if (!isset($_POST['woocommerce_pay'])) {

                            throw new Exception("Ocurrio un error en el pago, vuelve a intentarlo.", 1);

                        }else{

                            wc_add_notice(  "Ocurrio un error en el pago, vuelve a intentarlo.", 'error' );

                            $location = $_SERVER['HTTP_REFERER'];
                            wp_safe_redirect($location);
                            exit();

                        }


                    }


                }else{

                    if (!isset($_POST['woocommerce_pay'])) {

                        throw new Exception("Ocurrio un error en el pago, vuelve a intentarlo.", 1);

                    }else{

                        wc_add_notice(  "Ocurrio un error en el pago, vuelve a intentarlo.", 'error' );

                        $location = $_SERVER['HTTP_REFERER'];
                        wp_safe_redirect($location);
                        exit();

                    }
                }

            }

        }
        /* Output for the order received page.   */


        public function webhook() {

            $posted = $_REQUEST;

            if ( ! empty( $posted['transactionState'] ) && ! empty( $posted['referenceCode'] ) ) {
                $this->payulatam_return_process($posted);
            }
            if ( ! empty( $posted['state_pol'] ) && ! empty( $posted['reference_sale'] ) ) {
                $this->payulatam_confirmation_process($posted);
            }

        }

        public function payulatam_return_process($posted){

            global $woocommerce;

            $reference_code = (isset($posted['referenceCode'])) ? $posted['referenceCode'] : null;

            $refCode = explode('_', $reference_code);

            $order_id = $refCode[2];

            $orderUrlKey = get_post_meta($order_id, '_order_key',true);

            $returnUrl = wc_get_endpoint_url( 'order-received', $order_id, wc_get_page_permalink( 'checkout' ));

            $returnUrl = $returnUrl.$order_id.'/?key='.$orderUrlKey;

            $order = new WC_Order( $order_id );

            if ($posted['lapTransactionState'] == "PENDING"){

                $order->update_status('on-hold', __( 'PAYU_TB: La orden quedo pendiente por PSE.', 'woocommerce' ));

                $order->save();

                $woocommerce->cart->empty_cart();

                update_post_meta($order_id , 'pending_transaction_response', json_encode($posted));



            }elseif ($_GET['lapTransactionState'] == "DECLINED") {

                $order->update_status('pending', __( 'PAYU_TB: El pago fue rechazado por PSE', 'woocommerce' ));

                $order->save();

                update_post_meta($order_id, 'failed_transaction_response', json_encode($posted));


            }elseif ($_GET['lapTransactionState'] == "APPROVED") {

                update_post_meta($order_id, 'payu_tb_approved_transaction_response', json_encode($posted));
                // Mark as on-hold
                $order->update_status('processing', __( 'PAYU_TB: Orden confirmada por PSE con id de transaccion: '.$posted['transactionId'], 'woocommerce' ));
                // Reduce stock levels
                $order->reduce_order_stock();
                // Remove cart
                $woocommerce->cart->empty_cart();
                // Return thankyou redirect



            }


            header('Location: '.$returnUrl);

            exit;
        }

        public function payulatam_confirmation_process($posted){

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
