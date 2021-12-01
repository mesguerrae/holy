<?php
/**
 * Checkout Form
 *
 * This is an overridden copy of the woocommerce/templates/checkout/form-checkout.php file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check the WooCommerce MultiStep Checkout options
$options = get_option('wmsc_options');
require_once 'settings-array.php';
if ( !is_array($options) || count($options) === 0 ) {
    $defaults = get_wmsc_settings('wp-multi-step-checkout');
    $options = array();
    foreach($defaults as $_key => $_value ) {
        $options[$_key] = $_value['value'];
    }
} 
$options = array_map('stripslashes', $options);

// Use the WPML values instead of the ones from the admin form
if ( isset($options['t_wpml']) && $options['t_wpml'] == 1 ) {
    $defaults = get_wmsc_settings('wp-multi-step-checkout');
    foreach($options as $_key => $_value ) {
        if( substr($_key, 0, 2) == 't_' && $_key != 't_sign') {
            $options[$_key] = $defaults[$_key]['value'];
        }
    }
}

extract($options);

if ( !$show_shipping_step ) $unite_billing_shipping = false;

/*
$unite_billing_shipping = false;
$unite_order_payment = false;
$show_shipping_step = true;
$show_back_to_cart_button = true;
*/

// check the WooCommerce options
$is_registration_enabled = version_compare( '3.0', WC()->version, '<=') ? $checkout->is_registration_enabled() : get_option( 'woocommerce_enable_signup_and_login_from_checkout' ) == 'yes'; 
$has_checkout_fields = version_compare( '3.0', WC()->version, '<=') ? $checkout->get_checkout_fields() : (is_array($checkout->checkout_fields) && count($checkout->checkout_fields) > 0 );
$show_login_step = ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) ? false : true;
$stop_at_login = ( ! $is_registration_enabled && $checkout->is_registration_required() && ! is_user_logged_in() ) ? true : false;
$checkout_url = apply_filters( 'woocommerce_get_checkout_url', version_compare( '2.5', WC()->version, '<=' ) ? wc_get_checkout_url() : WC()->cart->get_checkout_url() );

// show the tabs
include_once(dirname(__FILE__) .'/form-tabs.php');

?>

<div style="clear: both;"></div>

<div class="wpmc-steps-wrapper">

<?php wc_print_notices(); ?>



<?php if( $show_login_step ) : ?>
	<!-- Step: Login -->
	<div class="wpmc-step-item wpmc-step-login">
			<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
			<div id="checkout_login" class="woocommerce_checkout_login wp-multi-step-checkout-step">
			<?php
			woocommerce_login_form(
				array(
					'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing &amp; Shipping section.', 'wp-multi-step-checkout' ),
					'redirect' => wc_get_page_permalink( 'checkout' ),
					'hidden'   => false,
				)
			);
			//echo do_shortcode( '[woo_social_login title="" networks="null"][/woo_social_login]' );
			?>
			</div>
			<?php
			if ( $stop_at_login ) {
				echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
			}
			?>
	</div>
<?php endif; ?>

<?php if ( $stop_at_login ) { echo '</div>'; return; } ?>
<div id="checkout_coupon" class="woocommerce_checkout_coupon" style="display: none;">
    <?php do_action( 'wpmc-woocommerce_checkout_coupon_form', $checkout ); ?>
</div>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">

    <div class="container">

        <div class="layout">
            <div class="col col-main" role="main">
                    <?php if ( $has_checkout_fields ) : ?>

                        <!-- Step: Billing -->
                        <div class="wpmc-step-item wpmc-step-billing">
                            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>


                        </div>
                    <?php endif; ?>


                <?php if ( class_exists('WooCommerce_Germanized') ) : ?>
                    <?php
                        $step_payment_div = '<div class="wpmc-step-item wpmc-step-payment">';
                        $step_order_div = '</div><div id="order_review" class="wpmc-step-item wpmc-step-review wpmc-order">';
                        if ($unite_order_payment) {
                            $step_payment_div = '<div id="order_review" class="wpmc-step-item wpmc-step-payment wpmc-order">';
                            $step_order_div = '';
                        }         
                    ?>
                    <!-- Step: Payment Info -->
                    <?php echo $step_payment_div; ?>
                        <h3 id="payment_heading"><?php _e( 'Choose a Payment Gateway', 'woocommerce-germanized' ) ?></h3>
                        <?php do_action( 'wpmc-woocommerce_checkout_payment' ); ?> 


                    <!-- Step: Review Order -->
                    <?php echo $step_order_div; ?>
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                        <h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ) ?></h3>
                        <?php do_action( 'wpmc-woocommerce_order_review' ); ?>
                        <?php if( function_exists( 'woocommerce_gzd_template_order_submit' ) ) { woocommerce_gzd_template_order_submit(); } ?>
                    </div>

                <?php else : ?>
                    <!-- Step: Review Order -->
                    <div id="order_review" class="wpmc-step-item wpmc-step-review wpmc-order">
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                        <h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>


                    <!-- Step: Payment Info -->
                    <?php if ( ! $unite_order_payment ) : ?></div><div class="wpmc-step-item wpmc-step-payment"><?php endif; ?>
                        <h3 id="payment_heading"><?php _e( 'Payment', 'woocommerce' ); ?></h3>
                        <?php do_action( 'wpmc-woocommerce_checkout_payment' ); ?>
                    </div>

                    <!-- Step: Final Review Order -->
                    <div id="order_review" class="wpmc-step-item wpmc-step-review wpmc-order">

                        <?php do_action( 'woocommerce_custom_order_review' ); ?>
                        <?php wc_get_template( 'checkout/terms.php' ); ?>
                        <?php do_action( 'woocommerce_custom_order_place_position' ); ?>
                        

                    </div>
                <?php endif; ?>
            </div>
            <div class="col col-complementary" role="complementary">

                <div class="accordion_container_or"> 
                    <div class="accordion_head_or order_review_cordeon_title"><?php echo _e('Resumen de la orden') ?> <i class="down"></i></label></div>
                    <div class="accordion_body_or order_review_acordeon_body" style="display: none;">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            <?php do_action( 'wpmc-woocommerce_order_review' ); ?>
                    </div> 
                </div>  

            </div>
        </div>
    </div>
</form>

<div id="woocommerce_before_checkout_form" class="woocommerce_before_checkout_form" data-step="<?php echo apply_filters('woocommerce_before_checkout_form_step', 'step-review'); ?>" style="display: none;">
    <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>

<?php include_once(dirname(__FILE__).'/form-buttons.php'); ?>

