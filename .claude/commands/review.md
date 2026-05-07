---
description: Five-axis senior audit of an EA widget (correctness, security, i18n, asset hygiene, architecture).
argument-hint: <Widget_Class_Name | widget-slug | path/to/Widget.php>
---

# Widget Review — $ARGUMENTS

Use the `widget-review` skill at `.claude/skills/widget-review/SKILL.md` to audit the widget.

**Widget identifier:** `$ARGUMENTS`

If `$ARGUMENTS` is empty, ask: *"Which widget? Class name (e.g. `Adv_Accordion`), file path, or slug."*

Follow the skill exactly:
1. Triage — parallel reads of class, `config.php` entry, SCSS source, JS source, edit JS
2. Five-axis review — correctness, security, i18n, asset hygiene, architecture
3. Verify each finding by reading the surrounding code (no false positives)
4. Output the structured report (Critical / Important / Nit / Strengths)

Do not edit any files. The deliverable is the report. If the user asks for fixes after the report, that's a separate request.
