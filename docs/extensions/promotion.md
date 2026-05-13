# Promotion Extension

> The Pro upsell injection layer — adds "teaser" control sections inside Elementor for each Pro-only feature, visible only when the Pro plugin is **not** active. Force-enabled regardless of `eael_save_settings`.

**Class file:** [`includes/Extensions/Promotion.php`](../../includes/Extensions/Promotion.php) (221 lines)
**Slug:** `promotion` (`config.php` `extensions` key)
**Public docs:** N/A — this extension is internal-only; no end-user-facing functionality.
**Pro-shared:** ⚠️ **Lite-only by design** — the constructor short-circuits when Pro is active, so this class effectively does nothing in Pro.

---

## Overview

`Promotion` is the canonical pattern for "I want to advertise a Pro feature inside Lite without any of that feature's actual code being present". It instantiates on every Lite installation, hooks Elementor's element-controls cycle for several element types (sections, columns, containers, common-element style, document settings), and emits styled "Meet EA <Feature>" panels with an upgrade button.

When the Pro plugin is active, `apply_filters('eael/pro_enabled', false)` returns true, the constructor returns early, and `Promotion` wires no hooks. The class still instantiates, but it does no work — invisible to the user.

The features advertised by this single extension cover the breadth of EA Pro: Parallax, Particles, Content Protection, Advanced Tooltip, Conditional Display, Smooth Animation (Interactive Animations), Custom Cursor. Image Masking and other features have moved to their own extensions; new Pro upsells should be added here when they don't justify a dedicated extension.

## Features

What `Promotion` adds to Elementor's UI when active:

- **Parallax** panel — under Section's Layout tab
- **Particles** panel — under Section's Layout tab
- **Content Protection** panel — under common element's Style tab
- **Advanced Tooltip** panel — under common element's Style tab
- **Conditional Display** panel — under common element's Style tab + Section / Column Advanced tabs
- **Interactive Animations** (Smooth Animation) panel — under common element's Style tab + Column Advanced tab
- **Custom Cursor** panel — under common element's Style tab + Section / Column Advanced tabs + Container's Layout tab + Document settings (page-level)

Each panel contains exactly one control of type `Controls_Manager::RAW_HTML` whose `raw` is the `teaser_template` HTML — title, body, and an upgrade-to-Pro CTA.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Class instantiates | ✅ | ✅ |
| Constructor wires upsell hooks | ✅ | ❌ (short-circuits) |
| User sees teaser panels | ✅ | ❌ (real Pro features render in same slots) |
| Real Parallax / Particles / Tooltip / etc. | ❌ | ✅ (provided by Pro plugin's own extensions) |

The teasers and the real features occupy the same Elementor controls slots. When Pro is installed, the real features take over because Pro's own classes hook the same `after_section_end` actions earlier (priority typically lower than 10, or ordering luck).

## Use Cases

- A site builder evaluating EA Lite sees the panels and clicks through to the upgrade page
- An existing Lite user discovers a feature exists in Pro
- An agency client browsing the editor sees Pro features and asks the agency to upgrade

The extension is **purely marketing**, with no functional behaviour.

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Extensions/Promotion.php`](../../includes/Extensions/Promotion.php) | The single class — constructor + per-feature teaser methods + shared `teaser_template` factory |
| [`config.php`](../../config.php) line 1338 (extensions key) | Registry entry: `'promotion' => [ 'class' => '\Essential_Addons_Elementor\Extensions\Promotion' ]`. No `dependency` block — the extension has no assets. |
| [`includes/Traits/Elements::register_extensions`](../../includes/Traits/Elements.php#L101) | The instantiation loop — `array_push($active_elements, 'promotion')` force-enables this class regardless of `eael_save_settings` |
| `assets/admin/images/icon-ea-new-logo.svg` | Logo used in the teaser HTML |

The class has zero asset dependencies — its UI is rendered by Elementor's RAW_HTML control, with inline CSS classes handled by the EA editor stylesheet.

## Architecture

- **Force-enabled by design.** [`Elements::register_extensions:104`](../../includes/Traits/Elements.php#L104) explicitly pushes `'promotion'` into the active list before iterating. This means Promotion runs even if a user disables every other element + extension. The rationale: marketing should always be visible to non-Pro users; user toggle of Promotion would defeat its purpose.
- **Pro short-circuit in the constructor.** Line 13: `if (! apply_filters('eael/pro_enabled', false))`. When Pro is active, this filter returns true, the negation makes the condition false, the constructor body is skipped, no hooks are wired. Pro can leverage the same hook slots without conflict.
- **Single class, multiple hooks.** Rather than splitting each Pro feature into its own extension, `Promotion` aggregates upsells. This keeps Lite's `includes/Extensions/` folder small, makes it obvious where new upsells should be added (one constructor, one method per feature).
- **Shared `teaser_template($texts)` factory.** Each feature's teaser method calls this with `title` + `messages` arguments, gets back the styled HTML. New upsells reuse the factory — no per-feature HTML duplication.

## Render Output

When the constructor runs, no immediate output. Output happens later when Elementor fires the wired hooks. For the Parallax teaser specifically:

```html
<div class="elementor-control-section eael_ext_section_parallax_section">
    <div class="elementor-control-content">
        <div class="ea-nerd-box">
            <div class="ea-nerd-box-icon">
                <img src="{plugin_url}/assets/admin/images/icon-ea-new-logo.svg">
            </div>
            <div class="ea-nerd-box-title">Meet EA Parallax</div>
            <div class="ea-nerd-box-message">
                Create stunning Parallax effects on your site and blow everyone away.
            </div>
            <a class="ea-nerd-box-link elementor-button elementor-button-default"
               href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">
                Upgrade Essential Addons
            </a>
        </div>
    </div>
</div>
```

(Plus Elementor's section-header markup wrapping the panel.)

The CSS for `.ea-nerd-box`, `.ea-nerd-box-icon`, `.ea-nerd-box-title`, etc. is handled by EA's editor stylesheet (`assets/admin/css/editor.css` or similar). Rendering happens client-side in the editor — there is no frontend output for this extension.

## Controls Reference

The extension does not register controls in the user's traditional sense — each "control" it adds is a `RAW_HTML` placeholder whose only purpose is to display a marketing card. No control id is meaningful for users; they cannot configure or save anything.

Per-feature, each `Promotion` method calls:

```php
$element->start_controls_section(
    'eael_ext_<feature>_section',
    [
        'label' => __('<i class="eaicon-logo"></i> <Feature Name>', 'essential-addons-for-elementor-lite'),
        'tab'   => Controls_Manager::TAB_LAYOUT  // or TAB_ADVANCED, etc.
    ]
);

$element->add_control(
    'eael_ext_<feature>_pro_required',
    [
        'type' => Controls_Manager::RAW_HTML,
        'raw'  => $this->teaser_template([
            'title'    => __('Meet EA <Feature>', '...'),
            'messages' => __('<short pitch>', '...'),
        ]),
    ]
);

$element->end_controls_section();
```

The `eael_ext_<feature>_section` and `eael_ext_<feature>_pro_required` ids are not user-editable; they serve only Elementor's internal control registry.

## Conditional Dependencies

N/A — Promotion has no user-toggleable controls and no internal conditional logic between feature panels. Each panel renders unconditionally for the Lite user.

## Behavior Flow

1. **Plugin loads.** Bootstrap reads `$registered_extensions` from `config.php`.
2. **`register_extensions` runs.** Force-pushes `'promotion'` to active list. Calls `new Promotion()`.
3. **Constructor runs.** Checks `apply_filters('eael/pro_enabled', false)`.
   - If Pro active: filter returns true, constructor body skipped. No hooks wired. **End of execution.**
   - If Pro inactive: 16 `add_action` calls fire, hooking 7 distinct features into various Elementor element-controls actions.
4. **User opens Elementor editor.** Picks a section / column / container / widget.
5. **Elementor fires `after_section_end` hooks** as it builds the controls panel for the selected element.
6. **Each `Promotion` method runs** when its hook fires. Calls `start_controls_section` + `add_control` (RAW_HTML) + `end_controls_section`. The "panel" appears in the controls panel.
7. **User clicks the section header** to expand it. Sees the marketing card. Clicks "Upgrade Essential Addons".
8. **Browser navigates** to `https://wpdeveloper.com/upgrade/ea-pro`.

There is no save / publish / render path for Promotion — the panels exist only inside the editor.

## JavaScript Lifecycle

N/A — pure server-side extension; no JS contribution.

## Asset Dependencies

N/A — no `dependency` block in `config.php`. Marketing CSS is part of EA's editor stylesheet, loaded by other paths (admin enqueue). No frontend assets.

## Hooks & Filters

### Elementor hooks consumed (extension wires these)

| Hook | Priority | Method | Adds panel to |
| ---- | -------- | ------ | ------------- |
| `elementor/element/section/section_layout/after_section_end` | 10 | `section_parallax` | Section → Layout tab |
| `elementor/element/section/section_layout/after_section_end` | 10 | `section_particles` | Section → Layout tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `content_protection` | Every widget → Style tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `section_tooltip` | Every widget → Style tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `conditional_display` | Every widget → Style tab |
| `elementor/element/column/section_advanced/after_section_end` | 10 | `conditional_display` | Column → Advanced tab |
| `elementor/element/section/section_advanced/after_section_end` | 10 | `conditional_display` | Section → Advanced tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `smooth_animation` | Every widget → Style tab |
| `elementor/element/column/section_advanced/after_section_end` | 10 | `smooth_animation` | Column → Advanced tab |
| `elementor/element/common/_section_style/after_section_end` | 10 | `custom_cursor` | Every widget → Style tab |
| `elementor/element/column/section_advanced/after_section_end` | 10 | `custom_cursor` | Column → Advanced tab |
| `elementor/element/section/section_advanced/after_section_end` | 10 | `custom_cursor` | Section → Advanced tab |
| `elementor/element/container/section_layout/after_section_end` | 10 | `custom_cursor` | Container → Layout tab |
| `elementor/documents/register_controls` | 10 | `custom_cursor_page` | Document (page) settings |

Plus `eael/pro_enabled` filter consumed in the constructor for the early-return check.

### Hooks emitted

None. Promotion does not call `do_action` or `apply_filters`.

## Customization Recipes

### Recipe 1 — Add a new Pro feature teaser

```php
// Inside Promotion::__construct(), in the !pro_enabled block:
add_action(
    'elementor/element/common/_section_style/after_section_end',
    [ $this, 'my_new_pro_feature' ],
    10
);

// Add this method to the class:
public function my_new_pro_feature( $element ) {
    $element->start_controls_section(
        'eael_ext_my_new_feature_section',
        [
            'label' => __( '<i class="eaicon-logo"></i> My New Feature', 'essential-addons-for-elementor-lite' ),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        ]
    );

    $element->add_control(
        'eael_ext_my_new_feature_pro_required',
        [
            'type' => Controls_Manager::RAW_HTML,
            'raw'  => $this->teaser_template( [
                'title'    => __( 'Meet EA My New Feature', 'essential-addons-for-elementor-lite' ),
                'messages' => __( 'One-sentence pitch for the feature.', 'essential-addons-for-elementor-lite' ),
            ] ),
        ]
    );

    $element->end_controls_section();
}
```

The naming convention matches existing methods. Choose the right Elementor hook for which element type should show the teaser.

### Recipe 2 — Hide Promotion via a filter (testing only)

```php
add_filter( 'eael/registered_extensions', function ( $exts ) {
    unset( $exts['promotion'] );
    return $exts;
} );
```

This removes Promotion from the registry before `register_extensions` runs. Useful for screenshots / demos where the upsell is unwanted. Production sites should not do this — it removes the marketing surface entirely.

### Recipe 3 — Customize the upgrade URL

The URL is hardcoded inside `teaser_template`. Changing it requires editing [`Promotion.php:41`](../../includes/Extensions/Promotion.php#L41). No filter currently exposes the URL for runtime modification — adding one would be a small enhancement:

```php
// Suggested enhancement in Promotion::teaser_template
$upgrade_url = apply_filters(
    'eael/promotion/upgrade_url',
    'https://wpdeveloper.com/upgrade/ea-pro'
);
```

## Common Issues

### Teasers appear inside Pro

- **Likely cause:** `apply_filters('eael/pro_enabled', false)` returns false even though Pro is installed. Either Pro plugin isn't active, or its filter registration runs after Bootstrap's `register_extensions` (timing race).
- **Diagnose:** `var_dump( apply_filters( 'eael/pro_enabled', false ) )` early in admin pageview.
- **Fix:** Confirm Pro plugin activated. If timing-related, Pro should register the filter at `plugins_loaded` priority earlier than 100 (where Lite's Bootstrap hooks run).

### Teasers do not appear in Lite

- **Likely cause:** Pro plugin appears active even when it isn't (e.g. Pro symlink lingering). Or `eael_pro_enabled` filter is forced to `true` by another plugin.
- **Diagnose:** Same `var_dump` check. If true and Pro is genuinely not active, find the filter caller.
- **Fix:** Remove the offending filter or fix the symlink.

### Teaser appears with wrong text / outdated copy

- **Cause:** Strings are hardcoded in PHP. Translation also requires updating the i18n file.
- **Fix:** Edit the relevant `__('Meet EA …', '…')` string in `Promotion.php`. Run `npm run build` to regenerate `.pot`.

### Multiple extensions collide on the same hook slot

If Pro and Lite both hook `elementor/element/common/_section_style/after_section_end` with the same priority (10), the order of registration determines who runs first. Promotion's constructor registers in Lite Bootstrap (priority 100); Pro's equivalent registers later (priority 100+). The teaser may briefly flash before Pro's real panel replaces it. Visible only with browser dev tools open during editor load.

## Testing Checklist

After modifying Promotion:

- [ ] Lite-only environment (Pro deactivated): each documented teaser panel appears in Elementor editor for the matching element type.
- [ ] Pro environment: no teasers appear (real Pro features take their place).
- [ ] Click the "Upgrade Essential Addons" button: opens `https://wpdeveloper.com/upgrade/ea-pro` in a new tab.
- [ ] Translation: switch site to non-English locale, confirm strings translate (or fall back gracefully).
- [ ] No PHP notices in `wp-content/debug.log` while editing in Lite.
- [ ] No console errors on the editor page.
- [ ] `eael/registered_extensions` filter that removes `'promotion'`: confirm no teasers appear (smoke test for the suppression recipe).

## Architecture Decisions

### Single aggregator class instead of one extension per upsell

- **Context:** Each Pro feature could have its own extension class (e.g. `Parallax_Promo`, `Particles_Promo`, etc.). With seven advertised features, that would mean seven classes.
- **Decision:** One aggregator class with a method per feature. Constructor wires all the hooks.
- **Alternatives rejected:** Per-feature extension classes (proliferation; harder to find all upsells in one place); rendering all upsells from a single hook (loses the per-element targeting).
- **Consequences:** Adding a new upsell touches one file, one constructor, one method. Removing a feature when it ships in Lite means removing one method + one or more `add_action` calls. Keeps the surface small.

### Force-enable bypassing `eael_save_settings`

- **Context:** Marketing must always be visible to non-Pro users. If users could disable Promotion in EA settings, accidental disables would suppress the upsell.
- **Decision:** [`Elements::register_extensions:104`](../../includes/Traits/Elements.php#L104) `array_push`es `'promotion'` to the active list unconditionally.
- **Alternatives rejected:** Hide Promotion's toggle in UI (still possible to disable via DB / wp-cli); separate "always-on" registry (over-engineering for one extension).
- **Consequences:** The only suppression path is the `eael/registered_extensions` filter — documented as a Customization Recipe.

### Constructor short-circuit on Pro detection

- **Context:** When Pro is active, real Pro features render in the same controls slots. Promotion should silently step aside.
- **Decision:** First line of constructor checks `apply_filters('eael/pro_enabled', false)`. If true, return immediately. No hooks wired, no work done, but the class is still instantiated (cost: one empty constructor call).
- **Alternatives rejected:** Don't instantiate at all when Pro is active (would require `register_extensions` to know about Pro state — cross-cutting); hooks with conditional callbacks (each method would need its own Pro check, more code).
- **Consequences:** Trivial overhead in Pro (one no-op constructor). Clean separation between marketing surface and real feature surface.

### `Controls_Manager::RAW_HTML` for the teaser body

- **Context:** Need styled HTML inside an Elementor controls panel. Elementor's normal control types (TEXT, SELECT, etc.) don't fit a marketing card.
- **Decision:** Use `RAW_HTML` control. The panel becomes pure HTML with no save/load semantics.
- **Alternatives rejected:** Custom control type (over-engineering for marketing); HEADING control (doesn't support styled body + button).
- **Consequences:** No user state; users cannot interact with the "control" beyond clicking the link. Fine for this use case.

## Known Limitations

- **Hardcoded upgrade URL.** `wpdeveloper.com/upgrade/ea-pro` is in 1 file, 1 line. If the URL changes, requires a code edit. No filter exposed.
- **Hardcoded English copy.** All `__('...', '...')` strings are baked in. Translation requires regenerating `.pot` and translating per locale.
- **No analytics.** Click on "Upgrade Essential Addons" is just a regular link; no event dispatch, no tracking. Cannot measure upsell conversion at the codebase level.
- **No A/B testing.** Single hardcoded copy per feature. Cannot vary text per user / per region.
- **Hook priority race with Pro.** When Pro activates, Promotion still wires its hooks for the briefest moment of the same Lite request. Real Pro features take precedence visually, but the teaser HTML is briefly part of the page DOM.
- **Translation completeness.** Some Pro feature names are noun-phrase translations that may not work cleanly in other languages.
- **No suppression UI.** Recipe-2 filter is the only path; not exposed to non-developers.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when:

- A new Pro feature is added or removed
- Hook targets change (new element type, or move from one tab to another)
- The CTA URL or button text changes
- A new translation challenge surfaces

Format: `version — description (#card)`.
