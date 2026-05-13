# Woo Cart Widget

> Replaces WooCommerce's native cart shortcode (`[woocommerce_cart]`) with an EA-styled cart page. Two Lite layouts (`default` Repeater-driven table builder + `style-2` two-column thumbnail/details split); Pro adds three more layouts via the `eael/woo-cart/layout` filter. Class extends WC's own `WC_Shortcode_Cart` via a wrapper subclass to inherit shipping-calc + nonce + form-handling, then re-renders the cart contents through EA's own template trait. Bootstraps WC's cart object in the constructor when not already initialised.

**Class file:** [`includes/Elements/Woo_Cart.php`](../../includes/Elements/Woo_Cart.php)
**Slug:** `woo-cart` (widget id `eael-woo-cart`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/woocommerce-cart/>
**Pro-shared:** ✅ Yes — Pro adds 3 layouts (`style-3`, `style-4`, `style-5`) via the `eael/woo-cart/layout` filter. Pro detection via `eael/pro_enabled` filter; Lite shows an inline "Only Available in Pro Version!" warning when a Pro layout is selected and silently returns from `render()` ([line 2758](../../includes/Elements/Woo_Cart.php#L2758)).

---

## Overview

Woo Cart replaces the WooCommerce cart-page shortcode with EA's own rendering pipeline. Constructor side-effects fire before `render()`: bootstraps `WC()->cart` if null, swaps WC's `woocommerce_cart_totals` + `woocommerce_button_proceed_to_checkout` callbacks for EA's versions, and conditionally appends an `eael-woo-cart` body class on cart pages. The widget extends `WC_Shortcode_Cart` via a child class `Woo_Cart_Shortcode` to inherit WC's shipping-calc + nonce-verification + cart-validation logic before delegating per-layout rendering to the `Woo_Cart_Helper` trait.

Constructor gates all hook-swapping on a post-meta check (`_elementor_controls_usage` or `_eael_widget_elements`) — only fires when the page is known to contain a Woo_Cart widget. Settings flow from `render()` to template methods via a static class property (`Woo_Cart_Helper::$setting_data`). Two cart layouts ship in Lite (default = Repeater table builder, style-2 = two-column thumbnail/details split); Pro layouts are stubbed into the layout dropdown via the `eael/woo-cart/layout` filter but selecting one without Pro produces an empty render.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Layout `default` (Repeater column builder — pick column items by type) | ✅ | ✅ |
| Layout `style-2` (two-column: thumbnail-side / price+qty+subtotal-side) | ✅ | ✅ |
| Layouts `style-3` / `style-4` / `style-5` | ❌ — Pro-only options shown via `eael/woo-cart/layout` filter; selecting without Pro → empty render + inline warning | ✅ |
| Table Builder Repeater (column items: remove, thumbnail, name, description, price, quantity, subtotal) | ✅ — `default` layout only | ✅ |
| Auto cart update (debounced qty change → click hidden `update_cart`) | ✅ | ✅ |
| Custom cart-clear / update / checkout / coupon button text | ✅ | ✅ |
| Custom empty-cart message via `wc_empty_cart_message` filter override | ✅ | ✅ |
| Hide checkout button toggle | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present (Pro signal is the inline RAW_HTML warning instead) | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Cart.php`](../../includes/Elements/Woo_Cart.php) | PHP widget class (2769 lines) — constructor with hook swaps, controls, render dispatcher |
| [`includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php`](../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php) | Trait (759 lines) — `woo_cart_style_one()` + `woo_cart_style_two()`, `eael_woo_cart_totals()` (replaces WC's totals), `eael_cart_button_proceed_to_checkout()` (replaces WC's checkout button), `woo_cart_collaterals()`, `wc_empty_cart_message()` callback, static `$setting_data` |
| [`includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php`](../../includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php) | Class (91 lines) extending `\WC_Shortcode_Cart` — inherits shipping-calc + nonce handling; `output()` checks `WC()->cart->is_empty()`, dispatches per-layout; wraps in `.eael-woo-cart-wrapper` |
| [`src/css/view/woo-cart.scss`](../../src/css/view/woo-cart.scss) | Source styles (1250 lines) — per-layout selectors, qty +/- buttons, table headers, totals table, coupon form |
| [`src/js/view/woo-cart.js`](../../src/js/view/woo-cart.js) | Frontend logic (49 lines) — injects `.eael-cart-qty-minus` / `-plus` buttons around qty inputs; debounced auto-update on qty change; re-binds on `updated_wc_div` WC event |
| [`config.php`](../../config.php#L1071) entry `'woo-cart'` | Asset declaration — single CSS + single JS, no vendor libs |
| `assets/front-end/css/view/woo-cart.min.css` / `.js` | Built outputs (do not edit) |

No vendor libraries. No shared assets. Subclasses WC's `WC_Shortcode_Cart` so WC core handles the form-action + nonce path.

## Architecture

- **Constructor swaps WC default callbacks before render** ([line 25-69](../../includes/Elements/Woo_Cart.php#L25)) — `remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10)` then `add_action('woocommerce_cart_collaterals', [$this, 'eael_woo_cart_totals'], 10)`; same pattern for `woocommerce_proceed_to_checkout` → `woocommerce_button_proceed_to_checkout` (priority 20). The swaps **persist across the entire request** — they are not removed after render. Any other code rendering the cart in the same request (multi-widget page, secondary cart shortcode) gets EA's output instead of WC's. ⚠️ Constructor-level swaps fire per-instance; multiple Woo_Cart widgets would re-register identical callbacks (PHP de-dupes by method handle).
- **Constructor gated by post-meta widget detection** ([line 34-42](../../includes/Elements/Woo_Cart.php#L34)) — reads `_elementor_controls_usage` (Elementor 3.x usage map) or falls back to `_eael_widget_elements` (legacy EA usage map). Widget keys probed: `eael-woo-cart` then `woo-cart`. If neither post-meta indicates this widget is present, **no constructor side effects fire** — prevents bloat on pages that don't use this widget. ⚠️ Theme builder templates (Single, Archive) and saved templates may not populate these meta keys reliably — known fragile lookup.
- **`WC()->cart` lazy-loaded if null** ([line 44-48](../../includes/Elements/Woo_Cart.php#L44)) — direct `include_once WC_ABSPATH . 'includes/wc-cart-functions.php'` + `wc_load_cart()` calls. Needed when widget renders before WC initialises its cart (e.g. AJAX-rendered Elementor template requests).
- **Subclasses `WC_Shortcode_Cart`** — `Woo_Cart_Shortcode extends \WC_Shortcode_Cart` ([Woo_Cart_Shortcode.php line 10](../../includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php#L10)) inherits the static `calculate_shipping()` method + WC's shipping-calc-nonce path. The class is conditionally defined (`if ( class_exists( '\WC_Shortcode_Cart' ) )`) — when WC inactive, the class doesn't exist and `ea_cart_render()` would fatal. Render guards via `class_exists('woocommerce')` upstream at [line 2742](../../includes/Elements/Woo_Cart.php#L2742).
- **Settings flow via static state** — `Woo_Cart_Helper::$setting_data` is a public static array. `ea_woo_cart_add_actions($settings)` writes to it, downstream template methods (`woo_cart_style_one`, `eael_woo_cart_totals`, `eael_cart_button_proceed_to_checkout`, `wc_empty_cart_message`) read via `ea_get_woo_cart_settings()`. ⚠️ Multiple Woo_Cart widgets on the same page write to the **same static slot** — last-write-wins; first widget's settings are clobbered by the second's.
- **Layout dispatcher in `Woo_Cart_Shortcode::output()`** ([line 75-82](../../includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php#L75)) — switches on `$settings['ea_woo_cart_layout']`; `default` → `woo_cart_style_one()`, `style-2` → `woo_cart_style_two()`. Pro layouts (`style-3/4/5`) are not in this switch — Pro adds its own cases via override. Lite-side, an unknown layout falls through the switch and renders **nothing** between `do_action('woocommerce_before_cart')` and `do_action('woocommerce_after_cart')` — the empty cart wrapper still renders.
- **Pro gating** — `eael_woo_cart_pro_enable_warning` RAW_HTML control shown when `! eael/pro_enabled` and layout in `[style-3, style-4, style-5]` ([line 169-180](../../includes/Elements/Woo_Cart.php#L169)); `render()` returns early when same condition matches ([line 2757-2761](../../includes/Elements/Woo_Cart.php#L2757)). Mechanism differs from standard `eael_section_pro` upsell (see [`_patterns.md`](_patterns.md)) — no separate panel, just an inline warning + silent render.
- **Body class manipulation** — on `is_cart()` pages, `body_class` filter callback `add_cart_body_class` adds `eael-woo-cart` to `<body>` class list ([line 51-53](../../includes/Elements/Woo_Cart.php#L51)); on non-cart pages (theme builder, custom landing), inline `<script>document.body.classList.add("eael-woo-cart");</script>` is emitted at end of render ([line 2765](../../includes/Elements/Woo_Cart.php#L2765)) to scope styles to `.eael-woo-cart` body class.
- **Cross-sell display force-removed** ([line 59](../../includes/Elements/Woo_Cart.php#L59)) — `add_action('eael_woocommerce_before_cart_collaterals', [$this, 'remove_woocommerce_cross_sell_display'])` and inside that callback `remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display')` ([Woo_Cart_Helper.php line 398](../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php#L398)). Unconditional — no panel toggle to re-enable cross-sells. To restore, user must `add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display')` elsewhere.

## Render Output

```html
<div class="eael-woo-cart-wrapper {empty-class} eael-woo-{default|style-2|style-3|style-4|style-5} {auto-update-class} {style-two-content-classes}">

  <!-- WC's woocommerce_before_cart hook fires here (third-party content) -->

  <!-- Layout 'default' (Repeater table builder): -->
  <form class="woocommerce-cart-form eael-woo-cart-form woocommerce" method="post">
    [?] <div class="eael-cart-clear-btn"><a class="button">Clear Cart</a></div>
    <div class="eael-woo-cart-table-warp">
      <div class="shop_table cart woocommerce-cart-form__contents eael-woo-cart-table">
        <div class="eael-wc-table-header">
          <div class="eael-wct-tr">
            <!-- One header cell per Repeater row, classed by column_type: -->
            <div class="eael-wct-th product-{remove|thumbnail|name|description|price|quantity|subtotal} elementor-repeater-item-<id>">
              {column_heading_title or remove icon}
            </div>
            …
          </div>
        </div>
        <div class="eael-wc-table-body">
          <!-- One row per WC()->cart->get_cart() item, filtered through WC hooks: -->
          <div class="eael-wct-tr woocommerce-cart-form__cart-item {woocommerce_cart_item_class}">
            <!-- Per-column cells in Repeater order -->
          </div>
        </div>
      </div>
    </div>
    [?] <div class="cart-collaterals"> {coupon form, cart totals, etc.} </div>
  </form>

  <!-- Layout 'style-2' (two-column thumbnail / details split): -->
  <form class="…">
    <div class="shop_table cart eael-woo-cart-table eael-woo-style-2">
      <div class="eael-woo-cart-thead">
        <div class="eael-woo-cart-tr">
          [?] <div class="eael-woo-cart-tr-left">       <!-- thumbnail+name -->
                <div class="eael-woo-cart-td product-thumbnail">…</div>
              </div>
          [?] <div class="eael-woo-cart-tr-right">      <!-- price+qty+subtotal+remove -->
                [?] <div class="eael-woo-cart-td product-price">…</div>
                [?] <div class="eael-woo-cart-td product-quantity">…</div>
                [?] <div class="eael-woo-cart-td product-subtotal">…</div>
                [?] <div class="eael-woo-cart-td product-remove"></div>
              </div>
        </div>
      </div>
      <div class="eael-woo-cart-tbody">…</div>
    </div>
  </form>

  <!-- WC's woocommerce_cart_collaterals action fires here -->
  <!-- EA's eael_woo_cart_totals callback renders the totals table + checkout button: -->
  <div class="cart_totals {calculated_shipping}">
    <table class="shop_table shop_table_responsive">
      [?] <tr class="cart-subtotal">    <!-- toggled by eael_woo_cart_components_cart_totals_subtotal -->
      [?] <tr class="cart-discount">    <!-- per coupon, toggled by …_coupon -->
      [?] <tr class="shipping">         <!-- toggled by …_shipping -->
      [?] <tr class="fee">              <!-- per fee, toggled by …_fees -->
      [?] <tr class="tax-rate|tax-total">  <!-- toggled by …_tax -->
      [?] <tr class="order-total">      <!-- toggled by …_total -->
    </table>
    <div class="wc-proceed-to-checkout">
      <a href="<wc_get_checkout_url>" class="checkout-button button alt wc-forward">
        {Custom checkout button text}
      </a>
    </div>
  </div>
</div>

<!-- Empty cart variant: -->
<div class="eael-woo-cart-wrapper eael-woo-cart-empty …">
  <!-- WC's cart-empty.php template; wc_empty_cart_message filter overrides text -->
</div>

<!-- Footer (non-cart pages only): -->
<script>document.body.classList.add("eael-woo-cart");</script>
```

Notes:

- `.eael-auto-update` class on wrapper drives JS click-debounce-and-fire-update-cart on qty change.
- Qty `+/-` buttons are injected into `.product-quantity div.quantity` by JS — initial server-render omits them.
- Each `.eael-wct-th` header cell carries `elementor-repeater-item-<id>` so per-column controls target individual columns via `.elementor-repeater-item-{id}` selector.
- `style-2` `eael-woo-cart-tr-left` / `-tr-right` widths set via the `eael_woo_cart_table_components_left_side_width` slider (default 45% / 55%).

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Cart.php#L121) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `ea_woo_cart_layout` | SELECT (filterable) | `default` | Content → General Settings | Layout dispatcher; options extended by Pro via `eael/woo-cart/layout` filter |
| `eael_woo_cart_pro_enable_warning` | RAW_HTML | — | Content → General Settings | Pro upsell inline warning when layout selected requires Pro |
| `table_items` (Repeater) | REPEATER | 6 default rows | Content → Table Builder | Column order + types for `default` layout — each row is one table column |
| `column_type` (per-item) | SELECT | `name` | Repeater item | One of: `remove`, `thumbnail`, `name`, `description`, `price`, `quantity`, `subtotal` |
| `column_heading_title` (per-item) | TEXT (AI active) | varies | Repeater item | Header text per column |
| `item_remove_icon` (per-item) | ICONS | `fas fa-times` | Repeater item | Remove-icon picker; visible only when `column_type == 'remove'` |
| `eael_woo_cart_table_components_thumbnail` / `_name` / `_sku` / `_price` / `_qty` / `_subtotal` / `_remove` | SWITCHER × 7 | mostly `yes` | Content → Table Components | Per-column show/hide for **`style-2` layout only** |
| `eael_woo_cart_table_components_*_title` | TEXT (AI active) × multiple | varies | Content → Table Components | Header titles per column for style-2 |
| `eael_woo_cart_table_components_left_side_width` | SLIDER (%/px responsive) | `45%` | Content → Table Components | Width split between thumbnail/details columns in style-2 |
| `eael_woo_cart_table_components_*_alignment` / `_width` | various | — | Content → Table Components | Per-column styling for style-2 |
| `eael_woo_cart_auto_cart_update` | SWITCHER | `yes` | Content → Cart Components | Adds `.eael-auto-update` to wrapper; JS triggers debounced update-cart click on qty change |
| `eael_woo_cart_components_cart_clear_button` / `_text` | SWITCHER + TEXT | `hide` / `"Clear Cart"` | Content → Cart Components | Show "Clear Cart" link with `?empty_cart=yes` URL param |
| `eael_woo_cart_components_cart_update_button` / `_text` | SWITCHER + TEXT | `yes` / `"Update Cart"` | Content → Cart Components | WC's native Update Cart button (text only override) |
| `eael_woo_cart_components_cart_coupon` / `_button_text` / `_placeholder` | SWITCHER + TEXT × 2 | `yes` / `"Apply Coupon"` / `"Coupon code"` | Content → Cart Components | Show coupon form + custom button/placeholder text |
| `eael_woo_cart_components_continue_shopping` / `_text` / `_icon` | SWITCHER + TEXT + ICONS | `yes` / — / icon | Content → Cart Components | "Continue Shopping" link rendered in collaterals |
| `eael_woo_cart_components_cart_totals` | SWITCHER | `yes` | Content → Cart Components | Toggle entire cart-totals block render |
| `eael_woo_cart_components_cart_totals_subtotal` / `_coupon` / `_shipping` / `_fees` / `_tax` / `_total` | SWITCHER × 6 | various | Content → Cart Components | Per-row toggles inside totals table |
| `eael_woo_cart_components_cart_checkout_button_text` | TEXT | `"Proceed to checkout"` | Content → Cart Components | Custom text via `eael_woo_cart_checkout_button_text` filter; renders into EA's replacement `<a class="checkout-button">` |
| `eael_woo_cart_hide_checkout_btn` | SWITCHER | empty | Content → Cart Components | Hide checkout button entirely (overrides custom text path) |
| `eael_woo_cart_components_empty_cart_text` | TEXT | empty | Content → Cart Components | Filtered through `wc_empty_cart_message`; replaces WC default "Your cart is currently empty." |
| Style → Various sections | — | — | Style tab | Per-region typography / color / spacing / border / box-shadow — 8 style sections (1274–2734 in PHP) |

## Conditional Dependencies

```text
# Layout gating
table_items Repeater section          → visible when ea_woo_cart_layout == 'default'
Table Components section              → visible when ea_woo_cart_layout == 'style-2'
eael_woo_cart_pro_enable_warning      → visible when ea_woo_cart_layout in [style-3, style-4, style-5]
                                        AND eael/pro_enabled filter returns false

# Repeater per-item
item_remove_icon                      → visible when column_type == 'remove'
column_heading_title                  → visible when column_type != 'remove' (some variants)

# Table Components (style-2)
eael_woo_cart_table_components_name           → visible when …_thumbnail == 'yes'
eael_woo_cart_table_components_*_title        → visible when matching switcher == 'yes'

# Cart Components
eael_woo_cart_components_cart_clear_button_text   → visible when …_clear_button == 'yes'
eael_woo_cart_components_cart_update_button_text  → visible when …_update_button == 'yes'
eael_woo_cart_components_cart_coupon_*            → visible when …_cart_coupon == 'yes'
eael_woo_cart_components_continue_shopping_*      → visible when …_continue_shopping == 'yes'
eael_woo_cart_components_cart_totals_*            → visible when …_cart_totals == 'yes'
eael_woo_cart_components_cart_checkout_button_text → visible when eael_woo_cart_hide_checkout_btn != 'yes'

# Frontend gate (runtime)
Entire output                         → empty when WooCommerce inactive
Empty-cart variant                    → when WC()->cart->is_empty() returns true
Pro-layout silent fail                → when layout in [style-3,4,5] AND eael/pro_enabled is false
```

No `eael_section_pro` upsell panel; signal is the inline RAW_HTML warning + silent render.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/woo-cart/layout` | filter (emitted) | `(array $options)` | Pro / 3rd-party can add layout dropdown options ([line 162](../../includes/Elements/Woo_Cart.php#L162)) |
| `eael_woo_cart_clear_button_text` | filter (emitted) | `(string $text)` | Override Clear Cart button label |
| `eael_woo_cart_table_{column_type}_title` | filter (emitted, dynamic) | `(string $title)` | Override per-column header title (`name`, `price`, `quantity`, `subtotal`, `thumbnail` — built into filter name) |
| `eael_woo_cart_item_description` | filter (emitted) | `(string $description, array $cart_item, string $cart_item_key, int $word_limit)` | Override per-row product description |
| `eael_woo_cart_totals_subtotal_label` | filter (emitted) | `(string $label)` | Override "Subtotal" text in totals table |
| `eael_woo_cart_totals_shipping_label` | filter (emitted) | `(string $label)` | Override "Shipping" text in totals table |
| `eael_woo_cart_totals_total_label` | filter (emitted) | `(string $label)` | Override "Total" text in totals table |
| `eael_woo_cart_checkout_button_text` | filter (emitted) | `(string $text)` | Override checkout button text in EA's `eael_cart_button_proceed_to_checkout` replacement |
| `eael_woocommerce_before_cart_collaterals` | action (emitted) | — | EA-prefixed pre-collaterals action; fires `remove_woocommerce_cross_sell_display` callback |
| `eael/pro_enabled` | filter (consumed) | `(bool $enabled)` | Inline Pro gate — layout select + render-time check |
| `body_class` | filter (consumed) | `(array $classes)` via callback `add_cart_body_class` | Adds `eael-woo-cart` class on `is_cart()` pages |
| `wc_empty_cart_message` | filter (consumed) | `(string $message)` via callback `wc_empty_cart_message` | Override WC default empty-cart text with `eael_woo_cart_components_empty_cart_text` setting |
| WC-native: `woocommerce_cart_collaterals` | action (consumed) | — | EA `remove_action`'s `woocommerce_cart_totals` then `add_action`'s `eael_woo_cart_totals` at priority 10 |
| WC-native: `woocommerce_proceed_to_checkout` | action (consumed) | — | EA `remove_action`'s `woocommerce_button_proceed_to_checkout` then `add_action`'s `eael_cart_button_proceed_to_checkout` at priority 20 |
| WC-native: `woocommerce_cart_collaterals` → `woocommerce_cross_sell_display` | action (consumed) | — | Unconditionally `remove_action`'d via `remove_woocommerce_cross_sell_display` callback |
| Many WC-native hooks fired inside template trait | various | various | `woocommerce_before_cart`, `_after_cart`, `_before_cart_table`, `_after_cart_table`, `_before_cart_contents`, `_after_cart_contents`, `_cart_contents`, `_before_cart_totals`, `_after_cart_totals`, `_cart_totals_before_shipping`, `_after_shipping`, `_cart_totals_before_order_total`, `_after_order_total`, `_after_cart_item_name`, `_proceed_to_checkout` — preserves WC hook contract for theme/plugin compatibility |

No shared patterns from [`_patterns.md`](_patterns.md) apply — no Liquid Glass, no FA4 shim, no WPML media, no `has_pro` JS handoff, no standard `eael_section_pro` upsell. The widget's Pro gating is bespoke (inline warning + silent render).

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-cart.default', WooCart)` ([line 49 of woo-cart.js](../../src/js/view/woo-cart.js#L49))
- **Boot pattern:** Uses legacy `jQuery(window).on("elementor/frontend/init", …)` wrapper (not the newer `eael.hooks.addAction("init","ea",…)` pattern)
- **Guard:** `if (eael.elementStatusCheck('eaelWooCart')) return false;` — prevents re-init in editor preview
- **Vendor deps:** jQuery + WC's `updated_wc_div` event + `ea.debounce` (EA global)
- **Reads on init:** `.eael-woo-cart-wrapper` class for `.eael-auto-update` detection
- **Init action:** `qtyIncDecButton($scope)` prepends `<span class="eael-cart-qty-minus" data-action-type="minus">-</span>` + appends `<span class="eael-cart-qty-plus" data-action-type="plus">+</span>` around `.product-quantity div.quantity > input[type=number]`
- **Click delegation (document-scoped, not scope-scoped):** `$($scope, document).on('click', 'div.quantity .eael-cart-qty-minus, .eael-cart-qty-plus', …)` — ⚠️ buggy selector — `$($scope, document)` passes `$scope` as selector + `document` as context, not as a union. Effectively delegates from `document`, so any cart on the page is captured.
- **Min/max validation:** reads `min` / `max` attrs from qty input; min defaults to `0` if missing/empty (allows decrement to 0); max condition checks if `max` attr is set, allows increment when current < max
- **Auto-update branch:** when wrapper has `.eael-auto-update`, qty-input `change` event debounced 300ms triggers `.update_cart` button click — re-fires WC's AJAX cart update path
- **`updated_wc_div` re-binding:** `jQuery(document).on('updated_wc_div', qtyIncDecButton)` ([line 43](../../src/js/view/woo-cart.js#L43)) — after WC's AJAX cart update replaces the DOM, EA re-injects qty +/- buttons. Passes the event object as `$scope` and the function handles via `$scope.type === 'updated_wc_div' ? document : $scope`.
- **No runtime state** — buttons are DOM-scoped; debounce timer is closure-scoped per click handler

## Common Issues

### Cart shows EA's totals box even on a Cart page that doesn't have the Woo_Cart widget

- **Likely cause:** Constructor's `remove_action(woocommerce_cart_totals) + add_action(eael_woo_cart_totals)` swap persists for the entire request. Once any Woo_Cart instance is constructed on the page, all subsequent cart rendering paths in the same request use EA's callbacks.
- **Diagnose:** check `_elementor_controls_usage` / `_eael_widget_elements` post-meta for the rendered page — does it include `eael-woo-cart`?
- **Fix:** by design when the meta lookup matches. If the page meta is incorrect (stale or out-of-sync after editor save), regenerate by re-saving the page in Elementor.

### Two Woo_Cart widgets on the same page show identical settings

- **Likely cause:** `Woo_Cart_Helper::$setting_data` is **static** — the second widget's `ea_woo_cart_add_actions()` overwrites the first widget's stored settings. Both render paths read the same static slot.
- **Diagnose:** place two instances with different layout/options; both render the second's settings
- **Fix:** known limitation — only one Woo_Cart per page is supported. Workaround: use one Woo_Cart instance + style with CSS

### Pro layout selected on Lite — widget renders nothing

- **Likely cause:** [line 2757-2761](../../includes/Elements/Woo_Cart.php#L2757) — when `ea_woo_cart_layout` in `[style-3, style-4, style-5]` AND `eael/pro_enabled` is false, `render()` returns early with no output
- **Diagnose:** check the selected layout in the panel; if Pro-only, switch to `default` or `style-2`
- **Fix:** activate EA Pro, or change layout

### `updated_wc_div` doesn't re-attach qty +/- buttons in some themes

- **Likely cause:** Theme/plugin intercepts the WC AJAX flow and emits its own `updated_wc_div` event without DOM-replacement of `.product-quantity`, OR `update_cart` button click is debounced too long and fires after a second user action
- **Diagnose:** browser console — watch for `updated_wc_div` events firing; inspect `.product-quantity > .quantity` — does the qty input exist after AJAX update?
- **Fix:** the 300ms `ea.debounce` may need tuning per-theme; or implement a MutationObserver fallback. Open a card if persistent.

### Coupon form has no panel-side toggle for "Remove coupon" link styling

- **Likely cause:** `eael_woo_cart_components_cart_coupon` only governs the input + button. WC's "Remove" link inside applied coupons inherits theme CSS
- **Diagnose:** apply a coupon; observe the "Remove" link visual
- **Fix:** custom CSS targeting `.eael-woo-cart-wrapper .cart-discount a.remove`

### Cross-sells disappear from the cart page

- **Likely cause:** EA force-removes `woocommerce_cross_sell_display` from `woocommerce_cart_collaterals` ([Woo_Cart_Helper.php line 398](../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php#L398)). Intentional but undocumented in panel
- **Diagnose:** disable Woo_Cart widget temporarily; cross-sells return
- **Fix:** re-add via `add_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display')` in theme `functions.php` at any priority

### Empty cart message override doesn't apply

- **Likely cause:** `wc_empty_cart_message` filter callback runs only if `Woo_Cart_Helper::$setting_data` has been populated. If a third-party plugin calls `wc_get_template('cart/cart-empty.php')` outside the widget's render path, EA's filter override doesn't fire because settings aren't set
- **Diagnose:** view source of the empty cart page — is the default message visible?
- **Fix:** confirm Woo_Cart widget renders on the cart page; the override only works inside EA's render context

## Known Limitations

- **Static `$setting_data` state** — multiple Woo_Cart widgets per page clobber each other ([Woo_Cart_Helper.php line 13](../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php#L13)). Refactor target: per-instance setting storage keyed by widget id.
- **Constructor hook swaps persist** — `remove_action` + `add_action` calls in the constructor are not reversed after render. Any code on the same request that re-renders the cart (or relies on `woocommerce_cart_totals` / `woocommerce_button_proceed_to_checkout` callbacks) gets EA's behaviour. Fragile if a page combines EA cart widget with another cart-rendering plugin.
- **Post-meta widget detection brittle** — `_elementor_controls_usage` (Elementor 3.x) or `_eael_widget_elements` (legacy fallback). Theme builder templates, Loop Grid items, and saved templates may not populate these keys reliably. Result: constructor side-effects skipped, widget renders but WC's default totals callbacks fire instead of EA's.
- **`$($scope, document).on(...)` jQuery selector bug** ([line 10 of woo-cart.js](../../src/js/view/woo-cart.js#L10)) — `$(selector, context)` passes `$scope` as a selector and `document` as the context. Since `$scope` is a jQuery object, this effectively becomes `$(document)` (jQuery degrades gracefully). The delegation captures from `document` scope, not the widget scope — meaning any qty input on the page triggers the handler, not just this widget's. Wider scope than intended.
- **`Woo_Cart_Shortcode` class conditionally defined** — wrapped in `if ( class_exists( '\WC_Shortcode_Cart' ) )` ([Woo_Cart_Shortcode.php line 9](../../includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php#L9)). If WC loads after this file, the class never exists and `ea_cart_render()` fatals. Upstream `class_exists('woocommerce')` guard in render() prevents this in practice; load order issues would manifest as silent template autoload failures.
- **Cross-sell display force-removed** with no panel toggle — unconditional `remove_action` of `woocommerce_cross_sell_display` ([Woo_Cart_Helper.php line 398](../../includes/Template/Woocommerce/Cart/Woo_Cart_Helper.php#L398)). Users who want cross-sells must restore via custom code; no UI signal.
- **Pro layouts (`style-3/4/5`) listed in panel but silently fail on Lite** — selecting one shows an inline warning but render returns nothing. User who dismissed the warning sees a blank cart page; no fallback to `default` layout.
- **Empty cart wrapper has malformed class attr** ([Woo_Cart_Shortcode.php line 50](../../includes/Template/Woocommerce/Cart/Woo_Cart_Shortcode.php#L50)) — `esc_attr( printf( '%s %s', "eael-woo-{$layout}", $auto_update ) )` — `printf` echoes directly + returns int (char count). `esc_attr(int)` becomes the count. Classes leak directly into HTML unescaped, and `class="3"` (or similar) ends up on the wrapper. Bug.
- **No `elementor_section_pro` upsell** — Pro detection is via inline RAW_HTML warning at top of General Settings + silent render. No standard upsell panel. Users on Lite see Pro options in the layout dropdown but no clear path to upgrade.
- **i18n hardcoded in Repeater item defaults** — Repeater default values include English strings (e.g. column titles) that ship in `.pot` files. Localised sites get translatable defaults but not auto-replacement; users must re-edit per language.
- **`woocommerce_after_cart_table` action fires inside `eael-woo-cart-table-warp` div, not after** — name suggests after the table; actually fires inside the wrapper div. Theme/plugin listeners expecting outer placement may render wrongly.
- **JS qty -/+ buttons inserted via `prepend`/`append` on `div.quantity`** — runs on every `frontend/element_ready` fire. WC's `updated_wc_div` event re-binds them, but if WC re-renders without firing `updated_wc_div`, buttons disappear from new rows.
