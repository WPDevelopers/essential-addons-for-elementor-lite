# Per-Widget Developer Docs

One markdown file per widget, capturing what the code can't tell you — the why behind decisions, which controls depend on which, how the widget actually behaves at runtime, where the extension points are, and how to troubleshoot common issues.

## Purpose

These docs answer specific questions without making the reader open source files:

- "Is there an issue with this widget?" → **Common Issues**, **Known Limitations**
- "Why isn't option X showing in the panel?" → **Conditional Dependencies**
- "Why isn't option X working at runtime?" → **Behavior Flow**, **JavaScript Lifecycle**, **Common Issues**
- "How do I add a new feature?" → **Architecture**, **Hooks & Filters**, **Customization Recipes**
- "What does this widget do?" → **Overview**, **Features**, **Use Cases**
- "What's safe to change without breaking Pro?" → **Hooks & Filters**, **Architecture Decisions**

End-user "how to use this widget on my site" content lives at <https://essential-addons.com/docs/> — don't duplicate it here.

## Required Sections (Checklist)

Every widget doc has these 19 sections in this order. If a section genuinely doesn't apply, keep the heading and write `N/A — <reason>` rather than deleting it — predictable structure across all docs is the value.

### Orientation

- [ ] **Overview** — one or two short paragraphs in plain language; what the widget shows and what problem it solves
- [ ] **Features** — bullet list from a site-builder's perspective; no jargon
- [ ] **Pro vs Lite** — capability matrix table
- [ ] **Use Cases** — three to five real scenarios where the widget fits

### Code Map

- [ ] **File Map** — table of files (PHP class, SCSS, JS, `config.php` entry, vendor libs) and their roles
- [ ] **Architecture** — the *why* behind non-obvious design choices; one paragraph per decision
- [ ] **Render Output** — annotated DOM tree; mark which classes are styling hooks, which attributes JS reads, which elements are conditional

### Reference

- [ ] **Controls Reference** — table of meaningful controls (id, type, default, tab → section, what they affect on output)
- [ ] **Conditional Dependencies** — `text` block mapping which controls hide / show based on others; the "why isn't option X showing?" answer
- [ ] **Behavior Flow** — numbered sequence from "user drops widget" to "user sees the rendered result"
- [ ] **JavaScript Lifecycle** — init trigger, guard, reads, branches, runtime state; or `N/A — pure CSS widget`
- [ ] **Asset Dependencies** — separate CSS and JS tables; source, when loaded, why

### Extension

- [ ] **Hooks & Filters** — the public contract; mark any un-prefixed legacy hooks clearly with the dual-emit migration note
- [ ] **Customization Recipes** — two to four copy-paste-ready snippets for common extension needs

### Operations

- [ ] **Common Issues** — FAQ format: symptom → likely cause → diagnose → fix
- [ ] **Testing Checklist** — manual verification steps to walk after any change

### History

- [ ] **Architecture Decisions** — ADR-style records: context, decision, alternatives rejected, consequences
- [ ] **Known Limitations** — edge cases, perf nits, browser quirks, WPML / RTL caveats
- [ ] **Recent Significant Changes** — micro-changelog of meaningful changes only (public hook / control id / rendered class / vendor lib / major refactor)

## How to write a new widget doc

1. **Start from the reference example.** Copy [`fancy-text.md`](fancy-text.md) as your starter — it follows the full structure with real content you can adapt:

   ```bash
   cp docs/widgets/fancy-text.md docs/widgets/<your-slug>.md
   ```

2. **Replace the header block** — class file path, slug, public docs URL, Pro-shared flag.

3. **Walk the checklist top to bottom**, replacing each section's content with your widget's specifics. For sections that don't apply, keep the heading and write `N/A — <reason>` (e.g. `N/A — pure CSS widget, no JS`).

4. **Keep the structure** even when content shrinks. A short doc that follows the same headings is more useful than a long doc with custom organisation — readers can predict where to find each piece of information.

5. **Run the [conventions](#conventions)** before opening the PR — bare URLs in angle brackets, language specifier on every code block, blank lines around tables, `### Heading` not `**Bold**` for subsections.

## How this folder grows

Lazy fill, not big-bang. We don't pre-write 61 widget docs — that would burn a week and decay fast. Instead:

1. The first time a widget is touched after this folder launches, create its doc using the checklist above
2. On every subsequent change to that widget, update the doc in the same PR
3. The `pr-workflow` skill includes a "Widget Doc Updated?" checkbox in the PR template (when wired up)

Comprehensive docs run 200–400 lines for complex widgets like Fancy Text. Simpler widgets fill fewer sections — sections that don't apply get a one-line `N/A — <reason>` note rather than being deleted, so the structure remains predictable across all docs.

## File naming

- Use the **slug** from `config.php`, not the class name: `fancy-text.md`, not `Fancy_Text.md`
- Slugs match the `config.php` element key and are URL-friendly
- Underscores `_` appear only when the slug itself uses them

## Conventions

- Cross-link to source with line numbers: `[register_controls()](../../includes/Elements/Fancy_Text.php#L72)`
- Cross-link to public docs in the header block using angle brackets: `<https://...>`
- Cross-link to skills and rules in `.claude/`
- Code blocks always specify a language (`html`, `php`, `js`, `scss`, `text` for diagrams)
- Tables surrounded by blank lines (markdown lint compliance)
- Section subtitles use `### Heading` not `**Bold**`
- Keep architecture and decision notes terse — one paragraph per item, with the rejected alternative if relevant
- "Recent Significant Changes" — only entries that change a public hook, control id, rendered class, default rendering, vendor lib, or major refactor; not every commit
