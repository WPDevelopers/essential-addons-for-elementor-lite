# Gravity Forms Widget

> CSS-styling wrapper around the Gravity Forms plugin. Picks a form via a SELECT2 control (populated by `RGFormsModel::get_forms(null, 'title')` — the plugin's static API), renders via `gravity_form($id, $show_title, $show_description, false, null, $ajax, '', true)` with native title/description and AJAX submission as panel-driven booleans. Echoes `GFCommon::gf_global()` + `GFCommon::gf_vars()` inline-script bootstraps inside the wrapper so the Gravity Forms frontend JS finds the localized variables it needs. Ships a 5-line edit-context JS file that forcibly shows `.gform_wrapper` in the Elementor preview (GF hides it by default in non-front-end contexts). **Conversational Forms post type is short-circuited** — render returns early on conversational pages.

**Class file:** [`includes/Elements/GravityForms.php`](../../includes/Elements/GravityForms.php)
**Slug:** `gravity-form` (widget id `eael-gravity-form`)
**Public docs:** <https://essential-addons.com/elementor/docs/gravity-forms/>
**Pro-shared:** ❌ No — Lite-only styling. **No `do_action` / `apply_filters` extension hooks** (zero Pro extension surface), no `eael_section_pro` upsell. Same lean profile as WPForms / Ninja Forms. Pro doesn't reference this widget.

---

## Overview

Gravity Forms follows the Form Integration pattern with two distinguishing features: (1) a small **edit-only JS file** (5 lines) that runs in the Elementor editor preview to force `.gform_wrapper` visibility — Gravity Forms hides itself in non-front-end contexts, breaking the preview, so EA's edit JS overrides that; (2) the render method **calls `GFCommon::gf_global()` and `GFCommon::gf_vars()`** after the form output to inject GF's JavaScript globals as inline `<script>` blocks — without these, GF's AJAX submission and multi-page navigation can't bootstrap. Form selection uses a SELECT2 control (richer than the standard SELECT used by CF7 / WPForms / NinjaForms) — better UX for sites with many forms. Render is gated on `class_exists('\GFForms')` AND a check that the current post is NOT a `conversational_form` post type (GF Conversational Forms render their own page wrappers and would conflict with EA's wrapping). Style depends declares `gravity_forms_theme_framework` so GF's theme stylesheet is loaded.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker (SELECT2), custom title/description, AJAX toggle | ✅ | ✅ |
| Show/hide labels (`labels-hide` class) | ✅ | ✅ |
| Show/hide placeholder, custom radio/checkbox | ✅ | ✅ |
| Multi-page next/previous button styling (sections of style controls) | ✅ | ✅ |
| `Use Ajax` switch — passes to `gravity_form()` as boolean | ✅ | ✅ |
| Conversational Forms guard (renders nothing on conversational post type) | ✅ | ✅ |
| Edit-mode preview fix (force-show `.gform_wrapper`) | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / errors / submit / next / prev buttons) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/GravityForms.php`](../../includes/Elements/GravityForms.php) | PHP widget class (2993 lines — by far the largest Form Integration) — controls covering 4 button styles (submit, next, previous, others), `render()`, `class_exists('\GFForms')` + post-type gate |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L542) | `get_gravity_form_list()` — `RGFormsModel::get_forms(null, 'title')` (sorted by title; static API not OOP) |
| [`src/css/view/gravity-form.scss`](../../src/css/view/gravity-form.scss) | Source styles (93 lines) — text-align alignment, labels-hide, title-description-hide, full-width button, file-upload button cross-browser fixes (`::file-selector-button`, `::-webkit-file-upload-button`) |
| [`src/js/edit/gravity-form.js`](../../src/js/edit/gravity-form.js) | Edit-mode JS (5 lines) — `$scope.find('.gform_wrapper').show()` on `frontend/element_ready/eael-gravity-form.default` |
| [`config.php`](../../config.php#L848) entry `'gravity-form'` | `Asset_Builder` dependency declaration: `gravity-form.min.css` (view) + `edit/gravity-form.min.js` (edit context only — no view JS) |
| `assets/front-end/css/view/gravity-form.min.css` | Built output (do not edit) |
| `assets/front-end/js/edit/gravity-form.min.js` | Built editor-only output |
| (no view JS file) | — Form submission / validation / AJAX / multi-page entirely from Gravity Forms' own scripts |

## Architecture

- **Plugin-gate on `class_exists('\GFForms')`** in both `register_controls()` and `render()` ([line 115](../../includes/Elements/GravityForms.php#L115), [line 2914](../../includes/Elements/GravityForms.php#L2914)) — consistent (unlike NinjaForms' asymmetric gate). `\GFForms` is GF's main static class.
- **Conversational Forms post-type guard** — `render()` at [line 2914](../../includes/Elements/GravityForms.php#L2914) returns early when `get_post_type(get_the_ID()) === 'conversational_form'`. GF Conversational Forms render themselves as fullscreen-takeover pages with their own bootstrap script; rendering an `.eael-gravity-form` wrapper inside the conversational page produces a non-functional double-form. **Unique to this widget** — no other Form Integration has a post-type-specific guard.
- **`gravity_form()` is called with 8 positional args** at [line 2979](../../includes/Elements/GravityForms.php#L2979):
  ```
  gravity_form( $form_id, $show_title, $show_description, $display_inactive=false, $field_values=null, $ajax, $tabindex='', $echo=true )
  ```
  Three booleans wired to panel switches: `$show_title` ← `form_title === 'yes'`, `$show_description` ← `form_description === 'yes'`, `$ajax` ← `form_ajax === 'yes'`. When the user enables `custom_title_description`, the native title/description are NOT suppressed via the `gravity_form()` booleans (which retain their saved values) but are instead **hidden via CSS** — `.title-description-hide .gform_heading { display: none }` at [SCSS line 39-41](../../src/css/view/gravity-form.scss#L39). Different mechanism from WPForms (which forces booleans to false) and Ninja Forms (which uses CSS).
- **`GFCommon::gf_global()` + `GFCommon::gf_vars()` echoed after the form** at [line 2983-2986](../../includes/Elements/GravityForms.php#L2983) — emits inline `<script>` blocks containing `var gf_global = {…}` and `var gf_vars = {…}` JS variables. These hold GF's runtime config (REST nonces, locale strings, validation messages). Without them, GF's frontend script can't initialize the form for AJAX submission. **No other form integration in EA does this** — most rely on the plugin's own `wp_localize_script` flow, but GF's localization is wired to the `gform_ajax_javascript` action which doesn't always fire reliably inside Elementor-rendered DOM.
- **Edit-context JS forces `.gform_wrapper` visibility** at [edit JS line 3-4](../../src/js/edit/gravity-form.js#L3) — `$scope.find('.gform_wrapper').show()`. GF's own JS hides the wrapper while it's processing or in non-front-end contexts; the Elementor editor preview qualifies as non-front-end. **Edit JS only** — declared in `config.php` with `'context' => 'edit'`, never loaded on the live site.
- **`get_style_depends()` declares `gravity_forms_theme_framework`** at [line 97-101](../../includes/Elements/GravityForms.php#L97) — Gravity Forms 2.5+ ships a theme framework (Orbital theme) for form layout; this handle ensures it's enqueued. **Distinct from WPForms** which declares the `wpforms-elementor` handle (a JS handle, not CSS).
- **SELECT2 form picker** at [line 149](../../includes/Elements/GravityForms.php#L149) — `Controls_Manager::SELECT2` instead of `SELECT`. Better for sites with 20+ forms (searchable dropdown). Other form widgets use plain SELECT.
- **Form list via static API** — `Helper::get_gravity_form_list()` calls `\RGFormsModel::get_forms(null, 'title')` ([Helper line 547](../../includes/Classes/Helper.php#L547)). Two args: `null` for active filter, `'title'` for sort-by. GF stores forms in custom tables (`gf_form`, `gf_form_meta`), not WP posts — different from CF7/WPForms.
- **Four-class alignment via if/elseif chain** ([line 2942-2953](../../includes/Elements/GravityForms.php#L2942)) — same pattern as Ninja Forms but for `gravity-form-align-*` classes. SCSS at [gravity-form.scss line 1-15](../../src/css/view/gravity-form.scss#L1) uses `text-align` (like CF7) — flex-based approach of WPForms isn't used here.
- **`title-description-hide` CSS rule is functional** ([SCSS line 39-41](../../src/css/view/gravity-form.scss#L39)) — hides `.gform_heading` (the container for both native title and description, since GF combines them). Same active-class pattern as Ninja Forms; unlike WPForms where it's a dead marker.
- **`placeholder-hide` is dead** — class written but no SCSS rule. Same pattern as WPForms / NinjaForms.
- **2993 lines of PHP — largest Form Integration** — Gravity Forms has the most style controls (separate sections for submit, next, previous buttons + section description style + progress bar style + conditional logic styling). 2-2.5× the size of other form widgets.
- **Two CRC-toggle controls instead of one** — both `custom_radio_checkbox` AND `custom_radio_style` map to the same `eael-custom-radio-checkbox` wrapper class via OR-condition at [line 2938](../../includes/Elements/GravityForms.php#L2938) — unique to this widget. `custom_radio_style` is a legacy/duplicate control kept for back-compat.

## Render Output

```html
<div class="eael-contact-form
            eael-gravity-form
            eael-contact-form-align-<default|left|right|center>
            [labels-hide]                                       ← when labels_switch != 'yes'
            [placeholder-hide]                                  ← when placeholder_switch != 'yes' (NO SCSS rule)
            [title-description-hide]                            ← when custom_title_description == 'yes'
            [eael-custom-radio-checkbox]">                      ← when custom_radio_checkbox == 'yes' OR custom_radio_style == 'yes'

  [?] <!-- EA-rendered custom title + description block -->
  <div class="eael-gravity-form-heading">
    [?] <h3 class="eael-contact-form-title eael-gravity-form-title">Title</h3>     ← esc_attr strips HTML
    [?] <div class="eael-contact-form-description eael-gravity-form-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <!-- gravity_form($id, $show_title, $show_description, false, null, $ajax, '', true) — entirely emitted by Gravity Forms -->
  <div class="gform_wrapper gravity-theme" id="gform_wrapper_<form-id>">
    [?] <div class="gform_heading">                                              ← hidden by CSS when title-description-hide
      <h3 class="gform_title">Native Title</h3>
      <span class="gform_description">Native description text</span>
    </div>
    <form method="post" enctype="multipart/form-data" id="gform_<form-id>" action="/page/#gf_<form-id>">
      <div class="gform_body gform-body">
        <ul class="gform_fields top_label form_sublabel_below description_below">
          <li class="gfield gfield_contains_required field_sublabel_below field_description_below gfield_visibility_visible">
            <label class="gfield_label" for="input_<form-id>_<field-id>">Name <span class="gfield_required">*</span></label>
            <div class="ginput_container">
              <input name="input_<field-id>" id="input_<form-id>_<field-id>" type="text" class="medium" value="">
            </div>
          </li>
          …
        </ul>
      </div>
      <div class="gform_footer top_label">
        <input type="submit" id="gform_submit_button_<form-id>" class="gform_button button" value="Submit">
      </div>
    </form>
  </div>

  <!-- Inline GF JavaScript bootstraps — emitted by EA, NOT by GF itself -->
  <script type="text/javascript">
    var gf_global = { gf_currency_config: {…}, base_url: "…", number_formats: [], spinnerUrl: "…" };
    var gf_vars = { thousandsSeparator: ",", decimalSeparator: ".", currency: { /* … */ }, … };
  </script>
</div>
```

Notes:

- The widget owns only the outer `.eael-contact-form.eael-gravity-form` div and optional heading block. Everything inside `<div class="gform_wrapper">` comes from Gravity Forms.
- `gravity-theme` class on `.gform_wrapper` is GF 2.5+ "Orbital" theme markup. Older sites without theme framework may have legacy class hooks (`gform_wrapper gf_browser_chrome` etc.).
- AJAX-enabled forms get an `<iframe name="gform_ajax_frame_<form-id>"` injected after `</form>` (target for the form's POST when `$ajax === true`). GF's frontend JS handles this transparently.
- `<script>` tags with `gf_global` and `gf_vars` are emitted INSIDE `.eael-gravity-form`, after the form. Most plugins emit these via `wp_print_inline_script` in the footer — EA's inline approach guarantees they're available even if asset deferral plugins skip GF's footer scripts.
- Title rendered via `esc_attr()` strips HTML — same inconsistency with `wp_kses`-filtered description as CF7, WPForms, NinjaForms.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/GravityForms.php#L110) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "Gravity Forms not installed" notice when `class_exists('\GFForms')` returns false |
| `contact_form_list` | SELECT2 | `0` | Content → Gravity Forms | Form picker; options from `Helper::get_gravity_form_list()` (RGFormsModel static API) |
| `custom_title_description` | SWITCHER | empty | Content → Gravity Forms | Toggle: GF-native title/description (off) vs EA-custom (on, GF-native hidden via CSS) |
| `form_title` | SWITCHER | `yes` | Content → Gravity Forms | Visible only when `custom_title_description != 'yes'`; passed to `gravity_form()` as `$show_title` |
| `form_description` | SWITCHER | `yes` | Content → Gravity Forms | Same; passed as `$show_description` |
| `form_title_custom` | TEXT (dynamic, AI) | empty | Content → Gravity Forms | EA-rendered custom title; visible only when `custom_title_description == 'yes'` |
| `form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → Gravity Forms | EA-rendered custom description; same condition |
| `labels_switch` | SWITCHER | `yes` | Content → Gravity Forms | Adds `labels-hide` class when off; SCSS hides `.gfield_label` + sublabels |
| `placeholder_switch` | SWITCHER | `yes` | Content → Gravity Forms | Adds `placeholder-hide` class when off (NO SCSS rule; functional no-op) |
| `form_ajax` | SWITCHER | empty | Content → Gravity Forms | Passes `true` to `gravity_form()` `$ajax` arg; enables GF's AJAX submission iframe |
| `error_messages` / `validation_errors` | SELECT (`selectors_dictionary`) | `show` | Content → Errors | GF error visibility |
| `eael_gravity_form_background` (group) / `_alignment` / `_width` / `_max_width` / `_margin` / `_padding` / `_border_radius` / `_border` / `_box_shadow` | various | — | Style → Form Container | Container styles |
| (title color, typography, margin, alignment) | various | — | Style → Title & Description | Selector union of EA-custom + GF-native (`.eael-contact-form-title, .gform_title`) |
| (description color, typography, margin) | various | — | Style → Title & Description | Same |
| (labels color, typography, margin) | various | — | Style → Labels | `.gfield_label, .ginput_complex.ginput_container label` |
| (form fields: width, height, padding, text-indent, background, color, border, focus, typography) | various | — | Style → Form Fields | `.gform_wrapper input:not(...)` + textarea + select |
| (placeholder color, typography) | various | — | Style → Placeholder | `::-webkit-input-placeholder` only |
| `custom_radio_checkbox` / `custom_radio_style` | SWITCHER × 2 | empty | Style → Radio & Checkbox | Either toggles `eael-custom-radio-checkbox` class (OR-condition); duplicate controls |
| (radio/checkbox: size, color, border, focus, checked state) | various | — | Style → Radio & Checkbox | Custom-styled span replacement; conditional on either toggle |
| (errors text color, typography, alignment) | various | — | Style → Errors | `.validation_error, .validation_message` |
| (submit button width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.gform_footer input[type="submit"], .gform_page_footer input[type="submit"]` |
| (next button: align, width, color, border, padding, margin, hover) | various | — | Style → Next Button | Multi-page navigation — `.gform_next_button` |
| (previous button: parallel to next) | various | — | Style → Previous Button | `.gform_previous_button` |
| (other style sections: progress bar, section description, conditional logic, etc.) | various | — | Style tab | GF multi-page + conditional features |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                 → visible when class_exists('\GFForms') is FALSE
ALL form controls + style sections       → visible when class_exists('\GFForms') is TRUE

# Title mode
form_title / form_description            → visible when custom_title_description != 'yes' (GF-native mode)
form_title_custom / form_description_custom → visible when custom_title_description == 'yes' (EA-custom mode)

# Style → Radio & Checkbox (~16 controls)
radio_checkbox_size / _color / _border / ... → conditional on custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on placeholder_switch == 'yes'

# Style → Next/Previous Button
(next button + previous button sections) → no explicit gate; only render on multi-page GF forms

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell). Same lean profile as WPForms / NinjaForms.

Gravity Forms' own hooks (`gform_pre_render`, `gform_pre_submission`, `gform_after_submission`, `gform_validation_<form_id>`, etc.) flow through GF's plugin hook chain — third parties listen for them independently.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **View JS:** N/A — **no view-context JS file.** Form submission, validation, AJAX, multi-page, conditional logic, file-upload all from Gravity Forms' own scripts. EA does NOT declare a view-context `get_script_depends()` (unlike WPForms' `wpforms-elementor` handle); GF's `gform.js` and `gform_gravityforms.js` self-enqueue via `gform_enqueue_scripts` action when GF detects its form on the page.
- **Edit JS (5 lines):** binds `frontend/element_ready/eael-gravity-form.default` and calls `$scope.find('.gform_wrapper').show()`. **Loaded only in `context: 'edit'`** per [config.php line 862](../../config.php#L862). Workaround for GF hiding its wrapper in non-front-end contexts (which the Elementor editor preview counts as).
- **Inline `<script>` blocks in render output** — `GFCommon::gf_global()` and `GFCommon::gf_vars()` echo JS variable declarations directly into the rendered HTML. These set up `window.gf_global` (currency config, base URL, spinner URL) and `window.gf_vars` (localization strings) that GF's frontend JS expects to find. Without them, AJAX-submitted forms fail to bootstrap.

## Common Issues

### Widget shows "Gravity Forms is not installed/activated"

- **Likely cause:** Gravity Forms plugin is deactivated or not yet installed. GF is a paid plugin — confirm license active.
- **Diagnose:** check Plugins → Installed; verify `class_exists('\GFForms')` returns true.
- **Fix:** install + activate Gravity Forms. Free CSS-styling-only form widgets (CF7, WPForms Lite, NinjaForms) are alternatives if GF isn't licensed.

### Form is empty / not visible in the Elementor editor preview

- **Likely cause:** GF's own JS hides `.gform_wrapper` in non-front-end contexts. EA's edit JS at [src/js/edit/gravity-form.js line 3-4](../../src/js/edit/gravity-form.js#L3) calls `.show()` on widget-ready, but if the edit JS handle isn't registered/loaded (e.g., asset-build issue, plugin conflict suppressing edit context), the wrapper stays hidden.
- **Diagnose:** browser DevTools in editor preview — does `.gform_wrapper` have `display: none` inline style?
- **Fix:** rebuild assets (`npm run build`); verify `assets/front-end/js/edit/gravity-form.min.js` exists and is enqueued.

### AJAX submission doesn't work

- **Likely cause:** `gf_global` / `gf_vars` JS variables not present in the page. EA emits them via inline `<script>` after the form, but a caching plugin / minifier could strip inline scripts.
- **Diagnose:** browser DevTools console → `typeof gf_global`, `typeof gf_vars`. Both should be `'object'`.
- **Fix:** disable inline-script minification for pages with GF widgets; verify `GFCommon::gf_global()` output isn't being deferred to footer (some optimizers move inline scripts).

### Form picker shows "Create a Form First" but I have forms

- **Likely cause:** `RGFormsModel::get_forms(null, 'title')` returned empty. GF stores forms in `wp_gf_form` and `wp_gf_form_meta` tables — could be a database migration issue if upgrading from GF 2.4 (old `wp_rg_form` table).
- **Diagnose:** WP admin → Forms; manually query `SELECT * FROM wp_gf_form WHERE is_active = 1`.
- **Fix:** ensure GF database tables are migrated. Re-run GF setup if needed.

### Conversational Form is broken / shows two forms / blank

- **Likely cause:** EA's widget renders `eael-gravity-form` wrapper inside a `conversational_form` post type. The render method short-circuits at [line 2914](../../includes/Elements/GravityForms.php#L2914) when post type matches, so this should NOT happen if the post type detection works — but `get_the_ID()` in some template contexts may return the wrong post id.
- **Diagnose:** inspect rendered HTML; if `.eael-gravity-form` is present on a conversational page, the post-type check failed.
- **Fix:** verify `get_post_type(get_the_ID())` returns `'conversational_form'` in the template context. Possible workaround: use a custom template builder rule to exclude the widget from conversational pages.

### Custom title/description shows BOTH EA-custom AND GF-native title

- **Likely cause:** the user enabled `custom_title_description` (which adds `title-description-hide` class) but the SCSS rule didn't match. Possibly because GF emits the title outside `.gform_heading` (older GF versions used `.gform_title` directly inside `.gform_wrapper`).
- **Diagnose:** browser DevTools — does GF's title appear inside `.gform_heading > h3.gform_title`?
- **Fix:** update Gravity Forms to 2.5+. The SCSS hide rule targets `.gform_heading` only.

### Labels appear even with `labels_switch` set to Hide

- **Likely cause:** GF's theme framework markup uses different label classes than `.gfield_label`. SCSS at [SCSS line 43-46](../../src/css/view/gravity-form.scss#L43) only targets `.top_label .gfield_label` and `.field_sublabel_below .ginput_complex.ginput_container label`. Custom form layouts (left_label, right_label) won't be hidden.
- **Diagnose:** browser DevTools — what selector chain matches the labels you see?
- **Fix:** ensure form layout is "Top Label" (GF Form Settings → Form Layout). Or add custom CSS for the layout your form uses.

### File upload button looks unstyled

- **Likely cause:** SCSS targets cross-browser file-upload pseudo-elements (`::file-selector-button`, `::-webkit-file-upload-button`) and the `.button` class. If GF's file-upload markup doesn't include `.button` (e.g., custom upload field), the style won't apply.
- **Diagnose:** inspect file-upload input element.
- **Fix:** customize via theme CSS targeting your specific upload markup.

## Known Limitations

- **Largest Form Integration by code (2993 lines)** — Gravity Forms has more style controls than CF7, WPForms, or NinjaForms combined (separate sections for submit, next, previous, progress bar, etc.). High maintenance burden if GF's markup changes.
- **Custom title rendered via `esc_attr()`** ([line 2961](../../includes/Elements/GravityForms.php#L2961)) — strips ALL HTML. Same inconsistency as CF7, WPForms, NinjaForms.
- **`placeholder-hide` is dead** — class written but no SCSS rule. Same as WPForms and NinjaForms.
- **Duplicate radio/checkbox toggle** — `custom_radio_checkbox` AND `custom_radio_style` controls both map to the same wrapper class via OR-condition. Likely legacy; one should be deprecated.
- **Edit-JS dependency on `.gform_wrapper.show()` is fragile** — if GF changes wrapper class names (already happened: 2.4 used `gform_wrapper gf_browser_chrome`, 2.5 uses `gform_wrapper gravity-theme`), the `find('.gform_wrapper')` still works because the base class is stable. But hide/show logic in GF's JS may interact unexpectedly.
- **Inline `gf_global` / `gf_vars` emission can collide with multiple widgets** — both global JS variables. If multiple Gravity Forms widgets on the same page each emit their own `<script>` block, the second redeclaration shadows the first (which is fine because they declare the same values per page). But strict mode would throw `SyntaxError: Identifier 'gf_global' has already been declared`.
- **`get_style_depends() = ['gravity_forms_theme_framework']`** — assumes GF 2.5+ with theme framework. Sites on older GF versions get a missing-handle warning in Asset_Builder.
- **No `eael_section_pro` upsell + zero hooks** — same as WPForms / NinjaForms. No extension point.
- **Conversational Forms guard uses `get_the_ID()`** — works in standard contexts but in custom template builders or AJAX-loaded fragments, the global post id may not be the conversational form's id. Edge-case false negatives.
- **No frontend AJAX integration with EA** — submission success/failure doesn't broadcast `eael.hooks.doAction(…)`. Tabs / accordions containing GF won't re-layout after submission.
- **Placeholder color targets only `::-webkit-input-placeholder`** — Firefox/Edge unstyled.
- **`form_ajax` switch passes a bare boolean** — GF's AJAX mode also accepts an array for advanced options (`['tabindex' => 5, …]`). The widget hard-codes `true` only.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. GF nonces are page-cache-aware, so this is usually fine; AJAX nonces are regenerated per submission.
- **`gravity_form()` `$tabindex` (7th positional arg) hardcoded to `''`** — sites needing custom tabindex management on forms inside tabs can't override via panel.
- **SCSS file-upload cross-browser rules apply to ALL `.button` classes inside `.ginput_container_fileupload`** — could over-style adjacent buttons in custom field layouts.
