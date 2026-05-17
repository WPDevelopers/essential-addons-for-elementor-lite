# Facebook Feed Widget

> Fetches posts from a Facebook Page via the Graph API (v18.0) and renders them as a masonry / grid layout. Each post card shows the page avatar, post text, attached image, comments preview, and a link to the original post. Load More triggers a dedicated AJAX endpoint (`facebook_feed_load_more`) that re-runs `facebook_feed_render_items()` and returns paginated HTML. Server-side transient caching with credential-hash key, similar to Twitter Feed.

**Class file:** [`includes/Elements/Facebook_Feed.php`](../../includes/Elements/Facebook_Feed.php)
**Slug:** `facebook-feed` (widget id `eael-facebook-feed`)
**Public docs:** <https://essential-addons.com/elementor/docs/facebook-feed/>
**Pro-shared:** ❌ Lite-only widget. **No Pro reference at all** — Pro neither subclasses, references, nor ships a sibling Carousel widget. Unusually, this widget has **no `eael_section_pro` upsell panel either** (one of the few widgets without the upsell — same as Code_Snippet, Feature_List, SVG_Draw, Image_Accordion).

---

## Overview

Facebook Feed pulls posts from a configured Facebook Page using the Graph API (currently v18.0 for posts, v4.0 for the avatar — version mismatch is legacy). Each post becomes a card in an Isotope masonry grid; on cold cache, posts are fetched from `graph.facebook.com/v18.0/{page_id}/{posts|feed}?...&access_token=...` and stored as a transient. Page credentials (Page ID + Access Token labeled "Secret Key" in the panel) are configured per widget.

Load More uses a dedicated AJAX endpoint (`facebook_feed_load_more`) — separate from the shared `load_more` action used by Post_Grid family. The handler re-runs `facebook_feed_render_items()` with the requested page number. Security posture is solid: nonce verified, widget settings re-fetched server-side from post meta via widget_id + post_id (client cannot tamper with the access token, only sends ID references).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All widget features (account, layout, cards, comments, load more) | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro sibling Carousel widget | — | — (none) |

The widget has zero Pro extension surface and zero upsell panel — clean Lite-only widget. Customisation is via CSS overrides only.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Facebook_Feed.php`](../../includes/Elements/Facebook_Feed.php) | PHP widget class — controls (~6 sections), thin `render()` that calls the trait |
| [`includes/Traits/Facebook_Feed.php`](../../includes/Traits/Facebook_Feed.php) | `facebook_feed_render_items()` — dual-purpose: initial render + AJAX load-more handler; `get_url()` builds the Graph API URL |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L158) | Registers `wp_ajax_facebook_feed_load_more` (logged-in) and `wp_ajax_nopriv_facebook_feed_load_more` (logged-out) |
| [`src/css/view/facebook-feed.scss`](../../src/css/view/facebook-feed.scss) | Source styles — card layout, header, image, comments, hover |
| [`src/js/view/facebook-feed.js`](../../src/js/view/facebook-feed.js) | Frontend logic (81 lines) — Isotope init, AJAX Load More, cross-widget reflow hooks |
| [`config.php`](../../config.php#L303) entry `'facebook-feed'` | Asset declaration: load-more.min.css + facebook-feed.min.css + imagesLoaded + Isotope + facebook-feed.min.js |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | Vendor — image-load coordination |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | Vendor — Isotope masonry layout |

## Architecture

- **Dedicated AJAX endpoint, NOT the shared `load_more`** — Facebook Feed registers `wp_ajax_facebook_feed_load_more` ([Bootstrap line 158-159](../../includes/Classes/Bootstrap.php#L158)) with a `nopriv` variant. JS in `facebook-feed.js` calls it directly with `{ action: 'facebook_feed_load_more', security: nonce, page, post_id, widget_id }`. Distinct from Post_Grid's shared `load_more` because the response format is JSON (`{ html, num_pages }`) not raw HTML.
- **`facebook_feed_render_items()` is dual-purpose** — called from both `render()` (initial render) and the AJAX handler. The trait method ([line 19-21](../../includes/Traits/Facebook_Feed.php#L19)) checks `$_REQUEST['action'] === 'facebook_feed_load_more'` at the top and branches: AJAX path re-fetches settings via `HelperClass::eael_get_widget_settings($post_id, $widget_id)`; render path uses `get_settings_for_display()`.
- **Nonce + server-side settings re-fetch protects the access token** — AJAX handler verifies `check_ajax_referer('essential-addons-elementor', 'security')` at [line 24](../../includes/Traits/Facebook_Feed.php#L24), then re-fetches widget settings server-side using `post_id + widget_id` from the request. Client sends only IDs; the Facebook Page ID, access token, and source live in post meta on the server. Tampering with the AJAX payload cannot leak credentials.
- **Transient cache with credential-hash key** — `eael_facebook_feed_<MD5(source + page_id + token + cache_limit)>` ([trait line 66-72](../../includes/Traits/Facebook_Feed.php#L66)) — same pattern as Twitter Feed. Default cache TTL 60 minutes; rotating credentials auto-invalidates.
- **Graph API version inconsistency** — posts/feed endpoints use `v18.0` ([trait line 306-329](../../includes/Traits/Facebook_Feed.php#L306)) but the avatar image URL hardcodes `v4.0` ([trait line 117](../../includes/Traits/Facebook_Feed.php#L117)). The `v4.0` URL still resolves due to Facebook's compatibility shim but is at risk of removal. Worth a future fix.
- **Cross-widget reflow hooks subscribed** — `eael.hooks.addAction("ea-lightbox-triggered" / "ea-advanced-tabs-triggered" / "ea-advanced-accordion-triggered" / "ea-toggle-triggered", "ea", FacebookGallery)` ([JS lines 72-75](../../src/js/view/facebook-feed.js#L72)). Re-runs Isotope layout when the widget appears inside a previously-hidden container — fixes the "masonry stacked on top of each other" bug that happens when a hidden tab/accordion finally becomes visible.
- **Editor preview inline `<script>`** — separate Isotope init in `render()` for the Elementor iframe. ⚠️ **The variable is named `$instagram_gallery`** ([line 1325 of Facebook_Feed.php](../../includes/Elements/Facebook_Feed.php#L1325)) — clear copy-paste artifact from Instagram_Feed widget. Functionally harmless but signals shared lineage.
- **`access_token` field labeled "Secret Key"** in the panel ([line 115](../../includes/Elements/Facebook_Feed.php#L115)) — misleading naming; the value is an OAuth access token, not a "secret key". Renames would break saved widget data, so it stays.
- **No `elementStatusCheck` guard** in JS — re-fired `frontend/init` re-runs Isotope. Idempotent but wasteful.
- **`wp_ajax_nopriv_` variant** ([Bootstrap line 159](../../includes/Classes/Bootstrap.php#L159)) — allows logged-out visitors to trigger Load More. Safe because: (1) nonce verified, (2) settings re-fetched server-side from `post_id + widget_id`, (3) no privilege-dependent paths. Unauthenticated content disclosure is the published Facebook posts themselves — already public.

## Render Output

```html
<div id="eael-facebook-feed-<widget-id>"
     class="eael-facebook-feed
            eael-facebook-feed-{column-class}
            eael-facebook-feed-{layout}
            [eael-facebook-feed-square-image]">

  <!-- For each Facebook post (up to max visible items): -->
  <div class="eael-facebook-feed-item">
    <div class="eael-facebook-feed-item-inner">
      <div class="eael-facebook-feed-item-header">
        <a href="https://www.facebook.com/<page-id>" target="_blank">
          <img src="https://graph.facebook.com/v4.0/<page-id>/picture" alt="..."
               class="eael-facebook-feed-avatar">
        </a>
        <div class="eael-facebook-feed-author-info">
          <a href="..." class="eael-facebook-feed-author-name">Page Name</a>
          <span class="eael-facebook-feed-time">2 hours ago</span>
        </div>
      </div>
      <div class="eael-facebook-feed-item-body">
        <p class="eael-facebook-feed-item-message">Post text...</p>
        [?] <div class="eael-facebook-feed-item-image">
              <img src="post-image-url" alt="...">
            </div>
        [?] <div class="eael-facebook-feed-item-comments">
              <!-- comments preview, when display_comment is on -->
            </div>
      </div>
      <a href="post-permalink" target="_blank" class="eael-facebook-feed-item-link">
        See on Facebook
      </a>
    </div>
  </div>
  …
</div>
<div class="clearfix"></div>

[?] <div class="eael-load-more-button-wrap">
      <button class="eael-load-more-button"
              id="eael-load-more-btn-<widget-id>"
              data-widget-id="<widget-id>"
              data-post-id="<page-post-id>"
              data-page="1">
        <span class="eael-btn-loader button__loader"></span>
        <span class="eael_fb_load_more_text">Load More</span>
      </button>
    </div>

[?] <!-- Inline <script> block — Isotope init in editor mode only.
        Variable is named $instagram_gallery (copy-paste artifact). -->
<script type="text/javascript">…</script>
```

Notes:

- Outer wrapper carries the widget id in its class and as `id`. Layout, column count, and force-square-image are class modifiers.
- Each post card has a fixed structure: header (avatar + page name + time), body (message + optional image + optional comments), footer (link to Facebook).
- Avatar URL uses `graph.facebook.com/v4.0/<page-id>/picture` — different API version from the posts endpoint (`v18.0`).
- Load More button carries `data-widget-id` and `data-post-id` (Elementor page id, NOT Facebook page id — confusing naming).
- `eael-facebook-feed-square-image` class added when "Force Square Image" toggle is on; SCSS applies `object-fit` rules.
- Comments preview is rendered server-side from the Graph API response when `eael_facebook_feed_comments` setting is on.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Facebook_Feed.php#L81) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_facebook_feed_page_id` | TEXT (dynamic) | empty | Content → Account Settings | Facebook Page ID — used in Graph API URL |
| `eael_facebook_feed_access_token` (labeled "Secret Key") | TEXT | empty | Content → Account Settings | Graph API access token (labeled misleadingly) |
| `eael_facebook_feed_data_source` | SELECT | `posts` | Content → Account Settings | API endpoint: `posts` or `feed` |
| `eael_facebook_feed_cache_limit` | NUMBER (minutes) | `60` | Content → Account Settings | Transient TTL |
| `eael_facebook_feed_sort_by` | SELECT | `most-recent` | Content → Feed Settings | Newest / Oldest |
| `eael_facebook_feed_image_count` | SLIDER | `12` | Content → Feed Settings | Max visible items per page |
| `eael_facebook_feed_force_square_img` | SWITCHER | `no` | Content → Feed Settings | Adds `eael-facebook-feed-square-image` class |
| `eael_facebook_feed_image_render_type` | SELECT | `fill` | Content → Feed Settings | Stretched / Cropped (only when force-square is on) |
| `eael_facebook_feed_image_dimension` | SLIDER (px, responsive) | `400` | Content → Feed Settings | Image size when force-square is on |
| `eael_facebook_feed_columns` | SELECT | (set) | Content → General Settings | Column count class |
| `eael_facebook_feed_layout` | SELECT | (set) | Content → General Settings | Layout variant class |
| `eael_facebook_feed_comments` | SWITCHER | empty | Content → General Settings | Render comments preview |
| `eael_facebook_feed_link_target` | SWITCHER | empty | Content → General Settings | `target="_blank"` on Facebook links |
| `show_load_more` | SWITCHER | (set) | Content → General Settings | Renders Load More button |
| `loadmore_text` | TEXT | (set) | Content → General Settings | Button label |
| Style → Feed Item Styles | various | — | Style tab | Card background, border, shadow, spacing |
| Style → Feed Item Hover Styles | various | — | Style tab | Hover-state variants |
| Style → Color & Typography | various | — | Style tab | Text colour, typography for author / message / time |
| Style → Load More Button | various | — | Style tab | **Injected via `do_action('eael/controls/load_more_button_style', $this)`** |

## Conditional Dependencies

```text
eael_facebook_feed_image_render_type   → visible when eael_facebook_feed_force_square_img == 'yes'
eael_facebook_feed_image_dimension     → visible when eael_facebook_feed_force_square_img == 'yes'

# No eael_section_pro upsell — not present in this widget
```

The widget has notably few conditional dependencies — most controls show unconditionally.

## Hooks & Filters

| Hook | Type | Listener | Purpose |
| ---- | ---- | -------- | ------- |
| `wp_ajax_facebook_feed_load_more` / `wp_ajax_nopriv_facebook_feed_load_more` | action (consumed) | `Facebook_Feed::facebook_feed_render_items()` | Dedicated Load More AJAX endpoint (separate from shared `load_more`) |
| `eael/controls/load_more_button_style` | action (emitted, **internal**) | `Bootstrap::load_more_button_style()` in Lite | Injects shared Load More button style controls |

No widget-specific extension hooks for theme / Pro customisation. No `eael/pro_enabled` filter consumed — the widget has no Lite/Pro branching.

The Load More button style hook is the same Lite-internal code-reuse pattern documented in [`post-grid.md § Hooks & Filters`](post-grid.md#hooks--filters).

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-facebook-feed.default', FacebookFeed)`
- **Guard:** none — no `elementStatusCheck`
- **Reads on init:** `.eael-load-more-button` data attrs (`widget-id`, `post-id`, `page`); no settings-driven branches
- **Isotope init (frontend only, not editor):** `$('.eael-facebook-feed').isotope({ itemSelector: '.eael-facebook-feed-item', percentPosition: true, columnWidth: '.eael-facebook-feed-item' })` — followed by `imagesLoaded().progress(layout)`
- **Cross-widget reflow listeners:** subscribes to 4 EA-side custom actions (lightbox / advanced-tabs / advanced-accordion / toggle triggered) — when one of these widgets is opened, Facebook Feed re-runs `isotope('layout')` to fix masonry layout in previously-hidden containers
- **AJAX Load More payload:** `{ action: 'facebook_feed_load_more', security: nonce, page, post_id, widget_id }` — server returns `{ html, num_pages }` JSON
- **Response handling:** `$('.eael-facebook-feed').append($html); isotope('appended', $html); imagesLoaded().progress(layout)`; if `num_pages > page` increment, else remove button
- **Editor preview inline `<script>`:** emitted in render() — duplicate Isotope init for editor iframe. Variable name `$instagram_gallery` is a copy-paste leftover
- **Runtime state:** none persistent

## Common Issues

### "No Feed found" / empty grid renders

- **Likely cause:** Page ID or access token wrong/expired; OR Facebook account doesn't have permission to read public posts; OR the chosen source (`posts` vs `feed`) returns no items
- **Diagnose:** browser network tab for Graph API call — check the response status and body; check WP debug log for `wp_remote_get` failures
- **Fix:** regenerate the access token via the EA helper tool (link in the panel description); verify the Page ID is the numeric ID, not the page slug

### Avatar image doesn't load (broken image)

- **Likely cause:** the avatar URL uses Graph API `v4.0` ([trait line 117](../../includes/Traits/Facebook_Feed.php#L117)) which Facebook may have deprecated; OR the page has restricted profile picture access
- **Diagnose:** open the avatar URL directly in browser
- **Fix:** known limitation — `v4.0` URL should be updated to `v18.0`. Currently no user-facing fix

### Load More returns empty / fails

- **Likely cause:** nonce expired (page cached too long); OR `wp_ajax_nopriv_facebook_feed_load_more` not registered (plugin loading order issue); OR API rate limit
- **Diagnose:** browser network tab for `admin-ajax.php` — check response status and `error` field
- **Fix:** reload the page; check WP debug log for the AJAX handler; for rate limits, wait or increase cache TTL

### Masonry layout breaks after AJAX append

- **Likely cause:** `isotope('appended').isotope('layout')` ran before images loaded; OR new posts have different aspect ratios that change column widths
- **Diagnose:** browser DevTools — inspect `.eael-facebook-feed-item` computed widths
- **Fix:** `imagesLoaded().progress(layout)` should re-fire layout as images load; if persistent, hard refresh

### Widget inside hidden tab/accordion shows posts stacked vertically

- **Likely cause:** Isotope initialised when the container was `display: none` — width measurement failed; cross-widget reflow hooks should fix this but didn't fire
- **Diagnose:** check the tab/accordion is an EA widget (the reflow hooks only subscribe to EA-side custom actions); a non-EA tab won't fire `ea-advanced-tabs-triggered`
- **Fix:** for non-EA containers, manually trigger `$('.eael-facebook-feed').isotope('layout')` from theme JS when the container opens

### Access token visible in widget export

- **Likely cause:** by design — `eael_facebook_feed_access_token` is stored as widget settings, which Elementor saves as post meta and includes in template exports
- **Diagnose:** inspect the exported template JSON
- **Fix:** known limitation. Treat the token as sensitive; sanitise template exports manually before sharing

### Comments preview shows "No comments" even though the post has comments

- **Likely cause:** the access token may not have `read_engagement` or `pages_read_user_content` scopes — comments endpoint returns empty result for token without permissions
- **Diagnose:** check the token's permissions via Facebook's Graph API Explorer
- **Fix:** regenerate the token with required scopes via the EA helper tool

## Known Limitations

- **Graph API version inconsistency** — posts endpoint uses `v18.0` ([trait line 306-329](../../includes/Traits/Facebook_Feed.php#L306)); avatar URL hardcodes `v4.0` ([trait line 117](../../includes/Traits/Facebook_Feed.php#L117)). The `v4.0` URL works via Facebook's compatibility shim but could break at any time.
- **Editor preview JS variable named `$instagram_gallery`** ([line 1325 of Facebook_Feed.php](../../includes/Elements/Facebook_Feed.php#L1325)) — copy-paste artifact from Instagram_Feed widget. Functionally correct (the variable is local to the IIFE), but cosmetically wrong.
- **Access token field labeled "Secret Key"** ([line 115 of Facebook_Feed.php](../../includes/Elements/Facebook_Feed.php#L115)) — misleading; renaming would break saved widget data.
- **`wp_ajax_nopriv_` AJAX endpoint** — allowed for public-facing pages. Safe because nonce + server-side settings re-fetch prevents tampering, and the content (Facebook page posts) is already public. Worth flagging for security review.
- **No `eael_section_pro` upsell panel** — unusual for a feed widget; one of the few widgets without the standard upsell. May be intentional (no Pro features to upsell) but worth noting for consistency reviews.
- **No `elementStatusCheck` guard** — re-fired `frontend/init` re-runs Isotope init; the call is idempotent but wasteful.
- **Access token stored as widget post meta** — plaintext storage. Exporting / sharing the page exposes it. Same concern as Twitter Feed's bearer token.
- **`$instagram_gallery` variable name in editor preview script** — copy-paste from Instagram_Feed. Three feed widgets (Twitter, Facebook, Instagram) share clear lineage and may have other duplicated logic worth deduplicating.
- **Cross-widget reflow hooks only cover EA containers** — Facebook Feed inside a non-EA tab / accordion / modal won't re-layout automatically. Theme JS workaround required.
- **Graph API token-permission failures fail silently** — comments may not render, avatar may break, post text may be truncated; no user-facing error indicates the token lacks required scopes.
- **Helper tool URL `https://app.essential-addons.com/facebook/`** — third-party EA-provided tool for getting credentials. If that service is down, users cannot regenerate tokens without going directly to Facebook's developer portal.
