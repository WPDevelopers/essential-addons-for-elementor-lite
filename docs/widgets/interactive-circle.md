# Interactive Circle Widget

> A radial menu/timeline that arranges up to eight Repeater-driven items around a circle (or half-circle for Preset 2); clicking or hovering an item swaps the centre-content panel. Four presets are pure CSS via a 6522-line SCSS file (largest stylesheet in Lite); the 116-line JS only handles event binding, scroll-triggered appearance animations via Waypoints, and a setInterval-driven autoplay. **Zero Pro extension hooks** and no `eael_section_pro` upsell — pure Lite-only widget.

**Class file:** [`includes/Elements/Interactive_Circle.php`](../../includes/Elements/Interactive_Circle.php)
**Slug:** `interactive-circle` (widget id `eael-interactive-circle`)
**Public docs:** <https://essential-addons.com/elementor/docs/interactive-circle/>
**Pro-shared:** ❌ No — Lite-only widget. **No `do_action` / `apply_filters` extension hooks at all** (zero Pro extension surface) and the `eael_section_pro` upsell panel is absent. Pro doesn't reference this widget.

---

## Overview

Interactive Circle renders a circular layout with up to eight items spaced around its circumference; the centre is a content panel that swaps when an item is activated (click or hover). Four hard-coded presets shape the visual: Preset 1 is a full circle with icons at radial positions, Preset 2 is a half-circle (height = width/2), Preset 3 and Preset 4 add connector shapes (two divs `.eael-shape-1` / `.eael-shape-2`) between items and the centre. The widget is overwhelmingly CSS — 6522 lines of SCSS handle item positioning per preset per item-count (1 through 8), rotation animations, the bounce-in / rotate / spinning entry animations triggered by Waypoints on scroll-into-view, and a "desktop view for mobile" override. JS is minimal: event binding (mouseenter or click), `setInterval` autoplay with a 5-second pause-after-user-interaction window, and keyboard activation on Tab + Space.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All 4 presets (full circle, half circle, connectors) | ✅ | ✅ |
| Click or Hover activation | ✅ | ✅ |
| Autoplay + interval (Preset 1 + 2 only) | ✅ | ✅ |
| Continuous rotation animation + pause-on-hover | ✅ | ✅ |
| Scroll-triggered entry animations (Waypoints) — None / Bounce In / Rotate / Spinning | ✅ | ✅ |
| Per-item icon, short title, content WYSIWYG, optional link | ✅ | ✅ |
| Per-item background (gradient / classic) | ✅ | ✅ |
| Desktop view for mobile toggle | ✅ | ✅ |
| Keyboard activation (Tab / Space) | ✅ | ✅ |
| FA4 → FA5 icon migration shim | ❌ — uses `Icons_Manager::render_icon()` without the FA4 compat field (no `fa4compatibility` on icon controls) | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Pro extension hooks | — | ❌ — no extension surface |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Interactive_Circle.php`](../../includes/Elements/Interactive_Circle.php) | PHP widget class (1235 lines) — controls split across six `eael_interactive_circle_*()` methods, `render()` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `eael_allowed_tags()`, `eael_e_optimized_markup()` |
| [`src/css/view/interactive-circle.scss`](../../src/css/view/interactive-circle.scss) | Source styles (**6522 lines** — largest single SCSS file in Lite) — per-preset radial positioning math for 1-to-8 items, rotation animations, RTL |
| [`src/js/view/interactive-circle.js`](../../src/js/view/interactive-circle.js) | Frontend logic (116 lines) — event binding, Waypoints viewport entry, autoplay with `setInterval`, keyboard activation |
| [`config.php`](../../config.php#L1243) entry `'interactive-circle'` | `Asset_Builder` dependency declaration: `interactive-circle.min.css` + `waypoints.min.js` + `interactive-circle.min.js` |
| `assets/front-end/js/lib-view/waypoint/waypoints.min.js` | Vendor — Waypoints (scroll-into-view trigger for entry animations) |
| `assets/front-end/css/view/interactive-circle.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/interactive-circle.min.js` | Built output (do not edit) |

## Architecture

- **Pure-CSS preset positioning, 1-to-8 item math precomputed** — the 6522-line SCSS file enumerates radial coordinates for each item count from 1 to 8 across all four presets. Adding a ninth item would break the layout — a panel-level RAW_HTML warning at [line 159-163](../../includes/Elements/Interactive_Circle.php#L159) reads "Circle Item limit max 8". No PHP enforcement; the user can add more, but the layout collapses.
- **Two render branches: Preset 2 vs everything else** — Preset 2 (half-circle) has its own foreach loop at [line 1156-1231](../../includes/Elements/Interactive_Circle.php#L1156) emitting slightly different markup (no class-based icon-shapes container around shape divs, content gets `.eael-circle-content-icon` for the per-content icon). Preset 1/3/4 share the branch at [line 1081-1155](../../includes/Elements/Interactive_Circle.php#L1081). Both branches duplicate the link-handling logic (`<a>` wrap when `btn_link_on == 'yes'`).
- **Waypoints-driven entry animation** — when animation is not "None", JS at [line 28-42](../../src/js/view/interactive-circle.js#L28) binds a Waypoint to `.eael-circle-content` with `offset: "80%"` and `triggerOnce: true`. When the element scrolls into view (top 80% of viewport), the animation class is added to `.eael-circle-wrapper`. CSS keyframes handle the actual motion. Lite uses Waypoints, not the IntersectionObserver pattern used by newer widgets (e.g., Progress_Bar).
- **`$('body').scroll(…)` is a likely-dead bug branch** — [JS line 22-26](../../src/js/view/interactive-circle.js#L22) binds a scroll listener to `body`, but in standard pages the scroll event fires on `window`, not `body` (unless `body` is an explicit overflow container). Probably a bug — was likely meant to be `$(window).scroll(…)`. The intent is to retrigger `window.resize` when the circle enters viewport, presumably to recalculate radial CSS. Currently never fires on typical pages.
- **Multi-active-default handling keeps only the last** — [JS line 12-15](../../src/js/view/interactive-circle.js#L12) — if more than one item has `eael_interactive_circle_default_active == 'yes'`, all but the last are demoted. The last "wins" silently. No console warning.
- **Autoplay pauses 5 seconds after any user click** — `setInterval` runs every `autoplay-interval` ms ([JS line 64-74](../../src/js/view/interactive-circle.js#L64)). On user interaction, `$autoplayPause = 1` is set in `handleEvent` (detected via `event.originalEvent` — synthetic JS triggers don't carry this property). The interval then defers via a nested `setTimeout` of 5000ms ([line 67](../../src/js/view/interactive-circle.js#L67)) before the next tick. **No interval cleanup** — `setInterval` is never `clearInterval`'d, so re-init of the widget (AJAX-rebuilt regions) leaks timers.
- **Custom event name has a typo** — `eaelInteractiveCicle` (missing the `r` in "Circle") is the synthetic event used by autoplay to advance through items ([JS line 61, 85](../../src/js/view/interactive-circle.js#L61)). Saved into both the listener and the triggering side, so it's self-consistent — but external code expecting `eaelInteractiveCircle` wouldn't bind.
- **`$autoplayPause` differentiates user vs synthetic events via `event.originalEvent`** — `handleEvent` reads `event.originalEvent ? 1 : 0` ([JS line 107](../../src/js/view/interactive-circle.js#L107)). Native DOM events have `originalEvent`; jQuery `.trigger()` of a custom event name does not. Clever but fragile — a custom event with `triggerHandler` or an event-object passed explicitly would break the heuristic.
- **Keyboard activation triggers the mouse event** — Tab (keycode 9) or Space (keycode 32) on `.eael-circle-btn` fires whichever event type is configured (`click` or `mouseenter`) via `$(this).trigger($eventType)` ([JS line 53-57](../../src/js/view/interactive-circle.js#L53)). Decent a11y, but keycode-based (deprecated `e.which`); newer `e.key === ' '` would be more reliable.
- **No Pro extension surface** — zero `do_action`, zero `apply_filters` (other than the conventional pattern of NOT consuming any in render). No `eael_section_pro` upsell either. Unusually self-contained for an EA widget.
- **`is_dynamic_content()` returns `false`** — render-cache is always active. Items use `wp_kses(Helper::eael_allowed_tags())` filtering on WYSIWYG content; no Saved Template option to disable the cache for.

## Render Output

```html
<div id="eael-interactive-circle-<widget-id>"
     class="eael-interactive-circle"
     data-tabid="<widget-id>">

  <div class="eael-circle-wrapper
              eael-interactive-circle-preset-1 [or -2/-3/-4]
              eael-interactive-circle-event-click [or -hover]
              [eael-interactive-circle-rotate]            ← when rotation == 'yes'
              [pause-rotate]                              ← when rotation_hover == 'yes'
              [eael-circle-desktop-view | eael-circle-responsive-view]
              [eael-interactive-circle-animation-1/2/3]"  ← added by JS via Waypoint
       data-appearance="eael-interactive-circle-animation-0|1|2|3"
       data-autoplay="0|1"
       data-autoplay-interval="2000">

    <div class="eael-circle-info" data-items="6">       ← also set on .eael-circle-inner for preset-2
      <div class="eael-circle-inner">

        <!-- Per Repeater item: -->
        <div class="eael-circle-item elementor-repeater-item-<repeater-id>">

          <div id="eael-circle-item-1"
               aria-controls="eael-interactive-1"
               tabindex="0"
               class="eael-circle-btn [active]">

            [?] <!-- Preset 3 / 4 only: connector shapes -->
            <div class="eael-circle-icon-shapes [classic]">
              <div class="eael-shape-1"></div>
              <div class="eael-shape-2"></div>
            </div>

            [?] <a href="…" [target="_blank"] [rel="…"]>      ← when btn_link_on == 'yes' AND link.url not empty
              <div class="eael-circle-btn-icon [classic]">
                <div class="eael-circle-icon-inner">
                  [?] <i class="fa-…"></i>     ← when btn_icon_show == 'yes'
                  [?] <span class="eael-circle-btn-txt">Item 1</span>  ← when btn_text_show == 'yes'
                </div>
              </div>
            </a>
            <!-- OR: same .eael-circle-btn-icon block without <a> wrapper -->
          </div>

          <div id="eael-interactive-1"
               aria-labelledby="eael-circle-item-1"
               class="eael-circle-btn-content eael-circle-item-1 [active]">
            <div class="eael-circle-content">
              [?] <div class="eael-circle-content-icon">    ← Preset 2 only, when content_icon_show == 'yes'
                <i class="fa-…"></i>
              </div>
              {wp_kses-filtered content}
            </div>
          </div>
        </div>
        …
      </div>
    </div>
  </div>
</div>
```

Notes:

- Preset 2's content panel id is `eael-interactive<n>` (without dash) at [line 1216](../../includes/Elements/Interactive_Circle.php#L1216), but other presets use `eael-interactive-<n>` (with dash) at [line 1141](../../includes/Elements/Interactive_Circle.php#L1141). The `aria-controls` on the button always points to `eael-interactive-<n>` (with dash) regardless of preset — so on Preset 2 the `aria-controls` target doesn't actually exist. ⚠ A11y bug.
- The `data-items` attribute is set on `.eael-circle-info` for presets 1/3/4 but on `.eael-circle-inner` for Preset 2 (cosmetic split, drives no JS).
- Per-item background gradient/classic is implemented via a Repeater Group_Control_Background hooked into a per-item selector with `{{CURRENT_ITEM}}` macro — same item gets different selector depending on whether preset is `-4` or not ([line 287-289](../../includes/Elements/Interactive_Circle.php#L287)).
- "Reload needed on first change" notice appears under the per-item background control ([line 292-300](../../includes/Elements/Interactive_Circle.php#L292)) — a hint that the dynamic style emission has a known timing quirk in the editor.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Interactive_Circle.php#L1022) calls six private methods. This table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_interactive_circle_preset` | SELECT | `eael-interactive-circle-preset-1` | Content → General | Top-level preset: 1 / 2 / 3 / 4 |
| `eael_interactive_circle_btn_icon_show` / `_btn_text_show` | SWITCHER | `yes` / `yes` | Content → General | Per-item icon + title visibility globally |
| `eael_interactive_circle_content_icon_show` | SWITCHER | empty | Content → General | Preset 2 only — show per-item content icon |
| `eael_global_warning_text` | RAW_HTML | — | Content → Content | "Circle Item limit max 8" warning |
| `eael_interactive_circle_item` | REPEATER | 6 default rows | Content → Content | Per-item settings (see sub-table) |
| `eael_interactive_circle_rotation` | SWITCHER | empty | Content → Additional Settings | Continuous CSS rotation animation (Preset 2 excluded) |
| `eael_interactive_circle_rotation_speed` | SLIDER (s) | `50` | Content → Additional Settings | Animation duration; applied via `selectors` |
| `eael_interactive_circle_rotation_hover` | SWITCHER | empty | Content → Additional Settings | Pause rotation on hover (adds `pause-rotate` class) |
| `eael_interactive_circle_event` | SELECT | `eael-interactive-circle-event-click` | Content → Additional Settings | `click` (default) or `hover` — JS reads from wrapper class |
| `eael_interactive_circle_animation` | SELECT | `eael-interactive-circle-animation-0` | Content → Additional Settings | Entry animation: None / Bounce In / Rotate / Spinning |
| `eael_interactive_circle_autoplay` | SWITCHER | empty | Content → Additional Settings | Autoplay (Preset 1 + 2 only) |
| `eael_interactive_circle_autoplay_interval` | SLIDER (px units, ms semantics) | `2000` | Content → Additional Settings | Cycle interval — note `size_units: ['px']` but value treated as ms in JS |
| `eael_interactive_circle_width` | SLIDER (px, responsive) | desktop / tablet only | Style → General | Circle diameter; Preset 2 uses `height: calc(SIZE/2)` |
| `eael_interactive_circle_padding` / `_margin` | DIMENSIONS | empty | Style → General | Circle inner padding / margin |
| `eael_interactive_circle_border` (group) / `_border_color` | GROUP / COLOR | empty | Style → General | Circle border styling |
| `eael_interactive_circle_connector_color` | COLOR | empty | Style → General | Preset 3 connector colour (`.eael-shape-1`, `.eael-shape-2`) |
| `eael_interactive_circle_desktop_view` | SWITCHER | empty | Style → General | When `yes`, preserves desktop circle layout on mobile (`eael-circle-desktop-view`) instead of vertical stack (`eael-circle-responsive-view`) |
| Style → Button / Content | various | — | Style tab | Icon size + colour, text typography, content padding + background, hover states |

### Per-item Repeater controls (`eael_interactive_circle_item`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_interactive_circle_default_active` | SWITCHER | empty | Adds `active` class — only LAST `yes` row wins (JS demotes earlier defaults) |
| `eael_interactive_circle_btn_icon` | ICONS | `fas fa-home` | Per-item icon. ⚠ No `fa4compatibility` field — FA4 strings won't migrate. |
| `eael_interactive_circle_btn_title` | TEXT (dynamic, AI) | `Title` | Short label inside the circle button |
| `eael_interactive_circle_btn_link_on` | SWITCHER | empty | Wrap button in `<a>` |
| `eael_interactive_circle_btn_link_mess` | RAW_HTML | — | Notice: link only works with Hover event |
| `eael_interactive_circle_btn_link` | URL (dynamic) | `#` | Item link; supports Post Meta + URL dynamic tag categories |
| `eael_interactive_circle_content_icon` | ICONS | empty | Preset 2 content-area icon |
| `eael_interactive_circle_item_content` | WYSIWYG (dynamic) | placeholder | Tab body; `wp_kses(Helper::eael_allowed_tags())`-filtered |
| `eael_interactive_circle_tab_bgtype` (group, gradient + classic) | GROUP | empty | Per-item background; selector uses `{{CURRENT_ITEM}}` for repeater-scoped CSS |
| `eael_interactive_circle_tab_bgtype_classic_notice` | RAW_HTML | — | Reload-needed notice on first background change |

## Conditional Dependencies

```text
# Preset-driven
eael_interactive_circle_content_settings (heading)  → visible when preset == preset-2
eael_interactive_circle_content_icon_show           → same
eael_interactive_circle_rotation                    → visible when preset != preset-2
eael_interactive_circle_autoplay                    → visible when preset in [preset-1, preset-2]
eael_interactive_circle_connectors (heading)        → visible when preset == preset-3
eael_interactive_circle_connector_color             → same

# Rotation gates
eael_interactive_circle_rotation_speed              → visible when rotation == 'yes'
eael_interactive_circle_rotation_hover              → same

# Autoplay gate
eael_interactive_circle_autoplay_interval           → visible when autoplay == 'yes'

# Mouse event: complex OR conditions
eael_interactive_circle_event                       → visible when autoplay != 'yes'
                                                       OR preset == preset-3
                                                       OR preset == preset-4

# Repeater per-item
eael_interactive_circle_btn_link / _btn_link_mess   → visible when btn_link_on == 'yes'
eael_interactive_circle_tab_bgtype_classic_notice   → visible when bgtype_background == 'classic'
                                                       AND bgtype_color == ''
```

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides only. **No `eael_section_pro` upsell panel either** — unique among Interactive-category widgets.

JS-side custom events / globals:

- `eaelInteractiveCicle` — ⚠ misspelled custom event (missing `r` in "Circle"). Used internally by the autoplay tick to advance to the next item via `$(elem).trigger('eaelInteractiveCicle')`. External code wanting to hook in must match the misspelling.
- No `window.X` global. No `eael.hooks.doAction(…)` broadcasts. Does NOT subscribe to cross-widget reflow events.

## JavaScript Lifecycle

- **Trigger:** `eael.hooks.addAction("init", "ea", () => elementorFrontend.hooks.addAction('frontend/element_ready/eael-interactive-circle.default', interactiveCircle))` — newer EA registration pattern.
- **Guard:** none — no `elementStatusCheck` flag.
- **Vendor dependency:** Waypoints (scroll-into-view trigger for entry animations).
- **Reads on init:** `data-appearance`, `data-autoplay`, `data-autoplay-interval` from `.eael-circle-wrapper`. Wrapper classes (`eael-interactive-circle-event-click` vs `-hover`) determine the trigger event type.
- **Branches:**
  - if more than one `.eael-circle-btn.active` — demote all but the last.
  - if `data-appearance` is not `eael-interactive-circle-animation-0` — bind a Waypoint on `.eael-circle-content` to add the animation class when in viewport (`offset: "80%", triggerOnce: true`). Also binds the suspicious `body.scroll` listener.
  - if wrapper has `eael-interactive-circle-event-click` class — bind click; otherwise mouseenter.
  - if `data-autoplay == 1` — start `setInterval` (cycles items via misspelled `eaelInteractiveCicle` event).
- **Activation logic** (`handleEvent`): if clicked button is not already active, remove `.active` from all `.eael-circle-btn` and matching `.eael-circle-btn-content`, then set this button + matching content active (matched by id-on-content equal to button's id).
- **Keyboard a11y:** Tab (keycode 9) and Space (keycode 32) on `.eael-circle-btn` triggers the configured event. Uses deprecated `e.which`.
- **Autoplay pause:** `$autoplayPause = event.originalEvent ? 1 : 0` in `handleEvent` — distinguishes user-initiated DOM events from synthetic jQuery triggers. When paused, next interval tick delays 5000ms via nested `setTimeout` before advancing.
- **No `clearInterval`:** the autoplay timer never gets cleaned up. Re-fires of `frontend/element_ready` (e.g., editor preview rebuild) start additional intervals that all keep running.

## Common Issues

### More than 8 items breaks the visual layout

- **Likely cause:** SCSS only encodes radial coordinates for 1-to-8 items. The 9th+ item has no positioning rule and falls back to default block flow.
- **Diagnose:** look at the wrapper — items 9+ stack at top-left, overlap.
- **Fix:** working as designed; the panel shows a "Circle Item limit max 8" warning ([line 159-163](../../includes/Elements/Interactive_Circle.php#L159)). Reduce to 8 or fewer items, or fork the SCSS to add coordinates for higher counts.

### Multiple "Active as Default" items but only one becomes active

- **Likely cause:** JS at [JS line 12-15](../../src/js/view/interactive-circle.js#L12) demotes all but the last `.eael-circle-btn.active`. Silently.
- **Diagnose:** browser DevTools — only the last default-active item has the `active` class after init.
- **Fix:** working as designed; pick a single default-active row.

### Autoplay never stops / accumulates after editor changes

- **Likely cause:** `setInterval` is never cleared. Each `frontend/element_ready` re-fire spawns another interval. In the Elementor editor, every panel change re-renders the widget, leaking intervals.
- **Diagnose:** browser performance profiler — interval ticks per second grows.
- **Fix:** workaround — refresh the editor preview. Permanent fix needs a `clearInterval` call on widget destroy / re-init.

### Click event activates a different item than expected (link wraps button)

- **Likely cause:** when `Item Link` is enabled AND a URL is set, the button content is wrapped in an `<a href>`. The `<a>` swallows the click and navigates instead of activating the panel.
- **Diagnose:** check the panel warning at [line 220-227](../../includes/Elements/Interactive_Circle.php#L220) — "To view detailed content, choose Hover event when using links".
- **Fix:** set Mouse Event to Hover (or remove the link). With click event, link wins.

### Aria-controls points to nowhere on Preset 2

- **Likely cause:** Preset 2's content id is `eael-interactive<n>` (no dash) at [line 1216](../../includes/Elements/Interactive_Circle.php#L1216), but the button's `aria-controls` always says `eael-interactive-<n>` (with dash) at [line 1167](../../includes/Elements/Interactive_Circle.php#L1167). Screen reader follows aria-controls to an element that doesn't exist.
- **Diagnose:** inspect Preset 2 markup — content `id` doesn't match button's `aria-controls`.
- **Fix:** a11y bug; needs aria-controls patched or content id corrected.

### Per-item background gradient/classic doesn't apply on first change

- **Likely cause:** known editor-rendering quirk — `Group_Control_Background` with `'classic'` type and empty color requires a reload to take effect on first change.
- **Diagnose:** see the editor notice at [line 292-300](../../includes/Elements/Interactive_Circle.php#L292).
- **Fix:** save and reload the editor; subsequent changes work immediately.

### Entry animation never plays on a short page

- **Likely cause:** Waypoint with `offset: "80%"` requires the element to scroll into the top 80% of the viewport. On a short page where the widget is already in view at page load, scroll never moves and the waypoint doesn't fire.
- **Diagnose:** scroll up and back down; if it plays then, the page wasn't long enough.
- **Fix:** Waypoints library doesn't auto-trigger on already-visible elements. Workaround: pick `Animation: None` if the widget is above-the-fold.

## Known Limitations

- **`$('body').scroll(…)` listener is likely dead code** ([JS line 22-26](../../src/js/view/interactive-circle.js#L22)) — scroll events fire on `window` not `body` on standard pages. The intent (re-trigger `window.resize` when the widget enters viewport) never executes. Cosmetic bug — animation works because the Waypoint at [line 28-42](../../src/js/view/interactive-circle.js#L28) handles it independently.
- **Typo in custom event name** — `eaelInteractiveCicle` (missing `r`). Stable for backwards compat; external listeners must match.
- **`setInterval` leak** — autoplay timer is never cleared. Multiple re-inits accumulate timers; over a long editor session this can degrade performance.
- **No `fa4compatibility` on icon controls** — `eael_interactive_circle_btn_icon`, `_content_icon` use `Controls_Manager::ICONS` without an FA4 compat field. Users importing widgets from older EA versions with FA4 strings won't get the auto-migration; see [`_patterns.md § FA4`](_patterns.md#fa4--fa5-icon-migration-shim).
- **Aria-controls mismatch on Preset 2** — content panel id is `eael-interactive<n>` but button claims `aria-controls="eael-interactive-<n>"` (with dash). Hard a11y bug; affects screen reader navigation only on Preset 2.
- **Hardcoded 8-item ceiling** — neither the SCSS nor the PHP renders sanely above 8 items. Only a panel warning (no `add_control` validation).
- **No cross-widget reflow listeners** — Interactive Circle inside a tab / accordion / lightbox does not re-layout when activated. Combined with the `body.scroll` bug, animations may not retrigger in hidden containers.
- **`autoplay_interval` SLIDER uses `size_units: ['px']` but value is milliseconds** ([line 499](../../includes/Elements/Interactive_Circle.php#L499)) — cosmetic only; panel shows "px" suffix on a millisecond value.
- **Deprecated `e.which` for keyboard activation** ([JS line 54](../../src/js/view/interactive-circle.js#L54)) — `e.which` is deprecated in favour of `e.key`. Still works in current browsers.
- **No `is_dynamic_content()` override = always cached** — saved-content edits go through Elementor's render cache. Static content widget, but dynamic tag expansion inside item WYSIWYG won't bypass cache.
- **6522-line SCSS** — by far the largest stylesheet in Lite; per-preset-per-item-count math is hard-coded rather than computed in PHP (cf. Feature_List's PHP-computed connector geometry). Visual changes to a single preset can ripple through dozens of selectors.
