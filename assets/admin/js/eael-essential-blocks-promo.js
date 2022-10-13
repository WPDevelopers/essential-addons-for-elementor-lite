(function ($) {
    let $gb_editor_panel = $('#editor');

    wp.data.subscribe(function () {
        setTimeout(function () {
            essential_block_button_init();
        }, 1);
    });

    function essential_block_button_init() {
        if (!$('#eael-eb-button').length) {
            $gb_editor_panel.find('.edit-post-header__settings').prepend($('#eael-gb-eb-button-template').html());
        }
    }

    $(document).on('click', '#eael-eb-button', function () {
        $('body').append($('#eael-gb-eb-popup-template').html());
    }).on('click', '.eael-gb-eb-dismiss', function () {
        $('.eael-gb-eb-popup').remove();
    }).on('click', '.eael-gb-eb-content-pagination span', function () {
        let $this = $(this),
            page_id = $this.data('page'),
            page_content = $(`#eael-gb-eb-button-template-page-${page_id}`).html();

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
    }).on('click', '.eael-gb-eb-never-show', function () {
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
                    $('.eael-gb-eb-popup').remove();
                    $('#eael-eb-button').remove();
                } else {
                    console.log(response.data);
                }
            },
            error: function (err) {
                console.log(err.responseText);
            },
        });
    });
})(jQuery);