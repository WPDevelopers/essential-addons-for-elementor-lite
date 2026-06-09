# Image Accordion Widget

> Horizontal or vertical row of background-image panels that expand on hover or click. The active panel grows (`flex: 3`); inactive panels shrink (`flex: 1`). Each panel has a title, content, and an optional title link revealed inside an overlay. Pro injects the Liquid Glass effect chain onto the active panel's overlay.

**Class file:** [`includes/Elements/Image_Accordion.php`](../../includes/Elements/Image_Accordion.php)
**Slug:** `image-accordion` (widget id `eael-image-accordion`)
**Public docs:** <https://essential-addons.com/elementor/docs/image-accordion/>
**Pro-shared:** ✅ Yes — front-only Liquid Glass injection chain (no rear variant). Pro registers listeners for `eael_wd_liquid_glass_effect_bg_color_effect4/5/6`, `_backdrop_filter_effect4/5/6`, `_noise_action`, `_svg_pro` — same pattern as Info_Box. Selector target is `.eael-img-accordion .overlay-active .overlay`. There is **no** standard `eael_section_pro` upsell panel; Pro upsell only appears within the Liquid Glass section.

---

## Overview

Image Accordion lays out a row (or column) of equal-flex panels, each carrying a background image and a hidden overlay with title and content. On user interaction (hover or click), the targeted panel becomes "active" — its `flex` grows from `1` to `3`, the other panels shrink to `1`, and the overlay's inner content fades in. The interaction is class-driven: JS toggles `overlay-active` on the panel wrapper and `overlay-inner-show` on the inner overlay; SCSS handles the rest.

The widget mixes server-side and runtime CSS — the panel's background image and any pre-selected active state are emitted inline at render time (`style="background-image: …; flex: 3 1 0%;"`), and `render()` also emits an inline `<style>` block at the end for hover-mode CSS scoped to the widget's id. The interaction handler in `image-accordion.js` is short (~50 lines) and uses EA's `eael.hooks.addAction("init", "ea", …)` registration pattern, which is newer than the more common `jQuery(window).on("elementor/frontend/init", …)` pattern.

## Features

- Two interaction modes: On Hover (default — hover any panel) or On Click (click toggles active)
- Two directions: Horizontal (default — row of panels) or Vertical (column)
- Per-item background image (Repeater)
- Per-item title with selectable HTML tag (`h1`–`h6`, `span`, `p`, `div`)
- Per-item WYSIWYG content
- Optional title link wrapping the title text inside an `<a>` (per-item, with separate enable switch)
- "Make it active?" switch on any item to set the initial active panel server-side
- Content horizontal alignment (left / center / right) at widget level
- Content vertical alignment (top / center / bottom) at widget level
- Liquid Glass effects on the active panel's overlay (front-only — no rear variant)
  - Lite: Effects 1 (Heavy Frost) and 2 (Soft Mist)
  - Pro: Effects 4 (Light Frost), 5 (Grain Frost), 6 (Fine Frost) plus noise and SVG `<defs>` injection
- Keyboard-focusable panels (`<div tabindex="0">`)

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Hover and Click interaction modes | ✅ | ✅ |
| Horizontal and Vertical direction | ✅ | ✅ |
| Per-item bg image, title, content, link | ✅ | ✅ |
| Initial active panel via "Make it active?" switch | ✅ | ✅ |
| Liquid Glass Effects 1 + 2 | ✅ | ✅ |
| Liquid Glass Effects 4 / 5 / 6 | ❌ — locked, shows upsell card | ✅ via `eael_wd_liquid_glass_effect_bg_color_effect4/5/6` |
| Liquid Glass noise distortion | ❌ — `_noise_action` is a no-op | ✅ |
| Liquid Glass SVG `<defs>` filter injection | ❌ — `_svg_pro` is a no-op | ✅ |
| Liquid Glass Shadow Effects (4 presets) | ❌ — Pro upsell card replaces controls | ✅ |
| Standard `eael_section_pro` upsell panel | ❌ — none present | — |

The widget does not ship a top-level upsell section. The only upsell surface is inside the Liquid Glass Effects section (which itself is conditional on `eael_wd_liquid_glass_effect_switch === 'yes'`). Users who don't enable Liquid Glass never see any Pro messaging.

## Use Cases

- Service / category showcase row — three to six panels each linking to a deeper page
- Portfolio teaser where hovering a panel reveals the project title and description
- "Meet the team" row with name + role revealed on hover
- Image-heavy hero section with click-to-expand interaction on mobile (responsive design considerations apply)
- Process / step-by-step explainer where each step is a clickable panel

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Image_Accordion.php`](../../includes/Elements/Image_Accordion.php) | PHP widget class — controls, render, inline-style emission, Liquid Glass section |
| [`src/css/view/image-accordion.scss`](../../src/css/view/image-accordion.scss) | Source styles — direction, alignment, active / inactive states, overlay transitions |
| [`src/js/view/image-accordion.js`](../../src/js/view/image-accordion.js) | Frontend logic — hover / click toggle of `overlay-active` and `overlay-inner-show` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `HelperTrait` — provides `eael_wd_liquid_glass_effect_bg_color_effect()` and `_backdrop_filter_effect()` helpers ([line 640+](../../includes/Traits/Helper.php#L640)) |
| [`config.php`](../../config.php#L444) entry `'image-accordion'` | `Asset_Builder` dependency declaration (CSS + JS) |
| `assets/front-end/css/view/image-accordion.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/image-accordion.min.js` | Built output (do not edit) |

## Architecture

- **Class name `eael-img-accordion` (with `img-`) on the root** even though the widget id is `eael-image-accordion` and the slug is `image-accordion`. The JS reads `.eael-img-accordion` ([line 2](../../src/js/view/image-accordion.js#L2)) and PHP emits `eael-img-accordion` on the wrapper. This is legacy naming — renaming would break theme CSS.
- **`flex: 1` / `flex: 3` toggle drives the visual expansion** — SCSS sets `flex: 1` by default on `.eael-image-accordion-hover`. On the active panel, JS adds `overlay-active` and writes `element.css('flex', '3')`. Inactive panels are reset to `flex: 1`. Smooth transition via SCSS `transition` on flex-basis.
- **Inline `<style>` emitted in `render()` for hover mode** — when interaction is on-hover, `render()` ([line 1105](../../includes/Elements/Image_Accordion.php#L1105)) outputs a `<style>` block scoped to `#eael-img-accordion-<id>` with `:hover` rules that mirror what JS does for click mode. The `<style>` tag has a typo `typr="text/css"` (should be `type="text/css"`) — browsers ignore unknown attributes and the default MIME for inline `<style>` is `text/css`, so it still works.
- **JS registration via EA's newer `eael.hooks.addAction("init", "ea", …)` pattern** — instead of the more common `jQuery(window).on("elementor/frontend/init", …)` used by older widgets, Image Accordion uses EA's wrapper which guards against double-registration internally. No explicit `elementStatusCheck` guard like Fancy Text uses.
- **Liquid Glass selector targets `.overlay-active .overlay`** — the glass effect is only visible on the currently-active panel; inactive panel overlays are hidden so the backdrop-filter has nothing to blur. The SVG `<defs>` injection (Pro) targets `.overlay-active` directly ([line 1076](../../includes/Elements/Image_Accordion.php#L1076)).
- **Server-side active state via "Make it active?" switch** — items with `eael_accordion_is_active === 'yes'` get `overlay-active` class plus `flex: 3 1 0%;` inline style at render time, so the first paint shows the active panel without waiting for JS.
- **No `eael_section_pro` upsell** — unlike Cta_Box, Info_Box, Flip_Box, Pricing_Table that all have a standalone upsell section, Image_Accordion only shows Pro messaging inside the Liquid Glass effects section. The widget's standard panel surface is Lite-clean.

## Render Output

The widget produces the following DOM structure. Annotated for default config (on-hover, horizontal, four items, none active); conditional elements marked `[?]`.

```html
<div id="eael-img-accordion-<widget-id>"
     class="eael-img-accordion accordion-direction-horizontal
            eael-img-accordion-horizontal-align-center
            eael-img-accordion-vertical-align-center"
     data-img-accordion-id="<widget-id>"
     data-img-accordion-type="on-hover">

  <div class="eael-image-accordion-hover eael-image-accordion-item [overlay-active]"
       style="background-image: url(…); [flex: 3 1 0%;]"
       tabindex="0">
    <div class="overlay">
      [?] <svg>…liquid-glass defs (Pro only)…</svg>
      <div class="overlay-inner [overlay-inner-show]">
        [?] <a href="…">
          <h2 class="img-accordion-title">Accordion item title</h2>
        [?] </a>
        <p>Accordion content goes here!</p>
      </div>
    </div>
  </div>

  <!-- … three more panels … -->
</div>

[?] <style typr="text/css">  <!-- on-hover mode only; note typo `typr` (does no harm) -->
  #eael-img-accordion-<widget-id> .eael-image-accordion-hover:hover {
    flex: 3 1 0% !important;
  }
  #eael-img-accordion-<widget-id> .eael-image-accordion-hover:hover:hover .overlay-inner * {
    opacity: 1;
    visibility: visible;
    transform: none;
    transition: all .3s .3s;
  }
</style>
```

Notes:

- The styling root is `.eael-img-accordion` (with `img-`, not `image-`). The JS scope target is the same element via `$scope.find(".eael-img-accordion").eq(0)`.
- Per-item wrapper carries `style="background-image: …"` always; `style="… flex: 3 1 0%;"` only when the item was pre-selected as active.
- Per-item wrapper class always includes both `eael-image-accordion-hover` and `eael-image-accordion-item`. The `-hover` suffix is misleading — it's present in click mode too. The class is the JS target selector.
- The inline `<style>` block emitted for on-hover mode includes `:hover:hover` (double pseudo-class) — works but unusual; harmless duplication.
- `<div tabindex="0">` enables keyboard focus on each panel, but keyboard activation does not toggle the active state — only mouse hover or click.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Image_Accordion.php#L67) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_img_accordion_type` | SELECT | `on-hover` | Content → General | `data-img-accordion-type`; JS interaction branch |
| `eael_img_accordion_direction` | SELECT | `accordion-direction-horizontal` | Content → General | Root class (`accordion-direction-horizontal` / `-vertical`) |
| `eael_img_accordion_content_horizontal_align` | CHOOSE | `center` | Content → General | Root class (`eael-img-accordion-horizontal-align-…`) |
| `eael_img_accordion_content_vertical_align` | CHOOSE | `center` | Content → General | Root class (`eael-img-accordion-vertical-align-…`) |
| `title_tag` | SELECT | `h2` | Content → General | Title HTML element |
| `eael_img_accordions` | REPEATER | 4 default items | Content → General | Per-item bg image, title, content, active state, link |
| `eael_wd_liquid_glass_effect_switch` | SWITCHER | off | Content → General | Unlocks the Liquid Glass section |
| `eael_accordion_height` | TEXT (px only) | `400` | Style → General | `height` on `.eael-img-accordion` (typed without unit; widget appends `px`) |
| `eael_accordion_bg_color` | COLOR | empty | Style → General | `background-color` on `.eael-img-accordion` |
| `eael_accordion_container_padding` | DIMENSIONS (responsive) | empty | Style → General | Container padding |
| `eael_wd_liquid_glass_effect` | CHOOSE | `effect1` | Style → Liquid Glass | `prefix_class` `eael_wd_liquid_glass-%s` on wrapper |
| `eael_wd_liquid_glass_effect_brightness_effect2` | SLIDER | `1` | Style → Liquid Glass | `brightness()` in `backdrop-filter` (Effect 2 only) |

### Per-item Repeater controls

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_accordion_is_active` | SWITCHER | off | Adds `overlay-active` class + `flex: 3 1 0%;` inline style at render |
| `eael_accordion_bg` | MEDIA | placeholder accordion.png | `background-image` URL on the panel wrapper |
| `eael_accordion_tittle` | TEXT (dynamic) | `"Accordion item title"` | `.img-accordion-title` text (typo: `tittle` not `title`) |
| `eael_accordion_content` | WYSIWYG | sample text | `<p>` inside `.overlay-inner` |
| `eael_accordion_enable_title_link` | SWITCHER | on | Wraps title in `<a href="…">` |
| `eael_accordion_title_link` | URL (dynamic) | `#` | Title link target (conditional on enable switch) |

Plus Style sections for Title (typography, color, hover color, padding), Content (typography, color, padding), Overlay (background, border, padding, transition timing). The Liquid Glass section is conditional on its switch.

## Conditional Dependencies

```text
eael_accordion_title_link               → visible when eael_accordion_enable_title_link == 'yes'

# Liquid Glass section is conditional on:
eael_wd_liquid_glass_effect_section     → visible when eael_wd_liquid_glass_effect_switch == 'yes'

eael_wd_liquid_glass_effect_pro_alert   → visible when Pro NOT active
                                          AND eael_wd_liquid_glass_effect in ['effect4','effect5','effect6']
eael_wd_liquid_glass_effect_brightness_effect2
                                        → visible when eael_wd_liquid_glass_effect == 'effect2'
eael_wd_liquid_glass_shadow_effect      → visible when eael_wd_liquid_glass_effect_switch == 'yes'
                                          AND Pro NOT active (Lite teaser)
```

No top-level Pro upsell condition (no `eael_section_pro`).

## Behavior Flow

1. User drops the widget → `register_controls()` runs. The Liquid Glass picker calls `apply_filters('eael_liquid_glass_effect_filter', …)` for label customisation.
2. User configures direction, interaction mode, content alignment, and the Repeater with bg-image / title / content per panel.
3. Optionally toggles "Make it active?" on one item → that item starts active server-side.
4. Editor preview re-renders via [`render()`](../../includes/Elements/Image_Accordion.php#L1032).
5. `render()` composes the root class list from direction + alignment settings + the widget's id-based hover-mode `<style>` block.
6. For each Repeater item, emits inline `style="background-image: url(…)"` plus `flex: 3 1 0%;` if pre-active; adds `overlay-active` class if pre-active; emits `<div tabindex="0">` for focus support.
7. Inside each panel: `<div class="overlay">` with `do_action('eael_wd_liquid_glass_effect_svg_pro', $this, $settings, '.overlay-active')` for Pro's SVG `<defs>` injection.
8. Each panel emits an `.overlay-inner` (with `overlay-inner-show` when active) containing the title (optionally wrapped in `<a>`) and the content paragraph.
9. After all panels: if interaction mode is on-hover, emit a `<style typr="text/css">` block with hover rules scoped to the widget id.
10. Browser receives static HTML. Pro (if active) has registered its handlers for the Liquid Glass actions; Lite handlers register Effects 1 and 2 controls via the trait.
11. EA's `eael.hooks` fires `init` → the registered handler runs `elementorFrontend.hooks.addAction("frontend/element_ready/eael-image-accordion.default", ImageAccordion)`.
12. For each widget on the page, the `ImageAccordion` handler runs: finds `.eael-image-accordion-hover` panels within `$scope`; branches on `data-img-accordion-type`.
13. On-click mode: binds `click` to each panel → on click, removes `overlay-active` and `overlay-inner-show` from all panels, then adds them to the clicked panel; sets clicked panel `flex: 3`, others `flex: 1`.
14. On-hover mode: binds `mouseenter` (via `.hover(handler)`) and `mouseleave` to each panel; the inline `<style>` block in HTML also handles the `:hover` state — JS adds the active classes, CSS handles the `:hover` flex transition.
15. CSS transition animates `flex` change; overlay-inner contents fade in via `transition: all .3s .3s;`.

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", () => { elementorFrontend.hooks.addAction("frontend/element_ready/eael-image-accordion.default", ImageAccordion); });` — registration is wrapped in EA's `eael.hooks` rather than `jQuery(window).on("elementor/frontend/init")`. This is the newer EA pattern shared with Advanced Tabs, Content Ticker, Product Grid, and Login/Register.
- **Guard:** none — `eael.hooks.addAction` internally prevents double-registration for the same `(action, namespace)` pair. No explicit `elementStatusCheck`.
- **Reads on init:** `$scope.find(".eael-img-accordion").eq(0)` for the widget container; per-item `data-img-accordion-type` for branch.
- **On-click branch:** binds `click` once on each `.eael-image-accordion-hover` panel; on click, removes `overlay-active` from siblings, adds it to the clicked one, swaps `flex: 1` / `flex: 3`, swaps `overlay-inner-show`.
- **On-hover branch:** uses jQuery's `.hover()` (mouseenter shortcut) + a separate `.mouseleave()` handler; mouseenter calls `hoverAction()`, mouseleave calls `hoverOutAction()` which removes `overlay-active` from all panels and resets flex.
- **Runtime state:** none persistent; class state on DOM is the truth.
- **Known JS issue:** `console.log('leave')` debug statement is left in `hoverOutAction` ([line 48](../../src/js/view/image-accordion.js#L48)) — pollutes the browser console on every mouseleave. Cleanup target.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one Image Accordion widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `image-accordion.min.css` | self (built from `src/css/view/image-accordion.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `image-accordion.min.js` | self | Hover / click toggle of `overlay-active` | Always when widget present |

No vendor libraries. Font Awesome icons (used only in the Elementor panel UI, not in the rendered widget) come via Elementor's `font-awesome-5-all` handle.

## Hooks & Filters

The widget's public contract — Pro consumes the Liquid Glass actions; the `eael_liquid_glass_effect_filter` is open for label customisation.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_liquid_glass_effect_filter` | filter (emitted) | `array $defaults` with key `styles` | Customise Liquid Glass picker labels ([line 630](../../includes/Elements/Image_Accordion.php#L630)) |
| `eael_wd_liquid_glass_effect_bg_color_effect4` / `_5` / `_6` | action (emitted) | `(Widget_Base $widget, string $effect, string $default_color, string $selector)` | Pro registers bg-color controls for Effects 4–6 ([lines 711-713](../../includes/Elements/Image_Accordion.php#L711)) |
| `eael_wd_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6` | action (emitted) | same | Pro registers backdrop-filter controls for Effects 4–6 ([lines 745-747](../../includes/Elements/Image_Accordion.php#L745)) |
| `eael_wd_liquid_glass_effect_noise_action` | action (emitted) | `(Widget_Base $widget)` | Pro adds noise-distortion controls ([line 750](../../includes/Elements/Image_Accordion.php#L750)) |
| `eael_wd_liquid_glass_effect_svg_pro` | action (emitted) | `(Widget_Base $widget, array $settings, string $selector)` | Pro injects inline `<svg>` with filter `<defs>` inside each panel's `.overlay` at render ([line 1076](../../includes/Elements/Image_Accordion.php#L1076)) |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides upsell controls inside the Liquid Glass section; toggles Pro lock icon on Effects 4/5/6 |

The Liquid Glass actions are widget-agnostic — the same names fire from Info_Box, Flip_Box (front-only), and Creative Button. Image Accordion is the second widget in this docs run (after Info Box) to use the front-only variant.

⚠️ The `do_action('eael_wd_liquid_glass_effect_svg_pro', …)` fires inside the per-panel `<div class="overlay">` loop — Pro's handler injects the `<svg>` markup for every panel rendered, not only the active one. The SVG filter only matters for the active panel due to CSS `:not(.overlay-active)` hiding the others' overlays.

## Customization Recipes

### Recipe 1 — Make the active panel fill the entire row (no shrunken siblings)

```scss
.eael-img-accordion .eael-image-accordion-hover.overlay-active {
    flex: 99 !important;
}
.eael-img-accordion .eael-image-accordion-hover:not(.overlay-active) {
    flex: 0 !important;
    opacity: 0.3;
}
```

Set a Custom CSS Class on the widget and scope the rules. The default `flex: 1` vs `flex: 3` ratio gives a balanced spread; this override turns it into a slide-show effect.

### Recipe 2 — Disable the click-anywhere behaviour and require an explicit button

```scss
.eael-img-accordion .eael-image-accordion-hover .overlay {
    pointer-events: none;  /* disable click bubbling */
}
.eael-img-accordion .eael-image-accordion-hover .overlay a {
    pointer-events: auto;  /* but re-enable on the title link */
}
```

Combined with click mode (`data-img-accordion-type="on-click"`), this restricts navigation to the title link only. The active panel can no longer be toggled by clicking the panel background.

### Recipe 3 — Add a smooth-transition delay on the overlay-inner fade-in

```scss
.eael-img-accordion .overlay-inner * {
    transition: all 0.6s 0.4s !important;  /* slower fade, longer delay */
}
```

The default transition is `all .3s .3s`. This recipe extends both duration and delay for a more dramatic reveal.

### Recipe 4 — Inject custom HTML into every panel overlay via a child plugin

```php
add_action( 'eael_wd_liquid_glass_effect_svg_pro', function ( $widget, $settings, $selector ) {
    if ( $widget->get_name() !== 'eael-image-accordion' ) {
        return;
    }
    echo '<span class="my-corner-badge">New</span>';
}, 5, 3 );
```

⚠️ Hijacking the SVG-injection hook for non-SVG output works because Lite invokes it at the right DOM position, but Pro's own handler also fires at priority 10. Use priority 5 to insert before Pro's content; or check `apply_filters('eael/pro_enabled', false)` to avoid duplication.

## Common Issues

### Active panel doesn't expand on hover

- **Likely cause:** the on-hover `<style>` block emitted by `render()` is targeting `#eael-img-accordion-<id>`, but a theme has overridden `.eael-img-accordion .eael-image-accordion-hover` with higher specificity that locks `flex`
- **Diagnose:** in DevTools inspect the active panel — does `flex: 3` show as overridden?
- **Fix:** raise selector specificity in your override; or switch the widget to On Click mode to use JS-driven inline `flex: 3` which beats most CSS

### Click mode doesn't deactivate the previous panel

- **Likely cause:** the click handler removes `overlay-active` from every `.eael-image-accordion-hover` and only adds it to the clicked one — but if the click target is inside `.overlay-inner` (e.g. the title link), the click bubbles to the panel but the link navigates first
- **Diagnose:** check the link target — does the page navigate before the deactivation can apply?
- **Fix:** the panel toggle is supposed to happen client-side; if you need both behaviours, intercept the link's click event and `e.preventDefault()` before manually calling the panel toggle

### Server-side pre-active panel doesn't show as active on first paint

- **Likely cause:** more than one item has `eael_accordion_is_active === 'yes'` — only the first item with the flag actually expands (CSS `flex: 3` × N panels = unpredictable layout); the JS handler later may reset
- **Diagnose:** check the Repeater — does only one item have the switch on?
- **Fix:** turn off "Make it active?" on all but one item

### Liquid Glass effect doesn't appear on the active panel

- **Likely cause:** the `eael_wd_liquid_glass_effect_switch` is off — without it the Liquid Glass section is hidden in the panel and no `eael_wd_liquid_glass-<effect>` class is applied; or the active panel's `.overlay` has a fully opaque background colour
- **Diagnose:** check the wrapper for `eael_wd_liquid_glass-effect1` (or similar); inspect the `.overlay` background-color — is it semi-transparent?
- **Fix:** toggle the switch on (Content → General); the section banner reminds the user that a semi-transparent background is required for the effect to be visible

### Pro Effect 4/5/6 picked but no styling

- **Likely cause:** Pro is not active; the picker shows Effects 4–6 with a pad-lock icon but lets the user pick them; the `eael_wd_liquid_glass-effect4` class is emitted but no matching CSS rule fires
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` — should be `true` with Pro
- **Fix:** activate Pro; or pick Effect 1 / 2 which are Lite-supported

### `console.log('leave')` spam in the browser console

- **Likely cause:** debug statement left in `hoverOutAction` ([line 48 of image-accordion.js](../../src/js/view/image-accordion.js#L48))
- **Diagnose:** open DevTools — every mouseleave on a panel logs `leave`
- **Fix:** remove the line and run `npm run build`; not user-fixable without modifying the source

### Inline `<style typr="text/css">` raises a console warning in strict HTML validators

- **Likely cause:** the typo `typr` instead of `type` ([line 1105 of Image_Accordion.php](../../includes/Elements/Image_Accordion.php#L1105)) makes the attribute unknown to HTML validators
- **Diagnose:** run a W3C HTML validator on the page — does it flag the `<style>` tag?
- **Fix:** harmless in browsers (default MIME for `<style>` is `text/css`); to silence the validator, override via theme `output_buffer` or wait for a fix in the PHP

### Vertical direction renders panels stacked but with same fixed height as horizontal

- **Likely cause:** the `eael_accordion_height` setting applies a fixed `height` on `.eael-img-accordion` regardless of direction; in vertical mode the total height divides among panels via flex, which can compress them
- **Diagnose:** check the widget's height setting — is it generous enough for the vertical panel count?
- **Fix:** increase the height value for vertical mode; or override via theme CSS with `height: auto` for vertical

## Testing Checklist

- [ ] Drop at default — 4 horizontal panels, hover any panel: active panel grows to `flex: 3`, others shrink to `flex: 1`
- [ ] Switch interaction to On Click — hover does nothing; click any panel toggles `overlay-active`
- [ ] Switch direction to Vertical — `accordion-direction-vertical` class; panels stack and expand vertically
- [ ] Switch content alignment (horizontal / vertical) — root classes update; overlay-inner shifts accordingly
- [ ] Set "Make it active?" on one item — that panel renders pre-active on first paint (no JS needed)
- [ ] Title link enable — wraps title in `<a href="…">`; clicking title navigates
- [ ] Empty Repeater — `render()` early-returns; no panels rendered
- [ ] Multiple Image Accordions on same page — each gets unique `#eael-img-accordion-<id>` and inline `<style>`
- [ ] Re-fired `elementor/frontend/init` (popup or SPA nav) — `eael.hooks` prevents double-registration
- [ ] Enable Liquid Glass + Effect 1 — `eael_wd_liquid_glass-effect1` class on wrapper; `backdrop-filter: blur(…)` rule applied to `.overlay-active .overlay`
- [ ] Effect 4 / 5 / 6 without Pro — upsell card appears; no styling rule emitted; panel renders unstyled
- [ ] Activate Pro + Effect 4 — bg-color and backdrop-filter rules emitted; SVG `<defs>` injected into every panel's `.overlay`
- [ ] Special characters (`<script>`) in title or content — sanitised via `Helper::eael_allowed_tags`; no XSS
- [ ] Keyboard focus — Tab cycles through panels (each is `tabindex="0"`); Enter / Space does NOT toggle (known limitation)
- [ ] Console — no `console.log('leave')` spam in production builds (known issue; remove from source)
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Front-only Liquid Glass injection (no rear variant)

- **Context:** unlike Flip Box, Image Accordion only has a single visible face per panel — there's no rear / back state. Only the active panel's overlay is visible at any time.
- **Decision:** emit only the standard front-side Liquid Glass action chain (`_bg_color_effect4`, etc.). No `_rear_` variants.
- **Alternatives rejected:** emit both for consistency with Flip Box — would force Pro to register listeners for hooks that target nothing; no clear front / back distinction in the markup.
- **Consequences:** half the Pro hook surface compared to Flip Box; cleaner extension contract for this widget specifically.

### Inline `<style>` block for hover-mode CSS instead of static SCSS

- **Context:** the hover-mode flex transition needs to scope to a specific widget instance so multiple Image Accordions on a page don't interfere. Static SCSS would either apply globally or require per-instance `data-*` attributes plus `[data-*=…]:hover` selectors.
- **Decision:** emit an inline `<style>` block in `render()` scoped to `#eael-img-accordion-<id>`.
- **Alternatives rejected:** rely on `:hover` CSS keyed on the widget root — works but requires extra specificity tricks; force interaction mode to click only — limits the widget's usefulness.
- **Consequences:** every widget instance adds ~250 bytes of inline CSS; the typo `typr="text/css"` survives (browsers ignore unknown attributes); harder to override via theme stylesheet without `!important`.

### Server-side active state via inline `flex: 3 1 0%;`

- **Context:** users expect the page to look "settled" on first paint, not flicker when JS finishes loading and resets the default flex.
- **Decision:** emit inline `style="background-image: …; flex: 3 1 0%;"` on the pre-active panel; add `overlay-active` and `overlay-inner-show` classes server-side. JS will not re-apply on init; it only handles user interaction afterward.
- **Alternatives rejected:** JS-only — initial paint shows all panels at `flex: 1` then snaps; data-attribute + CSS — would require more class composition; force users to interact before any panel is active.
- **Consequences:** SSR-friendly first paint; one inline style per item (small per-page cost); the active state matches user expectation immediately.

### EA's `eael.hooks.addAction("init", "ea", …)` registration pattern

- **Context:** newer EA widgets use a wrapper around `jQuery(window).on("elementor/frontend/init", …)` to consolidate hook registration via EA's own `eael.hooks` API.
- **Decision:** Image Accordion uses `eael.hooks.addAction("init", "ea", () => { elementorFrontend.hooks.addAction(…) });`.
- **Alternatives rejected:** the older `jQuery(window).on("elementor/frontend/init", …)` pattern used by Fancy Text / Creative Button / Flip Box — works but doesn't benefit from EA's internal double-registration guard.
- **Consequences:** registration is automatically idempotent via EA's hook system; no explicit `elementStatusCheck` needed; consistent with newer EA widgets.

## Known Limitations

- **`console.log('leave')` debug statement** in `hoverOutAction` ([line 48](../../src/js/view/image-accordion.js#L48)) — pollutes the browser console on every mouseleave in production. Cleanup target.
- **Typo `typr="text/css"`** instead of `type="text/css"` in the inline `<style>` block ([line 1105](../../includes/Elements/Image_Accordion.php#L1105)) — harmless in browsers (default MIME for `<style>` is `text/css`) but flags HTML validators.
- **Double `:hover:hover`** in the inline `<style>` block — also harmless (a hovered element is still hovered) but unusual.
- **Repeater control id `eael_accordion_tittle`** has a typo (`tittle` not `title`); renaming would break saved widget data. Legacy.
- **Root class `eael-img-accordion`** uses `img-` prefix while the widget id and config slug use `image-` — legacy naming. Renaming would break theme CSS.
- **Pre-active flag on multiple items produces undefined layout** — only one item should be marked active; the widget doesn't enforce uniqueness in the panel.
- **Keyboard activation (Enter / Space) does not toggle a panel** — `tabindex="0"` enables focus, but only mouse hover or click triggers the interaction. Accessibility gap.
- **`<style typr="text/css">` block is only emitted for on-hover mode** — click mode relies entirely on JS, so on-hover-then-click switches need a save round-trip to remove the inline style.
- **`eael_accordion_height` is plain text in px** — not a SLIDER, no unit picker. Users typing `400px` get `400pxpx` because `render()` appends `px`. Documented intent; could trip up new users.
- **Per-panel SVG `<defs>` injection** — Pro's handler fires inside every panel's `<div class="overlay">` loop iteration, not once per widget. Wastes a few KB of inline SVG markup when more than one Liquid Glass effect is enabled.
- **No top-level Pro upsell section** — users not enabling Liquid Glass never see any Pro messaging from this widget. Consistent with Feature_List, but unusual for widgets that do have Pro features.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
