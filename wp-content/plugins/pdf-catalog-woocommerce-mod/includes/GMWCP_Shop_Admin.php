<?php
$gmwcp_shop_enable_product = get_option( 'gmwcp_shop_enable_product' );
$gmwcp_shop_display_location = get_option( 'gmwcp_shop_display_location' );

?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwcp_shop_options_group' ); ?>
	<table class="form-table">
		 <tr valign="top">
            <th scope="row">
               <label for="gmwcp_shop_enable_product"><?php _e('Enable Shop Page', 'gmwcp'); ?></label>
            </th>
            <td>
               <input class="regular-text" type="checkbox" id="gmwcp_shop_enable_product" <?php echo (($gmwcp_shop_enable_product=='yes')?'checked':'') ; ?> name="gmwcp_shop_enable_product" value="yes" />
            </td>
         </tr>
         <tr>
			<th scope="row"><label><?php _e('Display Location', 'gmwcp'); ?></label></th>
			<td>
				<input type="radio" name="gmwcp_shop_display_location" <?php echo ($gmwcp_shop_display_location=='before')?'checked':''; ?> value="before"><?php _e('Before Shop Loop', 'gmwcp'); ?>
				<input type="radio" name="gmwcp_shop_display_location" <?php echo ($gmwcp_shop_display_location=='after')?'checked':''; ?> value="after"><?php _e('After Shop Loop', 'gmwcp'); ?>
			</td>
		</tr>
	</table>
	<?php  submit_button(); ?>
</form>
				