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

    svg_icon.hover(function (){
        draw_interval = window.setInterval(draw_line, .001);
    },function (){
        window.clearInterval(draw_interval);
    });

    $(window).on('scroll', function (e){
        console.log('scroll')
        draw_line(1);
    })

}
var SVGDraw = function ($scope, $) {
    let svg_icon = $('svg', $scope),
        paths = $('path', svg_icon),
        count = 50,
        path_count = 0,
    elemID = $scope.data("id"),
        draw_interval;

    var animRev = 'pause play reverse',
        timeLine = new TimelineMax({
            repeat: 0,
            yoyo: true,
            scrollTrigger: {
                trigger: '.elementor-element-' + elemID,
                toggleActions: "play " + animRev,
                start: "top 100", //when the top of the element hits that offset of the viewport.
            }
        });
    console.log(timeLine );
}
jQuery(window).on("elementor/frontend/init", function () {

    // if (ea.elementStatusCheck('eaelSVGDraw')) {
    //     return false;
    // }

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-svg-draw.default",
        SVGDraw
    );
});