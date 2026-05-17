# Essential Addons for Elementor — Documentation

Developer-facing documentation for contributors, Pro plugin developers, and any third-party integrating with EA's hooks. End-user "how to use this widget on my site" content lives at <https://essential-addons.com/docs/> — these docs are about the code, not the user experience.

Read this index first to find the right doc; each subdirectory has its own README that goes deeper.

## Layout

```
docs/
├── README.md                  ← this file
├── widgets/                   ← per-widget developer docs (one .md per widget)
├── architecture/              ← cross-cutting subsystems (asset loading, AJAX, login, …)
├── extensions/                ← per-extension docs (lazy-fill)
└── traits/                    ← per-trait docs (lazy-fill)
```

Plus, outside `docs/`:

- [`CLAUDE.md`](../CLAUDE.md) — terse pointers for AI agents (system map + cross-references to these docs)
- [`.claude/`](../.claude/) — AI tooling: skills, commands, rules, team usage guide. See [`.claude/README.md`](../.claude/README.md) for how to use the slash commands and skills.

## Where to start

Pick the question that matches your need:

| Your question | Where to read |
| ------------- | ------------- |
| "How does widget X work? What controls does it have? What are the gotchas?" | [`widgets/<slug>.md`](widgets/) — start with [`widgets/fancy-text.md`](widgets/fancy-text.md) as the canonical example |
| "How does the plugin decide which CSS / JS to load on a page?" | [`architecture/asset-loading.md`](architecture/asset-loading.md) |
| "Where do widget settings live, and how do they reach `render()`?" | [`architecture/editor-data-flow.md`](architecture/editor-data-flow.md) |
| "Where are AJAX endpoints? What's the security contract?" | [`architecture/dynamic-data/ajax-endpoints.md`](architecture/dynamic-data/ajax-endpoints.md) (or the [parent `dynamic-data.md` overview](architecture/dynamic-data.md)) |
| "How do load-more / infinite scroll work?" | [`architecture/dynamic-data/load-more-and-pagination.md`](architecture/dynamic-data/load-more-and-pagination.md) |
| "How is WooCommerce wired into widgets?" | [`architecture/dynamic-data/woocommerce-integration.md`](architecture/dynamic-data/woocommerce-integration.md) |
| "Where does Login & Registration validate, save, redirect?" | [`architecture/dynamic-data/login-register.md`](architecture/dynamic-data/login-register.md) |
| "How do third-party plugins (ACF, EmbedPress, form plugins) integrate?" | [`architecture/dynamic-data/third-party-integrations.md`](architecture/dynamic-data/third-party-integrations.md) |
| "Where does admin campaign copy come from? How do dismissals work?" | [`architecture/admin-notices.md`](architecture/admin-notices.md) |
| "How does the Quick Setup Wizard work? Where is the React source?" | [`architecture/quick-setup.md`](architecture/quick-setup.md) |
| "How do I add a new widget? Where's the build step?" | Start with [`.claude/skills/new-widget/SKILL.md`](../.claude/skills/new-widget/SKILL.md) and [`.claude/rules/widget-development.md`](../.claude/rules/widget-development.md), then [`docs/widgets/README.md`](widgets/README.md) for the doc convention |
| "I'm a new contributor. Where do I start?" | This file → [`architecture/README.md`](architecture/README.md) for the system map → [`widgets/fancy-text.md`](widgets/fancy-text.md) for a fully-fleshed widget doc |

## Per-directory READMEs

Each subdirectory has its own README explaining structure, conventions, and how to add new files:

- [`widgets/README.md`](widgets/README.md) — 19-section structure for per-widget docs, lazy-fill philosophy, naming conventions
- [`architecture/README.md`](architecture/README.md) — system map of the four render phases + AJAX flow, 12-section structure for architecture docs, list of completed and planned docs
- [`extensions/README.md`](extensions/README.md) — per-extension doc structure (folder is scaffolded; individual extension docs are lazy-filled as work touches them)
- [`traits/README.md`](traits/README.md) — per-trait doc structure (same lazy-fill philosophy)

## Conventions across all docs

- **English only.** No mixed-language content in any committed file. Conversational discussion can be in any language; committed content is English so the docs are accessible to all contributors and stay consistent across forks.
- **Cross-link to source with line numbers** when referencing specific code: `[Asset_Builder::frontend_asset_load](../includes/Classes/Asset_Builder.php#L115)`.
- **Cross-link to public docs in angle brackets**: `<https://essential-addons.com/elementor/docs/...>`.
- **Code blocks always specify a language** (`php`, `js`, `scss`, `text` for diagrams).
- **Tables surrounded by blank lines** (markdown lint compliance).
- **Section subtitles use `### Heading`** rather than bold-as-heading.
- **Architecture decisions are ADR-style** — context, decision, alternatives rejected, consequences.
- **Worked examples cite real EA code**; abstract pseudocode is not allowed in architecture docs.

## Maintenance

When you add a new architecture doc or per-widget doc, update the relevant subdirectory README and `CLAUDE.md`'s pointer list. The structure is small enough to maintain manually; no auto-generation pipeline is in place.

For doc drafts that should not ship to wordpress.org, place them under `docs/_drafts/` and add the path to `.distignore`.
