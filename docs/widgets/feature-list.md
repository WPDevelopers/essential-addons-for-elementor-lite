# Feature List Widget

> A vertical or horizontal list of features, each with an icon (or image), title, content, and optional link. Optional connector line (classic or modern style) draws between items. Pure-CSS layout with responsive icon position and connector placement computed inline per breakpoint.

**Class file:** [`includes/Elements/Feature_List.php`](../../includes/Elements/Feature_List.php)
**Slug:** `feature-list` (widget id `eael-feature-list`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-feature-list/>
**Pro-shared:** ❌ — Lite-only widget. The class emits **zero** `do_action()` / `apply_filters()` hooks and contains **no** `eael/pro_enabled` upsell section. Pro neither extends nor references it.

---

## Overview

Feature List renders an ordered series of feature blocks — each with a circular / square / rhombus icon (or image) plus a title and a body paragraph. The layout is vertical by default, with three icon positions (left, top, right) and an optional connector line drawn between consecutive items to visualise a sequence or process. The connector has two visual variants (classic and modern) and three line styles (solid, dashed, dotted).

The widget is intentionally pure CSS — no JavaScript ships. All layout switching (vertical / horizontal per breakpoint, icon position per breakpoint, shape, framed vs stacked icon, connector geometry) happens via class composition in `render()` and corresponding SCSS rules. The connector's inline position offset is computed in PHP from the icon shape and connector width, then written as `style="…"` on each connector `<span>` so the line lands exactly under or beside the icon at every viewport.

## Features

- Repeater of feature items — each with icon type (icon or image), title, content, and optional link
- Per-item icon (with FA4 legacy shim) or image
- Per-item icon-style override (individual icon colour, background, box background)
- Per-item link wraps both the icon anchor and the title
- Three icon shapes: circle, square, rhombus
- Two shape views: framed (icon container with border) or stacked (no frame)
- Icon position (responsive): left, top, or right
- Icon vertical position in horizontal layout: top, middle, bottom
- Layout (responsive): vertical or horizontal — different per desktop / tablet / mobile
- Connector line (vertical layouts only): classic or modern type; solid / dashed / dotted style; configurable colour and width
- Modern connector for `icon-position: top` (vertical) — connector appears between items as a horizontal segment plus arrow
- Configurable item width and gap (space-between)
- Title HTML tag selectable (`h1`–`h6`, `span`, `p`, `div`)
- Responsive icon position writes three classes on the outer wrapper (`-icon-position-…`, `-tablet-icon-position-…`, `-mobile-icon-position-…`)
- No Pro upsell — widget ships only Lite-side features

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All controls in the widget | ✅ | ✅ |
| Pro-only features | — | — |
| Pro upsell panel section | ❌ — none present | — |
| Filter or action hooks for Pro extension | ❌ — none emitted | — |

Feature List is the only widget in the Display category with **no Pro extension surface whatsoever** — no upsell panel, no `pro_enabled` gate, no `do_action` injection points. Pro does not reference it. Whatever ships in Lite is the entire widget.

## Use Cases

- "Why choose us" feature block on a landing page (icon + heading + paragraph per item)
- Process / step-by-step explanation with a connector line tracing the flow
- Pros-and-cons list with icons (e.g. check-mark for pros, X-mark for cons)
- Feature row in a horizontal layout across the page on desktop, stacking vertically on mobile
- Service offering list ("What we do") with linked icons leading to detail pages

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Feature_List.php`](../../includes/Elements/Feature_List.php) | PHP widget class — controls, render, connector geometry computation |
| [`src/css/view/feature-list.scss`](../../src/css/view/feature-list.scss) | Source styles — layouts, shapes, connector visuals, responsive breakpoints |
| [`config.php`](../../config.php#L557) entry `'feature-list'` | `Asset_Builder` dependency declaration (CSS only) |
| `assets/front-end/css/view/feature-list.min.css` | Built output (do not edit) |

No widget-specific JS source or compiled file exists.

## Architecture

- **Pure CSS widget, no JS** — the entire widget renders server-side and visual variation is handled by class composition. Hover effects, layout direction, shape, framed / stacked, and connector visibility all live in SCSS. The widget declares no JS dependency in [`config.php`](../../config.php#L557).
- **Empty `content_template()` method** — [`content_template() {}`](../../includes/Elements/Feature_List.php#L1102) is explicitly empty, which disables Elementor's JS-side preview template. Editor preview falls back to the server-side `render()` (slower but guaranteed to match production). Most EA widgets omit `content_template()` entirely; this widget deliberately stubs it.
- **Connector positioning computed in PHP, written as inline style** — `render()` computes `$connector_width = circle_size [+ rhombus margin] + connector_width` then emits `style="right: calc(100% - <N>px); left: 0;"` (or the right-side mirror) on each connector `<span>`. Three separate spans are emitted per item (desktop, tablet, mobile) so the inline style for each breakpoint can be independent. This avoids a viewport-aware JS calculation.
- **Three classes on the outer wrapper for responsive icon position** — `-icon-position-<desktop>`, `-tablet-icon-position-<tablet>`, `-mobile-icon-position-<mobile>` are stamped on the outer `<div>` so SCSS rules can target each breakpoint by class without needing media queries to read settings.
- **Responsive layout via `data-layout-*` attributes** — `data-layout-tablet` and `data-layout-mobile` carry the selected layout per breakpoint. SCSS rules match on attribute selectors (`[data-layout-tablet="horizontal"]`) so the widget can be horizontal on desktop but vertical on mobile (or any combination).
- **Modern vs classic connector — two completely different DOM strategies** — *Classic* uses the `<span class="connector">` element inline-positioned by PHP. *Modern* uses pseudo-elements (`:before` / `:after`) on each `.eael-feature-list-item` to draw the connector segment. The picker switches between them via `connector-type-classic` / `connector-type-modern` classes on the `<ul>`. The modern variant is also auto-applied when icon-position is `top` and connector is enabled, regardless of the picker value ([line 963](../../includes/Elements/Feature_List.php#L963)).
- **`__fa4_migrated` shim for the per-item icon** — `eael_feature_list_icon` (legacy FA4 string) and `eael_feature_list_icon_new` (Elementor `ICONS` control) coexist per item. Render picks the new picker when migrated or legacy field is empty ([line 1059](../../includes/Elements/Feature_List.php#L1059)). Same pattern as Cta_Box / Info_Box.

## Render Output

The widget produces the following DOM structure on the front end. Annotated for default config (vertical layout, icon-position left, no connector); conditional elements marked `[?]`.

```html
<div class="-icon-position-left -tablet-icon-position-left -mobile-icon-position-left">
  <ul id="eael-feature-list-<widget-id>"
      class="eael-feature-list-items circle stacked connector-type-classic
             eael-feature-list-vertical"
      data-layout-tablet="vertical"
      data-layout-mobile="vertical">
    <li class="eael-feature-list-item elementor-repeater-item-<id>">
      [?] <span class="connector"        style="right: calc(100% - 70px); left: 0;"></span>
      [?] <span class="connector connector-tablet" style="…"></span>
      [?] <span class="connector connector-mobile" style="…"></span>
      <div class="eael-feature-list-icon-box">
        <div class="eael-feature-list-icon-inner">
          <span class="eael-feature-list-icon fl-icon-0">
            <i class="fas fa-check" aria-hidden="true"></i>   <!-- icon -->
            OR
            <img class="eael-feature-list-img" src="…" alt="…"> <!-- image -->
          </span>
          <!-- when link is set, <span> becomes <a href="…"> -->
        </div>
      </div>
      <div class="eael-feature-list-content-box">
        <h2 class="eael-feature-list-title">
          [?] <a href="…">Feature title</a>
        </h2>
        <p class="eael-feature-list-content">Feature body paragraph…</p>
      </div>
    </li>
    …
  </ul>
</div>
```

Notes:

- The **outer wrapper** is a `<div>` with three `-icon-position-*` classes (one per breakpoint). The `<ul>` inside is the styling root.
- `<ul>` class list: `eael-feature-list-items` + shape (`circle` / `square` / `rhombus`) + shape view (`stacked` / `framed`) + connector type (`connector-type-classic` / `connector-type-modern`) + layout direction (`eael-feature-list-vertical` / `-horizontal`).
- When icon-position is `top` AND connector is `yes`, an additional `connector-type-modern` class is added regardless of the picker — modern is the only style that makes geometric sense for top-positioned icons.
- Three `<span class="connector">` elements per item — `connector` (desktop), `connector connector-tablet`, `connector connector-mobile`. Each carries an independent inline `style` so the offset matches the per-breakpoint icon position.
- Per-item icon wrapper is `<span>` by default; becomes `<a href="…">` when the item has a link.
- Title link is rendered as a nested `<a>` inside the heading — separate from the icon link, so a single item can have its title and icon linked to the same URL with two anchors.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Feature_List.php#L66) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_feature_list` | REPEATER | 3 default items | Content → Content Settings | Per-item icon / title / content / link |
| `eael_feature_list_layout` | SELECT (responsive) | `vertical` | Content → Content Settings | Outer class + `data-layout-*` attributes |
| `eael_feature_list_title_size` | SELECT | `h2` | Content → Content Settings | Title HTML element |
| `eael_feature_list_icon_shape` | SELECT | `circle` | Content → Content Settings | `<ul>` class (circle / square / rhombus); connector geometry |
| `eael_feature_list_icon_shape_view` | SELECT | `stacked` | Content → Content Settings | `<ul>` class (framed / stacked) |
| `eael_feature_list_icon_position` | CHOOSE (responsive) | `left` | Content → Content Settings | Outer wrapper class `-icon-position-…`, `-tablet-…`, `-mobile-…` |
| `eael_feature_list_icon_vertical_position` | CHOOSE | `start` | Content → Content Settings | `align-items` on `.eael-feature-list-item` (horizontal layout only) |
| `eael_feature_list_icon_right_indicator_position` | SLIDER (responsive) | `35px` | Content → Content Settings | `top` of modern connector `:after` (vertical + icon-on-top only) |
| `eael_feature_list_connector` | SWITCHER | `no` | Content → Content Settings | Renders `<span class="connector">` per item + adds `connector-type-modern` when needed |
| `eael_feature_list_auto_width` | SWITCHER | `no` | Style → List | Switches between fixed item width and auto-width (horizontal only) |
| `eael_feature_list_item_width` | SLIDER (responsive) | `40%` | Style → List | `width` on horizontal items (when not auto-width) |
| `eael_feature_list_space_between` | SLIDER (responsive) | `15` | Style → List | Gap between items + connector height calc |
| `eael_feature_list_connector_type` | SELECT | `connector-type-classic` | Style → List | `<ul>` class (classic / modern) |
| `eael_feature_list_connector_styles` | SELECT | `solid` | Style → List | `border-style` on `.connector` |
| `eael_feature_list_connector_color` | COLOR (global) | `#37368e` | Style → List | `border-color` on `.connector` |
| `eael_feature_list_connector_width` | SLIDER | `1px` | Style → List | `border-width` on `.connector`; factored into PHP's `$connector_width` |

### Per-item Repeater controls

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_feature_list_icon_type` | CHOOSE | `icon` | Whether to render an icon glyph or `<img>` |
| `eael_feature_list_icon_new` | ICONS | empty | Icon glyph (FA4 legacy via `eael_feature_list_icon`) |
| `eael_feature_list_icon_is_individual_style` | SWITCHER | off | Enables per-item icon style overrides |
| `eael_feature_list_icon_individual_color` / `_bg_color` / `_box_bg_color` | COLOR | various | Per-item icon colour, container bg, box bg (visible when individual style is on) |
| `eael_feature_list_img` | MEDIA | placeholder | Per-item image (image type only) |
| `eael_feature_list_title` | TEXT (dynamic) | `"Title"` | `.eael-feature-list-title` text |
| `eael_feature_list_content` | TEXTAREA (dynamic) | lorem ipsum | `.eael-feature-list-content` text |
| `eael_feature_list_link` | URL (dynamic) | empty | Wraps the icon anchor and the title in `<a>` |

Plus Style sections for Icon (size, padding, border, hover state), Content (typography for title and content), and Background sliders for the icon-inner box.

## Conditional Dependencies

```text
# Per-item Repeater
eael_feature_list_icon_new       → visible when eael_feature_list_icon_type == 'icon'
eael_feature_list_icon_is_individual_style
                                 → visible when eael_feature_list_icon_type == 'icon'
eael_feature_list_icon_individual_color / _bg_color / _box_bg_color
                                 → visible when eael_feature_list_icon_is_individual_style == 'on'
eael_feature_list_img            → visible when eael_feature_list_icon_type == 'image'

# Layout / connector
eael_feature_list_icon_vertical_position
                                 → visible when eael_feature_list_layout == 'horizontal'
                                   AND eael_feature_list_icon_position != 'top'
eael_feature_list_icon_right_indicator_position
                                 → visible when eael_feature_list_layout == 'vertical'
                                   AND eael_feature_list_icon_position == 'top'
eael_feature_list_connector      → visible when eael_feature_list_layout == 'vertical'
eael_feature_list_item_width     → visible when eael_feature_list_layout == 'horizontal'
                                   AND eael_feature_list_auto_width != 'yes'

eael_feature_list_connector_type → visible when eael_feature_list_connector == 'yes'
                                   AND eael_feature_list_icon_position != 'top'
eael_feature_list_connector_styles / _color / _width
                                 → visible when eael_feature_list_connector == 'yes'
```

## Behavior Flow

1. User drops the widget → `register_controls()` runs. No filter / action hooks are emitted.
2. User configures the Repeater with feature items, plus layout / icon position / connector settings.
3. Editor preview re-renders via [`render()`](../../includes/Elements/Feature_List.php#L941) (the empty `content_template()` disables the JS-side preview path).
4. `render()` computes responsive layouts: `$layout_desktop` / `$layout_tablet` / `$layout_mobile` with `tablet` falling back to desktop and `mobile` falling back to tablet.
5. `render()` derives `$connector_width` from `circle_size` (default 70px) + `connector_width` (default 1px), plus a 30px rhombus margin when shape is `rhombus`.
6. Per breakpoint, computes the connector inline style: when icon is on left, `right: calc(100% - <N>px); left: 0;`; when icon is on right, the mirror.
7. Three classes are stamped on the outer wrapper: `-icon-position-<desktop>`, `-tablet-icon-position-<tablet>`, `-mobile-icon-position-<mobile>`.
8. The `<ul>` gets the composed class list (`eael-feature-list-items`, shape, shape view, connector type, layout) plus `data-layout-tablet` / `data-layout-mobile` attributes.
9. For each item: emit three `<span class="connector">` (only when connector is enabled), then the icon box, then the content box.
10. Icon rendering: if `icon_type === 'icon'`, render the FA glyph (FA4 shim picks legacy or new field); if `icon_type === 'image'`, render `<img>`. Wrap in `<a>` when the item has a link.
11. Title rendering: emit the chosen HTML tag; if the item has a link, wrap the inner text in `<a>` (nested separately inside the heading, parallel to the icon `<a>`).
12. Browser receives static HTML. SCSS handles all visual variation via class and attribute selectors.

## JavaScript Lifecycle

N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`, and explicitly stubs `content_template()` to disable Elementor's JS-side preview.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one Feature List widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `feature-list.min.css` | self (built from `src/css/view/feature-list.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| _(none)_ | — | Widget is pure CSS — no JS asset registered for this slug | — |

Font Awesome glyphs render via `Icons_Manager::render_icon`, which depends on Elementor's `font-awesome-5-all` handle Elementor provides.

## Hooks & Filters

N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides only.

This is intentional: Feature List is a Lite-only widget with no Pro-side counterpart. The only customisation path is theme stylesheet overrides against the documented class names (see Customization Recipes).

## Customization Recipes

### Recipe 1 — Customise the connector dash pattern via theme CSS

```scss
.eael-feature-list-items.connector-type-classic .connector {
    border-style: dashed;
    border-image: repeating-linear-gradient(
        to bottom,
        #37368e 0 6px,
        transparent 6px 12px
    ) 1;
}
```

`border-image` lets you build a precise dash / gap pattern that the SCSS `dashed` style doesn't expose.

### Recipe 2 — Make the icon background a gradient on hover

```scss
.eael-feature-list-items .eael-feature-list-item:hover .eael-feature-list-icon-inner {
    background: linear-gradient(135deg, #07b4eb 0%, #37368e 100%);
    transition: background 0.3s ease-in-out;
}
```

The widget exposes only a flat colour control; gradient on hover is a theme-side addition.

### Recipe 3 — Force a different layout per page section via Custom CSS Class

```scss
.my-feature-row .eael-feature-list-items.eael-feature-list-vertical {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}
```

Set a Custom CSS Class of `my-feature-row` on a parent column to convert the rendered vertical list into a CSS Grid without touching the widget controls.

### Recipe 4 — Render the connector as an arrow with a custom marker

```scss
.eael-feature-list-items.connector-type-classic .eael-feature-list-item:not(:last-child) {
    position: relative;
}
.eael-feature-list-items.connector-type-classic .eael-feature-list-item:not(:last-child) .connector::after {
    content: "▼";
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    color: #37368e;
    font-size: 12px;
}
```

The classic connector is a plain border line; this recipe adds an arrowhead at the bottom of each line.

## Common Issues

### Connector line doesn't appear under the icon

- **Likely cause:** icon-position is `top` while connector type is `classic` — the picker's `connector-type-classic` value is auto-overridden to `connector-type-modern` in `render()` when icon is on top ([line 963](../../includes/Elements/Feature_List.php#L963)); the classic visual never renders for top-positioned icons
- **Diagnose:** inspect the `<ul>` class — does it have `connector-type-modern`?
- **Fix:** switch icon-position to left or right for classic connector; or accept the modern style for top icons

### Connector line is off-centre from the icon

- **Likely cause:** `eael_feature_list_icon_circle_size` default is 70px but the user changed the icon size — `render()` uses `circle_size` to compute `connector_width`, so changing the icon SIZE (font-size) without updating the CIRCLE size leaves the connector pinned to the original geometry
- **Diagnose:** in DevTools inspect a connector `<span>` — `right: calc(100% - 70px)` or similar
- **Fix:** update Style → Icon → Circle Size to match the icon's visible diameter; or override `right` / `left` inline via theme CSS

### Per-item icon colour override has no effect

- **Likely cause:** the `eael_feature_list_icon_is_individual_style` switch is off — the individual colour controls are conditional on it
- **Diagnose:** check the Repeater item — is "Icon Style" toggle on?
- **Fix:** turn the switch on; the override uses `{{CURRENT_ITEM}}` selectors, so saved per-item colours only apply when the switch is on

### Image icon shows the placeholder

- **Likely cause:** the user switched icon type to `image` but the media field still has the placeholder URL — no real image was picked
- **Diagnose:** open the Repeater item; does the Image field have a real attachment?
- **Fix:** pick a real image

### Horizontal layout looks broken on mobile

- **Likely cause:** the responsive layout SELECT defaults to `vertical` for tablet and mobile only if the desktop value is `vertical`. If the user sets desktop to `horizontal` without setting tablet / mobile, the widget falls back: `tablet` to desktop, `mobile` to tablet — so all three become horizontal
- **Diagnose:** check the responsive layout settings; what does each breakpoint show?
- **Fix:** explicitly set tablet and mobile to `vertical` if you want horizontal-on-desktop-only

### Modern connector segments don't align between items

- **Likely cause:** the modern connector uses `:before` / `:after` pseudo-elements on each item; their `height` calc depends on `space_between` value. Custom margin / padding on the item via Style controls can shift the geometry
- **Diagnose:** in DevTools inspect the pseudo-element's computed `top` and `height`
- **Fix:** adjust `space_between` or set explicit `margin: 0` on `.eael-feature-list-item` via theme CSS

### Linked title and linked icon point to different URLs

- **Likely cause:** by design — the item has a single `eael_feature_list_link` URL; both the icon `<a>` (line 1041) and the title nested `<a>` (line 1085) use the same `add_link_attributes` source. If the URLs differ, the widget data has been edited outside the panel
- **Diagnose:** export the widget JSON; both anchors share the same URL field
- **Fix:** re-save the link in the panel; or remove the link from the item

### Editor preview is slow after enabling many items

- **Likely cause:** the empty `content_template()` ([line 1102](../../includes/Elements/Feature_List.php#L1102)) routes every preview render through PHP `render()` via an AJAX call, instead of letting Elementor's JS Marionette template render client-side
- **Diagnose:** Network tab in the editor — every settings change triggers a `wp-admin/admin-ajax.php` round-trip
- **Fix:** by design — server-side preview is more accurate than the JS template would be; if performance is a real issue, file a feature request for a JS template

## Testing Checklist

- [ ] Drop at default — three items render vertically with check / X / anchor icons; no PHP notices
- [ ] Switch layout to Horizontal — items render side-by-side; `eael-feature-list-horizontal` class on `<ul>`
- [ ] Per breakpoint: desktop horizontal, tablet vertical, mobile vertical — `data-layout-tablet="vertical"` and `data-layout-mobile="vertical"`; CSS swaps direction
- [ ] Switch icon shape to circle / square / rhombus — `<ul>` class updates; SCSS applies the corresponding border-radius and aspect-ratio
- [ ] Switch shape view between framed and stacked — outer container border / no-border applies correctly
- [ ] Switch icon position to left / top / right per breakpoint — `-icon-position-…` wrapper classes update; SCSS arranges
- [ ] Enable connector with classic type — `<span class="connector">` appears; inline style positions it under the icon
- [ ] Switch connector type to modern — `:before` / `:after` pseudo-elements render the connector segments instead
- [ ] Switch icon position to top with connector enabled — `connector-type-modern` class auto-applied regardless of picker
- [ ] Connector solid / dashed / dotted — `border-style` switches on the connector
- [ ] Per-item Icon Style on with custom colour — `{{CURRENT_ITEM}}` selector applies the override on only that item
- [ ] Add a link to one item — both the icon wrapper and the title text become `<a>` pointing to the same URL
- [ ] Switch one item's icon type to image — `<img>` renders instead of `<i>` / SVG
- [ ] FA4 legacy icon (`eael_feature_list_icon` string + `__fa4_migrated` flag) — render picks the new picker
- [ ] Empty Repeater — `<ul>` renders empty; no PHP notices
- [ ] Special characters in title or content — output sanitised via `Helper::eael_allowed_tags`; no XSS
- [ ] RTL site — connector inline style `left` ↔ `right` swap is in SCSS (`body.rtl` selector); visual mirroring works
- [ ] Editor — every settings change triggers a server round-trip (the empty `content_template()` design)
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### No Pro extension surface

- **Context:** every other Display widget exposes at least an `eael_section_pro` upsell panel and an `eael/pro_enabled` gate; some emit deep extension hooks for Pro to inject styles or controls.
- **Decision:** Feature List ships zero hooks. No upsell panel, no filter or action calls. Pro contains no listener for any Feature_List-related hook.
- **Alternatives rejected:** add a standard upsell panel for consistency — would burn panel real-estate without a real Pro feature to upsell; emit speculative hooks "in case Pro wants to extend later" — premature abstraction.
- **Consequences:** simplest widget surface among Display widgets; Pro cannot extend without a Lite-side change; Lite users see a cleaner panel.

### Connector inline-positioning via PHP, not pure CSS

- **Context:** the connector line must land exactly under the icon (or beside it for horizontal layouts). The icon's exact offset depends on shape (rhombus adds 30px), shape view, circle size, and connector width.
- **Decision:** compute the offset in PHP from settings and write it as an inline `style` on each connector `<span>`. Emit three separate spans per item (desktop, tablet, mobile) so each breakpoint can have its own offset.
- **Alternatives rejected:** pure CSS variables (`--connector-offset: …`) — would work but requires a CSS-variable-aware browser; client-side JS to recompute on resize — adds JS and viewport observation cost.
- **Consequences:** server-side computation is one-shot and accurate at render; no JS observation needed; three connector spans per item rather than one.

### Empty `content_template()` to disable JS preview

- **Context:** Elementor caches a JS-side Marionette template per widget for fast editor preview without hitting the server. When `content_template()` is empty, the editor falls back to server-side `render()` via AJAX.
- **Decision:** explicitly stub `content_template() {}` to disable the JS path.
- **Alternatives rejected:** implement a full JS preview template — would need to mirror the complex connector geometry calculation in JS, doubling maintenance cost.
- **Consequences:** editor preview is slower (AJAX round-trip per change) but always matches production output exactly.

### Auto-promote to `connector-type-modern` when icon-position is top

- **Context:** the classic connector is a vertical bar drawn alongside the icons. When icons sit on top of the content, a vertical bar makes no geometric sense — the connector should be a horizontal segment between items.
- **Decision:** at render time, when `icon_position === 'top'` and `connector === 'yes'`, force the `connector-type-modern` class regardless of the picker value.
- **Alternatives rejected:** hide the connector type picker when icon is on top — surprising; let the user pick classic and render visibly broken — bad UX.
- **Consequences:** the picker stored value persists (so switching back to icon-position left restores the classic choice), but the rendered class is overridden for top-icon layouts.

## Known Limitations

- **No Pro extension hooks** — third-party code wanting to inject a new shape, layout, or connector style has no public hook and must override via CSS.
- **`circle_size` default 70 px is hardcoded in render fallback** — if the Icon Size control is left at default and the user manually sets `circle_size: 0` somehow, the connector calculation produces a `calc(100% - 0px)` which collapses to 100%, making the connector invisible.
- **Three connector spans per item** — one per breakpoint. Each carries an inline `style`. Slightly bloated DOM for the breakpoint coverage; alternative would be CSS variables.
- **Modern connector with top-positioned icon is the only correct combination for top icons** — there's no top-icon classic option (auto-promotion). User intent is forced toward modern.
- **`content_template()` empty stub means every editor settings change triggers an AJAX round-trip** — editor responsiveness degrades on slow networks or large feature lists. Documented intent; not fixable without a JS template port.
- **Per-item link wraps both the icon `<a>` and a nested `<a>` inside the title `<heading>`** — semantically duplicates the link; assistive technology may announce twice. The structure exists for back-compat with theme CSS targeting the icon link specifically.
- **`eael_feature_list_icon_vertical_position` only applies in horizontal layout** — but the control is hidden when icon-position is `top`. Saved value persists; toggling layout off and back on restores it.
- **No accessibility hint for decorative vs meaningful icons** — `aria-hidden="true"` is added for FA icons but not for images. Image icons should have `alt` text via the media field; the widget uses the WP attachment alt.
- **No control for the connector's `top` offset on classic in vertical layouts** — only modern + top icons exposes `eael_feature_list_icon_right_indicator_position`. Classic connector top alignment is fully CSS-managed.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
