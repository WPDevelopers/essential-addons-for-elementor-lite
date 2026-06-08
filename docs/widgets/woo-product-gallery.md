# Woo Product Gallery Widget

> WP_Query-driven WooCommerce product gallery with **categorized tab filter UI** at the top (cat / tag / "All" tabs). Tab clicks fire AJAX (`wp_ajax_eael_product_gallery`) that re-renders the product list. 4 preset templates + Isotope masonry layout + Load More + Quick View shared with Product_Grid. Hardcoded `id="eael-product-gallery"` reproduces the multi-instance ID bug. Worst hook-double-fire offender: `eael_woo_after_product_loop` fires **three times** per render.

**Class file:** [`includes/Elements/Woo_Product_Gallery.php`](../../includes/Elements/Woo_Product_Gallery.php)
**Slug:** `woo-product-gallery` (widget id `eael-woo-product-gallery`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/woo-product-gallery/>
**Pro-shared:** ✅ Yes — Pro adds more preset templates via `Template_Query` auto-merge (`EAEL_PRO_PLUGIN_PATH/includes/Template/Woo-Product-Gallery/`) and inline `eael/pro_enabled` gated controls. One explicit Pro extension `do_action('eael/product_gallery/style_settings/control/after_color_typography', $this)`. No `eael_section_pro` upsell panel.

---

## Overview

Distinguishing feature vs Product_Grid: **product-categorized tab navigation** at the top. Users pick which `product_cat` term IDs + `product_tag` term IDs to expose; widget renders them as `<ul class="eael-cat-tab">` clickable tabs; click triggers AJAX `eael_product_gallery` that returns refreshed HTML + new `max_page` for Load More. Optional "All" tab combines both taxonomies.

Beyond tabs, the widget shares almost all infrastructure with Product_Grid: same template-include preset pattern, same shared `load-more.js` for paginated Load More, same shared `quick-view.js` for Quick View popup, same secondary-image hover swap with per-device `data-ssi-{device}` switchers, same Isotope masonry layout. Differences: 4 presets (vs Product_Grid's 12+), no Compare integration, no Product Comparable trait, no list layout, has the tab filter UI.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| 4 preset templates (preset-1..preset-4) | ✅ | ✅ + extra Pro presets via auto-merge |
| Grid + Masonry layouts (Isotope-driven) | ✅ | ✅ |
| Product-categorized tab filter UI (`product_cat` + `product_tag` terms) | ✅ | ✅ |
| AJAX tab switching + Load More | ✅ | ✅ |
| Quick View popup (shared asset) | ✅ | ✅ |
| Secondary image hover swap per-device | ✅ | ✅ |
| 4 source modes: product / source_dynamic / source_archive / archive (theme builder) | ✅ | ✅ |
| Load More button + shared `load-more.js` | ✅ | ✅ |
| Sale / Stock-Out badge controls | ✅ | ✅ |
| Manual / featured / best-selling / sale / top-rated filter modes | ✅ | ✅ |
| `eael/product_gallery/style_settings/control/after_color_typography` Pro extension point | ✅ — emitted | Pro injects style controls inline |
| `eael_section_pro` upsell panel | ❌ — none present | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Gallery.php`](../../includes/Elements/Woo_Product_Gallery.php) | PHP widget class (3370 lines) — controls, render with template-include + tab UI, `build_product_query()`, `eael_product_terms_render()` for tab markup, `convert_wp_query_args_to_wc_product_query()` (helper) |
| [`includes/Template/Woo-Product-Gallery/`](../../includes/Template/Woo-Product-Gallery/) | 4 preset PHP templates (preset-1..preset-4); auto-discovered via `Template_Query::process_directory_name()` → `Woo-Product-Gallery` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | Shared helpers (badges, action buttons, view popup style, product query) |
| [`includes/Traits/Template_Query.php`](../../includes/Traits/Template_Query.php) | Lite + Pro + theme template-dir merge |
| [`includes/Traits/Ajax_Handler.php`](../../includes/Traits/Ajax_Handler.php#L995) | `ajax_eael_product_gallery()` handler — nonce-protected, forces `post_status='publish'`, handles `product_cat` + `product_tag` taxonomy filter from `$_POST['taxonomy']`, dispatches via `data-class` parameter |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `get_query_args()`, `get_dynamic_args()`, `sanitize_taxonomy_data()`, `eael_sanitize_relation()`, `eael_get_widget_settings()` |
| [`src/css/view/woo-product-gallery.scss`](../../src/css/view/woo-product-gallery.scss) | Source styles (1539 lines) — per-preset variants, tab UI horizontal/vertical layouts, masonry, badges, Load More |
| [`src/js/view/woo-product-gallery.js`](../../src/js/view/woo-product-gallery.js) | Frontend logic (171 lines) — tab click AJAX, Isotope re-init after AJAX, secondary-image hover, Safari bfcache hard-reload |
| [`src/js/view/load-more.js`](../../src/js/view/load-more.js) | **Shared** Load More AJAX (Post_Grid + Product_Grid + this widget) |
| [`src/js/view/quick-view.js`](../../src/js/view/quick-view.js) | **Shared** Quick View popup |
| [`config.php`](../../config.php#L1194) entry `'woo-product-gallery'` | load-more.min.css + quick-view.min.css + woo-product-gallery.min.css + imagesLoaded + Isotope + load-more.min.js + quick-view.min.js + woo-product-gallery.min.js |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | Vendor — defer Isotope until images load |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | Vendor — masonry layout |

`get_style_depends() = ['font-awesome-5-all', 'font-awesome-4-shim']` + `get_script_depends() = ['font-awesome-4-shim']` (deprecated FA4 shim handle).

## Architecture

- **Template-include pattern** — `render()` calls `get_template($preset_slug)` (strips `eael-product-` prefix at [line 2894](../../includes/Elements/Woo_Product_Gallery.php#L2894)) and `include`s the matching file inside `while($query->have_posts())`. Same as Product_Grid + Carousel; templates live in `includes/Template/Woo-Product-Gallery/`. Auto-merge across Lite + Pro + theme dirs via `Template_Query`.
- **Three render-path branching for query source** — `post_type === 'source_dynamic' && is_archive()` OR `!empty($_REQUEST['post_type'])` → uses `HelperClass::get_query_args()` + `get_dynamic_args()` (theme builder context); `post_type === 'archive' && is_archive() && is_product_archive` → uses `$wp_query` global directly; everything else → `build_product_query($settings)`.
- **Hardcoded `id="eael-product-gallery"`** ([line 2856](../../includes/Elements/Woo_Product_Gallery.php#L2856)) — same multi-instance ID bug as Product_Grid. JS scoping uses `.elementor-element-{widgetid}` to disambiguate but the `#eael-product-gallery` selector resolves to document-first match for any unscoped query.
- **Tab UI is the unique feature** — `eael_product_terms_render($settings, $args)` ([line 3242](../../includes/Elements/Woo_Product_Gallery.php#L3242)) emits `<ul class="eael-cat-tab">` with one `<li>` per selected `product_cat` term + one per `product_tag` term + optional "All" tab. Each `<a>` carries `data-taxonomy`, `data-terms`, `data-id`, `data-tagid` (when both cats + tags selected, "All" tab uses combined `data-taxonomy="product_cat|product_tag"`). Tab visibility short-circuited if NO categories AND NO tags AND `eael_woo_product_gallery_terms_show_all == ''` ([line 3249-3251](../../includes/Elements/Woo_Product_Gallery.php#L3249)).
- **AJAX `eael_product_gallery` dispatch chain** — JS reads `data-class`, `data-args`, `data-template`, `data-page-id`, `data-widget-id`, `data-nonce` from `.eael-cat-tab` wrapper. Server-side `ajax_eael_product_gallery()` ([Ajax_Handler line 995](../../includes/Traits/Ajax_Handler.php#L995)) does `wp_parse_str` on `$_POST['args']` to decode URL-encoded query args, **forces `$args['post_status'] = 'publish'`** (security hardening), `sanitize_taxonomy_data()` on `$_POST['taxonomy']`, manual offset calc `$args['offset'] += ($page - 1) * $args['posts_per_page']`. Returns rendered HTML for the products list.
- **`eael_woo_after_product_loop` fires THREE TIMES per render** — once after the loop body ([line 2948](../../includes/Elements/Woo_Product_Gallery.php#L2948)), once after `</ul>` close ([line 2955](../../includes/Elements/Woo_Product_Gallery.php#L2955)), once after the `if (file_exists($template))` block ([line 2961](../../includes/Elements/Woo_Product_Gallery.php#L2961)). Worst double-fire offender of any EA WC widget. Listeners must be idempotent or `func_num_args()` aware.
- **`eael_woo_before_product_loop` fires TWICE** — once before template lookup ([line 2890](../../includes/Elements/Woo_Product_Gallery.php#L2890)), once before the actual `while($query->have_posts())` loop ([line 2941](../../includes/Elements/Woo_Product_Gallery.php#L2941)). All emissions are no-arg (unlike Product_Grid which passes `$preset_slug` to its first emission).
- **`build_product_query()` locally defined** — not shared trait with Product_Grid / Carousel. Same drift risk pattern noted in woo-product-carousel doc.
- **AJAX nonce uses `eael_product_gallery`** — different from Product_Grid's `eael_product_grid` nonce. Cross-widget AJAX endpoint isolation maintained.
- **Inline `<script>` block emitted in render() body** ([line 2967-2985](../../includes/Elements/Woo_Product_Gallery.php#L2967)) — duplicates JS-side Isotope init for editor preview. Same pattern as Product_Grid (frontend+inline `<script>` race risk).
- **`load_quick_view_asset()` in constructor** — same `wp_footer` callback as Product_Grid + Carousel; enqueues WC's `photoswipe-*`, `wc-product-gallery-zoom`, `flexslider`, `wc-add-to-cart-variation`, `wc-single-product` when theme supports them.
- **`convert_wp_query_args_to_wc_product_query()` ** ([line 2995](../../includes/Elements/Woo_Product_Gallery.php#L2995)) — helper that maps WP_Query args to WC_Product_Query args (limit, status, include, exclude, author, page). **Unclear if actually called** — not referenced in `render()` directly; possibly dead code or used by an unsurveyed code path.
- **No `eael_section_pro` upsell** — only one Pro extension `do_action` at [line 1365](../../includes/Elements/Woo_Product_Gallery.php#L1365).

## Render Output

```html
<div id="eael-product-gallery"                                      <!-- HARDCODED — multi-instance dup -->
     class="eael-product-gallery {preset-class} {layout-class}
            {eael-terms-layout-horizontal|eael-terms-layout-vertical}"
     data-widget-id="<id>"
     data-page-id="<page-id>"
     data-nonce="<eael_product_gallery nonce>">

  [?] <!-- Tab filter UI (when terms or "show all" enabled): -->
  <ul class="eael-cat-tab"
      data-layout="{grid|masonry}"
      data-template='{"dir":"…","file_name":"…","name":"Woo-Product-Gallery"}'
      data-nonce="<nonce>"
      data-page-id="<id>"
      data-widget-id="<id>" data-widget="<id>"
      data-class="Essential_Addons_Elementor\Elements\Woo_Product_Gallery"
      data-args="<urlencoded WP_Query args>"
      data-page="1">

    [?] <li><a class="active post-list-filter-item post-list-cat-<id>"
              data-taxonomy="all|product_cat|product_tag|product_cat|product_tag"
              data-page="1" data-tagid='<json>' data-id='<json>'>
             [?] <img src="…"> {All tab text}
        </a></li>

    <!-- Per category: -->
    [?] <li><a class="post-list-filter-item"
              data-taxonomy="product_cat"
              data-terms='["<slug>"]' data-id="<term_id>" data-page="1">
             [?] <img src="…"> {category->name}
        </a></li>

    <!-- Per tag: -->
    [?] <li><a class="post-list-filter-item" data-taxonomy="product_tag" …>…</a></li>
  </ul>

  <div class="woocommerce">
    <!-- eael_woo_before_product_loop fires here (1st time) -->

    <ul class="products eael-post-appender eael-post-appender-<id>"
        data-layout-mode="{grid|masonry}"
        data-ssi-desktop="yes|no"
        [?] data-ssi-tablet="yes|no" data-ssi-mobile="yes|no" …>

      <!-- eael_woo_before_product_loop fires here (2nd time) -->
      <!-- Per-product <li> from preset template (preset-1..preset-4 markup): -->
      <li class="product">
        <div class="eael-product-wrap" data-src="<thumbnail>" data-src-hover="<gallery-img>">
          {preset-specific markup — image, title, rating, badges, price, add-to-cart, quick-view, etc.}
        </div>
      </li>
      …
      <!-- eael_woo_after_product_loop fires here (1st time) -->
    </ul>
    <!-- eael_woo_after_product_loop fires here (2nd time) -->

    [?] <!-- No products / no layout fallbacks: -->
    <h2 class="eael-product-not-found">No Product Found</h2>
    <h2 class="eael-product-not-found">No Layout Found</h2>

    <!-- eael_woo_after_product_loop fires here (3rd time) -->

    [?] <!-- Load More button (when paginated) -->
    <div class="eael-load-more-button-wrap">
      <button class="eael-load-more-button" data-…>{Load More}</button>
    </div>
  </div>
</div>

<script>
  // Inline Isotope masonry init for editor preview (also runs in product-gallery.js)
  jQuery(document).ready(function ($) { … });
</script>
```

Notes:

- Tab `<a>` `data-taxonomy="product_cat|product_tag"` (pipe-separated, not array) for "All" tab when both cats + tags selected.
- `data-terms` is a JSON array of slugs (one slug per term).
- `data-tagid` (on "All" tab) is a JSON-encoded comma-separated string of tag IDs.
- Tab thumbnails from `wp_get_attachment_url(get_term_meta($term_id, 'thumbnail_id', true))`; per-term emission, `<img src="">` inline.
- Same `eael-post-appender` class as Post_Grid + Product_Grid for AJAX-append target.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Gallery.php#L138) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_product_gallery_style_preset` | CHOOSE (image) | `eael-product-preset-1` | Content → Layouts | Selects preset template via `get_template()` (strips `eael-product-` prefix) |
| `eael_product_gallery_terms_position` | CHOOSE | `eael-terms-layout-horizontal` | Content → Layouts | Tab UI position class on wrapper |
| `eael_woo_product_gallery_terms_show_all` | SWITCHER | empty | Content → Layouts | Show "All" tab combining categories + tags |
| `eael_woo_product_gallery_terms_all_text` | TEXT | `"All"` | Content → Layouts | "All" tab label |
| `eael_woo_product_gallery_terms_thumb` | SWITCHER | empty | Content → Layouts | Render thumbnail image in each tab |
| `eael_all_tab_thumb` | MEDIA | — | Content → Layouts | Thumbnail for "All" tab |
| `eael_product_gallery_categories` | SELECT2 (multi, source=product_cat) | — | Content → Layouts | Selected category term IDs (comma-stripped for rendering) |
| `eael_product_gallery_tags` | SELECT2 (multi, source=product_tag) | — | Content → Layouts | Selected tag term IDs |
| `relation_cats_tags` | SELECT | `OR` | Content → Layouts | AND/OR relation between cats + tags in WP_Query tax_query (used by AJAX handler) |
| `eael_product_gallery_items_layout` | CHOOSE | `grid` or `masonry` | Content → Layouts | `data-layout-mode` for Isotope; `layout_mode` setting also passed to Load More |
| `eael_product_gallery_show_secondary_image` / `_tablet` / `_mobile` | SWITCHER × 3 | empty | Content → Image | Per-device `data-ssi-{device}` toggles for hover swap |
| `eael_product_gallery_image_size_size` | GROUP | `woocommerce_thumbnail` | Content → Image | Group_Control_Image_Size for product thumbnails |
| `post_type` | SELECT | — | Content → Query | `product` / `source_dynamic` / `source_archive` / `archive` (theme builder) |
| `eael_product_gallery_product_filter` | SELECT | `recent-products` | Content → Query | Filter mode; options via `eael/product-gallery/filterby-options` filter |
| `eael_product_gallery_products_count` | NUMBER | `3` | Content → Query | `posts_per_page` |
| `product_offset` | NUMBER | `0` | Content → Query | offset |
| `product_type_logged_users` | SELECT | empty | Content → Query | `purchased` / `not-purchased` filter by user order history |
| `orderby` | SELECT | varies | Content → Query | Options via `eael/product-gallery/orderby-options` filter |
| Content → Badges (sale + stockout) | various | — | Content → Sale Badge | Per-badge text + style |
| Content → Load More | various | — | Content → Load More | `show_load_more` switch, button text |
| Content → View Popup | various | — | Content → View Popup | Quick View settings |
| Content → Action Buttons | various | — | Content → Action Buttons | Quick view / compare / wishlist buttons (only Quick View applies to this widget) |
| Style → Many sections | various | — | Style tab | Per-region typography, color, spacing, border — 12+ style sections (lines 1144-2820) |
| Pro-injected style section | — | — | Style tab | Via `eael/product_gallery/style_settings/control/after_color_typography` action |
| Load More style section | — | — | Style tab | **Injected via `eael/controls/load_more_button_style` action** (shared with Post_Grid, Product_Grid) |

## Conditional Dependencies

```text
# Layout / preset
Style sections                          → many conditioned on style_preset value
                                          (preset-1 vs preset-2..4 have differing controls)

# Tab UI
eael_woo_product_gallery_terms_all_text → visible when eael_woo_product_gallery_terms_show_all == 'yes'
eael_all_tab_thumb                      → visible when terms_thumb == 'yes' AND terms_show_all == 'yes'
relation_cats_tags                      → visible when both categories AND tags selected

# Image
eael_product_gallery_show_secondary_image_tablet / _mobile
                                        → visible when eael_product_gallery_show_secondary_image == 'yes'

# Query
product_type_logged_users               → visible when is_user_logged_in() (runtime, not panel condition)

# Frontend gate
Tab UI render                           → empty when (no cats AND no tags AND show_all != 'yes')
Entire output                           → empty when WooCommerce inactive
Products list                           → "No Product Found" h2 when query has no posts
                                          "No Layout Found" h2 when get_template() returns false
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/product-gallery/orderby-options` | filter (emitted) | `(array $options)` | Extend orderby dropdown |
| `eael/product-gallery/filterby-options` | filter (emitted) | `(array $options)` | Extend filter-by dropdown |
| `eael/controls/load_more_button_style` | action (emitted, **internal**) | `(Widget_Base $this)` | Inject shared Load More button style controls — Bootstrap handler in Lite |
| `eael/product_gallery/style_settings/control/after_color_typography` | action (emitted) | `(Widget_Base $this)` | Pro injects style controls after color/typography section ([line 1365](../../includes/Elements/Woo_Product_Gallery.php#L1365)) |
| `eael_woo_before_product_loop` | action (emitted, **fires TWICE**) | `()` no-arg | Once before template lookup ([line 2890](../../includes/Elements/Woo_Product_Gallery.php#L2890)), once before `while have_posts()` ([line 2941](../../includes/Elements/Woo_Product_Gallery.php#L2941)) |
| `eael_woo_after_product_loop` | action (emitted, **fires THREE TIMES**) | `()` no-arg | After loop body, after `</ul>`, after `if file_exists` block ([lines 2948, 2955, 2961](../../includes/Elements/Woo_Product_Gallery.php#L2948)) |
| `eael_product_wrapper_class` (template-side) | filter (emitted) | `(array $classes, int $product_id, string $widget_id_string)` | Per-preset templates wrap each `<li class="product">` with filtered classes; widget_id_string passed as `'eael-woo-product-gallery'` |
| `wp_ajax_eael_product_gallery` / `_nopriv` | action (consumed) | — | Tab filter AJAX handler; **forces `post_status='publish'`** ([Ajax_Handler line 1001](../../includes/Traits/Ajax_Handler.php#L1001)) |
| `wp_ajax_load_more` / `_nopriv` | action (consumed) | — | Shared Load More handler (with Post_Grid + Product_Grid) — dispatches by `data-class` parameter |
| `eael/pro_enabled` | filter (consumed) | `(bool $enabled)` | Inline Pro gating in style sections |

⚠️ **Hook signature inconsistency**: Product_Grid emits `eael_woo_before_product_loop($preset_slug)` (with arg); this widget emits no-arg twice; Woo_Product_Carousel emits no-arg once; Ajax_Handler context emits no-arg. Cross-widget contract is unstable.

No shared patterns from [`_patterns.md`](_patterns.md) apply — no Liquid Glass, no FA4 shim, no WPML media, no `has_pro` JS handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-product-gallery.default', wooProductGallery)` ([line 167-169](../../src/js/view/woo-product-gallery.js#L167))
- **Boot pattern:** Newer `eael.hooks.addAction("init","ea",…)` wrapper
- **Guard:** `if (eael.elementStatusCheck('productGalleryLoad') && window.forceFullyRun === undefined) return` — `forceFullyRun` bypass flag
- **Vendor deps:** Isotope, imagesLoaded, jQuery
- **First-tab activation:** `$('.eael-cat-tab li:first a', $scope).addClass('active')` — picks the first tab as active on init
- **Tab click handler** ([line 10-105](../../src/js/view/woo-product-gallery.js#L10)) — `e.preventDefault()`, double-click guard (skip if already active), toggle `active` class; `localStorage.setItem('eael-cat-tab', 'true')` (flag never read elsewhere — dead code); reads `data-class`, `data-args`, `data-template`, `data-page-id`, `data-widget-id`, `data-nonce`, `data-layout`, plus per-tab `data-taxonomy`/`data-id`/`data-tagid`/`data-terms`; POSTs to `eael_product_gallery` AJAX with these as `taxonomy` object
- **AJAX response handling:** if response has `.no-posts-found` class OR length 0, render "No Product Found" h2 + hide Load More button. Otherwise: `$('.eael-post-appender').empty().append($content)`, parse `.eael-max-page` text from response, update `.eael-load-more-button data-max-page`; if layout is masonry, `destroy` existing Isotope and re-init with `imagesLoaded` progress callback
- **Secondary image hover swap** ([line 117-148](../../src/js/view/woo-product-gallery.js#L117)) — `mouseover/mouseout` delegated to `.eael-product-wrap`, swaps `<img>` `src` + `srcset` based on `data-ssi-{device}` and `body[data-elementor-device-mode]` (same pattern as Product_Grid)
- **Safari bfcache reload** ([line 154-160](../../src/js/view/woo-product-gallery.js#L154)) — `window.addEventListener('pageshow', evt => { if (evt.persisted) setTimeout(() => location.reload(), 10) })`. On browser back/forward into a Safari-restored page, force reload after 10ms. **Heavy-handed** — any back/forward navigation reloads the entire page (UX cost). Comment cites Safari bfcache as cause.
- **Quick View integration:** `eael.hooks.doAction("quickViewAddMarkup",$scope,$)` and `quickViewPopupViewInit` — hands off to shared `quick-view.js`
- **Inline render-time `<script>` block** ([line 2967 of Woo_Product_Gallery.php](../../includes/Elements/Woo_Product_Gallery.php#L2967)) — duplicates JS-side Isotope init for editor preview; window resize listener calls `isotope('layout')` (only inline, not in main JS — partial duplication)
- **`isEditMode` patch** — forces `.eael-product-image-wrap .woocommerce-product-gallery` opacity to 1

## Common Issues

### Tab clicks return "No Product Found" even when products exist

- **Likely cause:** AJAX `wp_parse_str($_POST['args'], $args)` decoded URL-encoded args produce different structure than initial render (e.g. `tax_query` shape); OR cached `data-args` attribute is stale after WC plugin updates
- **Diagnose:** browser DevTools → network → check `admin-ajax.php?action=eael_product_gallery` POST body + response; verify `args` decoded correctly
- **Fix:** clear page cache; re-save the widget in Elementor to regenerate `data-args`

### Tab filter shows wrong taxonomy when "All" tab pipes both cat + tag

- **Likely cause:** "All" tab uses `data-taxonomy="product_cat|product_tag"` (pipe-separated) but server-side AJAX handler's `sanitize_taxonomy_data()` may treat this as a single string instead of splitting it
- **Diagnose:** check Ajax_Handler `sanitize_taxonomy_data` implementation; check `tax_query` shape in `WP_Query` log via Query Monitor
- **Fix:** check if `sanitize_taxonomy_data` splits on `|`; if not, the "All" tab may only filter by the first taxonomy

### Multiple widgets on same page — second tab's products show in first widget

- **Likely cause:** `id="eael-product-gallery"` is hardcoded at [line 2856](../../includes/Elements/Woo_Product_Gallery.php#L2856). JS scopes via `.elementor-element-{widgetid}` but the inner `#eael-product-gallery` selector resolves to document-first match
- **Diagnose:** view source — count `id="eael-product-gallery"` occurrences. Should be 1; will be N for N widgets.
- **Fix:** known bug; only one Woo_Product_Gallery per page supported reliably

### Hidden masonry layout breaks after browser back navigation

- **Likely cause:** Safari bfcache restores DOM but Isotope state is lost. Comment at [line 152 of woo-product-gallery.js](../../src/js/view/woo-product-gallery.js#L152) documents this and force-reloads on `pageshow.persisted`
- **Diagnose:** Safari → back → check if reload happens
- **Fix:** intentional reload workaround; **UX cost** for non-Safari users who hit the same path. Could be gated to Safari-only

### Load More button keeps showing even when all products loaded

- **Likely cause:** `$('.eael-max-page').text()` parsing returns empty string when AJAX response doesn't include `.eael-max-page` element; JS comparison `load_more.data('page') >= $max_page` fails with `NaN`
- **Diagnose:** check AJAX response HTML for `<span class="eael-max-page">N</span>`
- **Fix:** server-side render must include `.eael-max-page` element; check template files

### Tab thumbnails don't show even though `eael_woo_product_gallery_terms_thumb` is enabled

- **Likely cause:** term meta `thumbnail_id` not set (no WC term thumbnail uploaded); OR `wp_get_attachment_url()` returns false
- **Diagnose:** WP admin → Products → Categories → check term thumbnail
- **Fix:** upload term thumbnails via WC's term edit screen

### Secondary image hover doesn't swap on first page load (works after AJAX tab click)

- **Likely cause:** initial `data-ssi-{device}` attrs only emit `desktop` if no per-device switcher set ([line 2931 of Woo_Product_Gallery.php](../../includes/Elements/Woo_Product_Gallery.php#L2931)); tablet/mobile attrs missing
- **Diagnose:** inspect `.eael-post-appender` for `data-ssi-tablet` / `data-ssi-mobile` attrs
- **Fix:** enable per-device switchers in panel; the initial render only emits desktop by default

## Known Limitations

- **`eael_woo_after_product_loop` fires THREE TIMES per render** ([lines 2948, 2955, 2961](../../includes/Elements/Woo_Product_Gallery.php#L2948)) — worst case in EA WC widgets. Listeners hit 3× per render; idempotency required.
- **`eael_woo_before_product_loop` fires TWICE** ([lines 2890, 2941](../../includes/Elements/Woo_Product_Gallery.php#L2890)) — once before template lookup, once before actual loop.
- **All emissions are no-arg** — differs from Product_Grid which passes `$preset_slug` to its first `eael_woo_before_product_loop` call. Listeners reading `$preset_slug` from this widget get `null`.
- **`id="eael-product-gallery"` is hardcoded** — multi-instance page produces duplicate HTML IDs. Same bug as Product_Grid.
- **`build_product_query()` locally defined** — drift risk with Product_Grid + Carousel.
- **`convert_wp_query_args_to_wc_product_query()` helper unclear-if-called** ([line 2995](../../includes/Elements/Woo_Product_Gallery.php#L2995)) — defined but no obvious caller in `render()`. Likely dead code or used by a different render path.
- **`localStorage.setItem('eael-cat-tab', 'true')` flag never read** ([line 21 of woo-product-gallery.js](../../src/js/view/woo-product-gallery.js#L21)) — dead JS code. localStorage pollution.
- **Safari bfcache hard-reload affects ALL browsers** ([line 154](../../src/js/view/woo-product-gallery.js#L154)) — `pageshow evt.persisted` true on Safari restore; not gated to Safari only. Firefox/Chrome users hit the same forced reload on back/forward navigation.
- **No `elementStatusCheck` on inline `<script>` for masonry init** — re-rendered editor preview can re-init Isotope multiple times.
- **Inline `<script>` adds `$(window).on('resize', isotope('layout'))` but main JS doesn't** ([line 2980-2982](../../includes/Elements/Woo_Product_Gallery.php#L2980)) — only inline (editor-preview) path debounces resize; frontend can lose layout on viewport resize for masonry.
- **Status allowlist not exposed in panel** (different from Product_Grid which has `eael_product_grid_products_status`) — `post_status='publish'` is forced in AJAX handler but not in initial render `build_product_query()` (relies on WC defaults).
- **`get_style_depends() = ['font-awesome-4-shim']` + `get_script_depends() = ['font-awesome-4-shim']`** — deprecated handles.
- **`eael_product_gallery_categories` + `_tags` are TEXT (CSV)** — comma-separated term IDs as text; no validation or sanitization in render path beyond `str_replace(' ', '')`. Malformed input (non-numeric, large lists) produces broken `<li>` tags.
- **Tab UI doesn't support nested taxonomy** — flat list only; hierarchical product categories render side-by-side, not as parent>child.
- **Tab `data-terms` JSON for `product_cat` uses `[$category->slug]` (slug array)** but for `product_tag` also uses `[$product_tag->slug]` — `tax_query` server-side uses `field: 'term_id'` in JS but `data-terms` carries slugs. Server-side `sanitize_taxonomy_data` may resolve via slug or term_id; brittle.
- **No `wpml_object_id` filter** — cross-language sites must reselect categories/tags per language tree.
- **First tab is unconditionally marked `active`** — even when the user navigated from a category page; no automatic restore of the active tab based on URL/referrer state.
