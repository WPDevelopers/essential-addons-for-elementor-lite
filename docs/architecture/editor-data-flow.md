# Editor ↔ Frontend Data Flow

How an Elementor control becomes a widget setting, how that setting reaches `render()`, and what `$settings` actually looks like inside `render()` — including the surprises (Repeaters, Group controls, Responsive controls, dynamic tags, the optimized-markup experiment).

When a contributor asks "why is `$settings['my_control']` empty in `render()`?" or "what's the difference between `condition` and `conditions`?" the answer lives here.

## Overview

Elementor is the data-storage and editor layer; EA widgets are tenants. Three things matter for working with widget data correctly:

1. **Where settings live** — the JSON tree in `_elementor_data` post meta, with each widget's settings keyed by control id under its node.
2. **How settings reach `render()`** — `$this->get_settings_for_display()` resolves defaults, applies dynamic tags, and processes Group / Responsive control prefixes. Calling `get_settings()` instead skips dynamic-tag resolution and is a frequent bug source.
3. **How `render()` output reaches the page** — wrapped (or not) by `.elementor-widget-container`, depending on Elementor's optimized-markup experiment + the widget's `has_widget_inner_wrapper()` declaration.

This doc covers all three plus the data-shape surprises.

## Components

| File / Concept | Role |
| -------------- | ---- |
| `_elementor_data` post meta | Elementor's JSON tree — every widget's `id`, `elType`, `widgetType`, `settings`, nested `elements`. Source of truth for the editor and `render()`. |
| `_eael_widget_elements` post meta | EA's flat slug list (managed by `Asset_Builder`); does not store settings. See [`asset-loading.md`](asset-loading.md). |
| `\Elementor\Widget_Base::get_settings_for_display()` | Resolves defaults + dynamic tags + Group/Responsive prefixes. Always use this in `render()`. |
| `\Elementor\Widget_Base::get_settings()` | Raw settings without dynamic-tag resolution. Almost never the right choice — see Common Pitfalls. |
| `\Elementor\Widget_Base::add_render_attribute()` | Builds the attribute strings emitted by `print_render_attribute_string()` — handles escaping. |
| [`Helper::eael_e_optimized_markup()`](../../includes/Classes/Helper.php#L2046) | Returns whether Elementor's `e_optimized_markup` experiment is active. Several widgets gate `has_widget_inner_wrapper()` on this. |
| `\Elementor\Widget_Base::has_widget_inner_wrapper()` (EA-overridden) | When false, Elementor skips wrapping the widget output in `.elementor-widget-container`. EA widgets that need to control wrapper behavior override this. |
| `\Elementor\Widget_Base::is_dynamic_content()` (EA-overridden) | Tells Elementor's caching layer that the widget renders content that varies per-request (e.g. `[elementor-template]` shortcodes). Adv_Tabs and Data_Table override this. |

## Architecture Diagram

```text
╔════════════════════════════════════════════════════════════════════╗
║ EDITOR PHASE — user manipulates the panel                          ║
║                                                                    ║
║   register_controls()                                              ║
║       │ defines control ids, types, defaults, conditions           ║
║       ▼                                                            ║
║   Elementor renders the panel (vanilla controls)                   ║
║       │                                                            ║
║       ▼  user changes a control                                    ║
║   Elementor updates its in-memory settings object                  ║
║       │                                                            ║
║       ▼                                                            ║
║   Editor preview iframe re-runs render() with new settings         ║
║       │  (server-side render, returned to iframe via REST)         ║
║       ▼                                                            ║
║   User clicks Update                                               ║
║       │                                                            ║
║       ▼                                                            ║
║   Elementor saves _elementor_data post meta (full JSON tree)       ║
║       │                                                            ║
║       ▼ elementor/editor/after_save                                ║
║   EA's Elements_Manager updates _eael_widget_elements (slug list)  ║
╚════════════════════════════════════════════════════════════════════╝

╔════════════════════════════════════════════════════════════════════╗
║ FRONTEND RENDER PHASE — page request                               ║
║                                                                    ║
║   Elementor reads _elementor_data → walks the tree                 ║
║       │                                                            ║
║       ▼  for each widget node                                      ║
║   Widget instance is constructed with its settings dict            ║
║       │                                                            ║
║       ▼                                                            ║
║   render() is called                                               ║
║       │                                                            ║
║       ▼                                                            ║
║   $settings = $this->get_settings_for_display()                    ║
║       │  resolves defaults from register_controls()                ║
║       │  resolves __dynamic__ entries to actual values             ║
║       │  flattens Group control prefixes                           ║
║       │  exposes Responsive controls as base + _tablet + _mobile   ║
║       ▼                                                            ║
║   render() builds HTML using add_render_attribute()                ║
║       │  outputs <div ...> with data-* attrs and escaped content   ║
║       ▼                                                            ║
║   Elementor optionally wraps in .elementor-widget-container        ║
║       │  unless has_widget_inner_wrapper() returns false           ║
║       │  AND/OR e_optimized_markup experiment is active            ║
║       ▼                                                            ║
║   Markup goes into the page response                               ║
╚════════════════════════════════════════════════════════════════════╝
```

## Hook Timing

Settings flow doesn't fire many EA-owned hooks — most of the data lifecycle is Elementor core. The relevant points where EA can hook in:

| Hook | Owner | When | Use case |
| ---- | ----- | ---- | -------- |
| `elementor/widget/render_content` (filter) | Elementor core | Wraps every widget's rendered output | EA uses this rarely; mostly for global wrapping |
| `elementor/widget/before_render_content` (action) | Elementor core | Before any widget renders | Setup hooks (rare in EA) |
| `elementor/editor/after_save` | Elementor core | Editor save complete | EA's `Elements_Manager` listens here ([asset-loading docs](asset-loading.md)) |
| `elementor/element/{widget_name}/{section}/before_section_end` | Elementor core | Inject controls into another widget's panel | Used for Pro-style cross-widget control injection |
| `elementor/dynamic_tags/register` | Elementor core | Register custom dynamic tags | EA Pro uses this; Lite doesn't expose dynamic tags itself |

For data-flow concerns specifically, the lifecycle is owned by Elementor — EA's job is to call the right APIs (`get_settings_for_display`, `add_render_attribute`) inside `render()`.

## Data Flow

The complete trip from "user edits a control" to "browser receives correct HTML":

1. **`register_controls()` runs once** (per page load on the editor side, once per render on the frontend side). It defines the schema — control ids, types, defaults, conditions, selectors. Elementor uses the returned schema to render the panel.
2. **User changes a control in the panel.** Elementor's editor JS updates its in-memory document state. For controls with `selectors` (live CSS), the change applies immediately via JS in the preview iframe. For controls without `selectors` (data that affects markup), Elementor triggers a server-side re-render.
3. **Server-side re-render.** Elementor calls the widget's `render()` again with updated settings. The output replaces the existing widget HTML in the preview iframe.
4. **User clicks Update.** Elementor serialises the entire document tree to JSON and writes it to `_elementor_data` post meta. The schema for each widget node is `{ id, elType, widgetType, settings, elements }`.
5. **`elementor/editor/after_save` fires.** EA's [`Elements_Manager::eael_elements_cache`](../../includes/Classes/Elements_Manager.php#L63) writes the slug list to `_eael_widget_elements` (used by `Asset_Builder`, not by render).
6. **Visitor loads the page.** Elementor reads `_elementor_data`, walks the tree, instantiates each widget class with its node's `settings` dict.
7. **`render()` is called.** Inside `render()`, `$this->get_settings_for_display()` resolves the final settings dict:
   - Fills in defaults from `register_controls()` for any missing keys.
   - Resolves `__dynamic__` entries (dynamic tags) to their concrete values via `Plugin::$instance->dynamic_tags`.
   - Group controls are flattened: a `Group_Control_Typography` named `eael_typography` produces flat keys like `eael_typography_typography`, `eael_typography_font_family`, `eael_typography_font_size`, `eael_typography_font_weight`, `eael_typography_line_height`, `eael_typography_letter_spacing` — each one a separate top-level entry in `$settings`.
   - Responsive controls expose three keys: `name` (desktop), `name_tablet`, `name_mobile`. Elementor's CSS-injection layer handles which one applies via media queries; `render()` typically uses only the desktop value, or branches based on what the widget needs.
8. **`render()` outputs HTML.** Best practice: `add_render_attribute('handle', 'class', 'value')` to build attribute strings, then `print_render_attribute_string('handle')` inside the markup. Elementor handles escaping when the attribute is printed via this API.
9. **Elementor decides whether to wrap.** By default Elementor wraps every widget's output in `<div class="elementor-widget-container">`. If the widget overrides `has_widget_inner_wrapper()` to return false (often gated on `Helper::eael_e_optimized_markup()`), Elementor skips that wrapper and emits the widget's own root element directly. This affects CSS selectors that count on `.elementor-widget-container`.

## `$settings` Reference (by control type)

What you actually receive in `render()` for each control type, with concrete examples:

### TEXT / TEXTAREA

```php
$settings['eael_fancy_text_prefix'] // => 'This is the '
```

If `'dynamic' => ['active' => true]` is on the control and the user picked a dynamic tag, `get_settings_for_display()` returns the resolved value. Without `get_settings_for_display()`, you'd get a `__dynamic__` shortcode-like string instead.

### NUMBER

```php
$settings['eael_fancy_text_speed'] // => 50  (always a string of digits in raw, integer-ish in display)
```

NUMBER values arrive as strings most often; cast with `(int)` if you need arithmetic.

### SELECT / CHOOSE / SWITCHER

```php
$settings['eael_fancy_text_style']     // SELECT  => 'style-1'
$settings['eael_fancy_text_alignment'] // CHOOSE  => 'center'
$settings['eael_fancy_text_loop']      // SWITCHER => 'yes' or '' (empty string when off)
```

The SWITCHER convention in EA: `'return_value' => 'yes'`. So check `if ( 'yes' === $settings['key'] )`, not `if ( $settings['key'] )` — empty string is truthy with loose comparison risk.

### COLOR

```php
$settings['eael_fancy_text_prefix_color'] // => '#000000' or ''
```

Empty string when the user hasn't set a color. If using inside `style="color: ...;"`, branch on emptiness to avoid producing invalid CSS.

### DIMENSIONS

```php
$settings['eael_fancy_text_strings_padding']
// => [
//   'top' => '10', 'right' => '10', 'bottom' => '10', 'left' => '10',
//   'unit' => 'px', 'isLinked' => true,
// ]
```

The whole array. When using `selectors` (CSS injection), Elementor formats this for you via `{{TOP}}{{UNIT}}` placeholders. When you need the value in PHP, read the components.

### SLIDER

```php
$settings['eael_fancy_text_strings_border_radius']
// => [ 'size' => 5, 'unit' => 'px' ]
```

`size` is the numeric, `unit` is the unit string.

### MEDIA

```php
$settings['eael_image']
// => [ 'id' => 123, 'url' => 'https://.../image.jpg', 'alt' => '...', 'source' => 'library' ]
```

Always check `id > 0` before treating it as a real attachment; placeholder media has `id = 0`.

### REPEATER

```php
$settings['eael_fancy_text_strings']
// => [
//   [ '_id' => 'abc123', 'eael_fancy_text_strings_text_field' => 'First string' ],
//   [ '_id' => 'def456', 'eael_fancy_text_strings_text_field' => 'Second string' ],
//   [ '_id' => 'ghi789', 'eael_fancy_text_strings_text_field' => 'Third string' ],
// ]
```

Array of items. Each item is a dict keyed by the field ids defined on the Repeater. The `_id` key is auto-generated by Elementor and stable across edits — use it as a key for `addEventListener`-style runtime hooks. Empty repeater = empty array, not null.

### Group_Control_*

```php
// Group_Control_Typography named 'eael_typography' produces:
$settings['eael_typography_typography']      // 'yes' or '' — master toggle
$settings['eael_typography_font_family']     // 'Roboto'
$settings['eael_typography_font_size']       // [ 'size' => 22, 'unit' => 'px' ]
$settings['eael_typography_font_weight']     // '600'
$settings['eael_typography_line_height']     // [ 'size' => 1.2, 'unit' => 'em' ]
$settings['eael_typography_letter_spacing']  // [ 'size' => 0, 'unit' => 'px' ]
```

Group controls do not return a nested array — they flatten. The group name becomes a prefix on every constituent property. If your group is named `eael_my_typo`, you read `$settings['eael_my_typo_font_family']`, etc.

### Responsive controls (`add_responsive_control`)

```php
$settings['eael_fancy_text_alignment']         // desktop  => 'center'
$settings['eael_fancy_text_alignment_tablet']  // tablet   => 'left' or ''
$settings['eael_fancy_text_alignment_mobile']  // mobile   => 'left' or ''
```

When the user hasn't set a tablet/mobile value, the key holds an empty string — Elementor's CSS layer falls back to the desktop value via media queries. In PHP, branch on emptiness if you need device-aware values.

### Conditional control behaviour

A control hidden by `condition` or `conditions` **still saves its value**. Don't infer state from absence — always check the parent control's value defensively in `render()`.

## `condition` vs `conditions`

Two forms with subtly different syntax. Pick the simpler one when possible.

### `condition` (singular) — single dependency, AND only

```php
$this->add_control(
    'eael_fancy_text_speed',
    [
        'label' => __( 'Typing Speed', 'essential-addons-for-elementor-lite' ),
        'type'  => Controls_Manager::NUMBER,
        'condition' => [
            'eael_fancy_text_transition_type' => 'typing',
        ],
    ]
);
```

Multiple keys in `condition` are AND-combined. Use this 95% of the time.

### `conditions` (plural) — OR / nested AND/OR

```php
'conditions' => [
    'relation' => 'or',
    'terms' => [
        [
            'name'     => 'eael_fancy_text_color_selector',
            'operator' => '==',
            'value'    => 'solid-color',
        ],
        [
            'name'     => 'eael_fancy_text_style',
            'operator' => '==',
            'value'    => 'style-2',
        ],
    ],
],
```

`relation` is `'or'` or `'and'`. `terms` is a list — each item is either a leaf (`name + operator + value`) or another nested condition group. Use this only when single-form `condition` cannot express the dependency.

**Codebase usage:** 1,942 single `condition` vs 99 plural `conditions` — the simpler form dominates by ~20:1.

## Common Pitfalls

### Calling `get_settings()` instead of `get_settings_for_display()`

Always use the latter in `render()`. The former returns raw settings without:

- Dynamic-tag resolution (you'd see a `__dynamic__` reference instead of the resolved value).
- Default-value resolution (missing keys stay missing).
- Group/Responsive flattening guarantees.

This is a top-three audit finding — see [`widget-review`](../../.claude/skills/widget-review/SKILL.md) Axis 1.

### Treating SWITCHER values as booleans

`$settings['my_switcher']` is `'yes'` (string) or `''` (empty string). Don't write `if ( $settings['my_switcher'] === true )` — it's never true. Use `'yes' === $settings['my_switcher']`.

### Reading hidden-control values without guarding

A control hidden by `condition` still has a value. If a user previously set `transition_type = typing` and `cursor = yes`, then switched to `fadeIn`, the `cursor` key is still `'yes'` in `$settings`. Render accordingly — don't assume hidden = unset.

### `condition` value not in the linked control's options

```php
'condition' => [
    'eael_fancy_text_transition_type' => 'fancy',  // 'fancy' is not in the SELECT options
],
```

The control will never become visible (dead control). Audit candidates with `widget-review` skill. Note: this is also the root cause of the documented `'fancy'` branch in [Fancy_Text.php:646](../../includes/Elements/Fancy_Text.php#L646) — render-time check for a value the Lite-side select never offers.

### Group control prefix surprises

If you name a Group_Control_Typography `'typography'`, the master toggle is `$settings['typography_typography']`. Re-read your section above when the group name itself contains `typography`.

### Forgetting `'dynamic' => ['active' => true]`

Without it, the dynamic-tag picker doesn't appear next to the control. Users assume EA doesn't support dynamic tags for that field; bug reports follow. EA has 221 dynamic-active controls across widgets — turning it off should be deliberate.

### Editor preview vs frontend divergence via `is_edit_mode()`

`Plugin::$instance->editor->is_edit_mode()` returns true in the editor iframe. Code branched on it can produce different HTML in editor vs frontend. Symptoms: works in editor, broken on frontend (or vice versa). The [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) skill's Editor-mismatch trace path catches these.

### `has_widget_inner_wrapper()` and theme CSS

When `has_widget_inner_wrapper()` returns false, Elementor skips `.elementor-widget-container`. Themes that target `.elementor-widget-container` for spacing will visually differ. The widget itself must absorb the layout responsibility (margin, max-width, etc.) on its own root element when it opts out.

### `is_dynamic_content()` and Elementor caching

When a widget renders content that varies per-request (e.g. `[elementor-template]` shortcode templates), Elementor's optimisation layer can over-cache. The widget should override `is_dynamic_content()` to return true. EA's [Adv_Tabs](../../includes/Elements/Adv_Tabs.php#L64) and [Data_Table](../../includes/Elements/Data_Table.php#L61) do this; new widgets with similar patterns should follow.

## Debugging Guide

When a setting "isn't working":

1. **Confirm the setting is saved.** Open the post in the WP DB, find `_elementor_data`, search for the widget's id, find the `settings` dict — is the key there with the expected value?
2. **Confirm `render()` reads it.** Add `error_log( print_r( $settings, true ) )` at the top of `render()` after `get_settings_for_display()`. Reload the frontend, check `wp-content/debug.log`.
3. **Group control flattening.** If you're reading `$settings['typography']` and it's empty, the group flattens to `$settings['typography_typography']` and friends — you're reading a key that doesn't exist as nested array.
4. **Dynamic tag not resolving.** Confirm `'dynamic' => ['active' => true]` on the control. Confirm you're calling `get_settings_for_display()`, not `get_settings()`. If the dynamic value still doesn't resolve, check `Plugin::$instance->dynamic_tags` — the tag's class might be missing or its `render()` might be returning empty.
5. **Editor preview ≠ frontend.** Search `render()` for `is_edit_mode()` branches. Step through each branch mentally — do they produce the same visible HTML?
6. **Wrapper missing.** If a CSS rule targeting `.elementor-widget-container .your-thing` stopped matching, check `has_widget_inner_wrapper()` on the widget — has it started returning false?

## Worked Example — Fancy_Text settings flow

A user opens the editor, drops Fancy Text, types "Hello " in Prefix, adds two Repeater items (`Foo`, `Bar`), picks `fadeIn`, sets typography, saves.

1. **Editor state after typing prefix:** Elementor's in-memory state for this widget node is updated to `{ id: 'abc1', elType: 'widget', widgetType: 'eael-fancy-text', settings: { eael_fancy_text_prefix: 'Hello ', eael_fancy_text_strings: [{...}, {...}, {...}], ... } }`. The preview iframe re-runs `render()` with the new settings.
2. **User adds Repeater items.** Each Repeater `add` produces a new entry in `eael_fancy_text_strings`, with auto-assigned `_id`. The dict for each item is keyed by the Repeater's field ids — for Fancy Text, just `eael_fancy_text_strings_text_field`.
3. **User picks `fadeIn`.** `eael_fancy_text_transition_type` becomes `'fadeIn'`. The cursor controls (gated on `condition: transition_type === 'typing'`) become hidden in the panel; their saved values remain in `settings`.
4. **User sets typography.** The Group_Control_Typography fields all get keys: `eael_fancy_text_strings_typography_typography = 'yes'`, `_font_family`, `_font_size = { size: 22, unit: 'px' }`, etc.
5. **User clicks Update.** Elementor serialises the full document tree to `_elementor_data`. EA's `Elements_Manager` extracts the slug list `['fancy-text']` and writes it to `_eael_widget_elements`.
6. **Visitor loads the page.** Elementor walks `_elementor_data`, finds the Fancy Text node, instantiates `Fancy_Text` with the saved settings. `render()` calls `get_settings_for_display()` → defaults filled in (Speed: 50, Loop: yes, Cursor: yes — even though hidden), Repeater items intact, Group control properties spread out flat.
7. **`render()` outputs HTML.** `eael_fancy_text_loop` is `'yes'`, so the JS will loop. `eael_fancy_text_cursor` is `'yes'`, but transition is `fadeIn` — the JS branch ignores cursor for non-typing modes. The strings get joined with `|` for the `data-fancy-text` attribute.
8. **Elementor wraps in `.elementor-widget-container`** since Fancy_Text doesn't override `has_widget_inner_wrapper()` — except wait, it does: [`Fancy_Text.php:64`](../../includes/Elements/Fancy_Text.php#L64) returns `! Helper::eael_e_optimized_markup()`. So if the experiment is active, no wrapper; if not, default wrapper.

## Architecture Decisions

### Always call `get_settings_for_display()` in `render()`

- **Context:** `get_settings()` returns raw settings; dynamic tags arrive as `__dynamic__` references, not values.
- **Decision:** Mandate `get_settings_for_display()` in widget render methods (codified in [`widget-development.md`](../../.claude/rules/widget-development.md) and [`widget-review`](../../.claude/skills/widget-review/SKILL.md) Axis 1).
- **Alternatives rejected:** Manually resolving dynamic tags per call site (error-prone, missed cases); using `get_settings()` and filtering after (duplicate logic).
- **Consequences:** Dynamic-tag values "just work" inside widgets without per-widget resolution code. Cost is a minor performance overhead per call vs raw settings.

### Override `has_widget_inner_wrapper()` to honour the optimized-markup experiment

- **Context:** Elementor introduced an `e_optimized_markup` experiment that removes `.elementor-widget-container` for cleaner DOM. Some widgets need the wrapper (forms, fixed-layout components); others can drop it (most display widgets).
- **Decision:** Widgets that benefit from leaner markup override `has_widget_inner_wrapper()` to return `! Helper::eael_e_optimized_markup()`. When the experiment is active, the wrapper is dropped; otherwise, default wrapper is preserved for backward compatibility.
- **Alternatives rejected:** Always wrap (misses optimization opportunity); always unwrap (breaks themes targeting `.elementor-widget-container`); per-widget hardcoded choice without checking the experiment (loses graceful fallback).
- **Consequences:** Widgets that adopted this pattern (Adv_Tabs, NinjaForms, FluentForm, Post_Grid, Betterdocs_Search_Form, Data_Table, NFT_Gallery, Fancy_Text, etc.) follow Elementor's experiment opt-in cleanly. Themes targeting `.elementor-widget-container` continue to work for users not opted-in.

### Override `is_dynamic_content()` for template-using widgets

- **Context:** Adv_Tabs and Data_Table support tab/cell content of type "template" (Elementor saved templates). Cached output on the page would freeze the template's content.
- **Decision:** Override `is_dynamic_content()` to return true when any tab/cell uses template-type content. Elementor's caching layer respects this and skips static caching.
- **Alternatives rejected:** Disable caching globally for these widgets (perf hit on the static cases); never cache (misses the optimization for non-template content); document the limitation (users wouldn't read it).
- **Consequences:** Widgets compute `is_dynamic_content()` per-render — a small cost in exchange for accurate caching behaviour.

## Known Limitations

- **No public schema for Group control flattening.** The flattened key names (`{group_name}_{field_name}`) are convention, not contract — Elementor could change them. EA widgets reading group properties must keep up if Elementor restructures.
- **Repeater `_id` is opaque.** Useful as a stable key but not human-readable. Don't try to derive meaning from `_id` strings.
- **Editor preview vs frontend can drift if `is_edit_mode()` branches accumulate.** Each new branch is a divergence opportunity. Audit periodically.
- **Dynamic tags don't have a per-widget compatibility matrix in this doc.** If a widget control sets `'dynamic' => ['active' => true]` but the user's specific dynamic tag class doesn't return the expected type (e.g. an array when the widget expects a string), the widget can break in surprising ways. No central registry of which widget supports which tag types.
- **Responsive control fallback in PHP is manual.** Elementor's CSS-injection layer handles `_tablet` / `_mobile` automatically via media queries, but PHP code reading these keys must implement its own fallback if it needs device-aware values.

## Cross-References

- **Skills:** [`widget-review`](../../.claude/skills/widget-review/SKILL.md) — Axis 1 (Correctness) catches `get_settings()` misuse and `condition` errors documented here.
- **Skills:** [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) — Editor-mismatch and Render trace paths land in this subsystem.
- **Skills:** [`elementor-controls`](../../.claude/skills/elementor-controls/SKILL.md) — defines the panel side of the data flow described here; see Step 3 (conditions) and Step 4 (EA-specific patterns).
- **Skills:** [`new-widget`](../../.claude/skills/new-widget/SKILL.md) — Phase 2 sets up the `register_controls()` + `render()` pair correctly from the start.
- **Rules:** [`widget-development.md`](../../.claude/rules/widget-development.md) — Render Method section codifies the `get_settings_for_display()` rule.
- **Rules:** [`php-standards.md`](../../.claude/rules/php-standards.md) — escape and sanitization conventions for the values arriving in `$settings`.
- **Architecture:** [`asset-loading.md`](asset-loading.md) — handles the assets that the rendered HTML depends on; this doc covers what produces that HTML.
- **Architecture:** [`dynamic-data/`](dynamic-data/) — AJAX endpoints that re-fetch widget HTML at runtime; settings flow at runtime is the same as documented here.
- **Widget docs:** [`fancy-text.md`](../widgets/fancy-text.md) — concrete data-flow walkthrough for one widget.
