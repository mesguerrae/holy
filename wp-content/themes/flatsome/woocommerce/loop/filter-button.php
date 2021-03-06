<?php
/**
 * Shop category filter button template
 *
 * @package flatsome
 */

$layout = get_theme_mod( 'category_sidebar', 'left-sidebar' );
if ( 'none' === $layout ) {
	return;
}

$after = 'data-visible-after="true"';
$class = 'show-for-medium';
if ( 'off-canvas' === $layout ) {
	$after = '';
	$class = '';
}

$custom_filter_text = get_theme_mod( 'category_filter_text' );
$filter_text = $custom_filter_text ? $custom_filter_text : __( 'Filter', 'woocommerce' );
?>
<div  style="
    height: 37.2px;    
">
	<div class="button wc-forward filter-category-button" style="display: none;">
		<a href="#" data-open="#shop-sidebar" <?php echo $after ?> data-pos="left" class="filter-button uppercase plain">
			<i class="icon-menu"></i>
			<strong><?php echo $filter_text ?></strong>
		</a>
		<div class="inline-block">
			<?php the_widget( 'WC_Widget_Layered_Nav_Filters' ) ?>
		</div>

		
	</div>
	<div class="form-flat filter-category-order" style="">
		<?php do_action('flatsome_category_title_alt') ;?>
	</div>
</div>
