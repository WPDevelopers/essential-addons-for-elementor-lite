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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/reading-progress.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/reading-progress.js":
/*!*****************************************!*\
  !*** ./src/js/view/reading-progress.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("jQuery(document).ready(function () {\n  // scroll func\n  jQuery(window).scroll(function () {\n    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;\n    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;\n    var scrolled = winScroll / height * 100;\n    jQuery(\".eael-reading-progress-fill\").css({\n      width: scrolled + \"%\"\n    });\n  }); // live prev\n\n  if (isEditMode) {\n    elementor.settings.page.addChangeCallback(\"eael_ext_reading_progress\", function (newValue) {\n      var $settings = elementor.settings.page.getSettings();\n\n      if (newValue == \"yes\") {\n        if (jQuery(\".eael-reading-progress-wrap\").length == 0) {\n          jQuery(\"body\").append('<div class=\"eael-reading-progress-wrap eael-reading-progress-wrap-local\"><div class=\"eael-reading-progress eael-reading-progress-local eael-reading-progress-' + $settings.settings.eael_ext_reading_progress_position + '\"><div class=\"eael-reading-progress-fill\"></div></div><div class=\"eael-reading-progress eael-reading-progress-global eael-reading-progress-' + $settings.settings.eael_ext_reading_progress_position + '\"><div class=\"eael-reading-progress-fill\"></div></div></div>');\n        }\n\n        jQuery(\".eael-reading-progress-wrap\").addClass(\"eael-reading-progress-wrap-local\").removeClass(\"eael-reading-progress-wrap-global eael-reading-progress-wrap-disabled\");\n      } else {\n        jQuery(\".eael-reading-progress-wrap\").removeClass(\"eael-reading-progress-wrap-local eael-reading-progress-wrap-global\");\n\n        if ($settings.settings.eael_ext_reading_progress_has_global == true) {\n          jQuery(\".eael-reading-progress-wrap\").addClass(\"eael-reading-progress-wrap-global\");\n        } else {\n          jQuery(\".eael-reading-progress-wrap\").addClass(\"eael-reading-progress-wrap-disabled\");\n        }\n      }\n    });\n    elementor.settings.page.addChangeCallback(\"eael_ext_reading_progress_position\", function (newValue) {\n      elementor.settings.page.setSettings(\"eael_ext_reading_progress_position\", newValue);\n      jQuery(\".eael-reading-progress\").removeClass(\"eael-reading-progress-top eael-reading-progress-bottom\").addClass(\"eael-reading-progress-\" + newValue);\n    });\n  }\n});\n\n//# sourceURL=webpack:///./src/js/view/reading-progress.js?");

/***/ })

/******/ });