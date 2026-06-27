# Woo Add To Cart Widget

> Renders WooCommerce's `woocommerce_template_single_add_to_cart()` on any page (not just the single-product template) — quantity input + add-to-cart button styled via EA controls, with optional AJAX submission for simple / variable / grouped product types. Editor preview emits a hardcoded mockup of each product-type layout (4 variants); the real WC template renders only on the frontend. Lite-only widget — Pro doesn't extend it.

**Class file:** [`includes/Elements/Woo_Add_To_Cart.php`](../../includes/Elements/Woo_Add_To_Cart.php)
**Slug:** `woo-add-to-cart` (widget id `eael-woo-add-to-cart`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-add-to-cart/>
**Pro-shared:** ❌ No — Lite-only widget. Pro doesn't extend it; no `eael/pro_enabled` gates; no `eael_section_pro` upsell panel present.

---

## Overview

Woo Add To Cart wraps WooCommerce's native `woocommerce_template_single_add_to_cart()` function in an EA-styled container so the add-to-cart button can be placed inside any layout (Elementor archive theme builder pages, custom landing pages, Loop Grid items). The widget filters `woocommerce_product_single_add_to_cart_text` to inject the panel's custom button text + icon, then renders WC's own template. On the editor canvas, it emits a hardcoded mockup of each of the 4 product types (simple / external / grouped / variable) so the styling preview works without a real product context.

The AJAX add-to-cart feature is the only frontend JS — when `eael_ajax_add_to_cart` is `yes`, the wrapper carries `data-eael-ajax-add-to-cart="yes"` + product metadata, and a click delegation on `.single_add_to_cart_button` hijacks the form submit, POSTs to the `eael_ajax_add_to_cart` AJAX endpoint, then triggers WooCommerce's `wc_fragment_refresh` event so the mini-cart updates without a page reload.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All controls (button / quantity / variations / icon styling) | ✅ | ✅ |
| AJAX add-to-cart (simple / variable / grouped) | ✅ | ✅ |
| 4 product-type editor mockups (simple / external / grouped / variable) | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |

Pro ships no extension of this widget — no hooks, no overrides, no separate Pro widget that supersedes it. Anyone needing more layout variants ships them via theme builder + Pro's `Woo_Product_Carousel` / `Product_Grid` widgets instead.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Add_To_Cart.php`](../../includes/Elements/Woo_Add_To_Cart.php) | PHP widget class (1165 lines) — controls, render, button-text filter callback, quantity-fields filter |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L1998) | `Helper::get_product()` — falls back to `wc_get_product()` with no arg (uses global `$product` from page context) when no ID passed; resolves `product_variation` post type via `get_product_variation()` |
| [`includes/Traits/Ajax_Handler.php`](../../includes/Traits/Ajax_Handler.php#L1614) | `eael_ajax_add_to_cart()` — AJAX handler for simple / variable / grouped types; nonce-protected; returns `fragments` + `cart_hash` + WC notices HTML |
| [`src/css/view/woo-add-to-cart.scss`](../../src/css/view/woo-add-to-cart.scss) | Source styles (300 lines) — quantity input, button hover states, variation selectors, AJAX loader |
| [`src/js/view/woo-add-to-cart.js`](../../src/js/view/woo-add-to-cart.js) | Frontend logic (122 lines) — AJAX submit handler only; pure passthrough when AJAX disabled |
| [`config.php`](../../config.php#L781) entry `'woo-add-to-cart'` | Asset declaration — single CSS + single JS, no vendor libs |
| `assets/front-end/css/view/woo-add-to-cart.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/woo-add-to-cart.min.js` | Built output (do not edit) |

No vendor libraries. No shared assets. The leanest WC widget in EA Lite.

## Architecture

- **Editor preview is a hardcoded mockup, not the WC template** — `render()` branches on `Plugin::$instance->editor->is_edit_mode() || get_post_type() === 'templately_library'` ([line 1055](../../includes/Elements/Woo_Add_To_Cart.php#L1055)) and emits a different HTML structure per product type from the `add_to_cart_product_type` control (simple / external / grouped / variable). Grouped mockup contains three hardcoded variations ("Hoodie with Pocket" at $45, "Beanie with Logo" at $18, second "Hoodie with Pocket" at $35) — these strings ship in i18n catalogs as literal product names. Variable mockup hardcodes a "Color" attribute with Red/Green/Blue options. ⚠️ Visual drift risk: mockup markup and WC's real template don't share class names — `.eael-add-to-cart-wrapper` (mockup) vs `form.cart` (WC) — so styling needs separate selectors to cover both.
- **Frontend render delegates to WC's `woocommerce_template_single_add_to_cart()`** ([line 1155](../../includes/Elements/Woo_Add_To_Cart.php#L1155)) — this is WC's built-in dispatcher that loads the right template part per product type (`add-to-cart/simple.php`, `variable.php`, `grouped.php`, `external.php`). EA doesn't ship its own templates for the frontend.
- **Custom button text injected via `woocommerce_product_single_add_to_cart_text` filter** — added immediately before `woocommerce_template_single_add_to_cart()` ([line 1154](../../includes/Elements/Woo_Add_To_Cart.php#L1154)), the callback `eael_add_to_cart_text_single` returns `$settings['add_to_cart_text']` for all product types except external (where it returns the original WC button text — typically "Buy product"). ⚠️ The filter is **never removed** — leaks into subsequent widgets on the same page (`add_filter()` registered without matching `remove_filter()`).
- **Cart icon injected as a side effect of the text filter** — `eael_add_to_cart_text_single` callback also calls `$this->eael_add_to_cart_icon($settings)` which calls `Icons_Manager::render_icon()` — but `render_icon()` **echoes** rather than returns, so the icon HTML is emitted **before** the button text in the document flow (not inside the button). This is a known quirk that depends on filter ordering.
- **Quantity-show toggle uses `woocommerce_is_sold_individually` filter** — when `add_to_cart_show_quantity` is empty, `eael_show_quantity_fields` filter callback is registered to return `true`, which tells WC to render the product as "sold individually" (no quantity input). ⚠️ Side effect: changes WC's reported sold-individually flag for any downstream WC code that calls `$product->is_sold_individually()` on the same request.
- **`Helper::get_product()` fallback to global `$product`** — when no product context exists (e.g. placing the widget on a non-product page), `wc_get_product()` returns the loop's global `$product`. On non-product pages this is `false` and `render()` returns silently ([line 1141](../../includes/Elements/Woo_Add_To_Cart.php#L1141)). No editor warning, no fallback UI — the widget silently disappears.
- **`woocommerce-notices-wrapper` div is empty on initial render** — only populated by AJAX response (`$notices.html(response.data.notices)`). For non-AJAX path, the wrapper sits empty and WC renders its own notice infrastructure elsewhere.
- **No `eael_section_pro` upsell** — unlike most Lite widgets, this one has no "Go Pro" panel. The widget is fully featured at Lite tier.

## Render Output

### Frontend (real product context)

```html
<div class="eael-single-product-add-to-cart">
  <div class="eael-add-to-cart-wrapper eael-product-{simple|variable|grouped|external}"
       [?] data-eael-ajax-add-to-cart="yes"
       [?] data-product-id="<id>"
       [?] data-product-type="<simple|variable|grouped|external>"
       [?] data-nonce="<eael-ajax-add-to-cart nonce>">

    <div class="woocommerce-notices-wrapper"></div>

    <!-- WC's woocommerce_template_single_add_to_cart() output: -->
    <form class="cart" method="post">
      <!-- Per-product-type markup from WC's templates: -->
      [?] <div class="quantity"><input type="number" class="qty" value="1"></div>
      [?] <table class="variations"> … </table>          <!-- variable product -->
      [?] <table class="group_table"> … </table>         <!-- grouped product -->
      <button type="submit" class="single_add_to_cart_button button alt">
        <!-- Cart icon (when add_to_cart_icon_show=yes): -->
        <i class="fa fa-cart-plus" aria-hidden="true"></i>
        {Custom button text from add_to_cart_text setting}
      </button>
    </form>
  </div>
</div>
```

### Editor mockup (no product context)

Different inner markup per `add_to_cart_product_type` control:

```html
<div class="eael-single-product-add-to-cart">
  <div class="eael-add-to-cart-wrapper eael-product-<simple|variable|grouped|external>_product">
    <!-- simple_product: -->
    [?] <input type="number" class="quantity-input" value="1" min="1">
    <button class="eael-add-to-cart">
      [?] <span class="cart-icon"><i class="fa fa-cart-plus"></i></span>
      <span class="button-text">{Add to cart}</span>
    </button>

    <!-- variable_product: dropdown + qty + button -->
    [?] <div class="eael-variable-product-edit">
      <div class="eael-variable-product">
        <div class="variable-label">Color</div>
        <select class="custom-select-option"><option>…</option></select>
      </div>
      <input class="quantity-input" …>
      <button class="eael-add-to-cart">…</button>
    </div>

    <!-- grouped_product: 3 hardcoded variations -->
    [?] <div class="eael-grouped-product-edit">
      <div class="grouped-product-variation">
        <div class="single-product-variation product-edit-odd">
          <input class="quantity-input" …>
          <a href="#" class="product-variation-title">Hoodie with Pocket</a>
          <p class="product-variation-price">$45.00</p>
        </div>
        … (Beanie with Logo $18, Hoodie with Pocket $35)
      </div>
      <button class="eael-add-to-cart">…</button>
    </div>

    <!-- external_product: button only (no quantity, no icon) -->
    [?] <button class="eael-add-to-cart">
      <span class="button-text">{Add to cart}</span>
    </button>
  </div>
</div>
```

Notes:

- `eael-add-to-cart--align-{flex-start|center|end}` is a `prefix_class` on `{{WRAPPER}}` from the `eael_add_to_cart_align` responsive control — alignment is parent-scoped via flexbox `justify-content`.
- `eael-add-to-cart--layout-{row|column}` toggles flex-direction on the inner `form.cart .cart` element — drives Inline vs Stacked layout for simple products.
- Two-name editor-vs-frontend mismatch: `.eael-add-to-cart` (editor button) vs `.single_add_to_cart_button` (WC frontend button). Style controls target both.
- Cart icon span class differs: `.cart-icon` (editor) vs no wrapper (frontend — icon is emitted bare by `Icons_Manager::render_icon`).
- `data-product-type` carries WC's product-type string (`simple`, `variable`, `grouped`, `external`) — JS reads it to branch AJAX payload construction.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Add_To_Cart.php#L59) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `add_to_cart_product_type` | SELECT | `simple_product` | Content → General | **Editor preview only** — selects mockup variant; ignored on frontend |
| `add_to_cart_layout` | SELECT | `row` | Content → General | `prefix_class` `eael-add-to-cart--layout-`; flex-direction on `.cart` |
| `add_to_cart_show_quantity` | SWITCHER | `yes` | Content → General | Toggles WC `woocommerce_is_sold_individually` filter (false hides qty input) |
| `add_to_cart_text` | TEXT (AI active) | `"Add to cart"` | Content → General | Custom button text via `woocommerce_product_single_add_to_cart_text` filter |
| `add_to_cart_icon_show` | SWITCHER | `yes` | Content → General | Toggle cart icon emission (only for non-external products) |
| `add_to_cart_icon` | ICONS | `fas fa-cart-plus` | Content → General | Icon picker; rendered via `Icons_Manager::render_icon()` |
| `eael_ajax_add_to_cart` | SWITCHER | empty | Content → General | Adds `data-eael-ajax-add-to-cart="yes"` to wrapper; AJAX submission path |
| `eael_add_to_cart_align` | CHOOSE (responsive) | none | Style → Button | `prefix_class` for alignment; flexbox `justify-content` |
| `width` | SLIDER (responsive) | none | Style → Button | Button width across both editor + frontend selectors |
| `eael_add_to_cart_text_align` | CHOOSE (responsive) | none | Style → Button | Inner button text alignment via flex `justify-content` |
| `eael_add_to_cart_button_typography` | GROUP | — | Style → Button | Button text typography |
| `eael_add_to_cart_button_border` | GROUP (excl. color) | — | Style → Button | Button border (color via tab) |
| `button_border_radius` | DIMENSIONS | none | Style → Button | Border radius across both selectors |
| Style → Button (Normal/Hover tabs) | various | — | Style tab | Text color, bg color, border color — per-state controls |
| Style → Quantity | various | — | Style tab | Quantity input: width, height, spacing (gap/margin), typography, border, padding, focus state — hidden when `product_type == external_product` |
| Style → Variations | various | — | Style tab | Variation table cell width/padding/typography — hidden when `product_type` IN `[external_product, simple_product]` |
| Style → Cart Icon | various | — | Style tab | Icon size, spacing, color — hidden when `product_type == external_product` |

## Conditional Dependencies

```text
# Layout / preview gating (all key on add_to_cart_product_type)
add_to_cart_layout              → hidden when product_type == 'external_product'
add_to_cart_show_quantity       → hidden when product_type == 'external_product'
add_to_cart_icon_show           → hidden when product_type == 'external_product'
add_to_cart_icon                → visible when add_to_cart_icon_show == 'yes'
                                  AND product_type != 'external_product'
eael_ajax_add_to_cart           → hidden when product_type == 'external_product'

# Style sections
Style → Quantity                → hidden when product_type == 'external_product'
Style → Variations              → hidden when product_type IN ['external_product', 'simple_product']
Style → Cart Icon               → hidden when product_type == 'external_product'

# Frontend gate (not in panel — runtime)
Entire render output            → empty when WooCommerce is inactive OR
                                  Helper::get_product() returns false (non-product page)
```

No `eael_section_pro` upsell panel. The widget has no Pro-gated controls.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `woocommerce_product_single_add_to_cart_text` | filter (consumed) | `(string $text)` via callback `eael_add_to_cart_text_single` | Inject the panel's `add_to_cart_text` value as button label — **never removed after render** (leaks to subsequent widgets) |
| `woocommerce_is_sold_individually` | filter (consumed) | `(bool $return, WC_Product $product)` | Force-`true` to hide WC's quantity input — registered conditionally when `add_to_cart_show_quantity` is empty |
| `wp_ajax_eael_ajax_add_to_cart` / `_nopriv` | action (consumed) | — | AJAX endpoint for the "AJAX Add to Cart" feature; handler in [`Ajax_Handler::eael_ajax_add_to_cart()`](../../includes/Traits/Ajax_Handler.php#L1614) |
| `woocommerce_add_to_cart_fragments` | filter (consumed) | `(array $fragments)` | WC-native fragment refresh; widget calls it inside the AJAX handler to populate mini-cart fragments in the response |

⚠️ Widget emits **no own** action / filter hooks — `do_action` / `apply_filters` count in `Woo_Add_To_Cart.php` is **zero**. The widget exists purely as a styled wrapper over WC's native template system; extension is via WC's own hook chain (`woocommerce_before_add_to_cart_form`, `woocommerce_after_add_to_cart_button`, etc.) which fires inside `woocommerce_template_single_add_to_cart()`.

No shared patterns from [`_patterns.md`](_patterns.md) apply — no Liquid Glass, no FA4 shim (uses modern `ICONS` picker directly without legacy FA4 control), no WPML media, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-add-to-cart.default', …)` ([line 2-4 of woo-add-to-cart.js](../../src/js/view/woo-add-to-cart.js#L2)) — keyed by widget id `eael-woo-add-to-cart`
- **Boot pattern:** Uses newer `eael.hooks.addAction("init", "ea", …)` outer wrapper (the same pattern as Adv_Accordion / Image_Accordion / Content_Ticker)
- **Guard:** None — but the handler short-circuits at line 9-11 if `.eael-add-to-cart-wrapper[data-eael-ajax-add-to-cart="yes"]` isn't present (AJAX disabled). No `elementStatusCheck()` flag.
- **Vendor deps:** jQuery + WooCommerce frontend scripts (`wc_fragment_refresh`, `added_to_cart` events). No EA vendor libs.
- **Reads on init:** `data-product-id`, `data-product-type`, `data-nonce` from `.eael-add-to-cart-wrapper`
- **Click delegation:** `$wrapper.off("click.eael-atc").on("click.eael-atc", ".single_add_to_cart_button", …)` — scoped to widget's wrapper, namespaced `.eael-atc` for safe `off()` (prevents double-binding on Elementor re-init)
- **Branch logic per product type:**
  - **External** — reads `form action` (WC puts affiliate URL there), opens in new window via `window.open(externalUrl, $form.attr("target") || "_self")` then returns without AJAX
  - **Variable** — reads `input[name="variation_id"]`; if empty, triggers `.variations select` change event to nudge WC's own variation resolver, returns without submitting
  - **Grouped** — iterates `input[name^="quantity["]` (WC's notation for grouped child quantities) and sends each `quantity[child_id]` as a separate POST param
  - **Simple** (default) — reads `input.qty` for quantity, posts `product_id` + `quantity` + nonce
- **AJAX response handling:**
  - On success: triggers `wc_fragment_refresh` (mini-cart refresh) + `added_to_cart` event (3 args matching WC's native event signature); injects `.added` class on button for 2-second visual feedback
  - On failure: injects WC notices into `.woocommerce-notices-wrapper` div
- **No runtime state** — all state is in the DOM (button class flags) or transient (in-flight request)

## Common Issues

### AJAX add-to-cart fires but cart count doesn't update

- **Likely cause:** Mini-cart fragment selector mismatch with theme; `wc_fragment_refresh` event triggered but no listener registered, or theme's mini-cart markup isn't keyed by WC's fragment selectors
- **Diagnose:** browser console for "fragments" object in network response; check theme has `<a class="cart-customlocation">` or equivalent WC fragment selectors
- **Fix:** WC fragment refresh requires the theme to ship WC-compatible mini-cart markup. If theme uses custom selectors, register them via `woocommerce_add_to_cart_fragments` filter

### Custom button text shows on second widget on the same page

- **Likely cause:** `add_filter('woocommerce_product_single_add_to_cart_text', …)` is registered before render but **never removed** — the filter persists for subsequent widgets on the same page render
- **Diagnose:** place two Woo_Add_To_Cart widgets with different `add_to_cart_text` values on one page; observe the second widget shows the first's text
- **Fix:** known bug — pending a `remove_filter()` call at end of `render()`. Workaround: set the same `add_to_cart_text` on all instances of the widget on one page

### Cart icon renders before the button text instead of inside the button

- **Likely cause:** `Icons_Manager::render_icon()` echoes the icon HTML — but EA's filter callback `eael_add_to_cart_text_single` calls it as a side effect, so the icon prints into the **document flow at the position of the filter execution** (before WC echoes the button), not inside the button
- **Diagnose:** view source — `<i class="fa fa-cart-plus">` appears before `<button>` element
- **Fix:** intentional? — the design relies on CSS positioning to overlay or float the icon. If icon position seems wrong, check `.eael-single-product-add-to-cart` CSS rules in `woo-add-to-cart.scss`

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns `false` because there's no `$product` global on the page; `render()` returns silently at [line 1141](../../includes/Elements/Woo_Add_To_Cart.php#L1141) without any user-facing message
- **Diagnose:** the widget appears blank on the frontend; editor shows mockup correctly
- **Fix:** the widget is designed for **single-product templates** (theme builder Single Product, Loop Grid in archive). On a generic page, pass a product context via theme builder or use Woo_Product_List / Product_Grid instead

### "AJAX Add to Cart" switcher does nothing when product is in stock but variation not chosen

- **Likely cause:** variable product without a selected variation; JS at [line 47-56 of woo-add-to-cart.js](../../src/js/view/woo-add-to-cart.js#L47) detects `variation_id <= 0` and triggers `.variations select` change to nudge WC's own resolver, but never re-attempts the AJAX submit
- **Diagnose:** select the variation; click again; AJAX fires correctly the second time
- **Fix:** intentional UX — user must select a variation first; the trigger nudges WC to show the price/SKU. No automatic re-submit (would race with WC's variation resolver)

### Grouped product children with quantity 0 still get added

- **Likely cause:** AJAX handler checks `$child_id > 0 && $qty > 0` ([line 1638 of Ajax_Handler.php](../../includes/Traits/Ajax_Handler.php#L1638)) — children with `qty == 0` are skipped, so this should not happen
- **Diagnose:** browser DevTools → network → AJAX POST body — confirm `quantity[<child_id>]=0` is being sent and not stripped client-side
- **Fix:** if seen, file a bug — the gate is server-side; only path to bypass is request tampering

## Known Limitations

- **`woocommerce_product_single_add_to_cart_text` filter leak** ([line 1154](../../includes/Elements/Woo_Add_To_Cart.php#L1154)) — `add_filter()` is registered before `woocommerce_template_single_add_to_cart()` but **never removed**. Subsequent renders on the same page request see the same filter callback fire. A second Woo_Add_To_Cart widget instance re-registers the same callback (function array, not closure) so PHP de-dupes it, but the stored `$this` reference still points to the first widget — second widget's `add_to_cart_text` is ignored.
- **`woocommerce_is_sold_individually` filter leak** ([line 1050](../../includes/Elements/Woo_Add_To_Cart.php#L1050)) — same pattern as above; registered when quantity is hidden, never removed. Contaminates `$product->is_sold_individually()` for any subsequent WC code in the same request (Cart, Mini-Cart, etc.).
- **Editor mockup has hardcoded English strings** — "Hoodie with Pocket", "Beanie with Logo", "$45.00", "Color", "Red/Green/Blue" — these strings ship as translatable (text-domain `essential-addons-for-elementor-lite`) but pollute translator workload and don't reflect any real product data. Production sites with non-English locales see these strings in editor preview only.
- **`add_to_cart_product_type` control affects editor only** — frontend ignores this setting entirely (uses WC's actual product type from `Helper::get_product()`). Misleading because it gates style sections (Variations, Cart Icon) on the **panel side** — if the editor shows a "simple" mockup but the actual product is variable, the user thinks they configured correctly but the variations section was hidden in their panel.
- **`Icons_Manager::render_icon()` echoes, doesn't return** — `eael_add_to_cart_icon()` calls it without `'echo' => false`, so the icon is printed mid-filter-execution rather than embedded inside the returned button text. Fragile to filter ordering.
- **No `eael_section_pro` upsell, no Pro extension surface** — unlike Product_Grid, Woo_Product_List, etc., this widget has no opportunity for Pro to add features. Pro provides no path to extend this widget without editing the Lite class.
- **No FA4 → FA5 icon shim** — the widget uses only `Controls_Manager::ICONS` (FA5+ era). Pre-2.5 saved widgets with FA4 icons may not migrate cleanly because the FA4 fallback control pair isn't present. Pattern in [`_patterns.md § FA4`](_patterns.md#fa4-fa5-icon-migration-shim) doesn't apply.
- **`Helper::get_product()` silently returns `wc_get_product()` with no arg** — relies on WC's global `$product`. Without one, `$product` is `false`; render exits silently. No editor warning, no admin notice. New users placing the widget on a generic page see "nothing rendered" with zero diagnostic feedback.
- **No `elementStatusCheck` guard in JS** — Elementor's re-fire of `frontend/element_ready` on preview-switching can re-execute the handler, but `$wrapper.off("click.eael-atc").on("click.eael-atc", …)` (namespaced) safely re-binds. Acceptable but unconventional vs other EA widgets.
- **External-product opens in `_self` by default** — `window.open(externalUrl, $form.attr("target") || "_self")` — if WC's form has no `target` attribute, the external affiliate URL replaces the current tab. Most affiliate plugins set `target="_blank"`; if not configured, customers lose the merchant's page navigation. WC's own external-product link uses `<a target="_blank">` by default — this widget's AJAX path differs.
- **AJAX handler doesn't enforce `current_user_can()` or rate limit** — `wp_ajax_nopriv_eael_ajax_add_to_cart` is open to unauthenticated visitors (correct — guest checkout). Nonce-protected but cart-bombing via repeated POSTs isn't prevented. Per-product `is_purchasable()` is implicitly checked by `WC()->cart->add_to_cart()` which returns `false` on disallowed products.
- **No CSS variable / theming tokens** — all styling is per-control. Refactor opportunity if EA adopts the design-system tokens charter (issue #810).
