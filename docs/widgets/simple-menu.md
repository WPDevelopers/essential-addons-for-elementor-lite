# Simple Menu Widget

> A wrapper around WordPress core `wp_nav_menu()` — renders a registered nav menu (chosen from a Select control populated by `wp_get_nav_menus()`) in horizontal or vertical layout with three visual presets, a hamburger fallback below a configurable Elementor breakpoint, scroll-spy active-state for in-page hash links, optional full-width dropdown that escapes the widget's container by computing `.elementor` width, and pure-CSS dropdown indicators. **Zero Pro extension hooks** and no `eael_section_pro` upsell.

**Class file:** [`includes/Elements/Simple_Menu.php`](../../includes/Elements/Simple_Menu.php)
**Slug:** `simple-menu` (widget id `eael-simple-menu`)
**Public docs:** <https://essential-addons.com/elementor/docs/simple-menu/>
**Pro-shared:** ❌ No — Lite-only widget. **No `do_action` / `apply_filters` extension hooks** and the `eael_section_pro` upsell panel is absent. Pro doesn't reference this widget. Pro ships its own separate `Advanced_Menu` widget for richer mega-menu functionality (currently in the promotion-widgets list).

---

## Overview

Simple Menu reuses WordPress's `wp_nav_menu()` rather than building a custom Repeater — the user picks a Menu from those registered under Appearance → Menus. Three CSS-only presets shape the visual, and a hamburger appears below a configurable Elementor breakpoint (sourced live from `Plugin::$instance->breakpoints->get_active_breakpoints()` so custom breakpoints show up). JS handles dropdown toggle, in-page-anchor scroll-spy that adds an `eael-item-active` class when the target enters viewport, hamburger open/close animation, and a full-width mode that escapes the widget's container by positioning the nav absolutely at `.elementor` width. The hamburger breakpoint is enforced by an inline `<style>` block written from `render()` (live media query with the selected breakpoint's `px` value) rather than from the compiled SCSS — editor saves trigger `elementor.saver.update()` + `elementor.reloadPreview()` so the inline style refreshes.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All controls and presets | ✅ | ✅ |
| 3 presets (`preset-1`/`-2`/`-3`) | ✅ | ✅ |
| Horizontal + vertical layouts | ✅ | ✅ |
| Hamburger fallback at configurable Elementor breakpoint | ✅ | ✅ |
| Scroll-spy active class for `#hash` menu links | ✅ | ✅ |
| Full-width dropdown (escapes container) | ✅ | ✅ |
| FA4 → FA5 icon migration shim | ❌ — uses `Controls_Manager::ICONS` without `fa4compatibility` on any icon control | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |
| Pro alternative — `Advanced_Menu` widget | — | separate Pro widget; not an extension of Simple Menu |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Simple_Menu.php`](../../includes/Elements/Simple_Menu.php) | PHP widget class (1731 lines) — controls (`get_simple_menus()`, `get_dropdown_options()`), `render()`, plus several `style_*()` helpers (`style_menu()`, `style_top_level_item()`, etc.) |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | Trait imported via `use Helper` — provides widget-helper methods |
| [`includes/Classes/Helper.php`](../../includes/Classes/Helper.php) | `eael_e_optimized_markup()` for `has_widget_inner_wrapper` |
| [`src/css/view/simple-menu.scss`](../../src/css/view/simple-menu.scss) | Source styles (798 lines) — preset variants, dropdown animations, hamburger toggle, vertical layout, RTL |
| [`src/js/view/simple-menu.js`](../../src/js/view/simple-menu.js) | Frontend logic (276 lines) — dropdown toggle, scroll-spy, hamburger resize, full-width mode |
| [`config.php`](../../config.php#L1175) entry `'simple-menu'` | `Asset_Builder` dependency declaration: `simple-menu.min.css` + `simple-menu.min.js` |
| `assets/front-end/css/view/simple-menu.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/simple-menu.min.js` | Built output (do not edit) |

## Architecture

- **WordPress core menu under the hood — no Repeater** — `render()` calls `wp_nav_menu($args)` at [line 1721](../../includes/Elements/Simple_Menu.php#L1721) with `menu_class` carrying preset/layout/indicator classes. The widget passes through the entire WordPress menu system: custom-walker overrides, plugin filters on nav menus, and per-link CSS classes from Appearance → Menus all flow through unchanged. Selecting which menu to render is a single Select control populated from `wp_get_nav_menus()` at [line 88](../../includes/Elements/Simple_Menu.php#L88) — if no menus exist, the panel shows an `empty_menu_notice` linking to `/wp-admin/nav-menus.php`.
- **Hamburger breakpoint emitted as inline `<style>`, not in compiled CSS** — `render()` writes a `<style>` block at [line 1693-1717](../../includes/Elements/Simple_Menu.php#L1693) with a live media query like `@media screen and (max-width: 767px) { .eael-hamburger--tablet { … } }`. The `max-width` value comes from `Plugin::$instance->breakpoints->get_breakpoints($hamburger_device)->get_value()` — the user's *current* Elementor breakpoint config, not a hardcoded value. Editor-side, a hook at [JS line 255-264](../../src/js/view/simple-menu.js#L255) watches for `eael_simple_menu_dropdown` setting changes and forces `elementor.saver.update().then(elementor.reloadPreview)` because the inline style otherwise persists from the last render.
- **Hamburger device list is breakpoint-introspecting** — `get_dropdown_options()` at [line 1591-1618](../../includes/Elements/Simple_Menu.php#L1591) iterates `Plugin::$instance->breakpoints->get_active_breakpoints()` (so custom breakpoints from Site Settings appear), excludes `laptop` and `widescreen` because the feature is mobile-oriented, and appends two synthetic options: `desktop` (defaults to `2400px` when widescreen isn't configured) and `none` (disables the hamburger entirely). The displayed labels embed the px value inline: `"Tablet (> 767px)"`. JS extracts the numeric breakpoint by stripping non-digits from this label string with `replace(/[^0-9]/g, '')` ([JS line 188](../../src/js/view/simple-menu.js#L188)) — a brittle parsing that breaks if the label format changes.
- **Scroll-spy for `#hash` menu links** — JS at [JS line 18-53](../../src/js/view/simple-menu.js#L18) scans every `<a>` in the menu, splits its `href` on `#`, and collects same-page hash IDs into `all_ids`. On `window.load resize scroll`, each ID's target is checked via `isInViewport()` (an EA helper); when in viewport, the corresponding menu link gets `eael-item-active` + `eael-menu-<id>` classes. `localize.page_permalink` (a localized PHP-emitted constant) is used to match same-page links versus other-page hash links.
- **Full-width hamburger mode escapes the container** — when `eael_simple_menu_full_width == 'yes'` AND the widget is in hamburger mode, JS wraps the nav in `<nav class="eael-nav-menu-wrapper">` then sets `width: $('.elementor').width(); left: -navMenu.offset().left; position: absolute` ([JS line 120-132](../../src/js/view/simple-menu.js#L120)) — visually stretching the dropdown across the full `.elementor` parent regardless of where Simple Menu sits in the column structure. Breaks gracefully if `.elementor` isn't a positioned ancestor.
- **Three indicator icons rendered to data attributes, injected by JS** — `Icons_Manager::render_icon()` outputs are captured via `ob_start()` / `ob_get_clean()` ([line 1652-1668](../../includes/Elements/Simple_Menu.php#L1652)) and stuffed into `data-hamburger-icon`, `data-indicator-icon`, `data-dropdown-indicator-icon`. JS reads these data attrs and appends them as `<span>` children to `.menu-item-has-children` items ([JS line 56-71, 192-210](../../src/js/view/simple-menu.js#L56)). This avoids server-side walker patching but means the indicators appear after JS initializes, briefly causing a flicker (`eael-simple-menu--loading` class hides the menu until JS removes it at [JS line 96](../../src/js/view/simple-menu.js#L96)).
- **Active state from THREE sources** — JS adds `eael-item-active` when (a) the menu link's href matches `localize.page_permalink` (same-page check), (b) WordPress already added `current-menu-item` or `current-menu-parent` classes server-side (covers archive, single, category pages), or (c) scroll-spy is currently observing a viewport-visible hash target. All three coexist; a single render can stack two of them.
- **Click-on-href="#" delegates to indicator click** — JS at [JS line 238-241](../../src/js/view/simple-menu.js#L238) intercepts clicks on `<a href="#">` (the WordPress convention for dropdown parents) and forwards the click to the `.eael-simple-menu-indicator` sibling. Lets users tap the parent link itself to expand a submenu rather than only the small indicator.
- **`get_settings()` not `get_settings_for_display()`** at [line 1622](../../includes/Elements/Simple_Menu.php#L1622) — like Post_Grid. Dynamic-tag values may not be resolved at render time. Likely a long-standing oversight.
- **No `eael_section_pro` upsell, no extension hooks** — only one of two Interactive-category widgets (with Interactive_Circle) that has zero Pro extension surface. Pro builds a separate `Advanced_Menu` widget rather than extending this one.
- **`is_dynamic_content()` not overridden** — defaults to `false`; render-cache active.

## Render Output

```html
<div class="elementor-widget-eael-simple-menu                ← Elementor's widget wrapper, NOT custom
            [eael-hamburger--responsive | eael-hamburger--not-responsive]"   ← JS-toggled by viewport

  <!-- INLINE <style> block — only present when hamburger_device != 'none' -->
  <style>
    @media screen and (max-width: <breakpoint-px>px) {
      .eael-hamburger--<device> { … hide horizontal/vertical, show toggle … }
    }
    .eael-simple-menu-container.eael-simple-menu--loading > ul { display: flex !important; … }
    .eael-simple-menu-container.eael-simple-menu--loading li ul { visibility: hidden !important; }
  </style>

  <div class="eael-simple-menu-container
              eael-simple-menu--loading                       ← removed by JS after init
              <align-class>                                   ← per preset (varies)
              [eael-simple-menu--stretch]                     ← full_width == 'yes'
              <dropdown-item-alignment>
              preset-1 [or -2 / -3]"
       data-hamburger-icon="<rendered icon HTML>"
       data-indicator-icon="<rendered icon HTML>"
       data-dropdown-indicator-icon="<rendered icon HTML>"
       data-hamburger-breakpoints='{"mobile":"…","tablet":"…","desktop":"Desktop (> 2400px)","none":"None"}'
       data-hamburger-device="tablet">

    <!-- wp_nav_menu() output — class set via menu_class arg: -->
    <ul class="eael-simple-menu
               <dropdown-animation>                          ← e.g., "fade", "slide-up"
               eael-simple-menu-indicator
               <hamburger-item-alignment>
               [eael-simple-menu-horizontal | -vertical]">

      <li class="menu-item menu-item-has-children [current-menu-item]">
        <a href="…">Item</a>
        <!-- JS appends: -->
        <span class="eael-simple-menu-indicator">{indicator icon}</span>
        <!-- For horizontal layout, JS also appends to the <a>: -->
        <span>{indicator icon}</span>
        <ul class="sub-menu">
          <li class="menu-item-has-children">
            <a href="…">Sub Item</a>
            <span class="eael-simple-menu-dropdown-indicator">{dropdown indicator icon}</span>
            <span class="eael-simple-menu-indicator">…</span>
            …
          </li>
        </ul>
      </li>
      …
    </ul>

    <!-- Always rendered; CSS hides it outside hamburger viewport -->
    <button class="eael-simple-menu-toggle">
      <span class="sr-only">Hamburger Toggle Menu</span>
      {hamburger icon}
    </button>
  </div>
</div>
```

Notes:

- The `eael-simple-menu--loading` class is on the container at first render and removed at the end of JS init ([JS line 96](../../src/js/view/simple-menu.js#L96)) — prevents the flash-of-unstyled menu while JS injects indicator icons.
- The `wp_nav_menu()` output uses WordPress core walker — third-party plugins that filter `wp_nav_menu_items`, `nav_menu_css_class`, or `walker_nav_menu_start_el` all affect this widget's output.
- `data-hamburger-breakpoints` is a JSON map where **values are display labels with embedded px values** like `"Tablet (> 767px)"`, not raw numbers. JS parses the px out via regex. Brittle but stable.
- When responsive mode activates, JS injects a `<span class="eael-simple-menu-toggle-text">` *before* the menu list ([JS line 75](../../src/js/view/simple-menu.js#L75)) to display the current menu item's text alongside the hamburger button.
- Full-width hamburger mode runtime-wraps the menu in `<nav class="eael-nav-menu-wrapper">` (added by JS, not in initial render).

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Simple_Menu.php#L102) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_simple_menu_menu` | SELECT | first menu id | Content → General | Which registered menu to render; sourced from `wp_get_nav_menus()` |
| `menu_manager_notice` | NOTICE | — | Content → General | Info link to `/wp-admin/nav-menus.php` |
| `empty_menu_notice` | NOTICE (warning) | — | Content → General | Shown only when no menus exist |
| `eael_simple_menu_preset` | SELECT | `preset-1` | Content → General | `preset-1` / `preset-2` / `preset-3` |
| `eael_simple_menu_layout` | SELECT | `horizontal` | Content → General | `horizontal` (adds `eael-simple-menu-horizontal`) or `vertical` |
| `eael_simple_menu_hamburger_disable_selected_menu` | SWITCHER | `no` (returns `hide`) | Content → Hamburger Options | Adds `eael_simple_menu_hamburger_disable_selected_menu_hide` prefix class — hides the active menu item in mobile view |
| `eael_simple_menu_hamburger_alignment` | CHOOSE | `right` | Content → Hamburger Options | Hamburger button alignment; prefix class `eael-simple-menu-hamburger-align-<value>` |
| `eael_simple_menu_full_width` | SWITCHER | `no` | Content → Hamburger Options | Stretches hamburger dropdown to `.elementor` width via JS-applied CSS |
| `eael_simple_menu_hamburger_icon` | ICONS | `fas fa-bars` | Content → Hamburger Options | Hamburger icon |
| `eael_simple_menu_dropdown` | SELECT | `tablet` | Content → Hamburger Options | Breakpoint at which the hamburger appears; populated from `Plugin::breakpoints` (excludes laptop / widescreen, includes synthetic `desktop` at 2400px and `none`). Adds prefix class `eael-hamburger--<value>` |
| `eael_simple_menu_item_indicator` | ICONS | indicator | Style → Top Level Items | Top-level dropdown caret; rendered to `data-indicator-icon` |
| `eael_simple_menu_dropdown_item_indicator` | ICONS | dropdown indicator | Style → Top Level Items | Nested dropdown caret; rendered to `data-dropdown-indicator-icon` |
| `eael_simple_menu_dropdown_animation` | SELECT | — | Style → Dropdown | Animation class added to the `<ul>` (`fade`, `slide-up`, etc.) |
| `eael_simple_menu_item_alignment` | CHOOSE | — | Style → Top Level Items | Used for preset-1 |
| `eael_simple_menu_item_alignment_center` | CHOOSE | — | Style → Top Level Items | Used for preset-2 |
| `eael_simple_menu_item_alignment_right` | CHOOSE | — | Style → Top Level Items | Used for preset-3 |
| `eael_simple_menu_dropdown_item_alignment` | CHOOSE | — | Style → Dropdown | Dropdown alignment class (`eael-simple-menu-dropdown-align-left/right`); affects vertical preset's nested padding via JS |
| `eael_hamburger_menu_item_alignment` | CHOOSE | empty | Style → Hamburger Menu | Aligns mobile menu items via class `eael-hamburger-<left/center/right>` (preset-1 only by condition) |
| Style → Main Menu / Top Level Items / Mobile Menu / Dropdown | various | — | Style tab | Typography, padding, margin, border, background, hover/active states for each tier |

## Conditional Dependencies

```text
# Menu availability
eael_simple_menu_menu               → visible when registered menus exist
empty_menu_notice                   → visible when NO menus exist

# Preset-driven alignment selectors
eael_simple_menu_item_alignment         → render uses for preset-1
eael_simple_menu_item_alignment_center  → render uses for preset-2
eael_simple_menu_item_alignment_right   → render uses for preset-3
eael_hamburger_menu_item_alignment      → visible when preset != preset-2 AND != preset-3

# Hamburger conditions
data-hamburger-breakpoints / device → emitted regardless;
                                       inline <style> only emitted when device != 'none'
```

No `eael_section_pro` upsell — no Pro-gated controls.

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. **The widget DOES indirectly trigger WordPress's full nav-menu filter chain** through `wp_nav_menu($args)`: any code listening to `wp_nav_menu_items`, `walker_nav_menu_start_el`, `nav_menu_css_class`, `wp_nav_menu_objects`, etc. affects this widget's output transparently.

JS-side custom events / globals:

- Consumes `localize.page_permalink` (PHP-emitted constant) for same-page hash matching.
- Consumes `$.fn.isInViewport` (EA Helper plugin extension) for scroll-spy hit-testing.
- No widget-specific custom events emitted.
- Does NOT subscribe to cross-widget reflow events (`ea-toggle-triggered`, etc.) — a Simple Menu inside a tab won't recompute its viewport on tab change.
- In editor mode, attaches to `elementor.channels.editor.on('change', …)` to force preview reload when the dropdown breakpoint changes.

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): none apply — this widget uses neither Liquid Glass nor FA4 shim nor WPML nor `has_pro` handoff.

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on('elementor/frontend/init', …)` → `elementorFrontend.hooks.addAction('frontend/element_ready/eael-simple-menu.default', SimpleMenu)`. Older registration pattern (NOT the newer `eael.hooks.addAction("init", "ea", …)`).
- **Guard:** `if (eael.elementStatusCheck('eaelSimpleMenu')) return false;` at module top — prevents double-init.
- **Vendor dependency:** none directly. Uses jQuery, native APIs, `localize.page_permalink`, and `$.fn.isInViewport`.
- **Reads on init:** `data-hamburger-icon`, `data-indicator-icon`, `data-dropdown-indicator-icon`, `data-hamburger-breakpoints` (JSON), `data-hamburger-device`. Layout determined by class check on `.eael-simple-menu`.
- **Branches:**
  - per-link scan: same-page-permalink-match adds `eael-item-active`; collects `#hash` IDs into `all_ids`; WP's `current-menu-item`/`-parent` also wins.
  - horizontal-only: append indicator spans to `.menu-item-has-children > a`.
  - on resize / load: call `eael_menu_resize($hamburger_max_width)` to enter/exit hamburger mode.
  - hamburger entry: add `eael-simple-menu-hamburger` + `eael-simple-menu-responsive` classes, set toggle text, optionally wrap in `.eael-nav-menu-wrapper` and apply stretch CSS.
  - hamburger exit: remove those classes; clear inline styles; `.removeAttr('style')` on the nav.
- **Scroll-spy:** on `load resize scroll`, iterate `all_ids`; for each ID whose target is in viewport, add `eael-menu-<id>` + `eael-item-active` to the matching `<a>`; for non-visible ones, remove those classes. Two selectors hit: with-permalink form `a[href="<permalink>#<id>"]` and bare form `a[href="#<id>"]`.
- **Dropdown toggle:** click on `.eael-simple-menu-indicator` toggles `.eael-simple-menu-indicator-open` and slides the sibling `<ul>`. Auto-closes other open dropdowns at the same nesting level.
- **Same-level slide-up on link click:** clicking any non-`#` link in the responsive menu closes the parent dropdown — `.slideUp(300)` on the closest `selectorByType`.
- **Editor preview reload:** in edit mode, `elementor.channels.editor.on('change', …)` watches for `eael_simple_menu_dropdown` changes; on hit, runs `elementor.saver.update().then(elementor.reloadPreview)`. Necessary because the inline `<style>` block from `render()` would otherwise stick to old breakpoint.

## Common Issues

### Menu doesn't appear in the Select dropdown

- **Likely cause:** no menus registered under Appearance → Menus, OR menus exist but `wp_get_nav_menus()` returns empty (filtered/excluded by another plugin).
- **Diagnose:** check `wp_get_nav_menus()` output — does it list your menu? The empty-menu notice appears in the panel when this returns empty.
- **Fix:** create a menu via Appearance → Menus; ensure no plugin filter is restricting `wp_get_nav_menus()` to empty.

### Custom menu walker / plugin filter breaks the indicators

- **Likely cause:** another plugin filters `walker_nav_menu_start_el` or `nav_menu_css_class` and removes the `menu-item-has-children` class.
- **Diagnose:** browser DevTools — does each parent `<li>` have the class? Indicators are appended only to elements matching `.menu-item-has-children`.
- **Fix:** disable conflicting filter, or filter `nav_menu_css_class` to re-add `menu-item-has-children` for items with submenus.

### Hamburger appears at the wrong breakpoint after editing

- **Likely cause:** breakpoint media query is in an inline `<style>` block emitted at render. In the editor, changing the breakpoint setting does NOT auto-rerender — the inline style sticks.
- **Diagnose:** check the rendered HTML — is the `<style>` block's media query matching the panel value?
- **Fix:** in the editor, the change handler at [JS line 256-263](../../src/js/view/simple-menu.js#L256) auto-triggers `saver.update().then(reloadPreview)`. If it didn't fire (errored), manually save and refresh.

### Hash-link scroll-spy never activates

- **Likely cause:** `$.fn.isInViewport` is not defined (EA Helper plugin not loaded), OR the hash links point to other-page URLs (only same-page links are tracked).
- **Diagnose:** browser console — `typeof jQuery.fn.isInViewport` should return `'function'`. The match check at [JS line 28](../../src/js/view/simple-menu.js#L28) compares against `localize.page_permalink`.
- **Fix:** ensure EA's frontend asset bundle loads before the widget; verify the link URL exactly matches the current page permalink + `#` + id.

### Full-width hamburger dropdown stretches into the wrong area

- **Likely cause:** JS uses `$('.elementor').width()` and `-$navMenu.offset().left` to absolutely-position the nav. If multiple `.elementor` elements exist (e.g., in a multi-template page) or the offset reads while the parent is `transform`-positioned, the math is off.
- **Diagnose:** browser DevTools — inspect `.eael-nav-menu-wrapper` inline styles; check `.elementor` matches the intended ancestor.
- **Fix:** disable Full Width or restructure parent containers. Known limitation of `position: absolute` + viewport-width math.

### `eael_simple_menu_dropdown_indicator` icon doesn't appear on nested items

- **Likely cause:** the dropdown indicator is appended only on top-level horizontal layout ([JS line 65-71](../../src/js/view/simple-menu.js#L65)) — `.eael-simple-menu > li ul li.menu-item-has-children`. Vertical layout doesn't get the dropdown-indicator span at all.
- **Diagnose:** check the layout setting.
- **Fix:** working as designed; the indicator-icon (top-level) is appended in both horizontal and vertical, but the *dropdown* indicator is horizontal-only.

## Known Limitations

- **Inline `<style>` block per widget render** — adds ~700 bytes of HTML for the hamburger media query plus loading-state rules. Multiple Simple Menu widgets on the same page emit duplicate inline styles.
- **`get_settings()` not `get_settings_for_display()`** at [line 1622](../../includes/Elements/Simple_Menu.php#L1622) — dynamic-tag values may not be resolved at render time. Inconsistent with most other EA widgets.
- **Brittle breakpoint label parsing** — JS reads breakpoint labels like `"Tablet (> 767px)"` and extracts the number via `.replace(/[^0-9]/g, '')` ([JS line 188](../../src/js/view/simple-menu.js#L188)). If `get_dropdown_options()` label format ever changes (or a label includes a non-px digit), parsing breaks silently.
- **`isInViewport()` runs on every `scroll` event** ([JS line 41-53](../../src/js/view/simple-menu.js#L41)) — no debounce/throttle. For long pages with many hash links, this is N hit-tests per scroll tick. Performance-sensitive on low-end devices.
- **Full-width mode depends on `.elementor` ancestor** — if the widget is rendered outside an `.elementor` container (rare, but possible in custom Loop Grid templates), `$('.elementor').width()` returns 0 and the dropdown collapses.
- **No FA4 → FA5 shim on icon controls** — `eael_simple_menu_hamburger_icon`, `_item_indicator`, `_dropdown_item_indicator` use `Controls_Manager::ICONS` without `fa4compatibility` field. Older saved widgets with FA4 strings won't auto-migrate.
- **`current-menu-item` matching on hash URLs may double-activate** — a link with `href="https://site/page#section"` matching both the permalink AND a scroll-spy hash can get `eael-item-active` from two code paths. Visual outcome is the same, but classlist gets duplicated entries before normalization.
- **Hamburger always rendered as `<button>` even when `device == 'none'`** — `eael_simple_menu_dropdown == 'none'` skips the inline media query, but the toggle button is still in the DOM (just CSS-hidden). Cosmetic but wasted markup.
- **Editor change handler hardcodes `eael_simple_menu_dropdown` only** ([JS line 258](../../src/js/view/simple-menu.js#L258)) — changing other settings that also affect inline `<style>` (none exist today, but a future addition would silently fail to refresh).
- **No cross-widget reflow listeners** — Simple Menu inside a hidden tab/accordion does not re-detect viewport size on activation. Hamburger state computed at init may be wrong if the wrapping widget revealed the menu at a different size.
- **Vertical layout indents nested dropdown items via inline CSS** at [JS line 213-227](../../src/js/view/simple-menu.js#L213) — reads parent `padding-left` / `padding-right` and adds `20px` to nested `<a>` elements. Hardcoded 20px not panel-controllable; multiplies on deeply nested menus.
