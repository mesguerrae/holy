<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = apply_filters( 'wp-multi-step-checkout-timeline', array(
  'login' => array(
    'order' => 0,
    'label' => $t_login,
  ),
  'billing' => array(
    'order' => 1,
    'label' => $t_billing,
  ),
  'shipping' => array(
    'order' => 2,
    'label' => $t_shipping,
  ),
  'order' => array(
    'order' => 3,
    'label' => $t_order,
  ),
  'payment' => array(
    'order' => 4,
    'label' => $t_payment,
  ),
  'review' => array(
    'order' => 5,
    'label' => 'Revisión',
  ),
));

if ( !isset($c_sign) ) $c_sign = '&';
if ( !$show_shipping_step) unset($steps['shipping']);
if ( !$show_login_step) unset($steps['login']);

if ( $unite_billing_shipping ) {
    $steps['billing']['label'] = $t_billing . ' '.esc_html($c_sign).' ' . $t_shipping; 
    unset($steps['shipping']);
}
if ( $unite_order_payment) {
    $steps['order']['label'] = $t_order . ' '.esc_html($c_sign).' ' . $t_payment; 
    unset($steps['payment']);
}

if ( class_exists('WooCommerce_Germanized') && !$unite_order_payment ) {
    $tmp = $steps['order']['order'];
    $steps['order']['order'] = $steps['payment']['order'];
    $steps['payment']['order'] = $tmp;
} 

if ( !function_exists('wpmc_sort_by_order') ) {
    function wpmc_sort_by_order($a, $b) {
        return $a['order'] - $b['order'];
    }
}
usort($steps, 'wpmc_sort_by_order');

?>

<!-- The steps tabs -->
<div class="wpmc-tabs-wrapper">
  <ul class="wpmc-tabs-list wpmc-<?php echo count($steps); ?>-tabs">
    <?php
    $i = 0;
    foreach( $steps as $_id => $_step ) :
      $class = ( $i == 0 ) ? ' current' : '';
      ?>
              <li class="wpmc-tab-item<?php echo $class; ?> wpmc-<?php echo $_id; ?>">
							<div class="wpmc-tab-number" number="<?php echo $i ?>"><?php echo $i = $i + 1; ?></div>
							<div class="wpmc-tab-text"><?php echo $_step['label']; ?></div>
				</li>
      <?php endforeach; ?>
		</ul>
</div>
