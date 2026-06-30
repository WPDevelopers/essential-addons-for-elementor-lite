# Flip Box Widget

> Two-sided card that flips on hover or click to reveal a back face. Seven CSS-driven animation styles (flip left / right / top / bottom + zoom in / zoom out + fade in), optional 3D depth, fixed or auto height, and front-and-back Liquid Glass effects with independent Pro hook chains for each face.

**Class file:** [`includes/Elements/Flip_Box.php`](../../includes/Elements/Flip_Box.php)
**Slug:** `flip-box` (widget id `eael-flip-box`)
**Public docs:** <https://essential-addons.com/elementor/docs/flip-box/>
**Pro-shared:** ✅ Yes — Pro hooks into the Lite Liquid Glass infrastructure for both front and rear faces. Lite emits front hooks (`eael_wd_liquid_glass_effect_bg_color_effect4/5/6`, etc.) and rear hooks (`_rear_effect4/5/6`, `_noise_action_rear`, `_svg_pro_back`); Pro registers handlers for both sets.

---

## Overview

Flip Box renders a card with two faces — a front side and a back side — that swap places when the user hovers over or clicks the card. The flip is CSS-driven via `transform: rotate3d / translate / scale`, so the JS only has to toggle a single `--active` class on click; hover uses `:hover` selectors directly. Seven animation styles cover the common cases: four directional flips (left / right / top / bottom), two zoom variants, and a fade.

Each face can render either inline content (image / icon + title + body, with optional button on the back) or an entire saved Elementor template — useful for embedding a layered design as one side of the card. The back face supports three link types: link the whole box, link just the title, or render a dedicated button. Liquid Glass effects on both faces share the same Lite/Pro pattern Info Box uses, but with separate hook chains so the two sides can be tuned independently.

## Features

- Seven flip animation styles: flip left / right / top / bottom, zoom in, zoom out, fade in
- 3D Depth toggle (visible only on the four directional flips)
- Event type: hover or click — click adds `--active` class via JS; hover uses pure CSS
- Configurable flip speed (1–1000 ms or 1–100 s)
- Height mode: fixed pixel/percent height, or auto height (Maximum content height / Based on visible content)
- Independent front and back content: image / icon / none, title with selectable HTML tag, WYSIWYG body
- Saved Elementor template as front or back content (each independently WPML-aware via `wpml_object_id`)
- Three link types — box (whole card), title (heading only), button (rendered on back face)
- Liquid Glass effects on front and back faces (independent switches)
  - Lite: Effects 1 (Heavy Frost) and 2 (Soft Mist) for each face
  - Pro: Effects 4 (Light Frost), 5 (Grain Frost), 6 (Fine Frost) plus noise distortion and SVG-filter `<defs>` injection
- Image / icon hover styles (per-face): background, border, border-radius
- Per-face content alignment, vertical alignment, padding
- Pro upsell section hidden automatically when Pro is active

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Seven flip animation styles | ✅ | ✅ |
| 3D depth toggle | ✅ | ✅ |
| Click and hover event types | ✅ | ✅ |
| Fixed and auto height modes | ✅ | ✅ |
| Saved Elementor template content on either face | ✅ | ✅ |
| Three link types (box / title / button) | ✅ | ✅ |
| Front Liquid Glass Effects 1 + 2 | ✅ | ✅ |
| Back Liquid Glass Effects 1 + 2 | ✅ | ✅ |
| Front Liquid Glass Effects 4 / 5 / 6 | ❌ — locked, shows upsell | ✅ via `eael_wd_liquid_glass_effect_bg_color_effect4/5/6` |
| Back Liquid Glass Effects 4 / 5 / 6 | ❌ — locked, shows upsell | ✅ via `eael_wd_liquid_glass_effect_bg_color_rear_effect4/5/6` |
| Liquid Glass noise distortion (front + back) | ❌ — actions are no-ops | ✅ via `eael_wd_liquid_glass_effect_noise_action` + `_rear` |
| Liquid Glass SVG `<defs>` injection (front + back) | ❌ — actions are no-ops | ✅ via `_svg_pro` + `_svg_pro_back` |
| Liquid Glass Shadow Effects (front + back) | ❌ — Pro upsell card | ✅ |
| Pro upsell section in panel | shown | hidden |

When Lite renders alone, selecting locked Effects 4/5/6 still emits the `eael_wd_liquid_glass-effect4` (front) or `eael_wd_liquid_glass_rear-effect4` (back) class on the wrapper — but no matching CSS rule fires, so the face appears unstyled rather than visibly broken.

## Use Cases

- Feature grid where each card flips to reveal a longer description and a CTA button
- Team-member profile cards (photo on front, bio + social links on back)
- Process step cards on a landing page (step number on front, detail on back)
- Pricing-tier teaser with the price on front and the full feature list on back
- Embedding a full saved template as the back face for rich layouts (image gallery, embedded video)

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Flip_Box.php`](../../includes/Elements/Flip_Box.php) | PHP widget class — controls, render, `render_icon()` helper, dual Liquid Glass sections |
| [`src/css/view/flip-box.scss`](../../src/css/view/flip-box.scss) | Source styles — animation keyframes, 3D depth, layout, hover / click states |
| [`src/js/view/flip-box.js`](../../src/js/view/flip-box.js) | Frontend logic — click toggle + auto-height adjustment |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `HelperTrait` — provides Liquid Glass front (`eael_wd_liquid_glass_effect_bg_color_effect`) and rear (`_rear`) helpers ([line 640+](../../includes/Traits/Helper.php#L640)) |
| [`config.php`](../../config.php#L192) entry `'flip-box'` | `Asset_Builder` dependency declaration (CSS + JS) |
| `assets/front-end/css/view/flip-box.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/flip-box.min.js` | Built output (do not edit) |

## Architecture

- **Flip is CSS, click toggle is JS** — the seven animation styles are all `transform` keyframes in SCSS keyed off `.eael-animate-{type}` plus `:hover` or `.--active`. For hover event, the SCSS rule itself triggers the flip. For click event, JS adds `--active` to the same element so the equivalent rule fires. JS does not animate anything — it only toggles class state.
- **Single template, no per-animation branching** — `render()` writes a fixed DOM tree with a front face and a rear face inside `.eael-elements-flip-box-flip-card`. The chosen animation style (`eael-animate-left`, `eael-animate-zoom-in`, etc.) is added to the outer wrapper as a class. All seven variants share the same DOM — only the CSS differs.
- **`render_icon($settings, $location)` accepts a location parameter** — same method renders the front icon (`'front'`), back icon (`'back'`), and the link-button icon (`'button'`) via a switch on key prefixes ([line 2829](../../includes/Elements/Flip_Box.php#L2829)). The legacy `__fa4_migrated` shim is applied uniformly.
- **Two completely independent Liquid Glass sections** — front-side hooks emit `eael_wd_liquid_glass_effect_bg_color_effect4/5/6`, `_backdrop_filter_effect4/5/6`, `_noise_action`, `_svg_pro`. Back-side hooks emit the same names with `_rear` suffix on the bg-color and backdrop_filter hooks plus `_noise_action_rear` and `_svg_pro_back`. Lite-side methods use `eael_wd_liquid_glass_effect_bg_color_effect()` for front and `_rear()` for back. Pro registers handlers for both chains.
- **Auto-height runs two different JS strategies** — `eael-flipbox-max` mode polls every 200 ms for 5 seconds with `setInterval(setFixedHeight, 200)` then clears, taking the **larger** of front and rear heights and setting it once. `eael-flipbox-dynamic` mode binds debounced handlers to click + hover so the card resizes between front and rear heights as the user interacts ([line 32+](../../src/js/view/flip-box.js#L32)). Two strategies because "tallest content always" and "shrink/expand on flip" are mutually exclusive user intents.
- **WPML media translation in `render()`** — both `eael_flipbox_image` and `eael_flipbox_image_back` IDs are run through `wpml_object_id` filter ([lines 2628](../../includes/Elements/Flip_Box.php#L2628) and [2683](../../includes/Elements/Flip_Box.php#L2683)) so multilingual sites resolve the translated attachment. The translated URL is re-derived via `wp_get_attachment_url()`.
- **Box-link mode swaps the wrapper element from `<div>` to `<a>`** — `render()` sets `$flipbox_if_html_tag = 'a'` when `flipbox_link_type === 'box'` ([line 2652](../../includes/Elements/Flip_Box.php#L2652)). Same applies to the title heading element, which becomes an `<a>` for `flipbox_link_type === 'title'`.

## Render Output

The widget's structure depends on the link type and content type per face. Annotated tree below uses default config (hover, animate-left, fixed height, content type on both faces, no link); conditional elements marked `[?]`.

```html
<div class="eael-elements-flip-box-container eael-animate-flip eael-animate-left
            eael-content eael-flip-box-hover
            eael-flipbox-fixed-height eael-flipbox-max">
  <!-- when link_type=box: <a href="…"> instead of <div> -->
  <div class="eael-elements-flip-box-flip-card">
    <div class="eael-elements-flip-box-front-container">
      [?] <svg>…liquid-glass defs (Pro only, front)…</svg>
      [?] {saved-template content via get_builder_content()}
      <div class="eael-elements-slider-display-table">
        <div class="eael-elements-flip-box-vertical-align">
          <div class="eael-elements-flip-box-padding">
            [?] <div class="eael-elements-flip-box-icon-image">
                  <i class="fas fa-snowflake ea-flipbox-icon"></i>  <!-- icon -->
                  OR
                  <img class="eael-flipbox-image-as-icon" src="…">   <!-- image -->
                </div>
            <h2 class="eael-elements-flip-box-heading">Front Title</h2>
            <div class="eael-elements-flip-box-content">
              <p>Front body content…</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="eael-elements-flip-box-rear-container">
      [?] <svg>…liquid-glass defs (Pro only, back)…</svg>
      [?] {saved-template content via get_builder_content()}
      <div class="eael-elements-slider-display-table">
        <div class="eael-elements-flip-box-vertical-align">
          <div class="eael-elements-flip-box-padding">
            [?] <div class="eael-elements-flip-box-icon-image">…icon/image…</div>
            <h2 class="eael-elements-flip-box-heading">Back Title</h2>
            <!-- when link_type=title: <h2><a class="flipbox-linked-title">…</a></h2> -->
            <div class="eael-elements-flip-box-content">
              <p>Back body content…</p>
            </div>
            [?] <a class="flipbox-button" href="…">
                  [?] <i class="ea-flipbox-icon"></i>   <!-- before -->
                  Get Started
                  [?] <i class="ea-flipbox-icon"></i>   <!-- after -->
                </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

Notes:

- `.eael-elements-flip-box-container` is the styling root and JS scope target. Classes assembled in `render()` cover: animation style (`eael-animate-{type}`), content type echo (`eael-content` / `eael-template` — derived from front content_type), event type (`eael-flip-box-{hover|click}`), height mode (`eael-flipbox-{fixed|auto}-height`), and height adjustment (`eael-flipbox-{max|dynamic}`).
- Outer element becomes `<a>` instead of `<div>` when `flipbox_link_type === 'box'`. SCSS depends on `a` styling resetting `display: block` ([line 34](../../src/css/view/flip-box.scss#L34)).
- The Liquid Glass picker adds `eael_wd_liquid_glass-{effect}` for front and `eael_wd_liquid_glass_rear-{effect}` for back as `prefix_class` on the wrapper.
- 3D depth adds `eael-flip-box--3d` class — visible only for the four directional flip styles (controls hide it for zoom / fade).
- The button is rendered inside the back face only; the front face never has a button.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Flip_Box.php#L95) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_flipbox_event_type` | CHOOSE | `hover` | Content → Settings | `eael-flip-box-{hover|click}` class on wrapper |
| `eael_flipbox_type` | SELECT | `animate-left` | Content → Settings | Animation class on wrapper (`eael-animate-*`) |
| `eael_flipbox_3d` | SWITCHER | off | Content → Settings | `eael-flip-box--3d` class (only for directional flips) |
| `eael_flipbox_flip_speed` | SLIDER (ms/s) | `500ms` | Content → Settings | `transition-duration` on flip card |
| `eael_flipbox_height_mode` | CHOOSE | `fixed` | Content → Settings | `eael-flipbox-{fixed|auto}-height` class |
| `eael_flipbox_height_adjustment` | CHOOSE | `maximum` | Content → Settings | `eael-flipbox-{max|dynamic}` class (auto height only) |
| `eael_flipbox_height` | SLIDER (responsive) | `300px` | Content → Settings | `height` / `min-height` on `.eael-flipbox-fixed-height` |
| `eael_wd_liquid_glass_effect_switch` | SWITCHER | off | Content → Settings | Unlocks the front Liquid Glass section |
| `eael_wd_liquid_glass_effect_switch_rear` | SWITCHER | off | Content → Settings | Unlocks the back Liquid Glass section |
| `eael_flipbox_front_content_type` | SELECT | `content` | Content → Content (Front) | WYSIWYG vs saved-template path (front) |
| `eael_flipbox_front_templates` | `eael-select2` | empty | Content → Content (Front) | Template id (conditional) |
| `eael_flipbox_img_or_icon` | SELECT | `icon` | Content → Content (Front) | Visual element type — none / image / icon |
| `eael_flipbox_image` | MEDIA | placeholder | Content → Content (Front) | `<img src>` (image variant) |
| `eael_flipbox_icon_new` | ICONS | `fas fa-snowflake` | Content → Content (Front) | Icon glyph (legacy shim via `__fa4_migrated`) |
| `eael_flipbox_image_resizer` | SLIDER (responsive) | `100` | Content → Content (Front) | `width` on `.eael-flipbox-image-as-icon` |
| `eael_flipbox_front_title` | TEXT (dynamic) | `"Front Title"` | Content → Content (Front) | `.eael-elements-flip-box-heading` text |
| `eael_flipbox_front_title_tag` | SELECT | `h2` | Content → Content (Front) | Heading HTML tag |
| `eael_flipbox_front_text` | WYSIWYG | sample paragraph | Content → Content (Front) | Body HTML (content type only) |
| `eael_flipbox_front_vertical_position` | CHOOSE | `middle` | Content → Content (Front) | `align-items` on front container |
| `eael_flipbox_content_alignment` | CHOOSE | `center` | Content → Content (Front) | `prefix_class` `eael-flipbox-content-align-%s` |
| `eael_flipbox_back_*` | mirror of front | various | Content → Content (Back) | Same controls applied to back face |
| `flipbox_link_type` | SELECT | `none` | Content → Link | Switches wrapper tag, title tag, or renders button |
| `flipbox_link` | URL (dynamic) | `#` | Content → Link | Link target (visible when link_type != none) |
| `flipbox_button_text` | TEXT (dynamic) | `"Get Started"` | Content → Link | Button label (link_type=button) |
| `button_icon_new` | ICONS | empty | Content → Link | Button icon glyph |
| `button_icon_position` | SELECT | `after` | Content → Link | Icon before or after the button text |
| Style → Flip Box Style | various | — | Style → Flip Box Style | Front and rear container background, border, box-shadow |
| Style → Image Style | various | — | Style → Image Style | Per-face image dimensions, border, hover state |
| Style → Icon Style | various | — | Style → Icon Style | Per-face icon size, color, border, background |
| Style → Color & Typography | various | — | Style → Color & Typography | Title and content typography, color (front + back) |
| Style → Button Style | various | — | Style → Button Style | Button typography, padding, background, border |
| `eael_wd_liquid_glass_effect` (front) | CHOOSE | `effect1` | Style → Liquid Glass Effects Front | `prefix_class` `eael_wd_liquid_glass-%s` |
| `eael_wd_liquid_glass_effect_rear` | CHOOSE | `effect1` | Style → Liquid Glass Effects Back | `prefix_class` `eael_wd_liquid_glass_rear-%s` |
| `eael_wd_liquid_glass_effect_brightness_effect2` / `_rear` | SLIDER | `1` | Style → Liquid Glass | `brightness()` in `backdrop-filter` (Effect 2 only) |

## Conditional Dependencies

```text
eael_flipbox_3d                       → visible when eael_flipbox_type in
                                          ['animate-left', 'animate-right',
                                           'animate-up', 'animate-down']
eael_flipbox_height                   → visible when eael_flipbox_height_mode == 'fixed'
eael_flipbox_height_adjustment        → visible when eael_flipbox_height_mode == 'auto'

eael_flipbox_front_templates          → visible when eael_flipbox_front_content_type == 'template'
eael_flipbox_img_or_icon              → visible when eael_flipbox_front_content_type == 'content'
eael_flipbox_image                    → visible when eael_flipbox_img_or_icon == 'img'
eael_flipbox_icon_new                 → visible when eael_flipbox_img_or_icon == 'icon'
eael_flipbox_image_resizer            → visible when eael_flipbox_img_or_icon == 'img'
eael_flipbox_front_title / _tag / _text / _vertical_position / content_alignment
                                       → visible when eael_flipbox_front_content_type == 'content'

(All back-side controls mirror the above with eael_flipbox_back_content_type / _img_or_icon_back)

eael_flixbox_link_section (entire Link section)
                                       → visible when eael_flipbox_back_content_type == 'content'
flipbox_link                          → visible when flipbox_link_type != 'none'
flipbox_button_text / button_icon_new / button_icon_position
                                       → visible when flipbox_link_type == 'button'

eael_wd_liquid_glass_effect_front_section
                                       → visible when eael_wd_liquid_glass_effect_switch == 'yes'
eael_wd_liquid_glass_effect_back_section
                                       → visible when eael_wd_liquid_glass_effect_switch_rear == 'yes'
eael_wd_liquid_glass_effect_pro_alert (front)
                                       → visible when Pro NOT active AND
                                         eael_wd_liquid_glass_effect in ['effect4','effect5','effect6']
eael_wd_liquid_glass_effect_pro_alert_rear
                                       → same logic for back face
eael_section_pro / eael_control_get_pro
                                       → visible when Pro NOT active
```

## Behavior Flow

End-to-end sequence from "user drops widget on canvas" to "user clicks the rendered card and sees it flip".

1. User drops the widget → Elementor calls `register_controls()` → panel appears with Settings / Content / Link content tabs plus a dozen Style sections including front and back Liquid Glass.
2. User configures front and back content (separate tabs inside the Content section), event type, animation style, height mode, link type, and optionally Liquid Glass effects.
3. Editor preview iframe re-renders via [`render()`](../../includes/Elements/Flip_Box.php#L2620).
4. `render()` computes height mode classes, link mode classes, and the wrapper tag (`<div>` or `<a>` depending on `flipbox_link_type === 'box'`).
5. Both front and back image IDs run through `wpml_object_id` for WPML compatibility; the translated URL is re-derived.
6. The single HTML template emits one front container and one rear container inside `.eael-elements-flip-box-flip-card`. Each container branches on `content_type`: template path calls `Plugin::$instance->frontend->get_builder_content(…, true)` ; content path emits the icon/image + title + body markup.
7. `render_icon()` is called for each side (with `'front'` / `'back'` / `'button'`), applying the `__fa4_migrated` shim and choosing between FA4 `<i>`, FA5 SVG, and uploaded SVG paths.
8. Front and rear containers each fire their respective `do_action('eael_wd_liquid_glass_effect_svg_pro', …)` / `_back` for Pro's filter-defs injection.
9. Browser receives static HTML. Elementor's `frontend/init` event fires.
10. `flip-box.js` runs: `addAction('frontend/element_ready/eael-flip-box.default', FlipBox)` registers the handler (guarded by `eael.elementStatusCheck('eaelFlipBox')`).
11. For each `.eael-elements-flip-box-container` element-ready, the `FlipBox` handler runs: binds `click` to `.eael-flip-box-click` (toggles `--active` class), `mouseenter mouseleave` to `.eael-flip-box-hover` (also toggles `--active` — but CSS uses `:hover` directly so the class is informational for SCSS coupling).
12. If wrapper has `eael-flipbox-auto-height`:
    - `eael-flipbox-max` → `setInterval(setFixedHeight, 200)` for 5 seconds; takes `Math.max(front, rear)` and sets it once.
    - `eael-flipbox-dynamic` → debounced (`100ms`) handlers on click / hover; sets the height to whichever side is currently shown (rear if `--active`).
13. CSS handles the flip transform: `transition-duration` from the speed control, `transform` keyframe matching the chosen `eael-animate-*` class fires when `:hover` or `.--active` is true.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-flip-box.default', FlipBox)`
- **Guard:** `if ( eael.elementStatusCheck('eaelFlipBox') ) return false;` — prevents re-registration on re-fired `elementor/frontend/init`
- **Reads on init:** the widget's `$scope`; finds `.eael-elements-flip-box-container` plus its front and rear inner heights
- **Click handler (`.eael-flip-box-click`):** `off('click').on('click', toggleClass('--active'))` — `off()` first to prevent double-binding on re-init
- **Hover handler (`.eael-flip-box-hover`):** `mouseenter mouseleave` also toggles `--active` — informational since CSS `:hover` triggers the flip independently
- **Auto height — `eael-flipbox-max`:** `setInterval(setFixedHeight, 200)` for 5 s, cleared by `setTimeout` ; takes `Math.max(frontHeight, rearHeight)` and sets the card height
- **Auto height — `eael-flipbox-dynamic`:** debounced (`100ms`) listener on click + hover that picks rear height when `wrapper.hasClass('--active')`, otherwise front height
- **Runtime state:** none persistent; interval is cleared, handlers are bound to the DOM and rely on jQuery's per-element data store. The internal `debounce()` is the only closure-scoped state.
- **`--active` class consumer:** SCSS keyframe rules for each `.eael-animate-*` variant target both `.eael-flip-box-hover:hover` and `.eael-flip-box-click.--active` so the JS toggle and the pure-CSS hover share the same animation rules.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one `Flip_Box` widget is detected on the page. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `flip-box.min.css` | self (built from `src/css/view/flip-box.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `flip-box.min.js` | self (built from `src/js/view/flip-box.js`) | Click toggle + auto-height adjustment | Always when widget present |

Font Awesome glyphs render via `Icons_Manager::render_icon`, which depends on Elementor's `font-awesome-5-all` handle Elementor provides.

## Hooks & Filters

The widget's public contract — Pro consumes the Liquid Glass actions; the `eael_liquid_glass_effect_filter` is open for label customisation.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_liquid_glass_effect_filter` | filter (emitted) | `array $defaults` with keys `styles` and `conditions` | Customise Liquid Glass picker labels and which entries are Pro-locked. Emitted once per side ([line 1721](../../includes/Elements/Flip_Box.php#L1721)) |
| `eael_wd_liquid_glass_effect_bg_color_effect4` / `_5` / `_6` (front) | action (emitted) | `(Widget_Base $widget, string $effect, string $default_color, string $selector)` | Pro registers bg-color controls for front-face Effects 4–6 |
| `eael_wd_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6` (front) | action (emitted) | same | Pro registers backdrop-filter controls for front-face Effects 4–6 |
| `eael_wd_liquid_glass_effect_noise_action` | action (emitted) | `(Widget_Base $widget)` | Pro adds noise-distortion controls for the front face |
| `eael_wd_liquid_glass_effect_svg_pro` | action (emitted) | `(Widget_Base $widget, array $settings, string $selector)` | Pro injects inline `<svg>` with filter `<defs>` inside front container at render ([line 2726](../../includes/Elements/Flip_Box.php#L2726)) |
| `eael_wd_liquid_glass_effect_bg_color_rear_effect4` / `_5` / `_6` | action (emitted) | same as front | Pro registers bg-color controls for back-face Effects 4–6 ([line 2223](../../includes/Elements/Flip_Box.php#L2223)) |
| `eael_wd_liquid_glass_effect_backdrop_filter_rear_effect4` / `_5` / `_6` | action (emitted) | same | Pro registers backdrop-filter controls for back-face Effects 4–6 |
| `eael_wd_liquid_glass_effect_noise_action_rear` | action (emitted) | `(Widget_Base $widget)` | Pro adds noise-distortion controls for the back face |
| `eael_wd_liquid_glass_effect_svg_pro_back` | action (emitted) | `(Widget_Base $widget, array $settings, string $selector)` | Pro injects inline `<svg>` with filter `<defs>` inside back container at render ([line 2774](../../includes/Elements/Flip_Box.php#L2774)) |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides upsell sections; toggles the Pro lock icon on Effects 4/5/6 |
| `wpml_object_id` | filter (consumed) | `int $object_id, string $type, bool $return_original` | Translates front / back image IDs and saved-template IDs under WPML ([lines 2628](../../includes/Elements/Flip_Box.php#L2628), [2683](../../includes/Elements/Flip_Box.php#L2683), [2733](../../includes/Elements/Flip_Box.php#L2733), [2780](../../includes/Elements/Flip_Box.php#L2780)) |

The Liquid Glass actions are widget-agnostic — the same names fire from Info_Box, Creative Button, and any other widget exposing the same controls. Flip_Box is the only widget that emits both the `_front` and `_rear` variants so the two faces can be styled independently.

## Customization Recipes

### Recipe 1 — Disable the click event globally and force hover-only

```php
add_action( 'elementor/element/before_section_end', function ( $element, $section_id ) {
    if ( $element->get_name() !== 'eael-flip-box' ) {
        return;
    }
    if ( $section_id !== 'eael_section_flipbox_content_settings' ) {
        return;
    }
    $element->update_control( 'eael_flipbox_event_type', [
        'default' => 'hover',
    ] );
}, 10, 2 );
```

Sets the default for new widgets. Existing widgets keep their stored value.

### Recipe 2 — Add a custom flip animation via theme SCSS

```scss
.eael-elements-flip-box-container.eael-animate-custom-spin {
    perspective: 1000px;

    .eael-elements-flip-box-flip-card {
        transform-style: preserve-3d;
        transition: transform 0.8s;
    }

    &.eael-flip-box-hover:hover .eael-elements-flip-box-flip-card,
    &.eael-flip-box-click.--active .eael-elements-flip-box-flip-card {
        transform: rotateY(360deg) scale(1.05);
    }

    .eael-elements-flip-box-rear-container {
        transform: rotateY(180deg);
        backface-visibility: hidden;
    }
}
```

```php
add_filter( 'elementor/element/before_section_end', function ( $element, $section_id, $args ) {
    if ( $element->get_name() !== 'eael-flip-box' || $section_id !== 'eael_section_flipbox_content_settings' ) {
        return;
    }
    $element->update_control( 'eael_flipbox_type', [
        'options' => array_merge( $element->get_controls( 'eael_flipbox_type' )['options'], [
            'animate-custom-spin' => __( 'Custom Spin', 'my-theme' ),
        ] ),
    ] );
}, 10, 3 );
```

The widget's `render()` writes the chosen value directly to the wrapper class without validation, so any value the SCSS supports renders correctly.

### Recipe 3 — Override the Liquid Glass label for "Heavy Frost" on both faces

```php
add_filter( 'eael_liquid_glass_effect_filter', function ( $defaults ) {
    $defaults['styles']['effect1'] = __( 'Maximum Blur', 'my-theme' );
    return $defaults;
} );
```

Affects the picker label on both the front and back Liquid Glass sections (the filter fires once per side, but the same value is returned each time).

### Recipe 4 — Force the front face to render a specific template id when an external query param is set

```php
add_filter( 'eael/widget/before_render', function ( $widget ) {
    if ( $widget->get_name() !== 'eael-flip-box' ) {
        return;
    }
    if ( isset( $_GET['preview_template'] ) && current_user_can( 'edit_posts' ) ) {
        $widget->set_settings( 'eael_flipbox_front_content_type', 'template' );
        $widget->set_settings( 'eael_flipbox_front_templates', absint( $_GET['preview_template'] ) );
    }
} );
```

⚠️ `eael/widget/before_render` is a generic Lite hook; verify it fires for your usage. Capability check is essential — the input is untrusted user-supplied data.

## Common Issues

### Card doesn't flip when clicked

- **Likely cause:** `eael_flipbox_event_type` is `hover`, not `click` — click handler is bound but the SCSS rule for the active class is configured for the hover selector
- **Diagnose:** check the wrapper class — does it have `eael-flip-box-click` or `eael-flip-box-hover`?
- **Fix:** switch the event type to Click in the Settings tab

### Auto-height card cuts off content on the back face

- **Likely cause:** `eael_flipbox_height_adjustment` is `dynamic` and the back content is taller than what was measured at init — debounced listener should fire on flip but if the user clicks before init runs, the resize is missed
- **Diagnose:** click the card twice; does it adjust the second time?
- **Fix:** switch to "Maximum Content Height" mode — it polls for 5 s and takes the larger of front/rear; or force a fixed height that fits both

### "Maximum Content Height" mode flickers during the first 5 seconds

- **Likely cause:** `setInterval(setFixedHeight, 200)` polls every 200 ms for 5 s and sets the height — if either side has lazy-loaded images or fonts that change height, each poll re-measures
- **Diagnose:** flicker stops after 5 s
- **Fix:** use fixed height; or wrap card content in fixed-height container; or accept the trade-off

### 3D depth toggle doesn't change the visual

- **Likely cause:** 3D depth only applies to the four directional flip styles — the toggle is conditional on `eael_flipbox_type` being `animate-left/right/up/down`. Zoom and fade animations don't use 3D transforms
- **Diagnose:** check the animation style in the Settings tab
- **Fix:** by design

### Liquid Glass effect on the back face is invisible

- **Likely cause:** the back face is hidden until the card flips; if the page is captured pre-flip (printout, screenshot), the back-face Liquid Glass `backdrop-filter` has nothing to blur because the card sits over its own background. Also the back-face requires `eael_wd_liquid_glass_effect_switch_rear` separately from the front
- **Diagnose:** flip the card and inspect — does the back face show the effect?
- **Fix:** ensure both switches are on (front and back are independent); back face's blur target needs a semi-transparent background and a non-uniform background behind the card to be visible

### Link Type "Box" makes the entire card clickable but the back-face button still appears

- **Likely cause:** the Link section is hidden when back content type is `template`, and link types are mutually pickable but not mutually exclusive in the rendered output — `flipbox_link_type === 'box'` wraps the card in `<a>` but also the button is still rendered when `flipbox_button_text` is non-empty
- **Diagnose:** the conditional on the link section ([line 796](../../includes/Elements/Flip_Box.php#L796)) hides the section but doesn't enforce one-link rule
- **Fix:** set Link Type to "Button" (not "Box") if you only want a button; an `<a>` inside `<a>` is invalid HTML and most browsers split it

### Saved-template content renders as a broken layout

- **Likely cause:** the template has its own width / positioning rules; inside a fixed-height flip box those clash with the absolute-positioned front/rear containers
- **Diagnose:** open the template separately — does it render correctly outside the flip box?
- **Fix:** simplify the template; or switch the flip box to `auto` height with maximum-content mode

### Title link doesn't navigate when clicked in click-event mode

- **Likely cause:** clicking the title also triggers the card's click handler which toggles `--active` — the link still navigates, but event bubbling can cause both effects to fire
- **Diagnose:** check whether the URL changes after clicking the title; if yes, navigation worked
- **Fix:** for a click-event card with a title link, consider using hover event instead, or use box-link mode for one combined target

### Icon migrated from FA4 still shows as the old glyph

- **Likely cause:** `__fa4_migrated` flag was set but the legacy `eael_flipbox_icon` (or `_back`, `_icon` button) field still has a value — `render_icon()` ([line 2848](../../includes/Elements/Flip_Box.php#L2848)) checks `empty($settings[$old_icon_key])` to decide; if both are set, the old wins
- **Diagnose:** export the widget JSON; if `eael_flipbox_icon` (old key) is non-empty, the new picker is ignored
- **Fix:** clear the legacy field via Code Injection or by editing the widget JSON

## Testing Checklist

- [ ] Drop at default — `animate-left` hover flip works; default snowflake icon visible front + back
- [ ] Switch animation style through all 7 — class on wrapper updates; visual flip changes accordingly
- [ ] Toggle 3D Depth on a directional flip — `eael-flip-box--3d` class appears; depth visible in the rotation
- [ ] Switch event type to Click — flip happens only on click; class toggles `--active`
- [ ] Flip speed slider — `transition-duration` on `.eael-elements-flip-box-flip-card` updates
- [ ] Switch height mode to Auto + Maximum — JS polls for 5 s and sets `Math.max(front, rear)` height
- [ ] Switch to Auto + Dynamic — card resizes between front and rear heights on click
- [ ] Front content type "Saved Templates" with a published template — front face renders the template
- [ ] Back content type "Saved Templates" — back face renders the template; Link section disappears (per the condition)
- [ ] Pick a Draft template on either face — content disappears (silent fall-through; known limitation)
- [ ] Link Type "Box" — entire card becomes one `<a>`; wrapper tag changes from `<div>` to `<a>`
- [ ] Link Type "Title" — only the back-face title becomes `<a>`
- [ ] Link Type "Button" — back face renders `.flipbox-button`; icon position Before / After works
- [ ] Front Liquid Glass switch + Effect 1 — `eael_wd_liquid_glass-effect1` class on wrapper; `backdrop-filter: blur(…)` rule fires
- [ ] Back Liquid Glass switch + Effect 1 — `eael_wd_liquid_glass_rear-effect1` class on wrapper; rear filter applies
- [ ] Effect 4 / 5 / 6 picked on either face without Pro — upsell card appears; class still emits but no styling
- [ ] Activate Pro — Effects 4–6 emit bg-color and backdrop-filter rules; SVG `<defs>` injected into both containers
- [ ] WPML with translated front image — `wpml_object_id` returns translated ID; `wp_get_attachment_url` re-derives URL
- [ ] Multiple flip boxes on same page — each animates independently; auto-height computed per instance
- [ ] After re-fired `elementor/frontend/init` (popup or SPA nav) — `eael.elementStatusCheck` guard prevents double-init
- [ ] Special characters in title or content — sanitised via `Helper::eael_allowed_tags`; no XSS
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Two parallel Liquid Glass sections (Front + Back) with separate hook chains

- **Context:** Flip Box has two visible faces that the user might want to style independently. Reusing the same hook names across front and back would let either Pro handler stomp the other's settings.
- **Decision:** Lite emits separate action hooks for the rear face with `_rear` (bg-color, backdrop_filter) or `_back` (svg) suffix. Helpers in the trait expose the rear-side variants (`eael_wd_liquid_glass_effect_bg_color_effect_rear`).
- **Alternatives rejected:** one shared section with a Front/Back toggle inside — collapses the control surface and forces Pro handlers to branch on context.
- **Consequences:** twice as many action hooks, but each face gets a clean configuration surface and Pro handlers can register independently per side.

### Two height-adjustment strategies under "Auto Height"

- **Context:** front and back faces routinely have different content heights. The card needs a height value to compute the flip transform reliably.
- **Decision:** offer two modes — "Maximum Content Height" (one-shot computation, picks the larger; uses 200 ms polling for 5 s to catch lazy-loaded content) and "Based on Visible Content" (resizes between front and rear on flip; uses 100 ms debounced listeners).
- **Alternatives rejected:** a single auto mode that always picks Maximum — surprising when the back face is much taller than the front; pure CSS solution — no way to read the back face's height while it's hidden.
- **Consequences:** two JS code paths; users can pick whichever matches their content shape.

### Single template branch in `render()` driven by class names

- **Context:** seven animation styles + click/hover + fixed/auto height + 3D depth could produce many DOM permutations.
- **Decision:** one DOM template; permutation handled by composing wrapper class names from settings.
- **Alternatives rejected:** per-animation `if … render …` branches — explodes the template; partial-include system — overkill for one widget.
- **Consequences:** SCSS does the heavy lifting; the PHP template stays ~50 lines for the entire output.

### Box-link mode swaps the wrapper element from `<div>` to `<a>`

- **Context:** "click anywhere on the card to go" needs an anchor that wraps the entire card without nesting interactive elements.
- **Decision:** in `render()`, when `flipbox_link_type === 'box'`, the wrapper element becomes `<a>` instead of `<div>`. The same render attributes apply.
- **Alternatives rejected:** wrap in an additional outer `<a>` — duplicates the role; use JS to navigate on click — breaks middle-click, accessibility, search engines.
- **Consequences:** SCSS includes a default `a { display: block; }` ([line 34](../../src/css/view/flip-box.scss#L34)) so the layout doesn't change; click-event toggles still work because the `<a>` still receives the click event.

### Click handler uses `off('click').on('click', …)` instead of `one()` or a guard

- **Context:** Elementor's `frontend/init` can fire multiple times in popup contexts; without `off()`, a re-init would double-bind and toggle `--active` twice per click (no visible effect).
- **Decision:** `off('click').on('click', …)` defensively unbinds before binding.
- **Alternatives rejected:** namespace-scoped binding then `.off('.eaelFlipBox')` — more code; rely on `elementStatusCheck` alone — works for the outer registration but not for the per-`$scope` handler.
- **Consequences:** safe under repeated init; minor overhead per init.

## Known Limitations

- **Hover handler toggles `--active` even though SCSS uses `:hover`** — line [28-30](../../src/js/view/flip-box.js#L28) of `flip-box.js` calls `toggleClass('--active')` on `mouseenter mouseleave`. The CSS rules for hover use `:hover` directly, so the class toggle is essentially informational. Cleanup target — the line could be removed.
- **The dynamic-height path reads `wrapper.hasClass('--active')`** — but `--active` is added to `.eael-flip-box-click` by the click handler. Because the wrapper element and the click target are the same DOM node (the wrapper has both `eael-elements-flip-box-container` and `eael-flip-box-click` classes), the check works — but it's fragile if the class lifecycle ever changes.
- **Saved-template path does not validate or sanitise template HTML** — `get_builder_content` is trusted because the template originates from the same site, but `wp_kses` is not applied. If a template is later edited to include disallowed content, the flip box renders it verbatim.
- **Box link wraps the card in `<a>` and still allows the button to render** — an `<a>` inside `<a>` is invalid HTML; user-error condition the widget doesn't catch.
- **5-second polling window in Maximum height mode** — content loaded after 5 s (e.g. iframe widgets, deferred CSS) won't be measured. Fixed window is a trade-off against indefinite polling.
- **No editor-side hint that a Draft template will render nothing** — same gotcha as Info_Box, Cta_Box.
- **Pro upsell description text in lock-icon helper is not translated** — `eael_pro_lock_icon()` returns hardcoded English HTML; minor i18n nit.
- **The class `eael-content` / `eael-template` on the wrapper reflects only the front content type** — `'eael-' . esc_attr($settings['eael_flipbox_front_content_type'])` ([line 2714](../../includes/Elements/Flip_Box.php#L2714)). If the front is `content` but the back is `template`, the wrapper still says `eael-content`. Cosmetic; no functional impact.
- **`eael_flipbox_back_text` is preserved when back content type switches to template** — Elementor saves all controls; switching back to `content` brings back the previous body text. Useful or surprising depending on the user's intent.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
