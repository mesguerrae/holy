/**
 * Is empty
 * (utility function to check if an object is empty)
 */
function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}

/*
* Tabs handler
*/
function openTab(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

/**
 * Affiliates ranking
 */
function affiliatesRankingChart() {

    jQuery.get( affiliateSuite.ajax_url, {'action': 'get_affiliates_ranking'}, (response) =>{
        affiliates = JSON.parse(response);
    
        // Prepare chart data
        let chartLabels = [];
        let chartData = [];

        for (const affiliate in affiliates) {
            if (affiliates.hasOwnProperty(affiliate)) {
                const a = affiliates[affiliate];
                chartLabels.push(a.affiliate_name);
                chartData.push(a.total_earned);
            }
        }

        const rankingChart = new Chart(document.getElementById("RankingChart"), {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total in comissions.',
                    data: chartData,
                    backgroundColor: [
                        'rgba(46, 204, 113, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(46, 204, 113, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255,99,132,1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    });
}

/**
 * Global comissions chart
 */
function globalComissionsReportChart() {
    let postData = {
        'action': 'as_get_global_week_report'
    }

    jQuery.post(affiliateSuite.ajax_url, postData, (response) => {
        
        let reportData = JSON.parse(response);

        let comissionsData = [];
        let weekTotal = 0;;

        for (const day in reportData) {
            if (reportData.hasOwnProperty(day)) {
                const element = reportData[day];
                comissionsData.push(element.day_total);
                weekTotal = weekTotal + parseFloat(element.day_total);
            }
        }

        document.querySelector('.affiliatesuite__amountTotal').innerHTML = weekTotal.toFixed(2);

        // ComissionChart purposely declared as global variable, so it can be updated later.
        ComissionsChart = new Chart(document.getElementById("comissionsChart"), {
            type: 'line',
            data: {
                labels: Object.keys(reportData),
                datasets: [{
                    label: 'Comissions in ' + affiliateSuite.currency_symbol,
                    data: comissionsData,
                    borderColor:'rgba(255,99,132,1)',
                    borderWidth: 2,
                    backgroundColor: 'transparent'
                }]
            }
        });

        let countData = [];
        for (const day in reportData) {
            if (reportData.hasOwnProperty(day)) {
                const element = reportData[day];
                countData.push(element.referrals_count);
            }
        }
    });
}

/* Update Total Comissions Report Chart (Not really a chart.js chart) */
function updateTotalComissionsReportChart(select) {

    let dateStart = '';
    let dateEnd = '';

    switch (select.value) {
        case 'all-times':
            dateStart = '';
            dateEnd = '';
            break;
        case 'this-week':
            dataSource = 'as_get_affiliate_week_report';
            dateStart = moment().subtract(7, 'days').format('YYYY-MM-DD H:mm:ss');
            dateEnd = moment().format('YYYY-MM-DD H:mm:ss')
            break;
        case 'this-month':
            dataSource = 'as_get_affiliate_month_report';
            dateStart = moment().startOf('month').format('YYYY-MM-DD H:mm:ss');
            dateEnd = moment().endOf('month').format('YYYY-MM-DD h:mm:ss');
            break;
        case 'previous-month':
            dataSource = 'as_get_affiliate_previous_month_report';
            dateStart = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD H:mm:ss');
            dateEnd = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD H:mm:ss');
            break;
        case 'this-year':
            dataSource = 'as_get_affiliate_year_report';
            dateStart = moment().startOf('year').format('YYYY-MM-DD H:mm:ss');
            dateEnd = moment().endOf('year').format('YYYY-MM-DD H:mm:ss');
            break;
    }

    let postData = {
        'action': 'as_get_total_comissions_amount',
        'date_start': dateStart,
        'date_end': dateEnd
    }

    jQuery.post(affiliateSuite.ajax_url, postData, (response) => {
        let amount = response.replace(/['"]+/g, '');
        document.querySelector('.affilatesuite__totalAmount').innerHTML = amount;
    });
}

 /* Affiliate Initial Charts */
 function affiliateInitialCharts(affiliate_id) {
    let postData = {
        'action': 'as_get_affiliate_week_report',
        'affiliate_id': affiliate_id
    }

    jQuery.post(affiliateSuite.ajax_url, postData, (response) => {
        
        let reportData = JSON.parse(response);

        let comissionsData = [];
        let weekTotal = 0;;

        for (const day in reportData) {
            if (reportData.hasOwnProperty(day)) {
                const element = reportData[day];
                comissionsData.push(element.day_total);
                weekTotal = weekTotal + parseFloat(element.day_total);
            }
        }

        document.querySelector('.affiliatesuite__amountTotal').innerHTML = weekTotal.toFixed(2);

        // ComissionChart purposely declared as global variable, so it can be updated later.
        ComissionsChart = new Chart(document.getElementById("comissionsChart"), {
            type: 'line',
            data: {
                labels: Object.keys(reportData),
                datasets: [{
                    label: 'Comissions in ' + affiliateSuite.currency_symbol,
                    data: comissionsData,
                    borderColor:'rgba(255,99,132,1)',
                    borderWidth: 2,
                    backgroundColor: 'transparent'
                }]
            }
        });

        let countData = [];
        for (const day in reportData) {
            if (reportData.hasOwnProperty(day)) {
                const element = reportData[day];
                countData.push(element.referrals_count);
            }
        }

        const referralsCountChart = new Chart(document.getElementById("referralsCountChart"), {
            type: 'line',
            data: {
                labels: Object.keys(reportData),
                datasets: [{
                    label: 'Referrals count',
                    data: countData,
                    borderColor:'#3498db',
                    borderWidth: 2,
                    backgroundColor: 'transparent'
                }]
            }
        });
    });

    jQuery.post(affiliateSuite.ajax_url, {'action': 'as_affiliate_top_products', 'affiliate_id': affiliate_id}, (response) => {
        
        let topProducts = JSON.parse(response);

        let productsData = [];

        for (const product in topProducts) {
            if (topProducts.hasOwnProperty(product)) {
                productsData.push(topProducts[product]);
            }
        }
        
        const topProductsChart = new Chart(document.getElementById("topProductsChart"), {
            type: 'horizontalBar',
            data: {
                labels: Object.keys(topProducts),
                datasets: [{
                    label: 'Sold units',
                    data: productsData,
                    backgroundColor: [
                        'rgba(46, 204, 113, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(46, 204, 113, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255,99,132,1)'
                    ]
                }]
            }
        });
        
    });
 }

/* Update comissions report chart */
function updateComissionsReportChart(select, affiliate_id, globalReport=false) {
    
    let dataSource = '';
    let dateStart = '';
    let dateEnd = '';

    if ( globalReport === true ) {
        switch (select.value) {
            case 'this-week':
                dataSource = 'as_get_global_week_report';
                dateStart = moment().subtract(7, 'days').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().format('YYYY-MM-DD H:mm:ss')
                break;
            case 'this-month':
                dataSource = 'as_get_global_month_report';
                dateStart = moment().startOf('month').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().endOf('month').format('YYYY-MM-DD h:mm:ss');
                break;
            case 'previous-month':
                dataSource = 'as_get_global_previous_month_report';
                dateStart = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD H:mm:ss');
                break;
            case 'this-year':
                dataSource = 'as_get_global_year_report';
                dateStart = moment().startOf('year').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().endOf('year').format('YYYY-MM-DD H:mm:ss');
                break;
        }
    } else {
        switch (select.value) {
            case 'this-week':
                dataSource = 'as_get_affiliate_week_report';
                dateStart = moment().subtract(7, 'days').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().format('YYYY-MM-DD H:mm:ss')
                break;
            case 'this-month':
                dataSource = 'as_get_affiliate_month_report';
                dateStart = moment().startOf('month').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().endOf('month').format('YYYY-MM-DD h:mm:ss');
                break;
            case 'previous-month':
                dataSource = 'as_get_affiliate_previous_month_report';
                dateStart = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD H:mm:ss');
                break;
            case 'this-year':
                dataSource = 'as_get_affiliate_year_report';
                dateStart = moment().startOf('year').format('YYYY-MM-DD H:mm:ss');
                dateEnd = moment().endOf('year').format('YYYY-MM-DD H:mm:ss');
                break;
        }
    }

    let postData = {
        'action': dataSource,
        'affiliate_id': affiliate_id,
        'date_start': dateStart,
        'date_end': dateEnd
    }

    jQuery.post(affiliateSuite.ajax_url, postData, (response) => {
        let referrals = JSON.parse(response);
        
        console.log(referrals);

        // format data for chartJs
        let comissionsData = [];
        let totalAmount = 0;

        let totalPointer = (select.value == 'this-year') ? 'month_total' : 'day_total';

        for (const referral in referrals) {
            if (referrals.hasOwnProperty(referral)) {
                const element = referrals[referral];
                comissionsData.push(element[totalPointer]);
                totalAmount = totalAmount + parseFloat(element[totalPointer]);
            }
        }


        // If year report, change month number for month name
        if ( select.value == 'this-year' ) {
            for (const monthNumber in referrals) {
                if (referrals.hasOwnProperty(monthNumber)) {
                    const element = referrals[monthNumber];
                    let month_name = '';
                    switch (monthNumber) {
                        case '01':
                            month_name = 'jan';
                            break;
                        case '02':
                            month_name = 'feb';
                            break;
                        case '03':
                            month_name = 'Mar';
                            break;
                        case '04':
                            month_name = 'Apr';
                            break;
                        case '05':
                            month_name = 'May';
                            break;
                        case '06':
                            month_name = 'Jun';
                            break;
                        case '07':
                            month_name = 'Jul';
                            break;
                        case '08':
                            month_name = 'Aug';
                            break;
                        case '09':
                            month_name = 'Sep';
                            break;
                        case '10':
                            month_name = 'Oct';
                            break;
                        case '11':
                            month_name = 'Nov';
                            break;
                        case '12':
                            month_name = 'Dec';
                            break;
                    } // End of switch

                    referrals[month_name] = Object.assign({}, element);
                    delete referrals[monthNumber];
                }
            }
        }

        // Update Chart (ComissionChart is a global variable)
        ComissionsChart.data.labels = Object.keys(referrals);
        ComissionsChart.data.datasets = [{
            label: 'Comissions in ' + affiliateSuite.currency_symbol,
            data: comissionsData,
            borderColor:'rgba(255,99,132,1)',
            borderWidth: 2,
            backgroundColor: 'transparent'
        }];
        
        ComissionsChart.update();

        // Update total amount
        document.querySelector('.affiliatesuite__amountTotal').innerHTML = totalAmount.toFixed(2);
    });

}

/* Import AffiliateWP referrals */
function importAffiliateWPReferrals(importBtn) {

    document.querySelector('.affiliatesuite__importBtn').disabled = true;
    document.querySelector('.affiliatesuite__importedReferrals').innerHTML = "Importing...";

    let data = {
        'action': 'import_affiliatewp_referrals',
        'user_id': importBtn.getAttribute('user-id')
    };

    jQuery.post( affiliateSuite.ajax_url, data, (response)=> {
        console.log( JSON.parse(response) );
        document.querySelector('.affiliatesuite__importedReferrals').innerHTML = `${response} records were successfully imported. Refresh this page to see them.`;
    });
}

/* Search WooCommerce referrals  */
function SearchWoocommerceReferrals(importBtn) {

    if (document.getElementById('minDateImport').value =='' || document.getElementById('maxDateImport').value =='') {
        alert("Please, select a start and end date.");
        return;
    }


    let referralsToImportDiv = document.getElementById('wooReferralsToImport');
    referralsToImportDiv.innerHTML = 'Searching...';

    let data = {
        'action': 'get_unimported_woocommerce_referrals',
        'affiliate_id': importBtn.getAttribute('affiliate-id'),
        'dateStart': document.getElementById('minDateImport').value,
        'dateEnd': document.getElementById('maxDateImport').value
    };

    jQuery.post( affiliateSuite.ajax_url, data, (response)=> {
        
        const orders = JSON.parse(response);
        console.log(orders);

        if ( orders.length > 0 ) {
            let html = '<table class="affiliateSuite_importReferrals_table"><thead><tr><th>Order ID</th><th>Total</th><th>Date Paid</th></tr></thead>';

            orders.forEach(order => {
                html = html + `<tr><td>${order.id}</td><td>${order.order_total}</td><td>${order.date_paid.date}</td></tr>`;
            });

            html = html + "</table>";

            html = html + '<button id="importWooOrders" class="affiliatesuite__btn__primary" onclick="importWoocommerceReferrals()">Import ' + orders.length + ' orders</button>';

            referralsToImportDiv.innerHTML = html;
        } else {
            referralsToImportDiv.innerHTML = "No orders found.";
        }
    });
}

/* Import WooCommerce referrals  */
function importWoocommerceReferrals() {
    let importBtn = document.getElementById('importWooOrders');
    importBtn.disabled = true;
    importBtn.innerHTML = "Importing...";

    // Get affiliate ID from url param
    let currentUrl = new URL(window.location.href);
    let affilate_id = currentUrl.searchParams.get('affiliate'); 
    
    let data = {
        'action': 'import_unimported_woocommerce_referrals',
        'affiliate_id': affilate_id,
        'dateStart': document.getElementById('minDateImport').value,
        'dateEnd': document.getElementById('maxDateImport').value,
        'import': true
    };

    jQuery.post( affiliateSuite.ajax_url, data, (response)=> {
    
        let imported = JSON.parse(response);

        if (imported) {
            console.log("Import successful");
            importBtn.innerHTML = "Success!";

            let referralsToImportDiv = document.getElementById('wooReferralsToImport');
            referralsToImportDiv.innerHTML = 'Done! This page will reload.';
            location.reload();
        }
    });
}

/* Recalculate WooCommerce referrals  */
function recalculateWoocommerceReferrals() {

    if (document.getElementById('recalculate_date_from').value =='' || document.getElementById('recalculate_date_end').value =='') {
        alert("Please, select a start and end date.");
        return false;
    }

    let recalculateBtn = document.getElementById("recalculate_referrals__btn");
    recalculateBtn.disabled = true;

    let recalculateNoticeDiv = document.getElementById('recalculateNotice');
    recalculateNoticeDiv.innerHTML = 'Working on it...';

    let data = {
        'action': 'recalculate_referrals_amounts',
        'affiliate_id': document.getElementById('referrals_from').value,
        'date_start': document.getElementById('recalculate_date_from').value,
        'date_end': document.getElementById('recalculate_date_end').value
    };

    jQuery.post( affiliateSuite.ajax_url, data, (response)=> {
        const message = JSON.parse(response);
        alert(message);
        recalculateBtn.disabled = false;
        recalculateNoticeDiv.innerHTML = '';
    });
}