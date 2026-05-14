# Formstack Widget

> CSS-styling wrapper around the Formstack Online Forms plugin (a SaaS form builder, not a local plugin). **Unique among Form Integrations: OAuth-based SaaS** — form list comes from the API, not a local CPT or DB table; forms are selected by URL (not ID); render fetches form HTML from `https://*.formstack.com/...` via `wp_remote_get()` and caches in a 1-hour transient. **Three distinct failure modes** in `register_controls()` with separate panel notices: plugin missing, OAuth not configured, no forms yet. Gate uses **`eael/is_plugin_active` filter** (consumed) — unique among Form Integrations; every other widget uses `function_exists`/`class_exists`/`defined`.

**Class file:** [`includes/Elements/Formstack.php`](../../includes/Elements/Formstack.php)
**Slug:** `formstack` (widget id `eael-formstack`)
**Public docs:** <https://essential-addons.com/elementor/docs/formstack/>
**Pro-shared:** ❌ No widget-specific Pro extension. **No `do_action` extension hooks**, only consumes `eael/is_plugin_active` (the EA-internal filter) for the plugin-presence gate. **No `eael_section_pro` upsell panel.** Pro doesn't reference this widget.

---

## Overview

Formstack is the only **SaaS-backed Form Integration** in EA — every other form widget binds to a local form plugin (CF7, WPForms, Ninja, etc.) that stores forms in WP's database. Formstack hosts forms on its own infrastructure; the WP plugin `formstack/plugin.php` just provides OAuth2 credentials and an admin page that pulls the user's form list via API and caches it as `wp_options['formstack_forms']`. EA's widget queries that cached list, presents the form URL as a SELECT, then at render time **fetches the form's HTML via `wp_remote_get()`** from `https://*.formstack.com/...` and caches the response in a 1-hour transient keyed by `'eael_formstack_' . md5($url)`. Output is `wp_kses`-filtered with `Helper::eael_allowed_tags()` before echo. Three distinct register-control branches handle each failure mode separately, and a "Refresh Formstack form cache" admin link is embedded in the form-picker description for syncing newly-created forms. All control IDs are double-prefixed `eael_formstack_*` — unique convention; siblings use unprefixed canonical names like `labels_switch`.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker (URL-based, sourced from `wp_options['formstack_forms']` cache) | ✅ | ✅ |
| Form fetch via `wp_remote_get()` with 1-hour transient cache | ✅ | ✅ |
| Three distinct failure-mode notices (plugin missing / OAuth not configured / no forms) | ✅ | ✅ |
| EA-custom title/description (`eael_formstack_custom_title_description`) | ✅ | ✅ |
| Show/hide labels (`eael_formstack_labels_switch`) | ✅ | ✅ |
| Show/hide placeholder (`eael_formstack_placeholder_switch`) | ✅ | ✅ |
| **Two-tier error visibility** — `error_messages` AND `validation_messages` separately | ✅ | ✅ |
| Custom radio/checkbox styling (`eael_formstack_custom_radio_checkbox`) | ✅ | ✅ |
| All styling controls (container / fields / placeholder / labels / radio-checkbox / errors / validation / submit button) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Formstack.php`](../../includes/Elements/Formstack.php) | PHP widget class (2161 lines) — controls, `render()`, three failure-mode methods (`no_app_setup()`, `no_forms_created()`, `formstack_not_activated()`), `access_token()`, `formstackAuth()`, `get_forms()`, form-URL regex validation, `wp_remote_get` + transient |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `eael_allowed_tags()` — sanitizes fetched form HTML before echo |
| [`src/css/view/formstack.scss`](../../src/css/view/formstack.scss) | Source styles (127 lines) — targets Formstack's `fsForm` / `fsCell` / `fsLabel` / `fsError` / `fsValidationError` / `fsRequiredLabel` / `fsBody` class hierarchy; labels-hide, error/validation-message-hide via wrapper modifier classes; alignment via `position:absolute;left:50%;transform:translateX(-50%)` (not flex/float) |
| [`config.php`](../../config.php#L836) entry `'formstack'` | `Asset_Builder` dependency declaration: **CSS only** — `formstack.min.css`. No widget JS, no `get_script_depends()` |
| `assets/front-end/css/view/formstack.min.css` | Built output (do not edit) |
| (no widget JS file) | — Form submission / validation / AJAX entirely from Formstack-hosted JS embedded in the fetched form HTML |
| `wp_options['formstack_forms']`, `formstack_settings`, `formstack_oauth2_code`, `formstack_form_count` | Plugin-managed options EA reads from (not written) |
| `transient['eael_formstack_' . md5($form_url)]` | 1-hour cache of fetched form HTML, per-form |

## Architecture

- **Plugin-gate via `apply_filters('eael/is_plugin_active', 'formstack/plugin.php')`** at [line 157](../../includes/Elements/Formstack.php#L157) and [line 2066](../../includes/Elements/Formstack.php#L2066) — uses EA's own plugin-presence filter (consumed). **Unique gate style** among Form Integrations: CF7 / Ninja use `function_exists`; WPForms / Gravity / Caldera use `class_exists`; FluentForm uses `defined()`; WeForms uses `function_exists`. The `eael/is_plugin_active` filter is EA-internal — it checks against the active plugins list, not a PHP symbol.
- **Three distinct failure-mode methods** — each registers its own `eael_global_warning` section with different copy:
  1. **`formstack_not_activated()`** ([line 119](../../includes/Elements/Formstack.php#L119)) — plugin not installed; includes deep-link `plugin-install.php?s=formstack&tab=search&type=term` (same FluentForm pattern)
  2. **`no_app_setup()`** ([line 77](../../includes/Elements/Formstack.php#L77)) — plugin installed but OAuth client_id / client_secret / access_token missing; "Please set your app client credentials on the Formstack settings page"
  3. **`no_forms_created()`** ([line 98](../../includes/Elements/Formstack.php#L98)) — OAuth configured but `formstack_forms` option is empty; "Please create form on the Formstack settings page"
  Other Form Integrations have a single "install + activate" notice. Formstack's three-tier diagnostic is unique.
- **Form list from cached API response, NOT a database query** — `get_forms()` at [line 141](../../includes/Elements/Formstack.php#L141) reads `get_option('formstack_forms', '')` and iterates `$forms['forms']` building `[$form->url => $form->name]`. The cache is populated by the Formstack plugin (not EA) when the user visits the plugin's admin page. **Form key in EA's panel is the URL, not the ID** — `eael_form_key` stores `https://example.formstack.com/forms/abc`.
- **Render fetches form HTML via `wp_remote_get`** at [line 2084-2092](../../includes/Elements/Formstack.php#L2084) — 120-second timeout, response stored in 1-hour transient `'eael_formstack_' . md5($form_url)`. **Output is `wp_kses`-sanitized** with `Helper::eael_allowed_tags()` before echo at [line 2153](../../includes/Elements/Formstack.php#L2153). The fetched HTML can include `<script>` tags for Formstack's form submission JS — `eael_allowed_tags()` allows `<script>` (verified by Formstack working at all); this is the only Form Integration that ships externally-fetched HTML through `wp_kses`. Trade-off: third-party content sanitization vs. silent breakage if Formstack adds new tags/attrs not in the allowlist.
- **URL regex validation before fetch** at [line 2076](../../includes/Elements/Formstack.php#L2076) — `preg_match('/\bhttps?:\/\/[a-zA-Z0-9.-]+\.formstack\.com\/[^\s"\']*/', $url)`. Ensures only `*.formstack.com` URLs are fetched; prevents SSRF via panel-edited settings. Strict enough to reject http (non-https Formstack subdomains exist) — actually wait, `https?` allows both http and https. So http subdomains pass. Still domain-locked to `.formstack.com`.
- **"Refresh cache" link in form-picker description** at [line 187-195](../../includes/Elements/Formstack.php#L187) — `add_query_arg(['clear_formstack_cache' => 'true'], admin_url('admin.php?page=Formstack'))`. Sites with newly-created forms hit this link to re-sync the local `formstack_forms` cache.
- **All control IDs double-prefixed `eael_formstack_*`** — unique convention. Siblings use unprefixed canonical names: `labels_switch`, `placeholder_switch`, `custom_title_description`, `custom_radio_checkbox`, `error_messages`. Formstack uses `eael_formstack_labels_switch`, `eael_formstack_placeholder_switch`, etc. Defensive against name collision with the Formstack plugin's own settings, but breaks `_patterns.md § Form Integration` selector uniformity.
- **Two-tier error visibility** — `eael_formstack_error_messages` (controls `.fsValidationError` styling) AND `eael_formstack_validation_messages` (controls `.fsError` display). Formstack distinguishes **inline validation** (red-bordered field box) from **error messages** (text below field) — EA exposes both. Other Form Integrations have only one error toggle.
- **Five wrapper classes added unconditionally** — `eael-formstack`, `clearfix`, `fs_wp_sidebar`, `fsBody`, `eael-contact-form`. The `fsBody` and `fs_wp_sidebar` classes are Formstack's own scoped CSS hooks — adding them to the EA wrapper ensures Formstack's stylesheet rules cascade correctly even when EA's wrapper is the outermost element.
- **Alignment via string concat, not `prefix_class`** at [line 2127-2128](../../includes/Elements/Formstack.php#L2127) — `$alignment = $settings[...]; ...->add_render_attribute('class', 'eael-formstack-form-align-'.$alignment)`. Missing value would produce `eael-formstack-form-align-` (empty suffix). SCSS uses `position:absolute;left:50%;transform:translateX(-50%)` for center alignment — unusual; could affect layout context.
- **Plugin admin page reachable at `admin.php?page=Formstack`** — capital F. Notable for the cache-clear URL.
- **Zero `do_action`, only 2 `apply_filters` (both `eael/is_plugin_active`)** — confirmed lean profile. No `eael_section_pro` upsell. Same hooks-and-upsell-free pattern as WPForms / Ninja / Gravity / Caldera / FluentForm.

## Render Output

```html
<div class="eael-formstack
            clearfix
            fs_wp_sidebar                                       ← Formstack scoping hook
            fsBody                                              ← Formstack scoping hook
            eael-contact-form
            eael-formstack-form-align-<default|left|right|center>
            [placeholder-hide]                                  ← when placeholder_switch != 'yes' (NO SCSS rule)
            [eael-formstack-form-labels-hide]                   ← when labels_switch != 'yes'
            [eael-formstack-error-message-hide]                 ← when error_messages == 'hide'
            [eael-formstack-validation-message-hide]            ← when validation_messages == 'hide'
            [eael-formstack-custom-radio-checkbox]">            ← when custom_radio_checkbox == 'yes'

  [?] <!-- EA-rendered custom title + description block -->
  <div class="eael-formstack-heading">
    [?] <h3 class="eael-contact-form-title eael-formstack-title">Title</h3>     ← esc_attr strips HTML
    [?] <div class="eael-contact-form-description eael-formstack-description">  ← wp_kses + parse_text_editor
      Description
    </div>
  </div>

  <div class="fsForm">
    <!-- Output of wp_remote_get($form_url) — 1-hour transient cached.
         Sanitized through wp_kses(Helper::eael_allowed_tags()) before echo.
         Formstack-hosted HTML emits its own form + JS bootstrap: -->
    <form action="https://www.formstack.com/forms/index.php" class="fsForm fsSingleColumn" method="post" id="fsForm123">
      <ol id="fsCells" class="fsLabelVertical">
        <li class="fsRow fsFieldRow fsLastRow" id="fsRow-…">
          <div class="fsCell fsFieldCell fsSpan100">
            <label class="fsLabel">Name <span class="fsRequiredMarker">*</span></label>
            <input class="fsField" type="text" name="field…">
          </div>
        </li>
        …
      </ol>
      <div class="fsSubmit fsPagination">
        <input type="submit" value="Submit" class="fsSubmitButton">
      </div>
    </form>
    <script>/* Formstack's submission/validation JS */</script>
  </div>
</div>
```

Notes:

- The widget owns only the outer `.eael-formstack` wrapper and optional EA-rendered heading block. Everything inside `.fsForm` comes from Formstack-hosted HTML.
- Five wrapper classes are added unconditionally — three of them (`fs_wp_sidebar`, `fsBody`, `clearfix`) mimic Formstack's expected DOM context so their stylesheet rules match.
- **Output includes inline `<script>` tags from Formstack** — `wp_kses(Helper::eael_allowed_tags())` allowlist must include `<script>` for the form to function. This is a notable departure from EA's normal defense-in-depth posture.
- Title rendered via `esc_attr()` strips HTML — same inconsistency with `wp_kses`-filtered description as every other Form Integration.
- Center alignment uses `position:absolute;left:50%;transform:translateX(-50%)` ([SCSS line 41-44](../../src/css/view/formstack.scss#L41)) — this requires the parent column to be `position:relative` (Elementor columns are by default), but can collide with other absolutely-positioned siblings.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Formstack.php#L155) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | One of THREE distinct notices depending on failure mode |
| `eael_form_key` | SELECT | empty | Content → Formstack | **Form URL** picker (not ID); options from `get_forms()` (reads `wp_options['formstack_forms']` cache); description embeds cache-refresh link |
| `eael_formstack_custom_title_description` | SWITCHER | empty | Content → Formstack | EA-custom title/description toggle |
| `eael_formstack_form_title_custom` | TEXT (dynamic, AI) | empty | Content → Formstack | EA-rendered custom title; visible when `custom_title_description == 'yes'` |
| `eael_formstack_form_description_custom` | TEXTAREA (dynamic, AI) | empty | Content → Formstack | EA-rendered custom description; same condition |
| `eael_formstack_labels_switch` | SWITCHER | `yes` | Content → Formstack | Adds `eael-formstack-form-labels-hide` when off; SCSS hides `.fsLabel` with `!important` |
| `eael_formstack_placeholder_switch` | SWITCHER | `yes` | Content → Formstack | Adds `placeholder-hide` class when off (NO SCSS rule; functional no-op) |
| `eael_formstack_error_messages` | SELECT | `show` | Content → Errors | Adds `eael-formstack-error-message-hide` when `hide`; SCSS unsets `.fsValidationError` background/box-shadow/colors |
| `eael_formstack_validation_messages` | SELECT | `show` | Content → Errors | Adds `eael-formstack-validation-message-hide` when `hide`; SCSS hides `.fsError` |
| `eael_formstack_form_background` | COLOR | — | Style → Form Container | `.eael-formstack` + `.eael-formstack .fsForm` background |
| `eael_formstack_form_alignment` | CHOOSE | — | Style → Form Container | String-concat class `eael-formstack-form-align-<value>` (NOT `prefix_class`) |
| `eael_formstack_form_max_width` / `_form_margin` / `_form_padding` / `_form_border_radius` | various | — | Style → Form Container | Container box model |
| Form container border + box-shadow | GROUP | — | Style → Form Container | Border + box-shadow groups |
| (form fields: width, height, padding, text-indent, background, color, border, focus state, typography) | various | — | Style → Form Fields | `.fsField` family |
| (placeholder color, typography) | various | — | Style → Placeholder | `::-webkit-input-placeholder` only |
| `eael_formstack_custom_radio_checkbox` | SWITCHER | empty | Style → Radio & Checkbox | Toggles `eael-formstack-custom-radio-checkbox`; gates custom radio/checkbox styling controls |
| (radio/checkbox: size, color, border, focus, checked state) | various | — | Style → Radio & Checkbox | Custom-styled replacement |
| (labels color, typography, margin) | various | — | Style → Labels | `.fsLabel` |
| (validation errors text color, typography, alignment, margin) | various | — | Style → Validation Messages | `.fsError` |
| (errors text color, typography, alignment, margin) | various | — | Style → Errors | `.fsValidationError` |
| (submit button: width, padding, color, border, background, typography, hover) | various | — | Style → Submit Button | `.fsSubmitButton`, `.fsSubmit input[type="submit"]` |

## Conditional Dependencies

```text
# Three-tier plugin gate — each step shows a DIFFERENT panel notice
eael_global_warning_text (variant 1)     → visible when eael/is_plugin_active('formstack/plugin.php') is FALSE
eael_global_warning_text (variant 2)     → visible when OAuth client_id OR client_secret OR access_token is empty
eael_global_warning_text (variant 3)     → visible when get_forms() returns empty (no forms in plugin cache)
ALL form controls + style sections       → visible when ALL three gates pass

# Title mode
eael_formstack_form_title_custom /
eael_formstack_form_description_custom   → visible when eael_formstack_custom_title_description == 'yes'

# Style → Radio & Checkbox
radio_checkbox_size / _color / _border / 
...                                       → conditional on eael_formstack_custom_radio_checkbox == 'yes'

# Style → Placeholder
(placeholder color, typography)          → conditional on eael_formstack_placeholder_switch == 'yes'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls at all
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/is_plugin_active` | filter (consumed, called 2×) | `string $plugin_path` | Plugin-active check; used at [line 157](../../includes/Elements/Formstack.php#L157) and [line 2066](../../includes/Elements/Formstack.php#L2066). Same filter feeds the Conversational Forms guard in GravityForms and the Ninja Tables gate in Filterable_Gallery. |

The widget emits **no `do_action`** (zero extension surface) and **does not consume `eael/pro_enabled`** (no upsell). Formstack's own form-submission flow happens on `formstack.com` infrastructure — local WP hooks don't fire on submit; only the API status notification arrives.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **no EA widget JavaScript file.** The `config.php` entry declares only a CSS dependency; no `get_script_depends()`. Form submission, validation, conditional logic, file-upload, and analytics are **all handled by Formstack-hosted JavaScript** embedded in the form HTML fetched at render time. The `<script>` tags in the fetched HTML pass through `wp_kses(Helper::eael_allowed_tags())` — which allowlists `<script>` (verified empirically by the widget working at all). This is the only Form Integration that echoes externally-fetched script tags.

## Common Issues

### Widget shows "Formstack Online Forms is not installed/activated"

- **Likely cause:** `formstack/plugin.php` is not in the active plugins list.
- **Diagnose:** check Plugins → Installed; verify the plugin file path matches `formstack/plugin.php`.
- **Fix:** install + activate via the deep-link `plugin-install.php?s=formstack&tab=search&type=term`.

### Widget shows "Please set your app client credentials on the Formstack settings page"

- **Likely cause:** plugin installed but OAuth not configured — `formstack_settings.client_id`, `client_secret`, or `formstack_oauth2_code` is empty.
- **Diagnose:** WP admin → Formstack settings page; verify all three OAuth fields are filled.
- **Fix:** create an OAuth app in your Formstack account (Settings → Apps), copy client_id + secret into the WP plugin's settings page, complete the OAuth handshake.

### Widget shows "Please create form on the Formstack settings page"

- **Likely cause:** OAuth works but `wp_options['formstack_forms']` is empty — either no forms exist in your Formstack account, OR the local cache is stale.
- **Diagnose:** WP admin → Formstack page; check the form list.
- **Fix:** create a form in Formstack OR click the cache-refresh link (`?clear_formstack_cache=true`).

### Newly created Formstack forms don't appear in the picker

- **Likely cause:** the local cache `wp_options['formstack_forms']` is stale. EA reads from this cache; the Formstack plugin updates it on its admin page visit.
- **Diagnose:** the form-picker control description has a "refreshed the Formstack form cache" link.
- **Fix:** click that link → reload the editor. The cache invalidates and re-fetches from the Formstack API.

### Form renders but submission fails / "Submit" button does nothing

- **Likely cause:** the fetched form HTML's inline `<script>` tags were stripped by `wp_kses`. EA's `Helper::eael_allowed_tags()` SHOULD allowlist `<script>` for this to work, but a custom filter on `eael_allowed_tags` could remove it.
- **Diagnose:** view-source for the page — are `<script>` tags from Formstack present in the rendered `.fsForm` div?
- **Fix:** revert any `eael_allowed_tags` filter overrides. If Formstack added new attributes (e.g., a new `data-*` attribute) to their script tags, they may be stripped silently.

### Form stays cached after Formstack-side edits

- **Likely cause:** EA caches the fetched form HTML in a 1-hour transient. Edits made on Formstack.com take up to an hour to appear.
- **Diagnose:** check `wp_options` for `_transient_eael_formstack_<md5>` entries.
- **Fix:** manually delete the transient via WP-CLI or DB, OR wait for the 1-hour TTL to expire. There is no panel control to force-refresh the form HTML transient (only the form list cache).

### Form URL validation fails — `eael_form_key` is set but render returns empty

- **Likely cause:** the URL doesn't match the regex `/\bhttps?:\/\/[a-zA-Z0-9.-]+\.formstack\.com\/[^\s"\']*/`. Common causes: URL was edited to use a non-formstack.com domain, or contains spaces/quotes.
- **Diagnose:** browser DevTools → inspect the widget settings; verify `eael_form_key` matches the regex.
- **Fix:** re-select the form from the picker. Don't manually edit the URL.

### Center alignment misplaces the form

- **Likely cause:** SCSS uses `position:absolute;left:50%;transform:translateX(-50%)` ([SCSS line 41-44](../../src/css/view/formstack.scss#L41)) which requires a `position:relative` parent. If the Elementor column has been overridden (e.g., custom CSS sets `position:static`), the form anchors to a higher ancestor.
- **Diagnose:** browser DevTools — what's the form's computed offset parent?
- **Fix:** ensure the immediate Elementor column has `position:relative`. Or use Left/Right alignment which uses `float`.

### `placeholder-hide` toggle doesn't hide placeholders

- **Likely cause:** like other Form Integrations, `placeholder-hide` is a dead class — no SCSS rule. Placeholders are emitted by Formstack-hosted HTML and not styleable via CSS `display`.
- **Diagnose:** inspect element — `placeholder-hide` class is present but placeholder still shows.
- **Fix:** edit the form on Formstack.com to remove placeholder text, OR add custom CSS `.placeholder-hide input::placeholder { opacity: 0 }` (cosmetic — value still posts).

## Known Limitations

- **OAuth setup is required before the widget usable** — the only Form Integration with this many setup prerequisites. Users must complete a 3-step OAuth handshake on the Formstack plugin's admin page before the EA widget shows anything but a warning notice.
- **Form HTML fetched at render time** — every page load with cold cache hits `formstack.com` via `wp_remote_get` (120-second timeout). On slow Formstack response, page TTFB suffers. Cache TTL is hardcoded to 1 hour; no panel control to tune.
- **Echoes externally-fetched `<script>` tags through `wp_kses`** — security-sensitive. `Helper::eael_allowed_tags()` must allowlist `<script>`; XSS protection relies on Formstack not serving malicious HTML.
- **URL regex `https?://*.formstack.com/...`** ([line 2076](../../includes/Elements/Formstack.php#L2076)) — `https?` allows BOTH http and https. Plain-HTTP Formstack subdomains would be allowed in (if they existed), bypassing TLS. Should be `https://` only.
- **Five wrapper classes added unconditionally** — `fsBody`, `fs_wp_sidebar`, `clearfix`, etc. polute the wrapper. Cosmetic, but unusual.
- **All control IDs double-prefixed `eael_formstack_*`** — breaks selector uniformity with siblings. Future `_patterns.md § Form Integration` extraction will need a "Formstack uses prefixed IDs" caveat.
- **Three-tier failure mode notices** — better UX than siblings, but unique. Increases code complexity (3 private notice methods + 3 branch checks at `register_controls` head + 2 redundant checks at `render` head).
- **No way to force-refresh form HTML transient from panel** — only the form-list cache has a refresh link. Edits made on Formstack.com to existing forms take up to 1 hour to appear.
- **Center alignment uses `transform:translateX(-50%)`** — fragile dependence on `position:relative` ancestor.
- **Title rendered via `esc_attr()`** strips HTML — same inconsistency as all other Form Integrations.
- **`placeholder-hide` is dead** — class written, no SCSS rule. Same pattern as siblings.
- **No frontend AJAX integration with EA** — submission happens on formstack.com infrastructure; success/failure can't broadcast `eael.hooks.doAction(…)`.
- **No `eael_section_pro` upsell + zero `do_action`** — same lean profile as WPForms / Ninja / Gravity / Caldera / FluentForm.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. The fetched form HTML is itself cached in a transient, but the widget output is also subject to Elementor's render cache, creating two cache layers.
- **`get_forms()` returns `$keys[$form->url] => $form->name`** — duplicate form names appear with the same human-readable label but different URLs. Form picker shows ambiguous duplicates if a user has two forms with the same name.
