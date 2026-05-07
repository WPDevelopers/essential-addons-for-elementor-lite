# {Widget Name} Widget

> One-line description of what the widget does and its primary use case.

**Class file:** [`includes/Elements/{ClassName}.php`](../../includes/Elements/{ClassName}.php)
**Slug:** `{slug-as-config.php-key}` (widget id `eael-{slug}`)
**Public docs:** https://essential-addons.com/elementor/docs/{slug}/
**Pro-shared:** ✅ Yes — {brief description of what Pro adds} | ❌ No — Lite-only

---

## Architecture Notes

The **why** behind non-obvious design decisions. Code shows what; this section explains why.

- Why we chose library X instead of Y / built-in
- Why a particular control structure (e.g. nested repeaters)
- Why certain branches in `render()` exist (e.g. legacy compat, Pro injection points)
- Why deps are loaded the way they are

Keep each note to one paragraph. If a decision was made for an FluentBoards card, link the card.

## Controls Summary

High-level overview only. **Don't list every property** — link to source for that.

- **Content tab:** {1-line description of main content controls}
- **Settings tab (if separate):** {behavior controls}
- **Style tab(s):** {what visual regions get styled}
- **Pro upsell section:** {present / absent}

Full controls list is in [`register_controls()`](../../includes/Elements/{ClassName}.php#L{line}). Refer to source for current state — controls evolve, this section doesn't have to track every change.

## Frontend Dependencies

| Library | Purpose | Loaded |
|---------|---------|--------|
| {lib-name} | {what it does for this widget} | Always / Conditional on {control}={value} |

If the widget has no JS deps, write "None — pure CSS widget."

If the widget uses Elementor's `swiper` or `font-awesome-5-all` handle, note that — see [`.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md).

## Pro Extension Points

Filters and actions Pro plugin hooks into. The Lite-side contract.

| Hook | Type | Pro use |
|------|------|---------|
| `eael/{hook-name}` | filter / action | {what Pro injects or extends} |
| `eael/pro_enabled` | filter | Render-time fallback if Pro disabled |

If the widget has no Pro extension points (Lite-only), write "None — widget is Lite-only with no extension points exposed."

⚠️ **Un-prefixed legacy hooks** (no `eael/` prefix) — if the widget has any, document them here as "(legacy, do not remove without coordinated Pro PR)" — Pro depends on them.

## Known Limitations

Edge cases, perf nits, WPML/RTL quirks, browser compat issues, customer-reported behaviors — anything a future contributor should know before assuming the widget works perfectly.

- {Limitation 1, with brief reasoning if not obvious}
- {Limitation 2}

If none, write "No known limitations as of {version}."

## Related Widgets

When to use this vs another EA widget. Helps readers find the right tool.

- **{Other_Widget}** — when {scenario}, prefer that one because {reason}

## Recent Significant Changes

Micro-changelog of meaningful changes. **Not** every commit — only changes that affect:

- Public hooks / contract surface
- Control ids / rendered classes (breaking for theme overrides)
- Default rendering
- Vendor lib choices
- Major refactors

Format: `version — one-line description (FluentBoards card #N)`

- {version} — {description} (#{card})
- {version} — {description} (#{card})

If empty, write "No significant documented changes yet."
