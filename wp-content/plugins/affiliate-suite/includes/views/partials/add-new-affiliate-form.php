<form id="affiliatessute_add_affiliate" class="affiliatesuite__form" method="post" action="">

    <input type="hidden" name="action" value="create">
    <input type="hidden" name="role" value="as_affiliate">

    <h3><?php _e('User Data', 'affiliate-suite') ?></h3>

    <div class="affiliatesuite__formgroup">
        <label for="user_login">Username</label>
        <input name="user_login" type="text"required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="user_email">Email</label>
        <input name="user_email" type="email" placeholder="example@email.com" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="fist_name">First name</label>
        <input name="first_name" type="text" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="last_name">Last name</label>
        <input name="last_name" type="text" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="user_pass">Password</label>
        <input name="user_pass" type="text" value="" placeholder="<?php _e('Password', 'affiliate-suite'); ?>" autocomplete="off" required>
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
                <div id="bind_coupons"></div>
            </div>

        <?php endif;
    }
    ?>

    <input type="submit" id="as_affiliate_submit" value="Register new affiliate">

</form>

<script>
    // MagicSuggest
    jQuery(document).ready(function() {

        if (document.getElementById('bind_coupons')) {

            jQuery.get(affiliateSuite.ajax_url, {'action': 'get_woocommerce_unbinded_coupons'}, function(response){

                let coupons = JSON.parse(response);

                let bindCoupons = jQuery('#bind_coupons').magicSuggest({
                    allowFreeEntries: false,
                    name: 'as_affiliate_woo_coupons',
                    data: coupons
                });

            });
        }

    });
</script>