# Caldera Forms Widget

> CSS-styling wrapper around the **Caldera Forms plugin — which was discontinued on April 5, 2022** and is no longer available for download. The widget is kept in EA Lite for backwards-compatibility on sites still running pre-discontinuation Caldera installs. The "plugin not installed" warning in both `register_controls()` and `render()` uses unique copy that tells users the plugin is gone rather than the standard "install + activate" message used by other Form Integrations. Form rendering delegates to `do_shortcode('[caldera_form id="…"]')`.

**Class file:** [`includes/Elements/Caldera_Forms.php`](../../includes/Elements/Caldera_Forms.php)
**Slug:** `caldera-form` (widget id `eael-caldera-form`)
**Public docs:** <https://essential-addons.com/elementor/docs/caldera-forms/>
**Pro-shared:** ❌ No — Lite-only styling for a discontinued third-party plugin. **No `do_action` / `apply_filters` extension hooks** (zero Pro extension surface), no `eael_section_pro` upsell. Same lean profile as WPForms / Ninja / Gravity. Pro doesn't reference this widget.

---

## Overview

Caldera Forms is the only EA widget whose **third-party plugin has been formally discontinued by its maintainer** (April 5, 2022, when CalderaWP wound down). EA Lite retains the widget so legacy sites can keep using their existing Caldera installs without losing the EA styling layer they configured. The Form Integration pattern is otherwise identical to siblings: gate on `class_exists('\Caldera_Forms')`, RAW_HTML warning in panel when missing, form picker via `Helper::get_caldera_form_list()` (which calls `\Caldera_Forms_Forms::get_forms(true, true)` — Caldera's OOP API), render via `do_shortcode('[caldera_form id="…"]')`. **Unique to this widget**: when the plugin is missing, `render()` doesn't just return early — it emits a visible `<center>`-wrapped message into the page, telling users Caldera is gone. Other form widgets render nothing when their plugin is absent. The widget has no JavaScript at all (no view JS, no edit JS) — Caldera Forms shipped its own jQuery-based submission scripts.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker, custom title/description toggle | ✅ | ✅ |
| Show/hide labels (via `eael-caldera-form-labels-yes/-` prefix class) | ✅ | ✅ |
| Show/hide placeholder, custom radio/checkbox | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / errors / submit button) | ✅ | ✅ |
| Front-end visible "plugin discontinued" message (in `render()` when `\Caldera_Forms` class missing) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Caldera_Forms.php`](../../includes/Elements/Caldera_Forms.php) | PHP widget class (1549 lines) — controls, `render()`, `class_exists('\Caldera_Forms')` gate, front-end-visible discontinuation notice |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L622) | `get_caldera_form_list()` — `\Caldera_Forms_Forms::get_forms(true, true)` (Caldera's OOP API; two booleans for include-drafts / active-only) |
| [`src/css/view/caldera-form.scss`](../../src/css/view/caldera-form.scss) | Source styles (56 lines — smallest Form Integration SCSS) — alignment via `text-align`, labels show/hide with **inverted-default** pattern, `.caldera-grid` and Bootstrap-style `.form-group .control-label` selectors |
| [`config.php`](../../config.php#L867) entry `'caldera-form'` | `Asset_Builder` dependency declaration: **CSS only** — `caldera-form.min.css`. No widget JS at all (no view, no edit) |
| `assets/front-end/css/view/caldera-form.min.css` | Built output (do not edit) |
| (no widget JS files) | — Form submission / validation entirely from Caldera Forms' own jQuery scripts (when the plugin is installed) |

## Architecture

- **Plugin-gate on `class_exists('\Caldera_Forms')`** in both `register_controls()` and `render()` ([line 67](../../includes/Elements/Caldera_Forms.php#L67), [line 1490](../../includes/Elements/Caldera_Forms.php#L1490)). Same canonical Form Integration gate pattern.
- **Discontinuation notice is rendered to the page, not just the panel** — when the plugin is missing, `render()` at [line 1492](../../includes/Elements/Caldera_Forms.php#L1492) emits `<center>Caldera Forms has been closed as of April 5, 2022 and is not available for download. You can try the other Form plugins instead</center>` before returning. **No other Form Integration in EA does this** — CF7, WPForms, Ninja, Gravity all just `return;` silently when their plugin is missing. The visible message is meant to alert site visitors (not just admins) on legacy sites where the plugin was deactivated post-discontinuation. **`<center>` is a deprecated HTML5 element** — used here anyway for legacy compatibility.
- **Panel warning copy is unique** — the RAW_HTML warning in `register_controls()` ([line 79](../../includes/Elements/Caldera_Forms.php#L79)) reads "has been closed as of April 5, 2022 and is not available for download. You can try the other Form plugins instead" — versus the standard "is not installed/activated, please install and activate first" used by every other Form Integration. **A documented sunset notice in production code.**
- **Form list via Caldera's OOP API** — `Helper::get_caldera_form_list()` at [Helper line 627](../../includes/Classes/Helper.php#L627) calls `\Caldera_Forms_Forms::get_forms(true, true)` and iterates `$form['ID']` / `$form['name']`. Caldera stores forms in `wp_options` (serialized array), not CPT or custom tables — different from every other form plugin EA integrates with.
- **Render via `do_shortcode('[caldera_form id="…"]')`** at [line 1544](../../includes/Elements/Caldera_Forms.php#L1544) — same shortcode pattern as CF7 (matching pattern: `[<plugin>_form id="…"]`). Unlike WPForms (`wpforms_display()` function) and Gravity (`gravity_form()` function), Caldera works through pure shortcode delegation.
- **Inverted-default labels SCSS** — `.control-label { display: none }` by default at [SCSS line 16-18](../../src/css/view/caldera-form.scss#L16); `.eael-caldera-form-labels-yes .control-label { display: block }` re-shows. Same `prefix_class => 'eael-caldera-form-labels-'` pattern as WPForms — `yes` suffix shows labels, empty suffix hides them. Caldera uses Bootstrap-style `.control-label` class (carried over from Caldera Forms' Bootstrap-flavored markup).
- **No `eael_section_pro` upsell, no extension hooks** — confirmed via `grep`: zero `do_action`, zero `apply_filters`, zero `eael_section_pro`. Same lean profile as WPForms / NinjaForms / GravityForms. Unique-as-a-group: 4 of the 9 Form Integrations have zero hooks and no upsell.
- **Three dead button-alignment classes** in SCSS — `eael-caldera-form-button-center` (lines 24-28), `eael-caldera-form-button-right` (lines 30-33), `eael-caldera-form-button-left` (line 53-56 inside `.rtl`) — write CSS rules but **no panel control writes these classes**. Legacy from an older version of the widget. Same dead-class pattern as siblings.
- **One dead full-width class** — `eael-caldera-form-button-full-width` ([SCSS line 47-50](../../src/css/view/caldera-form.scss#L47)) makes submit button full-width, but no panel control writes it. Dead CSS.
- **`placeholder-hide` and `title-description-hide` are dead wrapper classes** — both written by `render()` ([line 1505, 1509](../../includes/Elements/Caldera_Forms.php#L1505)) but **neither has a SCSS rule** in caldera-form.scss. Functional no-ops on both fronts (CF7's `title-description-hide` was also dead in some siblings; here both are dead).
- **`int-tel-input` selector hint** ([SCSS line 35-37](../../src/css/view/caldera-form.scss#L35)) — `display: inherit` workaround for international telephone input plugins integrated with Caldera. Edge case for users with international phone fields.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active.
- **4-class alignment via if/elseif chain** ([line 1515-1523](../../includes/Elements/Caldera_Forms.php#L1515)) — same pattern as Ninja and Gravity.

## Render Output

When the plugin is **missing**:

```html
<!-- render() emits this when class_exists('\Caldera_Forms') is false -->
<center>
  <strong>Caldera Forms</strong> has been closed as of April 5, 2022 and is not
  available for download. You can try the other Form plugins instead
</center>
```

When the plugin is **active**:

```html
<div class="eael-contact-form
            eael-caldera-form
            eael-contact-form-align-<default|left|right|center>
            [eael-caldera-form-labels-yes | eael-caldera-form-labels-]   ← prefix_class from labels_switch
            [placeholder-hide]                                            ← when placeholder_switch != 'yes' (NO SCSS rule)
            [title-description-hide]                                      ← when custom_title_description == 'yes' (NO SCSS rule)
            [eael-custom-radio-checkbox]">                                ← when custom_radio_checkbox == 'yes'

  [?] <!-- EA-rendered custom title + description block -->
  <div class="eael-caldera-form-heading">
    [?] <h3 class="eael-contact-form-title eael-caldera-form-title">Title</h3>     ← esc_attr strips HTML
    [?] <div class="eael-contact-form-description eael-caldera-form-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <!-- do_shortcode('[caldera_form id="…"]') — entirely emitted by Caldera Forms plugin -->
  <div class="caldera-grid" id="caldera_form_<form-id>">
    <form class="caldera_forms_form" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label class="control-label">Name <span class="field_required">*</span></label>
        <input type="text" class="form-control" name="name">
      </div>
      …
      <div class="form-group">
        <input type="submit" class="btn btn-default" value="Send">
      </div>
    </form>
  </div>
</div>
```

Notes:

- The widget owns only the outer `.eael-contact-form.eael-caldera-form` div and optional heading block. Everything inside `.caldera-grid` comes from Caldera Forms' shortcode.
- Caldera uses Bootstrap-flavored markup (`.form-group`, `.control-label`, `.form-control`, `.btn`) — older form-styling conventions. EA's SCSS targets those selectors.
- The visible discontinuation `<center>` notice is **server-rendered HTML** that appears on the live page — not a console warning or admin notice. Site visitors see it.
- Title rendered via `esc_attr()` strips HTML — same inconsistency with `wp_kses`-filtered description as all other Form Integrations.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Caldera_Forms.php#L62) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | **Unique copy**: "Caldera Forms has been closed as of April 5, 2022..." (not the standard "install + activate" message) |
| `contact_form_list` | SELECT | `0` | Content → Caldera Forms | Form picker; options from `Helper::get_caldera_form_list()` via Caldera's OOP API |
| `custom_title_description` | SWITCHER | empty | Content → Caldera Forms | Toggle EA-custom title/description (Caldera has no native title/description like other plugins) |
| `form_title_custom` | TEXT (dynamic, AI) | empty | Content → Caldera Forms | EA-rendered custom title; visible only when `custom_title_description == 'yes'` |
| `form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → Caldera Forms | EA-rendered custom description; same condition |
| `labels_switch` | SWITCHER (`prefix_class`) | `yes` | Content → Caldera Forms | Adds `eael-caldera-form-labels-yes` (visible) or `eael-caldera-form-labels-` (hidden) — inverted SCSS default |
| `placeholder_switch` | SWITCHER | `yes` | Content → Caldera Forms | Adds `placeholder-hide` class when off (NO SCSS rule; functional no-op) |
| `error_messages` | SELECT (`selectors_dictionary`) | `show` | Content → Errors | Caldera's `.parsley-required` validation tips visibility |
| `eael_contact_form_alignment` | CHOOSE | — | Style → Form Container | Adds `eael-contact-form-align-<default/left/right/center>` class |
| `eael_contact_form_max_width` | SLIDER | — | Style → Form Container | Container max-width |
| (form container padding, margin, border, border-radius, background, box-shadow) | various | — | Style → Form Container | Standard styles |
| (title color, typography, margin, alignment) | various | — | Style → Title & Description | Only the EA-custom title is styled (Caldera has no native title to style) — selector targets `.eael-caldera-form-title` only |
| (description color, typography, margin) | various | — | Style → Title & Description | Same |
| (labels color, typography, margin) | various | — | Style → Labels | `.form-group label` (Bootstrap selector) |
| (form fields: width, height, padding, text-indent, background, color, border, focus, typography) | various | — | Style → Form Fields | `.eael-caldera-form input:not(...)` + `.form-group textarea` + `.form-group select` |
| (placeholder color, typography) | various | — | Style → Placeholder | `::-webkit-input-placeholder` only |
| `custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-custom-radio-checkbox`; SCSS replaces `.caldera-grid` radio/checkbox |
| (radio/checkbox: size, color, border, focus, checked state) | various | — | Style → Radio & Checkbox | Custom-styled replacement; conditional on `custom_radio_checkbox == 'yes'` |
| (errors text color, typography, alignment) | various | — | Style → Errors | `.parsley-required` styles (Caldera uses Parsley.js for validation) |
| (submit button width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.form-group input[type="submit"], .form-group input[type="button"]` |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when class_exists('\Caldera_Forms') is FALSE
ALL form controls + style sections       → visible when class_exists('\Caldera_Forms') is TRUE

# Title mode
form_title_custom / form_description_custom → visible when custom_title_description == 'yes'

# Style → Radio & Checkbox (~12 controls)
radio_checkbox_size / _color / _border / ... → conditional on custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on placeholder_switch == 'yes'

# Title & Description style section is gated on custom_title_description == 'yes'
(many style controls inside this section) → conditional on custom_title_description == 'yes'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls at all
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell). Same lean profile as WPForms / NinjaForms / GravityForms.

Caldera Forms' own hooks (`caldera_forms_render_field_template`, `caldera_forms_submit_complete`, `caldera_forms_pre_render_form`, etc.) flow through Caldera's plugin hook chain — third parties listen for them independently. With the plugin discontinued, hook stability is no longer guaranteed.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no EA widget JavaScript at all.** No view JS, no edit JS. The `config.php` entry declares only a CSS dependency. Form interaction (submit, validation, AJAX, conditional logic, multi-page, file-upload) was handled by Caldera Forms' own jQuery + Parsley.js scripts — which loaded independently when the plugin was active.

This is the leanest Form Integration in EA — even smaller than CF7 (which has the same no-JS profile but more PHP controls). Caldera's 56-line SCSS is the smallest in the category.

## Common Issues

### Widget shows "Caldera Forms has been closed as of April 5, 2022..." instead of a form

- **Likely cause:** the Caldera Forms plugin is not installed/activated. Since the plugin can no longer be downloaded from WordPress.org or CalderaWP, this is increasingly common as sites are migrated or the plugin file gets cleaned up.
- **Diagnose:** check Plugins → Installed; verify `class_exists('\Caldera_Forms')`.
- **Fix:** there is **no fix** if you don't already have the plugin installed — Caldera Forms is gone. Options: (a) keep an archived copy of the plugin folder if you have one and re-install manually; (b) switch to one of the other 8 Form Integrations (WPForms / NinjaForms / GravityForms / Fluent Forms / etc.); (c) leave the widget in place — site visitors will see the visible notice instead of a broken form.

### The discontinuation notice appears as plain text instead of centered

- **Likely cause:** browser doesn't render `<center>` consistently in HTML5 mode, or a theme/plugin's reset CSS targets `center` to remove its default `text-align`.
- **Diagnose:** browser DevTools — does `<center>` have computed `text-align: center`?
- **Fix:** add custom CSS: `.eael-caldera-form-widget center { text-align: center; }`. Or ignore — the message is still readable.

### Multiple Caldera widgets on the same page

- **Likely cause:** legacy from when the plugin was active. Should work fine if Caldera is still installed.
- **Diagnose:** confirm the plugin is active.
- **Fix:** working as designed — each widget shortcode is independent. Caldera's own JS handled multi-form-per-page correctly.

### Form picker shows "Create a Form First" but I have Caldera Forms saved

- **Likely cause:** `\Caldera_Forms_Forms::get_forms(true, true)` returned empty. Caldera stores forms in `wp_options` (serialized). Database corruption or option-table cleanup could remove them.
- **Diagnose:** check `wp_options` for entries with `option_name LIKE '_caldera_forms_%'`.
- **Fix:** restore from a backup if the options were cleared. Manual re-creation of forms requires the plugin's admin UI to be functional.

### Custom title/description doesn't show

- **Likely cause:** the switcher is on but the text fields are empty.
- **Diagnose:** check `form_title_custom` and `form_description_custom` aren't blank.
- **Fix:** fill in text. Caldera Forms itself has no native title/description, so the EA-custom title is the only available option for showing a header above the form.

### Submit button alignment doesn't change

- **Likely cause:** SCSS provides three `eael-caldera-form-button-*` classes ([SCSS line 24-33, 53-56](../../src/css/view/caldera-form.scss#L24)) but no panel control writes them. Legacy dead CSS.
- **Diagnose:** inspect wrapper class.
- **Fix:** working as designed; alignment is at form-container level. Submit-button-specific alignment requires custom CSS.

### Labels stay visible even when toggle is set to Hide

- **Likely cause:** the SCSS default-state `.control-label { display: none }` at [SCSS line 16-18](../../src/css/view/caldera-form.scss#L16) requires the wrapper to be `.eael-caldera-form` (which it always is on this widget). If the wrapper class is somehow modified or stripped, the rule doesn't match and labels stay visible.
- **Diagnose:** inspect wrapper class — does it include `eael-caldera-form`?
- **Fix:** working as designed; if labels are unexpectedly visible, the prefix_class `eael-caldera-form-labels-yes` is overriding via `.eael-caldera-form-labels-yes .control-label { display: block }`. Toggle `labels_switch` to off (which produces the empty suffix `eael-caldera-form-labels-` and labels hide).

## Known Limitations

- **Caldera Forms is a discontinued plugin** (April 5, 2022 — CalderaWP wound down operations). No further security updates, bug fixes, or compatibility patches will land. **EA Lite retains the widget for legacy users only.** Recommend migrating to an actively-maintained form plugin.
- **Front-end-visible `<center>` notice** uses a deprecated HTML5 element ([line 1492](../../includes/Elements/Caldera_Forms.php#L1492)). Browsers still render it, but it'll eventually be removed from HTML.
- **Custom title rendered via `esc_attr()`** ([line 1531](../../includes/Elements/Caldera_Forms.php#L1531)) — strips ALL HTML. Same inconsistency as CF7, WPForms, NinjaForms, GravityForms.
- **Two dead wrapper classes** — `placeholder-hide` and `title-description-hide` are written by render() but neither has a SCSS rule. Functional no-ops.
- **Four dead SCSS button classes** — `eael-caldera-form-button-center/right/left/full-width` exist in SCSS but no panel control writes them. ~50 bytes of wasted CSS.
- **No `eael_section_pro` upsell + zero hooks** — same as WPForms / NinjaForms / GravityForms. No extension point.
- **`int-tel-input` selector** is an edge-case workaround for the (also unmaintained) International Telephone Input plugin's integration with Caldera. Stale dependency on stale dependency.
- **Bootstrap-flavored markup assumption** — SCSS targets `.form-group`, `.control-label`, `.form-control` — Caldera Forms used Bootstrap 3 conventions. Sites that loaded Bootstrap 4/5 may have layout conflicts.
- **No frontend AJAX integration with EA** — submission success/failure doesn't broadcast `eael.hooks.doAction(…)`. Tabs / accordions containing Caldera won't re-layout.
- **Placeholder color targets only `::-webkit-input-placeholder`** — Firefox/Edge unstyled. Same limitation as siblings.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active. Caldera nonces are page-cache-aware.
- **No way to opt out of the front-end discontinuation notice** — if Caldera is missing and you want a silent fail (just empty widget), there's no panel toggle. Has to be filtered or CSS-hidden externally.
- **`Helper::get_caldera_form_list()` uses `Caldera_Forms_Forms::get_forms(true, true)`** — second boolean argument's semantics are not documented in the helper. Likely "include drafts" + "active forms only" but with the plugin discontinued, behavior verification requires reading legacy source.
