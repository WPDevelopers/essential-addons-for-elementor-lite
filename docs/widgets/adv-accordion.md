# Advanced Accordion Widget

> A collapsible/togglable list of titled panels with two Lite layouts (`accordion`, `toggle`), a Pro-only `accordion_media` layout injected via two `do_action` hooks, deep-link via URL hash, optional FAQ schema emission, and a Repeater-driven custom content source plus a `WP_Query`-driven dynamic content source.

**Class file:** [`includes/Elements/Adv_Accordion.php`](../../includes/Elements/Adv_Accordion.php)
**Slug:** `adv-accordion` (widget id `eael-adv-accordion`)
**Public docs:** <https://essential-addons.com/elementor/docs/advanced-accordion/>
**Pro-shared:** ✅ Yes — Pro injects the `accordion_media` layout (image/video alongside content) via `do_action('eael_adv_accordion_media_type_controls', $this)` (controls injection during `register_controls`) and `do_action('eael_adv_accordion_media_type', $settings, $this)` (render injection inside `render`). No Liquid Glass hooks.

---

## Overview

Advanced Accordion is one of EA's oldest and most-used Lite widgets. It renders a vertical list of headers; clicking a header slides the matching content panel up/down via jQuery `slideToggle`. Two interaction modes ship in Lite: `accordion` (only one panel open at a time, others auto-close) and `toggle` (each panel independent). Each tab has a Repeater entry with title, optional per-tab open/closed icons, and either inline WYSIWYG content or a Saved Template. A second content source `dynamic` runs a `WP_Query` and renders post titles + excerpts/full-content as tabs. URL hash deep-linking opens a tab whose Custom ID matches the hash on page load. Optional JSON-LD FAQ schema is emitted for SEO when the toggle is on.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| `accordion` and `toggle` layouts | ✅ | ✅ |
| `accordion_media` layout (image/video next to content) | ❌ — picker shows the option but a "Only Available in Pro" notice + control conditions hide all related controls | ✅ via `eael_adv_accordion_media_type_controls` + `eael_adv_accordion_media_type` |
| Repeater per-tab content (title + WYSIWYG / Saved Template) | ✅ | ✅ |
| Dynamic content source (`WP_Query`) | ✅ | ✅ |
| URL hash deep-link, scroll-on-click, custom ID per tab | ✅ | ✅ |
| FAQ JSON-LD schema emission | ✅ | ✅ |
| FA4 → FA5 icon migration shim (toggle icon + per-tab open/closed icons) | ✅ — see [`_patterns.md § FA4`](_patterns.md#fa4--fa5-icon-migration-shim) | ✅ |
| WPML translation for Saved Template id | ✅ — see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation) | ✅ |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Adv_Accordion.php`](../../includes/Elements/Adv_Accordion.php) | PHP widget class — controls (1996 lines), `render()`, `render_dynamic_content()`, `print_toggle_icon()` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `eael_validate_html_tag()`, `eael_allowed_tags()`, `set_eael_advanced_accordion_faq()`, `is_elementor_publish_template()`, `eael_onpage_edit_template_markup()`, `eael_e_optimized_markup()` |
| [`src/css/view/advanced-accordion.scss`](../../src/css/view/advanced-accordion.scss) | Source styles — header, content, icon visibility (closed/opened), RTL caret rotation |
| [`src/js/view/advanced-accordion.js`](../../src/js/view/advanced-accordion.js) | Frontend logic (102 lines) — slide animation, hash deep-link, scroll-on-click, keyboard a11y |
| [`config.php`](../../config.php#L495) entry `'adv-accordion'` | `Asset_Builder` dependency declaration: `advanced-accordion.min.css` + `advanced-accordion.min.js` |
| `assets/admin/images/layout-previews/accordion-*.png` | Image-picker thumbnails for the layout chooser (default, toggle, media) |
| `assets/front-end/css/view/advanced-accordion.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/advanced-accordion.min.js` | Built output (do not edit) |

## Architecture

- **Two-hook Pro layout extension is NOT Liquid Glass** — Pro adds the `accordion_media` layout via two hooks: `do_action('eael_adv_accordion_media_type_controls', $this)` at [line 122](../../includes/Elements/Adv_Accordion.php#L122) (Pro injects its own controls section into Lite's `register_controls`) and `do_action('eael_adv_accordion_media_type', $settings, $this)` at [line 1719](../../includes/Elements/Adv_Accordion.php#L1719) (Pro short-circuits Lite's render and emits its own DOM). The whole `else { … standard render … }` branch only runs for non-media layouts. Layout option list itself is filterable via `apply_filters('eael_adv_accordion_styles', …)` at [line 144](../../includes/Elements/Adv_Accordion.php#L144) — Pro can add more entries and gate them with the `'conditions'` array (controls auto-hide via `condition` rule referencing it). When Pro is inactive, the `accordion_media` option still appears in the picker but a `eael_adv_accordion_type_pro_alert` heading control reads "Only Available in Pro Version" and every Lite control hides via `'eael_adv_accordion_type!' => 'accordion_media'`.
- **Two render paths driven by `eael_adv_accordion_content_source`** — `custom` runs `render()`'s Repeater loop (titles + WYSIWYG / Saved Templates); `dynamic` calls `render_dynamic_content()` at [line 1867](../../includes/Elements/Adv_Accordion.php#L1867) which builds args via `Helper::get_query_args($settings)` and renders post title + excerpt or full Elementor template content. Dynamic mode uses a single open/closed icon pair from the widget settings rather than per-Repeater-item icons.
- **`is_dynamic_content()` disables Elementor's render cache** when any tab uses a Saved Template OR when FAQ schema is enabled ([line 77-104](../../includes/Elements/Adv_Accordion.php#L77)). This bypasses Elementor's CSS file caching so saved-template content always reflects the latest template version.
- **Internal `eael/controls/query` action injects shared Query controls** at [line 680](../../includes/Elements/Adv_Accordion.php#L680). Same pattern as Post_Grid — Bootstrap registers the listener inside Lite, not Pro. Drives the `dynamic` content source.
- **Hash-based deep-link with a "safari" rewrite** — JS reads `window.location.hash`, opens any tab whose `id` matches it. A literal hash of `safari` is rewritten to `eael-safari` in BOTH PHP ([line 1751](../../includes/Elements/Adv_Accordion.php#L1751)) and JS ([line 6](../../src/js/view/advanced-accordion.js#L6)) — the string `safari` collides with WordPress / browser-detection JS that uses it as a body class. Custom ID is sanitized via `Helper::str_to_css_id($title)` when not explicitly provided.
- **Race-condition guard via `triggered` class** — every header click adds a `triggered` class 50ms after firing and removes it from all headers 70ms later ([JS line 51-92](../../src/js/view/advanced-accordion.js#L51)). Re-clicking within 70ms returns early. Workaround for double-click flicker on slow machines.
- **Modern markup toggle** — `has_widget_inner_wrapper()` returns `! Helper::eael_e_optimized_markup()` ([line 106](../../includes/Elements/Adv_Accordion.php#L106)). When optimised markup is on (Elementor 3.6+), the widget's outer `<div class="elementor-widget-container">` is skipped; selectors that target it via `{{WRAPPER}}` still work because `{{WRAPPER}}` is the widget root, not the inner container.
- **`eael/advanced_accordion/allow_dangerous_html` filter** ([line 1824](../../includes/Elements/Adv_Accordion.php#L1824)) bypasses `wp_kses` on Repeater tab content. Default off; flipping it on accepts raw HTML in tab WYSIWYG (use only when the editor is fully trusted).

## Render Output

```html
<div class="eael-adv-accordion"
     id="eael-adv-accordion-<widget-id>"
     data-accordion-id="<widget-id>"
     data-accordion-type="accordion"   [or "toggle"]
     data-toogle-speed="300"            [⚠ typo: "toogle" not "toggle"]
     data-scroll-on-click="no"          [or "yes"]
     data-scroll-speed="300"
     [?] data-custom-id-offset="0">    [present only when value > 0]

  <div class="eael-accordion-list">
    <div id="<custom-id-or-slugified-title>"
         class="elementor-tab-title eael-accordion-header
                [active-default]"      [class added when tab.default_active == 'yes']
         tabindex="0"
         data-tab="1"
         aria-controls="elementor-tab-content-<id-int><n>">

      [?] {toggle icon when position == ''} <i class="…fa-toggle"></i>
      [?] {tab title when icon position == ''} <span class="eael-accordion-tab-title">Title</span>
      [?] {per-tab icons when tab.icon_show == 'yes'}
          <span class="eael-advanced-accordion-icon-closed"><i class="…fa-accordion-icon"></i></span>
          <span class="eael-advanced-accordion-icon-opened"><i class="…fa-accordion-icon"></i></span>
      [?] {tab title when icon position == 'right' or null}
          <span class="eael-accordion-tab-title">Title</span>
      [?] {toggle icon when position == 'right'}
    </div>

    <div id="elementor-tab-content-<id-int><n>"
         class="eael-accordion-content clearfix [active-default]"
         data-tab="1"
         aria-labelledby="<custom-id-or-slugified-title>">
      {WYSIWYG content OR Saved Template render OR get_the_excerpt()}
    </div>
  </div>
  …
</div>

[?] <!-- FAQ JSON-LD emitted into <head> via Helper::set_eael_advanced_accordion_faq() when faq_schema_show == 'yes' -->
```

Notes:

- `data-toogle-speed` and `eael_adv_accordion_toggle_icon_postion` are misspelled but stable — renaming would break saved widget data.
- JS reads from the `.eael-adv-accordion` outer element, not the per-tab `.eael-accordion-header`. `accordion-type`, `toogle-speed`, `scroll-on-click`, etc. are widget-wide settings.
- Per-tab open/closed icon visibility is pure CSS — `.active .eael-advanced-accordion-icon-closed { display: none }` and `.active .eael-advanced-accordion-icon-opened { display: block }` ([SCSS line 39-46](../../src/css/view/advanced-accordion.scss#L39)).
- `role="tabpanel"` is commented out ([line 1765 and 1903](../../includes/Elements/Adv_Accordion.php#L1765)) — `aria-controls` / `aria-labelledby` exist but the panel doesn't declare its role. Accessibility gap.
- `aria-expanded` is never written to the header. Headers should toggle this on open/close to mirror the visible state for assistive tech.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Adv_Accordion.php#L115) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_adv_accordion_type` | CHOOSE (image picker) | `accordion` | Content → General Settings | Top-level layout switch; gates almost every other control |
| `eael_adv_accordion_title_tag` | SELECT | `span` | Content → General Settings | Title tag (`h1`–`h6`, `span`, `p`, `div`); validated via `Helper::eael_validate_html_tag()` |
| `eael_accordion_media_custom_animation` / `_duration` | SELECT / SLIDER | `''` / `0.6s` | Content → General Settings | Pro-only; condition gates them to `accordion_media` |
| `eael_adv_accordion_icon_show` | SWITCHER | `yes` | Content → General Settings | Toggles right-side caret entirely |
| `eael_adv_accordion_toggle_icon_postion` | SWITCHER | `right` | Content → General Settings | `left` (`''` value) vs `right`; PHP swaps title/icon order. ⚠ Typo: `postion` not `position` |
| `eael_adv_accordion_icon_new` (+ FA4 shim `eael_adv_accordion_icon`) | ICONS | `fas fa-angle-right` | Content → General Settings | Toggle caret icon; SVG library handled |
| `eael_adv_accordion_toggle_speed` | NUMBER (ms) | `300` | Content → General Settings | `data-toogle-speed`; jQuery `slideToggle` duration |
| `eael_adv_accordion_custom_id_offset` | NUMBER (px) | `0` | Content → General Settings | Subtracted from scroll target on hash deep-link |
| `eael_adv_accordion_scroll_speed` | NUMBER (ms) | `300` | Content → General Settings | `$('html, body').animate({…}, $srollSpeed)` ⚠ `srollSpeed` typo in JS |
| `eael_adv_accordion_scroll_onclick` | SWITCHER | `no` | Content → General Settings | If `yes`, animate scroll to opened tab |
| `eael_adv_accordion_faq_schema_show` | SWITCHER | `no` | Content → General Settings | If `yes`, emit JSON-LD `FAQPage` via `Helper::set_eael_advanced_accordion_faq()` |
| `eael_adv_accordion_content_source` | CHOOSE | `custom` | Content → Content Settings | `custom` (Repeater) vs `dynamic` (WP_Query) |
| `eael_adv_accordion_show_full_content` | SWITCHER | `no` | Content → Content Settings | Dynamic mode only — full content vs excerpt |
| `eael_adv_accordion_open_icon` / `_close_icon` | ICONS | `fas fa-minus` / `fas fa-plus` | Content → Content Settings | Dynamic-mode-only single icon pair (Repeater has its own per-tab icons) |
| `eael_adv_accordion_tab` | REPEATER | 3 default rows | Content → Content Settings | Per-tab settings (see sub-table below) |
| Content → Query | various | — | Content → Content Settings (when `dynamic`) | post_type / taxonomy / orderby — **injected via `do_action('eael/controls/query', $this)`** at [line 680](../../includes/Elements/Adv_Accordion.php#L680) |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| Style → General / Tab / Tab Content / Caret | various | — | Style tab | Padding, border, border-radius, box-shadow, background per state, typography, caret colour/size |

### Per-item Repeater controls (`eael_adv_accordion_tab`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_adv_accordion_tab_default_active` | SWITCHER | `no` | Adds `active-default` class — JS opens this tab on init |
| `eael_adv_accordion_tab_icon_show` | SWITCHER | `yes` | Toggles per-tab open/closed icon pair |
| `eael_adv_accordion_tab_title_icon_new_opened` (+ FA4 shim) | ICONS | `fas fa-minus` | Icon shown when tab is open |
| `eael_adv_accordion_tab_title_icon_new` (+ FA4 shim `eael_adv_accordion_tab_title_icon`) | ICONS | `fas fa-plus` | Icon shown when tab is closed |
| `eael_adv_accordion_tab_title` | TEXT (dynamic, AI) | `Tab Title` | Header text; passed through `wp_kses(Helper::eael_allowed_tags())` |
| `eael_adv_accordion_text_type` | SELECT | `content` | `content` (WYSIWYG) vs `template` (Saved Template) |
| `eael_primary_templates` | `eael-select2` | empty | Saved Template post id; runs through `wpml_object_id` filter |
| `eael_adv_accordion_tab_content` | WYSIWYG (dynamic) | placeholder Lorem | Tab body when `text_type == 'content'`; `wp_kses` unless `eael/advanced_accordion/allow_dangerous_html` is true |
| `eael_adv_accordion_tab_id` | TEXT (AI) | empty | Custom anchor id for hash deep-link; falls back to slugified title |
| `eael_adv_accordion_tab_faq_schema_text` | TEXT (dynamic, AI) | empty | FAQ schema answer when `text_type == 'template'` (template content can't be auto-extracted) |

## Conditional Dependencies

```text
# Layout-driven (hide nearly everything when Pro-only layout selected without Pro)
eael_adv_accordion_type_pro_alert      → visible when eael_adv_accordion_type in pro layouts
                                         (Pro overrides via 'eael_adv_accordion_styles' filter)
eael_accordion_media_custom_animation  → visible when eael_adv_accordion_type == 'accordion_media'
eael_accordion_media_custom_animation_duration → same
eael_adv_accordion_icon_show           → visible when eael_adv_accordion_type != 'accordion_media'
eael_adv_accordion_toggle_icon_postion → visible when icon_show == 'yes' AND type != 'accordion_media'
eael_adv_accordion_icon_new            → same
eael_adv_accordion_toggle_speed        → visible when type != 'accordion_media'
eael_adv_accordion_custom_id_offset    → same
eael_adv_accordion_scroll_speed        → same
eael_adv_accordion_scroll_onclick      → same
eael_section_adv_accordion_content_settings (whole section) → visible when type != 'accordion_media'

# Content-source driven
eael_adv_accordion_show_full_content   → visible when content_source == 'dynamic'
eael_adv_accordion_icon_tabs (open/closed icon pair) → same
eael_adv_accordion_tab (Repeater)      → visible when content_source == 'custom' AND type != 'accordion_media'

# Per-tab Repeater
eael_adv_accordion_tab_title_icon_new(_opened) → visible when tab_icon_show == 'yes'
eael_primary_templates                 → visible when text_type == 'template'
eael_adv_accordion_tab_content         → visible when text_type == 'content'
eael_adv_accordion_tab_faq_schema_text → visible when text_type == 'template'

# Pro upsell
eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_adv_accordion_media_type_controls` | action (emitted) | `(Adv_Accordion $widget)` | **Pro extension** — injects the `accordion_media` controls section during `register_controls`. ⚠ Un-prefixed (no `eael/`); legacy. |
| `eael_adv_accordion_media_type` | action (emitted) | `(array $settings, Adv_Accordion $widget)` | **Pro extension** — Pro emits its own DOM in `render()` and Lite skips the standard branch. ⚠ Un-prefixed; legacy. |
| `eael_adv_accordion_styles` | filter (emitted) | `array { styles, conditions }` | Filter the layout chooser options. Pro adds its own styles and updates the `conditions` allowlist used to hide the Pro-alert heading. |
| `eael/advanced_accordion/allow_dangerous_html` | filter (emitted) | `bool $allow = false` | When true, Repeater tab content is echoed without `wp_kses`. Use only on fully-trusted editors. |
| `eael/controls/query` | action (emitted, **internal**) | `(Widget_Base $widget)` | Inject shared post-query control section. Same internal pattern as Post_Grid — handler is in Lite's Bootstrap, not Pro. |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the `eael_section_pro` upsell when Pro is active. |
| `wpml_object_id` | filter (consumed) | `(int $id, 'wp_template', true)` | Translate the Saved-Template id per language; see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation). |

JS-side custom events / globals:

- `eael.hooks.doAction("widgets.reinit", $this.parent())` — fires after open animation; lets nested EA widgets (e.g., a Filterable Gallery inside an accordion panel) re-layout once visible.
- `eael.hooks.doAction("ea-advanced-accordion-triggered", $this.next())` — cross-widget reflow notification; consumers reflow when a panel becomes visible.
- `window.eaelPreventResizeOnClick = true` set on every open — prevents browser unresponsiveness from a resize-event loop in some EA widgets.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): FA4 shim, WPML, `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", () => elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-accordion.default', handler))` — newer EA registration pattern (same as Image_Accordion).
- **Guard:** none — no `elementStatusCheck` flag. Re-fires of `frontend/element_ready` (e.g., after AJAX load) re-bind everything.
- **Vendor dependency:** none (jQuery only — `slideUp/slideDown/animate`).
- **Reads on init:** `data-accordion-type`, `data-toogle-speed`, `data-custom-id-offset`, `data-scroll-on-click`, `data-scroll-speed` from `.eael-adv-accordion`. `window.location.hash` for deep-link target.
- **Branches:**
  - hash present OR scroll-on-click — store `offset().top` on each header as `data-scroll`; if a header's id matches the hash, open it.
  - on click — if `accordion-type === 'accordion'`, close all sibling open panels first; if `toggle`, just toggle this one.
- **Race-condition guard:** click handler returns early if header has `triggered` class; the class is added 50ms after open and globally cleared 70ms after click — short window protects against double-fire.
- **Keyboard a11y:** Enter (13) and Space (32) on a focused `.eael-accordion-header` triggers click ([JS line 96-100](../../src/js/view/advanced-accordion.js#L96)). `tabindex="0"` is set in render so headers are focusable.
- **Nested-accordion safety:** `$accordionHeader.unbind("click")` runs before binding to prevent stacking when the JS re-runs (e.g., a Pro accordion contains a Lite accordion).
- **`active` class on header is what JS toggles** — not on the content panel; SCSS keys icon-pair visibility off `.active` on the header sibling pattern.

## Common Issues

### Hash link `#safari` doesn't open the matching tab

- **Likely cause:** the literal id `safari` is rewritten to `eael-safari` on both PHP render and JS hash lookup ([line 1751](../../includes/Elements/Adv_Accordion.php#L1751); [JS line 6](../../src/js/view/advanced-accordion.js#L6)).
- **Diagnose:** inspect the rendered tab `id` — it'll be `eael-safari`, not `safari`.
- **Fix:** use a different Custom ID. The collision exists because `safari` is also used as a body class by browser-detection scripts.

### Tabs all open at once (or none open) regardless of `accordion`/`toggle` setting

- **Likely cause:** the `data-accordion-type` attribute on `.eael-adv-accordion` is missing or malformed (renders inside the inner-wrapper toggle when optimised markup is enabled).
- **Diagnose:** inspect the outer element — does it have `data-accordion-type="accordion"` or `="toggle"`?
- **Fix:** save the widget again so render emits a fresh attribute. If the value contains an extra space or quotes, the JS `data()` parse may fall back to undefined → defaults to `toggle` behaviour.

### Saved Template inside a tab is empty or shows the wrong language

- **Likely cause:** `eael_primary_templates` id is for a different language and `wpml_object_id` filter is not active (WPML/Polylang inactive); OR the template post isn't published.
- **Diagnose:** check `Helper::is_elementor_publish_template($id)` — does it return true?
- **Fix:** publish the template; if multilingual, ensure WPML language assignment exists. Saved-template content runs through `Plugin::$instance->frontend->get_builder_content()` which respects template post status.

### Editing tab content shows raw HTML / shortcodes

- **Likely cause:** content is run through `wp_kses(Helper::eael_allowed_tags())` so disallowed tags are stripped. The list excludes `<style>`, `<script>`, and several attributes.
- **Diagnose:** check what gets stripped vs. saved.
- **Fix:** broaden the allowlist via the `eael/allowed_tags` filter (in Helper trait), OR enable `eael/advanced_accordion/allow_dangerous_html` if the editor is fully trusted (security trade-off).

### FAQ schema not appearing in page source / Rich Results test

- **Likely cause:** schema is collected via `Helper::set_eael_advanced_accordion_faq($faq)` and emitted in `<head>` only after all widgets have rendered. If the page is fully cached or another widget breaks, emission may be skipped.
- **Diagnose:** view-source for the page — is there a `<script type="application/ld+json">` block?
- **Fix:** verify `eael_adv_accordion_faq_schema_show == 'yes'`; for `template` text_type tabs, also fill `eael_adv_accordion_tab_faq_schema_text` (template content can't be auto-extracted into JSON-LD).

### Repeated open/close clicks freeze briefly or skip animation

- **Likely cause:** the `triggered` 70ms guard is doing its job. Designed to absorb double-clicks.
- **Diagnose:** check if the second click happens within 70ms of the first — that's the dead window.
- **Fix:** working as intended; if undesirable, the guard window is tunable in JS but not exposed as a control.

## Known Limitations

- **`role="tabpanel"` and `aria-expanded` missing** — both are commented out / never written ([line 1765](../../includes/Elements/Adv_Accordion.php#L1765); never set on the header). Screen readers can't announce open/close state. `aria-controls` and `aria-labelledby` are present but incomplete without their counterparts.
- **Two long-standing typos in the public contract** — `data-toogle-speed` (should be `data-toggle-speed`) and `eael_adv_accordion_toggle_icon_postion` control id (should be `position`). Renaming would break saved widget data; treat as legacy. JS variable `$srollSpeed` ([JS line 14](../../src/js/view/advanced-accordion.js#L14)) carries the same `scroll`-misspelled flavour.
- **No `elementStatusCheck` guard in JS** — re-fires of `frontend/element_ready` (Elementor's lifecycle, AJAX-rebuilt regions) bind a fresh click handler. The `unbind('click')` at [JS line 44](../../src/js/view/advanced-accordion.js#L44) mitigates by clearing handlers on every (re-)init, but other listeners (keydown, scroll-data) accumulate.
- **`window.eaelPreventResizeOnClick` is process-global** — set to true on every open; never reset to false. Other widgets that read it stay in their post-resize-suppression state for the rest of the page lifecycle. Likely fine in practice but a leaked global.
- **`{{WRAPPER}} .eael-adv-accordion` selector and `.eael-accordion_media-wrapper` are paired in many style controls** — even when a Lite-only site never renders `.eael-accordion_media-wrapper`, every style rule emits both. Wasted CSS bytes per widget, but no runtime cost.
- **`eael_adv_accordion_styles` filter signature is undocumented for third parties** — Pro relies on the exact `{ styles: [...], conditions: [...] }` shape. Any third-party listener needs to preserve that shape or the Pro alert breaks.
- **Two un-prefixed legacy hooks** — `eael_adv_accordion_media_type_controls` and `eael_adv_accordion_media_type` predate the `eael/<context>/<action>` naming convention. Renaming requires dual-emit migration to avoid breaking Pro.
- **Dynamic content source uses widget-level open/closed icons** — per-Repeater item icons don't apply. Mixing dynamic + custom modes on the same page yields visually inconsistent caret pairs.
