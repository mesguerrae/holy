<?php
/**
 * Gift Cards form in the Cart/Checkout
 *
 * Shows an HTML form in the cart/checkout page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/apply-gift-card-form.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce Gift Cards
 * @since   1.3.5
 * @version 1.3.5
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_gc_before_apply_gift_card_form' );

?><div class="add_gift_card_form">
	<h4><?php esc_html_e( 'Have a gift card?', 'woocommerce-gift-cards' ); ?></h4>
	<div id="wc_gc_cart_redeem_form">
		<div class="wc_gc_add_gift_card_form__notices"></div>
		<input placeholder="<?php esc_attr_e( 'Enter your code&hellip;', 'woocommerce-gift-cards' ); ?>" type="text" name="wc_gc_cart_code" id="wc_gc_cart_code" autocomplete="off" />
		<button type="button" name="wc_gc_cart_redeem_send" id="wc_gc_cart_redeem_send"><?php esc_html_e( 'Apply', 'woocommerce-gift-cards' ); ?></button>
	</div>
	<?php if ( wc_coupons_enabled() ) { ?>
		<form class="checkout_coupon mb-0" method="post">
			<div class="coupon">
				<h3 class="widget-title"><?php echo get_flatsome_icon( 'icon-tag' ); ?> <?php esc_html_e( 'Coupon', 'woocommerce' ); ?></h3><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="is-form expand" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
				<?php do_action( 'woocommerce_cart_coupon' ); ?>
				<input type="text">266x
			</div>
		</form>
		<?php } ?>
</div><?php

do_action( 'woocommerce_gc_after_apply_gift_card_form' );
?>
