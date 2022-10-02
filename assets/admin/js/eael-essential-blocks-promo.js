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
    });
})(jQuery);