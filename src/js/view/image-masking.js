let ImageMaskingHandler = function ($scope, $) {
    let $images = $scope.find('img');
    let options = $scope.data('morphing-options');

    if( options?.exclude ){
        let exclude = options.exclude.split(',').map(item => item.trim());
        $images = $images.not(exclude.join(', '));
    }

    // Check if polygon animation is enabled and get settings
    function createClippedSVG(imageSrc, uniqueId, viewBox, pathD, originalImage, transform) {
        if( $(`#eael-morphing-svg-${uniqueId}`).length ){
            return;
        }

        const imgWidth = originalImage.offsetWidth || originalImage.naturalWidth;
        const imgHeight = originalImage.offsetHeight || originalImage.naturalHeight;

        // Parse viewBox to get the coordinate system dimensions
        const viewBoxValues = viewBox.split(' ').map(Number);
        const viewBoxX = viewBoxValues[0] || 0;
        const viewBoxY = viewBoxValues[1] || 0;
        const viewBoxWidth = viewBoxValues[2];
        const viewBoxHeight = viewBoxValues[3];

        // Handle transform attribute if provided
        const transformAttr = transform ? `transform="${transform}"` : '';

        // Calculate proper scaling to ensure the mask shape is never cropped
        const imageAspectRatio = imgWidth / imgHeight;
        const viewBoxAspectRatio = viewBoxWidth / viewBoxHeight;

        // Always scale the image to cover the entire viewBox area
        // This ensures the mask shape (which is defined in viewBox coordinates) is never cropped
        let imageDisplayWidth, imageDisplayHeight, imageX, imageY;

        if (imageAspectRatio > viewBoxAspectRatio) {
            // Image is wider than viewBox - scale to cover full viewBox width
            imageDisplayWidth = viewBoxWidth;
            imageDisplayHeight = viewBoxWidth / imageAspectRatio;
            imageX = viewBoxX;
            imageY = viewBoxY + (viewBoxHeight - imageDisplayHeight) / 2;
        } else {
            // Image is taller than viewBox - scale to cover full viewBox height
            imageDisplayHeight = viewBoxHeight;
            imageDisplayWidth = viewBoxHeight * imageAspectRatio;
            imageX = viewBoxX + (viewBoxWidth - imageDisplayWidth) / 2;
            imageY = viewBoxY;
        }

        // Ensure image covers the entire viewBox to prevent mask cropping
        if (imageDisplayWidth < viewBoxWidth || imageDisplayHeight < viewBoxHeight) {
            // Scale up to ensure full coverage
            const scaleX = viewBoxWidth / imageDisplayWidth;
            const scaleY = viewBoxHeight / imageDisplayHeight;
            const scale = Math.max(scaleX, scaleY);

            imageDisplayWidth *= scale;
            imageDisplayHeight *= scale;
            imageX = viewBoxX + (viewBoxWidth - imageDisplayWidth) / 2;
            imageY = viewBoxY + (viewBoxHeight - imageDisplayHeight) / 2;
        }

        return `
            <svg id="eael-morphing-svg-${uniqueId}" viewBox="${viewBox}" width="${imgWidth}" height="${imgHeight}" style="position: absolute; top: 0; left: 0; visibility: visible; display: block;">
                <defs>
                    <clipPath id="clip-path-${uniqueId}" clipPathUnits="userSpaceOnUse" style="transform-box: view-box; transform-origin: center;">
                        <path class="clip-path" d="${pathD}" ${transformAttr}/>
                    </clipPath>
                </defs>
                <image x="${imageX}" y="${imageY}" width="${imageDisplayWidth}" height="${imageDisplayHeight}" clip-path="url(#clip-path-${uniqueId})" href="${imageSrc}" preserveAspectRatio="none"/>
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

            if( options.rotation ){
                animationData.rotation = options.rotation;
            }

            if( options.rotationSpeed ){
                animationData.rotationSpeed = options.rotationSpeed;
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

                // Get initial transform from the first SVG path
                const initialTransform = svg_items.first().find('path').first().attr('transform') || '';
                image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath, image[0], initialTransform));
            });

            // Initialize proper centering for all clip paths
            const clipPaths = $scope.find('.clip-path');

            // Add CSS for proper centering - inject styles directly
            if (!$('#eael-morphing-center-fix').length) {
                $('head').append(`
                    <style id="eael-morphing-center-fix">
                        .eael-morphing-enabled clipPath,
                        .eael-morphing-enabled .clip-path {
                            transform-origin: center !important;
                            transform-box: view-box !important;
                        }
                        .eael-morphing-enabled svg {
                            overflow: visible !important;
                        }
                    </style>
                `);
            }

            // Set initial transform origin for all clip paths
            clipPaths.css({
                'transform-origin': 'center !important',
                'transform-box': 'view-box !important'
            });

            // Start animation immediately - remove any delays
            var morphing = gsap.timeline({
                repeat: options?.loop ? -1 : 0,
                yoyo: false,
                repeatDelay: 0.001,
            });

            svg_items.first().appendTo(svg_items.parent());
            svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');

            svg_items.each(function(index, element){
                const $svg = $(element);
                const $path = $svg.find('path').first();
                const transform = $path.attr('transform') || "";
                const clipPath = $scope.find('.clip-path');

                // Calculate duration per shape for smooth transitions
                const totalDuration = options?.duration || 6;
                const durationPerShape = totalDuration / svg_items.length;

                // Start first animation immediately, others at calculated intervals
                const startTime = index * durationPerShape;

                // Check if morphSVG is available, otherwise fallback to basic animation
                if (typeof gsap.plugins.morphSVG !== 'undefined') {
                    morphing.to(clipPath, {
                        morphSVG: {
                            shape: $path[0]
                        },
                        duration: durationPerShape,
                        ease: options?.ease || "sine.inOut",
                        transformOrigin: "center center",
                        onStart: function() {
                            // Apply any custom transform
                            if (transform) {
                                clipPath.attr('transform', transform);
                            }
                        }
                    }, startTime);
                } else {
                    // Fallback to basic path morphing
                    morphing.to(clipPath, {
                        attr: {
                            d: $path.attr('d')
                        },
                        duration: durationPerShape,
                        ease: options?.ease || "sine.inOut",
                        onStart: function() {
                            // Apply any custom transform
                            if (transform) {
                                clipPath.attr('transform', transform);
                            }
                        }
                    }, startTime);
                }
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