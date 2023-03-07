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

eval("function _typeof(obj) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && \"function\" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }, _typeof(obj); }\nvar _SVGDraw = function _SVGDraw($scope, $) {\n  var wrapper = $('.eael-svg-draw-container', $scope),\n    svg_icon = $('svg', wrapper),\n    paths = $('path', svg_icon),\n    speed = wrapper.data('speed'),\n    count = 50,\n    path_count = 0,\n    draw_interval;\n  var draw_line = function draw_line() {\n    var step = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : .5;\n    if (count < 0 && path_count <= paths.length) {\n      count = 50;\n      $(paths[path_count]).css({\n        'stroke-dasharray': 'none'\n      });\n      path_count++;\n    } else if (path_count === paths.length) {\n      clearInterval(draw_interval);\n      wrapper.addClass(wrapper.data('fill'));\n      return;\n    }\n    if (_typeof(paths[path_count]) !== undefined) {\n      var count_1 = 50 - count;\n      $(paths[path_count]).css({\n        'stroke-dasharray': count_1 + 'px, ' + count + 'px'\n      });\n    }\n    count -= step;\n  };\n  if (svg_icon.parent().hasClass('hover')) {\n    svg_icon.hover(function () {\n      draw_interval = window.setInterval(draw_line, speed);\n    }, function () {\n      window.clearInterval(draw_interval);\n    });\n  } else if (svg_icon.parent().hasClass('page-load')) {\n    window.setInterval(draw_line, speed);\n  }\n};\nvar SVGDraw = function SVGDraw($scope, $) {\n  var wrapper = $('.eael-svg-draw-container', $scope),\n    svg_icon = $('svg', wrapper),\n    speed = wrapper.data('speed'),\n    is_repeat = wrapper.data('loop'),\n    drawSvg,\n    addOrSubtract,\n    stepCount = 0,\n    $doc = $(document),\n    $win = $(window),\n    max = $doc.height() - $win.height();\n  function stepManager() {\n    if (addOrSubtract) {\n      stepCount += 0.01;\n      if (stepCount >= 1) {\n        addOrSubtract = false;\n      }\n    } else {\n      stepCount -= 0.01;\n      if (stepCount <= 0) {\n        addOrSubtract = true;\n      }\n    }\n    return stepCount;\n  }\n  function drawSVG() {\n    var lastSvg = '';\n    var drawSvg = setInterval(function () {\n      var currentSvg = svg_icon.html();\n      svg_icon.drawsvg('progress', stepManager());\n      if (currentSvg === lastSvg && is_repeat === 'no') {\n        wrapper.addClass(wrapper.data('fill'));\n        clearInterval(drawSvg);\n      }\n      lastSvg = currentSvg;\n    }, speed);\n  }\n  if (svg_icon.parent().hasClass('page-scroll')) {\n    $win.on('scroll', function () {\n      var step = $win.scrollTop() / max;\n      svg_icon.drawsvg('progress', step);\n    });\n  } else if (svg_icon.parent().hasClass('page-load')) {\n    drawSVG();\n  } else if (svg_icon.parent().hasClass('hover')) {\n    svg_icon.hover(function () {\n      drawSVG();\n    }, function () {\n      window.clearInterval(drawSvg);\n    });\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (ea.elementStatusCheck('eaelDrawSVG')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-svg-draw.default\", SVGDraw);\n});\n\n//# sourceURL=webpack:///./src/js/view/svg-draw.js?");

/***/ })

/******/ });