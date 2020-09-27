(function ($) {
    "use strict";
    /**
     * Eael Tabs
     */
    $(".eael-tabs li a").on("click", function (e) {
        e.preventDefault();
        $(".eael-tabs li a").removeClass("active");
        $(this).addClass("active");
        var tab = $(this).attr("href");
        $(".eael-settings-tab").removeClass("active");
        $(".eael-settings-tabs").find(tab).addClass("active");
    });

    $(".eael-get-pro").on("click", function () {
        Swal.fire({
            type: "warning",
            title: "<h2><span>Go</span> Premium",
            html:
                'Purchase our <b><a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" rel="nofollow">premium version</a></b> to unlock these pro components!',
            showConfirmButton: false,
            timer: 3000,
        });
    });

    // Save Button reacting on any changes
    var saveButton = $(".js-eael-settings-save");

    $(".eael-checkbox input:enabled").on("click", function (e) {
        saveButton
            .addClass("save-now")
            .removeAttr("disabled")
            .css("cursor", "pointer");
    });

    // Saving Data With Ajax Request
    $(".js-eael-settings-save").on("click", function (event) {
        event.preventDefault();

        var _this = $(this);

        if ($(this).hasClass("save-now")) {
            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "save_settings_with_ajax",
                    security: localize.nonce,
                    fields: $("form#eael-settings").serialize(),
                },
                beforeSend: function () {
                    _this.html(
                        '<svg id="eael-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span>Saving Data..</span>'
                    );
                },
                success: function (response) {
                    setTimeout(function () {
                        _this.html("Save Settings");
                        Swal.fire({
                            type: "success",
                            title: "Settings Saved!",
                            footer: "Have Fun :-)",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        saveButton.removeClass("save-now");
                    }, 500);
                },
                error: function () {
                    Swal.fire({
                        type: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                },
            });
        } else {
            $(this).attr("disabled", "true").css("cursor", "not-allowed");
        }
    });

    // Regenerate Assets
    $("#eael-regenerate-files").on("click", function (e) {
        e.preventDefault();
        var _this = $(this);

        $.ajax({
            url: localize.ajaxurl,
            type: "post",
            data: {
                action: "clear_cache_files_with_ajax",
                security: localize.nonce,
            },
            beforeSend: function () {
                _this.html(
                    '<svg id="eael-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span>Generating...</span>'
                );
            },
            success: function (response) {
                setTimeout(function () {
                    _this.html("Regenerate Assets");

                    Swal.fire({
                        type: "success",
                        title: "Assets Regenerated!",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }, 1000);
            },
            error: function () {
                Swal.fire({
                    type: "error",
                    title: "Ops!",
                    footer: "Something went wrong!",
                    showConfirmButton: false,
                    timer: 2000,
                });
            },
        });
    });

    // Elements global control
    $(document).on("click", ".eael-global-control-enable", function (e) {
        e.preventDefault();

        $(".eael-checkbox-container .eael-checkbox input:enabled").each(function (
            i
        ) {
            $(this).prop("checked", true).change();
        });

        saveButton
            .addClass("save-now")
            .removeAttr("disabled")
            .css("cursor", "pointer");
    });

    $(document).on("click", ".eael-global-control-disable", function (e) {
        e.preventDefault();

        $(".eael-checkbox-container .eael-checkbox input:enabled").each(function (
            i
        ) {
            $(this).prop("checked", false).change();
        });

        saveButton
            .addClass("save-now")
            .removeAttr("disabled")
            .css("cursor", "pointer");
    });

    // Popup
    $(document).on("click", ".eael-admin-settings-popup", function (e) {
        e.preventDefault();

        var title = $(this).data("title");
        var placeholder = $(this).data("placeholder");
        var type = $(this).data("option") || "text";
        var options = $(this).data("options") || {};
        var prepareOptions = {};
        var target = $(this).data("target");
        var val = $(target).val();
        var docSelector = $(this).data("doc");
        var docMarkup = docSelector
                        ? $(docSelector).clone().css("display", "block")
                        : false;

        if (Object.keys(options).length > 0) {
            prepareOptions["all"] = "All";

            for (var index in options) {
                prepareOptions[index] = options[index].toUpperCase();
            }
        }

        Swal.fire({
            title: title,
            input: type,
            inputPlaceholder: placeholder,
            inputValue: val,
            inputOptions: prepareOptions,
            footer: docMarkup,
            preConfirm: function (res) {
                $(target).val(res);

                saveButton
                    .addClass("save-now")
                    .removeAttr("disabled")
                    .css("cursor", "pointer");
            },
        });
    });

    $("#eael-js-print-method").on("change", function (evt) {
        var printMethod = $(this).val();
        saveButton
            .addClass("save-now")
            .removeAttr("disabled")
            .css("cursor", "pointer");

        if (printMethod === "internal") {
            $(".eael-external-printjs").hide();
            $(".eael-internal-printjs").show();
        } else {
            $(".eael-external-printjs").show();
            $(".eael-internal-printjs").hide();
        }
    });


    /**
     * Open a popup for typeform auth2 authentication
     */
    $("#eael-typeform-get-access").on('click', function (e) {
        e.preventDefault();
        var link = $(this).data("link");
        if (link != '') {
            window.open(link, 'mywindowtitle', 'width=500,height=500,left=500,top=200');
        }
    });

    // New Sweet Alert Forms for admin settings | Login & Register Settings
    $(document).on('click', '#eael-admin-settings-popup-extended', function (e) {
        e.preventDefault();
        const lr_i18n = localize.i18n.login_register;
        let settingsNodeId = $(this).data('settings-id');
        let $dnode = $('#'+settingsNodeId);
        let isProEnable = $dnode.data('pro-enabled');
        let rSitekey = $dnode.data('r-sitekey');
        let rSecret = $dnode.data('r-secret');
        let gClientId = $dnode.data('g-client-id');
        let fbAppId = $dnode.data('fb-app-id');
        let fbAppSecret = $dnode.data('fb-app-secret');
        let footerLink = isProEnable ? `<a target="_blank" href="https://essential-addons.com/elementor/docs/social-login-recaptcha">${lr_i18n.m_footer}</a>` : `<a target="_blank" href="https://www.google.com/recaptcha/admin/create">${lr_i18n.m_footer}</a>`
        let html = `<div class="eael-lr-settings-fields" id="lr_settings_fields">
                        <h2>${lr_i18n.r_title}</h2>
                        <div class="sf-group">
                            <label for="lr_recaptcha_sitekey">${lr_i18n.r_sitekey}:</label>
                            <input value="${rSitekey}" name="lr_recaptcha_sitekey" id="lr_recaptcha_sitekey" placeholder="${lr_i18n.r_sitekey}"/><br/>
                        </div>
                        <div class="sf-group">
                            <label for="lr_recaptcha_secret">${lr_i18n.r_sitesecret}:</label>
                            <input value="${rSecret}" name="lr_recaptcha_secret" id="lr_recaptcha_secret" placeholder="${lr_i18n.r_sitesecret}"/><br/>
                        </div>
                    `;
        if (isProEnable){
            html += `<hr>
                        <h2>${lr_i18n.g_title}</h2>
                        <div class="sf-group">
                            <label for="lr_g_client_id">${lr_i18n.g_cid}:</label>
                            <input value="${gClientId}" name="lr_g_client_id" id="lr_g_client_id" placeholder="${lr_i18n.g_cid}"/><br/>
                        </div>
                        <hr>
                        <h2>${lr_i18n.f_title}</h2>
                        <div class="sf-group">
                            <label for="lr_fb_app_id">${lr_i18n.f_app_id}:</label>
                            <input value="${fbAppId}" name="lr_fb_app_id" id="lr_fb_app_id" placeholder="${lr_i18n.f_app_id}"/><br/>
                        </div>
                        <div class="sf-group">
                            <label for="lr_fb_app_secret">${lr_i18n.f_app_secret}:</label>
                            <input value="${fbAppSecret}" name="lr_fb_app_secret" id="lr_fb_app_secret" placeholder="${lr_i18n.f_app_secret}"/><br/>
                        </div>`;
        }
        html  += '</div>'

        Swal.fire({
            title: `<strong>${lr_i18n.m_title}</strong>`,
            html: html,
            footer: footerLink,
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: lr_i18n.save,
            cancelButtonText: lr_i18n.cancel,
            preConfirm: () => {
                let formData = {
                    recaptchaSiteKey: document.getElementById('lr_recaptcha_sitekey').value,
                    recaptchaSiteSecret: document.getElementById('lr_recaptcha_secret').value,
                }
                if (isProEnable){
                    formData.gClientId = document.getElementById('lr_g_client_id').value;
                    formData.fbAppId = document.getElementById('lr_fb_app_id').value;
                    formData.fbAppSecret = document.getElementById('lr_fb_app_secret').value;
                }
                return formData;
            }
        }).then((result) => {
            if (result.value){
                $.ajax({
                    url: localize.ajaxurl,
                    type: "POST",
                    data: {
                        action: "save_settings_with_ajax",
                        security: localize.nonce,
                        fields: $.param(result.value),
                        is_login_register: 1,
                    },
                    success: function (response) {
                        if (response.success){
                            Swal.fire({
                                type: "success",
                                title: response.message ? response.message : lr_i18n.rm_title,
                                footer: lr_i18n.rm_footer,
                                showConfirmButton: true,
                                timer: 5000,
                            });
                        }
                    },
                    error: function (err) {
                        Swal.fire({
                            type: "error",
                            title: lr_i18n.e_title,
                            text: lr_i18n.e_text,
                        });
                    },
                });
            }
        });
    });

})(jQuery);

