let ImageMaskingHandler = function ($scope, $) {

    // Global storage for animation instances to prevent duplicates
    window.eaelImageMaskingAnimations = window.eaelImageMaskingAnimations || {};

    function get_clip_path( shape ) {
        let shapes = {
            'bavel' : 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)',
            'rabbet' : 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)',
            'chevron-left' : 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',
            'chevron-right' : 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',
            'star' : 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
        };
        return shapes[shape] || '';
    }

    // Function to clean up existing animations for an element
    function cleanupExistingAnimations( elementId ) {
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
                let clipPath = '';
                if( 'yes' === settings?.eael_image_masking_enable_custom_clip_path ){
                    clipPath = settings?.eael_image_masking_custom_clip_path;
                    clipPath = clipPath.replace( 'clip-path: ', '' );
                } else {
                    clipPath = get_clip_path( settings?.eael_image_masking_clip_path )
                }
                if( clipPath ) {
                    style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';
                }

                if( 'yes' === settings?.eael_image_masking_hover_effect ){
                    let hoverClipPath = '';
                    if( 'yes' === settings?.eael_image_masking_enable_custom_clip_path_hover ){
                        hoverClipPath = settings?.eael_image_masking_custom_clip_path_hover;
                        hoverClipPath = hoverClipPath.replace( 'clip-path: ', '' );
                    } else {
                        hoverClipPath = get_clip_path( settings?.eael_image_masking_clip_path_hover )
                    }
                    if( hoverClipPath ) {
                        let hoverSelector = settings?.eael_image_masking_hover_selector;
                        if( hoverSelector ){
                            hoverSelector = ' ' + hoverSelector.trim();
                        }
                        style += '.elementor-element-' + elementId + hoverSelector + ':hover img {clip-path: ' + hoverClipPath + ';}';
                    }
                }
            } else if( 'image' === settings?.eael_image_masking_type ){
                let svg_url = settings?.eael_image_masking_svg_url;
                let image = settings?.eael_image_masking_svg;
                let mask_url = '';
                if( 'upload' !== image ){
                    mask_url = svg_url + image + '.svg';
                } else if( 'upload' === image ){
                    let image = settings?.eael_image_masking_image;
                    mask_url = image?.url;
                }
                if( mask_url ) {
                    style += '.elementor-element-' + elementId + ' img {mask-image: url(' + mask_url + '); -webkit-mask-image: url(' + mask_url + ');}';
                }
                
                if( 'yes' === settings?.eael_image_masking_hover_effect ){
                    let hoverImage = settings?.eael_image_masking_image_hover;
                    let hover_mask_url = '';
                    if( 'upload' !== hoverImage ){
                        let svg_url = settings?.eael_image_masking_svg_url;
                        hover_mask_url = svg_url + hoverImage + '.svg';
                    } else if( 'upload' === hoverImage ){
                        hover_mask_url = hoverImage?.url;
                    }
                    if( hover_mask_url ) {
                        let hover_selector = settings?.eael_image_masking_hover_selector;
                        if( hover_selector ){
                            hover_selector = ' ' + hover_selector.trim();
                        }
                        style += '.elementor-element-' + elementId + hover_selector + ':hover img {mask-image: url(' + hover_mask_url + '); -webkit-mask-image: url(' + hover_mask_url + ');}';
                    }
                }
            }  else if( 'morphing' === settings?.eael_image_masking_type ){
                let morphingType = settings?.eael_morphing_type;
                let $images = $scope.find('img'); // Use $scope like frontend for proper container support

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
                    // Check if animation already exists for this element - prevent duplicates early
                    if (window.eaelImageMaskingAnimations[elementId]) {
                        return; // Animation already running, prevent duplicate
                    }

                    // Mark element as being processed to prevent race conditions
                    window.eaelImageMaskingAnimations[elementId] = {
                        timeline: null,
                        type: 'svg-morphing',
                        processing: true
                    };

                    let svgPathsType = settings?.eael_svg_paths;
                    if (!svgPathsType) {
                        console.warn('EAEL Image Masking: No SVG paths type found for morphing');
                        return;
                    }

                    let svg_html_wrapper = $('#eael-svg-items-' + elementId);
                    let duration = settings?.eael_image_morphing_duration?.size || 6;
                    let loop = 'yes' === settings?.eael_image_morphing_loop;

                    // Process SVG paths asynchronously
                    let svgPromises = [];
                    let svgContents = [];

                    if ('custom' === svgPathsType) {
                        // Handle custom SVG paths (from uploaded files or custom code)
                        let customSvgPaths = settings?.eael_svg_paths_custom;
                        if (!customSvgPaths || !customSvgPaths.length) {
                            console.warn('EAEL Image Masking: No custom SVG paths found for morphing asfdasfd');
                            return;
                        }

                        customSvgPaths.forEach(function( svgPath, index ){
                            if( 'code' === svgPath?.attributes?.eael_svg_file_type ){
                                // Handle inline SVG code
                                svgContents[index] = DOMPurify.sanitize( svgPath?.attributes?.eael_svg_code );
                                svgPromises.push(Promise.resolve());
                            } else if( 'file' === svgPath?.attributes?.eael_svg_file_type ){
                                // Handle SVG file URL - check if URL exists
                                let svgFileUrl = svgPath?.attributes?.eael_svg_file?.url;
                                if (!svgFileUrl) {
                                    console.warn('EAEL Image Masking: SVG file URL not found for path at index:', index);
                                    svgContents[index] = '';
                                    svgPromises.push(Promise.resolve());
                                    return;
                                }

                                // Fetch SVG file content
                                let fetchPromise = fetch(svgFileUrl)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`HTTP error! status: ${response.status}`);
                                        }
                                        return response.text();
                                    })
                                    .then(data => {
                                        svgContents[index] = DOMPurify.sanitize(data);
                                    })
                                    .catch(error => {
                                        console.error('EAEL Image Masking: Error fetching SVG file:', error);
                                        svgContents[index] = '';
                                    });
                                svgPromises.push(fetchPromise);
                            }
                        });
                    } else {
                        // Handle predefined SVG paths (blob, brush, dimond)
                        // Use the pro plugin URL for morphing assets (matches PHP implementation)
                        let svgPathUrl = settings?.eael_pro_image_masking_svg_url + svgPathsType + '-';

                        for (let i = 1; i <= 5; i++) {
                            let fetchPromise = fetch(svgPathUrl + i + '.svg')
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }
                                    return response.text();
                                })
                                .then(data => {
                                    svgContents[i - 1] = DOMPurify.sanitize(data);
                                })
                                .catch(error => {
                                    console.error('EAEL Image Masking: Error fetching predefined SVG file:', error);
                                    svgContents[i - 1] = '';
                                });
                            svgPromises.push(fetchPromise);
                        }
                    }

                    // Wait for all SVG content to be loaded before proceeding
                    Promise.all(svgPromises).then(() => {
                        // Build the complete SVG HTML
                        let svg_html = '<div id="eael-svg-items-' + elementId + '" style="display: none;">';
                        svgContents.forEach(content => {
                            if (content) {
                                svg_html += content;
                            }
                        });
                        svg_html += '</div>';

                        if( svg_html_wrapper.length > 0 ){
                            svg_html_wrapper.remove();
                        }

                        // Clean up any existing SVG masking elements to prevent accumulation
                        $scope.find('.eael-image-masking-container').each(function() {
                            const $container = $(this);
                            const $img = $container.find('img');
                            const $svg = $container.find('svg');

                            if ($img.length && $svg.length) {
                                // Restore original image visibility and remove SVG
                                $img.css('visibility', 'visible');
                                $svg.remove();
                                // Unwrap the container if it only contains the image
                                if ($container.children().length === 1) {
                                    $img.unwrap();
                                }
                            }
                        });

                        element.append( svg_html );

                        let svg_items = $('#eael-svg-items-' + elementId).find('svg');
                        if( !svg_items.length ){
                            console.warn('EAEL Image Masking: No SVG items found after loading');
                            return;
                        }

                        let viewBox = svg_items.first().attr('viewBox');
                        let defaultPath = svg_items.first().find('path').first().attr('d');
                        let transform = svg_items.first().find('path').first().attr('transform') || "";
                        $images.each(function(index, image) {
                            image = $(image);
                            let image_src = image.attr('src');
                            let uniqueId = elementId + '-' + index;

                            // Wrap image in container for proper positioning
                            if (!image.parent().hasClass('eael-image-masking-container')) {
                                image.wrap('<div class="eael-image-masking-container" style="position: relative; display: inline-block; overflow: hidden;"></div>');
                            }

                            // Hide original image and add SVG
                            image.css('visibility', 'hidden');
                            image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath, image[0], transform));
                        });

                        // Check if GSAP and required plugins are available
                        if (typeof gsap === 'undefined') {
                            console.warn('EAEL Image Masking: GSAP is not available for morphing animation');
                            return;
                        }

                        // Check if morphSVG plugin is available
                        if (typeof gsap.plugins === 'undefined' || typeof gsap.plugins.morphSVG === 'undefined') {
                            console.warn('EAEL Image Masking: GSAP morphSVG plugin is not available for animation');
                            return;
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

                        // Update animation reference with actual timeline
                        window.eaelImageMaskingAnimations[elementId] = {
                            timeline: morphing,
                            type: 'svg-morphing',
                            processing: false
                        };

                        svg_items.first().appendTo(svg_items.parent());
                        svg_items = $('#eael-svg-items-' + elementId).find('svg');

                        // Start animation immediately like frontend - no delays
                        svg_items.each(function(index, element){
                            const $svg = $(element);
                            const $path = $svg.find('path').first();

                            // Check if path element exists
                            if ($path.length === 0) {
                                console.warn('EAEL Image Masking: path element not found in SVG at index:', index);
                                return; // Skip this iteration
                            }

                            const transform = $path.attr('transform') || "translate(0,0)";
                            const clipPath = $scope.find('.clip-path'); // Use $scope for proper container support

                            if (clipPath.length === 0) {
                                console.warn('EAEL Image Masking: No clip-path elements found for animation');
                                return;
                            }

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
                    }).catch(error => {
                        console.error('EAEL Image Masking: Error loading SVG content:', error);
                        // Clean up animation reference on error
                        delete window.eaelImageMaskingAnimations[elementId];
                    });
                }
            }
            
            if( style ){
                element.append('<style id="' + styleId + '">' + style + '</style>');
            }
        }
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
        const viewBoxWidth = viewBoxValues[2];
        const viewBoxHeight = viewBoxValues[3];

        // Handle transform attribute if provided
        const transformAttr = transform ? `transform="${transform}"` : '';

        return `
            <svg id="eael-morphing-svg-${uniqueId}" viewBox="${viewBox}" width="${imgWidth}" height="${imgHeight}" style="position: absolute; top: 0; left: 0; visibility: visible; display: block;">
                <defs>
                    <clipPath id="clip-path-${uniqueId}">
                        <path class="clip-path" d="${pathD}" ${transformAttr}/>
                    </clipPath>
                </defs>
                <image x="0" y="0" width="${viewBoxWidth}" height="${viewBoxHeight}" clip-path="url(#clip-path-${uniqueId})" href="${imageSrc}" preserveAspectRatio="xMidYMid slice"/>
            </svg>
        `;
    }

    function getImageMaskingSettingsVal( models ) {
        $.each(models, function (_, model) {
            // Only process if image masking is enabled for this element
            let settings = model?.attributes?.settings?.attributes;
            if (settings && 'yes' === settings?.eael_enable_image_masking) {
                renderImageMasking( model );
            }

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
});