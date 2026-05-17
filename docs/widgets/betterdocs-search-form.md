# BetterDocs Search Form Widget

> Pure styling wrapper around BetterDocs's `[betterdocs_search_form]` shortcode. **Zero asset dependencies** in `config.php` — no CSS, no JS — relies entirely on BetterDocs's own enqueued assets for live-search autocomplete behavior and base styling. EA contributes only `{{WRAPPER}}`-scoped CSS overrides via `selectors` against BetterDocs's class hooks (`.betterdocs-live-search`, `.betterdocs-searchform`, `.docs-search-result`). Render delegates via `do_shortcode(shortcode_unautop('[betterdocs_search_form placeholder="…"]'))` — same `shortcode_unautop` wrapper as FluentForm. **Leanest BetterDocs widget** (598 PHP lines, 0 SCSS, 0 JS).

**Class file:** [`includes/Elements/Betterdocs_Search_Form.php`](../../includes/Elements/Betterdocs_Search_Form.php)
**Slug:** `betterdocs-search-form` (widget id `eael-betterdocs-search-form`)
**Public docs:** <https://essential-addons.com/elementor/docs/betterdocs-search-form/>
**Pro-shared:** ❌ No widget-specific Pro extension. The widget emits **one `apply_filters` call** (`eael_betterdocs_search_form_params`) but it's effectively a **dead hook** — the result is passed to `sprintf` with no placeholders to substitute. No `eael_section_pro` upsell, no `do_action`.

---

## Overview

BetterDocs Search Form is the simplest of the 3 BetterDocs widgets and one of the simplest widgets in EA Lite overall. It wraps BetterDocs's `[betterdocs_search_form]` shortcode, which emits an `<input>` with live-search autocomplete (BetterDocs provides the AJAX behavior + dropdown rendering). EA contributes only **panel-driven CSS overrides** targeting BetterDocs's selectors via `{{WRAPPER}} .betterdocs-* { … }`. The widget has **no SCSS file, no JS file, and no asset declarations in `config.php`** — it's the EA-side equivalent of "render the shortcode and let WP-enqueued plugin assets do everything else". A `render_plain_content()` method outputs the raw shortcode string for Elementor's Export-as-Code feature. Plugin gate is `defined('BETTERDOCS_URL')` — same as Category_Box / Category_Grid.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Search input via `[betterdocs_search_form]` shortcode | ✅ | ✅ |
| Live-search autocomplete behavior | ✅ — handled entirely by BetterDocs plugin's own JS | ✅ |
| Placeholder text customization (passed to shortcode `placeholder` attr) | ✅ | ✅ |
| Search box / field / result-dropdown styling via `selectors` against BetterDocs class hooks | ✅ | ✅ |
| Search field close-icon styling | ✅ | ✅ |
| Result-item hover-state styling | ✅ | ✅ |
| `render_plain_content()` for Elementor export-as-code | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Betterdocs_Search_Form.php`](../../includes/Elements/Betterdocs_Search_Form.php) | PHP widget class (598 lines) — entirely controls + minimal `render()` + `render_plain_content()` |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `eael_e_optimized_markup()` for `has_widget_inner_wrapper` — that's the only Helper call |
| [`config.php`](../../config.php#L968) entry `'betterdocs-search-form'` | **No `dependency` declared** — leanest config entry in EA |
| (no SCSS file) | — relies on BetterDocs's own `betterdocs-public.css` for base layout |
| (no JS file) | — BetterDocs's own JS handles live-search AJAX + autocomplete dropdown rendering |

## Architecture

- **Plugin gate via `defined('BETTERDOCS_URL')`** at [line 84](../../includes/Elements/Betterdocs_Search_Form.php#L84) (register_controls) and [line 584](../../includes/Elements/Betterdocs_Search_Form.php#L584) (render) — consistent with Category_Box / Category_Grid. Warning notice with plugin-install deep-link.
- **`config.php` entry has no `dependency` block** at all ([config.php line 968-970](../../config.php#L968)) — three lines total. No CSS file, no JS file, no vendor libs. The widget assumes BetterDocs's `betterdocs-public.css` + `betterdocs.js` are already loaded by the plugin's own `wp_enqueue_scripts` hook when its shortcode is detected on the page. **Leanest config entry in EA.**
- **Render uses `shortcode_unautop` wrapper** at [line 587-588](../../includes/Elements/Betterdocs_Search_Form.php#L587) — `do_shortcode( shortcode_unautop( $shortcode ) )`. Strips wrapping `<p>` tags wpautop adds around bare shortcodes. **Same pattern as FluentForm** — the two widgets in EA Lite that wrap their shortcode in `shortcode_unautop`.
- **Dead filter `eael_betterdocs_search_form_params`** at [line 587](../../includes/Elements/Betterdocs_Search_Form.php#L587) — the call is `sprintf('[betterdocs_search_form placeholder="' . esc_html($placeholder) . '"]', apply_filters('eael_betterdocs_search_form_params', []))`. The format string has **no `%s` / `%d` placeholders**, so `sprintf` ignores the second arg entirely. Filter exists in the codebase and is grep-able, but produces no effect. Third parties listening for it won't see their return value reach the shortcode. **Cosmetic bug — looks like a hook surface but isn't.**
- **`render_plain_content()` outputs raw shortcode string** at [line 591-597](../../includes/Elements/Betterdocs_Search_Form.php#L591) — `echo '[betterdocs_search_form placeholder="…"]'`. Elementor's Export-as-Code feature calls this for plain-text export. **Only BetterDocs widget to implement this method** — Category_Box and Category_Grid don't.
- **Styling controls target BetterDocs's own class hierarchy** — `{{WRAPPER}} .betterdocs-live-search` (outer search container, search box bg + padding), `.betterdocs-searchform` (search field wrapper, field bg/text/typography/padding/border/shadow), `.betterdocs-live-search .docs-search-result` (autocomplete dropdown, width/max-width/bg/border + per-item normal/hover styles). EA's CSS is `{{WRAPPER}}`-scoped, so multiple widgets don't bleed into each other.
- **Search field close-icon controls** at [line 289-300](../../includes/Elements/Betterdocs_Search_Form.php#L289) — when the user has typed, BetterDocs shows a clear-text "X" icon. Panel exposes color + border color for this icon.
- **Result-item hover state has dedicated controls tab** at [line 555-573](../../includes/Elements/Betterdocs_Search_Form.php#L555) — separate `Hover` tab inside the result-item style section with its own count color override. `start_controls_tabs` / `end_controls_tabs` pair for normal / hover.
- **No `eael_section_pro` upsell, no `do_action`, one (dead) `apply_filters`** — same lean profile as Category_Box. Hooks-free for practical purposes.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. Shortcode output is static markup (BetterDocs AJAX-hydrates after page load), so caching is fine.

## Render Output

```html
<!-- Render is just: do_shortcode(shortcode_unautop('[betterdocs_search_form placeholder="…"]'))
     The output below is emitted entirely by BetterDocs plugin's shortcode handler -->

<div class="betterdocs-live-search">
  <form action="<search-action-url>" method="GET" class="betterdocs-searchform">
    <input type="search" name="s" placeholder="Search" autocomplete="off">
    <button type="submit"><svg>…</svg></button>
    <span class="search-close-icon">×</span>      ← shown by BetterDocs JS when input has value
  </form>
  <ul class="docs-search-result">                  ← populated by BetterDocs AJAX on input
    <li>
      <a href="<doc-permalink>">Doc Title<span>{category}</span></a>
    </li>
    …
  </ul>
</div>
```

Notes:

- The widget owns no markup at all — only the outer Elementor widget wrapper. Everything inside `.betterdocs-live-search` is from the BetterDocs shortcode.
- Live-search behavior (typeahead AJAX, dropdown population, close-icon show/hide) is **entirely** from BetterDocs's own JS.
- EA's `selectors` write CSS rules against BetterDocs's class hooks. If BetterDocs renames its classes, all EA style controls silently break.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Betterdocs_Search_Form.php#L79) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "BetterDocs not installed" notice + plugin-install deep-link |
| `search_box_bg` (group) / `search_box_padding` | GROUP / DIMENSIONS (responsive) | — | Content → Search Box | Outer search container background + padding |
| `section_search_field_placeholder` | TEXT (AI) | `Search` | Content → Search Field | Placeholder text; passed to shortcode `placeholder="…"` attr |
| `search_field_bg` / `_text_color` / `_text_typography` (group) | COLOR / COLOR / GROUP | — | Content → Search Field | Field background, text color, typography |
| `search_field_padding` / `_padding_radius` | DIMENSIONS | — | Content → Search Field | Field box model |
| `search_field_border` (group) / `_shadow` (group) | GROUP | — | Content → Search Field | Border + box-shadow |
| `search_field_close_icon_color` / `_close_icon_border_color` | COLOR | — | Content → Search Field | Live-search clear button (×) styling |
| `result_box_width` / `_max_width` | SLIDER | — | Content → Search Result | Autocomplete dropdown size |
| `result_box_bg` (group) / `_border` (group) | GROUP | — | Content → Search Result | Dropdown background + border |
| `result_box_item` style controls (normal + hover tabs) | various | — | Content → Search Result Item | Per-item background, text color, count color (within `start_controls_tabs`) |
| `result_box_item_hover_count_color` | COLOR | — | Content → Search Result Item (Hover tab) | Count span hover color |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                  → visible when defined('BETTERDOCS_URL') is FALSE
ALL search/style controls                 → visible when defined('BETTERDOCS_URL') is TRUE

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls
```

No internal conditional dependencies among the style controls.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_betterdocs_search_form_params` | filter (emitted, **dead**) | `array $params = []` | ⚠ Listed in code but **non-functional** — the `sprintf` format string at [line 587](../../includes/Elements/Betterdocs_Search_Form.php#L587) has no `%s`/`%d` placeholders, so `sprintf` discards the filter's return value. Extension authors expecting this hook to inject shortcode params get a no-op. |

The widget emits **no `do_action`** and consumes no `eael/pro_enabled` filter. BetterDocs's own shortcode flows through its plugin hook chain (`betterdocs_before_search_form`, etc.) — third parties listen for those independently of EA.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply.

## JavaScript Lifecycle

> N/A — **no widget JavaScript file.** `config.php` declares no JS dependency. BetterDocs's own JS bundle handles live-search AJAX, dropdown population, close-icon visibility, and keyboard navigation. EA does not subscribe to or extend BetterDocs's frontend events.

## Common Issues

### Widget shows "BetterDocs is not installed/activated"

- **Likely cause:** BetterDocs plugin not active.
- **Diagnose:** `class_exists` / `defined('BETTERDOCS_URL')` returns false.
- **Fix:** install + activate BetterDocs.

### Search form renders unstyled (no rounded corners, no padding)

- **Likely cause:** BetterDocs's own `betterdocs-public.css` isn't enqueued — BetterDocs auto-enqueues when its shortcode is detected; if the shortcode is detected via late-running content (e.g., AJAX-loaded block), the CSS may be skipped.
- **Diagnose:** browser DevTools → Network → look for `betterdocs-public.css`.
- **Fix:** force-enqueue BetterDocs's CSS via custom code: `add_action('wp_enqueue_scripts', function() { wp_enqueue_style('betterdocs-public'); });`.

### Autocomplete dropdown doesn't appear when typing

- **Likely cause:** BetterDocs's own JS isn't loaded, or the AJAX endpoint is blocked.
- **Diagnose:** browser console for JS errors; Network for failed `admin-ajax.php` calls.
- **Fix:** verify BetterDocs settings → Search Settings → enable live search; check that `wp-admin/admin-ajax.php` is accessible.

### Styling controls don't affect the search field

- **Likely cause:** BetterDocs renamed its CSS classes between versions. EA selectors target `.betterdocs-live-search`, `.betterdocs-searchform`, `.docs-search-result` — if these don't match BetterDocs's current markup, no styles apply.
- **Diagnose:** browser DevTools → inspect the rendered form; check class names.
- **Fix:** update both EA Lite and BetterDocs to the latest version; or apply custom CSS targeting the new class names.

### Filter `eael_betterdocs_search_form_params` doesn't inject anything

- **Likely cause:** **by design (bug)** — filter return value is discarded because the `sprintf` format string has no placeholders. Filter signature exists but does nothing.
- **Diagnose:** check the filter is being called: `var_dump(apply_filters('eael_betterdocs_search_form_params', ['test']))` returns your value, but it doesn't reach the rendered shortcode.
- **Fix:** wait for an upstream patch, OR use BetterDocs's own filters (`betterdocs_search_form_*`) to modify shortcode behavior.

### Multiple search form widgets on the same page

- **Likely cause:** BetterDocs's live-search JS may use a global selector for AJAX binding, causing all forms to share state.
- **Diagnose:** type in one form — do other forms' dropdowns also update?
- **Fix:** known BetterDocs-side limitation. Use a single search form per page if state isolation matters.

## Known Limitations

- **`eael_betterdocs_search_form_params` filter is dead** — `sprintf` format string has no placeholders; filter return value is silently discarded. Either fix by adding `%s` and concatenating into shortcode attrs, OR remove the misleading filter call.
- **All styling depends on BetterDocs's class names being stable** — `.betterdocs-live-search`, `.betterdocs-searchform`, `.docs-search-result`. If BetterDocs renames classes, EA controls silently no-op.
- **No EA JS, no EA CSS** — entirely dependent on BetterDocs's enqueued assets. Cache plugins that defer / strip BetterDocs's assets break the widget completely.
- **No `eael_section_pro` upsell + zero functional hooks** — same lean profile as other BetterDocs widgets. The dead filter doesn't count.
- **Plugin-install deep-link uses `s=BetterDocs`** (mixed case) — same as Category_Box.
- **`render_plain_content()` is unique to this widget among the 3 BetterDocs widgets** — Category_Box and Category_Grid don't implement it. Inconsistency in Elementor export-as-code behavior across the trio.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active. Safe because the rendered HTML is static; BetterDocs hydrates client-side.
- **No `get_style_depends()` / `get_script_depends()`** — implicit dependency on BetterDocs's enqueue logic. If BetterDocs changes its enqueue conditions (e.g., requires a specific shortcode-detection function), EA breaks without warning.
- **`shortcode_unautop` is used here and in FluentForm only** — inconsistent treatment of the same wpautop issue across the codebase. Most other widget shortcodes don't get this wrapper.
- **Plain shortcode echoed in `render_plain_content()` doesn't `do_shortcode` it** — by design for plain-text export, but means the exported content won't render without BetterDocs being installed at the destination site.
- **No frontend AJAX integration with EA** — live-search results aren't surfaced to EA's `eael.hooks.doAction` chain. Tabs / accordions containing the search form don't get notified on result-click.
- **Result-item normal/hover tabs** in panel use `start_controls_tabs` — minor UX inconsistency with other widgets that use single-section controls for hover states via `{{WRAPPER}} .selector:hover`.
