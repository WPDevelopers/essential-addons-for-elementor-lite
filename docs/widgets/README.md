# Per-Widget Developer Docs

One markdown file per widget, capturing what the **code can't tell you** — the why, the Pro extension points, known limitations, and architectural decisions.

## Audience

- Internal devs adding/modifying a widget — read first to avoid duplicating existing logic
- New team members onboarding to a widget area
- Pro plugin devs needing the Lite contract for a widget
- Code reviewers verifying that a change aligns with the widget's documented surface

End-user "how to use this widget on my site" content lives at https://essential-addons.com/docs/ — **don't duplicate it here**.

## What goes in each doc

A short (~70–100 lines) markdown file with these sections:

- **Header block** — class file, slug, public docs link, Pro-shared flag
- **Architecture Notes** — *the why* — non-obvious design decisions
- **Controls Summary** — high-level overview, with link to source for detail (no per-property listing)
- **Frontend Dependencies** — vendor libs, what each is for, when loaded
- **Pro Extension Points** — filters and actions Pro hooks into
- **Known Limitations** — edge cases, perf nits, WPML quirks, etc.
- **Related Widgets** — when to use this vs that
- **Recent Significant Changes** — micro-changelog (only meaningful changes, not every commit)

See [`_template.md`](_template.md) for the exact structure.

## How this folder grows

**Lazy fill, not big-bang.** We don't pre-write 61 widget docs — that would burn a week and decay fast. Instead:

1. The first time you touch a widget after this folder is launched, **create** its doc using `_template.md`
2. On every subsequent change to that widget, **update** the doc in the same PR
3. The `pr-workflow` skill includes a "Widget Doc Updated?" checkbox in the PR template

After ~6 months of organic work, the most-edited widgets will all have docs. Rarely-touched widgets stay stub-free until they're touched.

## File naming

- Use the **slug** from `config.php`, not the class name: `fancy-text.md`, not `Fancy_Text.md`
- Slugs match the `config.php` element key and URL-friendly format
- Underscores `_` only when the slug itself uses them

## Conventions

- Cross-link to source: `[register_controls()](../../includes/Elements/Fancy_Text.php#L72)`
- Cross-link to public docs in the header block
- Cross-link to related skills/rules in `.claude/`
- Keep architecture notes terse — one paragraph per decision
- "Recent Significant Changes" — version + one-line description; not a full git log
