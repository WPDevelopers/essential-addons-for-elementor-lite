let ImageMaskingHandler = function ($scope, $) {

    // Global storage for animation instances to prevent duplicates
    window.eaelImageMaskingAnimations = window.eaelImageMaskingAnimations || {};

    function get_clip_path( shape ){
        let shapes = {
            'circle': 'circle(42% at 50% 50%)',
            'ellipse': 'ellipse(50% 35% at 50% 50%)',
            'inset': 'inset(10% 10% 10% 10%)',
            'triangle': 'polygon(50% 0%, 0% 100%, 100% 100%)',
            'trapezoid': 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)',
            'parallelogram': 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)',
            'rhombus': 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
            'pentagon': 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)',
            'hexagon': 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)',
            'heptagon': 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)',
            'octagon': 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',
            'nonagon': 'polygon(50% 0%, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%, 0% 50%, 15% 15%)',
            'decagon': 'polygon(50% 0%, 80% 10%, 100% 40%, 95% 80%, 65% 100%, 35% 100%, 5% 80%, 0% 40%, 20% 10%)',
            'star': 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
            'cross': 'polygon(30% 0%, 70% 0%, 70% 30%, 100% 30%, 100% 70%, 70% 70%, 70% 100%, 30% 100%, 30% 70%, 0% 70%, 0% 30%, 30% 30%)',
            'arrow': 'polygon(0% 40%, 60% 40%, 60% 20%, 100% 50%, 60% 80%, 60% 60%, 0% 60%)',
            'left_arrow': 'polygon(100% 40%, 40% 40%, 40% 20%, 0% 50%, 40% 80%, 40% 60%, 100% 60%)',
            'chevron': 'polygon(25% 0%, 100% 50%, 25% 100%, 0% 75%, 50% 50%, 0% 25%)',
            'message': 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 50% 100%, 50% 75%, 0% 75%)',
            'close': 'polygon(20% 0%, 50% 30%, 80% 0%, 100% 20%, 70% 50%, 100% 80%, 80% 100%, 50% 70%, 20% 100%, 0% 80%, 30% 50%, 0% 20%)',
            'frame': 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)',
            'rabbet': 'polygon(20% 0%, 80% 0%, 80% 20%, 100% 20%, 100% 80%, 80% 80%, 80% 100%, 20% 100%, 20% 80%, 0% 80%, 0% 20%, 20% 20%)',
            'starburst': 'polygon(50% 0%, 60% 20%, 80% 10%, 70% 30%, 90% 50%, 70% 70%, 80% 90%, 60% 80%, 50% 100%, 40% 80%, 20% 90%, 30% 70%, 10% 50%, 30% 30%, 20% 10%, 40% 20%)',
            'blob': 'polygon(50% 0%, 80% 10%, 100% 40%, 90% 70%, 60% 100%, 30% 90%, 10% 60%, 0% 30%, 20% 10%)'
        };
        return shapes[shape] || '';
    }

    // Function to clean up existing animations for an element
    function cleanupExistingAnimations(elementId) {
        // Check if GSAP is available before attempting cleanup
        if (typeof gsap === 'undefined') {
            return;
        }

        if (window.eaelImageMaskingAnimations[elementId]) {
            const existingAnimation = window.eaelImageMaskingAnimations[elementId];

            // Kill existing GSAP timeline
            if (existingAnimation.timeline) {
                existingAnimation.timeline.kill();
            }

            // Remove animation reference
            delete window.eaelImageMaskingAnimations[elementId];
        }

        // Also clean up any orphaned GSAP animations on the element
        const element = $(`.elementor-element-${elementId}`);
        if (element.length) {
            gsap.killTweensOf(element.find('.eael-clip-path'));
            gsap.killTweensOf(element.find('.clip-path'));
        }
    }

    function renderImageMasking (model) {
        let settings = model?.attributes?.settings?.attributes;
        let elementId = model?.attributes?.id, element = $(`.elementor-element-${elementId}`);
        let styleId = 'eael-image-masking-' + elementId;
        $scope = element;

        // Clean up existing animations before creating new ones
        cleanupExistingAnimations(elementId);

        // Remove existing style if present
        $('#' + styleId).remove();

        if( 'yes' === settings?.eael_enable_image_masking ) {
            let style = '';
            if( 'clip' === settings?.eael_image_masking_type ){
                let clipPath = get_clip_path( settings?.eael_image_masking_clip_path );
                if( 'custom' === settings?.eael_image_masking_clip_path ){
                    clipPath = settings?.eael_image_masking_custom_clip_path;
                    clipPath = clipPath.replace( 'clip-path: ', '' );
                }
                if( clipPath ) {
                    style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';
                }

                let hoverClipPath = get_clip_path( settings?.eael_image_masking_clip_path_hover );
                if( 'custom' === settings?.eael_image_masking_clip_path_hover ){
                    hoverClipPath = settings?.eael_image_masking_custom_clip_path_hover;
                    hoverClipPath = hoverClipPath.replace( 'clip-path: ', '' );
                }
                if( hoverClipPath ) {
                    let hoverSelector = settings?.eael_image_masking_hover_selector;
                    if( hoverSelector ){
                        hoverSelector = ' ' + hoverSelector.trim();
                    }
                    style += '.elementor-element-' + elementId + hoverSelector + ':hover img {clip-path: ' + hoverClipPath + ';}';
                }
            } else if( 'image' === settings?.eael_image_masking_type ){
                let image = settings?.eael_image_masking_image;
                if( image?.url ) {
                    style += '.elementor-element-' + elementId + ' img {mask-image: url(' + image.url + '); -webkit-mask-image: url(' + image.url + ');}';
                }

                let hoverImage = settings?.eael_image_masking_image_hover;
                if( hoverImage?.url ) {
                    let hoverSelector = settings?.eael_image_masking_hover_selector;
                    if( hoverSelector ){
                        hoverSelector = ' ' + hoverSelector.trim();
                    }
                    style += '.elementor-element-' + elementId + hoverSelector + ':hover img {mask-image: url(' + hoverImage.url + '); -webkit-mask-image: url(' + hoverImage.url + ');}';
                }
            }  else if( 'morphing' === settings?.eael_image_masking_type ){
                let morphingType = settings?.eael_morphing_type;
                let $images = $(`.elementor-element-${elementId}`).find('img');

                if( settings?.eael_image_morphing_exclude_selectors ){
                    $images = $images.not( settings?.eael_image_morphing_exclude_selectors );
                }
                if( 'clip-path' === morphingType ){
                    let clipPaths = settings?.eael_clip_paths;
                    let paths = [];
                    clipPaths.forEach(function( clipPath ){
                        paths.push( clipPath?.attributes?.eael_clip_path.replace( 'clip-path: ', '' ).replace( ';', '' ).replace( '\n', '' ) );
                    });

                    let animationData = {
                        polygonShapes: paths
                    };
                    if( settings?.eael_image_morphing_duration?.size ){
                        animationData.duration = settings?.eael_image_morphing_duration?.size;
                    }
                    if( settings?.eael_image_morphing_loop ){
                        animationData.loop = 'yes' === settings?.eael_image_morphing_loop;
                    }
                    if( settings?.eael_image_morphing_ease ){
                        animationData.ease = settings?.eael_image_morphing_ease;
                    }
                    if( settings?.eael_image_morphing_rotation ){
                        animationData.rotation = 'yes' === settings?.eael_image_morphing_rotation;
                    }
                    if( settings?.eael_image_morphing_rotation_speed?.size ){
                        animationData.rotationSpeed = settings?.eael_image_morphing_rotation_speed?.size;
                    }

                    if (animationData && typeof PolygonMorphingAnimation !== 'undefined' && $images.length > 0) {
                        // Create animation instance for each image individually
                        $images.each(function(_, imgElement) {
                            new PolygonMorphingAnimation($(imgElement), animationData);
                        });
                    }
                } else if ( 'svg' === settings?.eael_morphing_type ) {
                    let svgPaths = settings?.eael_svg_paths;
                    let svg_html = '<div id="eael-svg-items-' + elementId + '" style="display: none;">';
                    let svg_html_wrapper = $('#eael-svg-items-' + elementId);
                    let duration = settings?.eael_image_morphing_duration?.size || 6;
                    let loop = 'yes' === settings?.eael_image_morphing_loop;

                    svgPaths.forEach(function( svgPath ){
                        svg_html += DOMPurify.sanitize( svgPath?.attributes?.eael_svg_path );
                    });
                    svg_html += '</div>';

                    if( svg_html_wrapper.length > 0 ){
                        svg_html_wrapper.remove();
                    }

                    element.append( svg_html );
                    
                    let svg_items = $('#eael-svg-items-' + elementId).find('svg');
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

                    // Check if GSAP and required plugins are available
                    if (typeof gsap === 'undefined') {
                        return;
                    }

                    // Check if animation already exists for this element
                    if (window.eaelImageMaskingAnimations[elementId]) {
                        return; // Animation already running, prevent duplicate
                    }

                    var morphing = gsap.timeline({
                        repeat: loop ? -1 : 0,
                        yoyo: false,
                        repeatDelay: 0.001,
                        onComplete: function() {
                            // Clean up reference when animation completes (if not infinite)
                            if (!loop) {
                                delete window.eaelImageMaskingAnimations[elementId];
                            }
                        }
                    });

                    // Store animation reference to prevent duplicates
                    window.eaelImageMaskingAnimations[elementId] = {
                        timeline: morphing,
                        type: 'svg-morphing'
                    };

                    svg_items.first().appendTo(svg_items.parent());
                    svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');
                    svg_items.each(function(index, element){
                        const $svg = $(element);
                        const $path = $svg.find('path').first();
                        const transform = $path.attr('transform') || "translate(0,0)";
                        const clipPath = $scope.find('.eael-clip-path');

                        // Calculate duration per shape for smooth transitions
                        const totalDuration = duration || 6;
                        const durationPerShape = totalDuration / svg_items.length;

                        // Start first animation immediately, others at calculated intervals
                        const startTime = index * durationPerShape;

                        morphing.to(clipPath, {
                            morphSVG: {
                                shape: $path[0]
                            },
                            duration: durationPerShape,
                            ease: settings?.eael_image_morphing_ease || "sine.inOut",
                            onStart: function() {
                                clipPath.attr('transform', transform);
                            }
                        }, startTime);
                    });
                }
            }

            if( style ){
                element.append('<style id="' + styleId + '">' + style + '</style>');
            }
        }
    }

    // Check if polygon animation is enabled and get settings
    function createClippedSVG(imageSrc, uniqueId, viewBox, pathD, originalImage) {
        if( $(`#eael-morphing-svg-${uniqueId}`).length ){
            return;
        }

        const imgWidth = originalImage.offsetWidth || originalImage.naturalWidth;
        const imgHeight = originalImage.offsetHeight || originalImage.naturalHeight;

        // Parse viewBox to get the coordinate system dimensions
        const viewBoxValues = viewBox.split(' ').map(Number);
        const viewBoxWidth = viewBoxValues[2];
        const viewBoxHeight = viewBoxValues[3];

        return `
            <svg id="eael-morphing-svg-${uniqueId}" viewBox="${viewBox}" width="${imgWidth}" height="${imgHeight}" style="position: absolute; top: 0; left: 0; visibility: visible; display: block;">
                <defs>
                    <clipPath id="eael-clip-path-${uniqueId}">
                        <path class="eael-clip-path" d="${pathD}"/>
                    </clipPath>
                </defs>
                <image x="0" y="0" width="${viewBoxWidth}" height="${viewBoxHeight}" clip-path="url(#eael-clip-path-${uniqueId})" href="${imageSrc}" preserveAspectRatio="xMidYMid slice"/>
            </svg>
        `;
    }

    function getImageMaskingSettingsVal( models ) {
        $.each(models, function (_, model) {
            renderImageMasking( model );

            if ( model.attributes.elType !== 'widget' ) {
                getImageMaskingSettingsVal( model.attributes.elements.models );
            }
        });
    }

    getImageMaskingSettingsVal( window.elementor.elements.models );
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelImageMaskingEditor')) {
        return false;
    }
    elementorFrontend.hooks.addAction( "frontend/element_ready/widget", ImageMaskingHandler );
    elementorFrontend.hooks.addAction( "frontend/element_ready/container", ImageMaskingHandler );
    elementorFrontend.hooks.addAction( "frontend/element_ready/section", ImageMaskingHandler );
});