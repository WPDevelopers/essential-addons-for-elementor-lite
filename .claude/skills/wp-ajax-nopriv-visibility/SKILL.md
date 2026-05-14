---
name: wp-ajax-nopriv-visibility
description: >
  Audit and fix WordPress AJAX handlers registered via wp_ajax_nopriv_* that
  build WP_Query args from client input. Catches the common pattern where a
  handler overrides post_status/post_type to 'any' (or trusts client-supplied
  post__in / author / meta_query) and ends up exposing private, draft, pending,
  future, trash, or auto-draft content to unauthenticated visitors. Use when
  reviewing or hardening any wp_ajax_nopriv_ endpoint, or when a report mentions
  "unauthenticated information disclosure" / "draft exposure" / "private post
  leak" in a WordPress plugin.
---

# WP AJAX nopriv visibility hardening

## When to use

- A `wp_ajax_nopriv_<action>` handler accepts a serialized/parsed query arg blob (e.g. `wp_parse_str( $_POST['args'], $args )`).
- A vulnerability report mentions unauthenticated access to drafts, private posts, scheduled posts, or trashed posts.
- Reviewing any AJAX endpoint that calls `new WP_Query( $args )` where `$args` is built from request input.
- A nonce check exists but the matching nonce is itself obtainable by anonymous visitors (e.g. printed in page HTML, returned by another `nopriv` endpoint).

## Threat model

WordPress's `wp_ajax_nopriv_*` runs for visitors with no session. Nonces in WP are NOT authentication — they prevent CSRF, not unauthorized access. Any nonce returned by a `nopriv` route, or printed in a publicly cached page, is reachable by an attacker. Therefore:

- Never rely on nonce validity to gate access to non-public content.
- Never let a `nopriv` handler return content whose visibility depends on parameters the caller controls.

The dangerous primitives in `WP_Query` that leak non-public content:

| Param | Dangerous values | Safe whitelist |
|-------|------------------|----------------|
| `post_status` | `'any'`, `'private'`, `'draft'`, `'pending'`, `'future'`, `'trash'`, `'auto-draft'` | `['publish']`, plus `'inherit'` only if attachments are intentionally exposed |
| `post_type` | `'any'` | explicit list derived from server-side widget/shortcode settings |
| `post__in` | arbitrary IDs | `absint`-mapped, capped, or cross-checked against a server-derived allow-list |
| `perm` | `'editable'`, `'readable'` | drop entirely |
| `suppress_filters` | `true` | drop entirely |
| `meta_query`/`tax_query` keys hitting private fields | arbitrary | sanitize keys, whitelist taxonomies |

**Do NOT strip narrowing-only keys.** Once `post_status` is locked to `'publish'`, keys like `author`, `author__in`, `author__not_in`, `cat`, `tag`, `post__not_in` can only filter *within* the already-public set — they do not widen visibility. Stripping them silently breaks legitimate features (e.g. "Filter by Author" / "Posts by Current User") because plugins typically server-serialize the full `$args` blob into a `data-args` attribute via `http_build_query()` and round-trip it through the AJAX call. Verify by grepping the plugin for `http_build_query.*\$args` and `data-args` before deciding what is "client-controlled."

## Audit checklist

Run these greps in the plugin root:

```bash
# 1. Every nopriv handler — these are the entry points to review.
grep -rn "wp_ajax_nopriv_" --include="*.php" .

# 2. Handlers that re-parse a client query string.
grep -rn "wp_parse_str.*\$_POST\|wp_parse_str.*\$_REQUEST" --include="*.php" .

# 3. Any place that widens visibility. Each hit is a candidate bug.
grep -rnE "post_status['\"]?\s*\]?\s*=\s*['\"]any['\"]|post_type['\"]?\s*\]?\s*=\s*['\"]any['\"]" --include="*.php" .

# 4. Status values that should never appear in nopriv code paths.
grep -rnE "post_status['\"]?\s*\]?\s*=\s*['\"](private|draft|pending|future|trash|auto-draft)['\"]" --include="*.php" .

# 5. Nonces created by nopriv routes — if any of these are accepted by a
# sensitive handler, the nonce is decorative.
grep -rn "wp_create_nonce" --include="*.php" . | grep -B2 "wp_ajax_nopriv"
```

For each `wp_ajax_nopriv_*` action, walk the handler and answer:

1. Is `$args` (or any field passed to `WP_Query`) parsed from request input?
2. Does any code path set `post_status` or `post_type` to `'any'` or to a value pulled from request?
3. Is there an allow-list for `post__in`, or is the caller trusted to send valid IDs?
4. Are `author`, `author__in`, `perm`, `meta_query`, `tax_query`, `cache_results`, `suppress_filters` left as the caller sent them?
5. Does the matching nonce come from a `nopriv` source (page HTML, `wp_create_nonce` inside another `nopriv` handler)? If yes, treat the request as fully unauthenticated.

## Fix pattern

Apply these edits at the top of the handler, immediately after `wp_parse_str` (or wherever `$args` first exists):

```php
// $args is fully client-controlled. Strip ONLY the keys that can WIDEN
// visibility — these are never legitimate from the client. Without this,
// an unauthenticated visitor can pass post_status=private/draft (or
// perm=editable, or suppress_filters=true) and exfiltrate non-public
// content via this nopriv endpoint.
//
// NOTE: do NOT strip narrowing-only keys (author/author__in/cat/tag/etc.).
// Plugins commonly server-serialize the full $args into a data-args
// attribute via http_build_query() and round-trip them through Load More.
// Stripping them breaks the legitimate flow without buying any safety,
// because post_status='publish' below already prevents non-public leaks.
unset(
    $args['post_status'],
    $args['perm'],
    $args['suppress_filters']
);
$args['post_status'] = 'publish';

// post_type is intentionally NOT in the unset above because most widgets
// legitimately pass it via data-args. Validate it instead: if a branch
// later sets it to 'any', replace with a whitelist derived from $settings
// (see below). For the common case, accept the client value but verify
// it's a registered, public post type:
//   if ( ! empty( $args['post_type'] ) ) {
//       $args['post_type'] = (array) $args['post_type'];
//       $args['post_type'] = array_values( array_filter(
//           $args['post_type'],
//           function ( $t ) { return is_string( $t ) && 'any' !== $t && post_type_exists( $t ); }
//       ) );
//   }

// Coerce ID lists to ints and cap them so they cannot be abused for
// SQL bloat or to brute-force ID ranges.
foreach ( [ 'post__in', 'post__not_in' ] as $key ) {
    if ( isset( $args[ $key ] ) ) {
        $args[ $key ] = array_values( array_filter( array_map( 'absint', (array) $args[ $key ] ) ) );
        $args[ $key ] = array_slice( $args[ $key ], 0, 1000 );
    }
}
```

When a branch genuinely needs to widen the query (e.g. ACF gallery items are attachments with `post_status='inherit'`), build the whitelist from server-trusted settings, never from `$args` or `$_REQUEST`:

```php
// Whitelists derived from the widget's saved settings (server-trusted),
// NOT from request input. Never use 'any' here.
$safe_post_status = [ 'publish', 'inherit' ];     // 'inherit' only if attachments are intentionally exposed
$safe_post_types  = [ 'attachment' ];
if ( ! empty( $settings['post_type'] ) && is_string( $settings['post_type'] ) ) {
    if ( in_array( $settings['post_type'], [ 'by_id', 'manual', 'related', 'current_query' ], true ) ) {
        // Sentinel values like 'by_id' / 'Manual Selection' don't name a real
        // post type — the admin picked specific posts which can be of any
        // public type. Allow ALL public post types instead of stripping the
        // setting (which would silently drop parent posts from the result).
        // Still excludes internal types ('revision', 'oembed_cache',
        // 'nav_menu_item') that 'any' would have leaked.
        $safe_post_types = array_values( array_unique( array_merge(
            $safe_post_types,
            (array) get_post_types( [ 'public' => true ] )
        ) ) );
    } else {
        $safe_post_types[] = sanitize_key( $settings['post_type'] );
    }
}

if ( /* condition that requires attachments */ ) {
    $args['post_status'] = $safe_post_status;
    $args['post_type']   = $safe_post_types;
}
```

**Watch for sentinel post_type values.** Plugins commonly use placeholder strings (`'by_id'`, `'manual'`, `'related'`, `'current_query'`, `'custom'`) in `$settings['post_type']` to mean "the admin picked specific posts." These are NOT real post types — passing them to `WP_Query` filters out everything. If your whitelist drops them, parent items disappear from the result. Detect by listing the widget's `Controls_Manager::SELECT` options for the `post_type` control, or by reproducing the user-reported symptom ("only attachments come back, no parent posts").

Where the original code computed `post__in` server-side (e.g. by walking ACF fields on saved widget settings), keep that — those IDs are trustworthy. Still apply the whitelist on `post_type`/`post_status` as defence in depth.

## What to document in the diff

Each replaced `'any'` deserves an inline `SECURITY:` comment with the prior CVE-style sentence:

```php
// SECURITY: previously set to 'any'/'any' which let unauthenticated
// callers read drafts/private posts by passing arbitrary post__in IDs.
$args['post_status'] = $safe_post_status;
$args['post_type']   = $safe_post_types;
```

This makes future grep audits obvious and discourages a well-meaning refactor from reverting the fix.

## Nonce hygiene (secondary, often impossible to remove)

If the handler accepts a nonce that is generated by a `nopriv` route or printed in cached page HTML:

- Do not delete the nonce check — legitimate clients still need it for CSRF, and removing it may break the public widget.
- Do not assume the nonce gates access. Treat the request as anonymous and fix at the parameter validation layer (above).
- If a second, stricter nonce is also accepted (e.g. a generic plugin-wide nonce alongside an action-scoped one) and only the strict one is actually used by the legitimate client, drop the loose one. Confirm by grepping the JS for the action's data payload first.

## Verification

1. `php -l <file>` — syntax clean.
2. Re-run the audit greps above; the dangerous patterns must be gone.
3. Reproduce the original PoC against the fixed endpoint:
   - Obtain a nonce as an anonymous visitor.
   - Submit a request with `post_status=private`, `post_type=any`, `post__in[]=<known-draft-id>`, `author=1`, etc.
   - Response must contain only published content the attacker could already see.
4. **Regression test for narrowing keys.** Before declaring done, exercise every server-serialized arg the widget normally sends:
   - Configure the widget's "Filter by Author" / "Posts by Current User" / "Show Posts From Categories/Tags" / "Exclude Posts" controls.
   - Click Load More / scroll to trigger Infinity Scroll.
   - Confirm the AJAX response respects each filter (e.g. only posts by configured authors appear). If filters silently disappear, you over-stripped — the unset list is too aggressive. Re-read which keys round-trip via `print_load_more_button()` (or the equivalent `data-args` builder) and remove them from the unset.
5. **Regression test for every post_type source mode.** For widgets that let the admin choose the source (Latest Posts / Manual Selection / By Category / Related / Current Query), test each mode. The "Manual Selection" / "By ID" path is the easy miss — its `settings['post_type']` is a sentinel string, not a real type, so a naive whitelist filters out parent posts. Symptom: gallery shows attachments only, parent items missing.
4. Confirm the legitimate public flow still works: load the widget on a logged-out browser session and trigger "load more" / pagination.
5. Smoke-test logged-in editor flow if the same handler is also registered for `wp_ajax_<action>` (capability-gated paths often share the handler).

## Common related sinks in the same handler

While editing, scan for and fix:

- `realpath()` template loading that joins `$_REQUEST` segments — must verify the resolved path stays inside the expected directory (`strpos( $file_path, realpath( $base ) ) === 0`).
- `wp_send_json` / `echo` of `WP_Query` output without `wp_kses_post` or template-level escaping.
- `do_action( 'eael_before_ajax_load_more', $_REQUEST )`-style hooks that pass raw input to other listeners — those listeners are often where the real bug lives.
- `json_decode( html_entity_decode( stripslashes( $_POST[...] ) ) )` chains that bypass WP's slashing — coerce the result with `(array)` and `array_map( 'absint', ... )` before use.

## Reference fix

The canonical example (Essential Addons for Elementor Lite, `includes/Traits/Ajax_Handler.php` `ajax_load_more`) shows:

- Strip-and-redefault block right after `wp_parse_str`.
- Per-branch server-trusted whitelists for the Dynamic Filterable Gallery code path.
- `SECURITY:` comments on each replaced `'any'`.
- Nonce check left intact because the matching nonce is baked into rendered pages and cannot be removed without breaking public widgets.

=======
Summary
=======
Contents:
  - Threat model — nonces are CSRF-only, never auth; lists every dangerous WP_Query param + safe whitelist.                                                                  
  - Audit checklist — 5 grep recipes to find candidate handlers across a plugin.                                                                                             
  - Fix pattern — drop-in unset/whitelist block + server-trusted derivation for branches that legitimately need attachments.                                                 
  - Verification — PoC steps (anonymous nonce + private/draft request must return only public content) + regression check on public widget.                                  
  - Related sinks — realpath traversal, escape gaps, raw-input hooks, json_decode bypass — common neighbors in the same handler.                                             
  - Reference fix — points back to today's Ajax_Handler.php change.       