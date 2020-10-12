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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/load-more.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/load-more.js":
/*!**********************************!*\
  !*** ./src/js/view/load-more.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function ($) {\n  \"use strict\";\n\n  $(document).on(\"click\", \".eael-load-more-button\", function (e) {\n    e.preventDefault();\n    e.stopPropagation();\n    e.stopImmediatePropagation();\n    var $this = $(this),\n        $text = $(\"span\", $this).html(),\n        $widget_id = $this.data(\"widget\"),\n        $scope = $(\".elementor-element-\" + $widget_id),\n        $class = $this.data(\"class\"),\n        $args = $this.data(\"args\"),\n        $settings = $this.data(\"settings\"),\n        $layout = $this.data(\"layout\"),\n        $template_info = $this.data('template'),\n        $page = parseInt($this.data(\"page\")) + 1;\n\n    if (typeof $widget_id == \"undefined\" || typeof $args == \"undefined\") {\n      return;\n    }\n\n    var obj = {};\n    var $data = {\n      action: \"load_more\",\n      \"class\": $class,\n      args: $args,\n      settings: $settings,\n      page: $page,\n      template_info: $template_info\n    };\n    String($args).split(\"&\").forEach(function (item, index) {\n      var arr = String(item).split(\"=\");\n      obj[arr[0]] = arr[1];\n    });\n\n    if (obj.orderby == \"rand\") {\n      var $printed = $(\".eael-grid-post\");\n\n      if ($printed.length) {\n        var $ids = [];\n        $printed.each(function (index, item) {\n          var $id = $(item).data(\"id\");\n          $ids.push($id);\n        });\n        $data.post__not_in = $ids;\n      }\n    }\n\n    $this.addClass(\"button--loading\");\n    $(\"span\", $this).html(\"Loading...\");\n    $.ajax({\n      url: localize.ajaxurl,\n      type: \"post\",\n      data: $data,\n      success: function success(response) {\n        var $content = $(response);\n\n        if ($content.hasClass(\"no-posts-found\") || $content.length == 0) {\n          $this.remove();\n        } else {\n          if ($data[\"class\"] == \"Essential_Addons_Elementor\\\\Elements\\\\Product_Grid\") {\n            $(\".eael-product-grid .products\", $scope).append($content);\n          } else {\n            $(\".eael-post-appender\", $scope).append($content);\n\n            if ($layout == \"masonry\") {\n              var $isotope = $(\".eael-post-appender\", $scope).isotope();\n              $isotope.isotope(\"appended\", $content).isotope(\"layout\");\n              $isotope.imagesLoaded().progress(function () {\n                $isotope.isotope(\"layout\");\n              });\n            }\n          }\n\n          $this.removeClass(\"button--loading\");\n          $(\"span\", $this).html($text);\n          $this.data(\"page\", $page);\n        }\n      },\n      error: function error(response) {\n        console.log(response);\n      }\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./src/js/view/load-more.js?");

/***/ })

/******/ });