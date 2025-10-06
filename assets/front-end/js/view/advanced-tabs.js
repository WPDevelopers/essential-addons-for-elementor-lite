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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/advanced-tabs.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/advanced-tabs.js":
/*!**************************************!*\
  !*** ./src/js/view/advanced-tabs.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("eael.hooks.addAction(\"init\", \"ea\", function () {\n  if (eael.elementStatusCheck('eaelAdvancedTabs')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-adv-tabs.default\", function ($scope, $) {\n    var $currentTab = $scope.find('.eael-advance-tabs');\n    var $advanceTab = $scope.find(\".eael-advance-tabs\"),\n      $scrollOnClick = $advanceTab.data(\"scroll-on-click\");\n    $scrollSpeed = $advanceTab.data(\"scroll-speed\");\n    var $customIdOffsetTab = $currentTab.data('custom-id-offset');\n    if (!$currentTab.attr('id')) {\n      return false;\n    }\n    var $currentTabId = '#' + $currentTab.attr('id').toString();\n    var hashTag = window.location.hash.substr(1);\n    hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;\n    window.addEventListener('hashchange', function (e) {\n      hashTag = window.location.hash.substr(1);\n      if (hashTag !== 'undefined' && hashTag) {\n        $('#' + hashTag).trigger('click');\n      }\n    });\n    var hashLink = false;\n    $($currentTabId + ' > .eael-tabs-nav ul li', $scope).each(function (index) {\n      if (hashTag && $(this).attr(\"id\") == hashTag) {\n        $($currentTabId + ' .eael-tabs-nav > ul li', $scope).removeClass(\"active\").addClass(\"inactive\");\n        $(this).removeClass(\"inactive\").addClass(\"active\");\n        hashLink = true;\n      } else {\n        if ($(this).hasClass(\"active-default\") && !hashLink) {\n          $($currentTabId + ' .eael-tabs-nav > ul li', $scope).removeClass(\"active\").addClass(\"inactive\");\n          $(this).removeClass(\"inactive\").addClass('active');\n        } else {\n          if (index == 0) {\n            if ($currentTab.hasClass('eael-tab-auto-active')) {\n              $(this).removeClass(\"inactive\").addClass(\"active\");\n            }\n          }\n        }\n      }\n    });\n    var hashContent = false;\n    $($currentTabId + ' > .eael-tabs-content > div', $scope).each(function (index) {\n      if (hashTag && $(this).attr(\"id\") == hashTag + '-tab') {\n        $($currentTabId + ' > .eael-tabs-content > div', $scope).removeClass(\"active\");\n        var nestedLink = $(this).closest('.eael-tabs-content').closest('.eael-tab-content-item');\n        if (nestedLink.length) {\n          var parentTab = nestedLink.closest('.eael-advance-tabs'),\n            titleID = $(\"#\" + nestedLink.attr(\"id\")),\n            contentID = titleID.data('title-link');\n          parentTab.find(\" > .eael-tabs-nav > ul > li\").removeClass('active');\n          parentTab.find(\" > .eael-tabs-content > div\").removeClass('active');\n          titleID.addClass(\"active\");\n          $(\"#\" + contentID).addClass(\"active\");\n        }\n        $(this).removeClass(\"inactive\").addClass(\"active\");\n        hashContent = true;\n      } else {\n        if ($(this).hasClass(\"active-default\") && !hashContent) {\n          $($currentTabId + ' > .eael-tabs-content > div', $scope).removeClass(\"active\");\n          $(this).removeClass(\"inactive\").addClass(\"active\");\n        } else {\n          if (index == 0) {\n            if ($currentTab.hasClass('eael-tab-auto-active')) {\n              $(this).removeClass(\"inactive\").addClass(\"active\");\n            }\n          }\n        }\n      }\n    });\n    $($currentTabId + ' > .eael-tabs-nav ul li', $scope).on(\"click\", function (e) {\n      e.preventDefault();\n      var currentTabIndex = $(this).index();\n      var tabsContainer = $(this).closest(\".eael-advance-tabs\");\n      var tabsNav = $(tabsContainer).children(\".eael-tabs-nav\").children(\"ul\").children(\"li\");\n      var tabsContent = $(tabsContainer).children(\".eael-tabs-content\").children(\"div\");\n      if ($($currentTabId).hasClass('eael-tab-toggle')) {\n        $(this).toggleClass('active inactive');\n        $(tabsNav).not(this).removeClass(\"active active-default\").addClass(\"inactive\").attr('aria-selected', 'false').attr('aria-expanded', 'false');\n        $(this).attr(\"aria-selected\", 'true').attr(\"aria-expanded\", 'true');\n        $(tabsContent).not(':eq(' + currentTabIndex + ')').removeClass(\"active\").addClass(\"inactive\");\n        $(tabsContent).eq(currentTabIndex).toggleClass('active inactive');\n\n        //Scroll on click\n        if ($scrollOnClick === 'yes') {\n          var $eaelContainerSelect = $(this).attr('aria-controls');\n          var $scrollTarget = $('#' + $eaelContainerSelect);\n          var scrollPosition = $scrollTarget.offset().top;\n\n          // For vertical layout, adjust scroll position to account for content area positioning\n          if (tabsContainer.hasClass('eael-tabs-vertical')) {\n            // In vertical layout, use the content container's position instead of individual tab content\n            var $contentContainer = tabsContainer.find('.eael-tabs-content');\n            if ($contentContainer.length) {\n              scrollPosition = $contentContainer.offset().top;\n            }\n          }\n          $(this).attr('data-scroll', scrollPosition);\n        }\n        if ($scrollOnClick === 'yes' && $(this).hasClass(\"active\")) {\n          var $customIdOffsetVal = $customIdOffsetTab ? parseFloat($customIdOffsetTab) : 0;\n          $('html, body').animate({\n            scrollTop: $(this).data('scroll') - $customIdOffsetVal\n          }, $scrollSpeed);\n        }\n      } else {\n        $(this).parent(\"li\").addClass(\"active\");\n        $(tabsNav).removeClass(\"active active-default\").addClass(\"inactive\").attr('aria-selected', 'false').attr('aria-expanded', 'false');\n        $(this).addClass(\"active\").removeClass(\"inactive\");\n        $(this).attr(\"aria-selected\", 'true').attr(\"aria-expanded\", 'true');\n        $(tabsContent).removeClass(\"active\").addClass(\"inactive\");\n        $(tabsContent).eq(currentTabIndex).addClass(\"active\").removeClass(\"inactive\");\n\n        //Scroll on click\n        if ($scrollOnClick === 'yes') {\n          var _$eaelContainerSelect = $(this).attr('aria-controls');\n          var _$scrollTarget = $('#' + _$eaelContainerSelect);\n          var _scrollPosition = _$scrollTarget.offset().top;\n\n          // For vertical layout, adjust scroll position to account for content area positioning\n          if (tabsContainer.hasClass('eael-tabs-vertical')) {\n            // In vertical layout, use the content container's position instead of individual tab content\n            var _$contentContainer = tabsContainer.find('.eael-tabs-content');\n            if (_$contentContainer.length) {\n              _scrollPosition = _$contentContainer.offset().top;\n            }\n          }\n          $(this).attr('data-scroll', _scrollPosition);\n        }\n        if ($scrollOnClick === 'yes' && $(this).hasClass(\"active\")) {\n          var _$customIdOffsetVal = $customIdOffsetTab ? parseFloat($customIdOffsetTab) : 0;\n          $('html, body').animate({\n            scrollTop: $(this).data('scroll') - _$customIdOffsetVal\n          }, $scrollSpeed);\n        }\n      }\n      eael.hooks.doAction(\"ea-advanced-tabs-triggered\", $(tabsContent).eq(currentTabIndex));\n      $(tabsContent).each(function (index) {\n        $(this).removeClass(\"active-default\");\n      });\n      $($currentTabId + ' > .eael-tabs-nav ul li', $scope).attr('tabindex', '-1');\n      $($currentTabId + ' > .eael-tabs-nav ul li.active', $scope).attr('tabindex', '0');\n      var $filterGallery = tabsContent.eq(currentTabIndex).find(\".eael-filter-gallery-container\"),\n        $postGridGallery = tabsContent.eq(currentTabIndex).find(\".eael-post-grid.eael-post-appender\"),\n        $twitterfeedGallery = tabsContent.eq(currentTabIndex).find(\".eael-twitter-feed-masonry\"),\n        $instaGallery = tabsContent.eq(currentTabIndex).find(\".eael-instafeed\"),\n        $paGallery = tabsContent.eq(currentTabIndex).find(\".premium-gallery-container\"),\n        $evCalendar = $(\".eael-event-calendar-cls\", tabsContent);\n      if ($postGridGallery.length) {\n        $postGridGallery.isotope(\"layout\");\n      }\n      if ($twitterfeedGallery.length) {\n        $twitterfeedGallery.isotope(\"layout\");\n      }\n      if ($filterGallery.length) {\n        $filterGallery.isotope(\"layout\");\n      }\n      if ($instaGallery.length) {\n        $instaGallery.isotope(\"layout\");\n      }\n      if ($paGallery.length) {\n        $paGallery.each(function (index, item) {\n          $(item).isotope(\"layout\");\n        });\n      }\n      if ($evCalendar.length) {\n        eael.hooks.doAction(\"eventCalendar.reinit\");\n      }\n    });\n    $($currentTabId + ' > .eael-tabs-nav ul li.eael-tab-nav-item', $scope).keydown(function (e) {\n      if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {\n        var tabs = $($currentTabId + ' > .eael-tabs-nav ul li.eael-tab-nav-item', $scope);\n        var currentIndex = $($currentTabId + ' > .eael-tabs-nav ul li.eael-tab-nav-item.active', $scope);\n        var index = currentIndex < 0 ? tabs.index(this) : tabs.index(currentIndex);\n        if (e.key === 'ArrowRight') index = (index + 1) % tabs.length;\n        if (e.key === 'ArrowLeft') index = (index - 1 + tabs.length) % tabs.length;\n        $(tabs[index]).focus().click();\n      }\n    });\n    $($currentTabId + ' > .eael-tabs-nav ul li', $scope).attr('tabindex', '-1');\n    $($currentTabId + ' > .eael-tabs-nav ul li.active', $scope).attr('tabindex', '0');\n\n    // If hashTag is not null then scroll to that hashTag smoothly\n    if (typeof hashTag !== 'undefined' && hashTag && !eael.elementStatusCheck('eaelAdvancedTabScroll')) {\n      var $customIdOffsetValTab = $customIdOffsetTab ? parseFloat($customIdOffsetTab) : 0;\n      var scrollPosition = $(\"#\" + hashTag).offset().top;\n\n      // For vertical layout, adjust scroll position to account for content area positioning\n      if ($currentTab.hasClass('eael-tabs-vertical')) {\n        // In vertical layout, use the content container's position instead of individual tab content\n        var $contentContainer = $currentTab.find('.eael-tabs-content');\n        if ($contentContainer.length) {\n          scrollPosition = $contentContainer.offset().top;\n        }\n      }\n      $('html, body').animate({\n        scrollTop: scrollPosition - $customIdOffsetValTab\n      }, 300);\n    }\n  });\n});\n\n//# sourceURL=webpack:///./src/js/view/advanced-tabs.js?");

/***/ })

/******/ });