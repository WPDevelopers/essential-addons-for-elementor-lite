# Mega Menu Widget

> A nested Elementor widget where every menu item behaves like a tab and owns its own container, so any widget — headings, images, forms, WooCommerce widgets, dynamic tags — can be dropped inside a submenu panel.

**Class file:** [`includes/Elements/Mega_Menu.php`](../../includes/Elements/Mega_Menu.php)
**Slug:** `mega-menu` (widget id `eael-mega-menu`)
**Requires:** Elementor 3.8+ (`Widget_Nested_Base`) with the *Nested Elements* experiment active

---

## Overview

Mega Menu is EA's first **nested** widget. It follows the same architecture as Elementor's Nested Tabs: the `eael_mega_menu_items` repeater and the widget's child elements are kept in a strict 1:1 index mapping, so adding a repeater row creates a container and removing one deletes it. Elementor owns that lifecycle — the widget only decides *where* those containers are printed and *what* attributes they carry. A menu item is a tab, a tab is a container, and a container holds arbitrary widgets.

Because children are ordinary Elementor container elements they live inside the existing `_elementor_data` post meta: no new tables, no extra post meta, no extra queries.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| One-click header presets (Content → Mega Menu Preset) | ✅ | — |
| Nested menu items with per-item containers | ✅ | — |
| Hover / click trigger, close delay, outside-click close | ✅ | — |
| Four submenu width modes (menu, viewport, fit, custom) | ✅ | — |
| Five reveal animations | ✅ | — |
| Responsive collapse to toggle + accordion | ✅ | — |
| Full style tab (bar, item states, icon, indicator, panel, toggle) | ✅ | — |

Lite-only widget — Pro adds nothing and hooks nothing. The public extension points are the `eael/mega-menu/menu_items` filter and the two preset filters below.

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Mega_Menu.php`](../../includes/Elements/Mega_Menu.php) | Widget class — metadata, nested wiring, `print_child()` |
| [`includes/MegaMenu/Conditions.php`](../../includes/MegaMenu/Conditions.php) | Availability gate — Elementor version, nested API, experiment state |
| [`includes/MegaMenu/Manager.php`](../../includes/MegaMenu/Manager.php) | Service provider — option lists, defaults, child container shape |
| [`includes/MegaMenu/Controls/Content_Controls.php`](../../includes/MegaMenu/Controls/Content_Controls.php) | Content tab — preset picker, repeater, settings, responsive |
| [`includes/MegaMenu/Presets/Preset_Library.php`](../../includes/MegaMenu/Presets/Preset_Library.php) | Preset registry — control options, availability gate, `get_content( $slug, $mode )` |
| [`includes/MegaMenu/Presets/Saas_Menu.php`](../../includes/MegaMenu/Presets/Saas_Menu.php) | The **SaaS Menu** preset — header bar, widget settings, one nested container per row |
| [`includes/MegaMenu/Controls/Style_Controls.php`](../../includes/MegaMenu/Controls/Style_Controls.php) | Style tab — six sections, all writing CSS custom properties |
| [`includes/MegaMenu/Renderers/Frontend_Renderer.php`](../../includes/MegaMenu/Renderers/Frontend_Renderer.php) | PHP render + panel attribute decoration |
| [`includes/MegaMenu/Renderers/Editor_Renderer.php`](../../includes/MegaMenu/Renderers/Editor_Renderer.php) | Underscore `content_template()` |
| [`includes/MegaMenu/Traits/Menu_Items.php`](../../includes/MegaMenu/Traits/Menu_Items.php) | Repeater row normalisation shared by widget and renderer |
| [`includes/MegaMenu/Templates/menu-item.php`](../../includes/MegaMenu/Templates/menu-item.php) | Single menu item markup partial |
| [`includes/MegaMenu/Templates/mobile-toggle.php`](../../includes/MegaMenu/Templates/mobile-toggle.php) | Mobile toggle button partial |
| [`src/css/view/mega-menu.scss`](../../src/css/view/mega-menu.scss) | Source styles |
| [`src/js/view/mega-menu.js`](../../src/js/view/mega-menu.js) | Frontend + editor preview handler |
| [`src/js/edit/mega-menu.js`](../../src/js/edit/mega-menu.js) | **Editor window** — registers the nested element type, plus a guarded view subclass |
| [`config.php`](../../config.php) entry `'mega-menu'` | Asset_Builder dependencies + availability condition |
| `assets/front-end/css/view/mega-menu.min.css` / `js/view/mega-menu.min.js` | Built output (do not edit) |

## Architecture

- **`Widget_Nested_Base` + `Control_Nested_Repeater`** — Elementor owns the repeater ↔ child container lifecycle, which is what makes copy, paste, duplicate, delete, drag-and-drop and the Navigator work with no custom code. `get_default_children_placeholder_selector()` returns `.eael-mega-menu__panels`, which is where the editor mounts the container views.
- **The editor element type also patches an Elementor core defect** — `NestedElementBase`'s view binds `events.click` that dereferences `event.target.closest('.elementor').dataset` with no null check. `closest()` returns null whenever the clicked node was already detached, which is exactly what clicking "+" in an empty nested container and choosing a layout does: creating the container tears down the empty view that owned the clicked node. [`src/js/edit/mega-menu.js`](../../src/js/edit/mega-menu.js) overrides `getView()` with a subclass that skips the handler for a detached target. Reproducible on Elementor's own Nested Tabs, so the upstream bug is theirs; this only shields our widget.
- **A nested widget must register an editor element type** — returning `support_nesting` from PHP only *describes* the widget. Elementor treats it as nestable only once `elementor.elementsManager.registerElementType()` has been called for it in the **editor window**, which is what [`src/js/edit/mega-menu.js`](../../src/js/edit/mega-menu.js) does (subclassing `NestedElementBase` and implementing just `getType()`). Without it Elementor falls back to the plain widget model, `NestedModelBase.initialize()` never runs, and the default child containers are never created — the widget renders but has nowhere to drop widgets. `Manager::init()` enqueues that script on `elementor/editor/before_enqueue_scripts` at priority 20 with a `nested-elements` dependency.
- **Availability is gated in `config.php`, not in the class** — the `condition` entry (`class_exists` on `Widget_Nested_Base`, skip when false) means that on Elementor < 3.8 the widget is never instantiated and `Mega_Menu.php` is never autoloaded, so extending a missing base class cannot fatal. `show_in_panel()` additionally hides the widget when the *Nested Elements* experiment is off.
- **No separate Assets or Documents layer** — apart from the editor script above, asset loading goes through the existing `config.php` registry + `Asset_Builder`, which already gives per-page conditional loading plus popup / shortcode / Theme-Builder coverage; a parallel asset layer would double-load the files. Submenu content is stored as child elements in `_elementor_data`, so a custom document type would create exactly the duplicate storage that should be avoided.
- **CSS custom properties with fallbacks at the point of use** — every style control writes a `--eael-mm-*` variable onto `{{WRAPPER}}`. Defaults live in the `var()` fallback (`var(--eael-mm-item-gap, 8px)`), never as a declaration on `.eael-mega-menu`, because a declaration on the descendant would beat the value inherited from the Elementor wrapper.
- **A panel's `display` is never overridden** — the panel *is* an Elementor container and owns its own display (flex, or grid for a grid container). Hiding is therefore expressed as `…__panel:not(.…__panel--active) { display: none }` rather than `display: none` on all panels plus `display: block` on the active one. Forcing `block` silently disabled the container's own Direction control: a two-column layout stacked as two rows, because `flex-direction` has no effect on a block box.
- **One panels wrapper, `order`-based interleaving on mobile** — the editor mounts every child into a single placeholder, so the frontend uses the same single wrapper. Below the breakpoint, `.eael-mega-menu__list` and `.eael-mega-menu__panels` become `display: contents` and each item/panel carries `--eael-mm-order` (`2n` / `2n+1`) so they interleave into an accordion. Same technique Elementor's nested tabs uses.
- **A handler class, not the usual jQuery callback** — most EA widgets register `function ($scope, $)`. Mega Menu extends `elementorModules.frontend.handlers.Base` because a nested widget must react to `onEditSettingsChange('activeItemIndex')` so selecting a repeater row switches the previewed panel. Registered through `elementorFrontend.elementsHandler.attachHandler()` with a factory function (Elementor accepts class or factory).
- **The widget stretches itself, via `width`, not `flex-grow`** — a widget dropped into a row-direction container is a flex item and sizes to its content, which leaves the Align control nothing to distribute. `eael_mega_menu_stretch` (default on) sets `width: 100%` on the wrapper. `flex-grow` would have been wrong: in Elementor's default *column* containers the main axis is vertical, so it would stretch the menu bar's height instead of its width.
- **`section` items relocate an existing element, they do not copy it** — the referenced section is a normal Elementor element rendered elsewhere on the page, so it cannot be pulled in server side. PHP prints an empty panel carrying `data-section-id`, and the handler moves that node into it with `appendChild`. A move, not a clone, so the CSS ID stays unique. Two guards apply: the source is skipped if it contains the menu (relocating an ancestor would detach the widget from the document) and if the panel already has children. Never runs in the editor — relocating a container there would fight Elementor's views, which re-render elements into their original parents.
- **Saved-template panels are rendered by PHP, not by the nested container** — for `template` items the widget prints its own panel wrapper and fills it with `Plugin::$instance->frontend->get_builder_content()`, reusing the exact guards Advanced Tabs uses (refuse the current page or its revisions, require a published `elementor_library` post, honour `wpml_object_id`). The item's nested container still exists — Elementor keeps one per repeater row — but is skipped.
- **Theme button resets are neutralised explicitly** — an unlinked item and every disclosure button render as `<button type="button">`, and themes routinely ship `[type=button]:hover, button:focus { background: …; color: … }`. The attribute form is **(0,2,0)**, a straight tie with `.eael-mega-menu__link:hover`, so load order alone decided the winner and the widget's own colour controls could be silently overridden. The defence selectors are prefixed with the block class to reach **(0,3,0)**, and are declared before the hover/active blocks so those still take precedence by source order. The `<li>` owns the visible background; the button stays transparent in every state.
- **State colours never chain into each other** — `--eael-mm-*-active` falls back to the *normal* value, never to the hover value. Chaining them meant setting a hover colour silently repainted the active state, which reads as "I can't change this colour".
- **The collapsed dropdown is measured against the viewport, not the widget** — in a "logo left, hamburger right" header the widget shrinks to the toggle, and an overlay dropdown anchored to that box would render as a narrow strip. `positionDropdown()` writes `--eael-mm-dropdown-inset-start` (minus the nav's distance from the viewport edge, RTL aware) and `--eael-mm-dropdown-width`, giving a full-bleed sheet at any widget width. It falls back to the widget box if the handler has not run, and is reset in editing mode where the dropdown is in flow.
- **The collapsed layout ships styled, the bar does not** — the mobile dropdown defaults to a white surface with a soft shadow and hairline dividers between items, so it reads as a proper mobile menu with zero configuration, while the desktop bar stays completely unstyled. This is done with `var()` fallbacks per layout rather than control defaults: the Divider controls default to *empty*, which writes no custom property and lets the stylesheet decide (`0` on the bar, `1px solid rgba(0,0,0,.12)` when collapsed). Setting any Divider or Mobile Dropdown control writes the property at higher specificity and wins.
- **The collapsed layout is force-expanded while editing** — at a mobile breakpoint the menu starts closed and, with Overlay Dropdown on, floats over whatever follows it. Both make the mobile view impossible to design, so `--editing` + `--mobile` together pin the dropdown to `display: flex; position: static`. Front-end behaviour is untouched.
- **JS-driven breakpoint, not a media query** — the collapse breakpoint is compared against `elementorFrontend.getCurrentDeviceMode()` and toggles `.eael-mega-menu--mobile`. Static media queries could not honour custom breakpoint values set in Site Settings.

## Render Output

```html
<!-- data-trigger / data-breakpoint / data-touch-mode are read by JS -->
<nav class="eael-mega-menu eael-mega-menu--trigger-hover eael-mega-menu--anim-fade eael-mega-menu--stretch-dropdown"
     data-widget-number="195021895" data-trigger="hover" data-breakpoint="tablet"
     data-touch-mode="false" aria-label="Mega Menu">

  <!-- conditional: omitted when breakpoint = none. Visible only in .eael-mega-menu--mobile -->
  <button class="eael-mega-menu__toggle" type="button" aria-expanded="false"
          aria-controls="eael-mega-menu-container-195021895">…</button>

  <div class="eael-mega-menu__container" id="eael-mega-menu-container-195021895">
    <ul class="eael-mega-menu__list">

      <!-- link + submenu: the link stays navigable, a disclosure button owns the panel -->
      <li class="eael-mega-menu__item eael-mega-menu__item--has-submenu" data-item-index="1" style="--eael-mm-order: 2;">
        <a class="eael-mega-menu__link" id="eael-mega-menu-item-195021895-1" href="/shop">
          <span class="eael-mega-menu__item-icon">…</span>
          <span class="eael-mega-menu__item-label">Shop</span>
        </a>
        <button class="eael-mega-menu__disclosure" aria-expanded="false"
                aria-controls="eael-mega-menu-panel-195021895-1" aria-label="Show submenu for Shop">…</button>
      </li>

      <!-- no link + submenu: the item itself is the disclosure control -->
      <li class="eael-mega-menu__item eael-mega-menu__item--has-submenu" data-item-index="2" style="--eael-mm-order: 4;">
        <button class="eael-mega-menu__link" id="…-2" aria-expanded="false" aria-controls="…-panel-…-2">…</button>
      </li>

      <!-- link, no submenu: a plain anchor, no panel is printed -->
      <li class="eael-mega-menu__item" data-item-index="3" style="--eael-mm-order: 6;">
        <a class="eael-mega-menu__link" id="…-3" href="/contact" target="_blank" rel="nofollow">…</a>
      </li>
    </ul>

    <!-- editor mount point; PHP prints the same containers here -->
    <div class="eael-mega-menu__panels">
      <div id="eael-mega-menu-panel-195021895-1" class="eael-mega-menu__panel e-con …"
           aria-labelledby="eael-mega-menu-item-195021895-1" data-item-index="1"
           data-width-mode="full" style="--eael-mm-order: 3;"> … nested widgets … </div>
    </div>
  </div>
</nav>
```

Styling hooks: `.eael-mega-menu__item--active` (item whose panel is open), `.eael-mega-menu__panel--active`, `.eael-mega-menu--mobile`, `.eael-mega-menu--menu-open`, `.eael-mega-menu--active`.
JS-written properties: `--eael-mm-panel-inset-start` and `--eael-mm-panel-width`, set on open and on resize for every width mode except `full`.

## Controls Reference

| Control | Type | Default | Tab → Section | Affects |
| ------- | ---- | ------- | ------------- | ------- |
| `eael_mega_menu_preset` | Choose (image) | `custom` | Content → Mega Menu Preset | Records the applied preset; the editor script replaces the header block. `render_type: none` |
| `eael_mega_menu_items` | Nested repeater | 4 rows | Content → Menu Items | Items + child containers; `frontend_available` |
| `…_item_label` | Text (dynamic) | `Menu Item` | ↳ row | `.eael-mega-menu__item-label` |
| `…_item_link` | URL (dynamic) | — | ↳ row | `<a>` vs `<button>` element choice |
| `…_item_icon` | Icons | — | ↳ row | `.eael-mega-menu__item-icon` |
| `…_item_type` | Select | `mega` | ↳ row | `link` / `mega` (nested container) / `template` (saved template) / `section` (CSS ID) |
| `…_item_section_id` | Text | — | ↳ row | CSS ID of a section on the page, moved into the panel by JS |
| `…_item_template` | eael-select2 | — | ↳ row | Saved `elementor_library` post rendered into the panel |
| `…_item_panel_align` | Choose | `start` | ↳ row | Panel anchored start/center/end of its item (`item` & `custom` widths) |
| `…_item_panel_offset_x` / `_y` | Slider px | `0` | ↳ row | Per-item nudge, applied in every width mode |
| `…_item_submenu_width` | Select | `full` | ↳ row | `data-width-mode`; `full` / `viewport` / `item` / `custom` |
| `eael_mega_menu_stretch` | Switcher (resp.) | `yes` | Content → Settings | `width: 100%` on the widget wrapper |
| `…_item_submenu_custom_width` | Slider | `480px` | ↳ row | `--eael-mm-panel-width` (via JS) |
| `…_item_css_id` / `…_item_css_classes` | Text | — | ↳ row | `<li>` id / classes (`sanitize_html_class`) |
| `eael_mega_menu_trigger` | Select | `hover` | Content → Settings | `data-trigger` |
| `eael_mega_menu_hover_delay` | Slider ms | `150` | Content → Settings | Close timer |
| `eael_mega_menu_close_on_outside_click` | Switcher | `yes` | Content → Settings | Document click handler |
| `eael_mega_menu_animation` | Select | `fade` | Content → Settings | `--anim-*` root class |
| `eael_mega_menu_animation_duration` | Slider ms | `300` | Content → Settings | `--eael-mm-animation-duration` |
| `eael_mega_menu_align` | Choose (resp.) | `flex-start` | Content → Settings | `--eael-mm-bar-justify`; `stretch` also sets `--eael-mm-item-grow: 1` |
| `eael_mega_menu_indicator_icon` | Icons | `fa-chevron-down` | Content → Settings | `.eael-mega-menu__item-indicator` |
| `eael_mega_menu_breakpoint` | Select | `tablet` | Content → Responsive | `data-breakpoint`; `none` disables collapsing |
| `eael_mega_menu_toggle_text` / `_icon` / `_close_icon` | Text / Icons | `Menu`, bars, times | Content → Responsive | Toggle button contents |
| `eael_mega_menu_toggle_align` | Choose (resp.) | `flex-end` | Style → Mobile Toggle | `--eael-mm-toggle-align`; needs Stretch on to have room to move |
| `eael_mega_menu_toggle_full_width` | Switcher | `yes` | Content → Responsive | `--stretch-dropdown` (overlay vs push) |
| `eael_mega_menu_divider_*` | Select / Slider (resp.) / Color | *empty* | Style → Menu Items | `--eael-mm-divider-*`; empty keeps the per-layout default (none on the bar, hairline collapsed) |
| Style sections | — | — | Style | `Menu Bar`, `Menu Items` (+ Divider), `Icon`, `Submenu Indicator`, `Submenu Panel`, `Mobile Dropdown`, `Mobile Toggle` — all emit `--eael-mm-*` |

## Conditional Dependencies

```text
eael_mega_menu_item_type = template
  └── eael_mega_menu_item_template

eael_mega_menu_item_type = section
  └── eael_mega_menu_item_section_id

eael_mega_menu_item_type ∈ { mega, template, section }
  ├── eael_mega_menu_item_submenu_width
  │     ├── = custom        → eael_mega_menu_item_submenu_custom_width
  │     └── ∈ {item,custom} → eael_mega_menu_item_panel_align
  ├── eael_mega_menu_item_panel_offset_x
  └── eael_mega_menu_item_panel_offset_y

eael_mega_menu_trigger = hover
  └── eael_mega_menu_hover_delay

eael_mega_menu_animation != none
  └── eael_mega_menu_animation_duration

eael_mega_menu_breakpoint != none
  ├── eael_mega_menu_toggle_text / _icon / _close_icon / _full_width
  └── Style → Mobile Toggle (whole section)

eael_mega_menu_indicator_icon[value] != ''
  └── Style → Submenu Indicator (whole section)
```

## JavaScript Lifecycle

Registered on `elementor/frontend/init`, guarded by `eael.elementStatusCheck('eaelMegaMenu')` so repeated init events (popups, SPA navigation) don't double-register:

```js
elementorFrontend.elementsHandler.attachHandler("eael-mega-menu", getMegaMenuHandler);
```

`getMegaMenuHandler` is a factory returning the class so `elementorModules` is dereferenced only once Elementor is ready.

- **`onInit()`** — normalise panels (the editor mounts bare containers, so class / id / `data-item-index` / `data-width-mode` / `--eael-mm-order` are applied there), compute device mode, detect touch, and in the editor open `activeItemIndex` (defaults to 1 for a freshly dropped widget).
- **Reads** — `getElementSettings()` for trigger, delay, breakpoint, outside-click, and the repeater rows (for per-item width). DOM `data-*` attributes for everything else.
- **Branches** — hover trigger binds `mouseenter`/`mouseleave` on items *and* panels with a shared close timer; click / touch / mobile route through `togglePanel()`. On a linked item the first tap opens and the second follows the link.
- **Editor hooks** — `onEditSettingsChange('activeItemIndex')` switches the previewed panel; `onElementChange('eael_mega_menu_items')` rebuilds the cached collections after add / remove / reorder; `onElementChange('eael_mega_menu_breakpoint*')` recomputes the collapsed layout.
- **Runtime state** — `activeIndex` (only one panel open at a time) and `closeTimer`. `resize` / `orientationchange` recompute device mode and reposition the open panel.
- **Scoping** — element lookups use `.children()` chains rather than `.find()`, and every delegated handler checks `isOwnEvent()`, so a Mega Menu nested inside another one's panel cannot steal its parent's events.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/mega-menu/menu_items` | filter | `( array $items, Widget_Base $widget )` | Modify, extend or reorder repeater rows immediately before render |
| `eael/mega-menu/presets` | filter | `( array $presets )` | Add or remove presets. Each needs `title`, `thumbnail`, a `builder` taking a mode, and optionally `widgets` |
| `eael/mega-menu/preset_content` | filter | `( array $content, string $slug, string $mode )` | Adjust the element a preset applies, before the editor inserts it |

Assets are declared in `config.php` only (`type: self`, `context: view` for both CSS and JS), so `Asset_Builder` folds them into the per-page `eael-{post_id}.css` / `.js` bundle only when the widget is on the page. Nothing is enqueued globally.

## Presets

A preset is a ready-made **header**, applied from the first Content section, **Mega Menu Preset**. It is not a skin, and it is not only the menu: the widget's own design already lives in three places at once — the repeater rows that make the bar, the widget settings that style it, and the nested containers that fill each panel — and around it sits the third of a header nobody navigates without, the logo and whatever the site asks visitors to do. So a preset supplies all of it, and the editor **replaces the block the menu sits in** with the result.

Replacing rather than patching is the point. Elementor keeps `eael_mega_menu_items` and the widget's children in a strict 1:1 index mapping and syncs them through the repeater commands; writing a new row set into the settings would leave the old panels behind it, one per row that no longer exists. A widget built from the preset arrives with its rows and its children already in agreement. It is the same route [`Theme_Builder/Presets/Mega_Header.php`](../../includes/Theme_Builder/Presets/Mega_Header.php) takes to insert a Mega Menu in the first place, and it reuses the same element builders in [`Theme_Builder/Presets/Elements.php`](../../includes/Theme_Builder/Presets/Elements.php).

### What gets replaced

A header on an Elementor page is a top-level block — the container the document holds directly — so `presetTarget()` climbs from the widget to the last container before the document. That lands on the right thing in both situations that matter:

| Situation | Target | Mode |
| --------- | ------ | ---- |
| Widget just dropped on the canvas | the container Elementor created for it, holding nothing else | `header` |
| Menu already inside a preset header | the header bar itself, two levels up | `header` |
| Menu in a legacy column, or with no container above it | the widget | `widget` |

Taking the *immediate* parent instead would have swapped the header's navigation column for a whole second header nested inside the first — that was a real bug, and the walk is what fixes it. In `widget` mode the preset returns the menu alone and the Advanced tab is carried across; in `header` mode it is not, because the widget is moving into a bar it has never been in, where a width set for a standalone menu is a leftover rather than positioning.

The confirm dialog fires when the panels already hold content, or when the block being replaced holds more than the menu. A block holding only the menu is the container the widget arrived in, and turning that into a header is the whole point rather than something to warn about.

### Every tile is a switch

Including **Custom**, which is not a preset — there is no design behind it — but is a real choice all the same: picking it puts the widget back to the plain menu it ships as, wrapped in a bare container, which is the blank page someone asks for when they want to start over. A tile that quietly did nothing read as broken.

That is also why the apply is driven by **clicks on the tiles** rather than by watching `change:eael_mega_menu_preset`. Backbone only fires `change` when the value actually moves, so a model-bound handler ignored the two presses users make most: the tile that is already lit, to start the design over, and Custom.

### Flow

```text
user clicks a tile                                             (src/js/edit/mega-menu.js)
  └── resolve the edited widget from the panel, read the tile's slug
        ├── panels or block hold content → confirm (deferred a tick), Cancel restores the slug
        └── POST eael_mega_menu_preset { preset, mode }         (MegaMenu\Manager::ajax_preset)
              └── Preset_Library::get_content( $slug, $mode )   → one Elementor element
                    └── wait out Elementor's 800ms settings-history debounce
                          └── one history entry, "Apply Preset":
                                document/elements/delete  (the old block)
                                document/elements/create  (the preset's, at the same index)
```

Four details are load-bearing, and each of them cost real debugging:

- **The edited widget is asked of the panel, not remembered.** `panel/open_editor/widget/<type>` hands over a view, but only reliably when a *person* opened the panel — after the reopen an apply performs itself, it arrives empty. Caching it stranded every click after the first. The handler reads `getCurrentPageView().getOption( 'editedElementView' )` instead, and keeps the recorded one only as a fallback.
- **The confirm dialog opens on the next tick.** DialogsManager closes a dialog on a click outside it, and the press that asked for this one is still bubbling towards the document. Shown synchronously it is dismissed by the very gesture that opened it — which, again, reads as the tile doing nothing.
- **The debounce wait.** Elementor records a settings change on a debounced timer so a run of keystrokes collapses into one undo step. A swap that beat the timer put the tile's own entry on *top* of the delete and the create, aimed at a widget those two had already replaced: undo changed nothing visible, and redo built a second header beside the first. Waiting orders the stack the way the user performed it — `Apply Preset`, then `Mega Menu / Preset`, then `Editing Started`.
- **`end-log` needs the id `start-log` returned.** Without it the call closes whichever log the history happens to have open, and a log left open swallows every later entry — so a second apply and everything between the two collapse into a single undo step that walks the user back past work they meant to keep.

A cancelled switch leaves the canvas untouched and puts the tile back, but Elementor has already logged the radio moving, so it costs one cosmetic undo entry that only moves the value. Applying is what produces the single `Apply Preset` step.

`render_type: none` on the control is deliberate — the widget is about to be rebuilt by the script, and re-rendering it for the control's own sake would be a second teardown of every nested container.

### Shipped presets

| Slug | Title | Built from |
| ---- | ----- | ---------- |
| `saas` | SaaS Menu | Header: the site's own logo (core `image`, or `heading` with the site name when none is set), core `button` for Login, `eael-creative-button` for Create Account. Panels: `eael-adv-tabs` (vertical, the Product catalogue), `eael-info-box` + core `icon` in linked containers (the Resources list) |

`Preset_Library::get_content()` also answers for `custom`, building the widget's own defaults through `Manager::get_default_menu_items()` / `get_default_children_elements()`, so switching back lands exactly where someone who never touched the control would have started.

A preset that names its `widgets` is hidden whenever one is missing — switched off in EA's settings, disabled in Elementor's element manager — rather than applying half way and leaving the wreckage behind. When every preset is hidden the section is not registered at all.

### Adding one

1. Add a builder class in `includes/MegaMenu/Presets/` with a static `build( $mode )` returning **one** Elementor element: the finished header bar with a Mega Menu widget somewhere inside it for `header`, that widget alone for `widget`.
2. The widget's `elements` are one `Elements::nested_child()` per repeater row, **including the plain-link rows** — the widget prints child *n* for row *n*, so a skipped container shifts every later panel onto the wrong item.
3. Register it in `Preset_Library::get_presets()` (or through the `eael/mega-menu/presets` filter) with a `thumbnail` and the `widgets` it emits in either mode. `Preset_Library::get_content()` stamps the slug onto every Mega Menu in the tree, so the builder never has to know its own key.
4. Drop a ~129×123 wireframe PNG into `assets/admin/images/layout-previews/`, in the same `#5F6367`-on-transparent language as the existing files.

Nothing in a preset may be Pro, and nothing may need markup the widget alone knows how to render. The one exception is the link list inside an Advanced Tabs tab — a tab takes a WYSIWYG field rather than child widgets, so `Saas_Menu::link_list()` writes a small block of HTML whose classes the widget's own stylesheet answers (`.eael-mm-links` in [`mega-menu.scss`](../../src/css/view/mega-menu.scss)). Strip the classes and the links still work.

## Common Issues

### The widget is missing from the Elementor panel

The *Nested Elements* experiment is off, or Elementor is older than 3.8. Check **Elementor → Settings → Features**; `Conditions::is_nested_elements_active()` drives `show_in_panel()`. On Elementor < 3.8 the `condition` in `config.php` skips registration entirely, so the widget also won't appear in **EA → Elements**.

### Something in the header is underlined

Themes underline links, and everything in this header is a link. Three different layers answer for it, because there is no one place that can:

| Element | Reset by |
| ------- | -------- |
| Menu items, disclosure buttons, label / icon / indicator spans | [`mega-menu.scss`](../../src/css/view/mega-menu.scss), block-prefixed to (0,2,0) / (0,3,0) |
| The link list inside a panel (`.eael-mm-links`) | same file, including `:hover` / `:focus` / `:active` |
| **Login** (core Button) | a control — the preset sets `typography_text_decoration: none`, which writes Elementor's own per-widget rule |
| **Create Account** (Creative Button) | [`creative-btn.scss`](../../src/css/view/creative-btn.scss) |

The Creative Button is the one worth understanding. Its Typography group targets `.eael-creative-button .cretive-button-text` — the inner span — while the theme underlines the `<a class="eael-creative-button">` around it. **A decoration set on an ancestor draws through its inline descendants and cannot be switched off by them**, so no value in that control could ever have fixed it; the reset has to live on the `<a>`, which is why it is in the widget's own stylesheet rather than in the preset.

That same rule is worth remembering for anything a preset puts inside a panel. It does *not* apply to the menu links themselves — `.eael-mega-menu__link` is `display: flex`, which starts its own formatting context, so decorations do not propagate into it.

A theme that still wins after all this is either using `!important` or reaching past three classes; that belongs in the theme or in Custom CSS rather than another round of specificity here. One thing deliberately left alone: the logo's `<a>` picks up the underline, but it wraps an `<img>` with no text in it, so nothing is drawn.

### The active or focused item shows a colour I never set (often pink)

Almost always a theme reset painting the underlying `<button>`, e.g. `button:focus { background-color: #c36; color: #fff; }` in the theme's `reset.css`. Menu items without a URL, and every disclosure button, are `<button type="button">` elements, and a focused button keeps that style after being clicked. The widget now overrides these at (0,3,0) specificity, so the Normal / Hover / Active controls win regardless of stylesheet order. If a theme still breaks through, it is using `!important` or an ID — inspect the winning rule in DevTools before assuming it is the widget.

### A Section CSS ID item shows nothing

Check, in order: the ID matches exactly (enter it without the `#`, and IDs are case sensitive); the section is on the *same page* as the menu — a section in a different template is not in the DOM to move; and the section is not an ancestor of the menu itself, which the handler refuses to move. A missing or invalid ID leaves the panel empty and logs nothing, by design. The section is also only relocated on the front end, so an empty panel in the editor is expected.

### The menu bar does not fill its container, and Align appears to do nothing

Both symptoms are the same cause: the widget sits in a **row-direction** container, so it is a flex item that shrinks to its content and `justify-content` has no free space to distribute. Turn on **Content → Settings → Stretch To Full Width** (on by default), or set the container to column direction, or set Advanced → Width to 100%.

### "Stretch" alignment does not make the items fill the bar

Confirm the Align control is set to `stretch` and not the legacy `space-between` value. `stretch` sets `--eael-mm-item-grow: 1` so each item grows; `justify-content` on its own can only reposition items, never resize them.

### The mobile menu looks broken or uneditable in the editor

Expected before this was fixed: the collapsed menu starts closed, so there was nothing to select, and an overlaying dropdown drew across the following section. While editing, the collapsed dropdown is now always expanded and in flow. If it still overlaps, check for custom CSS forcing `position: absolute` on `.eael-mega-menu__container`.

### The collapsed dropdown is a narrow strip

Fixed — the dropdown is positioned against the viewport rather than the widget box, so it stays full-bleed even when Stretch is off and the widget has shrunk to its toggle. If it reappears, the handler has not run; check for a JS error, since the CSS fallback is the widget box.

### The hamburger will not align left / centre / right

Alignment is **Style → Mobile Toggle → Alignment** (only shown when Breakpoint is not `None`), and it works by `align-self`, so it needs free space to move within. If the widget has shrunk to its content — the usual cause being a row-direction parent container — there is no space and every value looks identical. Turn on **Content → Settings → Stretch To Full Width**.

### A multi-column layout inside a panel stacks vertically

Check the panel's computed `display` in DevTools. It must be `flex` (or `grid`); if it computes to `block`, something is overriding the container's own display and `flex-direction` is being ignored — a block box has no flex axis, so the Direction control appears to do nothing. The widget no longer sets `display` on an open panel for exactly this reason. A theme or custom CSS targeting `.e-con` or `.eael-mega-menu__panel` can reintroduce it.

### Console TypeError when adding a layout inside a menu item

`Cannot read properties of null (reading 'dataset')` from `nested-elements` `events.click`. This is an Elementor core bug — `event.target.closest('.elementor')` is dereferenced unguarded and returns null once the clicked node has been detached by the insert. It is harmless (the handler only selects an element) and reproduces on Elementor's own Nested Tabs. The widget ships a guarded view subclass so it does not surface here; if you see it, confirm the stack frame really is inside a nested widget of ours before treating it as an EA bug.

### Menu items show but there is no container to drop widgets into

The editor element type was not registered, so Elementor never created the child containers. Confirm `assets/front-end/js/edit/mega-menu.min.js` loads in the **editor window** (not the preview iframe) and that `elementor.elementsManager` has an `eael-mega-menu` type. Note that default children are only created at element-create time: a widget instance saved while the registration was missing stays empty, so **delete and re-add the widget** after fixing the load.

### The submenu is clipped inside a sticky header

An ancestor has `overflow: hidden`. Set the containing section / container to `overflow: visible`, and raise **Style → Submenu Panel → Z-Index** if the panel renders behind following content.

### The panel opens in the wrong horizontal position

Only `full` width is pure CSS. `viewport`, `item` and `custom` are positioned by JS on open and on resize. If the menu sits inside an ancestor with `transform`, `filter` or `will-change`, that ancestor becomes the containing block for the absolutely positioned panel and the computed offset will be wrong — remove the property or switch the item to `full` width.

### Turning a submenu off still shows a container in the editor

Expected. Elementor keeps one child container per repeater row so indexes stay aligned; `render_panels()` simply skips printing it on the frontend. Turning the submenu back on restores the existing content.

### Panels briefly stack on top of each other in the editor

The child containers are mounted before the handler adds `.eael-mega-menu__panel`. It resolves on the same tick. If it *persists*, the handler threw — check the console; a JS error in `mega-menu.min.js` leaves every panel visible rather than hiding them, which is the deliberate failure mode.

## Known Limitations

- No roving-tabindex arrow-key navigation between top-level items; keyboard support is Tab + Escape based (WAI-ARIA *Disclosure Navigation with Top Level Links*, not the menubar pattern).
- Only one panel can be open at a time, by design.
- `viewport` width, and the collapsed dropdown, use `document.documentElement.clientWidth`, so a layout with an overlaid/hidden scrollbar can be off by the scrollbar width.
- A `fit to content` panel is clamped to `min(100vw, 100%)`; without it an intrinsic `max-content` width can resolve to thousands of pixels when the panel holds nowrap or full-width children.
- The mobile accordion relies on `display: contents`, which strips the `<ul>`/`<li>` list semantics in some browsers; explicit ARIA on the interactive elements compensates, but list-item counts are not announced below the breakpoint.
- A `section` item briefly shows its source section in its original position before the handler moves it, and shows nothing at all if JavaScript fails. Moving is deliberately not done in the editor, so those panels preview empty (dimmed and dashed).
- A `template` item's panel is not previewed in the editor — the editor renders the widget from an Underscore template, which cannot run PHP. The panel shows the item's unused nested container, dimmed and dashed, and the content appears on the front end. Live preview would need an AJAX render round-trip.
- Nesting a Mega Menu inside another Mega Menu's panel works and is scoped defensively, but is not a tested configuration.
