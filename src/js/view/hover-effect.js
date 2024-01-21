let HoverEffectHandler = function ($scope, $) {
    let $data_options = $scope.data('rotate_effect'),
    $eaelContainer = $('.elementor-widget-container', $scope),
    $rotateX = $data_options.rotate_x ? `rotateX(${$data_options.rotate_x}deg)` : '',
    $rotateY = $data_options.rotate_y ? `rotateY(${$data_options.rotate_y}deg)` : '',
    $rotateZ = $data_options.rotate_z ? `rotateZ(${$data_options.rotate_z}deg)` : '';

    $eaelContainer.css({"transform": `${$rotateX} ${$rotateY} ${$rotateZ}`});

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
