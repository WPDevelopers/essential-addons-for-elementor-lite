var Advanced_Data_Table = function($scope, $) {
    var interval;

    // enable inline edit
    $('td', $scope).on('click', function(e) {
        e.preventDefault();

        $(this).focus();
    });
};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function(panel, model, view) {
    
    if (view.el.querySelector('.ea-advanced-data-table')) {
        view.el.querySelector('.ea-advanced-data-table').addEventListener('DOMSubtreeModified', function(e) {
            panel.el.querySelector('#elementor-panel-saver-button-publish').classList.remove('elementor-disabled');
            panel.el.querySelector('#elementor-panel-saver-button-save-options').classList.remove('elementor-disabled');
        });
    }

    panel.el.querySelector('#elementor-panel-saver-button-publish').addEventListener('click', function() {
        model.setSetting('ea_adv_data_table_static_html', view.el.querySelector('.ea-advanced-data-table').innerHTML);
    });
};

jQuery(window).on('elementor/frontend/init', function() {
    elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', Advanced_Data_Table_Inline_Edit);
    elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', Advanced_Data_Table);
});
