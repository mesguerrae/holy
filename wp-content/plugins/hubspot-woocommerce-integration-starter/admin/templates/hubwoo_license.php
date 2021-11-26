<?php
  global $hubwoo;

  if( isset( $_POST[ 'hubwoo_activate_license' ] ) ) {

    unset( $_POST[ 'hubwoo_activate_license' ] );

    $license_key = $_REQUEST['hubwoo_license_key']; 

    $api_params = array(
      'slm_action'        => 'slm_activate',
      'secret_key'        => HUBWOO_STARTER_ACTIVATION_SECRET_KEY,
      'license_key'       => $license_key,
      '_registered_domain' => $_SERVER['SERVER_NAME'],
      'item_reference'    => urlencode( HUBWOO_STARTER_ITEM_REFERENCE ),
      'product_reference' => 'MWBPK-10877'
    );
    
    $hubwoo->activate_license( $api_params );
  }
?>


<?php
	$message = __( 'Please enter the license key for this product to activate it. You were given a license key when you purchased this item in the confirmation email.' , 'hubwoo' );
?>
<div class="hubwoo-license-container">
  <div class="hubwoo-license-form-header hubwoo-common-header">
  	<h2><?php _e("License Activation","hubwoo") ?></h2>
  	<div class="hubwoo-license-form-desc"><?php echo $message ?></div>
  </div>
  <div class="hubwoo-license-body">
    <form class="hubwoo-license-form" action="" method="post">
    <div class="hubwoo-license">
      <label>
      <?php _e("License Key","hubwoo") ?>
      </label>
      <input class="regular-text" type="text" id="hubwoo_license_key" name="hubwoo_license_key" value="<?php echo get_option('hubwoo_starter_license_key',""); ?>" >
    </div>
    <div class="hubwoo-license-form-submit">
      <p class="submit">
        <input type="submit" name="hubwoo_activate_license" value="<?php _e("Save & Activate","hubwoo")?>" class="button-primary" />
      </p>
    </div>
    </form>
  </div>
</div>