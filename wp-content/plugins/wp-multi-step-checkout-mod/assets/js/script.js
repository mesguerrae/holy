jQuery(window).ready(function ($) {
    "use strict";
    var tabs = $(".wpmc-tab-item");
    var sections = $(".wpmc-step-item");
    var buttons = $(".wpmc-nav-buttons .button");
    var checkout_form = $("form.woocommerce-checkout");
    var coupon_form = $("#checkout_coupon");
    var before_form = $("#woocommerce_before_checkout_form");
    var firstIndex = 1;
    $(".wpmc-step-item:first").addClass("current");
    function currentIndex() {
        return sections.index(sections.filter(".current"));
    }
    if ($(".wpmc-step-login").length == 1) {
        if ($("#woo_slg_social_login").length) {
            $("#woo_slg_social_login").show();
        }
        $(".checkout").hide();
        if ($().selectWoo) {
            var getEnhancedSelectFormatString = function () {
                return {
                    language: {
                        errorLoading: function () {
                            return wc_country_select_params.i18n_searching;
                        },
                        inputTooLong: function (args) {
                            var overChars = args.input.length - args.maximum;
                            if (1 === overChars) {
                                return wc_country_select_params.i18n_input_too_long_1;
                            }
                            return wc_country_select_params.i18n_input_too_long_n.replace("%qty%", overChars);
                        },
                        inputTooShort: function (args) {
                            var remainingChars = args.minimum - args.input.length;
                            if (1 === remainingChars) {
                                return wc_country_select_params.i18n_input_too_short_1;
                            }
                            return wc_country_select_params.i18n_input_too_short_n.replace("%qty%", remainingChars);
                        },
                        loadingMore: function () {
                            return wc_country_select_params.i18n_load_more;
                        },
                        maximumSelected: function (args) {
                            if (args.maximum === 1) {
                                return wc_country_select_params.i18n_selection_too_long_1;
                            }
                            return wc_country_select_params.i18n_selection_too_long_n.replace("%qty%", args.maximum);
                        },
                        noResults: function () {
                            return wc_country_select_params.i18n_no_matches;
                        },
                        searching: function () {
                            return wc_country_select_params.i18n_searching;
                        },
                    },
                };
            };
            var wc_country_select_select2 = function () {
                $("select.country_select, select.state_select, select#identification_type_, select#billing_city").each(function () {
                    var select2_args = $.extend({ placeholder: $(this).attr("data-placeholder") || $(this).attr("placeholder") || "", width: "100%" }, getEnhancedSelectFormatString());
                    $(this)
                        .on("select2:select", function () {
                            $(this).focus();
                        })
                        .selectWoo(select2_args);
                });
            };
            wc_country_select_select2();
            $(document.body).bind("country_to_state_changed", function () {
                wc_country_select_select2();
            });
        }
        firstIndex = 2;
    }
    $("#wpmc-next, #wpmc-skip-login").on("click", function () {
        $(".checkout").show();
        if ($("#woo_slg_social_login").length) {
            $("#woo_slg_social_login").hide();
        }
        if (currentIndex() == firstIndex) {
            var validPayment = validateOrderReview();
            if (validPayment) switchTab(currentIndex() + 1);
            else switchTab(firstIndex);
        } else {
            switchTab(currentIndex() + 1);
        }
    });
    $("#wpmc-prev").on("click", function () {
        if ($(".wpmc-step-login").length == 1 && currentIndex() == 1) {
            if ($("#woo_slg_social_login").length) {
                $("#woo_slg_social_login").show();
            }
            $(".checkout").hide();
        }
        switchTab(currentIndex() - 1);
    });
    $(document).on("checkout_error", function () {
        var section_class = $(".woocommerce-invalid-required-field").closest(".wpmc-step-item").attr("class");
        $(".wpmc-step-item").each(function (i) {
            if ($(this).attr("class") === section_class) {
                switchTab(i);
            }
        });
    });
    function switchTab(theIndex) {
        $(".woocommerce-checkout").trigger("wpmc_before_switching_tab");
        if (theIndex < 0 || theIndex > sections.length - 1) return false;
        var diff = $(".wpmc-tabs-wrapper").offset().top - $(window).scrollTop();
        if (diff < -40) {
            $("html, body").animate({ scrollTop: $(".wpmc-tabs-wrapper").offset().top - 70 }, 800);
        }
        $("html, body")
            .promise()
            .done(function () {
                tabs.removeClass("previous").filter(".current").addClass("previous");
                sections.removeClass("previous").filter(".current").addClass("previous");
                tabs.removeClass("current", { duration: 500 });
                tabs.eq(theIndex).addClass("current", { duration: 500 });
                sections.removeClass("current", { duration: 500 });
                sections.eq(theIndex).addClass("current", { duration: 500 });
                buttons.removeClass("current");
                checkout_form.addClass("processing");
                coupon_form.hide();
                before_form.hide();
                if (theIndex < sections.length - 1) $("#wpmc-next").addClass("current");
                if (theIndex === 0 && $(".wpmc-step-login").length > 0) {
                    $("#wpmc-skip-login").addClass("current");
                    $("#wpmc-next").removeClass("current");
                }
                if (theIndex === sections.length - 1) {
                    $("#wpmc-prev").addClass("current");
                    $("#wpmc-submit").addClass("current");
                    checkout_form.removeClass("processing").unblock();
                }
                if (theIndex != 0) $("#wpmc-prev").addClass("current");
                if ($(".wpmc-order.current").length > 0) {
                    coupon_form.show();
                }
                if ($(".wpmc-" + before_form.data("step") + ".current").length > 0) {
                    before_form.show();
                }
                $(".woocommerce-checkout").trigger("wpmc_after_switching_tab");
            });
    }
    if ($(".the_champ_sharing_container").length > 0) {
        $(".the_champ_sharing_container").insertAfter($(this).parent().find("#checkout_coupon"));
    }
    $(".woocommerce-checkout").keydown(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
    $("#wpmc-back-to-cart").click(function () {
        window.location.href = $(this).data("href");
    });
    $(".wpmc-tab-number").click(function () {
        switchTab($(this).text() - 1);
    });
    if (WPMC.keyboard_nav == "1") {
        $(document).keydown(function (e) {
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if (key === 39) {
                switchTab(currentIndex() + 1);
            }
            if (key === 37) {
                switchTab(currentIndex() - 1);
            }
        });
    }
    function validateOrderReview() {
        var isValid = true;
        $(".payment_review").hide();
        var name = $("#billing_first_name");
        var email = $("#billing_email");
        var address = $("#billing_address_1");
        var address_extra = $("#billing_address_2");
        var stateInput = $("#billing_state").select2("data");
        var cityInput = $("#billing_city").select2("data");
        $(".order-review-name").html(name.val());
        $(".order-review-email").html(email.val());
        $(".order-review-address").html(address.val() + " " + address_extra.val() + " (" + cityInput[0].text + " - " + stateInput[0].text + ")");
        var paymentMethod = $("#payment_method").val();
        if (paymentMethod == "payu_tc") {
            var tc_form = $("#wc-payu_tc-cc-form").serializeArray();
            $(tc_form).each(function (index, element) {
                if (element.value == "" && element.name != "id_type" && element.name != "id_number") {
                    isValid = false;
                }
            });
            var franchise = $("#franchise").val();
            if (franchise == "CODENSA") {
                var idType = $("#id_type").val();
                var idNumber = $("#no_document").val();
                if (idType == "") {
                    isValid = false;
                }
                if (idNumber == "") {
                    isValid = false;
                }
            }
            $("#tc_label").show();
            $("#" + franchise.toLowerCase() + "_img").show();
        } else if (paymentMethod == "payu_efectivo") {
            var efectiveMethod = $("#efectivo-select").val();
            if (efectiveMethod == "") {
                isValid = false;
            }
            $("#efectivo_label").show();
            $("#" + efectiveMethod.toLowerCase() + "_img").show();
        } else if (paymentMethod == "payu_trasferencia_bancaria") {
            var pse_form = $("#wc-payu_trasferencia_bancaria-cc-form").serializeArray();
            $(pse_form).each(function (index, element) {
                if (element.value == "") {
                    isValid = false;
                }
            });
            $("#pse_label").show();
            $("#pse_image_img").show();
        } else {
            isValid = false;
            alert("Porfavor seleccione un metodo de pago");
            return isValid;
        }
        if (!isValid) {
            alert("Porfavor complete los datos de pago");
        }
        return isValid;
    }
    $("body").on("blur change", "#identification_number_", function () {
        var wrapper = $(this).closest(".form-row");
        if (/^\d+$/.test($(this).val())) {
            wrapper.addClass("woocommerce-validated");
        } else {
            wrapper.addClass("woocommerce-invalid");
        }
    });
    $("body").on("blur change", "#billing_email", function () {
        var wrapper = $(this).closest(".form-row");
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (re.test($(this).val())) {
            wrapper.addClass("woocommerce-validated");
        } else {
            wrapper.addClass("woocommerce-invalid");
        }
    });
});
