<table class="form-table">
    <p>This styling applies only to the shortcode buttons for this account. Leave blank to use the <a href="admin.php?page=nta_whatsapp">default styles set on the settings page</a></p>
    <tbody>
        <tr>
            <th scope="row"><label for="button_style">Button Style</label></th>
            <td>
<!--                <select name="button_style" id="button_style">
                    <option value="round" <?php echo ($buttonStyle['button_style'] == 'round' ? 'selected' : '')?>>Round</option>
                    <option value="square" <?php echo ($buttonStyle['button_style'] == 'square' ? 'selected' : '')?>>Square</option>
                </select>-->
                <div class="setting align">
                    <div class="button-group button-large" data-setting="align">
                        <button class="button btn-round <?php echo ($buttonStyle['button_style'] == 'round' ? 'active' : '') ?>" value="round" type="button">
                            Round							
                        </button>
                        <button class="button btn-square <?php echo ($buttonStyle['button_style'] == 'square' ? 'active' : '') ?>" value="square" type="button">
                            Square							
                        </button>
                    </div>
                    <input name="button_style" id="nta_button_style" class="hidden" value="<?php echo $buttonStyle['button_style'] ?>" />
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="button_back_color">Button Background Color</label></th>
            <td>
                <input type="text" id="button_back_color" name="button_back_color" value="<?php echo $buttonStyle['button_back_color']  ?>" class="widget-background-color" data-default-color="#2DB742" />    
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="button_text_color">Button Text Color</label></th>
            <td>
                <input type="text" id="button_text_color" name="button_text_color" value="<?php echo $buttonStyle['button_text_color'] ?>" class="widget-background-color" data-default-color="#fff" />    
            </td>
        </tr>
    </tbody>
</table>