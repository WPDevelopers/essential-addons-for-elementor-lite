# Fancy Text Widget

> Displays a static prefix, an animated rotating string ("fancy text"), and an optional suffix. Animation can be a typing effect or one of several fade/zoom/swing transitions.

**Class file:** [`includes/Elements/Fancy_Text.php`](../../includes/Elements/Fancy_Text.php)
**Slug:** `fancy-text` (widget id `eael-fancy-text`)
**Public docs:** https://essential-addons.com/elementor/docs/fancy-text/
**Pro-shared:** ✅ Yes — Pro injects `style-2` via the `fancy_text_style_types` filter and provides additional gradient/background treatments.

---

## Architecture Notes

- **Two animation engines:** [Typed.js](../../assets/front-end/js/lib-view/typed/typed.min.js) drives the `typing` transition; [Morphext](../../assets/front-end/js/lib-view/morphext/morphext.min.js) drives all other transitions (fade/zoom/bounce/swing). They were chosen separately because Typed.js gives precise per-character timing and Morphext gives CSS-keyframe-based transitions — neither covers both well.
- **DOMPurify on the JS side** (in addition to PHP-side `wp_kses`) is defense-in-depth: the `data-fancy-text` attribute carries pipe-separated user strings that get split and re-rendered in the DOM at runtime, so the JS sanitizes again before insertion.
- **Pro-style fallback in `render()`** — if Pro is disabled, [`render()`](../../includes/Elements/Fancy_Text.php#L628) force-resets `eael_fancy_text_style` to `style-1`. This means a user who selects `style-2 (Pro)` in Lite still gets a sensible Lite render rather than a broken one.
- **`elementStatusCheck` guard** — the JS init [registers a per-widget status flag (`eaelFancyTextLoad`)](../../src/js/view/fancy-text.js#L80) so a re-fired `elementor/frontend/init` (popups, SPA-style nav) doesn't double-init Typed/Morphext.

## Controls Summary

- **Content tab — "Fancy Text" section:** Prefix Text, Fancy Text Strings (Repeater), Suffix Text
- **Content tab — "Fancy Text Settings" section:** Style Type (`style-1` Lite / `style-2` Pro), Alignment (responsive), Animation Type, Animation Starts (page_load / view_port), Speed, Delay, Loop, Display Cursor
- **Style tab — "Prefix Text Styles":** color, typography
- **Style tab — "Fancy Text Styles":** background type (solid/gradient), background color, gradient, color, cursor color, padding, margin, border, border radius, typography
- **Style tab — "Suffix Text Styles":** color, typography
- **Pro upsell section:** present (only when Pro disabled)

Full controls list is in [`register_controls()`](../../includes/Elements/Fancy_Text.php#L72). Source is the current truth — this section just orients the reader.

## Frontend Dependencies

| Library | Purpose | Loaded |
|---------|---------|--------|
| [DOMPurify](../../assets/front-end/js/lib-view/dom-purify/purify.min.js) | Sanitize the `data-fancy-text` payload before splitting and inserting strings into the DOM | Always |
| [Typed.js](../../assets/front-end/js/lib-view/typed/typed.min.js) | Typing-effect transition (`transitionType == 'typing'`) | Always (P3 perf nit — see Limitations) |
| [Morphext](../../assets/front-end/js/lib-view/morphext/morphext.min.js) | Fade / zoom / bounce / swing transitions (any `transitionType != 'typing'`) | Always (P3 perf nit — see Limitations) |

`e-animations` is declared as a CSS handle dep ([`Fancy_Text.php:54-58`](../../includes/Elements/Fancy_Text.php#L54)) — Elementor provides this; do not bundle.

## Pro Extension Points

| Hook | Type | Pro use |
|------|------|---------|
| `fancy_text_style_types` ⚠️ **un-prefixed legacy** | filter | Pro injects `style-2` into the Style Type select options + adjusts the conditions array |
| `eael/pro_enabled` | filter | Render-time fallback — Lite forces `style-1` if Pro is not enabled |

⚠️ The `fancy_text_style_types` filter has no `eael/` prefix and is silenced with a `phpcs:ignore` ([line 159](../../includes/Elements/Fancy_Text.php#L159)). It is part of Pro's public contract; **do not rename or remove without a coordinated Pro PR**. A future migration should dual-emit `eael/fancy_text_style_types` and the legacy name for one release cycle, then deprecate.

## Known Limitations

- **All three vendor libraries load unconditionally** — DOMPurify is always needed, but Typed.js and Morphext are mutually exclusive at runtime. Conditional loading via `Asset_Builder` context flags would shave ~30 KB on most pages. Tracked as a P3 perf candidate.
- **`'fancy'` transition branch** ([`Fancy_Text.php:646-663`](../../includes/Elements/Fancy_Text.php#L646)) — The widget's render contains `if ($settings['eael_fancy_text_transition_type'] == 'fancy')`, but the Lite-side select never registers `'fancy'` as an option. The branch is either dead code or a Pro-injected option that Lite cannot exercise. Verify against Pro before removing.
- **Cursor color control** is conditional on `eael_fancy_text_cursor == 'yes'` only — if the user picks any non-typing transition, the cursor itself is hidden but the cursor color control still appears in the panel. Minor UX nit.
- **Trailing `<div class="clearfix"></div>`** ([`Fancy_Text.php:670`](../../includes/Elements/Fancy_Text.php#L670)) sits outside the main `.eael-fancy-text-container` root. Violates the "single root" widget-development rule. Legacy artifact — safe to remove in a coordinated change (themes targeting `.clearfix` here is unlikely but check).
- **Root class is `.eael-fancy-text-container`** rather than the conventional `.eael-fancy-text`. Themes may target the `-container` form, so renaming is breaking. Acceptable workaround: add `eael-fancy-text` as a sibling class without removing `eael-fancy-text-container`.
- **`<noscript>` fallback** lists strings comma-separated in repeater order; if the user expected pipe-separated or another order, they have to live with comma. Documented to avoid surprise reports.

## Related Widgets

- **Content_Ticker** — when the requirement is a horizontally scrolling rotation (RSS/news ticker style), prefer that over Fancy Text
- **Adv_Tabs** — also uses the Repeater pattern for content rotation, but with explicit user clicks rather than timed transitions
- **Dual_Color_Header** — when the user wants a multi-color heading without animation, that's a simpler choice

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
