# Better Payment Widget

> Anti-stub widget — exists ONLY to advertise the Better Payment plugin when it's NOT installed. Sibling pattern to [`embedpress.md`](embedpress.md). When the Better Payment plugin is active, this stub vanishes from the panel and Better Payment's own Elementor widget takes over (different widget id — no migration path).

**Class file:** [`includes/Elements/Better_Payment.php`](../../includes/Elements/Better_Payment.php)
**Slug:** `better-payment` (widget id `eael-better-payment`)
**Public docs:** N/A — `get_custom_help_url()` returns `'#'` (no help URL set, unique to this widget in EA Lite)
**Pro-shared:** ❌ No — not a Pro-extended widget. Inverted-condition stub only.

---

## Overview

Single-purpose discovery / upsell widget. `register_controls()` adds only a Warning RAW_HTML notice ("Better Payment is not installed/activated") with a plugin-install deep link; `render()` is `return;` (void output on frontend). Widget registration is **conditional** in [`config.php`](../../config.php#L1267) via `'condition' => ['class_exists', 'Better_Payment', true]` — the [Elements trait registration loop](../../includes/Traits/Elements.php#L70) `continue`s (SKIPS registration) when `condition[0](condition[1]) == condition[2]` is truthy → widget registers ONLY when `class_exists('Better_Payment')` is **false** (plugin absent). 65-line PHP class, ZERO SCSS, ZERO JS.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Warning notice in panel when Better Payment plugin absent | ✅ | ✅ |
| Renders anything on frontend | ❌ — `render()` is `return;` | ❌ — same |
| Anything to extend | ❌ — no controls, no hooks, no settings | ❌ |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Better_Payment.php`](../../includes/Elements/Better_Payment.php) | PHP widget class (65 lines) — single Warning RAW_HTML notice; empty `render()` |
| [`config.php`](../../config.php#L1267) entry `'better-payment'` | `Asset_Builder` registration with **inverted condition** — registers only when `Better_Payment` class is ABSENT |
| (no SCSS) | — no styles |
| (no JS) | — no scripts |

## Architecture

- **Anti-stub via `condition` array** — `config.php` declares `'condition' => ['class_exists', 'Better_Payment', true]`. The [`Elements::register_widgets()` loop](../../includes/Traits/Elements.php#L60) at line 70 evaluates `class_exists('Better_Payment') == true` and `continue`s (SKIPS registration) when truthy. Net effect: widget registers ONLY when the Better Payment plugin is NOT installed.
- **`render()` returns void** at [line 62](../../includes/Elements/Better_Payment.php#L62) — produces no markup on frontend even when widget is "saved" in editor. This matches EmbedPress's stub pattern (`embedpress.md`); however, EmbedPress's class-exists check uses the namespaced class `\EmbedPress\Elementor\Embedpress_Elementor_Integration`, whereas Better Payment checks the unprefixed `Better_Payment` class.
- **Unprefixed class-exists check is namespace-fragile** — `class_exists('Better_Payment')` matches the GLOBAL namespace only. The Better Payment plugin's main class must be declared at global scope (not inside `\Better_Payment_NS\…`). If the upstream plugin ever moves to namespaced classes, this check would silently fail and the EA stub would keep showing alongside Better Payment's own widget.
- **`get_custom_help_url()` returns `'#'`** at [line 36](../../includes/Elements/Better_Payment.php#L36) — only EA widget in Lite to use a placeholder `#` URL. Every other widget returns a real `https://essential-addons.com/...` doc URL. Clicking the help link in the Elementor panel does nothing (anchor-only).
- **Orphan saved instances render empty** — if a widget instance is saved into Elementor while Better Payment is absent, then Better Payment plugin is later installed, the EA stub instance silently disappears from the panel (registration condition flips), and the existing widget instance renders as nothing (`render()` is `return;`). The user must remove the EA widget and add Better Payment's own Elementor widget — no automatic migration.
- **No `has_widget_inner_wrapper()` override** — inconsistent with every other EA widget which overrides to `! Helper::eael_e_optimized_markup()`. Same omission as EmbedPress stub.

## Render Output

```html
<!-- Editor panel: Warning notice section only -->
<!-- Frontend: NOTHING (render() returns void) -->
```

Notes:

- No DOM output on frontend. Saved widget instances are invisible in production.
- Editor panel shows a single "Warning!" section with RAW_HTML notice + plugin-install deep link.

## Controls Reference

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | (warning copy + plugin-install link) | Content → Warning! | Renders the only panel notice — Better Payment plugin install prompt |

## Conditional Dependencies

```text
(no panel-level conditions — only the registration-level condition in config.php)

Widget registers      → only when class_exists('Better_Payment') is FALSE
```

When Better Payment plugin activates: widget vanishes from Elementor panel; Better Payment's own widget (separate widget id) takes over.

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes none. No `eael_section_pro` upsell, no `eael/pro_enabled` consumption.

## JavaScript Lifecycle

> N/A — pure PHP stub, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`.

## Common Issues

### Widget disappears from panel after installing Better Payment plugin

- **Likely cause:** Registration condition flipped — `class_exists('Better_Payment')` now true → widget skipped at [Elements trait line 70](../../includes/Traits/Elements.php#L70). Existing widget instances in saved pages become orphaned.
- **Diagnose:** Verify Better Payment plugin is active; check Elementor widget panel for either "Better Payment" widget from EA OR from Better Payment plugin (only one exists at a time).
- **Fix:** Remove the EA Better Payment widget instance and add the Better Payment plugin's own Elementor widget (different widget id). No automatic migration.

### "Help" link in Elementor panel goes nowhere

- **Likely cause:** `get_custom_help_url()` returns `'#'` ([line 36](../../includes/Elements/Better_Payment.php#L36)) — placeholder URL.
- **Diagnose:** Click the help icon next to widget title in panel; browser anchors to current page.
- **Fix:** Doc URL was never set. Patch `get_custom_help_url()` to return a real Better Payment docs URL, or accept it.

### Stub keeps showing alongside Better Payment plugin's own widget

- **Likely cause:** The plugin uses namespaced classes — `class_exists('Better_Payment')` matches global namespace only. If Better Payment moved to e.g. `\Better_Payment\Plugin`, the check would never see the class.
- **Diagnose:** `var_dump(class_exists('Better_Payment'))` after Better Payment plugin loads.
- **Fix:** Update `config.php` condition to use the new namespaced class name (e.g. `\Better_Payment\Plugin\Main`). Requires a coordinated update with the upstream plugin.

## Known Limitations

- **Unprefixed `class_exists('Better_Payment')` check** ([config.php line 1271](../../config.php#L1271)) — relies on Better Payment plugin declaring its main class at global namespace; breaks silently if upstream moves to namespaced classes.
- **`get_custom_help_url()` returns `'#'`** ([line 36](../../includes/Elements/Better_Payment.php#L36)) — only EA Lite widget with a placeholder help URL.
- **Empty `render()` produces no frontend output** — saved widget instances are invisible after Better Payment plugin activation (orphaned).
- **No `has_widget_inner_wrapper()` override** — inconsistent with every other EA widget (same omission as EmbedPress).
- **No migration path to Better Payment plugin's own widget** — different widget id (`eael-better-payment` vs whatever Better Payment plugin uses); user must manually re-add the widget.
- **No extension surface at all** — zero `do_action` / `apply_filters` / settings. Cannot be extended; can only be replaced by Better Payment plugin's widget activating.
- **No editor visual** — Elementor preview shows just the panel notice; users can't drop the widget to a layout and see what it would look like (because it would be nothing).
- **Smallest widget class in Business/E-commerce** — 65 lines PHP; same anti-stub pattern as EmbedPress (75 lines).
