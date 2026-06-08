# Info Box Widget

> Renders an icon / number / image alongside a title, optional sub-title, body copy, and optional button — all in a single card. Four image positions (top / bottom / left / right) control the layout; the whole card can also be a single clickable link. Pro adds three Liquid Glass button-background effects on top of the two Lite-side ones.

**Class file:** [`includes/Elements/Info_Box.php`](../../includes/Elements/Info_Box.php)
**Slug:** `info-box` (widget id `eael-info-box`)
**Public docs:** <https://essential-addons.com/elementor/docs/info-box/>
**Pro-shared:** ✅ Yes — Pro hooks into Lite's Liquid Glass infrastructure via `do_action()` injection points (`eael_wd_liquid_glass_effect_bg_color_effect4/5/6`, `_backdrop_filter_effect4/5/6`, `_noise_action`, `_svg_pro`). Pro does **not** re-implement the class — it extends in place via the public action hooks Lite emits.

---

## Overview

Info Box is a versatile card widget combining a visual element (image, icon, or number) with a heading, optional sub-title, descriptive copy, and an optional action button. Four image positions (`img-on-top` / `img-on-bottom` / `img-on-left` / `img-on-right`) cover most card-style layouts a marketing page needs. The whole card can also be made clickable, replacing the button with a wrapping `<a>`.

The widget is CSS-driven for layout and animation. Hover effects on the icon use Elementor's `HOVER_ANIMATION` control (which writes `elementor-animation-*` classes that Elementor's bundled CSS handles), so no widget-specific JavaScript ships. The Liquid Glass effects (Frost / Soft Mist / Light Frost / Grain Frost / Fine Frost) on the button are SVG-filter + `backdrop-filter` based — Lite provides Effects 1 and 2; Pro injects Effects 4, 5, 6 via `do_action` hooks that Pro listens to.

## Features

- Four image positions: top / bottom / left / right, each with independent alignment controls
- Visual element type: None / Number / Icon / Image (responsive choice)
- Title and sub-title with separately selectable HTML tags (`h1`–`h6`, `span`, `p`, `div`) and toggleable sub-title above or below the title
- Body content as inline WYSIWYG **or** an existing Elementor template (WPML-aware via `wpml_object_id` filter)
- Optional button with link, icon, icon position (before / after), icon spacing, and icon rotation
- "Infobox Clickable" mode — entire card becomes one `<a>`, mutually exclusive with the button
- Hover animations on icon / image / number (Elementor's `HOVER_ANIMATION` control)
- Image shape (square / circle / radius) with separate normal and hover values
- Liquid Glass button background effects — Frost / Soft Mist on Lite; Light / Grain / Fine Frost on Pro
- Liquid Glass shadow effects (4 presets, all Pro-gated in Lite)
- Container background, padding, margin, border, border-radius
- Responsive controls for image position, alignment, content alignment, and most spacing values
- Pro upsell sections hidden automatically when Pro is active

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Image position (4 options) | ✅ | ✅ |
| Visual type: image / icon / number / none | ✅ | ✅ |
| Saved Elementor template as body content | ✅ | ✅ |
| Whole-card clickable wrap | ✅ | ✅ |
| Button with icon (Before / After / rotation) | ✅ | ✅ |
| Liquid Glass Effect 1 (Heavy Frost) | ✅ | ✅ |
| Liquid Glass Effect 2 (Soft Mist) | ✅ | ✅ |
| Liquid Glass Effect 4 (Light Frost) | ❌ — locked, shows upsell card | ✅ via `eael_wd_liquid_glass_effect_bg_color_effect4` |
| Liquid Glass Effect 5 (Grain Frost) | ❌ — locked, shows upsell card | ✅ via `eael_wd_liquid_glass_effect_bg_color_effect5` |
| Liquid Glass Effect 6 (Fine Frost) | ❌ — locked, shows upsell card | ✅ via `eael_wd_liquid_glass_effect_bg_color_effect6` |
| Liquid Glass Shadow Effects (4 presets) | ❌ — Pro upsell card replaces controls | ✅ |
| Liquid Glass SVG filter `<defs>` injection | ❌ — `do_action('eael_wd_liquid_glass_effect_svg_pro', …)` is a no-op | ✅ — Pro adds the handler that injects SVG markup |
| Liquid Glass noise distortion | ❌ — `do_action('eael_wd_liquid_glass_effect_noise_action', …)` is a no-op | ✅ |
| Pro upsell sections in panel | shown | hidden |

When Lite renders alone, picking Effect 4/5/6 in the picker still emits the `eael_wd_liquid_glass-effect4` etc. class on the wrapper, but no matching CSS rule fires because Pro's bg-color and backdrop-filter controls are not registered — the button appears unstyled rather than visibly broken.

## Use Cases

- Feature card row on a service page (icon-left layout with title + paragraph)
- Stats block ("100K+ Users") using the Number visual type with a single big numeral
- Process step ("Step 3: Deploy") using img-on-top with an illustration
- Pricing-page feature list where each card has an icon and a "Learn More" button
- Glassmorphism-styled call-out cards over a hero image (Liquid Glass button effects)

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Info_Box.php`](../../includes/Elements/Info_Box.php) | PHP widget class — controls, render partials, Liquid Glass section |
| [`src/css/view/info-box.scss`](../../src/css/view/info-box.scss) | Source styles — layout (4 positions), shapes, hover-state visuals |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `HelperTrait` — provides `eael_wd_liquid_glass_effect_bg_color_effect()` and `_backdrop_filter_effect()` helpers shared across widgets ([line 640](../../includes/Traits/Helper.php#L640)) |
| [`config.php`](../../config.php#L180) entry `'info-box'` | `Asset_Builder` dependency declaration (CSS only) |
| `assets/front-end/css/view/info-box.min.css` | Built output (do not edit) |

No widget-specific JS source or compiled file exists — Elementor's bundled `elementor-animation-*` CSS classes handle hover animations.

## Architecture

- **`render()` is a four-step partial chain, not a monolithic template** — `eael_infobox_before()` → `render_infobox_icon()` → `render_infobox_content()` → `eael_infobox_after()` ([line 2722](../../includes/Elements/Info_Box.php#L2722)). Splitting by partial keeps each branch's responsibility small: before/after handle the outer wrapper and optional clickable `<a>`, icon and content each handle their own three-way type branch (img / icon / number for icon; content / template for body). `render_infobox_button()` is called from inside `render_infobox_content()` rather than as a top-level step, because the button is only relevant when content is shown and the button toggle is on.
- **Layout-as-class on the inner wrapper** — `eael-infobox` is always present; `icon-on-top` / `icon-on-bottom` / `icon-on-left` / `icon-on-right` is added by `eael_infobox_before()` from `eael_infobox_img_type`. SCSS rules key on those classes for flexbox direction. No template duplication per layout.
- **Liquid Glass picker uses a `prefix_class`** — `eael_wd_liquid_glass-` is applied to the widget wrapper, so SCSS rules like `.eael_wd_liquid_glass-effect1 .eael-infobox-button { … }` can target the chosen variant without a server-side branch in `render()`.
- **Pro injection via `do_action`, not class extension** — Lite emits `do_action('eael_wd_liquid_glass_effect_bg_color_effect4', $this, 'effect4', '#FFFFFF1F', '.eael-infobox-button')` (and similar for 5, 6, backdrop_filter_4/5/6, noise, svg). In Lite no listener is registered, so the action is a no-op. Pro's `Bootstrap.php` registers handlers that call the same trait-side helpers Lite uses for Effects 1 and 2, but with the Pro effect ids. This is the same extension pattern Creative Button uses — Lite owns the widget class; Pro extends behaviour by attaching to the actions Lite emits.
- **Icon migration shim (`__fa4_migrated`)** — `eael_infobox_icon` (legacy FA4 string) and `eael_infobox_icon_new` (Elementor `ICONS` control) coexist; same for the button icon. `render_infobox_icon()` ([line 2520](../../includes/Elements/Info_Box.php#L2520)) checks the migrated flag plus an emptiness check on the legacy field to pick the new control when present.
- **`HelperTrait` brings Liquid Glass helpers into scope** — Info_Box uses `Essential_Addons_Elementor\Traits\Helper as HelperTrait` ([line 20](../../includes/Elements/Info_Box.php#L20)) for `eael_wd_liquid_glass_effect_bg_color_effect()` and `_backdrop_filter_effect()` so Effects 1 and 2 register the same way the Pro hooks do for Effects 4/5/6. One implementation, six call sites.

## Render Output

The widget's structure depends on whether the card is clickable (`eael_show_infobox_clickable === 'yes'`) and the chosen image position. The annotated tree below uses `icon-on-top` with a button; conditional elements marked `[?]`.

```html
[?] <a href="…" class="…">  <!-- only when clickable mode is on -->
  <div class="eael-infobox icon-on-top">
    <div class="infobox-icon eael-icon-only elementor-animation-…">
      <!-- one of: -->
      <img src="…" alt="…">                                       <!-- img -->
      <div class="infobox-icon-wrap"><i class="fas fa-…"></i></div> <!-- icon -->
      <div class="infobox-icon-wrap">                                <!-- number -->
        <span class="infobox-icon-number">1</span>
      </div>
    </div>
    <div class="infobox-content eael-icon-only">
      [?] <div class="infobox-title-section">
            [?] <h4 class="sub_title">Sub title</h4>
            [?] <h2 class="title">Title</h2>
          </div>
      [?] <div>WYSIWYG body OR <div class="eael-infobox-template-wrapper">…template…</div></div>
      [?] <div class="infobox-button">
            <a class="eael-infobox-button" href="…">
              [?] <svg>…liquid-glass defs (Pro only)…</svg>
              [?] <img class="eael_infobox_button_icon_left" …>   <!-- icon Before -->
              <span class="infobox-button-text">Click Me!</span>
              [?] <i class="eael_infobox_button_icon_right …"></i> <!-- icon After -->
            </a>
          </div>
    </div>
  </div>
[?] </a>
```

Notes:

- `.eael-infobox` is the styling root and class hook. The trailing class (`icon-on-top` etc.) controls layout direction.
- Wrapper-level prefix classes are added by Elementor for alignment (`eael-infobox-content-align-{left|center|right}-`), shape (`eael-infobox-shape-{square|circle|radius}`), hover shape, and Liquid Glass selection (`eael_wd_liquid_glass-{effect1|…|effect6}`).
- `.infobox-content.eael-icon-only` — the `eael-icon-only` class is only added when visual type is `icon` (not when it is image or number); name is misleading.
- The button's leading `<svg>` is injected by Pro via `do_action('eael_wd_liquid_glass_effect_svg_pro', …)`; in Lite the action emits nothing.
- The whole-card `<a>` (clickable mode) only renders when `eael_show_infobox_clickable === 'yes'`. The button toggle and clickable toggle are mutually exclusive at the control level (`condition` keys), so only one of the two anchor styles wraps the card.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Info_Box.php#L89) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_infobox_img_type` | SELECT | `img-on-top` | Content → Image | Layout class on inner wrapper |
| `eael_infobox_img_or_icon` | CHOOSE (responsive) | `icon` | Content → Image | Which icon partial renders (img / icon / number / none) |
| `icon_vertical_position` | CHOOSE (responsive) | `top` | Content → Image | `align-self` on `.infobox-icon` (only for img-on-left / img-on-right) |
| `icon_vertical_position_top_bottom` | CHOOSE (responsive) | `middle` | Content → Image | `align-self` on `.infobox-icon` (only for img-on-top / img-on-bottom) |
| `eael_infobox_image` | MEDIA | placeholder | Content → Image | `<img src>` in the icon partial |
| `eael_infobox_icon_new` | ICONS | `fas fa-building` | Content → Image | Icon glyph (legacy `eael_infobox_icon` shimmed via `__fa4_migrated`) |
| `eael_infobox_number` | TEXT (dynamic) | empty | Content → Image | Number text in the icon partial |
| `eael_infobox_title` | TEXT (dynamic) | `"This is an icon box"` | Content → Content | `.title` text |
| `eael_infobox_title_tag` | SELECT | `h2` | Content → Content | Title HTML element tag |
| `eael_infobox_show_sub_title` | SWITCHER | off | Content → Content | Toggles sub-title rendering |
| `eael_infobox_show_sub_title_after` | CHOOSE | `column` | Content → Content | `flex-direction` on `.infobox-title-section` |
| `eael_infobox_sub_title` | TEXT (dynamic) | `"This is a sub title"` | Content → Content | `.sub_title` text |
| `eael_infobox_sub_title_tag` | SELECT | `h4` | Content → Content | Sub-title HTML element tag |
| `eael_infobox_text_type` | SELECT | `content` | Content → Content | WYSIWYG vs saved-template path |
| `eael_primary_templates` | `eael-select2` | empty | Content → Content | Template id (conditional on `text_type === 'template'`) |
| `eael_infobox_text` | WYSIWYG | sample paragraph | Content → Content | Body HTML (conditional on `text_type === 'content'`) |
| `eael_show_infobox_content` | SWITCHER | `yes` | Content → Content | Toggles the body+button block |
| `eael_infobox_content_alignment` | CHOOSE (responsive) | `center` | Content → Content | `prefix_class` `eael-infobox-content-align-%s-` (img-on-top only) |
| `eael_infobox_content_alignment_left_right` | CHOOSE (responsive) | empty | Content → Content | Same `prefix_class` (img-on-left / img-on-right) |
| `content_height` | SLIDER | empty | Content → Content | `height` on `.infobox-content` |
| `eael_show_infobox_button` | SWITCHER | off | Content → Button | Renders `.infobox-button` block |
| `eael_wd_liquid_glass_effect_switch` | SWITCHER | off | Content → Button | Unlocks the Liquid Glass section |
| `eael_show_infobox_clickable` | SWITCHER | `no` | Content → Button | Wraps the whole card in an `<a>` (mutually exclusive with button) |
| `eael_show_infobox_clickable_link` | URL (dynamic) | empty | Content → Button | Whole-card link target |
| `infobox_button_text` | TEXT (dynamic) | `"Click Me!"` | Content → Button | `.infobox-button-text` content |
| `infobox_button_link_url` | URL (dynamic) | `#` | Content → Button | Button `<a href>` |
| `eael_infobox_button_icon_new` | ICONS | empty | Content → Button | Button icon glyph |
| `eael_infobox_button_icon_alignment` | SELECT | `left` | Content → Button | Icon Before / After (Lite uses `left` / `right`) |
| `eael_infobox_button_icon_indent` | SLIDER | empty | Content → Button | Icon margin (separate selectors for left/right placement) |
| `eael_infobox_button_icon_rotate` | SLIDER (deg) | `0` | Content → Button | `rotate` CSS on the `<svg>` / `<i>` |
| `eael_section_infobox_container_bg` / `_padding` / `_margin` / `_border` / `_border_radius` | COLOR / DIMENSIONS / GROUP / DIMENSIONS | various | Style → Container | Outer card visual chrome |
| `eael_infobox_image_icon_bg_color` / `_shadow` / `_border` (Normal + Hover tabs) | COLOR / GROUP | various | Style → Image | Image variant chrome |
| `eael_infobox_img_shape` / `_hover_img_shape` | SELECT | `square` / `square` | Style → Image | `prefix_class` for shape (square / circle / radius) |
| `eael_infobox_img_shape_radius` / `_radius_hover` | DIMENSIONS | empty | Style → Image | `border-radius` on `.infobox-icon img` |
| `eael_wd_liquid_glass_effect` | CHOOSE | `effect1` | Style → Liquid Glass Effects | `prefix_class` `eael_wd_liquid_glass-%s` |
| `eael_wd_liquid_glass_effect_brightness_effect2` | SLIDER | `1` | Style → Liquid Glass Effects | Brightness in `backdrop-filter` (Effect 2 only) |

Plus typography groups for title, sub-title, body, button text; per-tab (Normal / Hover) controls for icon, number, button; padding / margin / sizing on each block; box-shadow on each block.

## Conditional Dependencies

A control hidden by a condition still saves its value. Map answers "why doesn't option X show?" without reading the source.

```text
icon_vertical_position           → visible when eael_infobox_img_type NOT in
                                     ['img-on-top', 'img-on-bottom']
icon_vertical_position_top_bottom → visible when eael_infobox_img_type NOT in
                                     ['img-on-left', 'img-on-right']
eael_infobox_image               → visible when eael_infobox_img_or_icon == 'img'
eael_infobox_icon_new            → visible when eael_infobox_img_or_icon == 'icon'
eael_infobox_number              → visible when eael_infobox_img_or_icon == 'number'
eael_infobox_show_sub_title_after → visible when eael_infobox_show_sub_title == 'yes'
eael_infobox_sub_title           → visible when eael_infobox_show_sub_title == 'yes'
eael_infobox_sub_title_tag       → visible when eael_infobox_show_sub_title == 'yes'
eael_primary_templates           → visible when eael_infobox_text_type == 'template'
eael_infobox_text                → visible when eael_infobox_text_type == 'content'
eael_infobox_content_alignment   → visible when eael_infobox_img_type == 'img-on-top'
eael_infobox_content_alignment_left_right
                                 → visible when eael_infobox_img_type in
                                     ['img-on-left', 'img-on-right']
eael_show_infobox_button         → visible when eael_show_infobox_clickable != 'yes'
eael_wd_liquid_glass_effect_switch
                                 → visible when eael_show_infobox_button == 'yes'
eael_show_infobox_clickable      → visible when eael_show_infobox_button != 'yes'
eael_show_infobox_clickable_link → visible when eael_show_infobox_clickable == 'yes'
infobox_button_text              → visible when eael_show_infobox_button == 'yes'
infobox_button_link_url          → visible when eael_show_infobox_button == 'yes'
eael_infobox_button_icon_new     → visible when eael_show_infobox_button == 'yes'
eael_infobox_button_icon_alignment
                                 → visible when eael_show_infobox_button == 'yes'
                                   AND eael_infobox_button_icon_new != ''
Style → Image section            → visible when eael_infobox_img_or_icon == 'img'
Style → Icon section             → visible when eael_infobox_img_or_icon == 'icon'
Style → Number Icon section      → visible when eael_infobox_img_or_icon == 'number'
eael_wd_liquid_glass_effect_section
                                 → visible when eael_show_infobox_button == 'yes'
                                   AND eael_wd_liquid_glass_effect_switch == 'yes'
eael_wd_liquid_glass_effect_pro_alert
                                 → visible when Pro is NOT active
                                   AND eael_wd_liquid_glass_effect in ['effect4','effect5','effect6']
eael_section_pro / eael_control_get_pro
                                 → visible when Pro plugin is NOT active
```

## Behavior Flow

End-to-end sequence from "user drops widget on canvas" to "rendered card on a published page".

1. User drops the widget → Elementor calls `register_controls()` → panel appears with Image / Content / Button content tabs plus Container / Image / Icon / Number Icon / Title / Color & Typography / Button / Liquid Glass style sections.
2. The Liquid Glass picker presents Effects 1–6; Effects 4, 5, 6 have a pad-lock icon appended ([line 2071](../../includes/Elements/Info_Box.php#L2071)) emitted by `eael_pro_lock_icon()` when Pro is not active.
3. User configures settings → Elementor saves to post meta.
4. Editor preview iframe re-renders via [`render()`](../../includes/Elements/Info_Box.php#L2722).
5. `render()` orchestrates four partials: `eael_infobox_before()` writes the outer `<a>` (if clickable) plus `<div class="eael-infobox icon-on-{position}">`.
6. `render_infobox_icon()` branches on `eael_infobox_img_or_icon` to emit one of: `<img>`, `<div class="infobox-icon-wrap"><i></i></div>` (with FA4 / FA5 shim), or `<div class="infobox-icon-wrap"><span class="infobox-icon-number">…</span></div>`; nothing if `none`.
7. `render_infobox_content()` writes the title section (sub-title + title, with the sub-title direction controlled by `flex-direction`), then the body. The body is either WYSIWYG (sanitised via `Helper::eael_allowed_tags()`) or a `get_builder_content()` call for a saved Elementor template (with `wpml_object_id` translation lookup).
8. Inside `render_infobox_content()` and only when `eael_show_infobox_content === 'yes'` and content_type is `'content'`, `render_infobox_button()` runs. It emits the button anchor, fires `do_action('eael_wd_liquid_glass_effect_svg_pro', …)` for Pro to inject SVG `<defs>`, then writes the icon (Before / After) and label.
9. `eael_infobox_after()` closes the inner div and the optional outer `<a>`.
10. Browser receives HTML. CSS handles every layout permutation; hover animations are driven by Elementor's `elementor-animation-*` classes that the widget stamps from the `HOVER_ANIMATION` control.
11. If Liquid Glass is enabled and Pro is active, Pro's handler for `eael_wd_liquid_glass_effect_svg_pro` has already injected an inline `<svg>` with the appropriate filter `<defs>` that the CSS `backdrop-filter: url(#…)` references.

## JavaScript Lifecycle

N/A — pure CSS widget, no widget-specific JavaScript.

The widget ships no source file under `src/js/view/`, declares no JS dependency in [`config.php`](../../config.php#L180), and registers no Elementor frontend `addAction`. Hover animations rely on Elementor's bundled `elementor-animation-*` classes; layout and Liquid Glass effects are CSS-only.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one `Info_Box` widget is detected on the page. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `info-box.min.css` | self (built from `src/css/view/info-box.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| _(none)_ | — | Pure CSS widget — no JS asset is registered for this slug | — |

Font Awesome glyphs render via `Icons_Manager::render_icon`, which depends on Elementor's `font-awesome-5-all` handle; Elementor's animations CSS provides the `elementor-animation-*` keyframes the hover-animation control writes.

## Hooks & Filters

The widget's public contract — Pro consumes most of these to inject Liquid Glass behaviour; third parties can override the picker filter or replace the SVG injection.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_liquid_glass_effect_filter` | filter (emitted) | `array $defaults` with key `styles` (effect ids → labels) | Lets Pro or a third party rename / add Liquid Glass effect labels in the picker ([line 2042](../../includes/Elements/Info_Box.php#L2042)) |
| `eael_wd_liquid_glass_effect_bg_color_effect4` | action (emitted) | `(\Elementor\Widget_Base $widget, string $effect, string $default_color, string $selector)` | Pro registers a bg-color control for Effect 4 ([line 2124](../../includes/Elements/Info_Box.php#L2124)) |
| `eael_wd_liquid_glass_effect_bg_color_effect5` / `_effect6` | action (emitted) | same | Same for Effects 5 and 6 |
| `eael_wd_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6` | action (emitted) | same | Pro registers backdrop-filter (`blur`, `brightness`) controls for Effects 4, 5, 6 |
| `eael_wd_liquid_glass_effect_noise_action` | action (emitted) | `(\Elementor\Widget_Base $widget)` | Pro registers noise-distortion controls ([line 2163](../../includes/Elements/Info_Box.php#L2163)) |
| `eael_wd_liquid_glass_effect_svg_pro` | action (emitted) | `(\Elementor\Widget_Base $widget, array $settings, string $selector)` | Pro injects inline `<svg>` with filter `<defs>` inside the button anchor at render time ([line 2672](../../includes/Elements/Info_Box.php#L2672)) |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides upsell sections; toggles the Pro lock icon on Effects 4/5/6 in the picker |
| `wpml_object_id` | filter (consumed) | `int $object_id, string $type, bool $return_original` | Translates the saved-template id for the body content under WPML ([line 2629](../../includes/Elements/Info_Box.php#L2629)) |

The Liquid Glass actions are widget-agnostic — the same hooks fire from Creative Button, Cta_Box (when extended in Pro), and any other widget using the same infrastructure. They are the canonical Pro-extension mechanism for visual effects in EA.

## Customization Recipes

### Recipe 1 — Add a custom Liquid Glass effect label without Pro

```php
add_filter( 'eael_liquid_glass_effect_filter', function ( $defaults ) {
    $defaults['styles']['effect1'] = __( 'Heavy Fog', 'my-theme' );  // rename
    return $defaults;
} );
```

The picker option text changes; the CSS class (`eael_wd_liquid_glass-effect1`) stays the same so existing rules still match.

### Recipe 2 — Override Liquid Glass for a specific section via theme CSS

```scss
.my-info-card .eael_wd_liquid_glass-effect1 .eael-infobox-button {
    backdrop-filter: blur(40px) saturate(180%);
    background: rgba(255, 255, 255, 0.15);
}
```

Set a Custom CSS Class of `my-info-card` on a parent column. Lets you tune the blur per-section without touching the widget controls.

### Recipe 3 — Strip the saved-template body content for SEO crawlers

```php
add_filter( 'eael/pro_enabled', '__return_true', 99 );  // unrelated; see below
add_action( 'wp', function () {
    if ( ! wp_is_serving_rest_request() && isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
        if ( str_contains( $_SERVER['HTTP_USER_AGENT'], 'Googlebot' ) ) {
            add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
                if ( $widget->get_name() === 'eael-info-box' ) {
                    return wp_strip_all_tags( $content );
                }
                return $content;
            }, 10, 2 );
        }
    }
} );
```

Useful when the saved template embeds heavy iframes / scripts that hurt indexing. The visible page still renders the template; the crawler gets clean text.

### Recipe 4 — Force the whole card clickable behaviour from a parent context

```php
add_action( 'elementor/element/before_section_end', function ( $element, $section_id ) {
    if ( $element->get_name() !== 'eael-info-box' ) {
        return;
    }
    if ( $section_id !== 'eael_infobox_button' ) {
        return;
    }
    $element->update_control( 'eael_show_infobox_clickable', [
        'default' => 'yes',
    ] );
}, 10, 2 );
```

Sets the default for new widgets to "card clickable" rather than "button". Existing saved widgets keep their stored value.

## Common Issues

### Liquid Glass effect 4 / 5 / 6 shows the upsell card and renders nothing

- **Likely cause:** Pro is not active; the picker has the pad-lock icon for Effects 4–6 but lets the user pick them anyway. The `eael_wd_liquid_glass_effect_pro_alert` raw HTML control appears in the panel when one of those is selected without Pro
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` — should be `true` when Pro is active
- **Fix:** activate Pro; or pick Effect 1 or 2 which are Lite-supported

### Liquid Glass renders nothing even with Pro and a semi-transparent background

- **Likely cause:** the `eael_wd_liquid_glass_effect_switch` switcher is off — without it the section is hidden and no `eael_wd_liquid_glass-effect*` class is applied
- **Diagnose:** open the Button section in Content tab and confirm "Enable Liquid Glass Effects" is on
- **Fix:** toggle it on; the alert at the top of the section reminds users the background must be semi-transparent

### Clickable mode and button are both ignored

- **Likely cause:** they are mutually exclusive — `eael_show_infobox_button` is hidden when clickable is `yes`, and vice versa. The control values still save but the panel hides whichever is off
- **Diagnose:** verify both switches in the saved widget JSON — one of them must be `yes`, not both
- **Fix:** pick one; if neither is on, neither anchor is rendered

### Body content disappears after switching `eael_infobox_text_type` to template

- **Likely cause:** the chosen template is in Draft / Pending state, or empty; `Helper::is_elementor_publish_template()` only accepts Published ones ([line 2622](../../includes/Elements/Info_Box.php#L2622))
- **Diagnose:** check the template status in Elementor → Templates
- **Fix:** publish the template; or pick a different one

### Icon position control is missing in the panel

- **Likely cause:** two separate icon-position controls exist — `icon_vertical_position` (for img-on-left / img-on-right) and `icon_vertical_position_top_bottom` (for img-on-top / img-on-bottom). The visible one depends on the chosen image position
- **Diagnose:** switch `eael_infobox_img_type` and watch which one appears
- **Fix:** by design; if the visible control's options don't read intuitively ("Top / Middle / Bottom" for img-on-left actually mean vertical alignment of the icon), that's the legacy naming

### Hover animations on the icon don't trigger

- **Likely cause:** Elementor's bundled animation CSS isn't loaded — the widget writes `elementor-animation-*` classes but relies on Elementor itself providing the keyframes
- **Diagnose:** in DevTools check whether `elementor-animation-grow` (or whichever) class is on `.infobox-icon` and whether a keyframe rule exists
- **Fix:** ensure Elementor is active and not aggressively excluded by performance plugins; clear Elementor's CSS cache

### Button icon Before / After is documented but the panel only shows "left" / "right"

- **Likely cause:** the underlying values are still `left` and `right` — the SELECT options were relabelled to "Before" / "After" but the stored value is unchanged ([line 654](../../includes/Elements/Info_Box.php#L654))
- **Diagnose:** export the widget JSON; the field stores `left` or `right`
- **Fix:** this is intentional for back-compat; theme CSS keying on `.eael_infobox_button_icon_left` / `_right` continues to work

### Card content goes past the container on small screens

- **Likely cause:** `content_height` was set to a fixed `px` height ([line 511](../../includes/Elements/Info_Box.php#L511)) without a responsive override
- **Diagnose:** check the control's tablet / mobile values
- **Fix:** use `%` units or set lower values on smaller breakpoints; the control isn't responsive by default

## Testing Checklist

- [ ] Drop at default config — icon (`fa-building`) at top, sample title and paragraph render; no PHP notices in `wp-content/debug.log`
- [ ] Switch image position to each of `img-on-top` / `_bottom` / `_left` / `_right` — wrapper class changes; flex layout flips
- [ ] Switch visual type to None / Number / Icon / Image — only the matching child renders inside `.infobox-icon`
- [ ] Empty the title and sub-title — `.infobox-title-section` is omitted entirely
- [ ] Switch sub-title position from Top to Bottom — `flex-direction` on `.infobox-title-section` flips
- [ ] Switch content type to Saved Templates — pick a published template; template renders in editor and on frontend
- [ ] Pick a Draft template — body content disappears (known limitation)
- [ ] Toggle "Show Content" off — `.infobox-content` still wraps but title section and body are absent; button also hidden
- [ ] Enable button with icon "Before" then "After" — icon appears in matching position; icon spacing applies the correct side margin
- [ ] Enable Infobox Clickable — whole card becomes one `<a>`; button toggle becomes hidden
- [ ] Turn off both clickable and button — neither anchor renders; card is non-interactive
- [ ] Enable Liquid Glass with Effect 1 — `eael_wd_liquid_glass-effect1` class appears on widget wrapper; button gets `backdrop-filter: blur(…)` rule
- [ ] Pick Effect 4 without Pro — `eael_wd_liquid_glass_effect_pro_alert` upsell card appears; no bg-color / backdrop rules emitted
- [ ] Activate Pro and pick Effect 4 — bg-color and backdrop-filter rules emitted; SVG `<defs>` injected inside the button anchor
- [ ] Switch image hover shape between square / circle / radius — `prefix_class` updates on `:hover`
- [ ] Responsive — alignment differs per breakpoint; image position can be different per breakpoint
- [ ] WPML site with a translated template id — body content shows the translated template
- [ ] Special characters (`<script>`) in title — output is sanitised through `Helper::eael_allowed_tags`; no XSS
- [ ] Pro upsell section in panel disappears when Pro is active; reappears when deactivated
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Four-step partial chain in `render()` instead of one template

- **Context:** the widget combines three optional render elements (icon, content, button), an optional wrapping anchor, and four image positions.
- **Decision:** split `render()` into `eael_infobox_before()` → `render_infobox_icon()` → `render_infobox_content()` (which calls `render_infobox_button()`) → `eael_infobox_after()`.
- **Alternatives rejected:** one monolithic template with many `<?php if … ?>` branches — too hard to follow at 200+ lines.
- **Consequences:** four short methods, each with clear responsibility; the button is reached through content rather than via a top-level step (intentional — it should never render without the content section).

### Pro extension via `do_action` injection, not class extension

- **Context:** Pro needs to add three Liquid Glass effects (4, 5, 6), shadow effects, noise distortion, and SVG `<defs>` injection — all of which should hook into the same widget.
- **Decision:** Lite emits `do_action()` at every extension point. Pro's Bootstrap registers handlers that call the same trait helpers Lite uses for Effects 1 and 2.
- **Alternatives rejected:** Pro extends `Info_Box` class — would force Pro to re-implement / shadow the Lite class and complicate widget registration; Pro filters the rendered HTML — slow and brittle.
- **Consequences:** Lite owns the widget class; Pro is a pure listener. When Pro is uninstalled, all `do_action` calls become no-ops with no error. Same pattern is used across the EA plugin pair.

### Locked Pro effects shown in the Lite picker rather than hidden

- **Context:** Effects 4, 5, 6 are Pro-only but should be visible to Lite users for discovery.
- **Decision:** keep them in the `CHOOSE` options with a pad-lock icon appended (`$this->eael_pro_lock_icon()`); show a Pro upsell raw-HTML control when one is selected without Pro.
- **Alternatives rejected:** hide them entirely from Lite — Lite users never see what Pro adds; show them but render Effect 1 as a silent fallback — surprises the user.
- **Consequences:** Lite users see a clear "this is Pro" affordance; the rendered class still gets emitted (no fallback in `render()`), which means picking a locked effect without Pro produces a visibly unstyled button — that's the discoverability cost.

### `HelperTrait` import to share Liquid Glass helpers across widgets

- **Context:** Lite-side Effect 1 and 2 need `eael_wd_liquid_glass_effect_bg_color_effect()` and `_backdrop_filter_effect()`; the same methods are needed by Creative Button, Cta_Box (in Pro), Flip Box, etc.
- **Decision:** put the helpers in `Essential_Addons_Elementor\Traits\Helper` and `use HelperTrait` in each widget class.
- **Alternatives rejected:** a static helper class — works but loses `$this` access to the widget's `add_control()` method; copy-paste the methods into each widget — fragmenting the implementation.
- **Consequences:** one implementation per helper; the trait is imported in any widget that exposes Liquid Glass controls.

## Known Limitations

- **`render_infobox_button()` has duplicated render-attribute code at lines [2693-2710](../../includes/Elements/Info_Box.php#L2693)** — the legacy FA4 branch for the right-icon case writes both `eael_infobox_button_icon_left` and `eael_infobox_button_icon_right` classes onto `button_icon`, plus the raw legacy `eael_infobox_button_icon` class. Cosmetic; effective CSS-target class is whichever last rule matches. Legacy code worth a cleanup pass.
- **`infobox_button_text` is output via `esc_attr` ([line 2684](../../includes/Elements/Info_Box.php#L2684))** rather than `esc_html`. `esc_attr` is stricter than necessary for visible text content but doesn't break anything; future cleanup target.
- **No semantic warning for non-heading title tags** — `title_tag` allows `span`, `p`, `div`, which are visually viable but lose heading semantics for screen readers / search engines.
- **Two icon-position controls with confusingly overlapping option labels** — `icon_vertical_position` and `icon_vertical_position_top_bottom` use Top / Middle / Bottom labels that mean different things depending on which is active. Minor UX nit.
- **`content_height` is non-responsive** — set to `300px` desktop and the same applies on mobile; users need to manually override responsively.
- **No CSS for `.icon-on-bottom` content alignment matching `.icon-on-top`** — the SCSS handles top, left, right thoroughly; bottom is less polished historically.
- **Picker shows locked Effects 4/5/6 without rendering fallback** — selecting a locked effect in Lite emits the class but no styling. Visible regression for the user, not a silent fall-through to Effect 1.
- **Saved-template path renders nothing for Draft / Pending templates** — same gotcha as Cta_Box; no editor-side hint when the chosen template is unpublished.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
