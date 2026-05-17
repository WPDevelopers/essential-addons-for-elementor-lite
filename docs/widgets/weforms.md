# weForms Widget

> CSS-styling wrapper around the weForms plugin (a fork of WP User Frontend by WeDevs). Picks a form via a Select control populated by `Helper::get_weform_list()` which queries the legacy `wpuf_contact_form` post type (weForms inherited the CPT name from WPUF but never renamed it). Renders via `do_shortcode('[weforms id="…"]')`. **The leanest Form Integration in the catalog** — no `labels_switch`, no `placeholder_switch`, no `custom_title_description`, no `custom_radio_checkbox`, no `error_messages` controls. Only 4 style sections (Form Container / Field Styles / Typography / Submit Button) versus 8-12 for siblings. **34-line SCSS, zero JS, 807-line PHP** — half the size of the next-smallest Form Integration.

**Class file:** [`includes/Elements/WeForms.php`](../../includes/Elements/WeForms.php)
**Slug:** `weforms` (widget id `eael-weform`) ⚠ **widget id is singular `eael-weform` while config slug is plural `weforms`** — minor mismatch, not the major class as Filterable_Gallery / Ninja
**Public docs:** <https://essential-addons.com/elementor/docs/weforms/>
**Pro-shared:** ❌ No widget-specific Pro extension — Pro doesn't add hooks here. The `eael_section_pro` upsell panel is the only Pro-related surface (matches Contact_Form_7's pattern). weForms' own Pro features are independent of EA Pro.

---

## Overview

weForms is the smallest Form Integration in EA Lite — by file size, control count, and rendered output complexity. It's a near-minimal wrapper: a single Select picker, four style sections, and a one-line shortcode delegation in `render()`. The widget does NOT include the canonical Form Integration controls that CF7 / WPForms / Ninja / Gravity / Caldera / FluentForm all share — there's no labels toggle, no placeholder toggle, no EA-custom title/description block, no custom-radio-checkbox styling toggle, and no error-message visibility control. **Why so lean?** weForms itself is largely dormant — last meaningful release was 2020, and the plugin is nominally still available but receives only sporadic security patches. EA's widget reflects that low maintenance investment: it covers the basics (form picker + container/field/typography styling) and trusts weForms' own CSS for everything else. Two `prefix_class` switchers — one for form alignment, one for **submit-button alignment** (rare among Form Integrations; most siblings have dead button-alignment SCSS classes that no panel control writes; weForms is the exception).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker (queries legacy `wpuf_contact_form` post type) | ✅ | ✅ |
| Form container styling (background, alignment, width, max-width, margin, padding, border-radius, border, box-shadow) | ✅ | ✅ |
| Form field styling (input/textarea backgrounds, padding, margin, border-radius, focus state, focus border) | ✅ | ✅ |
| Typography (label, field, placeholder colors + font groups) | ✅ | ✅ |
| Form alignment + button alignment via `prefix_class` switchers | ✅ | ✅ |
| Show/hide labels (`labels_switch`) | ❌ — control doesn't exist in this widget | — |
| Show/hide placeholder (`placeholder_switch`) | ❌ — control doesn't exist | — |
| EA-custom title/description block | ❌ — control doesn't exist | — |
| Custom radio/checkbox styling toggle | ❌ — control doesn't exist | — |
| Error message visibility control | ❌ — control doesn't exist | — |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/WeForms.php`](../../includes/Elements/WeForms.php) | PHP widget class (807 lines — half the size of the next-smallest Form Integration) — controls (4 style sections), `render()` (12 lines), `function_exists('WeForms')` gate |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L569) | `get_weform_list()` — `get_posts(['post_type' => 'wpuf_contact_form', 'showposts' => 999])` — uses **legacy WPUF post type name** that weForms inherited from WP User Frontend |
| [`src/css/view/weforms.scss`](../../src/css/view/weforms.scss) | Source styles (**34 lines** — smallest Form Integration SCSS) — input/textarea padding normalization, button-alignment via `eael-contact-form-btn-align-*` classes, max-width on `wpuf-fields` inputs |
| [`config.php`](../../config.php#L812) entry `'weforms'` | `Asset_Builder` dependency declaration: **CSS only** — `weforms.min.css`. No widget JS, no `get_script_depends()` |
| `assets/front-end/css/view/weforms.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form submission / validation / AJAX entirely from weForms' own scripts |

## Architecture

- **Plugin-gate via `function_exists('WeForms')`** in both `register_controls()` ([line 70](../../includes/Elements/WeForms.php#L70)) and `render()` ([line 795](../../includes/Elements/WeForms.php#L795)) — consistent gate. `WeForms` is both a class AND a function (function returns singleton, like `Ninja_Forms()`); the `function_exists` check works against either.
- **Form list uses the legacy `wpuf_contact_form` post type** at [Helper line 572](../../includes/Classes/Helper.php#L572) — weForms is a fork of WP User Frontend (WPUF) and kept the original CPT name to preserve user data on upgrade. The hardcoded `showposts = 999` cap is the same brittleness as CF7's helper. The control id `wpuf_contact_form` ([line 97](../../includes/Elements/WeForms.php#L97)) similarly preserves the WPUF name — renaming would break saved widget data.
- **Render is the simplest in the category** at [line 793-806](../../includes/Elements/WeForms.php#L793) — 12 lines total. No EA-custom title/description block, no toggle classes for labels/placeholder/title-description, no custom-radio-checkbox class. Just a `<div class="eael-weform-container">` wrapping the shortcode. **No `<style>` blocks emitted, no `ob_start` capture, no regex post-processing** — pure delegation.
- **Two `prefix_class` switchers for alignment** — `eael_weform_alignment` writes `eael-contact-form-align-<default|left|right|center>` to the wrapper at [line 182](../../includes/Elements/WeForms.php#L182), and a separate button-alignment switcher writes `eael-contact-form-btn-align-<…>` at [line 652](../../includes/Elements/WeForms.php#L652). **The button-alignment control is unique to this widget** — the SCSS classes `eael-contact-form-btn-align-*` exist as dead CSS in CF7 / Caldera / NinjaForms / GravityForms but no panel control writes them; here they're actively wired and used at [SCSS line 7-22](../../src/css/view/weforms.scss#L7).
- **Has `eael_section_pro` upsell** at [line 110](../../includes/Elements/WeForms.php#L110) — only Form Integration besides Contact_Form_7 to ship the canonical upsell panel. The other 5 form widgets (WPForms / Ninja / Gravity / Caldera / FluentForm) all skip the upsell.
- **Widget id ≠ slug** — `get_name()` returns `eael-weform` (singular) but config slug is `weforms` (plural). Cosmetic mismatch — not as severe as Filterable_Gallery (`eael-filterable-gallery` vs `filter-gallery`) or Ninja (`eael-ninja` vs `ninja-form`), but worth flagging.
- **Widget title uses lowercase 'w'** — `esc_html__('weForm', …)` at [line 28](../../includes/Elements/WeForms.php#L28) — branding quirk that follows weDevs' weForms / weMail / weDocs naming. Singular ("weForm") not plural ("weForms") — mismatched with the plugin name.
- **Missing canonical controls** — `grep` for `labels_switch`, `placeholder_switch`, `custom_title_description`, `custom_radio_checkbox`, `error_messages` returns ZERO matches. Every other Form Integration in EA has at least labels + placeholder + custom title/description. The omission is consistent: no SCSS modifier rules, no toggle classes, no panel sections.
- **Selectors target both EA wrapper class and legacy `.wpuf-form` class hierarchy** — SCSS at [line 24-34](../../src/css/view/weforms.scss#L24) uses `.eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"]` (etc.) reflecting that weForms inherited WPUF's markup conventions: a `<ul class="wpuf-form">` wrapping `<li>` field rows with `.wpuf-fields` inner divs.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active.

## Render Output

```html
<div class="eael-contact-form-align-<default|left|right|center>            ← wrapper alignment via prefix_class
            eael-contact-form-btn-align-<default|left|right|center>">      ← button alignment via prefix_class

  <div class="eael-weform-container">

    <!-- do_shortcode('[weforms id="…"]') — entirely emitted by weForms plugin.
         weForms inherited WPUF's markup: <ul class="wpuf-form"> wrapping <li> field rows. -->
    <form action="…" method="post" class="wpuf-form-add wpuf-form" id="wpuf-form-…">
      <ul class="wpuf-form form-label-above">
        <li class="wpuf-el text">
          <div class="wpuf-label">
            <label>Name <span class="required">*</span></label>
          </div>
          <div class="wpuf-fields">
            <input id="…" type="text" class="textfield" data-required="yes" data-type="text" name="…">
          </div>
        </li>
        …
        <li class="wpuf-submit">
          <input type="submit" name="submit" value="Send">
        </li>
      </ul>
    </form>
  </div>
</div>
```

Notes:

- The widget owns only `.eael-weform-container` and the two outer `prefix_class` classes (alignment + button-alignment). Everything inside `<form class="wpuf-form">` is from weForms.
- The two `prefix_class` classes appear on the **widget root** (Elementor's outer wrapper) — not on `.eael-weform-container`. SCSS selectors like `.eael-contact-form-btn-align-center .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]` exploit this descendant relationship.
- **No EA-rendered title/description block** — unique among Form Integrations; if you need a heading above the form, use a separate Heading widget.
- weForms uses Bootstrap-flavored markup (`.required`, `.label-above`) — same family as Caldera's selector conventions.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/WeForms.php#L67) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "weForms not installed" notice when `function_exists('WeForms')` returns false |
| `wpuf_contact_form` | SELECT | `0` | Content → Select Form | Form picker; **legacy control id** preserved from WPUF lineage; options from `Helper::get_weform_list()` (queries `wpuf_contact_form` CPT) |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| `eael_weform_background` | COLOR | — | Style → Form Container | `.eael-weform-container` background |
| `eael_weform_alignment` | CHOOSE (`prefix_class`, responsive) | `default` | Style → Form Container | Adds `eael-contact-form-align-<value>` to widget root |
| `eael_weform_width` / `_max_width` | SLIDER (px/em/%, responsive) | — | Style → Form Container | Width + max-width on `.eael-weform-container` |
| `eael_weform_margin` / `_padding` / `_border_radius` (DIMENSIONS) | various | — | Style → Form Container | Standard container styles |
| Form Container border + box shadow | GROUP | — | Style → Form Container | `Group_Control_Border`, `Group_Control_Box_Shadow` |
| `eael_weform_input_background` | COLOR | — | Style → Form Field Styles | Input/textarea/select background |
| `eael_weform_input_width` / `_textarea_width` | SLIDER | — | Style → Form Field Styles | Per-input + per-textarea widths |
| `eael_weform_input_padding` / `_margin` / `_border_radius` | DIMENSIONS | — | Style → Form Field Styles | Field box model |
| `eael_weform_input_border` (group) / `eael_weform_input_focus_border` | GROUP | — | Style → Form Field Styles | Border + focus state border |
| `eael_weform_focus_heading` / `eael_weform_label_style_heading` | HEADING | — | Style → Form Field Styles | Visual section dividers in panel |
| `eael_weform_label_margin` | DIMENSIONS | — | Style → Form Field Styles | Label margins |
| `eael_weform_label_color` / `_field_color` / `_placeholder_color` | COLOR | — | Style → Typography | Label / input value / placeholder colors |
| `eael_weform_label_heading` (group typography for label) | HEADING | — | Style → Typography | Visual divider |
| (label / field / placeholder typography group) | GROUP | — | Style → Typography | Font family/size/weight per element type |
| (button alignment switch with `prefix_class`) | CHOOSE | — | Style → Submit Button | Adds `eael-contact-form-btn-align-<value>` to widget root |
| (submit button: width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.wpuf-submit input[type="submit"]` styles |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when function_exists('WeForms') is FALSE
ALL form controls + style sections       → visible when function_exists('WeForms') is TRUE

# Pro upsell
eael_section_pro / eael_control_get_pro  → visible when Pro plugin is NOT active

# NO labels_switch, NO placeholder_switch, NO custom_title_description, NO custom_radio_checkbox, NO error_messages
# — entire conditional-display surface that other Form Integrations expose is absent here.
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the `eael_section_pro` upsell when Pro is active. Only `apply_filters` call in the entire 807-line file. |

The widget emits **no extension hooks** (no `do_action`). All form processing flows through weForms' own hook chain — `weforms_before_form_submit`, `weforms_form_render_pre`, etc. — which third parties listen for independently.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no widget JavaScript.** The `config.php` entry declares only a CSS dependency; no `get_script_depends()` either. Form interaction (submit, validation, AJAX, file-upload, multi-step navigation) is entirely handled by weForms' own JS, which loads independently via weForms' `wp_enqueue_scripts` hook when the plugin detects its shortcode in page content.

## Common Issues

### Widget shows "weForms is not installed/activated"

- **Likely cause:** the weForms plugin is deactivated or not installed.
- **Diagnose:** check Plugins → Installed; verify `function_exists('WeForms')` returns true.
- **Fix:** install + activate weForms. Note that weForms has been in low-maintenance mode since 2020; consider migrating to an actively-developed alternative if starting fresh.

### Form picker is empty / "Create a Form First"

- **Likely cause:** no posts of type `wpuf_contact_form` exist. weForms inherited the CPT name from WP User Frontend; in some upgrade scenarios from old WPUF to new weForms, the CPT was migrated to a different name.
- **Diagnose:** WP admin → weForms → All Forms; manually query `SELECT ID, post_title FROM wp_posts WHERE post_type = 'wpuf_contact_form' AND post_status = 'publish'`.
- **Fix:** create at least one form in weForms. The widget queries the WPUF-legacy CPT directly.

### Labels can't be hidden

- **Likely cause:** unlike every other Form Integration, weForms widget has **no `labels_switch` control**. Label visibility is controlled entirely by weForms' own form template settings (Form → Settings → Form Settings → Label Position).
- **Diagnose:** check the panel — there's no Labels section.
- **Fix:** edit the form in weForms admin to set `Label Position: Hidden`. Or add custom CSS: `.eael-weform-container .wpuf-label { display: none }`.

### Submit button alignment doesn't change despite the toggle

- **Likely cause:** the `eael-contact-form-btn-align-*` class is added to the WIDGET ROOT (Elementor's outer wrapper), not the `.eael-weform-container` div. SCSS selectors expect `.eael-contact-form-btn-align-center .eael-weform-container ul.wpuf-form .wpuf-submit` — if a custom theme strips the widget root class, alignment breaks.
- **Diagnose:** browser DevTools — does the widget's outer `.elementor-widget-eael-weform` have the alignment class?
- **Fix:** verify the class is present on the outer wrapper. Themes shouldn't strip Elementor widget classes.

### EA-custom title above the form doesn't work

- **Likely cause:** weForms widget doesn't have a `custom_title_description` control like other Form Integrations. By design, EA defers all title rendering to weForms' own form-level title settings.
- **Diagnose:** check the panel — there's no Title & Description section.
- **Fix:** use a separate Elementor Heading widget above the weForms widget. Or set the title in weForms' form-edit screen.

### Field styling doesn't apply consistently across field types

- **Likely cause:** SCSS selectors are explicit: `input[type="text"]`, `input[type="password"]`, `input[type="email"]`, `input[type="url"]`, `input[type="number"]`, `textarea`. Date pickers, file inputs, color pickers, range sliders, and HTML5 `tel` / `search` types are NOT covered.
- **Diagnose:** browser DevTools — does the panel-set background/padding rule apply to your specific input?
- **Fix:** add custom CSS for missing input types, OR keep the form's field types limited to the supported set.

### Plugin notice in panel even though weForms IS installed

- **Likely cause:** weForms plugin is active but the `WeForms` PHP function isn't yet defined at the moment EA's `register_controls()` runs (load-order issue). Rare; usually only happens when another plugin defers weForms' load.
- **Diagnose:** check `function_exists('WeForms')` from a `wp_loaded` action vs `init`.
- **Fix:** ensure weForms loads at standard plugin priority. If you've custom-deferred it, undo the deferral.

## Known Limitations

- **No labels / placeholder / custom title / custom radio-checkbox / error-message controls** — unique among the 7 Form Integrations documented so far. Users coming from CF7 / WPForms / Ninja / Gravity / Caldera / FluentForm will find the panel surprisingly bare.
- **Widget id `eael-weform` (singular) ≠ config slug `weforms` (plural)** — minor inconsistency. Asset_Builder uses the slug; JS handlers (none in this widget) would target the id.
- **Widget title is `weForm` (singular, lowercase 'w')** — three-way inconsistency with the slug (plural), the plugin name (plural), and standard EA capitalization conventions.
- **Control id `wpuf_contact_form`** preserves WPUF lineage — renaming would break saved widget data on legacy installs.
- **`Helper::get_weform_list()` hardcodes `showposts = 999`** ([Helper line 573](../../includes/Classes/Helper.php#L573)) — sites with 1000+ weForms would lose the last form from the picker. Same brittleness as CF7's helper. `posts_per_page = -1` would be more correct.
- **weForms plugin is in low-maintenance mode** — last meaningful release was 2020. Consider migrating to an actively-maintained alternative (Fluent Forms is from the same vendor and IS actively developed).
- **Field styling targets a hardcoded list of input types** — date, file, color, range, tel, search not styled.
- **No frontend AJAX integration with EA** — submission success/failure doesn't broadcast `eael.hooks.doAction(…)`. Tabs / accordions containing weForms won't re-layout after submission.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active.
- **No widget-specific Pro extension surface** — `do_action` count is zero. The only Pro-related code is the `eael_section_pro` upsell. weForms-specific Pro features (if EA Pro ever shipped any) would need to extend the widget by class hierarchy, not via hooks.
- **`get_style_depends()` not declared** — relies on weForms' own CSS being enqueued by the plugin. If weForms changes its enqueue logic, EA's selectors target classes that may not have weForms' base styles applied.
- **`do_shortcode()` round-trip** — third parties wanting to alter weForms' form HTML must hook weForms directly; EA provides no integration shim.
