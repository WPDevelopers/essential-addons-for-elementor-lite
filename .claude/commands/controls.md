---
description: Design or audit Elementor controls for a widget — tabs, types, conditions, responsive, EA Pro patterns.
argument-hint: <Widget_Class_Name | widget-slug>
---

# Elementor Controls — $ARGUMENTS

Use the `elementor-controls` skill at `.claude/skills/elementor-controls/SKILL.md`.

**Widget identifier:** `$ARGUMENTS`

If `$ARGUMENTS` is empty, ask which widget. Then ask the user:

- **Mode:** add new control(s) / refactor existing `register_controls()` / audit for convention compliance?
- **What** the control(s) should configure (in user-facing terms)
- **Whether responsive** — same on all breakpoints, or mobile/tablet differs?
- **Lite-only or Pro-shared** — does this need the EA Pro upsell pattern (Step 4)?

Follow the skill in order:
1. Plan tabs and sections (TAB_CONTENT / TAB_STYLE / TAB_ADVANCED)
2. Pick the right control type (lookup table)
3. Wire selectors (`{{WRAPPER}}` always), responsive, conditions (single `condition` 95% of the time)
4. Apply EA-specific patterns if Pro-shared (forced fallback in `render()`, prefixed filter, upsell section)
5. Verify in editor on `http://localhost:8888` — defaults render, conditions hide/show, responsive switcher works

Output ready-to-paste PHP that follows EA conventions (id prefix `eael_{slug}_*`, scoped selectors, sensible defaults).
