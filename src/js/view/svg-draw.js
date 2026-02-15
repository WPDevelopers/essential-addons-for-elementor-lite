
var SVGDraw = function ($scope, $) {
    let wrapper = $('.eael-svg-draw-container', $scope),
        widget_id = $scope.data('id'),
        svg_icon = $('svg', wrapper),
        settings = wrapper.data('settings'),
        transition = Number(settings.transition),
        loop_delay = Number(settings.loop_delay),
        offset = 0,
        lines = $('path, circle, rect, polygon', svg_icon);

    if (settings?.has_pro) {
        return false;
    }

    // Helper: Easing functions dictionary
    const easingFunctions = {
        'linear': 'linear',
        'power1.in': 'cubic-bezier(0.55, 0.085, 0.68, 0.53)',
        'power1.out': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
        'power1.inOut': 'cubic-bezier(0.455, 0.03, 0.515, 0.955)',
        'power2.in': 'cubic-bezier(0.55, 0.055, 0.675, 0.19)',
        'power2.out': 'cubic-bezier(0.215, 0.61, 0.355, 1)',
        'power2.inOut': 'cubic-bezier(0.645, 0.045, 0.355, 1)',
        'power3.in': 'cubic-bezier(0.895, 0.03, 0.685, 0.22)',
        'power3.out': 'cubic-bezier(0.165, 0.84, 0.44, 1)',
        'power3.inOut': 'cubic-bezier(0.77, 0, 0.175, 1)',
        'power4.in': 'cubic-bezier(0.895, 0.03, 0.685, 0.22)',
        'power4.out': 'cubic-bezier(0.165, 0.84, 0.44, 1)',
        'power4.inOut': 'cubic-bezier(0.86, 0, 0.07, 1)',
        'none': 'linear',
        'ease': 'ease',
        'ease-in': 'ease-in',
        'ease-out': 'ease-out',
        'ease-in-out': 'ease-in-out'
    };

    // Helper: Get easing value
    function getEasing(easeName) {
        return easingFunctions[easeName] || 'linear';
    }

    // Helper: Animate fill color
    function animateFill(elements, targetColor, duration) {
        $.each(elements, function (index, element) {
            element.animate(
                { fill: [getComputedStyle(element).fill, targetColor] },
                {
                    duration: duration * 1000,
                    fill: 'forwards',
                    easing: 'ease'
                }
            );
        });
    }

    // Initial fill animation
    if ('always' === settings.fill_type || 'before' === settings.fill_type) {
        animateFill(lines, settings.fill_color, transition);
    }

    // Main drawing function
    function drawSVGLine() {
        // Initialize stroke dash properties
        $.each(lines, function (index, line) {
            const length = line.getTotalLength() * (settings.stroke_length * .01);
            line.style.strokeDasharray = length;
            line.style.strokeDashoffset = length;
        });

        let animations = [];
        let isPaused = false;
        let shouldLoop = 'yes' === settings.loop;
        let isReverse = "reverse" === settings.direction;

        // Create animation for each line
        function createStrokeAnimation() {
            $.each(lines, function (index, line) {
                const animation = line.animate(
                    { strokeDashoffset: [line.style.strokeDashoffset, offset] },
                    {
                        duration: settings.speed * 1000,
                        fill: 'forwards',
                        easing: getEasing(settings.ease_type)
                    }
                );
                animations.push(animation);
            });

            // Handle animation complete
            animations[0].onfinish = function () {
                if (isPaused) return;

                // Handle fill animations on complete
                if ('' !== settings.fill_color) {
                    if ('after' === settings.fill_type) {
                        animateFill(lines, settings.fill_color, transition);

                        if (isReverse) {
                            setTimeout(function () {
                                if (!isPaused) {
                                    animateFill(lines, settings.fill_color + '00', transition);
                                }
                            }, loop_delay * 1000);
                        }
                    } else if ('before' === settings.fill_type) {
                        animateFill(lines, settings.fill_color + '00', transition);
                    }
                }

                // Handle looping
                if (shouldLoop) {
                    setTimeout(function () {
                        if (!isPaused) {
                            if (isReverse) {
                                // Reverse animation: animate back to initial state
                                reverseStrokeAnimation();
                            } else {
                                // Restart animation: reset and play again
                                resetAndRestart();
                            }
                        }
                    }, loop_delay * 1000);
                }
            };
        }

        // Reverse animation (for yoyo effect)
        function reverseStrokeAnimation() {
            animations = [];
            $.each(lines, function (index, line) {
                const currentOffset = parseFloat(line.style.strokeDashoffset) || 0;
                const length = line.getTotalLength() * (settings.stroke_length * .01);

                const animation = line.animate(
                    { strokeDashoffset: [currentOffset, length] },
                    {
                        duration: settings.speed * 1000,
                        fill: 'forwards',
                        easing: getEasing(settings.ease_type)
                    }
                );
                animations.push(animation);
            });

            animations[0].onfinish = function () {
                if (isPaused) return;

                setTimeout(function () {
                    if (!isPaused) {
                        resetAndRestart();
                    }
                }, loop_delay * 1000);
            };
        }

        // Reset and restart the animation
        function resetAndRestart() {
            // Reset stroke dash offset
            $.each(lines, function (index, line) {
                const length = line.getTotalLength() * (settings.stroke_length * .01);
                line.style.strokeDashoffset = length;
            });

            // Handle fill on start
            if ('' !== settings.fill_color) {
                if ('after' === settings.fill_type && "restart" === settings.direction) {
                    animateFill(lines, settings.fill_color + '00', transition);
                } else if ('before' === settings.fill_type) {
                    animateFill(lines, settings.fill_color, transition);
                }
            }

            // Restart animation
            animations = [];
            createStrokeAnimation();
        }

        // Handle fill on start
        if ('' !== settings.fill_color) {
            if ('after' === settings.fill_type && "restart" === settings.direction) {
                animateFill(lines, settings.fill_color + '00', transition);
            } else if ('before' === settings.fill_type) {
                animateFill(lines, settings.fill_color, transition);
            }
        }

        // Start initial animation
        createStrokeAnimation();

        // Handle pause on hover
        if ('yes' === settings.pause) {
            svg_icon.hover(function () {
                isPaused = true;
                $.each(animations, function (index, animation) {
                    animation.pause();
                });
            }, function () {
                isPaused = false;
                $.each(animations, function (index, animation) {
                    animation.play();
                });
            });
        }
    }

    if (wrapper.hasClass('page-load')) {
        drawSVGLine(lines, settings);
    } else if (wrapper.hasClass('mouse-hover')) {
        svg_icon.hover(function () {
            if (!wrapper.hasClass('draw-initialized')) {
                drawSVGLine(lines, settings);
                wrapper.addClass('draw-initialized');
            }
        });
    } else if (wrapper.hasClass('page-scroll')) {
        // Initialize stroke dash properties
        $.each(lines, function (index, line) {
            const length = line.getTotalLength() * (settings.stroke_length * .01);
            line.style.strokeDasharray = length;
            line.style.strokeDashoffset = length;
        });

        // Parse start and end points (format: "80%" or "center")
        function parseScrollPoint(point) {
            if (point.includes('%')) {
                return parseFloat(point) / 100;
            }
            // Handle named positions
            const positions = {
                'top': 0,
                'center': 0.5,
                'bottom': 1
            };
            return positions[point] || 0.5;
        }

        let startPoint = parseScrollPoint(settings.start_point);
        let endPoint = parseScrollPoint(settings.end_point);

        // Scroll-based animation using Intersection Observer with custom logic
        let observer;
        let scrollHandler = function () {
            const rect = wrapper[0].getBoundingClientRect();
            const windowHeight = window.innerHeight;

            // Calculate scroll progress
            const startTrigger = windowHeight * (1 - startPoint);
            const endTrigger = windowHeight * (1 - endPoint);

            let progress = 0;

            if (rect.top <= startTrigger && rect.top >= endTrigger) {
                progress = (startTrigger - rect.top) / (startTrigger - endTrigger);
                progress = Math.max(0, Math.min(1, progress));
            } else if (rect.top < endTrigger) {
                progress = 1;
            }

            // Update stroke dash offset based on scroll progress
            $.each(lines, function (index, line) {
                const length = line.getTotalLength() * (settings.stroke_length * .01);
                const targetOffset = length * (1 - progress);
                line.style.strokeDashoffset = targetOffset;
            });

            // Handle fill color changes based on progress
            if ('' !== settings.fill_color && ('before' === settings.fill_type || 'after' === settings.fill_type)) {
                let fill1 = settings.fill_color;
                let fill2 = settings.fill_color + '00';

                if ('after' === settings.fill_type) {
                    fill1 = settings.fill_color + '00';
                    fill2 = settings.fill_color;
                }

                if (progress < 0.95) {
                    $.each(lines, function (index, line) {
                        line.style.fill = fill1;
                    });
                } else if (progress > 0.95) {
                    $.each(lines, function (index, line) {
                        line.style.fill = fill2;
                    });
                }
            }
        };

        // Add scroll listener
        window.addEventListener('scroll', scrollHandler, { passive: true });
        // Initial calculation
        scrollHandler();

        // Cleanup on element removal
        wrapper.on('remove', function () {
            window.removeEventListener('scroll', scrollHandler);
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
