---
description: PHP and WordPress coding standards for this plugin
paths:
  - "includes/**/*.php"
  - "*.php"
---

# PHP & WordPress Standards

## Naming Conventions

- **Classes**: `PascalCase` — e.g. `Login_Registration`, `Asset_Builder`
- **Methods/Functions**: `snake_case` — e.g. `get_widget_list()`
- **Constants**: `UPPER_SNAKE_CASE` — e.g. `EAEL_PLUGIN_VERSION`
- **Hooks (actions/filters)**: `eael/{context}/{action}` — e.g. `eael/elements/before_render`

## Namespacing

All classes live under `Essential_Addons_Elementor\` with PSR-4 sub-namespaces:
- `Essential_Addons_Elementor\Elements\` → `includes/Elements/`
- `Essential_Addons_Elementor\Classes\` → `includes/Classes/`
- `Essential_Addons_Elementor\Traits\` → `includes/Traits/`
- `Essential_Addons_Elementor\Extensions\` → `includes/Extensions/`

## i18n

- Text domain: `essential-addons-for-elementor-lite`
- Always wrap user-facing strings: `__( 'String', 'essential-addons-for-elementor-lite' )`
- Use `esc_html__()` for output, `__()` for attribute values fed to `esc_attr()`

## Security

- Sanitize inputs: `sanitize_text_field()`, `absint()`, `wp_kses_post()`, etc.
- Escape outputs: `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()`
- Nonce verification for all AJAX handlers — check `wp_verify_nonce()` before processing
- Capability checks: `current_user_can()` before any privileged operation

## Adding Cross-Cutting Functionality

Extend a trait in `includes/Traits/` rather than adding methods directly to `Bootstrap.php`.
Bootstrap uses `use TraitName;` — find the right trait or create a new one.

## WordPress Hooks

- Prefix all custom hooks with `eael/`
- Register widget actions via `elementor/widgets/register`
- Use `plugins_loaded` (priority 100) for initialization
- Use `wp_enqueue_scripts` for front-end assets, `admin_enqueue_scripts` for admin assets
