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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/advanced-accordion.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/advanced-accordion.js":
/*!*******************************************!*\
  !*** ./src/js/view/advanced-accordion.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("ea.hooks.addAction(\"init\", \"ea\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-adv-accordion.default\", function ($scope, $) {\n    var hashTag = window.location.hash.substr(1);\n    hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;\n    var hashTagExists = false;\n    var $advanceAccordion = $scope.find(\".eael-adv-accordion\"),\n      $accordionHeader = $scope.find(\".eael-accordion-header\"),\n      $accordionType = $advanceAccordion.data(\"accordion-type\"),\n      $accordionSpeed = $advanceAccordion.data(\"toogle-speed\"),\n      $customIdOffset = $advanceAccordion.data(\"custom-id-offset\");\n\n    // Open default actived tab\n    if (hashTag) {\n      $accordionHeader.each(function () {\n        if ($(this).attr(\"id\") == hashTag) {\n          hashTagExists = true;\n          $(this).addClass(\"show active\");\n          $(this).next().slideDown($accordionSpeed);\n        }\n      });\n    }\n    if (hashTagExists === false) {\n      $accordionHeader.each(function () {\n        if ($(this).hasClass(\"active-default\")) {\n          $(this).addClass(\"show active\");\n          $(this).next().slideDown($accordionSpeed);\n        }\n      });\n    }\n\n    // Remove multiple click event for nested accordion\n    $accordionHeader.unbind(\"click\");\n    $accordionHeader.click(function (e) {\n      e.preventDefault();\n      var $this = $(this);\n      if ($accordionType === \"accordion\") {\n        if ($this.hasClass(\"show\")) {\n          $this.removeClass(\"show active\");\n          $this.next().slideUp($accordionSpeed);\n        } else {\n          $this.parent().parent().find(\".eael-accordion-header\").removeClass(\"show active\");\n          $this.parent().parent().find(\".eael-accordion-content\").slideUp($accordionSpeed);\n          $this.toggleClass(\"show active\");\n          $this.next().slideToggle($accordionSpeed);\n        }\n      } else {\n        // For acccordion type 'toggle'\n        if ($this.hasClass(\"show\")) {\n          $this.removeClass(\"show active\");\n          $this.next().slideUp($accordionSpeed);\n        } else {\n          $this.addClass(\"show active\");\n          $this.next().slideDown($accordionSpeed);\n        }\n      }\n      ea.hooks.doAction(\"widgets.reinit\", $this.parent());\n      ea.hooks.doAction(\"ea-advanced-accordion-triggered\", $this.next());\n    });\n\n    // If hashTag is not null then scroll to that hashTag smoothly\n    if (typeof hashTag !== 'undefined' && hashTag && !ea.elementStatusCheck('eaelAdvancedAccordionScroll')) {\n      var $customIdOffsetVal = $customIdOffset ? parseFloat($customIdOffset) : 0;\n      $('html, body').animate({\n        scrollTop: $(\"#\" + hashTag).offset().top - $customIdOffsetVal\n      }, 300);\n    }\n  });\n});\n\n//# sourceURL=webpack:///./src/js/view/advanced-accordion.js?");

/***/ })

/******/ });