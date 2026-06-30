# Fluent Forms Widget

> CSS-styling wrapper around the Fluent Forms plugin (WPManageNinja). **Form list query is the only Form Integration that bypasses Helper class** — `FluentForm::get_fluent_forms_list()` is a static method on the widget class itself that uses Fluent Forms' OOP query builder `wpFluent()->table('fluentform_forms')->select(['id','title'])->orderBy('id', 'DESC')->get()` to read directly from the plugin's custom DB table. Render via `do_shortcode( shortcode_unautop( '[fluentform id="…"]' ) )` — applies `shortcode_unautop()` to strip wrapping `<p>` tags WordPress adds around bare shortcodes. **Reads the selected form's `template_name` meta and conditionally adds an `eael-fluent-form-subscription` wrapper class for `inline_subscription` templates** (newsletter-style narrow forms). Has the most style controls of any Form Integration after Gravity Forms — includes dedicated style sections for section breaks, step-header progress indicators, and progressbar visualization.

**Class file:** [`includes/Elements/FluentForm.php`](../../includes/Elements/FluentForm.php)
**Slug:** `fluentform` (widget id `eael-fluentform`)
**Public docs:** <https://essential-addons.com/elementor/docs/fluent-form/>
**Pro-shared:** ❌ No — Lite-only styling. **No `do_action` / `apply_filters` extension hooks** (zero Pro extension surface), no `eael_section_pro` upsell. Same lean profile as WPForms / Ninja / Gravity / Caldera. Pro doesn't reference this widget.

---

## Overview

Fluent Forms follows the Form Integration pattern but with several departures from the canonical approach: (1) **form list helper lives ON the widget class as a static method** (`FluentForm::get_fluent_forms_list()`) instead of in the global `Helper` class — only Form Integration to do this; (2) plugin gate uses **`defined('FLUENTFORM')` constant check** (not `function_exists` like CF7/Ninja or `class_exists` like WPForms/Gravity/Caldera) — Fluent Forms defines a top-level `FLUENTFORM` constant on load; (3) form-list query reads **directly from a custom DB table** via the plugin's Eloquent-like query builder `wpFluent()->table('fluentform_forms')`; (4) **render reads each form's `template_name` meta** via `\FluentForm\App\Helpers\Helper::getFormMeta()` and adds an `eael-fluent-form-subscription` wrapper class when the form is an `inline_subscription` template (newsletter-style layout). Render uses `do_shortcode(shortcode_unautop(...))` — the `shortcode_unautop()` wrapper is unique to this widget; it strips wrapping `<p>` tags WordPress adds around shortcodes in `the_content` filter. Plugin-install link in the warning notice is a direct deep-link to `plugin-install.php?s=fluentform&tab=search&type=term` — better UX than other widgets' bare text warnings.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker (Fluent Forms' custom DB table) | ✅ | ✅ |
| Custom title/description, custom-radio/checkbox toggle | ✅ | ✅ |
| Show/hide labels (`fluent-form-labels-hide` class) | ✅ | ✅ |
| Show/hide placeholder | ✅ | ✅ |
| Show/hide error messages (`error-message-hide` class) | ✅ | ✅ |
| `inline_subscription` template detection (adds `eael-fluent-form-subscription` class) | ✅ | ✅ |
| Section break style controls (label + description + alignment) | ✅ | ✅ |
| Step-header label visibility (`eael-ff-step-header-yes/-no` prefix class) | ✅ | ✅ |
| Step-progressbar visibility (`eael-ff-step-progressbar-yes/-no` prefix class) | ✅ | ✅ |
| Progressbar style sub-section (height, color, border, bg) | ✅ | ✅ |
| Plugin-install deep-link in warning notice | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/FluentForm.php`](../../includes/Elements/FluentForm.php) | PHP widget class (2310 lines) — controls, `render()`, `get_form_attr()`, **static `get_fluent_forms_list()`** (NOT in Helper class) |
| (uses Fluent's helpers directly) | [`\FluentForm\App\Helpers\Helper::getFormMeta($form_id, 'template_name')`](#) — reads form template_name meta to detect subscription forms |
| [`src/css/view/fluentform.scss`](../../src/css/view/fluentform.scss) | Source styles (65 lines) — label-hide rule, button alignment classes, error-hide rule, step-header / progressbar visibility, section-break alignment |
| [`config.php`](../../config.php#L891) entry `'fluentform'` | `Asset_Builder` dependency declaration: **CSS only** — `fluentform.min.css`. No widget JS, no script-depends declaration (relies on FF's auto-enqueue) |
| `assets/front-end/css/view/fluentform.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form rendering / submission / validation / step navigation / file-upload entirely from Fluent Forms' own scripts |

## Architecture

- **Plugin-gate via `defined('FLUENTFORM')` constant check** ([line 84, 106, 2233](../../includes/Elements/FluentForm.php#L84)) — Fluent Forms defines a top-level `FLUENTFORM` constant on bootstrap. **Unique gate style** among Form Integrations: CF7 and Ninja use `function_exists`; WPForms / Gravity / Caldera use `class_exists`; this is the only one using `defined()`. The `get_fluent_forms_list()` method additionally double-gates with `function_exists('wpFluent')` to be safe before calling the query builder.
- **Form list helper is a static method on the widget class itself** ([line 79-99](../../includes/Elements/FluentForm.php#L79)) — `FluentForm::get_fluent_forms_list()`. Every other Form Integration uses `Helper::get_<plugin>_*_list()` in `includes/Classes/Helper.php`. The query is `wpFluent()->table('fluentform_forms')->select(['id', 'title'])->orderBy('id', 'DESC')->get()` — directly reads from the plugin's custom DB table sorted newest-first (other widgets sort by title or insertion order).
- **Plugin install deep-link in warning notice** at [line 118](../../includes/Elements/FluentForm.php#L118) — `<a href="plugin-install.php?s=fluentform&tab=search&type=term">` lets the admin click straight to a pre-searched plugin install page. Other Form Integrations just say "install + activate" in plain text.
- **`get_form_attr()` reads template_name meta** at [line 2226-2228](../../includes/Elements/FluentForm.php#L2226) via `\FluentForm\App\Helpers\Helper::getFormMeta($form_id, 'template_name')`. Used in `render()` to add `eael-fluent-form-subscription` class when the form is built from Fluent Forms' "inline subscription" template (a newsletter-style horizontal layout). The class triggers a SCSS rule at [fluentform.scss line 1652 of widget](../../includes/Elements/FluentForm.php#L1652) (inline-CSS path) that adjusts submit button positioning. **Unique to this widget** — no other Form Integration introspects the form's internal template.
- **`shortcode_unautop()` wrapper in render** at [line 2304](../../includes/Elements/FluentForm.php#L2304) — `do_shortcode( shortcode_unautop( '[fluentform id="…"]' ) )`. WordPress's `wpautop` filter wraps bare shortcodes in `<p>` tags when the content goes through `the_content`; `shortcode_unautop()` reverses this. **Unique to this widget** — CF7, Caldera, and others rely on raw `do_shortcode()` without `unautop` because the widget context doesn't go through `the_content`. FF's shortcode appears to be sensitive to surrounding `<p>` wrapping (form's CSS-grid layout breaks). Likely defensive coding.
- **`get_style_depends()` declares TWO CSS handles** at [line 67-72](../../includes/Elements/FluentForm.php#L67) — `fluent-form-styles` AND `fluentform-public-default`. The first is Fluent Forms' base stylesheet; the second is the public-facing-only default theme. Other Form Integrations declare zero (CF7, Caldera, Ninja) or one (WPForms, Gravity).
- **Step-form support via two `prefix_class` switchers** — `show_step_header` writes `eael-ff-step-header-yes/-no` ([line 1770](../../includes/Elements/FluentForm.php#L1770)) and `show_progressbar` writes `eael-ff-step-progressbar-yes/-no` ([line 1839](../../includes/Elements/FluentForm.php#L1839)). SCSS inverts defaults: `.ff-step-header .ff-el-progress-status` and `.ff-el-progress` are hidden by default; `eael-ff-step-header-yes` / `eael-ff-step-progressbar-yes` re-show them. Same `prefix_class` pattern as WPForms / Caldera. **Multi-step forms with progress visualization is unique to this widget** in the Form Integration category.
- **Dedicated Section Break style sub-section** — controls for section break label, description, padding/margin, alignment (left/center/right via SCSS `eael-fluentform-section-break-content-*`). Section breaks are Fluent Forms' visual separator between form groups; styling them is unique to this widget.
- **Labels SCSS uses `!important`** — `.eael-contact-form.eael-fluent-form-wrapper.fluent-form-labels-hide label { display: none !important; }` ([SCSS line 45-49](../../src/css/view/fluentform.scss#L45)). Other Form Integrations use plain `display: none`. Likely because FF's own CSS has higher specificity selectors that would otherwise win.
- **Error-message-hide is a dedicated CSS rule** at [SCSS line 17-19](../../src/css/view/fluentform.scss#L17) — `.error-message-hide .ff-el-is-error .text-danger { display: none }`. Same `error_messages == 'hide'` toggle as CF7/Caldera, but implemented via class instead of `selectors_dictionary`.
- **`placeholder-hide` is a dead wrapper class** — written but no SCSS rule (same pattern as WPForms / Ninja / Gravity / Caldera).
- **Three dead full-width / center / right button classes** — `eael-fluentform-form-button-full-width`, `eael-fluentform-form-button-center`, `eael-fluentform-form-button-right`, `eael-fluentform-form-button-left` (latter three at SCSS lines 22-43) — exist in SCSS but no panel control writes them. Legacy dead CSS, same pattern as siblings.
- **No `eael_section_pro` upsell, no extension hooks** — same lean profile as WPForms / Ninja / Gravity / Caldera. Five of nine Form Integrations have zero hooks and no upsell.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active.

## Render Output

```html
<div class="eael-contact-form
            eael-fluent-form-wrapper
            clearfix
            eael-contact-form-align-<default|left|right|center>
            [placeholder-hide]                             ← when placeholder_switch != 'yes' (NO SCSS rule)
            [fluent-form-labels-hide]                      ← when labels_switch != 'yes'
            [error-message-hide]                           ← when error_messages == 'hide'
            [eael-custom-radio-checkbox]                   ← when custom_radio_checkbox == 'yes'
            [eael-fluent-form-subscription]                ← when form's template_name == 'inline_subscription'
            [eael-ff-step-header-yes | eael-ff-step-header-no]
            [eael-ff-step-progressbar-yes | eael-ff-step-progressbar-no]
            [eael-fluentform-section-break-content-left|center|right]">

  [?] <!-- EA-rendered custom title + description block -->
  <div class="eael-fluentform-heading">
    [?] <h3 class="eael-contact-form-title eael-fluentform-title">Title</h3>     ← esc_attr strips HTML
    [?] <div class="eael-contact-form-description eael-fluentform-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <!-- do_shortcode( shortcode_unautop( '[fluentform id="…"]' ) ) — entirely emitted by Fluent Forms plugin.
       shortcode_unautop strips wrapping <p> that wpautop adds around shortcodes -->
  <div class="ff-default fluentform" id="fluentform_<form-id>">
    <form id="fluentform_<form-id>" class="frm-fluent-form ff-form-loading">
      <div class="ff-el-group">
        <label class="ff-el-input--label">Name</label>
        <div class="ff-el-input--content">
          <input class="ff-el-form-control" type="text" name="names[first_name]">
        </div>
      </div>
      …
      [?] <div class="ff-step-header">                       ← visible when eael-ff-step-header-yes
        <div class="ff-el-progress-status">Step 1 of 3</div>
      </div>
      [?] <div class="ff-el-progress">                       ← visible when eael-ff-step-progressbar-yes
        <div class="ff-el-progress-bar" style="width: 33%">33%</div>
      </div>
      <div class="ff-t-cell ff-t-column-1">
        <button type="submit" class="ff-btn ff-btn-submit ff-btn-md">Submit</button>
      </div>
    </form>
  </div>
</div>
```

Notes:

- The widget owns only the outer `.eael-contact-form.eael-fluent-form-wrapper.clearfix` div and optional heading block. Everything inside `.fluentform` comes from Fluent Forms' shortcode.
- `clearfix` class on the wrapper is unusual — other Form Integrations don't add it. Likely a workaround for floated submit buttons inside FF's grid layout.
- The `eael-fluent-form-subscription` class only appears when the underlying form was built from FF's "Inline Subscription" template (read via meta query at render time — not a panel toggle).
- Step-form classes (`eael-ff-step-header-*`, `eael-ff-step-progressbar-*`) are written even on single-step forms; FF's CSS just doesn't apply step-header/progressbar markup if the form has no steps.
- Title rendered via `esc_attr()` strips HTML — same inconsistency with `wp_kses`-filtered description as all other Form Integrations.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/FluentForm.php#L101) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "Fluent Form not installed" notice + plugin-install deep-link |
| `form_list` | SELECT | `0` | Content → Fluent Form | Form picker; options from static `FluentForm::get_fluent_forms_list()` |
| `custom_title_description` | SWITCHER | empty | Content → Fluent Form | Toggle EA-custom title/description (FF has no native title/description like other plugins) |
| `form_title_custom` | TEXT (dynamic, AI) | empty | Content → Fluent Form | EA-rendered custom title; visible when `custom_title_description == 'yes'` |
| `form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → Fluent Form | EA-rendered custom description; same condition |
| `labels_switch` | SWITCHER | `yes` | Content → Fluent Form | Adds `fluent-form-labels-hide` class when off; SCSS hides labels with `!important` |
| `placeholder_switch` | SWITCHER | `yes` | Content → Fluent Form | Adds `placeholder-hide` class when off (NO SCSS rule; functional no-op) |
| `error_messages` | SELECT | `show` | Content → Errors | Adds `error-message-hide` class when `hide`; SCSS hides `.ff-el-is-error .text-danger` |
| `eael_contact_form_alignment` | CHOOSE | — | Style → Form Container | Adds `eael-contact-form-align-<default/left/right/center>` class |
| `eael_contact_form_max_width` | SLIDER | — | Style → Form Container | Container max-width |
| (form container padding, margin, border, border-radius, background, box-shadow) | various | — | Style → Form Container | Standard styles |
| (title color, typography, margin, alignment) | various | — | Style → Title & Description | Selectors target `.eael-fluentform-title` only — FF has no native title to share styling |
| (description color, typography, margin) | various | — | Style → Title & Description | Same |
| `fluentform_link_color` | COLOR | — | Style → Title & Description | Custom link color inside title/description blocks |
| (labels color, typography, margin) | various | — | Style → Labels | `.ff-el-input--label` |
| (form fields: width, height, padding, text-indent, background, color, border, focus, typography) | various | — | Style → Form Fields | `.ff-el-form-control` |
| (placeholder color, typography) | various | — | Style → Placeholder | `::-webkit-input-placeholder` only |
| `custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-custom-radio-checkbox`; styles `.ff-el-form-check-label input` |
| (radio/checkbox: size, color, border, focus, checked state) | various | — | Style → Radio & Checkbox | Custom-styled replacement |
| (errors text color, typography, alignment, margin) | various | — | Style → Errors | `.error.text-danger` |
| (submit button: width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.ff-btn.ff-btn-submit` |
| `show_step_header` | SWITCHER (`prefix_class`) | — | Style → Pagination → Progress Bar | Adds `eael-ff-step-header-yes/-no` class; SCSS toggles `.ff-step-header .ff-el-progress-status` visibility |
| `show_progressbar` | SWITCHER (`prefix_class`) | — | Style → Pagination → Progress Bar | Adds `eael-ff-step-progressbar-yes/-no` class; SCSS toggles `.ff-el-progress` visibility |
| (progressbar: height, color, border, border-radius, background, label color, typography, padding, margin) | various | — | Style → Pagination → Progress Bar | Step-form progress visualization styles |
| (section break: label color, typography, padding, margin, alignment) | various | — | Style → Section Break | Styles FF's `.ff-el-section-break` separator element |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when defined('FLUENTFORM') is FALSE
ALL form controls + style sections       → visible when defined('FLUENTFORM') is TRUE

# Title mode
form_title_custom / form_description_custom → visible when custom_title_description == 'yes'

# Style → Title & Description (entire section)
many controls inside this section        → conditional on custom_title_description == 'yes'

# Style → Radio & Checkbox (~12 controls)
radio_checkbox_size / _color / _border / ... → conditional on custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on placeholder_switch == 'yes'

# Style → Pagination → Progress Bar
progressbar_height / _color / _border /
_border_radius / _bg                     → conditional on show_progressbar == 'yes'
(no condition on show_step_header)

# Subscription template detection (runtime, not panel-conditional)
eael-fluent-form-subscription class      → added at render time when getFormMeta(form_id, 'template_name') == 'inline_subscription'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls at all
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell). Same lean profile as WPForms / NinjaForms / GravityForms / Caldera.

Fluent Forms' own hooks (`fluentform_before_form_render`, `fluentform_submission_confirmation`, `fluentform_rendering_form`, etc.) flow through Fluent Forms' plugin hook chain — third parties listen for them independently. The widget consumes one direct API call: `\FluentForm\App\Helpers\Helper::getFormMeta($form_id, 'template_name')` to read form metadata at render time.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no EA widget JavaScript file.** The `config.php` entry declares only a CSS dependency, and the widget has no `get_script_depends()` declaration. Form interaction (submit, validation, AJAX, multi-step navigation, conditional logic, file-upload, payment integration) is entirely handled by Fluent Forms' own scripts — which enqueue automatically when FF's shortcode is detected in page content.

## Common Issues

### Widget shows "Fluent Form is not installed/activated"

- **Likely cause:** the Fluent Forms plugin is deactivated or not installed.
- **Diagnose:** check Plugins → Installed; verify the `FLUENTFORM` PHP constant is defined.
- **Fix:** click the deep-link in the panel notice — it goes directly to the WordPress plugin install search pre-filled for "fluentform". Or install + activate manually.

### Form picker shows empty / "Create a Form First"

- **Likely cause:** the `fluentform_forms` custom DB table is empty or doesn't exist. Could be a plugin upgrade issue or a fresh install before creating any forms.
- **Diagnose:** check FF admin → Forms; manually query `SELECT id, title FROM wp_fluentform_forms`.
- **Fix:** create at least one form in Fluent Forms. The query orders by `id DESC` (newest first) so the most recent form appears at the top of the dropdown.

### Subscription form looks broken / submit button overlaps fields

- **Likely cause:** the form was created from a non-`inline_subscription` template but the user expected subscription layout. The `eael-fluent-form-subscription` class is only added when `getFormMeta(form_id, 'template_name') === 'inline_subscription'` — other templates (Contact Form, Conversational Form, etc.) don't trigger the special CSS.
- **Diagnose:** check the form's `template_name` meta: `SELECT * FROM wp_fluentform_form_meta WHERE form_id = X AND meta_key = 'template_name'`.
- **Fix:** rebuild the form from the "Inline Subscription Form" template in FF, OR manually set the template_name meta via SQL, OR override via custom CSS.

### Labels still visible after toggling Labels to Hide

- **Likely cause:** unlike other Form Integrations, the FF SCSS uses `display: none !important;` — so the toggle should always win. If labels still appear, another stylesheet with higher specificity / later cascade is overriding even the `!important`.
- **Diagnose:** browser DevTools → Computed → check the cascade.
- **Fix:** locate the conflicting stylesheet (typically a theme); increase EA's CSS load priority or override per-element.

### `<p>` tags wrap the form output

- **Likely cause:** `shortcode_unautop()` failed to strip the wrapping `<p>` — could happen if a content filter that runs AFTER `shortcode_unautop()` re-wraps the output.
- **Diagnose:** browser DevTools — does the form have a wrapping `<p>` element around the shortcode region?
- **Fix:** disable conflicting filters (e.g., `the_content` priority too low). The widget's `shortcode_unautop()` is normally sufficient.

### Step-form progress bar / step header missing

- **Likely cause:** SCSS hides `.ff-step-header .ff-el-progress-status` and `.ff-el-progress` by default ([SCSS line 55-56](../../src/css/view/fluentform.scss#L55)). The `show_step_header == 'yes'` and `show_progressbar == 'yes'` toggles add `eael-ff-step-header-yes` / `eael-ff-step-progressbar-yes` to re-show them.
- **Diagnose:** check the wrapper class.
- **Fix:** enable both toggles in Style → Pagination → Progress Bar.

### Custom title/description shows up but appears too far above form

- **Likely cause:** EA's `eael-fluentform-heading` block is rendered BEFORE the FF shortcode output, with no margin control between them (gap is controlled by the form fields' top margin from FF's CSS).
- **Diagnose:** browser DevTools — measure the gap.
- **Fix:** use the description margin control or add custom CSS targeting `.eael-fluentform-heading + .ff-default`.

### Newly created forms don't appear in the dropdown

- **Likely cause:** the SELECT control runs at panel-load time (`get_fluent_forms_list()` is called by `register_controls`) — newly created forms don't appear until the Elementor panel is refreshed.
- **Diagnose:** create a form, immediately check the EA widget panel.
- **Fix:** close + reopen the panel, or refresh the editor.

## Known Limitations

- **Form list helper is on the widget class, not in `Helper`** ([line 79-99](../../includes/Elements/FluentForm.php#L79)) — only Form Integration to break this pattern. If `Helper` gets a future `get_fluent_forms_list()` added (for cross-widget reuse), there'll be a duplicate.
- **Direct DB query via `wpFluent()` query builder** — assumes the FF plugin's internal API is stable. If FF refactors `wpFluent` or removes the `fluentform_forms` table (e.g., switches to CPT in a major version), the form picker breaks.
- **`getFormMeta()` is a direct call into FF's internal namespace** ([line 2227](../../includes/Elements/FluentForm.php#L2227)) — `\FluentForm\App\Helpers\Helper::getFormMeta`. Path changes in FF's namespace structure (e.g., reorganization) would break the subscription detection without warning.
- **`shortcode_unautop()` is unique among Form Integrations** — other widgets call `do_shortcode()` directly without the unautop wrapper, suggesting either FF's shortcode is more sensitive to wrapping `<p>` tags, or the other widgets have a latent bug not yet noticed. Inconsistent treatment of the same problem.
- **`clearfix` class on wrapper** — only Form Integration to add this. Vestige from float-based layout era; modern CSS rarely needs explicit `clearfix`.
- **Custom title rendered via `esc_attr()`** ([line 2291](../../includes/Elements/FluentForm.php#L2291)) — strips ALL HTML. Same inconsistency as all other Form Integrations.
- **`placeholder-hide` is dead** — class written but no SCSS rule. Same as WPForms / Ninja / Gravity / Caldera.
- **Three dead button-alignment SCSS classes** + one dead full-width class — `eael-fluentform-form-button-center/right/left/full-width` exist in SCSS but no panel control writes them. Same dead-CSS pattern as siblings.
- **Subscription detection is render-time only** — the `eael-fluent-form-subscription` class is decided at render based on a DB query. Per-page caching is fine; full-site cache plugins may freeze the result if the user changes the form's template post-cache.
- **No `eael_section_pro` upsell + zero hooks** — same as 4 other Form Integrations.
- **No frontend AJAX integration with EA** — submission success/failure doesn't broadcast `eael.hooks.doAction(…)`. Tabs / accordions containing FF won't re-layout after submission.
- **Placeholder color targets only `::-webkit-input-placeholder`** — Firefox/Edge unstyled.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active. FF nonces are page-cache-aware so usually fine.
- **`get_style_depends()` returns 2 handles but doesn't account for missing handles** — if FF is updated to remove `fluent-form-styles` (e.g., consolidation into `fluentform-public-default`), the widget logs an "unknown handle" warning.
- **Step-header / progressbar styling assumes single multi-step form per page** — FF's `.ff-step-header` and `.ff-el-progress` classes are global; styling them via `{{WRAPPER}} .ff-step-header …` scopes correctly per widget, but two FF widgets with different step settings on the same page won't override each other cleanly.
- **`get_fluent_forms_list()` orders by `id DESC`** — newest forms first. Sites with many forms may prefer alphabetical (CF7 uses post creation order; Gravity sorts by title). Inconsistent UX.
