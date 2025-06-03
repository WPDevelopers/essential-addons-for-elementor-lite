/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/svg-draw.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/svg-draw.js":
/*!*********************************!*\
  !*** ./src/js/view/svg-draw.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var SVGDraw = function SVGDraw($scope, $) {\n  var wrapper = $('.eael-svg-draw-container', $scope),\n    widget_id = $scope.data('id'),\n    svg_icon = $('svg', wrapper),\n    settings = wrapper.data('settings'),\n    transition = Number(settings.transition),\n    loop_delay = Number(settings.loop_delay),\n    offset = 0,\n    lines = $('path, circle, rect, polygon', svg_icon);\n  if ('always' === settings.fill_type || 'before' === settings.fill_type) {\n    gsap.to(lines, {\n      fill: settings.fill_color,\n      duration: transition\n    });\n  }\n  function drawSVGLine() {\n    $.each(lines, function (index, line) {\n      var length = line.getTotalLength() * (settings.stroke_length * .01);\n      line.style.strokeDasharray = length;\n      line.style.strokeDashoffset = length;\n    });\n    var loopConfig = {};\n    if ('yes' === settings.loop) {\n      loopConfig = {\n        repeat: -1,\n        yoyo: \"reverse\" === settings.direction,\n        repeatDelay: loop_delay\n      };\n    }\n    var timeline = gsap.timeline(loopConfig);\n    timeline.to(lines, {\n      strokeDashoffset: offset,\n      duration: settings.speed,\n      ease: settings.ease_type,\n      onComplete: function onComplete() {\n        if ('' !== settings.fill_color) {\n          if ('after' === settings.fill_type) {\n            gsap.to(lines, {\n              fill: settings.fill_color,\n              duration: transition\n            });\n            if ('reverse' === settings.direction) {\n              setTimeout(function () {\n                gsap.to(lines, {\n                  fill: settings.fill_color + '00',\n                  duration: transition\n                });\n              }, loop_delay * 1000);\n            }\n          } else if ('before' === settings.fill_type) {\n            gsap.to(lines, {\n              fill: settings.fill_color + '00',\n              duration: transition\n            });\n          }\n        }\n      },\n      onStart: function onStart() {\n        if ('' !== settings.fill_color) {\n          if ('after' === settings.fill_type && \"restart\" === settings.direction) {\n            gsap.to(lines, {\n              fill: settings.fill_color + '00',\n              duration: transition\n            });\n          } else if ('before' === settings.fill_type) {\n            gsap.to(lines, {\n              fill: settings.fill_color,\n              duration: transition\n            });\n          }\n        }\n      }\n    });\n    if ('yes' === settings.pause) {\n      svg_icon.hover(function () {\n        timeline.pause();\n      }, function () {\n        timeline.play();\n      });\n    }\n  }\n  if (wrapper.hasClass('page-load')) {\n    drawSVGLine(lines, settings);\n  } else if (wrapper.hasClass('mouse-hover')) {\n    svg_icon.hover(function () {\n      if (!wrapper.hasClass('draw-initialized')) {\n        drawSVGLine(lines, settings);\n        wrapper.addClass('draw-initialized');\n      }\n    });\n  } else if (wrapper.hasClass('page-scroll')) {\n    gsap.registerPlugin(ScrollTrigger);\n    $.each(lines, function (index, line) {\n      var length = line.getTotalLength() * (settings.stroke_length * .01);\n      line.style.strokeDasharray = length;\n      line.style.strokeDashoffset = length;\n    });\n    var showMarkers = settings.marker && elementorFrontend.isEditMode();\n    $('.marker-' + widget_id).remove();\n    var timeline = gsap.timeline({\n      scrollTrigger: {\n        trigger: lines,\n        start: \"top \" + settings.start_point,\n        end: \"top \" + settings.end_point,\n        scrub: true,\n        id: widget_id,\n        markers: showMarkers,\n        onUpdate: function onUpdate(self) {\n          if ('' !== settings.fill_color && ('before' === settings.fill_type || 'after' === settings.fill_type)) {\n            var fill1 = settings.fill_color,\n              fill2 = settings.fill_color + '00';\n            if ('after' === settings.fill_type) {\n              fill1 = settings.fill_color + '00';\n              fill2 = settings.fill_color;\n            }\n            if (self.progress < 0.95) {\n              gsap.to(lines, {\n                fill: fill1,\n                duration: transition\n              });\n            } else if (self.progress > 0.95) {\n              gsap.to(lines, {\n                fill: fill2,\n                duration: transition\n              });\n            }\n          }\n        }\n      }\n    });\n    timeline.to(lines, {\n      strokeDashoffset: 0\n    });\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelDrawSVG')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-svg-draw.default\", SVGDraw);\n});\n\n//# sourceURL=webpack:///./src/js/view/svg-draw.js?");

/***/ })

/******/ });