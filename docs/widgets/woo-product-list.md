# Woo Product List Widget

> WC product list with 3 preset templates, panel-rich content composition (badges, rating, category, total sold, excerpt, action buttons), and shared Load More + Quick View AJAX assets. **Newer query path** — uses `eael_prepare_product_query()` + `WC_Product_Query` (vs Product_Grid's raw `WP_Query`). **Cleanest hook contract among WC widgets**: own `eael/woo-product-list/{before,after}-product-loop` actions (different namespace prefix), single emission each (no double-fire bug). 16-line JS (only Quick View hookpoints — heavy lifting in shared `load-more.js`).

**Class file:** [`includes/Elements/Woo_Product_List.php`](../../includes/Elements/Woo_Product_List.php)
**Slug:** `woo-product-list` (widget id `eael-woo-product-list`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-list/>
**Pro-shared:** ✅ Yes — Pro extends via `Template_Query` auto-merge (additional preset PHP files in `EAEL_PRO_PLUGIN_PATH/includes/Template/Woo-Product-List/`) and inline `eael/controls/load_more_button_style` action. No `eael_section_pro` upsell panel, no Pro-only inline gates.

---

## Overview

Renders WC products in a list format (one row per product) with 3 Lite presets — preset-1 default (3 panel-conditional sub-variants for badge / content-header), preset-2 + preset-3 use alternate badge + content-header settings. Each row's per-element visibility (badge, rating, review count, category, title, excerpt, price, total-sold, add-to-cart button, Quick View button, "View Product" link button) is toggled by SWITCHER controls; rendered via the per-preset template inside `while($query->have_posts())`.

Differs from other WC widgets architecturally on three points:

1. **Newer query path** — `eael_prepare_product_query()` at [line 3947](../../includes/Elements/Woo_Product_List.php#L3947) uses `WC_Product_Query` (passes `limit`/`status`/`include`/`exclude`/`author`/`page` keys) rather than Product_Grid's raw `WP_Query` args.
2. **Distinct hook namespace** — emits `eael/woo-product-list/before-product-loop` and `_after-product-loop` ([lines 4083 + 4125](../../includes/Elements/Woo_Product_List.php#L4083)) — NOT `eael_woo_before/after_product_loop` like Product_Grid + Carousel + Gallery. Single emission each per render, no double/triple-fire bug.
3. **No widget JS logic** — 16-line `woo-product-list.js` only fires `quickViewAddMarkup` + `quickViewPopupViewInit` hook actions. Load More, Quick View, and Compare logic lives in shared assets (`load-more.js`, `quick-view.js`, `product-grid.js`).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| 3 preset templates (preset-1..preset-3) | ✅ | ✅ + extra Pro presets via auto-merge |
| Per-element toggles (badge / rating / review-count / category / title / excerpt / price / total-sold / add-to-cart / quick-view / link button) | ✅ | ✅ |
| Custom add-to-cart text per product type (simple / variable / grouped / external / default) | ✅ | ✅ |
| Layout-conditional preset settings (preset-2/3 have alternate badge preset + content-header position) | ✅ | ✅ |
| WC_Product_Query (newer) | ✅ | ✅ |
| Load More button | ✅ | ✅ |
| Quick View popup (shared asset) | ✅ | ✅ |
| Action button "static" vs "on-hover" positioning | ✅ | ✅ |
| Image clickable / alignment / size | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro-specific features | — | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_List.php`](../../includes/Elements/Woo_Product_List.php) | PHP widget class (4148 lines) — controls, `render()`, static `get_woo_product_list_settings()` (settings flattener), `eael_prepare_product_query()` (WC_Product_Query builder), `add_to_cart_button_custom_text()` callback, per-section register helpers |
| [`includes/Template/Woo-Product-List/`](../../includes/Template/Woo-Product-List/) | 3 preset PHP templates (preset-1..preset-3); auto-discovered via `Template_Query::process_directory_name()` → `Woo-Product-List` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | Composed trait — provides `get_template`, `get_template_list_for_dropdown`, `get_filename_only`, `get_temp_dir_name`, `print_load_more_button`, `load_quick_view_asset` |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `get_query_args()`, `get_dynamic_args()`, `eael_get_all_user_ordered_products()`, `eael_validate_html_tag()`, `eael_allowed_tags()` |
| [`src/css/view/woo-product-list.scss`](../../src/css/view/woo-product-list.scss) | Source styles (795 lines) — per-preset variants, container/item/image/content sections, color+typography |
| [`src/js/view/woo-product-list.js`](../../src/js/view/woo-product-list.js) | Frontend logic (**16 lines**) — Quick View hook actions only |
| [`src/js/view/load-more.js`](../../src/js/view/load-more.js) | **Shared** Load More AJAX (Post_Grid + Product_Grid + this widget + Pro Filterable_Gallery) |
| [`src/js/view/quick-view.js`](../../src/js/view/quick-view.js) | **Shared** Quick View popup |
| [`config.php`](../../config.php#L618) entry `'woo-product-list'` | Asset declaration: load-more.min.css + quick-view.min.css + woo-product-list.min.css + imagesLoaded + Isotope + load-more.min.js + quick-view.min.js + woo-product-list.min.js |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | Vendor — loaded for shared `load-more.js` compatibility |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | Vendor — loaded for shared `load-more.js`; **not used** by this widget's own JS |

`get_style_depends() = ['font-awesome-5-all', 'font-awesome-4-shim']` + `get_script_depends() = ['font-awesome-4-shim']` (deprecated FA4 shim handle).

## Architecture

- **Template-include rendering** — `render()` calls `get_template($layout)` (where `$layout` is `preset-1`/`preset-2`/`preset-3` from `eael_dynamic_template_layout` control) and `include`s the matching file inside `while($query->have_posts())` ([line 4113-4116](../../includes/Elements/Woo_Product_List.php#L4113)). Same as Product_Grid pattern.
- **Dynamic layout picker built from template directory scan** — `eael_product_list_layout()` at [line 209-244](../../includes/Elements/Woo_Product_List.php#L209) calls `get_template_list_for_dropdown(true)` to build the CHOOSE image picker; preview image path `woo-product-list-{key}.png` with `custom-layout.png` fallback when file doesn't exist. Pro adds presets by dropping `Template Name:` PHP files into `EAEL_PRO_PLUGIN_PATH/includes/Template/Woo-Product-List/`.
- **`get_woo_product_list_settings($settings)` is `public static` settings flattener** ([line 3885-3940](../../includes/Elements/Woo_Product_List.php#L3885)) — runs in `render()` to flatten 40+ `$settings['eael_woo_product_list_*']` keys into a compact `$woo_product_list` array. Layout-conditional overrides for preset-2/3 (alternate `badge_preset_2`, `content_header_position_preset_2_3`, `total_sold_preset_2_3_show`). Result stored on instance property `$this->woo_product_list_settings` for filter-callback access. Static so templates can call it.
- **`add_to_cart_button_custom_text` filter callback reads instance state** ([line 103-142](../../includes/Elements/Woo_Product_List.php#L103)) — registered before render, **removed after render** ([line 4145](../../includes/Elements/Woo_Product_List.php#L4145)) — no filter leak (vs Woo_Add_To_Cart which never removes). Per-product-type text selector via `$product->get_type()` switch; out-of-stock simple products fall back to "default" text. **Forces "Read more" → "View More" string substitution** ([line 137-139](../../includes/Elements/Woo_Product_List.php#L137)) — non-obvious WC string override.
- **Clean public hook contract** ([lines 4083 + 4125](../../includes/Elements/Woo_Product_List.php#L4083)) — `eael/woo-product-list/before-product-loop` and `_after-product-loop` actions fire **once each**, no double-fire bug. Different namespace prefix (`eael/woo-product-list/`) vs sibling widgets (`eael_woo_*`). Cleanest extension surface in the WC widget set.
- **`eael_prepare_product_query()` uses `WC_Product_Query`** ([line 3947](../../includes/Elements/Woo_Product_List.php#L3947)) — passes `limit`/`status`/`include`/`exclude`/`author`/`page` keys to WC's product query class. Render `else` branch at [line 4097](../../includes/Elements/Woo_Product_List.php#L4097) still uses `new \WP_Query($args)` though — dual codepath inconsistency. The `args['limit']` key in `$args['limit'] ?? $args['posts_per_page']` lookup at [line 4130](../../includes/Elements/Woo_Product_List.php#L4130) is the WC_Product_Query-vs-WP_Query backward-compat shim.
- **3 source modes** — `source_dynamic` (theme builder + REQUEST `post_type`) → `ClassesHelper::get_query_args` + `get_dynamic_args`; `source_archive` → reads `$wp_query` global directly; default → `eael_prepare_product_query()`.
- **`purchased` / `not-purchased` filter for logged-in users** ([line 4055-4072](../../includes/Elements/Woo_Product_List.php#L4055)) — `ClassesHelper::eael_get_all_user_ordered_products()` returns user's order history; sets `post__in` (purchased) or `post__not_in` (merged with existing). No render path when `purchased` selected and user has no orders.
- **Category attribute anomaly** — `get_categories()` returns `['essential-addons-elementor']` ONLY ([line 60](../../includes/Elements/Woo_Product_List.php#L60)) — does NOT include `'woocommerce-elements'`. Unlike Product_Grid/Carousel/Gallery/Cart/Checkout which include both. May affect editor panel categorization (widget only appears under "Essential Addons", not under "WooCommerce" tab in editor sidebar).
- **`#[\AllowDynamicProperties]` PHP 8.2 attribute** ([line 21](../../includes/Elements/Woo_Product_List.php#L21)) — suppresses deprecation warnings for dynamic property assignment. Acknowledges the codebase uses dynamic properties without declaring them.
- **`load_quick_view_asset()` enqueues WC's photoswipe/zoom/flexslider on `wp_footer`** — same helper as Product_Grid + Carousel + Gallery; conditional on `current_theme_supports('wc-product-gallery-*')`.

## Render Output

```html
<div class="eael-product-list-wrapper {preset-1|preset-2|preset-3}">
  <div class="eael-product-list-body woocommerce">
    <div class="eael-product-list-container">

      <div class="eael-post-appender">
        <!-- eael/woo-product-list/before-product-loop action fires here -->

        <!-- Per-product output from preset template (preset-1..preset-3 markup varies): -->
        <div class="eael-product-list-item {layout-class}">
          <div class="eael-product-list-image-wrap {image-alignment-class}">
            [?] <a href="<permalink>">
              {wp_get_attachment_image($image_id, $image_size)}
            [?] </a>
          </div>
          <div class="eael-product-list-content-wrap">
            <div class="eael-product-list-content-header {content-header-direction-class}">
              [?] <span class="badge {badge-preset} {badge-alignment}">Sale</span> / Stock Out
              [?] <div class="eael-product-list-category">…</div>
              [?] <div class="eael-product-list-rating">★★★★☆</div>
              [?] <span class="eael-product-list-review-count">(N reviews)</span>
            </div>
            <{title_tag} class="eael-product-list-title">
              [?] <a href="<permalink>">{title}</a>
              [?] {title}
            </{title_tag}>
            [?] <div class="eael-product-list-excerpt">{wp_trim_words(excerpt, count, indicator)}</div>
            [?] <div class="eael-product-list-price">{$product->get_price_html()}</div>
            <div class="eael-product-list-footer">
              [?] <div class="eael-product-list-total-sold">{Total Sold: N}</div>
              [?] <div class="eael-product-list-total-sold-remaining">{Remaining: N}</div>
              <div class="eael-product-list-buttons {static|on-hover}">
                [?] {woocommerce_template_loop_add_to_cart() — with custom button text via filter}
                [?] <a class="eael-product-list-quick-view-button" data-product-id="<id>">{Quick View text}</a>
                [?] <a href="<permalink>" class="eael-product-list-link-button">{View Product}</a>
              </div>
            </div>
          </div>
        </div>
        …

        <!-- No products / no layout fallbacks: -->
        [?] <p class="no-posts-found">{products_not_found_text}</p>
        [?] <p class="eael-no-posts-found">No layout found!</p>

        <!-- eael/woo-product-list/after-product-loop action fires here -->
      </div>

      [?] <!-- Load More button (when found_posts > limit) -->
      <div class="eael-load-more-button-wrap">
        <button class="eael-load-more-button"
                data-widget="<widget-id>" data-page-id="<page-id>"
                data-class="Essential_Addons_Elementor\Elements\Woo_Product_List"
                data-args="<urlencoded WP_Query args>"
                data-page="1" data-max-page="N"
                data-template="…">
          <span class="eael_load_more_text">Load More</span>
        </button>
      </div>

    </div>
  </div>
</div>
```

Notes:

- Buttons positioned `static` (always visible) or `on-hover` (revealed on row hover) via wrapper class.
- Content-header `direction` switch (`rtl` / `ltr`) flips the badge/category/rating row order.
- Total Sold + Remaining show via WC's `total_sales` post meta + stock quantity (preset-2/3 use alternate visibility settings).
- Load More uses shared `load-more.js` infrastructure; AJAX dispatches by `data-class` parameter.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_List.php#L171) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_dynamic_template_layout` | CHOOSE (image) | `preset-1` | Content → Layout | Selects preset template via `get_template()` |
| `show_load_more` | SWITCHER | empty | Content → Layout | Toggle Load More button |
| `eael_woo_product_list_*_show` (badge, rating, review_count, category, title, excerpt, price, total_sold, add_to_cart_button, quick_view_button, link_button) | SWITCHER × 11 | various | Content → Layout / Content | Per-element toggles |
| `post_type` | SELECT | `product` | Content → Query | `product` / `source_dynamic` / `source_archive` |
| `eael_woo_product_list_products_count` | NUMBER | `4` | Content → Query | posts_per_page / limit |
| `product_offset` | NUMBER | `0` | Content → Query | offset |
| `eael_woo_product_list_product_filter` | SELECT | `recent-products` | Content → Query | Filter mode (recent/featured/best-selling/sale/top/manual) |
| `orderby` | SELECT | varies | Content → Query | Options via `eael/woo-product-list/orderby-options` filter |
| `product_type_logged_users` | SELECT | empty | Content → Query | `purchased` / `not-purchased` filter |
| `eael_product_list_image_size` | GROUP | `medium` | Content → Image | Group_Control_Image_Size |
| `eael_product_list_image_clickable` | SWITCHER | empty | Content → Image | Wrap image in `<a>` to permalink |
| `eael_product_list_image_alignment` | SELECT | varies | Content → Image | left/right/center alignment class |
| `eael_product_list_content_header_position` | SELECT | `before-title` | Content → Content | `before-title` / `after-title` for badge/category placement |
| `eael_product_list_content_header_position_preset_2_3` | SELECT | `after-title` | Content → Content | Override for preset-2/3 |
| `eael_product_list_content_header_direction` | SELECT | — | Content → Content | `rtl` / `ltr` direction of header row |
| `eael_product_list_content_header_badge_preset` | SELECT | `badge-preset-1` | Content → Content | Badge visual preset (preset-1/2/3) |
| `eael_product_list_content_header_badge_preset_2` | SELECT | `badge-preset-2` | Content → Content | Alternate for preset-2 |
| `eael_product_list_content_header_badge_alignment` | SELECT | `badge-alignment-left` | Content → Content | Badge alignment class |
| `eael_product_list_content_header_badge_sale_text` / `_stock_out_text` | TEXT × 2 | `Sale` / `Stock Out` | Content → Content | Badge labels |
| `eael_product_list_content_body_title_tag` | SELECT | `div` | Content → Content | Title HTML tag (sanitized via `eael_validate_html_tag`) |
| `eael_product_list_content_body_title_clickable` | SWITCHER | empty | Content → Content | Wrap title in `<a>` to permalink |
| `eael_product_list_content_body_excerpt_words_count` | NUMBER | — | Content → Content | wp_trim_words count |
| `eael_product_list_content_body_excerpt_expanison_indicator` | TEXT | `...` | Content → Content | ⚠️ Same `expanison` typo as Post_Grid (legacy) |
| `eael_product_list_content_general_button_position` | SELECT | varies | Content → Content | `static` / `on-hover` button positioning |
| `eael_product_list_content_footer_total_sold_text` / `_remaining_text` | TEXT × 2 | i18n defaults | Content → Content | Footer text labels |
| `eael_product_list_content_footer_quick_view_text` | TEXT | `View Product` | Content → Content | Quick View button text |
| `eael_product_list_content_footer_not_found_text` | TEXT | `No products found!` | Content → Content | Empty-query text |
| `eael_product_list_content_footer_add_to_cart_custom_text_show` | SWITCHER | empty | Content → Content | Enable per-product-type text overrides |
| `eael_product_list_content_footer_add_to_cart_{simple,variable,grouped,external,default}_text` | TEXT × 5 | `Buy Now` | Content → Content | Per-product-type button text |
| Style → Container / Item / Item Image / Item Content / Color+Typography / Load More / Popup | various | — | Style tab | Per-region typography, color, spacing, border |
| Style → Load More Button | various | — | Style tab | **Injected via `eael/controls/load_more_button_style` action** |

## Conditional Dependencies

```text
# Layout-conditional
eael_woo_product_list_total_sold_preset_2_3_show
                                      → visible when eael_dynamic_template_layout != 'preset-1'
eael_product_list_content_header_position_preset_2_3
                                      → visible when layout != 'preset-1'
eael_product_list_content_header_badge_preset_2
                                      → visible when layout != 'preset-2'
                                        (badge_preset_2 hidden for preset-2 OR similar — verify)

# Element visibility chain
add_to_cart_{type}_text                → visible when add_to_cart_custom_text_show == 'yes'

# Frontend gates
Entire output                          → empty when WooCommerce inactive
Empty render                           → "No products found" or "No layout found" per branch
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/woo-product-list/orderby-options` | filter (emitted) | `(array $options)` | Extend orderby dropdown ([line 146](../../includes/Elements/Woo_Product_List.php#L146)) |
| `eael/woo-product-list/filterby-options` | filter (emitted) | `(array $options)` | Extend filter-by dropdown ([line 161](../../includes/Elements/Woo_Product_List.php#L161)) |
| `eael/woo-woo-product-list/product-statuses` | filter (emitted) | `(array $statuses)` | ⚠️ Typo: `woo-woo` double prefix; extend status allowlist ([line 3738](../../includes/Elements/Woo_Product_List.php#L3738)) |
| `eael/woo-product-list/before-product-loop` | action (emitted) | `()` no-arg | Pre-loop hook — **single emission** ([line 4083](../../includes/Elements/Woo_Product_List.php#L4083)) |
| `eael/woo-product-list/after-product-loop` | action (emitted) | `()` no-arg | Post-loop hook — **single emission** ([line 4125](../../includes/Elements/Woo_Product_List.php#L4125)) |
| `eael/controls/load_more_button_style` | action (emitted, **internal**) | `(Widget_Base $this)` | Inject shared Load More style controls — Bootstrap handler in Lite |
| `eael/is_plugin_active` | filter (consumed) | `(bool $active, string $plugin)` | Detect WooCommerce active ([line 479](../../includes/Elements/Woo_Product_List.php#L479)) |
| `woocommerce_product_add_to_cart_text` | filter (consumed) | `(string $text, WC_Product $product)` | Custom button label per product type — **`remove_filter` called at end of render**, no leak ([line 4145](../../includes/Elements/Woo_Product_List.php#L4145)) |
| `wp_ajax_load_more` / `_nopriv` | action (consumed) | — | Shared Load More handler (Post_Grid + Product_Grid + this widget + Pro) |

⚠️ **Distinct hook namespace** — `eael/woo-product-list/*` (with slash separator), NOT `eael_woo_*` (with underscore) like Product_Grid / Carousel / Gallery. Listeners must bind precisely; cross-widget listeners need separate registrations for each widget.

⚠️ Typo in `eael/woo-woo-product-list/product-statuses` ([line 3738](../../includes/Elements/Woo_Product_List.php#L3738)) — `woo-woo` is a double prefix. Renaming breaks listeners. Leave as-is.

No shared patterns from [`_patterns.md`](_patterns.md) apply — no Liquid Glass, no FA4 shim (legacy `font-awesome-4-shim` handle only), no WPML media, no `has_pro` JS handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-product-list.default', wooProductList)` ([line 12-14 of woo-product-list.js](../../src/js/view/woo-product-list.js#L12))
- **Boot pattern:** Newer `eael.hooks.addAction("init","ea",…)` wrapper
- **Guard:** `if (eael.elementStatusCheck('eaelProductListLoad') && window.forceFullyRun === undefined) return`
- **Vendor deps:** None directly — JS is **16 lines** total
- **Reads on init:** Nothing — pure hook delegation
- **Init action:** Fires `quickViewAddMarkup` + `quickViewPopupViewInit` `eael.hooks.doAction()` calls only. Shared `quick-view.js` listens for these and bootstraps the Quick View modal.
- **No Isotope, no AJAX, no DOM mutation in this file** — all that lives in shared `load-more.js` (paging) and `quick-view.js` (modal). This widget shares Quick View popup body wrapper with Product_Grid, Carousel, Gallery, and Compare (`.eael-woocommerce-popup-view` body-injected once per page by whichever Woo widget renders first).

## Common Issues

### Load More returns wrong products / wrong page

- **Likely cause:** AJAX `wp_ajax_load_more` dispatches by `data-class` parameter; if the class string in HTML matches but server-side `class_exists` fails (e.g. autoloader race), the request errors out
- **Diagnose:** browser DevTools → network → `admin-ajax.php?action=load_more` response
- **Fix:** clear page cache; verify `data-class` HTML attr matches PHP class FQN exactly

### "View More" appears instead of "Read more"

- **Likely cause:** Intentional substitution at [line 137-139](../../includes/Elements/Woo_Product_List.php#L137) — `add_to_cart_button_custom_text` callback forces `Read more → View More` regardless of user settings
- **Diagnose:** WC's default external-product button text is "Read more"; this widget overrides to "View More"
- **Fix:** to keep "Read more", override via your own filter on `woocommerce_product_add_to_cart_text` at higher priority

### Preset-2/3 badge or content-header position panel changes don't take effect

- **Likely cause:** `get_woo_product_list_settings()` applies layout-specific overrides for preset-2/3 ([line 3930-3937](../../includes/Elements/Woo_Product_List.php#L3930)) — alternate control IDs (`*_preset_2_3` suffix); editing the preset-1 control has no effect on preset-2/3 rendering
- **Diagnose:** check which control you're editing; preset-2/3 use `_preset_2_3` suffix versions
- **Fix:** edit the matching `_preset_2_3` control panel-side

### Custom add-to-cart text doesn't apply

- **Likely cause:** `add_to_cart_custom_text_show` switcher not enabled ([line 107](../../includes/Elements/Woo_Product_List.php#L107)); callback falls through to WC's default text
- **Diagnose:** check the "Show Custom Add to Cart Text" switcher in Content → Content section
- **Fix:** enable the switcher to activate per-type text overrides

### Widget not appearing in editor "WooCommerce" panel section

- **Likely cause:** `get_categories()` at [line 60](../../includes/Elements/Woo_Product_List.php#L60) returns `['essential-addons-elementor']` only — does NOT include `'woocommerce-elements'`. Widget categorized under EA only.
- **Diagnose:** editor sidebar — Woo Product List shows in EA section, not WC section
- **Fix:** known categorization anomaly; widget is in EA category only. To add WC category, modify `get_categories()` (Pro-only fork or core change)

### Status not found / Cannot filter by draft/pending status

- **Likely cause:** Status allowlist filter typo at [line 3738](../../includes/Elements/Woo_Product_List.php#L3738) — `eael/woo-woo-product-list/product-statuses`. If you registered a listener on `eael/woo-product-list/product-statuses`, it doesn't fire.
- **Diagnose:** check filter name precisely; the typo persists for back-compat
- **Fix:** register listener on the typo'd filter name

## Known Limitations

- **`eael/woo-woo-product-list/product-statuses` filter typo** ([line 3738](../../includes/Elements/Woo_Product_List.php#L3738)) — `woo-woo` double prefix preserved for back-compat. Renaming breaks listeners.
- **`excerpt_expanison_indicator` control ID typo** (preserves `expanison` from Post_Grid legacy) — should be `expansion`. Same widespread typo across multiple widgets.
- **`get_categories()` omits `'woocommerce-elements'`** ([line 60](../../includes/Elements/Woo_Product_List.php#L60)) — widget categorized under EA only, not WC. Editor sidebar visibility anomaly.
- **`render()` uses `WP_Query` while `eael_prepare_product_query()` returns WC_Product_Query args** — dual codepath. `args['limit']` vs `args['posts_per_page']` reconciliation at [line 4130](../../includes/Elements/Woo_Product_List.php#L4130). Brittle when WC_Product_Query semantics change.
- **"Read more" → "View More" forced string substitution** ([line 137-139](../../includes/Elements/Woo_Product_List.php#L137)) — hardcoded; user can't keep WC's default external-product text without their own filter override.
- **Layout-conditional control IDs** — preset-2/3 use suffixed control IDs (`_preset_2_3`, `_preset_2`). Editing preset-1 controls has no effect on preset-2/3 rendering. Surprising for new users.
- **`get_style_depends() = ['font-awesome-4-shim']` + `get_script_depends() = ['font-awesome-4-shim']`** — deprecated handles.
- **imagesLoaded + Isotope loaded** for shared `load-more.js` compatibility, **not used** by this widget's own 16-line JS. ~50KB JS overhead per page even when widget doesn't need them.
- **Quick View popup body wrapper shared across Woo widgets** — `.eael-woocommerce-popup-view` injected once per page; last widget to render owns popup state. Multi-Woo-widget pages share modal.
- **No multi-instance hardcoded-id bug** — `data-widget-id` is used everywhere; widget-id-scoping is consistent. (Unlike Product_Grid + Gallery which hardcode `id="eael-product-grid"` / `id="eael-product-gallery"`.)
- **`#[\AllowDynamicProperties]` PHP 8.2 attribute** — suppresses warnings; doesn't fix the underlying dynamic property usage.
- **`$this->woo_product_list_settings` is set in `render()` and read by `add_to_cart_button_custom_text` filter callback** — coupling between filter callback and instance state; safer than static state of Woo_Cart / Woo_Checkout but still order-dependent.
- **`load_quick_view_asset()` enqueues WC photoswipe/zoom/flexslider on `wp_footer` unconditionally** — same as Product_Grid + Carousel + Gallery. If multiple Woo widgets are on the page, the assets enqueue multiple times via the wp_footer action.
- **No `wpml_object_id` filter** — cross-language product IDs not translated.
- **No `is_dynamic_content()` override** — Elementor render cache may store list HTML; stock/price changes won't reflect until cache expires.
- **`eael_product_list_image_size` setting takes precedence over WC's `woocommerce_thumbnail` filter chain** — third-party plugins that filter `woocommerce_thumbnail` (variant gallery image sizes) may not see their changes reflected in this widget.
