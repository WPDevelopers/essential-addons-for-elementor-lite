---
description: Pre-flight checklist for shipping an EA Lite version to WordPress.org. No phase skips, even for hotfixes.
argument-hint: <X.Y.Z>
---

# Release — $ARGUMENTS

Use the `release-checklist` skill at `.claude/skills/release-checklist/SKILL.md` to walk the release.

**Target version:** `$ARGUMENTS`

If `$ARGUMENTS` is empty, ask the user for the target version (semver) before starting.

Also gather before Phase 1:
- Release type (patch / minor / major) — derive from `$ARGUMENTS` vs current `EAEL_PLUGIN_VERSION`
- Summary of what changed (for the changelog entry)
- Any breaking changes (renamed hook, removed widget, changed control id, changed rendered class)?

Follow all 7 phases in order — none are skippable:
1. Pre-flight (clean tree, code freeze, Pro repo sync if shared code changed)
2. Quality gates: `npm run lint`, `composer run phpcs`, `npm run build`, `npm run test:e2e` — all green
3. Version bump in **3 locations** (plugin header L7, `EAEL_PLUGIN_VERSION` L30, `readme.txt` Stable tag L7)
4. Changelog entry in `readme.txt` (`= $ARGUMENTS - DD/MM/YYYY =` format) + `.pot` regen verify
5. `.distignore` audit + dist zip inspection (no `node_modules`/`src`/`tests`/`.claude`)
6. Tag (`git tag v$ARGUMENTS`) + wordpress.org SVN deploy + Pro team notify if shared API changed
7. Post-release verify (clean install + auto-update from previous version)

If any gate is red, stop. Don't ship.
