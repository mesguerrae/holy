<?php if ( ! defined( 'ABSPATH' ) ) {
  	exit;
}


?>


<div class="wpmc-nav-wrapper">
  <div class="wpmc-footer-left">
      <?php if( $show_back_to_cart_button ) : ?>
            <a href="#!" class="wpmc-back-to-cart-link"><button data-href="<?php echo wc_get_cart_url(); ?>" id="wpmc-back-to-cart" class="button alt" type="button"><?php echo $t_back_to_cart; ?></button></a>
      <?php endif; ?>
  </div>
  <div class="wpmc-footer-right wpmc-nav-buttons">
  <a href="#!" class="wpmc-previus-link"><button id="wpmc-prev" class="button button-inactive alt" type="button"><?php echo $t_previous; ?></button></a>
    <?php if ( $show_login_step ) : ?>
      <a href="#!" class="wpmc-next-link"><button id="wpmc-next" class="button button-active alt" type="button"><?php echo $t_next; ?></button></a>
      <a href="#!" class="wpmc-skip-link"><button id="wpmc-skip-login" class="button button-active current alt" type="button"><?php echo $t_skip_login; ?></button></a>
    <?php else : ?>
      <a href="#!" class="wpmc-next-link"><button id="wpmc-next" class="button button-active current alt" type="button"><?php echo $t_next; ?></button></a>
     <?php endif; ?>
    </div>
</div>