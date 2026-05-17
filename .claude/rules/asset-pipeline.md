---
description: Asset build pipeline, SCSS/JS conventions
paths:
  - "src/**/*"
  - "assets/front-end/**/*"
  - "webpack.config.js"
---

# Asset Pipeline

## Build Commands

```bash
npm run build   # Production (.min.js / .min.css) + dev assets + .pot file
npm run dev     # Production + watch mode
```

## Source → Output Map

| Source                    | Output                                      |
|---------------------------|---------------------------------------------|
| `src/css/view/*.scss`     | `assets/front-end/css/view/*.min.css`       |
| `src/js/view/*.js`        | `assets/front-end/js/view/*.min.js`         |
| `src/js/edit/*.js`        | `assets/front-end/js/edit/*.min.js`         |

Webpack auto-discovers entry points by globbing those dirs. Files prefixed `_` are partials — they are imported by others, not compiled directly.

## Third-Party Libraries

Live in `assets/front-end/js/lib-view/` — **not built by webpack**. Copy vendor files here manually; declare them as script dependencies in `config.php`.

## SCSS Conventions

- Variables: `src/css/view/_variables.scss`
- Mixins: `src/css/view/_mixins.scss`
- Widget root class: `.eael-{widget-slug}`
- BEM: `.eael-widget__element--modifier`
- RTL: use logical properties or provide `.eael-rtl` overrides

## JS Conventions

- ES6+ modules; Babel transpiles for browser compatibility
- Widget JS initialised via jQuery document-ready or `elementorFrontend.hooks`
- Use `@wordpress/hooks` for WP-flavoured `addAction` / `addFilter` in JS
- Edit-mode scripts (`.js` in `src/js/edit/`) run inside Elementor editor only

## After Editing Sources

Always run `npm run build` before committing — the repo tracks compiled assets.
