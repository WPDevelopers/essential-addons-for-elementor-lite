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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/event-calendar.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/event-calendar.js":
/*!***************************************!*\
  !*** ./src/js/edit/event-calendar.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * Event Calendar — editor only.\n *\n * Every colour picker Elementor builds clicks its own colour-format button while\n * it initialises. That synthetic click reaches Elementor's panel-wide\n * background-click handler, which queries the whole panel once per registered\n * listener. It happens once per picker and the panel grows with every picker, so\n * opening a Manual calendar with hundreds of events (three pickers per event)\n * froze the editor for minutes.\n *\n * While such a synthetic click inside a picker is being dispatched, the\n * background-click listeners are set aside and put back right after. Real user\n * clicks are never touched.\n */\neael.hooks.addAction(\"editMode.init\", \"ea\", function () {\n  var editorWindow = window.parent;\n  if (!editorWindow || editorWindow === window || !editorWindow.elementor) {\n    return;\n  }\n\n  // The preview reloads (and this runs again) without the editor reloading.\n  var guard = editorWindow.eaelPickerClickGuard || (editorWindow.eaelPickerClickGuard = {});\n  if (guard.capture) {\n    editorWindow.removeEventListener(\"click\", guard.capture, true);\n    editorWindow.removeEventListener(\"click\", guard.restore, false);\n  }\n  var setAside = null;\n  guard.restore = function () {\n    if (!setAside) {\n      return;\n    }\n    var editor = editorWindow.elementor;\n    // Keep anything registered while the listeners were set aside.\n    editor.backgroundClickListeners = Object.assign(setAside, editor.backgroundClickListeners);\n    setAside = null;\n  };\n  guard.capture = function (event) {\n    var editor = editorWindow.elementor;\n    var target = event.target;\n    if (event.isTrusted || setAside || !editor || !editor.backgroundClickListeners) {\n      return;\n    }\n    if (!target || !target.closest || !target.closest(\".pcr-app\")) {\n      return;\n    }\n    setAside = editor.backgroundClickListeners;\n    editor.backgroundClickListeners = {};\n\n    // Normally restored by the bubbling listener below; this covers a handler\n    // that stops propagation.\n    editorWindow.Promise.resolve().then(guard.restore);\n  };\n  editorWindow.addEventListener(\"click\", guard.capture, true);\n  editorWindow.addEventListener(\"click\", guard.restore, false);\n});\n\n//# sourceURL=webpack:///./src/js/edit/event-calendar.js?");

/***/ })

/******/ });