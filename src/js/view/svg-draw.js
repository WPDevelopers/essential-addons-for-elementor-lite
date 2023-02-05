var SVGDraw_old = function ($scope, $) {
    let svg_icon = $('svg', $scope),
        paths = $('path', svg_icon),
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
            return;
        }
        if ( typeof paths[path_count] !== undefined ){
            let count_1 = 50-count;
            $(paths[path_count]).css({'stroke-dasharray': count_1+'px, '+count+'px'});
        }

        count-= step;
    }

    console.log(svg_icon.parent());
    if ( svg_icon.parent().hasClass('hover') ){
        svg_icon.hover(function (){
            draw_interval = window.setInterval(draw_line, .001);
        },function (){
            window.clearInterval(draw_interval);
        });
    }else if ( svg_icon.parent().hasClass('page-load') ){
        window.setInterval(draw_line, .001);
    }
}
jQuery(window).on("elementor/frontend/init", function () {

    if (ea.elementStatusCheck('eaelSVGDraw')) {
        return false;
    }

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-svg-draw.default",
        SVGDraw_old
    );
});