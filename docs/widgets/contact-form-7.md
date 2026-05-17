# Contact Form 7 Widget

> CSS-only styling wrapper around the Contact Form 7 plugin's shortcode. Picks a CF7 form via a Select control (populated by `wp_query`-ing the `wpcf7_contact_form` post type), optionally renders a title + description, then delegates to `do_shortcode('[contact-form-7 id="…"]')`. All visual customization is done through `selectors` writing CSS rules into the rendered CF7 markup (the `.wpcf7-*` classes). **Zero widget JavaScript** — submission, validation, and AJAX all come from CF7's own scripts.

**Class file:** [`includes/Elements/Contact_Form_7.php`](../../includes/Elements/Contact_Form_7.php)
**Slug:** `contact-form-7` (widget id `eael-contact-form-7`)
**Public docs:** <https://essential-addons.com/elementor/docs/contact-form-7/>
**Pro-shared:** ❌ No widget-specific Pro extension — Pro doesn't add hooks here. The `eael_section_pro` upsell panel is the only Pro-related surface. (CF7's own Pro / extension plugins are independent of EA Pro.)

---

## Overview

Contact Form 7 is the canonical example of EA's **Form Integration** pattern: gate `register_controls()` and `render()` on a `function_exists('wpcf7')` check, render a "plugin not installed" RAW_HTML warning when missing, otherwise expose a form picker (populated by querying the third-party plugin's CPT) and render the form through its own shortcode. EA contributes only the **styling layer** — every visual control writes a CSS rule via `selectors` into the rendered `.wpcf7-*` markup classes. The widget adds an outer `.eael-contact-form-7-wrapper > .eael-contact-form.eael-contact-form-7` structure plus optional `<h3 class="eael-contact-form-7-title">` + `<div class="eael-contact-form-7-description">` above the shortcode output. Three toggle-class modifiers (`labels-hide`, `placeholder-show`, `eael-custom-radio-checkbox`) drive global visual variants. **Zero widget JavaScript**: form submission, validation, AJAX, file-upload, and reCAPTCHA all come from Contact Form 7's own scripts.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All form picker / title / description / labels / alignment controls | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / focus / errors / submit button) | ✅ | ✅ |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Contact_Form_7.php`](../../includes/Elements/Contact_Form_7.php) | PHP widget class (1773 lines) — controls, `render()`, `function_exists('wpcf7')` gate |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L516) | `get_wpcf7_list()` — queries `wpcf7_contact_form` post type (hardcoded `showposts = 999`); `eael_allowed_tags()`, `eael_e_optimized_markup()` |
| [`src/css/view/contact-form-7.scss`](../../src/css/view/contact-form-7.scss) | Source styles (131 lines) — wrapper + submit-button alignment classes; `labels-hide` modifier; layout normalization for `.wpcf7-form` |
| [`config.php`](../../config.php#L800) entry `'contact-form-7'` | `Asset_Builder` dependency declaration: **CSS only** — `contact-form-7.min.css`. No widget JS. |
| `assets/front-end/css/view/contact-form-7.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form submission / validation / AJAX handled by Contact Form 7 plugin's own scripts |

## Architecture

- **Plugin-gate via `function_exists('wpcf7')` in both `register_controls()` and `render()`** — when the CF7 plugin is inactive, `register_controls()` at [line 109-127](../../includes/Elements/Contact_Form_7.php#L109) shows ONLY a "Warning!" section with a RAW_HTML notice ("Contact Form 7 is not installed/activated on your site"). The full control set is wrapped in the `else` branch. `render()` at [line 1712-1714](../../includes/Elements/Contact_Form_7.php#L1712) returns early. This is the **canonical Form Integration gate pattern** — repeats across the 9 form integrations (each gated on a different `function_exists` / `class_exists` for its plugin).
- **Form list via `Helper::get_wpcf7_list()`** — `get_posts(['post_type' => 'wpcf7_contact_form', 'showposts' => 999])` at [Helper line 521-524](../../includes/Classes/Helper.php#L521). The `999` cap is hardcoded — sites with 1000+ CF7 forms would have their last form invisible in the picker. Default selection is "Select a Contact Form" with value `0`; empty result shows "Create a Form First".
- **Render is `do_shortcode('[contact-form-7 id="…"]')`** at [line 1768](../../includes/Elements/Contact_Form_7.php#L1768). The widget wraps the shortcode output in `.eael-contact-form-7-wrapper > .eael-contact-form.eael-contact-form-7` plus optional title/description. CF7 emits its own `<form class="wpcf7-form">` inside that — EA never touches the form markup, only wraps and styles it.
- **All customization is CSS via `selectors`** — every input style (background, border, padding, focus state, error display) is implemented as a CSS rule keyed on `.wpcf7-form-control.wpcf7-text`, `.wpcf7-not-valid-tip`, etc. This means **the widget breaks silently if CF7 changes its CSS class names** (last major rename was CF7 v5.0; subsequent additions like `wpcf7-spinner` aren't styled here).
- **Three render-attribute toggle classes** drive global visual modes — `labels-hide` when `labels_switch != 'yes'` ([SCSS line 67-69](../../src/css/view/contact-form-7.scss#L67) hides `label` inside `.wpcf7-form`); `placeholder-show` when `placeholder_switch == 'yes'` (no SCSS rule — placeholder visibility comes from CF7 itself; this class is reserved for child themes); `eael-custom-radio-checkbox` when `custom_radio_checkbox == 'yes'` (~12 controls gated on this; SCSS replaces native radio/checkbox with custom-styled spans).
- **Form alignment uses 4 classes mapped by PHP** at [line 1737-1748](../../includes/Elements/Contact_Form_7.php#L1737) — `default` → `eael-contact-form-align-default`, `left/right/center` → corresponding classes. Default falls back via PHP array lookup; missing or future values silently degrade to `eael-contact-form-align-default`. The alignment control's `selectors` also writes `text-align` on the wrapper, so the class is partly redundant (kept for SCSS scoping).
- **`selectors_dictionary` pattern for error message visibility** — `error_messages` and `validation_errors` controls use Elementor's `selectors_dictionary` ([line 241-247](../../includes/Elements/Contact_Form_7.php#L241)) to map `'show'/'hide'` → `'block'/'none'` directly in the CSS rule. Avoids needing a separate display control.
- **`eael_section_pro` upsell present, no widget-specific Pro hooks** — gated by `if (!apply_filters('eael/pro_enabled', false))` at [line 273](../../includes/Elements/Contact_Form_7.php#L273). CF7-specific Pro features (e.g., advanced styling for the spinner) don't exist in EA Pro; the upsell points users to general EA Pro features.
- **Title + Description are EA additions, NOT CF7 fields** — `form_title` and `form_description` controls produce a `<h3>` + `<div>` block above the form, sourced from EA panel TEXT/TEXTAREA inputs. They're not bound to any CF7 form fields and don't submit with form data. Useful for prefixing a heading without editing the CF7 form template.

## Render Output

```html
<div class="eael-contact-form-7-wrapper">
  <div class="eael-contact-form
              eael-contact-form-7
              eael-contact-form-<widget-id>
              eael-contact-form-align-<default|left|right|center>
              [labels-hide]                       ← when labels_switch != 'yes'
              [placeholder-show]                  ← when placeholder_switch == 'yes' (class for child-theme hooks)
              [eael-custom-radio-checkbox]">      ← when custom_radio_checkbox == 'yes'

    [?] <!-- Optional title + description block -->
    <div class="eael-contact-form-7-heading">
      [?] <h3 class="eael-contact-form-title eael-contact-form-7-title">Title</h3>
      [?] <div class="eael-contact-form-description eael-contact-form-7-description">Description</div>
    </div>

    <!-- CF7 shortcode output — entirely emitted by Contact Form 7 plugin -->
    <div role="form" class="wpcf7" id="…" lang="…">
      <div class="screen-reader-response">…</div>
      <form action="…#wpcf7-…" method="post" class="wpcf7-form init" novalidate>
        <p>
          <label>Your name
            <span class="wpcf7-form-control-wrap">
              <input class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" …>
            </span>
          </label>
        </p>
        …
        <p>
          <input class="wpcf7-form-control has-spinner wpcf7-submit" type="submit" value="Send">
          <span class="wpcf7-spinner"></span>
        </p>
        <div class="wpcf7-response-output"></div>
      </form>
    </div>
  </div>
</div>
```

Notes:

- The widget owns only the outer `.eael-contact-form-7-wrapper > .eael-contact-form-7` div and the optional title/description block. Everything inside `<div role="form" class="wpcf7">` comes from CF7 — EA selectors target `.wpcf7-*` class hooks within.
- `eael-contact-form-align-default` doesn't have its own SCSS rule (it's the default state); the three non-default alignment classes set `text-align` via the control's `selectors`.
- `placeholder-show` class has no SCSS rule in [`contact-form-7.scss`](../../src/css/view/contact-form-7.scss) — reserved class for theme overrides. Placeholder visibility is actually controlled by what CF7 emits in the form template (e.g., `[text* your-name placeholder "Your name"]` produces a `placeholder` attribute).
- The widget echoes `esc_attr( $settings['form_title_text'] )` — meaning HTML in title is stripped to text. Description goes through `wp_kses( …, Helper::eael_allowed_tags() )` so limited HTML is allowed.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Contact_Form_7.php#L104) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "CF7 not installed" notice — visible ONLY when `function_exists('wpcf7')` returns false (CF7 inactive) |
| `contact_form_list` | SELECT | `0` | Content → Contact Form | Form picker; options from `Helper::get_wpcf7_list()` (hardcoded `showposts = 999` cap) |
| `form_title` | SWITCHER | empty | Content → Contact Form | Toggles the title block emit |
| `form_title_text` | TEXT (dynamic, AI) | empty | Content → Contact Form | Title text; HTML stripped via `esc_attr` |
| `form_description` | SWITCHER | empty | Content → Contact Form | Toggles description block emit |
| `form_description_text` | TEXTAREA (dynamic, AI) | empty | Content → Contact Form | Description text; `wp_kses`-filtered |
| `labels_switch` | SWITCHER | `yes` | Content → Contact Form | Adds `labels-hide` class when off; SCSS hides `.wpcf7-form label` |
| `error_messages` | SELECT (`selectors_dictionary` block/none) | `show` | Content → Errors | Per-field validation tip visibility (`.wpcf7-not-valid-tip`) |
| `validation_errors` | SELECT (`selectors_dictionary` block/none) | `show` | Content → Errors | Top-level validation errors (`.wpcf7-validation-errors`) |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| `eael_contact_form_background` (group) | GROUP_BACKGROUND | — | Style → Form Container | Background of `.eael-contact-form` |
| `eael_contact_form_alignment` | CHOOSE (responsive) | `default` | Style → Form Container | text-align + alignment class on wrapper |
| `eael_contact_form_max_width` | SLIDER (px/em/%) | — | Style → Form Container | `max-width` of form |
| (form container padding, margin, border, border-radius, box-shadow) | various | — | Style → Form Container | Standard container styles |
| (form fields: width, height, padding, text-indent, background, color, border, focus state) | various | — | Style → Form Fields + Style → Focus | Textarea / text / select / date / quiz field styles. Selectors target `.wpcf7-form-control.wpcf7-text/quiz/date/textarea/select` |
| `placeholder_switch` | SWITCHER | `yes` | Style → Placeholder | Toggles `placeholder-show` class (no SCSS rule — reserved for themes) |
| `text_color_placeholder` / `typography_placeholder` | COLOR / GROUP_TYPO | — | Style → Placeholder | Placeholder text style (via `::-webkit-input-placeholder` — Chrome/Safari only; Firefox / Edge use `::placeholder`) |
| `custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-custom-radio-checkbox` class; gates ~12 size/color/border controls |
| `radio_checkbox_size`, `radio_checkbox_color`, `radio_border_*`, `checkbox_border_*`, … | various | — | Style → Radio & Checkbox | Custom-styled span replacement for native radio/checkbox |
| (label color, typography, margin) | various | — | Style → Labels | `.wpcf7-form label` styles; visible when `labels_switch == 'yes'` |
| (submit button width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `input[type="submit"]` styles. Button alignment via the existing `eael-contact-form-btn-align-*` classes (not exposed as a separate control here, but used by SCSS) |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when function_exists('wpcf7') is FALSE (CF7 plugin inactive)
ALL form controls + style sections       → visible when function_exists('wpcf7') is TRUE
                                           (entire `else` branch of register_controls)

# Within form section
form_title_text                          → visible when form_title == 'yes'
form_description_text                    → visible when form_description == 'yes'

# Style → Labels (entire visual subsection)
(label color / typography / margin)      → conditional on labels_switch == 'yes'

# Style → Placeholder
text_color_placeholder /
typography_placeholder                   → conditional on placeholder_switch == 'yes'

# Style → Radio & Checkbox (~12 controls)
radio_checkbox_size / _color / _border / 
checkbox_size / _color / _border / etc.  → conditional on custom_radio_checkbox == 'yes'

# Pro upsell
eael_section_pro / eael_control_get_pro  → visible when Pro plugin is NOT active
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the `eael_section_pro` upsell when Pro is active. |

The widget emits **no extension hooks** (no `do_action`, no `apply_filters` other than `eael/pro_enabled`). All form processing hooks flow through CF7's own hook chain — `wpcf7_before_send_mail`, `wpcf7_mail_sent`, `wpcf7_submit`, etc. — which third parties listen for independently of EA.

The shortcode call at [line 1768](../../includes/Elements/Contact_Form_7.php#L1768) implicitly invokes the entire WordPress shortcode filter chain (`do_shortcode_tag`, `shortcode_atts`, …) and CF7's own setup.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no widget JavaScript.** The `config.php` entry declares only a CSS dependency. Form interaction (submit, validation, AJAX response, file-upload progress, reCAPTCHA) is entirely handled by Contact Form 7's own JS (`/wp-content/plugins/contact-form-7/includes/js/index.js` and dependencies) — which loads independently of this widget via CF7's `wp_enqueue_scripts` hook when CF7 detects its shortcode in page content.

This is the canonical pattern for the Forms category — none of the form-integration widgets ship their own JS. They wrap and style the third-party plugin's shortcode output.

## Common Issues

### Widget shows "Contact Form 7 is not installed/activated"

- **Likely cause:** the CF7 plugin is deactivated or not installed.
- **Diagnose:** check Plugins → Installed; verify `function_exists('wpcf7')`.
- **Fix:** install + activate Contact Form 7. The widget's full control panel only appears after activation; existing widget instances continue to render the warning until the plugin returns.

### Form picker shows "Create a Form First" but I have CF7 forms saved

- **Likely cause:** the `get_posts(['post_type' => 'wpcf7_contact_form'])` query returned empty — could be a permission issue (logged-out edit), a custom-CPT-filter from another plugin, or all forms are in trash.
- **Diagnose:** check WP admin → Contact → Contact Forms; verify forms exist with `post_status = 'publish'`.
- **Fix:** publish at least one CF7 form. If the issue persists, look for `pre_get_posts` filters that may be excluding the CPT.

### Custom radio/checkbox styling doesn't work even with toggle on

- **Likely cause:** CF7 form template uses HTML5 radio/checkbox or custom shortcode formats that don't produce the standard `.wpcf7-list-item input[type="radio"]` markup. The EA SCSS targets specific markup; non-standard CF7 templates won't match.
- **Diagnose:** browser DevTools — does the markup match `<span class="wpcf7-list-item"><input type="radio">…<span class="wpcf7-list-item-label">…</span></span>`?
- **Fix:** rewrite the CF7 form to use the standard `[radio your-radio "Option 1" "Option 2"]` shortcode format.

### Field-level error tips show up even when "Error Messages: Hide" is set

- **Likely cause:** the CSS rule has `!important` ([line 246](../../includes/Elements/Contact_Form_7.php#L246)) which should win — UNLESS the theme or another plugin loads a stylesheet *after* CF7 styling that re-applies `display: block`.
- **Diagnose:** browser DevTools → Computed → check the cascade for `.wpcf7-not-valid-tip { display }`.
- **Fix:** identify the conflicting stylesheet and either dequeue it or increase `wp_enqueue_style` priority on EA's CSS.

### Form title / description doesn't show

- **Likely cause:** the switcher is on but the text field is empty. Render checks BOTH the switcher value AND non-empty text — `if ($settings['form_title'] == 'yes' && $settings['form_title_text'] != '')`.
- **Diagnose:** check the text field has content.
- **Fix:** enter title/description text.

### Placeholder color control doesn't affect placeholders in Firefox

- **Likely cause:** the SCSS selector uses `::-webkit-input-placeholder` (Chrome/Safari) only — Firefox uses `::placeholder` or `::-moz-placeholder`, Edge uses `:-ms-input-placeholder`.
- **Diagnose:** browser DevTools → inspect placeholder rule in different browsers.
- **Fix:** add custom CSS via theme for `::placeholder { color: <value>; }`. EA SCSS doesn't include the cross-browser pseudo-element list.

### Submit button alignment doesn't change

- **Likely cause:** the SCSS provides `eael-contact-form-btn-align-left/center/right` classes ([SCSS line 6-21](../../src/css/view/contact-form-7.scss#L6)) but **no panel control writes them** — they're legacy classes from an older version of the widget. Current panel uses `text-align` via the wrapper's alignment control, which doesn't reach the submit button specifically.
- **Diagnose:** inspect the rendered wrapper; only `.eael-contact-form-align-*` classes are written.
- **Fix:** style the submit button via theme CSS, or use the Style → Submit Button section's responsive-width/margin controls.

## Known Limitations

- **`Helper::get_wpcf7_list()` hardcodes `showposts = 999`** ([Helper line 523](../../includes/Classes/Helper.php#L523)) — sites with 1000+ CF7 forms will silently lose the last form from the picker. WordPress' `posts_per_page = -1` would be more correct.
- **Placeholder color/typography target only `::-webkit-input-placeholder`** — incomplete cross-browser support. Firefox/Edge users won't see the configured color.
- **Custom radio/checkbox styling depends on CF7's exact markup** — third-party CF7 add-ons that emit different markup (e.g., Conditional Fields, Smart Grid) bypass EA's styling and revert to native browser controls.
- **SCSS contains submit button alignment classes (`eael-contact-form-btn-align-*`) that no panel control writes** — legacy from an older version. Adds ~30 bytes of dead CSS.
- **`placeholder-show` class is wired up but has no SCSS rule** — toggling `placeholder_switch` adds/removes the class but visually does nothing without theme-side custom CSS. The switch itself is mostly a hook anchor for the per-state controls (color/typography) which DO have effect.
- **Title text is rendered via `esc_attr()`** ([line 1757](../../includes/Elements/Contact_Form_7.php#L1757)) — strips ALL HTML and double-encodes some characters (`&` → `&amp;`). Probably should be `esc_html()`. Inconsistent with description which uses `wp_kses + parse_text_editor`.
- **No frontend AJAX integration** — the widget doesn't subscribe to CF7's `wpcf7submit` JS event or expose form success/failure to the rest of EA. Tabs / accordions containing CF7 forms don't get re-laid out after submission.
- **No `eael_section_pro` upsell hide condition based on functionality** — the upsell appears on every Lite render even though no EA Pro features extend this widget. Visual clutter for users.
- **`do_shortcode` round-trip means EA can't intercept the form template** — third parties wanting to alter CF7's form HTML must hook CF7 directly (e.g., `wpcf7_form_elements`); EA provides no integration shim.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. CF7 nonces are normally page-cache-aware, but Elementor's widget render cache could in theory stale-cache the shortcode output if CF7 changes the form between widget renders. Rare edge case.
- **`function_exists('wpcf7')` check is a string-based plugin presence detector** — works in practice but doesn't catch CF7 forks (e.g., CF7-clone-plugin that doesn't define the `wpcf7` function). The user-facing warning is misleading in that edge case.
