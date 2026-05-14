# Scroll to Top Extension

> Floating "back to top" button at the bottom-left or bottom-right of any post or page — configurable per-page or promoted to a site-wide global. Pure document-level extension: no widget, no canvas footprint, just one panel under Elementor's page settings.

**Class file:** [`includes/Extensions/Scroll_to_Top.php`](../../includes/Extensions/Scroll_to_Top.php) (459 lines)
**Slug:** `scroll-to-top` (`config.php` `extensions` key, line 1391)
**Public docs:** <https://essential-addons.com/docs/scroll-to-top/>
**Pro-shared:** Lite-owned. Pro inherits the same controls and rendering — no Pro-only options on this extension.

---

## Overview

Scroll to Top adds a single controls section to Elementor's **Page Settings → Settings** tab. The user enables the button per-page, picks position (bottom-left / bottom-right), icon, size, colour, opacity, border-radius, z-index — and Elementor renders a `<div class="eael-ext-scroll-to-top-wrap">` at `wp_footer` time on the matching frontend page. A small jQuery script fades the button in once the visitor scrolls past 100px and animates a `scrollTop` to 0 on click.

The extension supports two activation modes that coexist on the same page:

- **Per-page** — `eael_ext_scroll_to_top => yes` on a single document. The button renders only when that specific page is loaded.
- **Global** — `eael_ext_scroll_to_top_global => yes` on a single "template" page. EA writes that document's settings into the `eael_global_settings` option under the `eael_ext_scroll_to_top` key, and the button renders on every post / page / both (configurable via `eael_ext_scroll_to_top_global_display_condition`). Editing any document that has its global toggle on **overwrites** the previous global config — only one global config exists across the site at a time.

The class itself only registers controls. The footer-render path lives in [`Elements::render_global_html()`](../../includes/Traits/Elements.php#L432), and the per-post-id global config is built by [`Core::save_global_values()`](../../includes/Traits/Core.php#L295) on each Elementor save.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Scroll_to_Top.php`](../../includes/Extensions/Scroll_to_Top.php) | The class — constructor wires one Elementor hook; `register_controls()` is the only method, contributing ~15 controls to Page Settings → Settings tab |
| [`includes/Traits/Elements.php` lines 653–701](../../includes/Traits/Elements.php#L653) | Frontend render path — assembles the wrapper + icon HTML, applies global display-condition filter (`pages` / `posts` / `all`), enqueues handles, appends to the `wp_footer` output buffer |
| [`includes/Traits/Elements.php::scroll_to_top_global_css()`](../../includes/Traits/Elements.php#L946) | Inline-CSS generator for global mode — bakes position / size / opacity / icon colour / etc. from `eael_global_settings` into `<style>` injected on `eael-scroll-to-top` handle |
| [`includes/Traits/Core::get_ext_scroll_to_top_global_settings()`](../../includes/Traits/Core.php#L358) | On Elementor save, copies the document's per-control values into `eael_global_settings['eael_ext_scroll_to_top']` if global mode is enabled |
| [`includes/Classes/Asset_Builder.php` lines 372–385](../../includes/Classes/Asset_Builder.php#L372) | Registers `eael-scroll-to-top` script + style handles (frontend bundle); enqueued lazily by `render_global_html` |
| [`src/css/view/scroll-to-top.scss`](../../src/css/view/scroll-to-top.scss) | Minimal frontend stylesheet — `.eael-ext-scroll-to-top-button { position: fixed; cursor: pointer; display: flex; align-items: center; justify-content: center }` and the `.scroll-to-top-hide { display: none }` initial state |
| [`src/js/view/scroll-to-top.js`](../../src/js/view/scroll-to-top.js) | Frontend jQuery — listens on `window.scroll` and the `elementorFrontend.elements.$body[0]` scroll event, fades the button in/out at 100px threshold, animates `scrollTop: 0` on click |
| [`src/js/edit/scroll-to-top.js`](../../src/js/edit/scroll-to-top.js) | Editor-only JS — when `eael_ext_scroll_to_top` or the icon is changed in Page Settings, calls `elementor.saver.update.apply()` then `elementor.reloadPreview()` so the change is visible immediately |
| `config.php` line 1391 | Registry entry — declares the edit-context JS asset only (the frontend CSS / JS handles are registered manually in `Asset_Builder::load_commnon_asset`) |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Per-page enable + all per-page controls | YES | YES (same code path) |
| Global mode | YES | YES |
| Display-condition select (`posts` / `pages` / `all`) | YES | YES |
| Position (bottom-left / bottom-right) | YES | YES |
| Icon picker (Elementor `ICONS` control) | YES | YES |
| Hide on mobile / desktop | NO | NO |
| Animation variants (slide / fade / pop) | NO | NO |
| Threshold customisation | NO | NO |

The class is Lite-owned and Pro re-uses it — there is no Pro-only override at present. The hide-on-mobile, threshold, and animation-variant fields listed in some marketing pages do **not** exist in the current Lite or Pro extension class. The threshold is hard-coded to `100` px in `src/js/view/scroll-to-top.js`.

## Architecture

- **Single Elementor hook.** Constructor wires exactly `elementor/documents/register_controls` at priority 10. No widget-level hooks, no `frontend/before_render` participation. The class operates purely at the document (page settings) layer.
- **Template-aware short-circuit.** [`Helper::prevent_extension_loading()`](../../includes/Classes/Helper.php#L110) is called first thing inside `register_controls()`. If the current document type is `header`, `footer`, `single`, `post`, `page`, `search-results`, `error-404`, or `section` (Theme Builder templates), the method returns early — Scroll to Top controls are not added to those template editors because the rendered template wouldn't have a meaningful "page" to scroll on.
- **No per-element controls.** Unlike Hover Effect or Wrapper Link, Scroll to Top doesn't decorate widgets or sections. The button is one global UI overlay per page, configured once in Page Settings.
- **Global mode is a single shared config.** Only one document at a time can be "the global Scroll to Top template". When User A enables Global on Page X and User B later enables Global on Page Y, Page Y's controls become the global config, and Page X loses its global role (Page X's `eael_ext_scroll_to_top_has_global` hidden field stays true, but the `post_id` stored in `eael_global_settings` now points to Page Y).
- **Render lives outside the class.** The class never emits HTML. Frontend output is concentrated in `Elements::render_global_html` (one method that also handles Reading Progress, TOC, and Custom Cursor on the same `wp_footer` action). This keeps the extension class shape uniform (constructor + `register_controls`) and centralises the footer-render orchestration.
- **Edit-mode JS is reload-based, not live-update.** Because the rendered HTML lives in `wp_footer` (outside Elementor's preview iframe), changes to the enable toggle or the icon trigger `elementor.reloadPreview()` rather than a DOM-patch. Other style controls use Elementor's `selectors` mechanism and update in-place via CSS without a reload.

## Render Behavior

### DOM

When per-page mode is active for the current document, or when global mode is active and the global display-condition matches the current view, the following markup is appended to `wp_footer`:

```html
<div class="eael-ext-scroll-to-top-wrap scroll-to-top-hide">
    <span class="eael-ext-scroll-to-top-button">
        <i class="fas fa-chevron-up"></i>
    </span>
</div>
```

The icon `<i>` may be an SVG element instead (`<svg>…</svg>`) when the user picks an SVG icon library — Helper::get_render_icon resolves either path.

### CSS

The frontend bundle ([`src/css/view/scroll-to-top.scss`](../../src/css/view/scroll-to-top.scss)) is intentionally tiny — just position + display, no visual styles:

```scss
.eael-ext-scroll-to-top-wrap.scroll-to-top-hide {
    display: none;
}

.eael-ext-scroll-to-top-button {
    position: fixed;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
```

All visual properties (background, size, position offsets, opacity, border-radius, icon colour, icon size) come from one of two paths:

- **Per-page mode** — Elementor's `selectors` mechanism on each control emits CSS to the page's local stylesheet (`.elementor-{post_id}.css` cached on disk by Elementor). Selectors reference `.eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button` and its descendants.
- **Global mode** — [`scroll_to_top_global_css()`](../../includes/Traits/Elements.php#L946) builds a string of identical declarations from `eael_global_settings['eael_ext_scroll_to_top']` and pushes them with `wp_add_inline_style('eael-scroll-to-top', $css)`. This means the same selector path works for both modes — only the source of the values changes.

### JS

[`src/js/view/scroll-to-top.js`](../../src/js/view/scroll-to-top.js) runs on jQuery document-ready:

```js
let offset = 100;
let speed = 300;
let duration = 300;

if ($(this).scrollTop() > offset) {
    $('.eael-ext-scroll-to-top-wrap').removeClass('scroll-to-top-hide');
}

if (typeof elementorFrontend !== 'undefined' && elementorFrontend) {
    elementorFrontend.elements.$body[0].addEventListener('scroll', function () {
        eaelScrollToTop(this);
    });
}

$(window).scroll(function () { eaelScrollToTop(this); });

function eaelScrollToTop($currentObj) {
    if ($($currentObj).scrollTop() < offset) {
        $('.eael-ext-scroll-to-top-wrap').fadeOut(duration);
    } else {
        $('.eael-ext-scroll-to-top-wrap').fadeIn(duration);
    }
}

$('.eael-ext-scroll-to-top-wrap').on('click', function () {
    $('html, body').animate({ scrollTop: 0 }, speed);
    return false;
});
```

Two scroll listeners are attached because some themes scroll the `body` element instead of `window` (Elementor sticks `elementorFrontend.elements.$body` on a custom scroll container in certain layouts). Both call the same `eaelScrollToTop` helper, fading the button in/out at the hard-coded 100 px threshold. Click handler animates `scrollTop: 0` over 300 ms.

The editor-context JS ([`src/js/edit/scroll-to-top.js`](../../src/js/edit/scroll-to-top.js)) is much smaller — just two `addChangeCallback` registrations that trigger a save-then-reload-preview when the enable toggle or icon is changed in Page Settings. Other controls update live via Elementor's selectors machinery and don't need a reload.

## Asset Dependencies

| Asset | When loaded | Source |
| ----- | ----------- | ------ |
| `eael-scroll-to-top` (CSS handle) | Frontend, lazy via `wp_enqueue_style` inside `render_global_html` only if the button is going to render | [`assets/front-end/css/view/scroll-to-top.min.css`](../../assets/front-end/css/view/scroll-to-top.min.css) compiled from [`src/css/view/scroll-to-top.scss`](../../src/css/view/scroll-to-top.scss) |
| `eael-scroll-to-top` (JS handle) | Frontend, lazy via `wp_enqueue_script` inside `render_global_html` only if the button is going to render | [`assets/front-end/js/view/scroll-to-top.min.js`](../../assets/front-end/js/view/scroll-to-top.min.js) compiled from [`src/js/view/scroll-to-top.js`](../../src/js/view/scroll-to-top.js); depends on `jquery` |
| Editor-context JS | Elementor editor iframe, declared in `config.php` `extensions.scroll-to-top.dependency.js[0]` with `'context' => 'edit'` | [`assets/front-end/js/edit/scroll-to-top.min.js`](../../assets/front-end/js/edit/scroll-to-top.min.js) compiled from [`src/js/edit/scroll-to-top.js`](../../src/js/edit/scroll-to-top.js) — `Asset_Builder` enqueues this only inside the editor |
| Font Awesome 5 / FA4 shim | When the user picks a Font Awesome icon, Elementor's own icon-rendering pipeline handles enqueueing | Provided by Elementor (handle: `font-awesome-5-all`) |

The frontend CSS and JS handles are not declared in the `config.php` `dependency` block — they are registered manually inside [`Asset_Builder::load_commnon_asset()`](../../includes/Classes/Asset_Builder.php#L372) and enqueued lazily only when `render_global_html` decides the button will actually appear. This avoids paying the bytes on pages where Scroll to Top isn't active.

## Hook Timing

### Elementor hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `elementor/documents/register_controls` | 10 | `register_controls` | Add the Scroll to Top panel under Page Settings → Settings tab |

### WordPress hooks consumed (indirectly via `Elements` trait)

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `wp_footer` | 10 | `Bootstrap::render_global_html` | Emits the wrapper HTML on the frontend if the active document or global mode says so |
| `wp_enqueue_scripts` | 100 | `Asset_Builder::frontend_asset_load` | Registers the `eael-scroll-to-top` handles ready for lazy enqueue |

### Hooks emitted

| Hook | Type | Fired in | Purpose |
| ---- | ---- | -------- | ------- |
| `eael/extentions/global_settings` | filter | [`Core::save_global_values:297`](../../includes/Traits/Core.php#L297) | Third-party filter to mutate the entire `eael_global_settings` array before persistence — includes scroll-to-top key |

The extension does not emit any of its own `eael/scroll_to_top/*` actions or filters.

## Configuration & Extension Points

### Per-page controls (Page Settings → Settings → "Scroll to Top")

| Control id | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_ext_scroll_to_top` | SWITCHER | `no` | Enable per-page |
| `eael_ext_scroll_to_top_has_global` | HIDDEN | computed | Stores whether any document currently owns the global config |
| `eael_ext_scroll_to_top_global` | SWITCHER | `no` | Promote this page's settings to be the site-wide global config |
| `eael_ext_scroll_to_top_global_display_condition` | SELECT | `all` | `posts` (single posts only) / `pages` (pages only) / `all` (both) — applies only to global mode |
| `eael_ext_scroll_to_top_position_text` | SELECT | `bottom-right` | `bottom-left` / `bottom-right` — controls which side anchor is used (no top variant) |
| `eael_ext_scroll_to_top_position_bottom` | SLIDER | 15 px | Distance from bottom |
| `eael_ext_scroll_to_top_position_left` | SLIDER | 15 px | Distance from left (visible only when `bottom-left`) |
| `eael_ext_scroll_to_top_position_right` | SLIDER | 15 px | Distance from right (visible only when `bottom-right`) |
| `eael_ext_scroll_to_top_button_width` | SLIDER | 50 px | Button width |
| `eael_ext_scroll_to_top_button_height` | SLIDER | 50 px | Button height |
| `eael_ext_scroll_to_top_z_index` | SLIDER | 9999 | Stacking order |
| `eael_ext_scroll_to_top_button_opacity` | SLIDER | 0.7 | Opacity |
| `eael_ext_scroll_to_top_button_icon_image` | ICONS | `fas fa-chevron-up` | Icon (Elementor `ICONS` control — supports Font Awesome library + SVG upload) |
| `eael_ext_scroll_to_top_button_icon_note` | RAW_HTML | — | Helper note recommending SVG when FA isn't available on the page |
| `eael_ext_scroll_to_top_button_icon_size` | SLIDER | 16 px | Icon size (applied as `font-size` for `<i>` and `width`/`height` for `<svg>`) |
| `eael_ext_scroll_to_top_button_icon_color` | COLOR | `#ffffff` | Icon colour (`color` for `<i>`, `fill` for `<svg>`) |
| `eael_ext_scroll_to_top_button_bg_color` | COLOR | `#000000` | Button background |
| `eael_ext_scroll_to_top_button_border_radius` | SLIDER | 5 px | Border radius |

Controls beyond the enable switch are all gated by the `eael_ext_scroll_to_top => yes` condition — when the toggle is off, the panel collapses to just the switch.

### Global settings option shape

`get_option('eael_global_settings')` carries the global mode config under `['eael_ext_scroll_to_top']`:

```php
[
    'post_id' => 123,                                          // the page that owns the global config
    'enabled' => true,
    'eael_ext_scroll_to_top_global_display_condition' => 'all',
    'eael_ext_scroll_to_top_position_text' => 'bottom-right',
    'eael_ext_scroll_to_top_position_bottom' => [ 'unit' => 'px', 'size' => 15 ],
    // …every per-page control above, mirrored as a key here…
]
```

This array is written on every Elementor save by [`Core::get_ext_scroll_to_top_global_settings()`](../../includes/Traits/Core.php#L358) when both `eael_ext_scroll_to_top` and `eael_ext_scroll_to_top_global` are `yes` on the saved document. When the global toggle goes from `yes` → `no` on the owning document (the document whose `post_id` matches), the option is reset to `[ 'post_id' => null, 'enabled' => false ]`.

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/extentions/global_settings` | [`Core::save_global_values:297`](../../includes/Traits/Core.php#L297) | Mutate the full `eael_global_settings` array (including the scroll-to-top key) before persistence |
| `eael/registered_extensions` | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Remove the `scroll-to-top` slug from the registry to disable the extension entirely |

### Activation

Activation follows the standard EA extension pattern — the slug `scroll-to-top` must be present in `eael_save_settings` (default-enabled on fresh installs via [`Core::set_default_values()`](../../includes/Traits/Core.php#L153)). Disable through EA Settings → Extensions, or filter `eael/registered_extensions` to remove the key.

## Customization Recipes

### Recipe 1 — Change the scroll-show threshold

The 100 px threshold is hard-coded inside `src/js/view/scroll-to-top.js`. To make it 300 px, edit the source and rebuild:

```js
let offset = 300;       // was 100
```

There is no PHP filter that exposes the threshold to runtime config — a future enhancement would `wp_localize_script` an `eaelScrollToTopConfig` object that the JS reads.

### Recipe 2 — Hide the button on mobile

There is no built-in toggle. Add this CSS to the theme or via Customizer:

```css
@media (max-width: 767px) {
    .eael-ext-scroll-to-top-wrap { display: none !important; }
}
```

### Recipe 3 — Force-disable the global mode site-wide

```php
add_filter( 'eael/extentions/global_settings', function ( $settings ) {
    $settings['eael_ext_scroll_to_top'] = [ 'post_id' => null, 'enabled' => false ];
    return $settings;
} );
```

This wins over any document save because it runs on the same persistence call.

### Recipe 4 — Remove the extension entirely

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['scroll-to-top'] );
    return $exts;
} );
```

After the filter applies, the class is not instantiated, controls don't appear, and the footer renderer's `$this->get_settings('scroll-to-top')` check on line 654 returns falsy — short-circuiting the entire block.

## Common Issues

### Button enabled but doesn't appear on frontend

- **Likely cause 1:** Current document is a Theme Builder template (header / footer / single / etc.). `Helper::prevent_extension_loading()` returns true and Scroll to Top controls were never registered for that document. The toggle was not actually set to `yes` — `eael_ext_scroll_to_top` doesn't exist in the document's settings.
- **Likely cause 2:** Global mode display-condition mismatch. The global `post_id` points to a page, but the current view is a single post, and `eael_ext_scroll_to_top_global_display_condition` is set to `pages`. See lines 686–691 of `Elements.php` — the `$scroll_to_top_html` is reset to `''`.
- **Likely cause 3:** The owning global post isn't published. Line 685 sets the HTML to empty if the configured post status isn't `publish`.

### Button appears but icon is missing or shows a square

- **Likely cause:** The page doesn't have Font Awesome loaded (some themes / Elementor optimization modes skip FA registration). The Scroll to Top inline note recommends using an SVG icon instead — switch the icon picker to "SVG" library and upload a custom SVG.

### Per-page settings ignored, global config wins

- **Likely cause:** When both `eael_ext_scroll_to_top => yes` and global mode exist, the render path checks the per-page first (`$scroll_to_top_status_global = false`), so per-page should always win. If global is winning, double-check that `eael_ext_scroll_to_top` is actually `yes` on the current document — the hidden `eael_ext_scroll_to_top_has_global` field can confuse the UI into displaying a "global enabled" warning even when the local toggle is off.

### Editor preview doesn't update when toggling the switch

- **Cause:** This is by design. The editor JS [`src/js/edit/scroll-to-top.js`](../../src/js/edit/scroll-to-top.js) only triggers a reload-preview for the enable toggle and the icon control. Other style controls do update live via Elementor's `selectors` mechanism. If the reload isn't happening, check the browser console for `elementor.saver.update.apply` errors — usually a nonce or AJAX failure.

### Multiple pages claim global ownership simultaneously

- **Cause:** Each save **overwrites** `eael_global_settings['eael_ext_scroll_to_top']`, so only the most recently-saved document with `eael_ext_scroll_to_top_global => yes` is the "real" global. Older documents still have their checkbox set to `yes` in their `_elementor_data` post meta, but the option points elsewhere.
- **Fix:** Open the older document in the editor — the `eael_ext_scroll_to_top_global_warning_text` RAW_HTML on lines 58–73 will display a link to the current owner.

## Debugging Guide

1. **Confirm activation.** `print_r( get_option('eael_save_settings') )` — `[scroll-to-top] => 1` should be present.
2. **Confirm constructor ran.** Add `error_log('Scroll_to_Top ctor')` at the top of `__construct`. Open any document in Elementor — the log line should fire.
3. **Confirm hook is wired.** From a shutdown hook, `var_export( has_action('elementor/documents/register_controls') )` should show the registered callback.
4. **Confirm controls reach the editor.** Open Page Settings → Settings — the panel should appear unless the document is a Theme Builder template. If it's missing on a regular page / post, check `Helper::prevent_extension_loading()` output.
5. **Confirm settings persist.** After save, `get_post_meta($post_id, '_elementor_page_settings', true)` should contain the `eael_ext_scroll_to_top_*` keys with their values.
6. **Confirm frontend render.** View page source — the `<div class="eael-ext-scroll-to-top-wrap scroll-to-top-hide">` should appear right before `</body>`. If not, walk through the `if ($this->get_settings('scroll-to-top') == true)` block in `Elements.php:654` — start with `$document = Plugin::$instance->documents->get($post_id, false)` and confirm `$document_settings_data` contains `eael_ext_scroll_to_top => 'yes'`.
7. **Confirm assets enqueue.** `wp_script_is('eael-scroll-to-top', 'enqueued')` should return true on the frontend page where the button renders. If the script registered but didn't enqueue, the render-path bailed before reaching line 695.
8. **Confirm JS runs.** Browser console — scroll past 100 px and watch for the wrapper losing `scroll-to-top-hide` class. If the class never updates, the `window.scroll` listener didn't bind (usually because jQuery is missing or a JS error elsewhere is breaking the bundle).

## Architecture Decisions

### One Elementor hook, register at document level

- **Context:** A scroll-to-top button is a page-wide overlay, not a per-widget decorator. Hooking `elementor/element/common/_section_style/after_section_end` would add the controls to every widget — useless duplication.
- **Decision:** Hook only `elementor/documents/register_controls`, adding controls to Page Settings.
- **Alternatives rejected:** Hooking each element-type's controls separately (massive duplication); attaching to `wp_head` outside Elementor's controls system (loses the editor preview integration).
- **Consequences:** Configuration lives in the page-settings panel, not in any individual widget. Theme Builder templates need the `prevent_extension_loading` short-circuit because they're documents too.

### Global mode is single-shared, not multi-template

- **Context:** Could allow multiple "global templates" with different display rules. Would be more flexible but adds UX confusion (which template wins if multiple match?) and storage complexity.
- **Decision:** One global config in `eael_global_settings['eael_ext_scroll_to_top']`. Whichever document was last saved with the global toggle on owns it.
- **Alternatives rejected:** Per-condition global slots (`global_for_posts`, `global_for_pages`); user-configurable template registry inside EA settings (would need a new admin UI surface).
- **Consequences:** Editing a second document with the global toggle on silently steals ownership. A warning RAW_HTML in the controls panel tells the user when their document **isn't** the owner.

### Frontend render lives in `Elements::render_global_html`, not the extension class

- **Context:** Four extensions (Reading Progress, TOC, Scroll to Top, Custom Cursor) all need to emit footer-time markup. Each extension could own its own `wp_footer` hook, or the work could centralise.
- **Decision:** Centralise — one method on the Bootstrap-composed `Elements` trait handles all four. The extension classes only register controls.
- **Alternatives rejected:** Per-class `wp_footer` hooks (four separate string-concat builds, more opportunities for asset-enqueue races); separate trait per extension (proliferation).
- **Consequences:** `Elements.php` is large (1,000+ lines including TOC global CSS). Footer-render bugs touch one file; control-registration bugs touch another. Tradeoff is acceptable.

### Hard-coded scroll threshold and animation duration

- **Context:** 100 px threshold and 300 ms animation are the standard UX. Exposing them as controls bloats the panel.
- **Decision:** Hard-code in `src/js/view/scroll-to-top.js`.
- **Alternatives rejected:** Per-page slider controls (UX cost); global filter for advanced users (no current demand).
- **Consequences:** Users who want a different threshold must edit source or override CSS. The `eaelScrollToTopConfig`-via-`wp_localize_script` enhancement remains open as a future improvement.

## Known Limitations

- **No hide-on-mobile / hide-on-desktop toggle.** Must be added via custom CSS (Recipe 2).
- **No animation variants.** The reveal is always jQuery `fadeIn` / `fadeOut` over 300 ms. No slide / pop / scale options.
- **Position is bottom-only.** No top-left / top-right / center variants. Adding them would require new selector logic in both per-page and global CSS paths.
- **Single global config.** See Architecture Decision 2 — only one document at a time can own the site-wide settings.
- **Theme Builder templates silently disabled.** Controls won't appear on header / footer / single / archive editors. If a user wants a Scroll to Top button to render alongside their archive theme, they must enable it on a regular page or fall back to a custom widget.
- **No threshold customization without source edit.** 100 px is fixed.
- **JS scroll listener attached to both `window` and `elementorFrontend.elements.$body[0]`.** This double-binding is intentional for certain themes but produces redundant fade calls on themes that use the standard window scroll. Visual impact is negligible.
- **Icon picker assumes Font Awesome is loaded.** When FA is absent (theme optimization, page builder mode), `<i class="fas …">` renders nothing — the inline `RAW_HTML` note suggests the SVG fallback, but doesn't enforce it.
- **Single instance per page.** No support for two different scroll-to-top buttons (e.g. one for the user, one for an embedded iframe).

## Recent Significant Changes

No micro-changelog entries yet. Add future entries here when:

- Hook target changes (e.g. moving from `elementor/documents/register_controls` to a more specific scope)
- New controls land (animation variants, hide-on-device toggles, threshold customisation)
- Global mode storage shape changes
- The fade animation is replaced with a CSS-based reveal

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — subsystem overview, registry + activation flow
- **Architecture:** [`../architecture/asset-loading.md`](../architecture/asset-loading.md) — how the `eael-scroll-to-top` handles flow through `Asset_Builder`
- **Architecture:** [`../architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — Page Settings storage path (`_elementor_page_settings` post meta)
- **Sibling extension:** [`reading-progress.md`](reading-progress.md) — shares the same global-template pattern and same `render_global_html` footer-render path
- **Sibling extension:** [`promotion.md`](promotion.md) — canonical extension-doc example
- **Public docs:** <https://essential-addons.com/docs/scroll-to-top/>
- **Rules:** [`../../.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md), [`../../.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
