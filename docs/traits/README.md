# Per-Trait Developer Docs

One markdown file per trait in `includes/Traits/`, capturing what the trait provides, where it's composed, what its public surface is, and how to extend or troubleshoot it. End-user content is irrelevant here — these docs are entirely about the internal architecture and are read by contributors and Pro plugin developers.

## Why per-trait docs?

The plugin's `Bootstrap` class is composed of fourteen traits, each handling a distinct concern. Some traits are large and cross-cutting enough to warrant their own architecture-level docs (already done: `Ajax_Handler` → [`../architecture/dynamic-data/ajax-endpoints.md`](../architecture/dynamic-data/ajax-endpoints.md), `Login_Registration` → [`../architecture/dynamic-data/login-register.md`](../architecture/dynamic-data/login-register.md), `Woo_Product_Comparable` → [`../architecture/dynamic-data/woocommerce-integration.md`](../architecture/dynamic-data/woocommerce-integration.md), `Enqueue` covered in [`../architecture/asset-loading.md`](../architecture/asset-loading.md)).

Other traits are smaller and don't justify a full architecture doc, but still benefit from per-file documentation that captures their public methods, hook registrations, and gotchas. That's what this folder is for.

## Inventory (14 traits)

| File | Lines | Role | Architecture-level doc? |
| ---- | ----- | ---- | ----------------------- |
| `Admin.php` | 2,030 | EA settings page, admin pages, dashboard | No — candidate for own doc |
| `Ajax_Handler.php` | 1,685 | Frontend AJAX endpoints | ✅ [`ajax-endpoints.md`](../architecture/dynamic-data/ajax-endpoints.md) |
| `Controls.php` | 2,397 | Shared Elementor control registrations | No — partially covered by [`elementor-controls`](../../.claude/skills/elementor-controls/SKILL.md) skill |
| `Core.php` | 418 | Plugin bootstrap helpers, `enable_setup_wizard`, version tracking | No |
| `Elements.php` | 1,038 | Widget registration with Elementor | No |
| `Enqueue.php` | 144 | Form-plugin compat shims for asset enqueue | ✅ Partial in [`asset-loading.md`](../architecture/asset-loading.md) |
| `Facebook_Feed.php` | 348 | Facebook feed AJAX render | No |
| `Helper.php` | 813 | Shared utility methods used across traits | No |
| `Library.php` | 349 | Shared utilities (path / URL safety, edit-mode detection, settings reading) | No |
| `Login_Registration.php` | 1,855 | Login / Register / Lost / Reset flows | ✅ [`login-register.md`](../architecture/dynamic-data/login-register.md) |
| `Shared.php` | 12 | Tiny shared helpers | No |
| `Template_Query.php` | 296 | Per-widget template directory lookup (lite / pro / theme) | No — partially covered in [`wp-query-construction.md`](../architecture/dynamic-data/wp-query-construction.md) (clarification, not the same as `Helper::get_query_args`) |
| `Twitter_Feed.php` | 274 | Twitter feed render | No |
| `Woo_Product_Comparable.php` | 2,330 | Compare table widget | ✅ Partial in [`woocommerce-integration.md`](../architecture/dynamic-data/woocommerce-integration.md) |

When an architecture doc already covers a trait in depth, the per-trait doc in this folder can be a thin pointer (50–100 lines) summarizing the trait's purpose and linking to the deep doc. When no architecture doc exists yet, the per-trait doc carries the full content (~200–400 lines) using the structure below.

## Required Sections (Checklist)

Each trait doc follows the same 12-section structure as the architecture docs and per-extension docs (consistency across `docs/`). If a section genuinely doesn't apply, keep the heading and write `N/A — <reason>` so structure stays predictable.

### Orientation

- [ ] **Overview** — one or two short paragraphs; what the trait provides
- [ ] **Composed by** — list of classes that `use` this trait (typically just `Bootstrap`, but some traits are used in multiple classes)
- [ ] **Public Surface** — list of public + protected methods this trait adds to the using class

### How it works

- [ ] **Architecture** — the *why* behind non-obvious design choices
- [ ] **Hook Timing** — actions and filters this trait registers, in fire order
- [ ] **Data Flow** — step-by-step trace if the trait owns a request lifecycle (otherwise `N/A — utility trait`)

### Extension

- [ ] **Configuration & Extension Points** — filters and actions this trait emits for third-party extension
- [ ] **Customization Recipes** — copy-paste-ready snippets for common extension scenarios

### Operations

- [ ] **Common Pitfalls** — gotchas, subtle behavior, things that bite contributors
- [ ] **Debugging Guide** — concrete diagnostic steps when this trait's behavior is suspected

### History

- [ ] **Worked Example** — real walkthrough using actual EA code
- [ ] **Architecture Decisions** — ADR-style records: context, decision, alternatives rejected, consequences
- [ ] **Known Limitations** — edge cases, perf nits, surprising behavior
- [ ] **Cross-References** — to architecture docs, skills, rules, sibling traits

## How to write a new trait doc

1. **Check whether an architecture-level doc already covers this trait** (see Inventory table above). If yes, the per-trait doc in this folder is a thin pointer (~80 lines) — overview + composed-by + public-surface table + cross-link to the deep doc.
2. **If no architecture-level doc exists**, write a comprehensive per-trait doc using the full 12-section checklist above (~200–400 lines).
3. Use slug-based filename: `core.md`, `helper.md`, `template-query.md`, `woo-product-comparable.md`. Convert `Underscore_Case` to `kebab-case` for filenames.
4. **Replace the header block** — class file path, line count, list of classes that compose the trait.
5. **Walk the checklist** top to bottom. For sections that don't apply (utility traits often have no Hook Timing or Data Flow), write `N/A — <reason>`.
6. **Cross-link to the architecture corpus** wherever a sister doc covers the same surface area — don't duplicate detailed content.
7. **Run the conventions** before opening the PR.

## How this folder grows

Lazy fill, not big-bang. With 14 traits — half of them already covered by architecture docs — the immediate need is per-trait docs only for the traits an active task touches.

Priority order suggested:

1. **Traits with no architecture-level doc and large surface** — `Admin` (2,030 lines), `Controls` (2,397), `Elements` (1,038), `Helper` (813)
2. **Smaller utility traits without their own doc** — `Core`, `Library`, `Template_Query`, `Shared`
3. **Single-purpose feed traits** — `Facebook_Feed`, `Twitter_Feed`
4. **Pointer-only docs** for traits already covered by architecture — `Ajax_Handler`, `Login_Registration`, `Woo_Product_Comparable`, `Enqueue`

Don't pre-write all of these. Lazy-fill as work touches each trait.

## File naming

- Use **kebab-case** of the class name without the namespace: `Ajax_Handler` → `ajax-handler.md`, `Woo_Product_Comparable` → `woo-product-comparable.md`.
- All-lowercase. No `Underscore_Case` in filenames.

## Conventions

Same as the rest of `docs/`:

- Cross-link to source with line numbers: `[Helper::get_query_args](../../includes/Classes/Helper.php#L179)`
- Cross-link to architecture docs and skills via relative paths
- Code blocks always specify a language (`php`, `js`, `text` for diagrams)
- Tables surrounded by blank lines
- Section subtitles use `### Heading`
- Architecture decisions are ADR-style
- Worked examples cite real EA code
