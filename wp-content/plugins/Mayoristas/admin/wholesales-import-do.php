<h1>Wholesales Import masive promotions</h1>
<?php $url = get_site_url().'/downloadProducts/wholesales' ?>

<form action="" method="post" enctype="multipart/form-data">
	<p>
		<input type="file" name="file" id="file" class="input-large">
	</p>
	<p>
		<button type="submit" class="button button button-primary">Upload</button>
		<a type="button" class="button button button-primary" href="<?php echo $url.'?'.time(); ?>" target="_blank">Download Products</a>
	</p>
</form>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
table#t01 {
  width: 100%;    
  background-color: #f1f1c1;
}
</style>
<?php
if($_GET['download'] == 'true'){
echo "entre";
}


if(isset($_FILES['file'])){

	$filename=$_FILES["file"]["tmp_name"];  

	if($_FILES["file"]["size"] > 0){

		$file = fopen($filename, "r");

		$discounts = array();

		echo '<table>';

		echo '<tr>
			    <th>POST ID</th>
			    <th>PRODUCT NAME</th>
			    <th>STATUS</th>
			  </tr>';

		while (($data = fgetcsv($file, 10000, ";")) !== FALSE){

			//delete_post_meta($data[0],'phoen_woocommerce_discount_mode');

			$roles = explode(',', $data[11]);

			if ($data[0] == '') {
				
				continue;
			}

			$_product = wc_get_product( $data[0]);
			
			if (!$_product) {
				
				continue;
			}

			if ($data[13] == 'qty') {
				
				$content = array(
					'min_val' => $data[5],
					'max_val' => $data[6],
					'discount' => $data[7],
					'type' => $data[8],
					'from' => $data[9],
					'to' => $data[10],
					'user_role' => $roles,
					'never_expire' => $data[12],
				);

			}else{

				$content = array(
					'min_price' => $data[5],
					'max_price' => $data[6],
					'qty'		=> $data[14],
					'discount_price' => $data[7],
					'type' => $data[8],
					'from' => $data[9],
					'to' => $data[10],
					'user_role' => $roles,
					'never_expire' => $data[12],
					'label'	=> $data[15]
				);
			}
			



			if ($data[7] == 0) {

				echo '<tr>
			    		<td>'.$data[0].'</td>
			    		<td>'.$_product->get_name().'</td>
			    		<td style="color:green;"><strong>DESCUENTO ELIMINADO</strong></td>
			  		</tr>';
				
				delete_post_meta($data[0] ,'phoen_woocommerce_discount_mode');

			}else{

				$discounts[$data[0]]['data'][] =  $content;
				$discounts[$data[0]]['type'] =  $data[13];
			}

			
		}

		foreach ($discounts as $post_id => $values) {

			$product_post = wc_get_product($post_id);
			
			if (!$product_post) {

				echo '<tr>
					    <td>'.$post_id.'</td>
					    <td>'.$product_post->get_name().'</td>
					    <td style="color:red;"><strong>NO ACTUALIZADO NO EXISTE</strong></td>
					  </tr>';
			
				continue;
			
			}

			delete_post_meta($post_id ,'phoen_woocommerce_discount_mode');


			delete_post_meta($post_id ,'phoen_woocommerce_discount_type');

			update_post_meta($post_id, 'phoen_woocommerce_discount_mode', $values['data']);

			if ($data[7] != 0) {
				update_post_meta($post_id, 'phoen_woocommerce_discount_type', $values['type']);
			}
			update_post_meta($post_id, 'phoen_woocommerce_discount_type', $values['type']);

			echo '<tr>
					    <td>'.$post_id.'</td>
					     <td>'.$product_post->get_name().'</td>
					    <td style="color:green;"><strong>ACTUALIZADO</strong></td>
					  </tr>';

		}

		echo '</table>';

		fclose($file);  
	}
}
