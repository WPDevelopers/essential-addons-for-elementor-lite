let ImageMaskingHandler = function ($scope, $) {
    let $img = $scope.find('img');

    // Check if blob animation is enabled and get settings
    
    if ( $scope.hasClass('eael-morphing-enabled') ) {
        let options = $scope.data('morphing-options');
        let shapes = atob(options.shapes);
        let animationData = {
            blobShapes: JSON.parse(shapes)
        };

        console.log(JSON.parse(shapes));
        

        if (animationData && typeof MorphingBlobAnimation !== 'undefined' && $img.length > 0) {
            // Create animation instance for each image individually
            $img.each(function(_, imgElement) {
                new MorphingBlobAnimation(imgElement, animationData);
            });
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