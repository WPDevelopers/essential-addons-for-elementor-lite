# WooCommerce Integration

How EA's eleven WooCommerce widgets plug into WC's hook system, where the shared rendering helpers live, why some WC default actions are removed and re-emitted under `eael_woo_*` prefixes, and how the cart / checkout / compare AJAX flows weave through it all.

The single most-touched cross-cutting concern when working with EA Pro's commerce widgets — bugs here often surface as "the add-to-cart button does nothing" or "the cart fragment doesn't update" or "Astra theme broke the product loop after EA was activated".

## Overview

EA does three things to integrate with WooCommerce:

1. **Mirrors core WC actions under EA-prefixed names.** [`Bootstrap.php:227-233`](../../../includes/Classes/Bootstrap.php#L227) re-registers `woocommerce_show_product_images`, `woocommerce_template_single_title`, etc. against new `eael_woo_single_product_image` and `eael_woo_single_product_summary` actions. Widgets like Woo_Product_Gallery and the Quick View popup invoke the EA-prefixed actions instead of the WC ones, giving EA control over what runs in those slots.
2. **Removes and re-adds the WC product-loop link wrappers** ([Bootstrap.php:248-273](../../../includes/Classes/Bootstrap.php#L248)) when a non-supported theme like Astra is active, so EA-styled markup nests correctly without Astra's theme-level customisations conflicting.
3. **Hosts cart, checkout, compare, gallery, and quickview AJAX flows** in [`Ajax_Handler`](../../../includes/Traits/Ajax_Handler.php) — the same security triad applies as in [`ajax-endpoints.md`](ajax-endpoints.md).

Plus the `Woo_Product_Comparable` trait (2,330 lines) — the largest single-purpose trait in the plugin — implements the entire Product Compare widget: control registration, table rendering, add/remove URLs, and the AJAX-driven compare table update.

## Components

| File / Method | Lines | Role |
| ------------- | ----- | ---- |
| [`includes/Classes/Bootstrap::register_wc_hooks`](../../../includes/Classes/Bootstrap.php#L318) | — | Hooked on `init` priority 5; wires up all WC integration when WC is active |
| [`Bootstrap` lines 225-273](../../../includes/Classes/Bootstrap.php#L225) | ~50 | EA-prefixed action mirrors + Astra theme compat |
| [`Bootstrap::eael_customize_woo_checkout_fields`](../../../includes/Classes/Bootstrap.php#L241) | — | `woocommerce_checkout_fields` filter for Woo Checkout widget customisation |
| [`includes/Traits/Woo_Product_Comparable.php`](../../../includes/Traits/Woo_Product_Comparable.php) | 2,330 | Product Compare widget — controls + render + cookie persistence + AJAX |
| [`includes/Template/Woocommerce/Cart/Woo_Cart_Helper`](../../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php) | — | Trait used by Woo_Cart widget; cart fragment rendering |
| [`includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper`](../../../includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php) | — | Trait used by Woo_Checkout widget; checkout sections, fragments |
| [`includes/Template/Content/Woo_Product_List`](../../../includes/Template/Content/Woo_Product_List.php) | — | Trait used by Woo_Product_List widget; layout-mode-specific output |
| [`Helper::eael_product_quick_view`](../../../includes/Classes/Helper.php#L1014) | — | Quick View popup markup |
| [`Helper::eael_woo_product_grid_actions`](../../../includes/Classes/Helper.php#L1064) | — | Sets up Product_Grid loop's WC actions before rendering |
| [`Helper::get_query_args`](../../../includes/Classes/Helper.php#L179) | (95) | Same shared query builder; product-type-aware Whols compat at [line 266](../../../includes/Classes/Helper.php#L266) |
| [`includes/Classes/Compatibility_Support`](../../../includes/Classes/Compatibility_Support.php) | 88 | Mondial Relay shipping compat for Woo Checkout |
| AJAX handlers | — | All registered in [`Ajax_Handler::init_ajax_hooks`](../../../includes/Traits/Ajax_Handler.php#L32); see [`./ajax-endpoints.md`](ajax-endpoints.md) for the full WC subset |

### The eleven WooCommerce widgets

| Widget | Class | Primary purpose |
| ------ | ----- | --------------- |
| Product Grid | `Product_Grid` | Grid / list / masonry of products with filtering and load-more |
| Woo Add To Cart | `Woo_Add_To_Cart` | Stand-alone add-to-cart button for a single product |
| Woo Cart | `Woo_Cart` | Full cart-page replacement |
| Woo Checkout | `Woo_Checkout` | Full checkout-page replacement with multi-step / accordion / modern layouts |
| Woo Product Carousel | `Woo_Product_Carousel` | Slider of products |
| Woo Product Compare | `Woo_Product_Compare` | Compare table with cookie-persisted product IDs |
| Woo Product Gallery | `Woo_Product_Gallery` | Tabbed / filterable product gallery (own taxonomy tabs) |
| Woo Product Images | `Woo_Product_Images` | Single product's image gallery |
| Woo Product List | `Woo_Product_List` | List layout for products with custom controls |
| Woo Product Price | `Woo_Product_Price` | Single product's price block |
| Woo Product Rating | `Woo_Product_Rating` | Single product's rating block |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ INIT PHASE                                                       ║
║                                                                  ║
║   plugins_loaded → Bootstrap::__construct                        ║
║       │                                                          ║
║       ▼                                                          ║
║   if class_exists('woocommerce')                                 ║
║       │                                                          ║
║       ▼                                                          ║
║   add_action('init', 'register_wc_hooks', 5)                     ║
║       │  add EA-prefixed action mirrors:                         ║
║       │    eael_woo_single_product_image →                       ║
║       │      woocommerce_show_product_images (priority 20)       ║
║       │    eael_woo_single_product_summary →                     ║
║       │      woocommerce_template_single_title (5)               ║
║       │      woocommerce_template_single_rating (10)             ║
║       │      woocommerce_template_single_price (15)              ║
║       │      woocommerce_template_single_excerpt (20)            ║
║       │      woocommerce_template_single_add_to_cart (25)        ║
║       │      woocommerce_template_single_meta (30)               ║
║       │                                                          ║
║       │  add_filter woocommerce_checkout_fields                  ║
║       │    → eael_customize_woo_checkout_fields                  ║
║       │                                                          ║
║       │  Astra theme detected? → theme-loop compat               ║
║       │    remove standard astra-woo loop wrappers,              ║
║       │    re-add WC defaults so EA markup nests correctly       ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ FIRST RENDER (page load) — Product_Grid                          ║
║                                                                  ║
║   render() reads $settings → Helper::get_query_args($settings,   ║
║     'product')                                                   ║
║       │  Whols compat at Helper.php:266 fires                    ║
║       │  if WC's hide-out-of-stock setting on, applies meta_query║
║       │                                                          ║
║       ▼                                                          ║
║   Helper::eael_woo_product_grid_actions sets up loop:            ║
║     • woocommerce_add_to_cart_form_action filter                 ║
║     • eael_woo_before_product_loop action runs                   ║
║       (woocommerce_output_all_notices)                           ║
║       │                                                          ║
║       ▼                                                          ║
║   new WP_Query($args) → loop products → wc loop templates emit   ║
║     standard WC product markup                                   ║
║       │                                                          ║
║       ▼                                                          ║
║   add_to_cart button rendered with EA-aware data attributes      ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ AJAX FLOW — Add to Cart (eael_ajax_add_to_cart)                  ║
║                                                                  ║
║   User clicks Add to Cart                                        ║
║       │ frontend JS fetches /admin-ajax.php                      ║
║       │   action=eael_ajax_add_to_cart                           ║
║       │   security=<plugin-wide nonce>                           ║
║       │   product_id, quantity, variation_id, …                  ║
║       ▼                                                          ║
║   Ajax_Handler::eael_ajax_add_to_cart                            ║
║       1. check_ajax_referer('essential-addons-elementor',        ║
║                              'security')                         ║
║       2. WC()->cart->add_to_cart(...)                            ║
║       3. Build cart fragments (cart count, mini cart, etc.)      ║
║       4. wp_send_json_success([ fragments, cart_hash, …])        ║
║       │                                                          ║
║       ▼                                                          ║
║   JS replaces fragment selectors in DOM                          ║
║   (e.g. .widget_shopping_cart_content, .cart-contents-count)     ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ CHECKOUT FLOW — woo_checkout_update_order_review                 ║
║                                                                  ║
║   User changes shipping country / state / coupon on checkout     ║
║       │                                                          ║
║       ▼                                                          ║
║   WC core fires AJAX → woocommerce_update_order_review_response  ║
║   EA wraps with woo_checkout_update_order_review handler:        ║
║     • Re-renders order review with EA's checkout-helper output   ║
║     • Returns updated fragments (totals, shipping options)       ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ COMPARE FLOW — Woo_Product_Comparable                            ║
║                                                                  ║
║   User clicks "Add to Compare" on a Product_Grid item            ║
║       │                                                          ║
║       ▼                                                          ║
║   JS reads product id → updates eael_compare cookie              ║
║       │                                                          ║
║       ▼                                                          ║
║   AJAX action=eael_product_grid → get_compare_table              ║
║       1. nonce check                                             ║
║       2. read product_ids from POST                              ║
║       3. validate each id exists + is publish                    ║
║       4. render compare table via trait's table-builder methods  ║
║       5. wp_send_json_success([ html ])                          ║
║       │                                                          ║
║       ▼                                                          ║
║   JS replaces compare modal contents                             ║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

EA registers the WC integration hooks on `init` priority 5 — earlier than most plugins, so EA's customisations apply before theme code runs.

| Hook | Owner | When | Purpose |
| ---- | ----- | ---- | ------- |
| `init` (priority 5) | EA Bootstrap | After plugins_loaded | Triggers `register_wc_hooks` |
| `eael_woo_single_product_image` (action) | EA-emitted | Inside Product_Gallery / Quickview render | Slot for product images |
| `eael_woo_single_product_summary` (action) | EA-emitted | Inside Product_Gallery / Quickview render | Slot for title / rating / price / excerpt / add_to_cart / meta |
| `eael_woo_before_product_loop` (action) | EA-emitted | Inside Product_Grid render and `ajax_load_more` for Product_Grid | Pre-loop setup; default handler outputs WC notices |
| `eael/woo-product-list/before-product-loop` (action) | EA-emitted | Inside Woo_Product_List load-more | Pre-loop setup specific to the list widget |
| `woocommerce_checkout_fields` (filter) | WC core | Checkout render | EA hooks `eael_customize_woo_checkout_fields` to apply widget settings |
| `woocommerce_product_query_meta_query` (filter) | WC core | Inside WP_Query for products | Whols compat applied here ([Helper.php:266](../../../includes/Classes/Helper.php#L266)) |
| `woocommerce_add_to_cart_form_action` (filter) | WC core | Add-to-cart form render | EA may redirect to avoid single-page navigation |
| `woocommerce_review_order_after_shipping` (action) | WC core | Checkout shipping section | Mondial Relay compat hook into this |
| `woocommerce_before_shop_loop_item` / `_after_shop_loop_item` (actions) | WC core | Product loop wrapping | EA may remove/re-add when Astra is active |
| `eael_woo_before_product_loop` action default handler | EA | Default behaviour | `woocommerce_output_all_notices` runs at priority 30 |

## Data Flow — Add to Cart from Product Grid

End-to-end click → cart updated → fragments refreshed:

1. **Render.** Product_Grid render emits product cards. Each card's add-to-cart button has `data-product_id` and (for variable products) variation data.
2. **User clicks button.** Frontend JS captures the click, prevents default form submission.
3. **JS reads product context.** `data-product_id`, quantity from input, variation_id if present, plus the localized `nonce` and `ajaxurl` from `Asset_Builder::load_commnon_asset` ([line 391](../../../includes/Classes/Asset_Builder.php#L391)).
4. **JS POSTs to admin-ajax.php** with action `eael_ajax_add_to_cart`.
5. **`eael_ajax_add_to_cart` handler.** Security triad: nonce check via `check_ajax_referer('essential-addons-elementor', 'security')`. Sanitize product_id via `absint`, quantity via `wc_stock_amount`.
6. **WC API call.** `WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations, $cart_item_data )`. WC handles stock checks, validation, fees.
7. **Cart fragments built.** EA's handler asks WC for the standard fragments (`woocommerce_add_to_cart_fragments` filter). Optionally adds EA-specific fragments like the mini cart icon count.
8. **Response.** `wp_send_json_success([ 'fragments' => $fragments, 'cart_hash' => $cart_hash ])`.
9. **JS receives response, replaces fragment selectors in DOM.** Standard WC pattern — `.widget_shopping_cart_content` HTML replaced wholesale, `.cart-contents-count` updated.
10. **Side effects.** Mini cart slides open, success toast shown if widget is configured for that. The add-to-cart button visually transitions back from "loading" state.

## Configuration & Extension Points

### EA-emitted hooks (the layer EA owns)

| Hook | Use it to |
| ---- | --------- |
| `eael_woo_single_product_image` | Add custom output to the product image slot in EA quickview / gallery |
| `eael_woo_single_product_summary` | Inject custom blocks (badges, related products, custom meta) into the product summary in EA quickview / gallery |
| `eael_woo_before_product_loop` | Run setup before EA Product_Grid renders its loop (e.g. additional notice handlers, custom sorting) |
| `eael/woo-product-list/before-product-loop` | Same for Woo_Product_List |
| `eael_pagination_link` | Customise WC pagination link output (used by EA's WC pagination handlers) |

### WC-side filters EA hooks into

| Hook | EA's use |
| ---- | -------- |
| `woocommerce_checkout_fields` | `Bootstrap::eael_customize_woo_checkout_fields` applies Woo_Checkout widget settings to add / remove / reorder checkout fields |
| `woocommerce_product_query_meta_query` | `Helper::get_query_args` applies it for Whols compat when post type is `product` |
| `woocommerce_add_to_cart_form_action` | EA's redirect-prevention helper |
| `woocommerce_add_to_cart_fragments` | Mini-cart fragment refresh |

### Theme compat

[Bootstrap.php:248-273](../../../includes/Classes/Bootstrap.php#L248) handles Astra-themed sites by:

1. Removing Astra's customised `woocommerce_before_shop_loop_item_title` / `_after_shop_loop_item` callbacks that interfere with EA's product card markup.
2. Re-adding WC's standard `woocommerce_template_loop_product_link_open` / `_close` so EA's nesting works.
3. Re-adding `woocommerce_template_loop_add_to_cart` if Astra removed it.

If a user reports "EA Product Grid looks broken on my Astra theme", the diagnosis usually involves checking whether this compat block ran. Test by deactivating Astra's WC integration setting and observing if the issue persists.

### Whols (B2B / wholesale) compat

[Helper.php:266](../../../includes/Classes/Helper.php#L266): when post type is `product` and `whols_lite()` function exists, the `woocommerce_product_query_meta_query` filter is applied to `$args['meta_query']`. Whols's own meta visibility logic (showing wholesale-only products to wholesale users, hiding from regular customers) hooks this filter — EA piggybacks to keep Whols's behavior intact.

If you query products via direct `wc_get_products()` instead of through the EA helper, you bypass the Whols compat — wholesale products may appear to non-wholesale users.

### Mondial Relay shipping compat

[`Compatibility_Support`](../../../includes/Classes/Compatibility_Support.php) class hooks `woocommerce_review_order_after_shipping` to render the Mondial Relay shipping form within EA's Woo_Checkout widget when the Mondial Relay plugin is active. Single-purpose; well-isolated.

## Common Pitfalls

### `eael_woo_*` actions vs `woocommerce_*` actions diverge

If a third-party plugin removes a callback from `woocommerce_template_single_title`, the EA mirror at `eael_woo_single_product_summary` is not affected — it's a separate action. EA's quickview / gallery may continue showing the title even though the rest of the site has it removed. Conversely, hooking only the EA-prefixed action means changes don't propagate to standard WC product pages.

When extending a hook EA provides, decide which scope you want — EA-only, WC-only, or both — and hook accordingly.

### Astra theme compat is fragile

The conditions that trigger the Astra compat block in Bootstrap are: Astra theme + WooCommerce active + EA's product widget on the page. If any condition flips after init priority 5, the compat doesn't run. Themes that switch active templates via `template_redirect` or other late hooks can produce inconsistent product loop markup.

### `eael_ajax_add_to_cart` does not handle variable products in all configurations

WC's variable products require `variation_id` and `variations` arrays. The EA handler accepts them but doesn't always validate the variation matches the product — relies on WC's `WC()->cart->add_to_cart` to reject mismatches. Some WC versions return cryptic errors when variation_id is invalid; surface those to the user.

### Cart fragments mismatch between WC core and EA

EA's add-to-cart handler returns the standard `woocommerce_add_to_cart_fragments` set. If a third-party plugin adds custom fragments that the regular WC add-to-cart handler emits but the EA handler doesn't, those fragments won't update on EA-driven add-to-cart. Symptoms: mini cart counter updates, custom "items added" indicator doesn't.

### `Product_Grid` vs `Woo_Product_List` divergent loop hooks

Product_Grid fires `eael_woo_before_product_loop`. Woo_Product_List fires `eael/woo-product-list/before-product-loop`. They look similar but are different hooks. Plugins extending the product loop need to hook both to apply their changes consistently across both widgets.

### Checkout field customisation runs at filter time, not save time

`eael_customize_woo_checkout_fields` modifies the displayed fields. If a plugin saves to a field that EA removed via this filter, the save fails silently — the field doesn't exist in the form. Keep checkout-field changes minimal; removing required core fields can break order processing.

### Compare cookie name collisions

The Product Compare widget persists product ids in an `eael_compare` cookie. Multiple sites on a subdomain can collide on this cookie if the cookie path is wide. The widget doesn't currently scope by site URL.

### `eael_woo_before_product_loop` does both Product_Grid render and load-more

Same hook fires in two places — initial render and AJAX load-more. Hooked code must be idempotent. Common bug: a code that registers a side-effect once on each fire ends up registering it N+1 times after N load-more clicks.

### Quickview popup re-renders on every open

[`Helper::eael_product_quick_view`](../../../includes/Classes/Helper.php#L1014) runs the standard WC single-product template chain (using EA-prefixed actions) every time the popup opens. For products with heavy associated content (lots of variations, complex tabs), this is noticeable. No caching layer.

## Debugging Guide

When a WC widget is broken:

1. **Confirm WooCommerce is active.** `function_exists( 'WC' )`. EA's WC code paths are gated on this — if WC deactivates mid-session, EA's compat doesn't fire.
2. **Confirm `init` priority 5 ran.** Add `error_log` in `register_wc_hooks` — confirms the integration setup happened. If not, WC was likely loaded too late or `class_exists('woocommerce')` returned false at the wrong time.
3. **For add-to-cart issues:** open Network tab, find the `eael_ajax_add_to_cart` request. Inspect response — `success: true` with fragments? Inspect the fragments — did the count update? Compare with WC's native add-to-cart on the same page.
4. **For Astra theme issues:** check if EA's compat block ran. Set a `var_dump` in [Bootstrap.php:248](../../../includes/Classes/Bootstrap.php#L248) to confirm the conditional block executed.
5. **For Product_Grid showing wrong products:** trace through `Helper::get_query_args` per [`wp-query-construction.md`](wp-query-construction.md). Check if Whols compat altered the `meta_query`.
6. **For checkout fields not appearing:** dump the result of `eael_customize_woo_checkout_fields` — if EA removed the field, that's expected; configure the widget to keep it.
7. **For compare-table not opening:** check the AJAX response for `eael_product_grid` action. Confirm the cookie has product ids set.
8. **For Mondial Relay not appearing:** verify the plugin is active *and* Compatibility_Support's conditional check passed.

## Worked Example — Woo_Product_Compare add-to-compare flow

1. **Initial render.** Product_Grid widget configured with "Show Compare Button" enabled. Each product card emits a `.eael-product-compare` button with `data-product_id`.
2. **User clicks compare.** Frontend JS reads `data-product_id`, reads existing `eael_compare` cookie, appends id, writes cookie back. Updates compare button UI to "Added" state.
3. **User clicks "View Comparison" floating action.** JS POSTs to admin-ajax.php with action `eael_product_grid`, `compare_button_action=add_to_compare`, `security=<plugin nonce>`, `product_ids=[12, 34, 56]`.
4. **Server handler `get_compare_table`** ([Bootstrap line 162-163](../../../includes/Classes/Bootstrap.php#L162)) runs.
5. **Security triad.** Nonce verified. Each `product_id` sanitized via `absint`. For each id, validates `WC()->product_factory->get_product($id)` returns a real, published, valid product. Invalid ids dropped silently.
6. **Compare table built.** [`Woo_Product_Comparable::get_products_list`](../../../includes/Traits/Woo_Product_Comparable.php#L1954) fetches each product with its comparison fields (price, rating, dimensions, custom attributes per widget settings).
7. **HTML rendered** via the trait's table-builder methods (`init_style_table_common_style`, `init_style_header_column_style`, etc., used at render-time) — produces a complete table HTML.
8. **Response.** `wp_send_json_success([ 'html' => $table_html ])`.
9. **JS injects the table into the modal container** — typically a `.eael-compare-modal` div on the page.
10. **Modal opens.** User sees the side-by-side compare table.

Removing a product from compare flips the cookie, posts a similar request, server re-renders without the removed id.

## Architecture Decisions

### Mirror WC actions under `eael_woo_*` prefix instead of hooking core directly

- **Context:** EA's quickview popup and product gallery want to invoke the same single-product templates WC core uses (title, rating, price, etc.) but in a custom container with custom ordering. Hooking `woocommerce_template_single_title` directly affects every product page on the site, not just EA's contexts.
- **Decision:** Define EA-prefixed actions (`eael_woo_single_product_image`, `eael_woo_single_product_summary`) and re-register WC's templates against them with the same priorities. EA contexts fire only the prefixed actions.
- **Alternatives rejected:** Inline call the WC functions directly (loses extensibility — third-parties can't hook); duplicate WC's templates (drift risk).
- **Consequences:** Third-party plugins must hook EA's prefix when extending EA contexts, vs WC's action when extending standard pages. Documented in Pitfalls. Trade-off accepted because the alternative leaks EA-context customisations into standard pages.

### Astra theme compat block in init

- **Context:** Astra's WooCommerce integration removes WC's default loop wrappers and substitutes its own — EA's product card markup nests differently, breaking visually.
- **Decision:** Detect Astra at init priority 5 and selectively undo / re-do loop wrappers so EA's markup pattern works.
- **Alternatives rejected:** Document the incompatibility and ask users to disable Astra's WC integration (poor UX); rebuild EA's markup to match Astra's expected nesting (couples EA tightly to one theme).
- **Consequences:** Bootstrap.php carries theme-specific code. Other popular themes (Storefront, OceanWP) don't need this; we'd add their compat if reports came in. The pattern scales linearly with popular themes — a future maintenance debt.

### Cart fragments returned from EA's add-to-cart handler

- **Context:** WC's add-to-cart returns fragments via the `woocommerce_add_to_cart_fragments` filter. The frontend expects to receive an updated mini cart. EA's custom handler must produce the same shape.
- **Decision:** EA handler calls `apply_filters('woocommerce_add_to_cart_fragments', [])` to get the WC-defined fragment set, plus any EA-specific fragments.
- **Alternatives rejected:** Trigger WC's full add-to-cart endpoint via internal redirect (complex; loses control); ignore fragments (mini cart wouldn't update).
- **Consequences:** Compat with WC's standard ecosystem. The Pitfall about fragment mismatches when third-parties add custom fragments is the trade-off — they expect WC's exact codepath; EA's mirroring isn't perfect.

### `Woo_Product_Comparable` as a trait, not a class

- **Context:** Two widgets share comparable behaviour — `Woo_Product_Compare` (the standalone widget) and the compare-button feature embedded in `Product_Grid`. A trait composed into both shares the comparison logic.
- **Decision:** Implement as a trait. Compose into both widget classes.
- **Alternatives rejected:** Copy code (drift); sub-class shared logic in a base class (PHP single inheritance constraints; both widgets already extend `\Elementor\Widget_Base`).
- **Consequences:** A 2,330-line trait. Big, but each method is single-purpose and well-named. The trait is the largest in the plugin; future refactor candidate as comparison logic grows.

## Known Limitations

- **Astra-only theme compat.** Storefront, OceanWP, Hello, GeneratePress all work fine without specific compat — but if a user's theme aggressively customises WC loop hooks like Astra does, EA's product widgets may visually break with no fallback.
- **No per-handler rate limiting.** Anonymous add-to-cart and pagination endpoints accept arbitrary submission volume. WAF / hosting-layer protection is the recommended mitigation; the plugin doesn't ship a rate limiter.
- **`eael_compare` cookie scoping.** Cookie path is site-wide; multi-site / subdomain installs can collide on the cookie name.
- **Quickview re-renders on every open.** No caching of the per-product HTML even when the same popup is opened repeatedly.
- **Product_Grid load-more `eael_woo_before_product_loop` idempotency.** Hooked code must be idempotent; the action fires on first render and on every load-more.
- **`eael_customize_woo_checkout_fields` is not idempotent.** Running it twice on the same fields array doubles up changes. Ensure it's hooked only once per request.
- **Variable product variation handling depends on WC version.** Some EA configurations work flawlessly on WC 8 but produce vague errors on WC 7.
- **Whols compat is one-way.** When Whols becomes active mid-session, the meta_query filter chain may return unexpectedly empty arrays, replacing whatever args were there.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — folder index; this is "Flow 3" (WC hook chain) in the system diagram.
- **Architecture:** [`./ajax-endpoints.md`](ajax-endpoints.md) — full inventory of WC AJAX endpoints with security profiles.
- **Architecture:** [`./load-more-and-pagination.md`](load-more-and-pagination.md) — Product_Grid load-more deep dive includes the `eael_woo_before_product_loop` hook used here.
- **Architecture:** [`./wp-query-construction.md`](wp-query-construction.md) — `Helper::get_query_args` with product type triggers Whols compat.
- **Architecture:** [`../asset-loading.md`](../asset-loading.md) — WC widgets register CSS/JS via the same `config.php` registry.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — AJAX trace path in the skill maps directly to WC AJAX failures.
- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — many WC endpoints accept anonymous requests; the security playbook applies here.
- **Skills:** [`widget-review`](../../../.claude/skills/widget-review/SKILL.md) — when reviewing a WC widget, consult this doc for the cross-cutting concerns.
- **Rules:** [`php-standards.md`](../../../.claude/rules/php-standards.md) — security and i18n conventions all WC handlers honour.
- **Widget docs:** [`../../widgets/`](../../widgets/) — per-widget WC docs (when added) reference this doc for shared infrastructure.
