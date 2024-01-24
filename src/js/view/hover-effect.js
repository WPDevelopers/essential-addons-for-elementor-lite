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
    $eaelbBurHoverEffect      = $scope.data('eael_blur_hover_effect'),
    $scopeId      = $scope.data('id'),
    $eaelContainer        = $('.elementor-widget-container', $scope);

    //Opacity
    let $opacityVal = $Opacity ? $Opacity?.opacity : '1';

    //Filter
    let $blur      = $eaelBlurEffect?.blur ? `blur(${$eaelBlurEffect.blur}px)`                        : '';
    let $contrast  = $eaelContrastEffect?.contrast ? `contrast(${$eaelContrastEffect.contrast}%)`     : '';
    let $grayscale = $eaelGrayscaleEffect?.grayscale ? `grayscale(${$eaelGrayscaleEffect.grayscale}%)`: '';
    let $invert    = $eaelInvertEffect?.invert ? `invert(${$eaelInvertEffect.invert}%)`               : '';
    let $saturate  = $eaelSaturateEffect?.saturate ? `saturate(${$eaelSaturateEffect.saturate}%)`     : '';
    let $sepia     = $eaelSepiaEffect?.sepia ? `sepia(${$eaelSepiaEffect.sepia}%)` : '';
    
    //Filter Hover
    let $blurHover      = $eaelbBurHoverEffect?.blur ? `blur(${$eaelbBurHoverEffect.blur}px)` : '';

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

    let hoverSelector = `body [data-id="${$scopeId}"] > .elementor-widget-container`;
    
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
        'filter': $blurHover,
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
