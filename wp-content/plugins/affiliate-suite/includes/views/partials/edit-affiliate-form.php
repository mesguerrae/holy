<form id="affiliatessute_edit_affiliate" class="affiliatesuite__form" method="post" action="">

    <input type="hidden" name="action" value="update">
    <input type="hidden" name="role" value="as_affiliate">

    <h3><?php _e('User Data', 'affiliate-suite') ?></h3>

    <div class="affiliatesuite__formgroup">
        <label for="affiliate_id">ID</label>
        <input name="affiliate_id" value="<?= $affiliate->ID ?>" type="text" disabled>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="user_login">Username</label>
        <input name="user_login" value="<?= $affiliate->user_login ?>" type="text" disabled>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="affiliate_link">Affilate link</label>
        <input name="affiliate_link" value="<?= site_url() . '?ar=' . $affiliate->ID ?>" type="text" disabled>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="user_email">Email</label>
        <input name="user_email" value="<?= $affiliate->user_email ?>" type="email" placeholder="example@email.com" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="first_name">First name</label>
        <input name="first_name" value="<?= $affiliate->first_name ?>" type="text" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="last_name">Last name</label>
        <input name="last_name" value="<?= $affiliate->last_name ?>" type="text" required>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="user_pass">Password</label>
        <input name="user_pass" type="text" placeholder="<?php _e('change password', 'affiliate-suite') ?>" value="" autocomplete="off">
        <p><?php _e('Leave blank to mantain current password.', 'affiliate-suite'); ?></p>
    </div>

    <div class="affiliatesuite__divider"></div>

    <h3><?php _e('Affiliate Data', 'affiliate-suite') ?></h3>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_date_registered">Affiliate since:</label>
        <input id="date_registered_pickr" type="text" name="as_affiliate_date_registered" value="<?= $affiliate->affiliate_date_registered ?>" placeholder="<?php _e('Select a date', 'affiliate-suite'); ?>" >
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_status">Status</label>
        <select name="as_affiliate_status" required>
            <option value="active" <?php echo ($affiliate->status == 'active') ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?php echo ($affiliate->status == 'inactive') ? 'selected' : '' ?>>Inactive</option>
        </select>
        <p><?php _e('Inactive affiliates won\'t receive commissions even if someone uses their link or coupon.', 'affiliate-suite'); ?></span>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_rate">Affiliate rate (%)</label>
        <input name="as_affiliate_rate" value="<?= $affiliate->rate ?>" type="number" required>
        <p><?php _e('Percentage of the sale that this affiliate will earn.', 'affiliate-suite'); ?></p>
    </div>

    <div class="affiliatesuite__formgroup">
        <label for="as_affiliate_notes"><?php _e('Affiliate notes', 'affiliate-area'); ?></label><br>
        <textarea name="as_affiliate_notes" cols="40" rows="6"><?= $affiliate->notes ?></textarea>
    </div>

    <?php // WooCommerce Coupons
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        if ( in_array('woocommerce', Affiliate_Suite::get_active_channels()) ) : ?>
            <h3>WooCommerce Coupons</h3>
            <p>You can bind WooCommerce coupons to this affiliate, so every time a binded coupon 
            is used the affiliate will receive a comission.</p>

            <div class="affiliatesuite__formgroup">
                <input id="previously_selected_coupons" type="hidden" name="previously_selected_coupons" value="<?php echo ($affiliate->woocommerce_coupons != null) ? implode(',', $affiliate->woocommerce_coupons) : ''; ?>">

                <label for="as_affiliate_woocommerce_coupons"><?php _e('Coupons', 'affiliate-suite'); ?></label>
                <div id="bind_coupons"></div>
            </div>

        <?php endif;
    }

    do_action('as_suite_after_edit_affiliate_form', $affiliate);

    ?>

    <input type="submit" id="as_affiliate_submit" value="Update affiliate">

</form>

<form method="post" action="">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="affiliate_id" value="<?= $affiliate->ID ?>">
    <input type="submit" value="<?php _e('Delete affiliate', 'affiliate-suite'); ?>" 
        onclick="return confirmDeleteAffiliate()"
        style="background: red; color: white">
</form>

<script>
    function confirmDeleteAffiliate() {
        return confirm("Are you sure you want to delete this affiliate?");
    }

    // Flatpickr
    flatpickr( "#date_registered_pickr", {
        enableTime: true,
        dateFormat: "Y-m-d H:i"
    });

    // MagicSuggest
    jQuery(document).ready(function() {

        if ( document.getElementById('bind_coupons') ) {
            let selectedCoupons =  document.getElementById('previously_selected_coupons').value.split(',');

            jQuery.get(affiliateSuite.ajax_url, {'action': 'get_woocommerce_unbinded_coupons'}, function(response){

                let coupons = JSON.parse(response);

                let bindCoupons = jQuery('#bind_coupons').magicSuggest({
                    allowFreeEntries: false,
                    name: 'as_affiliate_woo_coupons',
                    data: coupons
                });

                if ( selectedCoupons != '' ) {
                    bindCoupons.setValue(selectedCoupons);
                }
            });
        }
    });
</script>
