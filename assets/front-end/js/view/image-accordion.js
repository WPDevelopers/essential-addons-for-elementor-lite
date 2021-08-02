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

eval("var ImageAccordion = function ImageAccordion($scope, $) {\n  var $imageAccordion = $scope.find(\".eael-img-accordion\").eq(0),\n      $id = $imageAccordion.data(\"img-accordion-id\") !== undefined ? $imageAccordion.data(\"img-accordion-id\") : \"\",\n      $type = $imageAccordion.data(\"img-accordion-type\") !== undefined ? $imageAccordion.data(\"img-accordion-type\") : \"\";\n  var $clickCount = 0;\n\n  if (\"on-click\" === $type) {\n    $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").on(\"click\", function (e) {\n      if ($(this).hasClass(\"overlay-active\") == false) {\n        e.preventDefault();\n      }\n\n      $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\", $scope).removeClass(\"overlay-active\");\n\n      if ($clickCount == 0) {\n        if ($(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").hasClass('overlay-active')) {\n          $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").removeClass(\"overlay-active\");\n        }\n\n        $clickCount += 1;\n      }\n\n      $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").css(\"flex\", \"1\");\n      $(this).find(\".overlay\").parent(\".eael-image-accordion-hover\").addClass(\"overlay-active\");\n      $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").find(\".overlay-inner\").removeClass(\"overlay-inner-show\");\n      $(this).find(\".overlay-inner\").addClass(\"overlay-inner-show\");\n      $(this).css(\"flex\", \"3\");\n    });\n  } else {\n    $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").on('hover', function () {\n      if ($(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").hasClass('overlay-active')) {\n        $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover.overlay-active\").css(\"flex\", \"1\");\n        $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover\").removeClass(\"overlay-active\");\n        $(\"#eael-img-accordion-\" + $id + \" .eael-image-accordion-hover .overlay .overlay-inner\").removeClass('overlay-inner-show');\n      }\n    });\n  }\n};\n\nea.hooks.addAction(\"init\", \"ea\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-image-accordion.default\", ImageAccordion);\n});\n\n//# sourceURL=webpack:///./src/js/view/image-accordion.js?");

/***/ })

/******/ });