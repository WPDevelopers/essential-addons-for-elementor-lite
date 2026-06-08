# Vertical Text Orientation Extension

> CSS `writing-mode` toggle plus styling, gradient, and animation layer for five specific text widgets — Elementor's Heading + Text Editor + Animated Headline, and EA's Dual Color Header + Fancy Text. Adds a per-widget Style-tab section that flips text to vertical-LR / vertical-RL with optional upright orientation, gradient fills, and looping background animations.

**Class file:** [`includes/Extensions/Vertical_Text_Orientation.php`](../../includes/Extensions/Vertical_Text_Orientation.php) (559 lines)
**Slug:** `vertical-text-orientation` (`config.php` `extensions` key, line 1473)
**Public docs:** <https://essential-addons.com/docs/vertical-text-orientation/>
**Pro-shared:** Lite-owned, no Pro override at present. All controls including gradient + animation are available in Lite.

---

## Overview

The extension wires its `register_controls` callback to five specific Elementor `after_section_end` hooks — one per supported widget type. It does **not** hook the broadest `elementor/element/common/_section_style/after_section_end`, so only those five widgets receive the panel. Switching the SWITCHER on flips the widget's wrapper into vertical writing mode via a `prefix_class => 'eael_vto-'`, which CSS targets with `.eael_vto-vertical-lr` / `.eael_vto-vertical-rl`. The full panel offers flip (rotate 180°), height, upright orientation, letter / word / line spacing, text indent, and a "Styling Options" group with three style types (Normal / Background / Gradient), including a repeater for gradient stops and switcher controls for horizontal / vertical infinite background-position animations.

A small frontend JS pass (`elementorFrontend.hooks.addAction('frontend/element_ready/widget', verticalTextOrientation)`) reads `data-gradient_colors` / `data-gradient_color_angle` / `data-animation_control` attributes (added by `before_render`) and rewrites the widget's text background to a runtime-computed `linear-gradient(…)`. The same logic runs inside the editor by reaching into `window.elementor.elements.models` and locating the matching scope id.

## Components / File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Vertical_Text_Orientation.php`](../../includes/Extensions/Vertical_Text_Orientation.php) | The class — constructor wires six hooks (one `before_render` + five widget-specific control-registration hooks); `register_controls()` contributes one Style-tab section with ~20 controls; `before_render()` translates the gradient repeater into render-attribute JSON |
| [`src/css/view/vertical-text-orientation.scss`](../../src/css/view/vertical-text-orientation.scss) | Frontend CSS — `line-height: inherit` on all 12 supported selectors, plus the `eaelAnimationVTO` and `eaelAnimationVertical` `@keyframes` used by the Animation switches |
| [`src/js/view/vertical-text-orientation.js`](../../src/js/view/vertical-text-orientation.js) | Frontend + editor JS — applies the gradient as `background: linear-gradient(…)` with `-webkit-background-clip: text` so the text becomes the gradient mask; same routine runs at `frontend/element_ready/widget` and at editor init via `window.elementor.elements.models` walk |
| `config.php` line 1473 | Registry entry — declares both view-context CSS + view-context JS (different from Reading Progress / Scroll to Top which use edit-context only) |

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Writing-mode select (`vertical-lr` / `vertical-rl`) | YES | YES |
| Flip (rotate 180°) | YES | YES |
| Height (`inline-size`) | YES | YES |
| Upright orientation switcher | YES | YES |
| Letter / word / line spacing + text indent (responsive) | YES | YES |
| Style type select (Normal / Background / Gradient) | YES | YES |
| Background group control (classic backgrounds) | YES | YES |
| Gradient stop repeater + angle + direction | YES | YES |
| Background animation (horizontal + vertical keyframes) | YES | YES |
| `sideways-lr` / `sideways-rl` (CSS Writing Modes Level 4) | NO | NO |

The widgets touched by this extension include both an Elementor core widget (Heading, Text Editor, Animated Headline) and EA widgets (Dual Color Header, Fancy Text). The Animated Headline target hook (`elementor/element/animated-headline/section_style_text/after_section_end`) belongs to Elementor Pro's Animated Headline widget — on Lite-only installs that hook will never fire, but the extension still wires the listener (no-op).

## Architecture

- **Six hooks, one per supported widget type.** The constructor wires five `after_section_end` control-registration hooks plus one `elementor/frontend/before_render` hook at priority 100. Choosing scope-narrow hooks means the panel only appears on widgets the CSS can actually style — there's no point exposing "vertical-lr" on a Button widget.
- **`prefix_class` on the writing-mode select.** The `eael_vto_writing_mode` control sets `prefix_class => 'eael_vto-'`, so picking `vertical-lr` adds `.eael_vto-vertical-lr` to the widget wrapper and picking `vertical-rl` adds `.eael_vto-vertical-rl`. This is the activation surface for all twelve selectors (Heading title, headline, text-editor paragraphs, dual header, fancy text container — both as widget descendant and as widget root).
- **Inline-size, not width.** Vertical writing mode flips the box's main axis. `width` would no longer mean "horizontal extent" — instead, `inline-size` is the right logical property. The height slider's selector emits `inline-size: {{SIZE}}{{UNIT}}` accordingly.
- **Selectors duplicated across twelve targets.** Every styling control that needs to reach the actual text element repeats the same 12-line selector list. This is the cost of supporting both element-as-descendant (Heading widget where `.elementor-heading-title` is inside `.elementor-widget`) and element-as-root (Text Editor widget where `.elementor-text-editor p` is also inside, but the widget wrapper itself can carry `.elementor-widget-text-editor`).
- **Gradient via render-attributes + JS, not pure CSS.** A repeater control means N colour stops where N can be any positive integer; Elementor's CSS-emitting `selectors` mechanism doesn't accept a dynamic-length stop list. The extension solves this by encoding the repeater into `data-gradient_colors` JSON via `before_render` and letting `src/js/view/vertical-text-orientation.js` compute the `linear-gradient(…)` string at runtime.
- **Editor-mode JS reaches into `window.elementor.elements.models`.** Editor mode doesn't fire `before_render`, so the data attributes are missing. The JS walks the editor's element tree to find widgets with `eael_vertical_text_orientation_switch === 'yes'` and `eael_vto_writing_styling_type === 'gradient'`, rebuilds the gradient stops from settings, and applies the same CSS.
- **Two separate `@keyframes` for horizontal and vertical animations.** Defined in [`src/css/view/vertical-text-orientation.scss`](../../src/css/view/vertical-text-orientation.scss); each animation switcher in PHP emits `animation: eaelAnimationVTO 5s linear infinite` (horizontal) or `animation: eaelAnimationVertical 5s linear infinite` (vertical) onto the same 12-selector list.

## Render Behavior

### DOM

When the switcher is on with `vertical-lr`, a Heading widget renders like:

```html
<div class="elementor-element elementor-element-abc123 elementor-widget elementor-widget-heading eael_vto-vertical-lr"
     data-gradient_colors='[{"color":"#7C62FF","location":"50%"},{"color":"#FF6464","location":"90%"}]'
     data-gradient_color_angle="95deg"
     data-animation_control="horizontal">
    <div class="elementor-widget-container">
        <h2 class="elementor-heading-title elementor-size-default">Your text</h2>
    </div>
</div>
```

The `eael_vto-vertical-lr` class is added by Elementor's `prefix_class` mechanism on the writing-mode select. The three `data-gradient_*` attributes are added by [`Vertical_Text_Orientation::before_render()`](../../includes/Extensions/Vertical_Text_Orientation.php#L537) only when the gradient style type is active and the repeater has rows.

### CSS

Elementor's `selectors` mechanism emits per-page CSS like (one set per active widget):

```css
.elementor-element-abc123.eael_vto-vertical-lr,
.elementor-element-abc123.eael_vto-vertical-rl {
    writing-mode: vertical-lr;
    inline-size: 300px;
    text-orientation: upright;
    letter-spacing: 2px;
    word-spacing: 4px;
    line-height: 1.4;
}

.elementor-element-abc123.eael_vto-vertical-lr .elementor-heading-title,
.elementor-element-abc123.eael_vto-vertical-rl .elementor-heading-title,
/* …+10 more selector variants… */ {
    transform: rotate(180deg);                 /* if Flip = yes */
    background-clip: text;                     /* if Background style + Text Clip on */
    color: transparent;
    animation: eaelAnimationVTO 5s linear infinite;  /* if Animation on */
}
```

Frontend bundle CSS (`src/css/view/vertical-text-orientation.scss`):

```scss
.eael_vto-vertical-lr .elementor-heading-title,
/* …+11 selector siblings… */ {
    line-height: inherit;
}

@keyframes eaelAnimationVTO {
    0% { background-position: 0; }
    50% { background-position: 100%; }
    100% { background-position: 0; }
}

@keyframes eaelAnimationVertical {
    0% { background-position: 0% 0%; }
    50% { background-position: 0% 100%; }
    100% { background-position: 0% 0%; }
}
```

The `line-height: inherit` cascade override exists because rotated / writing-mode-flipped containers need their line-height to come from the parent context, not from the widget's own typography defaults — otherwise the text spacing breaks visually after rotation.

### JS

[`src/js/view/vertical-text-orientation.js`](../../src/js/view/vertical-text-orientation.js) registers as an Elementor frontend handler:

```js
jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck("eaelVerticalTextOrientation")) {
        return false;
    }
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/widget",
        verticalTextOrientation
    );
});
```

The `verticalTextOrientation($scope, $)` handler reads `$scope.data('gradient_colors')` (the JSON written by `before_render`), builds a `linear-gradient(angle, color stop, color stop, …)` string, and applies it via jQuery:

```js
$(fullSelector).css({
    background: linearGradient,
    "-webkit-background-clip": "text",
    "-webkit-text-fill-color": "transparent",
    "background-clip": "text",
});
```

Editor mode adds a parallel walk over `window.elementor.elements.models` to read settings directly from the Backbone model tree (because `before_render` doesn't run inside the editor preview).

## Asset Dependencies

| Asset | When loaded | Source |
| ----- | ----------- | ------ |
| CSS (`type=self`, `context=view`) | Frontend, via `Asset_Builder` whenever the extension is active | [`assets/front-end/css/view/vertical-text-orientation.min.css`](../../assets/front-end/css/view/vertical-text-orientation.min.css) |
| JS (`type=self`, `context=view`) | Frontend, via `Asset_Builder` whenever the extension is active | [`assets/front-end/js/view/vertical-text-orientation.min.js`](../../assets/front-end/js/view/vertical-text-orientation.min.js) |

Both files are declared in `config.php` (lines 1476–1490) and enqueued by `Asset_Builder` via the standard per-extension dependency pipeline — no manual `wp_register_script`/`wp_enqueue_script` calls elsewhere. Unlike Reading Progress and Scroll to Top, there is no edit-context-only JS; the same view bundle handles editor + frontend logic via `window.isEditMode` detection.

## Hook Timing

### Elementor hooks consumed

| Hook | Priority | Method | Purpose |
| ---- | -------- | ------ | ------- |
| `elementor/frontend/before_render` | 100 | `before_render` | Add `data-gradient_colors` / `data-gradient_color_angle` / `data-animation_control` render attributes (only when gradient mode is active) |
| `elementor/element/heading/section_title_style/after_section_end` | 10 | `register_controls` | Add Style-tab panel under Elementor's Heading widget |
| `elementor/element/text-editor/section_style/after_section_end` | 10 | `register_controls` | Add Style-tab panel under Elementor's Text Editor widget |
| `elementor/element/animated-headline/section_style_text/after_section_end` | 10 | `register_controls` | Add Style-tab panel under Elementor Pro's Animated Headline widget (no-op on Lite-only installs) |
| `elementor/element/eael-dual-color-header/eael_section_dch_title_style_settings/after_section_end` | 10 | `register_controls` | Add Style-tab panel under EA Dual Color Header |
| `elementor/element/eael-fancy-text/eael_fancy_text_suffix_styles/after_section_end` | 10 | `register_controls` | Add Style-tab panel under EA Fancy Text |

The constructor also has a commented-out hook for `elementor/element/common/_section_style/before_section_end` that would have added controls to every widget — left as a comment to signal the design choice.

### Hooks emitted

None. The extension does not call `do_action` or `apply_filters`.

## Configuration & Extension Points

### Global settings

N/A — Vertical Text Orientation has no entry in `eael_global_settings`. All configuration is per-widget, persisted into the widget's own settings dict via `_elementor_data` post meta.

### Per-widget controls (Style tab → "Vertical Text Orientation")

| Control id | Type | Default | Purpose |
| ---------- | ---- | ------- | ------- |
| `eael_vertical_text_orientation_switch` | SWITCHER | (off) | Master switch; all other controls are gated by `=== 'yes'` |
| `eael_vto_writing_mode` | SELECT | `vertical-lr` | `vertical-lr` / `vertical-rl`; emits `prefix_class => 'eael_vto-'` AND `writing-mode: {{VALUE}}` selector |
| `eael_vto_writing_mode_flip` | SWITCHER | (off) | When `yes`, emits `transform: rotate(180deg)` on the 12-selector text targets |
| `eael_vto_writing_height` (responsive) | SLIDER | — | Sets `inline-size: {{SIZE}}{{UNIT}}` on the widget wrapper |
| `eael_vto_writing_text_orientation` | SWITCHER | (off) | When `yes`, emits `text-orientation: upright` (otherwise CSS default `mixed`) |
| `eael_vto_writing_letter_spacing` (responsive) | SLIDER | — | `letter-spacing` |
| `eael_vto_writing_word_spacing` (responsive) | SLIDER | — | `word-spacing` |
| `eael_vto_writing_text_indent` (responsive) | SLIDER | — | `text-indent` |
| `eael_vto_writing_line_height` (responsive) | SLIDER | — | `line-height` |
| `eael_vto_writing_styling_heading` | HEADING | — | Section divider |
| `eael_vto_writing_styling_type` | SELECT | `normal` | `normal` / `background` / `gradient` — gates the styling sub-controls |
| `eael_vto_writing_styling_background` (group) | Group Control Background | — | Classic background image / color picker, applied with `background-clip: text` |
| `eael_vto_writing_gradient_color_repeater` | REPEATER | 2 default stops (`#7C62FF` @ 50%, `#FF6464` @ 90%) | Gradient stops; each row has color + location |
| `eael_vto_writing_styling_text_clip` | SWITCHER | `yes` | When on, `background-clip: text; color: transparent` (only for background style + classic background) |
| `eael_vto_writing_styling_text_animation_bg` | SWITCHER | (off) | When on for background style, applies `eaelAnimationVTO` 30s loop |
| `eael_vto_writing_text_animation_control` | CHOOSE | `vertical` | Gradient direction: `horizontal` / `vertical` |
| `eael_vto_writing_gradient_color_angle` (responsive) | SLIDER | 95 deg | Horizontal-direction gradient angle |
| `eael_vto_writing_styling_text_animation` | SWITCHER | (off) | When on for gradient-horizontal, applies `eaelAnimationVTO` 5s loop |
| `eael_vto_writing_gradient_color_angle_vertical` (responsive) | SLIDER | 2 deg | Vertical-direction gradient angle |
| `eael_vto_writing_styling_text_animation_vertical` | SWITCHER | (off) | When on for gradient-vertical, applies `eaelAnimationVertical` 5s loop |

### Filters

| Filter | Where fired | Purpose |
| ------ | ----------- | ------- |
| `eael/registered_extensions` | [`Bootstrap.php:114`](../../includes/Classes/Bootstrap.php#L114) | Remove `vertical-text-orientation` from the registry to disable the extension entirely |

The extension exposes no `eael/vto/*` filters of its own.

### Activation

Standard EA extension activation — slug `vertical-text-orientation` must be in `eael_save_settings`. Default-enabled on fresh installs via `Core::set_default_values`. Toggle via EA Settings → Extensions.

## Customization Recipes

### Recipe 1 — Apply VTO to additional widget types

Hook the same callback into another `after_section_end` action. From a theme `functions.php`:

```php
add_action( 'plugins_loaded', function () {
    if ( class_exists( '\Essential_Addons_Elementor\Extensions\Vertical_Text_Orientation' ) ) {
        $instance = new \Essential_Addons_Elementor\Extensions\Vertical_Text_Orientation();
        add_action(
            'elementor/element/eael-advanced-heading/section_title_style/after_section_end',
            [ $instance, 'register_controls' ]
        );
    }
}, 200 );
```

You'll also need CSS for the new widget's text selector(s) so writing-mode actually applies — the current 12-selector list is widget-specific.

### Recipe 2 — Disable the gradient animation site-wide

Pure CSS override:

```css
.eael_vto-vertical-lr .elementor-heading-title,
.eael_vto-vertical-rl .elementor-heading-title,
.eael_vto-vertical-lr .elementor-headline,
.eael_vto-vertical-rl .elementor-headline,
.eael_vto-vertical-lr .elementor-text-editor p,
.eael_vto-vertical-rl .elementor-text-editor p {
    animation: none !important;
}
```

The extension provides no PHP-side filter for animation suppression.

### Recipe 3 — Pre-fill gradient stops with brand colours

```php
add_action( 'elementor/element/heading/section_title_style/after_section_end', function ( $element ) {
    $element->update_control( 'eael_vto_writing_gradient_color_repeater', [
        'default' => [
            [ 'eael_vto_writing_gradient_color' => '#0c5cff', 'eael_vto_writing_gradient_color_location' => [ 'unit' => '%', 'size' => 0 ] ],
            [ 'eael_vto_writing_gradient_color' => '#ff5e00', 'eael_vto_writing_gradient_color_location' => [ 'unit' => '%', 'size' => 100 ] ],
        ],
    ] );
}, 20 );
```

Priority 20 so this runs after the extension's own `register_controls` at priority 10.

### Recipe 4 — Suppress the extension entirely

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['vertical-text-orientation'] );
    return $exts;
} );
```

## Common Issues

### Panel doesn't appear on an EA widget

- **Cause:** Only the five hooked widget types receive the panel (Heading, Text Editor, Animated Headline, Dual Color Header, Fancy Text). Other EA widgets — Advanced Heading, Creative Button, etc. — were not wired. Add a hook via Recipe 1 if needed.

### Writing mode applies but text doesn't actually rotate

- **Likely cause:** Widget content is inside a non-targeted child (e.g. a custom inner element). The 12-selector list covers `.elementor-heading-title`, `.elementor-headline`, `.elementor-text-editor p`, `.eael-dual-header`, `.eael-fancy-text-container`. Other inner structures aren't styled.
- **Fix:** Either add custom CSS for your inner element, or wrap the content in a supported element.

### Gradient text shows solid color instead of gradient

- **Likely cause 1:** Browser doesn't support `-webkit-background-clip: text` + `color: transparent` — primarily a very old Firefox or IE issue. Modern Chrome / Edge / Safari / Firefox all support it.
- **Likely cause 2:** The repeater has zero stops or one stop with no color. `before_render` skips emitting the `data-gradient_colors` attribute when the array is empty; the JS bails on missing data and the inline style isn't applied.
- **Likely cause 3:** `eael_vto_writing_styling_type` is `background`, not `gradient` — the repeater is ignored under background mode.

### Animation runs but looks janky

- **Cause:** The animation animates `background-position` between `0` and `100%`. With certain gradient stops, the visible color shift is very subtle.
- **Fix:** Increase the gradient color contrast or shorten the animation duration in `src/css/view/vertical-text-orientation.scss` (currently 5s for gradient, 30s for background). Source change requires `npm run build`.

### Flip rotates the text but breaks alignment inside the column

- **Cause:** `transform: rotate(180deg)` rotates around the element's center. If the widget's box is taller than the rotated text content, the rotated text appears off-anchor.
- **Fix:** Use `inline-size` (the Height control) to constrain the widget's vertical extent in writing-mode space, or align the column with flex.

### Editor preview doesn't show gradient until reload

- **Cause:** The editor-mode walk in `src/js/view/vertical-text-orientation.js` runs at `elementor/frontend/init` and again per widget render. If you change repeater stops without triggering a widget re-render, the inline style is stale.
- **Fix:** Click the widget to refresh its handle in the editor; this re-fires `frontend/element_ready/widget`.

## Debugging Guide

1. **Confirm activation.** `print_r( get_option('eael_save_settings') )` — `[vertical-text-orientation] => 1` should be present.
2. **Confirm constructor ran.** Add `error_log('VTO ctor')` at line 14 of the class file. Refresh any Elementor editor page; log line should fire.
3. **Confirm hook is wired to the right widget.** Open a Heading widget in the editor → Style tab. The "Vertical Text Orientation" panel should appear at the bottom. If it doesn't, the `after_section_end` hook name is wrong (compare with the actual hook your Elementor version fires — these are stable in Elementor 3.x but worth verifying).
4. **Confirm writing mode applies.** Toggle the switcher + writing-mode select on a Heading widget. The widget wrapper should gain `eael_vto-vertical-lr` (or `-rl`) class. If the class doesn't appear, `prefix_class` resolution failed — check Elementor version.
5. **Confirm `before_render` ran.** View page source on frontend; the wrapper should have `data-gradient_colors` if gradient mode is on. If missing, `before_render` either didn't fire (priority 100 hook conflict?) or the `eael_vto_writing_gradient_color_repeater` settings array is empty.
6. **Confirm frontend JS runs.** Browser console; `verticalTextOrientation` is a function variable. Type `window.eael` and check `elementStatusCheck` resolves. The `frontend/element_ready/widget` hook should fire for each widget render.
7. **Confirm gradient inline style applied.** Inspect the Heading title element → Computed → Background — should be `linear-gradient(95deg, #7C62FF 50%, #FF6464 90%)` -100% / 200%.
8. **Editor mode debug.** `window.elementor.elements.models[0].attributes.elements.models` — walk this tree to find your widget's id, then check `attributes.settings.attributes.eael_vto_writing_gradient_color_repeater`.

## Architecture Decisions

### Five widget-specific hooks instead of `_section_style/after_section_end`

- **Context:** Hooking the global Style-tab action would add the panel to every widget — even widgets where the 12 CSS selectors don't match anything (e.g. Button, Image). Users would see the panel but the styling wouldn't apply.
- **Decision:** Five explicit `after_section_end` hooks for the five widgets whose markup the CSS targets.
- **Alternatives rejected:** Global hook + conditional logic per-widget (more code, more confusion); per-widget extension classes (proliferation).
- **Consequences:** Adding support for a sixth widget type requires editing the constructor. The trade-off is correctness: users only see the panel where it works.

### Gradient via render-attributes + JS, not Elementor `selectors`

- **Context:** Elementor's `selectors` mechanism is great for static, fixed-shape CSS values. A gradient with N user-defined stops can't be expressed as a single `{{VALUE}}` placeholder.
- **Decision:** `before_render` JSON-encodes the repeater into a data attribute; frontend JS reads the attribute and applies a runtime-built `linear-gradient(…)` string.
- **Alternatives rejected:** Server-side CSS generation via an inline `<style>` block (would lose Elementor's CSS file caching); a fixed-count repeater (limits design flexibility).
- **Consequences:** Two parallel rendering paths (server-side `before_render` + client-side `frontend/element_ready/widget`). Editor preview needs its own path walking `window.elementor.elements.models` because `before_render` doesn't fire there.

### `prefix_class` on writing-mode select drives both class addition and selector

- **Context:** Two activation surfaces are needed: a class on the wrapper (so the bundled SCSS can target `.eael_vto-vertical-lr`), and a `writing-mode` CSS property emission.
- **Decision:** Use both Elementor mechanisms on the same control — `prefix_class => 'eael_vto-'` adds the class, while `selectors => '… writing-mode: {{VALUE}}'` emits the property.
- **Alternatives rejected:** Add class via `before_render` (more code); CSS-only via `selectors` (loses the SCSS bundle's `line-height: inherit` cascade override).
- **Consequences:** A single control change affects both DOM class and per-page CSS. Predictable.

### View-context assets (not edit-only like Reading Progress / Scroll to Top)

- **Context:** The gradient rendering needs JS at frontend (visitors must see the gradient). Reading Progress and Scroll to Top render via pure CSS at frontend and only need JS in the editor for preview-updates.
- **Decision:** Both `css` and `js` declared as `context => 'view'` in `config.php`. Editor reuses the same view JS via `window.isEditMode` detection.
- **Alternatives rejected:** Separate edit and view JS files (more build artifacts); inline `<script>` injection (cache miss).
- **Consequences:** One JS file does both jobs; the file is small enough (~190 lines) that the dual-mode complexity is acceptable.

## Known Limitations

- **Only five widget types supported.** Custom Elementor widgets or other EA widgets need an explicit hook addition (Recipe 1) + matching CSS selectors.
- **No `sideways-lr` / `sideways-rl` modes.** CSS Writing Modes Level 4 added these. Lite only ships the Level 3 `vertical-lr` and `vertical-rl`.
- **Selectors list is fragile.** The 12-line selector duplicated across ~10 controls. Changing the supported widget set requires editing every selector list — DRY violation but explicit.
- **Repeater colour stops have no transparency control.** Color input is `Controls_Manager::COLOR` (hex / rgba accepted by Elementor's color picker), but no separate alpha slider.
- **Gradient direction is binary (horizontal / vertical).** No diagonal-only flag; user must pick an angle within horizontal direction.
- **Animation duration is fixed at 5s / 30s.** Hard-coded in the SCSS keyframe rules; no per-widget control.
- **`-webkit-background-clip: text` is non-standard.** Now widely supported but still vendor-prefixed in CSS.
- **Editor-mode walk over `window.elementor.elements.models`** is O(N) over the entire page tree. On documents with many widgets this can be slow on first paint inside the editor.
- **No fallback for IE / old Edge.** The Edge of 5+ years ago doesn't support `background-clip: text`. The text appears transparent on those browsers — defensible since EA's overall browser support targets evergreen.

## Recent Significant Changes

No micro-changelog entries yet. Add entries when:

- New widget types are wired into the constructor's hook list
- The selectors list changes (e.g. EA Fancy Text refactor renames `.eael-fancy-text-container`)
- Gradient direction gains a third option (e.g. diagonal)
- Animation duration becomes a control
- A Pro override is introduced

Format: `version — description (#card)`.

## Cross-References

- **Architecture:** [`../architecture/extensions.md`](../architecture/extensions.md) — extension subsystem overview
- **Architecture:** [`../architecture/asset-loading.md`](../architecture/asset-loading.md) — how view-context assets flow through `Asset_Builder`
- **Architecture:** [`../architecture/editor-data-flow.md`](../architecture/editor-data-flow.md) — per-widget settings persistence
- **Sibling extension:** [`../extensions/promotion.md`](promotion.md) — canonical extension doc
- **Sibling extension:** [`scroll-to-top.md`](scroll-to-top.md) — different hook style (document-level vs widget-level)
- **Sibling extension (similar pattern):** the Hover Effect extension also uses `before_render` to add data-attrs read by view JS — see `includes/Extensions/Hover_Effect.php`
- **Public docs:** <https://essential-addons.com/docs/vertical-text-orientation/>
- **Rules:** [`../../.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md), [`../../.claude/rules/asset-pipeline.md`](../../.claude/rules/asset-pipeline.md)
