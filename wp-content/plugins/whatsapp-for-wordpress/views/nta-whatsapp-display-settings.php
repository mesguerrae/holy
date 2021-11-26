<form name="post" method="post" action="options.php" id="post" autocomplete="off"> 
    <input type="hidden" name="option_page" value="<?php echo $option_group; ?>">
    <input type="hidden" name="action" value="update">
    <?php wp_nonce_field($option_group . '-options'); ?>
    <table class="form-table">
        <p>Setting text and style for the floating widget.</p>
        <tbody>
            <tr>
                <th scope="row"><label for="nta-wa-switch-control">Enabled</label></th>
                <td>
                    <div class="nta-wa-switch-control">
                        <input type="checkbox" id="nta-wa-switch" name="nta_widget_status" <?php echo (isset($option['nta_widget_status']) ? 'checked' : '') ?>>
                        <label for="nta-wa-switch" class="green"></label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="whatsapp_name">Widget Text</label></th>
                <td><input name="widget_name" placeholder="Start a Conversation" type="text" id="whatsapp_name" value="<?php echo $option['widget_name'] ?>" class="regular-text"></td>
            </tr>

            <tr>
                <th scope="row"><label for="whatsapp_label">Widget Label</label></th>
                <td><input name="widget_label" placeholder="Need Help? <strong>Chat with us</strong>" type="text" id="whatsapp_label" value="<?php echo $option['widget_label'] ?>" class="regular-text"></td>
            </tr>

            <tr>
                <th scope="row"><label for="whatsapp_responseText">Response Time Text</label></th>
                <td><input name="widget_responseText" placeholder="The team typically replies in a few minutes." type="text" id="whatsapp_responseText" value="<?php echo $option['widget_responseText'] ?>" class="regular-text"></td>
            </tr>

            <tr>
                <th scope="row"><label for="text_color">Widget Text Color</label></th>
                <td><input type="text" id="text_color" name="text_color" value="<?php echo $option['text_color'] ?>" class="widget-text-color" data-default-color="#fff" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="back_color">Widget Background Color</label></th>
                <td><input id="back_color" type="text" name="back_color" value="<?php echo $option['back_color'] ?>" class="widget-background-color" data-default-color="#2db742" /></td>
            </tr>

            <tr>
                <th scope="row"><label for="">Widget Position</label></th>
                <td>
                    <div class="setting align">
                        <div class="button-group button-large" data-setting="align">
                            <button class="button btn-left <?php echo ($option['widget_position'] == 'left' ? 'active' : '') ?>" value="left" type="button">
                                Left							
                            </button>
                            <button class="button btn-right <?php echo ($option['widget_position'] == 'right' ? 'active' : '') ?>" value="right" type="button">
                                Right							
                            </button>
                        </div>
                        <input name="widget_position" id="widget_position" class="hidden" value="<?php echo $option['widget_position'] ?>" />
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="widget_description">Description</label></th>
                <td>
                    <?php
                    $settings = array(
                        'media_buttons' => false,
                        'textarea_rows' => get_option('default_post_edit_rows', 5),
                        'quicktags' => false,
                        'teeny' => true
                    );
                    wp_editor($option['widget_description'], 'widget_description', $settings);
                    ?>
                </td>
            </tr>

        </tbody>
    </table>
    <button class="button button-primary button-large" id="btnSave" type="submit">Save Display Settings</button>
</form>

