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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/image-accordion.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/image-accordion.js":
/*!****************************************!*\
  !*** ./src/js/view/image-accordion.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var ImageAccordion = function ImageAccordion($scope, $) {\n  var $imageAccordion = $scope.find(\".eael-img-accordion\").eq(0),\n    $id = $imageAccordion.data(\"img-accordion-id\") !== undefined ? $imageAccordion.data(\"img-accordion-id\") : \"\",\n    $type = $imageAccordion.data(\"img-accordion-type\") !== undefined ? $imageAccordion.data(\"img-accordion-type\") : \"\";\n  function hoverAction(event, element) {\n    if (element.hasClass(\"overlay-active\") === false) {\n      event.preventDefault();\n    }\n    var imageAccordion = $(\".eael-image-accordion-hover\", $scope);\n    imageAccordion.removeClass(\"overlay-active\");\n    imageAccordion.css(\"flex\", \"1\");\n    element.find(\".overlay\").parent(\".eael-image-accordion-hover\").addClass(\"overlay-active\");\n    imageAccordion.find(\".overlay-inner\").removeClass(\"overlay-inner-show\");\n    element.find(\".overlay-inner\").addClass(\"overlay-inner-show\");\n    element.css(\"flex\", \"3\");\n  }\n  function hoverOutAction(event, element) {\n    if (element.hasClass(\"overlay-active\") === false) {\n      event.preventDefault();\n    }\n    var imageAccordion = $(\".eael-image-accordion-hover\", $scope);\n    imageAccordion.removeClass(\"overlay-active\");\n    imageAccordion.css(\"flex\", \"1\");\n    imageAccordion.find(\".overlay-inner\").removeClass(\"overlay-inner-show\");\n  }\n  if (\"on-click\" === $type) {\n    $(\".eael-image-accordion-hover\", $scope).on(\"click\", function (e) {\n      hoverAction(e, $(this));\n    });\n  } else {\n    $(\".eael-image-accordion-hover\", $scope).hover(function (e) {\n      hoverAction(e, $(this));\n    });\n    $(\".eael-image-accordion-hover\", $scope).mouseleave(function (e) {\n      console.log('leave');\n      hoverOutAction(e, $(this));\n    });\n  }\n};\nea.hooks.addAction(\"init\", \"ea\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-image-accordion.default\", ImageAccordion);\n});\n\n//# sourceURL=webpack:///./src/js/view/image-accordion.js?");

/***/ })

/******/ });