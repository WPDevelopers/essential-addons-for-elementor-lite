# Load More & Pagination

How click-to-load-more, scroll-triggered infinite scroll, and AJAX-paginated WooCommerce flows actually work — the JS click handler that gathers state from `data-*` attributes, the AJAX request shape, the server-side re-derivation of `WP_Query` args from saved widget settings, and the DOM update path that handles isotope / masonry layouts.

If you've ever wondered "where does `data-page` come from?" or "why does load-more on a `rand` ordered grid show duplicates?" — this is the doc.

## Overview

The plugin ships one shared frontend script — [`src/js/view/load-more.js`](../../../src/js/view/load-more.js) — that powers the "Load More" button and the "Infinite Scroll" variant for almost every list widget: Post Grid, Post Timeline, Product Grid, Woo Product Gallery / List, plus Pro's Dynamic Filterable Gallery and Post Block. The script is dumb on purpose:

1. On click (or viewport-enter for infinite scroll), it reads `data-*` attributes off the rendered widget.
2. POSTs them to `wp-admin/admin-ajax.php` with action `load_more`.
3. The server-side handler [`ajax_load_more`](../../../includes/Traits/Ajax_Handler.php#L84) **re-derives** the `WP_Query` args from saved widget settings — it does not trust the `args` payload as authority for visibility.
4. Returns concatenated HTML.
5. JS appends the response to the widget's `.eael-post-appender` (or class-specific equivalent), re-runs isotope/masonry layout if needed, and updates the page counter on the button.

The contract: **page 2..N is constructed from the widget's saved settings, identically to page 1**, with `paged` (or `offset`) added. This guarantees order, taxonomy filters, exclusions, and visibility match.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`src/js/view/load-more.js`](../../../src/js/view/load-more.js) | 281 | The click handler, infinite-scroll listener, response router (per-widget-class branches), isotope re-layout |
| [`includes/Traits/Ajax_Handler::ajax_load_more`](../../../includes/Traits/Ajax_Handler.php#L84) | ~440 (within trait) | Server handler — security triad, settings retrieval, args re-build, per-class compat, template render |
| `Helper::eael_get_widget_settings( $page_id, $widget_id )` | — | Walks `Plugin::$instance->documents->get($page_id)->get_elements_data()` to find the widget by id and return its saved settings |
| [`Helper::get_query_args`](../../../includes/Classes/Helper.php#L179) | ~95 | Same shared query builder used by first-render — see [`wp-query-construction.md`](wp-query-construction.md) |
| `assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js` | vendor | Masonry layout engine — re-run on append for grids configured `layout=masonry` |
| `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` | vendor | Triggers isotope layout pass once images settle |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ INITIAL RENDER (page 1, server-side)                             ║
║                                                                  ║
║   Widget render() outputs:                                       ║
║     <div class="eael-grid-post-holder">                          ║
║       <div class="eael-post-appender">                           ║
║         …N posts…                                                ║
║       </div>                                                     ║
║       <button class="eael-load-more-button"                      ║
║               data-widget="abc1"                                 ║
║               data-page-id="42"                                  ║
║               data-class="…\Elements\Post_Grid"                  ║
║               data-args="post_type=post&category_ids[]=5&…"      ║
║               data-page="1"                                      ║
║               data-max-page="5"                                  ║
║               data-layout="masonry"                              ║
║               data-template="…template-info…">                   ║
║         <span class="eael_load_more_text">Load More</span>       ║
║       </button>                                                  ║
║     </div>                                                       ║
╚══════════════════════════════════════════════════════════════════╝
                          │
                          ▼ user clicks (or scroll for infinite)
╔══════════════════════════════════════════════════════════════════╗
║ JS HANDLER (load-more.js)                                        ║
║                                                                  ║
║   1. Read all data-* attrs into JS vars                          ║
║   2. Increment page: $page = parseInt(data-page) + 1             ║
║   3. Per-class fixups:                                           ║
║      • Woo_Product_Gallery → read active taxonomy tab            ║
║      • Dynamic_Filterable_Gallery → collect exclude_ids,         ║
║        active_term_id, active_taxonomy                           ║
║      • orderby=rand → collect printed post ids → post__not_in    ║
║   4. Disable button + show loading text                          ║
║   5. $.ajax POST to admin-ajax.php with composed body            ║
╚══════════════════════════════════════════════════════════════════╝
                          │
                          ▼
╔══════════════════════════════════════════════════════════════════╗
║ SERVER HANDLER (Ajax_Handler::ajax_load_more)                    ║
║                                                                  ║
║   1. Security triad: nonce + sanitize $_POST['args']             ║
║      • Force post_status=publish                                 ║
║      • eael_sanitize_relation on date_query                      ║
║   2. Validate $_POST['page_id'] and $_POST['widget_id']          ║
║   3. $settings = eael_get_widget_settings($page_id, $widget_id)  ║
║   4. Compute paged offset:                                       ║
║      $args['offset'] += ($page - 1) * $args['posts_per_page']    ║
║   5. Per-class fixups:                                           ║
║      • Woo_Product_Gallery → tax_query from $_REQUEST['taxonomy']║
║      • Post_Grid + rand → merge $_REQUEST['post__not_in']        ║
║      • Product_Grid → do_action('eael_woo_before_product_loop')  ║
║      • Woo_Product_List → do_action('eael/woo-product-list/…')   ║
║      • Dynamic_Filterable_Gallery → ACF + taxonomy hybrid query  ║
║   6. ob_start, render template, ob_get_clean → $html             ║
║   7. wp_send_json_success([ html, numberPosts, class, args ])    ║
║      (some flows return raw HTML directly)                       ║
╚══════════════════════════════════════════════════════════════════╝
                          │
                          ▼
╔══════════════════════════════════════════════════════════════════╗
║ JS RESPONSE HANDLER                                              ║
║                                                                  ║
║   1. If response empty / has no-posts-found class:               ║
║      • Most widgets → remove() the button                        ║
║      • Woo_Product_Gallery → hide-load-more class + handle       ║
║        infinite-scroll wrapper                                   ║
║      • Dynamic_Filterable_Gallery → mark active filter           ║
║        no-more-posts                                             ║
║   2. Otherwise, branch on data-class:                            ║
║      • Product_Grid → filter li's, append to .products,          ║
║        re-run isotope + imagesLoaded if masonry, init wc gallery ║
║      • Other widgets → append to .eael-post-appender             ║
║        Re-run isotope if data-layout=masonry or has_filter       ║
║   3. Update button data-page = $page                             ║
║   4. If $page >= $max_page → remove() the button                 ║
╚══════════════════════════════════════════════════════════════════╝
                          │
                          ▼
                   DOM updated, layout settled
```

## Hook Timing

Load-more does not introduce its own page-load hooks. The relevant hooks fire inside `ajax_load_more` for compat shims and class-specific setup:

| Hook | When | Purpose |
| ---- | ---- | ------- |
| `eael_before_ajax_load_more` (action) | Top of `ajax_load_more`, before sanitisation | Compat shims (e.g. YITH wishlist disable for AJAX context) |
| `eael_after_ajax_load_more` (action) | After response composition (where present) | External systems hook for analytics / cache warm |
| `eael_woo_before_product_loop` (action) | Inside handler, when class is Product_Grid | Triggers WC product-loop setup needed for the rendered preset |
| `eael/woo-product-list/before-product-loop` (action) | Inside handler, when class is Woo_Product_List | WC list-style setup |
| `eael_load_more_args` (filter) | Inside `ajax_load_more`, just before `WP_Query` | Last chance to mutate args before SQL — see [`ajax-endpoints.md § Filters`](ajax-endpoints.md) |

## Data Flow

End-to-end click on a Post Grid load-more button:

1. **Initial render emits the button.** Server-side, `Post_Grid::render()` includes the `.eael-load-more-button` with all required `data-*` attrs. `data-args` is a query-string-serialised version of the widget's settings (post_type, taxonomy ids, orderby, posts_per_page, offset, etc.).
2. **User clicks.** `load-more.js` click handler ([line 6](../../../src/js/view/load-more.js#L6)) fires.
3. **JS reads state.** `$widget_id`, `$page_id`, `$class`, `$args`, `$page` (current+1), `$max_page`, `$layout`, `$template_info`, `$nonce` (from `localize.nonce`).
4. **JS does class-specific fixups.** For Post_Grid with `orderby=rand`, the script walks every existing `.eael-grid-post`, collects their `data-id` ids, and adds `post__not_in: $ids` to the request — preventing duplicate posts on subsequent pages of a random query.
5. **JS POSTs to admin-ajax.php** with body `action=load_more`, `class=…\Post_Grid`, `args=<query-string>`, `page=2`, `page_id=42`, `widget_id=abc1`, `nonce=…`, `template_info=…`, plus class-specific extras.
6. **Server handler runs.** [`ajax_load_more`](../../../includes/Traits/Ajax_Handler.php#L84) does the security triad, then `wp_parse_str( $_POST['args'], $args )` and forces `post_status=publish`.
7. **Server retrieves saved settings.** `HelperClass::eael_get_widget_settings( $page_id, $widget_id )` walks `Plugin::$instance->documents->get($page_id)->get_elements_data()` to find the widget by id. If not found, returns error.
8. **Server computes paged offset.** [`Ajax_Handler:148`](../../../includes/Traits/Ajax_Handler.php#L148): `$args['offset'] = (int) $args['offset'] + ((int) $page - 1) * (int) $args['posts_per_page']`.
9. **Server applies Post_Grid + rand exclusion.** When orderby is `rand` and `$_REQUEST['post__not_in']` is non-empty, merge it into `$args['post__not_in']`, deduplicate, intval-coerce, and unset offset (random + offset doesn't make sense).
10. **Server runs `WP_Query`.** Uses `Helper::get_query_args` to rebuild a clean baseline, then applies the paged offset and merged exclusions on top.
11. **Server renders template.** `ob_start`, loops the WP_Query, includes the per-post template (the same one Post_Grid uses for first-render), `ob_get_clean` to capture HTML.
12. **Server responds.** `wp_send_json_success([ 'html' => $html, 'numberPosts' => $count, 'class' => $class, 'args' => $args ])` (older Post Grid path emits raw HTML — newer paths use the structured response).
13. **JS handles response.** [Line 141](../../../src/js/view/load-more.js#L141): `var $content = $(response)`. Branch on whether content is empty or has `no-posts-found` class.
14. **JS appends content.** For Post_Grid (default branch at [line 213](../../../src/js/view/load-more.js#L213)): `$('.eael-post-appender', $scope).append($content)`. If `$layout == 'masonry'` or `settings.has_filter` is true, re-run isotope layout on the post-appender, then `imagesLoaded` to re-trigger after image dimensions resolve.
15. **JS updates state.** [Line 237](../../../src/js/view/load-more.js#L237): `$this.data("page", $page)` updates the button's page counter for the next click.
16. **JS removes button if final page reached.** [Line 251](../../../src/js/view/load-more.js#L251): `if ($max_page && $data.page >= $max_page) { $this.remove() }`.

## Infinite Scroll Variant

The same load-more.js powers infinite scroll when the widget is wrapped in `.eael-infinity-scroll`. A separate handler ([line 262](../../../src/js/view/load-more.js#L262)) listens to `window.scroll`, computes whether each `.eael-infinity-scroll` element is in view (with an offset), and triggers a click on its `.eael-load-more-button` when visible.

This means the button always exists in the DOM — infinite scroll just hides the click target visually and triggers programmatically. The same AJAX flow runs.

## Per-Widget-Class Branches

Both JS and PHP branch on `data-class` (the widget's PHP class FQN). This is the contract that lets one shared script power different widgets with different DOM shapes:

| Class | JS difference | PHP difference |
| ----- | ------------- | -------------- |
| `\…\Elements\Post_Grid` | Default branch — appends to `.eael-post-appender`, re-runs isotope if masonry / has_filter | Read more button transient, rand-orderby exclusion |
| `\…\Elements\Post_Timeline` | Same default branch | Excerpt expander indicator compat |
| `\…\Elements\Product_Grid` | Filters `li` from response, appends to `.eael-product-grid .products`, masonry isotope, re-init `.wc_product_gallery()` for any new items | Fires `eael_woo_before_product_loop` |
| `\…\Elements\Woo_Product_List` | Default-ish branch | Fires `eael/woo-product-list/before-product-loop` |
| `\…\Elements\Woo_Product_Gallery` | Reads active category-tab data, paginates per-tab via `data("page")` on the tab; on empty response, hides load-more and removes infinite-scroll wrapper | tax_query rebuilt from `$_REQUEST['taxonomy']`, sanitized via `sanitize_taxonomy_data` |
| `\…\Pro\Elements\Dynamic_Filterable_Gallery` | Collects `data-itemid` from already-rendered items into `exclude_ids`, sends `active_term_id` + `active_taxonomy`. Page flag is fixed to 1 since exclusion drives pagination. Filter-button "no more posts" UI on empty | Hybrid ACF + taxonomy query, attachment taxonomy map computed, post__in / post__not_in juggling |
| `\…\Pro\Elements\Post_Block` | Default branch | Old → new control-name compat (e.g. `eael_post_block_hover_animation` → `post_block_hover_animation`), FA4-migrated icon resolution |

## Configuration & Extension Points

### Filters

| Filter | Purpose |
| ------ | ------- |
| `eael_load_more_args` | Last chance to mutate `$args` before `WP_Query` runs in `ajax_load_more` |
| `eael_pagination_link` | Customise pagination link output (used by WC pagination handlers) |

### Actions

| Action | Purpose |
| ------ | ------- |
| `eael_before_ajax_load_more` | Compat shims at handler entry |
| `eael_after_ajax_load_more` | Post-handler hooks for external systems |
| `eael_woo_before_product_loop` | WC loop setup for Product_Grid responses |
| `eael/woo-product-list/before-product-loop` | WC loop setup for Woo_Product_List responses |

### Adding load-more support to a new list widget — checklist

1. **Initial render** must include `.eael-load-more-button` with all required `data-*` attrs. Reference Post_Grid's render method for the canonical attribute set.
2. **Container** for new posts must be `.eael-post-appender` (or class-specific selector if branching in JS — but reusing the default keeps things simple).
3. **`data-class` must match** the widget's PHP FQN exactly. JS and PHP branch on it.
4. **Server handler** does not need new code if the widget uses the standard `Helper::get_query_args` path. If the widget needs custom args mutation, add a class-specific block to `ajax_load_more` keyed on `$class`.
5. **Per-post template** at `includes/Template/{Widget-Name}/` (managed by `Template_Query` trait) — `ajax_load_more` includes it via the trait's lookup.
6. **Test** — Playwright spec that loads page, clicks load-more, asserts new posts appear. See [`testing.md`](../../../.claude/rules/testing.md).

## Common Pitfalls

### Random orderby duplicates without `post__not_in`

WP_Query `orderby=rand` is stateless. Without `post__not_in`, the same post can appear on page 1, page 2, and page 3 of "load more". The JS handler ([line 103](../../../src/js/view/load-more.js#L103)) walks visible `.eael-grid-post` elements and sends their ids to exclude. If your widget's per-post markup doesn't carry `data-id`, this collection fails silently and duplicates appear. Confirm the per-post template emits `data-id="<post id>"`.

### Masonry layout collapse on append

After appending content, isotope needs both `appended` and `layout` calls. The JS handler does this, then chains `imagesLoaded` for image-driven layout shifts. If you skip the `imagesLoaded` step, masonry layouts settle on intrinsic dimensions and shift visually when images load. The handler's defensive double-trigger ([line 225](../../../src/js/view/load-more.js#L225)) is intentional.

### `data-args` shape vs `Helper::get_query_args` shape

The `data-args` attribute is a query-string-serialised view of the widget's saved settings — but the server discards most of it after `wp_parse_str` and re-derives args via `Helper::get_query_args` against the saved settings (retrieved by widget id). So mutating `data-args` client-side does almost nothing for visibility — which is intentional security. It does carry a few caller-controlled fields like `tax_query.relation` and `date_query` shape that the handler honours after sanitisation.

### `$max_page` mismatch

The button's `data-max-page` is computed at first render based on `WP_Query->max_num_pages`. If the underlying data set changes between first render and a subsequent click (a post is published, a category is moved), the max_page can be stale. Most widgets accept this; the load-more button gets removed when `$page >= $max_page` regardless of whether more posts actually exist.

### Settings not found on cloned widgets

If a widget is duplicated within the same page, both copies have the same widget id — `eael_get_widget_settings` returns the first match's settings. The second widget's load-more pulls posts using the wrong widget's filters. Most reports of "load-more works for one widget on the page but not others" trace here.

### Infinite scroll firing during initial page paint

The scroll listener triggers on every `window.scroll` event. On pages where the infinite-scroll element is already in viewport at initial paint, the listener may fire before the page is fully settled, causing a load-more request before the user expects. The handler relies on `$('.eael-load-more-button', scrollElement)` existing — which it does after server render — so the request does work, but the UX is "more posts appeared before I scrolled".

### `wp_send_json` exits — class-specific blocks must complete before responding

Inside `ajax_load_more`, several class-specific code blocks run between settings retrieval and template render. If a future change adds a `wp_send_json_*` call inside one of these blocks, all subsequent class-specific compat code is dead. Order matters; keep `wp_send_json_*` only at clean exit points.

### Dynamic Filterable Gallery's hybrid query is fragile

The Pro Dynamic_Filterable_Gallery branch ([Ajax_Handler:212](../../../includes/Traits/Ajax_Handler.php#L212)) computes a hybrid ACF-image + standard-post query map. It depends on multiple flags (`fetch_acf_image`, `eael_dfg_enable_combined_query`, `fetch_acf_image_gallery`) being mutually consistent. When debugging, log all three plus the resulting `taxonomy_map` to confirm the branch took the expected path.

## Debugging Guide

When load-more "isn't working":

1. **Confirm initial render emitted the button.** View page source — is `.eael-load-more-button` present with all `data-*` attrs?
2. **Confirm JS click handler is bound.** In browser console, click the button manually; the click handler should fire (set a breakpoint at [load-more.js:6](../../../src/js/view/load-more.js#L6)).
3. **Inspect the AJAX request.** Network tab → check the body — `action=load_more`, all data-attrs included, nonce present?
4. **Check the response.** 200 with `{ success: false }`? Read `data` for the error message. 200 with raw HTML? That's the older format — frontend handles both.
5. **For "no posts found" on page 2 unexpectedly:** the offset calculation is `($page - 1) * $posts_per_page`. If page 1 actually had fewer posts than `$posts_per_page` (rare edge case with custom orderby), page 2 starts past the available posts.
6. **For appended content but no layout:** check the masonry / isotope chain — open console, look for isotope errors. `data-layout=masonry` triggers the masonry branch; missing this attribute means default append without re-layout.
7. **For Woo_Product_Gallery tabs not paginating:** the per-tab `data-page` lives on the active `.eael-cat-tab li a.active` element, not the load-more button. Inspect the active tab's data attrs.
8. **For random orderby duplicates:** confirm rendered posts have `data-id` attribute.

## Worked Example — Post Grid Load More with Random Orderby

Most-revealing case because it exercises the JS exclusion logic and the PHP merge path:

1. **Page 1 renders.** Post_Grid with `orderby=rand`, `posts_per_page=4`. WP_Query returns 4 random posts. Each has `data-id` on its element. Button has `data-page=1`, `data-args="...&orderby=rand"`, `data-max-page=10`.
2. **User clicks load-more.** JS handler reads attrs. Detects `obj.orderby == "rand"` ([load-more.js:103](../../../src/js/view/load-more.js#L103)). Walks `.eael-grid-post`, collects 4 post ids, sets `$data.post__not_in = [12, 34, 56, 78]`. Sends POST.
3. **Server handler.** Security triad passes. Settings retrieved. `wp_parse_str` produces `$args` with `orderby=rand, posts_per_page=4, post__not_in=[]`. Forces `post_status=publish`.
4. **Per-class branch fires.** [Ajax_Handler:160](../../../includes/Traits/Ajax_Handler.php#L160) — class is Post_Grid, orderby is rand, `$_REQUEST['post__not_in']` is `[12, 34, 56, 78]`. Merge with existing `$args['post__not_in']` (empty), intval-coerce, dedupe, **unset `$args['offset']`** (random + offset is incoherent). Result: `$args['post__not_in'] = [12, 34, 56, 78]`.
5. **WP_Query runs.** `posts_per_page=4`, random order, excluding the 4 already-shown. Returns 4 new random posts (assuming enough exist).
6. **Server renders template, returns HTML.**
7. **JS appends.** `.eael-post-appender` now has 8 posts. Button `data-page=2`. Random across pages, no duplicates.
8. **User clicks again.** JS re-collects from now-8 elements: `[12, 34, 56, 78, 91, 102, 113, 124]`. Sends. Server excludes all 8. Returns next 4. And so on, until WP_Query returns zero rows or `$page >= $max_page`.

Without the JS-collected `post__not_in`, every page would draw from the full random set and produce visible duplicates within ~3 pages — a real-world bug class avoided by this design.

## Architecture Decisions

### Server re-derives args from saved settings; doesn't trust `data-args` for visibility

- **Context:** Trusting client-supplied `args` was the visibility-leak class — see [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md). For load-more specifically, trusting client args would let any visitor query arbitrary post types and visibilities.
- **Decision:** Pass `widget_id` + `page_id`, fetch saved settings server-side, run them through `Helper::get_query_args` again. The client `args` blob is used only for non-security-sensitive fields like `date_query.relation` and a few class-specific flags.
- **Alternatives rejected:** Trust client args (security hole); keep state in a server-side session (complexity); transient-keyed args (sync nightmare).
- **Consequences:** Load-more page 2..N is reproducible and consistent. Cost is one extra DB read per click (the `_elementor_data` post meta walk).

### One shared JS handler with per-class branches instead of per-widget scripts

- **Context:** Six+ widgets need load-more, with subtle DOM differences (Post Grid uses `.eael-post-appender`, Product_Grid uses `.products`, Woo Product Gallery has tabs).
- **Decision:** One `load-more.js` with class-keyed branches for the differences. The branch key is the widget's PHP FQN sent as `data-class`.
- **Alternatives rejected:** Per-widget JS files (duplicated request logic, drift); generic handler with no widget knowledge (each widget would have to layer its own append-and-layout code).
- **Consequences:** `load-more.js` grows whenever a new widget needs custom append behaviour. Trade-off accepted because the central handler keeps request semantics consistent across widgets.

### Random orderby state lives in the JS, not the server

- **Context:** WP_Query `orderby=rand` is stateless. Pagination via `paged` doesn't help because each page is a fresh random draw.
- **Decision:** JS collects already-rendered post ids on click and sends them as `post__not_in`. Server merges with widget settings' `post__not_in` and excludes both.
- **Alternatives rejected:** Server-side seed-based random (changes WP_Query SQL semantics; many MySQL versions don't seed RAND deterministically); session-based seen-list (session cost for guest browsing).
- **Consequences:** Random load-more works without duplicates. The `post__not_in` array grows linearly with pages clicked — at some point (~hundreds of posts) the SQL clause becomes large enough to be slow. Acceptable for typical use; not for archives with thousands of items.

### Infinite scroll uses the same button click programmatically

- **Context:** Two UX modes (button click vs scroll-driven) sharing one AJAX flow.
- **Decision:** Always render the button. Infinite scroll wraps it in `.eael-infinity-scroll` and uses a scroll listener to trigger the click programmatically. The button remains in the DOM, just visually styled differently.
- **Alternatives rejected:** Separate scroll-only handler (duplicate logic); no button (loses keyboard-accessibility fallback).
- **Consequences:** One code path; same accessibility surface; can switch UI between modes by toggling the wrapper class. The scroll handler does fire eagerly during initial paint if the element starts visible.

## Known Limitations

- **Cloned-widget id collision** — duplicate widget instances on the same page share the widget id; `eael_get_widget_settings` returns the first match's settings.
- **`max_page` staleness** — value is computed at first render and doesn't update if the underlying data changes mid-session.
- **`post__not_in` array growth** — random orderby with many pages produces an ever-growing exclusion list, eventually slowing SQL.
- **Infinite scroll fires during initial paint** — when the element starts in viewport, the first AJAX request can fire before the user has scrolled.
- **Dynamic Filterable Gallery hybrid path is brittle** — depends on multiple consistent flags; failures are hard to debug without instrumented logs.
- **`data-class` is a string-typed contract** — typos or namespace renames break the class branches silently. No compile-time check.
- **Older response format vs newer JSON shape** — some widgets return raw HTML; others return `{ success, data: { html, ... } }`. JS handles both, but the inconsistency complicates response inspection.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — folder index and the five dynamic-data flows.
- **Architecture:** [`./ajax-endpoints.md`](ajax-endpoints.md) — the `load_more` action's full inventory entry and security posture.
- **Architecture:** [`./wp-query-construction.md`](wp-query-construction.md) — `Helper::get_query_args` is the shared builder both first-render and load-more depend on.
- **Architecture:** [`../asset-loading.md`](../asset-loading.md) — how `load-more.min.js` and the isotope / imagesloaded vendor libs reach the page.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — the AJAX trace path lands in this doc when a load-more endpoint behaves unexpectedly.
- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — explains why server-side args re-derivation is the secure pattern.
- **Widget docs:** [`../../widgets/`](../../widgets/) — per-widget docs reference this doc when they describe their load-more / infinite scroll behaviour.
