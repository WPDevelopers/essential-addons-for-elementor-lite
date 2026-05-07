# WP_Query Construction

Almost every EA widget that lists posts (Post Grid, Post Timeline, Content Ticker, Adv Accordion, Product Grid, Product List, Product Gallery, Woo widgets…) goes through one shared helper to build its `WP_Query` arguments. Centralising the build means every list widget shares the same defaults, taxonomy logic, sticky-post handling, custom-meta sorting, and author filtering.

This doc covers that helper (`Helper::get_query_args`), the related sanitisation helpers, and how AJAX endpoints reuse the same construction path so load-more results match the original render exactly.

## Overview

EA list widgets do not hand-roll `WP_Query` args from settings. They go through [`Helper::get_query_args( $settings, $post_type = 'post' )`](../../../includes/Classes/Helper.php#L179) — a 95-line static method that:

- Fills in sensible defaults (orderby `date`, order `desc`, posts_per_page 3, offset 0)
- Forces `post_status = 'publish'` and `ignore_sticky_posts = 1`
- Resolves the `'by_id'` post-type pseudo-value to a `post__in`-driven `'any'` query
- Builds `tax_query` from per-taxonomy `{taxonomy_name}_ids` settings
- Maps EA-specific orderby values (`most_viewed`, `meta_value` with type) to WP_Query semantics
- Honours `posts_by_current_user`, `authors`, `post__not_in` filters
- Adds Whols compatibility for `product` post type

AJAX load-more handlers re-run the same widget's saved settings through the same helper, so the second page of results matches the first by construction — no separate "load-more args" path.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Classes/Helper::get_query_args`](../../../includes/Classes/Helper.php#L179) | ~95 (within Helper.php) | The canonical query builder — used by 8+ list widgets |
| [`includes/Classes/Helper::eael_sanitize_relation`](../../../includes/Classes/Helper.php#L1855) | ~7 | Coerces a relation operator to `'AND'` or `'OR'` only |
| [`includes/Traits/Helper::eael_query_controls`](../../../includes/Traits/Helper.php#L65) | — | Registers the standard EA query controls in `register_controls()` (post type, taxonomies, posts-per-page, offset, orderby) — produces the settings shape that `get_query_args` consumes |
| [`includes/Traits/Template_Query`](../../../includes/Traits/Template_Query.php) | 296 | **Not** query construction — handles widget template file selection (lite/pro/theme directory lookup). Listed here so the names don't get confused. |
| Widget callers | — | [Post_Grid:1780](../../../includes/Elements/Post_Grid.php#L1780), [Post_Timeline:751](../../../includes/Elements/Post_Timeline.php#L751), [Content_Ticker:727](../../../includes/Elements/Content_Ticker.php#L727), [Adv_Accordion:1870](../../../includes/Elements/Adv_Accordion.php#L1870), [Product_Grid:3642](../../../includes/Elements/Product_Grid.php#L3642), [Woo_Product_Gallery:2840](../../../includes/Elements/Woo_Product_Gallery.php#L2840), [Woo_Product_List:4047](../../../includes/Elements/Woo_Product_List.php#L4047) |

## Architecture Diagram

```text
╔═══════════════════════════════════════════════════════════════════╗
║ FIRST RENDER (server-side, on page load)                          ║
║                                                                   ║
║   Widget render() reads $settings via get_settings_for_display()  ║
║       │                                                           ║
║       ▼                                                           ║
║   $args = Helper::get_query_args( $settings, $post_type )         ║
║       │  applies defaults                                         ║
║       │  forces publish + no-sticky                               ║
║       │  branches on by_id vs taxonomy-based                      ║
║       │  builds tax_query                                         ║
║       │  applies meta_value / most_viewed orderby logic           ║
║       │  applies author / posts_by_current_user / not_in          ║
║       ▼                                                           ║
║   $query = new WP_Query( $args )                                  ║
║       │                                                           ║
║       ▼                                                           ║
║   while ( $query->have_posts() ) → render template / output HTML  ║
╚═══════════════════════════════════════════════════════════════════╝

╔═══════════════════════════════════════════════════════════════════╗
║ LOAD MORE (AJAX, on user click / scroll)                          ║
║                                                                   ║
║   ajax_load_more handler                                          ║
║       │  retrieves widget settings via                            ║
║       │    Plugin::$instance->documents->get(...)                 ║
║       │  re-runs Helper::get_query_args( $settings, $post_type )  ║
║       │  adds 'paged' = $_POST['page']                            ║
║       │  re-applies eael_sanitize_relation on date_query relation ║
║       ▼                                                           ║
║   Same WP_Query → same template render → wp_send_json_success     ║
╚═══════════════════════════════════════════════════════════════════╝
```

The contract: same widget settings → identical `$args` → identical loop. Load-more pages 2..N match page 1 because the construction path is shared, not duplicated.

## Hook Timing

Query construction is a synchronous helper call inside `render()` or AJAX handlers. No EA-specific hooks fire during construction itself, but the resulting `WP_Query` participates in WP / WC's standard query filter chain:

| Hook | Owner | Effect |
| ---- | ----- | ------ |
| `pre_get_posts` (action) | WP core | Lets plugins / themes alter `WP_Query` before SQL runs — fires for every EA query that uses `new WP_Query` |
| `posts_clauses` (filter) | WP core | Same purpose at the SQL-clauses level |
| `woocommerce_product_query_meta_query` (filter) | WC core | Applied for `'product'` post type when Whols Lite is active ([Helper:266](../../../includes/Classes/Helper.php#L266)) |

EA does not fire its own hooks during construction. If you need to mutate args before `WP_Query`, the cleanest paths are: (a) own filter on the widget's `$settings` before `get_query_args`, or (b) `pre_get_posts` action with a widget-aware predicate.

## Data Flow

End-to-end on a Post Grid widget configured to show 6 posts of post type `post` filtered by category id 5, ordered by date desc:

1. **`render()` is called.** `$settings = $this->get_settings_for_display()` produces:
   ```php
   [
       'post_type'      => 'post',
       'category_ids'   => [5],
       'posts_per_page' => '6',
       'orderby'        => 'date',
       'order'          => 'desc',
       // …other widget settings unrelated to query
   ]
   ```
2. **`Helper::get_query_args( $settings, 'post' )` runs.** First, `wp_parse_args` fills missing defaults: `posts_ids => []`, `offset => 0`, `post__not_in => []`.
3. **Standard args block builds:**
   ```php
   $args = [
       'orderby'             => 'date',
       'order'               => 'desc',
       'ignore_sticky_posts' => 1,
       'post_status'         => 'publish',
       'posts_per_page'      => '6',
       'offset'              => 0,
   ];
   ```
4. **post_type branch — not `by_id`.** `$args['post_type'] = 'post'`. `$args['tax_query'] = []` initialised. Loop registered taxonomies for the `post` post type. For each, look up `{taxonomy}_ids` in settings — `category_ids` is set to `[5]`, so a tax_query term is added:
   ```php
   $args['tax_query'][] = [
       'taxonomy' => 'category',
       'field'    => 'term_id',
       'terms'    => [5],
   ];
   $args['tax_query']['relation'] = 'AND';
   ```
5. **orderby branch — not `most_viewed` or `meta_value`.** No mutation.
6. **Author branch — `posts_by_current_user` not set, `authors` not set.** No mutation.
7. **Exclusion branch — `post__not_in` empty.** No mutation.
8. **Whols branch — post type `post`, not `product`.** Skipped.
9. **`get_query_args` returns the built `$args`.** Widget callsite does `$query = new WP_Query( $args )`, then loops to render HTML.
10. **Later, user clicks Load More.** `ajax_load_more` fetches the widget's saved settings, re-runs `Helper::get_query_args` (identical output), adds `'paged' => 2`, runs the query again, returns markup.

## The Settings → Args Contract

What settings keys `Helper::get_query_args` reads, and what `$args` keys it produces:

### Settings (input)

| Setting key | Type | Purpose |
| ----------- | ---- | ------- |
| `post_type` | string | Either a real post type slug (`post`, `product`, `eael_event_calendar`, …) or the pseudo-value `'by_id'` |
| `posts_ids` | array of ints | When `post_type === 'by_id'`, the explicit ids to include via `post__in` |
| `{taxonomy_name}_ids` | array of ints | Per-taxonomy term filter (e.g. `category_ids`, `post_tag_ids`, `product_cat_ids`) |
| `tax_query_relation` | `'AND'` / `'OR'` | Combinator for tax_query when multiple taxonomies are filtered |
| `orderby` | string | Standard WP_Query values plus EA-specific `'most_viewed'`, `'meta_value'` |
| `order` | `'asc'` / `'desc'` | Standard |
| `posts_per_page` | int / string | Post count per page |
| `offset` | int | Initial offset |
| `meta_key` | string (when orderby = `meta_value`) | Custom field key to sort by |
| `meta_type` | `'NUMERIC'` / `'DATE'` / `'DATETIME'` / `'CHAR'` | Cast type for the meta sort |
| `posts_by_current_user` | `'yes'` / empty | Restrict to current user's posts |
| `authors` | array of ints | Author id allow-list |
| `post__not_in` | array of ints | Exclude these post ids |

### Args (output)

| Args key | When set | Notes |
| -------- | -------- | ----- |
| `post_type` | always | Either the input post_type, or `'any'` when input was `'by_id'` |
| `post__in` | when `by_id` | Falls back to `[ 0 ]` when `posts_ids` is empty (intentionally returns nothing rather than everything) |
| `tax_query` | when not by_id and any `{taxonomy}_ids` is set | Includes a `relation` key only when at least one term is added |
| `orderby` | always | Translated for `most_viewed` / `meta_value` cases |
| `meta_key` / `meta_type` | when orderby = `meta_value*` | Used for custom-field sorting |
| `author__in` | when `posts_by_current_user='yes'` or `authors` set | Current-user variant takes precedence |
| `post__not_in` | when input non-empty | Pass-through |
| `meta_query` | for `product` + Whols active | Compatibility-only; otherwise omitted |
| `post_status` | always | **Always `'publish'`** — the helper does not accept caller overrides |
| `ignore_sticky_posts` | always | **Always `1`** — same |
| `posts_per_page`, `offset` | always | From settings, default 3 / 0 |

The "always" rows are the security and consistency guarantees: the helper itself prevents any caller from emitting non-public statuses or sticky-post surprises.

## Configuration & Extension Points

### Filters

| Filter | Where | Purpose |
| ------ | ----- | ------- |
| `pre_get_posts` (WP core) | Inside `WP_Query` | Mutate any EA query before SQL — this is the cleanest extension hook |
| `woocommerce_product_query_meta_query` (WC core) | When post_type = `product` and Whols Lite is active | EA piggybacks on this to keep Whols's wholesale-pricing meta filtering intact |

EA does not currently expose its own pre-construction filter on `$settings` or post-construction filter on `$args`. If you need to mutate args from outside the widget, `pre_get_posts` with a widget-aware predicate is the path.

### Adding a new query-driven widget — checklist

1. **Reuse `eael_query_controls`** in `register_controls()` to get the standard query control set ([Helper trait:65](../../../includes/Traits/Helper.php#L65)).
2. **In `render()`, call `Helper::get_query_args( $settings, $post_type )`** to build args.
3. **Construct `WP_Query`** with the result; do not extend the args dict for `post_status`, `ignore_sticky_posts`, or `post_type` overrides — the helper owns those.
4. **For load-more support**, re-derive args server-side in the AJAX handler from saved settings — never trust client-supplied args; that's the visibility-leak class. See [`ajax-endpoints.md`](ajax-endpoints.md) and [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md).
5. **For custom orderby** (anything beyond date / title / random / `most_viewed` / `meta_value`), extend the helper rather than overriding the result — keeps consistency for future widgets that need the same semantics.

## Common Pitfalls

### Trying to override `post_status` after the helper

`Helper::get_query_args` always sets `post_status = 'publish'`. Code like `$args['post_status'] = 'any'` after the helper call defeats the central security guarantee — and also means caller-supplied `post_status` from a load-more request would be honored. Don't do this. If the widget legitimately needs attachments (`'inherit'`), assemble the whitelist explicitly per-branch and document why.

### Forgetting to call `eael_sanitize_relation` on `date_query.relation`

[`ajax_load_more`](../../../includes/Traits/Ajax_Handler.php#L93) calls `eael_sanitize_relation` on `$args['date_query']['relation']` after `wp_parse_str`. Other handlers parsing similar args should do the same — the helper coerces to a known-good value (`'AND'` or `'OR'`), preventing SQL surprises if the caller sent a clever value.

### Confusing `Template_Query` trait with query construction

`Template_Query` trait handles **template file** selection (lite vs pro vs theme directory). It does **not** build `WP_Query` args. The names overlap; the concerns don't.

### `posts_per_page` as a string vs int

Elementor returns NUMBER control values as strings most of the time. WP_Query handles either, but if you do arithmetic on `$args['posts_per_page']` for pagination math, cast first.

### `'by_id'` with empty `posts_ids` returns nothing on purpose

[Line 202](../../../includes/Classes/Helper.php#L202) sets `$args['post__in'] = [ 0 ]` when the user picked "Manual Selection" but didn't select any posts. WP_Query with `post__in = [ 0 ]` returns zero rows. This is intentional — a missing selection should produce an empty list, not "all posts". Handle the empty case in your widget render rather than expecting the helper to fall back to a default query.

### Per-taxonomy setting key convention

The helper expects per-taxonomy filters under `{taxonomy_name}_ids` keys. If your widget's controls use a different convention (e.g. `selected_categories`), the helper won't find them — taxonomy filtering silently does nothing. Either rename your controls or pre-translate before calling the helper.

### Whols compatibility is post-type sensitive

The `woocommerce_product_query_meta_query` filter is applied only when both post type is `'product'` and Whols Lite is active. If you query products without going through the helper (e.g. direct `wc_get_products`), Whols's wholesale visibility logic is bypassed — products that should be hidden from non-wholesale users may appear.

## Debugging Guide

When a query returns wrong/no results:

1. **Inspect `$args` after the helper call.** Add `error_log( print_r( $args, true ) )` in your widget's `render()`. Confirm post_type, tax_query, and orderby match expectations.
2. **Run the same `$args` directly.** `var_dump( ( new WP_Query( $args ) )->request )` shows the generated SQL. Eyeball the WHERE clauses for typos in taxonomy names or meta keys.
3. **For `'by_id'` queries showing nothing:** confirm `posts_ids` is non-empty in settings. The `[ 0 ]` fallback is the cause when it's empty.
4. **For tax_query showing nothing:** confirm the taxonomy name matches what `get_object_taxonomies( $post_type )` returns. Custom post types may register taxonomies under unexpected slugs.
5. **For `most_viewed` returning the wrong order:** confirm posts have `_eael_post_view_count` meta written by the View Counter extension. Without that meta, posts sort as "missing meta" — typically last.
6. **For load-more results not matching the first page:** confirm the AJAX handler is calling the same helper, not building args itself. The contract is shared construction; divergence means the handler diverged.
7. **For Whols products not filtering:** confirm Whols Lite is active and the post type is `'product'`. The compat block only fires for both conditions.

## Worked Example — Post_Grid query

[Post_Grid.php:1780](../../../includes/Elements/Post_Grid.php#L1780) is the canonical caller. The widget settings contain (among others):

```php
$settings['post_type']         // 'post'
$settings['category_ids']      // [12, 19]
$settings['post_tag_ids']      // [3]
$settings['tax_query_relation']// 'OR'
$settings['posts_per_page']    // '8'
$settings['orderby']           // 'most_viewed'
$settings['order']             // 'desc'
$settings['post__not_in']      // [42]
```

After `Helper::get_query_args( $settings, 'post' )`:

```php
$args = [
    'post_type'           => 'post',
    'orderby'             => 'meta_value_num',  // translated from 'most_viewed'
    'meta_key'            => '_eael_post_view_count',
    'order'               => 'desc',
    'ignore_sticky_posts' => 1,
    'post_status'         => 'publish',
    'posts_per_page'      => '8',
    'offset'              => 0,
    'tax_query'           => [
        [
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => [12, 19],
        ],
        [
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => [3],
        ],
        'relation' => 'OR',
    ],
    'post__not_in' => [42],
];
```

Post_Grid then runs `new WP_Query( $args )`, loops, and emits markup. Load-more pages 2..N go through the same helper with `'paged' => N` added — guaranteed identical sort, taxonomy logic, and exclusions.

## Architecture Decisions

### Centralised `get_query_args` instead of per-widget builders

- **Context:** Without a shared helper, every list widget would have its own slightly different defaults, taxonomy logic, sticky-post handling, and orderby semantics. Bug fixes in one widget wouldn't propagate.
- **Decision:** One static helper. Every widget calls it. Differences expressed via settings, not via construction code.
- **Alternatives rejected:** Trait-mixed-into-widget (couples concerns); per-widget construction (drift); query-builder class (over-engineered for the actual variation).
- **Consequences:** Adding a new orderby (`most_viewed`, custom meta sort) touches one file but benefits 8+ widgets simultaneously. The trade-off is that the helper is the choke point — bugs in it affect everyone.

### Always force `post_status = 'publish'`

- **Context:** Caller-supplied `post_status` was the visibility-leak vector documented in the [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) skill. Defence in depth means even non-AJAX render paths shouldn't accept arbitrary statuses through this helper.
- **Decision:** Helper hardcodes `'publish'`. Widgets that need other statuses must opt in explicitly per-branch with their own logic, not through this helper.
- **Alternatives rejected:** Accept `post_status` from settings (caller can poison settings via export-import); whitelist statuses (still leaks future-added unsafe statuses).
- **Consequences:** Most widgets get safe behaviour for free. Widgets needing `'inherit'` (attachments) or other statuses bypass the helper for that branch — and document why.

### `'by_id'` pseudo post-type returns `'any'` with `post__in` fallback to `[ 0 ]`

- **Context:** Manual-selection mode lets users pick specific posts regardless of post type. With no selection, the user's intent is ambiguous — show nothing or show everything?
- **Decision:** Fall back to `post__in = [ 0 ]` (no post will ever have id 0) — guaranteed empty result.
- **Alternatives rejected:** Show all posts (information disclosure if mixed post types); throw an error (frustrates users mid-config).
- **Consequences:** Empty selection produces a deterministic empty render. Widgets must handle the empty case gracefully (placeholder text, hidden widget, etc.).

### Whols compatibility lives in the helper

- **Context:** Whols Lite (B2B / wholesale plugin) needs its meta-visibility filter applied on every product query. Adding it per-widget means missing one is a vendor data leak.
- **Decision:** Apply `woocommerce_product_query_meta_query` for product queries inside the shared helper.
- **Alternatives rejected:** Per-widget application (drift / forgetting); ignore Whols (breaks user expectation when both plugins are active).
- **Consequences:** Helper has a tiny knowledge of a third-party plugin. Acceptable because the alternative is invisible bugs in Whols + EA installs.

## Known Limitations

- **No pre-construction or post-construction EA filter.** Mutating args from outside the widget requires `pre_get_posts` with widget-id detection, which is awkward. Adding a thin filter layer would help — currently uncovered.
- **Settings shape is implicit.** The contract between `eael_query_controls` (which produces the settings) and `get_query_args` (which consumes them) is convention, not type. Renaming a control breaks queries silently.
- **`tax_query` always has `relation` when non-empty, even with one term.** Harmless but slightly odd output shape.
- **`meta_value` orderby with `meta_type='DATE'` and `'DATETIME'` both fall through to the same WP_Query semantics.** Distinguishing them only matters for some host MySQL configurations.
- **Whols compat is one-way.** If Whols is active but its `meta_query` filter returns null (rare config bug), `array_filter` produces an empty array, replacing whatever `$args['meta_query']` had. Edge case but possible.
- **No caching.** Each render re-runs the helper from scratch. For widgets with high-churn pages this is fine; for static-template-served pages it's a tiny waste.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — folder index and the five dynamic-data flows.
- **Architecture:** [`./ajax-endpoints.md`](ajax-endpoints.md) — handlers that re-call this helper for load-more / pagination paths.
- **Architecture:** [`./load-more-and-pagination.md`](load-more-and-pagination.md) — how the construction here gets paged at runtime.
- **Architecture:** [`../editor-data-flow.md`](../editor-data-flow.md) — settings shape produced by `eael_query_controls` and read here.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — Render path lands in this helper when a widget shows wrong posts.
- **Skills:** [`nopriv-ajax-hardening`](../../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — explains why `post_status` is forced and why caller-supplied query keys must be stripped.
- **Skills:** [`elementor-controls`](../../../.claude/skills/elementor-controls/SKILL.md) — Step 4 (EA-specific patterns) covers the standard query controls that feed this helper.
- **Rules:** [`php-standards.md`](../../../.claude/rules/php-standards.md) — sanitisation conventions used inside the helper.
