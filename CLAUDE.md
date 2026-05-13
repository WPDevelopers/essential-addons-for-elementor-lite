# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Essential Addons for Elementor (Lite) — a WordPress plugin providing 110+ widgets and extensions for the Elementor page builder. Main plugin file: `essential_adons_elementor.php`. Current version: 6.x.x. Requires PHP 7.0+, WordPress 5.0+, Elementor.

## Build Commands

```bash
npm run build   # Production (.min.js/.min.css) + dev assets + .pot file
npm run dev     # Production + watch mode
```

Sources in `src/` compile to `assets/front-end/`. Webpack discovers entries by globbing `src/js/view/*`, `src/js/edit/*`, `src/css/view/*`. Files prefixed `_` are partials (skipped as entry points).

## Architecture

### Bootstrap & Plugin Lifecycle

1. **Entry point** (`essential_adons_elementor.php`): Defines constants, loads `autoload.php` and `config.php`, hooks `Bootstrap` on `plugins_loaded`.
2. **Bootstrap** (`includes/Classes/Bootstrap.php`): Singleton composed via traits (`Core`, `Helper`, `Enqueue`, `Admin`, `Elements`, `Controls`, `Ajax_Handler`, `Login_Registration`, `Woo_Hooks`, …).
3. **Autoloading** (`autoload.php`): PSR-4 `Essential_Addons_Elementor\` → `includes/`.

### Element/Widget System

- **Config registry** (`config.php`): Central map of every element — maps slug → PHP class path, and declares CSS/JS dependencies per widget. This is the single source of truth for what assets each widget needs.
- **Widget classes** (`includes/Elements/`): ~65 widgets, each extending Elementor's base widget class. Standard Elementor widget structure: metadata, `register_controls()`, `render()`.
- **Extensions** (`includes/Extensions/`): Supplementary features (Table of Content, Hover Effect, Post Duplicator, Image Masking, etc.) that augment Elementor rather than adding standalone widgets.

### Trait-Based Composition

The Bootstrap class uses traits in `includes/Traits/` to organize functionality: `Admin`, `Ajax_Handler`, `Elements`, `Controls`, `Helper`, `Core`, `Enqueue`, `Login_Registration`, `Woo_Hooks`, etc. When adding cross-cutting functionality, add or extend a trait rather than bloating Bootstrap directly.

### Asset Pipeline

- **Source**: `src/css/view/*.scss` (SCSS), `src/js/view/*.js` and `src/js/edit/*.js` (ES6+)
- **Output**: `assets/front-end/css/view/`, `assets/front-end/js/view/`, `assets/front-end/js/edit/`
- **Build**: Webpack 4 with Babel (preset-env), Sass, PostCSS (autoprefixer), MiniCssExtractPlugin
- **Asset loading**: `Asset_Builder` class (`includes/Classes/`) dynamically enqueues only the CSS/JS needed for widgets present on a page, driven by `config.php`
- Third-party libraries live in `assets/front-end/js/lib-view/` (not built by webpack)

### Elementor-Provided Assets — Do NOT Re-bundle

Elementor registers several libraries as WordPress handles. Always depend on those handles rather than bundling duplicate files.

| Library | Handle | Notes |
|---------|--------|-------|
| Swiper v8 | `swiper` (CSS), lazy JS via `elementorFrontend.utils.swiper` | Container class is `.swiper` not `.swiper-container` |
| Font Awesome 5 | `font-awesome-5-all` | Already enqueued by Elementor |

**Rules:**
- CSS: declare the handle in the widget's `get_style_depends()`. Do **not** add a duplicate `file` entry in `config.php`.
- JS: use `elementorFrontend.utils.swiper` async loader. Do **not** enqueue `swiper.min.js` manually.
- Lite's own `swiper-bundle.min.css` in `assets/front-end/css/lib-view/swiper/` is a standalone fallback for contexts where Elementor may not load it. Pro widgets should use the `swiper` handle instead.

### Adding a New Widget

1. Create the widget class in `includes/Elements/YourWidget.php` under namespace `Essential_Addons_Elementor\Elements`
2. Add source SCSS in `src/css/view/` and JS in `src/js/view/` if needed
3. Register the widget and its asset dependencies in `config.php`
4. Run `npm run build`

## Key Files

| File | Purpose |
|------|---------|
| `config.php` | Element registry — all widgets, their classes, CSS/JS deps |
| `essential_adons_elementor.php` | Plugin entry point, constants |
| `includes/Classes/Bootstrap.php` | Main controller (singleton + traits) |
| `includes/Classes/Asset_Builder.php` | Dynamic per-page asset loading |
| `autoload.php` | PSR-4 autoloader |
| `webpack.config.js` | Build configuration |

## Architecture Documentation

Deep-dive subsystem documentation lives in [`docs/architecture/`](docs/architecture/). Read these when tracing bugs that span multiple files, designing cross-cutting features, or understanding hook timing:

- [`docs/architecture/README.md`](docs/architecture/README.md) — system map (4 render phases + AJAX flow) and per-doc index
- [`docs/architecture/asset-loading.md`](docs/architecture/asset-loading.md) — `Asset_Builder` lifecycle, `config.php` registry, popup/template/shortcode detection, caching, CSS print modes
- [`docs/architecture/editor-data-flow.md`](docs/architecture/editor-data-flow.md) — settings persistence, `$settings` shape (Repeater / Group / Responsive), `condition` vs `conditions`, dynamic tags, `eael_e_optimized_markup()`
- [`docs/architecture/admin-notices.md`](docs/architecture/admin-notices.md) — active `bfcm-pointer.php` campaign and dormant `WPDeveloper_Notice` class infrastructure, dismissal lifecycle, how to add a new campaign notice
- [`docs/architecture/quick-setup.md`](docs/architecture/quick-setup.md) — React-based onboarding wizard (`eael-setup-wizard`), Vite build pipeline, three AJAX endpoints, lifecycle option states, how to add a new wizard step
- [`docs/architecture/extensions.md`](docs/architecture/extensions.md) — `includes/Extensions/` subsystem (11 plain PHP classes that augment Elementor elements), registration loop, `'context' => 'edit'` vs `'view'`, the Promotion upsell pattern, how to author a new extension. Per-extension docs live in [`docs/extensions/`](docs/extensions/) (canonical example: [`docs/extensions/promotion.md`](docs/extensions/promotion.md))
- [`docs/architecture/dynamic-data/`](docs/architecture/dynamic-data/) — folder of seven docs: AJAX endpoint inventory, WP_Query construction, load-more / pagination, Login & Registration, WooCommerce integration, third-party integrations

For per-widget documentation, see [`docs/widgets/`](docs/widgets/) ([`fancy-text.md`](docs/widgets/fancy-text.md) is the canonical example).

For Claude Code skills, commands, and team usage guidance, see [`.claude/README.md`](.claude/README.md).

## Dependencies

- **PHP**: `priyomukul/wp-notice` (via Composer)
- **JS**: `@wordpress/hooks`, `axios` (via npm)

## Text Domain

`essential-addons-for-elementor-lite` — all user-facing strings must use this domain.

## E2E Testing

```bash
npm run test:setup   # First-time setup
npm run test:reset   # Clean slate
npm run test:e2e     # Run all specs
```

Site: `http://localhost:8888` — WP admin: `admin` / `password`

**Always verify fixes visually on the test site before marking a task done.**

## Rules

@.claude/rules/widget-development.md
@.claude/rules/php-standards.md
@.claude/rules/asset-pipeline.md
@.claude/rules/testing.md
