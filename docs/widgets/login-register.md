# Login | Register Form Widget

> Four-in-one auth widget — Login, Register, Lost Password, Reset Password — rendered as toggleable sections inside a single wrapper. Submits to the current page (not `wp_ajax_*`); form handler runs on `init` via the `Login_Registration` trait composed into `Bootstrap`. The largest single widget in Lite (~7,645 lines) and one of only two with a dedicated `docs/architecture/dynamic-data/` deep-dive.

**Class file:** [`includes/Elements/Login_Register.php`](../../includes/Elements/Login_Register.php)
**Slug:** `login-register` (widget id `eael-login-register`)
**Public docs:** <https://essential-addons.com/elementor/docs/login-register-form/>
**Pro-shared:** ✅ Yes — Pro adds social login (Google / Facebook), AJAX submission, input icons, Mailchimp / webhook integrations, register-field icon picker, and animated illustration character. Twenty-plus `do_action('eael/login-register/…')` injection points across `register_controls()` and `render()` host Pro features. The widget also re-emits the form-handler hook chain from the `Login_Registration` trait (composed into Bootstrap) — Pro listeners hang on the same `eael/login-register/*` names.

---

## Overview

Renders up to four `<section>` blocks inside one wrapper (`#eael-login-form-wrapper`, `#eael-register-form-wrapper`, `#eael-lostpassword-form-wrapper`, `#eael-resetpassword-form-wrapper`). Only one is visible at a time; toggle anchors (`#eael-lr-reg-toggle`, `#eael-lr-login-toggle`, …) swap visibility client-side with jQuery `fadeIn`/`hide` and push `?eael-register=1` / `?eael-lostpassword=1` to `history.replaceState`. Form submission posts to the current page; `Login_Registration::login_or_register_user()` runs on `init` and dispatches by `$_POST` flag. Non-AJAX errors round-trip via `eael_<action>_error_<widget_id>` cookies that the next render reads inline. Supports reCAPTCHA v2 + v3 + Cloudflare Turnstile, custom user-profile fields, custom email templates with `[placeholder]` substitution, and per-role registration.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Login, register, lost-password, reset-password forms | ✅ | ✅ |
| reCAPTCHA v2 + v3 | ✅ | ✅ |
| Cloudflare Turnstile | ✅ | ✅ |
| Custom user-profile fields (phone + arbitrary types) | ✅ via `eael_custom_profile_fields` option | ✅ |
| Custom email templates with placeholder substitution | ✅ | ✅ |
| Per-role registration, multi-role assignment | ✅ | ✅ |
| Lost / reset password full flow with email link | ✅ | ✅ |
| Social login (Google / Facebook) | ❌ — `enable_google_login` / `enable_fb_login` controls registered via `social_login_promo()` carry `classes => 'eael-pro-control'` and the `eael-pro-labe eicon-pro-icon` icon; toggling them has no effect ([line 1875](../../includes/Elements/Login_Register.php#L1875)) | ✅ via `eael/login-register/render_social_login_for_login_form` and `…_register_form` action listeners |
| AJAX form submission | ❌ — `enable_ajax` control rendered as `eael-pro-control` switcher ([line 729](../../includes/Elements/Login_Register.php#L729)), no `data-is-ajax="yes"` handler in Lite JS | ✅ — Pro JS branches on `isProAndAjaxEnabled` from `data-is-ajax` |
| Webhook integration | ❌ — `Enable Webhook` switcher is `eael-pro-control` only ([line 999](../../includes/Elements/Login_Register.php#L999)) | ✅ |
| Mailchimp integration controls | ❌ — `do_action('eael/login-register/mailchimp-integration', $this)` no-ops ([line 658](../../includes/Elements/Login_Register.php#L658)) | ✅ |
| Animated illustration character | ❌ — `do_action('eael/login-register/animated-character-controls', …)` / `…-style-controls` no-op ([lines 306, 331](../../includes/Elements/Login_Register.php#L306)) | ✅ |
| Input field icons (login / register / lostpassword / resetpassword) | ❌ — render() short-circuits `$show_icon` to `false` when `$this->pro_enabled` is false ([line 6299](../../includes/Elements/Login_Register.php#L6299)) | ✅ |
| Register-fields icon picker (per-repeater-row icon) | ❌ — repeater `title_field` falls back to label only ([line 2551](../../includes/Elements/Login_Register.php#L2551)) | ✅ |
| `eael_section_pro` upsell panel | shown via `show_pro_promotion()` ([line 2329](../../includes/Elements/Login_Register.php#L2329)) | hidden |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Login_Register.php`](../../includes/Elements/Login_Register.php) | PHP widget class (~7,645 lines) — controls, render branches for 4 form types, helpers for each form's header/footer/illustration |
| [`includes/Traits/Login_Registration.php`](../../includes/Traits/Login_Registration.php) | The form-handler trait (~1,855 lines) — composed into [`Bootstrap`](../../includes/Classes/Bootstrap.php#L32), hooked on `init` at [Bootstrap line 197](../../includes/Classes/Bootstrap.php#L197). Deep-dive: [`docs/architecture/dynamic-data/login-register.md`](../architecture/dynamic-data/login-register.md). |
| [`src/css/view/login-register.scss`](../../src/css/view/login-register.scss) | Source styles (489 lines) — form layouts, illustration positions, reCAPTCHA wrapper, password visibility toggle, error/success message styling |
| [`src/js/view/login-register.js`](../../src/js/view/login-register.js) | Frontend logic (312 lines) — section toggling, password visibility, reCAPTCHA render, Turnstile lazy render, nonce refresh, cookie-based error pickup |
| [`config.php`](../../config.php#L1090) entry `'login-register'` | `Asset_Builder` dependency declaration — self CSS + self JS |
| `assets/front-end/css/view/login-register.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/login-register.min.js` | Built output (do not edit) |
| Google reCAPTCHA (external CDN) | Vendor — `https://www.recaptcha.net/recaptcha/api.js` registered as `eael-recaptcha-v3` at [line 6182](../../includes/Elements/Login_Register.php#L6182); v2 handle `eael-recaptcha` declared in `get_script_depends()` |
| Cloudflare Turnstile (external CDN) | Vendor — `https://challenges.cloudflare.com/turnstile/v0/api.js` registered as `eael-cloudflare` in the constructor at [line 165](../../includes/Elements/Login_Register.php#L165), only when the site-wide Turnstile site key option is set |

## Architecture

- **Form handler lives in a trait, not the widget** — `Bootstrap` composes [`Login_Registration`](../../includes/Traits/Login_Registration.php) and registers `login_or_register_user()` on `init` at [Bootstrap line 197](../../includes/Classes/Bootstrap.php#L197). The widget's `render()` only emits markup; submission processing is decoupled, which means the handler fires regardless of whether the widget actually re-renders on the post-target page. See [`docs/architecture/dynamic-data/login-register.md`](../architecture/dynamic-data/login-register.md) for the full data flow, hook table, and recipes.
- **Four form sections, one render branch** — `render()` calls four printers in sequence: `print_resetpassword_form()`, `print_login_form()`, `print_register_form()`, `print_lostpassword_form()`. Each adds an `eael-lr-d-none` hide-class based on which form should be visible by default. The reset-password section is special — visible only when the user lands on the page via a password-reset email link OR when `preview_reset_password` is on in the editor.
- **`should_print_*` gating** — `should_print_login_form` / `_register_form` / `_lostpassword_form` are computed at [render() lines 6133-6136](../../includes/Elements/Login_Register.php#L6133) from `default_form_type` and `show_*_link` settings. The forms only ever print to the DOM when their gate is true — minimizes wasted markup when a widget is used as a one-shot register-only or lost-password-only form.
- **Twenty-plus `do_action` injection points for Pro** — Pro hooks listeners for social login (`render_social_login_for_login_form` / `…_register_form`), Mailchimp (`mailchimp-integration` panel, `mailchimp_user_consent_field` markup), animated character controls + style controls, and surround-points like `before-recaptcha`, `after-login-footer`, `after-password-field`. When Pro is inactive, these all no-op and the widget renders cleanly without Pro markup.
- **Bit Integrations cross-promo via `bit_integrations_promo()`** ([line 2358](../../includes/Elements/Login_Register.php#L2358)) — a "Connect & Automate" section appended to the bottom of the Content tab, after `do_action('eael/login-register/after-content-controls', $this)` and before `show_pro_promotion()`. Renders only when the Bit Integrations plugin is **not** active. Detection uses `is_plugin_active('bit-integrations/bitwpfi.php')` with a `class_exists('BitApps\\Integrations\\Config', false)` fallback — the plugin's WP.org folder slug `bit-integrations` does **not** match its main PHP file name `bitwpfi.php`, so a conventional slug-based check silently fails. The section is independent of `pro_enabled` (always evaluated; the conditional is purely "is the third-party plugin installed").
- **Static state on the trait carries email options across request boundaries** — `Login_Registration::$send_custom_email`, `$email_options`, `$email_options_lostpassword` are populated when the widget renders (read from saved settings) and consumed inside the form handler at submit time. This avoids re-reading `_elementor_data` inside the handler, but means **multiple Login_Register widgets on one page stomp each other's static state** — the last-rendered widget wins. The form handler then dispatches whichever widget submitted, which is usually correct, but custom email templates can be applied from the wrong widget if two sit on the same page.
- **Cookie-based error display (non-AJAX)** — when a non-AJAX submission fails security/validation gates, the handler sets `eael_<action>_error_<widget_id>` (e.g. `eael_login_error_abc1`) via raw `setcookie()` with no max-age, then `wp_safe_redirect( $_SERVER['HTTP_REFERER'] )`. The widget's JS at [line 212](../../src/js/view/login-register.js#L212) reads the cookie on next render, injects the error into `.eael-form-validation-container`, then deletes the cookie. **No referer → empty redirect → blank page** (documented pitfall — see Common Issues).
- **Filter name typo preserved for back-compat** — `eael/login-register/login-validatiob-error-message` ([trait line 206](../../includes/Traits/Login_Registration.php#L206)) is misspelled (**validatiob**). Third-party hooks rely on the exact name; do not rename without a dual-emit deprecation cycle.
- **JS uses the newer `eael.hooks.addAction("init", "ea", …)` pattern, not jQuery+`elementor/frontend/init`** — see [line 1 of view/login-register.js](../../src/js/view/login-register.js#L1). All toggle-anchor handlers, reCAPTCHA render, and Turnstile lazy-render live inside this single registration.

## Render Output

```html
<div class="eael-login-registration-wrapper [has-illustration]"
     data-is-ajax="[yes|no]"                                    ← Pro-only behaviour gate
     data-widget-id="{elementor element id}"
     data-page-id="{post id}"
     data-recaptcha-sitekey="{site-wide v2 key}"
     data-recaptcha-sitekey-v3="{site-wide v3 key}"
     data-login-recaptcha-version="[v2|v3]"
     data-register-recaptcha-version="[v2|v3]"
     data-lostpassword-recaptcha-version="[v2|v3]"
     data-redirect-to="{post-login URL}"
     data-resetpassword-redirect-to="{post-reset URL}">

  [?] <div data-logged-in-location="{redirect URL}"></div>      ← JS forces location.replace() when user is logged in

  <!-- Reset Password (visible only via email-link landing, or editor preview) -->
  [?] <section id="eael-resetpassword-form-wrapper" class="[eael-lr-d-none]"
               data-recaptcha-theme="…" data-recaptcha-size="…">
    <div class="eael-resetpassword-form-wrapper eael-lr-form-wrapper style-2">
      <form class="eael-resetpassword-form eael-lr-form" id="eael-resetpassword-form" method="post">…</form>
    </div>
  </section>

  <!-- Login -->
  [?] <section id="eael-login-form-wrapper" class="[eael-lr-d-none]"
               data-recaptcha-theme="…" data-recaptcha-size="…">
    <div class="eael-login-form-wrapper eael-lr-form-wrapper style-2 [lr-icon-showing]">
      <form class="eael-login-form eael-lr-form" id="eael-login-form" method="post">
        <input type="text"     name="eael-user-login"    id="eael-user-login"    required>
        <input type="password" name="eael-user-password" id="eael-user-password" required>
        [?] <p class="forget-menot"><input type="checkbox" name="eael-rememberme"> Remember Me</p>
        [?] <p class="forget-pass"><a href="…">Forgot Password?</a></p>
        [?] <div class="g-recaptcha" id="login-recaptcha-node-{widget-id}"></div>
        [?] <div class="cf-turnstile" data-sitekey="…"></div>
        <button type="submit" name="eael-login-submit">…</button>
        <input type="hidden" name="eael-login-nonce">           ← refreshed by JS from localize.nonce
        <input type="hidden" name="page_id">
        <input type="hidden" name="widget_id">
      </form>
    </div>
  </section>

  <!-- Register -->
  [?] <section id="eael-register-form-wrapper" class="[eael-lr-d-none]" …>
    <form class="eael-register-form eael-lr-form" id="eael-register-form" method="post">
      <!-- Repeater-driven fields: user_name, email, password, confirm_pass, first_name, last_name, website, honeypot, plus eael_custom_profile_field_* -->
      …
      <button type="submit" name="eael-register-submit">…</button>
    </form>
  </section>

  <!-- Lost Password -->
  [?] <section id="eael-lostpassword-form-wrapper" class="[eael-lr-d-none]" …>
    <form class="eael-lostpassword-form eael-lr-form" id="eael-lostpassword-form" method="post">
      <input type="text" name="eael-user-login" required>
      <button type="submit" name="eael-lostpassword-submit">…</button>
    </form>
  </section>

  [?] <div class="eael-recaptcha-no-branding-wrapper">          ← only when site-wide `eael_recaptcha_badge_hide` option is set
    <small>This site is protected by reCAPTCHA and the Google Privacy Policy and Terms of Service apply.</small>
  </div>
</div>
```

Notes:

- The four form `<section>`s are siblings; visibility is JS-driven via `fadeIn`/`hide` plus the `eael-lr-d-none` CSS class. Initial visibility is determined server-side from `default_form_type` and URL flags (`?eael-register=1`, `?eael-lostpassword=1`, `?eael-resetpassword=1`).
- `data-widget-id` and `data-page-id` are the two values the form-handler trait extracts from POST to call `lr_get_widget_settings()` and re-read the widget's saved config — without them the handler errors out with "Page ID is missing" / "Widget ID is missing".
- The wrapper's `data-is-ajax` attribute is read by Pro's JS only — Lite ignores it. Toggling the Lite-only `enable_ajax` switcher has no effect.
- Each form section carries its own `data-recaptcha-theme` / `data-recaptcha-size` so v2 widgets can have per-form styling. The v3 site key is wrapper-level.
- The optional `data-logged-in-location` `<div>` is rendered **only** when `redirect_for_logged_in_user` is `yes`, the user is logged in, and the current user does NOT have `manage_options` capability ([render line 6117](../../includes/Elements/Login_Register.php#L6117)) — admins are exempt so they can edit the page without being redirected away.
- The Cloudflare Turnstile widget needs explicit `turnstile.render()` calls inside popups and lightboxes — handled by [`renderTurnstile()` in login-register.js](../../src/js/view/login-register.js#L293) on `elementor/popup/show` and `ea-lightbox-triggered` events.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Login_Register.php#L273) dispatches into 30+ section initializers. The widget has the largest Content tab in the plugin — `register_controls()` spans ~5,800 lines.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `default_form_type` | SELECT | `login` | Content → General | Which form is visible on initial page load (`login` / `register` / `lostpassword`) |
| `enable_reset_password` | SWITCHER | `no` | Content → General | Whether to print the reset-password section — required for "Show Lost Password Form" → email link → reset flow |
| `preview_reset_password` | SWITCHER | `no` | Content → General | Forces reset-password section visible in the **editor only** for layout work |
| `hide_for_logged_in_user` | SWITCHER | `''` | Content → General | When `yes`, `render()` exits early on the frontend; editor still shows the form |
| `redirect_for_logged_in_user` | SWITCHER | `no` | Content → General | Emits the `data-logged-in-location` `<div>` that JS uses to `location.replace()` away |
| `redirect_url_for_logged_in_user` | URL | `site_url()` | Content → General | Target for the JS-driven redirect — same-domain only (validated server-side via `wp_validate_redirect`) |
| `show_register_link` / `show_login_link` / `show_lost_password` | SWITCHER | `yes` | Content → General (popover) | Inter-form toggle link visibility on each form |
| `registration_link_action` / `login_link_action` / `lost_password_link_type` | SELECT | `form` / `form` / `default` | Content → General (popover) | Link behaviour: `default` WP URL, `custom` URL, or in-place `form` swap |
| `enable_ajax` | SWITCHER | `''` | Content → General | **Pro-gated** — `classes => 'eael-pro-control'`; toggling has no Lite effect |
| `enable_login_register_recaptcha` | SWITCHER | `yes` | Content → Bot Protection | Master switch for Google reCAPTCHA |
| `login_register_recaptcha_version` | CHOOSE | `v2` | Content → Bot Protection | v2 (per-form opt-in) vs v3 (site-wide, score-based) |
| `enable_login_recaptcha` / `_register_recaptcha` / `_lostpassword_recaptcha` | SWITCHER | `''` | Content → Bot Protection | v2 only — per-form enable |
| `login_register_recaptcha_v3_score_threshold` | SLIDER | `0.5` | Content → Bot Protection | v3 score gate (range `[0, 1]`; out-of-range silently coerces to 0.5 — see Known Limitations) |
| `enable_cloudflare_turnstile` | SWITCHER | — | Content → Bot Protection | Toggles Cloudflare Turnstile (uses site-wide key from `eael_cloudflare_turnstile_sitekey` option) |
| Form Header → `*_form_logo` / `*_form_image` | MEDIA | empty | Content → Form Header | Per-form logo + illustration (login / register / lostpassword / resetpassword) |
| `login_label_types` / `register_label_types` / `lostpassword_label_types` | SELECT | `default` | Content → {Form} Fields | Label rendering: `default` translated, `custom` user-supplied, `none` hidden |
| `login_user_label` / `login_password_label` / `login_user_placeholder` / `login_password_placeholder` | TEXT | empty | Content → Login Fields | Override labels + placeholders when `login_label_types = custom` |
| `password_toggle` | SWITCHER | — | Content → Login Fields | Eye-icon visibility toggle on the password field |
| `login_show_remember_me` / `remember_text` / `login_form_fields_remember_me_checked` / `remember_me_style` | SWITCHER / TEXT / SWITCHER / SELECT | various | Content → Login Fields | "Remember Me" checkbox + label + default-checked + radio/checkbox style |
| `register_fields` | REPEATER | (user_name, email, password) | Content → Register Form Fields | Register form field set — see Per-item table below |
| `register_user_role` | SELECT2 (multiple) | (default WP role) | Content → Register Options | Roles assigned on register; multi-select |
| `redirect_after_login` / `redirect_url` | SWITCHER / URL | `no` / empty | Content → Login Options | Post-login redirect override (else HTTP_REFERER) |
| `redirect_after_register` / `redirect_url_register` | SWITCHER / URL | `no` / empty | Content → Register Actions | Post-register redirect override |
| `enable_google_login` / `enable_fb_login` | SWITCHER | — | Content → Social Login | **Pro-gated** — `classes => 'eael-pro-control'`; toggling has no Lite effect |
| `show_terms_conditions` / `acceptance_label` / `acceptance_text_source` | SWITCHER / TEXTAREA / SELECT | `no` / `"I Accept the Terms…"` / `custom` | Content → Terms & Conditions | Required-checkbox gate for register form |
| `lostpassword_email_message_reset_link_in_popup` / `…_popup_selector` | SWITCHER / TEXT | `no` / empty | Content → Reset Password Options | Re-route reset link into an Elementor popup via CSS selector |
| `enable_webhook` | SWITCHER | — | Content → Reset Password Options | **Pro-gated** — `classes => 'eael-pro-control'`; no Lite effect |
| `err_message_position_login` / `_register` / `_lostpassword` | SELECT | `top` | Content → Error Messages | Where validation errors render relative to the form |
| `eael_control_get_pro` | CHOOSE | `1` | Content → Go Premium for More Features | Decorative — `eael_section_pro` upsell only (hidden when Pro active) |
| `eael_bit_integrations_promo_notice` | RAW_HTML | — | Content → Connect & Automate | Decorative — Bit Integrations cross-promo only; the whole section is hidden when the Bit Integrations plugin is active. CTA links to the bitapps.pro affiliate redirect. Section position: after `after-content-controls` action, before `eael_section_pro` |
| Style → various | — | — | Style tab | ~25 style sections covering general / form header / fields / labels / per-form button (4×) / per-form link (2×) / per-form reCAPTCHA wrapper (3×) |

### Per-item Repeater controls (`register_fields`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `field_type` | SELECT | `user_name` | Field shape: `user_name`, `email`, `password`, `confirm_pass`, `first_name`, `last_name`, `website`, `honeypot`, plus `eael_phone_number` and any registered `eael_custom_profile_field_*` (when site-wide `eael_custom_profile_fields` option is `on`) |
| `field_label` | TEXT | `Username` | Field label (shown when `show_labels=yes`) |
| `placeholder` | TEXT | `Username` | Input placeholder; suppressed for `honeypot` |
| `required` | SWITCHER | — | Per-field required marker; ignored for `email` / `password` / `confirm_pass` (always required) / `honeypot` (never required) |
| `width` | RESPONSIVE SLIDER (px / %) | `100%` | Per-field grid width — controls flex-basis for two-column layouts |
| `icon` | ICONS | (none) | **Pro-only** — picker shown only when `pro_enabled`; Lite repeater `title_field` falls back to `{{ field_label }}` |

## Conditional Dependencies

```text
preview_reset_password              → visible when enable_reset_password == 'yes'
redirect_url_for_logged_in_user     → visible when redirect_for_logged_in_user == 'yes'
log_out_link_text                   → visible when show_log_out_message == 'yes'
lost_password_text / _link_type     → visible when show_lost_password == 'yes'
lost_password_url                   → visible when lost_password_link_type == 'custom' AND show_lost_password == 'yes'
registration_link_text / _action    → visible when show_register_link == 'yes'
custom_register_url                 → visible when registration_link_action == 'custom' AND show_register_link == 'yes'
registration_off_notice             → visible when default_form_type == 'register' AND users_can_register option is false
remember_text / _checked            → visible when login_show_remember_me == 'yes'
login_register_recaptcha_v3_description / _threshold → visible when login_register_recaptcha_version == 'v3' AND enable_login_register_recaptcha == 'yes'
enable_login_recaptcha / _register / _lostpassword → visible when recaptcha_version == 'v2' AND enable_login_register_recaptcha == 'yes'
register_user_role                  → only registered when users_can_register option is true
show_register_link (HIDDEN)         → forced when users_can_register option is false
acceptance_text                     → visible when show_terms_conditions == 'yes' AND acceptance_text_source == 'editor'
preview_reset_password effect       → applied only in editor (in_editor flag); ignored on frontend

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
enable_google_login / enable_fb_login → always visible BUT carry `eael-pro-control` class — toggling them is no-op in Lite
enable_ajax / enable_webhook        → registered only when !pro_enabled, also `eael-pro-control` — Lite-only "promo" controls
eael_section_bit_integrations_promo → visible when the Bit Integrations plugin is NOT active (checked via `is_plugin_active('bit-integrations/bitwpfi.php')` + `class_exists('BitApps\Integrations\Config')` fallback) — independent of Pro status
```

The widget's panel is **gated by the site-wide WP option `users_can_register`** (Dashboard → Settings → General → Membership). When that option is OFF, the registration-related controls collapse to `HIDDEN` defaults at [lines 591](../../includes/Elements/Login_Register.php#L591) and [662](../../includes/Elements/Login_Register.php#L662). The "registration is disabled on your site" notice at [line 360](../../includes/Elements/Login_Register.php#L360) tells the user where to enable it.

## Hooks & Filters

The widget itself emits ~30 `do_action` injection points + several `apply_filters` chains. The trait emits another ~15 (covered in [`docs/architecture/dynamic-data/login-register.md`](../architecture/dynamic-data/login-register.md)). Below is the widget-side summary; for handler-side hooks see the architecture doc.

### Widget-side actions (extension points)

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/login-register/before-content-controls` / `after-content-controls` | actions (emitted in `register_controls()`) | `(Widget_Base $widget)` | Pro adds extra Content-tab sections at start/end |
| `eael/login-register/before-style-controls` / `after-style-controls` | actions | `(Widget_Base $widget)` | Pro adds extra Style-tab sections |
| `eael/login-register/after-login-controls-section` | action | `(Widget_Base $widget)` | Pro inserts social-login real controls (Lite shows promo) |
| `eael/login-register/animated-character-controls` / `…-style-controls` | actions | `(Widget_Base $widget)` | Pro registers animated illustration content + style controls |
| `eael/login-register/after-general-controls` | action | `(Widget_Base $widget)` | General-section extension point |
| `eael/login-register/mailchimp-integration` | action | `(Widget_Base $widget)` | Pro adds Mailchimp panel inside the Register General popover |
| `eael/login-register/after-register-options-controls` | action | `(Widget_Base $widget)` | Pro adds register-options extras (consent field config, etc.) |
| `eael/login-register/after-init-{button_type}-button-style` | action | `(Widget_Base $widget, string $button_type)` | Per-form button style extension; `button_type ∈ {login, register, lostpassword, resetpassword}` |
| `eael/login-register/after-pass-visibility-controls` | action | `(Widget_Base $widget)` | Extension point inside Login Fields section |
| `eael/login-register/render_social_login_for_login_form` / `…_register_form` | actions (emitted in `render()`) | `(Widget_Base $widget)` | Pro emits Google + Facebook OAuth buttons at the chosen `position_for_login_form` / `position_for_register_form` |
| `eael/login-register/before-login-form` / `after-login-form` | actions | `(Widget_Base $widget)` | Wrap the `<form>` element |
| `eael/login-register/after-login-form-open` / `before-login-form-close` | actions | `(Widget_Base $widget)` | Wrap form content |
| `eael/login-register/before-recaptcha` / `after-recaptcha` | actions | `(Widget_Base $widget)` | Wrap the reCAPTCHA `<div>` |
| `eael/login-register/before-login-footer` / `after-login-footer` | actions | `(Widget_Base $widget)` | Wrap the submit + register-link footer |
| `eael/login-register/after-password-field` | action | `(Widget_Base $widget)` | Hook after the register form's password input (Pro adds strength meter) |
| `eael/login-register/after-email-field` | action | `()` | Hook after the register form's email input (Pro adds consent / extras) |
| `eael/login-register/mailchimp_user_consent_field` | action | `(Widget_Base $widget)` | Pro renders Mailchimp consent checkbox |
| `eael/login-register/before-register-form` / `after-register-form` / `…-open` / `…-close` / `…-recaptcha` / `…-footer` | actions | `(Widget_Base $widget)` | Same wrap pattern, register form |
| `eael/login-register/before-lostpassword-form` / `…-open` / `…-close` / `…-recaptcha` / `…-footer` | actions | `(Widget_Base $widget)` | Same wrap pattern, lostpassword form |
| `eael/login-register/before-resetpassword-form` / `…-open` / `…-close` / `…-footer` | actions | `(Widget_Base $widget)` | Same wrap pattern, resetpassword form |
| `eael/login-register/before-showing-lostpassword-error` / `…-success` / `before-showing-resetpassword-error` | actions | `($message, Widget_Base $widget)` | Wrap success/error banners |
| `eael/login-register/after-showing-login-error` | action | `($message, Widget_Base $widget)` | Wrap error banner after content |

### Widget-side filters

| Filter | Type | Signature | Purpose |
| ------ | ---- | --------- | ------- |
| `eael/login-register/scripts` | filter (emitted) | `array $script_handles` | Add/remove script handles in `get_script_depends()` |
| `eael/login-register/styles` | filter (emitted) | `array $style_handles` | Add/remove style handles in `get_style_depends()` (dashicons baked in) |
| `eael/registration-form-fields` | filter (emitted) | `array $field_types` | Add custom field types to the repeater's `field_type` SELECT options |
| `eael/login-register/register-repeater` | filter (emitted, return ignored) | `Repeater $repeater` | Mutate the repeater object before its controls are registered ⚠️ — the return value is discarded; mutate by reference |
| `eael/login-register/register-repeater-fields` | filter (emitted) | `array $controls` | Add/replace controls in the register-fields repeater |
| `eael/login-register/register-rf-default` | filter (emitted) | `array $defaults` | Replace the default register field set (user_name / email / password) |
| `eael/login-register/login-redirect-url` | filter (emitted) | `string $url, Widget_Base $widget` | Override post-login redirect URL |
| `eael/login-register/lostpassword-success-message` / `…-error-message` | filters | `$message` | Override messages shown after lost-password flow |
| `eael/login-register/resetpassword-success-message` / `…-error-message` | filters | `$message` | Override messages shown after reset-password flow |
| `eael/login-register/register-form-markup` | filter | `string $html` | Mutate register form HTML before output |
| `eael_lr_recaptcha_api_args_v3` | filter | `array $api_args` | Modify reCAPTCHA v3 script URL query args |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Gates social-login promo registration, register-row icon picker, input-icon rendering, ajax control registration, webhook control registration, `eael_section_pro` upsell, and several render-time `$show_icon` flags |

For form-handler hooks (`eael/login-register/before-login`, `…/after-insert-user`, `eael_recaptcha_threshold`, etc.) see [`docs/architecture/dynamic-data/login-register.md § Hook Timing`](../architecture/dynamic-data/login-register.md#hook-timing).

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", …)` ([line 1](../../src/js/view/login-register.js#L1)) — registers an `elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister)` callback inside.
- **Guard:** `if (eael.elementStatusCheck('eaelLoginRegister')) return false;` — prevents double-registration.
- **Vendor dependencies:**
  - `grecaptcha` (Google reCAPTCHA v2/v3) — loaded via the `eael-recaptcha` handle in `get_script_depends()` or `eael-recaptcha-v3` registered at [PHP line 6182](../../includes/Elements/Login_Register.php#L6182).
  - `turnstile` (Cloudflare) — loaded via the `eael-cloudflare` script registered in the widget constructor at [line 165](../../includes/Elements/Login_Register.php#L165).
- **Reads on init:**
  - `data-widget-id`, `data-recaptcha-sitekey`, `data-recaptcha-sitekey-v3`, `data-is-ajax`, `data-login-recaptcha-version` / `_register_recaptcha_version` / `_lostpassword_recaptcha_version` from the wrapper.
  - `data-recaptcha-theme` / `data-recaptcha-size` per form section.
  - `data-logged-in-location` from the optional `<div>` — forces `location.replace()` on first init.
  - Cookies `eael_login_error_<widget_id>` and `eael_register_errors_<widget_id>` — injected into `.eael-form-validation-container` and then deleted.
- **Branches:**
  - Section toggle handlers wired only when the corresponding link's `data-action` is `form` (vs `default` / `custom` URL).
  - reCAPTCHA initialisation skipped when `isProAndAjaxEnabled` is true (Pro takes over).
  - v3 token injection skipped when `loginRecaptchaVersion / registerRecaptchaVersion / lostpasswordRecaptchaVersion` are all v2.
  - reCAPTCHA `render()` calls are wrapped in `try/catch` because Elementor's editor re-fires `frontend/element_ready` on every save and grecaptcha throws on duplicate render.
- **Runtime state:**
  - Toggle handlers manipulate `URLSearchParams` to push `?eael-register=1` / `?eael-lostpassword=1` via `history.replaceState` — so deep-linking lands the user on the correct section.
  - `localStorage.setItem('eael-is-login-form', 'true')` is set on login submit and consumed on next page load to auto-click `#eael-lr-login-toggle` after a 100ms delay. This makes the login section visible again if a redirect lands the user back on the page.
  - Nonce refresh promise: `eael.getToken()` polls until `localize.nonce` is populated, then writes the value into the four form `<input type="hidden" name="eael-*-nonce">` fields — keeps nonces fresh on long-cached pages.
- **Custom events / API:**
  - Listens for `elementor/popup/show` (jQuery event) and `ea-lightbox-triggered` (`eael.hooks` action) to call `renderTurnstile($scope)` when the widget sits inside a popup or lightbox — `turnstile.render()` must run after the widget is in the DOM.
  - Exposes no globals.

## Common Issues

### "Page ID is missing" or "Widget ID is missing" cookie error after submit

- **Likely cause:** A page-caching or HTML-minifier plugin is stripping the form's hidden `<input name="page_id">` / `<input name="widget_id">` fields because it doesn't recognise them.
- **Diagnose:** View page source after caching is on; search for `name="page_id"`. If absent, the cache is the culprit.
- **Fix:** Exclude pages with the login/register widget from full-page cache, or add `page_id` / `widget_id` to the cache plugin's allowed-input list. Same issue surfaces with WP Rocket "Remove unused CSS" if it strips hidden inputs.

### reCAPTCHA v3 silently passes everyone or silently fails everyone

- **Likely cause:** The score threshold is outside `[0, 1]` and silently coerced to 0.5 ([trait line 42](../../includes/Traits/Login_Registration.php#L42)). Or the v3 site key is for a different domain.
- **Diagnose:** In browser devtools → Network, inspect the `siteverify` response — the `score` field shows what Google returned. Compare to the threshold; if your threshold is the default 0.5 and the score is 0.3, the user is correctly being flagged as a bot.
- **Fix:** Tune `login_register_recaptcha_v3_score_threshold` (default 0.5; lower for more permissive, higher for stricter). Confirm site key matches the production domain in the Google reCAPTCHA admin.

### Blank page after failed login on a direct-traffic visitor

- **Likely cause:** Non-AJAX handler's `wp_safe_redirect( $_SERVER['HTTP_REFERER'] )` fails when there is no referer (direct curl, bookmark with strict referer policy). Redirect resolves to empty string → no redirect → blank PHP response.
- **Diagnose:** Reproduce by opening the page in a fresh tab without coming from another URL, then submit a deliberately wrong credential. See blank page in Network tab with no Location header.
- **Fix:** Hook `eael/login-register/login-validatiob-error-message` (note misspelling) to short-circuit, or patch the trait to use `home_url()` when `HTTP_REFERER` is missing. Cleanest is enabling Pro AJAX, which keeps the response on the same URL.

### Stale "remember me" / form toggle after redirect

- **Likely cause:** The widget's JS stores `eael-is-login-form` in `localStorage` to re-open the login section after a redirect. If that flag is set but the new page doesn't have the toggle anchor, the `#eael-lr-login-toggle` click silently fails and the user lands on the wrong default section.
- **Diagnose:** Check `localStorage.getItem('eael-is-login-form')` in devtools. If `'true'` and `#eael-lr-login-toggle` is missing, that's the cause.
- **Fix:** Either use a single widget across the entire flow (so the anchor is consistent), or `localStorage.removeItem('eael-is-login-form')` before navigating.

### Custom user-profile field not saving on register

- **Likely cause:** The site-wide option `eael_custom_profile_fields` is not `'on'` — custom fields are gated by it at [Bootstrap line 202](../../includes/Classes/Bootstrap.php#L202) and at [widget line 261](../../includes/Elements/Login_Register.php#L261). Without that option, the field types don't even appear in the repeater's `field_type` SELECT.
- **Diagnose:** `get_option('eael_custom_profile_fields')` should return `'on'`. Check `wp_options` directly.
- **Fix:** Enable EA's "Custom Profile Fields" toggle in the EA dashboard settings. Then declare the field via the EA admin UI or via the `eael/registration-form-fields` filter.

### Two Login_Register widgets on one page send the wrong custom email

- **Likely cause:** Trait-level `Login_Registration::$email_options` is static. The last widget to render overwrites the first widget's email config; the form handler picks whichever was last set.
- **Diagnose:** Put both widgets with distinct email subjects. Submit from the first widget. Check the received email — if it has the second widget's subject, you've reproduced.
- **Fix:** Use one Login_Register widget per page. If multiple are needed, hook `eael/login-register/new-user-email-data` to differentiate by `widget_id` in `$_POST`.

## Known Limitations

- **Static state shared across multiple widgets on one page** — `Login_Registration::$email_options`, `$send_custom_email`, `$send_custom_email_admin`, `$send_custom_email_lostpassword` are class-level static properties ([trait lines 20-36](../../includes/Traits/Login_Registration.php#L20)). When two Login_Register widgets coexist on a page, the last to render overwrites the others. Documented but not fixed.
- **Filter misspelling `validatiob`** ([trait line 206](../../includes/Traits/Login_Registration.php#L206)) — `eael/login-register/login-validatiob-error-message` is preserved verbatim for back-compat. Third-party hooks rely on it.
- **reCAPTCHA v3 threshold silently coerces out-of-range values to 0.5** ([trait line 42](../../includes/Traits/Login_Registration.php#L42)). No admin notice. Users who enter 1.5 expect "max strict" but get the default.
- **`wp_safe_redirect($_SERVER['HTTP_REFERER'])` blanks the page when referer is empty** ([trait lines 88-91](../../includes/Traits/Login_Registration.php#L88), and parallel sites in `register_user` / `send_password_reset`). No `home_url()` fallback.
- **The `data-is-ajax` attribute is emitted in Lite but only honoured by Pro** ([render line 6188](../../includes/Elements/Login_Register.php#L6188)). Setting `enable_ajax = yes` in Lite emits `data-is-ajax="yes"` to the DOM but the Lite JS at [line 12](../../src/js/view/login-register.js#L12) reads it without actually changing submission behaviour. Pro's JS branches on it.
- **`apply_filters('eael/login-register/register-repeater', $repeater)` discards its return value** ([line 2525](../../includes/Elements/Login_Register.php#L2525)) — the return is not captured, so the filter must mutate the Repeater object by reference. Subtle gotcha for extension authors.
- **Cookie-based error display has no `max-age`** — `setcookie( 'eael_login_error_<widget_id>', $err_msg )` ([trait line 86](../../includes/Traits/Login_Registration.php#L86)) defaults to session cookie. If the user navigates away without re-rendering the widget, the cookie persists until session end. JS only deletes it after reading.
- **No `content_template()` stub** — editor preview uses server-side `render()` via AJAX on every settings change. With ~7,645 lines and ~30 sections, the editor is noticeably slower for this widget than for simpler ones.
- **The login-register `register-repeater` filter passes the Repeater by reference but is documented as a filter** — most filters return modified values. Test third-party hooks against both reference-mutation and value-return styles.
- **`Login_Registration` trait's form handler fires on EVERY `init`** ([Bootstrap line 197](../../includes/Classes/Bootstrap.php#L197)), regardless of whether the current page contains the widget. Cheap when no `$_POST['eael-*-submit']` flag is present (early return), but the cost adds up on busy admin-ajax endpoints.
- **CDN-loaded reCAPTCHA + Turnstile scripts** — both are registered with hardcoded external URLs. Sites with strict CSP `script-src` must whitelist `https://www.recaptcha.net` and `https://challenges.cloudflare.com`.
