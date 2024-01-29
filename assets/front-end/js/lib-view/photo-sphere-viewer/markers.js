(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('three'), require('@photo-sphere-viewer/core')) :
    typeof define === 'function' && define.amd ? define(['exports', 'three', '@photo-sphere-viewer/core'], factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory((global.PhotoSphereViewer = global.PhotoSphereViewer || {}, global.PhotoSphereViewer.MarkersPlugin = {}), global.THREE, global.PhotoSphereViewer));
})(this, (function (exports, THREE, PhotoSphereViewer) {

/*!
 * PhotoSphereViewer.MarkersPlugin 5.6.0
 * @copyright 2024 Damien "Mistic" Sorel
 * @licence MIT (https://opensource.org/licenses/MIT)
 */
"use strict";
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __export = (target, all) => {
    for (var name in all)
      __defProp(target, name, { get: all[name], enumerable: true });
  };
  var __copyProps = (to, from, except, desc) => {
    if (from && typeof from === "object" || typeof from === "function") {
      for (let key of __getOwnPropNames(from))
        if (!__hasOwnProp.call(to, key) && key !== except)
          __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    }
    return to;
  };

  // @photo-sphere-viewer/core
  var require_core = () => PhotoSphereViewer;

  // three
  var require_three = () => THREE;

  // src/index.ts
  var src_exports = {};
  __export(src_exports, {
    MarkersPlugin: () => MarkersPlugin,
    events: () => events_exports
  });
  var import_core15 = require_core();

  // src/events.ts
  var events_exports = {};
  __export(events_exports, {
    EnterMarkerEvent: () => EnterMarkerEvent,
    GotoMarkerDoneEvent: () => GotoMarkerDoneEvent,
    HideMarkersEvent: () => HideMarkersEvent,
    LeaveMarkerEvent: () => LeaveMarkerEvent,
    MarkerVisibilityEvent: () => MarkerVisibilityEvent,
    MarkersPluginEvent: () => MarkersPluginEvent,
    RenderMarkersListEvent: () => RenderMarkersListEvent,
    SelectMarkerEvent: () => SelectMarkerEvent,
    SelectMarkerListEvent: () => SelectMarkerListEvent,
    SetMarkersEvent: () => SetMarkersEvent,
    ShowMarkersEvent: () => ShowMarkersEvent,
    UnselectMarkerEvent: () => UnselectMarkerEvent
  });
  var import_core = require_core();
  var MarkersPluginEvent = class extends import_core.TypedEvent {
  };
  var _MarkerVisibilityEvent = class _MarkerVisibilityEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker, visible) {
      super(_MarkerVisibilityEvent.type);
      this.marker = marker;
      this.visible = visible;
    }
  };
  _MarkerVisibilityEvent.type = "marker-visibility";
  var MarkerVisibilityEvent = _MarkerVisibilityEvent;
  var _GotoMarkerDoneEvent = class _GotoMarkerDoneEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker) {
      super(_GotoMarkerDoneEvent.type);
      this.marker = marker;
    }
  };
  _GotoMarkerDoneEvent.type = "goto-marker-done";
  var GotoMarkerDoneEvent = _GotoMarkerDoneEvent;
  var _LeaveMarkerEvent = class _LeaveMarkerEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker) {
      super(_LeaveMarkerEvent.type);
      this.marker = marker;
    }
  };
  _LeaveMarkerEvent.type = "leave-marker";
  var LeaveMarkerEvent = _LeaveMarkerEvent;
  var _EnterMarkerEvent = class _EnterMarkerEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker) {
      super(_EnterMarkerEvent.type);
      this.marker = marker;
    }
  };
  _EnterMarkerEvent.type = "enter-marker";
  var EnterMarkerEvent = _EnterMarkerEvent;
  var _SelectMarkerEvent = class _SelectMarkerEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker, doubleClick, rightClick) {
      super(_SelectMarkerEvent.type);
      this.marker = marker;
      this.doubleClick = doubleClick;
      this.rightClick = rightClick;
    }
  };
  _SelectMarkerEvent.type = "select-marker";
  var SelectMarkerEvent = _SelectMarkerEvent;
  var _SelectMarkerListEvent = class _SelectMarkerListEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker) {
      super(_SelectMarkerListEvent.type);
      this.marker = marker;
    }
  };
  _SelectMarkerListEvent.type = "select-marker-list";
  var SelectMarkerListEvent = _SelectMarkerListEvent;
  var _UnselectMarkerEvent = class _UnselectMarkerEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(marker) {
      super(_UnselectMarkerEvent.type);
      this.marker = marker;
    }
  };
  _UnselectMarkerEvent.type = "unselect-marker";
  var UnselectMarkerEvent = _UnselectMarkerEvent;
  var _HideMarkersEvent = class _HideMarkersEvent extends MarkersPluginEvent {
    /** @internal */
    constructor() {
      super(_HideMarkersEvent.type);
    }
  };
  _HideMarkersEvent.type = "hide-markers";
  var HideMarkersEvent = _HideMarkersEvent;
  var _SetMarkersEvent = class _SetMarkersEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(markers) {
      super(_SetMarkersEvent.type);
      this.markers = markers;
    }
  };
  _SetMarkersEvent.type = "set-markers";
  var SetMarkersEvent = _SetMarkersEvent;
  var _ShowMarkersEvent = class _ShowMarkersEvent extends MarkersPluginEvent {
    /** @internal */
    constructor() {
      super(_ShowMarkersEvent.type);
    }
  };
  _ShowMarkersEvent.type = "show-markers";
  var ShowMarkersEvent = _ShowMarkersEvent;
  var _RenderMarkersListEvent = class _RenderMarkersListEvent extends MarkersPluginEvent {
    /** @internal */
    constructor(markers) {
      super(_RenderMarkersListEvent.type);
      this.markers = markers;
    }
  };
  _RenderMarkersListEvent.type = "render-markers-list";
  var RenderMarkersListEvent = _RenderMarkersListEvent;

  // src/MarkersButton.ts
  var import_core2 = require_core();

  // src/icons/pin.svg
  var pin_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="10 9 81 81"><path fill="currentColor" d="M50.5 90S22.9 51.9 22.9 36.6 35.2 9 50.5 9s27.6 12.4 27.6 27.6S50.5 90 50.5 90zm0-66.3c-6.1 0-11 4.9-11 11s4.9 11 11 11 11-4.9 11-11-4.9-11-11-11z"/><!--Created by Rohith M S from the Noun Project--></svg>\n';

  // src/MarkersButton.ts
  var MarkersButton = class extends import_core2.AbstractButton {
    constructor(navbar) {
      super(navbar, {
        className: "psv-markers-button",
        icon: pin_default,
        hoverScale: true,
        collapsable: true,
        tabbable: true
      });
      this.plugin = this.viewer.getPlugin("markers");
      if (this.plugin) {
        this.plugin.addEventListener(ShowMarkersEvent.type, this);
        this.plugin.addEventListener(HideMarkersEvent.type, this);
        this.toggleActive(true);
      }
    }
    destroy() {
      if (this.plugin) {
        this.plugin.removeEventListener(ShowMarkersEvent.type, this);
        this.plugin.removeEventListener(HideMarkersEvent.type, this);
      }
      super.destroy();
    }
    isSupported() {
      return !!this.plugin;
    }
    handleEvent(e) {
      if (e instanceof ShowMarkersEvent) {
        this.toggleActive(true);
      } else if (e instanceof HideMarkersEvent) {
        this.toggleActive(false);
      }
    }
    onClick() {
      this.plugin.toggleAllMarkers();
    }
  };
  MarkersButton.id = "markers";

  // src/MarkersListButton.ts
  var import_core4 = require_core();

  // src/constants.ts
  var import_core3 = require_core();

  // src/icons/pin-list.svg
  var pin_list_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="9 9 81 81"><path fill="currentColor" d="M37.5 90S9.9 51.9 9.9 36.6 22.2 9 37.5 9s27.6 12.4 27.6 27.6S37.5 90 37.5 90zm0-66.3c-6.1 0-11 4.9-11 11s4.9 11 11 11 11-4.9 11-11-4.9-11-11-11zM86.7 55H70c-1.8 0-3.3-1.5-3.3-3.3s1.5-3.3 3.3-3.3h16.7c1.8 0 3.3 1.5 3.3 3.3S88.5 55 86.7 55zm0-25h-15a3.3 3.3 0 0 1-3.3-3.3c0-1.8 1.5-3.3 3.3-3.3h15c1.8 0 3.3 1.5 3.3 3.3 0 1.8-1.5 3.3-3.3 3.3zM56.5 73h30c1.8 0 3.3 1.5 3.3 3.3 0 1.8-1.5 3.3-3.3 3.3h-30a3.3 3.3 0 0 1-3.3-3.3 3.2 3.2 0 0 1 3.3-3.3z"/><!--Created by Rohith M S from the Noun Project--></svg>\n';

  // src/constants.ts
  var SVG_NS = "http://www.w3.org/2000/svg";
  var MARKER_DATA = "psvMarker";
  var MARKER_DATA_KEY = import_core3.utils.dasherize(MARKER_DATA);
  var ID_PANEL_MARKER = "marker";
  var ID_PANEL_MARKERS_LIST = "markersList";
  var DEFAULT_HOVER_SCALE = {
    amount: 2,
    duration: 100,
    easing: "linear"
  };
  var MARKERS_LIST_TEMPLATE = (markers, title) => `
<div class="psv-panel-menu psv-panel-menu--stripped">
 <h1 class="psv-panel-menu-title">${pin_list_default} ${title}</h1>
 <ul class="psv-panel-menu-list">
   ${markers.map(
    (marker) => `
   <li data-${MARKER_DATA_KEY}="${marker.id}" class="psv-panel-menu-item" tabindex="0">
     ${marker.type === "image" ? `<span class="psv-panel-menu-item-icon"><img src="${marker.definition}"/></span>` : ""}
     <span class="psv-panel-menu-item-label">${marker.getListContent()}</span>
   </li>
   `
  ).join("")}
 </ul>
</div>
`;

  // src/MarkersListButton.ts
  var MarkersListButton = class extends import_core4.AbstractButton {
    constructor(navbar) {
      super(navbar, {
        className: " psv-markers-list-button",
        icon: pin_list_default,
        hoverScale: true,
        collapsable: true,
        tabbable: true
      });
      this.plugin = this.viewer.getPlugin("markers");
      if (this.plugin) {
        this.viewer.addEventListener(import_core4.events.ShowPanelEvent.type, this);
        this.viewer.addEventListener(import_core4.events.HidePanelEvent.type, this);
      }
    }
    destroy() {
      this.viewer.removeEventListener(import_core4.events.ShowPanelEvent.type, this);
      this.viewer.removeEventListener(import_core4.events.HidePanelEvent.type, this);
      super.destroy();
    }
    isSupported() {
      return !!this.plugin;
    }
    handleEvent(e) {
      if (e instanceof import_core4.events.ShowPanelEvent) {
        this.toggleActive(e.panelId === ID_PANEL_MARKERS_LIST);
      } else if (e instanceof import_core4.events.HidePanelEvent) {
        this.toggleActive(false);
      }
    }
    onClick() {
      this.plugin.toggleMarkersList();
    }
  };
  MarkersListButton.id = "markersList";

  // src/MarkersPlugin.ts
  var import_core14 = require_core();

  // src/markers/AbstractStandardMarker.ts
  var import_core8 = require_core();
  var import_three = require_three();

  // src/MarkerType.ts
  var import_core5 = require_core();
  var MarkerType = /* @__PURE__ */ ((MarkerType3) => {
    MarkerType3["image"] = "image";
    MarkerType3["html"] = "html";
    MarkerType3["element"] = "element";
    MarkerType3["imageLayer"] = "imageLayer";
    MarkerType3["videoLayer"] = "videoLayer";
    MarkerType3["polygon"] = "polygon";
    MarkerType3["polygonPixels"] = "polygonPixels";
    MarkerType3["polyline"] = "polyline";
    MarkerType3["polylinePixels"] = "polylinePixels";
    MarkerType3["square"] = "square";
    MarkerType3["rect"] = "rect";
    MarkerType3["circle"] = "circle";
    MarkerType3["ellipse"] = "ellipse";
    MarkerType3["path"] = "path";
    return MarkerType3;
  })(MarkerType || {});
  function getMarkerType(config, allowNone = false) {
    const found = [];
    Object.keys(MarkerType).forEach((type) => {
      if (config[type]) {
        found.push(type);
      }
    });
    if (found.length === 0 && !allowNone) {
      throw new import_core5.PSVError(`missing marker content, either ${Object.keys(MarkerType).join(", ")}`);
    } else if (found.length > 1) {
      throw new import_core5.PSVError(`multiple marker content, either ${Object.keys(MarkerType).join(", ")}`);
    }
    return found[0];
  }

  // src/markers/AbstractDomMarker.ts
  var import_core7 = require_core();

  // src/markers/Marker.ts
  var import_core6 = require_core();
  var Marker = class {
    constructor(viewer, plugin, config) {
      this.viewer = viewer;
      this.plugin = plugin;
      /** @internal */
      this.state = {
        dynamicSize: false,
        anchor: null,
        visible: false,
        staticTooltip: false,
        position: null,
        position2D: null,
        positions3D: null,
        size: null
      };
      if (!config.id) {
        throw new import_core6.PSVError("missing marker id");
      }
      this.type = getMarkerType(config);
      this.createElement();
      this.update(config);
    }
    get id() {
      return this.config.id;
    }
    get data() {
      return this.config.data;
    }
    get domElement() {
      return null;
    }
    get threeElement() {
      return null;
    }
    get video() {
      return null;
    }
    /**
     * @internal
     */
    destroy() {
      this.hideTooltip();
    }
    /**
     * Checks if it is a 3D marker (imageLayer, videoLayer)
     */
    is3d() {
      return false;
    }
    /**
     * Checks if it is a normal marker (image, html, element)
     */
    isNormal() {
      return false;
    }
    /**
     * Checks if it is a polygon/polyline marker
     */
    isPoly() {
      return false;
    }
    /**
     * Checks if it is an SVG marker
     */
    isSvg() {
      return false;
    }
    /**
     * Updates the marker with new properties
     * @throws {@link PSVError} if the configuration is invalid
     * @internal
     */
    update(config) {
      const newType = getMarkerType(config, true);
      if (newType !== void 0 && newType !== this.type) {
        throw new import_core6.PSVError("cannot change marker type");
      }
      if (import_core6.utils.isExtendedPosition(config)) {
        import_core6.utils.logWarn('Use the "position" property to configure the position of a marker');
        config.position = this.viewer.dataHelper.cleanPosition(config);
      }
      if ("width" in config && "height" in config) {
        import_core6.utils.logWarn('Use the "size" property to configure the size of a marker');
        config.size = { width: config["width"], height: config["height"] };
      }
      this.config = import_core6.utils.deepmerge(this.config, config);
      if (typeof this.config.tooltip === "string") {
        this.config.tooltip = { content: this.config.tooltip };
      }
      if (this.config.tooltip && !this.config.tooltip.trigger) {
        this.config.tooltip.trigger = "hover";
      }
      if (import_core6.utils.isNil(this.config.visible)) {
        this.config.visible = true;
      }
      if (import_core6.utils.isNil(this.config.zIndex)) {
        this.config.zIndex = 1;
      }
      if (import_core6.utils.isNil(this.config.opacity)) {
        this.config.opacity = 1;
      }
      this.state.anchor = import_core6.utils.parsePoint(this.config.anchor);
    }
    /**
     * Returns the markers list content for the marker, it can be either :
     * - the `listContent`
     * - the `tooltip`
     * - the `html`
     * - the `id`
     * @internal
     */
    getListContent() {
      if (this.config.listContent) {
        return this.config.listContent;
      } else if (this.config.tooltip?.content) {
        return this.config.tooltip.content;
      } else if (this.config.html) {
        return this.config.html;
      } else {
        return this.id;
      }
    }
    /**
     * Display the tooltip of this marker
     * @internal
     */
    showTooltip(clientX, clientY) {
      if (this.state.visible && this.config.tooltip?.content && this.state.position2D) {
        const config = {
          ...this.config.tooltip,
          style: {
            // prevents conflicts with tooltip tracking
            pointerEvents: this.state.staticTooltip ? "auto" : "none"
          },
          data: this,
          top: 0,
          left: 0
        };
        if (this.isPoly() || this.is3d()) {
          if (clientX || clientY) {
            const viewerPos = import_core6.utils.getPosition(this.viewer.container);
            config.top = clientY - viewerPos.y;
            config.left = clientX - viewerPos.x;
            config.box = {
              // separate the tooltip from the cursor
              width: 20,
              height: 20
            };
          } else {
            config.top = this.state.position2D.y;
            config.left = this.state.position2D.x;
          }
        } else {
          const position = this.viewer.dataHelper.vector3ToViewerCoords(this.state.positions3D[0]);
          let width = this.state.size.width;
          let height = this.state.size.height;
          if (this.config.hoverScale && !this.state.staticTooltip) {
            width *= this.config.hoverScale.amount;
            height *= this.config.hoverScale.amount;
          }
          config.top = position.y - height * this.state.anchor.y + height / 2;
          config.left = position.x - width * this.state.anchor.x + width / 2;
          config.box = { width, height };
        }
        if (this.tooltip) {
          this.tooltip.update(this.config.tooltip.content, config);
        } else {
          this.tooltip = this.viewer.createTooltip(config);
        }
      }
    }
    /**
     * Hides the tooltip of this marker
     * @internal
     */
    hideTooltip() {
      if (this.tooltip) {
        this.tooltip.hide();
        this.tooltip = null;
      }
    }
  };

  // src/markers/AbstractDomMarker.ts
  var AbstractDomMarker = class extends Marker {
    get domElement() {
      return this.element;
    }
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    destroy() {
      delete this.element[MARKER_DATA];
      super.destroy();
    }
    update(config) {
      super.update(config);
      const element = this.domElement;
      element.id = `psv-marker-${this.config.id}`;
      element.setAttribute("class", "psv-marker");
      if (this.state.visible) {
        element.classList.add("psv-marker--visible");
      }
      if (this.config.tooltip) {
        element.classList.add("psv-marker--has-tooltip");
      }
      if (this.config.content) {
        element.classList.add("psv-marker--has-content");
      }
      if (this.config.className) {
        import_core7.utils.addClasses(element, this.config.className);
      }
      element.style.opacity = `${this.config.opacity}`;
      element.style.zIndex = `${30 + this.config.zIndex}`;
      if (this.config.style) {
        Object.assign(element.style, this.config.style);
      }
    }
  };

  // src/markers/AbstractStandardMarker.ts
  var AbstractStandardMarker = class extends AbstractDomMarker {
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    createElement() {
      this.element[MARKER_DATA] = this;
      this.domElement.addEventListener("transitionend", () => {
        this.domElement.style.transition = "";
      });
    }
    render({
      viewerPosition,
      zoomLevel,
      hoveringMarker
    }) {
      this.__updateSize();
      const position = this.viewer.dataHelper.vector3ToViewerCoords(this.state.positions3D[0]);
      position.x -= this.state.size.width * this.state.anchor.x;
      position.y -= this.state.size.height * this.state.anchor.y;
      const isVisible = this.state.positions3D[0].dot(this.viewer.state.direction) > 0 && position.x + this.state.size.width >= 0 && position.x - this.state.size.width <= this.viewer.state.size.width && position.y + this.state.size.height >= 0 && position.y - this.state.size.height <= this.viewer.state.size.height;
      if (isVisible) {
        this.domElement.style.translate = `${position.x}px ${position.y}px 0px`;
        this.applyScale({
          zoomLevel,
          viewerPosition,
          mouseover: this === hoveringMarker
        });
        if (this.type === "element" /* element */) {
          this.config.element.updateMarker?.({
            marker: this,
            position,
            viewerPosition,
            zoomLevel,
            viewerSize: this.viewer.state.size
          });
        }
        return position;
      } else {
        return null;
      }
    }
    update(config) {
      super.update(config);
      const element = this.domElement;
      element.classList.add("psv-marker--normal");
      if (this.config.scale && Array.isArray(this.config.scale)) {
        this.config.scale = { zoom: this.config.scale };
      }
      if (typeof this.config.hoverScale === "boolean") {
        this.config.hoverScale = this.config.hoverScale ? this.plugin.config.defaultHoverScale || DEFAULT_HOVER_SCALE : null;
      } else if (typeof this.config.hoverScale === "number") {
        this.config.hoverScale = { amount: this.config.hoverScale };
      } else if (!this.config.hoverScale) {
        this.config.hoverScale = this.plugin.config.defaultHoverScale;
      }
      if (this.config.hoverScale) {
        this.config.hoverScale = {
          ...DEFAULT_HOVER_SCALE,
          ...this.plugin.config.defaultHoverScale,
          ...this.config.hoverScale
        };
      }
    }
    /**
     * Computes the real size of a marker
     * @description This is done by removing all it's transformations (if any) and making it visible
     * before querying its bounding rect
     */
    __updateSize() {
      if (!this.state.dynamicSize) {
        return;
      }
      const element = this.domElement;
      const init = !this.state.size;
      if (init) {
        element.classList.add("psv-marker--transparent");
      }
      if (this.isSvg()) {
        const rect = element.firstElementChild.getBoundingClientRect();
        this.state.size = {
          width: rect.width,
          height: rect.height
        };
      } else if (this.isNormal()) {
        this.state.size = {
          width: element.offsetWidth,
          height: element.offsetHeight
        };
      }
      if (init) {
        element.classList.remove("psv-marker--transparent");
      }
      if (this.isSvg()) {
        element.style.width = this.state.size.width + "px";
        element.style.height = this.state.size.height + "px";
      }
      if (this.type !== "element" /* element */) {
        this.state.dynamicSize = false;
      }
    }
    /**
     * Computes and applies the scale to the marker
     */
    applyScale({
      zoomLevel,
      viewerPosition,
      mouseover
    }) {
      if (mouseover !== null && this.config.hoverScale) {
        this.domElement.style.transition = `scale ${this.config.hoverScale.duration}ms ${this.config.hoverScale.easing}`;
      }
      let scale = 1;
      if (typeof this.config.scale === "function") {
        scale = this.config.scale(zoomLevel, viewerPosition);
      } else if (this.config.scale) {
        if (Array.isArray(this.config.scale.zoom)) {
          const [min, max] = this.config.scale.zoom;
          scale *= min + (max - min) * import_core8.CONSTANTS.EASINGS.inQuad(zoomLevel / 100);
        }
        if (Array.isArray(this.config.scale.yaw)) {
          const [min, max] = this.config.scale.yaw;
          const halfFov = import_three.MathUtils.degToRad(this.viewer.state.hFov) / 2;
          const arc = Math.abs(import_core8.utils.getShortestArc(this.state.position.yaw, viewerPosition.yaw));
          scale *= max + (min - max) * import_core8.CONSTANTS.EASINGS.outQuad(Math.max(0, (halfFov - arc) / halfFov));
        }
      }
      if (mouseover && this.config.hoverScale) {
        scale *= this.config.hoverScale.amount;
      }
      this.domElement.style.scale = `${scale}`;
    }
  };

  // src/markers/MarkerNormal.ts
  var import_core9 = require_core();
  var MarkerNormal = class extends AbstractStandardMarker {
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    isNormal() {
      return true;
    }
    createElement() {
      this.element = document.createElement("div");
      super.createElement();
    }
    update(config) {
      super.update(config);
      const element = this.domElement;
      if (!import_core9.utils.isExtendedPosition(this.config.position)) {
        throw new import_core9.PSVError("missing marker position");
      }
      if (this.config.image && !this.config.size) {
        throw new import_core9.PSVError("missing marker size");
      }
      if (this.config.size) {
        this.state.dynamicSize = false;
        this.state.size = this.config.size;
        element.style.width = this.config.size.width + "px";
        element.style.height = this.config.size.height + "px";
      } else {
        this.state.dynamicSize = true;
      }
      switch (this.type) {
        case "image" /* image */:
          this.definition = this.config.image;
          element.style.backgroundImage = `url("${this.config.image}")`;
          break;
        case "html" /* html */:
          this.definition = this.config.html;
          element.innerHTML = this.config.html;
          break;
        case "element" /* element */:
          if (this.definition !== this.config.element) {
            this.definition = this.config.element;
            element.childNodes.forEach((n) => n.remove());
            element.appendChild(this.config.element);
            this.config.element.style.display = "block";
          }
          break;
      }
      element.style.transformOrigin = `${this.state.anchor.x * 100}% ${this.state.anchor.y * 100}%`;
      this.state.position = this.viewer.dataHelper.cleanPosition(this.config.position);
      this.state.positions3D = [this.viewer.dataHelper.sphericalCoordsToVector3(this.state.position)];
    }
  };

  // src/markers/Marker3D.ts
  var import_core11 = require_core();
  var import_three4 = require_three();

  // ../shared/ChromaKeyMaterial.ts
  var import_three2 = require_three();

  // ../shared/shaders/chromaKey.fragment.glsl
  var chromaKey_fragment_default = "// https://www.8thwall.com/playground/chromakey-threejs\n\nuniform sampler2D map;\nuniform float alpha;\nuniform bool keying;\nuniform vec3 color;\nuniform float similarity;\nuniform float smoothness;\nuniform float spill;\n\nvarying vec2 vUv;\n\nvec2 RGBtoUV(vec3 rgb) {\n    return vec2(\n        rgb.r * -0.169 + rgb.g * -0.331 + rgb.b *  0.5    + 0.5,\n        rgb.r *  0.5   + rgb.g * -0.419 + rgb.b * -0.081  + 0.5\n    );\n}\n\nvoid main(void) {\n    gl_FragColor = texture2D(map, vUv);\n\n    if (keying) {\n        float chromaDist = distance(RGBtoUV(gl_FragColor.rgb), RGBtoUV(color));\n\n        float baseMask = chromaDist - similarity;\n        float fullMask = pow(clamp(baseMask / smoothness, 0., 1.), 1.5);\n        gl_FragColor.a *= fullMask * alpha;\n\n        float spillVal = pow(clamp(baseMask / spill, 0., 1.), 1.5);\n        float desat = clamp(gl_FragColor.r * 0.2126 + gl_FragColor.g * 0.7152 + gl_FragColor.b * 0.0722, 0., 1.);\n        gl_FragColor.rgb = mix(vec3(desat, desat, desat), gl_FragColor.rgb, spillVal);\n    } else {\n        gl_FragColor.a *= alpha;\n    }\n}\n";

  // ../shared/shaders/chromaKey.vertex.glsl
  var chromaKey_vertex_default = "varying vec2 vUv;\nuniform vec2 repeat;\nuniform vec2 offset;\n\nvoid main() {\n    vUv = uv * repeat + offset;\n    gl_Position = projectionMatrix *  modelViewMatrix * vec4( position, 1.0 );\n}\n";

  // ../shared/ChromaKeyMaterial.ts
  var ChromaKeyMaterial = class extends import_three2.ShaderMaterial {
    constructor(params) {
      super({
        transparent: true,
        depthTest: false,
        uniforms: {
          map: { value: params?.map },
          repeat: { value: new import_three2.Vector2(1, 1) },
          offset: { value: new import_three2.Vector2(0, 0) },
          alpha: { value: params?.alpha ?? 1 },
          keying: { value: false },
          color: { value: new import_three2.Color(65280) },
          similarity: { value: 0.2 },
          smoothness: { value: 0.2 },
          spill: { value: 0.1 }
        },
        vertexShader: chromaKey_vertex_default,
        fragmentShader: chromaKey_fragment_default
      });
      this.chromaKey = params?.chromaKey;
    }
    get map() {
      return this.uniforms.map.value;
    }
    set map(map) {
      this.uniforms.map.value = map;
    }
    set alpha(alpha) {
      this.uniforms.alpha.value = alpha;
    }
    get offset() {
      return this.uniforms.offset.value;
    }
    get repeat() {
      return this.uniforms.repeat.value;
    }
    set chromaKey(chromaKey) {
      this.uniforms.keying.value = chromaKey?.enabled === true;
      if (chromaKey?.enabled) {
        if (typeof chromaKey.color === "object" && "r" in chromaKey.color) {
          this.uniforms.color.value.set(
            chromaKey.color.r / 255,
            chromaKey.color.g / 255,
            chromaKey.color.b / 255
          );
        } else {
          this.uniforms.color.value.set(chromaKey.color ?? 65280);
        }
        this.uniforms.similarity.value = chromaKey.similarity ?? 0.2;
        this.uniforms.smoothness.value = chromaKey.smoothness ?? 0.2;
      }
    }
  };

  // ../shared/video-utils.ts
  function createVideo({
    src,
    withCredentials,
    muted,
    autoplay
  }) {
    const video = document.createElement("video");
    video.crossOrigin = withCredentials ? "use-credentials" : "anonymous";
    video.loop = true;
    video.playsInline = true;
    video.autoplay = autoplay;
    video.muted = muted;
    video.preload = "metadata";
    video.src = src;
    return video;
  }

  // src/utils.ts
  var import_core10 = require_core();
  var import_three3 = require_three();
  function greatArcIntermediaryPoint(p1, p2, f) {
    const [\u03BB1, \u03C61] = p1;
    const [\u03BB2, \u03C62] = p2;
    const r = import_core10.utils.greatArcDistance(p1, p2);
    const a = Math.sin((1 - f) * r) / Math.sin(r);
    const b = Math.sin(f * r) / Math.sin(r);
    const x = a * Math.cos(\u03C61) * Math.cos(\u03BB1) + b * Math.cos(\u03C62) * Math.cos(\u03BB2);
    const y = a * Math.cos(\u03C61) * Math.sin(\u03BB1) + b * Math.cos(\u03C62) * Math.sin(\u03BB2);
    const z = a * Math.sin(\u03C61) + b * Math.sin(\u03C62);
    return [Math.atan2(y, x), Math.atan2(z, Math.sqrt(x * x + y * y))];
  }
  function getPolygonCoherentPoints(points) {
    const workPoints = [points[0]];
    let k = 0;
    for (let i = 1; i < points.length; i++) {
      const d = points[i - 1][0] - points[i][0];
      if (d > Math.PI) {
        k += 1;
      } else if (d < -Math.PI) {
        k -= 1;
      }
      workPoints.push([points[i][0] + k * 2 * Math.PI, points[i][1]]);
    }
    return workPoints;
  }
  function getPolygonCenter(polygon) {
    const points = getPolygonCoherentPoints(polygon);
    const sum = points.reduce((intermediary, point) => [intermediary[0] + point[0], intermediary[1] + point[1]]);
    return [import_core10.utils.parseAngle(sum[0] / polygon.length), sum[1] / polygon.length];
  }
  function getPolylineCenter(polyline) {
    const points = getPolygonCoherentPoints(polyline);
    let length = 0;
    const lengths = [];
    for (let i = 0; i < points.length - 1; i++) {
      const l = import_core10.utils.greatArcDistance(points[i], points[i + 1]) * import_core10.CONSTANTS.SPHERE_RADIUS;
      lengths.push(l);
      length += l;
    }
    let consumed = 0;
    for (let j = 0; j < points.length - 1; j++) {
      if (consumed + lengths[j] > length / 2) {
        const r = (length / 2 - consumed) / lengths[j];
        return greatArcIntermediaryPoint(points[j], points[j + 1], r);
      }
      consumed += lengths[j];
    }
    return points[Math.round(points.length / 2)];
  }
  var C = new import_three3.Vector3();
  var N = new import_three3.Vector3();
  var V = new import_three3.Vector3();
  var X = new import_three3.Vector3();
  var Y = new import_three3.Vector3();
  var A = new import_three3.Vector3();
  function getGreatCircleIntersection(P1, P2, direction) {
    C.copy(direction).normalize();
    N.crossVectors(P1, P2).normalize();
    V.crossVectors(N, P1).normalize();
    X.copy(P1).multiplyScalar(-C.dot(V));
    Y.copy(V).multiplyScalar(C.dot(P1));
    const H = new import_three3.Vector3().addVectors(X, Y).normalize();
    A.crossVectors(H, C);
    return H.applyAxisAngle(A, 0.01).multiplyScalar(import_core10.CONSTANTS.SPHERE_RADIUS);
  }

  // src/markers/Marker3D.ts
  var Marker3D = class extends Marker {
    get threeElement() {
      return this.element;
    }
    get video() {
      if (this.type === "videoLayer" /* videoLayer */) {
        return this.threeElement.material.map.image;
      } else {
        return null;
      }
    }
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    is3d() {
      return true;
    }
    createElement() {
      const material = new ChromaKeyMaterial({ alpha: 0 });
      const geometry = new import_three4.PlaneGeometry(1, 1);
      const mesh = new import_three4.Mesh(geometry, material);
      mesh.userData = { [MARKER_DATA]: this };
      const group = new import_three4.Group().add(mesh);
      Object.defineProperty(group, "visible", {
        enumerable: true,
        get: function() {
          return this.children[0].userData[MARKER_DATA].state.visible;
        },
        set: function(visible) {
          this.children[0].userData[MARKER_DATA].state.visible = visible;
        }
      });
      this.element = mesh;
      if (this.type === "videoLayer" /* videoLayer */) {
        this.viewer.needsContinuousUpdate(true);
      }
    }
    destroy() {
      delete this.threeElement.userData[MARKER_DATA];
      if (this.type === "videoLayer" /* videoLayer */) {
        this.video.pause();
        this.viewer.needsContinuousUpdate(false);
      }
      super.destroy();
    }
    render() {
      if (this.viewer.renderer.isObjectVisible(this.threeElement)) {
        return this.viewer.dataHelper.sphericalCoordsToViewerCoords(this.state.position);
      } else {
        return null;
      }
    }
    update(config) {
      super.update(config);
      const mesh = this.threeElement;
      const group = mesh.parent;
      const material = mesh.material;
      this.state.dynamicSize = false;
      if (import_core11.utils.isExtendedPosition(this.config.position)) {
        if (!this.config.size) {
          throw new import_core11.PSVError("missing marker size");
        }
        this.state.position = this.viewer.dataHelper.cleanPosition(this.config.position);
        this.state.size = this.config.size;
        mesh.position.set(0.5 - this.state.anchor.x, this.state.anchor.y - 0.5, 0);
        this.viewer.dataHelper.sphericalCoordsToVector3(this.state.position, group.position);
        group.lookAt(0, group.position.y, 0);
        switch (this.config.orientation) {
          case "horizontal":
            group.rotateX(this.state.position.pitch < 0 ? -Math.PI / 2 : Math.PI / 2);
            break;
          case "vertical-left":
            group.rotateY(-Math.PI * 0.4);
            break;
          case "vertical-right":
            group.rotateY(Math.PI * 0.4);
            break;
        }
        group.scale.set(this.config.size.width / 100, this.config.size.height / 100, 1);
        const p = mesh.geometry.getAttribute("position");
        this.state.positions3D = [0, 1, 3, 2].map((i) => {
          const v3 = new import_three4.Vector3();
          v3.fromBufferAttribute(p, i);
          return mesh.localToWorld(v3);
        });
      } else {
        if (this.config.position?.length !== 4) {
          throw new import_core11.PSVError("missing marker position");
        }
        const positions = this.config.position.map((p2) => this.viewer.dataHelper.cleanPosition(p2));
        const positions3D = positions.map((p2) => this.viewer.dataHelper.sphericalCoordsToVector3(p2));
        const centroid = getPolygonCenter(positions.map(({ yaw, pitch }) => [yaw, pitch]));
        this.state.position = { yaw: centroid[0], pitch: centroid[1] };
        this.state.positions3D = positions3D;
        const p = mesh.geometry.getAttribute("position");
        [
          positions3D[0],
          positions3D[1],
          positions3D[3],
          // not a mistake!
          positions3D[2]
        ].forEach((v, i) => {
          p.setX(i, v.x);
          p.setY(i, v.y);
          p.setZ(i, v.z);
        });
        p.needsUpdate = true;
        this.__setTextureWrap(material);
      }
      switch (this.type) {
        case "videoLayer" /* videoLayer */:
          if (this.definition !== this.config.videoLayer) {
            material.map?.dispose();
            const video = createVideo({
              src: this.config.videoLayer,
              withCredentials: this.viewer.config.withCredentials,
              muted: true,
              autoplay: true
            });
            const texture = new import_three4.VideoTexture(video);
            material.map = texture;
            material.alpha = 0;
            video.addEventListener("loadedmetadata", () => {
              material.alpha = this.config.opacity;
              if (!import_core11.utils.isExtendedPosition(this.config.position)) {
                mesh.material.userData[MARKER_DATA] = { width: video.videoWidth, height: video.videoHeight };
                this.__setTextureWrap(material);
              }
            }, { once: true });
            video.play();
            this.definition = this.config.videoLayer;
          }
          break;
        case "imageLayer" /* imageLayer */:
          if (this.definition !== this.config.imageLayer) {
            material.map?.dispose();
            const texture = new import_three4.Texture();
            material.map = texture;
            material.alpha = 0;
            this.viewer.textureLoader.loadImage(this.config.imageLayer).then((image) => {
              if (!import_core11.utils.isExtendedPosition(this.config.position)) {
                mesh.material.userData[MARKER_DATA] = { width: image.width, height: image.height };
                this.__setTextureWrap(material);
              }
              texture.image = image;
              texture.anisotropy = 4;
              texture.needsUpdate = true;
              material.alpha = this.config.opacity;
              this.viewer.needsUpdate();
            });
            this.definition = this.config.imageLayer;
          }
          break;
      }
      material.chromaKey = this.config.chromaKey;
      mesh.renderOrder = 1e3 + this.config.zIndex;
      mesh.geometry.boundingBox = null;
    }
    /**
     * For layers positionned by corners, applies offset to the texture in order to keep its proportions
     */
    __setTextureWrap(material) {
      const imageSize = material.userData[MARKER_DATA];
      if (!imageSize || !imageSize.height || !imageSize.width) {
        material.repeat.set(1, 1);
        material.offset.set(0, 0);
        return;
      }
      const positions = this.config.position.map((p) => {
        return this.viewer.dataHelper.cleanPosition(p);
      });
      const w1 = import_core11.utils.greatArcDistance(
        [positions[0].yaw, positions[0].pitch],
        [positions[1].yaw, positions[1].pitch]
      );
      const w2 = import_core11.utils.greatArcDistance(
        [positions[3].yaw, positions[3].pitch],
        [positions[2].yaw, positions[2].pitch]
      );
      const h1 = import_core11.utils.greatArcDistance(
        [positions[1].yaw, positions[1].pitch],
        [positions[2].yaw, positions[2].pitch]
      );
      const h2 = import_core11.utils.greatArcDistance(
        [positions[0].yaw, positions[0].pitch],
        [positions[3].yaw, positions[3].pitch]
      );
      const layerRatio = (w1 + w2) / (h1 + h2);
      const imageRatio = imageSize.width / imageSize.height;
      let hMargin = 0;
      let vMargin = 0;
      if (layerRatio < imageRatio) {
        hMargin = imageRatio - layerRatio;
      } else {
        vMargin = 1 / imageRatio - 1 / layerRatio;
      }
      material.repeat.set(1 - hMargin, 1 - vMargin);
      material.offset.set(hMargin / 2, vMargin / 2);
    }
  };

  // src/markers/MarkerPolygon.ts
  var import_core12 = require_core();
  var MarkerPolygon = class extends AbstractDomMarker {
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    createElement() {
      this.element = document.createElementNS(SVG_NS, this.isPolygon ? "polygon" : "polyline");
      this.element[MARKER_DATA] = this;
    }
    isPoly() {
      return true;
    }
    /**
     * Checks if it is a polygon/polyline using pixel coordinates
     */
    get isPixels() {
      return this.type === "polygonPixels" /* polygonPixels */ || this.type === "polylinePixels" /* polylinePixels */;
    }
    /**
     * Checks if it is a polygon marker
     */
    get isPolygon() {
      return this.type === "polygon" /* polygon */ || this.type === "polygonPixels" /* polygonPixels */;
    }
    /**
     * Checks if it is a polyline marker
     */
    get isPolyline() {
      return this.type === "polyline" /* polyline */ || this.type === "polylinePixels" /* polylinePixels */;
    }
    render() {
      const positions = this.__getPolyPositions();
      const isVisible = positions.length > (this.isPolygon ? 2 : 1);
      if (isVisible) {
        const position = this.viewer.dataHelper.sphericalCoordsToViewerCoords(this.state.position);
        const points = positions.map((pos) => pos.x - position.x + "," + (pos.y - position.y)).join(" ");
        this.domElement.setAttributeNS(null, "points", points);
        this.domElement.setAttributeNS(null, "transform", `translate(${position.x} ${position.y})`);
        return position;
      } else {
        return null;
      }
    }
    update(config) {
      super.update(config);
      const element = this.domElement;
      element.classList.add("psv-marker--poly");
      this.state.dynamicSize = true;
      if (this.config.svgStyle) {
        Object.entries(this.config.svgStyle).forEach(([prop, value]) => {
          element.setAttributeNS(null, import_core12.utils.dasherize(prop), value);
        });
        if (this.isPolyline && !this.config.svgStyle.fill) {
          element.setAttributeNS(null, "fill", "none");
        }
      } else if (this.isPolygon) {
        element.setAttributeNS(null, "fill", "rgba(0,0,0,0.5)");
      } else if (this.isPolyline) {
        element.setAttributeNS(null, "fill", "none");
        element.setAttributeNS(null, "stroke", "rgb(0,0,0)");
      }
      const actualPoly = this.config[this.type];
      if (!Array.isArray(actualPoly[0])) {
        for (let i = 0; i < actualPoly.length; i++) {
          actualPoly.splice(i, 2, [actualPoly[i], actualPoly[i + 1]]);
        }
      }
      if (this.isPixels) {
        this.definition = actualPoly.map((coord) => {
          const sphericalCoords = this.viewer.dataHelper.textureCoordsToSphericalCoords({
            textureX: coord[0],
            textureY: coord[1]
          });
          return [sphericalCoords.yaw, sphericalCoords.pitch];
        });
      } else {
        this.definition = actualPoly.map((coord) => {
          return [import_core12.utils.parseAngle(coord[0]), import_core12.utils.parseAngle(coord[1], true)];
        });
      }
      const centroid = this.isPolygon ? getPolygonCenter(this.definition) : getPolylineCenter(this.definition);
      this.state.position = { yaw: centroid[0], pitch: centroid[1] };
      this.state.positions3D = this.definition.map((coord) => {
        return this.viewer.dataHelper.sphericalCoordsToVector3({ yaw: coord[0], pitch: coord[1] });
      });
    }
    /**
     * Computes viewer coordinates of each point of a polygon/polyline<br>
     * It handles points behind the camera by creating intermediary points suitable for the projector
     */
    __getPolyPositions() {
      const nbVectors = this.state.positions3D.length;
      const positions3D = this.state.positions3D.map((vector) => {
        return {
          vector,
          visible: vector.dot(this.viewer.state.direction) > 0
        };
      });
      const toBeComputed = [];
      positions3D.forEach((pos, i) => {
        if (!pos.visible) {
          const neighbours = [
            i === 0 ? positions3D[nbVectors - 1] : positions3D[i - 1],
            i === nbVectors - 1 ? positions3D[0] : positions3D[i + 1]
          ];
          neighbours.forEach((neighbour) => {
            if (neighbour.visible) {
              toBeComputed.push({
                visible: neighbour.vector,
                invisible: pos.vector,
                index: i
              });
            }
          });
        }
      });
      toBeComputed.reverse().forEach((pair) => {
        positions3D.splice(pair.index, 0, {
          vector: getGreatCircleIntersection(pair.visible, pair.invisible, this.viewer.state.direction),
          visible: true
        });
      });
      return positions3D.filter((pos) => pos.visible).map((pos) => this.viewer.dataHelper.vector3ToViewerCoords(pos.vector));
    }
  };

  // src/markers/MarkerSvg.ts
  var import_core13 = require_core();
  var MarkerSvg = class extends AbstractStandardMarker {
    constructor(viewer, plugin, config) {
      super(viewer, plugin, config);
    }
    isSvg() {
      return true;
    }
    createElement() {
      const svgType = this.type === "square" /* square */ ? "rect" : this.type;
      const elt = document.createElementNS(SVG_NS, svgType);
      this.element = document.createElementNS(SVG_NS, "svg");
      this.element.appendChild(elt);
      super.createElement();
    }
    update(config) {
      super.update(config);
      const svgElement = this.domElement.firstElementChild;
      if (!import_core13.utils.isExtendedPosition(this.config.position)) {
        throw new import_core13.PSVError("missing marker position");
      }
      this.state.dynamicSize = true;
      switch (this.type) {
        case "square" /* square */:
          this.definition = {
            x: 0,
            y: 0,
            width: this.config.square,
            height: this.config.square
          };
          break;
        case "rect" /* rect */:
          if (Array.isArray(this.config.rect)) {
            this.definition = {
              x: 0,
              y: 0,
              width: this.config.rect[0],
              height: this.config.rect[1]
            };
          } else {
            this.definition = {
              x: 0,
              y: 0,
              width: this.config.rect.width,
              height: this.config.rect.height
            };
          }
          break;
        case "circle" /* circle */:
          this.definition = {
            cx: this.config.circle,
            cy: this.config.circle,
            r: this.config.circle
          };
          break;
        case "ellipse" /* ellipse */:
          if (Array.isArray(this.config.ellipse)) {
            this.definition = {
              cx: this.config.ellipse[0],
              cy: this.config.ellipse[1],
              rx: this.config.ellipse[0],
              ry: this.config.ellipse[1]
            };
          } else {
            this.definition = {
              cx: this.config.ellipse.rx,
              cy: this.config.ellipse.ry,
              rx: this.config.ellipse.rx,
              ry: this.config.ellipse.ry
            };
          }
          break;
        case "path" /* path */:
          this.definition = {
            d: this.config.path
          };
          break;
      }
      Object.entries(this.definition).forEach(([prop, value]) => {
        svgElement.setAttributeNS(null, prop, value);
      });
      if (this.config.svgStyle) {
        Object.entries(this.config.svgStyle).forEach(([prop, value]) => {
          svgElement.setAttributeNS(null, import_core13.utils.dasherize(prop), value);
        });
      } else {
        svgElement.setAttributeNS(null, "fill", "rgba(0,0,0,0.5)");
      }
      this.domElement.style.transformOrigin = `${this.state.anchor.x * 100}% ${this.state.anchor.y * 100}%`;
      this.state.position = this.viewer.dataHelper.cleanPosition(this.config.position);
      this.state.positions3D = [this.viewer.dataHelper.sphericalCoordsToVector3(this.state.position)];
    }
  };

  // src/MarkersPlugin.ts
  var getConfig = import_core14.utils.getConfigParser(
    {
      clickEventOnMarker: false,
      gotoMarkerSpeed: "8rpm",
      markers: null,
      defaultHoverScale: null
    },
    {
      defaultHoverScale(defaultHoverScale) {
        if (!defaultHoverScale) {
          return null;
        }
        if (defaultHoverScale === true) {
          defaultHoverScale = DEFAULT_HOVER_SCALE;
        }
        if (typeof defaultHoverScale === "number") {
          defaultHoverScale = { amount: defaultHoverScale };
        }
        return {
          ...DEFAULT_HOVER_SCALE,
          ...defaultHoverScale
        };
      }
    }
  );
  function getMarkerCtor(config) {
    const type = getMarkerType(config, false);
    switch (type) {
      case "image":
      case "html":
      case "element":
        return MarkerNormal;
      case "imageLayer":
      case "videoLayer":
        return Marker3D;
      case "polygon":
      case "polyline":
      case "polygonPixels":
      case "polylinePixels":
        return MarkerPolygon;
      case "square":
      case "rect":
      case "circle":
      case "ellipse":
      case "path":
        return MarkerSvg;
      default:
        throw new import_core14.PSVError("invalid marker type");
    }
  }
  var MarkersPlugin = class extends import_core14.AbstractConfigurablePlugin {
    constructor(viewer, config) {
      super(viewer, config);
      this.markers = {};
      this.state = {
        visible: true,
        showAllTooltips: false,
        currentMarker: null,
        hoveringMarker: null,
        // require a 2nd render (only the scene) when 3d markers visibility changes
        needsReRender: false
      };
      this.container = document.createElement("div");
      this.container.className = "psv-markers";
      this.viewer.container.appendChild(this.container);
      this.svgContainer = document.createElementNS(SVG_NS, "svg");
      this.svgContainer.setAttribute("class", "psv-markers-svg-container");
      this.container.appendChild(this.svgContainer);
      this.container.addEventListener("mouseenter", this, true);
      this.container.addEventListener("mouseleave", this, true);
      this.container.addEventListener("mousemove", this, true);
      this.container.addEventListener("contextmenu", this);
    }
    /**
     * @internal
     */
    init() {
      super.init();
      import_core14.utils.checkStylesheet(this.viewer.container, "markers-plugin");
      this.viewer.addEventListener(import_core14.events.ClickEvent.type, this);
      this.viewer.addEventListener(import_core14.events.DoubleClickEvent.type, this);
      this.viewer.addEventListener(import_core14.events.RenderEvent.type, this);
      this.viewer.addEventListener(import_core14.events.ConfigChangedEvent.type, this);
      this.viewer.addEventListener(import_core14.events.ObjectEnterEvent.type, this);
      this.viewer.addEventListener(import_core14.events.ObjectHoverEvent.type, this);
      this.viewer.addEventListener(import_core14.events.ObjectLeaveEvent.type, this);
      this.viewer.addEventListener(import_core14.events.ReadyEvent.type, this, { once: true });
    }
    /**
     * @internal
     */
    destroy() {
      this.clearMarkers(false);
      this.viewer.unobserveObjects(MARKER_DATA);
      this.viewer.removeEventListener(import_core14.events.ClickEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.DoubleClickEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.RenderEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.ObjectEnterEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.ObjectHoverEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.ObjectLeaveEvent.type, this);
      this.viewer.removeEventListener(import_core14.events.ReadyEvent.type, this);
      this.viewer.container.removeChild(this.container);
      super.destroy();
    }
    /**
     * @internal
     */
    handleEvent(e) {
      switch (e.type) {
        case import_core14.events.ReadyEvent.type:
          if (this.config.markers) {
            this.setMarkers(this.config.markers);
            delete this.config.markers;
          }
          break;
        case import_core14.events.RenderEvent.type:
          this.renderMarkers();
          break;
        case import_core14.events.ClickEvent.type:
          this.__onClick(e, false);
          break;
        case import_core14.events.DoubleClickEvent.type:
          this.__onClick(e, true);
          break;
        case import_core14.events.ObjectEnterEvent.type:
        case import_core14.events.ObjectLeaveEvent.type:
        case import_core14.events.ObjectHoverEvent.type:
          if (e.userDataKey === MARKER_DATA) {
            const event = e.originalEvent;
            const marker = e.object.userData[MARKER_DATA];
            switch (e.type) {
              case import_core14.events.ObjectEnterEvent.type:
                if (marker.config.style?.cursor) {
                  this.viewer.setCursor(marker.config.style.cursor);
                } else if (marker.config.tooltip || marker.config.content) {
                  this.viewer.setCursor("pointer");
                }
                this.__onEnterMarker(event, marker);
                break;
              case import_core14.events.ObjectLeaveEvent.type:
                this.viewer.setCursor(null);
                this.__onLeaveMarker(marker);
                break;
              case import_core14.events.ObjectHoverEvent.type:
                this.__onHoverMarker(event, marker);
                break;
            }
          }
          break;
        case "mouseenter":
          this.__onEnterMarker(e, this.__getTargetMarker(e.target));
          break;
        case "mouseleave":
          this.__onLeaveMarker(this.__getTargetMarker(e.target));
          break;
        case "mousemove":
          this.__onHoverMarker(e, this.__getTargetMarker(e.target, true));
          break;
        case "contextmenu":
          e.preventDefault();
          break;
      }
    }
    /**
     * Toggles all markers
     */
    toggleAllMarkers() {
      if (this.state.visible) {
        this.hideAllMarkers();
      } else {
        this.showAllMarkers();
      }
    }
    /**
     * Shows all markers
     */
    showAllMarkers() {
      this.state.visible = true;
      this.renderMarkers();
      this.dispatchEvent(new ShowMarkersEvent());
    }
    /**
     * Hides all markers
     */
    hideAllMarkers() {
      this.state.visible = false;
      this.renderMarkers();
      this.dispatchEvent(new HideMarkersEvent());
    }
    /**
     * Toggles the visibility of all tooltips
     */
    toggleAllTooltips() {
      if (this.state.showAllTooltips) {
        this.hideAllTooltips();
      } else {
        this.showAllTooltips();
      }
    }
    /**
     *  Displays all tooltips
     */
    showAllTooltips() {
      this.state.showAllTooltips = true;
      Object.values(this.markers).forEach((marker) => {
        marker.state.staticTooltip = true;
        marker.showTooltip();
      });
    }
    /**
     * Hides all tooltips
     */
    hideAllTooltips() {
      this.state.showAllTooltips = false;
      Object.values(this.markers).forEach((marker) => {
        marker.state.staticTooltip = false;
        marker.hideTooltip();
      });
    }
    /**
     * Returns the total number of markers
     */
    getNbMarkers() {
      return Object.keys(this.markers).length;
    }
    /**
     * Returns all the markers
     */
    getMarkers() {
      return Object.values(this.markers);
    }
    /**
     * Adds a new marker to viewer
     * @throws {@link PSVError} when the marker's id is missing or already exists
     */
    addMarker(config, render = true) {
      if (this.markers[config.id]) {
        throw new import_core14.PSVError(`marker "${config.id}" already exists`);
      }
      const marker = new (getMarkerCtor(config))(this.viewer, this, config);
      if (marker.isPoly()) {
        this.svgContainer.appendChild(marker.domElement);
      } else if (marker.is3d()) {
        this.viewer.renderer.addObject(marker.threeElement.parent);
      } else {
        this.container.appendChild(marker.domElement);
      }
      this.markers[marker.id] = marker;
      if (this.state.showAllTooltips) {
        marker.state.staticTooltip = true;
      }
      if (render) {
        this.__afterChangerMarkers();
      }
    }
    /**
     * Returns the internal marker object for a marker id
     * @throws {@link PSVError} when the marker cannot be found
     */
    getMarker(markerId) {
      const id = typeof markerId === "object" ? markerId.id : markerId;
      if (!this.markers[id]) {
        throw new import_core14.PSVError(`cannot find marker "${id}"`);
      }
      return this.markers[id];
    }
    /**
     * Returns the last marker selected by the user
     */
    getCurrentMarker() {
      return this.state.currentMarker;
    }
    /**
     * Updates the existing marker with the same id
     * @description Every property can be changed but you can't change its type (Eg: `image` to `html`)
     */
    updateMarker(config, render = true) {
      const marker = this.getMarker(config.id);
      marker.update(config);
      if (render) {
        this.__afterChangerMarkers();
        if (marker === this.state.hoveringMarker && marker.config.tooltip?.trigger === "hover" || marker.state.staticTooltip) {
          marker.showTooltip();
        }
      }
    }
    /**
     * Removes a marker from the viewer
     */
    removeMarker(markerId, render = true) {
      const marker = this.getMarker(markerId);
      if (marker.isPoly()) {
        this.svgContainer.removeChild(marker.domElement);
      } else if (marker.is3d()) {
        this.viewer.renderer.removeObject(marker.threeElement.parent);
      } else {
        this.container.removeChild(marker.domElement);
      }
      if (this.state.hoveringMarker === marker) {
        this.state.hoveringMarker = null;
      }
      if (this.state.currentMarker === marker) {
        this.state.currentMarker = null;
      }
      marker.destroy();
      delete this.markers[marker.id];
      if (render) {
        this.__afterChangerMarkers();
      }
    }
    /**
     * Removes multiple markers
     */
    removeMarkers(markerIds, render = true) {
      markerIds.forEach((markerId) => this.removeMarker(markerId, false));
      if (render) {
        this.__afterChangerMarkers();
      }
    }
    /**
     * Replaces all markers
     */
    setMarkers(markers, render = true) {
      this.clearMarkers(false);
      markers?.forEach((marker) => {
        this.addMarker(marker, false);
      });
      if (render) {
        this.__afterChangerMarkers();
      }
    }
    /**
     * Removes all markers
     */
    clearMarkers(render = true) {
      Object.keys(this.markers).forEach((markerId) => {
        this.removeMarker(markerId, false);
      });
      if (render) {
        this.__afterChangerMarkers();
      }
    }
    /**
     * Rotate the view to face the marker
     */
    gotoMarker(markerId, speed = this.config.gotoMarkerSpeed) {
      const marker = this.getMarker(markerId);
      if (!speed) {
        this.viewer.rotate(marker.state.position);
        if (!import_core14.utils.isNil(marker.config.zoomLvl)) {
          this.viewer.zoom(marker.config.zoomLvl);
        }
        this.dispatchEvent(new GotoMarkerDoneEvent(marker));
        return Promise.resolve();
      } else {
        return this.viewer.animate({
          ...marker.state.position,
          zoom: marker.config.zoomLvl,
          speed
        }).then(() => {
          this.dispatchEvent(new GotoMarkerDoneEvent(marker));
        });
      }
    }
    /**
     * Hides a marker
     */
    hideMarker(markerId) {
      this.toggleMarker(markerId, false);
    }
    /**
     * Shows a marker
     */
    showMarker(markerId) {
      this.toggleMarker(markerId, true);
    }
    /**
     * Forces the display of the tooltip of a marker
     */
    showMarkerTooltip(markerId) {
      const marker = this.getMarker(markerId);
      marker.state.staticTooltip = true;
      marker.showTooltip();
    }
    /**
     * Hides the tooltip of a marker
     */
    hideMarkerTooltip(markerId) {
      const marker = this.getMarker(markerId);
      marker.state.staticTooltip = false;
      marker.hideTooltip();
    }
    /**
     * Toggles a marker visibility
     */
    toggleMarker(markerId, visible) {
      const marker = this.getMarker(markerId);
      marker.config.visible = import_core14.utils.isNil(visible) ? !marker.config.visible : visible;
      this.renderMarkers();
    }
    /**
     * Opens the panel with the content of the marker
     */
    showMarkerPanel(markerId) {
      const marker = this.getMarker(markerId);
      if (marker.config.content) {
        this.viewer.panel.show({
          id: ID_PANEL_MARKER,
          content: marker.config.content
        });
      } else {
        this.hideMarkerPanel();
      }
    }
    /**
     * Closes the panel if currently showing the content of a marker
     */
    hideMarkerPanel() {
      this.viewer.panel.hide(ID_PANEL_MARKER);
    }
    /**
     * Toggles the visibility of the list of markers
     */
    toggleMarkersList() {
      if (this.viewer.panel.isVisible(ID_PANEL_MARKERS_LIST)) {
        this.hideMarkersList();
      } else {
        this.showMarkersList();
      }
    }
    /**
     * Opens side panel with the list of markers
     */
    showMarkersList() {
      let markers = [];
      if (this.state.visible) {
        Object.values(this.markers).forEach((marker) => {
          if (marker.config.visible && !marker.config.hideList) {
            markers.push(marker);
          }
        });
      }
      const e = new RenderMarkersListEvent(markers);
      this.dispatchEvent(e);
      markers = e.markers;
      this.viewer.panel.show({
        id: ID_PANEL_MARKERS_LIST,
        content: MARKERS_LIST_TEMPLATE(markers, this.viewer.config.lang[MarkersButton.id]),
        noMargin: true,
        clickHandler: (target) => {
          const li = import_core14.utils.getClosest(target, "li");
          const markerId = li ? li.dataset[MARKER_DATA] : void 0;
          if (markerId) {
            const marker = this.getMarker(markerId);
            this.dispatchEvent(new SelectMarkerListEvent(marker));
            this.gotoMarker(marker.id);
            this.hideMarkersList();
          }
        }
      });
    }
    /**
     * Closes side panel if it contains the list of markers
     */
    hideMarkersList() {
      this.viewer.panel.hide(ID_PANEL_MARKERS_LIST);
    }
    /**
     * Updates the visibility and the position of all markers
     */
    renderMarkers() {
      if (this.state.needsReRender) {
        this.state.needsReRender = false;
        return;
      }
      const zoomLevel = this.viewer.getZoomLevel();
      const viewerPosition = this.viewer.getPosition();
      const hoveringMarker = this.state.hoveringMarker;
      Object.values(this.markers).forEach((marker) => {
        let isVisible = this.state.visible && marker.config.visible;
        let visibilityChanged = false;
        let position = null;
        if (isVisible) {
          position = marker.render({ viewerPosition, zoomLevel, hoveringMarker });
          isVisible = !!position;
        }
        visibilityChanged = marker.state.visible !== isVisible;
        marker.state.visible = isVisible;
        marker.state.position2D = position;
        if (!marker.is3d()) {
          import_core14.utils.toggleClass(marker.domElement, "psv-marker--visible", isVisible);
        }
        if (!isVisible) {
          marker.hideTooltip();
        } else if (marker.state.staticTooltip) {
          marker.showTooltip();
        } else if (marker !== this.state.hoveringMarker) {
          marker.hideTooltip();
        }
        if (visibilityChanged) {
          this.dispatchEvent(new MarkerVisibilityEvent(marker, isVisible));
          if (marker.is3d()) {
            this.state.needsReRender = true;
          }
        }
      });
      if (this.state.needsReRender) {
        this.viewer.needsUpdate();
      }
    }
    __getTargetMarker(target, closest = false) {
      if (target instanceof Node) {
        const target2 = closest ? import_core14.utils.getClosest(target, ".psv-marker") : target;
        return target2 ? target2[MARKER_DATA] : void 0;
      } else if (Array.isArray(target)) {
        return target.map((o) => o.userData[MARKER_DATA]).filter((m) => !!m).sort((a, b) => b.config.zIndex - a.config.zIndex)[0];
      } else {
        return null;
      }
    }
    /**
     * Handles mouse enter events, show the tooltip for non polygon markers
     */
    __onEnterMarker(e, marker) {
      if (marker) {
        this.state.hoveringMarker = marker;
        this.dispatchEvent(new EnterMarkerEvent(marker));
        if (marker instanceof AbstractStandardMarker) {
          marker.applyScale({
            zoomLevel: this.viewer.getZoomLevel(),
            viewerPosition: this.viewer.getPosition(),
            mouseover: true
          });
        }
        if (!marker.state.staticTooltip && marker.config.tooltip?.trigger === "hover") {
          marker.showTooltip(e.clientX, e.clientY);
        }
      }
    }
    /**
     * Handles mouse leave events, hide the tooltip
     */
    __onLeaveMarker(marker) {
      if (marker) {
        this.dispatchEvent(new LeaveMarkerEvent(marker));
        if (marker instanceof AbstractStandardMarker) {
          marker.applyScale({
            zoomLevel: this.viewer.getZoomLevel(),
            viewerPosition: this.viewer.getPosition(),
            mouseover: false
          });
        }
        this.state.hoveringMarker = null;
        if (!marker.state.staticTooltip && marker.config.tooltip?.trigger === "hover") {
          marker.hideTooltip();
        } else if (marker.state.staticTooltip) {
          marker.showTooltip();
        }
      }
    }
    /**
     * Handles mouse move events, refresh the tooltip for polygon markers
     */
    __onHoverMarker(e, marker) {
      if (marker && (marker.isPoly() || marker.is3d())) {
        if (marker.config.tooltip?.trigger === "hover") {
          marker.showTooltip(e.clientX, e.clientY);
        }
      }
    }
    /**
     * Handles mouse click events, select the marker and open the panel if necessary
     */
    __onClick(e, dblclick) {
      const threeMarker = this.__getTargetMarker(e.data.objects);
      const stdMarker = this.__getTargetMarker(e.data.target, true);
      const marker = stdMarker || threeMarker;
      if (this.state.currentMarker && this.state.currentMarker !== marker) {
        this.dispatchEvent(new UnselectMarkerEvent(this.state.currentMarker));
        this.viewer.panel.hide(ID_PANEL_MARKER);
        if (!this.state.showAllTooltips && this.state.currentMarker.config.tooltip?.trigger === "click") {
          this.hideMarkerTooltip(this.state.currentMarker.id);
        }
        this.state.currentMarker = null;
      }
      if (marker) {
        this.state.currentMarker = marker;
        this.dispatchEvent(new SelectMarkerEvent(marker, dblclick, e.data.rightclick));
        if (this.config.clickEventOnMarker) {
          e.data.marker = marker;
        } else {
          e.stopImmediatePropagation();
        }
        if (this.markers[marker.id]) {
          if (marker.config.tooltip?.trigger === "click") {
            if (marker.tooltip) {
              this.hideMarkerTooltip(marker.id);
            } else {
              this.showMarkerTooltip(marker.id);
            }
          } else {
            this.showMarkerPanel(marker.id);
          }
        }
      }
    }
    __afterChangerMarkers() {
      this.__refreshUi();
      this.__checkObjectsObserver();
      this.viewer.needsUpdate();
      this.dispatchEvent(new SetMarkersEvent(this.getMarkers()));
    }
    /**
     * Updates the visiblity of the panel and the buttons
     */
    __refreshUi() {
      const nbMarkers = Object.values(this.markers).filter((m) => !m.config.hideList).length;
      if (nbMarkers === 0) {
        if (this.viewer.panel.isVisible(ID_PANEL_MARKERS_LIST) || this.viewer.panel.isVisible(ID_PANEL_MARKER)) {
          this.viewer.panel.hide();
        }
      } else {
        if (this.viewer.panel.isVisible(ID_PANEL_MARKERS_LIST)) {
          this.showMarkersList();
        } else if (this.viewer.panel.isVisible(ID_PANEL_MARKER)) {
          this.state.currentMarker ? this.showMarkerPanel(this.state.currentMarker.id) : this.viewer.panel.hide();
        }
      }
      this.viewer.navbar.getButton(MarkersButton.id, false)?.toggle(nbMarkers > 0);
      this.viewer.navbar.getButton(MarkersListButton.id, false)?.toggle(nbMarkers > 0);
    }
    /**
     * Adds or remove the objects observer if there are 3D markers
     */
    __checkObjectsObserver() {
      const has3d = Object.values(this.markers).some((marker) => marker.is3d());
      if (has3d) {
        this.viewer.observeObjects(MARKER_DATA);
      } else {
        this.viewer.unobserveObjects(MARKER_DATA);
      }
    }
  };
  MarkersPlugin.id = "markers";
  MarkersPlugin.VERSION = "5.6.0";
  MarkersPlugin.configParser = getConfig;
  MarkersPlugin.readonlyOptions = ["markers"];

  // src/index.ts
  import_core15.DEFAULTS.lang[MarkersButton.id] = "Markers";
  import_core15.DEFAULTS.lang[MarkersListButton.id] = "Markers list";
  (0, import_core15.registerButton)(MarkersButton, "caption:left");
  (0, import_core15.registerButton)(MarkersListButton, "caption:left");
  __copyProps(__defProp(exports, "__esModule", { value: true }), src_exports);

}));//# sourceMappingURL=index.js.map