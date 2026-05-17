# BetterDocs Category Grid Widget

> Richer sibling of Betterdocs_Category_Box — renders each `doc_category` term as a **card with header (icon/title/count) + per-term doc list + nested sub-category accordion + footer "Explore More" button**. Adds Isotope masonry support (when `layout_mode == 'masonry'`) and click-to-toggle sub-category lists. Two layouts (`Layout_Default.php`, `Layout_2.php`) via `Template_Query` trait. Shares plugin gate (`defined('BETTERDOCS_URL')`) and query-controls action (`eael/controls/betterdocs/query`) with Category_Box; the shared handler `Controls::betterdocs_query()` branches on `$wb->get_name()` to add grid-specific `grid_per_page` instead of `box_per_page`.

**Class file:** [`includes/Elements/Betterdocs_Category_Grid.php`](../../includes/Elements/Betterdocs_Category_Grid.php)
**Slug:** `betterdocs-category-grid` (widget id `eael-betterdocs-category-grid`)
**Public docs:** <https://essential-addons.com/elementor/docs/betterdocs-category-grid/>
**Pro-shared:** ❌ No widget-specific Pro extension. Multi-KB support is a **runtime branch** like Category_Box. No `eael_section_pro` upsell, no `do_action` extension hooks. BetterDocs Pro doesn't extend this widget — same lean profile as the box sibling but with significantly more PHP (1745 vs 1193 lines) due to richer panel + footer button styling.

---

## Overview

Category_Grid is Category_Box's richer cousin: same `get_terms('doc_category')` query backend, same `Template_Query` trait for layout selection, same `defined('BETTERDOCS_URL')` gate, but with **header (icon/title/count) + per-term doc list (queries `docs` CPT inside each term render) + nested sub-category accordion + footer button**. The per-term template runs a `WP_Query` against the `docs` CPT filtered by the current term's slug; when nested sub-categories are enabled, a second `get_terms('doc_category', ['child_of' => $term->term_id])` builds the toggleable sub-lists. **Three layout modes**: `grid`, `fit-to-screen`, `masonry` — only `masonry` triggers Isotope init in the JS. Editor preview ships an inline `<script>` block that duplicates frontend Isotope init (same pattern as Post_Grid). Layout_Default template uses `Helper::include_with_variable($template, ['term', 'settings', 'default_multiple_kb'])` in the multi-KB branch (different from Category_Box's plain `include`), passing variables explicitly through the helper's `extract()` wrapper.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Category cards with header (icon/title/count) + doc list + sub-category accordion + button | ✅ | ✅ |
| `grid` / `fit-to-screen` / `masonry` layout modes | ✅ | ✅ |
| Isotope masonry (when `layout_mode == 'masonry'`) | ✅ | ✅ |
| Per-term WP_Query against `docs` CPT with `post_per_page`, `post_orderby`, `post_order` | ✅ | ✅ |
| Nested sub-category accordion (`get_terms('doc_category', ['child_of' => …])`) | ✅ | ✅ |
| Open/closed nested-list toggle icons | ✅ | ✅ |
| Footer "Explore More" button with icon + position | ✅ | ✅ |
| Term query controls (include/exclude/per-page/orderby/order/offset) — **shared with Category_Box** via `eael/controls/betterdocs/query` | ✅ | ✅ |
| Multi-KB selection — runtime branch via `Helper::get_betterdocs_multiple_kb_status()` | ✅ | ✅ |
| 6-column max grid (vs Category_Box's 4-column max) | ✅ | ✅ |
| Editor preview inline `<script>` for Isotope init | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Betterdocs_Category_Grid.php`](../../includes/Elements/Betterdocs_Category_Grid.php) | PHP widget class (1745 lines) — controls, `render()` (two near-identical branches like Category_Box), `render_editor_script()` inline Isotope JS |
| [`includes/Template/Betterdocs-Category-Grid/Layout_Default.php`](../../includes/Template/Betterdocs-Category-Grid/Layout_Default.php) | Default per-term template (234 lines) — header + WP_Query doc list + nested sub-cat accordion + button |
| [`includes/Template/Betterdocs-Category-Grid/Layout_2.php`](../../includes/Template/Betterdocs-Category-Grid/Layout_2.php) | Alternate per-term template |
| [`includes/Traits/Template_Query.php`](../../includes/Traits/Template_Query.php) | `template_options()`, `get_template()`, scans Lite + Pro + theme dirs |
| [`includes/Traits/Controls.php`](../../includes/Traits/Controls.php#L459) | `betterdocs_query()` — shared handler; adds `grid_query_heading` HEADING + `grid_per_page` NUMBER when `$wb->get_name() === 'eael-betterdocs-category-grid'` |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `get_betterdocs_multiple_kb_status()`, `get_multiple_kb_terms()`, `get_doc_post_count()`, `eael_allowed_icon_tags()`, `eael_validate_html_tag()`, **`include_with_variable($template, $vars)`** (used in multi-KB branch — `extract()`s vars into template scope) |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L185) | Registers `eael/controls/betterdocs/query` listener |
| [`src/css/view/betterdocs-category-grid.scss`](../../src/css/view/betterdocs-category-grid.scss) | Source styles (508 lines) — two-layout variants, header/list/button styling, nested list accordion arrow rotation, masonry/grid CSS |
| [`src/js/view/betterdocs-category-grid.js`](../../src/js/view/betterdocs-category-grid.js) | Frontend logic (34 lines) — Isotope masonry init (conditional on `data-layout-mode === 'masonry'`) + sub-cat title click-to-toggle (`slideToggle`). Handler named `PostGrid` (copy-paste artifact from Post_Grid) |
| [`config.php`](../../config.php#L927) entry `'betterdocs-category-grid'` | Asset declaration: `betterdocs-category-grid.min.css` + `imagesloaded` (vendor) + `isotope` (vendor) + `betterdocs-category-grid.min.js` |

## Architecture

- **Plugin gate via `defined('BETTERDOCS_URL')`** at [line 89](../../includes/Elements/Betterdocs_Category_Grid.php#L89) (register_controls) and [line 1571](../../includes/Elements/Betterdocs_Category_Grid.php#L1571) (render) — same gate style as Category_Box.
- **Two near-identical render branches** (multi-KB at [line 1629-1675](../../includes/Elements/Betterdocs_Category_Grid.php#L1629), standard at [line 1676-1706](../../includes/Elements/Betterdocs_Category_Grid.php#L1676)) — same code duplication pattern as Category_Box. **Subtle difference**: multi-KB branch uses `Helper::include_with_variable()` (helper that `extract()`s the variables array into template scope) while standard branch uses plain `ob_start` + `include`. Both pass the same vars (`term`, `settings`, `default_multiple_kb`); cosmetic divergence with no functional impact.
- **Shared query-controls action `eael/controls/betterdocs/query`** at [line 113](../../includes/Elements/Betterdocs_Category_Grid.php#L113) — handler `Controls::betterdocs_query()` branches on `$wb->get_name() === 'eael-betterdocs-category-grid'` ([Controls.php line 491, 525](../../includes/Traits/Controls.php#L491)) to inject a "Category Grid" HEADING control and a `grid_per_page` NUMBER (default `8`) instead of Category_Box's `box_per_page`.
- **Three layout modes** at [line 139-151](../../includes/Elements/Betterdocs_Category_Grid.php#L139): `grid`, `fit-to-screen`, `masonry`. Class name `$settings['layout_mode']` is added directly to the wrapper inner class list (`.eael-better-docs-category-grid grid`, `.fit-to-screen`, `.masonry`) plus the `data-layout-mode` attribute for JS init.
- **JS init guarded only by `layout_mode === 'masonry'`** — `grid` and `fit-to-screen` use pure CSS Grid / Flexbox layouts (no JS). Only masonry needs Isotope. JS handler is named `PostGrid` (copy-paste from Post_Grid widget — misleading but harmless).
- **Sub-category accordion is pure jQuery toggle** — `slideToggle` on `.docs-sub-cat-list` when `.eael-bd-grid-sub-cat-title` clicked ([JS line 18-26](../../src/js/view/betterdocs-category-grid.js#L18)). No saved-state; collapsed by default.
- **Per-term docs WP_Query inside template** at [Layout_Default.php line 64-109](../../includes/Template/Betterdocs-Category-Grid/Layout_Default.php#L64) — runs ONE `WP_Query` per category term. N+1 query risk: 8 categories × 10 docs per page = 9 queries (1 for terms + 8 for docs). On grids with many categories, this is visible in profiler.
- **Multi-KB doc query is more elaborate** — uses `term_taxonomy_id` (not `slug`) joined via a `$tax_map` array built by iterating `knowledge_base` + `doc_category` terms. Standard branch uses `slug` directly. Both `include_children` set to `false` (manual nested-sub-cat handling instead).
- **Nested sub-category accordion** is a SECOND `get_terms('doc_category', ['child_of' => $term->term_id])` per parent category at [Layout_Default.php line 131-193](../../includes/Template/Betterdocs-Category-Grid/Layout_Default.php#L131). For each sub-category, a THIRD `WP_Query` against `docs` CPT runs to list docs in that sub-category. **N+1+N nested**: 1 (terms) + N (per-term docs) + Σ(per-sub-cat docs). Heavy widget on documentation-rich sites.
- **`get_terms()` called with deprecated 2-param signature** at [Layout_Default.php line 139](../../includes/Template/Betterdocs-Category-Grid/Layout_Default.php#L139) — `get_terms('doc_category', $args)`. WordPress deprecated this in 4.5; newer signature is `get_terms($args_with_taxonomy)`. Code has explicit phpcs ignore comment. Functional but log-noisy.
- **Two nested-list toggle icons** — `nested_list_title_closed_icon` + `nested_list_title_open_icon` ICONS controls. JS toggles `display` between the two via `.toggle()` ([JS line 21-22](../../src/js/view/betterdocs-category-grid.js#L21)) — both icons render in HTML, one is `display: none` per state.
- **Footer button supports icon position + multi-KB URL placeholder** — same `%knowledge_base%` str_replace fallback as Category_Box. `show_button_icon` switcher gates the icon rendering; `icon_position` SELECT picks before/after.
- **Responsive grid via `elementor-grid%s-` prefix class** with max **6 columns** ([line 162-167](../../includes/Elements/Betterdocs_Category_Grid.php#L162)) — Category_Box maxes at 4. Higher cap for grid layouts.
- **`get_style_depends() = ['font-awesome-5-all', 'font-awesome-4-shim']`** at [line 42-48](../../includes/Elements/Betterdocs_Category_Grid.php#L42) — Category_Box has no style depends. Grid uses FA icons in the panel-controlled nested-list toggle + button icons.
- **Editor preview inline `<script>`** at [line 1710-1727](../../includes/Elements/Betterdocs_Category_Grid.php#L1710) — duplicates the frontend Isotope init. Necessary because Elementor's editor iframe doesn't reliably fire `frontend/element_ready`. Same pattern as Post_Grid / Filterable_Gallery.
- **Same legacy `'Layout_2'` → `'layout-2'` hardcoded mapping** at [line 1625-1627](../../includes/Elements/Betterdocs_Category_Grid.php#L1625) as Category_Box.
- **No `eael_section_pro` upsell + only 1 `do_action` (the shared internal hook)** — confirmed lean profile.

## Render Output

```html
<div id="eael-bd-cat-grid-<widget-id>"
     class="eael-better-docs-category-grid-wrapper
            elementor-grid3- [elementor-grid-tablet2- elementor-grid-mobile1-]">
  <div class="eael-better-docs-category-grid grid|fit-to-screen|masonry"
       data-layout-mode="grid|fit-to-screen|masonry">

    <!-- Per term: rendered by Layout_Default.php -->
    <article class="eael-better-docs-category-grid-post" data-id="<post-id>">
      <div class="eael-bd-cg-inner">

        [?] <!-- when show_header == 'true' -->
        <div class="eael-bd-cg-header">
          <div class="eael-bd-cg-header-inner">
            [?] <div class="eael-docs-cat-icon">
              <img src="<term meta image OR fallback SVG>" alt="…">
            </div>
            [?] <h2 class="eael-docs-cat-title">Category Name</h2>
            [?] <div class="eael-docs-item-count">{get_doc_post_count(count, term_id)}</div>
          </div>
        </div>

        [?] <!-- when show_list == 'true' -->
        <div class="eael-bd-cg-body">
          <ul>
            <li>
              <i class="<list-icon> eael-bd-cg-post-list-icon"></i>
              <a href="<doc permalink>">Doc Title</a>
            </li>
            …
          </ul>

          [?] <!-- when nested_subcategory == 'true' -->
          <span class="eael-bd-grid-sub-cat-title">
            <i class="<closed-icon> toggle-arrow arrow-right"></i>
            <i class="<open-icon> toggle-arrow arrow-down"></i>          ← one of these display:none per state
            <a href="#">Sub-Category Name</a>
          </span>
          <ul class="docs-sub-cat-list">                                  ← slideToggle on click
            <li class="sub-list">
              <i class="<list-icon> eael-bd-cg-post-list-icon"></i>
              <a href="<sub-doc permalink>">Sub-Doc Title</a>
            </li>
            …
          </ul>
          …
        </div>

        <div class="eael-bd-cg-footer">
          [?] <!-- when show_button == 'true' -->
          <a class="eael-bd-cg-button" href="<term link OR multi-KB-resolved link>">
            [?] <i class="<button-icon> eael-bd-cg-button-icon eael-bd-cg-button-icon-left"></i>
            Explore More
            [?] <i class="<button-icon> eael-bd-cg-button-icon eael-bd-cg-button-icon-right"></i>
          </a>
        </div>
      </div>
    </article>
    …
  </div>
  <div class="clearfix"></div>

  [?] <!-- editor mode only -->
  <script>jQuery(document).ready(function($) { /* Isotope init duplicate */ });</script>
</div>
```

Notes:

- Widget owns only the wrapper + inner container. Each `<article>` and inner markup comes from the template file.
- `<article>` has `data-id="<get_the_ID()>"` — uses `get_the_ID()` at render time which returns the **current page id**, not the term id. Same vestigial pattern as NinjaForms wrapper id.
- Both nested toggle icons (`arrow-right` + `arrow-down`) render in HTML; JS swaps visibility via `.toggle()`. CSS must hide one initially via default `display`.
- `clearfix` div at the end accommodates legacy float layouts.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Betterdocs_Category_Grid.php#L84) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "BetterDocs not installed" notice + plugin-install deep-link |
| `selected_knowledge_base` | SELECT2 | empty | Content → Query | **Multi-KB only** |
| `include` / `exclude` | SELECT2 (multiple) | empty | Content → Query | Term IDs from `doc_category` — **shared `eael/controls/betterdocs/query`** |
| `grid_per_page` | NUMBER | `8` | Content → Query | Grid-specific (Category_Box uses `box_per_page`) — added by shared handler when `widget_name === 'eael-betterdocs-category-grid'` |
| `orderby` / `order` / `offset` | various | (defaults) | Content → Query | `betterdocs_order` triggers `meta_value_num` on `doc_category_order` |
| `layout_template` | SELECT2 | `Default` | Content → Layout Options | Per-term template; Lite ships 2 |
| `layout_mode` | SELECT2 | `grid` | Content → Layout Options | `grid` / `fit-to-screen` / `masonry`; only masonry triggers Isotope JS |
| `grid_column` | SELECT (responsive) | 3/2/1 | Content → Layout Options | Max **6 columns** (vs Category_Box max 4); `elementor-grid%s-` prefix class |
| `show_header` / `show_icon` / `show_title` / `show_count` | SWITCHER | `true` | Content → Layout Options | Card header element toggles; icon/title/count gated on `show_header` |
| `title_tag` | SELECT | `h2` | Content → Layout Options | HTML tag; gated on `show_header == 'true'` AND `show_title == 'true'` |
| `show_list` | SWITCHER | `true` | Content → Layout Options | Toggle per-term doc list rendering |
| `post_per_page` / `post_orderby` / `post_order` | NUMBER / SELECT / SELECT | — | Content → Layout Options | Per-term `WP_Query` args for the doc list |
| `list_icon` | ICONS | — | Content → Layout Options | Icon prepended to each doc list item |
| `nested_subcategory` | SWITCHER | — | Content → Layout Options | Enables the sub-cat accordion section |
| `nested_list_title_closed_icon` / `_open_icon` | ICONS | — | Content → Layout Options | Toggle arrows (both render; JS swaps visibility) |
| `show_button` | SWITCHER | `true` | Content → Layout Options | Footer "Explore More" button |
| `button_text` | TEXT (dynamic, AI) | `Explore More` | Content → Layout Options | Button label |
| `show_button_icon` | SWITCHER | — | Content → Layout Options | Toggle button icon |
| `button_icon` | ICONS | — | Content → Layout Options | Button icon |
| `icon_position` | SELECT | `after` | Content → Layout Options | `before` / `after` button text |
| Style → Header / List / Nested / Button / Card | various | — | Style tab | Per-element styling (background, border, radius, typography, hover) |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                  → visible when defined('BETTERDOCS_URL') is FALSE
ALL form controls + style sections        → visible when defined('BETTERDOCS_URL') is TRUE

# Multi-KB gate (runtime)
selected_knowledge_base                   → visible when Helper::get_betterdocs_multiple_kb_status() is TRUE

# Header-element gates
show_icon / show_title / show_count       → conditional on show_header == 'true'
title_tag                                 → conditional on show_title == 'true' AND show_header == 'true'

# Nested sub-cat gates
nested_list_title_closed_icon /
nested_list_title_open_icon /
nested_list_*_style                       → conditional on nested_subcategory == 'true'

# Button gates
button_text                               → conditional on show_button == 'true'
button_icon                               → conditional on show_button_icon == 'true'
icon_position                             → conditional on show_button_icon == 'true'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/controls/betterdocs/query` | action (emitted, **internal**) | `(Widget_Base $widget)` | Shared query-controls injection; handler `Controls::betterdocs_query()` branches per-widget. Same Lite-internal-reuse pattern as Post_Grid. |

No widget-specific extension hooks. Multi-KB support is a runtime branch driven by `Helper::get_betterdocs_multiple_kb_status()`. No `eael_section_pro` upsell.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply.

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on('elementor/frontend/init', …)` → `elementorFrontend.hooks.addAction('frontend/element_ready/eael-betterdocs-category-grid.default', PostGrid)`. Older registration pattern. **Handler is named `PostGrid`** — copy-paste artifact from `post-grid.js`; misleading but harmless.
- **Guard:** none. No `elementStatusCheck`.
- **Vendor dependencies:** Isotope (masonry layout), imagesLoaded (defers layout until images settle).
- **Reads on init:** `data-layout-mode` on `.eael-better-docs-category-grid`.
- **Branches:**
  - `layout-mode === 'masonry'` — init Isotope with `itemSelector: '.eael-better-docs-category-grid-post'`, `percentPosition: true`; bind `imagesLoaded().progress(layout)` for image-load reflow.
  - other modes — skip Isotope, rely on CSS Grid/Flexbox.
- **Sub-cat toggle:** click `.eael-bd-grid-sub-cat-title` → `.toggle()` on `.toggle-arrow` children (swaps right/down arrow visibility) + `.slideToggle()` on sibling `.docs-sub-cat-list`.
- **No cross-widget reflow listeners** — grid inside a tab/accordion may show with broken masonry layout until interaction triggers re-layout.
- **Editor preview duplicates init** inline via `render_editor_script()` — same maintenance burden as Post_Grid / Filterable_Gallery.

## Common Issues

### Cards stack vertically when layout_mode is `masonry`

- **Likely cause:** Isotope init failed silently OR `imagesLoaded` reflow didn't run after images loaded.
- **Diagnose:** browser DevTools — does `.eael-better-docs-category-grid` have inline `position` style applied by Isotope?
- **Fix:** verify Isotope + imagesLoaded vendor JS loaded (Network tab). Try switching to `grid` mode as fallback.

### Sub-category list doesn't toggle on click

- **Likely cause:** the `.eael-bd-grid-sub-cat-title` click handler binding failed — usually because EA's JS didn't load.
- **Diagnose:** browser console — is the click event firing?
- **Fix:** verify `betterdocs-category-grid.min.js` is enqueued; click handler is bound globally (no `$scope`) so it should match any page instance.

### `Layout_2` selected but renders as Default

- **Likely cause:** legacy template name mapping `'Layout_2'` → `'layout-2'` at [line 1625-1627](../../includes/Elements/Betterdocs_Category_Grid.php#L1625); file is `Layout_2.php` (capital L). Case-sensitive Linux servers may not resolve.
- **Diagnose:** check file casing.
- **Fix:** ensure file exists with the lowercase-hyphen variant or fix path resolution.

### Doc count shows 0 in category card

- **Likely cause:** `Helper::get_doc_post_count($term->count, $term->term_id)` filters by visibility; private/draft docs not counted.
- **Fix:** publish docs.

### Both toggle arrows show on sub-cat title

- **Likely cause:** SCSS rule for `.toggle-arrow.arrow-down { display: none }` (or similar) not loaded. Both arrows render in HTML; CSS must hide one initially.
- **Diagnose:** inspect element — both `.arrow-right` and `.arrow-down` should not be `display: block` simultaneously.
- **Fix:** verify `betterdocs-category-grid.min.css` loaded.

### Per-term doc list is empty for some categories

- **Likely cause:** `WP_Query` args at template line 64-107 filter by `slug` (single-KB) or `term_taxonomy_id` (multi-KB) with `include_children: false`. Docs assigned ONLY to child categories don't appear in parent's list.
- **Diagnose:** check which categories have docs assigned directly.
- **Fix:** assign docs to the displayed category, OR enable `nested_subcategory` to render child categories' docs separately.

### N+1 query performance on category-heavy sites

- **Likely cause:** **inherent**: 1 query for terms + N per-term `WP_Query` for docs + Σ per-sub-cat `WP_Query` when nested enabled.
- **Diagnose:** Query Monitor → count `wp_posts` queries.
- **Fix:** disable nested sub-categories on grids with many categories; reduce `post_per_page` per term; cache via object cache plugin.

### Multi-KB term URL contains `%knowledge_base%`

- **Likely cause:** placeholder str_replace fallback — when `selected_knowledge_base` is empty, replaced with `'non-knowledgebase'`. Same pattern as Category_Box.
- **Fix:** pick a KB in panel OR let `'non-knowledgebase'` segment route via BetterDocs's URL rewrites.

## Known Limitations

- **JS handler named `PostGrid`** — copy-paste artifact. Misleading but harmless.
- **Two near-identical render branches** with `include_with_variable()` vs plain `include` divergence — could be merged.
- **N+1 (+N nested) query risk** — heavy widget. Cache critical for documentation sites.
- **`get_terms` 2-param deprecated signature** in Layout_Default.php (sub-categories) — works but logs deprecation in WP debug mode.
- **Both nested arrow icons rendered in HTML** — relies on CSS initial-state to hide one. SCSS dependency for correctness.
- **Editor preview inline `<script>`** duplicates frontend init — maintenance burden.
- **Legacy `'Layout_2'` → `'layout-2'` mapping** is one-off — future templates need similar mappings.
- **`data-id="<get_the_ID()>"`** on each `<article>` uses page id, not term id — useless attribute or bug.
- **`include_with_variable()` used only in multi-KB branch** — inconsistent with standard branch. Either pattern works; mixing both creates confusion.
- **No cross-widget reflow listeners** — Isotope masonry inside hidden tab/accordion shows broken until manual interaction.
- **No `eael_section_pro` upsell + zero widget-specific hooks** — lean profile.
- **Max 6 columns is panel-limit** — sites needing 7+ columns can't extend without code edit.
- **`get_style_depends()` includes `font-awesome-4-shim`** — deprecated handle in modern Elementor.
- **Footer button URL multi-KB placeholder fallback `'non-knowledgebase'`** — undocumented magic string, relies on BetterDocs rewrite rules.
- **`is_dynamic_content()` not overridden** — render cache active despite per-term WP_Query that could stale-cache on doc additions.
- **`wp_reset_postdata()` after term loops** (multiple) — wrong function for term loops; harmless.
- **Nested sub-cat list has fixed `posts_per_page = -1`** at template line 174 — no panel control; documentation sites with deep sub-cats can render hundreds of items.
