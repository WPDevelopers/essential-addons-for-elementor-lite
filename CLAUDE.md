# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Essential Addons for Elementor (Lite) — a WordPress plugin providing 110+ widgets and extensions for the Elementor page builder. Main plugin file: `essential_adons_elementor.php`. Current version: 6.5.13. Requires PHP 7.0+, WordPress 5.0+, Elementor.

## Build Commands

```bash
# Build both production (.min.js/.min.css) and development (.js/.css) assets
npm run build

# Build production and watch for changes
npm run dev
```

All source files are in `src/` and compile to `assets/front-end/`. Webpack dynamically discovers entry points by globbing `src/js/view/*`, `src/js/edit/*`, and `src/css/view/*`. Files prefixed with `_` are treated as partials and skipped as entry points.

Production builds also generate the `.pot` translation file.

## Architecture

### Bootstrap & Plugin Lifecycle

1. **Entry point** (`essential_adons_elementor.php`): Defines constants, loads `autoload.php` and `config.php`, hooks `Bootstrap` on `plugins_loaded`.
2. **Bootstrap** (`includes/Classes/Bootstrap.php`): Singleton that composes functionality via traits (Core, Helper, Enqueue, Admin, Elements, Controls, Ajax_Handler, etc.).
3. **Autoloading** (`autoload.php`): PSR-4 mapping `Essential_Addons_Elementor\` → `includes/`.

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

## Dependencies

- **PHP**: `priyomukul/wp-notice` (via Composer)
- **JS**: `@wordpress/hooks`, `axios` (via npm)

## Text Domain

The plugin text domain is `essential-addons-for-elementor-lite`. All user-facing strings must use this domain for i18n.

## E2E Testing

Run `npm run test:setup` (first time) or `npm run test:reset` (clean slate), then `npm run test:e2e`.
Site runs at `http://localhost:8888` — admin at `/wp-admin` with `admin` / `password`.

**Always verify fixes visually on the test site before marking a task done.**

To add a widget test: drop a JSON template in `tests/e2e/templates/`, register the page in `seed.sh`, add a spec in `tests/e2e/specs/`.
