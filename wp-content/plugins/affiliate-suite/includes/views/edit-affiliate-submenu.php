<div class="wrap">
    <h2><?php _e('Edit affiliate', 'affiliate-suite'); ?></h2>

    <a href="<?= admin_url('/admin.php?page=list-affiliates'); ?>"><?php _e('Affiliates list', 'affiliate-suite'); ?></a>

    <?php
        if( isset($_GET['updated']) ) {
            if ( $_GET['updated'] == 'true' ) {
                echo '<div class="affiliatesuite__notice">
                    <span class="affilliatesuite__noticeTitle"><b>Updated:</b></span>
                    <span class="affiliatesuite__noticeMessage">Affiliate has been successfully updated.</span>
                </div>';
            }
        } 
    ?>

    <?php include 'partials/edit-affiliate-form.php'; ?>
</div>