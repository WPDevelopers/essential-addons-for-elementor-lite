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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/count-down.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/count-down.js":
/*!***********************************!*\
  !*** ./src/js/view/count-down.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var CountDown = function CountDown($scope, $) {\n  var $coundDown = $scope.find(\".eael-countdown-wrapper\").eq(0),\n    $countdown_id = $coundDown.data(\"countdown-id\") !== undefined ? $coundDown.data(\"countdown-id\") : \"\",\n    $expire_type = $coundDown.data(\"expire-type\") !== undefined ? $coundDown.data(\"expire-type\") : \"\",\n    $expiry_text = $coundDown.data(\"expiry-text\") !== undefined ? $coundDown.data(\"expiry-text\") : \"\",\n    $expiry_title = $coundDown.data(\"expiry-title\") !== undefined ? $coundDown.data(\"expiry-title\") : \"\",\n    $redirect_url = $coundDown.data(\"redirect-url\") !== undefined ? $coundDown.data(\"redirect-url\") : \"\",\n    $template = $coundDown.data(\"template\") !== undefined ? $coundDown.data(\"template\") : \"\",\n    $countdown_type = $coundDown.data(\"countdown-type\") !== undefined ? $coundDown.data(\"countdown-type\") : \"\",\n    $evergreen_time = $coundDown.data(\"evergreen-time\") !== undefined ? $coundDown.data(\"evergreen-time\") : \"\",\n    $recurring = $coundDown.data(\"evergreen-recurring\") !== undefined ? $coundDown.data(\"evergreen-recurring\") : false,\n    $recurring_stop_time = $coundDown.data(\"evergreen-recurring-stop\") !== undefined ? $coundDown.data(\"evergreen-recurring-stop\") : \"\";\n  jQuery(document).ready(function ($) {\n    \"use strict\";\n\n    var countDown = $(\"#eael-countdown-\" + $countdown_id),\n      eael_countdown_options = {\n        end: function end() {\n          if ($expire_type == \"text\") {\n            countDown.html('<div class=\"eael-countdown-finish-message\"><h4 class=\"expiry-title\">' + $expiry_title + \"</h4>\" + '<div class=\"eael-countdown-finish-text\">' + $expiry_text + \"</div></div>\");\n          } else if ($expire_type === \"url\") {\n            if (isEditMode) {\n              countDown.html(\"Your Page will be redirected to given URL (only on Frontend).\");\n            } else {\n              window.location.href = $redirect_url;\n            }\n          } else if ($expire_type === \"template\") {\n            countDown.html($coundDown.find(\".eael-countdown-expiry-template\").html());\n          } else {\n            //do nothing!\n          }\n        }\n      };\n    if ($countdown_type === 'evergreen') {\n      var $evergreen_interval = \"eael_countdown_evergreen_interval_\".concat($countdown_id),\n        $evergreen_time_key = \"eael_countdown_evergreen_time_\".concat($countdown_id),\n        $interval = localStorage.getItem($evergreen_interval),\n        $date = localStorage.getItem($evergreen_time_key),\n        HOUR_IN_MILISECONDS = 60 * 60 * 1000;\n      if ($date === null || $interval === null || $interval != $evergreen_time) {\n        $date = Date.now() + parseInt($evergreen_time) * 1000;\n        localStorage.setItem($evergreen_interval, $evergreen_time.toString());\n        localStorage.setItem($evergreen_time_key, $date.toString());\n      }\n      if ($recurring !== false) {\n        $recurring_stop_time = new Date($recurring_stop_time);\n        var $recurring_after = parseFloat($recurring) * HOUR_IN_MILISECONDS;\n        if (parseInt($date) + $recurring_after < Date.now()) {\n          $date = Date.now() + parseInt($evergreen_time) * 1000;\n          localStorage.setItem($evergreen_time_key, $date.toString());\n        }\n        if ($recurring_stop_time.getTime() < $date) {\n          $date = $recurring_stop_time.getTime();\n        }\n      }\n      eael_countdown_options.date = new Date(parseInt($date));\n    }\n    countDown.countdown(eael_countdown_options);\n  });\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-countdown.default\", CountDown);\n});\n\n//# sourceURL=webpack:///./src/js/view/count-down.js?");

/***/ })

/******/ });