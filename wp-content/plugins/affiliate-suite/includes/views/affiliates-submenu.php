<div class="wrap">
    <h2><?php _e('Affiliates', 'affiliate-suite'); ?></h2>
    
    <a class="affiliatesuite__btn__primary" href="<?= admin_url('/admin.php?page=add-affiliate'); ?>" style="float: right"><?php _e('Add new', 'affiliate-suite'); ?></a>
    
    <?php if ( ! $affiliates ) {
        echo '<h4>No affilliates registered yet.</h4>';
        return;
    } ?>

    <table id="affiliatesuite__affiliatesList" class="affiliatesuite__table">
        <thead>
            <tr>
                <th><?php _e('ID', 'affiliate-suite'); ?></th>
                <th><?php _e('Name', 'affiliate-suite'); ?></th>
                <th><?php _e('Email', 'affiliate-suite'); ?></th>
                <th><?php _e('Report', 'affiliate-suite'); ?></th>
                <th><?php _e('Update', 'affiliate-suite'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($affiliates as $affiliate) : ?>
                <tr>
                    <td><?= $affiliate->ID ?></td>
                    <td><?= $affiliate->first_name . ' ' . $affiliate->last_name ?></td>
                    <td><?= $affiliate->user_email ?></td>
                    <td><a href="<?= admin_url("/admin.php?page=single-affiliate-report&affiliate=$affiliate->ID"); ?>"><?php _e('View Report', 'affiliate-suite'); ?></a></td>
                    <td><a href="<?= admin_url("/admin.php?page=edit-affiliate&affiliate=$affiliate->ID"); ?>"><?php _e('Edit', 'affiliate-suite'); ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        jQuery(document).ready( function () {
            jQuery('#affiliatesuite__affiliatesList').DataTable();
        });
    </script>
</div><!-- .wrap -->


