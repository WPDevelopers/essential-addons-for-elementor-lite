(function($) {
    window.isEditMode = !1;
    $(window).on("elementor/frontend/init", function() {
        window.isEditMode = elementorFrontend.isEditMode();
    });
})(jQuery);
!(function(a) {
    "function" == typeof define && define.amd
        ? define(["jquery"], a)
        : a(jQuery);
})(function(a) {
    "use strict";
    var b = function(c, d) {
        (this.$element = a(c)),
            (this.defaults = a.extend(
                {},
                b.defaults,
                this.$element.data(),
                a.isPlainObject(d) ? d : {}
            )),
            this.init();
    };
    (b.prototype = {
        constructor: b,
        init: function() {
            var a = this.$element.html(),
                b = new Date(this.defaults.date || a);
            b.getTime() &&
                ((this.content = a),
                (this.date = b),
                this.find(),
                this.defaults.autoStart && this.start());
        },
        find: function() {
            var a = this.$element;
            (this.$days = a.find("[data-days]")),
                (this.$hours = a.find("[data-hours]")),
                (this.$minutes = a.find("[data-minutes]")),
                (this.$seconds = a.find("[data-seconds]")),
                this.$days.length +
                    this.$hours.length +
                    this.$minutes.length +
                    this.$seconds.length >
                    0 && (this.found = !0);
        },
        reset: function() {
            this.found
                ? (this.output("days"),
                  this.output("hours"),
                  this.output("minutes"),
                  this.output("seconds"))
                : this.output();
        },
        ready: function() {
            var a,
                b = this.date,
                c = 100,
                d = 1e3,
                e = 6e4,
                f = 36e5,
                g = 864e5,
                h = {};
            return b
                ? ((a = b.getTime() - new Date().getTime()),
                  0 >= a
                      ? (this.end(), !1)
                      : ((h.days = a),
                        (h.hours = h.days % g),
                        (h.minutes = h.hours % f),
                        (h.seconds = h.minutes % e),
                        (h.milliseconds = h.seconds % d),
                        (this.days = Math.floor(h.days / g)),
                        (this.hours = Math.floor(h.hours / f)),
                        (this.minutes = Math.floor(h.minutes / e)),
                        (this.seconds = Math.floor(h.seconds / d)),
                        (this.deciseconds = Math.floor(h.milliseconds / c)),
                        !0))
                : !1;
        },
        start: function() {
            !this.active &&
                this.ready() &&
                ((this.active = !0),
                this.reset(),
                (this.autoUpdate = this.defaults.fast
                    ? setInterval(a.proxy(this.fastUpdate, this), 100)
                    : setInterval(a.proxy(this.update, this), 1e3)));
        },
        stop: function() {
            this.active && ((this.active = !1), clearInterval(this.autoUpdate));
        },
        end: function() {
            this.date &&
                (this.stop(),
                (this.days = 0),
                (this.hours = 0),
                (this.minutes = 0),
                (this.seconds = 0),
                (this.deciseconds = 0),
                this.reset(),
                this.defaults.end());
        },
        destroy: function() {
            this.date &&
                (this.stop(),
                (this.$days = null),
                (this.$hours = null),
                (this.$minutes = null),
                (this.$seconds = null),
                this.$element.empty().html(this.content),
                this.$element.removeData("countdown"));
        },
        fastUpdate: function() {
            --this.deciseconds >= 0
                ? this.output("deciseconds")
                : ((this.deciseconds = 9), this.update());
        },
        update: function() {
            --this.seconds >= 0
                ? this.output("seconds")
                : ((this.seconds = 59),
                  --this.minutes >= 0
                      ? this.output("minutes")
                      : ((this.minutes = 59),
                        --this.hours >= 0
                            ? this.output("hours")
                            : ((this.hours = 23),
                              --this.days >= 0
                                  ? this.output("days")
                                  : this.end())));
        },
        output: function(a) {
            if (!this.found)
                return void this.$element.empty().html(this.template());
            switch (a) {
                case "deciseconds":
                    this.$seconds.text(this.getSecondsText());
                    break;
                case "seconds":
                    this.$seconds.text(this.seconds);
                    break;
                case "minutes":
                    this.$minutes.text(this.minutes);
                    break;
                case "hours":
                    this.$hours.text(this.hours);
                    break;
                case "days":
                    this.$days.text(this.days);
            }
        },
        template: function() {
            return this.defaults.text
                .replace("%s", this.days)
                .replace("%s", this.hours)
                .replace("%s", this.minutes)
                .replace("%s", this.getSecondsText());
        },
        getSecondsText: function() {
            return this.active && this.defaults.fast
                ? this.seconds + "." + this.deciseconds
                : this.seconds;
        }
    }),
        (b.defaults = {
            autoStart: !0,
            date: null,
            fast: !1,
            end: a.noop,
            text: "%s days, %s hours, %s minutes, %s seconds"
        }),
        (b.setDefaults = function(c) {
            a.extend(b.defaults, c);
        }),
        (a.fn.countdown = function(c) {
            return this.each(function() {
                var d = a(this),
                    e = d.data("countdown");
                e || d.data("countdown", (e = new b(this, c))),
                    "string" == typeof c && a.isFunction(e[c]) && e[c]();
            });
        }),
        (a.fn.countdown.constructor = b),
        (a.fn.countdown.setDefaults = b.setDefaults),
        a(function() {
            a("[countdown]").countdown();
        });
});
!(function(t, s, e) {
    "use strict";
    var i = function(t, s) {
        var i = this;
        (this.el = t),
            (this.options = {}),
            Object.keys(r).forEach(function(t) {
                i.options[t] = r[t];
            }),
            Object.keys(s).forEach(function(t) {
                i.options[t] = s[t];
            }),
            (this.isInput = "input" === this.el.tagName.toLowerCase()),
            (this.attr = this.options.attr),
            (this.showCursor = !this.isInput && this.options.showCursor),
            (this.elContent = this.attr
                ? this.el.getAttribute(this.attr)
                : this.el.textContent),
            (this.contentType = this.options.contentType),
            (this.typeSpeed = this.options.typeSpeed),
            (this.startDelay = this.options.startDelay),
            (this.backSpeed = this.options.backSpeed),
            (this.backDelay = this.options.backDelay),
            e && this.options.stringsElement instanceof e
                ? (this.stringsElement = this.options.stringsElement[0])
                : (this.stringsElement = this.options.stringsElement),
            (this.strings = this.options.strings),
            (this.strPos = 0),
            (this.arrayPos = 0),
            (this.stopNum = 0),
            (this.loop = this.options.loop),
            (this.loopCount = this.options.loopCount),
            (this.curLoop = 0),
            (this.stop = !1),
            (this.cursorChar = this.options.cursorChar),
            (this.shuffle = this.options.shuffle),
            (this.sequence = []),
            this.build();
    };
    (i.prototype = {
        constructor: i,
        init: function() {
            var t = this;
            t.timeout = setTimeout(function() {
                for (var s = 0; s < t.strings.length; ++s) t.sequence[s] = s;
                t.shuffle && (t.sequence = t.shuffleArray(t.sequence)),
                    t.typewrite(t.strings[t.sequence[t.arrayPos]], t.strPos);
            }, t.startDelay);
        },
        build: function() {
            var t = this;
            if (
                (this.showCursor === !0 &&
                    ((this.cursor = s.createElement("span")),
                    (this.cursor.className = "typed-cursor"),
                    (this.cursor.innerHTML = this.cursorChar),
                    this.el.parentNode &&
                        this.el.parentNode.insertBefore(
                            this.cursor,
                            this.el.nextSibling
                        )),
                this.stringsElement)
            ) {
                (this.strings = []),
                    (this.stringsElement.style.display = "none");
                var e = Array.prototype.slice.apply(
                    this.stringsElement.children
                );
                e.forEach(function(s) {
                    t.strings.push(s.innerHTML);
                });
            }
            this.init();
        },
        typewrite: function(t, s) {
            if (this.stop !== !0) {
                var e = Math.round(70 * Math.random()) + this.typeSpeed,
                    i = this;
                i.timeout = setTimeout(function() {
                    var e = 0,
                        r = t.substr(s);
                    if ("^" === r.charAt(0)) {
                        var o = 1;
                        /^\^\d+/.test(r) &&
                            ((r = /\d+/.exec(r)[0]),
                            (o += r.length),
                            (e = parseInt(r))),
                            (t = t.substring(0, s) + t.substring(s + o));
                    }
                    if ("html" === i.contentType) {
                        var n = t.substr(s).charAt(0);
                        if ("<" === n || "&" === n) {
                            var a = "",
                                h = "";
                            for (
                                h = "<" === n ? ">" : ";";
                                t.substr(s + 1).charAt(0) !== h &&
                                ((a += t.substr(s).charAt(0)),
                                s++,
                                !(s + 1 > t.length));

                            );
                            s++, (a += h);
                        }
                    }
                    i.timeout = setTimeout(function() {
                        if (s === t.length) {
                            if (
                                (i.options.onStringTyped(i.arrayPos),
                                i.arrayPos === i.strings.length - 1 &&
                                    (i.options.callback(),
                                    i.curLoop++,
                                    i.loop === !1 || i.curLoop === i.loopCount))
                            )
                                return;
                            i.timeout = setTimeout(function() {
                                i.backspace(t, s);
                            }, i.backDelay);
                        } else {
                            0 === s && i.options.preStringTyped(i.arrayPos);
                            var e = t.substr(0, s + 1);
                            i.attr
                                ? i.el.setAttribute(i.attr, e)
                                : i.isInput
                                ? (i.el.value = e)
                                : "html" === i.contentType
                                ? (i.el.innerHTML = e)
                                : (i.el.textContent = e),
                                s++,
                                i.typewrite(t, s);
                        }
                    }, e);
                }, e);
            }
        },
        backspace: function(t, s) {
            if (this.stop !== !0) {
                var e = Math.round(70 * Math.random()) + this.backSpeed,
                    i = this;
                i.timeout = setTimeout(function() {
                    if (
                        "html" === i.contentType &&
                        ">" === t.substr(s).charAt(0)
                    ) {
                        for (
                            var e = "";
                            "<" !== t.substr(s - 1).charAt(0) &&
                            ((e -= t.substr(s).charAt(0)), s--, !(s < 0));

                        );
                        s--, (e += "<");
                    }
                    var r = t.substr(0, s);
                    i.attr
                        ? i.el.setAttribute(i.attr, r)
                        : i.isInput
                        ? (i.el.value = r)
                        : "html" === i.contentType
                        ? (i.el.innerHTML = r)
                        : (i.el.textContent = r),
                        s > i.stopNum
                            ? (s--, i.backspace(t, s))
                            : s <= i.stopNum &&
                              (i.arrayPos++,
                              i.arrayPos === i.strings.length
                                  ? ((i.arrayPos = 0),
                                    i.shuffle &&
                                        (i.sequence = i.shuffleArray(
                                            i.sequence
                                        )),
                                    i.init())
                                  : i.typewrite(
                                        i.strings[i.sequence[i.arrayPos]],
                                        s
                                    ));
                }, e);
            }
        },
        shuffleArray: function(t) {
            var s,
                e,
                i = t.length;
            if (i)
                for (; --i; )
                    (e = Math.floor(Math.random() * (i + 1))),
                        (s = t[e]),
                        (t[e] = t[i]),
                        (t[i] = s);
            return t;
        },
        reset: function() {
            var t = this;
            clearInterval(t.timeout);
            this.el.getAttribute("id");
            (this.el.textContent = ""),
                "undefined" != typeof this.cursor &&
                    "undefined" != typeof this.cursor.parentNode &&
                    this.cursor.parentNode.removeChild(this.cursor),
                (this.strPos = 0),
                (this.arrayPos = 0),
                (this.curLoop = 0),
                this.options.resetCallback();
        }
    }),
        (i["new"] = function(t, e) {
            var r = Array.prototype.slice.apply(s.querySelectorAll(t));
            r.forEach(function(t) {
                var s = t._typed,
                    r = "object" == typeof e && e;
                s && s.reset(),
                    (t._typed = s = new i(t, r)),
                    "string" == typeof e && s[e]();
            });
        }),
        e &&
            (e.fn.typed = function(t) {
                return this.each(function() {
                    var s = e(this),
                        r = s.data("typed"),
                        o = "object" == typeof t && t;
                    r && r.reset(),
                        s.data("typed", (r = new i(this, o))),
                        "string" == typeof t && r[t]();
                });
            }),
        (t.Typed = i);
    var r = {
        strings: [
            "These are the default values...",
            "You know what you should do?",
            "Use your own!",
            "Have a great day!"
        ],
        stringsElement: null,
        typeSpeed: 0,
        startDelay: 0,
        backSpeed: 0,
        shuffle: !1,
        backDelay: 500,
        loop: !1,
        loopCount: !1,
        showCursor: !0,
        cursorChar: "|",
        attr: null,
        contentType: "html",
        callback: function() {},
        preStringTyped: function() {},
        onStringTyped: function() {},
        resetCallback: function() {}
    };
})(window, document, window.jQuery);
!(function(a) {
    "use strict";

    function b(b, c) {
        (this.element = a(b)),
            (this.settings = a.extend({}, d, c)),
            (this._defaults = d),
            this._init();
    }
    var c = "Morphext",
        d = {
            animation: "bounceIn",
            separator: ",",
            speed: 2e3,
            complete: a.noop
        };
    (b.prototype = {
        _init: function() {
            var b = this;
            (this.phrases = []),
                this.element.addClass("morphext"),
                a.each(
                    this.element.text().split(this.settings.separator),
                    function(c, d) {
                        b.phrases.push(a.trim(d));
                    }
                ),
                (this.index = -1),
                this.animate(),
                this.start();
        },
        animate: function() {
            (this.index = ++this.index % this.phrases.length),
                (this.element[0].innerHTML =
                    '<span class="animated ' +
                    this.settings.animation +
                    '">' +
                    this.phrases[this.index] +
                    "</span>"),
                a.isFunction(this.settings.complete) &&
                    this.settings.complete.call(this);
        },
        start: function() {
            var a = this;
            this._interval = setInterval(function() {
                a.animate();
            }, this.settings.speed);
        },
        stop: function() {
            this._interval = clearInterval(this._interval);
        }
    }),
        (a.fn[c] = function(d) {
            return this.each(function() {
                a.data(this, "plugin_" + c) ||
                    a.data(this, "plugin_" + c, new b(this, d));
            });
        });
})(jQuery);
!(function(t, e) {
    "function" == typeof define && define.amd
        ? define("jquery-bridget/jquery-bridget", ["jquery"], function(i) {
              return e(t, i);
          })
        : "object" == typeof module && module.exports
        ? (module.exports = e(t, require("jquery")))
        : (t.jQueryBridget = e(t, t.jQuery));
})(window, function(t, e) {
    "use strict";

    function i(i, s, a) {
        function u(t, e, o) {
            var n,
                s = "$()." + i + '("' + e + '")';
            return (
                t.each(function(t, u) {
                    var h = a.data(u, i);
                    if (!h)
                        return void r(
                            i +
                                " not initialized. Cannot call methods, i.e. " +
                                s
                        );
                    var d = h[e];
                    if (!d || "_" == e.charAt(0))
                        return void r(s + " is not a valid method");
                    var l = d.apply(h, o);
                    n = void 0 === n ? l : n;
                }),
                void 0 !== n ? n : t
            );
        }

        function h(t, e) {
            t.each(function(t, o) {
                var n = a.data(o, i);
                n
                    ? (n.option(e), n._init())
                    : ((n = new s(o, e)), a.data(o, i, n));
            });
        }
        (a = a || e || t.jQuery),
            a &&
                (s.prototype.option ||
                    (s.prototype.option = function(t) {
                        a.isPlainObject(t) &&
                            (this.options = a.extend(!0, this.options, t));
                    }),
                (a.fn[i] = function(t) {
                    if ("string" == typeof t) {
                        var e = n.call(arguments, 1);
                        return u(this, t, e);
                    }
                    return h(this, t), this;
                }),
                o(a));
    }

    function o(t) {
        !t || (t && t.bridget) || (t.bridget = i);
    }
    var n = Array.prototype.slice,
        s = t.console,
        r =
            "undefined" == typeof s
                ? function() {}
                : function(t) {
                      s.error(t);
                  };
    return o(e || t.jQuery), i;
}),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("ev-emitter/ev-emitter", e)
            : "object" == typeof module && module.exports
            ? (module.exports = e())
            : (t.EvEmitter = e());
    })("undefined" != typeof window ? window : this, function() {
        function t() {}
        var e = t.prototype;
        return (
            (e.on = function(t, e) {
                if (t && e) {
                    var i = (this._events = this._events || {}),
                        o = (i[t] = i[t] || []);
                    return o.indexOf(e) == -1 && o.push(e), this;
                }
            }),
            (e.once = function(t, e) {
                if (t && e) {
                    this.on(t, e);
                    var i = (this._onceEvents = this._onceEvents || {}),
                        o = (i[t] = i[t] || {});
                    return (o[e] = !0), this;
                }
            }),
            (e.off = function(t, e) {
                var i = this._events && this._events[t];
                if (i && i.length) {
                    var o = i.indexOf(e);
                    return o != -1 && i.splice(o, 1), this;
                }
            }),
            (e.emitEvent = function(t, e) {
                var i = this._events && this._events[t];
                if (i && i.length) {
                    (i = i.slice(0)), (e = e || []);
                    for (
                        var o = this._onceEvents && this._onceEvents[t], n = 0;
                        n < i.length;
                        n++
                    ) {
                        var s = i[n],
                            r = o && o[s];
                        r && (this.off(t, s), delete o[s]), s.apply(this, e);
                    }
                    return this;
                }
            }),
            (e.allOff = function() {
                delete this._events, delete this._onceEvents;
            }),
            t
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("get-size/get-size", e)
            : "object" == typeof module && module.exports
            ? (module.exports = e())
            : (t.getSize = e());
    })(window, function() {
        "use strict";

        function t(t) {
            var e = parseFloat(t),
                i = t.indexOf("%") == -1 && !isNaN(e);
            return i && e;
        }

        function e() {}

        function i() {
            for (
                var t = {
                        width: 0,
                        height: 0,
                        innerWidth: 0,
                        innerHeight: 0,
                        outerWidth: 0,
                        outerHeight: 0
                    },
                    e = 0;
                e < h;
                e++
            ) {
                var i = u[e];
                t[i] = 0;
            }
            return t;
        }

        function o(t) {
            var e = getComputedStyle(t);
            return (
                e ||
                    a(
                        "Style returned " +
                            e +
                            ". Are you running this code in a hidden iframe on Firefox? See https://bit.ly/getsizebug1"
                    ),
                e
            );
        }

        function n() {
            if (!d) {
                d = !0;
                var e = document.createElement("div");
                (e.style.width = "200px"),
                    (e.style.padding = "1px 2px 3px 4px"),
                    (e.style.borderStyle = "solid"),
                    (e.style.borderWidth = "1px 2px 3px 4px"),
                    (e.style.boxSizing = "border-box");
                var i = document.body || document.documentElement;
                i.appendChild(e);
                var n = o(e);
                (r = 200 == Math.round(t(n.width))),
                    (s.isBoxSizeOuter = r),
                    i.removeChild(e);
            }
        }

        function s(e) {
            if (
                (n(),
                "string" == typeof e && (e = document.querySelector(e)),
                e && "object" == typeof e && e.nodeType)
            ) {
                var s = o(e);
                if ("none" == s.display) return i();
                var a = {};
                (a.width = e.offsetWidth), (a.height = e.offsetHeight);
                for (
                    var d = (a.isBorderBox = "border-box" == s.boxSizing),
                        l = 0;
                    l < h;
                    l++
                ) {
                    var f = u[l],
                        c = s[f],
                        m = parseFloat(c);
                    a[f] = isNaN(m) ? 0 : m;
                }
                var p = a.paddingLeft + a.paddingRight,
                    y = a.paddingTop + a.paddingBottom,
                    g = a.marginLeft + a.marginRight,
                    v = a.marginTop + a.marginBottom,
                    _ = a.borderLeftWidth + a.borderRightWidth,
                    z = a.borderTopWidth + a.borderBottomWidth,
                    I = d && r,
                    x = t(s.width);
                x !== !1 && (a.width = x + (I ? 0 : p + _));
                var S = t(s.height);
                return (
                    S !== !1 && (a.height = S + (I ? 0 : y + z)),
                    (a.innerWidth = a.width - (p + _)),
                    (a.innerHeight = a.height - (y + z)),
                    (a.outerWidth = a.width + g),
                    (a.outerHeight = a.height + v),
                    a
                );
            }
        }
        var r,
            a =
                "undefined" == typeof console
                    ? e
                    : function(t) {
                          console.error(t);
                      },
            u = [
                "paddingLeft",
                "paddingRight",
                "paddingTop",
                "paddingBottom",
                "marginLeft",
                "marginRight",
                "marginTop",
                "marginBottom",
                "borderLeftWidth",
                "borderRightWidth",
                "borderTopWidth",
                "borderBottomWidth"
            ],
            h = u.length,
            d = !1;
        return s;
    }),
    (function(t, e) {
        "use strict";
        "function" == typeof define && define.amd
            ? define("desandro-matches-selector/matches-selector", e)
            : "object" == typeof module && module.exports
            ? (module.exports = e())
            : (t.matchesSelector = e());
    })(window, function() {
        "use strict";
        var t = (function() {
            var t = window.Element.prototype;
            if (t.matches) return "matches";
            if (t.matchesSelector) return "matchesSelector";
            for (
                var e = ["webkit", "moz", "ms", "o"], i = 0;
                i < e.length;
                i++
            ) {
                var o = e[i],
                    n = o + "MatchesSelector";
                if (t[n]) return n;
            }
        })();
        return function(e, i) {
            return e[t](i);
        };
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("fizzy-ui-utils/utils", [
                  "desandro-matches-selector/matches-selector"
              ], function(i) {
                  return e(t, i);
              })
            : "object" == typeof module && module.exports
            ? (module.exports = e(t, require("desandro-matches-selector")))
            : (t.fizzyUIUtils = e(t, t.matchesSelector));
    })(window, function(t, e) {
        var i = {};
        (i.extend = function(t, e) {
            for (var i in e) t[i] = e[i];
            return t;
        }),
            (i.modulo = function(t, e) {
                return ((t % e) + e) % e;
            });
        var o = Array.prototype.slice;
        (i.makeArray = function(t) {
            if (Array.isArray(t)) return t;
            if (null === t || void 0 === t) return [];
            var e = "object" == typeof t && "number" == typeof t.length;
            return e ? o.call(t) : [t];
        }),
            (i.removeFrom = function(t, e) {
                var i = t.indexOf(e);
                i != -1 && t.splice(i, 1);
            }),
            (i.getParent = function(t, i) {
                for (; t.parentNode && t != document.body; )
                    if (((t = t.parentNode), e(t, i))) return t;
            }),
            (i.getQueryElement = function(t) {
                return "string" == typeof t ? document.querySelector(t) : t;
            }),
            (i.handleEvent = function(t) {
                var e = "on" + t.type;
                this[e] && this[e](t);
            }),
            (i.filterFindElements = function(t, o) {
                t = i.makeArray(t);
                var n = [];
                return (
                    t.forEach(function(t) {
                        if (t instanceof HTMLElement) {
                            if (!o) return void n.push(t);
                            e(t, o) && n.push(t);
                            for (
                                var i = t.querySelectorAll(o), s = 0;
                                s < i.length;
                                s++
                            )
                                n.push(i[s]);
                        }
                    }),
                    n
                );
            }),
            (i.debounceMethod = function(t, e, i) {
                i = i || 100;
                var o = t.prototype[e],
                    n = e + "Timeout";
                t.prototype[e] = function() {
                    var t = this[n];
                    clearTimeout(t);
                    var e = arguments,
                        s = this;
                    this[n] = setTimeout(function() {
                        o.apply(s, e), delete s[n];
                    }, i);
                };
            }),
            (i.docReady = function(t) {
                var e = document.readyState;
                "complete" == e || "interactive" == e
                    ? setTimeout(t)
                    : document.addEventListener("DOMContentLoaded", t);
            }),
            (i.toDashed = function(t) {
                return t
                    .replace(/(.)([A-Z])/g, function(t, e, i) {
                        return e + "-" + i;
                    })
                    .toLowerCase();
            });
        var n = t.console;
        return (
            (i.htmlInit = function(e, o) {
                i.docReady(function() {
                    var s = i.toDashed(o),
                        r = "data-" + s,
                        a = document.querySelectorAll("[" + r + "]"),
                        u = document.querySelectorAll(".js-" + s),
                        h = i.makeArray(a).concat(i.makeArray(u)),
                        d = r + "-options",
                        l = t.jQuery;
                    h.forEach(function(t) {
                        var i,
                            s = t.getAttribute(r) || t.getAttribute(d);
                        try {
                            i = s && JSON.parse(s);
                        } catch (a) {
                            return void (
                                n &&
                                n.error(
                                    "Error parsing " +
                                        r +
                                        " on " +
                                        t.className +
                                        ": " +
                                        a
                                )
                            );
                        }
                        var u = new e(t, i);
                        l && l.data(t, o, u);
                    });
                });
            }),
            i
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("outlayer/item", [
                  "ev-emitter/ev-emitter",
                  "get-size/get-size"
              ], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(require("ev-emitter"), require("get-size")))
            : ((t.Outlayer = {}),
              (t.Outlayer.Item = e(t.EvEmitter, t.getSize)));
    })(window, function(t, e) {
        "use strict";

        function i(t) {
            for (var e in t) return !1;
            return (e = null), !0;
        }

        function o(t, e) {
            t &&
                ((this.element = t),
                (this.layout = e),
                (this.position = {
                    x: 0,
                    y: 0
                }),
                this._create());
        }

        function n(t) {
            return t.replace(/([A-Z])/g, function(t) {
                return "-" + t.toLowerCase();
            });
        }
        var s = document.documentElement.style,
            r =
                "string" == typeof s.transition
                    ? "transition"
                    : "WebkitTransition",
            a =
                "string" == typeof s.transform
                    ? "transform"
                    : "WebkitTransform",
            u = {
                WebkitTransition: "webkitTransitionEnd",
                transition: "transitionend"
            }[r],
            h = {
                transform: a,
                transition: r,
                transitionDuration: r + "Duration",
                transitionProperty: r + "Property",
                transitionDelay: r + "Delay"
            },
            d = (o.prototype = Object.create(t.prototype));
        (d.constructor = o),
            (d._create = function() {
                (this._transn = {
                    ingProperties: {},
                    clean: {},
                    onEnd: {}
                }),
                    this.css({
                        position: "absolute"
                    });
            }),
            (d.handleEvent = function(t) {
                var e = "on" + t.type;
                this[e] && this[e](t);
            }),
            (d.getSize = function() {
                this.size = e(this.element);
            }),
            (d.css = function(t) {
                var e = this.element.style;
                for (var i in t) {
                    var o = h[i] || i;
                    e[o] = t[i];
                }
            }),
            (d.getPosition = function() {
                var t = getComputedStyle(this.element),
                    e = this.layout._getOption("originLeft"),
                    i = this.layout._getOption("originTop"),
                    o = t[e ? "left" : "right"],
                    n = t[i ? "top" : "bottom"],
                    s = parseFloat(o),
                    r = parseFloat(n),
                    a = this.layout.size;
                o.indexOf("%") != -1 && (s = (s / 100) * a.width),
                    n.indexOf("%") != -1 && (r = (r / 100) * a.height),
                    (s = isNaN(s) ? 0 : s),
                    (r = isNaN(r) ? 0 : r),
                    (s -= e ? a.paddingLeft : a.paddingRight),
                    (r -= i ? a.paddingTop : a.paddingBottom),
                    (this.position.x = s),
                    (this.position.y = r);
            }),
            (d.layoutPosition = function() {
                var t = this.layout.size,
                    e = {},
                    i = this.layout._getOption("originLeft"),
                    o = this.layout._getOption("originTop"),
                    n = i ? "paddingLeft" : "paddingRight",
                    s = i ? "left" : "right",
                    r = i ? "right" : "left",
                    a = this.position.x + t[n];
                (e[s] = this.getXValue(a)), (e[r] = "");
                var u = o ? "paddingTop" : "paddingBottom",
                    h = o ? "top" : "bottom",
                    d = o ? "bottom" : "top",
                    l = this.position.y + t[u];
                (e[h] = this.getYValue(l)),
                    (e[d] = ""),
                    this.css(e),
                    this.emitEvent("layout", [this]);
            }),
            (d.getXValue = function(t) {
                var e = this.layout._getOption("horizontal");
                return this.layout.options.percentPosition && !e
                    ? (t / this.layout.size.width) * 100 + "%"
                    : t + "px";
            }),
            (d.getYValue = function(t) {
                var e = this.layout._getOption("horizontal");
                return this.layout.options.percentPosition && e
                    ? (t / this.layout.size.height) * 100 + "%"
                    : t + "px";
            }),
            (d._transitionTo = function(t, e) {
                this.getPosition();
                var i = this.position.x,
                    o = this.position.y,
                    n = t == this.position.x && e == this.position.y;
                if ((this.setPosition(t, e), n && !this.isTransitioning))
                    return void this.layoutPosition();
                var s = t - i,
                    r = e - o,
                    a = {};
                (a.transform = this.getTranslate(s, r)),
                    this.transition({
                        to: a,
                        onTransitionEnd: {
                            transform: this.layoutPosition
                        },
                        isCleaning: !0
                    });
            }),
            (d.getTranslate = function(t, e) {
                var i = this.layout._getOption("originLeft"),
                    o = this.layout._getOption("originTop");
                return (
                    (t = i ? t : -t),
                    (e = o ? e : -e),
                    "translate3d(" + t + "px, " + e + "px, 0)"
                );
            }),
            (d.goTo = function(t, e) {
                this.setPosition(t, e), this.layoutPosition();
            }),
            (d.moveTo = d._transitionTo),
            (d.setPosition = function(t, e) {
                (this.position.x = parseFloat(t)),
                    (this.position.y = parseFloat(e));
            }),
            (d._nonTransition = function(t) {
                this.css(t.to), t.isCleaning && this._removeStyles(t.to);
                for (var e in t.onTransitionEnd)
                    t.onTransitionEnd[e].call(this);
            }),
            (d.transition = function(t) {
                if (!parseFloat(this.layout.options.transitionDuration))
                    return void this._nonTransition(t);
                var e = this._transn;
                for (var i in t.onTransitionEnd)
                    e.onEnd[i] = t.onTransitionEnd[i];
                for (i in t.to)
                    (e.ingProperties[i] = !0),
                        t.isCleaning && (e.clean[i] = !0);
                if (t.from) {
                    this.css(t.from);
                    var o = this.element.offsetHeight;
                    o = null;
                }
                this.enableTransition(t.to),
                    this.css(t.to),
                    (this.isTransitioning = !0);
            });
        var l = "opacity," + n(a);
        (d.enableTransition = function() {
            if (!this.isTransitioning) {
                var t = this.layout.options.transitionDuration;
                (t = "number" == typeof t ? t + "ms" : t),
                    this.css({
                        transitionProperty: l,
                        transitionDuration: t,
                        transitionDelay: this.staggerDelay || 0
                    }),
                    this.element.addEventListener(u, this, !1);
            }
        }),
            (d.onwebkitTransitionEnd = function(t) {
                this.ontransitionend(t);
            }),
            (d.onotransitionend = function(t) {
                this.ontransitionend(t);
            });
        var f = {
            "-webkit-transform": "transform"
        };
        (d.ontransitionend = function(t) {
            if (t.target === this.element) {
                var e = this._transn,
                    o = f[t.propertyName] || t.propertyName;
                if (
                    (delete e.ingProperties[o],
                    i(e.ingProperties) && this.disableTransition(),
                    o in e.clean &&
                        ((this.element.style[t.propertyName] = ""),
                        delete e.clean[o]),
                    o in e.onEnd)
                ) {
                    var n = e.onEnd[o];
                    n.call(this), delete e.onEnd[o];
                }
                this.emitEvent("transitionEnd", [this]);
            }
        }),
            (d.disableTransition = function() {
                this.removeTransitionStyles(),
                    this.element.removeEventListener(u, this, !1),
                    (this.isTransitioning = !1);
            }),
            (d._removeStyles = function(t) {
                var e = {};
                for (var i in t) e[i] = "";
                this.css(e);
            });
        var c = {
            transitionProperty: "",
            transitionDuration: "",
            transitionDelay: ""
        };
        return (
            (d.removeTransitionStyles = function() {
                this.css(c);
            }),
            (d.stagger = function(t) {
                (t = isNaN(t) ? 0 : t), (this.staggerDelay = t + "ms");
            }),
            (d.removeElem = function() {
                this.element.parentNode.removeChild(this.element),
                    this.css({
                        display: ""
                    }),
                    this.emitEvent("remove", [this]);
            }),
            (d.remove = function() {
                return r && parseFloat(this.layout.options.transitionDuration)
                    ? (this.once("transitionEnd", function() {
                          this.removeElem();
                      }),
                      void this.hide())
                    : void this.removeElem();
            }),
            (d.reveal = function() {
                delete this.isHidden,
                    this.css({
                        display: ""
                    });
                var t = this.layout.options,
                    e = {},
                    i = this.getHideRevealTransitionEndProperty("visibleStyle");
                (e[i] = this.onRevealTransitionEnd),
                    this.transition({
                        from: t.hiddenStyle,
                        to: t.visibleStyle,
                        isCleaning: !0,
                        onTransitionEnd: e
                    });
            }),
            (d.onRevealTransitionEnd = function() {
                this.isHidden || this.emitEvent("reveal");
            }),
            (d.getHideRevealTransitionEndProperty = function(t) {
                var e = this.layout.options[t];
                if (e.opacity) return "opacity";
                for (var i in e) return i;
            }),
            (d.hide = function() {
                (this.isHidden = !0),
                    this.css({
                        display: ""
                    });
                var t = this.layout.options,
                    e = {},
                    i = this.getHideRevealTransitionEndProperty("hiddenStyle");
                (e[i] = this.onHideTransitionEnd),
                    this.transition({
                        from: t.visibleStyle,
                        to: t.hiddenStyle,
                        isCleaning: !0,
                        onTransitionEnd: e
                    });
            }),
            (d.onHideTransitionEnd = function() {
                this.isHidden &&
                    (this.css({
                        display: "none"
                    }),
                    this.emitEvent("hide"));
            }),
            (d.destroy = function() {
                this.css({
                    position: "",
                    left: "",
                    right: "",
                    top: "",
                    bottom: "",
                    transition: "",
                    transform: ""
                });
            }),
            o
        );
    }),
    (function(t, e) {
        "use strict";
        "function" == typeof define && define.amd
            ? define("outlayer/outlayer", [
                  "ev-emitter/ev-emitter",
                  "get-size/get-size",
                  "fizzy-ui-utils/utils",
                  "./item"
              ], function(i, o, n, s) {
                  return e(t, i, o, n, s);
              })
            : "object" == typeof module && module.exports
            ? (module.exports = e(
                  t,
                  require("ev-emitter"),
                  require("get-size"),
                  require("fizzy-ui-utils"),
                  require("./item")
              ))
            : (t.Outlayer = e(
                  t,
                  t.EvEmitter,
                  t.getSize,
                  t.fizzyUIUtils,
                  t.Outlayer.Item
              ));
    })(window, function(t, e, i, o, n) {
        "use strict";

        function s(t, e) {
            var i = o.getQueryElement(t);
            if (!i)
                return void (
                    u &&
                    u.error(
                        "Bad element for " +
                            this.constructor.namespace +
                            ": " +
                            (i || t)
                    )
                );
            (this.element = i),
                h && (this.$element = h(this.element)),
                (this.options = o.extend({}, this.constructor.defaults)),
                this.option(e);
            var n = ++l;
            (this.element.outlayerGUID = n), (f[n] = this), this._create();
            var s = this._getOption("initLayout");
            s && this.layout();
        }

        function r(t) {
            function e() {
                t.apply(this, arguments);
            }
            return (
                (e.prototype = Object.create(t.prototype)),
                (e.prototype.constructor = e),
                e
            );
        }

        function a(t) {
            if ("number" == typeof t) return t;
            var e = t.match(/(^\d*\.?\d*)(\w*)/),
                i = e && e[1],
                o = e && e[2];
            if (!i.length) return 0;
            i = parseFloat(i);
            var n = m[o] || 1;
            return i * n;
        }
        var u = t.console,
            h = t.jQuery,
            d = function() {},
            l = 0,
            f = {};
        (s.namespace = "outlayer"),
            (s.Item = n),
            (s.defaults = {
                containerStyle: {
                    position: "relative"
                },
                initLayout: !0,
                originLeft: !0,
                originTop: !0,
                resize: !0,
                resizeContainer: !0,
                transitionDuration: "0.4s",
                hiddenStyle: {
                    opacity: 0,
                    transform: "scale(0.001)"
                },
                visibleStyle: {
                    opacity: 1,
                    transform: "scale(1)"
                }
            });
        var c = s.prototype;
        o.extend(c, e.prototype),
            (c.option = function(t) {
                o.extend(this.options, t);
            }),
            (c._getOption = function(t) {
                var e = this.constructor.compatOptions[t];
                return e && void 0 !== this.options[e]
                    ? this.options[e]
                    : this.options[t];
            }),
            (s.compatOptions = {
                initLayout: "isInitLayout",
                horizontal: "isHorizontal",
                layoutInstant: "isLayoutInstant",
                originLeft: "isOriginLeft",
                originTop: "isOriginTop",
                resize: "isResizeBound",
                resizeContainer: "isResizingContainer"
            }),
            (c._create = function() {
                this.reloadItems(),
                    (this.stamps = []),
                    this.stamp(this.options.stamp),
                    o.extend(this.element.style, this.options.containerStyle);
                var t = this._getOption("resize");
                t && this.bindResize();
            }),
            (c.reloadItems = function() {
                this.items = this._itemize(this.element.children);
            }),
            (c._itemize = function(t) {
                for (
                    var e = this._filterFindItemElements(t),
                        i = this.constructor.Item,
                        o = [],
                        n = 0;
                    n < e.length;
                    n++
                ) {
                    var s = e[n],
                        r = new i(s, this);
                    o.push(r);
                }
                return o;
            }),
            (c._filterFindItemElements = function(t) {
                return o.filterFindElements(t, this.options.itemSelector);
            }),
            (c.getItemElements = function() {
                return this.items.map(function(t) {
                    return t.element;
                });
            }),
            (c.layout = function() {
                this._resetLayout(), this._manageStamps();
                var t = this._getOption("layoutInstant"),
                    e = void 0 !== t ? t : !this._isLayoutInited;
                this.layoutItems(this.items, e), (this._isLayoutInited = !0);
            }),
            (c._init = c.layout),
            (c._resetLayout = function() {
                this.getSize();
            }),
            (c.getSize = function() {
                this.size = i(this.element);
            }),
            (c._getMeasurement = function(t, e) {
                var o,
                    n = this.options[t];
                n
                    ? ("string" == typeof n
                          ? (o = this.element.querySelector(n))
                          : n instanceof HTMLElement && (o = n),
                      (this[t] = o ? i(o)[e] : n))
                    : (this[t] = 0);
            }),
            (c.layoutItems = function(t, e) {
                (t = this._getItemsForLayout(t)),
                    this._layoutItems(t, e),
                    this._postLayout();
            }),
            (c._getItemsForLayout = function(t) {
                return t.filter(function(t) {
                    return !t.isIgnored;
                });
            }),
            (c._layoutItems = function(t, e) {
                if ((this._emitCompleteOnItems("layout", t), t && t.length)) {
                    var i = [];
                    t.forEach(function(t) {
                        var o = this._getItemLayoutPosition(t);
                        (o.item = t),
                            (o.isInstant = e || t.isLayoutInstant),
                            i.push(o);
                    }, this),
                        this._processLayoutQueue(i);
                }
            }),
            (c._getItemLayoutPosition = function() {
                return {
                    x: 0,
                    y: 0
                };
            }),
            (c._processLayoutQueue = function(t) {
                this.updateStagger(),
                    t.forEach(function(t, e) {
                        this._positionItem(t.item, t.x, t.y, t.isInstant, e);
                    }, this);
            }),
            (c.updateStagger = function() {
                var t = this.options.stagger;
                return null === t || void 0 === t
                    ? void (this.stagger = 0)
                    : ((this.stagger = a(t)), this.stagger);
            }),
            (c._positionItem = function(t, e, i, o, n) {
                o
                    ? t.goTo(e, i)
                    : (t.stagger(n * this.stagger), t.moveTo(e, i));
            }),
            (c._postLayout = function() {
                this.resizeContainer();
            }),
            (c.resizeContainer = function() {
                var t = this._getOption("resizeContainer");
                if (t) {
                    var e = this._getContainerSize();
                    e &&
                        (this._setContainerMeasure(e.width, !0),
                        this._setContainerMeasure(e.height, !1));
                }
            }),
            (c._getContainerSize = d),
            (c._setContainerMeasure = function(t, e) {
                if (void 0 !== t) {
                    var i = this.size;
                    i.isBorderBox &&
                        (t += e
                            ? i.paddingLeft +
                              i.paddingRight +
                              i.borderLeftWidth +
                              i.borderRightWidth
                            : i.paddingBottom +
                              i.paddingTop +
                              i.borderTopWidth +
                              i.borderBottomWidth),
                        (t = Math.max(t, 0)),
                        (this.element.style[e ? "width" : "height"] = t + "px");
                }
            }),
            (c._emitCompleteOnItems = function(t, e) {
                function i() {
                    n.dispatchEvent(t + "Complete", null, [e]);
                }

                function o() {
                    r++, r == s && i();
                }
                var n = this,
                    s = e.length;
                if (!e || !s) return void i();
                var r = 0;
                e.forEach(function(e) {
                    e.once(t, o);
                });
            }),
            (c.dispatchEvent = function(t, e, i) {
                var o = e ? [e].concat(i) : i;
                if ((this.emitEvent(t, o), h))
                    if (
                        ((this.$element = this.$element || h(this.element)), e)
                    ) {
                        var n = h.Event(e);
                        (n.type = t), this.$element.trigger(n, i);
                    } else this.$element.trigger(t, i);
            }),
            (c.ignore = function(t) {
                var e = this.getItem(t);
                e && (e.isIgnored = !0);
            }),
            (c.unignore = function(t) {
                var e = this.getItem(t);
                e && delete e.isIgnored;
            }),
            (c.stamp = function(t) {
                (t = this._find(t)),
                    t &&
                        ((this.stamps = this.stamps.concat(t)),
                        t.forEach(this.ignore, this));
            }),
            (c.unstamp = function(t) {
                (t = this._find(t)),
                    t &&
                        t.forEach(function(t) {
                            o.removeFrom(this.stamps, t), this.unignore(t);
                        }, this);
            }),
            (c._find = function(t) {
                if (t)
                    return (
                        "string" == typeof t &&
                            (t = this.element.querySelectorAll(t)),
                        (t = o.makeArray(t))
                    );
            }),
            (c._manageStamps = function() {
                this.stamps &&
                    this.stamps.length &&
                    (this._getBoundingRect(),
                    this.stamps.forEach(this._manageStamp, this));
            }),
            (c._getBoundingRect = function() {
                var t = this.element.getBoundingClientRect(),
                    e = this.size;
                this._boundingRect = {
                    left: t.left + e.paddingLeft + e.borderLeftWidth,
                    top: t.top + e.paddingTop + e.borderTopWidth,
                    right: t.right - (e.paddingRight + e.borderRightWidth),
                    bottom: t.bottom - (e.paddingBottom + e.borderBottomWidth)
                };
            }),
            (c._manageStamp = d),
            (c._getElementOffset = function(t) {
                var e = t.getBoundingClientRect(),
                    o = this._boundingRect,
                    n = i(t),
                    s = {
                        left: e.left - o.left - n.marginLeft,
                        top: e.top - o.top - n.marginTop,
                        right: o.right - e.right - n.marginRight,
                        bottom: o.bottom - e.bottom - n.marginBottom
                    };
                return s;
            }),
            (c.handleEvent = o.handleEvent),
            (c.bindResize = function() {
                t.addEventListener("resize", this), (this.isResizeBound = !0);
            }),
            (c.unbindResize = function() {
                t.removeEventListener("resize", this),
                    (this.isResizeBound = !1);
            }),
            (c.onresize = function() {
                this.resize();
            }),
            o.debounceMethod(s, "onresize", 100),
            (c.resize = function() {
                this.isResizeBound && this.needsResizeLayout() && this.layout();
            }),
            (c.needsResizeLayout = function() {
                var t = i(this.element),
                    e = this.size && t;
                return e && t.innerWidth !== this.size.innerWidth;
            }),
            (c.addItems = function(t) {
                var e = this._itemize(t);
                return e.length && (this.items = this.items.concat(e)), e;
            }),
            (c.appended = function(t) {
                var e = this.addItems(t);
                e.length && (this.layoutItems(e, !0), this.reveal(e));
            }),
            (c.prepended = function(t) {
                var e = this._itemize(t);
                if (e.length) {
                    var i = this.items.slice(0);
                    (this.items = e.concat(i)),
                        this._resetLayout(),
                        this._manageStamps(),
                        this.layoutItems(e, !0),
                        this.reveal(e),
                        this.layoutItems(i);
                }
            }),
            (c.reveal = function(t) {
                if ((this._emitCompleteOnItems("reveal", t), t && t.length)) {
                    var e = this.updateStagger();
                    t.forEach(function(t, i) {
                        t.stagger(i * e), t.reveal();
                    });
                }
            }),
            (c.hide = function(t) {
                if ((this._emitCompleteOnItems("hide", t), t && t.length)) {
                    var e = this.updateStagger();
                    t.forEach(function(t, i) {
                        t.stagger(i * e), t.hide();
                    });
                }
            }),
            (c.revealItemElements = function(t) {
                var e = this.getItems(t);
                this.reveal(e);
            }),
            (c.hideItemElements = function(t) {
                var e = this.getItems(t);
                this.hide(e);
            }),
            (c.getItem = function(t) {
                for (var e = 0; e < this.items.length; e++) {
                    var i = this.items[e];
                    if (i.element == t) return i;
                }
            }),
            (c.getItems = function(t) {
                t = o.makeArray(t);
                var e = [];
                return (
                    t.forEach(function(t) {
                        var i = this.getItem(t);
                        i && e.push(i);
                    }, this),
                    e
                );
            }),
            (c.remove = function(t) {
                var e = this.getItems(t);
                this._emitCompleteOnItems("remove", e),
                    e &&
                        e.length &&
                        e.forEach(function(t) {
                            t.remove(), o.removeFrom(this.items, t);
                        }, this);
            }),
            (c.destroy = function() {
                var t = this.element.style;
                (t.height = ""),
                    (t.position = ""),
                    (t.width = ""),
                    this.items.forEach(function(t) {
                        t.destroy();
                    }),
                    this.unbindResize();
                var e = this.element.outlayerGUID;
                delete f[e],
                    delete this.element.outlayerGUID,
                    h && h.removeData(this.element, this.constructor.namespace);
            }),
            (s.data = function(t) {
                t = o.getQueryElement(t);
                var e = t && t.outlayerGUID;
                return e && f[e];
            }),
            (s.create = function(t, e) {
                var i = r(s);
                return (
                    (i.defaults = o.extend({}, s.defaults)),
                    o.extend(i.defaults, e),
                    (i.compatOptions = o.extend({}, s.compatOptions)),
                    (i.namespace = t),
                    (i.data = s.data),
                    (i.Item = r(n)),
                    o.htmlInit(i, t),
                    h && h.bridget && h.bridget(t, i),
                    i
                );
            });
        var m = {
            ms: 1,
            s: 1e3
        };
        return (s.Item = n), s;
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("isotope-layout/js/item", ["outlayer/outlayer"], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(require("outlayer")))
            : ((t.Isotope = t.Isotope || {}), (t.Isotope.Item = e(t.Outlayer)));
    })(window, function(t) {
        "use strict";

        function e() {
            t.Item.apply(this, arguments);
        }
        var i = (e.prototype = Object.create(t.Item.prototype)),
            o = i._create;
        (i._create = function() {
            (this.id = this.layout.itemGUID++),
                o.call(this),
                (this.sortData = {});
        }),
            (i.updateSortData = function() {
                if (!this.isIgnored) {
                    (this.sortData.id = this.id),
                        (this.sortData["original-order"] = this.id),
                        (this.sortData.random = Math.random());
                    var t = this.layout.options.getSortData,
                        e = this.layout._sorters;
                    for (var i in t) {
                        var o = e[i];
                        this.sortData[i] = o(this.element, this);
                    }
                }
            });
        var n = i.destroy;
        return (
            (i.destroy = function() {
                n.apply(this, arguments),
                    this.css({
                        display: ""
                    });
            }),
            e
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("isotope-layout/js/layout-mode", [
                  "get-size/get-size",
                  "outlayer/outlayer"
              ], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(require("get-size"), require("outlayer")))
            : ((t.Isotope = t.Isotope || {}),
              (t.Isotope.LayoutMode = e(t.getSize, t.Outlayer)));
    })(window, function(t, e) {
        "use strict";

        function i(t) {
            (this.isotope = t),
                t &&
                    ((this.options = t.options[this.namespace]),
                    (this.element = t.element),
                    (this.items = t.filteredItems),
                    (this.size = t.size));
        }
        var o = i.prototype,
            n = [
                "_resetLayout",
                "_getItemLayoutPosition",
                "_manageStamp",
                "_getContainerSize",
                "_getElementOffset",
                "needsResizeLayout",
                "_getOption"
            ];
        return (
            n.forEach(function(t) {
                o[t] = function() {
                    return e.prototype[t].apply(this.isotope, arguments);
                };
            }),
            (o.needsVerticalResizeLayout = function() {
                var e = t(this.isotope.element),
                    i = this.isotope.size && e;
                return i && e.innerHeight != this.isotope.size.innerHeight;
            }),
            (o._getMeasurement = function() {
                this.isotope._getMeasurement.apply(this, arguments);
            }),
            (o.getColumnWidth = function() {
                this.getSegmentSize("column", "Width");
            }),
            (o.getRowHeight = function() {
                this.getSegmentSize("row", "Height");
            }),
            (o.getSegmentSize = function(t, e) {
                var i = t + e,
                    o = "outer" + e;
                if ((this._getMeasurement(i, o), !this[i])) {
                    var n = this.getFirstItemSize();
                    this[i] = (n && n[o]) || this.isotope.size["inner" + e];
                }
            }),
            (o.getFirstItemSize = function() {
                var e = this.isotope.filteredItems[0];
                return e && e.element && t(e.element);
            }),
            (o.layout = function() {
                this.isotope.layout.apply(this.isotope, arguments);
            }),
            (o.getSize = function() {
                this.isotope.getSize(), (this.size = this.isotope.size);
            }),
            (i.modes = {}),
            (i.create = function(t, e) {
                function n() {
                    i.apply(this, arguments);
                }
                return (
                    (n.prototype = Object.create(o)),
                    (n.prototype.constructor = n),
                    e && (n.options = e),
                    (n.prototype.namespace = t),
                    (i.modes[t] = n),
                    n
                );
            }),
            i
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("masonry-layout/masonry", [
                  "outlayer/outlayer",
                  "get-size/get-size"
              ], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(require("outlayer"), require("get-size")))
            : (t.Masonry = e(t.Outlayer, t.getSize));
    })(window, function(t, e) {
        var i = t.create("masonry");
        i.compatOptions.fitWidth = "isFitWidth";
        var o = i.prototype;
        return (
            (o._resetLayout = function() {
                this.getSize(),
                    this._getMeasurement("columnWidth", "outerWidth"),
                    this._getMeasurement("gutter", "outerWidth"),
                    this.measureColumns(),
                    (this.colYs = []);
                for (var t = 0; t < this.cols; t++) this.colYs.push(0);
                (this.maxY = 0), (this.horizontalColIndex = 0);
            }),
            (o.measureColumns = function() {
                if ((this.getContainerWidth(), !this.columnWidth)) {
                    var t = this.items[0],
                        i = t && t.element;
                    this.columnWidth =
                        (i && e(i).outerWidth) || this.containerWidth;
                }
                var o = (this.columnWidth += this.gutter),
                    n = this.containerWidth + this.gutter,
                    s = n / o,
                    r = o - (n % o),
                    a = r && r < 1 ? "round" : "floor";
                (s = Math[a](s)), (this.cols = Math.max(s, 1));
            }),
            (o.getContainerWidth = function() {
                var t = this._getOption("fitWidth"),
                    i = t ? this.element.parentNode : this.element,
                    o = e(i);
                this.containerWidth = o && o.innerWidth;
            }),
            (o._getItemLayoutPosition = function(t) {
                t.getSize();
                var e = t.size.outerWidth % this.columnWidth,
                    i = e && e < 1 ? "round" : "ceil",
                    o = Math[i](t.size.outerWidth / this.columnWidth);
                o = Math.min(o, this.cols);
                for (
                    var n = this.options.horizontalOrder
                            ? "_getHorizontalColPosition"
                            : "_getTopColPosition",
                        s = this[n](o, t),
                        r = {
                            x: this.columnWidth * s.col,
                            y: s.y
                        },
                        a = s.y + t.size.outerHeight,
                        u = o + s.col,
                        h = s.col;
                    h < u;
                    h++
                )
                    this.colYs[h] = a;
                return r;
            }),
            (o._getTopColPosition = function(t) {
                var e = this._getTopColGroup(t),
                    i = Math.min.apply(Math, e);
                return {
                    col: e.indexOf(i),
                    y: i
                };
            }),
            (o._getTopColGroup = function(t) {
                if (t < 2) return this.colYs;
                for (var e = [], i = this.cols + 1 - t, o = 0; o < i; o++)
                    e[o] = this._getColGroupY(o, t);
                return e;
            }),
            (o._getColGroupY = function(t, e) {
                if (e < 2) return this.colYs[t];
                var i = this.colYs.slice(t, t + e);
                return Math.max.apply(Math, i);
            }),
            (o._getHorizontalColPosition = function(t, e) {
                var i = this.horizontalColIndex % this.cols,
                    o = t > 1 && i + t > this.cols;
                i = o ? 0 : i;
                var n = e.size.outerWidth && e.size.outerHeight;
                return (
                    (this.horizontalColIndex = n
                        ? i + t
                        : this.horizontalColIndex),
                    {
                        col: i,
                        y: this._getColGroupY(i, t)
                    }
                );
            }),
            (o._manageStamp = function(t) {
                var i = e(t),
                    o = this._getElementOffset(t),
                    n = this._getOption("originLeft"),
                    s = n ? o.left : o.right,
                    r = s + i.outerWidth,
                    a = Math.floor(s / this.columnWidth);
                a = Math.max(0, a);
                var u = Math.floor(r / this.columnWidth);
                (u -= r % this.columnWidth ? 0 : 1),
                    (u = Math.min(this.cols - 1, u));
                for (
                    var h = this._getOption("originTop"),
                        d = (h ? o.top : o.bottom) + i.outerHeight,
                        l = a;
                    l <= u;
                    l++
                )
                    this.colYs[l] = Math.max(d, this.colYs[l]);
            }),
            (o._getContainerSize = function() {
                this.maxY = Math.max.apply(Math, this.colYs);
                var t = {
                    height: this.maxY
                };
                return (
                    this._getOption("fitWidth") &&
                        (t.width = this._getContainerFitWidth()),
                    t
                );
            }),
            (o._getContainerFitWidth = function() {
                for (var t = 0, e = this.cols; --e && 0 === this.colYs[e]; )
                    t++;
                return (this.cols - t) * this.columnWidth - this.gutter;
            }),
            (o.needsResizeLayout = function() {
                var t = this.containerWidth;
                return this.getContainerWidth(), t != this.containerWidth;
            }),
            i
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("isotope-layout/js/layout-modes/masonry", [
                  "../layout-mode",
                  "masonry-layout/masonry"
              ], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(
                  require("../layout-mode"),
                  require("masonry-layout")
              ))
            : e(t.Isotope.LayoutMode, t.Masonry);
    })(window, function(t, e) {
        "use strict";
        var i = t.create("masonry"),
            o = i.prototype,
            n = {
                _getElementOffset: !0,
                layout: !0,
                _getMeasurement: !0
            };
        for (var s in e.prototype) n[s] || (o[s] = e.prototype[s]);
        var r = o.measureColumns;
        o.measureColumns = function() {
            (this.items = this.isotope.filteredItems), r.call(this);
        };
        var a = o._getOption;
        return (
            (o._getOption = function(t) {
                return "fitWidth" == t
                    ? void 0 !== this.options.isFitWidth
                        ? this.options.isFitWidth
                        : this.options.fitWidth
                    : a.apply(this.isotope, arguments);
            }),
            i
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("isotope-layout/js/layout-modes/fit-rows", [
                  "../layout-mode"
              ], e)
            : "object" == typeof exports
            ? (module.exports = e(require("../layout-mode")))
            : e(t.Isotope.LayoutMode);
    })(window, function(t) {
        "use strict";
        var e = t.create("fitRows"),
            i = e.prototype;
        return (
            (i._resetLayout = function() {
                (this.x = 0),
                    (this.y = 0),
                    (this.maxY = 0),
                    this._getMeasurement("gutter", "outerWidth");
            }),
            (i._getItemLayoutPosition = function(t) {
                t.getSize();
                var e = t.size.outerWidth + this.gutter,
                    i = this.isotope.size.innerWidth + this.gutter;
                0 !== this.x &&
                    e + this.x > i &&
                    ((this.x = 0), (this.y = this.maxY));
                var o = {
                    x: this.x,
                    y: this.y
                };
                return (
                    (this.maxY = Math.max(
                        this.maxY,
                        this.y + t.size.outerHeight
                    )),
                    (this.x += e),
                    o
                );
            }),
            (i._getContainerSize = function() {
                return {
                    height: this.maxY
                };
            }),
            e
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define("isotope-layout/js/layout-modes/vertical", [
                  "../layout-mode"
              ], e)
            : "object" == typeof module && module.exports
            ? (module.exports = e(require("../layout-mode")))
            : e(t.Isotope.LayoutMode);
    })(window, function(t) {
        "use strict";
        var e = t.create("vertical", {
                horizontalAlignment: 0
            }),
            i = e.prototype;
        return (
            (i._resetLayout = function() {
                this.y = 0;
            }),
            (i._getItemLayoutPosition = function(t) {
                t.getSize();
                var e =
                        (this.isotope.size.innerWidth - t.size.outerWidth) *
                        this.options.horizontalAlignment,
                    i = this.y;
                return (
                    (this.y += t.size.outerHeight),
                    {
                        x: e,
                        y: i
                    }
                );
            }),
            (i._getContainerSize = function() {
                return {
                    height: this.y
                };
            }),
            e
        );
    }),
    (function(t, e) {
        "function" == typeof define && define.amd
            ? define([
                  "outlayer/outlayer",
                  "get-size/get-size",
                  "desandro-matches-selector/matches-selector",
                  "fizzy-ui-utils/utils",
                  "isotope-layout/js/item",
                  "isotope-layout/js/layout-mode",
                  "isotope-layout/js/layout-modes/masonry",
                  "isotope-layout/js/layout-modes/fit-rows",
                  "isotope-layout/js/layout-modes/vertical"
              ], function(i, o, n, s, r, a) {
                  return e(t, i, o, n, s, r, a);
              })
            : "object" == typeof module && module.exports
            ? (module.exports = e(
                  t,
                  require("outlayer"),
                  require("get-size"),
                  require("desandro-matches-selector"),
                  require("fizzy-ui-utils"),
                  require("isotope-layout/js/item"),
                  require("isotope-layout/js/layout-mode"),
                  require("isotope-layout/js/layout-modes/masonry"),
                  require("isotope-layout/js/layout-modes/fit-rows"),
                  require("isotope-layout/js/layout-modes/vertical")
              ))
            : (t.Isotope = e(
                  t,
                  t.Outlayer,
                  t.getSize,
                  t.matchesSelector,
                  t.fizzyUIUtils,
                  t.Isotope.Item,
                  t.Isotope.LayoutMode
              ));
    })(window, function(t, e, i, o, n, s, r) {
        function a(t, e) {
            return function(i, o) {
                for (var n = 0; n < t.length; n++) {
                    var s = t[n],
                        r = i.sortData[s],
                        a = o.sortData[s];
                    if (r > a || r < a) {
                        var u = void 0 !== e[s] ? e[s] : e,
                            h = u ? 1 : -1;
                        return (r > a ? 1 : -1) * h;
                    }
                }
                return 0;
            };
        }
        var u = t.jQuery,
            h = String.prototype.trim
                ? function(t) {
                      return t.trim();
                  }
                : function(t) {
                      return t.replace(/^\s+|\s+$/g, "");
                  },
            d = e.create("isotope", {
                layoutMode: "masonry",
                isJQueryFiltering: !0,
                sortAscending: !0
            });
        (d.Item = s), (d.LayoutMode = r);
        var l = d.prototype;
        (l._create = function() {
            (this.itemGUID = 0),
                (this._sorters = {}),
                this._getSorters(),
                e.prototype._create.call(this),
                (this.modes = {}),
                (this.filteredItems = this.items),
                (this.sortHistory = ["original-order"]);
            for (var t in r.modes) this._initLayoutMode(t);
        }),
            (l.reloadItems = function() {
                (this.itemGUID = 0), e.prototype.reloadItems.call(this);
            }),
            (l._itemize = function() {
                for (
                    var t = e.prototype._itemize.apply(this, arguments), i = 0;
                    i < t.length;
                    i++
                ) {
                    var o = t[i];
                    o.id = this.itemGUID++;
                }
                return this._updateItemsSortData(t), t;
            }),
            (l._initLayoutMode = function(t) {
                var e = r.modes[t],
                    i = this.options[t] || {};
                (this.options[t] = e.options ? n.extend(e.options, i) : i),
                    (this.modes[t] = new e(this));
            }),
            (l.layout = function() {
                return !this._isLayoutInited && this._getOption("initLayout")
                    ? void this.arrange()
                    : void this._layout();
            }),
            (l._layout = function() {
                var t = this._getIsInstant();
                this._resetLayout(),
                    this._manageStamps(),
                    this.layoutItems(this.filteredItems, t),
                    (this._isLayoutInited = !0);
            }),
            (l.arrange = function(t) {
                this.option(t), this._getIsInstant();
                var e = this._filter(this.items);
                (this.filteredItems = e.matches),
                    this._bindArrangeComplete(),
                    this._isInstant
                        ? this._noTransition(this._hideReveal, [e])
                        : this._hideReveal(e),
                    this._sort(),
                    this._layout();
            }),
            (l._init = l.arrange),
            (l._hideReveal = function(t) {
                this.reveal(t.needReveal), this.hide(t.needHide);
            }),
            (l._getIsInstant = function() {
                var t = this._getOption("layoutInstant"),
                    e = void 0 !== t ? t : !this._isLayoutInited;
                return (this._isInstant = e), e;
            }),
            (l._bindArrangeComplete = function() {
                function t() {
                    e &&
                        i &&
                        o &&
                        n.dispatchEvent("arrangeComplete", null, [
                            n.filteredItems
                        ]);
                }
                var e,
                    i,
                    o,
                    n = this;
                this.once("layoutComplete", function() {
                    (e = !0), t();
                }),
                    this.once("hideComplete", function() {
                        (i = !0), t();
                    }),
                    this.once("revealComplete", function() {
                        (o = !0), t();
                    });
            }),
            (l._filter = function(t) {
                var e = this.options.filter;
                e = e || "*";
                for (
                    var i = [],
                        o = [],
                        n = [],
                        s = this._getFilterTest(e),
                        r = 0;
                    r < t.length;
                    r++
                ) {
                    var a = t[r];
                    if (!a.isIgnored) {
                        var u = s(a);
                        u && i.push(a),
                            u && a.isHidden
                                ? o.push(a)
                                : u || a.isHidden || n.push(a);
                    }
                }
                return {
                    matches: i,
                    needReveal: o,
                    needHide: n
                };
            }),
            (l._getFilterTest = function(t) {
                return u && this.options.isJQueryFiltering
                    ? function(e) {
                          return u(e.element).is(t);
                      }
                    : "function" == typeof t
                    ? function(e) {
                          return t(e.element);
                      }
                    : function(e) {
                          return o(e.element, t);
                      };
            }),
            (l.updateSortData = function(t) {
                var e;
                t
                    ? ((t = n.makeArray(t)), (e = this.getItems(t)))
                    : (e = this.items),
                    this._getSorters(),
                    this._updateItemsSortData(e);
            }),
            (l._getSorters = function() {
                var t = this.options.getSortData;
                for (var e in t) {
                    var i = t[e];
                    this._sorters[e] = f(i);
                }
            }),
            (l._updateItemsSortData = function(t) {
                for (var e = t && t.length, i = 0; e && i < e; i++) {
                    var o = t[i];
                    o.updateSortData();
                }
            });
        var f = (function() {
            function t(t) {
                if ("string" != typeof t) return t;
                var i = h(t).split(" "),
                    o = i[0],
                    n = o.match(/^\[(.+)\]$/),
                    s = n && n[1],
                    r = e(s, o),
                    a = d.sortDataParsers[i[1]];
                return (t = a
                    ? function(t) {
                          return t && a(r(t));
                      }
                    : function(t) {
                          return t && r(t);
                      });
            }

            function e(t, e) {
                return t
                    ? function(e) {
                          return e.getAttribute(t);
                      }
                    : function(t) {
                          var i = t.querySelector(e);
                          return i && i.textContent;
                      };
            }
            return t;
        })();
        (d.sortDataParsers = {
            parseInt: function(t) {
                return parseInt(t, 10);
            },
            parseFloat: function(t) {
                return parseFloat(t);
            }
        }),
            (l._sort = function() {
                if (this.options.sortBy) {
                    var t = n.makeArray(this.options.sortBy);
                    this._getIsSameSortBy(t) ||
                        (this.sortHistory = t.concat(this.sortHistory));
                    var e = a(this.sortHistory, this.options.sortAscending);
                    this.filteredItems.sort(e);
                }
            }),
            (l._getIsSameSortBy = function(t) {
                for (var e = 0; e < t.length; e++)
                    if (t[e] != this.sortHistory[e]) return !1;
                return !0;
            }),
            (l._mode = function() {
                var t = this.options.layoutMode,
                    e = this.modes[t];
                if (!e) throw new Error("No layout mode: " + t);
                return (e.options = this.options[t]), e;
            }),
            (l._resetLayout = function() {
                e.prototype._resetLayout.call(this),
                    this._mode()._resetLayout();
            }),
            (l._getItemLayoutPosition = function(t) {
                return this._mode()._getItemLayoutPosition(t);
            }),
            (l._manageStamp = function(t) {
                this._mode()._manageStamp(t);
            }),
            (l._getContainerSize = function() {
                return this._mode()._getContainerSize();
            }),
            (l.needsResizeLayout = function() {
                return this._mode().needsResizeLayout();
            }),
            (l.appended = function(t) {
                var e = this.addItems(t);
                if (e.length) {
                    var i = this._filterRevealAdded(e);
                    this.filteredItems = this.filteredItems.concat(i);
                }
            }),
            (l.prepended = function(t) {
                var e = this._itemize(t);
                if (e.length) {
                    this._resetLayout(), this._manageStamps();
                    var i = this._filterRevealAdded(e);
                    this.layoutItems(this.filteredItems),
                        (this.filteredItems = i.concat(this.filteredItems)),
                        (this.items = e.concat(this.items));
                }
            }),
            (l._filterRevealAdded = function(t) {
                var e = this._filter(t);
                return (
                    this.hide(e.needHide),
                    this.reveal(e.matches),
                    this.layoutItems(e.matches, !0),
                    e.matches
                );
            }),
            (l.insert = function(t) {
                var e = this.addItems(t);
                if (e.length) {
                    var i,
                        o,
                        n = e.length;
                    for (i = 0; i < n; i++)
                        (o = e[i]), this.element.appendChild(o.element);
                    var s = this._filter(e).matches;
                    for (i = 0; i < n; i++) e[i].isLayoutInstant = !0;
                    for (this.arrange(), i = 0; i < n; i++)
                        delete e[i].isLayoutInstant;
                    this.reveal(s);
                }
            });
        var c = l.remove;
        return (
            (l.remove = function(t) {
                t = n.makeArray(t);
                var e = this.getItems(t);
                c.call(this, t);
                for (var i = e && e.length, o = 0; i && o < i; o++) {
                    var s = e[o];
                    n.removeFrom(this.filteredItems, s);
                }
            }),
            (l.shuffle = function() {
                for (var t = 0; t < this.items.length; t++) {
                    var e = this.items[t];
                    e.sortData.random = Math.random();
                }
                (this.options.sortBy = "random"), this._sort(), this._layout();
            }),
            (l._noTransition = function(t, e) {
                var i = this.options.transitionDuration;
                this.options.transitionDuration = 0;
                var o = t.apply(this, e);
                return (this.options.transitionDuration = i), o;
            }),
            (l.getFilteredItemElements = function() {
                return this.filteredItems.map(function(t) {
                    return t.element;
                });
            }),
            d
        );
    });
(function($) {
    "use strict";
    window.eaelLoadMore = function(options, settings) {
        var optionsValue = {
            totalPosts: options.totalPosts,
            loadMoreBtn: options.loadMoreBtn,
            postContainer: $(options.postContainer),
            postStyle: options.postStyle
        };
        var settingsValue = {
            postType: settings.postType,
            perPage: settings.perPage,
            postOrder: settings.postOrder,
            orderBy: settings.orderBy,
            showImage: settings.showImage,
            showTitle: settings.showTitle,
            showExcerpt: settings.showExcerpt,
            showMeta: settings.showMeta,
            imageSize: settings.imageSize,
            metaPosition: settings.metaPosition,
            excerptLength: settings.excerptLength,
            btnText: settings.btnText,
            tax_query: settings.tax_query,
            post__in: settings.post__in,
            excludePosts: settings.exclude_posts,
            offset: parseInt(settings.offset, 10),
            grid_style: settings.grid_style || "",
            hover_animation: settings.hover_animation,
            hover_icon: settings.hover_icon
        };
        var offset = settingsValue.offset + settingsValue.perPage;
        optionsValue.loadMoreBtn.on("click", function(e) {
            e.preventDefault();
            $(this).addClass("button--loading");
            $(this)
                .find("span")
                .html("Loading...");
            $.ajax({
                url: localize.ajaxurl,
                type: "post",
                data: {
                    action: "load_more",
                    post_style: optionsValue.postStyle,
                    eael_show_image: settingsValue.showImage,
                    image_size: settingsValue.imageSize,
                    eael_show_title: settingsValue.showTitle,
                    eael_show_meta: settingsValue.showMeta,
                    meta_position: settingsValue.metaPosition,
                    eael_show_excerpt: settingsValue.showExcerpt,
                    eael_excerpt_length: settingsValue.excerptLength,
                    post_type: settingsValue.postType,
                    posts_per_page: settingsValue.perPage,
                    offset: offset,
                    tax_query: settingsValue.tax_query,
                    post__not_in: settingsValue.excludePosts,
                    post__in: settingsValue.post__in,
                    orderby: settingsValue.orderBy,
                    order: settingsValue.postOrder,
                    grid_style: settingsValue.grid_style,
                    eael_post_grid_hover_animation:
                        settingsValue.hover_animation,
                    eael_post_grid_bg_hover_icon: settingsValue.hover_icon
                },
                beforeSend: function() {},
                success: function(response) {
                    var $content = $(response);
                    if (optionsValue.postStyle === "grid") {
                        setTimeout(function() {
                            optionsValue.postContainer.masonry();
                            optionsValue.postContainer
                                .append($content)
                                .masonry("appended", $content);
                            optionsValue.postContainer.masonry({
                                itemSelector: ".eael-grid-post",
                                percentPosition: !0,
                                columnWidth: ".eael-post-grid-column"
                            });
                        }, 100);
                    } else {
                        optionsValue.postContainer.append($content);
                    }
                    optionsValue.loadMoreBtn.removeClass("button--loading");
                    optionsValue.loadMoreBtn
                        .find("span")
                        .html(settingsValue.btnText);
                    offset = offset + settingsValue.perPage;
                    if (offset >= optionsValue.totalPosts) {
                        optionsValue.loadMoreBtn.remove();
                    }
                },
                error: function() {}
            });
        });
    };
})(jQuery);
!(function(a, b) {
    "function" == typeof define && define.amd
        ? define(["jquery"], function(a) {
              return b(a);
          })
        : "object" == typeof exports
        ? (module.exports = b(require("jquery")))
        : b(jQuery);
})(this, function(a) {
    function b(a) {
        this.$container,
            (this.constraints = null),
            this.__$tooltip,
            this.__init(a);
    }

    function c(b, c) {
        var d = !0;
        return (
            a.each(b, function(a, e) {
                return void 0 === c[a] || b[a] !== c[a]
                    ? ((d = !1), !1)
                    : void 0;
            }),
            d
        );
    }

    function d(b) {
        var c = b.attr("id"),
            d = c ? h.window.document.getElementById(c) : null;
        return d ? d === b[0] : a.contains(h.window.document.body, b[0]);
    }

    function e() {
        if (!g) return !1;
        var a = g.document.body || g.document.documentElement,
            b = a.style,
            c = "transition",
            d = ["Moz", "Webkit", "Khtml", "O", "ms"];
        if ("string" == typeof b[c]) return !0;
        c = c.charAt(0).toUpperCase() + c.substr(1);
        for (var e = 0; e < d.length; e++)
            if ("string" == typeof b[d[e] + c]) return !0;
        return !1;
    }
    var f = {
            animation: "fade",
            animationDuration: 350,
            content: null,
            contentAsHTML: !1,
            contentCloning: !1,
            debug: !0,
            delay: 300,
            delayTouch: [300, 500],
            functionInit: null,
            functionBefore: null,
            functionReady: null,
            functionAfter: null,
            functionFormat: null,
            IEmin: 6,
            interactive: !1,
            multiple: !1,
            parent: null,
            plugins: ["sideTip"],
            repositionOnScroll: !1,
            restoration: "none",
            selfDestruction: !0,
            theme: [],
            timer: 0,
            trackerInterval: 500,
            trackOrigin: !1,
            trackTooltip: !1,
            trigger: "hover",
            triggerClose: {
                click: !1,
                mouseleave: !1,
                originClick: !1,
                scroll: !1,
                tap: !1,
                touchleave: !1
            },
            triggerOpen: {
                click: !1,
                mouseenter: !1,
                tap: !1,
                touchstart: !1
            },
            updateAnimation: "rotate",
            zIndex: 9999999
        },
        g = "undefined" != typeof window ? window : null,
        h = {
            hasTouchCapability: !(
                !g ||
                !(
                    "ontouchstart" in g ||
                    (g.DocumentTouch &&
                        g.document instanceof g.DocumentTouch) ||
                    g.navigator.maxTouchPoints
                )
            ),
            hasTransitions: e(),
            IE: !1,
            semVer: "4.2.6",
            window: g
        },
        i = function() {
            (this.__$emitterPrivate = a({})),
                (this.__$emitterPublic = a({})),
                (this.__instancesLatestArr = []),
                (this.__plugins = {}),
                (this._env = h);
        };
    (i.prototype = {
        __bridge: function(b, c, d) {
            if (!c[d]) {
                var e = function() {};
                e.prototype = b;
                var g = new e();
                g.__init && g.__init(c),
                    a.each(b, function(a, b) {
                        0 != a.indexOf("__") &&
                            (c[a]
                                ? f.debug &&
                                  console.log(
                                      "The " +
                                          a +
                                          " method of the " +
                                          d +
                                          " plugin conflicts with another plugin or native methods"
                                  )
                                : ((c[a] = function() {
                                      return g[a].apply(
                                          g,
                                          Array.prototype.slice.apply(arguments)
                                      );
                                  }),
                                  (c[a].bridged = g)));
                    }),
                    (c[d] = g);
            }
            return this;
        },
        __setWindow: function(a) {
            return (h.window = a), this;
        },
        _getRuler: function(a) {
            return new b(a);
        },
        _off: function() {
            return (
                this.__$emitterPrivate.off.apply(
                    this.__$emitterPrivate,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        _on: function() {
            return (
                this.__$emitterPrivate.on.apply(
                    this.__$emitterPrivate,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        _one: function() {
            return (
                this.__$emitterPrivate.one.apply(
                    this.__$emitterPrivate,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        _plugin: function(b) {
            var c = this;
            if ("string" == typeof b) {
                var d = b,
                    e = null;
                return (
                    d.indexOf(".") > 0
                        ? (e = c.__plugins[d])
                        : a.each(c.__plugins, function(a, b) {
                              return b.name.substring(
                                  b.name.length - d.length - 1
                              ) ==
                                  "." + d
                                  ? ((e = b), !1)
                                  : void 0;
                          }),
                    e
                );
            }
            if (b.name.indexOf(".") < 0)
                throw new Error("Plugins must be namespaced");
            return (
                (c.__plugins[b.name] = b),
                b.core && c.__bridge(b.core, c, b.name),
                this
            );
        },
        _trigger: function() {
            var a = Array.prototype.slice.apply(arguments);
            return (
                "string" == typeof a[0] &&
                    (a[0] = {
                        type: a[0]
                    }),
                this.__$emitterPrivate.trigger.apply(this.__$emitterPrivate, a),
                this.__$emitterPublic.trigger.apply(this.__$emitterPublic, a),
                this
            );
        },
        instances: function(b) {
            var c = [],
                d = b || ".tooltipstered";
            return (
                a(d).each(function() {
                    var b = a(this),
                        d = b.data("tooltipster-ns");
                    d &&
                        a.each(d, function(a, d) {
                            c.push(b.data(d));
                        });
                }),
                c
            );
        },
        instancesLatest: function() {
            return this.__instancesLatestArr;
        },
        off: function() {
            return (
                this.__$emitterPublic.off.apply(
                    this.__$emitterPublic,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        on: function() {
            return (
                this.__$emitterPublic.on.apply(
                    this.__$emitterPublic,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        one: function() {
            return (
                this.__$emitterPublic.one.apply(
                    this.__$emitterPublic,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        },
        origins: function(b) {
            var c = b ? b + " " : "";
            return a(c + ".tooltipstered").toArray();
        },
        setDefaults: function(b) {
            return a.extend(f, b), this;
        },
        triggerHandler: function() {
            return (
                this.__$emitterPublic.triggerHandler.apply(
                    this.__$emitterPublic,
                    Array.prototype.slice.apply(arguments)
                ),
                this
            );
        }
    }),
        (a.tooltipster = new i()),
        (a.Tooltipster = function(b, c) {
            (this.__callbacks = {
                close: [],
                open: []
            }),
                this.__closingTime,
                this.__Content,
                this.__contentBcr,
                (this.__destroyed = !1),
                (this.__$emitterPrivate = a({})),
                (this.__$emitterPublic = a({})),
                (this.__enabled = !0),
                this.__garbageCollector,
                this.__Geometry,
                this.__lastPosition,
                (this.__namespace =
                    "tooltipster-" + Math.round(1e6 * Math.random())),
                this.__options,
                this.__$originParents,
                (this.__pointerIsOverOrigin = !1),
                (this.__previousThemes = []),
                (this.__state = "closed"),
                (this.__timeouts = {
                    close: [],
                    open: null
                }),
                (this.__touchEvents = []),
                (this.__tracker = null),
                this._$origin,
                this._$tooltip,
                this.__init(b, c);
        }),
        (a.Tooltipster.prototype = {
            __init: function(b, c) {
                var d = this;
                if (
                    ((d._$origin = a(b)),
                    (d.__options = a.extend(!0, {}, f, c)),
                    d.__optionsFormat(),
                    !h.IE || h.IE >= d.__options.IEmin)
                ) {
                    var e = null;
                    if (
                        (void 0 ===
                            d._$origin.data("tooltipster-initialTitle") &&
                            ((e = d._$origin.attr("title")),
                            void 0 === e && (e = null),
                            d._$origin.data("tooltipster-initialTitle", e)),
                        null !== d.__options.content)
                    )
                        d.__contentSet(d.__options.content);
                    else {
                        var g,
                            i = d._$origin.attr("data-tooltip-content");
                        i && (g = a(i)),
                            g && g[0]
                                ? d.__contentSet(g.first())
                                : d.__contentSet(e);
                    }
                    d._$origin.removeAttr("title").addClass("tooltipstered"),
                        d.__prepareOrigin(),
                        d.__prepareGC(),
                        a.each(d.__options.plugins, function(a, b) {
                            d._plug(b);
                        }),
                        h.hasTouchCapability &&
                            a(h.window.document.body).on(
                                "touchmove." + d.__namespace + "-triggerOpen",
                                function(a) {
                                    d._touchRecordEvent(a);
                                }
                            ),
                        d
                            ._on("created", function() {
                                d.__prepareTooltip();
                            })
                            ._on("repositioned", function(a) {
                                d.__lastPosition = a.position;
                            });
                } else d.__options.disabled = !0;
            },
            __contentInsert: function() {
                var a = this,
                    b = a._$tooltip.find(".tooltipster-content"),
                    c = a.__Content,
                    d = function(a) {
                        c = a;
                    };
                return (
                    a._trigger({
                        type: "format",
                        content: a.__Content,
                        format: d
                    }),
                    a.__options.functionFormat &&
                        (c = a.__options.functionFormat.call(
                            a,
                            a,
                            {
                                origin: a._$origin[0]
                            },
                            a.__Content
                        )),
                    "string" != typeof c || a.__options.contentAsHTML
                        ? b.empty().append(c)
                        : b.text(c),
                    a
                );
            },
            __contentSet: function(b) {
                return (
                    b instanceof a &&
                        this.__options.contentCloning &&
                        (b = b.clone(!0)),
                    (this.__Content = b),
                    this._trigger({
                        type: "updated",
                        content: b
                    }),
                    this
                );
            },
            __destroyError: function() {
                throw new Error(
                    "This tooltip has been destroyed and cannot execute your method call."
                );
            },
            __geometry: function() {
                var b = this,
                    c = b._$origin,
                    d = b._$origin.is("area");
                if (d) {
                    var e = b._$origin.parent().attr("name");
                    c = a('img[usemap="#' + e + '"]');
                }
                var f = c[0].getBoundingClientRect(),
                    g = a(h.window.document),
                    i = a(h.window),
                    j = c,
                    k = {
                        available: {
                            document: null,
                            window: null
                        },
                        document: {
                            size: {
                                height: g.height(),
                                width: g.width()
                            }
                        },
                        window: {
                            scroll: {
                                left:
                                    h.window.scrollX ||
                                    h.window.document.documentElement
                                        .scrollLeft,
                                top:
                                    h.window.scrollY ||
                                    h.window.document.documentElement.scrollTop
                            },
                            size: {
                                height: i.height(),
                                width: i.width()
                            }
                        },
                        origin: {
                            fixedLineage: !1,
                            offset: {},
                            size: {
                                height: f.bottom - f.top,
                                width: f.right - f.left
                            },
                            usemapImage: d ? c[0] : null,
                            windowOffset: {
                                bottom: f.bottom,
                                left: f.left,
                                right: f.right,
                                top: f.top
                            }
                        }
                    };
                if (d) {
                    var l = b._$origin.attr("shape"),
                        m = b._$origin.attr("coords");
                    if (
                        (m &&
                            ((m = m.split(",")),
                            a.map(m, function(a, b) {
                                m[b] = parseInt(a);
                            })),
                        "default" != l)
                    )
                        switch (l) {
                            case "circle":
                                var n = m[0],
                                    o = m[1],
                                    p = m[2],
                                    q = o - p,
                                    r = n - p;
                                (k.origin.size.height = 2 * p),
                                    (k.origin.size.width =
                                        k.origin.size.height),
                                    (k.origin.windowOffset.left += r),
                                    (k.origin.windowOffset.top += q);
                                break;
                            case "rect":
                                var s = m[0],
                                    t = m[1],
                                    u = m[2],
                                    v = m[3];
                                (k.origin.size.height = v - t),
                                    (k.origin.size.width = u - s),
                                    (k.origin.windowOffset.left += s),
                                    (k.origin.windowOffset.top += t);
                                break;
                            case "poly":
                                for (
                                    var w = 0,
                                        x = 0,
                                        y = 0,
                                        z = 0,
                                        A = "even",
                                        B = 0;
                                    B < m.length;
                                    B++
                                ) {
                                    var C = m[B];
                                    "even" == A
                                        ? (C > y &&
                                              ((y = C), 0 === B && (w = y)),
                                          w > C && (w = C),
                                          (A = "odd"))
                                        : (C > z &&
                                              ((z = C), 1 == B && (x = z)),
                                          x > C && (x = C),
                                          (A = "even"));
                                }
                                (k.origin.size.height = z - x),
                                    (k.origin.size.width = y - w),
                                    (k.origin.windowOffset.left += w),
                                    (k.origin.windowOffset.top += x);
                        }
                }
                var D = function(a) {
                    (k.origin.size.height = a.height),
                        (k.origin.windowOffset.left = a.left),
                        (k.origin.windowOffset.top = a.top),
                        (k.origin.size.width = a.width);
                };
                for (
                    b._trigger({
                        type: "geometry",
                        edit: D,
                        geometry: {
                            height: k.origin.size.height,
                            left: k.origin.windowOffset.left,
                            top: k.origin.windowOffset.top,
                            width: k.origin.size.width
                        }
                    }),
                        k.origin.windowOffset.right =
                            k.origin.windowOffset.left + k.origin.size.width,
                        k.origin.windowOffset.bottom =
                            k.origin.windowOffset.top + k.origin.size.height,
                        k.origin.offset.left =
                            k.origin.windowOffset.left + k.window.scroll.left,
                        k.origin.offset.top =
                            k.origin.windowOffset.top + k.window.scroll.top,
                        k.origin.offset.bottom =
                            k.origin.offset.top + k.origin.size.height,
                        k.origin.offset.right =
                            k.origin.offset.left + k.origin.size.width,
                        k.available.document = {
                            bottom: {
                                height:
                                    k.document.size.height -
                                    k.origin.offset.bottom,
                                width: k.document.size.width
                            },
                            left: {
                                height: k.document.size.height,
                                width: k.origin.offset.left
                            },
                            right: {
                                height: k.document.size.height,
                                width:
                                    k.document.size.width -
                                    k.origin.offset.right
                            },
                            top: {
                                height: k.origin.offset.top,
                                width: k.document.size.width
                            }
                        },
                        k.available.window = {
                            bottom: {
                                height: Math.max(
                                    k.window.size.height -
                                        Math.max(
                                            k.origin.windowOffset.bottom,
                                            0
                                        ),
                                    0
                                ),
                                width: k.window.size.width
                            },
                            left: {
                                height: k.window.size.height,
                                width: Math.max(k.origin.windowOffset.left, 0)
                            },
                            right: {
                                height: k.window.size.height,
                                width: Math.max(
                                    k.window.size.width -
                                        Math.max(
                                            k.origin.windowOffset.right,
                                            0
                                        ),
                                    0
                                )
                            },
                            top: {
                                height: Math.max(k.origin.windowOffset.top, 0),
                                width: k.window.size.width
                            }
                        };
                    "html" != j[0].tagName.toLowerCase();

                ) {
                    if ("fixed" == j.css("position")) {
                        k.origin.fixedLineage = !0;
                        break;
                    }
                    j = j.parent();
                }
                return k;
            },
            __optionsFormat: function() {
                return (
                    "number" == typeof this.__options.animationDuration &&
                        (this.__options.animationDuration = [
                            this.__options.animationDuration,
                            this.__options.animationDuration
                        ]),
                    "number" == typeof this.__options.delay &&
                        (this.__options.delay = [
                            this.__options.delay,
                            this.__options.delay
                        ]),
                    "number" == typeof this.__options.delayTouch &&
                        (this.__options.delayTouch = [
                            this.__options.delayTouch,
                            this.__options.delayTouch
                        ]),
                    "string" == typeof this.__options.theme &&
                        (this.__options.theme = [this.__options.theme]),
                    null === this.__options.parent
                        ? (this.__options.parent = a(h.window.document.body))
                        : "string" == typeof this.__options.parent &&
                          (this.__options.parent = a(this.__options.parent)),
                    "hover" == this.__options.trigger
                        ? ((this.__options.triggerOpen = {
                              mouseenter: !0,
                              touchstart: !0
                          }),
                          (this.__options.triggerClose = {
                              mouseleave: !0,
                              originClick: !0,
                              touchleave: !0
                          }))
                        : "click" == this.__options.trigger &&
                          ((this.__options.triggerOpen = {
                              click: !0,
                              tap: !0
                          }),
                          (this.__options.triggerClose = {
                              click: !0,
                              tap: !0
                          })),
                    this._trigger("options"),
                    this
                );
            },
            __prepareGC: function() {
                var b = this;
                return (
                    b.__options.selfDestruction
                        ? (b.__garbageCollector = setInterval(function() {
                              var c = new Date().getTime();
                              (b.__touchEvents = a.grep(
                                  b.__touchEvents,
                                  function(a, b) {
                                      return c - a.time > 6e4;
                                  }
                              )),
                                  d(b._$origin) ||
                                      b.close(function() {
                                          b.destroy();
                                      });
                          }, 2e4))
                        : clearInterval(b.__garbageCollector),
                    b
                );
            },
            __prepareOrigin: function() {
                var a = this;
                if (
                    (a._$origin.off("." + a.__namespace + "-triggerOpen"),
                    h.hasTouchCapability &&
                        a._$origin.on(
                            "touchstart." +
                                a.__namespace +
                                "-triggerOpen touchend." +
                                a.__namespace +
                                "-triggerOpen touchcancel." +
                                a.__namespace +
                                "-triggerOpen",
                            function(b) {
                                a._touchRecordEvent(b);
                            }
                        ),
                    a.__options.triggerOpen.click ||
                        (a.__options.triggerOpen.tap && h.hasTouchCapability))
                ) {
                    var b = "";
                    a.__options.triggerOpen.click &&
                        (b += "click." + a.__namespace + "-triggerOpen "),
                        a.__options.triggerOpen.tap &&
                            h.hasTouchCapability &&
                            (b += "touchend." + a.__namespace + "-triggerOpen"),
                        a._$origin.on(b, function(b) {
                            a._touchIsMeaningfulEvent(b) && a._open(b);
                        });
                }
                if (
                    a.__options.triggerOpen.mouseenter ||
                    (a.__options.triggerOpen.touchstart && h.hasTouchCapability)
                ) {
                    var b = "";
                    a.__options.triggerOpen.mouseenter &&
                        (b += "mouseenter." + a.__namespace + "-triggerOpen "),
                        a.__options.triggerOpen.touchstart &&
                            h.hasTouchCapability &&
                            (b +=
                                "touchstart." + a.__namespace + "-triggerOpen"),
                        a._$origin.on(b, function(b) {
                            (!a._touchIsTouchEvent(b) &&
                                a._touchIsEmulatedEvent(b)) ||
                                ((a.__pointerIsOverOrigin = !0),
                                a._openShortly(b));
                        });
                }
                if (
                    a.__options.triggerClose.mouseleave ||
                    (a.__options.triggerClose.touchleave &&
                        h.hasTouchCapability)
                ) {
                    var b = "";
                    a.__options.triggerClose.mouseleave &&
                        (b += "mouseleave." + a.__namespace + "-triggerOpen "),
                        a.__options.triggerClose.touchleave &&
                            h.hasTouchCapability &&
                            (b +=
                                "touchend." +
                                a.__namespace +
                                "-triggerOpen touchcancel." +
                                a.__namespace +
                                "-triggerOpen"),
                        a._$origin.on(b, function(b) {
                            a._touchIsMeaningfulEvent(b) &&
                                (a.__pointerIsOverOrigin = !1);
                        });
                }
                return a;
            },
            __prepareTooltip: function() {
                var b = this,
                    c = b.__options.interactive ? "auto" : "";
                return (
                    b._$tooltip.attr("id", b.__namespace).css({
                        "pointer-events": c,
                        zIndex: b.__options.zIndex
                    }),
                    a.each(b.__previousThemes, function(a, c) {
                        b._$tooltip.removeClass(c);
                    }),
                    a.each(b.__options.theme, function(a, c) {
                        b._$tooltip.addClass(c);
                    }),
                    (b.__previousThemes = a.merge([], b.__options.theme)),
                    b
                );
            },
            __scrollHandler: function(b) {
                var c = this;
                if (c.__options.triggerClose.scroll) c._close(b);
                else if (d(c._$origin) && d(c._$tooltip)) {
                    var e = null;
                    if (b.target === h.window.document)
                        c.__Geometry.origin.fixedLineage ||
                            (c.__options.repositionOnScroll && c.reposition(b));
                    else {
                        e = c.__geometry();
                        var f = !1;
                        if (
                            ("fixed" != c._$origin.css("position") &&
                                c.__$originParents.each(function(b, c) {
                                    var d = a(c),
                                        g = d.css("overflow-x"),
                                        h = d.css("overflow-y");
                                    if ("visible" != g || "visible" != h) {
                                        var i = c.getBoundingClientRect();
                                        if (
                                            "visible" != g &&
                                            (e.origin.windowOffset.left <
                                                i.left ||
                                                e.origin.windowOffset.right >
                                                    i.right)
                                        )
                                            return (f = !0), !1;
                                        if (
                                            "visible" != h &&
                                            (e.origin.windowOffset.top <
                                                i.top ||
                                                e.origin.windowOffset.bottom >
                                                    i.bottom)
                                        )
                                            return (f = !0), !1;
                                    }
                                    return "fixed" == d.css("position")
                                        ? !1
                                        : void 0;
                                }),
                            f)
                        )
                            c._$tooltip.css("visibility", "hidden");
                        else if (
                            (c._$tooltip.css("visibility", "visible"),
                            c.__options.repositionOnScroll)
                        )
                            c.reposition(b);
                        else {
                            var g =
                                    e.origin.offset.left -
                                    c.__Geometry.origin.offset.left,
                                i =
                                    e.origin.offset.top -
                                    c.__Geometry.origin.offset.top;
                            c._$tooltip.css({
                                left: c.__lastPosition.coord.left + g,
                                top: c.__lastPosition.coord.top + i
                            });
                        }
                    }
                    c._trigger({
                        type: "scroll",
                        event: b,
                        geo: e
                    });
                }
                return c;
            },
            __stateSet: function(a) {
                return (
                    (this.__state = a),
                    this._trigger({
                        type: "state",
                        state: a
                    }),
                    this
                );
            },
            __timeoutsClear: function() {
                return (
                    clearTimeout(this.__timeouts.open),
                    (this.__timeouts.open = null),
                    a.each(this.__timeouts.close, function(a, b) {
                        clearTimeout(b);
                    }),
                    (this.__timeouts.close = []),
                    this
                );
            },
            __trackerStart: function() {
                var a = this,
                    b = a._$tooltip.find(".tooltipster-content");
                return (
                    a.__options.trackTooltip &&
                        (a.__contentBcr = b[0].getBoundingClientRect()),
                    (a.__tracker = setInterval(function() {
                        if (d(a._$origin) && d(a._$tooltip)) {
                            if (a.__options.trackOrigin) {
                                var e = a.__geometry(),
                                    f = !1;
                                c(e.origin.size, a.__Geometry.origin.size) &&
                                    (a.__Geometry.origin.fixedLineage
                                        ? c(
                                              e.origin.windowOffset,
                                              a.__Geometry.origin.windowOffset
                                          ) && (f = !0)
                                        : c(
                                              e.origin.offset,
                                              a.__Geometry.origin.offset
                                          ) && (f = !0)),
                                    f ||
                                        (a.__options.triggerClose.mouseleave
                                            ? a._close()
                                            : a.reposition());
                            }
                            if (a.__options.trackTooltip) {
                                var g = b[0].getBoundingClientRect();
                                (g.height === a.__contentBcr.height &&
                                    g.width === a.__contentBcr.width) ||
                                    (a.reposition(), (a.__contentBcr = g));
                            }
                        } else a._close();
                    }, a.__options.trackerInterval)),
                    a
                );
            },
            _close: function(b, c, d) {
                var e = this,
                    f = !0;
                if (
                    (e._trigger({
                        type: "close",
                        event: b,
                        stop: function() {
                            f = !1;
                        }
                    }),
                    f || d)
                ) {
                    c && e.__callbacks.close.push(c),
                        (e.__callbacks.open = []),
                        e.__timeoutsClear();
                    var g = function() {
                        a.each(e.__callbacks.close, function(a, c) {
                            c.call(e, e, {
                                event: b,
                                origin: e._$origin[0]
                            });
                        }),
                            (e.__callbacks.close = []);
                    };
                    if ("closed" != e.__state) {
                        var i = !0,
                            j = new Date(),
                            k = j.getTime(),
                            l = k + e.__options.animationDuration[1];
                        if (
                            ("disappearing" == e.__state &&
                                l > e.__closingTime &&
                                e.__options.animationDuration[1] > 0 &&
                                (i = !1),
                            i)
                        ) {
                            (e.__closingTime = l),
                                "disappearing" != e.__state &&
                                    e.__stateSet("disappearing");
                            var m = function() {
                                clearInterval(e.__tracker),
                                    e._trigger({
                                        type: "closing",
                                        event: b
                                    }),
                                    e._$tooltip
                                        .off(
                                            "." +
                                                e.__namespace +
                                                "-triggerClose"
                                        )
                                        .removeClass("tooltipster-dying"),
                                    a(h.window).off(
                                        "." + e.__namespace + "-triggerClose"
                                    ),
                                    e.__$originParents.each(function(b, c) {
                                        a(c).off(
                                            "scroll." +
                                                e.__namespace +
                                                "-triggerClose"
                                        );
                                    }),
                                    (e.__$originParents = null),
                                    a(h.window.document.body).off(
                                        "." + e.__namespace + "-triggerClose"
                                    ),
                                    e._$origin.off(
                                        "." + e.__namespace + "-triggerClose"
                                    ),
                                    e._off("dismissable"),
                                    e.__stateSet("closed"),
                                    e._trigger({
                                        type: "after",
                                        event: b
                                    }),
                                    e.__options.functionAfter &&
                                        e.__options.functionAfter.call(e, e, {
                                            event: b,
                                            origin: e._$origin[0]
                                        }),
                                    g();
                            };
                            h.hasTransitions
                                ? (e._$tooltip.css({
                                      "-moz-animation-duration":
                                          e.__options.animationDuration[1] +
                                          "ms",
                                      "-ms-animation-duration":
                                          e.__options.animationDuration[1] +
                                          "ms",
                                      "-o-animation-duration":
                                          e.__options.animationDuration[1] +
                                          "ms",
                                      "-webkit-animation-duration":
                                          e.__options.animationDuration[1] +
                                          "ms",
                                      "animation-duration":
                                          e.__options.animationDuration[1] +
                                          "ms",
                                      "transition-duration":
                                          e.__options.animationDuration[1] +
                                          "ms"
                                  }),
                                  e._$tooltip
                                      .clearQueue()
                                      .removeClass("tooltipster-show")
                                      .addClass("tooltipster-dying"),
                                  e.__options.animationDuration[1] > 0 &&
                                      e._$tooltip.delay(
                                          e.__options.animationDuration[1]
                                      ),
                                  e._$tooltip.queue(m))
                                : e._$tooltip
                                      .stop()
                                      .fadeOut(
                                          e.__options.animationDuration[1],
                                          m
                                      );
                        }
                    } else g();
                }
                return e;
            },
            _off: function() {
                return (
                    this.__$emitterPrivate.off.apply(
                        this.__$emitterPrivate,
                        Array.prototype.slice.apply(arguments)
                    ),
                    this
                );
            },
            _on: function() {
                return (
                    this.__$emitterPrivate.on.apply(
                        this.__$emitterPrivate,
                        Array.prototype.slice.apply(arguments)
                    ),
                    this
                );
            },
            _one: function() {
                return (
                    this.__$emitterPrivate.one.apply(
                        this.__$emitterPrivate,
                        Array.prototype.slice.apply(arguments)
                    ),
                    this
                );
            },
            _open: function(b, c) {
                var e = this;
                if (!e.__destroying && d(e._$origin) && e.__enabled) {
                    var f = !0;
                    if (
                        ("closed" == e.__state &&
                            (e._trigger({
                                type: "before",
                                event: b,
                                stop: function() {
                                    f = !1;
                                }
                            }),
                            f &&
                                e.__options.functionBefore &&
                                (f = e.__options.functionBefore.call(e, e, {
                                    event: b,
                                    origin: e._$origin[0]
                                }))),
                        f !== !1 && null !== e.__Content)
                    ) {
                        c && e.__callbacks.open.push(c),
                            (e.__callbacks.close = []),
                            e.__timeoutsClear();
                        var g,
                            i = function() {
                                "stable" != e.__state && e.__stateSet("stable"),
                                    a.each(e.__callbacks.open, function(a, b) {
                                        b.call(e, e, {
                                            origin: e._$origin[0],
                                            tooltip: e._$tooltip[0]
                                        });
                                    }),
                                    (e.__callbacks.open = []);
                            };
                        if ("closed" !== e.__state)
                            (g = 0),
                                "disappearing" === e.__state
                                    ? (e.__stateSet("appearing"),
                                      h.hasTransitions
                                          ? (e._$tooltip
                                                .clearQueue()
                                                .removeClass(
                                                    "tooltipster-dying"
                                                )
                                                .addClass("tooltipster-show"),
                                            e.__options.animationDuration[0] >
                                                0 &&
                                                e._$tooltip.delay(
                                                    e.__options
                                                        .animationDuration[0]
                                                ),
                                            e._$tooltip.queue(i))
                                          : e._$tooltip.stop().fadeIn(i))
                                    : "stable" == e.__state && i();
                        else {
                            if (
                                (e.__stateSet("appearing"),
                                (g = e.__options.animationDuration[0]),
                                e.__contentInsert(),
                                e.reposition(b, !0),
                                h.hasTransitions
                                    ? (e._$tooltip
                                          .addClass(
                                              "tooltipster-" +
                                                  e.__options.animation
                                          )
                                          .addClass("tooltipster-initial")
                                          .css({
                                              "-moz-animation-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms",
                                              "-ms-animation-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms",
                                              "-o-animation-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms",
                                              "-webkit-animation-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms",
                                              "animation-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms",
                                              "transition-duration":
                                                  e.__options
                                                      .animationDuration[0] +
                                                  "ms"
                                          }),
                                      setTimeout(function() {
                                          "closed" != e.__state &&
                                              (e._$tooltip
                                                  .addClass("tooltipster-show")
                                                  .removeClass(
                                                      "tooltipster-initial"
                                                  ),
                                              e.__options.animationDuration[0] >
                                                  0 &&
                                                  e._$tooltip.delay(
                                                      e.__options
                                                          .animationDuration[0]
                                                  ),
                                              e._$tooltip.queue(i));
                                      }, 0))
                                    : e._$tooltip
                                          .css("display", "none")
                                          .fadeIn(
                                              e.__options.animationDuration[0],
                                              i
                                          ),
                                e.__trackerStart(),
                                a(h.window)
                                    .on(
                                        "resize." +
                                            e.__namespace +
                                            "-triggerClose",
                                        function(b) {
                                            var c = a(document.activeElement);
                                            ((c.is("input") ||
                                                c.is("textarea")) &&
                                                a.contains(
                                                    e._$tooltip[0],
                                                    c[0]
                                                )) ||
                                                e.reposition(b);
                                        }
                                    )
                                    .on(
                                        "scroll." +
                                            e.__namespace +
                                            "-triggerClose",
                                        function(a) {
                                            e.__scrollHandler(a);
                                        }
                                    ),
                                (e.__$originParents = e._$origin.parents()),
                                e.__$originParents.each(function(b, c) {
                                    a(c).on(
                                        "scroll." +
                                            e.__namespace +
                                            "-triggerClose",
                                        function(a) {
                                            e.__scrollHandler(a);
                                        }
                                    );
                                }),
                                e.__options.triggerClose.mouseleave ||
                                    (e.__options.triggerClose.touchleave &&
                                        h.hasTouchCapability))
                            ) {
                                e._on("dismissable", function(a) {
                                    a.dismissable
                                        ? a.delay
                                            ? ((m = setTimeout(function() {
                                                  e._close(a.event);
                                              }, a.delay)),
                                              e.__timeouts.close.push(m))
                                            : e._close(a)
                                        : clearTimeout(m);
                                });
                                var j = e._$origin,
                                    k = "",
                                    l = "",
                                    m = null;
                                e.__options.interactive &&
                                    (j = j.add(e._$tooltip)),
                                    e.__options.triggerClose.mouseleave &&
                                        ((k +=
                                            "mouseenter." +
                                            e.__namespace +
                                            "-triggerClose "),
                                        (l +=
                                            "mouseleave." +
                                            e.__namespace +
                                            "-triggerClose ")),
                                    e.__options.triggerClose.touchleave &&
                                        h.hasTouchCapability &&
                                        ((k +=
                                            "touchstart." +
                                            e.__namespace +
                                            "-triggerClose"),
                                        (l +=
                                            "touchend." +
                                            e.__namespace +
                                            "-triggerClose touchcancel." +
                                            e.__namespace +
                                            "-triggerClose")),
                                    j
                                        .on(l, function(a) {
                                            if (
                                                e._touchIsTouchEvent(a) ||
                                                !e._touchIsEmulatedEvent(a)
                                            ) {
                                                var b =
                                                    "mouseleave" == a.type
                                                        ? e.__options.delay
                                                        : e.__options
                                                              .delayTouch;
                                                e._trigger({
                                                    delay: b[1],
                                                    dismissable: !0,
                                                    event: a,
                                                    type: "dismissable"
                                                });
                                            }
                                        })
                                        .on(k, function(a) {
                                            (!e._touchIsTouchEvent(a) &&
                                                e._touchIsEmulatedEvent(a)) ||
                                                e._trigger({
                                                    dismissable: !1,
                                                    event: a,
                                                    type: "dismissable"
                                                });
                                        });
                            }
                            e.__options.triggerClose.originClick &&
                                e._$origin.on(
                                    "click." + e.__namespace + "-triggerClose",
                                    function(a) {
                                        e._touchIsTouchEvent(a) ||
                                            e._touchIsEmulatedEvent(a) ||
                                            e._close(a);
                                    }
                                ),
                                (e.__options.triggerClose.click ||
                                    (e.__options.triggerClose.tap &&
                                        h.hasTouchCapability)) &&
                                    setTimeout(function() {
                                        if ("closed" != e.__state) {
                                            var b = "",
                                                c = a(h.window.document.body);
                                            e.__options.triggerClose.click &&
                                                (b +=
                                                    "click." +
                                                    e.__namespace +
                                                    "-triggerClose "),
                                                e.__options.triggerClose.tap &&
                                                    h.hasTouchCapability &&
                                                    (b +=
                                                        "touchend." +
                                                        e.__namespace +
                                                        "-triggerClose"),
                                                c.on(b, function(b) {
                                                    e._touchIsMeaningfulEvent(
                                                        b
                                                    ) &&
                                                        (e._touchRecordEvent(b),
                                                        (e.__options
                                                            .interactive &&
                                                            a.contains(
                                                                e._$tooltip[0],
                                                                b.target
                                                            )) ||
                                                            e._close(b));
                                                }),
                                                e.__options.triggerClose.tap &&
                                                    h.hasTouchCapability &&
                                                    c.on(
                                                        "touchstart." +
                                                            e.__namespace +
                                                            "-triggerClose",
                                                        function(a) {
                                                            e._touchRecordEvent(
                                                                a
                                                            );
                                                        }
                                                    );
                                        }
                                    }, 0),
                                e._trigger("ready"),
                                e.__options.functionReady &&
                                    e.__options.functionReady.call(e, e, {
                                        origin: e._$origin[0],
                                        tooltip: e._$tooltip[0]
                                    });
                        }
                        if (e.__options.timer > 0) {
                            var m = setTimeout(function() {
                                e._close();
                            }, e.__options.timer + g);
                            e.__timeouts.close.push(m);
                        }
                    }
                }
                return e;
            },
            _openShortly: function(a) {
                var b = this,
                    c = !0;
                if (
                    "stable" != b.__state &&
                    "appearing" != b.__state &&
                    !b.__timeouts.open &&
                    (b._trigger({
                        type: "start",
                        event: a,
                        stop: function() {
                            c = !1;
                        }
                    }),
                    c)
                ) {
                    var d =
                        0 == a.type.indexOf("touch")
                            ? b.__options.delayTouch
                            : b.__options.delay;
                    d[0]
                        ? (b.__timeouts.open = setTimeout(function() {
                              (b.__timeouts.open = null),
                                  b.__pointerIsOverOrigin &&
                                  b._touchIsMeaningfulEvent(a)
                                      ? (b._trigger("startend"), b._open(a))
                                      : b._trigger("startcancel");
                          }, d[0]))
                        : (b._trigger("startend"), b._open(a));
                }
                return b;
            },
            _optionsExtract: function(b, c) {
                var d = this,
                    e = a.extend(!0, {}, c),
                    f = d.__options[b];
                return (
                    f ||
                        ((f = {}),
                        a.each(c, function(a, b) {
                            var c = d.__options[a];
                            void 0 !== c && (f[a] = c);
                        })),
                    a.each(e, function(b, c) {
                        void 0 !== f[b] &&
                            ("object" != typeof c ||
                            c instanceof Array ||
                            null == c ||
                            "object" != typeof f[b] ||
                            f[b] instanceof Array ||
                            null == f[b]
                                ? (e[b] = f[b])
                                : a.extend(e[b], f[b]));
                    }),
                    e
                );
            },
            _plug: function(b) {
                var c = a.tooltipster._plugin(b);
                if (!c)
                    throw new Error('The "' + b + '" plugin is not defined');
                return (
                    c.instance &&
                        a.tooltipster.__bridge(c.instance, this, c.name),
                    this
                );
            },
            _touchIsEmulatedEvent: function(a) {
                for (
                    var b = !1,
                        c = new Date().getTime(),
                        d = this.__touchEvents.length - 1;
                    d >= 0;
                    d--
                ) {
                    var e = this.__touchEvents[d];
                    if (!(c - e.time < 500)) break;
                    e.target === a.target && (b = !0);
                }
                return b;
            },
            _touchIsMeaningfulEvent: function(a) {
                return (
                    (this._touchIsTouchEvent(a) &&
                        !this._touchSwiped(a.target)) ||
                    (!this._touchIsTouchEvent(a) &&
                        !this._touchIsEmulatedEvent(a))
                );
            },
            _touchIsTouchEvent: function(a) {
                return 0 == a.type.indexOf("touch");
            },
            _touchRecordEvent: function(a) {
                return (
                    this._touchIsTouchEvent(a) &&
                        ((a.time = new Date().getTime()),
                        this.__touchEvents.push(a)),
                    this
                );
            },
            _touchSwiped: function(a) {
                for (
                    var b = !1, c = this.__touchEvents.length - 1;
                    c >= 0;
                    c--
                ) {
                    var d = this.__touchEvents[c];
                    if ("touchmove" == d.type) {
                        b = !0;
                        break;
                    }
                    if ("touchstart" == d.type && a === d.target) break;
                }
                return b;
            },
            _trigger: function() {
                var b = Array.prototype.slice.apply(arguments);
                return (
                    "string" == typeof b[0] &&
                        (b[0] = {
                            type: b[0]
                        }),
                    (b[0].instance = this),
                    (b[0].origin = this._$origin ? this._$origin[0] : null),
                    (b[0].tooltip = this._$tooltip ? this._$tooltip[0] : null),
                    this.__$emitterPrivate.trigger.apply(
                        this.__$emitterPrivate,
                        b
                    ),
                    a.tooltipster._trigger.apply(a.tooltipster, b),
                    this.__$emitterPublic.trigger.apply(
                        this.__$emitterPublic,
                        b
                    ),
                    this
                );
            },
            _unplug: function(b) {
                var c = this;
                if (c[b]) {
                    var d = a.tooltipster._plugin(b);
                    d.instance &&
                        a.each(d.instance, function(a, d) {
                            c[a] && c[a].bridged === c[b] && delete c[a];
                        }),
                        c[b].__destroy && c[b].__destroy(),
                        delete c[b];
                }
                return c;
            },
            close: function(a) {
                return (
                    this.__destroyed
                        ? this.__destroyError()
                        : this._close(null, a),
                    this
                );
            },
            content: function(a) {
                var b = this;
                if (void 0 === a) return b.__Content;
                if (b.__destroyed) b.__destroyError();
                else if ((b.__contentSet(a), null !== b.__Content)) {
                    if (
                        "closed" !== b.__state &&
                        (b.__contentInsert(),
                        b.reposition(),
                        b.__options.updateAnimation)
                    )
                        if (h.hasTransitions) {
                            var c = b.__options.updateAnimation;
                            b._$tooltip.addClass("tooltipster-update-" + c),
                                setTimeout(function() {
                                    "closed" != b.__state &&
                                        b._$tooltip.removeClass(
                                            "tooltipster-update-" + c
                                        );
                                }, 1e3);
                        } else
                            b._$tooltip.fadeTo(200, 0.5, function() {
                                "closed" != b.__state &&
                                    b._$tooltip.fadeTo(200, 1);
                            });
                } else b._close();
                return b;
            },
            destroy: function() {
                var b = this;
                if (b.__destroyed) b.__destroyError();
                else {
                    "closed" != b.__state
                        ? b
                              .option("animationDuration", 0)
                              ._close(null, null, !0)
                        : b.__timeoutsClear(),
                        b._trigger("destroy"),
                        (b.__destroyed = !0),
                        b._$origin
                            .removeData(b.__namespace)
                            .off("." + b.__namespace + "-triggerOpen"),
                        a(h.window.document.body).off(
                            "." + b.__namespace + "-triggerOpen"
                        );
                    var c = b._$origin.data("tooltipster-ns");
                    if (c)
                        if (1 === c.length) {
                            var d = null;
                            "previous" == b.__options.restoration
                                ? (d = b._$origin.data(
                                      "tooltipster-initialTitle"
                                  ))
                                : "current" == b.__options.restoration &&
                                  (d =
                                      "string" == typeof b.__Content
                                          ? b.__Content
                                          : a("<div></div>")
                                                .append(b.__Content)
                                                .html()),
                                d && b._$origin.attr("title", d),
                                b._$origin.removeClass("tooltipstered"),
                                b._$origin
                                    .removeData("tooltipster-ns")
                                    .removeData("tooltipster-initialTitle");
                        } else
                            (c = a.grep(c, function(a, c) {
                                return a !== b.__namespace;
                            })),
                                b._$origin.data("tooltipster-ns", c);
                    b._trigger("destroyed"),
                        b._off(),
                        b.off(),
                        (b.__Content = null),
                        (b.__$emitterPrivate = null),
                        (b.__$emitterPublic = null),
                        (b.__options.parent = null),
                        (b._$origin = null),
                        (b._$tooltip = null),
                        (a.tooltipster.__instancesLatestArr = a.grep(
                            a.tooltipster.__instancesLatestArr,
                            function(a, c) {
                                return b !== a;
                            }
                        )),
                        clearInterval(b.__garbageCollector);
                }
                return b;
            },
            disable: function() {
                return this.__destroyed
                    ? (this.__destroyError(), this)
                    : (this._close(), (this.__enabled = !1), this);
            },
            elementOrigin: function() {
                return this.__destroyed
                    ? void this.__destroyError()
                    : this._$origin[0];
            },
            elementTooltip: function() {
                return this._$tooltip ? this._$tooltip[0] : null;
            },
            enable: function() {
                return (this.__enabled = !0), this;
            },
            hide: function(a) {
                return this.close(a);
            },
            instance: function() {
                return this;
            },
            off: function() {
                return (
                    this.__destroyed ||
                        this.__$emitterPublic.off.apply(
                            this.__$emitterPublic,
                            Array.prototype.slice.apply(arguments)
                        ),
                    this
                );
            },
            on: function() {
                return (
                    this.__destroyed
                        ? this.__destroyError()
                        : this.__$emitterPublic.on.apply(
                              this.__$emitterPublic,
                              Array.prototype.slice.apply(arguments)
                          ),
                    this
                );
            },
            one: function() {
                return (
                    this.__destroyed
                        ? this.__destroyError()
                        : this.__$emitterPublic.one.apply(
                              this.__$emitterPublic,
                              Array.prototype.slice.apply(arguments)
                          ),
                    this
                );
            },
            open: function(a) {
                return (
                    this.__destroyed
                        ? this.__destroyError()
                        : this._open(null, a),
                    this
                );
            },
            option: function(b, c) {
                return void 0 === c
                    ? this.__options[b]
                    : (this.__destroyed
                          ? this.__destroyError()
                          : ((this.__options[b] = c),
                            this.__optionsFormat(),
                            a.inArray(b, [
                                "trigger",
                                "triggerClose",
                                "triggerOpen"
                            ]) >= 0 && this.__prepareOrigin(),
                            "selfDestruction" === b && this.__prepareGC()),
                      this);
            },
            reposition: function(a, b) {
                var c = this;
                return (
                    c.__destroyed
                        ? c.__destroyError()
                        : "closed" != c.__state &&
                          d(c._$origin) &&
                          (b || d(c._$tooltip)) &&
                          (b || c._$tooltip.detach(),
                          (c.__Geometry = c.__geometry()),
                          c._trigger({
                              type: "reposition",
                              event: a,
                              helper: {
                                  geo: c.__Geometry
                              }
                          })),
                    c
                );
            },
            show: function(a) {
                return this.open(a);
            },
            status: function() {
                return {
                    destroyed: this.__destroyed,
                    enabled: this.__enabled,
                    open: "closed" !== this.__state,
                    state: this.__state
                };
            },
            triggerHandler: function() {
                return (
                    this.__destroyed
                        ? this.__destroyError()
                        : this.__$emitterPublic.triggerHandler.apply(
                              this.__$emitterPublic,
                              Array.prototype.slice.apply(arguments)
                          ),
                    this
                );
            }
        }),
        (a.fn.tooltipster = function() {
            var b = Array.prototype.slice.apply(arguments),
                c =
                    "You are using a single HTML element as content for several tooltips. You probably want to set the contentCloning option to TRUE.";
            if (0 === this.length) return this;
            if ("string" == typeof b[0]) {
                var d = "#*$~&";
                return (
                    this.each(function() {
                        var e = a(this).data("tooltipster-ns"),
                            f = e ? a(this).data(e[0]) : null;
                        if (!f)
                            throw new Error(
                                "You called Tooltipster's \"" +
                                    b[0] +
                                    '" method on an uninitialized element'
                            );
                        if ("function" != typeof f[b[0]])
                            throw new Error('Unknown method "' + b[0] + '"');
                        this.length > 1 &&
                            "content" == b[0] &&
                            (b[1] instanceof a ||
                                ("object" == typeof b[1] &&
                                    null != b[1] &&
                                    b[1].tagName)) &&
                            !f.__options.contentCloning &&
                            f.__options.debug &&
                            console.log(c);
                        var g = f[b[0]](b[1], b[2]);
                        return g !== f || "instance" === b[0]
                            ? ((d = g), !1)
                            : void 0;
                    }),
                    "#*$~&" !== d ? d : this
                );
            }
            a.tooltipster.__instancesLatestArr = [];
            var e = b[0] && void 0 !== b[0].multiple,
                g = (e && b[0].multiple) || (!e && f.multiple),
                h = b[0] && void 0 !== b[0].content,
                i = (h && b[0].content) || (!h && f.content),
                j = b[0] && void 0 !== b[0].contentCloning,
                k = (j && b[0].contentCloning) || (!j && f.contentCloning),
                l = b[0] && void 0 !== b[0].debug,
                m = (l && b[0].debug) || (!l && f.debug);
            return (
                this.length > 1 &&
                    (i instanceof a ||
                        ("object" == typeof i && null != i && i.tagName)) &&
                    !k &&
                    m &&
                    console.log(c),
                this.each(function() {
                    var c = !1,
                        d = a(this),
                        e = d.data("tooltipster-ns"),
                        f = null;
                    e
                        ? g
                            ? (c = !0)
                            : m &&
                              (console.log(
                                  "Tooltipster: one or more tooltips are already attached to the element below. Ignoring."
                              ),
                              console.log(this))
                        : (c = !0),
                        c &&
                            ((f = new a.Tooltipster(this, b[0])),
                            e || (e = []),
                            e.push(f.__namespace),
                            d.data("tooltipster-ns", e),
                            d.data(f.__namespace, f),
                            f.__options.functionInit &&
                                f.__options.functionInit.call(f, f, {
                                    origin: this
                                }),
                            f._trigger("init")),
                        a.tooltipster.__instancesLatestArr.push(f);
                }),
                this
            );
        }),
        (b.prototype = {
            __init: function(b) {
                (this.__$tooltip = b),
                    this.__$tooltip
                        .css({
                            left: 0,
                            overflow: "hidden",
                            position: "absolute",
                            top: 0
                        })
                        .find(".tooltipster-content")
                        .css("overflow", "auto"),
                    (this.$container = a(
                        '<div class="tooltipster-ruler"></div>'
                    )
                        .append(this.__$tooltip)
                        .appendTo(h.window.document.body));
            },
            __forceRedraw: function() {
                var a = this.__$tooltip.parent();
                this.__$tooltip.detach(), this.__$tooltip.appendTo(a);
            },
            constrain: function(a, b) {
                return (
                    (this.constraints = {
                        width: a,
                        height: b
                    }),
                    this.__$tooltip.css({
                        display: "block",
                        height: "",
                        overflow: "auto",
                        width: a
                    }),
                    this
                );
            },
            destroy: function() {
                this.__$tooltip
                    .detach()
                    .find(".tooltipster-content")
                    .css({
                        display: "",
                        overflow: ""
                    }),
                    this.$container.remove();
            },
            free: function() {
                return (
                    (this.constraints = null),
                    this.__$tooltip.css({
                        display: "",
                        height: "",
                        overflow: "visible",
                        width: ""
                    }),
                    this
                );
            },
            measure: function() {
                this.__forceRedraw();
                var a = this.__$tooltip[0].getBoundingClientRect(),
                    b = {
                        size: {
                            height: a.height || a.bottom - a.top,
                            width: a.width || a.right - a.left
                        }
                    };
                if (this.constraints) {
                    var c = this.__$tooltip.find(".tooltipster-content"),
                        d = this.__$tooltip.outerHeight(),
                        e = c[0].getBoundingClientRect(),
                        f = {
                            height: d <= this.constraints.height,
                            width:
                                a.width <= this.constraints.width &&
                                e.width >= c[0].scrollWidth - 1
                        };
                    b.fits = f.height && f.width;
                }
                return (
                    h.IE &&
                        h.IE <= 11 &&
                        b.size.width !==
                            h.window.document.documentElement.clientWidth &&
                        (b.size.width = Math.ceil(b.size.width) + 1),
                    b
                );
            }
        });
    var j = navigator.userAgent.toLowerCase();
    -1 != j.indexOf("msie")
        ? (h.IE = parseInt(j.split("msie")[1]))
        : -1 !== j.toLowerCase().indexOf("trident") &&
          -1 !== j.indexOf(" rv:11")
        ? (h.IE = 11)
        : -1 != j.toLowerCase().indexOf("edge/") &&
          (h.IE = parseInt(j.toLowerCase().split("edge/")[1]));
    var k = "tooltipster.sideTip";
    return (
        a.tooltipster._plugin({
            name: k,
            instance: {
                __defaults: function() {
                    return {
                        arrow: !0,
                        distance: 6,
                        functionPosition: null,
                        maxWidth: null,
                        minIntersection: 16,
                        minWidth: 0,
                        position: null,
                        side: "top",
                        viewportAware: !0
                    };
                },
                __init: function(a) {
                    var b = this;
                    (b.__instance = a),
                        (b.__namespace =
                            "tooltipster-sideTip-" +
                            Math.round(1e6 * Math.random())),
                        (b.__previousState = "closed"),
                        b.__options,
                        b.__optionsFormat(),
                        b.__instance._on("state." + b.__namespace, function(a) {
                            "closed" == a.state
                                ? b.__close()
                                : "appearing" == a.state &&
                                  "closed" == b.__previousState &&
                                  b.__create(),
                                (b.__previousState = a.state);
                        }),
                        b.__instance._on(
                            "options." + b.__namespace,
                            function() {
                                b.__optionsFormat();
                            }
                        ),
                        b.__instance._on(
                            "reposition." + b.__namespace,
                            function(a) {
                                b.__reposition(a.event, a.helper);
                            }
                        );
                },
                __close: function() {
                    this.__instance.content() instanceof a &&
                        this.__instance.content().detach(),
                        this.__instance._$tooltip.remove(),
                        (this.__instance._$tooltip = null);
                },
                __create: function() {
                    var b = a(
                        '<div class="tooltipster-base tooltipster-sidetip"><div class="tooltipster-box"><div class="tooltipster-content"></div></div><div class="tooltipster-arrow"><div class="tooltipster-arrow-uncropped"><div class="tooltipster-arrow-border"></div><div class="tooltipster-arrow-background"></div></div></div></div>'
                    );
                    this.__options.arrow ||
                        b
                            .find(".tooltipster-box")
                            .css("margin", 0)
                            .end()
                            .find(".tooltipster-arrow")
                            .hide(),
                        this.__options.minWidth &&
                            b.css("min-width", this.__options.minWidth + "px"),
                        this.__options.maxWidth &&
                            b.css("max-width", this.__options.maxWidth + "px"),
                        (this.__instance._$tooltip = b),
                        this.__instance._trigger("created");
                },
                __destroy: function() {
                    this.__instance._off("." + self.__namespace);
                },
                __optionsFormat: function() {
                    var b = this;
                    if (
                        ((b.__options = b.__instance._optionsExtract(
                            k,
                            b.__defaults()
                        )),
                        b.__options.position &&
                            (b.__options.side = b.__options.position),
                        "object" != typeof b.__options.distance &&
                            (b.__options.distance = [b.__options.distance]),
                        b.__options.distance.length < 4 &&
                            (void 0 === b.__options.distance[1] &&
                                (b.__options.distance[1] =
                                    b.__options.distance[0]),
                            void 0 === b.__options.distance[2] &&
                                (b.__options.distance[2] =
                                    b.__options.distance[0]),
                            void 0 === b.__options.distance[3] &&
                                (b.__options.distance[3] =
                                    b.__options.distance[1]),
                            (b.__options.distance = {
                                top: b.__options.distance[0],
                                right: b.__options.distance[1],
                                bottom: b.__options.distance[2],
                                left: b.__options.distance[3]
                            })),
                        "string" == typeof b.__options.side)
                    ) {
                        var c = {
                            top: "bottom",
                            right: "left",
                            bottom: "top",
                            left: "right"
                        };
                        (b.__options.side = [
                            b.__options.side,
                            c[b.__options.side]
                        ]),
                            "left" == b.__options.side[0] ||
                            "right" == b.__options.side[0]
                                ? b.__options.side.push("top", "bottom")
                                : b.__options.side.push("right", "left");
                    }
                    6 === a.tooltipster._env.IE &&
                        b.__options.arrow !== !0 &&
                        (b.__options.arrow = !1);
                },
                __reposition: function(b, c) {
                    var d,
                        e = this,
                        f = e.__targetFind(c),
                        g = [];
                    e.__instance._$tooltip.detach();
                    var h = e.__instance._$tooltip.clone(),
                        i = a.tooltipster._getRuler(h),
                        j = !1,
                        k = e.__instance.option("animation");
                    switch (
                        (k && h.removeClass("tooltipster-" + k),
                        a.each(["window", "document"], function(d, k) {
                            var l = null;
                            if (
                                (e.__instance._trigger({
                                    container: k,
                                    helper: c,
                                    satisfied: j,
                                    takeTest: function(a) {
                                        l = a;
                                    },
                                    results: g,
                                    type: "positionTest"
                                }),
                                1 == l ||
                                    (0 != l &&
                                        0 == j &&
                                        ("window" != k ||
                                            e.__options.viewportAware)))
                            )
                                for (
                                    var d = 0;
                                    d < e.__options.side.length;
                                    d++
                                ) {
                                    var m = {
                                            horizontal: 0,
                                            vertical: 0
                                        },
                                        n = e.__options.side[d];
                                    "top" == n || "bottom" == n
                                        ? (m.vertical = e.__options.distance[n])
                                        : (m.horizontal =
                                              e.__options.distance[n]),
                                        e.__sideChange(h, n),
                                        a.each(
                                            ["natural", "constrained"],
                                            function(a, d) {
                                                if (
                                                    ((l = null),
                                                    e.__instance._trigger({
                                                        container: k,
                                                        event: b,
                                                        helper: c,
                                                        mode: d,
                                                        results: g,
                                                        satisfied: j,
                                                        side: n,
                                                        takeTest: function(a) {
                                                            l = a;
                                                        },
                                                        type: "positionTest"
                                                    }),
                                                    1 == l ||
                                                        (0 != l && 0 == j))
                                                ) {
                                                    var h = {
                                                            container: k,
                                                            distance: m,
                                                            fits: null,
                                                            mode: d,
                                                            outerSize: null,
                                                            side: n,
                                                            size: null,
                                                            target: f[n],
                                                            whole: null
                                                        },
                                                        o =
                                                            "natural" == d
                                                                ? i.free()
                                                                : i.constrain(
                                                                      c.geo
                                                                          .available[
                                                                          k
                                                                      ][n]
                                                                          .width -
                                                                          m.horizontal,
                                                                      c.geo
                                                                          .available[
                                                                          k
                                                                      ][n]
                                                                          .height -
                                                                          m.vertical
                                                                  ),
                                                        p = o.measure();
                                                    if (
                                                        ((h.size = p.size),
                                                        (h.outerSize = {
                                                            height:
                                                                p.size.height +
                                                                m.vertical,
                                                            width:
                                                                p.size.width +
                                                                m.horizontal
                                                        }),
                                                        "natural" == d
                                                            ? c.geo.available[
                                                                  k
                                                              ][n].width >=
                                                                  h.outerSize
                                                                      .width &&
                                                              c.geo.available[
                                                                  k
                                                              ][n].height >=
                                                                  h.outerSize
                                                                      .height
                                                                ? (h.fits = !0)
                                                                : (h.fits = !1)
                                                            : (h.fits = p.fits),
                                                        "window" == k &&
                                                            (h.fits
                                                                ? "top" == n ||
                                                                  "bottom" == n
                                                                    ? (h.whole =
                                                                          c.geo
                                                                              .origin
                                                                              .windowOffset
                                                                              .right >=
                                                                              e
                                                                                  .__options
                                                                                  .minIntersection &&
                                                                          c.geo
                                                                              .window
                                                                              .size
                                                                              .width -
                                                                              c
                                                                                  .geo
                                                                                  .origin
                                                                                  .windowOffset
                                                                                  .left >=
                                                                              e
                                                                                  .__options
                                                                                  .minIntersection)
                                                                    : (h.whole =
                                                                          c.geo
                                                                              .origin
                                                                              .windowOffset
                                                                              .bottom >=
                                                                              e
                                                                                  .__options
                                                                                  .minIntersection &&
                                                                          c.geo
                                                                              .window
                                                                              .size
                                                                              .height -
                                                                              c
                                                                                  .geo
                                                                                  .origin
                                                                                  .windowOffset
                                                                                  .top >=
                                                                              e
                                                                                  .__options
                                                                                  .minIntersection)
                                                                : (h.whole = !1)),
                                                        g.push(h),
                                                        h.whole)
                                                    )
                                                        j = !0;
                                                    else if (
                                                        "natural" == h.mode &&
                                                        (h.fits ||
                                                            h.size.width <=
                                                                c.geo.available[
                                                                    k
                                                                ][n].width)
                                                    )
                                                        return !1;
                                                }
                                            }
                                        );
                                }
                        }),
                        e.__instance._trigger({
                            edit: function(a) {
                                g = a;
                            },
                            event: b,
                            helper: c,
                            results: g,
                            type: "positionTested"
                        }),
                        g.sort(function(a, b) {
                            if (a.whole && !b.whole) return -1;
                            if (!a.whole && b.whole) return 1;
                            if (a.whole && b.whole) {
                                var c = e.__options.side.indexOf(a.side),
                                    d = e.__options.side.indexOf(b.side);
                                return d > c
                                    ? -1
                                    : c > d
                                    ? 1
                                    : "natural" == a.mode
                                    ? -1
                                    : 1;
                            }
                            if (a.fits && !b.fits) return -1;
                            if (!a.fits && b.fits) return 1;
                            if (a.fits && b.fits) {
                                var c = e.__options.side.indexOf(a.side),
                                    d = e.__options.side.indexOf(b.side);
                                return d > c
                                    ? -1
                                    : c > d
                                    ? 1
                                    : "natural" == a.mode
                                    ? -1
                                    : 1;
                            }
                            return "document" == a.container &&
                                "bottom" == a.side &&
                                "natural" == a.mode
                                ? -1
                                : 1;
                        }),
                        (d = g[0]),
                        (d.coord = {}),
                        d.side)
                    ) {
                        case "left":
                        case "right":
                            d.coord.top = Math.floor(
                                d.target - d.size.height / 2
                            );
                            break;
                        case "bottom":
                        case "top":
                            d.coord.left = Math.floor(
                                d.target - d.size.width / 2
                            );
                    }
                    switch (d.side) {
                        case "left":
                            d.coord.left =
                                c.geo.origin.windowOffset.left -
                                d.outerSize.width;
                            break;
                        case "right":
                            d.coord.left =
                                c.geo.origin.windowOffset.right +
                                d.distance.horizontal;
                            break;
                        case "top":
                            d.coord.top =
                                c.geo.origin.windowOffset.top -
                                d.outerSize.height;
                            break;
                        case "bottom":
                            d.coord.top =
                                c.geo.origin.windowOffset.bottom +
                                d.distance.vertical;
                    }
                    "window" == d.container
                        ? "top" == d.side || "bottom" == d.side
                            ? d.coord.left < 0
                                ? c.geo.origin.windowOffset.right -
                                      this.__options.minIntersection >=
                                  0
                                    ? (d.coord.left = 0)
                                    : (d.coord.left =
                                          c.geo.origin.windowOffset.right -
                                          this.__options.minIntersection -
                                          1)
                                : d.coord.left >
                                      c.geo.window.size.width - d.size.width &&
                                  (c.geo.origin.windowOffset.left +
                                      this.__options.minIntersection <=
                                  c.geo.window.size.width
                                      ? (d.coord.left =
                                            c.geo.window.size.width -
                                            d.size.width)
                                      : (d.coord.left =
                                            c.geo.origin.windowOffset.left +
                                            this.__options.minIntersection +
                                            1 -
                                            d.size.width))
                            : d.coord.top < 0
                            ? c.geo.origin.windowOffset.bottom -
                                  this.__options.minIntersection >=
                              0
                                ? (d.coord.top = 0)
                                : (d.coord.top =
                                      c.geo.origin.windowOffset.bottom -
                                      this.__options.minIntersection -
                                      1)
                            : d.coord.top >
                                  c.geo.window.size.height - d.size.height &&
                              (c.geo.origin.windowOffset.top +
                                  this.__options.minIntersection <=
                              c.geo.window.size.height
                                  ? (d.coord.top =
                                        c.geo.window.size.height -
                                        d.size.height)
                                  : (d.coord.top =
                                        c.geo.origin.windowOffset.top +
                                        this.__options.minIntersection +
                                        1 -
                                        d.size.height))
                        : (d.coord.left >
                              c.geo.window.size.width - d.size.width &&
                              (d.coord.left =
                                  c.geo.window.size.width - d.size.width),
                          d.coord.left < 0 && (d.coord.left = 0)),
                        e.__sideChange(h, d.side),
                        (c.tooltipClone = h[0]),
                        (c.tooltipParent = e.__instance.option(
                            "parent"
                        ).parent[0]),
                        (c.mode = d.mode),
                        (c.whole = d.whole),
                        (c.origin = e.__instance._$origin[0]),
                        (c.tooltip = e.__instance._$tooltip[0]),
                        delete d.container,
                        delete d.fits,
                        delete d.mode,
                        delete d.outerSize,
                        delete d.whole,
                        (d.distance =
                            d.distance.horizontal || d.distance.vertical);
                    var l = a.extend(!0, {}, d);
                    if (
                        (e.__instance._trigger({
                            edit: function(a) {
                                d = a;
                            },
                            event: b,
                            helper: c,
                            position: l,
                            type: "position"
                        }),
                        e.__options.functionPosition)
                    ) {
                        var m = e.__options.functionPosition.call(
                            e,
                            e.__instance,
                            c,
                            l
                        );
                        m && (d = m);
                    }
                    i.destroy();
                    var n, o;
                    "top" == d.side || "bottom" == d.side
                        ? ((n = {
                              prop: "left",
                              val: d.target - d.coord.left
                          }),
                          (o = d.size.width - this.__options.minIntersection))
                        : ((n = {
                              prop: "top",
                              val: d.target - d.coord.top
                          }),
                          (o = d.size.height - this.__options.minIntersection)),
                        n.val < this.__options.minIntersection
                            ? (n.val = this.__options.minIntersection)
                            : n.val > o && (n.val = o);
                    var p;
                    (p = c.geo.origin.fixedLineage
                        ? c.geo.origin.windowOffset
                        : {
                              left:
                                  c.geo.origin.windowOffset.left +
                                  c.geo.window.scroll.left,
                              top:
                                  c.geo.origin.windowOffset.top +
                                  c.geo.window.scroll.top
                          }),
                        (d.coord = {
                            left:
                                p.left +
                                (d.coord.left - c.geo.origin.windowOffset.left),
                            top:
                                p.top +
                                (d.coord.top - c.geo.origin.windowOffset.top)
                        }),
                        e.__sideChange(e.__instance._$tooltip, d.side),
                        c.geo.origin.fixedLineage
                            ? e.__instance._$tooltip.css("position", "fixed")
                            : e.__instance._$tooltip.css("position", ""),
                        e.__instance._$tooltip
                            .css({
                                left: d.coord.left,
                                top: d.coord.top,
                                height: d.size.height,
                                width: d.size.width
                            })
                            .find(".tooltipster-arrow")
                            .css({
                                left: "",
                                top: ""
                            })
                            .css(n.prop, n.val),
                        e.__instance._$tooltip.appendTo(
                            e.__instance.option("parent")
                        ),
                        e.__instance._trigger({
                            type: "repositioned",
                            event: b,
                            position: d
                        });
                },
                __sideChange: function(a, b) {
                    a.removeClass("tooltipster-bottom")
                        .removeClass("tooltipster-left")
                        .removeClass("tooltipster-right")
                        .removeClass("tooltipster-top")
                        .addClass("tooltipster-" + b);
                },
                __targetFind: function(a) {
                    var b = {},
                        c = this.__instance._$origin[0].getClientRects();
                    if (c.length > 1) {
                        var d = this.__instance._$origin.css("opacity");
                        1 == d &&
                            (this.__instance._$origin.css("opacity", 0.99),
                            (c = this.__instance._$origin[0].getClientRects()),
                            this.__instance._$origin.css("opacity", 1));
                    }
                    if (c.length < 2)
                        (b.top = Math.floor(
                            a.geo.origin.windowOffset.left +
                                a.geo.origin.size.width / 2
                        )),
                            (b.bottom = b.top),
                            (b.left = Math.floor(
                                a.geo.origin.windowOffset.top +
                                    a.geo.origin.size.height / 2
                            )),
                            (b.right = b.left);
                    else {
                        var e = c[0];
                        (b.top = Math.floor(e.left + (e.right - e.left) / 2)),
                            (e =
                                c.length > 2
                                    ? c[Math.ceil(c.length / 2) - 1]
                                    : c[0]),
                            (b.right = Math.floor(
                                e.top + (e.bottom - e.top) / 2
                            )),
                            (e = c[c.length - 1]),
                            (b.bottom = Math.floor(
                                e.left + (e.right - e.left) / 2
                            )),
                            (e =
                                c.length > 2
                                    ? c[Math.ceil((c.length + 1) / 2) - 1]
                                    : c[c.length - 1]),
                            (b.left = Math.floor(
                                e.top + (e.bottom - e.top) / 2
                            ));
                    }
                    return b;
                }
            }
        }),
        a
    );
});
("use strict");
var _slicedToArray = (function() {
    function sliceIterator(arr, i) {
        var _arr = [];
        var _n = !0;
        var _d = !1;
        var _e = undefined;
        try {
            for (
                var _i = arr[Symbol.iterator](), _s;
                !(_n = (_s = _i.next()).done);
                _n = !0
            ) {
                _arr.push(_s.value);
                if (i && _arr.length === i) break;
            }
        } catch (err) {
            _d = !0;
            _e = err;
        } finally {
            try {
                if (!_n && _i["return"]) _i["return"]();
            } finally {
                if (_d) throw _e;
            }
        }
        return _arr;
    }
    return function(arr, i) {
        if (Array.isArray(arr)) {
            return arr;
        } else if (Symbol.iterator in Object(arr)) {
            return sliceIterator(arr, i);
        } else {
            throw new TypeError(
                "Invalid attempt to destructure non-iterable instance"
            );
        }
    };
})();
var _createClass = (function() {
    function defineProperties(target, props) {
        for (var i = 0; i < props.length; i++) {
            var descriptor = props[i];
            descriptor.enumerable = descriptor.enumerable || !1;
            descriptor.configurable = !0;
            if ("value" in descriptor) descriptor.writable = !0;
            Object.defineProperty(target, descriptor.key, descriptor);
        }
    }
    return function(Constructor, protoProps, staticProps) {
        if (protoProps) defineProperties(Constructor.prototype, protoProps);
        if (staticProps) defineProperties(Constructor, staticProps);
        return Constructor;
    };
})();

function _typeof(obj) {
    return obj && typeof Symbol !== "undefined" && obj.constructor === Symbol
        ? "symbol"
        : typeof obj;
}

function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}
/**
 * A Twitter library in JavaScript
 *
 * @package   codebird
 * @version   3.0.0-dev
 * @author    Jublo Solutions <support@jublo.net>
 * @copyright 2010-2016 Jublo Solutions <support@jublo.net>
 * @license   http://opensource.org/licenses/GPL-3.0 GNU Public License 3.0
 * @link      https://github.com/jublonet/codebird-php
 */
(function() {
    var Codebird = (function() {
        function Codebird() {
            _classCallCheck(this, Codebird);
            this._oauth_consumer_key = null;
            this._oauth_consumer_secret = null;
            this._oauth_bearer_token = null;
            this._endpoint_base = "https://api.twitter.com/";
            this._endpoint_base_media = "https://upload.twitter.com/";
            this._endpoint = this._endpoint_base + "1.1/";
            this._endpoint_media = this._endpoint_base_media + "1.1/";
            this._endpoint_publish = "https://publish.twitter.com/";
            this._endpoint_oauth = this._endpoint_base;
            this._endpoint_proxy = "https://api.jublo.net/codebird/";
            this._use_proxy =
                typeof navigator !== "undefined" &&
                typeof navigator.userAgent !== "undefined";
            this._oauth_token = null;
            this._oauth_token_secret = null;
            this._version = "3.0.0-dev";
            this.b64_alphabet =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        }
        _createClass(Codebird, [
            {
                key: "setConsumerKey",
                value: function setConsumerKey(key, secret) {
                    this._oauth_consumer_key = key;
                    this._oauth_consumer_secret = secret;
                }
            },
            {
                key: "setBearerToken",
                value: function setBearerToken(token) {
                    this._oauth_bearer_token = token;
                }
            },
            {
                key: "getVersion",
                value: function getVersion() {
                    return this._version;
                }
            },
            {
                key: "setToken",
                value: function setToken(token, secret) {
                    this._oauth_token = token;
                    this._oauth_token_secret = secret;
                }
            },
            {
                key: "logout",
                value: function logout() {
                    this._oauth_token = this._oauth_token_secret = null;
                    return !0;
                }
            },
            {
                key: "setUseProxy",
                value: function setUseProxy(use_proxy) {
                    this._use_proxy = !!use_proxy;
                }
            },
            {
                key: "setProxy",
                value: function setProxy(proxy) {
                    if (!proxy.match(/\/$/)) {
                        proxy += "/";
                    }
                    this._endpoint_proxy = proxy;
                }
            },
            {
                key: "_url",
                value: function _url(data) {
                    if (
                        /boolean|number|string/.test(
                            typeof data === "undefined"
                                ? "undefined"
                                : _typeof(data)
                        )
                    ) {
                        return encodeURIComponent(data)
                            .replace(/!/g, "%21")
                            .replace(/'/g, "%27")
                            .replace(/\(/g, "%28")
                            .replace(/\)/g, "%29")
                            .replace(/\*/g, "%2A");
                    } else {
                        return "";
                    }
                }
            },
            {
                key: "_sha1",
                value: function _sha1(e) {
                    function n(e, b) {
                        e[b >> 5] |= 128 << (24 - (b % 32));
                        e[(((b + 64) >> 9) << 4) + 15] = b;
                        for (
                            var c = new Array(80),
                                a = 1732584193,
                                d = -271733879,
                                h = -1732584194,
                                k = 271733878,
                                g = -1009589776,
                                p = 0;
                            p < e.length;
                            p += 16
                        ) {
                            for (
                                var o = a, q = d, r = h, s = k, t = g, f = 0;
                                80 > f;
                                f++
                            ) {
                                var m = undefined;
                                if (f < 16) {
                                    m = e[p + f];
                                } else {
                                    m =
                                        c[f - 3] ^
                                        c[f - 8] ^
                                        c[f - 14] ^
                                        c[f - 16];
                                    m = (m << 1) | (m >>> 31);
                                }
                                c[f] = m;
                                m = l(
                                    l(
                                        (a << 5) | (a >>> 27),
                                        20 > f
                                            ? (d & h) | (~d & k)
                                            : 40 > f
                                            ? d ^ h ^ k
                                            : 60 > f
                                            ? (d & h) | (d & k) | (h & k)
                                            : d ^ h ^ k
                                    ),
                                    l(
                                        l(g, c[f]),
                                        20 > f
                                            ? 1518500249
                                            : 40 > f
                                            ? 1859775393
                                            : 60 > f
                                            ? -1894007588
                                            : -899497514
                                    )
                                );
                                g = k;
                                k = h;
                                h = (d << 30) | (d >>> 2);
                                d = a;
                                a = m;
                            }
                            a = l(a, o);
                            d = l(d, q);
                            h = l(h, r);
                            k = l(k, s);
                            g = l(g, t);
                        }
                        return [a, d, h, k, g];
                    }

                    function l(e, b) {
                        var c = (e & 65535) + (b & 65535);
                        return (
                            (((e >> 16) + (b >> 16) + (c >> 16)) << 16) |
                            (c & 65535)
                        );
                    }

                    function q(e) {
                        for (
                            var b = [], c = (1 << g) - 1, a = 0;
                            a < e.length * g;
                            a += g
                        ) {
                            b[a >> 5] |=
                                (e.charCodeAt(a / g) & c) << (24 - (a % 32));
                        }
                        return b;
                    }
                    var g = 8;
                    var b =
                        this._oauth_consumer_secret +
                        "&" +
                        (null !== this._oauth_token_secret
                            ? this._oauth_token_secret
                            : "");
                    if (this._oauth_consumer_secret === null) {
                        throw "To generate a hash, the consumer secret must be set.";
                    }
                    var c = q(b);
                    if (c.length > 16) {
                        c = n(c, b.length * g);
                    }
                    var bb = new Array(16);
                    for (var a = new Array(16), d = 0; d < 16; d++) {
                        a[d] = c[d] ^ 909522486;
                        bb[d] = c[d] ^ 1549556828;
                    }
                    c = n(a.concat(q(e)), 512 + e.length * g);
                    bb = n(bb.concat(c), 672);
                    b = "";
                    for (g = 0; g < 4 * bb.length; g += 3) {
                        for (
                            d =
                                (((bb[g >> 2] >> (8 * (3 - (g % 4)))) & 255) <<
                                    16) |
                                (((bb[(g + 1) >> 2] >>
                                    (8 * (3 - ((g + 1) % 4)))) &
                                    255) <<
                                    8) |
                                ((bb[(g + 2) >> 2] >>
                                    (8 * (3 - ((g + 2) % 4)))) &
                                    255),
                                e = 0;
                            4 > e;
                            e++
                        ) {
                            b =
                                8 * g + 6 * e > 32 * bb.length
                                    ? b + "="
                                    : b +
                                      this.b64_alphabet.charAt(
                                          (d >> (6 * (3 - e))) & 63
                                      );
                        }
                    }
                    return b;
                }
            },
            {
                key: "_base64_encode",
                value: function _base64_encode(a) {
                    var d = undefined,
                        e = undefined,
                        f = undefined,
                        b = undefined,
                        g = 0,
                        h = 0,
                        i = this.b64_alphabet,
                        c = [];
                    if (!a) {
                        return a;
                    }
                    do {
                        d = a.charCodeAt(g++);
                        e = a.charCodeAt(g++);
                        f = a.charCodeAt(g++);
                        b = (d << 16) | (e << 8) | f;
                        d = (b >> 18) & 63;
                        e = (b >> 12) & 63;
                        f = (b >> 6) & 63;
                        b &= 63;
                        c[h++] =
                            i.charAt(d) +
                            i.charAt(e) +
                            i.charAt(f) +
                            i.charAt(b);
                    } while (g < a.length);
                    i = c.join("");
                    a = a.length % 3;
                    return (a ? i.slice(0, a - 3) : i) + "===".slice(a || 3);
                }
            },
            {
                key: "_http_build_query",
                value: function _http_build_query(e, f, b) {
                    function g(c, a, d) {
                        var b = undefined,
                            e = [];
                        if (a === !0) {
                            a = "1";
                        } else if (a === !1) {
                            a = "0";
                        }
                        if (null !== a) {
                            if (
                                (typeof a === "undefined"
                                    ? "undefined"
                                    : _typeof(a)) === "object"
                            ) {
                                for (b in a) {
                                    if (a.hasOwnProperty(b) && a[b] !== null) {
                                        e.push(
                                            g.call(
                                                this,
                                                c + "[" + b + "]",
                                                a[b],
                                                d
                                            )
                                        );
                                    }
                                }
                                return e.join(d);
                            }
                            if (typeof a !== "function") {
                                return this._url(c) + "=" + this._url(a);
                            }
                            throw "There was an error processing for http_build_query().";
                        } else {
                            return "";
                        }
                    }
                    var d,
                        c,
                        h = [];
                    if (!b) {
                        b = "&";
                    }
                    for (c in e) {
                        if (!e.hasOwnProperty(c)) {
                            continue;
                        }
                        d = e[c];
                        if (f && !isNaN(c)) {
                            c = String(f) + c;
                        }
                        d = g.call(this, c, d, b);
                        if (d !== "") {
                            h.push(d);
                        }
                    }
                    return h.join(b);
                }
            },
            {
                key: "_nonce",
                value: function _nonce() {
                    var length =
                        arguments.length <= 0 || arguments[0] === undefined
                            ? 8
                            : arguments[0];
                    if (length < 1) {
                        throw "Invalid nonce length.";
                    }
                    var nonce = "";
                    for (var i = 0; i < length; i++) {
                        var character = Math.floor(Math.random() * 61);
                        nonce += this.b64_alphabet.substring(
                            character,
                            character + 1
                        );
                    }
                    return nonce;
                }
            },
            {
                key: "_ksort",
                value: function _ksort(input_arr) {
                    var keys = [],
                        sorter = undefined,
                        k = undefined;
                    sorter = function(a, b) {
                        var a_float = parseFloat(a),
                            b_float = parseFloat(b),
                            a_numeric = a_float + "" === a,
                            b_numeric = b_float + "" === b;
                        if (a_numeric && b_numeric) {
                            return a_float > b_float
                                ? 1
                                : a_float < b_float
                                ? -1
                                : 0;
                        } else if (a_numeric && !b_numeric) {
                            return 1;
                        } else if (!a_numeric && b_numeric) {
                            return -1;
                        }
                        return a > b ? 1 : a < b ? -1 : 0;
                    };
                    for (k in input_arr) {
                        if (input_arr.hasOwnProperty(k)) {
                            keys.push(k);
                        }
                    }
                    keys.sort(sorter);
                    return keys;
                }
            },
            {
                key: "_clone",
                value: function _clone(obj) {
                    var clone = {};
                    for (var i in obj) {
                        if (_typeof(obj[i]) === "object") {
                            clone[i] = this._clone(obj[i]);
                        } else {
                            clone[i] = obj[i];
                        }
                    }
                    return clone;
                }
            },
            {
                key: "_getXmlRequestObject",
                value: function _getXmlRequestObject() {
                    var xml = null;
                    if (
                        (typeof window === "undefined"
                            ? "undefined"
                            : _typeof(window)) === "object" &&
                        window &&
                        typeof window.XMLHttpRequest !== "undefined"
                    ) {
                        xml = new window.XMLHttpRequest();
                    } else if (
                        (typeof Ti === "undefined"
                            ? "undefined"
                            : _typeof(Ti)) === "object" &&
                        Ti &&
                        typeof Ti.Network.createHTTPClient !== "undefined"
                    ) {
                        xml = Ti.Network.createHTTPClient();
                    } else if (typeof ActiveXObject !== "undefined") {
                        try {
                            xml = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            throw "ActiveXObject object not defined.";
                        }
                    } else if (typeof require === "function") {
                        var XMLHttpRequest;
                        try {
                            XMLHttpRequest = require("xmlhttprequest")
                                .XMLHttpRequest;
                            xml = new XMLHttpRequest();
                        } catch (e1) {
                            try {
                                XMLHttpRequest = require("xhr2");
                                xml = new XMLHttpRequest();
                            } catch (e2) {
                                throw "xhr2 object not defined, cancelling.";
                            }
                        }
                    }
                    return xml;
                }
            },
            {
                key: "_parse_str",
                value: function _parse_str(str, array) {
                    var glue1 = "=",
                        glue2 = "&",
                        array2 = String(str)
                            .replace(/^&?([\s\S]*?)&?$/, "$1")
                            .split(glue2),
                        i,
                        j,
                        chr,
                        tmp,
                        key,
                        value,
                        bracket,
                        keys,
                        evalStr,
                        fixStr = function fixStr(str) {
                            return decodeURIComponent(str)
                                .replace(/([\\"'])/g, "\\$1")
                                .replace(/\n/g, "\\n")
                                .replace(/\r/g, "\\r");
                        };
                    if (!array) {
                        array = this.window;
                    }
                    for (i = 0; i < array2.length; i++) {
                        tmp = array2[i].split(glue1);
                        if (tmp.length < 2) {
                            tmp = [tmp, ""];
                        }
                        key = fixStr(tmp[0]);
                        value = fixStr(tmp[1]);
                        while (key.charAt(0) === " ") {
                            key = key.substr(1);
                        }
                        if (key.indexOf("\0") > -1) {
                            key = key.substr(0, key.indexOf("\0"));
                        }
                        if (key && key.charAt(0) !== "[") {
                            keys = [];
                            bracket = 0;
                            for (j = 0; j < key.length; j++) {
                                if (key.charAt(j) === "[" && !bracket) {
                                    bracket = j + 1;
                                } else if (key.charAt(j) === "]") {
                                    if (bracket) {
                                        if (!keys.length) {
                                            keys.push(
                                                key.substr(0, bracket - 1)
                                            );
                                        }
                                        keys.push(
                                            key.substr(bracket, j - bracket)
                                        );
                                        bracket = 0;
                                        if (key.charAt(j + 1) !== "[") {
                                            break;
                                        }
                                    }
                                }
                            }
                            if (!keys.length) {
                                keys = [key];
                            }
                            for (j = 0; j < keys[0].length; j++) {
                                chr = keys[0].charAt(j);
                                if (chr === " " || chr === "." || chr === "[") {
                                    keys[0] =
                                        keys[0].substr(0, j) +
                                        "_" +
                                        keys[0].substr(j + 1);
                                }
                                if (chr === "[") {
                                    break;
                                }
                            }
                            evalStr = "array";
                            for (j = 0; j < keys.length; j++) {
                                key = keys[j];
                                if ((key !== "" && key !== " ") || j === 0) {
                                    key = "'" + key + "'";
                                } else {
                                    key = eval(evalStr + ".push([]);") - 1;
                                }
                                evalStr += "[" + key + "]";
                                if (
                                    j !== keys.length - 1 &&
                                    eval("typeof " + evalStr) === "undefined"
                                ) {
                                    eval(evalStr + " = [];");
                                }
                            }
                            evalStr += " = '" + value + "';\n";
                            eval(evalStr);
                        }
                    }
                }
            },
            {
                key: "getApiMethods",
                value: function getApiMethods() {
                    var httpmethods = {
                        GET: [
                            "account/settings",
                            "account/verify_credentials",
                            "application/rate_limit_status",
                            "blocks/ids",
                            "blocks/list",
                            "collections/entries",
                            "collections/list",
                            "collections/show",
                            "direct_messages",
                            "direct_messages/sent",
                            "direct_messages/show",
                            "favorites/list",
                            "followers/ids",
                            "followers/list",
                            "friends/ids",
                            "friends/list",
                            "friendships/incoming",
                            "friendships/lookup",
                            "friendships/lookup",
                            "friendships/no_retweets/ids",
                            "friendships/outgoing",
                            "friendships/show",
                            "geo/id/:place_id",
                            "geo/reverse_geocode",
                            "geo/search",
                            "geo/similar_places",
                            "help/configuration",
                            "help/languages",
                            "help/privacy",
                            "help/tos",
                            "lists/list",
                            "lists/members",
                            "lists/members/show",
                            "lists/memberships",
                            "lists/ownerships",
                            "lists/show",
                            "lists/statuses",
                            "lists/subscribers",
                            "lists/subscribers/show",
                            "lists/subscriptions",
                            "mutes/users/ids",
                            "mutes/users/list",
                            "oauth/authenticate",
                            "oauth/authorize",
                            "saved_searches/list",
                            "saved_searches/show/:id",
                            "search/tweets",
                            "site",
                            "statuses/firehose",
                            "statuses/home_timeline",
                            "statuses/mentions_timeline",
                            "statuses/oembed",
                            "statuses/retweeters/ids",
                            "statuses/retweets/:id",
                            "statuses/retweets_of_me",
                            "statuses/sample",
                            "statuses/show/:id",
                            "statuses/user_timeline",
                            "trends/available",
                            "trends/closest",
                            "trends/place",
                            "user",
                            "users/contributees",
                            "users/contributors",
                            "users/profile_banner",
                            "users/search",
                            "users/show",
                            "users/suggestions",
                            "users/suggestions/:slug",
                            "users/suggestions/:slug/members"
                        ],
                        POST: [
                            "account/remove_profile_banner",
                            "account/settings__post",
                            "account/update_delivery_device",
                            "account/update_profile",
                            "account/update_profile_background_image",
                            "account/update_profile_banner",
                            "account/update_profile_colors",
                            "account/update_profile_image",
                            "blocks/create",
                            "blocks/destroy",
                            "collections/create",
                            "collections/destroy",
                            "collections/entries/add",
                            "collections/entries/curate",
                            "collections/entries/move",
                            "collections/entries/remove",
                            "collections/update",
                            "direct_messages/destroy",
                            "direct_messages/new",
                            "favorites/create",
                            "favorites/destroy",
                            "friendships/create",
                            "friendships/destroy",
                            "friendships/update",
                            "lists/create",
                            "lists/destroy",
                            "lists/members/create",
                            "lists/members/create_all",
                            "lists/members/destroy",
                            "lists/members/destroy_all",
                            "lists/subscribers/create",
                            "lists/subscribers/destroy",
                            "lists/update",
                            "media/upload",
                            "mutes/users/create",
                            "mutes/users/destroy",
                            "oauth/access_token",
                            "oauth/request_token",
                            "oauth2/invalidate_token",
                            "oauth2/token",
                            "saved_searches/create",
                            "saved_searches/destroy/:id",
                            "statuses/destroy/:id",
                            "statuses/filter",
                            "statuses/lookup",
                            "statuses/retweet/:id",
                            "statuses/unretweet/:id",
                            "statuses/update",
                            "statuses/update_with_media",
                            "users/lookup",
                            "users/report_spam"
                        ]
                    };
                    return httpmethods;
                }
            },
            {
                key: "_getDfd",
                value: function _getDfd() {
                    if (typeof window !== "undefined") {
                        if (
                            typeof window.jQuery !== "undefined" &&
                            window.jQuery.Deferred
                        ) {
                            return window.jQuery.Deferred();
                        }
                        if (typeof window.Q !== "undefined" && window.Q.defer) {
                            return window.Q.defer();
                        }
                        if (
                            typeof window.RSVP !== "undefined" &&
                            window.RSVP.defer
                        ) {
                            return window.RSVP.defer();
                        }
                        if (
                            typeof window.when !== "undefined" &&
                            window.when.defer
                        ) {
                            return window.when.defer();
                        }
                    }
                    if (typeof require !== "undefined") {
                        var promise_class = !1;
                        try {
                            promise_class = require("jquery");
                        } catch (e) {}
                        if (promise_class) {
                            return promise_class.Deferred();
                        }
                        try {
                            promise_class = require("q");
                        } catch (e) {}
                        if (!promise_class) {
                            try {
                                promise_class = require("rsvp");
                            } catch (e) {}
                        }
                        if (!promise_class) {
                            try {
                                promise_class = require("when");
                            } catch (e) {}
                        }
                        if (promise_class) {
                            try {
                                return promise_class.defer();
                            } catch (e) {}
                        }
                    }
                    return !1;
                }
            },
            {
                key: "_getPromise",
                value: function _getPromise(dfd) {
                    if (typeof dfd.promise === "function") {
                        return dfd.promise();
                    }
                    return dfd.promise;
                }
            },
            {
                key: "_parseApiParams",
                value: function _parseApiParams(params) {
                    var apiparams = {};
                    if (
                        (typeof params === "undefined"
                            ? "undefined"
                            : _typeof(params)) === "object"
                    ) {
                        apiparams = params;
                    } else {
                        this._parse_str(params, apiparams);
                    }
                    return apiparams;
                }
            },
            {
                key: "_stringifyNullBoolParams",
                value: function _stringifyNullBoolParams(apiparams) {
                    for (var key in apiparams) {
                        if (!apiparams.hasOwnProperty(key)) {
                            continue;
                        }
                        var value = apiparams[key];
                        if (value === null) {
                            apiparams[key] = "null";
                        } else if (value === !0 || value === !1) {
                            apiparams[key] = value ? "true" : "false";
                        }
                    }
                    return apiparams;
                }
            },
            {
                key: "_mapFnInsertSlashes",
                value: function _mapFnInsertSlashes(fn) {
                    return fn.split("_").join("/");
                }
            },
            {
                key: "_mapFnRestoreParamUnderscores",
                value: function _mapFnRestoreParamUnderscores(method) {
                    var url_parameters_with_underscore = [
                        "screen_name",
                        "place_id"
                    ];
                    var i = undefined,
                        param = undefined,
                        replacement_was = undefined;
                    for (
                        i = 0;
                        i < url_parameters_with_underscore.length;
                        i++
                    ) {
                        param = url_parameters_with_underscore[i].toUpperCase();
                        replacement_was = param.split("_").join("/");
                        method = method.split(replacement_was).join(param);
                    }
                    return method;
                }
            },
            {
                key: "_mapFnToApiMethod",
                value: function _mapFnToApiMethod(fn, apiparams) {
                    var method = "",
                        param = undefined,
                        i = undefined,
                        j = undefined;
                    method = this._mapFnInsertSlashes(fn);
                    method = this._mapFnRestoreParamUnderscores(method);
                    var method_template = method;
                    var match = method.match(/[A-Z_]{2,}/);
                    if (match) {
                        for (i = 0; i < match.length; i++) {
                            param = match[i];
                            var param_l = param.toLowerCase();
                            method_template = method_template
                                .split(param)
                                .join(":" + param_l);
                            if (typeof apiparams[param_l] === "undefined") {
                                for (j = 0; j < 26; j++) {
                                    method_template = method_template
                                        .split(String.fromCharCode(65 + j))
                                        .join(
                                            "_" + String.fromCharCode(97 + j)
                                        );
                                }
                                throw 'To call the templated method "' +
                                    method_template +
                                    '", specify the parameter value for "' +
                                    param_l +
                                    '".';
                            }
                            method = method
                                .split(param)
                                .join(apiparams[param_l]);
                            delete apiparams[param_l];
                        }
                    }
                    for (i = 0; i < 26; i++) {
                        method = method
                            .split(String.fromCharCode(65 + i))
                            .join("_" + String.fromCharCode(97 + i));
                        method_template = method_template
                            .split(String.fromCharCode(65 + i))
                            .join("_" + String.fromCharCode(97 + i));
                    }
                    return [method, method_template];
                }
            },
            {
                key: "_detectMethod",
                value: function _detectMethod(method, params) {
                    if (typeof params.httpmethod !== "undefined") {
                        var httpmethod = params.httpmethod;
                        delete params.httpmethod;
                        return httpmethod;
                    }
                    switch (method) {
                        case "account/settings":
                        case "account/login_verification_enrollment":
                        case "account/login_verification_request":
                            method = Object.keys(params).length
                                ? method + "__post"
                                : method;
                            break;
                    }
                    var apimethods = this.getApiMethods();
                    for (var httpmethod in apimethods) {
                        if (
                            apimethods.hasOwnProperty(httpmethod) &&
                            apimethods[httpmethod].indexOf(method) > -1
                        ) {
                            return httpmethod;
                        }
                    }
                    throw "Can't find HTTP method to use for \"" +
                        method +
                        '".';
                }
            },
            {
                key: "_detectMultipart",
                value: function _detectMultipart(method) {
                    var multiparts = [
                        "statuses/update_with_media",
                        "media/upload",
                        "account/update_profile_background_image",
                        "account/update_profile_image",
                        "account/update_profile_banner"
                    ];
                    return multiparts.indexOf(method) > -1;
                }
            },
            {
                key: "_getSignature",
                value: function _getSignature(
                    httpmethod,
                    method,
                    keys,
                    base_params
                ) {
                    var base_string = "",
                        key = undefined,
                        value = undefined;
                    for (var i = 0; i < keys.length; i++) {
                        key = keys[i];
                        value = base_params[key];
                        base_string += key + "=" + this._url(value) + "&";
                    }
                    base_string = base_string.substring(
                        0,
                        base_string.length - 1
                    );
                    return this._sha1(
                        httpmethod +
                            "&" +
                            this._url(method) +
                            "&" +
                            this._url(base_string)
                    );
                }
            },
            {
                key: "_time",
                value: function _time() {
                    return Math.round(new Date().getTime() / 1000);
                }
            },
            {
                key: "_sign",
                value: function _sign(httpmethod, method) {
                    var params =
                        arguments.length <= 2 || arguments[2] === undefined
                            ? {}
                            : arguments[2];
                    if (this._oauth_consumer_key === null) {
                        throw "To generate a signature, the consumer key must be set.";
                    }
                    var sign_params = {
                        consumer_key: this._oauth_consumer_key,
                        version: "1.0",
                        timestamp: this._time(),
                        nonce: this._nonce(),
                        signature_method: "HMAC-SHA1"
                    };
                    var sign_base_params = {};
                    for (var key in sign_params) {
                        if (!sign_params.hasOwnProperty(key)) {
                            continue;
                        }
                        var value = sign_params[key];
                        sign_base_params["oauth_" + key] = this._url(value);
                    }
                    if (this._oauth_token !== null) {
                        sign_base_params.oauth_token = this._url(
                            this._oauth_token
                        );
                    }
                    var oauth_params = this._clone(sign_base_params);
                    for (key in params) {
                        if (!params.hasOwnProperty(key)) {
                            continue;
                        }
                        sign_base_params[key] = params[key];
                    }
                    var keys = this._ksort(sign_base_params);
                    var signature = this._getSignature(
                        httpmethod,
                        method,
                        keys,
                        sign_base_params
                    );
                    params = oauth_params;
                    params.oauth_signature = signature;
                    keys = this._ksort(params);
                    var authorization = "OAuth ";
                    for (var i = 0; i < keys.length; i++) {
                        key = keys[i];
                        authorization +=
                            key + '="' + this._url(params[key]) + '", ';
                    }
                    return authorization.substring(0, authorization.length - 2);
                }
            },
            {
                key: "_buildMultipart",
                value: function _buildMultipart(method, params) {
                    if (!this._detectMultipart(method)) {
                        return;
                    }
                    var possible_methods = [
                        "media/upload",
                        "statuses/update_with_media",
                        "account/update_profile_background_image",
                        "account/update_profile_image",
                        "account/update_profile_banner"
                    ];
                    var possible_files = {
                        "media/upload": "media",
                        "statuses/update_with_media": "media[]",
                        "account/update_profile_background_image": "image",
                        "account/update_profile_image": "image",
                        "account/update_profile_banner": "banner"
                    };
                    if (possible_methods.indexOf(method) === -1) {
                        return;
                    }
                    possible_files = possible_files[method].split(" ");
                    var multipart_border =
                        "--------------------" + this._nonce();
                    var multipart_request = "";
                    for (var key in params) {
                        if (!params.hasOwnProperty(key)) {
                            continue;
                        }
                        multipart_request +=
                            "--" +
                            multipart_border +
                            '\r\nContent-Disposition: form-data; name="' +
                            key +
                            '"';
                        if (possible_files.indexOf(key) === -1) {
                            multipart_request +=
                                "\r\nContent-Transfer-Encoding: base64";
                        }
                        multipart_request += "\r\n\r\n" + params[key] + "\r\n";
                    }
                    multipart_request += "--" + multipart_border + "--";
                    return multipart_request;
                }
            },
            {
                key: "_detectMedia",
                value: function _detectMedia(method) {
                    var medias = ["media/upload"];
                    return medias.indexOf(method) > -1;
                }
            },
            {
                key: "_detectJsonBody",
                value: function _detectJsonBody(method) {
                    var json_bodies = ["collections/entries/curate"];
                    return json_bodies.indexOf(method) > -1;
                }
            },
            {
                key: "_getEndpoint",
                value: function _getEndpoint(method) {
                    var url = undefined;
                    if (method.substring(0, 5) === "oauth") {
                        url = this._endpoint_oauth + method;
                    } else if (this._detectMedia(method)) {
                        url = this._endpoint_media + method + ".json";
                    } else if (method === "statuses/oembed") {
                        url = this._endpoint_publish + "oembed";
                    } else {
                        url = this._endpoint + method + ".json";
                    }
                    return url;
                }
            },
            {
                key: "_parseApiReply",
                value: function _parseApiReply(reply) {
                    if (typeof reply !== "string" || reply === "") {
                        return {};
                    }
                    if (reply === "[]") {
                        return [];
                    }
                    var parsed = undefined;
                    try {
                        parsed = JSON.parse(reply);
                    } catch (e) {
                        parsed = {};
                        var elements = reply.split("&");
                        for (var i = 0; i < elements.length; i++) {
                            var element = elements[i].split("=", 2);
                            if (element.length > 1) {
                                parsed[element[0]] = decodeURIComponent(
                                    element[1]
                                );
                            } else {
                                parsed[element[0]] = null;
                            }
                        }
                    }
                    return parsed;
                }
            },
            {
                key: "oauth_authenticate",
                value: function oauth_authenticate() {
                    var params =
                        arguments.length <= 0 || arguments[0] === undefined
                            ? {}
                            : arguments[0];
                    var callback =
                        arguments.length <= 1 || arguments[1] === undefined
                            ? undefined
                            : arguments[1];
                    var type =
                        arguments.length <= 2 || arguments[2] === undefined
                            ? "authenticate"
                            : arguments[2];
                    var dfd = this._getDfd();
                    if (typeof params.force_login === "undefined") {
                        params.force_login = null;
                    }
                    if (typeof params.screen_name === "undefined") {
                        params.screen_name = null;
                    }
                    if (["authenticate", "authorize"].indexOf(type) === -1) {
                        type = "authenticate";
                    }
                    if (this._oauth_token === null) {
                        var error =
                            "To get the " +
                            type +
                            " URL, the OAuth token must be set.";
                        if (dfd) {
                            dfd.reject({
                                error: error
                            });
                            return this._getPromise(dfd);
                        }
                        throw error;
                    }
                    var url =
                        this._endpoint_oauth +
                        "oauth/" +
                        type +
                        "?oauth_token=" +
                        this._url(this._oauth_token);
                    if (params.force_login === !0) {
                        url += "&force_login=1";
                    }
                    if (params.screen_name !== null) {
                        url += "&screen_name=" + params.screen_name;
                    }
                    if (typeof callback === "function") {
                        callback(url);
                    }
                    if (dfd) {
                        dfd.resolve({
                            reply: url
                        });
                        return this._getPromise(dfd);
                    }
                    return !0;
                }
            },
            {
                key: "oauth_authorize",
                value: function oauth_authorize(params, callback) {
                    return this.oauth_authenticate(
                        params,
                        callback,
                        "authorize"
                    );
                }
            },
            {
                key: "oauth2_token",
                value: function oauth2_token(callback) {
                    var _this = this;
                    var dfd = this._getDfd();
                    if (this._oauth_consumer_key === null) {
                        var error =
                            "To obtain a bearer token, the consumer key must be set.";
                        if (dfd) {
                            dfd.reject({
                                error: error
                            });
                            return this._getPromise(dfd);
                        }
                        throw error;
                    }
                    if (!dfd && typeof callback === "undefined") {
                        callback = function() {};
                    }
                    var post_fields = "grant_type=client_credentials";
                    var url = this._endpoint_oauth + "oauth2/token";
                    if (this._use_proxy) {
                        url = url.replace(
                            this._endpoint_base,
                            this._endpoint_proxy
                        );
                    }
                    var xml = this._getXmlRequestObject();
                    if (xml === null) {
                        return;
                    }
                    xml.open("POST", url, !0);
                    xml.setRequestHeader(
                        "Content-Type",
                        "application/x-www-form-urlencoded"
                    );
                    xml.setRequestHeader(
                        (this._use_proxy ? "X-" : "") + "Authorization",
                        "Basic " +
                            this._base64_encode(
                                this._oauth_consumer_key +
                                    ":" +
                                    this._oauth_consumer_secret
                            )
                    );
                    xml.onreadystatechange = function() {
                        if (xml.readyState >= 4) {
                            var httpstatus = 12027;
                            try {
                                httpstatus = xml.status;
                            } catch (e) {}
                            var response = "";
                            try {
                                response = xml.responseText;
                            } catch (e) {}
                            var reply = _this._parseApiReply(response);
                            reply.httpstatus = httpstatus;
                            if (httpstatus === 200) {
                                _this.setBearerToken(reply.access_token);
                            }
                            if (typeof callback === "function") {
                                callback(reply);
                            }
                            if (dfd) {
                                dfd.resolve({
                                    reply: reply
                                });
                            }
                        }
                    };
                    xml.onerror = function(e) {
                        if (typeof callback === "function") {
                            callback(null, e);
                        }
                        if (dfd) {
                            dfd.reject(e);
                        }
                    };
                    xml.timeout = 30000;
                    xml.send(post_fields);
                    if (dfd) {
                        return this._getPromise(dfd);
                    }
                }
            },
            {
                key: "_callApi",
                value: function _callApi(httpmethod, method) {
                    var params =
                        arguments.length <= 2 || arguments[2] === undefined
                            ? {}
                            : arguments[2];
                    var multipart =
                        arguments.length <= 3 || arguments[3] === undefined
                            ? !1
                            : arguments[3];
                    var _this2 = this;
                    var app_only_auth =
                        arguments.length <= 4 || arguments[4] === undefined
                            ? !1
                            : arguments[4];
                    var callback =
                        arguments.length <= 5 || arguments[5] === undefined
                            ? function() {}
                            : arguments[5];
                    var dfd = this._getDfd();
                    var url = this._getEndpoint(method),
                        authorization = null;
                    var xml = this._getXmlRequestObject();
                    if (xml === null) {
                        return;
                    }
                    var post_fields = undefined;
                    if (httpmethod === "GET") {
                        var url_with_params = url;
                        if (JSON.stringify(params) !== "{}") {
                            url_with_params +=
                                "?" + this._http_build_query(params);
                        }
                        if (!app_only_auth) {
                            authorization = this._sign(httpmethod, url, params);
                        }
                        if (this._use_proxy) {
                            url_with_params = url_with_params
                                .replace(
                                    this._endpoint_base,
                                    this._endpoint_proxy
                                )
                                .replace(
                                    this._endpoint_base_media,
                                    this._endpoint_proxy
                                );
                        }
                        xml.open(httpmethod, url_with_params, !0);
                    } else {
                        if (multipart) {
                            if (!app_only_auth) {
                                authorization = this._sign(httpmethod, url, {});
                            }
                            params = this._buildMultipart(method, params);
                        } else if (this._detectJsonBody(method)) {
                            authorization = this._sign(httpmethod, url, {});
                            params = JSON.stringify(params);
                        } else {
                            if (!app_only_auth) {
                                authorization = this._sign(
                                    httpmethod,
                                    url,
                                    params
                                );
                            }
                            params = this._http_build_query(params);
                        }
                        post_fields = params;
                        if (this._use_proxy || multipart) {
                            url = url
                                .replace(
                                    this._endpoint_base,
                                    this._endpoint_proxy
                                )
                                .replace(
                                    this._endpoint_base_media,
                                    this._endpoint_proxy
                                );
                        }
                        xml.open(httpmethod, url, !0);
                        if (multipart) {
                            xml.setRequestHeader(
                                "Content-Type",
                                "multipart/form-data; boundary=" +
                                    post_fields.split("\r\n")[0].substring(2)
                            );
                        } else if (this._detectJsonBody(method)) {
                            xml.setRequestHeader(
                                "Content-Type",
                                "application/json"
                            );
                        } else {
                            xml.setRequestHeader(
                                "Content-Type",
                                "application/x-www-form-urlencoded"
                            );
                        }
                    }
                    if (app_only_auth) {
                        if (
                            this._oauth_consumer_key === null &&
                            this._oauth_bearer_token === null
                        ) {
                            var error =
                                "To make an app-only auth API request, consumer key or bearer token must be set.";
                            if (dfd) {
                                dfd.reject({
                                    error: error
                                });
                                return this._getPromise(dfd);
                            }
                            throw error;
                        }
                        if (this._oauth_bearer_token === null) {
                            if (dfd) {
                                return this.oauth2_token().then(function() {
                                    return _this2._callApi(
                                        httpmethod,
                                        method,
                                        params,
                                        multipart,
                                        app_only_auth,
                                        callback
                                    );
                                });
                            }
                            this.oauth2_token(function() {
                                _this2._callApi(
                                    httpmethod,
                                    method,
                                    params,
                                    multipart,
                                    app_only_auth,
                                    callback
                                );
                            });
                            return;
                        }
                        authorization = "Bearer " + this._oauth_bearer_token;
                    }
                    if (authorization !== null) {
                        xml.setRequestHeader(
                            (this._use_proxy ? "X-" : "") + "Authorization",
                            authorization
                        );
                    }
                    xml.onreadystatechange = function() {
                        if (xml.readyState >= 4) {
                            var httpstatus = 12027;
                            try {
                                httpstatus = xml.status;
                            } catch (e) {}
                            var response = "";
                            try {
                                response = xml.responseText;
                            } catch (e) {}
                            var reply = _this2._parseApiReply(response);
                            reply.httpstatus = httpstatus;
                            var rate = null;
                            if (
                                typeof xml.getResponseHeader !== "undefined" &&
                                xml.getResponseHeader("x-rate-limit-limit") !==
                                    ""
                            ) {
                                rate = {
                                    limit: xml.getResponseHeader(
                                        "x-rate-limit-limit"
                                    ),
                                    remaining: xml.getResponseHeader(
                                        "x-rate-limit-remaining"
                                    ),
                                    reset: xml.getResponseHeader(
                                        "x-rate-limit-reset"
                                    )
                                };
                            }
                            if (typeof callback === "function") {
                                callback(reply, rate);
                            }
                            if (dfd) {
                                dfd.resolve({
                                    reply: reply,
                                    rate: rate
                                });
                            }
                        }
                    };
                    xml.onerror = function(e) {
                        if (typeof callback === "function") {
                            callback(null, null, e);
                        }
                        if (dfd) {
                            dfd.reject(e);
                        }
                    };
                    xml.timeout = 30000;
                    xml.send(httpmethod === "GET" ? null : post_fields);
                    if (dfd) {
                        return this._getPromise(dfd);
                    }
                    return !0;
                }
            },
            {
                key: "__call",
                value: function __call(fn) {
                    var params =
                        arguments.length <= 1 || arguments[1] === undefined
                            ? {}
                            : arguments[1];
                    var callback = arguments[2];
                    var app_only_auth =
                        arguments.length <= 3 || arguments[3] === undefined
                            ? !1
                            : arguments[3];
                    if (
                        typeof callback !== "function" &&
                        typeof params === "function"
                    ) {
                        callback = params;
                        params = {};
                        if (typeof callback === "boolean") {
                            app_only_auth = callback;
                        }
                    } else if (typeof callback === "undefined") {
                        callback = function() {};
                    }
                    switch (fn) {
                        case "oauth_authenticate":
                        case "oauth_authorize":
                            return this[fn](params, callback);
                        case "oauth2_token":
                            return this[fn](callback);
                    }
                    var apiparams = this._parseApiParams(params);
                    apiparams = this._stringifyNullBoolParams(apiparams);
                    if (fn === "oauth_requestToken") {
                        this.setToken(null, null);
                    }
                    var _mapFnToApiMethod2 = this._mapFnToApiMethod(
                        fn,
                        apiparams
                    );
                    var _mapFnToApiMethod3 = _slicedToArray(
                        _mapFnToApiMethod2,
                        2
                    );
                    var method = _mapFnToApiMethod3[0];
                    var method_template = _mapFnToApiMethod3[1];
                    var httpmethod = this._detectMethod(
                        method_template,
                        apiparams
                    );
                    var multipart = this._detectMultipart(method_template);
                    return this._callApi(
                        httpmethod,
                        method,
                        apiparams,
                        multipart,
                        app_only_auth,
                        callback
                    );
                }
            }
        ]);
        return Codebird;
    })();
    if (
        (typeof module === "undefined" ? "undefined" : _typeof(module)) ===
            "object" &&
        module &&
        _typeof(module.exports) === "object"
    ) {
        module.exports = Codebird;
    } else {
        if (
            (typeof window === "undefined" ? "undefined" : _typeof(window)) ===
                "object" &&
            window
        ) {
            window.Codebird = Codebird;
        }
        if (typeof define === "function" && define.amd) {
            define("codebird", [], function() {
                return Codebird;
            });
        }
    }
})();
!(function() {
    "use strict";

    function e(n, t, r) {
        return ("string" == typeof t ? t : t.toString())
            .replace(n.define || a, function(e, t, o, a) {
                return (
                    0 === t.indexOf("def.") && (t = t.substring(4)),
                    t in r ||
                        (":" === o
                            ? (n.defineParams &&
                                  a.replace(n.defineParams, function(e, n, o) {
                                      r[t] = {
                                          arg: n,
                                          text: o
                                      };
                                  }),
                              t in r || (r[t] = a))
                            : new Function("def", "def['" + t + "']=" + a)(r)),
                    ""
                );
            })
            .replace(n.use || a, function(t, o) {
                n.useParams &&
                    (o = o.replace(n.useParams, function(e, n, t, o) {
                        if (r[t] && r[t].arg && o) {
                            var a = (t + ":" + o).replace(/'|\\/g, "_");
                            return (
                                (r.__exp = r.__exp || {}),
                                (r.__exp[a] = r[t].text.replace(
                                    new RegExp(
                                        "(^|[^\\w$])" + r[t].arg + "([^\\w$])",
                                        "g"
                                    ),
                                    "$1" + o + "$2"
                                )),
                                n + "def.__exp['" + a + "']"
                            );
                        }
                    }));
                var a = new Function("def", "return " + o)(r);
                return a ? e(n, a, r) : a;
            });
    }

    function n(e) {
        return e.replace(/\\('|\\)/g, "$1").replace(/[\r\t\n]/g, " ");
    }
    var t,
        r = {
            engine: "doT",
            version: "1.1.1",
            templateSettings: {
                evaluate: /\{\{([\s\S]+?(\}?)+)\}\}/g,
                interpolate: /\{\{=([\s\S]+?)\}\}/g,
                encode: /\{\{!([\s\S]+?)\}\}/g,
                use: /\{\{#([\s\S]+?)\}\}/g,
                useParams: /(^|[^\w$])def(?:\.|\[[\'\"])([\w$\.]+)(?:[\'\"]\])?\s*\:\s*([\w$\.]+|\"[^\"]+\"|\'[^\']+\'|\{[^\}]+\})/g,
                define: /\{\{##\s*([\w\.$]+)\s*(\:|=)([\s\S]+?)#\}\}/g,
                defineParams: /^\s*([\w$]+):([\s\S]+)/,
                conditional: /\{\{\?(\?)?\s*([\s\S]*?)\s*\}\}/g,
                iterate: /\{\{~\s*(?:\}\}|([\s\S]+?)\s*\:\s*([\w$]+)\s*(?:\:\s*([\w$]+))?\s*\}\})/g,
                varname: "it",
                strip: !0,
                append: !0,
                selfcontained: !1,
                doNotSkipEncoded: !1
            },
            template: void 0,
            compile: void 0,
            log: !0
        };
    (r.encodeHTMLSource = function(e) {
        var n = {
                "&": "&#38;",
                "<": "&#60;",
                ">": "&#62;",
                '"': "&#34;",
                "'": "&#39;",
                "/": "&#47;"
            },
            t = e ? /[&<>"'\/]/g : /&(?!#?\w+;)|<|>|"|'|\//g;
        return function(e) {
            return e
                ? e.toString().replace(t, function(e) {
                      return n[e] || e;
                  })
                : "";
        };
    }),
        (t = (function() {
            return this || (0, eval)("this");
        })()),
        "undefined" != typeof module && module.exports
            ? (module.exports = r)
            : "function" == typeof define && define.amd
            ? define(function() {
                  return r;
              })
            : (t.doT = r);
    var o = {
            append: {
                start: "'+(",
                end: ")+'",
                startencode: "'+encodeHTML("
            },
            split: {
                start: "';out+=(",
                end: ");out+='",
                startencode: "';out+=encodeHTML("
            }
        },
        a = /$^/;
    (r.template = function(c, i, u) {
        i = i || r.templateSettings;
        var d,
            s,
            p = i.append ? o.append : o.split,
            l = 0,
            f = i.use || i.define ? e(i, c, u || {}) : c;
        (f = (
            "var out='" +
            (i.strip
                ? f
                      .replace(/(^|\r|\n)\t* +| +\t*(\r|\n|$)/g, " ")
                      .replace(/\r|\n|\t|\/\*[\s\S]*?\*\//g, "")
                : f
            )
                .replace(/'|\\/g, "\\$&")
                .replace(i.interpolate || a, function(e, t) {
                    return p.start + n(t) + p.end;
                })
                .replace(i.encode || a, function(e, t) {
                    return (d = !0), p.startencode + n(t) + p.end;
                })
                .replace(i.conditional || a, function(e, t, r) {
                    return t
                        ? r
                            ? "';}else if(" + n(r) + "){out+='"
                            : "';}else{out+='"
                        : r
                        ? "';if(" + n(r) + "){out+='"
                        : "';}out+='";
                })
                .replace(i.iterate || a, function(e, t, r, o) {
                    return t
                        ? ((l += 1),
                          (s = o || "i" + l),
                          (t = n(t)),
                          "';var arr" +
                              l +
                              "=" +
                              t +
                              ";if(arr" +
                              l +
                              "){var " +
                              r +
                              "," +
                              s +
                              "=-1,l" +
                              l +
                              "=arr" +
                              l +
                              ".length-1;while(" +
                              s +
                              "<l" +
                              l +
                              "){" +
                              r +
                              "=arr" +
                              l +
                              "[" +
                              s +
                              "+=1];out+='")
                        : "';} } out+='";
                })
                .replace(i.evaluate || a, function(e, t) {
                    return "';" + n(t) + "out+='";
                }) +
            "';return out;"
        )
            .replace(/\n/g, "\\n")
            .replace(/\t/g, "\\t")
            .replace(/\r/g, "\\r")
            .replace(/(\s|;|\}|^|\{)out\+='';/g, "$1")
            .replace(/\+''/g, "")),
            d &&
                (i.selfcontained ||
                    !t ||
                    t._encodeHTML ||
                    (t._encodeHTML = r.encodeHTMLSource(i.doNotSkipEncoded)),
                (f =
                    "var encodeHTML = typeof _encodeHTML !== 'undefined'?_encodeHTML:(" +
                    r.encodeHTMLSource.toString() +
                    "(" +
                    (i.doNotSkipEncoded || "") +
                    "));" +
                    f));
        try {
            return new Function(i.varname, f);
        } catch (e) {
            throw ("undefined" != typeof console &&
                console.log("Could not create a template function:" + f),
            e);
        }
    }),
        (r.compile = function(e, n) {
            return r.template(e, null, n);
        });
})();
(function(global, factory) {
    typeof exports === "object" && typeof module !== "undefined"
        ? (module.exports = factory())
        : typeof define === "function" && define.amd
        ? define(factory)
        : (global.moment = factory());
})(this, function() {
    "use strict";
    var hookCallback;

    function hooks() {
        return hookCallback.apply(null, arguments);
    }

    function setHookCallback(callback) {
        hookCallback = callback;
    }

    function isArray(input) {
        return (
            input instanceof Array ||
            Object.prototype.toString.call(input) === "[object Array]"
        );
    }

    function isObject(input) {
        return (
            input != null &&
            Object.prototype.toString.call(input) === "[object Object]"
        );
    }

    function isObjectEmpty(obj) {
        if (Object.getOwnPropertyNames) {
            return Object.getOwnPropertyNames(obj).length === 0;
        } else {
            var k;
            for (k in obj) {
                if (obj.hasOwnProperty(k)) {
                    return !1;
                }
            }
            return !0;
        }
    }

    function isUndefined(input) {
        return input === void 0;
    }

    function isNumber(input) {
        return (
            typeof input === "number" ||
            Object.prototype.toString.call(input) === "[object Number]"
        );
    }

    function isDate(input) {
        return (
            input instanceof Date ||
            Object.prototype.toString.call(input) === "[object Date]"
        );
    }

    function map(arr, fn) {
        var res = [],
            i;
        for (i = 0; i < arr.length; ++i) {
            res.push(fn(arr[i], i));
        }
        return res;
    }

    function hasOwnProp(a, b) {
        return Object.prototype.hasOwnProperty.call(a, b);
    }

    function extend(a, b) {
        for (var i in b) {
            if (hasOwnProp(b, i)) {
                a[i] = b[i];
            }
        }
        if (hasOwnProp(b, "toString")) {
            a.toString = b.toString;
        }
        if (hasOwnProp(b, "valueOf")) {
            a.valueOf = b.valueOf;
        }
        return a;
    }

    function createUTC(input, format, locale, strict) {
        return createLocalOrUTC(input, format, locale, strict, !0).utc();
    }

    function defaultParsingFlags() {
        return {
            empty: !1,
            unusedTokens: [],
            unusedInput: [],
            overflow: -2,
            charsLeftOver: 0,
            nullInput: !1,
            invalidMonth: null,
            invalidFormat: !1,
            userInvalidated: !1,
            iso: !1,
            parsedDateParts: [],
            meridiem: null,
            rfc2822: !1,
            weekdayMismatch: !1
        };
    }

    function getParsingFlags(m) {
        if (m._pf == null) {
            m._pf = defaultParsingFlags();
        }
        return m._pf;
    }
    var some;
    if (Array.prototype.some) {
        some = Array.prototype.some;
    } else {
        some = function(fun) {
            var t = Object(this);
            var len = t.length >>> 0;
            for (var i = 0; i < len; i++) {
                if (i in t && fun.call(this, t[i], i, t)) {
                    return !0;
                }
            }
            return !1;
        };
    }

    function isValid(m) {
        if (m._isValid == null) {
            var flags = getParsingFlags(m);
            var parsedParts = some.call(flags.parsedDateParts, function(i) {
                return i != null;
            });
            var isNowValid =
                !isNaN(m._d.getTime()) &&
                flags.overflow < 0 &&
                !flags.empty &&
                !flags.invalidMonth &&
                !flags.invalidWeekday &&
                !flags.weekdayMismatch &&
                !flags.nullInput &&
                !flags.invalidFormat &&
                !flags.userInvalidated &&
                (!flags.meridiem || (flags.meridiem && parsedParts));
            if (m._strict) {
                isNowValid =
                    isNowValid &&
                    flags.charsLeftOver === 0 &&
                    flags.unusedTokens.length === 0 &&
                    flags.bigHour === undefined;
            }
            if (Object.isFrozen == null || !Object.isFrozen(m)) {
                m._isValid = isNowValid;
            } else {
                return isNowValid;
            }
        }
        return m._isValid;
    }

    function createInvalid(flags) {
        var m = createUTC(NaN);
        if (flags != null) {
            extend(getParsingFlags(m), flags);
        } else {
            getParsingFlags(m).userInvalidated = !0;
        }
        return m;
    }
    var momentProperties = (hooks.momentProperties = []);

    function copyConfig(to, from) {
        var i, prop, val;
        if (!isUndefined(from._isAMomentObject)) {
            to._isAMomentObject = from._isAMomentObject;
        }
        if (!isUndefined(from._i)) {
            to._i = from._i;
        }
        if (!isUndefined(from._f)) {
            to._f = from._f;
        }
        if (!isUndefined(from._l)) {
            to._l = from._l;
        }
        if (!isUndefined(from._strict)) {
            to._strict = from._strict;
        }
        if (!isUndefined(from._tzm)) {
            to._tzm = from._tzm;
        }
        if (!isUndefined(from._isUTC)) {
            to._isUTC = from._isUTC;
        }
        if (!isUndefined(from._offset)) {
            to._offset = from._offset;
        }
        if (!isUndefined(from._pf)) {
            to._pf = getParsingFlags(from);
        }
        if (!isUndefined(from._locale)) {
            to._locale = from._locale;
        }
        if (momentProperties.length > 0) {
            for (i = 0; i < momentProperties.length; i++) {
                prop = momentProperties[i];
                val = from[prop];
                if (!isUndefined(val)) {
                    to[prop] = val;
                }
            }
        }
        return to;
    }
    var updateInProgress = !1;

    function Moment(config) {
        copyConfig(this, config);
        this._d = new Date(config._d != null ? config._d.getTime() : NaN);
        if (!this.isValid()) {
            this._d = new Date(NaN);
        }
        if (updateInProgress === !1) {
            updateInProgress = !0;
            hooks.updateOffset(this);
            updateInProgress = !1;
        }
    }

    function isMoment(obj) {
        return (
            obj instanceof Moment ||
            (obj != null && obj._isAMomentObject != null)
        );
    }

    function absFloor(number) {
        if (number < 0) {
            return Math.ceil(number) || 0;
        } else {
            return Math.floor(number);
        }
    }

    function toInt(argumentForCoercion) {
        var coercedNumber = +argumentForCoercion,
            value = 0;
        if (coercedNumber !== 0 && isFinite(coercedNumber)) {
            value = absFloor(coercedNumber);
        }
        return value;
    }

    function compareArrays(array1, array2, dontConvert) {
        var len = Math.min(array1.length, array2.length),
            lengthDiff = Math.abs(array1.length - array2.length),
            diffs = 0,
            i;
        for (i = 0; i < len; i++) {
            if (
                (dontConvert && array1[i] !== array2[i]) ||
                (!dontConvert && toInt(array1[i]) !== toInt(array2[i]))
            ) {
                diffs++;
            }
        }
        return diffs + lengthDiff;
    }

    function warn(msg) {
        if (
            hooks.suppressDeprecationWarnings === !1 &&
            typeof console !== "undefined" &&
            console.warn
        ) {
            console.warn("Deprecation warning: " + msg);
        }
    }

    function deprecate(msg, fn) {
        var firstTime = !0;
        return extend(function() {
            if (hooks.deprecationHandler != null) {
                hooks.deprecationHandler(null, msg);
            }
            if (firstTime) {
                var args = [];
                var arg;
                for (var i = 0; i < arguments.length; i++) {
                    arg = "";
                    if (typeof arguments[i] === "object") {
                        arg += "\n[" + i + "] ";
                        for (var key in arguments[0]) {
                            arg += key + ": " + arguments[0][key] + ", ";
                        }
                        arg = arg.slice(0, -2);
                    } else {
                        arg = arguments[i];
                    }
                    args.push(arg);
                }
                warn(
                    msg +
                        "\nArguments: " +
                        Array.prototype.slice.call(args).join("") +
                        "\n" +
                        new Error().stack
                );
                firstTime = !1;
            }
            return fn.apply(this, arguments);
        }, fn);
    }
    var deprecations = {};

    function deprecateSimple(name, msg) {
        if (hooks.deprecationHandler != null) {
            hooks.deprecationHandler(name, msg);
        }
        if (!deprecations[name]) {
            warn(msg);
            deprecations[name] = !0;
        }
    }
    hooks.suppressDeprecationWarnings = !1;
    hooks.deprecationHandler = null;

    function isFunction(input) {
        return (
            input instanceof Function ||
            Object.prototype.toString.call(input) === "[object Function]"
        );
    }

    function set(config) {
        var prop, i;
        for (i in config) {
            prop = config[i];
            if (isFunction(prop)) {
                this[i] = prop;
            } else {
                this["_" + i] = prop;
            }
        }
        this._config = config;
        this._dayOfMonthOrdinalParseLenient = new RegExp(
            (this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) +
                "|" +
                /\d{1,2}/.source
        );
    }

    function mergeConfigs(parentConfig, childConfig) {
        var res = extend({}, parentConfig),
            prop;
        for (prop in childConfig) {
            if (hasOwnProp(childConfig, prop)) {
                if (
                    isObject(parentConfig[prop]) &&
                    isObject(childConfig[prop])
                ) {
                    res[prop] = {};
                    extend(res[prop], parentConfig[prop]);
                    extend(res[prop], childConfig[prop]);
                } else if (childConfig[prop] != null) {
                    res[prop] = childConfig[prop];
                } else {
                    delete res[prop];
                }
            }
        }
        for (prop in parentConfig) {
            if (
                hasOwnProp(parentConfig, prop) &&
                !hasOwnProp(childConfig, prop) &&
                isObject(parentConfig[prop])
            ) {
                res[prop] = extend({}, res[prop]);
            }
        }
        return res;
    }

    function Locale(config) {
        if (config != null) {
            this.set(config);
        }
    }
    var keys;
    if (Object.keys) {
        keys = Object.keys;
    } else {
        keys = function(obj) {
            var i,
                res = [];
            for (i in obj) {
                if (hasOwnProp(obj, i)) {
                    res.push(i);
                }
            }
            return res;
        };
    }
    var defaultCalendar = {
        sameDay: "[Today at] LT",
        nextDay: "[Tomorrow at] LT",
        nextWeek: "dddd [at] LT",
        lastDay: "[Yesterday at] LT",
        lastWeek: "[Last] dddd [at] LT",
        sameElse: "L"
    };

    function calendar(key, mom, now) {
        var output = this._calendar[key] || this._calendar.sameElse;
        return isFunction(output) ? output.call(mom, now) : output;
    }
    var defaultLongDateFormat = {
        LTS: "h:mm:ss A",
        LT: "h:mm A",
        L: "MM/DD/YYYY",
        LL: "MMMM D, YYYY",
        LLL: "MMMM D, YYYY h:mm A",
        LLLL: "dddd, MMMM D, YYYY h:mm A"
    };

    function longDateFormat(key) {
        var format = this._longDateFormat[key],
            formatUpper = this._longDateFormat[key.toUpperCase()];
        if (format || !formatUpper) {
            return format;
        }
        this._longDateFormat[key] = formatUpper.replace(
            /MMMM|MM|DD|dddd/g,
            function(val) {
                return val.slice(1);
            }
        );
        return this._longDateFormat[key];
    }
    var defaultInvalidDate = "Invalid date";

    function invalidDate() {
        return this._invalidDate;
    }
    var defaultOrdinal = "%d";
    var defaultDayOfMonthOrdinalParse = /\d{1,2}/;

    function ordinal(number) {
        return this._ordinal.replace("%d", number);
    }
    var defaultRelativeTime = {
        future: "in %s",
        past: "%s ago",
        s: "a few seconds",
        ss: "%d seconds",
        m: "a minute",
        mm: "%d minutes",
        h: "an hour",
        hh: "%d hours",
        d: "a day",
        dd: "%d days",
        M: "a month",
        MM: "%d months",
        y: "a year",
        yy: "%d years"
    };

    function relativeTime(number, withoutSuffix, string, isFuture) {
        var output = this._relativeTime[string];
        return isFunction(output)
            ? output(number, withoutSuffix, string, isFuture)
            : output.replace(/%d/i, number);
    }

    function pastFuture(diff, output) {
        var format = this._relativeTime[diff > 0 ? "future" : "past"];
        return isFunction(format)
            ? format(output)
            : format.replace(/%s/i, output);
    }
    var aliases = {};

    function addUnitAlias(unit, shorthand) {
        var lowerCase = unit.toLowerCase();
        aliases[lowerCase] = aliases[lowerCase + "s"] = aliases[
            shorthand
        ] = unit;
    }

    function normalizeUnits(units) {
        return typeof units === "string"
            ? aliases[units] || aliases[units.toLowerCase()]
            : undefined;
    }

    function normalizeObjectUnits(inputObject) {
        var normalizedInput = {},
            normalizedProp,
            prop;
        for (prop in inputObject) {
            if (hasOwnProp(inputObject, prop)) {
                normalizedProp = normalizeUnits(prop);
                if (normalizedProp) {
                    normalizedInput[normalizedProp] = inputObject[prop];
                }
            }
        }
        return normalizedInput;
    }
    var priorities = {};

    function addUnitPriority(unit, priority) {
        priorities[unit] = priority;
    }

    function getPrioritizedUnits(unitsObj) {
        var units = [];
        for (var u in unitsObj) {
            units.push({
                unit: u,
                priority: priorities[u]
            });
        }
        units.sort(function(a, b) {
            return a.priority - b.priority;
        });
        return units;
    }

    function zeroFill(number, targetLength, forceSign) {
        var absNumber = "" + Math.abs(number),
            zerosToFill = targetLength - absNumber.length,
            sign = number >= 0;
        return (
            (sign ? (forceSign ? "+" : "") : "-") +
            Math.pow(10, Math.max(0, zerosToFill))
                .toString()
                .substr(1) +
            absNumber
        );
    }
    var formattingTokens = /(\[[^\[]*\])|(\\)?([Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Qo?|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g;
    var localFormattingTokens = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g;
    var formatFunctions = {};
    var formatTokenFunctions = {};

    function addFormatToken(token, padded, ordinal, callback) {
        var func = callback;
        if (typeof callback === "string") {
            func = function() {
                return this[callback]();
            };
        }
        if (token) {
            formatTokenFunctions[token] = func;
        }
        if (padded) {
            formatTokenFunctions[padded[0]] = function() {
                return zeroFill(
                    func.apply(this, arguments),
                    padded[1],
                    padded[2]
                );
            };
        }
        if (ordinal) {
            formatTokenFunctions[ordinal] = function() {
                return this.localeData().ordinal(
                    func.apply(this, arguments),
                    token
                );
            };
        }
    }

    function removeFormattingTokens(input) {
        if (input.match(/\[[\s\S]/)) {
            return input.replace(/^\[|\]$/g, "");
        }
        return input.replace(/\\/g, "");
    }

    function makeFormatFunction(format) {
        var array = format.match(formattingTokens),
            i,
            length;
        for (i = 0, length = array.length; i < length; i++) {
            if (formatTokenFunctions[array[i]]) {
                array[i] = formatTokenFunctions[array[i]];
            } else {
                array[i] = removeFormattingTokens(array[i]);
            }
        }
        return function(mom) {
            var output = "",
                i;
            for (i = 0; i < length; i++) {
                output += isFunction(array[i])
                    ? array[i].call(mom, format)
                    : array[i];
            }
            return output;
        };
    }

    function formatMoment(m, format) {
        if (!m.isValid()) {
            return m.localeData().invalidDate();
        }
        format = expandFormat(format, m.localeData());
        formatFunctions[format] =
            formatFunctions[format] || makeFormatFunction(format);
        return formatFunctions[format](m);
    }

    function expandFormat(format, locale) {
        var i = 5;

        function replaceLongDateFormatTokens(input) {
            return locale.longDateFormat(input) || input;
        }
        localFormattingTokens.lastIndex = 0;
        while (i >= 0 && localFormattingTokens.test(format)) {
            format = format.replace(
                localFormattingTokens,
                replaceLongDateFormatTokens
            );
            localFormattingTokens.lastIndex = 0;
            i -= 1;
        }
        return format;
    }
    var match1 = /\d/;
    var match2 = /\d\d/;
    var match3 = /\d{3}/;
    var match4 = /\d{4}/;
    var match6 = /[+-]?\d{6}/;
    var match1to2 = /\d\d?/;
    var match3to4 = /\d\d\d\d?/;
    var match5to6 = /\d\d\d\d\d\d?/;
    var match1to3 = /\d{1,3}/;
    var match1to4 = /\d{1,4}/;
    var match1to6 = /[+-]?\d{1,6}/;
    var matchUnsigned = /\d+/;
    var matchSigned = /[+-]?\d+/;
    var matchOffset = /Z|[+-]\d\d:?\d\d/gi;
    var matchShortOffset = /Z|[+-]\d\d(?::?\d\d)?/gi;
    var matchTimestamp = /[+-]?\d+(\.\d{1,3})?/;
    var matchWord = /[0-9]{0,256}['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]{1,256}|[\u0600-\u06FF\/]{1,256}(\s*?[\u0600-\u06FF]{1,256}){1,2}/i;
    var regexes = {};

    function addRegexToken(token, regex, strictRegex) {
        regexes[token] = isFunction(regex)
            ? regex
            : function(isStrict, localeData) {
                  return isStrict && strictRegex ? strictRegex : regex;
              };
    }

    function getParseRegexForToken(token, config) {
        if (!hasOwnProp(regexes, token)) {
            return new RegExp(unescapeFormat(token));
        }
        return regexes[token](config._strict, config._locale);
    }

    function unescapeFormat(s) {
        return regexEscape(
            s
                .replace("\\", "")
                .replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function(
                    matched,
                    p1,
                    p2,
                    p3,
                    p4
                ) {
                    return p1 || p2 || p3 || p4;
                })
        );
    }

    function regexEscape(s) {
        return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&");
    }
    var tokens = {};

    function addParseToken(token, callback) {
        var i,
            func = callback;
        if (typeof token === "string") {
            token = [token];
        }
        if (isNumber(callback)) {
            func = function(input, array) {
                array[callback] = toInt(input);
            };
        }
        for (i = 0; i < token.length; i++) {
            tokens[token[i]] = func;
        }
    }

    function addWeekParseToken(token, callback) {
        addParseToken(token, function(input, array, config, token) {
            config._w = config._w || {};
            callback(input, config._w, config, token);
        });
    }

    function addTimeToArrayFromToken(token, input, config) {
        if (input != null && hasOwnProp(tokens, token)) {
            tokens[token](input, config._a, config, token);
        }
    }
    var YEAR = 0;
    var MONTH = 1;
    var DATE = 2;
    var HOUR = 3;
    var MINUTE = 4;
    var SECOND = 5;
    var MILLISECOND = 6;
    var WEEK = 7;
    var WEEKDAY = 8;
    addFormatToken("Y", 0, 0, function() {
        var y = this.year();
        return y <= 9999 ? "" + y : "+" + y;
    });
    addFormatToken(0, ["YY", 2], 0, function() {
        return this.year() % 100;
    });
    addFormatToken(0, ["YYYY", 4], 0, "year");
    addFormatToken(0, ["YYYYY", 5], 0, "year");
    addFormatToken(0, ["YYYYYY", 6, !0], 0, "year");
    addUnitAlias("year", "y");
    addUnitPriority("year", 1);
    addRegexToken("Y", matchSigned);
    addRegexToken("YY", match1to2, match2);
    addRegexToken("YYYY", match1to4, match4);
    addRegexToken("YYYYY", match1to6, match6);
    addRegexToken("YYYYYY", match1to6, match6);
    addParseToken(["YYYYY", "YYYYYY"], YEAR);
    addParseToken("YYYY", function(input, array) {
        array[YEAR] =
            input.length === 2 ? hooks.parseTwoDigitYear(input) : toInt(input);
    });
    addParseToken("YY", function(input, array) {
        array[YEAR] = hooks.parseTwoDigitYear(input);
    });
    addParseToken("Y", function(input, array) {
        array[YEAR] = parseInt(input, 10);
    });

    function daysInYear(year) {
        return isLeapYear(year) ? 366 : 365;
    }

    function isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
    }
    hooks.parseTwoDigitYear = function(input) {
        return toInt(input) + (toInt(input) > 68 ? 1900 : 2000);
    };
    var getSetYear = makeGetSet("FullYear", !0);

    function getIsLeapYear() {
        return isLeapYear(this.year());
    }

    function makeGetSet(unit, keepTime) {
        return function(value) {
            if (value != null) {
                set$1(this, unit, value);
                hooks.updateOffset(this, keepTime);
                return this;
            } else {
                return get(this, unit);
            }
        };
    }

    function get(mom, unit) {
        return mom.isValid()
            ? mom._d["get" + (mom._isUTC ? "UTC" : "") + unit]()
            : NaN;
    }

    function set$1(mom, unit, value) {
        if (mom.isValid() && !isNaN(value)) {
            if (
                unit === "FullYear" &&
                isLeapYear(mom.year()) &&
                mom.month() === 1 &&
                mom.date() === 29
            ) {
                mom._d["set" + (mom._isUTC ? "UTC" : "") + unit](
                    value,
                    mom.month(),
                    daysInMonth(value, mom.month())
                );
            } else {
                mom._d["set" + (mom._isUTC ? "UTC" : "") + unit](value);
            }
        }
    }

    function stringGet(units) {
        units = normalizeUnits(units);
        if (isFunction(this[units])) {
            return this[units]();
        }
        return this;
    }

    function stringSet(units, value) {
        if (typeof units === "object") {
            units = normalizeObjectUnits(units);
            var prioritized = getPrioritizedUnits(units);
            for (var i = 0; i < prioritized.length; i++) {
                this[prioritized[i].unit](units[prioritized[i].unit]);
            }
        } else {
            units = normalizeUnits(units);
            if (isFunction(this[units])) {
                return this[units](value);
            }
        }
        return this;
    }

    function mod(n, x) {
        return ((n % x) + x) % x;
    }
    var indexOf;
    if (Array.prototype.indexOf) {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(o) {
            var i;
            for (i = 0; i < this.length; ++i) {
                if (this[i] === o) {
                    return i;
                }
            }
            return -1;
        };
    }

    function daysInMonth(year, month) {
        if (isNaN(year) || isNaN(month)) {
            return NaN;
        }
        var modMonth = mod(month, 12);
        year += (month - modMonth) / 12;
        return modMonth === 1
            ? isLeapYear(year)
                ? 29
                : 28
            : 31 - ((modMonth % 7) % 2);
    }
    addFormatToken("M", ["MM", 2], "Mo", function() {
        return this.month() + 1;
    });
    addFormatToken("MMM", 0, 0, function(format) {
        return this.localeData().monthsShort(this, format);
    });
    addFormatToken("MMMM", 0, 0, function(format) {
        return this.localeData().months(this, format);
    });
    addUnitAlias("month", "M");
    addUnitPriority("month", 8);
    addRegexToken("M", match1to2);
    addRegexToken("MM", match1to2, match2);
    addRegexToken("MMM", function(isStrict, locale) {
        return locale.monthsShortRegex(isStrict);
    });
    addRegexToken("MMMM", function(isStrict, locale) {
        return locale.monthsRegex(isStrict);
    });
    addParseToken(["M", "MM"], function(input, array) {
        array[MONTH] = toInt(input) - 1;
    });
    addParseToken(["MMM", "MMMM"], function(input, array, config, token) {
        var month = config._locale.monthsParse(input, token, config._strict);
        if (month != null) {
            array[MONTH] = month;
        } else {
            getParsingFlags(config).invalidMonth = input;
        }
    });
    var MONTHS_IN_FORMAT = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/;
    var defaultLocaleMonths = "January_February_March_April_May_June_July_August_September_October_November_December".split(
        "_"
    );

    function localeMonths(m, format) {
        if (!m) {
            return isArray(this._months)
                ? this._months
                : this._months.standalone;
        }
        return isArray(this._months)
            ? this._months[m.month()]
            : this._months[
                  (this._months.isFormat || MONTHS_IN_FORMAT).test(format)
                      ? "format"
                      : "standalone"
              ][m.month()];
    }
    var defaultLocaleMonthsShort = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split(
        "_"
    );

    function localeMonthsShort(m, format) {
        if (!m) {
            return isArray(this._monthsShort)
                ? this._monthsShort
                : this._monthsShort.standalone;
        }
        return isArray(this._monthsShort)
            ? this._monthsShort[m.month()]
            : this._monthsShort[
                  MONTHS_IN_FORMAT.test(format) ? "format" : "standalone"
              ][m.month()];
    }

    function handleStrictParse(monthName, format, strict) {
        var i,
            ii,
            mom,
            llc = monthName.toLocaleLowerCase();
        if (!this._monthsParse) {
            this._monthsParse = [];
            this._longMonthsParse = [];
            this._shortMonthsParse = [];
            for (i = 0; i < 12; ++i) {
                mom = createUTC([2000, i]);
                this._shortMonthsParse[i] = this.monthsShort(
                    mom,
                    ""
                ).toLocaleLowerCase();
                this._longMonthsParse[i] = this.months(
                    mom,
                    ""
                ).toLocaleLowerCase();
            }
        }
        if (strict) {
            if (format === "MMM") {
                ii = indexOf.call(this._shortMonthsParse, llc);
                return ii !== -1 ? ii : null;
            } else {
                ii = indexOf.call(this._longMonthsParse, llc);
                return ii !== -1 ? ii : null;
            }
        } else {
            if (format === "MMM") {
                ii = indexOf.call(this._shortMonthsParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._longMonthsParse, llc);
                return ii !== -1 ? ii : null;
            } else {
                ii = indexOf.call(this._longMonthsParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._shortMonthsParse, llc);
                return ii !== -1 ? ii : null;
            }
        }
    }

    function localeMonthsParse(monthName, format, strict) {
        var i, mom, regex;
        if (this._monthsParseExact) {
            return handleStrictParse.call(this, monthName, format, strict);
        }
        if (!this._monthsParse) {
            this._monthsParse = [];
            this._longMonthsParse = [];
            this._shortMonthsParse = [];
        }
        for (i = 0; i < 12; i++) {
            mom = createUTC([2000, i]);
            if (strict && !this._longMonthsParse[i]) {
                this._longMonthsParse[i] = new RegExp(
                    "^" + this.months(mom, "").replace(".", "") + "$",
                    "i"
                );
                this._shortMonthsParse[i] = new RegExp(
                    "^" + this.monthsShort(mom, "").replace(".", "") + "$",
                    "i"
                );
            }
            if (!strict && !this._monthsParse[i]) {
                regex =
                    "^" +
                    this.months(mom, "") +
                    "|^" +
                    this.monthsShort(mom, "");
                this._monthsParse[i] = new RegExp(regex.replace(".", ""), "i");
            }
            if (
                strict &&
                format === "MMMM" &&
                this._longMonthsParse[i].test(monthName)
            ) {
                return i;
            } else if (
                strict &&
                format === "MMM" &&
                this._shortMonthsParse[i].test(monthName)
            ) {
                return i;
            } else if (!strict && this._monthsParse[i].test(monthName)) {
                return i;
            }
        }
    }

    function setMonth(mom, value) {
        var dayOfMonth;
        if (!mom.isValid()) {
            return mom;
        }
        if (typeof value === "string") {
            if (/^\d+$/.test(value)) {
                value = toInt(value);
            } else {
                value = mom.localeData().monthsParse(value);
                if (!isNumber(value)) {
                    return mom;
                }
            }
        }
        dayOfMonth = Math.min(mom.date(), daysInMonth(mom.year(), value));
        mom._d["set" + (mom._isUTC ? "UTC" : "") + "Month"](value, dayOfMonth);
        return mom;
    }

    function getSetMonth(value) {
        if (value != null) {
            setMonth(this, value);
            hooks.updateOffset(this, !0);
            return this;
        } else {
            return get(this, "Month");
        }
    }

    function getDaysInMonth() {
        return daysInMonth(this.year(), this.month());
    }
    var defaultMonthsShortRegex = matchWord;

    function monthsShortRegex(isStrict) {
        if (this._monthsParseExact) {
            if (!hasOwnProp(this, "_monthsRegex")) {
                computeMonthsParse.call(this);
            }
            if (isStrict) {
                return this._monthsShortStrictRegex;
            } else {
                return this._monthsShortRegex;
            }
        } else {
            if (!hasOwnProp(this, "_monthsShortRegex")) {
                this._monthsShortRegex = defaultMonthsShortRegex;
            }
            return this._monthsShortStrictRegex && isStrict
                ? this._monthsShortStrictRegex
                : this._monthsShortRegex;
        }
    }
    var defaultMonthsRegex = matchWord;

    function monthsRegex(isStrict) {
        if (this._monthsParseExact) {
            if (!hasOwnProp(this, "_monthsRegex")) {
                computeMonthsParse.call(this);
            }
            if (isStrict) {
                return this._monthsStrictRegex;
            } else {
                return this._monthsRegex;
            }
        } else {
            if (!hasOwnProp(this, "_monthsRegex")) {
                this._monthsRegex = defaultMonthsRegex;
            }
            return this._monthsStrictRegex && isStrict
                ? this._monthsStrictRegex
                : this._monthsRegex;
        }
    }

    function computeMonthsParse() {
        function cmpLenRev(a, b) {
            return b.length - a.length;
        }
        var shortPieces = [],
            longPieces = [],
            mixedPieces = [],
            i,
            mom;
        for (i = 0; i < 12; i++) {
            mom = createUTC([2000, i]);
            shortPieces.push(this.monthsShort(mom, ""));
            longPieces.push(this.months(mom, ""));
            mixedPieces.push(this.months(mom, ""));
            mixedPieces.push(this.monthsShort(mom, ""));
        }
        shortPieces.sort(cmpLenRev);
        longPieces.sort(cmpLenRev);
        mixedPieces.sort(cmpLenRev);
        for (i = 0; i < 12; i++) {
            shortPieces[i] = regexEscape(shortPieces[i]);
            longPieces[i] = regexEscape(longPieces[i]);
        }
        for (i = 0; i < 24; i++) {
            mixedPieces[i] = regexEscape(mixedPieces[i]);
        }
        this._monthsRegex = new RegExp("^(" + mixedPieces.join("|") + ")", "i");
        this._monthsShortRegex = this._monthsRegex;
        this._monthsStrictRegex = new RegExp(
            "^(" + longPieces.join("|") + ")",
            "i"
        );
        this._monthsShortStrictRegex = new RegExp(
            "^(" + shortPieces.join("|") + ")",
            "i"
        );
    }

    function createDate(y, m, d, h, M, s, ms) {
        var date = new Date(y, m, d, h, M, s, ms);
        if (y < 100 && y >= 0 && isFinite(date.getFullYear())) {
            date.setFullYear(y);
        }
        return date;
    }

    function createUTCDate(y) {
        var date = new Date(Date.UTC.apply(null, arguments));
        if (y < 100 && y >= 0 && isFinite(date.getUTCFullYear())) {
            date.setUTCFullYear(y);
        }
        return date;
    }

    function firstWeekOffset(year, dow, doy) {
        var fwd = 7 + dow - doy,
            fwdlw = (7 + createUTCDate(year, 0, fwd).getUTCDay() - dow) % 7;
        return -fwdlw + fwd - 1;
    }

    function dayOfYearFromWeeks(year, week, weekday, dow, doy) {
        var localWeekday = (7 + weekday - dow) % 7,
            weekOffset = firstWeekOffset(year, dow, doy),
            dayOfYear = 1 + 7 * (week - 1) + localWeekday + weekOffset,
            resYear,
            resDayOfYear;
        if (dayOfYear <= 0) {
            resYear = year - 1;
            resDayOfYear = daysInYear(resYear) + dayOfYear;
        } else if (dayOfYear > daysInYear(year)) {
            resYear = year + 1;
            resDayOfYear = dayOfYear - daysInYear(year);
        } else {
            resYear = year;
            resDayOfYear = dayOfYear;
        }
        return {
            year: resYear,
            dayOfYear: resDayOfYear
        };
    }

    function weekOfYear(mom, dow, doy) {
        var weekOffset = firstWeekOffset(mom.year(), dow, doy),
            week = Math.floor((mom.dayOfYear() - weekOffset - 1) / 7) + 1,
            resWeek,
            resYear;
        if (week < 1) {
            resYear = mom.year() - 1;
            resWeek = week + weeksInYear(resYear, dow, doy);
        } else if (week > weeksInYear(mom.year(), dow, doy)) {
            resWeek = week - weeksInYear(mom.year(), dow, doy);
            resYear = mom.year() + 1;
        } else {
            resYear = mom.year();
            resWeek = week;
        }
        return {
            week: resWeek,
            year: resYear
        };
    }

    function weeksInYear(year, dow, doy) {
        var weekOffset = firstWeekOffset(year, dow, doy),
            weekOffsetNext = firstWeekOffset(year + 1, dow, doy);
        return (daysInYear(year) - weekOffset + weekOffsetNext) / 7;
    }
    addFormatToken("w", ["ww", 2], "wo", "week");
    addFormatToken("W", ["WW", 2], "Wo", "isoWeek");
    addUnitAlias("week", "w");
    addUnitAlias("isoWeek", "W");
    addUnitPriority("week", 5);
    addUnitPriority("isoWeek", 5);
    addRegexToken("w", match1to2);
    addRegexToken("ww", match1to2, match2);
    addRegexToken("W", match1to2);
    addRegexToken("WW", match1to2, match2);
    addWeekParseToken(["w", "ww", "W", "WW"], function(
        input,
        week,
        config,
        token
    ) {
        week[token.substr(0, 1)] = toInt(input);
    });

    function localeWeek(mom) {
        return weekOfYear(mom, this._week.dow, this._week.doy).week;
    }
    var defaultLocaleWeek = {
        dow: 0,
        doy: 6
    };

    function localeFirstDayOfWeek() {
        return this._week.dow;
    }

    function localeFirstDayOfYear() {
        return this._week.doy;
    }

    function getSetWeek(input) {
        var week = this.localeData().week(this);
        return input == null ? week : this.add((input - week) * 7, "d");
    }

    function getSetISOWeek(input) {
        var week = weekOfYear(this, 1, 4).week;
        return input == null ? week : this.add((input - week) * 7, "d");
    }
    addFormatToken("d", 0, "do", "day");
    addFormatToken("dd", 0, 0, function(format) {
        return this.localeData().weekdaysMin(this, format);
    });
    addFormatToken("ddd", 0, 0, function(format) {
        return this.localeData().weekdaysShort(this, format);
    });
    addFormatToken("dddd", 0, 0, function(format) {
        return this.localeData().weekdays(this, format);
    });
    addFormatToken("e", 0, 0, "weekday");
    addFormatToken("E", 0, 0, "isoWeekday");
    addUnitAlias("day", "d");
    addUnitAlias("weekday", "e");
    addUnitAlias("isoWeekday", "E");
    addUnitPriority("day", 11);
    addUnitPriority("weekday", 11);
    addUnitPriority("isoWeekday", 11);
    addRegexToken("d", match1to2);
    addRegexToken("e", match1to2);
    addRegexToken("E", match1to2);
    addRegexToken("dd", function(isStrict, locale) {
        return locale.weekdaysMinRegex(isStrict);
    });
    addRegexToken("ddd", function(isStrict, locale) {
        return locale.weekdaysShortRegex(isStrict);
    });
    addRegexToken("dddd", function(isStrict, locale) {
        return locale.weekdaysRegex(isStrict);
    });
    addWeekParseToken(["dd", "ddd", "dddd"], function(
        input,
        week,
        config,
        token
    ) {
        var weekday = config._locale.weekdaysParse(
            input,
            token,
            config._strict
        );
        if (weekday != null) {
            week.d = weekday;
        } else {
            getParsingFlags(config).invalidWeekday = input;
        }
    });
    addWeekParseToken(["d", "e", "E"], function(input, week, config, token) {
        week[token] = toInt(input);
    });

    function parseWeekday(input, locale) {
        if (typeof input !== "string") {
            return input;
        }
        if (!isNaN(input)) {
            return parseInt(input, 10);
        }
        input = locale.weekdaysParse(input);
        if (typeof input === "number") {
            return input;
        }
        return null;
    }

    function parseIsoWeekday(input, locale) {
        if (typeof input === "string") {
            return locale.weekdaysParse(input) % 7 || 7;
        }
        return isNaN(input) ? null : input;
    }
    var defaultLocaleWeekdays = "Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split(
        "_"
    );

    function localeWeekdays(m, format) {
        if (!m) {
            return isArray(this._weekdays)
                ? this._weekdays
                : this._weekdays.standalone;
        }
        return isArray(this._weekdays)
            ? this._weekdays[m.day()]
            : this._weekdays[
                  this._weekdays.isFormat.test(format) ? "format" : "standalone"
              ][m.day()];
    }
    var defaultLocaleWeekdaysShort = "Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_");

    function localeWeekdaysShort(m) {
        return m ? this._weekdaysShort[m.day()] : this._weekdaysShort;
    }
    var defaultLocaleWeekdaysMin = "Su_Mo_Tu_We_Th_Fr_Sa".split("_");

    function localeWeekdaysMin(m) {
        return m ? this._weekdaysMin[m.day()] : this._weekdaysMin;
    }

    function handleStrictParse$1(weekdayName, format, strict) {
        var i,
            ii,
            mom,
            llc = weekdayName.toLocaleLowerCase();
        if (!this._weekdaysParse) {
            this._weekdaysParse = [];
            this._shortWeekdaysParse = [];
            this._minWeekdaysParse = [];
            for (i = 0; i < 7; ++i) {
                mom = createUTC([2000, 1]).day(i);
                this._minWeekdaysParse[i] = this.weekdaysMin(
                    mom,
                    ""
                ).toLocaleLowerCase();
                this._shortWeekdaysParse[i] = this.weekdaysShort(
                    mom,
                    ""
                ).toLocaleLowerCase();
                this._weekdaysParse[i] = this.weekdays(
                    mom,
                    ""
                ).toLocaleLowerCase();
            }
        }
        if (strict) {
            if (format === "dddd") {
                ii = indexOf.call(this._weekdaysParse, llc);
                return ii !== -1 ? ii : null;
            } else if (format === "ddd") {
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                return ii !== -1 ? ii : null;
            } else {
                ii = indexOf.call(this._minWeekdaysParse, llc);
                return ii !== -1 ? ii : null;
            }
        } else {
            if (format === "dddd") {
                ii = indexOf.call(this._weekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._minWeekdaysParse, llc);
                return ii !== -1 ? ii : null;
            } else if (format === "ddd") {
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._weekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._minWeekdaysParse, llc);
                return ii !== -1 ? ii : null;
            } else {
                ii = indexOf.call(this._minWeekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._weekdaysParse, llc);
                if (ii !== -1) {
                    return ii;
                }
                ii = indexOf.call(this._shortWeekdaysParse, llc);
                return ii !== -1 ? ii : null;
            }
        }
    }

    function localeWeekdaysParse(weekdayName, format, strict) {
        var i, mom, regex;
        if (this._weekdaysParseExact) {
            return handleStrictParse$1.call(this, weekdayName, format, strict);
        }
        if (!this._weekdaysParse) {
            this._weekdaysParse = [];
            this._minWeekdaysParse = [];
            this._shortWeekdaysParse = [];
            this._fullWeekdaysParse = [];
        }
        for (i = 0; i < 7; i++) {
            mom = createUTC([2000, 1]).day(i);
            if (strict && !this._fullWeekdaysParse[i]) {
                this._fullWeekdaysParse[i] = new RegExp(
                    "^" + this.weekdays(mom, "").replace(".", ".?") + "$",
                    "i"
                );
                this._shortWeekdaysParse[i] = new RegExp(
                    "^" + this.weekdaysShort(mom, "").replace(".", ".?") + "$",
                    "i"
                );
                this._minWeekdaysParse[i] = new RegExp(
                    "^" + this.weekdaysMin(mom, "").replace(".", ".?") + "$",
                    "i"
                );
            }
            if (!this._weekdaysParse[i]) {
                regex =
                    "^" +
                    this.weekdays(mom, "") +
                    "|^" +
                    this.weekdaysShort(mom, "") +
                    "|^" +
                    this.weekdaysMin(mom, "");
                this._weekdaysParse[i] = new RegExp(
                    regex.replace(".", ""),
                    "i"
                );
            }
            if (
                strict &&
                format === "dddd" &&
                this._fullWeekdaysParse[i].test(weekdayName)
            ) {
                return i;
            } else if (
                strict &&
                format === "ddd" &&
                this._shortWeekdaysParse[i].test(weekdayName)
            ) {
                return i;
            } else if (
                strict &&
                format === "dd" &&
                this._minWeekdaysParse[i].test(weekdayName)
            ) {
                return i;
            } else if (!strict && this._weekdaysParse[i].test(weekdayName)) {
                return i;
            }
        }
    }

    function getSetDayOfWeek(input) {
        if (!this.isValid()) {
            return input != null ? this : NaN;
        }
        var day = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
        if (input != null) {
            input = parseWeekday(input, this.localeData());
            return this.add(input - day, "d");
        } else {
            return day;
        }
    }

    function getSetLocaleDayOfWeek(input) {
        if (!this.isValid()) {
            return input != null ? this : NaN;
        }
        var weekday = (this.day() + 7 - this.localeData()._week.dow) % 7;
        return input == null ? weekday : this.add(input - weekday, "d");
    }

    function getSetISODayOfWeek(input) {
        if (!this.isValid()) {
            return input != null ? this : NaN;
        }
        if (input != null) {
            var weekday = parseIsoWeekday(input, this.localeData());
            return this.day(this.day() % 7 ? weekday : weekday - 7);
        } else {
            return this.day() || 7;
        }
    }
    var defaultWeekdaysRegex = matchWord;

    function weekdaysRegex(isStrict) {
        if (this._weekdaysParseExact) {
            if (!hasOwnProp(this, "_weekdaysRegex")) {
                computeWeekdaysParse.call(this);
            }
            if (isStrict) {
                return this._weekdaysStrictRegex;
            } else {
                return this._weekdaysRegex;
            }
        } else {
            if (!hasOwnProp(this, "_weekdaysRegex")) {
                this._weekdaysRegex = defaultWeekdaysRegex;
            }
            return this._weekdaysStrictRegex && isStrict
                ? this._weekdaysStrictRegex
                : this._weekdaysRegex;
        }
    }
    var defaultWeekdaysShortRegex = matchWord;

    function weekdaysShortRegex(isStrict) {
        if (this._weekdaysParseExact) {
            if (!hasOwnProp(this, "_weekdaysRegex")) {
                computeWeekdaysParse.call(this);
            }
            if (isStrict) {
                return this._weekdaysShortStrictRegex;
            } else {
                return this._weekdaysShortRegex;
            }
        } else {
            if (!hasOwnProp(this, "_weekdaysShortRegex")) {
                this._weekdaysShortRegex = defaultWeekdaysShortRegex;
            }
            return this._weekdaysShortStrictRegex && isStrict
                ? this._weekdaysShortStrictRegex
                : this._weekdaysShortRegex;
        }
    }
    var defaultWeekdaysMinRegex = matchWord;

    function weekdaysMinRegex(isStrict) {
        if (this._weekdaysParseExact) {
            if (!hasOwnProp(this, "_weekdaysRegex")) {
                computeWeekdaysParse.call(this);
            }
            if (isStrict) {
                return this._weekdaysMinStrictRegex;
            } else {
                return this._weekdaysMinRegex;
            }
        } else {
            if (!hasOwnProp(this, "_weekdaysMinRegex")) {
                this._weekdaysMinRegex = defaultWeekdaysMinRegex;
            }
            return this._weekdaysMinStrictRegex && isStrict
                ? this._weekdaysMinStrictRegex
                : this._weekdaysMinRegex;
        }
    }

    function computeWeekdaysParse() {
        function cmpLenRev(a, b) {
            return b.length - a.length;
        }
        var minPieces = [],
            shortPieces = [],
            longPieces = [],
            mixedPieces = [],
            i,
            mom,
            minp,
            shortp,
            longp;
        for (i = 0; i < 7; i++) {
            mom = createUTC([2000, 1]).day(i);
            minp = this.weekdaysMin(mom, "");
            shortp = this.weekdaysShort(mom, "");
            longp = this.weekdays(mom, "");
            minPieces.push(minp);
            shortPieces.push(shortp);
            longPieces.push(longp);
            mixedPieces.push(minp);
            mixedPieces.push(shortp);
            mixedPieces.push(longp);
        }
        minPieces.sort(cmpLenRev);
        shortPieces.sort(cmpLenRev);
        longPieces.sort(cmpLenRev);
        mixedPieces.sort(cmpLenRev);
        for (i = 0; i < 7; i++) {
            shortPieces[i] = regexEscape(shortPieces[i]);
            longPieces[i] = regexEscape(longPieces[i]);
            mixedPieces[i] = regexEscape(mixedPieces[i]);
        }
        this._weekdaysRegex = new RegExp(
            "^(" + mixedPieces.join("|") + ")",
            "i"
        );
        this._weekdaysShortRegex = this._weekdaysRegex;
        this._weekdaysMinRegex = this._weekdaysRegex;
        this._weekdaysStrictRegex = new RegExp(
            "^(" + longPieces.join("|") + ")",
            "i"
        );
        this._weekdaysShortStrictRegex = new RegExp(
            "^(" + shortPieces.join("|") + ")",
            "i"
        );
        this._weekdaysMinStrictRegex = new RegExp(
            "^(" + minPieces.join("|") + ")",
            "i"
        );
    }

    function hFormat() {
        return this.hours() % 12 || 12;
    }

    function kFormat() {
        return this.hours() || 24;
    }
    addFormatToken("H", ["HH", 2], 0, "hour");
    addFormatToken("h", ["hh", 2], 0, hFormat);
    addFormatToken("k", ["kk", 2], 0, kFormat);
    addFormatToken("hmm", 0, 0, function() {
        return "" + hFormat.apply(this) + zeroFill(this.minutes(), 2);
    });
    addFormatToken("hmmss", 0, 0, function() {
        return (
            "" +
            hFormat.apply(this) +
            zeroFill(this.minutes(), 2) +
            zeroFill(this.seconds(), 2)
        );
    });
    addFormatToken("Hmm", 0, 0, function() {
        return "" + this.hours() + zeroFill(this.minutes(), 2);
    });
    addFormatToken("Hmmss", 0, 0, function() {
        return (
            "" +
            this.hours() +
            zeroFill(this.minutes(), 2) +
            zeroFill(this.seconds(), 2)
        );
    });

    function meridiem(token, lowercase) {
        addFormatToken(token, 0, 0, function() {
            return this.localeData().meridiem(
                this.hours(),
                this.minutes(),
                lowercase
            );
        });
    }
    meridiem("a", !0);
    meridiem("A", !1);
    addUnitAlias("hour", "h");
    addUnitPriority("hour", 13);

    function matchMeridiem(isStrict, locale) {
        return locale._meridiemParse;
    }
    addRegexToken("a", matchMeridiem);
    addRegexToken("A", matchMeridiem);
    addRegexToken("H", match1to2);
    addRegexToken("h", match1to2);
    addRegexToken("k", match1to2);
    addRegexToken("HH", match1to2, match2);
    addRegexToken("hh", match1to2, match2);
    addRegexToken("kk", match1to2, match2);
    addRegexToken("hmm", match3to4);
    addRegexToken("hmmss", match5to6);
    addRegexToken("Hmm", match3to4);
    addRegexToken("Hmmss", match5to6);
    addParseToken(["H", "HH"], HOUR);
    addParseToken(["k", "kk"], function(input, array, config) {
        var kInput = toInt(input);
        array[HOUR] = kInput === 24 ? 0 : kInput;
    });
    addParseToken(["a", "A"], function(input, array, config) {
        config._isPm = config._locale.isPM(input);
        config._meridiem = input;
    });
    addParseToken(["h", "hh"], function(input, array, config) {
        array[HOUR] = toInt(input);
        getParsingFlags(config).bigHour = !0;
    });
    addParseToken("hmm", function(input, array, config) {
        var pos = input.length - 2;
        array[HOUR] = toInt(input.substr(0, pos));
        array[MINUTE] = toInt(input.substr(pos));
        getParsingFlags(config).bigHour = !0;
    });
    addParseToken("hmmss", function(input, array, config) {
        var pos1 = input.length - 4;
        var pos2 = input.length - 2;
        array[HOUR] = toInt(input.substr(0, pos1));
        array[MINUTE] = toInt(input.substr(pos1, 2));
        array[SECOND] = toInt(input.substr(pos2));
        getParsingFlags(config).bigHour = !0;
    });
    addParseToken("Hmm", function(input, array, config) {
        var pos = input.length - 2;
        array[HOUR] = toInt(input.substr(0, pos));
        array[MINUTE] = toInt(input.substr(pos));
    });
    addParseToken("Hmmss", function(input, array, config) {
        var pos1 = input.length - 4;
        var pos2 = input.length - 2;
        array[HOUR] = toInt(input.substr(0, pos1));
        array[MINUTE] = toInt(input.substr(pos1, 2));
        array[SECOND] = toInt(input.substr(pos2));
    });

    function localeIsPM(input) {
        return (input + "").toLowerCase().charAt(0) === "p";
    }
    var defaultLocaleMeridiemParse = /[ap]\.?m?\.?/i;

    function localeMeridiem(hours, minutes, isLower) {
        if (hours > 11) {
            return isLower ? "pm" : "PM";
        } else {
            return isLower ? "am" : "AM";
        }
    }
    var getSetHour = makeGetSet("Hours", !0);
    var baseConfig = {
        calendar: defaultCalendar,
        longDateFormat: defaultLongDateFormat,
        invalidDate: defaultInvalidDate,
        ordinal: defaultOrdinal,
        dayOfMonthOrdinalParse: defaultDayOfMonthOrdinalParse,
        relativeTime: defaultRelativeTime,
        months: defaultLocaleMonths,
        monthsShort: defaultLocaleMonthsShort,
        week: defaultLocaleWeek,
        weekdays: defaultLocaleWeekdays,
        weekdaysMin: defaultLocaleWeekdaysMin,
        weekdaysShort: defaultLocaleWeekdaysShort,
        meridiemParse: defaultLocaleMeridiemParse
    };
    var locales = {};
    var localeFamilies = {};
    var globalLocale;

    function normalizeLocale(key) {
        return key ? key.toLowerCase().replace("_", "-") : key;
    }

    function chooseLocale(names) {
        var i = 0,
            j,
            next,
            locale,
            split;
        while (i < names.length) {
            split = normalizeLocale(names[i]).split("-");
            j = split.length;
            next = normalizeLocale(names[i + 1]);
            next = next ? next.split("-") : null;
            while (j > 0) {
                locale = loadLocale(split.slice(0, j).join("-"));
                if (locale) {
                    return locale;
                }
                if (
                    next &&
                    next.length >= j &&
                    compareArrays(split, next, !0) >= j - 1
                ) {
                    break;
                }
                j--;
            }
            i++;
        }
        return null;
    }

    function loadLocale(name) {
        var oldLocale = null;
        if (
            !locales[name] &&
            typeof module !== "undefined" &&
            module &&
            module.exports
        ) {
            try {
                oldLocale = globalLocale._abbr;
                var aliasedRequire = require;
                aliasedRequire("./locale/" + name);
                getSetGlobalLocale(oldLocale);
            } catch (e) {}
        }
        return locales[name];
    }

    function getSetGlobalLocale(key, values) {
        var data;
        if (key) {
            if (isUndefined(values)) {
                data = getLocale(key);
            } else {
                data = defineLocale(key, values);
            }
            if (data) {
                globalLocale = data;
            }
        }
        return globalLocale._abbr;
    }

    function defineLocale(name, config) {
        if (config !== null) {
            var parentConfig = baseConfig;
            config.abbr = name;
            if (locales[name] != null) {
                deprecateSimple(
                    "defineLocaleOverride",
                    "use moment.updateLocale(localeName, config) to change " +
                        "an existing locale. moment.defineLocale(localeName, " +
                        "config) should only be used for creating a new locale " +
                        "See http://momentjs.com/guides/#/warnings/define-locale/ for more info."
                );
                parentConfig = locales[name]._config;
            } else if (config.parentLocale != null) {
                if (locales[config.parentLocale] != null) {
                    parentConfig = locales[config.parentLocale]._config;
                } else {
                    if (!localeFamilies[config.parentLocale]) {
                        localeFamilies[config.parentLocale] = [];
                    }
                    localeFamilies[config.parentLocale].push({
                        name: name,
                        config: config
                    });
                    return null;
                }
            }
            locales[name] = new Locale(mergeConfigs(parentConfig, config));
            if (localeFamilies[name]) {
                localeFamilies[name].forEach(function(x) {
                    defineLocale(x.name, x.config);
                });
            }
            getSetGlobalLocale(name);
            return locales[name];
        } else {
            delete locales[name];
            return null;
        }
    }

    function updateLocale(name, config) {
        if (config != null) {
            var locale,
                tmpLocale,
                parentConfig = baseConfig;
            tmpLocale = loadLocale(name);
            if (tmpLocale != null) {
                parentConfig = tmpLocale._config;
            }
            config = mergeConfigs(parentConfig, config);
            locale = new Locale(config);
            locale.parentLocale = locales[name];
            locales[name] = locale;
            getSetGlobalLocale(name);
        } else {
            if (locales[name] != null) {
                if (locales[name].parentLocale != null) {
                    locales[name] = locales[name].parentLocale;
                } else if (locales[name] != null) {
                    delete locales[name];
                }
            }
        }
        return locales[name];
    }

    function getLocale(key) {
        var locale;
        if (key && key._locale && key._locale._abbr) {
            key = key._locale._abbr;
        }
        if (!key) {
            return globalLocale;
        }
        if (!isArray(key)) {
            locale = loadLocale(key);
            if (locale) {
                return locale;
            }
            key = [key];
        }
        return chooseLocale(key);
    }

    function listLocales() {
        return keys(locales);
    }

    function checkOverflow(m) {
        var overflow;
        var a = m._a;
        if (a && getParsingFlags(m).overflow === -2) {
            overflow =
                a[MONTH] < 0 || a[MONTH] > 11
                    ? MONTH
                    : a[DATE] < 1 || a[DATE] > daysInMonth(a[YEAR], a[MONTH])
                    ? DATE
                    : a[HOUR] < 0 ||
                      a[HOUR] > 24 ||
                      (a[HOUR] === 24 &&
                          (a[MINUTE] !== 0 ||
                              a[SECOND] !== 0 ||
                              a[MILLISECOND] !== 0))
                    ? HOUR
                    : a[MINUTE] < 0 || a[MINUTE] > 59
                    ? MINUTE
                    : a[SECOND] < 0 || a[SECOND] > 59
                    ? SECOND
                    : a[MILLISECOND] < 0 || a[MILLISECOND] > 999
                    ? MILLISECOND
                    : -1;
            if (
                getParsingFlags(m)._overflowDayOfYear &&
                (overflow < YEAR || overflow > DATE)
            ) {
                overflow = DATE;
            }
            if (getParsingFlags(m)._overflowWeeks && overflow === -1) {
                overflow = WEEK;
            }
            if (getParsingFlags(m)._overflowWeekday && overflow === -1) {
                overflow = WEEKDAY;
            }
            getParsingFlags(m).overflow = overflow;
        }
        return m;
    }

    function defaults(a, b, c) {
        if (a != null) {
            return a;
        }
        if (b != null) {
            return b;
        }
        return c;
    }

    function currentDateArray(config) {
        var nowValue = new Date(hooks.now());
        if (config._useUTC) {
            return [
                nowValue.getUTCFullYear(),
                nowValue.getUTCMonth(),
                nowValue.getUTCDate()
            ];
        }
        return [
            nowValue.getFullYear(),
            nowValue.getMonth(),
            nowValue.getDate()
        ];
    }

    function configFromArray(config) {
        var i,
            date,
            input = [],
            currentDate,
            yearToUse;
        if (config._d) {
            return;
        }
        currentDate = currentDateArray(config);
        if (config._w && config._a[DATE] == null && config._a[MONTH] == null) {
            dayOfYearFromWeekInfo(config);
        }
        if (config._dayOfYear != null) {
            yearToUse = defaults(config._a[YEAR], currentDate[YEAR]);
            if (
                config._dayOfYear > daysInYear(yearToUse) ||
                config._dayOfYear === 0
            ) {
                getParsingFlags(config)._overflowDayOfYear = !0;
            }
            date = createUTCDate(yearToUse, 0, config._dayOfYear);
            config._a[MONTH] = date.getUTCMonth();
            config._a[DATE] = date.getUTCDate();
        }
        for (i = 0; i < 3 && config._a[i] == null; ++i) {
            config._a[i] = input[i] = currentDate[i];
        }
        for (; i < 7; i++) {
            config._a[i] = input[i] =
                config._a[i] == null ? (i === 2 ? 1 : 0) : config._a[i];
        }
        if (
            config._a[HOUR] === 24 &&
            config._a[MINUTE] === 0 &&
            config._a[SECOND] === 0 &&
            config._a[MILLISECOND] === 0
        ) {
            config._nextDay = !0;
            config._a[HOUR] = 0;
        }
        config._d = (config._useUTC ? createUTCDate : createDate).apply(
            null,
            input
        );
        if (config._tzm != null) {
            config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
        }
        if (config._nextDay) {
            config._a[HOUR] = 24;
        }
        if (
            config._w &&
            typeof config._w.d !== "undefined" &&
            config._w.d !== config._d.getDay()
        ) {
            getParsingFlags(config).weekdayMismatch = !0;
        }
    }

    function dayOfYearFromWeekInfo(config) {
        var w, weekYear, week, weekday, dow, doy, temp, weekdayOverflow;
        w = config._w;
        if (w.GG != null || w.W != null || w.E != null) {
            dow = 1;
            doy = 4;
            weekYear = defaults(
                w.GG,
                config._a[YEAR],
                weekOfYear(createLocal(), 1, 4).year
            );
            week = defaults(w.W, 1);
            weekday = defaults(w.E, 1);
            if (weekday < 1 || weekday > 7) {
                weekdayOverflow = !0;
            }
        } else {
            dow = config._locale._week.dow;
            doy = config._locale._week.doy;
            var curWeek = weekOfYear(createLocal(), dow, doy);
            weekYear = defaults(w.gg, config._a[YEAR], curWeek.year);
            week = defaults(w.w, curWeek.week);
            if (w.d != null) {
                weekday = w.d;
                if (weekday < 0 || weekday > 6) {
                    weekdayOverflow = !0;
                }
            } else if (w.e != null) {
                weekday = w.e + dow;
                if (w.e < 0 || w.e > 6) {
                    weekdayOverflow = !0;
                }
            } else {
                weekday = dow;
            }
        }
        if (week < 1 || week > weeksInYear(weekYear, dow, doy)) {
            getParsingFlags(config)._overflowWeeks = !0;
        } else if (weekdayOverflow != null) {
            getParsingFlags(config)._overflowWeekday = !0;
        } else {
            temp = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy);
            config._a[YEAR] = temp.year;
            config._dayOfYear = temp.dayOfYear;
        }
    }
    var extendedIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/;
    var basicIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/;
    var tzRegex = /Z|[+-]\d\d(?::?\d\d)?/;
    var isoDates = [
        ["YYYYYY-MM-DD", /[+-]\d{6}-\d\d-\d\d/],
        ["YYYY-MM-DD", /\d{4}-\d\d-\d\d/],
        ["GGGG-[W]WW-E", /\d{4}-W\d\d-\d/],
        ["GGGG-[W]WW", /\d{4}-W\d\d/, !1],
        ["YYYY-DDD", /\d{4}-\d{3}/],
        ["YYYY-MM", /\d{4}-\d\d/, !1],
        ["YYYYYYMMDD", /[+-]\d{10}/],
        ["YYYYMMDD", /\d{8}/],
        ["GGGG[W]WWE", /\d{4}W\d{3}/],
        ["GGGG[W]WW", /\d{4}W\d{2}/, !1],
        ["YYYYDDD", /\d{7}/]
    ];
    var isoTimes = [
        ["HH:mm:ss.SSSS", /\d\d:\d\d:\d\d\.\d+/],
        ["HH:mm:ss,SSSS", /\d\d:\d\d:\d\d,\d+/],
        ["HH:mm:ss", /\d\d:\d\d:\d\d/],
        ["HH:mm", /\d\d:\d\d/],
        ["HHmmss.SSSS", /\d\d\d\d\d\d\.\d+/],
        ["HHmmss,SSSS", /\d\d\d\d\d\d,\d+/],
        ["HHmmss", /\d\d\d\d\d\d/],
        ["HHmm", /\d\d\d\d/],
        ["HH", /\d\d/]
    ];
    var aspNetJsonRegex = /^\/?Date\((\-?\d+)/i;

    function configFromISO(config) {
        var i,
            l,
            string = config._i,
            match = extendedIsoRegex.exec(string) || basicIsoRegex.exec(string),
            allowTime,
            dateFormat,
            timeFormat,
            tzFormat;
        if (match) {
            getParsingFlags(config).iso = !0;
            for (i = 0, l = isoDates.length; i < l; i++) {
                if (isoDates[i][1].exec(match[1])) {
                    dateFormat = isoDates[i][0];
                    allowTime = isoDates[i][2] !== !1;
                    break;
                }
            }
            if (dateFormat == null) {
                config._isValid = !1;
                return;
            }
            if (match[3]) {
                for (i = 0, l = isoTimes.length; i < l; i++) {
                    if (isoTimes[i][1].exec(match[3])) {
                        timeFormat = (match[2] || " ") + isoTimes[i][0];
                        break;
                    }
                }
                if (timeFormat == null) {
                    config._isValid = !1;
                    return;
                }
            }
            if (!allowTime && timeFormat != null) {
                config._isValid = !1;
                return;
            }
            if (match[4]) {
                if (tzRegex.exec(match[4])) {
                    tzFormat = "Z";
                } else {
                    config._isValid = !1;
                    return;
                }
            }
            config._f = dateFormat + (timeFormat || "") + (tzFormat || "");
            configFromStringAndFormat(config);
        } else {
            config._isValid = !1;
        }
    }
    var rfc2822 = /^(?:(Mon|Tue|Wed|Thu|Fri|Sat|Sun),?\s)?(\d{1,2})\s(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s(\d{2,4})\s(\d\d):(\d\d)(?::(\d\d))?\s(?:(UT|GMT|[ECMP][SD]T)|([Zz])|([+-]\d{4}))$/;

    function extractFromRFC2822Strings(
        yearStr,
        monthStr,
        dayStr,
        hourStr,
        minuteStr,
        secondStr
    ) {
        var result = [
            untruncateYear(yearStr),
            defaultLocaleMonthsShort.indexOf(monthStr),
            parseInt(dayStr, 10),
            parseInt(hourStr, 10),
            parseInt(minuteStr, 10)
        ];
        if (secondStr) {
            result.push(parseInt(secondStr, 10));
        }
        return result;
    }

    function untruncateYear(yearStr) {
        var year = parseInt(yearStr, 10);
        if (year <= 49) {
            return 2000 + year;
        } else if (year <= 999) {
            return 1900 + year;
        }
        return year;
    }

    function preprocessRFC2822(s) {
        return s
            .replace(/\([^)]*\)|[\n\t]/g, " ")
            .replace(/(\s\s+)/g, " ")
            .trim();
    }

    function checkWeekday(weekdayStr, parsedInput, config) {
        if (weekdayStr) {
            var weekdayProvided = defaultLocaleWeekdaysShort.indexOf(
                    weekdayStr
                ),
                weekdayActual = new Date(
                    parsedInput[0],
                    parsedInput[1],
                    parsedInput[2]
                ).getDay();
            if (weekdayProvided !== weekdayActual) {
                getParsingFlags(config).weekdayMismatch = !0;
                config._isValid = !1;
                return !1;
            }
        }
        return !0;
    }
    var obsOffsets = {
        UT: 0,
        GMT: 0,
        EDT: -4 * 60,
        EST: -5 * 60,
        CDT: -5 * 60,
        CST: -6 * 60,
        MDT: -6 * 60,
        MST: -7 * 60,
        PDT: -7 * 60,
        PST: -8 * 60
    };

    function calculateOffset(obsOffset, militaryOffset, numOffset) {
        if (obsOffset) {
            return obsOffsets[obsOffset];
        } else if (militaryOffset) {
            return 0;
        } else {
            var hm = parseInt(numOffset, 10);
            var m = hm % 100,
                h = (hm - m) / 100;
            return h * 60 + m;
        }
    }

    function configFromRFC2822(config) {
        var match = rfc2822.exec(preprocessRFC2822(config._i));
        if (match) {
            var parsedArray = extractFromRFC2822Strings(
                match[4],
                match[3],
                match[2],
                match[5],
                match[6],
                match[7]
            );
            if (!checkWeekday(match[1], parsedArray, config)) {
                return;
            }
            config._a = parsedArray;
            config._tzm = calculateOffset(match[8], match[9], match[10]);
            config._d = createUTCDate.apply(null, config._a);
            config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
            getParsingFlags(config).rfc2822 = !0;
        } else {
            config._isValid = !1;
        }
    }

    function configFromString(config) {
        var matched = aspNetJsonRegex.exec(config._i);
        if (matched !== null) {
            config._d = new Date(+matched[1]);
            return;
        }
        configFromISO(config);
        if (config._isValid === !1) {
            delete config._isValid;
        } else {
            return;
        }
        configFromRFC2822(config);
        if (config._isValid === !1) {
            delete config._isValid;
        } else {
            return;
        }
        hooks.createFromInputFallback(config);
    }
    hooks.createFromInputFallback = deprecate(
        "value provided is not in a recognized RFC2822 or ISO format. moment construction falls back to js Date(), " +
            "which is not reliable across all browsers and versions. Non RFC2822/ISO date formats are " +
            "discouraged and will be removed in an upcoming major release. Please refer to " +
            "http://momentjs.com/guides/#/warnings/js-date/ for more info.",
        function(config) {
            config._d = new Date(config._i + (config._useUTC ? " UTC" : ""));
        }
    );
    hooks.ISO_8601 = function() {};
    hooks.RFC_2822 = function() {};

    function configFromStringAndFormat(config) {
        if (config._f === hooks.ISO_8601) {
            configFromISO(config);
            return;
        }
        if (config._f === hooks.RFC_2822) {
            configFromRFC2822(config);
            return;
        }
        config._a = [];
        getParsingFlags(config).empty = !0;
        var string = "" + config._i,
            i,
            parsedInput,
            tokens,
            token,
            skipped,
            stringLength = string.length,
            totalParsedInputLength = 0;
        tokens =
            expandFormat(config._f, config._locale).match(formattingTokens) ||
            [];
        for (i = 0; i < tokens.length; i++) {
            token = tokens[i];
            parsedInput = (string.match(getParseRegexForToken(token, config)) ||
                [])[0];
            if (parsedInput) {
                skipped = string.substr(0, string.indexOf(parsedInput));
                if (skipped.length > 0) {
                    getParsingFlags(config).unusedInput.push(skipped);
                }
                string = string.slice(
                    string.indexOf(parsedInput) + parsedInput.length
                );
                totalParsedInputLength += parsedInput.length;
            }
            if (formatTokenFunctions[token]) {
                if (parsedInput) {
                    getParsingFlags(config).empty = !1;
                } else {
                    getParsingFlags(config).unusedTokens.push(token);
                }
                addTimeToArrayFromToken(token, parsedInput, config);
            } else if (config._strict && !parsedInput) {
                getParsingFlags(config).unusedTokens.push(token);
            }
        }
        getParsingFlags(config).charsLeftOver =
            stringLength - totalParsedInputLength;
        if (string.length > 0) {
            getParsingFlags(config).unusedInput.push(string);
        }
        if (
            config._a[HOUR] <= 12 &&
            getParsingFlags(config).bigHour === !0 &&
            config._a[HOUR] > 0
        ) {
            getParsingFlags(config).bigHour = undefined;
        }
        getParsingFlags(config).parsedDateParts = config._a.slice(0);
        getParsingFlags(config).meridiem = config._meridiem;
        config._a[HOUR] = meridiemFixWrap(
            config._locale,
            config._a[HOUR],
            config._meridiem
        );
        configFromArray(config);
        checkOverflow(config);
    }

    function meridiemFixWrap(locale, hour, meridiem) {
        var isPm;
        if (meridiem == null) {
            return hour;
        }
        if (locale.meridiemHour != null) {
            return locale.meridiemHour(hour, meridiem);
        } else if (locale.isPM != null) {
            isPm = locale.isPM(meridiem);
            if (isPm && hour < 12) {
                hour += 12;
            }
            if (!isPm && hour === 12) {
                hour = 0;
            }
            return hour;
        } else {
            return hour;
        }
    }

    function configFromStringAndArray(config) {
        var tempConfig, bestMoment, scoreToBeat, i, currentScore;
        if (config._f.length === 0) {
            getParsingFlags(config).invalidFormat = !0;
            config._d = new Date(NaN);
            return;
        }
        for (i = 0; i < config._f.length; i++) {
            currentScore = 0;
            tempConfig = copyConfig({}, config);
            if (config._useUTC != null) {
                tempConfig._useUTC = config._useUTC;
            }
            tempConfig._f = config._f[i];
            configFromStringAndFormat(tempConfig);
            if (!isValid(tempConfig)) {
                continue;
            }
            currentScore += getParsingFlags(tempConfig).charsLeftOver;
            currentScore +=
                getParsingFlags(tempConfig).unusedTokens.length * 10;
            getParsingFlags(tempConfig).score = currentScore;
            if (scoreToBeat == null || currentScore < scoreToBeat) {
                scoreToBeat = currentScore;
                bestMoment = tempConfig;
            }
        }
        extend(config, bestMoment || tempConfig);
    }

    function configFromObject(config) {
        if (config._d) {
            return;
        }
        var i = normalizeObjectUnits(config._i);
        config._a = map(
            [
                i.year,
                i.month,
                i.day || i.date,
                i.hour,
                i.minute,
                i.second,
                i.millisecond
            ],
            function(obj) {
                return obj && parseInt(obj, 10);
            }
        );
        configFromArray(config);
    }

    function createFromConfig(config) {
        var res = new Moment(checkOverflow(prepareConfig(config)));
        if (res._nextDay) {
            res.add(1, "d");
            res._nextDay = undefined;
        }
        return res;
    }

    function prepareConfig(config) {
        var input = config._i,
            format = config._f;
        config._locale = config._locale || getLocale(config._l);
        if (input === null || (format === undefined && input === "")) {
            return createInvalid({
                nullInput: !0
            });
        }
        if (typeof input === "string") {
            config._i = input = config._locale.preparse(input);
        }
        if (isMoment(input)) {
            return new Moment(checkOverflow(input));
        } else if (isDate(input)) {
            config._d = input;
        } else if (isArray(format)) {
            configFromStringAndArray(config);
        } else if (format) {
            configFromStringAndFormat(config);
        } else {
            configFromInput(config);
        }
        if (!isValid(config)) {
            config._d = null;
        }
        return config;
    }

    function configFromInput(config) {
        var input = config._i;
        if (isUndefined(input)) {
            config._d = new Date(hooks.now());
        } else if (isDate(input)) {
            config._d = new Date(input.valueOf());
        } else if (typeof input === "string") {
            configFromString(config);
        } else if (isArray(input)) {
            config._a = map(input.slice(0), function(obj) {
                return parseInt(obj, 10);
            });
            configFromArray(config);
        } else if (isObject(input)) {
            configFromObject(config);
        } else if (isNumber(input)) {
            config._d = new Date(input);
        } else {
            hooks.createFromInputFallback(config);
        }
    }

    function createLocalOrUTC(input, format, locale, strict, isUTC) {
        var c = {};
        if (locale === !0 || locale === !1) {
            strict = locale;
            locale = undefined;
        }
        if (
            (isObject(input) && isObjectEmpty(input)) ||
            (isArray(input) && input.length === 0)
        ) {
            input = undefined;
        }
        c._isAMomentObject = !0;
        c._useUTC = c._isUTC = isUTC;
        c._l = locale;
        c._i = input;
        c._f = format;
        c._strict = strict;
        return createFromConfig(c);
    }

    function createLocal(input, format, locale, strict) {
        return createLocalOrUTC(input, format, locale, strict, !1);
    }
    var prototypeMin = deprecate(
        "moment().min is deprecated, use moment.max instead. http://momentjs.com/guides/#/warnings/min-max/",
        function() {
            var other = createLocal.apply(null, arguments);
            if (this.isValid() && other.isValid()) {
                return other < this ? this : other;
            } else {
                return createInvalid();
            }
        }
    );
    var prototypeMax = deprecate(
        "moment().max is deprecated, use moment.min instead. http://momentjs.com/guides/#/warnings/min-max/",
        function() {
            var other = createLocal.apply(null, arguments);
            if (this.isValid() && other.isValid()) {
                return other > this ? this : other;
            } else {
                return createInvalid();
            }
        }
    );

    function pickBy(fn, moments) {
        var res, i;
        if (moments.length === 1 && isArray(moments[0])) {
            moments = moments[0];
        }
        if (!moments.length) {
            return createLocal();
        }
        res = moments[0];
        for (i = 1; i < moments.length; ++i) {
            if (!moments[i].isValid() || moments[i][fn](res)) {
                res = moments[i];
            }
        }
        return res;
    }

    function min() {
        var args = [].slice.call(arguments, 0);
        return pickBy("isBefore", args);
    }

    function max() {
        var args = [].slice.call(arguments, 0);
        return pickBy("isAfter", args);
    }
    var now = function() {
        return Date.now ? Date.now() : +new Date();
    };
    var ordering = [
        "year",
        "quarter",
        "month",
        "week",
        "day",
        "hour",
        "minute",
        "second",
        "millisecond"
    ];

    function isDurationValid(m) {
        for (var key in m) {
            if (
                !(
                    indexOf.call(ordering, key) !== -1 &&
                    (m[key] == null || !isNaN(m[key]))
                )
            ) {
                return !1;
            }
        }
        var unitHasDecimal = !1;
        for (var i = 0; i < ordering.length; ++i) {
            if (m[ordering[i]]) {
                if (unitHasDecimal) {
                    return !1;
                }
                if (parseFloat(m[ordering[i]]) !== toInt(m[ordering[i]])) {
                    unitHasDecimal = !0;
                }
            }
        }
        return !0;
    }

    function isValid$1() {
        return this._isValid;
    }

    function createInvalid$1() {
        return createDuration(NaN);
    }

    function Duration(duration) {
        var normalizedInput = normalizeObjectUnits(duration),
            years = normalizedInput.year || 0,
            quarters = normalizedInput.quarter || 0,
            months = normalizedInput.month || 0,
            weeks = normalizedInput.week || 0,
            days = normalizedInput.day || 0,
            hours = normalizedInput.hour || 0,
            minutes = normalizedInput.minute || 0,
            seconds = normalizedInput.second || 0,
            milliseconds = normalizedInput.millisecond || 0;
        this._isValid = isDurationValid(normalizedInput);
        this._milliseconds =
            +milliseconds +
            seconds * 1e3 +
            minutes * 6e4 +
            hours * 1000 * 60 * 60;
        this._days = +days + weeks * 7;
        this._months = +months + quarters * 3 + years * 12;
        this._data = {};
        this._locale = getLocale();
        this._bubble();
    }

    function isDuration(obj) {
        return obj instanceof Duration;
    }

    function absRound(number) {
        if (number < 0) {
            return Math.round(-1 * number) * -1;
        } else {
            return Math.round(number);
        }
    }

    function offset(token, separator) {
        addFormatToken(token, 0, 0, function() {
            var offset = this.utcOffset();
            var sign = "+";
            if (offset < 0) {
                offset = -offset;
                sign = "-";
            }
            return (
                sign +
                zeroFill(~~(offset / 60), 2) +
                separator +
                zeroFill(~~offset % 60, 2)
            );
        });
    }
    offset("Z", ":");
    offset("ZZ", "");
    addRegexToken("Z", matchShortOffset);
    addRegexToken("ZZ", matchShortOffset);
    addParseToken(["Z", "ZZ"], function(input, array, config) {
        config._useUTC = !0;
        config._tzm = offsetFromString(matchShortOffset, input);
    });
    var chunkOffset = /([\+\-]|\d\d)/gi;

    function offsetFromString(matcher, string) {
        var matches = (string || "").match(matcher);
        if (matches === null) {
            return null;
        }
        var chunk = matches[matches.length - 1] || [];
        var parts = (chunk + "").match(chunkOffset) || ["-", 0, 0];
        var minutes = +(parts[1] * 60) + toInt(parts[2]);
        return minutes === 0 ? 0 : parts[0] === "+" ? minutes : -minutes;
    }

    function cloneWithOffset(input, model) {
        var res, diff;
        if (model._isUTC) {
            res = model.clone();
            diff =
                (isMoment(input) || isDate(input)
                    ? input.valueOf()
                    : createLocal(input).valueOf()) - res.valueOf();
            res._d.setTime(res._d.valueOf() + diff);
            hooks.updateOffset(res, !1);
            return res;
        } else {
            return createLocal(input).local();
        }
    }

    function getDateOffset(m) {
        return -Math.round(m._d.getTimezoneOffset() / 15) * 15;
    }
    hooks.updateOffset = function() {};

    function getSetOffset(input, keepLocalTime, keepMinutes) {
        var offset = this._offset || 0,
            localAdjust;
        if (!this.isValid()) {
            return input != null ? this : NaN;
        }
        if (input != null) {
            if (typeof input === "string") {
                input = offsetFromString(matchShortOffset, input);
                if (input === null) {
                    return this;
                }
            } else if (Math.abs(input) < 16 && !keepMinutes) {
                input = input * 60;
            }
            if (!this._isUTC && keepLocalTime) {
                localAdjust = getDateOffset(this);
            }
            this._offset = input;
            this._isUTC = !0;
            if (localAdjust != null) {
                this.add(localAdjust, "m");
            }
            if (offset !== input) {
                if (!keepLocalTime || this._changeInProgress) {
                    addSubtract(
                        this,
                        createDuration(input - offset, "m"),
                        1,
                        !1
                    );
                } else if (!this._changeInProgress) {
                    this._changeInProgress = !0;
                    hooks.updateOffset(this, !0);
                    this._changeInProgress = null;
                }
            }
            return this;
        } else {
            return this._isUTC ? offset : getDateOffset(this);
        }
    }

    function getSetZone(input, keepLocalTime) {
        if (input != null) {
            if (typeof input !== "string") {
                input = -input;
            }
            this.utcOffset(input, keepLocalTime);
            return this;
        } else {
            return -this.utcOffset();
        }
    }

    function setOffsetToUTC(keepLocalTime) {
        return this.utcOffset(0, keepLocalTime);
    }

    function setOffsetToLocal(keepLocalTime) {
        if (this._isUTC) {
            this.utcOffset(0, keepLocalTime);
            this._isUTC = !1;
            if (keepLocalTime) {
                this.subtract(getDateOffset(this), "m");
            }
        }
        return this;
    }

    function setOffsetToParsedOffset() {
        if (this._tzm != null) {
            this.utcOffset(this._tzm, !1, !0);
        } else if (typeof this._i === "string") {
            var tZone = offsetFromString(matchOffset, this._i);
            if (tZone != null) {
                this.utcOffset(tZone);
            } else {
                this.utcOffset(0, !0);
            }
        }
        return this;
    }

    function hasAlignedHourOffset(input) {
        if (!this.isValid()) {
            return !1;
        }
        input = input ? createLocal(input).utcOffset() : 0;
        return (this.utcOffset() - input) % 60 === 0;
    }

    function isDaylightSavingTime() {
        return (
            this.utcOffset() >
                this.clone()
                    .month(0)
                    .utcOffset() ||
            this.utcOffset() >
                this.clone()
                    .month(5)
                    .utcOffset()
        );
    }

    function isDaylightSavingTimeShifted() {
        if (!isUndefined(this._isDSTShifted)) {
            return this._isDSTShifted;
        }
        var c = {};
        copyConfig(c, this);
        c = prepareConfig(c);
        if (c._a) {
            var other = c._isUTC ? createUTC(c._a) : createLocal(c._a);
            this._isDSTShifted =
                this.isValid() && compareArrays(c._a, other.toArray()) > 0;
        } else {
            this._isDSTShifted = !1;
        }
        return this._isDSTShifted;
    }

    function isLocal() {
        return this.isValid() ? !this._isUTC : !1;
    }

    function isUtcOffset() {
        return this.isValid() ? this._isUTC : !1;
    }

    function isUtc() {
        return this.isValid() ? this._isUTC && this._offset === 0 : !1;
    }
    var aspNetRegex = /^(\-|\+)?(?:(\d*)[. ])?(\d+)\:(\d+)(?:\:(\d+)(\.\d*)?)?$/;
    var isoRegex = /^(-|\+)?P(?:([-+]?[0-9,.]*)Y)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)W)?(?:([-+]?[0-9,.]*)D)?(?:T(?:([-+]?[0-9,.]*)H)?(?:([-+]?[0-9,.]*)M)?(?:([-+]?[0-9,.]*)S)?)?$/;

    function createDuration(input, key) {
        var duration = input,
            match = null,
            sign,
            ret,
            diffRes;
        if (isDuration(input)) {
            duration = {
                ms: input._milliseconds,
                d: input._days,
                M: input._months
            };
        } else if (isNumber(input)) {
            duration = {};
            if (key) {
                duration[key] = input;
            } else {
                duration.milliseconds = input;
            }
        } else if (!!(match = aspNetRegex.exec(input))) {
            sign = match[1] === "-" ? -1 : 1;
            duration = {
                y: 0,
                d: toInt(match[DATE]) * sign,
                h: toInt(match[HOUR]) * sign,
                m: toInt(match[MINUTE]) * sign,
                s: toInt(match[SECOND]) * sign,
                ms: toInt(absRound(match[MILLISECOND] * 1000)) * sign
            };
        } else if (!!(match = isoRegex.exec(input))) {
            sign = match[1] === "-" ? -1 : match[1] === "+" ? 1 : 1;
            duration = {
                y: parseIso(match[2], sign),
                M: parseIso(match[3], sign),
                w: parseIso(match[4], sign),
                d: parseIso(match[5], sign),
                h: parseIso(match[6], sign),
                m: parseIso(match[7], sign),
                s: parseIso(match[8], sign)
            };
        } else if (duration == null) {
            duration = {};
        } else if (
            typeof duration === "object" &&
            ("from" in duration || "to" in duration)
        ) {
            diffRes = momentsDifference(
                createLocal(duration.from),
                createLocal(duration.to)
            );
            duration = {};
            duration.ms = diffRes.milliseconds;
            duration.M = diffRes.months;
        }
        ret = new Duration(duration);
        if (isDuration(input) && hasOwnProp(input, "_locale")) {
            ret._locale = input._locale;
        }
        return ret;
    }
    createDuration.fn = Duration.prototype;
    createDuration.invalid = createInvalid$1;

    function parseIso(inp, sign) {
        var res = inp && parseFloat(inp.replace(",", "."));
        return (isNaN(res) ? 0 : res) * sign;
    }

    function positiveMomentsDifference(base, other) {
        var res = {
            milliseconds: 0,
            months: 0
        };
        res.months =
            other.month() - base.month() + (other.year() - base.year()) * 12;
        if (
            base
                .clone()
                .add(res.months, "M")
                .isAfter(other)
        ) {
            --res.months;
        }
        res.milliseconds = +other - +base.clone().add(res.months, "M");
        return res;
    }

    function momentsDifference(base, other) {
        var res;
        if (!(base.isValid() && other.isValid())) {
            return {
                milliseconds: 0,
                months: 0
            };
        }
        other = cloneWithOffset(other, base);
        if (base.isBefore(other)) {
            res = positiveMomentsDifference(base, other);
        } else {
            res = positiveMomentsDifference(other, base);
            res.milliseconds = -res.milliseconds;
            res.months = -res.months;
        }
        return res;
    }

    function createAdder(direction, name) {
        return function(val, period) {
            var dur, tmp;
            if (period !== null && !isNaN(+period)) {
                deprecateSimple(
                    name,
                    "moment()." +
                        name +
                        "(period, number) is deprecated. Please use moment()." +
                        name +
                        "(number, period). " +
                        "See http://momentjs.com/guides/#/warnings/add-inverted-param/ for more info."
                );
                tmp = val;
                val = period;
                period = tmp;
            }
            val = typeof val === "string" ? +val : val;
            dur = createDuration(val, period);
            addSubtract(this, dur, direction);
            return this;
        };
    }

    function addSubtract(mom, duration, isAdding, updateOffset) {
        var milliseconds = duration._milliseconds,
            days = absRound(duration._days),
            months = absRound(duration._months);
        if (!mom.isValid()) {
            return;
        }
        updateOffset = updateOffset == null ? !0 : updateOffset;
        if (months) {
            setMonth(mom, get(mom, "Month") + months * isAdding);
        }
        if (days) {
            set$1(mom, "Date", get(mom, "Date") + days * isAdding);
        }
        if (milliseconds) {
            mom._d.setTime(mom._d.valueOf() + milliseconds * isAdding);
        }
        if (updateOffset) {
            hooks.updateOffset(mom, days || months);
        }
    }
    var add = createAdder(1, "add");
    var subtract = createAdder(-1, "subtract");

    function getCalendarFormat(myMoment, now) {
        var diff = myMoment.diff(now, "days", !0);
        return diff < -6
            ? "sameElse"
            : diff < -1
            ? "lastWeek"
            : diff < 0
            ? "lastDay"
            : diff < 1
            ? "sameDay"
            : diff < 2
            ? "nextDay"
            : diff < 7
            ? "nextWeek"
            : "sameElse";
    }

    function calendar$1(time, formats) {
        var now = time || createLocal(),
            sod = cloneWithOffset(now, this).startOf("day"),
            format = hooks.calendarFormat(this, sod) || "sameElse";
        var output =
            formats &&
            (isFunction(formats[format])
                ? formats[format].call(this, now)
                : formats[format]);
        return this.format(
            output || this.localeData().calendar(format, this, createLocal(now))
        );
    }

    function clone() {
        return new Moment(this);
    }

    function isAfter(input, units) {
        var localInput = isMoment(input) ? input : createLocal(input);
        if (!(this.isValid() && localInput.isValid())) {
            return !1;
        }
        units = normalizeUnits(!isUndefined(units) ? units : "millisecond");
        if (units === "millisecond") {
            return this.valueOf() > localInput.valueOf();
        } else {
            return (
                localInput.valueOf() <
                this.clone()
                    .startOf(units)
                    .valueOf()
            );
        }
    }

    function isBefore(input, units) {
        var localInput = isMoment(input) ? input : createLocal(input);
        if (!(this.isValid() && localInput.isValid())) {
            return !1;
        }
        units = normalizeUnits(!isUndefined(units) ? units : "millisecond");
        if (units === "millisecond") {
            return this.valueOf() < localInput.valueOf();
        } else {
            return (
                this.clone()
                    .endOf(units)
                    .valueOf() < localInput.valueOf()
            );
        }
    }

    function isBetween(from, to, units, inclusivity) {
        inclusivity = inclusivity || "()";
        return (
            (inclusivity[0] === "("
                ? this.isAfter(from, units)
                : !this.isBefore(from, units)) &&
            (inclusivity[1] === ")"
                ? this.isBefore(to, units)
                : !this.isAfter(to, units))
        );
    }

    function isSame(input, units) {
        var localInput = isMoment(input) ? input : createLocal(input),
            inputMs;
        if (!(this.isValid() && localInput.isValid())) {
            return !1;
        }
        units = normalizeUnits(units || "millisecond");
        if (units === "millisecond") {
            return this.valueOf() === localInput.valueOf();
        } else {
            inputMs = localInput.valueOf();
            return (
                this.clone()
                    .startOf(units)
                    .valueOf() <= inputMs &&
                inputMs <=
                    this.clone()
                        .endOf(units)
                        .valueOf()
            );
        }
    }

    function isSameOrAfter(input, units) {
        return this.isSame(input, units) || this.isAfter(input, units);
    }

    function isSameOrBefore(input, units) {
        return this.isSame(input, units) || this.isBefore(input, units);
    }

    function diff(input, units, asFloat) {
        var that, zoneDelta, delta, output;
        if (!this.isValid()) {
            return NaN;
        }
        that = cloneWithOffset(input, this);
        if (!that.isValid()) {
            return NaN;
        }
        zoneDelta = (that.utcOffset() - this.utcOffset()) * 6e4;
        units = normalizeUnits(units);
        switch (units) {
            case "year":
                output = monthDiff(this, that) / 12;
                break;
            case "month":
                output = monthDiff(this, that);
                break;
            case "quarter":
                output = monthDiff(this, that) / 3;
                break;
            case "second":
                output = (this - that) / 1e3;
                break;
            case "minute":
                output = (this - that) / 6e4;
                break;
            case "hour":
                output = (this - that) / 36e5;
                break;
            case "day":
                output = (this - that - zoneDelta) / 864e5;
                break;
            case "week":
                output = (this - that - zoneDelta) / 6048e5;
                break;
            default:
                output = this - that;
        }
        return asFloat ? output : absFloor(output);
    }

    function monthDiff(a, b) {
        var wholeMonthDiff =
                (b.year() - a.year()) * 12 + (b.month() - a.month()),
            anchor = a.clone().add(wholeMonthDiff, "months"),
            anchor2,
            adjust;
        if (b - anchor < 0) {
            anchor2 = a.clone().add(wholeMonthDiff - 1, "months");
            adjust = (b - anchor) / (anchor - anchor2);
        } else {
            anchor2 = a.clone().add(wholeMonthDiff + 1, "months");
            adjust = (b - anchor) / (anchor2 - anchor);
        }
        return -(wholeMonthDiff + adjust) || 0;
    }
    hooks.defaultFormat = "YYYY-MM-DDTHH:mm:ssZ";
    hooks.defaultFormatUtc = "YYYY-MM-DDTHH:mm:ss[Z]";

    function toString() {
        return this.clone()
            .locale("en")
            .format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ");
    }

    function toISOString() {
        if (!this.isValid()) {
            return null;
        }
        var m = this.clone().utc();
        if (m.year() < 0 || m.year() > 9999) {
            return formatMoment(m, "YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]");
        }
        if (isFunction(Date.prototype.toISOString)) {
            return this.toDate().toISOString();
        }
        return formatMoment(m, "YYYY-MM-DD[T]HH:mm:ss.SSS[Z]");
    }

    function inspect() {
        if (!this.isValid()) {
            return "moment.invalid(/* " + this._i + " */)";
        }
        var func = "moment";
        var zone = "";
        if (!this.isLocal()) {
            func = this.utcOffset() === 0 ? "moment.utc" : "moment.parseZone";
            zone = "Z";
        }
        var prefix = "[" + func + '("]';
        var year = 0 <= this.year() && this.year() <= 9999 ? "YYYY" : "YYYYYY";
        var datetime = "-MM-DD[T]HH:mm:ss.SSS";
        var suffix = zone + '[")]';
        return this.format(prefix + year + datetime + suffix);
    }

    function format(inputString) {
        if (!inputString) {
            inputString = this.isUtc()
                ? hooks.defaultFormatUtc
                : hooks.defaultFormat;
        }
        var output = formatMoment(this, inputString);
        return this.localeData().postformat(output);
    }

    function from(time, withoutSuffix) {
        if (
            this.isValid() &&
            ((isMoment(time) && time.isValid()) || createLocal(time).isValid())
        ) {
            return createDuration({
                to: this,
                from: time
            })
                .locale(this.locale())
                .humanize(!withoutSuffix);
        } else {
            return this.localeData().invalidDate();
        }
    }

    function fromNow(withoutSuffix) {
        return this.from(createLocal(), withoutSuffix);
    }

    function to(time, withoutSuffix) {
        if (
            this.isValid() &&
            ((isMoment(time) && time.isValid()) || createLocal(time).isValid())
        ) {
            return createDuration({
                from: this,
                to: time
            })
                .locale(this.locale())
                .humanize(!withoutSuffix);
        } else {
            return this.localeData().invalidDate();
        }
    }

    function toNow(withoutSuffix) {
        return this.to(createLocal(), withoutSuffix);
    }

    function locale(key) {
        var newLocaleData;
        if (key === undefined) {
            return this._locale._abbr;
        } else {
            newLocaleData = getLocale(key);
            if (newLocaleData != null) {
                this._locale = newLocaleData;
            }
            return this;
        }
    }
    var lang = deprecate(
        "moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.",
        function(key) {
            if (key === undefined) {
                return this.localeData();
            } else {
                return this.locale(key);
            }
        }
    );

    function localeData() {
        return this._locale;
    }

    function startOf(units) {
        units = normalizeUnits(units);
        switch (units) {
            case "year":
                this.month(0);
            case "quarter":
            case "month":
                this.date(1);
            case "week":
            case "isoWeek":
            case "day":
            case "date":
                this.hours(0);
            case "hour":
                this.minutes(0);
            case "minute":
                this.seconds(0);
            case "second":
                this.milliseconds(0);
        }
        if (units === "week") {
            this.weekday(0);
        }
        if (units === "isoWeek") {
            this.isoWeekday(1);
        }
        if (units === "quarter") {
            this.month(Math.floor(this.month() / 3) * 3);
        }
        return this;
    }

    function endOf(units) {
        units = normalizeUnits(units);
        if (units === undefined || units === "millisecond") {
            return this;
        }
        if (units === "date") {
            units = "day";
        }
        return this.startOf(units)
            .add(1, units === "isoWeek" ? "week" : units)
            .subtract(1, "ms");
    }

    function valueOf() {
        return this._d.valueOf() - (this._offset || 0) * 60000;
    }

    function unix() {
        return Math.floor(this.valueOf() / 1000);
    }

    function toDate() {
        return new Date(this.valueOf());
    }

    function toArray() {
        var m = this;
        return [
            m.year(),
            m.month(),
            m.date(),
            m.hour(),
            m.minute(),
            m.second(),
            m.millisecond()
        ];
    }

    function toObject() {
        var m = this;
        return {
            years: m.year(),
            months: m.month(),
            date: m.date(),
            hours: m.hours(),
            minutes: m.minutes(),
            seconds: m.seconds(),
            milliseconds: m.milliseconds()
        };
    }

    function toJSON() {
        return this.isValid() ? this.toISOString() : null;
    }

    function isValid$2() {
        return isValid(this);
    }

    function parsingFlags() {
        return extend({}, getParsingFlags(this));
    }

    function invalidAt() {
        return getParsingFlags(this).overflow;
    }

    function creationData() {
        return {
            input: this._i,
            format: this._f,
            locale: this._locale,
            isUTC: this._isUTC,
            strict: this._strict
        };
    }
    addFormatToken(0, ["gg", 2], 0, function() {
        return this.weekYear() % 100;
    });
    addFormatToken(0, ["GG", 2], 0, function() {
        return this.isoWeekYear() % 100;
    });

    function addWeekYearFormatToken(token, getter) {
        addFormatToken(0, [token, token.length], 0, getter);
    }
    addWeekYearFormatToken("gggg", "weekYear");
    addWeekYearFormatToken("ggggg", "weekYear");
    addWeekYearFormatToken("GGGG", "isoWeekYear");
    addWeekYearFormatToken("GGGGG", "isoWeekYear");
    addUnitAlias("weekYear", "gg");
    addUnitAlias("isoWeekYear", "GG");
    addUnitPriority("weekYear", 1);
    addUnitPriority("isoWeekYear", 1);
    addRegexToken("G", matchSigned);
    addRegexToken("g", matchSigned);
    addRegexToken("GG", match1to2, match2);
    addRegexToken("gg", match1to2, match2);
    addRegexToken("GGGG", match1to4, match4);
    addRegexToken("gggg", match1to4, match4);
    addRegexToken("GGGGG", match1to6, match6);
    addRegexToken("ggggg", match1to6, match6);
    addWeekParseToken(["gggg", "ggggg", "GGGG", "GGGGG"], function(
        input,
        week,
        config,
        token
    ) {
        week[token.substr(0, 2)] = toInt(input);
    });
    addWeekParseToken(["gg", "GG"], function(input, week, config, token) {
        week[token] = hooks.parseTwoDigitYear(input);
    });

    function getSetWeekYear(input) {
        return getSetWeekYearHelper.call(
            this,
            input,
            this.week(),
            this.weekday(),
            this.localeData()._week.dow,
            this.localeData()._week.doy
        );
    }

    function getSetISOWeekYear(input) {
        return getSetWeekYearHelper.call(
            this,
            input,
            this.isoWeek(),
            this.isoWeekday(),
            1,
            4
        );
    }

    function getISOWeeksInYear() {
        return weeksInYear(this.year(), 1, 4);
    }

    function getWeeksInYear() {
        var weekInfo = this.localeData()._week;
        return weeksInYear(this.year(), weekInfo.dow, weekInfo.doy);
    }

    function getSetWeekYearHelper(input, week, weekday, dow, doy) {
        var weeksTarget;
        if (input == null) {
            return weekOfYear(this, dow, doy).year;
        } else {
            weeksTarget = weeksInYear(input, dow, doy);
            if (week > weeksTarget) {
                week = weeksTarget;
            }
            return setWeekAll.call(this, input, week, weekday, dow, doy);
        }
    }

    function setWeekAll(weekYear, week, weekday, dow, doy) {
        var dayOfYearData = dayOfYearFromWeeks(
                weekYear,
                week,
                weekday,
                dow,
                doy
            ),
            date = createUTCDate(
                dayOfYearData.year,
                0,
                dayOfYearData.dayOfYear
            );
        this.year(date.getUTCFullYear());
        this.month(date.getUTCMonth());
        this.date(date.getUTCDate());
        return this;
    }
    addFormatToken("Q", 0, "Qo", "quarter");
    addUnitAlias("quarter", "Q");
    addUnitPriority("quarter", 7);
    addRegexToken("Q", match1);
    addParseToken("Q", function(input, array) {
        array[MONTH] = (toInt(input) - 1) * 3;
    });

    function getSetQuarter(input) {
        return input == null
            ? Math.ceil((this.month() + 1) / 3)
            : this.month((input - 1) * 3 + (this.month() % 3));
    }
    addFormatToken("D", ["DD", 2], "Do", "date");
    addUnitAlias("date", "D");
    addUnitPriority("date", 9);
    addRegexToken("D", match1to2);
    addRegexToken("DD", match1to2, match2);
    addRegexToken("Do", function(isStrict, locale) {
        return isStrict
            ? locale._dayOfMonthOrdinalParse || locale._ordinalParse
            : locale._dayOfMonthOrdinalParseLenient;
    });
    addParseToken(["D", "DD"], DATE);
    addParseToken("Do", function(input, array) {
        array[DATE] = toInt(input.match(match1to2)[0], 10);
    });
    var getSetDayOfMonth = makeGetSet("Date", !0);
    addFormatToken("DDD", ["DDDD", 3], "DDDo", "dayOfYear");
    addUnitAlias("dayOfYear", "DDD");
    addUnitPriority("dayOfYear", 4);
    addRegexToken("DDD", match1to3);
    addRegexToken("DDDD", match3);
    addParseToken(["DDD", "DDDD"], function(input, array, config) {
        config._dayOfYear = toInt(input);
    });

    function getSetDayOfYear(input) {
        var dayOfYear =
            Math.round(
                (this.clone().startOf("day") - this.clone().startOf("year")) /
                    864e5
            ) + 1;
        return input == null ? dayOfYear : this.add(input - dayOfYear, "d");
    }
    addFormatToken("m", ["mm", 2], 0, "minute");
    addUnitAlias("minute", "m");
    addUnitPriority("minute", 14);
    addRegexToken("m", match1to2);
    addRegexToken("mm", match1to2, match2);
    addParseToken(["m", "mm"], MINUTE);
    var getSetMinute = makeGetSet("Minutes", !1);
    addFormatToken("s", ["ss", 2], 0, "second");
    addUnitAlias("second", "s");
    addUnitPriority("second", 15);
    addRegexToken("s", match1to2);
    addRegexToken("ss", match1to2, match2);
    addParseToken(["s", "ss"], SECOND);
    var getSetSecond = makeGetSet("Seconds", !1);
    addFormatToken("S", 0, 0, function() {
        return ~~(this.millisecond() / 100);
    });
    addFormatToken(0, ["SS", 2], 0, function() {
        return ~~(this.millisecond() / 10);
    });
    addFormatToken(0, ["SSS", 3], 0, "millisecond");
    addFormatToken(0, ["SSSS", 4], 0, function() {
        return this.millisecond() * 10;
    });
    addFormatToken(0, ["SSSSS", 5], 0, function() {
        return this.millisecond() * 100;
    });
    addFormatToken(0, ["SSSSSS", 6], 0, function() {
        return this.millisecond() * 1000;
    });
    addFormatToken(0, ["SSSSSSS", 7], 0, function() {
        return this.millisecond() * 10000;
    });
    addFormatToken(0, ["SSSSSSSS", 8], 0, function() {
        return this.millisecond() * 100000;
    });
    addFormatToken(0, ["SSSSSSSSS", 9], 0, function() {
        return this.millisecond() * 1000000;
    });
    addUnitAlias("millisecond", "ms");
    addUnitPriority("millisecond", 16);
    addRegexToken("S", match1to3, match1);
    addRegexToken("SS", match1to3, match2);
    addRegexToken("SSS", match1to3, match3);
    var token;
    for (token = "SSSS"; token.length <= 9; token += "S") {
        addRegexToken(token, matchUnsigned);
    }

    function parseMs(input, array) {
        array[MILLISECOND] = toInt(("0." + input) * 1000);
    }
    for (token = "S"; token.length <= 9; token += "S") {
        addParseToken(token, parseMs);
    }
    var getSetMillisecond = makeGetSet("Milliseconds", !1);
    addFormatToken("z", 0, 0, "zoneAbbr");
    addFormatToken("zz", 0, 0, "zoneName");

    function getZoneAbbr() {
        return this._isUTC ? "UTC" : "";
    }

    function getZoneName() {
        return this._isUTC ? "Coordinated Universal Time" : "";
    }
    var proto = Moment.prototype;
    proto.add = add;
    proto.calendar = calendar$1;
    proto.clone = clone;
    proto.diff = diff;
    proto.endOf = endOf;
    proto.format = format;
    proto.from = from;
    proto.fromNow = fromNow;
    proto.to = to;
    proto.toNow = toNow;
    proto.get = stringGet;
    proto.invalidAt = invalidAt;
    proto.isAfter = isAfter;
    proto.isBefore = isBefore;
    proto.isBetween = isBetween;
    proto.isSame = isSame;
    proto.isSameOrAfter = isSameOrAfter;
    proto.isSameOrBefore = isSameOrBefore;
    proto.isValid = isValid$2;
    proto.lang = lang;
    proto.locale = locale;
    proto.localeData = localeData;
    proto.max = prototypeMax;
    proto.min = prototypeMin;
    proto.parsingFlags = parsingFlags;
    proto.set = stringSet;
    proto.startOf = startOf;
    proto.subtract = subtract;
    proto.toArray = toArray;
    proto.toObject = toObject;
    proto.toDate = toDate;
    proto.toISOString = toISOString;
    proto.inspect = inspect;
    proto.toJSON = toJSON;
    proto.toString = toString;
    proto.unix = unix;
    proto.valueOf = valueOf;
    proto.creationData = creationData;
    proto.year = getSetYear;
    proto.isLeapYear = getIsLeapYear;
    proto.weekYear = getSetWeekYear;
    proto.isoWeekYear = getSetISOWeekYear;
    proto.quarter = proto.quarters = getSetQuarter;
    proto.month = getSetMonth;
    proto.daysInMonth = getDaysInMonth;
    proto.week = proto.weeks = getSetWeek;
    proto.isoWeek = proto.isoWeeks = getSetISOWeek;
    proto.weeksInYear = getWeeksInYear;
    proto.isoWeeksInYear = getISOWeeksInYear;
    proto.date = getSetDayOfMonth;
    proto.day = proto.days = getSetDayOfWeek;
    proto.weekday = getSetLocaleDayOfWeek;
    proto.isoWeekday = getSetISODayOfWeek;
    proto.dayOfYear = getSetDayOfYear;
    proto.hour = proto.hours = getSetHour;
    proto.minute = proto.minutes = getSetMinute;
    proto.second = proto.seconds = getSetSecond;
    proto.millisecond = proto.milliseconds = getSetMillisecond;
    proto.utcOffset = getSetOffset;
    proto.utc = setOffsetToUTC;
    proto.local = setOffsetToLocal;
    proto.parseZone = setOffsetToParsedOffset;
    proto.hasAlignedHourOffset = hasAlignedHourOffset;
    proto.isDST = isDaylightSavingTime;
    proto.isLocal = isLocal;
    proto.isUtcOffset = isUtcOffset;
    proto.isUtc = isUtc;
    proto.isUTC = isUtc;
    proto.zoneAbbr = getZoneAbbr;
    proto.zoneName = getZoneName;
    proto.dates = deprecate(
        "dates accessor is deprecated. Use date instead.",
        getSetDayOfMonth
    );
    proto.months = deprecate(
        "months accessor is deprecated. Use month instead",
        getSetMonth
    );
    proto.years = deprecate(
        "years accessor is deprecated. Use year instead",
        getSetYear
    );
    proto.zone = deprecate(
        "moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/",
        getSetZone
    );
    proto.isDSTShifted = deprecate(
        "isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information",
        isDaylightSavingTimeShifted
    );

    function createUnix(input) {
        return createLocal(input * 1000);
    }

    function createInZone() {
        return createLocal.apply(null, arguments).parseZone();
    }

    function preParsePostFormat(string) {
        return string;
    }
    var proto$1 = Locale.prototype;
    proto$1.calendar = calendar;
    proto$1.longDateFormat = longDateFormat;
    proto$1.invalidDate = invalidDate;
    proto$1.ordinal = ordinal;
    proto$1.preparse = preParsePostFormat;
    proto$1.postformat = preParsePostFormat;
    proto$1.relativeTime = relativeTime;
    proto$1.pastFuture = pastFuture;
    proto$1.set = set;
    proto$1.months = localeMonths;
    proto$1.monthsShort = localeMonthsShort;
    proto$1.monthsParse = localeMonthsParse;
    proto$1.monthsRegex = monthsRegex;
    proto$1.monthsShortRegex = monthsShortRegex;
    proto$1.week = localeWeek;
    proto$1.firstDayOfYear = localeFirstDayOfYear;
    proto$1.firstDayOfWeek = localeFirstDayOfWeek;
    proto$1.weekdays = localeWeekdays;
    proto$1.weekdaysMin = localeWeekdaysMin;
    proto$1.weekdaysShort = localeWeekdaysShort;
    proto$1.weekdaysParse = localeWeekdaysParse;
    proto$1.weekdaysRegex = weekdaysRegex;
    proto$1.weekdaysShortRegex = weekdaysShortRegex;
    proto$1.weekdaysMinRegex = weekdaysMinRegex;
    proto$1.isPM = localeIsPM;
    proto$1.meridiem = localeMeridiem;

    function get$1(format, index, field, setter) {
        var locale = getLocale();
        var utc = createUTC().set(setter, index);
        return locale[field](utc, format);
    }

    function listMonthsImpl(format, index, field) {
        if (isNumber(format)) {
            index = format;
            format = undefined;
        }
        format = format || "";
        if (index != null) {
            return get$1(format, index, field, "month");
        }
        var i;
        var out = [];
        for (i = 0; i < 12; i++) {
            out[i] = get$1(format, i, field, "month");
        }
        return out;
    }

    function listWeekdaysImpl(localeSorted, format, index, field) {
        if (typeof localeSorted === "boolean") {
            if (isNumber(format)) {
                index = format;
                format = undefined;
            }
            format = format || "";
        } else {
            format = localeSorted;
            index = format;
            localeSorted = !1;
            if (isNumber(format)) {
                index = format;
                format = undefined;
            }
            format = format || "";
        }
        var locale = getLocale(),
            shift = localeSorted ? locale._week.dow : 0;
        if (index != null) {
            return get$1(format, (index + shift) % 7, field, "day");
        }
        var i;
        var out = [];
        for (i = 0; i < 7; i++) {
            out[i] = get$1(format, (i + shift) % 7, field, "day");
        }
        return out;
    }

    function listMonths(format, index) {
        return listMonthsImpl(format, index, "months");
    }

    function listMonthsShort(format, index) {
        return listMonthsImpl(format, index, "monthsShort");
    }

    function listWeekdays(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, "weekdays");
    }

    function listWeekdaysShort(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, "weekdaysShort");
    }

    function listWeekdaysMin(localeSorted, format, index) {
        return listWeekdaysImpl(localeSorted, format, index, "weekdaysMin");
    }
    getSetGlobalLocale("en", {
        dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
        ordinal: function(number) {
            var b = number % 10,
                output =
                    toInt((number % 100) / 10) === 1
                        ? "th"
                        : b === 1
                        ? "st"
                        : b === 2
                        ? "nd"
                        : b === 3
                        ? "rd"
                        : "th";
            return number + output;
        }
    });
    hooks.lang = deprecate(
        "moment.lang is deprecated. Use moment.locale instead.",
        getSetGlobalLocale
    );
    hooks.langData = deprecate(
        "moment.langData is deprecated. Use moment.localeData instead.",
        getLocale
    );
    var mathAbs = Math.abs;

    function abs() {
        var data = this._data;
        this._milliseconds = mathAbs(this._milliseconds);
        this._days = mathAbs(this._days);
        this._months = mathAbs(this._months);
        data.milliseconds = mathAbs(data.milliseconds);
        data.seconds = mathAbs(data.seconds);
        data.minutes = mathAbs(data.minutes);
        data.hours = mathAbs(data.hours);
        data.months = mathAbs(data.months);
        data.years = mathAbs(data.years);
        return this;
    }

    function addSubtract$1(duration, input, value, direction) {
        var other = createDuration(input, value);
        duration._milliseconds += direction * other._milliseconds;
        duration._days += direction * other._days;
        duration._months += direction * other._months;
        return duration._bubble();
    }

    function add$1(input, value) {
        return addSubtract$1(this, input, value, 1);
    }

    function subtract$1(input, value) {
        return addSubtract$1(this, input, value, -1);
    }

    function absCeil(number) {
        if (number < 0) {
            return Math.floor(number);
        } else {
            return Math.ceil(number);
        }
    }

    function bubble() {
        var milliseconds = this._milliseconds;
        var days = this._days;
        var months = this._months;
        var data = this._data;
        var seconds, minutes, hours, years, monthsFromDays;
        if (
            !(
                (milliseconds >= 0 && days >= 0 && months >= 0) ||
                (milliseconds <= 0 && days <= 0 && months <= 0)
            )
        ) {
            milliseconds += absCeil(monthsToDays(months) + days) * 864e5;
            days = 0;
            months = 0;
        }
        data.milliseconds = milliseconds % 1000;
        seconds = absFloor(milliseconds / 1000);
        data.seconds = seconds % 60;
        minutes = absFloor(seconds / 60);
        data.minutes = minutes % 60;
        hours = absFloor(minutes / 60);
        data.hours = hours % 24;
        days += absFloor(hours / 24);
        monthsFromDays = absFloor(daysToMonths(days));
        months += monthsFromDays;
        days -= absCeil(monthsToDays(monthsFromDays));
        years = absFloor(months / 12);
        months %= 12;
        data.days = days;
        data.months = months;
        data.years = years;
        return this;
    }

    function daysToMonths(days) {
        return (days * 4800) / 146097;
    }

    function monthsToDays(months) {
        return (months * 146097) / 4800;
    }

    function as(units) {
        if (!this.isValid()) {
            return NaN;
        }
        var days;
        var months;
        var milliseconds = this._milliseconds;
        units = normalizeUnits(units);
        if (units === "month" || units === "year") {
            days = this._days + milliseconds / 864e5;
            months = this._months + daysToMonths(days);
            return units === "month" ? months : months / 12;
        } else {
            days = this._days + Math.round(monthsToDays(this._months));
            switch (units) {
                case "week":
                    return days / 7 + milliseconds / 6048e5;
                case "day":
                    return days + milliseconds / 864e5;
                case "hour":
                    return days * 24 + milliseconds / 36e5;
                case "minute":
                    return days * 1440 + milliseconds / 6e4;
                case "second":
                    return days * 86400 + milliseconds / 1000;
                case "millisecond":
                    return Math.floor(days * 864e5) + milliseconds;
                default:
                    throw new Error("Unknown unit " + units);
            }
        }
    }

    function valueOf$1() {
        if (!this.isValid()) {
            return NaN;
        }
        return (
            this._milliseconds +
            this._days * 864e5 +
            (this._months % 12) * 2592e6 +
            toInt(this._months / 12) * 31536e6
        );
    }

    function makeAs(alias) {
        return function() {
            return this.as(alias);
        };
    }
    var asMilliseconds = makeAs("ms");
    var asSeconds = makeAs("s");
    var asMinutes = makeAs("m");
    var asHours = makeAs("h");
    var asDays = makeAs("d");
    var asWeeks = makeAs("w");
    var asMonths = makeAs("M");
    var asYears = makeAs("y");

    function clone$1() {
        return createDuration(this);
    }

    function get$2(units) {
        units = normalizeUnits(units);
        return this.isValid() ? this[units + "s"]() : NaN;
    }

    function makeGetter(name) {
        return function() {
            return this.isValid() ? this._data[name] : NaN;
        };
    }
    var milliseconds = makeGetter("milliseconds");
    var seconds = makeGetter("seconds");
    var minutes = makeGetter("minutes");
    var hours = makeGetter("hours");
    var days = makeGetter("days");
    var months = makeGetter("months");
    var years = makeGetter("years");

    function weeks() {
        return absFloor(this.days() / 7);
    }
    var round = Math.round;
    var thresholds = {
        ss: 44,
        s: 45,
        m: 45,
        h: 22,
        d: 26,
        M: 11
    };

    function substituteTimeAgo(
        string,
        number,
        withoutSuffix,
        isFuture,
        locale
    ) {
        return locale.relativeTime(
            number || 1,
            !!withoutSuffix,
            string,
            isFuture
        );
    }

    function relativeTime$1(posNegDuration, withoutSuffix, locale) {
        var duration = createDuration(posNegDuration).abs();
        var seconds = round(duration.as("s"));
        var minutes = round(duration.as("m"));
        var hours = round(duration.as("h"));
        var days = round(duration.as("d"));
        var months = round(duration.as("M"));
        var years = round(duration.as("y"));
        var a = (seconds <= thresholds.ss && ["s", seconds]) ||
            (seconds < thresholds.s && ["ss", seconds]) ||
            (minutes <= 1 && ["m"]) ||
            (minutes < thresholds.m && ["mm", minutes]) ||
            (hours <= 1 && ["h"]) ||
            (hours < thresholds.h && ["hh", hours]) ||
            (days <= 1 && ["d"]) ||
            (days < thresholds.d && ["dd", days]) ||
            (months <= 1 && ["M"]) ||
            (months < thresholds.M && ["MM", months]) ||
            (years <= 1 && ["y"]) || ["yy", years];
        a[2] = withoutSuffix;
        a[3] = +posNegDuration > 0;
        a[4] = locale;
        return substituteTimeAgo.apply(null, a);
    }

    function getSetRelativeTimeRounding(roundingFunction) {
        if (roundingFunction === undefined) {
            return round;
        }
        if (typeof roundingFunction === "function") {
            round = roundingFunction;
            return !0;
        }
        return !1;
    }

    function getSetRelativeTimeThreshold(threshold, limit) {
        if (thresholds[threshold] === undefined) {
            return !1;
        }
        if (limit === undefined) {
            return thresholds[threshold];
        }
        thresholds[threshold] = limit;
        if (threshold === "s") {
            thresholds.ss = limit - 1;
        }
        return !0;
    }

    function humanize(withSuffix) {
        if (!this.isValid()) {
            return this.localeData().invalidDate();
        }
        var locale = this.localeData();
        var output = relativeTime$1(this, !withSuffix, locale);
        if (withSuffix) {
            output = locale.pastFuture(+this, output);
        }
        return locale.postformat(output);
    }
    var abs$1 = Math.abs;

    function sign(x) {
        return (x > 0) - (x < 0) || +x;
    }

    function toISOString$1() {
        if (!this.isValid()) {
            return this.localeData().invalidDate();
        }
        var seconds = abs$1(this._milliseconds) / 1000;
        var days = abs$1(this._days);
        var months = abs$1(this._months);
        var minutes, hours, years;
        minutes = absFloor(seconds / 60);
        hours = absFloor(minutes / 60);
        seconds %= 60;
        minutes %= 60;
        years = absFloor(months / 12);
        months %= 12;
        var Y = years;
        var M = months;
        var D = days;
        var h = hours;
        var m = minutes;
        var s = seconds ? seconds.toFixed(3).replace(/\.?0+$/, "") : "";
        var total = this.asSeconds();
        if (!total) {
            return "P0D";
        }
        var totalSign = total < 0 ? "-" : "";
        var ymSign = sign(this._months) !== sign(total) ? "-" : "";
        var daysSign = sign(this._days) !== sign(total) ? "-" : "";
        var hmsSign = sign(this._milliseconds) !== sign(total) ? "-" : "";
        return (
            totalSign +
            "P" +
            (Y ? ymSign + Y + "Y" : "") +
            (M ? ymSign + M + "M" : "") +
            (D ? daysSign + D + "D" : "") +
            (h || m || s ? "T" : "") +
            (h ? hmsSign + h + "H" : "") +
            (m ? hmsSign + m + "M" : "") +
            (s ? hmsSign + s + "S" : "")
        );
    }
    var proto$2 = Duration.prototype;
    proto$2.isValid = isValid$1;
    proto$2.abs = abs;
    proto$2.add = add$1;
    proto$2.subtract = subtract$1;
    proto$2.as = as;
    proto$2.asMilliseconds = asMilliseconds;
    proto$2.asSeconds = asSeconds;
    proto$2.asMinutes = asMinutes;
    proto$2.asHours = asHours;
    proto$2.asDays = asDays;
    proto$2.asWeeks = asWeeks;
    proto$2.asMonths = asMonths;
    proto$2.asYears = asYears;
    proto$2.valueOf = valueOf$1;
    proto$2._bubble = bubble;
    proto$2.clone = clone$1;
    proto$2.get = get$2;
    proto$2.milliseconds = milliseconds;
    proto$2.seconds = seconds;
    proto$2.minutes = minutes;
    proto$2.hours = hours;
    proto$2.days = days;
    proto$2.weeks = weeks;
    proto$2.months = months;
    proto$2.years = years;
    proto$2.humanize = humanize;
    proto$2.toISOString = toISOString$1;
    proto$2.toString = toISOString$1;
    proto$2.toJSON = toISOString$1;
    proto$2.locale = locale;
    proto$2.localeData = localeData;
    proto$2.toIsoString = deprecate(
        "toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)",
        toISOString$1
    );
    proto$2.lang = lang;
    addFormatToken("X", 0, 0, "unix");
    addFormatToken("x", 0, 0, "valueOf");
    addRegexToken("x", matchSigned);
    addRegexToken("X", matchTimestamp);
    addParseToken("X", function(input, array, config) {
        config._d = new Date(parseFloat(input, 10) * 1000);
    });
    addParseToken("x", function(input, array, config) {
        config._d = new Date(toInt(input));
    });
    hooks.version = "2.19.3";
    setHookCallback(createLocal);
    hooks.fn = proto;
    hooks.min = min;
    hooks.max = max;
    hooks.now = now;
    hooks.utc = createUTC;
    hooks.unix = createUnix;
    hooks.months = listMonths;
    hooks.isDate = isDate;
    hooks.locale = getSetGlobalLocale;
    hooks.invalid = createInvalid;
    hooks.duration = createDuration;
    hooks.isMoment = isMoment;
    hooks.weekdays = listWeekdays;
    hooks.parseZone = createInZone;
    hooks.localeData = getLocale;
    hooks.isDuration = isDuration;
    hooks.monthsShort = listMonthsShort;
    hooks.weekdaysMin = listWeekdaysMin;
    hooks.defineLocale = defineLocale;
    hooks.updateLocale = updateLocale;
    hooks.locales = listLocales;
    hooks.weekdaysShort = listWeekdaysShort;
    hooks.normalizeUnits = normalizeUnits;
    hooks.relativeTimeRounding = getSetRelativeTimeRounding;
    hooks.relativeTimeThreshold = getSetRelativeTimeThreshold;
    hooks.calendarFormat = getCalendarFormat;
    hooks.prototype = proto;
    return hooks;
});
if (typeof Object.create !== "function") {
    Object.create = function(obj) {
        function F() {}
        F.prototype = obj;
        return new F();
    };
}
(function($, window, document, undefined) {
    $.fn.socialfeed = function(_options) {
        var defaults = {
            plugin_folder: "",
            template: "template.html",
            show_media: !1,
            media_min_width: 300,
            length: 500,
            date_format: "ll",
            date_locale: "en"
        };
        var options = $.extend(defaults, _options),
            container = $(this),
            template,
            social_networks = [
                "facebook",
                "instagram",
                "vk",
                "google",
                "blogspot",
                "twitter",
                "pinterest",
                "rss"
            ],
            posts_to_load_count = 0,
            loaded_post_count = 0;

        function calculatePostsToLoadCount() {
            social_networks.forEach(function(network) {
                if (options[network]) {
                    if (options[network].accounts) {
                        posts_to_load_count +=
                            options[network].limit *
                            options[network].accounts.length;
                    } else if (options[network].urls) {
                        posts_to_load_count +=
                            options[network].limit *
                            options[network].urls.length;
                    } else {
                        posts_to_load_count += options[network].limit;
                    }
                }
            });
        }
        calculatePostsToLoadCount();

        function fireCallback() {
            var fire = !0;
            if (fire && options.callback) {
                options.callback();
            }
        }
        var Utility = {
            request: function(url, callback) {
                $.ajax({
                    url: url,
                    dataType: "jsonp",
                    success: callback
                });
            },
            get_request: function(url, callback) {
                $.get(url, callback, "json");
            },
            wrapLinks: function(string, social_network) {
                var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi;
                if (social_network === "google-plus") {
                    string = string.replace(
                        /(@|#)([a-z0-9_]+['])/gi,
                        Utility.wrapGoogleplusTagTemplate
                    );
                } else {
                    string = string.replace(exp, Utility.wrapLinkTemplate);
                }
                return string;
            },
            wrapLinkTemplate: function(string) {
                return (
                    '<a target="_blank" href="' +
                    string +
                    '">' +
                    string +
                    "</a>"
                );
            },
            wrapGoogleplusTagTemplate: function(string) {
                return (
                    '<a target="_blank" href="https://plus.google.com/s/' +
                    string +
                    '" >' +
                    string +
                    "</a>"
                );
            },
            shorten: function(string) {
                string = $.trim(string);
                if (string.length > options.length) {
                    return (
                        jQuery
                            .trim(string)
                            .substring(0, options.length)
                            .split(" ")
                            .slice(0, -1)
                            .join(" ") + "..."
                    );
                } else {
                    return string;
                }
            },
            stripHTML: function(string) {
                if (typeof string === "undefined" || string === null) {
                    return "";
                }
                return string.replace(/(<([^>]+)>)|nbsp;|\s{2,}|/gi, "");
            }
        };

        function SocialFeedPost(social_network, data) {
            this.content = data;
            this.content.social_network = social_network;
            this.content.attachment =
                this.content.attachment === undefined
                    ? ""
                    : this.content.attachment;
            this.content.time_ago = data.dt_create
                .locale(options.date_locale)
                .fromNow();
            this.content.date = data.dt_create
                .locale(options.date_locale)
                .format(options.date_format);
            this.content.dt_create = this.content.dt_create.valueOf();
            this.content.text = Utility.wrapLinks(
                Utility.shorten(data.message + " " + data.description),
                data.social_network
            );
            this.content.moderation_passed = options.moderation
                ? options.moderation(this.content)
                : !0;
            Feed[social_network].posts.push(this);
        }
        SocialFeedPost.prototype = {
            render: function() {
                var rendered_html = Feed.template(this.content);
                var data = this.content;
                if (
                    $(container).children("[social-feed-id=" + data.id + "]")
                        .length !== 0
                ) {
                    return !1;
                }
                if ($(container).children().length === 0) {
                    $(container).append(rendered_html);
                } else {
                    var i = 0,
                        insert_index = -1;
                    $.each($(container).children(), function() {
                        if ($(this).attr("dt-create") < data.dt_create) {
                            insert_index = i;
                            return !1;
                        }
                        i++;
                    });
                    $(container).append(rendered_html);
                    if (insert_index >= 0) {
                        insert_index++;
                        var before = $(container).children(
                                "div:nth-child(" + insert_index + ")"
                            ),
                            current = $(container).children("div:last-child");
                        $(current).insertBefore(before);
                    }
                }
                if (options.media_min_width) {
                    var query =
                        "[social-feed-id=" + data.id + "] img.attachment";
                    var image = $(query);
                    var height,
                        width = "";
                    var img = new Image();
                    var imgSrc = image.attr("src");
                    $(img)
                        .on("load", function() {
                            if (img.width < options.media_min_width) {
                                image.hide();
                            }
                            delete img;
                        })
                        .on("error", function() {
                            image.hide();
                        })
                        .attr({
                            src: imgSrc
                        });
                }
                loaded_post_count++;
                if (loaded_post_count == posts_to_load_count) {
                    fireCallback();
                }
            }
        };
        var Feed = {
            template: !1,
            init: function() {
                Feed.getTemplate(function() {
                    social_networks.forEach(function(network) {
                        if (options[network]) {
                            if (options[network].accounts) {
                                options[network].accounts.forEach(function(
                                    account
                                ) {
                                    Feed[network].getData(account);
                                });
                            } else if (options[network].urls) {
                                options[network].urls.forEach(function(url) {
                                    Feed[network].getData(url);
                                });
                            } else {
                                Feed[network].getData();
                            }
                        }
                    });
                });
            },
            getTemplate: function(callback) {
                if (Feed.template) return callback();
                else {
                    if (options.template_html) {
                        Feed.template = doT.template(options.template_html);
                        return callback();
                    } else {
                        $.get(options.template, function(template_html) {
                            Feed.template = doT.template(template_html);
                            return callback();
                        });
                    }
                }
            },
            twitter: {
                posts: [],
                loaded: !1,
                api: "http://api.tweecool.com/",
                getData: function(account) {
                    var cb = new Codebird();
                    cb.setConsumerKey(
                        options.twitter.consumer_key,
                        options.twitter.consumer_secret
                    );
                    if (options.twitter.proxy !== undefined) {
                        cb.setProxy(options.twitter.proxy);
                    }
                    switch (account[0]) {
                        case "@":
                            var userid = account.substr(1);
                            cb.__call(
                                "statuses_userTimeline",
                                {
                                    id: userid,
                                    count: options.twitter.limit,
                                    tweet_mode:
                                        typeof options.twitter.tweet_mode ===
                                        "undefined"
                                            ? "compatibility"
                                            : options.twitter.tweet_mode
                                },
                                Feed.twitter.utility.getPosts,
                                !0
                            );
                            break;
                        case "#":
                            var hashtag = account.substr(1);
                            cb.__call(
                                "search_tweets",
                                {
                                    q: hashtag,
                                    count: options.twitter.limit,
                                    tweet_mode:
                                        typeof options.twitter.tweet_mode ===
                                        "undefined"
                                            ? "compatibility"
                                            : options.twitter.tweet_mode
                                },
                                function(reply) {
                                    Feed.twitter.utility.getPosts(
                                        reply.statuses
                                    );
                                },
                                !0
                            );
                            break;
                        default:
                    }
                },
                utility: {
                    getPosts: function(json) {
                        if (json) {
                            $.each(json, function() {
                                var element = this;
                                var post = new SocialFeedPost(
                                    "twitter",
                                    Feed.twitter.utility.unifyPostData(element)
                                );
                                post.render();
                            });
                        }
                    },
                    unifyPostData: function(element) {
                        var post = {};
                        if (element.id) {
                            post.id = element.id_str;
                            post.dt_create = moment(
                                element.created_at,
                                "dd MMM DD HH:mm:ss ZZ YYYY"
                            );
                            post.author_link =
                                "http://twitter.com/" +
                                element.user.screen_name;
                            post.author_picture =
                                element.user.profile_image_url_https;
                            post.post_url =
                                post.author_link + "/status/" + element.id_str;
                            post.author_name = element.user.name;
                            post.message =
                                typeof element.text === "undefined"
                                    ? element.full_text.substr(
                                          element.display_text_range[0],
                                          element.display_text_range[1]
                                      )
                                    : element.text;
                            post.description = "";
                            post.link =
                                "http://twitter.com/" +
                                element.user.screen_name +
                                "/status/" +
                                element.id_str;
                            if (options.show_media === !0) {
                                if (
                                    element.entities.media &&
                                    element.entities.media.length > 0
                                ) {
                                    var image_url =
                                        element.entities.media[0]
                                            .media_url_https;
                                    if (image_url) {
                                        post.attachment =
                                            '<img class="attachment" src="' +
                                            image_url +
                                            '" />';
                                    }
                                }
                            }
                        }
                        return post;
                    }
                }
            },
            facebook: {
                posts: [],
                graph: "https://graph.facebook.com/",
                loaded: !1,
                getData: function(account) {
                    var proceed = function(request_url) {
                        Utility.request(
                            request_url,
                            Feed.facebook.utility.getPosts
                        );
                    };
                    var fields =
                        "?fields=id,from,name,message,created_time,story,description,link";
                    fields +=
                        options.show_media === !0 ? ",picture,object_id" : "";
                    var request_url,
                        limit = "&limit=" + options.facebook.limit,
                        query_extention =
                            "&access_token=" +
                            options.facebook.access_token +
                            "&callback=?";
                    switch (account[0]) {
                        case "@":
                            var username = account.substr(1);
                            Feed.facebook.utility.getUserId(username, function(
                                userdata
                            ) {
                                if (userdata.id !== "") {
                                    request_url =
                                        Feed.facebook.graph +
                                        "v2.12/" +
                                        userdata.id +
                                        "/posts" +
                                        fields +
                                        limit +
                                        query_extention;
                                    proceed(request_url);
                                }
                            });
                            break;
                        case "!":
                            var page = account.substr(1);
                            request_url =
                                Feed.facebook.graph +
                                "v2.12/" +
                                page +
                                "/feed" +
                                fields +
                                limit +
                                query_extention;
                            proceed(request_url);
                            break;
                        default:
                            proceed(request_url);
                    }
                },
                utility: {
                    getUserId: function(username, callback) {
                        var query_extention =
                            "&access_token=" +
                            options.facebook.access_token +
                            "&callback=?";
                        var url =
                            "https://graph.facebook.com/" +
                            username +
                            "?" +
                            query_extention;
                        var result = "";
                        $.get(url, callback, "json");
                    },
                    prepareAttachment: function(element) {
                        var image_url = element.picture;
                        if (image_url.indexOf("_b.") !== -1) {
                        } else if (image_url.indexOf("safe_image.php") !== -1) {
                            image_url = Feed.facebook.utility.getExternalImageURL(
                                image_url,
                                "url"
                            );
                        } else if (
                            image_url.indexOf("app_full_proxy.php") !== -1
                        ) {
                            image_url = Feed.facebook.utility.getExternalImageURL(
                                image_url,
                                "src"
                            );
                        } else if (element.object_id) {
                            image_url =
                                Feed.facebook.graph +
                                element.object_id +
                                "/picture/?type=normal";
                        }
                        return (
                            '<img class="attachment" src="' + image_url + '" />'
                        );
                    },
                    getExternalImageURL: function(image_url, parameter) {
                        image_url = decodeURIComponent(image_url).split(
                            parameter + "="
                        )[1];
                        if (image_url.indexOf("fbcdn-sphotos") === -1) {
                            return image_url.split("&")[0];
                        } else {
                            return image_url;
                        }
                    },
                    getPosts: function(json) {
                        if (json.data) {
                            json.data.forEach(function(element) {
                                var post = new SocialFeedPost(
                                    "facebook",
                                    Feed.facebook.utility.unifyPostData(element)
                                );
                                post.render();
                            });
                        }
                    },
                    unifyPostData: function(element) {
                        var post = {},
                            text = element.message
                                ? element.message
                                : element.story;
                        post.id = element.id;
                        post.dt_create = moment(element.created_time);
                        post.author_link =
                            "http://facebook.com/" + element.from.id;
                        post.author_picture =
                            Feed.facebook.graph + element.from.id + "/picture";
                        post.author_name = element.from.name;
                        post.name = element.name || "";
                        post.message = text ? text : "";
                        post.description = element.description
                            ? element.description
                            : "";
                        post.link = element.link
                            ? element.link
                            : "http://facebook.com/" + element.from.id;
                        if (options.show_media === !0) {
                            if (element.picture) {
                                var attachment = Feed.facebook.utility.prepareAttachment(
                                    element
                                );
                                if (attachment) {
                                    post.attachment = attachment;
                                }
                            }
                        }
                        return post;
                    }
                }
            },
            google: {
                posts: [],
                loaded: !1,
                api: "https://www.googleapis.com/plus/v1/",
                getData: function(account) {
                    var request_url;
                    switch (account[0]) {
                        case "#":
                            var hashtag = account.substr(1);
                            request_url =
                                Feed.google.api +
                                "activities?query=" +
                                hashtag +
                                "&key=" +
                                options.google.access_token +
                                "&maxResults=" +
                                options.google.limit;
                            Utility.get_request(
                                request_url,
                                Feed.google.utility.getPosts
                            );
                            break;
                        case "@":
                            var username = account.substr(1);
                            request_url =
                                Feed.google.api +
                                "people/" +
                                username +
                                "/activities/public?key=" +
                                options.google.access_token +
                                "&maxResults=" +
                                options.google.limit;
                            Utility.get_request(
                                request_url,
                                Feed.google.utility.getPosts
                            );
                            break;
                        default:
                    }
                },
                utility: {
                    getPosts: function(json) {
                        if (json.items) {
                            $.each(json.items, function(i) {
                                var post = new SocialFeedPost(
                                    "google",
                                    Feed.google.utility.unifyPostData(
                                        json.items[i]
                                    )
                                );
                                post.render();
                            });
                        }
                    },
                    unifyPostData: function(element) {
                        var post = {};
                        post.id = element.id;
                        post.attachment = "";
                        post.description = "";
                        post.dt_create = moment(element.published);
                        post.author_link = element.actor.url;
                        post.author_picture = element.actor.image.url;
                        post.author_name = element.actor.displayName;
                        if (options.show_media === !0) {
                            if (element.object.attachments) {
                                $.each(element.object.attachments, function() {
                                    var image = "";
                                    if (this.fullImage) {
                                        image = this.fullImage.url;
                                    } else {
                                        if (this.objectType === "album") {
                                            if (
                                                this.thumbnails &&
                                                this.thumbnails.length > 0
                                            ) {
                                                if (this.thumbnails[0].image) {
                                                    image = this.thumbnails[0]
                                                        .image.url;
                                                }
                                            }
                                        }
                                    }
                                    post.attachment =
                                        '<img class="attachment" src="' +
                                        image +
                                        '"/>';
                                });
                            }
                        }
                        post.message = element.title;
                        post.link = element.url;
                        return post;
                    }
                }
            },
            instagram: {
                posts: [],
                api: "https://api.instagram.com/v1/",
                loaded: !1,
                accessType: function() {
                    if (
                        typeof options.instagram.access_token === "undefined" &&
                        typeof options.instagram.client_id === "undefined"
                    ) {
                        console.log(
                            "You need to define a client_id or access_token to authenticate with Instagram's API."
                        );
                        return undefined;
                    }
                    if (options.instagram.access_token) {
                        options.instagram.client_id = undefined;
                    }
                    options.instagram.access_type =
                        typeof options.instagram.client_id === "undefined"
                            ? "access_token"
                            : "client_id";
                    return options.instagram.access_type;
                },
                getData: function(account) {
                    var url;
                    if (this.accessType() !== "undefined") {
                        var authTokenParams =
                            options.instagram.access_type +
                            "=" +
                            options.instagram[options.instagram.access_type];
                    }
                    switch (account[0]) {
                        case "@":
                            var username = account.substr(1);
                            url =
                                Feed.instagram.api +
                                "users/search/?q=" +
                                username +
                                "&" +
                                authTokenParams +
                                "&count=1" +
                                "&callback=?";
                            Utility.request(
                                url,
                                Feed.instagram.utility.getUsers
                            );
                            break;
                        case "#":
                            var hashtag = account.substr(1);
                            url =
                                Feed.instagram.api +
                                "tags/" +
                                hashtag +
                                "/media/recent/?" +
                                authTokenParams +
                                "&" +
                                "count=" +
                                options.instagram.limit +
                                "&callback=?";
                            Utility.request(
                                url,
                                Feed.instagram.utility.getImages
                            );
                            break;
                        case "&":
                            var id = account.substr(1);
                            url =
                                Feed.instagram.api +
                                "users/" +
                                id +
                                "/?" +
                                authTokenParams +
                                "&" +
                                "count=" +
                                options.instagram.limit +
                                "&callback=?";
                            Utility.request(
                                url,
                                Feed.instagram.utility.getUsers
                            );
                        default:
                    }
                },
                utility: {
                    getImages: function(json) {
                        if (json.data) {
                            json.data.forEach(function(element) {
                                var post = new SocialFeedPost(
                                    "instagram",
                                    Feed.instagram.utility.unifyPostData(
                                        element
                                    )
                                );
                                post.render();
                            });
                        }
                    },
                    getUsers: function(json) {
                        if (options.instagram.access_type !== "undefined") {
                            var authTokenParams =
                                options.instagram.access_type +
                                "=" +
                                options.instagram[
                                    options.instagram.access_type
                                ];
                        }
                        if (!jQuery.isArray(json.data)) json.data = [json.data];
                        json.data.forEach(function(user) {
                            var url =
                                Feed.instagram.api +
                                "users/" +
                                user.id +
                                "/media/recent/?" +
                                authTokenParams +
                                "&" +
                                "count=" +
                                options.instagram.limit +
                                "&callback=?";
                            Utility.request(
                                url,
                                Feed.instagram.utility.getImages
                            );
                        });
                    },
                    unifyPostData: function(element) {
                        var post = {};
                        post.id = element.id;
                        post.dt_create = moment(element.created_time * 1000);
                        post.author_link =
                            "http://instagram.com/" + element.user.username;
                        post.author_picture = element.user.profile_picture;
                        post.author_name =
                            element.user.full_name || element.user.username;
                        post.message =
                            element.caption && element.caption
                                ? element.caption.text
                                : "";
                        post.description = "";
                        post.link = element.link;
                        if (options.show_media) {
                            post.attachment =
                                '<img class="attachment" src="' +
                                element.images.standard_resolution.url +
                                "" +
                                '" />';
                        }
                        return post;
                    }
                }
            },
            vk: {
                posts: [],
                loaded: !1,
                base: "http://vk.com/",
                api: "https://api.vk.com/method/",
                user_json_template:
                    "https://api.vk.com/method/" +
                    "users.get?fields=first_name,%20last_name,%20screen_name,%20photo&uid=",
                group_json_template:
                    "https://api.vk.com/method/" +
                    "groups.getById?fields=first_name,%20last_name,%20screen_name,%20photo&gid=",
                getData: function(account) {
                    var request_url;
                    switch (account[0]) {
                        case "@":
                            var username = account.substr(1);
                            request_url =
                                Feed.vk.api +
                                "wall.get?owner_id=" +
                                username +
                                "&filter=" +
                                options.vk.source +
                                "&count=" +
                                options.vk.limit +
                                "&callback=?";
                            Utility.get_request(
                                request_url,
                                Feed.vk.utility.getPosts
                            );
                            break;
                        case "#":
                            var hashtag = account.substr(1);
                            request_url =
                                Feed.vk.api +
                                "newsfeed.search?q=" +
                                hashtag +
                                "&count=" +
                                options.vk.limit +
                                "&callback=?";
                            Utility.get_request(
                                request_url,
                                Feed.vk.utility.getPosts
                            );
                            break;
                        default:
                    }
                },
                utility: {
                    getPosts: function(json) {
                        if (json.response) {
                            $.each(json.response, function() {
                                if (
                                    this != parseInt(this) &&
                                    this.post_type === "post"
                                ) {
                                    var owner_id = this.owner_id
                                            ? this.owner_id
                                            : this.from_id,
                                        vk_wall_owner_url =
                                            owner_id > 0
                                                ? Feed.vk.user_json_template +
                                                  owner_id +
                                                  "&callback=?"
                                                : Feed.vk.group_json_template +
                                                  -1 * owner_id +
                                                  "&callback=?",
                                        element = this;
                                    Utility.get_request(
                                        vk_wall_owner_url,
                                        function(wall_owner) {
                                            Feed.vk.utility.unifyPostData(
                                                wall_owner,
                                                element,
                                                json
                                            );
                                        }
                                    );
                                }
                            });
                        }
                    },
                    unifyPostData: function(wall_owner, element, json) {
                        var post = {};
                        post.id = element.id;
                        post.dt_create = moment.unix(element.date);
                        post.description = " ";
                        post.message = Utility.stripHTML(element.text);
                        if (options.show_media) {
                            if (element.attachment) {
                                if (element.attachment.type === "link")
                                    post.attachment =
                                        '<img class="attachment" src="' +
                                        element.attachment.link.image_src +
                                        '" />';
                                if (element.attachment.type === "video")
                                    post.attachment =
                                        '<img class="attachment" src="' +
                                        element.attachment.video.image_big +
                                        '" />';
                                if (element.attachment.type === "photo")
                                    post.attachment =
                                        '<img class="attachment" src="' +
                                        element.attachment.photo.src_big +
                                        '" />';
                            }
                        }
                        if (element.from_id > 0) {
                            var vk_user_json =
                                Feed.vk.user_json_template +
                                element.from_id +
                                "&callback=?";
                            Utility.get_request(vk_user_json, function(
                                user_json
                            ) {
                                var vk_post = new SocialFeedPost(
                                    "vk",
                                    Feed.vk.utility.getUser(
                                        user_json,
                                        post,
                                        element,
                                        json
                                    )
                                );
                                vk_post.render();
                            });
                        } else {
                            var vk_group_json =
                                Feed.vk.group_json_template +
                                -1 * element.from_id +
                                "&callback=?";
                            Utility.get_request(vk_group_json, function(
                                user_json
                            ) {
                                var vk_post = new SocialFeedPost(
                                    "vk",
                                    Feed.vk.utility.getGroup(
                                        user_json,
                                        post,
                                        element,
                                        json
                                    )
                                );
                                vk_post.render();
                            });
                        }
                    },
                    getUser: function(user_json, post, element, json) {
                        post.author_name =
                            user_json.response[0].first_name +
                            " " +
                            user_json.response[0].last_name;
                        post.author_picture = user_json.response[0].photo;
                        post.author_link =
                            Feed.vk.base + user_json.response[0].screen_name;
                        post.link =
                            Feed.vk.base +
                            user_json.response[0].screen_name +
                            "?w=wall" +
                            element.from_id +
                            "_" +
                            element.id;
                        return post;
                    },
                    getGroup: function(user_json, post, element, json) {
                        post.author_name = user_json.response[0].name;
                        post.author_picture = user_json.response[0].photo;
                        post.author_link =
                            Feed.vk.base + user_json.response[0].screen_name;
                        post.link =
                            Feed.vk.base +
                            user_json.response[0].screen_name +
                            "?w=wall-" +
                            user_json.response[0].gid +
                            "_" +
                            element.id;
                        return post;
                    }
                }
            },
            blogspot: {
                loaded: !1,
                getData: function(account) {
                    var url;
                    switch (account[0]) {
                        case "@":
                            var username = account.substr(1);
                            url =
                                "http://" +
                                username +
                                ".blogspot.com/feeds/posts/default?alt=json-in-script&callback=?";
                            request(url, getPosts);
                            break;
                        default:
                    }
                },
                utility: {
                    getPosts: function(json) {
                        $.each(json.feed.entry, function() {
                            var post = {},
                                element = this;
                            post.id = element.id["$t"].replace(
                                /[^a-z0-9]/gi,
                                ""
                            );
                            post.dt_create = moment(element.published["$t"]);
                            post.author_link = element.author[0].uri["$t"];
                            post.author_picture =
                                "http:" + element.author[0].gd$image.src;
                            post.author_name = element.author[0].name["$t"];
                            post.message =
                                element.title["$t"] +
                                "</br></br>" +
                                stripHTML(element.content["$t"]);
                            post.description = "";
                            post.link = element.link.pop().href;
                            if (options.show_media) {
                                if (element.media$thumbnail) {
                                    post.attachment =
                                        '<img class="attachment" src="' +
                                        element.media$thumbnail.url +
                                        '" />';
                                }
                            }
                            post.render();
                        });
                    }
                }
            },
            pinterest: {
                posts: [],
                loaded: !1,
                apiv1: "https://api.pinterest.com/v1/",
                getData: function(account) {
                    var request_url,
                        limit = "limit=" + options.pinterest.limit,
                        fields =
                            "fields=id,created_at,link,note,creator(url,first_name,last_name,image),image",
                        query_extention =
                            fields +
                            "&access_token=" +
                            options.pinterest.access_token +
                            "&" +
                            limit +
                            "&callback=?";
                    switch (account[0]) {
                        case "@":
                            var username = account.substr(1);
                            if (username === "me") {
                                request_url =
                                    Feed.pinterest.apiv1 +
                                    "me/pins/?" +
                                    query_extention;
                            } else {
                                request_url =
                                    Feed.pinterest.apiv1 +
                                    "boards/" +
                                    username +
                                    "/pins?" +
                                    query_extention;
                            }
                            break;
                        default:
                    }
                    Utility.request(
                        request_url,
                        Feed.pinterest.utility.getPosts
                    );
                },
                utility: {
                    getPosts: function(json) {
                        json.data.forEach(function(element) {
                            var post = new SocialFeedPost(
                                "pinterest",
                                Feed.pinterest.utility.unifyPostData(element)
                            );
                            post.render();
                        });
                    },
                    unifyPostData: function(element) {
                        var post = {};
                        post.id = element.id;
                        post.dt_create = moment(element.created_at);
                        post.author_link = element.creator.url;
                        post.author_picture =
                            element.creator.image["60x60"].url;
                        post.author_name =
                            element.creator.first_name +
                            element.creator.last_name;
                        post.message = element.note;
                        post.description = "";
                        post.social_network = "pinterest";
                        post.link = element.link
                            ? element.link
                            : "https://www.pinterest.com/pin/" + element.id;
                        if (options.show_media) {
                            post.attachment =
                                '<img class="attachment" src="' +
                                element.image.original.url +
                                '" />';
                        }
                        return post;
                    }
                }
            },
            rss: {
                posts: [],
                loaded: !1,
                api: "https://query.yahooapis.com/v1/public/yql?q=",
                datatype: "json",
                getData: function(url) {
                    var limit = options.rss.limit,
                        yql = encodeURIComponent(
                            "select entry FROM feednormalizer where url='" +
                                url +
                                "' AND output='atom_1.0' | truncate(count=" +
                                limit +
                                ")"
                        ),
                        request_url =
                            Feed.rss.api + yql + "&format=json&callback=?";
                    Utility.request(
                        request_url,
                        Feed.rss.utility.getPosts,
                        Feed.rss.datatype
                    );
                },
                utility: {
                    getPosts: function(json) {
                        console.log(json);
                        if (json.query.count > 0) {
                            $.each(json.query.results.feed, function(
                                index,
                                element
                            ) {
                                var post = new SocialFeedPost(
                                    "rss",
                                    Feed.rss.utility.unifyPostData(
                                        index,
                                        element
                                    )
                                );
                                post.render();
                            });
                        }
                    },
                    unifyPostData: function(index, element) {
                        var item = element;
                        if (element.entry !== undefined) {
                            item = element.entry;
                        }
                        var post = {};
                        post.id = '"' + item.id + '"';
                        post.dt_create = moment(
                            item.published,
                            "YYYY-MM-DDTHH:mm:ssZ",
                            "en"
                        );
                        post.author_link = "";
                        post.author_picture = "";
                        post.author_name = "";
                        if (item.creator !== undefined) {
                            post.author_name = item.creator;
                        }
                        post.message = item.title;
                        post.description = "";
                        if (item.summary !== undefined) {
                            post.description = Utility.stripHTML(
                                item.summary.content
                            );
                        }
                        post.social_network = "rss";
                        post.link = item.link.href;
                        if (
                            options.show_media &&
                            item.thumbnail !== undefined
                        ) {
                            post.attachment =
                                '<img class="attachment" src="' +
                                item.thumbnail.url +
                                '" />';
                        }
                        return post;
                    }
                }
            }
        };
        return this.each(function() {
            Feed.init();
            if (options.update_period) {
                setInterval(function() {
                    return Feed.init();
                }, options.update_period);
            }
        });
    };
})(jQuery);
!(function(a) {
    "function" == typeof define && define.amd
        ? define(["jquery"], a)
        : a(
              "object" == typeof exports
                  ? require("jquery")
                  : window.jQuery || window.Zepto
          );
})(function(a) {
    var b,
        c,
        d,
        e,
        f,
        g,
        h = "Close",
        i = "BeforeClose",
        j = "AfterClose",
        k = "BeforeAppend",
        l = "MarkupParse",
        m = "Open",
        n = "Change",
        o = "mfp",
        p = "." + o,
        q = "mfp-ready",
        r = "mfp-removing",
        s = "mfp-prevent-close",
        t = function() {},
        u = !!window.jQuery,
        v = a(window),
        w = function(a, c) {
            b.ev.on(o + a + p, c);
        },
        x = function(b, c, d, e) {
            var f = document.createElement("div");
            return (
                (f.className = "mfp-" + b),
                d && (f.innerHTML = d),
                e ? c && c.appendChild(f) : ((f = a(f)), c && f.appendTo(c)),
                f
            );
        },
        y = function(c, d) {
            b.ev.triggerHandler(o + c, d),
                b.st.callbacks &&
                    ((c = c.charAt(0).toLowerCase() + c.slice(1)),
                    b.st.callbacks[c] &&
                        b.st.callbacks[c].apply(b, a.isArray(d) ? d : [d]));
        },
        z = function(c) {
            return (
                (c === g && b.currTemplate.closeBtn) ||
                    ((b.currTemplate.closeBtn = a(
                        b.st.closeMarkup.replace("%title%", b.st.tClose)
                    )),
                    (g = c)),
                b.currTemplate.closeBtn
            );
        },
        A = function() {
            a.magnificPopup.instance ||
                ((b = new t()), b.init(), (a.magnificPopup.instance = b));
        },
        B = function() {
            var a = document.createElement("p").style,
                b = ["ms", "O", "Moz", "Webkit"];
            if (void 0 !== a.transition) return !0;
            for (; b.length; ) if (b.pop() + "Transition" in a) return !0;
            return !1;
        };
    (t.prototype = {
        constructor: t,
        init: function() {
            var c = navigator.appVersion;
            (b.isLowIE = b.isIE8 = document.all && !document.addEventListener),
                (b.isAndroid = /android/gi.test(c)),
                (b.isIOS = /iphone|ipad|ipod/gi.test(c)),
                (b.supportsTransition = B()),
                (b.probablyMobile =
                    b.isAndroid ||
                    b.isIOS ||
                    /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(
                        navigator.userAgent
                    )),
                (d = a(document)),
                (b.popupsCache = {});
        },
        open: function(c) {
            var e;
            if (c.isObj === !1) {
                (b.items = c.items.toArray()), (b.index = 0);
                var g,
                    h = c.items;
                for (e = 0; e < h.length; e++)
                    if (
                        ((g = h[e]), g.parsed && (g = g.el[0]), g === c.el[0])
                    ) {
                        b.index = e;
                        break;
                    }
            } else
                (b.items = a.isArray(c.items) ? c.items : [c.items]),
                    (b.index = c.index || 0);
            if (b.isOpen) return void b.updateItemHTML();
            (b.types = []),
                (f = ""),
                c.mainEl && c.mainEl.length
                    ? (b.ev = c.mainEl.eq(0))
                    : (b.ev = d),
                c.key
                    ? (b.popupsCache[c.key] || (b.popupsCache[c.key] = {}),
                      (b.currTemplate = b.popupsCache[c.key]))
                    : (b.currTemplate = {}),
                (b.st = a.extend(!0, {}, a.magnificPopup.defaults, c)),
                (b.fixedContentPos =
                    "auto" === b.st.fixedContentPos
                        ? !b.probablyMobile
                        : b.st.fixedContentPos),
                b.st.modal &&
                    ((b.st.closeOnContentClick = !1),
                    (b.st.closeOnBgClick = !1),
                    (b.st.showCloseBtn = !1),
                    (b.st.enableEscapeKey = !1)),
                b.bgOverlay ||
                    ((b.bgOverlay = x("bg").on("click" + p, function() {
                        b.close();
                    })),
                    (b.wrap = x("wrap")
                        .attr("tabindex", -1)
                        .on("click" + p, function(a) {
                            b._checkIfClose(a.target) && b.close();
                        })),
                    (b.container = x("container", b.wrap))),
                (b.contentContainer = x("content")),
                b.st.preloader &&
                    (b.preloader = x("preloader", b.container, b.st.tLoading));
            var i = a.magnificPopup.modules;
            for (e = 0; e < i.length; e++) {
                var j = i[e];
                (j = j.charAt(0).toUpperCase() + j.slice(1)),
                    b["init" + j].call(b);
            }
            y("BeforeOpen"),
                b.st.showCloseBtn &&
                    (b.st.closeBtnInside
                        ? (w(l, function(a, b, c, d) {
                              c.close_replaceWith = z(d.type);
                          }),
                          (f += " mfp-close-btn-in"))
                        : b.wrap.append(z())),
                b.st.alignTop && (f += " mfp-align-top"),
                b.fixedContentPos
                    ? b.wrap.css({
                          overflow: b.st.overflowY,
                          overflowX: "hidden",
                          overflowY: b.st.overflowY
                      })
                    : b.wrap.css({
                          top: v.scrollTop(),
                          position: "absolute"
                      }),
                (b.st.fixedBgPos === !1 ||
                    ("auto" === b.st.fixedBgPos && !b.fixedContentPos)) &&
                    b.bgOverlay.css({
                        height: d.height(),
                        position: "absolute"
                    }),
                b.st.enableEscapeKey &&
                    d.on("keyup" + p, function(a) {
                        27 === a.keyCode && b.close();
                    }),
                v.on("resize" + p, function() {
                    b.updateSize();
                }),
                b.st.closeOnContentClick || (f += " mfp-auto-cursor"),
                f && b.wrap.addClass(f);
            var k = (b.wH = v.height()),
                n = {};
            if (b.fixedContentPos && b._hasScrollBar(k)) {
                var o = b._getScrollbarSize();
                o && (n.marginRight = o);
            }
            b.fixedContentPos &&
                (b.isIE7
                    ? a("body, html").css("overflow", "hidden")
                    : (n.overflow = "hidden"));
            var r = b.st.mainClass;
            return (
                b.isIE7 && (r += " mfp-ie7"),
                r && b._addClassToMFP(r),
                b.updateItemHTML(),
                y("BuildControls"),
                a("html").css(n),
                b.bgOverlay
                    .add(b.wrap)
                    .prependTo(b.st.prependTo || a(document.body)),
                (b._lastFocusedEl = document.activeElement),
                setTimeout(function() {
                    b.content
                        ? (b._addClassToMFP(q), b._setFocus())
                        : b.bgOverlay.addClass(q),
                        d.on("focusin" + p, b._onFocusIn);
                }, 16),
                (b.isOpen = !0),
                b.updateSize(k),
                y(m),
                c
            );
        },
        close: function() {
            b.isOpen &&
                (y(i),
                (b.isOpen = !1),
                b.st.removalDelay && !b.isLowIE && b.supportsTransition
                    ? (b._addClassToMFP(r),
                      setTimeout(function() {
                          b._close();
                      }, b.st.removalDelay))
                    : b._close());
        },
        _close: function() {
            y(h);
            var c = r + " " + q + " ";
            if (
                (b.bgOverlay.detach(),
                b.wrap.detach(),
                b.container.empty(),
                b.st.mainClass && (c += b.st.mainClass + " "),
                b._removeClassFromMFP(c),
                b.fixedContentPos)
            ) {
                var e = {
                    marginRight: ""
                };
                b.isIE7
                    ? a("body, html").css("overflow", "")
                    : (e.overflow = ""),
                    a("html").css(e);
            }
            d.off("keyup" + p + " focusin" + p),
                b.ev.off(p),
                b.wrap.attr("class", "mfp-wrap").removeAttr("style"),
                b.bgOverlay.attr("class", "mfp-bg"),
                b.container.attr("class", "mfp-container"),
                !b.st.showCloseBtn ||
                    (b.st.closeBtnInside &&
                        b.currTemplate[b.currItem.type] !== !0) ||
                    (b.currTemplate.closeBtn &&
                        b.currTemplate.closeBtn.detach()),
                b.st.autoFocusLast &&
                    b._lastFocusedEl &&
                    a(b._lastFocusedEl).focus(),
                (b.currItem = null),
                (b.content = null),
                (b.currTemplate = null),
                (b.prevHeight = 0),
                y(j);
        },
        updateSize: function(a) {
            if (b.isIOS) {
                var c =
                        document.documentElement.clientWidth /
                        window.innerWidth,
                    d = window.innerHeight * c;
                b.wrap.css("height", d), (b.wH = d);
            } else b.wH = a || v.height();
            b.fixedContentPos || b.wrap.css("height", b.wH), y("Resize");
        },
        updateItemHTML: function() {
            var c = b.items[b.index];
            b.contentContainer.detach(),
                b.content && b.content.detach(),
                c.parsed || (c = b.parseEl(b.index));
            var d = c.type;
            if (
                (y("BeforeChange", [b.currItem ? b.currItem.type : "", d]),
                (b.currItem = c),
                !b.currTemplate[d])
            ) {
                var f = b.st[d] ? b.st[d].markup : !1;
                y("FirstMarkupParse", f),
                    f ? (b.currTemplate[d] = a(f)) : (b.currTemplate[d] = !0);
            }
            e &&
                e !== c.type &&
                b.container.removeClass("mfp-" + e + "-holder");
            var g = b["get" + d.charAt(0).toUpperCase() + d.slice(1)](
                c,
                b.currTemplate[d]
            );
            b.appendContent(g, d),
                (c.preloaded = !0),
                y(n, c),
                (e = c.type),
                b.container.prepend(b.contentContainer),
                y("AfterChange");
        },
        appendContent: function(a, c) {
            (b.content = a),
                a
                    ? b.st.showCloseBtn &&
                      b.st.closeBtnInside &&
                      b.currTemplate[c] === !0
                        ? b.content.find(".mfp-close").length ||
                          b.content.append(z())
                        : (b.content = a)
                    : (b.content = ""),
                y(k),
                b.container.addClass("mfp-" + c + "-holder"),
                b.contentContainer.append(b.content);
        },
        parseEl: function(c) {
            var d,
                e = b.items[c];
            if (
                (e.tagName
                    ? (e = {
                          el: a(e)
                      })
                    : ((d = e.type),
                      (e = {
                          data: e,
                          src: e.src
                      })),
                e.el)
            ) {
                for (var f = b.types, g = 0; g < f.length; g++)
                    if (e.el.hasClass("mfp-" + f[g])) {
                        d = f[g];
                        break;
                    }
                (e.src = e.el.attr("data-mfp-src")),
                    e.src || (e.src = e.el.attr("href"));
            }
            return (
                (e.type = d || b.st.type || "inline"),
                (e.index = c),
                (e.parsed = !0),
                (b.items[c] = e),
                y("ElementParse", e),
                b.items[c]
            );
        },
        addGroup: function(a, c) {
            var d = function(d) {
                (d.mfpEl = this), b._openClick(d, a, c);
            };
            c || (c = {});
            var e = "click.magnificPopup";
            (c.mainEl = a),
                c.items
                    ? ((c.isObj = !0), a.off(e).on(e, d))
                    : ((c.isObj = !1),
                      c.delegate
                          ? a.off(e).on(e, c.delegate, d)
                          : ((c.items = a), a.off(e).on(e, d)));
        },
        _openClick: function(c, d, e) {
            var f =
                void 0 !== e.midClick
                    ? e.midClick
                    : a.magnificPopup.defaults.midClick;
            if (
                f ||
                !(
                    2 === c.which ||
                    c.ctrlKey ||
                    c.metaKey ||
                    c.altKey ||
                    c.shiftKey
                )
            ) {
                var g =
                    void 0 !== e.disableOn
                        ? e.disableOn
                        : a.magnificPopup.defaults.disableOn;
                if (g)
                    if (a.isFunction(g)) {
                        if (!g.call(b)) return !0;
                    } else if (v.width() < g) return !0;
                c.type && (c.preventDefault(), b.isOpen && c.stopPropagation()),
                    (e.el = a(c.mfpEl)),
                    e.delegate && (e.items = d.find(e.delegate)),
                    b.open(e);
            }
        },
        updateStatus: function(a, d) {
            if (b.preloader) {
                c !== a && b.container.removeClass("mfp-s-" + c),
                    d || "loading" !== a || (d = b.st.tLoading);
                var e = {
                    status: a,
                    text: d
                };
                y("UpdateStatus", e),
                    (a = e.status),
                    (d = e.text),
                    b.preloader.html(d),
                    b.preloader.find("a").on("click", function(a) {
                        a.stopImmediatePropagation();
                    }),
                    b.container.addClass("mfp-s-" + a),
                    (c = a);
            }
        },
        _checkIfClose: function(c) {
            if (!a(c).hasClass(s)) {
                var d = b.st.closeOnContentClick,
                    e = b.st.closeOnBgClick;
                if (d && e) return !0;
                if (
                    !b.content ||
                    a(c).hasClass("mfp-close") ||
                    (b.preloader && c === b.preloader[0])
                )
                    return !0;
                if (c === b.content[0] || a.contains(b.content[0], c)) {
                    if (d) return !0;
                } else if (e && a.contains(document, c)) return !0;
                return !1;
            }
        },
        _addClassToMFP: function(a) {
            b.bgOverlay.addClass(a), b.wrap.addClass(a);
        },
        _removeClassFromMFP: function(a) {
            this.bgOverlay.removeClass(a), b.wrap.removeClass(a);
        },
        _hasScrollBar: function(a) {
            return (
                (b.isIE7 ? d.height() : document.body.scrollHeight) >
                (a || v.height())
            );
        },
        _setFocus: function() {
            (b.st.focus ? b.content.find(b.st.focus).eq(0) : b.wrap).focus();
        },
        _onFocusIn: function(c) {
            return c.target === b.wrap[0] || a.contains(b.wrap[0], c.target)
                ? void 0
                : (b._setFocus(), !1);
        },
        _parseMarkup: function(b, c, d) {
            var e;
            d.data && (c = a.extend(d.data, c)),
                y(l, [b, c, d]),
                a.each(c, function(c, d) {
                    if (void 0 === d || d === !1) return !0;
                    if (((e = c.split("_")), e.length > 1)) {
                        var f = b.find(p + "-" + e[0]);
                        if (f.length > 0) {
                            var g = e[1];
                            "replaceWith" === g
                                ? f[0] !== d[0] && f.replaceWith(d)
                                : "img" === g
                                ? f.is("img")
                                    ? f.attr("src", d)
                                    : f.replaceWith(
                                          a("<img>")
                                              .attr("src", d)
                                              .attr("class", f.attr("class"))
                                      )
                                : f.attr(e[1], d);
                        }
                    } else b.find(p + "-" + c).html(d);
                });
        },
        _getScrollbarSize: function() {
            if (void 0 === b.scrollbarSize) {
                var a = document.createElement("div");
                (a.style.cssText =
                    "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;"),
                    document.body.appendChild(a),
                    (b.scrollbarSize = a.offsetWidth - a.clientWidth),
                    document.body.removeChild(a);
            }
            return b.scrollbarSize;
        }
    }),
        (a.magnificPopup = {
            instance: null,
            proto: t.prototype,
            modules: [],
            open: function(b, c) {
                return (
                    A(),
                    (b = b ? a.extend(!0, {}, b) : {}),
                    (b.isObj = !0),
                    (b.index = c || 0),
                    this.instance.open(b)
                );
            },
            close: function() {
                return (
                    a.magnificPopup.instance && a.magnificPopup.instance.close()
                );
            },
            registerModule: function(b, c) {
                c.options && (a.magnificPopup.defaults[b] = c.options),
                    a.extend(this.proto, c.proto),
                    this.modules.push(b);
            },
            defaults: {
                disableOn: 0,
                key: null,
                midClick: !1,
                mainClass: "",
                preloader: !0,
                focus: "",
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !0,
                enableEscapeKey: !0,
                modal: !1,
                alignTop: !1,
                removalDelay: 0,
                prependTo: null,
                fixedContentPos: "auto",
                fixedBgPos: "auto",
                overflowY: "auto",
                closeMarkup:
                    '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
                tClose: "Close (Esc)",
                tLoading: "Loading...",
                autoFocusLast: !0
            }
        }),
        (a.fn.magnificPopup = function(c) {
            A();
            var d = a(this);
            if ("string" == typeof c)
                if ("open" === c) {
                    var e,
                        f = u ? d.data("magnificPopup") : d[0].magnificPopup,
                        g = parseInt(arguments[1], 10) || 0;
                    f.items
                        ? (e = f.items[g])
                        : ((e = d),
                          f.delegate && (e = e.find(f.delegate)),
                          (e = e.eq(g))),
                        b._openClick(
                            {
                                mfpEl: e
                            },
                            d,
                            f
                        );
                } else
                    b.isOpen &&
                        b[c].apply(b, Array.prototype.slice.call(arguments, 1));
            else
                (c = a.extend(!0, {}, c)),
                    u ? d.data("magnificPopup", c) : (d[0].magnificPopup = c),
                    b.addGroup(d, c);
            return d;
        });
    var C,
        D,
        E,
        F = "inline",
        G = function() {
            E && (D.after(E.addClass(C)).detach(), (E = null));
        };
    a.magnificPopup.registerModule(F, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function() {
                b.types.push(F),
                    w(h + "." + F, function() {
                        G();
                    });
            },
            getInline: function(c, d) {
                if ((G(), c.src)) {
                    var e = b.st.inline,
                        f = a(c.src);
                    if (f.length) {
                        var g = f[0].parentNode;
                        g &&
                            g.tagName &&
                            (D ||
                                ((C = e.hiddenClass),
                                (D = x(C)),
                                (C = "mfp-" + C)),
                            (E = f
                                .after(D)
                                .detach()
                                .removeClass(C))),
                            b.updateStatus("ready");
                    } else
                        b.updateStatus("error", e.tNotFound), (f = a("<div>"));
                    return (c.inlineElement = f), f;
                }
                return b.updateStatus("ready"), b._parseMarkup(d, {}, c), d;
            }
        }
    });
    var H,
        I = "ajax",
        J = function() {
            H && a(document.body).removeClass(H);
        },
        K = function() {
            J(), b.req && b.req.abort();
        };
    a.magnificPopup.registerModule(I, {
        options: {
            settings: null,
            cursor: "mfp-ajax-cur",
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },
        proto: {
            initAjax: function() {
                b.types.push(I),
                    (H = b.st.ajax.cursor),
                    w(h + "." + I, K),
                    w("BeforeChange." + I, K);
            },
            getAjax: function(c) {
                H && a(document.body).addClass(H), b.updateStatus("loading");
                var d = a.extend(
                    {
                        url: c.src,
                        success: function(d, e, f) {
                            var g = {
                                data: d,
                                xhr: f
                            };
                            y("ParseAjax", g),
                                b.appendContent(a(g.data), I),
                                (c.finished = !0),
                                J(),
                                b._setFocus(),
                                setTimeout(function() {
                                    b.wrap.addClass(q);
                                }, 16),
                                b.updateStatus("ready"),
                                y("AjaxContentAdded");
                        },
                        error: function() {
                            J(),
                                (c.finished = c.loadError = !0),
                                b.updateStatus(
                                    "error",
                                    b.st.ajax.tError.replace("%url%", c.src)
                                );
                        }
                    },
                    b.st.ajax.settings
                );
                return (b.req = a.ajax(d)), "";
            }
        }
    });
    var L,
        M = function(c) {
            if (c.data && void 0 !== c.data.title) return c.data.title;
            var d = b.st.image.titleSrc;
            if (d) {
                if (a.isFunction(d)) return d.call(b, c);
                if (c.el) return c.el.attr(d) || "";
            }
            return "";
        };
    a.magnificPopup.registerModule("image", {
        options: {
            markup:
                '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
            cursor: "mfp-zoom-out-cur",
            titleSrc: "title",
            verticalFit: !0,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },
        proto: {
            initImage: function() {
                var c = b.st.image,
                    d = ".image";
                b.types.push("image"),
                    w(m + d, function() {
                        "image" === b.currItem.type &&
                            c.cursor &&
                            a(document.body).addClass(c.cursor);
                    }),
                    w(h + d, function() {
                        c.cursor && a(document.body).removeClass(c.cursor),
                            v.off("resize" + p);
                    }),
                    w("Resize" + d, b.resizeImage),
                    b.isLowIE && w("AfterChange", b.resizeImage);
            },
            resizeImage: function() {
                var a = b.currItem;
                if (a && a.img && b.st.image.verticalFit) {
                    var c = 0;
                    b.isLowIE &&
                        (c =
                            parseInt(a.img.css("padding-top"), 10) +
                            parseInt(a.img.css("padding-bottom"), 10)),
                        a.img.css("max-height", b.wH - c);
                }
            },
            _onImageHasSize: function(a) {
                a.img &&
                    ((a.hasSize = !0),
                    L && clearInterval(L),
                    (a.isCheckingImgSize = !1),
                    y("ImageHasSize", a),
                    a.imgHidden &&
                        (b.content && b.content.removeClass("mfp-loading"),
                        (a.imgHidden = !1)));
            },
            findImageSize: function(a) {
                var c = 0,
                    d = a.img[0],
                    e = function(f) {
                        L && clearInterval(L),
                            (L = setInterval(function() {
                                return d.naturalWidth > 0
                                    ? void b._onImageHasSize(a)
                                    : (c > 200 && clearInterval(L),
                                      c++,
                                      void (3 === c
                                          ? e(10)
                                          : 40 === c
                                          ? e(50)
                                          : 100 === c && e(500)));
                            }, f));
                    };
                e(1);
            },
            getImage: function(c, d) {
                var e = 0,
                    f = function() {
                        c &&
                            (c.img[0].complete
                                ? (c.img.off(".mfploader"),
                                  c === b.currItem &&
                                      (b._onImageHasSize(c),
                                      b.updateStatus("ready")),
                                  (c.hasSize = !0),
                                  (c.loaded = !0),
                                  y("ImageLoadComplete"))
                                : (e++, 200 > e ? setTimeout(f, 100) : g()));
                    },
                    g = function() {
                        c &&
                            (c.img.off(".mfploader"),
                            c === b.currItem &&
                                (b._onImageHasSize(c),
                                b.updateStatus(
                                    "error",
                                    h.tError.replace("%url%", c.src)
                                )),
                            (c.hasSize = !0),
                            (c.loaded = !0),
                            (c.loadError = !0));
                    },
                    h = b.st.image,
                    i = d.find(".mfp-img");
                if (i.length) {
                    var j = document.createElement("img");
                    (j.className = "mfp-img"),
                        c.el &&
                            c.el.find("img").length &&
                            (j.alt = c.el.find("img").attr("alt")),
                        (c.img = a(j)
                            .on("load.mfploader", f)
                            .on("error.mfploader", g)),
                        (j.src = c.src),
                        i.is("img") && (c.img = c.img.clone()),
                        (j = c.img[0]),
                        j.naturalWidth > 0
                            ? (c.hasSize = !0)
                            : j.width || (c.hasSize = !1);
                }
                return (
                    b._parseMarkup(
                        d,
                        {
                            title: M(c),
                            img_replaceWith: c.img
                        },
                        c
                    ),
                    b.resizeImage(),
                    c.hasSize
                        ? (L && clearInterval(L),
                          c.loadError
                              ? (d.addClass("mfp-loading"),
                                b.updateStatus(
                                    "error",
                                    h.tError.replace("%url%", c.src)
                                ))
                              : (d.removeClass("mfp-loading"),
                                b.updateStatus("ready")),
                          d)
                        : (b.updateStatus("loading"),
                          (c.loading = !0),
                          c.hasSize ||
                              ((c.imgHidden = !0),
                              d.addClass("mfp-loading"),
                              b.findImageSize(c)),
                          d)
                );
            }
        }
    });
    var N,
        O = function() {
            return (
                void 0 === N &&
                    (N =
                        void 0 !==
                        document.createElement("p").style.MozTransform),
                N
            );
        };
    a.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function(a) {
                return a.is("img") ? a : a.find("img");
            }
        },
        proto: {
            initZoom: function() {
                var a,
                    c = b.st.zoom,
                    d = ".zoom";
                if (c.enabled && b.supportsTransition) {
                    var e,
                        f,
                        g = c.duration,
                        j = function(a) {
                            var b = a
                                    .clone()
                                    .removeAttr("style")
                                    .removeAttr("class")
                                    .addClass("mfp-animated-image"),
                                d = "all " + c.duration / 1e3 + "s " + c.easing,
                                e = {
                                    position: "fixed",
                                    zIndex: 9999,
                                    left: 0,
                                    top: 0,
                                    "-webkit-backface-visibility": "hidden"
                                },
                                f = "transition";
                            return (
                                (e["-webkit-" + f] = e["-moz-" + f] = e[
                                    "-o-" + f
                                ] = e[f] = d),
                                b.css(e),
                                b
                            );
                        },
                        k = function() {
                            b.content.css("visibility", "visible");
                        };
                    w("BuildControls" + d, function() {
                        if (b._allowZoom()) {
                            if (
                                (clearTimeout(e),
                                b.content.css("visibility", "hidden"),
                                (a = b._getItemToZoom()),
                                !a)
                            )
                                return void k();
                            (f = j(a)),
                                f.css(b._getOffset()),
                                b.wrap.append(f),
                                (e = setTimeout(function() {
                                    f.css(b._getOffset(!0)),
                                        (e = setTimeout(function() {
                                            k(),
                                                setTimeout(function() {
                                                    f.remove(),
                                                        (a = f = null),
                                                        y("ZoomAnimationEnded");
                                                }, 16);
                                        }, g));
                                }, 16));
                        }
                    }),
                        w(i + d, function() {
                            if (b._allowZoom()) {
                                if (
                                    (clearTimeout(e),
                                    (b.st.removalDelay = g),
                                    !a)
                                ) {
                                    if (((a = b._getItemToZoom()), !a)) return;
                                    f = j(a);
                                }
                                f.css(b._getOffset(!0)),
                                    b.wrap.append(f),
                                    b.content.css("visibility", "hidden"),
                                    setTimeout(function() {
                                        f.css(b._getOffset());
                                    }, 16);
                            }
                        }),
                        w(h + d, function() {
                            b._allowZoom() &&
                                (k(), f && f.remove(), (a = null));
                        });
                }
            },
            _allowZoom: function() {
                return "image" === b.currItem.type;
            },
            _getItemToZoom: function() {
                return b.currItem.hasSize ? b.currItem.img : !1;
            },
            _getOffset: function(c) {
                var d;
                d = c
                    ? b.currItem.img
                    : b.st.zoom.opener(b.currItem.el || b.currItem);
                var e = d.offset(),
                    f = parseInt(d.css("padding-top"), 10),
                    g = parseInt(d.css("padding-bottom"), 10);
                e.top -= a(window).scrollTop() - f;
                var h = {
                    width: d.width(),
                    height: (u ? d.innerHeight() : d[0].offsetHeight) - g - f
                };
                return (
                    O()
                        ? (h["-moz-transform"] = h.transform =
                              "translate(" + e.left + "px," + e.top + "px)")
                        : ((h.left = e.left), (h.top = e.top)),
                    h
                );
            }
        }
    });
    var P = "iframe",
        Q = "//about:blank",
        R = function(a) {
            if (b.currTemplate[P]) {
                var c = b.currTemplate[P].find("iframe");
                c.length &&
                    (a || (c[0].src = Q),
                    b.isIE8 && c.css("display", a ? "block" : "none"));
            }
        };
    a.magnificPopup.registerModule(P, {
        options: {
            markup:
                '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
            srcAction: "iframe_src",
            patterns: {
                youtube: {
                    index: "youtube.com",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "//player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "//maps.google.",
                    src: "%id%&output=embed"
                }
            }
        },
        proto: {
            initIframe: function() {
                b.types.push(P),
                    w("BeforeChange", function(a, b, c) {
                        b !== c && (b === P ? R() : c === P && R(!0));
                    }),
                    w(h + "." + P, function() {
                        R();
                    });
            },
            getIframe: function(c, d) {
                var e = c.src,
                    f = b.st.iframe;
                a.each(f.patterns, function() {
                    return e.indexOf(this.index) > -1
                        ? (this.id &&
                              (e =
                                  "string" == typeof this.id
                                      ? e.substr(
                                            e.lastIndexOf(this.id) +
                                                this.id.length,
                                            e.length
                                        )
                                      : this.id.call(this, e)),
                          (e = this.src.replace("%id%", e)),
                          !1)
                        : void 0;
                });
                var g = {};
                return (
                    f.srcAction && (g[f.srcAction] = e),
                    b._parseMarkup(d, g, c),
                    b.updateStatus("ready"),
                    d
                );
            }
        }
    });
    var S = function(a) {
            var c = b.items.length;
            return a > c - 1 ? a - c : 0 > a ? c + a : a;
        },
        T = function(a, b, c) {
            return a.replace(/%curr%/gi, b + 1).replace(/%total%/gi, c);
        };
    a.magnificPopup.registerModule("gallery", {
        options: {
            enabled: !1,
            arrowMarkup:
                '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: !0,
            arrows: !0,
            tPrev: "Previous (Left arrow key)",
            tNext: "Next (Right arrow key)",
            tCounter: "%curr% of %total%"
        },
        proto: {
            initGallery: function() {
                var c = b.st.gallery,
                    e = ".mfp-gallery";
                return (
                    (b.direction = !0),
                    c && c.enabled
                        ? ((f += " mfp-gallery"),
                          w(m + e, function() {
                              c.navigateByImgClick &&
                                  b.wrap.on(
                                      "click" + e,
                                      ".mfp-img",
                                      function() {
                                          return b.items.length > 1
                                              ? (b.next(), !1)
                                              : void 0;
                                      }
                                  ),
                                  d.on("keydown" + e, function(a) {
                                      37 === a.keyCode
                                          ? b.prev()
                                          : 39 === a.keyCode && b.next();
                                  });
                          }),
                          w("UpdateStatus" + e, function(a, c) {
                              c.text &&
                                  (c.text = T(
                                      c.text,
                                      b.currItem.index,
                                      b.items.length
                                  ));
                          }),
                          w(l + e, function(a, d, e, f) {
                              var g = b.items.length;
                              e.counter =
                                  g > 1 ? T(c.tCounter, f.index, g) : "";
                          }),
                          w("BuildControls" + e, function() {
                              if (
                                  b.items.length > 1 &&
                                  c.arrows &&
                                  !b.arrowLeft
                              ) {
                                  var d = c.arrowMarkup,
                                      e = (b.arrowLeft = a(
                                          d
                                              .replace(/%title%/gi, c.tPrev)
                                              .replace(/%dir%/gi, "left")
                                      ).addClass(s)),
                                      f = (b.arrowRight = a(
                                          d
                                              .replace(/%title%/gi, c.tNext)
                                              .replace(/%dir%/gi, "right")
                                      ).addClass(s));
                                  e.click(function() {
                                      b.prev();
                                  }),
                                      f.click(function() {
                                          b.next();
                                      }),
                                      b.container.append(e.add(f));
                              }
                          }),
                          w(n + e, function() {
                              b._preloadTimeout &&
                                  clearTimeout(b._preloadTimeout),
                                  (b._preloadTimeout = setTimeout(function() {
                                      b.preloadNearbyImages(),
                                          (b._preloadTimeout = null);
                                  }, 16));
                          }),
                          void w(h + e, function() {
                              d.off(e),
                                  b.wrap.off("click" + e),
                                  (b.arrowRight = b.arrowLeft = null);
                          }))
                        : !1
                );
            },
            next: function() {
                (b.direction = !0),
                    (b.index = S(b.index + 1)),
                    b.updateItemHTML();
            },
            prev: function() {
                (b.direction = !1),
                    (b.index = S(b.index - 1)),
                    b.updateItemHTML();
            },
            goTo: function(a) {
                (b.direction = a >= b.index), (b.index = a), b.updateItemHTML();
            },
            preloadNearbyImages: function() {
                var a,
                    c = b.st.gallery.preload,
                    d = Math.min(c[0], b.items.length),
                    e = Math.min(c[1], b.items.length);
                for (a = 1; a <= (b.direction ? e : d); a++)
                    b._preloadItem(b.index + a);
                for (a = 1; a <= (b.direction ? d : e); a++)
                    b._preloadItem(b.index - a);
            },
            _preloadItem: function(c) {
                if (((c = S(c)), !b.items[c].preloaded)) {
                    var d = b.items[c];
                    d.parsed || (d = b.parseEl(c)),
                        y("LazyLoad", d),
                        "image" === d.type &&
                            (d.img = a('<img class="mfp-img" />')
                                .on("load.mfploader", function() {
                                    d.hasSize = !0;
                                })
                                .on("error.mfploader", function() {
                                    (d.hasSize = !0),
                                        (d.loadError = !0),
                                        y("LazyLoadError", d);
                                })
                                .attr("src", d.src)),
                        (d.preloaded = !0);
                }
            }
        }
    });
    var U = "retina";
    a.magnificPopup.registerModule(U, {
        options: {
            replaceSrc: function(a) {
                return a.src.replace(/\.\w+$/, function(a) {
                    return "@2x" + a;
                });
            },
            ratio: 1
        },
        proto: {
            initRetina: function() {
                if (window.devicePixelRatio > 1) {
                    var a = b.st.retina,
                        c = a.ratio;
                    (c = isNaN(c) ? c() : c),
                        c > 1 &&
                            (w("ImageHasSize." + U, function(a, b) {
                                b.img.css({
                                    "max-width": b.img[0].naturalWidth / c,
                                    width: "100%"
                                });
                            }),
                            w("ElementParse." + U, function(b, d) {
                                d.src = a.replaceSrc(d, c);
                            }));
                }
            }
        }
    }),
        A();
});
!(function(e) {
    e.fn.eaelProgressBar = function() {
        var r = e(this),
            a = r.data("layout"),
            i = r.data("count"),
            s = r.data("duration");
        r.one("inview", function() {
            "line" == a
                ? e(".eael-progressbar-line-fill", r).css({
                      width: i + "%"
                  })
                : "half_circle" == a &&
                  e(".eael-progressbar-circle-half", r).css({
                      transform: "rotate(" + 1.8 * i + "deg)"
                  }),
                e(".eael-progressbar-count", r)
                    .prop({
                        counter: 0
                    })
                    .animate(
                        {
                            counter: i
                        },
                        {
                            duration: s,
                            easing: "linear",
                            step: function(i) {
                                if ("circle" == a) {
                                    var s = 3.6 * i;
                                    e(
                                        ".eael-progressbar-circle-half-left",
                                        r
                                    ).css({
                                        transform: "rotate(" + s + "deg)"
                                    }),
                                        s > 180 &&
                                            (e(
                                                ".eael-progressbar-circle-pie",
                                                r
                                            ).css({
                                                "clip-path": "inset(0)"
                                            }),
                                            e(
                                                ".eael-progressbar-circle-half-right",
                                                r
                                            ).css({
                                                visibility: "visible"
                                            }));
                                }
                                e(this).text(Math.ceil(i));
                            }
                        }
                    );
        });
    };
})(jQuery);
!(function(a) {
    "function" == typeof define && define.amd
        ? define(["jquery"], a)
        : "object" == typeof exports
        ? (module.exports = a(require("jquery")))
        : a(jQuery);
})(function(a) {
    function i() {
        var b,
            c,
            d = {
                height: f.innerHeight,
                width: f.innerWidth
            };
        return (
            d.height ||
                ((b = e.compatMode),
                (b || !a.support.boxModel) &&
                    ((c = "CSS1Compat" === b ? g : e.body),
                    (d = {
                        height: c.clientHeight,
                        width: c.clientWidth
                    }))),
            d
        );
    }

    function j() {
        return {
            top: f.pageYOffset || g.scrollTop || e.body.scrollTop,
            left: f.pageXOffset || g.scrollLeft || e.body.scrollLeft
        };
    }

    function k() {
        if (b.length) {
            var e = 0,
                f = a.map(b, function(a) {
                    var b = a.data.selector,
                        c = a.$element;
                    return b ? c.find(b) : c;
                });
            for (c = c || i(), d = d || j(); e < b.length; e++)
                if (a.contains(g, f[e][0])) {
                    var h = a(f[e]),
                        k = {
                            height: h[0].offsetHeight,
                            width: h[0].offsetWidth
                        },
                        l = h.offset(),
                        m = h.data("inview");
                    if (!d || !c) return;
                    l.top + k.height > d.top &&
                    l.top < d.top + c.height &&
                    l.left + k.width > d.left &&
                    l.left < d.left + c.width
                        ? m || h.data("inview", !0).trigger("inview", [!0])
                        : m && h.data("inview", !1).trigger("inview", [!1]);
                }
        }
    }
    var c,
        d,
        h,
        b = [],
        e = document,
        f = window,
        g = e.documentElement;
    (a.event.special.inview = {
        add: function(c) {
            b.push({
                data: c,
                $element: a(this),
                element: this
            }),
                !h && b.length && (h = setInterval(k, 250));
        },
        remove: function(a) {
            for (var c = 0; c < b.length; c++) {
                var d = b[c];
                if (d.element === this && d.data.guid === a.guid) {
                    b.splice(c, 1);
                    break;
                }
            }
            b.length || (clearInterval(h), (h = null));
        }
    }),
        a(f).on("scroll resize scrollstop", function() {
            c = d = null;
        }),
        !g.addEventListener &&
            g.attachEvent &&
            g.attachEvent("onfocusin", function() {
                d = null;
            });
});
var CountDown = function($scope, $) {
    var $coundDown = $scope.find(".eael-countdown-wrapper").eq(0),
        $countdown_id =
            $coundDown.data("countdown-id") !== undefined
                ? $coundDown.data("countdown-id")
                : "",
        $expire_type =
            $coundDown.data("expire-type") !== undefined
                ? $coundDown.data("expire-type")
                : "",
        $expiry_text =
            $coundDown.data("expiry-text") !== undefined
                ? $coundDown.data("expiry-text")
                : "",
        $expiry_title =
            $coundDown.data("expiry-title") !== undefined
                ? $coundDown.data("expiry-title")
                : "",
        $redirect_url =
            $coundDown.data("redirect-url") !== undefined
                ? $coundDown.data("redirect-url")
                : "",
        $template =
            $coundDown.data("template") !== undefined
                ? $coundDown.data("template")
                : "";
    jQuery(document).ready(function($) {
        "use strict";
        var countDown = $("#eael-countdown-" + $countdown_id);
        countDown.countdown({
            end: function() {
                if ($expire_type == "text") {
                    countDown.html(
                        '<div class="eael-countdown-finish-message"><h4 class="expiry-title">' +
                            $expiry_title +
                            "</h4>" +
                            '<div class="eael-countdown-finish-text">' +
                            $expiry_text +
                            "</div></div>"
                    );
                } else if ($expire_type === "url") {
                    var editMode = $("body").find("#elementor").length;
                    if (editMode > 0) {
                        countDown.html(
                            "Your Page will be redirected to given URL (only on Frontend)."
                        );
                    } else {
                        window.location.href = $redirect_url;
                    }
                } else if ($expire_type === "template") {
                    countDown.html($template);
                } else {
                }
            }
        });
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-countdown.default",
        CountDown
    );
});
var FancyText = function($scope, $) {
    var $fancyText = $scope.find(".eael-fancy-text-container").eq(0),
        $id =
            $fancyText.data("fancy-text-id") !== undefined
                ? $fancyText.data("fancy-text-id")
                : "",
        $fancy_text =
            $fancyText.data("fancy-text") !== undefined
                ? $fancyText.data("fancy-text")
                : "",
        $transition_type =
            $fancyText.data("fancy-text-transition-type") !== undefined
                ? $fancyText.data("fancy-text-transition-type")
                : "",
        $fancy_text_speed =
            $fancyText.data("fancy-text-speed") !== undefined
                ? $fancyText.data("fancy-text-speed")
                : "",
        $fancy_text_delay =
            $fancyText.data("fancy-text-delay") !== undefined
                ? $fancyText.data("fancy-text-delay")
                : "",
        $fancy_text_cursor =
            $fancyText.data("fancy-text-cursor") !== undefined ? !0 : !1,
        $fancy_text_loop =
            $fancyText.data("fancy-text-loop") !== undefined
                ? $fancyText.data("fancy-text-loop") == "yes"
                    ? !0
                    : !1
                : !1;
    $fancy_text = $fancy_text.split("|");
    if ($transition_type == "typing") {
        $("#eael-fancy-text-" + $id).typed({
            strings: $fancy_text,
            typeSpeed: $fancy_text_speed,
            backSpeed: 0,
            startDelay: 300,
            backDelay: $fancy_text_delay,
            showCursor: $fancy_text_cursor,
            loop: $fancy_text_loop
        });
    }
    if ($transition_type != "typing") {
        $("#eael-fancy-text-" + $id).Morphext({
            animation: $transition_type,
            separator: ", ",
            speed: $fancy_text_delay,
            complete: function() {}
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-fancy-text.default",
        FancyText
    );
});
var postTimelineHandler = function($scope, $) {
    var $_this = $scope.find(".eael-post-timeline"),
        $currentTimelineId = "#" + $_this.attr("id"),
        $total_posts = parseInt($_this.data("total_posts"), 10),
        $timeline_id = $_this.data("timeline_id"),
        $post_type = $_this.data("post_type"),
        $posts_per_page = parseInt($_this.data("posts_per_page"), 10),
        $post_order = $_this.data("post_order"),
        $post_orderby = $_this.data("post_orderby"),
        $post_offset = parseInt($_this.data("post_offset"), 10),
        $show_images = $_this.data("show_images"),
        $image_size = $_this.data("image_size"),
        $show_title = $_this.data("show_title"),
        $show_excerpt = $_this.data("show_excerpt"),
        $excerpt_length = parseInt($_this.data("excerpt_length"), 10),
        $btn_text = $_this.data("btn_text"),
        $tax_query = $_this.data("tax_query"),
        $post__in = $_this.data("post__in"),
        $exclude_posts = $_this.data("exclude_posts");
    var options = {
        totalPosts: $total_posts,
        loadMoreBtn: $("#eael-load-more-btn-" + $timeline_id),
        postContainer: $(".eael-post-appender-" + $timeline_id),
        postStyle: "timeline"
    };
    var settings = {
        postType: $post_type,
        perPage: $posts_per_page,
        postOrder: $post_order,
        orderBy: $post_orderby,
        offset: $post_offset,
        showImage: $show_images,
        imageSize: $image_size,
        showTitle: $show_title,
        showExcerpt: $show_excerpt,
        excerptLength: parseInt($excerpt_length, 10),
        btnText: $btn_text,
        tax_query: $tax_query,
        post__in: $post__in,
        exclude_posts: $exclude_posts
    };
    eaelLoadMore(options, settings);
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-post-timeline.default",
        postTimelineHandler
    );
});
var PricingTooltip = function($scope, $) {
    if ($.fn.tooltipster) {
        var $tooltip = $scope.find(".tooltip"),
            i;
        for (i = 0; i < $tooltip.length; i++) {
            var $currentTooltip = $("#" + $($tooltip[i]).attr("id")),
                $tooltipSide =
                    $currentTooltip.data("side") !== undefined
                        ? $currentTooltip.data("side")
                        : !1,
                $tooltipTrigger =
                    $currentTooltip.data("trigger") !== undefined
                        ? $currentTooltip.data("trigger")
                        : "hover",
                $animation =
                    $currentTooltip.data("animation") !== undefined
                        ? $currentTooltip.data("animation")
                        : "fade",
                $anim_duration =
                    $currentTooltip.data("animation_duration") !== undefined
                        ? $currentTooltip.data("animation_duration")
                        : 300,
                $theme =
                    $currentTooltip.data("theme") !== undefined
                        ? $currentTooltip.data("theme")
                        : "default",
                $arrow = "yes" == $currentTooltip.data("arrow") ? !0 : !1;
            $currentTooltip.tooltipster({
                animation: $animation,
                trigger: $tooltipTrigger,
                side: $tooltipSide,
                delay: $anim_duration,
                arrow: $arrow,
                theme: "tooltipster-" + $theme
            });
        }
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-pricing-table.default",
        PricingTooltip
    );
});
var TwitterFeedHandler = function($scope, $) {
    var loadingFeed = $scope.find(".eael-loading-feed");
    var $twitterFeed = $scope.find(".eael-twitter-feed-layout-wrapper").eq(0),
        $name =
            $twitterFeed.data("twitter-feed-ac-name") !== undefined
                ? $twitterFeed.data("twitter-feed-ac-name")
                : "",
        $limit =
            $twitterFeed.data("twitter-feed-post-limit") !== undefined
                ? $twitterFeed.data("twitter-feed-post-limit")
                : "",
        $hash_tag =
            $twitterFeed.data("twitter-feed-hashtag-name") !== undefined
                ? $twitterFeed.data("twitter-feed-hashtag-name")
                : "",
        $key =
            $twitterFeed.data("twitter-feed-consumer-key") !== undefined
                ? $twitterFeed.data("twitter-feed-consumer-key")
                : "",
        $app_secret =
            $twitterFeed.data("twitter-feed-consumer-secret") !== undefined
                ? $twitterFeed.data("twitter-feed-consumer-secret")
                : "",
        $length =
            $twitterFeed.data("twitter-feed-content-length") !== undefined
                ? $twitterFeed.data("twitter-feed-content-length")
                : 400,
        $media =
            $twitterFeed.data("twitter-feed-media") !== undefined
                ? $twitterFeed.data("twitter-feed-media")
                : !1,
        $feed_type =
            $twitterFeed.data("twitter-feed-type") !== undefined
                ? $twitterFeed.data("twitter-feed-type")
                : !1,
        $carouselId =
            $twitterFeed.data("twitter-feed-id") !== undefined
                ? $twitterFeed.data("twitter-feed-id")
                : " ";
    var $id_name = $name.toString();
    var $hash_tag_name = $hash_tag.toString();
    var $key_name = $key.toString();
    var $app_secret = $app_secret.toString();

    function eael_twitter_feeds() {
        $(
            "#eael-twitter-feed-" +
                $carouselId +
                ".eael-twitter-feed-layout-container"
        ).socialfeed({
            twitter: {
                accounts: [$id_name, $hash_tag_name],
                limit: $limit,
                consumer_key: $key_name,
                consumer_secret: $app_secret
            },
            length: $length,
            show_media: $media,
            template_html:
                '<div class="eael-social-feed-element {{? !it.moderation_passed}}hidden{{?}}" dt-create="{{=it.dt_create}}" social-feed-id = "{{=it.id}}">\
                <div class="eael-content">\
                    <a class="pull-left auth-img" href="{{=it.author_link}}" target="_blank">\
                        <img class="media-object" src="{{=it.author_picture}}">\
                    </a>\
                    <div class="media-body">\
                        <p>\
                            <i class="fa fa-{{=it.social_network}} social-feed-icon"></i>\
                            <span class="author-title">{{=it.author_name}}</span>\
                            <span class="muted pull-right social-feed-date"> {{=it.time_ago}}</span>\
                        </p>\
                        <div class="text-wrapper">\
                            <p class="social-feed-text">{{=it.text}} </p>\
                            <p><a href="{{=it.link}}" target="_blank" class="read-more-link">Read More <i class="fa fa-angle-double-right"></i></a></p>\
                        </div>\
                    </div>\
                </div>\
                {{=it.attachment}}\
            </div>'
        });
    }

    function eael_twitter_feed_masonry() {
        $(".eael-twitter-feed-layout-container.masonry-view").masonry({
            itemSelector: ".eael-social-feed-element",
            percentPosition: !0,
            columnWidth: ".eael-social-feed-element"
        });
    }
    $.ajax({
        url: eael_twitter_feeds(),
        beforeSend: function() {
            loadingFeed.addClass("show-loading");
        },
        success: function() {
            $(".eael-twitter-feed-layout-container").bind(
                "DOMSubtreeModified",
                function() {
                    if ($feed_type == "masonry") {
                        setTimeout(function() {
                            eael_twitter_feed_masonry();
                        }, 150);
                    }
                }
            );
            loadingFeed.removeClass("show-loading");
        },
        error: function() {
            console.log("error loading");
        }
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-twitter-feed.default",
        TwitterFeedHandler
    );
});
var filterableGalleryHandler = function($scope, $) {
    if (!isEditMode) {
        var $gallery = $(".eael-filter-gallery-container", $scope),
            $settings = $gallery.data("settings"),
            $gallery_items = $gallery.data("gallery-items"),
            $layout_mode =
                $settings.grid_style == "masonry" ? "masonry" : "fitRows",
            $gallery_enabled = $settings.gallery_enabled == "yes" ? !0 : !1;
        var $isotope_gallery = $gallery.isotope({
            itemSelector: ".eael-filterable-gallery-item-wrap",
            layoutMode: $layout_mode,
            percentPosition: !0,
            stagger: 30,
            transitionDuration: $settings.duration + "ms",
            filter: $(
                ".eael-filter-gallery-control .control.active",
                $scope
            ).data("filter")
        });
        $isotope_gallery.imagesLoaded().progress(function() {
            $isotope_gallery.isotope("layout");
        });
        $isotope_gallery.on("arrangeComplete", function() {
            $isotope_gallery.isotope("layout");
        });
        $(window).on("load", function() {
            $isotope_gallery.isotope("layout");
        });
        $scope.on("click", ".control", function() {
            var $this = $(this),
                $filterValue = $this.data("filter");
            $this.siblings().removeClass("active");
            $this.addClass("active");
            $isotope_gallery.isotope({
                filter: $filterValue
            });
        });
        $(".eael-magnific-link", $scope).magnificPopup({
            type: "image",
            gallery: {
                enabled: $gallery_enabled
            },
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            }
        });
        $($scope).magnificPopup({
            delegate: ".eael-magnific-video-link",
            type: "iframe",
            callbacks: {
                close: function() {
                    $("#elementor-lightbox").hide();
                }
            }
        });
        $scope.on("click", ".eael-gallery-load-more", function(e) {
            e.preventDefault();
            var $this = $(this),
                $init_show = $(
                    ".eael-filter-gallery-container",
                    $scope
                ).children(".eael-filterable-gallery-item-wrap").length,
                $total_items = $gallery.data("total-gallery-items"),
                $images_per_page = $gallery.data("images-per-page"),
                $nomore_text = $gallery.data("nomore-item-text"),
                $items = [];
            if ($init_show == $total_items) {
                $this.html(
                    '<div class="no-more-items-text">' + $nomore_text + "</div>"
                );
                setTimeout(function() {
                    $this.fadeOut("slow");
                }, 600);
            }
            for (var i = $init_show; i < $init_show + $images_per_page; i++) {
                $items.push($($gallery_items[i])[0]);
            }
            $gallery.append($items);
            $isotope_gallery.isotope("appended", $items);
            $isotope_gallery.imagesLoaded().progress(function() {
                $isotope_gallery.isotope("layout");
            });
            $(".eael-magnific-link", $scope).magnificPopup({
                type: "image",
                gallery: {
                    enabled: $gallery_enabled
                },
                callbacks: {
                    close: function() {
                        $("#elementor-lightbox").hide();
                    }
                }
            });
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-filterable-gallery.default",
        filterableGalleryHandler
    );
});
var dataTable = function($scope, $) {
    var $_this = $scope.find(".eael-data-table-wrap"),
        $id = $_this.data("table_id");
    var responsive = $_this.data("custom_responsive");
    if (!0 == responsive) {
        var $th = $scope.find(".eael-data-table").find("th");
        var $tbody = $scope.find(".eael-data-table").find("tbody");
        $tbody.find("tr").each(function(i, item) {
            $(item)
                .find("td .td-content-wrapper")
                .each(function(index, item) {
                    $(this).prepend(
                        '<div class="th-mobile-screen">' +
                            $th.eq(index).html() +
                            "</div>"
                    );
                });
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-data-table.default",
        dataTable
    );
});
var ImageAccordion = function($scope, $) {
    var $imageAccordion = $scope.find(".eael-img-accordion").eq(0),
        $id =
            $imageAccordion.data("img-accordion-id") !== undefined
                ? $imageAccordion.data("img-accordion-id")
                : "",
        $type =
            $imageAccordion.data("img-accordion-type") !== undefined
                ? $imageAccordion.data("img-accordion-type")
                : "";
    if ("on-click" === $type) {
        $("#eael-img-accordion-" + $id + " a").on("click", function(e) {
            if ($(this).hasClass("overlay-active") == !1) {
                e.preventDefault();
            }
            $("#eael-img-accordion-" + $id + " a").css("flex", "1");
            $(this)
                .find(".overlay")
                .parent("a")
                .addClass("overlay-active");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay-inner")
                .addClass("overlay-inner-show");
            $(this).css("flex", "3");
        });
        $("#eael-img-accordion-" + $id + " a").on("blur", function(e) {
            $("#eael-img-accordion-" + $id + " a").css("flex", "1");
            $("#eael-img-accordion-" + $id + " a")
                .find(".overlay-inner")
                .removeClass("overlay-inner-show");
            $(this)
                .find(".overlay")
                .parent("a")
                .removeClass("overlay-active");
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-image-accordion.default",
        ImageAccordion
    );
});
var ContentTicker = function($scope, $) {
    var $contentTicker = $scope.find(".eael-content-ticker").eq(0),
        $items =
            $contentTicker.data("items") !== undefined
                ? $contentTicker.data("items")
                : 1,
        $items_tablet =
            $contentTicker.data("items-tablet") !== undefined
                ? $contentTicker.data("items-tablet")
                : 1,
        $items_mobile =
            $contentTicker.data("items-mobile") !== undefined
                ? $contentTicker.data("items-mobile")
                : 1,
        $margin =
            $contentTicker.data("margin") !== undefined
                ? $contentTicker.data("margin")
                : 10,
        $margin_tablet =
            $contentTicker.data("margin-tablet") !== undefined
                ? $contentTicker.data("margin-tablet")
                : 10,
        $margin_mobile =
            $contentTicker.data("margin-mobile") !== undefined
                ? $contentTicker.data("margin-mobile")
                : 10,
        $effect =
            $contentTicker.data("effect") !== undefined
                ? $contentTicker.data("effect")
                : "slide",
        $speed =
            $contentTicker.data("speed") !== undefined
                ? $contentTicker.data("speed")
                : 400,
        $autoplay =
            $contentTicker.data("autoplay") !== undefined
                ? $contentTicker.data("autoplay")
                : 5000,
        $loop =
            $contentTicker.data("loop") !== undefined
                ? $contentTicker.data("loop")
                : !1,
        $grab_cursor =
            $contentTicker.data("grab-cursor") !== undefined
                ? $contentTicker.data("grab-cursor")
                : !1,
        $pagination =
            $contentTicker.data("pagination") !== undefined
                ? $contentTicker.data("pagination")
                : ".swiper-pagination",
        $arrow_next =
            $contentTicker.data("arrow-next") !== undefined
                ? $contentTicker.data("arrow-next")
                : ".swiper-button-next",
        $arrow_prev =
            $contentTicker.data("arrow-prev") !== undefined
                ? $contentTicker.data("arrow-prev")
                : ".swiper-button-prev",
        $pause_on_hover =
            $contentTicker.data("pause-on-hover") !== undefined
                ? $contentTicker.data("pause-on-hover")
                : "",
        $contentTickerOptions = {
            direction: "horizontal",
            loop: $loop,
            speed: $speed,
            effect: $effect,
            slidesPerView: $items,
            spaceBetween: $margin,
            grabCursor: $grab_cursor,
            paginationClickable: !0,
            autoHeight: !0,
            autoplay: {
                delay: $autoplay
            },
            pagination: {
                el: $pagination,
                clickable: !0
            },
            navigation: {
                nextEl: $arrow_next,
                prevEl: $arrow_prev
            },
            breakpoints: {
                480: {
                    slidesPerView: $items_mobile,
                    spaceBetween: $margin_mobile
                },
                768: {
                    slidesPerView: $items_tablet,
                    spaceBetween: $margin_tablet
                }
            }
        };
    var $contentTickerSlider = new Swiper(
        $contentTicker,
        $contentTickerOptions
    );
    if ($autoplay === 0) {
        $contentTickerSlider.autoplay.stop();
    }
    if ($pause_on_hover && $autoplay !== 0) {
        $contentTicker.on("mouseenter", function() {
            $contentTickerSlider.autoplay.stop();
        });
        $contentTicker.on("mouseleave", function() {
            $contentTickerSlider.autoplay.start();
        });
    }
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-content-ticker.default",
        ContentTicker
    );
});
var AdvAccordionHandler = function($scope, $) {
    var $advanceAccordion = $scope.find(".eael-adv-accordion"),
        $accordionHeader = $scope.find(".eael-accordion-header"),
        $accordionType = $advanceAccordion.data("accordion-type"),
        $accordionSpeed = $advanceAccordion.data("toogle-speed");
    $accordionHeader.each(function() {
        if ($(this).hasClass("active-default")) {
            $(this).addClass("show active");
            $(this)
                .next()
                .slideDown($accordionSpeed);
        }
    });
    $accordionHeader.unbind("click");
    $accordionHeader.click(function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($accordionType === "accordion") {
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this
                    .parent()
                    .parent()
                    .find(".eael-accordion-header")
                    .removeClass("show active");
                $this
                    .parent()
                    .parent()
                    .find(".eael-accordion-content")
                    .slideUp($accordionSpeed);
                $this.toggleClass("show active");
                $this.next().slideToggle($accordionSpeed);
            }
        } else {
            if ($this.hasClass("show")) {
                $this.removeClass("show active");
                $this.next().slideUp($accordionSpeed);
            } else {
                $this.addClass("show active");
                $this.next().slideDown($accordionSpeed);
            }
        }
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-adv-accordion.default",
        AdvAccordionHandler
    );
});
var AdvanceTabHandler = function($scope, $) {
    var $currentTab = $scope.find(".eael-advance-tabs"),
        $currentTabId = "#" + $currentTab.attr("id").toString();
    $($currentTabId + " .eael-tabs-nav ul li").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-nav > ul li")
                .removeClass("active")
                .addClass("inactive");
            $(this).removeClass("inactive");
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });
    $($currentTabId + " .eael-tabs-content div").each(function(index) {
        if ($(this).hasClass("active-default")) {
            $($currentTabId + " .eael-tabs-content > div").removeClass(
                "active"
            );
        } else {
            if (index == 0) {
                $(this)
                    .removeClass("inactive")
                    .addClass("active");
            }
        }
    });
    $($currentTabId + " .eael-tabs-nav ul li").click(function() {
        var currentTabIndex = $(this).index();
        var tabsContainer = $(this).closest(".eael-advance-tabs");
        var tabsNav = $(tabsContainer)
            .children(".eael-tabs-nav")
            .children("ul")
            .children("li");
        var tabsContent = $(tabsContainer)
            .children(".eael-tabs-content")
            .children("div");
        $(this)
            .parent("li")
            .addClass("active");
        $(tabsNav)
            .removeClass("active active-default")
            .addClass("inactive");
        $(this)
            .addClass("active")
            .removeClass("inactive");
        $(tabsContent)
            .removeClass("active")
            .addClass("inactive");
        $(tabsContent)
            .eq(currentTabIndex)
            .addClass("active")
            .removeClass("inactive");
        $(tabsContent).each(function(index) {
            $(this).removeClass("active-default");
        });
    });
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-adv-tabs.default",
        AdvanceTabHandler
    );
});
var ProgressBar = function($scope, $) {
    $(".eael-progressbar", $scope).eaelProgressBar();
};
jQuery(window).on("elementor/frontend/init", function() {
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-progress-bar.default",
        ProgressBar
    );
});
