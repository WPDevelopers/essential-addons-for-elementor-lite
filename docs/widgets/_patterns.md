# Shared Widget Patterns

> Cross-widget patterns documented once. Per-widget docs reference the relevant sections instead of re-explaining the mechanics each time.

## How to use this doc

When a per-widget doc mentions one of these patterns:

```markdown
**Liquid Glass injection** — see [_patterns.md § Liquid Glass](_patterns.md#liquid-glass-injection-chain).
Selector target: `.eael-my-widget .overlay-active .overlay`. Front-only (no rear variant).
```

The widget doc only needs to say **what's different** (selector target, front-only or front+rear, etc.). The shared doc handles the **how it works** part.

---

## Liquid Glass injection chain

Pro extends Lite widgets with extra glassmorphism effects (Light Frost, Grain Frost, Fine Frost, plus noise distortion and SVG filter `<defs>`) via a fixed set of `do_action` injection points. Lite emits the actions inside `register_controls()` and inside `render()`; Pro registers listeners in [`Bootstrap::__construct`](../../../essential-addons-elementor/includes/Classes/Bootstrap.php#L73). When Pro is not active, the actions are no-ops.

### Lite-side mechanism

1. **`eael_wd_liquid_glass_effect_switch` SWITCHER** in Content tab unlocks a Style → Liquid Glass section conditional on the switch.
2. **Picker** (`eael_wd_liquid_glass_effect`) with `prefix_class = 'eael_wd_liquid_glass-'`. Five options:
   - `effect1` Heavy Frost — Lite (registered via `HelperTrait::eael_wd_liquid_glass_effect_bg_color_effect`)
   - `effect2` Soft Mist — Lite
   - `effect4` Light Frost — Pro
   - `effect5` Grain Frost — Pro
   - `effect6` Fine Frost — Pro
3. **Pro lock icon** appended to Effects 4/5/6 text via `eael_pro_lock_icon()` helper when `apply_filters('eael/pro_enabled', false)` returns false.
4. **Upsell raw-HTML control** (`eael_wd_liquid_glass_effect_pro_alert`) appears when Pro-locked effect selected without Pro.
5. **`apply_filters('eael_liquid_glass_effect_filter', $defaults)`** — third party can rename labels.

### Pro extension hooks

Lite emits these in `eael_liquid_glass_effects()` (or `_rear` variant):

| Hook | When emitted | Purpose |
| ---- | ------------ | ------- |
| `eael_wd_liquid_glass_effect_bg_color_effect4` / `_5` / `_6` | inside `register_controls()` | Pro registers bg-color controls for Effects 4–6 |
| `eael_wd_liquid_glass_effect_backdrop_filter_effect4` / `_5` / `_6` | inside `register_controls()` | Pro registers backdrop-filter controls for Effects 4–6 |
| `eael_wd_liquid_glass_effect_noise_action` | inside `register_controls()` | Pro adds noise-distortion controls |
| `eael_wd_liquid_glass_effect_svg_pro` | inside `render()` (per-element) | Pro injects inline `<svg>` with filter `<defs>` |

All four signatures are `(Widget_Base $widget, string $effect_or_settings, string $default_color, string $selector)` except `_noise_action` which is `(Widget_Base $widget)` and `_svg_pro` which is `(Widget_Base $widget, array $settings, string $selector)`.

### Rear variant (Flip Box only)

Flip Box has two visible faces, so it emits a parallel chain with `_rear` suffix:

| Front hook | Rear hook |
| ---------- | --------- |
| `eael_wd_liquid_glass_effect_bg_color_effect4/5/6` | `eael_wd_liquid_glass_effect_bg_color_rear_effect4/5/6` |
| `eael_wd_liquid_glass_effect_backdrop_filter_effect4/5/6` | `eael_wd_liquid_glass_effect_backdrop_filter_rear_effect4/5/6` |
| `eael_wd_liquid_glass_effect_noise_action` | `eael_wd_liquid_glass_effect_noise_action_rear` |
| `eael_wd_liquid_glass_effect_svg_pro` | `eael_wd_liquid_glass_effect_svg_pro_back` |

Pro registers listeners for both sets independently.

### What Lite renders when Pro is inactive

- Effects 1 + 2 work normally (Lite's `HelperTrait` provides the helpers).
- Effects 4 / 5 / 6 selected without Pro: the picker emits the corresponding `prefix_class` (`eael_wd_liquid_glass-effect4` etc.) on the wrapper, but no matching CSS rule fires because Pro's bg-color / backdrop-filter controls are not registered. The widget renders unstyled rather than visibly broken.

### Per-widget customisation

In each widget doc, only document:
- **Selector target** — what CSS selector the effect is applied to (e.g. `.eael-infobox-button`, `.eael-elements-flip-box-front-container`, `.eael-img-accordion .overlay-active .overlay`)
- **Front-only or front+rear** — which hook variants are emitted
- **Default background colour** passed to the helpers (usually `#FFFFFF1F`)

**Widgets using this pattern:** Info_Box, Creative_Button, Flip_Box (front + rear), Image_Accordion (front only), Cta_Box (Pro adds the section).

---

## FA4 → FA5 icon migration shim

Elementor's icon system migrated from Font Awesome 4 strings (e.g. `fas fa-home`) to the `ICONS` control type (an array with `value` and `library` keys). EA widgets that predate the migration have a legacy FA4 control coexisting with the new `ICONS` control to keep old saved widgets rendering correctly.

### Control pair

```php
// Legacy FA4 string control (NOT exposed in the panel any more — only saved data)
// Field id: $widget_icon  (e.g. `eael_infobox_icon`)

// New ICONS picker with FA4 compatibility map
$this->add_control(
    'eael_widget_icon_new',
    [
        'type'             => Controls_Manager::ICONS,
        'fa4compatibility' => 'eael_widget_icon',  // ← maps legacy field to this picker
        'default'          => [
            'value'   => 'fas fa-home',
            'library' => 'fa-solid',
        ],
    ]
);
```

Elementor's migration runner detects the legacy field, converts it to the new schema, and sets `$settings['__fa4_migrated']['eael_widget_icon_new'] = true`.

### Render branching

```php
$is_migrated = isset( $settings['__fa4_migrated']['eael_widget_icon_new'] );
$is_new_icon = empty( $settings['eael_widget_icon'] );

if ( $is_migrated || $is_new_icon ) {
    // Use new ICONS picker
    Icons_Manager::render_icon( $settings['eael_widget_icon_new'], [ 'aria-hidden' => 'true' ] );
} else {
    // Fall back to legacy FA4 string
    echo '<i class="' . esc_attr( $settings['eael_widget_icon'] ) . '"></i>';
}
```

The condition handles three cases safely:
- **Pre-migration old widget** — `__fa4_migrated` not set, `eael_widget_icon` has a string → falls through to legacy
- **Migrated widget** — `__fa4_migrated` set, both fields populated → new picker wins
- **New widget** — `__fa4_migrated` not set, `eael_widget_icon` empty → new picker wins (the `empty()` check)

### Uploaded SVG path

When the user uploads an SVG via the ICONS picker, the value structure changes:

```php
$settings['eael_widget_icon_new']['library'] === 'svg'
$settings['eael_widget_icon_new']['value'] === ['id' => 42, 'url' => '...']
```

Render must check for this case before calling `Icons_Manager::render_icon`:

```php
if ( isset( $settings['eael_widget_icon_new']['value']['url'] ) ) {
    echo '<img src="' . esc_url( $settings['eael_widget_icon_new']['value']['url'] ) . '" alt="..." />';
} else {
    Icons_Manager::render_icon( $settings['eael_widget_icon_new'], [ 'aria-hidden' => 'true' ] );
}
```

### Per-widget customisation

In each widget doc, only mention:
- **Field name** (e.g. "Icon uses the FA4 migration shim — old field `eael_widget_icon`, new picker `eael_widget_icon_new`")
- **Default value**
- Anything non-standard about the render branch

**Widgets using this pattern:** Fancy_Text, Creative_Button, Cta_Box, Info_Box, Flip_Box, Pricing_Table (button + per-item list icon), Feature_List (per-item), Tooltip, Image_Accordion (does NOT use — only image / icon / number variant without FA4 history).

---

## WPML media translation

WordPress Multilingual (WPML) keeps separate attachment IDs per language for the same logical image. EA widgets that embed media must translate the attachment ID to the active language before computing the URL, otherwise multilingual sites serve the wrong language's image.

### Pattern

```php
// Inside render()
if ( ! empty( $settings['eael_widget_image']['id'] ) ) {
    $settings['eael_widget_image']['id'] = apply_filters(
        'wpml_object_id',
        $settings['eael_widget_image']['id'],
        'attachment',          // object type — 'attachment' for media, 'wp_template' for saved templates
        true                   // return original ID if no translation exists
    ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

    if ( $settings['eael_widget_image']['id'] ) {
        // Re-derive URL because the translated attachment has a different URL
        $settings['eael_widget_image']['url'] = wp_get_attachment_url( $settings['eael_widget_image']['id'] );
    }
}
```

Both steps are required:
1. **Translate the ID** — `wpml_object_id` filter returns the translated ID (or the original if no translation exists).
2. **Re-derive the URL** — the saved settings have the original-language URL; only the ID is reliable.

### Why the `phpcs:ignore`

WPML's filter is `wpml_object_id` without the `eael/` prefix. EA's PHPCS config flags un-prefixed hook usage; the `phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound` annotation suppresses the warning for this specific line.

### Saved-template path

For widgets that embed Elementor templates (Cta_Box, Info_Box, Flip_Box content path):

```php
if ( ! is_array( $settings['eael_primary_templates'] ) ) {
    $settings['eael_primary_templates'] = apply_filters(
        'wpml_object_id',
        $settings['eael_primary_templates'],
        'wp_template',                       // ← different object type for templates
        true
    );
}
// Then:
echo Plugin::$instance->frontend->get_builder_content( $settings['eael_primary_templates'], true );
```

### Uploaded SVG case

When the icon is an uploaded SVG via the ICONS picker (`library === 'svg'`), the nested attachment ID also needs translation:

```php
if ( ! empty( $settings['eael_widget_icon_new']['value']['id'] ) ) {
    $settings['eael_widget_icon_new']['value']['id'] = apply_filters(
        'wpml_object_id',
        $settings['eael_widget_icon_new']['value']['id'],
        'attachment',
        true
    );
    if ( $settings['eael_widget_icon_new']['value']['id'] ) {
        $settings['eael_widget_icon_new']['value']['url'] = wp_get_attachment_url(
            $settings['eael_widget_icon_new']['value']['id']
        );
    }
}
```

### Per-widget customisation

In each widget doc, only mention:
- **Which fields are translated** (image, icon SVG upload, template ID, etc.)

**Widgets using this pattern:** Cta_Box (saved template), Info_Box (saved template), Flip_Box (front image + back image + front template + back template), Tooltip (image content + SVG icon upload).

---

## `has_pro` runtime handoff (Lite/Pro JS swap)

Some widgets ship distinct JavaScript implementations in Lite and Pro — Pro uses a vendor library (GSAP, ScrollTrigger, etc.) while Lite uses the native browser APIs. Both register on the same `frontend/element_ready/eael-<widget>.default` action; Lite's handler reads a `has_pro` flag from `data-settings` and short-circuits when Pro is active so only Pro's handler runs.

### PHP side

```php
// Inside render()
$widget_settings = [
    'speed'    => $settings['speed'],
    'duration' => $settings['duration'],
    // ... other widget options ...
    'has_pro'  => apply_filters( 'eael/pro_enabled', false ),
];

$this->add_render_attribute( 'wrapper', [
    'data-settings' => wp_json_encode( $widget_settings ),
] );
```

### JS side (Lite)

```js
var MyWidget = function ($scope, $) {
    let wrapper = $('.my-widget-container', $scope),
        settings = wrapper.data('settings');

    // Short-circuit when Pro is active — Pro's handler will run instead
    if (settings?.has_pro) {
        return false;
    }

    // ... Lite implementation ...
};

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelMyWidgetLite')) return false;
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-my-widget.default",
        MyWidget
    );
});
```

### JS side (Pro)

```js
var MyWidgetPro = function ($scope, $) {
    // ... Pro implementation (e.g. using GSAP) ...
};

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck('eaelMyWidgetPro')) return false;   // ← different flag
    elementorFrontend.hooks.addAction(
        "frontend/element_ready/eael-my-widget.default",
        MyWidgetPro
    );
});
```

### Why this pattern instead of class extension

- **No PHP coupling** — Pro doesn't subclass the Lite widget; Lite has no awareness of Pro at PHP load time.
- **Clean fallback** — when Pro is uninstalled, Lite continues working without any cleanup.
- **Independent guards** — Lite's and Pro's `elementStatusCheck` flags are different, so both can register without overwriting each other.
- **Third-party safety risk** — third-party handlers on the same action that don't check `has_pro` will double-fire. Document this in the per-widget doc.

### Per-widget customisation

In each widget doc, only mention:
- **Engine difference** (e.g. "Lite uses Web Animations API; Pro uses GSAP + ScrollTrigger")
- **`elementStatusCheck` flag names** (e.g. `eaelDrawSVG` Lite / `eaelDrawSVGPro` Pro)
- **Which capabilities are Pro-only at runtime** (e.g. easing types that fall back to linear in Lite)

**Widgets using this pattern:** SVG_Draw (only Display widget; possibly more in Interactive / WooCommerce categories — investigate when documenting those).

---

## `eael_section_pro` standard upsell panel

A standardised Pro upsell panel that appears at the end of the Content tab when Pro is not active.

### Lite-side mechanism

```php
if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
    $this->start_controls_section(
        'eael_section_pro',
        [
            'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite' ),
        ]
    );

    $this->add_control(
        'eael_control_get_pro',
        [
            'label'       => __( 'Unlock more possibilities', 'essential-addons-for-elementor-lite' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => [
                '1' => [
                    'title' => '',
                    'icon'  => 'fa fa-unlock-alt',
                ],
            ],
            'default'     => '1',
            'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
        ]
    );

    $this->end_controls_section();
}
```

### Behaviour

- When Pro is active, the entire section is omitted from the panel (the `if` skips registration).
- When Pro is inactive, the section appears as the last Content-tab section with a lock icon and a description linking to the EA Pro upgrade page.
- The control value (`eael_control_get_pro`) is saved like any other Elementor control but is purely decorative — no runtime behaviour depends on it.

### What goes in the per-widget doc

For widgets that have this section, include a row in the **Pro vs Lite** table:

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| ... | ... | ... |
| `eael_section_pro` upsell panel | shown | hidden |

If the widget does NOT have this section (Feature_List, Code_Snippet, SVG_Draw, Image_Accordion), call it out as a known limitation or design choice — Lite users get no in-panel Pro discovery surface for that widget.

**Widgets WITH the standard upsell:** Fancy_Text, Creative_Button, Cta_Box, Info_Box, Flip_Box, Pricing_Table, Tooltip.

**Widgets WITHOUT it:** Feature_List (no Pro features), Image_Accordion (only Liquid Glass section as upsell), Code_Snippet (no Pro features at all), SVG_Draw (Pro discovery via easing picker only, no panel).

---

## Empty `content_template()` stub

Elementor caches a Marionette JS template per widget for fast editor preview without an AJAX round-trip. When `content_template()` returns nothing, the editor falls back to server-side `render()` via AJAX — slower but exactly matches production output.

### Pattern

```php
class My_Widget extends Widget_Base {
    // ... register_controls(), render() ...

    protected function content_template() {}   // explicitly empty
}
```

### When to use this stub

- **Complex render logic** that would be hard to mirror in JavaScript (saved-template content, conditional partials, FA4 migration shim).
- **Server-side data** that JS can't access (template rendering, WPML translation).
- **Render output that must match production exactly** (shortcode execution, dynamic-tag resolution).

### Trade-off

- **Pro:** editor preview is always accurate; no JS template duplication burden.
- **Con:** each settings change triggers `wp-admin/admin-ajax.php` — slower editor on large forms or slow networks.

### Per-widget customisation

In each widget doc, just one line:
- **"`content_template()` is empty — editor preview uses server-side `render()`"**

**Widgets using this pattern:** Feature_List, Tooltip.

---

## Recipes (snippets that work across widgets)

### Hide the `eael_section_pro` upsell site-wide

```php
add_filter( 'eael/pro_enabled', '__return_true', 99 );
```

⚠️ This filter is global — suppresses upsells on **every** EA widget, not just one. Use only when the entire site is policy-bound to never advertise Pro.

### Customise Liquid Glass picker labels

```php
add_filter( 'eael_liquid_glass_effect_filter', function ( $defaults ) {
    $defaults['styles']['effect1'] = __( 'Maximum Blur', 'my-theme' );
    return $defaults;
} );
```

Affects every widget that has a Liquid Glass picker — the filter fires once per widget instance per render.

### Strip script tags from any user-supplied HTML (defensive)

```php
$cleaned = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $user_html );
$cleaned = preg_replace( '#<script(.*?)>(.*?)</script#is', '', $cleaned );
```

The two passes handle both well-formed and truncated `<script>` attacks. SVG Draw uses this exact pattern.

---

## Documentation conventions

When writing a per-widget doc that uses one of these patterns, reference this file with the section anchor:

```markdown
**Liquid Glass injection** — see [_patterns.md § Liquid Glass injection chain](_patterns.md#liquid-glass-injection-chain).
Selector target: `.eael-my-widget .overlay-active .overlay`. Front-only.
```

This saves ~30–50 lines per widget doc (depending on how many patterns apply) and keeps the per-widget docs focused on what's **unique** to each widget.
