# Tooltip Widget

> A standalone tooltip element — a trigger (icon, text, image, or shortcode output) with a styled hover-or-focus tooltip in one of four directions (left / right / top / bottom). Pure CSS animation; keyboard-focusable; built-in `aria-describedby` linking trigger to tooltip text.

**Class file:** [`includes/Elements/Tooltip.php`](../../includes/Elements/Tooltip.php)
**Slug:** `tooltip` (widget id `eael-tooltip`)
**Public docs:** <https://essential-addons.com/elementor/docs/tooltip/>
**Pro-shared:** ❌ — Lite-only widget. Standard `eael_section_pro` upsell panel is present, but no Pro-specific features exist; Pro neither subclasses nor extends. The widget emits no widget-specific filter or action hooks.

---

## Overview

Tooltip renders a single trigger (icon, plain text, image, or rendered shortcode) with a hidden tooltip text that appears on hover or keyboard focus. CSS handles both the show animation (one keyframe per direction) and the arrow indicator. Hover speed is configurable via a CSS animation duration; the four cardinal directions each have their own keyframe and pseudo-element arrow placement.

The widget is deliberately small — under 800 lines of PHP, all CSS-driven, no JavaScript. It exposes built-in accessibility (`tabindex="0"` on the trigger, `aria-describedby` linking trigger to tooltip text, `role="tooltip"` on the text span), and supports WPML media translation for both image content and uploaded SVG icons. The shortcode content type runs `do_shortcode()` on user-supplied input — useful for embedding arbitrary content as the trigger, but a context-aware security consideration.

## Features

- Four content types for the trigger: icon, text, image, shortcode
- Icon (with FA4 legacy shim) — also supports uploaded SVG with WPML translation
- Text content with selectable HTML tag (`h1`–`h6`, `div`, `span`, `p`)
- Image content with WPML media translation
- Shortcode content — runs `do_shortcode()` to render any registered shortcode as the trigger
- Optional link on the trigger (wraps content in `<a>`) — disabled for shortcode type
- Tooltip hover direction: left, right, top, bottom (each with its own CSS keyframe)
- Configurable hover speed (animation duration in milliseconds)
- Configurable tooltip max-width (responsive, px or %)
- Hover state: separate normal and hover styling tabs (background, text colour, border, box-shadow)
- Content alignment within the tooltip (left / center / right / justified)
- Built-in accessibility: `tabindex="0"`, `aria-describedby`, `role="tooltip"`
- Show on `:focus` as well as `:hover` — keyboard-only users can trigger the tooltip
- Pro upsell panel when Pro is not active

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Four content types (icon / text / image / shortcode) | ✅ | ✅ |
| Four hover directions | ✅ | ✅ |
| Optional trigger link | ✅ | ✅ |
| Normal + hover state styling | ✅ | ✅ |
| WPML media translation | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | shown | hidden |

The widget has the standard Pro upsell section ([line 532](../../includes/Elements/Tooltip.php#L532)) but no Pro-specific behaviour. The upsell is informational — there are no Lite-locked styles, no Pro hooks, no shadow `<style>` blocks. Pro neither extends nor references this widget in its codebase.

## Use Cases

- Glossary term in a blog post — hover an underlined word, see the definition
- Form field hint — "?" icon next to a field, hover for the explanation
- Image caption alternative — image trigger with a longer description in the tooltip
- Icon-only navigation help — info-icon with the page meaning in the tooltip
- Trigger a Contact Form 7 shortcode (or any registered shortcode) as the trigger — niche but supported

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Tooltip.php`](../../includes/Elements/Tooltip.php) | PHP widget class — controls, render, four content-type branches, WPML handling |
| [`src/css/view/tooltip.scss`](../../src/css/view/tooltip.scss) | Source styles — four directional keyframes, arrow pseudo-elements, hover + focus selectors |
| [`config.php`](../../config.php#L482) entry `'tooltip'` | `Asset_Builder` dependency declaration (CSS only) |
| `assets/front-end/css/view/tooltip.min.css` | Built output (do not edit) |

No widget-specific JS source or compiled file exists.

## Architecture

- **Pure CSS — no JS** — the entire widget is CSS-animated. SCSS uses `:hover` on `.eael-tooltip` and `:focus` on the trigger to show the tooltip text; four named keyframes (`eaelTooltipLeftIn`, `…RightIn`, `…TopIn`, `…BottomIn`) animate the entrance per direction. No `Asset_Builder` JS dependency.
- **Empty `content_template()` method** — [line 774](../../includes/Elements/Tooltip.php#L774) explicitly stubs `content_template() {}` to disable Elementor's JS-side preview. Editor preview falls back to the server-side `render()` via AJAX, so the four content-type branches (especially shortcode) always reflect production output. Same pattern as Feature_List.
- **Four content-type branches in `render()`** — `text`, `icon`, `image`, `shortcode`. Each emits a different inner structure for the trigger but the same outer `<div class="eael-tooltip">` and the same `<span id="tooltip-text-<id>" class="eael-tooltip-text eael-tooltip-<direction>" role="tooltip">` for the tooltip text. The `<span>` id is unique per widget instance so multiple tooltips on a page can be `aria-describedby`-linked without collision.
- **Show on `:hover` and `:focus`** — SCSS selectors at [lines 40-41](../../src/css/view/tooltip.scss#L40) combine `.eael-tooltip:hover .eael-tooltip-text` with `.eael-tooltip-content:focus + .eael-tooltip-text` (adjacent sibling). The trigger has `tabindex="0"` so keyboard users can tab to it and see the tooltip without a mouse. Real a11y feature — unusual for an EA widget.
- **Animation speed via CSS selector targeting** — the `eael_tooltip_hover_speed` control writes `animation-duration: {{SIZE}}ms` to four separate selectors (one per direction) ([lines 316-321](../../includes/Elements/Tooltip.php#L316)). The control input is a TEXT control, not a SLIDER, but the user-supplied number is interpreted as a CSS `ms` value via the `{{SIZE}}` token. Default `300ms`.
- **WPML media translation for two fields** — `eael_tooltip_img_content` (image trigger) and `eael_tooltip_icon_content_new.value.id` (uploaded SVG icon) both run through `wpml_object_id` filter; if a translated attachment exists, `wp_get_attachment_url()` re-derives the URL ([lines 731-744](../../includes/Elements/Tooltip.php#L731)).
- **Shortcode trigger runs `do_shortcode()` unsanitised** — `<?php echo do_shortcode( $settings['eael_tooltip_shortcode_content'] ); ?>` at [line 767](../../includes/Elements/Tooltip.php#L767). The user-supplied shortcode string is trusted; whatever shortcodes are registered on the site will execute. Authoring-time trust model (only the post author can edit Elementor widgets); not an XSS hole but a deliberate trust boundary.

## Render Output

The widget produces one of four content-type-specific shapes inside the same outer wrapper. Annotated below for default config (icon type, right direction); conditional elements marked `[?]`.

### Icon trigger (default)

```html
<div class="eael-tooltip">
  <span class="eael-tooltip-content" tabindex="0"
        aria-describedby="tooltip-text-<widget-id>">
    [?] <a href="…">
      <!-- legacy FA4: -->
      <i class="fas fa-home" aria-hidden="true"></i>
      <!-- OR new ICONS picker (FA5 SVG): -->
      <svg aria-hidden="true">…</svg>
      <!-- OR uploaded SVG attachment: -->
      <img class="ea-tooltip-svg-trigger" src="…" alt="…" />
    [?] </a>
  </span>
  <span id="tooltip-text-<widget-id>"
        class="eael-tooltip-text eael-tooltip-right"
        role="tooltip">
    Tooltip content
  </span>
</div>
```

### Text trigger

```html
<div class="eael-tooltip">
  <span class="eael-tooltip-content" tabindex="0"
        aria-describedby="tooltip-text-<widget-id>">
    [?] <a href="…">
      Hover Me!
    [?] </a>
  </span>
  <span id="tooltip-text-<widget-id>" class="eael-tooltip-text eael-tooltip-right" role="tooltip">…</span>
</div>
```

The trigger element tag is configurable (`span` default; can be `h1`–`h6`, `div`, `p`).

### Image trigger

```html
<div class="eael-tooltip">
  <span class="eael-tooltip-content" tabindex="0"
        aria-describedby="tooltip-text-<widget-id>">
    [?] <a href="…">
      <img src="…" alt="…">
    [?] </a>
  </span>
  <span id="tooltip-text-<widget-id>" class="eael-tooltip-text eael-tooltip-right" role="tooltip">…</span>
</div>
```

### Shortcode trigger

```html
<div class="eael-tooltip">
  <div class="eael-tooltip-content" tabindex="0"
       aria-describedby="tooltip-text-<widget-id>">
    {output of do_shortcode([the-shortcode])}
  </div>
  <span id="tooltip-text-<widget-id>" class="eael-tooltip-text eael-tooltip-right" role="tooltip">…</span>
</div>
```

⚠️ Link is **not** rendered for shortcode triggers — the `eael_tooltip_enable_link` control is hidden when content type is shortcode. Trigger element is `<div>`, not `<span>`.

Notes:

- `.eael-tooltip` is the styling root and `:hover` target. `.eael-tooltip-content` is the keyboard-focus target.
- The tooltip text span has `id="tooltip-text-<widget-id>"` matching `aria-describedby` on the trigger — unique per widget instance.
- Direction class on the tooltip text (`eael-tooltip-left`, `-right`, `-top`, `-bottom`) selects which keyframe animation applies and where the arrow `::after` pseudo-element sits.
- `role="tooltip"` on the text span signals to screen readers that this is a tooltip (not a generic element).

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Tooltip.php#L65) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_tooltip_type` | CHOOSE | `icon` | Content → Content Settings | `render()` branch — icon / text / image / shortcode |
| `eael_tooltip_icon_content_new` | ICONS | `fas fa-home` | Content → Content Settings | Icon glyph (FA4 legacy via `eael_tooltip_icon_content`) — icon type only |
| `eael_tooltip_icon_size` | SLIDER (responsive) | `60` | Content → Content Settings | `font-size` on `<i>`, `height/width` on `<svg>` and `.ea-tooltip-svg-trigger` |
| `eael_tooltip_content` | WYSIWYG (dynamic) | `"Hover Me!"` | Content → Content Settings | Trigger inner HTML (text type only) |
| `eael_tooltip_content_tag` | SELECT | `span` | Content → Content Settings | Trigger HTML element (text type only) |
| `eael_tooltip_img_content` | MEDIA | placeholder | Content → Content Settings | Trigger `<img>` (image type only) |
| `eael_tooltip_shortcode_content` | TEXTAREA | `"[shortcode-here]"` | Content → Content Settings | Trigger HTML via `do_shortcode()` (shortcode type only) |
| `eael_tooltip_content_alignment` | CHOOSE (responsive) | `left` | Content → Content Settings | `prefix_class` `eael-tooltip-align…` on widget wrapper |
| `eael_tooltip_enable_link` | SWITCHER | off | Content → Content Settings | Wraps trigger in `<a>` (not available for shortcode) |
| `eael_tooltip_link` | URL (dynamic) | empty | Content → Content Settings | Trigger link target (conditional on enable switch) |
| `eael_tooltip_hover_content` | WYSIWYG (dynamic) | `"Tooltip content"` | Content → Tooltip Settings | Inside `<span class="eael-tooltip-text">` |
| `eael_tooltip_hover_dir` | SELECT | `right` | Content → Tooltip Settings | Direction class (`eael-tooltip-left/right/top/bottom`) |
| `eael_tooltip_hover_speed` | TEXT | `"300"` | Content → Tooltip Settings | `animation-duration` in ms for all four direction keyframes |
| `eael_tooltip_max_width` | SLIDER (responsive) | `100px` | Style → Content Style | `width` on `.eael-tooltip` |
| `eael_tooltip_content_padding` | DIMENSIONS (responsive) | empty | Style → Content Style | `padding` on `.eael-tooltip` |
| `eael_tooltip_content_margin` | DIMENSIONS (responsive) | empty | Style → Content Style | `margin` on `.eael-tooltip` |
| `eael_tooltip_text_alignment` | CHOOSE (responsive) | `left` | Style → Content Style | `prefix_class` `eael-tooltip-text-align-` (text type only) |
| `eael_tooltip_content_bg_color` / `_color` | COLOR | empty / empty | Style → Content Style (Normal) | `.eael-tooltip` bg, text/svg/link colour |
| `eael_tooltip_content_hover_bg_color` / `_hover_color` | COLOR | empty / `#212121` | Style → Content Style (Hover) | `.eael-tooltip:hover` bg, text/svg/link colour |
| `eael_tooltip_shadow` / `_hover_shadow` | GROUP_BOX_SHADOW | various | Style → Content Style | Normal + hover box-shadow |
| `eael_tooltip_border` / `_hover_border` | GROUP_BORDER | various | Style → Content Style | Normal + hover border |
| `eael_tooltip_content_typography` | GROUP_TYPOGRAPHY | various | Style → Content Style | Typography on `.eael-tooltip` |
| `eael_tooltip_content_radius` | DIMENSIONS (responsive) | empty | Style → Content Style | `border-radius` on `.eael-tooltip` |

Plus a similar "Tooltip Style" section (further down) for the hover tooltip text styling.

## Conditional Dependencies

```text
eael_tooltip_icon_content_new   → visible when eael_tooltip_type == 'icon'
eael_tooltip_icon_size          → visible when eael_tooltip_type == 'icon'
eael_tooltip_content            → visible when eael_tooltip_type == 'text'
eael_tooltip_content_tag        → visible when eael_tooltip_type == 'text'
eael_tooltip_img_content        → visible when eael_tooltip_type == 'image'
eael_tooltip_shortcode_content  → visible when eael_tooltip_type == 'shortcode'

eael_tooltip_enable_link        → visible when eael_tooltip_type != 'shortcode'
eael_tooltip_link               → visible when eael_tooltip_enable_link == 'yes'

eael_tooltip_text_alignment     → visible when eael_tooltip_type == 'text'

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

## Behavior Flow

1. User drops the widget → `register_controls()` runs. No filter / action hooks are emitted.
2. User configures content type and the corresponding content field. For shortcode type, the user types a shortcode string (e.g. `[contact-form-7 id="42"]`).
3. User picks tooltip direction, content alignment, hover speed (animation duration), and per-state styling.
4. Editor preview re-renders via [`render()`](../../includes/Elements/Tooltip.php#L723) (the empty `content_template()` forces server-side preview).
5. `render()` computes the FA4 / FA5 migration flag for the icon picker, runs `add_link_attributes` for the optional link, and runs `wpml_object_id` for image / SVG attachments.
6. The render branches on `eael_tooltip_type` — emits one of four trigger structures inside the outer `<div class="eael-tooltip">`.
7. After the trigger, emits the `<span class="eael-tooltip-text eael-tooltip-<direction>">` with `wp_kses`-sanitised hover content; for shortcode triggers, `do_shortcode()` runs and its raw output is echoed.
8. Browser receives static HTML. CSS handles all interactivity.
9. Hover the `.eael-tooltip` (or focus its trigger via keyboard) → SCSS rule activates the matching `:hover` / `:focus` selector → `opacity` and `visibility` flip; the named keyframe animation runs for the chosen direction.
10. Default animation runs at 300 ms; the hover-speed control overrides via per-selector `animation-duration` CSS rules.

## JavaScript Lifecycle

N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`, and explicitly stubs `content_template()` to disable Elementor's JS-side preview.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one Tooltip widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `tooltip.min.css` | self (built from `src/css/view/tooltip.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| _(none)_ | — | Widget is pure CSS — no JS asset registered for this slug | — |

Font Awesome glyphs render via `Icons_Manager::render_icon`, which depends on Elementor's `font-awesome-5-all` handle Elementor provides.

## Hooks & Filters

The widget emits **no widget-specific filter or action hooks**. It only consumes core hooks for compatibility.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the upsell section ([line 532](../../includes/Elements/Tooltip.php#L532)) |
| `wpml_object_id` | filter (consumed) | `int $object_id, string $type, bool $return_original` | Translates image / SVG icon attachment IDs ([lines 733, 740](../../includes/Elements/Tooltip.php#L733)) |

No `eael/tooltip/*` extension hook exists. Pro does not extend this widget; theme customisation is via CSS overrides against the documented classes.

## Customization Recipes

### Recipe 1 — Add a custom direction (e.g. top-left diagonal)

```scss
.eael-tooltip .eael-tooltip-text.eael-tooltip-top-left {
    top: auto;
    bottom: 100%;
    right: 50%;
    transform: translate(50%, -10px);
    opacity: 0;
    visibility: hidden;
}
.eael-tooltip:hover .eael-tooltip-text.eael-tooltip-top-left,
.eael-tooltip-content:focus + .eael-tooltip-text.eael-tooltip-top-left {
    animation: eaelTooltipTopLeftIn 300ms ease-in-out;
    opacity: 1;
    visibility: visible;
}
@keyframes eaelTooltipTopLeftIn {
    from { transform: translate(50%, 10px); opacity: 0; }
    to   { transform: translate(50%, -10px); opacity: 1; }
}
```

```php
add_action( 'elementor/element/before_section_end', function ( $element, $section_id ) {
    if ( $element->get_name() !== 'eael-tooltip' || $section_id !== 'eael_section_tooltip_hover_content_settings' ) {
        return;
    }
    $element->update_control( 'eael_tooltip_hover_dir', [
        'options' => [
            'left'      => __( 'Left', 'my-theme' ),
            'right'     => __( 'Right', 'my-theme' ),
            'top'       => __( 'Top', 'my-theme' ),
            'bottom'    => __( 'Bottom', 'my-theme' ),
            'top-left'  => __( 'Top Left', 'my-theme' ),
        ],
    ] );
}, 10, 2 );
```

The widget writes the chosen value as `eael-tooltip-<direction>` class on the tooltip text span without validation; any SCSS rule matching that class will fire.

### Recipe 2 — Force keyboard focus to show the tooltip with a longer delay

```scss
.eael-tooltip-content:focus + .eael-tooltip-text {
    animation-duration: 800ms !important;
    animation-delay: 200ms;
}
```

Keyboard users get a slightly slower reveal so the tooltip is less jarring when tabbing through a page with many tooltips. Mouse hover keeps its native (control-driven) duration.

### Recipe 3 — Globally hide all tooltips for print

```scss
@media print {
    .eael-tooltip .eael-tooltip-text {
        display: none !important;
    }
}
```

Tooltips clutter printed pages; this hides them while keeping the trigger visible.

### Recipe 4 — Override the shortcode output before `do_shortcode` runs

```php
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
    if ( $widget->get_name() !== 'eael-tooltip' ) {
        return $content;
    }
    $settings = $widget->get_settings_for_display();
    if ( $settings['eael_tooltip_type'] === 'shortcode' ) {
        // Inspect the rendered shortcode output here
        if ( str_contains( $content, '[malicious-pattern]' ) ) {
            return str_replace( '[malicious-pattern]', '', $content );
        }
    }
    return $content;
}, 10, 2 );
```

⚠️ The widget runs `do_shortcode()` on user-supplied input without sanitisation. If your site has untrusted authors who can edit Elementor widgets, audit the registered shortcodes — any shortcode they can name will execute. The widget's trust model is authoring-time; the filter above is a post-render hook for defence-in-depth.

## Common Issues

### Tooltip doesn't appear on hover

- **Likely cause:** the tooltip text content is empty — the `<span class="eael-tooltip-text">` renders but with nothing inside; SCSS still toggles it visible but it has zero dimensions
- **Diagnose:** inspect the tooltip span — is `innerHTML` empty? Set a real tooltip content in the panel
- **Fix:** populate `eael_tooltip_hover_content` (Content → Tooltip Settings)

### Hover speed value has no effect

- **Likely cause:** the speed control is a TEXT input, not a SLIDER. The user-supplied number is interpreted as `ms` via the `{{SIZE}}` CSS token. If the user typed `300ms` literally, the resulting CSS becomes `animation-duration: 300msms;` which is invalid and ignored
- **Diagnose:** inspect the inline styles — does the value have `ms` appended twice?
- **Fix:** type only the number (e.g. `500`), not the unit. The widget appends `ms` automatically

### Tooltip animation runs every time the trigger is focused, not just first time

- **Likely cause:** the `:hover` and `:focus` selectors both apply the animation independently. Tabbing back to a focused trigger re-runs the animation
- **Diagnose:** by design — the animation rule applies whenever the selector matches
- **Fix:** add `animation: none !important;` for `:focus` if the keyboard-replay is unwanted; or override via the recipe in Recipe 2

### Shortcode trigger shows the raw `[shortcode-name]` string instead of the rendered output

- **Likely cause:** the shortcode is not registered, or registered with a different tag name
- **Diagnose:** check the shortcode tag via `shortcode_exists( 'shortcode-name' )`
- **Fix:** ensure the plugin or theme that registers the shortcode is active and the tag name matches exactly

### Tooltip text has no styling

- **Likely cause:** the Style → Tooltip Style section was not configured — the tooltip text inherits default browser styling and may be invisible against the page background
- **Diagnose:** inspect `.eael-tooltip-text` computed styles
- **Fix:** set background colour, text colour, padding, and border-radius on the tooltip text section

### Tooltip extends past the viewport edge (clipped)

- **Likely cause:** the trigger is near the right or bottom edge of the page; `eael-tooltip-right` direction pushes the tooltip beyond viewport
- **Diagnose:** scroll the page horizontally — is the tooltip cut off?
- **Fix:** pick a different direction (e.g. `left` for triggers on the right side); or wrap the widget in a container with `overflow: visible` and adjust positioning via theme CSS

### Multiple tooltips on the same page show wrong `aria-describedby` linkage

- **Likely cause:** the widget id is reused across page reload (Elementor caches IDs, very rare in practice)
- **Diagnose:** inspect each tooltip — does `aria-describedby` value match the adjacent `id`?
- **Fix:** in normal use, Elementor IDs are 7-character random strings and unique. If reproduced, regenerate the page

### Tooltip overlay covers form field below it

- **Likely cause:** the tooltip text has `z-index` insufficient to stack above adjacent elements
- **Diagnose:** in DevTools inspect the stacking context — what is the computed `z-index` of `.eael-tooltip-text`?
- **Fix:** override via theme CSS — `.eael-tooltip .eael-tooltip-text { z-index: 9999; }`

### Empty `content_template()` causes editor preview to feel slow

- **Likely cause:** every settings change triggers a server round-trip — the JS-side template is intentionally disabled
- **Diagnose:** Network tab in the editor shows `wp-admin/admin-ajax.php` calls on each change
- **Fix:** by design — server-side preview matches production exactly, especially for shortcode triggers where the JS template couldn't reproduce `do_shortcode()` output

## Testing Checklist

- [ ] Drop at default — icon trigger with `fa-home` glyph; "Tooltip content" appears on hover to the right
- [ ] Tab to the trigger via keyboard — tooltip appears on focus; matches hover behaviour
- [ ] Switch content type to Text — trigger renders the WYSIWYG content with the chosen HTML tag
- [ ] Switch to Image — trigger renders `<img>`; WPML translates the attachment if a translated version exists
- [ ] Switch to Shortcode — type a registered shortcode; trigger renders the shortcode output via `do_shortcode()`
- [ ] Enable Link on non-shortcode types — trigger wraps in `<a href="…">`; link is not available for shortcode type
- [ ] Switch hover direction to each of left / right / top / bottom — direction class updates; matching keyframe animation fires
- [ ] Set hover speed `500` (no unit) — `animation-duration: 500ms` applied to all four direction selectors
- [ ] Set hover speed `500ms` (with unit, common mistake) — CSS becomes `500msms`, animation invalid (known limitation)
- [ ] Configure both normal and hover background / text colour — both states render the corresponding colours
- [ ] Multiple tooltips on the same page — each has unique `id="tooltip-text-<widget-id>"` and `aria-describedby` linkage
- [ ] FA4 legacy icon (`eael_tooltip_icon_content` non-empty + `__fa4_migrated` flag) — render picks the new picker; `:hover` works
- [ ] Uploaded SVG icon (icon type with SVG URL) — renders as `<img class="ea-tooltip-svg-trigger">`
- [ ] Long tooltip content — exceeds `max-width`; wraps within the configured width
- [ ] Special characters (`<script>`) in tooltip text — sanitised via `Helper::eael_allowed_tags`; no XSS
- [ ] Maliciously-named shortcode in shortcode type — `do_shortcode()` runs; verify the shortcode is registered before allowing untrusted authors to edit
- [ ] Pro upsell section in panel appears when Pro is inactive; hidden when Pro is active
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Built-in keyboard accessibility (`:focus` triggers tooltip)

- **Context:** tooltips that only appear on `:hover` are inaccessible to keyboard-only users — a known a11y failure mode.
- **Decision:** SCSS selectors combine `:hover` on `.eael-tooltip` with `:focus` on `.eael-tooltip-content` (adjacent sibling). Trigger gets `tabindex="0"`. Hover and focus produce the same animation.
- **Alternatives rejected:** hover only — WCAG-failing; JS-driven focus handling — adds JS dependency for what CSS handles natively.
- **Consequences:** keyboard users get the same experience as mouse users. Slight ARIA verbosity (`aria-describedby` linking is required for the focus path to work meaningfully with screen readers).

### `do_shortcode()` on user-supplied input

- **Context:** the shortcode content type is the only way to render arbitrary HTML/PHP-generated content as the trigger (e.g. a Contact Form 7 button as a trigger).
- **Decision:** `echo do_shortcode( $settings['eael_tooltip_shortcode_content'] );` ([line 767](../../includes/Elements/Tooltip.php#L767)) without sanitisation.
- **Alternatives rejected:** `wp_kses` on the result — breaks shortcode HTML; restrict to a registered allowlist — limits user flexibility.
- **Consequences:** authoring-time trust model — only users who can edit Elementor widgets can name a shortcode; shortcode authors trust the shortcode implementations on their site. Not an XSS hole unless the shortcode itself is vulnerable.

### Empty `content_template()` disables JS preview

- **Context:** the four content-type branches are non-trivial; shortcode preview specifically cannot be reproduced JS-side.
- **Decision:** stub `content_template() {}` to force server-side preview via AJAX.
- **Alternatives rejected:** implement a JS template for text / icon / image and skip shortcode — gives a misleading preview that doesn't match production.
- **Consequences:** editor responsiveness is slightly slower; every preview matches production output exactly.

### Animation speed via per-selector CSS rule (not CSS variable)

- **Context:** the user wants to configure hover speed for all four directions with a single control value.
- **Decision:** the `eael_tooltip_hover_speed` control writes to four separate selectors at render time ([lines 316-321](../../includes/Elements/Tooltip.php#L316)), one per direction.
- **Alternatives rejected:** CSS variable `--tooltip-duration` set on the wrapper — works but requires browser support for the variable; deduplicating selectors via a shared class — would require restructuring the keyframes.
- **Consequences:** four CSS rule emissions per widget instance; small overhead but works in all browsers.

### Standard `eael_section_pro` upsell despite no Pro features

- **Context:** the widget has no Pro-specific behaviour, but the panel UI is consistent if every widget shows a "Go Premium" section when Pro is not active.
- **Decision:** keep the upsell section purely for UX consistency with other Display widgets (Cta_Box, Info_Box, Flip_Box, Pricing_Table).
- **Alternatives rejected:** omit the upsell (like Image Accordion / Feature List) — inconsistent panel surface.
- **Consequences:** an upsell that doesn't advertise any specific Pro feature — generic "more stunning elements" text.

## Known Limitations

- **`eael_tooltip_hover_speed` is a TEXT control, not a SLIDER** — users who type `300ms` (with unit) get `300msms` in the rendered CSS, breaking the animation. Documented intent (the widget appends `ms` via `{{SIZE}}` token); could be improved with a SLIDER and `size_units: ['ms']`.
- **Empty `content_template()` makes editor preview slower** — every settings change triggers an AJAX round-trip. Intentional trade-off for shortcode preview accuracy.
- **No `eael/tooltip/*` extension hook** — third-party customisation must rely on CSS overrides; no way to inject custom directions or content types without a fork.
- **`do_shortcode()` on user-supplied input** — authoring-time trust; not vulnerable to direct XSS but trusts the registered shortcodes on the site.
- **Tooltip can extend past viewport edge** — no automatic flip/repositioning like Tooltipster offers. User must pick a direction that fits the page layout.
- **`aria-describedby` linkage only works when the tooltip text has stable content** — dynamic content via Elementor dynamic tags is fine; user-modified content via JS would break the linkage.
- **Shortcode type cannot be linked** — the `eael_tooltip_enable_link` control is hidden for shortcode type, presumably because wrapping a shortcode's HTML in `<a>` could double-link an `<a>` the shortcode itself rendered.
- **WPML media translation handles attachment ID swap but not URL re-derivation for uploaded SVG case** — line 743 re-derives URL via `wp_get_attachment_url()` for the SVG path, but if the translated attachment doesn't exist, the URL silently uses the original.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
