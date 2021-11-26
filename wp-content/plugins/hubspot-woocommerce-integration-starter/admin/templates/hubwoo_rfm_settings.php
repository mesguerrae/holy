<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php

global $hubwoo;
$callname_lcne = $hubwoo::$lcne_callback_function;

if( isset( $_POST['hubwoo_rfm_settings']) && ! empty( $_POST ) ) {

	unset( $_POST["hubwoo_rfm_settings"] );

	if( isset($_POST["_wpnonce"]) ){
		unset( $_POST["_wpnonce"]);
	}
	if( isset( $_POST["_wp_http_referer"] ) ){
		unset( $_POST["_wp_http_referer"] );
	}
	if( isset( $_POST["paged"] ) ){
		unset( $_POST["paged"] );
	}
	if( isset( $_POST["save"] ) ){
		unset( $_POST["save"] );
	}
	foreach( $_POST as $key => $value ) {

		if( isset( $_POST[$key] ) ) {

			update_option( $key, $value );
		}
	}
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e( 'RFM Settings saved succesfully', 'hubwoo' ); ?></strong></p>
	</div>
	<?php
}
?>

<?php

if( Hubwoo::$callname_lcne() ) {

	$rfm_settings = new RFM_Configuration();
	$rfm_settings->prepare_items();
	?>
	<div class="hubwoo-rfm-form-header">
		<h2><?php _e("RFM Settings","hubwoo") ?></h2>
	</div>
	<form action="" method="post" id="hubwoo-rfm-form">
		<div class="hubwoo_rfm_settings">
		    <?php
		        $rfm_settings->display();
		    ?>
		</div>
		<p class="submit">
			<input type="submit" class="button button-primary" name="hubwoo_rfm_settings" value="<?php echo __("Save Changes","hubwoo") ?>">
		</p>
	</form>
	<?php
}
?>