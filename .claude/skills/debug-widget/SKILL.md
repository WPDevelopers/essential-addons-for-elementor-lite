---
name: debug-widget
description: Systematic root-cause debugging for an EA widget. Use when a widget is reported broken, a fix didn't take effect, editor and frontend diverge, or asset/AJAX failures are suspected. Reproduce → localize → trace → fix-at-root → guard. Every step ends with a confirming signal — no guesses.
---

# Debug Widget

## When to Invoke

- "Widget X is broken" / fix shipped but bug still reproduces
- Editor preview ≠ frontend output
- Asset (CSS/JS) not loading, console error, AJAX 4xx/5xx
- Behavior changed after `npm run build` or plugin update

## Required Inputs

Widget identifier · symptom (expected vs actual) · environment (frontend / editor / both, logged-in?) · recent changes (last commit, build, version bump). Missing input → ask, don't guess.

## Step 1 — Reproduce

A bug you can't reproduce, you can't fix.

1. `npx wp-env start` if needed → open `http://localhost:8888`
2. Build a minimal Elementor page with **only** the target widget at default settings
3. Confirm symptom appears. Can't reproduce → stop, ask for exact steps / page URL / settings export
4. Note exact symptom: visual, console error, network failure, or PHP notice

## Step 2 — Localize (decision tree)

Narrow the failure surface before opening files.

```
Q1. Frontend / editor / both?      → "editor only" → trace path: Editor-mismatch
Q2. Is `.eael-{slug}` HTML present? → no  → trace path: Render
Q3. Are `{slug}.min.css/.min.js` loaded (Network tab)? → no → trace path: Asset
Q4. Console errors?  → yes → trace path: JS init
    AJAX 4xx/5xx?    → yes → trace path: AJAX
    No errors but visual still wrong → trace path: Visual/CSS
```

## Step 3 — Trace by Category

Each path ends with a **confirming signal**. Don't fix until the signal matches — if it doesn't, you're in the wrong path; re-localize.

| Path | Where to look | EA-specific gotcha | Confirming signal |
|------|---------------|-------------------|-------------------|
| **Render** | `render()` in `includes/Elements/{Widget}.php`; tail `wp-content/debug.log` (`wp config set WP_DEBUG_LOG true`) | Early-return on empty settings; PHP notice suppressing output | error-log line or specific failing `if`-branch |
| **Asset** | `config.php` slug entry; built file at `assets/front-end/.../{slug}.min.{css,js}`; **`Asset_Builder` detection** | `Asset_Builder` walks Elementor doc data — misses widgets inside templates, popups, global widgets, or shortcodes. Bump `EAEL_PLUGIN_VERSION` / regen Elementor CSS if cache stale | file 200 in Network OR identified gap (missing build, missing dep, missed detection context) |
| **JS init** | `src/js/view/{slug}.js` source (not `.min.js`) | Standard pattern: `elementorFrontend.hooks.addAction("frontend/element_ready/eael-{slug}.default", Fn)` guarded by `eael.elementStatusCheck('eael{Slug}Load')`. Common breaks: missing guard → double-init; vendor lib (Typed/Swiper/Morphext) load order in `config.php` deps; `isEditMode` code leaking to frontend | console error → file:line in source |
| **AJAX** | `wp_ajax_*` in `includes/Traits/Ajax_Handler.php`; Network → request payload + response | **Security triad**: `check_ajax_referer()` nonce + `current_user_can()` cap + sanitization. Most common: nonce mismatch (re-login) or `wp_send_json_error()` early on validation | matched handler with explicit failure reason |
| **Editor-mismatch** | `Elementor::instance()->editor->is_edit_mode()` branches; `is_dynamic_content()`; editor-only enqueue context in `config.php` | Edit-iframe preview uses different CSS/JS context than frontend | identified diverging conditional |
| **Visual/CSS** | DevTools Elements: classes match `render()`?; SCSS source rule exists?; `grep` rule in built `.min.css` (regen if missing); specificity vs theme/Elementor globals | Source rule present but build missing → `npm run build` | identified missing / overridden / wrong-selector rule |

## Step 4 — Root-Cause, Don't Patch

Ask **"why?"** until the answer has no parent. A symptom-patch ships fast and breaks something else next week. If you can't reach a root cause in 5 levels of "why?", stop and report what you've narrowed — don't ship a guess.

## Step 5 — Fix, Guard, Verify

1. **Fix** smallest scope at the root cause. Don't touch unrelated code; flag adjacent issues separately. `npm run build` if `src/` was touched.
2. **Guard** per [.claude/rules/testing.md](../../rules/testing.md): export Elementor template → register in `tests/e2e/utils/seed.sh` → write `tests/e2e/specs/{widget-slug}-{bug-id}.spec.ts` with one assertion that fails on broken code, passes on fixed → `npm run test:e2e` green.
3. **Verify** on `http://localhost:8888` with original repro steps. After cache clear / rebuild, also test in a private window (cold load).

## Output Report

```markdown
## Debug Report: {Widget} — {one-line symptom}
**Repro:** {steps}
**Root cause:** {one sentence — what was actually wrong}
**Fix:** {file}:{lines} — {one-sentence change}
**Guard:** {tests/e2e/specs/...} — {what it asserts}
**Verified on:** {browsers, URL, date}
**Out of scope (noted, not fixed):** {adjacent issues}
```

## Operating Rules

1. **Reproduce first.** No repro = no proof of fix.
2. **One bug per pass.** Adjacent issues → write down, don't fix in this scope.
3. **Confirming signal required.** Step 3 path without a matched signal = wrong path; re-localize.
4. **Stop and report at 30 min** of unsuccessful narrowing. Silent looping wastes more time than asking.
