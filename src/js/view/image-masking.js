let ImageMaskingHandler = function ($scope, $) {
    let $images = $scope.find('img');

    // Check if polygon animation is enabled and get settings
    function createClippedSVG(imageSrc, uniqueId, viewBox, pathD) {
        return `
            <svg viewBox="${viewBox}" width="100%" style="visibility: visible;">
                <defs>
                    <clipPath id="clip-path-${uniqueId}">
                        <path class="clip-path" d="${pathD}" transform="translate(100, 100)"/>
                    </clipPath>
                </defs>
                <image width="100%" height="100%" clip-path="url(#clip-path-${uniqueId})" href="${imageSrc}"/>
            </svg>
        `;
    }

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
				repeat: -1,
				yoyo: true,
				repeatDelay: 0.001,
				delay: 0.001
			});
			let ease = "power2.inOut";
			let gap = "-=2";

            function clipPathTransform( transform, svgElement ){
                let $clip = svgElement.find('path');
                let oldTransform = $clip.attr('transform') || "translate(0,0)";
                let newTransform = transform;

                function parseTransform(str) {
                    let t = /translate\(([^,]+)[ ,]+([^,]+)\)/.exec(str);
                    return { x: t ? parseFloat(t[1]) : 0, y: t ? parseFloat(t[2]) : 0 };
                }

                let from = parseTransform(oldTransform);
                let to   = parseTransform(newTransform);

                gsap.fromTo($clip, 
                    { attr: { transform: `translate(${from.x}, ${from.y})` } },
                    { attr: { transform: `translate(${to.x}, ${to.y})` }, duration: 6, ease: "sine.inOut" }
                );
            }

            svg_items.each(function(index, element){
                const $svg = $(element);
                const $path = $svg.find('path').first();
                const transform = $path.attr('transform') || "translate(0, 0)";
                const clipPath = $scope.find('.clip-path');

                morphing.to(clipPath, {
                    morphSVG: {
                        shape: $path[0]
                    },
                    duration: 6,
                    ease: ease,
                    onStart: function() { clipPathTransform(transform, element) }
                }, gap);
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