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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/facebook-feed.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/facebook-feed.js":
/*!**************************************!*\
  !*** ./src/js/view/facebook-feed.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var FacebookFeed = function FacebookFeed($scope, $) {\n  if (!isEditMode) {\n    $facebook_gallery = $(\".eael-facebook-feed\", $scope).isotope({\n      itemSelector: \".eael-facebook-feed-item\",\n      percentPosition: true,\n      columnWidth: \".eael-facebook-feed-item\"\n    });\n    $facebook_gallery.imagesLoaded().progress(function () {\n      $facebook_gallery.isotope(\"layout\");\n    });\n  } // ajax load more\n\n\n  $(\".eael-load-more-button\", $scope).on(\"click\", function (e) {\n    e.preventDefault();\n    $this = $(this);\n    $settings = $this.attr(\"data-settings\");\n    $page = $this.attr(\"data-page\");\n    $loadmore_text = $this.attr(\"data-loadmore-text\"); // update load moer button\n\n    $this.addClass(\"button--loading\");\n    $(\"span\", $this).html(\"Loading...\");\n    $.ajax({\n      url: localize.ajaxurl,\n      type: \"post\",\n      data: {\n        action: \"facebook_feed_load_more\",\n        security: localize.nonce,\n        settings: $settings,\n        page: $page\n      },\n      success: function success(response) {\n        $html = $(response.html); // append items\n\n        $facebook_gallery = $(\".eael-facebook-feed\", $scope).isotope();\n        $(\".eael-facebook-feed\", $scope).append($html);\n        $facebook_gallery.isotope(\"appended\", $html);\n        $facebook_gallery.imagesLoaded().progress(function () {\n          $facebook_gallery.isotope(\"layout\");\n        }); // update load more button\n\n        if (response.num_pages > $page) {\n          $this.attr(\"data-page\", parseInt($page) + 1);\n          $this.removeClass(\"button--loading\");\n          $(\"span\", $this).html($loadmore_text);\n        } else {\n          $this.remove();\n        }\n      },\n      error: function error() {}\n    });\n  });\n};\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-facebook-feed.default\", FacebookFeed);\n});\n\n//# sourceURL=webpack:///./src/js/view/facebook-feed.js?");

/***/ })

/******/ });