---
description: Systematic root-cause debugging for an EA widget — reproduce, localize, trace, fix-at-root, guard.
argument-hint: <Widget_Class_Name | widget-slug>
---

# Debug Widget — $ARGUMENTS

Use the `debug-widget` skill at `.claude/skills/debug-widget/SKILL.md` to investigate.

**Widget identifier:** `$ARGUMENTS`

If `$ARGUMENTS` is empty, ask the user which widget. Then before starting Phase 1, ask for:

- Symptom (expected vs actual behavior)
- Environment (frontend / editor / both, logged-in?)
- Recent changes (last commit, build, version bump?)

Follow the skill workflow:
1. Reproduce on `http://localhost:8888` (start `wp-env` if needed)
2. Localize via the 4-question decision tree
3. Trace the matching path from the lookup table — confirming signal required
4. Root-cause via 5-whys (no symptom-patches)
5. Fix at smallest scope, build if `src/` touched
6. Guard with a Playwright spec (per `.claude/rules/testing.md`)
7. Verify on the test site

Stop and report at 30 minutes if root cause not narrowed — don't loop silently.
