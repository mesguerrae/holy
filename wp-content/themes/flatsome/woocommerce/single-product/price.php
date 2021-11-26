<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;


$ImageUrl = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' )[0];
$ItemId = $product->id;
$Title = $product-> get_title();
$ProductUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$Price = $product->get_price();
$RegularPrice = $product->get_regular_price();
$DiscountAmount = $RegularPrice - $Price;
$terms = get_terms( 'product_tag' );




$classes = array();
if($product->is_on_sale()) $classes[] = 'price-on-sale';
if(!$product->is_in_stock()) $classes[] = 'price-not-in-stock'; ?>
<div class="price-wrapper">
	<p class="price product-page-price <?php echo implode(' ', $classes); ?>">
  <?php echo $product->get_price_html(); ?></p>
</div>
<script>
 var Title = "<?php echo $Title; ?>";
 var ItemId = "<?php echo $ItemId; ?>";
 var ImageUrl = "<?php echo $ImageUrl; ?>";
 var ProductUrl = "<?php echo $ProductUrl; ?>";
 var Price = "<?php echo $Price; ?>";
 var DiscountAmount = "<?php echo $DiscountAmount; ?>";
 var RegularPrice = "<?php echo $RegularPrice; ?>";
 var _learnq = _learnq || [];

    _learnq.push(['track', 'Viewed Product', {
      Title: Title,
      ItemId: ItemId,
      ImageUrl: ImageUrl,
      Url: ProductUrl,
      Metadata: {
        Price: Price,
        DiscountAmount: DiscountAmount,
        RegularPrice: RegularPrice
      }
 }]);
</script>
