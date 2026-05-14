# Table of Content Extension

> Auto-generated, sticky table of contents built from headings in the rendered page — controls live on the page (document) settings, the floating TOC widget is injected into `wp_footer`, and a per-site "global TOC" pattern lets one configured post act as the TOC source for the entire site or for a chosen post type.

**Class file:** [`includes/Extensions/Table_of_Content.php`](../../includes/Extensions/Table_of_Content.php) (1,310 lines)
**Slug:** `table-of-content` ([`config.php` line 1369](../../config.php#L1369))
**Public docs:** <https://essential-addons.com/elementor/docs/table-of-content/>
**Pro-shared:** Lite-owned. Pro does not ship a competing TOC; the same class serves both.

---

## Overview

Table of Content (TOC) is one of the larger EA extensions because it has three responsibilities packed into one slug:

1. **Controls authoring** — `Table_of_Content::register_controls()` adds a four-section block to the **document settings panel** (Settings tab + three Style tabs: EA TOC, EA TOC Header, EA TOC Body). All 60+ controls live here. There is no per-widget panel — TOC is a page-level feature.
2. **Per-page activation + Global TOC** — beyond the local `eael_ext_table_of_content` switcher, a second switcher (`eael_ext_toc_global`) promotes the current document to be the **site-wide TOC configuration source**. When global mode is on, the configuration is mirrored into `eael_global_settings` (option in `wp_options`) and other pages render the TOC from that snapshot instead of their own settings. `eael_ext_toc_global_display_condition` narrows the global scope to All / All Pages / All Posts / a specific CPT.
3. **Frontend render + JS scroll-spy** — the class does **not** render the TOC itself. Rendering happens later in [`Elements::render_global_html()`](../../includes/Traits/Elements.php#L432), which is hooked to `wp_footer` from [`Bootstrap.php:180`](../../includes/Classes/Bootstrap.php#L180). That method emits a `<div id="eael-toc">` skeleton; the frontend view JS ([`src/js/view/table-of-content.js`](../../src/js/view/table-of-content.js)) walks the DOM, finds matching heading tags, generates anchor IDs, and fills `#eael-toc-list` client-side.

This three-way split — controls in the extension class, render in a Bootstrap trait, list-building in view JS — is the key thing to understand before tracing any TOC bug. See [`docs/architecture/extensions.md`](../architecture/extensions.md) for the wider subsystem.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Table_of_Content.php`](../../includes/Extensions/Table_of_Content.php) (1,310 lines) | The class — constructor wires one hook; `register_controls()` builds 60+ controls in four control-section blocks |
| [`includes/Traits/Elements.php`](../../includes/Traits/Elements.php#L526) — TOC block at L526-651 inside `render_global_html()` | Reads local + global settings, emits the `<div id="eael-toc">` skeleton at `wp_footer`, enqueues `eael-table-of-content` style + script handles |
| [`includes/Traits/Elements.php#L717`](../../includes/Traits/Elements.php#L717) — `toc_global_css()` | When a page renders the **global** TOC, this method emits inline CSS (via `wp_add_inline_style('eael-table-of-content', ...)`) so the snapshot's colors / padding / typography apply on non-source pages |
| [`includes/Traits/Core.php#L206`](../../includes/Traits/Core.php#L206) | On editor save, mirrors all `eael_ext_toc_*` values into `eael_global_settings['eael_ext_table_of_content']` when the page is marked as the global source |
| [`includes/Traits/Core.php#L333`](../../includes/Traits/Core.php#L333) | On trash, clears the global slot if the trashed post was the source |
| [`includes/Classes/Asset_Builder.php#L358`](../../includes/Classes/Asset_Builder.php#L358) | Registers `eael-table-of-content` style + script handles (URL → `assets/front-end/css/view/table-of-content.min.css` and `.../js/view/table-of-content.min.js`). Not auto-enqueued — `render_global_html` enqueues them inline when a TOC is actually going to render. |
| [`src/css/view/table-of-content.scss`](../../src/css/view/table-of-content.scss) | Source SCSS — `.eael-toc`, `.eael-sticky`, `.collapsed`, `.eael-toc-right/left`, `.eael-toc-list-bar/arrow`, list separators, sticky behaviour. Compiles to `assets/front-end/css/view/table-of-content.min.css`. |
| [`src/js/view/table-of-content.js`](../../src/js/view/table-of-content.js) (413 lines) | Frontend behaviour — heading scan, hierarchical list build, slugified anchor IDs, scroll-spy / sticky toggle, auto-collapse, auto-highlight, mobile class toggle |
| [`src/js/edit/table-of-content.js`](../../src/js/edit/table-of-content.js) (153 lines) | Edit-time live preview — `elementor.settings.page.addChangeCallback(...)` for nine controls (position, list style, icon, title text, etc.). Only loaded inside the Elementor editor via config.php `'context' => 'edit'`. |
| [`config.php` line 1369](../../config.php#L1369) | Registry entry — only declares the edit JS (`assets/front-end/js/edit/table-of-content.min.js`). View CSS/JS are commented out because they are registered directly by `Asset_Builder` (see above). |
| [`includes/Classes/Helper.php#L110`](../../includes/Classes/Helper.php#L110) — `prevent_extension_loading()` | Suppresses `register_controls` on header / footer / single / page / search-results / error-404 / section templates so TOC controls don't pollute Elementor template editing |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Class instantiated | Yes (if slug enabled in `eael_save_settings`) | Yes (Pro reuses Lite's class) |
| All 60+ controls | Yes | Yes |
| Global TOC mode | Yes | Yes |
| Frontend rendering | Yes | Yes |
| JSON-LD schema | Implicit — `itemscope` / `itemtype="http://schema.org/ListItem"` / `itemprop="item"` microdata is emitted on each TOC item by the view JS ([`table-of-content.js:136-138`](../../src/js/view/table-of-content.js#L136)). No explicit JSON-LD script block. | Same |

TOC is fully Lite-owned. There is no Pro upsell inside `Table_of_Content.php`.

## Architecture

- **One Elementor hook, many controls.** The constructor wires exactly one action: `elementor/documents/register_controls` at priority 10 ([line 20](../../includes/Extensions/Table_of_Content.php#L20)). When the user opens any document's settings panel in the editor, Elementor fires that action, and `register_controls()` runs once per document.
- **`prevent_extension_loading` early return.** [Line 25](../../includes/Extensions/Table_of_Content.php#L25) calls `Helper::prevent_extension_loading(get_the_ID())`. That helper returns true when the current post is an Elementor **template** (header, footer, single, page, search-results, error-404, section). For templates, controls are not registered — the global TOC concept doesn't apply to templates and would create noise in the template editor.
- **Global TOC encoded as two booleans + one option.** `eael_ext_table_of_content` (per-document on/off) + `eael_ext_toc_global` (promote this document's settings to site-wide) drive the behaviour. On save, [`Core::save_global_values()` at line 206](../../includes/Traits/Core.php#L206) copies every relevant setting into `wp_options.eael_global_settings['eael_ext_table_of_content']` with `post_id` + `enabled` markers. Other pages then read from that snapshot.
- **Warning UI for the source post.** When the editor is **not** on the source post but a different post is the global source, the extension swaps the global-on switcher for a `RAW_HTML` warning with a link to edit the source post ([lines 59-74](../../includes/Extensions/Table_of_Content.php#L59)). This avoids two pages both declaring themselves the global source.
- **`eael_ext_toc_global_display_condition` enumerates dynamically.** The `Display On` select is seeded from `get_option('elementor_cpt_support')` ([lines 91-109](../../includes/Extensions/Table_of_Content.php#L91)). Built-in options are `all` / `post` / `page`; any CPT marked as Elementor-supported is appended (e.g. `product`).
- **Sticky behaviour is JS, not pure CSS.** `.eael-sticky` is added by `eaelTocSticky()` ([view JS line 211](../../src/js/view/table-of-content.js#L211)) when `window.pageYOffset >= stickyScroll` (default 200px, configurable per page). CSS then takes over with `position: fixed`. The `eael_ext_toc_sticky_offset` control writes `top: {{SIZE}}px !important` via the selector at [line 443](../../includes/Extensions/Table_of_Content.php#L443).
- **Auto-collapse is event-driven, not transition-based.** A document-level click handler ([view JS line 319](../../src/js/view/table-of-content.js#L319)) checks for `.eael-toc-auto-collapse.eael-sticky:not(.collapsed)` clicks outside the TOC and toggles `.collapsed` on the wrapper. The wrapper's CSS then hides body+header and shows the `.eael-toc-button`.
- **Auto-highlight is viewport-spy, not IntersectionObserver.** `highlightCurrentHeading()` ([view JS line 231](../../src/js/view/table-of-content.js#L231)) walks every `#eael-toc-list .eael-toc-link`, resolves each to its heading element, calls `isElementInViewport(...)` (vanilla `getBoundingClientRect` check), and adds `.eael-highlight-active`. With `eael_ext_toc_auto_highlight_single_item_only=yes`, the loop breaks after the first match.

## Render Behavior

### Footer HTML (emitted by `Elements::render_global_html`)

```html
<div id="eael-toc"
     class="eael-toc eael-toc-disable eael-toc-left eael-toc-auto-collapse collapsed"
     data-eaelTocTag="h2,h3,h4,h5,h6"
     data-contentSelector=""
     data-excludeSelector=""
     data-stickyScroll="200"
     data-titleUrl="false"
     data-page_offset="120">
  <div class="eael-toc-header">
    <span class="eael-toc-close">×</span>
    <h2 class="eael-toc-title">Table of Contents</h2>
  </div>
  <div class="eael-toc-body">
    <ul id="eael-toc-list" class="eael-toc-list  eael-toc-list-none eael-toc-collapse eael-toc-bullet"></ul>
  </div>
  <button class="eael-toc-button"><i class="fas fa-list"></i><span>Table of Contents</span></button>
</div>
```

Modifier classes added by PHP:

- `eael-toc-disable` — initial state; removed by JS once at least one heading is found
- `eael-toc-left` / `eael-toc-right` — column position
- `eael-bottom-to-top` — close-button text orientation
- `eael-toc-auto-collapse collapsed` — start collapsed
- `eael-toc-mobile-hide` — hide on viewports ≤991px
- `eael-toc-top` / `eael-toc-bottom` — mobile dock position
- `eael-toc-global` — set only when this page is rendering from the global snapshot rather than local settings

Modifier classes on the `<ul>`:

- `eael-toc-list-{none|arrow|bar}` — indicator style
- `eael-toc-collapse` — collapse sub-headings by default
- `eael-toc-number` / `eael-toc-bullet` — list-item marker style
- `eael-toc-word-wrap` — wrap long heading text
- `eael-toc-auto-highlight` / `eael-toc-highlight-single-item` — scroll-spy modes

### Anchor ID generation (view JS)

```js
function eael_build_id(showTitle, title) {
    if (showTitle == "true" && title != "") {
        return title.toString().toLowerCase()
            .normalize("NFD")
            .trim()
            .replace(/[^a-z0-9 -]/g, "")  // strip non-ASCII
            .replace(/\s+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "")
            .replace(/-+/g, "-");
    }
    return "eael-table-of-content";  // fallback when "Heading Text in URL" = off
}
```

The slug is prefixed with the heading's index inside the document: `el.id = listIndex + "-" + eael_build_id(...)`. So `<h2>Hello World</h2>` as the third heading becomes `id="2-hello-world"`. The numeric prefix guarantees uniqueness even when two headings share the same slug — there is no explicit dedupe / collision-suffix step.

### Microdata (schema)

Each `<li>` whose `parentLevel` matches the base level (or where the level resets up) gets:

```html
<li itemscope itemtype="http://schema.org/ListItem" itemprop="itemListElement">
  <a class="eael-toc-link" itemprop="item" href="#3-section-title">
    <span>Section Title</span>
  </a>
</li>
```

That is the entire structured-data surface — there is no `<script type="application/ld+json">` block, no FAQ schema, no Article schema. SEO tools that parse microdata pick up the list; others don't.

### Heading discovery

```js
var mainSelector = document.querySelectorAll(selector);  // resolved via eael_toc_check_content()
for (var j = 0; j < mainSelector.length; j++) {
    allSupportTag = [
        ...allSupportTag,
        ...mainSelector[j].querySelectorAll(supportTag),  // e.g. "h2,h3,h4,h5,h6"
    ];
}
allSupportTag = Array.from(new Set(allSupportTag));
```

The content-selector resolution falls back through `.site-content` → `.elementor-inner` → `#site-content` → `.site-main` ([view JS line 305](../../src/js/view/table-of-content.js#L305)) when the user hasn't set a custom selector. Hardcoded exclude zones strip out `.ab-top-menu, .page-header, .site-title, nav, footer, .comments-area, .woocommerce-tabs, .related.products, .blog-author, .post-author, .post-related-posts, .eael-toc-header` ([view JS line 90](../../src/js/view/table-of-content.js#L90)).

## Asset Dependencies

### CSS

| Source | Output | Handle | When loaded |
| ------ | ------ | ------ | ----------- |
| [`src/css/view/table-of-content.scss`](../../src/css/view/table-of-content.scss) | `assets/front-end/css/view/table-of-content.min.css` | `eael-table-of-content` | Inline `wp_enqueue_style('eael-table-of-content')` at [`Elements.php:645`](../../includes/Traits/Elements.php#L645) — only when `render_global_html` decides a TOC must render |

Note that `config.php` line 1369-1387 has the CSS block commented out — it is *not* dependency-driven by Asset_Builder. Registration happens in `Asset_Builder::frontend_asset_load` ([`Asset_Builder.php:358`](../../includes/Classes/Asset_Builder.php#L358)). The handle exists from `wp_enqueue_scripts` time onward; the `wp_enqueue_style` call inside `render_global_html` (which runs on `wp_footer`) is what actually loads the file.

### JS

| Source | Output | Handle | Context | When loaded |
| ------ | ------ | ------ | ------- | ----------- |
| [`src/js/view/table-of-content.js`](../../src/js/view/table-of-content.js) | `assets/front-end/js/view/table-of-content.min.js` | `eael-table-of-content` (script) | view | Same path as CSS — `wp_enqueue_script` inside `render_global_html` |
| [`src/js/edit/table-of-content.js`](../../src/js/edit/table-of-content.js) | `assets/front-end/js/edit/table-of-content.min.js` | (Asset_Builder-generated) | **edit** | Per `config.php` line 1381-1384 — only inside the Elementor editor iframe |

`config.php` declares only the **edit** JS as a registry dependency. The view CSS + view JS are owned by `Asset_Builder` registration (see the file table above) — they sit in WordPress's script/style registry but stay un-enqueued until `render_global_html` enqueues them.

## Hook Timing

### Elementor hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `elementor/documents/register_controls` | 10 | `Table_of_Content::register_controls` | Add the four TOC control sections to every document's panel (except templates filtered by `prevent_extension_loading`) |

### WordPress hooks consumed (in collaborating traits)

| Hook | Priority | Owner | Purpose |
| ---- | -------- | ----- | ------- |
| `wp_footer` | 10 | [`Bootstrap.php:180`](../../includes/Classes/Bootstrap.php#L180) → `Elements::render_global_html` | Emit the TOC skeleton; enqueue style + script handles inline |
| `elementor/editor/after_save` | (saves) | `Core::save_global_values` | Mirror per-page TOC settings into `eael_global_settings` when this post is the global source |

### Hooks emitted

The extension itself does not call `do_action` or `apply_filters`. The wider TOC flow does call:

- `eael/extentions/global_settings` (note the `extentions` typo is intentional in the source) in [`Core.php:297`](../../includes/Traits/Core.php#L297) — filter point covering the entire `eael_global_settings` payload, not TOC-specific.

If you need TOC-specific extensibility today, you have to hook one of those wider points or modify the view JS via a child theme.

## Configuration & Extension Points

### Document-level controls (Settings tab → "Table of Contents")

Enable + global enable:

| Control ID | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_table_of_content` | SWITCHER | `no` | Master switch — TOC renders only when this is `yes` (or when a global TOC matches this URL) |
| `eael_ext_toc_global` | SWITCHER | `no` | Promote this page's settings to be the site-wide TOC configuration |
| `eael_ext_toc_global_display_condition` | SELECT | `all` | Where the global TOC shows up: `all`, `post`, `page`, or any CPT slug from `elementor_cpt_support` |
| `eael_ext_toc_has_global` | HIDDEN | (derived) | Internal flag — true when `eael_global_settings['eael_ext_table_of_content']['enabled']` is set on another post |
| `eael_ext_toc_global_warning_text` | RAW_HTML | — | The "another post owns the global TOC" warning, shown when `has_global` is true |

Heading + selector tabs (Include / Exclude):

| Control ID | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_toc_title` | TEXT | `Table of Contents` | Header label; AI + dynamic enabled |
| `eael_ext_toc_title_tag` | CHOOSE | `h2` | Wrapping HTML tag for the title — validated through `Helper::eael_validate_html_tag()` |
| `eael_ext_toc_supported_heading_tag` | SELECT2 (multi) | `[h2..h6]` | Which heading tags qualify; passed as `data-eaelTocTag` |
| `eael_ext_toc_content_selector` | TEXT | (empty → JS fallback) | Custom CSS selector to scope heading discovery |
| `eael_toc_exclude_selector` | TEXT | (empty) | Comma-separated selectors to skip — implemented as `$(el).closest(excludes).length` in JS |

Behaviour switches:

| Control ID | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_toc_collapse_sub_heading` | SWITCHER | `yes` | Hide nested levels until the parent is clicked |
| `eael_ext_toc_use_title_in_url` | SWITCHER | `no` | Use slugified heading text in the anchor IDs (otherwise the fallback `eael-table-of-content` per index) |
| `eael_ext_toc_word_wrap` | SWITCHER | `no` | Add `.eael-toc-word-wrap` to wrap long headings |
| `eael_ext_toc_auto_collapse` | SWITCHER | `yes` | Click outside collapses the sticky TOC |
| `eael_ext_toc_auto_highlight` | SWITCHER | `no` | Add `.eael-highlight-active` to the heading nearest the viewport |
| `eael_ext_toc_auto_highlight_single_item_only` | SWITCHER | `yes` | When auto-highlight is on, highlight only the first match; otherwise highlight every visible heading |
| `eael_ext_toc_hide_in_mobile` | SWITCHER | `no` | Suppress TOC on viewports ≤991px |

Sticky / positioning:

| Control ID | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_toc_max_height` | SLIDER (vh) | 50vh | Sticky body cap |
| `eael_ext_toc_sticky_scroll` | SLIDER (px) | 200 | Scroll threshold above which the TOC pins; passed as `data-stickyScroll` |
| `eael_ext_toc_sticky_offset` | SLIDER (px) | 200 | `top: …!important` for the sticky wrapper |
| `eael_ext_toc_main_page_offset` | SLIDER (px) | 120 | JS offset for anchor scroll (subtracted from target.top to land below a fixed header) |
| `eael_ext_toc_sticky_z_index` | SLIDER (px) | 9999 | `z-index` for the wrapper |
| `eael_ext_toc_position` | SELECT | `left` | Desktop column side |
| `eael_ext_toc_position_mobile` | SWITCHER | (none) | Show on mobile dock |
| `eael_ext_toc_position_mobile_top_bottom` | SELECT | `top` | Mobile dock side |
| `eael_ext_toc_position_mobile_top_offset` / `_bottom_offset` | SLIDER (px) | 50 | Mobile dock offset |

Style tabs (EA TOC, EA TOC Header, EA TOC Body) hold ~30 visual controls — width, border, box-shadow, border radius, list-icon, bullet size, header bg/text, close button styling, indicator color/size/position, separator style, etc. These are pure cosmetic selectors and don't change the behaviour described above.

### Filter / action surface

There is **no dedicated TOC filter or action surface today**. Extensibility points:

- `eael/extentions/global_settings` ([`Core.php:297`](../../includes/Traits/Core.php#L297)) — filter the merged `eael_global_settings` array. You can rewrite the TOC snapshot before it's persisted to `wp_options`.
- `eael/registered_extensions` (Bootstrap) — remove `'table-of-content'` from the registry to disable the extension entirely.
- DOM-level customisation — the view JS is unscoped enough that a small JS snippet can post-process `#eael-toc-list` after page load.

## Customization Recipes

### Recipe 1 — Force-exclude a CSS region from heading discovery

```php
// Add to your child theme's functions.php or a small mu-plugin.
// Appends ".my-aside" to every TOC's exclude selector. Useful when you can't
// teach editors to fill in the per-page Exclude field.
add_filter( 'eael/extentions/global_settings', function ( $global, $document, $post_id ) {
    if ( isset( $global['eael_ext_table_of_content']['eael_toc_exclude_selector'] ) ) {
        $existing = $global['eael_ext_table_of_content']['eael_toc_exclude_selector'];
        $global['eael_ext_table_of_content']['eael_toc_exclude_selector'] = trim(
            $existing . ',.my-aside',
            ','
        );
    }
    return $global;
}, 10, 3 );
```

Filters the global-mode snapshot only. For per-page TOCs, edit the field in the document settings panel instead.

### Recipe 2 — Suppress TOC for one specific URL

The cleanest way is to disable the master switch on that page. If the TOC is coming from the global source and you can't edit the source, you can de-register the wp_footer rendering for that request:

```php
add_action( 'template_redirect', function () {
    if ( is_singular( 'landing-page' ) ) {
        remove_action( 'wp_footer', [ \Essential_Addons_Elementor\Classes\Bootstrap::instance(), 'render_global_html' ] );
    }
} );
```

This removes the entire `render_global_html` block — also kills Reading Progress, Scroll to Top, and Custom Cursor for that URL. Use sparingly.

### Recipe 3 — Override the anchor ID slug

The view JS is the only slug producer; PHP doesn't run it. Drop in your own normaliser before `eael_toc_content` runs:

```js
// /wp-content/themes/your-child/assets/toc-slug.js
// Enqueue after 'eael-table-of-content' script handle.
jQuery( document ).ready( function () {
    // Re-slugify every heading produced by the TOC.
    document.querySelectorAll( '.eael-heading-content' ).forEach( function ( el ) {
        var base = el.id.split( '-' ).slice( 1 ).join( '-' ); // drop numeric prefix
        el.id = el.id.split( '-' )[0] + '-my-prefix-' + base;
    } );
    // Refresh links inside #eael-toc-list to match.
    document.querySelectorAll( '#eael-toc-list a.eael-toc-link' ).forEach( function ( a, i ) {
        var heading = document.querySelectorAll( '.eael-heading-content' )[i];
        if ( heading ) a.href = '#' + heading.id;
    } );
} );
```

Only useful when you control the page template; for editor-distributed sites, a PHP-side filter would be safer if it existed (today it doesn't).

### Recipe 4 — Add a CPT to the "Display On" dropdown

Mark the CPT as Elementor-supported:

```php
add_action( 'admin_init', function () {
    $cpts = (array) get_option( 'elementor_cpt_support', [] );
    if ( ! in_array( 'docs', $cpts, true ) ) {
        $cpts[] = 'docs';
        update_option( 'elementor_cpt_support', $cpts );
    }
} );
```

The TOC extension reads `elementor_cpt_support` at control-registration time ([`Table_of_Content.php:91-109`](../../includes/Extensions/Table_of_Content.php#L91)), so the CPT appears in the dropdown the next time the editor loads. The match in render is `get_post_type() === $toc_global_display_condition` ([`Elements.php:617`](../../includes/Traits/Elements.php#L617)).

## Common Issues

### TOC appears but the list is empty

- **Symptom:** `#eael-toc` is in the DOM but `#eael-toc-list` has no `<li>`s; the wrapper keeps the `.eael-toc-disable` class.
- **Likely causes:**
  1. The content selector resolved to a container that doesn't include the target headings. JS fallback order is `.site-content` → `.elementor-inner` → `#site-content` → `.site-main`. Themes that don't expose any of these break discovery.
  2. The headings are inside a hardcoded exclude zone (`.ab-top-menu, .page-header, .site-title, nav, footer, .comments-area, .woocommerce-tabs, .related.products, .blog-author, .post-author, .post-related-posts, .eael-toc-header`). The exclude list is hardcoded in [view JS line 90](../../src/js/view/table-of-content.js#L90).
  3. The selected "Supported Heading Tag" doesn't match what's on the page.
- **Diagnose:** In browser devtools console: `document.querySelectorAll('.elementor-inner h2, .elementor-inner h3')`. If empty, the selector is the bug.
- **Fix:** Set a custom Content Selector on the document (e.g. `main` or `.entry-content`). For exclude-zone collisions, the only fix today is to remove the conflicting class from the theme (the hardcoded exclude is not filterable).

### Anchor links scroll past the heading

- **Symptom:** Clicking a TOC item lands halfway down the heading or just below it.
- **Cause:** The `offsetSpan` calculation in the view JS uses `$("header").height()` as a default. Themes that don't have a `<header>` or whose header is hidden may produce undefined heights; the fallback is 120px.
- **Fix:** Set `eael_ext_toc_main_page_offset` to match your fixed-header height. The control writes to `data-page_offset` which the view JS reads at [line 10](../../src/js/view/table-of-content.js#L10).

### Two pages each claim to be the global TOC

- **Symptom:** Both pages have `eael_ext_toc_global = yes`; the most-recently-saved one wins because each save overwrites `eael_global_settings['eael_ext_table_of_content']`.
- **Diagnose:** `wp option get eael_global_settings | grep post_id` — note which `post_id` is currently stored.
- **Fix:** Open the *losing* page in Elementor, turn the global switcher off, save. The warning UI ([`Table_of_Content.php:59`](../../includes/Extensions/Table_of_Content.php#L59)) intentionally prevents the next person from flipping it on without seeing the conflict.

### TOC styles look broken on non-source pages (global mode)

- **Symptom:** Global TOC shows up on pages other than the source post, but background colours / paddings / typography are wrong.
- **Cause:** Non-source pages render the snapshot via `toc_global_css()` ([`Elements.php:717`](../../includes/Traits/Elements.php#L717)), which emits a hand-built CSS string into `wp_add_inline_style('eael-table-of-content', ...)`. If any setting key was missing in the snapshot (e.g. the source post was saved before a TOC control was added in an EA version bump), the generated CSS has empty values.
- **Fix:** Open the source post in Elementor and re-save. `Core::save_global_values` will rewrite the snapshot with the current control set.

### Style updates from the source page don't propagate

- **Symptom:** You changed a colour on the source post, but other pages still show the old colour.
- **Cause:** Global snapshot is only re-written on `elementor/editor/after_save`. Browser HTTP cache or page-cache plugins serve the old `wp_add_inline_style` payload.
- **Diagnose:** View page source on a non-source page, search for `wp_add_inline_style` / inline `<style>` near the TOC; compare with the colours you set.
- **Fix:** Clear page cache (and asset cache if any). The inline style is reissued on every page load, so no extra step is required after cache clear.

### `eael-table-of-content` handle is registered but the CSS never loads

- **Symptom:** TOC visible but unstyled. View source shows `#eael-toc` but no `<link rel="stylesheet" href=".../table-of-content.min.css">`.
- **Cause:** `wp_enqueue_style('eael-table-of-content')` runs inside `render_global_html` (on `wp_footer`). If your theme prints stylesheets only in `<head>`, the footer enqueue still emits the link tag — just later in the DOM. Some aggressive optimisation plugins strip late stylesheets.
- **Fix:** Check your optimisation plugin's "exclude from concatenation" list — add `table-of-content`.

## Debugging Guide

1. **Confirm the extension is active.** `wp option get eael_save_settings | grep table-of-content` should show `1`. If not, the user disabled it in EA settings or the Setup Wizard.
2. **Confirm controls register.** Open any page in Elementor → page settings (gear icon) → look for "Table of Contents" section. If missing, either (a) the post is an Elementor template type filtered by `prevent_extension_loading`, or (b) `Table_of_Content` didn't instantiate. Verify with `error_log( 'TOC constructed' )` at the top of the constructor.
3. **Confirm `render_global_html` runs.** `error_log( 'render_global_html in TOC branch' )` at [`Elements.php:527`](../../includes/Traits/Elements.php#L527). If absent, either the page isn't `is_singular() || is_archive() || is_home() || is_front_page() || is_search()` (see [line 437](../../includes/Traits/Elements.php#L437)), or `eael_save_settings['table-of-content']` is falsy.
4. **Confirm the HTML emitted.** View source, look for `<div id="eael-toc"`. If missing, `$should_render_toc` is false — check the `eael_ext_toc_global_display_condition` branch ([line 609-624](../../includes/Traits/Elements.php#L609)).
5. **Confirm asset enqueue.** Network tab → `table-of-content.min.js` and `table-of-content.min.css` should both load. If only one, check that the matching `wp_enqueue_*` line at [`Elements.php:645-646`](../../includes/Traits/Elements.php#L645) is hit.
6. **Inspect data attributes on `#eael-toc`.** Open the wrapper in devtools. `data-eaeltoctag` must match the headings on the page. `data-contentselector` empty is OK (JS will fallback). `data-stickyscroll` = pixels.
7. **Console-test heading scan.** Paste into devtools:
   ```js
   var t = document.getElementById('eael-toc');
   document.querySelectorAll(t.dataset.contentselector || '.site-content, .elementor-inner').length
   ```
   Then narrow to `t.dataset.eaeltoctag.split(',').join(',')` and confirm matches.
8. **For global mode**, dump the snapshot: `wp option get eael_global_settings --format=json | jq .eael_ext_table_of_content`. Confirm `post_id` and `enabled`. Confirm all expected keys are present.

## Architecture Decisions

### Render via `wp_footer` instead of an Elementor element

- **Context:** TOC is page-wide, not section-wide. It needs to know the entire page's headings, which means waiting until after the main content has rendered.
- **Decision:** Add the skeleton on `wp_footer` (priority 10) inside the trait-owned `render_global_html`. JS then walks the DOM (already complete) and fills the list.
- **Alternatives rejected:** Render at a specific Elementor hook (e.g. `elementor/frontend/before_render`) — too early; content not yet emitted. Render at the document's first element — coupling to one specific element type is fragile.
- **Consequences:** TOC always renders after the page body, so the JS scan is reliable. Cost: a single `wp_footer` callback handles three extensions (TOC, Reading Progress, Scroll to Top, Custom Cursor). Errors in one can affect the others.

### Global TOC as a snapshot, not a live join

- **Context:** When one page configures a site-wide TOC, every other page needs the same colours / behaviour. We could (a) query the source post's settings on every request, or (b) copy them into a single option at save time.
- **Decision:** Copy at save time into `wp_options.eael_global_settings`. Two updates: option (very fast read on subsequent requests), with `Core::save_global_values_trashed_post` ([line 311](../../includes/Traits/Core.php#L311)) cleaning up when the source is trashed.
- **Alternatives rejected:** Live join with `Plugin::$instance->documents->get($source_post_id)` on every visit — adds a DB round-trip and a full Elementor document hydration per page view.
- **Consequences:** Snapshot can drift if `eael_global_settings` is hand-edited or if a control is added in an EA version bump without re-saving the source post. The "TOC styles look broken on non-source pages" common issue (above) follows from this.

### View JS generates anchor IDs (PHP doesn't)

- **Context:** The TOC needs each heading to have an `id` it can link to. We could (a) inject IDs server-side by parsing rendered Elementor output, or (b) inject IDs client-side by scanning the live DOM.
- **Decision:** Client-side. The view JS calls `el.id = listIndex + "-" + eael_build_id(...)` ([view JS line 43](../../src/js/view/table-of-content.js#L43)).
- **Alternatives rejected:** Server-side parsing — would need a DOMDocument pass over the entire post content, plus mark-up rewrite via output buffering. Brittle with dynamic content.
- **Consequences:** Anchors are not crawlable as static page sources; SEO tools that only parse HTML see un-id'd headings. Microdata still emits via JS, but JSON-LD style schema is impractical from JS in a way that crawlers honour reliably.

### Hardcoded exclude zone in view JS

- **Context:** Many themes wrap headings in navigation, related-posts widgets, comment areas. Including those in the TOC produces junk entries.
- **Decision:** [View JS line 90](../../src/js/view/table-of-content.js#L90) hardcodes `.ab-top-menu, .page-header, .site-title, nav, footer, .comments-area, .woocommerce-tabs, .related.products, .blog-author, .post-author, .post-related-posts, .eael-toc-header` as always-excluded.
- **Alternatives rejected:** PHP filter to extend the exclude list (today there is none); rely entirely on the per-page Exclude Selector (would require every editor to know about it).
- **Consequences:** Themes that use those class names for legitimate content (rare but possible) silently lose headings from the TOC. No PHP filter to override.

### No JSON-LD schema, only microdata

- **Context:** Schema.org supports TOC-style structured data via `ItemList` / `ListItem`. Two ways to express it: JSON-LD (Google-preferred) or microdata.
- **Decision:** Microdata on `<li itemtype="http://schema.org/ListItem">` plus `<a itemprop="item">` — emitted by view JS as the TOC list is built.
- **Alternatives rejected:** JSON-LD — would have to be emitted by the same JS, but search engines have inconsistent treatment of JS-injected JSON-LD.
- **Consequences:** Microdata is valid and Google reads it, but it's a less-common form. Sites that audit structured data with Lighthouse or Rich Results Test may flag the TOC as having "no JSON-LD" — that's by design.

## Known Limitations

- **Single Elementor hook.** `elementor/documents/register_controls` only — there's no element-level control. A widget-specific TOC (e.g. "TOC for just this section") would need its own widget.
- **No FAQ / Article schema integration.** Microdata only. The TOC doesn't emit `BreadcrumbList`, `WebPage`, or `Article` JSON-LD.
- **Anchor IDs collide with the post's own IDs.** `el.id = listIndex + "-..."` overwrites any existing `id` on a heading. If your content has hand-rolled anchors, they will be lost.
- **No PHP filter for the heading exclude zone.** Only the per-page `eael_toc_exclude_selector` is configurable; the hardcoded list of exclude zones in JS is not.
- **Sticky JS uses `pageYOffset`, not `IntersectionObserver`.** On long scrolls in modern browsers this is fine, but it doesn't benefit from observer-based off-main-thread optimisation. Pinning fires on every scroll event.
- **Auto-highlight breaks at the bottom of the page.** When the user scrolls past the last heading, `isElementInViewport` returns false for every link and none are highlighted. There's no "stick to last" fallback.
- **`eael_ext_toc_max_height` uses `vh` only.** Other units would require schema changes.
- **Global snapshot stores presentation + behaviour, not the source post's content.** If the source post becomes private / draft, the snapshot still serves data — but the warning UI link breaks because `get_post_status() != 'publish'` is checked in `register_controls` and rendering ([line 621](../../includes/Traits/Elements.php#L621)).
- **No filter to wrap or post-process the TOC HTML.** `render_global_html` builds the string and prints it directly; the only override path is `remove_action('wp_footer', ...)`.
- **Mobile detection is width-only.** `window.innerWidth <= 991` ([view JS line 403](../../src/js/view/table-of-content.js#L403)) — does not consider user-agent or touch capability. Tablets in landscape look like desktops here.

## Recent Significant Changes

No documented breaking changes in the recent v6.x line. Notable past additions visible in the controls list:

- `eael_ext_toc_auto_highlight_single_item_only` — added to refine scroll-spy behaviour
- `eael_ext_toc_main_page_offset` — added to compensate for themes with tall fixed headers
- AI-control attributes on TEXT controls (title, content selector, exclude selector) — added as part of the EA AI integration rollout

Format for future entries: `version — description (#card)`.

## Cross-References

- **Subsystem doc:** [`docs/architecture/extensions.md`](../architecture/extensions.md) — registration loop, `'context'` semantics, `eael_save_settings`
- **Render-phase doc:** [`docs/architecture/asset-loading.md`](../architecture/asset-loading.md) — how `Asset_Builder` registers the `eael-table-of-content` handle even though the dependency block in `config.php` only declares the edit JS
- **Editor-data doc:** [`docs/architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — how `get_settings_for_display()` resolves the per-document overrides
- **Sibling extension doc:** [`docs/extensions/promotion.md`](promotion.md) — the canonical example for this folder's format
- **Public docs:** <https://essential-addons.com/elementor/docs/table-of-content/>
- **Skills:** [`.claude/skills/debug-widget`](../../.claude/skills/debug-widget/SKILL.md) — applicable when triaging TOC bugs because the path crosses three files (extension class, trait, view JS)
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md), [`.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
