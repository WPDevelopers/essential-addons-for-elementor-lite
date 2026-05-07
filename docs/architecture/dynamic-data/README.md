# Dynamic Data

Where the plugin's runtime data flow lives — AJAX endpoints, server-built `WP_Query` calls, pagination and load-more, login and registration, WooCommerce integration, third-party fields. This is the area issue [#804](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/804) called out as the largest documentation gap, and it spans roughly 6,000 lines across `Ajax_Handler` (1,685L), `Login_Registration` (1,855L), `Woo_Product_Comparable` (2,330L), and parts of `Helper` and `Template_Query`.

For static rendering — the path from saved settings to first-paint HTML — see [`../editor-data-flow.md`](../editor-data-flow.md). For asset enqueueing on the rendered page, see [`../asset-loading.md`](../asset-loading.md). This folder covers what happens *after* the page is loaded and JavaScript starts requesting more data.

## Sub-Docs Index

| Doc | Status | Covers |
| --- | ------ | ------ |
| [`ajax-endpoints.md`](ajax-endpoints.md) | ✅ Available (Phase 2A) | Full inventory of `wp_ajax_*` actions, nonce conventions, security triad, response shapes |
| [`wp-query-construction.md`](wp-query-construction.md) | ⬜ Planned (Phase 2B) | Shared query helpers, `Template_Query` trait, `wp_parse_str` patterns, query-arg sanitization |
| [`load-more-and-pagination.md`](load-more-and-pagination.md) | ⬜ Planned (Phase 2C) | Infinite scroll mechanics, page state, `args`/`page` data, IsotopeJS interactions |
| [`login-register.md`](login-register.md) | ⬜ Planned (Phase 2D) | The 1,855-line `Login_Registration` trait — nonce, redirect, social, reCAPTCHA, validation |
| [`woocommerce-integration.md`](woocommerce-integration.md) | ⬜ Planned (Phase 2E) | `Woo_Product_Comparable`, `Woo_Hooks`, shared helpers, cart/checkout/quickview/gallery |
| [`third-party-integrations.md`](third-party-integrations.md) | ⬜ Planned (Phase 2F) | ACF, custom fields, EmbedPress, dynamic taxonomy population |

## System Diagram

The five flows that all dynamic-data work falls into:

```text
╔══════════════════════════════════════════════════════════════════╗
║ FLOW 1 — Frontend JS triggers an AJAX request                    ║
║                                                                  ║
║   Browser event (click, scroll, viewport-enter)                  ║
║       │                                                          ║
║       ▼  jQuery $.ajax / fetch                                   ║
║   POST wp-admin/admin-ajax.php                                   ║
║   body: action=eael_<endpoint>                                   ║
║         security=<nonce>                                         ║
║         args=<serialized query data>                             ║
║       │                                                          ║
║       ▼                                                          ║
║   WordPress dispatches to wp_ajax_eael_<endpoint>                ║
║       (or wp_ajax_nopriv_ if no session)                         ║
║       │                                                          ║
║       ▼                                                          ║
║   Ajax_Handler trait method runs                                 ║
║       1. check_ajax_referer / wp_verify_nonce                    ║
║       2. current_user_can (when privileged)                      ║
║       3. sanitize input (parse $_POST['args'])                   ║
║       4. WP_Query / WC API call                                  ║
║       5. wp_send_json_success / wp_send_json_error               ║
║       │                                                          ║
║       ▼                                                          ║
║   JSON response → frontend JS handler renders DOM                ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ FLOW 2 — Login / Register form submission                        ║
║                                                                  ║
║   User submits form on the Login & Register widget               ║
║       │                                                          ║
║       ▼                                                          ║
║   Login_Registration trait handler                               ║
║       (form-action route, not ajax-action route)                 ║
║       │                                                          ║
║       ▼                                                          ║
║   Validate → reCAPTCHA → wp_signon / wp_create_user              ║
║       │                                                          ║
║       ▼                                                          ║
║   Redirect / re-render with errors                               ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ FLOW 3 — WooCommerce hook chain                                  ║
║                                                                  ║
║   WC fires an action / filter (cart updated, product loaded)     ║
║       │                                                          ║
║       ▼                                                          ║
║   Woo_Hooks / Woo_Product_Comparable trait listener              ║
║       │                                                          ║
║       ▼                                                          ║
║   Modify response, fragment, or output                           ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ FLOW 4 — Shared query construction                               ║
║                                                                  ║
║   Widget render() OR AJAX handler                                ║
║       │                                                          ║
║       ▼                                                          ║
║   Helper / Template_Query builds $args from settings             ║
║       │                                                          ║
║       ▼                                                          ║
║   new WP_Query( $args ) OR get_posts / wc_get_products           ║
║       │                                                          ║
║       ▼                                                          ║
║   Loop result → markup → response or HTML output                 ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ FLOW 5 — Third-party field resolution                            ║
║                                                                  ║
║   Widget needs ACF / custom-field / dynamic-tag value            ║
║       │                                                          ║
║       ▼                                                          ║
║   get_field() (ACF) or get_post_meta() or Elementor dynamic-tag  ║
║       │                                                          ║
║       ▼                                                          ║
║   Render in markup or JSON response                              ║
╚══════════════════════════════════════════════════════════════════╝
```

Most "why does this widget X stop working at runtime?" reports trace to one of these five flows. Identify the flow first, then drill into the relevant sub-doc.

## Per-Doc Structure

Each sub-doc in this folder follows the same 12-section structure as the parent [`../README.md`](../README.md) defines (Overview → Components → Diagram → Hook Timing → Data Flow → Configuration → Pitfalls → Debugging → Worked Example → ADRs → Limitations → Cross-References).

The [`ajax-endpoints.md`](ajax-endpoints.md) doc additionally embeds the inventory tables — actions, nonces, capabilities — that the rest of the sub-docs reference.

## Cross-References

- **Parent:** [`../README.md`](../README.md) — system map and the four-phase render lifecycle
- **Sibling:** [`../asset-loading.md`](../asset-loading.md) — how the rendered HTML's CSS/JS get on the page
- **Sibling:** [`../editor-data-flow.md`](../editor-data-flow.md) — settings flow that feeds the widgets that fire these AJAX requests
- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — security audit workflow that maps directly onto this folder's contents
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — AJAX trace path lands here when an endpoint returns 4xx/5xx
- **Rules:** [`php-standards.md`](../../../.claude/rules/php-standards.md) — security and i18n conventions every endpoint must follow
- **Widget docs:** [`../../widgets/`](../../widgets/) — per-widget docs reference the specific endpoints their JS calls
