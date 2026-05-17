# SVG Draw Widget

> Animates the strokes of an SVG icon or pasted SVG markup as if being drawn line-by-line. Three trigger modes (page load, page scroll, mouse hover), 16 easing functions in Lite (plus GSAP-only bounce / elastic / back / steps in Pro), optional fill animation (none / always / after / before), loop with restart / reverse direction, and pause-on-hover. Lite uses the native Web Animations API; Pro replaces Lite's handler with a GSAP + ScrollTrigger implementation.

**Class file:** [`includes/Elements/SVG_Draw.php`](../../includes/Elements/SVG_Draw.php)
**Slug:** `svg-draw` (widget id `eael-svg-draw`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-svg-draw/>
**Pro-shared:** ✅ Yes — but via a **unique pattern**: both Lite and Pro register handlers on the same `frontend/element_ready/eael-svg-draw.default` action with different `elementStatusCheck` flags (`eaelDrawSVG` for Lite, `eaelDrawSVGPro` for Pro). Lite passes `has_pro` in the `data-settings` JSON; Lite's JS short-circuits at line 12 (`if (settings?.has_pro) return false;`) when Pro is active so only Pro's handler runs. Pro is **not** an extension of Lite — Pro replaces Lite's animation engine entirely (GSAP + ScrollTrigger vs Web Animations API + custom scroll math).

---

## Overview

SVG Draw takes an SVG (either from Elementor's ICONS picker or pasted raw markup) and animates its strokes from invisible to fully drawn using `stroke-dasharray` / `stroke-dashoffset` transitions. Each `<path>`, `<circle>`, `<rect>`, `<polygon>` inside the SVG is animated in parallel; the trigger can be page load, scroll progress (with start / end percentages), or mouse hover.

The widget is one of two in the EA codebase where Lite and Pro both ship working but distinct JavaScript handlers for the same widget. Lite uses the browser's native Web Animations API (`Element.animate()`) — no vendor library, ~9 KB minified, with 16 cubic-bezier easings. Pro swaps in GSAP + ScrollTrigger (~70 KB) to support advanced easings (back, bounce, elastic, steps) and pixel-accurate scroll scrubbing. Lite explicitly cedes control via a runtime `has_pro` flag when Pro is detected.

## Features

- Two SVG sources: Elementor ICONS picker (icon library or uploaded SVG) or raw SVG markup pasted into a TEXTAREA
- Three animation triggers: page load, page scroll (with start / end %), mouse hover (first-time gated by `draw-initialized` class)
- 16 easings in Lite (linear, ease, ease-in/out, power1–power4 ×3 variants); Pro adds back, bounce, elastic, steps
- Four fill modes: none, always, after draw, before draw
- Configurable speed (duration in seconds) and stroke-length (% of total path)
- Loop with two directions: restart (reset and replay) or reverse (yoyo back to start)
- Configurable repeat delay between loops
- Pause on hover (mouse-hover trigger only)
- Marker debug overlay for scroll mode (editor only)
- Width, height, alignment, link, fill colour, stroke colour
- Script-tag stripping in custom SVG markup (defensive XSS guard)
- Lite-only: native Web Animations API; Pro-only: GSAP + ScrollTrigger

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Page-load, page-scroll, mouse-hover triggers | ✅ | ✅ |
| 16 cubic-bezier easings (linear, ease, power1–power4 variants) | ✅ | ✅ |
| GSAP-only easings (back, bounce, elastic, steps) | ❌ — fall back to `linear` silently | ✅ |
| Loop with restart / reverse | ✅ | ✅ |
| Pause on hover | ✅ | ✅ |
| Animation engine | Web Animations API (`Element.animate()`) | GSAP + ScrollTrigger |
| Scroll mode implementation | Custom `getBoundingClientRect()` math on `window.scroll` | GSAP ScrollTrigger with `scrub: true` |
| Scroll-mode markers in editor | — (custom math, no built-in markers) | ✅ via `ScrollTrigger.markers` |
| Page weight | ~9 KB self + 0 KB vendor | ~9 KB self + ~70 KB GSAP + ScrollTrigger |

The easings picker exposes all options regardless of Pro status — Lite users selecting `back.inOut(2)` get `linear` silently. There is **no Pro upsell panel** and **no in-picker lock icon**; the failure mode is invisible (the animation plays, just with the wrong easing).

## Use Cases

- Animated logo reveal on page load
- "Hand-drawn" decorative SVG that draws itself as the user scrolls past
- Hover-to-draw icon — first hover triggers the draw, subsequent hovers are no-ops
- Process step illustration where each stroke represents a step
- Mathematical / scientific diagrams where the stroke order matters

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/SVG_Draw.php`](../../includes/Elements/SVG_Draw.php) | PHP widget class — controls, render, default custom SVG, script-tag sanitisation |
| [`src/css/view/svg-draw.scss`](../../src/css/view/svg-draw.scss) | Source styles — base SVG styling with `stroke-dasharray: 4000000` initial hide + `eaelFillIn` keyframe |
| [`src/js/view/svg-draw.js`](../../src/js/view/svg-draw.js) | Frontend logic (Lite) — Web Animations API, three trigger branches, `has_pro` short-circuit |
| [`config.php`](../../config.php#L1318) entry `'svg-draw'` | `Asset_Builder` dependency declaration (CSS + JS) |
| `assets/front-end/css/view/svg-draw.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/svg-draw.min.js` | Built output (do not edit) |

When Pro is active, an additional Pro-side file kicks in:

| File | Role |
| ---- | ---- |
| `essential-addons-elementor/src/js/view/svg-draw.js` (Pro) | Pro's GSAP-based animation handler — registers on the same action key with a different `elementStatusCheck` flag |

## Architecture

- **Lite uses Web Animations API; Pro uses GSAP** — Lite's `SVGDraw($scope, $)` calls `element.animate({ strokeDashoffset: [from, to] }, { duration, easing, fill: 'forwards' })` directly on each `<path>`. Pro's `SVGDrawPro($scope, $)` calls `gsap.timeline().to(lines, …)` instead. Same DOM, same data-settings, different animation engine.
- **Both register on `frontend/element_ready/eael-svg-draw.default`** — Lite uses `elementStatusCheck('eaelDrawSVG')`, Pro uses `elementStatusCheck('eaelDrawSVGPro')`. Both handlers register independently. Lite's handler reads the `has_pro` flag from `data-settings` and short-circuits with `return false;` when Pro is detected ([line 12-14 of svg-draw.js](../../src/js/view/svg-draw.js#L12)). This pattern lets Pro override Lite's behaviour without Pro touching the Lite class.
- **PHP passes `has_pro` flag in `data-settings`** — `render()` calls `apply_filters('eael/pro_enabled', false)` and includes the result as `has_pro` in the JSON-encoded settings ([line 622](../../includes/Elements/SVG_Draw.php#L622)). Without this flag, Lite's and Pro's handlers would both run, double-animating.
- **Three trigger modes branch on container class** — `render()` adds one of `page-load`, `page-scroll`, `mouse-hover` to the `.eael-svg-draw-container`; JS reads the class via `wrapper.hasClass(...)` to choose the branch. No JSON option — the class drives behaviour.
- **Initial hide via `stroke-dasharray: 4000000`** — SCSS sets a deliberately huge default `stroke-dasharray` and `stroke-dashoffset` on every `<path>` / `<circle>` / `<rect>` / `<polygon>` so they render invisible before JS runs. JS overwrites with `getTotalLength() * stroke_length%`, so subsequent animations work on the real path geometry.
- **`fill-svg` class triggers a CSS keyframe** for the "before draw" fill case — emitted in PHP when `fill_type === 'before'` ([line 604](../../includes/Elements/SVG_Draw.php#L604)). The keyframe `eaelFillIn` is CSS-only (0 → 100% fill-opacity), separate from the JS fill animation.
- **Script-tag stripping on custom SVG** — `render()` runs two `preg_replace` patterns to strip `<script>…</script>` and unclosed `<script>` tags from user-supplied SVG markup ([line 599](../../includes/Elements/SVG_Draw.php#L599)). The rest of the SVG content is emitted **unescaped** via `printf('%s', $svg_html)` — authoring-time trust model with this targeted defence.
- **Scroll mode is custom math, not IntersectionObserver** — despite the comment "Scroll-based animation using Intersection Observer with custom logic" ([line 241 of svg-draw.js](../../src/js/view/svg-draw.js#L241)), the implementation actually uses `window.addEventListener('scroll', …, { passive: true })` with `getBoundingClientRect()` on every scroll. Comment is misleading. Pro's GSAP ScrollTrigger version is the proper scroll observer.
- **Speed control fallback chain** — `render()` checks new control `eael_svg_drawing_speed` first, falls back to legacy `eael_svg_draw_speed * 0.05`, defaults to `1` ([lines 626-632](../../includes/Elements/SVG_Draw.php#L626)). Legacy `eael_svg_draw_speed` survives for back-compat with widgets saved before the new control.

## Render Output

The widget produces a simple structure; the heavy lifting happens in the embedded SVG and the JS-driven animations.

```html
[?] <a href="…">    <!-- when link is set -->
  <div class="eael-svg-draw-container page-load [fill-svg]"
       data-settings='{"fill_type":"after","fill_color":"#000","speed":1,"loop":"yes",
                       "loop_delay":1.5,"direction":"reverse","ease_type":"power3.inOut",
                       "stroke_length":100,"has_pro":false,...}'>
    <!-- SVG content emitted from one of three paths: -->
    <!-- 1. ICONS picker, SVG library: Icons_Manager::render_icon() output -->
    <!-- 2. ICONS picker, other library: Helper::get_svg_by_icon() output -->
    <!-- 3. Custom SVG markup: $svg_html (with <script> stripped, otherwise unescaped) -->
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 74 74">
      <path d="M..." stroke="#2c3e50" stroke-width="1.5"
            style="stroke-dasharray: 4000000; stroke-dashoffset: 4000000;"></path>
      …
    </svg>
  </div>
[?] </a>
```

Notes:

- `.eael-svg-draw-container` always has the trigger-mode class (`page-load`, `page-scroll`, or `mouse-hover`).
- `fill-svg` class is added when `fill_type === 'before'`; CSS keyframe `eaelFillIn` runs immediately on render.
- `data-settings` JSON contains all runtime config — fill type, fill colour, speed, loop, direction, easing, stroke length, scroll start/end points, marker flag, transition time, plus the `has_pro` runtime flag.
- For mouse-hover trigger, the wrapper later gets a `draw-initialized` class (added by JS) to prevent re-init on subsequent hovers.
- `stroke-dasharray: 4000000` / `stroke-dashoffset: 4000000` in SCSS hides the SVG before JS runs; JS replaces these with real `getTotalLength()` values.
- The default custom SVG (medical-research icon, defined in [`default_custom_svg()`](../../includes/Elements/SVG_Draw.php#L62)) is emitted when the user picks "Custom SVG" source but leaves the textarea empty.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/SVG_Draw.php#L77) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_svg_src` | SELECT | `icon` | Content → General | Selects between Elementor ICONS picker and custom SVG markup |
| `eael_svg_icon` | ICONS | bundled `svg-draw.svg` | Content → General | Icon glyph or uploaded SVG (icon source only) |
| `svg_html` | TEXTAREA | sample SVG | Content → General | Raw SVG markup (custom source only) |
| `eael_svg_exclude_style` | SWITCHER | `no` | Content → General | Strips inline `<style>` from SVG source (passed to JS via `excludeStyle`) |
| `eael_svg_width` / `_height` | SLIDER (responsive) | `200px` / `200px` | Content → General | `width` / `height` on `<svg>` |
| `eael_svg_alignment` | CHOOSE | `center` | Content → General | `text-align` on `.eael-svg-draw-container` |
| `eael_svg_link` | URL | empty | Content → General | Wraps the wrapper in `<a href>` |
| `eael_svg_fill` | SELECT | `none` | Content → Appearance | Fill mode — none / always / after / before |
| `eael_svg_fill_transition` | NUMBER (seconds) | `1` | Content → Appearance | Fill animation duration |
| `eael_svg_animation_on` | SELECT | `page-load` | Content → Appearance | Trigger mode — none / page-load / page-scroll / mouse-hover |
| `eael_svg_animation_type` | SELECT | `none` | Content → Appearance | Easing — 14 GSAP-style options (some Pro-only at runtime) |
| `eael_show_marker` | SWITCHER | empty | Content → Appearance | Editor-only debug markers (scroll mode only); only meaningful with Pro's ScrollTrigger |
| `eael_svg_draw_start_point` / `_end_point` | SLIDER (% 0–100) | `0%` / `10%` | Content → Appearance | Scroll start / end position |
| `eael_svg_pause_on_hover` | SWITCHER | `yes` | Content → Appearance | Pauses the animation when mouse leaves the SVG (mouse-hover trigger only) |
| `eael_svg_loop` | SWITCHER | `yes` | Content → Appearance | Loops the animation indefinitely |
| `eael_svg_loop_delay` | NUMBER (seconds) | `1.5` | Content → Appearance | Delay between loop iterations |
| `eael_svg_animation_direction` | SELECT | `reverse` | Content → Appearance | Loop direction — restart (reset and replay) or reverse (yoyo) |
| `eael_svg_drawing_speed` | NUMBER (seconds) | empty (defaults to `1`) | Content → Appearance | Draw duration; legacy `eael_svg_draw_speed × 0.05` fallback |
| `eael_svg_stroke_dash_adjustment` | SLIDER (% 10–100) | `100%` | Content → Appearance | What fraction of the path is animated (100% = full path) |
| Style → Style section | various | — | Style → Style | Stroke colour, stroke width, fill colour, background |

### Easing options exposed in the panel

The `eael_svg_animation_type` picker exposes 14 options. Of these, **9 are Lite-supported** (mapped to cubic-bezier in `easingFunctions`); the others fall back to `linear` silently in Lite:

| Option label | Picker value | Lite supported? |
| ------------ | ------------ | --------------- |
| Power | `power3.inOut` | ✅ |
| Power In | `power3.in` | ✅ |
| Power Out | `power3.out` | ✅ |
| Back | `back.inOut(2)` | ❌ Pro only (falls back to `linear` in Lite) |
| Back In | `back.in(2)` | ❌ |
| Back Out | `back.out(2)` | ❌ |
| Bounce | `bounce.inOut` | ❌ |
| Bounce In | `bounce.in` | ❌ |
| Bounce Out | `bounce.out` | ❌ |
| Elastic | `elastic.inOut(1,0.4)` | ❌ |
| Elastic In | `elastic.in(1,0.4)` | ❌ |
| Elastic Out (label typo: "Elastic 0ut") | `elastic.out(1,0.4)` | ❌ |
| Steps | `steps(50)` | ❌ |
| None | `none` | ✅ (maps to `linear`) |

⚠️ Label typo `Elastic 0ut` (zero instead of letter O) at [line 298](../../includes/Elements/SVG_Draw.php#L298).

## Conditional Dependencies

```text
# General section
eael_svg_icon                    → visible when eael_svg_src == 'icon'
svg_html                         → visible when eael_svg_src == 'custom'

# Appearance section
eael_svg_animation_type          → visible when eael_svg_animation_on NOT in ['none', 'page-scroll']
eael_show_marker / eael_svg_draw_start_point / eael_svg_draw_end_point
                                 → visible when eael_svg_animation_on == 'page-scroll'
eael_svg_pause_on_hover          → visible when eael_svg_animation_on == 'mouse-hover'
eael_svg_loop                    → visible when eael_svg_animation_on NOT in ['page-scroll', 'none']
eael_svg_loop_delay              → visible when ... AND eael_svg_loop == 'yes'
eael_svg_animation_direction     → visible when ... AND eael_svg_loop == 'yes'
eael_svg_drawing_speed           → visible when eael_svg_animation_on NOT in ['none', 'page-scroll']
eael_svg_stroke_dash_adjustment  → visible when eael_svg_animation_on NOT in ['none', 'page-scroll']
```

No `eael_section_pro` upsell panel.

## Behavior Flow

1. User drops the widget → `register_controls()` runs. The easing picker shows all 14 options regardless of Pro status.
2. User picks SVG source (icon library or custom markup), configures animation trigger / easing / loop / fill settings.
3. Editor preview re-renders via [`render()`](../../includes/Elements/SVG_Draw.php#L597).
4. `render()` strips `<script>` tags from custom SVG markup, builds the wrapper class list (`eael-svg-draw-container` + trigger-mode + optional `fill-svg`), and writes the JSON-encoded settings to `data-settings`.
5. `render()` checks `apply_filters('eael/pro_enabled', false)` and adds `has_pro` to the settings JSON.
6. Per source: icon library → `Icons_Manager::render_icon()`; uploaded SVG → `Icons_Manager::render_icon()` (svg library mode); other icon library → `Helper::get_svg_by_icon()`; custom markup → raw `printf` after script-tag stripping.
7. Browser receives static HTML with `<svg>` containing `<path>` / `<circle>` etc., each with `stroke-dasharray: 4000000; stroke-dashoffset: 4000000;` from SCSS (hidden by default).
8. Elementor's `frontend/init` fires.
9. Lite's `SVGDraw` registers via `addAction('frontend/element_ready/eael-svg-draw.default', SVGDraw)` with guard `elementStatusCheck('eaelDrawSVG')`.
10. Pro's `SVGDrawPro` (if Pro active) registers the same way with guard `elementStatusCheck('eaelDrawSVGPro')`. Both register; both fire per widget.
11. Per widget, both handlers run. Lite's reads `data-settings.has_pro`; when `true`, returns early at line 12 — only Pro's runs. When `false`, Lite's continues.
12. Lite branch on `wrapper.hasClass(...)`:
    - **page-load**: immediately calls `drawSVGLine()`. Computes `path.getTotalLength() * stroke_length%` per path; sets `strokeDasharray` and `strokeDashoffset`; calls `line.animate({ strokeDashoffset: [from, 0] }, …)`.
    - **mouse-hover**: binds `hover()` on the SVG; on first hover, calls `drawSVGLine()` and adds `draw-initialized` class to prevent re-init.
    - **page-scroll**: binds `scroll` listener on `window` (`passive: true`); on each scroll, computes progress between start and end points, sets `strokeDashoffset = length * (1 - progress)`. Custom math, no real `IntersectionObserver`.
13. Fill animation runs at one of four phases:
    - **always**: animates fill on widget init (immediately).
    - **before**: animates fill before the draw starts (and again on restart).
    - **after**: animates fill after the draw completes.
    - **none**: no fill animation.
14. Loop: when enabled, sets timeout for `loop_delay` seconds after the draw completes, then either resets and replays (`restart` direction) or animates back to start (`reverse` direction).
15. Pause on hover (mouse-hover only): hover events call `animation.pause()` / `.play()` on each path animation.

## JavaScript Lifecycle

- **Trigger (Lite):** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-svg-draw.default', SVGDraw)`
- **Trigger (Pro):** same action, separate handler `SVGDrawPro` — both fire; Lite short-circuits on `has_pro`
- **Guard:** `if (eael.elementStatusCheck('eaelDrawSVG')) return false;` prevents Lite double-registration on re-fired `elementor/frontend/init`. Pro uses `eaelDrawSVGPro` flag — separate guard.
- **Reads on init:** `$scope` for wrapper, `wrapper.data('settings')` for JSON config, `$('path, circle, rect, polygon', svg_icon)` for the elements to animate.
- **Animation engine (Lite):** native Web Animations API — `Element.animate({ strokeDashoffset: [from, to] }, { duration, easing, fill: 'forwards' })`. Returns an `Animation` object with `.onfinish`, `.pause()`, `.play()`.
- **Animation engine (Pro):** GSAP timeline — `gsap.timeline({ repeat, yoyo, repeatDelay }).to(lines, { strokeDashoffset: 0, duration, ease, onComplete, onStart })`. ScrollTrigger plugin for scroll mode.
- **Easing translation (Lite only):** `easingFunctions` constant maps 16 names to cubic-beziers; unknown names fall back to `linear`. Pro passes the GSAP name directly to `gsap.to({ ease })`.
- **Trigger branches:** `page-load` → immediate; `mouse-hover` → `svg_icon.hover(callback)` first-time gate; `page-scroll` → `window.addEventListener('scroll', handler, { passive: true })`.
- **Scroll-mode cleanup:** `wrapper.on('remove', () => window.removeEventListener('scroll', scrollHandler))` — removes the listener when Elementor removes the widget from the DOM (e.g. editor delete).
- **Pause on hover:** stores all `Animation` objects in an `animations[]` array; hover handlers iterate and call `.pause()` / `.play()` on each.
- **`isPaused` flag:** prevents `onfinish` callbacks from firing loop / fill-after handlers when the animation was paused mid-flight.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one SVG Draw widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `svg-draw.min.css` | self (built from `src/css/view/svg-draw.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `svg-draw.min.js` (Lite) | self | Web Animations API engine, three trigger branches, `has_pro` short-circuit | Always when widget present |
| `svg-draw.min.js` (Pro) | `essential-addons-elementor/assets/...` | GSAP timeline + ScrollTrigger engine | Loaded by Pro plugin when Pro is active |

Pro depends on GSAP + ScrollTrigger being available globally (`window.gsap`, `window.ScrollTrigger`); Pro logs a console warning if either is missing for scroll mode. GSAP is shared across Pro widgets (not bundled per-widget); it loads ~70 KB once per page.

## Hooks & Filters

The widget consumes one filter and emits no widget-specific hooks. The Pro / Lite handoff happens via the `has_pro` runtime flag in `data-settings`, not via a PHP hook.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Sets the `has_pro` flag in `data-settings`; Lite's JS short-circuits when true ([line 622](../../includes/Elements/SVG_Draw.php#L622)) |

No widget-specific `eael/svg_draw/*` extension hook exists. Theme customisation is via CSS overrides or via the public global-action key (override the action handler via `addAction` with higher priority).

## Customization Recipes

### Recipe 1 — Add a custom easing function via theme JS

```js
jQuery( window ).on( 'elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/eael-svg-draw.default',
        function ( $scope ) {
            const wrapper = $scope.find('.eael-svg-draw-container');
            const settings = wrapper.data('settings');
            if (settings.has_pro) return;  // let Pro handle if active

            // Override the wrapper's data-settings to inject a custom easing
            settings.ease_type = 'cubic-bezier(0.68, -0.55, 0.265, 1.55)';  // back-out
            wrapper.data('settings', settings);
        },
        5  // priority lower than EA's default — runs first
    );
} );
```

The widget reads `settings.ease_type` directly; any CSS cubic-bezier string passed through Lite's `easingFunctions` map falls back to `linear` unless it's an exact match — but the Web Animations API accepts cubic-bezier strings directly, so override at the JS API level.

### Recipe 2 — Override stroke colour and stroke width on hover

```scss
.eael-svg-draw-container:hover svg path,
.eael-svg-draw-container:hover svg circle {
    stroke: #ff5722 !important;
    stroke-width: 2.5 !important;
    transition: stroke 0.3s, stroke-width 0.3s;
}
```

The widget exposes stroke colour and width in the Style tab, but no built-in hover state. Theme CSS adds it cleanly.

### Recipe 3 — Trigger the animation on a custom event

```js
document.addEventListener('my-custom-trigger', function (event) {
    const $svgDraw = jQuery(event.detail.target).find('.eael-svg-draw-container');
    if (!$svgDraw.length || $svgDraw.hasClass('draw-initialized')) return;

    // Manually trigger by adding the page-load class and re-running
    $svgDraw.removeClass('mouse-hover page-scroll').addClass('page-load draw-initialized');
    // Need to re-trigger the handler — easiest is to dispatch the Elementor event
    elementorFrontend.hooks.doAction(
        'frontend/element_ready/eael-svg-draw.default',
        $svgDraw.closest('.elementor-widget')
    );
});
```

⚠️ Re-dispatching the Elementor event is brittle. Cleaner alternative: read the settings and call the Web Animations API directly on the paths.

### Recipe 4 — Pause all SVG Draws on the page programmatically

```js
window.eaelPauseAllSVGDraws = function () {
    document.querySelectorAll('.eael-svg-draw-container svg path, .eael-svg-draw-container svg circle')
        .forEach(function (el) {
            el.getAnimations().forEach(function (anim) {
                anim.pause();
            });
        });
};

window.eaelPlayAllSVGDraws = function () {
    document.querySelectorAll('.eael-svg-draw-container svg path, .eael-svg-draw-container svg circle')
        .forEach(function (el) {
            el.getAnimations().forEach(function (anim) {
                anim.play();
            });
        });
};
```

Uses the Web Animations API's `Element.getAnimations()` to find every running animation on the page. Useful for pausing decorative animations during user input or page transitions.

## Common Issues

### Animation doesn't start on page load

- **Likely cause:** the SVG has no `<path>` / `<circle>` / `<rect>` / `<polygon>` elements — the JS query returns empty; or `eael_svg_animation_on` is set to `none`
- **Diagnose:** inspect the rendered `<svg>` — does it have path elements? Check the wrapper class — does it have `page-load`?
- **Fix:** pick an icon with at least one path; or paste valid SVG markup; or switch animation trigger to a non-`none` value

### Easing options like Back / Bounce / Elastic produce a linear animation

- **Likely cause:** Pro is not active; Lite's `easingFunctions` map doesn't include GSAP-specific easings — they fall back to `linear` silently
- **Diagnose:** Check if Pro plugin is active; browser console for warnings (none — fail is silent)
- **Fix:** activate Pro; or pick one of the Lite-supported easings (linear, ease, power1–power4 variants)

### Scroll-mode markers don't appear in editor

- **Likely cause:** Pro is not active — Lite's scroll handler doesn't use real ScrollTrigger, so the marker control has no effect; the control is conditional on `page-scroll` mode and the marker switch, but visualises nothing in Lite
- **Diagnose:** Pro plugin status
- **Fix:** activate Pro; or rely on the start / end percentage values without visual debug markers

### Animation flickers / stutters on scroll

- **Likely cause:** scroll mode in Lite uses `window.addEventListener('scroll', …)` with `passive: true`, but the handler runs on every scroll event — fast scrolls can produce 60+ events per second, recomputing `getBoundingClientRect()` each time
- **Diagnose:** in Performance tab record a scroll session; how many handler invocations?
- **Fix:** Pro's GSAP ScrollTrigger version uses `scrub: true` which optimises this; for Lite, theme CSS can `transform: translateZ(0)` the wrapper to create a GPU layer

### Custom SVG with `<script>` tags renders but the script doesn't run

- **Likely cause:** by design — `render()` strips `<script>` tags via `preg_replace` to prevent XSS in authoring contexts
- **Diagnose:** inspect the rendered HTML — is the `<script>` tag missing?
- **Fix:** intended security behaviour; if you need interactivity, hook the SVG with theme JS instead

### Loop doesn't reverse — instead resets and replays

- **Likely cause:** `eael_svg_animation_direction` is set to `restart` (default for many widgets); switch to `reverse` for the yoyo behaviour
- **Diagnose:** check the direction control in the panel
- **Fix:** set `Direction` to `Reverse`

### Hover trigger draws once and never again

- **Likely cause:** by design — first hover adds `draw-initialized` class to the wrapper; subsequent hovers check `if (!wrapper.hasClass('draw-initialized'))` and skip
- **Diagnose:** inspect the wrapper class list after first hover — `draw-initialized` should be present
- **Fix:** by design; for re-draw on every hover, remove the class via theme JS on `mouseleave`

### Multiple SVG Draws on same page conflict during scroll

- **Likely cause:** all of them bind their own scroll listener; they don't share computation. Each does its own `getBoundingClientRect` per scroll event
- **Diagnose:** Performance tab — count scroll listener invocations
- **Fix:** Pro's GSAP ScrollTrigger version coordinates via a single observer; Lite version has no shared throttling

### Path Length set to 50% — the SVG draws partially but the line keeps going

- **Likely cause:** `stroke_length` controls what fraction of the path is *animated*, but `stroke-dasharray` is set to `length * stroke_length%`; a 50% setting means the dash pattern is half the path length, so the line draws to 50% then visually disappears (because of dash gap)
- **Diagnose:** by design — the slider controls the dash size, not the draw distance
- **Fix:** keep at 100% for the full-path animation; lower values produce partial reveals deliberately

## Testing Checklist

- [ ] Drop at default — bundled `svg-draw.svg` icon renders at 200×200; page-load animation draws the strokes
- [ ] Switch source to Custom SVG — paste a `<svg>...</svg>` snippet; renders correctly
- [ ] Paste an SVG with `<script>` tags — script is stripped; the rest of the SVG renders
- [ ] Switch animation trigger through none / page-load / page-scroll / mouse-hover — wrapper class updates
- [ ] Page-load mode — animation starts on render; loops with chosen direction; pauses on hover (if enabled)
- [ ] Mouse-hover mode — first hover triggers draw; subsequent hovers do nothing; wrapper has `draw-initialized` class after first
- [ ] Page-scroll mode — strokes draw progressively as the user scrolls; reaches 100% at `end_point` position
- [ ] Each of the 14 easings — Power-* render with cubic-bezier; Back / Bounce / Elastic / Steps in Lite fall back to linear (verify with stopwatch or DevTools)
- [ ] Activate Pro — same controls produce GSAP-accurate animations; Back / Bounce / Elastic / Steps work
- [ ] Fill mode none — strokes draw with no fill animation
- [ ] Fill mode always — fill animates on widget init (immediately on render)
- [ ] Fill mode after — fill animates after draw completes
- [ ] Fill mode before — fill animates first, then draw runs; `fill-svg` class on wrapper
- [ ] Loop on with restart direction — draw resets and replays after `loop_delay`
- [ ] Loop on with reverse direction — draw runs forward, then backward (yoyo)
- [ ] Pause on hover — animation freezes when mouse enters SVG, resumes on leave
- [ ] Stroke length 50% — partial draw; stroke disappears at 50%
- [ ] Width / height / alignment controls — `<svg>` size updates; container alignment updates
- [ ] Link control — wrapper wraps in `<a href>`; click navigates
- [ ] Multiple SVG Draws on same page — each animates independently; verify scroll listener count in DevTools
- [ ] Re-fired `elementor/frontend/init` (popup / SPA nav) — `elementStatusCheck` prevents double-registration
- [ ] Editor delete — `wrapper.on('remove')` should remove the scroll listener (verify via Memory tab — listener should drop)
- [ ] Special characters in custom SVG markup — verify the SVG renders; the script-stripping regex doesn't break other tags
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Both Lite and Pro register handlers on the same action key with different status flags

- **Context:** SVG Draw is one of the most animation-heavy widgets; the visual difference between Web Animations and GSAP-driven animation is significant for advanced easings.
- **Decision:** Lite registers `SVGDraw` with `elementStatusCheck('eaelDrawSVG')`; Pro registers `SVGDrawPro` with `elementStatusCheck('eaelDrawSVGPro')`. Lite's handler reads the runtime `has_pro` flag from `data-settings` and returns early when true.
- **Alternatives rejected:** Pro removes Lite's action via `removeAction` — fragile, order-dependent; Pro extends Lite class — Lite would need to be aware of the extension at PHP load; share one handler — Lite would carry GSAP as a dependency forever.
- **Consequences:** clean separation. Lite ships without GSAP; Pro ships GSAP separately. Both handlers register without coordination; Lite cedes at runtime via the `has_pro` flag. The flag is the only PHP-side contract between Lite and Pro for this widget.

### Web Animations API instead of GSAP in Lite

- **Context:** GSAP is ~70 KB minified; native Web Animations API is built into browsers (Safari 13.1+, all evergreen browsers).
- **Decision:** Lite uses `Element.animate()` with cubic-bezier easing strings; supports 16 easings (linear, ease, power1–power4 ×3).
- **Alternatives rejected:** bundle GSAP for Lite — 70 KB cost on every page with SVG Draw, even when no advanced easing is used; load GSAP on-demand — adds latency to first animation.
- **Consequences:** Lite is ~9 KB self only; Pro adds 70 KB GSAP shared across Pro widgets; users who want bounce / elastic / back must upgrade to Pro. Lite's easings cover 90% of typical use cases.

### Initial hide via `stroke-dasharray: 4000000`

- **Context:** SVG paths render fully visible by default; without prep, the initial paint shows the complete path before JS can hide it.
- **Decision:** SCSS sets `stroke-dasharray: 4000000; stroke-dashoffset: 4000000;` on all path-like elements. JS replaces with `getTotalLength() * stroke_length%` on init.
- **Alternatives rejected:** JS hides via `opacity: 0` until init — flash of styled content on slow JS load; `visibility: hidden` initially — same problem.
- **Consequences:** SVGs with intrinsic path lengths > 4 million units are mishandled (extremely rare). All standard SVGs work; the magic-number SCSS rule is non-obvious but effective.

### Script-tag stripping, rest of SVG markup emitted unescaped

- **Context:** users paste raw SVG markup; `<script>` inside SVG runs in the document context — XSS vector.
- **Decision:** `preg_replace` strips `<script>…</script>` and unclosed `<script>` tags before emit. Rest of the SVG (paths, styles, attributes) is emitted via `printf('%s', $svg_html)` unescaped.
- **Alternatives rejected:** `wp_kses` on the whole SVG — strips many valid SVG attributes (`viewBox`, `xmlns`, etc.) without an extensive allowlist; sanitise via DOMDocument — heavy, may break complex SVG.
- **Consequences:** authoring-time trust model; `<script>` is the only blocked vector. Other XSS vectors (e.g. `<foreignObject>` with HTML content, `on*` event handlers in SVG) are NOT stripped — a known limitation.

### Custom scroll math instead of `IntersectionObserver`

- **Context:** scroll-progress requires not just "is in viewport" but "what % between start and end points" — IntersectionObserver alone doesn't compute progress.
- **Decision:** Lite uses `window.addEventListener('scroll', handler, { passive: true })` and computes progress via `getBoundingClientRect()` on every scroll event.
- **Alternatives rejected:** IntersectionObserver with multiple thresholds (e.g. 100 thresholds) — works but is verbose; rely on `requestAnimationFrame` — same overhead.
- **Consequences:** every SVG Draw with scroll mode adds a scroll listener (no shared throttling); fast scrolls produce 60+ handler runs per second. Pro's GSAP ScrollTrigger version coordinates internally — better perf for scroll-heavy pages.

## Known Limitations

- **Easings that fail silently** — Back / Bounce / Elastic / Steps are exposed in the Lite picker but fall back to `linear` without warning. Pro provides the real implementation; Lite users see broken animations as merely fast linear ones.
- **No `eael_section_pro` upsell panel** — Pro discovery is implicit via the easing picker (Lite users see "Back / Bounce / Elastic" options without lock indicators). Users may not realise these require Pro.
- **Label typo `Elastic 0ut`** (zero instead of O) at [line 298](../../includes/Elements/SVG_Draw.php#L298) of `SVG_Draw.php`. Visible in the panel.
- **Misleading code comment** — Lite's scroll handler claims "Intersection Observer with custom logic" but is actually a scroll-event listener with custom math.
- **Per-widget scroll listener with no shared throttling** — multiple SVG Draws in scroll mode multiply the scroll handler load.
- **Marker control has no effect in Lite** — only meaningful with Pro's ScrollTrigger.
- **`stroke_length` slider's behaviour is non-obvious** — controls dash pattern size, not visible draw extent. 50% setting produces a half-line that disappears at 50%.
- **`Path Length: 4000000` SCSS hardcode** — paths with intrinsic length > 4 million units (extremely rare) won't be hidden by the initial dash. No mitigation in source.
- **Hover-trigger first-draw-only is by design** — repeated hovers don't re-trigger. No control to make it loop on every hover; manual class removal via theme JS required.
- **Script-stripping is regex-based, not DOM-parsed** — doesn't handle SVG-specific XSS vectors like `<foreignObject>` with HTML or `on*` event handlers on SVG elements.
- **`Helper::get_svg_by_icon()` vs `Icons_Manager::render_icon()` branching** — three rendering paths for icon source (svg library / other library / custom), maintenance complexity.
- **Pro / Lite double-registration** — both register on the same action key; Lite explicitly cedes via `has_pro` short-circuit. If a third party hooks the same action without checking `has_pro`, they'll double-fire.
- **Custom-SVG textarea has no syntax validation** — malformed SVG markup renders as-is; the browser may show partial output or nothing.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
