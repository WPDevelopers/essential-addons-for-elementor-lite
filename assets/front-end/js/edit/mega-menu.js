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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/edit/mega-menu.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/edit/mega-menu.js":
/*!**********************************!*\
  !*** ./src/js/edit/mega-menu.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(o) { \"@babel/helpers - typeof\"; return _typeof = \"function\" == typeof Symbol && \"symbol\" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && \"function\" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? \"symbol\" : typeof o; }, _typeof(o); }\nfunction _readOnlyError(r) { throw new TypeError('\"' + r + '\" is read-only'); }\nfunction _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError(\"Cannot call a class as a function\"); }\nfunction _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, \"value\" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }\nfunction _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, \"prototype\", { writable: !1 }), e; }\nfunction _toPropertyKey(t) { var i = _toPrimitive(t, \"string\"); return \"symbol\" == _typeof(i) ? i : i + \"\"; }\nfunction _toPrimitive(t, r) { if (\"object\" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || \"default\"); if (\"object\" != _typeof(i)) return i; throw new TypeError(\"@@toPrimitive must return a primitive value.\"); } return (\"string\" === r ? String : Number)(t); }\nfunction _callSuper(t, o, e) { return o = _getPrototypeOf(o), _possibleConstructorReturn(t, _isNativeReflectConstruct() ? Reflect.construct(o, e || [], _getPrototypeOf(t).constructor) : o.apply(t, e)); }\nfunction _possibleConstructorReturn(t, e) { if (e && (\"object\" == _typeof(e) || \"function\" == typeof e)) return e; if (void 0 !== e) throw new TypeError(\"Derived constructors may only return object or undefined\"); return _assertThisInitialized(t); }\nfunction _assertThisInitialized(e) { if (void 0 === e) throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); return e; }\nfunction _isNativeReflectConstruct() { try { var t = !Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); } catch (t) {} return (_isNativeReflectConstruct = function _isNativeReflectConstruct() { return !!t; })(); }\nfunction _superPropGet(t, o, e, r) { var p = _get(_getPrototypeOf(1 & r ? t.prototype : t), o, e); return 2 & r && \"function\" == typeof p ? function (t) { return p.apply(e, t); } : p; }\nfunction _get() { return _get = \"undefined\" != typeof Reflect && Reflect.get ? Reflect.get.bind() : function (e, t, r) { var p = _superPropBase(e, t); if (p) { var n = Object.getOwnPropertyDescriptor(p, t); return n.get ? n.get.call(arguments.length < 3 ? e : r) : n.value; } }, _get.apply(null, arguments); }\nfunction _superPropBase(t, o) { for (; !{}.hasOwnProperty.call(t, o) && null !== (t = _getPrototypeOf(t));); return t; }\nfunction _getPrototypeOf(t) { return _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function (t) { return t.__proto__ || Object.getPrototypeOf(t); }, _getPrototypeOf(t); }\nfunction _inherits(t, e) { if (\"function\" != typeof e && null !== e) throw new TypeError(\"Super expression must either be null or a function\"); t.prototype = Object.create(e && e.prototype, { constructor: { value: t, writable: !0, configurable: !0 } }), Object.defineProperty(t, \"prototype\", { writable: !1 }), e && _setPrototypeOf(t, e); }\nfunction _setPrototypeOf(t, e) { return _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function (t, e) { return t.__proto__ = e, t; }, _setPrototypeOf(t, e); }\n/**\n * EA Mega Menu — editor element type registration.\n *\n * Runs in the Elementor editor window (not the preview iframe).\n *\n * Returning `support_nesting` from the widget's PHP config is not enough on its\n * own: Elementor only treats a widget as nestable once an element type for it is\n * registered with `elementor.elementsManager`. Without this registration the\n * widget falls back to the plain widget model, so `NestedModelBase.initialize()`\n * never runs, the default child containers are never created, and the panels\n * placeholder is never used — the widget renders but has nowhere to drop widgets.\n *\n * `NestedElementBase` already supplies the view, the empty view and the model;\n * only `getType()` has to be implemented, and it must match the widget name.\n *\n * The base class is loaded asynchronously by Elementor's nested-elements module,\n * which dispatches `elementor/nested-element-type-loaded` once it is ready.\n */\n(function () {\n  \"use strict\";\n\n  var WIDGET_TYPE = \"eael-mega-menu\";\n  function registerMegaMenuElementType() {\n    if (\"undefined\" === typeof window.elementor || !elementor.elementsManager || !elementor.modules || !elementor.modules.elements || !elementor.modules.elements.types || !elementor.modules.elements.types.NestedElementBase) {\n      return;\n    }\n    var GuardedView = buildGuardedView();\n    var EaelMegaMenuElementType = /*#__PURE__*/function (_elementor$modules$el) {\n      function EaelMegaMenuElementType() {\n        _classCallCheck(this, EaelMegaMenuElementType);\n        return _callSuper(this, EaelMegaMenuElementType, arguments);\n      }\n      _inherits(EaelMegaMenuElementType, _elementor$modules$el);\n      return _createClass(EaelMegaMenuElementType, [{\n        key: \"getType\",\n        value: function getType() {\n          return WIDGET_TYPE;\n        }\n      }, {\n        key: \"getView\",\n        value: function getView() {\n          return GuardedView || _superPropGet(EaelMegaMenuElementType, \"getView\", this, 3)([]);\n        }\n      }]);\n    }(elementor.modules.elements.types.NestedElementBase);\n    elementor.elementsManager.registerElementType(new EaelMegaMenuElementType());\n    patchContainerPresets();\n  }\n\n  /**\n   * Restore the structure presets inside a submenu panel.\n   *\n   * Picking a structure in an empty nested container routes through\n   * `elementor.helpers.container.createContainerFromPreset( preset, target,\n   * { createWrapper: false } )` — \"use this container as the preset's parent\n   * instead of creating one\". Editor V4 (`e_opt_in_v4`, on by default for sites\n   * installed on Elementor 4.0+) reimplemented that helper in\n   * `v4-flexbox-preset.js`, and its `createWrapper: false` branch reuses the\n   * target *without ever applying the preset's parent props*:\n   *\n   *     const reuseTarget = isRoot && false === options.createWrapper;\n   *     const node = reuseTarget ? target : createFlexboxElement( target, buildModel( parentProps … ) );\n   *\n   * So the row direction that makes a multi column preset a *row* of columns is\n   * dropped — \"50 / 50\" builds two full width children that stack — and the two\n   * direction-only presets (`c100`, `r100`), which carry no children at all,\n   * become complete no-ops: clicking them does nothing.\n   *\n   * This is upstream behaviour shared by every nested element, Elementor's own\n   * Nested Tabs included, so the repair is scoped as tightly as possible: it\n   * only runs for a target that is a direct child of a Mega Menu widget, only\n   * while V4 is on, and only for the `createWrapper: false` call shape. Every\n   * other element keeps whatever the installed Elementor does.\n   */\n  function patchContainerPresets() {\n    var helper = elementor.helpers ? elementor.helpers.container : null;\n    if (!helper || helper.eaelMegaMenuPresetsPatched || \"function\" !== typeof helper.createContainerFromPreset) {\n      return;\n    }\n    var createContainerFromPreset = helper.createContainerFromPreset;\n    helper.createContainerFromPreset = function (preset, target, options) {\n      var created = createContainerFromPreset.apply(this, arguments);\n      try {\n        applyPresetParent(preset, target, options);\n      } catch (e) {\n        // A repair is never worth breaking the insert that just succeeded.\n      }\n      return created;\n    };\n    helper.eaelMegaMenuPresetsPatched = true;\n  }\n\n  /**\n   * Apply the parent half of a preset that V4 dropped.\n   *\n   * @param {string} preset  Preset id, e.g. `c100` or `50-50`.\n   * @param {Object} target  Container the preset was applied to.\n   * @param {Object} options Command options the preset was called with.\n   */\n  function applyPresetParent(preset, target, options) {\n    if (!isV4OptIn() || !options || false !== options.createWrapper) {\n      return;\n    }\n    if (!isMegaMenuPanel(target)) {\n      return;\n    }\n    var settings = getPresetParentSettings(preset);\n    if (!settings) {\n      return;\n    }\n\n    // `c100` and `r100` describe a bare container with no children. Applying\n    // the direction to the panel itself would leave the click with nothing to\n    // show for it — the panel is still empty, so the picker just stays open.\n    // Creating the container the pre-V4 helper created keeps the visible\n    // result users expect from those two tiles.\n    if (\"c100\" === preset || \"r100\" === preset) {\n      elementor.helpers.container.createContainer(settings, target, {});\n      return;\n    }\n    elementor.helpers.container.setContainerSettings(settings, target);\n  }\n\n  /**\n   * The flex settings a preset's parent carries.\n   *\n   * Mirrors `PRESET_DEFINITIONS` and `rowOfSizes()` in Elementor's\n   * `v4-flexbox-preset.js`, including its rule that sizes summing past 100%\n   * wrap.\n   *\n   * @param {string} preset Preset id.\n   *\n   * @return {Object|null} Container settings, or null when the id is unknown.\n   */\n  function getPresetParentSettings(preset) {\n    if (\"c100\" === preset) {\n      return {\n        flex_direction: \"column\"\n      };\n    }\n\n    // The one preset whose id mixes prefixes and sizes; its parent is a row.\n    if (\"r100\" === preset || \"c100-c50-50\" === preset) {\n      return {\n        flex_direction: \"row\"\n      };\n    }\n    var sizes = String(preset).split(\"-\").map(Number);\n    if (!sizes.length || sizes.some(function (size) {\n      return !size || isNaN(size);\n    })) {\n      return null;\n    }\n    var settings = {\n      flex_direction: \"row\"\n    };\n    if (sizes.reduce(function (sum, size) {\n      return sum + size;\n    }, 0) > 100) {\n      settings.flex_wrap = \"wrap\";\n    }\n    return settings;\n  }\n\n  /**\n   * Is this container one of our submenu panels — a direct child of a Mega Menu?\n   *\n   * @param {Object} container Elementor container object.\n   *\n   * @return {boolean} True for a Mega Menu panel.\n   */\n  function isMegaMenuPanel(container) {\n    var parent = container ? container.parent : null;\n    if (!parent || !parent.model || \"function\" !== typeof parent.model.get) {\n      return false;\n    }\n    return WIDGET_TYPE === parent.model.get(\"widgetType\");\n  }\n\n  /**\n   * Is the V4 editor active? Its preset helper is the one with the gap above.\n   *\n   * @return {boolean}\n   */\n  function isV4OptIn() {\n    var features = window.elementorCommon && elementorCommon.config ? elementorCommon.config.experimentalFeatures : null;\n    return !!(features && features.e_opt_in_v4);\n  }\n\n  /**\n   * Null-guard Elementor's nested element click handler.\n   *\n   * `modules/nested-elements/assets/js/editor/views/view.js` does:\n   *\n   *     events.click = ( event ) => {\n   *         if ( … === event.target.closest( '.elementor' ).dataset.elementorId ) {\n   *\n   * `closest()` returns null whenever the clicked node has already been\n   * detached — which is exactly what happens when you click \"+\" inside an empty\n   * nested container and pick a layout: creating the container tears down the\n   * empty view that owned the clicked node, and the click then bubbles to this\n   * handler with a target that is no longer in the document.\n   *\n   * The result is an uncaught TypeError in the console on every layout insert.\n   * It is harmless — the handler only selects an element — but it is noise, and\n   * it is reproducible on Elementor's own Nested Tabs, so it is not ours to fix\n   * upstream. Subclassing the view lets us skip the handler in exactly that\n   * detached case for this widget only.\n   *\n   * @return {Function|null} Guarded view class, or null to keep the default.\n   */\n  function buildGuardedView() {\n    var component = \"undefined\" !== typeof $e && $e.components ? $e.components.get(\"nested-elements\") : null;\n    var BaseView = component && component.exports ? component.exports.NestedView : null;\n    if (\"function\" !== typeof BaseView) {\n      return null;\n    }\n    return /*#__PURE__*/function (_BaseView) {\n      function EaelMegaMenuView() {\n        _classCallCheck(this, EaelMegaMenuView);\n        return _callSuper(this, EaelMegaMenuView, arguments);\n      }\n      _inherits(EaelMegaMenuView, _BaseView);\n      return _createClass(EaelMegaMenuView, [{\n        key: \"events\",\n        value: function events() {\n          var events = _superPropGet(EaelMegaMenuView, \"events\", this, 3)([]);\n          var original = events.click;\n          if (\"function\" !== typeof original) {\n            return events;\n          }\n          events.click = function (event) {\n            var target = event ? event.target : null;\n            if (!target || \"function\" !== typeof target.closest || !target.closest(\".elementor\")) {\n              return;\n            }\n            return original.apply(this, arguments);\n          };\n          return events;\n        }\n      }]);\n    }(BaseView);\n  }\n  if (\"undefined\" === typeof window.elementorCommon || !elementorCommon.elements) {\n    return;\n  }\n  elementorCommon.elements.$window.on(\"elementor/nested-element-type-loaded\", registerMegaMenuElementType);\n})();\n\n//# sourceURL=webpack:///./src/js/edit/mega-menu.js?");

/***/ })

/******/ });