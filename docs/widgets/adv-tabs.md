# Advanced Tabs Widget

> Horizontal or vertical tabs widget with scheduled active tabs (DATE_TIME windows), URL hash deep-link with `hashchange` listener, full keyboard navigation (Arrow Left/Right + tabindex), Swiper-in-tab rehydration on activation, cross-widget reflow notifications for galleries inside hidden panels, and a Pro `glassey` (Liquid Glass) style added via filter + action + a Lite-emitted SVG filter element.

**Class file:** [`includes/Elements/Adv_Tabs.php`](../../includes/Elements/Adv_Tabs.php)
**Slug:** `adv-tabs` (widget id `eael-adv-tabs`)
**Public docs:** <https://essential-addons.com/elementor/docs/advanced-tabs/>
**Pro-shared:** ✅ Yes — Pro adds the `glassey` (Liquid Glass) style via three extension points: `apply_filters('eael_adv_tab_styles', …)` (chooser entries), `do_action('eael_adv_tab_liquid_glass_effect_tab_bar', $this)` (controls injection), and `apply_filters('eael_adv_tab_glassey_class', '', $style)` (CSS class). The `feTurbulence` / `feDisplacementMap` SVG filter `#switcher` is emitted by Lite. **Not** the standard Liquid Glass injection chain documented in [`_patterns.md`](_patterns.md#liquid-glass-injection-chain) — uses different hook names.

---

## Overview

Advanced Tabs renders a horizontal or vertical tabbed interface; clicking a tab swaps the visible content panel. Each tab is a Repeater entry with title, optional icon (Font Awesome) or image, and either WYSIWYG content or a Saved Template body. Distinctive features beyond a typical tabs widget: a "scheduled active tab" capability that picks the active tab based on a DATE_TIME window per row, a Toggle Tab mode that lets the active tab collapse on second click, full keyboard navigation per the WAI-ARIA Tabs pattern (Arrow keys + tabindex shifting), URL hash deep-link plus a runtime `hashchange` listener for in-page navigation, and a JS handler that re-runs Swiper layout calculations for any swiper inside a newly-activated panel (otherwise hidden swipers stay measured at zero width). A Pro `glassey` style adds a Liquid Glass effect to the tab bar via three Pro hook handlers; Lite still emits the SVG filter so Pro can reference it via CSS `filter: url(#switcher)`.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Horizontal + vertical layouts | ✅ | ✅ |
| `default` + `glassey` (Liquid Glass) styles | ❌ — Pro alert appears under the chooser; Tab Bar style controls hidden | ✅ via `eael_adv_tab_styles`, `eael_adv_tab_liquid_glass_effect_tab_bar`, `eael_adv_tab_glassey_class` |
| Scheduled active tab (DATE_TIME window) | ✅ | ✅ |
| Toggle Tab (collapse on second click) | ✅ | ✅ |
| Per-tab WYSIWYG OR Saved Template content | ✅ | ✅ |
| URL hash deep-link + runtime `hashchange` listener | ✅ | ✅ |
| Keyboard a11y (`role="tab"`, `tablist`, Arrow Left/Right, tabindex) | ✅ | ✅ |
| Swiper-in-tab rehydration on activation | ✅ | ✅ |
| Cross-widget reflow on tab switch (Post Grid, Filterable Gallery, Twitter/Insta Feed, Event Calendar) | ✅ | ✅ |
| FA4 → FA5 icon migration shim | ✅ — see [`_patterns.md § FA4`](_patterns.md#fa4--fa5-icon-migration-shim) | ✅ |
| WPML translation for Saved Template id | ✅ — see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation) | ✅ |
| `eael_section_pro` upsell panel | shown — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) | hidden |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Adv_Tabs.php`](../../includes/Elements/Adv_Tabs.php) | PHP widget class (1496 lines) — controls, `render()`, `get_scheduled_active_tab()` private helper |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `eael_validate_html_tag()`, `eael_allowed_tags()`, `is_elementor_publish_template()`, `eael_onpage_edit_template_markup()`, `str_to_css_id()`, `eael_e_optimized_markup()` |
| [`src/css/view/advanced-tabs.scss`](../../src/css/view/advanced-tabs.scss) | Source styles (210 lines) — horizontal/vertical layouts, caret pseudo-element, top-icon vs inline-icon variants, RTL caret, mobile-768 wrap |
| [`src/js/view/advanced-tabs.js`](../../src/js/view/advanced-tabs.js) | Frontend logic (280 lines) — tab switching, hash + hashchange, Swiper rehydrate helper, isotope/event-calendar reflow, keyboard nav |
| [`config.php`](../../config.php#L514) entry `'adv-tabs'` | `Asset_Builder` dependency declaration: `advanced-tabs.min.css` + `advanced-tabs.min.js` |
| `assets/admin/images/layout-previews/advtab-default.png`, `advtab-glassey.png` | Image-picker thumbnails for the styles chooser |
| `assets/front-end/css/view/advanced-tabs.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/advanced-tabs.min.js` | Built output (do not edit) |

## Architecture

- **Three-hook Pro `glassey` extension is NOT the standard Liquid Glass pattern** — Pro adds the `glassey` style via three independent hooks at distinct lifecycle points: `apply_filters('eael_adv_tab_styles', …)` at [line 111](../../includes/Elements/Adv_Tabs.php#L111) injects the chooser entry; `do_action('eael_adv_tab_liquid_glass_effect_tab_bar', $this)` at [line 740](../../includes/Elements/Adv_Tabs.php#L740) lets Pro append a Tab Bar style sub-section; `apply_filters('eael_adv_tab_glassey_class', '', $settings['eael_adv_tab_new_style'])` at [line 1324](../../includes/Elements/Adv_Tabs.php#L1324) returns the Pro CSS class to attach to `.eael-tabs-nav`. The `<svg><filter id="switcher">…</filter></svg>` (`feTurbulence` + `feDisplacementMap`) is emitted by **Lite** at [line 1426-1434](../../includes/Elements/Adv_Tabs.php#L1426) when `glassey` is selected — Pro CSS references `filter: url(#switcher)`. When Pro is inactive, picking `glassey` shows a Pro-alert heading; the chooser still lists the option (different from a hard-conditional hide).
- **Scheduled active tab is a DATE_TIME window evaluator** — [`get_scheduled_active_tab()`](../../includes/Elements/Adv_Tabs.php#L1226) loops Repeater rows, parses `start_date` and optional `end_date` against `current_time('timestamp')`, picks the **most recently started** matching tab via `usort` by `start_timestamp DESC`. Wins over the per-row `active-default` switcher when set. Server-time evaluated at render — caching plugins can freeze the result.
- **Two render paths driven by `eael_adv_tabs_text_type`** — `content` (per-Repeater WYSIWYG, `wp_kses` filtered unless `eael/advanced_tabs/allow_dangerous_html` is true) vs `template` (Saved Template included via `Plugin::$instance->frontend->get_builder_content()`). Saved-template path explicitly checks for **page-id == template-id** or template being a revision of the current page, refusing to render to prevent infinite recursion ([line 1472](../../includes/Elements/Adv_Tabs.php#L1472)).
- **Caret visibility uses inverted naming** — control `eael_adv_tabs_tab_caret_show` defaults to `yes` (caret visible). When **off**, render adds class `active-caret-on` to the wrapper, which the SCSS uses as the **hide** trigger (`.active-caret-on … li.active:after { display: none }` — [line 68-69 of SCSS](../../src/css/view/advanced-tabs.scss#L68)). Class name reads "on" but semantically means "caret-suppressed". Same pattern for `responsive_vertical_layout` (default `yes`; class `responsive-vertical-layout` is added when value is **not** `yes`, and SCSS targets it to override mobile vertical wrapping).
- **Hash deep-link with runtime `hashchange` listener** — JS reads `window.location.hash` on init and binds `window.addEventListener('hashchange', …)` to react to in-page navigation ([JS line 62-67](../../src/js/view/advanced-tabs.js#L62)). The literal hash `safari` is rewritten to `eael-safari` for tabs and `eael-safari-tab` for content panels in PHP ([line 1340 / 1444](../../includes/Elements/Adv_Tabs.php#L1340)) and JS ([line 60](../../src/js/view/advanced-tabs.js#L60)) — same browser-detection collision as Adv_Accordion. Nested tabs are handled in the hash branch ([JS line 99-108](../../src/js/view/advanced-tabs.js#L99)): if the matching content lives inside a parent tab's content panel, both parent and child are activated.
- **Swiper-in-tab rehydration is required because hidden tabs measure as zero-width** — `eaelFixSwiperHover()` at [JS line 5-43](../../src/js/view/advanced-tabs.js#L5) calls `swiper.update() / updateSize() / updateSlides() / updateProgress() / updateSlidesClasses()`, removes `inert` + `aria-hidden` + `pointer-events: none` from each slide, re-runs `slideTo(activeIndex, 0, false)`, and forces a paint via `el.offsetHeight` read. Without this, a Swiper carousel inside an inactive tab stays broken after the tab is opened.
- **Cross-widget reflow on tab switch** — JS reflows any Isotope-driven gallery in the newly-active panel (Filterable Gallery, Post Grid, Twitter Feed, Instagram Feed, Pro Premium Gallery) and broadcasts `eael.hooks.doAction("eventCalendar.reinit")` for Pro Event Calendar ([JS line 212-243](../../src/js/view/advanced-tabs.js#L212)). Standard EA cross-widget reflow event `ea-advanced-tabs-triggered` is fired at [JS line 203](../../src/js/view/advanced-tabs.js#L203).
- **Keyboard a11y meets the WAI-ARIA Tabs pattern** — render emits `role="tablist"` on `<ul>`, `role="tab"` + `aria-selected` + `aria-controls` + `aria-expanded` + `tabindex` on each `<li>` ([line 1354-1363](../../includes/Elements/Adv_Tabs.php#L1354)). JS shifts `tabindex` so only the active tab is `0` and others `-1` ([JS line 209-210, 258-259](../../src/js/view/advanced-tabs.js#L209)). Arrow Right / Arrow Left navigation cycles tabs ([JS line 246-256](../../src/js/view/advanced-tabs.js#L246)). Significantly better a11y than Adv_Accordion.
- **`is_dynamic_content()` disables Elementor's render cache** when any tab uses a Saved Template ([line 64-87](../../includes/Elements/Adv_Tabs.php#L64)). No FAQ schema branch (unlike Adv_Accordion).

## Render Output

```html
<div id="eael-advance-tabs-<widget-id>"
     class="eael-advance-tabs
            [eael-tabs-horizontal | eael-tabs-vertical]
            [eael-tab-auto-active]            ← when default_active_tab == 'yes'
            [eael-tab-toggle]                  ← when toggle_tab == 'yes'
            [active-caret-on]                  ← when caret_show != 'yes' (HIDES caret)
            [responsive-vertical-layout]"      ← when responsive_vertical_layout != 'yes' (suppresses mobile wrap)
     data-tabid="<widget-id>"
     data-scroll-on-click="no"  [or "yes"]
     data-scroll-speed="300"
     [?] data-custom-id-offset="0">

  <div class="eael-tabs-nav [<glassey-class>]">  ← glassey-class injected by Pro filter
    <ul class="[eael-tab-inline-icon | eael-tab-top-icon]" role="tablist">

      <!-- Per Repeater item: -->
      <li id="<custom-id-or-slugified-title>"
          class="[active-default] eael-tab-item-trigger eael-tab-nav-item"
          aria-selected="true|false"
          data-tab="1"
          role="tab"
          tabindex="0|-1"
          aria-controls="<id>-tab"
          aria-expanded="false">

        [?] {title-before-icon span — when icon position == inline AND alignment == 'after'}
            <span class="eael-tab-title title-before-icon">Title</span>

        [?] {icon — when icon_show == 'yes' AND tab.icon_type == 'icon'}
            <i class="…"></i>   or   <svg>…</svg>

        [?] {image — when icon_show == 'yes' AND tab.icon_type == 'image'}
            <img src="…" alt="…">

        [?] {title-after-icon span — when icon position == inline AND alignment != 'after'}
            <span class="eael-tab-title title-after-icon">Title</span>

        [?] {title — when icon position == top (stacked)}
            <span class="eael-tab-title title-after-icon">Title</span>
      </li>
      …

      [?] <!-- Lite emits this SVG filter ONLY when style == 'glassey'.
              Pro CSS references filter: url(#switcher) on the tab bar. -->
      <div class="eael-tabs-glassey-svg">
        <svg style="display: none">
          <filter id="switcher" x="0" y="0" width="100%" height="100%" filterUnits="objectBoundingBox">
            <feTurbulence type="fractalNoise" baseFrequency="0.003 0.007" numOctaves="1" result="turbulence" />
            <feDisplacementMap in="SourceGraphic" in2="turbulence" scale="200" xChannelSelector="R" yChannelSelector="G" />
          </filter>
        </svg>
      </div>
    </ul>
  </div>

  <div class="eael-tabs-content">
    <div id="<id>-tab"
         class="clearfix eael-tab-content-item [active-default]"
         data-title-link="<id>-tab">
      {WYSIWYG content OR Saved Template render}
    </div>
    …
  </div>
</div>
```

Notes:

- `<ul>` carries `role="tablist"` but `<li>` is not wrapped in an anchor — the click target is the `<li>` itself.
- `aria-controls` value is `<id>-tab` but tabs whose Custom ID is `safari` get content `id="eael-safari-tab"` while the `<li>` aria-controls still says `eael-safari-tab` (not `safari-tab`) — the rewrite is consistent because the tab `id` is rewritten before `aria-controls` is built.
- The `<svg>` filter is emitted in the **navigation `<ul>`**, not in `<head>` or in the content area — so its `id="switcher"` is not unique across the page if multiple Adv_Tabs instances use `glassey`. Browsers resolve `filter: url(#switcher)` to the first match in the document.
- `data-tab` is a **1-based** counter, not the array index.
- `eael-tab-title` wrapper span uses `title-before-icon` / `title-after-icon` modifier classes purely for layout margins ([SCSS line 707-708](../../includes/Elements/Adv_Tabs.php#L707)) — semantics are positional, not visual emphasis.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Adv_Tabs.php#L98) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_adv_tab_new_style` | CHOOSE (image picker) | `default` | Content → General Settings | `default` vs `glassey`; Pro-only `glassey` triggers SVG filter emission and Pro extension hooks |
| `eael_adv_tab_style_pro_alert` | HEADING | — | Content → General Settings | Pro alert shown when `glassey` selected without Pro |
| `eael_adv_tab_layout` | SELECT | `eael-tabs-horizontal` | Content → General Settings | `eael-tabs-horizontal` vs `eael-tabs-vertical` (added as wrapper class) |
| `eael_adv_tabs_icon_show` | SWITCHER | `yes` | Content → General Settings | Toggles all per-tab icons globally |
| `eael_adv_tab_icon_position` | SELECT | `eael-tab-inline-icon` | Content → General Settings | `eael-tab-top-icon` (stacked) vs `eael-tab-inline-icon` (inline); applied to `<ul>` |
| `eael_adv_tabs_tab_icon_alignment` | CHOOSE | `before` | Content → General Settings | Before/after title; only inline-icon mode |
| `eael_adv_tabs_default_active_tab` | SWITCHER | `yes` | Content → General Settings | Adds `eael-tab-auto-active` class — JS opens first tab if no `active-default` and no scheduled tab match |
| `eael_adv_tabs_toggle_tab` | SWITCHER | `''` | Content → General Settings | Adds `eael-tab-toggle` class — JS allows active tab to collapse on second click |
| `eael_adv_tabs_custom_id_offset` | NUMBER (px) | `0` | Content → General Settings | Subtracted from scroll target on hash deep-link / scroll-on-click |
| `eael_adv_tabs_scroll_speed` | NUMBER (ms) | `300` | Content → General Settings | jQuery `animate` duration |
| `eael_adv_tabs_scroll_onclick` | SWITCHER | `no` | Content → General Settings | Animate scroll to opened tab content |
| `eael_adv_tabs_tab` | REPEATER | 3 default rows | Content → Content | Per-tab settings (see sub-table) |
| `eael_section_pro` / `eael_control_get_pro` | section + CHOOSE | — | Content → Go Premium | Standard upsell — see [`_patterns.md § upsell`](_patterns.md#eael_section_pro-standard-upsell-panel) |
| `eael_adv_tabs_tab_caret_show` | SWITCHER | `yes` | Style → Tab Style | ⚠ Inverted: when `!= 'yes'`, render adds `active-caret-on` class which **hides** caret |
| `eael_adv_tabs_tab_caret_size` / `_color` | SLIDER / COLOR | `10` / `#444` | Style → Tab Style | Caret pseudo-element border-width / border-color |
| `responsive_vertical_layout` | SWITCHER | `yes` | Style → Responsive Controls | ⚠ Inverted: when `!= 'yes'`, adds `responsive-vertical-layout` class which suppresses default mobile wrap |
| Style → General / Tab Title / Tab Content | various | — | Style tab | Padding, border, border-radius, box-shadow, typography, normal/active/hover backgrounds |

### Per-item Repeater controls (`eael_adv_tabs_tab`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_adv_tabs_tab_show_as_default` | SWITCHER | `inactive` (return `active-default`) | Adds `active-default` class — JS opens this tab on init |
| `eael_adv_tabs_tab_show_as_scheduled` | SWITCHER | `no` | Enables DATE_TIME-window-based active selection; overrides `_show_as_default` |
| `eael_adv_tabs_schedule_date` / `_schedule_end_date` | DATE_TIME | now / empty | Active window; matched against `current_time('timestamp')` server-side |
| `eael_adv_tabs_icon_type` | CHOOSE | `icon` | `none` / `icon` (FA) / `image` (MEDIA) |
| `eael_adv_tabs_tab_title_icon_new` (+ FA4 shim `eael_adv_tabs_tab_title_icon`) | ICONS | `fas fa-home` | Per-tab Font Awesome icon |
| `eael_adv_tabs_tab_title_image` | MEDIA | placeholder | Per-tab image (when `icon_type == 'image'`) |
| `eael_adv_tabs_tab_title` | TEXT (dynamic, AI) | `Tab Title` | Tab label; `wp_kses_post`-filtered in render |
| `eael_adv_tabs_tab_title_html_tag` | SELECT | `span` | Title tag (`h1`–`h6`, `div`, `span`, `p`); validated via `Helper::eael_validate_html_tag()` |
| `eael_adv_tabs_text_type` | SELECT | `content` | `content` (WYSIWYG) vs `template` (Saved Template) |
| `eael_primary_templates` | `eael-select2` | empty | Saved Template post id; `wpml_object_id`-translated |
| `eael_adv_tabs_tab_content` | WYSIWYG (dynamic) | placeholder | Tab body when `text_type == 'content'`; `wp_kses` unless `eael/advanced_tabs/allow_dangerous_html` is true |
| `eael_adv_tabs_tab_id` | TEXT (AI) | empty | Custom anchor id for hash deep-link; falls back to slugified title |

## Conditional Dependencies

```text
# Pro style gate
eael_adv_tab_style_pro_alert            → visible when eael_adv_tab_new_style in pro styles
                                          (Pro overrides via 'eael_adv_tab_styles' filter)

# Icon controls
eael_adv_tab_icon_position              → visible when eael_adv_tabs_icon_show == 'yes'
eael_adv_tabs_tab_icon_alignment        → visible when eael_adv_tab_icon_position == 'eael-tab-inline-icon'
eael_adv_tabs_title_width               → visible when eael_adv_tab_layout == 'eael-tabs-vertical'

# Caret
eael_adv_tabs_tab_caret_size            → visible when eael_adv_tabs_tab_caret_show == 'yes'
eael_adv_tabs_tab_caret_color           → same

# Per-tab Repeater
eael_adv_tabs_schedule_date             → visible when eael_adv_tabs_tab_show_as_scheduled == 'yes'
eael_adv_tabs_schedule_end_date         → same
eael_adv_tabs_tab_title_icon_new        → visible when eael_adv_tabs_icon_type == 'icon'
eael_adv_tabs_tab_title_image           → visible when eael_adv_tabs_icon_type == 'image'
eael_primary_templates                  → visible when eael_adv_tabs_text_type == 'template'
eael_adv_tabs_tab_content               → visible when eael_adv_tabs_text_type == 'content'

# Pro Tab Bar style controls
{Pro-injected via eael_adv_tab_liquid_glass_effect_tab_bar action} → conditional on style == 'glassey'

# Pro upsell
eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active

# Header style tabs
eael_adv_tabs_header_normal (whole tab) → visible when eael_adv_tab_new_style == 'default'
```

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_adv_tab_styles` | filter (emitted) | `array { styles, conditions }` | **Pro extension** — adds the `glassey` chooser entry. Pro must preserve the `{ styles, conditions }` shape; the `conditions` array drives `eael_adv_tab_style_pro_alert` visibility. |
| `eael_adv_tab_liquid_glass_effect_tab_bar` | action (emitted) | `(Adv_Tabs $widget)` | **Pro extension** — Pro injects a Tab Bar style sub-section inside the Tab Title style section. ⚠ Un-prefixed (no `eael/`); legacy. |
| `eael_adv_tab_glassey_class` | filter (emitted) | `(string $class, string $style)` | **Pro extension** — Pro returns a CSS class to append to `.eael-tabs-nav` when style is `glassey`. |
| `eael/advanced_tabs/allow_dangerous_html` | filter (emitted) | `bool $allow = false` | When true, Repeater tab content is echoed without `wp_kses`. Use only on fully-trusted editors. |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the `eael_section_pro` upsell when Pro is active. |
| `wpml_object_id` | filter (consumed) | `(int $id, 'wp_template', true)` | Translate the Saved-Template id per language; see [`_patterns.md § WPML`](_patterns.md#wpml-media-translation). |

JS-side custom events / globals:

- `eael.hooks.doAction("ea-advanced-tabs-triggered", $activeTabContent)` — fires 50ms after activation; sister to `ea-advanced-accordion-triggered` and `ea-lightbox-triggered`. Consumers reflow when a panel becomes visible.
- `eael.hooks.doAction("eventCalendar.reinit")` — fired when the active panel contains `.eael-event-calendar-cls` (Pro Event Calendar).
- `window.eaelPreventResizeOnClick = true` — set on every tab click; never reset (same leaked global as Adv_Accordion).
- Triggered Isotope reflows: `.eael-post-grid.eael-post-appender`, `.eael-twitter-feed-masonry`, `.eael-filter-gallery-container`, `.eael-instafeed`, `.premium-gallery-container` (Pro).

For shared patterns referenced in this doc, see [`_patterns.md`](_patterns.md): FA4 shim, WPML, `eael_section_pro` upsell.

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", () => elementorFrontend.hooks.addAction('frontend/element_ready/eael-adv-tabs.default', handler))` — newer EA registration pattern.
- **Guard:** `if (eael.elementStatusCheck('eaelAdvancedTabs')) return false;` — prevents double-init across re-fires of `frontend/element_ready` (Adv_Accordion lacks this).
- **Vendor dependency:** none directly (jQuery + native APIs). Reads instances of `Swiper` (`element.swiper`) for the rehydrate helper.
- **Reads on init:** `data-scroll-on-click`, `data-scroll-speed`, `data-custom-id-offset` from `.eael-advance-tabs`. `window.location.hash` for deep-link. `id` of the `.eael-advance-tabs` to scope all queries.
- **Branches:**
  - hash present — find tab `<li>` whose `id` matches; activate it (and any parent tab if it's nested).
  - hashchange — re-fire activation on every URL hash change (in-page navigation).
  - on click in `eael-tab-toggle` mode — toggle `active`/`inactive` on this tab; if it was active, panel collapses.
  - on click in standard mode — set this tab `active`, all others `inactive`.
  - vertical layout AND scroll-on-click — scroll target is the `.eael-tabs-content` container, not the individual tab content (better UX since vertical content all shares the same top edge).
- **Swiper rehydrate:** `eaelFixSwiperHover($activeTabContent)` runs 50ms after activation. For each `.swiper` / `.swiper-container` inside the newly-active panel, calls `swiper.update()`, removes `inert` / `aria-hidden` / `pointer-events: none` from each slide, re-runs `slideTo(activeIndex, 0, false)`, and forces a paint (`el.offsetHeight` read).
- **Cross-widget reflow:** Isotope reflows for Post_Grid, Twitter_Feed, Filterable_Gallery, Instagram_Feed, premium-gallery (Pro). `eael.hooks.doAction("eventCalendar.reinit")` for Event Calendar.
- **Keyboard a11y:** Arrow Right / Arrow Left cycles tabs (modulo wrap-around); `tabindex` is shifted so only `.active` has `0`. Click handler also runs `.focus()` on the focused target.
- **Hash + scroll on init:** if hash present and `eael.elementStatusCheck('eaelAdvancedTabScroll')` is false, animate-scroll to the hashed tab (vertical layout uses `.eael-tabs-content` offset). Uses a hardcoded 300ms duration regardless of `data-scroll-speed`.
- **Custom ID `safari` rewrite:** matches PHP — `safari` → `eael-safari` for tab id, `eael-safari-tab` for content panel id.

## Common Issues

### A Swiper carousel inside a tab is broken / clipped on first open

- **Likely cause:** Swiper measured itself at zero width while the tab was hidden; `update()` never ran.
- **Diagnose:** open browser DevTools, switch tabs, inspect the swiper element — does it have `width: 0` or `transform: translate3d(0, 0, 0)` after activation?
- **Fix:** working as intended via `eaelFixSwiperHover()` — if it still breaks, the swiper instance may not be exposed on `element.swiper` (older Swiper versions, or custom init that doesn't attach to the DOM). Reach for the `ea-advanced-tabs-triggered` hook from the inner widget's JS to re-init manually.

### Scheduled active tab doesn't switch at the scheduled time

- **Likely cause:** scheduling is **server-time evaluated at render** ([line 1232](../../includes/Elements/Adv_Tabs.php#L1232)) — full-page caches freeze the result until cache expiry.
- **Diagnose:** disable page cache and reload at the scheduled time.
- **Fix:** set a short cache TTL on pages with scheduled tabs, or use a JS-side scheduler instead. There is no client-side re-evaluation.

### Pro Liquid Glass `glassey` style applied but no visible distortion

- **Likely cause:** the `<svg id="switcher">` filter is not in the document — Lite only emits it when `eael_adv_tab_new_style == 'glassey'` AND Pro is active. If you imported settings that set `glassey` while Pro was inactive, Lite still emits the SVG only at render time.
- **Diagnose:** view-source for `<filter id="switcher">`. CSS in DevTools → `.eael-tabs-nav` should have `filter: url(#switcher)` from Pro.
- **Fix:** save the widget once with Pro active. If multiple Adv_Tabs widgets use `glassey`, browsers resolve `url(#switcher)` to the first SVG only — visual is consistent but ID is duplicated.

### Hash deep-link `#safari` doesn't match a tab id

- **Likely cause:** the literal `safari` is rewritten to `eael-safari` (and content to `eael-safari-tab`) in both PHP and JS. The original `safari` string never appears as an `id`.
- **Diagnose:** inspect the rendered tab `<li>` `id` attribute.
- **Fix:** use a different Custom ID. The collision exists because `safari` is also used as a body class by browser-detection scripts.

### Caret toggle disabled but caret still visible (or vice versa)

- **Likely cause:** the control `eael_adv_tabs_tab_caret_show` is **inverted** at the class level — turning it OFF adds the `active-caret-on` class which **hides** the caret. Conceptually the class name and the value are reversed.
- **Diagnose:** check whether the wrapper has the `active-caret-on` class.
- **Fix:** working as intended; behaviour matches the control label even if class names are misleading.

### Saved Template that is the same as the host page produces no output

- **Likely cause:** [line 1472](../../includes/Elements/Adv_Tabs.php#L1472) refuses to render when the template id matches the current page id or any of its revisions — prevents infinite recursion.
- **Diagnose:** check the rendered output for the explicit message: "The provided Template matches the current page or one of its revisions!".
- **Fix:** pick a different template, or duplicate the template into a separate library entry.

## Known Limitations

- **`<filter id="switcher">` is duplicated when multiple Adv_Tabs use `glassey`** — emitted inline per widget at [line 1426-1434](../../includes/Elements/Adv_Tabs.php#L1426). Browsers resolve `filter: url(#switcher)` to the first match, so the visual is consistent, but valid-HTML linters will flag duplicate `id`s.
- **Scheduled active tab is not client-side reactive** — no JS schedule check, no countdown. Page must be re-rendered to update. Caches freeze the result.
- **Hardcoded 300ms scroll duration on hash-init scroll** ([JS line 277](../../src/js/view/advanced-tabs.js#L277)) — ignores `eael_adv_tabs_scroll_speed`. Per-click scroll honours the control; init scroll does not.
- **Three un-prefixed legacy hooks** — `eael_adv_tab_styles`, `eael_adv_tab_liquid_glass_effect_tab_bar`, `eael_adv_tab_glassey_class` predate the `eael/<context>/<action>` naming convention. Renaming requires dual-emit migration to avoid breaking Pro.
- **Inverted-naming controls** — `eael_adv_tabs_tab_caret_show` toggle ON adds no class; OFF adds `active-caret-on`. `responsive_vertical_layout` toggle ON adds no class; OFF adds `responsive-vertical-layout`. Both classes' names sound positive but are negative gates. Documented but actively confusing.
- **`window.eaelPreventResizeOnClick` is process-global** — set true on every tab click, never reset to false (same leaked global as Adv_Accordion).
- **Cross-widget reflow list is hardcoded** ([JS line 212-217](../../src/js/view/advanced-tabs.js#L212)) — adding a new EA widget that requires reflow on tab activation needs a JS edit to this file. No public registration API.
- **Content type 'image' alt text reads attachment meta inline** at [line 1391](../../includes/Elements/Adv_Tabs.php#L1391) via `get_post_meta(…, '_wp_attachment_image_alt', true)` — N+1 query risk if many tabs use images. Cache layers smooth this out.
- **`role="tabpanel"` is missing on content panels** — `<div class="eael-tab-content-item">` has no `role` attribute. Screen readers rely on `aria-labelledby` which is also not set on the panel — a11y improvement vs Adv_Accordion is in the **header**, not the panel.
- **Cosmetic typo** — variable `$tab_tpggle` at [line 1295](../../includes/Elements/Adv_Tabs.php#L1295) (should be `$tab_toggle`); never referenced outside this scope, harmless.
