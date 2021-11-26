<div class="wrap">
    <h1><?php _e('Export Data', 'affiliate-suite'); ?></h1>
    <p>Export data to CSV format.</p>

    <div id="affiliatesuite__exportPageGrid">
        <div id="affiliatesuite__exportReferrals__basic" class="card">
            <h2><?php _e('Export Referrals (Basic)', 'affiliate-suite'); ?></h2>
            <p>Export CSV file with referrals data.</p>

            <form id="exportReferralsForm__basic" class="affiliatesuite__form" action="" autocomplete="off">
                <div class="affiliatesuite__formgroup">
                    <label for="affiliate_to_export">Referrals from:</label>
                    <select name="affiliate_to_export" id="affiliate-select" required>
                        <option value="all">All affiliates</option>
                    </select>
                </div>
                <div class="affiliatesuite__formgroup">
                    <label for="date_from">Date start:</label>
                    <input type="text" name="date_start" class="datepicker" placeholder="Select a date">
                </div>
                <div class="affiliatesuite__formgroup">
                    <label for="date_end">Date end:</label>
                    <input type="text" name="date_end" class="datepicker" placeholder="Select a date">
                </div>
                <div class="affiliatesuite__formgroup">
                    <input type="submit" value="<?php _e('Export CSV', 'affiliate-suite'); ?>">
                </div>
            </form>

            <div id="affiliatesuite__exportReferrals__notice">
                <div class="export-success" style="display: none">
                    <?php _e('Successfully exported. If your download does not start automatically '); ?>
                    <a href="<?php echo WP_PLUGIN_URL . '/affiliate-suite/tmp/referrals.csv' ?>" id="download-referrals">
                        <?php _e('click here', 'affiliate-suite'); ?>
                    </a>.
                </div>
                <div class="export-failed" style="display: none">
                    <?php _e('Something went wrong. Referrals could not be exported.', 'affiliate-suite'); ?>
                </div>
            </div>
           
            <script>
                // Init datepickers
                document.addEventListener('DOMContentLoaded', ()=> { 
                    flatpickr(".datepicker");
                });

                // Submit form via ajax
                document.getElementById('exportReferralsForm__basic').addEventListener('submit', (event) => {
                    event.preventDefault();
                    
                    let affiliateToExport = document.querySelector('select[name="affiliate_to_export"]').value
                    let dateStart = document.querySelector('input[name="date_start"]').value;
                    let dateEnd = document.querySelector('input[name="date_end"]').value;

                    let data = {
                        'action': 'export_csv_referrals',
                        'affiliate_to_export': affiliateToExport,
                        'date_start': dateStart,
                        'date_end': dateEnd
                    };
                    
                    jQuery.post(affiliateSuite.ajax_url, data, (response) => {
                        let exported = JSON.parse(response);

                        if (exported) {
                            document.querySelector('.export-success').style.display = 'block';
                            document.getElementById('download-referrals').click();
                        } else {
                            document.querySelector('.export-failed').style.display = 'block';
                        }
                    });
                });
            </script>
        </div><!-- affiliatesuite__exportReferrals -->
        
        <div id="affiliatesuite__exportReferrals__woocommerce" class="card">
            <h2><?php _e('Export Referrals (with WooCommerce Data)', 'affiliate-suite'); ?></h2>
            <p>Export CSV file containing referrals and WooCommerce orders data.</p>

            <form id="exportReferralsForm__woocommerce" class="affiliatesuite__form" action="" autocomplete="off">
                <div class="affiliatesuite__formgroup">
                    <label for="affiliate_to_export_woocommerce">Referrals from:</label>
                    <select name="affiliate_to_export_woocommerce" id="affiliate-select" required>
                        <option value="all">All affiliates</option>
                    </select>
                </div>
                <div class="affiliatesuite__formgroup">
                    <label for="date_from_woocommerce">Date start:</label>
                    <input type="text" name="date_start_woocommerce" class="datepicker" placeholder="Select a date">
                </div>
                <div class="affiliatesuite__formgroup">
                    <label for="date_end_woocommerce">Date end:</label>
                    <input type="text" name="date_end_woocommerce" class="datepicker" placeholder="Select a date">
                </div>
                <div class="affiliatesuite__formgroup">
                    <input id="woocommerce-submit" type="submit" value="<?php _e('Export CSV', 'affiliate-suite'); ?>">
                </div>
            </form>

            <div id="affiliatesuite__exportReferrals__notice--woocommerce">
                <div id="export-success-woocommerce" class="export-success" style="display: none">
                    <?php _e('Successfully exported. If your download does not start automatically '); ?>
                    <a href="<?php echo WP_PLUGIN_URL . '/affiliate-suite/tmp/referrals-woocommerce.csv' ?>" id="download-referrals-woocommerce">
                        <?php _e('click here', 'affiliate-suite'); ?>
                    </a>.
                </div>
                <div id="export-failed" class="export-failed" style="display: none">
                    <?php _e('Something went wrong. Referrals could not be exported.', 'affiliate-suite'); ?>
                </div>
            </div>

            <script>
                // Submit form via ajax
                document.getElementById('exportReferralsForm__woocommerce').addEventListener('submit', (event) => {
                    event.preventDefault();

                    document.getElementById('woocommerce-submit').disabled = true;
                    
                    let affiliateToExport = document.querySelector('select[name="affiliate_to_export_woocommerce"]').value
                    let dateStart = document.querySelector('input[name="date_start_woocommerce"]').value;
                    let dateEnd = document.querySelector('input[name="date_end_woocommerce"]').value;

                    let data = {
                        'action': 'export_csv_referrals_woocommerce',
                        'affiliate_to_export': affiliateToExport,
                        'date_start': dateStart + ' 00:00:00',
                        'date_end': dateEnd + ' 23:59:59'
                    };
                    
                    jQuery.post(affiliateSuite.ajax_url, data, (response) => {

                        console.log(JSON.parse(response));

                        let exported = JSON.parse(response);

                        if (exported) {
                            document.querySelector('#export-success-woocommerce').style.display = 'block';
                            document.getElementById('download-referrals-woocommerce').click();
                        } else {
                            document.querySelector('#export-failed-woocommerce').style.display = 'block';
                        }

                        document.getElementById('woocommerce-submit').disabled = false;
                    });
                });
            </script>
        </div>

    </div>
</div><!-- wrap -->