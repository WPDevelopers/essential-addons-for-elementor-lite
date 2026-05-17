# Woo Product Price Widget

> Renders WC's `single-product/price.php` template on any page with `$product` context — adds prefix + suffix (text or icon) blocks above/below WC's native price markup. **Pure CSS widget — no JS file**, 46-line SCSS. Hardcoded editor preview mockup ($80 strike → $50 sale). **Zero hooks emitted**. Lite-only widget; no Pro extension.

**Class file:** [`includes/Elements/Woo_Product_Price.php`](../../includes/Elements/Woo_Product_Price.php)
**Slug:** `woo-product-price` (widget id `eael-woo-product-price`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-price>
**Pro-shared:** ❌ No — Lite-only. Zero `do_action` / `apply_filters` emitted. No `eael/pro_enabled` checks. No `eael_section_pro` upsell panel.

---

## Overview

Thin widget: 926 PHP lines (mostly style controls — typography + color + spacing for regular price, sale price, currency symbols, prefix, suffix), 46 SCSS lines, **NO JS**. `render()` delegates to WC's `wc_get_template('/single-product/price.php')` on frontend; editor renders a hardcoded "$80.00 → $50.00" mockup. Adds optional Prefix + Suffix (text or icon) sibling elements that flank WC's price markup.

Two layout flexbox controls:
- **Sale Price Position** — `row` (default; sale after regular) or `row-reverse` (sale before regular)
- **Stacked** switcher — flex-wraps `del` + `ins` to 100% width each

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Renders WC's native `single-product/price.php` | ✅ | ✅ |
| Hardcoded editor preview mockup ($80 / $50) | ✅ | ✅ |
| Prefix / Suffix blocks (text or icon) | ✅ | ✅ |
| Sale Price Position (row / row-reverse) | ✅ | ✅ |
| Stacked layout switcher | ✅ | ✅ |
| Style sections: regular price, currency symbol, sale price, prefix, suffix | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro-specific features | — | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Price.php`](../../includes/Elements/Woo_Product_Price.php) | PHP widget class (926 lines) — controls, render with editor/frontend branch |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L1998) | `Helper::get_product()` — falls back to global `$product` |
| [`src/css/view/woo-product-price.scss`](../../src/css/view/woo-product-price.scss) | Source styles (46 lines) — minimal price wrapper + prefix/suffix spacing |
| [`config.php`](../../config.php#L667) entry `'woo-product-price'` | Asset declaration — **single CSS only, NO JS** |
| `assets/front-end/css/view/woo-product-price.min.css` | Built output |

No `get_style_depends()` declared — relies on Elementor's default FA registration for icon picker.

## Architecture

- **Render delegates to WC's `wc_get_template('/single-product/price.php')`** ([line 902](../../includes/Elements/Woo_Product_Price.php#L902)) — WC's own template emits the canonical `<p class="price"><del>…</del><ins>…</ins></p>` markup. EA widget only adds the `.eael-single-product-price` wrapper and optional Prefix / Suffix sibling elements.
- **Editor preview is hardcoded mockup** ([line 816-873](../../includes/Elements/Woo_Product_Price.php#L816)) — when `Plugin::$instance->editor->is_edit_mode()` OR `get_post_type() === 'templately_library'`, renders fixed markup with `$80.00` strike + `$50.00` sale, currency symbol from `get_woocommerce_currency_symbol()`. **Hardcoded English numerals** are i18n-translatable via `esc_html_e('80.00', '…')` (translator overhead for fixed mockup data).
- **`Helper::get_product()` fallback to global `$product`** — when no product context, render exits silently at [line 876-878](../../includes/Elements/Woo_Product_Price.php#L876). No editor warning shown (same pattern as Woo_Add_To_Cart + Woo_Product_Images).
- **Two flex-layout controls drive the price arrangement:**
  - `sale_price_position` (`row` / `row-reverse`) sets `flex-direction` on `.price`
  - `stacked_price` switcher adds `flex-wrap: wrap` + `display: block; flex: 1 1 100%` on `del` + `ins`
- **Prefix / Suffix system** — `show_prefix` + `show_suffix` switchers; `prefix_content` + `suffix_content` CHOOSE between `text` (TEXT input) or `icon` (ICONS picker); rendered in same conditional structure both inside editor + frontend branches (code duplication).
- **Both code paths (editor mockup + frontend) duplicate Prefix/Suffix rendering** ([line 820-872 + 882-922](../../includes/Elements/Woo_Product_Price.php#L820)) — same switch/case block emitted twice. Maintenance burden if prefix/suffix structure changes.
- **No FA4 → FA5 icon migration shim** — uses `Controls_Manager::ICONS` directly. Pre-2.5 saved widgets with FA4 string icons may not migrate cleanly.
- **ZERO `do_action` / `apply_filters` emitted** — no extension surface beyond CSS overrides. Smallest hook footprint of any EA Woo widget.

## Render Output

### Frontend (real product context)

```html
<div class="eael-single-product-price">
  [?] <div class="prefix-price-text"><span>{prefix_text}</span></div>
      <!-- OR -->
  [?] <div class="prefix-price-icon">{Icons_Manager::render_icon(prefix_icon)}</div>

  <!-- WC's wc_get_template('/single-product/price.php') output: -->
  <p class="price" style="flex-direction: {row|row-reverse}; flex-wrap: {wrap when stacked}">
    [?] <del aria-hidden="true">
          <span class="woocommerce-Price-amount amount">
            <bdi>
              <span class="woocommerce-Price-currencySymbol">{$}</span>
              {regular_price}
            </bdi>
          </span>
        </del>
    [?] <ins>  <!-- when on sale: -->
          <span class="woocommerce-Price-amount amount">
            <bdi>
              <span class="woocommerce-Price-currencySymbol">{$}</span>
              {sale_price}
            </bdi>
          </span>
        </ins>
  </p>

  [?] <div class="suffix-price-text"><span>{suffix_text}</span></div>
      <!-- OR -->
  [?] <div class="suffix-price-icon">{Icons_Manager::render_icon(suffix_icon)}</div>
</div>
```

### Editor preview mockup

```html
<div class="eael-single-product-price">
  [?] {Prefix block — same as frontend}

  <div class="eael-product-price-edit">          <!-- editor-only wrapper -->
    <p class="price">
      <del aria-hidden="true">
        <span class="woocommerce-Price-amount amount">
          <bdi><span class="woocommerce-Price-currencySymbol">{$}</span>80.00</bdi>
        </span>
      </del>
      <ins aria-hidden="true">
        <span class="woocommerce-Price-amount amount">
          <bdi><span class="woocommerce-Price-currencySymbol">{$}</span>50.00</bdi>
        </span>
      </ins>
    </p>
  </div>

  [?] {Suffix block — same as frontend}
</div>
```

Notes:

- Editor uses an extra `.eael-product-price-edit` wrapper around `<p class="price">`; frontend doesn't — selector mismatch in style controls. Most controls target both `.eael-single-product-price .price` (frontend) + `.eael-product-price-edit` (editor justify-content for alignment).
- `aria-hidden="true"` on both `<del>` + `<ins>` in editor — same as frontend WC output but mockup numerals aren't real data.
- Selectors use `.woocommerce-Price-amount`, `.woocommerce-Price-currencySymbol` — WC-native class names; widget styling relies on theme NOT overriding these classes.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Price.php#L55) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `sale_price_position` | SELECT | `row` | Content → Content | `flex-direction` on `.price` — `row` (sale after regular) / `row-reverse` (sale before regular) |
| `stacked_price` | SWITCHER | empty | Content → Content | `flex-wrap: wrap` on `.price`; `display: block; flex: 1 1 100%` on `del` + `ins` |
| `show_prefix` | SWITCHER | empty | Content → Content | Show prefix block |
| `prefix_content` | CHOOSE | `text` | Content → Content | `text` or `icon` |
| `prefix_text` | TEXT (AI) | `Limited Time Offer` | Content → Content | Prefix text |
| `prefix_icon` | ICONS | `fas fa-fire` | Content → Content | Prefix icon |
| `show_suffix` | SWITCHER | empty | Content → Content | Show suffix block |
| `suffix_content` | CHOOSE | `text` | Content → Content | `text` or `icon` |
| `suffix_text` | TEXT (AI) | `Sales Ongoing` | Content → Content | Suffix text |
| `suffix_icon` | ICONS | `fas fa-pepper-hot` | Content → Content | Suffix icon |
| Style → Price section | various | — | Style tab | Regular price color, alignment, typography, text-decoration-color |
| Style → Price Currency Symbol | various | — | Style tab | Currency symbol color, typography, spacing |
| Style → Sale Price section | various | — | Style tab | Sale price color, typography |
| Style → Sale Currency Symbol | various | — | Style tab | Sale price currency symbol styling |
| Style → Prefix section | various | — | Style tab | Prefix block typography, color, background, border, padding |
| Style → Suffix section | various | — | Style tab | Suffix block typography, color, background, border, padding |

## Conditional Dependencies

```text
# Prefix / Suffix gating
prefix_content                          → visible when show_prefix == 'yes'
prefix_text                             → visible when show_prefix == 'yes' AND prefix_content == 'text'
prefix_icon                             → visible when show_prefix == 'yes' AND prefix_content == 'icon'

suffix_content                          → visible when show_suffix == 'yes'
suffix_text                             → visible when show_suffix == 'yes' AND suffix_content == 'text'
suffix_icon                             → visible when show_suffix == 'yes' AND suffix_content == 'icon'

# Style sections
Prefix style section                    → visible when show_prefix == 'yes'
Suffix style section                    → visible when show_suffix == 'yes'

# Frontend gates
Entire output                           → empty when WooCommerce inactive
Silent return                           → when Helper::get_product() returns false (no $product context)
Editor preview                          → hardcoded mockup ($80/$50) when in editor or templately_library
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

⚠️ **ZERO `do_action` / `apply_filters` emitted** by this widget — minimum extension surface in EA Woo set.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `Helper::get_product()` (consumed) | — | `(int\|bool $id)` | Returns `wc_get_product($id)`; falls back to global `$product` |
| WC-native: `wc_get_template('/single-product/price.php')` | template (consumed) | — | WC's canonical price markup; extends via WC's own template hooks (`woocommerce_get_price_html`, etc.) |
| Implicit WC filters | filter (consumed via template) | various | All WC core price filters fire inside the included template — `woocommerce_get_price_html`, `formatted_woocommerce_price`, `wc_price`, etc. |

No `eael/pro_enabled`, no `eael_section_pro`. No shared patterns from [`_patterns.md`](_patterns.md) apply.

## JavaScript Lifecycle

**N/A — pure CSS widget**, no JavaScript file declared in [`config.php`](../../config.php#L667). Static server-render only; no DOM interaction, no AJAX, no Elementor frontend hook listener.

## Common Issues

### Widget shows nothing on a non-product page

- **Likely cause:** `Helper::get_product()` returns false; render exits silently at [line 876-878](../../includes/Elements/Woo_Product_Price.php#L876) with no editor warning
- **Diagnose:** widget appears blank on frontend; editor shows mockup ($80/$50)
- **Fix:** use on Single Product theme builder template; or pass a product context via theme builder Loop Grid

### Sale price doesn't show even when product is on sale

- **Likely cause:** product's `_sale_price` post meta is empty / cleared by WC; the rendered template only emits `<ins>` when sale_price exists. Sale Price Position control affects flex direction even when no sale price — but invisible.
- **Diagnose:** check product → Edit → Inventory → "Sale price" field is set
- **Fix:** set sale price in WC product admin

### Sale and regular price overlap when Stacked is enabled

- **Likely cause:** `stacked_price` adds `flex: 1 1 100%` on both `del` + `ins`; if container width is too small, items may stack incorrectly across browsers
- **Diagnose:** inspect `.price` computed style; verify `flex-wrap: wrap` is applied
- **Fix:** known flex layout limitation; widen container or check theme CSS interference

### Prefix/Suffix text contains HTML entities that get stripped

- **Likely cause:** `wp_kses($settings['prefix_text'], Helper::eael_allowed_tags())` filters output — allows only EA's allowed tags whitelist
- **Diagnose:** check `Helper::eael_allowed_tags()` return for permitted HTML
- **Fix:** use only tags in the allowlist (basic inline formatting only)

### Currency symbol position panel control doesn't exist

- **Likely cause:** WC's currency symbol position is controlled site-wide via WC settings (`woocommerce_currency_pos` option: left, right, left_space, right_space). EA widget can't override per-instance.
- **Diagnose:** WP admin → WooCommerce → Settings → General → Currency Position
- **Fix:** change site-wide via WC settings; or filter `woocommerce_currency_position` programmatically

### Editor preview always shows $80/$50 regardless of selected product

- **Likely cause:** Editor render is a hardcoded mockup ([line 816-873](../../includes/Elements/Woo_Product_Price.php#L816)) — does NOT fetch real product data
- **Diagnose:** intentional — preview shows mockup so styling controls work without a product context
- **Fix:** known behavior; preview ≠ frontend output

## Known Limitations

- **Zero extension hooks** — widget emits no `do_action` / `apply_filters`. No way for Pro / 3rd party to inject controls or alter render output beyond CSS.
- **Editor mockup hardcoded English numerals `$80.00` / `$50.00`** — i18n-wrapped (`esc_html_e`) so they pollute the `.pot` catalog as translatable strings. Currency symbol pulled from `get_woocommerce_currency_symbol()` (locale-aware) but numerals are fixed English digits — won't auto-localize to Arabic, Bengali, Devanagari, etc. numerals.
- **Render exits silently on no `$product`** ([line 876-878](../../includes/Elements/Woo_Product_Price.php#L876)) — no editor warning, no admin notice.
- **Editor vs frontend selector mismatch** — editor wraps `<p class="price">` in `.eael-product-price-edit`; frontend doesn't. Most style controls target both selectors to cover; if a future control only targets one selector, editor/frontend will diverge visually.
- **Prefix / Suffix render block duplicated in editor + frontend branches** ([line 820-872 vs 882-922](../../includes/Elements/Woo_Product_Price.php#L820)) — same switch/case structure emitted twice. Maintenance burden if structure changes.
- **No FA4 → FA5 icon migration shim** — `Controls_Manager::ICONS` only; legacy FA4 saved settings may not migrate cleanly. No fallback control pair.
- **`wp_kses` with `Helper::eael_allowed_tags()` restricts prefix/suffix HTML** — users can't embed custom markup (e.g. `<svg>` inline) without expanding the allowlist.
- **No way to hide regular price OR sale price independently** — WC template emits both unconditionally when both exist. To hide one, user must CSS-hide via custom code.
- **No per-instance currency position control** — WC site-wide setting drives currency symbol placement (before/after price). Widget can't override.
- **`get_style_depends()` not declared** — relies on Elementor's default FA registration. If FA isn't loaded by Elementor on the page (rare), icons silently miss.
- **No `wpml_object_id` filter** — product ID isn't translated; cross-language sites may render the wrong product's price.
- **No `is_dynamic_content()` override** — Elementor's render cache may freeze price HTML; live price changes (currency rate, dynamic pricing plugins) won't reflect until cache expires.
- **No keyboard / a11y considerations** — `aria-hidden="true"` on `del` + `ins` matches WC's convention (assistive tech reads price as joined text), but the prefix/suffix labels don't get `aria-label` association with the price.
- **Sale price flow visually breaks RTL when `row-reverse` selected** — combined `flex-direction: row-reverse` + theme `direction: rtl` produces double-reversal. No RTL handling.
