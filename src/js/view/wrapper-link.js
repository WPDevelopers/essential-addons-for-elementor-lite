let EaelWrapperLink = function ($scope, $) {
    if ($scope.data("eael-wrapper-link") !== undefined) {
        let wrapperData = $scope.data("eael-wrapper-link"),
            target = wrapperData.is_external === 'on' ? '_blank' : '_self';

        $scope.on('click', function () {
            let anchor = document.createElement('a');

            anchor.href = wrapperData.url;
            anchor.target = target;

            if (wrapperData.nofollow === 'on') {
                anchor.rel = 'nofollow';
            }

            anchor.click();
        });
    }
};

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', EaelWrapperLink);
});