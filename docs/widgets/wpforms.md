# WPForms Widget

> CSS-styling wrapper around the WPForms plugin. Picks a form via a Select control (populated by querying the `wpforms` post type), optionally renders an EA-custom title + description (which **replaces** WPForms' native title/description by passing `false` to `wpforms_display()`), then delegates rendering to `wpforms_display($id, $show_title, $show_description)`. Unlike Contact_Form_7, this widget declares a script dependency (`wpforms-elementor`) registered by the WPForms plugin's own Elementor integration when active. **Zero EA widget-specific JavaScript**, **zero Pro extension hooks**, **and no `eael_section_pro` upsell panel** — even leaner than CF7.

**Class file:** [`includes/Elements/WpForms.php`](../../includes/Elements/WpForms.php)
**Slug:** `wpforms` (widget id `eael-wpforms`)
**Public docs:** <https://essential-addons.com/elementor/docs/wpforms/>
**Pro-shared:** ❌ No — Lite-only styling. **No `do_action` / `apply_filters` extension hooks at all** (zero Pro extension surface), and the `eael_section_pro` upsell panel is absent. Pro doesn't reference this widget.

---

## Overview

WPForms follows the **Form Integration pattern** introduced by Contact Form 7: gate `register_controls()` and `render()` on a `class_exists('\WPForms\WPForms')` check, render a "plugin not installed" RAW_HTML warning when missing, otherwise expose a form picker. The render path differs from CF7 in two ways: (a) it calls `wpforms_display($id, $show_title, $show_description)` instead of `do_shortcode()`, passing booleans to control WPForms' own title/description emission; (b) it declares `get_script_depends() = ['wpforms-elementor']` so WPForms' plugin-side Elementor integration script is enqueued. EA contributes only the styling layer plus a `custom_title_description` toggle that **replaces** WPForms' native title/description with EA-rendered ones (panel-driven TEXT/TEXTAREA fields). All visual customization targets WPForms' own `.wpforms-*` markup classes via `selectors`.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker, title/description controls (WPForms-native OR custom EA-rendered) | ✅ | ✅ |
| Show/hide labels (via `eael-wpforms-labels-yes` / `-no` prefix class) | ✅ | ✅ |
| Show/hide placeholder, custom radio/checkbox styling | ✅ | ✅ |
| Show/hide field-level error messages | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / errors / submit button) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/WpForms.php`](../../includes/Elements/WpForms.php) | PHP widget class (1526 lines) — controls, `render()`, `class_exists('\WPForms\WPForms')` gate, `get_script_depends()` |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L647) | `get_wpforms_list()` — queries `wpforms` post type with `posts_per_page = -1` (no cap, unlike CF7's hardcoded 999) |
| [`src/css/view/wpforms.scss`](../../src/css/view/wpforms.scss) | Source styles (70 lines) — alignment flex variants, labels show/hide, field min-height enforcement |
| [`config.php`](../../config.php#L879) entry `'wpforms'` | `Asset_Builder` dependency declaration: **CSS only** — `wpforms.min.css`. The script handle `wpforms-elementor` is declared via `get_script_depends()` (registered by the WPForms plugin) |
| `assets/front-end/css/view/wpforms.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form submission / validation / AJAX handled by WPForms plugin's own scripts via the `wpforms-elementor` handle |

## Architecture

- **Plugin-gate via `class_exists('\WPForms\WPForms')` in both `register_controls()` and `render()`** — when the WPForms plugin is inactive, `register_controls()` at [line 77-94](../../includes/Elements/WpForms.php#L77) shows ONLY a "Warning!" section with a RAW_HTML notice. `render()` at [line 1464-1466](../../includes/Elements/WpForms.php#L1464) returns early. Same canonical Form Integration gate pattern as Contact_Form_7, but using `class_exists` against the plugin's main class instead of `function_exists`.
- **`get_script_depends()` declares the WPForms-Elementor handle** at [line 40-46](../../includes/Elements/WpForms.php#L40) — returns `['wpforms-elementor']` only when the WPForms class exists, otherwise empty array. The script handle itself is **registered by the WPForms plugin** (not by EA) — EA just declares a dependency so Asset_Builder enqueues it when this widget is on a page. CF7 has no equivalent script dependency declaration.
- **Render via `wpforms_display()` PHP function, not `do_shortcode()`** at [line 1520](../../includes/Elements/WpForms.php#L1520) — `wpforms_display($form_id, $show_title, $show_description)`. The two boolean args drive whether WPForms emits its NATIVE `.wpforms-title` and `.wpforms-description` elements. When EA's `custom_title_description == 'yes'` toggle is on, BOTH booleans force to `false` — replacing the native markup with EA-rendered `<h3 class="eael-wpforms-title">` and `<div class="eael-wpforms-description">` from panel TEXT/TEXTAREA fields ([line 1493-1509](../../includes/Elements/WpForms.php#L1493)).
- **Form list via `Helper::get_wpforms_list()`** with `posts_per_page = -1` ([Helper line 654](../../includes/Classes/Helper.php#L654)) — no hardcoded cap (unlike CF7's `showposts = 999`). Empty result shows "Create a Form First". When WPForms class doesn't exist, returns `[0 => 'Create a Form First']` directly (no "Select a WPForm" prompt).
- **Inverted label visibility** — SCSS at [line 42-43](../../src/css/view/wpforms.scss#L42) **hides** `.wpforms-field-label { display: none }` by default, then [line 64-66](../../src/css/view/wpforms.scss#L64) re-shows them only when wrapper has `.eael-wpforms-labels-yes`. The `labels_switch` SWITCHER uses Elementor's `prefix_class => 'eael-wpforms-labels-'` so the value (`yes`/empty) becomes the suffix: `yes` → labels visible, off → labels hidden. **Cleaner than CF7's `labels-hide` toggle class pattern** (CF7's default is visible; here default is hidden — the SCSS default-state is inverted to match the prefix-class convention).
- **Three render-attribute toggle classes** — `placeholder-hide` (when `placeholder_switch != 'yes'`; **no SCSS rule** — reserved for themes), `title-description-hide` (when `custom_title_description == 'yes'`; **no SCSS rule** either — marker for a state where WPForms-native title/description is intentionally suppressed), and `eael-custom-radio-checkbox` (when `custom_radio_checkbox == 'yes'`; gates ~12 size/color/border controls in panel and triggers SCSS radio/checkbox replacement).
- **Alignment via flex on the outer container** — [SCSS line 5-23](../../src/css/view/wpforms.scss#L5) wraps the alignment in `&:not(.eael-wpforms-align-default) { display: flex }` and applies `justify-content: start | center | end` per class. **NOT the same as CF7's `text-align` approach** — flex parents work better for WPForms' multi-element form layout (each `.wpforms-container-full` becomes a flex child whose margin is also reset).
- **No `eael_section_pro` upsell, no extension hooks** — confirmed via `grep`: zero `do_action`, zero `apply_filters` (not even `eael/pro_enabled`). Unique in this category and unusually clean for an EA widget.
- **Custom title/description renders BEFORE `wpforms_display()`** in render flow ([line 1492-1521](../../includes/Elements/WpForms.php#L1492)) — `<div class="eael-wpforms-heading">…</div>` comes first, then `wpforms_display()` output with native title/description suppressed by `false` args. Mixing modes is impossible: you either get WPForms-native or EA-custom title/description, never both.

## Render Output

```html
<div class="eael-contact-form
            eael-wpforms
            eael-wpforms-align-<default|left|right|center>
            [eael-wpforms-labels-yes | eael-wpforms-labels-]    ← prefix_class from labels_switch (yes vs empty)
            [placeholder-hide]                                  ← when placeholder_switch != 'yes' (NO SCSS rule)
            [title-description-hide]                            ← when custom_title_description == 'yes' (NO SCSS rule)
            [eael-custom-radio-checkbox]">                      ← when custom_radio_checkbox == 'yes'

  [?] <!-- EA-rendered custom title + description block (replaces WPForms-native) -->
  <div class="eael-wpforms-heading">
    [?] <h3 class="eael-contact-form-title eael-wpforms-title">Title</h3>     ← esc_attr — strips HTML
    [?] <div class="eael-contact-form-description eael-wpforms-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <!-- wpforms_display($id, $show_native_title, $show_native_description) — entirely emitted by WPForms plugin -->
  <div class="wpforms-container wpforms-container-full" id="wpforms-N">
    [?] <div class="wpforms-title">Native Title</div>            ← only when custom_title_description != 'yes' AND form_title == 'yes'
    [?] <div class="wpforms-description">Native Description</div> ← same condition for form_description

    <form id="wpforms-form-N" method="post" class="wpforms-validate wpforms-form">
      <div class="wpforms-field-container">
        <div class="wpforms-field wpforms-field-text">
          <label class="wpforms-field-label" for="…">Name</label>
          <input class="wpforms-field-medium" type="text" name="wpforms[fields][N]">
        </div>
        …
      </div>
      <div class="wpforms-submit-container">
        <button class="wpforms-submit" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>
```

Notes:

- The widget owns only the outer `.eael-contact-form.eael-wpforms` div and optional heading block. Everything inside `<div class="wpforms-container">` is from the WPForms plugin.
- WPForms-native vs EA-custom title/description are **mutually exclusive**: enabling `custom_title_description` forces both `$show_title` and `$show_description` to `false` in the `wpforms_display()` call.
- The labels CSS uses **`display: block`** for shown labels rather than restoring the default state — won't match WPForms' newer flex/grid-based field layouts if they change the default `display` of `.wpforms-field-label`.
- Title is rendered via `esc_attr()` ([line 1497](../../includes/Elements/WpForms.php#L1497)) — same HTML-stripping inconsistency as CF7. Description goes through `wp_kses + parse_text_editor`.
- Three classes (`placeholder-hide`, `title-description-hide`, label `prefix_class`) appear on the wrapper but only the `labels-yes` form has an active SCSS rule.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/WpForms.php#L74) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "WPForms not installed" notice — visible ONLY when `class_exists('\WPForms\WPForms')` returns false |
| `contact_form_list` | SELECT | `0` | Content → WPForms | Form picker; options from `Helper::get_wpforms_list()` with `posts_per_page = -1` |
| `custom_title_description` | SWITCHER | empty | Content → WPForms | Toggle: WPForms-native title/description (off) vs EA-custom rendered (on) |
| `form_title` | SWITCHER | `yes` | Content → WPForms | Visible only when `custom_title_description != 'yes'`; passed to `wpforms_display()` as `$show_title` |
| `form_description` | SWITCHER | `yes` | Content → WPForms | Same; passed as `$show_description` |
| `form_title_custom` | TEXT (dynamic, AI) | empty | Content → WPForms | EA-rendered custom title; visible only when `custom_title_description == 'yes'` |
| `form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → WPForms | EA-rendered custom description; same condition |
| `labels_switch` | SWITCHER (`prefix_class`) | `yes` | Content → WPForms | Adds `eael-wpforms-labels-yes` (visible) or `eael-wpforms-labels-` (hidden) — inverted SCSS default |
| `placeholder_switch` | SWITCHER | `yes` | Content → WPForms | Adds `placeholder-hide` class when off (no SCSS rule; reserved for themes / future state controls) |
| `error_messages` | SELECT (`selectors_dictionary`) | `show` | Content → Errors | Per-field validation messages (`label.wpforms-error`) via `display: block/none !important` |
| `eael_contact_form_alignment` | CHOOSE | — | Style → Form Container | Adds `eael-wpforms-align-<default/left/right/center>` class (flex `justify-content` via SCSS) |
| `eael_contact_form_max_width` | SLIDER (px/em/%) | — | Style → Form Container | `.wpforms-container { max-width; width }` |
| (form container padding, margin, border, border-radius, background, box-shadow) | various | — | Style → Form Container | Standard container styles |
| (title color, typography, margin, alignment) | various | — | Style → Title & Description | Both EA-custom + WPForms-native via selector union (`.eael-contact-form-title, .wpforms-title`) |
| (description color, typography, margin) | various | — | Style → Title & Description | Same |
| (labels color, typography, margin) | various | — | Style → Labels | `.wpforms-field label, .wpforms-field legend` styles |
| (form fields: width, height, padding, text-indent, background, color, border, border-radius, focus state, typography) | various | — | Style → Form Fields | Selectors target `.wpforms-field input:not(...)` (exclude radio/checkbox/submit/button/image/file) + textarea + select |
| `placeholder` color | various | — | Style → Placeholder | Targets `::-webkit-input-placeholder` (Chrome/Safari) — incomplete cross-browser coverage |
| `custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-custom-radio-checkbox` class; gates ~12 size/color/border controls |
| (radio/checkbox: size, color, border, focus, checked state) | various | — | Style → Radio & Checkbox | Custom-styled span replacement; conditional on `custom_radio_checkbox == 'yes'` |
| (errors text color, typography, alignment) | various | — | Style → Errors | `label.wpforms-error` styles |
| (submit button width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `input[type="submit"], button[type="submit"], .wpforms-submit, .wpforms-page-button` |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when class_exists('\WPForms\WPForms') is FALSE
ALL form controls + style sections       → visible when class_exists('\WPForms\WPForms') is TRUE
                                           (entire `else` branch of register_controls)

# Title/description mode
form_title / form_description            → visible when custom_title_description != 'yes' (WPForms-native mode)
form_title_custom / form_description_custom → visible when custom_title_description == 'yes' (EA-custom mode)

# Style → Radio & Checkbox (~12 controls)
radio_checkbox_size / _color / _border / 
checkbox_size / _color / _border / etc.  → conditional on custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on placeholder_switch == 'yes'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls at all
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell to gate). Unique in this category. Extension is via CSS overrides only.

WPForms' own hooks (`wpforms_emails_send`, `wpforms_process_complete`, `wpforms_field_properties`, etc.) flow through the WPForms plugin's hook chain unchanged — third parties listen for them independently of EA.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no EA widget JavaScript file.** The `config.php` entry declares only a CSS dependency; `get_script_depends()` declares the `wpforms-elementor` handle which is registered by the WPForms plugin itself (typically loads `wpforms.js` plus an Elementor-shim that listens to `elementor/frontend/init` to re-initialize WPForms when widgets are re-rendered by Elementor). Form interaction (submit, validation, AJAX, multi-page navigation, conditional logic, file-upload, reCAPTCHA) is entirely handled by WPForms' own JS.

This follows the canonical Form Integration pattern — like CF7, but with an explicit script-dependency declaration so the third-party plugin's Elementor-aware JS gets enqueued reliably.

## Common Issues

### Widget shows "WPForms is not installed/activated"

- **Likely cause:** the WPForms plugin is deactivated or not installed. Both WPForms Lite and WPForms Pro define `\WPForms\WPForms` — either works.
- **Diagnose:** check Plugins → Installed; verify `class_exists('\WPForms\WPForms')` returns true.
- **Fix:** install + activate WPForms (Lite or Pro). The widget's full control panel only appears after activation.

### Form picker shows "Create a Form First" but I have WPForms saved

- **Likely cause:** `get_posts(['post_type' => 'wpforms'])` returned empty — could be all forms in trash, custom CPT filter excluding them, or permission issue.
- **Diagnose:** check WP admin → WPForms → All Forms; verify `post_status = 'publish'`.
- **Fix:** publish at least one WPForms form. The widget doesn't cap query results (`posts_per_page = -1`) so all published forms appear.

### Labels disappear unexpectedly

- **Likely cause:** the SCSS default-state is `display: none` for `.wpforms-field-label` ([wpforms.scss line 42-44](../../src/css/view/wpforms.scss#L42)) — labels are HIDDEN by default and only re-shown when wrapper has `.eael-wpforms-labels-yes`. If `labels_switch` is off, the prefix class is empty (`eael-wpforms-labels-`) and labels stay hidden.
- **Diagnose:** inspect wrapper class — does it have `eael-wpforms-labels-yes`?
- **Fix:** toggle `labels_switch` to Show. Working as designed; the SCSS default is opposite of CF7's.

### Custom title/description doesn't replace native one

- **Likely cause:** `custom_title_description` switcher is off OR `form_title_custom` / `form_description_custom` text fields are empty. The render path at [line 1493-1509](../../includes/Elements/WpForms.php#L1493) checks BOTH the switcher AND non-empty text.
- **Diagnose:** check both the toggle AND the text fields.
- **Fix:** enable `custom_title_description` AND fill in `form_title_custom` / `form_description_custom`. WPForms-native title/description are suppressed automatically via `false` args to `wpforms_display()`.

### Mixed WPForms-native + EA-custom title/description

- **Likely cause:** impossible by design — toggling `custom_title_description` to `yes` forces BOTH booleans in `wpforms_display()` to `false` ([line 1514-1517](../../includes/Elements/WpForms.php#L1514)). They're mutually exclusive.
- **Diagnose:** verify the toggle state.
- **Fix:** pick one mode. If you need both, modify the render method.

### Form submit button styled with EA controls but doesn't react to clicks

- **Likely cause:** WPForms' own JS isn't loaded — possibly because the `wpforms-elementor` script handle wasn't registered (older WPForms version without Elementor integration) or the WPForms plugin failed to load.
- **Diagnose:** browser console — is `wpforms` defined? Network tab — was `wpforms.min.js` loaded?
- **Fix:** update WPForms to a version with Elementor integration (most modern releases). If still not loading, check `wp_register_script('wpforms-elementor', …)` in the plugin's source.

### Placeholder hides in panel but still shows on form

- **Likely cause:** the `placeholder_switch != 'yes'` adds `placeholder-hide` class to the wrapper, but **no SCSS rule** in wpforms.scss targets this class. The toggle effectively does nothing visually.
- **Diagnose:** inspect element — class is present but no `::placeholder { display: none }` (which wouldn't work anyway — placeholders can't be `display`'d).
- **Fix:** edit the WPForms form to remove placeholders, OR add custom CSS via theme: `.placeholder-hide input::placeholder { opacity: 0 }`.

### Field min-height too tall / can't make compact

- **Likely cause:** SCSS line 58 enforces `min-height: 43px` on all input/textarea/select inside `.wpforms-field` — even when the panel's field-height slider is set lower, the `!important` cascade and explicit `min-height` win.
- **Diagnose:** browser DevTools → Computed → check `min-height` source.
- **Fix:** override via custom CSS: `.eael-wpforms .wpforms-container .wpforms-field input { min-height: 0 !important }`. There's no panel control for `min-height`.

## Known Limitations

- **Two un-rendered marker classes** — `placeholder-hide` and `title-description-hide` are written to the wrapper but neither has a SCSS rule in [`wpforms.scss`](../../src/css/view/wpforms.scss). Reserved for themes / future state controls; non-functional today.
- **One un-written SCSS class** — `eael-wpforms-form-button-full-width` ([wpforms.scss line 68-70](../../src/css/view/wpforms.scss#L68)) makes submit button full-width, but no panel control writes it. Dead CSS unless added by theme.
- **Title text rendered with `esc_attr()`** ([line 1497](../../includes/Elements/WpForms.php#L1497)) — strips ALL HTML. Same inconsistency as CF7. Description correctly uses `wp_kses + parse_text_editor`.
- **`min-height: 43px` hardcoded on form fields** ([wpforms.scss line 58](../../src/css/view/wpforms.scss#L58)) — no panel control to override. Tall input boxes can't be made compact without custom CSS.
- **Inverted labels default** — SCSS hides `.wpforms-field-label` by default; only `.eael-wpforms-labels-yes` re-shows them. Sites that disable the EA widget partway (e.g., conditional rendering) get a state where WPForms' native CSS would show labels but the EA SCSS hides them — labels disappear until the wrapper class is restored.
- **`get_script_depends()` returns `['wpforms-elementor']` unconditionally when class exists** — even if the page doesn't actually use this widget's form output (e.g., on a 404 with the widget in a header), WPForms' Elementor JS loads. Wasted bandwidth on irrelevant pages. Asset_Builder mitigates per-page.
- **No frontend AJAX integration** — the widget doesn't subscribe to WPForms' `wpforms-elementor` integration's events or expose form success/failure to the rest of EA. Tabs / accordions containing WPForms don't re-layout after submission.
- **Placeholder color targets only `::-webkit-input-placeholder`** — Firefox / Edge users won't see the configured color. Same limitation as CF7.
- **WPForms-native vs EA-custom title/description are mutually exclusive** — no way to render both. Custom is an outright replacement.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. Forms with dynamic field values (e.g., logged-in user prefills) may stale-cache.
- **No `eael_section_pro` upsell + zero hooks** — unique among form integrations. Saves panel space and complexity, but provides no extension point for Pro or third parties wanting to add per-form controls.
- **WPForms-native title/description style controls share selectors with EA-custom ones** (`.eael-contact-form-title, .wpforms-title`) — styling one affects both. Editing the panel's Title style is global to the rendered title regardless of which mode is active.
