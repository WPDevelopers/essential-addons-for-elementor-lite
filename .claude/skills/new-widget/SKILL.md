---
name: new-widget
description: Scaffold a new Essential Addons widget end-to-end — gather requirements, generate PHP class, SCSS, optional JS, register in config.php, optional Pro upsell, build, and verify. Use when adding any new widget, even simple display ones. Outputs files that already follow EA conventions (text domain, BEM, Asset_Builder deps, render method) so the next session can start with controls + render logic, not boilerplate.
---

# New Widget Scaffold

## When to Invoke

- "Create a new widget called X" / "Scaffold widget Y"
- Spinning up the file skeleton before designing controls
- After a FluentBoards card requests a new widget

## Phase 1 — Gather Requirements

Before generating any file, ask the user:

1. **Widget class name** (PascalCase with underscores, e.g. `Marquee_Text`). Derive slug = lowercase + hyphens (`marquee-text`).
2. **Frontend interactivity?** — does the widget need JS at view time (animations, swiper, ticker, ajax)?
3. **Edit-mode JS?** — rare; only if the widget needs custom behavior inside the Elementor editor.
4. **Pro-shared?** — will Pro inject additional styles or options? If yes, include the Pro upsell pattern in Phase 7.
5. **Vendor libraries?** — needs Swiper, GSAP, Typed.js, etc.? See [.claude/rules/asset-pipeline.md](../../rules/asset-pipeline.md) — prefer Elementor's `swiper` handle over a bundled copy.
6. **Initial controls** — what's the minimum set to make the widget render? (Content + 1–2 style controls.)

Don't scaffold without the class name. Don't guess interactivity — ask.

## Phase 2 — PHP Class

Create `includes/Elements/{ClassName}.php`:

- `namespace Essential_Addons_Elementor\Elements;` + `if (!defined('ABSPATH')) exit;`
- `use \Elementor\Controls_Manager; use \Elementor\Widget_Base;` (add `Group_Control_*` and `Repeater` only if Phase 1 needs them)
- `class {ClassName} extends Widget_Base`
- `get_name()` → `'eael-{slug}'`
- `get_title()` → `esc_html__('{Human Name}', 'essential-addons-for-elementor-lite')`
- `get_icon()` → `'eaicon-{slug}'` (placeholder; replace with real eicon-* if no custom icon)
- `get_categories()` → `['essential-addons-elementor']`
- `get_keywords()` → 4–6 relevant terms
- `get_custom_help_url()` → `'https://essential-addons.com/elementor/docs/{slug}/'`
- `register_controls()` — one Content section with the user's initial controls; one Style section
- `render()` — `$settings = $this->get_settings_for_display();` then output `<div class="eael-{slug}"> ... </div>` (single root, scoped class)

Use `add_render_attribute()` for any user-controlled attribute. Escape every output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).

## Phase 3 — SCSS

Create `src/css/view/{slug}.scss`:

```scss
@import "variables";   // if needed
@import "mixins";      // if needed

.eael-{slug} {
    // Root styles only. Children use BEM: .eael-{slug}__element--modifier
}
```

Don't define `@keyframes` inside selectors — keep them top-level.

## Phase 4 — Frontend JS (only if Phase 1 said yes)

Create `src/js/view/{slug}.js`:

```js
var {ClassName} = function ($scope, $) {
    var $widget = $scope.find(".eael-{slug}").eq(0);
    if (!$widget.length) return;

    // widget logic here
};

jQuery(window).on("elementor/frontend/init", function () {
    if (eael.elementStatusCheck("eael{ClassName}Load")) { return false; }
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-{slug}.default", {ClassName});
});
```

The `eael.elementStatusCheck` guard prevents double-init if the page re-fires `elementor/frontend/init` (SPA navigation, popups).

## Phase 5 — Edit JS (only if Phase 1 said yes)

Create `src/js/edit/{slug}.js` only when the widget needs editor-only behavior (custom panel UI, preview hooks). Most widgets don't need this.

## Phase 6 — Register in `config.php`

Add an entry under the elements map:

```php
'{slug}' => [
    'class' => '\Essential_Addons_Elementor\Elements\{ClassName}',
    'dependency' => [
        'css' => [
            [
                'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/{slug}.min.css',
                'type'    => 'self',
                'context' => 'view',
            ],
        ],
        'js' => [
            // vendor libs first (if any), then self
            [
                'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/{slug}.min.js',
                'type'    => 'self',
                'context' => 'view',
            ],
        ],
    ],
],
```

`Asset_Builder` reads this to enqueue only what's needed per page. Skip the `js` dep block if the widget has no JS.

## Phase 7 — Pro Upsell Section (only if Phase 1 said Pro-shared)

In `register_controls()`, after the main sections:

```php
if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
    $this->start_controls_section( 'eael_section_pro', [
        'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite' ),
    ] );
    // Single CHOOSE control with description linking to upgrade page.
    // See Fancy_Text.php:299–324 for the boilerplate.
    $this->end_controls_section();
}
```

For Pro-injected style options, expose a **prefixed** filter: `apply_filters( 'eael/{slug}_style_types', $defaults )` — never un-prefixed.

## Phase 8 — Build & Verify

```bash
npm run build
```

Confirm no webpack errors. Verify outputs exist:
- `assets/front-end/css/view/{slug}.min.css`
- `assets/front-end/js/view/{slug}.min.js` (only if Phase 4)

Open Elementor editor on `http://localhost:8888`, drop the widget on a page with default settings, confirm:
- Widget appears in the panel under "Essential Addons" category
- Default render is sensible (no PHP notices in `wp-content/debug.log`)
- Style controls update preview live

## Phase 9 — Optional Playwright Spec

Per [.claude/rules/testing.md](../../rules/testing.md), if the widget is non-trivial, scaffold a regression test:

1. Save a page with the widget at default settings, export Elementor JSON → `tests/e2e/templates/{slug}.json`
2. Register the page in `tests/e2e/utils/seed.sh`
3. Create `tests/e2e/specs/{slug}.spec.ts` with a single "renders without errors" assertion
4. Run `npm run test:e2e` — confirm green

## Output Report

When done, list every file created or modified with full paths, plus the exact commands run (`npm run build`, etc.). Note anything skipped (e.g. "no JS — Phase 4 skipped, Phase 1 said static widget").

## Operating Rules

1. **Class name first, then nothing else.** Don't generate any file until Phase 1 is fully answered.
2. **Slug derived from class name** — every file (PHP, SCSS, JS, config key, CSS class) uses the same kebab slug. No drift.
3. **Single root `<div class="eael-{slug}">`** in `render()` — no sibling divs, no `clearfix` artifacts.
4. **Build before declaring done.** A scaffolded widget without `npm run build` is not done.
5. **No vendor libraries unless asked.** Phase 1 must explicitly mention them; don't bundle Swiper / GSAP / etc. by default.
6. **Defaults must render acceptably.** Drop the widget on a page with zero configuration — it should look fine.
