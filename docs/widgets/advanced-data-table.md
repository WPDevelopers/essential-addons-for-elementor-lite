# Advanced Data Table Widget

> Source-driven HTML data table — seven sources (static + CSV in Lite; Ninja Tables via third-party plugin; Database, Remote Database, Google Sheets, TablePress in Pro). The editor experience is unusually rich: Quill bubble editor for inline cell editing, drag-to-resize column widths, right-click context menu (Add Row Above/Below, Add/Delete Column), CSV paste-import with first-row-as-header toggle, and CSV export. Front-end features: search by row text, button or select pagination, click-to-sort columns, and Ninja Tables WooCommerce add-to-cart hookup. The widget renders the raw table HTML string stored in a HIDDEN control through `wp_kses`, sanitizes static-source rendered content with DOMPurify (54 attributes allowlist), and dispatches non-Lite sources through the `eael/advanced-data-table/table_html/integration/<source>` filter.

**Class file:** [`includes/Elements/Advanced_Data_Table.php`](../../includes/Elements/Advanced_Data_Table.php)
**Slug:** `advanced-data-table` (widget id `eael-advanced-data-table`)
**Public docs:** <https://essential-addons.com/elementor/docs/advanced-data-table/>
**Pro-shared:** ✅ Yes — Pro adds four data sources (`database`, `remote`, `google`, `tablepress`) via two extension hooks: `do_action('eael/advanced-data-table/source/control', $this)` (legacy, marked `// TODO: RM` at [line 161](../../includes/Elements/Advanced_Data_Table.php#L161)) and `do_action('eael/controls/advanced-data-table/source', $this)` (current). Per-source rendering is dispatched through `apply_filters('eael/advanced-data-table/table_html/integration/<source>', $settings)` so each Pro source registers a filter to return the table HTML.

---

## Overview

Advanced Data Table treats the table HTML as a string stored in a HIDDEN control (`ea_adv_data_table_static_html` for static source, `ea_adv_data_table_csv_html` for CSV) and renders it through `wp_kses(Helper::eael_allowed_tags(), Helper::eael_allowed_protocols())` at runtime. Lite supports two native sources (static and CSV) plus a free Ninja Tables integration (gated on the Ninja Tables plugin being active). Pro registers four more sources by hooking `eael/controls/advanced-data-table/source` (controls) and `eael/advanced-data-table/table_html/integration/<source>` (render). Database source is additionally gated on `current_user_can('install_plugins')` — non-admin users never see the option. Front-end features (search / sort / pagination / Woo) are vanilla DOM operations in a 451-line `advancedDataTable` class. The editor is a 675-line `advancedDataTableEdit` class that bootstraps Quill, listens for clicks on the panel's CSV import/export buttons, binds mousedown/mousemove/mouseup on `<th>` for column-width drag, and pushes a right-click context menu group via `elementor.hooks.addFilter('elements/widget/contextMenuGroups', …)`. The edit JS exposes class state through `eael.hooks` filters (`advancedDataTable.getClassProps` / `setClassProps`) so context-menu callbacks can read it without a tight class reference.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Static source (Quill-edited HTML in panel) | ✅ | ✅ |
| CSV source (paste + parse + Quill edit) | ✅ | ✅ |
| Ninja Tables source | ✅ (requires Ninja Tables plugin) | ✅ |
| Database source | ❌ — shown as "Database (Pro)" in chooser; also hidden entirely from users lacking `install_plugins` | ✅ |
| Remote Database source | ❌ — shown as "Remote Database (Pro)" | ✅ |
| Google Sheets source | ❌ — shown as "Google Sheets (Pro)"; has cache-time setting | ✅ |
| TablePress source | ❌ — shown as "TablePress (Pro)" (also requires TablePress plugin) | ✅ |
| Inline Quill editor in panel (bubble theme) | ✅ | ✅ |
| Drag-to-resize column widths | ✅ | ✅ |
| Right-click context menu (Add Row Above/Below, Add Column Left/Right, Delete) | ✅ (static source only) | ✅ |
| Front-end search, sort, pagination (button or select) | ✅ | ✅ |
| Ninja Tables WooCommerce add-to-cart hookup (`.nt_button_woo`, `.nt_woo_quantity`) | ✅ | ✅ |
| CSV import (with first-row-as-header toggle) | ✅ | ✅ |
| CSV export (editor-only) | ✅ | ✅ |
| DOMPurify sanitization of static-source cell HTML (54-attr allowlist) | ✅ | ✅ |
| `eael_section_pro` upsell panel | ❌ — none; Pro alert is an inline RAW_HTML control instead | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Advanced_Data_Table.php`](../../includes/Elements/Advanced_Data_Table.php) | PHP widget class (1754 lines) — controls, `render()`, `get_table_content()`, `ninja_integration()` |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `eael_allowed_tags()`, `eael_allowed_protocols()`, `eael_e_optimized_markup()` |
| [`src/css/view/advanced-data-table.scss`](../../src/css/view/advanced-data-table.scss) | Source styles (175 lines) — table styling, sort/pagination/search visuals, hover states |
| [`src/js/view/advanced-data-table.js`](../../src/js/view/advanced-data-table.js) | Frontend logic (451 lines) — `advancedDataTable` class: `initFrontend`, `initTableSearch`, `initTableSort`, `initTablePagination`, `initWooFeatures` |
| [`src/js/edit/advanced-data-table.js`](../../src/js/edit/advanced-data-table.js) | Editor logic (675 lines) — `advancedDataTableEdit` class: Quill inline editor, column-width drag, context menu, CSV import/export |
| [`config.php`](../../config.php#L337) entry `'advanced-data-table'` | `Asset_Builder` dependency declaration: quill.bubble.min.css + advanced-data-table.min.css (view) + quill.min.js (edit) + DOMPurify (view) + advanced-data-table.min.js (view) + edit/advanced-data-table.min.js (edit) |
| `assets/front-end/css/lib-edit/quill/quill.bubble.min.css` | Vendor — Quill bubble theme stylesheet (editor only) |
| `assets/front-end/js/lib-edit/quill/quill.min.js` | Vendor — Quill 1.x WYSIWYG editor (editor only) |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor — DOMPurify (sanitizes decoded static-source cell HTML on the frontend) |

## Architecture

- **Table is a stored HTML string, not a structured Repeater** — `ea_adv_data_table_static_html` and `ea_adv_data_table_csv_html` are `Controls_Manager::HIDDEN` controls that hold raw `<thead>…</thead><tbody>…</tbody>` markup ([line 167-180](../../includes/Elements/Advanced_Data_Table.php#L167)). Quill inline-editor in the panel mutates the DOM directly, then the edit script writes the updated `<table>.innerHTML` back to the model via `parent.window.$e.run('document/elements/settings', …)`. This pattern means the source-of-truth is HTML, not a normalized cell array — robust against schema changes, but `wp_kses` filtering at render time defines the allowed tags + attrs.
- **Two-tier sanitization** — server-side, `wp_kses($content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols())` at [line 1632](../../includes/Elements/Advanced_Data_Table.php#L1632) strips disallowed tags/protocols before emit. Client-side, when the table has class `ea-advanced-data-table-static`, view JS iterates every `<th>` / `<td>` and runs cells matching `/&[a-zA-Z]+;/` through `DOMPurify.sanitize()` with an explicit 54-attribute allowlist covering Global / `<a>` / `<form>` / `<input>` / `<table>` / `<audio>` / `<video>` / `<svg>` / `<iframe>` / ARIA / form-elements / `<select>` / `<label>` ([JS line 32-71](../../src/js/view/advanced-data-table.js#L32)). The `isEscapedHtmlString()` check ensures only HTML-entity-encoded cells are decoded — purely-plain cells skip the decode step.
- **Source dispatch via filter chain** — `get_table_content()` at [line 1664-1694](../../includes/Elements/Advanced_Data_Table.php#L1664) returns: `static` / `csv` settings strings directly, calls `ninja_integration()` for Ninja Tables, and for any other source applies `eael/advanced-data-table/table_html/integration/<source>` filter — Pro registers one filter per source (database, remote, google, tablepress) to return its rendered HTML. If a filter returns an array (instead of a string), `get_table_content()` returns empty — Pro uses this as a signal-back-to-empty short-circuit.
- **Two Pro control extension hooks, one legacy** — `do_action('eael/advanced-data-table/source/control', $this)` at [line 161](../../includes/Elements/Advanced_Data_Table.php#L161) is marked `// TODO: RM` (slated for removal); `do_action('eael/controls/advanced-data-table/source', $this)` at [line 164](../../includes/Elements/Advanced_Data_Table.php#L164) is the current naming. Both are still emitted in parallel so Pro can listen on the new name while old Pro releases keep working — dual-emit migration in progress.
- **`database` source double-gates on capability + Pro** — at [line 91-93](../../includes/Elements/Advanced_Data_Table.php#L91) the source is `unset` from the chooser when the user lacks `install_plugins`. Then at [line 95-100](../../includes/Elements/Advanced_Data_Table.php#L95), when Pro is inactive, the remaining four Pro sources get a `" (Pro)"` suffix in their labels. Selecting any of those four shows an inline RAW_HTML "Only Available in Pro Version!" notice instead of a separate upsell section.
- **Editor uses Quill bubble theme with custom HTML preservation** — each cell's original HTML is stashed in `dataset.quill` (URI-encoded) before Quill wraps the content in `<div class="inline-editor">` ([edit JS line 113-118](../../src/js/edit/advanced-data-table.js#L113)). Quill's `text-change` event updates `dataset.quill` continuously and pushes the parsed table back to the model. On panel close, `parseHTML()` strips `.inline-editor` wrappers and restores original cell HTML from `dataset.quill`. `cleanQuillHTML()` removes empty `<p>` tags Quill auto-inserts on every line.
- **Class-state via internal `eael.hooks` filters** — the edit class exposes `view`, `model`, `table`, `activeCell` through `eael.hooks.addFilter("advancedDataTable.getClassProps", …)` / `setClassProps` at [edit JS line 18-19](../../src/js/edit/advanced-data-table.js#L18). Context-menu callbacks (which lose `this` because they're plain functions registered with Elementor) read state via `applyFilters("advancedDataTable.getClassProps")` instead of closing over `this`. Eight internal hooks: `getClassProps`, `setClassProps`, `parseHTML`, `initEditor`, `updateFromView`, `initInlineEdit`, `initPanelAction`, `triggerTextChange`, plus broadcasts `afterInitPanel` and `panelAction`.
- **Right-click context menu only for static source** — `initContextMenu()` at [edit JS line 433-436](../../src/js/edit/advanced-data-table.js#L433) checks `widgetType == "eael-advanced-data-table" && source == "static"` before registering the menu group. Non-static sources (CSV/Ninja/Pro) are read-only in the editor — you can edit them via Quill inline but can't add/remove rows/columns from the right-click menu.
- **CSV import is a custom in-flight parser** — at [edit JS line 332-355](../../src/js/edit/advanced-data-table.js#L332) parses pasted text character-by-character handling `""` escape and quoted-comma fields. NOT a regex split. First row optionally becomes `<thead>` based on a checkbox in the panel's RAW_HTML import textarea.
- **Editor-mode `tbody` row truncation** — when rendering in the editor with CSV source, `render()` at [line 1564-1621](../../includes/Elements/Advanced_Data_Table.php#L1564) loads the HTML via `DOMDocument` (with `<?xml encoding="UTF-8">` prefix to force UTF-8 parsing) and emits only the first N `<tbody>` rows where N = `items_per_page` (default 10). Avoids rendering 10k-row CSVs in the editor preview. Frontend renders all rows.
- **`elementor.config.version > "2.7.6"` backwards-compat branch** — `updateFromView()` at [edit JS line 38](../../src/js/edit/advanced-data-table.js#L38) uses modern `$e.run("document/elements/settings", …)` for newer Elementor, falls back to Backbone `model.setSetting()` for 2.7.6 and below. `remoteRender` flag toggles Elementor's debounced server-side rerender.
- **Drag-to-resize column widths persists per-source** — mousedown on `<th>` starts drag; mouseup writes either `ea_adv_data_table_static_html` (full table HTML if static) or `ea_adv_data_table_dynamic_th_width` (array of per-column width strings) for other sources ([edit JS line 215-238](../../src/js/edit/advanced-data-table.js#L215)). Double-click clears resize.
- **Ninja Tables `data_type` switch in PHP** — `ninja_integration()` at [line 1696-1752](../../includes/Elements/Advanced_Data_Table.php#L1696) reads Ninja's `data_type` per column and emits special cell markup for `image` (`<a><img>`), `selection` (comma-joined), `button` (`<a class="button">`), or plain text. Editor-mode pagination truncation applies here too.
- **Ninja Tables Woo integration is JS-side** — `initWooFeatures()` at [JS line 430-443](../../src/js/view/advanced-data-table.js#L430) adds `add_to_cart_button ajax_add_to_cart` classes to `.nt_button_woo` (Ninja Tables' Woo widget renders these) and binds quantity inputs to forward into the matching `.nt_add_to_cart_<product_id>` button's `data-quantity`. Triggers WooCommerce's existing AJAX add-to-cart without a separate widget integration.

## Render Output

```html
<div class="ea-advanced-data-table-wrap" data-id="<widget-id>">

  [?] <!-- Search bar — when ea_adv_data_table_search == 'yes' -->
  <div class="ea-advanced-data-table-search-wrap ea-advanced-data-table-search-<alignment>">
    <input type="search" placeholder="…" class="ea-advanced-data-table-search">
  </div>

  <div class="ea-advanced-data-table-wrap-inner">
    <table class="ea-advanced-data-table
                  ea-advanced-data-table-<source>             ← static | csv | ninja | database | remote | google | tablepress
                  ea-advanced-data-table-<widget-id>
                  [ea-advanced-data-table-sortable]           ← when sort == 'yes'
                  [ea-advanced-data-table-paginated]          ← when pagination == 'yes'
                  [ea-advanced-data-table-searchable]         ← when search == 'yes'
                  [ea-advanced-data-table-editable]"          ← editor mode only
           data-id="<widget-id>"
           [data-items-per-page="10"]>                        ← when paginated
      <!-- wp_kses-filtered output of get_table_content() -->
      <thead>
        <tr>
          <th [style="width: …px"]>Header</th>                ← style.width persists drag-resize for non-static sources
          …
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Cell content</td>
          …
        </tr>
        …
      </tbody>
    </table>
  </div>

  [?] <!-- Pagination wrapper — when pagination == 'yes' -->
  <!-- Editor preview is static demo markup; frontend is empty div that JS fills -->
  <div class="ea-advanced-data-table-pagination
              ea-advanced-data-table-pagination-<button|select>
              clearfix">
    <!-- Editor: hardcoded demo "« 1 2 »" links or <select><option>1</option></select> -->
    <!-- Frontend: empty; populated by initTablePagination() in JS -->
  </div>

  [?] <!-- Empty state — when get_table_content() returns empty -->
  <!-- Just the filtered no-content text from `eael/advanced-data-table/no-content-found-text` -->
  No content found
</div>
```

Notes:

- **No widget-specific Pro source class on table** — class names are predictable per source (`-static`, `-csv`, `-ninja`, etc.) which Pro keys frontend behaviour off of.
- The `<thead>` and `<tbody>` come from `wp_kses`-filtered HTML stored in HIDDEN controls (static/csv) or returned by source filters (other sources). No widget-emitted wrapper around individual cells.
- Static-source cells get an additional client-side DOMPurify pass — if a cell's `innerHTML` contains HTML entities like `&lt;`, the JS decodes via textarea trick then re-sanitizes. Non-static sources skip this.
- `ea-advanced-data-table-static`, `-csv`, `-ninja` classes all drive frontend feature attachment AND editor inline-edit logic — Quill is only initialized for static; CSV only allows column-width drag.
- The empty-state text passes through `apply_filters('eael/advanced-data-table/no-content-found-text', __('No content found', …))` so themes / Pro can localize the fallback.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Advanced_Data_Table.php#L71) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `ea_adv_data_table_source` | SELECT | `static` | Content → Data Source | One of seven sources; gates everything else. Pro suffix `(Pro)` on 4 entries when Pro inactive. `database` hidden entirely from non-admins. |
| `ea_adv_data_table_csv_string` | RAW_HTML (textarea + checkbox) | empty | Content → Data Source | CSV paste input + first-row-as-header toggle (visible when `source == 'csv'`) |
| `ea_adv_data_table_import_csv_button` | BUTTON (event=`ea:advTable:import`) | — | Content → Data Source | Editor-only — triggers CSV parser |
| `eael_adv_data_table_pro_enable_warning` | RAW_HTML | — | Content → Data Source | Pro alert link; visible when one of 4 Pro sources is selected without Pro |
| `ea_adv_data_table_static_html` | HIDDEN | 4×5 empty table | Content → Data Source | Raw HTML for static source; written by Quill |
| `ea_adv_data_table_csv_html` | HIDDEN | demo "John Doe / Jane Smith" table | Content → Data Source | Raw HTML for CSV source; written by CSV import |
| `ea_adv_data_table_sort` | SWITCHER | `yes` | Content → Advanced Features | Adds `ea-advanced-data-table-sortable` class; JS attaches click-to-sort |
| `ea_adv_data_table_search` | SWITCHER | `yes` | Content → Advanced Features | Adds `ea-advanced-data-table-searchable` class; renders search input |
| `ea_adv_data_table_search_placeholder` | TEXT (dynamic, AI) | `Search` | Content → Advanced Features | Search input placeholder |
| `ea_adv_data_table_pagination` | SWITCHER | `yes` | Content → Advanced Features | Adds `ea-advanced-data-table-paginated` class |
| `ea_adv_data_table_pagination_type` | SELECT | `button` | Content → Advanced Features | `button` (page links) vs `select` (dropdown) |
| `ea_adv_data_table_items_per_page` | NUMBER (min 1) | `10` | Content → Advanced Features | Rows per page; used in both frontend pagination AND editor `<tbody>` truncation for CSV source |
| `eael_global_warning_text` | RAW_HTML | — | Content → Advanced Features | Notice: "Pagination will be applied on Live Preview only" |
| `ea_adv_data_table_export_csv_button` | BUTTON (event=`ea:advTable:export`) | — | Content → Export | Editor-only — scrapes table, downloads CSV blob via parent document |
| `ea_adv_data_table_data_cache_limit` | NUMBER (min 1) | `60` | Content → Data Cache Setting | Google Sheets cache expiration in minutes; visible when `source == 'google'` only |
| `ea_adv_data_table_dynamic_th_width` | array (HIDDEN — set by edit JS) | empty | — | Per-column widths for non-static sources; persisted from drag-resize |
| Style → Table / Header / Body / Cell / Search / Pagination / Buttons | various | — | Style tab | Width, border, border-radius, padding, typography, hover, search-input style, pagination button style |

## Conditional Dependencies

```text
# Source gate
ea_adv_data_table_csv_string             → visible when source == 'csv'
ea_adv_data_table_import_csv_button      → visible when source == 'csv'
eael_adv_data_table_pro_enable_warning   → visible when source in [database, remote, google, tablepress] AND Pro not active
ea_adv_data_table_data_cache_limit       → visible when source == 'google' (section condition)

# Feature gates
ea_adv_data_table_search_placeholder     → visible when search == 'yes'
ea_adv_data_table_pagination_type        → visible when pagination == 'yes'
ea_adv_data_table_items_per_page         → visible when pagination == 'yes'
eael_global_warning_text                 → visible when pagination == 'yes'

# Pro upsell
(no eael_section_pro — Pro alert is inline RAW_HTML control gated by source value)
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/advanced-data-table/source/control` | action (emitted) | `(Advanced_Data_Table $widget)` | **Pro extension (legacy)** — marked `// TODO: RM`; still emitted for dual-emit migration. |
| `eael/controls/advanced-data-table/source` | action (emitted) | `(Advanced_Data_Table $widget)` | **Pro extension (current)** — Pro registers per-source control sections (Database connection, Google Sheets ID, TablePress table ID, Ninja Tables table ID). |
| `eael/advanced-data-table/table_html/integration/<source>` | filter (emitted) | `(string $content, array $settings)` | **Pro extension** — per-source render filter. Pro registers one per data source (database / remote / google / tablepress). Returning array short-circuits to empty. |
| `eael/advanced-data-table/no-content-found-text` | filter (emitted) | `(string $text)` | Customize the empty-state fallback text. |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Gates: source list `(Pro)` suffixes, source-not-supported short-circuit in render, Pro-alert RAW_HTML visibility. |
| `eael/is_plugin_active` | filter (consumed) | `string $plugin_path` | Gates TablePress and Ninja Tables render paths (returns empty if the third-party plugin is inactive). |

JS-side custom events / globals:

- `ea:advTable:export` — control button event; click handled by edit script's `initPanelAction()` in editor only.
- `ea:advTable:import` — same; triggers custom CSV parser.
- 10 internal `eael.hooks.*` for edit-script class state: `advancedDataTable.getClassProps`, `setClassProps`, `parseHTML`, `initEditor`, `updateFromView`, `initInlineEdit`, `initPanelAction`, `triggerTextChange`, `afterInitPanel`, `panelAction`.
- `elementor.hooks.addFilter('elements/widget/contextMenuGroups', …)` — adds the right-click context menu group (static source only).
- `elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', initPanel)` — editor panel open hook.
- Frontend: no broadcast custom events; does NOT subscribe to cross-widget reflow events.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply — no Liquid Glass, no FA4 shim (no icon controls), no WPML, no `has_pro` JS handoff, no `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger (frontend):** `eael.hooks.addAction("init", "ea", () => new advancedDataTable())` — class constructor binds `elementorFrontend.hooks.addAction('frontend/element_ready/eael-advanced-data-table.default', this.initFrontend.bind(this))`. Newer EA pattern.
- **Guard:** `if (eael.elementStatusCheck('eaelAdvancedDataTable')) return false;` — wraps `new advancedDataTable()`.
- **Edit mode skipped on frontend init** — `if (!eael.isEditMode && table !== null)` at [JS line 19](../../src/js/view/advanced-data-table.js#L19) — prevents frontend features (search/sort/pagination) from running inside the Elementor preview iframe; edit features are handled by the separate edit script instead.
- **Vendor dependencies (frontend):** DOMPurify (cell sanitization for static source). Sort + pagination + search are vanilla DOM operations — no jQuery TableSorter, no plugin.
- **Branches (frontend):**
  - source class `-static` — decode HTML entities + DOMPurify sanitize per cell.
  - sort enabled — bind click on `<th>` to toggle ascending/descending; collect rows into array, sort by cell `textContent`, reinsert.
  - pagination enabled — slice rows by `data-items-per-page`; render button or `<select>` pagination control.
  - search enabled — bind `input` event; case-insensitive `textContent.indexOf` match per row; hide non-matching `<tr>`. Adds `ea-advanced-data-table-unsortable` class during active search.
  - Woo features — add `add_to_cart_button ajax_add_to_cart` classes to `.nt_button_woo`; bind quantity input sync.
- **Edit-mode trigger:** `elementor.hooks.addAction('panel/open_editor/widget/eael-advanced-data-table', this.initPanel.bind(this))` — fires `initInlineEdit`, `initPanelAction`, broadcasts `afterInitPanel`. On editor close, runs `parseHTML(eaTable.cloneNode(true))` to strip Quill wrappers and stash final HTML.
- **Inline editor:** Quill bubble theme on every `<th>` / `<td>` (static source only). Cell `dataset.quill` holds URI-encoded original innerHTML; replaced with `<div class="inline-editor">…</div>` while Quill is active; restored on save. Quill `text-change` debounced via `clearTimeout` + `setTimeout(1001)` to throttle model updates against Elementor's render debouncer.
- **Drag column resize:** `mousedown` on `<th>` (any source) starts drag, `mousemove` updates `style.width`, `mouseup` persists. Static source writes the full updated `<table>.innerHTML`; non-static sources write only the per-column widths array to `ea_adv_data_table_dynamic_th_width`.
- **Right-click context menu:** registered via `elementor.hooks.addFilter('elements/widget/contextMenuGroups', this.initContextMenu)`. Menu group `ea_advanced_data_table` with actions: Add Row Above / Below, Add Column Left / Right, Delete Row / Delete Column / Duplicate Row / Duplicate Column. Each callback reads class state through `applyFilters("advancedDataTable.getClassProps")` (not `this`) since callbacks lose context.

## Common Issues

### Pro source selected but table renders as "No content found"

- **Likely cause:** Pro plugin not active OR Pro's source filter not registered for this source name.
- **Diagnose:** check `has_filter('eael/advanced-data-table/table_html/integration/<source>')`; verify Pro plugin is active.
- **Fix:** install/activate Pro. Source select gracefully falls back to empty content per `render()` short-circuit.

### Database source missing from chooser

- **Likely cause:** current user lacks `install_plugins` capability — the source is `unset` at [line 92](../../includes/Elements/Advanced_Data_Table.php#L92) before render.
- **Diagnose:** check user role; only admins (or roles with `install_plugins`) can use database source.
- **Fix:** sign in as admin, or grant `install_plugins` capability via a role-management plugin.

### CSV pasted but table doesn't update after Import click

- **Likely cause:** custom CSV parser at [edit JS line 332-355](../../src/js/edit/advanced-data-table.js#L332) requires `\n`-separated rows; if your CSV uses `\r\n`, the parser still works (split on `\n` leaves trailing `\r`, but the field accumulator handles it). However, the `clearInterval` polling at [line 388-394](../../src/js/edit/advanced-data-table.js#L388) waits for `view.el.querySelector('.ea-advanced-data-table').innerHTML` to match — if the markup transforms during render, the polling never resolves.
- **Diagnose:** browser DevTools — does the model attribute update? Inspect `ea_adv_data_table_csv_html` setting.
- **Fix:** save the page manually to force a re-render. If the issue persists, paste a smaller CSV to verify the parser; large CSVs (>1k rows) may hit Elementor's setting-size limits.

### Inline Quill editor doesn't appear

- **Likely cause:** the table source isn't `static`. Quill editor only initializes for `ea-advanced-data-table-static` class.
- **Diagnose:** inspect the table's class list.
- **Fix:** change source to Static, OR edit the CSV/Ninja source data externally and paste back.

### Right-click context menu missing

- **Likely cause:** widget source isn't `static` — context menu registration at [edit JS line 433-436](../../src/js/edit/advanced-data-table.js#L433) only fires for static-source widgets.
- **Diagnose:** check the source dropdown value.
- **Fix:** working as designed — only static source supports add/remove rows/columns from the menu.

### Search hides ALL rows instead of filtering

- **Likely cause:** offset calculation at [JS line 84](../../src/js/view/advanced-data-table.js#L84) reads `table.rows[0].parentNode.tagName` to detect `<thead>` — if your table has no `<thead>` (just `<tbody>`), offset is 0 and row 0 (likely header content) gets matched against the search string.
- **Diagnose:** check whether the table HTML has a proper `<thead>` wrapping the first row.
- **Fix:** wrap header row in `<thead>` (Static source) or edit the CSV import to use first-row-as-header toggle.

### Cell content shows `&amp;` instead of `&`

- **Likely cause:** cell HTML was double-encoded somewhere — `isEscapedHtmlString()` decodes once via the textarea trick, but doubly-encoded content (e.g., `&amp;amp;`) decodes to `&amp;` which then doesn't match the regex on the next pass.
- **Diagnose:** inspect cell `innerHTML` before and after JS init.
- **Fix:** re-enter cell content via Quill — Quill stores clean text and re-encodes on save. CSV imports should not have double-encoded content.

## Known Limitations

- **Table HTML stored as a single setting string** — `ea_adv_data_table_static_html` is one Elementor setting. Very large tables (1000+ rows) can hit setting-size limits or cause editor slowdowns. No streaming or chunked storage.
- **CSV editor preview is truncated to `items_per_page`** ([line 1589-1620](../../includes/Elements/Advanced_Data_Table.php#L1589)) — useful for performance, but the editor preview no longer mirrors the frontend exactly. Frontend renders all rows.
- **Two un-prefixed (NOT `eael/` form) for sources** is fine, but the legacy `eael/advanced-data-table/source/control` is marked `// TODO: RM` — eventual removal will be a public-contract change requiring Pro version coordination.
- **`elementor.config.version > "2.7.6"` legacy branch** ([edit JS line 38](../../src/js/edit/advanced-data-table.js#L38)) — supports Elementor 2.7.6 and below via Backbone direct model mutation. Eventually removable; minor maintenance burden today.
- **No FA4 shim** — no icon controls in this widget. No migration concern.
- **No frontend export** — CSV export button is editor-only, like Data_Table. Frontend users can't download CSVs through the widget.
- **DOMPurify ALLOWED_ATTR list is hardcoded** ([JS line 42-60](../../src/js/view/advanced-data-table.js#L42)) — 54 attributes covering common HTML5 elements. Custom attributes (`data-*` IS allowlisted) work, but any uncommon attribute (e.g., `is="autonomous-element"` for custom elements) gets stripped silently.
- **`ea_adv_data_table_pro_enable_warning` notice is RAW_HTML** — not a proper Elementor notice control. No dismissal, no consistent styling with other EA Pro alerts (which use HEADING).
- **No `eael_section_pro` upsell section** — unique among large widgets. Saves panel space but means no consolidated "Go Premium" pitch.
- **CSV export uses non-standard `event` global** ([edit JS line 282-283](../../src/js/edit/advanced-data-table.js#L282)) — same as Data_Table. Works because the handler is bound via DOM `onclick` (preserves `window.event`), would fail under strict mode ES modules.
- **`initFrontend` reads from `$scope[0].querySelector(".ea-advanced-data-table-pagination")`** but the pagination element may not exist (when pagination is off) — JS handles this with `null` checks. Defensive, but multiple null-guards.
- **`elementor.config.version` is a string comparison** — `"2.7.6"` > `"2.10.0"` is false because of string ordering, but Elementor moved to `"3.x.x"` years ago so the practical effect is "everyone is on the new code path". Cosmetic but technically buggy for hypothetical 2.10.x users.
- **DOMPurify is loaded on every page** — view context dependency in config.php, ~14KB. Wasted bandwidth on pages where the widget renders non-static sources (CSV/Ninja/Pro) that don't trigger the sanitization branch.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render cache active. Google Sheets source has its own cache (60-min default) inside Pro's filter, so this is OK for Google but may stale-cache static + CSV edits in some setups.
- **Ninja Tables `data_type == 'selection'` cell renders via `implode((array) $tr[…], ', ')`** at [line 1739](../../includes/Elements/Advanced_Data_Table.php#L1739) — but `implode` argument order changed in PHP 7.4 → 8.0 (8.0 only accepts glue-first OR array-only). Currently uses `glue, array` which is PHP 7.0+ compatible but deprecated in PHP 8.x; eventually breaks.
