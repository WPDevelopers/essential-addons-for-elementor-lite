var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        settings = wrapper.data('settings'),
        speed = settings.speed,
        is_repeat = settings.loop,
        pauseOnHover = settings.pause,
        direction = settings.direction,
        offset = settings.offset,
        draw_interval,
        addOrSubtract,
        stepCount = 0,
        $doc = $(document),
        $win = $(window),
        max = $doc.height() - $win.height();

    if ( settings.excludeFill === 'yes' ){
        $('path', svg_icon).css('fill', '');
    }

    function dashArrayReset(){
        let largestDashArray = 0, largestPath = '';
        $('path', svg_icon).each(function() {
            let dashArray = $(this).css('stroke-dasharray');
            let dashArrayValue = parseInt(dashArray);
            if (dashArrayValue > largestDashArray) {
                largestDashArray = dashArrayValue;
                largestPath = $(this);
            }
        });

        if ( largestDashArray < 3999 && largestDashArray/2 > 600 && settings.fill === 'fill-svg' ){
            let offset = largestPath.css('stroke-dashoffset');
            offset = parseInt(offset);

            if ( offset < largestDashArray/2 ){
                wrapper.addClass(settings.fill);
            }
        }
    }

    function stepManager() {
        dashArrayReset();
        if (addOrSubtract) {
            stepCount += 0.001;
            if (stepCount >= 1) {
                addOrSubtract = false;
                if ( settings.fill === 'fill-svg' ){
                    wrapper.removeClass('fillout-svg').addClass(settings.fill);
                }
            }
        }
        else if ( direction === 'restart' ){
            stepCount = 0;
            addOrSubtract = true;
        }
        else {
            stepCount -= 0.001;
            if (stepCount <= 0) {
                addOrSubtract = true;
            }
        }

        return stepCount;
    }

    if (svg_icon.parent().hasClass('page-scroll')){
        $win.on('scroll', function() {
            let step =( ($win.scrollTop()-offset) / max );
            svg_icon.drawsvg('progress', step);
            dashArrayReset();
        });
    }
    else if ( svg_icon.parent().hasClass('page-load') ){
        let lastSvg = '';
        let  drawSvg = setInterval(function() {
            let currentSvg = svg_icon.html();
            svg_icon.drawsvg('progress', stepManager());

            if (  currentSvg === lastSvg && is_repeat === 'no'){
                wrapper.addClass( settings.fill );
                clearInterval(drawSvg);
            }
            lastSvg = currentSvg;
        }, speed);
    }
    else if ( svg_icon.parent().hasClass('hover') ){
        let lastSvg = '';
        svg_icon.hover(function (){
            if ( pauseOnHover === 'yes' || typeof draw_interval === 'undefined' ) {
                draw_interval = window.setInterval(function (){
                    let currentSvg = svg_icon.html();
                    svg_icon.drawsvg('progress', stepManager());

                    if (  currentSvg === lastSvg && is_repeat === 'no' ){
                        wrapper.addClass( settings.fill );
                        window.clearInterval(draw_interval);
                    }

                    lastSvg = currentSvg;
                }, speed);
            }
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