var Advanced_Data_Table = function ($scope, $) {
    if (isEditMode) {
        var table = $scope.context.querySelector('.ea-advanced-data-table');

        // add edit class
        table.classList.add('ea-advanced-data-table-editable')

        // insert editable area
        table.querySelectorAll('th, td').forEach(function (el) {
            var value = el.innerHTML;

            if (value.indexOf('<textarea rows="1">') !== 0) {
                el.innerHTML = '<textarea rows="1">' + value + '</textarea>';
            }
        });
    }
};

// Inline edit
var Advanced_Data_Table_Inline_Edit = function (panel, model, view) {
    setTimeout(function () {
        if (view.el.querySelector('.ea-advanced-data-table')) {
            var interval;
            var table = view.el.querySelector('.ea-advanced-data-table');

            // save input on edit
            table.querySelectorAll('textarea').forEach(function (el) {
                el.addEventListener('focusout', function (e) {
                    clearTimeout(interval);

                    // clone current table
                    var origTable = table.cloneNode(true);

                    // remove editable area
                    origTable.querySelectorAll('th, td').forEach(function (el) {
                        var value = el.querySelector('textarea').value;
                        el.innerHTML = value;
                    });

                    // disable elementor remote server render
                    model.remoteRender = false;

                    // update backbone model
                    model.setSetting('ea_adv_data_table_static_html', origTable.innerHTML);

                    // enable elementor remote server render just after elementor throttle
                    // ignore multiple assign
                    interval = setTimeout(function () {
                        model.remoteRender = true;
                    }, 1001);
                });
            });
        }
    }, 300);
};


Advanced_Data_Table_Context_Menu = function (groups, element) {
    if (element.options.model.attributes.widgetType == 'eael-advanced-data-table') {
        groups.push({
            name: 'ea_advanced_data_table',
            actions: [
                {
                    name: 'add_row',
                    title: 'Add Row',
                    callback: function () {
                        var table = document.querySelector('.ea-advanced-data-table-' + element.options.model.attributes.id);
                        var row = table.insertRow(table.rows.length);

                        for (var i = 0; i < table.rows[0].cells.length; i++) {
                            var cell = row.insertCell(i);
                            cell.innerHTML = '<textarea rows="1"></textarea>';
                        }
                    }
                },
                {
                    name: 'add_column',
                    title: 'Add Column',
                    callback: function () {
                        var table = document.querySelector('.ea-advanced-data-table-' + element.options.model.attributes.id);

                        for (var i = 0; i < table.rows.length; i++) {
                            var cell = table.rows[i].insertCell(table.rows[i].cells.length - 1);
                            cell.innerHTML = '<textarea rows="1"></textarea>';
                        }
                    }
                }
            ]
        });
    }

    return groups;
}

jQuery(window).on('elementor/frontend/init', function () {
    if (isEditMode) {
        elementor.hooks.addFilter('elements/widget/contextMenuGroups', Advanced_Data_Table_Context_Menu);
        elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', Advanced_Data_Table_Inline_Edit);
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', Advanced_Data_Table);
});
