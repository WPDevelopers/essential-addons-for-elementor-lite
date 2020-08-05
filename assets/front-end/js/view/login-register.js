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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/login-register.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/login-register.js":
/*!***************************************!*\
  !*** ./src/js/view/login-register.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var EALoginRegister = function EALoginRegister($scope, $) {\n    var $loginFormWrapper = $scope.find(\"#eael-login-form-wrapper\");\n    var $regFormWrapper = $scope.find(\"#eael-register-form-wrapper\");\n    var $regLinkAction = $scope.find('#eael-lr-reg-toggle');\n    var $loginLinkAction = $scope.find('#eael-lr-login-toggle');\n    var $passField = $loginFormWrapper.find('#eael-user-password');\n\n    if ('form' === $regLinkAction.data('action')) {\n      $regLinkAction.on('click', function (e) {\n        e.preventDefault();\n        $loginFormWrapper.hide();\n        $regFormWrapper.fadeIn();\n      });\n    }\n\n    if ('form' === $loginLinkAction.data('action')) {\n      $loginLinkAction.on('click', function (e) {\n        e.preventDefault();\n        $regFormWrapper.hide();\n        $loginFormWrapper.fadeIn();\n      });\n    } // Password Visibility Toggle\n\n\n    var pass_shown = false;\n    $(document).on('click', '#wp-hide-pw', function (e) {\n      var $icon = $(this).find('span'); // cache\n\n      if (pass_shown) {\n        $passField.attr('type', 'password');\n        $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');\n        pass_shown = false;\n      } else {\n        $passField.attr('type', 'text');\n        $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');\n        pass_shown = true;\n      }\n    });\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-login-register.default\", EALoginRegister);\n});\n\n//# sourceURL=webpack:///./src/js/view/login-register.js?");

/***/ })

/******/ });