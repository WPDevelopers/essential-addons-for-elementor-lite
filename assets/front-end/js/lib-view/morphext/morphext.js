(function ($) {
    "use strict";

    var pluginName = "Morphext",
        defaults = {
            animation: "bounceIn",
            separator: ",",
            speed: 2000,
            phrases: null,
            complete: $.noop
        };

    function Plugin (element, options) {
        this.element = $(element);

        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._init();
    }

    // A phrase may legitimately contain inline markup (<br>, <strong>, &nbsp;),
    // so it is written as HTML — but only after DOMPurify has stripped anything
    // dangerous. Without DOMPurify we degrade to plain text (CVE-2026-15145).
    function setPhrase (node, phrase) {
        phrase = (phrase === null || phrase === undefined) ? "" : String(phrase);

        if (typeof DOMPurify !== "undefined" && typeof DOMPurify.sanitize === "function") {
            node.innerHTML = DOMPurify.sanitize(phrase);
        } else {
            node.textContent = phrase;
        }
    }

    Plugin.prototype = {
        _init: function () {
            var $that = this;
            this.phrases = [];

            this.element.addClass("morphext");

            // Prefer the phrase list handed over by the caller — it preserves the
            // author's markup. Reading the element itself only yields rendered
            // text, which loses tags and splits on any separator character that
            // happens to appear inside a phrase.
            if (Array.isArray(this.settings.phrases)) {
                $.each(this.settings.phrases, function (key, value) {
                    var phrase = $.trim((value === null || value === undefined) ? "" : String(value));
                    if (phrase) {
                        $that.phrases.push(phrase);
                    }
                });
            }

            if (!this.phrases.length) {
                $.each(this.element.text().split(this.settings.separator), function (key, value) {
                    $that.phrases.push($.trim(value));
                });
            }

            this.index = -1;
            this.animate();
            this.start();
        },
        animate: function () {
            this.index = ++this.index % this.phrases.length;

            // Build the animated span without innerHTML so the animation class
            // cannot inject markup (CVE-2026-15145).
            var span = document.createElement("span");
            span.className = "animated";
            String(this.settings.animation || "").split(/\s+/).forEach(function (cls) {
                if (cls) {
                    span.classList.add(cls);
                }
            });
            setPhrase(span, this.phrases[this.index]);

            this.element[0].textContent = "";
            this.element[0].appendChild(span);

            if ($.isFunction(this.settings.complete)) {
                this.settings.complete.call(this);
            }
        },
        start: function () {
            var $that = this;
            this._interval = setInterval(function () {
                $that.animate();
            }, this.settings.speed);
        },
        stop: function () {
            this._interval = clearInterval(this._interval);
        }
    };

    $.fn[pluginName] = function (options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };
})(jQuery);
