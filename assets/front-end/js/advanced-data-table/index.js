var Advanced_Data_Table = function($scope, $) {
    var interval;

    // enable inline edit
    $('td', $scope).on('click', function(e) {
        e.preventDefault();

        $(this).focus();
    });

    // update static table
    $('.ea-advanced-data-table', $scope).on('DOMSubtreeModified', function() {
        clearTimeout(interval);

        interval = setTimeout(function() {
            // let canvasDocument = iframe.contentDocument || iframe.contentWindow;

            // console.log(parent.querySelector('[data-setting="ea_adv_data_table_static_html"]'));
            console.log(jQuery(window));
        }, 250);
    });
};

jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', Advanced_Data_Table);

    //     elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', function(panel, model, view) {
    //         // model.addChangeCallback('ea_adv_data_table_static_num_row', function(val) {
    //         //     console.log(val);
    //         // });
    //         model.setSetting('ea_adv_data_table_static_html', 'dsakfj;slkdfj;l');
    //     });
});
