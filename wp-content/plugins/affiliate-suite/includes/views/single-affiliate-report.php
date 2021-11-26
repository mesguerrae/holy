<div class="wrap">
    <h2>Single Affiliate Report</h2>

    <div id="singleAffiliateReports_grid">
        <!-- Affiliate info -->
        <div id="affiliatesuite__affiliate_info" class="affiliatesuite__card__block affiliatesuite__affiliate_info">
            <div>
                <h3><?php echo $affiliate->first_name . ' ' . $affiliate->last_name; ?> <span class="affiliatesuite__affiliateStatus <?php echo $affiliate->status; ?>">&nbsp; &bull; <?php echo $affiliate->status; ?></span></h3>
                <p><?php echo $affiliate->user_email; ?></p>
            </div>
            <div>
                <h3>All-time Earnings</h3>
                <span><?= get_woocommerce_currency_symbol() . calculate_affiliate_all_time_earnings($affiliate->ID); ?></span>
            </div>
            <div>
                <h3>Comission Rate</h3>
                <span><?php echo $affiliate->rate . '%'; ?></span>
            </div>
            <div>
                <h3>Since</h3>
                <span><?php echo date_format( date_create($affiliate->affiliate_date_registered), 'F d, Y' ); ?></span>
            </div>
        </div>

        <!-- Comissions amount -->
        <div id="affiliatesuite__comissionsReport" class="affiliatesuite__card affiliatesuite__comissionsReport">
            <h3><?php _e('Comissions earned', 'affiliate-suite'); ?></h3>
            <div class="affiliatesuite__reportDateSelect">
                <span style="color: #808080">&bull; Total: <?= get_woocommerce_currency_symbol() ?>
                    <span class="affiliatesuite__amountTotal">...</span>
                </span>
                
                <form action="" id="comissionsReportDateForm">
                    <select name="date" onchange="updateComissionsReportChart(this, <?php echo $affiliate->ID ?>)">
                        <option value="this-week">This week</option>
                        <option value="this-month">This month</option>
                        <option value="previous-month">Previous month</option>
                        <option value="this-year">This year</option>
                    </select>
                </form>
            </div>
            <canvas id="comissionsChart"></canvas>
        </div>

        <!-- Referrals count -->
        <div id="affiliatesuite__lastQuarterReferralsCountReport" class="affiliatesuite__card affiliatesuite__lastQuarterReferralsCountReport">
            <h3><?php _e('Referrals count', 'affiliate-suite'); ?></h3>
            <p style="color: #808080">&bull; Last seven days</p>
            <canvas id="referralsCountChart"></canvas>
        </div>

        <!-- Top Products -->
        <div id="affiliatesuite__topProductsReport" class="affiliatesuite__card affiliatesuite__topProductsReport">
            <h3><?php _e('Top Products', 'affiliate-suite'); ?></h3>
            <p style="color: #808080">&bull; Most selling products</p>
            <canvas id="topProductsChart"></canvas>
        </div>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', ()=> { 
                affiliateInitialCharts(<?php echo $affiliate->ID; ?>);
            });
        </script>

        <!-- Referrals -->
        <div id="affiliatesuite__affiliateReferrals" class="affiliatesuite__card affiliatesuite__affiliateReferrals">
            <h3><?php _e('Referrals', 'affiliate-suite'); ?></h3>

            <div class="affiliatesuite__filterbyDate">
                <span><?php _e('Filter by date:', 'affiliate-suite'); ?></span>
                <input name="min" id="min" type="text" placeholder="<?php _e( 'Start date' ); ?>">
                <input name="max" id="max" type="text" placeholder="<?php _e( 'End date' ); ?>">
            </div>

            <table id="affiliateReferralsTable" class="affiliatesuite__affiliateReferralsTable" data-order='[[ 4, "desc" ]]'>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($referrals) : ?>
                        <?php foreach ($referrals as $referral) : ?>
                            <tr>
                                <td><?= $referral->reference; ?></td>
                                <td><?= as_referral_products_stringify($referral, '<br>'); ?></td>
                                <td><?= get_woocommerce_currency_symbol() . $referral->amount; ?></td>
                                <td><?= $referral->status ?></td>
                                <td><?= $referral->date ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5"><?php _e('No referrals yet', 'affiliate-suite'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', ()=> {

                    // Initialize datepickers
                    let minDate = flatpickr("#min");
                    let maxDate = flatpickr("#max");

                    // Date range plugin
                    jQuery.fn.dataTable.ext.search.push(
                        function (settings, data, dataIndex) {

                            let min = minDate.selectedDates.length > 0 ? new Date(document.getElementById('min').value + ' 00:00:00') : new Date( '1950-01-01 00:00:00' );
                            let max = maxDate.selectedDates.length > 0 ? new Date(document.getElementById('max').value + ' 23:59:59') : new Date();

                            var startDate = new Date(data[4]);
                            if (min == null && max == null) { return true; }
                            if (min == '' && max == '') { return true; }
                            if (min == null && startDate <= max) { return true;}
                            if(max == null && startDate >= min) {return true;}
                            if (startDate <= max && startDate >= min) { return true; }
                            return false;
                        }
                    );

                    // Initialize datatable
                    var referralsTable = jQuery('#affiliateReferralsTable').DataTable();

                    // Event listener to the two range filtering inputs to redraw on input
                    jQuery('#min, #max').change(function () {
                        referralsTable.draw();
                    });
                });
            </script>
        </div>

        <?php if ( is_plugin_active('affiliate-wp/affiliate-wp.php') ) : ?>
            <!-- Import AffiliateWP Referrals -->
            <div id="affiliateSuite__importReferrals" class="affiliatesuite__card affiliateSuite__importReferrals">
                <h3><?php _e('Import Referrals', 'affiliate-suite'); ?></h3>

                <?php
                    $affiliateWpReferrals = Affiliate_Suite::get_unimported_affiliatewp_referrals($affiliate->ID);

                    if ($affiliateWpReferrals) {
                        echo "<p>" . sprintf( __('%d un-imported AffiliateWP referrals have been found. Do you want to import them into Affiliate Suite?', 'affiliate-suite'), count($affiliateWpReferrals) ) . "</p>";
                        echo '<div class="affiliatesuite__importedReferrals"></div>';
                        echo '<button class="affiliatesuite__importBtn" user-id="'.$affiliate->ID.'" onclick="importAffiliateWPReferrals(this)">'. __('import', 'affiliate-suite') .'</button>';
                    } else {
                        echo "<p>".__('No records were found that you did not already have in your Affiliate Suite installation.')."</p>";
                    }
                ?>
            </div>
        <?php endif; ?>

        <!-- Import unregistered referrals -->
        <div id="affiliateSuite__importReferrals__woocommerce" class="affiliatesuite__card affiliateSuite__importReferrals">
            <h3><?php _e('Import unregistered referrals', 'affiliate-suite'); ?></h3>
            <p>Import referrals that may have occurred prior to the affiliate's registration.</p>
            
            <input name="minDateImport" id="minDateImport" type="text" placeholder="<?php _e( 'Start date' ); ?>" required>
            <input name="maxDateImport" id="maxDateImport" type="text" placeholder="<?php _e( 'End date' ); ?>" required>
            <script>
                flatpickr("#minDateImport");
                flatpickr("#maxDateImport");
            </script>

            <button id="searchReferrals" class="affiliatesuite__btn" affiliate-id="<?php echo $affiliate->ID ?>" onclick="SearchWoocommerceReferrals(this)"><?php _e( 'Search for referrals', 'affiliate-suite' ); ?></button>
            <div id="wooReferralsToImport"></div> 
        </div>
        
    </div>
</div>