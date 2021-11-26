<form id="affiliatessute_add_affiliate" class="affiliatesuite__form" name="convert-to-affiliate" method="post" action="">

    <input type="hidden" name="action" value="convert">

    <h3><?php _e('Find user', 'affiliate-suite') ?></h3>

    <div class="affiliatesuite__formgroup">
        <label for="user_email">Email</label>
        <div id="magicsuggest_user_email"></div>
    </div>

    <div class="affiliatesuite__divider"></div>

    <h3><?php _e('Affiliate Data', 'affiliate-suite') ?></h3>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_status">Status</label>
        <select name="as_affiliate_status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <p><?php _e('Inactive affiliates won\'t receive commissions even if someone uses their link or coupon.', 'affiliate-suite'); ?></span>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_rate">Affiliate rate (%)</label>
        <input name="as_affiliate_rate" type="number" required>
        <p><?php _e('Percentage of the sale that this affiliate will earn.', 'affiliate-suite'); ?></p>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_notes"><?php _e('Affiliate notes', 'affiliate-area'); ?></label>
        <p><?php _e('Private notes. Only administrators will see this.'); ?></p>
        <textarea name="as_affiliate_notes" cols="40" rows="6"></textarea>
    </div>

    <?php // WooCommerce Coupons
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        if ( in_array('woocommerce', Affiliate_Suite::get_active_channels()) ) : ?>
            <h3>WooCommerce Coupons</h3>
            <p>You can bind WooCommerce coupons to this affiliate, so every time a binded coupon 
            is used the affiliate will receive a comission.</p>

            <div class="affiliatesuite__formgroup">
                <label for="as_affiliate_woocommerce_coupons"><?php _e('Coupons', 'affiliate-suite'); ?></label>
                <div id="convert_bind_coupons"></div>
            </div>

        <?php endif;
    }
    ?>

    <input type="submit" id="as_affiliate_submit" value="<?php _e('Convert to affiliate', 'affiliate-suite'); ?>">

</form>

<script>
    jQuery(document).ready(function() {
        // Search existing user
        jQuery.get(affiliateSuite.ajax_url, {'action': 'get_non_affiliates'}, function(response) {
            let users = JSON.parse(response);

            let usersData = [];

            users.forEach(user => {
                usersData.push({
                    'id': user.data.ID,
                    'name': user.data.user_email
                });
            });

            jQuery('#magicsuggest_user_email').magicSuggest({
                allowFreeEntries: false,
                name: 'user_id',
                required: true,
                data: usersData
            });
        });

        // WooCommerce Coupons
        if (document.getElementById('convert_bind_coupons')) {

            jQuery.get(affiliateSuite.ajax_url, {'action': 'get_woocommerce_unbinded_coupons'}, function(response){

                let coupons = JSON.parse(response);

                let bindCoupons = jQuery('#convert_bind_coupons').magicSuggest({
                    allowFreeEntries: false,
                    name: 'as_affiliate_woo_coupons',
                    data: coupons
                });

            });
        }
    
    });
</script>