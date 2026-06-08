# Fancy Text Widget

> Displays a static prefix, an animated rotating string ("fancy text"), and an optional suffix. Animation can be a typing effect or one of several fade / zoom / bounce / swing transitions.

**Class file:** [`includes/Elements/Fancy_Text.php`](../../includes/Elements/Fancy_Text.php)
**Slug:** `fancy-text` (widget id `eael-fancy-text`)
**Public docs:** <https://essential-addons.com/elementor/docs/fancy-text/>
**Pro-shared:** ✅ Yes — Pro injects `style-2` via the `fancy_text_style_types` filter and provides additional gradient / background treatments.

---

## Overview

Fancy Text is a headline-style widget that mixes a fixed prefix, a rotating word or phrase, and an optional suffix. The rotating piece animates either as a character-by-character typing effect or as a CSS-keyframe transition (fade in any direction, zoom, bounce, swing). Used to give marketing-style copy a sense of motion without distracting from the message.

Most pages drop it into a hero section or service introduction. The widget reads its rotating strings from a Repeater control and exposes per-element styling so the prefix, the fancy string itself, and the suffix can each have their own typography, color, and spacing.

## Features

- Prefix and suffix text with Elementor dynamic-tag support
- Multiple rotating strings via a Repeater
- Nine transition styles: typing, fadeIn, fadeInUp, fadeInDown, fadeInLeft, fadeInRight, zoomIn, bounceIn, swing
- Animation start choice: on page load or when scrolled into view
- Configurable typing speed, delay between strings, loop on/off, cursor on/off
- Per-element styling for prefix, fancy strings, and suffix (typography, color, padding, margin, border, radius)
- Solid or gradient background for the fancy strings (Style 1 only in Lite)
- Responsive alignment (separate values per breakpoint)
- `<noscript>` fallback that lists strings comma-separated for SEO and no-JS clients

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Style 1 (default) | ✅ | ✅ |
| Style 2 (extra background / gradient treatment) | ❌ — falls back to Style 1 | ✅ |
| All nine transition types | ✅ | ✅ |
| Solid / gradient color picker on fancy strings | ✅ (Style 1) | ✅ (both styles) |
| Inject custom style options via filter | ❌ | ✅ via `fancy_text_style_types` |
| Pro upsell section in panel | shown | hidden |

When the Pro plugin is not active, selecting Style 2 in the panel still renders Style 1 — see Architecture Decisions for the rationale.

## Use Cases

- Hero section with rotating value props ("We build [websites | apps | landing pages]")
- Marketing headers that emphasise variety of services
- Tagline copy below a logo or page title
- Testimonial or quote intros where one verb / noun rotates

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Fancy_Text.php`](../../includes/Elements/Fancy_Text.php) | PHP widget class — controls registration, render, helper |
| [`src/css/view/fancy-text.scss`](../../src/css/view/fancy-text.scss) | Source styles, RTL block, animation keyframes |
| [`src/js/view/fancy-text.js`](../../src/js/view/fancy-text.js) | Frontend logic — Typed.js + Morphext orchestration |
| [`config.php`](../../config.php) entry `'fancy-text'` | Asset_Builder dependency declaration |
| `assets/front-end/css/view/fancy-text.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/fancy-text.min.js` | Built output (do not edit) |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor: DOMPurify — JS-side string sanitization |
| `assets/front-end/js/lib-view/typed/typed.min.js` | Vendor: Typed.js — typing transition |
| `assets/front-end/js/lib-view/morphext/morphext.min.js` | Vendor: Morphext — non-typing transitions |

## Architecture

- **Two animation engines (Typed.js + Morphext)** — Typed.js drives the typing transition because it gives precise per-character timing with cursor support. Morphext drives every other transition because it's CSS-keyframe-based and handles fade / zoom / swing cleanly. Combining them in one library was rejected; each does its job better than a single-library solution would.
- **DOMPurify on the JS side** — strings flow PHP → `data-fancy-text` attribute → JS split → DOM insertion. PHP-side `eael_wp_kses` sanitizes once. DOMPurify re-sanitizes before DOM insertion as defense-in-depth, since attribute-to-JS-to-DOM is a classic XSS vector if upstream sanitization is ever bypassed.
- **Force-fallback in `render()` when Pro is disabled** — if the user picked `style-2` in the panel but Pro is not active, [`render()`](../../includes/Elements/Fancy_Text.php#L628) resets the setting to `style-1` before output. The user sees a working Lite render rather than a broken Style 2.
- **`elementStatusCheck` guard in JS init** — wraps the `addAction` registration so re-fired `elementor/frontend/init` events (popups, SPA-style nav) don't double-register the handler and double-init Typed/Morphext on the same widget instance.
- **`<noscript>` fallback** — for SEO and no-JS clients, the strings are also rendered comma-separated inside a `<noscript>` block so search engines and screen-readers see all rotating values.

## Render Output

The widget produces the following DOM structure on the front end (defaults shown for clarity):

```html
<div class="eael-fancy-text-container style-1"
     data-fancy-text-id="<elementor-element-id>"
     data-fancy-text="First string|Second string|Third string"
     data-fancy-text-transition-type="typing"
     data-fancy-text-speed="50"
     data-fancy-text-delay="2500"
     data-fancy-text-cursor="yes"
     data-fancy-text-loop="yes"
     data-fancy-text-action="page_load">
  <span class="eael-fancy-text-prefix">This is the </span>
  <span id="eael-fancy-text-<id>" class="eael-fancy-text-strings solid-color">
    <noscript>First string, Second string, Third string</noscript>
  </span>
  <span class="eael-fancy-text-suffix"> of the sentence.</span>
</div>
<div class="clearfix"></div>
```

Notes:

- `.eael-fancy-text-container` is the styling root and the JS scope target. JS reads every `data-*` attribute from this element.
- `id="eael-fancy-text-<id>"` on the strings span is what Typed.js / Morphext target; the id mirrors `data-fancy-text-id` to ensure uniqueness across multiple instances on a page.
- The trailing `<div class="clearfix"></div>` is a legacy artifact (no floats in the markup any more) — see Known Limitations.
- Prefix and suffix spans are conditional — `<span class="eael-fancy-text-prefix">` only appears when the prefix setting is non-empty; same for suffix.

## Controls Reference

Comprehensive table of meaningful controls. Source [`register_controls()`](../../includes/Elements/Fancy_Text.php#L72) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_fancy_text_prefix` | TEXT | `"This is the "` | Content → Fancy Text | `.eael-fancy-text-prefix` text |
| `eael_fancy_text_strings` | REPEATER | 3 default items | Content → Fancy Text | `data-fancy-text` attribute (pipe-joined) |
| `eael_fancy_text_suffix` | TEXT | `" of the sentence."` | Content → Fancy Text | `.eael-fancy-text-suffix` text |
| `eael_fancy_text_style` | SELECT | `style-1` | Content → Settings | Container class + render fallback |
| `eael_fancy_text_alignment` | CHOOSE (responsive) | `center` | Content → Settings | `.eael-fancy-text-container { text-align }` |
| `eael_fancy_text_transition_type` | SELECT | `typing` | Content → Settings | `data-fancy-text-transition-type`; JS branch |
| `eael_fancy_text_animation_start_on` | SELECT | `page_load` | Content → Settings | `data-fancy-text-action`; JS branch |
| `eael_fancy_text_speed` | NUMBER | `50` | Content → Settings | `data-fancy-text-speed`; Typed.js `typeSpeed` |
| `eael_fancy_text_delay` | NUMBER | `2500` | Content → Settings | `data-fancy-text-delay`; Typed `backDelay` / Morphext `speed` |
| `eael_fancy_text_loop` | SWITCHER | `yes` | Content → Settings | `data-fancy-text-loop`; both engines |
| `eael_fancy_text_cursor` | SWITCHER | `yes` | Content → Settings | `data-fancy-text-cursor`; cursor visibility |
| `eael_fancy_text_color_selector` | CHOOSE | `solid-color` | Style → Fancy Text Styles | Picks solid vs gradient pathway |
| `eael_fancy_text_strings_background_color` | COLOR | empty | Style → Fancy Text Styles | `.eael-fancy-text-strings { background }` |
| `eael_fancy_text_color_gradient` | Group_Control_Background (gradient) | `#062ACA → #9401D9` | Style → Fancy Text Styles | `.eael-fancy-text-strings` gradient |
| `eael_fancy_text_strings_color` | COLOR | empty | Style → Fancy Text Styles | `.eael-fancy-text-strings { color }` |
| `eael_fancy_text_cursor_color` | COLOR | empty | Style → Fancy Text Styles | `.eael-fancy-text-strings::after { color }` |
| `eael_fancy_text_strings_padding` / `_margin` | DIMENSIONS (responsive) | empty | Style → Fancy Text Styles | `.eael-fancy-text-strings` spacing |
| `eael_fancy_text_strings_border_radius` | SLIDER | empty | Style → Fancy Text Styles | `.eael-fancy-text-strings { border-radius }` |
| `eael_fancy_text_prefix_color` / `_suffix_color` | COLOR | empty | Style → Prefix/Suffix Text Styles | Respective span color |

Plus three Group_Control_Typography blocks (one each for prefix, fancy strings, suffix) and `Group_Control_Border` on the strings.

## Conditional Dependencies

A control hidden by a condition still saves its value. This map answers "why doesn't option X show in my panel?" without reading the source.

```text
eael_fancy_text_style_pro_alert        → visible when style == 'style-2'
eael_fancy_text_speed                  → visible when transition_type == 'typing'
eael_fancy_text_cursor                 → visible when transition_type == 'typing'
eael_fancy_text_cursor_color           → visible when cursor == 'yes'
eael_fancy_text_color_selector         → visible when style == 'style-1'
eael_fancy_text_strings_background_color → visible when:
                                           color_selector == 'solid-color'
                                           OR style == 'style-2'
eael_fancy_text_color_gradient         → visible when:
                                           color_selector == 'gradient-color'
                                           AND style == 'style-1'
eael_fancy_text_strings_color          → visible when style in ['style-1', 'style-2']
eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

## Behavior Flow

End-to-end sequence from "user drops widget on canvas" to "user sees animated text on a published page".

1. User drops the widget on the Elementor canvas → Elementor calls `register_controls()` → control panel appears.
2. User configures settings → Elementor saves the settings into post meta.
3. Editor preview iframe re-renders by calling `render()` for live preview.
4. On page publish, the front-end HTML is rendered server-side via the same `render()`.
5. `render()` builds settings via [`get_settings_for_display()`](../../includes/Elements/Fancy_Text.php#L626), runs `fancy_text()` to join repeater items with `|`, applies Pro fallback if Pro is disabled, then writes the container's `data-*` attributes via `add_render_attribute()`.
6. Browser receives HTML. Elementor's `frontend/init` event fires.
7. `fancy-text.js` runs: `addAction('frontend/element_ready/eael-fancy-text.default', FancyText)` registers the handler (guarded by `eael.elementStatusCheck`).
8. For each `.eael-fancy-text-container` Elementor element-ready, the `FancyText` handler is invoked with `$scope`.
9. Handler reads `data-*` attributes, runs `DOMPurify.sanitize()` on the pipe-separated strings, splits into an array.
10. Branch on transition type: `typing` → `initTyped()` (Typed.js), else → `initMorphext()` (Morphext).
11. Branch on action: `page_load` → init immediately, `view_port` → bind a window scroll listener; init when `$fancyText.isInViewport(1)` returns true.
12. After 500ms (or 800ms in editor), `showFancyText()` flips `.eael-fancy-text-strings` from `display: none` to `display: inline-block`.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-fancy-text.default', FancyText)`
- **Guard:** `if (eael.elementStatusCheck('eaelFancyTextLoad')) return false;` — blocks re-registration on re-fired `elementor/frontend/init`
- **Reads on init:** all `data-*` attributes from `.eael-fancy-text-container` (id, strings, transition type, speed, delay, cursor, loop, action)
- **Sanitization:** `DOMPurify.sanitize(rawString || "")` then `.split("|")` → array of safe strings
- **Engine branch:**
  - `transitionType === 'typing'` → `new Typed("#eael-fancy-text-<id>", { strings, typeSpeed, backDelay, showCursor, loop })`
  - else → `$("#eael-fancy-text-<id>").Morphext({ animation, separator: ", ", speed, complete })`
- **Action branch:**
  - `page_load` → init immediately
  - `view_port` → `$(window).on('scroll', ...)` with `isInViewport(1)` check; once triggered, adds `.eael-animated` class so it doesn't re-init
- **Visibility delay:** `.eael-fancy-text-strings` starts as `display: none` (CSS); `setTimeout(showFancyText, 500)` flips to `inline-block`. In editor (`isEditMode`), the timeout is 800 ms to accommodate slower init.
- **Runtime state:** the Typed / Morphext instance lives inside the closure; no global state apart from the `eaelFancyTextLoad` flag.

## Asset Dependencies

Asset_Builder enqueues these only when at least one `Fancy_Text` widget is detected on the page. See [`asset-pipeline`](../../.claude/rules/asset-pipeline.md) for detection caveats (templates, popups, shortcodes).

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `fancy-text.min.css` | self (built from `src/css/view/fancy-text.scss`) | Always when widget present |
| `e-animations` | Elementor handle (declared in `get_style_depends()`) | Always — Elementor provides; do not bundle |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `purify.min.js` (DOMPurify) | `assets/front-end/js/lib-view/dom-purify/` | Sanitize `data-fancy-text` payload before DOM insertion | Always |
| `morphext.min.js` (Morphext) | `assets/front-end/js/lib-view/morphext/` | Drives all non-typing transitions | Always (load order before self) |
| `typed.min.js` (Typed.js) | `assets/front-end/js/lib-view/typed/` | Drives typing transition | Always (load order before self) |
| `fancy-text.min.js` | self (built from `src/js/view/fancy-text.js`) | Widget logic | Always when widget present |

All three vendor libs load unconditionally — a known performance trade-off (see Known Limitations).

## Hooks & Filters

The Lite-side public contract that Pro and any third-party developer can extend.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `fancy_text_style_types` ⚠️ **un-prefixed legacy** | filter | `array $defaults` with keys `styles` and `conditions` | Inject additional style options into the Style Type select; Pro adds `style-2` |
| `eael/pro_enabled` | filter | `bool $enabled` | Render-time fallback gate — Lite forces `style-1` when this returns false |

⚠️ The `fancy_text_style_types` filter has no `eael/` prefix and is silenced with `phpcs:ignore` ([line 159](../../includes/Elements/Fancy_Text.php#L159)). It is part of Pro's public contract; **do not rename or remove without a coordinated Pro PR + a dual-emit migration over one release cycle** (emit both `eael/fancy_text_style_types` and the legacy name, then deprecate).

## Customization Recipes

Copy-paste-ready snippets that solve common extension needs.

### Recipe 1 — Inject a custom style option via the filter

```php
add_filter( 'fancy_text_style_types', function ( $options ) {
    $options['styles']['style-3']    = 'Style 3 (My Custom)';
    $options['conditions'][]          = 'style-3';   // suppress price-alert heading for this style
    return $options;
} );
```

To make `style-3` actually render differently, also add CSS targeting `.eael-fancy-text-container.style-3`. The widget's `render()` writes the chosen style key as a class on the container, so theme CSS can hook on it.

### Recipe 2 — Override cursor color site-wide via theme CSS

```scss
.eael-fancy-text-strings::after {
    color: #ff5722;
    font-weight: 700;
}
```

This wins over the per-widget cursor color control because the in-widget rule is keyed on `[data-fancy-text-cursor="yes"]` only — adding `!important` is unnecessary unless another rule is more specific.

### Recipe 3 — Switch to fadeIn on small screens (override transition type at runtime)

```javascript
jQuery( window ).on( 'elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/eael-fancy-text.default',
        function ( $scope ) {
            if ( window.innerWidth <= 767 ) {
                $scope.find( '.eael-fancy-text-container' )
                      .attr( 'data-fancy-text-transition-type', 'fadeIn' );
            }
        },
        5  // priority lower than EA's default; runs first
    );
} );
```

The handler must run **before** `FancyText` initialises Typed / Morphext, so set the priority lower than EA's default.

## Common Issues

### Animation does not start at all

- **Likely cause:** vendor libs failed to load, or `eael.elementStatusCheck` blocked init, or repeater is empty
- **Diagnose:** open the browser console for JS errors; in Network tab confirm `purify.min.js`, `typed.min.js`, `morphext.min.js`, `fancy-text.min.js` returned 200; inspect the widget — is `.eael-fancy-text-strings` element present?
- **Fix:** if assets missing, run `npm run build` and clear Elementor's CSS cache (Elementor → Tools → Regenerate CSS & Data); if elementStatusCheck blocked init after a navigation event, hard-refresh

### "Style 2" is selected but the page renders Style 1

- **Likely cause:** Pro plugin is not active or `eael/pro_enabled` filter returns false
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` — should be `true` with Pro
- **Fix:** activate the Pro plugin; this fallback is intentional in Lite — the widget always degrades gracefully when Pro is disabled

### Cursor color picker has no effect on the rendered widget

- **Likely cause:** the cursor `::after` pseudo-element is only rendered when `data-fancy-text-cursor="yes"`, and is only meaningful in `typing` transition
- **Diagnose:** check the current transition type in the Settings panel; check if "Display Type Cursor" is on
- **Fix:** switch transition to "Typing", or enable the cursor switcher; otherwise the cursor doesn't render so its color setting has no target

### `view_port` action never starts the animation on above-the-fold widgets

- **Likely cause:** `view_port` action binds a `window scroll` listener and calls `$fancyText.isInViewport(1)`. If the widget is already visible on initial paint and the user never scrolls, the listener is never invoked.
- **Diagnose:** scroll the page once — does the animation start?
- **Fix:** for above-fold widgets, switch the action to "On Page Load"; this is a known limitation of the current view-port implementation

### Multiple Fancy Text widgets on the same page conflict

- **Likely cause:** very rarely, two widgets get the same Elementor element id (e.g. when imported templates collide)
- **Diagnose:** inspect each widget's `data-fancy-text-id` — should be unique per instance
- **Fix:** Elementor normally guarantees unique ids; if you reproduce duplicates, regenerate the page or file an issue with the import source

### Special characters (`<`, `>`, `&`) appear escaped or stripped in the output

- **Likely cause:** `eael_wp_kses` strips disallowed tags; `html_entity_decode` runs before sanitisation in [`fancy_text()`](../../includes/Elements/Fancy_Text.php#L613) so encoded HTML can leak through if it represents an allowed tag, but disallowed tags are stripped
- **Diagnose:** inspect the `data-fancy-text` attribute in the DOM to see what reached the browser
- **Fix:** if you need a tag that is being stripped, extend the `eael_allowed_tags` filter; do not edit `eael_wp_kses` directly

### Translations on the Pro upsell description don't apply

- **Likely cause:** the description string at [line 319](../../includes/Elements/Fancy_Text.php#L319) is hardcoded English and not wrapped in `__()`
- **Diagnose:** grep the file for the literal English text; confirm it has no translation wrapper
- **Fix:** wrap with `sprintf( __( '...', 'essential-addons-for-elementor-lite' ), $open_link, $close_link )`. See the `widget-review` skill for the full audit.

## Testing Checklist

Manual verification after any change to this widget. Walk through before opening a PR.

- [ ] Drop at default config — typing animation rotates through three default strings; no PHP notices in `wp-content/debug.log`
- [ ] Switch transition to `fadeIn` — Morphext takes over; cursor disappears
- [ ] Switch back to `typing` — Typed.js takes over; cursor reappears (if cursor switch is on)
- [ ] Mobile responsive switch — alignment differs per breakpoint as expected
- [ ] Disable the Pro plugin and select Style 2 — render falls back to Style 1
- [ ] Two widgets on one page — both animate independently; no shared state
- [ ] `view_port` action — widget below the fold; scroll triggers animation; widget above the fold confirms the known above-fold limitation
- [ ] Empty prefix or suffix — those spans are absent from the rendered HTML
- [ ] Single string in repeater — animation rotates through one item without breaking
- [ ] Special characters (`<script>`) in a string — output is sanitized; no XSS in editor or frontend
- [ ] RTL site — `.eael-fancy-text-container` keeps `direction: ltr` (per the `.rtl` block in SCSS)
- [ ] After source changes, run `npm run build` and visually verify the change on `http://localhost:8888`

## Architecture Decisions

### Two animation engines (Typed.js + Morphext) instead of a single library

- **Context:** the widget needs both a precise per-character typing effect and a set of CSS-keyframe-driven transitions (fade / zoom / bounce / swing).
- **Decision:** Typed.js for typing, Morphext for everything else.
- **Alternatives rejected:** single-library options (e.g. Animate.css standalone) didn't give typing precision; building a bespoke implementation would duplicate well-tested vendor code.
- **Consequences:** bundle size doubles for any widget instance, since both libs load even when only one is used at runtime. Trade-off accepted historically. Candidate for `Asset_Builder` context-flag refactor.

### DOMPurify on the JS side, on top of PHP-side `eael_wp_kses`

- **Context:** strings flow PHP → `data-fancy-text` attribute → JS read → DOM insertion. Attribute-to-DOM-via-JS is a known XSS class.
- **Decision:** PHP sanitizes once (`eael_wp_kses`), JS re-sanitizes via DOMPurify before insertion.
- **Alternatives rejected:** trust PHP alone; that exposes the widget to a future regression in the PHP sanitizer.
- **Consequences:** an extra ~25 KB DOMPurify load per page using the widget; negligible runtime cost.

### Force-fallback to Style 1 in `render()` when Pro is disabled

- **Context:** users may select "Style 2 (Pro)" in the panel even with the Pro plugin uninstalled.
- **Decision:** `render()` resets `eael_fancy_text_style` to `style-1` when `apply_filters( 'eael/pro_enabled', false )` returns false.
- **Alternatives rejected:** skip rendering (frustrates the user); render Style 2 unstyled (visual breakage).
- **Consequences:** Lite users see a working render even with the Pro option chosen; Pro overrides the filter when active and the chosen style passes through unchanged.

### All three vendor libraries load unconditionally

- **Context:** at runtime only one of Typed.js or Morphext is needed (DOMPurify is always needed).
- **Decision:** load all three on every page that has the widget.
- **Alternatives rejected:** split into context-aware `config.php` entries based on settings (complicates `Asset_Builder`); dynamic-import the right engine on init (browser-support concerns historically; less of an issue today).
- **Consequences:** ~30 KB unused per page; noticeable on the Lighthouse score budget for low-spec devices. Tracked as a P3 perf candidate.

## Known Limitations

- **All three vendor libraries load unconditionally** — DOMPurify is always needed, but Typed.js and Morphext are mutually exclusive at runtime. Conditional loading via `Asset_Builder` context flags would shave ~30 KB on most pages. P3 perf candidate.
- **`'fancy'` transition branch in `render()`** ([lines 646-663](../../includes/Elements/Fancy_Text.php#L646)) — the widget tests `transition_type == 'fancy'`, but the Lite-side select never registers `'fancy'` as an option. The branch is either dead code or a Pro-injected option Lite cannot exercise. Verify against Pro before removing.
- **Cursor color control visible without context** — the control hides only behind `cursor == 'yes'`, but the cursor itself only renders for `typing`. With a non-typing transition the cursor is invisible regardless of color. Minor UX nit.
- **Trailing `<div class="clearfix"></div>`** ([line 670](../../includes/Elements/Fancy_Text.php#L670)) sits outside `.eael-fancy-text-container`, violating the single-root rule. Legacy artifact — likely safe to remove (no floats in the markup), but check theme CSS targeting `.clearfix` first.
- **Root class is `.eael-fancy-text-container`** rather than the conventional `.eael-fancy-text`. Themes may target the `-container` form, so a rename is breaking. Acceptable workaround: add `eael-fancy-text` as a sibling class without dropping the existing one.
- **`<noscript>` fallback ordering** lists strings comma-separated in repeater order; if user expectation is pipe-separated or another order, that has to be lived with.
- **Pro upsell description not translated** ([line 319](../../includes/Elements/Fancy_Text.php#L319)) — hardcoded English; needs `__()` wrapping.
- **`view_port` action above-the-fold** — animation never triggers if the widget is visible on initial paint and the user doesn't scroll.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
