<div class="wrap">
    <h2><?php _e('Register new affiliate', 'affiliate-suite'); ?></h2>
    
    <?php Affiliate_Suite::do_notices(); ?>

    <p>
        Affiliate accounts are just regular user acconts with some extra fields.
        They are assigned with an special <i>Role</i> (as_affiliate) and will have no access to your WordPress admin dashboard.
        To learn more about how the affiliate accounts work and how your affiliates can check their referrals, check the documentation.
    </p>

    <!-- Tab links -->
    <div class="tab">
        <button class="tablinks active" onclick="openTab(event, 'new-user')">Register new affiliate</button>
        <button class="tablinks" onclick="openTab(event, 'convert-user')">Convert existing user to affiliate</button>
    </div>

    <!-- Tab content -->
    <div id="new-user" class="tabcontent" style="display: block">
        <h3>Register new user as affiliate</h3>
        <?php include 'partials/add-new-affiliate-form.php'; ?>
    </div>

    <div id="convert-user" class="tabcontent">
        <h3>Convert existing user to affiliate</h3>
        <?php include 'partials/convert-user-to-affiliate-form.php'; ?>
    </div>

    

</div><!-- .wrap -->
