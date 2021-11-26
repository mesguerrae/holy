<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Antifraud_Notification_Order_Email extends WC_Email {

    /**
     * Set email defaults
     *
     * @since 0.1
     */
    public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_antifraud_notification_order';

        // this is the title in WooCommerce Email settings
        $this->title = 'Notificacion Antifraude';

        // this is the description in WooCommerce email settings
        $this->description = 'Este email notifica al administrador de posibles fraudes de parte de CS.';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = 'Posible fraude!';

        $this->subject = 'Posible fraude!';

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_html  = 'emails/admin-antifraud-notification.php';

        $this->template_plain = 'emails/plain/admin-new-order.php';

        // Trigger on new paid orders
        add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $this, 'trigger' ) );
        add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
        add_action( 'woocommerce_order_status_pending_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
        add_action( 'woocommerce_order_status_failed_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
        add_action( 'woocommerce_order_status_failed_to_completed_notification', array( $this, 'trigger' ), 10, 2 );
        add_action( 'woocommerce_order_status_cancelled_to_processing_notification', array( $this, 'trigger' ), 10, 2 );
        add_action( 'woocommerce_order_status_cancelled_to_completed_notification', array( $this, 'trigger' ), 10, 2 );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // this sets the recipient to the settings defined below in init_form_fields()
        $this->recipient = $this->get_option( 'recipient' );

        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
    }

    /**
     * Determine if the email should actually be sent and setup email merge variables
     *
     * @since 0.1
     * @param int $order_id
     */
    public function trigger( $order_id ) {

        // bail if no order ID is present
        if ( ! $order_id )
            return;

        // setup order object
        $this->object = new WC_Order( $order_id );

        // replace variables in the subject/headings
        $this->find[] = '{order_date}';



        $this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

        $this->find[] = '{order_number}';

        $this->replace[] = $this->object->get_order_number();

        /*payu_tc_approved_transaction_response
        payu_tb_approved_transaction_response
        payu_notification_response*/

        $payment_method = $this->object->get_payment_method();

        $notify = false;

        switch ($payment_method) {
            case 'payu_efectivo':
                $transaction = get_post_meta($this->object->get_id(), 'payu_notification_response', true);

                if($transaction == ''){

                    $notify = true;
                    break;
                }

                $transaction =  json_decode($transaction, true);

                $state = isset($transaction['state_pol']) ? $transaction['state_pol'] : false;

                if ($state != 4) {

                    $notify = true;
                }

                break;

            case 'payu_trasferencia_bancaria':
                $transaction = get_post_meta($this->object->get_id(), 'payu_tb_approved_transaction_response', true);

                if($transaction == ''){

                    $notify = true;
                    break;
                }

                $transaction =  json_decode($transaction, true);

                $state = isset($transaction['polTransactionState']) ? $transaction['polTransactionState'] : false;

                if ($state != 4) {

                    $notify = true;
                }

                break;

            case 'payu_tc':
                $transaction = get_post_meta($this->object->get_id(), 'payu_tc_approved_transaction_response', true);

                if($transaction == ''){

                    $notify = true;

                    break;
                }

                $transaction =  json_decode($transaction, true);

                $state = isset($transaction['state']) ? $transaction['state'] : false;

                if ($state != 'APPROVEDDDD') {

                    $notify = true;
                }

                break;

            default:
                # code...
                break;
        }

        if ( ! $this->is_enabled() || ! $this->get_recipient() || ! $notify )
            return;


        // woohoo, send the email!
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );


    }

    /**
     * get_content_html function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_html() {
        ob_start();

        $current_user = wp_get_current_user();

        woocommerce_get_template( $this->template_html, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'user'          => $current_user->data->user_login
        ) );
        return ob_get_clean();
    }


    /**
     * get_content_plain function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        woocommerce_get_template( $this->template_plain, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading()
        ) );
        return ob_get_clean();
    }

    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable this email notification',
                'default' => 'yes'
            ),
            'recipient'  => array(
                'title'       => 'Recipient(s)',
                'type'        => 'text',
                'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
                'placeholder' => '',
                'default'     => ''
            ),
            'subject'    => array(
                'title'       => 'Subject',
                'type'        => 'text',
                'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => 'Email Heading',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => 'Email type',
                'type'        => 'select',
                'description' => 'Choose which format of email to send.',
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
            )
        );
    }

} // end \WC_Expedited_Order_Email class