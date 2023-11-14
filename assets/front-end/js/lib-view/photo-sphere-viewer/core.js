(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('three')) :
        typeof define === 'function' && define.amd ? define(['exports', 'three'], factory) :
            (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.PhotoSphereViewer = {}, global.THREE));
})(this, (function (exports, THREE) {

    /*!
 * PhotoSphereViewer 5.4.4
 * @copyright 2014-2015 Jérémy Heleine
 * @copyright 2023 Damien "Mistic" Sorel
 * @licence MIT (https://opensource.org/licenses/MIT)
 */
    "use strict";
    var __defProp = Object.defineProperty;
    var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
    var __getOwnPropNames = Object.getOwnPropertyNames;
    var __hasOwnProp = Object.prototype.hasOwnProperty;
    var __export = (target, all) => {
        for (var name in all)
            __defProp(target, name, { get: all[name], enumerable: true });
    };
    var __copyProps = (to, from, except, desc) => {
        if (from && typeof from === "object" || typeof from === "function") {
            for (let key of __getOwnPropNames(from))
                if (!__hasOwnProp.call(to, key) && key !== except)
                    __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
        }
        return to;
    };

    // three
    var require_three = () => THREE;

    // src/index.ts
    var src_exports = {};
    __export(src_exports, {
        AbstractAdapter: () => AbstractAdapter,
        AbstractButton: () => AbstractButton,
        AbstractComponent: () => AbstractComponent,
        AbstractConfigurablePlugin: () => AbstractConfigurablePlugin,
        AbstractPlugin: () => AbstractPlugin,
        CONSTANTS: () => constants_exports,
        DEFAULTS: () => DEFAULTS,
        EquirectangularAdapter: () => EquirectangularAdapter,
        PSVError: () => PSVError,
        SYSTEM: () => SYSTEM,
        TypedEvent: () => TypedEvent,
        Viewer: () => Viewer,
        events: () => events_exports,
        registerButton: () => registerButton,
        utils: () => utils_exports
    });
    var import_three13 = require_three();

    // src/data/constants.ts
    var constants_exports = {};
    __export(constants_exports, {
        ACTIONS: () => ACTIONS,
        ANIMATION_MIN_DURATION: () => ANIMATION_MIN_DURATION,
        CAPTURE_EVENTS_CLASS: () => CAPTURE_EVENTS_CLASS,
        CTRLZOOM_TIMEOUT: () => CTRLZOOM_TIMEOUT,
        DBLCLICK_DELAY: () => DBLCLICK_DELAY,
        DEFAULT_TRANSITION: () => DEFAULT_TRANSITION,
        EASINGS: () => EASINGS,
        ICONS: () => ICONS,
        IDS: () => IDS,
        INERTIA_WINDOW: () => INERTIA_WINDOW,
        KEY_CODES: () => KEY_CODES,
        LONGTOUCH_DELAY: () => LONGTOUCH_DELAY,
        MOVE_THRESHOLD: () => MOVE_THRESHOLD,
        SPHERE_RADIUS: () => SPHERE_RADIUS,
        TWOFINGERSOVERLAY_DELAY: () => TWOFINGERSOVERLAY_DELAY,
        VIEWER_DATA: () => VIEWER_DATA
    });

    // src/icons/arrow.svg
    var arrow_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="40 40 432 432"><g transform="rotate(0, 256, 256)"><path fill="currentColor" d="M425.23 210.55H227.39a5 5 0 01-3.53-8.53l56.56-56.57a45.5 45.5 0 000-64.28 45.15 45.15 0 00-32.13-13.3 45.15 45.15 0 00-32.14 13.3L41.32 256l174.83 174.83a45.15 45.15 0 0032.14 13.3 45.15 45.15 0 0032.13-13.3 45.5 45.5 0 000-64.28l-56.57-56.57a5 5 0 013.54-8.53h197.84c25.06 0 45.45-20.39 45.45-45.45s-20.4-45.45-45.45-45.45z"/></g><!-- Created by Flatart from the Noun Project --></svg>\n';

    // src/icons/close.svg
    var close_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><g fill="currentColor" transform=" translate(50, 50) rotate(45)"><rect x="-5" y="-65" width="10" height="130"/><rect x="-65" y="-5" width="130" height="10"/></g></svg>';

    // src/icons/download.svg
    var download_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M83.3 35.6h-17V3H32.2v32.6H16.6l33.6 32.7 33-32.7z"/><path fill="currentColor" d="M83.3 64.2v16.3H16.6V64.2H-.1v32.6H100V64.2H83.3z"/><!--Created by Michael Zenaty from the Noun Project--></svg>\n';

    // src/icons/fullscreen-in.svg
    var fullscreen_in_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M100 40H87.1V18.8h-21V6H100zM100 93.2H66V80.3h21.1v-21H100zM34 93.2H0v-34h12.9v21.1h21zM12.9 40H0V6h34v12.9H12.8z"/><!--Created by Garrett Knoll from the Noun Project--></svg>\n';

    // src/icons/fullscreen-out.svg
    var fullscreen_out_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M66 7h13v21h21v13H66zM66 60.3h34v12.9H79v21H66zM0 60.3h34v34H21V73.1H0zM21 7h13v34H0V28h21z"/><!--Created by Garrett Knoll from the Noun Project--></svg>\n';

    // src/icons/info.svg
    var info_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="currentColor" d="M28.3 26.1c-1 2.6-1.9 4.8-2.6 7-2.5 7.4-5 14.7-7.2 22-1.3 4.4.5 7.2 4.3 7.8 1.3.2 2.8.2 4.2-.1 8.2-2 11.9-8.6 15.7-15.2l-2.2 2a18.8 18.8 0 0 1-7.4 5.2 2 2 0 0 1-1.6-.2c-.2-.1 0-1 0-1.4l.8-1.8L41.9 28c.5-1.4.9-3 .7-4.4-.2-2.6-3-4.4-6.3-4.4-8.8.2-15 4.5-19.5 11.8-.2.3-.2.6-.3 1.3 3.7-2.8 6.8-6.1 11.8-6.2z"/><circle fill="currentColor" cx="39.3" cy="9.2" r="8.2"/><!--Created by Arafat Uddin from the Noun Project--></svg>\n';

    // src/icons/menu.svg
    var menu_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="10 10 80 80"><g fill="currentColor"><circle r="10" cx="20" cy="20"/><circle r="10" cx="50" cy="20"/><circle r="10" cx="80" cy="20"/><circle r="10" cx="20" cy="50"/><circle r="10" cx="50" cy="50"/><circle r="10" cx="80" cy="50"/><circle r="10" cx="20" cy="80"/><circle r="10" cx="50" cy="80"/><circle r="10" cx="80" cy="80"/></g><!-- Created by Richard Kun\xE1k from the Noun Project--></svg>\n';

    // src/icons/zoom-in.svg
    var zoom_in_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" d="M14.043 12.22a7.738 7.738 0 1 0-1.823 1.822l4.985 4.985c.503.504 1.32.504 1.822 0a1.285 1.285 0 0 0 0-1.822l-4.984-4.985zm-6.305 1.043a5.527 5.527 0 1 1 0-11.053 5.527 5.527 0 0 1 0 11.053z"/><path fill="currentColor" d="M8.728 4.009H6.744v2.737H4.006V8.73h2.738v2.736h1.984V8.73h2.737V6.746H8.728z"/><!--Created by Ryan Canning from the Noun Project--></svg>\n';

    // src/icons/zoom-out.svg
    var zoom_out_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" d="M14.043 12.22a7.738 7.738 0 1 0-1.823 1.822l4.985 4.985c.503.504 1.32.504 1.822 0a1.285 1.285 0 0 0 0-1.822l-4.984-4.985zm-6.305 1.043a5.527 5.527 0 1 1 0-11.053 5.527 5.527 0 0 1 0 11.053z"/><path fill="currentColor" d="M4.006 6.746h7.459V8.73H4.006z"/><!--Created by Ryan Canning from the Noun Project--></svg>\n';

    // src/data/constants.ts
    var DEFAULT_TRANSITION = 1500;
    var ANIMATION_MIN_DURATION = 500;
    var MOVE_THRESHOLD = 4;
    var DBLCLICK_DELAY = 300;
    var LONGTOUCH_DELAY = 500;
    var TWOFINGERSOVERLAY_DELAY = 100;
    var CTRLZOOM_TIMEOUT = 2e3;
    var INERTIA_WINDOW = 300;
    var SPHERE_RADIUS = 10;
    var VIEWER_DATA = "photoSphereViewer";
    var CAPTURE_EVENTS_CLASS = "psv--capture-event";
    var ACTIONS = /* @__PURE__ */ ((ACTIONS2) => {
        ACTIONS2["ROTATE_UP"] = "ROTATE_UP";
        ACTIONS2["ROTATE_DOWN"] = "ROTATE_DOWN";
        ACTIONS2["ROTATE_RIGHT"] = "ROTATE_RIGHT";
        ACTIONS2["ROTATE_LEFT"] = "ROTATE_LEFT";
        ACTIONS2["ZOOM_IN"] = "ZOOM_IN";
        ACTIONS2["ZOOM_OUT"] = "ZOOM_OUT";
        return ACTIONS2;
    })(ACTIONS || {});
    var IDS = {
        MENU: "menu",
        TWO_FINGERS: "twoFingers",
        CTRL_ZOOM: "ctrlZoom",
        ERROR: "error",
        DESCRIPTION: "description"
    };
    var KEY_CODES = {
        Enter: "Enter",
        Control: "Control",
        Escape: "Escape",
        Space: " ",
        PageUp: "PageUp",
        PageDown: "PageDown",
        ArrowLeft: "ArrowLeft",
        ArrowUp: "ArrowUp",
        ArrowRight: "ArrowRight",
        ArrowDown: "ArrowDown",
        Delete: "Delete",
        Plus: "+",
        Minus: "-"
    };
    var ICONS = {
        arrow: arrow_default,
        close: close_default,
        download: download_default,
        fullscreenIn: fullscreen_in_default,
        fullscreenOut: fullscreen_out_default,
        info: info_default,
        menu: menu_default,
        zoomIn: zoom_in_default,
        zoomOut: zoom_out_default
    };
    var EASINGS = {
        linear: (t) => t,
        inQuad: (t) => t * t,
        outQuad: (t) => t * (2 - t),
        inOutQuad: (t) => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
        inCubic: (t) => t * t * t,
        outCubic: (t) => --t * t * t + 1,
        inOutCubic: (t) => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
        inQuart: (t) => t * t * t * t,
        outQuart: (t) => 1 - --t * t * t * t,
        inOutQuart: (t) => t < 0.5 ? 8 * t * t * t * t : 1 - 8 * --t * t * t * t,
        inQuint: (t) => t * t * t * t * t,
        outQuint: (t) => 1 + --t * t * t * t * t,
        inOutQuint: (t) => t < 0.5 ? 16 * t * t * t * t * t : 1 + 16 * --t * t * t * t * t,
        inSine: (t) => 1 - Math.cos(t * (Math.PI / 2)),
        outSine: (t) => Math.sin(t * (Math.PI / 2)),
        inOutSine: (t) => 0.5 - 0.5 * Math.cos(Math.PI * t),
        inExpo: (t) => Math.pow(2, 10 * (t - 1)),
        outExpo: (t) => 1 - Math.pow(2, -10 * t),
        inOutExpo: (t) => (t = t * 2 - 1) < 0 ? 0.5 * Math.pow(2, 10 * t) : 1 - 0.5 * Math.pow(2, -10 * t),
        inCirc: (t) => 1 - Math.sqrt(1 - t * t),
        outCirc: (t) => Math.sqrt(1 - (t - 1) * (t - 1)),
        inOutCirc: (t) => (t *= 2) < 1 ? 0.5 - 0.5 * Math.sqrt(1 - t * t) : 0.5 + 0.5 * Math.sqrt(1 - (t -= 2) * t)
    };

    // src/utils/index.ts
    var utils_exports = {};
    __export(utils_exports, {
        Animation: () => Animation,
        Dynamic: () => Dynamic,
        MultiDynamic: () => MultiDynamic,
        PressHandler: () => PressHandler,
        Slider: () => Slider,
        SliderDirection: () => SliderDirection,
        addClasses: () => addClasses,
        angle: () => angle,
        applyEulerInverse: () => applyEulerInverse,
        checkStylesheet: () => checkStylesheet,
        cleanCssPosition: () => cleanCssPosition,
        clone: () => clone,
        createTexture: () => createTexture,
        cssPositionIsOrdered: () => cssPositionIsOrdered,
        dasherize: () => dasherize,
        deepEqual: () => deepEqual,
        deepmerge: () => deepmerge,
        distance: () => distance,
        exitFullscreen: () => exitFullscreen,
        firstNonNull: () => firstNonNull,
        getAbortError: () => getAbortError,
        getAngle: () => getAngle,
        getClosest: () => getClosest,
        getConfigParser: () => getConfigParser,
        getElement: () => getElement,
        getPosition: () => getPosition,
        getShortestArc: () => getShortestArc,
        getStyle: () => getStyle,
        getStyleProperty: () => getStyleProperty,
        getTouchData: () => getTouchData,
        getXMPValue: () => getXMPValue,
        greatArcDistance: () => greatArcDistance,
        hasParent: () => hasParent,
        invertResolvableBoolean: () => invertResolvableBoolean,
        isAbortError: () => isAbortError,
        isEmpty: () => isEmpty,
        isExtendedPosition: () => isExtendedPosition,
        isFullscreenEnabled: () => isFullscreenEnabled,
        isNil: () => isNil,
        isPlainObject: () => isPlainObject,
        logWarn: () => logWarn,
        parseAngle: () => parseAngle,
        parsePoint: () => parsePoint,
        parseSpeed: () => parseSpeed,
        removeClasses: () => removeClasses,
        requestFullscreen: () => requestFullscreen,
        resolveBoolean: () => resolveBoolean,
        speedToDuration: () => speedToDuration,
        sum: () => sum,
        throttle: () => throttle,
        toggleClass: () => toggleClass,
        wrap: () => wrap
    });

    // src/utils/math.ts
    function wrap(value, max) {
        let result = value % max;
        if (result < 0) {
            result += max;
        }
        return result;
    }
    function sum(array) {
        return array.reduce((a, b) => a + b, 0);
    }
    function distance(p1, p2) {
        return Math.sqrt(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));
    }
    function angle(p1, p2) {
        return Math.atan2(p2.y - p1.y, p2.x - p1.x);
    }
    function getShortestArc(from, to) {
        const candidates = [
            0,
            // direct
            Math.PI * 2,
            // clock-wise cross zero
            -Math.PI * 2
            // counter-clock-wise cross zero
        ];
        return candidates.reduce((value, candidate) => {
            const newCandidate = to - from + candidate;
            return Math.abs(newCandidate) < Math.abs(value) ? newCandidate : value;
        }, Infinity);
    }
    function getAngle(position1, position2) {
        return Math.acos(
            Math.cos(position1.pitch) * Math.cos(position2.pitch) * Math.cos(position1.yaw - position2.yaw) + Math.sin(position1.pitch) * Math.sin(position2.pitch)
        );
    }
    function greatArcDistance([yaw1, pitch1], [yaw2, pitch2]) {
        if (yaw1 - yaw2 > Math.PI) {
            yaw1 -= 2 * Math.PI;
        } else if (yaw1 - yaw2 < -Math.PI) {
            yaw1 += 2 * Math.PI;
        }
        const x = (yaw2 - yaw1) * Math.cos((pitch1 + pitch2) / 2);
        const y = pitch2 - pitch1;
        return Math.sqrt(x * x + y * y);
    }

    // src/utils/browser.ts
    function getElement(selector) {
        if (typeof selector === "string") {
            return selector.match(/^[a-z]/i) ? document.getElementById(selector) : document.querySelector(selector);
        } else {
            return selector;
        }
    }
    function toggleClass(element, className, active) {
        if (active === void 0) {
            element.classList.toggle(className);
        } else if (active) {
            element.classList.add(className);
        } else if (!active) {
            element.classList.remove(className);
        }
    }
    function addClasses(element, className) {
        element.classList.add(...className.split(" "));
    }
    function removeClasses(element, className) {
        element.classList.remove(...className.split(" "));
    }
    function hasParent(el, parent) {
        let test = el;
        do {
            if (test === parent) {
                return true;
            }
            test = test.parentElement;
        } while (test);
        return false;
    }
    function getClosest(el, selector) {
        if (!el?.matches) {
            return null;
        }
        let test = el;
        do {
            if (test.matches(selector)) {
                return test;
            }
            test = test.parentElement;
        } while (test);
        return null;
    }
    function getPosition(el) {
        let x = 0;
        let y = 0;
        let test = el;
        while (test) {
            x += test.offsetLeft - test.scrollLeft + test.clientLeft;
            y += test.offsetTop - test.scrollTop + test.clientTop;
            test = test.offsetParent;
        }
        return { x, y };
    }
    function getStyle(elt, prop) {
        return window.getComputedStyle(elt, null)[prop];
    }
    function getStyleProperty(elt, varname) {
        return window.getComputedStyle(elt).getPropertyValue(varname);
    }
    function getTouchData(e) {
        if (e.touches.length < 2) {
            return null;
        }
        const p1 = { x: e.touches[0].clientX, y: e.touches[0].clientY };
        const p2 = { x: e.touches[1].clientX, y: e.touches[1].clientY };
        return {
            distance: distance(p1, p2),
            angle: angle(p1, p2),
            center: { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 }
        };
    }
    function isFullscreenEnabled(elt) {
        return (document.fullscreenElement || document.webkitFullscreenElement) === elt;
    }
    function requestFullscreen(elt) {
        (elt.requestFullscreen || elt.webkitRequestFullscreen).call(elt);
    }
    function exitFullscreen() {
        (document.exitFullscreen || document.webkitExitFullscreen).call(document);
    }

    // src/utils/misc.ts
    function dasherize(str) {
        return str.replace(/[A-Z](?:(?=[^A-Z])|[A-Z]*(?=[A-Z][^A-Z]|$))/g, (s, i) => {
            return (i > 0 ? "-" : "") + s.toLowerCase();
        });
    }
    function throttle(callback, wait) {
        let paused = false;
        return function(...args) {
            if (!paused) {
                paused = true;
                setTimeout(() => {
                    callback.apply(this, args);
                    paused = false;
                }, wait);
            }
        };
    }
    function isPlainObject(value) {
        if (typeof value !== "object" || value === null || Object.prototype.toString.call(value) !== "[object Object]") {
            return false;
        }
        if (Object.getPrototypeOf(value) === null) {
            return true;
        }
        let proto = value;
        while (Object.getPrototypeOf(proto) !== null) {
            proto = Object.getPrototypeOf(proto);
        }
        return Object.getPrototypeOf(value) === proto;
    }
    function deepmerge(target, src) {
        const first = src;
        return function merge(target2, src2) {
            if (Array.isArray(src2)) {
                if (!target2 || !Array.isArray(target2)) {
                    target2 = [];
                } else {
                    target2.length = 0;
                }
                src2.forEach((e, i) => {
                    target2[i] = merge(null, e);
                });
            } else if (typeof src2 === "object") {
                if (!target2 || Array.isArray(target2)) {
                    target2 = {};
                }
                Object.keys(src2).forEach((key) => {
                    if (typeof src2[key] !== "object" || !src2[key] || !isPlainObject(src2[key])) {
                        target2[key] = src2[key];
                    } else if (src2[key] !== first) {
                        if (!target2[key]) {
                            target2[key] = merge(null, src2[key]);
                        } else {
                            merge(target2[key], src2[key]);
                        }
                    }
                });
            } else {
                target2 = src2;
            }
            return target2;
        }(target, src);
    }
    function clone(src) {
        return deepmerge(null, src);
    }
    function isEmpty(obj) {
        return !obj || Object.keys(obj).length === 0 && obj.constructor === Object;
    }
    function isNil(val) {
        return val === null || val === void 0;
    }
    function firstNonNull(...values) {
        for (const val of values) {
            if (!isNil(val)) {
                return val;
            }
        }
        return null;
    }
    function deepEqual(obj1, obj2) {
        if (obj1 === obj2) {
            return true;
        } else if (isObject(obj1) && isObject(obj2)) {
            if (Object.keys(obj1).length !== Object.keys(obj2).length) {
                return false;
            }
            for (const prop of Object.keys(obj1)) {
                if (!deepEqual(obj1[prop], obj2[prop])) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    function isObject(obj) {
        return typeof obj === "object" && obj !== null;
    }

    // src/utils/psv.ts
    var import_three = require_three();

    // src/PSVError.ts
    var PSVError = class _PSVError extends Error {
        constructor(message) {
            super(message);
            this.name = "PSVError";
            Error.captureStackTrace?.(this, _PSVError);
        }
    };

    // src/utils/psv.ts
    function resolveBoolean(value, cb) {
        if (isPlainObject(value)) {
            cb(value.initial, true);
            value.promise.then((res) => cb(res, false));
        } else {
            cb(value, true);
        }
    }
    function invertResolvableBoolean(value) {
        return {
            initial: !value.initial,
            promise: value.promise.then((res) => !res)
        };
    }
    function getAbortError() {
        const error = new Error("Loading was aborted.");
        error.name = "AbortError";
        return error;
    }
    function isAbortError(err) {
        return err?.name === "AbortError";
    }
    function logWarn(message) {
        console.warn(`PhotoSphereViewer: ${message}`);
    }
    function isExtendedPosition(object) {
        if (!object) {
            return false;
        }
        return [
            ["textureX", "textureY"],
            ["yaw", "pitch"]
        ].some(([key1, key2]) => {
            return object[key1] !== void 0 && object[key2] !== void 0;
        });
    }
    function getXMPValue(data, attr) {
        let result = data.match("<GPano:" + attr + ">(.*)</GPano:" + attr + ">");
        if (result !== null) {
            const val = parseInt(result[1], 10);
            return isNaN(val) ? null : val;
        }
        result = data.match("GPano:" + attr + '="(.*?)"');
        if (result !== null) {
            const val = parseInt(result[1], 10);
            return isNaN(val) ? null : val;
        }
        return null;
    }
    var CSS_POSITIONS = {
        top: "0%",
        bottom: "100%",
        left: "0%",
        right: "100%",
        center: "50%"
    };
    var X_VALUES = ["left", "center", "right"];
    var Y_VALUES = ["top", "center", "bottom"];
    var POS_VALUES = [...X_VALUES, ...Y_VALUES];
    var CENTER = "center";
    function parsePoint(value) {
        if (!value) {
            return { x: 0.5, y: 0.5 };
        }
        if (typeof value === "object") {
            return value;
        }
        let tokens = value.toLocaleLowerCase().split(" ").slice(0, 2);
        if (tokens.length === 1) {
            if (CSS_POSITIONS[tokens[0]]) {
                tokens = [tokens[0], CENTER];
            } else {
                tokens = [tokens[0], tokens[0]];
            }
        }
        const xFirst = tokens[1] !== "left" && tokens[1] !== "right" && tokens[0] !== "top" && tokens[0] !== "bottom";
        tokens = tokens.map((token) => CSS_POSITIONS[token] || token);
        if (!xFirst) {
            tokens.reverse();
        }
        const parsed = tokens.join(" ").match(/^([0-9.]+)% ([0-9.]+)%$/);
        if (parsed) {
            return {
                x: parseFloat(parsed[1]) / 100,
                y: parseFloat(parsed[2]) / 100
            };
        } else {
            return { x: 0.5, y: 0.5 };
        }
    }
    function cleanCssPosition(value, { allowCenter, cssOrder } = {
        allowCenter: true,
        cssOrder: true
    }) {
        if (!value) {
            return null;
        }
        if (typeof value === "string") {
            value = value.split(" ");
        }
        if (value.length === 1) {
            if (value[0] === CENTER) {
                value = [CENTER, CENTER];
            } else if (X_VALUES.indexOf(value[0]) !== -1) {
                value = [CENTER, value[0]];
            } else if (Y_VALUES.indexOf(value[0]) !== -1) {
                value = [value[0], CENTER];
            }
        }
        if (value.length !== 2 || POS_VALUES.indexOf(value[0]) === -1 || POS_VALUES.indexOf(value[1]) === -1) {
            logWarn(`Unparsable position ${value}`);
            return null;
        }
        if (!allowCenter && value[0] === CENTER && value[1] === CENTER) {
            logWarn(`Invalid position center center`);
            return null;
        }
        if (cssOrder && !cssPositionIsOrdered(value)) {
            value = [value[1], value[0]];
        }
        if (value[1] === CENTER && X_VALUES.indexOf(value[0]) !== -1) {
            value = [CENTER, value[0]];
        }
        if (value[0] === CENTER && Y_VALUES.indexOf(value[1]) !== -1) {
            value = [value[1], CENTER];
        }
        return value;
    }
    function cssPositionIsOrdered(value) {
        return Y_VALUES.indexOf(value[0]) !== -1 && X_VALUES.indexOf(value[1]) !== -1;
    }
    function parseSpeed(speed) {
        let parsed;
        if (typeof speed === "string") {
            const speedStr = speed.toString().trim();
            let speedValue = parseFloat(speedStr.replace(/^(-?[0-9]+(?:\.[0-9]*)?).*$/, "$1"));
            const speedUnit = speedStr.replace(/^-?[0-9]+(?:\.[0-9]*)?(.*)$/, "$1").trim();
            if (speedUnit.match(/(pm|per minute)$/)) {
                speedValue /= 60;
            }
            switch (speedUnit) {
                case "dpm":
                case "degrees per minute":
                case "dps":
                case "degrees per second":
                    parsed = import_three.MathUtils.degToRad(speedValue);
                    break;
                case "rdpm":
                case "radians per minute":
                case "rdps":
                case "radians per second":
                    parsed = speedValue;
                    break;
                case "rpm":
                case "revolutions per minute":
                case "rps":
                case "revolutions per second":
                    parsed = speedValue * Math.PI * 2;
                    break;
                default:
                    throw new PSVError(`Unknown speed unit "${speedUnit}"`);
            }
        } else {
            parsed = speed;
        }
        return parsed;
    }
    function speedToDuration(value, angle2) {
        if (typeof value !== "number") {
            const speed = parseSpeed(value);
            return angle2 / Math.abs(speed) * 1e3;
        } else {
            return Math.abs(value);
        }
    }
    function parseAngle(angle2, zeroCenter = false, halfCircle = zeroCenter) {
        let parsed;
        if (typeof angle2 === "string") {
            const match = angle2.toLowerCase().trim().match(/^(-?[0-9]+(?:\.[0-9]*)?)(.*)$/);
            if (!match) {
                throw new PSVError(`Unknown angle "${angle2}"`);
            }
            const value = parseFloat(match[1]);
            const unit = match[2];
            if (unit) {
                switch (unit) {
                    case "deg":
                    case "degs":
                        parsed = import_three.MathUtils.degToRad(value);
                        break;
                    case "rad":
                    case "rads":
                        parsed = value;
                        break;
                    default:
                        throw new PSVError(`Unknown angle unit "${unit}"`);
                }
            } else {
                parsed = value;
            }
        } else if (typeof angle2 === "number" && !isNaN(angle2)) {
            parsed = angle2;
        } else {
            throw new PSVError(`Unknown angle "${angle2}"`);
        }
        parsed = wrap(zeroCenter ? parsed + Math.PI : parsed, Math.PI * 2);
        return zeroCenter ? import_three.MathUtils.clamp(parsed - Math.PI, -Math.PI / (halfCircle ? 2 : 1), Math.PI / (halfCircle ? 2 : 1)) : parsed;
    }
    function createTexture(img, mimaps = false) {
        const texture = new import_three.Texture(img);
        texture.needsUpdate = true;
        texture.minFilter = mimaps ? import_three.LinearMipmapLinearFilter : import_three.LinearFilter;
        texture.generateMipmaps = mimaps;
        texture.anisotropy = mimaps ? 2 : 1;
        return texture;
    }
    var quaternion = new import_three.Quaternion();
    function applyEulerInverse(vector, euler) {
        quaternion.setFromEuler(euler).invert();
        vector.applyQuaternion(quaternion);
    }
    function getConfigParser(defaults, parsers) {
        const parser = function(userConfig) {
            if (!userConfig) {
                return clone(defaults);
            }
            const rawConfig = clone({
                ...defaults,
                ...userConfig
            });
            const config = {};
            for (let [key, value] of Object.entries(rawConfig)) {
                if (parsers && key in parsers) {
                    value = parsers[key](value, {
                        rawConfig,
                        defValue: defaults[key]
                    });
                } else if (!(key in defaults)) {
                    logWarn(`Unknown option ${key}`);
                    continue;
                }
                config[key] = value;
            }
            return config;
        };
        parser.defaults = defaults;
        parser.parsers = parsers || {};
        return parser;
    }
    function checkStylesheet(element, name) {
        if (getStyleProperty(element, `--psv-${name}-loaded`) !== "true") {
            console.error(`PhotoSphereViewer: stylesheet "@photo-sphere-viewer/${name}/index.css" is not loaded`);
        }
    }

    // src/utils/Animation.ts
    var Animation = class {
        constructor(options) {
            this.easing = EASINGS["linear"];
            this.callbacks = [];
            this.resolved = false;
            this.cancelled = false;
            this.options = options;
            if (options) {
                if (options.easing) {
                    this.easing = typeof options.easing === "function" ? options.easing : EASINGS[options.easing] || EASINGS["linear"];
                }
                this.delayTimeout = setTimeout(() => {
                    this.delayTimeout = void 0;
                    this.animationFrame = window.requestAnimationFrame((t) => this.__run(t));
                }, options.delay || 0);
            } else {
                this.resolved = true;
            }
        }
        __run(timestamp) {
            if (this.cancelled) {
                return;
            }
            if (!this.start) {
                this.start = timestamp;
            }
            const progress = (timestamp - this.start) / this.options.duration;
            const current = {};
            if (progress < 1) {
                for (const [name, prop] of Object.entries(this.options.properties)) {
                    if (prop) {
                        const value = prop.start + (prop.end - prop.start) * this.easing(progress);
                        current[name] = value;
                    }
                }
                this.options.onTick(current, progress);
                this.animationFrame = window.requestAnimationFrame((t) => this.__run(t));
            } else {
                for (const [name, prop] of Object.entries(this.options.properties)) {
                    if (prop) {
                        current[name] = prop.end;
                    }
                }
                this.options.onTick(current, 1);
                this.__resolve(true);
                this.animationFrame = void 0;
            }
        }
        __resolve(value) {
            if (value) {
                this.resolved = true;
            } else {
                this.cancelled = true;
            }
            this.callbacks.forEach((cb) => cb(value));
            this.callbacks.length = 0;
        }
        /**
         * Promise chaining
         * @param [onFulfilled] - Called when the animation is complete (true) or cancelled (false)
         */
        then(onFulfilled) {
            if (this.resolved || this.cancelled) {
                return Promise.resolve(this.resolved).then(onFulfilled);
            }
            return new Promise((resolve) => {
                this.callbacks.push(resolve);
            }).then(onFulfilled);
        }
        /**
         * Cancels the animation
         */
        cancel() {
            if (!this.cancelled && !this.resolved) {
                this.__resolve(false);
                if (this.delayTimeout) {
                    window.clearTimeout(this.delayTimeout);
                    this.delayTimeout = void 0;
                }
                if (this.animationFrame) {
                    window.cancelAnimationFrame(this.animationFrame);
                    this.animationFrame = void 0;
                }
            }
        }
    };

    // src/utils/Dynamic.ts
    var import_three2 = require_three();
    var Dynamic = class {
        constructor(fn, config) {
            this.fn = fn;
            this.mode = 0 /* STOP */;
            this.speed = 0;
            this.speedMult = 0;
            this.currentSpeed = 0;
            this.target = 0;
            this.__current = 0;
            this.min = config.min;
            this.max = config.max;
            this.wrap = config.wrap;
            this.current = config.defaultValue;
            if (this.wrap && this.min !== 0) {
                throw new PSVError("invalid config");
            }
            if (this.fn) {
                this.fn(this.current);
            }
        }
        get current() {
            return this.__current;
        }
        set current(current) {
            this.__current = current;
        }
        /**
         * Changes base speed
         */
        setSpeed(speed) {
            this.speed = speed;
        }
        /**
         * Defines the target position
         */
        goto(position, speedMult = 1) {
            this.mode = 2 /* POSITION */;
            this.target = this.wrap ? wrap(position, this.max) : import_three2.MathUtils.clamp(position, this.min, this.max);
            this.speedMult = speedMult;
        }
        /**
         * Increases/decreases the target position
         */
        step(step, speedMult = 1) {
            if (speedMult === 0) {
                this.setValue(this.current + step);
            } else {
                if (this.mode !== 2 /* POSITION */) {
                    this.target = this.current;
                }
                this.goto(this.target + step, speedMult);
            }
        }
        /**
         * Starts infinite movement
         */
        roll(invert = false, speedMult = 1) {
            this.mode = 1 /* INFINITE */;
            this.target = invert ? -Infinity : Infinity;
            this.speedMult = speedMult;
        }
        /**
         * Stops movement
         */
        stop() {
            this.mode = 0 /* STOP */;
        }
        /**
         * Defines the current position and immediately stops movement
         * @param {number} value
         */
        setValue(value) {
            this.target = this.wrap ? wrap(value, this.max) : import_three2.MathUtils.clamp(value, this.min, this.max);
            this.mode = 0 /* STOP */;
            this.currentSpeed = 0;
            if (this.target !== this.current) {
                this.current = this.target;
                if (this.fn) {
                    this.fn(this.current);
                }
                return true;
            }
            return false;
        }
        /**
         * @internal
         */
        update(elapsed) {
            if (this.mode === 2 /* POSITION */) {
                if (this.wrap && Math.abs(this.target - this.current) > this.max / 2) {
                    this.current = this.current < this.target ? this.current + this.max : this.current - this.max;
                }
                const dstStop = this.currentSpeed * this.currentSpeed / (this.speed * this.speedMult * 4);
                if (Math.abs(this.target - this.current) <= dstStop) {
                    this.mode = 0 /* STOP */;
                }
            }
            let targetSpeed = this.mode === 0 /* STOP */ ? 0 : this.speed * this.speedMult;
            if (this.target < this.current) {
                targetSpeed = -targetSpeed;
            }
            if (this.currentSpeed < targetSpeed) {
                this.currentSpeed = Math.min(
                    targetSpeed,
                    this.currentSpeed + elapsed / 1e3 * this.speed * this.speedMult * 2
                );
            } else if (this.currentSpeed > targetSpeed) {
                this.currentSpeed = Math.max(
                    targetSpeed,
                    this.currentSpeed - elapsed / 1e3 * this.speed * this.speedMult * 2
                );
            }
            let next = null;
            if (this.current > this.target && this.currentSpeed) {
                next = Math.max(this.target, this.current + this.currentSpeed * elapsed / 1e3);
            } else if (this.current < this.target && this.currentSpeed) {
                next = Math.min(this.target, this.current + this.currentSpeed * elapsed / 1e3);
            }
            if (next !== null) {
                next = this.wrap ? wrap(next, this.max) : import_three2.MathUtils.clamp(next, this.min, this.max);
                if (next !== this.current) {
                    this.current = next;
                    if (this.fn) {
                        this.fn(this.current);
                    }
                    return true;
                }
            }
            return false;
        }
    };

    // src/utils/MultiDynamic.ts
    var MultiDynamic = class {
        constructor(fn, dynamics) {
            this.fn = fn;
            this.dynamics = dynamics;
            if (this.fn) {
                this.fn(this.current);
            }
        }
        get current() {
            return Object.entries(this.dynamics).reduce(
                (values, [name, dynamic]) => {
                    values[name] = dynamic.current;
                    return values;
                },
                {}
            );
        }
        /**
         * Changes base speed
         */
        setSpeed(speed) {
            for (const d of Object.values(this.dynamics)) {
                d.setSpeed(speed);
            }
        }
        /**
         * Defines the target positions
         */
        goto(positions, speedMult = 1) {
            for (const [name, position] of Object.entries(positions)) {
                this.dynamics[name].goto(position, speedMult);
            }
        }
        /**
         * Increase/decrease the target positions
         */
        step(steps, speedMult = 1) {
            if (speedMult === 0) {
                this.setValue(
                    Object.keys(steps).reduce(
                        (values, name) => {
                            values[name] = steps[name] + this.dynamics[name].current;
                            return values;
                        },
                        {}
                    )
                );
            } else {
                for (const [name, step] of Object.entries(steps)) {
                    this.dynamics[name].step(step, speedMult);
                }
            }
        }
        /**
         * Starts infinite movements
         */
        roll(rolls, speedMult = 1) {
            for (const [name, roll] of Object.entries(rolls)) {
                this.dynamics[name].roll(roll, speedMult);
            }
        }
        /**
         * Stops movements
         */
        stop() {
            for (const d of Object.values(this.dynamics)) {
                d.stop();
            }
        }
        /**
         * Defines the current positions and immediately stops movements
         */
        setValue(values) {
            let hasUpdates = false;
            for (const [name, value] of Object.entries(values)) {
                hasUpdates = this.dynamics[name].setValue(value) || hasUpdates;
            }
            if (hasUpdates && this.fn) {
                this.fn(this.current);
            }
            return hasUpdates;
        }
        /**
         * @internal
         */
        update(elapsed) {
            let hasUpdates = false;
            for (const d of Object.values(this.dynamics)) {
                hasUpdates = d.update(elapsed) || hasUpdates;
            }
            if (hasUpdates && this.fn) {
                this.fn(this.current);
            }
            return hasUpdates;
        }
    };

    // src/utils/PressHandler.ts
    var PressHandler = class {
        constructor(delay = 200) {
            this.delay = delay;
            this.time = 0;
            this.delay = delay;
        }
        get pending() {
            return this.time !== 0;
        }
        down() {
            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = void 0;
            }
            this.time = (/* @__PURE__ */ new Date()).getTime();
        }
        up(cb) {
            if (!this.time) {
                return;
            }
            const elapsed = Date.now() - this.time;
            if (elapsed < this.delay) {
                this.timeout = setTimeout(() => {
                    cb();
                    this.timeout = void 0;
                    this.time = 0;
                }, this.delay);
            } else {
                cb();
                this.time = 0;
            }
        }
    };

    // src/utils/Slider.ts
    var SliderDirection = /* @__PURE__ */ ((SliderDirection2) => {
        SliderDirection2["VERTICAL"] = "VERTICAL";
        SliderDirection2["HORIZONTAL"] = "HORIZONTAL";
        return SliderDirection2;
    })(SliderDirection || {});
    var Slider = class {
        constructor(container, direction, listener) {
            this.container = container;
            this.direction = direction;
            this.listener = listener;
            this.mousedown = false;
            this.mouseover = false;
            this.container.addEventListener("click", this);
            this.container.addEventListener("mousedown", this);
            this.container.addEventListener("mouseenter", this);
            this.container.addEventListener("mouseleave", this);
            this.container.addEventListener("touchstart", this);
            this.container.addEventListener("mousemove", this, true);
            this.container.addEventListener("touchmove", this, true);
            window.addEventListener("mouseup", this);
            window.addEventListener("touchend", this);
        }
        get isVertical() {
            return this.direction === "VERTICAL" /* VERTICAL */;
        }
        get isHorizontal() {
            return this.direction === "HORIZONTAL" /* HORIZONTAL */;
        }
        destroy() {
            window.removeEventListener("mouseup", this);
            window.removeEventListener("touchend", this);
        }
        /**
         * @internal
         */
        handleEvent(e) {
            switch (e.type) {
                case "click":
                    e.stopPropagation();
                    break;
                case "mousedown":
                    this.__onMouseDown(e);
                    break;
                case "mouseenter":
                    this.__onMouseEnter(e);
                    break;
                case "mouseleave":
                    this.__onMouseLeave(e);
                    break;
                case "touchstart":
                    this.__onTouchStart(e);
                    break;
                case "mousemove":
                    this.__onMouseMove(e);
                    break;
                case "touchmove":
                    this.__onTouchMove(e);
                    break;
                case "mouseup":
                    this.__onMouseUp(e);
                    break;
                case "touchend":
                    this.__onTouchEnd(e);
                    break;
            }
        }
        __onMouseDown(evt) {
            this.mousedown = true;
            this.__update(evt.clientX, evt.clientY, true);
        }
        __onMouseEnter(evt) {
            this.mouseover = true;
            this.__update(evt.clientX, evt.clientY, true);
        }
        __onTouchStart(evt) {
            this.mouseover = true;
            this.mousedown = true;
            const touch = evt.changedTouches[0];
            this.__update(touch.clientX, touch.clientY, true);
        }
        __onMouseMove(evt) {
            if (this.mousedown || this.mouseover) {
                evt.stopPropagation();
                this.__update(evt.clientX, evt.clientY, true);
            }
        }
        __onTouchMove(evt) {
            if (this.mousedown || this.mouseover) {
                evt.stopPropagation();
                const touch = evt.changedTouches[0];
                this.__update(touch.clientX, touch.clientY, true);
            }
        }
        __onMouseUp(evt) {
            if (this.mousedown) {
                this.mousedown = false;
                this.__update(evt.clientX, evt.clientY, false);
            }
        }
        __onMouseLeave(evt) {
            if (this.mouseover) {
                this.mouseover = false;
                this.__update(evt.clientX, evt.clientY, true);
            }
        }
        __onTouchEnd(evt) {
            if (this.mousedown) {
                this.mouseover = false;
                this.mousedown = false;
                const touch = evt.changedTouches[0];
                this.__update(touch.clientX, touch.clientY, false);
            }
        }
        __update(clientX, clientY, moving) {
            const boundingClientRect = this.container.getBoundingClientRect();
            const cursor = this.isVertical ? clientY : clientX;
            const pos = boundingClientRect[this.isVertical ? "bottom" : "left"];
            const size = boundingClientRect[this.isVertical ? "height" : "width"];
            const val = Math.abs((pos - cursor) / size);
            this.listener({
                value: val,
                click: !moving,
                mousedown: this.mousedown,
                mouseover: this.mouseover,
                cursor: { clientX, clientY }
            });
        }
    };

    // src/events.ts
    var events_exports = {};
    __export(events_exports, {
        BeforeAnimateEvent: () => BeforeAnimateEvent,
        BeforeRenderEvent: () => BeforeRenderEvent,
        BeforeRotateEvent: () => BeforeRotateEvent,
        ClickEvent: () => ClickEvent,
        ConfigChangedEvent: () => ConfigChangedEvent,
        DoubleClickEvent: () => DoubleClickEvent,
        FullscreenEvent: () => FullscreenEvent,
        HideNotificationEvent: () => HideNotificationEvent,
        HideOverlayEvent: () => HideOverlayEvent,
        HidePanelEvent: () => HidePanelEvent,
        HideTooltipEvent: () => HideTooltipEvent,
        KeypressEvent: () => KeypressEvent,
        LoadProgressEvent: () => LoadProgressEvent,
        ObjectEnterEvent: () => ObjectEnterEvent,
        ObjectEvent: () => ObjectEvent,
        ObjectHoverEvent: () => ObjectHoverEvent,
        ObjectLeaveEvent: () => ObjectLeaveEvent,
        PanoramaErrorEvent: () => PanoramaErrorEvent,
        PanoramaLoadedEvent: () => PanoramaLoadedEvent,
        PositionUpdatedEvent: () => PositionUpdatedEvent,
        ReadyEvent: () => ReadyEvent,
        RenderEvent: () => RenderEvent,
        ShowNotificationEvent: () => ShowNotificationEvent,
        ShowOverlayEvent: () => ShowOverlayEvent,
        ShowPanelEvent: () => ShowPanelEvent,
        ShowTooltipEvent: () => ShowTooltipEvent,
        SizeUpdatedEvent: () => SizeUpdatedEvent,
        StopAllEvent: () => StopAllEvent,
        ViewerEvent: () => ViewerEvent,
        ZoomUpdatedEvent: () => ZoomUpdatedEvent
    });

    // src/lib/TypedEventTarget.ts
    var TypedEvent = class extends Event {
        constructor(type, cancelable = false) {
            super(type, { cancelable });
        }
    };
    var TypedEventTarget = class extends EventTarget {
        dispatchEvent(e) {
            return super.dispatchEvent(e);
        }
        /**
         * @template T the name of event
         * @template E the class of the event
         */
        addEventListener(type, callback, options) {
            super.addEventListener(type, callback, options);
        }
        /**
         * @template T the name of event
         * @template E the class of the event
         */
        removeEventListener(type, callback, options) {
            super.removeEventListener(type, callback, options);
        }
    };

    // src/events.ts
    var ViewerEvent = class extends TypedEvent {
    };
    var _BeforeAnimateEvent = class _BeforeAnimateEvent extends ViewerEvent {
        /** @internal */
        constructor(position, zoomLevel) {
            super(_BeforeAnimateEvent.type, true);
            this.position = position;
            this.zoomLevel = zoomLevel;
        }
    };
    _BeforeAnimateEvent.type = "before-animate";
    var BeforeAnimateEvent = _BeforeAnimateEvent;
    var _BeforeRenderEvent = class _BeforeRenderEvent extends ViewerEvent {
        /** @internal */
        constructor(timestamp, elapsed) {
            super(_BeforeRenderEvent.type);
            this.timestamp = timestamp;
            this.elapsed = elapsed;
        }
    };
    _BeforeRenderEvent.type = "before-render";
    var BeforeRenderEvent = _BeforeRenderEvent;
    var _BeforeRotateEvent = class _BeforeRotateEvent extends ViewerEvent {
        /** @internal */
        constructor(position) {
            super(_BeforeRotateEvent.type, true);
            this.position = position;
        }
    };
    _BeforeRotateEvent.type = "before-rotate";
    var BeforeRotateEvent = _BeforeRotateEvent;
    var _ClickEvent = class _ClickEvent extends ViewerEvent {
        /** @internal */
        constructor(data) {
            super(_ClickEvent.type);
            this.data = data;
        }
    };
    _ClickEvent.type = "click";
    var ClickEvent = _ClickEvent;
    var _ConfigChangedEvent = class _ConfigChangedEvent extends ViewerEvent {
        /** @internal */
        constructor(options) {
            super(_ConfigChangedEvent.type);
            this.options = options;
        }
        /**
         * Checks if at least one of the `options` has been modified
         */
        containsOptions(...options) {
            return options.some((option) => this.options.includes(option));
        }
    };
    _ConfigChangedEvent.type = "config-changed";
    var ConfigChangedEvent = _ConfigChangedEvent;
    var _DoubleClickEvent = class _DoubleClickEvent extends ViewerEvent {
        /** @internal */
        constructor(data) {
            super(_DoubleClickEvent.type);
            this.data = data;
        }
    };
    _DoubleClickEvent.type = "dblclick";
    var DoubleClickEvent = _DoubleClickEvent;
    var _FullscreenEvent = class _FullscreenEvent extends ViewerEvent {
        /** @internal */
        constructor(fullscreenEnabled) {
            super(_FullscreenEvent.type);
            this.fullscreenEnabled = fullscreenEnabled;
        }
    };
    _FullscreenEvent.type = "fullscreen";
    var FullscreenEvent = _FullscreenEvent;
    var _HideNotificationEvent = class _HideNotificationEvent extends ViewerEvent {
        /** @internal */
        constructor(notificationId) {
            super(_HideNotificationEvent.type);
            this.notificationId = notificationId;
        }
    };
    _HideNotificationEvent.type = "hide-notification";
    var HideNotificationEvent = _HideNotificationEvent;
    var _HideOverlayEvent = class _HideOverlayEvent extends ViewerEvent {
        /** @internal */
        constructor(overlayId) {
            super(_HideOverlayEvent.type);
            this.overlayId = overlayId;
        }
    };
    _HideOverlayEvent.type = "hide-overlay";
    var HideOverlayEvent = _HideOverlayEvent;
    var _HidePanelEvent = class _HidePanelEvent extends ViewerEvent {
        /** @internal */
        constructor(panelId) {
            super(_HidePanelEvent.type);
            this.panelId = panelId;
        }
    };
    _HidePanelEvent.type = "hide-panel";
    var HidePanelEvent = _HidePanelEvent;
    var _HideTooltipEvent = class _HideTooltipEvent extends ViewerEvent {
        /** @internal */
        constructor(tooltipData) {
            super(_HideTooltipEvent.type);
            this.tooltipData = tooltipData;
        }
    };
    _HideTooltipEvent.type = "hide-tooltip";
    var HideTooltipEvent = _HideTooltipEvent;
    var _KeypressEvent = class _KeypressEvent extends ViewerEvent {
        /** @internal */
        constructor(key) {
            super(_KeypressEvent.type, true);
            this.key = key;
        }
    };
    _KeypressEvent.type = "key-press";
    var KeypressEvent = _KeypressEvent;
    var _LoadProgressEvent = class _LoadProgressEvent extends ViewerEvent {
        /** @internal */
        constructor(progress) {
            super(_LoadProgressEvent.type);
            this.progress = progress;
        }
    };
    _LoadProgressEvent.type = "load-progress";
    var LoadProgressEvent = _LoadProgressEvent;
    var _PanoramaLoadedEvent = class _PanoramaLoadedEvent extends ViewerEvent {
        /** @internal */
        constructor(data) {
            super(_PanoramaLoadedEvent.type);
            this.data = data;
        }
    };
    _PanoramaLoadedEvent.type = "panorama-loaded";
    var PanoramaLoadedEvent = _PanoramaLoadedEvent;
    var _PanoramaErrorEvent = class _PanoramaErrorEvent extends ViewerEvent {
        /** @internal */
        constructor(panorama, error) {
            super(_PanoramaErrorEvent.type);
            this.panorama = panorama;
            this.error = error;
        }
    };
    _PanoramaErrorEvent.type = "panorama-error";
    var PanoramaErrorEvent = _PanoramaErrorEvent;
    var _PositionUpdatedEvent = class _PositionUpdatedEvent extends ViewerEvent {
        /** @internal */
        constructor(position) {
            super(_PositionUpdatedEvent.type);
            this.position = position;
        }
    };
    _PositionUpdatedEvent.type = "position-updated";
    var PositionUpdatedEvent = _PositionUpdatedEvent;
    var _ReadyEvent = class _ReadyEvent extends ViewerEvent {
        /** @internal */
        constructor() {
            super(_ReadyEvent.type);
        }
    };
    _ReadyEvent.type = "ready";
    var ReadyEvent = _ReadyEvent;
    var _RenderEvent = class _RenderEvent extends ViewerEvent {
        /** @internal */
        constructor() {
            super(_RenderEvent.type);
        }
    };
    _RenderEvent.type = "render";
    var RenderEvent = _RenderEvent;
    var _ShowNotificationEvent = class _ShowNotificationEvent extends ViewerEvent {
        /** @internal */
        constructor(notificationId) {
            super(_ShowNotificationEvent.type);
            this.notificationId = notificationId;
        }
    };
    _ShowNotificationEvent.type = "show-notification";
    var ShowNotificationEvent = _ShowNotificationEvent;
    var _ShowOverlayEvent = class _ShowOverlayEvent extends ViewerEvent {
        /** @internal */
        constructor(overlayId) {
            super(_ShowOverlayEvent.type);
            this.overlayId = overlayId;
        }
    };
    _ShowOverlayEvent.type = "show-overlay";
    var ShowOverlayEvent = _ShowOverlayEvent;
    var _ShowPanelEvent = class _ShowPanelEvent extends ViewerEvent {
        /** @internal */
        constructor(panelId) {
            super(_ShowPanelEvent.type);
            this.panelId = panelId;
        }
    };
    _ShowPanelEvent.type = "show-panel";
    var ShowPanelEvent = _ShowPanelEvent;
    var _ShowTooltipEvent = class _ShowTooltipEvent extends ViewerEvent {
        /** @internal */
        constructor(tooltip, tooltipData) {
            super(_ShowTooltipEvent.type);
            this.tooltip = tooltip;
            this.tooltipData = tooltipData;
        }
    };
    _ShowTooltipEvent.type = "show-tooltip";
    var ShowTooltipEvent = _ShowTooltipEvent;
    var _SizeUpdatedEvent = class _SizeUpdatedEvent extends ViewerEvent {
        /** @internal */
        constructor(size) {
            super(_SizeUpdatedEvent.type);
            this.size = size;
        }
    };
    _SizeUpdatedEvent.type = "size-updated";
    var SizeUpdatedEvent = _SizeUpdatedEvent;
    var _StopAllEvent = class _StopAllEvent extends ViewerEvent {
        /** @internal */
        constructor() {
            super(_StopAllEvent.type);
        }
    };
    _StopAllEvent.type = "stop-all";
    var StopAllEvent = _StopAllEvent;
    var _ZoomUpdatedEvent = class _ZoomUpdatedEvent extends ViewerEvent {
        /** @internal */
        constructor(zoomLevel) {
            super(_ZoomUpdatedEvent.type);
            this.zoomLevel = zoomLevel;
        }
    };
    _ZoomUpdatedEvent.type = "zoom-updated";
    var ZoomUpdatedEvent = _ZoomUpdatedEvent;
    var ObjectEvent = class extends ViewerEvent {
        /** @internal */
        constructor(type, originalEvent, object, viewerPoint, userDataKey) {
            super(type);
            this.originalEvent = originalEvent;
            this.object = object;
            this.viewerPoint = viewerPoint;
            this.userDataKey = userDataKey;
        }
    };
    var _ObjectEnterEvent = class _ObjectEnterEvent extends ObjectEvent {
        /** @internal */
        constructor(originalEvent, object, viewerPoint, userDataKey) {
            super(_ObjectEnterEvent.type, originalEvent, object, viewerPoint, userDataKey);
        }
    };
    _ObjectEnterEvent.type = "enter-object";
    var ObjectEnterEvent = _ObjectEnterEvent;
    var _ObjectLeaveEvent = class _ObjectLeaveEvent extends ObjectEvent {
        /** @internal */
        constructor(originalEvent, object, viewerPoint, userDataKey) {
            super(_ObjectLeaveEvent.type, originalEvent, object, viewerPoint, userDataKey);
        }
    };
    _ObjectLeaveEvent.type = "leave-object";
    var ObjectLeaveEvent = _ObjectLeaveEvent;
    var _ObjectHoverEvent = class _ObjectHoverEvent extends ObjectEvent {
        /** @internal */
        constructor(originalEvent, object, viewerPoint, userDataKey) {
            super(_ObjectHoverEvent.type, originalEvent, object, viewerPoint, userDataKey);
        }
    };
    _ObjectHoverEvent.type = "hover-object";
    var ObjectHoverEvent = _ObjectHoverEvent;

    // src/adapters/AbstractAdapter.ts
    var import_three3 = require_three();
    var _AbstractAdapter = class _AbstractAdapter {
        constructor(viewer) {
            this.viewer = viewer;
        }
        /**
         * Initializes the adapter
         */
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        init() {
        }
        /**
         * Destroys the adapter
         */
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        destroy() {
        }
        /**
         * Indicates if the adapter supports transitions between panoramas
         */
        // @ts-ignore unused paramater
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        supportsTransition(panorama) {
            return false;
        }
        /**
         * Indicates if the adapter supports preload of a panorama
         */
        // @ts-ignore unused paramater
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        supportsPreload(panorama) {
            return false;
        }
        /**
         * @internal
         */
        static createOverlayMaterial({
                                         additionalUniforms,
                                         overrideVertexShader
                                     } = {}) {
            return new import_three3.ShaderMaterial({
                uniforms: {
                    ...additionalUniforms,
                    [_AbstractAdapter.OVERLAY_UNIFORMS.panorama]: { value: null },
                    [_AbstractAdapter.OVERLAY_UNIFORMS.overlay]: { value: null },
                    [_AbstractAdapter.OVERLAY_UNIFORMS.globalOpacity]: { value: 1 },
                    [_AbstractAdapter.OVERLAY_UNIFORMS.overlayOpacity]: { value: 0 }
                },
                vertexShader: overrideVertexShader || `
varying vec2 vUv;

void main() {
  vUv = uv;
  gl_Position = projectionMatrix *  modelViewMatrix * vec4( position, 1.0 );
}`,
                fragmentShader: `
uniform sampler2D ${_AbstractAdapter.OVERLAY_UNIFORMS.panorama};
uniform sampler2D ${_AbstractAdapter.OVERLAY_UNIFORMS.overlay};
uniform float ${_AbstractAdapter.OVERLAY_UNIFORMS.globalOpacity};
uniform float ${_AbstractAdapter.OVERLAY_UNIFORMS.overlayOpacity};

varying vec2 vUv;

void main() {
  vec4 tColor1 = texture2D( ${_AbstractAdapter.OVERLAY_UNIFORMS.panorama}, vUv );
  vec4 tColor2 = texture2D( ${_AbstractAdapter.OVERLAY_UNIFORMS.overlay}, vUv );
  gl_FragColor = vec4(
    mix( tColor1.rgb, tColor2.rgb, tColor2.a * ${_AbstractAdapter.OVERLAY_UNIFORMS.overlayOpacity} ),
    ${_AbstractAdapter.OVERLAY_UNIFORMS.globalOpacity}
  );
}`
            });
        }
    };
    /**
     * Indicates if the adapter supports panorama download natively
     */
    _AbstractAdapter.supportsDownload = false;
    /**
     * Indicates if the adapter can display an additional transparent image above the panorama
     */
    _AbstractAdapter.supportsOverlay = false;
    /**
     * @internal
     */
    _AbstractAdapter.OVERLAY_UNIFORMS = {
        panorama: "panorama",
        overlay: "overlay",
        globalOpacity: "globalOpacity",
        overlayOpacity: "overlayOpacity"
    };
    var AbstractAdapter = _AbstractAdapter;
    function adapterInterop(adapter) {
        if (adapter) {
            for (const [, p] of [["_", adapter], ...Object.entries(adapter)]) {
                if (p.prototype instanceof AbstractAdapter) {
                    return p;
                }
            }
        }
        return null;
    }

    // src/adapters/EquirectangularAdapter.ts
    var import_three4 = require_three();

    // src/data/system.ts
    var LOCALSTORAGE_TOUCH_SUPPORT = `${VIEWER_DATA}_touchSupport`;
    var SYSTEM = {
        /**
         * Indicates if the system data has been loaded
         */
        loaded: false,
        /**
         * Device screen pixel ratio
         */
        pixelRatio: 1,
        /**
         * Device supports WebGL
         */
        isWebGLSupported: false,
        /**
         * Maximum WebGL texture width
         */
        maxTextureWidth: 0,
        /**
         * Device supports touch events
         */
        isTouchEnabled: null,
        /**
         * Name of the fullscreen event
         */
        fullscreenEvent: null,
        /**
         * @internal
         */
        __maxCanvasWidth: null,
        /**
         * Maximum canvas width
         */
        get maxCanvasWidth() {
            if (this.__maxCanvasWidth === null) {
                this.__maxCanvasWidth = getMaxCanvasWidth(this.maxTextureWidth);
            }
            return this.__maxCanvasWidth;
        },
        /**
         * Loads the system if not already loaded
         * @internal
         */
        load() {
            if (!this.loaded) {
                const ctx = getWebGLCtx();
                this.pixelRatio = window.devicePixelRatio || 1;
                this.isWebGLSupported = ctx !== null;
                this.maxTextureWidth = ctx ? ctx.getParameter(ctx.MAX_TEXTURE_SIZE) : 0;
                this.isTouchEnabled = isTouchEnabled();
                this.fullscreenEvent = getFullscreenEvent();
                this.loaded = true;
            }
            if (!SYSTEM.isWebGLSupported) {
                throw new PSVError("WebGL is not supported.");
            }
            if (SYSTEM.maxTextureWidth === 0) {
                throw new PSVError("Unable to detect system capabilities");
            }
        }
    };
    function getWebGLCtx() {
        const canvas = document.createElement("canvas");
        const names = ["webgl2", "webgl", "experimental-webgl", "moz-webgl", "webkit-3d"];
        let context = null;
        if (!canvas.getContext) {
            return null;
        }
        if (names.some((name) => {
            try {
                context = canvas.getContext(name);
                return context !== null;
            } catch (e) {
                return false;
            }
        })) {
            return context;
        } else {
            return null;
        }
    }
    function isTouchEnabled() {
        let initial = "ontouchstart" in window || navigator.maxTouchPoints > 0;
        if (LOCALSTORAGE_TOUCH_SUPPORT in localStorage) {
            initial = localStorage[LOCALSTORAGE_TOUCH_SUPPORT] === "true";
        }
        const promise = new Promise((resolve) => {
            const clear = () => {
                window.removeEventListener("mousedown", listenerMouse);
                window.removeEventListener("touchstart", listenerTouch);
                clearTimeout(listenerTimeoutId);
            };
            const listenerMouse = () => {
                clear();
                localStorage[LOCALSTORAGE_TOUCH_SUPPORT] = false;
                resolve(false);
            };
            const listenerTouch = () => {
                clear();
                localStorage[LOCALSTORAGE_TOUCH_SUPPORT] = true;
                resolve(true);
            };
            const listenerTimeout = () => {
                clear();
                localStorage[LOCALSTORAGE_TOUCH_SUPPORT] = initial;
                resolve(initial);
            };
            window.addEventListener("mousedown", listenerMouse, false);
            window.addEventListener("touchstart", listenerTouch, false);
            const listenerTimeoutId = setTimeout(listenerTimeout, 1e4);
        });
        return { initial, promise };
    }
    function getMaxCanvasWidth(maxWidth) {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        canvas.width = maxWidth;
        canvas.height = maxWidth / 2;
        while (canvas.width > 1024) {
            ctx.fillStyle = "white";
            ctx.fillRect(0, 0, 1, 1);
            try {
                if (ctx.getImageData(0, 0, 1, 1).data[0] > 0) {
                    return canvas.width;
                }
            } catch (e) {
            }
            canvas.width /= 2;
            canvas.height /= 2;
        }
        throw new PSVError("Unable to detect system capabilities");
    }
    function getFullscreenEvent() {
        if ("exitFullscreen" in document) {
            return "fullscreenchange";
        }
        if ("webkitExitFullscreen" in document) {
            return "webkitfullscreenchange";
        }
        return null;
    }

    // src/adapters/interpolationWorker.ts
    function interpolationWorker() {
        self.onmessage = (e) => {
            const panoData = e.data.panoData;
            const buffer = new OffscreenCanvas(panoData.fullWidth, panoData.fullHeight);
            const ctx = buffer.getContext("2d");
            const img = new OffscreenCanvas(panoData.croppedWidth, panoData.croppedHeight);
            const ctxImg = img.getContext("2d");
            ctxImg.putImageData(e.data.image, 0, 0);
            autoBackground(buffer, img, panoData);
            postMessage(ctx.getImageData(0, 0, buffer.width, buffer.height));
        };
        function autoBackground(buffer, img, panoData) {
            const croppedY2 = panoData.fullHeight - panoData.croppedHeight - panoData.croppedY;
            const croppedX2 = panoData.fullWidth - panoData.croppedWidth - panoData.croppedX;
            const middleY = panoData.croppedY + panoData.croppedHeight / 2;
            const blurSize = buffer.width / 32;
            const padding = blurSize;
            const edge = 10;
            const filter = `blur(${blurSize}px)`;
            const ctx = buffer.getContext("2d");
            ctx.drawImage(
                img,
                panoData.croppedX,
                panoData.croppedY,
                panoData.croppedWidth,
                panoData.croppedHeight
            );
            if (panoData.croppedY > 0) {
                if (panoData.croppedX > 0 || croppedX2 > 0) {
                    ctx.filter = "none";
                    const colorLeft = getAverageColor(ctx, panoData.croppedX, panoData.croppedY, edge, edge, 2);
                    const colorRight = getAverageColor(ctx, buffer.width - croppedX2 - 11, panoData.croppedY, edge, edge, 2);
                    const colorCenter = averageRgb(colorLeft, colorRight);
                    if (panoData.croppedX > 0) {
                        ctx.fillStyle = createHorizontalGradient(ctx, 0, panoData.croppedX, colorCenter, colorLeft);
                        ctx.fillRect(-padding, -padding, panoData.croppedX + padding * 2, middleY + padding);
                    }
                    if (croppedX2 > 0) {
                        ctx.fillStyle = createHorizontalGradient(ctx, buffer.width - croppedX2, buffer.width, colorRight, colorCenter);
                        ctx.fillRect(buffer.width - croppedX2 - padding, -padding, croppedX2 + padding * 2, middleY + padding);
                    }
                }
                ctx.filter = filter;
                ctx.drawImage(
                    img,
                    0,
                    0,
                    img.width,
                    edge,
                    panoData.croppedX,
                    -padding,
                    panoData.croppedWidth,
                    panoData.croppedY + padding * 2
                );
                ctx.fillStyle = rgbCss(getAverageColor(ctx, 0, 0, buffer.width, edge, edge));
                ctx.fillRect(-padding, -padding, buffer.width + padding * 2, padding * 2);
            }
            if (croppedY2 > 0) {
                if (panoData.croppedX > 0 || croppedX2 > 0) {
                    ctx.filter = "none";
                    const colorLeft = getAverageColor(ctx, panoData.croppedX, buffer.height - croppedY2 - 1 - edge, edge, edge, 2);
                    const colorRight = getAverageColor(ctx, buffer.width - croppedX2 - 1 - edge, buffer.height - croppedY2 - 1 - edge, edge, edge, 2);
                    const colorCenter = averageRgb(colorLeft, colorRight);
                    if (panoData.croppedX > 0) {
                        ctx.fillStyle = createHorizontalGradient(ctx, 0, panoData.croppedX, colorCenter, colorLeft);
                        ctx.fillRect(-padding, middleY, panoData.croppedX + padding * 2, buffer.height - middleY + padding);
                    }
                    if (croppedX2 > 0) {
                        ctx.fillStyle = createHorizontalGradient(ctx, buffer.width - croppedX2, buffer.width, colorRight, colorCenter);
                        ctx.fillRect(buffer.width - croppedX2 - padding, middleY, croppedX2 + padding * 2, buffer.height - middleY + padding);
                    }
                }
                ctx.filter = filter;
                ctx.drawImage(
                    img,
                    0,
                    img.height - edge,
                    img.width,
                    edge,
                    panoData.croppedX,
                    buffer.height - croppedY2 - padding,
                    panoData.croppedWidth,
                    croppedY2 + padding * 2
                );
                ctx.fillStyle = rgbCss(getAverageColor(ctx, 0, buffer.height - 1 - edge, buffer.width, edge, edge));
                ctx.fillRect(-padding, buffer.height - padding, buffer.width + padding * 2, padding * 2);
            }
            if (panoData.croppedX > 0) {
                ctx.filter = filter;
                ctx.drawImage(
                    img,
                    img.width - edge,
                    0,
                    edge,
                    img.height,
                    -padding,
                    panoData.croppedY,
                    padding * 2,
                    panoData.croppedHeight
                );
                ctx.drawImage(
                    img,
                    0,
                    0,
                    edge,
                    img.height,
                    0,
                    panoData.croppedY,
                    panoData.croppedX + padding,
                    panoData.croppedHeight
                );
            }
            if (croppedX2 > 0) {
                ctx.filter = filter;
                ctx.drawImage(
                    img,
                    0,
                    0,
                    edge,
                    img.height,
                    buffer.width - padding,
                    panoData.croppedY,
                    padding * 2,
                    panoData.croppedHeight
                );
                ctx.drawImage(
                    img,
                    img.width - edge,
                    0,
                    edge,
                    img.height,
                    buffer.width - croppedX2 - padding,
                    panoData.croppedY,
                    croppedX2 + padding,
                    panoData.croppedHeight
                );
            }
            ctx.filter = "none";
            ctx.drawImage(
                img,
                panoData.croppedX,
                panoData.croppedY,
                panoData.croppedWidth,
                panoData.croppedHeight
            );
        }
        function rgbCss(color) {
            return `rgb(${color.r}, ${color.g}, ${color.b})`;
        }
        function averageRgb(c1, c2) {
            return {
                r: Math.round(c1.r / 2 + c2.r / 2),
                g: Math.round(c1.g / 2 + c2.g / 2),
                b: Math.round(c1.b / 2 + c2.b / 2)
            };
        }
        function createHorizontalGradient(ctx, x1, x2, c1, c2) {
            const grad = ctx.createLinearGradient(x1, 0, x2, 0);
            grad.addColorStop(0, rgbCss(c1));
            grad.addColorStop(1, rgbCss(c2));
            return grad;
        }
        function getAverageColor(ctx, x, y, w, h, every) {
            every = Math.round(every);
            let r = 0;
            let g = 0;
            let b = 0;
            let count = 0;
            const data = ctx.getImageData(x, y, w, h);
            for (let row = 0; row < h; row += every) {
                for (let col = 0; col < w; col += every) {
                    const i = 4 * (row * w + col);
                    r += data.data[i];
                    g += data.data[i + 1];
                    b += data.data[i + 2];
                    count++;
                }
            }
            r = Math.round(r / count);
            g = Math.round(g / count);
            b = Math.round(b / count);
            return { r, g, b };
        }
    }
    var interpolationWorkerSrc = URL.createObjectURL(
        new Blob(
            ["(", interpolationWorker.toString(), ")()"],
            { type: "application/javascript" }
        )
    );

    // src/adapters/EquirectangularAdapter.ts
    var getConfig = getConfigParser(
        {
            backgroundColor: "#000",
            interpolateBackground: false,
            resolution: 64,
            useXmpData: true,
            blur: false
        },
        {
            resolution: (resolution) => {
                if (!resolution || !import_three4.MathUtils.isPowerOfTwo(resolution)) {
                    throw new PSVError("EquirectangularAdapter resolution must be power of two");
                }
                return resolution;
            }
        }
    );
    var EquirectangularAdapter = class extends AbstractAdapter {
        constructor(viewer, config) {
            super(viewer);
            this.config = getConfig(config);
            if (!isNil(this.viewer.config.useXmpData)) {
                this.config.useXmpData = this.viewer.config.useXmpData;
            }
            if (!isNil(this.viewer.config.canvasBackground)) {
                this.config.backgroundColor = this.viewer.config.canvasBackground;
            }
            if (this.config.interpolateBackground) {
                if (!window.Worker) {
                    logWarn("Web Worker API not available");
                    this.config.interpolateBackground = false;
                } else {
                    this.interpolationWorker = new Worker(interpolationWorkerSrc);
                }
            }
            this.SPHERE_SEGMENTS = this.config.resolution;
            this.SPHERE_HORIZONTAL_SEGMENTS = this.SPHERE_SEGMENTS / 2;
        }
        supportsTransition() {
            return true;
        }
        supportsPreload() {
            return true;
        }
        destroy() {
            this.interpolationWorker?.terminate();
            super.destroy();
        }
        async loadTexture(panorama, newPanoData, useXmpPanoData = this.config.useXmpData) {
            if (typeof panorama !== "string") {
                return Promise.reject(new PSVError("Invalid panorama url, are you using the right adapter?"));
            }
            const blob = await this.viewer.textureLoader.loadFile(panorama, (p) => this.viewer.loader.setProgress(p), panorama);
            const xmpPanoData = useXmpPanoData ? await this.loadXMP(blob) : null;
            const img = await this.viewer.textureLoader.blobToImage(blob);
            if (typeof newPanoData === "function") {
                newPanoData = newPanoData(img, xmpPanoData);
            }
            if (!newPanoData && !xmpPanoData) {
                newPanoData = this.__defaultPanoData(img);
            }
            const panoData = {
                fullWidth: firstNonNull(newPanoData?.fullWidth, xmpPanoData?.fullWidth, img.width),
                fullHeight: firstNonNull(newPanoData?.fullHeight, xmpPanoData?.fullHeight, img.height),
                croppedWidth: firstNonNull(newPanoData?.croppedWidth, xmpPanoData?.croppedWidth, img.width),
                croppedHeight: firstNonNull(newPanoData?.croppedHeight, xmpPanoData?.croppedHeight, img.height),
                croppedX: firstNonNull(newPanoData?.croppedX, xmpPanoData?.croppedX, 0),
                croppedY: firstNonNull(newPanoData?.croppedY, xmpPanoData?.croppedY, 0),
                poseHeading: firstNonNull(newPanoData?.poseHeading, xmpPanoData?.poseHeading, 0),
                posePitch: firstNonNull(newPanoData?.posePitch, xmpPanoData?.posePitch, 0),
                poseRoll: firstNonNull(newPanoData?.poseRoll, xmpPanoData?.poseRoll, 0)
            };
            if (panoData.croppedWidth !== img.width || panoData.croppedHeight !== img.height) {
                logWarn(`Invalid panoData, croppedWidth/croppedHeight is not coherent with the loaded image.
            panoData: ${panoData.croppedWidth}x${panoData.croppedHeight}, image: ${img.width}x${img.height}`);
            }
            if (Math.abs(panoData.fullWidth - panoData.fullHeight * 2) > 1) {
                logWarn("Invalid panoData, fullWidth should be twice fullHeight");
                panoData.fullWidth = panoData.fullHeight * 2;
            }
            if (panoData.croppedX + panoData.croppedWidth > panoData.fullWidth) {
                logWarn("Invalid panoData, croppedX + croppedWidth > fullWidth");
                panoData.croppedX = panoData.fullWidth - panoData.croppedWidth;
            }
            if (panoData.croppedY + panoData.croppedHeight > panoData.fullHeight) {
                logWarn("Invalid panoData, croppedY + croppedHeight > fullHeight");
                panoData.croppedY = panoData.fullHeight - panoData.croppedHeight;
            }
            const texture = this.createEquirectangularTexture(img, panoData);
            return {
                panorama,
                texture,
                panoData,
                cacheKey: panorama
            };
        }
        /**
         * Loads the XMP data of an image
         */
        async loadXMP(blob) {
            const binary = await this.loadBlobAsString(blob);
            const a = binary.indexOf("<x:xmpmeta");
            const b = binary.indexOf("</x:xmpmeta>");
            const data = binary.substring(a, b);
            if (a !== -1 && b !== -1 && data.includes("GPano:")) {
                return {
                    fullWidth: getXMPValue(data, "FullPanoWidthPixels"),
                    fullHeight: getXMPValue(data, "FullPanoHeightPixels"),
                    croppedWidth: getXMPValue(data, "CroppedAreaImageWidthPixels"),
                    croppedHeight: getXMPValue(data, "CroppedAreaImageHeightPixels"),
                    croppedX: getXMPValue(data, "CroppedAreaLeftPixels"),
                    croppedY: getXMPValue(data, "CroppedAreaTopPixels"),
                    poseHeading: getXMPValue(data, "PoseHeadingDegrees"),
                    posePitch: getXMPValue(data, "PosePitchDegrees"),
                    poseRoll: getXMPValue(data, "PoseRollDegrees")
                };
            }
            return null;
        }
        /**
         * Reads a Blob as a string
         */
        loadBlobAsString(blob) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsText(blob);
            });
        }
        /**
         * Creates the final texture from image and panorama data
         */
        createEquirectangularTexture(img, panoData) {
            if (this.config.blur || panoData.fullWidth > SYSTEM.maxTextureWidth || panoData.croppedWidth !== panoData.fullWidth || panoData.croppedHeight !== panoData.fullHeight) {
                const ratio = Math.min(1, SYSTEM.maxCanvasWidth / panoData.fullWidth);
                const resizedPanoData = {
                    fullWidth: panoData.fullWidth * ratio,
                    fullHeight: panoData.fullHeight * ratio,
                    croppedWidth: panoData.croppedWidth * ratio,
                    croppedHeight: panoData.croppedHeight * ratio,
                    croppedX: panoData.croppedX * ratio,
                    croppedY: panoData.croppedY * ratio
                };
                const buffer = document.createElement("canvas");
                buffer.width = resizedPanoData.fullWidth;
                buffer.height = resizedPanoData.fullHeight;
                const ctx = buffer.getContext("2d");
                if (this.config.backgroundColor) {
                    ctx.fillStyle = this.config.backgroundColor;
                    ctx.fillRect(0, 0, buffer.width, buffer.height);
                }
                if (this.config.blur) {
                    ctx.filter = `blur(${buffer.width / 2048}px)`;
                }
                ctx.drawImage(
                    img,
                    resizedPanoData.croppedX,
                    resizedPanoData.croppedY,
                    resizedPanoData.croppedWidth,
                    resizedPanoData.croppedHeight
                );
                const t = createTexture(buffer);
                if (this.config.interpolateBackground && (panoData.croppedWidth !== panoData.fullWidth || panoData.croppedHeight !== panoData.fullHeight)) {
                    this.interpolationWorker.postMessage({
                        image: ctx.getImageData(
                            resizedPanoData.croppedX,
                            resizedPanoData.croppedY,
                            resizedPanoData.croppedWidth,
                            resizedPanoData.croppedHeight
                        ),
                        panoData: resizedPanoData
                    });
                    this.interpolationWorker.onmessage = (e) => {
                        ctx.putImageData(e.data, 0, 0);
                        t.needsUpdate = true;
                        this.viewer.needsUpdate();
                    };
                }
                return t;
            }
            return createTexture(img);
        }
        createMesh(scale = 1) {
            const geometry = new import_three4.SphereGeometry(
                SPHERE_RADIUS * scale,
                this.SPHERE_SEGMENTS,
                this.SPHERE_HORIZONTAL_SEGMENTS,
                -Math.PI / 2
            ).scale(-1, 1, 1);
            const material = AbstractAdapter.createOverlayMaterial();
            return new import_three4.Mesh(geometry, material);
        }
        setTexture(mesh, textureData) {
            this.__setUniform(mesh, AbstractAdapter.OVERLAY_UNIFORMS.panorama, textureData.texture);
        }
        setOverlay(mesh, textureData, opacity) {
            this.__setUniform(mesh, AbstractAdapter.OVERLAY_UNIFORMS.overlayOpacity, opacity);
            if (!textureData) {
                this.__setUniform(mesh, AbstractAdapter.OVERLAY_UNIFORMS.overlay, null);
            } else {
                this.__setUniform(mesh, AbstractAdapter.OVERLAY_UNIFORMS.overlay, textureData.texture);
            }
        }
        setTextureOpacity(mesh, opacity) {
            this.__setUniform(mesh, AbstractAdapter.OVERLAY_UNIFORMS.globalOpacity, opacity);
            mesh.material.transparent = opacity < 1;
        }
        disposeTexture(textureData) {
            textureData.texture?.dispose();
        }
        __setUniform(mesh, uniform, value) {
            mesh.material.uniforms[uniform].value = value;
        }
        __defaultPanoData(img) {
            const fullWidth = Math.max(img.width, img.height * 2);
            const fullHeight = Math.round(fullWidth / 2);
            const croppedX = Math.round((fullWidth - img.width) / 2);
            const croppedY = Math.round((fullHeight - img.height) / 2);
            return {
                fullWidth,
                fullHeight,
                croppedWidth: img.width,
                croppedHeight: img.height,
                croppedX,
                croppedY
            };
        }
    };
    EquirectangularAdapter.id = "equirectangular";
    EquirectangularAdapter.supportsDownload = true;
    EquirectangularAdapter.supportsOverlay = true;

    // src/components/AbstractComponent.ts
    var AbstractComponent = class _AbstractComponent {
        constructor(parent, config) {
            this.parent = parent;
            /**
             * All child components
             * @internal
             */
            this.children = [];
            /**
             * Container element
             */
            this.container = document.createElement("div");
            /**
             * Internal properties
             * @internal
             */
            this.state = {
                visible: true
            };
            this.viewer = parent instanceof _AbstractComponent ? parent.viewer : parent;
            this.container.className = config.className || "";
            this.parent.children.push(this);
            this.parent.container.appendChild(this.container);
        }
        /**
         * Destroys the component
         */
        destroy() {
            this.parent.container.removeChild(this.container);
            const childIdx = this.parent.children.indexOf(this);
            if (childIdx !== -1) {
                this.parent.children.splice(childIdx, 1);
            }
            this.children.slice().forEach((child) => child.destroy());
            this.children.length = 0;
        }
        /**
         * Displays or hides the component
         */
        toggle(visible = !this.isVisible()) {
            if (!visible) {
                this.hide();
            } else {
                this.show();
            }
        }
        /**
         * Hides the component
         */
        // @ts-ignore unused parameter
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        hide(options) {
            this.container.style.display = "none";
            this.state.visible = false;
        }
        /**
         * Displays the component
         */
        // @ts-ignore unused parameter
        // eslint-disable-next-line @typescript-eslint/no-unused-vars
        show(options) {
            this.container.style.display = "";
            this.state.visible = true;
        }
        /**
         * Checks if the component is visible
         */
        isVisible() {
            return this.state.visible;
        }
    };

    // src/buttons/AbstractButton.ts
    var getConfig2 = getConfigParser({
        id: null,
        className: null,
        title: null,
        hoverScale: false,
        collapsable: false,
        tabbable: true,
        icon: null,
        iconActive: null
    });
    var AbstractButton = class extends AbstractComponent {
        constructor(navbar, config) {
            super(navbar, {
                className: `psv-button ${config.hoverScale ? "psv-button--hover-scale" : ""} ${config.className || ""}`
            });
            /**
             * Internal properties
             */
            this.state = {
                visible: true,
                enabled: true,
                supported: true,
                collapsed: false,
                active: false,
                width: 0
            };
            this.config = getConfig2(config);
            this.config.id = this.constructor.id;
            if (config.icon) {
                this.__setIcon(config.icon);
            }
            this.state.width = this.container.offsetWidth;
            if (this.config.title) {
                this.container.title = this.config.title;
            } else if (this.id && this.id in this.viewer.config.lang) {
                this.container.title = this.viewer.config.lang[this.id];
            }
            if (config.tabbable) {
                this.container.tabIndex = 0;
            }
            this.container.addEventListener("click", (e) => {
                if (this.state.enabled) {
                    this.onClick();
                }
                e.stopPropagation();
            });
            this.container.addEventListener("keydown", (e) => {
                if (e.key === KEY_CODES.Enter && this.state.enabled) {
                    this.onClick();
                    e.stopPropagation();
                }
            });
        }
        get id() {
            return this.config.id;
        }
        get title() {
            return this.container.title;
        }
        get content() {
            return this.container.innerHTML;
        }
        get width() {
            return this.state.width;
        }
        get collapsable() {
            return this.config.collapsable;
        }
        show(refresh = true) {
            if (!this.isVisible()) {
                this.state.visible = true;
                if (!this.state.collapsed) {
                    this.container.style.display = "";
                }
                if (refresh) {
                    this.viewer.navbar.autoSize();
                }
            }
        }
        hide(refresh = true) {
            if (this.isVisible()) {
                this.state.visible = false;
                this.container.style.display = "none";
                if (refresh) {
                    this.viewer.navbar.autoSize();
                }
            }
        }
        /**
         * Hides/shows the button depending of the result of {@link isSupported}
         * @internal
         */
        checkSupported() {
            resolveBoolean(this.isSupported(), (supported, init) => {
                if (!this.state) {
                    return;
                }
                this.state.supported = supported;
                if (!init) {
                    this.toggle(supported);
                } else if (!supported) {
                    this.hide();
                }
            });
        }
        /**
         * Perform action when the navbar size/content changes
         * @internal
         */
        autoSize() {
        }
        /**
         * Checks if the button can be displayed
         */
        isSupported() {
            return true;
        }
        /**
         * Changes the active state of the button
         */
        toggleActive(active = !this.state.active) {
            if (active !== this.state.active) {
                this.state.active = active;
                toggleClass(this.container, "psv-button--active", this.state.active);
                if (this.config.iconActive) {
                    this.__setIcon(this.state.active ? this.config.iconActive : this.config.icon);
                }
            }
        }
        /**
         * Disables the button
         */
        disable() {
            this.container.classList.add("psv-button--disabled");
            this.state.enabled = false;
        }
        /**
         * Enables the button
         */
        enable() {
            this.container.classList.remove("psv-button--disabled");
            this.state.enabled = true;
        }
        /**
         * Collapses the button in the navbar menu
         */
        collapse() {
            this.state.collapsed = true;
            this.container.style.display = "none";
        }
        /**
         * Uncollapses the button from the navbar menu
         */
        uncollapse() {
            this.state.collapsed = false;
            if (this.state.visible) {
                this.container.style.display = "";
            }
        }
        __setIcon(icon) {
            this.container.innerHTML = icon;
            addClasses(this.container.querySelector("svg"), "psv-button-svg");
        }
    };

    // src/buttons/CustomButton.ts
    var CustomButton = class extends AbstractButton {
        constructor(navbar, config) {
            super(navbar, {
                className: `psv-custom-button ${config.className || ""}`,
                hoverScale: false,
                collapsable: config.collapsable !== false,
                tabbable: config.tabbable !== false,
                title: config.title
            });
            this.customOnClick = config.onClick;
            if (config.id) {
                this.config.id = config.id;
            } else {
                this.config.id = "psvButton-" + Math.random().toString(36).substring(2);
            }
            if (config.content) {
                this.container.innerHTML = config.content;
            }
            this.state.width = this.container.offsetWidth;
            if (config.disabled) {
                this.disable();
            }
            if (config.visible === false) {
                this.hide();
            }
        }
        onClick() {
            this.customOnClick?.(this.viewer);
        }
    };

    // src/buttons/DescriptionButton.ts
    var DescriptionButton = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-description-button",
                hoverScale: true,
                collapsable: false,
                tabbable: true,
                icon: ICONS.info
            });
            this.mode = 0 /* NONE */;
            this.viewer.addEventListener(HideNotificationEvent.type, this);
            this.viewer.addEventListener(ShowNotificationEvent.type, this);
            this.viewer.addEventListener(HidePanelEvent.type, this);
            this.viewer.addEventListener(ShowPanelEvent.type, this);
            this.viewer.addEventListener(ConfigChangedEvent.type, this);
        }
        destroy() {
            this.viewer.removeEventListener(HideNotificationEvent.type, this);
            this.viewer.removeEventListener(ShowNotificationEvent.type, this);
            this.viewer.removeEventListener(HidePanelEvent.type, this);
            this.viewer.removeEventListener(ShowPanelEvent.type, this);
            this.viewer.removeEventListener(ConfigChangedEvent.type, this);
            super.destroy();
        }
        handleEvent(e) {
            if (e instanceof ConfigChangedEvent) {
                e.containsOptions("description") && this.autoSize(true);
                return;
            }
            if (!this.mode) {
                return;
            }
            let closed = false;
            if (e instanceof HideNotificationEvent) {
                closed = this.mode === 1 /* NOTIF */;
            } else if (e instanceof ShowNotificationEvent) {
                closed = this.mode === 1 /* NOTIF */ && e.notificationId !== IDS.DESCRIPTION;
            } else if (e instanceof HidePanelEvent) {
                closed = this.mode === 2 /* PANEL */;
            } else if (e instanceof ShowPanelEvent) {
                closed = this.mode === 2 /* PANEL */ && e.panelId !== IDS.DESCRIPTION;
            }
            if (closed) {
                this.toggleActive(false);
                this.mode = 0 /* NONE */;
            }
        }
        onClick() {
            if (this.mode) {
                this.__close();
            } else {
                this.__open();
            }
        }
        hide(refresh) {
            super.hide(refresh);
            if (this.mode) {
                this.__close();
            }
        }
        /**
         * This button can only be refreshed from NavbarCaption
         * @internal
         */
        autoSize(refresh = false) {
            if (refresh) {
                const caption = this.viewer.navbar.getButton("caption", false);
                const captionHidden = caption && !caption.isVisible();
                const hasDescription = !!this.viewer.config.description;
                if (captionHidden || hasDescription) {
                    this.show(false);
                } else {
                    this.hide(false);
                }
            }
        }
        __close() {
            switch (this.mode) {
                case 1 /* NOTIF */:
                    this.viewer.notification.hide(IDS.DESCRIPTION);
                    break;
                case 2 /* PANEL */:
                    this.viewer.panel.hide(IDS.DESCRIPTION);
                    break;
                default:
            }
        }
        __open() {
            this.toggleActive(true);
            if (this.viewer.config.description) {
                this.mode = 2 /* PANEL */;
                this.viewer.panel.show({
                    id: IDS.DESCRIPTION,
                    content: (this.viewer.config.caption ? `<p>${this.viewer.config.caption}</p>` : "") + this.viewer.config.description
                });
            } else {
                this.mode = 1 /* NOTIF */;
                this.viewer.notification.show({
                    id: IDS.DESCRIPTION,
                    content: this.viewer.config.caption
                });
            }
        }
    };
    DescriptionButton.id = "description";

    // src/buttons/DownloadButton.ts
    var DownloadButton = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-download-button",
                hoverScale: true,
                collapsable: true,
                tabbable: true,
                icon: ICONS.download
            });
            this.viewer.addEventListener(ConfigChangedEvent.type, this);
        }
        destroy() {
            this.viewer.removeEventListener(ConfigChangedEvent.type, this);
            super.destroy();
        }
        handleEvent(e) {
            if (e instanceof ConfigChangedEvent) {
                e.containsOptions("downloadUrl") && this.checkSupported();
            }
        }
        onClick() {
            const link = document.createElement("a");
            link.href = this.viewer.config.downloadUrl || this.viewer.config.panorama;
            if (link.href.startsWith("data:") && !this.viewer.config.downloadName) {
                link.download = "panorama." + link.href.substring(0, link.href.indexOf(";")).split("/").pop();
            } else {
                link.download = this.viewer.config.downloadName || link.href.split("/").pop();
            }
            link.target = "_blank";
            this.viewer.container.appendChild(link);
            link.click();
            setTimeout(() => {
                this.viewer.container.removeChild(link);
            }, 100);
        }
        checkSupported() {
            const supported = this.viewer.adapter.constructor.supportsDownload || this.viewer.config.downloadUrl;
            if (supported) {
                this.show();
            } else {
                this.hide();
            }
        }
    };
    DownloadButton.id = "download";

    // src/buttons/FullscreenButton.ts
    var FullscreenButton = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-fullscreen-button",
                hoverScale: true,
                collapsable: false,
                tabbable: true,
                icon: ICONS.fullscreenIn,
                iconActive: ICONS.fullscreenOut
            });
            this.viewer.addEventListener(FullscreenEvent.type, this);
        }
        destroy() {
            this.viewer.removeEventListener(FullscreenEvent.type, this);
            super.destroy();
        }
        handleEvent(e) {
            if (e instanceof FullscreenEvent) {
                this.toggleActive(e.fullscreenEnabled);
            }
        }
        onClick() {
            this.viewer.toggleFullscreen();
        }
    };
    FullscreenButton.id = "fullscreen";

    // src/buttons/MenuButton.ts
    var BUTTON_DATA = "psvButton";
    var MENU_TEMPLATE = (buttons, title) => `
<div class="psv-panel-menu psv-panel-menu--stripped">
  <h1 class="psv-panel-menu-title">${ICONS.menu} ${title}</h1>
  <ul class="psv-panel-menu-list">
    ${buttons.map(
        (button) => `
    <li data-psv-button="${button.id}" class="psv-panel-menu-item" tabindex="0">
      <span class="psv-panel-menu-item-icon">${button.content}</span>
      <span class="psv-panel-menu-item-label">${button.title}</span>
    </li>
    `
    ).join("")}
  </ul>
</div>
`;
    var MenuButton = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-menu-button",
                hoverScale: true,
                collapsable: false,
                tabbable: true,
                icon: ICONS.menu
            });
            this.viewer.addEventListener(ShowPanelEvent.type, this);
            this.viewer.addEventListener(HidePanelEvent.type, this);
            super.hide();
        }
        destroy() {
            this.viewer.removeEventListener(ShowPanelEvent.type, this);
            this.viewer.removeEventListener(HidePanelEvent.type, this);
            super.destroy();
        }
        handleEvent(e) {
            if (e instanceof ShowPanelEvent) {
                this.toggleActive(e.panelId === IDS.MENU);
            } else if (e instanceof HidePanelEvent) {
                this.toggleActive(false);
            }
        }
        onClick() {
            if (this.state.active) {
                this.__hideMenu();
            } else {
                this.__showMenu();
            }
        }
        hide(refresh) {
            super.hide(refresh);
            this.__hideMenu();
        }
        show(refresh) {
            super.show(refresh);
            if (this.state.active) {
                this.__showMenu();
            }
        }
        __showMenu() {
            this.viewer.panel.show({
                id: IDS.MENU,
                content: MENU_TEMPLATE(this.viewer.navbar.collapsed, this.viewer.config.lang.menu),
                noMargin: true,
                clickHandler: (target) => {
                    const li = target ? getClosest(target, "li") : void 0;
                    const buttonId = li ? li.dataset[BUTTON_DATA] : void 0;
                    if (buttonId) {
                        this.viewer.navbar.getButton(buttonId).onClick();
                        this.__hideMenu();
                    }
                }
            });
        }
        __hideMenu() {
            this.viewer.panel.hide(IDS.MENU);
        }
    };
    MenuButton.id = "menu";

    // src/buttons/AbstractMoveButton.ts
    function getIcon(value) {
        let angle2 = 0;
        switch (value) {
            case 0 /* UP */:
                angle2 = 90;
                break;
            case 1 /* DOWN */:
                angle2 = -90;
                break;
            case 3 /* RIGHT */:
                angle2 = 180;
                break;
            default:
                angle2 = 0;
                break;
        }
        return ICONS.arrow.replace("rotate(0", `rotate(${angle2}`);
    }
    var AbstractMoveButton = class extends AbstractButton {
        constructor(navbar, direction) {
            super(navbar, {
                className: "psv-move-button",
                hoverScale: true,
                collapsable: false,
                tabbable: true,
                icon: getIcon(direction)
            });
            this.direction = direction;
            this.handler = new PressHandler();
            this.container.addEventListener("mousedown", this);
            this.container.addEventListener("keydown", this);
            this.container.addEventListener("keyup", this);
            this.viewer.container.addEventListener("mouseup", this);
            this.viewer.container.addEventListener("touchend", this);
        }
        destroy() {
            this.__onMouseUp();
            this.viewer.container.removeEventListener("mouseup", this);
            this.viewer.container.removeEventListener("touchend", this);
            super.destroy();
        }
        handleEvent(e) {
            switch (e.type) {
                case "mousedown":
                    this.__onMouseDown();
                    break;
                case "mouseup":
                    this.__onMouseUp();
                    break;
                case "touchend":
                    this.__onMouseUp();
                    break;
                case "keydown":
                    e.key === KEY_CODES.Enter && this.__onMouseDown();
                    break;
                case "keyup":
                    e.key === KEY_CODES.Enter && this.__onMouseUp();
                    break;
            }
        }
        onClick() {
        }
        isSupported() {
            return invertResolvableBoolean(SYSTEM.isTouchEnabled);
        }
        __onMouseDown() {
            if (!this.state.enabled) {
                return;
            }
            const dynamicRoll = {};
            switch (this.direction) {
                case 0 /* UP */:
                    dynamicRoll.pitch = false;
                    break;
                case 1 /* DOWN */:
                    dynamicRoll.pitch = true;
                    break;
                case 3 /* RIGHT */:
                    dynamicRoll.yaw = false;
                    break;
                default:
                    dynamicRoll.yaw = true;
                    break;
            }
            this.viewer.stopAll();
            this.viewer.dynamics.position.roll(dynamicRoll);
            this.handler.down();
        }
        __onMouseUp() {
            if (!this.state.enabled) {
                return;
            }
            this.handler.up(() => {
                this.viewer.dynamics.position.stop();
                this.viewer.resetIdleTimer();
            });
        }
    };
    AbstractMoveButton.groupId = "move";

    // src/buttons/MoveDownButton.ts
    var MoveDownButton = class extends AbstractMoveButton {
        constructor(navbar) {
            super(navbar, 1 /* DOWN */);
        }
    };
    MoveDownButton.id = "moveDown";

    // src/buttons/MoveLeftButton.ts
    var MoveLeftButton = class extends AbstractMoveButton {
        constructor(navbar) {
            super(navbar, 2 /* LEFT */);
        }
    };
    MoveLeftButton.id = "moveLeft";

    // src/buttons/MoveRightButton.ts
    var MoveRightButton = class extends AbstractMoveButton {
        constructor(navbar) {
            super(navbar, 3 /* RIGHT */);
        }
    };
    MoveRightButton.id = "moveRight";

    // src/buttons/MoveUpButton.ts
    var MoveUpButton = class extends AbstractMoveButton {
        constructor(navbar) {
            super(navbar, 0 /* UP */);
        }
    };
    MoveUpButton.id = "moveUp";

    // src/buttons/AbstractZoomButton.ts
    var AbstractZoomButton = class extends AbstractButton {
        constructor(navbar, icon, direction) {
            super(navbar, {
                className: "psv-zoom-button",
                hoverScale: true,
                collapsable: false,
                tabbable: true,
                icon
            });
            this.direction = direction;
            this.handler = new PressHandler();
            this.container.addEventListener("mousedown", this);
            this.container.addEventListener("keydown", this);
            this.container.addEventListener("keyup", this);
            this.viewer.container.addEventListener("mouseup", this);
            this.viewer.container.addEventListener("touchend", this);
        }
        destroy() {
            this.__onMouseUp();
            this.viewer.container.removeEventListener("mouseup", this);
            this.viewer.container.removeEventListener("touchend", this);
            super.destroy();
        }
        handleEvent(e) {
            switch (e.type) {
                case "mousedown":
                    this.__onMouseDown();
                    break;
                case "mouseup":
                    this.__onMouseUp();
                    break;
                case "touchend":
                    this.__onMouseUp();
                    break;
                case "keydown":
                    e.key === KEY_CODES.Enter && this.__onMouseDown();
                    break;
                case "keyup":
                    e.key === KEY_CODES.Enter && this.__onMouseUp();
                    break;
            }
        }
        onClick() {
        }
        isSupported() {
            return invertResolvableBoolean(SYSTEM.isTouchEnabled);
        }
        __onMouseDown() {
            if (!this.state.enabled) {
                return;
            }
            this.viewer.dynamics.zoom.roll(this.direction === 1 /* OUT */);
            this.handler.down();
        }
        __onMouseUp() {
            if (!this.state.enabled) {
                return;
            }
            this.handler.up(() => this.viewer.dynamics.zoom.stop());
        }
    };
    AbstractZoomButton.groupId = "zoom";

    // src/buttons/ZoomInButton.ts
    var ZoomInButton = class extends AbstractZoomButton {
        constructor(navbar) {
            super(navbar, ICONS.zoomIn, 0 /* IN */);
        }
    };
    ZoomInButton.id = "zoomIn";

    // src/buttons/ZoomOutButton.ts
    var ZoomOutButton = class extends AbstractZoomButton {
        constructor(navbar) {
            super(navbar, ICONS.zoomOut, 1 /* OUT */);
        }
    };
    ZoomOutButton.id = "zoomOut";

    // src/buttons/ZoomRangeButton.ts
    var ZoomRangeButton = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-zoom-range",
                hoverScale: false,
                collapsable: false,
                tabbable: false
            });
            this.zoomRange = document.createElement("div");
            this.zoomRange.className = "psv-zoom-range-line";
            this.container.appendChild(this.zoomRange);
            this.zoomValue = document.createElement("div");
            this.zoomValue.className = "psv-zoom-range-handle";
            this.zoomRange.appendChild(this.zoomValue);
            this.slider = new Slider(this.container, "HORIZONTAL" /* HORIZONTAL */, (data) => this.__onSliderUpdate(data));
            this.mediaMinWidth = parseInt(getStyleProperty(this.container, "max-width"), 10);
            this.viewer.addEventListener(ZoomUpdatedEvent.type, this);
            if (this.viewer.state.ready) {
                this.__moveZoomValue(this.viewer.getZoomLevel());
            } else {
                this.viewer.addEventListener(ReadyEvent.type, this);
            }
        }
        destroy() {
            this.slider.destroy();
            this.viewer.removeEventListener(ZoomUpdatedEvent.type, this);
            this.viewer.removeEventListener(ReadyEvent.type, this);
            super.destroy();
        }
        handleEvent(e) {
            if (e instanceof ZoomUpdatedEvent) {
                this.__moveZoomValue(e.zoomLevel);
            } else if (e instanceof ReadyEvent) {
                this.__moveZoomValue(this.viewer.getZoomLevel());
            }
        }
        onClick() {
        }
        isSupported() {
            return invertResolvableBoolean(SYSTEM.isTouchEnabled);
        }
        autoSize() {
            if (this.state.supported) {
                if (this.viewer.state.size.width <= this.mediaMinWidth && this.state.visible) {
                    this.hide(false);
                } else if (this.viewer.state.size.width > this.mediaMinWidth && !this.state.visible) {
                    this.show(false);
                }
            }
        }
        __moveZoomValue(level) {
            this.zoomValue.style.left = level / 100 * this.zoomRange.offsetWidth - this.zoomValue.offsetWidth / 2 + "px";
        }
        __onSliderUpdate(data) {
            if (data.mousedown) {
                this.viewer.zoom(data.value * 100);
            }
        }
    };
    ZoomRangeButton.id = "zoomRange";
    ZoomRangeButton.groupId = "zoom";

    // src/data/config.ts
    var import_three5 = require_three();

    // src/plugins/AbstractPlugin.ts
    var AbstractPlugin = class extends TypedEventTarget {
        constructor(viewer) {
            super();
            this.viewer = viewer;
        }
        /**
         * Initializes the plugin
         */
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        init() {
        }
        /**
         * Destroys the plugin
         */
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        destroy() {
        }
    };
    var AbstractConfigurablePlugin = class extends AbstractPlugin {
        constructor(viewer, config) {
            super(viewer);
            this.config = this.constructor.configParser(config);
        }
        /**
         * Update options
         */
        setOption(option, value) {
            this.setOptions({ [option]: value });
        }
        /**
         * Update options
         */
        setOptions(options) {
            const rawConfig = {
                ...this.config,
                ...options
            };
            const ctor = this.constructor;
            const parser = ctor.configParser;
            const readonly = ctor.readonlyOptions;
            const id = ctor.id;
            for (let [key, value] of Object.entries(options)) {
                if (!(key in parser.defaults)) {
                    logWarn(`${id}: Unknown option "${key}"`);
                    continue;
                }
                if (readonly.includes(key)) {
                    logWarn(`${id}: Option "${key}" cannot be updated`);
                    continue;
                }
                if (key in parser.parsers) {
                    value = parser.parsers[key](value, {
                        rawConfig,
                        defValue: parser.defaults[key]
                    });
                }
                this.config[key] = value;
            }
        }
    };
    AbstractConfigurablePlugin.readonlyOptions = [];
    function pluginInterop(plugin) {
        if (plugin) {
            for (const [, p] of [["_", plugin], ...Object.entries(plugin)]) {
                if (p.prototype instanceof AbstractPlugin) {
                    return p;
                }
            }
        }
        return null;
    }

    // src/data/config.ts
    var DEFAULTS = {
        panorama: null,
        overlay: null,
        overlayOpacity: 1,
        container: null,
        adapter: [EquirectangularAdapter, null],
        plugins: [],
        caption: null,
        description: null,
        downloadUrl: null,
        downloadName: null,
        loadingImg: null,
        loadingTxt: "Loading...",
        size: null,
        fisheye: 0,
        minFov: 30,
        maxFov: 90,
        defaultZoomLvl: 50,
        defaultYaw: 0,
        defaultPitch: 0,
        sphereCorrection: null,
        moveSpeed: 1,
        zoomSpeed: 1,
        moveInertia: true,
        mousewheel: true,
        mousemove: true,
        mousewheelCtrlKey: false,
        touchmoveTwoFingers: false,
        useXmpData: null,
        panoData: null,
        requestHeaders: null,
        canvasBackground: null,
        rendererParameters: { alpha: true, antialias: true },
        withCredentials: false,
        // prettier-ignore
        navbar: [
            "zoom",
            "move",
            "download",
            "description",
            "caption",
            "fullscreen"
        ],
        lang: {
            zoom: "Zoom",
            zoomOut: "Zoom out",
            zoomIn: "Zoom in",
            moveUp: "Move up",
            moveDown: "Move down",
            moveLeft: "Move left",
            moveRight: "Move right",
            download: "Download",
            fullscreen: "Fullscreen",
            menu: "Menu",
            close: "Close",
            twoFingers: "Use two fingers to navigate",
            ctrlZoom: "Use ctrl + scroll to zoom the image",
            loadError: "The panorama can't be loaded"
        },
        keyboard: "fullscreen",
        keyboardActions: {
            [KEY_CODES.ArrowUp]: "ROTATE_UP" /* ROTATE_UP */,
            [KEY_CODES.ArrowDown]: "ROTATE_DOWN" /* ROTATE_DOWN */,
            [KEY_CODES.ArrowRight]: "ROTATE_RIGHT" /* ROTATE_RIGHT */,
            [KEY_CODES.ArrowLeft]: "ROTATE_LEFT" /* ROTATE_LEFT */,
            [KEY_CODES.PageUp]: "ZOOM_IN" /* ZOOM_IN */,
            [KEY_CODES.PageDown]: "ZOOM_OUT" /* ZOOM_OUT */,
            [KEY_CODES.Plus]: "ZOOM_IN" /* ZOOM_IN */,
            [KEY_CODES.Minus]: "ZOOM_OUT" /* ZOOM_OUT */
        }
    };
    var READONLY_OPTIONS = {
        panorama: "Use setPanorama method to change the panorama",
        panoData: "Use setPanorama method to change the panorama",
        overlay: "Use setOverlay method to changer the overlay",
        overlayOpacity: "Use setOverlay method to changer the overlay",
        container: "Cannot change viewer container",
        adapter: "Cannot change adapter",
        plugins: "Cannot change plugins"
    };
    var CONFIG_PARSERS = {
        container: (container) => {
            if (!container) {
                throw new PSVError("No value given for container.");
            }
            return container;
        },
        adapter: (adapter, { defValue }) => {
            if (!adapter) {
                adapter = defValue;
            } else if (Array.isArray(adapter)) {
                adapter = [adapterInterop(adapter[0]), adapter[1]];
            } else {
                adapter = [adapterInterop(adapter), null];
            }
            if (!adapter[0]) {
                throw new PSVError("An undefined value was given for adapter.");
            }
            if (!adapter[0].id) {
                throw new PSVError(`Adapter has no id.`);
            }
            return adapter;
        },
        overlayOpacity: (overlayOpacity) => {
            return import_three5.MathUtils.clamp(overlayOpacity, 0, 1);
        },
        defaultYaw: (defaultYaw) => {
            return parseAngle(defaultYaw);
        },
        defaultPitch: (defaultPitch) => {
            return parseAngle(defaultPitch, true);
        },
        defaultZoomLvl: (defaultZoomLvl) => {
            return import_three5.MathUtils.clamp(defaultZoomLvl, 0, 100);
        },
        minFov: (minFov, { rawConfig }) => {
            if (rawConfig.maxFov < minFov) {
                logWarn("maxFov cannot be lower than minFov");
                minFov = rawConfig.maxFov;
            }
            return import_three5.MathUtils.clamp(minFov, 1, 179);
        },
        maxFov: (maxFov, { rawConfig }) => {
            if (maxFov < rawConfig.minFov) {
                maxFov = rawConfig.minFov;
            }
            return import_three5.MathUtils.clamp(maxFov, 1, 179);
        },
        lang: (lang) => {
            if (Array.isArray(lang.twoFingers)) {
                logWarn("lang.twoFingers must not be an array");
                lang.twoFingers = lang.twoFingers[0];
            }
            return {
                ...DEFAULTS.lang,
                ...lang
            };
        },
        keyboard: (keyboard) => {
            if (!keyboard) {
                return false;
            }
            if (typeof keyboard === "object") {
                logWarn(`Use keyboardActions to configure the keyboard actions, keyboard option must be either true, false, 'fullscreen' or 'always'`);
                return "fullscreen";
            }
            return keyboard === "always" ? "always" : "fullscreen";
        },
        keyboardActions: (keyboardActions, { rawConfig }) => {
            if (rawConfig.keyboard && typeof rawConfig.keyboard === "object") {
                return rawConfig.keyboard;
            }
            return keyboardActions;
        },
        fisheye: (fisheye) => {
            if (fisheye === true) {
                return 1;
            } else if (fisheye === false) {
                return 0;
            }
            return fisheye;
        },
        requestHeaders: (requestHeaders) => {
            if (requestHeaders && typeof requestHeaders === "object") {
                return () => requestHeaders;
            }
            if (typeof requestHeaders === "function") {
                return requestHeaders;
            }
            return null;
        },
        rendererParameters: (rendererParameters, { defValue }) => ({
            ...rendererParameters,
            ...defValue
        }),
        plugins: (plugins) => {
            return plugins.map((plugin, i) => {
                if (Array.isArray(plugin)) {
                    plugin = [pluginInterop(plugin[0]), plugin[1]];
                } else {
                    plugin = [pluginInterop(plugin), null];
                }
                if (!plugin[0]) {
                    throw new PSVError(`An undefined value was given for plugin ${i}.`);
                }
                if (!plugin[0].id) {
                    throw new PSVError(`Plugin ${i} has no id.`);
                }
                return plugin;
            });
        },
        navbar: (navbar) => {
            if (navbar === false) {
                return null;
            }
            if (navbar === true) {
                return clone(DEFAULTS.navbar);
            }
            if (typeof navbar === "string") {
                return navbar.split(/[ ,]/);
            }
            return navbar;
        },
        useXmpData: (useXmpData) => {
            if (useXmpData !== null) {
                logWarn(`Global useXmpData is deprecated, it is now configured on the adapter.`);
            }
            return useXmpData;
        },
        canvasBackground: (canvasBackground) => {
            if (canvasBackground !== null) {
                logWarn(`Global canvasBackground is deprecated, it is now configured on the adapter.`);
            }
            return canvasBackground;
        }
    };
    var getViewerConfig = getConfigParser(DEFAULTS, CONFIG_PARSERS);

    // src/components/NavbarCaption.ts
    var NavbarCaption = class extends AbstractButton {
        constructor(navbar) {
            super(navbar, {
                className: "psv-caption",
                hoverScale: false,
                collapsable: false,
                tabbable: true
            });
            this.contentWidth = 0;
            this.state.width = 0;
            this.contentElt = document.createElement("div");
            this.contentElt.className = "psv-caption-content";
            this.container.appendChild(this.contentElt);
            this.setCaption(this.viewer.config.caption);
        }
        hide() {
            this.contentElt.style.display = "none";
            this.state.visible = false;
        }
        show() {
            this.contentElt.style.display = "";
            this.state.visible = true;
        }
        onClick() {
        }
        /**
         * Changes the caption
         */
        setCaption(html) {
            this.show();
            this.contentElt.innerHTML = html ?? "";
            if (this.contentElt.innerHTML) {
                this.contentWidth = this.contentElt.offsetWidth;
            } else {
                this.contentWidth = 0;
            }
            this.autoSize();
        }
        /**
         * Toggles content and icon depending on available space
         */
        autoSize() {
            this.toggle(this.container.offsetWidth >= this.contentWidth);
            this.__refreshButton();
        }
        __refreshButton() {
            this.viewer.navbar.getButton(DescriptionButton.id, false)?.autoSize(true);
        }
    };
    NavbarCaption.id = "caption";

    // src/components/Navbar.ts
    var AVAILABLE_BUTTONS = {};
    var AVAILABLE_GROUPS = {};
    function registerButton(button, defaultPosition) {
        if (!button.id) {
            throw new PSVError("Button id is required");
        }
        AVAILABLE_BUTTONS[button.id] = button;
        if (button.groupId) {
            (AVAILABLE_GROUPS[button.groupId] = AVAILABLE_GROUPS[button.groupId] || []).push(button);
        }
        if (defaultPosition) {
            const navbar = DEFAULTS.navbar;
            switch (defaultPosition) {
                case "start":
                    navbar.unshift(button.id);
                    break;
                case "end":
                    navbar.push(button.id);
                    break;
                default: {
                    const [id, pos] = defaultPosition.split(":");
                    const idx = navbar.indexOf(id);
                    if (!id || !pos || idx === -1) {
                        throw new PSVError(`Invalid defaultPosition ${defaultPosition}`);
                    }
                    navbar.splice(idx + (pos === "right" ? 1 : 0), 0, button.id);
                }
            }
        }
    }
    [
        ZoomOutButton,
        ZoomRangeButton,
        ZoomInButton,
        DescriptionButton,
        NavbarCaption,
        DownloadButton,
        FullscreenButton,
        MoveLeftButton,
        MoveRightButton,
        MoveUpButton,
        MoveDownButton
    ].forEach((btn) => registerButton(btn));
    var Navbar = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer, {
                className: `psv-navbar ${CAPTURE_EVENTS_CLASS}`
            });
            /**
             * @internal
             */
            this.collapsed = [];
            this.state.visible = false;
        }
        /**
         * Shows the navbar
         */
        show() {
            this.viewer.container.classList.add("psv--has-navbar");
            this.container.classList.add("psv-navbar--open");
            this.state.visible = true;
        }
        /**
         * Hides the navbar
         */
        hide() {
            this.viewer.container.classList.remove("psv--has-navbar");
            this.container.classList.remove("psv-navbar--open");
            this.state.visible = false;
        }
        /**
         * Change the buttons visible on the navbar
         */
        setButtons(buttons) {
            this.children.slice().forEach((item) => item.destroy());
            this.children.length = 0;
            if (buttons.indexOf(NavbarCaption.id) !== -1 && buttons.indexOf(DescriptionButton.id) === -1) {
                buttons.splice(buttons.indexOf(NavbarCaption.id), 0, DescriptionButton.id);
            }
            buttons.forEach((button) => {
                if (typeof button === "object") {
                    new CustomButton(this, button);
                } else if (AVAILABLE_BUTTONS[button]) {
                    new AVAILABLE_BUTTONS[button](this);
                } else if (AVAILABLE_GROUPS[button]) {
                    AVAILABLE_GROUPS[button].forEach((buttonCtor) => {
                        new buttonCtor(this);
                    });
                } else {
                    logWarn(`Unknown button ${button}`);
                }
            });
            new MenuButton(this);
            this.children.forEach((item) => {
                if (item instanceof AbstractButton) {
                    item.checkSupported();
                }
            });
            this.autoSize();
        }
        /**
         * Changes the navbar caption
         */
        setCaption(html) {
            this.children.some((item) => {
                if (item instanceof NavbarCaption) {
                    item.setCaption(html);
                    return true;
                } else {
                    return false;
                }
            });
        }
        /**
         * Returns a button by its identifier
         */
        getButton(id, warnNotFound = true) {
            const button = this.children.find((item) => {
                return item instanceof AbstractButton && item.id === id;
            });
            if (!button && warnNotFound) {
                logWarn(`button "${id}" not found in the navbar`);
            }
            return button;
        }
        /**
         * Automatically collapses buttons
         * @internal
         */
        autoSize() {
            this.children.forEach((child) => {
                if (child instanceof AbstractButton) {
                    child.autoSize();
                }
            });
            const availableWidth = this.container.offsetWidth;
            let totalWidth = 0;
            const collapsableButtons = [];
            this.children.forEach((item) => {
                if (item.isVisible() && item instanceof AbstractButton) {
                    totalWidth += item.width;
                    if (item.collapsable) {
                        collapsableButtons.push(item);
                    }
                }
            });
            if (totalWidth === 0) {
                return;
            }
            if (availableWidth < totalWidth && collapsableButtons.length > 0) {
                collapsableButtons.forEach((item) => item.collapse());
                this.collapsed = collapsableButtons;
                this.getButton(MenuButton.id).show(false);
            } else if (availableWidth >= totalWidth && this.collapsed.length > 0) {
                this.collapsed.forEach((item) => item.uncollapse());
                this.collapsed = [];
                this.getButton(MenuButton.id).hide(false);
            }
            this.getButton(NavbarCaption.id, false)?.autoSize();
        }
    };

    // src/components/Loader.ts
    var Loader = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer, { className: "psv-loader-container" });
            this.loader = document.createElement("div");
            this.loader.className = "psv-loader";
            this.container.appendChild(this.loader);
            this.size = this.loader.offsetWidth;
            this.canvas = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            this.canvas.setAttribute("class", "psv-loader-canvas");
            this.canvas.setAttribute("viewBox", `0 0 ${this.size} ${this.size}`);
            this.loader.appendChild(this.canvas);
            this.textColor = getStyleProperty(this.loader, "color");
            this.color = getStyleProperty(this.canvas, "color");
            this.border = parseInt(getStyleProperty(this.loader, "--psv-loader-border"), 10);
            this.thickness = parseInt(getStyleProperty(this.loader, "--psv-loader-tickness"), 10);
            this.viewer.addEventListener(ConfigChangedEvent.type, this);
            this.__updateContent();
            this.hide();
        }
        /**
         * @internal
         */
        destroy() {
            this.viewer.removeEventListener(ConfigChangedEvent.type, this);
            super.destroy();
        }
        /**
         * @internal
         */
        handleEvent(e) {
            if (e instanceof ConfigChangedEvent) {
                e.containsOptions("loadingImg", "loadingTxt") && this.__updateContent();
            }
        }
        /**
         * Sets the loader progression
         */
        setProgress(value) {
            const angle2 = Math.min(value, 99.999) / 100 * Math.PI * 2;
            const halfSize = this.size / 2;
            const startX = halfSize;
            const startY = this.thickness / 2 + this.border;
            const radius = (this.size - this.thickness) / 2 - this.border;
            const endX = Math.sin(angle2) * radius + halfSize;
            const endY = -Math.cos(angle2) * radius + halfSize;
            const largeArc = value > 50 ? "1" : "0";
            this.canvas.innerHTML = `
            <circle cx="${halfSize}" cy="${halfSize}" r="${halfSize}" fill="${this.color}"/>
            <path d="M ${startX} ${startY} A ${radius} ${radius} 0 ${largeArc} 1 ${endX} ${endY}" 
                  fill="none" stroke="${this.textColor}" stroke-width="${this.thickness}" stroke-linecap="round"/>
        `;
            this.viewer.dispatchEvent(new LoadProgressEvent(Math.round(value)));
        }
        __updateContent() {
            const current = this.loader.querySelector(".psv-loader-image, .psv-loader-text");
            if (current) {
                this.loader.removeChild(current);
            }
            let inner;
            if (this.viewer.config.loadingImg) {
                inner = document.createElement("img");
                inner.className = "psv-loader-image";
                inner.src = this.viewer.config.loadingImg;
            } else if (this.viewer.config.loadingTxt) {
                inner = document.createElement("div");
                inner.className = "psv-loader-text";
                inner.innerHTML = this.viewer.config.loadingTxt;
            }
            if (inner) {
                const size = Math.round(Math.sqrt(2 * Math.pow(this.size / 2 - this.thickness / 2 - this.border, 2)));
                inner.style.maxWidth = size + "px";
                inner.style.maxHeight = size + "px";
                this.loader.appendChild(inner);
            }
        }
    };

    // src/components/Notification.ts
    var Notification = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer, {
                className: "psv-notification"
            });
            /**
             * @internal
             */
            this.state = {
                visible: false,
                contentId: null,
                timeout: null
            };
            this.content = document.createElement("div");
            this.content.className = "psv-notification-content";
            this.container.appendChild(this.content);
            this.content.addEventListener("click", () => this.hide());
        }
        /**
         * Checks if the notification is visible
         */
        isVisible(id) {
            return this.state.visible && (!id || !this.state.contentId || this.state.contentId === id);
        }
        /**
         * @throws {@link PSVError} always
         * @internal
         */
        toggle() {
            throw new PSVError("Notification cannot be toggled");
        }
        /**
         * Displays a notification on the viewer
         *
         * @example
         * viewer.showNotification({ content: 'Hello world', timeout: 5000 })
         * @example
         * viewer.showNotification('Hello world')
         */
        show(config) {
            if (this.state.timeout) {
                clearTimeout(this.state.timeout);
                this.state.timeout = null;
            }
            if (typeof config === "string") {
                config = { content: config };
            }
            this.state.contentId = config.id || null;
            this.content.innerHTML = config.content;
            this.container.classList.add("psv-notification--visible");
            this.state.visible = true;
            this.viewer.dispatchEvent(new ShowNotificationEvent(config.id));
            if (config.timeout) {
                this.state.timeout = setTimeout(() => this.hide(this.state.contentId), config.timeout);
            }
        }
        /**
         * Hides the notification
         */
        hide(id) {
            if (this.isVisible(id)) {
                const contentId = this.state.contentId;
                this.container.classList.remove("psv-notification--visible");
                this.state.visible = false;
                this.state.contentId = null;
                this.viewer.dispatchEvent(new HideNotificationEvent(contentId));
            }
        }
    };

    // src/components/Overlay.ts
    var Overlay = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer, {
                className: `psv-overlay ${CAPTURE_EVENTS_CLASS}`
            });
            /**
             * @internal
             */
            this.state = {
                visible: false,
                contentId: null,
                dissmisable: true
            };
            this.image = document.createElement("div");
            this.image.className = "psv-overlay-image";
            this.container.appendChild(this.image);
            this.title = document.createElement("div");
            this.title.className = "psv-overlay-title";
            this.container.appendChild(this.title);
            this.text = document.createElement("div");
            this.text.className = "psv-overlay-text";
            this.container.appendChild(this.text);
            this.container.addEventListener("click", this);
            this.viewer.addEventListener(KeypressEvent.type, this);
            super.hide();
        }
        /**
         * @internal
         */
        destroy() {
            this.viewer.removeEventListener(KeypressEvent.type, this);
            super.destroy();
        }
        /**
         * @internal
         */
        handleEvent(e) {
            if (e.type === "click") {
                if (this.isVisible() && this.state.dissmisable) {
                    this.hide();
                    e.stopPropagation();
                }
            } else if (e instanceof KeypressEvent) {
                if (this.isVisible() && this.state.dissmisable && e.key === KEY_CODES.Escape) {
                    this.hide();
                    e.preventDefault();
                }
            }
        }
        /**
         * Checks if the overlay is visible
         */
        isVisible(id) {
            return this.state.visible && (!id || !this.state.contentId || this.state.contentId === id);
        }
        /**
         * @throws {@link PSVError} always
         * @internal
         */
        toggle() {
            throw new PSVError("Overlay cannot be toggled");
        }
        /**
         * Displays an overlay on the viewer
         */
        show(config) {
            if (typeof config === "string") {
                config = { title: config };
            }
            this.state.contentId = config.id || null;
            this.state.dissmisable = config.dissmisable !== false;
            this.image.innerHTML = config.image || "";
            this.title.innerHTML = config.title || "";
            this.text.innerHTML = config.text || "";
            super.show();
            this.viewer.dispatchEvent(new ShowOverlayEvent(config.id));
        }
        /**
         * Hides the overlay
         */
        hide(id) {
            if (this.isVisible(id)) {
                const contentId = this.state.contentId;
                super.hide();
                this.state.contentId = null;
                this.viewer.dispatchEvent(new HideOverlayEvent(contentId));
            }
        }
    };

    // src/components/Panel.ts
    var PANEL_MIN_WIDTH = 200;
    var PANEL_CLASS_NO_INTERACTION = "psv-panel-content--no-interaction";
    var Panel = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer, {
                className: `psv-panel ${CAPTURE_EVENTS_CLASS}`
            });
            /**
             * @internal
             */
            this.state = {
                visible: false,
                contentId: null,
                mouseX: 0,
                mouseY: 0,
                mousedown: false,
                clickHandler: null,
                keyHandler: null,
                width: {}
            };
            const resizer = document.createElement("div");
            resizer.className = "psv-panel-resizer";
            this.container.appendChild(resizer);
            const closeBtn = document.createElement("div");
            closeBtn.className = "psv-panel-close-button";
            closeBtn.innerHTML = ICONS.close;
            closeBtn.title = viewer.config.lang.close;
            this.container.appendChild(closeBtn);
            this.content = document.createElement("div");
            this.content.className = "psv-panel-content";
            this.container.appendChild(this.content);
            this.container.addEventListener("wheel", (e) => e.stopPropagation());
            closeBtn.addEventListener("click", () => this.hide());
            resizer.addEventListener("mousedown", this);
            resizer.addEventListener("touchstart", this);
            this.viewer.container.addEventListener("mouseup", this);
            this.viewer.container.addEventListener("touchend", this);
            this.viewer.container.addEventListener("mousemove", this);
            this.viewer.container.addEventListener("touchmove", this);
            this.viewer.addEventListener(KeypressEvent.type, this);
        }
        /**
         * @internal
         */
        destroy() {
            this.viewer.removeEventListener(KeypressEvent.type, this);
            this.viewer.container.removeEventListener("mousemove", this);
            this.viewer.container.removeEventListener("touchmove", this);
            this.viewer.container.removeEventListener("mouseup", this);
            this.viewer.container.removeEventListener("touchend", this);
            super.destroy();
        }
        /**
         * @internal
         */
        handleEvent(e) {
            switch (e.type) {
                case "mousedown":
                    this.__onMouseDown(e);
                    break;
                case "touchstart":
                    this.__onTouchStart(e);
                    break;
                case "mousemove":
                    this.__onMouseMove(e);
                    break;
                case "touchmove":
                    this.__onTouchMove(e);
                    break;
                case "mouseup":
                    this.__onMouseUp(e);
                    break;
                case "touchend":
                    this.__onTouchEnd(e);
                    break;
                case KeypressEvent.type:
                    this.__onKeyPress(e);
                    break;
            }
        }
        /**
         * Checks if the panel is visible
         */
        isVisible(id) {
            return this.state.visible && (!id || !this.state.contentId || this.state.contentId === id);
        }
        /**
         * @throws {@link PSVError} always
         * @internal
         */
        toggle() {
            throw new PSVError("Panel cannot be toggled");
        }
        /**
         * Shows the panel
         */
        show(config) {
            if (typeof config === "string") {
                config = { content: config };
            }
            const wasVisible = this.isVisible(config.id);
            this.state.contentId = config.id || null;
            this.state.visible = true;
            if (this.state.clickHandler) {
                this.content.removeEventListener("click", this.state.clickHandler);
                this.content.removeEventListener("keydown", this.state.keyHandler);
                this.state.clickHandler = null;
                this.state.keyHandler = null;
            }
            if (config.id && this.state.width[config.id]) {
                this.container.style.width = this.state.width[config.id];
            } else if (config.width) {
                this.container.style.width = config.width;
            } else {
                this.container.style.width = null;
            }
            this.content.innerHTML = config.content;
            this.content.scrollTop = 0;
            this.container.classList.add("psv-panel--open");
            toggleClass(this.content, "psv-panel-content--no-margin", config.noMargin === true);
            if (config.clickHandler) {
                this.state.clickHandler = (e) => {
                    config.clickHandler(e.target);
                };
                this.state.keyHandler = (e) => {
                    if (e.key === KEY_CODES.Enter) {
                        config.clickHandler(e.target);
                    }
                };
                this.content.addEventListener("click", this.state.clickHandler);
                this.content.addEventListener("keydown", this.state.keyHandler);
                if (!wasVisible) {
                    setTimeout(() => {
                        this.content.querySelector("a,button,[tabindex]")?.focus();
                    }, 300);
                }
            }
            this.viewer.dispatchEvent(new ShowPanelEvent(config.id));
        }
        /**
         * Hides the panel
         */
        hide(id) {
            if (this.isVisible(id)) {
                const contentId = this.state.contentId;
                this.state.visible = false;
                this.state.contentId = null;
                this.content.innerHTML = null;
                this.container.classList.remove("psv-panel--open");
                if (this.state.clickHandler) {
                    this.content.removeEventListener("click", this.state.clickHandler);
                    this.state.clickHandler = null;
                }
                this.viewer.dispatchEvent(new HidePanelEvent(contentId));
            }
        }
        __onMouseDown(evt) {
            evt.stopPropagation();
            this.__startResize(evt.clientX, evt.clientY);
        }
        __onTouchStart(evt) {
            evt.stopPropagation();
            if (evt.touches.length === 1) {
                const touch = evt.touches[0];
                this.__startResize(touch.clientX, touch.clientY);
            }
        }
        __onMouseUp(evt) {
            if (this.state.mousedown) {
                evt.stopPropagation();
                this.state.mousedown = false;
                this.content.classList.remove(PANEL_CLASS_NO_INTERACTION);
            }
        }
        __onTouchEnd(evt) {
            if (this.state.mousedown) {
                evt.stopPropagation();
                if (evt.touches.length === 0) {
                    this.state.mousedown = false;
                    this.content.classList.remove(PANEL_CLASS_NO_INTERACTION);
                }
            }
        }
        __onMouseMove(evt) {
            if (this.state.mousedown) {
                evt.stopPropagation();
                this.__resize(evt.clientX, evt.clientY);
            }
        }
        __onTouchMove(evt) {
            if (this.state.mousedown) {
                const touch = evt.touches[0];
                this.__resize(touch.clientX, touch.clientY);
            }
        }
        __onKeyPress(evt) {
            if (this.isVisible() && evt.key === KEY_CODES.Escape) {
                this.hide();
                evt.preventDefault();
            }
        }
        __startResize(clientX, clientY) {
            this.state.mouseX = clientX;
            this.state.mouseY = clientY;
            this.state.mousedown = true;
            this.content.classList.add(PANEL_CLASS_NO_INTERACTION);
        }
        __resize(clientX, clientY) {
            const x = clientX;
            const y = clientY;
            const width = Math.max(PANEL_MIN_WIDTH, this.container.offsetWidth - (x - this.state.mouseX)) + "px";
            if (this.state.contentId) {
                this.state.width[this.state.contentId] = width;
            }
            this.container.style.width = width;
            this.state.mouseX = x;
            this.state.mouseY = y;
        }
    };

    // src/components/Tooltip.ts
    var Tooltip = class extends AbstractComponent {
        /**
         * @internal
         */
        constructor(viewer, config) {
            super(viewer, {
                className: "psv-tooltip"
            });
            /**
             * @internal
             */
            this.state = {
                visible: true,
                arrow: 0,
                border: 0,
                state: 0 /* NONE */,
                width: 0,
                height: 0,
                pos: "",
                config: null,
                data: null
            };
            this.content = document.createElement("div");
            this.content.className = "psv-tooltip-content";
            this.container.appendChild(this.content);
            this.arrow = document.createElement("div");
            this.arrow.className = "psv-tooltip-arrow";
            this.container.appendChild(this.arrow);
            this.container.addEventListener("transitionend", this);
            this.container.addEventListener("touchdown", (e) => e.stopPropagation());
            this.container.addEventListener("mousedown", (e) => e.stopPropagation());
            this.container.style.top = "-1000px";
            this.container.style.left = "-1000px";
            this.show(config);
        }
        /**
         * @internal
         */
        handleEvent(e) {
            if (e.type === "transitionend") {
                this.__onTransitionEnd(e);
            }
        }
        /**
         * @internal
         */
        destroy() {
            delete this.state.data;
            super.destroy();
        }
        /**
         * @throws {@link PSVError} always
         * @internal
         */
        toggle() {
            throw new PSVError("Tooltip cannot be toggled");
        }
        /**
         * Displays the tooltip on the viewer
         * @internal
         */
        show(config) {
            if (this.state.state !== 0 /* NONE */) {
                throw new PSVError("Initialized tooltip cannot be re-initialized");
            }
            if (config.className) {
                addClasses(this.container, config.className);
            }
            if (config.style) {
                Object.assign(this.container.style, config.style);
            }
            this.state.state = 3 /* READY */;
            this.update(config.content, config);
            this.state.data = config.data;
            this.state.state = 1 /* SHOWING */;
            this.viewer.dispatchEvent(new ShowTooltipEvent(this, this.state.data));
            this.__waitImages();
        }
        /**
         * Updates the content of the tooltip, optionally with a new position
         * @throws {@link PSVError} if the configuration is invalid
         */
        update(content, config) {
            this.content.innerHTML = content;
            const rect = this.container.getBoundingClientRect();
            this.state.width = rect.right - rect.left;
            this.state.height = rect.bottom - rect.top;
            this.state.arrow = parseInt(getStyleProperty(this.arrow, "border-top-width"), 10);
            this.state.border = parseInt(getStyleProperty(this.container, "border-top-left-radius"), 10);
            this.move(config ?? this.state.config);
        }
        /**
         * Moves the tooltip to a new position
         * @throws {@link PSVError} if the configuration is invalid
         */
        move(config) {
            if (this.state.state !== 1 /* SHOWING */ && this.state.state !== 3 /* READY */) {
                throw new PSVError("Uninitialized tooltip cannot be moved");
            }
            config.box = config.box ?? this.state.config?.box ?? { width: 0, height: 0 };
            this.state.config = config;
            const t = this.container;
            const a = this.arrow;
            const style = {
                posClass: cleanCssPosition(config.position, { allowCenter: false, cssOrder: false }) || ["top", "center"],
                width: this.state.width,
                height: this.state.height,
                top: 0,
                left: 0,
                arrowTop: 0,
                arrowLeft: 0
            };
            this.__computeTooltipPosition(style, config);
            let swapY = null;
            let swapX = null;
            if (style.top < 0) {
                swapY = "bottom";
            } else if (style.top + style.height > this.viewer.state.size.height) {
                swapY = "top";
            }
            if (style.left < 0) {
                swapX = "right";
            } else if (style.left + style.width > this.viewer.state.size.width) {
                swapX = "left";
            }
            if (swapX || swapY) {
                const ordered = cssPositionIsOrdered(style.posClass);
                if (swapY) {
                    style.posClass[ordered ? 0 : 1] = swapY;
                }
                if (swapX) {
                    style.posClass[ordered ? 1 : 0] = swapX;
                }
                this.__computeTooltipPosition(style, config);
            }
            t.style.top = style.top + "px";
            t.style.left = style.left + "px";
            a.style.top = style.arrowTop + "px";
            a.style.left = style.arrowLeft + "px";
            const newPos = style.posClass.join("-");
            if (newPos !== this.state.pos) {
                t.classList.remove(`psv-tooltip--${this.state.pos}`);
                this.state.pos = newPos;
                t.classList.add(`psv-tooltip--${this.state.pos}`);
            }
        }
        /**
         * Hides the tooltip
         */
        hide() {
            this.container.classList.remove("psv-tooltip--visible");
            this.state.state = 2 /* HIDING */;
            this.viewer.dispatchEvent(new HideTooltipEvent(this.state.data));
        }
        /**
         * Finalize transition
         */
        __onTransitionEnd(e) {
            if (e.propertyName === "transform") {
                switch (this.state.state) {
                    case 1 /* SHOWING */:
                        this.container.classList.add("psv-tooltip--visible");
                        this.state.state = 3 /* READY */;
                        break;
                    case 2 /* HIDING */:
                        this.state.state = 0 /* NONE */;
                        this.destroy();
                        break;
                    default:
                }
            }
        }
        /**
         * Computes the position of the tooltip and its arrow
         */
        __computeTooltipPosition(style, config) {
            const arrow = this.state.arrow;
            const top = config.top;
            const height = style.height;
            const left = config.left;
            const width = style.width;
            const offsetSide = arrow + this.state.border;
            const offsetX = config.box.width / 2 + arrow * 2;
            const offsetY = config.box.height / 2 + arrow * 2;
            switch (style.posClass.join("-")) {
                case "top-left":
                    style.top = top - offsetY - height;
                    style.left = left + offsetSide - width;
                    style.arrowTop = height;
                    style.arrowLeft = width - offsetSide - arrow;
                    break;
                case "top-center":
                    style.top = top - offsetY - height;
                    style.left = left - width / 2;
                    style.arrowTop = height;
                    style.arrowLeft = width / 2 - arrow;
                    break;
                case "top-right":
                    style.top = top - offsetY - height;
                    style.left = left - offsetSide;
                    style.arrowTop = height;
                    style.arrowLeft = arrow;
                    break;
                case "bottom-left":
                    style.top = top + offsetY;
                    style.left = left + offsetSide - width;
                    style.arrowTop = -arrow * 2;
                    style.arrowLeft = width - offsetSide - arrow;
                    break;
                case "bottom-center":
                    style.top = top + offsetY;
                    style.left = left - width / 2;
                    style.arrowTop = -arrow * 2;
                    style.arrowLeft = width / 2 - arrow;
                    break;
                case "bottom-right":
                    style.top = top + offsetY;
                    style.left = left - offsetSide;
                    style.arrowTop = -arrow * 2;
                    style.arrowLeft = arrow;
                    break;
                case "left-top":
                    style.top = top + offsetSide - height;
                    style.left = left - offsetX - width;
                    style.arrowTop = height - offsetSide - arrow;
                    style.arrowLeft = width;
                    break;
                case "center-left":
                    style.top = top - height / 2;
                    style.left = left - offsetX - width;
                    style.arrowTop = height / 2 - arrow;
                    style.arrowLeft = width;
                    break;
                case "left-bottom":
                    style.top = top - offsetSide;
                    style.left = left - offsetX - width;
                    style.arrowTop = arrow;
                    style.arrowLeft = width;
                    break;
                case "right-top":
                    style.top = top + offsetSide - height;
                    style.left = left + offsetX;
                    style.arrowTop = height - offsetSide - arrow;
                    style.arrowLeft = -arrow * 2;
                    break;
                case "center-right":
                    style.top = top - height / 2;
                    style.left = left + offsetX;
                    style.arrowTop = height / 2 - arrow;
                    style.arrowLeft = -arrow * 2;
                    break;
                case "right-bottom":
                    style.top = top - offsetSide;
                    style.left = left + offsetX;
                    style.arrowTop = arrow;
                    style.arrowLeft = -arrow * 2;
                    break;
            }
        }
        /**
         * If the tooltip contains images, recompute its size once they are loaded
         */
        __waitImages() {
            const images = this.content.querySelectorAll("img");
            if (images.length > 0) {
                const promises = [];
                images.forEach((image) => {
                    promises.push(
                        new Promise((resolve) => {
                            image.onload = resolve;
                            image.onerror = resolve;
                        })
                    );
                });
                Promise.all(promises).then(() => {
                    if (this.state.state === 1 /* SHOWING */ || this.state.state === 3 /* READY */) {
                        const rect = this.container.getBoundingClientRect();
                        this.state.width = rect.right - rect.left;
                        this.state.height = rect.bottom - rect.top;
                        this.move(this.state.config);
                    }
                });
            }
        }
    };

    // src/data/cache.ts
    var import_three6 = require_three();
    var Cache = {
        enabled: true,
        maxItems: 10,
        ttl: 10 * 60,
        items: {},
        purgeInterval: null,
        init() {
            if (import_three6.Cache.enabled) {
                logWarn("ThreeJS cache should be disabled");
                import_three6.Cache.enabled = false;
            }
            if (!this.purgeInterval && this.enabled) {
                this.purgeInterval = setInterval(() => this.purge(), 60 * 1e3);
            }
        },
        add(url, key, data) {
            if (this.enabled && key) {
                this.items[key] = this.items[key] ?? { files: {}, lastAccess: null };
                this.items[key].files[url] = data;
                this.items[key].lastAccess = Date.now();
            }
        },
        get(url, key) {
            if (this.enabled && key && this.items[key]) {
                this.items[key].lastAccess = Date.now();
                return this.items[key].files[url];
            }
        },
        remove(url, key) {
            if (this.enabled && key && this.items[key]) {
                delete this.items[key].files[url];
                if (Object.keys(this.items[key].files).length === 0) {
                    delete this.items[key];
                }
            }
        },
        purge() {
            Object.entries(this.items).sort(([, a], [, b]) => {
                return b.lastAccess - a.lastAccess;
            }).forEach(([key, { lastAccess }], index) => {
                if (index > 0 && (Date.now() - lastAccess >= this.ttl * 1e3 || index >= this.maxItems)) {
                    delete this.items[key];
                }
            });
        }
    };

    // src/icons/error.svg
    var error_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="15 15 70 70"><path fill="currentColor" d="M50,16.2c-18.6,0-33.8,15.1-33.8,33.8S31.4,83.7,50,83.7S83.8,68.6,83.8,50S68.6,16.2,50,16.2z M50,80.2c-16.7,0-30.2-13.6-30.2-30.2S33.3,19.7,50,19.7S80.3,33.3,80.3,50S66.7,80.2,50,80.2z"/><rect fill="currentColor" x="48" y="31.7" width="4" height="28"/><rect fill="currentColor" x="48" y="63.2" width="4" height="5"/><!--Created by Shastry from the Noun Project--></svg>\n';

    // src/services/DataHelper.ts
    var import_three7 = require_three();

    // src/services/AbstractService.ts
    var AbstractService = class {
        /**
         * @internal
         */
        constructor(viewer) {
            this.viewer = viewer;
            this.config = viewer.config;
            this.state = viewer.state;
        }
        /**
         * Destroys the service
         * @internal
         */
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        destroy() {
        }
    };

    // src/services/DataHelper.ts
    var vector3 = new import_three7.Vector3();
    var EULER_ZERO = new import_three7.Euler(0, 0, 0, "ZXY");
    var DataHelper = class extends AbstractService {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer);
        }
        /**
         * Converts vertical FOV to zoom level
         */
        fovToZoomLevel(fov) {
            const temp = Math.round((fov - this.config.minFov) / (this.config.maxFov - this.config.minFov) * 100);
            return temp - 2 * (temp - 50);
        }
        /**
         * Converts zoom level to vertical FOV
         */
        zoomLevelToFov(level) {
            return this.config.maxFov + level / 100 * (this.config.minFov - this.config.maxFov);
        }
        /**
         * Converts vertical FOV to horizontal FOV
         */
        vFovToHFov(vFov) {
            return import_three7.MathUtils.radToDeg(2 * Math.atan(Math.tan(import_three7.MathUtils.degToRad(vFov) / 2) * this.state.aspect));
        }
        /**
         * @internal
         */
        getAnimationProperties(speed, targetPosition, targetZoom) {
            const positionProvided = !isNil(targetPosition);
            const zoomProvided = !isNil(targetZoom);
            const properties = {};
            let duration;
            if (positionProvided) {
                const currentPosition = this.viewer.getPosition();
                const dYaw = getShortestArc(currentPosition.yaw, targetPosition.yaw);
                properties.yaw = { start: currentPosition.yaw, end: currentPosition.yaw + dYaw };
                properties.pitch = { start: currentPosition.pitch, end: targetPosition.pitch };
                duration = speedToDuration(speed, getAngle(currentPosition, targetPosition));
            }
            if (zoomProvided) {
                const currentZoom = this.viewer.getZoomLevel();
                const dZoom = Math.abs(targetZoom - currentZoom);
                properties.zoom = { start: currentZoom, end: targetZoom };
                if (!duration) {
                    duration = speedToDuration(speed, Math.PI / 4 * dZoom / 100);
                }
            }
            duration = Math.max(ANIMATION_MIN_DURATION, duration);
            return { duration, properties };
        }
        /**
         * Converts pixel texture coordinates to spherical radians coordinates
         * @throws {@link PSVError} when the current adapter does not support texture coordinates
         */
        textureCoordsToSphericalCoords(point) {
            const panoData = this.state.panoData;
            if (!panoData) {
                throw new PSVError("Current adapter does not support texture coordinates.");
            }
            const relativeX = (point.textureX + panoData.croppedX) / panoData.fullWidth * Math.PI * 2;
            const relativeY = (point.textureY + panoData.croppedY) / panoData.fullHeight * Math.PI;
            const result = {
                yaw: relativeX >= Math.PI ? relativeX - Math.PI : relativeX + Math.PI,
                pitch: Math.PI / 2 - relativeY
            };
            if (!EULER_ZERO.equals(this.viewer.renderer.panoramaPose) || !EULER_ZERO.equals(this.viewer.renderer.sphereCorrection)) {
                this.sphericalCoordsToVector3(result, vector3);
                vector3.applyEuler(this.viewer.renderer.panoramaPose);
                vector3.applyEuler(this.viewer.renderer.sphereCorrection);
                return this.vector3ToSphericalCoords(vector3);
            } else {
                return result;
            }
        }
        /**
         * Converts spherical radians coordinates to pixel texture coordinates
         * @throws {@link PSVError} when the current adapter does not support texture coordinates
         */
        sphericalCoordsToTextureCoords(position) {
            const panoData = this.state.panoData;
            if (!panoData) {
                throw new PSVError("Current adapter does not support texture coordinates.");
            }
            if (!EULER_ZERO.equals(this.viewer.renderer.panoramaPose) || !EULER_ZERO.equals(this.viewer.renderer.sphereCorrection)) {
                this.sphericalCoordsToVector3(position, vector3);
                applyEulerInverse(vector3, this.viewer.renderer.sphereCorrection);
                applyEulerInverse(vector3, this.viewer.renderer.panoramaPose);
                position = this.vector3ToSphericalCoords(vector3);
            }
            const relativeLong = position.yaw / Math.PI / 2 * panoData.fullWidth;
            const relativeLat = position.pitch / Math.PI * panoData.fullHeight;
            return {
                textureX: Math.round(
                    position.yaw < Math.PI ? relativeLong + panoData.fullWidth / 2 : relativeLong - panoData.fullWidth / 2
                ) - panoData.croppedX,
                textureY: Math.round(panoData.fullHeight / 2 - relativeLat) - panoData.croppedY
            };
        }
        /**
         * Converts spherical radians coordinates to a Vector3
         */
        sphericalCoordsToVector3(position, vector, distance2 = SPHERE_RADIUS) {
            if (!vector) {
                vector = new import_three7.Vector3();
            }
            vector.x = distance2 * -Math.cos(position.pitch) * Math.sin(position.yaw);
            vector.y = distance2 * Math.sin(position.pitch);
            vector.z = distance2 * Math.cos(position.pitch) * Math.cos(position.yaw);
            return vector;
        }
        /**
         * Converts a Vector3 to spherical radians coordinates
         */
        vector3ToSphericalCoords(vector) {
            const phi = Math.acos(vector.y / Math.sqrt(vector.x * vector.x + vector.y * vector.y + vector.z * vector.z));
            const theta = Math.atan2(vector.x, vector.z);
            return {
                yaw: theta < 0 ? -theta : Math.PI * 2 - theta,
                pitch: Math.PI / 2 - phi
            };
        }
        /**
         * Converts position on the viewer to a THREE.Vector3
         */
        viewerCoordsToVector3(viewerPoint) {
            const sphereIntersect = this.viewer.renderer.getIntersections(viewerPoint).filter((i) => i.object.userData[VIEWER_DATA]);
            if (sphereIntersect.length) {
                return sphereIntersect[0].point;
            } else {
                return null;
            }
        }
        /**
         * Converts position on the viewer to spherical radians coordinates
         */
        viewerCoordsToSphericalCoords(viewerPoint) {
            const vector = this.viewerCoordsToVector3(viewerPoint);
            return vector ? this.vector3ToSphericalCoords(vector) : null;
        }
        /**
         * Converts a Vector3 to position on the viewer
         */
        vector3ToViewerCoords(vector) {
            const vectorClone = vector.clone();
            vectorClone.project(this.viewer.renderer.camera);
            return {
                x: Math.round((vectorClone.x + 1) / 2 * this.state.size.width),
                y: Math.round((1 - vectorClone.y) / 2 * this.state.size.height)
            };
        }
        /**
         * Converts spherical radians coordinates to position on the viewer
         */
        sphericalCoordsToViewerCoords(position) {
            this.sphericalCoordsToVector3(position, vector3);
            return this.vector3ToViewerCoords(vector3);
        }
        /**
         * @internal
         */
        isPointVisible(point) {
            let vector;
            let viewerPoint;
            if (point instanceof import_three7.Vector3) {
                vector = point;
                viewerPoint = this.vector3ToViewerCoords(point);
            } else if (isExtendedPosition(point)) {
                vector = this.sphericalCoordsToVector3(point, vector3);
                viewerPoint = this.vector3ToViewerCoords(vector);
            } else {
                return false;
            }
            return vector.dot(this.viewer.state.direction) > 0 && viewerPoint.x >= 0 && viewerPoint.x <= this.viewer.state.size.width && viewerPoint.y >= 0 && viewerPoint.y <= this.viewer.state.size.height;
        }
        /**
         * Converts pixel position to angles if present and ensure boundaries
         */
        cleanPosition(position) {
            if (position.textureX !== void 0 && position.textureY !== void 0) {
                return this.textureCoordsToSphericalCoords(position);
            }
            return {
                yaw: parseAngle(position.yaw),
                pitch: parseAngle(position.pitch, !this.state.littlePlanet)
            };
        }
        /**
         * Ensure a SphereCorrection object is valid
         */
        cleanSphereCorrection(sphereCorrection) {
            return {
                pan: parseAngle(sphereCorrection?.pan || 0),
                tilt: parseAngle(sphereCorrection?.tilt || 0, true),
                roll: parseAngle(sphereCorrection?.roll || 0, true, false)
            };
        }
        /**
         * Parse the pose angles of the pano data
         */
        cleanPanoramaPose(panoData) {
            return {
                pan: import_three7.MathUtils.degToRad(panoData?.poseHeading || 0),
                tilt: import_three7.MathUtils.degToRad(panoData?.posePitch || 0),
                roll: import_three7.MathUtils.degToRad(panoData?.poseRoll || 0)
            };
        }
    };

    // src/services/EventsHandler.ts
    var import_three8 = require_three();

    // src/icons/gesture.svg
    var gesture_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="currentColor" d="M33.38 33.2a1.96 1.96 0 0 0 1.5-3.23 10.61 10.61 0 0 1 7.18-17.51c.7-.06 1.31-.49 1.61-1.12a13.02 13.02 0 0 1 11.74-7.43c7.14 0 12.96 5.8 12.96 12.9 0 3.07-1.1 6.05-3.1 8.38-.7.82-.61 2.05.21 2.76.83.7 2.07.6 2.78-.22a16.77 16.77 0 0 0 4.04-10.91C72.3 7.54 64.72 0 55.4 0a16.98 16.98 0 0 0-14.79 8.7 14.6 14.6 0 0 0-12.23 14.36c0 3.46 1.25 6.82 3.5 9.45.4.45.94.69 1.5.69m45.74 43.55a22.13 22.13 0 0 1-5.23 12.4c-4 4.55-9.53 6.86-16.42 6.86-12.6 0-20.1-10.8-20.17-10.91a1.82 1.82 0 0 0-.08-.1c-5.3-6.83-14.55-23.82-17.27-28.87-.05-.1 0-.21.02-.23a6.3 6.3 0 0 1 8.24 1.85l9.38 12.59a1.97 1.97 0 0 0 3.54-1.17V25.34a4 4 0 0 1 1.19-2.87 3.32 3.32 0 0 1 2.4-.95c1.88.05 3.4 1.82 3.4 3.94v24.32a1.96 1.96 0 0 0 3.93 0v-33.1a3.5 3.5 0 0 1 7 0v35.39a1.96 1.96 0 0 0 3.93 0v-.44c.05-2.05 1.6-3.7 3.49-3.7 1.93 0 3.5 1.7 3.5 3.82v5.63c0 .24.04.48.13.71l.1.26a1.97 1.97 0 0 0 3.76-.37c.33-1.78 1.77-3.07 3.43-3.07 1.9 0 3.45 1.67 3.5 3.74l-1.77 18.1zM77.39 51c-1.25 0-2.45.32-3.5.9v-.15c0-4.27-3.33-7.74-7.42-7.74-1.26 0-2.45.33-3.5.9V16.69a7.42 7.42 0 0 0-14.85 0v1.86a7 7 0 0 0-3.28-.94 7.21 7.21 0 0 0-5.26 2.07 7.92 7.92 0 0 0-2.38 5.67v37.9l-5.83-7.82a10.2 10.2 0 0 0-13.35-2.92 4.1 4.1 0 0 0-1.53 5.48C20 64.52 28.74 80.45 34.07 87.34c.72 1.04 9.02 12.59 23.4 12.59 7.96 0 14.66-2.84 19.38-8.2a26.06 26.06 0 0 0 6.18-14.6l1.78-18.2v-.2c0-4.26-3.32-7.73-7.42-7.73z"/><!--Created by AomAm from the Noun Project--></svg>\n';

    // src/icons/mousewheel.svg
    var mousewheel_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="10 17 79 79"><path fill="currentColor" d="M38.1 29.27c-.24 0-.44.2-.44.45v10.7a.45.45 0 00.9 0v-10.7c0-.25-.2-.45-.45-.45zm10.2 26.66a11.54 11.54 0 01-8.48-6.14.45.45 0 10-.8.41 12.45 12.45 0 009.22 6.62.45.45 0 00.07-.9zm24.55-13.08a23.04 23.04 0 00-22.56-23v7.07l-.01.05a2.83 2.83 0 012.39 2.78v14.03l.09-.02h8.84v-9.22a.45.45 0 11.9 0v9.22h10.35v-.9zm0 27.33V44.66H62.5c-.02 2.01-.52 4-1.47 5.76a.45.45 0 01-.61.18.45.45 0 01-.19-.61 11.54 11.54 0 001.36-5.33h-8.83l-.1-.01a2.83 2.83 0 01-2.83 2.84h-.04-.04a2.83 2.83 0 01-2.83-2.83v-14.9a2.82 2.82 0 012.47-2.8v-7.11a23.04 23.04 0 00-22.57 23v.91h14.72V29.88a8.2 8.2 0 015.02-7.57c.22-.1.5.01.59.24.1.23-.01.5-.24.6a7.3 7.3 0 00-4.47 6.73v13.88h3.9a.45.45 0 110 .9h-3.9v.15a7.32 7.32 0 0011.23 6.17.45.45 0 01.49.76 8.22 8.22 0 01-12.62-6.93v-.15H26.82v25.52a23.04 23.04 0 0023.01 23.01 23.04 23.04 0 0023.02-23.01zm1.8-27.33v27.33A24.85 24.85 0 0149.84 95a24.85 24.85 0 01-24.82-24.82V42.85a24.85 24.85 0 0124.82-24.82 24.85 24.85 0 0124.83 24.82zM57.98 29.88v9.36a.45.45 0 11-.9 0v-9.36a7.28 7.28 0 00-3.4-6.17.45.45 0 01.49-.76 8.18 8.18 0 013.8 6.93z"/><!-- Created by Icon Island from the Noun Project --></svg>\n';

    // src/services/EventsHandler.ts
    var _Step = class _Step {
        constructor() {
            this.$ = _Step.IDLE;
        }
        is(...steps) {
            return steps.some((step) => this.$ & step);
        }
        set(step) {
            this.$ = step;
        }
        add(step) {
            this.$ |= step;
        }
        remove(step) {
            this.$ &= ~step;
        }
    };
    _Step.IDLE = 0;
    _Step.CLICK = 1;
    _Step.MOVING = 2;
    _Step.INERTIA = 4;
    var Step = _Step;
    var EventsHandler = class extends AbstractService {
        constructor(viewer) {
            super(viewer);
            this.data = {
                /** start x position of the click/touch */
                startMouseX: 0,
                /** start y position of the click/touch */
                startMouseY: 0,
                /** current x position of the cursor */
                mouseX: 0,
                /** current y position of the cursor */
                mouseY: 0,
                /** list of latest positions of the cursor, [time, x, y] */
                mouseHistory: [],
                /** distance between fingers when zooming */
                pinchDist: 0,
                /** when the Ctrl key is pressed */
                ctrlKeyDown: false,
                /** temporary storage of click data between two clicks */
                dblclickData: null,
                dblclickTimeout: null,
                longtouchTimeout: null,
                twofingersTimeout: null,
                ctrlZoomTimeout: null
            };
            this.step = new Step();
            this.keyHandler = new PressHandler();
            this.resizeObserver = new ResizeObserver(throttle(() => this.viewer.autoSize(), 50));
            this.moveThreshold = MOVE_THRESHOLD * SYSTEM.pixelRatio;
        }
        /**
         * @internal
         */
        init() {
            window.addEventListener("keydown", this, { passive: false });
            window.addEventListener("keyup", this);
            this.viewer.container.addEventListener("mousedown", this);
            window.addEventListener("mousemove", this, { passive: false });
            window.addEventListener("mouseup", this);
            this.viewer.container.addEventListener("touchstart", this, { passive: false });
            window.addEventListener("touchmove", this, { passive: false });
            window.addEventListener("touchend", this, { passive: false });
            this.viewer.container.addEventListener("wheel", this, { passive: false });
            document.addEventListener(SYSTEM.fullscreenEvent, this);
            this.resizeObserver.observe(this.viewer.container);
        }
        destroy() {
            window.removeEventListener("keydown", this);
            window.removeEventListener("keyup", this);
            this.viewer.container.removeEventListener("mousedown", this);
            window.removeEventListener("mousemove", this);
            window.removeEventListener("mouseup", this);
            this.viewer.container.removeEventListener("touchstart", this);
            window.removeEventListener("touchmove", this);
            window.removeEventListener("touchend", this);
            this.viewer.container.removeEventListener("wheel", this);
            document.removeEventListener(SYSTEM.fullscreenEvent, this);
            this.resizeObserver.disconnect();
            clearTimeout(this.data.dblclickTimeout);
            clearTimeout(this.data.longtouchTimeout);
            clearTimeout(this.data.twofingersTimeout);
            clearTimeout(this.data.ctrlZoomTimeout);
            super.destroy();
        }
        /**
         * @internal
         */
        handleEvent(evt) {
            switch (evt.type) {
                case "keydown":
                    this.__onKeyDown(evt);
                    break;
                case "keyup":
                    this.__onKeyUp();
                    break;
                case "mousemove":
                    this.__onMouseMove(evt);
                    break;
                case "mouseup":
                    this.__onMouseUp(evt);
                    break;
                case "touchmove":
                    this.__onTouchMove(evt);
                    break;
                case "touchend":
                    this.__onTouchEnd(evt);
                    break;
                case SYSTEM.fullscreenEvent:
                    this.__onFullscreenChange();
                    break;
            }
            if (!getClosest(evt.target, "." + CAPTURE_EVENTS_CLASS)) {
                switch (evt.type) {
                    case "mousedown":
                        this.__onMouseDown(evt);
                        break;
                    case "touchstart":
                        this.__onTouchStart(evt);
                        break;
                    case "wheel":
                        this.__onMouseWheel(evt);
                        break;
                }
            }
        }
        /**
         * Handles keyboard events
         */
        __onKeyDown(e) {
            if (this.config.mousewheelCtrlKey) {
                this.data.ctrlKeyDown = e.key === KEY_CODES.Control;
                if (this.data.ctrlKeyDown) {
                    clearTimeout(this.data.ctrlZoomTimeout);
                    this.viewer.overlay.hide(IDS.CTRL_ZOOM);
                }
            }
            if (!this.viewer.dispatchEvent(new KeypressEvent(e.key))) {
                return;
            }
            if (!this.state.keyboardEnabled) {
                return;
            }
            const action = this.config.keyboardActions?.[e.key];
            if (typeof action === "function") {
                action(this.viewer);
                e.preventDefault();
            } else if (action && !this.keyHandler.pending) {
                if (action !== "ZOOM_IN" /* ZOOM_IN */ && action !== "ZOOM_OUT" /* ZOOM_OUT */) {
                    this.viewer.stopAll();
                }
                switch (action) {
                    case "ROTATE_UP" /* ROTATE_UP */:
                        this.viewer.dynamics.position.roll({ pitch: false });
                        break;
                    case "ROTATE_DOWN" /* ROTATE_DOWN */:
                        this.viewer.dynamics.position.roll({ pitch: true });
                        break;
                    case "ROTATE_RIGHT" /* ROTATE_RIGHT */:
                        this.viewer.dynamics.position.roll({ yaw: false });
                        break;
                    case "ROTATE_LEFT" /* ROTATE_LEFT */:
                        this.viewer.dynamics.position.roll({ yaw: true });
                        break;
                    case "ZOOM_IN" /* ZOOM_IN */:
                        this.viewer.dynamics.zoom.roll(false);
                        break;
                    case "ZOOM_OUT" /* ZOOM_OUT */:
                        this.viewer.dynamics.zoom.roll(true);
                        break;
                }
                this.keyHandler.down();
                e.preventDefault();
            }
        }
        /**
         * Handles keyboard events
         */
        __onKeyUp() {
            this.data.ctrlKeyDown = false;
            if (!this.state.keyboardEnabled) {
                return;
            }
            this.keyHandler.up(() => {
                this.viewer.dynamics.position.stop();
                this.viewer.dynamics.zoom.stop();
                this.viewer.resetIdleTimer();
            });
        }
        /**
         * Handles mouse down events
         */
        __onMouseDown(evt) {
            this.step.add(Step.CLICK);
            this.data.startMouseX = evt.clientX;
            this.data.startMouseY = evt.clientY;
        }
        /**
         *Handles mouse up events
         */
        __onMouseUp(evt) {
            if (this.step.is(Step.CLICK, Step.MOVING)) {
                this.__stopMove(evt.clientX, evt.clientY, evt.target, evt.button === 2);
            }
        }
        /**
         * Handles mouse move events
         */
        __onMouseMove(evt) {
            if (this.config.mousemove && this.step.is(Step.CLICK, Step.MOVING)) {
                evt.preventDefault();
                this.__doMove(evt.clientX, evt.clientY);
            }
            this.__handleObjectsEvents(evt);
        }
        /**
         * Handles touch events
         */
        __onTouchStart(evt) {
            if (evt.touches.length === 1) {
                this.step.add(Step.CLICK);
                this.data.startMouseX = evt.touches[0].clientX;
                this.data.startMouseY = evt.touches[0].clientY;
                if (!this.data.longtouchTimeout) {
                    this.data.longtouchTimeout = setTimeout(() => {
                        const touch = evt.touches[0];
                        this.__stopMove(touch.clientX, touch.clientY, touch.target, true);
                        this.data.longtouchTimeout = null;
                    }, LONGTOUCH_DELAY);
                }
            } else if (evt.touches.length === 2) {
                this.step.set(Step.IDLE);
                this.__cancelLongTouch();
                if (this.config.mousemove) {
                    this.__cancelTwoFingersOverlay();
                    this.__startMoveZoom(evt);
                    evt.preventDefault();
                }
            }
        }
        /**
         * Handles touch events
         */
        __onTouchEnd(evt) {
            this.__cancelLongTouch();
            if (this.step.is(Step.CLICK, Step.MOVING)) {
                evt.preventDefault();
                this.__cancelTwoFingersOverlay();
                if (evt.touches.length === 1) {
                    this.__stopMove(this.data.mouseX, this.data.mouseY);
                } else if (evt.touches.length === 0) {
                    const touch = evt.changedTouches[0];
                    this.__stopMove(touch.clientX, touch.clientY, touch.target);
                }
            }
        }
        /**
         * Handles touch move events
         */
        __onTouchMove(evt) {
            this.__cancelLongTouch();
            if (!this.config.mousemove) {
                return;
            }
            if (evt.touches.length === 1) {
                if (this.config.touchmoveTwoFingers) {
                    if (this.step.is(Step.CLICK) && !this.data.twofingersTimeout) {
                        this.data.twofingersTimeout = setTimeout(() => {
                            this.viewer.overlay.show({
                                id: IDS.TWO_FINGERS,
                                image: gesture_default,
                                title: this.config.lang.twoFingers
                            });
                        }, TWOFINGERSOVERLAY_DELAY);
                    }
                } else if (this.step.is(Step.CLICK, Step.MOVING)) {
                    evt.preventDefault();
                    const touch = evt.touches[0];
                    this.__doMove(touch.clientX, touch.clientY);
                }
            } else {
                this.__doMoveZoom(evt);
                this.__cancelTwoFingersOverlay();
            }
        }
        /**
         * Cancel the long touch timer if any
         */
        __cancelLongTouch() {
            if (this.data.longtouchTimeout) {
                clearTimeout(this.data.longtouchTimeout);
                this.data.longtouchTimeout = null;
            }
        }
        /**
         * Cancel the two fingers overlay timer if any
         */
        __cancelTwoFingersOverlay() {
            if (this.config.touchmoveTwoFingers) {
                if (this.data.twofingersTimeout) {
                    clearTimeout(this.data.twofingersTimeout);
                    this.data.twofingersTimeout = null;
                }
                this.viewer.overlay.hide(IDS.TWO_FINGERS);
            }
        }
        /**
         * Handles mouse wheel events
         */
        __onMouseWheel(evt) {
            if (!this.config.mousewheel || !evt.deltaY) {
                return;
            }
            if (this.config.mousewheelCtrlKey && !this.data.ctrlKeyDown) {
                this.viewer.overlay.show({
                    id: IDS.CTRL_ZOOM,
                    image: mousewheel_default,
                    title: this.config.lang.ctrlZoom
                });
                clearTimeout(this.data.ctrlZoomTimeout);
                this.data.ctrlZoomTimeout = setTimeout(() => this.viewer.overlay.hide(IDS.CTRL_ZOOM), CTRLZOOM_TIMEOUT);
                return;
            }
            evt.preventDefault();
            evt.stopPropagation();
            const delta = evt.deltaY / Math.abs(evt.deltaY) * 5 * this.config.zoomSpeed;
            if (delta !== 0) {
                this.viewer.dynamics.zoom.step(-delta, 5);
            }
        }
        /**
         * Handles fullscreen events
         */
        __onFullscreenChange() {
            const fullscreen = this.viewer.isFullscreenEnabled();
            if (this.config.keyboard === "fullscreen") {
                if (fullscreen) {
                    this.viewer.startKeyboardControl();
                } else {
                    this.viewer.stopKeyboardControl();
                }
            }
            this.viewer.dispatchEvent(new FullscreenEvent(fullscreen));
        }
        /**
         * Resets all state variables
         */
        __resetMove() {
            this.step.set(Step.IDLE);
            this.data.mouseX = 0;
            this.data.mouseY = 0;
            this.data.startMouseX = 0;
            this.data.startMouseY = 0;
            this.data.mouseHistory.length = 0;
        }
        /**
         * Initializes the combines move and zoom
         */
        __startMoveZoom(evt) {
            this.viewer.stopAll();
            this.__resetMove();
            const touchData = getTouchData(evt);
            this.step.set(Step.MOVING);
            ({
                distance: this.data.pinchDist,
                center: { x: this.data.mouseX, y: this.data.mouseY }
            } = touchData);
            this.__logMouseMove(this.data.mouseX, this.data.mouseY);
        }
        /**
         * Stops the movement
         * @description If the move threshold was not reached a click event is triggered, otherwise an animation is launched to simulate inertia
         */
        __stopMove(clientX, clientY, target, rightclick = false) {
            if (this.step.is(Step.MOVING)) {
                if (this.config.moveInertia) {
                    this.__logMouseMove(clientX, clientY);
                    this.__stopMoveInertia(clientX, clientY);
                } else {
                    this.__resetMove();
                    this.viewer.resetIdleTimer();
                }
            } else {
                if (this.step.is(Step.CLICK) && !this.__moveThresholdReached(clientX, clientY)) {
                    this.__doClick(clientX, clientY, target, rightclick);
                }
                this.step.remove(Step.CLICK);
                if (!this.step.is(Step.INERTIA)) {
                    this.__resetMove();
                    this.viewer.resetIdleTimer();
                }
            }
        }
        /**
         * Performs an animation to simulate inertia when the movement stops
         */
        __stopMoveInertia(clientX, clientY) {
            const curve = new import_three8.SplineCurve(this.data.mouseHistory.map(([, x, y]) => new import_three8.Vector2(x, y)));
            const direction = curve.getTangent(1);
            const speed = this.data.mouseHistory.reduce(({ total, prev }, curr) => ({
                total: !prev ? 0 : total + distance({ x: prev[1], y: prev[2] }, { x: curr[1], y: curr[2] }) / (curr[0] - prev[0]),
                prev: curr
            }), {
                total: 0,
                prev: null
            }).total / this.data.mouseHistory.length;
            if (!speed) {
                this.__resetMove();
                this.viewer.resetIdleTimer();
                return;
            }
            this.step.set(Step.INERTIA);
            let currentClientX = clientX;
            let currentClientY = clientY;
            this.state.animation = new Animation({
                properties: {
                    speed: { start: speed, end: 0 }
                },
                duration: 1e3,
                easing: "outQuad",
                onTick: (properties) => {
                    currentClientX += properties.speed * direction.x * 3 * SYSTEM.pixelRatio;
                    currentClientY += properties.speed * direction.y * 3 * SYSTEM.pixelRatio;
                    this.__applyMove(currentClientX, currentClientY);
                }
            });
            this.state.animation.then((done) => {
                this.state.animation = null;
                if (done) {
                    this.__resetMove();
                    this.viewer.resetIdleTimer();
                }
            });
        }
        /**
         * Triggers an event with all coordinates when a simple click is performed
         */
        __doClick(clientX, clientY, target, rightclick = false) {
            const boundingRect = this.viewer.container.getBoundingClientRect();
            const viewerX = clientX - boundingRect.left;
            const viewerY = clientY - boundingRect.top;
            const intersections = this.viewer.renderer.getIntersections({ x: viewerX, y: viewerY });
            const sphereIntersection = intersections.find((i) => i.object.userData[VIEWER_DATA]);
            if (sphereIntersection) {
                const sphericalCoords = this.viewer.dataHelper.vector3ToSphericalCoords(sphereIntersection.point);
                const data = {
                    rightclick,
                    target,
                    clientX,
                    clientY,
                    viewerX,
                    viewerY,
                    yaw: sphericalCoords.yaw,
                    pitch: sphericalCoords.pitch,
                    objects: intersections.map((i) => i.object).filter((o) => !o.userData[VIEWER_DATA])
                };
                try {
                    const textureCoords = this.viewer.dataHelper.sphericalCoordsToTextureCoords(data);
                    data.textureX = textureCoords.textureX;
                    data.textureY = textureCoords.textureY;
                } catch (e) {
                    data.textureX = NaN;
                    data.textureY = NaN;
                }
                if (!this.data.dblclickTimeout) {
                    this.viewer.dispatchEvent(new ClickEvent(data));
                    this.data.dblclickData = clone(data);
                    this.data.dblclickTimeout = setTimeout(() => {
                        this.data.dblclickTimeout = null;
                        this.data.dblclickData = null;
                    }, DBLCLICK_DELAY);
                } else {
                    if (Math.abs(this.data.dblclickData.clientX - data.clientX) < this.moveThreshold && Math.abs(this.data.dblclickData.clientY - data.clientY) < this.moveThreshold) {
                        this.viewer.dispatchEvent(new DoubleClickEvent(this.data.dblclickData));
                    }
                    clearTimeout(this.data.dblclickTimeout);
                    this.data.dblclickTimeout = null;
                    this.data.dblclickData = null;
                }
            }
        }
        /**
         * Trigger events for observed THREE objects
         */
        __handleObjectsEvents(evt) {
            if (!isEmpty(this.state.objectsObservers) && hasParent(evt.target, this.viewer.container)) {
                const viewerPos = getPosition(this.viewer.container);
                const viewerPoint = {
                    x: evt.clientX - viewerPos.x,
                    y: evt.clientY - viewerPos.y
                };
                const intersections = this.viewer.renderer.getIntersections(viewerPoint);
                const emit = (object, key, evtCtor) => {
                    this.viewer.dispatchEvent(new evtCtor(evt, object, viewerPoint, key));
                };
                for (const [key, object] of Object.entries(this.state.objectsObservers)) {
                    const intersection = intersections.find((i) => i.object.userData[key]);
                    if (intersection) {
                        if (object && intersection.object !== object) {
                            emit(object, key, ObjectLeaveEvent);
                            this.state.objectsObservers[key] = null;
                        }
                        if (!object) {
                            this.state.objectsObservers[key] = intersection.object;
                            emit(intersection.object, key, ObjectEnterEvent);
                        } else {
                            emit(intersection.object, key, ObjectHoverEvent);
                        }
                    } else if (object) {
                        emit(object, key, ObjectLeaveEvent);
                        this.state.objectsObservers[key] = null;
                    }
                }
            }
        }
        /**
         * Starts moving when crossing moveThreshold and performs movement
         */
        __doMove(clientX, clientY) {
            if (this.step.is(Step.CLICK) && this.__moveThresholdReached(clientX, clientY)) {
                this.viewer.stopAll();
                this.__resetMove();
                this.step.set(Step.MOVING);
                this.data.mouseX = clientX;
                this.data.mouseY = clientY;
                this.__logMouseMove(clientX, clientY);
            } else if (this.step.is(Step.MOVING)) {
                this.__applyMove(clientX, clientY);
                this.__logMouseMove(clientX, clientY);
            }
        }
        /**
         * Checks if the cursor was move beyond the move threshold
         */
        __moveThresholdReached(clientX, clientY) {
            return Math.abs(clientX - this.data.startMouseX) >= this.moveThreshold || Math.abs(clientY - this.data.startMouseY) >= this.moveThreshold;
        }
        /**
         * Raw method for movement, called from mouse event and move inertia
         */
        __applyMove(clientX, clientY) {
            const rotation = {
                yaw: this.config.moveSpeed * ((clientX - this.data.mouseX) / this.state.size.width) * import_three8.MathUtils.degToRad(this.state.littlePlanet ? 90 : this.state.hFov),
                pitch: this.config.moveSpeed * ((clientY - this.data.mouseY) / this.state.size.height) * import_three8.MathUtils.degToRad(this.state.littlePlanet ? 90 : this.state.vFov)
            };
            const currentPosition = this.viewer.getPosition();
            this.viewer.rotate({
                yaw: currentPosition.yaw - rotation.yaw,
                pitch: currentPosition.pitch + rotation.pitch
            });
            this.data.mouseX = clientX;
            this.data.mouseY = clientY;
        }
        /**
         * Perfoms combined move and zoom
         */
        __doMoveZoom(evt) {
            if (this.step.is(Step.MOVING)) {
                evt.preventDefault();
                const touchData = getTouchData(evt);
                const delta = (touchData.distance - this.data.pinchDist) / SYSTEM.pixelRatio * this.config.zoomSpeed;
                this.viewer.zoom(this.viewer.getZoomLevel() + delta);
                this.__doMove(touchData.center.x, touchData.center.y);
                this.data.pinchDist = touchData.distance;
            }
        }
        /**
         * Stores each mouse position during a mouse move
         * @description Positions older than "INERTIA_WINDOW" are removed<br>
         * Positions before a pause of "INERTIA_WINDOW" / 10 are removed
         */
        __logMouseMove(clientX, clientY) {
            const now = Date.now();
            const last = this.data.mouseHistory.length ? this.data.mouseHistory[this.data.mouseHistory.length - 1] : [0, -1, -1];
            if (last[1] === clientX && last[2] === clientY) {
                last[0] = now;
            } else if (now === last[0]) {
                last[1] = clientX;
                last[2] = clientY;
            } else {
                this.data.mouseHistory.push([now, clientX, clientY]);
            }
            let previous = null;
            for (let i = 0; i < this.data.mouseHistory.length; ) {
                if (this.data.mouseHistory[i][0] < now - INERTIA_WINDOW) {
                    this.data.mouseHistory.splice(i, 1);
                } else if (previous && this.data.mouseHistory[i][0] - previous > INERTIA_WINDOW / 10) {
                    this.data.mouseHistory.splice(0, i);
                    i = 0;
                    previous = this.data.mouseHistory[i][0];
                } else {
                    previous = this.data.mouseHistory[i][0];
                    i++;
                }
            }
        }
    };

    // src/services/Renderer.ts
    var import_three9 = require_three();
    var vector2 = new import_three9.Vector2();
    var Renderer = class extends AbstractService {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer);
            this.renderer = new import_three9.WebGLRenderer(this.config.rendererParameters);
            this.renderer.setPixelRatio(SYSTEM.pixelRatio);
            this.renderer.outputColorSpace = import_three9.LinearSRGBColorSpace;
            this.renderer.domElement.className = "psv-canvas";
            this.scene = new import_three9.Scene();
            this.camera = new import_three9.PerspectiveCamera(50, 16 / 9, 0.1, 2 * SPHERE_RADIUS);
            this.camera.matrixWorldAutoUpdate = false;
            this.mesh = this.viewer.adapter.createMesh();
            this.mesh.userData = { [VIEWER_DATA]: true };
            this.meshContainer = new import_three9.Group();
            this.meshContainer.add(this.mesh);
            this.scene.add(this.meshContainer);
            this.raycaster = new import_three9.Raycaster();
            this.container = document.createElement("div");
            this.container.className = "psv-canvas-container";
            this.container.appendChild(this.renderer.domElement);
            this.viewer.container.appendChild(this.container);
            this.viewer.addEventListener(SizeUpdatedEvent.type, this);
            this.viewer.addEventListener(ZoomUpdatedEvent.type, this);
            this.viewer.addEventListener(PositionUpdatedEvent.type, this);
            this.viewer.addEventListener(ConfigChangedEvent.type, this);
            this.hide();
        }
        get panoramaPose() {
            return this.mesh.rotation;
        }
        get sphereCorrection() {
            return this.meshContainer.rotation;
        }
        /**
         * @internal
         */
        init() {
            this.show();
            this.renderer.setAnimationLoop((t) => this.__renderLoop(t));
        }
        /**
         * @internal
         */
        destroy() {
            this.renderer.setAnimationLoop(null);
            this.cleanScene(this.scene);
            this.viewer.container.removeChild(this.container);
            this.viewer.removeEventListener(SizeUpdatedEvent.type, this);
            this.viewer.removeEventListener(ZoomUpdatedEvent.type, this);
            this.viewer.removeEventListener(PositionUpdatedEvent.type, this);
            this.viewer.removeEventListener(ConfigChangedEvent.type, this);
            super.destroy();
        }
        /**
         * @internal
         */
        handleEvent(e) {
            switch (e.type) {
                case SizeUpdatedEvent.type:
                    this.__onSizeUpdated();
                    break;
                case ZoomUpdatedEvent.type:
                    this.__onZoomUpdated();
                    break;
                case PositionUpdatedEvent.type:
                    this.__onPositionUpdated();
                    break;
                case ConfigChangedEvent.type:
                    if (e.containsOptions("fisheye")) {
                        this.__onPositionUpdated();
                    }
                    if (e.containsOptions("canvasBackground")) {
                        this.container.style.background = this.config.canvasBackground;
                    }
                    break;
            }
        }
        /**
         * Hides the viewer
         */
        hide() {
            this.container.style.opacity = "0";
        }
        /**
         * Shows the viewer
         */
        show() {
            this.container.style.opacity = "1";
        }
        /**
         * Resets or replaces the THREE renderer by a custom one
         */
        setCustomRenderer(factory) {
            if (factory) {
                this.customRenderer = factory(this.renderer);
            } else {
                this.customRenderer = null;
            }
            this.viewer.needsUpdate();
        }
        /**
         * Updates the size of the renderer and the aspect of the camera
         */
        __onSizeUpdated() {
            this.renderer.setSize(this.state.size.width, this.state.size.height);
            this.camera.aspect = this.state.aspect;
            this.camera.updateProjectionMatrix();
            this.viewer.needsUpdate();
        }
        /**
         * Updates the fov of the camera
         */
        __onZoomUpdated() {
            this.camera.fov = this.state.vFov;
            this.camera.updateProjectionMatrix();
            this.viewer.needsUpdate();
        }
        /**
         * Updates the position of the camera
         */
        __onPositionUpdated() {
            this.camera.position.set(0, 0, 0);
            this.camera.lookAt(this.state.direction);
            if (this.config.fisheye) {
                this.camera.position.copy(this.state.direction).multiplyScalar(this.config.fisheye / 2).negate();
            }
            this.camera.updateMatrixWorld();
            this.viewer.needsUpdate();
        }
        /**
         * Main event loop, performs a render if `state.needsUpdate` is true
         */
        __renderLoop(timestamp) {
            const elapsed = !this.timestamp ? 0 : timestamp - this.timestamp;
            this.timestamp = timestamp;
            this.viewer.dispatchEvent(new BeforeRenderEvent(timestamp, elapsed));
            this.viewer.dynamics.update(elapsed);
            if (this.state.needsUpdate || this.state.continuousUpdateCount > 0) {
                (this.customRenderer || this.renderer).render(this.scene, this.camera);
                this.viewer.dispatchEvent(new RenderEvent());
                this.state.needsUpdate = false;
            }
        }
        /**
         * Applies the texture to the scene, creates the scene if needed
         * @internal
         */
        setTexture(textureData) {
            if (this.viewer.adapter.constructor.supportsOverlay) {
                this.setOverlay(null, 0);
            }
            if (this.state.textureData) {
                this.viewer.adapter.disposeTexture(this.state.textureData);
            }
            this.state.textureData = textureData;
            this.state.panoData = textureData.panoData;
            this.viewer.adapter.setTexture(this.mesh, textureData);
            this.viewer.needsUpdate();
        }
        /**
         * Applies the overlay to the mesh
         * @internal
         */
        setOverlay(textureData, opacity) {
            if (this.state.overlayData) {
                this.viewer.adapter.disposeTexture(this.state.overlayData);
            }
            this.state.overlayData = textureData;
            this.viewer.adapter.setOverlay(this.mesh, textureData, opacity);
            this.viewer.needsUpdate();
        }
        /**
         * Applies a panorama data pose to a Mesh
         * @internal
         */
        setPanoramaPose(panoData, mesh = this.mesh) {
            const cleanCorrection = this.viewer.dataHelper.cleanPanoramaPose(panoData);
            mesh.rotation.set(-cleanCorrection.tilt, -cleanCorrection.pan, -cleanCorrection.roll, "ZXY");
        }
        /**
         * Applies a SphereCorrection to a Group
         * @internal
         */
        setSphereCorrection(sphereCorrection, group = this.meshContainer) {
            const cleanCorrection = this.viewer.dataHelper.cleanSphereCorrection(sphereCorrection);
            group.rotation.set(cleanCorrection.tilt, cleanCorrection.pan, cleanCorrection.roll, "ZXY");
        }
        /**
         * Performs transition between the current and a new texture
         * @internal
         */
        transition(textureData, options) {
            const positionProvided = !isNil(options.position);
            const zoomProvided = !isNil(options.zoom);
            const e = new BeforeAnimateEvent(
                positionProvided ? this.viewer.dataHelper.cleanPosition(options.position) : void 0,
                options.zoom
            );
            this.viewer.dispatchEvent(e);
            const group = new import_three9.Group();
            const mesh = this.viewer.adapter.createMesh(0.5);
            this.viewer.adapter.setTexture(mesh, textureData, true);
            this.viewer.adapter.setTextureOpacity(mesh, 0);
            this.setPanoramaPose(textureData.panoData, mesh);
            this.setSphereCorrection(options.sphereCorrection, group);
            if (positionProvided && options.transition === "fade-only") {
                const currentPosition = this.viewer.getPosition();
                const verticalAxis = new import_three9.Vector3(0, 1, 0);
                group.rotateOnWorldAxis(verticalAxis, e.position.yaw - currentPosition.yaw);
                const horizontalAxis = new import_three9.Vector3(0, 1, 0).cross(this.camera.getWorldDirection(new import_three9.Vector3())).normalize();
                group.rotateOnWorldAxis(horizontalAxis, e.position.pitch - currentPosition.pitch);
            }
            group.add(mesh);
            this.scene.add(group);
            this.renderer.setRenderTarget(new import_three9.WebGLRenderTarget());
            this.renderer.render(this.scene, this.camera);
            this.renderer.setRenderTarget(null);
            const { duration, properties } = this.viewer.dataHelper.getAnimationProperties(
                options.transition,
                options.transition === true ? e.position : null,
                e.zoomLevel
            );
            const animation = new Animation({
                properties: {
                    ...properties,
                    opacity: { start: 0, end: 1 }
                },
                duration,
                easing: "inOutCubic",
                onTick: (props) => {
                    this.viewer.adapter.setTextureOpacity(mesh, props.opacity);
                    if (positionProvided && options.transition === true) {
                        this.viewer.dynamics.position.setValue({
                            yaw: props.yaw,
                            pitch: props.pitch
                        });
                    }
                    if (zoomProvided) {
                        this.viewer.dynamics.zoom.setValue(props.zoom);
                    }
                    this.viewer.needsUpdate();
                }
            });
            animation.then((completed) => {
                if (completed) {
                    this.setTexture(textureData);
                    this.viewer.adapter.setTextureOpacity(this.mesh, 1);
                    this.setPanoramaPose(textureData.panoData);
                    this.setSphereCorrection(options.sphereCorrection);
                    if (positionProvided && options.transition === "fade-only") {
                        this.viewer.rotate(options.position);
                    }
                } else {
                    this.viewer.adapter.disposeTexture(textureData);
                }
                this.scene.remove(group);
                mesh.geometry.dispose();
                mesh.geometry = null;
            });
            return animation;
        }
        /**
         * Returns intersections with objects in the scene
         */
        getIntersections(viewerPoint) {
            vector2.x = 2 * viewerPoint.x / this.state.size.width - 1;
            vector2.y = -2 * viewerPoint.y / this.state.size.height + 1;
            this.raycaster.setFromCamera(vector2, this.camera);
            const intersections = this.raycaster.intersectObjects(this.scene.children, true).filter((i) => i.object.isMesh && !!i.object.userData);
            if (this.customRenderer?.getIntersections) {
                intersections.push(...this.customRenderer.getIntersections(this.raycaster, vector2));
            }
            return intersections;
        }
        /**
         * Adds an object to the THREE scene
         */
        addObject(object) {
            this.scene.add(object);
        }
        /**
         * Removes an object from the THREE scene
         */
        removeObject(object) {
            this.scene.remove(object);
        }
        /**
         * Calls `dispose` on all objects and textures
         * @internal
         */
        cleanScene(object) {
            object.traverse((item) => {
                if (item.geometry) {
                    item.geometry.dispose();
                }
                if (item.material) {
                    if (Array.isArray(item.material)) {
                        item.material.forEach((material) => {
                            if (material.map) {
                                material.map.dispose();
                            }
                            material.dispose();
                        });
                    } else {
                        if (item.material.map) {
                            item.material.map.dispose();
                        }
                        item.material.dispose();
                    }
                }
                if (item.dispose && !(item instanceof import_three9.Scene)) {
                    item.dispose();
                }
                if (item !== object) {
                    this.cleanScene(item);
                }
            });
        }
    };

    // src/services/TextureLoader.ts
    var import_three10 = require_three();
    var TextureLoader = class extends AbstractService {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer);
            this.fileLoader = new import_three10.FileLoader();
            this.fileLoader.setResponseType("blob");
            this.imageLoader = new import_three10.ImageLoader();
            if (this.config.withCredentials) {
                this.fileLoader.setWithCredentials(true);
                this.imageLoader.setCrossOrigin("use-credentials");
            }
        }
        /**
         * @internal
         */
        destroy() {
            this.abortLoading();
            super.destroy();
        }
        /**
         * Cancels current HTTP requests
         * @internal
         */
        abortLoading() {
        }
        /**
         * Loads a Blob with FileLoader
         */
        loadFile(url, onProgress, cacheKey) {
            const cached = Cache.get(url, cacheKey);
            if (cached) {
                if (cached instanceof Blob) {
                    onProgress?.(100);
                    return Promise.resolve(cached);
                } else {
                    Cache.remove(url, cacheKey);
                }
            }
            if (this.config.requestHeaders) {
                this.fileLoader.setRequestHeader(this.config.requestHeaders(url));
            }
            return new Promise((resolve, reject) => {
                let progress = 0;
                onProgress?.(progress);
                this.fileLoader.load(
                    url,
                    (result) => {
                        progress = 100;
                        onProgress?.(progress);
                        Cache.add(url, cacheKey, result);
                        resolve(result);
                    },
                    (e) => {
                        if (e.lengthComputable) {
                            const newProgress = e.loaded / e.total * 100;
                            if (newProgress > progress) {
                                progress = newProgress;
                                onProgress?.(progress);
                            }
                        }
                    },
                    (err) => {
                        reject(err);
                    }
                );
            });
        }
        /**
         * Loads an image with ImageLoader or with FileLoader if progress is tracked or if request headers are configured
         */
        loadImage(url, onProgress, cacheKey) {
            const cached = Cache.get(url, cacheKey);
            if (cached) {
                onProgress?.(100);
                if (cached instanceof Blob) {
                    return this.blobToImage(cached);
                } else {
                    return Promise.resolve(cached);
                }
            }
            if (!onProgress && !this.config.requestHeaders) {
                return this.imageLoader.loadAsync(url).then((result) => {
                    Cache.add(url, cacheKey, result);
                    return result;
                });
            } else {
                return this.loadFile(url, onProgress, cacheKey).then((blob) => this.blobToImage(blob));
            }
        }
        /**
         * Converts a file loaded with {@link loadFile} into an image
         */
        blobToImage(blob) {
            return new Promise((resolve, reject) => {
                const img = document.createElement("img");
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                    resolve(img);
                };
                img.onerror = reject;
                img.src = URL.createObjectURL(blob);
            });
        }
        /**
         * Preload a panorama file without displaying it
         */
        preloadPanorama(panorama) {
            if (this.viewer.adapter.supportsPreload(panorama)) {
                return this.viewer.adapter.loadTexture(panorama);
            } else {
                return Promise.reject(new PSVError("Current adapter does not support preload"));
            }
        }
    };

    // src/services/ViewerDynamics.ts
    var import_three11 = require_three();
    var ViewerDynamics = class extends AbstractService {
        /**
         * @internal
         */
        constructor(viewer) {
            super(viewer);
            this.zoom = new Dynamic(
                (zoomLevel) => {
                    this.viewer.state.vFov = this.viewer.dataHelper.zoomLevelToFov(zoomLevel);
                    this.viewer.state.hFov = this.viewer.dataHelper.vFovToHFov(this.viewer.state.vFov);
                    this.viewer.dispatchEvent(new ZoomUpdatedEvent(zoomLevel));
                },
                {
                    defaultValue: this.viewer.config.defaultZoomLvl,
                    min: 0,
                    max: 100,
                    wrap: false
                }
            );
            this.position = new MultiDynamic(
                (position) => {
                    this.viewer.dataHelper.sphericalCoordsToVector3(position, this.viewer.state.direction);
                    this.viewer.dispatchEvent(new PositionUpdatedEvent(position));
                },
                {
                    yaw: new Dynamic(null, {
                        defaultValue: this.config.defaultYaw,
                        min: 0,
                        max: 2 * Math.PI,
                        wrap: true
                    }),
                    pitch: new Dynamic(null, {
                        defaultValue: this.config.defaultPitch,
                        min: !this.viewer.state.littlePlanet ? -Math.PI / 2 : 0,
                        max: !this.viewer.state.littlePlanet ? Math.PI / 2 : Math.PI * 2,
                        wrap: this.viewer.state.littlePlanet
                    })
                }
            );
            this.updateSpeeds();
        }
        /**
         * @internal
         */
        updateSpeeds() {
            this.zoom.setSpeed(this.config.zoomSpeed * 50);
            this.position.setSpeed(import_three11.MathUtils.degToRad(this.config.moveSpeed * 50));
        }
        /**
         * @internal
         */
        update(elapsed) {
            this.zoom.update(elapsed);
            this.position.update(elapsed);
        }
    };

    // src/services/ViewerState.ts
    var import_three12 = require_three();
    var ViewerState = class {
        /**
         * @internal
         */
        // eslint-disable-next-line  @typescript-eslint/no-empty-function
        constructor() {
            /**
             * when all components are loaded
             */
            this.ready = false;
            /**
             * if the view needs to be renderer
             */
            this.needsUpdate = false;
            /**
             * number of plugins requesting to continuously render the scene
             */
            this.continuousUpdateCount = 0;
            /**
             * if the keyboard events are currently listened to
             */
            this.keyboardEnabled = false;
            /**
             * direction of the camera
             */
            this.direction = new import_three12.Vector3(0, 0, SPHERE_RADIUS);
            /**
             * vertical FOV
             */
            this.vFov = 60;
            /**
             * horizontal FOV
             */
            this.hFov = 60;
            /**
             * renderer aspect ratio
             */
            this.aspect = 1;
            /**
             * currently running animation
             */
            this.animation = null;
            /**
             * currently running transition
             */
            this.transitionAnimation = null;
            /**
             * promise of the last "setPanorama()" call
             */
            this.loadingPromise = null;
            /**
             * special tweaks for LittlePlanetAdapter
             */
            this.littlePlanet = false;
            /**
             * time of the last user action
             */
            this.idleTime = -1;
            /**
             * registered THREE objects observer
             */
            this.objectsObservers = {};
            /**
             * size of the container
             */
            this.size = {
                width: 0,
                height: 0
            };
            /**
             * panorama metadata, if supported
             */
            this.panoData = {
                fullWidth: 0,
                fullHeight: 0,
                croppedWidth: 0,
                croppedHeight: 0,
                croppedX: 0,
                croppedY: 0,
                poseHeading: 0,
                posePitch: 0,
                poseRoll: 0
            };
        }
    };

    // src/Viewer.ts
    var Viewer = class extends TypedEventTarget {
        constructor(config) {
            super();
            /** @internal */
            this.plugins = {};
            /** @internal */
            this.children = [];
            this.onResize = throttle(() => this.navbar.autoSize(), 500);
            Cache.init();
            SYSTEM.load();
            this.state = new ViewerState();
            this.config = getViewerConfig(config);
            this.parent = getElement(config.container);
            this.parent[VIEWER_DATA] = this;
            this.container = document.createElement("div");
            this.container.classList.add("psv-container");
            this.parent.appendChild(this.container);
            checkStylesheet(this.container, "core");
            this.adapter = new this.config.adapter[0](this, this.config.adapter[1]);
            this.renderer = new Renderer(this);
            this.textureLoader = new TextureLoader(this);
            this.eventsHandler = new EventsHandler(this);
            this.dataHelper = new DataHelper(this);
            this.dynamics = new ViewerDynamics(this);
            this.adapter.init?.();
            this.loader = new Loader(this);
            this.navbar = new Navbar(this);
            this.panel = new Panel(this);
            this.notification = new Notification(this);
            this.overlay = new Overlay(this);
            this.resize(this.config.size);
            this.setCursor(null);
            resolveBoolean(SYSTEM.isTouchEnabled, (enabled) => {
                toggleClass(this.container, "psv--is-touch", enabled);
            });
            this.config.plugins.forEach(([plugin, opts]) => {
                this.plugins[plugin.id] = new plugin(this, opts);
            });
            for (const plugin of Object.values(this.plugins)) {
                plugin.init?.();
            }
            if (this.config.navbar) {
                this.navbar.setButtons(this.config.navbar);
            }
            if (!this.state.loadingPromise) {
                if (this.config.panorama) {
                    this.setPanorama(this.config.panorama);
                } else {
                    this.loader.show();
                }
            }
        }
        /**
         * Destroys the viewer
         */
        destroy() {
            this.stopAll();
            this.stopKeyboardControl();
            this.exitFullscreen();
            for (const [id, plugin] of Object.entries(this.plugins)) {
                plugin.destroy();
                delete this.plugins[id];
            }
            this.children.slice().forEach((child) => child.destroy());
            this.children.length = 0;
            this.eventsHandler.destroy();
            this.renderer.destroy();
            this.textureLoader.destroy();
            this.dataHelper.destroy();
            this.adapter.destroy();
            this.dynamics.destroy();
            this.parent.removeChild(this.container);
            delete this.parent[VIEWER_DATA];
        }
        init() {
            this.eventsHandler.init();
            this.renderer.init();
            if (this.config.navbar) {
                this.navbar.show();
            }
            if (this.config.keyboard === "always") {
                this.startKeyboardControl();
            }
            this.resetIdleTimer();
            this.state.ready = true;
            this.dispatchEvent(new ReadyEvent());
        }
        /**
         * Restarts the idle timer
         * @internal
         */
        resetIdleTimer() {
            this.state.idleTime = performance.now();
        }
        /**
         * Stops the idle timer
         * @internal
         */
        disableIdleTimer() {
            this.state.idleTime = -1;
        }
        /**
         * Returns the instance of a plugin if it exists
         * @example By plugin identifier
         * ```js
         * viewer.getPlugin('markers')
         * ```
         * @example By plugin class with TypeScript support
         * ```ts
         * viewer.getPlugin<MarkersPlugin>(MarkersPlugin)
         * ```
         */
        getPlugin(pluginId) {
            if (typeof pluginId === "string") {
                return this.plugins[pluginId];
            } else {
                const pluginCtor = pluginInterop(pluginId);
                return pluginCtor ? this.plugins[pluginCtor.id] : null;
            }
        }
        /**
         * Returns the current position of the camera
         */
        getPosition() {
            return this.dataHelper.cleanPosition(this.dynamics.position.current);
        }
        /**
         * Returns the current zoom level
         */
        getZoomLevel() {
            return this.dynamics.zoom.current;
        }
        /**
         * Returns the current viewer size
         */
        getSize() {
            return { ...this.state.size };
        }
        /**
         * Checks if the viewer is in fullscreen
         */
        isFullscreenEnabled() {
            return isFullscreenEnabled(this.parent);
        }
        /**
         * Request a new render of the scene
         */
        needsUpdate() {
            this.state.needsUpdate = true;
        }
        /**
         * Request the scene to be continuously renderer (when using videos)
         */
        needsContinuousUpdate(enabled) {
            if (enabled) {
                this.state.continuousUpdateCount++;
            } else if (this.state.continuousUpdateCount > 0) {
                this.state.continuousUpdateCount--;
            }
        }
        /**
         * Resizes the scene if the viewer is resized
         */
        autoSize() {
            if (this.container.clientWidth !== this.state.size.width || this.container.clientHeight !== this.state.size.height) {
                this.state.size.width = Math.round(this.container.clientWidth);
                this.state.size.height = Math.round(this.container.clientHeight);
                this.state.aspect = this.state.size.width / this.state.size.height;
                this.state.hFov = this.dataHelper.vFovToHFov(this.state.vFov);
                this.dispatchEvent(new SizeUpdatedEvent(this.getSize()));
                this.onResize();
            }
        }
        /**
         * Loads a new panorama file
         * @description Loads a new panorama file, optionally changing the camera position/zoom and activating the transition animation.<br>
         * If the "options" parameter is not defined, the camera will not move and the ongoing animation will continue.<br>
         * If another loading is already in progress it will be aborted.
         * @returns promise resolved with false if the loading was aborted by another call
         */
        setPanorama(path, options = {}) {
            this.textureLoader.abortLoading();
            this.state.transitionAnimation?.cancel();
            if (!this.state.ready) {
                ["sphereCorrection", "panoData", "overlay", "overlayOpacity"].forEach((opt) => {
                    if (!(opt in options)) {
                        options[opt] = this.config[opt];
                    }
                });
            }
            if (isExtendedPosition(options)) {
                logWarn(`PanoramaOptions.yaw and PanoramaOptions.pitch are deprecated, use PanoramaOptions.position instead`);
                options.position = this.dataHelper.cleanPosition(options);
            }
            if (typeof options.transition === "number") {
                logWarn(`Use PanoramaOptions.speed to define the speed/duration of the transition`);
                options.speed = options.transition;
                options.transition = true;
            }
            if (options.transition === void 0) {
                options.transition = true;
            }
            if (options.speed === void 0) {
                options.speed = DEFAULT_TRANSITION;
            }
            if (options.showLoader === void 0) {
                options.showLoader = true;
            }
            if (options.caption === void 0) {
                options.caption = this.config.caption;
            }
            if (options.description === void 0) {
                options.description = this.config.description;
            }
            if (!options.panoData && typeof this.config.panoData === "function") {
                options.panoData = this.config.panoData;
            }
            const positionProvided = !isNil(options.position);
            const zoomProvided = !isNil(options.zoom);
            if (positionProvided || zoomProvided) {
                this.stopAll();
            }
            this.hideError();
            this.resetIdleTimer();
            this.config.panorama = path;
            this.config.caption = options.caption;
            this.config.description = options.description;
            const done = (err) => {
                this.loader.hide();
                this.state.loadingPromise = null;
                if (isAbortError(err)) {
                    return false;
                } else if (err) {
                    this.navbar.setCaption("");
                    this.showError(this.config.lang.loadError);
                    console.error(err);
                    this.dispatchEvent(new PanoramaErrorEvent(path, err));
                    throw err;
                } else {
                    this.setOverlay(options.overlay, options.overlayOpacity);
                    this.navbar.setCaption(this.config.caption);
                    return true;
                }
            };
            this.navbar.setCaption(`<em>${this.config.loadingTxt || ""}</em>`);
            if (options.showLoader || !this.state.ready) {
                this.loader.show();
            }
            const loadingPromise = this.adapter.loadTexture(this.config.panorama, options.panoData).then((textureData) => {
                if (textureData.panorama !== this.config.panorama) {
                    this.adapter.disposeTexture(textureData);
                    throw getAbortError();
                }
                return textureData;
            });
            if (!options.transition || !this.state.ready || !this.adapter.supportsTransition(this.config.panorama)) {
                this.state.loadingPromise = loadingPromise.then((textureData) => {
                    this.renderer.show();
                    this.renderer.setTexture(textureData);
                    this.renderer.setPanoramaPose(textureData.panoData);
                    this.renderer.setSphereCorrection(options.sphereCorrection);
                    if (!this.state.ready) {
                        this.init();
                    }
                    this.dispatchEvent(new PanoramaLoadedEvent(textureData));
                    if (zoomProvided) {
                        this.zoom(options.zoom);
                    }
                    if (positionProvided) {
                        this.rotate(options.position);
                    }
                }).then(
                    () => done(),
                    (err) => done(err)
                );
            } else {
                this.state.loadingPromise = loadingPromise.then((textureData) => {
                    this.loader.hide();
                    this.dispatchEvent(new PanoramaLoadedEvent(textureData));
                    this.state.transitionAnimation = this.renderer.transition(textureData, options);
                    return this.state.transitionAnimation;
                }).then((completed) => {
                    this.state.transitionAnimation = null;
                    if (!completed) {
                        throw getAbortError();
                    }
                }).then(
                    () => done(),
                    (err) => done(err)
                );
            }
            return this.state.loadingPromise;
        }
        /**
         * Loads a new overlay
         */
        setOverlay(path, opacity = this.config.overlayOpacity) {
            const supportsOverlay = this.adapter.constructor.supportsOverlay;
            if (!path) {
                if (supportsOverlay) {
                    this.renderer.setOverlay(null, 0);
                }
                return Promise.resolve();
            } else {
                if (!supportsOverlay) {
                    return Promise.reject(new PSVError(`Current adapter does not supports overlay`));
                }
                return this.adapter.loadTexture(
                    path,
                    (image) => {
                        const p = this.state.panoData;
                        const r = image.width / p.croppedWidth;
                        return {
                            fullWidth: r * p.fullWidth,
                            fullHeight: r * p.fullHeight,
                            croppedWidth: r * p.croppedWidth,
                            croppedHeight: r * p.croppedHeight,
                            croppedX: r * p.croppedX,
                            croppedY: r * p.croppedY
                        };
                    },
                    false
                ).then((textureData) => {
                    this.renderer.setOverlay(textureData, opacity);
                });
            }
        }
        /**
         * Update options
         * @throws {@link PSVError} if the configuration is invalid
         */
        setOptions(options) {
            const rawConfig = {
                ...this.config,
                ...options
            };
            for (let [key, value] of Object.entries(options)) {
                if (!(key in DEFAULTS)) {
                    logWarn(`Unknown option ${key}`);
                    continue;
                }
                if (key in READONLY_OPTIONS) {
                    logWarn(READONLY_OPTIONS[key]);
                    continue;
                }
                if (key in CONFIG_PARSERS) {
                    value = CONFIG_PARSERS[key](value, {
                        rawConfig,
                        defValue: DEFAULTS[key]
                    });
                }
                this.config[key] = value;
                switch (key) {
                    case "mousemove":
                        if (!this.state.cursorOverride) {
                            this.setCursor(null);
                        }
                        break;
                    case "caption":
                        this.navbar.setCaption(this.config.caption);
                        break;
                    case "size":
                        this.resize(this.config.size);
                        break;
                    case "sphereCorrection":
                        this.renderer.setSphereCorrection(this.config.sphereCorrection);
                        break;
                    case "navbar":
                    case "lang":
                        this.navbar.setButtons(this.config.navbar);
                        break;
                    case "moveSpeed":
                    case "zoomSpeed":
                        this.dynamics.updateSpeeds();
                        break;
                    case "minFov":
                    case "maxFov":
                        this.dynamics.zoom.setValue(this.dataHelper.fovToZoomLevel(this.state.vFov));
                        this.dispatchEvent(new ZoomUpdatedEvent(this.getZoomLevel()));
                        break;
                    case "keyboard":
                        if (this.config.keyboard === "always") {
                            this.startKeyboardControl();
                        } else {
                            this.stopKeyboardControl();
                        }
                        break;
                    default:
                        break;
                }
            }
            this.needsUpdate();
            this.dispatchEvent(new ConfigChangedEvent(Object.keys(options)));
        }
        /**
         * Update options
         * @throws {@link PSVError} if the configuration is invalid
         */
        setOption(option, value) {
            this.setOptions({ [option]: value });
        }
        /**
         * Displays an error message over the viewer
         */
        showError(message) {
            this.overlay.show({
                id: IDS.ERROR,
                image: error_default,
                title: message,
                dissmisable: false
            });
        }
        /**
         *  Hides the error message
         */
        hideError() {
            this.overlay.hide(IDS.ERROR);
        }
        /**
         * Rotates the view to specific position
         */
        rotate(position) {
            const e = new BeforeRotateEvent(this.dataHelper.cleanPosition(position));
            this.dispatchEvent(e);
            if (e.defaultPrevented) {
                return;
            }
            this.dynamics.position.setValue(e.position);
        }
        /**
         * Zooms to a specific level between `maxFov` and `minFov`
         */
        zoom(level) {
            this.dynamics.zoom.setValue(level);
        }
        /**
         * Increases the zoom level
         */
        zoomIn(step = 1) {
            this.dynamics.zoom.step(step);
        }
        /**
         * Decreases the zoom level
         */
        zoomOut(step = 1) {
            this.dynamics.zoom.step(-step);
        }
        /**
         * Rotates and zooms the view with a smooth animation
         */
        animate(options) {
            const positionProvided = isExtendedPosition(options);
            const zoomProvided = !isNil(options.zoom);
            const e = new BeforeAnimateEvent(
                positionProvided ? this.dataHelper.cleanPosition(options) : void 0,
                options.zoom
            );
            this.dispatchEvent(e);
            if (e.defaultPrevented) {
                return;
            }
            this.stopAll();
            const { duration, properties } = this.dataHelper.getAnimationProperties(options.speed, e.position, e.zoomLevel);
            if (!duration) {
                if (positionProvided) {
                    this.rotate(e.position);
                }
                if (zoomProvided) {
                    this.zoom(e.zoomLevel);
                }
                return new Animation(null);
            }
            this.state.animation = new Animation({
                properties,
                duration,
                easing: "inOutSine",
                onTick: (props) => {
                    if (positionProvided) {
                        this.dynamics.position.setValue({
                            yaw: props.yaw,
                            pitch: props.pitch
                        });
                    }
                    if (zoomProvided) {
                        this.dynamics.zoom.setValue(props.zoom);
                    }
                }
            });
            this.state.animation.then(() => {
                this.state.animation = null;
                this.resetIdleTimer();
            });
            return this.state.animation;
        }
        /**
         * Stops the ongoing animation
         * @description The return value is a Promise because the is no guaranty the animation can be stopped synchronously.
         */
        stopAnimation() {
            if (this.state.animation) {
                this.state.animation.cancel();
                return this.state.animation;
            } else {
                return Promise.resolve();
            }
        }
        /**
         * Resizes the viewer
         */
        resize(size) {
            const s = size;
            ["width", "height"].forEach((dim) => {
                if (size && s[dim]) {
                    if (/^[0-9.]+$/.test(s[dim])) {
                        s[dim] += "px";
                    }
                    this.parent.style[dim] = s[dim];
                }
            });
            this.autoSize();
        }
        /**
         * Enters the fullscreen mode
         */
        enterFullscreen() {
            if (!this.isFullscreenEnabled()) {
                requestFullscreen(this.parent);
            }
        }
        /**
         * Exits the fullscreen mode
         */
        exitFullscreen() {
            if (this.isFullscreenEnabled()) {
                exitFullscreen();
            }
        }
        /**
         * Enters or exits the fullscreen mode
         */
        toggleFullscreen() {
            if (!this.isFullscreenEnabled()) {
                this.enterFullscreen();
            } else {
                this.exitFullscreen();
            }
        }
        /**
         * Enables the keyboard controls
         */
        startKeyboardControl() {
            this.state.keyboardEnabled = true;
        }
        /**
         * Disables the keyboard controls
         */
        stopKeyboardControl() {
            this.state.keyboardEnabled = false;
        }
        /**
         * Creates a new tooltip
         * @description Use {@link Tooltip.move} to update the tooltip without re-create
         * @throws {@link PSVError} if the configuration is invalid
         */
        createTooltip(config) {
            return new Tooltip(this, config);
        }
        /**
         * Changes the global mouse cursor
         */
        setCursor(cursor) {
            this.state.cursorOverride = cursor;
            if (!cursor) {
                this.container.style.cursor = this.config.mousemove ? "move" : "default";
            } else {
                this.container.style.cursor = cursor;
            }
        }
        /**
         * Subscribes to events on objects in the three.js scene
         * @param userDataKey - only objects with the following `userData` will be observed
         */
        observeObjects(userDataKey) {
            if (!this.state.objectsObservers[userDataKey]) {
                this.state.objectsObservers[userDataKey] = null;
            }
        }
        /**
         * Unsubscribes to events on objects
         */
        unobserveObjects(userDataKey) {
            delete this.state.objectsObservers[userDataKey];
        }
        /**
         * Stops all current animations
         * @internal
         */
        stopAll() {
            this.dispatchEvent(new StopAllEvent());
            this.disableIdleTimer();
            return this.stopAnimation();
        }
    };

    // src/index.ts
    import_three13.Cache.enabled = false;
    import_three13.ColorManagement.enabled = false;
    __copyProps(__defProp(exports, "__esModule", { value: true }), src_exports);

}));//# sourceMappingURL=index.js.map