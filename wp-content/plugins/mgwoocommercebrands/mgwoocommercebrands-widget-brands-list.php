<?php
/* 
Class Name: Widget Brands List

Author: MagniumThemes
Author URI: http://magniumthemes.com/
Copyright MagniumThemes.com. All rights reserved
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class mgwoocommercebrands_list_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname'   => 'mgwoocommercebrands_class',
							 'description' => 'Display a list of your brands on your site. ' );
		parent::__construct( 'mgwoocommercebrands_list_widget', 'WooCommerce Brands list', $widget_ops );
	}

	function form( $instance ) {
		if(isset($instance['title'])) {
			$title  = $instance['title'];
		}
		else {
			$title  = 'Shop by brand';
		}

		if(!isset($instance['show_images'])) {
			$instance['show_images'] = 0;
		}

		if(!isset($instance['hide_empty'])) {
			$instance['hide_empty'] = 0;
		}

		if(!isset($instance['show_count'])) {
			$instance['show_count'] = 1;
		}
		
		?>
	
		<p><label><?php echo __( 'Title:', 'mgwoocommercebrands' ); ?></label>
			<input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p><label><?php echo __( 'Display type:', 'mgwoocommercebrands' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'show_images' ); ?>">
				<option value='0' <?php if ( $instance['show_images'] == 0 ) {	echo "selected='selected'"; } ?>><?php echo __( 'Text List', 'mgwoocommercebrands' ); ?></option>
				<option value='1' <?php if ( $instance['show_images'] == 1 ) {	echo "selected='selected'"; } ?>><?php echo __( 'Images List', 'mgwoocommercebrands' ); ?></option>
				<option value='2' <?php if ( $instance['show_images'] == 2 ) {	echo "selected='selected'"; } ?>><?php echo __( 'Dropdown List', 'mgwoocommercebrands' ); ?></option>
			</select>
		</p>
		<p><label><?php echo __( 'Hide empty brands:', 'mgwoocommercebrands' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>">
				<option value='0' <?php if ( $instance['hide_empty'] == 0 ) {	echo "selected='selected'"; } ?>><?php echo __( 'No', 'mgwoocommercebrands' ); ?></option>
				<option value='1' <?php if ( $instance['hide_empty'] == 1 ) {	echo "selected='selected'"; } ?>><?php echo __( 'Yes', 'mgwoocommercebrands' ); ?></option>
			</select>
		</p>
		<p><label><?php echo __( 'Show product count (for Text brands display):', 'mgwoocommercebrands' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'show_count' ); ?>">
				<option value='0' <?php if ( $instance['show_count'] == 0 ) {	echo "selected='selected'"; } ?>><?php echo __( 'No', 'mgwoocommercebrands' ); ?></option>
				<option value='1' <?php if ( $instance['show_count'] == 1 ) {	echo "selected='selected'"; } ?>><?php echo __( 'Yes', 'mgwoocommercebrands' ); ?></option>
			</select>
		</p>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance               = $old_instance;
		$instance['show_images'] = esc_sql( $new_instance['show_images'] );
		$instance['hide_empty'] = esc_sql( $new_instance['hide_empty'] );
		$instance['show_count'] = esc_sql( $new_instance['show_count'] );
		$instance['title']      = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );
		
		$show_images = $instance['show_images'];
		$hide_empty = $instance['hide_empty'];
		$show_count = $instance['show_count'];

		if ( $instance['title'] ) {
			echo "<h3>{$instance['title']}</h3>";
		}?>
		<?php 
		
			echo '<div class="widget woocommerce widget_mgwoocommercebrands">';
	
			$brands_list = get_terms( 'product_brand', array(
				'orderby'    => 'name',
				'order'             => 'ASC',
				'hide_empty'	=> $hide_empty
			));

			if ( !empty( $brands_list ) && !is_wp_error( $brands_list ) ){
				
				if($show_images <> 2) {
					echo "<ul>";
				}

				if($show_images == 2) {
					echo '<select class="mgt-woocommerce-brands-dropdown">';
					echo '<option value="">'.__('Please select brand', 'mgwoocommercebrands').'</option>';
				}

				foreach ( $brands_list as $brand_item ) {

					if($show_images <> 2) {
						echo '<li>';	
					}

					if($show_images == 1) {
						if((get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb'))) {
							$brand_image_src_term = get_tax_meta($brand_item->term_id, 'mgwb_image_brand_thumb');
							$brand_image_src = $brand_image_src_term['src'];
							echo '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'"><img src="'.$brand_image_src .'" alt="'.$brand_item->name.'"/></a>';
						}
					}
					else {
						// Drop down list display		
						if($show_images == 2) {

							if($show_count == 1) {
							echo '<option value="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.$brand_item->name.' ('.$brand_item->count.')</option>';
							} else {
								echo '<option value="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.$brand_item->name.'</option>';
							}

						// Text list display
						} else {
							if($show_count == 1) {
								echo '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.$brand_item->name.'</a> <span class="count">('.$brand_item->count.')</span>';
							} else {
								echo '<a href="'.get_term_link( $brand_item->slug, 'product_brand' ).'">'.$brand_item->name.'</a>';
							}
						}
						
					}

					if($show_images <> 2) {
						echo '</li>';
					}
				}

				if($show_images == 2) {
					echo '</select>';
				}

				if($show_images <> 2) {
					echo '</ul>';
				}
			} 
			?>
		</div>
	<?php
	}
}

?>