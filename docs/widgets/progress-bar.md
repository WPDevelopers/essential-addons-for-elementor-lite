# Progress Bar Widget

> Animated progress indicator triggered when the element scrolls into view. Three layouts in Lite (line, circle, half-circle); Pro adds line-rainbow, circle-fill, half-circle-fill, and box variants via a rich hook chain. Counter number animates via jQuery `.animate()` step callback; CSS transitions handle the bar fill / pie-chart rotation. Uses `inview` vendor library for viewport-entry detection.

**Class file:** [`includes/Elements/Progress_Bar.php`](../../includes/Elements/Progress_Bar.php)
**Slug:** `progress-bar` (widget id `eael-progress-bar`)
**Public docs:** <https://essential-addons.com/elementor/docs/progress-bar/>
**Pro-shared:** ✅ Yes — Pro extends via 7 hooks (1 filter for layout picker, 3 filters for wrapper-class expansion, 2 un-prefixed legacy actions for control / block injection, plus the standard `eael/pro_enabled` gate). Pro adds 4 new layouts (`line_rainbow`, `circle_fill`, `half_circle_fill`, `box`) without subclassing.

---

## Overview

Progress Bar shows a value from 0–100 as a horizontal bar, a circle (full or half), or one of four Pro-only variants. When the wrapper enters the viewport, the bar fills and the counter number animates from 0 to the target value over a configurable duration. The line layout uses `width %` transition; the circle layout uses pie-chart-style transform rotations on two half-circles; the half-circle layout uses a single transform rotation on a single half. Counter text animation runs via jQuery `.animate({counter: $num}, { step })`, decoupled from the visual fill so the text and bar progress in sync visually but via different mechanisms.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Line layout | ✅ | ✅ |
| Circle (full) | ✅ | ✅ |
| Half-circle | ✅ | ✅ |
| Line Rainbow | ❌ — force-fallback to `line` in `render()` | ✅ via `eael_progressbar_rainbow_wrap_class` filter |
| Circle Fill | ❌ — force-fallback to `line` | ✅ via `eael_progressbar_circle_fill_wrap_class` filter |
| Half Circle Fill | ❌ — force-fallback to `line` | ✅ via `eael_progressbar_half_circle_wrap_class` filter + Pro-active render branch |
| Box | ❌ — force-fallback to `line` | ✅ via `add_eael_progressbar_block` action |
| Inner title (label inside the bar) | ✅ Line layout only by default | ✅ extended to additional layouts via `eael_progressbar_general_style_condition` filter |
| Stripe + animate (line only) | ✅ | ✅ |
| Counter value type: static / dynamic | ✅ | ✅ |
| `eael_section_pro` upsell panel | shown when Lite | hidden |
| `eael_pricing_table_style_pro_alert` heading | appears when Pro-only layout selected | — |

Pro-only layouts (`line_rainbow`, `circle_fill`, `half_circle_fill`, `box`) appear in the picker with a "(Pro)" suffix and a panel heading alert when selected without Pro. `render()` resets the layout to `line` ([line 920-922](../../includes/Elements/Progress_Bar.php#L920)) so the rendered output is a working line bar — same force-fallback pattern as Fancy_Text style-2.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Progress_Bar.php`](../../includes/Elements/Progress_Bar.php) | PHP widget class — 7 sections, three render branches, Pro fallback |
| [`src/css/view/progress-bar.scss`](../../src/css/view/progress-bar.scss) | Source styles — line, circle pie-chart, half-circle, stripe animation, alignment |
| [`src/js/view/progress-bar.js`](../../src/js/view/progress-bar.js) | Frontend logic — `inview` listener, layout-branched animation, counter step callback |
| [`config.php`](../../config.php#L533) entry `'progress-bar'` | `Asset_Builder` dependency declaration (CSS + JS + `inview` lib) |
| `assets/front-end/js/lib-view/inview/inview.min.js` | Vendor — jQuery inview plugin (fires `inview` event when element enters viewport) |
| `assets/front-end/css/view/progress-bar.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/progress-bar.min.js` | Built output (do not edit) |

## Architecture

- **`inview` event fires once per widget** — `$this.one("inview", function () { … })` ([line 11 of progress-bar.js](../../src/js/view/progress-bar.js#L11)). When the element enters the viewport, the handler runs once; subsequent scrolls into / out of view do not re-trigger. Good for one-shot reveal; means animation cannot be replayed without page reload.
- **Counter and visual fill animate separately** — `.eael-progressbar-line-fill` gets `width: $num + "%"` set as an inline style; CSS transition (with inline `transition-duration` from the control) handles the visual fill. The number text animates via jQuery `.animate({counter: 0 → $num}, { duration, step })` with a linear easing. Two systems happen to take the same duration value but are decoupled.
- **Pie-chart circle layout uses two half-circles + clip-path** — at 0–180° only `.eael-progressbar-circle-half-left` rotates; at 180°+ the `clip-path: inset(0)` activates and `.eael-progressbar-circle-half-right` becomes visible. Pie-chart math: `rotate = counter * 3.6` (100% × 3.6 = 360°).
- **Half-circle layout uses a single transform** — `rotate(counter * 1.8 deg)` on `.eael-progressbar-circle-half` (100% × 1.8 = 180°).
- **Force-fallback to `line` when Pro-only layout selected without Pro** — [`render()` line 919-923](../../includes/Elements/Progress_Bar.php#L919) resets `$settings['progress_bar_layout']` before the render branch picks. Lite users see a working line bar instead of empty markup.
- **Pro-only `half_circle_fill` shares the half-circle render branch** — line 997-1001 expands `$circle_condition` to include `half_circle_fill` when Pro is active. Pro's listener for `eael_progressbar_half_circle_wrap_class` filter adds the differentiating wrapper class.
- **JS-side custom action `eael.hooks.doAction("progressBar.initValue", $this, $layout, $num)`** ([line 22 of JS](../../src/js/view/progress-bar.js#L22)) — fires after initial DOM setup, before counter animation starts. Third-party JS can listen via `eael.hooks.addAction("progressBar.initValue", "<namespace>", callback)`.
- **No `elementStatusCheck` guard** — re-fired `frontend/init` (popups, SPA nav) can re-bind the `inview` handler. Since `$this.one("inview", …)` itself is idempotent (the `.one()` removes after first fire), this is safe in practice — but the wrapper class state may not reset across navigation.
- **Counter value capped at 100** — `if ($num > 100) $num = 100;` ([line 7 of JS](../../src/js/view/progress-bar.js#L7)). PHP slider control also limits 0–100; dynamic value type allows manual entry of any number but JS clamps.

## Render Output

The widget produces one of three DOM shapes in Lite (plus Pro's box variant via the `add_eael_progressbar_block` action). All share the outer `.eael-progressbar` and inner `.eael-progressbar-count`.

### Line layout (default)

```html
<div class="eael-progressbar-line-container left">
  <div class="eael-progressbar-title">Progress Bar</div>
  <div class="eael-progressbar eael-progressbar-line
              [eael-progressbar-line-stripe]
              [eael-progressbar-line-animate | eael-progressbar-line-animate-rtl]"
       data-layout="line"
       data-count="50"
       data-duration="1500">
    [?] <span class="eael-progressbar-count-wrap">
          <span class="eael-progressbar-count">0</span>
          <span class="postfix">%</span>
        </span>
    <span class="eael-progressbar-line-fill"
          style="transition-duration: 1500ms;">
      [?] Inner Title
    </span>
  </div>
</div>
```

### Circle / Circle Fill layout

```html
<div class="eael-progressbar-circle-container center">
  [?] <div class="eael-progressbar-circle-shadow">    <!-- when box-shadow is set -->
    <div class="eael-progressbar eael-progressbar-circle"
         data-layout="circle"
         data-count="50"
         data-duration="1500">
      <div class="eael-progressbar-circle-pie">
        <div class="eael-progressbar-circle-half-left eael-progressbar-circle-half"></div>
        <div class="eael-progressbar-circle-half-right eael-progressbar-circle-half"></div>
      </div>
      <div class="eael-progressbar-circle-inner"></div>
      <div class="eael-progressbar-circle-inner-content">
        <div class="eael-progressbar-title">Progress Bar</div>
        [?] <span class="eael-progressbar-count-wrap">
              <span class="eael-progressbar-count">0</span>
              <span class="postfix">%</span>
            </span>
      </div>
    </div>
  [?] </div>
</div>
```

### Half Circle layout

```html
<div class="eael-progressbar-circle-container">
  <div class="eael-progressbar eael-progressbar-half-circle"
       data-layout="half_circle"
       data-count="50"
       data-duration="1500">
    <div class="eael-progressbar-circle">
      <div class="eael-progressbar-circle-pie">
        <div class="eael-progressbar-circle-half"
             style="transition-duration: 1500ms;"></div>
      </div>
      <div class="eael-progressbar-circle-inner"></div>
    </div>
    <div class="eael-progressbar-circle-inner-content">
      <div class="eael-progressbar-title">…</div>
      <span class="eael-progressbar-count-wrap">…</span>
    </div>
  </div>
  <div class="eael-progressbar-half-circle-after">
    [?] <span class="eael-progressbar-prefix-label">$</span>
    [?] <span class="eael-progressbar-postfix-label">%</span>
  </div>
</div>
```

Notes:

- `.eael-progressbar` carries `data-layout`, `data-count`, `data-duration` — JS reads these on `inview` fire.
- Layout-specific wrapper classes: `eael-progressbar-line` / `-circle` / `-half-circle`. Pro extends with `_line_rainbow`, `_circle_fill`, `_half_circle_fill` via the wrapper-class filters.
- `.eael-progressbar-line-fill` inline `transition-duration` matches the control value; the SCSS default `1500ms` is overridden.
- Postfix character is hardcoded `%` ([line 956 / 989 / 1035 of PHP](../../includes/Elements/Progress_Bar.php#L956)) — no control to change to `°` or `pts`.
- The count wrap is always rendered (even when display count is off) for circle layouts — just `style="display: none;"` ([line 989](../../includes/Elements/Progress_Bar.php#L989)). Line and half-circle layouts conditionally omit it.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Progress_Bar.php#L70) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `progress_bar_layout` | SELECT | `line` | Content → Layout | Layout class + render branch; `data-layout` |
| `eael_pricing_table_style_pro_alert` | HEADING | — | Content → Layout | Pro-only alert (only visible when Pro-locked layout selected) |
| `progress_bar_title` | TEXT (dynamic) | `"Progress Bar"` | Content → Layout | `.eael-progressbar-title` text |
| `progress_bar_title_html_tag` | SELECT | `div` | Content → Layout | Title HTML element |
| `progress_bar_title_inner_show` | SWITCHER | empty | Content → Layout | Toggles inner title (line layout in Lite; expanded via `eael_progressbar_general_style_condition` filter) |
| `progress_bar_title_inner` | TEXT (dynamic) | `"Progress Bar"` | Content → Layout | Inner title text |
| `progress_bar_value_type` | SELECT | `static` | Content → Layout | Static slider vs dynamic NUMBER |
| `progress_bar_value` | SLIDER (0–100) | `50` | Content → Layout | `data-count` (static type) |
| `progress_bar_value_dynamic` | NUMBER (dynamic) | empty | Content → Layout | `data-count` (dynamic type) — capped at 100 in JS |
| `progress_bar_show_count` | SWITCHER | `yes` | Content → Layout | Renders the counter number block |
| `progress_bar_animation_duration` | SLIDER (ms) | `1500` | Content → Layout | `data-duration` AND inline `transition-duration` on fill |
| `progress_bar_line_fill_stripe` | SWITCHER | empty | Content → Style | `eael-progressbar-line-stripe` class (line only) |
| `progress_bar_line_fill_stripe_animate` | SELECT | `normal`/`reverse`/empty | Content → Style | Adds `eael-progressbar-line-animate` or `-animate-rtl` |
| `progress_bar_line_alignment` / `_circle_alignment` | CHOOSE | `center` | Style → Layout | Container alignment class |
| `progress_bar_prefix_label` / `_postfix_label` | TEXT (dynamic) | empty | Content → Layout | Prefix / postfix span (half-circle only in Lite) |
| Style → Background / Fill / Title / Count / etc. | various | — | Style tab | Visual styling |

## Conditional Dependencies

```text
# Layout-specific
progress_bar_value                          → visible when progress_bar_value_type == 'static'
progress_bar_value_dynamic                  → visible when progress_bar_value_type == 'dynamic'

# Inner title (Lite: line only; Pro extends via filter)
progress_bar_title_inner_show               → visible when layout in
                                                eael_progressbar_general_style_condition filter result
                                                (Lite default: ['line'])
progress_bar_title_inner                    → visible when ... AND _inner_show == 'yes'

# Stripe (line only)
progress_bar_line_fill_stripe_animate       → visible when progress_bar_line_fill_stripe == 'yes'

# Pro-only layout alert
eael_pricing_table_style_pro_alert          → visible when layout in
                                                ['line_rainbow', 'circle_fill', 'half_circle_fill', 'box']
                                                (the Pro-conditions list from eael_progressbar_styles filter)

# Pro upsell
eael_section_pro / eael_control_get_pro     → visible when Pro plugin is NOT active
```

## Hooks & Filters

The widget exposes a rich Pro-extension surface. Pro consumes most of these; the JS-side custom action is open for any third party.

| Hook | Type | Signature | Purpose | Legacy? |
| ---- | ---- | --------- | ------- | ------- |
| `add_eael_progressbar_layout` | filter | `array { layouts: id=>label, conditions: id[] }` | Pro adds 4 new layouts to the picker ([line 90](../../includes/Elements/Progress_Bar.php#L90)) | ⚠️ un-prefixed (missing `eael/`) |
| `eael_progressbar_styles` | filter | `array $defaults` | Reserved for further layout customisation; mentioned in `register_controls()` flow |  |
| `eael_progressbar_general_style_condition` | filter | `array $layouts` | Layouts that get "general" style controls (inner title, etc.); Pro extends from `['line']` | properly prefixed |
| `eael_progressbar_line_fill_stripe_condition` | filter | `array $condition` | Stripe-availability condition; Pro extends | properly prefixed |
| `eael_circle_style_general_condition` | filter | `array $layouts` | Layouts that get circle-style controls; Pro adds `half_circle_fill` and `circle_fill` | properly prefixed |
| `eael_progressbar_rainbow_wrap_class` | filter | `(array $wrap_classes, array $settings)` | Pro adds the rainbow class for `line_rainbow` ([line 927](../../includes/Elements/Progress_Bar.php#L927)) | properly prefixed |
| `eael_progressbar_circle_fill_wrap_class` | filter | `(array $wrap_classes, array $settings)` | Pro adds the circle-fill class ([line 964](../../includes/Elements/Progress_Bar.php#L964)) | properly prefixed |
| `eael_progressbar_half_circle_wrap_class` | filter | `(array $wrap_classes, array $settings)` | Pro adds the half-circle-fill class ([line 1005](../../includes/Elements/Progress_Bar.php#L1005)) | properly prefixed |
| `add_progress_bar_control` | action | `(Widget_Base $widget)` | Pro adds box-layout controls ([line 774](../../includes/Elements/Progress_Bar.php#L774)) | ⚠️ un-prefixed legacy |
| `add_eael_progressbar_block` | action | `(array $settings, Widget_Base $widget, array $wrap_classes)` | Pro renders the box-layout DOM ([line 1044](../../includes/Elements/Progress_Bar.php#L1044)) | ⚠️ un-prefixed legacy |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides upsell; toggles render fallback |  |

JS-side action:

| Hook | Signature | Purpose |
| ---- | --------- | ------- |
| `progressBar.initValue` (`eael.hooks.doAction`) | `($element, $layout, $num)` | Fires after viewport-entry; third-party JS can react to a bar starting its animation ([line 22 of JS](../../src/js/view/progress-bar.js#L22)) |

⚠️ Three un-prefixed legacy hooks (`add_eael_progressbar_layout`, `add_progress_bar_control`, `add_eael_progressbar_block`) are part of Pro's public contract. Renames require dual-emit migration. See [`_patterns.md`](_patterns.md) for the standard `eael_section_pro` upsell.

The standard Pro upsell panel ([line 320](../../includes/Elements/Progress_Bar.php#L320)) and the `eael/pro_enabled` filter follow [`_patterns.md § eael_section_pro standard upsell panel`](_patterns.md#eael_section_pro-standard-upsell-panel).

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-progress-bar.default', ProgressBar)`
- **Guard:** none — no `elementStatusCheck`. Safe due to `$this.one("inview", …)` idempotence at the per-widget level.
- **Vendor dependency:** [`inview.min.js`](../../assets/front-end/js/lib-view/inview/inview.min.js) — provides jQuery `inview` event (fires when element enters viewport)
- **Reads on init:** `data-layout`, `data-count`, `data-duration` from `.eael-progressbar`
- **One-shot binding:** `$this.one("inview", …)` — fires only the first time the element enters the viewport
- **Cap on count value:** `if ($num > 100) $num = 100;`
- **Layout branches:**
  - **Line:** `width = $num + "%"` on `.eael-progressbar-line-fill` (CSS transition handles the animation)
  - **Half-circle:** `transform = rotate($num * 1.8 deg)` on `.eael-progressbar-circle-half`
  - **Circle / Circle Fill:** during counter animation step, sets `transform = rotate(counter * 3.6 deg)` on `.eael-progressbar-circle-half-left`; when rotate > 180° also activates `clip-path: inset(0)` on `.eael-progressbar-circle-pie` and shows `.eael-progressbar-circle-half-right`
- **Counter text animation:** jQuery `.animate({counter: 0 → $num}, { duration, easing: 'linear', step: counter => $(this).text(Math.ceil(counter)) })` — virtual property animation
- **JS-side extension hook:** `eael.hooks.doAction("progressBar.initValue", $this, $layout, $num)` — fires once per widget after settings are read, before the counter animation starts
- **Runtime state:** none persistent; the `.one()` removes the listener after firing

## Common Issues

### Bar fills slower / faster than the counter number animates

- **Likely cause:** the `progress_bar_animation_duration` control writes both the CSS `transition-duration` and the JS `.animate()` `duration` — so they should match. If a theme overrides the CSS transition with `!important`, the two diverge.
- **Diagnose:** in DevTools inspect `.eael-progressbar-line-fill` — compare computed `transition-duration` to `data-duration`
- **Fix:** remove theme override; or set duration in the panel to match the theme's hardcoded value

### Pro-only layout selected without Pro renders a plain line bar

- **Likely cause:** `render()` ([line 919-923](../../includes/Elements/Progress_Bar.php#L919)) force-falls-back to `line` when the chosen layout is in `['line_rainbow', 'circle_fill', 'half_circle_fill', 'box']` and Pro is inactive
- **Diagnose:** is Pro active?
- **Fix:** activate Pro; or pick a Lite-supported layout (`line`, `circle`, `half_circle`)

### Dynamic counter value > 100 caps silently

- **Likely cause:** JS hard-caps at 100 (`if ($num > 100) $num = 100;`) even though the PHP NUMBER control has `'max' => 100`. Dynamic data from Elementor's dynamic tags could feed in a larger value
- **Diagnose:** inspect `data-count` on the rendered widget — does it match what the dynamic tag returned?
- **Fix:** the cap is intentional (progress bars represent percentages); normalise the dynamic value to 0-100 before binding

### Animation doesn't re-run when scrolling back to the widget

- **Likely cause:** `$this.one("inview", …)` removes the listener after the first fire — by design, one-shot animation
- **Diagnose:** scroll past the widget, then back — does the bar reset to 0 and re-animate? No.
- **Fix:** none built-in; theme JS can manually re-trigger via `$this.trigger('inview')` after resetting the bar state

### Counter shows `0` permanently

- **Likely cause:** `inview` event never fired — vendor JS failed to load, OR the widget is in a fixed-height container without scroll
- **Diagnose:** browser console for vendor JS 404; inspect — is the widget visible in the viewport?
- **Fix:** ensure `inview.min.js` is loaded; if widget is always visible without scroll, manually fire the event: `$('.eael-progressbar').trigger('inview')`

### Postfix character can't be changed from `%`

- **Likely cause:** the postfix `%` is hardcoded in PHP (`<span class="postfix">%</span>` at [lines 956, 989, 1035](../../includes/Elements/Progress_Bar.php#L956))
- **Diagnose:** by design — no control for postfix character
- **Fix:** override via CSS — `.eael-progressbar .postfix { display: none; }` and use `prefix_label` / `postfix_label` controls instead (half-circle layout only)

### Inner title doesn't appear despite enabling the switch

- **Likely cause:** the inner-title switch is conditional on `progress_bar_layout` being in `eael_progressbar_general_style_condition` filter result; Lite defaults to `['line']` only. Circle / half-circle layouts hide the switch
- **Diagnose:** check the layout selection
- **Fix:** Pro extends to more layouts; without Pro, inner title is line-only

## Known Limitations

- **One-shot animation** — `inview` event fires once via `.one()`; cannot replay without page reload or manual trigger.
- **Counter capped at 100** — JS hard-caps regardless of dynamic input source.
- **Three un-prefixed legacy hooks** — `add_eael_progressbar_layout`, `add_progress_bar_control`, `add_eael_progressbar_block`. Renames require dual-emit migration (see [`_patterns.md`](_patterns.md)).
- **Hardcoded `%` postfix** — no control for non-percentage progress bars (e.g. "75 / 100 pts").
- **Pro alert heading id is misleadingly named** — `eael_pricing_table_style_pro_alert` ([line 118](../../includes/Elements/Progress_Bar.php#L118)) — copy-paste from Pricing Table. Renaming would break saved widget data.
- **CSS transition + JS step callback are two animation systems** — if either is misaligned (theme override on `transition`, slow JS frame timing), counter and bar visually drift apart.
- **No `elementStatusCheck` guard** — safe due to per-widget `.one()` idempotence, but inconsistent with other EA widgets.
- **Circle `display: none` on count wrap** — even when display count is off, the markup is emitted (just hidden). Slightly bloats DOM for the circle / circle_fill layouts.
- **Prefix / postfix labels are half-circle-only in Lite** — line and circle layouts emit `<span class="postfix">%</span>` hardcoded, ignoring the prefix / postfix label controls.
- **`progress_bar_value` is a `%`-only slider** — even though the postfix could conceptually represent other units, the value control restricts to 0-100%.
- **Pro listener for `add_eael_progressbar_block` runs at default priority** — third-party listeners on the same action without coordinated priority can render duplicate boxes.
