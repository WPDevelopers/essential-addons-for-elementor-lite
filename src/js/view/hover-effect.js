let HoverEffectHandler = function ($scope, $) {
    let $eaelRotateEffect = $scope.data('eael_rotate_effect'),
    $eaelScaleEffect = $scope.data('eael_scale_effect'),
    $eaelSkewEffect = $scope.data('eael_skew_effect'),
    $eaelContainer = $('.elementor-widget-container', $scope);

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

    $eaelContainer.css(
        {
            "transform": `${$rotateX} ${$rotateY} ${$rotateZ} ${$scaleX} ${$scaleY} ${$skewX} ${$skewY}`
        }
    );
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
