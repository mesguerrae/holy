<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<a href="#!" class="wc_payment_method"><div class="accordion_head payment_method_<?php echo esc_attr( $gateway->id ); ?>" payment-method="<?php echo esc_attr( $gateway->id ); ?>"><?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?><span class="plusminus"></span></div></a>
<div class="accordion_body" style="display: none;">
    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" >
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</div>


