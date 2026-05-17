# Data Table Widget

> Renders an HTML table with two Repeater inputs (header columns + flat list of row-markers and cell-items), per-cell content type (icon / textarea / editor / Saved Template), colspan/rowspan, CSS class/id, optional per-cell link, custom mobile-stacked layout via inline `<style>` block, and an editor-only CSV export button that scrapes the rendered table from the iframe. Table sorting (jQuery TableSorter) is **Pro-only** — the Lite control shows a Pro alert; the `tablesorter` class is on the `<table>` but no `sorting` class is added to `<th>` elements without Pro, so the sorter no-ops.

**Class file:** [`includes/Elements/Data_Table.php`](../../includes/Elements/Data_Table.php)
**Slug:** `data-table` (widget id `eael-data-table`)
**Public docs:** <https://essential-addons.com/elementor/docs/data-table/>
**Pro-shared:** ✅ Yes — Pro injects the **Table Sorting** toggle control via `do_action('eael_section_data_table_enabled', $this)` at [line 109](../../includes/Elements/Data_Table.php#L109); Pro also exposes a global `enableProSorter(jQuery, $_this)` function called from Lite's JS at [JS line 7](../../src/js/view/data-table.js#L7). When Pro is active, render adds a `sorting` class to each `<th>` ([line 1401-1403](../../includes/Elements/Data_Table.php#L1401)) for the TableSorter plugin to bind to. WPML for Saved Templates.

---

## Overview

Data Table renders a standard HTML `<table>` driven by two Repeaters: one for header columns (label, colspan, icon, CSS class/id) and one for content rows. The content Repeater is **flat** — each entry is either `type='row'` (a marker that opens a new `<tr>`) or `type='col'` (an actual `<td>` cell). Render iterates twice: once to bucket cells into rows via shared `uniqid()` row markers, once to emit the `<table>`. Each cell can hold one of four content types: an icon (FA / SVG), a textarea (plain text + optional link), a WYSIWYG editor block, or a Saved Template. Table sorting requires Pro — Lite ships the `tablesorter` CSS class on the table and calls `enableProSorter()` (defined by Pro) but never adds the `sorting` class to headers without Pro. CSV export is a `Controls_Manager::BUTTON` control inside the editor panel — clicking it fires a custom `ea:table:export` event that JS listens for, scrapes the rendered table from the editor iframe, and downloads a CSV blob via the parent (admin) document.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Header + body Repeaters (icon / textarea / editor / template cells) | ✅ | ✅ |
| Colspan, rowspan, CSS class, CSS id (per cell + per header) | ✅ | ✅ |
| Saved Template cells (with WPML translation) | ✅ | ✅ |
| Custom mobile-stacked responsive layout (per-cell th-label via JS) | ✅ | ✅ |
| Editor-only CSV export button | ✅ | ✅ |
| Table sorting (jQuery TableSorter) | ❌ — Pro-alert shown when toggle enabled; `sorting` class missing on `<th>` | ✅ via `eael_section_data_table_enabled` + global `enableProSorter()` JS |
| FA4 → FA5 icon migration shim (header icon + cell icon) | ✅ — see [`_patterns.md § FA4`](_patterns.md#fa4--fa5-icon-migration-shim) | ✅ |
| WPML translation for Saved Template id | ✅ — see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation) | ✅ |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Data_Table.php`](../../includes/Elements/Data_Table.php) | PHP widget class (1509 lines) — controls, `render()`, empty `content_template()` (disables JS preview) |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `eael_wp_kses()`, `eael_allowed_tags()`, `get_elementor_templates()`, `is_elementor_publish_template()`, `eael_onpage_edit_template_markup()`, `eael_e_optimized_markup()` |
| [`src/css/view/data-table.scss`](../../src/css/view/data-table.scss) | Source styles (425 lines) — table styling, hover row states, mobile-stack helper class, sort-indicator icons |
| [`src/js/view/data-table.js`](../../src/js/view/data-table.js) | Frontend logic (85 lines) — Pro sorter bridge (`enableProSorter`), per-cell mobile th-label injection, editor-side CSV export |
| [`config.php`](../../config.php#L376) entry `'data-table'` | `Asset_Builder` dependency declaration: `data-table.min.css` + `data-table.min.js` |
| `assets/front-end/css/view/data-table.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/data-table.min.js` | Built output (do not edit) |

## Architecture

- **Flat Repeater with row markers, not nested rows** — `eael_data_table_content_rows` is one Repeater where each entry has a `row_type` of `'row'` (a marker that opens a new `<tr>`) or `'col'` (the actual cell). Render iterates once at [line 1314-1349](../../includes/Elements/Data_Table.php#L1314) to bucket cells under the most-recent row id (generated via `uniqid()` per row marker), then again at [line 1430-1501](../../includes/Elements/Data_Table.php#L1430) to emit the `<table>`. Adding a cell without an antecedent row marker silently misroutes it. The repeater `title_field` uses `'row'::title` or `'col'::content` so editors can scan the list.
- **Custom responsive mode emits inline `<style>` block** — when `eael_enable_responsive_header_styles == 'yes'`, render writes a `<style>` block at [line 1373-1387](../../includes/Elements/Data_Table.php#L1373) scoped by widget id with `@media (max-width: <breakpoint>px)` that hides `<thead>` and stacks `<td>`s as `display: flex; width: 100%`. JS at [JS line 16-26](../../src/js/view/data-table.js#L16) then prepends `<div class="th-mobile-screen">{header text}</div>` inside each `.td-content-wrapper`, so each cell shows its column header above its value on mobile. The per-cell label injection runs on every page load (not just when responsive), but the inline style block decides whether anything's visible.
- **Pro sorter is a globally-defined function check** — Lite's JS at [JS line 5-9](../../src/js/view/data-table.js#L5) does `typeof enableProSorter !== "undefined" && $.isFunction(enableProSorter)` and runs it if available. Pro defines this global on widget init. **Lite never adds the `sorting` class to `<th>`** — that's gated by `apply_filters('eael/pro_enabled', false)` at [line 1401-1403](../../includes/Elements/Data_Table.php#L1401). Without Pro, TableSorter doesn't have any sortable column targets even though the CSS class `tablesorter` is on the `<table>`. **Lite-side toggle is a Pro decoy** — the `eael_section_data_table_enabled` control exists in Lite as a SWITCHER but only emits a Pro-alert heading; Pro registers its own version via the `do_action` to replace this behavior with a working control.
- **CSV export only works in the editor** — `ea_adv_data_table_export_csv_button` is a `Controls_Manager::BUTTON` control with `'event' => 'ea:table:export'`. The JS handler `Data_Table_Click_Handler` ([JS line 30-62](../../src/js/view/data-table.js#L30)) is bound only when `isEditMode` — `elementor.hooks.addAction("panel/open_editor/widget/eael-data-table", data_table_panel)`. Click → check `event.target.dataset.event` → scrape `view.el.querySelector("#eael-data-table-<id>")` → join `<th>` / `<td>` inner text into CSV rows → create Blob → `parent.document.createElement("a").click()` (uses `parent.document` because the editor preview runs in an iframe). No frontend export. The handler reads `event` as a non-standard `window.event` global at [JS line 31](../../src/js/view/data-table.js#L31) — modern browsers preserve it, but strict-mode JS modules would fail.
- **`is_dynamic_content()` disables render cache for Saved Template cells** — [line 61-85](../../includes/Elements/Data_Table.php#L61). Loops the content rows, returns true if any cell has `content_type == 'template'`. Same pattern as Adv_Accordion + Adv_Tabs but limited to template cells (icon / textarea / editor cells don't bypass cache).
- **Pro extension via single action hook** — `do_action('eael_section_data_table_enabled', $this)` at [line 109](../../includes/Elements/Data_Table.php#L109), inside the Header controls section start. Pro adds a real sorting toggle here. Un-prefixed legacy name (no `eael/`). Whether-to-show-the-Pro-alert is gated by `!apply_filters('eael/pro_enabled', false)` immediately after.
- **Empty `content_template()` disables JS preview** — at [line 1508](../../includes/Elements/Data_Table.php#L1508). The widget falls back to a full PHP-rendered preview in the editor (slower but accurate). Same pattern as Feature_List, Tooltip.
- **`content_template()` typo in section id** — `eael_section_data_table_cotnent` ([line 300](../../includes/Elements/Data_Table.php#L300)) instead of `_content`. Stable for backwards compat; never seen by end users.

## Render Output

```html
<div class="eael-data-table-wrap [custom-responsive-option-enable]"
     id="eael-data-table-wrapper-<widget-id>"
     data-table_id="<widget-id>"
     data-custom_responsive="true|false"
     [?] data-table_enabled="true">          ← when sort toggle on (Pro)

  [?] <!-- Inline <style> for custom responsive mode -->
  <style>
    @media (max-width: 767px) {
      #eael-data-table-wrapper-<widget-id>.custom-responsive-option-enable .eael-data-table thead { display: none; }
      #eael-data-table-wrapper-<widget-id>.custom-responsive-option-enable .eael-data-table tbody tr td {
        float: none; clear: left; width: 100%; text-align: left; display: flex; align-items: center;
      }
    }
  </style>

  <table id="eael-data-table-<widget-id>"
         class="tablesorter eael-data-table <table_alignment>">    ← .tablesorter class always present; only sorts if Pro adds .sorting to <th>
    <thead>
      <tr class="table-header">
        <!-- Per header Repeater item: -->
        <th [class="<css-class> sorting"]                          ← .sorting added only when Pro is active
            [id="<css-id>"]
            [colspan="N"]>

          [?] <!-- Icon (FA, SVG, or image) -->
          [?] <i class="…data-header-icon"></i>
          [?] <img class="data-header-icon data-table-header-svg-icon" src="…">
          [?] <img class="eael-data-table-th-img" style="width:Npx" src="…" alt="…">

          <span class="data-table-header-text">Header Label</span>
        </th>
        …
      </tr>
    </thead>
    <tbody>
      <!-- For each row marker, emit a <tr>; cells are matched by row_id -->
      <tr>
        <!-- Per col Repeater item: -->
        <td [class="<css-class>"]
            [id="<css-id>"]
            [colspan="N"]
            [rowspan="N"]>

          <div class="td-content-wrapper">

            [?] <!-- JS-injected on responsive mode: -->
            <div class="th-mobile-screen">{matching header label}</div>

            <!-- Per content_type: -->
            [?] <div class="td-content [eael-datatable-icon]">     ← icon type
              <i class="…" aria-hidden="true"></i>  or  <svg>…</svg>
            </div>

            [?] <a href="…" …>Cell text</a>                       ← textarea + link
            [?] <div class="td-content">Cell text</div>           ← textarea (no link), wp_kses-filtered
            [?] <div class="td-content">{builder content}</div>   ← template; Plugin::frontend->get_builder_content()
          </div>
        </td>
        …
      </tr>
      …
    </tbody>
  </table>
</div>
```

Notes:

- Every `<td>` wraps its content in `.td-content-wrapper` — JS targets this when prepending the mobile `<div class="th-mobile-screen">` so the original cell content stays untouched.
- The `tablesorter` class is on the `<table>` unconditionally; without Pro, no `<th>` has the `sorting` class so the plugin treats every column as un-sortable. Adding the class manually (via custom JS) wouldn't enable sorting without `enableProSorter` being defined either.
- For "icon" content type with image-library FA5 icons, the SVG markup gets wrapped in `<div class="eael-datatable-icon td-content">` instead of a plain `<div class="td-content">`.
- Header `<th>` `colspan` is from `eael_data_table_header_col_span` (TEXT input, allows any value — not validated to be an integer at the PHP side). Cell `<td>` `colspan` / `rowspan` are NUMBER inputs with min=1.
- The `data-table_enabled` data attribute on the wrapper is only set when the Lite sorting toggle is on — but without Pro this is informational; nothing reads it.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Data_Table.php#L96) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_section_data_table_enabled` | SWITCHER | empty | Content → Header | **Pro feature** — Lite version is a decoy + Pro-alert heading; Pro replaces via `eael_section_data_table_enabled` action |
| `eael_pricing_table_style_pro_alert` | HEADING | — | Content → Header | ⚠ Reused control id from Pricing Table — cosmetic only, displays Pro alert |
| `eael_data_table_header_cols_data` | REPEATER | 4 default rows | Content → Header | Header columns (see sub-table) |
| `eael_data_table_content_rows` | REPEATER | 1 row + 4 col defaults | Content → Content | Body rows (see sub-table) |
| `ea_adv_data_table_export_csv_button` | BUTTON (event=`ea:table:export`) | — | Content → Export | Editor-only CSV export |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| `table_width` | SLIDER (responsive) | 100% | Style → General Style | `max-width` on `.eael-data-table` |
| `table_alignment` | CHOOSE | `center` | Style → General Style | Prefix class `eael-table-align-` |
| `eael_enable_responsive_header_styles` | SWITCHER | empty | Style → Header Style (Responsive subsection) | Enables custom-responsive mode (inline `<style>` + JS th-label injection) |
| `eael_data_table_responsive_breakpoint` | NUMBER (px) | `767` | Style → Header Style | Breakpoint for the inline-style media query |
| Style → Header Style / Body Style / Cells / Responsive Header | various | — | Style tab | Typography, padding, border, background, hover states |

### Per-item Header Repeater controls (`eael_data_table_header_cols_data`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_data_table_header_col` | TEXT (dynamic, AI) | `Table Header` | Header text; `wp_kses(Helper::eael_allowed_tags())`-filtered |
| `eael_data_table_header_col_span` | TEXT (dynamic, AI) | empty | `<th colspan="…">` (no integer validation) |
| `eael_data_table_header_col_icon_enabled` | SWITCHER | empty | Show icon next to label |
| `eael_data_table_header_icon_type` | CHOOSE | `icon` | `icon` (FA) vs `image` (MEDIA) |
| `eael_data_table_header_col_icon_new` (+ FA4 shim `eael_data_table_header_col_icon`) | ICONS | empty | Header icon |
| `eael_data_table_header_col_img` / `_img_size` | MEDIA / NUMBER | empty / — | Image icon + width in px |
| `eael_data_table_header_css_class` / `_css_id` | TEXT | empty | `<th>` class / id |

### Per-item Content Repeater controls (`eael_data_table_content_rows`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_data_table_content_row_type` | SELECT | `row` | `row` (`<tr>` marker) or `col` (`<td>` cell) |
| `eael_data_table_content_type` | CHOOSE | `textarea` | `icon` / `textarea` / `editor` / `template` — gates per-content-type controls |
| `eael_data_table_content_row_colspan` / `_rowspan` | NUMBER (min 1) | `1` / `1` | `<td colspan>` / `<td rowspan>` (only emitted when > 1) |
| `eael_data_table_icon_content_new` (+ FA4 shim `_icon_content`) | ICONS | `fas fa-home` | Cell icon (when type=icon) |
| `eael_data_table_content_row_title` | TEXTAREA (dynamic) | `Content` | Cell text (when type=textarea); `wp_kses`-filtered |
| `eael_data_table_content_row_content` | WYSIWYG | `Content` | Cell content (when type=editor) |
| `eael_primary_templates_for_tables` | SELECT | empty | Saved Template id (when type=template); WPML-translated via `wpml_object_id` |
| `eael_data_table_content_row_title_link` | URL (dynamic) | empty | Wraps cell text in `<a>` (only when type=textarea) |
| `eael_data_table_content_row_css_class` / `_css_id` | TEXT (dynamic, AI) | empty | `<td>` class / id |

## Conditional Dependencies

```text
# Pro gate
eael_pricing_table_style_pro_alert      → visible when eael_section_data_table_enabled == 'true'
                                          (Pro replaces with real Sort Type / Asc-Desc controls)

# Header icon
eael_data_table_header_col_icon_new     → visible when header_col_icon_enabled == 'true'
                                          AND header_icon_type == 'icon'
eael_data_table_header_col_img          → visible when header_col_icon_enabled == 'true'
                                          AND header_icon_type == 'image'

# Content rows — gated by row_type
(colspan / rowspan / content_type / icon / textarea / editor / template / link / css-class / css-id)
                                        → all visible when row_type == 'col'

# Content type
eael_primary_templates_for_tables       → visible when content_type == 'template'
eael_data_table_icon_content_new        → visible when content_type == 'icon'
eael_data_table_content_row_title       → visible when content_type == 'textarea'
eael_data_table_content_row_content     → visible when content_type == 'editor'
eael_data_table_content_row_title_link  → visible when content_type == 'textarea'

# Pro upsell
eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active

# Responsive
eael_data_table_responsive_breakpoint   → visible when eael_enable_responsive_header_styles == 'yes'
                                          (most other Responsive-section controls similarly gated)
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_section_data_table_enabled` | action (emitted) | `(Data_Table $widget)` | **Pro extension** — injects the real Table Sorting controls (sort type, asc/desc default, etc.) inside the Header controls section. ⚠ Un-prefixed (no `eael/`); legacy. |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | (a) Hides the Pro-alert switcher & adds `sorting` class to `<th>` when active; (b) hides the `eael_section_pro` upsell. |
| `wpml_object_id` | filter (consumed) | `(int $id, 'wp_template', true)` | Translate Saved-Template id per language; see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation). |

JS-side custom events / globals:

- `enableProSorter` — **defined by Pro**, called by Lite. Global function on `window`. Lite checks `typeof enableProSorter !== 'undefined'` and calls `enableProSorter($, $_this)` on `document.ready` if Pro is loaded.
- `ea:table:export` — control button event name (string), routed through Elementor's control `'event'` mechanism. Listened for via `event.target.dataset.event` inside the panel-click handler, ONLY in edit mode.
- No frontend custom events broadcast; does NOT subscribe to cross-widget reflow events.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): FA4 shim, WPML, `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger (frontend):** `jQuery(window).on('elementor/frontend/init', …)` → `elementorFrontend.hooks.addAction('frontend/element_ready/eael-data-table.default', dataTable)`. Older registration pattern (NOT the newer `eael.hooks.addAction("init", "ea", …)`).
- **Guard:** `if (eael.elementStatusCheck('eaelDataTable')) return false;` at module top.
- **Vendor dependency:** none in Lite; Pro adds jQuery TableSorter (loaded by Pro's asset declarations) and defines `enableProSorter` global.
- **Reads on init:** `data-table_id` and `data-custom_responsive` from `.eael-data-table-wrap`.
- **Branches:**
  - `enableProSorter` defined — call it inside `$(document).ready(…)`.
  - `data-custom_responsive === true` — iterate every `<td>` inside the body, prepend `<div class="th-mobile-screen">{matching header text}</div>` inside `.td-content-wrapper`. The matching is by index: if the table has more `<td>`s in a row than `<th>`s, the extra cells get an empty `<div class="th-mobile-screen"></div>`.
- **Editor-only:** `elementor.hooks.addAction("panel/open_editor/widget/eael-data-table", data_table_panel)` — attaches a click listener to the Elementor panel element; on `ea:table:export` button click, scrapes the table from the editor iframe via `view.el.querySelector(…)`, joins inner text into CSV rows (`JSON.stringify` per cell — so any embedded quotes get escape-encoded), creates a Blob, appends a hidden `<a>` to `parent.document.body`, clicks it, removes it. Listener is removed on `panel.currentPageView.on("destroy", …)`.
- **No cross-widget reflow:** the widget doesn't subscribe to `ea-toggle-triggered`, `ea-lightbox-triggered`, etc. Data Table inside a hidden tab doesn't recompute on activation — but since the table is static HTML with no measurement, this is fine.

## Common Issues

### Sorting doesn't work even with the toggle enabled

- **Likely cause:** Pro is not active. The Lite toggle only shows a Pro-alert heading; without Pro, no `sorting` class is added to `<th>` and `enableProSorter` global isn't defined.
- **Diagnose:** browser DevTools — check whether `<th>` has the `sorting` class.
- **Fix:** install Pro. Lite's toggle is a placeholder.

### Cells appear in wrong rows

- **Likely cause:** missing `'row'` marker before some `'col'` cells. Render at [line 1314-1349](../../includes/Elements/Data_Table.php#L1314) groups cells under the most-recent row id (`uniqid()`-stamped); cells without a preceding row marker get attached to whichever row was last open (or no row at all → silently dropped).
- **Diagnose:** check the Content Repeater order — every group of `col` entries must be preceded by a `row` marker.
- **Fix:** add a `row` entry at the start of each row group.

### Mobile responsive mode hides header but cells don't show column labels

- **Likely cause:** JS at [JS line 16-26](../../src/js/view/data-table.js#L16) only injects `.th-mobile-screen` when `data-custom_responsive === true`. Check that the panel toggle `eael_enable_responsive_header_styles` is actually `'yes'`.
- **Diagnose:** inspect `.eael-data-table-wrap` — does it have `data-custom_responsive="true"`?
- **Fix:** enable the responsive toggle; clear page cache so the wrapper attribute updates.

### CSV export button does nothing on the live page

- **Likely cause:** the export button is **editor-only**. The `Data_Table_Click_Handler` is only bound when `isEditMode`. There is no frontend export path.
- **Diagnose:** the button is a `Controls_Manager::BUTTON` — it exists only in the editor panel, not in render output.
- **Fix:** for frontend export, you'd need to render your own download link in custom JS. Not supported by the widget.

### CSV export produces empty cells

- **Likely cause:** `cols[j].innerText` returns empty for cells containing only icons or images (innerText excludes alt text and non-text content).
- **Diagnose:** open the editor, click export, inspect the downloaded CSV.
- **Fix:** known limitation — cells with only icons/images export as empty. Use textarea content type for exportable cells.

### Saved Template cells return empty

- **Likely cause:** the template id passed to `eael_primary_templates_for_tables` doesn't satisfy `Helper::is_elementor_publish_template()` (template post is not published, or not built with Elementor).
- **Diagnose:** verify template publish status in Templates → Saved Templates.
- **Fix:** publish the template; for multilingual sites, ensure WPML translation exists since `wpml_object_id` swaps the id.

### Sorting works but icon column sorts unexpectedly

- **Likely cause:** TableSorter sorts by text content. An icon-only cell has empty text; multiple such cells appear equal during sort.
- **Diagnose:** browser DevTools — does the icon `<i>` have any text content?
- **Fix:** Pro feature — use Pro's sort-type-per-column config to mark icon columns as non-sortable.

## Known Limitations

- **Flat Repeater with row markers is error-prone** — `'row'` and `'col'` types intermixed in one list. Adding a cell without a preceding row marker is silently misrouted. No PHP validation; no editor visual feedback.
- **Header `eael_data_table_header_col_span` is a TEXT input** ([line 152-163](../../includes/Elements/Data_Table.php#L152)) — accepts non-integer values which then become invalid `colspan` attributes. Most browsers tolerate this, but it's loose.
- **CSV export uses `JSON.stringify(text)` per cell** ([JS line 43](../../src/js/view/data-table.js#L43)) — produces correctly-quoted CSV for most content but produces double-double-quotes for empty cells (`""""` vs `""`) and may break Excel imports on certain whitespace patterns.
- **CSV export reads `event` as `window.event` global** ([JS line 31](../../src/js/view/data-table.js#L31)) — non-standard pattern; only works because the handler is bound as a click listener and runs in non-strict-mode jQuery context. Wouldn't work in ES modules.
- **No frontend CSV export** — the export button is panel-only. Sites needing public-facing CSV download must build it themselves.
- **`content_template()` is empty** ([line 1508](../../includes/Elements/Data_Table.php#L1508)) — disables JS preview in the editor; falls back to full PHP-rendered preview on every change. Slower but accurate.
- **Inline `<style>` block per widget in responsive mode** — adds ~300 bytes per Data Table. Multiple Data Tables on the same page emit duplicate styles scoped only by widget id (necessary for the per-widget breakpoint).
- **Sorter requires global function** — `enableProSorter` lives on `window` (defined by Pro). Lite has no fallback sorter (e.g., bundled TableSorter). The `tablesorter` class on `<table>` does nothing without Pro.
- **One un-prefixed legacy hook** — `eael_section_data_table_enabled` predates the `eael/<context>/<action>` naming convention. Renaming requires dual-emit migration to avoid breaking Pro.
- **Reused control id** — `eael_pricing_table_style_pro_alert` at [line 125](../../includes/Elements/Data_Table.php#L125) is copy-pasted from Pricing Table. Cosmetic, but confusing for grep. Same legacy mislabel as Progress Bar.
- **Typo in section id** — `eael_section_data_table_cotnent` ([line 300](../../includes/Elements/Data_Table.php#L300)) instead of `_content`. Internal only; not exposed.
- **No cross-widget reflow listeners** — Data Table inside a tab doesn't trigger any recompute on activation. Fine for static HTML tables, but Pro's sorter inside a hidden tab may need a manual `enableProSorter` re-run.
- **JS-injected `.th-mobile-screen` runs even in non-responsive mode** ([JS line 12-26](../../src/js/view/data-table.js#L12)) — actually conditional, but the loop iterates every cell. For very large tables (~100+ cells), an O(n) traversal on every page load. Cheap, but noticeable on extremely long tables.
- **`get_style_depends()` returns `font-awesome-4-shim`** ([line 1302](../../includes/Elements/Data_Table.php#L1302)) — deprecated handle in modern Elementor versions.
