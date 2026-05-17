# Twitter Feed Widget

> Fetches tweets from the Twitter / X API (v1.1 with OAuth bearer-token exchange, or v2 with direct bearer token) and renders them as a masonry or list-style card grid. All tweets fetch upfront and render in the initial response — Load More is page-batch reveal (CSS show/hide), not AJAX. Editor-side "Clear Cache" button calls an admin-AJAX endpoint to flush the transient.

**Class file:** [`includes/Elements/Twitter_Feed.php`](../../includes/Elements/Twitter_Feed.php)
**Slug:** `twitter-feed` (widget id `eael-twitter-feed`)
**Public docs:** <https://essential-addons.com/elementor/docs/twitter-feed/>
**Pro-shared:** ❌ No widget-specific Pro extension — Pro ships a separate `Twitter_Feed_Carousel` widget (different class, different slug). Standard `eael_section_pro` upsell ([line 588](../../includes/Elements/Twitter_Feed.php#L588)) appears when Pro is inactive.

---

## Overview

Twitter Feed pulls tweets from a configured account and optionally filters them by hashtag, then renders the results as cards in a masonry or list layout. Two API paths exist: legacy Twitter API v1.1 (consumer key + consumer secret → OAuth bearer-token exchange) and the v2 API (direct user-supplied bearer token). Choice is controlled by an `eael_twitter_api_v2` switcher. All tweets are fetched server-side and rendered in the initial page response; the "Load More" button is client-side page-batch reveal (CSS `eael-d-none` → `eael-d-block`), not AJAX pagination.

API responses are cached as WordPress transients with a configurable expiration (default 60 minutes) and a cache key that mixes account name + credentials hash. The widget exposes an admin-AJAX endpoint `eael_clear_widget_cache_data` for the editor "Clear Cache" button to flush the transient on demand.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Twitter API v1.1 (consumer key/secret + OAuth) | ✅ | ✅ |
| Twitter API v2 (bearer token) | ✅ | ✅ |
| Masonry / list layouts | ✅ | ✅ |
| Page-batch Load More | ✅ | ✅ |
| Cache with admin "Clear Cache" button | ✅ | ✅ |
| Hashtag filtering | ✅ | ✅ |
| Twitter Feed Carousel widget | — | ✅ (separate widget class `Twitter_Feed_Carousel`) |
| `eael_section_pro` upsell panel | shown | hidden |

Pro does not extend Twitter_Feed itself — it ships an independent Carousel widget with its own controls and Swiper integration. See [`_patterns.md § eael_section_pro standard upsell panel`](_patterns.md#eael_section_pro-standard-upsell-panel) for the upsell pattern.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Twitter_Feed.php`](../../includes/Elements/Twitter_Feed.php) | PHP widget class — controls (~13 sections), `render()`, `render_loadmore_button()` |
| [`includes/Traits/Twitter_Feed.php`](../../includes/Traits/Twitter_Feed.php) | `twitter_feed_render_items()` — API caller + transient cache + tweet HTML generator |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php#L535) | `eael_clear_widget_cache_data()` — admin-AJAX handler for the "Clear Cache" button |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L165) | Registers `wp_ajax_eael_clear_widget_cache_data` |
| [`src/css/view/twitter-feed.scss`](../../src/css/view/twitter-feed.scss) | Source styles — card layout, masonry, list view, hover effects |
| [`src/js/view/twitter-feed.js`](../../src/js/view/twitter-feed.js) | Frontend logic (87 lines) — Isotope masonry init, page-batch Load More handler, editor Clear-Cache AJAX |
| [`config.php`](../../config.php#L274) entry `'twitter-feed'` | Asset declaration: twitter-feed.min.css + imagesLoaded + Isotope + twitter-feed.min.js |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | Vendor — image-load coordination for Isotope reflow |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | Vendor — Isotope masonry layout |

## Architecture

- **Two API paths via switcher** — `eael_twitter_api_v2` toggles between v1.1 (consumer_key + consumer_secret → POST `oauth2/token` → bearer token cached in `update_option`) and v2 (direct bearer token from user setting). v2 path also fetches the user object once and caches it in `update_option` to map username → user_id ([Twitter_Feed.php trait line 79-100](../../includes/Traits/Twitter_Feed.php#L79)).
- **All tweets fetched + rendered upfront; Load More is CSS reveal** — `twitter_feed_render_items()` loops every tweet (up to `eael_twitter_feed_post_limit`, default 10) and assigns each to a "page" via counter math; items beyond the first page get `eael-d-none` class. JS "Load More" handler ([line 66 of twitter-feed.js](../../src/js/view/twitter-feed.js#L66)) just swaps `eael-d-none` → `eael-d-block` for the next page batch — no AJAX. This is different from Post_Grid / Post_Timeline which use real AJAX pagination.
- **Transient cache with credential-hash key** — cache key composes account name + expiration + MD5 of (hashtag + consumer_key + consumer_secret + bearer_token) ([trait line 23](../../includes/Traits/Twitter_Feed.php#L23)). Rotating credentials automatically invalidates the cache (different hash → different key). Default expiration 60 minutes via `eael_twitter_feed_cache_limit` × `MINUTE_IN_SECONDS`.
- **Editor "Clear Cache" button via `Controls_Manager::BUTTON` with custom event** — ([line 217-229 of Twitter_Feed.php](../../includes/Elements/Twitter_Feed.php#L217)) emits a `ea:cache:clear` event; JS handler in twitter-feed.js editor branch sends the AJAX request with credentials in the payload. The endpoint `wp_ajax_eael_clear_widget_cache_data` is admin-only (no nopriv variant), so credentials in transit are admin-context only.
- **`https_ssl_verify` disabled** ([trait lines 49 and 70](../../includes/Traits/Twitter_Feed.php#L49)) — `add_filter('https_ssl_verify', '__return_false')` before each `wp_remote_post` / `wp_remote_get` to Twitter API. Disables SSL certificate verification — MITM exposure for development hosts with broken cert chains. Documented in Known Limitations.
- **Bearer token stored as WordPress option** — `update_option($id . '_' . $account_name . '_tf_token', $token)` ([trait line 65](../../includes/Traits/Twitter_Feed.php#L65)) — plaintext storage in `wp_options`. Same for the user object (`_tf_user_object`).
- **Per-widget inline `<style>` block** — render emits column-width calc rules scoped to `.eael-twitter-feed-<widget-id>` ([Twitter_Feed.php lines 1618-1640](../../includes/Elements/Twitter_Feed.php#L1618)). Column gutter math is server-computed; CSS variables would be cleaner but require browser support.
- **Editor preview inline `<script>`** — separate Isotope init at render time ([line 1641-1666](../../includes/Elements/Twitter_Feed.php#L1641)) because `frontend/element_ready` doesn't fire reliably in the Elementor editor iframe.
- **Static class property `self::$twitter_feed_fetched_count`** — tracks number of items fetched; `render_loadmore_button()` shows the button only when `$twitter_feed_fetched_count > $post_per_page` ([line 1546](../../includes/Elements/Twitter_Feed.php#L1546)). Static means it persists across multiple widget instances on the same page — last instance wins for the button visibility check.

## Render Output

```html
<div>
  <div class="eael-twitter-feed eael-twitter-feed-<widget-id>
              eael-twitter-feed-masonry
              eael-twitter-feed-col-3
              clearfix"
       data-gutter="10"
       data-posts-per-page="3"
       data-total-posts="10"
       data-nomore-item-text="No more items"
       data-next-page="2">

    <!-- For each tweet (up to post_limit). Items beyond page 1 get .eael-d-none: -->
    <div class="eael-twitter-feed-item page-1 eael-d-block">
      <div class="eael-twitter-feed-item-inner">
        <div class="eael-twitter-feed-item-header">
          <img src="profile-image-url" alt="..." class="eael-twitter-feed-author-avatar">
          <h2 class="eael-twitter-feed-author-name">User Name</h2>
          <span class="eael-twitter-feed-author-screen-name">@username</span>
        </div>
        <div class="eael-twitter-feed-item-content">Tweet text with parsed entities (links, hashtags, mentions)…</div>
        <div class="eael-twitter-feed-item-footer">
          <span class="eael-twitter-feed-item-date">2 days ago</span>
          <a href="..." class="eael-twitter-feed-item-link" target="_blank">…</a>
        </div>
      </div>
    </div>
    …
    <div class="eael-twitter-feed-item page-2 eael-d-none">…</div>
    <div class="eael-twitter-feed-item page-3 eael-d-none eael-last-twitter-feed-item">…</div>
  </div>

  <div class="clearfix">
    [?] <div class="eael-twitter-feed-loadmore-wrap">
          <a href="#" class="eael-twitter-feed-load-more elementor-button elementor-size-md">
            <span class="eael-btn-loader"></span>
            <span class="eael-twitter-feed-load-more-text">Load More</span>
          </a>
        </div>
  </div>
</div>

<!-- Inline <style> block — column widths scoped to this widget -->
<style>
  .eael-twitter-feed-<widget-id>.eael-twitter-feed-masonry.eael-twitter-feed-col-3 .eael-twitter-feed-item {
      width: calc(33.33% - 7px);
  }
  …
</style>

[?] <!-- Inline <script> block — Isotope init in editor mode only -->
<script type="text/javascript">…</script>
```

Notes:

- Outer wrapper carries the widget id in its class (`.eael-twitter-feed-<widget-id>`) — used by the per-widget inline `<style>` to scope column widths.
- Each tweet `<div class="eael-twitter-feed-item page-<N>">` carries the page number it belongs to. JS Load More handler removes `eael-d-none` from `.eael-twitter-feed-item.page-<N>` to reveal that page.
- Last tweet has `eael-last-twitter-feed-item` class; JS uses this to know when to swap the button to "No more items" text and fade out.
- `data-posts-per-page` and `data-total-posts` are read by JS; `data-next-page` increments per click.
- Profile image / username come from the cached user object (`_tf_user_object` option); first render after cache miss makes an extra API call to fetch the user info.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Twitter_Feed.php#L90) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_twitter_api_v2` | SWITCHER | empty | Content → Account Settings | Toggles between API v1.1 and v2 paths |
| `eael_twitter_feed_ac_name` | TEXT (dynamic) | `@wpdevteam` | Content → Account Settings | Twitter account handle (server strips `@` before API call) |
| `eael_twitter_feed_hashtag_name` | TEXT (dynamic) | empty | Content → Account Settings | Filter tweets by hashtag (case-insensitive match) |
| `eael_twitter_feed_consumer_key` / `_consumer_secret` | TEXT | empty | Content → Account Settings | v1.1 credentials (stored in widget settings) |
| `eael_twitter_feed_bearer_token` | TEXT | empty | Content → Account Settings | v2 credential (stored in widget settings) |
| `eael_auto_clear_cache` | SWITCHER | `yes` | Content → Account Settings | Toggles between time-based cache expiration and manual clear |
| `eael_twitter_feed_cache_limit` | NUMBER (minutes) | `60` | Content → Account Settings | Cache TTL when auto-clear enabled |
| `eael_clear_cache_control` | BUTTON | — | Content → Account Settings | "Clear" button; emits `ea:cache:clear` event handled by editor JS |
| `eael_twitter_feed_type` | SELECT | `masonry` | Content → Layout | List or Masonry layout (drives Isotope init) |
| `eael_twitter_feed_type_col_type` | SELECT | `col-3` | Content → Layout | 2/3/4 column grid (masonry only) |
| `eael_twitter_feed_content_length` | NUMBER | `400` | Content → Layout | Tweet content max characters |
| `eael_twitter_feed_column_spacing` | SLIDER (responsive, px) | `10` | Content → Layout | Isotope gutter + inline `<style>` column widths |
| `eael_twitter_feed_post_limit` | NUMBER | `10` | Content → Layout | Total tweets to fetch (capped client-side; API may return more) |
| `eael_twitter_feed_posts_per_page` | NUMBER | (set later) | Content → Layout | Tweets per Load More page batch |
| `eael_twitter_feed_show_replies` | SWITCHER | `true` | Content → Layout | Show replies in feed |
| Content → Card Settings | various | — | Content → Card | Header / footer / date format / link target controls |
| `pagination` | SWITCHER | (set later) | Content → Pagination | Show Load More button |
| `load_more_text` / `nomore_items_text` | TEXT | — | Content → Pagination | Button label + final-state label |
| `load_more_icon_new` | ICONS | empty | Content → Pagination | Button icon (FA4 migration shim) |
| `button_icon_position` | SELECT | — | Content → Pagination | before / after text |
| Style → various sections | — | — | Style tab | Card, header, body, button styling |

## Conditional Dependencies

```text
# API version branching
eael_twitter_feed_consumer_key       → visible when eael_twitter_api_v2 == ''
eael_twitter_feed_consumer_secret    → visible when eael_twitter_api_v2 == ''
eael_twitter_feed_bearer_token       → visible when eael_twitter_api_v2 == 'yes'

# Cache controls
eael_twitter_feed_cache_limit        → visible when eael_auto_clear_cache == 'yes'
eael_clear_cache_control             → visible when eael_auto_clear_cache == ''  (manual mode)

# Layout
eael_twitter_feed_type_col_type      → visible when eael_twitter_feed_type == 'masonry'

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

## Hooks & Filters

| Hook | Type | Listener | Purpose |
| ---- | ---- | -------- | ------- |
| `wp_ajax_eael_clear_widget_cache_data` | action (consumed) | `Helper::eael_clear_widget_cache_data()` | Admin-AJAX endpoint for "Clear Cache" button — admin only, no `nopriv` variant |
| `https_ssl_verify` | filter (consumed, two calls) | `__return_false` | Disables SSL cert verification for `wp_remote_post` / `wp_remote_get` to Twitter API |
| `eael/pro_enabled` | filter (consumed) | — | Hides upsell panel when Pro active |

No widget-specific extension hooks. The widget consumes only WordPress + EA core hooks; Pro's `Twitter_Feed_Carousel` is a completely independent widget class.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-twitter-feed.default', TwitterFeedHandler)`
- **Editor trigger:** `elementor.hooks.addAction('panel/open_editor/widget/eael-twitter-feed', …)` — wires the "Clear Cache" button click handler in the panel
- **Guard:** none — no `elementStatusCheck`
- **Reads on init:** `.eael-twitter-feed` `data-posts-per-page` / `data-total-posts` / `data-nomore-item-text` / `data-next-page`; `.eael-twitter-feed-masonry` `data-gutter`
- **Isotope init (frontend only, not editor):** `$('.eael-twitter-feed-masonry').isotope({ itemSelector: '.eael-twitter-feed-item', percentPosition: true, masonry: { columnWidth: '.eael-twitter-feed-item', gutter: $gutter } })` — followed by `imagesLoaded().progress(layout)` for image-load reflow
- **Editor cache-clear AJAX:** sends `{ action: 'eael_clear_widget_cache_data', security: nonce, page_permalink, widget_id, ac_name, hastag, c_key, c_secret }` — the credentials are passed to the server again because that's how the cache key is computed (must match the original cache key built during render)
- **Load More handler:** `.eael-twitter-feed-load-more` click → `removeClass('eael-d-none').addClass('eael-d-block')` on `.eael-twitter-feed-item.page-<next>`; increments `data-next-page`; calls `isotope('layout')` to reflow; on last page, swaps button text to "No more items" and fades out
- **Runtime state:** module-level `$next_page` counter (per-widget closure); no persistent global state across instances
- **Editor preview inline `<script>`** emitted by render() handles Isotope init in the editor iframe where `frontend/element_ready` is unreliable

## Common Issues

### "Account Name" set but no tweets appear

- **Likely cause:** API credentials missing or wrong; OR Twitter API returned an error (rate limit, account suspended, etc.); OR the response is cached as empty
- **Diagnose:** browser network tab for `oauth2/token` or `users/{id}/tweets` API calls — check status codes; inspect the rendered `<div class="eael-twitter-feed">` — is it empty?
- **Fix:** verify credentials match the widget's API version setting; click "Clear Cache" in the editor to flush the transient; check WP debug log for `wp_remote_get` errors

### "Clear Cache" button shows "Failed"

- **Likely cause:** AJAX nonce mismatch (stale page after Elementor save), OR `wp_ajax_eael_clear_widget_cache_data` handler isn't registered (Bootstrap loading failure), OR the credentials in the panel don't match what was used to build the cache key
- **Diagnose:** browser network tab for the AJAX response payload
- **Fix:** reload the Elementor editor; check WP debug log

### Twitter API v2 returns "404 Not Found" for username

- **Likely cause:** `@` not stripped from username; OR username has a space; OR account was renamed
- **Diagnose:** check `eael_twitter_feed_ac_name` — does it have `@` prefix? The render method strips it via `str_replace('@', '', …)` but the cache key uses the raw value
- **Fix:** remove the `@`; the server normalises but the cache key still includes the unnormalised value, so changing produces a fresh cache key

### Masonry items overlap or stack incorrectly

- **Likely cause:** Isotope ran before images loaded; OR per-widget inline `<style>` block didn't load (CSS race condition); OR theme overrode `.eael-twitter-feed-item` width
- **Diagnose:** browser DevTools — inspect computed widths on `.eael-twitter-feed-item`
- **Fix:** hard refresh; the `imagesLoaded().progress(layout)` should handle most cases but slow connections may still race

### Load More button stays visible when no more tweets

- **Likely cause:** `eael-last-twitter-feed-item` class missing from the actual last item (template bug, or hashtag filter removed the marked item)
- **Diagnose:** inspect — does the last visible item have `eael-last-twitter-feed-item`?
- **Fix:** if hashtag filter is on, the "last item" assignment runs before the filter and may not align; verify the rendered HTML

### Credentials visible in widget post meta export

- **Likely cause:** by design — `eael_twitter_feed_consumer_key`, `_consumer_secret`, `_bearer_token` are stored as widget settings, which Elementor saves as post meta. Exporting the page or copying the widget exposes them
- **Diagnose:** inspect the post meta via WP REST API or DB query
- **Fix:** known limitation. Treat the credentials as sensitive; do not share exported pages with credentials embedded

### SSL certificate error breaks the API call

- **Likely cause:** `https_ssl_verify` is disabled by the widget ([trait lines 49, 70](../../includes/Traits/Twitter_Feed.php#L49)) — but if the server-side cURL still has fatal SSL issues (e.g. expired CA bundle), the request fails before the filter applies
- **Diagnose:** WP debug log for "SSL certificate problem"
- **Fix:** update server's CA bundle; or set `WP_HTTP_BLOCK_EXTERNAL` is NOT a fix, only worsens the situation. Long-term, the widget should remove the `__return_false` filter

## Known Limitations

- **`https_ssl_verify` disabled** ([trait lines 49 and 70](../../includes/Traits/Twitter_Feed.php#L49)) — `add_filter('https_ssl_verify', '__return_false')` before each Twitter API call. MITM exposure on development hosts with broken cert chains; in production with valid CA bundles, no protection lost — but the bypass itself is a security anti-pattern that should be removed.
- **API credentials stored as widget settings** — consumer key/secret and bearer token live in post meta, plaintext. Exporting / sharing the page exposes them.
- **Bearer token stored as WordPress option** — `update_option(<id>_<account>_tf_token, $token)` — plaintext in `wp_options`. Accessible to any plugin that reads options; survives page deletion until manually cleaned.
- **All tweets fetched upfront; Load More is CSS reveal, not AJAX** — fetching 100 tweets to show 10 wastes API quota. v1.1 endpoint requests `count=999`; v2 requests `max_results=100`. Cache softens the cost but cold cache fetches the maximum.
- **No `elementStatusCheck` guard** in JS — re-fired `elementor/frontend/init` re-runs Isotope init; the call is idempotent but wasteful.
- **Per-widget inline `<style>` block** for column widths — works but bloats every Twitter Feed widget instance with ~10 lines of inline CSS.
- **Static class property `self::$twitter_feed_fetched_count`** — multi-widget pages: each render overwrites the static, so the Load More button visibility check for the first widget may use the wrong count by the time the second widget renders. Edge case.
- **AJAX cache-clear handler is admin-only** — correct security posture, but means programmatic cache clearing from theme code requires direct `delete_transient()` call with the same composed key.
- **Cache key includes credentials hash** — rotating credentials forces a full re-fetch even if the underlying user/tweet data hasn't changed. Acceptable trade-off; documented for ops awareness.
- **Twitter API v1.1 is deprecated by X (Twitter)** — endpoints `oauth2/token` and `1.1/statuses/user_timeline.json` continue to function but are no longer maintained. v2 is the current supported path; v1.1 controls remain for legacy widgets that haven't been migrated.
- **`eael_twitter_feed_show_replies` switcher has `default => 'true'`** ([line 316](../../includes/Elements/Twitter_Feed.php#L316)) — note "true" string, not boolean. Some Elementor versions treat string vs boolean differently for switcher defaults; usually works but worth noting.
- **Twitter API endpoint URLs hardcoded** — if X migrates to a different domain (e.g. `api.x.com`), the widget breaks until the trait is updated. Already-deprecated URLs like `api.twitter.com/1.1/statuses/user_timeline.json` are at risk.
