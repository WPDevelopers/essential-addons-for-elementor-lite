# Asset Loading

How the plugin decides which CSS and JS files to load on a given page, builds per-post bundles, handles popups and theme-builder locations, and exposes extension points.

This is the most-touched subsystem when debugging "my widget's CSS isn't loading" or "why is this lib loading on every page" — and the area `Asset_Builder` covers spans 529 lines, with cooperating logic in `Elements_Manager` (418 lines) and a small `Enqueue` trait (144 lines).

## Overview

The plugin avoids the naive approach of enqueueing every widget's assets on every page. Instead it does three things:

1. On every Elementor save, it walks the post's element tree and stores a flat list of EA widgets and extensions used → in the post meta key `_eael_widget_elements`.
2. On every frontend request, it reads that cached list, looks each entry up in the `config.php` registry to get its CSS/JS deps, and writes a single concatenated `eael-{post_id}.css` and `eael-{post_id}.js` to disk.
3. On the page, it enqueues only those two per-post files (plus a small always-loaded `eael-general` bundle) — no matter how many widgets the page uses.

The result: a page with two EA widgets enqueues two files, not eighty.

## Components

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Classes/Asset_Builder.php`](../../includes/Classes/Asset_Builder.php) | 529 | Hook registration, frontend / editor / popup / theme-builder enqueue paths, inline CSS/JS, common asset registration |
| [`includes/Classes/Elements_Manager.php`](../../includes/Classes/Elements_Manager.php) | 418 | Widget detection (walks Elementor data tree), per-post cache (`_eael_widget_elements`), backward-compat slug map, dependency resolution from `config.php`, file generation |
| [`includes/Traits/Enqueue.php`](../../includes/Traits/Enqueue.php) | 144 | Compatibility shims for WPForms / Caldera / Gravity / reCAPTCHA / Beehive theme; editor and frontend admin styles |
| [`includes/Traits/Library.php`](../../includes/Traits/Library.php) | — | Provides `safe_path()`, `safe_url()`, `is_edit_mode()`, `is_preview_mode()`, `get_settings()` used across the asset paths |
| [`config.php`](../../config.php) | ~3,000 | The element registry — slug → class + CSS/JS deps. `Asset_Builder` reads this; widget devs write to it. |
| Per-post cache (post meta) | — | `_eael_widget_elements` (list of widget slugs), `_eael_custom_js` (per-page custom JS) |
| Per-post bundles (filesystem) | — | `EAEL_ASSET_PATH/eael-{post_id}.css` and `.js` — concatenated dep files |

## Architecture Diagram

```text
╔════════════════════════════════════════════════════════════════════╗
║ EDITOR SAVE PHASE                                                  ║
║                                                                    ║
║   User clicks Update in Elementor editor                           ║
║                                                                    ║
║   elementor/editor/after_save                                      ║
║       │                                                            ║
║       ▼                                                            ║
║   Elements_Manager::eael_elements_cache($post_id, $data)           ║
║       │                                                            ║
║       ▼                                                            ║
║   get_widget_list($data)                                           ║
║       │  walks data tree via Plugin::$instance->db->iterate_data() ║
║       │  identifies widgets where type starts with "eael-"         ║
║       │  resolves global widgets recursively                       ║
║       │  applies replace_widget_name() backward-compat map         ║
║       │  collects extensions from settings (e.g. eael_*_switch)    ║
║       ▼                                                            ║
║   save_widgets_list($post_id, $list, $custom_js)                   ║
║       │  writes post meta: _eael_widget_elements + _eael_custom_js ║
║       │  removes any stale cached files for this post              ║
║       │  if cache dir was used, regenerates the bundles            ║
║       ▼                                                            ║
║   (later: page load)                                               ║
╚════════════════════════════════════════════════════════════════════╝

╔════════════════════════════════════════════════════════════════════╗
║ FRONTEND PAGE LOAD PHASE                                           ║
║                                                                    ║
║   Browser requests a page                                          ║
║                                                                    ║
║   wp_enqueue_scripts (priority 100)                                ║
║       │                                                            ║
║       ▼                                                            ║
║   Asset_Builder::frontend_asset_load                               ║
║       │  registers eael-general handle (theme-aware deps)          ║
║       │  registers common assets: FA5, eael-reading-progress, etc. ║
║       │  builds localize object (ajaxurl, nonce, i18n, breakpoints)║
║       │  enqueues eael-general                                     ║
║       │                                                            ║
║   elementor/frontend/before_enqueue_styles                         ║
║       │                                                            ║
║       ▼                                                            ║
║   Asset_Builder::ea_before_enqueue_styles                          ║
║       │  reads _eael_widget_elements post meta                     ║
║       │  if non-empty:                                             ║
║       │     fires eael/before_enqueue_styles + ..._scripts actions ║
║       │     calls enqueue_asset($post_id, $elements)               ║
║       ▼                                                            ║
║   enqueue_asset                                                    ║
║       │  if css_print_method == 'external' (default):              ║
║       │     check has_asset() → if missing, generate_script()      ║
║       │     wp_enqueue_style('eael-{post_id}', .css)               ║
║       │  if 'internal':                                            ║
║       │     concatenate CSS into $css_strings → printed in footer  ║
║       │  (same dual path for JS)                                   ║
║       ▼                                                            ║
║   wp_footer                                                        ║
║       │  add_inline_css (priority 15)  → <style> tag if internal   ║
║       │  add_inline_js  (priority 100) → <script> tag if internal  ║
╚════════════════════════════════════════════════════════════════════╝

╔════════════════════════════════════════════════════════════════════╗
║ THEME-BUILDER + POPUP + TEMPLATE EDGE CASES                        ║
║                                                                    ║
║   elementor/theme/register_locations (priority 20)                 ║
║       → load_asset_per_location($instance)                         ║
║       → walks each Pro theme-builder location (header / footer /   ║
║         archive) and enqueues each document's EA assets            ║
║                                                                    ║
║   elementor/files/file_name (filter)                               ║
║       → load_asset_per_file($file_name)                            ║
║       → extracts post_id from the filename → reads its EA cache    ║
║       → covers popups and CSS-file-based template loading          ║
╚════════════════════════════════════════════════════════════════════╝
```

## Hook Timing

All hooks registered by [`Asset_Builder::init_hook()`](../../includes/Classes/Asset_Builder.php#L99), plus the editor-save hook owned by `Elements_Manager`. Listed in fire order across a save → page-load lifecycle:

| Hook | Priority | Phase | Handler | What it does |
| ---- | -------- | ----- | ------- | ------------ |
| `elementor/editor/after_save` | 10 | Editor save | `Elements_Manager::eael_elements_cache` | Writes `_eael_widget_elements` post meta from the data tree |
| `after_delete_post` | 10 | Post lifecycle | `Asset_Builder::delete_cache_data` | Removes per-post bundle files + post meta |
| `wp_enqueue_scripts` | **100** | Frontend | `Asset_Builder::frontend_asset_load` | Registers `eael-general`, common assets, localize |
| `elementor/frontend/before_enqueue_styles` | 10 | Frontend | `Asset_Builder::ea_before_enqueue_styles` | Reads cache → calls `enqueue_asset` for the page |
| `elementor/theme/register_locations` | **20** | Theme builder | `Asset_Builder::load_asset_per_location` | Walks each Pro theme-builder location and enqueues its EA assets |
| `elementor/files/file_name` (filter) | 10 | Popups / template files | `Asset_Builder::load_asset_per_file` | Extracts post_id from filename → enqueues that post's assets |
| `wp_footer` | **15** | Frontend | `Asset_Builder::add_inline_css` | Prints `<style>` if `css_print_method = internal` |
| `wp_footer` | **100** | Frontend | `Asset_Builder::add_inline_js` | Prints `<script>` if `js_print_method = internal` |
| `eael/before_enqueue_styles` (action) | — | Fires inside `ea_before_enqueue_styles` | Various traits (Enqueue, Woo) | Compatibility hooks (WPForms, Gravity, reCAPTCHA, etc.) |
| `eael/before_enqueue_scripts` (action) | — | Same | Various | Same |
| `eael/localize_objects` (filter) | — | Inside `frontend_asset_load` | — | Lets extensions add to the JS `localize` object |

## Data Flow

The complete trip from "user adds a Fancy Text widget in editor" to "browser executes Typed.js":

1. **In editor:** user drops the widget on the canvas. Settings panel renders from `register_controls()`. As the user adjusts controls, Elementor stores them in its in-editor state.
2. **User clicks Update.** Elementor saves the document. `elementor/editor/after_save` fires.
3. [`Elements_Manager::eael_elements_cache`](../../includes/Classes/Elements_Manager.php#L63) runs. It calls `get_widget_list($data)`, which uses [`Plugin::$instance->db->iterate_data()`](../../includes/Classes/Elements_Manager.php#L82) to walk every node in the Elementor tree.
4. For each node, the type is read (`widgetType` if widget, else `elType`). Global widgets are resolved by following `templateID` → recursively calling `get_widget_list()` on the linked document. Backward-compat slugs are normalised via [`replace_widget_name()`](../../includes/Classes/Elements_Manager.php#L215). Widget types prefixed with `eael-` are added to the list with the prefix stripped.
5. [`get_extension_list($element)`](../../includes/Classes/Elements_Manager.php#L154) inspects each element's settings for keys like `eael_particle_switch`, `eael_tooltip_section_enable`, `eael_wrapper_link_switch`, `eael_enable_custom_cursor`. These trigger cross-cutting extensions and get added to the list under their own slugs.
6. [`save_widgets_list`](../../includes/Classes/Elements_Manager.php#L246) writes the resulting flat array to post meta `_eael_widget_elements`. If the new list hashes the same as the old one, it returns early — no churn. If different, it removes any stale per-post bundle files and regenerates.
7. **Time passes. A visitor loads the page.**
8. `wp_enqueue_scripts` (priority 100) fires. [`Asset_Builder::frontend_asset_load`](../../includes/Classes/Asset_Builder.php#L115) runs: it registers `eael-general` (the always-loaded shared bundle, with theme-aware CSS deps), registers common assets (FA5, reading-progress, table-of-content, scroll-to-top), and builds the JS `localize` object (ajaxurl, nonce, i18n, Elementor breakpoints, cart URL).
9. `elementor/frontend/before_enqueue_styles` fires. [`ea_before_enqueue_styles`](../../includes/Classes/Asset_Builder.php#L156) reads `_eael_widget_elements` from post meta. If empty, returns early — nothing to enqueue. If non-empty, fires `eael/before_enqueue_styles` and `eael/before_enqueue_scripts` actions (compatibility shims listen here), then calls `enqueue_asset`.
10. [`enqueue_asset`](../../includes/Classes/Asset_Builder.php#L424) branches on `css_print_method` and `js_print_method`:
    - **External (default):** check `has_asset($post_id, 'css')` — does `EAEL_ASSET_PATH/eael-{post_id}.css` exist on disk? If not, call `generate_script()` to walk each widget's deps from `config.php`, read each file's contents, concatenate into the per-post bundle, write to disk. Then `wp_enqueue_style('eael-{post_id}', ...)`.
    - **Internal:** call `generate_strings()` to build the same concatenated content in memory, append to `$this->css_strings`. The string is printed in `wp_footer` priority 15 via `add_inline_css`.
11. **Browser receives HTML + `eael-{post_id}.css` and `eael-{post_id}.js`.** Elementor's runtime fires `elementor/frontend/init`. Each widget's JS handler (registered via `elementorFrontend.hooks.addAction("frontend/element_ready/eael-fancy-text.default", FancyText)`) runs against the `$scope` element.

For the runtime side of step 11 — JS lifecycle inside a widget — see [`editor-data-flow.md`](editor-data-flow.md).

## Configuration & Extension Points

### `config.php` registry schema

Every widget and extension that needs assets has an entry under the top-level `elements` key:

```php
'fancy-text' => [
    'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
    'dependency' => [
        'css' => [
            [
                'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/fancy-text.min.css',
                'type'    => 'self',     // 'self' = built from src/, 'lib' = vendor in lib-view/
                'context' => 'view',     // 'view' = frontend, 'edit' = Elementor editor
            ],
        ],
        'js' => [ /* same shape; vendor libs first, self last (load order matters) */ ],
    ],
],
```

| Field | Values | Meaning |
| ----- | ------ | ------- |
| `type` | `self` / `lib` | `self` = built file from `src/` (we built it); `lib` = vendor library in `assets/front-end/js/lib-view/` |
| `context` | `view` / `edit` | `view` enqueues on frontend; `edit` enqueues only inside Elementor editor |
| `file` | absolute path | Use `EAEL_PLUGIN_PATH` constant; trailing slash semantics matter (don't accidentally double-slash) |

JS `lib` entries should be listed **before** the widget's own `self` entry, since `Asset_Builder` concatenates in declaration order and the widget JS depends on the libs being defined first.

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/localize_objects` | `Asset_Builder::load_commnon_asset` ([line 391](../../includes/Classes/Asset_Builder.php#L391)) | Add or modify the JS `localize` object passed to `eael-general`. Other widgets / extensions use this to expose nonces, paths, settings to their JS. |
| `eael/filterble-gallery/mfp-counter-text` | Inside the localize array | Customise Magnific Popup counter text |
| `eael_lr_recaptcha_api_args` | `Enqueue::before_enqueue_styles` ([line 46](../../includes/Traits/Enqueue.php#L46)) | Modify reCAPTCHA API args (render mode, language) |
| `elementor/files/file_name` | Elementor core | EA hooks `load_asset_per_file` here to handle popups / template files |

### Actions

| Action | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/before_enqueue_styles` | `ea_before_enqueue_styles`, `load_asset_per_location`, `load_asset_per_file` | Compatibility shims: WPForms / Caldera / Gravity / reCAPTCHA listen here to register their own assets when the relevant EA widget is on the page |
| `eael/before_enqueue_scripts` | Same | Same |
| `eael/register_custom_cursor_assets` | Inside `load_commnon_asset` | Pro extension hook for the custom-cursor feature |

### Settings options

| Option | Effect |
| ------ | ------ |
| `elementor_css_print_method` | `external` (default) = per-post `.css` file; `internal` = inline `<style>` in footer |
| `eael_js_print_method` | `external` (default) = per-post `.js` file; `internal` = inline `<script>` in footer |
| `custom-js` (in EA settings) | Enables the per-post custom JS feature (`_eael_custom_js` post meta) |

## Common Pitfalls

### "My widget's CSS is not loading" — checklist

The most reported issue. In order of likelihood:

- **Widget is in a popup, template, theme-builder location, or shortcode.** `_eael_widget_elements` is post-meta keyed on the *current page's* post ID — but the widget might live on a different document. Check `load_asset_per_location` (theme builder) and `load_asset_per_file` (popups, templates) — does the relevant document make it through?
- **Page was last saved before the widget was added.** Re-save the Elementor page once after dropping a new widget — Elementor doesn't re-save automatically.
- **Per-post cache file is stale.** Run **Elementor → Tools → Regenerate CSS & Data**. This deletes `EAEL_ASSET_PATH/eael-*.{css,js}` and forces regeneration on next page load.
- **`config.php` entry has the wrong slug.** The slug used in `config.php` must match what `get_widget_list()` produces — which is `eael-` stripped from the widget's `get_name()`. So `get_name() = 'eael-fancy-text'` → registry key `'fancy-text'`.
- **Widget's `get_name()` doesn't start with `eael-`.** Detection requires this prefix ([Elements_Manager.php:105](../../includes/Classes/Elements_Manager.php#L105)).

### `replace_widget_name()` mismatches

[The backward-compat map](../../includes/Classes/Elements_Manager.php#L215) renames legacy slugs (e.g. `eael-countdown` → `eael-count-down`). If you rename a widget without updating this map, old saved pages will quietly stop loading the renamed widget's assets.

### Internal CSS print mode bypasses per-post bundles

If a user toggles Elementor → Settings → CSS Method to "Internal Embedding", `enqueue_asset` skips the file-based path and concatenates everything inline. This is fine functionally but breaks browser caching across pages — so a site with many pages may regress visibly. Document this in the user-facing release notes if you ever change behavior here.

### Editor mode vs frontend mode are not symmetric

[`is_edit()`](../../includes/Classes/Asset_Builder.php#L512) returns true for editor / preview / WP preview. In edit mode, `frontend_asset_load` does NOT read `_eael_widget_elements` — it calls `get_settings()` directly to enumerate enabled widgets from EA settings, then enqueues all of them via `enqueue_asset(null, $elements, 'edit')`. So a widget's editor-time CSS bundle is shaped by EA settings, not by what's on the document. Don't be surprised when editor-mode loads more than frontend.

### Global widgets and the recursion trap

`get_widget_list` resolves global widgets ([line 91](../../includes/Classes/Elements_Manager.php#L91)) by recursively calling itself on the linked template's data. If a global widget's template links to itself, this recurses forever — Elementor doesn't have a built-in cycle guard. In practice this is rare, but a misconfigured global widget can hang the save handler.

### Extension toggle keys must exist in `get_extension_list`

If you add a new cross-cutting extension that's enabled via a control like `eael_my_new_thing_switch`, you must register that key in [`get_extension_list`](../../includes/Classes/Elements_Manager.php#L154) and add a corresponding registry entry in `config.php`. Otherwise the toggle works visually but no assets load.

### `kit` template type is excluded

Site-kit documents are skipped in [`excluded_template_type`](../../includes/Classes/Elements_Manager.php#L413) — they don't get cached. Don't try to use a kit document for site-wide EA assets; it won't work.

### `wp_localize_script('eael-general', ...)` only fires on non-edit OR internal-print mode

In edit mode with external print method, the `eael-general` handle isn't enqueued, so `wp_localize_script` calls on it silently do nothing. If your editor-mode JS depends on `localize.ajaxurl`, account for this.

### Beehive theme replaces the swiper handle

[`beehive_theme_swiper_slider_compatibility`](../../includes/Traits/Enqueue.php#L131) deliberately overrides the Beehive theme's swiper bundling. If a user reports broken swiper on Beehive, this is the place to look.

### Theme-aware CSS dependency in `eael-general`

[`register_script`](../../includes/Classes/Asset_Builder.php#L295) special-cases Hello Elementor 2.1+, Astra, XStore, and CartFlows — adding their theme handles to the dep array so EA's CSS loads after the theme's. If a user's theme isn't in this list and load order matters, it's a candidate fix here.

## Debugging Guide

When the asset path is suspected (Step 3c in the [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) skill's localize tree), walk these checks:

1. **Is the widget detected at all?** Open WordPress DB, find the post meta `_eael_widget_elements` for the page's post ID. The widget's slug should be in that array. If not, save the Elementor page once.
2. **Is the registry entry correct?** Search `config.php` for the slug. Confirm `class` namespace matches the actual class file. Confirm the file paths under `dependency.css` and `dependency.js` exist on disk.
3. **Does the per-post cache file exist?** Check `EAEL_ASSET_PATH/eael-{post_id}.css` and `.js`. If missing, hit "Regenerate CSS & Data" in Elementor admin, or trigger a page load that calls `frontend_asset_load`.
4. **Is the per-post handle enqueued?** In Network tab, search for `eael-{post_id}.css`. Status 200? If 404, the file write failed — check `EAEL_ASSET_PATH` permissions.
5. **Internal vs external mode?** If no per-post file exists at all, check Elementor → Settings → CSS Method. "Internal Embedding" means the CSS is inline — search the HTML source for `<style id="eael-inline-css">`.
6. **Edge case — popup / theme builder?** Search for `<link>` tags whose URL contains the popup's post ID instead of the page's. If the popup's assets aren't loading, `load_asset_per_file` is the responsible handler — confirm it's firing (add `error_log` if needed).
7. **Browser cache.** Hard-refresh (`Cmd+Shift+R`) or disable cache via DevTools — `eael-{post_id}.css` is versioned by `get_post_modified_time()`, so a stale cache is unlikely but possible.

## Worked Example — Fancy Text on a Single Page

Trace the full pipeline for a page containing one Fancy Text widget:

1. **Save:** [`get_widget_list`](../../includes/Classes/Elements_Manager.php#L77) walks the data tree, finds `widgetType: "eael-fancy-text"` on one node, strips `eael-` → `'fancy-text'`. No extensions are enabled. The list `['fancy-text' => 'fancy-text']` gets written to `_eael_widget_elements`.
2. **Page load:** `wp_enqueue_scripts` fires `frontend_asset_load`. `eael-general` is registered with theme-aware deps. The localize object is built and stored. `eael-general` is enqueued.
3. `elementor/frontend/before_enqueue_styles` fires. `ea_before_enqueue_styles` reads `_eael_widget_elements`, gets `['fancy-text' => 'fancy-text']`, fires the `eael/before_enqueue_styles` action (the WPForms / Gravity shims see `'fancy-text'` and do nothing — they only react to their own slugs), then calls `enqueue_asset($post_id, $elements)`.
4. `enqueue_asset` checks `has_asset($post_id, 'css')`. First request — the file doesn't exist. `generate_script` is called. It walks `config.php → 'fancy-text' → dependency → css`, finds one file (`fancy-text.min.css`), reads its bytes, concatenates into a string, prepends `general.min.css` (always-included shared bundle), and writes to `EAEL_ASSET_PATH/eael-{post_id}.css`. Same for JS — DOMPurify, Morphext, Typed.js, and `fancy-text.min.js` get concatenated into `eael-{post_id}.js`.
5. `wp_enqueue_style('eael-{post_id}', ...)` and `wp_enqueue_script('eael-{post_id}', ...)` register the per-post handles, both depending on `eael-general`.
6. **Subsequent page loads:** `has_asset` returns true (the file is on disk). No regeneration. The existing file is served with a `?ver=` based on `get_post_modified_time()`, so browsers cache it until the page is edited.

Net result: two CSS files on the page (`eael-general.min.css` and `eael-{post_id}.css`), two JS files (`eael-general.min.js` and `eael-{post_id}.js`), regardless of how many EA widgets the page has.

## Architecture Decisions

### Per-post bundles instead of per-widget asset enqueueing

- **Context:** A page with 5 EA widgets, naive enqueue, would mean 5 separate `<link>` tags + the widget JS — plus the `lib-view` libs each widget pulls. On WordPress.org with `concatenate_scripts` off, each is a request.
- **Decision:** Concatenate all needed CSS/JS for the page into one bundle each, written to disk, served via two `<link>` / `<script>` tags.
- **Alternatives rejected:** Per-widget enqueue (request count); inline everything (no browser cache across pages); webpack-style code splitting (build complexity not worth it for the WP plugin context).
- **Consequences:** First load on a new page is slower (file write); subsequent loads are fast and cached. Cache invalidation needs careful handling (`after_delete_post`, `eael_elements_cache` removes stale files).

### Cache key is `_eael_widget_elements` post meta, not a transient

- **Context:** Need to know what widgets a page uses without re-walking the data tree on every request.
- **Decision:** Store the flat list in post meta keyed on post ID. Updated on `elementor/editor/after_save`.
- **Alternatives rejected:** Transients (no per-post invalidation hook in WP core; would force expiration-based invalidation); compute on every request (slow).
- **Consequences:** The post-meta key is the durable source of truth. If it gets out of sync (e.g. a manual DB edit removes it), assets stop loading until the page is re-saved.

### `replace_widget_name` for backward compatibility

- **Context:** Several widgets were renamed in past versions (`eael-countdown` → `eael-count-down`, etc.).
- **Decision:** Keep a static map in `Elements_Manager` that translates legacy slugs at detection time.
- **Alternatives rejected:** Force users to re-save old pages (high support burden); migrate post meta in place (one-shot DB churn risk).
- **Consequences:** Whenever a slug is renamed, the map must be updated. The map is permanent — entries cannot be removed without breaking old saved pages.

### Internal vs external print method as a user setting

- **Context:** Some hosting environments restrict file writes to `wp-content/uploads/`. Internal print mode (inline CSS/JS) is a safety valve.
- **Decision:** Honour Elementor's existing `elementor_css_print_method` setting + add `eael_js_print_method` for symmetry.
- **Alternatives rejected:** Require external mode (host incompatibility); always inline (caching loss).
- **Consequences:** Two code paths for every enqueue — `enqueue_asset` branches throughout. All compatibility shims need to handle both modes.

## Known Limitations

- **First-load penalty:** On a brand-new page (or after Regenerate CSS & Data), the first request triggers `generate_script`'s file write — slower than subsequent requests. Tracked as a perf item; mitigated by serving cached files thereafter.
- **No request-level dedup of compat shims:** If a page has both `wpforms` and `caldera-form`, the `eael/before_enqueue_styles` action fires once — handlers must check the widget list themselves. They do, but it's a place worth verifying when adding new compat shims.
- **`get_post_modified_time` versioning:** Used as the asset version in `wp_enqueue_*`. If the post is touched without a substantive edit (admin metadata changes, etc.), the asset URL bumps — minor cache busting noise.
- **Theme-builder locations enqueued unconditionally:** `load_asset_per_location` enqueues every theme-builder document's EA assets on every front-end request, even if the location isn't displayed on this particular page. Generally fine because each is small per-post, but worth knowing.
- **`load_asset_per_file` regex is brittle:** [Line 238](../../includes/Classes/Asset_Builder.php#L238) extracts the post ID via `preg_replace( '/[^0-9]/', '', $file_name )`. A filename containing unrelated digits can give a wrong post ID. Hasn't bitten in practice but it's not robust.
- **Detection runs on every save, even for non-EA edits:** Saving a page that contains only Elementor core widgets still fires `eael_elements_cache`. The hash check at the end of `save_widgets_list` short-circuits the actual write, but the walk still happens.

## Cross-References

- **Skills:** [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) — when this subsystem is suspected, the **Asset path** trace in the skill's lookup table maps directly to this doc's debugging guide.
- **Skills:** [`new-widget`](../../.claude/skills/new-widget/SKILL.md) — Phase 6 (Register in `config.php`) writes the registry entry that this subsystem reads.
- **Rules:** [`asset-pipeline.md`](../../.claude/rules/asset-pipeline.md) — build commands, source → output map, vendor library conventions.
- **Widget docs:** [`fancy-text.md § Asset Dependencies`](../widgets/fancy-text.md#asset-dependencies) — concrete example of a widget's dep declaration and runtime behaviour.
- **Architecture:** [`editor-data-flow.md`](editor-data-flow.md) — how `$settings` arrives at `render()`, which produces the HTML this subsystem decorates with assets.
- **Architecture:** [`dynamic-data/`](dynamic-data/) — AJAX endpoints and dynamic queries that load *more* widget HTML at runtime; their assets are still enqueued via this subsystem on the parent page.
