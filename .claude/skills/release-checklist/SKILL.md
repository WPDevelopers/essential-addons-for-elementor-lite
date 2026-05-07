---
name: release-checklist
description: Pre-flight checklist for shipping an EA Lite release to WordPress.org. Use when bumping a version, preparing a release branch, building a distribution zip, or before tagging. Walks through quality gates, version bump in three required files, changelog format, .distignore audit, and post-release verification. No phase is skippable — wordpress.org publishes whatever the Stable tag points to.
---

# Release Checklist

## When to Invoke

- Cutting a new release (patch / minor / major)
- After feature freeze, before tagging
- Preparing a hotfix
- Diagnosing why a release looked wrong on wordpress.org (cache, version mismatch, distignore leak)

## Required Inputs

Target version (semver) · release type (patch / minor / major) · summary of what changed (for changelog) · whether any public hook, control id, or rendered class changed (= breaking, needs special note). Missing → ask.

## Phase 1 — Pre-flight

- Confirm branch state: clean working tree, up to date with main.
- Freeze: no new feature commits past this point — only fixes from QA findings.
- Decide version per semver: breaking → major; new widget/extension → minor; bug fixes only → patch.
- If any shared code (traits, classes, hooks) changed, coordinate with the Pro team — note the Lite PR in Pro's tracking and ensure Pro's matching PR is ready to land before Lite ships.

## Phase 2 — Quality Gates (must all be green)

| Command | Checks | Fail action |
|---------|--------|-------------|
| `composer run phpcs` | WPCS + PHP Compatibility WP on PHP | `composer run phpcbf` for auto-fixable; manually fix the rest |
| `npm run build` | Production + dev assets + `.pot` regeneration | Read webpack error; do not ship if exit code ≠ 0 |
| `npm run test:e2e` | Playwright suite — see [.claude/rules/testing.md](../../rules/testing.md) | All specs green; flake → re-run; consistent failure → block release |

Any red gate **blocks** the release. Don't skip "for a small patch" — small patches break wordpress.org installs the most.

## Phase 3 — Version Bump (three required edits)

Version must match in **all three** locations or wordpress.org will show stale data and cache busting will misfire.

| File | Line | Field |
|------|------|-------|
| [essential_adons_elementor.php](../../../essential_adons_elementor.php#L7) | 7 | ` * Version: X.Y.Z` (plugin header) |
| [essential_adons_elementor.php](../../../essential_adons_elementor.php#L30) | 30 | `define('EAEL_PLUGIN_VERSION', 'X.Y.Z');` |
| [readme.txt](../../../readme.txt#L7) | 7 | `Stable tag: X.Y.Z` |

Also verify (no edit needed unless changing): `Tested up to: 7.0` ([readme.txt:5](../../../readme.txt#L5)) — bump only when a new WP major is released and tested.

## Phase 4 — Changelog & i18n

**Changelog entry** at top of `== Changelog ==` section in `readme.txt`:

```
= X.Y.Z - DD/MM/YYYY =

- Fixed: {what the user-visible bug was}
- Improved: {visible enhancement}
- Added: {new widget or feature}
- Few minor bug fixes & improvements
```

Rules:
- Date format **DD/MM/YYYY** (matches existing entries — don't switch)
- One line per item; lead with category (`Fixed:`, `Improved:`, `Added:`, `Removed:`)
- User-visible language only — no "refactored trait X". The end user reads this on wordpress.org
- For breaking changes (renamed hook, removed widget, changed control id), add a leading note: `**Breaking:** ...`

**`.pot` regeneration:** confirmed by Phase 2's `npm run build` (CLAUDE.md line 12 — build command emits `.pot`). Verify the `.pot` file mtime is fresh: `ls -la languages/*.pot`.

## Phase 5 — Distribution Prep

**`.distignore` audit** — re-read [.distignore](../../../.distignore) and confirm:

- All dev configs excluded: `.babelrc`, `phpcs.xml*`, `webpack.config.js`, `.prettier*`
- Source folders excluded: `src`, `tests`, `node_modules`, `.git`, `.github`, `.husky`
- Lock files excluded: `package-lock.json`, `pnpm-lock.yaml`, `composer.lock`
- Internal folders excluded: `.claude`
- Verify: `grep -E "^(\.claude|src|tests|node_modules|webpack)" .distignore`

If any dev artifact is missing from `.distignore`, **add it before building the dist zip**.

**Build the dist zip** (whatever your release tool uses — typically `wp dist-archive` or a manual zip excluding `.distignore` patterns). Inspect the zip:

- Open the zip, confirm `node_modules/`, `src/`, `tests/`, `.claude/`, `.git/` are absent
- `assets/front-end/` is present with `.min.css` / `.min.js` files
- `languages/*.pot` present
- `vendor/` (composer dependencies) present if production deps exist

## Phase 6 — Tag & Deploy

- `git tag vX.Y.Z` and push the tag.
- Deploy to wordpress.org SVN via the team's release workflow (often automated through GitHub Actions). The Stable tag in `readme.txt` is what wordpress.org will show — confirm it matches the tag.
- Notify the Pro team if any shared hook, control id, or rendered class changed — Pro depends on the Lite contract.

## Phase 7 — Post-release Verify

- Wait ~15 min for wordpress.org cache to update, then check the plugin page shows new version.
- On a **clean WP install** (not your dev `wp-env`): install the released plugin, activate, drop a few key widgets (Fancy_Text, Adv_Accordion, Countdown), confirm they render.
- On an install with the **previous version**: run the auto-update from WP admin, confirm settings persist and widgets still render.
- Spot-check 2–3 reported issues from the changelog — confirm the fixes are live.

## Rollback Plan

If post-release verification surfaces a critical regression:

1. **Immediate:** revert the `Stable tag` line in `readme.txt` (SVN trunk) to the last known good version. wordpress.org will serve that version while you fix.
2. Cut a hotfix branch from the previous tag, apply fix only, run Phases 2–7.
3. Notify users via support channel and changelog: `= X.Y.Z+1 - DATE = Hotfix for {issue}`.

Never `git push --force` to the release tag — cut a new patch version.

## Operating Rules

1. **No phase skips**, even for hotfixes. Quality gates exist because past releases broke when they were skipped.
2. **All three version locations** updated together — one PR, one commit. Mismatch = cache and admin notices break.
3. **Changelog entries are user-facing.** If a non-developer can't understand the line, rewrite it.
4. **Test the zip, not the dev tree.** Many bugs are `.distignore` leaks visible only in the built artifact.
5. **Breaking changes flagged at the top** of the changelog with `**Breaking:**` — don't bury them.
