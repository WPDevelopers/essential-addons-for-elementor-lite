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

eval("var SVGDraw = function SVGDraw($scope, $) {\n  var wrapper = $('.eael-svg-draw-container', $scope),\n    widget_id = $scope.data('id'),\n    svg_icon = $('svg', wrapper),\n    settings = wrapper.data('settings'),\n    transition = Number(settings.transition),\n    loop_delay = Number(settings.loop_delay),\n    offset = 0,\n    lines = $('path, circle, rect, polygon', svg_icon);\n  if (settings !== null && settings !== void 0 && settings.has_pro) {\n    return false;\n  }\n\n  // Helper: Easing functions dictionary\n  var easingFunctions = {\n    'linear': 'linear',\n    'power1.in': 'cubic-bezier(0.55, 0.085, 0.68, 0.53)',\n    'power1.out': 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',\n    'power1.inOut': 'cubic-bezier(0.455, 0.03, 0.515, 0.955)',\n    'power2.in': 'cubic-bezier(0.55, 0.055, 0.675, 0.19)',\n    'power2.out': 'cubic-bezier(0.215, 0.61, 0.355, 1)',\n    'power2.inOut': 'cubic-bezier(0.645, 0.045, 0.355, 1)',\n    'power3.in': 'cubic-bezier(0.895, 0.03, 0.685, 0.22)',\n    'power3.out': 'cubic-bezier(0.165, 0.84, 0.44, 1)',\n    'power3.inOut': 'cubic-bezier(0.77, 0, 0.175, 1)',\n    'power4.in': 'cubic-bezier(0.895, 0.03, 0.685, 0.22)',\n    'power4.out': 'cubic-bezier(0.165, 0.84, 0.44, 1)',\n    'power4.inOut': 'cubic-bezier(0.86, 0, 0.07, 1)',\n    'none': 'linear',\n    'ease': 'ease',\n    'ease-in': 'ease-in',\n    'ease-out': 'ease-out',\n    'ease-in-out': 'ease-in-out'\n  };\n\n  // Helper: Get easing value\n  function getEasing(easeName) {\n    return easingFunctions[easeName] || 'linear';\n  }\n\n  // Helper: Animate fill color\n  function animateFill(elements, targetColor, duration) {\n    $.each(elements, function (index, element) {\n      element.animate({\n        fill: [getComputedStyle(element).fill, targetColor]\n      }, {\n        duration: duration * 1000,\n        fill: 'forwards',\n        easing: 'ease'\n      });\n    });\n  }\n\n  // Initial fill animation\n  if ('always' === settings.fill_type || 'before' === settings.fill_type) {\n    animateFill(lines, settings.fill_color, transition);\n  }\n\n  // Main drawing function\n  function drawSVGLine() {\n    // Initialize stroke dash properties\n    $.each(lines, function (index, line) {\n      var length = line.getTotalLength() * (settings.stroke_length * .01);\n      line.style.strokeDasharray = length;\n      line.style.strokeDashoffset = length;\n    });\n    var animations = [];\n    var isPaused = false;\n    var shouldLoop = 'yes' === settings.loop;\n    var isReverse = \"reverse\" === settings.direction;\n\n    // Create animation for each line\n    function createStrokeAnimation() {\n      $.each(lines, function (index, line) {\n        var animation = line.animate({\n          strokeDashoffset: [line.style.strokeDashoffset, offset]\n        }, {\n          duration: settings.speed * 1000,\n          fill: 'forwards',\n          easing: getEasing(settings.ease_type)\n        });\n        animations.push(animation);\n      });\n\n      // Handle animation complete\n      animations[0].onfinish = function () {\n        if (isPaused) return;\n\n        // Handle fill animations on complete\n        if ('' !== settings.fill_color) {\n          if ('after' === settings.fill_type) {\n            animateFill(lines, settings.fill_color, transition);\n            if (isReverse) {\n              setTimeout(function () {\n                if (!isPaused) {\n                  animateFill(lines, settings.fill_color + '00', transition);\n                }\n              }, loop_delay * 1000);\n            }\n          } else if ('before' === settings.fill_type) {\n            animateFill(lines, settings.fill_color + '00', transition);\n          }\n        }\n\n        // Handle looping\n        if (shouldLoop) {\n          setTimeout(function () {\n            if (!isPaused) {\n              if (isReverse) {\n                // Reverse animation: animate back to initial state\n                reverseStrokeAnimation();\n              } else {\n                // Restart animation: reset and play again\n                resetAndRestart();\n              }\n            }\n          }, loop_delay * 1000);\n        }\n      };\n    }\n\n    // Reverse animation (for yoyo effect)\n    function reverseStrokeAnimation() {\n      animations = [];\n      $.each(lines, function (index, line) {\n        var currentOffset = parseFloat(line.style.strokeDashoffset) || 0;\n        var length = line.getTotalLength() * (settings.stroke_length * .01);\n        var animation = line.animate({\n          strokeDashoffset: [currentOffset, length]\n        }, {\n          duration: settings.speed * 1000,\n          fill: 'forwards',\n          easing: getEasing(settings.ease_type)\n        });\n        animations.push(animation);\n      });\n      animations[0].onfinish = function () {\n        if (isPaused) return;\n        setTimeout(function () {\n          if (!isPaused) {\n            resetAndRestart();\n          }\n        }, loop_delay * 1000);\n      };\n    }\n\n    // Reset and restart the animation\n    function resetAndRestart() {\n      // Reset stroke dash offset\n      $.each(lines, function (index, line) {\n        var length = line.getTotalLength() * (settings.stroke_length * .01);\n        line.style.strokeDashoffset = length;\n      });\n\n      // Handle fill on start\n      if ('' !== settings.fill_color) {\n        if ('after' === settings.fill_type && \"restart\" === settings.direction) {\n          animateFill(lines, settings.fill_color + '00', transition);\n        } else if ('before' === settings.fill_type) {\n          animateFill(lines, settings.fill_color, transition);\n        }\n      }\n\n      // Restart animation\n      animations = [];\n      createStrokeAnimation();\n    }\n\n    // Handle fill on start\n    if ('' !== settings.fill_color) {\n      if ('after' === settings.fill_type && \"restart\" === settings.direction) {\n        animateFill(lines, settings.fill_color + '00', transition);\n      } else if ('before' === settings.fill_type) {\n        animateFill(lines, settings.fill_color, transition);\n      }\n    }\n\n    // Start initial animation\n    createStrokeAnimation();\n\n    // Handle pause on hover\n    if ('yes' === settings.pause) {\n      svg_icon.hover(function () {\n        isPaused = true;\n        $.each(animations, function (index, animation) {\n          animation.pause();\n        });\n      }, function () {\n        isPaused = false;\n        $.each(animations, function (index, animation) {\n          animation.play();\n        });\n      });\n    }\n  }\n  if (wrapper.hasClass('page-load')) {\n    drawSVGLine(lines, settings);\n  } else if (wrapper.hasClass('mouse-hover')) {\n    svg_icon.hover(function () {\n      if (!wrapper.hasClass('draw-initialized')) {\n        drawSVGLine(lines, settings);\n        wrapper.addClass('draw-initialized');\n      }\n    });\n  } else if (wrapper.hasClass('page-scroll')) {\n    // Parse start and end points (format: \"80%\" or \"center\")\n    var parseScrollPoint = function parseScrollPoint(point) {\n      if (point.includes('%')) {\n        return parseFloat(point) / 100;\n      }\n      // Handle named positions\n      var positions = {\n        'top': 0,\n        'center': 0.5,\n        'bottom': 1\n      };\n      return positions[point] || 0.5;\n    };\n    // Initialize stroke dash properties\n    $.each(lines, function (index, line) {\n      var length = line.getTotalLength() * (settings.stroke_length * .01);\n      line.style.strokeDasharray = length;\n      line.style.strokeDashoffset = length;\n    });\n    var startPoint = parseScrollPoint(settings.start_point);\n    var endPoint = parseScrollPoint(settings.end_point);\n\n    // Scroll-based animation using Intersection Observer with custom logic\n    var observer;\n    var scrollHandler = function scrollHandler() {\n      var rect = wrapper[0].getBoundingClientRect();\n      var windowHeight = window.innerHeight;\n\n      // Calculate scroll progress\n      var startTrigger = windowHeight * (1 - startPoint);\n      var endTrigger = windowHeight * (1 - endPoint);\n      var progress = 0;\n      if (rect.top <= startTrigger && rect.top >= endTrigger) {\n        progress = (startTrigger - rect.top) / (startTrigger - endTrigger);\n        progress = Math.max(0, Math.min(1, progress));\n      } else if (rect.top < endTrigger) {\n        progress = 1;\n      }\n\n      // Update stroke dash offset based on scroll progress\n      $.each(lines, function (index, line) {\n        var length = line.getTotalLength() * (settings.stroke_length * .01);\n        var targetOffset = length * (1 - progress);\n        line.style.strokeDashoffset = targetOffset;\n      });\n\n      // Handle fill color changes based on progress\n      if ('' !== settings.fill_color && ('before' === settings.fill_type || 'after' === settings.fill_type)) {\n        var fill1 = settings.fill_color;\n        var fill2 = settings.fill_color + '00';\n        if ('after' === settings.fill_type) {\n          fill1 = settings.fill_color + '00';\n          fill2 = settings.fill_color;\n        }\n        if (progress < 0.95) {\n          $.each(lines, function (index, line) {\n            line.style.fill = fill1;\n          });\n        } else if (progress > 0.95) {\n          $.each(lines, function (index, line) {\n            line.style.fill = fill2;\n          });\n        }\n      }\n    };\n\n    // Add scroll listener\n    window.addEventListener('scroll', scrollHandler, {\n      passive: true\n    });\n    // Initial calculation\n    scrollHandler();\n\n    // Cleanup on element removal\n    wrapper.on('remove', function () {\n      window.removeEventListener('scroll', scrollHandler);\n    });\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelDrawSVG')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-svg-draw.default\", SVGDraw);\n});\n\n//# sourceURL=webpack:///./src/js/view/svg-draw.js?");

/***/ })

/******/ });