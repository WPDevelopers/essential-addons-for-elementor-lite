# Typeform Widget

> SaaS-backed Form Integration that embeds Typeform forms via Typeform's official **`embed.min.js`** library. **No WordPress plugin required** — authentication is a Personal Access Token (PAT) saved on EA's own settings page, not a separate Typeform plugin. Form list pulled directly from `https://api.typeform.com/forms` via `wp_remote_get` with `Authorization: Bearer <token>` header, cached in a 1-hour transient. Render emits a `<div data-typeform='<json>'>` placeholder; JS reads the data attribute and calls `typeformEmbed.makeWidget(el, url, {hideFooter, hideHeaders, opacity})` to inject the iframe. **Smallest Form Integration in EA Lite** — 381 PHP / 14 SCSS / 24 JS lines.

**Class file:** [`includes/Elements/TypeForm.php`](../../includes/Elements/TypeForm.php)
**Slug:** `typeform` (widget id `eael-typeform`)
**Public docs:** <https://essential-addons.com/elementor/docs/typeform/>
**Pro-shared:** ❌ No widget-specific Pro extension. **No `do_action` / `apply_filters` extension hooks**, no `eael_section_pro` upsell. Pro doesn't reference this widget.

---

## Overview

Typeform is **the second SaaS-backed Form Integration** (after Formstack) but takes a different path: instead of integrating with a third-party WP plugin's OAuth flow, it stores a Typeform Personal Access Token directly in `wp_options['eael_save_typeform_personal_token']` and calls `api.typeform.com` itself. Form list fetched and transient-cached at panel-load; render emits a `<div data-typeform='<json>'>` placeholder; embed.js (Typeform's official client library, bundled at `assets/front-end/js/lib-view/embed/embed.min.js`) finds the div by id and replaces it with an iframe via `typeformEmbed.makeWidget()`. Only three Typeform-specific controls: `hide_header`, `hide_footer`, `opacity` (passed through to embed.js). All other controls are EA's standard container styling. **No EA-custom title/description**, no labels/placeholder toggles, no error/validation controls — the form is rendered entirely inside a Typeform-controlled iframe, so EA can't style the form internals.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Form picker (Typeform API; results cached 1 hour) | ✅ | ✅ |
| Personal Access Token saved on EA settings page | ✅ | ✅ |
| Iframe embed via Typeform's embed.js (`typeformEmbed.makeWidget`) | ✅ | ✅ |
| Hide header / hide footer / opacity (passed to embed.js) | ✅ | ✅ |
| Form container styling (background, alignment, max-width, max-height, margin, padding, border, border-radius, box-shadow) | ✅ | ✅ |
| EA-custom title/description | ❌ — control absent | — |
| Labels / placeholder / radio-checkbox / error controls | ❌ — irrelevant for iframe-embedded form | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/TypeForm.php`](../../includes/Elements/TypeForm.php) | PHP widget class (381 lines — smallest Form Integration) — controls, `render()`, `no_token_set()`, `get_personal_token()`, `get_form_list()` (API call + transient), `private $form_list = []` cache |
| [`src/css/view/typeform.scss`](../../src/css/view/typeform.scss) | Source styles (14 lines) — fixed `width:100%; height:700px` on `.eael-typeform`; three alignment variants (center via `transform:translateX(-50%)`, left/right via `float`) |
| [`src/js/view/typeform.js`](../../src/js/view/typeform.js) | Frontend logic (24 lines) — reads `data-typeform` JSON from widget div, calls `typeformEmbed.makeWidget(el, url, options)` |
| [`config.php`](../../config.php#L903) entry `'typeform'` | `Asset_Builder` dependency declaration: `typeform.min.css` + `lib-view/embed/embed.min.js` (vendor) + `typeform.min.js` (self) |
| `assets/front-end/js/lib-view/embed/embed.min.js` | **Vendor: Typeform's official embed library** — exposes `typeformEmbed` global; renders forms as iframes with prev/next nav, custom CSS injection, postMessage submission events |
| `wp_options['eael_save_typeform_personal_token']` | EA-managed option storing user's Typeform PAT (saved via EA settings page) |
| `transient['eael_typeform_' . md5('eael_type_form_data' . $token)]` | 1-hour cache of API form list response |

## Architecture

- **No WP plugin dependency, no `function_exists`/`class_exists` gate** — Typeform doesn't ship an official WordPress plugin; integration is API-only. EA gate is simply `get_personal_token() != ''` ([line 126](../../includes/Elements/TypeForm.php#L126)) — checks `wp_options['eael_save_typeform_personal_token']`. **Unique gate style** among Form Integrations: token-presence-only.
- **Single failure mode notice** — `no_token_set()` ([line 100](../../includes/Elements/TypeForm.php#L100)) — "haven't connected your Typeform account... navigate to WordPress Dashboard → Essential Addons → Elements → Typeform" with deep-link to `admin.php?page=eael-settings`. Unlike Formstack's three-tier diagnostic.
- **Form list via direct REST API call** — `get_form_list()` at [line 65](../../includes/Elements/TypeForm.php#L65) calls `wp_remote_get('https://api.typeform.com/forms?page_size=200', ['headers' => ['Authorization' => "Bearer $token"]])`. Page size hardcoded to 200 — sites with 200+ Typeforms lose access to the rest (Typeform's API supports pagination but EA doesn't iterate). HTTP status checked at [line 83](../../includes/Elements/TypeForm.php#L83) — only `200` responses are cached. 1-hour transient TTL hardcoded.
- **Form picker values are display URLs**, not IDs — `$item->_links->display` ([line 94](../../includes/Elements/TypeForm.php#L94)) is the public form URL like `https://username.typeform.com/to/AbCdEf`. Same URL-not-ID pattern as Formstack.
- **`embed.js` (Typeform's official lib) does the actual rendering** — render emits a placeholder `<div id="eael-type-form-<widget-id>" data-typeform='{"url":"…","hideFooter":bool,"hideHeaders":bool,"opacity":N}'></div>`. The bundled `embed.min.js` defines a `typeformEmbed` global; EA's tiny `typeform.js` calls `typeformEmbed.makeWidget(el, url, options)` which **replaces the div with an iframe**. Form is then entirely under Typeform's iframe security context — EA can't style fields inside.
- **Vendor bundle path** — `assets/front-end/js/lib-view/embed/embed.min.js`. This is Typeform's `@typeform/embed` (legacy v1; modern API is `@typeform/embed-react` / `@typeform/embed`, but EA still uses the older `typeformEmbed` global). Bundled file, not a CDN reference — works offline / self-hosted.
- **Three embed.js options exposed**:
  - `hideHeaders` (`eael_typeform_hideheaders`) — hides Typeform's question-number header
  - `hideFooter` (`eael_typeform_hidefooter`) — hides "Powered by Typeform" footer
  - `opacity` (`eael_typeform_opacity`) — integer 0-100; controls iframe background opacity. **Note `eael_typeform_opacity`'s `'size_units' => ['px']`** at [line 277](../../includes/Elements/TypeForm.php#L277) — cosmetic only; value is meant to be a percentage/scalar for embed.js.
- **`htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8')`** at [line 377](../../includes/Elements/TypeForm.php#L377) — JSON-encodes the embed config then HTML-escapes for safe attribute embedding. URL is also `esc_url()`-filtered before encoding. **Robust against XSS via panel-edited token / URLs** — even if API returns a malicious URL, `esc_url` strips dangerous schemes.
- **Five wrapper classes added unconditionally** — `eael-typeform`, `clearfix`, `fs_wp_sidebar`, `fsBody`, `eael-contact-form`. ⚠ **`fs_wp_sidebar` and `fsBody` are Formstack-specific classes** — likely copy-pasted from `Formstack.php` (the file order in `register_controls()`-style siblings hints they share an ancestor). Cosmetic but misleading; classes match nothing useful in Typeform context.
- **Alignment via string concat, not `prefix_class`** — same shortcut as Formstack. `'eael-typeform-align-' . $alignment` — missing value produces `eael-typeform-align-` (empty suffix).
- **Default height `700px` hardcoded in SCSS** ([typeform.scss line 3](../../src/css/view/typeform.scss#L3)) — also matches the panel control's default ([line 264](../../includes/Elements/TypeForm.php#L264)). User-changed height overrides via the `eael_typeform_max_height` slider.
- **Center alignment uses fragile `position:absolute;left:50%;transform:translateX(-50%)`** ([SCSS line 5-7](../../src/css/view/typeform.scss#L5)) — requires `position:relative` ancestor; same caveat as Formstack.
- **Render returns empty if no form selected** ([line 352-354](../../includes/Elements/TypeForm.php#L352)) — clean no-op. No "select a form" placeholder.
- **Zero `do_action`, zero `apply_filters`** — completely hooks-free. No `eael_section_pro` upsell. Truly minimal extension surface.
- **No `eael_global_warning` if API call fails silently** — if the user's token is invalid or revoked, `get_form_list()` returns just the "Select Form" default option. Panel shows the empty picker with no error indication. UX gap.

## Render Output

```html
<!-- When no token saved: -->
<!-- (no front-end output; panel shows "haven't connected" warning) -->

<!-- When token + form selected: -->
<div id="eael-type-form-<widget-id>"
     data-typeform='{"url":"https://username.typeform.com/to/AbCdEf",
                     "hideFooter":false,
                     "hideHeaders":false,
                     "opacity":50}'
     class="eael-typeform
            clearfix
            fs_wp_sidebar                       ← ⚠ Formstack-leftover class
            fsBody                              ← ⚠ Formstack-leftover class
            eael-contact-form
            eael-typeform-align-<default|left|right|center>">
</div>

<!-- After embed.js runs, the div is replaced by Typeform's iframe: -->
<div id="eael-type-form-<widget-id>" class="…">
  <iframe src="https://username.typeform.com/to/AbCdEf?embed-hide-headers=true&…"
          frameborder="0"
          allow="camera; microphone; autoplay; encrypted-media;"
          allowfullscreen
          width="100%" height="700">
  </iframe>
</div>
```

Notes:

- The widget owns ONLY the `<div class="eael-typeform">` wrapper. Form content is entirely inside Typeform's iframe — EA's `selectors` controls only affect the wrapper div's container styling (background, border, padding, etc.), NEVER reaching inside the iframe due to same-origin-policy.
- `fs_wp_sidebar` and `fsBody` classes are vestigial — they match no SCSS rule in `typeform.scss` and serve no purpose. Almost certainly accidental copy-paste from `Formstack.php`.
- The 700px default height is the embed's `height` attribute — adjustable via `eael_typeform_max_height`. Mobile users see the same fixed height.
- Custom title/description block is absent — no `eael-typeform-heading` wrapper. Users wanting a heading must add a separate Heading widget.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/TypeForm.php#L124) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | Single "haven't connected" notice + deep-link to EA settings page |
| `eael_typeform_list` | SELECT | empty | Content → Typeform | Form picker; options from `get_form_list()` (API + 1-hour transient); value is the form's public URL |
| `eael_typeform_hideheaders` | SWITCHER | `no` | Content → Typeform | Passed to `embed.js` as `hideHeaders` option |
| `eael_typeform_hidefooter` | SWITCHER | `no` | Content → Typeform | Passed to `embed.js` as `hideFooter` option |
| `eael_typeform_background` | COLOR | — | Style → Form Container | `.eael-typeform` background (the wrapper div, NOT the iframe content) |
| `eael_typeform_alignment` | CHOOSE (responsive) | `default` | Style → Form Container | String-concat class `eael-typeform-align-<value>` |
| `eael_typeform_max_width` / `_max_height` | SLIDER (px/em/%, responsive) | `_ / 700px` | Style → Form Container | Width + height on `.eael-typeform`; height drives iframe dimensions too |
| `eael_typeform_opacity` | SLIDER (`px` units, scalar value 0-100) | `50` | Style → Form Container | Passed to embed.js as `opacity`. Note misleading `px` unit |
| `eael_typeform_margin` / `_padding` (DIMENSIONS, responsive) | various | — | Style → Form Container | Wrapper box model |
| `eael_type_border_radius` | DIMENSIONS | — | Style → Form Container | Wrapper border-radius |
| `eael_type_border` (group) / `eael_typeform_box_shadow` (group) | GROUP | — | Style → Form Container | Border + shadow groups |

**Notable omissions** compared to other Form Integrations: NO custom_title_description, NO labels_switch, NO placeholder_switch, NO custom_radio_checkbox, NO error/validation controls. Cross-iframe boundary means none would have effect anyway.

## Conditional Dependencies

```text
# Token gate (single tier)
eael_global_warning_text                 → visible when get_personal_token() returns empty string
ALL form controls + style sections       → visible when token is set

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls
```

## Hooks & Filters

> N/A — the widget emits **no widget-specific filter or action hooks** and **does not consume `eael/pro_enabled`** (no upsell). Cleanest extension profile in the Form Integration category — only consumes `wp_remote_get` and `get_option` / `set_transient`.

Typeform's own form-submission flow runs entirely inside the iframe on `typeform.com` — local WP hooks don't fire on submit. Typeform's embed.js does emit `postMessage` events for submit/form-completion that custom JS could listen for, but EA doesn't surface them.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on('elementor/frontend/init', …)` → `elementorFrontend.hooks.addAction('frontend/element_ready/eael-typeform.default', TypeFormHandler)`. Older registration pattern (NOT the newer `eael.hooks.addAction("init", "ea", …)`).
- **Guard:** none — no `elementStatusCheck`. Re-fires of `frontend/element_ready` would re-invoke `typeformEmbed.makeWidget()` on the same element; embed.js handles the second call by replacing the existing iframe (idempotent).
- **Vendor dependency:** `typeformEmbed` global from `embed.min.js` (Typeform's official embed library v1).
- **Reads on init:** widget `id` and `data-typeform` JSON attribute.
- **No branches:** call `typeformEmbed.makeWidget(el, data.url, {hideFooter, hideHeaders, opacity})` if both id and data are defined. No error handling on failure.
- **No cross-widget reflow listeners** — Typeform inside a tab/accordion doesn't re-init when revealed; embed.js handles iframe resize internally.

## Common Issues

### Widget shows "Whoops! It seems like you haven't connected your Typeform account"

- **Likely cause:** `wp_options['eael_save_typeform_personal_token']` is empty.
- **Diagnose:** click the deep-link in the panel notice — goes to `admin.php?page=eael-settings` (EA settings page, NOT a Typeform plugin admin page).
- **Fix:** create a Typeform Personal Access Token (Typeform account → Settings → Personal Tokens), paste into EA settings → save.

### Form picker is empty after saving the token

- **Likely cause:** the token is invalid, revoked, or lacks the required scope. API call fails silently — `get_form_list()` returns just the "Select Form" default.
- **Diagnose:** browser DevTools → Network → reload editor → look for the `api.typeform.com/forms` request. Check response code.
- **Fix:** verify the token is correct, has `forms:read` scope, and the Typeform account is in good standing. Re-save the token.

### Newly created Typeforms don't appear in the picker

- **Likely cause:** 1-hour transient cache hasn't expired.
- **Diagnose:** check `wp_options` for `_transient_eael_typeform_<md5>` entries; their `option_value` timestamp shows the cache age.
- **Fix:** wait up to 1 hour OR manually delete the transient via WP-CLI/DB OR change the saved token (md5 cache key changes).

### Form renders but is cut off at 700px height

- **Likely cause:** default `height: 700px` in SCSS. Long Typeforms need a taller iframe.
- **Diagnose:** inspect element — `.eael-typeform` computed `height`.
- **Fix:** Style → Form Container → Form Height slider. Set to desired value.

### Opacity slider says "px" but doesn't behave like pixels

- **Likely cause:** the panel control has `'size_units' => ['px']` ([line 277](../../includes/Elements/TypeForm.php#L277)) — cosmetic only. Value is passed to embed.js as `opacity: <int>` (0-100 scalar).
- **Diagnose:** working as designed; `px` label is wrong.
- **Fix:** treat the slider value as a percentage despite the unit label.

### Form list missing forms beyond 200

- **Likely cause:** `page_size=200` hardcoded in API call ([line 72](../../includes/Elements/TypeForm.php#L72)). Typeform's API paginates beyond that; EA doesn't iterate.
- **Diagnose:** count your Typeforms — if 200+, some are missing.
- **Fix:** archive or delete unused Typeforms. There's no panel-side pagination workaround.

### Container styling controls don't affect form fields

- **Likely cause:** the form is inside a cross-origin `<iframe>` from `typeform.com`. Same-origin policy prevents EA's CSS from styling iframe content.
- **Diagnose:** working as designed — EA can only style the wrapper `.eael-typeform`, not the form fields.
- **Fix:** customize the Typeform's theme on `typeform.com` (Typeform's own theme editor). EA controls only adjust the wrapper.

### embed.js fails to load / form doesn't render

- **Likely cause:** the bundled `embed.min.js` wasn't enqueued. Check Asset_Builder loaded the lib dependency.
- **Diagnose:** browser console — `typeof typeformEmbed`. Should be `'object'`.
- **Fix:** verify `assets/front-end/js/lib-view/embed/embed.min.js` exists. Rebuild assets if needed.

## Known Limitations

- **`fs_wp_sidebar` and `fsBody` classes in the wrapper** are Formstack-specific, almost certainly copy-pasted from `Formstack.php`. Harmless but misleading.
- **`page_size=200` hardcoded** — sites with 200+ Typeforms lose access to the rest.
- **No API error feedback** — invalid token / revoked token / API rate limit all produce an empty picker with no notice.
- **1-hour transient TTL hardcoded** — newly created Typeforms take up to 1 hour to appear; no force-refresh link (unlike Formstack's cache-refresh link).
- **`eael_typeform_opacity` SLIDER mislabeled with `px` units** — cosmetic only.
- **Default `700px` height + fixed in SCSS** — mobile users see the same height; no responsive default.
- **Container styling can't affect iframe content** — Typeform's cross-origin iframe enforces same-origin policy. EA selectors only style the wrapper div.
- **No EA-custom title/description block** — like WeForms, must use a separate Heading widget.
- **No `eael_section_pro` upsell + zero hooks** — same lean profile as WPForms / Ninja / Gravity / Caldera / FluentForm / WeForms / Formstack. **7 of 9 Form Integrations are hooks-free + upsell-free.**
- **Center alignment uses fragile `position:absolute;transform:translateX(-50%)`** — same caveat as Formstack.
- **Token stored in plaintext in `wp_options`** — anyone with database access can extract it. Acceptable for a read-only scope token, but be aware.
- **No `is_dynamic_content()` override** — defaults to `false`; render cache active. The render output is just a placeholder div, so caching is fine.
- **No frontend AJAX integration with EA** — submission happens in the Typeform iframe; success/failure can't broadcast `eael.hooks.doAction(…)`. Typeform's `postMessage` events would need a separate listener.
- **`Group_Control_Border` / `_Box_Shadow` only affect the wrapper** — applying them creates a visible frame around the iframe, not around the form's perceived edge.
- **embed.js is the v1 / legacy API** — Typeform's modern recommended client is `@typeform/embed` v3+, but EA bundles the older `typeformEmbed` global. May lose compatibility if Typeform deprecates the legacy API.
- **No way to pass query params / pre-fill data** to the embed — Typeform supports `?your_field_name=Value` URL params for pre-filling, but EA's form URL is the bare `_links->display` string with no panel control to extend it.
