var CreativeButton = function ($scope, $) {
    var buttonWrapper = $scope.find(".eael-creative-button-wrapper"),
        button = buttonWrapper.find('.eael-creative-button');

    if ( button.hasClass( 'csvg-use-color' ) ) {
        let svg = button.find('svg');
        svg.removeAttr('fill').find('*').removeAttr('fill');
    }
};

jQuery(window).on("elementor/frontend/init", function () {
    if ( eael.elementStatusCheck('eaelCreativeButton') ) {
        return false;
    }

    elementorFrontend.hooks.addAction("frontend/element_ready/eael-creative-button.default", CreativeButton);
});
