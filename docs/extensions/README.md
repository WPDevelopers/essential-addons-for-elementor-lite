# Per-Extension Developer Docs

One markdown file per extension in `includes/Extensions/`, capturing what the extension does, how it modifies the page output, what hooks it exposes, and how to extend or troubleshoot it. End-user "how to enable / use this extension" content lives at <https://essential-addons.com/docs/> — these docs are about the code.

## Extension vs Widget

The two concepts are easy to confuse:

| | Widget | Extension |
| --- | ------ | --------- |
| Folder | `includes/Elements/` | `includes/Extensions/` |
| Renders standalone? | Yes — user drags it onto the canvas | No — augments existing Elementor elements (sections, columns, all widgets) or the page itself |
| Per-instance settings? | Yes (a Fancy Text widget has its own settings) | Sometimes — usually configured globally in EA settings + on a per-section toggle |
| Asset enqueue path | `config.php` `elements` registry | `config.php` `extensions` registry (same shape, different top-level key) |
| Doc folder | [`../widgets/`](../widgets/) | this folder |

If you're documenting something that ships with its own visible "drop me on the canvas" widget, it's a widget. If it's a behavior layered onto existing content (a hover effect, scroll-to-top button, table of content, custom JS field), it's an extension.

## Inventory (11 extensions)

| File | Role |
| ---- | ---- |
| `Custom_JS.php` | Per-page custom JavaScript injection |
| `Hover_Effect.php` | Hover-state animations on any element |
| `Image_Masking.php` | SVG-based image masking |
| `Liquid_Glass_Effect.php` | Glassmorphism / liquid-glass visual effect |
| `Post_Duplicator.php` | Admin-side "duplicate this post" link |
| `Promotion.php` | Internal promotion / upsell rendering |
| `Reading_Progress.php` | Reading-progress bar at top of post |
| `Scroll_to_Top.php` | Scroll-to-top floating button |
| `Table_of_Content.php` | Auto-generated table of contents from headings |
| `Vertical_Text_Orientation.php` | Vertical text writing-mode helpers |
| `Wrapper_Link.php` | Make a whole section / column a clickable link |

## Required Sections (Checklist)

Each extension doc has the same 12-section structure as the architecture docs (consistency across all of `docs/`). If a section genuinely doesn't apply, keep the heading and write `N/A — <reason>` so the structure stays predictable.

### Orientation

- [ ] **Overview** — one or two short paragraphs in plain language; what the extension does and what problem it solves
- [ ] **Components** — table of files / classes / hooks involved (with line counts)
- [ ] **Pro vs Lite** — capability matrix table (extensions can be Pro-only, Lite-only, or shared)

### How it works

- [ ] **Architecture** — the *why* behind non-obvious design choices; one paragraph per decision
- [ ] **Render Behavior** — how it modifies the page output (DOM additions, CSS injections, JS hooks)
- [ ] **Asset Dependencies** — separate CSS and JS sections; sources, when loaded, why
- [ ] **Hook Timing** — what fires when, in what priority, in what phase

### Extension

- [ ] **Configuration & Extension Points** — the global settings + per-element controls + filters and actions this extension exposes
- [ ] **Customization Recipes** — copy-paste-ready snippets for common extension needs

### Operations

- [ ] **Common Issues** — FAQ format: symptom → cause → fix
- [ ] **Debugging Guide** — concrete steps when this extension misbehaves

### History

- [ ] **Architecture Decisions** — ADR-style records: context, decision, alternatives rejected, consequences
- [ ] **Known Limitations** — edge cases, perf nits, browser quirks
- [ ] **Recent Significant Changes** — micro-changelog of meaningful changes only
- [ ] **Cross-References** — to skills, rules, sibling docs

## How to write a new extension doc

1. **Start from the canonical example** (when one exists). Until then, copy the same structure that [`../widgets/fancy-text.md`](../widgets/fancy-text.md) demonstrates and adapt it for an extension's concerns. Use slug-based filename: `table-of-content.md`, not `Table_of_Content.md`.
2. **Replace the header block** — class file path, slug (config.php key), public docs URL, Pro-shared flag.
3. **Walk the checklist top to bottom**, replacing each section's content with your extension's specifics. For sections that don't apply, keep the heading and write `N/A — <reason>` (for example, an extension with no JS gets `N/A — pure CSS extension, no JS`).
4. **Keep the structure** even when content shrinks — a short doc that follows the same headings is more useful than a long doc with custom organization.
5. **Run the conventions** before opening the PR — bare URLs in angle brackets, language specifier on every code block, blank lines around tables, `### Heading` not `**Bold**`.

## How this folder grows

Lazy fill, not big-bang. Don't pre-write all 11 extension docs — that would burn time and decay fast. Instead:

1. The first time an extension is touched after this folder launches, create its doc using the checklist above.
2. On every subsequent change to that extension, update the doc in the same PR.
3. The `pr-workflow` skill includes an "Extension Doc Updated?" mental check (when wired up in the PR template).

Comprehensive docs run 200–400 lines for complex extensions like Table of Content. Simpler extensions (Wrapper_Link, Post_Duplicator) might fit in 100 lines — that's fine, just keep the section structure consistent.

## File naming

- Use the **slug** from `config.php` `extensions` registry, not the class name: `table-of-content.md`, not `Table_of_Content.md`.
- Slugs are URL-friendly kebab-case.
- Underscores `_` appear only when the slug itself uses them.

## Conventions

Same as the rest of `docs/`:

- Cross-link to source with line numbers: `[Table_of_Content::render()](../../includes/Extensions/Table_of_Content.php#L100)`
- Cross-link to public docs in angle brackets: `<https://...>`
- Cross-link to skills, rules, sibling architecture docs in `.claude/`
- Code blocks always specify a language (`html`, `php`, `js`, `scss`, `text` for diagrams)
- Tables surrounded by blank lines (markdown lint compliance)
- Section subtitles use `### Heading` rather than bold-as-heading
- Architecture decisions are ADR-style — context, decision, alternatives rejected, consequences
- Worked examples cite real EA code; abstract pseudocode is not allowed
