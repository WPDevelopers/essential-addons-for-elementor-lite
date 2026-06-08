---
name: wp-password-protected-exposure
description: >
  Audit and fix WordPress / WooCommerce plugins that leak password-protected
  post content through AJAX handlers or listing-widget queries. Catches the
  specific gap that `is_visible()` + `post_status === 'publish'` checks miss:
  password-protected posts keep `post_status='publish'`, so they bypass
  draft/private guards while their full content (title, price, SKU,
  description, permalink) is rendered to unauthenticated visitors. Use when
  a report mentions "password-protected product/post leak", "quickview
  exposure", "post_password not enforced", or whenever reviewing a
  `wp_ajax_nopriv_` endpoint that loads a single post's content, or any
  widget that builds a new `WP_Query` for a public listing.
---

# WordPress password-protected content exposure

Companion to `wp-ajax-nopriv-visibility`. That skill covers the
`post_status='any'` / `post__in` / draft / private family. This one covers
the **password-protected** primitive, which slips past the standard guards
because protected posts are technically `publish`.

## When to use

- Reviewing any `wp_ajax_nopriv_*` handler that fetches a single post or
  product and returns its title/content/price/meta (quickview, modal,
  preview, "load more details", etc.).
- Reviewing any widget or shortcode that builds a fresh `WP_Query` for a
  public listing (product grid/carousel/gallery/list, post grid, etc.) and
  does not pass `has_password`.
- A vulnerability report cites unauthenticated retrieval of fields like
  *price, SKU, description, gallery, permalink* of a password-protected
  product even though the standard single-post page shows the password form.
- Auditing a Wordfence/Patchstack/WPVulnDB advisory that names
  `post_password_required()` as the missing check.

## Threat model — why the usual guards miss this

WordPress's password-protect feature does **not** change `post_status`. A
protected post stays `post_status='publish'`. WP enforces the password by:

1. Replacing `the_content()` / `the_excerpt()` output with the password form
   (via filters `the_content` / `the_excerpt`).
2. Returning `true` from `post_password_required( $post )` when the visitor
   has not submitted the matching `wp-postpass_*` cookie.

Anything that bypasses `the_content` — direct property reads (`$post->post_content`,
`$product->get_description()`, `wc_get_template_part(...)` macros that render
title/price/SKU directly) — leaks the data. Common exposure surfaces:

| Surface | Why it leaks |
|---------|--------------|
| Quickview / modal AJAX | Fetches `wc_get_product()` and template-renders title/price/SKU; never calls `the_content` filter chain |
| Product/Post grid/carousel/gallery widgets | `new WP_Query( $args )` with no `has_password` filter; templates print `the_title()` (no auto-hide) + `$product->get_price_html()` directly |
| Search/autocomplete AJAX | Returns post titles + excerpts pulled via `$post->post_excerpt` |
| REST endpoints with custom controllers | If they don't gate on `post_password_required()` and don't return `WP_REST_Response` through `prepare_item_for_response()` |

Two more facts worth committing to memory:

- **Titles are NOT auto-hidden.** WP only prepends `"Protected: "` via the
  `protected_title_format` filter when titles are run through `the_title()`.
  If a widget reads `$post->post_title` directly (or a WC template does), the
  raw title is leaked without the prefix. Treat title leakage as in-scope
  *only* when the report explicitly calls it out, but always note it in your
  review.
- **The standard `/shop/` page lists password-protected products** with
  title + price visible (with `"Protected: "` prefix). That is WC's
  intentional default. Hiding them in a widget is therefore a
  defense-in-depth choice, not a bug-for-bug match — surface this when
  proposing patches so the maintainer can decide.

## The two fix primitives

### Primitive 1 — single-post AJAX: gate with `post_password_required()`

For any AJAX handler that loads one specific post/product and returns its
HTML or fields:

```php
$post = get_post( $product_id );

// Existing draft/private guard (keep it):
if ( ! current_user_can( 'edit_post', $product_id )
     && $post->post_status !== 'publish' ) {
    wp_send_json_error( __( 'Not found', 'textdomain' ) );
}

// NEW: password-protected guard.
if ( ! current_user_can( 'edit_post', $product_id )
     && post_password_required( $post ) ) {
    wp_send_json_error( __( 'Not found', 'textdomain' ) );
}
```

Why the `edit_post` bypass: admins and editors must keep loading the
quickview for protected products during authoring/preview. Use
`current_user_can( 'edit_post', $id )` because it is map-meta-cap aware —
do **not** use `current_user_can( 'edit_posts' )` (no arg), which is
weaker for CPTs.

### Primitive 2 — listing widgets: filter `has_password=false`

For any widget that builds a fresh `WP_Query` for a public listing:

```php
// Right before `new \WP_Query( $args )`, after any user/category filters
// have been merged:
if ( ! current_user_can( 'edit_others_posts' ) ) {
    $args['has_password'] = false;
}
$query = new \WP_Query( $args );
```

`has_password` is a first-class `WP_Query` parameter:

| Value | Behaviour |
|-------|-----------|
| `null` (default) | All posts, including password-protected |
| `true`           | Only password-protected posts |
| `false`          | Exclude password-protected posts |

Capability choice for listings: `edit_others_posts` (not `edit_post`,
because there's no specific post in scope at query time). Visitors and
subscribers/customers fall through to the filter; editors and admins skip
it.

**Do not patch the `is_archive()` / `$wp_query` global branch.** When a
widget reuses the global `$wp_query` (e.g. on shop archives), WC and the
main query already handle visibility. Only the `else` branch that builds a
new query is in scope.

## Audit grep recipes

```bash
# 1. Every nopriv handler — entry points for primitive 1.
grep -rn "wp_ajax_nopriv_" --include="*.php" .

# 2. Among those, the ones returning a single post/product:
grep -rnE "wc_get_product\(|get_post\(\s*\\\$.*_id" --include="*.php" .

# 3. Existing password-required checks (so you can see what's already gated):
grep -rn "post_password_required" --include="*.php" .

# 4. Listing widgets that instantiate WP_Query — primitive 2 targets.
grep -rnE "new \\\\?WP_Query\(" --include="*.php" includes/Elements/

# 5. Existing has_password filters (so you don't double-patch):
grep -rn "has_password" --include="*.php" .

# 6. Template files that print title/price/SKU directly — confirms what an
#    AJAX leak would expose if the handler is missing the gate.
grep -rnE "->post_title|get_price_html|->get_sku\(\)" --include="*.php" includes/Template/
```

## Test plan (reproduce → patch → verify)

The reproduction needs to use a real nonce because `nopriv` endpoints still
require a valid `essential-addons-elementor`-style nonce. Two ways to get
one as an attacker, both legitimate for QA:

```bash
# Generate one via WP-CLI (mirrors what an unauthenticated visitor would
# read from any public page that loads the plugin's frontend assets):
wp eval 'echo wp_create_nonce("essential-addons-elementor");'

# Or extract from a public page:
NONCE=$(curl -sk https://site.test/shop/ \
  | grep -oP '"nonce":"\K[^"]+' | head -1)
```

### Step 1 — Reproduce the leak (pre-patch)

```bash
curl -sk -X POST https://site.test/wp-admin/admin-ajax.php \
  -d "action=<eael_product_quickview_popup>&security=$NONCE&product_id=<PROTECTED_ID>&page_id=1&widget_id=x" \
  | head -c 500
# Expect: {"success":true,"data":"<full HTML with title/price/SKU>"}
```

### Step 2 — Apply the gate (both primitives if both surfaces exist).

### Step 3 — Re-run the curl. Expected: `{"success":false,"data":"Product not found or not accessible"}`.

### Step 4 — Regression matrix (script with WP-CLI):

```php
// Run via: wp eval-file <file>
$pid_protected = 21990;
$pid_normal    = 21411;

$cases = [
    ['user' => 0,                                  'pid' => $pid_protected, 'expect' => false],
    ['user' => 0,                                  'pid' => $pid_normal,    'expect' => true ],
    ['user' => 'administrator',                    'pid' => $pid_protected, 'expect' => true ],
    ['user' => 'subscriber',                       'pid' => $pid_protected, 'expect' => false],
];

foreach ( $cases as $c ) {
    $uid = $c['user'] === 0 ? 0 : get_users(['role' => $c['user'], 'number' => 1])[0]->ID;
    wp_set_current_user( $uid );
    $_POST = $_REQUEST = [
        'action'     => 'eael_product_quickview_popup',
        'security'   => wp_create_nonce( 'essential-addons-elementor' ),
        'product_id' => $c['pid'],
        'page_id'    => 1,
        'widget_id'  => 'test',
    ];
    $instance = \Essential_Addons_Elementor\Classes\Bootstrap::instance();
    ob_start();
    try { $instance->eael_product_quickview_popup(); } catch (\Throwable $e) {}
    $out = ob_get_clean();
    $got = strpos( $out, '"success":true' ) !== false;
    echo sprintf( "user=%s pid=%d expect=%s got=%s %s\n",
        $c['user'] ?: 'anon', $c['pid'],
        $c['expect'] ? 'OK' : 'BLOCK',
        $got       ? 'OK' : 'BLOCK',
        ( $got === $c['expect'] ) ? '✓' : '✗FAIL'
    );
}
```

### Step 5 — UI smoke test (Chrome DevTools MCP if available, otherwise manual):

- Open a page with the affected widget. Confirm the protected product no
  longer appears in the listing (primitive 2).
- Click a normal product's quickview button. Confirm popup opens with
  title/price/SKU and add-to-cart works (no regression).
- Click a variable product's quickview (if variable products exist).
  Confirm variation selectors render — this exercises a different template
  path that historically breaks on careless gates.
- For the AJAX (primitive 1), the click on a protected product (if it's
  reachable from somewhere) should return `{"success":false}` in the
  network panel and not render a popup.

## Common pitfalls

- **Using `current_user_can( 'edit_posts' )` without the post ID.** That
  cap is weaker than `edit_post`/`edit_others_posts` for custom post types
  with custom capability_type. Always pass the post ID for single-post
  gates and use `edit_others_posts` for listing gates.
- **Adding `post_password_required()` BEFORE `get_post()`.** The function
  needs a post object (or numeric ID it will resolve). Put the gate
  immediately after `$post = get_post( $id )` and after the `! $post`
  null-check.
- **Patching only the AJAX handler.** If the same plugin's widget lists
  the protected product with its title/price in the grid, an unauthenticated
  visitor still sees the leak via plain page HTML — not via your AJAX
  fetch. Audit both surfaces.
- **Patching only one of several widget classes.** Product Grid, Product
  Carousel, Product List, Product Gallery, Post Grid all build their own
  queries. Grep `new \\WP_Query` under `includes/Elements/` to find them
  all in one pass.
- **Forgetting the archive branch is fine.** Resist the urge to add
  `has_password` inside the `is_archive() && $is_product_archive` branch
  that uses the global `$wp_query`. That branch is already constrained by
  WC/main-query rules; touching it can break shop pagination.
- **Capability bypass too broad.** `current_user_can( 'read' )` is true
  for any logged-in user including subscribers. The right bypass for both
  primitives in a publishing/authoring context is editor-and-above:
  `edit_post` (with ID) for single fetches, `edit_others_posts` for
  listings.
- **Title leakage assumptions.** Be careful claiming "title is hidden" —
  WP only hides the title via `the_title()` + `protected_title_format`,
  and widgets that print `$post->post_title` skip that. If the report
  hinges on title visibility, verify what the widget actually outputs.

## Related skills

- `wp-ajax-nopriv-visibility` — broader `post_status`/`post__in`/`perm`
  hardening for `nopriv` AJAX. Use it first if the report is about drafts,
  private posts, or trash; use this one for the password-protected gap.
- `agent-skills:security-and-hardening` — general OWASP/defense-in-depth
  patterns once the WP-specific gate is in place.
