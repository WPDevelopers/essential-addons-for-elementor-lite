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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/fancy-text.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/fancy-text.js":
/*!***********************************!*\
  !*** ./src/js/view/fancy-text.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var FancyText = function FancyText($scope, $) {\n  var $fancyText = $scope.find(\".eael-fancy-text-container\").eq(0),\n    $id = $fancyText.data(\"fancy-text-id\") !== undefined ? $fancyText.data(\"fancy-text-id\") : \"\",\n    $fancy_text = $fancyText.data(\"fancy-text\") !== undefined ? $fancyText.data(\"fancy-text\") : \"\",\n    $transition_type = $fancyText.data(\"fancy-text-transition-type\") !== undefined ? $fancyText.data(\"fancy-text-transition-type\") : \"\",\n    $fancy_text_speed = $fancyText.data(\"fancy-text-speed\") !== undefined ? $fancyText.data(\"fancy-text-speed\") : \"\",\n    $fancy_text_delay = $fancyText.data(\"fancy-text-delay\") !== undefined ? $fancyText.data(\"fancy-text-delay\") : \"\",\n    $fancy_text_cursor = $fancyText.data(\"fancy-text-cursor\") === \"yes\",\n    $fancy_text_loop = $fancyText.data(\"fancy-text-loop\") !== undefined ? $fancyText.data(\"fancy-text-loop\") === \"yes\" : false;\n  $fancy_text = DOMPurify.sanitize($fancy_text).split(\"|\");\n  if ($transition_type === \"typing\") {\n    new Typed(\"#eael-fancy-text-\" + $id, {\n      strings: $fancy_text,\n      typeSpeed: $fancy_text_speed,\n      backSpeed: 0,\n      startDelay: 300,\n      backDelay: $fancy_text_delay,\n      showCursor: $fancy_text_cursor,\n      loop: $fancy_text_loop\n    });\n  }\n  if ($transition_type !== \"typing\") {\n    $(\"#eael-fancy-text-\" + $id).Morphext({\n      animation: $transition_type,\n      separator: \", \",\n      speed: $fancy_text_delay,\n      complete: function complete() {\n        if (!$fancy_text_loop && $(this)[0].index + 1 === $(this)[0].phrases.length) {\n          $(this)[0].stop();\n        }\n      }\n    });\n  }\n  $(document).ready(function () {\n    setTimeout(function () {\n      $(\".eael-fancy-text-strings\", $scope).css(\"display\", \"inline-block\");\n    }, 500);\n  });\n  if (isEditMode) {\n    setTimeout(function () {\n      $(\".eael-fancy-text-strings\", $scope).css(\"display\", \"inline-block\");\n    }, 800);\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (eael.elementStatusCheck('eaelFancyTextLoad')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-fancy-text.default\", FancyText);\n});\n\n//# sourceURL=webpack:///./src/js/view/fancy-text.js?");

/***/ })

/******/ });