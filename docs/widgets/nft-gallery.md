# NFT Gallery Widget

> Fetches NFTs from OpenSea (v2 REST) or Magic Eden (mainnet v2) and renders them in a grid (2 presets) or list layout. Per-item card includes thumbnail, title, current price, last sale / ends-in, owner + creator with verified badges, chain icon (Solana/Ethereum), and "View Details" link. **Load-more pagination is client-side CSS reveal** — fetches `item_limit` rows upfront, hides extras with `eael-d-none`, click swaps classes per `page-N`.

**Class file:** [`includes/Elements/NFT_Gallery.php`](../../includes/Elements/NFT_Gallery.php)
**Slug:** `nft-gallery` (widget id `eael-nft-gallery`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-nft-gallery/>
**Pro-shared:** ❌ No — Lite-only widget. No `eael_section_pro` upsell registered. No `eael/pro_enabled` consumption. No `do_action`/`apply_filters` injection points. Pro doesn't reference this widget.

---

## Overview

Single render branch with two API source paths. OpenSea path hits `https://api.opensea.io/api/v2` with `X-API-KEY` header (key set per-widget, not site-wide); Magic Eden path hits `https://api-mainnet.magiceden.dev/v2` without auth (public endpoints). Both fetchers normalise responses into a `$response` array, cache via transient keyed by source + filters + widget id + item count, then loop into `print_nft_gallery_item_grid()` or `print_nft_gallery_item_list()` per layout setting. Per-item is hardcoded markup with currency conversion (`$item['current_price'] / $unit_convert`). Pagination is **classes-only client-side** — `page-N` data attribute on each item, `eael-d-none` default, click on Load More toggles to `eael-d-block`. **Zero extension hooks** — the only EA widget in Business/E-commerce with no `do_action`/`apply_filters` injection points.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| OpenSea source (v2 API, collections + assets) | ✅ — `assets` type commented out in SELECT at [line 112](../../includes/Elements/NFT_Gallery.php#L112), only `collections` exposed | ✅ |
| Magic Eden source (mainnet v2, collections + wallet) | ✅ | ✅ |
| Grid layout (preset-1, preset-2) + List layout | ✅ | ✅ |
| Per-card chain icon (Solana/Ethereum) | ✅ | ✅ |
| Owner / creator verified badge | ✅ | ✅ |
| Client-side reveal pagination | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none registered | — |
| Extension hooks (`do_action` / `apply_filters`) | ❌ — none emitted | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/NFT_Gallery.php`](../../includes/Elements/NFT_Gallery.php) | PHP widget class (~3,190 lines) — controls, two API fetchers in single `fetch_nft_gallery_from_api()`, `print_nft_gallery()` dispatcher, `print_nft_gallery_item_grid()` / `print_nft_gallery_item_list()` per-item renderers, `render_loadmore_button()` |
| [`src/css/view/nft-gallery.scss`](../../src/css/view/nft-gallery.scss) | Source styles (~378 lines) — grid columns, list flex layout, preset-1 vs preset-2 chrome, chain button, verified-badge positioning, load-more button |
| [`src/js/view/nft-gallery.js`](../../src/js/view/nft-gallery.js) | Frontend logic (27 lines, smallest JS in category) — single Load More click handler that toggles `eael-d-none` / `eael-d-block` on `.page-{N}` items |
| [`config.php`](../../config.php#L1275) entry `'nft-gallery'` | `Asset_Builder` deps: self CSS + self JS (no vendor libs) |

## Architecture

- **Two API source paths, same fetcher method** — `fetch_nft_gallery_from_api()` at [line 2895](../../includes/Elements/NFT_Gallery.php#L2895) has an if-elseif body switching on `$nft_gallery['source']`. OpenSea path requires `X-API-KEY` header (per-widget control, no site-wide option like Business_Reviews). Magic Eden path is unauthenticated — uses **public mainnet endpoints** (`/collections/{symbol}/listings` and `/wallets/{address}/tokens`) which work without keys but are rate-limited.
- **OpenSea response shape branching** — body is `$body->assets` (when type=assets), `$body->nfts` (when type=collections — note that OpenSea v2 returns NFTs under `nfts` key for collection endpoint), else the raw body. Conditional unwrap at [line 2998-3000](../../includes/Elements/NFT_Gallery.php#L2998).
- **OpenSea `assets` type is registered but commented out in the panel SELECT** ([line 112](../../includes/Elements/NFT_Gallery.php#L112)) — the codepath remains in `fetch_nft_gallery_from_api()` and `print_nft_gallery()` but the user can never select it from the panel. Settings deserialised from older widget instances saved with `assets` would still work; pristine new widgets only see `collections`.
- **Magic Eden type SELECT exposes `collections` + `wallet`** in panel; fetcher [line 3055](../../includes/Elements/NFT_Gallery.php#L3055) handles both. Wallet path uses `/wallets/{address}/tokens`; collections path uses `/collections/{symbol}/listings`.
- **Cache key includes widget id** so multiple instances of the widget on the same page don't share transients. Format: `{source}_{expiration}_{md5}_nftg_cache` where md5 hashes `(opensea_type + opensea_filterby + filterby_slug + filterby_wallet + item_limit + order + widget_id)` for OpenSea, `(magiceden_type + collection_symbol + wallet_address + item_limit + widget_id)` for Magic Eden. **TTL is panel-driven** in minutes (`eael_nft_gallery_opensea_data_cache_time` / `_magiceden_data_cache_time`, defaults `DAY_IN_SECONDS`).
- **Item limit is post-fetch slice** — both fetchers `array_splice($response, 0, absint($limit))` after JSON decode. The API request also sends `limit` param to the upstream so the splice is normally a no-op, but if a future API change returns more results, the splice clamps locally.
- **Pagination is pure CSS-class reveal, not AJAX** — `print_nft_gallery()` emits ALL items into the DOM, marking items past `posts_per_page` with `page-N` class + `eael-d-none`. The 27-line JS at [view/nft-gallery.js](../../src/js/view/nft-gallery.js) toggles classes on click; once `eael-last-nft-gallery-item` is reached, replaces button text with `nomore-item-text` and `fadeOut(1500)`. **No second API call, no `wp_ajax_*` endpoint** — entire pagination state is in DOM classes. Means initial page-load weight = full `item_limit` payload regardless of `posts_per_page`.
- **Currency conversion via `$unit_convert`** — Per-item `current_price` / `last_sale` divided by `$unit_convert` (defaults to 1, populated per-source). Used to convert wei → ETH (`1e18`) for OpenSea or lamports → SOL (`1e9`) for Magic Eden, depending on what each API returns.
- **Verified-badge SVG hardcoded inline** at [lines 2535, 2558](../../includes/Elements/NFT_Gallery.php#L2535) — the same 30×30 viewBox checkmark inside a sun-burst shield rendered twice (creator + owner). **The verified-badge link `<a href="{thumbnail url}">` wraps the badge with the thumbnail URL** as href, which is semantically wrong (verified-badge anchor should link to verification proof, not the creator's avatar). Likely copy-paste error.
- **Chain icon is wrong for Solana** — Magic Eden branch renders a Solana icon at [line 2477](../../includes/Elements/NFT_Gallery.php#L2477), but the SVG is the **hamburger-menu / equal-bars icon** (three horizontal lines), not the official Solana logo (gradient slanted parallelogram). Likely placeholder that was never updated.
- **`render()` is ~25 lines** — fetches via `fetch_nft_gallery_from_api()`, emits inline error message when empty, otherwise delegates to `print_nft_gallery()`. No `content_template()` stub — editor preview uses server-side render via AJAX. Each settings change in the editor triggers an API roundtrip — fast cache hits OK, but a re-saved widget with new filter forces fresh fetch (240s `wp_remote_get` timeout!).
- **`get_settings()` used in `print_nft_gallery()`** at [line 2689](../../includes/Elements/NFT_Gallery.php#L2689) — bypasses Elementor's display-time processing (dynamic tags, defaults). Most EA widgets use `get_settings_for_display()`. Cosmetic difference in this widget since values are simple text/select; could break if a future control uses dynamic tags.

## Render Output

```html
<div class="eael-nft-gallery-wrapper eael-nft-gallery-{widget-id} clearfix"
     data-posts-per-page="6"
     data-total-posts="9|12"                              ← source-specific limit (OpenSea 9, Magic Eden 12)
     data-nomore-item-text="No more items"
     data-next-page="2">

  [?] <!-- error path: rendered instead of items div when fetch fails -->
  <p class="eael-nft-gallery-error-message">Please insert a valid API Key for OpenSea</p>

  <div id="eael-nft-gallery-{widget-id}"
       class="eael-nft-gallery-items eael-nft-[grid|list] [preset-1|preset-2]">

    <!-- Grid item (preset-1 or preset-2) -->
    <div class="eael-nft-item [page-N] [eael-d-none] [eael-last-nft-gallery-item]">

      [?] <div class="eael-nft-chain">                    ← when show_chain=yes
        <button class="eael-nft-chain-button">
          <svg>Solana hamburger OR Ethereum diamond</svg>
        </button>
      </div>

      <div class="eael-nft-thumbnail">
        [?] <a href="{view_details_link}" target="_blank">    ← when thumbnail_clickable=yes AND preset-1
          <img src="{thumbnail}" alt="NFT Gallery">
        [?] </a>
      </div>

      <div class="eael-nft-main-content">
        <div class="eael-nft-content">
          [?] <h3 class="eael-nft-title">Bored Ape #1234</h3>

          [?] <div class="eael-nft-current-price-wrapper">
            <p class="eael-nft-current-price">{price / unit_convert} ETH</p>
          </div>

          [?] <!-- Creator (preset-1 grid only) -->
          <div class="eael-nft-creator-wrapper">
            <div class="eael-nft-creator-img">
              <img src="{creator_thumbnail}" alt="EA NFT Creator Thumbnail">
              [?] <a class="creator-verified-icon" href="{creator_thumbnail}"><svg>verified shield</svg></a>
            </div>
            <div class="eael-nft-created-by">
              <div><span>Creator</span></div>
              <div><a href="{created_by_link}">name</a></div>
            </div>
          </div>

          [?] <!-- Owner -->
          <div class="eael-nft-owner-wrapper">
            …same shape as creator with owned_by_*…
          </div>

          [?] <!-- Last Sale OR Ends In (mutually exclusive) -->
          <div class="eael-nft-last-sale-wrapper">
            [?] <p class="eael-nft-last-sale"><span>Last sale:</span> <span>{price} ETH</span></p>
            [?] <p class="eael-nft-ends-in"><span>Ends in:</span> <span>{time}</span></p>
          </div>
        </div>

        [?] <div class="eael-nft-button">
          <button class="eael-nft-gallery-button-align-{left|center|right|justify}">
            <a href="{view_details_link}" target="_blank">View Details</a>
          </button>
        </div>
      </div>

      [?] <!-- preset-2 full-card click overlay -->
      <a href="{view_details_link}" target="_blank"></a>
    </div>

    <!-- List item (alternative layout) -->
    <div class="eael-nft-item …">
      <div class="eael-nft-main-content">
        <div class="eael-nft-content eael-nft-grid-container">
          <div class="eael-nft-list-thumbnail eael-nft-grid-item">…</div>
          …per-row flex layout…
        </div>
      </div>
    </div>

  </div>

  [?] <!-- Load More button: rendered when pagination=yes AND total > posts_per_page -->
  <div class="eael-nft-gallery-loadmore-wrap">
    <a href="#" class="eael-nft-gallery-load-more elementor-button elementor-size-{size}">
      <span class="eael-btn-loader"></span>
      [?] <span class="eael-nft-gallery-load-more-icon nft-gallery-load-more-icon-[left|right] {icon-class}"></span>
      <span class="eael-nft-gallery-load-more-text">Load More</span>
    </a>
  </div>
</div>
```

Notes:

- `data-total-posts` reflects the source-specific item limit panel control (OpenSea uses `_opensea_item_limit`, Magic Eden uses `_magiceden_item_limit`), not the actual response count.
- `data-next-page` starts at 2 because page 1 is server-rendered visible by default; click increments and reveals `page-2`, `page-3`, etc.
- Verified-badge `href` points to the **thumbnail URL** (likely intent: link to verification page). Semantically misleading.
- Solana SVG is a hamburger-bars icon (three horizontal rectangles), not the official Solana brand mark.
- The verified-badge wrapping `<a>` opens in `target="_blank"` — opens a profile image as a top-level navigation, which is unusual.
- Empty `view_details_link` falls back to `#` in list layout ([line 2607](../../includes/Elements/NFT_Gallery.php#L2607)) but NOT in grid layout — grid emits `href=""` which behaves as link-to-current-page.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/NFT_Gallery.php#L62) — 13 main sections.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_nft_gallery_sources` | SELECT | `opensea` | Content → Query | `opensea` / `magiceden` |
| `eael_nft_gallery_source_key` | TEXT | empty | Content → Query | OpenSea X-API-KEY header (per-widget, not site-wide) |
| `eael_nft_gallery_opensea_type` | SELECT | `collections` | Content → Query (OpenSea) | `collections` — `assets` value commented out at [line 112](../../includes/Elements/NFT_Gallery.php#L112) |
| `eael_nft_gallery_opensea_filterby` | SELECT | `collection-slug` | Content → Query (OpenSea, type=assets only) | `collection-slug` / `wallet-address` — only relevant when `assets` selected (currently impossible from panel) |
| `eael_nft_gallery_opensea_filterby_slug` | TEXT | empty | Content → Query | OpenSea collection slug |
| `eael_nft_gallery_opensea_filterby_wallet` | TEXT | empty | Content → Query | OpenSea wallet address (label says "Collection Slug" — copy-paste typo at [line 158](../../includes/Elements/NFT_Gallery.php#L158)) |
| `eael_nft_gallery_opensea_order` | SELECT | `desc` | Content → Query | `asc` / `desc` (OpenSea `order_direction`) |
| `eael_nft_gallery_opensea_item_limit` | NUMBER | 9 | Content → Query | OpenSea max items returned (also drives post-fetch slice) |
| `eael_nft_gallery_opensea_data_cache_time` | NUMBER (min) | (DAY_IN_SECONDS) | Content → Query | OpenSea transient TTL |
| `eael_nft_gallery_magiceden_type` | SELECT | `collections` | Content → Query (Magic Eden) | `collections` / `wallet` |
| `eael_nft_gallery_magiceden_collection_symbol` | TEXT | empty | Content → Query | Magic Eden collection symbol |
| `eael_nft_gallery_magiceden_wallet_address` | TEXT | empty | Content → Query | Magic Eden wallet (Solana address) |
| `eael_nft_gallery_magiceden_item_limit` | NUMBER | 12 | Content → Query | Magic Eden max items |
| `eael_nft_gallery_magiceden_data_cache_time` | NUMBER (min) | (DAY_IN_SECONDS) | Content → Query | Magic Eden transient TTL |
| `eael_nft_gallery_items_layout` | SELECT | `grid` | Content → Layout | `grid` / `list` |
| `eael_nft_gallery_style_preset` | SELECT | `preset-1` | Content → Layout | Grid preset; list layout uses preset-1 only |
| `eael_nft_gallery_posts_per_page` | NUMBER | 6 | Content → Pagination | Items revealed per page-click |
| `eael_nft_gallery_pagination` | SWITCHER | empty | Content → Pagination | Enables Load More; emits no button when total <= per_page |
| `eael_nft_gallery_load_more_text` | TEXT | "Load More" | Content → Pagination | Button label |
| `eael_nft_gallery_nomore_items_text` | TEXT | (default) | Content → Pagination | Replacement text + `fadeOut(1500)` when exhausted |
| `eael_nft_gallery_load_more_icon` / `_load_more_icon_new` | FA4 + ICONS shim | (default) | Content → Pagination | Button icon; uses [_patterns.md § FA4 → FA5 icon migration shim](_patterns.md#fa4--fa5-icon-migration-shim) |
| `eael_nft_gallery_button_icon_position` | SELECT | `before` | Content → Pagination | Icon side |
| `eael_nft_gallery_button_size` | SELECT | (Elementor sizes) | Content → Pagination | Adds `elementor-size-{N}` class |
| `eael_nft_gallery_show_image` / `_image_clickable` | SWITCHER × 2 | empty | Content → Card | Thumbnail visibility + clickability (clickable only in preset-1) |
| `eael_nft_gallery_show_title` / `_show_owner` / `_show_creator` / `_show_current_price` / `_show_last_sale_ends_in` / `_show_button` / `_show_chain` | SWITCHER × 7 | various | Content → Card | Per-element visibility |
| `eael_nft_gallery_content_owned_by_label` / `_content_created_by_label` / `_content_view_details_label` / `_content_last_sale_label` / `_content_ends_in_label` | TEXT × 5 | "Owner" / "Owner" / "View Details" / "Last sale:" / "Ends in:" | Content → Card | Static labels |
| `eael_nft_gallery_button_alignment` | SELECT | — | Content → Card | `eael-nft-gallery-button-align-{value}` class |
| Style → various | — | — | Style tab | ~8 sections — gallery container / item / thumbnail / title / price / owner-creator / button / load-more |

## Conditional Dependencies

```text
eael_nft_gallery_source_key                  → visible when sources == 'opensea'
eael_nft_gallery_opensea_*                   → visible when sources == 'opensea'
eael_nft_gallery_opensea_filterby_slug       → visible when opensea_type == 'assets' AND filterby == 'collection-slug'
eael_nft_gallery_opensea_filterby_wallet     → visible when (opensea_type == 'assets' AND filterby == 'wallet-address') OR opensea_type == 'collections'
eael_nft_gallery_magiceden_*                 → visible when sources == 'magiceden'
eael_nft_gallery_magiceden_collection_symbol → visible when magiceden_type == 'collections'
eael_nft_gallery_magiceden_wallet_address    → visible when magiceden_type == 'wallet'
eael_nft_gallery_style_preset                → visible when items_layout == 'grid'
eael_nft_gallery_image_clickable             → effective only when preset == 'preset-1' (silent in preset-2/list)
eael_nft_gallery_load_more_icon* / _button_* → visible when pagination == 'yes'
Load More button HTML                        → emitted only when pagination == 'yes' AND items_count > posts_per_page
```

No `eael_section_pro` upsell conditional registered.

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides only.

This is the only widget in the Business/E-commerce category (and one of very few in Lite) with **zero `do_action` / `apply_filters` injection points**. To extend NFT_Gallery behaviour, third parties must subclass the widget or fork the file.

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on("elementor/frontend/init", …)` registering `elementorFrontend.hooks.addAction("frontend/element_ready/eael-nft-gallery.default", NFTGalleryHandler)`. Older pattern (not `eael.hooks.addAction("init", "ea", …)`).
- **Guard:** **None** — no `eael.elementStatusCheck` check. Re-renders in editor or re-binds via cross-widget events would double-register the click handler.
- **Vendor dependencies:** none — pure jQuery, ~27 lines.
- **Reads on init:** `data-posts-per-page`, `data-total-posts`, `data-nomore-item-text`, `data-next-page` from wrapper.
- **Branches:**
  - On click of `.eael-nft-gallery-load-more`: reveal `.eael-nft-item.page-{next_page}`, increment `data-next-page` attr + closure variable.
  - If revealed items include `.eael-last-nft-gallery-item`: swap button text to `nomore-item-text` and `fadeOut(1500)`.
- **Runtime state:** Closure variable `$next_page` (sync with `data-next-page` attr).
- **No custom events emitted or consumed.** No cross-widget reflow listeners (Adv_Tabs / Adv_Accordion will NOT re-init this widget on activation — items revealed before tab switch stay revealed, but layout calculations don't refresh).
- **No reads inside cards** — every per-item element is server-rendered with static markup; no per-item interactivity in JS.

## Common Issues

### Empty gallery with "Please insert a valid API Key for OpenSea"

- **Likely cause:** `eael_nft_gallery_source_key` empty. Or key invalid / rate-limited.
- **Diagnose:** Test the key directly: `curl -H "X-API-KEY: …" https://api.opensea.io/api/v2/collection/{slug}/nfts?limit=1`. If 401/403, key invalid; if 429, rate-limited.
- **Fix:** Get a new key from <https://docs.opensea.io/reference/api-keys>. Note that **the key is stored in the widget's saved settings**, not in `wp_options` — every Business Reviews widget needs its own copy (unlike Business_Reviews where the key is site-wide).

### Magic Eden gallery empty with "Please provide a valid collection symbol"

- **Likely cause:** Wrong collection symbol. Magic Eden symbols are different from OpenSea slugs.
- **Diagnose:** Visit `https://api-mainnet.magiceden.dev/v2/collections/{symbol}` directly in browser; valid symbols return JSON, invalid return 404.
- **Fix:** Use the symbol from the Magic Eden collection URL (e.g. `degods` not `de_gods`).

### List layout shows empty href "" on broken items

- **Likely cause:** Grid `print_nft_gallery_item_grid` doesn't fallback `view_details_link` to `#` ([compare to line 2607](../../includes/Elements/NFT_Gallery.php#L2607) where list does). Empty href in grid mode produces a link-to-current-page on broken items.
- **Diagnose:** Inspect — `<a href="">` instead of `<a href="https://opensea.io/...">`. Means upstream item had no permalink.
- **Fix:** Switch layout to List, or hook the widget's `print_nft_gallery_item_grid` via PHP override.

### Pagination broken after switching tabs / triggering Adv_Tabs

- **Likely cause:** Click handler binds once on `frontend/element_ready`. No cross-widget reflow listener; no `eael.hooks.addAction` registration; no guard. If the widget container is destroyed/recreated by the parent (rare in Elementor but possible), handler is lost.
- **Diagnose:** Hard-reload the page; pagination works. Switch tab away and back; pagination breaks.
- **Fix:** Document limitation. Workaround is to rebind on `eael-advanced-tabs-triggered` manually via custom JS.

### Stale cache after API key change

- **Likely cause:** API key is part of the OpenSea md5 hash (via implicit args composition), but Magic Eden cache key doesn't include API key (none exists for Magic Eden). For OpenSea, **the cache key hash actually doesn't include `api_key`** ([line 2924](../../includes/Elements/NFT_Gallery.php#L2924)) — recheck the md5 inputs: only `opensea_type + opensea_filterby + filterby_slug + filterby_wallet + item_limit + order + widget_id`. **Changing the API key without changing any filter input WILL hit the stale cache.**
- **Diagnose:** Save widget with old key, then update key, save again — still old data.
- **Fix:** Toggle a setting (e.g. flip and revert `posts_per_page`) to mutate the cache key, or `delete_transient('opensea_{exp}_{md5}_nftg_cache')` via WP-CLI.

## Known Limitations

- **OpenSea `assets` type commented out in panel SELECT** ([line 112](../../includes/Elements/NFT_Gallery.php#L112)) — codepath remains but unreachable from UI; existing widgets saved with `assets` continue to render. Slug/wallet filterby controls visibility depends on `assets` being selectable.
- **Pagination is CSS-reveal, not AJAX** — initial page load fetches ALL items (up to `item_limit`), hides extras with `eael-d-none`. Initial page weight = full `item_limit` payload + render markup regardless of `posts_per_page`. **Acceptable for typical 9–12-item galleries; problematic if `item_limit` is set higher.**
- **No `elementStatusCheck` guard in JS** — re-renders may double-bind the load-more click handler.
- **No cross-widget reflow listeners** — sitting inside an Adv_Tab / Adv_Accordion / Lightbox doesn't trigger reflow; pagination state persists across tab switches but no refresh on activation.
- **Verified-badge `<a href="{thumbnail url}">` is semantically wrong** ([line 2535](../../includes/Elements/NFT_Gallery.php#L2535)) — anchor points to the avatar image URL instead of a verification page.
- **Solana SVG icon is a hamburger-bars shape, not the Solana brand** ([line 2477](../../includes/Elements/NFT_Gallery.php#L2477)).
- **`eael_nft_gallery_opensea_filterby_wallet` control label reads "Collection Slug"** ([line 158](../../includes/Elements/NFT_Gallery.php#L158)) — copy-paste from the slug control above; misleading.
- **OpenSea cache key md5 inputs don't include `api_key`** ([line 2924](../../includes/Elements/NFT_Gallery.php#L2924)) — rotating the key without changing other filters hits stale cache.
- **Grid layout doesn't fallback `view_details_link` to `#`** — list layout does ([line 2607](../../includes/Elements/NFT_Gallery.php#L2607)). Inconsistent.
- **Empty creator/owner branch comment `// default creator svg`** at [line 2538](../../includes/Elements/NFT_Gallery.php#L2538) but no fallback SVG is rendered — items without creator/owner thumbnail show empty `.eael-nft-creator-img` div.
- **`render()` uses `get_settings()` in `print_nft_gallery()`** at [line 2689](../../includes/Elements/NFT_Gallery.php#L2689), bypassing dynamic-tag resolution. Cosmetic in current control set.
- **Zero extension hooks** — no `do_action` / `apply_filters` anywhere in the widget. Adding a new source (e.g. Rarible, Foundation) requires subclassing.
- **`wp_remote_get` timeout is 240s** ([lines 2986, 3071](../../includes/Elements/NFT_Gallery.php#L2986)) — extremely long; a slow API would block render for 4 minutes. Editor preview re-fires this on every settings change.
- **No `content_template()` stub** — editor preview goes through full server `render()` + API fetch on every settings change.
- **`eael-last-nft-gallery-item` class is referenced in JS but not actually added by PHP in this file** — `grep -n eael-last-nft-gallery-item Elements/NFT_Gallery.php` shows zero matches. JS branch at [view/nft-gallery.js line 13](../../src/js/view/nft-gallery.js#L13) checks for it but the trigger condition never fires — "no more items" text/fadeOut never executes. **The Load More button keeps working past the last page** as a result.
- **No `eael_section_pro` upsell** — the only Business/E-commerce widget without one.
- **No CSP friendliness** — inline SVGs are fine but verified-badge SVG uses gradient classes (`sc-9c65691d-1 jiZrqV`) referencing third-party styled-components hashes that don't exist in EA's CSS; the gradient renders fallback fill only.
