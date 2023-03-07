var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        speed = wrapper.data('speed'),
        is_repeat = wrapper.data('loop'),
        pauseOnHover = wrapper.data('pause'),
        draw_interval,
        addOrSubtract,
        stepCount = 0,
        $doc = $(document),
        $win = $(window),
        max = $doc.height() - $win.height();

    function stepManager() {
        if (addOrSubtract) {
            stepCount += 0.01;
            if (stepCount >= 1) {
                addOrSubtract = false;
            }
        } else {
            stepCount -= 0.01;
            if (stepCount <= 0) {
                addOrSubtract = true;
            }
        }

        return stepCount;
    }

    if (svg_icon.parent().hasClass('page-scroll')){
        $win.on('scroll', function() {
            let step =( $win.scrollTop() / max );
            svg_icon.drawsvg('progress', step);
        });
    }
    else if ( svg_icon.parent().hasClass('page-load') ){
        let lastSvg = '';
        let  drawSvg = setInterval(function() {
            let currentSvg = svg_icon.html();
            svg_icon.drawsvg('progress', stepManager());

            if (  currentSvg === lastSvg && is_repeat === 'no'){
                wrapper.addClass( wrapper.data('fill') )
                clearInterval(drawSvg);
            }
            lastSvg = currentSvg;
        }, speed);
    }
    else if ( svg_icon.parent().hasClass('hover') ){
        let lastSvg = '';
        svg_icon.hover(function (){
            draw_interval = window.setInterval(function (){
                let currentSvg = svg_icon.html();
                svg_icon.drawsvg('progress', stepManager());

                if (  currentSvg === lastSvg && is_repeat === 'no'){
                    wrapper.addClass( wrapper.data('fill') )
                    window.clearInterval(draw_interval);
                }

                lastSvg = currentSvg;
            }, speed);

        },function (){
            if ( pauseOnHover === 'yes' ) {
                window.clearInterval(draw_interval);
            }
        });
    }
}
jQuery(window).on("elementor/frontend/init", function () {

    if (ea.elementStatusCheck('eaelDrawSVG')) {
        return false;
    }

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-svg-draw.default",
        SVGDraw
    );
});