var dataTable = function($scope, $) {
    var $_this = $scope.find('.eael-data-table-wrap'),
        $id = $_this.data('table_id');

    if (typeof enableProSorter !== 'undefined' && $.isFunction(enableProSorter)) {
        $(document).ready(function() {
            enableProSorter(jQuery, $_this);
        });
    }

    var responsive = $_this.data('custom_responsive');
    if (true == responsive) {
        var $th = $scope.find('.eael-data-table').find('th');
        var $tbody = $scope.find('.eael-data-table').find('tbody');

        $tbody.find('tr').each(function(i, item) {
            $(item)
                .find('td .td-content-wrapper')
                .each(function(index, item) {
                    $(this).prepend('<div class="th-mobile-screen">' + $th.eq(index).html() + '</div>');
                });
        });
    }
};

var data_table_context_meu = function(groups, element) {
    if (element.options.model.attributes.widgetType == 'eael-data-table') {
        groups.push({
            name: 'ea_data_table',
            actions: [
                {
                    name: 'export_csv',
                    title: 'Export as CSV',
                    callback: function() {
                        var table = document.querySelector('#eael-data-table-' + element.options.model.attributes.id);
                        var rows = table.querySelectorAll('table tr');
                        var csv = [];

                        // generate csv
                        for (var i = 0; i < rows.length; i++) {
                            var row = [];
                            var cols = rows[i].querySelectorAll('th, td');

                            for (var j = 0; j < cols.length; j++) {
                                row.push(JSON.stringify(cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').trim()));
                            }

                            csv.push(row.join(','));
                        }

                        // download
                        var csv_file = new Blob([csv.join('\n')], { type: 'text/csv' });
                        var download_link = parent.document.createElement('a');

                        download_link.classList.add('eael-data-table-download-' + element.options.model.attributes.id);
                        download_link.download = 'eael-data-table-' + element.options.model.attributes.id + '.csv';
                        download_link.href = window.URL.createObjectURL(csv_file);
                        download_link.style.display = 'none';
                        parent.document.body.appendChild(download_link);
                        download_link.click();

                        parent.document.querySelector('.eael-data-table-download-' + element.options.model.attributes.id).remove();
                    }
                }
            ]
        });
    }

    return groups;
};

jQuery(window).on('elementor/frontend/init', function() {
    // export table
    if (isEditMode) {
        elementor.hooks.addFilter('elements/widget/contextMenuGroups', data_table_context_meu);
    }

    elementorFrontend.hooks.addAction('frontend/element_ready/eael-data-table.default', dataTable);
});
