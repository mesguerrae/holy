<?php
/**
 * New Referral Email Template
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="affiliatesuite__emailBox">
            <header>
                <h1>New referral</h1>
                <h3>You've made a new sale!</h3>
            </header>
            
            <div class="affiliatesuite__emailBody">    
                <p>
                    Congratulations, you've made a new sale. Here are the details:
                </p>
                <table class="affiliatesuite__emailReferralTable">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Products</th>
                            <th>Date</th>
                            <th>Your comission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $args['reference']; ?></td>
                            <td><?php echo as_products_stringify( unserialize($args['products']), '<br>' ); ?></td>
                            <td><?php echo $args['date']; ?></td>
                            <td><?php echo "$" . number_format($args['amount'], 2, '.', ','); ?></td>
                        </tr>
                    </tbody>
                </table>
                <p>
                    This month you've made: <b><?php echo "$" . $month_total; ?></b>
                </p>
            </div><!-- affiliatesuite__emailBody -->
            <div class="affiliatesuite__emailFooter">
                <small>
                    <?php echo get_bloginfo( 'name' ); ?>
                </small>
            </div>
        </div>
    </body>
</html>