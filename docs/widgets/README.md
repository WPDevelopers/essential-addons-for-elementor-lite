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

## Required Sections (Compressed format — current standard)

Every NEW widget doc has these 12 sections in this order. Target length: ~200 lines. The first 12 widget docs in this folder (fancy-text → svg-draw) use an older 19-section format; both coexist.

### Orientation

- [ ] **Overview** — one short paragraph in plain language; what the widget shows and what problem it solves
- [ ] **Pro vs Lite** — capability matrix table

### Code Map

- [ ] **File Map** — table of files (PHP class, SCSS, JS, `config.php` entry, vendor libs) and their roles
- [ ] **Architecture** — three to five bullets covering non-obvious design choices; if the widget uses one of the shared patterns, reference [`_patterns.md`](_patterns.md) instead of re-explaining
- [ ] **Render Output** — annotated DOM tree; mark which classes are styling hooks, which attributes JS reads, which elements are conditional

### Reference

- [ ] **Controls Reference** — table of meaningful controls (id, type, default, tab → section, what they affect on output)
- [ ] **Conditional Dependencies** — `text` block mapping which controls hide / show based on others
- [ ] **JavaScript Lifecycle** — init trigger, guard, reads, branches, runtime state; or `N/A — pure CSS widget`

### Extension

- [ ] **Hooks & Filters** — the public contract; reference [`_patterns.md`](_patterns.md) for the standard Liquid Glass / FA4 / WPML / `has_pro` / upsell patterns

### Operations

- [ ] **Common Issues** — FAQ format: symptom → likely cause → diagnose → fix; 3-5 entries
- [ ] **Known Limitations** — edge cases, perf nits, browser quirks, WPML / RTL caveats; 3-5 bullets

## Shared patterns

Five common patterns are documented once in [`_patterns.md`](_patterns.md):

1. **Liquid Glass injection chain** — Pro extends visual effects via fixed `do_action` hooks (Info_Box, Flip_Box, Creative_Button, Image_Accordion, Cta_Box via Pro)
2. **FA4 → FA5 icon migration shim** — legacy FA4 string + new ICONS picker coexistence (most widgets with icon controls)
3. **WPML media translation** — `wpml_object_id` filter for image / attachment / template IDs (Cta_Box, Info_Box, Flip_Box, Tooltip)
4. **`has_pro` runtime handoff** — Lite and Pro both register handlers; Lite cedes when Pro is active (SVG_Draw)
5. **`eael_section_pro` standard upsell panel** — the generic "Go Premium" section that appears when Pro is not active

Per-widget docs reference these instead of re-explaining the mechanics each time. This saves 30-50 lines per widget.

## Legacy 19-section format (first 12 widgets)

The first 12 docs in this folder use the original 19-section structure:

```text
fancy-text, dual-header, creative-btn, call-to-action, info-box, flip-box,
price-table, feature-list, image-accordion, tooltip, code-snippet, svg-draw
```

Original 19 sections were: Overview, Features, Pro vs Lite, Use Cases, File Map, Architecture, Render Output, Controls Reference, Conditional Dependencies, Behavior Flow, JavaScript Lifecycle, Asset Dependencies, Hooks & Filters, Customization Recipes, Common Issues, Testing Checklist, Architecture Decisions, Known Limitations, Recent Significant Changes.

These docs are not being retro-compressed — they remain valuable as-is. Sections dropped in the compressed format that the legacy docs still have:

- **Features** — duplicated Controls Reference
- **Use Cases** — generic, low-value
- **Behavior Flow** — derivable from Architecture + Render Output
- **Asset Dependencies** — one-line table; `config.php` is the truth
- **Customization Recipes** — moved to [`_patterns.md`](_patterns.md) for cross-widget patterns
- **Testing Checklist** — predictable per widget type
- **Architecture Decisions** — merged into Architecture
- **Recent Significant Changes** — empty placeholder, populated only when a public-contract change happens

## How to write a new widget doc

1. **Survey first.** Read the PHP class, SCSS, JS source, and `config.php` entry. Identify which shared patterns ([`_patterns.md`](_patterns.md)) apply.

2. **Copy the template** as a starter:

   ```bash
   cp docs/widgets/_template.md docs/widgets/<your-slug>.md
   ```

   [`_template.md`](_template.md) follows the 11 `##` sections (12 with the implicit Header block) with placeholder content and inline guidance per section.

3. **Use the 12-section structure** above. Target ~200 lines. If the widget is unusually complex (10+ controls sections, multi-engine JS, large Pro extension surface), longer is fine.

4. **Reference [`_patterns.md`](_patterns.md)** for any shared pattern. Don't re-explain Liquid Glass / FA4 / WPML / `has_pro` / upsell — just document what's unique to this widget (selector target, default value, engine flag name, etc.).

5. **Run the [conventions](#conventions)** before opening the PR — bare URLs in angle brackets, language specifier on every code block, blank lines around tables, `### Heading` not `**Bold**` for subsections.

## How this folder grows

Lazy fill, not big-bang. We don't pre-write 61 widget docs — that would burn a week and decay fast. Instead:

1. The first time a widget is touched after this folder launches, create its doc using the checklist above
2. On every subsequent change to that widget, update the doc in the same PR
3. The `pr-workflow` skill includes a "Widget Doc Updated?" checkbox in the PR template (when wired up)

Compressed docs target ~200 lines per widget. Complex widgets (large Pro extension surface, multi-engine JS, 10+ control sections) may run longer. Simpler widgets that don't use any shared patterns may run shorter. Sections that genuinely don't apply get a one-line `N/A — <reason>` note rather than being deleted, so the structure remains predictable across all docs.

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
