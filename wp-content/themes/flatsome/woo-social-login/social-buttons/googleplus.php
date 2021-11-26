<?php
/**
 * Googleplus Button Template
 * 
 * Handles to load Googleplus button template
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
		
		<a title="<?php esc_html_e( 'Connect with Google', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-gp-icon"></i>
			<?php echo !empty($button_text) ? $button_text : esc_html__( 'Sign in with Google', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<!--<a title="<?php esc_html_e( 'Connect with Google', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus">
			<img src="<?php echo esc_url($gpimgurl);?>" alt="<?php esc_html_e( 'Google', 'wooslg');?>" />
		</a>-->

		<!-- GOOGLE PLUS -->
		<a title="<?php esc_html_e( 'Connect with Google', 'wooslg');?>" href="javascript:void(0);"  class="sc-btn sc--flat sc--google-plus woo-slg-social-login-googleplus">
		  <span class="sc-icon">
		      <svg viewBox="0 0 33 33" width="25" height="25" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M 17.471,2c0,0-6.28,0-8.373,0C 5.344,2, 1.811,4.844, 1.811,8.138c0,3.366, 2.559,6.083, 6.378,6.083 c 0.266,0, 0.524-0.005, 0.776-0.024c-0.248,0.475-0.425,1.009-0.425,1.564c0,0.936, 0.503,1.694, 1.14,2.313 c-0.481,0-0.945,0.014-1.452,0.014C 3.579,18.089,0,21.050,0,24.121c0,3.024, 3.923,4.916, 8.573,4.916 c 5.301,0, 8.228-3.008, 8.228-6.032c0-2.425-0.716-3.877-2.928-5.442c-0.757-0.536-2.204-1.839-2.204-2.604 c0-0.897, 0.256-1.34, 1.607-2.395c 1.385-1.082, 2.365-2.603, 2.365-4.372c0-2.106-0.938-4.159-2.699-4.837l 2.655,0 L 17.471,2z M 14.546,22.483c 0.066,0.28, 0.103,0.569, 0.103,0.863c0,2.444-1.575,4.353-6.093,4.353 c-3.214,0-5.535-2.034-5.535-4.478c0-2.395, 2.879-4.389, 6.093-4.354c 0.75,0.008, 1.449,0.129, 2.083,0.334 C 12.942,20.415, 14.193,21.101, 14.546,22.483z M 9.401,13.368c-2.157-0.065-4.207-2.413-4.58-5.246 c-0.372-2.833, 1.074-5.001, 3.231-4.937c 2.157,0.065, 4.207,2.338, 4.58,5.171 C 13.004,11.189, 11.557,13.433, 9.401,13.368zM 26,8L 26,2L 24,2L 24,8L 18,8L 18,10L 24,10L 24,16L 26,16L 26,10L 32,10L 32,8 z"></path></g></svg>
		  </span>
		  <span class="sc-text">
		      <?php esc_html_e( 'Ingresa con Google');?>
		  </span>
		</a>
	<?php } ?>
</div>