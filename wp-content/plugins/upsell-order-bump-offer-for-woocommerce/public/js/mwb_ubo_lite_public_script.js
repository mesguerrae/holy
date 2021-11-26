jQuery(document).ready(function ($) {

    // When bump is prepared we will get this data.
    var bump_id = '';
    var bump_discount = '';
    var bump_target_cart_key = '';
    var default_image = '';
    var default_price = '';
    var order_bump_index = '';
    var order_bump_id = '';
    var smart_offer_upgrade = '';
    var selected_order_bump = '';
    var selected_order_bump_popup = '';

    function mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup) {

        if (mwb_ubo_lite_public.mobile_view != 1) {

            selected_order_bump_popup.find('.woocommerce-product-gallery__image').zoom();
        }
    }

    const triggerRemoveOffer = (order_bump_index, order_bump_id) => {
        // Remove the same product from cart.
        jQuery.ajax({

            type: 'post',
            dataType: 'json',
            url: mwb_ubo_lite_public.ajaxurl,
            data: {
                nonce: mwb_ubo_lite_public.auth_nonce,
                action: 'remove_offer_in_cart',

                // Index : index_{ digit }
                bump_index: order_bump_index,
                order_bump_id: order_bump_id,
            },

            success: function (msg) {

                $('body').trigger('update_checkout');
                $('.mwb_ubo_wrapper_' + order_bump_index).css('pointer-events', 'all');
                $('.mwb_ubo_wrapper_' + order_bump_index).css('opacity', '1');
            }
        });
    }

    /**
     * Process orderbump for variations.
     * 
     * @param {object} object    Bump object
     * @param {array}  formdata  Custom form object.
     */
    function triggerAddOfferVariation(object, form_data) {

        // Prevent mulitple clicks on this button.
        object.prop('disabled', true);

        order_bump_index = object.attr('offer_bump_index');
        if (typeof order_bump_index === 'undefined') {
            console.log('order bump not found');
            return;
        }

        // Order Bump Object.
        var parent_wrapper_class = '.mwb_ubo_wrapper_index_' + order_bump_index;
        var selected_order_bump = jQuery(parent_wrapper_class);

        // Disable bump div.
        $(parent_wrapper_class).css('pointer-events', 'none');
        $(parent_wrapper_class).css('opacity', '0.4');

        // Required Data.
        bump_id = selected_order_bump.find('.offer_shown_id').val();
        bump_discount = selected_order_bump.find('.offer_shown_discount').val();
        bump_target_cart_key = selected_order_bump.find('.target_id_cart_key').val();
        order_bump_id = selected_order_bump.find('.order_bump_id').val();
        smart_offer_upgrade = selected_order_bump.find('.order_bump_smo').val();

        var variation_selected = '';
        jQuery('body').find('.variation_id_selected').each(function () {
            if (object.attr('offer_bump_index') == order_bump_index) {
                variation_selected = jQuery(this).val();
            }
        });

        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: mwb_ubo_lite_public.ajaxurl,
            data: {
                nonce: mwb_ubo_lite_public.auth_nonce,
                action: 'add_variation_offer_in_cart',
                id: variation_selected, // variation offer product id.
                parent_id: bump_id, // variation offer parent product id.
                discount: bump_discount,
                order_bump_id: order_bump_id,
                smart_offer_upgrade: smart_offer_upgrade,

                // Index : { digit }
                bump_index: order_bump_index,
                bump_target_cart_key: bump_target_cart_key,

                // Form data if present.
                form_data: form_data
            },
            success: function (msg) {

                $('body').removeClass('mwb_upsell_variation_pop_up_body');
                $('.mwb_bump_popup_wrapper').css('display', 'none');
                $('body').trigger('update_checkout');
                $(parent_wrapper_class).css('pointer-events', 'all');
                $(parent_wrapper_class).css('opacity', '1');
                $('.mwb_ubo_bump_add_to_cart_button').prop('disabled', false);

                // When Reload is required.
                if ('subs_reload' == msg) {

                    // Scroll Top and Reload.
                    $("html, body").scrollTop(300);
                    location.reload();
                }
            }
        });
    }

    /**
     * Process orderbump.
     * 
     * @param {object} object    Bump object
     * @param {array}  formdata  Custom form object.
     */
    function triggerAddOffer(object, formdata) {

        order_bump_index = object.closest('.mwb_upsell_offer_main_wrapper').find('.order_bump_index').val();
        parent_wrapper_class = '.mwb_ubo_wrapper_' + order_bump_index;
        order_bump_id = object.closest('.mwb_upsell_offer_main_wrapper').find('.order_bump_id').val();

        // Disable bump div.
        $('.mwb_ubo_wrapper_' + order_bump_index).css('pointer-events', 'none');
        $('.mwb_ubo_wrapper_' + order_bump_index).css('opacity', '0.4');

        if ($(parent_wrapper_class + ' .add_offer_in_cart').is(':checked')) {

            // Get Order Bump variation popup ready.
            handle_pre_selected_values();

            // Show loader for Variable offers.
            if ('variable' == object.closest('.mwb_upsell_offer_main_wrapper').find('.offer_shown_id_type').val()) {
                $('.mwb_bump_popup_loader').css('display', 'flex');
            }

            bump_id = object.closest('.mwb_upsell_offer_main_wrapper').find('.offer_shown_id').val(); // offer product id.
            bump_discount = object.closest('.mwb_upsell_offer_main_wrapper').find('.offer_shown_discount').val();
            bump_target_cart_key = object.closest('.mwb_upsell_offer_main_wrapper').find('.target_id_cart_key').val();
            smart_offer_upgrade = object.closest('.mwb_upsell_offer_main_wrapper').find('.order_bump_smo').val();

            // Add product to cart.
            jQuery.ajax({

                type: 'post',
                dataType: 'json',
                url: mwb_ubo_lite_public.ajaxurl,
                data: {
                    nonce: mwb_ubo_lite_public.auth_nonce,
                    action: 'add_offer_in_cart',
                    id: bump_id, // offer product id.
                    discount: bump_discount,
                    bump_target_cart_key: bump_target_cart_key,
                    order_bump_id: order_bump_id,
                    smart_offer_upgrade: smart_offer_upgrade,

                    // Index : index_{ digit }
                    bump_index: order_bump_index,
                    // Form data.
                    form_data: formdata,
                },

                success: function (msg) {

                    // For variable product.
                    if (msg['key'] == 'true') {

                        variation_popup_index = order_bump_index.replace('index_', '');
                        $('.mwb_ubo_price_html_for_variation').html(msg['message']);
                        $('.mwb_bump_popup_loader').css('display', 'none');
                        $('.mwb_bump_popup_' + variation_popup_index).css('display', 'flex');
                        $('body').addClass('mwb_upsell_variation_pop_up_body');

                        // Add zoom to defaut image.
                        selected_order_bump_popup = jQuery('.mwb_bump_popup_' + variation_popup_index);
                        mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup);

                        if (default_price == '') {
                            default_price = $('.mwb_ubo_price_html_for_variation').html();
                        }
                    }

                    // For simple Products and Variations.
                    else {

                        $('.mwb_bump_popup_loader').css('display', 'none');
                        $('body').trigger('update_checkout');
                        $('.mwb_ubo_wrapper_' + order_bump_index).css('pointer-events', 'all');
                        $('.mwb_ubo_wrapper_' + order_bump_index).css('opacity', '1');

                        // When Reload is required.
                        if ('subs_reload' == msg) {

                            // Scroll Top and Reload.
                            $("html, body").scrollTop(300);
                            location.reload();
                        }
                    }
                }
            });
        }
    }

    // Prevent Enter Key Press for checkbox of Order Bump offers.
    $(document).on('keypress', '.add_offer_in_cart', function (e) {
        // The enter key code.
        if (e.which == 13) {
            e.preventDefault();
        }
    });

    /*==========================================================================
                            Add to cart checkbox click
    ============================================================================*/
    /**
     * CHECKBOX ADD TO CART [ works with simple product and product variations ].
     */
    jQuery(document).on('click', '.add_offer_in_cart', function (e) {

        order_bump_trigger_obj = jQuery(this);
        order_bump_index = order_bump_trigger_obj.closest('.mwb_upsell_offer_main_wrapper').find('.order_bump_index').val();
        parent_wrapper_class = '.mwb_ubo_wrapper_' + order_bump_index;
        order_bump_id = order_bump_trigger_obj.closest('.mwb_upsell_offer_main_wrapper').find('.order_bump_id').val();


        // When offer is added.
        if (order_bump_trigger_obj.is(':checked')) {

            // Check if meta form present.
            let popup_obj = jQuery('#mwb-meta-form-index-' + order_bump_id);

            let index = 0;

            // Meta form available.
            if (popup_obj.length > 0 && !popup_obj.hasClass('mwb_bump_popup_variable_meta_form')) {

                open_custom_form(popup_obj, order_bump_trigger_obj);

                jQuery('.mwb-meta-form-submit').on('click', function (e) {

                    e.preventDefault();
                    let data_arr = [];
                    jQuery('#mwb-meta-form-index-' + order_bump_id).find('.mwb_ubo_custom_meta_field').each(function () {
                    
                        let field_obj = {};

                        if ('' == jQuery(this).val()) {
                            alert(jQuery(this).attr('name') + ' field cannot be empty');
                            return;
                        } else if ('checkbox' == jQuery(this).attr('type')) {

                            // Push the values in an array.
                            field_obj.name = jQuery(this).attr('name');
                            field_obj.value = jQuery(this).prop('checked');
                            data_arr[index] = field_obj;
                            index++;
                        } else {
                            // Push the values in an array.
                            field_obj.name = jQuery(this).attr('name');
                            field_obj.value = jQuery(this).val();

                            data_arr[index] = field_obj;
                            index++;
                        }
                    });

                    data_arr = data_arr.filter(onlyUnique);

                    // All fields are saved!
                    if (data_arr.length == popup_obj.find('.mwb_ubo_custom_meta_field').length) {

                        // Close popup and send add to cart request.
                        close_custom_form();
                        triggerAddOffer(order_bump_trigger_obj, data_arr);
                    }
                });

            } else {

                triggerAddOffer(jQuery(this), []);
            }

        } else {

            // Remove Offer.
            triggerRemoveOffer(order_bump_index, order_bump_id);
        }

    });

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }


    /*=======================================================================
                        Default selection trigger in variations popup.
    ========================================================================*/

    function handle_pre_selected_values() {
        var variations = jQuery('.mwb_upsell_offer_variation_select');
        if (variations.length > 0) {
            var value_exists = false;
            variations.each(function () {
                if (jQuery(this).val() !== '') {
                    value_exists = true;
                    return;
                }
            });

            if (true == value_exists) {
                jQuery('.mwb_upsell_offer_variation_select').trigger('change');
            }
        }
    }

    function open_custom_form(form_obj, order_bump_obj) {

        let form_wrap = form_obj.parent().parent().parent().parent();
        jQuery('body').css('overflow', 'hidden');
        if (jQuery('.mwb-g-modal').hasClass('mwb-modal--close')) {
            jQuery('.mwb-g-modal').removeClass('mwb-modal--close');
        }
        form_wrap.addClass('mwb-modal--open');

        jQuery('.mwb-g-modal__close').on('click', function () {
            order_bump_obj.prop('checked', false);
            close_custom_form();
        });
    }

    function close_custom_form() {
        jQuery('body').css('overflow', 'auto');
        jQuery('.mwb-g-modal').addClass('mwb-modal--close');
        setTimeout(function () {
            jQuery('.mwb-g-modal').removeClass('mwb-modal--open');
        }, 320);
    }

    /*=======================================================================
                        Select the variations in popup.
    ========================================================================*/
    /*
     * POP-UP Select change JS,
     * To add the price html and image of selected variation in popup.
     */

    $(document).on('change', '.mwb_upsell_offer_variation_select', function (e) {

        var selected_order_bump_index = $(this).attr('order_bump_index');

        // Order Bump Object.
        var parent_wrapper_class = '.mwb_ubo_wrapper_index_' + selected_order_bump_index;
        selected_order_bump = jQuery(parent_wrapper_class);

        // Order Bump Popup Object.
        var popup_wrapper_class = '.mwb_bump_popup_' + selected_order_bump_index;
        selected_order_bump_popup = jQuery(popup_wrapper_class);

        // Fetch selected attributes.
        var selected_variations = selected_order_bump_popup.find('.mwb_upsell_offer_variation_select');
        var attributes_selected = [];

        // Default image handle here.
        if (default_image == '') {

            if (selected_order_bump_popup.find('woocommerce-product-gallery__image')) {

                default_image = selected_order_bump_popup.find('.woocommerce-product-gallery__image');
            }

            else {

                default_image = selected_order_bump_popup.find('.woocommerce-placeholder');
            }
        }

        for (var i = selected_variations.length - 1; i >= 0; i--) {

            if (selected_variations[i].value == '') {

                // Default View on no selection.
                selected_order_bump_popup.find('.mwb_bump_popup_image').html(default_image);
                mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup);
                selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').css('display', 'none');
                selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').css('display', 'block');
                selected_order_bump_popup.find('.mwb_ubo_bump_add_to_cart_button').css('display', 'none');
                selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').html(default_price);

                return;

            } else {

                attributes_selected[selected_variations[i].id] = selected_variations[i].value;
            }
        }

        /*
         * Got an array of all selected attribute.
         * Will run an ajax for search of attributes.
         * Show price and image.
         * Will see for variation id and in stock.
         * Add to cart button will be shown. 
         */

        // Converts attributes array in object.
        attributes_selected = Object.assign({}, attributes_selected);

        // Required Data.
        bump_id = selected_order_bump.find('.offer_shown_id').val();
        bump_discount = selected_order_bump.find('.offer_shown_discount').val();

        jQuery.ajax({

            type: 'post',
            dataType: 'json',
            url: mwb_ubo_lite_public.ajaxurl,
            data: {
                nonce: mwb_ubo_lite_public.auth_nonce,
                action: 'search_variation_id_by_select',
                attributes_selected_options: attributes_selected,
                id: bump_id,
                discount: bump_discount,
            },

            success: function (msg) {
                if (msg['key'] == 'stock') {

                    selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').css('display', 'flex');
                    selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').css('display', 'none');
                    selected_order_bump_popup.find('.mwb_ubo_bump_add_to_cart_button').css('display', 'none');

                    selected_order_bump_popup.find('.mwb_bump_popup_image').html(msg['image']);
                    mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup);
                    selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').html(msg['message']);

                } else if (msg['key'] == 'not_available') {

                    selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').css('display', 'flex');
                    selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').css('display', 'none');
                    selected_order_bump_popup.find('.mwb_ubo_bump_add_to_cart_button').css('display', 'none');

                    selected_order_bump_popup.find('.mwb_bump_popup_image').html(msg['image']);
                    mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup);
                    selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').html(msg['message']);

                } else if (!isNaN(msg['key'])) {

                    selected_order_bump_popup.find('.mwb_ubo_err_waring_for_variation').css('display', 'none');
                    selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').css('display', 'block');
                    selected_order_bump_popup.find('.mwb_ubo_bump_add_to_cart_button').css('display', 'flex');

                    selected_order_bump_popup.find('.mwb_bump_popup_image').html(msg['image']);
                    mwb_ubo_lite_intant_zoom_img(selected_order_bump_popup);
                    selected_order_bump_popup.find('.variation_id_selected').val(msg['key']);
                    selected_order_bump_popup.find('.mwb_ubo_price_html_for_variation').html(msg['message']);
                }
            }
        });
    });

    /*==========================================================================
                            Variation popup add to cart
    ============================================================================*/
    /*
     * POP-UP ADD TO CART BUTTON [ works with variable products].
     * To add the selected js.
     */
    $(document).on('click', '.mwb_ubo_bump_add_to_cart_button', function (e) {
        e.preventDefault();

        order_bump_index = jQuery(this).attr('offer_bump_index');

        // Order Bump Object.
        var parent_wrapper_class = '.mwb_bump_popup_' + order_bump_index;
        var popup_obj = jQuery(parent_wrapper_class);

        // Meta form exists.
        if (popup_obj.length > 0) {
            let data_arr = [];
            popup_obj.find('.mwb_ubo_custom_meta_field').each(function (index) {

                let field_obj = {};
                if ('' == jQuery(this).val()) {
                    alert(jQuery(this).attr('name') + ' field cannot be empty');
                    return;
                } else if ('checkbox' == jQuery(this).attr('type')) {

                    // Push the values in an array.
                    field_obj.name = jQuery(this).attr('name');
                    field_obj.value = jQuery(this).prop('checked');
                    data_arr[index] = field_obj;
                    index++;
                } else {
                    // Push the values in an array.
                    field_obj.name = jQuery(this).attr('name');
                    field_obj.value = jQuery(this).val();

                    data_arr[index] = field_obj;

                }
            });

            // All fields are saved!
            if (data_arr.length == popup_obj.find('.mwb_ubo_custom_meta_field').length) {

                // Close popup and send add to cart request.
                triggerAddOfferVariation(jQuery(this), data_arr);
            }
        } else { // Simple variable add to cart.
            triggerAddOfferVariation(jQuery(this), []);
        }
    });

    /*==========================================================================
                                Popup closing
    ============================================================================*/
    /*
     * POP-UP JS.
     * To hide on click close.
     */
    $(document).on('click', '.mwb_bump_popup_close', function (e) {

        order_bump_index = $(this).attr('offer_bump_index');

        $('.mwb_ubo_wrapper_index_' + order_bump_index).css('pointer-events', 'all');
        $('.mwb_ubo_wrapper_index_' + order_bump_index).css('opacity', '1');
        $('body').removeClass('mwb_upsell_variation_pop_up_body');
        $('.mwb_bump_popup_' + order_bump_index).css('display', 'none');
        $('.mwb_ubo_wrapper_index_' + order_bump_index).find('.add_offer_in_cart').prop('checked', false);
        $('.mwb_bump_popup_meta_form_fields').css('display', 'none');
    });


    // Onclick outside the div close the popup.
    $('body').click(function (e) {

        if (e.target.className.search('mwb_bump_popup_wrapper') == 0) {

            order_bump_index = e.target.className.replace('mwb_bump_popup_wrapper mwb_bump_popup_', '');

            $('.mwb_ubo_wrapper_index_' + order_bump_index).css('pointer-events', 'all');
            $('.mwb_ubo_wrapper_index_' + order_bump_index).css('opacity', '1');
            $('body').removeClass('mwb_upsell_variation_pop_up_body');
            $('.mwb_bump_popup_wrapper').hide();
            $('.mwb_ubo_wrapper_index_' + order_bump_index).find('.add_offer_in_cart').prop('checked', false);
        }
    }
    );


    /*==========================================================================
                                Zooming Effect on mobile.
    ============================================================================*/
    if (mwb_ubo_lite_public.mobile_view != 1) {

        // Function for zooming image( not for mobile view ).
        $(document).on('hover', '.mwb_bump_popup_image', function (e) {

            // Block opening image.
            e.preventDefault();
            $('.woocommerce-product-gallery__image').zoom({
                magnify: 1.0  // Magnify upto 120 %.
            });
        });

    } else {

        $(document).on('click', '.mwb_bump_popup_image', function (e) {

            // Block opening image.
            e.preventDefault();
        });
    }

    // END OF SCRIPT
});
