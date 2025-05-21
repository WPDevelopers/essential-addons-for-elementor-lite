
var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        svg_icon = $('svg', wrapper),
        settings = wrapper.data('settings'),
        transition = Number( settings.transition ),
        offset = '' !== settings.offset ? settings.offset : 0,
        $doc = $(document),
        $win = $(window),
        lines = $('path, circle, rect, polygon', svg_icon),
        max = $doc.height() - $win.height();

        if( 'always' === settings.fill_type || 'before' === settings.fill_type ) {
            gsap.to(lines, {
                fill: settings.fill_color,
                duration: transition
            });
        }

        function drawSVGLine (){
            $.each( lines, function (index, line) { 
                const length = line.getTotalLength() * ( settings.stroke_length * .01 );
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
                ease: settings.ease_type,
                onComplete: function() {
                    if( '' !== settings.fill_color ) {
                        if( 'after' === settings.fill_type ) {
                            gsap.to(lines, {
                                fill: settings.fill_color,
                                duration: transition
                            });
                            if ( 'reverse' === settings.direction ) {
                                gsap.to(lines, {
                                    fill: settings.fill_color + '00',
                                    duration: transition
                                });
                            }
                        } else if ( 'before' === settings.fill_type ) {
                            gsap.to(lines, {
                                fill: settings.fill_color + '00',
                                duration: transition
                            });
                        }
                    }
                },
                onStart: function () {
                    if( '' !== settings.fill_color ) {
                        if( 'after' === settings.fill_type && "restart" === settings.direction ) {
                            gsap.to(lines, {
                                fill: settings.fill_color + '00',
                                duration: transition
                            });
                        } else if ( 'before' === settings.fill_type ) {
                            gsap.to(lines, {
                                fill: settings.fill_color,
                                duration: transition
                            });
                        }
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
    } else if (wrapper.hasClass('page-scroll')) {
        gsap.registerPlugin(ScrollTrigger);
        $.each( lines, function (index, line) { 
            const length = line.getTotalLength() * ( settings.stroke_length * .01 );
            line.style.strokeDasharray = length;
            line.style.strokeDashoffset = length;
        });
        
        let timeline = gsap.timeline({
            scrollTrigger: {
                trigger: lines,
                start: "top 95%",
                end: "top 10%",
                scrub: true,
                markers: true,
                onUpdate: (self) => {
                    if( '' !== settings.fill_color && ( 'before' === settings.fill_type || 'after' === settings.fill_type ) ) {
                        let fill1 = settings.fill_color, fill2 = settings.fill_color + '00';
                        if ( 'after' === settings.fill_type ) {
                            fill1 = settings.fill_color + '00';
                            fill2 = settings.fill_color;
                        }
                        if ( self.progress < 0.95 ) {
                            gsap.to(lines, {
                                fill: fill1,
                                duration: transition
                            });
                        } else if ( self.progress > 0.95 ) {
                            gsap.to(lines, {
                                fill: fill2,
                                duration: transition
                            });
                        }
                    }
                },
            } 
        });

        timeline.to(lines, { strokeDashoffset: 0, });
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
