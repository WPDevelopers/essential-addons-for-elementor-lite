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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/custom-cursor.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/custom-cursor.js":
/*!**************************************!*\
  !*** ./src/js/edit/custom-cursor.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var CustomCursorHandler = function CustomCursorHandler($scope, $) {\n  if (window.isEditMode) {\n    var renderCustomCursor = function renderCustomCursor(model) {\n      var _model$attributes;\n      var settings = model === null || model === void 0 || (_model$attributes = model.attributes) === null || _model$attributes === void 0 || (_model$attributes = _model$attributes.settings) === null || _model$attributes === void 0 ? void 0 : _model$attributes.attributes;\n      if ('yes' === (settings === null || settings === void 0 ? void 0 : settings.eael_custom_cursor_switch)) {\n        var _model$attributes2;\n        var elementId = model === null || model === void 0 || (_model$attributes2 = model.attributes) === null || _model$attributes2 === void 0 ? void 0 : _model$attributes2.id,\n          element = $(\".elementor-element-\".concat(elementId));\n        if ('image' === (settings === null || settings === void 0 ? void 0 : settings.eael_custom_cursor_type)) {\n          var _settings$eael_custom;\n          element.attr('style', 'cursor: url(\"' + (settings === null || settings === void 0 || (_settings$eael_custom = settings.eael_custom_cursor_image) === null || _settings$eael_custom === void 0 ? void 0 : _settings$eael_custom.url) + '\") 0 0, auto;');\n        } else if ('icon' === (settings === null || settings === void 0 ? void 0 : settings.eael_custom_cursor_type)) {\n          var _settings$eael_custom2;\n          element.attr('style', 'cursor: url(\"data:image/svg+xml;base64,' + (settings === null || settings === void 0 || (_settings$eael_custom2 = settings.eael_custom_cursor_icon) === null || _settings$eael_custom2 === void 0 ? void 0 : _settings$eael_custom2.value) + '\") 0 0, auto;');\n        } else if ('svg_code' === (settings === null || settings === void 0 ? void 0 : settings.eael_custom_cursor_type)) {\n          element.attr('style', 'cursor: url(\"data:image/svg+xml;base64,' + btoa(settings === null || settings === void 0 ? void 0 : settings.eael_custom_cursor_svg_code) + '\") 0 0, auto;');\n        }\n      }\n    };\n    var _getHoverEffectSettingsVal = function getHoverEffectSettingsVal(models) {\n      $.each(models, function (i, model) {\n        renderCustomCursor(model);\n        if (model.attributes.elType !== 'widget') {\n          _getHoverEffectSettingsVal(model.attributes.elements.models);\n        }\n      });\n    };\n    _getHoverEffectSettingsVal(window.elementor.elements.models);\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelCustomCursor')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/widget\", CustomCursorHandler);\n});\n\n//# sourceURL=webpack:///./src/js/edit/custom-cursor.js?");

/***/ })

/******/ });