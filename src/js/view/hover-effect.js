let HoverEffectHandler = function ($scope, $) {
    let $eaelRotateEffect = $scope.data('eael_rotate_effect'),
    $eaelScaleEffect      = $scope.data('eael_scale_effect'),
    $eaelSkewEffect       = $scope.data('eael_skew_effect'),
    $Opacity              = $scope.data('eael_opacity'),
    $eaelBlurEffect       = $scope.data('eael_blur_effect'),
    $eaelContrastEffect   = $scope.data('eael_contrast_effect'),
    $eaelGrayscaleEffect  = $scope.data('eael_grayscale_effect'),
    $eaelInvertEffect     = $scope.data('eael_invert_effect'),
    $eaelSaturateEffect   = $scope.data('eael_saturate_effect'),
    $eaelSepiaEffect      = $scope.data('eael_sepia_effect'),
    $scopeId = $scope.data('id'),
    $eaelBurHoverEffect = $scope.data('eael_blur_hover_effect'),
    $eaelContrastHoverEffect = $scope.data('eael_contrast_hover_effect'),
    $eaelGrayscalHoverEffect = $scope.data('eael_grayscal_hover_effect'),
    $eaelInvertHoverEffect = $scope.data('eael_invert_hover_effect'),
    $eaelSaturateHoverEffect = $scope.data('eael_saturate_hover_effect'),
    $eaelSepiaHoverEffect = $scope.data('eael_sepia_hover_effect'),
    $eaelRotateHoverEffect = $scope.data('eael_rotate_hover_effect'),
    $eaelScaleHoverEffect = $scope.data('eael_scale_hover_effect'),
    $eaelSkewHoverEffect = $scope.data('eael_skew_hover_effect'),
    $opacityHover              = $scope.data('eael_opacity_hover'),
    $eaelDuration              = $scope.data('eael_duration'),
    $eaelDelay              = $scope.data('eael_delay'),
    $eaelEasing              = $scope.data('eael_easing'),
    $eaelHoverDuration              = $scope.data('eael_hover_duration'),
    $eaelHoverDelay              = $scope.data('eael_hover_delay'),
    $eaelHoverEasing              = $scope.data('eael_hover_easing'),
    $eaelOffsetTop              = $scope.data('eael_offset_top'),
    $eaelOffsetLeft              = $scope.data('eael_offset_left'),
    $eaelOffsetHoverTop              = $scope.data('eael_offset_hover_top'),
    $eaelOffsetHoverLeft              = $scope.data('eael_offset_hover_left'),
    $eaelContainer        = $('.elementor-widget-container', $scope);

    let hoverSelector = `body [data-id="${$scopeId}"] > .elementor-widget-container`;

    //Opacity
    let $opacityVal = $Opacity ? $Opacity?.opacity : '1';

    //Offset
    let $offsetX = $eaelOffsetTop?.size ? `translateX(${$eaelOffsetTop.size}${$eaelOffsetTop.unit})` : 'translateX(0)';
    let $offsetY = $eaelOffsetLeft?.size ? `translateX(${$eaelOffsetLeft.size}${$eaelOffsetLeft.unit})` : 'translateX(0)';

    //Offset Hover
    let $offsetHoverX = $eaelOffsetHoverTop?.size ? `translateX(${$eaelOffsetHoverTop.size}${$eaelOffsetHoverTop.unit})` : 'translateX(0)';
    let $offsetHoverY = $eaelOffsetHoverLeft?.size ? `translateX(${$eaelOffsetHoverLeft.size}${$eaelOffsetHoverLeft.unit})` : 'translateX(0)';
    
    //Transitions
    let $eaelDurationVal = $eaelDuration ? $eaelDuration?.transitionDuration : '0';
    let $eaelDelayVal = $eaelDelay ? $eaelDelay?.transitionDelay : '0';
    let $eaelEasingVal = $eaelEasing ? $eaelEasing?.transitionEasing : '0';

    //Transitions Hover
    let $eaelDurationHoverVal = $eaelHoverDuration ? $eaelHoverDuration?.transitionDuration : '0';
    let $eaelDelayHoverVal = $eaelHoverDelay ? $eaelHoverDelay?.transitionDelay : '0';
    let $eaelEasingHoverVal = $eaelHoverEasing ? $eaelHoverEasing?.transitionEasing : '0';

    //Filter
    let $blur      = $eaelBlurEffect?.blur ? `blur(${$eaelBlurEffect.blur}px)`                        : 'blur(0px)';
    let $contrast  = $eaelContrastEffect?.contrast ? `contrast(${$eaelContrastEffect.contrast}%)`     : 'contrast(100%)';
    let $grayscale = $eaelGrayscaleEffect?.grayscale ? `grayscale(${$eaelGrayscaleEffect.grayscale}%)`: 'grayscale(0%)';
    let $invert    = $eaelInvertEffect?.invert ? `invert(${$eaelInvertEffect.invert}%)`               : 'invert(0%)';
    let $saturate  = $eaelSaturateEffect?.saturate ? `saturate(${$eaelSaturateEffect.saturate}%)`     : 'saturate(100%)';
    let $sepia     = $eaelSepiaEffect?.sepia ? `sepia(${$eaelSepiaEffect.sepia}%)` : 'sepia(0%)';

    //Rotate
    let $rotateX = $eaelRotateEffect?.rotate_x ? `rotateX(${$eaelRotateEffect.rotate_x}deg)` : 'rotateX(0)';
    let $rotateY = $eaelRotateEffect?.rotate_y ? `rotateY(${$eaelRotateEffect.rotate_y}deg)` : 'rotateY(0)';
    let $rotateZ = $eaelRotateEffect?.rotate_z ? `rotateZ(${$eaelRotateEffect.rotate_z}deg)` : 'rotateZ(0)';

    //Scale
    let $scaleX = $eaelScaleEffect?.scale_x ? `scaleX(${$eaelScaleEffect.scale_x})` : 'scaleX(1)';
    let $scaleY = $eaelScaleEffect?.scale_y ? `scaleY(${$eaelScaleEffect.scale_y})` : 'scaleY(1)';
    
    //Skew
    let $skewX = $eaelSkewEffect?.skew_x ? `skewX(${$eaelSkewEffect.skew_x}deg)` : 'skewX(0deg)';
    let $skewY = $eaelSkewEffect?.skew_y ? `skewY(${$eaelSkewEffect.skew_y}deg)` : 'skewY(0deg)';

    //Hover
    let $opacityHoverVal = $opacityHover ? $opacityHover?.opacity : '1';
    //Rotate
    let $rotateXHover = $eaelRotateHoverEffect?.rotate_x ? `rotateX(${$eaelRotateHoverEffect.rotate_x}deg)` : 'rotateX(0)';
    let $rotateYHover = $eaelRotateHoverEffect?.rotate_y ? `rotateY(${$eaelRotateHoverEffect.rotate_y}deg)` : 'rotateY(0)';
    let $rotateZHover = $eaelRotateHoverEffect?.rotate_z ? `rotateZ(${$eaelRotateHoverEffect.rotate_z}deg)` : 'rotateZ(0)';

    //Scale
    let $scaleXHover = $eaelScaleHoverEffect?.scale_x ? `scaleX(${$eaelScaleHoverEffect.scale_x})` : 'scaleX(1)';
    let $scaleYHover = $eaelScaleHoverEffect?.scale_y ? `scaleY(${$eaelScaleHoverEffect.scale_y})` : 'scaleY(1)';
    
    //Skew
    let $skewXHover = $eaelSkewHoverEffect?.skew_x ? `skewX(${$eaelSkewHoverEffect.skew_x}deg)` : 'skewX(0)';
    let $skewYHover = $eaelSkewHoverEffect?.skew_y ? `skewY(${$eaelSkewHoverEffect.skew_y}deg)` : 'skewY(0)';
    
    //Filter Hover
    let $blurHover      = $eaelBurHoverEffect?.blur ? `blur(${$eaelBurHoverEffect.blur}px)` : 'blur(0px)';
    let $contrastHover      = $eaelContrastHoverEffect?.contrast ? `contrast(${$eaelContrastHoverEffect.contrast}%)` : 'contrast(100%)';
    let $grayscaleHover      = $eaelGrayscalHoverEffect?.grayscale ? `grayscale(${$eaelGrayscalHoverEffect.grayscale}%)` : 'grayscale(0%)';
    let $invertHover      = $eaelInvertHoverEffect?.invert ? `invert(${$eaelInvertHoverEffect.invert}%)` : 'invert(0%)';
    let $saturateHover      = $eaelSaturateHoverEffect?.saturate ? `saturate(${$eaelSaturateHoverEffect.saturate}%)` : 'saturate(100%)';
    let $sepiaHover      = $eaelSepiaHoverEffect?.sepia ? `sepia(${$eaelSepiaHoverEffect.sepia}%)` : 'sepia(0%)';
    
    //Normal
    let normalStyles = {
        "transform": `${$rotateX} ${$rotateY} ${$rotateZ} ${$scaleX} ${$scaleY} ${$skewX} ${$skewY} ${$offsetX} ${$offsetY}`,
        "opacity": $opacityVal,
        "filter": `${$blur} ${$contrast} ${$grayscale} ${$invert} ${$saturate} ${$sepia}`,
        "transition-property": 'all',
        "transition-duration": `${$eaelDurationVal}ms`,
        "transition-delay": `${$eaelDelayVal}ms`,
        "transition-timing-function": $eaelEasingVal,
    }

    //Hover
    let hoverStyles = {
        'opacity': $opacityHoverVal,
        'filter': `${$blurHover} ${$contrastHover} ${$grayscaleHover} ${$invertHover} ${$saturateHover} ${$sepiaHover}`,
        "transform": `${$rotateXHover} ${$rotateYHover} ${$rotateZHover} ${$scaleXHover} ${$scaleYHover} ${$skewXHover} ${$skewYHover} ${$offsetHoverX} ${$offsetHoverY}`,
        "transition-property": 'all',
        "transition-duration": `${$eaelDurationHoverVal}ms`,
        "transition-delay": `${$eaelDelayHoverVal}ms`,
        "transition-timing-function": $eaelEasingHoverVal,
    };

    $(hoverSelector).hover(
        function() {
            $(this).css(hoverStyles);
        },
        function() {
        $(this).css(normalStyles);
        }
    );  

    //
    $eaelContainer.css(normalStyles);
}

jQuery(window).on("elementor/frontend/init", function () {
    if (ea.elementStatusCheck('eaelHoverEffect')) {
        return false;
    }
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/widget", 
        HoverEffectHandler
    );
});
