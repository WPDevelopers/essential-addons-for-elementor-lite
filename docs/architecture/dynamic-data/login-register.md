# Login & Registration

The largest single subsystem in the plugin — 1,855 lines in [`includes/Traits/Login_Registration.php`](../../../includes/Traits/Login_Registration.php) — that powers the Login | Register Form widget. Handles four form actions (login, register, lost password, reset password), reCAPTCHA v2 / v3 / Cloudflare Turnstile, custom user-profile fields, email-template customisation with placeholders, and graceful error display via cookies after a non-AJAX redirect.

If you're tracing "why doesn't custom field X save on register?" or "why is the reCAPTCHA threshold ignored?" or "where do redirect URLs come from?" — this is the doc.

## Overview

Unlike the AJAX endpoints documented in [`ajax-endpoints.md`](ajax-endpoints.md), the Login | Register widget does not register `wp_ajax_*` actions for its main flows. It uses **form submissions to the current page**, with `$_POST` flags that identify which sub-action to run. The trait's [`login_or_register_user()`](../../../includes/Traits/Login_Registration.php#L46) entry point dispatches based on which submit field is present:

| `$_POST` flag | Sub-handler | Purpose |
| ------------- | ----------- | ------- |
| `eael-login-submit` | `log_user_in()` | Authenticate and redirect |
| `eael-register-submit` | `register_user()` | Create user, send notifications, redirect |
| `eael-lostpassword-submit` | `send_password_reset()` | Email reset link |
| `eael-resetpassword-submit` | `reset_password()` | Apply new password |

Each handler does its own security triad — nonce verify, sanitize input, validate via reCAPTCHA / Turnstile if enabled — then invokes `wp_signon()` / `wp_create_user()` / `retrieve_password()` / `reset_password()` (the WordPress core APIs) and either responds via AJAX (when `wp_doing_ajax()`) or sets an error/success cookie and redirects via `wp_safe_redirect( HTTP_REFERER )`.

The cookie-based error pattern is the key UX hack: after a non-AJAX redirect, the widget render() reads `eael_<action>_error_<widget_id>` cookies and shows the error inline on the page that posted the form. This works without sessions or transients.

## Components

| File / Method | Lines | Role |
| ------------- | ----- | ---- |
| [`includes/Traits/Login_Registration.php`](../../../includes/Traits/Login_Registration.php) | 1,855 | The trait composed into Bootstrap. Owns every public method below. |
| [`login_or_register_user()`](../../../includes/Traits/Login_Registration.php#L46) | ~15 | Dispatch entry-point — fires at form-handle time |
| [`log_user_in()`](../../../includes/Traits/Login_Registration.php#L65) | ~210 | Login flow: nonce + reCAPTCHA + `wp_signon` + redirect |
| [`register_user()`](../../../includes/Traits/Login_Registration.php#L276) | ~510 | Register flow: nonce + reCAPTCHA + validation + `wp_create_user` + custom-fields + roles + email |
| [`send_password_reset()`](../../../includes/Traits/Login_Registration.php#L790) | ~215 | Lost password flow: validates user, runs `retrieve_password()`, custom email |
| [`reset_password()`](../../../includes/Traits/Login_Registration.php#L1006) | ~170 | Reset password flow: validates `key` + `login`, sets new password, signs user in |
| [`lr_validate_recaptcha()`](../../../includes/Traits/Login_Registration.php#L1629) | ~25 | Server-side validation against Google reCAPTCHA API (v2 + v3) |
| [`lr_validate_cloudflare_turnstile()`](../../../includes/Traits/Login_Registration.php#L1654) | ~24 | Server-side validation against Cloudflare Turnstile |
| [`lr_get_widget_settings()`](../../../includes/Traits/Login_Registration.php#L1678) | ~18 | Retrieves saved widget settings from `_elementor_data` (same pattern as load-more) |
| [`new_user_notification_email()`](../../../includes/Traits/Login_Registration.php#L1386) | ~68 | Custom new-user email body / subject |
| [`replace_placeholders()`](../../../includes/Traits/Login_Registration.php#L1485) | ~45 | `[user_login]`, `[user_email]`, `[firstname]` etc. replacements |
| [`get_eael_custom_profile_fields()`](../../../includes/Traits/Login_Registration.php#L1816) | ~40 | Reads custom profile fields registered via filter |
| [`eael_extra_user_profile_fields()` / `_save_extra_user_profile_fields()`](../../../includes/Traits/Login_Registration.php#L1708) | ~50 + 25 | Renders + saves custom fields on the WP admin profile screen |
| [`eael_is_phone()`](../../../includes/Traits/Login_Registration.php#L1795) | ~17 | Phone-field validation |
| [`generate_username_from_email()`](../../../includes/Traits/Login_Registration.php#L1277) | ~46 | Auto-username when register form omits it |

Static state worth knowing:

```php
public static $send_custom_email             = false;
public static $send_custom_email_admin       = false;
public static $send_custom_email_lostpassword = false;
public static $email_options                 = [];   // populated by widget render
public static $email_options_lostpassword    = [];
public static $eael_custom_profile_field_prefix = 'eael_custom_profile_field_';
public static $recaptcha_v3_default_action   = 'eael_login_register_form';
```

These are populated when the widget renders (so its settings reach the form-handler at submit time without re-reading the document) and consulted inside the email-customisation methods.

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ FORM RENDER PHASE                                                ║
║                                                                  ║
║   Login | Register widget render()                               ║
║       │  reads $settings (email options, redirect URLs,          ║
║       │    reCAPTCHA flags, custom fields, role)                 ║
║       │                                                          ║
║       ▼                                                          ║
║   Static class state populated:                                  ║
║     $email_options, $email_options_lostpassword,                 ║
║     $send_custom_email, $send_custom_email_admin                 ║
║       │                                                          ║
║       ▼                                                          ║
║   <form action="<current page>" method="post">                   ║
║     <input name="eael-login-nonce" value="<nonce>">              ║
║     <input name="page_id" value="<post_id>">                     ║
║     <input name="widget_id" value="<elementor element id>">      ║
║     …login / register / reset fields…                            ║
║     <button name="eael-login-submit">Log In</button>             ║
║   </form>                                                        ║
╚══════════════════════════════════════════════════════════════════╝
                           │
                           ▼ user submits form
╔══════════════════════════════════════════════════════════════════╗
║ FORM HANDLER PHASE (init / wp / template_redirect)               ║
║                                                                  ║
║   login_or_register_user() runs (hooked early enough that        ║
║   wp_signon and wp_safe_redirect work)                           ║
║       │                                                          ║
║       ▼  $_POST flag dispatch                                    ║
║   ┌───────────────┬───────────────┬──────────────────────┐       ║
║   │ login         │ register      │ lostpassword         │       ║
║   │ log_user_in   │ register_user │ send_password_reset  │       ║
║   └───────────────┴───────────────┴──────────────────────┘       ║
║                                                                  ║
║   Each handler:                                                  ║
║     1. Validate page_id, widget_id (sets $err_msg if missing)    ║
║     2. Nonce check (eael-<action>-nonce, action                  ║
║        'essential-addons-elementor')                             ║
║     3. Settings retrieval via lr_get_widget_settings             ║
║     4. reCAPTCHA / Turnstile validation if enabled               ║
║     5. Field validation (passwords match, email format, …)       ║
║     6. WordPress core call:                                      ║
║        • wp_signon (login)                                       ║
║        • wp_create_user / wp_insert_user (register)              ║
║        • retrieve_password (lostpassword)                        ║
║        • reset_password (reset)                                  ║
║     7. Side effects: do_action('eael/login-register/…'),         ║
║        custom emails, cookie-based error display, mailchimp,     ║
║        custom field persistence                                  ║
║     8. AJAX response or wp_safe_redirect                         ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ ERROR DISPLAY (non-AJAX)                                         ║
║                                                                  ║
║   handler sets cookie:                                           ║
║     eael_login_error_<widget_id>=<error message>                 ║
║   then wp_safe_redirect( HTTP_REFERER ) → exit                   ║
║       │                                                          ║
║       ▼ browser follows redirect → page re-renders               ║
║   Login | Register widget render() reads cookies                 ║
║   and displays errors inline                                     ║
║       │                                                          ║
║       ▼                                                          ║
║   Cookie cleared after first read (or expires automatically)     ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ RESET PASSWORD LINK FLOW (out-of-band)                           ║
║                                                                  ║
║   Email contains link → /<reset-page>/?key=<key>&login=<login>   ║
║   eael_redirect_to_reset_password() detects key + login          ║
║   sets cookies/transients → redirects to widget page             ║
║   widget render() shows reset password form                      ║
║   User submits → reset_password() runs → password updated        ║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

The trait emits a rich set of `eael/login-register/*` hooks at every meaningful point. External integrations (custom redirects, mailchimp, marketing automation, audit logs) hook into these instead of forking the trait.

### Actions (do_action)

| Action | When | Args | Purpose |
| ------ | ---- | ---- | ------- |
| `eael/login-register/before-processing-login-register` | Top of `login_or_register_user` | `$_POST` | Pre-dispatch hook for any sub-action |
| `eael/login-register/after-processing-login-register` | Bottom of `login_or_register_user` | `$_POST` | Post-dispatch hook |
| `eael/login-register/before-login` | Top of `log_user_in`, after security gate | `$_POST, $settings, $this` | Add login pre-checks (e.g. ban list) |
| `eael/login-register/after-login` | After `wp_signon` succeeds | `$user_login, $user_data, $settings` | Audit log, custom analytics |
| `eael/login-register/before-register` | Top of `register_user` validation | — | Pre-register checks |
| `eael/login-register/before-insert-user` | Just before `wp_insert_user` / `wp_create_user` | `$user_data` | Last chance to mutate insert data |
| `eael/login-register/after-insert-user` | After user is created | `$user_id, $user_data, $settings` | Post-register side effects |
| `eael/login-register/mailchimp-integration-action` | After insert user | `$user_id, $user_data, $settings` | Reserved hook for mailchimp tie-in |
| `eael/login-register/before-lostpassword-email` | Before sending lostpassword email | — | Custom mailer config |
| `eael/login-register/before-resetpassword-email` | Before reset-password notification | — | Custom mailer config |
| `wp_login` (WP core) | After login success | Standard | Re-fired with EA's login flow context |
| `register_new_user` (WP core) | After register success | `$user_id` | Re-fired |

### Filters (apply_filters)

| Filter | Purpose |
| ------ | ------- |
| `eael_recaptcha_threshold` | Override v3 score threshold (default 0.5) |
| `eael/login-register/login-validatiob-error-message` | ⚠️ misspelled (**validatiob**) — kept for back-compat — modify login validation error message |
| `eael/login-register/register-user-password-validation` | Add custom password rules during register |
| `eael/login-register/register-redirect-url` | Override post-register redirect target |
| `eael/login-register/new-user-data` | Mutate the data passed to `wp_insert_user` |
| `eael/login-register/new-user-roles` | Customise the roles a newly registered user gets |
| `eael/login-register/new-user-email-data` | Customise the user-facing welcome email |
| `eael/login-register/new-user-admin-email-data` | Customise the admin notification email |
| `eael/login-register/wp-login-url` | Override the wp-login.php URL used in email links |
| `eael_lr_recaptcha_api_args` | Modify reCAPTCHA frontend script args (render mode, language) |

## Data Flow

End-to-end registration with reCAPTCHA v3 + custom fields + custom email:

1. **Form render.** Widget `render()` outputs the form HTML, populates static `$email_options` from settings, registers `<script>` tags for reCAPTCHA if enabled (via the `Enqueue` trait's compat shim).
2. **User submits.** Browser POSTs to current page. Form fields include `eael-register-submit`, `eael-register-nonce`, `page_id`, `widget_id`, `email`, `password`, `confirm_password`, custom fields prefixed `eael_custom_profile_field_*`, plus reCAPTCHA token in `g-recaptcha-response`.
3. **Form-handler hook fires** (registered in Bootstrap on a form-time hook like `init` / `wp` — check Bootstrap registration). `login_or_register_user()` runs.
4. **Dispatch.** `eael-register-submit` is set → `register_user()`.
5. **`register_user` validates basics.** `page_id`, `widget_id`, nonce. On failure: AJAX → `wp_send_json_error`; non-AJAX → set `eael_register_error_<widget_id>` cookie + `wp_safe_redirect`.
6. **Widget settings retrieved.** `lr_get_widget_settings($page_id, $widget_id)` returns the form's saved configuration (custom fields list, default role, redirect URL, email templates).
7. **reCAPTCHA v3 validation.** `lr_validate_recaptcha('v3', $settings)` POSTs the token to Google. If response score is below `get_recaptcha_threshold($settings)` (default 0.5, filterable), validation fails. On failure: error cookie + redirect.
8. **Field validation loop.** Email format, password matches confirm-password, password meets `eael/login-register/register-user-password-validation` rules (filterable), required fields present.
9. **Build user data.** `$user_data = [ 'user_login' => …, 'user_email' => …, 'user_pass' => …, 'role' => filtered roles ]`. Username generated from email if not provided ([line 1277](../../../includes/Traits/Login_Registration.php#L1277)).
10. **Filter chain.**
    - `eael/login-register/register-redirect-url` — let extensions override redirect
    - `eael/login-register/new-user-data` — let extensions mutate `$user_data`
    - `eael/login-register/new-user-roles` — let extensions adjust `$user_data['role']`
11. **`do_action('eael/login-register/before-insert-user', $user_data)`** — last hook for inspection.
12. **`wp_insert_user( $user_data )`** runs. Returns user id or `WP_Error`.
13. **Custom fields persistence.** Loop the form's custom fields, save each as user meta with the `eael_custom_profile_field_` prefix.
14. **`do_action('eael/login-register/after-insert-user', $user_id, $user_data, $settings)`** + `do_action('eael/login-register/mailchimp-integration-action', …)`.
15. **Email send.** If custom email template configured, `replace_placeholders` substitutes `[user_login]` / `[user_email]` / etc., then `wp_mail` ships it. Admin notification is parallel (filterable separately).
16. **`do_action('register_new_user', $user_id)`** — WP core hook re-emitted for compat with other plugins.
17. **Auto-login (if configured).** `wp_signon` fires → user session active.
18. **Redirect.** Either AJAX `wp_send_json_success` with redirect URL OR `wp_safe_redirect( $custom_redirect_url )` + exit.

## Configuration & Extension Points

### reCAPTCHA / Turnstile

Three options, configured per-widget:

| Mode | Frontend | Backend validation |
| ---- | -------- | ------------------ |
| **reCAPTCHA v2** | Checkbox / invisible challenge widget | `lr_validate_recaptcha('v2')` — POST to `https://www.recaptcha.net/recaptcha/api/siteverify` |
| **reCAPTCHA v3** | Invisible score-based — token attached on submit | `lr_validate_recaptcha('v3')` — same endpoint, additionally compares score vs `get_recaptcha_threshold($settings)` |
| **Cloudflare Turnstile** | Cloudflare's widget | `lr_validate_cloudflare_turnstile()` — POST to Cloudflare's API |

The frontend `<script>` for whichever mode is enabled is registered in [`Enqueue::before_enqueue_styles`](../../../includes/Traits/Enqueue.php#L41) when the page contains the Login | Register widget. The widget settings expose site key + secret — site key emitted to the markup; secret used server-side.

### Custom user profile fields

Widgets can declare extra registration fields via the form designer. Each field gets:

- A control id like `eael_custom_profile_field_phone`
- An `eael_custom_profile_field_*` user meta key after register
- A render row on the WP admin profile page via [`eael_extra_user_profile_fields`](../../../includes/Traits/Login_Registration.php#L1708) (hooked to `show_user_profile` / `edit_user_profile`)
- A save handler via [`eael_save_extra_user_profile_fields`](../../../includes/Traits/Login_Registration.php#L1770) (hooked to `personal_options_update` / `edit_user_profile_update`)

Phone validation goes through [`eael_is_phone`](../../../includes/Traits/Login_Registration.php#L1795) — accepts E.164-style numbers with optional country code.

### Email customisation

Three customisable emails per widget:

| Email | Static flag | Options array |
| ----- | ----------- | ------------- |
| New user (welcome) | `$send_custom_email` | `$email_options` |
| Admin notification | `$send_custom_email_admin` | `$email_options` (admin sub-key) |
| Lost password | `$send_custom_email_lostpassword` | `$email_options_lostpassword` |

Each supports custom subject + body + content type. Bodies use placeholder syntax — `[user_login]`, `[user_email]`, `[firstname]`, `[lastname]`, custom field values, plus `[reset_url]` for lostpassword. Replacement happens in [`replace_placeholders`](../../../includes/Traits/Login_Registration.php#L1485) variants.

### Roles

Widget setting controls role assignment. Single role or multiple roles (multiple = comma-separated). Filter `eael/login-register/new-user-roles` lets extensions intercept the resolved role list before `wp_insert_user`.

### Redirect URLs

- Login success → setting `redirect_url` (or HTTP_REFERER fallback)
- Register success → `eael/login-register/register-redirect-url` filter, or setting, or HTTP_REFERER fallback
- Lost password / reset password → setting-driven page

## Common Pitfalls

### Filter name typo: `validatiob` not `validation`

[Line 206](../../../includes/Traits/Login_Registration.php#L206): `eael/login-register/login-validatiob-error-message`. The filter is **misspelled**. Existing third-party hooks rely on this exact spelling — **don't fix the typo without a coordinated migration** (dual-emit + deprecation cycle, like `fancy_text_style_types`).

### Cookie-based error display has no built-in expiry hygiene

`setcookie( 'eael_login_error_<widget_id>', $err_msg )` without a max-age leaves the cookie in the browser until session end. The widget render() reads and clears it, but if the user closes the tab without re-loading, the cookie persists until session-cookie expiry.

### Static state across multiple widgets on one page

`$send_custom_email`, `$email_options` are static on the trait. If a page has two Login | Register widgets with different email templates, the second widget's render overwrites the first's static state. The form handler fires for whichever widget submitted — usually fine, but note that the static state is whatever was last populated.

### `wp_safe_redirect( HTTP_REFERER )` fails silently when referer is missing

The fallback path on validation errors redirects to `$_SERVER['HTTP_REFERER']`. If the form was submitted from a context without a referer (direct curl, some bookmark managers), the redirect is to an empty string — `wp_safe_redirect` no-ops. The user sees a blank page or whatever PHP outputs after the failed redirect. Form handlers should fall back to `home_url()` when `HTTP_REFERER` is empty.

### reCAPTCHA threshold range (0.0 – 1.0) — outside-range silently coerces to 0.5

[Line 42](../../../includes/Traits/Login_Registration.php#L42): if the user enters a threshold outside `[0, 1]`, it's silently set to 0.5. There's no admin warning. Users who configure 1.5 or -0.1 expect different behaviour.

### Custom fields meta key collisions

Custom field ids become user meta keys with the `eael_custom_profile_field_` prefix. If two custom fields have the same id (e.g. user duplicates a field), the second overwrites the first — both at register time and on the admin profile screen. The widget UI does not enforce id uniqueness.

### Username auto-generation can collide

[`generate_username_from_email`](../../../includes/Traits/Login_Registration.php#L1277) takes the local part of the email and appends a numeric suffix if the name exists. With high registration volume on common email prefixes (`info@`, `admin@`), suffix exhaustion can produce visually surprising usernames like `info-7`.

### `wp_signon` does not fire `wp_login` hooks reliably across hosts

EA's flow re-emits `wp_login` and `register_new_user` actions with EA's context — this is intentional. Some host-side plugins (security plugins) hook only WP core's `wp_login`. EA's re-emission ensures those hooks fire consistently regardless of whether `wp_signon` triggered them on this particular host.

### AJAX vs non-AJAX divergence in error display

The same handler does either `wp_send_json_error` or cookie+redirect depending on `wp_doing_ajax()`. Test both paths — a fix in one branch may not exist in the other. The non-AJAX path is the older, cookie-based; AJAX is the newer JSON path.

## Debugging Guide

When a login or register flow misbehaves:

1. **Confirm which sub-handler ran.** Add `error_log( 'login_or_register_user dispatch: ' . print_r( $_POST, true ) )` at the top of the dispatch entry — confirms the form posted with the expected `eael-*-submit` flag.
2. **Confirm nonce.** Form must include `eael-login-nonce` (or analogous) with action `'essential-addons-elementor'`. Inspect form HTML for the hidden field.
3. **Check the cookie path.** Non-AJAX errors land as `eael_<action>_error_<widget_id>` cookies. Use browser devtools → Application → Cookies. If the cookie isn't there but you see a redirect, the handler likely failed before setting the cookie.
4. **For reCAPTCHA failures.** Compare site key in widget settings vs site-wide settings. If using v3, log the score Google returned and compare against the threshold. A common cause is page-level caching (full-page CDN) serving stale tokens.
5. **For custom fields not saving.** Confirm field id has `eael_custom_profile_field_` prefix. Confirm the value isn't filtered out by `wp_kses` (rich-text fields lose tags). Check user meta directly via `get_user_meta($user_id, 'eael_custom_profile_field_<key>', true)`.
6. **For email not arriving.** Check `wp_mail` is actually sending (most hosts have an SMTP plugin or log). Set `$send_custom_email = false` temporarily to use WP core's default email and isolate whether the issue is EA's custom path.
7. **For redirect URL ignored.** The redirect filter chain is `setting → eael/login-register/register-redirect-url filter → HTTP_REFERER fallback`. If the setting isn't being applied, a third-party plugin may be hooking the filter and overriding.
8. **For "Page ID is missing" / "Widget ID is missing".** The form must include `<input name="page_id">` and `<input name="widget_id">` hidden fields. Caching plugins that strip "unknown" hidden fields are the usual culprit.

## Worked Example — Register with custom phone field + auto-login + custom email

A widget configured with: a `phone` custom field, `subscriber` role, auto-login on, custom welcome email, reCAPTCHA v3.

1. **User fills form, submits.** POST body: `eael-register-submit=1`, `email=user@example.com`, `password=…`, `confirm_password=…`, `eael_custom_profile_field_phone=+880-1234-567890`, `g-recaptcha-response=<token>`, `eael-register-nonce=<nonce>`, `page_id=42`, `widget_id=abc1`.
2. **Dispatch.** `login_or_register_user()` sees `eael-register-submit` → calls `register_user()`.
3. **Security gate.** Nonce verified against `'essential-addons-elementor'`.
4. **Settings retrieved.** `lr_get_widget_settings(42, 'abc1')` returns the widget's saved config including custom field list, role, redirect URL, custom email templates.
5. **reCAPTCHA validated.** POST to Google with `secret + response`. Score is 0.7. Threshold is 0.5 (default, filterable). Pass.
6. **Phone validated.** `eael_is_phone('+880-1234-567890')` → true.
7. **Email format validated.** Standard `is_email()`. Password meets minimum length, matches confirm.
8. **Username generated.** Local part is `user`. `username_exists('user')` → false. Use `'user'`.
9. **`$user_data` built:** `[ user_login => 'user', user_email => 'user@example.com', user_pass => '<plain>', role => 'subscriber' ]`.
10. **Filter chain.** `eael/login-register/new-user-data` may mutate (no-op here). `eael/login-register/new-user-roles` returns `['subscriber']`.
11. **`wp_insert_user($user_data)`** returns user id 51.
12. **Custom field saved.** `update_user_meta(51, 'eael_custom_profile_field_phone', '+880-1234-567890')`.
13. **Hooks fire.** `do_action('eael/login-register/after-insert-user', 51, $user_data, $settings)` and the mailchimp integration action.
14. **Email built.** `replace_placeholders` walks the body template, replaces `[user_login]` → `user`, `[user_email]` → `user@example.com`, `[eael_custom_profile_field_phone]` → `+880-…`. `wp_mail($email, $subject, $message, $headers)` ships it.
15. **Admin notification.** Same flow, separate template, separate filter chain.
16. **`do_action('register_new_user', 51)`** — WP core hook for plugin compat.
17. **Auto-login.** `wp_signon([ user_login => 'user', user_password => '<plain>' ])` returns user object. Session cookie set.
18. **Redirect.** `wp_safe_redirect( $settings['eael_register_redirect_url'] ?: home_url() )`. Browser navigates. User sees the configured success page, logged in.

## Architecture Decisions

### Form-action route, not AJAX action route

- **Context:** The Login | Register widget needs to support both AJAX and non-AJAX submission. WP `wp_ajax_*` only handles the AJAX case. Form submission to the current page handles both.
- **Decision:** Use `$_POST` flags (`eael-login-submit`, `eael-register-submit`) on a form posted to the current page. Detect AJAX via `wp_doing_ajax()` and respond accordingly.
- **Alternatives rejected:** Pure AJAX (degrades to broken form when JS is disabled or fails); separate AJAX + form handlers (duplicate logic).
- **Consequences:** One handler covers both modes. Cookie-based error display is needed for the non-AJAX path because the response is a redirect, not JSON. Adds the cookie hygiene concerns documented in Pitfalls.

### Widget settings re-fetched on submit (not trusted from form)

- **Context:** Email templates, role, redirect URL, custom fields are widget-level configuration. Trusting the form to send them would let an attacker submit `role=administrator`.
- **Decision:** Form sends `widget_id` + `page_id`. Handler re-reads saved settings server-side via `lr_get_widget_settings`. Ignores any role / template / URL fields the form might send.
- **Alternatives rejected:** Sign the form fields cryptographically (complexity, key rotation); accept form values then validate (whack-a-mole; still risky).
- **Consequences:** Same widget-id collision concern as load-more (cloned widgets share id). Trade-off is solid security against role / configuration tampering.

### Static state for email options

- **Context:** Email templates depend on widget settings. They need to be available inside `wp_mail` callbacks — which run in the global namespace, far from the widget instance.
- **Decision:** Populate static class properties (`$email_options`, `$send_custom_email`) at widget render time. Email-customisation methods read static state. The form handler's downstream `wp_mail` calls inherit whatever the last render set.
- **Alternatives rejected:** Pass options through every layer (verbose); per-user-id transients (database churn for what's effectively request-scoped data).
- **Consequences:** Multiple Login | Register widgets on a single page share a single static state — the last-rendered widget's options apply. Acceptable because users rarely have multiple Login | Register widgets on one page.

### Misspelled filter `validatiob` kept verbatim

- **Context:** A typo shipped to production: `eael/login-register/login-validatiob-error-message`. Third-party plugins hooked it. Renaming would silently break their integrations.
- **Decision:** Keep the typo as the canonical filter name. Document it. Plan a future dual-emit migration (emit both correct and typo'd names; deprecate typo over a release cycle).
- **Alternatives rejected:** Rename now (third-party breakage); ignore (compounds the embarrassment with future typos).
- **Consequences:** Doc must call this out in every audit. The pattern matches `fancy_text_style_types` un-prefixed legacy — same migration playbook applies when ready.

### Cookie-based error display

- **Context:** Non-AJAX flow redirects to HTTP_REFERER, losing all PHP-side state. Need to display the error on the page that hosted the form.
- **Decision:** Set a per-widget cookie (`eael_<action>_error_<widget_id>`) before redirect. Widget render reads and clears the cookie inline.
- **Alternatives rejected:** Transients keyed by IP (privacy), session storage (host-dependent), URL params (information disclosure).
- **Consequences:** Works without sessions or transients. Stale-cookie hygiene is the trade-off — documented in Pitfalls. Some users with cookie-restrictive browser settings may not see errors at all.

## Known Limitations

- **Cloned-widget id collision** — like load-more, multiple instances on one page share the widget id; settings retrieval returns the first match.
- **Static state cross-contamination** — multiple Login | Register widgets share one set of static email options.
- **Misspelled filter `validatiob`** — kept for back-compat; awkward but durable.
- **HTTP_REFERER fallback failures** — direct submissions without referer produce empty redirect targets.
- **reCAPTCHA threshold silent coercion** — out-of-range values become 0.5 with no warning.
- **Cookie hygiene** — error cookies linger until session end if the user closes the tab pre-render.
- **Custom field id uniqueness not enforced** — duplicate ids overwrite each other silently.
- **Username auto-generation suffix exhaustion** — high-volume registers from common email prefixes produce odd usernames.
- **No rate limiting** — login + register endpoints are unauthenticated and accept arbitrary submission volume. Mitigation lives at the WAF / reCAPTCHA layer; the trait itself doesn't throttle.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — folder index; this is "Flow 2" in the system diagram.
- **Architecture:** [`./ajax-endpoints.md`](ajax-endpoints.md) — explains why this flow is *not* in the `wp_ajax_*` inventory and what the form-action approach implies.
- **Architecture:** [`../asset-loading.md`](../asset-loading.md) — `Enqueue` trait's `before_enqueue_styles` registers the reCAPTCHA / Turnstile script when this widget is on the page.
- **Architecture:** [`../editor-data-flow.md`](../editor-data-flow.md) — the widget's settings shape that this trait reads.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — debugging a Login | Register issue follows the AJAX trace path despite the form-action mechanism.
- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — same security principles apply: caller-supplied configuration must not be trusted.
- **Rules:** [`php-standards.md`](../../../.claude/rules/php-standards.md) — security and i18n conventions every handler in this trait honours.
