# Post Duplicator Extension

> Admin-side "EA Duplicator" link in the row actions of every post / page list and an entry in the top admin bar on `post.php`. Clicking the link deep-copies the post — title (with " - Copy" suffix), content, excerpt, parent, status (forced to `draft`), taxonomies, and all post meta except a tight blocklist (`_wc_average_rating`, `_wc_review_count`, `_wc_rating_count`, `_elementor_css`).

**Class file:** [`includes/Extensions/Post_Duplicator.php`](../../includes/Extensions/Post_Duplicator.php) (186 lines)
**Slug:** `post-duplicator` ([`config.php:1388`](../../config.php#L1388))
**Public docs:** <https://essential-addons.com/elementor/docs/post-duplicator/>
**Pro-shared:** Lite-only class; reused as-is when EA Pro is installed alongside Lite.

---

## Overview

Post Duplicator is an admin-only extension — it never touches the frontend, never enqueues a script, never registers a control. The constructor wires four WordPress core hooks: an `admin_action_eae_duplicate` handler (the actual duplication endpoint), an `admin_bar_menu` injector (top-bar shortcut on `post.php`), and `post_row_actions` + `page_row_actions` filters (the per-row "EA Duplicator" link in admin list tables).

The post type the extension is active for is controlled by the `eael_save_post_duplicator_post_type` option. The default is `'all'`, meaning every post type's row actions get the link. A specific post type slug stored in that option narrows the surface to only that type.

The duplicate is published as `draft` regardless of the source's status, so accidental clicks never push a clone to the live site.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Post_Duplicator.php`](../../includes/Extensions/Post_Duplicator.php) | The entire extension — 186 lines, four methods, no shared helpers |
| [`config.php:1388`](../../config.php#L1388) | Registry entry: `'post-duplicator' => [ 'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator' ]`. No `dependency` block — no assets ship with this extension. |
| `eael_save_post_duplicator_post_type` (option) | The per-post-type gate: `'all'` (default) or a specific post type slug |
| Admin URL `admin.php?action=eae_duplicate&post=<id>` | The custom admin action handled by [`Post_Duplicator::duplicate()`](../../includes/Extensions/Post_Duplicator.php#L75) |
| Nonce `ea_duplicator` | Required for the duplicate action; embedded in the link URL via `wp_nonce_url` |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Class instantiates | ✅ | ✅ (same class) |
| Row action appears | ✅ | ✅ |
| Admin-bar entry on `post.php` | ✅ | ✅ |
| Duplicates Elementor data (`_elementor_data`, `_elementor_edit_mode`, etc.) | ✅ | ✅ |
| Duplicates WooCommerce product meta (except review aggregates) | ✅ | ✅ |
| Frontend output | ❌ — admin only | ❌ — admin only |

No Pro override exists. The single Lite class is the source of truth.

## Architecture

- **Admin-only by design.** Every hook the constructor wires is admin-side: `admin_action_*`, `admin_bar_menu`, `post_row_actions`, `page_row_actions`. The extension has no `wp_enqueue_scripts` callback and no `wp_footer` callback. On a published-page request, the class loads but does nothing.
- **No `dependency` block.** Because nothing runs on the frontend, the registry entry omits `dependency`. `Asset_Builder` reads the empty entry and contributes zero bytes per request.
- **Two-step capability check.** The row-actions filter uses a coarse `current_user_can( 'edit_posts' )` so the link appears for editors / authors / contributors. The duplicate action handler then runs a per-post `current_user_can( 'edit_post', $post_id )` and, for non-administrator/editor/author roles, a `edit_others_posts` / `edit_others_pages` check. Users without permission are silently redirected back to the post list without an error message.
- **Direct SQL for meta copy.** Post meta is copied via a single batched `INSERT` statement constructed from a `$wpdb->prepare`-d row list. The `$wpdb->query` call carries a `phpcs:ignore` for `PreparedSQL.NotPrepared` because the row VALUES are individually prepared and concatenated into one query for performance — the alternative of one `wp_insert_post_meta` per row is dramatically slower for posts with hundreds of meta rows (Elementor pages especially).
- **Blocklist on meta keys.** Four keys are skipped:
    - `_wc_average_rating` / `_wc_review_count` / `_wc_rating_count` — copying these would falsely inflate WooCommerce review aggregates on the duplicate.
    - `_elementor_css` — generated CSS, regenerated on first edit of the duplicate; copying it would point to the source's CSS file.
    - `_elementor_template_type` is conditionally deleted before insert (via `delete_post_meta` of the freshly created duplicate). This guards against duplicating a template's "template type" semantically — the duplicate becomes a regular `post`/`page`/etc. of the same post type rather than a template subtype.
- **`wp_insert_post` + `wp_set_object_terms`.** Taxonomies are copied via `wp_get_object_terms` followed by `wp_set_object_terms`. No deep custom logic — just whatever the source had.

## Render Behavior

### Admin row actions

Inside any admin list table for posts or pages where `current_user_can( 'edit_posts' )` and the post type matches `eael_save_post_duplicator_post_type` (or that option is `'all'`):

```html
<span class="eae_duplicate">
  <a href="https://example.com/wp-admin/admin.php?action=eae_duplicate&post=42&_wpnonce=abc123"
     title="Duplicate Hello World">EA Duplicator</a>
</span>
```

### Admin bar (on `post.php` only)

When viewing a post-editor page and the post type matches:

```html
<li id="wp-admin-bar-eae-duplicator">
  <a href="https://example.com/wp-admin/admin.php?action=eae_duplicate&post=42&_wpnonce=abc123">EA Duplicator</a>
</li>
```

### Duplicate action redirect

`Post_Duplicator::duplicate()` exits with `wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) )`. The user lands on the appropriate admin list with the new draft visible at the top.

### Frontend

N/A — no output. The extension contributes nothing to a `wp` / `wp_enqueue_scripts` / `wp_head` / `wp_footer` request.

## Asset Dependencies

N/A — admin-only extension, no CSS or JS shipped. The registry entry omits `'dependency'`.

## Hook Timing

| Hook | Priority | Phase | Effect |
| ---- | -------- | ----- | ------ |
| `admin_action_eae_duplicate` | 10 (default) | Admin — when `admin.php?action=eae_duplicate` loads | Runs `Post_Duplicator::duplicate()` to do the actual copy |
| `admin_bar_menu` | 10000 | Admin — top admin bar render | Adds "EA Duplicator" node, restricted to `post.php` and the configured post type |
| `post_row_actions` | 10 | Admin — post list table render | Adds row link to every post row |
| `page_row_actions` | 10 | Admin — page list table render | Adds row link to every page row |

### Nonce lifecycle

- **Issued** by `wp_nonce_url( $duplicate_url, 'ea_duplicator' )` inside both `row_actions` and `admin_bar_menu`. The `_wpnonce` query parameter is appended to the URL.
- **Verified** by `wp_verify_nonce( $nonce, 'ea_duplicator' )` at the top of `duplicate()`. Failure silently `return`s — no error, no notice.

### Permission lifecycle

1. **List view**: `current_user_can( 'edit_posts' )` — coarse check, returns true for anyone who can edit any post.
2. **Action handler — first gate**: `current_user_can( 'edit_post', $post_id )` — fine-grained, post-specific. Failure calls `wp_die()` with an i18n'd message.
3. **Action handler — second gate**: for non-administrator/editor/author roles, an additional `edit_others_posts` / `edit_others_pages` check when the source post isn't authored by the current user. Failure silently `wp_safe_redirect`s back.

## Configuration & Extension Points

Post Duplicator does not emit any `do_action` / `apply_filters` of its own — no filter to intercept the duplicate payload, no action to react to a completed duplicate. The configuration surface is:

| Mechanism | Where | Use |
| --------- | ----- | --- |
| `eael_save_post_duplicator_post_type` option | EA Settings (admin UI) | `'all'` (default) or specific post type slug — gates which types show the link |
| `eael_save_settings` option | EA Settings / Setup Wizard | Toggle `post-duplicator => 1/0` |
| `eael/registered_extensions` filter | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Remove from registry entirely |

The blocklist of skipped meta keys is hardcoded inside `duplicate()`. Extending it requires editing the class.

## Customization Recipes

### Recipe 1 — Allow duplication on a specific post type only

```php
update_option( 'eael_save_post_duplicator_post_type', 'product' );
```

Now the row action appears only for `product` rows; `post`, `page`, and other types lose the link. Same effect as picking a single post type in EA's settings UI.

### Recipe 2 — Extend the meta-key blocklist

The shipped class hardcodes four keys. To skip additional ones (e.g. SEO plugin meta you don't want copied), subclass and replace:

```php
namespace My_Plugin;

class Custom_Post_Duplicator extends \Essential_Addons_Elementor\Extensions\Post_Duplicator {
    // The duplicate() method is monolithic; the cleanest path is to copy it
    // and adjust the $exclude_meta_keys list. See Post_Duplicator.php:162.
}

add_filter( 'eael/registered_extensions', function ( $exts ) {
    if ( isset( $exts['post-duplicator'] ) ) {
        $exts['post-duplicator']['class'] = '\\My_Plugin\\Custom_Post_Duplicator';
    }
    return $exts;
} );
```

A cleaner upstream fix would introduce a filter such as `eael/post_duplicator/exclude_meta_keys`. Not yet shipped — see [Known Limitations](#known-limitations).

### Recipe 3 — Notify on duplicate completion

The shipped class emits no action when duplication finishes. To bolt one on without forking, hook `wp_insert_post` and detect the EA pattern:

```php
add_action( 'wp_insert_post', function ( $post_id, $post, $update ) {
    if ( $update || $post->post_status !== 'draft' ) {
        return;
    }
    if ( substr( $post->post_title, -7 ) !== ' - Copy' ) {
        return;
    }
    if ( empty( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'eae_duplicate' ) {
        return;
    }
    // Confirmed: a Post Duplicator clone just landed.
    error_log( sprintf( 'EA duplicate created: %d -> %d', $_REQUEST['post'], $post_id ) );
}, 10, 3 );
```

Fragile — it inspects the title suffix and the request action. A real `eael/post_duplicator/after_duplicate` would solve this properly.

## Common Issues

### "EA Duplicator" link doesn't appear in row actions

- **Cause:** Extension disabled in `eael_save_settings`, or the post type filter excludes the current type, or current user lacks `edit_posts`.
- **Diagnose:** `var_dump( get_option( 'eael_save_settings' )['post-duplicator'] ?? 'missing' );` and `var_dump( get_option( 'eael_save_post_duplicator_post_type', 'all' ) );`
- **Fix:** Enable the extension; set the post type to `all` or the current type.

### Clicking the link redirects without creating a duplicate

- **Cause:** Capability check failed silently. The handler returns / redirects without an error message for several failure modes (no nonce, expired nonce, no permission, post not found).
- **Diagnose:** Enable `WP_DEBUG_LOG` and add `error_log()` calls at each early return inside `duplicate()` to identify which gate is failing.
- **Fix:** Confirm the user has `edit_post` capability on the source post.

### Duplicate is missing some custom meta

- **Cause:** The meta key matches one of the four hardcoded blocklist entries, or the source's meta lives outside `wp_postmeta` (some plugins store custom data in custom tables).
- **Fix:** Custom-table data: write a separate copier hooked to `wp_insert_post` after the duplicate is created. Blocklist: see [Recipe 2](#recipe-2--extend-the-meta-key-blocklist).

### Duplicate created but featured image / Elementor data is broken

- **Cause:** Featured image is a `_thumbnail_id` post meta pointing at an attachment ID — the attachment itself is not duplicated; both posts share the same attachment, which is intended. For Elementor: `_elementor_css` is intentionally skipped (regenerated on first edit). If `_elementor_data` is missing, the meta-copy loop ran into an error before reaching it — check `debug.log` for `$wpdb` errors.
- **Fix:** For attachments to be cloned per duplicate, write a separate copier. For Elementor: open the duplicate in Elementor once; the CSS file regenerates.

### WooCommerce review counts copied incorrectly

- **Cause:** Should not happen — the four blocklisted keys (`_wc_average_rating`, `_wc_review_count`, `_wc_rating_count`) cover the WC aggregates. If counts are still showing on the duplicate, they were derived from comments that were *not* copied (the extension doesn't copy comments at all) — so counts will be stale until WC recalculates.
- **Fix:** Either trigger WC's recalculation routines on the duplicate, or accept that counts read zero until reviews are posted on the duplicate.

### Duplicate's post status isn't `draft`

- **Cause:** Working as designed — the duplicate is *always* created as `draft`. If you observed `publish`, something post-processed the duplicate after creation.
- **Fix:** Audit `save_post` / `wp_insert_post` callbacks that might be flipping the status.

## Debugging Guide

1. **Confirm activation.** `error_log( get_option( 'eael_save_settings' )['post-duplicator'] ?? 'missing' );`
2. **Confirm post-type gate.** `error_log( get_option( 'eael_save_post_duplicator_post_type', 'all' ) );`
3. **Inspect the link's URL.** Hover the row action in admin; the URL should include `action=eae_duplicate&post=<id>&_wpnonce=<hash>`. Missing `_wpnonce` means `wp_nonce_url` wasn't called — check that `row_actions` returned the modified array.
4. **Verify nonce.** `wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'ea_duplicator' )` should return non-false. Expired nonces (more than 24h since link generation) silently fail.
5. **Per-gate logging.** Add `error_log` at each early `return` / `wp_die` / `wp_safe_redirect` inside `duplicate()` to pinpoint which capability check rejected the request.
6. **Inspect the SQL.** Add `error_log( $duplicate_insert_query . $insert );` before `$wpdb->query(...)`. The query should be a single `INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (…), (…), (…)`. If the row count is unexpectedly low, the blocklist is excluding more than expected.
7. **For Elementor data**: open the duplicate in Elementor and confirm the page renders. The first render triggers `_elementor_css` regeneration. If the page is blank, `_elementor_data` may have failed to copy — re-check the SQL log.

## Architecture Decisions

### Admin-only, no asset footprint

- **Context:** Duplication is fundamentally an admin operation. There's no scenario where a frontend visitor needs the extension's behaviour.
- **Decision:** Wire only admin-side hooks. Skip the `dependency` block in the registry entirely.
- **Alternatives rejected:** Add a frontend "duplicate this page" link for logged-in admins (overlap with the toolbar, security headaches); pre-emptively enqueue a CSS file for the row action's styling (irrelevant — WP core styles the row actions).
- **Consequences:** The extension class loads on every request (admin and frontend), but contributes zero work on frontend requests. Acceptable overhead — a class definition with no triggered hooks costs nothing meaningful at runtime.

### Direct batched `INSERT` for meta copy

- **Context:** Posts can carry hundreds of meta rows. `update_post_meta` per row issues one query each; Elementor pages frequently have 50+ meta entries between settings and revision pointers.
- **Decision:** Build one batched `INSERT INTO wp_postmeta (...) VALUES (...), (...)` via `$wpdb->prepare` per row, concatenated, then a single `$wpdb->query`.
- **Alternatives rejected:** `update_post_meta` in a loop (slow, breaks `add_post_meta` hooks for consumers — but those aren't fired by raw INSERT either, so this is a wash); `$wpdb->insert` per row (still N queries).
- **Consequences:** Raw SQL bypasses meta-related hooks (`added_post_meta`, etc.). Consumers expecting those hooks to fire on duplication won't see them. Documented as a [Known Limitation](#known-limitations).

### Duplicate status forced to `draft`

- **Context:** A duplicate of a published post that auto-publishes would push an unintended duplicate live, with the same content, immediately visible at a new URL — bad for SEO and embarrassing for the user.
- **Decision:** Force `post_status => 'draft'` in `wp_insert_post` args regardless of source status.
- **Alternatives rejected:** Copy the source status (dangerous default); ask the user (no UI surface for it).
- **Consequences:** Users who genuinely want to duplicate-and-republish must manually flip the duplicate's status. Tiny extra step, large safety win.

### `_elementor_template_type` deletion after copy

- **Context:** Duplicating an Elementor template (header / footer / popup) and keeping the `_elementor_template_type` meta would make the duplicate appear as another template of the same kind. That's almost always wrong — the user wanted to clone the *content*, not the template's role.
- **Decision:** After the duplicate is inserted, `delete_post_meta( $duplicated_id, '_elementor_template_type' )` strips that role.
- **Alternatives rejected:** Skip the key in the blocklist (would leave the meta in place); copy and then prompt the user.
- **Consequences:** Duplicates of templates become regular posts of the same post type — which the user can re-register as a template manually if desired.

### Silent failure on capability rejection

- **Context:** Showing an error message exposes that the URL exists and reveals user-role-specific differences in behaviour — minor information disclosure.
- **Decision:** Most capability failures return / redirect silently. Only the `current_user_can('edit_post')` failure shows `wp_die()` with an i18n'd message.
- **Alternatives rejected:** Uniform `wp_die` for all failures (verbose, leaks info); uniform silent redirect for all failures (the `edit_post` case is a developer-relevant error, not a user-facing one).
- **Consequences:** Users with insufficient permission see "nothing happened" rather than "permission denied" — confusing for support cases.

## Known Limitations

- **No `eael/post_duplicator/...` filters / actions.** Cannot intercept the duplicate args, skip meta keys, or react after duplication without forking the class. The blocklist of skipped meta keys is hardcoded.
- **Raw-SQL meta copy bypasses meta hooks.** Plugins listening to `added_post_meta`, `updated_post_meta`, etc. for sync / search-index purposes don't see the duplicate's meta. Consumers must reindex manually.
- **Comments not copied.** A duplicated post has zero comments regardless of the source. Often desired (a fresh start) but not configurable.
- **Featured image is shared, not cloned.** The duplicate references the same attachment ID. Edits to that attachment affect both posts.
- **Custom taxonomies copy slugs, not term IDs.** `wp_get_object_terms( ..., [ 'fields' => 'slugs' ] )` + `wp_set_object_terms`. If a slug exists in a different taxonomy with different metadata, the duplicate gets the target's term — same slug, but you lose any term-meta divergence. In practice negligible.
- **Title suffix is hardcoded to " - Copy".** Not i18n-aware; not filterable. Reads awkwardly in non-English locales.
- **Nonce lifetime is WP default (24h).** Stale links from old admin tabs silently fail. The user gets the redirect but no error.
- **Silent capability rejections.** See the architecture decision above. Support cases routinely involve "the duplicator does nothing when I click it" with no actionable error.
- **No per-meta-key filter for the blocklist.** Adding `_yoast_seo_*` exclusions or similar requires subclassing.
- **No "duplicate as private" / "duplicate as published" UI.** Status is locked to `draft`.

## Recent Significant Changes

No tracked changes yet. Future entries here when:

- A new meta-key blocklist filter (`eael/post_duplicator/exclude_meta_keys`) ships
- An `eael/post_duplicator/after_duplicate` action ships
- Comment copying becomes optional
- The title suffix becomes filterable / translatable
- Featured-image attachment cloning becomes optional

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — subsystem doc identifies Post Duplicator as the admin-only extension exemplar.
- **Sibling extension docs:** [`./promotion.md`](promotion.md), [`./custom-js.md`](custom-js.md) — also have no frontend asset footprint but for different reasons (editor-only UI vs admin-only operations).
- **Rules:** [`../../.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md) — nonce verification + capability checks every admin-action handler must follow.
- **WordPress docs:** [Adding row actions](https://developer.wordpress.org/reference/hooks/post_row_actions/) and [`admin_action_{$action}`](https://developer.wordpress.org/reference/hooks/admin_action__action/).
- **Public docs:** <https://essential-addons.com/elementor/docs/post-duplicator/>
