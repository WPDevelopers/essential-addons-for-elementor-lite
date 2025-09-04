let ImageMaskingHandler = function ($scope, $) {
    let $images = $scope.find('img');
    let options = $scope.data('morphing-options');

    if( options?.exclude ){
        let exclude = options.exclude.split(',').map(item => item.trim());
        $images = $images.not(exclude.join(', '));
    }

    // Check if polygon animation is enabled and get settings
    function createClippedSVG(imageSrc, uniqueId, viewBox, pathD, originalImage) {
        const imgWidth = originalImage.offsetWidth || originalImage.naturalWidth;
        const imgHeight = originalImage.offsetHeight || originalImage.naturalHeight;

        // Parse viewBox to get the coordinate system dimensions
        const viewBoxValues = viewBox.split(' ').map(Number);
        const viewBoxWidth = viewBoxValues[2];
        const viewBoxHeight = viewBoxValues[3];

        return `
            <svg viewBox="${viewBox}" width="${imgWidth}" height="${imgHeight}" style="position: absolute; top: 0; left: 0; visibility: visible; display: block;">
                <defs>
                    <clipPath id="clip-path-${uniqueId}">
                        <path class="clip-path" d="${pathD}"/>
                    </clipPath>
                </defs>
                <image x="0" y="0" width="${viewBoxWidth}" height="${viewBoxHeight}" clip-path="url(#clip-path-${uniqueId})" href="${imageSrc}" preserveAspectRatio="xMidYMid slice"/>
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

                // Wrap image in container for proper positioning
                if (!image.parent().hasClass('eael-image-masking-container')) {
                    image.wrap('<div class="eael-image-masking-container" style="position: relative; display: inline-block; overflow: hidden;"></div>');
                }

                // Hide original image and add SVG
                image.css('visibility', 'hidden');
                image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath, image[0]));
            });

            // Start animation immediately - remove any delays
            var morphing = gsap.timeline({
                repeat: options?.loop ? -1 : 0,
                yoyo: options?.loop,
                repeatDelay: 0,
            });

            svg_items.each(function(index, element){
                const $svg = $(element);
                const $path = $svg.find('path').first();
                const transform = $path.attr('transform') || "translate(0,0)";
                const clipPath = $scope.find('.clip-path');

                // Calculate duration per shape for smooth transitions
                const totalDuration = options?.duration || 6;
                const durationPerShape = totalDuration / svg_items.length;

                // Start first animation immediately, others at calculated intervals
                const startTime = index * durationPerShape;

                morphing.to(clipPath, {
                    morphSVG: {
                        shape: $path[0]
                    },
                    duration: durationPerShape,
                    ease: options?.ease || "sine.inOut",
                    onStart: function() {
                        clipPath.attr('transform', transform);
                    }
                }, startTime);
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