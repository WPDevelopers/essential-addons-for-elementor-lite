# Woo Product Tabs Widget

> ⚠️ **Status: SPEC / NOT YET IMPLEMENTED.** This doc describes the planned widget per [`PRD-Woo-Product-Tabs`](../../../../../PRD/PRD-Woo-Product-Tabs.md) (PRD lives outside the repo, in the non-git `eadev/PRD/` folder). Line references and "as-built" details are filled in after implementation + `npm run build`.

> Renders the current product's **native WooCommerce data tabs** (Description / Additional Information / Reviews) via `wc_get_template( 'single-product/tabs/tabs.php' )` and styles them entirely through Elementor selector controls. Free-version equivalent of Elementor Pro's **Product Data Tabs**. **Pure style widget — no own Content controls, no JS planned** (WC's own single-product tab JS drives the toggle on the frontend). Lite-only; no Pro extension.

**Class file:** `includes/Elements/Woo_Product_Tabs.php` *(to be created)*
**Slug:** `woo-product-tabs` (widget id `eael-woo-product-tabs`) — slug + widget id consistent.
**Controller prefix:** all new control IDs use `eael_product_tabs`.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-tabs>
**Pro-shared:** ❌ No — Lite-only planned. No `eael/pro_enabled` checks. No `eael_section_pro` upsell panel.

---

## Overview

A thin single-product display widget. `render()` resolves the product via `Helper::get_product()`, brackets a `setup_postdata()` / `wp_reset_postdata()` pair, and includes WooCommerce's overridable `single-product/tabs/tabs.php` template. The tab set itself (Description, Additional Information, Reviews) is owned by WooCommerce and filtered via `woocommerce_product_tabs` — the widget adds **no** tab-management UI; its entire job is to skin the native tabs and panels.

Mirrors the render shape of Elementor Pro's `Product_Data_Tabs` almost verbatim (the Pro widget has no Pro-only dependency — it just renders the WC template and styles it). The only EA additions are the WC-inactive editor guard and a non-product-context editor placeholder.

## Reference Widget (Elementor Pro)

| Aspect | Pro `Product_Data_Tabs` |
| ------ | ----------------------- |
| Class | `extends Base_Widget` (Woo module) |
| `get_name()` | `woocommerce-product-data-tabs` |
| `get_title()` | `Product Data Tabs` |
| `get_icon()` | `eicon-product-tabs` |
| `get_style_depends()` | `widget-woocommerce-product-data-tabs` |
| Controls | **Style tab only** — two sections: **Tabs** (normal/active text/bg/border color, label typography, top border-radius) and **Panel** (body color + typography, heading color + typography, border width/radius, box-shadow). No Content tab. |
| `render()` | `global $product = $this->get_product()` → `setup_postdata()` → `wc_get_template( 'single-product/tabs/tabs.php' )`; on AJAX (editor) fires `jQuery(...).trigger('init')` to re-init tab JS |
| `render_plain_content()` | empty |

EA reproduces the render path and the two style sections directly. `$this->get_product()` becomes `Helper::get_product()`; everything else (the WC template include, the AJAX re-init script) is copied.

## Pro vs Lite

| Capability | Lite (planned) | Elementor Pro |
| ---------- | -------------- | ------------- |
| Native WC tabs render (`tabs.php`) | ✅ | ✅ |
| Theme template override respected | ✅ | ✅ |
| Tabs normal/active text/bg/border color | ✅ | ✅ |
| Tab label typography + top border-radius | ✅ | ✅ |
| Panel body color + typography | ✅ | ✅ |
| Panel heading (`h2`) color + typography | ✅ | ✅ |
| Panel border width / radius / box-shadow | ✅ | ✅ |
| "style affected by theme" alert | ✅ | ✅ |
| Editor AJAX tab re-init (`.trigger('init')`) | ✅ | ✅ |
| WC-inactive editor warning | ✅ `eael_wc_notice_controls()` | — |
| Editor placeholder when no product context | ✅ | — (Pro assumes product context) |
| Custom tab add / remove / reorder | ❌ (out of scope, future PRD) | ❌ (Pro has none either) |
| `has_widget_inner_wrapper()` / `e_optimized_markup` | ❌ not managed | ✅ |

## File Map

| File | Role |
| ---- | ---- |
| `includes/Elements/Woo_Product_Tabs.php` | PHP widget class — two style sections + render with editor/frontend branch *(to create)* |
| `includes/Classes/Helper.php` → `Helper::get_product()` | Product context resolution — falls back to global `$product` |
| `src/css/view/woo-product-tabs.scss` | Source styles — editor placeholder + minimal reset *(to create)* |
| `config.php` entry `'woo-product-tabs'` | Asset declaration — **single CSS only, NO JS** *(to add)* |
| `assets/front-end/css/view/woo-product-tabs.min.css` | Built output *(after `npm run build`)* |
| `woocommerce/templates/single-product/tabs/tabs.php` (consumed) | The WC template this widget includes; theme override target |

## Architecture

- **Render delegates to the WC template.** `wc_get_template( 'single-product/tabs/tabs.php' )` emits WooCommerce's native `.woocommerce-tabs` markup; theme overrides in `your-theme/woocommerce/single-product/tabs/tabs.php` continue to apply. The widget reimplements nothing.
- **`global $product = Helper::get_product()`** — shared single-product context pattern across EA Woo widgets. Required because the WC template and review form read the `$product` / `$post` globals.
- **`setup_postdata()` / `wp_reset_postdata()` bracket the render.** The reviews tab and comment form read the global `$post`; bracketing prevents corrupting the surrounding Theme Builder loop. (Pro does not reset; EA adds the reset for Theme Builder safety.)
- **Editor AJAX re-init.** When `wp_doing_ajax()` (editor render), an inline `jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' )` re-runs WC's tab toggle JS so only the active panel shows in the editor. On the frontend, WC's own `wc-single-product` script handles this — the widget ships no JS.
- **Styling is 100% selector-driven.** All visual controls target `.woocommerce {{WRAPPER}} .woocommerce-tabs …` — the leading `.woocommerce` body-class prefix is mandatory to out-specify WC's base CSS (Pro uses the same pattern).
- **WC-inactive guard** — `register_controls()` calls `eael_wc_notice_controls()` first; with WooCommerce inactive it shows a warning and returns early.
- **Silent frontend exit** when no `$product` (non-product page); editor shows a placeholder div so style controls remain usable.

## Render Output

### Frontend (real product context)

```html
<!-- output of wc_get_template('single-product/tabs/tabs.php') -->
<div class="woocommerce-tabs wc-tabs-wrapper">
  <ul class="tabs wc-tabs">
    <li class="description_tab active"><a href="#tab-description">Description</a></li>
    <li class="additional_information_tab"><a href="#tab-additional_information">Additional information</a></li>
    <li class="reviews_tab"><a href="#tab-reviews">Reviews (N)</a></li>
  </ul>
  <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--description" id="tab-description">
    <h2>Description</h2>
    ...
  </div>
  <!-- additional_information + reviews panels -->
</div>
```

### Editor preview (no product context)

```html
<div class="eael-woo-product-tabs-placeholder">
  Product Data Tabs will be displayed here on a single product page.
</div>
```

## Controls Reference

Source `register_controls()` is the truth once built. All control IDs use the `eael_product_tabs` prefix.

### Style → Tabs (`eael_section_product_tabs_style`)

| ID | Type | Affects |
| --- | ---- | ------- |
| `eael_product_tabs_style_warning` | ALERT (info) | "style affected by theme/plugins" notice |
| `eael_product_tabs_tab_text_color` | COLOR | `ul.wc-tabs li a` color (normal) |
| `eael_product_tabs_tab_bg_color` | COLOR (alpha off) | `ul.wc-tabs li` background (normal) |
| `eael_product_tabs_border_color` | COLOR | panel + `ul.wc-tabs li` border-color (normal) |
| `eael_product_tabs_active_tab_text_color` | COLOR | `li.active a` color |
| `eael_product_tabs_active_tab_bg_color` | COLOR (alpha off) | active `li` + panel background |
| `eael_product_tabs_active_border_color` | COLOR | active `li` border (with bottom-seam handling) |
| `eael_product_tabs_typography` | GROUP_TYPOGRAPHY | `ul.wc-tabs li a` |
| `eael_product_tabs_border_radius` | SLIDER | `ul.wc-tabs li` top-corner radius (`{{SIZE}} {{SIZE}} 0 0`) |

### Style → Panel (`eael_section_product_panel_style`)

| ID | Type | Affects |
| --- | ---- | ------- |
| `eael_product_tabs_panel_text_color` | COLOR | `.woocommerce-Tabs-panel` color |
| `eael_product_tabs_content_typography` | GROUP_TYPOGRAPHY | panel body |
| `eael_product_tabs_panel_heading` | HEADING | "Heading" label (separator before) |
| `eael_product_tabs_heading_color` | COLOR | panel `h2` color |
| `eael_product_tabs_content_heading_typography` | GROUP_TYPOGRAPHY | panel `h2` |
| `eael_product_tabs_panel_border_width` | DIMENSIONS | panel `border-width` + `margin-top: -{{TOP}}` (tab/panel seam) |
| `eael_product_tabs_panel_border_radius` | DIMENSIONS | panel `border-radius` |
| `eael_product_tabs_panel_box_shadow` | GROUP_BOX_SHADOW | panel shadow |

No Content tab (matches Pro). No `eael_section_pro` upsell panel.

## Conditional Dependencies

```text
# WC-inactive guard
eael_global_warning section            → shown only when WC() is unavailable; register_controls() returns early
All style controls                     → registered only when WooCommerce is active

# Frontend gates
Entire output                          → empty when WooCommerce inactive (early return)
Silent return                          → when Helper::get_product() is false AND not in editor (non-product page)
Editor placeholder                     → static div when in editor with no product
AJAX re-init <script>                  → printed only when wp_doing_ajax() (editor render)
```

## Hooks & Filters

| Hook | Type | Purpose |
| ---- | ---- | ------- |
| `Helper::get_product()` (consumed) | — | Returns `wc_get_product($id)`; falls back to global `$product` |
| `woocommerce_product_tabs` (consumed, indirect) | filter | WC-core filter that defines which tabs render; the widget does not register it but inherits whatever it yields |
| `the_content` (consumed, indirect) | filter | Runs inside WC's description tab template |

⚠️ Emits **zero** `do_action` / `apply_filters` of its own (planned) — no extension surface beyond CSS and the WC template override.

## JavaScript Lifecycle

**N/A — no widget JS file planned in `config.php`.** On the frontend, WooCommerce's `wc-single-product` script (already enqueued on single-product pages) drives the tab toggle. In the Elementor editor, an inline `jQuery(...).trigger('init')` printed during AJAX render re-initialises the toggle so only the active panel is visible.

## Common Issues

### All panels show at once (no toggle) in the editor

- **Likely cause:** WC tab JS not (re-)initialised after the editor's AJAX render.
- **Diagnose:** every panel visible stacked; clicking tabs does nothing.
- **Fix:** ensure the `wp_doing_ajax()` branch prints `jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' )` (copied from Pro).

### Color / border controls have no effect

- **Likely cause:** selector missing the leading `.woocommerce` body-class scope, so WC base CSS out-specifies it.
- **Diagnose:** inspect the tab `li`/panel; the widget's rule is overridden by a `.woocommerce …` rule.
- **Fix:** prefix selectors as `.woocommerce {{WRAPPER}} .woocommerce-tabs …` exactly as Pro does.

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns false; render exits silently with no editor warning.
- **Diagnose:** blank on frontend; editor shows the placeholder div.
- **Fix:** use on a Single Product Theme Builder template, or provide product context via a Loop Grid.

### Reviews tab missing

- **Likely cause:** WooCommerce "Enable reviews" is off (Settings → Products) or the product has reviews disabled.
- **Diagnose:** only Description / Additional Information appear.
- **Fix:** WC-controlled, not a widget bug — enable reviews in WC settings.

### Surrounding Theme Builder widgets show the wrong post

- **Likely cause:** `setup_postdata()` called without a matching `wp_reset_postdata()`.
- **Diagnose:** widgets after Product Tabs pull this product's data.
- **Fix:** confirm `wp_reset_postdata()` runs after the template include.

## Known Limitations

- **No tab management** — cannot add, remove, reorder, or rename tabs from the widget; the tab set is whatever `woocommerce_product_tabs` yields (matches Pro). Tab customisation requires a `woocommerce_product_tabs` filter in code/another plugin.
- **No layout variants** — native horizontal WC tabs only; no accordion / vertical-tabs option (future PRD).
- **No tab icons.**
- **Silent frontend exit on no `$product`** — no editor warning, no admin notice.
- **No `wpml_object_id` filter** — product ID isn't translated; cross-language sites may render the wrong product's tabs.
- **`has_widget_inner_wrapper()` / `e_optimized_markup` not handled** — uses the default EA wrapper (EA Lite convention).

## Implementation Checklist

See [`PRD-Woo-Product-Tabs`](../../../../../PRD/PRD-Woo-Product-Tabs.md) §11 for the ordered build steps. Summary:

1. Create `includes/Elements/Woo_Product_Tabs.php` (copy the two Pro style sections, re-prefix IDs to `eael_product_tabs`, swap `$this->get_product()` → `Helper::get_product()`, add the WC-inactive guard + editor placeholder).
2. Create `src/css/view/woo-product-tabs.scss` (placeholder + minimal reset).
3. Register `'woo-product-tabs'` in `config.php` (single CSS, no JS).
4. `npm run build`.
5. Verify editor + frontend on the wp-env test site (use a product with reviews so all three tabs appear; confirm editor toggle works).
6. Replace the "to create / after build" notes in this doc with real line references.
