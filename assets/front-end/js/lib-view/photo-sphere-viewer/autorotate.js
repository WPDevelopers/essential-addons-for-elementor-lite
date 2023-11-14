(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('three'), require('@photo-sphere-viewer/core')) :
    typeof define === 'function' && define.amd ? define(['exports', 'three', '@photo-sphere-viewer/core'], factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory((global.PhotoSphereViewer = global.PhotoSphereViewer || {}, global.PhotoSphereViewer.AutorotatePlugin = {}), global.THREE, global.PhotoSphereViewer));
})(this, (function (exports, THREE, PhotoSphereViewer) {

/*!
 * PhotoSphereViewer.AutorotatePlugin 5.4.4
 * @copyright 2023 Damien "Mistic" Sorel
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
    AutorotatePlugin: () => AutorotatePlugin,
    events: () => events_exports
  });
  var import_core4 = require_core();

  // src/AutorotateButton.ts
  var import_core2 = require_core();

  // src/events.ts
  var events_exports = {};
  __export(events_exports, {
    AutorotateEvent: () => AutorotateEvent
  });
  var import_core = require_core();
  var _AutorotateEvent = class _AutorotateEvent extends import_core.TypedEvent {
    /** @internal */
    constructor(autorotateEnabled) {
      super(_AutorotateEvent.type);
      this.autorotateEnabled = autorotateEnabled;
    }
  };
  _AutorotateEvent.type = "autorotate";
  var AutorotateEvent = _AutorotateEvent;

  // src/icons/play-active.svg
  var play_active_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 41 41" overflow="visible"><g fill="currentColor" transform-origin="center" transform="scale(1.3)"><path d="M40.5 14.1c-.1-.1-1.2-.5-2.898-1-.102 0-.202-.1-.202-.2C34.5 6.5 28 2 20.5 2S6.6 6.5 3.7 12.9c0 .1-.1.1-.2.2-1.7.6-2.8 1-2.9 1l-.6.3v12.1l.6.2c.1 0 1.1.399 2.7.899.1 0 .2.101.2.199C6.3 34.4 12.9 39 20.5 39c7.602 0 14.102-4.6 16.9-11.1 0-.102.1-.102.199-.2 1.699-.601 2.699-1 2.801-1l.6-.3V14.3l-.5-.2zM6.701 11.5C9.7 7 14.8 4 20.5 4c5.8 0 10.9 3 13.8 7.5.2.3-.1.6-.399.5-3.799-1-8.799-2-13.6-2-4.7 0-9.5 1-13.2 2-.3.1-.5-.2-.4-.5zM25.1 20.3L18.7 24c-.3.2-.7 0-.7-.5v-7.4c0-.4.4-.6.7-.4l6.399 3.8c.301.1.301.6.001.8zm9.4 8.901A16.421 16.421 0 0 1 20.5 37c-5.9 0-11.1-3.1-14-7.898-.2-.302.1-.602.4-.5 3.9 1 8.9 2.1 13.6 2.1 5 0 9.9-1 13.602-2 .298-.1.5.198.398.499z"/></g><!--Created by Nick Bluth from the Noun Project--></svg>\n';

  // src/icons/play.svg
  var play_default = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 41 41" overflow="visible"><g fill="currentColor" transform-origin="center" transform="scale(1.3)"><path d="M40.5 14.1c-.1-.1-1.2-.5-2.899-1-.101 0-.2-.1-.2-.2C34.5 6.5 28 2 20.5 2S6.6 6.5 3.7 12.9c0 .1-.1.1-.2.2-1.7.6-2.8 1-2.9 1l-.6.3v12.1l.6.2c.1 0 1.1.4 2.7.9.1 0 .2.1.2.199C6.3 34.4 12.9 39 20.5 39c7.601 0 14.101-4.6 16.9-11.1 0-.101.1-.101.2-.2 1.699-.6 2.699-1 2.8-1l.6-.3V14.3l-.5-.2zM20.5 4c5.8 0 10.9 3 13.8 7.5.2.3-.1.6-.399.5-3.8-1-8.8-2-13.6-2-4.7 0-9.5 1-13.2 2-.3.1-.5-.2-.4-.5C9.7 7 14.8 4 20.5 4zm0 33c-5.9 0-11.1-3.1-14-7.899-.2-.301.1-.601.4-.5 3.9 1 8.9 2.1 13.6 2.1 5 0 9.9-1 13.601-2 .3-.1.5.2.399.5A16.422 16.422 0 0 1 20.5 37zm18.601-12.1c0 .1-.101.3-.2.3-2.5.9-10.4 3.6-18.4 3.6-7.1 0-15.6-2.699-18.3-3.6C2.1 25.2 2 25 2 24.9V16c0-.1.1-.3.2-.3 2.6-.9 10.6-3.6 18.2-3.6 7.5 0 15.899 2.7 18.5 3.6.1 0 .2.2.2.3v8.9z"/><path d="M18.7 24l6.4-3.7c.3-.2.3-.7 0-.8l-6.4-3.8c-.3-.2-.7 0-.7.4v7.4c0 .5.4.7.7.5z"/></g><!--Created by Nick Bluth from the Noun Project--></svg>\n';

  // src/AutorotateButton.ts
  var AutorotateButton = class extends import_core2.AbstractButton {
    constructor(navbar) {
      super(navbar, {
        className: "psv-autorotate-button",
        hoverScale: true,
        collapsable: true,
        tabbable: true,
        icon: play_default,
        iconActive: play_active_default
      });
      this.plugin = this.viewer.getPlugin("autorotate");
      this.plugin?.addEventListener(AutorotateEvent.type, this);
    }
    destroy() {
      this.plugin?.removeEventListener(AutorotateEvent.type, this);
      super.destroy();
    }
    isSupported() {
      return !!this.plugin;
    }
    handleEvent(e) {
      if (e instanceof AutorotateEvent) {
        this.toggleActive(e.autorotateEnabled);
      }
    }
    onClick() {
      if (this.plugin.isEnabled()) {
        this.plugin.config.autostartOnIdle = false;
      }
      this.plugin.toggle();
    }
  };
  AutorotateButton.id = "autorotate";

  // src/AutorotatePlugin.ts
  var import_core3 = require_core();
  var import_three = require_three();
  var getConfig = import_core3.utils.getConfigParser(
    {
      autostartDelay: 2e3,
      autostartOnIdle: true,
      autorotateSpeed: import_core3.utils.parseSpeed("2rpm"),
      autorotatePitch: null,
      autorotateZoomLvl: null,
      keypoints: null,
      startFromClosest: true
    },
    {
      autostartOnIdle: (autostartOnIdle, { rawConfig }) => {
        if (autostartOnIdle && import_core3.utils.isNil(rawConfig.autostartDelay)) {
          import_core3.utils.logWarn("autostartOnIdle requires a non null autostartDelay");
          return false;
        }
        return autostartOnIdle;
      },
      autorotateSpeed: (autorotateSpeed) => {
        return import_core3.utils.parseSpeed(autorotateSpeed);
      },
      autorotatePitch: (autorotatePitch) => {
        if (!import_core3.utils.isNil(autorotatePitch)) {
          return import_core3.utils.parseAngle(autorotatePitch, true);
        }
        return null;
      },
      autorotateZoomLvl: (autorotateZoomLvl) => {
        if (!import_core3.utils.isNil(autorotateZoomLvl)) {
          return import_three.MathUtils.clamp(autorotateZoomLvl, 0, 100);
        }
        return null;
      }
    }
  );
  var NUM_STEPS = 16;
  function serializePt(position) {
    return [position.yaw, position.pitch];
  }
  var AutorotatePlugin = class extends import_core3.AbstractConfigurablePlugin {
    constructor(viewer, config) {
      super(viewer, config);
      this.state = {
        initialStart: true,
        /** if the automatic rotation is enabled */
        enabled: false,
        /** current index in keypoints */
        idx: -1,
        /** curve between idx and idx + 1 */
        curve: [],
        /** start point of the current step */
        startStep: null,
        /** end point of the current step */
        endStep: null,
        /** start time of the current step  */
        startTime: null,
        /** expected duration of the step */
        stepDuration: null,
        /** time remaining for the pause */
        remainingPause: null,
        /** previous timestamp in render loop */
        lastTime: null,
        /** currently displayed tooltip */
        tooltip: null
      };
      this.state.initialStart = !import_core3.utils.isNil(this.config.autostartDelay);
    }
    /**
     * @internal
     */
    init() {
      super.init();
      this.video = this.viewer.getPlugin("video");
      this.markers = this.viewer.getPlugin("markers");
      if (this.config.keypoints) {
        this.setKeypoints(this.config.keypoints);
        delete this.config.keypoints;
      }
      this.viewer.addEventListener(import_core3.events.StopAllEvent.type, this);
      this.viewer.addEventListener(import_core3.events.BeforeRenderEvent.type, this);
      if (!this.video) {
        this.viewer.addEventListener(import_core3.events.KeypressEvent.type, this);
      }
    }
    /**
     * @internal
     */
    destroy() {
      this.viewer.removeEventListener(import_core3.events.StopAllEvent.type, this);
      this.viewer.removeEventListener(import_core3.events.BeforeRenderEvent.type, this);
      this.viewer.removeEventListener(import_core3.events.KeypressEvent.type, this);
      delete this.video;
      delete this.markers;
      delete this.keypoints;
      super.destroy();
    }
    /**
     * @internal
     */
    handleEvent(e) {
      switch (e.type) {
        case import_core3.events.StopAllEvent.type:
          this.stop();
          break;
        case import_core3.events.BeforeRenderEvent.type: {
          this.__beforeRender(e.timestamp);
          break;
        }
        case import_core3.events.KeypressEvent.type:
          if (e.key === import_core3.CONSTANTS.KEY_CODES.Space && this.viewer.state.keyboardEnabled) {
            this.toggle();
            e.preventDefault();
          }
          break;
      }
    }
    /**
     * Changes the keypoints
     * @throws {@link PSVError} if the configuration is invalid
     */
    setKeypoints(keypoints) {
      if (!keypoints) {
        this.keypoints = null;
      } else {
        if (keypoints.length < 2) {
          throw new import_core3.PSVError("At least two points are required");
        }
        this.keypoints = keypoints.map((pt, i) => {
          const keypoint = {
            position: null,
            markerId: null,
            pause: 0,
            tooltip: null
          };
          let position;
          if (typeof pt === "string") {
            keypoint.markerId = pt;
          } else if (import_core3.utils.isExtendedPosition(pt)) {
            position = pt;
          } else {
            keypoint.markerId = pt.markerId;
            keypoint.pause = pt.pause;
            position = pt.position;
            if (pt.tooltip && typeof pt.tooltip === "object") {
              keypoint.tooltip = pt.tooltip;
            } else if (typeof pt.tooltip === "string") {
              keypoint.tooltip = { content: pt.tooltip };
            }
          }
          if (keypoint.markerId) {
            if (!this.markers) {
              throw new import_core3.PSVError(`Keypoint #${i} references a marker but the markers plugin is not loaded`);
            }
            const marker = this.markers.getMarker(keypoint.markerId);
            keypoint.position = serializePt(marker.state.position);
          } else if (position) {
            keypoint.position = serializePt(this.viewer.dataHelper.cleanPosition(position));
          } else {
            throw new import_core3.PSVError(`Keypoint #${i} is missing marker or position`);
          }
          return keypoint;
        });
      }
      if (this.isEnabled()) {
        this.stop();
        this.start();
      }
    }
    /**
     * Checks if the automatic rotation is enabled
     */
    isEnabled() {
      return this.state.enabled;
    }
    /**
     * Starts the automatic rotation
     */
    start() {
      if (this.isEnabled()) {
        return;
      }
      this.viewer.stopAll();
      if (!this.keypoints) {
        this.__animate();
      } else if (this.config.startFromClosest) {
        this.__shiftKeypoints();
      }
      this.state.initialStart = false;
      this.state.enabled = true;
      this.dispatchEvent(new AutorotateEvent(true));
    }
    /**
     * Stops the automatic rotation
     */
    stop() {
      if (!this.isEnabled()) {
        return;
      }
      this.__hideTooltip();
      this.__reset();
      this.viewer.stopAnimation();
      this.viewer.dynamics.position.stop();
      this.viewer.dynamics.zoom.stop();
      this.state.enabled = false;
      this.dispatchEvent(new AutorotateEvent(false));
    }
    /**
     * Starts or stops the automatic rotation
     */
    toggle() {
      if (this.isEnabled()) {
        this.stop();
      } else {
        this.start();
      }
    }
    /**
     * @internal
     */
    reverse() {
      if (this.isEnabled() && !this.keypoints) {
        this.config.autorotateSpeed = -this.config.autorotateSpeed;
        this.__animate();
      }
    }
    /**
     * Launches the standard animation
     */
    __animate() {
      let p;
      if (!import_core3.utils.isNil(this.config.autorotateZoomLvl)) {
        p = this.viewer.animate({
          zoom: this.config.autorotateZoomLvl,
          // "2" is magic, and kinda related to the "PI/4" in getAnimationProperties()
          speed: `${this.viewer.config.zoomSpeed * 2}rpm`
        });
      } else {
        p = Promise.resolve(true);
      }
      p.then((done) => {
        if (done) {
          this.viewer.dynamics.position.roll(
            {
              yaw: this.config.autorotateSpeed < 0
            },
            Math.abs(this.config.autorotateSpeed / this.viewer.config.moveSpeed)
          );
          this.viewer.dynamics.position.goto(
            {
              pitch: this.config.autorotatePitch ?? this.viewer.config.defaultPitch
            },
            Math.abs(this.config.autorotateSpeed / this.viewer.config.moveSpeed)
          );
        }
      });
    }
    /**
     * Resets all the curve variables
     */
    __reset() {
      this.state.idx = -1;
      this.state.curve = [];
      this.state.startStep = null;
      this.state.endStep = null;
      this.state.startTime = null;
      this.state.stepDuration = null;
      this.state.remainingPause = null;
      this.state.lastTime = null;
      this.state.tooltip = null;
    }
    /**
     * Automatically starts if the delay is reached
     * Performs keypoints animation
     */
    __beforeRender(timestamp) {
      if ((this.state.initialStart || this.config.autostartOnIdle) && this.viewer.state.idleTime > 0 && timestamp - this.viewer.state.idleTime > this.config.autostartDelay) {
        this.start();
      }
      if (this.isEnabled() && this.keypoints) {
        if (!this.state.startTime) {
          this.state.endStep = serializePt(this.viewer.getPosition());
          this.__nextStep();
          this.state.startTime = timestamp;
          this.state.lastTime = timestamp;
        }
        this.__nextFrame(timestamp);
      }
    }
    __shiftKeypoints() {
      const currentPosition = serializePt(this.viewer.getPosition());
      const index = this.__findMinIndex(this.keypoints, (keypoint) => {
        return import_core3.utils.greatArcDistance(keypoint.position, currentPosition);
      });
      this.keypoints.push(...this.keypoints.splice(0, index));
    }
    __incrementIdx() {
      this.state.idx++;
      if (this.state.idx === this.keypoints.length) {
        this.state.idx = 0;
      }
    }
    __showTooltip() {
      const keypoint = this.keypoints[this.state.idx];
      if (keypoint.tooltip) {
        const position = this.viewer.dataHelper.vector3ToViewerCoords(this.viewer.state.direction);
        this.state.tooltip = this.viewer.createTooltip({
          content: keypoint.tooltip.content,
          position: keypoint.tooltip.position,
          top: position.y,
          left: position.x
        });
      } else if (keypoint.markerId) {
        const marker = this.markers.getMarker(keypoint.markerId);
        marker.showTooltip();
        this.state.tooltip = marker.tooltip;
      }
    }
    __hideTooltip() {
      if (this.state.tooltip) {
        const keypoint = this.keypoints[this.state.idx];
        if (keypoint.tooltip) {
          this.state.tooltip.hide();
        } else if (keypoint.markerId) {
          const marker = this.markers.getMarker(keypoint.markerId);
          marker.hideTooltip();
        }
        this.state.tooltip = null;
      }
    }
    __nextPoint() {
      const workPoints = [];
      if (this.state.idx === -1) {
        const currentPosition = serializePt(this.viewer.getPosition());
        workPoints.push(
          currentPosition,
          currentPosition,
          this.keypoints[0].position,
          this.keypoints[1].position
        );
      } else {
        for (let i = -1; i < 3; i++) {
          const keypoint = this.state.idx + i < 0 ? this.keypoints[this.keypoints.length - 1] : this.keypoints[(this.state.idx + i) % this.keypoints.length];
          workPoints.push(keypoint.position);
        }
      }
      const workVectors = [new import_three.Vector2(workPoints[0][0], workPoints[0][1])];
      let k = 0;
      for (let i = 1; i <= 3; i++) {
        const d = workPoints[i - 1][0] - workPoints[i][0];
        if (d > Math.PI) {
          k += 1;
        } else if (d < -Math.PI) {
          k -= 1;
        }
        if (k !== 0 && i === 1) {
          workVectors[0].x -= k * 2 * Math.PI;
          k = 0;
        }
        workVectors.push(new import_three.Vector2(workPoints[i][0] + k * 2 * Math.PI, workPoints[i][1]));
      }
      const curve = new import_three.SplineCurve(workVectors).getPoints(NUM_STEPS * 3).map((p) => [p.x, p.y]);
      this.state.curve = curve.slice(NUM_STEPS + 1, NUM_STEPS * 2 + 1);
      if (this.state.idx !== -1) {
        this.state.remainingPause = this.keypoints[this.state.idx].pause;
        if (this.state.remainingPause) {
          this.__showTooltip();
        } else {
          this.__incrementIdx();
        }
      } else {
        this.__incrementIdx();
      }
    }
    __nextStep() {
      if (this.state.curve.length === 0) {
        this.__nextPoint();
        this.state.endStep[0] = import_core3.utils.parseAngle(this.state.endStep[0]);
      }
      this.state.startStep = this.state.endStep;
      this.state.endStep = this.state.curve.shift();
      const distance = import_core3.utils.greatArcDistance(this.state.startStep, this.state.endStep);
      this.state.stepDuration = distance * 1e3 / Math.abs(this.config.autorotateSpeed);
      if (distance === 0) {
        this.__nextStep();
      }
    }
    __nextFrame(timestamp) {
      const ellapsed = timestamp - this.state.lastTime;
      this.state.lastTime = timestamp;
      if (this.state.remainingPause) {
        this.state.remainingPause = Math.max(0, this.state.remainingPause - ellapsed);
        if (this.state.remainingPause > 0) {
          return;
        } else {
          this.__hideTooltip();
          this.__incrementIdx();
          this.state.startTime = timestamp;
        }
      }
      let progress = (timestamp - this.state.startTime) / this.state.stepDuration;
      if (progress >= 1) {
        this.__nextStep();
        progress = 0;
        this.state.startTime = timestamp;
      }
      this.viewer.rotate({
        yaw: this.state.startStep[0] + (this.state.endStep[0] - this.state.startStep[0]) * progress,
        pitch: this.state.startStep[1] + (this.state.endStep[1] - this.state.startStep[1]) * progress
      });
    }
    __findMinIndex(array, mapper) {
      let idx = 0;
      let current = Number.MAX_VALUE;
      array.forEach((item, i) => {
        const value = mapper(item);
        if (value < current) {
          current = value;
          idx = i;
        }
      });
      return idx;
    }
  };
  AutorotatePlugin.id = "autorotate";
  AutorotatePlugin.configParser = getConfig;
  AutorotatePlugin.readonlyOptions = ["keypoints"];

  // src/index.ts
  (0, import_core4.registerButton)(AutorotateButton, "start");
  import_core4.DEFAULTS.lang[AutorotateButton.id] = "Automatic rotation";
  __copyProps(__defProp(exports, "__esModule", { value: true }), src_exports);

}));