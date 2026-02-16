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

eval("var ImageMaskingHandler = function ImageMaskingHandler($scope, $) {\n  var _window$elementor;\n  function get_clip_path(shape) {\n    var shapes = {\n      'bavel': 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)',\n      'rabbet': 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)',\n      'chevron-left': 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',\n      'chevron-right': 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',\n      'star': 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)'\n    };\n    return shapes[shape] || '';\n  }\n  function renderImageMasking(model) {\n    var _model$attributes, _model$attributes2;\n    var settings = model === null || model === void 0 || (_model$attributes = model.attributes) === null || _model$attributes === void 0 || (_model$attributes = _model$attributes.settings) === null || _model$attributes === void 0 ? void 0 : _model$attributes.attributes;\n    var elementId = model === null || model === void 0 || (_model$attributes2 = model.attributes) === null || _model$attributes2 === void 0 ? void 0 : _model$attributes2.id,\n      element = $(\".elementor-element-\".concat(elementId));\n    var styleId = 'eael-image-masking-' + elementId;\n    $scope = element;\n\n    // Remove existing style if present\n    $('#' + styleId).remove();\n    if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_enable_image_masking)) {\n      var style = '';\n      if ('clip' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_type)) {\n        var clipPath = '';\n        if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_enable_custom_clip_path)) {\n          clipPath = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_custom_clip_path;\n          clipPath = clipPath.replace('clip-path: ', '');\n        } else {\n          clipPath = get_clip_path(settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_clip_path);\n        }\n        if (clipPath) {\n          style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';\n        }\n        if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_hover_effect)) {\n          var hoverClipPath = '';\n          if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_enable_custom_clip_path_hover)) {\n            hoverClipPath = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_custom_clip_path_hover;\n            hoverClipPath = hoverClipPath.replace('clip-path: ', '');\n          } else {\n            hoverClipPath = get_clip_path(settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_clip_path_hover);\n          }\n          if (hoverClipPath) {\n            var hoverSelector = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_hover_selector;\n            if (hoverSelector) {\n              hoverSelector = ' ' + hoverSelector.trim();\n            }\n            style += '.elementor-element-' + elementId + hoverSelector + ':hover img {clip-path: ' + hoverClipPath + ';}';\n          }\n        }\n      } else if ('image' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_type)) {\n        var image = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_svg;\n        var mask_url = '';\n        if ('upload' !== image) {\n          var _EAELImageMaskingConf;\n          mask_url = ((_EAELImageMaskingConf = EAELImageMaskingConfig) === null || _EAELImageMaskingConf === void 0 ? void 0 : _EAELImageMaskingConf.svg_dir_url) + image + '.svg';\n        } else if ('upload' === image) {\n          var _image = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_image;\n          mask_url = _image === null || _image === void 0 ? void 0 : _image.url;\n        }\n        if (mask_url) {\n          style += '.elementor-element-' + elementId + ' img {mask-image: url(' + mask_url + '); -webkit-mask-image: url(' + mask_url + ');}';\n        }\n        if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_hover_effect)) {\n          var hover_mask_url = '';\n          var hoverImage = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_svg_hover;\n          if ('upload' !== hoverImage) {\n            var _EAELImageMaskingConf2;\n            hover_mask_url = ((_EAELImageMaskingConf2 = EAELImageMaskingConfig) === null || _EAELImageMaskingConf2 === void 0 ? void 0 : _EAELImageMaskingConf2.svg_dir_url) + hoverImage + '.svg';\n          } else if ('upload' === hoverImage) {\n            var _settings$eael_image_;\n            hover_mask_url = settings === null || settings === void 0 || (_settings$eael_image_ = settings.eael_image_masking_image_hover) === null || _settings$eael_image_ === void 0 ? void 0 : _settings$eael_image_.url;\n          }\n          if (hover_mask_url) {\n            var hover_selector = settings === null || settings === void 0 ? void 0 : settings.eael_image_masking_hover_selector;\n            if (hover_selector) {\n              hover_selector = ' ' + hover_selector.trim();\n            }\n            style += '.elementor-element-' + elementId + hover_selector + ':hover img {mask-image: url(' + hover_mask_url + '); -webkit-mask-image: url(' + hover_mask_url + ');}';\n          }\n        }\n      }\n      if (style) {\n        element.append('<style id=\"' + styleId + '\">' + style + '</style>');\n      }\n    }\n  }\n  function getImageMaskingSettingsVal(models) {\n    $.each(models, function (_, model) {\n      var _model$attributes3;\n      // Only process if image masking is enabled for this element\n      var settings = model === null || model === void 0 || (_model$attributes3 = model.attributes) === null || _model$attributes3 === void 0 || (_model$attributes3 = _model$attributes3.settings) === null || _model$attributes3 === void 0 ? void 0 : _model$attributes3.attributes;\n      if (settings && 'yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_enable_image_masking)) {\n        renderImageMasking(model);\n      }\n      if (model.attributes.elType !== 'widget') {\n        getImageMaskingSettingsVal(model.attributes.elements.models);\n      }\n    });\n  }\n  if ((_window$elementor = window.elementor) !== null && _window$elementor !== void 0 && (_window$elementor = _window$elementor.elements) !== null && _window$elementor !== void 0 && _window$elementor.models) {\n    var _window$elementor2;\n    getImageMaskingSettingsVal((_window$elementor2 = window.elementor) === null || _window$elementor2 === void 0 || (_window$elementor2 = _window$elementor2.elements) === null || _window$elementor2 === void 0 ? void 0 : _window$elementor2.models);\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelImageMaskingEditor')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", ImageMaskingHandler);\n});\n\n//# sourceURL=webpack:///./src/js/edit/image-masking.js?");

/***/ })

/******/ });