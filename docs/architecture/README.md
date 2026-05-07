# Architecture Documentation

Cross-cutting subsystems that every widget shares — asset loading, editor-to-frontend data flow, dynamic data (AJAX, WP_Query, login, WooCommerce). These are the areas where contributors and AI assistants hit walls when debugging, because the systems span multiple files and the *why* is not obvious from any single file.

Per-widget specifics live in [`../widgets/`](../widgets/). These architecture docs cover the shared machinery widgets stand on.

## Purpose

These docs answer questions that span the whole plugin, not a single widget:

- "How does the plugin decide which CSS / JS to load on a given page?" → **asset-loading**
- "Why is my widget's asset not loading inside a popup / template / shortcode?" → **asset-loading § Detection edge cases**
- "How does Elementor get my widget's settings to `render()`?" → **editor-data-flow**
- "What's the difference between `condition` and `conditions`?" → **editor-data-flow § Control conditions**
- "Where are AJAX endpoints defined and what's their security contract?" → **dynamic-data/ajax-endpoints**
- "How does load-more / infinite scroll actually work?" → **dynamic-data/load-more-and-pagination**
- "How is WooCommerce wired into widgets like Product Grid?" → **dynamic-data/woocommerce-integration**
- "Where does Login & Registration validate, save, and redirect?" → **dynamic-data/login-register**
- "What's the hook timing for asset enqueue?" → **asset-loading § Hook timing table**
- "Where does admin campaign copy come from? How do dismissals work?" → **admin-notices**
- "How do I add a new BFCM-style admin pointer?" → **admin-notices § Adding a New Campaign Notice**

## System Map

The plugin runs in four phases per page request, plus a separate AJAX flow:

```text
┌─ INIT PHASE ─────────────────────────────────────────────────────┐
│ plugins_loaded (priority 100)                                    │
│   → Bootstrap singleton (includes/Classes/Bootstrap.php)         │
│   → composes 15 traits (Core, Helper, Enqueue, Admin, Elements,  │
│     Controls, Ajax_Handler, Login_Registration, Woo_Hooks, …)    │
│   → instantiates Elements_Manager + Asset_Builder                │
└──────────────────────┬───────────────────────────────────────────┘
                       │
┌─ PAGE RENDER PHASE ──▼───────────────────────────────────────────┐
│ Elementor walks post data tree                                   │
│   → for each EA widget instance, calls render()                  │
│   → render() reads $this->get_settings_for_display()             │
│   → outputs HTML with add_render_attribute() data-* attrs        │
└──────────────────────┬───────────────────────────────────────────┘
                       │
┌─ ASSET PHASE ────────▼───────────────────────────────────────────┐
│ wp_enqueue_scripts                                               │
│   → Asset_Builder::frontend_asset_load                           │
│   → reads config.php registry (slug → class + CSS/JS deps)       │
│   → detects which widgets exist on the page                      │
│   → enqueues ONLY their declared deps                            │
└──────────────────────┬───────────────────────────────────────────┘
                       │
┌─ JS RUNTIME PHASE ───▼───────────────────────────────────────────┐
│ elementor/frontend/init fires                                    │
│   → each widget JS calls                                         │
│     elementorFrontend.hooks.addAction(                           │
│       "frontend/element_ready/eael-{slug}.default", Handler)     │
│   → guarded by eael.elementStatusCheck("eael{Slug}Load")         │
│   → handler reads data-* attrs from the rendered HTML            │
└──────────────────────────────────────────────────────────────────┘

┌─ AJAX FLOW (independent of the four-phase render) ───────────────┐
│ Browser fetch → wp-admin/admin-ajax.php                          │
│   → wp_ajax_eael_{action} OR wp_ajax_nopriv_eael_{action}        │
│   → Ajax_Handler trait method                                    │
│   → security triad: check_ajax_referer + current_user_can        │
│                     + sanitize input                             │
│   → WP_Query / WC API / Login_Registration logic                 │
│   → wp_send_json_success / wp_send_json_error                    │
└──────────────────────────────────────────────────────────────────┘
```

If you're tracing a bug, identify which phase / flow the failure lives in first — the trace path in the [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) skill mirrors these phases.

## The Docs

| Doc | Status | Covers |
| --- | ------ | ------ |
| [`asset-loading.md`](asset-loading.md) | ✅ | `Asset_Builder` lifecycle, `config.php` registry, detection in popups/templates/shortcodes, hook timing, caching, CSS print modes |
| [`editor-data-flow.md`](editor-data-flow.md) | ✅ | Settings persistence, `$settings` shape, `get_settings_for_display()`, Repeater / Group / Responsive control data, `condition` vs `conditions`, dynamic tags, `eael_e_optimized_markup()` |
| [`admin-notices.md`](admin-notices.md) | ✅ | Active `bfcm-pointer.php` campaign + dormant `WPDeveloper_Notice` class infrastructure, dismissal lifecycle, how to add a new campaign |
| [`dynamic-data/`](dynamic-data/) | ✅ | AJAX, WP_Query, load-more, login, WooCommerce, third-party integrations — folder of seven docs |
| └─ [`README.md`](dynamic-data/README.md) | ✅ | Subsystem index + the five dynamic-data flows |
| └─ [`ajax-endpoints.md`](dynamic-data/ajax-endpoints.md) | ✅ | Inventory of 18+ frontend `wp_ajax_*` actions + security triad |
| └─ [`wp-query-construction.md`](dynamic-data/wp-query-construction.md) | ✅ | Shared `Helper::get_query_args` query builder used by 8+ list widgets |
| └─ [`load-more-and-pagination.md`](dynamic-data/load-more-and-pagination.md) | ✅ | Click + infinite scroll mechanics, page state, isotope re-layout |
| └─ [`login-register.md`](dynamic-data/login-register.md) | ✅ | The 1,855-line `Login_Registration` trait — login / register / lost / reset flows |
| └─ [`woocommerce-integration.md`](dynamic-data/woocommerce-integration.md) | ✅ | EA-prefixed action mirrors, theme compat, eleven WC widgets, compare table |
| └─ [`third-party-integrations.md`](dynamic-data/third-party-integrations.md) | ✅ | ACF, EmbedPress, ten form plugins, compat shims, custom-meta integration |

## How These Docs Relate To Other Docs

| Layer | Path | Scope | When you read it |
| ----- | ---- | ----- | ---------------- |
| **Architecture** | `docs/architecture/` (you are here) | Plugin-wide subsystems | Tracing a bug across multiple widgets, designing a new cross-cutting feature, understanding hook timing |
| **Per-widget** | [`docs/widgets/`](../widgets/) | One widget each | Working on a specific widget, looking up its controls / hooks / quirks |
| **AI tooling** | [`.claude/skills/`](../../.claude/skills/), [`.claude/rules/`](../../.claude/rules/) | Workflows + conventions | When you invoke a Claude Code skill, or when writing code that should follow conventions |
| **Plugin entry** | [`CLAUDE.md`](../../CLAUDE.md) | Quick orientation for AI agents | First file an AI reads — terse pointers |

The four layers cross-link freely. Each architecture doc names the relevant skills + widget docs in its **Cross-References** section.

## Required Sections (per architecture doc)

Every doc in this folder follows the same 12-section structure. Predictable layout means readers always know where to look.

1. **Overview** — what this subsystem is, why it exists, the problem it solves
2. **Components** — files / classes / traits / hooks involved (with line counts so you know what you're walking into)
3. **Architecture Diagram** — ASCII flow / sequence / state diagram
4. **Hook Timing** — what fires when, in what priority, in what phase
5. **Data Flow** — step-by-step trace from input to output
6. **Configuration & Extension Points** — filters, actions, config entries this subsystem exposes
7. **Common Pitfalls** — edge cases, "things that bite contributors", surprising behavior
8. **Debugging Guide** — concrete steps when something in this subsystem is broken
9. **Worked Example** — a real walkthrough using actual EA code (not abstract)
10. **Architecture Decisions** — ADR-style records: context, decision, alternatives rejected, consequences
11. **Known Limitations** — perf nits, browser quirks, integration caveats
12. **Cross-References** — to skills, rules, related architecture and widget docs

## How to write a new architecture doc

1. Identify the subsystem boundary clearly. If it bleeds into another doc, either expand the boundary or split.
2. Survey the relevant code first — note line counts, hook usage, key methods. Put this in **Components**.
3. Build the **Architecture Diagram** before writing prose. The diagram is the spine of the doc.
4. Walk the 12-section checklist top to bottom. For sections that genuinely don't apply, write `N/A — <reason>` instead of deleting — predictable structure.
5. Cite real EA code in **Worked Example**. Abstract pseudocode is forbidden — readers come here for grounded answers.
6. Run the [conventions](#conventions) before opening the PR.

## Conventions

- Cross-link to source with line numbers: `[Asset_Builder::frontend_asset_load](../../includes/Classes/Asset_Builder.php#LXX)`
- Cross-link to skills and rules in `.claude/`
- Code blocks always specify a language (`php`, `js`, `scss`, `text` for diagrams)
- Tables surrounded by blank lines (markdown lint compliance)
- Section subtitles use `### Heading`, not `**Bold**`
- Hook timing tables: list hooks in fire order with priority + phase columns
- Diagrams: ASCII first; mermaid optional only if it adds clarity that ASCII can't
- Keep architecture decisions terse — one paragraph per decision, with the rejected alternative if relevant
