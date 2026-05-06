# Claude Code — Team Guide

This folder gives your Claude Code agent a shared brain for working on Essential Addons (Lite). It encodes how we name branches, audit widgets, debug issues, scaffold new widgets, harden AJAX endpoints, and ship releases — so every team member gets the same answers.

## Quick Start

Two ways to invoke a skill:

1. **Slash command** — type `/<name> <args>` in Claude Code, e.g. `/review Fancy_Text`. Auto-complete shows all available commands.
2. **Natural prompt** — describe the task in your own words, e.g. *"Review the Fancy_Text widget"*. The skill auto-loads when its trigger phrases match.

Both routes invoke the same workflow. Slash is faster when you know the command; natural prompts work better when chaining ideas.

## At a Glance

| Command | Skill | What it does |
|---------|-------|--------------|
| `/review <widget>` | [widget-review](skills/widget-review/SKILL.md) | Five-axis senior audit of a widget |
| `/debug <widget>` | [debug-widget](skills/debug-widget/SKILL.md) | Root-cause debugging with regression test |
| `/controls <widget>` | [elementor-controls](skills/elementor-controls/SKILL.md) | Design or audit Elementor controls |
| `/new-widget <Class>` | [new-widget](skills/new-widget/SKILL.md) | Scaffold a new widget end-to-end |
| `/pr <card-number>` | [pr-workflow](skills/pr-workflow/SKILL.md) | Feature branch → atomic commit → PR |
| `/release <X.Y.Z>` | [release-checklist](skills/release-checklist/SKILL.md) | Pre-flight checklist for WordPress.org |
| `/audit-nopriv [handler]` | [nopriv-ajax-hardening](skills/nopriv-ajax-hardening/SKILL.md) | Security audit for `wp_ajax_nopriv_*` |

---

## Skills in Detail

### `/review` — Widget Review

**What it does:**
- Audits one widget across five axes — correctness, security, i18n, asset hygiene, architecture.
- Produces a structured report with `file:line` references and Critical / Important / Nit / Strengths grouping.
- Read-only by default — no edits unless you say "fix it".

**When to use:**
- Before merging a PR that touches `includes/Elements/`
- After porting a widget between Lite and Pro
- When QA reports a problem and you're not sure where to start

**Examples:**
```
/review Adv_Accordion
/review includes/Elements/Fancy_Text.php
"Review the Countdown widget for security and i18n"
```

---

### `/debug` — Debug Widget

**What it does:**
- Walks Reproduce → Localize → Trace → Fix-at-root → Guard. Each step ends with a confirming signal so you don't fix the wrong thing.
- Knows EA-specific traps: `Asset_Builder` detection gaps, `eael.elementStatusCheck` double-init, AJAX security triad.
- Adds a Playwright regression test before declaring the fix done.

**When to use:**
- A widget is reported broken
- A fix shipped but the bug still reproduces
- Editor preview ≠ frontend output
- CSS/JS not loading after `npm run build`

**Examples:**
```
/debug Fancy_Text
"Countdown shows wrong timezone on the frontend"
"Filterable_Gallery filter buttons not clickable in editor"
```

---

### `/controls` — Elementor Controls

**What it does:**
- Decides the right tab, control type, conditions, responsive flags using lookup tables tuned to EA's real usage.
- Outputs PHP that already follows EA conventions — `eael_{slug}_*` IDs, `{{WRAPPER}}`-scoped selectors, sensible defaults.
- Bakes in the Pro/Lite split pattern (HEADING alert + force-fallback in `render()` + prefixed `eael/{slug}_*` filter).

**When to use:**
- Adding controls to a new or existing widget
- Refactoring a 1000-line `register_controls()`
- Choosing between `condition` and `conditions`, or between `SLIDER` and `NUMBER`

**Examples:**
```
/controls Fancy_Text
"Add a hover color control to Cta_Box's button"
"Refactor Adv_Accordion controls — add Normal/Hover tabs"
```

---

### `/new-widget` — Scaffold a New Widget

**What it does:**
- Asks 6 requirement questions first (interactivity, Pro-shared, vendor libs, initial controls) — won't generate anything until they're answered.
- Generates PHP class, SCSS, optional JS, `config.php` registration, optional Pro upsell — all using the same slug, no drift.
- Runs `npm run build` and verifies the widget appears in the editor before declaring done.

**When to use:**
- Building a new widget for a FluentBoards card
- Even for "simple display" widgets — saves boilerplate and convention drift

**Examples:**
```
/new-widget Marquee_Text
/new-widget Pricing_Comparison
"Create a new widget called Logo_Carousel"
```

---

### `/pr` — Pull Request Workflow

**What it does:**
- Creates a feature branch named `<card-number>-<specific-slug>` (number first for fast `grep ^N` and tab-complete).
- Stages selectively, runs the husky pre-commit hook honestly (no `--no-verify`), pushes the feature branch, opens a PR with structured body and FluentBoards card link.
- **Hard refuses** any push to `main` / `master` / `trunk`.

**When to use:**
- After any change you intend to merge — fix, feature, refactor, docs
- Even small typo fixes go through PR (no exceptions, no direct main pushes)

**Examples:**
```
/pr 1234
"Card #5678 — fix done, push and PR"
"Ship this fix as PR for card 9012"
```

**Branch slug must be specific** — `1234-fix` is rejected; `1234-fancy-text-pro-description-i18n` is good.

---

### `/release` — Release Checklist

**What it does:**
- Walks all 7 phases in order: pre-flight → quality gates → version bump (3 files) → changelog → distignore audit → tag + deploy → post-release verify.
- No phase is skippable — even hotfixes go through every gate.
- Includes a rollback plan if production verification fails.

**When to use:**
- Cutting any release (patch / minor / major / hotfix)
- Diagnosing why wordpress.org shows a stale version

**Examples:**
```
/release 6.6.4
"Prepare 6.7.0 minor release with the new Marquee_Text widget"
"Why is wordpress.org still showing 6.6.2?"
```

**Three version locations must match:** plugin header `Version:` (line 7), `EAEL_PLUGIN_VERSION` (line 30), `readme.txt` Stable tag (line 7).

---

### `/audit-nopriv` — nopriv AJAX Hardening

**What it does:**
- Runs five grep recipes to enumerate every `wp_ajax_nopriv_*` handler and dangerous `WP_Query` pattern in the plugin.
- Walks each handler through a 5-question audit (post_status overrides, post_type=any, untrusted post__in/author, suspect nonce sources).
- Provides a drop-in fix pattern (strip-and-redefault block + `SECURITY:` inline comments) so future audits catch regressions via grep.

**When to use:**
- Auditing AJAX endpoints during a security pass
- Vulnerability report mentions "unauthenticated info disclosure" / "draft exposure"
- After adding any new `nopriv` AJAX handler in `includes/Traits/Ajax_Handler.php`

**Examples:**
```
/audit-nopriv
/audit-nopriv ajax_load_more
"Audit Ajax_Handler for unauthenticated post leaks"
```

---

## Skill Chains — Common Workflows

Most real tasks chain two or three skills together. Claude will invoke them in sequence on a single prompt.

### Bug fix → ship
```
"Card #1234 — Fancy_Text typing animation not running. Debug and ship."
```
Chain: `debug-widget` → `pr-workflow`. Output: root-cause fix + Playwright spec + PR linked to card.

### Widget review → fix → ship
```
"Review Adv_Accordion, then fix the Critical findings on card #5678."
```
Chain: `widget-review` → (apply fixes) → `pr-workflow`.

### New widget → controls → ship
```
"Card #9012 — scaffold Marquee_Text widget, add 4 content controls + 2 style controls, then PR."
```
Chain: `new-widget` → `elementor-controls` → `pr-workflow`.

### Security audit → coordinated fix → release
```
"Audit nopriv AJAX, then prepare 6.6.5 hotfix release."
```
Chain: `nopriv-ajax-hardening` → (apply fixes per pr-workflow) → `release-checklist`.

---

## Rules & Conventions

The skills reference shared conventions in [`rules/`](rules/) — read these once, they're the source of truth:

- [`widget-development.md`](rules/widget-development.md) — widget creation checklist, controls, render method, asset naming
- [`php-standards.md`](rules/php-standards.md) — naming, namespacing, i18n, security, hooks
- [`asset-pipeline.md`](rules/asset-pipeline.md) — build commands, source→output map, FA5 / Swiper handle rules
- [`testing.md`](rules/testing.md) — Playwright E2E setup and adding-a-test workflow

Plugin-wide architecture (Bootstrap traits, Asset_Builder, config.php registry) is in the root [`CLAUDE.md`](../CLAUDE.md).

---

## Tips

- **Both routes work.** If `/review` doesn't auto-complete, just type `"review the Fancy_Text widget"` — same skill loads.
- **Skills can refuse to start** if required input is missing. They'll ask — don't guess and prompt again, just answer.
- **`/pr` requires a card number.** No card → no branch. Get the number from your FluentBoards card before invoking.
- **Read the skill file** if you want the deep workflow. Each `skills/<name>/SKILL.md` is self-contained.
- **Check `/help` and `/`** in Claude Code to see all available commands inline.

---

## Maintenance

When you add or modify a skill or command, update the **At a Glance** table and the corresponding **Skills in Detail** section so the team's quick reference stays accurate. The skill files themselves are the source of truth — this README is the friendly index.
