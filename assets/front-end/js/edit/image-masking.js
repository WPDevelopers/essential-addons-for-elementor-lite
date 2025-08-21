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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/image-masking.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/image-masking.js":
/*!**************************************!*\
  !*** ./src/js/edit/image-masking.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var ImageMaskingHandler = function ImageMaskingHandler($scope, $) {\n  function get_clip_path(shape) {\n    var shapes = {\n      'circle': 'circle(50% at 50% 50%)',\n      'ellipse': 'ellipse(50% 35% at 50% 50%)',\n      'inset': 'inset(10% 10% 10% 10%)',\n      'triangle': 'polygon(50% 0%, 0% 100%, 100% 100%)',\n      'trapezoid': 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)',\n      'parallelogram': 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)',\n      'rhombus': 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',\n      'pentagon': 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)',\n      'hexagon': 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)',\n      'heptagon': 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)',\n      'octagon': 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',\n      'nonagon': 'polygon(50% 0%, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%, 0% 50%, 15% 15%)',\n      'decagon': 'polygon(50% 0%, 80% 10%, 100% 40%, 95% 80%, 65% 100%, 35% 100%, 5% 80%, 0% 40%, 20% 10%)',\n      'star': 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',\n      'cross': 'polygon(30% 0%, 70% 0%, 70% 30%, 100% 30%, 100% 70%, 70% 70%, 70% 100%, 30% 100%, 30% 70%, 0% 70%, 0% 30%, 30% 30%)',\n      'arrow': 'polygon(0% 40%, 60% 40%, 60% 20%, 100% 50%, 60% 80%, 60% 60%, 0% 60%)',\n      'left_arrow': 'polygon(100% 40%, 40% 40%, 40% 20%, 0% 50%, 40% 80%, 40% 60%, 100% 60%)',\n      'chevron': 'polygon(25% 0%, 100% 50%, 25% 100%, 0% 75%, 50% 50%, 0% 25%)',\n      'message': 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 50% 100%, 50% 75%, 0% 75%)',\n      'close': 'polygon(20% 0%, 50% 30%, 80% 0%, 100% 20%, 70% 50%, 100% 80%, 80% 100%, 50% 70%, 20% 100%, 0% 80%, 30% 50%, 0% 20%)',\n      'frame': 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)',\n      'rabbet': 'polygon(20% 0%, 80% 0%, 80% 20%, 100% 20%, 100% 80%, 80% 80%, 80% 100%, 20% 100%, 20% 80%, 0% 80%, 0% 20%, 20% 20%)',\n      'starburst': 'polygon(50% 0%, 60% 20%, 80% 10%, 70% 30%, 90% 50%, 70% 70%, 80% 90%, 60% 80%, 50% 100%, 40% 80%, 20% 90%, 30% 70%, 10% 50%, 30% 30%, 20% 10%, 40% 20%)',\n      'blob': 'polygon(50% 0%, 80% 10%, 100% 40%, 90% 70%, 60% 100%, 30% 90%, 10% 60%, 0% 30%, 20% 10%)'\n    };\n    return shapes[shape] || '';\n  }\n  function renderImageMasking(model) {\n    var _model$attributes, _model$attributes2;\n    var settings = model === null || model === void 0 || (_model$attributes = model.attributes) === null || _model$attributes === void 0 || (_model$attributes = _model$attributes.settings) === null || _model$attributes === void 0 ? void 0 : _model$attributes.attributes;\n    var elementId = model === null || model === void 0 || (_model$attributes2 = model.attributes) === null || _model$attributes2 === void 0 ? void 0 : _model$attributes2.id,\n      element = $(\".elementor-element-\".concat(elementId));\n    var styleId = 'eael-image-masking-' + elementId;\n\n    // Remove existing style if present\n    $('#' + styleId).remove();\n    if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_enable_image_masking)) {\n      var style = '';\n      if ('clip' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_type)) {\n        var clipPath = get_clip_path(settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_clip_path);\n        if ('custom' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_clip_path)) {\n          clipPath = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_custom_clip_path;\n          clipPath = clipPath.replace('clip-path: ', '');\n        }\n        if (clipPath) {\n          style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';\n        }\n      }\n      if (style) {\n        element.append('<style id=\"' + styleId + '\">' + style + '</style>');\n      }\n    }\n  }\n  function getImageMaskingSettingsVal(models) {\n    $.each(models, function (_, model) {\n      renderImageMasking(model);\n      if (model.attributes.elType !== 'widget') {\n        getImageMaskingSettingsVal(model.attributes.elements.models);\n      }\n    });\n  }\n  getImageMaskingSettingsVal(window.elementor.elements.models);\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelImageMaskingEditor')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", ImageMaskingHandler);\n});\n\n//# sourceURL=webpack:///./src/js/edit/image-masking.js?");

/***/ })

/******/ });