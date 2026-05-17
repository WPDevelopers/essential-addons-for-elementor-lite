# {Widget Name} Widget

> One-line description: what the widget renders, primary use case, and what's distinctive about it (Pro pattern, vendor lib, special UX). Aim for one sentence that reads well on its own.

**Class file:** [`includes/Elements/{ClassName}.php`](../../includes/Elements/{ClassName}.php)
**Slug:** `{slug}` (widget id `eael-{slug}`) {⚠️ if widget id differs from slug, flag it here and cross-link to asset-loading.md}
**Public docs:** <https://essential-addons.com/elementor/docs/{slug}/>
**Pro-shared:** ✅ Yes — {one-line summary of what Pro adds and via what mechanism} | ❌ No — Lite-only widget. {Mention if `eael_section_pro` upsell is absent}

---

## Overview

One short paragraph (3–5 sentences) covering:

- What the widget renders (DOM shape, layout types)
- What problem it solves for a site builder
- Key technical detail (e.g. "uses Plyr v3 for video", "GSAP-driven animation in Pro")
- The Lite/Pro relationship summary if relevant

Target: ~80–120 words. Don't repeat the metadata in the header.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| {Feature 1 — what it does} | ✅ | ✅ |
| {Pro-only feature} | ❌ — {fallback behaviour} | ✅ via `{hook_name}` |
| `eael_section_pro` upsell panel | shown | hidden |

Add 4–10 rows covering the meaningful Lite/Pro differences. Skip features that work identically in both — they crowd the table.

If the widget is purely Lite (no Pro reference at all), use:

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All controls | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present (or: shown when Lite) | — |

For shared patterns reference [`_patterns.md`](_patterns.md) instead of explaining the mechanics.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/{ClassName}.php`](../../includes/Elements/{ClassName}.php) | PHP widget class — controls, render, helpers |
| [`src/css/view/{slug}.scss`](../../src/css/view/{slug}.scss) | Source styles — {what regions / layout variants} |
| [`src/js/view/{slug}.js`](../../src/js/view/{slug}.js) | Frontend logic — {one-line summary of what it does} |
| [`config.php`](../../config.php#L{line}) entry `'{slug}'` | `Asset_Builder` dependency declaration |
| `assets/front-end/css/view/{slug}.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/{slug}.min.js` | Built output (do not edit) |
| {vendor lib path} | Vendor — {library name + purpose} |

Drop rows that don't apply (no JS, no vendor libs, etc.). For pure-CSS widgets, just CSS + config entry.

## Architecture

Three to five bullets covering **non-obvious** design choices. Each bullet should answer "why is this the way it is?", not "how does it work?".

- **{Decision 1 in bold}** — one-paragraph rationale. Cite source lines when helpful: `[line 123](../../includes/Elements/{ClassName}.php#L123)`.
- **{Shared pattern reference}** — see [`_patterns.md § Liquid Glass`](_patterns.md#liquid-glass-injection-chain). Selector target: `.eael-{slug} .{specific-selector}`. Front-only (or front + rear).
- **{Render branching}** — if `render()` branches on a setting, summarise the branches in one bullet.
- **{Special JS pattern}** — e.g. `eael.hooks.addAction("init", "ea", …)` instead of `jQuery(window).on("elementor/frontend/init", …)`; reference `_patterns.md` if shared.
- **{Known cruft / legacy}** — e.g. legacy typo in control id, dead code branch, deprecated WP function — call it out here, expand in Known Limitations.

When the widget uses a shared pattern (Liquid Glass, FA4 shim, WPML, `has_pro` handoff, `eael_section_pro` upsell), reference [`_patterns.md`](_patterns.md) section instead of re-explaining. Document only what's **unique** to this widget (selector target, default value, flag name, etc.).

## Render Output

Annotated DOM tree showing the rendered HTML. Mark conditional elements with `[?]`.

```html
<div class="eael-{slug} {variant-class}"
     data-{attr}="{value}">
  [?] <div class="{conditional-element}">…</div>
  <div class="{main-region}">
    {content here}
  </div>
</div>
```

Notes:

- Most important class names and `data-*` attributes that JS reads or themes target
- Conditional elements (`[?]`) with their visibility condition
- Any non-obvious markup decisions (e.g. wrapper tag swaps, nested anchors, inline styles emitted from PHP)
- HTML correctness flags (unmatched tags, validator warnings)

If the widget has multiple render branches (style variants, layout types), include one annotated tree per branch under `### Sub-headings`.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/{ClassName}.php#L{line}) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `{control_id}` | SELECT / SWITCHER / TEXT / etc. | `default-value` | Content → {Section Name} | What this control changes on the output |

Include 10–25 meaningful controls. Skip pure visual controls (every typography group, every padding slider) unless they're unusual. Style sections can be summarised in a single row:

| Style → {Section Name} | various | — | Style tab | {What styling is exposed — typography, colour, padding, etc.} |

For Repeater controls, add a sub-table:

### Per-item Repeater controls (`{repeater_id}`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `{item_field}` | TEXT | empty | `{output}` |

## Conditional Dependencies

```text
{control_id_a}                  → visible when {control_id_b} == '{value}'
{control_id_c}                  → visible when {control_id_b} in ['{value1}', '{value2}']
{control_id_d}                  → visible when ... AND {other condition}

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

Drop the `eael_section_pro` line if the widget doesn't have the upsell panel.

This block answers "why doesn't option X show in my panel?" without making the reader open the source.

## Hooks & Filters

For widgets with Pro extension hooks or other hooks of interest:

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_{hook_name}` | filter (emitted) | `array $defaults` | What Pro injects or extends |
| `{un_prefixed_legacy_hook}` ⚠️ legacy | action (emitted) | `(Widget_Base $widget, ...)` | What it does; warning about not renaming without dual-emit migration |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides upsell; force-fallback logic |
| `wpml_object_id` | filter (consumed) | `int $id, string $type, bool $original` | WPML translation (see [_patterns.md § WPML](_patterns.md#wpml-media-translation)) |

For shared patterns, link to [`_patterns.md`](_patterns.md) and only document **widget-specific** parameters (selector targets, default colour, etc.).

If the widget emits no widget-specific hooks:

> N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides only.

## JavaScript Lifecycle

For widgets with JavaScript:

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-{slug}.default', {HandlerName})` (or `eael.hooks.addAction("init", "ea", …)` — newer EA pattern)
- **Guard:** `if (eael.elementStatusCheck('{flagName}')) return false;` (or "none — relies on {pattern}" if no guard)
- **Vendor dependency:** `{lib-name}` — `{what it provides}`
- **Reads on init:** what `data-*` attributes, what DOM nodes
- **Branches:** if-statements that drive behaviour (`hasClass('xxx')` checks, settings switches)
- **Runtime state:** any persistent state (timers, closures, module vars) and whether it's per-instance or global
- **Custom events / API:** any `CustomEvent` dispatched, any `window.X` global exposed

For pure-CSS widgets:

> N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`.

## Common Issues

Three to five user-facing issues in symptom → cause → diagnose → fix format:

### {Symptom (what the user sees)}

- **Likely cause:** {what's actually wrong}
- **Diagnose:** {one or two debugging steps}
- **Fix:** {concrete action to take}

Skip issues that apply to all WordPress widgets (asset caching, JS errors, etc.). Focus on widget-specific gotchas: settings combinations that don't work, browser-specific quirks, render-branch surprises, common typos in user input.

## Known Limitations

Three to five bullets — edge cases, perf nits, deprecation warnings, accessibility gaps, browser quirks. Each should be **specific** (cite source lines, version constraints, browser features).

- **{Limitation 1}** — what fails, when it fails, link to source line if relevant
- **{Limitation 2}** — `{specific control}` is hardcoded; users can't change via panel
- **{Limitation 3}** — known bug in `render()` line N (stray `</a>`, missing `esc_url()`, etc.)
- **{Performance trade-off}** — vendor lib loads unconditionally; ~N KB even when feature is off
- **{Compatibility caveat}** — WPML / RTL / shared-host quirk

Limitations that are part of shared patterns (e.g. "Pro/Lite double-registration risk") should reference [`_patterns.md`](_patterns.md) instead of being re-explained.
