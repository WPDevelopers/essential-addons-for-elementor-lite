let ImageMaskingHandler = function ($scope, $) {
    let $img = $scope.find('img');

    // Check if blob animation is enabled and get settings
    if ($scope.hasClass('eael-blob-animation-enabled')) {
        let animationData = $scope.data('blob-animation');

        if (animationData && typeof MorphingBlobAnimation !== 'undefined' && $img.length > 0) {
            let imageMasking = new MorphingBlobAnimation($img, animationData);
        }
    }
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelImageMaskingView') || window.isEditMode) {
        return false;
    }
    
    elementorFrontend.hooks.addAction( "frontend/element_ready/widget", ImageMaskingHandler );
    elementorFrontend.hooks.addAction( "frontend/element_ready/container", ImageMaskingHandler );
    elementorFrontend.hooks.addAction( "frontend/element_ready/section", ImageMaskingHandler );
});