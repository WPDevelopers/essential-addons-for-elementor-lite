let EaelWrapperLink = function ($scope, $) {
    if ($scope.data("eael-wrapper-link") !== undefined) {
        let wrapperData = $scope.data("eael-wrapper-link"),
            url = wrapperData.url,
            target = wrapperData.is_external,
            nofollow = wrapperData.nofollow;

        console.log('URL:'+url, 'Target:'+target, 'NoFollow:'+nofollow);
    }
};
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', EaelWrapperLink);
});