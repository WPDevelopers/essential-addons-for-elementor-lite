# Creative Button — Atomic (V4) Widget

> Atomic-widget (Elementor V4 editor) variant of the EA Creative Button. Text, secondary "hover-reveal" text, link, and the free CSS hover effects (Default, Winona, Ujarak, Wayra, Tamaya, Rayen). Twig-rendered, no per-instance JS. Self-registered behind Elementor's Atomic Widgets experiment — never touches the classic widget.

**Class file:** [`includes/Elements/Atomic/Creative_Button/Creative_Button.php`](../../includes/Elements/Atomic/Creative_Button/Creative_Button.php)
**Element type (widget id):** `eael-creative-button-atomic`
**Registered by:** [`includes/Classes/Atomic_Widgets_Loader.php`](../../includes/Classes/Atomic_Widgets_Loader.php) — **not** `config.php`
**Companion:** the classic full-feature widget — see [`creative-btn.md`](creative-btn.md)
**Pro-shared:** ❌ No — Pro effects are intentionally not supported in the atomic variant.

---

## Overview

This is the V4-editor (Atomic Widgets) port of Creative Button. It extends `Atomic_Widget_Base` and renders through a Twig template instead of a PHP `render()` method. The user picks a hover effect, sets primary text, optional secondary (hover) text, and an optional link. All styling beyond the effect mechanics is owned by the V4 **Style panel** — the widget deliberately ships almost no base styles (see Architecture).

Unlike the classic widget, the atomic variant has **no icon, no gradient/Liquid-Glass background, and no Pro effects**. These are omitted because V4 lacks the FontAwesome icon-library picker (and its SVG control has no clear/remove action). The classic `eael-creative-button` remains the full-feature implementation.

## Why a separate widget (not a port)

The two widgets coexist on the same site and even the same page. The atomic variant:

- Registers only when Elementor's `e_atomic_elements` experiment is active — `Atomic_Widgets_Loader::is_atomic_active()` gates everything, so nothing here loads or fatals when the experiment is off or the atomic base classes are absent.
- Carries a marker class `eael-creative-button--atomic` (added by the Twig) so its CSS overrides never bleed into classic creative buttons.
- Uses its own style handles (`eael-cb-atomic-base` → `eael-cb-atomic`) so it does not depend on the classic widget being present on the page.

## Features

- Primary text (inline-editable, `html-v3` prop)
- Secondary "hover-reveal" text (`data-text` attribute + cross-fade / Tamaya slide)
- Six free hover effects: Default, Winona, Ujarak, Wayra, Tamaya, Rayen
- Link (renders `<button>` until a link is set, then `<a>` — see Render Output)
- Full V4 Style panel ownership of colour, typography, spacing, border, etc.
- CSS ID control (`_cssid`)

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Atomic/Creative_Button/Creative_Button.php`](../../includes/Elements/Atomic/Creative_Button/Creative_Button.php) | Atomic widget class — prop schema, atomic controls, base styles, effect list, `render_markdown()` |
| [`includes/Classes/Atomic_Widgets_Loader.php`](../../includes/Classes/Atomic_Widgets_Loader.php) | Registers the atomic widget + its styles; gates on the Atomic experiment |
| [`includes/Elements/Atomic/Creative_Button/atomic-creative-button.html.twig`](../../includes/Elements/Atomic/Creative_Button/atomic-creative-button.html.twig) | Twig template — class assembly, `<button>`/`<a>` tag choice, Tamaya markup |
| [`src/css/view/creative-btn.scss`](../../src/css/view/creative-btn.scss) | Shared effect mechanics (keyframes/transitions) — also used by the classic widget |
| [`src/css/view/creative-btn-atomic.scss`](../../src/css/view/creative-btn-atomic.scss) | Atomic-only reveal colours + `<button>` UA reset + canonical Tamaya, all scoped under `.eael-creative-button--atomic` |
| `assets/front-end/css/view/creative-btn.min.css` | Built shared mechanics (do not edit) |
| `assets/front-end/css/view/creative-btn-atomic.min.css` | Built atomic layer (do not edit) |
| [`tests/e2e/specs/creative-btn-atomic.spec.ts`](../../tests/e2e/specs/creative-btn-atomic.spec.ts) | Playwright E2E spec |
| [`tests/e2e/templates/creative-btn-atomic.json`](../../tests/e2e/templates/creative-btn-atomic.json) | Seed template (V4 prop format) |

## Architecture

- **Effect = CSS class on the root.** The `effect` prop maps directly to a class like `eael-creative-button--winona`; SCSS owns the animation. Same model as the classic widget.
- **Twig rendering, not `render()`.** Uses `Has_Template`; `get_templates()` maps `eael/elements/atomic-creative-button` → the `.html.twig` file. There is no `render()`.
- **Base styles deliberately omit `color`.** [`define_base_styles()`](../../includes/Elements/Atomic/Creative_Button/Creative_Button.php#L148) sets display/cursor/text-align/padding/radius/background but **not** text colour — a hardcoded base colour wins over the V4 Style panel in the live preview and makes text colour appear "stuck". Elementor's own atomic Button omits it for the same reason; let the Style panel own text colour.
- **Tag switches `<button>` → `<a>` on link.** See Render Output — this dodges Elementor's link-in-link guard that would otherwise disable the Link control.
- **Atomic-only CSS fills the gaps the classic controls used to.** `creative-btn-atomic.scss` adds reveal-layer background colours (Ujarak/Wayra/Rayen would otherwise animate fully transparent), a secondary-text cross-fade for effects without a native reveal (Default/Ujarak/Wayra), and a corrected Tamaya (the shared Tamaya rules are broken; the atomic file overrides them scoped to the marker class).

## Render Output

The root element is `<button>` by default and becomes `<a>` only when a link is set:

```html
<!-- No link: rendered as <button>, mirroring Elementor's atomic Button -->
<button class="<base> eael-creative-button eael-creative-button--atomic eael-creative-button--winona eael-creative-button--has-secondary"
        data-text="Go!" data-interaction-id="…">
  <div class="creative-button-inner">
    <span class="cretive-button-text">Click Me!</span>
  </div>
</button>

<!-- Link set: same markup, root becomes <a> with link attributes -->
<a class="… eael-creative-button--atomic …" href="https://example.com" data-text="Go!" …> … </a>
```

- `eael-creative-button--has-secondary` is added only when secondary text is non-empty (gates the cross-fade so primary text never fades into a blank button).
- **Tamaya** additionally emits `.eael-creative-button--tamaya-secondary` before/after divs (the atomic SCSS hides these and uses a `::before` reveal instead — the divs are legacy markup carried from the classic shape).
- The text-span class is `cretive-button-text` (the same historical typo as the classic widget — kept for CSS-contract parity).

> **Why `<button>` until a link exists:** Elementor's link-in-link guard flags an element whose root DOM node merely *contains* an `<a>` while having no link of its own, which disables the Link control. Always emitting `<a>` trips that guard; emitting `<button>` until a link is set keeps the control usable, and the root itself becomes the `<a>` once a link is added (so the anchor is never nested in a non-linked element).

## Props / Controls Reference

| Prop | Type | Default | Section | Affects |
| ---- | ---- | ------- | ------- | ------- |
| `text` | `Html_V3` | "Click Me!" | Content | Primary button text (inline-editable) |
| `secondary_text` | `String` | "Go!" | Content | `data-text` attribute + reveal text (Winona/Rayen/Tamaya natively; cross-fade for the rest) |
| `effect` | `String` (enum) | `eael-creative-button--default` | Settings | Hover-animation class on the root |
| `link` | `Link` | — | Settings | When set, root becomes `<a>` with link attributes |
| `classes` | `Classes` | `[]` | (Advanced) | Extra CSS classes |
| `_cssid` | `String` | — | Settings | CSS ID on the element |
| `attributes` | `Attributes` | — | (Advanced) | Custom attributes (overridable-ignored) |

Effect options come from [`get_effects()`](../../includes/Elements/Atomic/Creative_Button/Creative_Button.php#L66) — the single source for both the prop enum and the Select control.

## Asset Dependencies

Registered in [`Atomic_Widgets_Loader::register_assets()`](../../includes/Classes/Atomic_Widgets_Loader.php#L40) on `elementor/frontend/after_register_styles`; the widget declares the leaf handle via `get_style_depends()`:

| Handle | Source | Depends on |
| ------ | ------ | ---------- |
| `eael-cb-atomic-base` | `creative-btn.min.css` (shared mechanics) | — |
| `eael-cb-atomic` | `creative-btn-atomic.min.css` (atomic reveal layer) | `eael-cb-atomic-base` |

`get_style_depends()` returns `['eael-cb-atomic']`; the base handle loads transitively. No JS — the atomic widget has no frontend script.

## E2E Testing

Seeded by [`tests/e2e/utils/seed.sh`](../../tests/e2e/utils/seed.sh), which enables the `e_atomic_elements` experiment (required — the widget does not register without it) and creates `/creative-btn-atomic-test/` from the V4 template. Run:

```bash
npm run test:setup   # first time
npm run test:e2e     # runs creative-btn-atomic.spec.ts among others
```

The spec asserts: primary text renders, the effect + `--has-secondary` marker classes apply, `data-text` carries the secondary text, and the root is a `<button>` when no link is set.

## Known Limitations

- **No icon.** V4 lacks the FA icon-library picker; intentionally omitted.
- **No gradient / Liquid-Glass background, no Pro effects.** Only the six free effects.
- **Hardcoded `<a>` tag when linked** — does not honour `settings.link.tag` (Elementor's atomic Button does). Acceptable scope cut.
- **`data-text` + Tamaya divs always emit** even when the effect doesn't use them — negligible dead markup, mirrors the classic widget.
- **Text span typo `cretive-button-text`** retained for CSS-contract parity with the classic widget.
- **Requires the Atomic Widgets experiment.** With `e_atomic_elements` off, the widget is absent (by design).

## Recent Significant Changes

- Promoted from pilot to production. Future entries only when a public contract / element type / rendered class changes — not for every commit. Format: `version — description (#card)`.
