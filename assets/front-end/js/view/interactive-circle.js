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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/interactive-circle.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/interactive-circle.js":
/*!*******************************************!*\
  !*** ./src/js/view/interactive-circle.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  var interactiveCircle = function interactiveCircle($scope, $) {\n    var $circleWrap = $scope.find(\".eael-circle-wrapper\");\n    var $eventType = \"mouseenter\";\n    var $animation = $circleWrap.data('animation');\n    var $autoplay = $circleWrap.data('autoplay');\n    var $autoplayInterval = parseInt($circleWrap.data('autoplay-interval'));\n    var $autoplayPause = 0;\n    if ($animation != 'eael-interactive-circle-animation-0') {\n      var $circleContent = $scope.find(\".eael-circle-content\");\n      $($circleContent).waypoint(function () {\n        $circleWrap.addClass($animation);\n      }, {\n        offset: \"80%\",\n        triggerOnce: true\n      });\n    }\n    if ($circleWrap.hasClass('eael-interactive-circle-event-click')) {\n      $eventType = \"click\";\n    }\n    var $tabLinks = $circleWrap.find(\".eael-circle-btn\");\n    var $tabContents = $circleWrap.find(\".eael-circle-btn-content\");\n\n    //Support for Keyboard accessibility\n    $scope.on('keyup', '.eael-circle-btn', function (e) {\n      if (e.which === 9 || e.which === 32) {\n        $(this).trigger($eventType);\n      }\n    });\n    $tabLinks.each(function (element) {\n      $(this).on($eventType, handleEvent(element));\n    });\n    if ($autoplay) {\n      setInterval(function () {\n        if ($autoplayPause) {\n          setTimeout(function () {\n            autoplayInteractiveCircle();\n          }, 5000);\n        } else {\n          autoplayInteractiveCircle();\n        }\n      }, $autoplayInterval);\n    }\n    function autoplayInteractiveCircle() {\n      var activeIndex = 0;\n      $tabLinks.each(function (index) {\n        if ($(this).hasClass('active')) {\n          activeIndex = index + 1;\n          activeIndex = activeIndex >= $tabLinks.length ? 0 : activeIndex;\n        }\n      });\n      setTimeout(function () {\n        $($tabLinks[activeIndex]).trigger($eventType);\n      }, 300);\n    }\n    function handleEvent(element) {\n      return function (event) {\n        var $element = $(this);\n        var $activeTab = $(this).hasClass(\"active\");\n        if ($activeTab == false) {\n          $tabLinks.each(function (tabLink) {\n            $(this).removeClass(\"active\");\n          });\n          $element.addClass(\"active\");\n          $tabContents.each(function (tabContent) {\n            $(this).removeClass(\"active\");\n            if ($(this).hasClass($element.attr(\"id\"))) {\n              $(this).addClass(\"active\");\n            }\n          });\n        }\n        $autoplayPause = event.originalEvent ? 1 : 0;\n      };\n    }\n  };\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-interactive-circle.default\", interactiveCircle);\n});\n\n//# sourceURL=webpack:///./src/js/view/interactive-circle.js?");

/***/ })

/******/ });