# BetterDocs Category Box Widget

> CSS-only listing widget that queries the `doc_category` taxonomy directly (`get_terms()`) and renders each category as an icon + title + post-count card. Uses **Template_Query trait** for layout selection — two templates ship (`Layout_Default.php`, `Layout_2.php`) under `includes/Template/Betterdocs-Category-Box/`. Supports BetterDocs Pro's **multiple knowledge bases** feature via `Helper::get_betterdocs_multiple_kb_status()` + meta_query on `doc_category_knowledge_base`. Shared query-controls section injected via internal `eael/controls/betterdocs/query` action — same handler also serves Betterdocs_Category_Grid. **Zero JS**, no Pro extension hooks, no `eael_section_pro` upsell.

**Class file:** [`includes/Elements/Betterdocs_Category_Box.php`](../../includes/Elements/Betterdocs_Category_Box.php)
**Slug:** `betterdocs-category-box` (widget id `eael-betterdocs-category-box`)
**Public docs:** <https://essential-addons.com/elementor/docs/betterdocs-category-box/>
**Pro-shared:** ❌ No widget-specific Pro extension. Multi-KB support is a **runtime branch** that activates when BetterDocs Pro is installed and multi-KB feature is enabled; no `do_action` extension hooks. No `eael_section_pro` upsell.

---

## Overview

BetterDocs Category Box is one of three BetterDocs Integration widgets (alongside Category_Grid + Search_Form). It renders a grid of BetterDocs categories: each term in `doc_category` taxonomy becomes a card with an optional icon (from `doc_category_image-id` term meta or fallback SVG), title (with selectable HTML tag), and post-count badge (with prefix/suffix text). The widget uses the **Template_Query trait** (same as Content_Ticker) for layout selection — two layouts ship in Lite. Renders by calling `get_terms()` directly against `doc_category` and looping the results through the selected template via `ob_start` / `include` / `echo ob_get_clean()` per term. **Multi-KB-aware**: when BetterDocs Pro's multiple knowledge bases feature is enabled, an extra `selected_knowledge_base` SELECT appears in the panel and the term query filters by the `doc_category_knowledge_base` meta. Plugin gate is `defined('BETTERDOCS_URL')` — unique among Form-style integrations but shared across the 3 BetterDocs widgets.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Category listing via `get_terms('doc_category')` | ✅ | ✅ |
| Two ship-with layouts (`Default`, `Layout_2`) + theme override path | ✅ | ✅ — Pro can drop additional templates into its own `includes/Template/Betterdocs-Category-Box/` |
| Include / Exclude term IDs (via shared `eael/controls/betterdocs/query`) | ✅ | ✅ |
| `box_per_page`, `orderby`, `order`, `offset` | ✅ | ✅ |
| `orderby == 'betterdocs_order'` (uses `doc_category_order` meta_value_num) | ✅ | ✅ |
| Multi-KB selection (`selected_knowledge_base`) | ✅ — runtime branch when BetterDocs Pro's multi-KB feature is on | ✅ |
| Show/hide icon, title, count with prefix/suffix | ✅ | ✅ |
| Responsive column count via `prefix_class => 'elementor-grid%s-'` | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Betterdocs_Category_Box.php`](../../includes/Elements/Betterdocs_Category_Box.php) | PHP widget class (1193 lines) — controls, `render()` with two branches (multi-KB / standard), `defined('BETTERDOCS_URL')` gate |
| [`includes/Template/Betterdocs-Category-Box/Layout_Default.php`](../../includes/Template/Betterdocs-Category-Box/Layout_Default.php) | Default per-term template (52 lines) — emits `<a class="eael-better-docs-category-box-post"><div class="eael-bd-cb-inner">` with icon + title + count |
| [`includes/Template/Betterdocs-Category-Box/Layout_2.php`](../../includes/Template/Betterdocs-Category-Box/Layout_2.php) | Alternate per-term template |
| [`includes/Traits/Template_Query.php`](../../includes/Traits/Template_Query.php) | `template_options()`, `get_default()`, `get_template()` — scans Lite + Pro + theme dirs for `Template Name:` headers |
| [`includes/Traits/Controls.php`](../../includes/Traits/Controls.php#L459) | `betterdocs_query()` — handler for `eael/controls/betterdocs/query` action; **shared between Category_Box and Category_Grid** (differentiates by `$wb->get_name()`) |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php#L828) | `get_multiple_kb_terms()`, `get_betterdocs_multiple_kb_status()`, `get_doc_post_count()`, `eael_allowed_icon_tags()`, `eael_validate_html_tag()` |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L185) | Registers the `eael/controls/betterdocs/query` listener (Lite-internal, like Post_Grid's `eael/controls/query`) |
| [`src/css/view/betterdocs-category-box.scss`](../../src/css/view/betterdocs-category-box.scss) | Source styles (385 lines) — two-layout variants, hover states, responsive grid |
| [`config.php`](../../config.php#L956) entry `'betterdocs-category-box'` | `Asset_Builder` dependency declaration: **CSS only** — `betterdocs-category-box.min.css`. No widget JS. |
| `assets/front-end/img/betterdocs-cat-icon.svg` | Fallback icon shown when a doc_category has no `doc_category_image-id` term meta |

## Architecture

- **Plugin gate via `defined('BETTERDOCS_URL')`** at [line 84](../../includes/Elements/Betterdocs_Category_Box.php#L84) (register_controls) and [line 1064](../../includes/Elements/Betterdocs_Category_Box.php#L1064) (render) — BetterDocs defines this constant on bootstrap. Same gate style as FluentForm's `defined('FLUENTFORM')`; shared across all 3 BetterDocs widgets. Warning notice includes plugin-install deep-link `plugin-install.php?s=BetterDocs&tab=search&type=term`.
- **Renders by direct `get_terms('doc_category')` query, not shortcode** — at [line 1136 / 1163](../../includes/Elements/Betterdocs_Category_Box.php#L1136). BetterDocs doesn't ship a `[doc_category]` shortcode that EA could delegate to; EA queries the taxonomy itself and renders each term via the chosen template. Different from Form Integrations (which all delegate to plugin shortcodes/functions).
- **Two render branches: multi-KB vs standard** — `$default_multiple_kb = Helper::get_betterdocs_multiple_kb_status()` ([line 1111](../../includes/Elements/Betterdocs_Category_Box.php#L1111)) checks if BetterDocs Pro's multi-KB feature is enabled. When on, adds a `meta_query` for `doc_category_knowledge_base` ([line 1124-1133](../../includes/Elements/Betterdocs_Category_Box.php#L1124)) filtering categories by the selected KB. Both branches are otherwise identical (same template include, same loop) — could be deduplicated but isn't. **Code duplication between [line 1117-1160] and [line 1162-1189].**
- **Template_Query trait drives layout selection** — `template_options()` populates the Layout SELECT2 control. Scans three locations: `includes/Template/Betterdocs-Category-Box/` in Lite, the same in Pro (when active), and `<theme>/Template/Betterdocs-Category-Box/` in the active theme. Each PHP file with a `Template Name:` header becomes a dropdown entry. Theme-override path lets sites customize per-category card markup. Same trait + pattern as Content_Ticker (Forms category).
- **Legacy template name normalization** at [line 1113-1115](../../includes/Elements/Betterdocs_Category_Box.php#L1113) — `if ($settings['layout_template'] == 'Layout_2') { $settings['layout_template'] = 'layout-2'; }`. The Title-cased dropdown value gets lowercased + hyphenated for the file lookup. Suggests the template-key normalization in `Template_Query::process_directory_name()` doesn't catch this case, and a one-off mapping is hardcoded. Cosmetic legacy compat.
- **`orderby == 'betterdocs_order'` is a special term-meta sort** at [line 1102-1108](../../includes/Elements/Betterdocs_Category_Box.php#L1102) — switches `orderby` to `meta_value_num` and `meta_key` to `doc_category_order` (the manual sort order users set in BetterDocs admin). Forces `order = 'ASC'` regardless of user setting. Other orderby values pass through to WP's standard term-ordering.
- **Shared query-controls section via `eael/controls/betterdocs/query`** at [line 110](../../includes/Elements/Betterdocs_Category_Box.php#L110) — internal Lite action (not a Pro extension hook). Handler `Controls::betterdocs_query()` at [Traits/Controls.php line 459](../../includes/Traits/Controls.php#L459) is shared between Category_Box and Category_Grid; differentiates per-widget controls by checking `$wb->get_name() === 'eael-betterdocs-category-grid'`. Same Lite-internal-reuse pattern as Post_Grid's `eael/controls/query`.
- **Responsive grid via Elementor's `elementor-grid%s-` prefix class** at [line 149](../../includes/Elements/Betterdocs_Category_Box.php#L149) — `%s` is replaced by Elementor with the breakpoint suffix (`-tablet`, `-mobile`). Reuses Elementor's built-in grid utility classes instead of writing custom flex/grid CSS.
- **Icon rendering: term meta → attachment, or fallback SVG** — template at [Layout_Default.php line 27-37](../../includes/Template/Betterdocs-Category-Box/Layout_Default.php#L27) reads `doc_category_image-id` term meta, calls `wp_get_attachment_image()` with `thumbnail` size, falls back to bundled SVG `assets/front-end/img/betterdocs-cat-icon.svg`. Icon is `wp_kses`-filtered with `Helper::eael_allowed_icon_tags()` (SVG-aware variant, allows `<svg>` / `<path>` etc.).
- **`wp_reset_postdata()` after term loop** at [line 1153 / 1181](../../includes/Elements/Betterdocs_Category_Box.php#L1153) — technically incorrect; this function resets the `$post` global which `get_terms()` doesn't modify. Harmless no-op. Likely copy-paste from a post-loop widget.
- **No `eael_section_pro` upsell, only 1 `do_action` (the shared internal hook)** — confirmed: zero `apply_filters` for widget-specific extension, zero upsell. Same hooks-free profile as most Form Integrations.
- **`has_widget_inner_wrapper()` follows optimized-markup pattern** at [line 70-72](../../includes/Elements/Betterdocs_Category_Box.php#L70) — standard EA modern-markup support.

## Render Output

```html
<!-- When BETTERDOCS_URL is not defined: nothing renders (early return) -->

<!-- When BetterDocs is active: -->
<div id="eael-bd-cat-box-<widget-id>"
     class="eael-better-docs-category-box-wrapper
            elementor-grid3- [elementor-grid-tablet2- elementor-grid-mobile1-]"   ← Elementor's responsive grid prefix
>
  <div class="eael-better-docs-category-box">

    <!-- Per term: rendered by Layout_Default.php (or Layout_2.php) -->
    <a href="<term link>" class="eael-better-docs-category-box-post">
      <div class="eael-bd-cb-inner">

        [?] <!-- when show_icon == 'true' -->
        <div class="eael-bd-cb-cat-icon">
          <img src="<term-meta image OR fallback SVG>" alt="…">     ← wp_kses(Helper::eael_allowed_icon_tags)
        </div>

        [?] <!-- when show_title == 'true' -->
        <h2 class="eael-bd-cb-cat-title">Category Name</h2>            ← title_tag selectable (h1-h6 / span / p / div)

        [?] <!-- when show_count == 'true' -->
        <div class="eael-bd-cb-cat-count">
          <span class="count-prefix">prefix</span>
          {Helper::get_doc_post_count(count, term_id)}
          <span class="count-suffix">suffix</span>
        </div>

      </div>
    </a>
    …

  </div>
</div>

<!-- Empty result -->
<p class="no-posts-found">No posts found!</p>

<!-- Template file missing -->
<h4>File Not Found</h4>
```

Notes:

- Widget owns only the outer wrapper + inner container. Each card markup comes from the template file — drop a `Template Name: …` PHP file into `<theme>/Template/Betterdocs-Category-Box/` to add a third layout.
- Term link uses `get_term_link($term->slug, 'doc_category')` — multi-KB mode replaces `%knowledge_base%` placeholder in the link with the selected KB slug (Layout_Default.php line 16-19).
- `get_doc_post_count(count, term_id)` returns the published doc count for the term — likely accounts for permission visibility (not just `$term->count`).
- `eael_allowed_icon_tags()` is the SVG-aware kses allowlist; SVG icons render unstripped.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Betterdocs_Category_Box.php#L79) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | "BetterDocs not installed" notice + plugin-install deep-link |
| `selected_knowledge_base` | SELECT2 | empty | Content → Query | **Multi-KB only**; visible when `get_betterdocs_multiple_kb_status()` returns true |
| `include` / `exclude` | SELECT2 (multiple) | empty / empty | Content → Query | Term IDs from `doc_category` — **injected via `do_action('eael/controls/betterdocs/query', $this)`** |
| `box_per_page` | NUMBER | (default) | Content → Query | `get_terms` `number` arg |
| `orderby` | SELECT | (default) | Content → Query | name / count / betterdocs_order — `betterdocs_order` triggers meta_value_num sort by `doc_category_order` |
| `order` | SELECT | ASC | Content → Query | get_terms order direction |
| `offset` | NUMBER | 0 | Content → Query | get_terms offset |
| `layout_template` | SELECT2 | `Default` | Content → Layout Options | Per-term template; Lite ships `Default` + `Layout_2`; theme/Pro can register more |
| `box_column` | SELECT (responsive) | 3 / 2 / 1 | Content → Layout Options | Adds `elementor-grid%s-N` prefix class for column count |
| `show_icon` / `show_title` / `show_count` | SWITCHER | `true` / `true` / `true` | Content → Layout Options | Toggle visibility of each card element |
| `title_tag` | SELECT | `h2` | Content → Layout Options | HTML tag for category title; validated via `Helper::eael_validate_html_tag()` |
| `count_prefix` / `count_suffix` | TEXT (dynamic) | empty / empty | Content → Layout Options | Wrapper text around the post count number |
| Style → Box / Icon / Title / Count | various | — | Style tab | Card background, border, border-radius, box-shadow, padding, margin, hover state, icon size/color, title typography, count typography |

## Conditional Dependencies

```text
# Plugin gate
eael_global_warning_text                  → visible when defined('BETTERDOCS_URL') is FALSE
ALL form controls + style sections        → visible when defined('BETTERDOCS_URL') is TRUE

# Multi-KB gate (runtime)
selected_knowledge_base                   → visible when Helper::get_betterdocs_multiple_kb_status() is TRUE

# Layout-specific conditions
title_tag                                 → visible when show_title == 'true'

# NO Pro upsell — no eael_section_pro / eael_control_get_pro controls
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/controls/betterdocs/query` | action (emitted, **internal**) | `(Widget_Base $widget)` | Inject shared BetterDocs query controls (KB select + include/exclude/per-page/orderby/order/offset). Handler `Controls::betterdocs_query()` registered by Bootstrap; **shared between Category_Box and Category_Grid** (handler branches on `$wb->get_name()`). Same Lite-internal-reuse pattern as Post_Grid's `eael/controls/query`. |

The widget emits **no widget-specific extension hooks** (no Pro `do_action`, no `apply_filters` for customization). The internal action is the only one. Multi-KB integration is a runtime branch driven by `Helper::get_betterdocs_multiple_kb_status()` checking BetterDocs Pro state — no extension surface needed.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply — no Liquid Glass, no FA4 shim, no WPML, no `has_pro` handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

> N/A — **pure CSS-styling widget, no widget JavaScript.** The `config.php` entry declares only a CSS dependency. Cards are static HTML; no click handlers, search, or filter behavior at the widget level. Sibling Betterdocs_Search_Form is the interactive piece in the BetterDocs trio.

## Common Issues

### Widget shows "BetterDocs is not installed/activated"

- **Likely cause:** BetterDocs plugin not active. Gate is `defined('BETTERDOCS_URL')`.
- **Diagnose:** check Plugins → Installed; verify the constant is defined.
- **Fix:** install + activate BetterDocs via the panel deep-link.

### No categories appear / "No posts found!"

- **Likely cause:** `get_terms('doc_category')` returned empty. Could be: no categories created yet, all categories empty (no docs), include/exclude filtered everything out, or multi-KB filter set to a KB with no categories.
- **Diagnose:** WP admin → BetterDocs → Categories; verify categories exist with assigned docs. Check panel's Include/Exclude.
- **Fix:** create categories + assign docs; clear include/exclude filters.

### "File Not Found" message instead of cards

- **Likely cause:** `layout_template` references a template file that doesn't exist in any of the three scan dirs (Lite / Pro / theme).
- **Diagnose:** check what `$settings['layout_template']` is set to; check `includes/Template/Betterdocs-Category-Box/` for matching filenames.
- **Fix:** select an existing layout from the dropdown. If using a theme override, verify the `Template Name:` header docblock + file path.

### `Layout_2` selected but renders as Default

- **Likely cause:** the legacy name normalization at [line 1113-1115](../../includes/Elements/Betterdocs_Category_Box.php#L1113) maps `'Layout_2'` → `'layout-2'` but no file named `layout-2.php` exists. Lite ships `Layout_2.php` (capital L, underscore).
- **Diagnose:** check file casing. Path is case-sensitive on Linux but not Windows/macOS dev.
- **Fix:** rename file if needed, or trust the Template_Query trait's `process_directory_name` to handle case.

### Multi-KB control missing despite using BetterDocs Pro

- **Likely cause:** `get_betterdocs_multiple_kb_status()` requires both Pro to be active AND multi-KB feature enabled in BetterDocs Settings.
- **Diagnose:** WP admin → BetterDocs → Settings → enable Multiple Knowledge Base.
- **Fix:** enable the feature; reload the editor panel.

### Term link is broken on multi-KB sites

- **Likely cause:** `get_term_link()` returns a URL containing `%knowledge_base%` placeholder when multi-KB is on. Layout_Default.php str_replaces it with the selected KB slug — if `selected_knowledge_base` is empty, replaces with `'non-knowledgebase'` ([Layout_Default.php line 18](../../includes/Template/Betterdocs-Category-Box/Layout_Default.php#L18)).
- **Diagnose:** inspect the rendered `<a href>`. Should NOT contain literal `%knowledge_base%`.
- **Fix:** select a specific KB in the panel OR accept `non-knowledgebase` as the URL segment (BetterDocs handles this).

### Icon doesn't appear despite Show Icon being on

- **Likely cause:** the doc_category term has no `doc_category_image-id` meta set. Template falls back to bundled SVG `betterdocs-cat-icon.svg` — if that file is missing, no icon renders.
- **Diagnose:** WP admin → BetterDocs → Categories → edit term → verify image is set.
- **Fix:** set a featured image on the term, OR check `assets/front-end/img/betterdocs-cat-icon.svg` exists in the EA plugin folder.

### Count shows 0 even when docs exist in category

- **Likely cause:** `Helper::get_doc_post_count($term->count, $term->term_id)` filters by visibility — private/draft docs aren't counted. Or BetterDocs's KB-scoped post counts differ from raw term counts in multi-KB mode.
- **Diagnose:** check doc post statuses in the category.
- **Fix:** publish docs; if multi-KB, verify docs are assigned to the active KB.

## Known Limitations

- **Two render branches with near-identical code** ([line 1117-1160 vs 1162-1189](../../includes/Elements/Betterdocs_Category_Box.php#L1117)) — multi-KB branch and standard branch differ only in the `meta_query` addition. Could be deduplicated; the duplication invites drift.
- **`wp_reset_postdata()` called after term loop** — wrong function for terms; harmless but misleading.
- **Legacy template-name normalization is a one-off mapping** — `'Layout_2'` → `'layout-2'`. If future templates are added with similar Title_Case names, each needs its own mapping.
- **`Layout_2` is the only non-Default Lite template** — limited variety without theme overrides.
- **No `eael_section_pro` upsell + zero widget-specific hooks** — same lean profile as most Form Integrations and Interactive widgets.
- **Multi-KB feature detection is runtime** — adding `selected_knowledge_base` control requires reloading the editor panel after toggling multi-KB in BetterDocs Settings.
- **`get_doc_post_count()` is a Helper method whose visibility logic isn't documented at the widget level** — count semantics depend on Helper implementation. Custom doc statuses may not match expectations.
- **No frontend AJAX integration with EA** — no search/filter on the boxes themselves. Search lives in the sibling Betterdocs_Search_Form widget.
- **`elementor-grid%s-` prefix class assumes Elementor's grid utility CSS is loaded** — if Elementor's core CSS is dequeued (rare optimization), columns may collapse.
- **Term link multi-KB fallback `'non-knowledgebase'`** is a magic-string convention from BetterDocs — undocumented at the widget level; relies on BetterDocs's URL rewrite rules handling that segment.
- **Plugin-install deep-link uses `s=BetterDocs`** (mixed case) — search may not match the lowercase WP.org slug if the search is case-sensitive. Test shows WP.org plugin search is case-insensitive; safe.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. Term query results may stale-cache if categories are added/removed between widget renders.
- **`get_style_depends()` not declared** — relies on BetterDocs's own CSS or default browser styling for `.doc_category` taxonomy markup outside the EA wrapper.
- **385-line SCSS is relatively large for a static widget** — two-layout variants + responsive grid + hover states + count badges. Trimmable if old `Layout_2` is deprecated.
