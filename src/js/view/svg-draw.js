
var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        settings = wrapper.data('settings'),
        transition = Number( settings.transition ),
        is_repeat = settings.loop,
        pauseOnHover = settings.pause,
        direction = settings.direction,
        offset = '' !== settings.offset ? settings.offset : 0,
        draw_interval,
        addOrSubtract,
        stepCount = 0,
        $doc = $(document),
        $win = $(window),
        lines = $('path, circle, rect, polygon', svg_icon),
        max = $doc.height() - $win.height();

        function drawSVGLine (){
            $.each( lines, function (index, line) { 
                const length = line.getTotalLength();
                line.style.strokeDasharray = length;
                line.style.strokeDashoffset = length;
            });
        
            let loopConfig = {};
            if ( 'yes' === settings.loop ) {
                loopConfig = {
                    repeat: -1,
                    yoyo: "reverse" === settings.direction,
                    repeatDelay:  transition
                };
            }
            
            let timeline = gsap.timeline(loopConfig);
        
            timeline.to(lines, {
                strokeDashoffset: offset,
                duration: settings.speed,
                onComplete: function() {
                    if( 'after' === settings.fill_type && '' !== settings.fill_color ) {
                        gsap.to(lines, {
                            fill: settings.fill_color,
                            duration: 1
                        });
                    }
                },
                onReverseComplete: function() {
                    if( 'after' === settings.fill_type && '' !== settings.fill_color ) {
                        gsap.to(lines, {
                            fill: 'none',
                            duration: 1
                        });
                    }
                },
                onStart: function () {
                    if( 'after' === settings.fill_type && '' !== settings.fill_color && "restart" === settings.direction ) {
                        gsap.to(lines, {
                            fill: 'none',
                            duration: 1
                        });
                    }
                }
            });

            if( 'yes' === settings.pause ) {
                svg_icon.hover(function(){
                    timeline.pause();
                }, function(){
                    timeline.play();
                });
            }
        }

    if( wrapper.hasClass( 'page-load' ) ) {
        drawSVGLine( lines, settings );
    } else if ( wrapper.hasClass( 'mouse-hover' ) ) {
        svg_icon.hover( function(){
            if ( ! wrapper.hasClass('draw-initialized') ) {
                drawSVGLine( lines, settings );
                wrapper.addClass('draw-initialized');
            } 
        });
    }
}
jQuery(window).on("elementor/frontend/init", function () {

    if (eael.elementStatusCheck('eaelDrawSVG')) {
        return false;
    }

    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-svg-draw.default",
        SVGDraw
    );
});