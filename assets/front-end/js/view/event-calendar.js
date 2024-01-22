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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/event-calendar.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/event-calendar.js":
/*!***************************************!*\
  !*** ./src/js/view/event-calendar.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== \"undefined\" && o[Symbol.iterator] || o[\"@@iterator\"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === \"number\") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError(\"Invalid attempt to iterate non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\"); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it[\"return\"] != null) it[\"return\"](); } finally { if (didErr) throw err; } } }; }\nfunction _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === \"string\") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === \"Object\" && o.constructor) n = o.constructor.name; if (n === \"Map\" || n === \"Set\") return Array.from(o); if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }\nfunction _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }\nvar EventCalendar = function EventCalendar($scope, $) {\n  var Calendar = FullCalendar.Calendar;\n  var element = $(\".eael-event-calendar-cls\", $scope),\n    wrapper = $(\".eael-event-calendar-wrapper\", $scope),\n    CloseButton = $(\".eaelec-modal-close\", $scope).eq(0),\n    ecModal = $(\"#eaelecModal\", $scope),\n    eventAll = element.data(\"events\"),\n    firstDay = element.data(\"first_day\"),\n    calendarID = element.data(\"cal_id\"),\n    locale = element.data(\"locale\"),\n    translate = element.data(\"translate\"),\n    defaultView = element.data(\"defaultview\"),\n    defaultDate = element.data(\"defaultdate\"),\n    multiDaysEventDayCount = typeof element.data(\"multidays_event_day_count\") !== 'undefined' ? element.data(\"multidays_event_day_count\") : 0,\n    eventLimit = element.data(\"event_limit\"),\n    popupDateFormate = element.data(\"popup_date_formate\"),\n    time_format = element.data(\"time_format\") == \"yes\" ? true : false;\n  if (wrapper.hasClass('layout-calendar')) {\n    var refreshPopUpDetailsLink = function refreshPopUpDetailsLink() {\n      var modalFooter = $(\".eaelec-modal-footer\"),\n        modalFooterClass = modalFooter.find('a').attr('class'),\n        modalFooterText = $(\".eael-event-calendar-cls\", $scope).attr('data-detailsButtonText');\n      modalFooter.html('<a class=\"' + modalFooterClass + '\">' + modalFooterText + '</a>');\n    };\n    var calendar = new Calendar($scope[0].querySelector(\".eael-event-calendar-cls\"), {\n      editable: false,\n      selectable: false,\n      firstDay: firstDay,\n      eventTimeFormat: {\n        hour: \"2-digit\",\n        minute: \"2-digit\",\n        hour12: !time_format\n      },\n      nextDayThreshold: \"00:00:00\",\n      headerToolbar: {\n        start: \"prev,next today\",\n        center: \"title\",\n        end: \"timeGridDay,timeGridWeek,dayGridMonth,listMonth\"\n      },\n      events: eventAll,\n      locale: locale,\n      dayMaxEventRows: typeof eventLimit !== \"undefined\" && eventLimit > 0 ? parseInt(eventLimit) : 3,\n      initialView: defaultView,\n      initialDate: defaultDate,\n      eventClassNames: function eventClassNames(info) {},\n      eventContent: function eventContent(info) {},\n      eventDidMount: function eventDidMount(info) {\n        var element = $(info.el),\n          event = info.event;\n        moment.locale(locale);\n        if (multiDaysEventDayCount && event.endStr > event.startStr) {\n          var _$$prevAll;\n          var startDate = typeof event.startStr !== 'undefined' ? new Date(event.startStr) : '';\n          var endDate = typeof event.endStr !== 'undefined' ? new Date(event.endStr) : '';\n          var oneDay = 24 * 60 * 60 * 1000;\n          var totalDays = (endDate - startDate) / oneDay;\n          var currentCellDate = (_$$prevAll = $(element).prevAll('tr.fc-list-day:first')) === null || _$$prevAll === void 0 ? void 0 : _$$prevAll.data('date');\n          currentCellDate = typeof currentCellDate !== 'undefined' ? new Date(currentCellDate) : '';\n          var eventDayCount = startDate && currentCellDate ? Math.ceil((currentCellDate - startDate) / oneDay) + 1 : '';\n          var eventTitle = \"\".concat(event.title, \" (Day \").concat(eventDayCount, \"/\").concat(totalDays, \" )\");\n          element.find(\".fc-list-event-title a\").text(eventTitle);\n        }\n\n        // when event is finished event text are cross\n        if (element.hasClass(\"fc-event-past\")) {\n          element.find(\".fc-event-title\").addClass(\"eael-event-completed\");\n        }\n        translate.today = info.event._context.dateEnv.locale.options.buttonText.today;\n        element.attr(\"style\", \"color:\" + event.textColor + \";background:\" + event.backgroundColor + \";\");\n        element.find(\".fc-list-event-dot\").attr(\"style\", \"border-color:\" + event.backgroundColor + \";\");\n        element.find(\".fc-daygrid-event-dot\").remove();\n        if (event._def.extendedProps.is_redirect === 'yes') {\n          element.attr(\"href\", event.url);\n          if (event._def.extendedProps.external === \"on\") {\n            element.attr(\"target\", \"_blank\");\n          }\n          if (event._def.extendedProps.nofollow === \"on\") {\n            element.attr(\"rel\", \"nofollow\");\n          }\n          if (event._def.extendedProps.custom_attributes !== '') {\n            $.each(event._def.extendedProps.custom_attributes, function (index, item) {\n              element.attr(item.key, item.value);\n            });\n          }\n          if (element.hasClass('fc-list-item')) {\n            element.removeAttr(\"href target rel\");\n            element.removeClass(\"fc-has-url\");\n            element.css('cursor', 'default');\n          }\n        } else {\n          element.attr(\"href\", \"javascript:void(0);\");\n          element.click(function (e) {\n            e.preventDefault();\n            e.stopPropagation();\n            var startDate = event.start,\n              timeFormate = time_format ? \"H:mm\" : \"h:mm A\",\n              endDate = event.end,\n              startSelector = $(\"span.eaelec-event-date-start\"),\n              endSelector = $(\"span.eaelec-event-date-end\"),\n              modalFooterLink = $(\".eaelec-modal-footer a\");\n            if (event.allDay) {\n              var newEnd = moment(endDate).subtract(1, \"days\");\n              endDate = newEnd._d;\n              timeFormate = \" \";\n            }\n            moment.locale(locale);\n            var startYear = moment(startDate).format(\"YYYY\"),\n              endYear = moment(endDate).format(\"YYYY\"),\n              yearDiff = endYear > startYear,\n              startView = \"\",\n              endView = \"\";\n            startSelector.html(\" \");\n            endSelector.html(\" \");\n            ecModal.addClass(\"eael-ec-popup-ready\").removeClass(\"eael-ec-modal-removing\");\n            if (event.allDay && moment(startDate).format(\"MM-DD-YYYY\") === moment(endDate).format(\"MM-DD-YYYY\")) {\n              startView = moment(startDate).format(\"MMM Do\");\n              if (moment(startDate).isSame(Date.now(), \"day\") === true) {\n                startView = translate.today;\n              } else if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n                startView = translate.tomorrow;\n              }\n            } else {\n              if (moment(event.start).isSame(Date.now(), \"day\") === true) {\n                startView = translate.today + \" \" + moment(event.start).format(timeFormate);\n              }\n              if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n                startView = translate.tomorrow + \" \" + moment(event.start).format(timeFormate);\n              }\n              if (moment(startDate).format(\"MM-DD-YYYY\") < moment(new Date()).format(\"MM-DD-YYYY\") || moment(startDate).format(\"MM-DD-YYYY\") > moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n                startView = moment(event.start).format(popupDateFormate + \" \" + timeFormate);\n              }\n              startView = yearDiff ? startYear + \" \" + startView : startView;\n              if (moment(endDate).isSame(Date.now(), \"day\") === true) {\n                if (moment(startDate).isSame(Date.now(), \"day\") !== true) {\n                  endView = translate.today + \" \" + moment(endDate).format(timeFormate);\n                } else {\n                  endView = moment(endDate).format(timeFormate);\n                }\n              }\n              if (moment(startDate).format(\"MM-DD-YYYY\") !== moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\") && moment(endDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n                endView = translate.tomorrow + \" \" + moment(endDate).format(timeFormate);\n              }\n              if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\") && moment(endDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n                endView = moment(endDate).format(timeFormate);\n              }\n              if (moment(endDate).diff(moment(startDate), \"days\") > 0 && endSelector.text().trim().length < 1) {\n                endView = moment(endDate).format(popupDateFormate + \" \" + timeFormate);\n              }\n              if (moment(startDate).format(\"MM-DD-YYYY\") === moment(endDate).format(\"MM-DD-YYYY\")) {\n                endView = moment(endDate).format(timeFormate);\n              }\n              endView = yearDiff ? endYear + \" \" + endView : endView;\n            }\n            if (event.extendedProps.hideEndDate !== undefined && event.extendedProps.hideEndDate === \"yes\") {\n              endSelector.html(\" \");\n            } else {\n              endSelector.html(endView != \"\" ? \"- \" + endView : \"\");\n            }\n            startSelector.html('<i class=\"eicon-calendar\"></i> ' + startView);\n            $(\".eaelec-modal-header h2\").html(event.title);\n            $(\".eaelec-modal-body\").html(event.extendedProps.description);\n            if (event.extendedProps.description.length < 1) {\n              $(\".eaelec-modal-body\").css(\"height\", \"auto\");\n            } else {\n              $(\".eaelec-modal-body\").css(\"height\", \"300px\");\n            }\n            if ($(\".eael-event-calendar-cls\", $scope).data('hidedetailslink') !== 'yes') {\n              modalFooterLink.attr(\"href\", event.url).css(\"display\", \"block\");\n            } else {\n              modalFooterLink.css(\"display\", \"none\");\n            }\n            if (event.extendedProps.external === \"on\") {\n              modalFooterLink.attr(\"target\", \"_blank\");\n            }\n            if (event.extendedProps.nofollow === \"on\") {\n              modalFooterLink.attr(\"rel\", \"nofollow\");\n            }\n            if (event.extendedProps.custom_attributes != '') {\n              $.each(event.extendedProps.custom_attributes, function (index, item) {\n                modalFooterLink.attr(item.key, item.value);\n              });\n            }\n\n            // Popup color\n            $(\".eaelec-modal-header\").css(\"border-left\", \"5px solid \" + event.borderColor);\n\n            // Popup color\n            $(\".eaelec-modal-header\").css(\"border-left\", \"5px solid \" + event.borderColor);\n          });\n        }\n      },\n      eventWillUnmount: function eventWillUnmount(arg) {}\n    });\n    CloseButton.on(\"click\", function (event) {\n      event.stopPropagation();\n      ecModal.addClass(\"eael-ec-modal-removing\").removeClass(\"eael-ec-popup-ready\");\n      refreshPopUpDetailsLink();\n    });\n    $(document).on(\"click\", function (event) {\n      if (event.target.closest(\".eaelec-modal-content\")) return;\n      if (ecModal.hasClass(\"eael-ec-popup-ready\")) {\n        ecModal.addClass(\"eael-ec-modal-removing\").removeClass(\"eael-ec-popup-ready\");\n        refreshPopUpDetailsLink();\n      }\n    });\n    calendar.render();\n    var observer = new IntersectionObserver(function (entries) {\n      var _iterator = _createForOfIteratorHelper(entries),\n        _step;\n      try {\n        for (_iterator.s(); !(_step = _iterator.n()).done;) {\n          var entry = _step.value;\n          window.dispatchEvent(new Event('resize'));\n          setTimeout(function () {\n            window.dispatchEvent(new Event('resize'));\n          }, 200);\n        }\n      } catch (err) {\n        _iterator.e(err);\n      } finally {\n        _iterator.f();\n      }\n    });\n    observer.observe(element[0]);\n    ea.hooks.addAction(\"eventCalendar.reinit\", \"ea\", function () {\n      calendar.today();\n    });\n  } else {\n    var table = $('.eael-event-calendar-table', wrapper),\n      pagination = table.hasClass('ea-ec-table-paginated'),\n      itemPerPage = pagination ? table.data('items-per-page') : 10,\n      sortColumn = $('.eael-ec-event-date', table).index();\n    $(\".eael-event-calendar-table\", wrapper).fancyTable({\n      sortColumn: sortColumn >= 0 ? sortColumn : 0,\n      pagination: pagination,\n      perPage: itemPerPage,\n      globalSearch: true,\n      searchInput: $(\".ea-ec-search-wrap\", wrapper),\n      paginationElement: $(\".eael-event-calendar-pagination\", wrapper)\n    });\n  }\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  if (ea.elementStatusCheck('eaelEventCalendar')) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-event-calendar.default\", EventCalendar);\n});\n\n//# sourceURL=webpack:///./src/js/view/event-calendar.js?");

/***/ })

/******/ });