---
name: widget-review
description: Senior-level audit of a single EA widget across five axes — correctness, security, i18n, asset hygiene, and architecture. Use when asked to "review", "audit", or "check" a widget in `includes/Elements/`, before merging widget changes, or after porting a widget between Lite and Pro. Produces a structured report with file:line references and concrete fix suggestions.
---

# Widget Review

Audit one Essential Addons widget end-to-end. The review is **scoped to a single widget** plus its source SCSS/JS and `config.php` entry — not the whole plugin.

## When to Invoke

- User says "review widget X", "audit widget X", "check widget X"
- Before merging a PR that touches `includes/Elements/`
- After porting a widget between Lite ↔ Pro
- When a widget is reported as broken and root cause is unclear

## Inputs Required

The widget identifier — either the class name (`Adv_Accordion`), file (`includes/Elements/Adv_Accordion.php`), or slug (`eael-adv-accordion`).

If ambiguous, ask: *"Which widget? Class name, file path, or slug."*

## Workflow

### Step 1 — Locate and Triage (parallel reads)

In one parallel batch, read:

1. The widget class: `includes/Elements/{Widget}.php`
2. The `config.php` entry — grep for the slug
3. Source SCSS: `src/css/view/{slug}.scss` (if exists)
4. Source JS: `src/js/view/{slug}.js` (if exists)
5. Edit JS: `src/js/edit/{slug}.js` (if exists)

Note the line counts. If the widget class is > 1500 lines, plan to skim `register_controls()` for patterns and read `render()` in full — don't read controls line by line.

State scope to the user before deep review:

```
Reviewing: {Widget class}
Files: {list}
Size: {N lines class, M lines SCSS, K lines JS}
```

### Step 2 — Five-Axis Review

Run each axis. Record findings as `{axis}: {file}:{line} — {issue} → {fix}`. Group at the end.

#### Axis 1: Correctness

- [ ] `get_name()` returns kebab-case slug matching `config.php` key
- [ ] `get_title()`, `get_icon()`, `get_categories()` implemented
- [ ] `render()` uses `$this->get_settings_for_display()` — **never** `get_settings()`
- [ ] Output wrapped in single root `<div class="eael-{slug}">`
- [ ] Empty / missing settings handled (no PHP notices in editor preview)
- [ ] Default control values produce a sensible render with zero configuration
- [ ] Edit-mode (`Elementor::instance()->editor->is_edit_mode()`) branches don't leak to frontend

#### Axis 2: Security

For each output expression, verify the right escape function:

| Output | Required escape |
|--------|-----------------|
| Plain text | `esc_html()` |
| URL (`href`, `src`) | `esc_url()` |
| HTML attribute | `esc_attr()` |
| Rich HTML (post content, editor field) | `wp_kses_post()` |
| Inline JS data | `wp_json_encode()` + `esc_js()` if inserted as string |

Also check:
- [ ] `$_GET` / `$_POST` / `$_REQUEST` sanitized (`sanitize_text_field`, `absint`, `wp_kses_post`, etc.)
- [ ] AJAX handlers verify nonce (`wp_verify_nonce` / `check_ajax_referer`)
- [ ] Privileged ops check `current_user_can()`
- [ ] SQL uses `$wpdb->prepare()` — no string concat in queries
- [ ] No raw `echo $setting['x']` for user-controlled fields

Grep aid: `grep -nE "echo |print " includes/Elements/{Widget}.php` then verify each.

#### Axis 3: i18n

- [ ] Every user-facing string wrapped: `__()`, `esc_html__()`, `_e()`, `esc_attr__()`
- [ ] Text domain is exactly `essential-addons-for-elementor-lite` (no typos, no Pro domain leaking)
- [ ] No string concatenation inside translation calls — use `printf`/`sprintf` with `%s` placeholders
- [ ] Plurals use `_n()` not ternary
- [ ] Translator comments (`/* translators: %s is ... */`) for any placeholder

Grep aid: `grep -nE "__\(|_e\(|_n\(|esc_html__\(|esc_attr__\(" includes/Elements/{Widget}.php | grep -v "essential-addons-for-elementor-lite"` — any output is a wrong/missing text domain.

#### Axis 4: Asset Hygiene

- [ ] Widget slug present in `config.php` element registry
- [ ] CSS/JS deps in `config.php` reflect what the widget actually uses
- [ ] SCSS file exists at `src/css/view/{slug}.scss` if widget needs custom styles
- [ ] BEM classes prefixed `eael-{slug}__element--modifier`
- [ ] **No duplicate Swiper bundling** — uses Elementor's `swiper` handle (CSS) and `elementorFrontend.utils.swiper` (JS); see `.claude/rules/asset-pipeline.md`
- [ ] **No duplicate Font Awesome** — depends on `font-awesome-5-all` handle
- [ ] If widget uses Swiper container, class is `.swiper` (not legacy `.swiper-container`)
- [ ] No inline `<style>` / `<script>` tags in `render()` for static content (move to SCSS/JS source)

#### Axis 5: Architecture

- [ ] Namespace: `Essential_Addons_Elementor\Elements`
- [ ] Extends `\Elementor\Widget_Base`
- [ ] Cross-cutting logic delegated to a trait in `includes/Traits/` or class in `includes/Classes/` — widget class itself stays focused on controls + render
- [ ] No direct DB calls inside `render()` — go through a helper that can be cached
- [ ] Hook names follow `eael/{context}/{action}` if widget emits any
- [ ] If widget is shared with Pro, there's a clear extension point (filter or method) — not duplicated logic

### Step 3 — Verify Issues Before Reporting

For every finding flagged in Step 2, **read the surrounding code** to confirm it's a real issue, not a false positive (e.g., a value that's already escaped upstream, or a string that's a CSS class name and shouldn't be translated).

A finding without verification is a guess. Don't report guesses.

### Step 4 — Output Report

Produce a structured report in this exact shape:

```markdown
## Widget Review: {Widget Name}

**Files reviewed:** {list with line counts}
**Verdict:** {ship-ready | needs-fixes | blocked}

### Critical (must fix before ship)
- [ ] {file}:{line} — {issue}
  → Fix: {concrete suggestion, ideally with replacement snippet}

### Important (fix soon)
- [ ] ...

### Nits (optional polish)
- [ ] ...

### Strengths
- {anything done well — keep this short, 2–4 bullets max}
```

**Severity calibration:**
- **Critical** — security holes (XSS, missing nonce/cap), broken render, wrong text domain, breaks Asset_Builder
- **Important** — missed escapes on low-risk fields, missing i18n, asset duplication, render fragility
- **Nit** — naming, BEM consistency, docblocks, control grouping

## Operating Rules

1. **Scope discipline.** Review only the requested widget + its assets + its `config.php` entry. Don't refactor traits, don't audit other widgets even if they look similar.

2. **No fixes without permission.** This skill produces a report. Do not edit files unless the user says "fix it" / "apply" / "go ahead". The report is the deliverable.

3. **One widget at a time.** If the user names two, ask which to do first or confirm batch mode explicitly.

4. **Reference, don't repeat.** Where rules live in `.claude/rules/*.md`, link to them — don't re-state every rule in the report.

5. **Verify by reading.** Before flagging "missing escape" or "wrong text domain", read enough context to be certain. Grep finds candidates; reading confirms issues.
