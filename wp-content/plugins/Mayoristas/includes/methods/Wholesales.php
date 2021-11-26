<?php
    
    
    class WC_Wholesales_Payment_Gateway extends WC_Payment_Gateway {
        /**
         * Constructor for the gateway.
         *
         * @return void
         */
                
        public function __construct() {

            global $woocommerce;

            $this->id             = 'wholesales';
            $this->icon               = apply_filters('woocommerce_custom_gateway_icon', '');
            $this->has_fields         = false;
            $this->method_title       = __( 'Mayoristas', $this->domain );

            $this->method_description = __( 'Metodo pago mayoristas.', $this->domain );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables
            $this->title        = $this->get_option( 'title' );
            $this->description  = $this->get_option( 'description' );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_custom', array( $this, 'thankyou_page' ) );


        }
        
        public function init_form_fields(){
            
            $this->form_fields = array(
                'enabled' => array(
                    'title'   => __( 'Enable/Disable', $this->domain ),
                    'type'    => 'checkbox',
                    'label'   => __( 'Enable Custom Payment', $this->domain ),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title'       => __( 'Mayoristas', $this->domain ),
                    'type'        => 'text',
                    'description' => __( 'Mayoristas', $this->domain ),
                    'default'     => __( 'Mayoristas', $this->domain ),
                    'desc_tip'    => true,
                ),
                
                'description' => array(
                    'title'       => __( 'Description', $this->domain ),
                    'type'        => 'textarea',
                    'description' => __( 'Payment method description that the customer will see on your checkout.', $this->domain ),
                    'default'     => __('Payment Information', $this->domain),
                    'desc_tip'    => true,
                ),
                
            );
                                       
        }
        
        public function payment_fields() {
            
            
            // I will echo() the form, but you can close PHP tags and print it directly in HTML
            //echo 'Metodo de pago para proveedores';
            $text = get_option( 'wholesales_success_message', 1 );
            echo $text;
            // Add this action hook if you want your custom gateway to support it
            do_action( 'woocommerce_credit_card_form_start', $this->id );
            
          
        }


        public function validate_fields(){
            
            return true;
            
        }
        
        /* Process the payment and return the result. */
        public function process_payment ($order_id) {
            
            global $woocommerce;
            
            $order = new WC_Order( $order_id );

            $order->update_status('on-hold', __(  "Metodo de pago proveedores ", 'woocommerce' ));
                    $order->save();

            $woocommerce->cart->empty_cart();
            
            return array(
             'result'     => 'success',
             'redirect'    => $this->get_return_url( $order )
             );            
        }
        /* Output for the order received page.   */

        /* Output for the order received page.   */
        public function thankyou() {
          echo $this->instructions != '' ? wpautop( $this->instructions ) : '';
        }
    }
