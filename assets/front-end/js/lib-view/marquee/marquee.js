(function ($) {
    $.fn.eaelmarque = function (options) {
        var settings = $.extend({
            speed: 1,
            direction: 'left',
            pauseOnHover: true,
            draggable: true
        }, options);

        /*
        This helper function makes a group of elements animate along the x-axis in a seamless, responsive loop.
        Features:
        - Uses xPercent so that even if the widths change (like if the window gets resized), things still position correctly.
        - Draggable integration (requires GSAP Draggable)
        - combined with InertiaPlugin, dragging has momentum
        - Supports "center: true" (to put the active element in the center)
        - customizable speed
        - pauses on hover (if you set that up in the config)
        - supports "snap" (for snapping to elements)
        - responsive
        */
        function horizontalLoop(items, config) {
            items = gsap.utils.toArray(items);
            config = config || {};
            let onChange = config.onChange,
                lastIndex = 0,
                tl = gsap.timeline({
                    repeat: -1,
                    onUpdate:
                        onChange &&
                        function () {
                            let i = tl.closestIndex();
                            if (lastIndex !== i) {
                                lastIndex = i;
                                onChange(items[i], i);
                            }
                        },
                    paused: config.paused,
                    defaults: { ease: "none" },
                    onReverseComplete: () => tl.totalTime(tl.rawTime() + tl.duration() * 10)
                }),
                length = items.length,
                startX = items[0].offsetLeft,
                times = [],
                widths = [],
                spaceBefore = [],
                xPercents = [],
                curIndex = 0,
                center = config.center,
                pixelsPerSecond = (config.speed || 1) * 50,
                snap = config.snap === false ? (v) => v : gsap.utils.snap(config.snap || 1), // some browsers shift by a pixel to accommodate flex layouts, so for example if width is 20% the first element's width might be 242px, and the next 243px, alternating back and forth. So we snap to 5 percentage points to make things look more natural
                timeOffset = 0,
                container =
                    center === true
                        ? items[0].parentNode
                        : gsap.utils.toArray(center)[0] || items[0].parentNode,
                totalWidth,
                getTotalWidth = () =>
                    items[length - 1].offsetLeft +
                    (xPercents[length - 1] / 100) * widths[length - 1] -
                    startX +
                    spaceBefore[0] +
                    items[length - 1].offsetWidth *
                    gsap.getProperty(items[length - 1], "scaleX") +
                    (parseFloat(config.paddingRight) || 0),
                populateWidths = () => {
                    let b1 = container.getBoundingClientRect(),
                        b2;
                    items.forEach((el, i) => {
                        widths[i] = parseFloat(gsap.getProperty(el, "width", "px"));
                        xPercents[i] = snap(
                            (parseFloat(gsap.getProperty(el, "x", "px")) / widths[i]) * 100 +
                            gsap.getProperty(el, "xPercent")
                        );
                        b2 = el.getBoundingClientRect();
                        spaceBefore[i] = b2.left - (i ? b1.right : b1.left);
                        b1 = b2;
                    });
                    gsap.set(items, {
                        // convert "x" to "xPercent" to make things responsive, and populate the widths/xPercents Arrays to make lookups faster.
                        xPercent: (i) => xPercents[i]
                    });
                    totalWidth = getTotalWidth();
                },
                timeWrap,
                populateOffsets = () => {
                    timeOffset = center
                        ? (tl.duration() * (container.offsetWidth / 2)) / totalWidth
                        : 0;
                    center &&
                        times.forEach((t, i) => {
                            times[i] = timeWrap(
                                tl.labels["label" + i] +
                                (tl.duration() * widths[i]) / 2 / totalWidth -
                                timeOffset
                            );
                        });
                },
                getClosest = (values, value, wrap) => {
                    let i = values.length,
                        closest = 1e10,
                        index = 0,
                        d;
                    while (i--) {
                        d = Math.abs(values[i] - value);
                        if (d > wrap / 2) {
                            d = wrap - d;
                        }
                        if (d < closest) {
                            closest = d;
                            index = i;
                        }
                    }
                    return index;
                },
                populateTimeline = () => {
                    let i, item, curX, distanceToStart, distanceToLoop;
                    tl.clear();
                    for (i = 0; i < length; i++) {
                        item = items[i];
                        curX = (xPercents[i] / 100) * widths[i];
                        distanceToStart = item.offsetLeft + curX - startX + spaceBefore[0];
                        distanceToLoop =
                            distanceToStart + widths[i] * gsap.getProperty(item, "scaleX");
                        tl.to(
                            item,
                            {
                                xPercent: snap(((curX - distanceToLoop) / widths[i]) * 100),
                                duration: distanceToLoop / pixelsPerSecond
                            },
                            0
                        )
                            .fromTo(
                                item,
                                {
                                    xPercent: snap(
                                        ((curX - distanceToLoop + totalWidth) / widths[i]) * 100
                                    )
                                },
                                {
                                    xPercent: xPercents[i],
                                    duration:
                                        (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond,
                                    immediateRender: false
                                },
                                distanceToLoop / pixelsPerSecond
                            )
                            .add("label" + i, distanceToStart / pixelsPerSecond);
                        times[i] = distanceToStart / pixelsPerSecond;
                    }
                    timeWrap = gsap.utils.wrap(0, tl.duration());
                },
                refresh = (deep) => {
                    let progress = tl.progress();
                    tl.progress(0, true);
                    populateWidths();
                    deep && populateTimeline();
                    populateOffsets();
                    deep && tl.draggable
                        ? tl.time(times[curIndex], true)
                        : tl.progress(progress, true);
                },
                proxy;
            gsap.set(items, { x: 0 });
            populateWidths();
            populateTimeline();
            populateOffsets();
            window.addEventListener("resize", () => refresh(true));
            function toIndex(index, vars) {
                vars = vars || {};
                Math.abs(index - curIndex) > length / 2 &&
                    (index += index > curIndex ? -length : length); // always go in the shortest direction
                let newIndex = gsap.utils.wrap(0, length, index),
                    time = times[newIndex];
                if (time > tl.time() !== index > curIndex) {
                    // if we're wrapping the timeline's playhead, make the proper adjustments
                    time += tl.duration() * (index > curIndex ? 1 : -1);
                }
                if (time < 0 || time > tl.duration()) {
                    vars.modifiers = { time: timeWrap };
                }
                curIndex = newIndex;
                vars.overwrite = true;
                gsap.killTweensOf(proxy);
                return tl.tweenTo(time, vars);
            }
            tl.next = (vars) => toIndex(curIndex + 1, vars);
            tl.previous = (vars) => toIndex(curIndex - 1, vars);
            tl.current = () => curIndex;
            tl.toIndex = (index, vars) => toIndex(index, vars);
            tl.closestIndex = (setCurrent) => {
                let index = getClosest(times, tl.time(), tl.duration());
                setCurrent && (curIndex = index);
                return index;
            };
            tl.times = times;
            tl.progress(1, true).progress(0, true); // pre-render for performance
            if (config.reversed) {
                tl.vars.onReverseComplete();
                tl.reverse();
            }
            if (config.draggable && typeof Draggable === "function") {
                proxy = document.createElement("div");
                let wrap = gsap.utils.wrap(0, 1),
                    ratio,
                    startProgress,
                    draggable,
                    dragSnap,
                    align = () =>
                        tl.progress(
                            wrap(startProgress + (draggable.startX - draggable.x) * ratio)
                        ),
                    syncIndex = () => tl.closestIndex(true);
                typeof InertiaPlugin === "undefined" &&
                    console.warn(
                        "InertiaPlugin required for momentum-based scrolling and snapping. https://greensock.com/club"
                    );
                draggable = Draggable.create(proxy, {
                    trigger: items[0].parentNode,
                    type: "x",
                    onPressInit() {
                        gsap.killTweensOf(tl);
                        startProgress = tl.progress();
                        refresh();
                        ratio = 1 / totalWidth;
                        gsap.set(proxy, { x: startProgress / -ratio });
                    },
                    onDrag: align,
                    onThrowUpdate: align,
                    inertia: true,
                    maxDuration: 1.5,
                    throwResistance: 2000,
                    snap: (value) => {
                        let time = -(value * ratio) * tl.duration(),
                            wrappedTime = timeWrap(time),
                            snapTime = times[getClosest(times, wrappedTime, tl.duration())],
                            dif = snapTime - wrappedTime;
                        Math.abs(dif) > tl.duration() / 2 &&
                            (dif += dif < 0 ? tl.duration() : -tl.duration());
                        return (time + dif) / tl.duration() / -ratio;
                    },
                    onRelease: syncIndex,
                    onThrowComplete: syncIndex
                })[0];
                tl.draggable = draggable;
            }
            tl.closestIndex(true);
            onChange && onChange(items[curIndex], curIndex);
            return tl;
        }

        return this.each(function () {
            var $wrapper = $(this);
            var $items = $wrapper.find('.product'); // Use find to get items

            if ($items.length === 0) return;

            var loop = horizontalLoop($items.toArray(), {
                paused: false,
                draggable: settings.draggable,
                center: false, // active element is the one in the center of the container rather than th left edge
                speed: settings.speed,
                reversed: settings.direction === 'right', // Reverse for right direction
                onChange: (element, index) => {
                    // when the active element changes, this function gets called.
                    $items.removeClass("-active");
                    $(element).addClass("-active");
                }
            });

            if (settings.pauseOnHover) {
                $wrapper.on('mouseenter', function () {
                    loop.pause();
                }).on('mouseleave', function () {
                    loop.play();
                });
            }
        });
    };
})(jQuery);