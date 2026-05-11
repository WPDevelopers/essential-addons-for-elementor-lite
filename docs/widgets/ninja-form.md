# Ninja Forms Widget

> CSS-styling wrapper around the Ninja Forms plugin. Picks a form via a Select control (populated by `Ninja_Forms()->form()->get_forms()` — the plugin's OOP API, not a CPT query), renders via `Ninja_Forms()->display($form_id)` with `ob_start/ob_get_clean` capture plus a `preg_replace` workaround that un-escapes Ninja Forms' "required" asterisk span (a fix for a multi-form-per-page bug in NF itself). EA contributes the styling layer; Ninja Forms' Backbone-based frontend renders the actual form fields. **Widget id is `eael-ninja` while config slug is `ninja-form`** — divergence between the two for legacy reasons.

**Class file:** [`includes/Elements/NinjaForms.php`](../../includes/Elements/NinjaForms.php)
**Slug:** `ninja-form` (widget id `eael-ninja`) ⚠ **widget id differs from config slug** — see [`docs/architecture/asset-loading.md § Common Pitfalls`](../architecture/asset-loading.md)
**Public docs:** <https://essential-addons.com/elementor/docs/ninja-forms/>
**Pro-shared:** ❌ No — Lite-only styling. **No `do_action` / `apply_filters` extension hooks** (zero Pro extension surface) and the `eael_section_pro` upsell panel is absent. Pro doesn't reference this widget. Same lean profile as WPForms.

---

## Overview

Ninja Forms follows the Form Integration pattern but with two quirks unique to this widget: (1) `register_controls()` gates on `function_exists('Ninja_Forms')` while `render()` gates on `class_exists('Ninja_Forms')` — the symbol is **both** a global function and a class (the function returns the singleton instance), so both checks pass when the plugin is active, but the inconsistency is unnecessary; (2) the render path captures `Ninja_Forms()->display()` output via output buffering, then runs a `preg_replace` to un-escape the required-field asterisk span — a workaround for an upstream Ninja Forms bug where multiple forms on the same page get their `<span class="ninja-forms-req-symbol">*</span>` HTML-entity-escaped on the second-onward render. Three `prefix_class` controls (`title-`, `labels-`, `button-`) carry state into SCSS. **Zero EA widget JavaScript** — Ninja Forms uses its own Backbone-based frontend for form fields, validation, and submission. **Zero `do_action`/`apply_filters`** in the widget — no Pro extension surface, no upsell.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker, custom title/description toggle | ✅ | ✅ |
| Show/hide labels (`eael-ninja-form-labels-yes/-`) | ✅ | ✅ |
| Show/hide native title (`eael-ninja-form-title-yes/-`) | ✅ | ✅ |
| Show/hide placeholder, custom radio/checkbox | ✅ | ✅ |
| Full-width submit button (`eael-ninja-form-button-full-width`) | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / errors / submit button) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/NinjaForms.php`](../../includes/Elements/NinjaForms.php) | PHP widget class (1784 lines) — controls, `render()`, asymmetric `function_exists` / `class_exists` gate, post-render regex fix |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L595) | `get_ninja_form_list()` — `Ninja_Forms()->form()->get_forms()` (OOP API, not CPT query) |
| [`src/css/view/ninja-form.scss`](../../src/css/view/ninja-form.scss) | Source styles (65 lines) — alignment via floats + flex hybrid, labels show/hide, full-width button, ⚠ typo `nf-field-labe` |
| [`config.php`](../../config.php#L824) entry `'ninja-form'` | `Asset_Builder` dependency declaration: **CSS only** — `ninja-form.min.css`. No widget JS, no script-depends (unlike WPForms) |
| `assets/front-end/css/view/ninja-form.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form rendering, submission, validation entirely from Ninja Forms' own Backbone.js + jQuery scripts |

## Architecture

- **Asymmetric plugin-gate** — `register_controls()` at [line 72](../../includes/Elements/NinjaForms.php#L72) uses `function_exists('Ninja_Forms')`; `render()` at [line 1715](../../includes/Elements/NinjaForms.php#L1715) uses `class_exists('Ninja_Forms')`. Both work because the plugin defines BOTH the class `Ninja_Forms` and the function `Ninja_Forms()` (the function returns the singleton instance). Cosmetic inconsistency, not a bug, but unnecessary.
- **Form list via Ninja Forms' OOP API, not a CPT query** — `Helper::get_ninja_form_list()` at [Helper line 600](../../includes/Classes/Helper.php#L600) calls `Ninja_Forms()->form()->get_forms()` then iterates `$form->get_id()` / `$form->get_setting('title')`. NF stores forms in custom tables (`nf3_forms`, `nf3_form_meta`), not WordPress posts — so `posts_per_page` doesn't apply. No cap. CF7 and WPForms use `get_posts()`; NF is the only widget in this category using the plugin's API.
- **Render via `Ninja_Forms()->display()` with output-buffering capture + regex fix** — at [line 1772-1776](../../includes/Elements/NinjaForms.php#L1772):
  1. `ob_start()` before `Ninja_Forms()->display($form_id)` (which echoes directly to stdout)
  2. `ob_get_clean()` captures the output into `$form_html`
  3. `preg_replace('/&lt;span\s+class=(?:&quot;|"|\'|&#039;)ninja-forms-req-symbol(?:&quot;|"|\'|&#039;)\s*&gt;\*&lt;(?:\\\\)?\/span&gt;/', '<span class="ninja-forms-req-symbol">*</span>', $form_html)` reverses the HTML entity escaping that NF applies to its required-field asterisk on second-form-onward renders on the same page.
  4. `echo $form_html`
  The bug is in Ninja Forms itself ("Required Field span tag is getting escaped for more than one form in a single page" — comment at [line 1771](../../includes/Elements/NinjaForms.php#L1771)) — EA's regex matches four different quote-styles (`&quot;`, literal `"`, `'`, `&#039;`) and an optional escaped slash. Brittle but functional.
- **Wrapper id includes `get_the_ID()` not widget id** — `'id' => 'eael-ninja-form-' . get_the_ID()` at [line 1727](../../includes/Elements/NinjaForms.php#L1727). On a single post/page this is unique enough, but in archive / Loop Grid / template-builder contexts where the same `eael-ninja` widget renders inside multiple posts, IDs collide. Other form widgets use `$this->get_id()` (the unique widget id). Likely a long-standing bug.
- **Three `prefix_class` SWITCHERs for state** — more than WPForms' one. `form_title` → `eael-ninja-form-title-<yes|empty>` ([line 133](../../includes/Elements/NinjaForms.php#L133)), `labels_switch` → `eael-ninja-form-labels-<yes|empty>` ([line 183](../../includes/Elements/NinjaForms.php#L183)), and a button full-width control writes `eael-ninja-form-button-full-width` ([line 1339](../../includes/Elements/NinjaForms.php#L1339)). SCSS inverts default state: `.nf-form-title` and `.nf-field-label` are hidden by default; the `-yes` suffix re-shows them.
- **Mixed alignment + dead WPUF selectors in SCSS** — alignment uses both `text-align`-style (via `eael-contact-form-align-*`) AND legacy submit-button alignment (`eael-contact-form-btn-align-*`) — pairs of selectors at SCSS lines 7-34. SCSS line 36-44 contains selectors like `ul.wpuf-form li .wpuf-fields input[type="text"]` — that's WP User Frontend, a totally different form plugin. **Dead CSS** carried over from a shared-form-styling era; no panel control writes these. Same `wpuf-form` artifact exists nowhere else in EA.
- **SCSS typo `nf-field-labe`** at [ninja-form.scss line 49](../../src/css/view/ninja-form.scss#L49) — should be `nf-field-label`. The default-state hide rule for native labels was supposed to be `.eael-ninja-form .nf-field-label { display: none }` but the typo means the selector never matches. Then `.eael-ninja-form-labels-yes .nf-field-label { display: block }` re-shows them — but since the default is unset, the off-state of `labels_switch` is **silently broken**: labels stay visible regardless. **A real bug; user-visible.**
- **Native title hide via SCSS — actually works** — `.title-description-hide .nf-form-title { display: none }` AND `&.title-description-hide .nf-form-title { display: none }` (parent-form selector + descendant) at [SCSS line 47-50](../../src/css/view/ninja-form.scss#L47). Unlike WPForms (where `title-description-hide` is a dead marker class), in Ninja Forms it actively hides the native title when the user enables EA-custom title/description mode.
- **`placeholder-hide` is dead** — same as WPForms, no SCSS rule.
- **Inversion-toggle pattern**: 4 alignment classes via if/elseif/elseif/else at [line 1741-1749](../../includes/Elements/NinjaForms.php#L1741) — less elegant than CF7's array lookup but functionally equivalent.
- **Custom title/description renders BEFORE `Ninja_Forms()->display()`** at [line 1753-1768](../../includes/Elements/NinjaForms.php#L1753) — `<div class="eael-ninja-form-heading">` block with `<h3>` + description, then the captured NF output (with native title hidden via the `title-description-hide` SCSS rule). NF's `display()` doesn't accept boolean args like WPForms — title hiding is purely CSS-driven.
- **Widget id ≠ slug** — `get_name()` returns `eael-ninja` but config slug is `ninja-form`. JS binding (none in this widget) would target `frontend/element_ready/eael-ninja.default`. Asset_Builder uses `ninja-form` for the registry key. Differs from siblings in this category (CF7: matches, WPForms: matches).

## Render Output

```html
<div class="eael-contact-form
            eael-ninja-form
            eael-contact-form-align-<default|left|right|center>
            [eael-ninja-form-title-yes | eael-ninja-form-title-]
            [eael-ninja-form-labels-yes | eael-ninja-form-labels-]
            [eael-ninja-form-button-full-width]
            [placeholder-hide]                                  ← when placeholder_switch != 'yes' (NO SCSS rule)
            [title-description-hide]                            ← when custom_title_description == 'yes'
            [eael-custom-radio-checkbox]"                       ← when custom_radio_checkbox == 'yes'
     id="eael-ninja-form-<get_the_ID()>">                       ← ⚠ uses post id, not widget id

  [?] <!-- EA-rendered custom title + description block -->
  <div class="eael-ninja-form-heading">
    [?] <h3 class="eael-contact-form-title eael-ninja-form-title">Title</h3>     ← esc_attr strips HTML
    [?] <div class="eael-contact-form-description eael-ninja-form-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <!-- Ninja_Forms()->display() output — captured via ob_start, regex-fixed, echoed.
       Ninja Forms 3.x renders an empty container + JS bootstrap. Actual form is built by Backbone/JS at runtime. -->
  <div id="nf-form-<form-id>-cont" class="nf-form-cont">
    <!-- Backbone-rendered form fields appear here after JS hydration. Typical: -->
    <div class="nf-form-title"><h3>Native Title</h3></div>      ← hidden by SCSS when title-description-hide
    <nf-fields-wrap>
      <nf-field>
        <div class="nf-field-container">
          <div class="nf-field-label">
            <label>Name <span class="ninja-forms-req-symbol">*</span></label>  ← regex-fixed back from &lt;span&gt;
          </div>
          <div class="nf-field-element">
            <input type="text" class="ninja-forms-field">
          </div>
        </div>
      </nf-field>
    </nf-fields-wrap>
  </div>
</div>
```

Notes:

- The form fields are NOT in the initial HTML — Ninja Forms 3.x emits an empty container plus a `<script>` that bootstraps Backbone.js to hydrate the form on `DOMContentLoaded`. Any CSS rule targeting `.nf-field-label` etc. applies to JS-rendered elements; styles must avoid `:not()` chains that won't match before hydration.
- The wrapper `id` uses `get_the_ID()` instead of `$this->get_id()` — collides in archive contexts.
- `<span class="ninja-forms-req-symbol">*</span>` is the asterisk next to required field labels. The escape-then-regex-fix round-trip is necessary because of an NF upstream bug.
- Title rendered via `esc_attr()` strips HTML — same inconsistency with `wp_kses`-filtered description as CF7 and WPForms.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/NinjaForms.php#L66) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "Ninja Forms not installed" notice when `function_exists('Ninja_Forms')` returns false |
| `contact_form_list` | SELECT | `0` | Content → Ninja Forms | Form picker; options from `Helper::get_ninja_form_list()` (OOP API call) |
| `custom_title_description` | SWITCHER | empty | Content → Ninja Forms | Toggle: NF-native title (off, hidden by SCSS) vs EA-custom (on) |
| `form_title` | SWITCHER (`prefix_class`) | `yes` | Content → Ninja Forms | Visible only when `custom_title_description != 'yes'`; adds `eael-ninja-form-title-yes` to show native title |
| `form_title_custom` | TEXT (dynamic, AI) | empty | Content → Ninja Forms | EA-rendered custom title; visible only when `custom_title_description == 'yes'` |
| `form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → Ninja Forms | EA-rendered custom description; same condition |
| `labels_switch` | SWITCHER (`prefix_class`) | `yes` | Content → Ninja Forms | Adds `eael-ninja-form-labels-yes` (visible) or `eael-ninja-form-labels-` (hidden). ⚠ Off-state broken due to SCSS typo `nf-field-labe` |
| `placeholder_switch` | SWITCHER | `yes` | Content → Ninja Forms | Adds `placeholder-hide` class when off (NO SCSS rule; functional no-op) |
| `error_messages` / `validation_errors` | SELECT (`selectors_dictionary`) | `show` | Content → Errors | NF error visibility via `display: block/none !important` |
| `eael_contact_form_alignment` | CHOOSE | — | Style → Form Container | Adds `eael-contact-form-align-<default/left/right/center>` class; SCSS uses `float` + flex hybrid |
| `eael_contact_form_max_width` | SLIDER | — | Style → Form Container | `max-width` on container |
| (container padding, margin, border, border-radius, background, box-shadow) | various | — | Style → Form Container | Standard styles |
| (title color, typography, margin, alignment) | various | — | Style → Title & Description | Selector union of EA-custom + NF-native (`.eael-contact-form-title, .nf-form-title`) |
| (description color, typography, margin) | various | — | Style → Title & Description | Same |
| (labels color, typography, margin) | various | — | Style → Labels | `.nf-field-label` (NF class) |
| (form fields: width, height, padding, text-indent, background, color, border, focus state, typography) | various | — | Style → Form Fields | `.nf-field-element input:not(...)` + textarea + select |
| (placeholder color, typography) | various | — | Style → Placeholder | `::-webkit-input-placeholder` (Chrome/Safari) only |
| `custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-custom-radio-checkbox`; gates ~16 size/color/border controls (more than CF7 / WPForms) |
| (errors text color, typography, alignment) | various | — | Style → Errors | NF error message styles |
| (submit button width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.nf-field-element input[type="button"]` (NF uses `type="button"` not `submit` — Backbone-handled) |
| Submit button full-width | SWITCHER (`prefix_class`) | empty | Style → Submit Button | Adds `eael-ninja-form-button-full-width` class |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when function_exists('Ninja_Forms') is FALSE
ALL form controls + style sections       → visible when function_exists('Ninja_Forms') is TRUE

# Title mode
form_title                               → visible when custom_title_description != 'yes' (NF-native mode)
form_title_custom / form_description_custom → visible when custom_title_description == 'yes' (EA-custom mode)

# Style → Radio & Checkbox (~16 controls)
radio_checkbox_size / _color / _border / ... → conditional on custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on placeholder_switch == 'yes'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls at all
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell). Same lean profile as WPForms.

Ninja Forms' own hooks (`ninja_forms_display_after_form_open`, `ninja_forms_process_completed`, `ninja_forms_localize_field_settings_<type>`, etc.) flow through the Ninja Forms plugin's hook chain — third parties listen for them independently.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no EA widget JavaScript file.** The `config.php` entry declares only a CSS dependency, and the widget has no `get_script_depends()` declaration (unlike WPForms). Ninja Forms 3.x ships its own Backbone.js-based renderer (`/wp-content/plugins/ninja-forms/assets/js/min/front-end.js` plus Marionette/Underscore deps) which hydrates the empty `<div class="nf-form-cont">` placeholder into the full form on `DOMContentLoaded`. Submission is AJAX via NF's REST endpoints; validation, multi-step forms, file uploads, conditional logic are all NF-side.

The lack of `get_script_depends()` (unlike WPForms) means EA does NOT formally declare a dependency on Ninja Forms' JS. Ninja Forms enqueues its own scripts via `wp_enqueue_scripts` when it detects its forms; this works because EA's `Ninja_Forms()->display()` call triggers NF's standard enqueue flow.

## Common Issues

### Widget shows "Ninja Forms is not installed/activated"

- **Likely cause:** Ninja Forms plugin is deactivated. Verify `function_exists('Ninja_Forms')` returns true.
- **Diagnose:** check Plugins → Installed; ensure no fatal errors in NF's bootstrap.
- **Fix:** install + activate Ninja Forms.

### Required field asterisk shows as literal `&lt;span&gt;*&lt;/span&gt;` text

- **Likely cause:** the post-render regex at [line 1776](../../includes/Elements/NinjaForms.php#L1776) didn't match — could be because NF added a new escape variant (e.g., `\\&quot;` slash-escaped) not in the regex.
- **Diagnose:** view-source for the page — does the raw output contain `&lt;span class=&quot;ninja-forms-req-symbol&quot;&gt;*&lt;/span&gt;` un-replaced?
- **Fix:** update EA Lite or add a custom filter to `the_content` to handle the new escape form. Known fragile workaround for an upstream NF bug.

### Labels still visible after toggling Labels to Hide

- **Likely cause:** SCSS typo at [ninja-form.scss line 49](../../src/css/view/ninja-form.scss#L49) — `nf-field-labe` (missing `l` in label). The default-hide rule never matches the actual NF class `nf-field-label`. Result: labels stay visible regardless of the toggle.
- **Diagnose:** browser DevTools → Computed → no `display: none` rule applies to `.nf-field-label` in the OFF state.
- **Fix:** either patch the SCSS typo (rebuild required), or add custom CSS via theme: `.eael-ninja-form-labels- .nf-field-label { display: none !important; }`. **Known SCSS bug.**

### Multiple Ninja Forms widgets on the same page share an id

- **Likely cause:** wrapper id is `eael-ninja-form-<get_the_ID()>` — uses the post id, not the widget id. Two NF widgets on the same post collide.
- **Diagnose:** browser DevTools — multiple elements with the same `id`.
- **Fix:** known bug; harmless unless custom CSS / JS targets `#eael-ninja-form-<id>`. Use `$this->get_id()` patch would resolve.

### Form picker shows "Create a Form First" but I have Ninja Forms saved

- **Likely cause:** `Ninja_Forms()->form()->get_forms()` returned empty. Could be database issue (NF stores forms in `nf3_forms` table — not `wp_posts`).
- **Diagnose:** check the WP admin → Ninja Forms → Forms list. Manually query `SELECT * FROM wp_nf3_forms`.
- **Fix:** ensure NF database tables exist; re-create a form if necessary. NF database integrity issues are separate from this widget.

### Submit button alignment doesn't change

- **Likely cause:** SCSS provides `eael-contact-form-btn-align-*` classes but no panel control writes them — same dead-class pattern as CF7. Use the general alignment control instead, which applies via float on `.eael-ninja-container`.
- **Diagnose:** inspect wrapper class.
- **Fix:** working as designed; alignment is at form-container level not per-button.

### WPUF (WP User Frontend) selectors in DevTools but I don't use WPUF

- **Likely cause:** SCSS at lines 36-44 contains `ul.wpuf-form li .wpuf-fields input[type="text"]` etc. — leftover dead CSS targeting WP User Frontend (a different form plugin), shipped in the bundled stylesheet.
- **Diagnose:** view rendered CSS; selectors are present but match nothing on your page.
- **Fix:** none needed; cosmetic. Could be cleaned up in a future SCSS pass.

### Form fields are blank initially, then suddenly appear

- **Likely cause:** Ninja Forms 3.x uses Backbone.js to render fields after `DOMContentLoaded`. The initial HTML has an empty `<div class="nf-form-cont">` placeholder only.
- **Diagnose:** view-source vs DevTools — view-source shows empty container; DevTools shows fields.
- **Fix:** working as designed. For fast-loading themes you may see a FOUC; consider adding CSS to hide the container until hydration: `.nf-form-cont:empty { min-height: 100px; }`.

## Known Limitations

- **SCSS typo `nf-field-labe`** — `labels_switch` off-state silently broken; labels stay visible. **Real user-visible bug.**
- **Wrapper id uses `get_the_ID()` not widget id** — multiple NF widgets on the same post share the wrapper id. Long-standing bug.
- **Post-render regex is brittle** — if NF changes how it escapes the required-field span (e.g., adds slash-escaping for some attribute style), the regex won't match and asterisks show as raw entity-escaped HTML.
- **Asymmetric plugin-gate** — `function_exists` in `register_controls`, `class_exists` in `render`. Both work but it's inconsistent.
- **Dead WPUF selectors in SCSS** ([ninja-form.scss line 36-44](../../src/css/view/ninja-form.scss#L36)) — `ul.wpuf-form li .wpuf-fields` targets WP User Frontend, never matches NF markup. Bytes wasted.
- **Two dead wrapper classes** — `placeholder-hide` has no SCSS rule (same as WPForms); the legacy `eael-contact-form-btn-align-*` classes have rules but no panel control writes them.
- **Submit button selector matches `input[type="button"]` not `input[type="submit"]`** — NF 3.x renders submit as `<input type="button">` and handles click via Backbone. SCSS at [line 52-54](../../src/css/view/ninja-form.scss#L52) acknowledges this; submit-button style controls also target `input[type="button"]`. Behaviour is correct but unusual — distinct from CF7 / WPForms which use real `<input type="submit">`.
- **Custom title rendered via `esc_attr()`** ([line 1757](../../includes/Elements/NinjaForms.php#L1757)) — strips ALL HTML. Same inconsistency as CF7 and WPForms.
- **No script-depends declaration** — unlike WPForms which declares `wpforms-elementor` handle, this widget doesn't formally depend on NF's JS. Works because NF auto-enqueues; brittle if NF's enqueue logic changes.
- **No `eael_section_pro` upsell, zero hooks** — same as WPForms. No extension point.
- **Style controls assume static DOM** — NF's Backbone-hydrated DOM may differ from the empty placeholder. Late-loading scripts that re-render the form (e.g., conditional logic showing/hiding fields) bypass EA's `selectors`-emitted CSS until the new fields inherit the cascade.
- **No frontend AJAX integration with EA** — submission success/failure doesn't broadcast `eael.hooks.doAction(…)`. Tabs / accordions containing NF won't re-layout after submission.
- **Placeholder color targets only `::-webkit-input-placeholder`** — Firefox/Edge unstyled. Same limitation as CF7 and WPForms.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. NF nonces are page-cache-aware, so this is usually fine.
- **Widget id `eael-ninja` ≠ slug `ninja-form`** — JS lookups (if anything ever needed them) would target `eael-ninja` not `eael-ninja-form`. Asset_Builder uses `ninja-form`. Same divergence pattern as Filterable_Gallery.
