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

eval("var ImageMaskingHandler = function ImageMaskingHandler($scope, $) {\n  var $images = $scope.find('img');\n  var options = $scope.data('morphing-options');\n  if (options !== null && options !== void 0 && options.exclude) {\n    var exclude = options.exclude.split(',').map(function (item) {\n      return item.trim();\n    });\n    $images = $images.not(exclude.join(', '));\n  }\n\n  // Check if polygon animation is enabled and get settings\n  function createClippedSVG(imageSrc, uniqueId, viewBox, pathD, originalImage, transform) {\n    if ($(\"#eael-morphing-svg-\".concat(uniqueId)).length) {\n      return;\n    }\n    var imgWidth = originalImage.offsetWidth || originalImage.naturalWidth;\n    var imgHeight = originalImage.offsetHeight || originalImage.naturalHeight;\n\n    // Parse viewBox to get the coordinate system dimensions\n    var viewBoxValues = viewBox.split(' ').map(Number);\n    var viewBoxX = viewBoxValues[0] || 0;\n    var viewBoxY = viewBoxValues[1] || 0;\n    var viewBoxWidth = viewBoxValues[2];\n    var viewBoxHeight = viewBoxValues[3];\n\n    // Handle transform attribute if provided\n    var transformAttr = transform ? \"transform=\\\"\".concat(transform, \"\\\"\") : '';\n\n    // Calculate proper scaling to ensure the mask shape is never cropped\n    var imageAspectRatio = imgWidth / imgHeight;\n    var viewBoxAspectRatio = viewBoxWidth / viewBoxHeight;\n\n    // Always scale the image to cover the entire viewBox area\n    // This ensures the mask shape (which is defined in viewBox coordinates) is never cropped\n    var imageDisplayWidth, imageDisplayHeight, imageX, imageY;\n    if (imageAspectRatio > viewBoxAspectRatio) {\n      // Image is wider than viewBox - scale to cover full viewBox width\n      imageDisplayWidth = viewBoxWidth;\n      imageDisplayHeight = viewBoxWidth / imageAspectRatio;\n      imageX = viewBoxX;\n      imageY = viewBoxY + (viewBoxHeight - imageDisplayHeight) / 2;\n    } else {\n      // Image is taller than viewBox - scale to cover full viewBox height\n      imageDisplayHeight = viewBoxHeight;\n      imageDisplayWidth = viewBoxHeight * imageAspectRatio;\n      imageX = viewBoxX + (viewBoxWidth - imageDisplayWidth) / 2;\n      imageY = viewBoxY;\n    }\n\n    // Ensure image covers the entire viewBox to prevent mask cropping\n    if (imageDisplayWidth < viewBoxWidth || imageDisplayHeight < viewBoxHeight) {\n      // Scale up to ensure full coverage\n      var scaleX = viewBoxWidth / imageDisplayWidth;\n      var scaleY = viewBoxHeight / imageDisplayHeight;\n      var scale = Math.max(scaleX, scaleY);\n      imageDisplayWidth *= scale;\n      imageDisplayHeight *= scale;\n      imageX = viewBoxX + (viewBoxWidth - imageDisplayWidth) / 2;\n      imageY = viewBoxY + (viewBoxHeight - imageDisplayHeight) / 2;\n    }\n    return \"\\n            <svg id=\\\"eael-morphing-svg-\".concat(uniqueId, \"\\\" viewBox=\\\"\").concat(viewBox, \"\\\" width=\\\"\").concat(imgWidth, \"\\\" height=\\\"\").concat(imgHeight, \"\\\" style=\\\"position: absolute; top: 0; left: 0; visibility: visible; display: block;\\\">\\n                <defs>\\n                    <clipPath id=\\\"clip-path-\").concat(uniqueId, \"\\\" clipPathUnits=\\\"userSpaceOnUse\\\" style=\\\"transform-box: view-box; transform-origin: center;\\\">\\n                        <path class=\\\"clip-path\\\" d=\\\"\").concat(pathD, \"\\\" \").concat(transformAttr, \"/>\\n                    </clipPath>\\n                </defs>\\n                <image x=\\\"\").concat(imageX, \"\\\" y=\\\"\").concat(imageY, \"\\\" width=\\\"\").concat(imageDisplayWidth, \"\\\" height=\\\"\").concat(imageDisplayHeight, \"\\\" clip-path=\\\"url(#clip-path-\").concat(uniqueId, \")\\\" href=\\\"\").concat(imageSrc, \"\\\" preserveAspectRatio=\\\"none\\\"/>\\n            </svg>\\n        \");\n  }\n  if ($scope.hasClass('eael-morphing-enabled')) {\n    if ('clip-path' === options.type) {\n      var shapes = atob(options.shapes);\n      var animationData = {\n        polygonShapes: JSON.parse(shapes)\n      };\n      if (options.duration) {\n        animationData.duration = options.duration;\n      }\n      if (options.scaleMin) {\n        animationData.scale.min = options.scaleMin;\n      }\n      if (options.rotation) {\n        animationData.rotation = options.rotation;\n      }\n      if (options.rotationSpeed) {\n        animationData.rotationSpeed = options.rotationSpeed;\n      }\n      if (animationData && typeof PolygonMorphingAnimation !== 'undefined' && $images.length > 0) {\n        // Create animation instance for each image individually\n        $images.each(function (_, imgElement) {\n          new PolygonMorphingAnimation(imgElement, animationData);\n        });\n      }\n    } else if ('svg' === options.type) {\n      var svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');\n      if (!svg_items.length) {\n        return;\n      }\n      var viewBox = svg_items.first().attr('viewBox');\n      var defaultPath = svg_items.first().find('path').first().attr('d');\n      $images.each(function (index, image) {\n        image = $(image);\n        var image_src = image.attr('src');\n        var uniqueId = $scope.data('id') + '-' + index;\n\n        // Wrap image in container for proper positioning\n        if (!image.parent().hasClass('eael-image-masking-container')) {\n          image.wrap('<div class=\"eael-image-masking-container\" style=\"position: relative; display: inline-block; overflow: hidden;\"></div>');\n        }\n\n        // Hide original image and add SVG\n        image.css('visibility', 'hidden');\n\n        // Get initial transform from the first SVG path\n        var initialTransform = svg_items.first().find('path').first().attr('transform') || '';\n        image.after(createClippedSVG(image_src, uniqueId, viewBox, defaultPath, image[0], initialTransform));\n      });\n\n      // Initialize proper centering for all clip paths\n      var clipPaths = $scope.find('.clip-path');\n\n      // Add CSS for proper centering - inject styles directly\n      if (!$('#eael-morphing-center-fix').length) {\n        $('head').append(\"\\n                    <style id=\\\"eael-morphing-center-fix\\\">\\n                        .eael-morphing-enabled clipPath,\\n                        .eael-morphing-enabled .clip-path {\\n                            transform-origin: center !important;\\n                            transform-box: view-box !important;\\n                        }\\n                        .eael-morphing-enabled svg {\\n                            overflow: visible !important;\\n                        }\\n                    </style>\\n                \");\n      }\n\n      // Set initial transform origin for all clip paths\n      clipPaths.css({\n        'transform-origin': 'center !important',\n        'transform-box': 'view-box !important'\n      });\n\n      // Start animation immediately - remove any delays\n      var morphing = gsap.timeline({\n        repeat: options !== null && options !== void 0 && options.loop ? -1 : 0,\n        yoyo: false,\n        repeatDelay: 0.001\n      });\n      svg_items.first().appendTo(svg_items.parent());\n      svg_items = $('#eael-svg-items-' + $scope.data('id')).find('svg');\n      svg_items.each(function (index, element) {\n        var $svg = $(element);\n        var $path = $svg.find('path').first();\n        var transform = $path.attr('transform') || \"\";\n        var clipPath = $scope.find('.clip-path');\n\n        // Calculate duration per shape for smooth transitions\n        var totalDuration = (options === null || options === void 0 ? void 0 : options.duration) || 6;\n        var durationPerShape = totalDuration / svg_items.length;\n\n        // Start first animation immediately, others at calculated intervals\n        var startTime = index * durationPerShape;\n\n        // Check if morphSVG is available, otherwise fallback to basic animation\n        if (typeof gsap.plugins.morphSVG !== 'undefined') {\n          morphing.to(clipPath, {\n            morphSVG: {\n              shape: $path[0]\n            },\n            duration: durationPerShape,\n            ease: (options === null || options === void 0 ? void 0 : options.ease) || \"sine.inOut\",\n            transformOrigin: \"center center\",\n            onStart: function onStart() {\n              // Apply any custom transform\n              if (transform) {\n                clipPath.attr('transform', transform);\n              }\n            }\n          }, startTime);\n        } else {\n          // Fallback to basic path morphing\n          morphing.to(clipPath, {\n            attr: {\n              d: $path.attr('d')\n            },\n            duration: durationPerShape,\n            ease: (options === null || options === void 0 ? void 0 : options.ease) || \"sine.inOut\",\n            onStart: function onStart() {\n              // Apply any custom transform\n              if (transform) {\n                clipPath.attr('transform', transform);\n              }\n            }\n          }, startTime);\n        }\n      });\n    }\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelImageMaskingView') || window.isEditMode) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", ImageMaskingHandler);\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/container\", ImageMaskingHandler);\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/section\", ImageMaskingHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/image-masking.js?");

/***/ })

/******/ });