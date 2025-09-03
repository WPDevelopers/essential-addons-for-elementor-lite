let ImageMaskingHandler = function ($scope, $) {
    let $images = $scope.find('img');
    let options = $scope.data('morphing-options');

    if( options?.exclude ){
        let exclude = options.exclude.split(',').map(item => item.trim());
        $images = $images.not(exclude.join(', '));
    }

    // Check if polygon animation is enabled and get settings
    function createClippedSVG(imageSrc, uniqueId, viewBox, pathD) {
        return `
            <svg viewBox="${viewBox}" width="100%" style="visibility: visible; overflow: hidden;">
                <defs>
                    <clipPath id="clip-path-${uniqueId}">
                        <path class="clip-path" d="${pathD}"/>
                    </clipPath>
                </defs>
                <image width="100%" height="100%" clip-path="url(#clip-path-${uniqueId})" href="${imageSrc}"/>
            </svg>
        `;
    }

    if ( $scope.hasClass('eael-morphing-enabled') ) {
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

            if (animationData && typeof PolygonMorphingAnimation !== 'undefined' && $images.length > 0) {
                // Create animation instance for each image individually
                $images.each(function(_, imgElement) {
                    new PolygonMorphingAnimation(imgElement, animationData);
                });
            }
        } else if( 'svg' === options.type ){
            let svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');
            if( !svg_items.length ){
                return;
            }
            
            let viewBox = svg_items.first().attr('viewBox');
            let defaultPath = svg_items.first().find('path').first().attr('d');

            $images.each(function(index, image) {
                image = $(image);
				let image_src = image.attr('src');
                let uniqueId = $scope.data('id') + '-' + index;
				image.hide();
				image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath));
            });

            var morphing = gsap.timeline({
				repeat: options?.loop ? -1 : 0,
				yoyo: options?.loop,
				repeatDelay: 0.001,
				delay: 0.001
			});

            svg_items.each(function(index, element){
                const $svg = $(element);
                const $path = $svg.find('path').first();
                const transform = $path.attr('transform') || "translate(0,0)";
                const clipPath = $scope.find('.clip-path');

                morphing.to(clipPath, {
                    morphSVG: {
                        shape: $path[0]
                    },
                    duration: options?.duration || 6,
                    ease: options?.ease || "sine.inOut",
                    onStart: function() {
                        clipPath.attr('transform', transform);
                    }
                }, "+=0");
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