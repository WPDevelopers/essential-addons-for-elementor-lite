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

        $this.addClass('active').siblings().removeClass('active');
        $('.eael-gb-eb-popup .eael-gb-eb-content-image').replaceWith($(page_content).find('.eael-gb-eb-content-image')[0].outerHTML);
    });
})(jQuery);