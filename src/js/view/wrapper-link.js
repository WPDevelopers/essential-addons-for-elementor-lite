let EaelWrapperLink = function ($scope, $) {
    if ($scope.data("eael-wrapper-link") !== undefined) {
        let anchorLink = $scope.prev('.--eael-wrapper-link-tag');

        anchorLink.appendTo($scope).css({
            background: 'transparent',
            border: 'none',
            position: 'absolute',
            height:'100%',
            width: '100%',
            zIndex: '9999',
            top: 0,
            left: 0
        });
    }
};

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', EaelWrapperLink);
});