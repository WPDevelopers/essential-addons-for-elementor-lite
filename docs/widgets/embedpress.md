# EmbedPress Widget

> **Anti-stub widget** — exists only to advertise EmbedPress integration when the EmbedPress plugin is NOT installed. When EmbedPress IS installed, EA's stub is **skipped during widget registration** (inverted `condition` semantic in `config.php` — `class_exists` matching `true` causes `continue` in the Elements trait registration loop). Users with EmbedPress active use **EmbedPress's own Elementor widget** registered by `\EmbedPress\Elementor\Embedpress_Elementor_Integration` — not this stub. The 75-line PHP file is the smallest widget in EA Lite by an order of magnitude.

**Class file:** [`includes/Elements/EmbedPress.php`](../../includes/Elements/EmbedPress.php)
**Slug:** `embedpress` (widget id `eael-embedpress`)
**Public docs:** <https://embedpress.com/documentation> ⚠ points to EmbedPress's own docs, not EA's
**Pro-shared:** ❌ N/A — EA does not provide any EmbedPress functionality. Pro doesn't reference this widget. The actual embed functionality is entirely provided by the EmbedPress plugin's own Elementor integration.

---

## Overview

EmbedPress is the **only widget in EA Lite that intentionally never renders content**. Its 75-line PHP class has an empty `render()` (literally `return;`) and a `register_controls()` that adds only a warning notice — no form controls, no style controls, no template. The widget exists for one purpose: when the EmbedPress plugin is **not** installed, EA shows the stub in the Elementor widget panel so users searching for "embedpress" / "video" / "audio" / "youtube" / "vimeo" find it and see the warning message directing them to install EmbedPress. The instant EmbedPress is installed, EA's stub disappears from the panel entirely (skipped in the registration loop) and EmbedPress's own Elementor widget — provided by `\EmbedPress\Elementor\Embedpress_Elementor_Integration` — takes over. The condition format in `config.php` uses the **inverted-skip semantic**: when `class_exists(<EmbedPress integration class>) == true`, the registration loop calls `continue` to skip this stub. **No SCSS, no JS, no asset declarations.**

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Warning notice when EmbedPress not installed | ✅ | ✅ |
| Actual embed rendering (YouTube / Vimeo / Wistia / Maps / etc.) | ❌ — provided by EmbedPress plugin's own Elementor widget when installed | — |
| Any style controls | ❌ — none | — |
| Any frontend output | ❌ — `render()` returns void | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/EmbedPress.php`](../../includes/Elements/EmbedPress.php) | PHP widget class (75 lines — smallest in EA Lite). `register_controls()` adds a single warning section; `render()` returns immediately |
| [`includes/Traits/Elements.php`](../../includes/Traits/Elements.php#L63-L72) | Registration loop — `condition` handler decides whether to skip the widget based on `condition[0](condition[1]) == condition[2]` |
| [`config.php`](../../config.php#L1044) entry `'embedpress'` | `'condition' => ['class_exists', '\EmbedPress\Elementor\Embedpress_Elementor_Integration', true]` — skip-when-class-exists semantic |
| `\EmbedPress\Elementor\Embedpress_Elementor_Integration` (third-party) | EmbedPress plugin's own Elementor integration — when present, this class registers the real widget; EA's stub is skipped |
| (no SCSS file) | — no styling needed (widget never renders) |
| (no JS file) | — no behavior needed |

## Architecture

- **Inverted `condition` semantic skips registration when class exists** — `Elements::register_widgets()` at [Elements.php line 63-72](../../includes/Traits/Elements.php#L63):
  ```php
  if ( $this->registered_elements[$key]['condition'][0]($this->registered_elements[$key]['condition'][1]) == $check ) {
      continue;  // SKIP registering this widget
  }
  ```
  For EmbedPress: `class_exists('\EmbedPress\Elementor\Embedpress_Elementor_Integration')` → when `true` matches `condition[2] === true` → `continue` is hit → widget is NOT registered. **Same skip semantic** is used for all conditionally-registered widgets (Better_Payment, BetterDocs widgets, NFT_Gallery, etc.) but EmbedPress is the only one that uses it as an "advertise when absent" pattern.
- **Three-element condition array** — `[function_name_string, function_arg, expected_result]`. The function is called dynamically via PHP's variable-function-call syntax (`condition[0]($arg)`). Same pattern as `function_exists` checks but parameterized.
- **Render returns immediately** at [line 72-74](../../includes/Elements/EmbedPress.php#L72) — even if the stub somehow ended up on a page (which it can't, because Elementor only allows widgets registered to its widget manager), nothing renders. Defensive coding.
- **No `eael_section_pro` upsell, zero hooks** — confirmed lean profile. Not even a `has_widget_inner_wrapper()` or `get_custom_help_url()` override beyond pointing to EmbedPress's own docs site.
- **`get_custom_help_url()` points to `https://embedpress.com/documentation`** — only EA widget whose help URL is **external** (every other widget points to `https://essential-addons.com/elementor/docs/<slug>/`). Acknowledges that EA isn't documenting EmbedPress functionality at all.
- **Keywords list includes media-related terms** — `audio`, `video`, `map`, `youtube`, `vimeo`, `wistia`, `google` — broad keyword match so users searching for any of these in the Elementor widget panel discover the stub.
- **No `Helper` import**, no `Controls_Manager::*` types beyond `RAW_HTML` — minimal external dependencies.
- **Not in any `has_pro` JS handoff or Liquid Glass chain** — fully decoupled from EA Pro.

## Render Output

```html
<!-- No output. render() is `return;` -->
```

When the widget is somehow placed in an Elementor document while EmbedPress is **not** installed (which only happens if the user previously added it then deactivated EmbedPress), Elementor displays no content for the widget on the front-end. The editor-mode panel shows the warning notice.

When EmbedPress IS installed, this widget never appears in the Elementor panel — EmbedPress's own widget appears instead (different widget id, different class).

## Controls Reference

Source [`register_controls()`](../../includes/Elements/EmbedPress.php#L50) is the truth — entire panel is one warning.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_global_warning_text` | RAW_HTML | — | Content → Warning! | Static notice: "EmbedPress is not installed/activated. Please install and activate EmbedPress first." with plugin-install deep-link `plugin-install.php?s=embedpress&tab=search&type=term` |

That's the entire panel. No other sections, no style tab content.

## Conditional Dependencies

```text
# Registration-level gate (in config.php, NOT register_controls)
Widget itself                              → only registered when class_exists('\EmbedPress\Elementor\Embedpress_Elementor_Integration') is FALSE
                                             (because `'condition' => [..., true]` → loop `continue` when class IS present)

# Panel-level — no conditional logic; the warning is the only content.
eael_global_warning_text                  → always visible when widget IS registered (i.e., when EmbedPress is absent)
```

## Hooks & Filters

> N/A — no `do_action`, no `apply_filters`, no `eael/pro_enabled` consumption. Zero extension surface. The widget's reason for existing is to detect EmbedPress's absence via `class_exists`, not via any filter.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply.

## JavaScript Lifecycle

> N/A — **no widget JavaScript file, no `get_script_depends()`.** The widget never renders content, so frontend JS has nothing to attach to. EmbedPress's own Elementor widget brings its own JS for embed initialization.

## Common Issues

### Widget disappears from the Elementor panel after installing EmbedPress

- **Likely cause:** working as designed. When EmbedPress activates, its integration class registers the real widget, and EA's stub is skipped via the `condition` array in `config.php`.
- **Diagnose:** check Plugins → Installed → EmbedPress is active.
- **Fix:** use EmbedPress's own widget (also titled "EmbedPress") from the panel. Different widget id but same purpose.

### Old EmbedPress widget instances render empty after EmbedPress is deactivated

- **Likely cause:** widget instances saved with EA's stub widget id (`eael-embedpress`) have a `render()` that returns void. Even when EmbedPress is later re-installed, EA's stub remains the saved widget type — Elementor doesn't auto-migrate to EmbedPress's widget.
- **Diagnose:** view-source for the page — does the widget container render with no content inside?
- **Fix:** delete the saved widget instance and re-add using EmbedPress's own widget.

### Warning notice references "EmbedPress" but I have it installed

- **Likely cause:** EmbedPress is installed but the specific class `\EmbedPress\Elementor\Embedpress_Elementor_Integration` doesn't exist. Older EmbedPress versions (pre-Elementor integration) or an incomplete install lack this class.
- **Diagnose:** run `class_exists('\EmbedPress\Elementor\Embedpress_Elementor_Integration')` via WP-CLI or browser console.
- **Fix:** update EmbedPress to a version that ships the Elementor integration class.

### Help URL points to embedpress.com, not essential-addons.com

- **Likely cause:** intentional. EA doesn't document EmbedPress functionality; the help link redirects to EmbedPress's own docs site.
- **Fix:** none needed.

## Known Limitations

- **The widget never renders content** — `render()` is `return;`. By design, but means saved widget instances become orphans when EmbedPress is uninstalled.
- **No migration path from EA stub to EmbedPress's own widget** — different widget ids; Elementor doesn't auto-convert. Users must manually delete + re-add.
- **`condition` semantic is documented only by the Elements trait** — not in any per-widget doc. Reading `'condition' => ['class_exists', X, true]` looks like "register when X exists" but actually means "skip when X exists". Counter-intuitive.
- **Keyword list claims `youtube`, `vimeo`, `wistia`, `google` etc.** — when EmbedPress is installed and the EA stub is skipped, EA's panel won't show any widget matching these keywords (EmbedPress's own widget may not register with the same keywords).
- **No `Helper::eael_e_optimized_markup()` consideration** — `has_widget_inner_wrapper()` not overridden, falls through to Elementor's default. Inconsistent with every other EA widget (which all override).
- **Help URL goes to a third-party domain** — `embedpress.com/documentation` is external. EA has no version control over its content.
- **Not categorized as a Business/E-commerce or Other widget in EA docs** — sits in the "Other" bucket by convention.
- **No `is_dynamic_content()` override** — defaults to `false`; irrelevant since render is empty.
- **No `get_style_depends()`** — irrelevant since no content renders.
- **Smallest widget class in EA Lite (75 lines)** — by a wide margin. Next smallest is Adv_Tabs at ~1500 lines. The minimal stub is unique.
- **Plugin-install deep-link uses `s=embedpress`** (lowercase) — matches WP.org plugin slug.
