<?php
/**
 * This class is loaded on the back-end since its main job is
 * to display the Admin to box.
 */
require_once GMWCP_PLUGINDIR.'dompdf-master/lib/html5lib/Parser.php';
require_once GMWCP_PLUGINDIR.'dompdf-master/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;


class GMWCP_PDF {

	public function __construct () {
		add_action( 'init', array( $this, 'woo_comman_single_button' ));
	}

	function woo_comman_single_button(){
		if ($_REQUEST['action']=='catelog_single') {
			$dompdf = new Dompdf();
			ob_start();
			?>

			<link type="text/css" href="<?php echo GMWCP_PLUGINDIR.'css/print-style.css'; ?>" rel="stylesheet" />
			<?php
			$this->gmwcp_css();
			$this->gmwcp_signle_pdf($_REQUEST['id']);
			$output = ob_get_clean();
			$dompdf->loadHtml($output);
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream("relatorio.pdf", array("Attachment" => false));
			exit;
		}

		if ($_REQUEST['action']=='catelog_shop') {
			$dompdf = new Dompdf();
			ob_start();
			$this->gmwcp_css();
			?>
			<link type="text/css" href="<?php echo GMWCP_PLUGINDIR.'css/print-style.css'; ?>" rel="stylesheet" />
			<?php
			if($_REQUEST['id']=='full'){
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1
				);
			}else{
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => -1,
					'tax_query' => array(
									        array(
									            'taxonomy' => 'product_cat',
									            'field'    => 'term_id',
									            'terms'    => array( $_REQUEST['id'] ),
									            'operator' => 'IN',
									        ),
									    ),
				);
			}

			$query1 = new WP_Query( $args );
			while ( $query1->have_posts() ) {
			   $query1->the_post();
			   global $post;
			   $this->gmwcp_signle_pdf($post->ID);
			}

			$output = ob_get_clean();
			$dompdf->set_option('defaultFont', 'Helvetica');
			$dompdf->loadHtml($output);
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream("relatorio.pdf", array("Attachment" => false));
			exit;
		}

		if ($_REQUEST['action']=='wholesales_catelog_shop_generate') {
			$dompdf = new Dompdf();
			ob_start();
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'order' => 'ASC',
    			'orderby' => 'title',
				'meta_query'     => array(
			        'relation' => 'AND',
			        array(
			            'key'     => 'phoen_woocommerce_discount_mode',
			            //'value'   => array(''),
        				'compare' => 'EXISTS'
			        ),
			    ),
				//'post__in' => array(52292,165528)
			);

			$query1 = new WP_Query( $args );

			$posts = $query1->get_posts();

			$titles = array();

			foreach($posts as $post){

				$data = get_post_meta($post->ID, 'phoen_woocommerce_discount_mode',true);

				if($data == null){
					continue;
				}



				foreach ($data as $key => $value) {

					$titles[] = array(
						'label'             => $value['label'],
						'min_price'         => money_format('%.0n',$value['min_price']),
						'max_price'         => money_format('%.0n',$value['max_price']),
						'qty'               => $value['qty']
					);

					//$discountPrice = $product->get_price() - $value['discount_price'];

				}

				break;
			}


			$logo_img = GMWCP_PLUGINDIR.'img/logo-holy.jpg';

			?>
			<html>
			    <head>
			        <style>
			            /**
			            * Set the margins of the PDF to 0
			            * so the background image will cover the entire page.
			            **/
			            @page {
			                margin: 0cm 0cm;
			            }

			            /**
			            * Define the real margins of the content of your PDF
			            * Here you will fix the margins of the header and footer
			            * Of your background image.
			            **/
			            body {
			                margin-top:    3.5cm;
			                margin-bottom: 1cm;
			                margin-left:   1cm;
			                margin-right:  1cm;
			            }

			            /**
			            * Define the width, height, margins and position of the watermark.
			            **/
			            #watermark {
			                position: fixed;
			                bottom:   0px;
			                left:     250px;
			                top: 	  20px;
			                right: 	  100px;
			                /** The width and height may change
			                    according to the dimensions of your letterhead
			                **/
			                width:    21.8cm;
			                height:   28cm;

			                /** Your watermark should be behind every content**/
			                z-index:  -1000;
			            }
						.datagrid table{ border-collapse:collapse; text-align:center; width:100%} .datagrid{ font:normal 12px/150% Arial, Helvetica, sans-serif; background:#fff; overflow:hidden; border:1px solid #8C8C8C; -webkit-border-radius:3px; -moz-border-radius:3px; border-radius:3px; text-align:center}.datagrid table td, .datagrid table th{ padding:3px 10px}.datagrid table thead th{ background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) ); background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D'); background-color:#8C8C8C; color:#FFFFFF; font-size:12px; font-weight:bold; border-left:1px solid #A3A3A3} .datagrid table thead th:first-child{ border:none}.datagrid table tbody td{ color:#7D7D7D; border-left:1px solid #DBDBDB; font-size:12px; font-weight:normal;}.datagrid table tbody .alt td{ background:#EBEBEB; color:#7D7D7D}.datagrid table tbody td:first-child{ border-left:none}.datagrid table tbody tr:last-child td{ border-bottom:none}.datagrid table tfoot td div{ border-top:1px solid #8C8C8C; background:#EBEBEB} .datagrid table tfoot td{ padding:0; font-size:12px} .datagrid table tfoot td div{ padding:2px}.datagrid table tfoot td ul{ margin:0; padding:0; list-style:none; text-align:center}.datagrid table tfoot li{ display:inline}.datagrid table tfoot li a{ text-decoration:none; display:inline-block; padding:2px 8px; margin:1px; color:#F5F5F5; border:1px solid #8C8C8C; -webkit-border-radius:3px; -moz-border-radius:3px; border-radius:3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #8C8C8C), color-stop(1, #7D7D7D) ); background:-moz-linear-gradient( center top, #8C8C8C 5%, #7D7D7D 100% ); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#8C8C8C', endColorstr='#7D7D7D'); background-color:#8C8C8C}.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover{ text-decoration:none; border-color:#7D7D7D; color:#F5F5F5; background:none; background-color:#8C8C8C}div.dhtmlx_window_active, div.dhx_modal_cover_dv{ position:fixed !important;}

			        </style>
			    </head>
			    <div style="text-align: center;margin-top:-100px;margin-bottom: 20px;">
			    	<img src="<?php echo $logo_img; ?>" alt="" width="300">
			    </div>
			    <div class="datagrid">
				<table>
					<thead>
						<tr>
							<th>NOMBRE</th>
							<th>IMAGEN</th>
							<!--<th>PRECIO AL PÚBLICO</th>-->
							<?php foreach($titles as $key => $title): ?>
								<th>
									<?php echo $title['label'].'( $'. number_format($title['min_price'],0,",",".").' - $'.number_format($title['max_price'],0,",",".").' ) MÍNIMO '.$title['qty']. ' UNIDADES' ?>
								</th>

							<?php endforeach; ?>
						</tr>
					</thead>
						<tbody>
			<?php
				$alt = true;
				while ( $query1->have_posts() ) {
					$alt = !$alt;
				   	$query1->the_post();
				   	global $post;

				   	$this->gmwcp_signle_pdf($post->ID,$alt, $titles = false);
				}

			?>
					</tbody>
				</table>
			</div>

			<?php

			$output = ob_get_clean();
			$dompdf->set_option('defaultFont', 'Helvetica');
			$dompdf->loadHtml($output);
			$dompdf->render();

			$content = $dompdf->output();

			$uploads = wp_upload_dir();

			$upload_path = $uploads['basedir'];


			$pdfname ='CATALOGO_MAYORISTA_HOLY_COSMETICS('.time().').pdf';
			
			file_put_contents($upload_path.'/'.$pdfname, $content);

			unlink($upload_path.'/'.get_option('wholesales_pdf_name'));

			update_option('wholesales_pdf_name', $pdfname);

			echo "Listo";
			// Output the generated PDF to Browser
			//$dompdf->stream("CATALOGO_MAYORISTA_HOLY_COSMETICS.pdf", array("Attachment" => false));
			exit;

		}

		if ($_REQUEST['action']=='wholesales_catalog_shop_download') {

			$filename = get_option('wholesales_pdf_name');

			$uploads = wp_upload_dir();

			$upload_path = $uploads['basedir'];

			$file = $upload_path.'/'.$filename;
			
			$content = file_get_contents($upload_path.'/'.$filename);

			$url = site_url('wp-content/uploads/').$filename;
			//file_get_contents is standard function

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header('Location: '.$url);
			
			exit;

		}


	}

	

	function gmwcp_signle_pdf($product_id, $alt = false, $titles){
		setlocale(LC_MONETARY, 'en_US');
		$product = wc_get_product( $product_id );
		$data = get_post_meta($product_id, 'phoen_woocommerce_discount_mode',true);
		$attachment_id = $product->get_image_id();
		$url = wp_get_attachment_image_url( $attachment_id,'mediaum' );
		if($url!=''){
			$uploads = wp_upload_dir();
			$fullsize_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
		}else{
			$fullsize_path = GMWCP_PLUGINDIR.'img/woocommerce-placeholder-600x600.png';
		}


		?>

				<tr class="<?php if($alt) echo 'alt'; ?>">
					<td style=""><?php echo $product->get_name();?></td>
					<td style="text-align: center;"><img style="width: 100px;height: 100px;" src="<?php echo $fullsize_path;?>" style='border:1px solid black;' alt=""></td>
					<!--<td style="">$<?php echo number_format($product->get_price(),0,",","."); ?></td>-->

					<?php

						if($data != ''){
							$discount_type = get_post_meta($product_id, 'phoen_woocommerce_discount_type',true);

							if ($discount_type  == 'price') {

								//echo '<div class="radio-button_wrapper">';

								foreach ($data as $key => $value) {

									echo '<td style="">';

									if ($product->is_type( 'simple' )) {

											$regular_price  =  $product->get_regular_price();
									}
									elseif($product->is_type('variable')){

										$regular_price  =  $product->get_variation_regular_price( 'max', true );
									}

									


									$discountPrice = '$'.number_format($regular_price - $value['discount_price'],0,",",".");
									echo $discountPrice;
									/*echo '<div class="radio-button">
												<div class="radio-button_inner">
													<h4>'.strtoupper($value['label']).'</h4>
													Ordenes entre '.
													money_format('%.0n',$value['min_price']).' y '.money_format('%.0n',$value['max_price']).
													' : '.str_replace(',','.',money_format('%.0n',$discountPrice)).
												' C/U (APLICA MINIMO CON '.(isset($value['qty']) ? $value['qty'] : 1).' UNIDADES)</label>
												</div>
										</div>';*/

									echo '</td>';
								}

								//echo '</div>';

							}else{

								$minimunAmount = get_option( 'wholesales_required_amount', 1 );

								$suggestedPrice = get_post_meta( $product_id ,'_suggested_price', 1 );

								echo '</div>';

								echo '<table style="width:100%" id="wholesales-discounts">
										<tr>
										<th>Cantidad</th>
										<th>Precio</th>
										</tr>
										<tbody>';

								foreach ($data as $key => $value) {

									$discountPrice = $product->get_price() - $value['discount'];

									echo '<tr>
											<td class="discount">'.$value['min_val'].' - '.$value['max_val'].'</td>
											<td class="discount-price">'. str_replace(',','.',money_format('%.0n',$discountPrice)) .' x Unidad</td>
										</tr>';
								}


								echo'
									</tbody>
									</table>';

							}

						}

						?>


				</tr>

		<?php


	}

	function gmwcp_css(){
		$gmpcp_background_color = get_option( 'gmpcp_background_color' );
		$gmpcp_item_background_color = get_option( 'gmpcp_item_background_color' );
		?>
		<style type="text/css" media="screen">
			body{
				background-color: <?php echo $gmpcp_background_color;?>;
				color: <?php echo $gmpcp_item_background_color;?>;
			}
		</style>

		<?php
	}




}


