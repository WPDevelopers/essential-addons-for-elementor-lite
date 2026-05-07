# Session Summary — Claude Code Setup + Architecture Documentation

> এই document-এ বলা আছে এই session-এ EA Lite plugin-এ কী কী কাজ হয়েছে, কোথায় কী file আছে, কীভাবে use করতে হয়, এবং পরে কী করতে হবে।

**Date:** 2026-05-07
**Plugin:** Essential Addons for Elementor (Lite)
**Goal:** Plugin-কে Claude Code-এর জন্য পুরোপুরি optimized করা — skills, commands, rules, এবং comprehensive documentation।

---

## 🎯 কী করা হয়েছে — Overview

এই session-এ ৩টা বড় কাজ হয়েছে:

1. **`.claude/` infrastructure** তৈরি — skills + commands + team guide (Claude Code agent-এর জন্য)
2. **Per-widget documentation** শুরু — `docs/widgets/` folder (Fancy_Text canonical example)
3. **Architecture documentation** complete — `docs/architecture/` folder, GitHub issue #804-এর full coverage

**Total artifacts created:** ~৫,০০০ lines documentation + tooling।

---

## 📊 Big Numbers

| Category | Files | Lines |
|----------|-------|-------|
| `.claude/skills/` | 7 SKILL.md | ~975 |
| `.claude/commands/` | 7 commands | ~170 |
| `.claude/README.md` (team guide) | 1 | 234 |
| `.claude/rules/` | 4 (already existed) | ~150 |
| `docs/widgets/` | 2 (README + fancy-text) | ~430 |
| `docs/architecture/` | 10 docs | 2,938 |
| **Total new content** | **~25 files** | **~4,927** |

---

## 🛠 Phase 1 — `.claude/` Infrastructure

### ৭টা Skills তৈরি

প্রতিটা skill একটা specific workflow encode করে। Natural prompt-এও auto-trigger হবে, slash command-এও invoke হবে।

| # | Skill | Lines | কী করে |
|---|-------|-------|---------|
| 1 | **widget-review** | 155 | এক widget-এর five-axis senior audit (correctness, security, i18n, asset hygiene, architecture) |
| 2 | **debug-widget** | 81 | Widget bug debug — reproduce → localize (4-question tree) → trace (6 paths) → fix-at-root → guard with Playwright |
| 3 | **elementor-controls** | 110 | Elementor controls design/audit — tab placement, control types, conditions, Pro/Lite split pattern |
| 4 | **new-widget** | 165 | নতুন widget scaffold — PHP class + SCSS + JS + config.php + Pro upsell + build + verify (9 phases) |
| 5 | **release-checklist** | 117 | WordPress.org release pre-flight — 7 phases, version bump in 3 files, .distignore audit, rollback plan |
| 6 | **pr-workflow** | 168 | Feature branch + atomic commit + PR। Hard refusal on push to main। FluentBoards card-based naming |
| 7 | **nopriv-ajax-hardening** | 176 | `wp_ajax_nopriv_*` security audit — visibility leak prevention, fix pattern with SECURITY: comments |

**প্রতিটা skill-এ কী থাকে:**
- Frontmatter (trigger keywords for auto-load)
- When to Invoke
- Required Inputs
- Step/Phase-by-step workflow
- Operating Rules (hard guarantees)
- Output Report format
- Cross-references to other skills/rules

### ৭টা Slash Commands

প্রতিটা skill-এর জন্য একটা thin wrapper command:

| Command | Skill | Use |
|---------|-------|-----|
| `/review <widget>` | widget-review | "Adv_Accordion review করো" |
| `/debug <widget>` | debug-widget | "Fancy_Text broken — debug করো" |
| `/controls <widget>` | elementor-controls | "Cta_Box-এ hover color control add করো" |
| `/new-widget <ClassName>` | new-widget | "Marquee_Text widget বানাও" |
| `/pr <card-number>` | pr-workflow | "Card #1234 — PR দাও" |
| `/release <X.Y.Z>` | release-checklist | "6.6.4 release কর" |
| `/audit-nopriv [handler]` | nopriv-ajax-hardening | Security audit |

**Pattern:** প্রতিটা command-এ frontmatter (`description`, `argument-hint`) আছে, body শুধু skill invoke করে।

### `.claude/README.md` — Team Guide (234 lines)

Team member-দের জন্য guide:
- Quick Start (slash vs natural prompt)
- At a Glance table (সব commands)
- Per-skill detail section (3 examples each)
- Skill Chains (combo workflows — bug fix → ship, etc.)
- Rules & Conventions pointers

### Memory Saved

User preference saved — "Rules-first foundation" feedback। Future sessions-এ Claude জানবে যে skills-এর আগে rules audit করতে হবে।

---

## 📚 Phase 2 — Per-Widget Documentation

### `docs/widgets/` Folder

**Audience:** Developers (যেকোনো team member, future contributors)। PM-readable structure, anyone can use to modify/debug/fix bugs।

| File | Lines | কী আছে |
|------|-------|--------|
| `README.md` | 56 | Folder intro, 19-section checklist, "How to write a new widget doc" |
| `fancy-text.md` | 367 | Fancy_Text-এর canonical comprehensive doc — model for all future widgets |

### Per-Widget Doc Structure (19 sections)

```
1. Overview (plain language)
2. Features (user-facing)
3. Pro vs Lite matrix
4. Use Cases
5. File Map
6. Architecture (the why)
7. Render Output (DOM tree)
8. Controls Reference (id/type/default/affects)
9. Conditional Dependencies (when does X show?)
10. Behavior Flow (drop → render → JS init)
11. JavaScript Lifecycle
12. Asset Dependencies
13. Hooks & Filters
14. Customization Recipes (copy-paste ready)
15. Common Issues (FAQ)
16. Testing Checklist
17. Architecture Decisions
18. Known Limitations
19. Recent Significant Changes
```

### Lazy-fill Philosophy

৬১টা widget আছে — সব এক সাথে document না। **Pattern:** যখন কোনো widget-এ work করতে হবে, তখন তার doc create/update করব। ছয় মাসে most-used widgets covered হবে। `pr-workflow` skill-এ "Widget Doc Updated?" checkbox থাকবে drift prevent করতে।

---

## 🏗️ Phase 3 — Architecture Documentation (Issue #804)

GitHub Issue #804: "Plugin-এর internal systems-এর proper documentation নেই"। তিনটা area: Asset Loading, Editor↔Frontend Data Flow, Dynamic Widget Query Layer।

আমাদের solution issue-এর চেয়ে better:
- Issue proposed 3 flat files
- আমরা সাজিয়েছি system map সহ + sub-folder for dynamic-data (কারণ ৬,০০০+ lines code কভার করতে হয়েছে)
- প্রতিটা doc-এ "Common Pitfalls" + "Debugging Guide" + "Worked Example" + "Architecture Decisions" সহ

### `docs/architecture/` — 10 Files, 2,938 Lines

```
architecture/
├── README.md                          (132)  ← System map + index
├── asset-loading.md                   (321)  ← Asset_Builder lifecycle
├── editor-data-flow.md                (387)  ← Settings → render flow
└── dynamic-data/                              ← Folder (not single file)
    ├── README.md                       (122)  ← Folder index + 5 flows
    ├── ajax-endpoints.md               (307)  ← 18+ endpoints + nonces
    ├── wp-query-construction.md        (320)  ← Helper::get_query_args
    ├── load-more-and-pagination.md     (305)  ← Click + infinite scroll
    ├── login-register.md               (378)  ← 1,855-line trait
    ├── woocommerce-integration.md      (352)  ← 11 WC widgets
    └── third-party-integrations.md     (314)  ← ACF + EmbedPress + 10 forms
```

### প্রতিটা Architecture Doc-এ কী আছে (12 sections)

```
1. Overview — what this subsystem is
2. Components — files / classes / traits with line counts
3. Architecture Diagram — ASCII flow / sequence
4. Hook Timing — fire order, priorities, phases
5. Data Flow — step-by-step trace
6. Configuration & Extension Points — filters, actions
7. Common Pitfalls — edge cases, "things that bite"
8. Debugging Guide — concrete diagnostic steps
9. Worked Example — real EA code walkthrough
10. Architecture Decisions — ADR-style for major choices
11. Known Limitations — perf/edge cases
12. Cross-References — to skills, rules, sibling docs
```

### Issue #804-এর Sub-questions Coverage — 100%

| Issue area | Sub-question | Doc |
|------------|--------------|-----|
| **Asset Loading** | Widget detection mechanism | `asset-loading.md` |
| | Popup/template/AJAX handling | `asset-loading.md` |
| | Hook ordering | `asset-loading.md` § Hook Timing |
| | Cache strategy | `asset-loading.md` |
| | Internal vs external CSS | `asset-loading.md` |
| | `context` flags inventory | `asset-loading.md` |
| **Editor Data Flow** | Settings persistence | `editor-data-flow.md` |
| | `$settings` shape | `editor-data-flow.md` § Reference |
| | Repeater/Group/Responsive data | `editor-data-flow.md` |
| | `condition` vs `conditions` | `editor-data-flow.md` § dedicated section |
| | `get_settings_for_display()` | `editor-data-flow.md` |
| | Dynamic Tag support | `editor-data-flow.md` |
| | `eael_e_optimized_markup()` | `editor-data-flow.md` § ADR |
| **Dynamic Data** | WP_Query construction | `wp-query-construction.md` |
| | AJAX endpoint inventory | `ajax-endpoints.md` |
| | Pagination / load-more | `load-more-and-pagination.md` |
| | WooCommerce integration | `woocommerce-integration.md` |
| | Login/Register flow | `login-register.md` |
| | Nonce / security | All docs + `nopriv-ajax-hardening` skill |
| | Caching | `asset-loading.md` + `wp-query-construction.md` |
| | ACF / custom field | `third-party-integrations.md` |

---

## 📁 Final File Structure

```
essential-addons-for-elementor-lite/
│
├── CLAUDE.md                                  ← AI agent entry (already existed)
│
├── .claude/                                   ← AI tooling
│   ├── README.md                              (234) ✅ team guide
│   ├── settings.json
│   ├── rules/                                 (4 files, ~150 lines, already existed)
│   │   ├── asset-pipeline.md
│   │   ├── php-standards.md
│   │   ├── testing.md
│   │   └── widget-development.md
│   ├── commands/                              (7 thin wrappers, ~170 lines)
│   │   ├── audit-nopriv.md
│   │   ├── controls.md
│   │   ├── debug.md
│   │   ├── new-widget.md
│   │   ├── pr.md
│   │   ├── release.md
│   │   └── review.md
│   ├── skills/                                (7 SKILL.md, ~975 lines)
│   │   ├── debug-widget/SKILL.md
│   │   ├── elementor-controls/SKILL.md
│   │   ├── new-widget/SKILL.md
│   │   ├── nopriv-ajax-hardening/SKILL.md
│   │   ├── pr-workflow/SKILL.md
│   │   ├── release-checklist/SKILL.md
│   │   └── widget-review/SKILL.md
│   └── agents/                                (empty — for future use)
│
└── docs/                                      ← Human/AI documentation
    ├── SESSION-SUMMARY.md                     (this file)
    ├── widgets/                               (lazy-fill, 1 done so far)
    │   ├── README.md                          (56)
    │   └── fancy-text.md                      (367) ← canonical example
    └── architecture/                          (complete)
        ├── README.md                          (132)
        ├── asset-loading.md                   (321)
        ├── editor-data-flow.md                (387)
        └── dynamic-data/
            ├── README.md                      (122)
            ├── ajax-endpoints.md              (307)
            ├── wp-query-construction.md       (320)
            ├── load-more-and-pagination.md    (305)
            ├── login-register.md              (378)
            ├── woocommerce-integration.md     (352)
            └── third-party-integrations.md    (314)
```

---

## 🚀 কীভাবে Use করবেন

### Daily Workflow

**যেকোনো widget-এ কাজ করার সময়:**

```
১. Doc check — docs/widgets/<slug>.md আছে?
   - হ্যাঁ → পড়ে context নাও
   - নাই → কাজ শেষে create/update করো

২. Cross-cutting questions — docs/architecture/ দেখো:
   - "Asset কেন load হচ্ছে না?" → asset-loading.md
   - "Setting কেন reach করছে না render-এ?" → editor-data-flow.md
   - "AJAX 403 দিচ্ছে কেন?" → dynamic-data/ajax-endpoints.md

৩. Skill invoke করো প্রয়োজন অনুযায়ী:
   - Bug fix → /debug FancyText
   - Code merge-এর আগে → /review FancyText
   - Add control → /controls FancyText
   - Ship → /pr 1234

৪. Skill chain works:
   debug-widget → fix → pr-workflow
   widget-review → fix → pr-workflow
   new-widget → elementor-controls → pr-workflow
```

### Team Onboarding

নতুন dev join করলে এই order-এ দেখাবেন:

1. **`.claude/README.md`** — Claude Code কীভাবে use করবে
2. **`docs/architecture/README.md`** — Plugin-এর system map
3. **`docs/widgets/fancy-text.md`** — একটা real widget-এর full doc কেমন দেখায়
4. **`CLAUDE.md`** — top-level pointers
5. Specific area-এর architecture doc যেটায় কাজ করবে

### Issue #804 GitHub-এ Reply

Issue close করার সময় বলতে পারেন:

> **All three documentation areas covered + more:**
> - `docs/architecture/asset-loading.md` (321 lines)
> - `docs/architecture/editor-data-flow.md` (387 lines)
> - `docs/architecture/dynamic-data/` folder (7 docs, 2,098 lines)
>
> Beyond what was asked: system map, cross-links to skills/rules, common pitfalls per doc, real worked examples from EA code, ADR-style architecture decisions, debugging guides.
>
> Total: 2,938 lines of architecture documentation across 10 files.

---

## ⏳ যা Pending আছে (Deferred Items)

পরে করার জন্য রেখে দেওয়া হয়েছে:

### Skills consistency cleanup (~6 fixes, ~16 min)
- `nopriv-ajax-hardening` — section heading rename + single-line description
- `widget-review` — section rename + Option A refactor (155 → ~110)
- `new-widget` — Output Report structured template
- FA5 syntax check in widget-review

### Rules expansion (P0 originally)
- `pr-workflow` skill-এ "Widget Doc Updated?" PR checkbox যোগ করা
- `php-standards.md` expansion (hook deprecation + AJAX security sections)
- `widget-development.md` expansion (render conventions + controls deep)
- `pro-lite-split.md` — পরে বাদ দেওয়া হয়েছে (Pro repo-এ আলাদা ভাবে হবে)

### Per-widget docs expansion (lazy-fill)
- ৬০+ widgets বাকি (Adv_Accordion, Countdown, Post_Grid, Login_Register Form, ইত্যাদি)
- Strategy: যখন কোনো widget-এ work হবে, তার doc তৈরি বা update করা হবে

### Other docs/ folders (originally Phase 1 of docs plan)
- `docs/getting-started/` — local setup walkthrough for new devs
- `docs/contribution/` — branch + commit + PR conventions for external contributors
- `docs/extensions/` — what's an Extension vs Widget; per-extension docs (12 extensions)
- `docs/traits/` — trait composition guide; per-trait docs (15 traits)

---

## 🎯 Highlights — কী Special

### Skills Are Workflows, Not Prompts

প্রতিটা skill একটা encoded process — Claude শুধু randomly উত্তর দেয় না, structured workflow follow করে। `debug-widget` skill যেমন:
- Reproduce আগে (প্রমাণ ছাড়া fix নয়)
- 4-question decision tree (file না খুলে narrow)
- 6-row lookup table (path → confirming signal)
- 5-whys for root cause (symptom-patch নয়)
- Mandatory Playwright spec (regression guard)

### Architecture Docs Aren't Just "What" — They're "Why"

প্রতিটা architecture doc-এ ADR-style "Architecture Decisions" section আছে — কেন এই design choice হয়েছিল, কী alternatives consider করা হয়েছিল, কেন reject হয়েছিল, consequences কী।

উদাহরণ: `asset-loading.md`-এ আমরা document করেছি কেন per-post bundles bundles ব্যবহার হয়, কেন `_eael_widget_elements` post meta (transient না), কেন `replace_widget_name` legacy map permanent হবে।

### Cross-Linked Documentation Network

প্রতিটা doc অন্যদের সাথে link করা — Reader যেদিক থেকে আসুক, সম্পূর্ণ পাবে:
- Skill → architecture doc → widget doc → source code
- Architecture doc → related skill → related rule → sibling architecture doc

### Worked Examples Use Real EA Code

প্রতিটা architecture doc-এ একটা "Worked Example" section আছে — abstract pseudocode না, EA-এর actual code দিয়ে walkthrough। উদাহরণ:
- `asset-loading.md`: Fancy_Text on a single page — full pipeline trace
- `wp-query-construction.md`: Post_Grid query for posts in 2 categories
- `load-more-and-pagination.md`: Random orderby with post__not_in tracking
- `login-register.md`: Register with custom phone field + auto-login + custom email
- `woocommerce-integration.md`: Add to compare flow with cookie persistence
- `third-party-integrations.md`: Dynamic Filterable Gallery hybrid ACF query

### Issue Coverage > Issue Asked

Issue #804 চেয়েছিল ৩টা flat doc। আমরা delivered:
- **Better organization:** dynamic-data sub-folder (1 file → 7 files for ৬,০০০+ lines)
- **System map:** issue-এ ছিল না, আমরা যোগ করেছি (architecture/README.md)
- **AI tooling cross-link:** issue-এ ছিল না, আমরা skills/rules-এর সাথে যুক্ত করেছি
- **Beyond docs:** ৭টা skills + ৭টা commands + team README + per-widget doc পদ্ধতি — issue-এ ছিল না, আমরা bonus হিসেবে added

---

## 📞 Questions / Issues / Future Work

পরবর্তী session-এ যা হতে পারে:

1. **Pending consistency fixes apply** (~16 min)
2. **PR checkbox enforcement** in pr-workflow
3. **Top widgets doc** — git log দিয়ে most-edited widgets identify করে document
4. **Extensions/traits docs** — Phase 3 of original docs plan
5. **Getting-started + contribution docs** — Phase 1 of original docs plan
6. **Smoke test** — real prompt দিয়ে docs verify ("how does Asset_Builder detect widgets in popups?" — uchit answer doc থেকে আসা উচিত)

---

## 🙏 Notes

এই session সম্পূর্ণ করার জন্য Claude এই tools/conventions ব্যবহার করেছে:

- **Read** tool — actual EA code পড়ে সব line numbers verify
- **grep / Bash** — surveys (AJAX endpoint count, control type usage stats, etc.)
- **Edit / Write** — surgical file changes
- **TodoWrite** — multi-step task tracking
- **Markdown lint compliance** — bare URLs in `<>`, tables with blank lines, language-specified code blocks

**Conventions established:**
- Per-doc 12 or 19 section structure (consistency)
- ASCII diagrams (no mermaid dependency)
- Cross-links use relative paths (`../../includes/...`)
- Always specify language in code blocks
- Real EA code in worked examples, no abstract pseudocode

---

**End of Session Summary**

পুরো কাজ এই document-এ আছে। ভবিষ্যতে কেউ জিজ্ঞেস করলে এটা share করতে পারেন। প্রশ্ন থাকলে ask করুন।
