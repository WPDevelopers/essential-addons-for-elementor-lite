# Breadcrumbs Widget

> Renders a breadcrumb trail derived from the current page's context — page parents, post category hierarchy, archive type, search query, date archive, etc. Uses WooCommerce's native `woocommerce_breadcrumb()` for product / product-archive pages, falls back to a custom WordPress-context branch tree for everything else. Pure-CSS widget — no JavaScript.

**Class file:** [`includes/Elements/Breadcrumbs.php`](../../includes/Elements/Breadcrumbs.php)
**Slug:** `breadcrumbs` (widget id `eael-breadcrumbs`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-breadcrumbs/>
**Pro-shared:** ❌ — Lite-only widget. No `eael_section_pro` upsell panel, no `pro_enabled` filter check, no `do_action` / `apply_filters` calls. Pro neither references nor extends.

---

## Overview

Breadcrumbs reads the current page's WP context (post type, parent hierarchy, archive type, search query, date archive, 404, etc.) and renders a chain of links from the home page to the current page. WooCommerce contexts (single products, product categories, product tags) delegate to WC's native `woocommerce_breadcrumb()` function with EA's separator and home label injected via `$args`. Everything else uses a custom branch tree in `eael_breadcrumbs()` covering 11 page types: home, category archive, attachment, page (with / without parent), single post, custom post type single, search results, day / month / year archives, tag, author, 404.

The widget is purely server-rendered — `render()` produces the entire trail HTML at page load. No JS, no client-side hydration. SCSS is 42 lines covering spacing, separator visibility, and a WooCommerce-specific selector for the WC-injected breadcrumb markup.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| WordPress breadcrumb trail (11 page types) | ✅ | ✅ |
| WooCommerce breadcrumb (delegated to `woocommerce_breadcrumb()`) | ✅ | ✅ |
| Custom separator (icon or text) | ✅ | ✅ |
| Custom prefix (icon or text) | ✅ | ✅ |
| Configurable home label | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Filter or action hooks for Pro extension | ❌ — none emitted | — |

The widget ships zero Pro extension surface. Customisation is via CSS overrides or WordPress filters on `woocommerce_breadcrumb_defaults` / the WC breadcrumb output filter chain.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Breadcrumbs.php`](../../includes/Elements/Breadcrumbs.php) | PHP widget class — 4 control sections + 4 protected helper methods + render branching |
| [`src/css/view/breadcrumbs.scss`](../../src/css/view/breadcrumbs.scss) | Source styles — 42 lines; container, link, separator, WC integration |
| [`config.php`](../../config.php#L691) entry `'breadcrumbs'` | `Asset_Builder` dependency declaration (CSS only) |
| `assets/front-end/css/view/breadcrumbs.min.css` | Built output (do not edit) |

No widget-specific JS source or compiled file. No vendor libraries.

## Architecture

- **`render()` branches on WooCommerce context** — calls `eael_wc_breadcrumb()` when the current page is a WC product, product category, or product tag; otherwise `eael_breadcrumbs()`. WooCommerce detection: `class_exists('WooCommerce') && (wc_get_product(get_the_ID()) || is_tax('product_cat') || is_tax('product_tag'))` ([line 728-730](../../includes/Elements/Breadcrumbs.php#L728)).
- **WC path delegates to native `woocommerce_breadcrumb()`** — passes `$args` with EA's separator (from `eael_breadcrumb_separator()`), home label, and a `<nav>` wrapper ([line 593-603](../../includes/Elements/Breadcrumbs.php#L593)). WC's standard filters (`woocommerce_breadcrumb_defaults`, `woocommerce_breadcrumb_home_url`) all still apply.
- **Non-WC path is a long `if/elseif` chain** in [`eael_breadcrumbs()`](../../includes/Elements/Breadcrumbs.php#L606) covering 11 page types: home, category, attachment, page (parent / no-parent), single post (custom post type / default), unknown post type, search, day / month / year archive, tag, author, 404. Each branch builds the chain manually with `get_category_parents()`, `get_permalink()`, and `single_*_title()` calls.
- **Four protected helpers compose the output:**
  - `eael_breadcrumb_home_label()` — returns sanitised home text ([line 546](../../includes/Elements/Breadcrumbs.php#L546))
  - `eael_breadcrumb_prefix()` — outputs the optional icon / text prefix wrapped in `.eael-breadcrumbs__prefix` ([line 554](../../includes/Elements/Breadcrumbs.php#L554))
  - `eael_breadcrumb_separator()` — returns `<span class="eael-breadcrumb-separator">` with icon or text ([line 580](../../includes/Elements/Breadcrumbs.php#L580))
  - `eael_wc_breadcrumb()` — calls `woocommerce_breadcrumb()` with EA's `$args` ([line 592](../../includes/Elements/Breadcrumbs.php#L592))
- **No `eael_section_pro` upsell and no Pro hooks** — third in the Display category after Feature_List and Code_Snippet to ship with zero Pro extension surface. Pure Lite widget; customisation via CSS only.
- **`register_controls()` is a thin orchestrator** that calls four section-builder methods (`eael_breadcrumb_general`, `_style`, `_separator_style`, `_prefix_style`). Pattern is unique among Display widgets — most inline all sections into `register_controls()`.

## Render Output

The widget produces one of two structures depending on WC context.

### Non-WC pages (default)

```html
<div class="eael-breadcrumbs">
  [?] <div class="eael-breadcrumbs__prefix">
        <a href="<home-url>" class="eael-breadcrumbs__prefix-link">
          <i class="fas fa-home"></i>     <!-- icon prefix -->
        </a>
        <!-- OR -->
        <span>Browse:</span>              <!-- text prefix -->
      </div>
  <div class="eael-breadcrumbs__content">
    <a href="<home-url>">Home</a>
    <span class="eael-breadcrumb-separator">/</span>     <!-- or icon -->

    <!-- page-type-specific links built by eael_breadcrumbs() -->
    <a href="<parent-url>">Parent Page</a>
    <span class="eael-breadcrumb-separator">/</span>
    <span class="eael-current">Current Page Title</span>
  </div>
</div>
```

### WooCommerce pages

```html
<div class="eael-breadcrumbs">
  [?] <div class="eael-breadcrumbs__prefix">…</div>
  <nav class="eael-breadcrumbs__content woocommerce-breadcrumb" aria-label="Breadcrumb">
    <!-- WC's native breadcrumb output with EA's separator injected -->
    <a href="<home>">Home</a>
    <span class="eael-breadcrumb-separator">/</span>
    <a href="<shop>">Shop</a>
    <span class="eael-breadcrumb-separator">/</span>
    Current Product Title
  </nav>
</div>
```

Notes:

- `.eael-breadcrumbs` is the styling root.
- Prefix block is conditional on `breadcrumb_prefix_switch === 'yes'`; icon prefix wraps in `<a>` linking to home, text prefix is a plain `<span>` (no link).
- Current page (terminal node) is wrapped in `<span class="eael-current">` for the WP context branch; WC branch leaves it as plain text (WC's default).
- Separator is rendered as `<span class="eael-breadcrumb-separator">` with either an icon (`<svg>` / `<i>`) or a text character.
- ⚠️ **Stray `</a>` at [line 574](../../includes/Elements/Breadcrumbs.php#L574)** — in the prefix template, `</a>` appears after the switch case closes. For the **text** case, the prefix renders `<span>...</span></a>` — an unmatched closing tag. Browsers tolerate this but HTML validators flag it.
- ⚠️ **Home URL not escaped via `esc_url()`** in `eael_breadcrumbs()` ([lines 621, 625, 674](../../includes/Elements/Breadcrumbs.php#L621)) — uses raw `get_bloginfo('url')` concatenation into `href` attribute. Theoretical XSS risk if the site URL is corrupted, but `get_bloginfo` returns a sanitised URL — defence-in-depth would still add `esc_url()`.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Breadcrumbs.php#L41) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `breadcrumb_home_text` | TEXT (dynamic via AI) | `"Home"` | Content → General | Home label text |
| `breadcrumb_prefix_switch` | SWITCHER | empty | Content → General | Renders the prefix block |
| `eael_breadcrumb_prefix_type` | CHOOSE | `icon` | Content → General | Icon vs text prefix |
| `eael_breadcrumb_prefix_icon` | ICONS | `fas fa-home` | Content → General | Prefix icon glyph |
| `eael_breadcrumb_prefix_text` | TEXT (dynamic) | `"Browse: "` | Content → General | Prefix text content |
| `eael_separator_type` | CHOOSE | `text` | Content → General | Icon vs text separator |
| `eael_separator_icon` | ICONS | `fas fa-angle-double-right` | Content → General | Separator icon glyph |
| `eael_separator_type_text` | TEXT (dynamic) | `"/"` | Content → General | Separator text character |
| Style → Breadcrumb / Separator / Prefix | various | — | Style tab | Typography, colour (normal + hover), background, padding, border |

No control for separator-after-current-page (whether the trailing separator appears after the current page name). No control for `$show_current` (whether to render the current page name at all). Both are hardcoded.

## Conditional Dependencies

```text
# Content → General
eael_breadcrumb_prefix_type      → visible when breadcrumb_prefix_switch == 'yes'
eael_breadcrumb_prefix_icon      → visible when breadcrumb_prefix_switch == 'yes'
                                   AND eael_breadcrumb_prefix_type == 'icon'
eael_breadcrumb_prefix_text      → visible when breadcrumb_prefix_switch == 'yes'
                                   AND eael_breadcrumb_prefix_type == 'text'
eael_separator_icon              → visible when eael_separator_type == 'icon'
eael_separator_type_text         → visible when eael_separator_type == 'text'

# Style sections have no conditional visibility — always shown
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension paths:

| Hook | Where | Purpose |
| ---- | ----- | ------- |
| `woocommerce_breadcrumb_defaults` | WC core (consumed indirectly) | Override default `$args` for `woocommerce_breadcrumb()` calls — affects EA's WC path |
| `woocommerce_breadcrumb_home_url` | WC core (consumed indirectly) | Override the home URL emitted by WC's breadcrumb |
| WordPress core: `get_the_category`, `get_category_parents`, `single_*_title`, `get_permalink` | consumed | Standard WP filters apply to the data the non-WC branch reads |

Theme customisation is via CSS overrides (the class set is documented above) or by hooking WC's own filter chain.

## JavaScript Lifecycle

N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`, and the entire breadcrumb trail is server-rendered.

## Common Issues

### Breadcrumb shows "Home" but no other links on a category archive

- **Likely cause:** `get_query_var('cat')` returned 0 (homepage with category context query string but no actual category) — the `is_category()` branch checks `$get_category->parent` but if `$get_category` is false, the code emits only the home link plus the "Archive by category" suffix
- **Diagnose:** check the URL — is it a real category archive (`/category/<slug>`)?
- **Fix:** category archives must have a registered taxonomy term to render correctly

### WooCommerce breadcrumb doesn't appear on shop archive

- **Likely cause:** `wc_get_product(get_the_ID())` returns false on the shop index, and `is_tax('product_cat')` / `is_tax('product_tag')` are also false. The WC detection at [line 729-730](../../includes/Elements/Breadcrumbs.php#L729) misses the shop page, so the widget falls through to the non-WC branch which doesn't know about the shop page
- **Diagnose:** browse to `/shop/` — does the widget show "Home > [shop page title]" or the WC trail?
- **Fix:** known limitation; shop archive is not detected as a WC context. The non-WC branch renders the page title via `is_page()` if the shop page is set in WC settings

### Prefix text renders with a stray closing `</a>` tag

- **Likely cause:** [line 574](../../includes/Elements/Breadcrumbs.php#L574) emits `</a>` outside the switch, but the text case opens a `<span>` not an `<a>` — produces `<span>text</span></a>`. Browsers self-correct; HTML validators flag this as "unmatched end tag"
- **Diagnose:** run a W3C validator on a page with the prefix-text variant
- **Fix:** known minor bug; not user-fixable without editing the source

### Separator appears with extra whitespace before / after

- **Likely cause:** `eael_breadcrumbs()` builds the output string by concatenating `' ' . $delimiter . ' '` — adds a literal space before and after every separator
- **Diagnose:** by design; CSS can normalise via `.eael-breadcrumb-separator { margin: 0 4px; } .eael-breadcrumbs__content { word-spacing: 0; }`
- **Fix:** override via theme CSS

### Page title appears uncapitalised on some templates

- **Likely cause:** `get_the_title()` returns raw title; if the theme applied a CSS `text-transform: uppercase` to ancestor elements, the breadcrumb inherits
- **Diagnose:** inspect computed `text-transform` on `.eael-current`
- **Fix:** override via theme CSS — `.eael-breadcrumbs .eael-current { text-transform: none; }`

### Custom post type breadcrumb missing the archive link

- **Likely cause:** the CPT's `rewrite.slug` is empty / set to `false` — the code checks `$post_type->rewrite ?? false` then `isset($get_slug['slug'])`; when slug is missing the link points to the bare home + empty slug ([line 669-674](../../includes/Elements/Breadcrumbs.php#L669))
- **Diagnose:** check the CPT registration `'rewrite' => ['slug' => '<slug>']`
- **Fix:** add a `rewrite.slug` to the CPT registration

## Known Limitations

- **Stray `</a>` after the prefix-text case** ([line 574](../../includes/Elements/Breadcrumbs.php#L574)) — unmatched closing tag emitted for the text-prefix variant. Browsers tolerate; HTML validators flag.
- **Orphan `$this->end_controls_tabs()` at [line 142](../../includes/Elements/Breadcrumbs.php#L142)** — no matching `start_controls_tabs()` call. Dead method invocation (Elementor's `end_controls_tabs()` is a no-op when called without an open tabs stack).
- **Home URL not escaped via `esc_url()`** in `eael_breadcrumbs()` ([lines 621, 625, 674](../../includes/Elements/Breadcrumbs.php#L621)) — uses raw `get_bloginfo('url')` concatenation. Defence-in-depth gap; not exploitable in practice since `get_bloginfo` returns sanitised URLs.
- **No `$show_current` control** — whether to render the current page name is hardcoded `= 1` ([line 611](../../includes/Elements/Breadcrumbs.php#L611)). Users cannot hide the trailing page name from the panel.
- **No `$show_on_home` control** — homepage breadcrumb visibility is hardcoded `= 1` ([line 608](../../includes/Elements/Breadcrumbs.php#L608)). On the homepage, the widget renders "Home" — possibly redundant.
- **Shop archive is not detected as WC context** — the WC detection at [line 728-730](../../includes/Elements/Breadcrumbs.php#L728) misses the shop page; users browsing `/shop/` get the non-WC breadcrumb branch.
- **`is_search()` / `is_day()` / etc. branches are unreachable for some configurations** — the `elseif` chain in `eael_breadcrumbs()` ([line 692-716](../../includes/Elements/Breadcrumbs.php#L692)) has an earlier `! is_single() && ! is_page() && get_post_type() !== 'post' && ! is_404()` branch that may catch traffic intended for the later branches.
- **`get_category_parents()` deprecated in newer WP versions** — used at [lines 629, 636, 683](../../includes/Elements/Breadcrumbs.php#L629); WP recommends `get_category_lineage()` since 6.6. Currently still functional but emits deprecation warnings on debug-mode sites.
- **`global $post` reliance** — `eael_breadcrumbs()` uses `global $post` for page-parent and attachment parent lookups; works on standard WP loops but may misbehave inside nested queries or templates that don't set `$post`.
- **WC breadcrumb's home URL is the EA home label** — `$args['home'] = $this->eael_breadcrumb_home_label()` passes the text, but WC's `home_url` filter may still override the URL the home label points to. No clear way for users to control the WC home URL from the EA panel.
- **No control for "Archive by category" / "Posts tagged" / "Articles posted by" prefixes** — these labels are hardcoded in PHP, only translatable via the text domain. Site builders cannot rename them per widget.
