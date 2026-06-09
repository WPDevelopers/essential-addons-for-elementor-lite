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

## Inventory (11 extensions — all docs ✅)

| Slug | Class | Doc | Role |
| ---- | ----- | --- | ---- |
| `custom-js` | `Custom_JS.php` | [custom-js.md](custom-js.md) | Per-page custom JavaScript injection |
| `special-hover-effect` ⚠️ | `Hover_Effect.php` | [special-hover-effect.md](special-hover-effect.md) | Hover-state animations on any element (slug ≠ classname) |
| `image-masking` | `Image_Masking.php` | [image-masking.md](image-masking.md) | SVG-based image masking |
| `liquid-glass-effect` | `Liquid_Glass_Effect.php` | [liquid-glass-effect.md](liquid-glass-effect.md) | Glassmorphism / liquid-glass visual effect |
| `post-duplicator` | `Post_Duplicator.php` | [post-duplicator.md](post-duplicator.md) | Admin-side "duplicate this post" link |
| `promotion` | `Promotion.php` | [promotion.md](promotion.md) | Internal promotion / upsell rendering (canonical example) |
| `reading-progress` | `Reading_Progress.php` | [reading-progress.md](reading-progress.md) | Reading-progress bar at top of post |
| `scroll-to-top` | `Scroll_to_Top.php` | [scroll-to-top.md](scroll-to-top.md) | Scroll-to-top floating button |
| `table-of-content` | `Table_of_Content.php` | [table-of-content.md](table-of-content.md) | Auto-generated table of contents from headings |
| `vertical-text-orientation` | `Vertical_Text_Orientation.php` | [vertical-text-orientation.md](vertical-text-orientation.md) | Vertical text writing-mode helpers |
| `wrapper-link` | `Wrapper_Link.php` | [wrapper-link.md](wrapper-link.md) | Make a whole section / column a clickable link |

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

All 11 extensions now have docs (filled 2026-05-11 in a single batch — see [`project_widget_docs_progress`](../../../.claude/) memory record). Subsequent work follows update-with-change discipline:

1. On every change to an extension, update its doc in the same PR.
2. The `pr-workflow` skill includes an "Extension Doc Updated?" mental check (when wired up in the PR template).

Doc lengths run 220–460 lines depending on extension complexity — Hover_Effect / Image_Masking / Table_of_Content are the largest; Custom_JS is the smallest. Match the per-extension complexity rather than padding to a length target.

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
