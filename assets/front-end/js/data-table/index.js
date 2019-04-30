var dataTable = function($scope, $) {
    var $_this = $scope.find(".eael-data-table-wrap"),
        $id = $_this.data("table_id");


    if(typeof enableProSorter !== 'undefined' && $.isFunction(enableProSorter) ) {
        $(document).ready(function(){
            enableProSorter(jQuery, $_this);
        });
    }

    var responsive = $_this.data("custom_responsive");
    if (true == responsive) {
        var $th = $scope.find(".eael-data-table").find("th");
        var $tbody = $scope.find(".eael-data-table").find("tbody");

        $tbody.find("tr").each(function(i, item) {
            $(item)
                .find("td .td-content-wrapper")
                .each(function(index, item) {
                    $(this).prepend(
                        '<div class="th-mobile-screen">' +
                            $th.eq(index).html() +
                            "</div>"
                    );
                });
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-data-table.default",
        dataTable
    );
});