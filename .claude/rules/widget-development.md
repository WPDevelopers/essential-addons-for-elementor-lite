---
description: Rules for creating and modifying EA widgets
paths:
  - "includes/Elements/**/*.php"
  - "src/js/view/*.js"
  - "src/css/view/*.scss"
  - "config.php"
---

# Widget Development Rules

## Creating a New Widget — Checklist

1. **PHP class** → `includes/Elements/YourWidget.php`
   - Namespace: `Essential_Addons_Elementor\Elements`
   - Extend `\Elementor\Widget_Base`
   - Implement: `get_name()`, `get_title()`, `get_icon()`, `get_categories()`, `register_controls()`, `render()`

2. **SCSS** → `src/css/view/your-widget.scss`
   - BEM naming: `.eael-your-widget__element--modifier`
   - Use existing SCSS variables from `src/css/view/_variables.scss`

3. **JS** (if interactive) → `src/js/view/your-widget.js`
   - Export a default function or class; webpack handles bundling

4. **Register in `config.php`**
   - Add to the element map: slug → class path
   - Declare CSS/JS deps; this drives `Asset_Builder` conditional loading

5. **Build** → `npm run build`

## Controls Best Practices

- Group related controls with `start_controls_section()` / `end_controls_section()`
- Use `start_controls_tabs()` for Normal/Hover states
- Prefix control IDs with the widget slug to avoid collisions
- Always add `'label_block' => true` for long labels

## Render Method

- Use `$this->get_settings_for_display()` — never `$this->get_settings()`
- Sanitize dynamic output: `esc_html()`, `esc_url()`, `wp_kses_post()`
- Wrap output in a single root element with class `eael-{widget-slug}`

## Asset Naming Convention

| Type   | Source                        | Output                              |
|--------|-------------------------------|-------------------------------------|
| CSS    | `src/css/view/widget.scss`    | `assets/front-end/css/view/widget.min.css` |
| JS     | `src/js/view/widget.js`       | `assets/front-end/js/view/widget.min.js` |
| Edit JS| `src/js/edit/widget.js`       | `assets/front-end/js/edit/widget.min.js` |

Files prefixed with `_` (e.g. `_variables.scss`) are partials — webpack skips them as entry points.
