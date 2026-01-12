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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/vertical-text-orientation.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/vertical-text-orientation.js":
/*!**************************************************!*\
  !*** ./src/js/view/vertical-text-orientation.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var verticalTextOrientation = function verticalTextOrientation($scope, $) {\n  var gradientColor = $scope.data(\"gradient_colors\"),\n    $scopeId = $scope.data(\"id\");\n\n  /**\n   * For editor page - following hover-effect.js pattern\n   */\n  if (window.isEditMode) {\n    if (window.isRunFirstTime === undefined && window.isEditMode || 1) {\n      var _getVerticalTextSettings = function getVerticalTextSettings($el) {\n        $.each($el, function (i, el) {\n          var $getSettings = el.attributes.settings.attributes;\n          if (el.attributes.elType === 'widget') {\n            if ($getSettings['eael_vertical_text_orientation_switch'] === 'yes' && $getSettings['eael_vto_writing_styling_type'] === 'gradient') {\n              eaelEditModeSettings[el.attributes.id] = el.attributes.settings.attributes;\n            }\n          }\n          if (el.attributes.elType === 'container') {\n            _getVerticalTextSettings(el.attributes.elements.models);\n          }\n          if (el.attributes.elType === 'section') {\n            _getVerticalTextSettings(el.attributes.elements.models);\n          }\n          if (el.attributes.elType === 'column') {\n            _getVerticalTextSettings(el.attributes.elements.models);\n          }\n        });\n      };\n      window.isRunFirstTime = true;\n      var eaelEditModeSettings = [];\n      _getVerticalTextSettings(window.elementor.elements.models);\n    }\n\n    // Get settings for current widget from editor mode settings\n    var _loop = function _loop() {\n      if ($scopeId === key) {\n        // Get gradient color from editor settings\n        var editorGradientColor = eaelEditModeSettings[key]['eael_vto_writing_gradient_color_repeater'];\n\n        // Create array of objects like frontend format\n        var gradientArray = [];\n        if (editorGradientColor && editorGradientColor.length > 0) {\n          editorGradientColor.forEach(function (gradient) {\n            var _gradient$attributes;\n            if (gradient.attributes && gradient.attributes.eael_vto_writing_gradient_color && gradient !== null && gradient !== void 0 && (_gradient$attributes = gradient.attributes) !== null && _gradient$attributes !== void 0 && _gradient$attributes.eael_vto_writing_gradient_color_location) {\n              gradientArray.push({\n                color: gradient.attributes.eael_vto_writing_gradient_color,\n                location: gradient.attributes.eael_vto_writing_gradient_color_location.size + gradient.attributes.eael_vto_writing_gradient_color_location.unit\n              });\n            }\n          });\n\n          // Update gradientColor with the properly formatted array\n          if (gradientArray.length > 0) {\n            var gradientStops = [];\n\n            // Check if gradientColor exists and is an array\n            if (gradientArray && Array.isArray(gradientArray) && gradientArray.length > 0) {\n              gradientArray.forEach(function (gradient) {\n                if (gradient.color && gradient.location) {\n                  gradientStops.push(\"\".concat(gradient.color, \" \").concat(gradient.location));\n                }\n              });\n\n              // Only apply gradient if we have valid gradient stops\n              var gradientColorAngle = eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle\"][\"size\"] ? eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle\"][\"size\"] + eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle\"][\"unit\"] : \"0deg\";\n              var gradientColorAngleVertical = eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle_vertical\"][\"size\"] ? eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle_vertical\"][\"size\"] + eaelEditModeSettings[key][\"eael_vto_writing_gradient_color_angle_vertical\"][\"unit\"] : \"0deg\";\n              var animationControl = eaelEditModeSettings[key][\"eael_vto_writing_text_animation_control\"];\n              if (gradientStops.length > 0) {\n                if (animationControl === \"horizontal\") {\n                  linearGradient = \"linear-gradient(\".concat(gradientColorAngle, \", \").concat(gradientStops.join(\", \"), \")\") + \" -100% / 200%\";\n                } else {\n                  linearGradient = \"linear-gradient(\".concat(gradientColorAngleVertical, \", \").concat(gradientStops.join(\", \"), \")\") + \" 0% 0% / 100% 200%\";\n                }\n                var targetSelectors = [\".elementor-heading-title\", \".elementor-text-editor p\", \".elementor-headline\", \".eael-dual-header\", \".eael-fancy-text-container\"];\n                var fullSelectors = targetSelectors.map(function (selector) {\n                  return \".elementor-element-\".concat($scopeId, \" \").concat(selector);\n                }).join(', ');\n                $(fullSelectors).css({\n                  background: linearGradient,\n                  \"-webkit-background-clip\": \"text\",\n                  \"-webkit-text-fill-color\": \"transparent\",\n                  \"background-clip\": \"text\"\n                });\n              }\n            }\n          }\n        }\n      }\n    };\n    for (var key in eaelEditModeSettings) {\n      _loop();\n    }\n  }\n\n  /**\n   * Frontend page - apply gradient styles\n   */\n  function applyGradientStyles() {\n    var gradientStops = [];\n\n    // Check if gradientColor exists and is an array\n    if (gradientColor && Array.isArray(gradientColor) && gradientColor.length > 0) {\n      gradientColor.forEach(function (gradient) {\n        if (gradient.color && gradient.location) {\n          gradientStops.push(\"\".concat(gradient.color, \" \").concat(gradient.location));\n        }\n      });\n\n      // Only apply gradient if we have valid gradient stops\n      var gradientColorAngle = $scope.data(\"gradient_color_angle\");\n      var animationControl = $scope.data(\"animation_control\");\n      if (gradientStops.length > 0) {\n        if (animationControl === 'horizontal') {\n          linearGradient = \"linear-gradient(\".concat(gradientColorAngle, \", \").concat(gradientStops.join(\", \"), \")\") + \" -100% / 200%\";\n        } else {\n          linearGradient = \"linear-gradient(\".concat(gradientColorAngle, \", \").concat(gradientStops.join(\", \"), \")\") + \" 0% 0% / 100% 200%\";\n        }\n\n        // Define target selectors\n        var targetSelectors = [\".elementor-heading-title\", \"p\", \".elementor-headline\", \".eael-dual-header\", \".eael-fancy-text-container\"];\n        var fullSelector = targetSelectors.map(function (selector) {\n          return \".elementor-element-\".concat($scopeId, \" \").concat(selector);\n        }).join(', ');\n        $(fullSelector).css({\n          background: linearGradient,\n          \"-webkit-background-clip\": \"text\",\n          \"-webkit-text-fill-color\": \"transparent\",\n          \"background-clip\": \"text\"\n        });\n      }\n    }\n  }\n\n  // Apply gradient styles for both frontend and editor\n  applyGradientStyles();\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck(\"eaelVerticalTextOrientation\")) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", verticalTextOrientation);\n});\n\n//# sourceURL=webpack:///./src/js/view/vertical-text-orientation.js?");

/***/ })

/******/ });