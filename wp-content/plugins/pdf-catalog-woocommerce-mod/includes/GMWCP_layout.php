<?php
$gmpcp_background_color = get_option( 'gmpcp_background_color' );
$gmpcp_item_background_color = get_option( 'gmpcp_item_background_color' );
?>
<div class="inside">
    <form action="#" method="post" id="wp_gmpcp_layout">
        <?php wp_nonce_field( 'gmpcp_nonce_action_layout', 'gmpcp_nonce_field_layout' ); ?>
        <h3><?php _e('Settings', 'gmpcp'); ?></h3>
       
        <table class="form-table">
            
            
            <tr>
                <th scope="row"><label><?php _e('Background Color', 'gmtrip'); ?></label></th>
                <td>
                   <input type="text"  class="gmpcp-color-field" name="gmpcplayotarr[gmpcp_background_color]" value="<?php echo $gmpcp_background_color; ?>">
                   <p class="description">
                        <?php _e('Enter Color Like <strong>#fff</strong>. Default will be take <strong>#fff</strong>', 'gmtrip'); ?>
                    </p>
                   
                </td>
            </tr>
            <tr>
                <th scope="row"><label><?php _e('Item Background Color', 'gmtrip'); ?></label></th>
                <td>
                   <input type="text"  class="gmpcp-color-field" name="gmpcplayotarr[gmpcp_item_background_color]" value="<?php echo $gmpcp_item_background_color; ?>">
                   <p class="description">
                        <?php _e('Enter Color Like <strong>#000</strong>. Default will be take <strong>#000</strong>', 'gmtrip'); ?>
                    </p>
                   
                </td>
            </tr>
            
            
        </table>
        
        <p class="submit">
            <input type="hidden" name="action" value="wp_gmpcp_layout">
            <input type="submit" name="submit"  class="button button-primary" value="Save">
        </p>
    </form>
</div>