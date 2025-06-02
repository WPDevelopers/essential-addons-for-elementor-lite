
var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        widget_id = $scope.data('id'),
        svg_icon = $('svg', wrapper),
        settings = wrapper.data('settings'),
        transition = Number( settings.transition ),
        loop_delay = Number( settings.loop_delay ),
        offset = 0,
        lines = $('path, circle, rect, polygon', svg_icon);

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
                    repeatDelay: loop_delay
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
                                setTimeout( function() {
                                    gsap.to(lines, {
                                        fill: settings.fill_color + '00',
                                        duration: transition
                                    });
                                }, loop_delay * 1000);
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
        let showMarkers = settings.marker && elementorFrontend.isEditMode();
        $('.marker-'+widget_id).remove();
        
        let timeline = gsap.timeline({
            scrollTrigger: {
                trigger: lines,
                start: "top "+settings.start_point,
                end: "top "+settings.end_point,
                scrub: true,
                id: widget_id,
                markers: showMarkers,
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
