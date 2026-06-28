# Woo Product Title Widget

> ℹ️ **Implemented (Lite-only, no Pro dependency).** Class, SCSS, and `config.php` registration are in place; the widget renders with zero Pro/license gating. PRD (with Bangla rationale, kept outside the repo): `eadev/PRD/PRD-Woo-Product-Title.md`.
>
> Renders the current WooCommerce product's name as a heading (`h1`–`h6` / `div` / `span` / `p`) inside a single-product context — modelled on Elementor Pro's `Product Title`, but built free on `Widget_Base` instead of extending `Widget_Heading`. Emits the WC/theme-standard `product_title entry-title` classes. Likely **pure-CSS widget — no JS**, since style is driven entirely by Elementor selector controls.

**Class file:** [`includes/Elements/Woo_Product_Title.php`](../../includes/Elements/Woo_Product_Title.php)
**Slug:** `woo-product-title` (widget id `eael-woo-product-title`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-title>
**Pro-shared:** ❌ No — Lite-only. No `eael_section_pro` upsell planned; no `eael/pro_enabled` gate.

---

## Overview

A thin single-product display widget: it resolves the current product via `Helper::get_product()` and prints its name inside a configurable heading tag. Default tag is `h1` (single-product pages conventionally carry the product name as the page's primary heading, mirroring WC's own `woocommerce_template_single_title()` output). The widget exposes HTML tag, alignment, color, typography, text-shadow and blend-mode controls, plus an optional link wrap (product permalink or a custom URL). The `product_title` + `entry-title` classes are emitted verbatim so existing WC/theme CSS that targets WooCommerce's native title continues to apply.

Unlike Elementor Pro's `Product_Title` — which extends core `Widget_Heading` and sources the name from the Pro-only `woocommerce-product-title-tag` dynamic tag — the free EA version renders the name directly in `render()`, because that dynamic tag is not available in Lite.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Render current product name as a heading | ✅ | ✅ |
| HTML tag select (h1–h6, div, span, p), default `h1` | ✅ | ✅ |
| Alignment, color, typography, text-shadow, blend mode | ✅ | ✅ |
| Optional link wrap (product permalink / custom URL) | ✅ | ✅ (inherited Heading `link`) |
| `product_title` + `entry-title` classes | ✅ | ✅ |
| Editor preview when no product context | ✅ — "Product Title" placeholder | ✅ — dynamic-tag preview |
| Source of the title text | direct `$product->get_name()` in `render()` | `woocommerce-product-title-tag` dynamic tag (Pro-only) |
| `eael_section_pro` upsell panel | ❌ — none planned | — |

> This is an EA Lite-native widget, not a Lite-stub-extended-by-Pro one. There is no planned Pro injection hook; EA Pro does not need to extend it.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Title.php`](../../includes/Elements/Woo_Product_Title.php) | PHP widget class — metadata, controls, render with editor/frontend branch |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L2019) | `Helper::get_product()` — resolves product context, falls back to global `$product` / `wc_get_product()` |
| [`src/css/view/woo-product-title.scss`](../../src/css/view/woo-product-title.scss) | Source styles — minimal title-wrapper reset; most styling comes from selector controls |
| [`config.php`](../../config.php) entry `'woo-product-title'` | `Asset_Builder` dependency declaration — single CSS, no JS |
| `assets/front-end/css/view/woo-product-title.min.css` | Built output (do not edit) |

No JS file planned. No vendor libraries. No `get_style_depends()` beyond Elementor's default FA registration (icons are not used by this widget).

## Architecture

- **Built on `\Elementor\Widget_Base`, not `Widget_Heading`** — Elementor Pro's widget extends `Widget_Heading` to inherit every heading control for free. EA Lite's every Woo widget extends `Widget_Base`, so this one follows suit for consistency (shared `eael_wc_notice_controls()`, `Helper::get_product()`, editor-mockup pattern). The trade-off is re-declaring the heading-style controls (tag, align, color, typography, text-shadow, blend mode) by hand — all standard Elementor group controls, low cost.
- **Title text sourced directly, not via dynamic tag** — `render()` calls `Helper::get_product()->get_name()`. The Pro `woocommerce-product-title-tag` dynamic tag is unavailable in Lite, so there is no `title` control to override; the name is computed at render time.
- **`product_title` + `entry-title` classes are emitted verbatim** — WC core (`single-product/title.php` → `<h1 class="product_title entry-title">`) and most themes style the product title via these classes. Reusing them keeps third-party CSS working. This mirrors Pro's `render()` which adds the same two classes to the heading element.
- **HTML tag is whitelist-validated before printing** — `header_size` is sanitised through `Helper::eael_validate_html_tag()` ([Helper.php:1115](../../includes/Classes/Helper.php#L1115); allow-list `EAEL_ALLOWED_HTML_TAGS` includes `h1`–`h6`, `div`, `span`, `p`) so an arbitrary/injected tag string can never reach output — an unknown tag falls back to `div`.
- **Editor preview falls back to a placeholder** — when no product context exists (e.g. dropped on a non-product page in the editor), `render()` prints a `Product Title` placeholder so style controls remain testable; on the frontend with no product it returns silently (same contract as `Woo_Product_Price`).

## Render Output

### Frontend (real product context)

```html
<div class="eael-woo-product-title">          <!-- flex row: prefix · title · suffix -->
  [?] <span class="eael-product-title-prefix eael-product-title-prefix-text">{prefix_text}</span>
      <!-- OR -->
  [?] <span class="eael-product-title-prefix eael-product-title-prefix-icon">{Icons_Manager::render_icon(prefix_icon)}</span>

  <h1 class="product_title entry-title">
    [?] <a href="{product_permalink | custom_url}">  <!-- when link switcher on -->
          {product->get_name()}
        </a>
    <!-- OR, when link off: -->
    {product->get_name()}
  </h1>

  [?] <span class="eael-product-title-suffix eael-product-title-suffix-text">{suffix_text}</span>
      <!-- OR -->
  [?] <span class="eael-product-title-suffix eael-product-title-suffix-icon">{Icons_Manager::render_icon(suffix_icon)}</span>
</div>
```

- The heading tag (`h1` shown) is the validated `header_size` value.
- `[?]` `<a>` wrap appears only when `eael_product_title_link == 'yes'`.
- `[?]` prefix appears only when `show_prefix == 'yes'` (text or icon per `prefix_content`); suffix likewise. An empty text / unset icon renders nothing even when the switch is on.

### Editor preview (no product context)

```html
<div class="eael-woo-product-title">
  <h1 class="product_title entry-title">Product Title</h1>
</div>
```

Notes:

- Single render path — the same `render()` runs in editor and frontend; only the title text differs (placeholder vs `$product->get_name()`). No separate editor mockup branch, no editor-only wrapper.
- `.eael-woo-product-title` is `display:flex; align-items:center; flex-wrap:wrap; gap:10px` so prefix/suffix sit on the same baseline as the title. `align` drives `justify-content` on the wrapper (positions the group) and `text-align` on `.product_title` (aligns wrapped title text).

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Title.php#L52) is the truth; [`render()`](../../includes/Elements/Woo_Product_Title.php#L260) drives output.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `header_size` | SELECT | `h1` | Content → Content | Output HTML tag — `h1`–`h6`, `div`, `span`, `p` |
| `eael_product_title_link` | SWITCHER | empty | Content → Content | Wrap title in an anchor |
| `eael_product_title_link_type` | SELECT | `product` | Content → Content | `product` (permalink) or `custom` URL |
| `eael_product_title_custom_link` | URL | empty | Content → Content | Custom link target |
| `show_prefix` | SWITCHER | empty | Content → Content | Show a prefix block before the title |
| `prefix_content` | CHOOSE | `text` | Content → Content | `text` or `icon` |
| `prefix_text` | TEXT | `New` | Content → Content | Prefix text (`wp_kses` allowed tags) |
| `prefix_icon` | ICONS | `fas fa-star` | Content → Content | Prefix icon |
| `show_suffix` | SWITCHER | empty | Content → Content | Show a suffix block after the title |
| `suffix_content` | CHOOSE | `text` | Content → Content | `text` or `icon` |
| `suffix_text` | TEXT | `Sale` | Content → Content | Suffix text (`wp_kses` allowed tags) |
| `suffix_icon` | ICONS | `fas fa-tag` | Content → Content | Suffix icon |
| `align` | CHOOSE (responsive) | — | Style → Title | `justify-content` on wrapper + `text-align` on `.product_title` |
| `title_color` | COLOR | — | Style → Title | Title color |
| `typography` | GROUP_TYPOGRAPHY | — | Style → Title | Title typography |
| `text_shadow` | GROUP_TEXT_SHADOW | — | Style → Title | Title text shadow |
| `blend_mode` | SELECT | — | Style → Title | CSS `mix-blend-mode` |
| Container sub-group (`Container` heading) | DIMENSIONS / COLOR / BORDER | — | Style → Title | `.eael-woo-product-title` wrapper: margin, padding, background color, border (group), border-radius — under a `Container` HEADING inside the Title section |
| Prefix / Suffix style sections | various | — | Style → Prefix, Style → Suffix | Text variant: color, typography, margin, padding (gated `*_content == 'text'`). Icon variant: size, color, margin, padding (gated `*_content == 'icon'`). Whole section gated on `*_show_* == 'yes'`. |

Both Prefix/Suffix style sections are generated by `register_affix_style_controls( $type )` (one helper, called for `prefix` + `suffix`), so the two sets are identical bar the `eael_product_title_{prefix|suffix}_*` ID stem. Selector stems: `.eael-product-title-{type}-text` (text), `.eael-product-title-{type}-icon i|svg` (icon glyph), `.eael-product-title-{type}-icon` (icon box margin/padding).

Plus `eael_global_warning` (RAW_HTML) shown only when WooCommerce is inactive.

## Conditional Dependencies

```text
eael_product_title_link_type    → visible when eael_product_title_link == 'yes'
eael_product_title_custom_link  → visible when eael_product_title_link == 'yes' AND eael_product_title_link_type == 'custom'

prefix_content                  → visible when show_prefix == 'yes'
prefix_text                     → visible when show_prefix == 'yes' AND prefix_content == 'text'
prefix_icon                     → visible when show_prefix == 'yes' AND prefix_content == 'icon'

suffix_content                  → visible when show_suffix == 'yes'
suffix_text                     → visible when show_suffix == 'yes' AND suffix_content == 'text'
suffix_icon                     → visible when show_suffix == 'yes' AND suffix_content == 'icon'

# Frontend gates
Entire control set              → replaced by WooCommerce-inactive warning when WC not active
Silent return                   → frontend, when Helper::get_product() returns false (no $product context)
Editor placeholder              → "Product Title" shown in editor when no product context
```

No `eael_section_pro` upsell panel planned.

## Hooks & Filters

> N/A (planned) — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. It consumes `Helper::get_product()` (which internally uses WC's `wc_get_product()`), `get_permalink()`, and the product's `get_name()`. Extension is via CSS overrides only.

## JavaScript Lifecycle

> N/A — pure-CSS widget, no JavaScript planned. The widget declares no JS dependency in `config.php` and registers no Elementor frontend `addAction`. Static server-render only.

## Common Issues

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns false; `render()` exits silently on the frontend with no editor warning.
- **Diagnose:** widget is blank on frontend; editor shows the "Product Title" placeholder.
- **Fix:** use inside a Single Product Theme Builder template, or pass product context via a Loop Grid item.

### Heading shows the parent name on a variation page

- **Likely cause:** `Helper::get_product()` resolves `product_variation` to its parent; `get_name()` returns the parent product's name.
- **Diagnose:** URL is a variation, but the rendered name is the parent product.
- **Fix:** expected behaviour — WC product titles are owned by the parent product.

### Two `h1` elements on the product page

- **Likely cause:** `header_size` left at default `h1` while the theme/WC template also outputs an `h1` product title.
- **Diagnose:** view source; count `<h1>` on the single-product template.
- **Fix:** set this widget to `h1` and remove/hide the theme's native title, or drop this widget to `h2`.

### Link wrap points to the wrong URL

- **Likely cause:** `eael_product_title_link_type` is `custom` but `eael_product_title_custom_link` is empty, or `product` mode on a page where `Helper::get_product()` resolves a different product than expected.
- **Diagnose:** inspect the rendered `<a href>`.
- **Fix:** set the custom URL, or verify the product context the template provides.

## Known Limitations

- **No dynamic-tag source** — unlike Pro, the title is not exposed as an Elementor dynamic tag; other widgets can't reuse it as a dynamic value.
- **`header_size` tag list is fixed** — only `h1`–`h6`, `div`, `span`, `p` are whitelisted; arbitrary tags are intentionally rejected.
- **Render exits silently on no `$product`** — no editor warning, no admin notice on the frontend (matches `Woo_Product_Price`).
- **No `wpml_object_id` translation** — the resolved product ID is not translated; multilingual sites may render the source-language product on the wrong language route.
- **No `is_dynamic_content()` override** — Elementor render cache may freeze the title; a renamed product won't reflect until cache clears.
- **No per-instance heading-level a11y guard** — nothing prevents emitting a second `h1` if the theme already outputs one; document outline correctness is the builder's responsibility.
