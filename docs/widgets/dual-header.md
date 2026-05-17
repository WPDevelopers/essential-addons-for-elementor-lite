# Dual Color Heading Widget

> Renders a stylised heading split into two colour halves, with optional icon, sub-text, separator (line or icon), and a Repeater mode for multi-line gradient titles. Pure CSS — no JS at runtime.

**Class file:** [`includes/Elements/Dual_Color_Header.php`](../../includes/Elements/Dual_Color_Header.php)
**Slug:** `dual-header` (config.php key) — widget id `eael-dual-color-header`
**Public docs:** <https://essential-addons.com/elementor/docs/dual-color-headline/>
**Pro-shared:** ⚠️ Lite-only with standard Pro upsell section; no Pro-injected features in this widget.

**Naming note:** the widget id `eael-dual-color-header` and the config slug `dual-header` differ because of a historical rename. The legacy mapping is preserved by [`Elements_Manager::replace_widget_name`](../../includes/Classes/Elements_Manager.php#L215) — see [`asset-loading.md § replace_widget_name`](../architecture/asset-loading.md#common-pitfalls) for the broader pattern.

---

## Overview

Dual Color Heading is the typical "two colour title" widget used in hero sections, section dividers, and landing-page headers. The title splits into a lead phrase (first segment, often coloured or gradient-treated) and a continuation phrase. An optional sub-text line, separator decoration, and icon round out the design.

A Repeater mode lets the user define multiple title spans, each with its own gradient toggle — useful for stacked multi-line gradient headings without managing separate widgets. Rendering is entirely server-side; the browser receives finished HTML with classes and inline styles only, no JS needs to run.

## Features

- Lead title + continuation title with separate colour controls
- Gradient title fill (linear gradient between two colour stops)
- Multi-title Repeater mode — each entry can opt in to a gradient
- Optional sub-text line beneath the title
- Optional icon (Font Awesome 5 or custom SVG via Elementor's `ICONS` control)
- Separator decoration — two-span CSS line OR an icon — positioned before or after the title
- Four layout types: default, icon on top, icon and sub-text on top, sub-text on top
- HTML tag selector for the title element (`h1` … `h6`, `div`, `span`, `p`) sanitised server-side
- Responsive typography and spacing controls via Elementor's standard control groups
- Pro upsell section at the bottom (only visible when Pro is not active)

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Lead + continuation title | ✅ | ✅ |
| Single gradient title | ✅ | ✅ |
| Multi-title Repeater | ✅ | ✅ |
| All four layout types | ✅ | ✅ |
| Icon + sub-text + separator | ✅ | ✅ |
| Pro upsell section visible | ✅ | ❌ (suppressed when Pro is active) |

No widget-specific Pro feature gating beyond the standard upsell section. This widget is largely identical between Lite and Pro.

## Use Cases

- Landing-page hero heading with a two-colour value proposition (e.g. "Build **better** websites")
- Section dividers introducing a feature area
- Marketing copy emphasising contrast between two ideas
- Sub-section titles inside long-form pages where typography variation matters
- Multi-line stacked gradient headings using the Repeater mode

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Dual_Color_Header.php`](../../includes/Elements/Dual_Color_Header.php) | PHP widget class (1,217 lines) — controls registration + render |
| [`src/css/view/dual-header.scss`](../../src/css/view/dual-header.scss) | Source styles |
| [`config.php`](../../config.php) entry `'dual-header'` | Asset_Builder dependency declaration (line 223) |
| `assets/front-end/css/view/dual-header.min.css` | Built output (do not edit) |

No JS files. No vendor libraries. No edit-mode JS.

## Architecture

- **Pure CSS rendering** — the widget produces complete HTML server-side; the browser does no runtime initialisation. Gradient titles use `background: linear-gradient(...)` + `background-clip: text` (handled in CSS, applied via the `eael-dch-title-gradient` class).
- **`Helper::eael_validate_html_tag`** sanitises the user-chosen HTML tag for the title — prevents arbitrary tag injection. See [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) for the allow-list.
- **`Helper::eael_fetch_color_or_global_color`** resolves a colour control's value, honouring Elementor's global colour selections. The widget uses this for the two gradient colour stops so theme global colours are respected.
- **Four `render()` branches** keyed on `eael_dch_type` — each branch produces a different DOM order for icon / sub-text / title / separator. The branches are sequential `<?php elseif ?>` blocks, not a unified template. This means a future fifth layout requires extending the conditional rather than configuring a template engine.
- **Single root `<div class="eael-dual-header">`** in every branch — consistent with widget-development rules. The slug used for the root class is the legacy `dual-header`, matching the config.php key, not the widget id.
- **Standard Pro upsell pattern** ([line 373](../../includes/Elements/Dual_Color_Header.php#L373)) — `if ( ! apply_filters( 'eael/pro_enabled', false ) )` gates a marketing section at the bottom of the controls panel. See [`elementor-controls`](../../.claude/skills/elementor-controls/SKILL.md) for the canonical pattern.

## Render Output

The widget produces one of four DOM structures depending on `eael_dch_type`. All start with the same root.

### Default layout (`eael_dch_type == 'dch-default'`)

```html
<div class="eael-dual-header">
  <!-- separator (if position = before_title): -->
  <div class="eael-dch-separator-wrap">
    <span class="separator-one"></span>
    <span class="separator-two"></span>
    <!-- OR an icon, if eael_dch_separator_type == 'icon' -->
  </div>

  <!-- title -->
  <h2 class="title eael-dch-title">
    <span class="eael-dch-title-text eael-dch-title-lead lead solid-color"
          style="background: linear-gradient(#062ACA, #9401D9);"  <!-- only when has_gradient -->
    >First Title</span>
    <span class="eael-dch-title-text">Second Title</span>
  </h2>

  <!-- separator (if position = after_title) -->

  <!-- subtext, if non-empty -->
  <span class="subtext">Optional sub-text</span>

  <!-- icon, if eael_show_dch_icon_content == 'yes' -->
  <span class="eael-dch-svg-icon">
    <!-- SVG markup from Icons_Manager::render_icon -->
  </span>
</div>
```

When the Repeater mode is on (`eael_dch_enable_multiple_titles == 'yes'`), the title element instead contains one `<span>` per Repeater item:

```html
<h2 class="title eael-dch-title">
  <span class="eael-dch-title-text elementor-repeater-item-abc123 eael-dch-title-gradient">First piece</span>
  <span class="eael-dch-title-text elementor-repeater-item-def456">Second piece</span>
  <!-- …more repeater items -->
</h2>
```

Each item gets its `_id`-keyed class (`elementor-repeater-item-<id>`) for per-item style targeting, plus optionally `eael-dch-title-gradient`.

### Other layout branches

- `dch-icon-on-top` — icon emitted first, then separator + title + subtext
- `dch-icon-subtext-on-top` — icon + subtext both rendered before title
- `dch-subtext-on-top` — subtext first, then title, then icon

The same conditional emit logic runs in each branch ([`render()` lines 1126-1215](../../includes/Elements/Dual_Color_Header.php#L1126)).

## Controls Reference

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_dch_type` | SELECT | `dch-default` | Content → Dual Color Heading | Branches in `render()` — picks one of four DOM layouts |
| `title_tag` | SELECT | `h2` | Content → Dual Color Heading | The `<tag class="title eael-dch-title">` wrapper (sanitised via `eael_validate_html_tag`) |
| `eael_dch_enable_multiple_titles` | SWITCHER | `''` | Content → Dual Color Heading | Toggles between single-title and Repeater mode; controls a large block of conditional dependencies |
| `eael_dch_first_title` | TEXT | "Default" | Content → Dual Color Heading | Lead title text; visible only when Repeater mode off |
| `eael_dch_last_title` | TEXT | "Title" | Content → Dual Color Heading | Continuation title text; visible only when Repeater mode off |
| `eael_dch_multiple_titles` | REPEATER | 3 default items | Content → Dual Color Heading | Per-item title spans; visible only when Repeater mode on |
| `eael_dch_subtext` | TEXTAREA | "" | Content → Dual Color Heading | Sub-text content (rendered through `wp_kses` + `parse_text_editor`) |
| `eael_dch_separator_type` | SELECT | `default` | Content → Dual Color Heading | Picks two-span CSS separator or icon separator |
| `eael_dch_separator_icon` | ICONS | — | Content → Dual Color Heading | Icon for the separator (only when type = icon) |
| `eael_dch_separator_position` | SELECT | `after_title` | Content → Dual Color Heading | Renders separator before or after the title |
| `eael_show_dch_icon_content` | SWITCHER | `''` | Content → Dual Color Heading | Toggles icon emission |
| `eael_dch_icon_new` | ICONS | — | Content → Dual Color Heading | Icon (FA5 or SVG); falls back to legacy `eael_dch_icon` for FA4-migrated installs |
| `eael_dch_dual_color_selector` | CHOOSE | `solid-color` | Style → Lead Title Color | Gates which gradient / colour controls are visible |
| `eael_dch_dual_title_color_gradient_first` / `_second` | COLOR (global-aware) | `#062ACA` / `#9401D9` | Style → Lead Title Color | Gradient stops for the lead title's `background` |
| `eael_dch_dual_title_color` | COLOR | — | Style → Lead Title Color | Solid lead title colour (when gradient is off) |
| `eael_dch_title_color` | COLOR | — | Style → Title | Continuation title colour |
| `eael_dch_title_typography` (Group) | Group_Control_Typography | — | Style → Title | Title typography |
| `eael_dch_separator_color`, `_height`, `_width`, `_margin`, … | Various | — | Style → Separator | Separator-line appearance |
| `eael_dch_subtext_color`, `_typography`, `_margin`, … | Various | — | Style → Subtext | Sub-text appearance |
| `eael_dch_icon_color`, `_size`, `_margin`, … | Various | — | Style → Icon | Icon styling |
| `eael_section_pro` + `eael_control_get_pro` | (Pro upsell) | — | (custom) | Standard upsell — visible only when Pro is not active |

Full controls in [`register_controls()`](../../includes/Elements/Dual_Color_Header.php#L72) — six top-level sections (one Content, four Style, one Pro upsell).

## Conditional Dependencies

A control hidden behind a condition still saves its value. This map answers "why doesn't option X show in my panel?" without reading the source.

```text
eael_dch_first_title                       → visible when enable_multiple_titles != 'yes'
eael_dch_last_title                        → visible when enable_multiple_titles != 'yes'
eael_dch_multiple_titles (Repeater)        → visible when enable_multiple_titles == 'yes'

eael_dch_separator_icon                    → visible when separator_type == 'icon'
eael_dch_separator_position                → always (no condition)

eael_dch_icon_new                          → visible when eael_show_dch_icon_content == 'yes'
(icon styling controls)                    → visible when eael_show_dch_icon_content == 'yes'

eael_dch_dual_title_color_gradient_first   → visible when dual_color_selector == 'gradient-color'
eael_dch_dual_title_color_gradient_second  → visible when dual_color_selector == 'gradient-color'
eael_dch_dual_title_color                  → visible when dual_color_selector == 'solid-color'

(Repeater-mode per-item title styling)     → visible when enable_multiple_titles == 'yes'
(single-title styling controls)            → visible when enable_multiple_titles != 'yes'

eael_section_pro / eael_control_get_pro    → visible when Pro plugin is NOT active
```

The most impactful toggle is `eael_dch_enable_multiple_titles` — flipping it on hides a substantial block of single-title styling controls and reveals the Repeater + its per-item styling.

## Behavior Flow

End-to-end from "user drops widget" to "browser renders":

1. **User drops the widget on the Elementor canvas.** Elementor calls `register_controls()` → control panel appears with defaults.
2. **User configures the widget** — picks a layout, fills in titles, optionally enables Repeater mode, sets colours.
3. **User clicks Update.** Elementor saves the settings to `_elementor_data` post meta.
4. **Editor preview iframe re-renders** by calling `render()` with the new settings.
5. **`render()` reads `$this->get_settings_for_display()`** and computes:
   - Whether gradient is active (both gradient colour stops non-empty)
   - The resolved gradient CSS via `Helper::eael_fetch_color_or_global_color` (respects Elementor global colours)
   - The sanitised title tag via `Helper::eael_validate_html_tag`
   - The separator markup (icon or two-span)
   - The title markup (single span pair OR Repeater iteration)
6. **`render()` branches on `eael_dch_type`** — emits one of four DOM layouts, each combining the pre-computed pieces in a different order.
7. **Browser receives the complete HTML.** No JS init happens; CSS handles gradient text fill, separator styling, and responsive typography. The widget is visually complete on first paint.

## JavaScript Lifecycle

**N/A — pure CSS widget, no JS.** The widget has no `src/js/view/dual-header.js` and declares no `js` block in its `config.php` dependency. All rendering is server-side; all visual effects are CSS.

This is the simplest possible widget lifecycle from a runtime perspective. There is nothing to debug client-side beyond the rendered HTML and applied CSS.

## Asset Dependencies

`Asset_Builder` enqueues the single CSS file only when the widget is detected on the page. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats (templates, popups, shortcodes).

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `dual-header.min.css` | self (built from `src/css/view/dual-header.scss`) | Always when widget present |

### JS

| | |
| --- | --- |
| _N/A_ | The widget has no JS — pure CSS rendering. |

## Hooks & Filters

The widget itself does not expose any custom filters. Extension points come from upstream:

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter | `(bool $enabled)` | Standard Lite/Pro gate — suppresses the Pro upsell section when Pro is active |
| `eael_allowed_tags` (used by `Helper::eael_allowed_tags`) | filter | `(array $tags)` | Modify allowed HTML tags accepted in subtext / titles (cross-cutting EA filter) |

No widget-specific style filter (e.g. there is no `dual_color_header_style_types`). New visual variants of this widget cannot be injected via filter — they require code changes to `render()`'s branch chain.

## Customization Recipes

### Recipe 1 — Override the gradient direction via theme CSS

The widget hardcodes `linear-gradient(...)` without an angle, defaulting to a top-to-bottom (`180deg`) gradient. To override:

```scss
// In your theme stylesheet
.eael-dual-header .eael-dch-title-lead {
    background: linear-gradient( 90deg, #062ACA, #9401D9 ) !important;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
```

`!important` is needed because the widget emits an inline `style="background: linear-gradient(...);"` attribute — inline styles win cascade unless overridden.

### Recipe 2 — Add a custom layout type via render override

The four `eael_dch_type` values are hardcoded in the SELECT and in `render()`'s branches. A custom layout requires (a) filter-injecting a new SELECT option and (b) overriding `render()`. Cleaner approach: subclass the widget or fork the render via `elementor/widget/render_content`:

```php
add_filter( 'elementor/widget/render_content', function ( $content, $widget ) {
    if ( $widget->get_name() !== 'eael-dual-color-header' ) {
        return $content;
    }
    $settings = $widget->get_settings_for_display();
    if ( ( $settings['eael_dch_type'] ?? '' ) !== 'my-custom-layout' ) {
        return $content;
    }
    // Build and return your custom markup.
    return '<div class="eael-dual-header my-custom-layout">…</div>';
}, 10, 2 );
```

### Recipe 3 — Force-hide the Pro upsell for development screenshots

```php
add_filter( 'eael/pro_enabled', '__return_true' );
```

Suppresses every Pro upsell across EA, including this widget's. Useful for clean documentation screenshots; remove before production.

## Common Issues

### Gradient text shows as a solid coloured rectangle instead of gradient-filled text

- **Likely cause:** browser doesn't support `background-clip: text` / `-webkit-text-fill-color: transparent`, or theme CSS overrode one of them
- **Diagnose:** inspect `.eael-dch-title-lead` in DevTools — does it have both `background-clip: text` and `-webkit-text-fill-color: transparent`?
- **Fix:** add both properties at higher specificity in theme CSS; check browser support (modern Chrome / Safari / Firefox all support it, but older browsers may fall back to the rectangle)

### Repeater items render but per-item gradient toggle has no effect

- **Likely cause:** the `eael-dch-title-gradient` class is on the span, but the CSS rule targeting it isn't loaded (cache) or is overridden
- **Diagnose:** view source, confirm the class is on the span; inspect computed styles in DevTools
- **Fix:** regenerate Elementor CSS (Tools → Regenerate CSS & Data); confirm `dual-header.min.css` is enqueued on the page

### Title HTML tag isn't applied (always renders as `h2`)

- **Likely cause:** `Helper::eael_validate_html_tag` rejected the user's tag (not in the allow-list) and fell back to `h2`
- **Diagnose:** check the allow-list in `Helper::eael_validate_html_tag`
- **Fix:** use one of the allowed tags; or extend the allow-list (security-sensitive — only add tags safe for the context)

### Icon doesn't appear after upgrading from an older version

- **Likely cause:** the widget supports both legacy `eael_dch_icon` (FA4) and new `eael_dch_icon_new` (FA5 / Icons control). The migration flag `__fa4_migrated.eael_dch_icon_new` controls which one renders.
- **Diagnose:** inspect post meta — does `_elementor_data` show the migrated flag? Does `eael_dch_icon_new` have a value?
- **Fix:** re-pick the icon in the widget's settings; Elementor will write to `eael_dch_icon_new` and update the migrated flag

### Layout changes (e.g. icon-on-top → default) don't take effect

- **Likely cause:** Elementor's per-post CSS cache; the layout type changes which class structure renders, but rebuilt CSS hasn't been generated
- **Diagnose:** view source — did the markup change to the new layout?
- **Fix:** Tools → Regenerate CSS & Data; hard-refresh the page

### Subtext shows raw HTML tags

- **Likely cause:** the tag the user typed isn't in `Helper::eael_allowed_tags`
- **Diagnose:** inspect the subtext setting in post meta and compare to the allow-list
- **Fix:** stick to allowed tags; or extend the allow-list via the `eael_allowed_tags` filter (be cautious — affects every EA widget)

## Testing Checklist

After modifying this widget, manually verify on `http://localhost:8888`:

- [ ] Drop widget at default config — renders the default layout with two-colour title
- [ ] Switch to each of the four `eael_dch_type` layouts — DOM order changes accordingly
- [ ] Toggle gradient on the lead title — `<span style="background: linear-gradient(...);">` emits; gradient text fill works
- [ ] Switch to Repeater mode — multiple title spans render, each with `elementor-repeater-item-<id>` class
- [ ] Toggle per-Repeater-item gradient — `eael-dch-title-gradient` class appears on that span only
- [ ] Change separator type from default to icon — emits SVG / `<i>` instead of two-span
- [ ] Move separator position before / after title
- [ ] Toggle icon — `<span class="eael-dch-svg-icon">` appears / disappears
- [ ] Change title HTML tag (h1 → h6 → p → span) — wrapper tag changes; invalid values fall back to h2
- [ ] Mobile / tablet / desktop responsive switcher — typography and spacing change per breakpoint
- [ ] Two Dual Color Heading widgets on one page — both render correctly with no class collisions
- [ ] Disable Pro plugin — Pro upsell section appears in the panel
- [ ] Special characters in titles (`<`, `>`, `&`) — output is escaped; no XSS
- [ ] RTL site — text direction handled by Elementor's RTL pipeline (no widget-specific RTL block)
- [ ] After source changes, `npm run build` and visually confirm on the test site

## Architecture Decisions

### Pure server-side rendering (no JS)

- **Context:** the widget is a static heading. There is no interactivity, no animation that needs client-side state, no AJAX.
- **Decision:** render the entire DOM on the server. Use CSS for the only visual effect (gradient text fill).
- **Alternatives rejected:** client-side rendering for the gradient (adds complexity for no gain); CSS-in-JS-style runtime style injection (loses cacheability).
- **Consequences:** zero JS bytes for this widget. First paint is final paint. Caching works perfectly. The trade-off is that future runtime features (e.g. a click-to-rotate-title animation) would require introducing JS that doesn't exist today.

### Four hardcoded layouts via branched `render()`

- **Context:** four layout permutations are common requests; templating them generically (e.g. data-driven slot order) is over-engineering for the actual variation.
- **Decision:** four explicit `elseif` branches in `render()`, each producing a complete DOM tree for its layout.
- **Alternatives rejected:** templating engine with slot ordering (more code, no benefit for four cases); separate widgets per layout (multiplies the user-facing widget list).
- **Consequences:** adding a fifth layout requires editing `render()` and the SELECT options. Acceptable because the use case has been stable.

### Both gradient colour stops must be non-empty for gradient to apply

- **Context:** a half-configured gradient (one colour set, the other empty) would emit broken CSS.
- **Decision:** `$has_gradient = $settings[first] && $settings[second]` — both must be truthy.
- **Alternatives rejected:** apply gradient even with one stop (browser silently fails); use a default for the missing stop (surprises the user).
- **Consequences:** users picking only one of the two colours see the solid-colour fallback rendering. Confusing if they expected partial gradient, but the failure mode is harmless.

### Legacy slug `dual-header` kept after rename

- **Context:** the widget id was renamed from `eael-dual-color-header` to the shorter `eael-dual-header` at some point. Saved pages using the old id needed to continue working.
- **Decision:** keep the legacy slug in `config.php` and in the `replace_widget_name` map. The widget id (`get_name()`) and the config key (`'dual-header'`) intentionally diverge.
- **Alternatives rejected:** force a migration of all `_elementor_data` post meta (one-shot DB churn, risk of failure); break old pages (unacceptable).
- **Consequences:** the asset filename is `dual-header.min.css`, the doc filename is `dual-header.md`, the root class is `eael-dual-header`, but the widget id is `eael-dual-color-header`. New contributors find this confusing; documented at the top of this file.

## Known Limitations

- **No filter for additional layouts.** Adding a fifth layout type requires editing `render()` directly; no public extension point.
- **Gradient angle is hardcoded.** The widget emits `linear-gradient($a, $b)` with no angle parameter — defaults to top-to-bottom. Overriding requires theme CSS with `!important`.
- **Single root class is `eael-dual-header`, not `eael-dual-color-header`.** Themes targeting the long form will not match. The short form is the legacy slug.
- **No per-Repeater-item ordering control beyond the standard drag handles.** Adding visual sort options would require editing the Repeater controls.
- **No accessibility hint for decorative separators.** The two-span CSS separator (and icon separator) don't carry `aria-hidden`; screen readers may attempt to announce them.
- **Inline gradient `style="..."` attribute fights with theme CSS.** Documented as a Common Issue; `!important` is the standard workaround.
- **Subtext uses `parse_text_editor` only in the default layout.** Other three layouts call `wp_kses` directly without `parse_text_editor`. Shortcodes may behave differently across layouts. Likely a refactor candidate.
- **The `__fa4_migrated` icon migration flag is non-obvious.** Old installs with un-migrated icons may show legacy `<i class="...">` markup; new installs always use the `Icons_Manager::render_icon` path. Documented in Common Issues.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
