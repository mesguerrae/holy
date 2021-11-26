<div class="wa__btn_popup">
    <div class="wa__btn_popup_txt"><?php echo $option['widget_label'] ?></div>
    <div class="wa__btn_popup_icon"></div>
</div>
<div class="wa__popup_chat_box">
    <div class="wa__popup_heading">
        <div class="wa__popup_title"><?php echo $option['widget_name'] ?></div>
        <div class="wa__popup_intro"><?php echo $option['widget_description'] ?></a></div>
    </div>
    <!-- /.wa__popup_heading -->
    <div class="wa__popup_content wa__popup_content_left">
        <div class="wa__popup_notice"><?php echo $option['widget_responseText'] ?></div>
        <div class="wa__popup_content_list">

            <?php foreach ($account_list_view as $row): ?>
                <?php
                    $href = '';
                    
                    if (strpos( $row['nta_group_number'], 'chat.whatsapp.com')) {
                        $href .= 'target="_blank"';
                        $href .= ' href="'.esc_url( $row['nta_group_number'] ).'"';
                    } else {
                        $href = $link_to_app == 'web' ? 'target="_blank"' : '';
                        $href .= ' href="https://';
                        $href .= $link_to_app . '.whatsapp.com/send?phone=';
                        $number = preg_replace( '/[^0-9]/', '', $row['nta_group_number'] );
                        $href .= $number;
                        $href .= ($row['nta_predefined_text'] != '' ? '&text=' . do_shortcode($row['nta_predefined_text']) : '');
                        $href .= '"';
                    }

                    $href .= ' class="wa__stt ';
                    $href .= (get_back_time($row) == 'online' ? 'wa__stt_online' : 'wa__stt_offline');
                    $href .= '"';
                ?>
                <div class="wa__popup_content_item">
                    <a <?php echo $href ?>>
                        <?php if (!empty($row['avatar'])): ?>
                            <div class="wa__popup_avatar">
                                <div class="wa__cs_img_wrap" style="background: url(<?php echo $row['avatar'] ?>) center center no-repeat; background-size: cover;"></div>
                            </div>
                        <?php else : ?>
                            <div class="wa__popup_avatar nta-default-avt">   
                                <?php echo NTA_WHATSAPP_DEFAULT_AVATAR ?>
                            </div>
                        <?php endif; ?>

                        <div class="wa__popup_txt">
                            <div class="wa__member_name"><?php echo $row['post_title'] ?></div>
                            <!-- /.wa__member_name -->
                            <div class="wa__member_duty"><?php echo $row['nta_title'] ?></div>
                            <!-- /.wa__member_duty -->
                            <?php if (get_back_time($row) != 'online'):?>
                            <div class="wa__member_status">
                                <?php echo ((get_back_time($row) == 'offline') ? $row['nta_over_time'] : do_shortcode('[njwa_time_work_wg id="'. $row['account_id'] .'"]')); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <!-- /.wa__popup_txt -->
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
        <!-- /.wa__popup_content_list -->
    </div>
    <!-- /.wa__popup_content -->
</div>
<!-- /.wa__popup_chat_box -->