let EaelWrapperLink = function ($scope, $) {
    if ($scope.data("eael-wrapper-link") !== undefined) {
        if( $scope.hasClass( 'eael-non-traditional-link' ) ){
            let wrapperData = $scope.data("eael-wrapper-link"),
            target = wrapperData.is_external === 'on' ? '_blank' : '_self';
            
            $scope.css( 'cursor', 'pointer' );
            $scope.on('click', function () {
                let anchor = document.createElement('a');

                anchor.href = ea.sanitizeURL(wrapperData.url);
                anchor.target = target;

                if (wrapperData.nofollow === 'on') {
                    anchor.rel = 'nofollow';
                }

                anchor.click();
            });
        } else {
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
    }
};

jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', EaelWrapperLink);
});