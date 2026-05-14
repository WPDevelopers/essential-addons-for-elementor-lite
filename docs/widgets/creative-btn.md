# Creative Button Widget

> Animated call-to-action button with a library of CSS-driven hover effects (Winona, Tamaya, and more), optional icon on either side, custom Liquid Glass surface effects, and secondary "hover-text" for select effect styles. Minimal JS — just an SVG-fill stripper for the Liquid Glass colour mode.

**Class file:** [`includes/Elements/Creative_Button.php`](../../includes/Elements/Creative_Button.php)
**Slug:** `creative-btn` (config.php key) — widget id `eael-creative-button`
**Public docs:** <https://essential-addons.com/elementor/docs/creative-buttons/>
**Pro-shared:** ✅ Yes — Pro injects additional button effects (e.g. Pipaluk) and Liquid Glass effects 4-6; the Lite-side hook `eael_wd_liquid_glass_effect_svg_pro` is the integration point.

**Naming note:** widget id `eael-creative-button` and config slug `creative-btn` differ because of a historical short-form rename. The legacy mapping is preserved by [`Elements_Manager::replace_widget_name`](../../includes/Classes/Elements_Manager.php#L215). See [`asset-loading.md § Common Pitfalls`](../architecture/asset-loading.md#common-pitfalls) for the broader replace-widget-name pattern.

---

## Overview

Creative Button is the workhorse CTA widget. The user picks a base effect style (one of ~10 in Lite, more in Pro), sets text + link, optionally adds an icon and "secondary text" that appears on hover for compatible effects. CSS does the heavy lifting — animations, transforms, transitions, and the `data-text` attribute that hover-reveal effects read.

A small JavaScript file ([`creative-btn.js`](../../src/js/view/creative-btn.js), 17 lines) runs only when the user enables the "use my color" toggle for SVG icons — it strips inline `fill` attributes from any SVG inside the button so CSS `color` / `fill` rules can apply.

The Liquid Glass Effect is shared infrastructure (provided by `Traits\Helper`) — Creative Button surfaces effect1 and effect2 in Lite; effects 4-6 are Pro-locked teasers in the controls panel; Pro injects the corresponding SVG layer at render time via the `eael_wd_liquid_glass_effect_svg_pro` action.

## Features

- Base text + link with full URL control (external, nofollow, target)
- Icon on left or right side (Font Awesome 5 or custom SVG via Elementor's `ICONS` control), with FA4-to-FA5 migration shim
- Secondary "hover-reveal" text for effects that support it (Tamaya, Winona-style)
- ~10 hover effect styles in Lite, additional effects unlocked in Pro
- Liquid Glass Effect — effect1 + effect2 in Lite; effects 4-6 are visible-but-locked teasers
- Per-effect background colour controls via the Liquid Glass helper
- "Use my colour for SVG" toggle that strips inline SVG fills so CSS can colour them
- Standard typography, padding, border, border-radius, shadow controls
- Hover-state typography and colour overrides
- Responsive sizing and alignment

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Standard hover effects (Winona, Tamaya, etc.) | ✅ ~10 effects | ✅ Lite effects + Pro-only effects (Pipaluk, …) |
| Icon support + left/right alignment | ✅ | ✅ |
| Secondary hover-reveal text | ✅ (Tamaya / compatible effects) | ✅ |
| Liquid Glass Effect 1 + 2 | ✅ | ✅ |
| Liquid Glass Effects 4-6 | ❌ (panel shows lock icons + upsell teaser) | ✅ (full effect rendering) |
| SVG layer for Liquid Glass | ❌ | ✅ via `eael_wd_liquid_glass_effect_svg_pro` action |
| Pro upsell section in panel | ✅ shown | ❌ hidden |

The Pro extension wires real implementations into the same effect dropdown options the Lite teasers occupy.

## Use Cases

- Primary call-to-action button on landing pages with attention-grabbing hover animation
- Secondary CTA where the standard Elementor button looks too plain
- "Sign up" / "Get started" buttons that need on-brand hover micro-interactions
- Marketing pages that want differentiated buttons across multiple sections
- Cases where icon + text + animation need to be combined without custom CSS

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Creative_Button.php`](../../includes/Elements/Creative_Button.php) | PHP widget class (1,230 lines) — controls registration + render. Uses `HelperTrait` for shared Liquid Glass control helpers. |
| [`src/css/view/creative-btn.scss`](../../src/css/view/creative-btn.scss) | Source styles — base button + per-effect keyframes / transitions |
| [`src/js/view/creative-btn.js`](../../src/js/view/creative-btn.js) | Tiny frontend JS (17 lines) — strips SVG fills when `csvg-use-color` class is present |
| [`config.php`](../../config.php) entry `'creative-btn'` | Asset_Builder dependency declaration (line 108) — CSS + JS |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php) | `eael_liquid_glass_effects()`, `eael_liquid_glass_shadow_effects()`, `eael_wd_liquid_glass_effect_bg_color_effect()`, `eael_pro_lock_icon()`, `eael_teaser_template()` — used to register Liquid Glass controls |
| `assets/front-end/css/view/creative-btn.min.css` | Built CSS output (do not edit) |
| `assets/front-end/js/view/creative-btn.min.js` | Built JS output (do not edit) |

## Architecture

- **Effect = CSS class on the button.** The chosen `creative_button_effect` setting maps directly to a class like `eael-creative-button--winona` or `eael-creative-button--tamaya`. SCSS owns the entire animation; PHP just sets the class.
- **`data-text` attribute carries the secondary text** for effects that reveal a second label on hover. CSS reads `content: attr(data-text)` in `::after` / `::before` pseudo-elements (visible in `creative-btn.scss`, e.g. the Winona block).
- **Tamaya is a special render branch** — instead of using the standard inner layout, it emits two `tamaya-before` / `tamaya-after` secondary spans wrapping the inner content. Adding similar "structural" effects requires editing `render()`, not just SCSS.
- **`HelperTrait` mixin adds Liquid Glass controls** — `use HelperTrait;` at [line 25](../../includes/Elements/Creative_Button.php#L25) imports a set of methods that other widgets also use. The widget calls `$this->eael_liquid_glass_effects()` / `$this->eael_liquid_glass_shadow_effects()` to register the cross-widget Liquid Glass control surface.
- **Pro injection via action hook.** At render time, [line 1193](../../includes/Elements/Creative_Button.php#L1193) emits `do_action( 'eael_wd_liquid_glass_effect_svg_pro', $this, $settings, '.eael-creative-button' )`. Lite has no listener; Pro registers a callback that injects the SVG `<defs>` + filter markup required by Liquid Glass effects.
- **JS is conditional — does nothing unless `csvg-use-color`.** [`creative-btn.js:5`](../../src/js/view/creative-btn.js#L5) only acts when the button carries the class (i.e. only when the user enabled "use my color"). For the common case the handler is a no-op.
- **Single root `<div class="eael-creative-button-wrapper">`** in `render()` — the `<a>` anchor is inside. Consistent with widget-development rules.
- **Standard Pro upsell pattern** at [line 247](../../includes/Elements/Creative_Button.php#L247) — `if ( ! apply_filters( 'eael/pro_enabled', false ) )` gates the marketing section. See [`elementor-controls`](../../.claude/skills/elementor-controls/SKILL.md) for the canonical pattern.

## Render Output

The widget produces one of two DOM shapes — standard or Tamaya-special-case.

### Standard layout (most effects)

```html
<div class="eael-creative-button-wrapper">
  <a class="eael-creative-button eael-creative-button--winona eael-cb-icon-position-left csvg-use-color"
     href="https://example.com"
     target="_blank" rel="nofollow"
     data-text="Hover Me">
    <!-- Pro action injects SVG defs/filter here when Liquid Glass is active -->

    <div class="creative-button-inner">
      <!-- Icon on left (only when icon enabled AND alignment=left AND effect != tamaya) -->
      <span class="eael-creative-button-icon-left">
        <!-- SVG via Icons_Manager::render_icon -->
      </span>

      <span class="cretive-button-text">Button Text</span>

      <!-- Icon on right (only when icon enabled AND alignment=right AND effect != tamaya) -->
      <span class="eael-creative-button-icon-right">…</span>
    </div>
  </a>
</div>
```

Note the typo in the text-span class: `cretive-button-text` (should read `creative-button-text`). Documented as a Known Limitation — themes that override this class must use the typo'd form.

### Tamaya layout (`creative_button_effect == 'eael-creative-button--tamaya'`)

```html
<div class="eael-creative-button-wrapper">
  <a class="eael-creative-button eael-creative-button--tamaya …"
     href="…" data-text="Secondary Text">

    <div class="eael-creative-button--tamaya-secondary eael-creative-button--tamaya-before">
      <span>Secondary Text</span>
    </div>

    <div class="creative-button-inner">
      <span class="cretive-button-text">Button Text</span>
      <!-- icon emit suppressed in Tamaya layout -->
    </div>

    <div class="eael-creative-button--tamaya-secondary eael-creative-button--tamaya-after">
      <span>Secondary Text</span>
    </div>
  </a>
</div>
```

Tamaya does not emit icons (the icon block is gated on `$settings['creative_button_effect'] !== 'eael-creative-button--tamaya'`).

## Controls Reference

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `creative_button_text` | TEXT | "Click Me" | Content → Creative Button | Visible button text |
| `creative_button_secondary_text` | TEXT | "Hover Me" | Content → Creative Button | `data-text` attribute + Tamaya secondary spans |
| `creative_button_link_url` | URL | `'#'` | Content → Creative Button | `<a href>` + target + rel + nofollow attributes |
| `creative_button_effect` | SELECT | `eael-creative-button--winona` | Content → Creative Button | Sets a CSS class on the `<a>`; determines hover animation and DOM-layout branch (Tamaya special case) |
| `eael_show_creative_button_icon_content` | SWITCHER | `''` | Content → Creative Button | Toggles icon emission (suppressed in Tamaya regardless) |
| `eael_creative_button_icon_new` | ICONS | — | Content → Creative Button | Icon control (FA5 / SVG); falls back to legacy `eael_creative_button_icon` for FA4-migrated installs |
| `eael_creative_button_icon_alignment` | CHOOSE | `left` | Content → Creative Button | Renders icon on left or right side (suppressed in Tamaya) |
| `eael_creative_button_remove_svg_color` | SWITCHER | `''` | Content → Creative Button | Adds `csvg-use-color` class; activates the JS handler to strip SVG inline fills |
| `eael_liquid_glass_*` (Group) | Multiple | — | Content → Liquid Glass Effect (HelperTrait-registered) | Liquid Glass effect picker — effects 1-2 functional in Lite, 4-6 locked teasers |
| `eael_liquid_glass_effect_bg_color_*` | COLOR | various | Content → Liquid Glass Effect | Per-effect background colour |
| `creative_button_*_typography` (Group) | Group_Control_Typography | — | Style → Button Style | Button typography |
| `creative_button_*_color` / `_hover_color` | COLOR | — | Style → Button Style | Normal + hover state colours |
| `creative_button_*_border` (Group) | Group_Control_Border | — | Style → Button Style | Border |
| `creative_button_*_box_shadow` (Group) | Group_Control_Box_Shadow | — | Style → Button Style | Shadow |
| `eael_creative_button_icon_size`, `_color`, `_margin`, … | Various | — | Style → Icon Style | Icon styling |
| `eael_section_pro` + `eael_control_get_pro` | (Pro upsell) | — | (custom) | Standard upsell — visible only when Pro is not active |

Full controls in [`register_controls()`](../../includes/Elements/Creative_Button.php#L80). Four top-level sections (Content + 2 Style + Pro upsell) plus the Liquid Glass section registered by `HelperTrait`.

## Conditional Dependencies

A control hidden behind a condition still saves its value. This map answers "why doesn't option X show in my panel?" without reading the source.

```text
eael_creative_button_icon_new                    → visible when eael_show_creative_button_icon_content == 'yes'
eael_creative_button_icon_alignment              → visible when eael_show_creative_button_icon_content == 'yes'
(icon style controls — size, color, margin, …)  → visible when eael_show_creative_button_icon_content == 'yes'

eael_creative_button_remove_svg_color            → always (no condition)
                                                   but only takes effect when icon is SVG-based

creative_button_secondary_text                   → always (visible for all effects)
                                                   but only renders to DOM for Tamaya;
                                                   for non-Tamaya effects, used via data-text
                                                   attribute (CSS `content: attr(data-text)`)

(Liquid Glass per-effect bg color controls)     → conditional on effect selection
(Liquid Glass effects 4-6)                       → visible (as locked teasers)
                                                   when Pro is NOT active;
                                                   replaced by real Pro options
                                                   when Pro is active

eael_section_pro / eael_control_get_pro          → visible when Pro plugin is NOT active
```

## Behavior Flow

End-to-end from "user drops widget" to "browser renders animated button":

1. **User drops the widget on the Elementor canvas.** Elementor calls `register_controls()` → control panel appears. `HelperTrait` is mixed in, so Liquid Glass controls also register.
2. **User configures** — text, link, picks an effect, toggles icon if needed, optionally enables Liquid Glass.
3. **User clicks Update.** Elementor saves settings to `_elementor_data` post meta.
4. **Editor preview iframe re-renders** by calling `render()` with the new settings.
5. **`render()` reads `$this->get_settings_for_display()`** and computes:
   - Icon migration state (`__fa4_migrated` flag)
   - The CSS class string combining `eael-creative-button` + effect class + icon position class + optional `csvg-use-color`
   - Link attributes via `add_link_attributes` (handles target + nofollow + external)
   - Sets `data-text` attribute from `creative_button_secondary_text`
6. **`render()` branches on the effect.** Tamaya gets a different DOM tree; other effects use the standard inner layout with icon on left or right.
7. **`do_action('eael_wd_liquid_glass_effect_svg_pro', …)`** fires inside the `<a>` element. In Lite no handler is registered; in Pro a handler injects SVG `<defs>` markup that the CSS filter references.
8. **Browser receives the complete HTML.** CSS handles every effect's animation. The `<a>` has all the classes; pseudo-elements (`::before`, `::after`) animate on hover using `transform`, `opacity`, etc.
9. **Frontend JS init runs.** `elementor/frontend/init` fires; the guard `eael.elementStatusCheck('eaelCreativeButton')` runs once. `addAction("frontend/element_ready/eael-creative-button.default", CreativeButton)` registers.
10. **For each widget instance**, the `CreativeButton($scope, $)` handler runs. If the button has the `csvg-use-color` class, it strips inline `fill` attributes from all SVG descendants so CSS can colour them. Otherwise the handler is a no-op.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-creative-button.default', CreativeButton)`
- **Guard:** `if ( eael.elementStatusCheck('eaelCreativeButton') ) return false;` — prevents re-registration on re-fired `elementor/frontend/init`
- **Reads on init:** the widget's `$scope`; finds `.eael-creative-button-wrapper` and the inner `.eael-creative-button`
- **Branch:** only does work when the button has the `csvg-use-color` class — strips `fill` from the inline SVG and from every descendant element
- **Runtime state:** none; the handler is a one-shot DOM mutation. No bound events. No timers. No vendor lib state.

For non-SVG buttons (the common case), this handler is effectively a no-op — minor cost of a class check.

## Asset Dependencies

`Asset_Builder` enqueues only when the widget is detected on the page. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats (templates, popups, shortcodes).

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `creative-btn.min.css` | self (built from `src/css/view/creative-btn.scss`) | Always when widget present |

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `creative-btn.min.js` | self (built from `src/js/view/creative-btn.js`) | SVG fill stripper when `csvg-use-color` is set | Always when widget present |

No vendor libraries. No SweetAlert, no Swiper, no Typed — pure self-built assets.

## Hooks & Filters

The widget participates in the standard EA hook surface plus its own Liquid Glass extension point.

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/pro_enabled` | filter | `(bool $enabled)` | Standard Lite/Pro gate; controls Pro upsell visibility and Liquid Glass effects 4-6 |
| `eael_wd_liquid_glass_effect_svg_pro` | action | `( $widget, $settings, $selector )` | **Pro injection point** — fires inside the `<a>` during `render()`. Pro registers a handler that emits the SVG `<defs>` and filter markup required for the Liquid Glass visual effect. Lite has no handler. |
| `eael_allowed_tags` (used by `Helper::eael_allowed_tags`) | filter | `(array $tags)` | Cross-cutting EA filter for tags allowed in `wp_kses`; affects the button text + secondary text escape pipeline |

No widget-specific style filter (e.g. there is no `creative_button_effect_types`). New effect styles cannot be filter-injected — they require code changes to the SELECT options and the SCSS animations.

## Customization Recipes

### Recipe 1 — Add a custom effect via SCSS + theme JS

Adding a fully new effect requires three pieces:

1. Add the option to the effect select (currently hardcoded — needs PHP edit or a filter on `elementor/widget/render_content`).
2. Write the SCSS that styles `.eael-creative-button--my-effect`.
3. Optionally use `data-text` for a hover-reveal effect via `::after { content: attr(data-text); }`.

For a quick-and-dirty CSS-only effect (without a panel option), use a custom class in theme CSS and apply it via the Advanced → CSS Classes Elementor field:

```scss
.eael-creative-button.my-custom-button {
    transition: transform 0.3s ease;

    &:hover {
        transform: scale(1.05);
    }
}
```

### Recipe 2 — Force-strip SVG colours via theme JS (without the toggle)

If you want every Creative Button on the site to use CSS-coloured SVG icons regardless of the per-widget toggle:

```javascript
jQuery( window ).on( 'elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/eael-creative-button.default',
        function ( $scope ) {
            $scope.find( '.eael-creative-button svg' )
                  .removeAttr( 'fill' )
                  .find( '*' ).removeAttr( 'fill' );
        }
    );
} );
```

Replicates the widget's own JS handler unconditionally.

### Recipe 3 — Hook the Liquid Glass injection point from a child plugin

If you want to render your own SVG layer instead of (or alongside) Pro's:

```php
add_action( 'eael_wd_liquid_glass_effect_svg_pro', function ( $widget, $settings, $selector ) {
    if ( $widget->get_name() !== 'eael-creative-button' ) {
        return;
    }
    if ( ( $settings['eael_liquid_glass_effects'] ?? '' ) !== 'effect1' ) {
        return;
    }
    // Emit your own SVG defs / filter markup for the button's effect.
    echo '<svg xmlns="http://www.w3.org/2000/svg" style="display:none;">…</svg>';
}, 10, 3 );
```

The action passes the widget instance, the resolved settings, and the CSS selector — enough context to scope your injection.

## Common Issues

### Hover effect appears in editor but not on frontend

- **Likely cause:** `creative-btn.min.css` not loaded on the frontend (Asset_Builder detection gap, page caching, etc.)
- **Diagnose:** Network tab → search for `creative-btn.min.css`; should be 200
- **Fix:** Regenerate CSS via Elementor → Tools → Regenerate CSS & Data; check the widget's `config.php` slug is present in the registry

### Tamaya effect renders but secondary text is empty

- **Likely cause:** `creative_button_secondary_text` is empty
- **Diagnose:** check the setting in the panel
- **Fix:** add text in the "Secondary Text" field; Tamaya specifically needs this filled

### Icon doesn't appear after upgrading from older version

- **Likely cause:** legacy `eael_creative_button_icon` (FA4) → new `eael_creative_button_icon_new` (FA5 / Icons control) migration; the widget supports both, controlled by `__fa4_migrated` flag
- **Diagnose:** check `_elementor_data` post meta for the migration flag and the new icon value
- **Fix:** re-pick the icon in the widget settings; Elementor writes to the new field and updates the migration flag

### SVG icon doesn't take CSS colour despite enabling "Use my color"

- **Likely cause:** the SVG has inline `style="fill: …"` (not just `fill` attribute); the JS strips `fill` attribute but doesn't touch `style`
- **Diagnose:** view source of the rendered icon; check for `style="fill:..."`
- **Fix:** edit the SVG file to remove the inline style attribute, or extend the JS to handle `style`:
  ```js
  svg.find('*').each(function () {
      this.style.fill = '';
  });
  ```

### Liquid Glass effects 4-6 picker shows but does nothing

- **Likely cause:** Pro plugin not active; these are locked teasers
- **Diagnose:** check Pro plugin status
- **Fix:** activate Pro; or use effects 1-2 which work in Lite

### Button alignment changes don't apply

- **Likely cause:** wrapper `.eael-creative-button-wrapper` has `display: flex` (per [`creative-btn.scss:5`](../../src/css/view/creative-btn.scss#L5)); alignment classes work but theme CSS may override
- **Diagnose:** DevTools — inspect computed styles on the wrapper
- **Fix:** override at higher specificity in theme CSS, or use Elementor's Advanced → Spacing for layout control

### Text typo `cretive-button-text` causes my theme override to miss

- **Cause:** the text-span class has a typo (`cretive` instead of `creative`) — hardcoded in `render()`
- **Diagnose:** view source; class name is `cretive-button-text`
- **Fix:** target the typo'd class in your CSS — `.cretive-button-text { … }`. Renaming would be a breaking change for every theme override out there. Documented as Known Limitation.

## Testing Checklist

After modifying this widget, manually verify on `http://localhost:8888`:

- [ ] Drop widget at default config — renders with Winona effect, "Click Me" text, no icon
- [ ] Switch effect to Tamaya — DOM changes to the tamaya-before / tamaya-after structure; icon block disappears
- [ ] Switch effect to other Lite effects — base class on `<a>` changes; CSS animation reflects the new effect
- [ ] Toggle icon on — icon appears at the configured alignment
- [ ] Switch icon alignment left ↔ right — DOM order changes
- [ ] Enable "Use my color" toggle on an SVG icon — `csvg-use-color` class added; JS strips fill; CSS `color` rule colours the SVG
- [ ] Set a hover-state colour — hover state visibly changes
- [ ] Enable Liquid Glass Effect 1 — Pro action fires (verify Pro is active for real effect; in Lite, fires as a no-op)
- [ ] Try to select Liquid Glass Effects 4-6 in Lite — they appear locked / show teaser
- [ ] Mobile / tablet / desktop responsive — typography and padding switch per breakpoint
- [ ] Two Creative Button widgets on one page — both animate independently; `eael.elementStatusCheck` doesn't block the second
- [ ] Disable Pro plugin — Pro upsell section visible; Liquid Glass effects 4-6 show as locked
- [ ] Set `target="_blank"` + `nofollow` — both attributes appear on `<a>`
- [ ] Secondary hover-reveal text (Winona-style) — `data-text` populates; CSS reveals it on hover
- [ ] Special characters in text (`<`, `>`, `&`) — output is escaped; no XSS
- [ ] RTL site — Elementor's RTL pipeline handles direction; verify left / right icon alignment is mirrored if expected
- [ ] After source changes, `npm run build` and visually confirm

## Architecture Decisions

### CSS-driven effects with class-name switching

- **Context:** ~10 hover effects with diverse animations could be implemented in JS (jQuery / vanilla) or pure CSS.
- **Decision:** Pure CSS. PHP sets a class on the `<a>`; SCSS owns every animation.
- **Alternatives rejected:** JS-driven animations (more bytes, harder to maintain, less performant for transform/opacity); per-effect JS handler (proliferation).
- **Consequences:** Adding an effect requires SCSS work and a new SELECT option. Effects can leverage browser's compositor-friendly transitions. The trade-off: complex effect logic (e.g. Liquid Glass) requires SVG injection from Pro — handled via the action hook.

### Tamaya special-case in render

- **Context:** Tamaya needs two extra wrapper divs (`tamaya-before`, `tamaya-after`) that other effects don't.
- **Decision:** Branch in `render()` keyed on the effect class string.
- **Alternatives rejected:** Emit the wrappers for every effect (extra DOM noise); per-effect template engine (over-engineered).
- **Consequences:** Adding similar "structural" effects requires editing `render()`. If future effects share Tamaya's needs, factor into a shared "needs-wrapper" check.

### Action-hook injection point for Pro features

- **Context:** Liquid Glass effects 4-6 require SVG filter markup that Lite shouldn't ship (binary size, opt-in feature).
- **Decision:** Emit `do_action( 'eael_wd_liquid_glass_effect_svg_pro', $widget, $settings, $selector )` inside the `<a>`. Pro registers a callback that injects markup; Lite has no callback so nothing is added.
- **Alternatives rejected:** Conditional bundling in Lite (always-loaded even if unused); separate widget for Pro effects (UX split).
- **Consequences:** Clean Lite/Pro separation. The Lite-side hook is part of the public contract — Pro depends on it firing with stable arguments. Renaming or moving this hook is a breaking change for Pro.

### Minimal JS (no-op for most users)

- **Context:** The only behaviour that needs JS is stripping SVG fills, which only matters for users using SVG icons with the "use my color" toggle.
- **Decision:** Ship a 17-line JS file that runs the class check and acts only when needed.
- **Alternatives rejected:** Skip JS entirely (loses the SVG colour mode); inline the JS into a global EA bundle (worse cacheability for users who never use this widget).
- **Consequences:** Per-widget JS bundle is tiny — barely measurable. The handler is registered for every Creative Button on the page but does almost nothing for most.

### Typo `cretive-button-text` preserved

- **Context:** A typo in the text-span class name shipped at some point. Themes and customisations may target this class as-is.
- **Decision:** Keep the typo. Document it.
- **Alternatives rejected:** Fix the typo (silently breaks every theme override targeting `.cretive-button-text`); add both classes (clutters the rendered markup).
- **Consequences:** The typo is now part of the public CSS contract. Renaming requires the same dual-emit migration approach used for legacy filter names — emit both `cretive-button-text` and `creative-button-text` for a release cycle, deprecate the typo with notice, then drop.

## Known Limitations

- **Text span class is misspelled.** `cretive-button-text` (should be `creative`). Public CSS contract; renaming is a breaking change. Documented in Common Issues + Architecture Decisions.
- **No filter to add new effects.** Effect options are hardcoded in the SELECT and animations are in SCSS. Pro extends by overriding `register_controls()` and shipping additional SCSS, but third-party plugins cannot inject new effects via a public hook.
- **Tamaya icon suppression is hard-coded.** Tamaya's render branch ignores the icon settings. Surprising if a user enables an icon and switches to Tamaya.
- **SVG fill stripper only handles the `fill` attribute, not inline `style`.** Documented in Common Issues; recipe provided for working around.
- **`data-text` attribute always emits, even for effects that don't use it.** Negligible cost; reduces the conditional logic in render.
- **No accessibility hint for purely-decorative buttons.** No `role="button"` or `aria-label` injection; users must use Elementor's Custom Attributes field manually.
- **`eael_wd_liquid_glass_effect_svg_pro` action name is non-prefixed-EA-style** — uses `eael_wd_` instead of `eael/` namespace convention. Documented as legacy naming; Pro depends on it.
- **The `cretive-button-text` typo is also present in the SCSS file.** Search-and-replace in SCSS would only fix half the problem since the rendered class is in PHP.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
