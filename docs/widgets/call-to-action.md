# Call to Action Widget

> Renders a heading, supporting copy, and one or two action buttons inside a styled container — with optional background colour, image, or fixed-image backdrop. Three layout styles (basic, flex grid, flex grid with icon) and per-layout presets cover most marketing-card use cases.

**Class file:** [`includes/Elements/Cta_Box.php`](../../includes/Elements/Cta_Box.php)
**Slug:** `call-to-action` (widget id `eael-cta-box`)
**Public docs:** <https://essential-addons.com/elementor/docs/call-to-action/>
**Pro-shared:** ❌ — Lite-only widget. Pro does not re-implement or extend the class. The only Pro interaction is the standard upsell section that hides when `apply_filters( 'eael/pro_enabled', false )` returns true.

---

## Overview

Call to Action is a marketing-card widget that combines a heading, an optional sub-title, descriptive body copy (rich text or a saved Elementor template), and a primary action button — with an optional secondary button alongside. Three layout styles ship out of the box: basic centred copy, flex grid (copy left / button right), and flex grid with a leading icon.

The widget is intentionally CSS-driven — there is no widget-specific JavaScript. All visual variation (presets, button hover effects, layout switches, multi-colour titles) is achieved by class-name toggling in `render()` and matching rules in [`call-to-action.scss`](../../src/css/view/call-to-action.scss). Most marketing-card needs ("get started" sections, pricing footers, content upgrades) are covered without writing any custom CSS.

## Features

- Three layout styles: Basic, Flex Grid, Flex Grid with Icon
- Two visual presets per layout (`cta-preset-1`, `cta-preset-2`) with distinct typography and button placement defaults
- Background as solid colour, scrolling image, or fixed-attachment image with optional overlay
- Per-image background controls (repeat, position, size) behind a popover
- Heading with selectable HTML tag (`h1`–`h6`, `span`, `p`, `div`) and optional sub-title above
- Multi-colour title via a Repeater — each chunk gets its own colour, typography, or gradient fill
- Body copy as inline WYSIWYG **or** an existing Elementor template (WPML-aware)
- Primary and optional secondary button — each with icon, icon position, link, and two hover effect styles (top-to-bottom, left-to-right)
- Per-button typography, gradient background, padding, and margin
- Responsive controls for alignment, container width, and most spacing values
- Pro-upsell section hidden automatically when Pro is active

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All three layout styles (basic / flex / icon-flex) | ✅ | ✅ |
| Two presets per layout | ✅ | ✅ |
| Multi-colour title with gradient option | ✅ | ✅ |
| Saved-template content type (Elementor library) | ✅ | ✅ |
| `eael_section_pro` upsell section in panel | shown | hidden |
| Class extension or filter hooks specifically for this widget | ❌ | ❌ — Pro adds nothing widget-specific |

Pro does not register additional styles, presets, or controls for Call to Action. The only Lite-side concession is hiding the upsell section when Pro is active.

## Use Cases

- Hero-style call-out below a landing-page intro ("Start your free trial")
- Newsletter sign-up card at the bottom of a blog post (icon-flex layout, secondary "Learn More" button)
- Pricing-page footer card driving users to "Contact Sales"
- Feature card embedding a saved template (image + paragraph) and a single CTA button
- Service-page mid-section break separating two long sections of copy

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Cta_Box.php`](../../includes/Elements/Cta_Box.php) | PHP widget class — controls, render, layout branching |
| [`src/css/view/call-to-action.scss`](../../src/css/view/call-to-action.scss) | Source styles — layout, presets, hover effects, image backgrounds |
| [`config.php`](../../config.php) entry `'call-to-action'` | `Asset_Builder` dependency declaration (CSS only) |
| `assets/front-end/css/view/call-to-action.min.css` | Built output (do not edit) |

No widget-specific JS source or compiled file exists — the widget is pure CSS plus class-name switching from `render()`.

## Architecture

- **Three layouts × two presets = six rendered shapes, all class-driven** — `render()` emits one of three top-level `<div>` shapes (`cta-basic`, `cta-flex`, `cta-icon-flex`) and stamps `cta-preset-1` / `cta-preset-2` on the same root. Every visual variant is matched in SCSS by a class selector; no per-variant template duplication beyond the three layout branches.
- **No widget-specific JavaScript** — the widget is intentionally static. Hover effects are CSS-only (`::before` / `::after` transforms keyed on `effect-1` / `effect-2`), so there is no init handler, no `elementStatusCheck` guard, and no vendor lib to load. Saves ~3 KB versus the JS-driven hover-effect path used by Creative Button.
- **Saved-template content path uses `Plugin::$instance->frontend->get_builder_content`** — when `eael_cta_title_content_type === 'template'`, the body copy is replaced with the rendered output of another Elementor template. WPML filter `wpml_object_id` is applied to the template id so multilingual sites resolve the correct translation. In editor mode the template content is wrapped with `eael-cta-template-wrapper` so on-page editing affordances can target it ([line 2140](../../includes/Elements/Cta_Box.php#L2140)).
- **Multi-colour title is a Repeater rendered as inline spans** — each repeater item becomes a `<span class="eael-cta-title-text elementor-repeater-item-<id>">…</span>` so its typography, colour, or gradient fill can be styled per-item via the `{{CURRENT_ITEM}}` selector hook. The Repeater is only consumed when the simple-title text field is empty and `eael_cta_enable_multi_color_title` is on ([line 2110](../../includes/Elements/Cta_Box.php#L2110)).
- **Icon migration shim for the icon-flex layout** — `eael_cta_flex_grid_icon` (legacy FA4 string) and `eael_cta_flex_grid_icon_new` (Elementor `ICONS` control) coexist. The render path checks `__fa4_migrated` flag plus an emptiness check on the legacy field to pick the new control when present ([lines 2073-2074](../../includes/Elements/Cta_Box.php#L2073)). This pattern lets old saved widgets keep rendering after the FA4-to-FA5 migration without forcing a one-time data rewrite.

## Render Output

The widget produces one of three DOM shapes depending on `eael_cta_type`. Annotated below; conditional elements marked `[?]`.

### Basic layout (`cta-basic`)

```html
<div class="eael-call-to-action cta-basic bg-lite cta-preset-1">
  [?] <h4 class="sub-title">Sub title text</h4>
  <h2 class="title eael-cta-heading">
    Heading text
    [?] <span class="eael-cta-title-text elementor-repeater-item-<id>">multi-colour chunk</span>
  </h2>
  [?] WYSIWYG content OR <div class="eael-cta-template-wrapper">…template…</div>
  <a class="cta-button cta-preset-1 cta-btn-preset-2 effect-2" href="…">
    [?] <span class="btn-icon"><i class="fas …"></i></span>
    Click Here
  </a>
  [?] <a class="cta-button cta-secondary-button effect-1" href="…">…</a>
</div>
```

### Flex Grid layout (`cta-flex`)

```html
<div class="eael-call-to-action cta-flex bg-img bg-fixed cta-preset-2">
  <div class="content">
    <h4 class="sub-title">…</h4>
    <h2 class="title eael-cta-heading">…</h2>
    …body content…
  </div>
  <div class="action">
    <a class="cta-button …">Primary</a>
    [?] <a class="cta-button cta-secondary-button …">Secondary</a>
  </div>
</div>
```

### Flex Grid with Icon layout (`cta-icon-flex`)

```html
<div class="eael-call-to-action cta-icon-flex bg-lite cta-preset-1">
  <div class="icon">
    <i class="fas fa-bullhorn"></i>     <!-- legacy FA4 -->
    OR
    <img src="…" alt="…" />              <!-- if new ICONS control returned an SVG upload -->
    OR
    <svg>…rendered icon…</svg>           <!-- standard FA5 path -->
  </div>
  <div class="content">…heading + body…</div>
  <div class="action">…buttons…</div>
</div>
```

Notes:

- `.eael-call-to-action` is the styling root. The first companion class indicates layout (`cta-basic` / `cta-flex` / `cta-icon-flex`), the second the background mode (`bg-lite` / `bg-img` / `bg-img bg-fixed`), the third the preset (`cta-preset-1` / `cta-preset-2`).
- Button hover effect classes (`effect-1`, `effect-2`) are added only when the corresponding effect type is selected — `default` emits no extra class.
- Sub-title `<h4 class="sub-title">` is omitted entirely when the field is empty (not rendered as an empty tag).
- Outer body copy markup is sanitised through `Helper::eael_allowed_tags()`; titles go through the same allowlist before being echoed.
- The basic layout writes everything inside a single root — no separate `content` / `action` wrappers. The flex layouts split into `.content` and `.action` for flexbox sizing.

## Controls Reference

Comprehensive table of meaningful controls. Source [`register_controls()`](../../includes/Elements/Cta_Box.php#L88) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_cta_type` | SELECT | `cta-basic` | Content → Layout | Top-level layout branch in `render()` |
| `eael_cta_preset` | SELECT | `cta-preset-1` | Content → Layout | Preset class on root + on `<a>` |
| `eael_cta_content_type` | CHOOSE (responsive) | `cta-default` | Content → Layout | `prefix_class` `content-align-%s` (Basic only) |
| `eael_cta_color_type` | SELECT | `cta-bg-color` | Content → Layout | Background class: `bg-lite` / `bg-img` / `bg-img bg-fixed` |
| `eael_cta_bg_image` | MEDIA | placeholder | Content → Layout | `background-image` URL on `.bg-img` / `.bg-img-fixed` |
| `eael_cta_bg_image_repeat` / `_position` / `_size` | SELECT | `no-repeat` / `center` / `cover` | Content → Layout (popover) | Image positioning rules |
| `eael_cta_bg_overlay` | SWITCHER | `yes` | Content → Layout | `prefix_class` `eael-cta-overlay-%s` |
| `eael_cta_flex_grid_icon_new` | ICONS | `fas fa-bullhorn` | Content → Layout | `.icon` content (icon-flex layout only) |
| `eael_cta_enable_multi_color_title` | SWITCHER | off | Content → Content | Switches between simple TEXT and Repeater |
| `eael_cta_title` | TEXT (dynamic) | `"Sample Call to Action Heading"` | Content → Content | `.eael-cta-heading` text |
| `eael_cta_multi_color_title` | REPEATER | 3 default items | Content → Content | Inline `<span>` chunks inside `.eael-cta-heading` |
| `eael_cta_sub_title` | TEXT (dynamic) | empty | Content → Content | `<h4 class="sub-title">` content |
| `title_tag` | CHOOSE | `h2` | Content → Content | Heading element tag |
| `eael_cta_title_content_type` | SELECT | `content` | Content → Content | WYSIWYG body vs saved template |
| `eael_primary_templates` | `eael-select2` | empty | Content → Content | Template id (conditional on `content_type === 'template'`) |
| `eael_cta_content` | WYSIWYG | sample paragraph | Content → Content | Body HTML (conditional on `content_type === 'content'`) |
| `eael_cta_btn_preset` | SELECT | `cta-btn-preset-2` | Content → Button | Button preset class |
| `eael_cta_btn_text` | TEXT (dynamic) | `"Click Here"` | Content → Button | Primary `<a>` label |
| `eael_cta_btn_link` | URL (dynamic) | `#` | Content → Button | Primary `<a href>` (via `add_link_attributes`) |
| `eael_cta_primary_btn_icon_show` | SWITCHER | off | Content → Button | Toggles primary icon (Preset 1 only) |
| `eael_cta_btn_primary_icon` | ICONS | `fas fa-arrow-right` | Content → Button | Primary icon glyph (Preset 1) |
| `eael_cta_btn_icon` | ICONS | `fas fa-bullhorn` | Content → Button | Primary icon glyph (Preset 2) |
| `eael_cta_btn_primary_icon_direction` | CHOOSE | `left` | Content → Button | `float` on `.btn-icon` (Preset 1) |
| `eael_cta_secondary_btn_is_show` | SWITCHER | off | Content → Button | Renders the secondary `<a>` block |
| `eael_cta_secondary_btn_text` / `_link` / `_icon_show` / `_btn_secondary_icon` | TEXT / URL / SWITCHER / ICONS | various | Content → Button | Secondary button payload |
| `eael_cta_btn_effect_type` | SELECT | `default` | Style → Primary Button | `effect-1` / `effect-2` class on primary `<a>` |
| `eael_cta_secondary_btn_effect_type` | SELECT | `default` | Style → Secondary Button | Same on secondary `<a>` |
| `eael_cta_container_width` / `_value` | SWITCHER / SLIDER | `yes` / `1170px` | Style → Call to Action | `max-width` on root |
| `eael_cta_bg_color` / `_preset_2` | COLOR | `#f4f4f4` / empty | Style → Call to Action | Root `background-color` (preset-keyed) |
| `eael_cta_content_color` | COLOR | empty | Style → Call to Action | `p` colour inside root |

Plus typography group controls for sub-title, heading, body, primary button, and secondary button; background / border / box-shadow groups on the primary and secondary buttons; padding & margin (responsive) on the root and on each button.

## Conditional Dependencies

A control hidden by a condition still saves its value. This map answers "why doesn't option X show in my panel?" without reading the source.

```text
eael_cta_content_type           → visible when eael_cta_type == 'cta-basic'
eael_cta_bg_image               → visible when eael_cta_color_type in ['cta-bg-img', 'cta-bg-img-fixed']
eael_cta_bg_image_background_manager (popover toggle and inner controls)
                                → visible when eael_cta_color_type in ['cta-bg-img', 'cta-bg-img-fixed']
eael_cta_bg_overlay             → visible when eael_cta_color_type != 'cta-bg-color'
eael_cta_flex_grid_icon_new     → visible when eael_cta_type == 'cta-icon-flex'
eael_cta_title                  → visible when eael_cta_enable_multi_color_title != 'yes'
eael_cta_multi_color_title      → visible when eael_cta_enable_multi_color_title == 'yes'
eael_primary_templates          → visible when eael_cta_title_content_type == 'template'
eael_cta_content                → visible when eael_cta_title_content_type == 'content'
eael_cta_btn_preset             → visible when eael_cta_preset == 'cta-preset-2'
eael_cta_btn_icon               → visible when eael_cta_preset == 'cta-preset-2'
                                  AND eael_cta_btn_preset == 'cta-btn-preset-2'
eael_cta_btn_primary_icon       → visible when eael_cta_primary_btn_icon_show == 'yes'
                                  AND eael_cta_preset == 'cta-preset-1'
eael_cta_btn_primary_icon_direction
                                → visible when eael_cta_primary_btn_icon_show == 'yes'
                                  AND eael_cta_preset == 'cta-preset-1'
eael_cta_secondary_btn_*        → visible when eael_cta_secondary_btn_is_show == 'yes'
eael_cta_btn_secondary_icon     → visible when eael_cta_secondary_btn_is_show == 'yes'
                                  AND eael_cta_secondary_btn_icon_show == 'yes'
eael_cta_bg_color               → visible when eael_cta_preset == 'cta-preset-1'
eael_cta_bg_color_preset_2      → visible when eael_cta_preset == 'cta-preset-2'
eael_cta_container_width_value  → visible when eael_cta_container_width == 'yes'
eael_section_pro / eael_control_get_pro
                                → visible when Pro plugin is NOT active
```

## Behavior Flow

End-to-end sequence from "user drops widget on canvas" to "user sees rendered CTA on a published page".

1. User drops the widget on the Elementor canvas → Elementor calls `register_controls()` → panel appears.
2. User configures Layout / Content / Button settings → Elementor saves to post meta.
3. Editor preview iframe re-renders via `render()` for live preview.
4. On publish, the front-end HTML is rendered server-side via the same `render()`.
5. [`render()`](../../includes/Elements/Cta_Box.php#L2068) calls `get_settings_for_display()` and computes three class fragments: layout class is implicit in the markup branch chosen at the bottom; background class (`bg-lite` / `bg-img` / `bg-img bg-fixed`) is computed from `eael_cta_color_type`; effect classes (`effect-1` / `effect-2`) are computed per-button from the effect type.
6. Heading markup is assembled — sub-title if non-empty, then either the simple title or each repeater chunk wrapped in a `<span>` (multi-colour branch).
7. Body content is assembled — either the WYSIWYG payload sanitised via `Helper::eael_allowed_tags()`, or a `get_builder_content` call for the saved-template branch (with `wpml_object_id` translation lookup).
8. Primary button attributes are stamped via `add_link_attributes` and `add_render_attribute`. Icon markup is chosen based on preset (`cta-btn-preset-2` always renders an icon; `cta-preset-1` renders one when `eael_cta_primary_btn_icon_show === 'yes'`).
9. Secondary button is built the same way when `eael_cta_secondary_btn_is_show === 'yes'`.
10. One of three template branches runs based on `eael_cta_type` — emitting `.cta-basic`, `.cta-flex`, or `.cta-icon-flex`. The browser receives static HTML; no JS init happens for this widget.
11. Browser renders. CSS handles every visual state — preset variants, hover effects, image overlay, multi-colour title gradient fills.

## JavaScript Lifecycle

N/A — pure CSS widget, no JavaScript.

The widget ships no source file under `src/js/view/`, declares no JS dependency in [`config.php`](../../config.php#L211), and registers no Elementor frontend `addAction`. All interactivity (hover effects, transitions) is achieved with CSS pseudo-elements and transforms keyed on class names assembled in `render()`. Multi-instance pages are safe by construction — there is no shared runtime state to collide.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one `Cta_Box` widget is detected on the page. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats (templates, popups, shortcodes).

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `call-to-action.min.css` | self (built from `src/css/view/call-to-action.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| _(none)_ | — | Widget is pure CSS — no JS asset is registered for this slug | — |

Font Awesome glyphs render via Elementor's `Icons_Manager::render_icon`, which depends on the `font-awesome-5-all` handle Elementor already provides — no extra registration is needed.

## Hooks & Filters

The widget consumes only WordPress / Elementor / EA core hooks; it does **not** export its own filter or action specifically for third-party extension.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the in-panel upsell section when Pro is active ([line 806](../../includes/Elements/Cta_Box.php#L806)) |
| `wpml_object_id` | filter (consumed) | `int $object_id, string $type, bool $return_original` | Translates the saved-template id under WPML before `get_builder_content` ([line 2137](../../includes/Elements/Cta_Box.php#L2137)) |

No widget-specific `eael/cta_*` filter or action exists. Extension via CSS overrides (theme stylesheet) or via Elementor's standard global `widget_render_attribute` hooks is the only path to customise output without forking the class.

## Customization Recipes

Copy-paste-ready snippets that solve common extension needs.

### Recipe 1 — Add a third button-effect style via theme CSS

The widget renders `effect-1` (top-to-bottom) and `effect-2` (left-to-right). The select control only exposes those two plus `default`. To add a third effect without touching the widget, target a wrapper class on the page and a custom modifier:

```scss
.my-cta-effect-bounce .eael-call-to-action .cta-button {
    transition: transform .2s ease;
    &:hover { transform: translateY(-3px); }
}
```

Set a Custom CSS Class of `my-cta-effect-bounce` on the parent Section in Elementor. CSS-only — no PHP changes.

### Recipe 2 — Force a fixed image background on small screens (Lite has only desktop fixed-attachment)

```scss
@media (max-width: 767px) {
    .eael-call-to-action.bg-img.bg-fixed {
        background-attachment: scroll;
    }
}
```

iOS Safari ignores `background-attachment: fixed` on most viewports — this avoids the broken-looking partial-paint on mobile.

### Recipe 3 — Strip the in-panel pro upsell entirely (multisite that already runs Lite-only)

```php
add_filter( 'eael/pro_enabled', '__return_true', 99 );
```

The widget then skips the `eael_section_pro` block in [`register_controls()`](../../includes/Elements/Cta_Box.php#L806). ⚠️ This filter is global — it suppresses upsells on **every** EA widget, not just CTA. Use only when the entire site is policy-bound to never advertise Pro.

### Recipe 4 — Replace the saved-template content with a shortcode at render time

```php
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
    if ( $widget->get_name() !== 'eael-cta-box' ) {
        return $content;
    }
    return str_replace( '{my_shortcode}', do_shortcode( '[my_shortcode]' ), $content );
}, 10, 2 );
```

The widget's content payload accepts `{my_shortcode}` as a marker which the filter rewrites server-side. Useful when the editor user shouldn't see shortcodes in the WYSIWYG but the published output should run one.

## Common Issues

### Background image doesn't appear after selecting `cta-bg-img`

- **Likely cause:** the media field has the placeholder URL but no real image was chosen, OR the popover `eael_cta_bg_image_background_manager` is set to "Default" so no `background-position` / `background-size` rule was emitted
- **Diagnose:** inspect `.eael-call-to-action.bg-img` in DevTools — is `background-image` set to a real URL? Is `background-size` `cover` or `0px`?
- **Fix:** pick a real image; if the inspector shows the URL but the image is invisible, toggle the popover to "Custom" and set `size: cover`

### Secondary button doesn't render despite "Show" being on

- **Likely cause:** `eael_cta_secondary_btn_is_show === 'yes'` is required but `eael_cta_secondary_btn_text` is empty — empty text still renders the `<a>` but with no visible label, which looks like nothing rendered
- **Diagnose:** inspect for `<a class="cta-button cta-secondary-button">` in the DOM
- **Fix:** add visible text; if the tag is missing entirely, save the panel again — Elementor occasionally drops conditional values on first save

### Multi-colour title shows the simple title text instead of the repeater chunks

- **Likely cause:** the simple `eael_cta_title` field still has text — `render()` prefers the simple field whenever it is non-empty, even with the multi-colour switch on ([line 2108](../../includes/Elements/Cta_Box.php#L2108))
- **Diagnose:** is `eael_cta_title` blank in the panel? If not, the repeater is ignored
- **Fix:** clear `eael_cta_title` to fall through to the repeater branch

### Saved-template content type renders nothing on the frontend

- **Likely cause:** the chosen template is in Draft / Pending state — `Helper::is_elementor_publish_template()` only accepts Published templates ([line 2133](../../includes/Elements/Cta_Box.php#L2133))
- **Diagnose:** open the chosen template in Elementor Library → confirm status is Publish
- **Fix:** publish the template; or pick a different one that is already published

### Icon-flex layout shows a broken-image icon instead of the chosen FA glyph

- **Likely cause:** legacy widget data with `eael_cta_flex_grid_icon` (FA4 string) was migrated to `eael_cta_flex_grid_icon_new` but the new value somehow stores `value.url` of a media upload — the render path then treats it as an `<img>` ([line 2239](../../includes/Elements/Cta_Box.php#L2239))
- **Diagnose:** open the widget JSON via Navigator → Export — does `eael_cta_flex_grid_icon_new.value` have `url` set?
- **Fix:** re-pick the icon from the Icons picker (Font Awesome library); save again

### "Background Overlay" toggle has no visible effect on solid-colour background

- **Likely cause:** the overlay control is conditional on `eael_cta_color_type != 'cta-bg-color'` — the SCSS `:after` overlay layer is only meaningful over an image background
- **Diagnose:** check `eael_cta_color_type` — switch to `cta-bg-img` and the overlay starts to act
- **Fix:** this is by design; for a colour-on-colour stack, use a section background instead

### Container `max-width` overrides Elementor section width

- **Likely cause:** `eael_cta_container_width` defaults to `yes` and applies `max-width: 1170px` directly to `.eael-call-to-action`
- **Diagnose:** inspect the root — is `max-width` set inline by the panel-driven rule?
- **Fix:** toggle "Set max width" off, or raise the slider value; alternatively wrap the widget in a Section whose `inner_max_width` matches

## Testing Checklist

Manual verification after any change to this widget. Walk through before opening a PR.

- [ ] Drop at default config — basic layout renders heading + paragraph + primary button; no PHP notices in `wp-content/debug.log`
- [ ] Switch layout to `cta-flex` — heading/body align left, button right-aligned in the same row
- [ ] Switch layout to `cta-icon-flex` — leading icon appears on the left at default `fa-bullhorn`
- [ ] Switch preset to `cta-preset-2` — root class changes; button preset control becomes visible
- [ ] Set background to `cta-bg-img` and pick an image — root receives `bg-img` class; image appears
- [ ] Toggle Fixed Image — `bg-fixed` class appears; image scrolling stops on desktop (verify on iOS Safari mobile)
- [ ] Enable Background Overlay — `eael-cta-overlay-yes` prefix class set; overlay layer renders over the image
- [ ] Enable multi-colour title and clear simple title — repeater chunks render as `<span>` inline, each with the colour or gradient picked
- [ ] Switch content type to Saved Templates — pick a published template; template content renders in the editor and on the frontend
- [ ] Pick a Draft template — render outputs no body content (known limitation, not a bug)
- [ ] Enable secondary button — second `<a>` renders with its own icon and link
- [ ] Switch primary button effect to `top-to-bottom` then `left-to-right` — `effect-1` then `effect-2` classes appear; hover animation differs
- [ ] Responsive switch — alignment differs per breakpoint; container `max-width` slider has per-breakpoint values
- [ ] Disable Pro plugin — "Go Premium" upsell section appears in the panel; activate Pro — section disappears
- [ ] Special characters (`<script>`) in title or content — output is sanitised through `Helper::eael_allowed_tags`; no XSS in editor or frontend
- [ ] RTL site — verify `.eael-call-to-action` flex layouts mirror correctly (SCSS has no RTL block; relies on Elementor's container direction)
- [ ] After source changes, run `npm run build` and visually verify the change on `http://localhost:8888`

## Architecture Decisions

### Three layouts as separate `<div>` template branches in `render()`

- **Context:** the widget supports basic, flex grid, and flex grid with icon — three structurally distinct DOM shapes.
- **Decision:** branch on `eael_cta_type` and emit one of three template literals; share heading / content / button markup as variables built earlier.
- **Alternatives rejected:** a single shared template using nested conditionals (`<?php if (…): ?>`) — harder to read with two flex variants that need an extra `<div class="icon">` child; a partial-include system — overkill for three branches.
- **Consequences:** ~50 lines of template duplication for the three branches, accepted as the most readable option.

### No widget-specific JavaScript

- **Context:** hover effects, layout switches, and visual presets could be JS- or CSS-driven.
- **Decision:** CSS-only via class-name switching from `render()`.
- **Alternatives rejected:** JS-driven hover effects (Creative Button's approach) — would require an `addAction` handler, `elementStatusCheck` guard, asset registration, and the perf cost of a script per page.
- **Consequences:** zero JS asset weight for this widget; all variants are class-keyed in SCSS; one less point of failure in popups / SPA navigation.

### Legacy FA4 icon control kept alongside the new ICONS control (icon-flex layout)

- **Context:** the icon-flex variant used a FA4 string (`eael_cta_flex_grid_icon`) before the FA5 migration; new widgets use `eael_cta_flex_grid_icon_new` (the Elementor `ICONS` control).
- **Decision:** keep both controls; in `render()`, prefer the new control whenever `__fa4_migrated` flag is set or the legacy field is empty.
- **Alternatives rejected:** a one-time DB migration replacing all `eael_cta_flex_grid_icon` values — risky on large sites; deprecating the legacy field outright — would break old saved pages.
- **Consequences:** widget data carries two icon fields forever for migrated content; `render()` has an extra branch; safe across all old saved widgets.

### Multi-colour title uses a Repeater rendered as inline `<span>` chunks

- **Context:** users want per-word colours or gradient fills inside one heading.
- **Decision:** Repeater of `{text, color, gradient}` items, rendered as space-separated `<span>` chunks inside the heading tag, with the per-item `_id` used as a `{{CURRENT_ITEM}}` selector hook for typography and gradient controls.
- **Alternatives rejected:** WYSIWYG with inline styles — fragile, hard to constrain, no typography group control; multiple separate title fields — caps at a fixed count and loses dynamic-data support.
- **Consequences:** unlimited chunks, each independently styled; consumes the simple `eael_cta_title` only when empty.

## Known Limitations

- **No widget-specific extension filter** — third-party code wanting to add a new preset, effect, or layout has no public hook and must override via CSS or fork the class. The widget could grow an `eael/cta_box/render_classes` filter if the demand emerges.
- **`eael_cta_btn_primary_icon_direction` `default` value is `left` but `toggle` is `true`** ([line 651](../../includes/Elements/Cta_Box.php#L651)) — clicking the active "Left" button deselects it, leaving the icon with no `float` rule and falling back to whatever the SCSS default is.
- **`eael_cta_btn_secondary_icon_direction` `default` is `'row'`** ([line 757](../../includes/Elements/Cta_Box.php#L757)) — `row` isn't a valid value for the `float` selector this control writes; effectively means the secondary icon has no float by default until the user picks Left or Right. Minor cosmetic glitch.
- **Saved-template content path renders nothing for Draft / Pending templates** — `Helper::is_elementor_publish_template()` filters to Published only, with no user-facing warning. A "template not published" hint in the editor would prevent silent breakage.
- **No RTL-specific SCSS** — the widget relies on Elementor's container direction inheritance. Most layouts mirror correctly, but the flex layouts' `text-align: right` on `.action` ([line 70](../../src/css/view/call-to-action.scss#L70)) is hard-coded and does not flip under `.rtl`.
- **`title_tag` allows non-heading elements** (`span`, `p`, `div`) — useful for visual hierarchy without semantic weight, but the SCSS rule `.eael-cta-heading { font-size: 36px; }` was written assuming an `<h2>`, so `<p class="title …">` may inherit unexpected paragraph margins from the theme.
- **No on-page edit affordance for non-template content** — the saved-template path wraps content in `.eael-cta-template-wrapper` for in-place editing, but the WYSIWYG path has no equivalent; users must reach into the panel to edit body copy.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
