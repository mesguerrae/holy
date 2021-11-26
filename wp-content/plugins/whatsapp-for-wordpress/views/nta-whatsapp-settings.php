<input type="hidden" name="option_page" value="<?php echo $option_group; ?>">
<input type="hidden" name="action" value="update">
<?php wp_nonce_field($option_group . '-options'); ?>
<table class="form-table">
    <p>Use this form to set default style for shortcode buttons. You can reset the style for individual button when creating/editing a WhatsApp account.</p>
    <tbody>
        <tr>
            <th scope="row"><label for="nta-whatsapp-button-text">Button Text</label></th>
            <td>
                <input type="text" id="nta-whatsapp-button-text" name="button-text" value="<?php echo $option['button-text'] ?>" class="nta-whatsapp-button-text regular-text" placeholder="Need help? Chat via Whatsapp"/>    
            </td>    
        </tr>

        <tr>
            <th scope="row"><label for="nta_button_style">Button Style</label></th>
            <td>
                <div class="setting align">
                    <div class="button-group button-large" data-setting="align">
                        <button class="button btn-round <?php echo ($option['button_style'] == 'round' ? 'active' : '') ?>" value="round" type="button">
                            Round							
                        </button>
                        <button class="button btn-square <?php echo ($option['button_style'] == 'square' ? 'active' : '') ?>" value="square" type="button">
                            Square							
                        </button>
                    </div>
                    <input name="button_style" id="nta_button_style" class="hidden" value="<?php echo $option['button_style'] ?>" />
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="button_back_color">Button Background Color</label></th>
            <td>
                <input type="text" id="button_back_color" name="button_back_color" value="<?php echo $option['button_back_color'] ?>" class="widget-background-color" data-default-color="#2DB742" />    
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="button_text_color">Button Text Color</label></th>
            <td>
                <input type="text" id="button_text_color" name="button_text_color" value="<?php echo $option['button_text_color'] ?>" class="widget-background-color" data-default-color="#fff" />    
            </td>
        </tr>
    </tbody>
</table>
<button class="button button-primary button-large" id="btnSave" type="submit">Save Changes</button>