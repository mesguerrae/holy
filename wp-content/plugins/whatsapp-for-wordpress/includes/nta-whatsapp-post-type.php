<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

class NTA_Whatsapp_PostType {

    public function __construct() {

        add_action('init', function() {

            $labels = array(
                'name' => __('WhatsApp Accounts'),
                'singular_name' => __('Whatsapp Account'),
                'add_new' => __('Add  New Account'),
                'add_new_item' => __('Add New Account'),
                'edit_item' => __('Edit Account'),
                'new_item' => __('New Account'),
                'all_items' => __('All Accounts'),
                'view_item' => __('View Accounts'),
                'search_items' => __('Search Account'),
                'featured_image' => 'Avatar',
                'set_featured_image' => 'Select an image',
                'remove_featured_image' => 'Remove avatar'
            );

            $args = array(
                'labels' => $labels,
                'description' => 'Manager Accounts',
                'public' => false,
                'show_ui' => true,
                'has_archive' => true,
                'show_in_admin_bar' => false,
                'show_in_menu' => 'nta_whatsapp',
                'menu_position' => 100,
                'query_var' => 'whatsapp-accounts',
                'supports' => array(
                    'title',
                    'thumbnail',
                ),
            );
            register_post_type('whatsapp-accounts', $args);
        });

        add_action('save_post', [$this, 'save_account'], 10, 2);

        add_action('add_meta_boxes', function() {
            add_meta_box('whatsapp-account-info', 'WhatsApp Account Information', [$this, 'meta_form_account'], 'whatsapp-accounts', 'normal');
            add_meta_box('whatsapp-button-style', 'Button Style', [$this, 'meta_form_button_style'], 'whatsapp-accounts', 'normal');
            $current_screen = get_current_screen();
            if ($current_screen->action != 'add') {
                add_meta_box('whatsapp-button-shortcode', 'Shortcode for this account', [$this, 'account_shortcode_form'], 'whatsapp-accounts', 'side');
            }
        });

        add_filter('manage_whatsapp-accounts_posts_columns', [$this, 'manager_accounts_columns'], 10, 1);

        add_action('manage_whatsapp-accounts_posts_custom_column', [$this, 'manager_accounts_show_columns'], 10, 2);
        add_filter('enter_title_here', 'my_title_place_holder', 20, 2);

        function my_title_place_holder($title, $post) {
            if ($post->post_type == 'whatsapp-accounts') {
                $my_title = "Account Name";
                return $my_title;
            }

            return $title;
        }

        
    }

    public function save_account($post_id, $post) {
        // Ki???m tra n???u nonce ch??a ???????c g??n gi?? tr???
        if (!isset($_POST['form_account_nonce'])) {
            return;
        }
        // Ki???m tra n???u gi?? tr??? nonce kh??ng tr??ng kh???p
        if (!wp_verify_nonce($_POST['form_account_nonce'], 'save_form_account')) {
            return;
        }

        $new_account = array(
            'nta_group_number' => $_POST['nta_group_number'],
            'nta_title' => $_POST['nta_title'],
            'nta_predefined_text' => $_POST['nta_predefined_text'],
            'nta_offline_text' => $_POST['nta_offline_text'],
            'nta_over_time' => $_POST['nta_over_time'],
            'nta_sunday' => isset($_POST['nta_sunday']) ? 'checked' : '',
            'nta_sunday_working' => $_POST['nta_sunday_hour_start'] . '-' . $_POST['nta_sunday_hour_end'],
            'nta_monday' => isset($_POST['nta_monday']) ? 'checked' : '',
            'nta_monday_working' => $_POST['nta_monday_hour_start'] . '-' . $_POST['nta_monday_hour_end'],
            'nta_tuesday' => isset($_POST['nta_tuesday']) ? 'checked' : '',
            'nta_tuesday_working' => $_POST['nta_tuesday_hour_start'] . '-' . $_POST['nta_tuesday_hour_end'],
            'nta_wednesday' => isset($_POST['nta_wednesday']) ? 'checked' : '',
            'nta_wednesday_working' => $_POST['nta_wednesday_hour_start'] . '-' . $_POST['nta_wednesday_hour_end'],
            'nta_thursday' => isset($_POST['nta_thursday']) ? 'checked' : '',
            'nta_thursday_working' => $_POST['nta_thursday_hour_start'] . '-' . $_POST['nta_thursday_hour_end'],
            'nta_friday' => isset($_POST['nta_friday']) ? 'checked' : '',
            'nta_friday_working' => $_POST['nta_friday_hour_start'] . '-' . $_POST['nta_friday_hour_end'],
            'nta_saturday' => isset($_POST['nta_saturday']) ? 'checked' : '',
            'nta_saturday_working' => $_POST['nta_saturday_hour_start'] . '-' . $_POST['nta_saturday_hour_end'],
//            'wo_active' => 'none',
//            'wo_position' => '0'
        );

        $refer_url = $_POST['_wp_http_referer'];
        $add_new_action = strpos($refer_url, 'post-new.php');

        if ($add_new_action !== false) {
            $new_account['position'] = '0';
            $new_account['nta_active'] = 'none';
            $new_account['wo_active'] = 'none';
            $new_account['wo_position'] = '0';
        } else {
            $old_account = get_post_meta($post_id, 'nta_whatsapp_accounts', true);
            $new_account['position'] = $old_account['position'];
            $new_account['nta_active'] = $old_account['nta_active'];
            $new_account['wo_active'] = $old_account['wo_active'];
            $new_account['wo_position'] = $old_account['wo_position'];
        }

        update_post_meta($post_id, 'nta_whatsapp_accounts', $new_account);


        //Save button style

        if ($_POST['nta_button_label'] != '' || $_POST['button_style'] != '' || $_POST['button_back_color'] != '' || $_POST['button_text_color'] != '') {
            $new_input['button-text'] = $_POST['nta_button_label'];
            $new_input['button_style'] = $_POST['button_style'];
            $new_input['button_back_color'] = $_POST['button_back_color'];
            $new_input['button_text_color'] = $_POST['button_text_color'];
            update_post_meta($post_id, 'nta_wabutton_style', $new_input);
        }
    }

    public function meta_form_account($post) {
        wp_nonce_field('save_form_account', 'form_account_nonce');
        $edit_account = get_post_meta($post->ID, 'nta_whatsapp_accounts', true);
        $edit_button_label = get_post_meta($post->ID, 'nta_wabutton_style', true);
        require(NTA_WHATSAPP_PLUGIN_DIR . 'views/nta-whatsapp-meta-accounts.php');
    }

    public function account_shortcode_form() {
        ?>
        <p>Copy the shortcode below and paste it into the editor to display the button.</p>
        <p><input type="text" id="nta-button-shortcode-copy" value="[njwa_button id=&quot;<?php echo get_the_ID() ?>&quot;]" class="widefat" readonly=""></p>
        <p class="nta-shortcode-copy-status hidden" style="color: green"><strong>Copied!</strong></p>
        <?php
    }

    public function meta_form_button_style($post) {
        $buttonStyle = get_post_meta($post->ID, 'nta_wabutton_style', true);
        if (empty($buttonStyle)) {
            $buttonStyle = array();
            $buttonStyle['button-text'] = '';
            $buttonStyle['button_style'] = '';
            $buttonStyle['button_back_color'] = '';
            $buttonStyle['button_text_color'] = '';
        }

        require(NTA_WHATSAPP_PLUGIN_DIR . 'views/nta-whatsapp-meta-button-style.php');
    }

    public function manager_accounts_columns($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => 'Account Name',
            'nta_wa_avatar' => 'Avatar',
            'nta_wa_number' => 'Number',
            'nta_wa_title' => 'Title',
            'activedays' => 'Active Days',
            'shortcode' => 'Shortcode'
        );
        return $columns;
    }

    function manager_accounts_sortable_columns($columns) {

        $columns['number'] = 'Number';
        $columns['timeslot'] = 'Time Slot';

        return $columns;
    }

    function manager_accounts_show_columns($name, $post_id) {
        $data_account = get_post_meta($post_id, 'nta_whatsapp_accounts', true);

        switch ($name) {
            case 'nta_wa_avatar':
                the_post_thumbnail('thumbnail', array('class' => 'img-size-table'));
                break;
            case 'nta_wa_number':
                echo $data_account['nta_group_number'];
                break;
            case 'nta_wa_title':
                echo $data_account['nta_title'];
                break;
            case 'activedays':
                echo print_date($data_account);
                break;
            case 'shortcode':
                echo '<input type="text" class="nta-shortcode-table" name="country" value="[njwa_button id=&quot;' . $post_id . '&quot;]" readonly>';
                break;
        }
    }

}


