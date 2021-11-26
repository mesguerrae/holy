<table class="form-table" id="nta-custom-wc-button-settings">
    <tbody>
        <tr>
            <th scope="row">
                <label for="nta_group_number">
                    Account Number or group chat URL
                </label>
            </th>
            <td>
                <p>
                    <input type="text" class="widefat" id="nta_group_number" name="nta_group_number" value="<?php echo (!empty($edit_account) ? $edit_account['nta_group_number'] : '') ?>" autocomplete="off">
                </p>
                <p class="description">Refer to <a href="https://faq.whatsapp.com/en/general/21016748" target="_blank">https://faq.whatsapp.com/en/general/21016748</a>
                    for a detailed explanation.</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_title">Title</label>
            </th>
            <td>
                <input type="text" id="nta_title" name="nta_title" value="<?php echo (!empty($edit_account) ? $edit_account['nta_title'] : '') ?>" class="widefat" autocomplete="off">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_predefined_text">Predefined Text</label>
            </th>
            <td>
                <textarea name="nta_predefined_text" id="nta_predefined_text" rows="3" class="widefat"><?php echo (!empty($edit_account) ? $edit_account['nta_predefined_text'] : '') ?></textarea>
                <p class="description">Use [njwa_page_title] and [njwa_page_url] shortcodes to output the page's
                    title and URL respectively.
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="nta_button_label">Button Label</label>
            </th>
            <td>
                <input type="text" id="nta_button_label" name="nta_button_label" value="<?php echo (!empty($edit_button_label['button-text']) ? $edit_button_label['button-text'] : '') ?>" placeholder="Need help? Chat via WhatsApp"
                       class="widefat" autocomplete="off">
                <p class="description">This text applies only on shortcode button. Leave empty to use the default label.
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label>Time Availability</label>
            </th>
            <td>
                <table class="form-table time-available">
                    <tbody>
                        <tr>
                            <td width="150">
                                <input type="checkbox" id="nta_sunday" name="nta_sunday" <?php echo (!empty($edit_account) ? $edit_account['nta_sunday'] : '') ?>>
                                <label for="nta_sunday">Sunday</label>
                            </td>   
                            <td width="100">
                                <select name="nta_sunday_hour_start" class="nta_sunday_hour_start nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_sunday_working'], 0, 5)) : get_times()); ?></select>
                            </td>
                            <td width="100">
                                <select name="nta_sunday_hour_end" class="nta_sunday_hour_end nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_sunday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>
                            <td>
                                <a href="javascript:;" type="button" class="button" id="btn-apply-time">Apply to All Days</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_monday" name="nta_monday" <?php echo (!empty($edit_account) ? $edit_account['nta_monday'] : '') ?>>
                                <label for="nta_monday">Monday
                                </label>
                            </td>
                            <td>
                                <select name="nta_monday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_monday_working'], 0, 5)) : get_times()); ?></select>
                            </td>
                            <td>
                                <select name="nta_monday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_monday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_tuesday" name="nta_tuesday" <?php echo (!empty($edit_account) ? $edit_account['nta_tuesday'] : '') ?>>
                                <label for="nta_tuesday">Tuesday
                                </label>
                            </td>
                            <td>
                                <select name="nta_tuesday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_tuesday_working'], 0, 5)) : get_times()); ?></select>
                            </td>
                            <td>
                                <select name="nta_tuesday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_tuesday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_wednesday" name="nta_wednesday" <?php echo (!empty($edit_account) ? $edit_account['nta_wednesday'] : '') ?>>
                                <label for="nta_wednesday">Wednesday
                                </label>
                            </td>
                            <td>
                                <select name="nta_wednesday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_wednesday_working'], 0, 5)) : get_times()); ?></select>

                            </td>
                            <td>
                                <select name="nta_wednesday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_wednesday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_thursday" name="nta_thursday" <?php echo (!empty($edit_account) ? $edit_account['nta_thursday'] : '') ?>>
                                <label for="nta_thursday">Thursday
                                </label>
                            </td>
                            <td>
                                <select name="nta_thursday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_thursday_working'], 0, 5)) : get_times()); ?></select>

                            </td>
                            <td>
                                <select name="nta_thursday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_thursday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_friday" name="nta_friday" <?php echo (!empty($edit_account) ? $edit_account['nta_friday'] : '') ?>>
                                <label for="nta_friday">Friday
                                </label>
                            </td>
                            <td>
                                <select name="nta_friday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_friday_working'], 0, 5)) : get_times()); ?></select>

                            </td>
                            <td>
                                <select name="nta_friday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_friday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="nta_saturday" name="nta_saturday" <?php echo (!empty($edit_account) ? $edit_account['nta_saturday'] : '') ?>>
                                <label for="nta_saturday">Saturday
                                </label>
                            </td>
                            <td>
                                <select name="nta_saturday_hour_start" class="nta_hour_start"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_saturday_working'], 0, 5)) : get_times()); ?></select>

                            </td>
                            <td>
                                <select name="nta_saturday_hour_end" class="nta_hour_end"><?php echo (!empty($edit_account) ? get_times(substr($edit_account['nta_saturday_working'], 6, 5)) : get_times('17:30')); ?></select>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
            <th scope="row"><label for="nta_offline_text">Description text when offline</label></th>
            <td>
                <input type="text" id="nta_offline_text" name="nta_offline_text" value="<?php echo (!empty($edit_account) ? $edit_account['nta_offline_text'] : 'I will be back in [njwa_time_work]') ?>" class="widefat" autocomplete="off">
                <p class="description">You can use shortcode [njwa_time_work] to display the extract time this account is back to work on a working day.
                </p>
                <input type="text" id="nta_over_time" name="nta_over_time" value="<?php echo (!empty($edit_account) ? $edit_account['nta_over_time'] : 'I will be back soon') ?>" class="widefat" autocomplete="off">
                <p class="description">You can use this text to display on days this account does not work.
                </p>
            </td>
        </tr>
    </tbody>
</table>