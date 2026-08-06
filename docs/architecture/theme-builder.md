# Theme Builder

The Theme Builder lets users design **headers and footers** in Elementor and bind them to parts of the site with display conditions. It is not a widget and not an extension — it is a self-contained module with its own post type, admin screen, condition engine and front-end render pipeline.

Source: [`includes/Theme_Builder/`](../../includes/Theme_Builder/)
Admin app: [`includes/templates/admin/theme-builder/`](../../includes/templates/admin/theme-builder/) (React + Vite)
Assets: [`assets/admin/css/theme-builder.css`](../../assets/admin/css/theme-builder.css), [`assets/admin/js/theme-builder.js`](../../assets/admin/js/theme-builder.js)

> **Directory note.** The feature spec sketched a top-level `modules/theme-builder/`. The module lives under `includes/Theme_Builder/` instead, so the existing PSR-4 autoloader (`Essential_Addons_Elementor\` → `includes/`) picks it up with no changes to [`autoload.php`](../../autoload.php).

## Module map

| Path | Responsibility |
| --- | --- |
| `Theme_Builder.php` | Module singleton. Instantiates components, exposes `path()`, `capability()`, `page_url()`, `is_enabled()` |
| `Core/Post_Type.php` | Registers the `ea_theme_builder` CPT + all `_ea_template_*` meta with sanitization callbacks |
| `Core/Template_Types.php` | Registry of template types. `header` and `footer` ship; more are added on `eael/theme_builder/register_types` |
| `Core/Template_Cache.php` | Version-namespaced cache over transients + a per-request static array |
| `Models/Template.php` | Typed wrapper around one template post — the only place meta keys are read/written |
| `Conditions/Rules.php` | The 13–14 display rules: labels, groups, specificity, sub-object source, evaluation callbacks |
| `Conditions/Conditions_Manager.php` | Sanitize / validate conditions, resolve the winning template, detect conflicts, search sub-objects |
| `Admin/Admin.php` | Submenu registration, row/bulk action handling, screen options, asset enqueue + localization |
| `Admin/Templates_List_Table.php` | `WP_List_Table` — columns, views, tabs, search, month filter, pagination, row actions |
| `Admin/Ajax.php` | Four logged-in-only endpoints behind a shared nonce + capability check |
| `Integrations/Document.php` | Elementor document type (`ea-theme-builder`) |
| `Integrations/Elementor_Integration.php` | Document registration, canvas template, direct-access guard, cache busting on save |
| `Integrations/Compatibility.php` | Polylang / WPML translation, sitemap exclusions (core, Yoast, Rank Math) |
| `Frontend/Frontend.php` | Resolves templates for the request and picks a render mode |
| `Renderers/Template_Renderer.php` | Produces the markup, guarded against duplicate rendering |
| `Templates/` | `header.php`, `footer.php`, `canvas.php` for the front end, plus `admin/dashboard.php` — the only admin view left in PHP, since the modals are React |

## Data model

One custom post type, `ea_theme_builder`, holding six meta keys:

| Meta | Value |
| --- | --- |
| `_ea_template_type` | `header` \| `footer` \| any registered type |
| `_ea_template_conditions` | Array of `[ 'type' => include\|exclude, 'name' => rule, 'sub_id' => int ]` |
| `_ea_template_priority` | 1–100, default 10. Tie-breaker only |
| `_ea_template_status` | Mirror of `post_status`, kept in sync on save/transition |
| `_ea_template_platform` | `elementor` in v1 |
| `_ea_template_active` | `yes` \| `no` — lets a user park a template without trashing it |

The CPT is `public => false` but `publicly_queryable => true`: Elementor's editor preview iframe loads the template by URL, so it has to be queryable, and `Elementor_Integration::restrict_direct_access()` 404s anyone who cannot `edit_post` it.

`Template::create()` also writes Elementor's own `_elementor_edit_mode = builder` and `_elementor_template_type = ea-theme-builder`, which is what makes Elementor resolve `Integrations\Document` instead of its generic post document — and what lets a new template open straight into the editor. It seeds `_wp_page_template = elementor_canvas` too, so a new template previews on a blank canvas rather than inside the theme's single template.

## Page Settings → Page Layout

Two document properties gate the **Page Layout** select, and both must be `true`:

| Property | Gates |
| --- | --- |
| `support_page_layout` | Whether the control is injected at all — `Page_Templates\Module::action_register_template_control()` |
| `support_wp_page_templates` | Whether the chosen layout is honoured when the template renders — `Page_Templates\Module::template_include()` (priority 11) |

`Elementor_Integration::template_include()` runs at priority 999, *after* Elementor's, and only forces the canvas when `_wp_page_template` is empty or `default`. Picking "Elementor Full Width" or "Theme" in Page Settings therefore keeps working.

Excerpt, Featured Image, Order and Allow Comments do **not** appear in the panel — `PageBase::register_post_fields_control()` gates them on post type supports, and the CPT declares only `title`, `author`, `revisions` and `elementor`. They would be meaningless on a header fragment.

## No Elementor Pro dependency

Elementor ships its Theme Builder in Pro; this module deliberately reimplements the header/footer half on **free** Elementor APIs only. It never calls `elementor_theme_do_location()` or touches any `ElementorPro\` class — the whole render path is `get_header`/`get_footer` interception plus `Frontend::get_builder_content_for_display()`, both of which are Elementor free. Verified end to end with Elementor Pro deactivated.

## The rule registry

`Conditions/Rules.php` builds the registry in three layers, in order:

1. **Core rules** — the fixed WordPress views: Entire Site, Front Page, Blog Page, Search Results, 404 Page, Archive, Category / Tag / Author / Date archive, Singular, Post, Page. Hard-coded, because `is_front_page()`, `is_search()` and friends have no generic, data-driven form.
2. **Dynamic rules** — derived from `get_post_types()` and `get_taxonomies()` on every request:
   - one **singular** rule per public post type (`product`, `portfolio`, …), targetable down to one entry;
   - one **archive** rule per public post type that has `has_archive`;
   - one **archive** rule per public taxonomy, targetable down to one term.
3. **Third-party rules** — whatever `eael/theme_builder/condition_rules` returns.

A site that registers a `portfolio` type and a `portfolio_cat` taxonomy gets `portfolio`, `portfolio_archive` and `portfolio_cat_archive` with no code.

### What the dynamic layer skips

`public => true` is not enough on its own: Elementor's library, Templately's library, floating buttons and similar builder post types are all public so their previews resolve. The discriminator is **`show_ui && show_in_nav_menus`** — content types users link to are in nav menus, container types are not — plus an explicit blocklist. `category` and `post_tag` are skipped because they are already core rules under the names saved conditions reference.

Both lists are filterable: `eael/theme_builder/excluded_post_types`, `eael/theme_builder/excluded_taxonomies`.

### Rule shape

| Key | Meaning |
| --- | --- |
| `label` | Name shown in the condition builder. **Required.** |
| `callback` | Callable receiving the sub-object ID, returning bool. **Required.** |
| `group` | Optgroup — `general`, `archive`, `singular` (default `general`) |
| `specificity` | See the table below (default `SPECIFICITY_TYPE`) |
| `sub_source` | Slug of the object type the rule narrows to, or `false` |
| `sub_source_type` | What `sub_source` names — `post_type`, `taxonomy` or `user` |
| `supports` | `include` and/or `exclude` (default `[ 'include' ]`) |

`normalize_rules()` runs over the filtered registry, so a third-party rule only needs a label and a callback — everything else is defaulted, `supports` is intersected against the valid values, and a rule with no callable callback or no label is dropped rather than reaching the matching engine.

```php
add_filter( 'eael/theme_builder/condition_rules', function ( $rules ) {
	$rules['is_logged_in'] = [
		'label'    => __( 'Logged-in Users', 'my-textdomain' ),
		'supports' => [ 'include', 'exclude' ],
		'callback' => 'is_user_logged_in',
	];

	return $rules;
} );
```

`sub_source_type` is what keeps the specific-target lookups generic: `Conditions_Manager::search_objects()` and `get_object_label()` ask `Rules::get_sub_source_type()` what kind of object a source names instead of carrying their own list of taxonomies. It also gates the AJAX search — a source no registered rule declares is rejected, so the endpoint cannot be steered into an arbitrary post type or taxonomy.

The registry is memoized, but **only once `init` has fired** — post types and taxonomies are still being registered until then, and caching earlier would freeze an incomplete list. `Rules::flush()` drops the cache for late registrations and tests.

## Condition matching

`Conditions_Manager::get_active_template_id( $type )` resolves exactly one template per type per request:

1. Load every published, active template of that type (cached — see below).
2. A template matches when **at least one `include` row matches and no `exclude` row does**. One matching exclusion vetoes the whole template.
3. Among matches, the highest **specificity** wins. Specificity comes from `Rules`:

   | Tier | Score | Examples |
   | --- | --- | --- |
   | Site-wide | 10 | Entire Site |
   | Broad | 20 | Archive, Singular |
   | Per type | 30 | Category Archive, Post, Page, Product |
   | Unique view | 40 | Front Page, Blog Page, Search, 404 |
   | + specific object | +20 | "Page: Contact", "Category: News" |

   So `Page: Contact` (50) beats `Page` (30) beats `Singular` (20) beats `Entire Site` (10).
4. Equal specificity → lower `_ea_template_priority` wins; the query is ordered by modification date, so the most recently edited template also wins ties on priority.
5. The winner is passed through `Compatibility::translate_template_id()` for Polylang/WPML.

The `include`/`exclude` split is not symmetric — `Rules` declares a `supports` array per rule, and the spec's exclusion list is smaller than the inclusion list (no Blog Page, Search, 404 or taxonomy archives). `Rules::is_valid( $name, $type )` enforces it on both save and read.

### Conflict detection

`find_conflicts()` finds other templates of the same type claiming an identical include row. It is **advisory, not blocking** — the save succeeds and the condition builder shows a warning naming the other templates, because a conflict is often intentional (a broad header plus a narrower one that overrides it on some pages).

## Caching

`Template_Cache` namespaces every key with a version number from the `eael_theme_builder_cache_version` autoloaded option, plus the current language code. Flushing is a single option increment, so no orphan transients accumulate. It is flushed on: template save, status transition, deletion, `elementor/editor/after_save`, and every admin row/bulk action.

Two tiers:

- `get()` / `set()` — persistent, holds the per-type template list.
- `remember()` / `recall()` — request-scoped only, holds the resolved template for *this* URL. That result must never be persisted, since it depends on the current query.

## Render pipeline

`Frontend::setup()` runs on `template_redirect` (priority 5) — late enough for conditional tags to be reliable, early enough to be before `wp_head`. It resolves the templates, and if any matched, hooks the asset enqueue and picks a **render mode**:

| Mode | When | What it does |
| --- | --- | --- |
| `replace` | Classic themes (default) | Swaps out the theme's `header.php` / `footer.php` |
| `hooks` | Block themes (`wp_is_block_theme()`) | Injects at `wp_body_open` and `wp_footer` — block themes never call `get_header()` |
| `theme` | Theme declares `add_theme_support( 'ea-theme-builder' )` | Nothing automatic; the theme calls `do_action( 'eael/theme_builder/render', 'header' )` |

Override with the `eael/theme_builder/render_mode` filter.

### How `replace` mode swallows the theme's header

`override_header()` runs on `get_header`:

1. Include `Templates/header.php` — emits the doctype, `<head>`, `wp_head()`, opens `<body>`, calls `wp_body_open()`, prints the header template.
2. `remove_all_actions( 'wp_head' )` and `remove_all_actions( 'wp_body_open' )` so those callbacks cannot fire twice.
3. Pre-load the theme's own `header.php` into a discarded output buffer.

Step 3 is the trick: `locate_template()` loads with `require_once`, so the call WordPress makes immediately after this hook is a silent no-op. The `$name` argument is honoured, so `get_header( 'shop' )` discards `header-shop.php` too. `override_footer()` mirrors this, calling `wp_footer()` and closing the document itself.

**Guard against a second overrider.** If `did_action( 'wp_head' )` is already true when the hook fires, another plugin (Templately's builder does exactly this, at `get_header` priority 0) has already opened the document. Emitting a second doctype/`<head>`/`<body>` would corrupt the page, so the module prints only the template markup and skips the document scaffolding.

**Known limitation of `replace` mode:** if only one of header/footer matches, the theme supplies the other. Themes that open a wrapper `<div>` in `header.php` and close it in `footer.php` will have an unbalanced wrapper in that case.

### Assets

`Frontend::enqueue_assets()` runs on `wp_enqueue_scripts` **priority 101** — after Elementor (5/20) and after `Asset_Builder::frontend_asset_load` (100) have registered their handles, but still inside `wp_head` so nothing lands in the footer.

A second callback runs earlier, at **priority 9** — before Elementor's own `enqueue_styles` at 20 — and announces each resolved template to Elementor's render pipeline:

```php
do_action( 'elementor/post/render', $template_id );   // + enable the template's page assets
```

This is not optional. Elementor fires `elementor/post/render` for the **current** post only, and only when `is_singular()`. A Theme Builder template is neither — it renders on pages that know nothing about it, frequently on non-singular views. `Atomic_Styles_Manager::enqueue_styles()` returns early when no post was announced, so the atomic widgets' shared `base-desktop.css` / `base-mobile.css` are never emitted, and any atomic element in the template (`e-image`, `e-heading`, flexbox…) renders unstyled.

The symptom is deceptive: the header looks right on a static page and collapses into a vertical stack on the blog index, an archive, search or 404 — because on a singular view Elementor happens to announce *that* post, which is enough to make the manager emit the shared base files. `enable_conditional_assets()` mirrors Elementor's private `Frontend::handle_page_assets()` for the same reason.

Two more things happen in the priority-101 callback, and both matter:

1. `\Elementor\Plugin::$instance->frontend->enqueue_styles()`. Elementor only auto-hooks this when `is_singular()` **and** the current document is built with Elementor ([`elementor/includes/frontend.php`](../../../elementor/includes/frontend.php) `init()`). A header rendering on a category archive would otherwise get no `elementor-frontend` CSS at all. The method has an internal static guard, so calling it when Elementor already did is a no-op.
2. `Post_CSS::create( $template_id )->enqueue()` for each resolved template. Constructing the file object fires `elementor/files/file_name`, which is the hook `Asset_Builder::load_asset_per_file()` listens on — so EA widgets used *inside* the template get their CSS/JS loaded without Asset_Builder needing to know Theme Builder exists.

Elementor's frontend scripts follow automatically: rendering sets Elementor's `_has_elementor_in_page` flag, and its `wp_footer` handler enqueues them.

`Document::get_css_wrapper_selector()` returns `.elementor-{id}` rather than the post document's `body.elementor-page-{id}` — a Theme Builder template renders on pages other than its own, where that body class is never present. `.elementor-{id}` is the wrapper `get_builder_content_for_display()` emits.

### Duplicate rendering

`Template_Renderer` tracks which types it has already printed and returns an empty string on a second call for a `single` type. That covers a theme firing `get_header()` twice, and any plugin re-triggering the hook.

## Where the admin UI lives — React vs PHP

The dashboard is deliberately split, and the line is drawn by whether WordPress already does the job well:

| Part | Rendered by | Why |
| --- | --- | --- |
| Templates list, views, tabs, search, filters, pagination, bulk actions | `WP_List_Table` (PHP) | Native admin chrome. Re-implementing it in React would mean re-implementing nonce-checked bulk actions, screen options and pagination, and would look foreign in wp-admin. |
| Quick Edit | jQuery + core's `inline-edit-*` markup | Has to sit inside the list table's DOM and inherit `list-tables.css`. |
| "Add New Template" and "Display Conditions" modals | **React** ([`includes/templates/admin/theme-builder/`](../../includes/templates/admin/theme-builder/)) | Genuinely stateful custom UI: a cascade whose options depend on each other, an async searchable target picker, and a two-step wizard. This is what the jQuery version was bad at. |

The app follows the same convention as [`quick-setup`](../../includes/templates/admin/quick-setup/) and `eael-dashboard`: its own `package.json` and `vite.config.js`, sources in `src/`, built into `dist/theme-builder.min.js` + `.min.css` which `Admin::enqueue_assets()` enqueues. `src/`, `index.html` and the build config are excluded from the distribution via `.distignore`; only `dist/` ships.

```bash
cd includes/templates/admin/theme-builder
npm install       # once
npm run dev       # watch — rebuilds dist/ on save, then refresh the screen
npm run build     # one-off production build
```

`npm run dev` is `vite build --watch`, the same approach the other two React apps use: it rebuilds the bundle, it does **not** serve it. WordPress still enqueues `dist/`, so there is no HMR — save, then refresh.

`Admin::asset_version()` versions the Theme Builder assets by `filemtime()` when `wp_get_environment_type()` is `local`/`development`, or when `WP_DEBUG`/`SCRIPT_DEBUG` is on, so a rebuild is picked up on a normal refresh instead of being masked by the cached `?ver=` from the plugin version. Production keeps `EAEL_PLUGIN_VERSION`, because `filemtime()` can differ across servers behind a load balancer and would break shared caching. Override with `eael/theme_builder/version_assets_by_mtime`.

It does **not** take over the page. `App.jsx` attaches one delegated `click` listener and reacts to the two server-rendered triggers (`.eael-tb-add-new`, `.eael-tb-edit-conditions`), reading the row's `data-conditions` payload. After a save it repaints the affected row's conditions cell and re-stamps that attribute rather than reloading the screen.

Data comes from the `eaelThemeBuilder` global. It is localized onto the jQuery handle, and the React bundle declares that handle as a dependency purely to guarantee the global is printed first — the app itself does not use jQuery.

The condition builder is a cascade — **Include/Exclude → group → rule → specific target** — because the rule list grows with the site. With the dynamic layer registering every public post type and taxonomy, a single flat select stops being usable well before a typical WooCommerce install.

## Admin flow

```text
Theme Builder dashboard
        │  "Add New Template"
        ▼
Modal 1 — type + name        → eael_theme_builder_create_template  (creates a DRAFT)
        ▼
Modal 2 — display conditions → eael_theme_builder_save_conditions
        │                        └─ conflicts? warn + "Continue"
        ▼
Elementor editor  →  Publish  →  template goes live
```

Templates are created as **drafts** on purpose: publishing immediately would put an empty header on the live site before the user has built anything. Only `publish` + `_ea_template_active = yes` templates take part in matching.

### Row actions

| Action | What it does |
| --- | --- |
| Edit with Elementor | Opens the document in the Elementor editor |
| Edit Conditions | Opens the condition builder modal, prefilled from `data-conditions` on the link |
| Quick Edit | Inline editor for name, type, status, priority and the active flag |
| Duplicate | `Template::duplicate()` — copies the layout and every meta value as a new **draft** |
| View | `get_preview_post_link()`; only reachable by users who can edit the template (see the direct-access guard) |
| Edit | The classic WordPress editor |
| Trash / Restore / Delete Permanently | Status depending |

Activate / Deactivate live in Quick Edit and in the bulk actions rather than as standalone row links.

Extend the list with the `eael/theme_builder/row_actions` filter.

### Quick Edit

Deliberately **not** wired to core's `inline-edit-post` script — that is coupled to `edit.php`'s markup and to `wp_ajax_inline_save`, which returns a row rendered by `WP_Posts_List_Table`, not ours. The *markup*, however, is core's, so the panel inherits WordPress's own styling rather than a hand-rolled imitation:

| Column | Fields |
| --- | --- |
| Left (`.inline-edit-col-left`) | Title, Slug, Date (month select + day/year/hour/minute), Password –OR– Private |
| Right (`.inline-edit-col-right`) | Template (page layout), Status, Template Type, Priority, Active |

How it works:

- Each row ships an `#eael-tb-inline_{id}` payload of its current values (same idea as core's `#inline_{id}`).
- The JS builds the editor row from that payload, so opening Quick Edit costs no request.
- Saving hits `eael_theme_builder_quick_edit` and repaints only the title, post-state, type and date cells, then refreshes the stashed payload so reopening shows the new values.

Four details that are easy to get wrong:

- The row needs the `quick-edit-row-post` class. `list-tables.css` keys the 40% / 39% two-column split on it (`#wpbody-content .quick-edit-row-post .inline-edit-col-left`); without it both fieldsets render full width and the layout collapses into one column.
- The fieldsets must sit inside a `.inline-edit-wrapper` div. `tr.inline-edit-row td` is `padding: 0` — all of the panel's padding comes from that wrapper, so a custom wrapper class leaves the content flush against the table edge.
- The editor is a `<div>`, **not** a `<form>`. It is injected inside the list table's own `<form>`, and a nested form is invalid HTML: the browser submits it natively instead of letting the handler intercept, which reloads the screen and silently drops the edit. Enter is intercepted on the row's inputs for the same reason.
- `wp_update_post()` ignores `post_date` unless `edit_date` is also passed, so a date change would silently do nothing.

Private is a checkbox beside the password field, mirroring core: ticking it forces `post_status` to `private` and clears the password, and the status select only offers Published / Pending Review / Draft.

### AJAX endpoints

All four are `wp_ajax_` only (never `nopriv`), share the `eael_theme_builder` nonce, and re-check `Theme_Builder::capability()`:

| Action | Purpose |
| --- | --- |
| `eael_theme_builder_create_template` | Create the draft, return its ID + editor URL |
| `eael_theme_builder_save_conditions` | Validate + persist conditions, return conflicts and a summary. Also checks `edit_post` on the specific template |
| `eael_theme_builder_search_objects` | Populate the sub-object selector. Rejects any source not declared by a registered rule, so it cannot be used as a generic post/term query proxy |
| `eael_theme_builder_quick_edit` | Save the inline editor. Also checks `edit_post`; will not set a trash status |

Row and bulk actions (trash, restore, delete, duplicate, activate, deactivate) are handled in `Admin::on_load()` behind `check_admin_referer()` and a per-post `current_user_can()` check, then redirect with a result message.

## Extending it

Register a new template type — it appears in the dashboard tabs, the "Add New Template" modal and the condition engine automatically:

```php
add_action( 'eael/theme_builder/register_types', function ( $types ) {
	$types->register_type( 'archive', [
		'label'       => __( 'Archive', 'my-textdomain' ),
		'plural'      => __( 'Archives', 'my-textdomain' ),
		'wrapper_tag' => 'div',
		'location'    => 'content',   // header | footer | content
		'menu_order'  => 30,
	] );
} );
```

Add a display rule:

```php
add_filter( 'eael/theme_builder/condition_rules', function ( $rules ) {
	$rules['is_logged_in'] = [
		'label'       => __( 'Logged-in Users', 'my-textdomain' ),
		'group'       => 'general',
		'specificity' => 40,
		'sub_source'  => false,
		'supports'    => [ 'include', 'exclude' ],
		'callback'    => 'is_user_logged_in',
	];

	return $rules;
} );
```

### Hook reference

| Hook | Type | Fires |
| --- | --- | --- |
| `eael/theme_builder/init` | action | Module booted, all components wired |
| `eael/theme_builder/enabled` | filter | Whether the module loads at all |
| `eael/theme_builder/capability` | filter | Capability for the dashboard and the AJAX endpoints (default `manage_options`) |
| `eael/theme_builder/register_types` | action | Register template types |
| `eael/theme_builder/post_type_args` | filter | `register_post_type()` arguments |
| `eael/theme_builder/condition_rules` | filter | Rule definitions — core + dynamic, before normalization |
| `eael/theme_builder/condition_groups` | filter | Optgroup labels in the condition builder |
| `eael/theme_builder/excluded_post_types` | filter | Post types the dynamic layer skips |
| `eael/theme_builder/excluded_taxonomies` | filter | Taxonomies the dynamic layer skips |
| `eael/theme_builder/page_template_options` | filter | Page layouts offered in Quick Edit |
| `eael/theme_builder/row_actions` | filter | Row actions on the templates list |
| `eael/theme_builder/check_rule` | filter | Outcome of a single rule evaluation |
| `eael/theme_builder/active_template_id` | filter | The template chosen for the request |
| `eael/theme_builder/should_render` | filter | Whether to render on this request |
| `eael/theme_builder/render_mode` | filter | `replace` \| `hooks` \| `theme` |
| `eael/theme_builder/render` | action | Manual render entry point — pass a type slug |
| `eael/theme_builder/before_render` / `after_render` | action | Around one template, receives the type slug |
| `eael/theme_builder/before_header` / `after_header` | action | Inside `Templates/header.php` |
| `eael/theme_builder/before_footer` / `after_footer` | action | Inside `Templates/footer.php` |
| `eael/theme_builder/wrapper_classes` | filter | CSS classes on the rendered wrapper |
| `eael/theme_builder/rendered_html` | filter | Final markup of a rendered template |
| `eael/theme_builder/enqueue_assets` | action | After template assets are enqueued |
| `eael/theme_builder/cache_flushed` | action | After the caches are invalidated |
| `eael/theme_builder/template_created` | action | After a template is created |

## Gotchas

- **Conditional tags before `template_redirect` are unreliable.** Everything in `Rules` depends on `is_singular()`, `is_archive()` and friends, which is why resolution happens at `template_redirect` and not earlier.
- **Two full-document overriders cannot both win.** The `did_action( 'wp_head' )` guard degrades gracefully, but a site running Theme Builder alongside another header/footer builder should use only one.
- **The type registry lazily registers defaults** on first `get_types()` call, so labels are translated at use time rather than at file load.
- **`Template_Cache` keys include the language code**, because `WP_Query` is language-filtered under WPML/Polylang and a cached template list is therefore language-specific.
