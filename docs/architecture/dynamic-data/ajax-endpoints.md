# AJAX Endpoints

Complete inventory of every `wp_ajax_*` action the plugin registers, the handler that runs, the nonce it expects, and whether it accepts unauthenticated requests. The nonce / capability / sanitization triad is the security contract every handler must honour.

If you're triaging "why is my AJAX request returning 403?" or "what nonce do I send for endpoint X?" — start here.

## Overview

The plugin registers AJAX endpoints in two places:

- **`Ajax_Handler` trait** ([`includes/Traits/Ajax_Handler.php`](../../../includes/Traits/Ajax_Handler.php), 1,685 lines) — the bulk of frontend AJAX endpoints. Hooked from `Bootstrap::init_ajax_hooks()`.
- **`Bootstrap` class** ([`includes/Classes/Bootstrap.php`](../../../includes/Classes/Bootstrap.php)) — a handful of widget-cross-cutting actions registered directly during bootstrap (Facebook feed, compare table, cache management, checkout cart updates).

Two nonce conventions cover almost every handler:

1. **Plugin-wide nonce** — created with action `'essential-addons-elementor'`, sent as `security` parameter, verified via `check_ajax_referer( 'essential-addons-elementor', 'security' )`. Used by most newer endpoints.
2. **Endpoint-scoped nonces** — created and verified per-action (`'load_more'`, `'eael_product_gallery'`, `'eael_select2'`). Used by older endpoints; some accept *both* the scoped and the plugin-wide nonce as fallback.

Every endpoint that accepts unauthenticated requests (`wp_ajax_nopriv_*`) must treat the nonce as CSRF protection only — not as authorization. See the [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) skill for the full hardening playbook.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Traits/Ajax_Handler.php`](../../../includes/Traits/Ajax_Handler.php) | 1,685 | All `init_ajax_hooks()` registrations + handler methods for load-more, WooCommerce widgets, quickview, gallery, select2, settings save, cache clear, token |
| [`includes/Classes/Bootstrap.php`](../../../includes/Classes/Bootstrap.php) | 384 | Direct registrations for Facebook feed, product compare table, widget cache clear, templately promo, checkout cart qty |
| [`includes/Classes/Helper.php`](../../../includes/Classes/Helper.php) | 2,067 | `eael_sanitize_relation()`, `eael_wp_kses()`, sanitization helpers used inside handlers |
| `Ajax_Handler` does NOT cover | — | Login/Register submissions (form-action route, see [`login-register.md`](login-register.md)); admin plugin-installer / setup-wizard / usage-tracker AJAX (admin-only, separate concerns) |

## Endpoint Inventory

Every frontend-relevant endpoint, in order of registration. Admin-only utility endpoints (plugin installer, setup wizard, usage tracker) are listed separately at the bottom.

### Frontend-relevant endpoints

| Action | Handler | Nonce action | Accepts | Source |
| ------ | ------- | ------------ | ------- | ------ |
| `load_more` | `ajax_load_more` | `load_more` OR `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:34`](../../../includes/Traits/Ajax_Handler.php#L34) |
| `woo_product_pagination_product` | `eael_woo_pagination_product_ajax` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:37`](../../../includes/Traits/Ajax_Handler.php#L37) |
| `woo_product_pagination` | `eael_woo_pagination_ajax` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:40`](../../../includes/Traits/Ajax_Handler.php#L40) |
| `eael_product_add_to_cart` | `eael_product_add_to_cart` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:43`](../../../includes/Traits/Ajax_Handler.php#L43) |
| `eael_ajax_add_to_cart` | `eael_ajax_add_to_cart` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:46`](../../../includes/Traits/Ajax_Handler.php#L46) |
| `woo_checkout_update_order_review` | `woo_checkout_update_order_review` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:49`](../../../includes/Traits/Ajax_Handler.php#L49) |
| `eael_product_quickview_popup` | `eael_product_quickview_popup` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:52`](../../../includes/Traits/Ajax_Handler.php#L52) |
| `eael_product_gallery` | `ajax_eael_product_gallery` | `eael_product_gallery` | priv + nopriv | [`Ajax_Handler:55`](../../../includes/Traits/Ajax_Handler.php#L55) |
| `eael_select2_search_post` | `select2_ajax_posts_filter_autocomplete` | `eael_select2` | priv only | [`Ajax_Handler:58`](../../../includes/Traits/Ajax_Handler.php#L58) |
| `eael_select2_get_title` | `select2_ajax_get_posts_value_titles` | `eael_select2` | priv only | [`Ajax_Handler:59`](../../../includes/Traits/Ajax_Handler.php#L59) |
| `eael_save_settings_with_ajax` | `save_settings` | `essential-addons-elementor` + `manage_options` cap | admin priv only | [`Ajax_Handler:62`](../../../includes/Traits/Ajax_Handler.php#L62) |
| `eael_clear_cache_files_with_ajax` | `clear_cache_files` | `essential-addons-elementor` | admin priv only | [`Ajax_Handler:63`](../../../includes/Traits/Ajax_Handler.php#L63) |
| `eael_admin_promotion` | `eael_admin_promotion` | `essential-addons-elementor` | admin priv only | [`Ajax_Handler:64`](../../../includes/Traits/Ajax_Handler.php#L64) |
| `eael_get_token` | `eael_get_token` | `essential-addons-elementor` | priv + nopriv | [`Ajax_Handler:67`](../../../includes/Traits/Ajax_Handler.php#L67) |
| `facebook_feed_load_more` | `facebook_feed_render_items` | endpoint-specific | priv + nopriv | [`Bootstrap:158`](../../../includes/Classes/Bootstrap.php#L158) |
| `eael_product_grid` | `get_compare_table` | endpoint-specific | priv + nopriv | [`Bootstrap:162`](../../../includes/Classes/Bootstrap.php#L162) |
| `eael_clear_widget_cache_data` | `eael_clear_widget_cache_data` | `essential-addons-elementor` + cap | admin priv only | [`Bootstrap:165`](../../../includes/Classes/Bootstrap.php#L165) |
| `eael_checkout_cart_qty_update` | `eael_checkout_cart_qty_update` | `essential-addons-elementor` | priv + nopriv | [`Bootstrap:237`](../../../includes/Classes/Bootstrap.php#L237) |

### Admin-only utility endpoints

These exist for plugin lifecycle management; widgets do not call them. Listed for completeness:

| Action | Source | Purpose |
| ------ | ------ | ------- |
| `templately_promo_status` | [`Bootstrap:222`](../../../includes/Classes/Bootstrap.php#L222) | Templately upsell visibility tracking |
| `wpdeveloper_upsale_core_install_*` | [`WPDeveloper_Core_Installer.php`](../../../includes/Classes/WPDeveloper_Core_Installer.php) | Core plugin installer |
| `wpdeveloper_install_plugin` / `upgrade_plugin` / `activate_plugin` / `deactivate_plugin` | [`WPDeveloper_Plugin_Installer.php`](../../../includes/Classes/WPDeveloper_Plugin_Installer.php) | Plugin install / activate flow |
| `wpdeveloper_upsale_notice_dissmiss_for_*` | [`WPDeveloper_Notice.php`](../../../includes/Classes/WPDeveloper_Notice.php) | Admin notice dismissal |
| `deactivation_form_*` | [`Plugin_Usage_Tracker.php`](../../../includes/Classes/Plugin_Usage_Tracker.php) | Deactivation reason form |
| `save_setup_wizard_data` / `enable_wpins_process` / `save_eael_elements_data` | [`WPDeveloper_Setup_Wizard.php`](../../../includes/Classes/WPDeveloper_Setup_Wizard.php) | Setup wizard persistence |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ REQUEST                                                          ║
║                                                                  ║
║   Frontend JS (e.g. eael.js, post-grid.js, woo-cart.js)          ║
║       │                                                          ║
║       ▼  jQuery $.ajax / fetch                                   ║
║   POST wp-admin/admin-ajax.php                                   ║
║     body:                                                        ║
║       action: eael_<endpoint-name>                               ║
║       security OR nonce: <token>                                 ║
║       args: <serialized query string OR JSON>                    ║
║       page: <int> (load-more endpoints)                          ║
║       …other endpoint-specific fields                            ║
╚══════════════════════════════════════════════════════════════════╝
                              │
                              ▼
╔══════════════════════════════════════════════════════════════════╗
║ DISPATCH (WordPress core)                                        ║
║                                                                  ║
║   admin-ajax.php → do_action( "wp_ajax_<action>" )               ║
║       (or wp_ajax_nopriv_<action> if user not logged in)         ║
║       │                                                          ║
║       ▼                                                          ║
║   Registered handler runs (Ajax_Handler trait method or          ║
║   Bootstrap method)                                              ║
╚══════════════════════════════════════════════════════════════════╝
                              │
                              ▼
╔══════════════════════════════════════════════════════════════════╗
║ HANDLER (the Security Triad)                                     ║
║                                                                  ║
║   1. NONCE                                                       ║
║      check_ajax_referer( 'essential-addons-elementor',           ║
║                          'security' )                            ║
║      OR wp_verify_nonce( $_POST['nonce'],                        ║
║                          '<endpoint-action>' )                   ║
║      → on failure: wp_send_json_error / die                      ║
║                                                                  ║
║   2. CAPABILITY (when privileged)                                ║
║      current_user_can( '<cap>' )                                 ║
║      → on failure: wp_send_json_error                            ║
║                                                                  ║
║   3. SANITIZE                                                    ║
║      wp_parse_str / sanitize_text_field / absint / wp_kses_post  ║
║      Strip caller-supplied dangerous query args (post_status,    ║
║      post_type='any', author, perm) for nopriv handlers          ║
║      → see nopriv-ajax-hardening skill                           ║
║                                                                  ║
║   4. WORK                                                        ║
║      WP_Query / WC API / Helper / Template_Query                 ║
║                                                                  ║
║   5. RESPOND                                                     ║
║      wp_send_json_success( $data )                               ║
║      OR wp_send_json_error( $message )                           ║
║      OR wp_send_json( $array )  (used in select2 endpoints)      ║
╚══════════════════════════════════════════════════════════════════╝
                              │
                              ▼
                     JSON to frontend JS
```

## Hook Timing

AJAX endpoints don't have a "phase" the way page rendering does — they're dispatched directly by WordPress core when the request arrives. The relevant hooks fire in handler order, not before/after dispatch:

| Hook | Owner | When | Use |
| ---- | ----- | ---- | --- |
| `wp_ajax_<action>` (action) | WP core | Each AJAX request, logged-in users | Handler dispatch |
| `wp_ajax_nopriv_<action>` (action) | WP core | Each AJAX request, anonymous users | Handler dispatch |
| `eael_before_ajax_load_more` (action) | EA | Top of `ajax_load_more`, before sanitisation | Compatibility shims (e.g. YITH wishlist) listen here |
| `eael_before_woo_pagination_product_ajax_start` (action) | EA | Top of woo product pagination | Same — third-party compat |

## Data Flow

End-to-end trip for a typical EA AJAX request:

1. **Frontend JS prepares the request.** Reads the rendered widget's `data-*` attrs (typically a JSON-serialised settings blob), composes a POST body with `action`, `security` (or `nonce`), `args`, and any endpoint-specific fields like `page`.
2. **`wp-admin/admin-ajax.php`** receives the request. WordPress core dispatches `do_action( 'wp_ajax_eael_<endpoint>' )` (or the nopriv variant).
3. **Handler runs.** The first thing every handler must do is the security triad — nonce, capability check (if privileged), input sanitization. Failures call `wp_send_json_error()` and exit.
4. **`$_POST['args']` is parsed.** Most load-more / WooCommerce endpoints serialize a query-args blob and send it as a single `args` string. Handler calls `wp_parse_str( $_POST['args'], $args )` to deserialize. Handler then strips dangerous query keys (`post_status`, `post_type=any`, `author`, `perm`) and re-applies safe values — see [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) for the canonical pattern.
5. **Widget settings retrieval.** For load-more handlers, the widget id is in `$_POST` and the handler fetches saved settings via `Plugin::$instance->documents->get(...)->get_elements_data()` filtered by element id. This is how the handler knows what query the originating widget was configured for.
6. **WP_Query / WC API call.** `new WP_Query( $args )` for posts; `wc_get_products`, cart fragment fetching, or other WC functions for commerce. Helper / Template_Query traits provide shared building blocks — see [`wp-query-construction.md`](wp-query-construction.md).
7. **Response render.** Most handlers loop the result and `ob_start` / `ob_get_clean` to capture markup, then `wp_send_json_success([ 'html' => $markup, 'numberPosts' => $count, ... ])`. Some return raw fragments (e.g. cart updates).
8. **Frontend JS handles the response.** Inserts the HTML into the DOM, updates page counters, may re-trigger Elementor widget re-init for nested widgets in the response.

## Configuration & Extension Points

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/load_more_args` | Inside `ajax_load_more` (after sanitisation) | Last chance to mutate `$args` before `WP_Query` runs |
| `eael_pagination_link` | WC pagination handlers | Customise pagination link output |
| `eael_lr_recaptcha_api_args` | Recaptcha-using handlers | Modify reCAPTCHA API args |

### Actions

| Action | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael_before_ajax_load_more` | Top of `ajax_load_more` | Third-party compatibility shims |
| `eael_before_woo_pagination_product_ajax_start` | Top of WC product pagination | Third-party compat (e.g. YITH) |
| `eael_after_ajax_load_more` | After `ajax_load_more` work | Trigger external systems on completion |

### Adding a new AJAX endpoint — checklist

When adding a new endpoint, follow this order:

1. **Decide auth model.** Logged-in only (priv only) or also accepts anonymous (priv + nopriv)? Anonymous endpoints **must** strip caller-supplied visibility overrides — see [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md).
2. **Pick a nonce convention.** New endpoints should use the plugin-wide `essential-addons-elementor` nonce verified via `check_ajax_referer( 'essential-addons-elementor', 'security' )`. Endpoint-scoped nonces are legacy.
3. **Register in `Ajax_Handler::init_ajax_hooks()`** for the `priv`/`nopriv` actions.
4. **Implement the handler** with the security triad as the first lines: `check_ajax_referer`, capability check (if priv-only), input sanitisation. Then the work, then `wp_send_json_*`.
5. **Document the endpoint** in this inventory (add a row to the table above).
6. **Add a Playwright spec** if the endpoint backs a widget — see [`testing.md`](../../../.claude/rules/testing.md).

## Common Pitfalls

### Wrong nonce action passed by the frontend

The two-convention world (plugin-wide vs endpoint-scoped) means JS calls easily send the wrong token. Symptoms: 400/403 with `"Security token did not match"` body. Diagnose by inspecting the request body — is the `security` / `nonce` field set to the right value? Cross-check with the handler's `check_ajax_referer` / `wp_verify_nonce` action argument.

### Stripping visibility overrides — the nopriv visibility leak

Handlers that `wp_parse_str( $_POST['args'], $args )` and pass `$args` straight into `WP_Query` will accept caller-supplied `post_status='private'`, `post_type='any'`, `author=<id>`, `perm='editable'`. An anonymous attacker exfiltrates drafts / private posts with a single request. Every nopriv handler must strip-and-redefault these keys at the top, before WP_Query — see the [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) skill's drop-in fix block.

### `$_POST['args']` slashing

WordPress automatically adds slashes to `$_POST` data. `wp_parse_str` does not strip them. If you use the parsed array directly without `wp_unslash`, you get backslash-prefixed values. Handlers that build SQL/HTML strings from `$args` are at risk. Apply `wp_unslash` early.

### Widget settings retrieval failing for cloned widgets

Several load-more handlers fetch widget settings by walking `Plugin::$instance->documents->get(...)->get_elements_data()` and filtering by element id. If a widget is duplicated within the page, the id may collide unpredictably. Most reports of "load more works for one widget on the page but not others" trace to this.

### `wp_send_json_*` exits — code after returns nothing

`wp_send_json_success` / `wp_send_json_error` call `wp_die()` internally. Any code after a `wp_send_json_*` call is dead. If you need cleanup, use try/finally before the json call — though most handlers don't need cleanup.

### Mixed priv/nopriv handlers must not assume `$_POST['security']` exists

For nopriv calls without authentication context, the JS must still send the nonce, but if a handler is registered for both priv and nopriv and only the priv path provides a nonce, the nopriv flow fails. Always send the nonce, always verify on entry.

### Browser cache + admin-ajax.php

Admin-ajax responses are not cached by default, but some CDN configurations apply aggressive caching. If you see one user's add-to-cart response served to another user, suspect CDN caching of admin-ajax. Set `Cache-Control: no-store` on responses if needed.

### `eael_get_token` is a recursion trap

`eael_get_token` returns a freshly minted nonce. Some endpoints accept a nonce obtained from this endpoint. Treating that nonce as authentication is wrong — anonymous visitors can fetch fresh nonces, so the nonce only proves "this came from a browser, not a script". Combine with capability checks and parameter validation for any privileged work.

## Debugging Guide

When an AJAX endpoint is suspected (Step 3e in the [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) skill's localize tree):

1. **Inspect the request in the browser Network tab.** Confirm `action`, `security`/`nonce`, and any other expected params are present in the request body. Status code reveals which gate failed:
   - `0` (no response) — admin-ajax.php returned `0` because no handler matched the action. Check `init_ajax_hooks` registration — did you spell the action name right?
   - `400` / `403` — usually nonce or capability failure; check the response body for `"Security token did not match"` or similar.
   - `500` — handler threw a PHP error; check `wp-content/debug.log`.
2. **Add `error_log()` at the top of the handler.** Confirm the handler is even being invoked. If not, the registration or action name is wrong.
3. **Confirm the nonce action.** The `security` / `nonce` POST param must match the action argument used in `check_ajax_referer` / `wp_verify_nonce`. Check JS source for what nonce it's sending — typically a `localize` value emitted by `Asset_Builder::load_commnon_asset` ([`Asset_Builder.php:391`](../../../includes/Classes/Asset_Builder.php#L391)).
4. **For 4xx with valid-looking nonce.** Check `current_user_can` calls in the handler — is the requesting user logged in for a priv-only endpoint?
5. **For 200 with empty/wrong response.** Inspect the response body. Most handlers return `{ success: bool, data: {...} }`. If `success: false`, the `data` field has the error message. If `success: true` but data is empty, the work query returned zero rows.
6. **For widget-settings-not-found.** Several load-more handlers respond with `"Widget settings are not found. Did you save the widget before using load more??"` — the saved Elementor settings for the widget id couldn't be retrieved. Resave the page in Elementor.

## Worked Example — `ajax_load_more`

Trace the full lifecycle of a Post Grid load-more click:

1. **User clicks "Load More" on a Post Grid widget.** Frontend `load-more.min.js` (declared in `config.php` for `post-grid`) reads the widget's `data-eael-load-more-args` attribute (a query-string-serialised version of the saved query args) and the widget's element id.
2. **JS POSTs to `wp-admin/admin-ajax.php`** with body:
   ```text
   action=load_more
   nonce=<load_more nonce OR essential-addons-elementor nonce>
   args=<wp_parse_str-able query string>
   page=2
   class=eael-post-grid
   widget_id=abc1
   ```
3. **WordPress dispatches** `do_action( 'wp_ajax_load_more' )` (or nopriv variant). The handler registered at [`Ajax_Handler:34`](../../../includes/Traits/Ajax_Handler.php#L34) runs — `ajax_load_more`.
4. **`ajax_load_more` does the security triad.** Checks `$_POST['nonce']` is present, then `wp_verify_nonce` against either `'load_more'` or `'essential-addons-elementor'` (dual accept for backward compat) ([`Ajax_Handler:106`](../../../includes/Traits/Ajax_Handler.php#L106)). Failure → `wp_send_json_error`.
5. **Sanitises `$args`.** Calls `wp_parse_str( $_POST['args'], $args )`, then forces `$args['post_status'] = 'publish'` and runs `eael_sanitize_relation` on `date_query['relation']`.
6. **Retrieves widget settings.** Walks `Plugin::$instance->documents->get(...)` for the widget's host page, filters by `widget_id`, gets the saved settings dict. Failure (settings not found) → `wp_send_json_error( [ 'message' => 'Widget settings are not found...' ] )`.
7. **Builds the loop.** Adds `paged = $page` (from `$_POST['page']`) to `$args`. Runs `new WP_Query( $args )`.
8. **Renders post markup.** `ob_start`, loops the WP_Query, includes the per-post template (Helper or Template_Query trait method), `ob_get_clean` to capture the markup.
9. **Responds.** `wp_send_json_success( [ 'html' => $markup, 'numberPosts' => $found_posts, 'class' => $class, 'args' => $args ] )`.
10. **Frontend JS appends `data.html`** to the existing post grid DOM. Updates page counter. May trigger Isotope re-layout if the grid uses masonry.

The complete handler ([`Ajax_Handler.php:84`](../../../includes/Traits/Ajax_Handler.php#L84) onward) is one of the longest in the trait — it doubles as the load-more for Post Grid, Product Grid, Post Timeline, and other shared post-list widgets.

## Architecture Decisions

### Two nonce conventions during a long migration

- **Context:** Original endpoints used per-action nonces (`'load_more'`, `'eael_select2'`). Newer endpoints adopted a plugin-wide nonce (`'essential-addons-elementor'`, sent as `security`). Migrating all endpoints at once would have broken third-party JS that hardcoded the old nonce action.
- **Decision:** Keep the legacy per-action nonces working, accept the plugin-wide nonce as fallback in the same handlers, default new endpoints to the plugin-wide convention.
- **Alternatives rejected:** Hard migration (third-party breakage); keep per-action forever (proliferation of nonce action names; harder to mass-rotate).
- **Consequences:** `ajax_load_more` (and a few others) accept either nonce — visible in [line 106](../../../includes/Traits/Ajax_Handler.php#L106). Any new endpoint should use only the plugin-wide nonce.

### `nopriv` handlers exist for guest-cart and guest-browse use cases

- **Context:** WooCommerce and post-list widgets must work for anonymous visitors (browsing products, paginating posts, adding to cart without creating an account). `wp_ajax_*` alone wouldn't fire for those visitors.
- **Decision:** Register both `wp_ajax_<action>` and `wp_ajax_nopriv_<action>` for endpoints that need to serve guests.
- **Alternatives rejected:** Force login (kills conversion); separate nopriv routes (duplicate logic).
- **Consequences:** Every shared handler must work for both authenticated and anonymous flows. The handler can't assume a session, can't trust client-supplied user ids, can't read user meta without explicit current-user check. The visibility-leak risk this creates is the entire reason [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) exists.

### `wp_parse_str( $_POST['args'], $args )` for query payloads

- **Context:** Load-more requests need to send the originating widget's WP_Query args. Sending each arg as a separate POST field would mean dozens of fields and brittle JS.
- **Decision:** Frontend JS query-string-serialises the args, sends as a single `args` POST field, handler parses on entry.
- **Alternatives rejected:** JSON-encoded `args` (requires JS+PHP json round-trip; simple cases like nested arrays are fine in query strings); per-arg POST fields (verbose, brittle).
- **Consequences:** Handler must always strip dangerous keys after parse — caller-supplied `post_status`, `post_type='any'`, etc. The strip-and-redefault block is a hard requirement on every handler that uses this pattern. Without it, you get the visibility-leak bug class.

### Widget settings retrieved from `_elementor_data` per request

- **Context:** Load-more needs the widget's saved query args (post type, taxonomy filters, posts-per-page, etc.). Trusting these from the client would let attackers query arbitrary post types.
- **Decision:** Re-derive the args server-side by reading the widget's saved settings from `_elementor_data` and re-running the same query construction the original `render()` used.
- **Alternatives rejected:** Trust client args (security hole); cache args separately keyed by widget id (sync nightmare with editor saves).
- **Consequences:** Each load-more request reads `_elementor_data` post meta — small DB hit per request. Widget id must be passed and validated. If the page is re-saved between the initial render and a load-more click, the user can see "different" results (but always within their authorised scope). Widgets with template-injected content may need special handling.

## Known Limitations

- **No central nonce rotation.** Rotating `'essential-addons-elementor'` requires bumping the plugin version (which busts the localized nonce). Per-action nonces would rotate per-page-save but only some endpoints use them.
- **`wp_send_json_error` swallows error context.** The default error response is `{ success: false, data: <message> }` — fine for end users but loses structured info that could help debugging. Some handlers wrap data in `[ 'message' => $msg ]`; not all.
- **Widget settings lookup is tree-walking.** `Plugin::$instance->documents->get(...)->get_elements_data()` returns the full tree; the handler then walks to find the matching widget id. With heavy pages this is non-trivial work per request. Caching candidates exist but haven't been implemented.
- **Compat shims fire for every load-more request.** `eael_before_ajax_load_more` runs even when no third-party shim cares. Hot path; minor cost; would be nicer with conditional.
- **No request-level rate limiting.** Anonymous load-more / WooCommerce endpoints can be hammered. Mitigation lives at the WAF / hosting layer; the plugin doesn't ship its own rate limiter.

## Cross-References

- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — the canonical security audit workflow for endpoints in this inventory.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — the AJAX trace path lands directly in this doc's debugging guide.
- **Rules:** [`php-standards.md`](../../../.claude/rules/php-standards.md) — security and i18n conventions every handler must honour.
- **Architecture:** [`./README.md`](README.md) — folder index and the five dynamic-data flows.
- **Architecture:** [`../asset-loading.md`](../asset-loading.md) — `Asset_Builder::load_commnon_asset` builds the `localize` object that ships nonces to the frontend.
- **Architecture:** [`./wp-query-construction.md`](wp-query-construction.md) (Phase 2B) — the shared query construction these handlers depend on.
- **Architecture:** [`./load-more-and-pagination.md`](load-more-and-pagination.md) (Phase 2C) — the load-more / pagination behaviour summarised in the worked example.
- **Architecture:** [`./woocommerce-integration.md`](woocommerce-integration.md) (Phase 2E) — the WC-specific endpoints in this inventory.
