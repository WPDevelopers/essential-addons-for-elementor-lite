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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayLikeToArray; });\nfunction _arrayLikeToArray(arr, len) {\n  if (len == null || len > arr.length) len = arr.length;\n\n  for (var i = 0, arr2 = new Array(len); i < len; i++) {\n    arr2[i] = arr[i];\n  }\n\n  return arr2;\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _arrayWithoutHoles; });\n/* harmony import */ var _arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray */ \"./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _arrayWithoutHoles(arr) {\n  if (Array.isArray(arr)) return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(arr);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _iterableToArray; });\nfunction _iterableToArray(iter) {\n  if (typeof Symbol !== \"undefined\" && Symbol.iterator in Object(iter)) return Array.from(iter);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/iterableToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _nonIterableSpread; });\nfunction _nonIterableSpread() {\n  throw new TypeError(\"Invalid attempt to spread non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _toConsumableArray; });\n/* harmony import */ var _arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles */ \"./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js\");\n/* harmony import */ var _iterableToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray */ \"./node_modules/@babel/runtime/helpers/esm/iterableToArray.js\");\n/* harmony import */ var _unsupportedIterableToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray */ \"./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js\");\n/* harmony import */ var _nonIterableSpread__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread */ \"./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js\");\n\n\n\n\nfunction _toConsumableArray(arr) {\n  return Object(_arrayWithoutHoles__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(arr) || Object(_iterableToArray__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(arr) || Object(_unsupportedIterableToArray__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(arr) || Object(_nonIterableSpread__WEBPACK_IMPORTED_MODULE_3__[\"default\"])();\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return _unsupportedIterableToArray; });\n/* harmony import */ var _arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray */ \"./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js\");\n\nfunction _unsupportedIterableToArray(o, minLen) {\n  if (!o) return;\n  if (typeof o === \"string\") return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(o, minLen);\n  var n = Object.prototype.toString.call(o).slice(8, -1);\n  if (n === \"Object\" && o.constructor) n = o.constructor.name;\n  if (n === \"Map\" || n === \"Set\") return Array.from(n);\n  if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return Object(_arrayLikeToArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(o, minLen);\n}\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createAddHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createAddHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ */ \"./node_modules/@wordpress/hooks/build-module/index.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Returns a function which, when invoked, will add a hook.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that adds a new hook.\n */\n\nfunction createAddHook(hooks) {\n  /**\n   * Adds the hook to the appropriate hooks container.\n   *\n   * @param {string}   hookName  Name of hook to add\n   * @param {string}   namespace The unique namespace identifying the callback in the form `vendor/plugin/function`.\n   * @param {Function} callback  Function to call when the hook is run\n   * @param {?number}  priority  Priority of this hook (default=10)\n   */\n  return function addHook(hookName, namespace, callback) {\n    var priority = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 10;\n\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    }\n\n    if ('function' !== typeof callback) {\n      // eslint-disable-next-line no-console\n      console.error('The hook callback must be a function.');\n      return;\n    } // Validate numeric priority\n\n\n    if ('number' !== typeof priority) {\n      // eslint-disable-next-line no-console\n      console.error('If specified, the hook priority must be a number.');\n      return;\n    }\n\n    var handler = {\n      callback: callback,\n      priority: priority,\n      namespace: namespace\n    };\n\n    if (hooks[hookName]) {\n      // Find the correct insert index of the new hook.\n      var handlers = hooks[hookName].handlers;\n      var i;\n\n      for (i = handlers.length; i > 0; i--) {\n        if (priority >= handlers[i - 1].priority) {\n          break;\n        }\n      }\n\n      if (i === handlers.length) {\n        // If append, operate via direct assignment.\n        handlers[i] = handler;\n      } else {\n        // Otherwise, insert before index via splice.\n        handlers.splice(i, 0, handler);\n      } // We may also be currently executing this hook.  If the callback\n      // we're adding would come after the current callback, there's no\n      // problem; otherwise we need to increase the execution index of\n      // any other runs by 1 to account for the added element.\n\n\n      (hooks.__current || []).forEach(function (hookInfo) {\n        if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n          hookInfo.currentIndex++;\n        }\n      });\n    } else {\n      // This is the first hook of its type.\n      hooks[hookName] = {\n        handlers: [handler],\n        runs: 0\n      };\n    }\n\n    if (hookName !== 'hookAdded') {\n      Object(___WEBPACK_IMPORTED_MODULE_2__[\"doAction\"])('hookAdded', hookName, namespace, callback, priority);\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createAddHook);\n//# sourceMappingURL=createAddHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createAddHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createCurrentHook.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createCurrentHook.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return the name of the\n * currently running hook, or `null` if no hook of the given type is currently\n * running.\n *\n * @param  {Object}   hooks          Stored hooks, keyed by hook name.\n *\n * @return {Function}                Function that returns the current hook.\n */\nfunction createCurrentHook(hooks) {\n  /**\n   * Returns the name of the currently running hook, or `null` if no hook of\n   * the given type is currently running.\n   *\n   * @return {?string}             The name of the currently running hook, or\n   *                               `null` if no hook is currently running.\n   */\n  return function currentHook() {\n    if (!hooks.__current || !hooks.__current.length) {\n      return null;\n    }\n\n    return hooks.__current[hooks.__current.length - 1].name;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createCurrentHook);\n//# sourceMappingURL=createCurrentHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createCurrentHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDidHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDidHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/**\n * Internal dependencies\n */\n\n/**\n * Returns a function which, when invoked, will return the number of times a\n * hook has been called.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns a hook's call count.\n */\n\nfunction createDidHook(hooks) {\n  /**\n   * Returns the number of times an action has been fired.\n   *\n   * @param  {string} hookName The hook name to check.\n   *\n   * @return {number}          The number of times the hook has run.\n   */\n  return function didHook(hookName) {\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hookName)) {\n      return;\n    }\n\n    return hooks[hookName] && hooks[hookName].runs ? hooks[hookName].runs : 0;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDidHook);\n//# sourceMappingURL=createDidHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createDidHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createDoingHook.js":
/*!***********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createDoingHook.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return whether a hook is\n * currently being executed.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns whether a hook is currently\n *                          being executed.\n */\nfunction createDoingHook(hooks) {\n  /**\n   * Returns whether a hook is currently being executed.\n   *\n   * @param  {?string} hookName The name of the hook to check for.  If\n   *                            omitted, will check for any hook being executed.\n   *\n   * @return {boolean}             Whether the hook is being executed.\n   */\n  return function doingHook(hookName) {\n    // If the hookName was not passed, check for any current hook.\n    if ('undefined' === typeof hookName) {\n      return 'undefined' !== typeof hooks.__current[0];\n    } // Return the __current hook.\n\n\n    return hooks.__current[0] ? hookName === hooks.__current[0].name : false;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createDoingHook);\n//# sourceMappingURL=createDoingHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createDoingHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHasHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHasHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Returns a function which, when invoked, will return whether any handlers are\n * attached to a particular hook.\n *\n * @param  {Object}   hooks Stored hooks, keyed by hook name.\n *\n * @return {Function}       Function that returns whether any handlers are\n *                          attached to a particular hook and optional namespace.\n */\nfunction createHasHook(hooks) {\n  /**\n   * Returns whether any handlers are attached for the given hookName and optional namespace.\n   *\n   * @param {string}  hookName  The name of the hook to check for.\n   * @param {?string} namespace Optional. The unique namespace identifying the callback\n   *                                      in the form `vendor/plugin/function`.\n   *\n   * @return {boolean} Whether there are handlers that are attached to the given hook.\n   */\n  return function hasHook(hookName, namespace) {\n    // Use the namespace if provided.\n    if ('undefined' !== typeof namespace) {\n      return hookName in hooks && hooks[hookName].handlers.some(function (hook) {\n        return hook.namespace === namespace;\n      });\n    }\n\n    return hookName in hooks;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHasHook);\n//# sourceMappingURL=createHasHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createHasHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createHooks.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createHooks.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _createAddHook__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createAddHook */ \"./node_modules/@wordpress/hooks/build-module/createAddHook.js\");\n/* harmony import */ var _createRemoveHook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./createRemoveHook */ \"./node_modules/@wordpress/hooks/build-module/createRemoveHook.js\");\n/* harmony import */ var _createHasHook__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./createHasHook */ \"./node_modules/@wordpress/hooks/build-module/createHasHook.js\");\n/* harmony import */ var _createRunHook__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./createRunHook */ \"./node_modules/@wordpress/hooks/build-module/createRunHook.js\");\n/* harmony import */ var _createCurrentHook__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./createCurrentHook */ \"./node_modules/@wordpress/hooks/build-module/createCurrentHook.js\");\n/* harmony import */ var _createDoingHook__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./createDoingHook */ \"./node_modules/@wordpress/hooks/build-module/createDoingHook.js\");\n/* harmony import */ var _createDidHook__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./createDidHook */ \"./node_modules/@wordpress/hooks/build-module/createDidHook.js\");\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\n/**\n * Returns an instance of the hooks object.\n *\n * @return {Object} Object that contains all hooks.\n */\n\nfunction createHooks() {\n  var actions = Object.create(null);\n  var filters = Object.create(null);\n  actions.__current = [];\n  filters.__current = [];\n  return {\n    addAction: Object(_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(actions),\n    addFilter: Object(_createAddHook__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(filters),\n    removeAction: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(actions),\n    removeFilter: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(filters),\n    hasAction: Object(_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(actions),\n    hasFilter: Object(_createHasHook__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(filters),\n    removeAllActions: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(actions, true),\n    removeAllFilters: Object(_createRemoveHook__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(filters, true),\n    doAction: Object(_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(actions),\n    applyFilters: Object(_createRunHook__WEBPACK_IMPORTED_MODULE_3__[\"default\"])(filters, true),\n    currentAction: Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(actions),\n    currentFilter: Object(_createCurrentHook__WEBPACK_IMPORTED_MODULE_4__[\"default\"])(filters),\n    doingAction: Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(actions),\n    doingFilter: Object(_createDoingHook__WEBPACK_IMPORTED_MODULE_5__[\"default\"])(filters),\n    didAction: Object(_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(actions),\n    didFilter: Object(_createDidHook__WEBPACK_IMPORTED_MODULE_6__[\"default\"])(filters),\n    actions: actions,\n    filters: filters\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createHooks);\n//# sourceMappingURL=createHooks.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createHooks.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRemoveHook.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRemoveHook.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validateNamespace.js */ \"./node_modules/@wordpress/hooks/build-module/validateNamespace.js\");\n/* harmony import */ var _validateHookName_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validateHookName.js */ \"./node_modules/@wordpress/hooks/build-module/validateHookName.js\");\n/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ */ \"./node_modules/@wordpress/hooks/build-module/index.js\");\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Returns a function which, when invoked, will remove a specified hook or all\n * hooks by the given name.\n *\n * @param  {Object}   hooks      Stored hooks, keyed by hook name.\n * @param  {boolean}     removeAll  Whether to remove all callbacks for a hookName, without regard to namespace. Used to create `removeAll*` functions.\n *\n * @return {Function}            Function that removes hooks.\n */\n\nfunction createRemoveHook(hooks, removeAll) {\n  /**\n   * Removes the specified callback (or all callbacks) from the hook with a\n   * given hookName and namespace.\n   *\n   * @param {string}    hookName  The name of the hook to modify.\n   * @param {string}    namespace The unique namespace identifying the callback in the form `vendor/plugin/function`.\n   *\n   * @return {number}             The number of callbacks removed.\n   */\n  return function removeHook(hookName, namespace) {\n    if (!Object(_validateHookName_js__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(hookName)) {\n      return;\n    }\n\n    if (!removeAll && !Object(_validateNamespace_js__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(namespace)) {\n      return;\n    } // Bail if no hooks exist by this name\n\n\n    if (!hooks[hookName]) {\n      return 0;\n    }\n\n    var handlersRemoved = 0;\n\n    if (removeAll) {\n      handlersRemoved = hooks[hookName].handlers.length;\n      hooks[hookName] = {\n        runs: hooks[hookName].runs,\n        handlers: []\n      };\n    } else {\n      // Try to find the specified callback to remove.\n      var handlers = hooks[hookName].handlers;\n\n      var _loop = function _loop(i) {\n        if (handlers[i].namespace === namespace) {\n          handlers.splice(i, 1);\n          handlersRemoved++; // This callback may also be part of a hook that is\n          // currently executing.  If the callback we're removing\n          // comes after the current callback, there's no problem;\n          // otherwise we need to decrease the execution index of any\n          // other runs by 1 to account for the removed element.\n\n          (hooks.__current || []).forEach(function (hookInfo) {\n            if (hookInfo.name === hookName && hookInfo.currentIndex >= i) {\n              hookInfo.currentIndex--;\n            }\n          });\n        }\n      };\n\n      for (var i = handlers.length - 1; i >= 0; i--) {\n        _loop(i);\n      }\n    }\n\n    if (hookName !== 'hookRemoved') {\n      Object(___WEBPACK_IMPORTED_MODULE_2__[\"doAction\"])('hookRemoved', hookName, namespace);\n    }\n\n    return handlersRemoved;\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRemoveHook);\n//# sourceMappingURL=createRemoveHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createRemoveHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/createRunHook.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/createRunHook.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js\");\n\n\n/**\n * Returns a function which, when invoked, will execute all callbacks\n * registered to a hook of the specified type, optionally returning the final\n * value of the call chain.\n *\n * @param  {Object}   hooks          Stored hooks, keyed by hook name.\n * @param  {?boolean}    returnFirstArg Whether each hook callback is expected to\n *                                   return its first argument.\n *\n * @return {Function}                Function that runs hook callbacks.\n */\nfunction createRunHook(hooks, returnFirstArg) {\n  /**\n   * Runs all callbacks for the specified hook.\n   *\n   * @param  {string} hookName The name of the hook to run.\n   * @param  {...*}   args     Arguments to pass to the hook callbacks.\n   *\n   * @return {*}               Return value of runner, if applicable.\n   */\n  return function runHooks(hookName) {\n    if (!hooks[hookName]) {\n      hooks[hookName] = {\n        handlers: [],\n        runs: 0\n      };\n    }\n\n    hooks[hookName].runs++;\n    var handlers = hooks[hookName].handlers; // The following code is stripped from production builds.\n\n    if (true) {\n      // Handle any 'all' hooks registered.\n      if ('hookAdded' !== hookName && hooks.all) {\n        handlers.push.apply(handlers, Object(_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(hooks.all.handlers));\n      }\n    }\n\n    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {\n      args[_key - 1] = arguments[_key];\n    }\n\n    if (!handlers || !handlers.length) {\n      return returnFirstArg ? args[0] : undefined;\n    }\n\n    var hookInfo = {\n      name: hookName,\n      currentIndex: 0\n    };\n\n    hooks.__current.push(hookInfo);\n\n    while (hookInfo.currentIndex < handlers.length) {\n      var handler = handlers[hookInfo.currentIndex];\n      var result = handler.callback.apply(null, args);\n\n      if (returnFirstArg) {\n        args[0] = result;\n      }\n\n      hookInfo.currentIndex++;\n    }\n\n    hooks.__current.pop();\n\n    if (returnFirstArg) {\n      return args[0];\n    }\n  };\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (createRunHook);\n//# sourceMappingURL=createRunHook.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/createRunHook.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/index.js":
/*!*************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/index.js ***!
  \*************************************************************/
/*! exports provided: createHooks, addAction, addFilter, removeAction, removeFilter, hasAction, hasFilter, removeAllActions, removeAllFilters, doAction, applyFilters, currentAction, currentFilter, doingAction, doingFilter, didAction, didFilter, actions, filters */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addAction\", function() { return addAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"addFilter\", function() { return addFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAction\", function() { return removeAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeFilter\", function() { return removeFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasAction\", function() { return hasAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hasFilter\", function() { return hasFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllActions\", function() { return removeAllActions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"removeAllFilters\", function() { return removeAllFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doAction\", function() { return doAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"applyFilters\", function() { return applyFilters; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentAction\", function() { return currentAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"currentFilter\", function() { return currentFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingAction\", function() { return doingAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"doingFilter\", function() { return doingFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didAction\", function() { return didAction; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"didFilter\", function() { return didFilter; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"actions\", function() { return actions; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"filters\", function() { return filters; });\n/* harmony import */ var _createHooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./createHooks */ \"./node_modules/@wordpress/hooks/build-module/createHooks.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"createHooks\", function() { return _createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/**\n * Internal dependencies\n */\n\n\nvar _createHooks = Object(_createHooks__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(),\n    addAction = _createHooks.addAction,\n    addFilter = _createHooks.addFilter,\n    removeAction = _createHooks.removeAction,\n    removeFilter = _createHooks.removeFilter,\n    hasAction = _createHooks.hasAction,\n    hasFilter = _createHooks.hasFilter,\n    removeAllActions = _createHooks.removeAllActions,\n    removeAllFilters = _createHooks.removeAllFilters,\n    doAction = _createHooks.doAction,\n    applyFilters = _createHooks.applyFilters,\n    currentAction = _createHooks.currentAction,\n    currentFilter = _createHooks.currentFilter,\n    doingAction = _createHooks.doingAction,\n    doingFilter = _createHooks.doingFilter,\n    didAction = _createHooks.didAction,\n    didFilter = _createHooks.didFilter,\n    actions = _createHooks.actions,\n    filters = _createHooks.filters;\n\n\n//# sourceMappingURL=index.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/index.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateHookName.js":
/*!************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateHookName.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a hookName string.\n *\n * @param  {string} hookName The hook name to validate. Should be a non empty string containing\n *                           only numbers, letters, dashes, periods and underscores. Also,\n *                           the hook name cannot begin with `__`.\n *\n * @return {boolean}            Whether the hook name is valid.\n */\nfunction validateHookName(hookName) {\n  if ('string' !== typeof hookName || '' === hookName) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name must be a non-empty string.');\n    return false;\n  }\n\n  if (/^__/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name cannot begin with `__`.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(hookName)) {\n    // eslint-disable-next-line no-console\n    console.error('The hook name can only contain numbers, letters, dashes, periods and underscores.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateHookName);\n//# sourceMappingURL=validateHookName.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/validateHookName.js?");

/***/ }),

/***/ "./node_modules/@wordpress/hooks/build-module/validateNamespace.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@wordpress/hooks/build-module/validateNamespace.js ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Validate a namespace string.\n *\n * @param  {string} namespace The namespace to validate - should take the form\n *                            `vendor/plugin/function`.\n *\n * @return {boolean}             Whether the namespace is valid.\n */\nfunction validateNamespace(namespace) {\n  if ('string' !== typeof namespace || '' === namespace) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace must be a non-empty string.');\n    return false;\n  }\n\n  if (!/^[a-zA-Z][a-zA-Z0-9_.\\-\\/]*$/.test(namespace)) {\n    // eslint-disable-next-line no-console\n    console.error('The namespace can only contain numbers, letters, dashes, periods, underscores and slashes.');\n    return false;\n  }\n\n  return true;\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (validateNamespace);\n//# sourceMappingURL=validateNamespace.js.map\n\n//# sourceURL=webpack:///./node_modules/@wordpress/hooks/build-module/validateNamespace.js?");

/***/ }),

/***/ "./src/advancedDataTable.js":
/*!**********************************!*\
  !*** ./src/advancedDataTable.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }\n\nfunction _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }\n\nvar advancedDataTable = /*#__PURE__*/function () {\n  function advancedDataTable(isEditMode) {\n    _classCallCheck(this, advancedDataTable);\n\n    // class props\n    this.timeout = null;\n    this.activeCell = null;\n    this.dragStartX = null;\n    this.dragStartWidth = null;\n    this.dragEl = null;\n    this.dragging = false; // hooks\n\n    elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-advanced-data-table.default\", this.initFrontend.bind(this)); // ea.hooks.addAction(\"ea.advDataTable.init\", \"ea\", this.hello.bind(this));\n    // ea.hooks.doAction(\"ea.advDataTable.init\");\n  }\n\n  _createClass(advancedDataTable, [{\n    key: \"initFrontend\",\n    value: function initFrontend($scope, $) {\n      var table = $scope.context.querySelector(\".ea-advanced-data-table\");\n      var search = $scope.context.querySelector(\".ea-advanced-data-table-search\");\n      var pagination = $scope.context.querySelector(\".ea-advanced-data-table-pagination\");\n      var classCollection = {};\n\n      if (ea.isEditMode) {\n        table.querySelectorAll(\"th, td\").forEach(function (el) {\n          if (el.innerHTML.indexOf('<div class=\"inline-edit\">') !== 0) {\n            el.innerHTML = '<div class=\"inline-edit\">' + el.innerHTML + \"</div>\";\n          }\n        });\n      } else {\n        // search\n        this.initTableSearch(table, search, pagination); // sort\n\n        this.initTableSort(table, pagination, classCollection); // paginated table\n\n        this.initTablePagination(table, pagination, classCollection); // woocommerce\n\n        this.initWooFeatures(table);\n      }\n    }\n  }, {\n    key: \"initTableSearch\",\n    value: function initTableSearch(table, search, pagination) {\n      if (search) {\n        search.addEventListener(\"input\", function (e) {\n          var input = this.value.toLowerCase();\n          var hasSort = table.classList.contains(\"ea-advanced-data-table-sortable\");\n          var offset = table.rows[0].parentNode.tagName.toLowerCase() == \"thead\" ? 1 : 0;\n\n          if (table.rows.length > 1) {\n            if (input.length > 0) {\n              if (hasSort) {\n                table.classList.add(\"ea-advanced-data-table-unsortable\");\n              }\n\n              if (pagination && pagination.innerHTML.length > 0) {\n                pagination.style.display = \"none\";\n              }\n\n              for (var i = offset; i < table.rows.length; i++) {\n                var matchFound = false;\n\n                if (table.rows[i].cells.length > 0) {\n                  for (var j = 0; j < table.rows[i].cells.length; j++) {\n                    if (table.rows[i].cells[j].textContent.toLowerCase().indexOf(input) > -1) {\n                      matchFound = true;\n                      break;\n                    }\n                  }\n                }\n\n                if (matchFound) {\n                  table.rows[i].style.display = \"table-row\";\n                } else {\n                  table.rows[i].style.display = \"none\";\n                }\n              }\n            } else {\n              if (hasSort) {\n                table.classList.remove(\"ea-advanced-data-table-unsortable\");\n              }\n\n              if (pagination && pagination.innerHTML.length > 0) {\n                pagination.style.display = \"\";\n                var currentPage = pagination.querySelector(\".ea-advanced-data-table-pagination-current\").dataset.page;\n                var startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;\n                var endIndex = currentPage * table.dataset.itemsPerPage;\n\n                for (var _i = 1; _i <= table.rows.length - 1; _i++) {\n                  if (_i >= startIndex && _i <= endIndex) {\n                    table.rows[_i].style.display = \"table-row\";\n                  } else {\n                    table.rows[_i].style.display = \"none\";\n                  }\n                }\n              } else {\n                for (var _i2 = 1; _i2 <= table.rows.length - 1; _i2++) {\n                  table.rows[_i2].style.display = \"table-row\";\n                }\n              }\n            }\n          }\n        });\n      }\n    }\n  }, {\n    key: \"initTableSort\",\n    value: function initTableSort(table, pagination, classCollection) {\n      if (table.classList.contains(\"ea-advanced-data-table-sortable\")) {\n        table.addEventListener(\"click\", function (e) {\n          if (e.target.tagName.toLowerCase() === \"th\") {\n            var index = e.target.cellIndex;\n            var currentPage = 1;\n            var startIndex = 1;\n            var endIndex = table.rows.length - 1;\n            var sort = \"\";\n            var classList = e.target.classList;\n            var collection = [];\n            var origTable = table.cloneNode(true);\n\n            if (classList.contains(\"asc\")) {\n              e.target.classList.remove(\"asc\");\n              e.target.classList.add(\"desc\");\n              sort = \"desc\";\n            } else if (classList.contains(\"desc\")) {\n              e.target.classList.remove(\"desc\");\n              e.target.classList.add(\"asc\");\n              sort = \"asc\";\n            } else {\n              e.target.classList.add(\"asc\");\n              sort = \"asc\";\n            }\n\n            if (pagination && pagination.innerHTML.length > 0) {\n              currentPage = pagination.querySelector(\".ea-advanced-data-table-pagination-current\").dataset.page;\n              startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;\n              endIndex = endIndex - (currentPage - 1) * table.dataset.itemsPerPage >= table.dataset.itemsPerPage ? currentPage * table.dataset.itemsPerPage : endIndex;\n            } // collect header class\n\n\n            classCollection[currentPage] = [];\n            table.querySelectorAll(\"th\").forEach(function (el) {\n              if (el.cellIndex != index) {\n                el.classList.remove(\"asc\", \"desc\");\n              }\n\n              classCollection[currentPage].push(el.classList.contains(\"asc\") ? \"asc\" : el.classList.contains(\"desc\") ? \"desc\" : \"\");\n            }); // collect table cells value\n\n            for (var i = startIndex; i <= endIndex; i++) {\n              var value = void 0;\n              var cell = table.rows[i].cells[index];\n\n              if (isNaN(parseInt(cell.innerText))) {\n                value = cell.innerText.toLowerCase();\n              } else {\n                value = parseInt(cell.innerText);\n              }\n\n              collection.push({\n                index: i,\n                value: value\n              });\n            } // sort collection array\n\n\n            if (sort == \"asc\") {\n              collection.sort(function (x, y) {\n                return x.value > y.value ? 1 : -1;\n              });\n            } else if (sort == \"desc\") {\n              collection.sort(function (x, y) {\n                return x.value < y.value ? 1 : -1;\n              });\n            } // sort table\n\n\n            collection.forEach(function (row, index) {\n              table.rows[startIndex + index].innerHTML = origTable.rows[row.index].innerHTML;\n            });\n          }\n        });\n      }\n    }\n  }, {\n    key: \"initTablePagination\",\n    value: function initTablePagination(table, pagination, classCollection) {\n      if (table.classList.contains(\"ea-advanced-data-table-paginated\")) {\n        var paginationHTML = \"\";\n        var paginationType = pagination.classList.contains(\"ea-advanced-data-table-pagination-button\") ? \"button\" : \"select\";\n        var currentPage = 1;\n        var startIndex = table.rows[0].parentNode.tagName.toLowerCase() == \"thead\" ? 1 : 0;\n        var endIndex = currentPage * table.dataset.itemsPerPage;\n        var maxPages = Math.ceil((table.rows.length - 1) / table.dataset.itemsPerPage); // insert pagination\n\n        if (maxPages > 1) {\n          if (paginationType == \"button\") {\n            for (var i = 1; i <= maxPages; i++) {\n              paginationHTML += '<a href=\"#\" data-page=\"' + i + '\" class=\"' + (i == 1 ? \"ea-advanced-data-table-pagination-current\" : \"\") + '\">' + i + \"</a>\";\n            }\n\n            pagination.insertAdjacentHTML(\"beforeend\", '<a href=\"#\" data-page=\"1\">&laquo;</a>' + paginationHTML + '<a href=\"#\" data-page=\"' + maxPages + '\">&raquo;</a>');\n          } else {\n            for (var _i3 = 1; _i3 <= maxPages; _i3++) {\n              paginationHTML += '<option value=\"' + _i3 + '\">' + _i3 + \"</option>\";\n            }\n\n            pagination.insertAdjacentHTML(\"beforeend\", \"<select>\" + paginationHTML + \"</select>\");\n          }\n        } // make initial item visible\n\n\n        for (var _i4 = 0; _i4 <= endIndex; _i4++) {\n          if (_i4 >= table.rows.length) {\n            break;\n          }\n\n          table.rows[_i4].style.display = \"table-row\";\n        } // paginate on click\n\n\n        if (paginationType == \"button\") {\n          pagination.addEventListener(\"click\", function (e) {\n            e.preventDefault();\n\n            if (e.target.tagName.toLowerCase() == \"a\") {\n              currentPage = e.target.dataset.page;\n              offset = table.rows[0].parentNode.tagName.toLowerCase() == \"thead\" ? 1 : 0;\n              startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;\n              endIndex = currentPage * table.dataset.itemsPerPage;\n              pagination.querySelectorAll(\".ea-advanced-data-table-pagination-current\").forEach(function (el) {\n                el.classList.remove(\"ea-advanced-data-table-pagination-current\");\n              });\n              pagination.querySelectorAll('[data-page=\"' + currentPage + '\"]').forEach(function (el) {\n                el.classList.add(\"ea-advanced-data-table-pagination-current\");\n              });\n\n              for (var _i5 = offset; _i5 <= table.rows.length - 1; _i5++) {\n                if (_i5 >= startIndex && _i5 <= endIndex) {\n                  table.rows[_i5].style.display = \"table-row\";\n                } else {\n                  table.rows[_i5].style.display = \"none\";\n                }\n              }\n\n              table.querySelectorAll(\"th\").forEach(function (el, index) {\n                el.classList.remove(\"asc\", \"desc\");\n\n                if (typeof classCollection[currentPage] != \"undefined\") {\n                  if (classCollection[currentPage][index]) {\n                    el.classList.add(classCollection[currentPage][index]);\n                  }\n                }\n              });\n            }\n          });\n        } else {\n          if (pagination.hasChildNodes()) {\n            pagination.querySelector(\"select\").addEventListener(\"input\", function (e) {\n              e.preventDefault();\n              currentPage = e.target.value;\n              offset = table.rows[0].parentNode.tagName.toLowerCase() == \"thead\" ? 1 : 0;\n              startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;\n              endIndex = currentPage * table.dataset.itemsPerPage;\n\n              for (var _i6 = offset; _i6 <= table.rows.length - 1; _i6++) {\n                if (_i6 >= startIndex && _i6 <= endIndex) {\n                  table.rows[_i6].style.display = \"table-row\";\n                } else {\n                  table.rows[_i6].style.display = \"none\";\n                }\n              }\n\n              table.querySelectorAll(\"th\").forEach(function (el, index) {\n                el.classList.remove(\"asc\", \"desc\");\n\n                if (typeof classCollection[currentPage] != \"undefined\") {\n                  if (classCollection[currentPage][index]) {\n                    el.classList.add(classCollection[currentPage][index]);\n                  }\n                }\n              });\n            });\n          }\n        }\n      }\n    }\n  }, {\n    key: \"initWooFeatures\",\n    value: function initWooFeatures(table) {\n      table.querySelectorAll(\".nt_button_woo\").forEach(function (el) {\n        el.classList.add(\"add_to_cart_button\", \"ajax_add_to_cart\");\n      });\n      table.querySelectorAll(\".nt_woo_quantity\").forEach(function (el) {\n        el.addEventListener(\"input\", function (e) {\n          var product_id = e.target.dataset.product_id;\n          var quantity = e.target.value;\n          $(\".nt_add_to_cart_\" + product_id, $(table)).data(\"quantity\", quantity);\n        });\n      });\n    }\n  }]);\n\n  return advancedDataTable;\n}();\n\nea.hooks.addAction(\"ea.frontend.init\", \"ea\", function (isEditMode) {\n  new advancedDataTable();\n});\n\n//# sourceURL=webpack:///./src/advancedDataTable.js?");

/***/ }),

/***/ "./src/common.js":
/*!***********************!*\
  !*** ./src/common.js ***!
  \***********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ \"./node_modules/@wordpress/hooks/build-module/index.js\");\n\nwindow.ea = {\n  hooks: Object(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__[\"createHooks\"])(),\n  isEditMode: false\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  window.ea.isEditMode = elementorFrontend.isEditMode(); // hooks\n\n  ea.hooks.doAction(\"ea.frontend.init\", ea.isEditMode);\n});\n\n//# sourceURL=webpack:///./src/common.js?");

/***/ }),

/***/ 0:
/*!********************************************************!*\
  !*** multi ./src/common.js ./src/advancedDataTable.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("__webpack_require__(/*! ./src/common.js */\"./src/common.js\");\nmodule.exports = __webpack_require__(/*! ./src/advancedDataTable.js */\"./src/advancedDataTable.js\");\n\n\n//# sourceURL=webpack:///multi_./src/common.js_./src/advancedDataTable.js?");

/***/ })

/******/ });