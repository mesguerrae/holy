<div class="search-account">
<!--    <input name="" type="text" id="input-account" value="" class="regular-text" autocomplete="off" />-->
    <input id="input-users" type="text" autocomplete="off" placeholder="Search account by enter name or title">
</div>
<br/>

<label class="nta-list-status"><strong>Selected Accounts:</strong></label>

<div class="nta-list-box-accounts postbox" id="sortable">
    <?php foreach ($account_list_view as $row): ?>
        <div class="nta-list-items" data-index="<?php echo $row['account_id'] ?>" data-position="<?php echo $row['position'] ?>">
            <div class="box-content">
                <div class="box-row">
                    <div class="account-avatar">
                        <?php if (!empty($row['avatar'])): ?>
                            <div class="wa_img_wrap" style="background: url(<?php echo $row['avatar'] ?>) center center no-repeat; background-size: cover;"></div>
                        <?php else:
                            echo NTA_WHATSAPP_DEFAULT_AVATAR;
                            ?>
                        <?php endif; ?>
                    </div>
                    <div class="container-block">
                        <a href="<?php echo get_edit_post_link($row['account_id']); ?>"><h4><?php echo $row['post_title'] ?></h4></a>
                        <p><?php echo $row['nta_title'] ?></p>
                        <p>
                            <span <?php echo ($row['nta_monday'] == 'checked' ? 'class="active-date"' : '') ?>>Mon</span><span <?php echo ($row['nta_tuesday'] == 'checked' ? 'class="active-date"' : '') ?>>Tue</span><span <?php echo ($row['nta_wednesday'] == 'checked' ? 'class="active-date"' : '') ?>>Wed</span><span <?php echo ($row['nta_thursday'] == 'checked' ? 'class="active-date"' : '') ?>>Thur</span><span <?php echo ($row['nta_friday'] == 'checked' ? 'class="active-date"' : '') ?>>Fri</span><span <?php echo ($row['nta_saturday'] == 'checked' ? 'class="active-date"' : '') ?>>Sar</span><span <?php echo ($row['nta_sunday'] == 'checked' ? 'class="active-date"' : '') ?> >Sun</span>
                        </p>
                        <a data-remove="<?php echo $row['account_id'] ?>" href="javascrtip:;" class="btn-remove-account">Remove</a>
                    </div>
                    <div class="icon-block">
                        <img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'images/bar-sortable.svg' ?>" width="20px">
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
