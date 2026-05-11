# Pricing Table Widget

> Multi-style pricing card with title, optional sub-title, price (with optional on-sale strike-through), currency placement, period separator, a feature list (Repeater with per-item tooltip via Tooltipster), and an action button. Lite ships style-1 and style-2 plus four "Featured" ribbon styles; Pro injects style-3, style-4, style-5 entirely via Lite-emitted hook chains — no class extension.

**Class file:** [`includes/Elements/Pricing_Table.php`](../../includes/Elements/Pricing_Table.php)
**Slug:** `price-table` (widget id `eael-pricing-table`) ⚠️ **Widget id differs from slug** — `get_name()` returns `eael-pricing-table` while the config key is `price-table`. Asset detection in [`Asset_Builder`](../../includes/Classes/Asset_Builder.php) keys on the slug; JS hook in [`price-table.js`](../../src/js/view/price-table.js#L48) keys on the widget id. See [`asset-loading.md`](../architecture/asset-loading.md) for the rename pattern.
**Public docs:** <https://essential-addons.com/elementor/docs/pricing-table/>
**Pro-shared:** ✅ Yes — Pro adds style-3, style-4, style-5, additional header layouts, currency positioning for style-2, and the multi-column variant. **All extension happens via Lite-emitted hooks**; Pro does not subclass `Pricing_Table`. The extension surface is the largest of any Display widget — ten distinct filter and action hooks.

---

## Overview

Pricing Table renders a single pricing card: title, optional sub-title (style-2+ only), price with optional sale strike-through, currency placement, period separator (`/ month`), an icon-prefixed feature list, and a CTA button. Each feature-list item supports an inline tooltip (powered by Tooltipster v4) that can be themed and animated independently.

The widget is deeply Pro-extensible. Lite ships only style-1 (Default) and style-2 (with icon header), but the panel exposes style-3/4/5 with pad-lock indicators. Each of those Pro styles is contributed entirely by Pro's listeners on Lite-emitted hooks — Pro adds the control panels via `add_pricing_table_settings_control`, the DOM template via `add_pricing_table_style_block`, and the style allowlists (icon support, header bg, header radius, subtitle support) via filter expansion. When Pro is uninstalled, picking style-3/4/5 produces a `style-3` (etc.) class on the wrapper with no matching CSS and no template — the page renders empty content within `.eael-pricing`.

## Features

- Five pricing styles in the picker (style-1, style-2 Lite; style-3, style-4, style-5 Pro-only)
- Featured "ribbon" overlay with four ribbon designs (`ribbon-1` to `ribbon-4`) and configurable text
- Ribbon alignment (left or right) on `ribbon-4` only
- Per-style icon support (`style-2` shows a leading icon; Pro styles add more via the icon-support filter)
- Title and selectable HTML tag (`h1`–`h6`, `span`, `p`, `div`)
- Sub-title (style-2 only in Lite; Pro filter `pricing_table_subtitle_field_for` adds more styles)
- Price with optional "On Sale?" sale-price strike-through
- Currency placement (Left or Right of price); Pro hook `pricing_table_currency_position` adds top/bottom for style-2
- Period separator (`/`) + period label (`month`)
- Feature list as Repeater — per-item icon, active / disabled toggle, custom colours, optional Tooltipster tooltip
- Tooltipster per-item: content, side, trigger (hover / click), animation type, animation duration, arrow toggle, six theme presets
- Action button with icon (left / right), link, and full styling
- Pro upsell section auto-hidden when Pro is active

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Style 1 (Default) | ✅ | ✅ |
| Style 2 (with icon header + sub-title) | ✅ | ✅ |
| Style 3 / 4 / 5 | ❌ — visible in picker as "(Pro)"; renders empty when selected | ✅ via `add_pricing_table_style_block` |
| Multi-column Pricing Table widget | ❌ | ✅ — separate Pro widget [`Multicolumn_Pricing_Table`](../../../essential-addons-elementor/includes/Elements/Multicolumn_Pricing_Table.php) |
| All four ribbon styles | ✅ | ✅ |
| Sub-title control (additional styles) | ❌ — style-2 only | ✅ via `pricing_table_subtitle_field_for` |
| Icon control on additional styles | ❌ — style-2 only | ✅ via `eael_pricing_table_icon_supported_style` |
| Header background controls on additional styles | ❌ | ✅ via `eael_pricing_table_header_bg_supported_style` |
| Header border-radius controls on additional styles | ❌ | ✅ via `eael_pricing_table_header_radius_supported_style` |
| Two-row header layout for style-5 | ❌ | ✅ via `eael_pricing_table_control_header_extra_layout` |
| Top / bottom currency placement for style-2 | ❌ — Left / Right only | ✅ via `pricing_table_currency_position` |
| Header image control (style-4 / style-5) | ❌ | ✅ via `add_pricing_table_settings_control` |
| Per-item Tooltipster tooltips | ✅ | ✅ |
| `eael_section_pro` upsell section in panel | shown | hidden |

When Pro is inactive and the user picks style-3/4/5, Lite emits the corresponding class (e.g. `style-3`) and an alert heading `eael_pricing_table_style_pro_alert` appears in the panel, but `render()` only writes templates for style-1 and style-2. The trailing `do_action('add_pricing_table_style_block', …)` ([line 2664](../../includes/Elements/Pricing_Table.php#L2664)) is a no-op without Pro, so the outer `.eael-pricing.style-3` wrapper renders with no inner card.

## Use Cases

- Standalone pricing card embedded in a "Pricing" page section
- Single-tier offering ("Get Started" CTA card)
- Feature-rich comparison row built by placing three Pricing Tables in a 3-column section
- Sale-pricing card with strike-through original price and discounted price
- Service offering with tooltip-annotated feature list (hover any feature for details)

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Pricing_Table.php`](../../includes/Elements/Pricing_Table.php) | PHP widget class — controls, render, `render_feature_list()`, `render_pricing_list_icon()` |
| [`src/css/view/price-table.scss`](../../src/css/view/price-table.scss) | Source styles — style-1, style-2, ribbon variants, hover, feature list, tooltipster overrides |
| [`src/js/view/price-table.js`](../../src/js/view/price-table.js) | Frontend logic — Tooltipster initialisation per item with DOMPurify sanitisation |
| [`config.php`](../../config.php#L235) entry `'price-table'` | `Asset_Builder` dependency declaration (CSS + JS + Tooltipster + DOMPurify) |
| `assets/front-end/css/view/price-table.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/price-table.min.js` | Built output (do not edit) |
| `assets/front-end/css/lib-view/tooltipster/tooltipster.bundle.min.css` | Vendor — Tooltipster core CSS |
| `assets/front-end/css/lib-view/tooltipster/tooltipster-theme.min.css` | Vendor — Tooltipster theme bundle (noir, light, punk, shadow, borderless) |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor — DOMPurify (JS-side tooltip HTML sanitisation) |
| `assets/front-end/js/lib-view/tooltipster/tooltipster.bundle.min.js` | Vendor — Tooltipster |

## Architecture

- **Pro extension via ten distinct hooks, not class extension** — the widget exposes one filter for the style picker (`eael_pricing_table_styles`), four filters for per-style capability allowlists (icon / header bg / header radius / subtitle support), one filter for the `eael/pro_enabled` gate, and four actions for Pro to inject controls and the rendered DOM for style-3/4/5. This is the most hook-rich widget in the EA Display category; Pro's `Bootstrap::__construct` registers handlers for each ([line 73-148](../../../essential-addons-elementor/includes/Classes/Bootstrap.php#L73)).
- **Style picker emits classes for styles Pro hasn't loaded** — Lite always lists style-1 through style-5 in the SELECT. Pro's filter expands the labels; without Pro, picking style-3 emits `<div class="eael-pricing style-3">` and the `style_pro_alert` heading appears as a panel reminder. The DOM template never renders for unsupported styles because the `<?php if ('style-1') … elseif ('style-2') … endif; ?>` branch ([lines 2573-2661](../../includes/Elements/Pricing_Table.php#L2573)) does not match.
- **`render()` has two inline templates plus a `do_action` extension point** — style-1 and style-2 are hand-written templates in `render()`. style-3/4/5 are rendered via `do_action('add_pricing_table_style_block', $settings, $this, $pricing, $button_url, $featured_class, $depricated_param)` at the end ([line 2664](../../includes/Elements/Pricing_Table.php#L2664)). The action passes the assembled `$pricing` HTML and a `$featured_class` string so Pro doesn't need to re-derive them.
- **`render_feature_list()` is a shared template** for all styles, called from inside each `<?php if ('style-X') ?>` branch and from Pro's injected templates. The Tooltipster `data-*` attributes are written here, so Pro inherits tooltip support automatically.
- **Tooltipster init reads all settings from `data-*` attributes** — `render_feature_list()` writes `data-content`, `data-side`, `data-trigger`, `data-animation`, `data-animation_duration`, `data-arrow`, `data-theme`, plus a unique `id`. The JS reads each `data-*` and constructs the Tooltipster config. DOMPurify sanitises `data-content` JS-side before insertion (defense-in-depth on top of `Helper::eael_wp_kses` in PHP).
- **Widget id ≠ config slug** — `get_name()` returns `eael-pricing-table` but the asset config key is `price-table`. `Asset_Builder` keys on the slug for asset detection; the JS hook `frontend/element_ready/eael-pricing-table.default` keys on the widget id. Both must be kept in sync; renaming either is a breaking change for Pro and themes.
- **Featured ribbon `ribbon-4` triggers an inline `style="overflow: hidden"`** — `render()` adds inline overflow CSS to both the outer `.eael-pricing` wrapper and the inner `.eael-pricing-item` when ribbon-4 is selected ([line 2507-2509](../../includes/Elements/Pricing_Table.php#L2507) and [2522-2524](../../includes/Elements/Pricing_Table.php#L2522)) because the ribbon graphic extends outside the card boundary. Other ribbon styles don't need this.

## Render Output

The widget produces one of two Lite-side DOM shapes (style-1 or style-2), plus a Pro-injected third shape for style-3/4/5. Annotated below for style-2 (icon header + subtitle), conditional elements marked `[?]`.

### Style 1 (Default)

```html
<div class="eael-pricing style-1 [eael-header-devider]">
  <div class="eael-pricing-item [featured ribbon-1] [ribbon-left]">
    <div class="header">
      <h2 class="title">Startup</h2>
    </div>
    <div class="eael-pricing-tag">
      <span class="price-tag">
        [?] <del class="original-price">…</del>  <!-- when on-sale -->
        <span class="original-price"><span class="price-currency">$</span>99</span>
      </span>
      <span class="price-period">/month</span>
    </div>
    <div class="body">
      <ul>
        <li class="eael-pricing-item-feature elementor-repeater-item-<id>
                   [disable-item]"
            [?] data-content="…" class="eael-pricing-tooltip"
            [?] id="<widget-id><n>"
            [?] data-side="top" data-trigger="hover" data-animation="fade" …>
          <span class="li-icon"><i class="fas fa-check"></i></span>
          <span>Unlimited calls</span>
        </li>
        …
      </ul>
    </div>
    [?] <div class="footer">
          <a class="eael-pricing-button" href="…">
            [?] <i class="fas fa-… fa-icon-left"></i>
            Sign Up
            [?] <i class="fas fa-… fa-icon-right"></i>
          </a>
        </div>
  </div>
</div>
```

### Style 2 (with icon + subtitle)

```html
<div class="eael-pricing style-2">
  <div class="eael-pricing-item [featured ribbon-2]">
    <div class="eael-pricing-icon">
      <span class="icon" style="background:#…;">
        <i class="fas fa-home"></i>
      </span>
    </div>
    <div class="header">
      <h2 class="title">Startup</h2>
      <span class="subtitle">A tagline here.</span>
    </div>
    <div class="eael-pricing-tag">…</div>
    <div class="body">…feature list…</div>
    [?] <div class="footer"><a class="eael-pricing-button">…</a></div>
  </div>
</div>
```

Notes:

- Root `.eael-pricing` always carries the style class (`style-1`, `style-2`, etc.) and may carry `eael-header-devider` when `eael_pricing_table_devider_show === 'yes'` for style-1 or style-3.
- `.eael-pricing-item.featured` carries one of `ribbon-1`/`-2`/`-3`/`-4` based on `eael_pricing_table_featured_styles`, plus `ribbon-left` when alignment is left and ribbon style is ribbon-4.
- Sale price toggles between two markup forms:
  - With currency Left: `<del><span class="price-currency">$</span>99</del><span class="sale-price"><span class="price-currency">$</span>89</span>`
  - With currency Right: `<del>99<span class="price-currency">$</span></del><span class="sale-price">89<span class="price-currency">$</span></span>`
- Per-feature tooltip attributes (`data-content`, `data-side`, etc.) live on the inner `<span class="eael-pricing-tooltip">`, not the `<li>`. The JS reads attributes by `id`.
- `id` is composed as `<widget-id><counter>` (no separator) — so widget id `45a8b7c` with feature 2 becomes `id="45a8b7c2"`. Watch for collisions across widgets if widget id ends in digits.
- Feature list `<li>` carries `elementor-repeater-item-<id>` so per-item selectors (`{{CURRENT_ITEM}}` in Elementor's selectors API) keep working.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Pricing_Table.php#L74) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_pricing_table_style` | SELECT | `style-1` | Content → Settings | Root wrapper class (`style-N`); branch in `render()` |
| `eael_pricing_table_icon_enabled` | SWITCHER | `show` | Content → Settings | Whether the per-item icon renders |
| `eael_pricing_table_icon_placement` | SELECT | `left` | Content → Settings | Icon position (left / right of the text) |
| `eael_pricing_table_title` | TEXT (dynamic) | `"Startup"` | Content → Settings | `.title` text |
| `eael_pricing_table_title_tag` | CHOOSE | `h2` | Content → Settings | Title HTML element |
| `eael_pricing_table_sub_title` | TEXT (dynamic) | `"A tagline here."` | Content → Settings | `.subtitle` text (style-2 only by default) |
| `eael_pricing_table_style_2_icon_new` | ICONS | `fas fa-home` | Content → Settings | Icon glyph (style-2 only by default; legacy shim via `__fa4_migrated`) |
| `eael_pricing_table_price` | TEXT (dynamic) | `"99"` | Content → Price | Numerical price (string; `"0"` is preserved verbatim) |
| `eael_pricing_table_onsale` | SWITCHER | `no` | Content → Price | Adds the sale strike-through branch |
| `eael_pricing_table_onsale_price` | TEXT (dynamic) | `"89"` | Content → Price | Sale price (conditional) |
| `eael_pricing_table_price_cur` | TEXT (dynamic) | `"$"` | Content → Price | Currency symbol |
| `eael_pricing_table_price_cur_placement` | SELECT | `left` | Content → Price | Currency before or after the price |
| `eael_pricing_table_price_period` | TEXT (dynamic) | `"month"` | Content → Price | Period label |
| `eael_pricing_table_period_separator` | TEXT (dynamic) | `"/"` | Content → Price | Separator between price and period |
| `eael_pricing_table_items` | REPEATER | 5 default features | Content → Feature | List items; each item has its own icon, text, tooltip controls |
| `eael_pricing_table_button_show` | SWITCHER | `yes` | Content → Button | Renders the footer button |
| `eael_pricing_table_button_icon_new` | ICONS | empty | Content → Button | Button icon glyph (legacy shim) |
| `eael_pricing_table_button_icon_alignment` | SELECT | (read via `get_settings`) | Content → Button | Icon Before / After text |
| `eael_pricing_table_btn` | TEXT | (visible) | Content → Button | Button label |
| `eael_pricing_table_btn_link` | URL (dynamic) | empty | Content → Button | Button href |
| `eael_pricing_table_featured` | SWITCHER | `no` | Content → Ribbon | Adds `.featured` class to the card |
| `eael_pricing_table_featured_styles` | SELECT | `ribbon-1` | Content → Ribbon | Ribbon design class (`ribbon-1` to `ribbon-4`) |
| `eael_pricing_table_featured_tag_text` | TEXT (dynamic) | `"Featured"` | Content → Ribbon | Pseudo-element `content` value (only for ribbons 2, 3, 4) |
| `eael_pricing_table_ribbon_alignment` | CHOOSE | `right` | Content → Ribbon | `.ribbon-left` class on `.eael-pricing-item` (only for `ribbon-4`) |
| `eael_pricing_table_bg_color` | COLOR | empty | Style → Pricing Table Style | `.eael-pricing-item` background |
| `eael_pricing_table_devider_show` | SWITCHER | not set | Style → Pricing Table Style | `eael-header-devider` class on `.eael-pricing` (style-1, style-3) |
| `eael_pricing_table_hover_box_shadow` | SWITCHER | not set | Style → Pricing Table Style | `eael-pricing-box-shadow` class on the wrapper |

### Per-feature Repeater item controls

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_pricing_table_item` | TEXT (dynamic) | sample text | `<span>` text |
| `eael_pricing_table_list_icon_new` | ICONS | `fas fa-check` | Icon glyph |
| `eael_pricing_table_icon_mood` | SWITCHER | `yes` | `disable-item` class when off (strike-through + 0.5 opacity) |
| `eael_pricing_table_list_icon_color` / `_list_text_color` | COLOR | empty | Per-item icon and text colour |
| `eael_pricing_item_tooltip` | SWITCHER | off | Enables Tooltipster on this item |
| `eael_pricing_item_tooltip_content` | TEXTAREA (dynamic) | sample text | `data-content` |
| `eael_pricing_item_tooltip_side` | CHOOSE | `top` | `data-side` (left / top / right / bottom) |
| `eael_pricing_item_tooltip_trigger` | SELECT | `hover` | `data-trigger` (hover / click) |
| `eael_pricing_item_tooltip_animation` | SELECT | `fade` | `data-animation` (fade / grow / swing / slide / fall) |
| `pricing_item_tooltip_animation_duration` | TEXT | `300` | `data-animation_duration` |
| `eael_pricing_table_toolip_arrow` | SWITCHER | `yes` | `data-arrow` |
| `eael_pricing_item_tooltip_theme` | SELECT | `noir` | `data-theme` — six options (`default`, `noir`, `light`, `punk`, `shadow`, `borderless`) |

Plus a dozen typography group controls (title, sub-title, price, period, currency, list item, button) and per-state padding / margin in the Style sections.

## Conditional Dependencies

```text
eael_pricing_table_style_pro_alert     → visible when eael_pricing_table_style in
                                          ['style-3', 'style-4', 'style-5']
eael_pricing_table_icon_placement      → visible when eael_pricing_table_icon_enabled == 'show'
eael_pricing_table_sub_title           → visible when eael_pricing_table_style in
                                          ['style-2', ...filter result of pricing_table_subtitle_field_for]
eael_pricing_table_style_2_icon_new    → visible when eael_pricing_table_style in
                                          ['style-2', ...filter result of eael_pricing_table_icon_supported_style]
eael_pricing_table_onsale_price        → visible when eael_pricing_table_onsale == 'yes'

# Feature Repeater (per-item conditions)
eael_pricing_item_tooltip_content      → visible when eael_pricing_item_tooltip == 'yes'
eael_pricing_item_tooltip_side / _trigger / _animation / _duration / _arrow / _theme
                                       → all visible when eael_pricing_item_tooltip == 'yes'

# Button
eael_pricing_table_button_icon_new     → visible when eael_pricing_table_button_show == 'yes'

# Ribbon
eael_pricing_table_featured_styles     → visible when eael_pricing_table_featured == 'yes'
eael_pricing_table_featured_tag_text   → visible when eael_pricing_table_featured == 'yes'
                                         AND eael_pricing_table_featured_styles in
                                            ['ribbon-2', 'ribbon-3', 'ribbon-4']
eael_pricing_table_ribbon_alignment    → visible when eael_pricing_table_featured == 'yes'
                                         AND eael_pricing_table_featured_styles == 'ribbon-4'

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

## Behavior Flow

1. User drops the widget → `register_controls()` runs. The style picker calls `apply_filters('eael_pricing_table_styles', $defaults)` — Pro's filter expands the labels with "Style 3 (Pro)" etc., or Lite-default keeps them with the same label.
2. `apply_filters('pricing_table_subtitle_field_for', ['style-2'])` ([line 223](../../includes/Elements/Pricing_Table.php#L223)) decides which styles get a subtitle control; same pattern for icon support, header bg, header radius.
3. `do_action('add_pricing_table_settings_control', $this)` ([line 262](../../includes/Elements/Pricing_Table.php#L262)) lets Pro add header-image controls for style-4 and style-5.
4. `do_action('pricing_table_currency_position', $this)` ([line 348](../../includes/Elements/Pricing_Table.php#L348)) lets Pro add top / bottom currency placement for style-2.
5. `do_action('eael_pricing_table_after_pricing_style', $this)` ([line 123](../../includes/Elements/Pricing_Table.php#L123)) lets Pro add style-5-specific settings.
6. `do_action('eael_pricing_table_control_header_extra_layout', $this)` ([line 1233](../../includes/Elements/Pricing_Table.php#L1233)) lets Pro add the two-row header layout option for style-5.
7. The Pro upsell section is added only when `eael/pro_enabled` filter returns false.
8. User configures the card → Elementor saves to post meta.
9. Editor preview / front-end render calls [`render()`](../../includes/Elements/Pricing_Table.php#L2491).
10. `render()` sanitises price / currency / button text via `Helper::eael_wp_kses`; computes the featured / ribbon class; builds the `$pricing` HTML string with currency placement.
11. The template branches: style-1 → hand-written DOM; style-2 → hand-written DOM with icon header; everything else → falls through to `do_action('add_pricing_table_style_block', …)` which Pro listens to for style-3/4/5.
12. Each Lite branch calls `$this->render_feature_list($settings, $this)` for the feature list. Per-item Tooltipster `data-*` attributes are emitted when `eael_pricing_item_tooltip === 'yes'`.
13. Browser receives static HTML. Elementor's `frontend/init` event fires.
14. `price-table.js` runs. The handler iterates `.eael-pricing-tooltip` elements within `$scope`, reads `data-*` attributes by id, sanitises `data-content` via DOMPurify, and initialises `$.tooltipster(…)` with the parsed config.
15. CSS handles every visual variant; tooltips appear on hover or click depending on the `data-trigger` value.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-pricing-table.default', PricingTooltip)`
- **Guard:** none — no `elementStatusCheck`. The handler binds Tooltipster afresh on each `frontend/init`. Tooltipster's plugin guards against double-init internally, but this is a latent gap compared to other widgets.
- **Reads on init:** finds all `.eael-pricing-tooltip` inside `$scope`, then for each iterates by `id` (re-queries with `#<id>`)
- **DOMPurify sanitisation:** `DOMPurify.sanitize($tooltipContent)` runs before Tooltipster sees the content — defence-in-depth against any HTML that survived `eael_wp_kses` on the PHP side
- **Tooltipster config:** `animation`, `trigger`, `content` (HTML-as-HTML), `contentAsHTML: true`, `side`, `delay` (mapped from `animation_duration`), `arrow` (boolean), `theme` (prefixed with `tooltipster-`)
- **Runtime state:** Tooltipster instances live in jQuery's per-element data store; no global state
- **Vendor dep:** Tooltipster v4 + DOMPurify; both load unconditionally with the widget (also a perf trade-off — DOMPurify is ~25 KB, Tooltipster is ~50 KB minified)

## Asset Dependencies

`Asset_Builder` enqueues only when at least one Pricing Table widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `tooltipster.bundle.min.css` | Vendor (`lib-view/tooltipster/`) | Always when widget present |
| `tooltipster-theme.min.css` | Vendor (`lib-view/tooltipster/`) | Always when widget present |
| `price-table.min.css` | self (built from `src/css/view/price-table.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `purify.min.js` (DOMPurify) | `lib-view/dom-purify/` | Sanitises tooltip HTML before Tooltipster inserts it | Always (load order before self) |
| `tooltipster.bundle.min.js` | `lib-view/tooltipster/` | Tooltip engine | Always (load order before self) |
| `price-table.min.js` | self | Per-item Tooltipster init | Always when widget present |

All three vendor libs load unconditionally — a known perf trade-off (see Known Limitations). Tooltipster is required even when no item enables a tooltip, because `Asset_Builder` cannot inspect settings.

## Hooks & Filters

The widget's public contract — the largest extension surface of any Display widget. Pro consumes all ten widget-specific hooks; many lack the `eael/` prefix and are legacy.

| Hook | Type | Signature | Purpose | Legacy? |
| ---- | ---- | --------- | ------- | ------- |
| `eael_pricing_table_styles` | filter | `array { styles: id=>label, conditions: id[] }` | Expand the style picker; Pro adds style-3/4/5 ([line 87](../../includes/Elements/Pricing_Table.php#L87)) | properly prefixed |
| `eael_pricing_table_after_pricing_style` | action | `(Widget_Base $widget)` | Add settings after the style picker; Pro adds style-5 settings ([line 123](../../includes/Elements/Pricing_Table.php#L123)) | properly prefixed |
| `pricing_table_subtitle_field_for` | filter | `array $styles` of style ids supporting subtitle | Pro adds more styles ([line 223](../../includes/Elements/Pricing_Table.php#L223)) | ⚠️ **un-prefixed legacy** |
| `eael_pricing_table_icon_supported_style` | filter | `array $styles` of style ids supporting an icon header | Pro extends ([line 257](../../includes/Elements/Pricing_Table.php#L257)) | properly prefixed |
| `add_pricing_table_settings_control` | action | `(Widget_Base $widget)` | Add header-image / extra controls in Settings; Pro adds style-4 / style-5 header image ([line 262](../../includes/Elements/Pricing_Table.php#L262)) | ⚠️ **un-prefixed legacy** |
| `pricing_table_currency_position` | action | `(Widget_Base $widget)` | Add currency-position options for style-2; Pro adds top / bottom ([line 348](../../includes/Elements/Pricing_Table.php#L348)) | ⚠️ **un-prefixed legacy** |
| `eael_pricing_table_header_bg_supported_style` | filter | `array $styles` | Style ids that allow header bg controls; Pro extends ([line 1093](../../includes/Elements/Pricing_Table.php#L1093)) | properly prefixed |
| `eael_pricing_table_header_radius_supported_style` | filter | `array $styles` | Style ids that allow header border-radius; Pro extends ([line 1107](../../includes/Elements/Pricing_Table.php#L1107)) | properly prefixed |
| `eael_pricing_table_control_header_extra_layout` | action | `(Widget_Base $widget)` | Add extra header layouts in style; Pro adds two-row layout for style-5 ([line 1233](../../includes/Elements/Pricing_Table.php#L1233)) | properly prefixed |
| `add_pricing_table_style_block` | action | `(array $settings, Widget_Base $widget, string $pricing_html, string $button_url, string $featured_class, string $depricated_param)` | Render the DOM for style-3/4/5; Pro provides the entire template ([line 2664](../../includes/Elements/Pricing_Table.php#L2664)) | ⚠️ **un-prefixed legacy** |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Hides the upsell section |

⚠️ The four un-prefixed hooks (`pricing_table_subtitle_field_for`, `add_pricing_table_settings_control`, `pricing_table_currency_position`, `add_pricing_table_style_block`) are part of Pro's public contract. **Do not rename or remove without a coordinated Pro PR + a dual-emit migration over one release cycle** (emit both `eael/<name>` and the legacy name, then deprecate). All four are marked with `// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound` in the source.

Plus the inline note: `add_pricing_table_style_block` passes `$featured_class` twice — once as `$featured_class` and once as `$depricated_param` (sic) ([line 2664](../../includes/Elements/Pricing_Table.php#L2664)). This is a backwards-compat shim for Pro handlers written against an older signature; the duplicate parameter is intentionally preserved.

## Customization Recipes

### Recipe 1 — Add a custom Pricing Table style without Pro

```php
add_filter( 'eael_pricing_table_styles', function ( $defaults ) {
    $defaults['styles']['style-custom'] = __( 'Custom Style', 'my-theme' );
    return $defaults;
} );

add_action( 'add_pricing_table_style_block', function ( $settings, $widget, $pricing, $button_url, $featured_class ) {
    if ( $settings['eael_pricing_table_style'] !== 'style-custom' ) {
        return;
    }
    ?>
    <div class="eael-pricing-item my-custom-card <?php echo esc_attr( $featured_class ); ?>">
        <h2 class="title"><?php echo esc_html( $settings['eael_pricing_table_title'] ); ?></h2>
        <div class="my-custom-price"><?php echo wp_kses_post( $pricing ); ?></div>
        <?php $widget->render_feature_list( $settings, $widget ); ?>
    </div>
    <?php
}, 10, 5 );
```

Pair with theme CSS targeting `.eael-pricing.style-custom .eael-pricing-item.my-custom-card`. The picker label automatically appears in the panel.

### Recipe 2 — Override the default Tooltipster theme site-wide

```scss
.tooltipster-default,
.tooltipster-noir {
    background: #1a1a1a;
    color: #fff;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 14px;
}
```

Tooltipster appends its own theme class (`tooltipster-default`, `tooltipster-noir`, etc.) outside the widget DOM, so a theme stylesheet must scope by the Tooltipster class, not the widget wrapper.

### Recipe 3 — Sanitise tooltip content more strictly than DOMPurify defaults

```js
jQuery( window ).on( 'elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/eael-pricing-table.default',
        function ( $scope ) {
            $scope.find( '.eael-pricing-tooltip' ).each( function () {
                const raw = jQuery( this ).data( 'content' ) || '';
                const stricter = DOMPurify.sanitize( raw, {
                    ALLOWED_TAGS: ['b', 'i', 'em', 'strong'],
                    ALLOWED_ATTR: []
                } );
                jQuery( this ).data( 'content', stricter );
            } );
        },
        5  // priority lower than EA default — runs before Tooltipster init
    );
} );
```

EA's default handler runs at the default priority; setting `5` ensures the override fires first.

### Recipe 4 — Remove the Tooltipster vendor load when no item enables a tooltip

```php
add_filter( 'eael/asset/dependency', function ( $dependencies, $element_id ) {
    if ( $element_id !== 'price-table' ) {
        return $dependencies;
    }
    // Inspect post content to detect tooltip use; expensive, run with caching
    if ( false === get_transient( 'my_pricing_uses_tooltip_' . get_the_ID() ) ) {
        // ... custom detection logic ...
        unset( $dependencies['js'][1] );   // tooltipster.bundle.min.js
        unset( $dependencies['css'][0] );  // tooltipster.bundle.min.css
        unset( $dependencies['css'][1] );  // tooltipster-theme.min.css
    }
    return $dependencies;
}, 10, 2 );
```

⚠️ Performance gain depends on accurate detection — false negatives drop the assets and silently break the widget. Verify the filter name against your `Asset_Builder` version before relying on this.

## Common Issues

### Style 3/4/5 selected without Pro renders an empty card

- **Likely cause:** the picker shows style-3/4/5 with a "(Pro)" suffix and a `style_pro_alert` heading in the panel; selecting it emits `<div class="eael-pricing style-X">` but `render()` only handles style-1 and style-2 inline. Pro's `add_pricing_table_style_block` handler provides the rest; without Pro, that action is a no-op.
- **Diagnose:** view the rendered HTML — is `.eael-pricing.style-3` empty inside?
- **Fix:** activate Pro; or switch back to style-1 / style-2

### Tooltip does not appear on the rendered card

- **Likely cause:** `$.fn.tooltipster` is undefined — Tooltipster failed to load; or the item's `eael_pricing_item_tooltip` switch is off; or `data-content` is empty
- **Diagnose:** browser console for JS errors; Network tab for `tooltipster.bundle.min.js` 200; inspect the `<li>` for `data-content`
- **Fix:** check `Asset_Builder` is loading vendor JS; re-save the panel to refresh `data-*` attributes; clear Elementor CSS cache

### Tooltip arrow is missing despite "Show" toggle

- **Likely cause:** `data-arrow` is read as a string in JS — `"yes" == $currentTooltip.data("arrow") ? true : false` ([line 32](../../src/js/view/price-table.js#L32)); if Elementor's REPEATER returned a non-string the check fails
- **Diagnose:** inspect the `<li>` — does it have `data-arrow="yes"`?
- **Fix:** re-save the panel; if still missing, toggle the arrow switch off then back on to force re-emit

### Tooltip content renders escaped HTML literally

- **Likely cause:** DOMPurify is sanitising tags that aren't in its default allowlist; or PHP's `eael_wp_kses` stripped them before storage
- **Diagnose:** check the raw `data-content` value via DevTools — is the tag present?
- **Fix:** use a tag that survives `eael_allowed_tags`; or override DOMPurify config (see Recipe 3)

### `featured` ribbon-4 graphic is clipped to the card boundary

- **Likely cause:** ribbon-4 is positioned outside `.eael-pricing-item`; `render()` adds `style="overflow: hidden"` ([line 2508](../../includes/Elements/Pricing_Table.php#L2508) and 2523) which is intentional to prevent the rest of the ribbon graphic from overflowing
- **Diagnose:** by design; the inline overflow rule keeps the visible ribbon segment clean
- **Fix:** none — override with `!important` CSS only if you've redesigned ribbon-4

### Currency placement Top / Bottom hidden in panel even with Pro active

- **Likely cause:** the `pricing_table_currency_position` action is registered by Pro's `Bootstrap::__construct`; but if Pro is installed and not activated, the action handler doesn't register
- **Diagnose:** check Pro plugin status; the `eael/pro_enabled` filter should return true
- **Fix:** activate Pro

### Two Pricing Tables on the same page share tooltip ids

- **Likely cause:** the `id` for a tooltip is `<widget-id><counter>` — if both widget ids happen to end in digits that collide with the counter (e.g. widget id `42` and counter `1` produce `421` matching widget id `4` counter `21`), DOM ids collide
- **Diagnose:** inspect tooltip ids — are any duplicated?
- **Fix:** rare in practice (Elementor IDs are random 7-character strings); if reproduced, regenerate page or report

### Feature item shows the icon column even when icon is disabled

- **Likely cause:** `eael_pricing_table_icon_placement` defaults to `left` in the saved settings even when `eael_pricing_table_icon_enabled` is `hide`; `render_feature_list()` only checks `icon_enabled` so the column layout still applies CSS-side
- **Diagnose:** inspect `.eael-pricing-item-feature` — is `.li-icon` present?
- **Fix:** the `.li-icon` `<span>` is not emitted when `icon_enabled !== 'show'` ([line 2390](../../includes/Elements/Pricing_Table.php#L2390)); if the column gap persists, the SCSS gap is from `display: flex` on `<li>` — toggle right alignment to remove

## Testing Checklist

- [ ] Drop at default — style-1 renders with default 5-item feature list; tooltips disabled by default
- [ ] Enable tooltip on one item; pick each side / trigger / animation / theme — Tooltipster renders with the chosen config
- [ ] Click trigger — tooltip appears on click; click outside dismisses
- [ ] Multi-line tooltip content with HTML (`<b>`, `<em>`) — content renders as HTML after DOMPurify
- [ ] Special characters (`<script>`) in tooltip content — sanitised both PHP-side and JS-side; no XSS
- [ ] Enable "On Sale?" — original price gets `<del>` strike-through; sale price appears
- [ ] Switch currency placement Left ↔ Right — currency symbol moves; markup ordering changes
- [ ] Switch style to style-2 — `.eael-pricing-icon` block appears; sub-title visible
- [ ] Pick style-3 / style-4 / style-5 without Pro — `eael_pricing_table_style_pro_alert` panel heading appears; rendered card is empty inside `.eael-pricing.style-3`
- [ ] Activate Pro and pick style-3 / 4 / 5 — full card renders via `add_pricing_table_style_block` handler
- [ ] Enable Featured + each ribbon style — `.featured.ribbon-N` class appears; ribbon graphic visible
- [ ] ribbon-4 with alignment Left — `.ribbon-left` class added; ribbon graphic mirrors
- [ ] Disable button — footer block omitted
- [ ] Button icon Left / Right — `fa-icon-left` / `fa-icon-right` class on `<i>` switches
- [ ] FA4 legacy icon (`eael_pricing_table_list_icon` non-empty + `__fa4_migrated` flag) — `render_pricing_list_icon` picks the new picker
- [ ] Per-item "Item Active?" off — `disable-item` class applied; strike-through visible
- [ ] Multiple Pricing Tables on same page — each gets unique tooltipster ids; tooltips fire independently
- [ ] After re-fired `elementor/frontend/init` — Tooltipster initialises but the JS has no `elementStatusCheck` guard; rely on Tooltipster's internal idempotence
- [ ] Tooltipster theme `borderless` — no border, transparent background
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Style picker shows Pro-only styles in the Lite picker (not hidden)

- **Context:** Lite users should discover that more styles exist in Pro; hiding them silently makes Pro upsell harder.
- **Decision:** keep style-3/4/5 in the dropdown with a `(Pro)` label; show `eael_pricing_table_style_pro_alert` heading when one is selected without Pro; render an empty `.eael-pricing.style-3` wrapper rather than fall back to style-1.
- **Alternatives rejected:** hide locked styles from Lite entirely — Lite users don't see what Pro adds; fall back to style-1 silently — surprises the user.
- **Consequences:** Lite users see clear "this is Pro" messaging; if they ignore the alert and publish, the rendered page shows an empty card — discoverability cost.

### Pro extension via ten distinct hooks instead of class inheritance

- **Context:** Pro contributes three new styles, two new currency positions, additional subtitle / icon / header bg / header radius / header layout / header image controls. All on the same widget class as Lite.
- **Decision:** emit one filter for style allowlists, one for the style picker, one filter pair for capability allowlists (4 total), and four actions for control injection / DOM rendering.
- **Alternatives rejected:** Pro subclasses `Pricing_Table` — duplicates registration logic and breaks Lite-side asset detection; Pro overrides `render()` via filter — would have to re-implement everything Lite already does.
- **Consequences:** Lite owns the widget; Pro is purely additive. When Pro is uninstalled, hooks become no-ops with no errors. Easy to maintain in isolation but the hook contract is now large (10 hooks) and partly legacy.

### Tooltipster instead of CSS-only or native `<title>` tooltips

- **Context:** per-feature tooltips need rich content (HTML), positioning (4 sides), and theming.
- **Decision:** Tooltipster v4 — gives positioning, themes, animation, click trigger, and HTML content out of the box.
- **Alternatives rejected:** native `<title>` attribute — no HTML, no theme, no click trigger; CSS pseudo-element tooltip — no HTML, hard to position dynamically; tippy.js — modern alternative but would add another vendor lib to the EA bundle.
- **Consequences:** ~50 KB Tooltipster + ~25 KB DOMPurify load per page with this widget; load is unconditional even when no tooltip is enabled.

### Sanitise tooltip HTML twice (PHP + JS)

- **Context:** tooltip content flows from PHP through `data-*` attribute to JS to DOM insertion. Each step is a potential XSS vector.
- **Decision:** `Helper::eael_wp_kses($content)` PHP-side when writing the attribute; `DOMPurify.sanitize($tooltipContent)` JS-side before Tooltipster inserts.
- **Alternatives rejected:** PHP only — vulnerable to a future regression in `eael_wp_kses`; JS only — vulnerable to direct DOM manipulation before init.
- **Consequences:** Tooltip content cost is two sanitisation passes; defence-in-depth.

### `featured_class` passed twice to `add_pricing_table_style_block` (`$depricated_param` shim)

- **Context:** older Pro handlers expected an additional `$featured_class` positional parameter; the rename broke compatibility.
- **Decision:** pass `$featured_class` as the 5th argument and again as the 6th `$depricated_param` (sic) for back-compat.
- **Alternatives rejected:** remove the parameter — breaks older Pro versions; rename the legacy parameter — same breaking change.
- **Consequences:** the duplicate parameter is intentional cruft; cleanup requires coordinated Pro release.

## Known Limitations

- **Widget id (`eael-pricing-table`) ≠ config slug (`price-table`)** — legacy rename. Cross-document references and asset detection must keep both in sync. See [`asset-loading.md § Common Pitfalls`](../architecture/asset-loading.md).
- **Four un-prefixed legacy hooks** (`pricing_table_subtitle_field_for`, `add_pricing_table_settings_control`, `pricing_table_currency_position`, `add_pricing_table_style_block`) — must be preserved for Pro compatibility; renames require dual-emit migration.
- **`add_pricing_table_style_block` passes `$featured_class` twice** ([line 2664](../../includes/Elements/Pricing_Table.php#L2664)) — second copy named `$depricated_param` (sic, misspelled). Legacy shim.
- **No `elementStatusCheck` guard in `price-table.js`** — re-fired `elementor/frontend/init` re-registers the action. Tooltipster's internal idempotence prevents visible double-init, but the binding is technically duplicated.
- **Tooltip id collision risk** — `id = <widget-id><counter>` is concatenated without separator. With short widget ids ending in digits, two widgets could collide. Rare in practice; Elementor IDs are 7-character.
- **Tooltipster + DOMPurify always load** — ~75 KB even if no item enables a tooltip. `Asset_Builder` cannot inspect settings.
- **Style 3/4/5 silently render empty without Pro** — picker accepts the value but `render()` only handles 1 and 2 inline; the upsell heading is the only user-facing hint. Could fall back to style-1 with a `force-fallback` similar to Fancy Text, but discoverability would suffer.
- **Currency placement is a SELECT with only Left / Right in Lite** — Pro adds Top / Bottom only for style-2; no way to access them on style-1.
- **`eael_pricing_table_ribbon_alignment` only affects `ribbon-4`** — controls hide it for other ribbons but the value is still saved.
- **`eael_pricing_table_btn` (button text)** is the only button-text control; renaming requires updating Pro template handlers that reference `$pricing` and adjacent fields.
- **Per-item icon colour applies via `{{CURRENT_ITEM}}` selectors** but the SVG fill rule uses `!important` ([line 436](../../includes/Elements/Pricing_Table.php#L436)) — theme overrides need higher specificity or another `!important`.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
