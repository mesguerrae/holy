<?php
/**
 * Affiliate Dashboad Shortcode
 * [as_affiliate_dashboard]
 */
?>

<?php do_action( 'before_affiliate_referrals_dashboard', $affiliate); ?>

<div id="singleAffiliateReports_grid">
    <!-- Comissions amount -->
    <div id="affiliatesuite__comissionsReport" class="affiliatesuite__card affiliatesuite__comissionsReport affiliatesuite__comissionsReport__dashboard">
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
    <div id="affiliatesuite__lastQuarterReferralsCountReport" class="affiliatesuite__card affiliatesuite__lastQuarterReferralsCountReport affiliatesuite__lastQuarterReferralsCountReport__dashboard">
        <h3><?php _e('Referrals count', 'affiliate-suite'); ?></h3>
        <p style="color: #808080">&bull; Last seven days</p>
        <canvas id="referralsCountChart"></canvas>
    </div>

    <!-- Top Products -->
    <div style="display: none;" id="affiliatesuite__topProductsReport" class="affiliatesuite__card affiliatesuite__topProductsReport">
        <h3><?php _e('Top Products', 'affiliate-suite'); ?></h3>
        <p style="color: #808080">&bull; Most selling products</p>
        <canvas id="topProductsChart"></canvas>
    </div>

    <!-- Referrals  Table -->

    <?php do_action( 'before_affiliate_referrals_table', $affiliate, $referrals); ?>

    <div id="affiliatesuite__affiliateReferrals" class="affiliatesuite__card affiliatesuite__affiliateReferrals">
        <table class="affiliatesuite__affiliateReferralsTable" data-order='[[ 4, "desc" ]]'>
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
    </div>

    <?php do_action( 'after_affiliate_referrals_table', $affiliate, $referrals); ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', ()=> { 
                affiliateInitialCharts(<?php echo $affiliate->ID; ?>);
    });

    jQuery(document).ready( function () {
        jQuery('.affiliatesuite__affiliateReferralsTable').DataTable();
    });
</script>

<?php do_action( 'after_affiliate_referrals_dashboard', $affiliate); ?>