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
    $eaelContainer        = $('.elementor-widget-container', $scope);

    let hoverSelector = `body [data-id="${$scopeId}"] > .elementor-widget-container`;

    //Opacity
    let $opacityVal = $Opacity ? $Opacity?.opacity : '1';

    //Filter
    let $blur      = $eaelBlurEffect?.blur ? `blur(${$eaelBlurEffect.blur}px)`                        : '';
    let $contrast  = $eaelContrastEffect?.contrast ? `contrast(${$eaelContrastEffect.contrast}%)`     : '';
    let $grayscale = $eaelGrayscaleEffect?.grayscale ? `grayscale(${$eaelGrayscaleEffect.grayscale}%)`: '';
    let $invert    = $eaelInvertEffect?.invert ? `invert(${$eaelInvertEffect.invert}%)`               : '';
    let $saturate  = $eaelSaturateEffect?.saturate ? `saturate(${$eaelSaturateEffect.saturate}%)`     : '';
    let $sepia     = $eaelSepiaEffect?.sepia ? `sepia(${$eaelSepiaEffect.sepia}%)` : '';

    //Rotate
    let $rotateX = $eaelRotateEffect?.rotate_x ? `rotateX(${$eaelRotateEffect.rotate_x}deg)` : '';
    let $rotateY = $eaelRotateEffect?.rotate_y ? `rotateY(${$eaelRotateEffect.rotate_y}deg)` : '';
    let $rotateZ = $eaelRotateEffect?.rotate_z ? `rotateZ(${$eaelRotateEffect.rotate_z}deg)` : '';

    //Scale
    let $scaleX = $eaelScaleEffect?.scale_x ? `scaleX(${$eaelScaleEffect.scale_x})` : '';
    let $scaleY = $eaelScaleEffect?.scale_y ? `scaleY(${$eaelScaleEffect.scale_y})` : '';
    
    //Skew
    let $skewX = $eaelSkewEffect?.skew_x ? `skewX(${$eaelSkewEffect.skew_x}deg)` : '';
    let $skewY = $eaelSkewEffect?.skew_y ? `skewY(${$eaelSkewEffect.skew_y}deg)` : '';

    //Hover
    //Rotate
    let $rotateXHover = $eaelRotateHoverEffect?.rotate_x ? `rotateX(${$eaelRotateHoverEffect.rotate_x}deg)` : '';
    let $rotateYHover = $eaelRotateHoverEffect?.rotate_y ? `rotateY(${$eaelRotateHoverEffect.rotate_y}deg)` : '';
    let $rotateZHover = $eaelRotateHoverEffect?.rotate_z ? `rotateZ(${$eaelRotateHoverEffect.rotate_z}deg)` : '';

    //Scale
    let $scaleXHover = $eaelScaleHoverEffect?.scale_x ? `scaleX(${$eaelScaleHoverEffect.scale_x})` : '';
    let $scaleYHover = $eaelScaleHoverEffect?.scale_y ? `scaleY(${$eaelScaleHoverEffect.scale_y})` : '';
    
    //Skew
    let $skewXHover = $eaelSkewHoverEffect?.skew_x ? `skewX(${$eaelSkewHoverEffect.skew_x}deg)` : '';
    let $skewYHover = $eaelSkewHoverEffect?.skew_y ? `skewY(${$eaelSkewHoverEffect.skew_y}deg)` : '';
    
    //Filter Hover
    let $blurHover      = $eaelBurHoverEffect?.blur ? `blur(${$eaelBurHoverEffect.blur}px)` : '';
    let $contrastHover      = $eaelContrastHoverEffect?.contrast ? `contrast(${$eaelContrastHoverEffect.contrast}%)` : '';
    let $grayscaleHover      = $eaelGrayscalHoverEffect?.grayscale ? `grayscale(${$eaelGrayscalHoverEffect.grayscale}%)` : '';
    let $invertHover      = $eaelInvertHoverEffect?.invert ? `invert(${$eaelInvertHoverEffect.invert}%)` : '';
    let $saturateHover      = $eaelSaturateHoverEffect?.saturate ? `saturate(${$eaelSaturateHoverEffect.saturate}%)` : '';
    let $sepiaHover      = $eaelSepiaHoverEffect?.sepia ? `sepia(${$eaelSepiaHoverEffect.sepia}%)` : '';
    
    //Normal
    let normalStyles = {
        "transform": `${$rotateX} ${$rotateY} ${$rotateZ} ${$scaleX} ${$scaleY} ${$skewX} ${$skewY}`,
        "opacity": $opacityVal,
        "filter": `${$blur} ${$contrast} ${$grayscale} ${$invert} ${$saturate} ${$sepia}`,
        "transition": `1s`
    }

    //Hover
    let hoverStyles = {
        'opacity': '.5',
        'filter': `${$blurHover} ${$contrastHover} ${$grayscaleHover} ${$invertHover} ${$saturateHover} ${$sepiaHover}`,
        "transform": `${$rotateXHover} ${$rotateYHover} ${$rotateZHover} ${$scaleXHover} ${$scaleYHover} ${$skewXHover} ${$skewYHover}`,
        "transition": `.5s`
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
