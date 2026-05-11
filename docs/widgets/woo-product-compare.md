# Woo Product Compare Widget

> Static side-by-side comparison table of manually-selected WooCommerce products. Class is a thin shell (115 lines); virtually all logic lives in the shared `Woo_Product_Comparable` trait (2330 lines) reused by Product_Grid + Woo_Product_List for their AJAX-modal comparison flow. **No JS file** — pure server-rendered table. 7 visual presets, 9 default field types + WC product attribute taxonomies.

**Class file:** [`includes/Elements/Woo_Product_Compare.php`](../../includes/Elements/Woo_Product_Compare.php)
**Slug:** `woo-product-compare` (widget id `eael-woo-product-compare`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/woo-product-compare/>
**Pro-shared:** ❌ No widget-specific Pro extension surface in this widget; the parent trait `Woo_Product_Comparable` exposes 4 `do_action('eael/wcpc/*-controls', $this)` injection points for Pro to extend, but no Pro-only fields / themes / layouts are currently gated.

---

## Overview

Smallest WooCommerce widget in EA Lite by class line count (115 lines). The widget itself only declares metadata + delegates everything to the `Woo_Product_Comparable` trait. The trait is **shared with Product_Grid + Woo_Product_List + (Pro) Woo_Product_Carousel** — those widgets reuse `print_compare_button()` + `get_compare_table()` AJAX handler to show the comparison modal; this standalone widget reuses `render_compare_table()` for static inline rendering.

Differs architecturally from Product_Grid's compare integration:

| Aspect | Standalone Woo_Product_Compare | Product_Grid compare button |
| ------ | ------------------------------- | --------------------------- |
| Product source | Panel-selected `product_ids` Select2 multi-select | Per-product `.eael-wc-compare` button click → `localStorage.productIds` |
| Render path | Server-rendered inline on page load | AJAX `wp_ajax_eael_product_grid` → returns HTML to JS, injected into body-appended `.eael-wcpc-modal` |
| Remove button | Hidden (gated by `self::class` check at trait [line 1929](../../includes/Traits/Woo_Product_Comparable.php#L1929)) | Visible — `.eael-wc-remove` triggers AJAX with `remove_product=1` flag |
| Persistence | Static — same products on every render | `localStorage.productIds` JSON array, JS-managed |

**No JavaScript file** declared in [`config.php`](../../config.php#L1117). The widget is pure CSS + server-render.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| 7 visual presets (default + theme-1..theme-6) | ✅ | ✅ |
| 9 default field types (image, title, price, add-to-cart, description, sku, stock, weight, dimension) + WC product attribute taxonomies | ✅ | ✅ |
| Fields Repeater (order + custom label per field) | ✅ | ✅ |
| Highlighted product (theme-3, theme-4 only) | ✅ | ✅ |
| Ribbon text (theme-4 only) | ✅ | ✅ |
| Repeat "Price" / "Add to cart" at end of table | ✅ | ✅ |
| Linkable product image | ✅ | ✅ |
| 4 `do_action('eael/wcpc/*-controls', $this)` extension points around control sections | ✅ — Pro can inject controls/sections here | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Compare.php`](../../includes/Elements/Woo_Product_Compare.php) | 115-line PHP shell — `use Woo_Product_Comparable;` + metadata; `register_controls()` calls trait section helpers; `render()` calls `render_compare_table()` |
| [`includes/Traits/Woo_Product_Comparable.php`](../../includes/Traits/Woo_Product_Comparable.php) | 2330-line shared trait — controls helpers, `render_compare_table()` static, `get_compare_table()` AJAX handler (used by Product_Grid/List modal, not this widget), `print_compare_button()`, `static_get_products_list()`, `static_fields()`, themes registry, field-types registry, `get_field_types()` (merges `eael/wcpc/default-fields` filter with `get_wc_attr_taxonomies_list()`) |
| [`src/css/view/woo-product-compare.scss`](../../src/css/view/woo-product-compare.scss) | Source styles (566 lines) — table layout, 6 theme variants, ribbon, highlighted column highlight, responsive overflow |
| [`config.php`](../../config.php#L1117) entry `'woo-product-compare'` | Asset declaration — **single CSS only** (no JS) |
| `assets/front-end/css/view/woo-product-compare.min.css` | Built output |

Trait declares `get_style_depends()` returning `['font-awesome-5-all', 'font-awesome-4-shim']` and `get_script_depends() = ['font-awesome-4-shim']` ([Woo_Product_Comparable.php line 21-32](../../includes/Traits/Woo_Product_Comparable.php#L21)) — used by ALL widgets that `use Woo_Product_Comparable` (including Product_Grid).

## Architecture

- **Thin widget; fat trait** — 115 widget lines vs 2330 trait lines. Trait is shared with at least Product_Grid + Woo_Product_List + (Pro) Woo_Product_Carousel. Any change to trait method signatures or filter contracts affects all consumers simultaneously. Same drift risk as `Woo_Cart_Helper`'s static-state pattern but applies to a wider consumer set.
- **Section-helper conditional rendering** — `init_content_product_compare_controls()` ([trait line 144](../../includes/Traits/Woo_Product_Comparable.php#L144)) branches on `$this->get_name()`. When `'eael-woo-product-compare'` (this standalone widget), the section has no `show_compare` conditional gate (always visible) AND adds the `product_ids` Select2 multi-select. For all other consumer widgets (Product_Grid, etc.), the section is conditional on `show_compare == 'yes'` AND skips the Select2 (those widgets show the compare button per-product, no manual list). The shared `show_compare` HIDDEN control is added with default `yes` to keep the conditional uniform.
- **Standalone widget hides remove buttons** — `render_compare_table()` checks `if ('Essential_Addons_Elementor\Elements\Woo_Product_Compare' !== self::class)` at [line 1929](../../includes/Traits/Woo_Product_Comparable.php#L1929) before emitting the remove row. Since the standalone widget renders products from a panel-defined list, removal would be meaningless (settings drive what's shown).
- **`render_compare_table()` is `public static`** ([trait line 1753](../../includes/Traits/Woo_Product_Comparable.php#L1753)) — called both by `Woo_Product_Compare::render()` (static-context server render) AND by `get_compare_table()` AJAX handler (when Product_Grid's modal asks for HTML). Single rendering codepath for both inline + AJAX contexts.
- **`eael-select2` product picker** ([trait line 163](../../includes/Traits/Woo_Product_Comparable.php#L163)) — `source_type=product`, `source_name=post_type`, multi-select. Backend select2 AJAX queries WC products. Same picker pattern used by Login_Register / Product_Grid manual selection.
- **Field types include dynamic WC attribute taxonomies** ([trait line 49](../../includes/Traits/Woo_Product_Comparable.php#L49)) — `get_wc_attr_taxonomies_list()` merges product attribute taxonomies into the field options array. Adding a new WC global attribute = new comparable field, no widget changes.
- **No `eael_section_pro` upsell** — widget has no Pro-only features. Pro extends via the 4 `eael/wcpc/*-controls` injection points around `register_controls()` ([widget line 90-99](../../includes/Elements/Woo_Product_Compare.php#L90)).
- **Hidden control `show_compare`** ([widget line 157-161 via trait](../../includes/Traits/Woo_Product_Comparable.php#L157)) — emitted on this widget with default `yes` to satisfy the trait's universal conditional. On other widgets that use the trait, `show_compare` is a real SWITCHER. Same control id, different visibility — clever code reuse, fragile to refactor.
- **`no_products_found_text` panel control gated by `'eicon-woocommerce' === get_name()`** ([trait line 282](../../includes/Traits/Woo_Product_Comparable.php#L282)) — only Product_Grid (widget id `eicon-woocommerce`) gets this control. Standalone Woo_Product_Compare uses an EMPTY string for `$not_found_text` ([render_compare_table line 1779](../../includes/Traits/Woo_Product_Comparable.php#L1779)) since the control isn't registered. Renders `<td></td>` with no text when no products selected. ⚠️ Inconsistency: standalone widget has no "no products" message control even though it's the most likely consumer to need it.
- **Filter `eael/wcpc/get_product_remove_url`** ([trait line 2086](../../includes/Traits/Woo_Product_Comparable.php#L2086)) — builds URL `?action=eael-wcpc-remove-product&id=<id>` for a removal action; **no PHP handler is registered for this action** in EA Lite. Likely Pro-only or legacy. Standalone widget never emits remove buttons so the URL is unused here.

## Render Output

```html
<!-- eael/wcpc/before_content_wrapper fires here -->
<div class="eael-wcpc-wrapper woocommerce {custom theme-N when theme selected}">

  <!-- eael/wcpc/before_main_table fires here -->
  <table class="eael-wcpc-table table-responsive">
    <tbody>

      <!-- Empty state when no products selected: -->
      [?] <tr class="no-products">
            <td>{$not_found_text — empty string in standalone widget; controllable via panel only on Product_Grid (eicon-woocommerce widget)}</td>
          </tr>

      <!-- Remove row ONLY shown for Product_Grid/List context, NOT standalone Woo_Product_Compare -->
      <!-- (i.e. this branch is suppressed when self::class === Woo_Product_Compare) -->

      <!-- Per-field rows (image, title, price, description, add-to-cart, sku, stock, weight, dimension, attribute_*): -->
      <tr class="{field}">
        <th class="thead {first-th when row 1}">
          <div class="wcpc-table-header">
            [?] {Title <h1>/<h2>/etc. via $title_tag} when field=='image' AND $title not empty
            [?] {Ribbon for theme-4}
            [?] {field icon via Icons_Manager} when $icon set
            <span class="field-name">{field label}</span>
          </div>
        </th>

        <!-- Per-product columns: -->
        <td class="odd|even col_<index> product_<id> {featured when product_id === highlighted_product_id}">
          {field-specific output:
            image     → $product->get_image() — wrapped in <a href> when linkable_img == 'yes'
            title     → $product->get_title()
            price     → $product->get_price_html()
            add-to-cart → woocommerce_template_loop_add_to_cart()
            description → woocommerce_short_description filtered
            stock     → "In stock" / "Out of stock"
            sku       → $product->get_sku() or '-'
            weight, dimension → product attributes
            attribute_<tax> → joined terms via taxonomy lookup
          }
        </td>
        …
      </tr>
      …

      <!-- Optional repeated rows: -->
      [?] <tr class="price repeated">          when repeat_price == 'yes'
      [?] <tr class="add-to-cart repeated">    when repeat_add_to_cart == 'yes'

    </tbody>
  </table>
  <!-- eael/wcpc/after_main_table fires here -->

</div>
<!-- eael/wcpc/after_content_wrapper fires here -->
```

Notes:

- `theme_wrap_class` adds `" custom theme-N"` to wrapper when a theme preset chosen; theme-specific CSS in SCSS targets `.eael-wcpc-wrapper.theme-N`.
- Theme-5 renders title differently inside the row (see [line 1909-1912](../../includes/Traits/Woo_Product_Comparable.php#L1909)) — title inlined into the `thead` cell rather than rendered as a row title cell.
- `featured` class on `<td>` marks the highlighted product column; controlled by `highlighted_product_id` setting (only available when theme-3 or theme-4).
- Field labels go through `wp_kses(_, HelperClass::eael_allowed_tags())` — allows safe HTML in custom labels.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Compare.php#L83) + trait section helpers.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `show_compare` | HIDDEN | `yes` | — | Trait conditional universal gate (always `yes` on this widget; real switcher on other widgets) |
| `product_ids` | EAEL-SELECT2 (multi, source=product) | — | Content → Product Compare | List of product IDs to compare; comma-separated AJAX-searched product picker |
| `highlighted_product_id` | EAEL-SELECT2 (single) | — | Content → Product Compare | Marks one product column with `featured` class (theme-3, theme-4 only) |
| `theme` | SELECT (filterable via `eael/wcpc/default-themes`) | `''` (Theme Default) | Content → Product Compare | One of `''`, `theme-1`..`theme-6`; adds `theme-N` modifier class |
| `ribbon` | TEXT | `"New"` | Content → Product Compare | Ribbon text for theme-4 only |
| `table_title` | TEXT | `"Compare Products"` | Content → Compare Table Settings | Title text rendered in the image-row header cell |
| `table_title_tag` | SELECT | `h1` | Content → Compare Table Settings | Title HTML tag (sanitized via `eael_validate_html_tag`) |
| `fields` (Repeater) | REPEATER | 9 default field rows from `get_default_rf_fields()` | Content → Compare Table Settings | Per-item: `field_type` SELECT (from `get_field_types()` filter), `field_label` TEXT |
| `repeat_price` | SWITCHER | `yes` | Content → Compare Table Settings | Render "Price" row again at end of table |
| `repeat_add_to_cart` | SWITCHER | empty | Content → Compare Table Settings | Render "Add to cart" row again at end of table |
| `linkable_img` | SWITCHER | empty | Content → Compare Table Settings | Wrap product image in `<a>` to permalink |
| `field_icon` | ICONS | — | Content → Compare Table Settings | Icon prepended to every field name in row header |
| Style → Content Section | various | — | Style tab | Table wrapper background, padding, border, box-shadow, typography |
| Style → Table Section | various | — | Style tab | Cell padding, border, header/body typography, highlighted column color, ribbon style, theme tweaks |

## Conditional Dependencies

```text
# Visible conditions
highlighted_product_id           → visible when theme in [theme-3, theme-4]
ribbon                           → visible when theme == 'theme-4'

# Frontend gates
Entire output                    → empty render when WooCommerce inactive
Empty no-products row            → when products_list is empty (no product_ids selected)

# Style sections — many conditioned on theme value for theme-specific styling
# (full set in trait init_style_content_controls / init_style_table_controls)
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/wcpc/before-content-controls` | action (emitted) | `(Widget_Base $this)` | Pro / 3rd-party injects Content-tab sections before standard sections ([widget line 90](../../includes/Elements/Woo_Product_Compare.php#L90)) |
| `eael/wcpc/after-content-controls` | action (emitted) | `(Widget_Base $this)` | Same, after standard Content sections |
| `eael/wcpc/before-style-controls` | action (emitted) | `(Widget_Base $this)` | Same, before standard Style sections |
| `eael/wcpc/after-style-controls` | action (emitted) | `(Widget_Base $this)` | Same, after standard Style sections |
| `eael/wcpc/default-fields` | filter (emitted) | `(array $fields)` | Extend field-type registry ([trait line 39](../../includes/Traits/Woo_Product_Comparable.php#L39)) — 9 EA defaults + WC attribute taxonomies merged |
| `eael/wcpc/default-themes` | filter (emitted) | `(array $themes)` | Extend theme preset list ([trait line 53](../../includes/Traits/Woo_Product_Comparable.php#L53)) |
| `eael/wcpc/default-rf-fields` | filter (emitted) | `(array $default_rows)` | Override Repeater default rows ([trait line 68](../../includes/Traits/Woo_Product_Comparable.php#L68)) |
| `eael/wcpc/rf-fields` | filter (emitted) | `(array $repeater_controls)` | Override Repeater child-controls schema ([trait line 258](../../includes/Traits/Woo_Product_Comparable.php#L258)) |
| `eael/wcpc/products_ids` | filter (emitted) | `(array $product_ids)` | Modify the products list before render ([trait line 1960](../../includes/Traits/Woo_Product_Comparable.php#L1960) + 2205 — fired in both `get_products_list` and `static_get_products_list`) |
| `eael/wcpc/products_list` | filter (emitted) | `(array $products_list)` | Modify the resolved product-data list (with `->fields`) before render |
| `eael/wcpc/products_fields_none` | filter (emitted) | `(array $fields = [])` | Returned when no products / no fields configured |
| `eael/wcpc/products_fields_to_show` | filter (emitted) | `(array $fields_to_show, array $products)` | Final field map (field_type → label) before render |
| `eael/wcpc/compare_field_<field>` | action (emitted, **dynamic name**) | `(array ['product' => $product, 'field' => $field])` | Per-field render hook for **custom field types** registered via filter — Pro can use this to render new field types ([trait line 2036 + 2285](../../includes/Traits/Woo_Product_Comparable.php#L2036)) |
| `eael/wcpc/woocommerce_short_description` | filter (emitted) | `(string $description)` | Modify the short description used for the description field |
| `eael/wcpc/before_content_wrapper` | action (emitted) | — | Pre-wrapper hook in render ([trait line 1771](../../includes/Traits/Woo_Product_Comparable.php#L1771)) |
| `eael/wcpc/before_main_table` | action (emitted) | — | Inside wrapper, before `<table>` |
| `eael/wcpc/after_main_table` | action (emitted) | — | Inside wrapper, after `</table>` |
| `eael/wcpc/after_content_wrapper` | action (emitted) | — | After wrapper close |
| `eael/wcpc/get_product_remove_url` | filter (emitted) | `(string $url, string $remove_action)` | Override remove URL ([trait line 2086](../../includes/Traits/Woo_Product_Comparable.php#L2086)) — used by Product_Grid context only; standalone widget never emits the URL |
| WC-native filters (consumed) | filter | various | `woocommerce_short_description` is the main one; trait re-applies it before EA's own filter |
| `wp_ajax_eael_product_grid` / `_nopriv` | action (consumed via trait) | — | `get_compare_table()` handler — **used by Product_Grid + Woo_Product_List modal**, not by this standalone widget |

No shared patterns from [`_patterns.md`](_patterns.md) apply.

## JavaScript Lifecycle

**N/A — pure CSS widget**, no JavaScript file declared in [`config.php`](../../config.php#L1117). The widget renders a static HTML table on every page load.

JS-based comparison (modal popup, AJAX add/remove, localStorage state) lives in Product_Grid's [`product-grid.js`](../../src/js/view/product-grid.js) and is invoked when consumer widgets use `print_compare_button()` from the same `Woo_Product_Comparable` trait. The trait's `get_compare_table()` AJAX handler responds to those clicks.

For the standalone Woo_Product_Compare widget, what you configure in the panel = what you see; no client-side state, no AJAX, no DOM mutation post-render.

## Common Issues

### Empty table — no products shown

- **Likely cause:** `product_ids` Select2 is empty
- **Diagnose:** check panel — are any products selected?
- **Fix:** add product IDs via the Select2 picker

### "No products found" text is blank in standalone widget

- **Likely cause:** `no_products_found_text` panel control is gated by `'eicon-woocommerce' === $this->get_name()` ([trait line 282](../../includes/Traits/Woo_Product_Comparable.php#L282)) — only Product_Grid (widget id `eicon-woocommerce`) gets the control. Standalone Woo_Product_Compare uses empty string default.
- **Diagnose:** inspect panel — is there a "No products found" text field?
- **Fix:** known limitation; standalone widget can't customize the empty-state message via panel. Hook `eael/wcpc/before_main_table` to inject custom HTML

### Highlighted product column shows on theme-1 / theme-2 / theme-5 / theme-6

- **Likely cause:** `highlighted_product_id` panel control is conditioned on `theme in [theme-3, theme-4]` but the CSS class `featured` is added to columns regardless if the value was saved before changing themes
- **Diagnose:** inspect a column — does `<td class="… featured">` appear?
- **Fix:** re-save the widget with the desired theme to clear stale `highlighted_product_id`

### Ribbon text appears on themes other than theme-4

- **Likely cause:** Same — panel-side `condition: theme == 'theme-4'` but render reads `$ds['ribbon']` unconditionally ([line 1907](../../includes/Traits/Woo_Product_Comparable.php#L1907)). If the value was set when theme-4 was selected then theme changed, the value persists.
- **Diagnose:** view source — is the ribbon `<span>` present?
- **Fix:** clear ribbon text in panel before switching theme; or wire the ribbon render to also check theme value

### Selected product attribute taxonomy field shows no value

- **Likely cause:** product doesn't have terms assigned to that attribute taxonomy. The field renders empty (or "-")
- **Diagnose:** check the product's Attributes tab in WC admin
- **Fix:** add term values to the product's WC attributes

### Add-to-cart button doesn't work for variable products

- **Likely cause:** `woocommerce_template_loop_add_to_cart()` renders the loop-style button (defaults to "Read more" for variable products linking to product page, NOT direct add)
- **Diagnose:** view source for the variable product column
- **Fix:** intentional — WC's archive/loop add-to-cart doesn't support inline variation selection; user must click through to the product page

## Known Limitations

- **Massive shared trait (`Woo_Product_Comparable.php` 2330 lines) couples 4+ widgets** — any change to `render_compare_table()` / `static_get_products_list()` / control schemas affects Woo_Product_Compare + Product_Grid + Woo_Product_List + Pro Woo_Product_Carousel simultaneously. Drift between consumers is silent.
- **`show_compare` HIDDEN control on this widget vs SWITCHER on others** — same control id, different visibility/type. Refactoring this universal conditional pattern requires touching all 4+ consumer widgets.
- **`no_products_found_text` only registered for `'eicon-woocommerce'` widget** ([trait line 282](../../includes/Traits/Woo_Product_Comparable.php#L282)) — standalone Woo_Product_Compare has no panel control for the empty-state message; renders empty `<td>`.
- **Highlighted product + ribbon don't unset on theme change** — settings persist; column may render `featured` class / ribbon span when theme moved away from theme-3/4.
- **`fields` Repeater uses non-prefixed control ID** ([trait line 254](../../includes/Traits/Woo_Product_Comparable.php#L254)) — `'fields'` clashes risk with any other widget using a same-named control (rare but a general naming-collision concern).
- **Filter `eael/wcpc/get_product_remove_url`** ([trait line 2086](../../includes/Traits/Woo_Product_Comparable.php#L2086)) builds a removal URL with `action=eael-wcpc-remove-product` query arg but **no PHP handler is registered for this action** in EA Lite — dead URL builder. Likely Pro-only handler.
- **Per-field action `eael/wcpc/compare_field_<field>`** uses dynamic name — listeners must precisely match the field key (incl. attribute taxonomy slugs prefixed by WC). Hard to discover; only documented inline.
- **`get_style_depends() = ['font-awesome-4-shim']`** (deprecated handle) — inherited via trait `Woo_Product_Comparable::get_style_depends()`; affects all widgets that `use` it.
- **`render_compare_table()` is `public static`** but reads `self::class` for branching ([line 1929](../../includes/Traits/Woo_Product_Comparable.php#L1929)) — late-static-binding awareness; can produce surprising behavior when called from subclass contexts.
- **`product_ids` field has no schema migration when WC attribute taxonomies change** — if a global attribute is renamed/removed, saved field labels point to nonexistent taxonomies. Render produces empty `<td>` silently.
- **No `wpml_object_id` filter** — product IDs aren't passed through WPML translation. Cross-language sites need to manually pick localized product IDs per language tree. Compare to [`_patterns.md § WPML`](_patterns.md#wpml-media-translation).
- **No editor preview optimization** — the widget runs full WC product lookups via `wc_get_product()` × N products on every editor render; large product lists slow the editor noticeably. Cache layer relies on Elementor's render cache.
- **`is_dynamic_content()` not overridden** — Elementor's render cache may store the table HTML; product stock/price changes won't reflect until cache expires.
