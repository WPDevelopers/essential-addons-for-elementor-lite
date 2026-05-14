# Reading Progress Extension

> Fixed reading-progress bar at the top or bottom of the viewport, growing 0% → 100% as the visitor scrolls a post or page. Configurable per-page or promoted to a site-wide global with display-condition gating (pages / posts / both).

**Class file:** [`includes/Extensions/Reading_Progress.php`](../../includes/Extensions/Reading_Progress.php) (218 lines)
**Slug:** `reading-progress` (`config.php` `extensions` key, line 1345)
**Public docs:** <https://essential-addons.com/docs/reading-progress-bar/>
**Pro-shared:** Lite-owned. Pro re-uses the same controls and rendering; no Pro-only options.

---

## Overview

Reading Progress adds a single panel to **Page Settings → Settings** in the Elementor editor. The user enables it per-page, picks top / bottom position, height, fill colour, animation speed — and a 999999-z-index fixed bar renders at `wp_footer` time on the matching frontend page. Width is driven from JS that computes `(scrollTop / (scrollHeight - clientHeight)) * 100` on every `window.scroll` event.

The extension supports two activation modes:

- **Per-page** — `eael_ext_reading_progress => yes` on a single document. The bar renders only when that page is loaded.
- **Global** — `eael_ext_reading_progress_global => yes` on one template page. EA writes that document's settings into `eael_global_settings['reading_progress']`, and the bar renders on every post / page / both (filtered by `eael_ext_reading_progress_global_display_condition`). Only one document can own the global config — saving a different page with the global toggle on overwrites the previous owner.

The class only registers controls. Frontend rendering lives in [`Elements::render_global_html()`](../../includes/Traits/Elements.php#L432) at `wp_footer` priority 10, alongside Scroll to Top, TOC, and Custom Cursor.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Reading_Progress.php`](../../includes/Extensions/Reading_Progress.php) | The class — constructor wires `elementor/documents/register_controls`; `register_controls()` is the only method, adding ~9 controls to Page Settings → Settings |
| [`includes/Traits/Elements.php` lines 471–524](../../includes/Traits/Elements.php#L471) | Frontend render path — builds the wrapper + nested `.eael-reading-progress-fill`, applies global display-condition filter (`pages` / `posts` / `all`), enqueues handles, appends to `wp_footer` output |
| [`includes/Traits/Elements.php::progress_bar_local_css()`](../../includes/Traits/Elements.php#L928) | Inline-CSS for per-page mode — adds the fill colour scoped by `#eael-reading-progress-{post_id}` |
| [`includes/Traits/Core.php` lines 185–203](../../includes/Traits/Core.php#L185) | On Elementor save, copies the document's reading-progress controls into `eael_global_settings['reading_progress']` if global mode is enabled |
| [`includes/Classes/Asset_Builder.php` lines 342–355](../../includes/Classes/Asset_Builder.php#L342) | Registers `eael-reading-progress` script + style handles; both are enqueued lazily inside `render_global_html` |
| [`src/css/view/reading-progress.scss`](../../src/css/view/reading-progress.scss) | Frontend stylesheet — fixed-position bar with `z-index: 999999`, top/bottom variant via `.eael-reading-progress-bottom`, conditional visibility based on `.eael-reading-progress-wrap-{local,global,disabled}` parent class |
| [`src/js/view/reading-progress.js`](../../src/js/view/reading-progress.js) | Frontend jQuery — on `window.scroll`, computes scroll percentage and writes it as `width` onto `.eael-reading-progress-fill` |
| [`src/js/edit/reading-progress.js`](../../src/js/edit/reading-progress.js) | Editor-only JS — toggling the switch adds `.eael-reading-progress-wrap-disabled` and triggers `elementor.saver.update().reloadPreview()`; position changes swap `.eael-reading-progress-{top,bottom}`; fill-color changes update the bar inline |
| `config.php` line 1345 | Registry entry — declares only the **edit-context** JS asset. The frontend CSS and JS handles are registered manually in `Asset_Builder::load_commnon_asset()`, not via the registry's `dependency` block (the view-context entries are commented out) |

The commented-out `dependency.css` and `dependency.js[view]` entries in `config.php` are deliberate — those handles are pre-registered in `Asset_Builder::load_commnon_asset()` and lazily enqueued inside `render_global_html`. This pattern avoids loading the bar's CSS/JS on every page; only pages that actually render the bar pay the cost.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Per-page enable + all per-page controls | YES | YES |
| Global mode | YES | YES |
| Display-condition select (`posts` / `pages` / `all`) | YES | YES |
| Position (top / bottom) | YES | YES |
| Height slider | YES | YES |
| Background colour | YES | YES |
| Fill colour | YES | YES |
| Animation speed slider | YES | YES |
| Gradient fill | NO | NO |

The class is Lite-owned; Pro doesn't override it. The bar is single-color, not gradient — that would require either a second color picker + computed background-image, or a custom Repeater control.

## Architecture

- **Single Elementor hook.** Constructor wires `elementor/documents/register_controls` at priority 10. No widget-level participation. Same architecture as Scroll to Top.
- **Template-aware short-circuit.** [`Helper::prevent_extension_loading()`](../../includes/Classes/Helper.php#L110) returns true for Theme Builder template types (header, footer, single, post, page, search-results, error-404, section), preventing controls from being added to those documents. The bar would have no meaningful "scroll target" in those contexts.
- **Render lives outside the class.** The class never emits HTML. `Elements::render_global_html` builds the markup centrally for all four global extensions (Reading Progress, TOC, Scroll to Top, Custom Cursor) on the same `wp_footer` action.
- **Wrapper class drives mode.** The frontend wrapper carries one of three classes — `.eael-reading-progress-wrap-local`, `.eael-reading-progress-wrap-global`, or `.eael-reading-progress-wrap-disabled` — and the SCSS shows the matching inner `.eael-reading-progress-local` / `.eael-reading-progress-global` and hides the others. This means the same DOM can carry both inner bars; only one is visible at a time. The `disabled` class is used by edit-mode JS to hide the bar briefly while the editor reloads.
- **JS recomputes on every scroll event.** No debounce, no `requestAnimationFrame` — a `width` update fires per scroll tick. The fill's CSS `transition: width <speed>ms ease` smooths the visual.
- **`eael-reading-progress-{post_id}` id selector for per-page color override.** When per-page mode is on, [`progress_bar_local_css`](../../includes/Traits/Elements.php#L928) emits an inline `<style>` scoped by the post-id-suffixed id. This keeps per-page color isolated from other pages' rendered bars in cached CSS scenarios.

## Render Behavior

### DOM

The bar is emitted just before `</body>` via `wp_footer`. Per-page mode (the local toggle is `yes`):

```html
<div id="eael-reading-progress-123" class="eael-reading-progress-wrap eael-reading-progress-wrap-local">
    <div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-top">
        <div class="eael-reading-progress-fill"></div>
    </div>
</div>
```

Global mode (the visiting page didn't enable the bar locally, but a global template owns it):

```html
<div id="eael-reading-progress-456" class="eael-reading-progress-wrap eael-reading-progress-wrap-global">
    <div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-top"
         style="height: 5px;background-color: #eeeeee;">
        <div class="eael-reading-progress-fill"
             style="height: 5px;background-color: #1fd18e;transition: width 50ms ease;"></div>
    </div>
</div>
```

Note the inline styles only appear on the global-mode markup — that's because the global rendering path inlines the global settings directly. Per-page mode relies on Elementor's per-page CSS file emitted via the controls' `selectors` mechanism.

### CSS

Bundled CSS ([`src/css/view/reading-progress.scss`](../../src/css/view/reading-progress.scss)):

```scss
.eael-reading-progress-wrap {
    &.eael-reading-progress-wrap-local .eael-reading-progress-global { display: none; }
    &.eael-reading-progress-wrap-global .eael-reading-progress-local { display: none; }
    &.eael-reading-progress-wrap-disabled {
        .eael-reading-progress-global, .eael-reading-progress-local { display: none; }
    }

    .eael-reading-progress {
        width: 100%;
        position: fixed;
        top: 0; left: 0;
        height: 5px;
        z-index: 999999;

        &.eael-reading-progress-bottom { top: unset; bottom: 0; }

        .eael-reading-progress-fill {
            height: 5px;
            background-color: #1fd18e;
            width: 0%;
            transition: width 50ms ease;
        }
    }
}
```

The `z-index: 999999` is intentional — the bar should sit above every theme element including sticky headers / modals.

### JS

[`src/js/view/reading-progress.js`](../../src/js/view/reading-progress.js) is intentionally tiny:

```js
jQuery(document).ready(function () {
    jQuery(window).scroll(function () {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        if (height === 0) { height = document.documentElement.scrollHeight; }
        var scrolled = (winScroll / height) * 100;
        jQuery(".eael-reading-progress-fill").css({ width: scrolled + "%" });
    });
});
```

The `height === 0` fallback handles pages where `clientHeight` equals `scrollHeight` (no scrollable content) — without it, the resulting `Infinity` or `NaN` would break the bar. The selector `.eael-reading-progress-fill` matches both the local and global inner fill — only one is visible at a time per the SCSS rules above, so writing to both is harmless.

The edit-context JS ([`src/js/edit/reading-progress.js`](../../src/js/edit/reading-progress.js)) wires three change-callbacks on Page Settings: the enable switch (add `.eael-reading-progress-wrap-disabled`, save, reload preview); position select (swap `.eael-reading-progress-top` / `-bottom` class); fill color (write inline `background-color` onto the per-post-id `.eael-reading-progress .eael-reading-progress-fill`). Height and animation-speed updates flow through Elementor's `selectors` mechanism without needing this JS.

## Asset Dependencies

| Asset | When loaded | Source |
| ----- | ----------- | ------ |
| `eael-reading-progress` (CSS handle) | Frontend, lazy via `wp_enqueue_style` inside `render_global_html` only when the bar will render | [`assets/front-end/css/view/reading-progress.min.css`](../../assets/front-end/css/view/reading-progress.min.css) |
| `eael-reading-progress` (JS handle) | Frontend, lazy via `wp_enqueue_script` inside `render_global_html` only when the bar will render | [`assets/front-end/js/view/reading-progress.min.js`](../../assets/front-end/js/view/reading-progress.min.js); depends on `jquery` |
| Editor-context JS | Inside Elementor editor iframe — declared in `config.php` `dependency.js[0]` with `'context' => 'edit'`, enqueued by `Asset_Builder` | [`assets/front-end/js/edit/reading-progress.min.js`](../../assets/front-end/js/edit/reading-progress.min.js) |

The frontend CSS / JS handles are registered manually in [`Asset_Builder::load_commnon_asset()`](../../includes/Classes/Asset_Builder.php#L342) — not via the `dependency` block. The block's view-context entries are commented out. This is the deliberate split: edit-context JS via the registry (because Asset_Builder's standard pipeline handles editor enqueueing); frontend assets via manual register + lazy enqueue (because `render_global_html` is the single decision point for whether the bar renders at all).

## Hook Timing

### Elementor hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `elementor/documents/register_controls` | 10 | `register_controls` | Add the Reading Progress Bar panel under Page Settings → Settings |

### WordPress hooks consumed (indirectly via `Elements` trait)

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `wp_footer` | 10 | `Bootstrap::render_global_html` | Emits the wrapper HTML if local or global mode says the bar should render |
| `wp_enqueue_scripts` | 100 | `Asset_Builder::frontend_asset_load` | Registers the `eael-reading-progress` handles ready for lazy enqueue |

### Hooks emitted

| Hook | Type | Fired in | Purpose |
| ---- | ---- | -------- | ------- |
| `eael/extentions/global_settings` | filter | [`Core::save_global_values:297`](../../includes/Traits/Core.php#L297) | Mutate the full `eael_global_settings` array (including the `reading_progress` key) before persistence |

The extension exposes no `eael/reading_progress/*` actions or filters.

## Configuration & Extension Points

### Per-page controls (Page Settings → Settings → "Reading Progress Bar")

| Control id | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_reading_progress` | SWITCHER | `no` | Enable per-page |
| `eael_ext_reading_progress_has_global` | HIDDEN | computed | Mirrors whether the global config is currently set |
| `eael_ext_reading_progress_global` | SWITCHER | `no` | Promote this page's settings to the site-wide global config |
| `eael_ext_reading_progress_global_display_condition` | SELECT | `all` | `posts` / `pages` / `all` — applies only in global mode |
| `eael_ext_reading_progress_position` | SELECT | `top` | `top` / `bottom` viewport edge |
| `eael_ext_reading_progress_height` | SLIDER | 5 px | Bar thickness; also `!important` to override theme styles |
| `eael_ext_reading_progress_bg_color` | COLOR | (none) | Background colour of the unfilled bar |
| `eael_ext_reading_progress_fill_color` | COLOR | `#1fd18e` | Fill colour |
| `eael_ext_reading_progress_animation_speed` | SLIDER | 50 ms | CSS `transition` duration on the fill's `width` |

All controls are conditioned on `eael_ext_reading_progress => yes`.

### Global settings option shape

```php
$global_settings['reading_progress'] = [
    'post_id' => 123,
    'enabled' => true,
    'eael_ext_reading_progress_global_display_condition' => 'all',
    'eael_ext_reading_progress_position' => 'top',
    'eael_ext_reading_progress_height' => [ 'unit' => 'px', 'size' => 5 ],
    'eael_ext_reading_progress_bg_color' => '',
    'eael_ext_reading_progress_fill_color' => '#1fd18e',
    'eael_ext_reading_progress_animation_speed' => [ 'unit' => 'px', 'size' => 50 ],
];
```

Written by [`Core::save_global_values()`](../../includes/Traits/Core.php#L185) whenever a document saves with both `eael_ext_reading_progress => yes` and `eael_ext_reading_progress_global => yes`. The `enabled` flag is reset to false (and `post_id` to null) when the owning document later disables global mode.

> **Architecture-doc note:** the architecture overview ([`../architecture/extensions.md`](../architecture/extensions.md)) references `eael_ext_reading_progress_global_display_condition` as the controlling key. That's the per-document control name; in the `eael_global_settings` array it is stored under that same key (preserved from the document's setting), inside the `reading_progress` sub-array.

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/extentions/global_settings` | [`Core::save_global_values:297`](../../includes/Traits/Core.php#L297) | Mutate the full `eael_global_settings` array before persistence |
| `eael/registered_extensions` | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Remove `reading-progress` from the registry to disable the extension entirely |

### Activation

Standard EA extension activation. Slug `reading-progress` must be in `eael_save_settings` (default-enabled on fresh installs).

## Customization Recipes

### Recipe 1 — Hide the bar on mobile

```css
@media (max-width: 767px) {
    .eael-reading-progress-wrap { display: none !important; }
}
```

No built-in hide-on-device control.

### Recipe 2 — Force-disable the global mode site-wide

```php
add_filter( 'eael/extentions/global_settings', function ( $settings ) {
    $settings['reading_progress'] = [ 'post_id' => null, 'enabled' => false ];
    return $settings;
} );
```

### Recipe 3 — Use `requestAnimationFrame` instead of every scroll event (perf)

Replace the JS source. Not exposed via a filter, so requires source edit + `npm run build`:

```js
jQuery(document).ready(function () {
    let ticking = false;
    jQuery(window).scroll(function () {
        if (!ticking) {
            window.requestAnimationFrame(function () {
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                if (height === 0) { height = document.documentElement.scrollHeight; }
                jQuery(".eael-reading-progress-fill").css({ width: (winScroll / height) * 100 + "%" });
                ticking = false;
            });
            ticking = true;
        }
    });
});
```

### Recipe 4 — Suppress the extension entirely

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['reading-progress'] );
    return $exts;
} );
```

## Common Issues

### Bar enabled but doesn't render

- **Likely cause 1:** Current document is a Theme Builder template. `Helper::prevent_extension_loading()` blocks controls from being registered there.
- **Likely cause 2:** Global mode display-condition mismatch. Global config's `display_condition` is `pages` but you're on a single post — `Elements.php:510` resets the HTML to empty.
- **Likely cause 3:** The owning global post status isn't `publish` (line 508).

### Bar appears but never fills

- **Likely cause 1:** Page has no scrollable content (`scrollHeight - clientHeight === 0`). The JS falls back to `scrollHeight`, but if `scrollTop` is also 0, the fill stays at 0%.
- **Likely cause 2:** JS didn't load. Confirm `wp_script_is('eael-reading-progress', 'enqueued')` returns true. If false, walk `render_global_html` to find where the early return happens.
- **Likely cause 3:** Theme uses `body` overflow + an internal scroller div. The JS reads `document.documentElement` scroll only, missing the actual scroller. No fix in current code; theme integration needed.

### Bar disappears on Theme Builder archive templates

- **Cause:** `prevent_extension_loading` blocks template documents. The archive template doc never had the controls registered, so settings can't exist.
- **Fix:** Enable global mode from a regular page; the global render path uses `is_archive()` / `is_home()` checks in `render_global_html`'s outer scope (line 437), so the global bar still renders on archive views.

### Per-page colour overrides global colour even when local toggle is off

- **Likely cause:** `progress_bar_local_css` is unconditionally called when `reading_progress_status` is true, even in global mode (line 484-486 of Elements.php). The inline style is `#eael-reading-progress-{post_id}`-scoped, so it shouldn't collide — but in cached HTML scenarios you may see stale local CSS.
- **Diagnose:** View page source; search for `<style>` blocks scoped by the wrapper's id.

### Editor preview shows old position after switching top → bottom

- **Cause:** The edit-mode JS swaps `.eael-reading-progress-top` / `-bottom` classes immediately, but the bar markup may have been built before. If the bar isn't visible after toggling, save and reload the preview.

## Debugging Guide

1. **Confirm activation.** `get_option('eael_save_settings')['reading-progress']` should be `1`.
2. **Confirm constructor ran.** `error_log('Reading_Progress ctor')` at line 14; open any document in Elementor.
3. **Confirm panel appears.** Open Page Settings → Settings — the "Reading Progress Bar" panel should be present (unless on a Theme Builder template).
4. **Confirm settings persist.** After save, inspect `_elementor_page_settings` post meta — the `eael_ext_reading_progress_*` keys should be there.
5. **Confirm frontend wrapper renders.** View page source — `<div id="eael-reading-progress-...">` should appear before `</body>` if either local or global mode is active and conditions match.
6. **Confirm assets enqueue.** Inspect the page; `eael-reading-progress.min.css` and `eael-reading-progress.min.js` should be in the network panel.
7. **Confirm JS runs.** Browser console → scroll the page → `.eael-reading-progress-fill` should grow. If it doesn't, check console for jQuery / script errors.
8. **For global mode debugging.** `print_r(get_option('eael_global_settings')['reading_progress'])` should show the `post_id` of the owning document and the config snapshot.

## Architecture Decisions

### Frontend CSS / JS via manual register + lazy enqueue, not `dependency` block

- **Context:** `Asset_Builder` automatically enqueues `'context' => 'view'` assets whenever the extension is in `$registered_extensions`. That would load the bar's CSS / JS on every page even when the bar doesn't render.
- **Decision:** Register the view handles in `Asset_Builder::load_commnon_asset` instead, then call `wp_enqueue_script` / `wp_enqueue_style` only inside `render_global_html` when the bar is going to render. The `dependency` block keeps only the edit-context JS so editor preview support still works through the standard pipeline.
- **Alternatives rejected:** Always-on view assets (waste bytes); JIT-register in `render_global_html` (more code).
- **Consequences:** The view-context entries in `config.php` are intentionally commented out — they're not dead code, they're documentation of the alternative. Removing them would lose that context for future maintainers.

### Single Elementor hook (document-level only)

- **Context:** A reading progress bar is a page-wide overlay, not a per-widget decorator.
- **Decision:** Hook only `elementor/documents/register_controls`.
- **Alternatives rejected:** Hook each element type's `_section_style` (controls would appear on every widget — confusing).
- **Consequences:** Theme Builder templates need the `prevent_extension_loading` short-circuit. Configuration lives in Page Settings, never on individual widgets.

### Global vs local rendered as a single wrapper with two inner bars

- **Context:** Could render only one inner bar (the active mode's), or render both with CSS hiding the inactive.
- **Decision:** Render both inner `.eael-reading-progress-local` and `.eael-reading-progress-global` — actually, the PHP path renders only one of the two based on `$global_reading_progress`. The SCSS still defines hide rules for the other so the markup is robust even if PHP shape changes.
- **Alternatives rejected:** Two completely separate render paths (harder to keep in sync); CSS-only mode switching (would need both bars in DOM, doubling cost).
- **Consequences:** SCSS is slightly larger because of the `&.eael-reading-progress-wrap-{local,global,disabled}` rules; but the design is forgiving.

### Inline `<style>` for global mode, per-page `selectors` for local mode

- **Context:** Global mode reads from `eael_global_settings`, not from any document's per-page CSS. So Elementor's per-page CSS cache won't pick it up.
- **Decision:** Inline the global mode styles directly in the wrapper / fill `style=""` attributes during PHP render. Local mode uses Elementor's standard `selectors` mechanism so the styles end up in the page's cached CSS.
- **Alternatives rejected:** `wp_add_inline_style` for global (works but harder to target with `!important`); always inline (loses Elementor caching).
- **Consequences:** Two styling paths exist. The `progress_bar_local_css` helper handles the local color override specifically scoped by post id; the inline `style` handles global config.

### Edit-mode JS triggers reload-preview for major changes, in-place for color

- **Context:** Position swap (top→bottom) is a class change Elementor's selectors don't handle automatically. Color change can be applied in-place. Enable / disable changes change DOM shape entirely.
- **Decision:** Three change-callbacks with different strategies — disable triggers `reloadPreview()`; position swaps classes inline; color writes inline `background-color` directly.
- **Alternatives rejected:** Always reload preview (slow UX); always in-place (won't capture DOM-shape changes).
- **Consequences:** Editor JS is slightly larger but UX is much faster for the common case (color tweaking).

## Known Limitations

- **No gradient fill.** Single color only.
- **No `requestAnimationFrame` throttling.** Every scroll event triggers a jQuery `.css()` write. Negligible on modern devices but visible in profilers.
- **`document.documentElement` is the assumed scroll context.** Themes that scroll a wrapper div instead of the document break the calculation.
- **`z-index: 999999`.** May collide with rare modals that use higher z-indexes; tweak via custom CSS.
- **No hide-on-device toggle.** Recipe 1 covers it.
- **Single global config.** Only one document can own global mode at a time; saving another with global on overwrites the previous owner.
- **Theme Builder templates silently disabled.** Controls don't appear on header / footer / single / archive editors. Configure globally from a regular page instead.
- **The `height === 0` fallback** assigns `scrollHeight` as the denominator, but if the page is literally non-scrollable the fill width is always 0 — accurate, but visually identical to "broken JS".
- **No progress tracking based on article container (vs document).** Some implementations track scroll relative to a specific article wrapper for more meaningful progress in long pages with footers. Lite uses document-level.

## Recent Significant Changes

No micro-changelog entries yet. Add entries when:

- The view assets stop being loaded manually and shift to a non-commented `dependency` block
- A gradient fill control lands
- A scroll target other than `document.documentElement` becomes supported
- A `requestAnimationFrame` throttle replaces every-scroll writes

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — extension subsystem overview, registry + activation flow
- **Architecture:** [`../architecture/asset-loading.md`](../architecture/asset-loading.md) — how the manual-register + lazy-enqueue pattern fits the asset pipeline
- **Architecture:** [`../architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — Page Settings persistence into `_elementor_page_settings` post meta
- **Sibling extension:** [`scroll-to-top.md`](scroll-to-top.md) — same global-template pattern, same `render_global_html` footer-render path
- **Sibling extension:** [`promotion.md`](promotion.md) — canonical extension-doc example
- **Public docs:** <https://essential-addons.com/docs/reading-progress-bar/>
- **Rules:** [`../../.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md), [`../../.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
