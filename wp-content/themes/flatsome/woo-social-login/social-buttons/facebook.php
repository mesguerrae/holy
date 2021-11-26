<?php
/**
 * Facebook Button Template
 * 
 * Handles to load facebook button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/facebook.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php esc_html_e( 'Connect with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" class="woo-slg-social-login-facebook woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fb-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with Facebook', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>

		<!-- FACEBOOK -->
		<a class="sc-btn sc--flat sc--facebook" title="<?php esc_html_e( 'Connect with Facebook', 'wooslg');?>" data-action="connect" data-plugin="woo-slg" data-popupwidth="600" data-popupheight="800" rel="nofollow" href="<?php echo esc_url($facebookClass->woo_slg_get_login_url()); ?>" >
		  <span class="sc-icon">
		      <svg viewBox="0 0 33 33" width="25" height="25" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M 17.996,32L 12,32 L 12,16 l-4,0 l0-5.514 l 4-0.002l-0.006-3.248C 11.993,2.737, 13.213,0, 18.512,0l 4.412,0 l0,5.515 l-2.757,0 c-2.063,0-2.163,0.77-2.163,2.209l-0.008,2.76l 4.959,0 l-0.585,5.514L 18,16L 17.996,32z"></path></g></svg>
		  </span>
		  <span class="sc-text">
		      <?php  esc_html_e( 'Ingresa con Facebook'); ?>
		  </span>
		</a>

	<?php } ?>
</div>