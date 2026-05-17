# Custom JS Extension

> A page-level Custom JS field on every Elementor document. Adds a `CODE` control to the document's Advanced tab where an administrator can paste JavaScript that runs on that page. The JS is persisted as a separate post meta key (`_eael_custom_js`) and replayed by `Asset_Builder` while editing.

**Class file:** [`includes/Extensions/Custom_JS.php`](../../includes/Extensions/Custom_JS.php) (75 lines)
**Slug:** `custom-js` ([`config.php:1342`](../../config.php#L1342))
**Public docs:** <https://essential-addons.com/elementor/docs/custom-js/>
**Pro-shared:** Lite-only (no equivalent class lives under EA Pro; Pro inherits the extension by sharing the same Lite codebase when both are installed).

---

## Overview

`Custom_JS` is the smallest extension in the codebase. Its single job is to register one `CODE`-type control on every Elementor document. The user pastes JavaScript into that field; on save, the value lands in the `_elementor_page_settings` post meta as `eael_custom_js`, and is mirrored into a dedicated `_eael_custom_js` post meta key by [`Elements_Manager::eael_elements_cache()`](../../includes/Classes/Elements_Manager.php#L63).

The control sits under **Document Settings → Advanced → Custom JS** for every page / post / template built with Elementor. Below the code editor, the panel shows usage notes (jQuery or vanilla DOM selectors both work) and a docs link.

When the current user is not an administrator, an additional inline warning explains that only administrators can edit the field. The constructor does not block editing — it relies on a global filter elsewhere in the codebase (`elementor/document/save/data` in [`Bootstrap.php:325`](../../includes/Classes/Bootstrap.php#L325)) to discard any client-supplied `eael_custom_js` value for non-administrators by re-reading the previously saved value from post meta.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Custom_JS.php`](../../includes/Extensions/Custom_JS.php) | The extension class — single constructor + single `section_custom_js` callback that adds five controls in one section |
| [`config.php:1342`](../../config.php#L1342) | Registry entry: `'custom-js' => [ 'class' => '\Essential_Addons_Elementor\Extensions\Custom_JS' ]`. No `dependency` block — the extension ships no assets. |
| [`includes/Classes/Elements_Manager.php:23`](../../includes/Classes/Elements_Manager.php#L23) | `const JS_KEY = '_eael_custom_js'` — the post meta key the value lands in |
| [`includes/Classes/Elements_Manager.php:63`](../../includes/Classes/Elements_Manager.php#L63) | `eael_elements_cache()` — copies `eael_custom_js` from `_elementor_page_settings` to the standalone `_eael_custom_js` post meta on each editor save |
| [`includes/Classes/Asset_Builder.php:487`](../../includes/Classes/Asset_Builder.php#L487) | `load_custom_js( $post_id )` — reads `_eael_custom_js` and appends it to the per-request inline JS buffer |
| [`includes/Classes/Asset_Builder.php:269`](../../includes/Classes/Asset_Builder.php#L269) | `add_inline_js()` — emits `<script id="eael-inline-js">…</script>` in the footer, but **only when `is_edit_mode() || is_preview_mode()`** |
| [`includes/Classes/Bootstrap.php:324`](../../includes/Classes/Bootstrap.php#L324) | The non-administrator save guard — replaces submitted `eael_custom_js` with the previously stored value |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Class instantiates | ✅ (when enabled in `eael_save_settings`) | ✅ (same Lite class is reused) |
| Adds Custom JS control to document settings | ✅ | ✅ |
| `_eael_custom_js` post meta written on save | ✅ | ✅ |
| Frontend execution path | Partial — runs inside Elementor editor / preview only, see below | Same |

There is no separate Pro implementation. When Pro is active alongside Lite, this extension behaves identically — it's a shared surface.

## Architecture

- **Constructor wires a single hook.** [`add_action( 'elementor/documents/register_controls', [ $this, 'section_custom_js' ], 20 )`](../../includes/Extensions/Custom_JS.php#L14). Priority 20 (later than the default 10) leaves the panel below other EA-added document panels such as Reading Progress and Table of Content that hook the same action at priority 10.
- **One section, five controls.** The `section_custom_js` callback emits a single `Controls_Manager::TAB_ADVANCED` section labelled "Custom JS", containing: an instruction `RAW_HTML`, the `CODE` editor itself (`eael_custom_js`, `language => 'javascript'`), an optional non-admin warning, a usage hint, and a docs link.
- **Persistence path is split.** The value is saved by Elementor into `_elementor_page_settings` as `eael_custom_js`, then immediately mirrored into a dedicated `_eael_custom_js` post meta by `Elements_Manager::eael_elements_cache()` so that `Asset_Builder` can read it without parsing the larger settings blob on every request.
- **Capability split is enforced outside the extension.** The constructor does not check capabilities to decide who gets the editor — the control always appears. The non-admin warning is purely informative. The actual write-protection lives in [`Bootstrap.php:325`](../../includes/Classes/Bootstrap.php#L325), which hooks `elementor/document/save/data` and rewrites the submitted `eael_custom_js` back to the stored post-meta value when `! current_user_can( 'administrator' )`.

## Render Behavior

The extension itself emits no HTML directly. It registers a control; Elementor handles editor rendering.

On the frontend, the stored JS reaches the page via `Asset_Builder`:

1. `frontend_asset_load()` runs at `wp_enqueue_scripts:100` ([`Asset_Builder.php:115`](../../includes/Classes/Asset_Builder.php#L115)).
2. In the non-edit branch, it calls `load_custom_js( $this->post_id )` ([`Asset_Builder.php:126`](../../includes/Classes/Asset_Builder.php#L126)).
3. `load_custom_js()` checks the master toggle `$this->custom_js_enable = $this->get_settings('custom-js')` (the same `eael_save_settings` flag that controls instantiation) and short-circuits if disabled.
4. It then reads `_eael_custom_js`, appends `;` to defend against missing terminators, and concatenates into `$this->custom_js`.
5. `add_inline_js()` is hooked on `wp_footer:100`. It prints `<script id="eael-inline-js">…</script>` **only inside `is_edit_mode()` or `is_preview_mode()`** ([`Asset_Builder.php:271`](../../includes/Classes/Asset_Builder.php#L271)).

The published-page output path is the conspicuous gap — see [Known Limitations](#known-limitations).

## Asset Dependencies

N/A — the registry entry has no `dependency` block. The extension neither ships nor enqueues a JS / CSS file of its own.

The `Controls_Manager::CODE` control's editor (Ace) is provided by Elementor core.

## Hook Timing

| Hook | Priority | Phase | Effect |
| ---- | -------- | ----- | ------ |
| `elementor/documents/register_controls` | 20 | Editor — when Elementor builds the document settings panel | Adds the "Custom JS" section + 5 controls under the Advanced tab |
| `elementor/editor/after_save` (consumed by `Elements_Manager`, not this class) | 10 | Editor — on document save | Copies `eael_custom_js` from `_elementor_page_settings` into `_eael_custom_js` post meta |
| `elementor/document/save/data` (consumed by `Bootstrap`, not this class) | 10 | Editor — on document save, for non-administrators only | Restores any incoming `eael_custom_js` value to the previously stored value, blocking edits by non-admins |
| `wp_enqueue_scripts` (consumed by `Asset_Builder::frontend_asset_load`) | 100 | Frontend | Accumulates `_eael_custom_js` into the inline JS buffer |
| `wp_footer` (consumed by `Asset_Builder::add_inline_js`) | 100 | Frontend | Prints the buffer — only when `is_edit_mode() || is_preview_mode()` |

The extension class wires only the first hook. The other entries are consumed by other classes and are listed because they affect the end-to-end Custom JS lifecycle.

## Configuration & Extension Points

There are no filters or actions emitted by `Custom_JS` itself. Useful filter points are the ones from neighbours:

| Filter | Owner | Why it matters |
| ------ | ----- | -------------- |
| `eael/registered_extensions` | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Add or remove `custom-js` from the registry before instantiation |
| `eael_save_settings` (option, not a filter) | EA Settings page / Setup Wizard | Toggle `custom-js => 1` / `0` to enable or disable the extension wholesale |
| `elementor/document/save/data` | [`Bootstrap.php:325`](../../includes/Classes/Bootstrap.php#L325) | Where the non-admin save guard lives — extend here to add additional capability tiers |

The `eael_custom_js_usage` and `eael_custom_js_docs` controls inside the panel are pure `RAW_HTML` — their only role is to inform the user, not to configure anything.

## Customization Recipes

### Recipe 1 — Disable the Custom JS extension globally

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['custom-js'] );
    return $exts;
} );
```

After this filter runs at `plugins_loaded:100` (Lite's Bootstrap timing), the class is never instantiated, no control appears on documents, and `Asset_Builder::load_custom_js` short-circuits because `$this->custom_js_enable` reads false.

### Recipe 2 — Require a stricter capability than `administrator`

The current non-admin guard lives in [`Bootstrap.php:325`](../../includes/Classes/Bootstrap.php#L325). Replicate the pattern with a stricter capability:

```php
add_filter( 'elementor/document/save/data', function ( $data ) {
    if ( ! current_user_can( 'manage_options' ) && isset( $data['settings']['eael_custom_js'] ) ) {
        $data['settings']['eael_custom_js'] = get_post_meta( get_the_ID(), '_eael_custom_js', true );
    }
    return $data;
}, 11 ); // run after EA's own filter at priority 10
```

### Recipe 3 — Print the stored Custom JS on the published frontend

The shipped code only emits the inline `<script>` in edit / preview mode (see [Known Limitations](#known-limitations)). To run the JS on real visitors as well:

```php
add_action( 'wp_footer', function () {
    if ( is_admin() || ( function_exists( 'is_singular' ) && ! is_singular() ) ) {
        return;
    }

    $post_id   = get_the_ID();
    $custom_js = $post_id ? get_post_meta( $post_id, '_eael_custom_js', true ) : '';

    if ( $custom_js ) {
        // Stored value originates from administrators only — escape for context anyway.
        printf( '<script id="eael-inline-js-frontend">%s</script>', $custom_js );
    }
}, 110 );
```

Choose your priority carefully — running before `eael-general` JS means the user's code can't rely on jQuery / Elementor frontend being initialised.

## Common Issues

### The Custom JS panel doesn't appear in document settings

- **Likely cause:** `custom-js` is disabled in `eael_save_settings`. The extension never instantiated, so its constructor's `add_action` never fired.
- **Diagnose:** `var_dump( get_option( 'eael_save_settings' ) )` and confirm `custom-js` is `1` or present.
- **Fix:** Enable "Custom JS" in the EA settings page, or re-run the Setup Wizard.

### Code typed by a non-administrator silently disappears on save

- **Cause:** Working as designed. [`Bootstrap.php:325`](../../includes/Classes/Bootstrap.php#L325) replaces the submitted value with the previously stored post meta when the current user is not `administrator`.
- **Fix:** Either log in as an administrator, or follow [Recipe 2](#recipe-2--require-a-stricter-capability-than-administrator) (or its inverse) to relax the capability.

### Custom JS runs in the editor preview but not on the published page

- **Cause:** [`Asset_Builder::add_inline_js`](../../includes/Classes/Asset_Builder.php#L269) gates the output on `is_edit_mode() || is_preview_mode()`. There is no shipped path that emits the buffer on a regular published-page response.
- **Fix:** Use [Recipe 3](#recipe-3--print-the-stored-custom-js-on-the-published-frontend), or move the JS into a child-theme `functions.php` / dedicated `wp_enqueue_script` for production sites.

### JS works on first save then disappears

- **Cause:** Browser cache of the editor settings; or `Asset_Builder::delete_cache_data` ran during `after_delete_post` for a related entity, wiping `_eael_custom_js` ([`Asset_Builder.php:467`](../../includes/Classes/Asset_Builder.php#L467)).
- **Diagnose:** `get_post_meta( $post_id, '_eael_custom_js', true )` — empty means the meta was wiped.
- **Fix:** Re-paste the JS and re-save the document. If `delete_cache_data` is firing unexpectedly, audit any code that calls `wp_delete_post` on the document's ID.

## Debugging Guide

1. **Confirm the extension is active.** `error_log( print_r( get_option( 'eael_save_settings' ), true ) );` should include `custom-js => 1`.
2. **Confirm the constructor ran.** Add a temporary `error_log( 'Custom_JS constructed' );` at the top of `Custom_JS::__construct` and reload an admin page. If the line never appears, the instantiation chain is broken — re-check the registry filter.
3. **Confirm the control appears.** Open any Elementor page → Document Settings (gear icon, bottom-left) → Advanced tab → look for the "Custom JS" section. If missing, check that no other `elementor/documents/register_controls` callback raised an exception ahead of the priority-20 callback.
4. **Confirm save persistence.** After saving the page, run `wp post meta get <post_id> _eael_custom_js`. The output should match the typed JS.
5. **Confirm edit-mode rendering.** Open `?elementor-preview=<post_id>` in the browser, view source, search for `id="eael-inline-js"`. The script tag should contain your JS.
6. **For frontend testing**, follow [Recipe 3](#recipe-3--print-the-stored-custom-js-on-the-published-frontend) since the shipped code does not emit on the published frontend.
7. **For "JS not running":** open the editor / preview, open browser devtools, check the Console for syntax errors. The inline script is unescaped (`printf( '<script id="eael-inline-js">%s</script>', $this->custom_js )`) — a stray `</script>` in the user's code will break out of the tag and emit garbage.

## Architecture Decisions

### Single hook on `elementor/documents/register_controls`

- **Context:** Custom JS needs to be a page-level setting that lives in document settings, not a per-widget control. Elementor's document-controls registration hook is the canonical place to add to that panel.
- **Decision:** Hook `elementor/documents/register_controls` at priority 20.
- **Alternatives rejected:** Hook `elementor/element/common/_section_style/after_section_end` (puts the field on every widget — wrong scope); ship a separate admin page (loses the page-level association); hook at default priority 10 (would collide with other EA document-level extensions visually).
- **Consequences:** The Custom JS panel sits after Reading Progress, Table of Content, Scroll to Top etc. in the Advanced tab. Ordering is stable as long as no other extension hooks at priority > 20.

### Capability check outside the constructor

- **Context:** Showing the control to non-administrators is intentional (gives them visibility), but allowing them to overwrite an admin's code is not.
- **Decision:** Always show the field; gate writes via `elementor/document/save/data` in `Bootstrap`. Inside the extension, only render a warning `RAW_HTML` to clarify the read-only state.
- **Alternatives rejected:** Conditionally skip `add_control` for non-admins (hides the field; user can't see what an admin set); add the capability check inside `section_custom_js` and abort (same issue — hides the field).
- **Consequences:** Non-admins can type into the editor but the typed value is discarded on save. The UI doesn't surface that fact strongly — only the small inline warning. See [Known Limitations](#known-limitations).

### Mirror to a separate post meta key

- **Context:** Reading the entire `_elementor_page_settings` blob on every frontend request to extract one field is wasteful.
- **Decision:** On editor save, `Elements_Manager::eael_elements_cache()` copies `eael_custom_js` into a standalone `_eael_custom_js` post meta.
- **Alternatives rejected:** Read `_elementor_page_settings` on each request (wasteful); store in an option (no per-page association); store outside post meta (loses transactional save semantics).
- **Consequences:** Two sources of truth for the same string. If `_eael_custom_js` is wiped (e.g. `delete_cache_data`) but the page settings still contain the value, the next editor save resyncs them automatically. The reverse — editing `_eael_custom_js` directly while leaving page settings stale — would be overwritten on the next editor save.

## Known Limitations

- **No frontend output on published pages.** [`Asset_Builder::add_inline_js`](../../includes/Classes/Asset_Builder.php#L269) only emits the buffered Custom JS in `is_edit_mode() || is_preview_mode()`. A real visitor to the published page never receives the typed JS through the shipped pipeline. Workaround documented in [Recipe 3](#recipe-3--print-the-stored-custom-js-on-the-published-frontend).
- **Unescaped output.** The buffer is concatenated with the user's raw code and printed via `printf( '<script id="eael-inline-js">%s</script>', $this->custom_js )` — no `wp_kses` or similar sanitisation. The capability gate is the only defence; a malicious administrator could inject anything.
- **No `unfiltered_html` enforcement.** WordPress's default capability for arbitrary script storage is `unfiltered_html` (or `unfiltered_html` on multisite). The extension relies solely on `administrator` instead, which is a broader (single-site) but narrower (multisite) check than WP's own model.
- **Single global field per document.** No multi-snippet support, no priority ordering between snippets. A user wanting "header JS" and "footer JS" separately must concatenate them.
- **No syntax validation.** A typo (missing semicolon, unmatched brace) silently breaks the inline script tag. The trailing `;` appended in `load_custom_js` defends against missing terminators only, not against structural errors.
- **No version history.** Overwriting the field on save destroys the previous value. WordPress revisions don't capture `_eael_custom_js` because it's not in `_elementor_page_settings` directly — only `_eael_custom_js` is, and standalone meta keys don't participate in revisions.
- **Warning copy invisible to admins.** The "Only the Administrator can add/edit JavaScript code from here" warning only renders when `! current_user_can( 'administrator' )`. Admins editing a page have no equivalent reminder that what they type is unsanitised.

## Recent Significant Changes

No tracked changes yet. Future entries here only when:

- The capability gate changes (e.g. moves from `administrator` to `unfiltered_html`)
- Frontend rendering is added (closing the gap in [Known Limitations](#known-limitations))
- Sanitisation is applied to stored content
- The control's location in document settings moves to a different tab / priority

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — subsystem-level wiring, registration loop, hook map.
- **Architecture:** [`../architecture/asset-loading.md`](../architecture/asset-loading.md) — `Asset_Builder`'s inline JS buffer, edit / preview / frontend modes.
- **Architecture:** [`../architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — how `_elementor_page_settings` flows from editor save through to render.
- **Sibling extension docs:** [`./promotion.md`](promotion.md), [`./reading-progress.md`](reading-progress.md), [`./table-of-content.md`](table-of-content.md), [`./scroll-to-top.md`](scroll-to-top.md) — other extensions that hook `elementor/documents/register_controls` at the document level.
- **Public docs:** <https://essential-addons.com/elementor/docs/custom-js/>
