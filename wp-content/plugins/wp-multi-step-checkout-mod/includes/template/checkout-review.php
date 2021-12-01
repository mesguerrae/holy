<table class="shop_table">
    <thead>
        <tr>
            <th class="product-name">Datos de env&iacute;o</th>
            <th class="product-total"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Nombre y Apellido</td>
            <td class="order-review-name"></td>
        </tr>
        <tr>
            <td>Correo electr&oacute;nico</td>
            <td class="order-review-email"></td>
        </tr>
        <tr>
            <td>Direcci&oacute;n</td>
            <td class="order-review-address"></td>
        </tr>
    </tbody>
    
</table>
<? if (is_plugin_active( 'payu-custom-checkout/payu-custom-checkout.php' )): ?>
    
    <?php $path = get_site_url().'/wp-content/plugins/payu-custom-checkout/includes/methods/assets'; ?>

  
<? endif; ?>
<table class="shop_table">
    <thead>
        <tr>
            <th class="product-name">Metodo de pago</th>
            <th class="product-total"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
              <div style="display: none;" class="payment_review" id="efectivo_label">EFECTIVO</div>
              <div style="display: none;" class="payment_review" id="tc_label">TARJETA DE CR&Eacute;DITO</div>
              <div style="display: none;" class="payment_review" id="pse_label">PSE</div>
              <div style="display: none;" class="payment_review" id="gift_card">TARJETA REGALO</div>
            </td>
            <td>      
              <img style="display: none;" class="payment_review"src="<?php echo $path; ?>\visa.png" alt="" id="visa_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\mastercard.png" alt="" id="mastercard_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\codensa.png" alt="" id="codensa_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\dinners_club.png" alt="" id="dinners_club_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\amercian_express.png" alt="" id="amex_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\baloto.png" alt="" id="baloto_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\efecty.png" alt="" id="efecty_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\bancodebogota.png" alt="" id="bank_referenced_1_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\bancolombia.png" alt="" id="bank_referenced_2_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\su-red.png" alt="" id="others_cash_img">
              <img style="display: none;" class="payment_review" src="<?php echo $path; ?>\bank.png" alt="" id="pse_image_img">
            </td>
        </tr>

    </tbody>
    
</table>

<script>



</script>