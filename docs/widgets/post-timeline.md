# Post Timeline Widget

> WP_Query-driven vertical timeline rendering of posts — alternating left/right entries via CSS `:nth-child(2n)`, bullet markers on a centre line, with arrow callouts pointing toward each post card. Skin templates per preset (same template-include pattern as Post_Grid). No widget-specific JS — uses only the shared `load-more.js` for AJAX pagination. Standard `eael_section_pro` upsell injected via `HelperClass::go_premium()`.

**Class file:** [`includes/Elements/Post_Timeline.php`](../../includes/Elements/Post_Timeline.php)
**Slug:** `post-timeline` (widget id `eael-post-timeline`)
**Public docs:** <https://essential-addons.com/elementor/docs/post-timeline/>
**Pro-shared:** ❌ Lite-only widget. No Pro reference, no widget-specific Pro extension. Standard `eael_section_pro` upsell panel via [`HelperClass::go_premium($this)`](../../includes/Classes/Helper.php#L276) when Pro is inactive.

---

## Overview

Post Timeline renders WP_Query results as a vertical chronological list with cards alternating left and right of a centre line. Each card has a bullet on the centre line and an arrow callout pointing toward it; the alternating layout is pure CSS (`:nth-child(2n)` selectors) — no JS needed for the timeline visual.

Architecturally identical to Post_Grid: skin templates in `includes/Templates/Post-Timeline/` get `include`d inside the WP_Query loop. Pagination uses the shared `load-more.js` + `wp_ajax_load_more` infrastructure documented in [`post-grid.md`](post-grid.md#architecture). Differences from Post_Grid: no Isotope (timeline is sequential), no widget-specific JS file, simpler render method, no editor preview inline script.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Skin templates (multiple presets) | ✅ | ✅ |
| Alternating left/right timeline layout | ✅ | ✅ |
| WP_Query backend with full filters (post type, taxonomy, author, date, orderby) | ✅ | ✅ |
| Load More button + Infinity Scroll | ✅ | ✅ |
| Title/image link controls (nofollow, target=_blank) | ✅ | ✅ |
| `eael_section_pro` upsell panel (via `go_premium()` helper) | shown | hidden |

The widget has no widget-specific Pro extension surface. Pro neither extends nor references it.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Post_Timeline.php`](../../includes/Elements/Post_Timeline.php) | PHP widget class — controls (uses Helper trait for shared sections), render with template-include loop |
| [`includes/Templates/Post-Timeline/`](../../includes/Templates/Post-Timeline/) | Skin template directory — one PHP file per preset, included inside WP_Query loop |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L276) | `HelperClass::go_premium($wb)` — standard upsell section emitter |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `print_load_more_button()` (shared) |
| [`includes/Traits/Ajax_Handler.php`](../../includes/Traits/Ajax_Handler.php) | `ajax_load_more()` AJAX handler (shared with Post_Grid, Product_Grid, etc.) |
| [`src/css/view/post-timeline.scss`](../../src/css/view/post-timeline.scss) | Source styles — alternating layout via `:nth-child(2n)`, bullets, arrow callouts |
| [`src/js/view/load-more.js`](../../src/js/view/load-more.js) | Shared AJAX pagination — no widget-specific JS for Post_Timeline |
| [`config.php`](../../config.php#L50) entry `'post-timeline'` | Asset declaration: load-more.min.css + post-timeline.min.css + load-more.min.js (no widget-specific JS) |

## Architecture

- **Template-include rendering** — same pattern as Post_Grid: `get_template($preset)` returns a PHP file in `includes/Templates/Post-Timeline/`; `include()`d inside the WP_Query loop ([line 787-790](../../includes/Elements/Post_Timeline.php#L787)). Adding a new skin = drop a new template file.
- **Two shared control sets injected via `eael/controls/*` action hooks** — `do_action('eael/controls/query', $this)` and `do_action('eael/controls/layout', $this)` ([lines 76-77](../../includes/Elements/Post_Timeline.php#L76)). The `layout` action is one Post_Grid does NOT use — provides the skin-picker section and column layout controls. Both registered by Lite's [Bootstrap::layout()](../../includes/Classes/Bootstrap.php#L186) — internal code reuse, not Pro extension. See [`post-grid.md § Hooks & Filters`](post-grid.md#hooks--filters) for the four-action chain detail.
- **`HelperClass::go_premium($this)` for the standard upsell** ([line 110-112](../../includes/Elements/Post_Timeline.php#L110)) — a helper-function variant of the inline upsell pattern documented in [`_patterns.md § eael_section_pro standard upsell panel`](_patterns.md#eael_section_pro-standard-upsell-panel). Functionally identical: emits the same `eael_section_pro` section + `eael_control_get_pro` choose control when Pro is inactive.
- **No widget-specific JS** — the only loaded JS is the shared `load-more.js`. Timeline visual is pure CSS via `:nth-child(2n)` selectors that flip alternating items left-to-right. No `frontend/element_ready` handler registered for this widget.
- **Alternating layout in SCSS** — `.eael-timeline-post:nth-child(2n)` flips bullet alignment ([line 29 of SCSS](../../src/css/view/post-timeline.scss#L29)), card alignment ([line 73](../../src/css/view/post-timeline.scss#L73)), and arrow direction ([line 90](../../src/css/view/post-timeline.scss#L90)). Even-numbered cards (2nd, 4th, …) appear on the opposite side from odd-numbered ones.
- **Shared Load More infrastructure** — `load-more.js` document-delegation `click` handler + `wp_ajax_load_more` AJAX endpoint. See [`post-grid.md § Architecture`](post-grid.md#architecture) for the full AJAX dispatch chain. Post_Timeline's class name is sent in `data-class` so the handler dispatches to the correct render path.
- **Settings remap from `excerpt_expanison_indicator` → `expanison_indicator`** ([line 754](../../includes/Elements/Post_Timeline.php#L754)) — the typo `expanison` (should be `expansion`) is the original control id; render assigns it to a shorter key for template files to consume. Same typo as Post_Grid.

## Render Output

```html
<div id="eael-post-timeline-<widget-id>"
     class="eael-post-timeline
            timeline-layout-<preset>
            eael-post-timeline-arrow-<alignment>">
  <div class="eael-post-timeline eael-post-appender eael-post-appender-<widget-id>">

    <!-- For each WP_Query post — repeated; SCSS :nth-child(2n) flips even ones: -->
    <div class="eael-timeline-post">
      <div class="eael-timeline-bullet">
        <!-- bullet on centre line -->
      </div>
      <div class="eael-timeline-post-inner">
        <!-- Template output (varies by preset). Typical: -->
        <div class="eael-timeline-post-image">
          <img src="..." alt="...">
        </div>
        <h2 class="eael-timeline-post-title"><a href="...">Title</a></h2>
        <div class="eael-timeline-post-meta">Author • Date</div>
        <div class="eael-timeline-post-content">Excerpt...</div>
        <a class="eael-readmore-btn" href="...">Read More</a>
      </div>
    </div>
    …
  </div>
</div>

[?] <!-- Load More button or Infinity Scroll wrapper — same as Post_Grid -->
<div class="eael-load-more-button-wrap [eael-infinity-scroll]"
     data-offset="-200">
  <button class="eael-load-more-button"
          data-widget="<widget-id>"
          data-class="Essential_Addons_Elementor\Elements\Post_Timeline"
          data-args="<url-encoded args>"
          …>
    <span class="eael_load_more_text">Load More</span>
  </button>
</div>
```

Notes:

- Root has three classes: `eael-post-timeline` + `timeline-layout-<preset>` + `eael-post-timeline-arrow-<alignment>`. The latter two carry the user's skin selection and arrow-callout direction.
- Inner `.eael-post-timeline` carries `eael-post-appender` — AJAX-appended posts land here, same convention as Post_Grid.
- Alternating layout is CSS-only: `.eael-timeline-post:nth-child(2n)` triggers all the flip rules.
- The arrow callout (`::after` pseudo-element on `.eael-timeline-post-inner`) points right on odd cards, left on even cards.
- Load More wrapper is identical to Post_Grid — same `data-class` dispatch chain, same `ajax_load_more` endpoint.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Post_Timeline.php#L69) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| Content → Query | various | — | Content → Query | post_type, taxonomy, terms, author, date, orderby — **injected via `do_action('eael/controls/query', $this)`** |
| Content → Layout | various | — | Content → Layout | Skin/preset picker, posts-per-page, columns — **injected via `do_action('eael/controls/layout', $this)`** |
| `timeline_link_nofollow` | SWITCHER | empty | Content → Links | Adds `rel="nofollow"` to post links |
| `timeline_link_target_blank` | SWITCHER | empty | Content → Links | Adds `target="_blank"` to post links |
| `eael_timeline_display_overlay` | SWITCHER | `yes` | Style → Timeline Style | Renders overlay over post image |
| `eael_timeline_arrow_alignment` | SELECT | (default) | Style → Timeline Style | Arrow-callout direction class on root |
| `excerpt_expanison_indicator` (sic — `expanison`) | TEXT | `"..."` | Content → Excerpt | Excerpt-ending indicator (typo: `expanison`) |
| Style → Timeline Style / Bullet / Arrow / Content | various | — | Style tab | Per-region styling (typography, colour, spacing) |
| Style → Load More Button | various | — | Style tab | **Injected via `do_action('eael/controls/load_more_button_style', $this)`** |
| `eael_control_get_pro` (when Pro inactive) | CHOOSE | `1` | Content → Go Premium | Decorative upsell; emitted by `HelperClass::go_premium()` |

The bulk of meaningful controls (query, layout, posts_per_page, skin picker) come from the shared `eael/controls/query` and `eael/controls/layout` injected sections. Per [Bootstrap::query()](../../includes/Classes/Bootstrap.php#L184) for the query control list; this is the same set Post_Grid, Adv_Accordion, and similar widgets share.

## Conditional Dependencies

```text
# Most conditions live inside the injected Query and Layout sections —
# see Bootstrap::query() and Bootstrap::layout() for the full chain

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
                                          (emitted by HelperClass::go_premium())
```

The widget exposes only a few inline conditions because most controls (query filters, layout settings) live in the injected sections and bring their own conditional dependencies.

## Hooks & Filters

| Hook | Type | Listener | Purpose |
| ---- | ---- | -------- | ------- |
| `eael/controls/query` | action (emitted, **internal**) | `Bootstrap::query()` in Lite | Injects shared query control section |
| `eael/controls/layout` | action (emitted, **internal**) | `Bootstrap::layout()` in Lite | Injects shared layout control section (skin picker, posts-per-page, columns) |
| `eael/controls/load_more_button_style` | action (emitted, **internal**) | `Bootstrap::load_more_button_style()` in Lite | Injects shared Load More button style controls |
| `wp_ajax_load_more` / `wp_ajax_nopriv_load_more` | action (consumed) | `Ajax_Handler::ajax_load_more()` | Shared AJAX endpoint — `data-class` dispatches to render path |
| `eael/pro_enabled` | filter (consumed) | — | Hides upsell panel when Pro active |

⚠️ The three `eael/controls/*` hooks are **Lite-internal code reuse**, NOT Pro extension points. Same pattern as Post_Grid — see [`post-grid.md § Hooks & Filters`](post-grid.md#hooks--filters).

## JavaScript Lifecycle

N/A — pure CSS for the timeline visual. The only JS loaded is the shared [`load-more.js`](../../src/js/view/load-more.js) for AJAX pagination. No `frontend/element_ready/eael-post-timeline.default` action handler is registered — Post_Timeline relies entirely on the document-delegation click handler in `load-more.js` for Load More / infinity scroll.

See [`post-grid.md § JavaScript Lifecycle`](post-grid.md#javascript-lifecycle) for the shared `load-more.js` behaviour. Post_Timeline's specifics:

- **`data-class` payload:** `"Essential_Addons_Elementor\\Elements\\Post_Timeline"` — AJAX handler dispatches accordingly
- **No Isotope re-layout** — timeline appended posts simply add to the bottom; CSS `:nth-child(2n)` automatically flips new entries

## Common Issues

### Alternating left/right doesn't flip after Load More appends posts

- **Likely cause:** `:nth-child(2n)` recalculates based on DOM order — appended posts continue the pattern automatically. If the visual doesn't flip, the appended content may not be a direct child of `.eael-post-timeline` (wrapper mismatch)
- **Diagnose:** inspect appended posts — are they at the same DOM depth as initial posts?
- **Fix:** verify the template file structure produces a flat `.eael-timeline-post` list, not nested wrappers

### Widget doesn't appear in the Elementor panel category

- **Likely cause:** `get_categories()` returns `'essential-addons-for-elementor-lite'` ([line 41](../../includes/Elements/Post_Timeline.php#L41)) — every other EA widget returns `'essential-addons-elementor'`. The widget may be registered into a different (probably empty) panel section
- **Diagnose:** check the Elementor widget panel — is Post Timeline in the standard EA section?
- **Fix:** known bug; renaming the category would require a coordinated fix across the EA category registration to support the new id. Currently low-impact because Elementor's category fallback shows the widget regardless

### Read More button doesn't appear after first page (Load More)

- **Likely cause:** template files include the Read More button per-post; if the template doesn't conditionally render it (e.g. when settings disable it), it appears even when disabled
- **Diagnose:** open the template file in `includes/Templates/Post-Timeline/` — does it check the `read_more_button_text` setting?
- **Fix:** template-level fix; or override via theme CSS to hide

### `expanison_indicator` setting saves but doesn't appear in excerpt

- **Likely cause:** typo in the control id (`expanison` not `expansion`); template files read `$settings['expanison_indicator']` after the render-time remap at [line 754](../../includes/Elements/Post_Timeline.php#L754). If a custom template reads `$settings['expansion_indicator']` (correct spelling), nothing displays
- **Diagnose:** check the template's variable access
- **Fix:** template must use the typo'd key for compatibility

### Load More button stays visible after the last page

- **Likely cause:** same as Post_Grid — the server response detection logic in `load-more.js` relies on `.no-posts-found` class; edge cases where the response doesn't match cause the button to persist
- **Diagnose:** inspect AJAX response in DevTools — does it contain `.no-posts-found`?
- **Fix:** see [`post-grid.md § Common Issues`](post-grid.md#common-issues)

## Known Limitations

- **`get_categories()` typo** ([line 41](../../includes/Elements/Post_Timeline.php#L41)) — returns `'essential-addons-for-elementor-lite'` instead of `'essential-addons-elementor'` like every other widget. Causes the widget to potentially appear in a different category panel section. Low-impact because Elementor tolerates unknown category IDs, but unique to this widget.
- **Same `excerpt_expanison_indicator` typo** as Post_Grid — `expanison` should be `expansion`. Renaming breaks saved widget data.
- **Shared `load-more.js` dependency** — Post_Timeline depends on the AJAX pagination chain shared across multiple widgets. Same limitations: nonce 24h TTL, infinity-scroll global window listener, etc.
- **No `frontend/element_ready` handler** — if a future feature needs per-widget JS init (e.g. animation on scroll), the widget will need to add a JS file and register a handler.
- **`get_settings()` would be more consistent than `get_settings_for_display()`** — currently uses `get_settings_for_display()` at line 749 which is the correct choice; Post_Grid uses the older `get_settings()` and is inconsistent. Worth noting that these two sibling widgets diverge here.
- **No support for masonry / staggered layouts** — Post_Timeline is strictly sequential alternating. Users wanting masonry-style timelines need Post_Grid.
- **CSS-only alternating layout breaks if templates inject extra wrappers** — `:nth-child(2n)` depends on flat child structure. Custom skin templates must keep `.eael-timeline-post` as direct children.
- **No control for arrow callout shape** — only direction (`eael_timeline_arrow_alignment`); arrow geometry is hardcoded in SCSS.
