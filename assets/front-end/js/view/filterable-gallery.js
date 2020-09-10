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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/filterable-gallery.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/filterable-gallery.js":
/*!*******************************************!*\
  !*** ./src/js/view/filterable-gallery.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var filterableGalleryHandler = function filterableGalleryHandler($scope, $) {\n  var filterControls = $scope.find(\".fg-layout-3-filter-controls\").eq(0),\n      filterTrigger = $scope.find(\"#fg-filter-trigger\"),\n      form = $scope.find(\".fg-layout-3-search-box\"),\n      input = $scope.find(\"#fg-search-box-input\"),\n      searchRegex,\n      buttonFilter,\n      timer;\n\n  if (form.length) {\n    form.on(\"submit\", function (e) {\n      e.preventDefault();\n    });\n  }\n\n  filterTrigger.on(\"click\", function () {\n    filterControls.toggleClass(\"open-filters\");\n  }).blur(function () {\n    filterControls.toggleClass(\"open-filters\");\n  });\n\n  if (!isEditMode) {\n    var $gallery = $(\".eael-filter-gallery-container\", $scope),\n        $settings = $gallery.data(\"settings\"),\n        $gallery_items = $gallery.data(\"gallery-items\"),\n        $layout_mode = $settings.grid_style == \"masonry\" ? \"masonry\" : \"fitRows\",\n        $first_show = $(\".eael-filter-gallery-container\", $scope).children(\".eael-filterable-gallery-item-wrap\").length,\n        $gallery_enabled = $settings.gallery_enabled == \"yes\" ? true : false; // init isotope\n\n    var layoutMode = $(\".eael-filter-gallery-wrapper\").data(\"layout-mode\");\n    var mfpCaption = $(\".eael-filter-gallery-wrapper\").data(\"mfp_caption\");\n    var $isotope_gallery = $gallery.isotope({\n      itemSelector: \".eael-filterable-gallery-item-wrap\",\n      layoutMode: $layout_mode,\n      percentPosition: true,\n      stagger: 30,\n      transitionDuration: $settings.duration + \"ms\",\n      filter: function filter() {\n        var $this = $(this);\n        var $result = searchRegex ? $this.text().match(searchRegex) : true;\n\n        if (buttonFilter == undefined) {\n          if (layoutMode != \"layout_3\") {\n            buttonFilter = $scope.find(\".eael-filter-gallery-control ul li\").first().data(\"filter\");\n          } else {\n            buttonFilter = $scope.find(\".fg-layout-3-filter-controls li\").first().data(\"filter\");\n          }\n        }\n\n        var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;\n        return $result && buttonResult;\n      }\n    }); // Popup\n\n    $($scope).magnificPopup({\n      delegate: \".eael-magnific-link\",\n      type: \"image\",\n      gallery: {\n        enabled: $gallery_enabled\n      },\n      image: {\n        titleSrc: function titleSrc(item) {\n          if (mfpCaption == \"yes\") {\n            return item.el.parents('.gallery-item-caption-over').find('.fg-item-title').html() || item.el.parents('.gallery-item-caption-wrap').find('.fg-item-title').html() || item.el.parents('.eael-filterable-gallery-item-wrap').find('.fg-item-title').html();\n          }\n        }\n      }\n    }); // filter\n\n    $scope.on(\"click\", \".control\", function () {\n      var $this = $(this);\n      buttonFilter = $(this).attr(\"data-filter\");\n\n      if ($scope.find(\"#fg-filter-trigger > span\")) {\n        $scope.find(\"#fg-filter-trigger > span\").text($this.text());\n      }\n\n      $this.siblings().removeClass(\"active\");\n      $this.addClass(\"active\");\n      $isotope_gallery.isotope();\n    }); //quick search\n\n    input.on(\"input\", function () {\n      var $this = $(this);\n      clearTimeout(timer);\n      timer = setTimeout(function () {\n        searchRegex = new RegExp($this.val(), \"gi\");\n        $isotope_gallery.isotope();\n      }, 600);\n    }); // layout gal, while images are loading\n\n    $isotope_gallery.imagesLoaded().progress(function () {\n      $isotope_gallery.isotope(\"layout\");\n    }); // layout gal, on click tabs\n\n    $isotope_gallery.on(\"arrangeComplete\", function () {\n      $isotope_gallery.isotope(\"layout\");\n    }); // layout gal, after window loaded\n\n    $(window).on(\"load\", function () {\n      $isotope_gallery.isotope(\"layout\");\n    }); // Load more button\n\n    $scope.on(\"click\", \".eael-gallery-load-more\", function (e) {\n      e.preventDefault();\n      var $this = $(this),\n          $init_show = $(\".eael-filter-gallery-container\", $scope).children(\".eael-filterable-gallery-item-wrap\").length,\n          $total_items = $gallery.data(\"total-gallery-items\"),\n          $images_per_page = $gallery.data(\"images-per-page\"),\n          $nomore_text = $gallery.data(\"nomore-item-text\"),\n          filter_enable = $(\".eael-filter-gallery-control\", $scope).length,\n          $items = [];\n      var filter_name = $(\".eael-filter-gallery-control li.active\").data('filter');\n\n      if (filterControls.length > 0) {\n        filter_name = $(\".fg-layout-3-filter-controls li.active\").data('filter');\n      }\n\n      if ($init_show == $total_items) {\n        $this.html('<div class=\"no-more-items-text\">' + $nomore_text + \"</div>\");\n        setTimeout(function () {\n          $this.fadeOut(\"slow\");\n        }, 600);\n      } // new items html\n\n\n      var i = $init_show;\n      var item_found = 0;\n\n      while (i < $init_show + $images_per_page) {\n        if (filter_name != '' && filter_name != '*' && filter_enable) {\n          for (var j = i; j < $gallery_items.length; j++) {\n            var element = $($($gallery_items[j])[0]);\n\n            if (element.is(filter_name)) {\n              ++item_found;\n              $items.push($($gallery_items[j])[0]);\n              delete $gallery_items[j];\n\n              if (item_found === $images_per_page) {\n                break;\n              }\n            }\n          } //break when cross $images_per_page or no image found\n\n\n          break;\n        } else {\n          $items.push($($gallery_items[i])[0]);\n          delete $gallery_items[i];\n        }\n\n        i++;\n      } // append items\n\n\n      $gallery.append($items);\n      $isotope_gallery.isotope(\"appended\", $items);\n      $isotope_gallery.imagesLoaded().progress(function () {\n        $isotope_gallery.isotope(\"layout\");\n      });\n    });\n  }\n};\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-filterable-gallery.default\", filterableGalleryHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/filterable-gallery.js?");

/***/ })

/******/ });