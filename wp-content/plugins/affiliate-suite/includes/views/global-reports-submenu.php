<div class="wrap">
    <h2><?php _e('Global reports', 'affiliate-suite'); ?></h2>

    <div id="affilaitesuite__GlobalReportsGrid">

        <!-- Affilaites ranking -->
        <div id="affiliatesuite__affiliatesRanking" class="affiliatesuite__card">
            <h3><?php _e('Affiliates Ranking', 'affiliate-suite') ?></h3>

            <canvas id="RankingChart"></canvas>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', ()=> { affiliatesRankingChart() });
            </script>
        </div>

        <!-- Comissions amount chart -->
        <div id="affiliatesuite__comissionsReport" class="affiliatesuite__card">
            <h3><?php _e('Comissions Chart', 'affiliate-suite'); ?></h3>
            <div class="affiliatesuite__reportDateSelect">
                <span style="color: #808080">&bull; Total: <?= get_woocommerce_currency_symbol() ?>
                    <span class="affiliatesuite__amountTotal">...</span>
                </span>
                
                <form action="" id="comissionsReportDateForm">
                    <select name="date" onchange="updateComissionsReportChart(this, null, true)">
                        <option value="this-week">This week</option>
                        <option value="this-month">This month</option>
                        <option value="previous-month">Previous month</option>
                        <option value="this-year">This year</option>
                    </select>
                </form>
            </div>
            <canvas id="comissionsChart"></canvas>

            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', ()=> { globalComissionsReportChart() });
            </script>
        </div>

        <!-- Total comissions -->
        <div id="affiliatesuite__totalComissions">
            <div class="affiliatesuite__card__block">
                <h3><?php _e('Total comissions', 'affiliate-suite'); ?></h3>

                <div class="affiliatesuite__reportDateSelect">
                    <span style="color: #808080">• Select a range</span>
                    <form action="" id="comissionsReportDateForm">
                        <select name="date" onchange="updateTotalComissionsReportChart(this)">
                            <option value="all-times">All times</option>
                            <option value="this-week">This week</option>
                            <option value="this-month">This month</option>
                            <option value="previous-month">Previous month</option>
                            <option value="this-year">This year</option>
                        </select>
                    </form>
                </div>

                <div class="affiliatesuite__comissionsTotal">
                    <span class="affiliatesuite__currencySymbol"><?= get_woocommerce_currency_symbol() ?></span>
                    <span class="affilatesuite__totalAmount"><?= as_get_total_comissions_amount(); ?></span>
                </div>
            </div>
        </div>

        <!-- All referrals -->
        <div id="affiliatesuite__allReferrals" class="affiliatesuite__card">
            <h3><?php _e('All Referrals', 'affiliate-suite'); ?></h3>

            <div class="affiliatesuite__filterbyDate">
                <span><?php _e('Filter by date:', 'affiliate-suite'); ?></span>
                <input name="min" id="min" type="text" placeholder="<?php _e( 'Start date' ); ?>">
                <input name="max" id="max" type="text" placeholder="<?php _e( 'End date' ); ?>">
            </div>
            
            <table id="affiliatesuite__referralsTable" data-order='[[ 5, "desc" ]]'>
                <thead>
                    <tr>
                        <th>Affiliate</th>
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
                                <td><?= $referral->affiliate_name; ?></td>
                                <td><?= $referral->reference; ?></td>
                                <td><?= as_referral_products_stringify($referral, '<br>'); ?></td>
                                <td><?= get_woocommerce_currency_symbol() . $referral->amount; ?></td>
                                <td><?= $referral->status; ?></td>
                                <td><?= $referral->date; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6"><?php _e('No referrals yet', 'affiliate-suite'); ?></td>
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

                            var startDate = new Date(data[5]);
                            if (min == null && max == null) { return true; }
                            if (min == '' && max == '') { return true; }
                            if (min == null && startDate <= max) { return true;}
                            if(max == null && startDate >= min) {return true;}
                            if (startDate <= max && startDate >= min) { return true; }
                            return false;
                        }
                    );

                    // Initialize datatable
                    var referralsTable = jQuery('#affiliatesuite__referralsTable').DataTable();

                    // Event listener to the two range filtering inputs to redraw on input
                    jQuery('#min, #max').change(function () {
                        referralsTable.draw();
                    });
                });
            </script>
        </div>

        <div id="affiliatesuite__recalculateComissions" class="affiliatesuite__card">
            <h3><?php _e('Recalculate Comissions', 'affiliate-suite'); ?></h3>

            <div class="affiliatesuite__form">
                <div class="affiliatesuite__formgroup">
                    <label for="referrals_from">Referrals from:</label>
                    <select name="referrals_from" id="referrals_from">
                        <option value="all" disabled>All affiliates</option>
                        <?php
                            $affiliates = Affiliates_Controller::get_affiliates();
                            if ($affiliates) {
                                foreach ($affiliates as $affiliate) {
                                    echo '<option value="'.$affiliate->ID.'">'.$affiliate->display_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="affiliatesuite__formgroup">
                    <label for="recalculate_date_from">Date start:</label>
                    <input name="recalculate_date_from" id="recalculate_date_from" type="text" placeholder="<?php _e( 'Start date' ); ?>">
                </div>
                <div class="affiliatesuite__formgroup">
                    <label for="recalculate_date_end">Date end:</label>
                    <input name="recalculate_date_end" id="recalculate_date_end" type="text" placeholder="<?php _e( 'End date' ); ?>">
                </div>

               <script>
                    let recalculateMinDate = flatpickr("#recalculate_date_from");
                    let recalculateMaxDate = flatpickr("#recalculate_date_end");
               </script>

                <div class="affiliatesuite__formgroup">
                    <button id="recalculate_referrals__btn" class="affiliatesuite__btn" onclick="recalculateWoocommerceReferrals()">Re-calculate</button>
                </div>

                <div id="recalculateNotice"></div>
            </div>
        </div>

    </div><!-- global reports grid -->

</div>