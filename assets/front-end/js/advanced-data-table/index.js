var Advanced_Data_Table = function($scope, $) {};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function(panel, model, view) {
    setTimeout(function() {
        var table = view.el.querySelector('.ea-advanced-data-table').querySelector('table');

        // inline edit
        table.addEventListener('click', function(e) {
            if (e.target.tagName.toLowerCase() == 'th' || e.target.tagName.toLowerCase() == 'td') {
                e.target.focus();
            }
        });

        // update table
        table.querySelectorAll('th, td').forEach(function(el) {
            el.addEventListener('focusout', function(e) {
                model.remoteRender = false;
                model.setSetting('ea_adv_data_table_static_html', table.innerHTML);

                setTimeout(function() {
                    model.remoteRender = true;
                }, 1001);
            });
        });
    }, 500);
};

jQuery(window).on('elementor/frontend/init', function() {
    if (isEditMode) {
        elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', Advanced_Data_Table_Inline_Edit);
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', Advanced_Data_Table);
});
