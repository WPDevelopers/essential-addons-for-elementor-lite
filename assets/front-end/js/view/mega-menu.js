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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/view/mega-menu.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/view/mega-menu.js":
/*!**********************************!*\
  !*** ./src/js/view/mega-menu.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * EA Mega Menu — desktop behaviour.\n *\n * What this file does, in order:\n *   1. Reads the settings PHP put on the wrapper as data-* attributes.\n *   2. Decides how a panel opens — on hover, or on click.\n *   3. Works out how wide each panel should be and where to place it.\n *   4. Closes panels again on mouse-out, outside click, Escape, or tab-out.\n */\nvar MegaMenu = function MegaMenu($scope, $) {\n  // -----------------------------------------------------------------\n  // 1. Find the elements we need\n  // -----------------------------------------------------------------\n\n  var $container = $scope.find(\".eael-mega-menu-container\").eq(0);\n\n  // Nothing to do if the widget did not render (e.g. no menu items).\n  if (!$container.length) {\n    return;\n  }\n\n  // The plain DOM node. jQuery objects are handy for events, but plain\n  // nodes are easier for measuring and for class checks.\n  var container = $container[0];\n\n  // Only items that actually have a dropdown need any behaviour.\n  var $panelItems = $container.find(\".eael-mega-menu__item--has-panel\");\n  if (!$panelItems.length) {\n    return;\n  }\n\n  // -----------------------------------------------------------------\n  // 2. Read the settings PHP passed down as data-* attributes\n  // -----------------------------------------------------------------\n\n  var trigger = container.dataset.trigger || \"hover\";\n  var animation = container.dataset.animation || \"fade\";\n  var duration = parseInt(container.dataset.duration, 10);\n\n  // parseInt gives NaN for an empty or broken value, so fall back to 300ms.\n  if (isNaN(duration)) {\n    duration = 300;\n  }\n\n  // How long to wait before closing after the mouse leaves. Without this\n  // small delay the panel flickers shut while moving towards it.\n  var CLOSE_DELAY = 150;\n\n  // How long to wait after a resize before measuring again. Resize fires\n  // many times per second, and measuring on every one of them is wasteful.\n  var RESIZE_DELAY = 150;\n\n  // Holds the pending \"close this panel\" timer, so we can cancel it.\n  var closeTimer = null;\n\n  // Elementor's id for this widget. PHP used it to name the temporary\n  // stylesheet, and we use it to keep our event handlers separate.\n  var widgetId = $scope.data(\"id\") || \"\";\n\n  // Every widget gets its own event namespace. We attach some handlers to\n  // document/window, and the Elementor editor re-runs this file each time\n  // the widget is edited — without a namespace to remove first, those\n  // handlers would pile up on every edit.\n  var eventNs = \".eaelMegaMenu\" + widgetId;\n\n  // -----------------------------------------------------------------\n  // 3. Apply the animation settings\n  // -----------------------------------------------------------------\n\n  // The SCSS reads both of these; the class picks which animation runs.\n  container.style.setProperty(\"--eael-mm-duration\", duration + \"ms\");\n  container.classList.add(\"eael-mega-menu--anim-\" + animation);\n\n  // -----------------------------------------------------------------\n  // 4. Small helpers\n  // -----------------------------------------------------------------\n\n  // Get the panel that belongs to a menu item.\n  function getPanel(item) {\n    return item.querySelector(\".eael-mega-menu__panel\");\n  }\n\n  // Get the <a> that belongs to a menu item.\n  function getLink(item) {\n    return item.querySelector(\".eael-mega-menu__item-link\");\n  }\n\n  // Is this item's panel currently open?\n  function isOpen(item) {\n    return item.classList.contains(\"is-active\");\n  }\n\n  // -----------------------------------------------------------------\n  // 5. Sizing and placing a panel\n  // -----------------------------------------------------------------\n\n  /**\n   * Give the panel the right width and horizontal position.\n   *\n   * The panel is positioned relative to its menu item, so every offset\n   * below is \"how far from the menu item\" — which is why we subtract the\n   * item's own position from whatever we are lining up with.\n   */\n  function positionPanel(item) {\n    var panel = getPanel(item);\n    if (!panel) {\n      return;\n    }\n\n    // Wipe the previous run's values first, otherwise we would measure\n    // the panel while it is still wearing its old size.\n    panel.style.left = \"\";\n    panel.style.right = \"\";\n    panel.style.width = \"\";\n    panel.style.transform = \"\";\n\n    // clientWidth is the viewport WITHOUT the scrollbar.\n    // window.innerWidth includes it and would make the panel too wide.\n    var viewportWidth = document.documentElement.clientWidth;\n    var itemBox = item.getBoundingClientRect();\n\n    // Option A — stretch across the whole viewport.\n    if (panel.classList.contains(\"eael-mega-menu__panel--full\")) {\n      panel.style.width = viewportWidth + \"px\";\n      panel.style.left = -itemBox.left + \"px\";\n      panel.style.right = \"auto\";\n      return;\n    }\n\n    // Option B — match the Elementor container the menu sits in.\n    if (panel.classList.contains(\"eael-mega-menu__panel--container\")) {\n      var parentBox = getParentContainerBox(item);\n      if (parentBox) {\n        panel.style.width = parentBox.width + \"px\";\n        panel.style.left = parentBox.left - itemBox.left + \"px\";\n        panel.style.right = \"auto\";\n      }\n      return;\n    }\n\n    // Option C — a fixed width the user typed in. The CSS already applied\n    // that width, so here we only rescue it if it hangs off the screen.\n    keepPanelOnScreen(panel, viewportWidth);\n  }\n\n  // Find the Elementor container/section wrapping this menu, and return its\n  // size and position. Falls back to the widget's own parent element.\n  function getParentContainerBox(item) {\n    var parent = item.closest(\".e-con, .elementor-container, .elementor-widget-wrap\");\n    if (!parent) {\n      parent = container.parentElement;\n    }\n    if (!parent) {\n      return null;\n    }\n    return parent.getBoundingClientRect();\n  }\n\n  // If a fixed-width panel sticks out past either edge of the screen, pin it\n  // to that edge instead.\n  function keepPanelOnScreen(panel, viewportWidth) {\n    var panelBox = panel.getBoundingClientRect();\n\n    // A centred panel is centred with translateX(-50%). Once we pin it to\n    // an edge that shift is wrong, so we clear it.\n    if (panelBox.right > viewportWidth) {\n      panel.style.left = \"auto\";\n      panel.style.right = \"0\";\n      panel.style.transform = \"none\";\n    } else if (panelBox.left < 0) {\n      panel.style.left = \"0\";\n      panel.style.right = \"auto\";\n      panel.style.transform = \"none\";\n    }\n  }\n\n  // -----------------------------------------------------------------\n  // 6. Opening and closing\n  // -----------------------------------------------------------------\n\n  function openPanel(item) {\n    if (isOpen(item)) {\n      return;\n    }\n\n    // Nothing to show — see markUnresolved().\n    if (getPanel(item).classList.contains(\"is-unresolved\")) {\n      return;\n    }\n\n    // Only one panel may be open at a time.\n    closeAllPanels();\n\n    // Measure before showing, so the panel does not visibly jump.\n    positionPanel(item);\n    item.classList.add(\"is-active\");\n    getPanel(item).classList.add(\"is-open\");\n    getLink(item).setAttribute(\"aria-expanded\", \"true\");\n  }\n  function closePanel(item) {\n    item.classList.remove(\"is-active\");\n    getPanel(item).classList.remove(\"is-open\");\n    getLink(item).setAttribute(\"aria-expanded\", \"false\");\n  }\n  function closeAllPanels() {\n    $panelItems.each(function () {\n      closePanel(this);\n    });\n  }\n  function togglePanel(item) {\n    if (isOpen(item)) {\n      closePanel(item);\n    } else {\n      openPanel(item);\n    }\n  }\n\n  // Cancel a close that was scheduled but has not happened yet.\n  function cancelScheduledClose() {\n    clearTimeout(closeTimer);\n    closeTimer = null;\n  }\n\n  // Close this item shortly from now, unless something cancels it first.\n  function scheduleClose(item) {\n    cancelScheduledClose();\n    closeTimer = setTimeout(function () {\n      closePanel(item);\n    }, CLOSE_DELAY);\n  }\n\n  // -----------------------------------------------------------------\n  // 7. Linked sections\n  // -----------------------------------------------------------------\n  //\n  // A panel can pull in a Container that lives elsewhere on the page,\n  // matched by its CSS ID. We physically MOVE that Container into the\n  // panel, so it still exists only once in the page — good for SEO and\n  // for screen readers. PHP hid it with a small stylesheet meanwhile.\n\n  // Mark a panel whose linked section could not be used. An unresolved\n  // panel never opens, so the menu item behaves as an ordinary link.\n  function markUnresolved(panel) {\n    panel.classList.add(\"is-unresolved\");\n  }\n\n  // Move one item's linked section into its panel.\n  function adoptLinkedSection(item) {\n    var panel = getPanel(item);\n    var sectionId = panel.getAttribute(\"data-mega-section\");\n\n    // This panel uses a saved template, not a linked section.\n    if (!sectionId) {\n      return;\n    }\n    var section = document.getElementById(sectionId);\n\n    // Nothing on the page has that CSS ID.\n    if (!section) {\n      markUnresolved(panel);\n      return;\n    }\n\n    // Already moved in by an earlier run.\n    if (panel.contains(section)) {\n      return;\n    }\n\n    // This menu sits inside the section it points at. Moving the section\n    // would put the menu inside its own panel, so refuse.\n    if (section.contains(container)) {\n      markUnresolved(panel);\n      return;\n    }\n\n    // Another menu item already claimed this section.\n    if (section.dataset.eaelAdopted) {\n      markUnresolved(panel);\n      return;\n    }\n    section.dataset.eaelAdopted = \"1\";\n    section.classList.add(\"eael-mega-menu__adopted\");\n    panel.appendChild(section);\n  }\n  function adoptAllLinkedSections() {\n    $panelItems.each(function () {\n      adoptLinkedSection(this);\n    });\n\n    // The sections are inside their panels now, so drop the stylesheet that\n    // was hiding them — otherwise they would stay hidden in there too.\n    var hideStyle = document.getElementById(\"eael-mm-hide-\" + widgetId);\n    if (hideStyle) {\n      hideStyle.remove();\n    }\n\n    // Anything inside a moved section (sliders, lazy images) measured itself\n    // while hidden and got zero. Ask everything to measure again.\n    elementorFrontend.elements.$window.trigger(\"resize\");\n  }\n\n  // Place every panel held open by the \"Keep Panel Open\" switch, so the user\n  // styles it at the same size it will have on the live site.\n  function positionEditorOpenPanels() {\n    $panelItems.each(function () {\n      var panel = getPanel(this);\n      if (panel.classList.contains(\"eael-mega-menu__panel--editor-open\")) {\n        positionPanel(this);\n      }\n    });\n  }\n\n  // -----------------------------------------------------------------\n  // 7. Wire up the events\n  // -----------------------------------------------------------------\n\n  // Remove this widget's old document/window handlers before adding new\n  // ones. See the eventNs comment above for why this matters.\n  $(document).off(eventNs);\n  $(window).off(eventNs);\n\n  // Inside the Elementor editor a panel is opened by the \"Keep Panel Open\"\n  // switch instead of by hovering — otherwise the panel would vanish the\n  // moment you moved the mouse towards the style controls. So we skip all\n  // the open/close wiring here and only keep the panels correctly sized.\n  if (window.isEditMode) {\n    positionEditorOpenPanels();\n    $(window).on(\"resize\" + eventNs, eael.debounce(positionEditorOpenPanels, RESIZE_DELAY));\n    return;\n  }\n  adoptAllLinkedSections();\n\n  // Some content arrives late (lazy loading, other widgets rendering). Give\n  // any section we could not find one more chance once the page has loaded.\n  $(window).on(\"load\" + eventNs, function () {\n    $panelItems.each(function () {\n      var panel = getPanel(this);\n      if (panel.classList.contains(\"is-unresolved\")) {\n        panel.classList.remove(\"is-unresolved\");\n        adoptLinkedSection(this);\n      }\n    });\n  });\n  if (trigger === \"click\") {\n    $panelItems.children(\".eael-mega-menu__item-link\").on(\"click\" + eventNs, function (event) {\n      var item = $(this).closest(\".eael-mega-menu__item\")[0];\n\n      // An unresolved panel has nothing to show, so let the link\n      // behave like a normal link instead of opening an empty box.\n      if (getPanel(item).classList.contains(\"is-unresolved\")) {\n        return;\n      }\n\n      // Stop the browser following the link's href.\n      event.preventDefault();\n      togglePanel(item);\n    });\n  } else {\n    $panelItems.on(\"mouseenter\" + eventNs, function () {\n      cancelScheduledClose();\n      openPanel(this);\n    });\n    $panelItems.on(\"mouseleave\" + eventNs, function () {\n      scheduleClose(this);\n    });\n  }\n\n  // Clicking anywhere outside this menu closes it.\n  $(document).on(\"click\" + eventNs, function (event) {\n    if (!container.contains(event.target)) {\n      cancelScheduledClose();\n      closeAllPanels();\n    }\n  });\n\n  // Escape closes the menu and puts focus back on the item that was open.\n  $(document).on(\"keydown\" + eventNs, function (event) {\n    if (event.key !== \"Escape\" && event.key !== \"Esc\") {\n      return;\n    }\n    var openItem = container.querySelector(\".eael-mega-menu__item.is-active\");\n    if (!openItem) {\n      return;\n    }\n    cancelScheduledClose();\n    closeAllPanels();\n    getLink(openItem).focus();\n  });\n\n  // Tabbing out of the menu closes it. relatedTarget is whatever received\n  // focus next; if that is outside the menu, we are done here.\n  $container.on(\"focusout\" + eventNs, function (event) {\n    if (!event.relatedTarget || !container.contains(event.relatedTarget)) {\n      cancelScheduledClose();\n      closeAllPanels();\n    }\n  });\n\n  // A resized window changes every measurement, so re-place whatever is\n  // still open. eael.debounce waits until resizing stops.\n  var repositionOpenPanel = eael.debounce(function () {\n    $panelItems.each(function () {\n      if (isOpen(this)) {\n        positionPanel(this);\n      }\n    });\n  }, RESIZE_DELAY);\n  $(window).on(\"resize\" + eventNs, repositionOpenPanel);\n  $(window).on(\"orientationchange\" + eventNs, repositionOpenPanel);\n};\njQuery(window).on(\"elementor/frontend/init\", function () {\n  // Runs this widget's setup only once, even if Elementor fires init again.\n  if (eael.elementStatusCheck(\"eaelMegaMenuLoad\")) {\n    return false;\n  }\n  elementorFrontend.hooks.addAction(\"frontend/element_ready/eael-mega-menu.default\", MegaMenu);\n});\n\n//# sourceURL=webpack:///./src/js/view/mega-menu.js?");

/***/ })

/******/ });