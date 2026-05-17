# Image Masking Extension

> Per-element image masking — registers an "Image Masking" control panel under every section, column, container, and widget that adds CSS `clip-path` or `mask-image` styling to images inside the element. Lite ships clip-path + built-in SVG shapes; SVG upload and Morphing are Pro-gated.

**Class file:** [`includes/Extensions/Image_Masking.php`](../../includes/Extensions/Image_Masking.php) (639 lines)
**Slug:** `image-masking` ([`config.php` line 1446](../../config.php#L1446))
**Public docs:** <https://essential-addons.com/elementor/docs/image-masking/>
**Pro-shared:** Lite hosts the controls + render. Pro adds custom SVG upload + Morphing via two `do_action()` extension points (`eael/image-masking/image_control` and `eael/image_masking/morphing_controls`) and one filter (`eael/image_masking/morphing_options`).

---

## Overview

Image Masking is one of the few EA extensions whose **controls** apply to every element type that can contain an `<img>`: sections, columns, containers, and the common-widget surface (which covers every standalone widget). The extension does not modify Elementor markup directly — it emits a per-element inline `<style id="eael-image-masking-{element_id}">` block during `elementor/frontend/before_render`, scoping its CSS to a wrapper class it adds via `add_render_attribute`.

There are three masking modes the user can choose between (`eael_image_masking_type` SELECT):

1. **`clip`** (default in Lite) — pure CSS `clip-path: polygon(...)`. The class ships five built-in shapes (`bavel`, `rabbet`, `chevron-left`, `chevron-right`, `star`) plus a "Use Custom Clip Path" textarea where users can paste `clip-path: polygon(...)` from <https://bennettfeely.com/clippy/>.
2. **`image`** — CSS `mask-image: url(...)` pointing at one of ~30 built-in SVG shapes shipped under `assets/front-end/img/image-masking/svg-shapes/`. The "Upload" option in the choose-shape grid is Pro-gated — Lite shows an upsell card; Pro fires `eael/image-masking/image_control` to inject the actual upload control.
3. **`morphing`** — entirely Pro-gated. Lite shows the upsell card. Pro hooks `eael/image_masking/morphing_options` to return `['svg_html' => '<svg>...</svg>', ...]`, and Lite's `before_render` echoes the SVG and attaches `data-morphing-options` JSON to the wrapper. The frontend lib (`polygon-morphing-animation.min.js`) reads those attributes.

For each mode the user can also enable a **Hover** variant: a separate tab in the controls panel offers parallel controls under the `_hover` suffix. `before_render` emits an extra CSS rule like `.eael-image-masking-{id}:hover img { clip-path: ... }` (or with a custom hover selector inserted before `:hover`).

The extension is **opt-in per element** via `eael_enable_image_masking` (SWITCHER, default off). Nothing happens until that switch is on; the rest of the controls are gated behind conditions on it.

## Components / File Map

| File | Lines | Role |
| ---- | ----- | ---- |
| [`includes/Extensions/Image_Masking.php`](../../includes/Extensions/Image_Masking.php) | 639 | Constructor (5 hooks), `register_controls()`, `masking_controllers($element, $tab)` helper (Normal + Hover share), `before_render()`, `cleanup_settings_data()`, `enqueue_scripts()`, private `clip_paths($shape)`, private `extract_first_path_d($svg)` |
| [`src/js/edit/image-masking.js`](../../src/js/edit/image-masking.js) (134 lines) | 134 | Editor-only live preview — walks `window.elementor.elements.models` recursively, calls `renderImageMasking(model)` for any element with `eael_enable_image_masking: yes`, appends a `<style id="eael-image-masking-{id}">` to the element node. Also strips legacy `eael_svg_path` keys from saved data. |
| `assets/front-end/js/lib-view/blob-animation/polygon-morphing-animation.min.js` | (third-party) | The morphing animation library — reads `data-morphing-options` from element wrappers. Used in both `view` and `edit` contexts per [`config.php` line 1449-1459](../../config.php#L1449). |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | (third-party) | DOMPurify — declared as an **edit-only** lib dependency. Not referenced directly in `image-masking.js`; loaded so Pro's morphing control panel (which accepts user-supplied SVG markup) has a sanitiser available before injecting it into the editor preview. |
| `assets/front-end/img/image-masking/svg-shapes/*.svg` | (assets) | ~30 built-in SVG shapes — referenced as `mask-image` URLs |
| `assets/front-end/img/image-masking/clip-paths/*.svg` | (assets) | Preview thumbnails for the clip-path CHOOSE control |
| [`config.php` line 1446](../../config.php#L1446) | 27 | Registry entry — declares **four** JS dependencies: morphing lib (view), morphing lib (edit), DOMPurify (edit), and the edit JS itself. No CSS dependency: the rendered styles are inline per-element. |
| N/A — no `src/js/view/image-masking.js` | — | The frontend has no extension-specific JS bundle. All frontend visual effect comes from CSS in the inline `<style>` block plus the morphing lib reading `data-morphing-options`. |
| N/A — no SCSS in `src/css/view/` | — | Same reason — no extension-owned stylesheet. The Elementor editor's general CSS handles control-panel layout. |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Type: `clip` with 5 built-in polygons | Yes | Yes |
| Type: `clip` with custom `clip-path` paste | Yes | Yes |
| Type: `image` with built-in SVG shapes (~30) | Yes | Yes |
| Type: `image` with **Upload Custom SVG** | Upsell only — RAW_HTML card with "Upgrade to EA PRO" | Yes — Pro hooks `eael/image-masking/image_control` to inject MEDIA control + upload handler |
| Type: `morphing` | Upsell only — RAW_HTML card | Yes — Pro hooks `eael/image_masking/morphing_controls` (controls) + `eael/image_masking/morphing_options` (runtime SVG payload) |
| Hover variant (Normal + Hover tabs) | Yes (for clip + built-in image) | Yes (extends to upload + morphing) |
| Mask size / position / repeat (for `image` type) | Yes | Yes |
| Render path (`before_render` writing inline `<style>`) | Lite-owned | Lite-owned; Pro hooks add data, never replace the render |

Lite hosts the entire skeleton; Pro plugs into two `do_action` slots inside the controls and one `apply_filters` slot inside the render. Both Lite and Pro instantiate the same `Image_Masking` class.

## Architecture

- **Five hooks in the constructor** ([lines 17-26](../../includes/Extensions/Image_Masking.php#L17)). Four `after_section_end` actions wire the same `register_controls` callback into column, section, container, and the common-widget Style tab. One `elementor/frontend/before_render` (priority 100) wires the runtime style emitter. One `wp_enqueue_scripts` localizes the SVG directory URL into the `elementor-frontend` script. Two `elementor/document/...` filters (`replace_id`, `save/data`) wire the cleanup pass.
- **Tab in Advanced tab, not Style tab.** `register_controls()` opens its `start_controls_section` on `Controls_Manager::TAB_ADVANCED` ([line 331](../../includes/Extensions/Image_Masking.php#L331)). This deliberately differs from `Hover_Effect`'s controls, which sit in the Style tab. Image Masking sits with other behavioural extensions (Wrapper Link, Custom Cursor) in the Advanced area.
- **Normal + Hover share via a helper.** `masking_controllers($element, $tab)` ([line 95](../../includes/Extensions/Image_Masking.php#L95)) builds the shape / custom-clip / SVG-choose controls once; called twice from `register_controls` — once with `$tab = ''` (Normal) and once with `$tab = '_hover'` (Hover). All control IDs are suffixed with `$tab`, e.g. `eael_image_masking_clip_path` and `eael_image_masking_clip_path_hover`.
- **Pro extension via `do_action`, not subclass.** Pro doesn't subclass `Image_Masking`. Instead, Lite fires `eael/image-masking/image_control` ([line 300](../../includes/Extensions/Image_Masking.php#L300)) and `eael/image_masking/morphing_controls` ([line 522](../../includes/Extensions/Image_Masking.php#L522)) at the points where the upload / morphing controls would otherwise be. Pro listens, calls `$element->add_control(...)` with the real controls. Lite's `if (!apply_filters('eael/pro_enabled', false))` branches show the upsell card on Lite; the `else` branch runs `do_action(...)` on Pro.
- **`before_render` writes a per-element `<style>` tag.** [Line 546](../../includes/Extensions/Image_Masking.php#L546). For each enabled element, the wrapper class `eael-image-masking-{element_id}` is added via `add_render_attribute`, then CSS is built scoped to that class — e.g. `.eael-image-masking-abc123 img { clip-path: polygon(...) }`. The `<style id="eael-image-masking-abc123">…</style>` is `echo`-ed inline. Note the `WordPress.Security.EscapeOutput.OutputNotEscaped` phpcs ignore: the CSS payload is not run through `wp_strip_all_tags` because it contains intentional `{ }` punctuation; escaping is delegated to `esc_html()` on the variable parts (element id, clip-path string parsed from settings).
- **Cleanup on save / id-replace.** `cleanup_settings_data($element_data)` ([line 28](../../includes/Extensions/Image_Masking.php#L28)) walks the entire `elements` tree of saved Elementor data. For any element whose `eael_enable_image_masking` is not `yes`, it strips every key with prefix `eael_image_masking_`, `eael_clip_`, `eael_svg_paths_`, `eael_image_morphing_`. Also strips a legacy `eael_svg_path` field from repeater items in `eael_svg_paths_custom`. Hooked to both `elementor/document/element/replace_id` (duplicate-element flows) and `elementor/document/save/data` (any save). This keeps `_elementor_data` lean.
- **`extract_first_path_d($svg)`** ([line 528](../../includes/Extensions/Image_Masking.php#L528)) — server-side helper that pulls the `d=""` attribute out of a `<path>` inside user-supplied SVG. Uses `DOMDocument::loadXML` first, falls back to a regex match. **Not called from anywhere in Lite's code path** — present in this file because Pro's morphing control flow uses it via inheritance/reference. The method exists in Lite so the Pro extension can call it on the shared class without redeclaring.
- **No CSS clip vs SVG `<mask>` decision per se.** The extension uses both:
  - `type = clip` → CSS `clip-path: polygon(...)` only
  - `type = image` → CSS `mask-image: url('/path/to/shape.svg')` + the `-webkit-mask-image` vendor prefix
  - `type = morphing` → an actual inline `<svg>` printed into the page, plus `data-morphing-options` JSON read by the polygon-morphing-animation lib

  There is no `<mask>` element constructed manually; the `mask-image` CSS handles that side via the browser engine.

## Render Behavior

### Editor-side (live preview)

[`src/js/edit/image-masking.js`](../../src/js/edit/image-masking.js) hooks `elementor/frontend/init`, then `elementorFrontend.hooks.addAction("frontend/element_ready/widget", ImageMaskingHandler)`. For every model with `eael_enable_image_masking: 'yes'`, it builds the same kind of CSS as PHP would and appends it to the element's DOM node:

```js
function renderImageMasking(model) {
    let settings = model?.attributes?.settings?.attributes;
    let elementId = model?.attributes?.id, element = $(`.elementor-element-${elementId}`);
    let styleId = 'eael-image-masking-' + elementId;
    $('#' + styleId).remove();

    if ('yes' === settings?.eael_enable_image_masking) {
        let style = '';
        if ('clip' === settings?.eael_image_masking_type) {
            // …build clip-path…
            style += '.elementor-element-' + elementId + ' img {clip-path: ' + clipPath + ';}';
        } else if ('image' === settings?.eael_image_masking_type) {
            let mask_url = EAELImageMaskingConfig?.svg_dir_url + image + '.svg';
            style += '.elementor-element-' + elementId + ' img {mask-image: url(' + mask_url + '); -webkit-mask-image: url(' + mask_url + ');}';
        }
        if (style) {
            element.append('<style id="' + styleId + '">' + style + '</style>');
        }
    }
}
```

Note that the editor uses `.elementor-element-{id}` as the scope selector, while the frontend uses `.eael-image-masking-{id}` (added via `add_render_attribute('_wrapper', 'class', ...)`). Both ultimately match the same wrapper since Elementor outputs `.elementor-element-{id}` itself.

### Frontend (rendered post)

`before_render($element)` ([line 546](../../includes/Extensions/Image_Masking.php#L546)) runs at priority 100 on `elementor/frontend/before_render`. For an element with `eael_enable_image_masking = yes` and `type = clip`:

```html
<!-- wrapper attributes get +class="eael-image-masking-abc123" -->
<section class="elementor-section elementor-element-abc123 eael-image-masking-abc123 ...">
  <style id="eael-image-masking-abc123">
    .eael-image-masking-abc123 img {clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)}
    .eael-image-masking-abc123:hover img {clip-path: polygon(...)}
  </style>
  <!-- rest of section …including <img>… -->
</section>
```

For `type = image`:

```html
<style id="eael-image-masking-abc123">
  .eael-image-masking-abc123 img {
      mask-image: url(.../svg-shapes/polygon.svg);
      -webkit-mask-image: url(.../svg-shapes/polygon.svg);
  }
</style>
```

For `type = morphing` (Pro-driven via `eael/image_masking/morphing_options`):

```html
<!-- Inline SVG printed by before_render (echoes $morphing_options['svg_html']) -->
<svg width="0" height="0">...</svg>
<section class="... eael-image-masking-abc123 eael-morphing-enabled"
         data-morphing-options="{ ...JSON... }">
  <!-- content -->
</section>
```

The morphing-animation lib (`polygon-morphing-animation.min.js`) reads `data-morphing-options` on `[data-morphing-options]` selectors at page load.

### Mask sizing / positioning (type `image` only)

`eael_image_masking_image_size`, `..._image_position`, `..._image_repeat`, `..._image_custom_size_custom` write directly to selectors like:

```scss
{{WRAPPER}} img {
    mask-size: contain;
    -webkit-mask-size: contain;
    mask-position: center center;
    mask-repeat: no-repeat;
}
```

These use Elementor's standard `selectors` mechanism — no PHP rendering needed beyond what Elementor's CSS pipeline already does.

## Asset Dependencies

### CSS

N/A — no extension-owned stylesheet. All effects are produced by inline `<style>` blocks emitted per-element by `before_render`, plus Elementor-pipeline CSS for mask sizing controls.

### JS

| Source | Output / file | Type | Context | Purpose |
| ------ | ------------- | ---- | ------- | ------- |
| `assets/front-end/js/lib-view/blob-animation/polygon-morphing-animation.min.js` | (vendor file copied into `lib-view/`) | `lib` | `view` | Reads `data-morphing-options` from `.eael-morphing-enabled` wrappers on the frontend |
| Same file | Same | `lib` | `edit` | Same library, loaded inside the Elementor editor iframe so morphing previews work |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | (vendor file) | `lib` | `edit` | DOMPurify global. Image_Masking's own JS doesn't call it; declared so Pro's morphing controls (which let users paste SVG markup) have a sanitiser available client-side before injecting markup into the editor preview |
| [`src/js/edit/image-masking.js`](../../src/js/edit/image-masking.js) | `assets/front-end/js/edit/image-masking.min.js` | `self` | `edit` | Live preview of clip / image masking inside the editor; legacy-data cleanup on save |

All four entries are in `config.php` lines 1449-1470. No `view` self-JS — frontend has only the lib + the inline `<style>` blocks.

### Localised data

`enqueue_scripts()` ([line 80](../../includes/Extensions/Image_Masking.php#L80)) calls:

```php
wp_localize_script( 'elementor-frontend', 'EAELImageMaskingConfig', [
    'svg_dir_url' => EAEL_PLUGIN_URL . 'assets/front-end/img/image-masking/svg-shapes/',
] );
```

This makes `EAELImageMaskingConfig.svg_dir_url` available to the editor JS so it can build `mask-image` URLs without round-tripping through PHP.

## Hook Timing

### Elementor hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `elementor/element/column/section_advanced/after_section_end` | 10 | `register_controls` | Add "Image Masking" panel to Column → Advanced tab |
| `elementor/element/section/section_advanced/after_section_end` | 10 | `register_controls` | Section → Advanced tab |
| `elementor/element/container/section_layout/after_section_end` | 10 | `register_controls` | Container → Layout tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `register_controls` | Every standalone widget → Style tab (despite the section name, the registered control section uses `TAB_ADVANCED`) |
| `elementor/frontend/before_render` | **100** | `before_render` | Emit per-element wrapper class + inline `<style>` |
| `elementor/document/element/replace_id` | 10 | `cleanup_settings_data` | Strip masking keys from copy-pasted elements where masking is disabled |
| `elementor/document/save/data` | 10 | `cleanup_settings_data` | Strip same keys on save; also strip legacy `eael_svg_path` from `eael_svg_paths_custom` repeaters |

### WordPress hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `wp_enqueue_scripts` | 10 | `enqueue_scripts` | `wp_localize_script('elementor-frontend', 'EAELImageMaskingConfig', ...)` |

### Hooks emitted

| Hook | Type | Where | Purpose |
| ---- | ---- | ----- | ------- |
| `eael/image-masking/image_control` | action | [line 300](../../includes/Extensions/Image_Masking.php#L300) | Pro hooks this to add the real "Upload SVG" media control when the user picks the Upload shape in the `image` type. Args: `$element`, `$condition` array, `$tab` suffix. |
| `eael/image_masking/morphing_controls` | action | [line 522](../../includes/Extensions/Image_Masking.php#L522) | Pro hooks this to add the morphing-mode controls (path repeater, animation timing, etc.). Arg: `$element`. |
| `eael/image_masking/morphing_options` | filter | [line 621](../../includes/Extensions/Image_Masking.php#L621) | Pro hooks this to return `['svg_html' => '<svg>...</svg>', ...other options]` that Lite will then print into the page and attach as `data-morphing-options` JSON. Args: `[]`, `$element`, `$element_id`. |
| `eael/pro_enabled` (consumed) | filter | [lines 283, 502](../../includes/Extensions/Image_Masking.php#L283) | Standard EA Pro detection — branches between upsell card and real Pro hook |

## Configuration & Extension Points

### Per-element controls (rendered in every section / column / container / widget when the extension is active)

| Control ID | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_enable_image_masking` | SWITCHER | — | Master enable |
| `eael_image_masking_type` | CHOOSE | `clip` | `image` / `clip` / `morphing` |
| `eael_image_masking_clip_path[ _hover ]` | CHOOSE | `bavel` | Pick from `bavel`, `rabbet`, `chevron-left`, `chevron-right`, `star` |
| `eael_image_masking_enable_custom_clip_path[ _hover ]` | SWITCHER | — | Override the choose-control with a custom polygon |
| `eael_image_masking_custom_clip_path[ _hover ]` | TEXTAREA | `clip-path: polygon(50% 0%, 80% 10%, ...)` | Custom polygon paste-in (placeholder hint links to Clippy) |
| `eael_image_masking_svg[ _hover ]` | CHOOSE | `polygon` | ~30 built-in SVG shapes + `upload` (upload is Pro-only) |
| `eael_image_masking_upload_pro_message[ _hover ]` | RAW_HTML | (upsell) | Shown only on Lite, hidden behind condition `eael_image_masking_svg = upload` |
| `eael_image_masking_hover_effect` | SWITCHER | — | Inside the Hover tab; enables the parallel `_hover` controls |
| `eael_image_masking_hover_selector` | TEXT | — | CSS selector — restricts which child triggers the hover state |
| `eael_image_masking_image_size` | SELECT | `contain` | Maps to CSS `mask-size` |
| `eael_image_masking_image_custom_size_custom` | SLIDER | — | When size = `custom`, slider for `mask-size` in px / % |
| `eael_image_masking_image_position` | SELECT | `center center` | CSS `mask-position` |
| `eael_image_masking_image_repeat` | SELECT | `no-repeat` | CSS `mask-repeat` |
| `eael_image_masking_pro_message` | RAW_HTML | (upsell) | Shown only on Lite, when `type = morphing` |

Pro adds further controls inside the `eael/image-masking/image_control` and `eael/image_masking/morphing_controls` extension points — they do not appear here.

### Filter / action surface

| Hook | Direction | Where to call |
| ---- | --------- | ------------- |
| `eael/image-masking/image_control` | listen (Pro pattern) | Add SVG-upload control under the Image type's "Upload" choice |
| `eael/image_masking/morphing_controls` | listen (Pro pattern) | Add the entire morphing-mode controls block |
| `eael/image_masking/morphing_options` | listen (Pro pattern) | Return the runtime morphing payload (SVG HTML + animation options) |
| `eael/pro_enabled` | already consumed | Standard Pro detection |

Image Masking does not currently expose a public render-time filter (e.g. for the inline CSS payload). The only override path for the rendered CSS is to remove `before_render` and reimplement it — not recommended.

## Customization Recipes

### Recipe 1 — Add your own clip-path preset

The five built-ins live in `Image_Masking::clip_paths($shape)` ([line 85](../../includes/Extensions/Image_Masking.php#L85)). Adding a sixth requires editing the class (no filter exists today):

```php
// includes/Extensions/Image_Masking.php — extending clip_paths
private function clip_paths( $shape ){
    $shapes = [
        'bavel'         => 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)',
        'rabbet'        => 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)',
        'chevron-left'  => 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',
        'chevron-right' => 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',
        'star'          => 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
        'heart'         => 'polygon(50% 15%, 70% 0%, 100% 25%, 50% 100%, 0% 25%, 30% 0%)',  // new
    ];
    return $shapes[$shape] ?? '';
}
```

You also have to add a matching CHOOSE option (with thumbnail SVG) to the `eael_image_masking_clip_path` control around [line 114](../../includes/Extensions/Image_Masking.php#L114) and likewise to the editor JS's `get_clip_path` ([line 4](../../src/js/edit/image-masking.js#L4)). A safer recipe for users is to use the existing "Use Custom Clip Path" textarea — same end result without code changes.

### Recipe 2 — Use a custom clip-path from Clippy

Inside the editor:

1. Turn on **Enable Image Masking**, set Type to **Clip Path**
2. Turn on **Use Custom Clip Path**
3. Paste from <https://bennettfeely.com/clippy/> into the textarea (the leading `clip-path: ` prefix is stripped by `before_render`)

```text
clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%);
```

No PHP changes required. The hover variant works the same way inside the Hover tab.

### Recipe 3 — Hide the upsell card without flipping global pro state

For internal demos / screenshots, you may want to remove the "Upgrade to EA PRO" cards. Filter them out at the controls layer:

```php
add_action( 'elementor/element/before_section_end', function ( $section, $section_id ) {
    if ( $section_id === 'eael_image_masking_section' ) {
        // No clean public hook to remove a single control; the simplest path is to
        // unset both Pro-upsell controls' visibility via JS or a custom Elementor
        // tweak. For a server-side hide, remove the *registration*:
        remove_action(
            'elementor/element/common/_section_style/after_section_end',
            [ \Essential_Addons_Elementor\Classes\Bootstrap::instance(), 'register_controls' ]
        );
    }
}, 10, 2 );
```

Caution — that disables the entire Image Masking panel on widgets, not just the upsell card. There is no narrow "hide just the Pro card" path today.

### Recipe 4 — Suppress the extension entirely

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['image-masking'] );
    return $exts;
} );
```

Removes the slug from the registry before Bootstrap iterates it — `Image_Masking` never instantiates, the morphing-animation lib + DOMPurify edit-asset enqueues are skipped, no `wp_localize_script` runs. Test-environment use only.

## Common Issues

### Mask applied to wrong image inside a section

- **Symptom:** Multiple `<img>`s inside a section, but the clip-path applies to all of them.
- **Cause:** The selector is `.eael-image-masking-{id} img` — matches every descendant `<img>`. No control today scopes to a specific image inside a column/section/widget.
- **Fix:** Apply the mask on the widget (Image / Image Box) instead of the parent section. Each widget gets its own element id, so the rule scopes to its image only.

### Clip-path or mask invisible on Safari

- **Symptom:** Works in Chrome / Firefox but not Safari.
- **Cause:** Safari needs the `-webkit-` prefix for `mask-image`, which `before_render` does emit. For `clip-path`, Safari has supported the unprefixed property since 14; older Safaris (< 14) need `-webkit-clip-path`, which the extension does **not** emit.
- **Fix:** For very old browser support, add `-webkit-clip-path` via a child theme stylesheet that mirrors `.eael-image-masking-* img { … }` rules. Modern Safari needs no change.

### Hover state never triggers

- **Symptom:** Hover tab is configured but `:hover` styles don't apply.
- **Likely causes:**
  1. `eael_image_masking_hover_effect` is off — the hover SWITCHER inside the Hover tab. The tab is visible without it, but the hover CSS isn't emitted.
  2. The Hover Selector field has a typo. The CSS rule becomes `.eael-image-masking-{id} .typo:hover img { ... }` — if `.typo` doesn't exist inside the element, nothing matches.
- **Diagnose:** View page source, find the `<style id="eael-image-masking-{id}">`. Check that a `:hover` rule is present and its selector path matches your DOM.
- **Fix:** Toggle `eael_image_masking_hover_effect` to Yes. Leave Hover Selector blank to hover on the entire element.

### Upload shape shows upsell in Pro

- **Symptom:** Pro is active but the "Upload" option in the SVG choose grid still shows the upsell card.
- **Likely cause:** `apply_filters('eael/pro_enabled', false)` returns false. Either Pro plugin is not active, or its filter registration happens after Lite's Bootstrap runs.
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` at the top of any admin pageview.
- **Fix:** Confirm Pro is active. If a timing issue, Pro should register the filter at `plugins_loaded` priority earlier than 100.

### Saved masking settings linger after disabling the extension

- **Symptom:** Disabled Image Masking in EA settings, but `_elementor_data` still contains `eael_image_masking_*` keys.
- **Cause:** `cleanup_settings_data` only fires on **save** or **replace_id**. Disabling the extension doesn't open every saved post and re-save it.
- **Fix:** Either re-save the post (cleanup runs), or accept the legacy keys (they're inert without the extension active). Don't hand-edit `_elementor_data` — the JSON shape is fragile.

### Editor preview lags when toggling controls

- **Symptom:** Switching shapes redraws the preview slowly.
- **Cause:** `getImageMaskingSettingsVal` ([edit JS line 91](../../src/js/edit/image-masking.js#L91)) recursively walks `elementor.elements.models` from the root on every editor open. For very large pages this is O(n).
- **Fix:** No user-facing fix today. The walk runs only once per editor load; subsequent updates use the per-element `frontend/element_ready/widget` hook. If your editor open is slow, profile to confirm it's this loop and not Elementor's own initialisation.

## Debugging Guide

1. **Confirm activation.** `wp option get eael_save_settings | grep image-masking` — expect `1`. If `0`, enable via EA settings or Setup Wizard.
2. **Confirm controls register.** Open any Image widget in Elementor; switch to **Advanced** tab; look for **Image Masking** section. Missing? — the constructor didn't instantiate (verify with an `error_log` at the top of `__construct`) or the slug was filtered out via `eael/registered_extensions`.
3. **Confirm the wrapper class is added.** View page source for the affected section. Expect `class="elementor-section ... eael-image-masking-{id} ..."`. If absent, `before_render` did not branch into the masking code — either `eael_enable_image_masking !== 'yes'`, or `before_render` isn't hooked.
4. **Confirm the inline `<style>`.** Search for `<style id="eael-image-masking-`. If absent, check that `clip_paths($shape)` returns a non-empty string (an invalid shape name returns `''`, which suppresses the rule).
5. **Confirm the lib loads on the frontend.** Network tab: `polygon-morphing-animation.min.js` should be in the response. If missing, `Asset_Builder` may have skipped enqueueing — check `config.php` line 1449 isn't filtered out.
6. **Confirm DOMPurify presence in editor.** Editor pageload network tab: `purify.min.js` from `assets/front-end/js/lib-view/dom-purify/`. If absent, the edit-context dependency is broken (re-run `npm run build` to confirm assets are present in the file system).
7. **For `type = image` failures**, hit the mask URL directly in the browser (e.g. `https://example.com/wp-content/plugins/.../svg-shapes/polygon.svg`). 404 means the SVG file is missing from the plugin distribution — re-install or restore from the WordPress.org zip.
8. **For morphing failures (Pro only)**, dump `apply_filters('eael/image_masking/morphing_options', [], $element, $id)` from inside `before_render` to confirm Pro is returning a payload. If empty, the issue is on the Pro side, not Lite.

## Architecture Decisions

### Inline `<style>` per element instead of an external stylesheet

- **Context:** Each masking setting depends on per-element values (clip-path shape, hover selector, custom polygon). We could (a) generate a single CSS file at save time, or (b) emit a `<style>` tag inline on each render.
- **Decision:** Inline `<style>` blocks emitted during `before_render`.
- **Alternatives rejected:** Saved CSS file — requires write access to `wp-content/uploads/`, plus a regeneration step on every save; not portable across hosts. Inline `style` attribute on the element — `clip-path` works but pseudo-selectors like `:hover` are impossible with inline-style.
- **Consequences:** Page-render cost is one extra `<style>` block per masking-enabled element (negligible). HTML payload grows slightly. Cache-busting "just works" — there's no separate file to invalidate.

### Pro extension via `do_action`, not subclass

- **Context:** Pro needs to add upload + morphing controls. Two ways: (a) subclass `Image_Masking` and override `register_controls`, or (b) host extension points in Lite that Pro plugs into.
- **Decision:** Two `do_action` hooks inside Lite's `register_controls` + one `apply_filters` inside `before_render`. Pro hooks them.
- **Alternatives rejected:** Subclass + re-instantiate from Pro — would mean both classes register on the same Elementor hooks, double-rendering controls; cleaning that up would require Lite to know about Pro.
- **Consequences:** Lite always runs first. The upsell card is part of Lite's control flow; Pro replaces it via the action. Cleaner ownership. Cost: Pro is coupled to the specific `do_action` signatures.

### Cleanup pass on `replace_id` and `save/data`

- **Context:** When a user disables masking on an element, the saved settings still contain leftover `eael_image_masking_*` keys. They're inert but they accumulate.
- **Decision:** Two filter hooks — `elementor/document/element/replace_id` (duplicate flows) and `elementor/document/save/data` (any save) — walk the entire element tree and strip the keys.
- **Alternatives rejected:** No cleanup (data accumulation forever); cleanup on element render (runs on every page view, wasteful); a separate cron job (overengineered).
- **Consequences:** Saves take slightly longer (single tree walk). The `_elementor_data` payload stays clean. Legacy data from pre-cleanup versions stays in the DB until each post is re-saved — handled by the `eael_svg_path` "TODO: remove after several versions" block.

### Morphing as Pro-only, Lite still loads the lib

- **Context:** Morphing requires `polygon-morphing-animation.min.js`. Lite doesn't ship the morphing controls, but the lib is part of the Lite distribution.
- **Decision:** Declare the morphing lib in `config.php` for both `view` and `edit` contexts under the `image-masking` slug. Pro then doesn't need to ship its own copy.
- **Alternatives rejected:** Lite skip the lib; Pro ships it — would mean Pro needs to manage its own asset registration and risks version drift between Lite and Pro. Lite is the source of truth for asset paths.
- **Consequences:** Lite frontend payload includes ~20KB of unused lib when masking is enabled but morphing is not. Pro adds zero net asset bytes. Pragmatic trade-off.

### Hover via separate `_hover`-suffixed controls instead of `start_controls_tabs` state

- **Context:** Elementor's `start_controls_tabs` Normal/Hover pattern usually uses one set of controls bound to a `:hover` selector via Elementor's selector engine. Image Masking is more complex — it conditionally adds a different shape on hover.
- **Decision:** Two parallel sets of controls. `masking_controllers($element, $tab)` runs twice, once with `''` and once with `'_hover'`. `before_render` reads both sets and emits two CSS rules.
- **Alternatives rejected:** Single set of controls with built-in tabs binding — couldn't express "different polygon shape on hover" cleanly.
- **Consequences:** Twice the control IDs. Visible to other code paths (e.g. `cleanup_settings_data` strips both prefixes). User-facing: editors see a familiar Normal/Hover tab pair.

## Known Limitations

- **All `<img>` inside the element get the mask.** No control to target a specific child image. Workaround: apply on a leaf widget (Image / Image Box) rather than the parent section.
- **No `-webkit-clip-path` emitted.** Safari 14+ is fine; older Safari versions get no clipping. EA does not formally support Safari < 14.
- **Custom clip-path field is a free-form textarea.** No validation. Pasting invalid syntax silently produces broken visuals.
- **`extract_first_path_d()` exists in Lite but is unused.** Dead code in Lite, used by Pro. Removing it would break Pro silently — kept as a stable interface point.
- **Mask sizing controls (`mask-size`, `mask-position`, `mask-repeat`) only apply to type `image`.** Clip type ignores them. Documented in the control conditions but not in the UI tooltip.
- **Morphing lib loads even when morphing is not in use.** ~20KB unconditional cost per page where masking is active.
- **No filter on the rendered CSS payload.** Cannot post-process the inline `<style>` content from PHP.
- **No filter to add a custom clip-path shape.** The five built-ins are hardcoded; adding a sixth requires editing the class file (no `apply_filters` around the array).
- **Editor JS recursion walks the entire model tree on init.** O(n) cost on first editor open; trivial for small pages, noticeable for very large ones.
- **`cleanup_settings_data` runs once per save** and walks the whole tree. For pages with thousands of elements, the save handler grows linearly.
- **Hover selector accepts any CSS, no scoping.** A hover selector of `body` would apply the hover mask whenever the page is hovered — by design, but not validated against. Editors can shoot themselves in the foot here.

## Recent Significant Changes

- **`eael_svg_path` deprecation.** Legacy `eael_svg_path` key inside `eael_svg_paths_custom` repeaters is being stripped on save (`cleanup_settings_data`, [line 50-55](../../includes/Extensions/Image_Masking.php#L50)) and on editor load ([edit JS line 111](../../src/js/edit/image-masking.js#L111)). The TODO comments say to remove the cleanup blocks once existing sites have re-saved — not yet removed.
- **DOMPurify added as an edit-context dependency.** Not directly used by Lite's edit JS; staged for Pro's morphing custom-SVG paste flow.
- **`enqueue_scripts` localising `EAELImageMaskingConfig.svg_dir_url`.** Allows the editor JS to build mask URLs without a PHP round-trip.

Format for future entries: `version — description (#card)`.

## Cross-References

- **Subsystem doc:** [`docs/architecture/extensions.md`](../architecture/extensions.md) — extension registration loop, `'context'` semantics, action-map table that lists Image Masking
- **Render-phase doc:** [`docs/architecture/asset-loading.md`](../architecture/asset-loading.md) — how `Asset_Builder` reads the four-entry dependency block
- **Editor-data doc:** [`docs/architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — `get_settings_for_display()` resolution, which `before_render` uses
- **Sibling extension doc:** [`docs/extensions/promotion.md`](promotion.md) — canonical example for this folder's format; also the Pro-upsell pattern used by Image Masking's `image_masking_upload_pro_message` and `image_masking_pro_message` cards
- **Sibling extension doc:** [`docs/extensions/special-hover-effect.md`](special-hover-effect.md) — similar "control panel on every widget" pattern but with view-context CSS / JS bundles
- **Public docs:** <https://essential-addons.com/elementor/docs/image-masking/>
- **External resource:** <https://bennettfeely.com/clippy/> — referenced in the custom-clip control description as a polygon generator
- **Skills:** [`.claude/skills/debug-widget`](../../.claude/skills/debug-widget/SKILL.md), [`.claude/skills/widget-review`](../../.claude/skills/widget-review/SKILL.md)
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md), [`.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
