(function ($) {
    var wa_time_out,
            wa_time_in;
    $(document).ready(function () {
        $('.wa__btn_popup').on('click', function () {
            if ($('.wa__popup_chat_box').hasClass('wa__active')) {
                $('.wa__popup_chat_box').removeClass('wa__active');
                $('.wa__btn_popup').removeClass('wa__active');
                clearTimeout(wa_time_in);
                if ($('.wa__popup_chat_box').hasClass('wa__lauch')) {
                    wa_time_out = setTimeout(function () {
                        $('.wa__popup_chat_box').removeClass('wa__pending');
                        $('.wa__popup_chat_box').removeClass('wa__lauch');
                    }, 400);
                }
            } else {
                $('.wa__popup_chat_box').addClass('wa__pending');
                $('.wa__popup_chat_box').addClass('wa__active');
                $('.wa__btn_popup').addClass('wa__active');
                clearTimeout(wa_time_out);
                if (!$('.wa__popup_chat_box').hasClass('wa__lauch')) {
                    wa_time_in = setTimeout(function () {
                        $('.wa__popup_chat_box').addClass('wa__lauch');
                    }, 100)
                }
            }
        })
    })
})(jQuery);