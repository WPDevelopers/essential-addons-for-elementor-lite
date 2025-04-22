
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

        function dashArrayReset() {
            let largestDashArray = 0, largestPath = '';
            $.each( lines, function (index, line) {
                let dashArray = $(line).css('stroke-dasharray');
                let dashArrayValue = parseInt(dashArray);
                if (dashArrayValue > largestDashArray) {
                    largestDashArray = dashArrayValue;
                    largestPath = $(line);
                }
            });
    
            if ( largestDashArray < 3999 && largestDashArray / 2 > 600 ) {
                let offset = largestPath.css('stroke-dashoffset');
                offset = parseInt(offset);
    
                if ( 'after' === settings.fill ) {
                    if (offset < largestDashArray / 2) {
                        wrapper.addClass('fill-svg');
                    } else if ( wrapper.hasClass('fill-svg') ) {
                        wrapper.removeClass('fill-svg');
                    }
                }
                
            }
        }

        function drawSVGLine (){
            console.log('lines', lines);
            
            $.each( lines, function (index, line) { 
                const length = line.getTotalLength() * ( settings.stroke_length * .01 );
                console.log('leng', length, settings.stroke_length);
                
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
                        setTimeout(function(){
                            gsap.to(lines, {
                                fill: settings.fill_color + '00',
                                duration: 2
                            });
                        }, transition * 1000 );

                    }
                },
                onRepeat: function(){
                    console.log('repeat started');
                    
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
    } else if (wrapper.hasClass('page-scroll')) {
        $win.on('scroll', function () {
            let step = (($win.scrollTop() - offset) / max);
            let offsetTop = svg_icon.offset().top,
                viewPort = $win.innerHeight(),
                offsetBottom = offsetTop - viewPort;

            if (offsetTop > $win.scrollTop() && offsetBottom < $win.scrollTop()) {
                step = (($win.scrollTop() - offset) - offsetBottom) / viewPort;
                svg_icon.drawsvg('progress', step);
            }
            dashArrayReset();
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