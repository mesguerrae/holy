<?php
/**
 * Admin View: CSV Import steps.
 *
 * @author   SomewhereWarm <info@somewherewarm.com>
 * @package  WooCommerce Gift Cards
 * @since    1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ol class="wc-progress-steps">
	<?php foreach ( $this->steps as $step_key => $step ) : ?>
		<?php
		$step_class = '';
		if ( $step_key === $this->step ) {
			$step_class = 'active';
		} elseif ( array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true ) ) {
			$step_class = 'done';
		}
		?>
		<li class="<?php echo esc_attr( $step_class ); ?>">
			<?php echo esc_html( $step['name'] ); ?>
		</li>
	<?php endforeach; ?>
</ol>
