# Filterable Gallery Widget

> Isotope-driven gallery with five layouts (three Lite, two Pro), client-side category filters with progressive item-pool reveal, Magnific Popup lightbox (image + iframe video with `<video>` fallback on mobile), quick-search across items, "load more" pagination that reads from a base64-encoded JSON payload in `data-gallery-items`, optional randomization, and a privacy-notice overlay on YouTube/Vimeo popups. Five Pro extension hooks add Grid Flow and Harmonic layouts and per-item animation controls.

**Class file:** [`includes/Elements/Filterable_Gallery.php`](../../includes/Elements/Filterable_Gallery.php)
**Slug:** `filter-gallery` (widget id `eael-filterable-gallery`) ⚠ widget id differs from config slug — see [`docs/architecture/asset-loading.md § Common Pitfalls`](../architecture/asset-loading.md)
**Public docs:** <https://essential-addons.com/elementor/docs/filterable-gallery/>
**Pro-shared:** ✅ Yes — Pro adds `grid_flow_gallery` and `harmonic_gallery` layouts via five extension hooks: `eael_fg_caption_styles` (filter, chooser entries), `eael_grid_fg_item_animator_popover` (action, per-item animation controls in the Repeater), `eael_grid_flow_gallery_icon_control` (action, icon control in the Repeater), `eael_grid_flow_gallery_style` + `eael_harmonic_gallery_style` (actions, style-tab sections), and `add_filterable_gallery_style_block` (action in `render()`, replaces Lite's items wrap with Pro markup). Plus shared `load-more.js` + Isotope + Magnific Popup pipeline.

---

## Overview

Filterable Gallery is the largest Lite widget by code size (4719-line PHP class, 612-line JS, 1147-line SCSS). It renders a configurable grid of images and videos with category filter buttons, paginated Load More, and a click-to-zoom lightbox. The item list is shipped to the browser as a **base64-encoded JSON array of pre-rendered HTML strings** in `data-gallery-items` — JS runs each item through `DOMPurify.sanitize()` before injecting, splices the initially-shown items out of the pool, and lazily appends matching items on filter click or Load More. Quick-search across the entire pool (not just rendered items) is gated by an `eael_search_among_all` switcher. Three layouts in Lite (`hoverer` overlay, `card`, `layout_3` search-and-filter dropdown) plus two Pro layouts (`grid_flow_gallery`, `harmonic_gallery`) — Lite-only layouts share one render path; Pro layouts get their own item iterator via `gallery_item_store()` and a do_action handoff.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| `hoverer`, `card`, `layout_3` layouts | ✅ | ✅ |
| `grid_flow_gallery`, `harmonic_gallery` layouts | ❌ — picker lists them with "(Pro)" label; selecting them in Lite emits empty items wrap | ✅ via 5 extension hooks |
| Isotope grid + masonry | ✅ | ✅ |
| Magnific Popup lightbox (image + iframe video) | ✅ | ✅ |
| Quick-search across full item pool (not just rendered) | ✅ via `eael_search_among_all` | ✅ |
| Per-category Load More with item-pool depletion | ✅ | ✅ |
| Initial randomization (Fisher-Yates shuffle) | ✅ via `eael_item_randomize` | ✅ |
| Video gallery (YouTube/Vimeo/Self-hosted) + YT privacy mode | ✅ | ✅ |
| `<video>` tag swap for self-hosted MP4 on mobile | ✅ via `eael_fg_use_video_tag` | ✅ |
| Privacy notice overlay (5s) inside video popup | ✅ via `eael_privacy_notice_control` | ✅ |
| Mobile scroll-to-top on filter click | ✅ via `eael_fg_mobile_scroll_to_top` | ✅ |
| Per-item animation controls | ❌ | ✅ via `eael_grid_fg_item_animator_popover` |
| Default-active filter via `eael_fg_control_active_as_default` per Repeater row | ✅ | ✅ |
| FA4 → FA5 icon migration shim | ✅ — see [`_patterns.md § FA4`](_patterns.md#fa4--fa5-icon-migration-shim) | ✅ |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Filterable_Gallery.php`](../../includes/Elements/Filterable_Gallery.php) | PHP widget class (4719 lines) — controls, `render()`, `render_filters()`, `render_layout_3_filters()`, `render_gallery_items()`, `render_layout_3_gallery_items()`, `render_fg_buttons()`, `render_loadmore_button()`, `render_editor_script()`, `gallery_item_store()`, `video_gallery_switch_content()`, `gallery_item_full_image_clickable_content()`, `sorter_class()`, `eael_render_gallery_item_wrap()` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `eael_allowed_tags()`, `eael_wp_kses()`, `eael_validate_html_tag()`, `eael_e_optimized_markup()` |
| [`src/css/view/filterable-gallery.scss`](../../src/css/view/filterable-gallery.scss) | Source styles (1147 lines) — overlay/card variants, layout_3 search dropdown, Magnific Popup overrides, video-gallery layout, RTL |
| [`src/js/view/filterable-gallery.js`](../../src/js/view/filterable-gallery.js) | Frontend logic (612 lines) — Isotope init, Magnific Popup, click filter, quick-search, Load More, keyboard nav, EA cross-widget reflow listeners |
| [`config.php`](../../config.php#L395) entry `'filter-gallery'` | Asset declaration: load-more.min.css + magnific-popup.min.css + filterable-gallery.min.css + DOMPurify + imagesLoaded + Isotope + magnific-popup.min.js + filterable-gallery.min.js |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor — DOMPurify (sanitizes the base64-decoded item HTML before injection) |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | Vendor — Isotope (grid + masonry layout + filter) |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | Vendor — coordinates layout reflow after images load |
| `assets/front-end/js/lib-view/magnific-popup/jquery.magnific-popup.min.js` + CSS | Vendor — lightbox |
| `assets/admin/images/layout-previews/filterable-gallery-*.png` | Image-picker thumbnails for layout chooser |

## Architecture

- **Items shipped as base64-encoded JSON of pre-rendered HTML** — `render()` builds HTML strings for every Repeater item via `render_gallery_items()` / `render_layout_3_gallery_items()`, JSON-encodes the array, base64-encodes that, and stores it in `data-gallery-items` on `.eael-filter-gallery-container` ([line 4415-4420](../../includes/Elements/Filterable_Gallery.php#L4415)). Only the first N items (`eael_fg_items_to_show`, default 6) are echoed into the DOM; the rest live in the data attribute. JS decodes `atob(data)` → `JSON.parse` → `fg_items.map(DOMPurify.sanitize)` ([JS line 154-163](../../src/js/view/filterable-gallery.js#L154)), then `splice(0, $init_show)` removes the already-rendered ones from the pool. Filter clicks and Load More append from this in-memory pool — no AJAX. This is fundamentally different from Post_Grid's AJAX `load_more` endpoint.
- **DOMPurify is required not optional** — the base64-encoded item HTML is run through `DOMPurify.sanitize()` before injection. Without it, an admin who can edit gallery item HTML could ship XSS to visitors through the WYSIWYG content field (filtered server-side via `wp_kses(Helper::eael_allowed_tags())` already, but DOMPurify is the second line).
- **Filter buttons are local CSS classes derived from category strings** — `sorter_class()` at [line 3584-3625](../../includes/Elements/Filterable_Gallery.php#L3584) normalizes a category string into a CSS-class-safe slug via ~30 character substitutions (`,-` → ` eael-cf-`, `&` → `and`, `#` → `hash`, `>` → `greaterthan`, etc.). Items get `eael-cf-<slug>` class; filter buttons get `data-filter=".eael-cf-<slug>"`. Multi-category items use space-separated `eael-cf-` prefixes (the `,-` → ` eael-cf-` rule does the work).
- **Quick-search across full pool with lazy materialization** — when `eael_search_among_all == 'yes'`, the first keystroke triggers a one-time pool flush ([JS line 441-456](../../src/js/view/filterable-gallery.js#L441)) that appends **every** remaining item to the DOM, then hides Load More. Subsequent keystrokes are debounced 600ms and rebuild the regex used in Isotope's `filter()` callback. Cheap text-search of `$this.text().match(searchRegex)` — no fuzzy matching, no indexing.
- **Five Pro extension hooks at distinct lifecycle points** — `eael_fg_caption_styles` filter at [line 110](../../includes/Elements/Filterable_Gallery.php#L110) adds Grid Flow / Harmonic chooser entries; `eael_grid_fg_item_animator_popover` action at [line 715](../../includes/Elements/Filterable_Gallery.php#L715) injects per-item animation controls; `eael_grid_flow_gallery_icon_control` action at [line 837](../../includes/Elements/Filterable_Gallery.php#L837) injects an icon control into the Repeater; `eael_grid_flow_gallery_style` + `eael_harmonic_gallery_style` actions at [line 2184 / 2191](../../includes/Elements/Filterable_Gallery.php#L2184) add style-tab sections; `add_filterable_gallery_style_block` action at [line 4441](../../includes/Elements/Filterable_Gallery.php#L4441) in `render()` lets Pro emit its own items wrap instead of Lite's standard `eael_render_gallery_item_wrap()`. All hooks are un-prefixed (no `eael/`) — legacy naming.
- **Magnific Popup config has video-on-mobile fallback** — for self-hosted MP4 when `eael_fg_use_video_tag == 'yes'` and the user-agent matches mobile regex, the popup's `markupParse` callback at [JS line 244-254](../../src/js/view/filterable-gallery.js#L244) rewrites the `<iframe>` to a `<video autoplay controls playsinline>`. Solves iOS Safari's iframe-video autoplay restrictions.
- **Privacy notice for embedded video** — `eael_privacy_notice` text is stored in `data-privacy-notice`; on popup open, JS injects it into `.eael-privacy-message` for 5 seconds, then removes it ([JS line 256-264](../../src/js/view/filterable-gallery.js#L256)).
- **YouTube/Vimeo URL normalization** — `gallery_item_store()` at [line 3789-3809](../../includes/Elements/Filterable_Gallery.php#L3789) regex-extracts video IDs from `youtu.be`, `youtube.com/shorts/`, and standard URLs, normalizes to `youtube.com/watch?v=<id>`. When `video_gallery_yt_privacy == 'yes'`, swaps to `youtube-nocookie.com/embed/` and adds `dnt=1` to Vimeo URLs.
- **Editor preview is a separate inline script block** — `render_editor_script()` at [line 4466](../../includes/Elements/Filterable_Gallery.php#L4466) emits a duplicate Isotope init for the Elementor editor iframe. Changes to frontend init need mirroring there.
- **Cross-widget reflow listeners** — JS subscribes to `ea-toggle-triggered`, `ea-lightbox-triggered`, `ea-advanced-tabs-triggered`, `ea-advanced-accordion-triggered` ([JS line 589-600](../../src/js/view/filterable-gallery.js#L589)). When any of these fires (e.g., a tab is opened and reveals a hidden Filterable Gallery), it re-runs `imagesLoaded().progress(layout)` to fix zero-width Isotope measurements.
- **Widget id ≠ slug** — `get_name()` returns `eael-filterable-gallery` while config slug is `filter-gallery`. JS handler binds to `frontend/element_ready/eael-filterable-gallery.default`. Asset_Builder uses the slug key.

## Render Output

```html
<div id="eael-filter-gallery-wrapper-<widget-id>"
     class="eael-filter-gallery-wrapper"
     data-layout-mode="hoverer | card | layout_3 | grid_flow_gallery | harmonic_gallery"
     data-default_control_key="0"        ← integer index of the active-default filter
     data-custom_default_control="1|0"   ← 1 if any per-item active-default OR "All" label is empty
     [?] data-breakpoints="<json>">      ← Elementor breakpoints config

  [?] <!-- Standard filter bar (hoverer, card, grid_flow, harmonic) -->
  <div class="eael-filter-gallery-control">
    <ul>
      [?] <li class="control all-control [active]" data-filter="*" data-load-more-status="0" data-first-init="1">All</li>
      <!-- Per Repeater control row: -->
      <li [id="<custom-id>"]
          class="control [active]"
          data-filter=".eael-cf-<sorter-slug>"
          data-load-more-status="0"
          data-first-init="0"
          tabindex="0|-1">Category Label</li>
      …
    </ul>
  </div>

  [?] <!-- Layout_3 filter bar (search + dropdown) -->
  <div class="fg-layout-3-filters-wrap">
    <div class="fg-filter-wrap">
      <button id="fg-filter-trigger" class="fg-filter-trigger">
        <span>All</span><i class="fas fa-angle-down"></i>
      </button>
      <ul class="fg-layout-3-filter-controls">
        <li class="control active" data-filter="*">All</li>
        <li class="control" data-filter=".eael-cf-<slug>">Category</li>
        …
      </ul>
    </div>
    <form class="fg-layout-3-search-box" id="fg-layout-3-search-box" autocomplete="off">
      <input type="text" id="fg-search-box-input" name="fg-frontend-search" placeholder="…">
    </form>
  </div>

  <!-- LITE renders this; Pro `grid_flow_gallery` / `harmonic_gallery` skip this and emit their own via `add_filterable_gallery_style_block` action -->
  <div class="eael-filter-gallery-container [eael-filter-gallery-grid | masonry]"
       data-images-per-page="6"
       data-total-gallery-items="N"
       data-nomore-item-text="…"
       data-is-randomize="yes|no"
       data-settings="<json — grid_style, popup, duration, gallery_enabled, video_gallery_yt_privacy, control_all_text, mobile_scroll_to_top, mobile_scroll_offset, post_id, widget_id>"
       data-search-all="yes|no"
       data-gallery-items="<base64-encoded JSON of pre-rendered item HTML strings>"
       data-init-show="6"
       [?] data-privacy-notice="…"
       [?] data-use-video-tag="yes">

    <!-- First N items echoed into DOM; rest live in data-gallery-items -->
    <div class="eael-filterable-gallery-item-wrap eael-cf-<slug>" data-search-key="lowercased-title">
      <div class="eael-gallery-grid-item">  <!-- or .fg-layout-3-item for layout_3 -->
        [?] <a> wrapper if full-image-clickable
        <div class="gallery-item-thumbnail-wrap">
          <img src="…" alt="…" class="gallery-item-thumbnail">
        </div>
        <div class="gallery-item-caption-wrap caption-style-hoverer | caption-style-card">
          [?] {fg-caption-head with price + ratings + category}
          [?] {video-gallery-switch content}
          [?] {gallery-item-buttons: <a class="eael-magnific-link eael-magnific-link-clone active">…</a>}
        </div>
      </div>
    </div>
    …

    [?] <div id="eael-fg-no-items-found" style="display:none;">…</div>   ← layout_3 only
  </div>

  [?] <!-- Editor-only inline <script> for Elementor preview -->
  <script>jQuery(document).ready(function($){ … inline Isotope init … });</script>

  [?] <!-- Load More button (when pagination == 'yes') -->
  <div class="eael-filterable-gallery-loadmore">
    <button class="eael-gallery-load-more elementor-button elementor-size-<size>">
      <span class="eael-btn-loader"></span>
      [?] {icon before/after}
      <span class="eael-filterable-gallery-load-more-text">Load More</span>
    </button>
  </div>
</div>
```

Notes:

- `.eael-magnific-link-clone.active` is the Magnific Popup delegate selector. The `:not([style*='display: none'])` filter on the delegate ensures only currently-visible items become part of the popup gallery's prev/next chain ([JS line 209-210](../../src/js/view/filterable-gallery.js#L209)).
- `data-gallery-items` is base64-encoded JSON, NOT raw HTML — `atob()` + `JSON.parse()` + per-item `DOMPurify.sanitize()` required on the JS side.
- `data-search-key` is the lowercased, hyphen-joined item title; not used by the regex-search code (which searches DOM text), but available for custom extensions.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Filterable_Gallery.php#L97) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_fg_caption_style` | CHOOSE (image picker) | `hoverer` | Content → Settings | Top-level layout: `hoverer`, `card`, `layout_3` (Lite), `grid_flow_gallery`, `harmonic_gallery` (Pro) |
| `eael_fg_items_to_show` | NUMBER (dynamic) | `6` | Content → Settings | Initial render count; rest live in `data-gallery-items` until filter/Load More |
| `eael_fg_filter_duration` | NUMBER (ms) | `500` | Content → Settings | Isotope `transitionDuration` |
| `columns` | CHOOSE (responsive) | `3` desktop / `2` tablet / `1` mobile | Content → Settings | Grid column count |
| `eael_fg_grid_style` | CHOOSE | `grid` | Content → Settings | `grid` vs `masonry`; controls Isotope `layoutMode` |
| `eael_fg_grid_item_height` | NUMBER (px) | `300` | Content → Settings | Image height when `grid_style == 'grid'`; via `selectors` |
| `eael_search_among_all` | SWITCHER | empty | Content → Settings | When `yes`, first keystroke materializes the full pool into DOM (one-time flush) |
| `filter_enable` | SWITCHER | `yes` | Content → Filter | Toggles filter bar render |
| `eael_fg_all_label_text` | TEXT (dynamic) | `All` | Content → Filter | "All" filter button label; empty hides the button |
| `eael_fg_show_popup` | CHOOSE | `buttons` | Content → Settings | `none` / `media` (full image clickable to lightbox) / `buttons` (zoom + link icons) |
| `eael_title_clickable` / `eael_section_fg_full_image_clickable` | SWITCHER | empty | Content → Settings | Wraps title / image in `<a href="<gallery_link>">` |
| `eael_section_fg_mfp_caption` | SWITCHER | empty | Content → Settings | Show item title as Magnific Popup title |
| `eael_section_fg_zoom_icon_new` / `_link_icon_new` (+ FA4 shim) | ICONS | `fas fa-search-plus` / `fas fa-link` | Content → Settings | Per-item zoom + link icons |
| `eael_fg_mobile_scroll_to_top` / `_offset` | SWITCHER / NUMBER | empty / 0 | Content → Settings | Animate scroll to gallery container on filter click (≤767px viewport only) |
| `eael_privacy_notice_control` / `eael_privacy_notice` | SWITCHER / TEXTAREA | empty | Content → Settings | Show 5s notice text inside video popup |
| `eael_item_randomize` | SWITCHER | empty | Content → Settings | Fisher-Yates shuffle on init (re-shuffles on each page load) |
| `eael_fg_use_video_tag` | SWITCHER | empty | Content → Settings | Swap `<iframe>` → `<video>` for self-hosted MP4 on mobile |
| `video_gallery_yt_privacy` | SWITCHER | empty | Content → Gallery Items | Use `youtube-nocookie.com` + Vimeo `dnt=1` |
| `photo_gallery` | SWITCHER | `yes` | Content → Gallery Items | Magnific Popup `gallery.enabled` — chained prev/next nav vs single-item popups |
| `eael_fg_gallery_items` | REPEATER | empty | Content → Gallery Items | Per-item settings (image, video, title, content, category, link, lightbox toggle, price, ratings) |
| `eael_fg_controls` | REPEATER | empty | Content → Filter | Per-filter-button settings (label, category match, active-default, custom id) |
| `pagination` | SWITCHER | empty | Content → Pagination | Show Load More button |
| `images_per_page` | NUMBER | `6` | Content → Pagination | Items to append per Load More click |
| `nomore_items_text` | TEXT | `No more items!` | Content → Pagination | Text shown when pool depleted |
| `load_more_text` / `load_more_icon_new` (+ FA4 shim) | TEXT / ICONS | `Load More` / icon | Content → Pagination | Button label + icon |
| `eael_fg_not_found_text` | TEXT | `No items found!` | Content → Settings | Layout_3 only — empty-search message |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| `title_tag` | SELECT | `h2` | Content / Style | Item title HTML tag; validated via `Helper::eael_validate_html_tag()` |
| Style → Filter / Item / Image / Title / Content / Button / Layout_3 / Popup / No-items / Pro layouts | various | — | Style tab | Typography, padding, borders, backgrounds, popup background, layout-specific styles |

### Per-item Repeater controls (`eael_fg_gallery_items`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_fg_gallery_item_name` | TEXT (dynamic, AI) | empty | Item title |
| `eael_fg_gallery_item_content` | TEXTAREA (dynamic, AI) | empty | Item description (wp_kses-filtered with `Helper::eael_allowed_tags()`) |
| `eael_fg_gallery_img` | MEDIA | placeholder | Item image |
| `eael_fg_gallery_link` | SWITCHER | empty | Whether title/image links somewhere |
| `eael_fg_gallery_img_link` | URL | empty | Link target (when `gallery_link == 'yes'`) |
| `eael_fg_gallery_lightbox` | SWITCHER | `yes` | Show this item in the Magnific Popup chain |
| `eael_fg_gallery_control_name` | TEXT | empty | Category label(s) — comma-separated for multi-category; passed through `sorter_class()` |
| `fg_video_gallery_switch` | SWITCHER | empty | Mark item as video instead of image |
| `eael_fg_gallery_item_video_link` | URL | empty | YouTube / Vimeo / Self-hosted URL; normalized in `gallery_item_store()` |
| `fg_video_gallery_play_icon` | ICONS | play icon | Play overlay icon |
| `fg_item_price_switch` / `fg_item_price` | SWITCHER / TEXT | empty | Price badge |
| `fg_item_ratings_switch` / `fg_item_ratings` | SWITCHER / TEXT | empty | Ratings badge |
| `fg_item_cat_switch` / `fg_item_cat` | SWITCHER / TEXT | empty | Category label (display) |

### Per-filter Repeater controls (`eael_fg_controls`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_fg_control` | TEXT | `Category` | Category match string (must match `eael_fg_gallery_control_name` on items) |
| `eael_fg_control_label` | TEXT | empty | Display label; falls back to `eael_fg_control` |
| `eael_fg_control_active_as_default` | SWITCHER | empty | Pre-activate this filter on init |
| `eael_fg_control_custom_id` | TEXT | empty | DOM `id` for direct deep-linking via `#hash` |

## Conditional Dependencies

```text
# Layout-driven (lots of controls hide for grid_flow_gallery / harmonic_gallery)
eael_fg_filter_duration                   → visible when eael_fg_caption_style in [hoverer, card, layout_3]
eael_fg_grid_style                        → same
columns                                   → all layouts
eael_fg_grid_item_height                  → visible when eael_fg_grid_style == 'grid'
eael_fg_show_popup                        → visible when caption_style in [hoverer, card, layout_3]
eael_title_clickable                      → same
eael_section_fg_full_image_clickable      → same
eael_section_fg_mfp_caption               → same
eael_section_fg_zoom_icon_new             → visible when show_popup == 'buttons'
                                              AND full_image_clickable != 'yes'
                                              AND caption_style != [grid_flow, harmonic]
eael_section_fg_link_icon_new             → similar
photo_gallery                             → visible when caption_style != [grid_flow, harmonic]
video_gallery_yt_privacy                  → same
eael_fg_mobile_scroll_offset              → visible when eael_fg_mobile_scroll_to_top == 'yes'
eael_privacy_notice                       → visible when eael_privacy_notice_control == 'yes'

# Per-item video controls
eael_fg_gallery_item_video_link           → visible when fg_video_gallery_switch == 'true'
fg_video_gallery_play_icon                → same
(price/ratings/category — when respective switch == 'true')

# Pagination
images_per_page / nomore_items_text /
load_more_text / load_more_icon_new /
button_size / button_icon_position        → visible when pagination == 'yes'

# Pro upsell
eael_section_pro / eael_control_get_pro   → visible when Pro plugin is NOT active
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_fg_caption_styles` | filter (emitted) | `array { styles, conditions }` | **Pro extension** — adds `grid_flow_gallery` + `harmonic_gallery` chooser entries; `conditions` array drives Pro-alert visibility. |
| `eael_grid_fg_item_animator_popover` | action (emitted) | `(Filterable_Gallery $widget)` | **Pro extension** — injects per-item animation popover controls during `register_controls`. ⚠ Un-prefixed; legacy. |
| `eael_grid_flow_gallery_icon_control` | action (emitted) | `(Repeater $repeater)` | **Pro extension** — injects an icon control into the gallery-items Repeater (signature takes the Repeater, not the widget). ⚠ Un-prefixed; legacy. |
| `eael_grid_flow_gallery_style` | action (emitted) | `(Filterable_Gallery $widget)` | **Pro extension** — injects style-tab section for Grid Flow layout. ⚠ Un-prefixed; legacy. |
| `eael_harmonic_gallery_style` | action (emitted) | `(Filterable_Gallery $widget)` | **Pro extension** — injects style-tab section for Harmonic layout. ⚠ Un-prefixed; legacy. |
| `add_filterable_gallery_style_block` | action (emitted in `render()`) | `(array $settings, Filterable_Gallery $widget, array $gallery_items_pro)` | **Pro extension** — for grid_flow / harmonic layouts only, Pro emits its own items wrap; Lite skips `eael_render_gallery_item_wrap()`. ⚠ Un-prefixed; **no `eael_` prefix at all**. |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the `eael_section_pro` upsell when Pro is active. |

JS-side custom events / listeners:

- `eael:filterable-gallery:items-loaded` — jQuery event fired on `document` after every filter or Load More append; carries `[$scope.data("id")]`. Pro Grid Flow / Harmonic layouts listen for this to re-run their own layout pass.
- Consumes (re-layouts on): `ea-toggle-triggered`, `ea-lightbox-triggered`, `ea-advanced-tabs-triggered`, `ea-advanced-accordion-triggered`.
- No `window.X` global; `Isotope` and `magnificPopup` are jQuery plugins.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): FA4 shim, `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on("elementor/frontend/init", …)` → `elementorFrontend.hooks.addAction('frontend/element_ready/eael-filterable-gallery.default', filterableGalleryHandler)`. Older registration pattern (NOT the newer `eael.hooks.addAction("init", "ea", …)`).
- **Guard:** `if (eael.elementStatusCheck('eaelFilterableGallery')) return false;` at module top — prevents double-init.
- **Vendor dependencies:** Isotope (grid + masonry + filter), imagesLoaded, Magnific Popup, DOMPurify (item HTML sanitization).
- **Reads on init:** `data-settings` (JSON: grid_style, popup, duration, gallery_enabled, video_gallery_yt_privacy, control_all_text, mobile_scroll_to_top, mobile_scroll_offset, post_id, widget_id), `data-gallery-items` (base64 JSON of item HTML strings), `data-images-per-page`, `data-init-show`, `data-is-randomize`, `data-total-gallery-items`, `data-nomore-item-text`, `data-search-all`, `data-privacy-notice`, `data-use-video-tag`, `data-default_control_key`, `data-custom_default_control`.
- **Branches:**
  - `is_randomize === 'yes'` — Fisher-Yates shuffle the items array, then re-append first N items (empties + re-fills the gallery container).
  - on filter click — first click on a non-`*` filter triggers a lazy materialization of matching items from the pool (up to `images_per_page`); subsequent clicks just refilter.
  - on Load More — append next `images_per_page` matching items from the pool; when pool exhausted, show no-more-items-text and fade out.
  - on quick-search input — debounced 600ms; if `search-all == 'yes'`, one-time pool flush; build regex, re-filter.
- **Custom default control:** on init, if `custom_default_control == 1`, click the `nth-child(default_control_key + increment)` filter button (increment is 2 if the All label is present, else 1).
- **Hash deep-link:** if `window.location.hash` is set, click the matching `id` — covers per-filter `eael_fg_control_custom_id` deep-linking.
- **Keyboard a11y:** Arrow Left / Arrow Right cycles filter buttons; `tabindex` shifted so only `.active` has `0`. Same pattern as Adv_Tabs.
- **Magnific Popup `delegate` selector** excludes items hidden by Isotope (`:not([style*='display: none'])`) so the prev/next chain only walks visible items.
- **Cross-widget reflow:** subscribes to four EA events to re-layout when the gallery becomes visible after being hidden in a tab/accordion/lightbox/toggle.

## Common Issues

### Items appear stacked / clipped on first load

- **Likely cause:** Isotope measured the container at zero width while the gallery was inside a hidden tab/accordion.
- **Diagnose:** does the parent widget fire one of the cross-widget triggers (`ea-advanced-tabs-triggered`, etc.) on activation?
- **Fix:** working as intended via the four `eael.hooks.addAction(…, FilterableGallery)` calls; if it still stacks, the parent doesn't fire the trigger. Trigger `eael.hooks.doAction("ea-advanced-tabs-triggered", $container)` manually from custom JS.

### Filter button clicks first time show fewer items than `images_per_page`

- **Likely cause:** the **first click** on a non-`*` filter only pulls items from the pool that match the filter (capped at `images_per_page`). If the pool has fewer matching items than initially-rendered ones already on-screen, no new items appear.
- **Diagnose:** check `data-first-init` on the button — `0` means it'll do the pool materialization; `1` means already done.
- **Fix:** working as designed; ensure `eael_fg_items_to_show` is small enough relative to per-category counts so the first click actually adds items.

### Self-hosted MP4 video stuck on iOS — won't autoplay or shows iframe

- **Likely cause:** iOS Safari blocks iframe-embedded autoplay; the `<iframe>` wrapping never plays.
- **Diagnose:** check `eael_fg_use_video_tag` is `yes`.
- **Fix:** turning on `Use Video Tag for Mobile` triggers the `markupParse` callback to rewrite `<iframe>` → `<video autoplay controls playsinline>` ([JS line 244-254](../../src/js/view/filterable-gallery.js#L244)). Only triggers on iOS user agents matching the mobile regex.

### Quick-search returns no results across all items

- **Likely cause:** `eael_search_among_all` is off — search only scans already-rendered items.
- **Diagnose:** open the panel, check the "Search All Items" toggle.
- **Fix:** turn it on. Then the first keystroke does a one-time pool flush into the DOM; Isotope's `filter()` callback then scans the full set via `$this.text().match(searchRegex)`.

### Custom ID hash deep-link doesn't activate the filter

- **Likely cause:** hash matches a filter button `id`; JS clicks it on `document.ready`. If the element doesn't exist yet (lazy-loaded section), the click fires on nothing.
- **Diagnose:** browser console — `$('#<hash>')` returns 0 elements at `ready`?
- **Fix:** ensure the Filterable Gallery section is in the initial viewport / DOM; lazy-loaded sections need a custom retry handler.

### `category, sub-category` shows as two separate filters but item only matches one

- **Likely cause:** `sorter_class()` splits on `,-` (comma + dash) to multi-class items. Comma alone is replaced with `comma`. So `category, sub-category` becomes `eael-cf-category eael-cf-sub-category` — but typed as `category,subcategory` (no space-dash) becomes `eael-cf-categorycommasubcategory` — a single class.
- **Diagnose:** inspect the rendered item element's `class` attribute.
- **Fix:** use `category, sub-category` format (comma-space) on item rows; the filter button category names should also be space-separated when the item belongs to multiple categories.

### Editor preview doesn't filter / lightbox doesn't open

- **Likely cause:** editor preview uses the inline `<script>` block from `render_editor_script()`, which is a duplicated copy of frontend init. If the frontend JS has changed but the inline script hasn't, behaviour diverges.
- **Diagnose:** compare `render_editor_script()` against `filterable-gallery.js`.
- **Fix:** edit both. Known maintenance burden.

## Known Limitations

- **All five Pro extension hooks are un-prefixed legacy** — `eael_fg_caption_styles`, `eael_grid_fg_item_animator_popover`, `eael_grid_flow_gallery_icon_control`, `eael_grid_flow_gallery_style`, `eael_harmonic_gallery_style`, `add_filterable_gallery_style_block`. The last has **no `eael` prefix at all**. Renaming requires dual-emit migration to avoid breaking Pro.
- **Items pool is per-page-load, not session-persistent** — randomize re-shuffles on every refresh, so users see different content each visit. Not configurable.
- **`data-gallery-items` payload size grows linearly with item count** — base64 encoding inflates ~33% over raw JSON. A 50-item gallery with rich content can push the attribute past 100 KB. No pagination at the data-attribute level; everything ships up front.
- **DOMPurify is mandatory** — disabling it would expose XSS through any HTML in `eael_fg_gallery_item_content`. The `wp_kses` server-side filter is the first defense, but DOMPurify catches anything that slipped through.
- **`sorter_class()` is a hand-rolled slugifier with ~30 character substitutions** — `sanitize_title_with_dashes()` would be safer / faster but breaks backwards compatibility (existing items have classes like `eael-cf-cat-hash` for `cat#`).
- **Editor preview script duplicates frontend init** — two parallel `magnificPopup()` + `isotope()` calls; changes in one must mirror in the other. No shared module.
- **Quick-search is regex over DOM text** ([JS line 187](../../src/js/view/filterable-gallery.js#L187)) — special chars in user input (e.g., `.`, `*`, `(`) silently become regex tokens, sometimes matching unexpectedly or throwing if invalid (caught by browsers' lenient regex but produces empty results).
- **Privacy notice removal is unconditional 5s `setTimeout`** ([JS line 262-264](../../src/js/view/filterable-gallery.js#L262)) — if the user lingers past 5s, no notice is shown again on prev/next within the same popup.
- **Mobile scroll-to-top is hardcoded `<= 767px`** ([JS line 77](../../src/js/view/filterable-gallery.js#L77)) — doesn't respect Elementor's `tablet_default` breakpoint. May fire on tablets with custom breakpoint overrides.
- **No `role="grid"` / `role="gridcell"`** on the gallery container — keyboard-accessible filter buttons exist, but screen readers see the grid as generic divs.
- **`localize?.eael_translate_text?.fg_mfp_counter_text`** at [JS line 56-60](../../src/js/view/filterable-gallery.js#L56) depends on a global `localize` object (set up by `Asset_Builder`); if missing, falls back to `"%curr% of %total%"` literal — works but ignores translation.
- **`get_style_depends()` returns `font-awesome-4-shim`** at [line 53](../../includes/Elements/Filterable_Gallery.php#L53) which is a deprecated handle in modern Elementor versions.
- **`is_dynamic_content()` always returns `false`** at [line 84-86](../../includes/Elements/Filterable_Gallery.php#L84) — Elementor's render cache is always active, even when items contain dynamic tags. May cause stale cached output after Repeater edits in some configurations.
