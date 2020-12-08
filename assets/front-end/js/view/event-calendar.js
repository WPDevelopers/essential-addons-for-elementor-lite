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

eval("var EventCalendar = function EventCalendar($scope, $) {\n  var Calendar = FullCalendar.Calendar;\n  var element = $(\".eael-event-calendar-cls\", $scope),\n      CloseButton = $(\".eaelec-modal-close\", $scope).eq(0),\n      ecModal = $(\"#eaelecModal\", $scope),\n      eventAll = element.data(\"events\"),\n      firstDay = element.data(\"first_day\"),\n      calendarID = element.data(\"cal_id\"),\n      locale = element.data(\"locale\"),\n      translate = element.data(\"translate\"),\n      defaultView = element.data(\"defaultview\"),\n      time_format = element.data(\"time_format\") == 'yes' ? true : false,\n      calendarEl = document.getElementById(\"eael-event-calendar-\" + calendarID);\n  var calendar = new Calendar(calendarEl, {\n    plugins: [\"dayGrid\", \"timeGrid\", \"list\"],\n    editable: false,\n    selectable: false,\n    draggable: false,\n    firstDay: firstDay,\n    eventTimeFormat: {\n      hour: '2-digit',\n      minute: '2-digit',\n      hour12: !time_format\n    },\n    nextDayThreshold: \"00:00:00\",\n    header: {\n      left: \"prev,next today\",\n      center: \"title\",\n      right: \"timeGridDay,timeGridWeek,dayGridMonth,listMonth\"\n    },\n    events: eventAll,\n    selectHelper: true,\n    locale: locale,\n    eventLimit: 3,\n    defaultView: defaultView,\n    eventRender: function eventRender(info) {\n      var element = $(info.el),\n          event = info.event;\n      moment.locale(locale); // when event is finished event text are cross\n\n      if (event.extendedProps.eventHasComplete !== undefined && event.extendedProps.eventHasComplete === 'yes') {\n        element.find('div.fc-content .fc-title').addClass('eael-event-completed');\n        element.find('td.fc-list-item-title').addClass('eael-event-completed');\n      }\n\n      translate.today = info.event._calendar.dateEnv.locale.options.buttonText.today;\n      element.attr(\"href\", \"javascript:void(0);\");\n      element.click(function (e) {\n        e.preventDefault();\n        e.stopPropagation();\n        var startDate = event.start,\n            timeFormate = time_format ? \"H:mm A\" : \"h:mm A\",\n            endDate = event.end,\n            startSelector = $(\"span.eaelec-event-date-start\"),\n            endSelector = $(\"span.eaelec-event-date-end\");\n\n        if (event.allDay === \"yes\") {\n          var newEnd = moment(endDate).subtract(1, \"days\");\n          endDate = newEnd._d;\n          timeFormate = \" \";\n        }\n\n        var startYear = moment(startDate).format(\"YYYY\"),\n            endYear = moment(endDate).format(\"YYYY\"),\n            yearDiff = endYear > startYear,\n            startView = '',\n            endView = '';\n        startSelector.html(\" \");\n        endSelector.html(\" \");\n        ecModal.addClass(\"eael-ec-popup-ready\").removeClass(\"eael-ec-modal-removing\");\n\n        if (event.allDay === \"yes\" && moment(startDate).format(\"MM-DD-YYYY\") === moment(endDate).format(\"MM-DD-YYYY\")) {\n          startView = moment(startDate).format(\"MMM Do\");\n\n          if (moment(startDate).isSame(Date.now(), \"day\") === true) {\n            startView = translate.today;\n          } else if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n            startView = translate.tomorrow;\n          }\n        } else {\n          if (moment(event.start).isSame(Date.now(), \"day\") === true) {\n            startView = translate.today + ' ' + moment(event.start).format(timeFormate);\n          }\n\n          if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n            startView = translate.tomorrow + ' ' + moment(event.start).format(timeFormate);\n          }\n\n          if (moment(startDate).format(\"MM-DD-YYYY\") < moment(new Date()).format(\"MM-DD-YYYY\") || moment(startDate).format(\"MM-DD-YYYY\") > moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n            startView = moment(event.start).format(\"MMM Do \" + timeFormate);\n          }\n\n          startView = yearDiff ? startYear + ' ' + startView : startView;\n\n          if (moment(endDate).isSame(Date.now(), \"day\") === true) {\n            if (moment(startDate).isSame(Date.now(), \"day\") !== true) {\n              endView = translate.today + \" \" + moment(endDate).format(timeFormate);\n            } else {\n              endView = moment(endDate).format(timeFormate);\n            }\n          }\n\n          if (moment(startDate).format(\"MM-DD-YYYY\") !== moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\") && moment(endDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n            endView = translate.tomorrow + \" \" + moment(endDate).format(timeFormate);\n          }\n\n          if (moment(startDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\") && moment(endDate).format(\"MM-DD-YYYY\") === moment(new Date()).add(1, \"days\").format(\"MM-DD-YYYY\")) {\n            endView = moment(endDate).format(timeFormate);\n          }\n\n          if (moment(endDate).diff(moment(startDate), \"days\") > 0 && endSelector.text().trim().length < 1) {\n            endView = moment(endDate).format(\"MMM Do \" + timeFormate);\n          }\n\n          if (moment(startDate).format(\"MM-DD-YYYY\") === moment(endDate).format(\"MM-DD-YYYY\")) {\n            endView = moment(endDate).format(timeFormate);\n          }\n\n          endView = yearDiff ? endYear + ' ' + endView : endView;\n        }\n\n        if (event.extendedProps.hideEndDate !== undefined && event.extendedProps.hideEndDate === 'yes') {\n          endSelector.html(\" \");\n        } else {\n          endSelector.html(endView != '' ? \"- \" + endView : '');\n        }\n\n        startSelector.html('<i class=\"eicon-calendar\"></i> ' + startView);\n        $(\".eaelec-modal-header h2\").html(event.title);\n        $(\".eaelec-modal-body p\").html(event.extendedProps.description);\n\n        if (event.extendedProps.description.length < 1) {\n          $(\".eaelec-modal-body\").css(\"height\", \"auto\");\n        } else {\n          $(\".eaelec-modal-body\").css(\"height\", \"300px\");\n        }\n\n        $(\".eaelec-modal-footer a\").attr(\"href\", event.url);\n\n        if (event.extendedProps.external === \"on\") {\n          $(\".eaelec-modal-footer a\").attr(\"target\", \"_blank\");\n        }\n\n        if (event.extendedProps.nofollow === \"on\") {\n          $(\".eaelec-modal-footer a\").attr(\"rel\", \"nofollow\");\n        }\n\n        if (event.url == \"\") {\n          $(\".eaelec-modal-footer a\").css(\"display\", \"none\");\n        } // Popup color\n\n\n        $(\".eaelec-modal-header\").css(\"border-left\", \"5px solid \" + event.borderColor);\n      });\n    }\n  });\n  CloseButton.on(\"click\", function () {\n    event.stopPropagation();\n    ecModal.addClass(\"eael-ec-modal-removing\").removeClass(\"eael-ec-popup-ready\");\n  });\n  $(document).on('click', function (event) {\n    if (event.target.closest(\".eaelec-modal-content\")) return;\n\n    if (ecModal.hasClass(\"eael-ec-popup-ready\")) {\n      ecModal.addClass(\"eael-ec-modal-removing\").removeClass(\"eael-ec-popup-ready\");\n    }\n  });\n  calendar.render();\n};\n\njQuery(window).on(\"elementor/frontend/init\", function () {\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-event-calendar.default\", EventCalendar);\n});\n\n//# sourceURL=webpack:///./src/js/view/event-calendar.js?");

/***/ })

/******/ });