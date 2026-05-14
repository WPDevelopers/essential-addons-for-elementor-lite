# Woo Product Images Widget

> Single-product image gallery — main Swiper + linked thumbnail Swiper + Magnific Popup lightbox + image zoom (lens or magnify). Renders the current product's featured + gallery images on any page that has a global `$product` context. **Stateless: panel-selected `Sale Flash`** styling, **runtime-driven Variation sync** (listens to WC's `show_variation`/`hide_variation` events and swaps main + thumb image attributes), **placeholder mockup in editor**. Lite-only widget — no Pro extension; no `eael_section_pro` upsell.

**Class file:** [`includes/Elements/Woo_Product_Images.php`](../../includes/Elements/Woo_Product_Images.php)
**Slug:** `woo-product-images` (widget id `eael-woo-product-images`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-images/>
**Pro-shared:** ❌ No — Lite-only widget. No `eael/pro_enabled` gates; no Pro extension hooks; only one public filter (`eael_product_image_product_id`).

---

## Overview

Replicates WC's single-product gallery UI as an Elementor widget that works on any page with a `$product` context (theme builder Single Product, archive Loop Grid items). Renders a main image Swiper synced to a thumbnail Swiper via Swiper's `thumbs.swiper` config. 6 transition effects (slide / fade / cards / cube / flip / coverflow) — many require Swiper Effect modules not always loaded by Elementor's `e-swiper` handle. Thumbnail position toggles between bottom (horizontal Swiper) and left/right (vertical Swiper via `direction: vertical` in breakpoint config).

Three independent vendor libraries beyond Swiper: `zoom-lense.min.js` (custom; `$.fn.eaelZoomLense` + `$.fn.eaelMagnify` plugins), Magnific Popup (lightbox), and reuses `filterable-gallery.min.css` from the Filter_Gallery widget. WC variation product support: JS listens for `show_variation`/`hide_variation`/`reset_image` events on `.variations_form` and swaps gallery image attributes (`src`, `srcset`, `sizes`, `data-src`, `data-large_image`) directly — no swiper re-init.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Main + thumbnail synced Swipers | ✅ | ✅ |
| 6 transition effects (slide / fade / cards / cube / flip / coverflow) | ✅ | ✅ |
| 3 thumb positions (bottom / left / right) | ✅ | ✅ |
| Magnific Popup lightbox | ✅ | ✅ |
| Image zoom: Lens + Magnify modes | ✅ | ✅ — Inside Image mode panel option commented out; `zoomInsideEffect()` JS stub empty |
| WC variation image sync (`show_variation` event) | ✅ | ✅ |
| Sale Flash (`onsale` span) styling controls | ✅ | ✅ |
| Per-device thumb item counts (desktop/tablet/mobile) | ✅ | ✅ |
| Mobile-specific thumb height | ✅ | ✅ |
| 7 image resolutions (full → 2048x2048) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro-specific features | — | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Images.php`](../../includes/Elements/Woo_Product_Images.php) | PHP widget class (1173 lines) — controls, `render()`, `render_image_slider()`, `render_thumbnail_slider()`, `render_slide()`, `eael_pi_data_settings()` (builds JSON config object for JS) |
| [`src/css/view/woo-product-images.scss`](../../src/css/view/woo-product-images.scss) | Source styles (239 lines) — Swiper containers, thumb positioning, sale flash, zoom lens |
| [`src/js/view/woo-product-image.js`](../../src/js/view/woo-product-image.js) | Frontend logic (341 lines) — initializes thumb swiper first, then main swiper with thumb reference, magnific popup, zoom lens, variation event handlers |
| [`config.php`](../../config.php#L737) entry `'woo-product-images'` | Asset declaration: woo-product-images.min.css + swiper-bundle.min.css + magnific-popup.min.css + **filterable-gallery.min.css** (cross-widget) + zoom-lense.min.js + magnific-popup.min.js + woo-product-image.min.js |
| `assets/front-end/js/lib-view/image-zoom/zoom-lense.min.js` | Vendor — `$.fn.eaelZoomLense` + `$.fn.eaelMagnify` plugins |
| `assets/front-end/js/lib-view/magnific-popup/jquery.magnific-popup.min.js` | Vendor — lightbox |
| `assets/front-end/css/lib-view/swiper/swiper-bundle.min.css` | ⚠️ Vendor — **bundled Swiper CSS DUPLICATE** of Elementor's `e-swiper` handle declared via `get_style_depends()` |
| `assets/front-end/css/lib-view/magnific-popup/magnific-popup.min.css` | Vendor — lightbox CSS |
| `assets/front-end/css/view/filterable-gallery.min.css` | **Cross-widget asset** — loads even when Filter_Gallery widget not on page (wasted bandwidth) |

`get_style_depends() = ['e-swiper']` declared, but config.php ALSO bundles `swiper-bundle.min.css` — duplicate Swiper CSS shipped per page.

## Architecture

- **Main + thumb Swiper synced via `thumbs.swiper`** ([JS line 197-227](../../src/js/view/woo-product-image.js#L197)) — thumb Swiper initialized first; resolved Swiper instance passed to main Swiper config as `thumbs.swiper`. Main Swiper async-loaded after thumb resolves. Chained Promises via `swiperLoader` helper.
- **JSON-encoded data attributes carry Swiper config** ([PHP line 1086-1091, 916-948](../../includes/Elements/Woo_Product_Images.php#L1086)) — `eael_pi_data_settings()` builds an associative array, JSON-encodes via `json_encode($sliderThumbs)` → `data-pi_thumb` attribute on `.product_image_slider__thumbs`. JS reads `data("pi_thumb")` and passes directly to Swiper constructor. Identical pattern for main slider's `data-pi_image`.
- **Editor preview is hardcoded placeholder mockup** ([line 1153-1158](../../includes/Elements/Woo_Product_Images.php#L1153)) — when `Plugin::$instance->editor->is_edit_mode()` OR `get_post_type() === 'templately_library'`, fills with 6 copies of `EAEL_PLUGIN_URL . 'assets/front-end/img/eael-default-placeholder.png'`. Frontend: reads `$product->get_gallery_image_ids()` merged with featured image id.
- **WC variation event sync** ([JS line 31-122](../../src/js/view/woo-product-image.js#L31)) — listens for `show_variation` on `.variations_form` (WC native event); reads `variation.image.{src,srcset,sizes,full_src,gallery_thumbnail_src,*_h}` and patches both main + thumb image attributes; stops Swiper autoplay + slides to position 0; re-initializes zoom lens after 100ms delay (`setTimeout` to let attributes settle). `hide_variation` / `reset_image` events restore original attributes with `fadeOut(100) → attr swap → fadeIn(100)` chain. **No Swiper destroy/re-init** — pure DOM attribute swap.
- **Original image attribute snapshot at JS init** ([JS line 18-19](../../src/js/view/woo-product-image.js#L18)) — `getImageAttributes($productGalleryImage)` captures `{src, srcset, sizes}` once on init; never updated after. If WC navigates to a new product via AJAX (unlikely with this widget), snapshot is stale.
- **`$(".eael-single-product-images")` selector NOT scoped to `$scope`** ([JS line 3](../../src/js/view/woo-product-image.js#L3)) — global jQuery query at JS init. Multi-instance page → first widget's images returned for all instances. Likely multi-instance bug.
- **HARDCODED 5-breakpoint Swiper config** ([PHP line 1029-1077](../../includes/Elements/Woo_Product_Images.php#L1029)) — `[320, 768, 1024, 1440, 1920]` — does NOT honor Elementor's responsive breakpoints. For left/right thumb position, same breakpoints with `direction: vertical` added.
- **`$(window).on('load')` mobile-height calc not Elementor-scoped** ([JS line 163-192](../../src/js/view/woo-product-image.js#L163)) — runs on global window load, not `frontend/element_ready`. Re-init via Elementor preview doesn't re-fire. `matchMedia('(max-width: 767px)')` → adjusts `.eael-pi-thumb-{left,right} .product_image_slider__thumbs` height to `min(slidesPerView × height_for_mobile, image_height)`.
- **`zoomInsideEffect()` is empty stub** ([JS line 316-318](../../src/js/view/woo-product-image.js#L316)) — corresponding panel option (`'inside'` in `eael_zoom_effect_type` SELECT) is commented out. Dead code path.
- **Magnific Popup builds items array per-click** ([JS line 233-252](../../src/js/view/woo-product-image.js#L233)) — `.product_image_slider__trigger a` click reads all `.swiper-slide .image_slider__image img` `src` attributes into items array; no caching, no preload. Each click rebuilds.
- **`eael_product_image_product_id` filter** ([PHP line 1140](../../includes/Elements/Woo_Product_Images.php#L1140)) — override the product ID resolution. Useful for "show this product on an unrelated page" scenarios.
- **`get_style_depends() = ['e-swiper']`** declared BUT config.php also bundles `swiper-bundle.min.css` — DUPLICATE Swiper CSS shipped per page (~80KB Swiper CSS twice).
- **`filterable-gallery.min.css` loaded as cross-widget dependency** — even when Filter_Gallery widget is not on the page. Wasted bandwidth (~50KB extra).
- **Render exits silently when no `$product`** ([PHP line 1160-1162](../../includes/Elements/Woo_Product_Images.php#L1160)) — no editor warning, no admin notice. Same pattern as Woo_Add_To_Cart.

## Render Output

```html
<div class="eael-single-product-images eael-pi-thumb-{bottom|left|right}"
     id="slider-container-<widget-id>"
     data-id="<widget-id>">

  <div class="product_image_slider">

    <!-- Main image slider -->
    <div class="product_image_slider__container" data-pi_image='<json-config>'>
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <!-- Per gallery image (or 6 placeholder copies in editor): -->
          <div class="swiper-slide">
            <div class="image_slider__image">
              [editor] <img src="eael-default-placeholder.png" alt="" />
              [frontend] {wp_get_attachment_image($image_id, $resolution)}
            </div>
          </div>
          …
        </div>
        [?] <span class="swiper-button-prev"></span>
        [?] <span class="swiper-button-next"></span>
        [?] <div class="swiper-pagination"></div>
      </div>

      [?] <!-- Trigger for Magnific Popup gallery: -->
      <div class="product_image_slider__trigger"><a href="#">…</a></div>
    </div>

    <!-- Thumb slider (when eael_pi_thumbnail == yes) -->
    [?] <div class="product_image_slider__thumbs"
            data-pi_thumb='<json-config>'
            data-for_mobile="<mobile-height>">
      <div class="swiper-container">
        <div class="swiper-wrapper {single-thumb-img when only 1 image}">
          <!-- Per gallery image: -->
          <div class="swiper-slide">
            <div class="product_image_slider__thumbs__image">
              <img src="…" />
            </div>
          </div>
          …
        </div>
        [?] <span class="swiper-button-prev {left-right-prev when thumb_position in [left,right]}"></span>
        [?] <span class="swiper-button-next {left-right-prev}"></span>
      </div>
    </div>
  </div>
</div>

<!-- Dynamically injected by zoom-lense.min.js when zoom mode active: -->
<div class="eael-lens-zoom"></div>
<div class="eael-result-zoom"></div>
```

Notes:

- `id="slider-container-<widget-id>"` is widget-id-scoped — multi-instance safe at the wrapper level (unlike Product_Grid).
- `data-pi_thumb` carries a JSON object with all Swiper options + EA-specific flags (`thumbnail`, `desktop`, `tablet`, `mobile`, `image_loop`, etc.).
- `data-pi_image` carries the main slider's Swiper config including `zoomEffect` object.
- `data-for_mobile` carries `height_for_mobile` slider value for window-load height calc.
- `.eael-lens-zoom` + `.eael-result-zoom` are dynamically injected by the zoom-lense plugin; cleaned up on image-swap via `$('.eael-lens-zoom, .eael-result-zoom').remove()`.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Images.php#L60) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_pi_image_resolution` | SELECT | `full` | Content → Content | Image resolution for `wp_get_attachment_image()` |
| `eael_pi_effects` | SELECT | `slide` | Content → Content | Swiper `effect`: slide / cards / fade / cube / flip / coverflow |
| `eael_pi_pagination` | SWITCHER | empty | Content → Content | Show pagination dots |
| `eael_pi_navigation` | SWITCHER | empty | Content → Content | Show nav arrows |
| `eael_pi_thumbnail` | SWITCHER | `yes` | Content → Content | Render thumbnail Swiper |
| `thumb_image_resolution` | GROUP | `thumbnail` | Content → Content | `Group_Control_Image_Size` for thumbnails |
| `eael_pi_select_thumb_items` | POPOVER_TOGGLE | empty | Content → Content | Enable per-device thumb item count overrides |
| `eael_pi_thumb_{desktop,tablet,mobile}_items` | SLIDER × 3 | `4` / `3` / `2` | Content → Content (popover) | Items per view per device |
| `eael_pi_thumb_height_for_mobile` | SLIDER | `40` (px) | Content → Content | Mobile-screen thumb height multiplier |
| `eael_pi_thumb_position` | SELECT | `bottom` | Content → Content | `bottom` / `left` / `right` — adds `eael-pi-thumb-{position}` modifier class; left/right enables `direction: vertical` swiper config |
| `eael_pi_thumb_navigation` | SWITCHER | empty | Content → Content | Show nav arrows on thumb slider (only when items > desktop count) |
| `eael_product_image_loop` | SWITCHER | empty | Content → Content | Swiper `loop` |
| `eael_product_image_autoplay` | SWITCHER | empty | Content → Content | Swiper autoplay enable |
| `eael_product_image_autoplay_delay` | SLIDER | — | Content → Content | Autoplay delay ms |
| `eael_pi_mouse_wheel` | SWITCHER | empty | Content → Content | Swiper `mousewheel` enable |
| `eael_pi_grab_cursor` | SWITCHER | empty | Content → Content | Swiper `grabCursor` |
| `eael_pi_keyboard_press` | SWITCHER | empty | Content → Content | Swiper `keyboard.enabled` |
| `eael_image_sale_flash` | SWITCHER | `yes` | Style → Images | Show / style `.onsale` span (rendered by WC core or theme, not this widget) |
| `eael_image_sale_flash_text_color` / `_bg_color` / typography | COLOR × 2 + GROUP | — | Style → Images | Onsale span styling |
| `eael_image_zoom_show` | SWITCHER | — | Style → Zoom | Show zoom controls |
| `eael_image_zoom_effect` | SWITCHER | `yes` | Style → Zoom | Enable zoom |
| `eael_zoom_effect_type` | SELECT | `lense` | Style → Zoom | `lense` / `magnify` (third option `inside` panel-commented; JS stub empty) |
| `eael_zoom_lens_size` | SLIDER (responsive) | — | Style → Zoom | Lens dimensions for both lense + magnify |
| `eael_zoom_lens_border_radius` | SLIDER | — | Style → Zoom | Lens border radius |
| `eael_zoom_lens_border` | various | — | Style → Zoom | Lens border styling |
| Other Style sections | various | — | Style tab | Pagination, navigation, swiper container, thumb borders |

## Conditional Dependencies

```text
# Thumb settings
thumb_image_resolution                  → visible when eael_pi_thumbnail == 'yes'
eael_pi_select_thumb_items              → visible when eael_pi_thumbnail == 'yes'
eael_pi_thumb_{desktop,tablet,mobile}_items
                                        → visible when eael_pi_thumbnail == 'yes'
                                          AND eael_pi_select_thumb_items == 'yes'
eael_pi_thumb_height_for_mobile         → visible when eael_pi_thumbnail == 'yes'
                                          AND eael_pi_select_thumb_items == 'yes'
eael_pi_thumb_position                  → visible when eael_pi_thumbnail == 'yes'
eael_pi_thumb_navigation                → visible when eael_pi_thumbnail == 'yes'

# Zoom
eael_zoom_effect_type                   → visible when eael_image_zoom_effect == 'yes'
eael_zoom_lens_size                     → visible when eael_zoom_effect_type in [lense, magnify]
eael_image_sale_flash_{text,bg}_color   → visible when eael_image_sale_flash == 'yes'

# Frontend gate
Entire output                           → empty when WooCommerce inactive
Empty render                            → silent return when Helper::get_product() returns false (no $product context)
Editor preview                          → 6 placeholder images when editor or templately_library
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_product_image_product_id` | filter (emitted) | `(int\|false $product_id, Widget_Base $this)` | Override product ID resolution; returns `false` by default → `Helper::get_product()` falls back to global `$product` ([line 1140](../../includes/Elements/Woo_Product_Images.php#L1140)) |
| `Helper::get_product()` (consumed via Classes\Helper) | — | `(int\|bool $id)` | Returns `wc_get_product($id)`; falls back to global `$product` when arg is false |
| WC-native: `show_variation` | event (consumed in JS) | `(jQuery event, variation_data)` | Updates main + thumb image attributes; stops autoplay; re-init zoom lens after 100ms |
| WC-native: `hide_variation` / `reset_image` | event (consumed in JS) | — | Restores original image attributes with fadeOut/fadeIn |

⚠️ Only ONE PHP filter, ZERO actions emitted. Extension surface is intentionally minimal. No shared patterns from [`_patterns.md`](_patterns.md) apply.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-woo-product-images.default', WooProdectImage)` ([line 336-340 of woo-product-image.js](../../src/js/view/woo-product-image.js#L336)) — note function name typo `WooProdectImage` (Product → Prodect).
- **Boot pattern:** Legacy `jQuery(window).on("elementor/frontend/init", …)` — NOT the newer `eael.hooks.addAction("init","ea",…)` style.
- **Guard:** None — no `elementStatusCheck()` flag; relies on `$.fn.eaelZoomLense`, `$.fn.eaelMagnify`, `$.magnificPopup`, `Swiper` being defined.
- **Vendor deps:** Swiper (via `elementorFrontend.utils.swiper` async loader), Magnific Popup, custom `eaelZoomLense` / `eaelMagnify` jQuery plugins
- **Init order:**
  1. Capture original image attribute snapshot via `getImageAttributes()`
  2. Bind variation event handlers on `.variations_form`
  3. Window-load handler for mobile-height calc (NOT scoped to widget — global)
  4. `swiperLoader` for thumb container → resolves promise → builds main config with `thumbs.swiper` ref → `swiperLoader` for main container
  5. Main Swiper `slideChange` event handler → re-init zoom lens on active slide after 100ms
  6. Magnific Popup click binding on `.product_image_slider__trigger a`
  7. Zoom lens setup (if enabled): lens / magnify / inside (stub)
- **Variation image swap flow:**
  - `handleShowVariation(event, variation)` → `updateProductImage(variation.image)` → `setImageAttributes` + `setThumbImageAttributes` → 100ms `setTimeout` → `initializeZoomLens($productGalleryImage)`
  - Stops both sliders' autoplay + slides to 0 via `toggleSliderAutoplay('stop')` + `slideTo(0)`
- **Reset variation flow:**
  - `handleResetVariation()` → `resetProductImages()` (per-image `fadeOut(100) → attr swap → fadeIn(100)`); reset image triggers zoom lens re-init in fadeIn callback after 50ms
- **Zoom lens setup (`zoomLenseEffect`):** Multiple init fallbacks — immediate call → `$(window).on('load')` → `imagesLoaded(callback)` if available. Cleans `.eael-lens-zoom, .eael-result-zoom` before each init. `$img.eaelZoomLense({lensWidth, lensHeight, borderRadius, lensBorder, autoResize: true})`.
- **Magnify setup (`magnifyEffect`):** Single call `$img.eaelMagnify({lensSize: 200, lensBorder})` — no fallback chain.
- **`window.isEditMode` removes `.eael-magnify-lens`** ([line 321-323](../../src/js/view/woo-product-image.js#L321)) — strips stray magnify lens from editor context.
- **`swiperLoader` helper:** Uses `elementorFrontend.utils.swiper` async loader if `Swiper` not defined; else direct `new Swiper(...)` wrapped in Promise.

## Common Issues

### Variation product image doesn't swap when selecting variation

- **Likely cause:** WC's `show_variation` event not firing — typically because `.variations_form` isn't present (non-variable product) OR variation_id resolution fails server-side
- **Diagnose:** browser DevTools → Network → check if WC's variation AJAX completes; check `.variations_form` exists in DOM
- **Fix:** ensure product is variable type; check WC variations are published; clear page cache

### Zoom lens doesn't appear or stays after image change

- **Likely cause:** zoom-lense.js cleanup runs via `$('.eael-lens-zoom, .eael-result-zoom').remove()` before each init — if image swap happens faster than init, dangling lens may persist
- **Diagnose:** inspect DOM — count `.eael-lens-zoom` / `.eael-result-zoom` elements; should be 1 each per active image
- **Fix:** known race; force-reload page or hover off the image to trigger cleanup

### Multiple Woo_Product_Images widgets on same page show first widget's images

- **Likely cause:** `$(".eael-single-product-images")` at [JS line 3](../../src/js/view/woo-product-image.js#L3) is NOT scoped to `$scope` — global jQuery selector returns first match
- **Diagnose:** multi-widget page; observe whether variation events on widget 2 swap widget 1's images
- **Fix:** known multi-instance bug; only one Woo_Product_Images per page works reliably

### Empty render on a page that should show the product

- **Likely cause:** `Helper::get_product()` returns false (no global `$product` context); render exits silently at [line 1160-1162](../../includes/Elements/Woo_Product_Images.php#L1160) with no message
- **Diagnose:** widget appears blank; editor shows 6 placeholder images correctly
- **Fix:** use the widget on a Single Product theme builder template OR pass a product context via theme builder Loop Grid; OR use the `eael_product_image_product_id` filter to force a product ID

### Swiper effect (e.g. "cube" or "flip") doesn't render

- **Likely cause:** Elementor's `e-swiper` handle may not include all Swiper Effect modules (Cube, Flip, etc. are optional in Swiper 8+). Fallback to slide effect or no animation.
- **Diagnose:** browser console for Swiper module errors
- **Fix:** check Swiper version Elementor ships; may need a Swiper-modules polyfill for advanced effects

### Mobile thumb height is wrong

- **Likely cause:** Window-load handler at [JS line 163](../../src/js/view/woo-product-image.js#L163) only fires on `$(window).on('load')` — does NOT re-fire on Elementor preview re-render or responsive-mode switching
- **Diagnose:** switch responsive mode in Elementor editor; observe thumb height stays at desktop value until full page reload
- **Fix:** known limitation; refresh browser after responsive-mode changes in editor

### Bundled Swiper CSS shipped twice

- **Likely cause:** `get_style_depends() = ['e-swiper']` enqueues Elementor's Swiper CSS; `config.php` ALSO bundles `swiper-bundle.min.css` as a `lib` dependency. Both load.
- **Diagnose:** Network panel → search for "swiper" — two CSS files loaded
- **Fix:** known asset bloat; remove `swiper-bundle.min.css` from config.php (technical debt)

## Known Limitations

- **Bundled Swiper CSS DUPLICATE** — `get_style_depends() = ['e-swiper']` declared but config.php also ships `swiper-bundle.min.css` (~80KB) per page.
- **`filterable-gallery.min.css` cross-widget dependency** — loads even when Filter_Gallery widget isn't on the page. Wasted ~50KB.
- **Function name typo `WooProdectImage`** ([JS line 1](../../src/js/view/woo-product-image.js#L1)) — preserved in handler registration; renaming would require coordinated edit.
- **Filename typo: `woo-product-image.js`** (singular) for widget slug `woo-product-images` (plural). Asset filename mismatch with widget name. Build outputs match the source name.
- **Original image attribute snapshot frozen at init** ([JS line 18-19](../../src/js/view/woo-product-image.js#L18)) — `getImageAttributes()` captures once on widget init; if product context changes via AJAX, snapshot is stale.
- **`$(".eael-single-product-images")` global selector** ([JS line 3](../../src/js/view/woo-product-image.js#L3)) — not scoped to `$scope`. Multi-instance bug.
- **`zoomInsideEffect()` JS stub empty** ([JS line 316](../../src/js/view/woo-product-image.js#L316)) — corresponding `inside` panel option commented out at [PHP line 841](../../includes/Elements/Woo_Product_Images.php#L841). Dead code path.
- **HARDCODED 5-breakpoint Swiper config** ([PHP line 1029-1077](../../includes/Elements/Woo_Product_Images.php#L1029)) — `[320, 768, 1024, 1440, 1920]`. Does NOT honor Elementor's custom breakpoints.
- **Window-load mobile-height calc not Elementor-scoped** ([JS line 163](../../src/js/view/woo-product-image.js#L163)) — `$(window).on('load')` runs once. Elementor preview re-renders / responsive-mode switches don't re-fire.
- **Render exits silently on no `$product`** ([PHP line 1160-1162](../../includes/Elements/Woo_Product_Images.php#L1160)) — no editor warning. Widget appears blank on non-product pages with no feedback.
- **Variation autoplay control reads `$sliderThumbs.autoplay`** ([JS line 95](../../src/js/view/woo-product-image.js#L95)) — checks `undefined`; if thumb config has no autoplay key, resume doesn't trigger. Subtle bug.
- **`get_style_depends() = ['e-swiper']` does not include `get_script_depends()`** — JS relies on `elementorFrontend.utils.swiper` async loader and falls back to `new Swiper(...)` if Swiper is in global scope. Brittle if Elementor changes Swiper's exposure mechanism.
- **Magnific Popup `items` array rebuilt per click** ([JS line 233-252](../../src/js/view/woo-product-image.js#L233)) — no caching; iterates `.swiper-slide img` every click.
- **`product_image_slider__trigger a` selector** for opening lightbox — relies on a `<a>` with this class existing in template (not always emitted; check render branch).
- **No `wpml_object_id` filter** — featured image and gallery image IDs aren't translated. Cross-language sites may see English images on translated product pages. Compare to [`_patterns.md § WPML`](_patterns.md#wpml-media-translation).
- **`is_dynamic_content()` not overridden** — Elementor's render cache may store product image HTML; product image changes won't reflect until cache expires.
- **`zoom-lense.min.js` is a custom Lite vendor** — no community maintenance; bug reports must go to EA team.
- **No keyboard navigation hint** — `eael_pi_keyboard_press` switcher toggles Swiper's `keyboard.enabled`, but no visible focus indicator on slides for screen-reader users.
- **Sale Flash styling controls but no render output** — `eael_image_sale_flash` switcher gates color controls, but the widget itself doesn't emit `.onsale` span. Relies on WC/theme to render it; styles apply only when theme renders the span inside `.eael-single-product-images`.
- **6 transition effects may not all work** — `cards`, `cube`, `flip` require Swiper Effect modules; if Elementor's `e-swiper` handle doesn't include them, those effects silently fall back to slide.
