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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/flip-box.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/flip-box.js":
/*!*********************************!*\
  !*** ./src/js/view/flip-box.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var FlipBox = function FlipBox($scope, $) {\n  var wrapper = $scope.find('.eael-elements-flip-box-container');\n  function setFixedHeight() {\n    var frontHeight = wrapper.find('.eael-elements-flip-box-front-container').outerHeight();\n    var rearHeight = wrapper.find('.eael-elements-flip-box-rear-container').outerHeight();\n    var maxHeight = Math.max(frontHeight, rearHeight);\n    wrapper.find('.eael-elements-flip-box-flip-card').height(maxHeight);\n  }\n  function setDynamicHeight() {\n    var frontHeight = wrapper.find('.eael-elements-flip-box-front-container').outerHeight();\n    var rearHeight = wrapper.find('.eael-elements-flip-box-rear-container').outerHeight();\n    if (wrapper.hasClass('--active')) {\n      wrapper.find('.eael-elements-flip-box-flip-card').height(rearHeight);\n    } else {\n      wrapper.find('.eael-elements-flip-box-flip-card').height(frontHeight);\n    }\n  }\n  $('.eael-flip-box-click', $scope).off('click').on('click', function () {\n    $(this).toggleClass('--active');\n  });\n  $('.eael-flip-box-hover', $scope).on('mouseenter mouseleave', function () {\n    $(this).toggleClass('--active');\n  });\n  if (wrapper.hasClass('eael-flipbox-auto-height')) {\n    if (wrapper.hasClass('eael-flipbox-max')) {\n      var heightAdjustment = setInterval(setFixedHeight, 200);\n      setTimeout(function () {\n        clearInterval(heightAdjustment);\n      }, 5000);\n    } else if (wrapper.hasClass('eael-flipbox-dynamic')) {\n      $('.eael-flip-box-click', $scope).on('click', debounce(setDynamicHeight, 100));\n      $('.eael-flip-box-hover', $scope).on('mouseenter mouseleave', debounce(setDynamicHeight, 100));\n    }\n  }\n\n  // Debounce function to limit resize event frequency\n  function debounce(func, wait) {\n    var timeout;\n    return function executedFunction() {\n      var later = function later() {\n        clearTimeout(timeout);\n        func();\n      };\n      clearTimeout(timeout);\n      timeout = setTimeout(later, wait);\n    };\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelFlipBox')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-flip-box.default\", FlipBox);\n});\n\n//# sourceURL=webpack:///./src/js/view/flip-box.js?");

/***/ })

/******/ });