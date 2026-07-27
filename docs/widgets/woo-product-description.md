# Woo Product Description Widget

> ⚠️ **Status: SPEC / NOT YET IMPLEMENTED.** This doc describes the planned widget per [`PRD-Woo-Product-Description`](../../../../../PRD/PRD-Woo-Product-Description.md) (PRD lives outside the repo, in the non-git `eadev/PRD/` folder). Line references and "as-built" details are filled in after implementation + `npm run build`.

> Renders the current product's **full long description** (`the_content` — the WP editor body) on any page with `$product` context. Free-version equivalent of Elementor Pro's **Product Content** widget. **Pure CSS widget — no JS planned.** Hardcoded editor placeholder when no product context. Lite-only widget; no Pro extension.

**Class file:** `includes/Elements/Woo_Product_Description.php` *(to be created)*
**Slug:** `woo-product-description` (widget id `eael-woo-product-description`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-description>
**Pro-shared:** ❌ No — Lite-only planned. No `eael/pro_enabled` checks. No `eael_section_pro` upsell panel.

---

## Overview

Thin single-product display widget. `render()` outputs the product's long description by running `apply_filters( 'the_content', $post->post_content )` (so shortcodes, `wpautop`, and oEmbed all process) on frontend; the editor renders a static placeholder paragraph so the style controls can be tested without a product context.

Mirrors the structure of the already-shipped [`woo-product-short-description.md`](woo-product-short-description.md) — same WC-inactive guard, same `Helper::get_product()` context resolution, same editor-placeholder pattern. **The only meaningful difference is the rendered content:** Short Description renders the product *excerpt* via `wc_get_template('single-product/short-description.php')`; **Product Description renders the full post body (`the_content`)** — the main WP editor content, which WooCommerce normally surfaces in the "Description" tab.

### Short Description vs Product Description

| Widget | Source data | WC class / output |
| ------ | ----------- | ----------------- |
| Woo Product Short Description | product **excerpt** | `.woocommerce-product-details__short-description` (via WC template) |
| **Woo Product Description (this widget)** | product **post content** (`the_content`) | `.eael-single-product-description` wrapping filtered content |

## Reference Widget (Elementor Pro)

| Aspect | Pro `Product_Content` |
| ------ | --------------------- |
| Class | `extends Post_Content` (ThemeBuilder) → `Base_Widget` + `Skin_Content_Base` |
| `get_name()` | `woocommerce-product-content` |
| `get_title()` | `Product Content` |
| Controls | inherited from `Post_Content` — single **Style** section: `align`, `text_color` (global TEXT color), `typography` (global TEXT typography) |
| `render()` | `render_post_content( false, false )` — runs `the_content` for the current post, skips post-CSS print |

EA reproduces the parent's three style controls and the `the_content` render path directly (Pro's `render_post_content()` is Pro-only, so we replicate its behaviour with `setup_postdata()` + `apply_filters('the_content', …)` + `wp_reset_postdata()`).

## Pro vs Lite

| Capability | Lite (planned) | Elementor Pro |
| ---------- | -------------- | ------------- |
| Renders product long description (`the_content`) | ✅ | ✅ |
| Shortcode / `wpautop` / oEmbed processing | ✅ (`the_content` filter) | ✅ |
| Alignment (responsive, RTL-aware) | ✅ | ✅ |
| Text color (global TEXT default) | ✅ | ✅ |
| Typography (global TEXT default) | ✅ | ✅ |
| Editor placeholder when no product context | ✅ | — (Pro assumes product context) |
| WC-inactive editor warning | ✅ `eael_wc_notice_controls()` | — |
| `has_widget_inner_wrapper()` / `e_optimized_markup` | ❌ not managed | ✅ |

## File Map

| File | Role |
| ---- | ---- |
| `includes/Elements/Woo_Product_Description.php` | PHP widget class — controls + render with editor/frontend branch *(to create)* |
| `includes/Classes/Helper.php` → `Helper::get_product()` | Product context resolution — falls back to global `$product` |
| `src/css/view/woo-product-description.scss` | Source styles — minimal wrapper reset *(to create)* |
| `config.php` entry `'woo-product-description'` | Asset declaration — **single CSS only, NO JS** *(to add)* |
| `assets/front-end/css/view/woo-product-description.min.css` | Built output *(after `npm run build`)* |

## Architecture

- **Render emits the filtered post body.** `Helper::get_product()` resolves the product; the post object (`get_post( $product->get_id() )`) supplies `post_content`, which is run through `apply_filters( 'the_content', … )` so all standard WP content filters fire — same end result as Pro's `render_post_content()`.
- **`setup_postdata()` / `wp_reset_postdata()` bracket the render.** Some `the_content` filters read the global `$post`; the widget sets it up for the product post and resets afterward so it does not corrupt the surrounding Theme Builder loop.
- **`global $product = Helper::get_product()`** — shared single-product context pattern across EA Woo widgets.
- **Editor placeholder** — when `Plugin::$instance->editor->is_edit_mode()` OR `get_post_type() === 'templately_library'` and no product, renders a static placeholder `<p>` so style controls preview correctly.
- **Silent frontend exit** when no `$product` context (non-product page) — no admin notice (same as Short Description / Woo_Product_Price).
- **No `do_action` / `apply_filters` of its own** planned — extension surface is CSS overrides only (plus the WP-core `the_content` filter it consumes).

## Render Output

### Frontend (real product context)

```html
<div class="eael-single-product-description">
  <!-- apply_filters('the_content', $product_post->post_content) output: -->
  <p>{paragraph from product long description}</p>
  ...shortcodes, oEmbeds, blocks rendered...
</div>
```

Empty when the product's `post_content` is blank (graceful — no placeholder on frontend).

### Editor preview (no product context)

```html
<div class="eael-single-product-description">
  <p>This is a product description placeholder. Add a long description
     (the main editor content) to your product to see it here.</p>
</div>
```

## Controls Reference

Source `register_controls()` is the truth once built. All control IDs use the `eael_product_description` prefix.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_product_description_align` | CHOOSE (responsive) | empty | Style → Description | `text-align` on `.eael-single-product-description` — start / center / end / justify; RTL-aware via `selectors_dictionary`; `classes => elementor-control-start-end` |
| `eael_product_description_text_color` | COLOR | global `COLOR_TEXT` | Style → Description | `color` on `.eael-single-product-description` |
| `eael_product_description_typography` | GROUP_TYPOGRAPHY | global `TYPOGRAPHY_TEXT` | Style → Description | typography on `.eael-single-product-description` |

No Content tab (matches Pro). No `eael_section_pro` upsell panel.

## Conditional Dependencies

```text
# WC-inactive guard
eael_global_warning section            → shown only when WC() is unavailable; register_controls() returns early
All style controls                     → registered only when WooCommerce is active

# Frontend gates
Entire output                          → empty when WooCommerce inactive (early return)
Silent return                          → when Helper::get_product() is false AND not in editor (non-product page)
Editor placeholder                     → static <p> when in editor / templately_library with no product
Empty render                           → when product exists but post_content is blank
```

## Hooks & Filters

| Hook | Type | Purpose |
| ---- | ---- | ------- |
| `Helper::get_product()` (consumed) | — | Returns `wc_get_product($id)`; falls back to global `$product` |
| `the_content` (consumed) | filter | WP-core content filter — runs `wpautop`, `do_shortcode`, oEmbed, block rendering on the product long description |

⚠️ Emits **zero** `do_action` / `apply_filters` of its own (planned) — no extension surface beyond CSS + the consumed `the_content` filter.

## JavaScript Lifecycle

**N/A — pure CSS widget**, no JavaScript file planned in `config.php`. Static server-render only; no DOM interaction, no AJAX.

## Common Issues

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns false; render exits silently with no editor warning.
- **Diagnose:** blank on frontend; editor shows the placeholder paragraph.
- **Fix:** use on a Single Product Theme Builder template, or provide product context via a Loop Grid.

### Description renders as raw text with visible shortcodes / no paragraphs

- **Likely cause:** content echoed without the `the_content` filter.
- **Diagnose:** `[shortcode]` literals or unwrapped text appear.
- **Fix:** ensure render uses `apply_filters( 'the_content', $post->post_content )`, not raw `post_content`.

### Surrounding Theme Builder widgets show the wrong post after this widget

- **Likely cause:** `setup_postdata()` called without a matching `wp_reset_postdata()`.
- **Diagnose:** widgets placed after Product Description in the template pull this product's data.
- **Fix:** confirm `wp_reset_postdata()` runs after the content echo.

### Color / typography overridden by theme

- **Likely cause:** theme CSS targets the content elements with higher specificity.
- **Diagnose:** inspect computed styles on `.eael-single-product-description`.
- **Fix:** known theme-interference pattern; raise selector specificity if needed (decided at verify step).

## Known Limitations

- **Renders the full post body only** — no read-more / trim / word-limit control (matches Pro; out of scope).
- **No "style affected by theme" alert** — Pro's Product Content has none either (unlike Short Description); intentionally omitted for parity.
- **Silent frontend exit on no `$product`** — no editor warning, no admin notice.
- **No `wpml_object_id` filter** — product ID isn't translated; cross-language sites may render the wrong product's description.
- **No `is_dynamic_content()` override** — Elementor render cache may freeze the description HTML; dynamic content changes won't reflect until cache expires.
- **`has_widget_inner_wrapper()` / `e_optimized_markup` not handled** — uses the default EA wrapper (EA Lite convention).

## Implementation Checklist

See [`PRD-Woo-Product-Description`](../../../../../PRD/PRD-Woo-Product-Description.md) §11 for the ordered build steps. Summary:

1. Create `includes/Elements/Woo_Product_Description.php` (copy `Woo_Product_Short_Description.php`, swap render to `the_content`).
2. Create `src/css/view/woo-product-description.scss`.
3. Register `'woo-product-description'` in `config.php`.
4. `npm run build`.
5. Verify editor + frontend on the wp-env test site (Single Product template; product body with a shortcode).
6. Replace the "to create / after build" notes in this doc with real line references.
