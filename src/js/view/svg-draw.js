var SVGDraw = function ($scope, $) {
    let svg_icon = $('svg', $scope);
    let count = 50;
    function draw_line() {
        // your original if-else wasn't allowing count to increment past 1
        if (count < 0) {
            return;
        }
        let count_1 = 50-count;
        $('path', svg_icon).css({'stroke-dashoffset': '0px', 'stroke-dasharray': count_1+'px, '+count+'px'});
        count--;
    }

    setInterval(draw_line,90);
}
// stroke-dashoffset: 63;
// stroke-dasharray: 0px, 38px;
// stroke: #c36;
// stroke-width: 1px;
// fill: transparent;

jQuery(window).on("elementor/frontend/init", function () {

    // if (ea.elementStatusCheck('eaelSVGDraw')) {
    //     return false;
    // }

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-svg-draw.default",
        SVGDraw
    );
});