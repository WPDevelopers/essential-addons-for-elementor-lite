# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Essential Addons for Elementor (Lite) — a WordPress plugin providing 110+ widgets and extensions for the Elementor page builder. Main plugin file: `essential_adons_elementor.php`. Current version: 6.6.0. Requires PHP 7.0+, WordPress 5.0+, Elementor.

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

- **Config registry** (`config.php`): slug → PHP class path + CSS/JS deps. Single source of truth for asset dependencies.
- **Widget classes** (`includes/Elements/`): ~65 widgets extending Elementor's base. Structure: `register_controls()` + `render()`.
- **Extensions** (`includes/Extensions/`): Features that augment Elementor (Table of Content, Hover Effect, Image Masking, …).

### Adding a New Widget

See detailed steps in `.claude/rules/widget-development.md`, or run `/new-widget WidgetName`.

## Key Files

| File                                 | Purpose                                              |
| ------------------------------------ | ---------------------------------------------------- |
| `config.php`                         | Element registry — all widgets, classes, CSS/JS deps |
| `essential_adons_elementor.php`      | Plugin entry point, constants                        |
| `includes/Classes/Bootstrap.php`     | Main controller (singleton + traits)                 |
| `includes/Classes/Asset_Builder.php` | Dynamic per-page asset loading                       |
| `autoload.php`                       | PSR-4 autoloader                                     |
| `webpack.config.js`                  | Build configuration                                  |

## Dependencies

- **PHP**: `priyomukul/wp-notice` (Composer)
- **JS**: `@wordpress/hooks`, `axios` (npm)

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
