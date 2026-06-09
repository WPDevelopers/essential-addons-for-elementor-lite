# Admin Design System — initiative charter

> Folder index for the admin UI design-system initiative ([Issue #810](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/810)). Captures the verified state of the codebase, what the issue missed, and the phased plan with concrete acceptance per phase. Per-phase docs (audit, tokens, components, migrations) land in this folder as the work progresses.

## Context

Issue #810 is an **engineering proposal**, not just a doc write-up. It frames a five-phase plan (audit → tokens → primitives → migrate one surface → roll forward) to give the plugin's admin surfaces a shared design system. Today those surfaces are six hand-written CSS files plus two React sub-projects, none of which share tokens or component primitives. Brand purple `#5725ff` is consistent across surfaces; neutrals / greys / panel colours drift.

Unique among the cross-cutting docs initiative (#804–#807): this one ends with *new source files in `src/css/admin/`*, a webpack pipeline change, and ongoing migration work — not just a markdown deliverable.

## Verified facts

All facts from the issue check out against the current codebase. Adding facts the issue did not capture:

- **`src/` contains only `css/view/` and `js/`** — confirmed; no admin SCSS source ([`src/`](../../src/)).
- **Six hand-written admin CSS files** in `assets/admin/css/` — confirmed. Sizes:
  - `cloud.css` — 137 lines
  - `eael-templately-promo.css` — 225 lines
  - `eaicon.css` — 478 lines *(icon font; likely auto-generated)*
  - `editor.css` — 535 lines
  - `notice.css` — 280 lines
  - `quick-setup.css` — 7,224 lines *(the elephant in the room — 81% of total admin CSS)*
  - **Total: 8,879 lines**
- **No `_variables.scss` / `_mixins.scss`** — confirmed via `find src -name "_variables*" -o -name "_mixins*"` (returns nothing). [Issue rules][rules-wd] and [rules-ap] reference these as if they exist.
- **No minified admin CSS** — `assets/admin/css/*.min.css` does not match. Admin CSS is served raw; only widget CSS (`assets/front-end/css/view/`) goes through webpack.
- **Webpack discovers entries only from `src/css/view/*`** ([webpack.config.js line 26-39](../../webpack.config.js#L26)) — admin pipeline would need new entries.
- **Two React sub-projects live in the repo** *(issue does not mention these — they have shared design-system implications)*:
  - `includes/templates/admin/quick-setup/` — Vite + React 18 + `@wordpress/i18n`; `dist/` checked in
  - `includes/templates/admin/eael-dashboard/` — Vite + React 18; `App.css` + components; `dist/` checked in; README labels it "EA React Dashboard"
- **Icon-font source** at `includes/templates/admin/icons/` — feeds `eaicon.css`.
- **SweetAlert2 vendored** at [`assets/admin/vendor/sweetalert2/`](../../assets/admin/vendor/sweetalert2/) — confirmed.
- **Observable colour drift** (grep across admin CSS):
  - Brand purple `#5725ff` consistent
  - Greys: `#222`, `#333538`, `#69727d`, `#6d7882`, `#727272` — five values doing similar work
  - Whites: both `#fff` and `#ffffff` used inconsistently
  - Light panel fills: `#c0bbcf`, `#d2d3e0`, `#dbe0e9`, `#f3f6fb`, `#f4f4f4` — five tints with no clear hierarchy

[rules-wd]: ../../.claude/rules/widget-development.md
[rules-ap]: ../../.claude/rules/asset-pipeline.md

## What's missing

Gaps from the issue that need answers before / during the audit phase:

- **Two React sub-projects unaccounted for.** The issue mentions "Quick Setup is React" but treats it as a single surface. There are *two* React sub-projects — Quick Setup AND eael-dashboard. Tokens must work for both, plus the six CSS files. The audit must inventory both.
- **Where does Pro hook in?** The Pro plugin has its own admin CSS (not in this repo). Token sharing across the Lite/Pro boundary is undefined. Will Pro re-vendor the tokens? Read them via `:root` custom properties at runtime? Ignore them?
- **`quick-setup.css` is 7,224 lines** — 81% of admin CSS surface area. The issue's Phase 4 suggests migrating `notice.css` first ("smallest blast radius"). True — but the real migration cost lives in `quick-setup.css`. Plan needs a strategy for it: full rewrite alongside the React migration? Token-only retrofit? Phase 6 scope?
- **Webpack pipeline change scope undefined.** Phase 2 says "Wire into webpack so `assets/admin/css/_tokens.css` is buildable." How: new entry points in `webpack.config.js`? A separate admin-side webpack config? Vite (already used by the React projects)? Choice affects build commands, CI, and `npm run build` semantics.
- **No design-token bridging strategy for the React sub-projects.** SCSS `_tokens.scss` → CSS custom properties via build → React reads `var(--ea-color-primary)` at runtime is the conventional path, but neither React project currently has this wired up. Phase 2 should explicitly produce the CSS-custom-properties output the React side consumes.
- **No accessibility / contrast audit in scope.** The audit is documenting current state. Contrast ratios, focus styles, prefers-reduced-motion handling — should those be flagged during the audit, deferred, or out of scope entirely?
- **`eaicon.css` (478 lines) is an icon font** — auto-generated. Should be excluded from token migration but listed in the audit so future contributors know it's intentionally unmigrated.
- **RTL handling unmentioned.** WordPress plugins are RTL-aware by convention. Tokens should include direction-aware spacing helpers or the migration will produce RTL regressions.
- **No success metric.** "Migrate one surface" is the Phase 4 goal but the win condition is fuzzy. Suggest: zero visual regression on `notice.css` (screenshot diff), file size reduction (target: 280 → ~120 lines), reusable primitives (button, input, alert) consumed from `src/css/admin/components/`.

## Proposed location

Single audit doc proposed by the issue lands at `docs/design-system/audit.md`. Build the doc tree as the initiative progresses:

```text
docs/design-system/
├── README.md              ← this doc (initiative charter — verified state + phased plan)
├── audit.md               ← Phase 1 output
├── tokens.md              ← Phase 2 documentation (what each token represents, when to use which)
├── components.md          ← Phase 3 documentation (primitive API + usage rules)
└── migration-notice.md    ← Phase 4 case study (before/after, screenshots, decisions)
```

Per phase migrations (Phase 5) get follow-up issues — `migration-<surface>.md` per file as they land.

## Acceptance — what "done" looks like

Beyond the issue's checklist, the following should be verifiable on the closed state:

- [ ] **Phase 1 audit** — `docs/design-system/audit.md` lists every admin CSS file with: line count, identified tokens (colours, spacing, typography, radius, shadow), inconsistencies vs other surfaces, and a token-mapping recommendation
- [ ] **Phase 1 audit covers both React projects** — `quick-setup/` and `eael-dashboard/` styles are inventoried alongside the CSS files
- [ ] **Phase 2 tokens** — `src/css/admin/_tokens.scss` exists; webpack (or new pipeline) builds it to `assets/admin/css/_tokens.css` (CSS custom properties); buildable via `npm run build` without breaking widget CSS pipeline
- [ ] **Phase 2 tokens consumable from React** — Quick Setup and eael-dashboard import the same `_tokens.css` and use `var(--ea-color-primary)` etc. Verified by inspecting at least one component in each
- [ ] **Phase 3 primitives** — `src/css/admin/components/_button.scss`, `_input.scss`, `_alert.scss` at minimum; documented variants and the cases they cover
- [ ] **Phase 4 `notice.css` migrated** — re-implemented using tokens + primitives; visual diff zero (or documented intentional deviation); line count reduced
- [ ] **Rules updated** — `.claude/rules/widget-development.md` and `.claude/rules/asset-pipeline.md` either point to real files or remove the references. No aspirational paths left.
- [ ] **Pro coordination** — note in `audit.md` documenting the Lite/Pro boundary decision (CSS-custom-properties shared via `:root`, or Pro re-vendors, or other). Pro can read the decision and adopt accordingly.
- [ ] **Phase 5 deferred** — follow-up issues opened per migrated surface; `quick-setup.css` migration specifically split out as its own multi-phase issue given the 7,224-line scope

## Related

- Part of cross-cutting docs initiative #804 (architecture).
- Closed siblings #805–#807 — narrower scope (single architecture doc per issue). This one differs by ending with **shipped code + a webpack-pipeline change**, not only docs.
- Quick Setup architecture doc: [`docs/architecture/quick-setup.md`](../architecture/quick-setup.md) — overlaps with this initiative; tokens must work for the wizard.
- Admin notices doc: [`docs/architecture/admin-notices.md`](../architecture/admin-notices.md) — `notice.css` is the Phase 4 target; cross-link when migration lands.

## Out of scope (carried from issue, restated)

- Front-end widget styling (BEM, no shared tokens by design — front-end widgets stay as-is for now)
- Replacing SweetAlert2
- Dark mode / theme support (defer until tokens exist)

## Open questions for the next planning round

- Pro plugin's design-system stance — adopt, ignore, or evolve in parallel?
- Webpack vs Vite for admin SCSS — extend existing webpack or create a parallel Vite pipeline matching the React projects?
- Visual-regression test infrastructure — does `notice.css` migration need a Playwright screenshot baseline, or is manual review enough for Phase 4?
