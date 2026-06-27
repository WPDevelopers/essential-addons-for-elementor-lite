# Liquid Glass Effect Extension

> Glassmorphism on any Elementor element — switcher + preset picker (six effects, two Lite + four Pro) + backdrop-filter / border / shadow / SVG-filter controls. The global toggle that backs the per-widget Liquid Glass injection chain documented in [`_patterns.md`](../widgets/_patterns.md#liquid-glass-injection-chain).

**Class file:** [`includes/Extensions/Liquid_Glass_Effect.php`](../../includes/Extensions/Liquid_Glass_Effect.php) (680 lines)
**Slug:** `liquid-glass-effect` (`config.php` `extensions` key)
**Public docs:** <https://essential-addons.com/docs/ea-liquid-glass-effects/>
**Pro-shared:** ⚠️ **Lite emits, Pro consumes** — the extension fires multiple `do_action()` injection points that Pro listens on to register Effects 4/5/6 controls, noise distortion, and the inline `<svg>` filter `<defs>`. See [Cross-References](#cross-references).

---

## Overview

`Liquid_Glass_Effect` is the **global** counterpart to the per-widget Liquid Glass picker documented in [`_patterns.md`](../widgets/_patterns.md#liquid-glass-injection-chain). The per-widget pattern injects a Liquid Glass Style sub-section into a small number of EA widgets (Info_Box, Creative_Button, Flip_Box, Image_Accordion, Cta_Box) targeting a specific inner selector. **This extension** instead exposes the same picker plus richer controls (border distortion, shadow effects, border-radius per preset) under **every** Elementor element — container, column, section, and every widget — applied to the element's `{{WRAPPER}}` itself.

Both surfaces share the same effect taxonomy (`effect1`-`effect6`), the same `prefix_class` (`eael_liquid_glass-`), and the same Pro injection hooks (`eael_liquid_glass_effect_bg_color_effect4` / `_5` / `_6` and the noise / SVG actions). Pro registers listeners once and both surfaces get the Pro effects together.

The Lite class itself implements: the panel registration, Lite's Effects 1 + 2 (Heavy Frost + Soft Mist) as concrete `backdrop-filter: blur(...)` rules driven by Elementor's `selectors` system, the SVG `<defs>` render hook (which is purely a `do_action` dispatch point — actual SVG markup comes from Pro), and the border-distortion / shadow / border-radius variants for the four `effect1`–`effect4` shadow presets.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Extensions/Liquid_Glass_Effect.php`](../../includes/Extensions/Liquid_Glass_Effect.php) | 680 | The class — five hook callbacks: `register_controls`, `before_render` (currently a no-op stub), `eael_liquid_glass_effect_svg_render` (widget filter), `eael_liquid_glass_effect_container_svg_render` (container action). Plus internal helpers for Lite-side effect controls |
| [`src/css/view/liquid-glass-effect.scss`](../../src/css/view/liquid-glass-effect.scss) | 106 | CSS variables `--c-light` / `--c-dark` / `--glass-reflex-*`; the `eael_liquid_glass_border_distortion_yes` heavy multi-layer box-shadow recipe; Lite's `effect1` / `effect2` `backdrop-filter` rules; shadow-preset rulesets `effect1`–`effect4` (both `.elementor-edit-area-active` editor variant and frontend) |
| [`config.php:1434`](../../config.php#L1434) | — | Registry entry — `class` + a single CSS dependency, `type => 'self'`, `context => 'view'`. **No JS dependency** — the extension is CSS-only on Lite |
| `Essential_Addons_Elementor\Traits\Helper` (used via `use HelperTrait;`) | — | Trait imported at [`Liquid_Glass_Effect.php:14`](../../includes/Extensions/Liquid_Glass_Effect.php#L14); contributes the `eael_wd_liquid_glass_*` helpers used by the per-widget picker (documented in `_patterns.md`). This extension currently uses none of the trait's helpers in its own callbacks; the `use` declaration is symmetric with the per-widget pattern |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Panel registers on container / column / section / common (every widget) | ✅ | ✅ |
| Master switch `eael_liquid_glass_effect_switch` | ✅ | ✅ |
| Six preset CHOOSE picker (`effect1`–`effect6`) with Pro lock icon | ✅ | ✅ (lock removed for 3-6) |
| Effects 1 + 2 (Heavy Frost, Soft Mist) — bg color + backdrop blur + brightness | ✅ | ✅ |
| Effects 3 / 4 / 5 / 6 (Glass Frost, Light Frost, Grain Frost, Fine Frost) | RAW_HTML upsell card only | ✅ (full controls registered via `do_action`) |
| Noise distortion controls | ❌ | ✅ |
| Border distortion switch + border radius | ✅ | ✅ |
| Shadow Effects (`effect1`–`effect4`) | ✅ (when border distortion off, presets 1+2) | ✅ (extended to presets 1, 2, 4, 5, 6) |
| Inline `<svg>` filter `<defs>` injection | ❌ (Lite fires `do_action` only) | ✅ (Pro listener emits the SVG) |
| Public filter `eael_liquid_glass_effect_filter` | ✅ | ✅ |

The class is the **Lite-side emitter** for the full Liquid Glass Effects system. Pro is consumer-only — Pro registers listeners for the actions Lite fires; it does not subclass or replace this class.

## Architecture

- **Five hooks in the constructor.** [`Liquid_Glass_Effect::__construct():18-29`](../../includes/Extensions/Liquid_Glass_Effect.php#L18) wires:
  1. `elementor/element/container/section_layout/after_section_end` — panel on container's Layout tab
  2. `elementor/element/column/section_advanced/after_section_end` — panel on column's Advanced tab
  3. `elementor/element/section/section_advanced/after_section_end` — panel on section's Advanced tab
  4. `elementor/element/common/_section_style/after_section_end` — panel on every widget's Style tab (via Advanced tab routing — see `start_controls_section` `tab => TAB_ADVANCED` at [`register_controls():187`](../../includes/Extensions/Liquid_Glass_Effect.php#L187))
  5. `elementor/frontend/before_render` priority 100 — currently a stub method
- **Two render-side hooks.** [`Liquid_Glass_Effect::__construct():25-28`](../../includes/Extensions/Liquid_Glass_Effect.php#L25) adds `elementor/widget/render_content` filter and `elementor/frontend/container/after_render` action. Both call `do_action('eael_liquid_glass_effect_svg_pro', $element, $settings)` — Lite's only purpose at render time is firing the injection point that Pro hooks to emit inline `<svg>` filter `<defs>` markup.
- **Branched register_controls based on `eael/pro_enabled`.** [`register_controls():268-294`](../../includes/Extensions/Liquid_Glass_Effect.php#L268) — when Pro is inactive, a `RAW_HTML` upsell card replaces the Pro-specific settings header; when Pro is active, the heading shows and Pro's listeners fill in the controls. This means the **same code** drives both surfaces; Pro doesn't replace `register_controls` — it adds listeners that get called by it.
- **Lite's two effects are concrete; Pro's four are placeholders.** [`register_controls():297-298`](../../includes/Extensions/Liquid_Glass_Effect.php#L297) calls `$this->eael_liquid_glass_effect_bg_color_effect($element, 'effect1', '#FFFFFF1F')` directly (Lite implements its own controls). For Pro effects, [`register_controls():301-303`](../../includes/Extensions/Liquid_Glass_Effect.php#L301) fires `do_action('eael_liquid_glass_effect_bg_color_effect4', $element, 'effect4', '')`. The naming is identical: Lite uses `$this->method(...)`, Pro uses `do_action(...)` with the same effect name.
- **The `effect3` slot is Pro-only despite being "Glass Frost".** Note the asymmetry: Effects 1+2 are Lite; Effects 3-6 are Pro (the public docs / `_patterns.md` table sometimes summarises "Effects 4/5/6" as Pro but `effect3` is also Pro). See [`register_controls():241-244`](../../includes/Extensions/Liquid_Glass_Effect.php#L241) — `effect3` gets the lock icon.
- **Border distortion is the largest single visual feature** ([`src/css/view/liquid-glass-effect.scss:8-40`](../../src/css/view/liquid-glass-effect.scss#L8)). A multi-layer inset/outset box-shadow recipe using CSS `color-mix(in srgb, ...)` and CSS variables `--glass-reflex-light` / `--glass-reflex-dark`. Toggled via `eael_liquid_glass_border_distortion` switcher whose `prefix_class` is `eael_liquid_glass_border_distortion_` — when on, the wrapper gains `eael_liquid_glass_border_distortion_yes` and CSS supplies the rest.
- **Shadow Effects is a SELECT2 sub-picker.** When border distortion is off, [`register_controls():389-410`](../../includes/Extensions/Liquid_Glass_Effect.php#L389) (Lite branch) registers a 4-option shadow-preset picker; the corresponding presets are emitted by helper methods `eael_liquid_glass_effect_border` / `_border_radius` / `_box_shadow` ([`Liquid_Glass_Effect.php:78-155`](../../includes/Extensions/Liquid_Glass_Effect.php#L78)) and the static CSS for the editor-active preview at [`src/css/view/liquid-glass-effect.scss:52-106`](../../src/css/view/liquid-glass-effect.scss#L52).
- **No `before_render` work in Lite.** [`Liquid_Glass_Effect::before_render():662-665`](../../includes/Extensions/Liquid_Glass_Effect.php#L662) reads `get_settings_for_display()` but does not modify the element. The hook is wired only so Pro (or future Lite work) has a place to plug in.

## Render Behavior

### Wrapper class assembly

Controls do the styling through Elementor's `prefix_class` mechanism:

| Control | `prefix_class` | Resulting class |
| ------- | -------------- | --------------- |
| `eael_liquid_glass_effect` (CHOOSE) | `eael_liquid_glass-` | `eael_liquid_glass-effect1`, `effect2`, … |
| `eael_liquid_glass_shadow_effect` (SELECT2) | `eael_liquid_glass_shadow-` | `eael_liquid_glass_shadow-effect1`, … |
| `eael_liquid_glass_border_distortion` (SWITCHER) | `eael_liquid_glass_border_distortion_` | `eael_liquid_glass_border_distortion_yes` |

So a typical enabled widget wrapper carries something like:

```html
<div class="elementor-widget … eael_liquid_glass-effect1 eael_liquid_glass_shadow-effect1">
```

The CSS rules in `liquid-glass-effect.scss` target those exact compound classes; backdrop-filter, box-shadow, border, and border-radius are emitted by Elementor's `selectors` system at save time from the per-control `selectors` block (e.g. `'{{WRAPPER}}.eael_liquid_glass-effect1' => 'background-color: {{VALUE}}'`).

### `do_action` injection at render

[`eael_liquid_glass_effect_svg_render($content, $element)`](../../includes/Extensions/Liquid_Glass_Effect.php#L667) (widget filter) and [`eael_liquid_glass_effect_container_svg_render($element)`](../../includes/Extensions/Liquid_Glass_Effect.php#L675) (container action) both call:

```php
do_action( 'eael_liquid_glass_effect_svg_pro', $element, $settings );
```

When Pro is inactive, the action has no listeners — no-op. When Pro is active, its listener emits an inline `<svg>` block with `<defs>` filter elements that the Pro effects' CSS references via `filter: url(#…)`. See [`_patterns.md § Pro extension hooks`](../widgets/_patterns.md#pro-extension-hooks) for the full hook signature.

### What renders when Pro is inactive but Pro effects are selected

If a user has Pro installed, configures Effect 4 (Light Frost), then deactivates Pro: the saved `eael_liquid_glass_effect` setting is still `effect4`, the wrapper still gets `eael_liquid_glass-effect4`, but no CSS rule for that class exists in Lite. The element renders **unstyled** rather than broken — the picker's `RAW_HTML` upsell card appears in the panel ([`register_controls():269-281`](../../includes/Extensions/Liquid_Glass_Effect.php#L269)) directing the user to upgrade.

## Asset Dependencies

### CSS

Registered in [`config.php:1437-1443`](../../config.php#L1437): `assets/front-end/css/view/liquid-glass-effect.min.css`, `type => 'self'`, `context => 'view'`. Loaded on frontend pages where any widget / container with Liquid Glass enabled is present (Asset_Builder makes the determination per-page from saved element settings).

The compiled CSS contains:
- CSS-variable defaults (`--c-light: #fff`, `--c-dark: #000`, `--glass-reflex-light: 2`, `--glass-reflex-dark: 2`)
- Border distortion's multi-layer box-shadow recipe
- Lite's `effect1` + `effect2` `backdrop-filter` rules
- The four shadow-preset rulesets (frontend + `.elementor-edit-area-active` editor variants)

### JS

**N/A — no JS dependency.** The Lite implementation is pure CSS. Pro may register additional JS through its own pipeline; Lite does not need any. This is the cleanest "CSS-only extension" in `includes/Extensions/`.

The absence is intentional. The visual states (preset class, shadow class, border-distortion class) are driven entirely by Elementor's `prefix_class` mechanism plus saved `selectors` rules; nothing client-side is required.

## Hook Timing

### Elementor hooks consumed (per-element panel painting)

| Hook | Priority | Callback | Target |
| ---- | -------- | -------- | ------ |
| `elementor/element/container/section_layout/after_section_end` | 10 | `register_controls` | Container Layout tab |
| `elementor/element/column/section_advanced/after_section_end` | 10 | `register_controls` | Column Advanced tab |
| `elementor/element/section/section_advanced/after_section_end` | 10 | `register_controls` | Section Advanced tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `register_controls` | Every widget — but section opens in `TAB_ADVANCED` so it lands under the Advanced tab |
| `elementor/frontend/before_render` | 100 | `before_render` | No-op stub |
| `elementor/widget/render_content` (filter) | 10, 2 args | `eael_liquid_glass_effect_svg_render` | Fires `eael_liquid_glass_effect_svg_pro` action per widget render |
| `elementor/frontend/container/after_render` | 10 | `eael_liquid_glass_effect_container_svg_render` | Fires the same action per container render |

### Hooks emitted (Pro injection surface)

| Hook | Where fired | Signature | Pro role |
| ---- | ----------- | --------- | -------- |
| `eael_liquid_glass_effect_bg_color_effect4` / `_5` / `_6` | [`register_controls():301-303`](../../includes/Extensions/Liquid_Glass_Effect.php#L301) | `($element, $effect, $default_color)` | Pro registers bg-color COLOR control for Effects 4-6 |
| `eael_liquid_glass_effect_blur_effect3` | [`register_controls():336`](../../includes/Extensions/Liquid_Glass_Effect.php#L336) | `($element, 'effect3', 0)` | Pro registers Effect 3's blur SLIDER |
| `eael_liquid_glass_effect_saturate_effect3` | [`register_controls():337`](../../includes/Extensions/Liquid_Glass_Effect.php#L337) | `($element, 'effect3', 100)` | Pro registers Effect 3's saturate SLIDER |
| `eael_liquid_glass_effect_border_radius_effect3` | [`register_controls():338`](../../includes/Extensions/Liquid_Glass_Effect.php#L338) | `($element, 'effect3', '24px')` | Pro registers Effect 3's border-radius DIMENSIONS |
| `eael_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6` | [`register_controls():340-342`](../../includes/Extensions/Liquid_Glass_Effect.php#L340) | `($element, $effect, $default_size)` | Pro registers backdrop-filter SLIDER for Effects 4-6 |
| `eael_liquid_glass_effect_noise_action` | [`register_controls():345`](../../includes/Extensions/Liquid_Glass_Effect.php#L345) | `($element)` | Pro adds noise-distortion sub-controls |
| `eael_liquid_glass_effect_svg_pro` | [`eael_liquid_glass_effect_svg_render():670`](../../includes/Extensions/Liquid_Glass_Effect.php#L670) and [`eael_liquid_glass_effect_container_svg_render():678`](../../includes/Extensions/Liquid_Glass_Effect.php#L678) | `($element, $settings)` | Pro emits inline `<svg>` `<defs>` markup at render |

These action / filter names are **shared with the per-widget Liquid Glass picker** documented in [`_patterns.md § Pro extension hooks`](../widgets/_patterns.md#pro-extension-hooks). Pro registers each listener exactly once; both surfaces (this extension + each per-widget picker that emits the same actions) get Pro's behaviour together.

### Filters consumed

| Filter | Used at | Purpose |
| ------ | ------- | ------- |
| `eael/pro_enabled` | [`register_controls():268, :347, :502`](../../includes/Extensions/Liquid_Glass_Effect.php#L268) and [`eael_pro_lock_icon():174`](../../includes/Extensions/Liquid_Glass_Effect.php#L174) | Branch between Lite (RAW_HTML upsell + Effects 1+2 only) and Pro (full controls) |
| `eael_liquid_glass_effect_filter` | [`register_controls():212-224`](../../includes/Extensions/Liquid_Glass_Effect.php#L212) | Third-party can rename the six preset labels |

## Configuration & Extension Points

### Global activation

Toggled via `eael_save_settings[liquid-glass-effect]`. Default-enabled on first install. Disable from EA Settings → Extensions to remove the panel from every element and skip the CSS bundle.

### Per-element controls (panel reference)

| Control id | Type | Notes |
| ---------- | ---- | ----- |
| `eael_liquid_glass_effect_switch` | SWITCHER | Master on/off; gates everything else |
| `eael_liquid_glass_effect_notice` | ALERT | Reminds user that semi-transparent bg colour is required for the effect to show |
| `eael_liquid_glass_effect` | CHOOSE | Six options (`effect1`-`effect6`); `prefix_class => 'eael_liquid_glass-'`; lock icon appended to Effects 3/4/5/6 when Pro inactive |
| `eael_liquid_glass_effect_pro_alert` | RAW_HTML | Lite-only — upsell shown when a Pro effect is selected; conditional on `eael_liquid_glass_effect` in `[effect3, effect4, effect5, effect6]` |
| `eael_liquid_glass_effect_bg_color_effect1` / `_2` | COLOR | Lite-registered |
| `eael_liquid_glass_effect_backdrop_filter_effect1` / `_2` | SLIDER (px) | Lite-registered; default 24 / 20 |
| `eael_liquid_glass_effect_brightness_effect2` | SLIDER | Effect 2 only; combines with backdrop blur into a single composed `backdrop-filter` value |
| `eael_liquid_glass_border_distortion` | SWITCHER | `prefix_class => 'eael_liquid_glass_border_distortion_'`; the heavy `color-mix`/`box-shadow` recipe lights up when on |
| `eael_liquid_glass_border_radious_distortion` | DIMENSIONS | Conditional on the border-distortion switch |
| `eael_liquid_glass_shadow_effect` | SELECT2 | Picker for four shadow presets `effect1`-`effect4`; conditioned on border-distortion off |
| `eael_liquid_glass_border_<effect1-4>` | GROUP_CONTROL_BORDER | One per shadow preset; conditioned on shadow-effect + main effect picker |
| `eael_liquid_glass_border_radius_<effect1-4>` | DIMENSIONS | Per shadow preset |
| `eael_liquid_glass_shadow_<effect1-4>` | GROUP_CONTROL_BOX_SHADOW | Per shadow preset |

The condition matrix is the meat of `register_controls`. Controls only appear when:
- master switch is `'yes'`, **and**
- the main preset picker matches the per-control `eael_liquid_glass_effect` condition, **and**
- (for shadow / border controls) border distortion is off, **and**
- (for `effect3`+) Pro is active.

### Filter surface

#### `eael_liquid_glass_effect_filter`

```php
add_filter( 'eael_liquid_glass_effect_filter', function ( $defaults ) {
    $defaults['styles']['effect1'] = __( 'Frosted Glass', 'my-theme' );
    return $defaults;
} );
```

Renames the six picker labels in the panel. Affects only this extension's panel — the per-widget picker has its own filter call site with the same name, so the change applies there too. See [`_patterns.md § Customise Liquid Glass picker labels`](../widgets/_patterns.md#customise-liquid-glass-picker-labels).

#### Pro `do_action` injection points

See the [Hooks emitted table above](#hooks-emitted-pro-injection-surface). Lite developers do not normally listen on these — they're Pro's contract. Third-party plugins **could** hook them to add custom controls when Pro is inactive (though doing so means duplicating Pro's responsibility, which is rarely desired).

## Customization Recipes

### Recipe 1 — Rename Liquid Glass preset labels

```php
add_filter( 'eael_liquid_glass_effect_filter', function ( $defaults ) {
    $defaults['styles']['effect1'] = __( 'Frosted', 'my-theme' );
    $defaults['styles']['effect2'] = __( 'Misted', 'my-theme' );
    return $defaults;
} );
```

Affects both this extension's panel and the per-widget Liquid Glass picker (shared filter call site).

### Recipe 2 — Suppress the extension entirely

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['liquid-glass-effect'] );
    return $exts;
} );
```

Removes the global panel. Per-widget pickers (Info_Box, Creative_Button, etc.) are unaffected — they ship their own picker via `HelperTrait` and do not depend on this class.

### Recipe 3 — Adjust global glass-reflex CSS variables

The SCSS exposes `--c-light`, `--c-dark`, `--glass-reflex-light`, `--glass-reflex-dark` at `:root`. Override in your theme:

```scss
:root {
    --c-light: #f0f8ff;     /* warmer highlight tone */
    --c-dark: #000814;
    --glass-reflex-light: 3;
    --glass-reflex-dark: 1.5;
}
```

Affects the `color-mix(...)` calculations inside `.eael_liquid_glass_border_distortion_yes` ([`src/css/view/liquid-glass-effect.scss:8-40`](../../src/css/view/liquid-glass-effect.scss#L8)).

### Recipe 4 — Add a custom listener for the Pro injection points (advanced)

```php
add_action( 'eael_liquid_glass_effect_svg_pro', function ( $element, $settings ) {
    if ( empty( $settings['eael_liquid_glass_effect_switch'] ) ) {
        return;
    }
    // Emit your own <svg> filter <defs> here when Pro is inactive.
    echo '<svg style="position:absolute;width:0;height:0"><defs>…</defs></svg>';
}, 10, 2 );
```

Lite fires this action; Pro normally listens. Adding a third-party listener provides custom SVG filter markup independent of Pro.

## Common Issues

### Effect doesn't appear despite enabling the switch

- **Likely cause:** No semi-transparent background colour on the element. The extension's own info alert ([`register_controls():200-210`](../../includes/Extensions/Liquid_Glass_Effect.php#L200)) warns about this. Without a background colour, there's nothing for `backdrop-filter` to blur visibly.
- **Diagnose:** Inspect the element — confirm a `background-color: rgba(...)` or `#FFFFFF1F`-style value is present.
- **Fix:** Set a background colour with alpha < 1 (the extension supplies default `#FFFFFF1F` for Lite effects, but only when the per-effect bg-color control is opened and saved).

### Effects 3/4/5/6 selected but element renders unstyled

- **Likely cause:** Pro is not active. The picker still saves the value (`eael_liquid_glass_effect = effect4` etc.), so the `eael_liquid_glass-effect4` class lands on the wrapper, but no CSS rule for it exists in Lite.
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` should return `true` for Pro effects to work.
- **Fix:** Activate Pro, or pick Effect 1 or 2 in Lite.

### Border distortion + shadow effect conflict

- **Likely cause:** When border distortion is on, the SELECT2 shadow picker is conditioned out (`'eael_liquid_glass_border_distortion!' => 'yes'` at [`register_controls():406`](../../includes/Extensions/Liquid_Glass_Effect.php#L406)). User toggled distortion on but expected the shadow to apply too.
- **Diagnose:** Check the panel — the Shadow Effects picker should disappear when distortion is on.
- **Fix:** Choose one or the other; the two visual systems collide.

### Panel doesn't appear on Containers

- **Likely cause:** Older Elementor without container support, or extension disabled. The hook `elementor/element/container/section_layout/after_section_end` is specific to the new Container element ([`__construct():20`](../../includes/Extensions/Liquid_Glass_Effect.php#L20)).
- **Diagnose:** Confirm Elementor version supports containers, and `eael_save_settings[liquid-glass-effect] = 1`.
- **Fix:** Upgrade Elementor; enable the extension.

### Per-widget Liquid Glass picker vs global extension confusion

- **Symptom:** "I see a Liquid Glass picker on my Info Box widget but not on a Heading widget."
- **Cause:** The per-widget picker (Info_Box, Creative_Button, Flip_Box, Image_Accordion, Cta_Box) is **different** from this extension's panel — it lives inside the widget's own Style tab and targets an inner selector. The global extension's panel (this class) appears on every widget under the Advanced tab and targets `{{WRAPPER}}`.
- **Diagnose:** Look for "Liquid Glass Effects" under Advanced (this extension) vs "Liquid Glass" or similar under Style (per-widget picker).
- **Fix:** Both exist intentionally. See [`_patterns.md`](../widgets/_patterns.md#liquid-glass-injection-chain).

### SVG `<defs>` never rendered

- **Likely cause:** Pro is inactive — Lite does not emit any SVG markup; it only fires `do_action('eael_liquid_glass_effect_svg_pro')`. With no listener, no SVG.
- **Diagnose:** View page source, search for `eael_liquid_glass_effect_svg_pro` — won't be there. Search for `<filter id="`. Missing for Lite.
- **Fix:** Activate Pro, or emit your own SVG via Recipe 4.

## Debugging Guide

When the extension misbehaves:

1. **Confirm activation.** `var_dump( get_option( 'eael_save_settings' )['liquid-glass-effect'] ?? null )` should be `1`.
2. **Confirm the constructor ran.** Add `error_log( 'LGE ctor' )` at [`Liquid_Glass_Effect::__construct()`](../../includes/Extensions/Liquid_Glass_Effect.php#L18). If never logged, the extension is disabled or its class file isn't autoloaded.
3. **Confirm hooks wired.** `var_dump( has_action( 'elementor/element/container/section_layout/after_section_end' ) )` should include this class's callback.
4. **Confirm class assembly.** Inspect a saved widget's wrapper — should see `eael_liquid_glass-effect1` (or the picked effect). If missing, `prefix_class` didn't apply (check the picker's saved value).
5. **Confirm CSS loaded.** DevTools Network — `liquid-glass-effect.min.css` should be in the page's stylesheets. If missing, Asset_Builder didn't see the extension as active (check option) or saw no element on the page using it (check page detection).
6. **Pro effects look unstyled** — already covered above; check `eael/pro_enabled`.
7. **Border distortion variables** — DevTools Computed → CSS variables panel; check `--c-light`, `--c-dark`, `--glass-reflex-light`, `--glass-reflex-dark` values.
8. **SVG injection** — search the rendered HTML for `<svg`; with Pro active, expect at least one filter `<defs>` block per liquid-glass-enabled widget.
9. **Per-widget vs global picker** — establish which surface you're debugging. They share the action / filter names but live in different code paths.

## Architecture Decisions

### Shared `do_action` hook names between this extension and per-widget picker

- **Context:** Both the global extension (this class) and the per-widget Liquid Glass picker (in `HelperTrait`, used by Info_Box etc.) emit Pro injection points for Effects 4-6 controls and the SVG `<defs>`. If they used different action names, Pro would have to register listeners twice.
- **Decision:** Both surfaces fire the same action names: `eael_liquid_glass_effect_bg_color_effect4` / `_5` / `_6`, `eael_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6`, `eael_liquid_glass_effect_noise_action`, `eael_liquid_glass_effect_svg_pro`.
- **Alternatives rejected:** Separate action namespaces per surface (`eael_lge_global_bg_color_effect4` vs `eael_lge_widget_bg_color_effect4`) — would force Pro to duplicate every listener. Single class instead of two surfaces — collapses the global "every element" and per-widget "specific selector" concerns into one piece that's harder to maintain.
- **Consequences:** Pro's listener registry is half the size; this extension's panel and a widget's picker share the same Pro extension behaviour. Mental cost: there are two emitters per action; debugging requires checking both.

### CSS-only Lite implementation (no JS dependency)

- **Context:** Hover_Effect and Image_Masking ship JS; Liquid Glass could too (e.g. to dynamically tune `backdrop-filter` based on viewport).
- **Decision:** Lite is 100% CSS. State is driven via Elementor's `prefix_class` mechanism. No script runs at frontend.
- **Alternatives rejected:** JS-driven blur tuning (mobile performance cost, complexity); CSS-in-JS via inline `<style>` blocks (more bytes per page).
- **Consequences:** Cannot do runtime calculations (e.g. adaptive blur). The trade-off is bytes-saved + simplicity. Pro adds JS as needed for noise/SVG features.

### Branched `register_controls` rather than two methods

- **Context:** Pro and Lite need different control sets — Lite shows `RAW_HTML` upsell + restricted shadow conditions; Pro shows the heading + extended shadow conditions covering `effect4`/`effect5`/`effect6`.
- **Decision:** One method, two branches inside (`if (! apply_filters('eael/pro_enabled', false))`) at multiple call sites.
- **Alternatives rejected:** Two separate methods (`register_controls_lite` / `register_controls_pro`) dispatched in the constructor — would force Pro to know the constructor structure to wire its listeners differently.
- **Consequences:** [`register_controls()`](../../includes/Extensions/Liquid_Glass_Effect.php#L181) is 480 lines with two large branches that duplicate much of the shadow / border / radius setup (one for each Pro state). Maintenance pain — additions must usually be made in both branches.

### `_rear` variant lives in `_patterns.md`, not here

- **Context:** Flip Box's per-widget picker emits `_rear`-suffixed action variants (`eael_wd_liquid_glass_effect_svg_pro_back`, etc.) for the back face. This extension targets `{{WRAPPER}}` only — there's no concept of "front + rear" for an arbitrary widget / container / column / section wrapper.
- **Decision:** Don't implement `_rear` here. The `_rear` chain is exclusively Flip Box's domain via `HelperTrait`.
- **Alternatives rejected:** Add a "two-sided" toggle to this extension that emits the rear chain — meaningless for non-Flip-Box elements; would confuse users.
- **Consequences:** Flip Box's per-widget picker is the only emitter of `_rear` actions. Documented at [`_patterns.md § Rear variant`](../widgets/_patterns.md#rear-variant-flip-box-only).

## Known Limitations

- **`before_render` is a no-op stub** ([`Liquid_Glass_Effect.php:662-665`](../../includes/Extensions/Liquid_Glass_Effect.php#L662)). It reads settings then returns. Hook is wired purely for future / Pro extension; today, no work happens.
- **Effects 3-6 render unstyled when Pro is inactive** but the saved class is on the wrapper. Switching back to Effect 1 or 2 requires editing the widget; no automatic fallback. Documented in Common Issues.
- **The class file's two main branches** ([`register_controls():347-501`](../../includes/Extensions/Liquid_Glass_Effect.php#L347) Lite branch vs [`:502-657`](../../includes/Extensions/Liquid_Glass_Effect.php#L502) Pro branch) duplicate much of the shadow / border / radius setup — additions must be made in both.
- **No `view`-context JS** means runtime calculations are impossible. Adaptive blur based on viewport width, ambient light, etc. requires either Pro or a custom listener.
- **`prefix_class` collisions** — if a third party also uses `eael_liquid_glass-` prefixed classes for their own purposes, the wrapper assembly would conflict. Unlikely in practice but worth noting.
- **Container action hook fires for every container regardless** — even containers without Liquid Glass enabled trigger `eael_liquid_glass_effect_svg_pro` via [`eael_liquid_glass_effect_container_svg_render():675-679`](../../includes/Extensions/Liquid_Glass_Effect.php#L675). Pro's listener has to filter by settings; tiny overhead per container.
- **The widget render-content filter signature** (`eael_liquid_glass_effect_svg_render($content, $element)`) returns `$content` unchanged. Only its side effect (`do_action`) matters. Using `elementor/widget/render_content` is heavier than a no-op `do_action` source would be, but Elementor doesn't offer a per-widget post-render action with the right signature.
- **The `use HelperTrait;` declaration** at [`Liquid_Glass_Effect.php:14`](../../includes/Extensions/Liquid_Glass_Effect.php#L14) imports many helpers but the class uses none — pure code-clean-up gap. Safe to remove if the trait's methods are confirmed unreferenced from this class.
- **`teaser_template`** here ([`:160-170`](../../includes/Extensions/Liquid_Glass_Effect.php#L160)) is a duplicate of `Promotion::teaser_template` with slight differences (no icon, no title). The two could be unified into a single helper.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when:

- New effect presets are added (currently fixed at 6)
- The Pro injection action names change (would break Pro compatibility)
- A `view`-context JS is added
- The Lite / Pro register_controls branches are refactored to share code
- The container support hook changes (Elementor renames `elementor/frontend/container/after_render`)

Format: `version — description (#card)`.

## Cross-References

- **Shared pattern:** [`docs/widgets/_patterns.md § Liquid Glass injection chain`](../widgets/_patterns.md#liquid-glass-injection-chain) — the per-widget side of the same system; this extension and that pattern share Pro hook names
- **Architecture:** [`docs/architecture/extensions.md`](../architecture/extensions.md) — subsystem overview, the registration loop, why `register_controls` runs four times (one per element-type hook)
- **Architecture:** [`docs/architecture/asset-loading.md`](../architecture/asset-loading.md) — CSS-only `view`-context loading path
- **Sibling extension:** [`special-hover-effect.md`](special-hover-effect.md) — the other "applied to every widget" extension, but with inline JS styling instead of `prefix_class` + CSS
- **Sibling extension:** [`promotion.md`](promotion.md) — the canonical extension-doc example; shows the `RAW_HTML` upsell pattern this class also uses
- **Source:** [`includes/Extensions/Liquid_Glass_Effect.php`](../../includes/Extensions/Liquid_Glass_Effect.php)
- **Source:** [`src/css/view/liquid-glass-effect.scss`](../../src/css/view/liquid-glass-effect.scss)
- **Source:** [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) — `HelperTrait` houses the per-widget picker helpers (`eael_wd_liquid_glass_*`)
- **Public docs:** <https://essential-addons.com/docs/ea-liquid-glass-effects/>
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md)
- **Rules:** [`.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
