<?php

$last_update = get_option('last_canceled_ga_execution', false);
$max_day_execution = get_option('last_canceled_ga_execution_counter', 0);
$max_executions = 1;
$curr_date = date('Y-m-d');

if($last_update == $curr_date && $max_day_execution == $max_executions)
    return;

$days = 1;
$initial_date = date('Y-m-d',strtotime("-$days days"));
$final_date = date('Y-m-d');
$orders = wc_get_orders(array(
    'limit'=> -1,
    'type'=> 'shop_order',
    'status'=> array( 'wc-cancelled','wc-refunded'),
    'date_created'=> $initial_date .'...'. $final_date,
    'meta_key'     => '_canceled_ga_success',
    'meta_compare' => 'NOT EXISTS',
    )
);

if(count($orders) == 0)
    return;

?>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WWCN6W5');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WWCN6W5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<script>
    <?php foreach($orders as $order): ?>

        dataLayer.push({
            'event':'refund', 
            'ecommerce': {
                'refund': {
                'actionField': {'id': '<?= $order->get_id(); ?>'}
                }
            }
        });

        <?php $order->update_meta_data('_canceled_ga_success', 1); ?>
        <?php $order->save(); ?>
    <?php endforeach; ?>
</script>

<?php

    if($last_update != $curr_date && $max_day_execution == $max_executions)
        $max_day_execution = 0;
        
    update_option( 'last_canceled_ga_execution', $curr_date );   
    update_option( 'last_canceled_ga_execution_counter', ($max_day_execution + 1) ); 

?>