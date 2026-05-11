# Woo Checkout Widget

> Replaces WooCommerce's native checkout shortcode with EA-styled rendering: drag-and-drop reordering of billing/shipping fields, AJAX-updated order review on shipping/coupon/qty changes, custom labels for every checkout region, and three layout variants (`default` Lite; `multi-steps` and `split` Pro). Removes / re-registers ~10 WC core action callbacks at render time and writes post meta on every frontend render when field-reorder is enabled.

**Class file:** [`includes/Elements/Woo_Checkout.php`](../../includes/Elements/Woo_Checkout.php)
**Slug:** `woo-checkout` (widget id `eael-woo-checkout`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/woo-checkout/>
**Pro-shared:** ✅ Yes — Pro adds `multi-steps` + `split` layouts via the `eael/woo-checkout/layout` filter and four Pro-only `do_action` extension points (`eael_woo_checkout_pro_enabled_general_settings`, `_tabs_styles`, `_steps_btn_styles`, `eael_add_woo_checkout_pro_layout`). Pro gating via `eael/pro_enabled` filter; selecting a Pro layout on Lite shows inline RAW_HTML warning + silent `return` from `render()` at [line 3196-3198](../../includes/Elements/Woo_Checkout.php#L3196).

---

## Overview

Woo Checkout replaces WC's `[woocommerce_checkout]` shortcode with EA's own rendering pipeline. Constructor (line 24-44) bootstraps `WC()->cart` if null, adds `body_class` filter for `eael-woo-checkout`, and wires WC Subscriptions recurring-totals hook. Render fires `eael_woo_checkout_before_render` action, force-removes several theme/plugin actions (Neve coupon move, WC Carrier Agents shipping field), registers `woocommerce_checkout_fields` filter at priority 99999, then dispatches to `ea_checkout()` / `ea_order_pay()` / `ea_order_received()` based on `$wp->query_vars`.

The field-reorder feature is the biggest unique contribution. Two Repeaters (`ea_billing_fields_list`, `ea_shipping_fields_list`) seeded from `WC()->checkout()->get_checkout_fields()` at panel-load. At render, the chosen order is persisted to post meta `_eael_checkout_fields_settings` + JSON-serialised into `data-checkout_ids` attribute. JS listens for WC's `country_to_state_changing` event and reorders DOM fragments into custom containers.

The widget removes WC's default checkout-render callbacks (`woocommerce_checkout_login_form`, `_coupon_form`, `checkout_form_billing`, `_form_shipping`, `woocommerce_order_review`, `woocommerce_checkout_payment`) and re-registers EA's replacements — but only if `did_action()` reports the WC action hasn't fired yet (defensive against double-render contexts).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Layout `default` (sidebar order review + form) | ✅ | ✅ |
| Layout `multi-steps` (3-step wizard with tabs) | ❌ — silent `return` in render; inline warning shown in panel | ✅ via Pro layouts hooked into `eael_add_woo_checkout_pro_layout` action |
| Layout `split` (50/50 form-summary split) | ❌ — same gating as multi-steps | ✅ |
| Field reorder via drag-and-drop Repeater (billing + shipping) | ✅ | ✅ |
| AJAX order review update on shipping/coupon/qty change | ✅ | ✅ |
| AJAX cart-item quantity update with 300ms debounce | ✅ | ✅ |
| Custom labels for: title, login, coupon, order summary, shipping, totals, place-order | ✅ | ✅ |
| Custom shipping package name | ✅ | ✅ |
| WC Subscriptions recurring-totals support | ✅ — auto-wired when `WC_Subscriptions_Cart` exists | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present (Pro signal is inline RAW_HTML warning + image picker dimming) | — |
| Pro-only style sections (tabs styles, steps button styles) | ❌ — Pro hooks them in via `eael_woo_checkout_pro_enabled_*` actions | ✅ |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Checkout.php`](../../includes/Elements/Woo_Checkout.php) | PHP widget class (3348 lines) — controls (huge style section), `render()` dispatcher, field-reorder logic, body-class filter |
| [`includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php`](../../includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php) | Trait (852 lines) — static `$setting_data` storage; `ea_checkout()`, `ea_order_received()`, `ea_order_pay()`, `ea_coupon_template()`, `ea_login_template()`, `checkout_order_review_template()`, `checkout_order_review_default()`, `render_default_template_()`, `ea_woo_checkout_add_actions()`, `eael_checkout_cart_quantity_input_print()` |
| [`includes/Traits/Ajax_Handler.php`](../../includes/Traits/Ajax_Handler.php#L911) | `woo_checkout_update_order_review()` AJAX handler ([line 911-939](../../includes/Traits/Ajax_Handler.php#L911)) — checks `essential-addons-elementor` nonce, sets `WC()->session` shipping method, re-renders order-review default template |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L237) | Registers `wp_ajax_eael_checkout_cart_qty_update` + nopriv (line 237-238) — qty input change handler |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php#L477) | `eael_forcefully_remove_action()` — removes a hook callback by string lookup against `$wp_filter` global, even when added by instance method |
| [`src/css/view/woo-checkout.scss`](../../src/css/view/woo-checkout.scss) | Source styles (966 lines) — per-layout selectors, form-row spacing, blockUI overlay overrides, payment list |
| [`src/js/view/woo-checkout.js`](../../src/js/view/woo-checkout.js) | Frontend logic (193 lines) — AJAX order-review refresh; field reorder on country/state change; qty update; place-order button text override |
| [`config.php`](../../config.php#L1052) entry `'woo-checkout'` | Single CSS + single JS, no vendor libs |
| `assets/front-end/css|js/view/woo-checkout.min.*` | Built outputs |

Style depends declared: `font-awesome-5-all` + `font-awesome-4-shim` (FA4 shim is deprecated in modern Elementor).

## Architecture

- **Constructor side-effects** ([line 24-44](../../includes/Elements/Woo_Checkout.php#L24)) — Unlike Woo_Cart, NOT gated by post-meta widget detection. Fires for every editor/render of any `is_type_instance` Woo_Checkout: bootstraps `WC()->cart` if null (direct `include_once` + `wc_load_cart()`); adds `body_class` filter for `eael-woo-checkout` class on `is_checkout()`; calls `eael_woocheckout_recurring()` which wires `WC_Subscriptions_Cart::display_recurring_totals` to EA's `eael_display_recurring_total_total` action when WC Subscriptions is active.
- **Massive `register_controls()` builds Repeater defaults from live WC schema** ([line 742-752](../../includes/Elements/Woo_Checkout.php#L742)) — `WC()->checkout()->get_checkout_fields('billing')` called at panel-load to seed `ea_billing_fields_list` default Repeater rows. If a third-party plugin adds checkout fields, they appear as defaults on first render. ⚠️ Schema drift: after registering the widget, if a plugin adds new fields, the Repeater defaults are NOT updated — the panel-side warning instructs users to "remove this widget and again add this".
- **Render method DB-writes on every frontend page load** ([line 3235](../../includes/Elements/Woo_Checkout.php#L3235)) — `update_post_meta( $post->ID, '_eael_checkout_fields_settings', $eael_checkout_fields )` runs unconditionally when `eael_enable_checkout_fields_reorder == 'yes'`, OR `delete_post_meta` runs when disabled. **Side effect on every render** — front-end page load writes to DB. Caches that mediate WP cache hits can mask this; uncached pages add a DB write per render.
- **Force-removes other plugins' WC hooks** ([line 3183-3189](../../includes/Elements/Woo_Checkout.php#L3183)) — `eael_forcefully_remove_action` removes Neve theme's `move_coupon` + `clear_coupon` callbacks (priority 10) and Woo Carrier Agents' `add_carrier_agent_field_before_payment` callback. Carrier Agents re-attached to EA's `eael_wc_multistep_checkout_after_shipping` action — Pro-layout flow. ⚠️ Hard-coded plugin compatibility shims; if Neve or Carrier Agents rename their callbacks, removal fails silently.
- **Settings transfer via static `$setting_data`** — `Woo_Checkout_Helper::$setting_data` (public static array). `ea_woo_checkout_add_actions($settings)` calls `ea_set_woo_checkout_settings($settings)` to write it; downstream template methods (login, coupon, order-review, billing/shipping forms) read via `ea_get_woo_checkout_settings()`. ⚠️ Same multi-instance pitfall as Woo_Cart: last-write-wins for static state. Multiple Woo_Checkout per page is not supported.
- **WC callback swap** ([Woo_Checkout_Helper.php line 781-817](../../includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php#L781)) — `remove_action` then `add_action`-replace for 6 WC default callbacks: `woocommerce_checkout_login_form` → `ea_login_template`, `_coupon_form` → `ea_coupon_template`, `checkout_form_billing` → `ea_checkout_form_billing`, `_form_shipping` → `ea_checkout_form_shipping`, `woocommerce_order_review` → `checkout_order_review_template`, `woocommerce_checkout_payment` → `ea_checkout_payment`. Each re-registration guarded by `if (!did_action('woocommerce_before_checkout_form'))` etc. — defensive against double-render contexts.
- **`woocommerce_checkout_fields` filter at priority 99999** ([line 3192](../../includes/Elements/Woo_Checkout.php#L3192)) — runs after all third-party filters; reorders billing+shipping field arrays based on Repeater order. Sets each field's `priority` to `($key + 1) * 10` so WC's `wc_render_field` honours the order. Field `class` array is rewritten to include only the selected width class (`form-row-first`/`-last`/`-wide`).
- **`render_order_review_template()` JS uses a 100-second unblock timer** ([line 41 of woo-checkout.js](../../src/js/view/woo-checkout.js#L41)) — `setTimeout(unblock, 100000)` runs **only if AJAX completes first** to remove the blockUI overlay. If the AJAX request fails or hangs, the overlay would persist for the full 100 seconds (effectively forever). The current placement inside `success` callback means it only fires on success, so failed AJAX leaves the overlay stuck.
- **`country_to_state_changing` event reorders DOM after WC AJAX fields refresh** ([line 122-173 of woo-checkout.js](../../src/js/view/woo-checkout.js#L122)) — WC re-renders billing/shipping fields when the country selector changes; EA's listener reads `data-checkout_ids` from the wrapper and DOM-shuffles each field into stub containers (`<div type="text/template" id="eael-wc-billing-reordered-fields">`) emitted by `render()`. Uses `wrapper.find()` then `replaceWith()` per field — 500ms `setTimeout` to wait for WC's render to finish.
- **`#place_order` button text override** ([line 175-193 of woo-checkout.js](../../src/js/view/woo-checkout.js#L175)) — 500ms `setTimeout` on `update_checkout` / `payment_method_selected` / `updated_checkout` events + payment label clicks. Re-applies custom text from `data-button_texts.place_order`. Pure DOM patch; WC's own re-render in same events can revert before patch fires (race condition possible).
- **No `eael_section_pro` upsell panel** — Pro detection is inline RAW_HTML warning when Pro layout selected (line 159-170) + 4 Pro extension `do_action` points + silent `return` in render. Standard `_patterns.md § eael_section_pro` pattern does not apply.
- **Bypasses Editor Updater context** ([line 92](../../includes/Elements/Woo_Checkout.php#L92)) — when `is_admin() && $_GET['elementor_updater']` is set, `register_controls()` returns early. Prevents the panel-load WC `get_checkout_fields()` calls (which may fail under the Elementor updater AJAX context).

## Render Output

```html
<body class="… eael-woo-checkout">  <!-- via body_class filter on is_checkout() pages; also via JS document.body.classList.add() on render -->

<div class="ea-woo-checkout layout-{default|multi-steps|split} {checkout-reorder-enabled} {astra-pro-wc-module-activated}"
     data-checkout="<json-encoded order_review settings>"
     [?] data-button_texts='{"place_order":"…"}'
     [?] data-checkout_ids='{"billing":{"billing_first_name":"form-row-first", …},"shipping":{…}}'>

  <!-- Stub containers used by JS field reorder -->
  <div type="text/template" id="eael-wc-billing-reordered-fields">
    <div class="eael-woo-billing-fields"></div>
  </div>
  <div type="text/template" id="eael-wc-shipping-reordered-fields">
    <div class="eael-woo-shipping-fields"></div>
  </div>

  <div class="woocommerce">
    <style>.woocommerce .blockUI.blockOverlay:before { background-image: url('<WC loader.svg>') …; }</style>

    <!-- Dispatched by render(): -->
    [?] ea_order_pay($wp->query_vars['order-pay'])      <!-- pay-for-order endpoint -->
    [?] ea_order_received($wp->query_vars['order-received'])  <!-- thank-you page -->
    [?] ea_checkout($settings)                           <!-- default checkout -->

    <!-- Default-layout ea_checkout output (Lite): -->
    do_action('woocommerce_before_checkout_form_cart_notices')
    {empty-cart-return when cart empty}
    do_action('woocommerce_check_cart_items')
    self::render_default_template_($checkout, $settings)
      do_action('woocommerce_before_checkout_form', $checkout)
      <form class="checkout woocommerce-checkout ea-woo-checkout-form">
        <div class="ea-woo-checkout-left">
          <!-- Replaced WC callbacks: -->
          ea_login_template       → "Returning customer? Click here to login" + login form
          ea_coupon_template      → "Have a coupon?" + coupon form
          ea_checkout_form_billing → billing fields (reordered)
          ea_checkout_form_shipping → shipping fields (reordered)
        </div>
        <div class="ea-woo-checkout-right">
          checkout_order_review_template  → calls checkout_order_review_default($settings)
            <table class="ea-checkout-review-order-table">
              <thead>{Your Order header text}</thead>
              <tbody>
                <!-- per cart item: -->
                <li class="table-row {woocommerce_cart_item_class}">
                  <div class="row-thumbnail"><img …></div>
                  <div class="row-name">…  ×{quantity} or qty input</div>
                  <div class="row-quantity">{quantity}</div>
                  <div class="row-price">{subtotal}</div>
                </li>
              </tbody>
              <tfoot>
                {Shop link row when cart empty}
                {Subtotal row} {Shipping row} {Coupon rows} {Fees rows} {Tax rows} {Total row}
                {do_action eael_display_recurring_total_total — WC Subscriptions}
              </tfoot>
            </table>
          ea_checkout_payment    → payment method list + #place_order button
        </div>
      </form>
      do_action('woocommerce_after_checkout_form', $checkout)

    <!-- Pro-only Pro layouts (multi-steps / split) dispatched via: -->
    do_action('eael_add_woo_checkout_pro_layout', $checkout, $settings)
  </div>
</div>
```

Notes:

- `data-checkout` carries JSON-encoded order-review label settings (header text, product text, qty text, etc.) — JS reads it and sends to AJAX endpoint on every order-review refresh.
- `data-checkout_ids` carries JSON-encoded field-key → class map per type (billing/shipping) — JS uses to know which field goes where on `country_to_state_changing` event.
- `.eael-checkout-cart-qty-input` on cart-item qty inputs — JS `change` handler debounced 300ms fires `eael_checkout_cart_qty_update` AJAX.
- Inline `<style>` block injected at top of `.woocommerce` wrapper overrides blockUI's spinner with WC's own loader.svg.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Checkout.php#L91) is the truth — only the meaningful controls listed.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `ea_woo_checkout_layout` | CHOOSE (image, filterable) | `default` | Content → General Settings | Layout dispatcher; image options seeded from `eael/woo-checkout/layout` filter |
| `eael_woo_checkout_pro_enable_warning` | RAW_HTML | — | Content → General Settings | Pro upsell inline warning when Pro layout selected on Lite |
| `ea_woo_checkout_order_details_title` | TEXT (dynamic + AI) | `"Your Order"` | Content → Order Details | Order summary heading |
| `ea_woo_checkout_cart_update_enable` | SWITCHER | `yes` | Content → Order Details | Show qty input in cart-summary; enables `.eael-checkout-cart-qty-input` JS handler |
| `ea_woo_checkout_table_*_text` (header, product, quantity, price, subtotal, shipping, total, …) | TEXT × many | i18n defaults | Content → Order Details | Order-review column / row labels |
| `ea_woo_checkout_shop_link` / `_shop_link_text` | SWITCHER + TEXT | varies | Content → Order Details | Empty-cart "back to shop" link |
| `ea_woo_checkout_coupon_title` / `_coupon_link_text` | TEXT × 2 | varies | Content → Coupon | Coupon collapsed label + reveal link |
| `ea_woo_checkout_login_title` / `_login_link_text` | TEXT × 2 | varies | Content → Login | Login collapsed label + reveal link |
| `ea_woo_checkout_billing_title` / `_shipping_title` / `_payment_title` | TEXT × 3 | varies | Content → Billing/Shipping/Payment | Section headings |
| `ea_woo_checkout_place_order_text` | TEXT (dynamic + AI) | `"Place Order"` | Content → Payment | Place-order button text; JS overrides `#place_order` text via `data-button_texts` |
| `eael_enable_checkout_fields_reorder` | SWITCHER | `yes` | Content → Billing/Shipping Fields | Toggles field reorder system (writes/deletes post meta `_eael_checkout_fields_settings`) |
| `eael_new_field_appearing_position` | SELECT | `before` | Content → Billing/Shipping Fields | New / unlisted fields appear `before` or `after` reordered list |
| `eael_new_checkout_fields_not_found` | RAW_HTML | — | Content → Billing/Shipping Fields | Warning instructing users to re-add widget when custom fields added later |
| `ea_billing_fields_list` (Repeater) | REPEATER | seeded from `WC()->checkout()->get_checkout_fields('billing')` | Content → Billing/Shipping Fields → Billing tab | Per-field: `field_label`, `field_key` (hidden), `field_class` (SELECT: form-row-first/last/wide), `field_placeholder` |
| `ea_shipping_fields_list` (Repeater) | REPEATER | seeded from shipping fields | Content → Billing/Shipping Fields → Shipping tab | Same per-field schema as billing |
| Many Style sections (12+ sections from line 833-3105) | various | — | Style tab | Per-region typography, color, spacing, border — sale badge, tabs, steps button (Pro), payment list, place-order, etc. |
| Pro-injected sections | — | — | Style tab | Via `eael_woo_checkout_pro_enabled_general_settings` (general), `eael_woo_checkout_pro_enabled_tabs_styles` (multi-step tabs), `eael_woo_checkout_pro_enabled_steps_btn_styles` (steps button) actions |

## Conditional Dependencies

```text
# Layout / Pro gating
eael_woo_checkout_pro_enable_warning   → visible when layout in [multi-steps, split]
                                          AND eael/pro_enabled is false

# Field reorder
eael_new_field_appearing_position      → visible when eael_enable_checkout_fields_reorder == 'yes'
eael_new_checkout_fields_not_found     → same condition
ea_woo_checkout_reorder_fields tabs    → same condition

# Frontend gates
Entire output                          → empty when WooCommerce inactive
ea_checkout() return                   → cart empty AND not customize-preview AND
                                          woocommerce_checkout_redirect_empty_cart filter is true
Pro-layout silent fail                 → layout in [multi-steps, split] AND eael/pro_enabled false

# Endpoint dispatch (URL-driven)
ea_order_pay()                         → $wp->query_vars['order-pay'] set OR legacy $_GET['order']+$_GET['key']
ea_order_received()                    → $wp->query_vars['order-received'] set
ea_checkout()                          → default fallback
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/woo-checkout/layout` | filter (emitted) | `(array $options)` | Pro / 3rd-party can add layout options (key → label); image picker uses `<key>.png` from `assets/admin/images/layout-previews/` |
| `eael_woo_checkout_pro_enabled_general_settings` | action (emitted) | `(Widget_Base $this)` | Pro injects General Settings controls inline |
| `eael_woo_checkout_pro_enabled_tabs_styles` | action (emitted) | `(Widget_Base $this)` | Pro injects Tabs style controls (for multi-step layout) |
| `eael_woo_checkout_pro_enabled_steps_btn_styles` | action (emitted) | `(Widget_Base $this)` | Pro injects Steps Button style controls |
| `eael_add_woo_checkout_pro_layout` | action (emitted) | `(WC_Checkout $checkout, array $settings)` | Pro registers per-layout render handler; switch case fires when layout is not `default` |
| `eael_woo_checkout_before_render` | action (emitted) | `(array $settings)` | Pre-render hook; subscriptions integration removes recurring totals via this |
| `eael_wc_multistep_checkout_after_shipping` | action (emitted) | — | Pro multistep layout fires this between shipping and payment steps; Carrier Agents callback re-attached here ([line 3189](../../includes/Elements/Woo_Checkout.php#L3189)) |
| `eael_woo_checkout_before_cart_get_fees` | action (emitted) | `(WC $wc)` | Inside `checkout_order_review_default()` before iterating cart fees |
| `eael_woo_checkout_cart_fee` | filter (emitted, declared via `apply_filters` but **NOT echoed**) | `(WC_Cart_Fee $fee)` | ⚠️ Bug: filter return value discarded — likely intended as filter but written as if action |
| `eael_display_recurring_total_total` | action (emitted) | — | Subscriptions extension point; `WC_Subscriptions_Cart::display_recurring_totals` attached when WC Subs active |
| `eael_mondialrelay_order_after_shipping` | action (emitted) | — | Mondial Relay shipping plugin integration; fires inside AJAX order review handler |
| `wp_ajax_woo_checkout_update_order_review` / `_nopriv` | action (consumed) | — | AJAX endpoint for order-review refresh; handler in `Ajax_Handler::woo_checkout_update_order_review()` |
| `wp_ajax_eael_checkout_cart_qty_update` / `_nopriv` | action (consumed) | — | AJAX endpoint for cart-item qty update from order summary; registered in `Bootstrap.php` line 237 |
| `body_class` | filter (consumed) | `(array $classes)` via `add_checkout_body_class` | Adds `eael-woo-checkout` class on `is_checkout()` pages |
| `woocommerce_checkout_fields` | filter (consumed) | `(array $fields)` at priority 99999 via `ea_checkout_fields` | Reorders billing+shipping fields based on Repeater order; sets `priority` = `($idx + 1) * 10` |
| `woocommerce_shipping_package_name` | filter (consumed) | `(string $package_name, int $i, array $package)` via `custom_shipping_package_name` | Override shipping package labels |
| `eael/pro_enabled` | filter (consumed) | `(bool $enabled)` | Pro gate |
| `woocommerce_is_checkout` (AJAX handler) | filter (consumed) | force-`true` via `__return_true` | Forces `is_checkout()` to return true during AJAX order-review refresh — Avatax / similar plugins behave correctly |
| WC-native: `woocommerce_before_checkout_form`, `_after_checkout_form`, `woocommerce_checkout_billing`, `_shipping`, `_order_review`, `_review_order_before_*`, `_after_*`, many more | various | various | WC contract preserved; replacement callbacks fire WC's expected hooks |

No `_patterns.md` patterns apply — bespoke Pro gating; no Liquid Glass, no FA4 shim (uses ICONS directly but `get_style_depends` includes legacy `font-awesome-4-shim` handle).

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-checkout.default', WooCheckout)` ([line 114-118](../../src/js/view/woo-checkout.js#L114))
- **Boot pattern:** Legacy `jQuery(window).on("elementor/frontend/init", …)` wrapper
- **Guard:** None — no `elementStatusCheck()` flag; relies on `$.blockUI` global being defined (jQuery blockUI vendor included via Elementor's checkout deps)
- **Global side-effect:** `$.blockUI.defaults.overlayCSS.cursor = "default"` — overrides default block-cursor for entire page
- **Body class:** `document.body.classList.add("eael-woo-checkout")` ([line 5](../../src/js/view/woo-checkout.js#L5)) — runs on every widget init; also added via PHP `body_class` filter on `is_checkout()`. **Redundant on cart pages, additive on non-cart contexts**.
- **`render_order_review_template()` flow:**
  - 200ms `setTimeout` delay before AJAX kicks off (allow WC's own updates to settle)
  - Adds `.processing` class + `$.blockUI` overlay (`opacity: 0.6`, `background: #fff`)
  - POSTs `action: woo_checkout_update_order_review` + `security: localize.nonce` + `orderReviewData` (from `data-checkout` attr) + `shippingData` (selected radio) + `post_data` (serialized checkout form)
  - On success: `replaceWith(response.order_review)` swaps DOM
  - **100-second unblock timer inside success callback** ([line 41](../../src/js/view/woo-checkout.js#L41)) — `setTimeout(unblock, 100000)`. If AJAX fails, blockUI never released; if successful but slow handler triggers re-block before this fires, may visually flicker.
- **Triggers for order-review refresh:**
  - `.woocommerce-remove-coupon` click
  - `form.checkout_coupon` submit
  - `change` on `select.shipping_method`, `input[name^="shipping_method"]`, `#ship-to-different-address input`, `.update_totals_on_change` selectors
  - `update_checkout` event on `document.body`
- **Field reorder block** ([line 121-174](../../src/js/view/woo-checkout.js#L121)) — guarded by `hasClass('checkout-reorder-enabled')`; listens for `country_to_state_changing` event WC fires when country changes; reads `data-checkout_ids` from wrapper; per type (billing/shipping), iterates field-key map, finds field DOM (`#${field_key}_field` or fallback `input[name='${field_key}']`), removes WC's spacing classes, adds the selected class, appends to stub container; 500ms `setTimeout` waits for WC's render to finish.
- **`removed_coupon_in_checkout` listener** — moves removed-coupon `.woocommerce-message` element back into the coupon form (multi-step / split layouts where message would otherwise appear in wrong tab).
- **`eael_update_checkout` qty handler** ([line 78-105](../../src/js/view/woo-checkout.js#L78)) — `change` on `.eael-checkout-cart-qty-input` debounced 300ms; reads `cart[<key>][qty]` notation from `name` attr; POSTs `action: eael_checkout_cart_qty_update` + `nonce: localize.nonce` + `cart_item_key` + `quantity`; triggers `update_checkout` on success (which fires `render_order_review_template()` via the bound listener).
- **`change_button_text()`** ([line 175-193](../../src/js/view/woo-checkout.js#L175)) — 500ms timeout, reads `data-button_texts.place_order`, `$('#place_order').text(...)` swap. Re-runs on 5 event types: `update_checkout`, `payment_method_selected`, `updated_checkout`, payment-label click, and on init.
- **No runtime state** — pure DOM-side, all settings carried in `data-*` attrs

## Common Issues

### Order review spinner stuck for ~100 seconds after coupon/shipping change

- **Likely cause:** [line 41 of woo-checkout.js](../../src/js/view/woo-checkout.js#L41) — `setTimeout(unblock, 100000)` inside the AJAX `success` callback. If response is slow but eventually arrives, the unblock fires 100 seconds later. Likely intent was `100` (ms) not `100000`.
- **Diagnose:** browser DevTools → network → check `admin-ajax.php?action=woo_checkout_update_order_review` response time
- **Fix:** known bug; the spinner unblock should be `100` ms, not `100000`. File a card.

### Order review never updates after qty change

- **Likely cause:** `eael_update_checkout` debounced handler fires AJAX `eael_checkout_cart_qty_update`. On success it `trigger('update_checkout')` which calls `render_order_review_template()`. If the qty AJAX nonce check fails (`localize.nonce` rotated by page cache), update silently fails.
- **Diagnose:** network panel — does the qty AJAX request return success? does subsequent order-review AJAX fire?
- **Fix:** clear page cache; verify `localize.nonce` is fresh

### Custom checkout fields from a third-party plugin don't appear in the reorder Repeater

- **Likely cause:** Repeater defaults seeded once when widget panel loads via `WC()->checkout()->get_checkout_fields()` ([line 742](../../includes/Elements/Woo_Checkout.php#L742)). If a plugin adds fields AFTER the widget was added to the page, the saved Repeater rows don't include them.
- **Diagnose:** check the panel's reorder Repeater — are the new fields visible as default rows?
- **Fix:** the panel shows a warning ("If you didn't find your custom checkout fields. Please remove this widget and again add this."). Remove + re-add the widget OR set `eael_new_field_appearing_position` to `before`/`after` to control where unlisted fields appear at render time.

### Place Order button text reverts to "Place order" after payment method change

- **Likely cause:** `change_button_text()` runs on 5 event types but uses `setTimeout(_, 500)` — WC's own re-render of the button can complete BEFORE the 500ms timeout fires, leaving the original text.
- **Diagnose:** rapid payment method switching; observe button text briefly switching to custom then reverting
- **Fix:** known race; reduce the 500ms timeout or implement a MutationObserver on `#place_order`

### Pro layout selected on Lite — checkout disappears entirely

- **Likely cause:** [line 3196-3198](../../includes/Elements/Woo_Checkout.php#L3196) — `if ($settings['ea_woo_checkout_layout'] in [multi-steps, split]) AND eael/pro_enabled is false) return`. Widget renders nothing.
- **Diagnose:** check panel — is a Pro layout selected? An inline warning should appear
- **Fix:** switch to `default` layout, or activate EA Pro

### Carrier Agents shipping field missing on multi-step layout

- **Likely cause:** [line 3187-3190](../../includes/Elements/Woo_Checkout.php#L3187) — `\Woo_Carrier_Agents` callback is forcibly removed from `woocommerce_checkout_order_review` and re-attached to `eael_wc_multistep_checkout_after_shipping`. If Pro doesn't fire that action (or if the Carrier Agents class signature changed), the field is gone.
- **Diagnose:** disable Woo_Checkout temporarily; field returns to its WC-native position
- **Fix:** Pro-only flow; check Pro's multistep layout fires `eael_wc_multistep_checkout_after_shipping`

### "The order totals have been updated" notice appears on first render

- **Likely cause:** `ea_checkout()` checks `$_POST['woocommerce_checkout_update_totals']` and adds a notice when set ([Woo_Checkout_Helper.php line 52-56](../../includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php#L52)). Cached pages may carry over POST state inappropriately.
- **Diagnose:** check if first render is happening after a non-JS POST submit
- **Fix:** intentional WC behaviour; ignore

### Field reorder DB writes flood the database on cached pages

- **Likely cause:** `update_post_meta` runs at [line 3235](../../includes/Elements/Woo_Checkout.php#L3235) on every render of a Woo_Checkout widget when reorder is enabled. Uncached page loads → DB write per request.
- **Diagnose:** Query Monitor → check for `_eael_checkout_fields_settings` updates per page load
- **Fix:** refactor to write only when Repeater changes; cards that wraps it under a `get_post_meta` comparison

## Known Limitations

- **`update_post_meta` runs on every frontend render** ([line 3235](../../includes/Elements/Woo_Checkout.php#L3235)) — DB write per page load when field reorder enabled. Caches mitigate but not eliminate. No comparison check — writes occur even when meta value is identical.
- **100-second unblock timer in `render_order_review_template()`** ([line 41 of woo-checkout.js](../../src/js/view/woo-checkout.js#L41)) — `setTimeout(unblock, 100000)`. Likely meant `100` ms; locks blockUI for 100 seconds before unblocking. Mitigated by AJAX completing before that and replacing the table (overlay attached to old DOM, swapped out).
- **`eael_woo_checkout_cart_fee` filter never echoed** ([Woo_Checkout_Helper.php line 520](../../includes/Template/Woocommerce/Checkout/Woo_Checkout_Helper.php#L520)) — `<?php apply_filters('eael_woo_checkout_cart_fee', $fee); ?>` — return value discarded. Either should be `echo apply_filters(...)` (filter intent) or `do_action(...)` (action intent). Bug.
- **Constructor side-effects NOT gated by widget detection** — unlike Woo_Cart, constructor side-effects fire for any `is_type_instance` Woo_Checkout regardless of whether the page actually has the widget. Cheaper because only filter/action registration (no `update_post_meta`), but still creates page-render contamination.
- **Static `$setting_data`** — multiple Woo_Checkout per page clobber each other (only one supported per page).
- **`woocommerce_checkout_fields` filter at priority 99999** — last in chain. If another plugin uses an even higher priority (`PHP_INT_MAX`), it wins. Brittle ordering assumption.
- **Repeater defaults frozen at panel-load** — `WC()->checkout()->get_checkout_fields()` only called once when panel renders. Plugin schema changes after that point require widget removal + re-add. Panel shows a warning RAW_HTML but no auto-sync.
- **Hardcoded Neve + Carrier Agents + Mondial Relay + Astra Pro + WC Subscriptions integrations** — all keyed by global class/function name lookups. Plugin renames break compat silently.
- **`get_style_depends() = ['font-awesome-4-shim']`** — deprecated handle in modern Elementor. Will warn or fail to enqueue in newer Elementor versions.
- **`eael_forcefully_remove_action()` walks `$wp_filter` globals** — strips callbacks by string lookup ([Helper.php line 477](../../includes/Traits/Helper.php#L477)). Closure callbacks won't match the string check; recent OOP plugins using closures bypass the removal.
- **JS `$.blockUI.defaults.overlayCSS.cursor = "default"`** — global mutation; any other blockUI usage on the page gets the same cursor override.
- **No nopriv rate limiting on AJAX endpoints** — both `wp_ajax_nopriv_woo_checkout_update_order_review` and `wp_ajax_nopriv_eael_checkout_cart_qty_update` are open to unauthenticated users (correct for guest checkout). Nonces protect but no per-IP throttle.
- **Multiple `did_action()` guards but no symmetric `add_action` removal** — `ea_woo_checkout_add_actions()` adds callbacks but never removes them at end of render. Same persistent-hook pattern as Woo_Cart.
- **`#place_order` text override uses 500ms setTimeout** — race-prone; WC re-renders the button on payment-method change and may render before / after the patch fires.
- **Field-reorder JS depends on `country_to_state_changing` event** — WC's own AJAX may not always fire this event in older versions or under certain plugin combinations; field reorder silently doesn't happen.
- **Inline `<style>` block emitted in render() body** ([line 3304-3308](../../includes/Elements/Woo_Checkout.php#L3304)) — overrides WC's blockUI spinner. Defeats CSP `style-src` policies that don't include `'unsafe-inline'`.
