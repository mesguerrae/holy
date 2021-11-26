<?php
$gmwcp_enable_single_product = get_option( 'gmwcp_enable_single_product' );
$gmwcp_single_display_location = get_option( 'gmwcp_single_display_location' );
?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwcp_options_group' ); ?>
	<table class="form-table">
		<tr valign="top">
            <th scope="row">
               <label for="gmwcp_enable_single_product"><?php _e('Enable Single Product Page', 'gmwsvs'); ?></label>
            </th>
            <td>
               <input class="regular-text" type="checkbox" id="gmwcp_enable_single_product" <?php echo (($gmwcp_enable_single_product=='yes')?'checked':'') ; ?> name="gmwcp_enable_single_product" value="yes" />
            </td>
         </tr>
		<tr>
			<th scope="row"><label><?php _e('Display Location', 'gmwcp'); ?></label></th>
			<td>
				<input type="radio" name="gmwcp_single_display_location" <?php echo ($gmwcp_single_display_location=='before')?'checked':''; ?> value="before"><?php _e('Before Woocommerce Product Meta', 'gmwcp'); ?>
				<input type="radio" name="gmwcp_single_display_location" <?php echo ($gmwcp_single_display_location=='after')?'checked':''; ?> value="after"><?php _e('After Product Title', 'gmwcp'); ?>
			</td>
		</tr>
		
	</table>
	<?php  submit_button(); ?>
</form>