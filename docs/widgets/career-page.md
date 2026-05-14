# EasyJobs Career Page Widget

> Anti-stub widget — exists ONLY to advertise the EasyJobs plugin when it's NOT installed. Third such stub in EA Lite (after [`embedpress.md`](embedpress.md) and [`better-payment.md`](better-payment.md)). When the EasyJobs plugin is active, this stub vanishes from the panel and EasyJobs' own Elementor widget takes over.

**Class file:** [`includes/Elements/Career_Page.php`](../../includes/Elements/Career_Page.php)
**Slug:** `career-page` (widget id `eael-career-page`)
**Public docs:** <https://easy.jobs/docs/> ⚠️ external — only EA widget pointing to a non-essential-addons.com docs URL alongside EmbedPress
**Pro-shared:** ❌ No — not Pro-extended. Inverted-condition stub only.

---

## Overview

Single-purpose discovery / upsell widget for [easy.jobs](https://easy.jobs/) — a separate WPDeveloper-affiliated SaaS jobs platform. `register_controls()` adds only a Warning RAW_HTML notice ("EasyJobs is not installed/activated") with a plugin-install deep link; `render()` is `return;` (void output). Registration condition in [`config.php`](../../config.php#L1109) is `'condition' => ['function_exists', 'run_easyjobs', true]` — widget registers ONLY when `run_easyjobs()` is **absent**. 70-line PHP class, ZERO SCSS, ZERO JS. **Differs from Better_Payment + EmbedPress stubs by using `function_exists` not `class_exists`** for the upstream-presence check.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Warning notice in panel when EasyJobs plugin absent | ✅ | ✅ |
| Renders anything on frontend | ❌ — `render()` is `return;` | ❌ — same |
| Anything to extend | ❌ — no controls, no hooks, no settings | ❌ |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Career_Page.php`](../../includes/Elements/Career_Page.php) | PHP widget class (70 lines) — single Warning RAW_HTML notice; empty `render()` |
| [`config.php`](../../config.php#L1109) entry `'career-page'` | Inverted condition — registers only when `run_easyjobs` function is ABSENT |
| (no SCSS) | — no styles |
| (no JS) | — no scripts |
| (no `'dependency'` key in config) | — Asset_Builder skips asset queuing entirely |

## Architecture

- **Anti-stub via `function_exists` condition** — `config.php` declares `'condition' => ['function_exists', 'run_easyjobs', true]`. The [`Elements::register_widgets()` loop](../../includes/Traits/Elements.php#L60) evaluates `function_exists('run_easyjobs') == true` and `continue`s (SKIPS registration) when truthy. Net: widget registers ONLY when EasyJobs plugin is NOT installed.
- **`function_exists` instead of `class_exists`** — unique among EA Lite's three anti-stub widgets. EmbedPress checks a namespaced class; Better_Payment checks an unprefixed global class; Career_Page checks a global function. EasyJobs plugin's main bootstrap is a function called `run_easyjobs()` (per the plugin's PSR-4 conventions) — so the check is appropriate. Slightly more robust than the namespaced-class check because function names are always in the global namespace; no namespace ambiguity.
- **`render()` returns void** at [line 67](../../includes/Elements/Career_Page.php#L67) — produces no markup on frontend even when widget is "saved" in editor.
- **`get_custom_help_url()` returns external URL** `https://easy.jobs/docs/` at [line 42](../../includes/Elements/Career_Page.php#L42) — only EA Lite widget besides EmbedPress with a non-essential-addons.com help URL. Points to the third-party platform's docs.
- **Title is `'EasyJobs Career Page'`** at [line 18](../../includes/Elements/Career_Page.php#L18) — surfaces the upstream plugin name in the widget title (similar to "EmbedPress" surfacing in its stub title).
- **Orphan saved instances render empty** — if a widget instance is saved while EasyJobs is absent, then EasyJobs is later installed, the EA stub disappears from the panel (condition flips) and existing instances render as nothing. The user must remove the EA stub and add EasyJobs' own widget — no automatic migration.
- **No `has_widget_inner_wrapper()` override** — same omission as EmbedPress and Better_Payment stubs.
- **No `'dependency'` declaration in `config.php`** at all — most widgets at minimum declare empty CSS/JS arrays; Career_Page omits the key entirely.

## Render Output

```html
<!-- Editor panel: Warning notice section only -->
<!-- Frontend: NOTHING (render() returns void) -->
```

Notes:

- No DOM output on frontend. Saved widget instances are invisible in production.
- Editor panel shows a single "Warning!" section with RAW_HTML notice + plugin-install deep link `plugin-install.php?s=easyjobs&tab=search&type=term`.

## Controls Reference

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | (warning copy + plugin-install link) | Content → Warning! | Renders the only panel notice — EasyJobs plugin install prompt |

## Conditional Dependencies

```text
(no panel-level conditions — only the registration-level condition in config.php)

Widget registers      → only when function_exists('run_easyjobs') is FALSE
```

When EasyJobs plugin activates: widget vanishes from Elementor panel; EasyJobs' own Career Page widget (different widget id) takes over.

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes none. No `eael_section_pro` upsell, no `eael/pro_enabled` consumption.

## JavaScript Lifecycle

> N/A — pure PHP stub, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`.

## Common Issues

### Widget disappears from panel after installing EasyJobs plugin

- **Likely cause:** Registration condition flipped — `function_exists('run_easyjobs')` now true → widget skipped at [Elements trait line 70](../../includes/Traits/Elements.php#L70). Existing widget instances become orphans.
- **Diagnose:** Verify EasyJobs is active; check Elementor panel for the EasyJobs plugin's own widget (different widget id).
- **Fix:** Remove the EA stub instance and add EasyJobs' Elementor widget. No automatic migration.

### Help link goes to easy.jobs not essential-addons.com

- **Likely cause:** `get_custom_help_url()` returns the third-party platform's docs at [line 42](../../includes/Elements/Career_Page.php#L42).
- **Diagnose:** Click help icon — opens `https://easy.jobs/docs/` instead of EA docs.
- **Fix:** Intentional — stub is upselling the EasyJobs platform; help URL is set to that platform's docs. Behaviour matches EmbedPress (`https://embedpress.com/documentation`).

### Stub keeps showing alongside EasyJobs plugin's own widget

- **Likely cause:** EasyJobs plugin bootstrap function renamed or namespaced — `function_exists('run_easyjobs')` no longer matches. Less likely than the class-namespace fragility issue with Better_Payment.
- **Diagnose:** `var_dump(function_exists('run_easyjobs'))` after WordPress loads.
- **Fix:** Update `config.php` condition with the new function name.

## Known Limitations

- **`function_exists('run_easyjobs')` registration check** — unlike Better_Payment's class-exists check, this is robust against namespace changes (function names are always global). However, if the upstream plugin renames its bootstrap function, this stub would silently reactivate.
- **External help URL** `https://easy.jobs/docs/` — points to third-party platform, not EA docs (intentional but worth noting).
- **Empty `render()` produces no frontend output** — saved instances are invisible after EasyJobs activation.
- **No `has_widget_inner_wrapper()` override** — same omission as other two stubs.
- **No migration path to EasyJobs plugin's own widget** — different widget id; user must manually re-add.
- **No extension surface at all** — zero `do_action` / `apply_filters` / settings.
- **No `'dependency'` key in `config.php`** — config entry is more minimal than Better_Payment and EmbedPress (those have no dependency but at least the array shape; Career_Page omits the array key entirely).
- **Widget title surfaces the upstream brand** — `'EasyJobs Career Page'` rather than a generic `'Career Page'` — makes the discovery intent explicit; users looking for "Career Page" widgets may overlook this entry if they don't recognise the EasyJobs brand.
- **Third anti-stub widget in EA Lite** — pattern: EmbedPress (namespaced `class_exists`) + Better_Payment (global `class_exists`) + Career_Page (global `function_exists`). All three render void on frontend, all three vanish from panel when upstream is active, none have automatic migration.
