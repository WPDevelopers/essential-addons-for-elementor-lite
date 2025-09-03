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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/image-masking.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/image-masking.js":
/*!**************************************!*\
  !*** ./src/js/view/image-masking.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var ImageMaskingHandler = function ImageMaskingHandler($scope, $) {\n  var $images = $scope.find('img');\n  var options = $scope.data('morphing-options');\n  if (options !== null && options !== void 0 && options.exclude) {\n    var exclude = options.exclude.split(',').map(function (item) {\n      return item.trim();\n    });\n    $images = $images.not(exclude.join(', '));\n  }\n\n  // Check if polygon animation is enabled and get settings\n  function createClippedSVG(imageSrc, uniqueId, viewBox, pathD) {\n    return \"\\n            <svg viewBox=\\\"\".concat(viewBox, \"\\\" width=\\\"100%\\\" style=\\\"visibility: visible;\\\">\\n                <defs>\\n                    <clipPath id=\\\"clip-path-\").concat(uniqueId, \"\\\">\\n                        <path class=\\\"clip-path\\\" d=\\\"\").concat(pathD, \"\\\" transform=\\\"translate(100, 100)\\\"/>\\n                    </clipPath>\\n                </defs>\\n                <image width=\\\"100%\\\" height=\\\"100%\\\" clip-path=\\\"url(#clip-path-\").concat(uniqueId, \")\\\" href=\\\"\").concat(imageSrc, \"\\\"/>\\n            </svg>\\n        \");\n  }\n  if ($scope.hasClass('eael-morphing-enabled')) {\n    if ('clip-path' === options.type) {\n      var shapes = atob(options.shapes);\n      var animationData = {\n        polygonShapes: JSON.parse(shapes)\n      };\n      if (options.duration) {\n        animationData.duration = options.duration;\n      }\n      if (options.scaleMin) {\n        animationData.scale.min = options.scaleMin;\n      }\n      if (options.scaleMax) {\n        animationData.scale.max = options.scaleMax;\n      }\n      if (options.rotation) {\n        animationData.rotation = options.rotation;\n      }\n      if (options.rotationSpeed) {\n        animationData.rotationSpeed = options.rotationSpeed;\n      }\n      if (options.scaleMin) {\n        animationData.scale.min = options.scaleMin;\n      }\n      if (options.scaleMax) {\n        animationData.scale.max = options.scaleMax;\n      }\n      if (animationData && typeof PolygonMorphingAnimation !== 'undefined' && $img.length > 0) {\n        // Create animation instance for each image individually\n        $img.each(function (_, imgElement) {\n          new PolygonMorphingAnimation(imgElement, animationData);\n        });\n      }\n    } else if ('svg' === options.type) {\n      var clipPathTransform = function clipPathTransform(transform, svgElement) {\n        var $clip = $(svgElement).find('path');\n        var oldTransform = $clip.attr('transform') || \"translate(0,0)\";\n        var newTransform = transform;\n        function parseTransform(str) {\n          var t = /translate\\(([^,]+)[ ,]+([^,]+)\\)/.exec(str);\n          return {\n            x: t ? parseFloat(t[1]) : 0,\n            y: t ? parseFloat(t[2]) : 0\n          };\n        }\n        var from = parseTransform(oldTransform);\n        var to = parseTransform(newTransform);\n        gsap.fromTo($clip, {\n          attr: {\n            transform: \"translate(\".concat(from.x, \", \").concat(from.y, \")\")\n          }\n        }, {\n          attr: {\n            transform: \"translate(\".concat(to.x, \", \").concat(to.y, \")\")\n          },\n          duration: (options === null || options === void 0 ? void 0 : options.duration) || 6,\n          ease: ease\n        });\n      };\n      var svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');\n      if (!svg_items.length) {\n        return;\n      }\n      var viewBox = svg_items.first().attr('viewBox');\n      var defaultPath = svg_items.first().find('path').first().attr('d');\n      $images.each(function (index, image) {\n        image = $(image);\n        var image_src = image.attr('src');\n        var uniqueId = $scope.data('id') + '-' + index;\n        image.hide();\n        image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath));\n      });\n      var morphing = gsap.timeline({\n        repeat: options !== null && options !== void 0 && options.loop ? -1 : 0,\n        yoyo: options === null || options === void 0 ? void 0 : options.loop,\n        repeatDelay: 0.001,\n        delay: 0.001\n      });\n      var ease = (options === null || options === void 0 ? void 0 : options.ease) || \"sine.inOut\";\n      var gap = \"+=0\";\n      svg_items.each(function (index, element) {\n        var $svg = $(element);\n        var $path = $svg.find('path').first();\n        var transform = $path.attr('transform') || \"translate(0, 0)\";\n        var clipPath = $scope.find('.clip-path');\n        morphing.to(clipPath, {\n          morphSVG: {\n            shape: $path[0]\n          },\n          duration: (options === null || options === void 0 ? void 0 : options.duration) || 6,\n          ease: ease,\n          onStart: function onStart() {\n            clipPathTransform(transform, element);\n          }\n        }, gap);\n      });\n    }\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelImageMaskingView') || window.isEditMode) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", ImageMaskingHandler);\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/container\", ImageMaskingHandler);\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/section\", ImageMaskingHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/image-masking.js?");

/***/ })

/******/ });