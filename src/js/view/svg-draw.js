var _SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        paths = $('path', svg_icon),
        speed = wrapper.data('speed'),
            count = 50,
        path_count = 0,
        draw_interval;

    let draw_line = function (step=.5){
        if (count < 0 && path_count <= paths.length ){
            count = 50;
            $(paths[path_count]).css({'stroke-dasharray': 'none'});
            path_count++;
        }
        else if (path_count===paths.length){
            clearInterval(draw_interval);
            wrapper.addClass( wrapper.data('fill') );
            return;
        }
        if ( typeof paths[path_count] !== undefined ){
            let count_1 = 50-count;
            $(paths[path_count]).css({'stroke-dasharray': count_1+'px, '+count+'px'});
        }

        count-= step;
    }

    if ( svg_icon.parent().hasClass('hover') ){
        svg_icon.hover(function (){
            draw_interval = window.setInterval(draw_line, speed);
        },function (){
            window.clearInterval(draw_interval);
        });
    }else if ( svg_icon.parent().hasClass('page-load') ){
        window.setInterval(draw_line, speed);
    }
}

var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        speed = wrapper.data('speed'),
        is_repeat = wrapper.data('loop'),
        drawSvg,
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

    function drawSVG(){
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

    if (svg_icon.parent().hasClass('page-scroll')){
        $win.on('scroll', function() {
            let step =( $win.scrollTop() / max );
            svg_icon.drawsvg('progress', step);
        });
    }
    else if ( svg_icon.parent().hasClass('page-load') ){
        drawSVG();
    }
    else if ( svg_icon.parent().hasClass('hover') ){
        svg_icon.hover(function (){
            drawSVG();
        },function (){
            window.clearInterval(drawSvg);
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