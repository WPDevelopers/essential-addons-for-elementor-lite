# Woo Product Short Description Widget

> Renders WC's `single-product/short-description.php` template on any page with `$product` context — outputs the product's short description (excerpt) inside WC's native `.woocommerce-product-details__short-description` markup. **Pure CSS widget — no JS file**. Hardcoded editor placeholder paragraph when no product context. Modeled on Elementor Pro's "Short Description" Woo widget. Lite-only widget; no Pro extension. Spec source: `PRD/PRD-Woo-Product-Short-Description.md` (non-git, repo root).

**Class file:** [`includes/Elements/Woo_Product_Short_Description.php`](../../includes/Elements/Woo_Product_Short_Description.php)
**Slug:** `woo-product-short-description` (widget id `eael-woo-product-short-description`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-short-description>
**Pro-shared:** ❌ No — Lite-only. No `do_action` / `apply_filters` emitted. No `eael/pro_enabled` checks. No `eael_section_pro` upsell panel.

---

## Overview

Thin display widget. `render()` delegates to WC's `wc_get_template('single-product/short-description.php')` on the frontend when a `$product` context exists; in the editor without a product it renders a hardcoded placeholder paragraph (wrapped in WC's native class so style controls preview correctly). A single **Style** section provides alignment, text color, and typography — matching Elementor Pro's widget, which also exposes only those three controls plus a theme-conflict warning alert. No Content tab.

The short description is the product **excerpt** (WP admin → Product edit → "Product short description" box). WC's template emits it through the `woocommerce_short_description` filter (so shortcodes / `wpautop` run), which is why the widget delegates rather than printing the excerpt directly.

## Reference Widget (Elementor Pro)

Modeled on `elementor-pro/modules/woocommerce/widgets/product-short-description.php`. Pro's widget:

- `extends Base_Widget` (Pro Woo base) — EA's version extends `\Elementor\Widget_Base` instead, per EA convention.
- One Style section: `wc_style_warning` (info ALERT), `text_align` (responsive CHOOSE, RTL-aware), `text_color` (COLOR), `text_typography` (typography group).
- `render()`: `global $product = $this->get_product()` → return if no product → `wc_get_template('single-product/short-description.php')`.
- No Pro-only dynamic tags — content comes entirely from the WC template, so the feature set reproduces cleanly in the free version.

## Pro vs Lite

| Capability | Lite (this widget) | Elementor Pro |
| ---------- | ------------------ | ------------- |
| Renders WC's native `single-product/short-description.php` | ✅ | ✅ |
| Alignment (start / center / end / justify, responsive, RTL-aware) | ✅ | ✅ |
| Text color | ✅ | ✅ |
| Typography group | ✅ | ✅ |
| Theme-conflict warning alert | ✅ | ✅ |
| Editor placeholder when no product | ✅ (hardcoded paragraph) | live excerpt preview |
| WooCommerce-inactive editor warning | ✅ `eael_wc_notice_controls()` | — (Pro assumes WC) |
| `eael_section_pro` upsell panel | ❌ — none | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Short_Description.php`](../../includes/Elements/Woo_Product_Short_Description.php) | PHP widget class — controls + render with editor/frontend branch |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L1998) | `Helper::get_product()` — falls back to global `$product` |
| [`src/css/view/woo-product-short-description.scss`](../../src/css/view/woo-product-short-description.scss) | Source styles — minimal wrapper reset |
| [`config.php`](../../config.php) entry `'woo-product-short-description'` | Asset declaration — **single CSS only, NO JS** |
| `assets/front-end/css/view/woo-product-short-description.min.css` | Built output |
| `includes/Traits/Admin.php` + `includes/Classes/WPDeveloper_Setup_Wizard.php` | Dashboard toggle + setup-wizard listing (woocommerce-elements) |
| `woocommerce/templates/single-product/short-description.php` | WC's canonical short-description template (consumed) |

## Architecture

- **Render delegates to WC's `wc_get_template('single-product/short-description.php')`** — WC's template emits `<div class="woocommerce-product-details__short-description">{excerpt}</div>` through the `woocommerce_short_description` filter. The EA widget only adds an `.eael-single-product-short-description` wrapper around it.
- **`global $product` must be set before the template call** — WC's template reads the global `$product`. After `Helper::get_product()`, the result is assigned back to `global $product` (same as Elementor Pro's widget); skipping this yields an empty render.
- **Editor placeholder paragraph** — when in editor (or `templately_library` post type) with no product context, renders a fixed placeholder `<p>` inside the native `.woocommerce-product-details__short-description` div so alignment / color / typography controls preview correctly. Frontend with no product context exits silently (Woo_Product_Price pattern).
- **`Helper::get_product()` fallback to global `$product`** — when no product context on the frontend, render exits silently with no editor warning.
- **Single Style section, no Content tab** — mirrors Pro. Alignment uses Elementor's `start` / `end` dictionary with an RTL-aware `selectors_dictionary`.

## Render Output

### Frontend (real product context)

```html
<div class="eael-single-product-short-description">
  <!-- WC's wc_get_template('single-product/short-description.php') output: -->
  <div class="woocommerce-product-details__short-description">
    {product short description / excerpt — filtered through woocommerce_short_description}
  </div>
</div>
```

### Editor placeholder (no product context)

```html
<div class="eael-single-product-short-description">
  <div class="woocommerce-product-details__short-description">
    <p>This is a short product description placeholder. Add a short description
       to your product to see it here.</p>
  </div>
</div>
```

Notes:

- Placeholder uses the **same** `.woocommerce-product-details__short-description` class as the frontend so style-control selectors apply identically in editor and frontend (no editor/frontend selector mismatch — an improvement over `Woo_Product_Price`'s `.eael-product-price-edit` wrapper divergence).
- Real content is produced by WC's template (which runs `wpautop` + shortcodes); the widget does not re-escape it.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Short_Description.php) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `wc_style_warning` | ALERT (info) | — | Style → Short Description | Static notice: style is often affected by theme/plugins |
| `text_align` | CHOOSE (responsive) | empty | Style → Short Description | `text-align` on `{{WRAPPER}}` — start / center / end / justify; RTL-aware via `selectors_dictionary` |
| `text_color` | COLOR | — | Style → Short Description | `color` on `{{WRAPPER}} .woocommerce-product-details__short-description` |
| `text_typography` | GROUP_TYPOGRAPHY | — | Style → Short Description | Typography on the same selector |

## Conditional Dependencies

```text
# Frontend gates
Entire output       → empty when WooCommerce inactive (render returns early)
Silent return       → when Helper::get_product() returns false on frontend (no $product context)
Editor placeholder  → hardcoded paragraph when in editor / templately_library AND no product

# Controls
WC-inactive warning → eael_wc_notice_controls() shows a Warning section when !function_exists('WC')
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

⚠️ **No `do_action` / `apply_filters` emitted** by this widget — minimal extension surface (same as `Woo_Product_Price`).

| Hook | Type | Purpose |
| ---- | ---- | ------- |
| `Helper::get_product()` (consumed) | — | Returns `wc_get_product($id)`; falls back to global `$product` |
| `wc_get_template('single-product/short-description.php')` | template (consumed) | WC's canonical short-description markup |
| `woocommerce_short_description` (consumed via template) | filter | WC core filter applied to the excerpt inside the template (`wpautop`, shortcodes, etc.) |

## JavaScript Lifecycle

**N/A — pure CSS widget**, no JavaScript file declared in `config.php`. Static server-render only; no DOM interaction, no AJAX.

## Common Issues

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns false; render exits silently with no editor warning.
- **Diagnose:** blank on frontend; editor shows the placeholder paragraph.
- **Fix:** use on a Single Product theme-builder template, or inside a Loop context that provides product data.

### Editor shows placeholder but frontend is empty

- **Likely cause:** the product has no short description (empty excerpt); WC's template renders an empty/absent block.
- **Diagnose:** WP admin → Product edit → "Product short description" box is empty.
- **Fix:** add a short description to the product.

### Alignment / color doesn't apply (theme overrides it)

- **Likely cause:** theme CSS targeting `.woocommerce .woocommerce-product-details__short-description` has higher specificity than the widget's `{{WRAPPER}} .woocommerce-product-details__short-description` selectors.
- **Diagnose:** inspect computed style; check for theme rules winning specificity.
- **Fix:** if widespread, prefix the control selectors with `.woocommerce {{WRAPPER}}` (as Elementor Pro does) to boost specificity. Decide during the verify step.

## Known Limitations

- **No content controls** — no read-more toggle, character-limit, or trim. Output is whatever WC's template emits. (Pro has none either.)
- **No extension hooks** — emits no `do_action` / `apply_filters`. No way for Pro / 3rd party to inject controls or alter output beyond CSS.
- **Render exits silently on no `$product`** — no editor warning, no admin notice.
- **No `wpml_object_id` filter** — product ID isn't translated; cross-language sites may render the wrong product's description.
- **No `is_dynamic_content()` override** — Elementor's render cache may freeze the description HTML until cache expires.
- **Theme-dependent styling** — output relies on WC's native `.woocommerce-product-details__short-description` class, which themes commonly style; hence the built-in warning alert.
</content>
