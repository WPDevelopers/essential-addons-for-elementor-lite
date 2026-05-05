---
name: elementor-controls
description: Design and audit Elementor controls for an EA widget. Use when adding controls to a new or existing widget, refactoring a messy `register_controls()`, deciding between control types, or wiring conditions, responsive, and selectors. Outputs control code that follows EA conventions and renders sensibly with default values.
---

# Elementor Controls

## When to Invoke

- Adding controls to a new widget or extending an existing one
- Refactoring `register_controls()` for consistency
- Choosing between control types or condition forms
- Auditing controls for usability and EA-convention compliance

## Required Inputs

Widget identifier · what the control(s) should let the user configure · whether responsive · whether Lite-only / Pro-shared. Missing → ask.

## Step 1 — Plan Tabs and Sections

Three tabs, in this order:

| Tab | Purpose | Sections inside |
|-----|---------|-----------------|
| `TAB_CONTENT` (default) | What the user types/selects (text, repeater items, source, source-data settings) | "Content", "Settings", "Query" |
| `TAB_STYLE` | How it looks (color, typography, spacing, border, shadow) | One section per visual region — e.g. "Title Style", "Button Style" |
| `TAB_ADVANCED` | Provided by Elementor (margin, padding, motion, custom CSS) — **do not duplicate** |

**Section naming:** `eael_{widget_slug}_{purpose}` — e.g. `eael_fancy_text_settings`. Keep section count low; group related controls.

## Step 2 — Pick the Right Control Type

| Use case | Control type | Notes |
|----------|--------------|-------|
| Single-line text | `TEXT` | Add `'dynamic' => ['active' => true]` if value can come from a dynamic tag; `'ai' => ['active' => true]` for AI-assist |
| Multi-line / rich | `TEXTAREA` / `WYSIWYG` | WYSIWYG only when user needs HTML formatting |
| Number with no unit | `NUMBER` | Use for counts, delays, speeds |
| Number with unit/range | `SLIDER` | Set `range` per unit; pair with `selectors` for live CSS |
| Spacing (top/right/bottom/left) | `DIMENSIONS` | Always pair with `selectors` using `{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} ...` |
| 3-option visual pick (e.g. align) | `CHOOSE` | Provide `icon` per option |
| ≥4 options or text labels | `SELECT` | |
| Boolean | `SWITCHER` | `'return_value' => 'yes'` is the EA convention |
| Color | `COLOR` | Use `'global' => ['default' => Global_Colors::COLOR_PRIMARY]` for theme-aware defaults |
| Repeating items | `REPEATER` | `new Repeater()` → fields → `'fields' => $repeater->get_controls()` + `'title_field'` |
| Image | `MEDIA` | Pair with `Group_Control_Image_Size` |
| Icon | `ICONS` | FA5 — see [.claude/rules/asset-pipeline.md](../../rules/asset-pipeline.md) on the `font-awesome-5-all` handle |
| URL | `URL` | Auto-handles `target=_blank` and `rel=nofollow` |
| Visual divider/label | `HEADING` | Often used as Pro-feature alert (see Step 4) |

**Group controls** — prefer over individual controls when composing a visual style:

| Group | Replaces |
|-------|----------|
| `Group_Control_Typography` | font-family, weight, size, line-height, letter-spacing |
| `Group_Control_Border` | border-style, width, color, radius |
| `Group_Control_Box_Shadow` | inset, x, y, blur, spread, color |
| `Group_Control_Background` | classic / gradient / video |
| `Group_Control_Image_Size` | thumbnail / medium / large / custom |

## Step 3 — Wire Selectors, Responsive, Conditions

- **Selectors:** `'{{WRAPPER}} .eael-{slug}__el' => 'css-prop: {{VALUE}}{{UNIT}};'` — must be scoped under `{{WRAPPER}}` so styles don't leak.
- **Responsive:** use `add_responsive_control()` for any control where mobile/tablet might differ — spacing, font-size, alignment, visibility. Don't use it for booleans or dynamic tags.
- **Conditions** — pick the form:
  - `'condition' => ['key' => 'value']` — single dependency, AND only. Use this 95% of the time.
  - `'conditions' => ['relation' => 'or', 'terms' => [...]]` — OR logic or nested AND/OR. Use only when single form can't express the dependency.
  - Common bug: a control hidden by condition still saves its value — **don't rely on absence**, always read defensively in `render()`.

## Step 4 — EA-Specific Patterns

- **Control ID prefix:** every control id starts with `eael_{widget_slug}_*` to avoid collisions with Elementor core or other widgets.
- **Default values must render acceptably.** A widget dropped on a page with no configuration must look sensible. Set `default` on every meaningful control.
- **Pro-feature select option** — register the Lite-side select with a `'style-2 (Pro)'` label, pair with a `HEADING` control conditional on that value showing "Only available in Pro version". In `render()`, force-fallback if Pro is not enabled:
  ```php
  if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
      $settings['eael_{slug}_style'] = 'style-1';
  }
  ```
- **Pro upsell section** at the bottom, gated by `if ( ! apply_filters( 'eael/pro_enabled', false ) )` — single `CHOOSE` control with description linking to upgrade page. Boilerplate exists in many widgets ([Fancy_Text.php:299-324](../../../includes/Elements/Fancy_Text.php#L299) for reference).
- **Hooks for Pro extension:** if Pro injects options (e.g. additional style choices), expose them via a prefixed filter — `apply_filters( 'eael/{slug}_style_types', $defaults )` — never `apply_filters( '{slug}_style_types', ... )` (un-prefixed = lint violation).

## Step 5 — Verify in Editor

Open the widget on `http://localhost:8888` in Elementor editor:

- [ ] Drop widget at default config — does it render acceptably without changes?
- [ ] Toggle each control — preview updates live (no save needed)
- [ ] Conditions hide/show as expected — no orphaned controls visible when their parent is unset
- [ ] Responsive switcher (desktop/tablet/mobile) — controls flagged responsive show the breakpoint switcher
- [ ] Long labels use `'label_block' => true` (don't wrap awkwardly)
- [ ] No PHP notices in `wp-content/debug.log` while editing

## Common Pitfalls

| Pitfall | Symptom | Fix |
|---------|---------|-----|
| Selector missing `{{WRAPPER}}` | Style leaks to other instances | Always prefix |
| `condition` on a value not in the linked select | Dead control (never visible) | Verify the value exists in options |
| Responsive on non-CSS controls (e.g. SWITCHER) | Confusing UX, no effect | Drop the responsive — make it a normal control |
| Default missing | Widget renders empty / broken on first drop | Set `default` to a reasonable value |
| Group control without scoped `selector` | Styles bleed | `'selector' => '{{WRAPPER}} .eael-{slug}__el'` |
| Un-prefixed control id | Collides with Elementor core controls | `eael_{slug}_*` always |
| `ICONS` control with FA4 string (`fa fa-x`) | Missing-glyph rendering | Use FA5 (`fas fa-x`) |

## Operating Rules

1. **Defaults must render.** No widget should look broken on first drop.
2. **`{{WRAPPER}}` always.** Selectors without it are a styling leak and a P0 bug.
3. **Prefix every control id and section id** with `eael_{widget_slug}_`.
4. **Prefer single `condition`** unless dependency truly needs OR/AND nesting.
