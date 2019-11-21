var Advanced_Data_Table = function($scope, $) {};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function(panel, model, view) {
    if (view.el.querySelector('.ea-advanced-data-table')) {
        // enable inline edit
        // view.el.querySelector('.ea-advanced-data-table').addEventListener('click', function(e) {
        //     if (e.target.tagName.toLowerCase() == 'th' || e.target.tagName.toLowerCase() == 'td') {
        //         e.target.focus();
        //     }
        // });

        view.el
            .querySelector('.ea-advanced-data-table')
            .querySelectorAll('th, td')
            .forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.target.focus();
                });
                el.addEventListener('keyup', function(e) {
                    model.remoteRender = false;
                    model.setSetting('ea_adv_data_table_static_html', this.innerHTML);
                    model.remoteRender = true;
                });
            });

        // if (e.code === 'KeyZ' && e.ctrlKey === true) {
        //     var row = e.target.parentNode.rowIndex;
        //     var cell = e.target.cellIndex;

        //     this.innerHTML = model.getSetting('ea_adv_data_table_static_html');
        //     this.querySelector('table').rows[row].cells[cell].focus();
        // } else {
        //     model.setSetting('ea_adv_data_table_static_html', this.innerHTML);
        // }
    }
};

jQuery(window).on('elementor/frontend/init', function() {
    if (isEditMode) {
        elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', Advanced_Data_Table_Inline_Edit);
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', Advanced_Data_Table);
});
