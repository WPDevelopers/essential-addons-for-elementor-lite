(function ($) {
    let $gb_editor_panel = $('#editor');
    var $is_popup_button_added = false;

    wp.data.subscribe(function () {
        setTimeout(function () {
            essential_block_button_init();
        }, 1);
    });

    function essential_block_button_init() {
        if (!$('#eael-eb-popup-button').length && !$is_popup_button_added) {
            $gb_editor_panel.find('.edit-post-header__settings, .editor-header__settings').prepend($('#eael-gb-eb-button-template').html());
            if ($('#eael-eb-popup-button').length) {
                $is_popup_button_added = true;

                if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                    $('#eael-eb-popup-button').closest('.editor-header').addClass('has-eael-eb-promo');
                }
            }
        }
    }

    $(document).on('click', '#eael-eb-popup-button', function () {
        $('body').append($('#eael-gb-eb-popup-template').html()).append(`<div id="eael-gb-eb-popup-overlay"></div>`);
    }).on('click', '.eael-gb-eb-dismiss, #eael-gb-eb-popup-overlay', function () {
        $('.eael-gb-eb-popup, #eael-gb-eb-popup-overlay').remove();
    }).on('click', '.eael-gb-eb-content-pagination span', function () {
        let $this = $(this),
            page_id = $this.data('page'),
            page_content = $(`#eael-gb-eb-button-template-page-${page_id}`).html();

        $('.eael-gb-eb-popup-content.--page-1').addClass('hide-eael-gb-eb-never-show-button');
        $this.addClass('active').siblings().removeClass('active').closest('.eael-gb-eb-popup-content')
            .removeClass('--page-1 --page-2 --page-3 --page-4 --page-5').addClass(`--page-${page_id}`);
        $('.eael-gb-eb-popup .eael-gb-eb-content-image').html($(page_content).find('.eael-gb-eb-content-image').html());
        $('.eael-gb-eb-popup .eael-gb-eb-content-info').html($(page_content).find('.eael-gb-eb-content-info').html());
    }).on('click', '.eael-gb-eb-prev, .eael-gb-eb-next', function () {
        let $this = $(this),
            isNext = $this.hasClass('eael-gb-eb-next'),
            isPrev = $this.hasClass('eael-gb-eb-prev'),
            currentPage = $('.eael-gb-eb-content-pagination span.active');

        if (isNext) {
            currentPage.next().trigger('click');
        } else if (isPrev) {
            currentPage.prev().trigger('click');
        }
    }).on('click', 'button.eael-gb-eb-never-show', function () {
        let $this = $(this),
            nonce = $this.data('nonce');

        $.ajax({
            url: "admin-ajax.php",
            type: "POST",
            data: {
                action: "eael_gb_eb_popup_dismiss",
                security: nonce,
            },
            success: function (response) {
                if (response.success) {
                    $('.eael-gb-eb-dismiss').trigger('click');
                    $('#eael-eb-popup-button').remove();
                } else {
                    console.log(response.data);
                }
            },
            error: function (err) {
                console.log(err.responseText);
            },
        });
    }).on('click', 'button.eael-gb-eb-install', function (ev) {
        ev.preventDefault();

        let button = $(this),
            action = button.data("action"),
            nonce = button.data("nonce");

        if ($.active && typeof action != "undefined") {
            button.text("Waiting...").attr("disabled", true);

            setInterval(function () {
                if (!$.active) {
                    button.attr("disabled", false).trigger("click");
                }
            }, 1000);
        }

        if (action === "install" && !$.active) {
            button.text("Installing...").attr("disabled", true);

            $.ajax({
                url: "admin-ajax.php",
                type: "POST",
                data: {
                    action: "wpdeveloper_install_plugin",
                    security: nonce,
                    slug: "essential-blocks",
                },
                success: function (response) {
                    if (response.success) {
                        button.text("Activated");
                        button.data("action", null);
                        $('.eael-gb-eb-dismiss').trigger('click');
                        $('#eael-eb-popup-button').remove();

                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        button.text("Try Essential Blocks");
                    }

                    button.attr("disabled", false);
                },
                error: function (err) {
                    console.log(err.responseJSON);
                },
            });
        } else if (action === "activate" && !$.active) {
            button.text("Activating...").attr("disabled", true);

            $.ajax({
                url: "admin-ajax.php",
                type: "POST",
                data: {
                    action: "wpdeveloper_activate_plugin",
                    security: nonce,
                    basename: "essential-blocks/essential-blocks.php",
                },
                success: function (response) {
                    if (response.success) {
                        button.text("Activated");
                        button.data("action", null);
                        $('.eael-gb-eb-dismiss').trigger('click');
                        $('#eael-eb-popup-button').remove();

                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    } else {
                        button.text("Activate");
                    }

                    button.attr("disabled", false);
                },
                error: function (err) {
                    console.log(err.responseJSON);
                },
            });
        }
    });
})(jQuery);