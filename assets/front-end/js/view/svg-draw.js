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

eval("var SVGDraw = function SVGDraw($scope, $) {\n  var wrapper = $('.eael-svg-draw-container', $scope),\n    svg_icon = $('svg', wrapper),\n    settings = wrapper.data('settings'),\n    speed = settings.speed,\n    is_repeat = settings.loop,\n    pauseOnHover = settings.pause,\n    direction = settings.direction,\n    offset = settings.offset,\n    draw_interval,\n    addOrSubtract,\n    stepCount = 0,\n    $doc = $(document),\n    $win = $(window),\n    lines = $('path, circle, rect, polygon', svg_icon),\n    max = $doc.height() - $win.height();\n  if (settings.excludeStyle === 'yes') {\n    lines.attr('style', '');\n  }\n  function dashArrayReset() {\n    var largestDashArray = 0,\n      largestPath = '';\n    $('path', svg_icon).each(function () {\n      var dashArray = $(this).css('stroke-dasharray');\n      var dashArrayValue = parseInt(dashArray);\n      if (dashArrayValue > largestDashArray) {\n        largestDashArray = dashArrayValue;\n        largestPath = $(this);\n      }\n    });\n    if (largestDashArray < 3999 && largestDashArray / 2 > 600 && settings.fill === 'fill-svg') {\n      var _offset = largestPath.css('stroke-dashoffset');\n      _offset = parseInt(_offset);\n      if (_offset < largestDashArray / 2) {\n        wrapper.addClass(settings.fill);\n      }\n    }\n  }\n  function stepManager() {\n    dashArrayReset();\n    if (addOrSubtract) {\n      stepCount += 0.01;\n      if (stepCount >= 1) {\n        addOrSubtract = false;\n        if (settings.fill === 'fill-svg') {\n          wrapper.removeClass('fillout-svg').addClass(settings.fill);\n        }\n      }\n    } else if (direction === 'restart') {\n      stepCount = 0;\n      addOrSubtract = true;\n    } else {\n      stepCount -= 0.01;\n      if (stepCount <= 0) {\n        addOrSubtract = true;\n      }\n    }\n    return stepCount;\n  }\n  if (svg_icon.parent().hasClass('page-scroll')) {\n    $win.on('scroll', function () {\n      var step = ($win.scrollTop() - offset) / max;\n      var offsetTop = svg_icon.offset().top,\n        viewPort = $win.innerHeight(),\n        offsetBottom = offsetTop - viewPort;\n      if (offsetTop > $win.scrollTop() && offsetBottom < $win.scrollTop()) {\n        step = ($win.scrollTop() - offset - offsetBottom) / viewPort;\n        svg_icon.drawsvg('progress', step);\n      }\n      dashArrayReset();\n    });\n  } else if (svg_icon.parent().hasClass('page-load')) {\n    var lastSvg = '';\n    var drawSvg = setInterval(function () {\n      var currentSvg = svg_icon.html();\n      svg_icon.drawsvg('progress', stepManager());\n      if (currentSvg === lastSvg && is_repeat === 'no') {\n        wrapper.addClass(settings.fill);\n        clearInterval(drawSvg);\n      }\n      lastSvg = currentSvg;\n    }, speed);\n  } else if (svg_icon.parent().hasClass('hover')) {\n    var _lastSvg = '';\n    svg_icon.hover(function () {\n      if (pauseOnHover === 'yes' || typeof draw_interval === 'undefined') {\n        draw_interval = window.setInterval(function () {\n          var currentSvg = svg_icon.html();\n          svg_icon.drawsvg('progress', stepManager());\n          if (currentSvg === _lastSvg && is_repeat === 'no') {\n            wrapper.addClass(settings.fill);\n            window.clearInterval(draw_interval);\n          }\n          _lastSvg = currentSvg;\n        }, speed);\n      }\n    }, function () {\n      if (pauseOnHover === 'yes') {\n        window.clearInterval(draw_interval);\n      }\n    });\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (ea.elementStatusCheck('eaelDrawSVG')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-svg-draw.default\", SVGDraw);\n});\n\n//# sourceURL=webpack:///./src/js/view/svg-draw.js?");

/***/ })

/******/ });