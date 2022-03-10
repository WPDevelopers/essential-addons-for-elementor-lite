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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/twitter-feed.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/twitter-feed.js":
/*!*************************************!*\
  !*** ./src/js/view/twitter-feed.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var TwitterFeedHandler = function TwitterFeedHandler($scope, $) {\n  if (!isEditMode) {\n    $gutter = $(\".eael-twitter-feed-masonry\", $scope).data(\"gutter\");\n    $settings = {\n      itemSelector: \".eael-twitter-feed-item\",\n      percentPosition: true,\n      masonry: {\n        columnWidth: \".eael-twitter-feed-item\",\n        gutter: $gutter\n      }\n    }; // init isotope\n\n    $twitter_feed_gallery = $(\".eael-twitter-feed-masonry\", $scope).isotope($settings); // layout gal, while images are loading\n\n    $twitter_feed_gallery.imagesLoaded().progress(function () {\n      $twitter_feed_gallery.isotope(\"layout\");\n    });\n  } else {\n    elementor.hooks.addAction(\"panel/open_editor/widget/eael-twitter-feed\", function (panel, model, view) {\n      panel.content.el.onclick = function (event) {\n        if (event.target.dataset.event == \"ea:cache:clear\") {\n          var button = event.target;\n          button.innerHTML = \"Clearing...\";\n          jQuery.ajax({\n            url: localize.ajaxurl,\n            type: \"post\",\n            data: {\n              action: \"eael_clear_widget_cache_data\",\n              security: localize.nonce,\n              ac_name: model.attributes.settings.attributes.eael_twitter_feed_ac_name,\n              hastag: model.attributes.settings.attributes.eael_twitter_feed_hashtag_name,\n              c_key: model.attributes.settings.attributes.eael_twitter_feed_consumer_key,\n              c_secret: model.attributes.settings.attributes.eael_twitter_feed_consumer_secret\n            },\n            success: function success(response) {\n              if (response.success) {\n                button.innerHTML = \"Clear\";\n              } else {\n                button.innerHTML = \"Failed\";\n              }\n            },\n            error: function error() {\n              button.innerHTML = \"Failed\";\n            }\n          });\n        }\n      };\n    });\n  }\n};\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-twitter-feed.default\", TwitterFeedHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/twitter-feed.js?");

/***/ })

/******/ });