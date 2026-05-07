# Dynamic Data

Where the plugin's runtime data flow lives — AJAX endpoints, server-built `WP_Query` calls, pagination and load-more, login and registration, WooCommerce integration, third-party fields. This is the area issue [#804](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/804) called out as the largest documentation gap, spanning roughly 6,000 lines across `Ajax_Handler` (1,685), `Login_Registration` (1,855), `Woo_Product_Comparable` (2,330), and parts of `Helper` and `Template_Query`.

The scope was too large to fit into a single page without losing navigability, so the content lives in a sibling **[`dynamic-data/`](dynamic-data/) folder** with one focused doc per subsystem. This page is the entry point — read the overview here, then drill into the relevant sub-doc.

For static rendering — the path from saved settings to first-paint HTML — see [`./editor-data-flow.md`](editor-data-flow.md). For asset enqueueing on the rendered page, see [`./asset-loading.md`](asset-loading.md). This area covers what happens *after* the page is loaded and JavaScript starts requesting more data.

## Sub-Docs Index

| Doc | Covers |
| --- | ------ |
| [`dynamic-data/README.md`](dynamic-data/README.md) | Folder index + the five dynamic-data flows (system map for the subsystem) |
| [`dynamic-data/ajax-endpoints.md`](dynamic-data/ajax-endpoints.md) | Inventory of 18+ frontend `wp_ajax_*` actions, two nonce conventions, security triad |
| [`dynamic-data/wp-query-construction.md`](dynamic-data/wp-query-construction.md) | Shared `Helper::get_query_args` query builder used by 8+ list widgets |
| [`dynamic-data/load-more-and-pagination.md`](dynamic-data/load-more-and-pagination.md) | Click + infinite scroll mechanics, page state, isotope re-layout |
| [`dynamic-data/login-register.md`](dynamic-data/login-register.md) | The 1,855-line `Login_Registration` trait — login / register / lost / reset, reCAPTCHA + Cloudflare Turnstile |
| [`dynamic-data/woocommerce-integration.md`](dynamic-data/woocommerce-integration.md) | EA-prefixed action mirrors, theme compat, eleven WC widgets, compare-table flow |
| [`dynamic-data/third-party-integrations.md`](dynamic-data/third-party-integrations.md) | ACF, EmbedPress, ten form plugins, compat shims, custom-meta integration |

## Five Flows at a Glance

The five flows that all dynamic-data work falls into. Identify the flow first, then drill into the relevant sub-doc — the flow diagrams in [`dynamic-data/README.md`](dynamic-data/README.md) walk each one in detail.

1. **Frontend JS triggers an AJAX request** — the standard pattern for load-more, filter-toggle, add-to-cart, quickview popup. POST to `admin-ajax.php` with `action=eael_<endpoint>`. Handler runs the security triad (nonce + cap + sanitize) before any work.
2. **Login / Register form submission** — different from the AJAX route: form posts to the current page, `$_POST` flag identifies which sub-action runs, errors are surfaced via cookie + redirect rather than JSON. See [`login-register.md`](dynamic-data/login-register.md).
3. **WooCommerce hook chain** — WC fires `woocommerce_*` action; EA's `Woo_Hooks` / `Woo_Product_Comparable` listeners modify the response or fragment. See [`woocommerce-integration.md`](dynamic-data/woocommerce-integration.md).
4. **Shared query construction** — widget `render()` or AJAX handler calls `Helper::get_query_args($settings, $post_type)` → `WP_Query`. See [`wp-query-construction.md`](dynamic-data/wp-query-construction.md).
5. **Third-party field resolution** — ACF / custom-meta / dynamic-tag values are read inline during render or AJAX. See [`third-party-integrations.md`](dynamic-data/third-party-integrations.md).

Most "why does this widget X stop working at runtime?" reports trace to one of these five flows.

## Cross-References

- **Parent:** [`./README.md`](README.md) — system map and the four-phase render lifecycle
- **Sibling:** [`./asset-loading.md`](asset-loading.md) — how the rendered HTML's CSS/JS get on the page
- **Sibling:** [`./editor-data-flow.md`](editor-data-flow.md) — settings flow that feeds the widgets that fire these AJAX requests
- **Skills:** [`.claude/skills/nopriv-ajax-hardening/SKILL.md`](../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — security audit workflow that maps directly onto this area
- **Skills:** [`.claude/skills/debug-widget/SKILL.md`](../../.claude/skills/debug-widget/SKILL.md) — AJAX trace path lands here when an endpoint returns 4xx/5xx
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md) — security and i18n conventions every endpoint must follow
- **Widget docs:** [`../widgets/`](../widgets/) — per-widget docs reference the specific endpoints their JS calls
