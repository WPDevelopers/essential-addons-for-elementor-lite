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

        if (window.eaelImageMaskingAnimations?.[elementId]) {
            const existingAnimation = window.eaelImageMaskingAnimations[elementId];

            // Kill existing GSAP timeline(s)
            if (existingAnimation?.timeline) {
                // Handle single timeline (old format)
                existingAnimation.timeline.kill();
            } else if (existingAnimation?.timelines) {
                // Handle multiple timelines (new format)
                existingAnimation.timelines.forEach(timeline => {
                    if (timeline) {
                        timeline.kill();
                    }
                });
            }

            // Remove animation reference
            delete window.eaelImageMaskingAnimations[elementId];
        }

        // Also clean up any orphaned GSAP animations on the element
        const element = $(`.elementor-element-${elementId}`);
        if (element?.length) {
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
                let image = settings?.eael_image_masking_svg;
                let mask_url = '';
                if( 'upload' !== image ){
                    mask_url = EAELImageMaskingConfig?.svg_dir_url + image + '.svg';
                } else if( 'upload' === image ){
                    let image = settings?.eael_image_masking_image;
                    mask_url = image?.url;
                }
                if( mask_url ) {
                    style += '.elementor-element-' + elementId + ' img {mask-image: url(' + mask_url + '); -webkit-mask-image: url(' + mask_url + ');}';
                }
                
                if( 'yes' === settings?.eael_image_masking_hover_effect ){
                    let hover_mask_url = '';
                    let hoverImage = settings?.eael_image_masking_svg_hover;
                    if( 'upload' !== hoverImage ){
                        hover_mask_url = EAELImageMaskingConfig?.svg_dir_url + hoverImage + '.svg';
                    } else if( 'upload' === hoverImage ){
                        hover_mask_url = settings?.eael_image_masking_image_hover?.url;
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
                // Verify masking is enabled before applying effects
                if ('yes' !== settings?.eael_enable_image_masking) {
                    // console.warn('EAEL Image Masking: Masking is not enabled for element', elementId);
                    return;
                }

                let morphingType = settings?.eael_morphing_type;
                let $images = $(`.elementor-element-${elementId} img`); // Target only images within this specific element
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
                    // Verify SVG morphing is enabled before applying effects
                    if ('yes' !== settings?.eael_enable_image_masking || 'morphing' !== settings?.eael_image_masking_type) {
                        // console.warn('EAEL Image Masking: SVG morphing is not properly enabled for element', elementId);
                        return;
                    }

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
                        // console.warn('EAEL Image Masking: No SVG paths type found for morphing');
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
                            // console.warn('EAEL Image Masking: No custom SVG paths found for morphing asfdasfd');
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
                                    // console.warn('EAEL Image Masking: SVG file URL not found for path at index:', index);
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
                                        // console.error('EAEL Image Masking: Error fetching SVG file:', error);
                                        svgContents[index] = '';
                                    });
                                svgPromises.push(fetchPromise);
                            }
                        });
                    } else {
                        // Handle predefined SVG paths (blob, brush, dimond)
                        // Use the pro plugin URL for morphing assets (matches PHP implementation)
                        let svgPathUrl = (typeof eaelImageMaskingConfig !== 'undefined' && eaelImageMaskingConfig.svgDirUrl)
                            ? eaelImageMaskingConfig.svgDirUrl + svgPathsType + '-'
                            : settings?.eael_pro_image_masking_svg_url + svgPathsType + '-';

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
                                    // console.error('EAEL Image Masking: Error fetching predefined SVG file:', error);
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
                            // console.warn('EAEL Image Masking: No SVG items found after loading');
                            return;
                        }

                        let viewBox = svg_items.first()?.attr('viewBox');
                        let defaultPath = svg_items.first()?.find('path').first()?.attr('d');
                        let transform = svg_items.first()?.find('path').first()?.attr('transform') || "";

                        if (!viewBox || !defaultPath) {
                            // console.warn('EAEL Image Masking: Missing viewBox or defaultPath from SVG');
                            return;
                        }

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

                        $images.each(function(index, image) {
                            image = $(image);
                            let image_src = image.attr('src');
                            let uniqueId = elementId + '-' + index;

                            // Wrap image in container for proper positioning
                            if (!image.parent()?.hasClass('eael-image-masking-container')) {
                                image.wrap('<div class="eael-image-masking-container eael-morphing-enabled" style="position: relative; display: inline-block; overflow: hidden;"></div>');
                            }

                            // Hide original image and add SVG
                            image.css('visibility', 'hidden');
                            const initialTransform = svg_items.first().find('path').first().attr('transform') || '';
                            image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath, image[0], initialTransform));
                        });

                        // Check if GSAP and required plugins are available
                        if (typeof gsap === 'undefined') {
                            // console.warn('EAEL Image Masking: GSAP is not available for morphing animation');
                            return;
                        }

                        // Check if morphSVG plugin is available
                        if (typeof gsap.plugins === 'undefined' || typeof gsap.plugins.morphSVG === 'undefined') {
                            // console.warn('EAEL Image Masking: GSAP morphSVG plugin is not available for animation');
                            return;
                        }

                        // Initialize proper centering for all clip paths
                        const clipPaths = $scope.find('.clip-path');

                        // Set initial transform origin for all clip paths
                        clipPaths.css({
                            'transform-origin': 'center !important',
                            'transform-box': 'view-box !important'
                        });

                        // Store all timelines for cleanup
                        var imageTimelines = [];

                        svg_items.first().appendTo(svg_items.parent());
                        svg_items = $('#eael-svg-items-' + elementId).find('svg');

                        // Create separate timeline for each image to ensure independent animations
                        $images.each(function(imageIndex, imageElement) {
                            const imageId = elementId + '-' + imageIndex;
                            // Use global selector instead of $scope to ensure we find the clip-path element
                            const imageClipPath = $(`#clip-path-${imageId} .clip-path`);

                            if (!imageClipPath || imageClipPath.length === 0) {
                                // console.warn(`EAEL Image Masking: No clip-path element found for image ${imageIndex} with ID clip-path-${imageId}`);
                                return;
                            }

                            // console.log(`Found clip-path element for image ${imageIndex}:`, imageClipPath);

                            // Calculate offset for this image to prevent timeline conflicts
                            const imageOffset = imageIndex * 0.1; // Small offset between images

                            // Create independent timeline for this specific image with offset
                            var imageTimeline = gsap.timeline({
                                repeat: loop ? -1 : 0,
                                yoyo: false,
                                repeatDelay: 0.001,
                                delay: imageOffset, // Add delay to prevent conflicts
                                onComplete: function() {
                                    // console.log(`Animation completed for image ${imageIndex}`);
                                }
                            });

                            // Store timeline reference
                            imageTimelines.push(imageTimeline);

                            // Start animation immediately like frontend - no delays
                            svg_items.each(function(shapeIndex, element){
                                const $svg = $(element);
                                const $path = $svg?.find('path')?.first();

                                // Check if path element exists
                                if (!$path || $path.length === 0) {
                                    // console.warn('EAEL Image Masking: path element not found in SVG at index:', shapeIndex);
                                    return; // Skip this iteration
                                }

                                const transform = $path.attr('transform') || "translate(0,0)";

                                // Calculate duration per shape for smooth transitions
                                const totalDuration = duration || 6;
                                const durationPerShape = svg_items?.length ? totalDuration / svg_items.length : 1;

                                // Start first animation immediately, others at calculated intervals
                                const startTime = shapeIndex * durationPerShape;

                                // Check if morphSVG is available, otherwise fallback to basic animation
                                if (typeof gsap.plugins.morphSVG !== 'undefined') {
                                    imageTimeline.to(imageClipPath, {
                                        morphSVG: {
                                            shape: $path[0]
                                        },
                                        duration: durationPerShape,
                                        ease: settings?.eael_image_morphing_ease || "sine.inOut",
                                        transformOrigin: "center center",
                                        onStart: function() {
                                            // Apply any custom transform
                                            if (transform) {
                                                imageClipPath.attr('transform', transform);
                                            }
                                        }
                                    }, startTime);
                                } else {
                                    // Fallback to basic path morphing
                                    imageTimeline.to(imageClipPath, {
                                        attr: {
                                            d: $path.attr('d')
                                        },
                                        duration: durationPerShape,
                                        ease: settings?.eael_image_morphing_ease || "sine.inOut",
                                        onStart: function() {
                                            // Apply any custom transform
                                            if (transform) {
                                                imageClipPath.attr('transform', transform);
                                            }
                                        }
                                    }, startTime);
                                }
                            });
                        });

                        // Update animation reference with all timelines for cleanup
                        window.eaelImageMaskingAnimations[elementId] = {
                            timelines: imageTimelines,
                            type: 'svg-morphing',
                            processing: false
                        };
                    }).catch(error => {
                        // console.error('EAEL Image Masking: Error loading SVG content:', error);
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
        let imageDisplayWidth, imageDisplayHeight, imageX, imageY;

        if (imageAspectRatio > viewBoxAspectRatio) {
            imageDisplayWidth = viewBoxWidth;
            imageDisplayHeight = viewBoxWidth / imageAspectRatio;
            imageX = viewBoxX;
            imageY = viewBoxY + (viewBoxHeight - imageDisplayHeight) / 2;
        } else {
            imageDisplayHeight = viewBoxHeight;
            imageDisplayWidth = viewBoxHeight * imageAspectRatio;
            imageX = viewBoxX + (viewBoxWidth - imageDisplayWidth) / 2;
            imageY = viewBoxY;
        }

        // Ensure image covers the entire viewBox to prevent mask cropping
        if (imageDisplayWidth < viewBoxWidth || imageDisplayHeight < viewBoxHeight) {
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

    if( window.elementor?.elements?.models ){
        getImageMaskingSettingsVal( window.elementor?.elements?.models );
    }
}

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelImageMaskingEditor')) {
        return false;
    }
    elementorFrontend.hooks.addAction( "frontend/element_ready/widget", ImageMaskingHandler );
});