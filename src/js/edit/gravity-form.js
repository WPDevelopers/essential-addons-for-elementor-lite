jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/eael-gravity-form.default', function( $scope, $ ) {
        var $element = $scope.find( '.gform_wrapper' );
        $element.show();
    } );
});