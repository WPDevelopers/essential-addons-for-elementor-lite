# Hover Interactions Extension

> Per-widget hover-state animation engine — twelve effect families (opacity, blur, contrast, grayscale, invert, saturate, sepia, offset, rotate, scale, skew, tilt) configurable independently for Normal and Hover states, exposed under every widget's Advanced tab.

**Class file:** [`includes/Extensions/Hover_Effect.php`](../../includes/Extensions/Hover_Effect.php) (1,636 lines)
**Slug:** `special-hover-effect` (`config.php` `extensions` key — note: **not** `hover-effect`; the class file is named `Hover_Effect.php` but its registry key uses the legacy "Special Hover Effects" branding from the product copy)
**Public docs:** <https://essential-addons.com/docs/hover-interactions/>
**Pro-shared:** Lite-only public surface (no `do_action` injection points). Pro does not currently extend this extension.

---

## Overview

`Hover_Effect` is the largest extension in `includes/Extensions/` by a wide margin (1,636 lines vs the next-largest's 1,310). Its size comes from sheer repetition: every effect appears twice (Normal tab + Hover tab), and most effects are subdivided into a switcher + a slider for the value, all of them wrapped in POPOVER_TOGGLE groupings. There is no underlying engine — just one giant `register_controls()` method that defines the panel and one `before_render()` method that copies the settings onto the wrapper as `data-*` attributes, plus a frontend JS handler that reads those attributes and applies inline styles via jQuery `.hover()`.

The user enables the panel under any widget's Advanced tab → "Hover Interactions", toggles each effect family on, and tunes sliders. The output is one CSS class (`eael_hover_effect`) plus 20-something `data-*` attributes on the widget's `_wrapper`. The frontend JS reads these on `frontend/element_ready/widget` and binds `mouseenter` / `mouseleave` handlers that swap inline `transform` / `filter` / `opacity` / transition properties.

### Slug-vs-class-name mismatch (non-obvious)

The registry slug is `special-hover-effect` ([`config.php:1415`](../../config.php#L1415)) but the PHP class is `Hover_Effect` and its source files are `hover-effect.scss` / `hover-effect.js`. The mismatch is a relic of older product copy ("Special Hover Effects"). Practical impact:

- The active-list key in `eael_save_settings` is `special-hover-effect` — anyone scripting toggles must use that string, not `hover-effect`.
- The asset filenames (`hover-effect.min.css` / `.min.js`) use the short form; nothing breaks because the registry's `dependency` block hardcodes the asset path.
- Any third-party filter that wants to suppress this extension via `eael/registered_extensions` must `unset($exts['special-hover-effect'])` — see Customization Recipes.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Extensions/Hover_Effect.php`](../../includes/Extensions/Hover_Effect.php) | 1,636 | The class — constructor wires two hooks; `register_controls()` paints the panel; `before_render()` serialises every enabled effect into a `data-*` attribute |
| [`src/css/view/hover-effect.scss`](../../src/css/view/hover-effect.scss) | 13 | Minimal stylesheet — only declares the `.eael_hover_effect` wrapper as `position: relative` plus tilt-state transitions. All other styling is inline (set by JS) |
| [`src/js/view/hover-effect.js`](../../src/js/view/hover-effect.js) | 349 | `HoverEffectHandler($scope, $)` — reads `data-*` attrs, builds two style maps (normal + hover), binds jQuery `.hover()`. Edit-mode branch reads `window.elementor.elements.models` for live preview |
| [`config.php:1415`](../../config.php#L1415) | — | Registry entry under slug `special-hover-effect`; declares CSS and JS, both `context => 'view'` |
| [`assets/front-end/css/view/hover-effect.min.css`](../../assets/front-end/css/view/hover-effect.min.css) | — | Built output of the SCSS |
| [`assets/front-end/js/view/hover-effect.min.js`](../../assets/front-end/js/view/hover-effect.min.js) | — | Built output of the JS |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Panel registers under every widget's Advanced tab | ✅ | ✅ (same code path; Pro doesn't override) |
| All twelve effect families | ✅ | ✅ |
| Tilt effect | ✅ | ✅ |
| Live editor preview (opt-in) | ✅ | ✅ |
| Pro-specific extension hooks (`do_action`) | ❌ | ❌ (this extension exposes no Pro injection points today) |

Unlike Liquid Glass Effect, Hover Interactions has **no `do_action()` calls** for Pro to listen on. The full surface is shipped in Lite. Pro does not extend it.

## Architecture

- **Two hooks, zero state.** [`Hover_Effect::__construct():17-20`](../../includes/Extensions/Hover_Effect.php#L17) wires only `elementor/element/common/_section_style/after_section_end` (panel paint) and `elementor/frontend/before_render` at priority 100 (data-attr injection). The class never stores state — every render reads fresh settings.
- **`elementor/element/common/_section_style/after_section_end` is the broadest scope.** It fires for every widget but **not** for sections, columns, or containers. So Hover Interactions appears on widgets only — not on layout primitives. Rationale: hover-on-section is normally handled by Elementor's own section hover background; widget-level fine control was the actual gap.
- **POPOVER_TOGGLE grouping.** Each effect family is a popover so the panel doesn't explode into a hundred-row scroll. The popover toggle itself is a saved control (`*_popover` suffix); the contents are conditioned on it being `'yes'`.
- **Two parallel control trees (Normal + Hover).** Effects mirror perfectly between the two tabs — same controls, hover-suffixed IDs. See [`register_controls():72-1357`](../../includes/Extensions/Hover_Effect.php#L72). Rationale: an effect's resting value and hovered value are independent; users want to set both.
- **No `selectors` — all styling is inline via JS.** Elementor's normal pattern is `selectors => '{{WRAPPER}} { transform: ... }'` driving inline `<style>` blocks. Hover_Effect instead serialises everything to `data-*` and lets JS apply the styles via `.css()`. Rationale: hover-state styles in CSS require pseudo-selectors that don't compose with Elementor's selector substitution; JS sidesteps the problem and supports the live-preview path uniformly.
- **`frontend_available => true` is selective.** Some controls (sliders for filter values) are server-only because the JS reads them out of the data attribute, not the live settings object. Other controls (`*_easing`, `*_popover` toggles) are `frontend_available => true` because the editor-mode branch reads them from `elementor.elements.models` for live preview. See e.g. [`register_controls():348`](../../includes/Extensions/Hover_Effect.php#L348).
- **Live preview is opt-in.** [`register_controls():48-69`](../../includes/Extensions/Hover_Effect.php#L48) adds a `eael_hover_effect_enable_live_changes` switch with an info note: enabling Hover Interactions disables Elementor's Transform feature inside the editor, so live preview costs the user the editor's built-in transform UX. Default off.
- **Tilt effect is special.** [`before_render():1625-1627`](../../includes/Extensions/Hover_Effect.php#L1625) emits a `data-eaeltilt="eael_tilt"` attribute that the SCSS hooks via `[data-eaeltilt="eael_tilt"].eael_hover_effect { transform-style: preserve-3d; … }` and the JS hooks via `mousemove` listeners ([`hover-effect.js:322-337`](../../src/js/view/hover-effect.js#L322)). Tilt is the one effect that pairs CSS with JS rather than pure inline-JS styling.

## Render Behavior

### Wrapper class + 20+ data attributes

After `before_render` runs, the widget's `_wrapper` carries:

```html
<div class="elementor-widget … eael_hover_effect"
     data-id="{widget-id}"
     data-eael_opacity='{"opacity":0.8}'
     data-eael_blur_effect='{"blur":1}'
     data-eael_contrast_effect='{"contrast":80}'
     data-eael_grayscale_effect='{"grayscale":40}'
     data-eael_invert_effect='{"invert":70}'
     data-eael_saturate_effect='{"saturate":50}'
     data-eael_sepia_effect='{"sepia":50}'
     data-eael_offset_top='{"size":5,"unit":"px"}'
     data-eael_offset_left='{"size":5,"unit":"px"}'
     data-eael_rotate_effect='{"rotate_x":0,"rotate_y":0,"rotate_z":5}'
     data-eael_scale_effect='{"scale_x":0.9,"scale_y":0.9}'
     data-eael_skew_effect='{"skew_x":5,"skew_y":5}'
     data-eael_duration='{"transitionDuration":1000}'
     data-eael_delay='{"transitionDelay":0}'
     data-eael_easing='{"transitionEasing":"ease"}'
     data-eael_opacity_hover='{"opacity":1}'
     <!-- and the corresponding *_hover variants -->
     data-eaeltilt="eael_tilt">
   …widget content…
</div>
```

Only attributes for enabled effects are emitted — disabled effects produce no `data-*`. See [`before_render():1362-1633`](../../includes/Extensions/Hover_Effect.php#L1362).

### JS-applied inline styles

On `frontend/element_ready/widget`, [`HoverEffectHandler`](../../src/js/view/hover-effect.js#L1) reads all `data-eael_*` attributes off the scope, builds two style maps:

```js
let normalStyles = {
  "transform": `${$rotateX} ${$rotateY} ${$rotateZ} ${$scaleX} ${$scaleY} ${$skewX} ${$skewY} ${$offsetX} ${$offsetY}`,
  "opacity": $opacityVal,
  "filter": `${$blur} ${$contrast} ${$grayscale} ${$invert} ${$saturate} ${$sepia}`,
  "transition-property": 'all',
  "transition-duration": `${$eaelDurationVal}ms`,
  "transition-delay": `${$eaelDelayVal}ms`,
  "transition-timing-function": $eaelEasingVal,
  "z-index": 1
};
```

Then binds `.hover()`:

```js
$hoverSelector.hover(
  function () { $(this).css(hoverStyles); },
  function () { $(this).css(normalStyles); }
);
$hoverSelector.css(normalStyles);
```

The `normalStyles` are applied immediately; hover/leave swap to `hoverStyles` and back. See [`hover-effect.js:309-320`](../../src/js/view/hover-effect.js#L309).

### Tilt effect (mousemove-driven 3D)

When `data-eaeltilt="eael_tilt"` is set, an additional `mousemove` listener computes pointer offset from element centre and applies `transform: perspective(500px) rotateY(...) rotateX(...)` inline. `mouseleave` resets to `rotateY(0) rotateX(0)`. See [`hover-effect.js:323-337`](../../src/js/view/hover-effect.js#L323). The SCSS provides only the `transform-style: preserve-3d` and the bouncy `cubic-bezier` transition.

## Asset Dependencies

### CSS

Registered in [`config.php:1418-1424`](../../config.php#L1418): `assets/front-end/css/view/hover-effect.min.css`, `type => 'self'`, `context => 'view'`. The source is 13 lines — only the wrapper `position: relative` and tilt-specific transitions live here. Every other visual effect is set inline by JS, so CSS specificity / theme conflicts are not a concern.

### JS

Registered in [`config.php:1425-1431`](../../config.php#L1425): `assets/front-end/js/view/hover-effect.min.js`, `type => 'self'`, `context => 'view'`. The handler is a function bound to `frontend/element_ready/widget` via `elementorFrontend.hooks.addAction` ([`hover-effect.js:341-349`](../../src/js/view/hover-effect.js#L341)). Guard: `eael.elementStatusCheck('eaelHoverEffect')` returns true on subsequent calls to prevent double-binding.

Edit-mode branch ([`hover-effect.js:39-215`](../../src/js/view/hover-effect.js#L39)) walks `window.elementor.elements.models` recursively (handles `widget`, `container`, `section`, `column`), collects per-element settings for widgets that have both `eael_hover_effect_switch === 'yes'` **and** `eael_hover_effect_enable_live_changes === 'yes'`. This branch is what makes the editor preview work for users who opt in.

### Why `context => 'view'` (not `'edit'`)

Most extensions use `'edit'` because their UI is editor-only. Hover Interactions runs at frontend — the JS binds real mouse listeners on the published page. The edit-mode preview path is conditional inside the same JS file (`if (window.isEditMode)`), so a single `'view'` asset serves both contexts.

## Hook Timing

### Elementor / WP hooks consumed

| Hook | Priority | Callback | Effect |
| ---- | -------- | -------- | ------ |
| `elementor/element/common/_section_style/after_section_end` | 10 (default) | `Hover_Effect::register_controls` | Adds the "Hover Interactions" panel under every widget's Advanced tab |
| `elementor/frontend/before_render` | **100** | `Hover_Effect::before_render` | Serialises settings into `data-*` attributes on the widget's `_wrapper`. Late priority so other extensions (Wrapper_Link, etc.) at default priority run first |

### Hooks emitted

None. Hover_Effect does not call `do_action()` or `apply_filters()` anywhere. This is the cleanest example of a self-contained extension in the codebase — no extension surface, no third-party injection points.

### Why priority 100 on `before_render`

The two extensions that hook `elementor/frontend/before_render` are this one and `Wrapper_Link`. Both register at priority 100. There is no documented dependency order between them, but the late priority ensures any widget-level callbacks at default priority (10) have set their render attributes first. See [`extensions.md § Common Pitfalls`](../architecture/extensions.md#hook-target-element-type-mismatch).

## Configuration & Extension Points

### Global activation

Toggled via `eael_save_settings[special-hover-effect]`. Default-enabled on first install (`Core::set_default_values` fills both elements + extensions). Disable from EA Settings page → Extensions tab to remove the panel and prevent any JS from loading. No filter overrides this — the extension is on or off per option, full stop.

### Per-element controls

All controls live in one section under each widget's Advanced tab. Top-level switches:

| Control id | Type | Purpose |
| ---------- | ---- | ------- |
| `eael_hover_effect_switch` | SWITCHER | Master on/off for this widget |
| `eael_hover_effect_enable_live_changes` | SWITCHER | Enable editor preview (default off; disables Elementor's transform UI when on) |

Inside, two tabs (`Normal` / `Hover`), each containing five popover groupings:

| Popover toggle | Wraps | Sub-controls |
| -------------- | ----- | ------------ |
| `*_opacity_popover` | Opacity | `*_opacity` slider |
| `*_filter_popover` | Filter | Blur, Contrast, Grayscale, Invert, Saturate, Sepia (each: switch + slider) |
| `*_offset_popover` | Offset | `*_offset_top` + `*_offset_left` (responsive) |
| `*_transform_popover` | Transform | Rotate (X/Y/Z), Scale (X/Y), Skew (X/Y), each gated by its own switch |
| _(general settings)_ | Transitions | Duration, Delay, Easing |

Plus one Hover-tab-only control: `eael_hover_effect_hover_tilt` (mouse-following 3D tilt).

Total: roughly 60 controls per tab, 120+ total per widget. (The size accounts for most of the 1,636-line class file.)

### Filter / action surface

None. The extension exposes no `apply_filters()` or `do_action()` calls. To customise behaviour, the only paths are:

1. Suppress the extension entirely via `eael/registered_extensions` (Recipe 2 below).
2. Patch styles by overriding `.eael_hover_effect` rules in your own stylesheet (works only for tilt; other effects are inline-styled by JS and CSS overrides cannot win).
3. Override the JS handler — see Recipe 3.

## Customization Recipes

### Recipe 1 — Disable Hover Interactions on specific widget types

```php
add_action( 'elementor/element/common/_section_style/after_section_end', function ( $element ) {
    // Bail before Hover_Effect's controls register on certain widget types.
    if ( in_array( $element->get_name(), [ 'eael-fancy-text', 'eael-progress-bar' ], true ) ) {
        // Cannot un-register a hook by callback removed-after-the-fact, but
        // we can short-circuit by hooking earlier and unhooking the extension:
        remove_action(
            'elementor/element/common/_section_style/after_section_end',
            [ \Essential_Addons_Elementor\Classes\Bootstrap::instance()->get_extension( 'special-hover-effect' ), 'register_controls' ]
        );
    }
}, 1 );
```

In practice, the cleanest path is to disable Hover Interactions globally via EA settings if it conflicts with specific widgets. Per-widget suppression is awkward because the panel is registered against the common element hook.

### Recipe 2 — Suppress the extension entirely (testing / minimal-build)

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['special-hover-effect'] );
    return $exts;
} );
```

Note: the key is `special-hover-effect`, not `hover-effect`. This removes the extension from the registry before Bootstrap instantiates it. No panel, no JS, no CSS. Useful for performance audits.

### Recipe 3 — Replace the frontend handler with a custom one

The JS hook is registered via `elementorFrontend.hooks.addAction("frontend/element_ready/widget", HoverEffectHandler)` ([`hover-effect.js:345-348`](../../src/js/view/hover-effect.js#L345)). To replace:

```js
// In your own script, loaded after hover-effect.min.js:
elementorFrontend.hooks.removeAction(
    'frontend/element_ready/widget',
    HoverEffectHandler  // not exported — you'd need a wider deregistration
);
elementorFrontend.hooks.addAction(
    'frontend/element_ready/widget',
    function ($scope, $) {
        // Your custom logic that reads $scope.data('eael_*') attrs
    }
);
```

In practice the original `HoverEffectHandler` is not exported as a module symbol, so cleanly removing it requires either patching the JS source or using a higher-priority handler that mutates the wrapper to suppress the data attributes before the original runs.

### Recipe 4 — Force-enable tilt on specific widgets server-side

```php
add_action( 'elementor/frontend/before_render', function ( $element ) {
    if ( $element->get_name() === 'eael-info-box' ) {
        $element->add_render_attribute( '_wrapper', 'data-eaeltilt', 'eael_tilt' );
        $element->add_render_attribute( '_wrapper', 'class', 'eael_hover_effect' );
    }
}, 110 ); // Priority > 100 so it runs after Hover_Effect::before_render
```

This bypasses the panel toggle and forces tilt on a specific widget class. The frontend JS will pick it up unconditionally.

## Common Issues

### Effects do not appear at frontend

- **Likely cause:** `eael_hover_effect_switch` is off, or `eael_save_settings[special-hover-effect]` is off (extension disabled globally).
- **Diagnose:** Inspect the widget wrapper in DOM — if `.eael_hover_effect` class and `data-eael_*` attrs are missing, `before_render` didn't run (likely extension disabled). If they're present but no effect on hover, JS didn't bind (check console for errors).
- **Fix:** Toggle the panel on; check EA Settings → Extensions → Hover Interactions is enabled.

### Editor preview shows nothing despite live-changes toggle

- **Likely cause:** Live-changes toggle is per-widget — easy to enable globally in expectation but not at the widget itself.
- **Diagnose:** Inspect the widget's saved settings: `eael_hover_effect_enable_live_changes` must equal `'yes'`. Also confirm the editor JS is the `view` context build (it is — same file).
- **Fix:** Toggle "Show Preview in Editor" at the widget level. Be aware this disables Elementor's Transform editor UI for that widget.

### Hover state "stuck" — element doesn't return to normal

- **Likely cause:** Mouse left while a transition was mid-flight, or the hover region is smaller than the visible element (e.g. transform scaled up and the mouse left a corner).
- **Diagnose:** Reproduce in browser; check the inline `style` attribute — if `transform` or `filter` is locked to the hover values, the leave handler didn't fire.
- **Fix:** Increase the transition duration so the leave handler always has time; or use lower scale values (1.05 instead of 1.5) so the hovered shape doesn't escape the original bounds.

### Tilt effect conflicts with another mousemove handler

- **Likely cause:** Another widget (e.g. Image Comparison) or plugin binds `mousemove` on the same element.
- **Diagnose:** Console — log `$(element).data('events')` or inspect bound handlers via Chrome DevTools.
- **Fix:** Bind your handler with namespacing (`.mousemove('namespace', fn)`) so handlers don't clobber; or unbind Hover_Effect's tilt by adding `removeAttr('data-eaeltilt')` server-side.

### Elementor's Transform feature broken inside editor

- **Likely cause:** Live-changes toggle is on. The info note ([`register_controls():41-46`](../../includes/Extensions/Hover_Effect.php#L41)) warns that enabling Hover Interactions disables Elementor's Transform UI.
- **Fix:** Turn off `eael_hover_effect_enable_live_changes` if you need Elementor's transform; or accept the trade-off if hover preview is more valuable for this widget.

### Effects look different in editor vs frontend

- **Likely cause:** The editor-mode branch in [`hover-effect.js:39-215`](../../src/js/view/hover-effect.js#L39) walks `elementor.elements.models` and re-derives values; if the user changed a setting after the editor's model snapshot, the editor preview may lag a save/refresh.
- **Fix:** Save the page and refresh the editor (or preview in a new tab).

### Slug confusion — code references `hover-effect` instead of `special-hover-effect`

- **Likely cause:** Someone wrote a customisation against the file/class name and not the registry slug.
- **Diagnose:** `var_dump( get_option( 'eael_save_settings' ) )` — the key is `special-hover-effect`.
- **Fix:** Use `special-hover-effect` everywhere except for asset filenames.

## Debugging Guide

When Hover Interactions misbehaves:

1. **Confirm the extension is active.** `var_dump( get_option( 'eael_save_settings' )['special-hover-effect'] ?? null )` should return `1` (or `'1'`).
2. **Confirm the panel registers.** Open Elementor editor, select any widget → Advanced tab — "Hover Interactions" header should appear. If not, `Hover_Effect::register_controls` isn't running (extension not instantiated).
3. **Confirm `before_render` is wired.** `var_dump( has_action( 'elementor/frontend/before_render' ) )` should include `Hover_Effect::before_render` at priority 100.
4. **Inspect the data attributes.** View the page source (not the live DOM — the live DOM has inline styles applied; the source shows what `before_render` emitted). Each enabled effect should have a corresponding `data-eael_*` attribute on the widget wrapper.
5. **Inspect the inline styles.** Open the rendered page, hover the widget, use DevTools to see the wrapper's inline `style`. If `transform: …` looks wrong (e.g. `translateX(NaNpx)`), one of the slider settings is corrupt — re-save the widget.
6. **JS console check.** `eael.elementStatusCheck('eaelHoverEffect')` returning true on first call would indicate a double-init; the guard at [`hover-effect.js:342`](../../src/js/view/hover-effect.js#L342) prevents this normally.
7. **Live-preview branch issues:** check `window.isEditMode` truthy + `window.elementor.elements.models` non-empty. If the editor renders inside an iframe with a different `window`, `isEditMode` may be unset.
8. **Tilt-only debugging:** verify `data-eaeltilt="eael_tilt"` on the **element wrapper** (not the inner widget content). The mousemove handler binds to `.elementor-element-{$scopeId}` ([`hover-effect.js:324`](../../src/js/view/hover-effect.js#L324)) — confirm that selector matches.

## Architecture Decisions

### Inline JS styles instead of CSS `selectors`

- **Context:** Elementor's normal pattern for per-control styling is `'selectors' => '{{WRAPPER}} { transform: scale({{SIZE}}); }'` which generates a `<style>` block on the page. For hover, the natural CSS would be `{{WRAPPER}}:hover { transform: scale(…); }`. Two problems: (1) hover styles inside POPOVER_TOGGLE groupings cannot be cleanly emitted because the conditional logic is per-control; (2) Elementor's editor preview for hover-state CSS is unreliable on widgets with their own internal hover states.
- **Decision:** Don't use `selectors` at all. Serialise every setting to a `data-*` attribute in `before_render`. Apply styles via jQuery `.css()` in `hover-effect.js`. Same JS path handles editor + frontend.
- **Alternatives rejected:** CSS-only approach with `:hover` pseudo-selectors (loses live preview, fights Elementor's own hover styles); CSS variables `--eael-hover-opacity: …` on the wrapper plus a hover ruleset (cleaner but doesn't support the editor-preview branch).
- **Consequences:** Hover Interactions adds zero CSS bytes per-widget (only the inline JS does). But: cannot be overridden via stylesheet — once `.css()` writes inline styles, theme CSS can't win without `!important` everywhere. Documented as a known limitation.

### POPOVER_TOGGLE groupings

- **Context:** With 12 effect families × 2 tabs × multiple sub-controls = 100+ controls in one panel. A flat list would be unusable.
- **Decision:** Group each effect family inside a `POPOVER_TOGGLE` so the user clicks "Filter" and a popover opens with the six sub-effects. Saves vertical space.
- **Alternatives rejected:** Sub-tabs (Elementor's tabs UI doesn't nest cleanly past two levels); collapsed sections (visually heavier than popovers); separating into multiple panels (loses the unified "Hover Interactions" identity).
- **Consequences:** Each effect family has an extra control (`*_popover`) saved in settings. Re-opening a widget shows popover state preserved.

### Single hook on `elementor/element/common/_section_style/after_section_end`

- **Context:** The extension applies to widgets only. Should it also apply to sections, columns, containers?
- **Decision:** Widgets only. The hook is the broadest "every widget" target.
- **Alternatives rejected:** Hook all element types (Wrapper_Link does this) — would require per-element-type branching in `before_render` because section/column wrappers don't carry the same render attribute APIs uniformly.
- **Consequences:** Hover Interactions cannot be applied to a whole section or container. Users wanting that effect must apply it to an inner widget that fills the section. Sometimes-asked feature; not a planned addition.

### `before_render` at priority 100

- **Context:** Other extensions (Wrapper_Link) also hook `elementor/frontend/before_render`. Without priority control, ordering between them is undefined.
- **Decision:** Run at 100, well after the default 10 used by Elementor core and most other plugins.
- **Alternatives rejected:** Default priority 10 (collides with Elementor's own render-attr machinery); much later (200+) — no need.
- **Consequences:** Hover Interactions always sees the final render attrs from earlier hooks; safe to merge in.

## Known Limitations

- **Inline styles cannot be overridden by theme CSS** ([`hover-effect.js:286-307`](../../src/js/view/hover-effect.js#L286)). All effect values are written as inline `style="..."`; `!important` doesn't help against inline styles. Custom styling requires either suppressing this extension or replacing the JS handler.
- **Widgets only — no sections / columns / containers** ([`Hover_Effect::__construct():18`](../../includes/Extensions/Hover_Effect.php#L18)). Applying hover effects to layout primitives requires manual `add_render_attribute` from your own code.
- **Live preview disables Elementor Transform UI.** Documented in an info alert ([`register_controls():41-46`](../../includes/Extensions/Hover_Effect.php#L41)). Users must choose: live preview of Hover Interactions, or Elementor's transform tools.
- **Tilt is mousemove-driven and CPU-cheap but laggy on touch.** Mobile pointer events do not fire `mousemove` the same way; tilt is effectively a desktop-only feature.
- **No `do_action()` / `apply_filters()` extension points** — Pro cannot extend or modify behaviour without monkey-patching the class or replacing the JS.
- **Slug vs class-name divergence** (`special-hover-effect` vs `Hover_Effect`) is a footgun for anyone scripting the option key or writing `eael/registered_extensions` filters. Documented in Overview.
- **120+ controls per widget** bloats the saved settings dictionary. A widget with Hover Interactions disabled still carries the panel's default state in its serialised `_elementor_data` post meta. Storage cost is small per widget but accumulates across large sites.
- **The `before_render` method walks every setting unconditionally inside the outer `eael_hover_effect_switch` check.** If the switch is off, nothing emits — good. But when on, all 30+ sub-effects are re-checked per render. Performance fine for normal pages; relevant only at extreme widget counts.
- **JS edit-mode branch walks the entire Elementor model tree** ([`hover-effect.js:44-72`](../../src/js/view/hover-effect.js#L44)) recursively for every widget that gets the handler. Cost compounds with deeply nested layouts. Editor-only, not frontend.
- **No FA migration shim or i18n surprises**, but the panel label is wrapped with `<i class="eaicon-logo"></i>` HTML inside `__(…)` — translators may need to preserve the HTML.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when:

- The hook scope changes (widgets only → also sections / columns / containers)
- New effect families are added
- The JS handler is rewritten (e.g. to drop jQuery, or to use CSS variables instead of inline styles)
- The slug-vs-class-name mismatch is reconciled in a major version

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`docs/architecture/extensions.md`](../architecture/extensions.md) — subsystem-level overview, the registration loop, `'context' => 'view'` vs `'edit'` semantics
- **Architecture:** [`docs/architecture/asset-loading.md`](../architecture/asset-loading.md) — how `Asset_Builder` reads the registry's `dependency` block
- **Sibling extension:** [`liquid-glass-effect.md`](liquid-glass-effect.md) — the other big "applied to every widget" extension; very different architecture (CSS `selectors` + Pro injection points instead of inline-JS styling)
- **Sibling extension:** [`promotion.md`](promotion.md) — canonical extension-doc example
- **Source:** [`includes/Extensions/Hover_Effect.php`](../../includes/Extensions/Hover_Effect.php) — class implementation
- **Source:** [`src/css/view/hover-effect.scss`](../../src/css/view/hover-effect.scss) — minimal stylesheet
- **Source:** [`src/js/view/hover-effect.js`](../../src/js/view/hover-effect.js) — frontend handler
- **Public docs:** <https://essential-addons.com/docs/hover-interactions/>
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md) — naming and i18n conventions used here
- **Rules:** [`.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md) — SCSS / JS build conventions
