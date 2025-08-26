let ImageMaskingHandler = function ($scope, $) {
    let $img = $scope.find('img');

    // Check if polygon animation is enabled and get settings
    
    if ( $scope.hasClass('eael-morphing-enabled') ) {
        let options = $scope.data('morphing-options');
        
        if( 'clip-path' === options.type ){
            let shapes = atob(options.shapes);
            let animationData = {
                polygonShapes: JSON.parse(shapes)
            };

            if( options.duration ){
                animationData.duration = options.duration;
            }

            if( options.scaleMin ){
                animationData.scale.min = options.scaleMin;
            }

            if( options.scaleMax ){
                animationData.scale.max = options.scaleMax;
            }

            if( options.rotation ){
                animationData.rotation = options.rotation;
            }

            if( options.rotationSpeed ){
                animationData.rotationSpeed = options.rotationSpeed;
            }

            if( options.scaleMin ){
                animationData.scale.min = options.scaleMin;
            }
            if( options.scaleMax ){
                animationData.scale.max = options.scaleMax;
            }

            if (animationData && typeof PolygonMorphingAnimation !== 'undefined' && $img.length > 0) {
                // Create animation instance for each image individually
                $img.each(function(_, imgElement) {
                    new PolygonMorphingAnimation(imgElement, animationData);
                });
            }
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