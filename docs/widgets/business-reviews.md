# Business Reviews Widget

> Google Places API-driven reviews widget with Slider (Swiper) or Grid layouts and three presets. Supports legacy **Places API** (`/maps/api/place/details/json`) and modern **Places API (New)** (`/v1/places/{id}`); new API response gets mapped to legacy structure so render code stays uniform. LocalBusiness JSON-LD schema emission, 1–4-star rating filters, source-extensible via `eael/business_reviews/sources` filter.

**Class file:** [`includes/Elements/Business_Reviews.php`](../../includes/Elements/Business_Reviews.php)
**Slug:** `business-reviews` (widget id `eael-business-reviews`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-business-reviews/>
**Pro-shared:** ❌ No — Lite-only widget. Has filter extension points (`eael/business_reviews/sources`, `…/google_api_options`, `…/fetch_api`, `…/render`, `…/settings`, `…/controls`) so Pro or third parties can add new review sources (Yelp, Facebook, Trustpilot) without forking the class. No `eael_section_pro` upsell registered.

---

## Overview

Single-source-with-extension-hooks pattern: only `google-reviews` ships in Lite, but every dispatch point (sources SELECT, settings array, fetch dispatcher, render dispatcher) goes through `apply_filters(...)` so a Pro plugin or custom code can register a new source by adding to the sources array, hooking `eael/business_reviews/fetch_api` to return data, and `eael/business_reviews/render` to short-circuit the default render. The widget fetches reviews server-side at render-time via `wp_remote_get` (240s timeout), caches by composite key (`source + place_id + expiration + md5(sort + translation + widget_id + api_key)`), maps new API to legacy structure, then dispatches to Slider (Swiper-driven, three presets) or Grid render path. DOMPurify is loaded for sanitising **Swiper selector attributes** in JS (not user content) — defensive against XSS via stored API responses.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Google Reviews source (Places + Places New) | ✅ | ✅ |
| Slider (3 presets) + Grid layouts | ✅ | ✅ |
| Star-rating filters (hide 1/2/3/4-star) | ✅ | ✅ |
| LocalBusiness JSON-LD schema emission | ✅ — Places (legacy) only; Places (New) doesn't return `address_components` so schema empties out | ✅ |
| Per-source extension hooks | ✅ | ✅ |
| Accessibility link-in-same-tab toggle | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none registered | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Business_Reviews.php`](../../includes/Elements/Business_Reviews.php) | PHP widget class (~3,654 lines) — controls, `register_controls()`, settings extractor `get_business_reviews_settings()`, API fetchers (`fetch_business_reviews_from_api`, `fetch_google_reviews_from_api`, error mapper `fetch_google_place_response_error_message`), render dispatchers (`print_business_reviews_google`, `print_google_reviews_slider`, `print_google_reviews_grid`), three preset functions, ratings printer, LocalBusiness schema emitter |
| [`src/css/view/business-reviews.scss`](../../src/css/view/business-reviews.scss) | Source styles (~249 lines) — Swiper container chrome, three slider presets, grid columns, error-message banner, business-header row layouts |
| [`src/js/view/business-reviews.js`](../../src/js/view/business-reviews.js) | Frontend logic (~117 lines) — Swiper bootstrap via `elementorFrontend.utils.swiper` async loader, DOMPurify scrub on Swiper navigation/pagination selectors, autoplay + pause-on-hover wiring; **Grid layout has no JS** (server-render only) |
| [`config.php`](../../config.php#L1294) entry `'business-reviews'` | `Asset_Builder` deps: self CSS + DOMPurify lib JS + self JS |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor — DOMPurify; used to sanitise the **Swiper selectors** (`data-pagination`, `data-arrow-next`, `data-arrow-prev`) before they're passed to Swiper config (defence against any future stored-XSS that could write `'); evil(`-style payloads into those attrs) |
| `get_style_depends() = ['font-awesome-5-all', 'font-awesome-4-shim', 'e-swiper']` | Inherits FA + Elementor-registered Swiper CSS |
| `get_script_depends() = ['font-awesome-4-shim']` | FA4 shim only — Swiper JS loads async via `elementorFrontend.utils.swiper` |
| Site option `eael_br_google_place_api_key` | Stores the Google Places API key in `wp_options`; set via EA Dashboard → Elements → Business Reviews Settings (NOT per-widget) |

## Architecture

- **Single site-wide API key in `wp_options`** — `get_option('eael_br_google_place_api_key')` at [line 2647](../../includes/Elements/Business_Reviews.php#L2647); widget reads it once into `$business_reviews['api_key']` in `get_business_reviews_settings()`. If empty, `register_controls()` emits an inline RAW_HTML warning at [line 134](../../includes/Elements/Business_Reviews.php#L134) linking to the EA settings page. Per-widget API key override is **not** supported — switching keys site-wide breaks every Business Reviews widget at once.
- **Two API paths with response mapping** — Places (legacy) hits `https://maps.googleapis.com/maps/api/place/details/json` with `fields=…&placeid=…&key=…` query args. Places (New) hits `https://places.googleapis.com/v1/places/{place_id}` with `X-Goog-Api-Key` + `X-Goog-FieldMask` headers. After the new-API call, [lines 2818-2842](../../includes/Elements/Business_Reviews.php#L2818) **manually map** the response object back to the legacy structure (`displayName.text` → `name`, `userRatingCount` → `user_ratings_total`, `authorAttribution.{displayName,uri,photoUri}` → review's `{author_name,author_url,profile_photo_url}`, `relativePublishTimeDescription` → `relative_time_description`, `text.text` → `text`). Downstream render code reads only the legacy shape — render is API-version-agnostic.
- **Transient cache keyed by every variable that affects the response** — `eael_{source}_{place_id}_{expiration}_{md5}_brev_cache` where `md5 = md5(sort + translation + widget_id + api_key)` ([line 2664](../../includes/Elements/Business_Reviews.php#L2664)). Widget ID is in the hash so two widgets on the same page with the same place ID but different sort orders cache independently. **TTL is panel-driven** (`eael_business_reviews_data_cache_time` in minutes, default `DAY_IN_SECONDS`) — same uncommon pattern as Event_Calendar.
- **Dispatch chain has four extension surfaces** —
  - `eael/business_reviews/sources` (filter) — Pro adds 'yelp', 'facebook' etc to the source SELECT
  - `eael/business_reviews/google_api_options` (filter) — Pro can extend / restrict the API-type SELECT
  - `eael/business_reviews/controls` (action) — Pro can inject controls into the General section
  - `eael/business_reviews/settings` (filter) — mutate the unified settings array before fetch
  - `eael/business_reviews/fetch_api` (filter) — return non-null to short-circuit Google fetch (Pro's Yelp adapter hooks here)
  - `eael/business_reviews/render` (filter) — return truthy to suppress default Google render and emit your own markup
- **Render is preset-function-per-preset** — Slider has 3 distinct PHP methods (`print_google_reviews_slider_preset_1/2/3`) selected by `eael_business_reviews_style_preset_slider`. Grid (`print_google_reviews_grid`) has its own preset chooser via `eael_business_reviews_style_preset_grid`. Coverflow effect **forces `columns = 3`** at [line 2740](../../includes/Elements/Business_Reviews.php#L2740) regardless of user-set columns.
- **LocalBusiness JSON-LD schema requires Places (legacy)** — `print_localbusiness_schema()` at [line 3551](../../includes/Elements/Business_Reviews.php#L3551) walks `$business_reviews_items_obj->address_components` (set only when `localbusiness_schema` flag adds `'address_components'` to the legacy API `fields` param at [line 2853](../../includes/Elements/Business_Reviews.php#L2853)). The **new API mapping doesn't populate `address_components`** — sites using Places (New) get an `aggregateRating` + `review` schema but no `PostalAddress`. Empty city/region/country still emit (renders `addressLocality: ""` etc).
- **`reviews_no_translations` flag inverted from panel control** — when `review_text_translation = 1`, the request sets `reviews_no_translations = false` (i.e. translations enabled) at [line 2858](../../includes/Elements/Business_Reviews.php#L2858). Reads naturally because the panel control is phrased positively ("Translate review text") while the API parameter is negative-form.
- **Star-rating filter loop iterates after fetch+slice** — `reviews_max_count` truncates the result array first; the 1/2/3/4-star hide filters then `continue` per-iteration. Net effect: if `max=5` and the first 5 reviews are all 1-star and 1-star is hidden, **zero reviews render** even though more 5-star reviews exist past index 5. The widget doesn't refetch with a different sort or extend the slice.
- **Place ID has a hardcoded default** — `ChIJj61dQgK6j4AR4GeTYWZsKWw` ([line 2653](../../includes/Elements/Business_Reviews.php#L2653)) — the Googleplex in Mountain View. A widget dropped onto a page with no `place_id` set still renders someone else's reviews using your API key, burning your quota.
- **Default cache TTL is one day** — `DAY_IN_SECONDS` when `eael_business_reviews_data_cache_time` is empty ([line 2659](../../includes/Elements/Business_Reviews.php#L2659)). Reasonable for Google reviews (low change rate) but means panel changes to filters (sort order, rating filter) won't show new data until cache expires — though sort order is in the cache key so re-saving sort triggers a refetch.
- **`render()` is 3 lines** — entire render delegates to `fetch_business_reviews_from_api()` then `print_business_reviews()` + `print_localbusiness_schema()` ([line 3650](../../includes/Elements/Business_Reviews.php#L3650)). Most logic lives in the dozen helper methods.

## Render Output

```html
<div class="eael-business-reviews-wrapper eael-business-reviews-{widget-id} clearfix"
     data-source="google-reviews"
     data-layout="slider|grid">

  <div id="eael-business-reviews-{widget-id}"
       class="eael-business-reviews-items eael-business-reviews-[slider|grid] [preset-1|preset-2|preset-3]">

    <!-- Slider layout -->
    <div class="eael-google-reviews-wrapper swiper-container-wrap swiper-container-wrap-dots-outside [preset-N]"
         id="eael-google-reviews-{widget-id}">
      <div class="eael-google-reviews-items eael-google-reviews-slider">

        [?] <div class="eael-google-reviews-arrows eael-google-reviews-arrows-outside">
          <div class="swiper-button-prev-{widget-id}"><i class="…"></i></div>
          <div class="swiper-button-next-{widget-id}"><i class="…"></i></div>
        </div>

        <div class="eael-google-reviews-dots eael-google-reviews-dots-outside"></div>

        <div class="eael-google-reviews-content swiper swiper-8 swiper-container-{widget-id}"
             data-pagination=".swiper-pagination-{widget-id}"
             data-arrow-next=".swiper-button-next-{widget-id}"
             data-arrow-prev=".swiper-button-prev-{widget-id}"
             data-effect="slide|coverflow|fade"
             data-items="3" data-items_tablet="3" data-items_mobile="3"
             data-item_gap="30" data-loop="0|1"
             data-autoplay="0|1" data-autoplay_delay="3000"
             data-pause_on_hover="0|1" data-grab_cursor="0|1"
             data-speed="1000">

          <!-- Business header -->
          <div class="eael-google-reviews-slider-header">
            [?] <div class="eael-google-reviews-business-logo">…</div>
            [?] <div class="eael-google-reviews-business-name">
              <a href="{website}" [target="_blank"]>Business Name</a>
            </div>
            [?] <div class="eael-google-reviews-business-rating">
              <p>4.8</p>
              <p>{stars}</p>
              <p><a href="{maps url}">123 Google reviews</a></p>
            </div>
            [?] <div class="eael-google-reviews-business-address">
              <p><span></span> {formatted address}</p>
              <p><span></span> <a href="tel:…">+1 …</a></p>
            </div>
          </div>

          <!-- Slides -->
          <div class="eael-google-reviews-slider-body swiper-wrapper">
            <div class="eael-google-reviews-slider-item clearfix swiper-slide">
              <div class="eael-google-review-reviewer-with-text">
                <!-- One of three presets, see below -->
              </div>
            </div>
            …
          </div>

          [?] <div class="swiper-pagination-{widget-id}"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- LocalBusiness JSON-LD (only when localbusiness_schema = yes, Places legacy only) -->
[?] <script type="application/ld+json">
  { "@context": "https://schema.org", "@type": "LocalBusiness",
    "name": "…", "address": { "@type": "PostalAddress", … },
    "review": [ … ], "aggregateRating": { … }, "url": "…", "telephone": "…" }
</script>
```

### Preset variants (slider)

```html
<!-- preset-1 (vertical photo+name+time, then rating-top OR text, then rating-bottom) -->
<div class="eael-google-review-reviewer-photo"><img src="…"></div>
<div class="eael-google-review-reviewer-name"><a href="{author_url}" target="_blank">Author</a></div>
<div class="eael-google-review-time">a month ago</div>
[?] <div class="eael-google-review-rating eael-rating-position-top">{stars}</div>
<div class="eael-google-review-text">{review text}</div>
[?] <div class="eael-google-review-rating eael-rating-position-bottom">{stars}</div>

<!-- preset-2 (text on top, footer row: photo + name/time + rating) -->
<div class="preset-content-wrap">
  <div class="preset-content-body">
    <div class="eael-google-review-text">…</div>
  </div>
  <div class="preset-content-footer">
    <div class="preset-content-footer-photo">…</div>
    <div class="preset-content-footer-reviewer-name">…</div>
    <div class="preset-content-footer-rating">…</div>
  </div>
</div>

<!-- preset-3 (rating+text+SVG quote, then footer with photo+name+time) -->
<div class="preset-content-body">
  <div class="eael-google-review-rating">…</div>
  <div class="eael-google-review-text">…</div>
  <div class="preset-extra-shadow eael-d-none"><svg>…quote SVG…</svg></div>
</div>
<div class="preset-content-footer">…</div>
```

Notes:

- `data-source` / `data-layout` on outer wrapper drive JS branching. Grid path produces no Swiper init; CSS columns handle layout via `eael-column-{N}` classes.
- Review text rendered via `esc_html()` — preserves linebreaks visually but strips any markup (Google reviews are plain text anyway).
- `<a href="{author_url}">` for reviewer name comes from Google's `authorAttribution.uri` (new) / `author_url` (legacy) — opens in new tab unless accessibility "link in same tab" is enabled.
- `business_name_label` falls back to `$google_reviews_data['name']` when empty ([line 3097](../../includes/Elements/Business_Reviews.php#L3097)) — panel can override the displayed business name without affecting cache or API.
- `business_logo_icon_new` uses the FA4→FA5 ICONS shim ([_patterns.md § FA4 → FA5 icon migration shim](_patterns.md#fa4--fa5-icon-migration-shim)). Field name: legacy `eael_business_reviews_business_logo_icon`, new picker `eael_business_reviews_business_logo_icon_new`. **Empty-icon-value branch renders the hardcoded multi-colour Google "G" SVG** ([line 3083](../../includes/Elements/Business_Reviews.php#L3083)) — a unique behaviour for this widget; other shim-using widgets fall through to a placeholder, not a specific logo.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Business_Reviews.php#L90) — 11 main sections.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_business_reviews_sources` | SELECT | `google-reviews` | Content → General | Source type; extensible via `eael/business_reviews/sources` filter |
| `eael_business_reviews_google_api_type` | SELECT | `places` | Content → General | `places` (legacy) vs `places-new` |
| `eael_business_reviews_business_place_id` | TEXT | `ChIJj61dQgK6j4AR4GeTYWZsKWw` (Googleplex) | Content → General | Google Place ID — required for real data |
| `eael_business_reviews_sort_by` | SELECT | `most_relevant` | Content → General | Places API `reviews_sort` param (legacy only) |
| `eael_business_reviews_review_text_translation` | SWITCHER | empty | Content → General | Inverted into `reviews_no_translations` API param |
| `eael_business_reviews_max_reviews_places` (or `_max_reviews` legacy) | NUMBER | 5 | Content → General | Slice size after fetch; star-rating filters apply within slice |
| `eael_business_reviews_data_cache_time` | NUMBER (min) | (default DAY_IN_SECONDS) | Content → General | Transient TTL in minutes |
| `eael_business_reviews_localbusiness_schema` | SWITCHER | empty | Content → General | Emit JSON-LD; adds `address_components` to API fields (legacy only) |
| `eael_business_reviews_items_layout` | SELECT | `slider` | Content → Layout | `slider` (Swiper) vs `grid` (CSS columns) |
| `eael_business_reviews_style_preset_slider` | SELECT | `preset-1` | Content → Layout | One of three slider preset render functions |
| `eael_business_reviews_style_preset_grid` | SELECT | (preset-1) | Content → Layout | Grid preset |
| `eael_business_reviews_column[_tablet|_mobile]` | NUMBER × 3 | 3/3/3 | Content → Layout | Slider columns per breakpoint |
| `eael_business_reviews_column_grid[_tablet|_mobile]` | NUMBER × 3 | 3/2/1 | Content → Layout | Grid columns per breakpoint; written as `eael-column-N` prefix classes |
| `eael_business_reviews_column_preset_2[_tablet|_mobile]` | NUMBER × 3 | — | Content → Layout | Override columns specifically for preset-2 (visible only when preset-2 + slider) |
| `eael_business_reviews_loop` / `_arrows` / `_dots` / `_autoplay` / `_pause_on_hover` / `_grab_cursor` | SWITCHER × 6 | various | Content → Layout (slider) | Swiper options |
| `eael_business_reviews_transition_effect` | SELECT | `slide` | Content → Layout (slider) | `slide` / `fade` / `coverflow`; coverflow forces 3 columns |
| `eael_business_reviews_item_gap` | SLIDER | 30 | Content → Layout (slider) | Swiper `spaceBetween` px |
| `eael_business_reviews_autoplay_delay` | SLIDER (ms) | 3000 | Content → Layout (slider) | Swiper autoplay delay; JS sets 999999 when autoplay off (~16.7min) |
| `eael_business_reviews_slider_speed` | SLIDER (ms) | 1000 | Content → Layout (slider) | Swiper transition speed |
| `eael_business_reviews_arrows_type` | TEXT/SELECT | `fa fa-angle-right` | Content → Layout | FA class for next/prev arrows (prev is rotated via CSS) |
| `eael_business_reviews_business_logo` / `_business_name` / `_business_rating` / `_business_address` | SWITCHER × 4 | — | Content → Business Header | Toggle header row sections |
| `eael_business_reviews_business_logo_icon_new` | ICONS (FA4 shim) | (Google G SVG fallback) | Content → Business Header | Logo icon — empty value renders hardcoded Google "G" SVG |
| `eael_business_reviews_business_name_label` | TEXT | empty | Content → Business Header | Override business name display (defaults to API-returned name) |
| `eael_business_reviews_google_reviews_label` | TEXT | empty | Content → Business Header | Suffix string after rating count (e.g. "Google reviews") |
| `eael_business_reviews_reviewer_photo` / `_reviewer_name` / `_review_time` / `_review_text` / `_review_rating` | SWITCHER × 5 | — | Content → Single Review | Per-review element visibility |
| `eael_business_reviews_review_rating_position` | SELECT | `bottom` | Content → Single Review | `top` vs `bottom` — preset-1 only |
| `eael_business_reviews_review_1_star_hide` / `_2_star_hide` / `_3_star_hide` / `_4_star_hide` | SWITCHER × 4 | — | Content → Single Review | Per-rating hide filter; applied AFTER slice — no refetch |
| `eael_business_reviews_enable_accessibilty` / `_link_in_same_tab` | SWITCHER × 2 | — | Content → Accessibility | "Accessibilty" misspelled in control id — author links open in same tab when on |
| Style → various | — | — | Style tab | ~10 style sections — Business Header / Single Review / Star Rating / Slider Arrows / Dots / Layout colours |

## Conditional Dependencies

```text
eael_business_reviews_google_api_type           → visible when eael_business_reviews_sources == 'google-reviews'
eael_br_google_place_api_key_missing notice    → visible when wp_options key empty AND source == google-reviews
eael_business_reviews_max_reviews_places        → visible for Places (new) API; legacy uses _max_reviews
eael_business_reviews_style_preset_slider       → visible when layout == 'slider'
eael_business_reviews_style_preset_grid         → visible when layout == 'grid'
eael_business_reviews_column_preset_2 / _tablet / _mobile → visible when layout == 'slider' AND preset == 'preset-2'
slider-only controls (loop, arrows, dots, autoplay, …) → visible when layout == 'slider'
grid-only column controls                       → visible when layout == 'grid'
eael_business_reviews_link_in_same_tab          → visible when enable_accessibilty == 'yes'
eael_business_reviews_review_rating_position    → visible when review_rating == 'yes' (preset-1 only)
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/business_reviews/sources` | filter (emitted in `register_controls`) | `array $sources` | Add source options (Yelp, Facebook, Trustpilot) |
| `eael/business_reviews/google_api_options` | filter (emitted in `register_controls`) | `array $options` | Add or restrict API-type options |
| `eael/business_reviews/controls` | action (emitted at the end of `register_controls`'s General section) | `(Widget_Base $widget)` | Pro/third-party can append controls |
| `eael/business_reviews/settings` | filter (emitted in `get_business_reviews_settings`) | `(array $settings, array $widget_settings)` | Mutate the unified settings hash before fetch (override cache key inputs, columns, etc.) |
| `eael/business_reviews/fetch_api` | filter (emitted in `fetch_business_reviews_from_api`) | `(null|array $data, array $business_reviews, Widget_Base $widget)` | Return non-null array to short-circuit the Google fetch (Pro's Yelp/Facebook adapter hooks here) |
| `eael/business_reviews/render` | filter (emitted in `print_business_reviews`) | `(bool $handled, array $business_reviews, array $items, Widget_Base $widget)` | Return truthy to suppress default Google render and emit custom markup |

No `eael_section_pro` upsell. No `eael/pro_enabled` consumption.

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", …)` ([line 109](../../src/js/view/business-reviews.js#L109)) registering `elementorFrontend.hooks.addAction("frontend/element_ready/eael-business-reviews.default", BusinessReviewsHandler)`. Newer EA pattern.
- **Guard:** `if (eael.elementStatusCheck('eaelBusinessReviews')) return false;`
- **Vendor dependencies:**
  - `Swiper` — loaded async via `elementorFrontend.utils.swiper` (Elementor's bundled v8)
  - `DOMPurify` — global (vendor at `assets/front-end/js/lib-view/dom-purify/purify.min.js`)
- **Reads on init:**
  - `data-source` / `data-layout` from outer wrapper — branches at top of handler.
  - Slider-only: `data-pagination`, `data-arrow-next`, `data-arrow-prev`, `data-effect`, `data-items[_tablet|_mobile]`, `data-item_gap`, `data-loop`, `data-speed`, `data-autoplay`, `data-autoplay_delay`, `data-pause_on_hover`, `data-grab_cursor`.
- **Branches:**
  - `source !== 'google-reviews'` → handler exits silently (Pro source's own JS handles render).
  - `layout !== 'slider'` → handler exits (grid is CSS-only, server-rendered).
  - `effect === 'slide' || 'coverflow'` → sets `breakpoints { 1024 / 768 / 320 }` (**hardcoded** — same magic numbers as Woo_Product_Carousel; ignores Elementor breakpoint config).
  - Other effects (fade) → `items = 1`.
- **DOMPurify usage** at [lines 33, 37, 38](../../src/js/view/business-reviews.js#L33) — applies to Swiper's `pagination.el`, `navigation.nextEl`, `navigation.prevEl` selector strings. **NOT** for sanitising review content (PHP `esc_html` handles that). Defensive against future scenarios where `data-pagination` could be tampered with — at present these are server-emitted via `esc_attr` so the additional pass is belt-and-braces.
- **Runtime state:**
  - `autoplay === 0` triggers `swiperObj.autoplay.stop()` after init resolution.
  - `pauseOnHover` wires `mouseenter`/`mouseleave` listeners that toggle `autoplay.start()` / `.stop()`.
  - **`autoplay.delay = 999999` magic number** when autoplay off ([line 41](../../src/js/view/business-reviews.js#L41)) — same trick as Woo_Product_Carousel; instead of disabling autoplay outright in config, sets impossible delay then `.stop()`s after init.
- **No custom events** — handler is fire-and-forget; no cross-widget hooks listened to or emitted.
- **`swiperLoader()` helper** at [line 91](../../src/js/view/business-reviews.js#L91) wraps the async/sync paths: when Swiper isn't yet loaded, uses Elementor's `utils.swiper` (async); when already loaded (e.g. another widget on the page initialised it first), uses sync `new Swiper()`.

## Common Issues

### Reviews showing for the wrong business

- **Likely cause:** Place ID empty — defaults to Googleplex (`ChIJj61dQgK6j4AR4GeTYWZsKWw`). Or a typo in Place ID matches a different listing.
- **Diagnose:** Check the Place ID against Google's Place ID finder (`https://developers.google.com/maps/documentation/places/web-service/place-id`).
- **Fix:** Set the correct Place ID. Hardcoded Googleplex fallback is a UX trap — consider hooking `eael/business_reviews/settings` to fail-loud when Place ID is empty.

### "An error occurred while fetching data from Google Places API" with no detail

- **Likely cause:** API key missing, quota exceeded, key restricted to wrong referrer, or Places API not enabled in Cloud Console.
- **Diagnose:** Tail `wp_remote_get` response — set `'timeout' => 240` so slow networks don't false-fail. Hit the URL directly in browser with the same key.
- **Fix:** Enable "Places API" (legacy) or "Places API (New)" in Google Cloud Console → APIs & Services. Confirm key is unrestricted or HTTP referrer allowlist includes your domain. Set the key in EA Dashboard → Elements → Business Reviews Settings.

### LocalBusiness schema empty when using Places (New) API

- **Likely cause:** New API doesn't return `address_components`; the mapping at [line 2818](../../includes/Elements/Business_Reviews.php#L2818) doesn't populate that field. `print_localbusiness_schema()` emits empty strings for street / city / region / country.
- **Diagnose:** View source — search for `"addressLocality":""`. If empty, this is the cause.
- **Fix:** Switch API Type to "Google Places API" (legacy) when LocalBusiness schema matters. Or hook `eael/business_reviews/fetch_api` to enrich the response with parsed address components.

### Zero reviews render even though business has reviews

- **Likely cause:** Star-rating filters are all on AND the first `max_reviews` results are all in the hidden tier. Filter applies after slice — no refetch with different sort.
- **Diagnose:** Temporarily disable all star-rating hide filters; if reviews appear, filtering was the cause.
- **Fix:** Increase `max_reviews`; tune the sort order (`most_relevant`, `newest`, `highest_rating`, `lowest_rating`) to surface allowed tiers earlier.

### Transient cache "stuck" with old reviews after API key change

- **Likely cause:** The site-wide API key is in `wp_options` and not part of the cache key (well, it is via md5 — but only for the specific widget instance). Other instances of the widget on other pages may still have cached responses.
- **Diagnose:** Run `wp transient delete --all` or check `wp_options` for keys starting with `_transient_eael_google-reviews_`.
- **Fix:** Bump `eael_business_reviews_data_cache_time` to 1 minute, save, wait, restore. Or programmatically `delete_transient($business_reviews['cache_key'])` after key updates.

### Coverflow effect ignores column setting

- **Likely cause:** Render at [line 2740](../../includes/Elements/Business_Reviews.php#L2740) overwrites `columns = 3` whenever `effect === 'coverflow'`.
- **Diagnose:** Switch effect to `slide`; columns honoured. Switch back to coverflow; always 3.
- **Fix:** Intentional design — coverflow visually requires center+side slides. To override, hook `eael/business_reviews/settings` to set `columns` after the forced reset.

## Known Limitations

- **Hardcoded Googleplex Place ID default** ([line 2653](../../includes/Elements/Business_Reviews.php#L2653)) — a widget saved with empty Place ID renders someone else's reviews using your API key (and burns your quota).
- **Single site-wide API key in `wp_options`** — no per-widget key override; rotating the key affects every Business Reviews widget at once.
- **Places (New) API does not yield `address_components`** — LocalBusiness schema's `PostalAddress` is empty when using the new API; the mapping at [lines 2818-2842](../../includes/Elements/Business_Reviews.php#L2818) doesn't translate address parts.
- **Star-rating filter applied after slice** — no refetch if filtered tier consumes all results in the slice; can yield zero reviews while plenty exist past the slice.
- **Default cache TTL of 1 day** — combined with sort/translation in cache key, panel-side filter changes can lag visibly until cache expires.
- **`reviews_max_count` slice is post-cache** — fine because all reviews are returned by the API (max 5 from Google as of writing), but if Google ever lifts the limit, slicing post-cache still works the same way.
- **Coverflow effect forces `columns = 3`** ([line 2740](../../includes/Elements/Business_Reviews.php#L2740)) — user-set columns are silently overridden.
- **Slider breakpoints hardcoded to 1024 / 768 / 320 in JS** ([lines 49-62 of view/business-reviews.js](../../src/js/view/business-reviews.js#L49)) — ignores Elementor breakpoint configuration.
- **Empty business-logo-icon-new value emits a hardcoded multi-colour Google "G" SVG** ([line 3083](../../includes/Elements/Business_Reviews.php#L3083)) — non-obvious; a user who clears the icon expects "no icon" but gets Google's logo. Removing the logo requires switching `business_logo` off entirely.
- **Accessibility control id misspelled `eael_business_reviews_enable_accessibilty`** (missing `i`) — third-party hooks targeting it must use the misspelt form.
- **`fetch_business_reviews_from_api` returns inconsistently** — when transient is set, returns the items wrapped in `['items' => …, 'error_message' => '']`; when no transient and `fetch_api` filter returns null, returns raw `$data` directly from `fetch_google_reviews_from_api` (already wrapped). [Lines 2779-2789](../../includes/Elements/Business_Reviews.php#L2779). Result: shape is consistent, but the cache-bypass path is shorter and harder to trace.
- **No CSP friendliness** — JSON-LD `<script>` block printed via `echo`; OK for `script-src` if site allows ld+json. Swiper config includes inline styles on slides — sites with strict `style-src` see visual glitches.
- **`render()` doesn't gate on Elementor editor / preview** — fetch runs in editor too; can hit Google API quota during page-building.
- **DOMPurify added as a dependency solely for sanitising Swiper selector attributes** — costly for ~3 lines of usage; PHP-side `esc_attr` already covers normal cases. Defence-in-depth justifies the include.
- **`fetch_google_place_response_error_message()` switch-case at [line 2901](../../includes/Elements/Business_Reviews.php#L2901) returns mapped messages for Places (legacy) status codes only** — Places (New) errors arrive in `$body->error->message` directly and bypass this mapper.
