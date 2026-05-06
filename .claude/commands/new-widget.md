---
description: Scaffold a new EA widget end-to-end via the new-widget skill — PHP class, SCSS, optional JS, config.php, build, verify.
argument-hint: <Widget_Class_Name>
---

# New Widget — $ARGUMENTS

Use the `new-widget` skill at `.claude/skills/new-widget/SKILL.md` to scaffold the widget.

**Class name:** `$ARGUMENTS` (PascalCase with underscores, e.g. `Marquee_Text`)

If `$ARGUMENTS` is empty, ask the user for the widget class name before doing anything.

Before scaffolding any file, complete Phase 1 (Gather Requirements) — the skill needs answers to:

- Frontend interactivity (JS at view time)?
- Edit-mode JS?
- Pro-shared (will Pro inject options)?
- Vendor libraries needed (prefer Elementor's `swiper` handle over bundled)?
- Initial controls — minimum set to make the widget render?

Then follow Phases 2–9 in order: PHP class → SCSS → JS (if needed) → edit JS (if needed) → `config.php` → Pro upsell (if shared) → build & verify → optional Playwright spec.

Return a list of every file created/modified and the build status.
