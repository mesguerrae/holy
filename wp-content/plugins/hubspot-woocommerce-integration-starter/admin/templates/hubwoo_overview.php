<?php 

/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0 
 */
//check if the license is entered and have valid license..

global $hubwoo;

$GLOBALS['hide_save_button']  = true;

?>
    <div class="hubwoo-overview-wrapper">
        <div class="hubwoo-overview-header hubwoo-common-header">
            <h2><?php _e("How our Integration works?", "hubwoo") ?></h2>
        </div>
        <div class="hubwoo-overview-body">
            <div class="hubwoo-what-we-do hubwoo-overview-container">
                <h4><?php _e( "What we create?", "hubwoo" );?></h4>
                <div class="hubwoo-custom-fields">
                    <p class="hubwoo-anchors" href="#"><?php _e( "Groups & Properties","hubwoo")?></p>
                </div>
                <div class="hubwoo-segments">
                    <p class="hubwoo-anchors" href="#"><?php _e( "Smart Lists","hubwoo")?></p>
                </div>
                <p class="hubwoo-desc-num">1</p>
            </div>
            <div class="hubwoo-how-easy-to-setup hubwoo-overview-container">
                <h4><?php _e( "How easy is it?", "hubwoo" );?></h4>
                <div class="hubwoo-setup">
                    <p class="hubwoo-anchors" href="#"><?php _e( "Just 3 steps to Go!","hubwoo")?></p>
                </div>
                <p class="hubwoo-desc-num">2</p>
            </div>
            <div class="hubwoo-what-you-achieve hubwoo-overview-container">
                <h4><?php _e( "What at the End?", "hubwoo" );?></h4>
                <div class="hubwoo-automation">
                    <p class="hubwoo-anchors" href="#"><?php _e( "Automated Marketing","hubwoo")?></p>
                </div>
                <p class="hubwoo-desc-num">3</p>
            </div>
        </div>
        <div class="hubwoo-overview-footer">
            <div class="hubwoo-overview-footer-content-2 hubwoo-footer-container">
                
                <?php
                    if( $hubwoo->hubwoo_starter_get_started() ) {
                        ?>
                            <a href="?page=hubwoo&hubwoo_tab=hubwoo_connect" class="hubwoo-overview-get-started"><?php echo __( 'Next', 'hubwoo' ) ?></a>
                        <?php
                    }
                    else {
                        ?>
                            <img width="40px" height="40px" src="<?php echo HUBWOO_STARTER_URL . 'admin/images/right-direction-icon.png' ?>"/>
                            <a id="hubwoo-get-started" href="javascript:void(0)" class="hubwoo-overview-get-started"><?php echo __( 'Get Started', 'hubwoo' ) ?></a>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>