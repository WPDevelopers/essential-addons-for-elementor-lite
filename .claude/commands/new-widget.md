# New Widget Scaffold

Create a new Essential Addons widget named **$ARGUMENTS**.

Follow these steps exactly:

1. **PHP class**: Create `includes/Elements/$ARGUMENTS.php`
    - Namespace `Essential_Addons_Elementor\Elements`
    - Extend `\Elementor\Widget_Base`
    - Implement `get_name()` → kebab-case slug, `get_title()` → human name, `get_icon()` → eicon-\*, `get_categories()` → `['essential-addons-elementor']`
    - Add a starter `register_controls()` section and a `render()` method with a root div `.eael-{slug}`

2. **SCSS**: Create `src/css/view/{slug}.scss` with a root `.eael-{slug}` block

3. **JS** (only if the widget needs frontend interactivity): Create `src/js/view/{slug}.js`

4. **Register in `config.php`**: Add the widget entry with slug, class path, and asset deps

5. Run `npm run build` and confirm there are no errors

6. Report: list all files created/modified and their full paths
