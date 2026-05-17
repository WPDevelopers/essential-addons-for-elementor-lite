# Wrapper Link Extension

> Make any Elementor section, column, container, or widget behave as a clickable link. Adds a "Wrapper Link" control panel to all four element types and, at frontend render time, either injects a real `<a>` tag inside the wrapper or attaches a JS click handler that synthesises an anchor on the fly.

**Class file:** [`includes/Extensions/Wrapper_Link.php`](../../includes/Extensions/Wrapper_Link.php) (135 lines)
**Slug:** `wrapper-link` ([`config.php:1403`](../../config.php#L1403))
**Public docs:** <https://essential-addons.com/docs/wrapper-link-elementor/>
**Pro-shared:** Lite-only class; the same file is reused when EA Pro is installed alongside Lite. No Pro-specific override exists.

---

## Overview

Wrapper Link is the canonical "extend every element with one control" extension. Its constructor wires the same `register_controls` callback against four Elementor control-registration hooks — common (every widget), section advanced, column advanced, and container layout — so the **Wrapper Link** section appears on every selectable element type in the editor.

Each panel offers three controls:

1. **Enable Wrapper Link** — switcher that gates everything else.
2. **Link** — a URL control with dynamic-tags support (External / nofollow / Custom attributes inherited from Elementor's `URL` type).
3. **Disable Traditional Link** — switcher that flips the runtime behaviour between two implementations of the click target. A warning `RAW_HTML` row appears when this is enabled, explaining that dynamic tags and custom attributes won't work.

At frontend render, [`Wrapper_Link::before_render()`](../../includes/Extensions/Wrapper_Link.php#L109) runs at priority 100 on `elementor/frontend/before_render`. If wrapper link is enabled and a URL is set, it either:

- Adds a `data-eael-wrapper-link='{json}'` attribute plus the `eael-non-traditional-link` class on the wrapper (the JS-driven path), or
- Echoes an `<a>` tag before the wrapper output, marked with a `--eael-wrapper-link-tag` class and a unique id, plus a `data-eael-wrapper-link="<id>"` reference on the wrapper.

The frontend JS at [`src/js/view/wrapper-link.js`](../../src/js/view/wrapper-link.js) then resolves whichever path was chosen.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Wrapper_Link.php`](../../includes/Extensions/Wrapper_Link.php) | The extension class — constructor wires 5 hooks, `register_controls` adds the panel, `before_render` injects the link, `eael_container_before_wrapper_link_wpml` translates the URL for WPML containers |
| [`src/js/view/wrapper-link.js`](../../src/js/view/wrapper-link.js) | Frontend behaviour — registers an Elementor frontend action against `frontend/element_ready/global` that wires either a click handler (non-traditional path) or repositions the injected anchor (traditional path) |
| [`assets/front-end/js/view/wrapper-link.min.js`](../../assets/front-end/js/view/wrapper-link.min.js) | Built output enqueued by `Asset_Builder` |
| [`config.php:1403`](../../config.php#L1403) | Registry entry with a `'js'` `'view'`-context dependency — no CSS |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Class instantiates | ✅ | ✅ (same class) |
| Controls appear on widgets / sections / columns / containers | ✅ | ✅ |
| WPML URL translation for containers | ✅ | ✅ |
| Traditional `<a>` injection path | ✅ | ✅ |
| Non-traditional `data-href` click path | ✅ | ✅ |
| Dynamic tags inside the URL control | ✅ | ✅ (Pro provides more dynamic tag sources) |

No Pro override. The dynamic-tags listed in the Link control are populated by whichever EA plan provides them.

## Architecture

- **Four `register_controls` hooks, one callback.** The constructor wires `elementor/element/common/_section_style/after_section_end` (every widget's Style tab), `elementor/element/column/section_advanced/after_section_end` (column Advanced tab), `elementor/element/section/section_advanced/after_section_end` (section Advanced tab), and `elementor/element/container/section_layout/after_section_end` (container Layout tab). Each invokes the same `register_controls($element)` method — so the panel structure is identical everywhere, and Elementor figures out the right tab placement based on which hook fired.
- **`before_render` at priority 100.** The render-time injection hook runs late (priority 100) so any element-specific render attributes added by earlier extensions are already in place before Wrapper Link layers its own. `Hover_Effect` shares this priority for its own runtime modifications.
- **Two implementations of "clickable".** The two paths exist because each has trade-offs:
    - **Traditional (default)**: a real `<a>` tag with `add_link_attributes` — keeps Elementor's dynamic-tag, custom-attribute, and `rel` handling intact, but produces nested `<a>` elements when the wrapped widget itself contains links (invalid HTML).
    - **Non-traditional**: a `data-eael-wrapper-link` JSON attribute consumed by JS that synthesises a single-use anchor on click — avoids nested-anchor HTML, but loses dynamic tags and custom attributes (hence the warning copy in the editor).
- **WPML hook is container-specific.** The standalone `eael_container_before_wrapper_link_wpml` callback also hooks `elementor/frontend/before_render` (no explicit priority, so it runs at the default 10, before `before_render` itself at 100). It rewrites `eael_wrapper_link['url']` to the WPML-translated permalink before the main render handler reads it. Containers were singled out because Elementor's built-in WPML integration covers widgets and sections via Elementor's own URL handling but skips the container element type at the time this extension was written.
- **No render output for disabled state.** The render handler short-circuits unless the switcher is `"yes"` and the URL is non-empty. Inactive wrapper links contribute zero bytes to the rendered HTML.

## Render Behavior

### Traditional path (default)

When `eael_wrapper_link_switch == 'yes'`, `eael_wrapper_link_disable_traditional` is empty or off, and the URL is set:

```html
<a class="eael-wrapper-link-<element_id> --eael-wrapper-link-tag" href="https://example.com" target="_blank" rel="nofollow"></a>
<div class="elementor-element ..." data-eael-wrapper-link="eael-wrapper-link-<element_id>">
  <!-- normal element output -->
</div>
```

The `<a>` is emitted via `echo` inside `before_render` and ends up immediately before the wrapper. The frontend JS then moves the anchor inside the wrapper and stretches it absolutely to fill the wrapper's bounds:

```js
anchorLink.appendTo($scope).css({
    background: 'transparent', border: 'none', position: 'absolute',
    height: '100%', width: '100%', zIndex: '9999', top: 0, left: 0
});
```

### Non-traditional path

When `eael_wrapper_link_disable_traditional == 'yes'`:

```html
<div class="elementor-element ... eael-non-traditional-link"
     data-eael-wrapper-link='{"url":"https:\/\/example.com","is_external":"on","nofollow":""}'>
  <!-- normal element output -->
</div>
```

No `<a>` is emitted. The frontend JS sets `cursor: pointer` on the wrapper and attaches a click handler that creates a synthetic anchor (via `document.createElement('a')`) per click. `target = '_blank'` is set when `is_external === 'on'`; `rel = 'nofollow'` is set when `nofollow === 'on'`. The synthetic anchor's `href` is passed through `ea.sanitizeURL()` (provided by EA's general JS bundle).

### WPML rewrite (container only)

If the rendered element is a container and a URL is set, `eael_container_before_wrapper_link_wpml` rewrites `eael_wrapper_link['url']` to `apply_filters( 'wpml_permalink', $url, $lang, true )` before the main render handler reads the settings.

## Asset Dependencies

| Type | Source | Output | Context | Notes |
| ---- | ------ | ------ | ------- | ----- |
| JS | [`src/js/view/wrapper-link.js`](../../src/js/view/wrapper-link.js) | `assets/front-end/js/view/wrapper-link.min.js` | `view` | Registered in [`config.php:1403`](../../config.php#L1403). Loaded by `Asset_Builder` when the page contains any element that has wrapper link enabled — driven by the element registry, not by element-level scanning, so the JS loads as soon as the extension is active even if no element actually uses it. |

No CSS — the `cursor: pointer` style is set inline by the JS for the non-traditional path; the traditional path positions the injected `<a>` via inline styles too.

The JS depends on `elementorFrontend.hooks.addAction('frontend/element_ready/global', …)`, which is part of Elementor core's frontend runtime.

## Hook Timing

| Hook | Priority | Phase | Effect |
| ---- | -------- | ----- | ------ |
| `elementor/element/common/_section_style/after_section_end` | 10 (default) | Editor — every widget's Style tab | Adds Wrapper Link panel |
| `elementor/element/column/section_advanced/after_section_end` | 10 | Editor — column Advanced tab | Adds Wrapper Link panel |
| `elementor/element/section/section_advanced/after_section_end` | 10 | Editor — section Advanced tab | Adds Wrapper Link panel |
| `elementor/element/container/section_layout/after_section_end` | 10 | Editor — container Layout tab | Adds Wrapper Link panel |
| `elementor/frontend/before_render` (callback `before_render`) | 100 | Frontend render | Injects `<a>` or `data-*` attributes |
| `elementor/frontend/before_render` (callback `eael_container_before_wrapper_link_wpml`) | 10 | Frontend render | Rewrites container URL through `wpml_permalink` |
| `wpml_permalink` (filter consumed) | — | Frontend render | WPML returns translated permalink for the current language |
| `wpml_current_language` (filter consumed) | — | Frontend render | WPML returns the current language code for the lookup |

### JS lifecycle

| Event | Handler |
| ----- | ------- |
| `elementor/frontend/init` (window event) | Registers `EaelWrapperLink` against `frontend/element_ready/global` |
| `frontend/element_ready/global` (Elementor hook) | `EaelWrapperLink($scope, $)` runs per element — either wires click handler or repositions injected anchor |

## Configuration & Extension Points

Wrapper Link does not emit any custom `do_action` / `apply_filters` of its own. It does consume:

- `wpml_current_language` and `wpml_permalink` (filters from the WPML plugin) — used in the container-specific WPML rewrite. Filterable via WPML's own surface; not EA-owned.

To extend behaviour:

| Mechanism | Where | Use |
| --------- | ----- | --- |
| `eael/registered_extensions` filter | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Remove `wrapper-link` from the registry to disable everywhere |
| `eael_save_settings` option | EA Settings / Setup Wizard | Toggle on/off |
| Higher-priority `elementor/frontend/before_render` callback | Your own code | Intercept and modify `eael_wrapper_link` settings before this extension reads them — must run before priority 100 |

## Customization Recipes

### Recipe 1 — Restrict Wrapper Link to sections only

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    if ( isset( $exts['wrapper-link'] ) ) {
        // Replace with a subclass that wires only the section hook.
        $exts['wrapper-link']['class'] = 'My_Custom\\Wrapper_Link_Sections_Only';
    }
    return $exts;
} );
```

The subclass overrides `__construct` to register only `elementor/element/section/section_advanced/after_section_end`, plus the `before_render` hook. The other `register_controls` hooks are skipped, so the panel never appears on widgets / columns / containers.

### Recipe 2 — Force non-traditional mode for all elements

If you want to avoid nested-anchor HTML on every site:

```php
add_action( 'elementor/frontend/before_render', function ( $element ) {
    $settings = $element->get_settings_for_display();
    if ( ! empty( $settings['eael_wrapper_link_switch'] ) ) {
        $element->set_settings( 'eael_wrapper_link_disable_traditional', 'yes' );
    }
}, 90 ); // run before Wrapper_Link::before_render at priority 100
```

Setting the value before the main handler reads it forces the non-traditional branch. Loses dynamic tags and custom attributes, as the editor warning explains.

### Recipe 3 — Open all wrapper links in the same tab

Override the JS-level `target` resolution. Add this to a child theme's frontend JS:

```js
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
        if ($scope.hasClass('eael-non-traditional-link')) {
            // Suppress the new-tab behaviour by stripping is_external.
            const data = $scope.data('eael-wrapper-link');
            if (data && data.is_external === 'on') {
                data.is_external = '';
                $scope.data('eael-wrapper-link', data);
            }
        }
    }, 5); // run before EA's handler at default priority 10
});
```

## Common Issues

### Wrapper Link panel doesn't appear

- **Cause:** `wrapper-link` is disabled in `eael_save_settings`, or the class never instantiated.
- **Fix:** Enable Wrapper Link in EA settings.

### Click does nothing on frontend

- **Cause:** The JS file did not enqueue — usually because `Asset_Builder` didn't see this page as eligible (popup templates, REST endpoints, AMP).
- **Diagnose:** View source, search for `wrapper-link.min.js`. Confirm it loaded.
- **Fix:** Confirm the page type is in `Asset_Builder`'s render-eligible list. See [`asset-loading.md`](../architecture/asset-loading.md).

### Nested anchor HTML warnings in HTML validator

- **Cause:** Traditional path is active, and the wrapped widget already contains its own `<a>` (e.g. a Heading widget with a link). HTML spec forbids `<a>` inside `<a>`.
- **Fix:** Toggle "Disable Traditional Link" on for that element. Accept the loss of dynamic tags / custom attributes.

### Dynamic tag inside the URL prints as raw shortcode

- **Cause:** "Disable Traditional Link" is on. The non-traditional path stores the URL as a literal string in `data-eael-wrapper-link`; Elementor's `add_link_attributes` (which resolves dynamic tags) is not called on that branch.
- **Fix:** Switch back to traditional mode for that specific element.

### WPML container link uses the default-language URL

- **Cause:** `eael_container_before_wrapper_link_wpml` runs at priority 10 on `elementor/frontend/before_render`. If another callback at the same priority mutates the settings after this one, the rewrite is lost.
- **Fix:** Inspect any sibling `before_render` callback. Move yours to priority > 10 if necessary.

### Click target is wrong (opens same tab vs new tab)

- **Cause:** Mismatch between the URL control's "Open in new window" toggle and what the JS reads. The JS branches on `is_external === 'on'` (string `"on"`, not boolean).
- **Fix:** Ensure the URL control's external toggle is genuinely on. Re-save the element. Inspect the rendered `data-eael-wrapper-link` JSON to confirm.

## Debugging Guide

1. **Confirm activation.** `error_log( get_option( 'eael_save_settings' )['wrapper-link'] ?? 'missing' );`
2. **Confirm the JS is loaded on frontend.** View source for the published page; search for `wrapper-link.min.js`. If missing, the Asset_Builder pipeline didn't enqueue it — check `_eael_widget_elements` post meta for the page and verify the extension's dependency was registered.
3. **Inspect the rendered HTML.** For traditional mode, look for the `<a class="--eael-wrapper-link-tag">` sibling immediately before the wrapper. For non-traditional, look for the `data-eael-wrapper-link='{…}'` JSON attribute on the wrapper.
4. **Open browser devtools Console.** When clicking the element, no errors should appear. A common one is `ea.sanitizeURL is not a function`, which means `eael-general.js` did not load.
5. **For container WPML issues**, log inside `eael_container_before_wrapper_link_wpml`:
   ```php
   error_log( sprintf( 'Wrapper Link WPML: %s -> %s', $original, $url ) );
   ```
6. **For "control appears in too many places"**, the extension is wired against `_section_style/after_section_end` (every widget) — that's intentional. If you want narrower scope, see [Recipe 1](#recipe-1--restrict-wrapper-link-to-sections-only).
7. **For race conditions on save**, the `_elementor_data` blob must contain `eael_wrapper_link_switch === 'yes'` and a non-empty URL. If either is missing, `before_render` short-circuits silently — no console errors, no PHP notices, no rendered output.

## Architecture Decisions

### Two implementations of the click target

- **Context:** A real `<a>` wrap is the cleanest semantic; but Elementor widgets routinely contain their own anchors (Heading link, Button, etc.), and HTML forbids nested anchors.
- **Decision:** Default to the traditional `<a>` injection but offer "Disable Traditional Link" to switch to a JS-synthesised click handler.
- **Alternatives rejected:** Always wrap in `<a>` (produces invalid HTML for many widgets); always use `data-*` + JS click (loses dynamic-tag support and custom-attribute support); detect nested anchors at render time and auto-switch (fragile — only knows about EA widgets, can't see third-party content inside).
- **Consequences:** Two render paths means twice the JS surface (the `wrapper-link.js` handles both branches) and the user must decide which to use per element. The warning row in the editor surfaces the trade-off at the point of choice.

### `before_render` at priority 100

- **Context:** Other render-time mutations (Hover Effect, render-attribute manipulations from widgets themselves) should compose with Wrapper Link, not race against it.
- **Decision:** Hook at priority 100 — well after the default of 10 — to run after other mutations.
- **Alternatives rejected:** Default priority 10 (would compose unpredictably with Hover Effect which also uses 100); a much higher priority like 1000 (no benefit, future-proofing).
- **Consequences:** Anything that wants to mutate `eael_wrapper_link` settings before this extension reads them must hook at priority < 100.

### Standalone WPML hook for containers

- **Context:** Elementor's built-in WPML integration translates URLs on widgets and sections automatically. Containers fell through that net at the time the extension was extended for WPML support.
- **Decision:** Add a dedicated callback specifically for `elType === 'container'`, hooked at default priority 10 on the same `elementor/frontend/before_render` action. The callback short-circuits for non-containers.
- **Alternatives rejected:** Patch Elementor's WPML integration (out of scope); rewrite all URLs ourselves regardless of element type (could double-translate widget URLs that Elementor already handled).
- **Consequences:** The behaviour is opaque — there's no editor surface explaining "WPML rewrite applies to containers only". Documented here for completeness.

### `Controls_Manager::URL` for the link field

- **Context:** Need URL + new-tab + nofollow + custom attributes + dynamic tags. Elementor's `URL` control type bundles all of these into one widget.
- **Decision:** Use `URL` with `'dynamic' => [ 'active' => true ]`.
- **Alternatives rejected:** Plain `TEXT` (loses everything); custom control type (over-engineering).
- **Consequences:** Dynamic tags only work on the traditional path because they're resolved by `add_link_attributes`, which the non-traditional path doesn't call. The editor warning communicates this.

## Known Limitations

- **Nested anchor risk on traditional path.** Widgets containing their own `<a>` produce invalid HTML when wrapped traditionally. Validation warnings in tools like the W3C validator; SEO impact is minor in practice (browsers handle it), but accessibility-wise the inner anchor is unreachable by keyboard tab order when stacked under the absolutely-positioned wrapper anchor.
- **Lost dynamic tags on non-traditional path.** A user who wants both dynamic tags and a non-nested anchor can't get both from this extension alone.
- **JS-dependency for non-traditional path.** Visitors with JS disabled cannot click an element configured for the non-traditional path. The traditional path's `<a>` is a real anchor and works without JS.
- **No middle-click / Ctrl+click support on non-traditional path.** The JS synthesises a click via `anchor.click()`, which doesn't honour modifier keys for "open in new tab". The traditional path, being a real `<a>`, does.
- **No keyboard focus on non-traditional path.** The wrapper isn't a focusable element. Users navigating by keyboard can't reach the synthetic link.
- **WPML rewrite skips widgets/sections/columns.** The dedicated WPML callback only handles containers. The other element types rely on Elementor's own WPML integration to translate the URL.
- **No `target` attribute on synthesised anchor beyond `_blank`/`_self`.** Iframe `_parent` / `_top` aren't representable.
- **`ea.sanitizeURL()` dependency.** The non-traditional path's click handler calls `ea.sanitizeURL()` from EA's general JS bundle. If that bundle fails to load, clicks throw a console error and do nothing.

## Recent Significant Changes

No tracked changes yet. Future entries here when:

- A new element type joins the wired hooks (e.g. Elementor adds a new wrapper element)
- The non-traditional click handler gains modifier-key support
- The WPML callback extends to non-container element types
- The traditional path adapts to avoid the nested-anchor problem (e.g. detect and skip)

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — Wrapper Link is used as the worked example in the subsystem doc.
- **Architecture:** [`../architecture/asset-loading.md`](../architecture/asset-loading.md) — `view`-context JS enqueueing.
- **Sibling extension docs:** [`./special-hover-effect.md`](special-hover-effect.md) — also hooks `elementor/frontend/before_render` at priority 100; the two extensions compose at render time.
- **Source JS:** [`../../src/js/view/wrapper-link.js`](../../src/js/view/wrapper-link.js)
- **Public docs:** <https://essential-addons.com/docs/wrapper-link-elementor/>
