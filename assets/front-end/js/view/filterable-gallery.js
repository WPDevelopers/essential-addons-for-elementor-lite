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

eval("function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }\n\nfunction _nonIterableRest() { throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); }\n\nfunction _iterableToArrayLimit(arr, i) { if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i[\"return\"] != null) _i[\"return\"](); } finally { if (_d) throw _e; } } return _arr; }\n\nfunction _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }\n\nfunction _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === \"undefined\" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === \"number\") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError(\"Invalid attempt to iterate non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it[\"return\"] != null) it[\"return\"](); } finally { if (didErr) throw err; } } }; }\n\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(o); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\n\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  var filterableGalleryHandler = function filterableGalleryHandler($scope, $) {\n    var filterControls = $scope.find(\".fg-layout-3-filter-controls\").eq(0),\n        filterTrigger = $scope.find(\"#fg-filter-trigger\"),\n        form = $scope.find(\".fg-layout-3-search-box\"),\n        input = $scope.find(\"#fg-search-box-input\"),\n        searchRegex,\n        buttonFilter,\n        timer;\n\n    if (form.length) {\n      form.on(\"submit\", function (e) {\n        e.preventDefault();\n      });\n    }\n\n    filterTrigger.on(\"click\", function () {\n      filterControls.toggleClass(\"open-filters\");\n    });\n    filterTrigger.on(\"blur\", function () {\n      filterControls.removeClass(\"open-filters\");\n    });\n\n    if (!isEditMode) {\n      var $gallery = $(\".eael-filter-gallery-container\", $scope),\n          $settings = $gallery.data(\"settings\"),\n          fg_items = $gallery_items = $gallery.data(\"gallery-items\"),\n          $layout_mode = $settings.grid_style === \"masonry\" ? \"masonry\" : \"fitRows\",\n          $gallery_enabled = $settings.gallery_enabled === \"yes\",\n          $init_show_setting = $gallery.data(\"init-show\");\n      fg_items.splice(0, $init_show_setting); // init isotope\n\n      var gwrap = $(\".eael-filter-gallery-wrapper\");\n      var layoutMode = gwrap.data(\"layout-mode\");\n      var mfpCaption = gwrap.data(\"mfp_caption\");\n      var $isotope_gallery = $gallery.isotope({\n        itemSelector: \".eael-filterable-gallery-item-wrap\",\n        layoutMode: $layout_mode,\n        percentPosition: true,\n        stagger: 30,\n        transitionDuration: $settings.duration + \"ms\",\n        filter: function filter() {\n          var $this = $(this);\n          var $result = searchRegex ? $this.text().match(searchRegex) : true;\n\n          if (buttonFilter === undefined) {\n            if (layoutMode !== \"layout_3\") {\n              buttonFilter = $scope.find(\".eael-filter-gallery-control ul li\").first().data(\"filter\");\n            } else {\n              buttonFilter = $scope.find(\".fg-layout-3-filter-controls li\").first().data(\"filter\");\n            }\n          }\n\n          var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;\n          return $result && buttonResult;\n        }\n      }); // Popup\n\n      $($scope).magnificPopup({\n        delegate: \".eael-magnific-link\",\n        type: \"image\",\n        gallery: {\n          enabled: $gallery_enabled\n        },\n        image: {\n          titleSrc: function titleSrc(item) {\n            if (mfpCaption === \"yes\") {\n              return item.el.parents('.gallery-item-caption-over').find('.fg-item-title').html() || item.el.parents('.gallery-item-caption-wrap').find('.fg-item-title').html() || item.el.parents('.eael-filterable-gallery-item-wrap').find('.fg-item-title').html();\n            }\n          }\n        }\n      }); // filter\n\n      $scope.on(\"click\", \".control\", function () {\n        var $this = $(this);\n        buttonFilter = $(this).attr(\"data-filter\");\n        var $tspan = $scope.find(\"#fg-filter-trigger > span\");\n\n        if ($tspan.length) {\n          $tspan.text($this.text());\n        }\n\n        var LoadMoreShow = $(this).data(\"load-more-status\"),\n            loadMore = $(\".eael-gallery-load-more\", $scope); //hide load more button if selected control have no item to show\n\n        if (LoadMoreShow || fg_items.length < 1) {\n          loadMore.hide();\n        } else {\n          loadMore.show();\n        }\n\n        $this.siblings().removeClass(\"active\");\n        $this.addClass(\"active\");\n        $isotope_gallery.isotope();\n      }); //quick search\n\n      input.on(\"input\", function () {\n        var $this = $(this);\n        clearTimeout(timer);\n        timer = setTimeout(function () {\n          searchRegex = new RegExp($this.val(), \"gi\");\n          $isotope_gallery.isotope();\n        }, 600);\n      }); // layout gal, while images are loading\n\n      $isotope_gallery.imagesLoaded().progress(function () {\n        $isotope_gallery.isotope(\"layout\");\n      }); // layout gal, on click tabs\n\n      $isotope_gallery.on(\"arrangeComplete\", function () {\n        $isotope_gallery.isotope(\"layout\");\n      }); // layout gal, after window loaded\n\n      $(window).on(\"load\", function () {\n        $isotope_gallery.isotope(\"layout\");\n      }); // Load more button\n\n      $scope.on(\"click\", \".eael-gallery-load-more\", function (e) {\n        e.preventDefault();\n        var $this = $(this),\n            $init_show = $(\".eael-filter-gallery-container\", $scope).children(\".eael-filterable-gallery-item-wrap\").length,\n            $total_items = $gallery.data(\"total-gallery-items\"),\n            $images_per_page = $gallery.data(\"images-per-page\"),\n            $nomore_text = $gallery.data(\"nomore-item-text\"),\n            filter_enable = $(\".eael-filter-gallery-control\", $scope).length,\n            $items = [];\n        var filter_name = $(\".eael-filter-gallery-control li.active\", $scope).data('filter');\n\n        if (filterControls.length > 0) {\n          filter_name = $(\".fg-layout-3-filter-controls li.active\", $scope).data('filter');\n        }\n\n        var item_found = 0;\n        var index_list = [];\n\n        var _iterator = _createForOfIteratorHelper(fg_items.entries()),\n            _step;\n\n        try {\n          for (_iterator.s(); !(_step = _iterator.n()).done;) {\n            var _step$value = _slicedToArray(_step.value, 2),\n                index = _step$value[0],\n                item = _step$value[1];\n\n            if (filter_name !== '' && filter_name !== '*' && filter_enable) {\n              var element = $($(item)[0]);\n\n              if (element.is(filter_name)) {\n                ++item_found;\n                $items.push($(item)[0]);\n                index_list.push(index);\n              }\n\n              if (fg_items.length - 1 === index) {\n                $(\".eael-filter-gallery-control li.active\", $scope).data('load-more-status', 1);\n                $this.hide();\n              }\n            } else {\n              ++item_found;\n              $items.push($(item)[0]);\n              index_list.push(index);\n            }\n\n            if (item_found === $images_per_page) {\n              break;\n            }\n          }\n        } catch (err) {\n          _iterator.e(err);\n        } finally {\n          _iterator.f();\n        }\n\n        if (index_list.length > 0) {\n          fg_items = fg_items.filter(function (item, index) {\n            return !index_list.includes(index);\n          });\n        }\n\n        if (fg_items.length < 1) {\n          $this.html('<div class=\"no-more-items-text\">' + $nomore_text + \"</div>\");\n          setTimeout(function () {\n            $this.fadeOut(\"slow\");\n          }, 600);\n        } // append items\n\n\n        $gallery.append($items);\n        $isotope_gallery.isotope(\"appended\", $items);\n        $isotope_gallery.imagesLoaded().progress(function () {\n          $isotope_gallery.isotope(\"layout\");\n        });\n      }); // Fix issue on Safari: hide filter menu\n\n      $(document).on('mouseup', function (e) {\n        if (!filterTrigger.is(e.target) && filterTrigger.has(e.target).length === 0) {\n          filterControls.removeClass(\"open-filters\");\n        }\n      });\n    }\n  };\n\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-filterable-gallery.default\", filterableGalleryHandler);\n});\n\n//# sourceURL=webpack:///./src/js/view/filterable-gallery.js?");

/***/ })

/******/ });