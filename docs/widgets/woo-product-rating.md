# Woo Product Rating Widget

> Renders WC product star rating + review count count + caption. Three star presets (`style_1` solid / `style_2` classic outline / `style_3` half-stroke) emit inline SVG paths from Font Awesome's free icon set (no FA dependency). **Pure CSS widget — no JS**, 63-line SCSS. Hardcoded editor preview (3 stars + "1 Customer Rating"). Zero hooks emitted. Lite-only.

**Class file:** [`includes/Elements/Woo_Product_Rating.php`](../../includes/Elements/Woo_Product_Rating.php)
**Slug:** `woo-product-rating` (widget id `eael-woo-product-rating`) — slug + widget id consistent.
**Public docs:** <https://essential-addons.com/elementor/docs/ea-woo-product-rating>
**Pro-shared:** ❌ No — Lite-only. Zero `do_action` / `apply_filters` emitted. No `eael/pro_enabled` checks. No `eael_section_pro` upsell.

---

## Overview

528-line widget that renders WC's product rating star UI on any page with `$product` context. Replaces WC's CSS-pseudo `.star-rating` approach with **inline SVG stars** (5 stars per render, fill state per star via `ceil($average)` comparison). Three preset star shapes — each shape has its own PHP render method (`eael_star_solid`, `eael_star_classic`, `eael_star_half_stroke`) emitting a hardcoded SVG path from Font Awesome free icons (viewBox `0 0 576 512`). Style controls target BOTH EA's `.eael-product-rating.filled/unfilled svg path` AND WC's native `.star-rating` selectors for theme compatibility.

Frontend reads `$product->get_average_rating()` + `get_rating_count()` + `get_review_count()`. Editor preview hardcodes `$average = 3` + `$rating_count = 1`. Gated by `wc_review_ratings_enabled()` (WC site setting) — silent return when reviews are globally disabled. Plural suffix `s` appended when `rating_count > 1` (English-only pluralization).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| 3 star presets (solid / classic / half-stroke) | ✅ | ✅ |
| Show Review Count toggle + caption (`( `, `Customer Rating`, ` )`) | ✅ | ✅ |
| Show Empty Review toggle + caption (`No Customer Rating`) | ✅ | ✅ |
| Star color (rating + given + empty + text) | ✅ | ✅ |
| Star size, gap, margin, alignment | ✅ | ✅ |
| Inline SVG icons (no FA dependency) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro-specific features | — | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Woo_Product_Rating.php`](../../includes/Elements/Woo_Product_Rating.php) | PHP widget class (528 lines) — controls, render, `eael_star_solid()`, `eael_star_classic()`, `eael_star_half_stroke()` SVG emitter methods, `eael_rating_style()` dispatch |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L1998) | `Helper::get_product()` — falls back to global `$product` |
| [`src/css/view/woo-product-rating.scss`](../../src/css/view/woo-product-rating.scss) | Source styles (63 lines) — flex wrap, alignment prefix classes, star fill states |
| [`config.php`](../../config.php#L679) entry `'woo-product-rating'` | Asset declaration — **single CSS only, NO JS** |
| `assets/front-end/css/view/woo-product-rating.min.css` | Built output |

No `get_style_depends()` declared. No FA dependency (inline SVG paths bake the icons into PHP source).

## Architecture

- **Render delegates to `eael_rating_style()` dispatch** ([line 426-439](../../includes/Elements/Woo_Product_Rating.php#L426)) — `$style_methods` array maps `style_1→eael_star_solid`, `style_2→eael_star_classic`, `style_3→eael_star_half_stroke`; 5-star loop computes per-star fill via `$i <= ceil($average)` and calls the dispatched method. ⚠️ `ceil()` means `4.1` rating → 5 filled stars (no half-star support; widget can't render `4.5` visually).
- **3 inline SVG path methods** ([line 396-424](../../includes/Elements/Woo_Product_Rating.php#L396)) — each method outputs `<span class="eael-product-rating {filled|unfilled}"><svg viewBox="0 0 576 512"><path d="…"/></svg></span>`. SVG path data is **hardcoded from Font Awesome free**: solid star (`fa-star`), classic outlined (`fa-star-regular`), half-stroke (`fa-star-half-stroke`). No FA library required at runtime — paths baked into PHP source.
- **Editor preview mockup** ([line 449-471](../../includes/Elements/Woo_Product_Rating.php#L449)) — when `Plugin::$instance->editor->is_edit_mode()` OR `get_post_type() === 'templately_library'`, hardcodes `$average = 3, $rating_count = 1` and emits `<span class="count">1</span>` (translatable via `esc_html_e('1', '…')` — same i18n-pollution pattern as Woo_Product_Price).
- **Frontend gates:**
  - `function_exists('WC')` — silent return at [line 442-444](../../includes/Elements/Woo_Product_Rating.php#L442)
  - `Helper::get_product()` returns false → silent return at [line 474-476](../../includes/Elements/Woo_Product_Rating.php#L474)
  - `wc_review_ratings_enabled()` returns false → silent return at [line 477-479](../../includes/Elements/Woo_Product_Rating.php#L477) — WC site-wide setting kills the widget
  - `$rating_count === 0` AND `show_empty_review !== 'yes'` → outer wrapper not rendered (entire `<div class="eael-single-product-rating">` skipped) — only when explicitly toggled on does empty state appear
  - `comments_open()` is checked separately for the review-link `<a>` — review link hidden on comments-disabled posts even when review count exists
- **`$rating_count > 1` plural suffix** ([line 489](../../includes/Elements/Woo_Product_Rating.php#L489)) — `$caption_suffix = ( $rating_count > 1 ) ? 's' : ''` then `$review_caption .= $caption_suffix`. **English-only pluralization**; non-English locales get incorrect grammar. Translator must edit each rating-caption string to embed plural logic.
- **`eael_rating_color` vs `eael_rating_count_color` selector conflict** ([line 99-120](../../includes/Elements/Woo_Product_Rating.php#L99)) — BOTH controls target `.star-rating` color (WC's native CSS-pseudo star). `eael_rating_count_color` (labeled "Rating Given Color") loads later in CSS output, so its value wins for WC's native star UI. ⚠️ Inconsistent naming — UI calls one "Rating Color" and other "Rating Given Color" but both write same selector.
- **`eael_empty_star_color` overrides ALL stars** ([line 122-135](../../includes/Elements/Woo_Product_Rating.php#L122)) — selector `.eael-product-rating svg path` (without `.filled`/`.unfilled` class) overrides both filled + unfilled stars when emitted. Only conditional-shown when `show_empty_review == 'yes'`; once set, paints every star regardless of state. Source-order dependent.
- **`render()` checks `$product` global directly THEN reassigns** ([line 446 + 481](../../includes/Elements/Woo_Product_Rating.php#L446)) — `global $product;` at line 446, but `$product = Helper::get_product();` only at line 481 (after `wc_review_ratings_enabled()` check). The early `if (!$product)` check at [line 474](../../includes/Elements/Woo_Product_Rating.php#L474) tests the **global** value, not the helper-resolved value. Subtle: helper might find a product where the global is null. Edge case may break for theme builder contexts.
- **Zero extension hooks** — no `do_action`, no `apply_filters`, no `eael/pro_enabled`. Smallest hook footprint along with Woo_Product_Price (also zero).

## Render Output

### Frontend

```html
<!-- Silent return when:
     - WC inactive
     - No $product
     - wc_review_ratings_enabled() === false (WC site setting)
     - rating_count === 0 AND show_empty_review !== 'yes' -->

<div class="eael-single-product-rating eael-product-rating--align-{left|center|right}">
  <div class="woocommerce-product-rating">

    <div class="eael-product-rating-wrap">
      <!-- 5-star loop, fill per ceil($average): -->
      <span class="eael-product-rating filled|unfilled">
        <svg viewBox="0 0 576 512"><path d="…hardcoded FA path…"/></svg>
      </span>
      … (× 5)
    </div>

    [?] <a href="#reviews" class="woocommerce-review-link" rel="nofollow">  <!-- when comments_open() && show_review_count -->
      <span class="before-rating">{before_rating_caption — default "( "}</span>
      <span class="count">
        [?] <span class="count_number">{$review_count}</span>  <!-- when review_count > 0 -->
        <span class="count_text">{rating_caption}{s if count>1}</span>
      </span>
      <span class="after-rating">{after_rating_caption — default " )"}</span>
    </a>

    [?] <a href="#reviews" class="woocommerce-review-link" rel="nofollow">  <!-- when rating_count==0 && show_empty_review -->
      {empty_rating_caption — default "No Customer Rating"}
    </a>
  </div>
</div>
```

### Editor preview mockup

```html
<div class="eael-single-product-rating">
  <div class="woocommerce-product-rating">
    <div class="eael-product-rating-wrap">
      <!-- Hardcoded average=3 → 3 filled + 2 unfilled stars -->
      <span class="eael-product-rating filled">{SVG}</span>
      <span class="eael-product-rating filled">{SVG}</span>
      <span class="eael-product-rating filled">{SVG}</span>
      <span class="eael-product-rating unfilled">{SVG}</span>
      <span class="eael-product-rating unfilled">{SVG}</span>
    </div>
    [?] <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
      <span class="before-rating">{before_rating_caption}</span>
      <span class="count">1</span>                          <!-- hardcoded English digit, i18n-wrapped -->
      {rating_caption — default "Customer Rating"}
      <span class="after-rating">{after_rating_caption}</span>
    </a>
  </div>
</div>
```

Notes:

- Style controls write to BOTH `.eael-product-rating svg path` (EA's SVG) AND `.star-rating` (WC's CSS-pseudo). When the widget renders, EA's SVG path is what's visible; the `.star-rating` selectors apply only when third-party / WC contexts re-emit native star markup.
- `eael-product-rating--align-{left|center|right}` is a `prefix_class` on `{{WRAPPER}}` — alignment applied via CSS variable / wrapper class, not flexbox justify-content on inner element.
- Both review-link branches (review-count > 0 vs empty-review) use the same `<a href="#reviews">` permalink — assumes a `#reviews` anchor exists on the same page.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Woo_Product_Rating.php#L57) is the truth.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `show_review_count` | SWITCHER | `yes` | Content → Content | Toggle review-link `<a>` rendering |
| `rating_style` | SELECT | `style_1` | Content → Content | `style_1` solid / `style_2` classic / `style_3` half-stroke |
| `rating_caption` | TEXT (AI) | `Customer Rating` | Content → Content | Caption text after count number |
| `before_rating_caption` | TEXT (AI) | `( ` | Content → Content | Text before count number |
| `after_rating_caption` | TEXT (AI) | ` )` | Content → Content | Text after caption |
| `show_empty_review` | SWITCHER | empty | Content → Content | Show "No Customer Rating" state when `rating_count == 0` |
| `empty_rating_caption` | TEXT (AI) | `No Customer Rating` | Content → Content | Empty-state caption |
| `eael_star_align` | CHOOSE (responsive) | — | Style → Rating | `prefix_class` `eael-product-rating--align-{left|center|right}` |
| `eael_rating_color` | COLOR | — | Style → Rating | Unfilled SVG fill + WC `.star-rating` base color (named "Rating Color") |
| `eael_rating_count_color` | COLOR | — | Style → Rating | Filled SVG fill + WC `.star-rating` color (named "Rating Given Color") — **overlaps with `eael_rating_color`** |
| `eael_empty_star_color` | COLOR | — | Style → Rating | Empty-state star color — selector `.eael-product-rating svg path` overrides ALL stars; gated by `show_empty_review == 'yes'` |
| `eael_star_size` | SLIDER (px/rem/%) | — | Style → Rating | `font-size` on `.star-rating`; `height`/`width` on SVG |
| `eael_star_gap` | SLIDER | — | Style → Rating | Flex `gap` on `.eael-product-rating-wrap`; `letter-spacing` on `.star-rating` |
| `product_rating_margin` | DIMENSIONS (responsive) | — | Style → Rating | Margin on rating wrapper |
| `eael_star_text_color` | COLOR | — | Style → Rating | Review-link `<a>` text color |
| `eael_star_text_typography` | GROUP | — | Style → Rating | Review-link typography |
| `eael_star_text_spaceing` | SLIDER | — | Style → Rating | `margin-right` on rating wrapper — separates stars from review text (typo `spaceing` → `spacing`) |

## Conditional Dependencies

```text
# Review count visibility
rating_caption                          → visible when show_review_count == 'yes'
before_rating_caption                   → visible when show_review_count == 'yes'
after_rating_caption                    → visible when show_review_count == 'yes'

# Empty state
empty_rating_caption                    → visible when show_empty_review == 'yes'
eael_empty_star_color                   → visible when show_empty_review == 'yes'

# Frontend gates (runtime)
Entire output                           → empty when WooCommerce inactive
                                          OR Helper::get_product() returns false
                                          OR wc_review_ratings_enabled() === false (WC site setting)
                                          OR ($rating_count === 0 AND show_empty_review !== 'yes')

Review-link <a> (with-count branch)    → rendered when comments_open() AND show_review_count == 'yes'
Review-link <a> (empty branch)         → rendered when $rating_count === 0 AND show_empty_review == 'yes'

Editor preview                          → hardcoded $average=3 + $rating_count=1 in editor/templately_library
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

⚠️ **ZERO `do_action` / `apply_filters` emitted** — minimum extension surface along with Woo_Product_Price.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `Helper::get_product()` (consumed) | — | `(int\|bool $id)` | Falls back to global `$product` |
| WC-native: `wc_review_ratings_enabled()` (consumed) | — | `(): bool` | WC site-wide review setting; renders nothing if false |
| WC-native: `comments_open()` (consumed) | — | `(int $post_id = null): bool` | Hides review link `<a>` when comments are closed for the post |
| WC-native: `$product->get_average_rating()` / `get_rating_count()` / `get_review_count()` (consumed) | — | — | Source data for star fill + count display |

No `eael/pro_enabled`, no `eael_section_pro`. No shared patterns from [`_patterns.md`](_patterns.md) apply.

## JavaScript Lifecycle

**N/A — pure CSS widget**, no JavaScript file declared in [`config.php`](../../config.php#L679). Static server-render only.

## Common Issues

### Widget shows nothing on a product page that has reviews

- **Likely cause:** `wc_review_ratings_enabled()` returns false — WP admin → WooCommerce → Settings → Products → Enable Reviews + Enable Star Ratings must both be on
- **Diagnose:** check WC settings
- **Fix:** enable review ratings site-wide

### Star count shows 5 filled when actual rating is 4.1

- **Likely cause:** `ceil($average)` at [line 435](../../includes/Elements/Woo_Product_Rating.php#L435) rounds 4.1 up to 5
- **Diagnose:** `ceil(4.1)` returns 5; loop fills all 5 stars
- **Fix:** known limitation; widget can't render half-stars. To get half-star precision, use WC's native `wc_get_rating_html()` directly (theme template) instead of this widget

### "Rating Color" + "Rating Given Color" both affect the same star color

- **Likely cause:** Both controls target WC's `.star-rating` selector ([line 104 + 116](../../includes/Elements/Woo_Product_Rating.php#L104)). Later-loaded value wins.
- **Diagnose:** inspect computed style for `.star-rating` color; verify which control value applies
- **Fix:** for EA's SVG stars, the controls target `.filled` / `.unfilled` separately and work as expected. For WC's `.star-rating` (third-party theme contexts), the controls conflict — only one wins.

### Empty Star Color paints filled stars too

- **Likely cause:** `eael_empty_star_color` selector `.eael-product-rating svg path` (no `.filled`/`.unfilled` qualifier) overrides BOTH states
- **Diagnose:** inspect SVG path fill — both filled and unfilled have the empty color
- **Fix:** if you only want to color unfilled stars, use `eael_rating_color` instead. `eael_empty_star_color` is a blanket override.

### Plural "Ratings" not localized

- **Likely cause:** Hardcoded `'s'` suffix logic at [line 489](../../includes/Elements/Woo_Product_Rating.php#L489) — English-only
- **Diagnose:** non-English locales see incorrect grammar (e.g. "2 Customer Ratings" becomes "2 Customer Klantbeoordelings" — wrong)
- **Fix:** translator must edit each caption string to embed plural-aware text; or override the widget render

### Review count number shows `1` in editor regardless of product

- **Likely cause:** Editor preview hardcodes `<span class="count">1</span>` at [line 462](../../includes/Elements/Woo_Product_Rating.php#L462) — not real data
- **Diagnose:** intentional — editor shows mockup for styling preview
- **Fix:** verify via frontend preview

### Widget appears blank on theme builder Single Product page

- **Likely cause:** `$product` global might not be set when theme builder is in special preview context; `Helper::get_product()` returns false → silent return
- **Diagnose:** widget appears blank on frontend; editor shows mockup
- **Fix:** verify theme builder template includes a Loop Grid or proper product context; or use a real product URL with this template

## Known Limitations

- **`ceil($average)` for star fill — no half-star support** ([line 435](../../includes/Elements/Woo_Product_Rating.php#L435)) — `4.1` average renders as 5 filled stars. Inaccurate visual representation. Half-stroke preset (style_3) only affects the SVG shape, not fill precision.
- **`eael_rating_color` + `eael_rating_count_color` selector conflict on WC's `.star-rating`** — both write the same color selector; order-dependent.
- **`eael_empty_star_color` overrides ALL stars** — blanket selector, not just unfilled.
- **English-only plural "s" suffix** ([line 489](../../includes/Elements/Woo_Product_Rating.php#L489)) — non-English locales see wrong grammar.
- **Editor mockup hardcodes English digit `1`** ([line 462](../../includes/Elements/Woo_Product_Rating.php#L462)) — `esc_html_e('1', '…')` pollutes i18n catalog; doesn't auto-localize to non-Latin numerals.
- **Global `$product` early-check uses wrong source** — [line 474](../../includes/Elements/Woo_Product_Rating.php#L474) tests `if (!$product)` before `Helper::get_product()` reassignment at line 481. Subtle ordering bug; helper might succeed where global fails.
- **Zero extension hooks** — no `do_action` / `apply_filters` emitted. No way for Pro / 3rd party to override star shapes or caption logic beyond CSS.
- **`wc_review_ratings_enabled()` gate is global** — single switch in WC settings kills this widget across the entire site.
- **Review-link `<a href="#reviews">` anchor assumed** — if no `#reviews` section exists on the page (e.g. when widget is on archive without single-product reviews block), link is dead.
- **SVG paths are FA Free path data inline** — Font Awesome attribution requirement may apply per FA license. Check FA Free license terms.
- **Control id typo `eael_star_text_spaceing`** — `spaceing` should be `spacing`. Renaming breaks saved widget data; left as-is.
- **No FA4 → FA5 shim** (no FA dependency at all — uses inline SVGs).
- **No `wpml_object_id` filter** — product ID not translated.
- **No `is_dynamic_content()` override** — Elementor render cache may freeze the rating count; new reviews won't reflect until cache expires.
- **No half-star precision via CSS overlay** — modern approach is dual-layer star with CSS clip-path or `linear-gradient`; this widget uses discrete fill state per SVG instance.
- **No keyboard / a11y considerations** — stars lack `role="img"` + `aria-label="rated N out of 5"`. Screen-reader users can't determine the rating.
