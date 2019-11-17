var Advanced_Data_Table = function($scope, $) {};

// var Advanced_Data_Table_Gen = function($args) {
//     console.log($args);
// };

jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/advanced-data-table.default', Advanced_Data_Table);
    // elementor.channels.editor.on('ea:atab:gen', Advanced_Data_Table_Gen);
});
