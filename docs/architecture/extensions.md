# Extensions Subsystem

How [`includes/Extensions/`](../../includes/Extensions/) is wired up — eleven plain PHP classes that augment existing Elementor elements (sections, columns, containers, common-element controls, the document itself) without ever extending `Widget_Base`. They share the activation surface (`eael_save_settings` option) with widgets but follow a fundamentally different architecture: no register_controls + render lifecycle, just a constructor that hooks into Elementor's existing element-controls registration cycle.

This doc answers the documentation gap raised in [issue #805](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/805): when to choose extension vs widget, how the registration loop works, what `'context' => 'edit'` vs `'view'` means for asset enqueueing, the action map of which Elementor hooks extensions wire into, and the `Promotion` pattern for Pro upsells.

## Overview

Extensions are toggleable behaviours that layer onto Elementor's existing UI surface. A user enables Reading Progress and a progress bar appears at the top of every post. A user enables Wrapper Link and a "Wrapper Link" control appears under every section, column, container, and widget — set its URL, the whole element becomes clickable. A user does **not** add an extension to a page like they add a widget; extensions are page-wide or section-wide effects.

The architecture follows three rules:

1. **Each extension is a plain PHP class** with a constructor that wires Elementor action hooks. No `Widget_Base` inheritance. No `register_controls()` / `render()` pair.
2. **The constructor itself is the entire setup.** All `add_action` calls happen in the constructor; the rest of the class is just hook callback methods.
3. **Activation is opt-in via the same `eael_save_settings` option as widgets.** [`Bootstrap::register_extensions()`](../../includes/Traits/Elements.php#L101) walks `$registered_extensions`, instantiates the class only if the slug is in the active list. Disabled extensions never instantiate, so disabled extensions cost nothing at runtime.

The Promotion extension is the one exception to rule 3 — it is force-enabled regardless of user setting, because its job is to advertise Pro features inside the Elementor editor. It only does work when Pro is **not** active.

## Components

| File / Symbol | Lines | Role |
| ------------- | ----- | ---- |
| [`includes/Extensions/`](../../includes/Extensions/) | 6,118 total | Eleven extension classes (plus `index.php`) |
| [`includes/Traits/Elements::register_extensions`](../../includes/Traits/Elements.php#L101) | ~17 | The instantiation loop — reads active list from `get_settings`, force-pushes `'promotion'`, instantiates each enabled class |
| [`includes/Classes/Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | line 114 | `$this->registered_extensions = apply_filters('eael/registered_extensions', $GLOBALS['eael_config']['extensions']);` — third-party filter point |
| [`includes/Classes/Bootstrap.php:122`](../../includes/Classes/Bootstrap.php#L122) | line 122 | Calls `register_extensions()` during Bootstrap construction |
| [`includes/Traits/Core::set_default_values`](../../includes/Traits/Core.php#L153) | line 153 | `array_fill_keys` over both elements + extensions → all default to enabled in `eael_save_settings` on first install |
| [`config.php:1338`](../../config.php#L1338) | line 1338 | `'extensions'` key in the registry — slug → class + optional `dependency` block (same shape as `'elements'`) |
| Asset handling | — | Same `Asset_Builder` machinery as widgets — the registry's `dependency` block follows the identical schema. See [`asset-loading.md`](asset-loading.md). |
| Storage option `eael_save_settings` | `wp_options` | The single activation source for both elements and extensions |

### Inventory (11 extensions)

| Slug | Class | Lines | Hooks into | Default behaviour |
| ---- | ----- | ----- | ---------- | ----------------- |
| `promotion` | `Promotion` | 221 | `elementor/element/section/section_layout/after_section_end`, `elementor/element/common/_section_style/after_section_end`, `elementor/element/column/section_advanced/after_section_end`, `elementor/documents/register_controls` | Always enabled (force-pushed in `register_extensions`); only does work when Pro is not active |
| `custom-js` | `Custom_JS` | 75 | `elementor/documents/register_controls` | Per-page custom JS field on document settings |
| `hover-effect` | `Hover_Effect` | 1,636 | `elementor/element/common/_section_style/after_section_end`, `elementor/frontend/before_render` | Adds Hover Effect controls under every widget's Style tab; modifies render attributes at frontend |
| `image-masking` | `Image_Masking` | 639 | Per-element control sections + frontend asset injection | SVG-based image mask controls |
| `liquid-glass-effect` | `Liquid_Glass_Effect` | 680 | Per-element control sections + frontend rendering | Glassmorphism / liquid-glass visual effect controls |
| `post-duplicator` | `Post_Duplicator` | 186 | Admin-side `post_row_actions`, `admin_action_*` | Adds "Duplicate" link to admin post list |
| `reading-progress` | `Reading_Progress` | 218 | `elementor/documents/register_controls` (priority 10) + frontend asset enqueue | Page-level reading-progress bar |
| `scroll-to-top` | `Scroll_to_Top` | 459 | `elementor/documents/register_controls` + frontend rendering | Floating scroll-to-top button |
| `table-of-content` | `Table_of_Content` | 1,310 | `elementor/documents/register_controls` (priority 10) + frontend rendering | Auto-generated TOC from headings |
| `vertical-text-orientation` | `Vertical_Text_Orientation` | 559 | Per-element control sections + frontend rendering | CSS `writing-mode` helpers |
| `wrapper-link` | `Wrapper_Link` | 135 | `elementor/element/common/_section_style/after_section_end`, `elementor/element/column/section_advanced/after_section_end`, `elementor/element/section/section_advanced/after_section_end`, `elementor/element/container/section_layout/after_section_end`, `elementor/frontend/before_render` | Whole-element clickable-link control |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ INIT PHASE                                                       ║
║                                                                  ║
║   plugins_loaded → Bootstrap::__construct                        ║
║       │                                                          ║
║       ▼                                                          ║
║   Bootstrap.php:114                                              ║
║   $registered_extensions = apply_filters(                        ║
║       'eael/registered_extensions',                              ║
║       $GLOBALS['eael_config']['extensions']                      ║
║   )                                                              ║
║       │                                                          ║
║       ▼                                                          ║
║   Bootstrap.php:122                                              ║
║   $this->register_extensions()                                   ║
║       │ (Elements trait method)                                  ║
║       ▼                                                          ║
║   register_extensions() runs:                                    ║
║       $active_elements = (array) $this->get_settings()           ║
║       array_push($active_elements, 'promotion')  // ALWAYS ON    ║
║       foreach ($registered_extensions as $key => $extension):    ║
║           if (! in_array($key, $active_elements)) continue       ║
║           if (class_exists($extension['class'])):                ║
║               new $extension['class']  // safe instantiation     ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼
╔══════════════════════════════════════════════════════════════════╗
║ EXTENSION CONSTRUCTOR PHASE (per active extension)               ║
║                                                                  ║
║   Each constructor wires Elementor action hooks. Common targets: ║
║                                                                  ║
║   • elementor/documents/register_controls                        ║
║       → adds controls to document settings                       ║
║       → used by: Custom_JS, Reading_Progress, Table_of_Content,  ║
║                  Scroll_to_Top                                   ║
║                                                                  ║
║   • elementor/element/common/_section_style/after_section_end    ║
║       → adds controls under EVERY widget's Style tab             ║
║       → used by: Hover_Effect, Wrapper_Link, Promotion           ║
║                  (multiple Promotion features)                   ║
║                                                                  ║
║   • elementor/element/section/section_advanced/after_section_end ║
║   • elementor/element/section/section_layout/after_section_end   ║
║   • elementor/element/column/section_advanced/after_section_end  ║
║   • elementor/element/container/section_layout/after_section_end ║
║       → adds controls to specific element types                  ║
║       → used by: Wrapper_Link, Promotion                         ║
║                                                                  ║
║   • elementor/frontend/before_render                             ║
║       → modifies render attributes at runtime (priority 100)     ║
║       → used by: Hover_Effect, Wrapper_Link                      ║
║                                                                  ║
║   • elementor/element/common/_section_style/after_section_end    ║
║       → admin: post_row_actions, admin_action_*                  ║
║       → used by: Post_Duplicator                                 ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼ user opens Elementor editor
╔══════════════════════════════════════════════════════════════════╗
║ EDITOR REGISTRATION PHASE                                        ║
║                                                                  ║
║   Elementor fires elementor/element/<type>/<section>/...         ║
║       │                                                          ║
║       ▼                                                          ║
║   Each extension's hooked callback runs:                         ║
║     $element->start_controls_section(...)                        ║
║     $element->add_control(...)                                   ║
║     $element->end_controls_section(...)                          ║
║       │                                                          ║
║       ▼                                                          ║
║   Result: extension's controls appear inside Elementor's panel   ║
║   for the appropriate element types                              ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼ user saves the page; visitor loads it
╔══════════════════════════════════════════════════════════════════╗
║ ASSET ENQUEUE PHASE (Asset_Builder)                              ║
║                                                                  ║
║   Asset_Builder::frontend_asset_load fires on wp_enqueue_scripts ║
║       │                                                          ║
║       ▼                                                          ║
║   Reads $registered_extensions (passed in via constructor) and   ║
║   walks each extension's `dependency` block in config.php        ║
║       │                                                          ║
║       ▼                                                          ║
║   Per-extension entries with type='self' or type='lib' and       ║
║   context='view' or 'edit' are enqueued accordingly.             ║
║   Extensions tend to use context='edit' because most extension   ║
║   work happens inside the Elementor editor (e.g. Reading_        ║
║   Progress's edit JS).                                           ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼
╔══════════════════════════════════════════════════════════════════╗
║ FRONTEND RENDER PHASE                                            ║
║                                                                  ║
║   Elementor fires elementor/frontend/before_render               ║
║       │                                                          ║
║       ▼                                                          ║
║   Extensions hooked at priority 100 (Hover_Effect,               ║
║   Wrapper_Link) modify render attributes:                        ║
║     $element->add_render_attribute(...)                          ║
║       │                                                          ║
║       ▼                                                          ║
║   Output HTML carries the extra classes / data-* attrs           ║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

Extension subsystem hooks split into three categories:

### Subsystem-level hooks (EA-owned)

| Hook | Owner | When | Purpose |
| ---- | ----- | ---- | ------- |
| `eael/registered_extensions` (filter) | EA Bootstrap | At Bootstrap construction (`Bootstrap.php:114`) | Third-party filter to add / remove extensions from the registry before instantiation |
| `eael/registered_extensions` consumer | EA Bootstrap | Same | Reads the filter result, passes to `Asset_Builder` constructor |

### Extension-level hooks (per-extension constructors)

Each extension's constructor wires `add_action` calls. Common patterns:

| Elementor hook | Priority typical | Used by | Purpose |
| -------------- | ---------------- | ------- | ------- |
| `elementor/documents/register_controls` | 10 (default) | Custom_JS, Reading_Progress, Table_of_Content, Scroll_to_Top | Add controls to document-level (page) settings |
| `elementor/element/common/_section_style/after_section_end` | 10 | Hover_Effect, Wrapper_Link, Promotion (multiple features) | Add controls under EVERY widget's Style tab |
| `elementor/element/section/section_layout/after_section_end` | 10 | Promotion | Section's Layout tab |
| `elementor/element/section/section_advanced/after_section_end` | 10 | Wrapper_Link, Promotion | Section's Advanced tab |
| `elementor/element/column/section_advanced/after_section_end` | 10 | Wrapper_Link, Promotion | Column's Advanced tab |
| `elementor/element/container/section_layout/after_section_end` | 10 | Wrapper_Link | Container's Layout tab |
| `elementor/frontend/before_render` | 100 | Hover_Effect, Wrapper_Link | Runtime modification of element render attributes |
| `post_row_actions` (WP core) | 10 | Post_Duplicator | Add "Duplicate" link in admin post list |
| `admin_action_*` (WP core) | 10 | Post_Duplicator | Handle duplicate request |

The `Promotion` extension wires a much larger set of these because each Pro feature it advertises requires a separate `start_controls_section` call on its own target element type.

### Asset hooks (Asset_Builder-owned)

Standard Asset_Builder pipeline. See [`asset-loading.md`](asset-loading.md). The extension's `dependency` block in `config.php` is read on `wp_enqueue_scripts` priority 100 if the extension is active.

## Data Flow

End-to-end activation lifecycle for a typical extension (Reading_Progress, with default-enable):

1. **Plugin activation.** `Core::set_default_values()` runs, fills `eael_save_settings` with all element + extension keys mapped to `1`. So `reading-progress => 1` exists in the option from day one.
2. **Bootstrap loads on every request.** [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) reads `$GLOBALS['eael_config']['extensions']` (the registry), passes through the `eael/registered_extensions` filter (allowing third-party additions / removals), stores the result on `$this->registered_extensions`.
3. **`register_extensions()` fires.** Reads `eael_save_settings` via `get_settings()`, gets the active list. Pushes `'promotion'` regardless of setting (Promotion is force-enabled). Loops the registry; for each enabled key, instantiates the class.
4. **Reading_Progress constructor runs.** Calls `add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10)`.
5. **Asset_Builder is constructed** with `$registered_extensions` ([`Bootstrap.php:128`](../../includes/Classes/Bootstrap.php#L128)) — Asset_Builder now knows about every active extension's CSS/JS dependencies.
6. **User opens Elementor editor for a page.** Elementor fires `elementor/documents/register_controls`. Reading_Progress's `register_controls` callback runs, calling `start_controls_section / add_control / end_controls_section` on the document instance — adding a "Reading Progress" panel to the page settings.
7. **User configures Reading Progress** (enables it, picks colour, height). Settings save into `_elementor_data` post meta along with all other Elementor settings.
8. **Visitor loads the page.** `Asset_Builder::frontend_asset_load` enqueues per-page bundles. Reading_Progress's edit JS (per the registry's `context => 'edit'` flag) is queued only when relevant.
9. **`elementor/frontend/before_render` fires** for each element on the page (no extension hooks this for Reading_Progress; if it did, it could add render attributes here).
10. **Reading_Progress reads its settings** from the document and emits the progress-bar markup at frontend render. Or, if its frontend implementation is JS-driven, the JS reads document data attributes and renders the bar client-side.

For the `Promotion` extension specifically, steps 6-10 differ because Promotion only registers controls (the "teaser" panels) and doesn't render anything user-visible at frontend.

## Configuration & Extension Points

### Registry schema (`config.php` `extensions` key)

```php
'extensions' => [
    'promotion' => [
        'class' => '\Essential_Addons_Elementor\Extensions\Promotion',
    ],
    'custom-js' => [
        'class' => '\Essential_Addons_Elementor\Extensions\Custom_JS',
    ],
    'reading-progress' => [
        'class' => '\Essential_Addons_Elementor\Extensions\Reading_Progress',
        'dependency' => [
            'js' => [
                [
                    'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/reading-progress.min.js',
                    'type'    => 'self',           // 'self' = built from src/, 'lib' = vendor in lib-view/
                    'context' => 'edit',           // 'view' = frontend, 'edit' = Elementor editor
                ],
            ],
        ],
    ],
    // …more extensions…
],
```

The schema is **identical** to the `elements` key — same `class`, same `dependency` block with `css` / `js` arrays, each entry with `file` + `type` + `context`. Asset_Builder treats extensions and widgets symmetrically once the registry is read.

### `'context' => 'edit'` vs `'context' => 'view'`

Extensions tend to use `'edit'` because:

- Many extensions add UI inside the Elementor editor (control panels, preview modifiers, custom JS that runs during editing).
- The frontend rendering of an extension is often inline CSS or render-attribute additions — not a separate JS file.

Widgets tend to use `'view'` because their JS runs at frontend (typing animation, swiper, modal, etc.).

Asset_Builder enqueues:

- `'view'` context only on frontend pageloads
- `'edit'` context only inside the Elementor editor iframe (it's the JS context the editor uses for in-place rendering)

If your extension's JS needs to run on the published frontend, use `'view'`. If it runs only inside the Elementor editor (configuring previews, live updates while editing), use `'edit'`.

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/registered_extensions` | `Bootstrap.php:114` | Add / remove extensions from the registry before Bootstrap reads it. Useful for child plugins / themes that want to suppress an EA extension or add their own. |
| `eael/pro_enabled` | Multiple call sites; relevant here for `Promotion::__construct` | Pro plugin returns `true` via this filter; Promotion's constructor uses it to short-circuit (don't show upsell teasers when Pro is active) |

### Activation

Activation is via the same `eael_save_settings` option as widgets. The Setup Wizard ([`quick-setup.md`](quick-setup.md)) writes to this option; the EA settings page also writes to it. Disabling an extension in either UI prevents instantiation on the next request.

The exception: `'promotion'` is always force-pushed into the active list inside `register_extensions()`, so the user cannot disable Promotion through normal UI. The class itself short-circuits when Pro is active, so this is harmless.

### Adding a new extension — checklist

1. **Create the PHP class** in `includes/Extensions/<ClassName>.php`:
   - Namespace `Essential_Addons_Elementor\Extensions`
   - Plain class, no `Widget_Base` inheritance
   - Constructor wires `add_action` calls — that's the entire setup
   - Hook callback methods are the rest of the class
2. **Register in `config.php`** under `'extensions'`:
   ```php
   'my-feature' => [
       'class' => '\Essential_Addons_Elementor\Extensions\My_Feature',
       // optionally add a 'dependency' block matching the widget schema
   ],
   ```
3. **Decide the activation policy.**
   - Default-enable on first install: works automatically — `Core::set_default_values` walks the entire registry.
   - Force-enable always: add the slug to the `array_push` line in [`Elements::register_extensions`](../../includes/Traits/Elements.php#L101) (only `promotion` does this today).
   - Default-disable: no special action, just don't add to default values; user enables via Setup Wizard or settings page.
4. **If the extension has assets**, list them in the `dependency` block. Use `'context' => 'edit'` for editor-only JS, `'context' => 'view'` for frontend.
5. **Per-extension docs**: add `docs/extensions/<slug>.md` following [`docs/extensions/README.md`](../extensions/README.md)'s 12-section checklist.
6. **`npm run build`** if you added source CSS/JS to `src/`.
7. **Test:** open Elementor editor, confirm controls appear in the right place; toggle the extension off in EA settings, confirm it disappears.

## The `Promotion` Pattern (Pro upsell injection)

`Promotion` is the documented pattern for "I want to show a Pro feature teaser inside Lite". Mechanics:

1. **Force-enabled**, so it always instantiates regardless of user setting.
2. **Constructor short-circuits** when Pro is active: `if (! apply_filters('eael/pro_enabled', false))`. When Pro is enabled, the constructor returns immediately and the extension wires no hooks.
3. **When Pro is not active**, the constructor wires hooks for each Pro feature it advertises. Each hook callback emits a "teaser" controls section with title + body + upgrade button. The teaser uses `Controls_Manager::RAW_HTML` so it's a styled marketing card, not a functional control.
4. **`teaser_template($texts)`** ([`Promotion.php:33`](../../includes/Extensions/Promotion.php#L33)) is the shared HTML factory — accepts `title` and `messages`, emits `.ea-nerd-box` with the upgrade link to `wpdeveloper.com/upgrade/ea-pro`.

This pattern is reusable: any new Pro feature you want to advertise inside Lite adds a new `add_action` in `Promotion::__construct` and a new method that calls `$this->teaser_template(...)`.

## Common Pitfalls

### Confusing "extension" with "widget"

A widget is a thing the user drags onto the canvas (Fancy Text, Adv Accordion). An extension is a behaviour layered onto existing elements (Wrapper Link, Hover Effect, Reading Progress). When in doubt: **does the user place this somewhere, or does it apply to existing things?** Place → widget. Apply → extension.

### `Promotion` running hooks inside Pro

If `Promotion::__construct` runs but the constructor's Pro check evaluates to false (e.g. timing issue where `eael/pro_enabled` filter hasn't been registered yet), the teasers will appear inside Pro alongside the real Pro features — embarrassing. Confirm Pro plugin registers the filter early enough (typically before `plugins_loaded` priority 100 where Lite's Bootstrap runs).

### `'context' => 'edit'` JS not loading on frontend

Extensions with edit-context assets (Reading_Progress, Table_of_Content) only enqueue their JS inside the Elementor editor. If your extension needs frontend JS, use `'context' => 'view'`. Asset_Builder treats the contexts strictly — there's no "load both" option.

### Extension instantiated but no controls appear in editor

Most likely the constructor's `add_action` wiring is wrong. Check:

- Is the Elementor hook name correct? (Common typo: `_section_style` vs `section_style`.)
- Is the priority appropriate? Some core hooks run early; if your callback runs before Elementor sets up the controls infrastructure, it can no-op silently.
- Is `class_exists($extension['class'])` returning true? PSR-4 autoloading should handle this, but a rename or namespace typo can produce a false miss.

### Hook target element type mismatch

`elementor/element/common/_section_style/after_section_end` fires for every widget. `elementor/element/section/section_layout/after_section_end` fires only for sections. If you hook the wrong scope, your control either doesn't appear or appears in places it shouldn't. The action map table above documents which extension uses which.

### `register_extensions` ordering

Extensions are instantiated in the order they appear in `config.php`. If extension A depends on a side effect of extension B's constructor, ordering matters. In practice, no current extensions have this dependency — but a future extension that does will surprise contributors.

### `array_push` with `'promotion'` is not idempotent

`Elements::register_extensions:104` appends `'promotion'` to the active list. Running `register_extensions` twice would double-push. The method is hooked once during Bootstrap, so this isn't a real bug, but if a future refactor hooks it from another path, watch out.

### Disabling Promotion via filter

`'promotion'` is force-pushed inside `register_extensions`, so `eael_save_settings` cannot disable it. The cleanest way to fully suppress the Promotion class is to filter it out of `eael/registered_extensions`:

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['promotion'] );
    return $exts;
} );
```

This removes it from the registry before `register_extensions` ever sees it. Documented for testing purposes; production should rarely need this.

## Debugging Guide

When an extension misbehaves:

1. **Confirm the extension class instantiated.** Add `error_log( __CLASS__ . ' constructed' )` at the top of the constructor. If the log line never appears, the activation chain is broken — check `eael_save_settings` for the slug; check `register_extensions` is hooked from Bootstrap; check `class_exists` returns true.
2. **Confirm the hooks wired.** `var_dump( has_action( 'elementor/element/common/_section_style/after_section_end' ) )` should show your callback registered. If not, the constructor ran but `add_action` didn't take.
3. **For controls not appearing in editor**: Elementor must fire the hook you registered. Try a more general hook (`elementor/element/common/_section_style/after_section_end` is the broadest) and confirm controls show up; if yes, narrow scope from there.
4. **For frontend rendering issues** (`elementor/frontend/before_render`): Hover_Effect and Wrapper_Link both hook this at priority 100. If multiple extensions hook the same priority, ordering between them is undefined. Use a lower priority (50) if you need to run before the others.
5. **For asset issues**: extension assets follow the same path as widget assets. See [`asset-loading.md § Debugging Guide`](asset-loading.md#debugging-guide).
6. **For `Promotion` showing inside Pro**: confirm `apply_filters('eael/pro_enabled', false)` returns true at the moment Promotion's constructor runs. The Pro plugin must register this filter before Bootstrap construction.

## Worked Example — Wrapper_Link end-to-end

The simplest non-trivial extension. 135 lines.

1. **Plugin activation.** `eael_save_settings` populated with `wrapper-link => 1`.
2. **Bootstrap loads.** `register_extensions` reads active list, finds `wrapper-link`, calls `new Wrapper_Link()`.
3. **Constructor ([`Wrapper_Link.php:17`](../../includes/Extensions/Wrapper_Link.php#L17)):**
   ```php
   add_action('elementor/element/common/_section_style/after_section_end', [$this, 'register_controls']);
   add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'register_controls']);
   add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'register_controls']);
   add_action('elementor/element/container/section_layout/after_section_end', [$this, 'register_controls']);
   add_action('elementor/frontend/before_render', [$this, 'before_render'], 100);
   ```
4. **User opens editor for any element** (widget, section, column, container). Elementor fires the matching `after_section_end`. `Wrapper_Link::register_controls($element)` runs, calls `start_controls_section('eael_wrapper_link_section', …)` and adds URL + nofollow + target controls.
5. **User sets the URL.** Elementor saves the setting into the element's settings dict in `_elementor_data`.
6. **Visitor loads page.** Elementor renders the element. Just before output, fires `elementor/frontend/before_render`. `Wrapper_Link::before_render($element)` runs, reads the saved URL setting via `$element->get_settings()`, and (if non-empty) calls `$element->add_render_attribute('_wrapper', 'class', 'eael-wrapper-link')` plus `data-eael-wrapper-link="<url>"`.
7. **Element output** carries the new class and data attribute. The corresponding frontend JS (or pure CSS using `[data-eael-wrapper-link]` selector) makes the whole element clickable.

Total surface area: 135 lines of PHP, no `Widget_Base` complexity, no separate render path. Adding similar "I want this control on every section" behaviour follows the same pattern.

## Architecture Decisions

### Plain PHP class, no `Widget_Base` inheritance

- **Context:** Extensions don't render standalone — they hook into Elementor's existing element-controls cycle. Inheriting `Widget_Base` would force them to define `get_name()`, `get_title()`, `register_controls()`, `render()` — all of which are meaningless for a behaviour modifier.
- **Decision:** Keep extensions as plain PHP classes that wire hooks in the constructor.
- **Alternatives rejected:** Inheriting `Widget_Base` (forces unused methods and breaks Elementor's widget-list); making extensions Pro-only and using widgets for everything (loses the per-element behaviour pattern).
- **Consequences:** Two parallel registration paths in the codebase (widgets via `Widgets_Manager::register`; extensions via plain instantiation). Documented above to clarify when to use which.

### Force-enable `'promotion'`

- **Context:** The Promotion extension's job is to advertise Pro features inside Lite. If users could disable it, the upsell would disappear — defeating its purpose. The extension is harmless when Pro is active because of the constructor's short-circuit.
- **Decision:** Force-push `'promotion'` into the active list inside `Elements::register_extensions:104`, regardless of `eael_save_settings`.
- **Alternatives rejected:** Hide the toggle in UI (still possible to disable via DB / wp-cli); make it a separate non-extension class (would lose the registration uniformity).
- **Consequences:** Users who want to fully suppress Promotion must filter it out of `eael/registered_extensions`. Documented in Common Pitfalls.

### Same registry schema as widgets

- **Context:** Asset_Builder needs to enqueue extension assets just like widget assets. Different schemas would force two parallel resolver paths.
- **Decision:** Use the identical `dependency` block shape (`type` + `context` + `file`) for both. The only difference is the top-level key (`elements` vs `extensions`).
- **Alternatives rejected:** Custom extension-specific schema (more code in Asset_Builder); skip dependency resolution for extensions and require them to manually enqueue (loses the conditional-loading benefit).
- **Consequences:** Symmetry across the codebase. Asset_Builder's `Elements_Manager::generate_dependency` handles both transparently.

### Activation via `eael_save_settings` (shared with widgets)

- **Context:** Two places where activation can be configured: Setup Wizard + EA Settings page. Both already write to `eael_save_settings` for widgets. Splitting extensions into a separate option would require both UIs to handle two storage keys.
- **Decision:** Treat extensions as just-another-key in `eael_save_settings`.
- **Alternatives rejected:** Separate `eael_extensions_settings` option (UI duplication); always-on extensions with no toggle (removes user control).
- **Consequences:** Default values populate via `Core::set_default_values` walking the merged set. UIs work uniformly. The `'promotion'` force-push is the one exception, documented above.

### `'context' => 'edit'` for most extension JS

- **Context:** Many extensions only need JS during the editor experience (live preview of changes, control-driven UI updates). Loading that JS on frontend would be wasted bytes.
- **Decision:** Default extension JS to `'edit'` context unless it genuinely runs at frontend.
- **Alternatives rejected:** Always load both contexts (waste); always load `'view'` (extensions whose only JS runs in the editor would still pay the frontend cost).
- **Consequences:** Reading_Progress's progress-bar JS loads only in editor, not frontend — but the progress bar itself is rendered by frontend CSS reading the document settings. Some extensions that need frontend JS (Hover_Effect, Wrapper_Link) achieve it via `elementor/frontend/before_render` adding render attributes, then frontend JS that's part of the page's general bundle reads those attributes.

## Known Limitations

- **No "extension lifecycle" hooks.** Extensions don't have an opt-out path equivalent to a widget's deactivation; once activated, the only way to disable is to flip the setting and reload. There is no `extension_deactivated` hook for cleanup.
- **`'promotion'` cannot be disabled via UI.** Documented; suppression requires the `eael/registered_extensions` filter.
- **Constructor execution order is registry order.** No explicit dependency declaration between extensions. If extension A relies on extension B being constructed first, the only guarantee is config.php ordering.
- **No per-extension capability checks.** Extensions activate for all admin users regardless of capability. Some extensions (Custom_JS) probably should require `unfiltered_html` or similar — not enforced today.
- **Elementor hook coverage is incomplete.** EA extensions hook the most common Elementor element types but not every type. Custom Elementor element types added by other plugins won't pick up EA extension behaviour automatically.
- **No audit trail.** When `eael_save_settings` toggles an extension off, no log records who or when. For team installs this is a minor gap.
- **`'edit'` context conflation.** Asset_Builder treats `'edit'` as "Elementor editor iframe", but some extensions might want "WP admin only" semantics that's neither editor-iframe nor frontend. Today, those edge cases are handled with manual `is_admin()` checks inside the constructor.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — system map with the four render phases.
- **Architecture:** [`./asset-loading.md`](asset-loading.md) — Asset_Builder reads extension `dependency` blocks identically to widget blocks.
- **Architecture:** [`./editor-data-flow.md`](editor-data-flow.md) — extensions add controls to existing elements; this doc describes how those controls flow from editor to render.
- **Architecture:** [`./quick-setup.md`](quick-setup.md) — Setup Wizard writes to `eael_save_settings`, which controls extension activation.
- **Per-extension docs:** [`../extensions/`](../extensions/) — folder for individual extension docs (lazy-fill).
- **Per-extension reference:** [`../extensions/promotion.md`](../extensions/promotion.md) — fully-fleshed example of a single extension, the most-asked-about (Pro upsell injection).
- **Skills:** [`.claude/skills/new-widget`](../../.claude/skills/new-widget/SKILL.md) — for adding a new widget. There is no `new-extension` skill; extensions are rare enough that the doc's "Adding a new extension — checklist" above is sufficient.
- **Rules:** [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md) — namespacing and security conventions every extension class must follow.
- **Issue:** [#805](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/805) — the request that drove this doc.
