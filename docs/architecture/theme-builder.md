# Theme Builder

The Theme Builder lets users design **headers and footers** in Elementor and bind them to parts of the site with display conditions. It is not a widget and not an extension — it is a self-contained module with its own post type, admin screen, condition engine and front-end render pipeline.

Source: [`includes/Theme_Builder/`](../../includes/Theme_Builder/)
Admin app: [`includes/templates/admin/theme-builder/`](../../includes/templates/admin/theme-builder/) (React + Vite)
Assets: [`assets/admin/css/theme-builder.css`](../../assets/admin/css/theme-builder.css), [`assets/admin/js/theme-builder.js`](../../assets/admin/js/theme-builder.js)

> **Directory note.** The feature spec sketched a top-level `modules/theme-builder/`. The module lives under `includes/Theme_Builder/` instead, so the existing PSR-4 autoloader (`Essential_Addons_Elementor\` → `includes/`) picks it up with no changes to [`autoload.php`](../../autoload.php).

## Module map

| Path | Responsibility |
| --- | --- |
| `Theme_Builder.php` | Module singleton. `boot()` is the entry point `Bootstrap` calls: the module when Elementor is present, `Requirements_Screen` when it is not. Also exposes `path()`, `capability()`, `page_url()`, `is_enabled()` |
| `Core/Post_Type.php` | Registers the `ea_theme_builder` CPT + all `_ea_template_*` meta with sanitization callbacks |
| `Core/Template_Types.php` | Registry of template types. `header` and `footer` ship; more are added on `eael/theme_builder/register_types` |
| `Core/Template_Cache.php` | Version-namespaced cache over transients + a per-request static array |
| `Models/Template.php` | Typed wrapper around one template post — the only place meta keys are read/written |
| `Conditions/Rules.php` | The condition registry — Elementor's Theme Builder model: three top level conditions, everything nested under them derived from the site's post types and taxonomies, their labels, specificity and evaluation callbacks |
| `Conditions/Conditions_Manager.php` | Sanitize / validate conditions, resolve the winning template, detect conflicts, search sub-objects |
| `Conditions/Conditions_Cleanup.php` | Drops condition rows whose target post / term / user was deleted, and deactivates a template left with no include row |
| `Admin/Admin.php` | Submenu registration, row/bulk action handling, screen options, asset enqueue + localization |
| `Admin/Requirements_Screen.php` | Stand-in submenu page registered instead of the module when Elementor is missing, so the page stays reachable and says why |
| `Admin/Templates_List_Table.php` | `WP_List_Table` — columns, views, tabs, search, month filter, pagination, row actions |
| `Admin/Ajax.php` | Six logged-in-only endpoints behind a shared nonce + capability check |
| `Integrations/Document.php` | Elementor document type (`ea-theme-builder`) |
| `Integrations/Editor.php` | Loads the React app inside the Elementor editor and hands it the template being edited, so publishing can ask for display conditions first — and so the EA button can offer presets |
| `Integrations/Elementor_Integration.php` | Document registration, canvas template, direct-access guard, cache busting on save |
| `Integrations/Compatibility.php` | Polylang / WPML translation, sitemap exclusions (core, Yoast, Rank Math) |
| `Presets/Preset_Library.php` | Ready-made header and footer starting points: the cards the picker shows, and the Elementor elements each one inserts |
| `Presets/Mega_Header.php` | The Mega Menu header preset — the header bar, the menu's settings, and the two mega panels it ships with |
| `Presets/Modern_Footer.php` | The multi-column footer preset — columns, highlights strip and legal bar |
| `Presets/Elements.php` | The Elementor shapes a preset is assembled from: containers, widgets, repeater rows, spacing and slider values |
| `Frontend/Frontend.php` | Resolves templates for the request and picks a render mode |
| `Renderers/Template_Renderer.php` | Produces the markup, guarded against duplicate rendering |
| `Templates/` | `header.php`, `footer.php`, `canvas.php` for the front end, plus `admin/dashboard.php` — the only admin view left in PHP, since the modals are React |

## Data model

One custom post type, `ea_theme_builder`, holding six meta keys:

| Meta | Value |
| --- | --- |
| `_ea_template_type` | `header` \| `footer` \| any registered type |
| `_ea_template_conditions` | Array of `[ 'type' => include\|exclude, 'name' => general\|archive\|singular, 'sub_name' => string, 'sub_id' => int ]` |
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

`Conditions/Rules.php` is a port of Elementor's Theme Builder conditions — the model, the vocabulary and the specificity numbers. Users arrive at this screen already knowing that language, and an *almost* identical one is worse than either.

A saved row is four values:

| Field | Value |
| --- | --- |
| `type` | `include` \| `exclude` |
| `name` | `general` \| `archive` \| `singular` — the top level |
| `sub_name` | a condition nested under that top level, or `''` for all of it |
| `sub_id` | the object that sub-condition is narrowed to, or `0` for all |

Which reads, in the builder, as `Include / Singular / In Category / News`.

### The tree

Nesting is two levels deep and rendered as **one grouped select**: a sub-condition that has sub-conditions of its own becomes an optgroup whose first option is the sub-condition itself. That is what puts "In Category" — the condition that matches *single posts filed under a term*, as opposed to the term's archive — inside a "Posts" group.

```text
Entire Site

Archives ─┬─ All Archives
          ├─ Author Archive · Date Archive · Search Results
          └─ [Posts Archive] ─┬─ Posts Archive
                              ├─ Categories · Tags
                              └─ Direct child Category of · Any child Category of

Singular ─┬─ All Singular
          ├─ Front Page
          ├─ [Posts] ─┬─ Posts
          │           ├─ In Category · In child Categories · In Tag
          │           └─ Posts by Author
          ├─ [Pages] ─┬─ Pages
          │           └─ Pages by Author
          └─ Direct child of · Any child of · By Author · 404 Page
```

Everything below the three top levels is derived from the site on every request: one entry per public post type, its taxonomies under both its archive and its singular condition, `child_of` / `any_child_of` for hierarchical types. A WooCommerce install gets "Products", "In Category", "In Brand" and "Products Archive" with no code, exactly as Elementor does.

Include and exclude are offered on **every** condition. There is no `supports` allowlist: `match_conditions()` evaluates both the same way, so a restriction there could only ever produce the bug QA filed against the old registry — picking "Category Archive" and then switching the row to Exclude silently reset the target.

### What the registry skips

`public => true` is not enough on its own: Elementor's library, Templately's library, floating buttons and similar builder post types are all public so their previews resolve. The discriminator is **`show_ui && show_in_nav_menus`** — content types users link to are in nav menus, container types are not — plus an explicit blocklist. Taxonomies are filtered the same way, per post type, via `get_object_taxonomies()`.

Both lists are filterable: `eael/theme_builder/excluded_post_types`, `eael/theme_builder/excluded_taxonomies`.

### Condition shape

| Key | Meaning |
| --- | --- |
| `label` | Name shown in the builder. **Required.** |
| `callback` | Callable receiving `sub_id`, returning bool. **Required.** |
| `all_label` | Name of its "all of it" option when it heads a group (defaults to `label`) |
| `priority` | Specificity — see below (default `PRIORITY_SINGULAR_SUB`) |
| `sub_conditions` | Names nested under it |
| `source` | `[ 'kind' => post\|term\|user, … ]`, or absent when it targets a whole view |

`normalize()` runs over the filtered registry: a third-party condition needs only a label and a callback, one with neither is dropped rather than reaching the engine, and a `sub_conditions` entry naming a condition that does not exist is removed so the builder cannot render an empty option.

```php
add_filter( 'eael/theme_builder/conditions', function ( $conditions ) {
	$conditions['logged_in'] = [
		'label'    => __( 'Logged-in Users', 'my-textdomain' ),
		'priority' => 40,
		'callback' => 'is_user_logged_in',
	];

	// Make it reachable: add it under a top level condition.
	$conditions['singular']['sub_conditions'][] = 'logged_in';

	return $conditions;
} );
```

`source` is what keeps the object picker generic — and what keeps the AJAX search safe. The client sends the **condition name**, never a post type or taxonomy to query; `search_objects()` resolves what may be searched from the registry, so a condition with no picker returns nothing and there is no request shape that reaches content the builder does not offer.

The registry is memoized, but **only once `init` has fired** — post types and taxonomies are still being registered until then, and caching earlier would freeze an incomplete list. `Rules::flush()` drops the cache for late registrations and tests.

### Conditions saved before this model

Rows stored by earlier versions name a flat rule (`category_archive`, `blog`, `post`, `{taxonomy}_archive`, …) with no `sub_name`. `Rules::map_legacy_rule()` translates them as they are read, in `sanitize_conditions()`:

| Was | Is now |
| --- | --- |
| `entire_site` | `general` / — |
| `blog` | `archive` / `post_archive` |
| `search`, `date_archive`, `author_archive` | `archive` / `search`, `date`, `author` |
| `category_archive`, `tag_archive`, `{taxonomy}_archive` | `archive` / the taxonomy |
| `{post_type}_archive` | `archive` / `{post_type}_archive` |
| `front_page`, `not_found` | `singular` / `front_page`, `not_found404` |
| `post`, `page`, `{post_type}` | `singular` / the post type |

Translation happens on read, not as a one-off migration: a live site keeps rendering the same headers with no upgrade step, the meta is rewritten in the new shape the next time the template is saved, and rolling the plugin back leaves nothing stranded.

## Condition matching

`Conditions_Manager::get_active_template_id( $type )` resolves exactly one template per type per request:

1. Load every published, active template of that type (cached — see below).
2. A row matches when its top level condition matches **and** its sub-condition does. The top level is checked first: it is cheap and rules out most rows.
3. A template matches when **at least one `include` row matches and no `exclude` row does**. One matching exclusion vetoes the whole template.
4. Among matches, the **narrowest** condition wins — and narrow means a *low* number. The scale is Elementor's:

   | Condition | Score |
   | --- | --- |
   | Entire Site | 100 |
   | All Archives | 80 |
   | Author / Date / Search / a post type archive / a taxonomy | 70 |
   | All Singular | 60 |
   | A post type, In Category, By Author, Child of | 40 |
   | Front Page | 30 |
   | 404 Page | 20 |

   `Rules::get_priority()` then narrows that score the way Elementor's `get_condition_priority()` does: take the lower of the top level and the sub-condition, subtract 10 for naming a sub-condition at all, another 10 for naming an object inside it, or 5 instead when the sub-condition has no children of its own and is therefore already specific.

   So `Singular / Posts / Hello World` (20) beats `Singular / Posts` (30) beats `All Singular` (60) beats `Entire Site` (100).
5. Equal specificity → lower `_ea_template_priority` wins. Equal priority too → the **highest post ID** wins, i.e. the template created last.

   That third level is compared explicitly in the loop rather than left to the order `WP_Query` returned the rows in. The query is ordered by modification date, so a first-match-wins loop would hand the tie to whichever template was edited most recently — and the winner would then flip every time the *losing* template was re-saved, with nothing about either template actually changed. The post ID is the one key that never moves.

6. The winner is passed through `Compatibility::translate_template_id()` for Polylang/WPML.

### Archive of a term vs. content in a term

The two are different conditions and always have been in Elementor, which is worth stating because it is the question this screen is asked most often:

- `Archives / Categories: News` matches `/category/news/` — the listing.
- `Singular / In Category: News` matches every single post filed under News.

Neither implies the other. A header that should cover both needs two rows.

### Conflict detection

`find_conflicts()` finds other templates of the same type claiming an identical include row. It is **advisory, not blocking** — the save succeeds and the condition builder shows a warning naming the other templates, because a conflict is often intentional (a broad header plus a narrower one that overrides it on some pages).

### When a condition's target is deleted

A row narrowed to one object stores that object's ID, so deleting the object strands the row: it can never match again, and the list can no longer name what it targeted. Three things handle that, in order of when they apply:

1. `Conditions_Cleanup` hooks `deleted_post`, `delete_term` and `deleted_user` and removes the matching rows — the same thing core does for nav menu items pointing at a deleted page. Post IDs are globally unique, so post deletion matches on the ID alone and does not need the post type.
2. A template left with **no include row** would be published but unable to match anything, which is indistinguishable from a broken one. It is flagged inactive, its ID is queued in the `eael_tb_deactivated_templates` option, and the dashboard turns that into a notice on the next page load (`Admin::get_orphaned_notice()`, which clears the option as it reads it).
3. Rows orphaned while the plugin was inactive miss the hooks entirely. `get_conditions_summary()` renders those as `Page: deleted #42` rather than a bare `Page` — which would read as "every page", the opposite of the truth — and `find_broken_conditions()` puts a warning marker on the row in the Display Conditions column.

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
| `hooks` | Block themes (`wp_is_block_theme()`) | Replaces the theme's header/footer `core/template-part` blocks — block themes never call `get_header()`. Falls back to `wp_body_open` / `wp_footer` when the template has no such part |
| `theme` | Theme declares `add_theme_support( 'ea-theme-builder' )` | Nothing automatic; the theme calls `do_action( 'eael/theme_builder/render', 'header' )` |

Override with the `eael/theme_builder/render_mode` filter.

### How `replace` mode swallows the theme's header

`override_header()` runs on `get_header`:

1. Include `Templates/header.php` — emits the doctype, `<head>`, `wp_head()`, opens `<body>`, calls `wp_body_open()`, prints the header template.
2. `remove_all_actions( 'wp_head' )` and `remove_all_actions( 'wp_body_open' )` so those callbacks cannot fire twice.
3. `restore_theme_wrappers()` pre-loads the theme's own `header.php` into an output buffer.

Step 3 is the trick: `locate_template()` loads with `require_once`, so the call WordPress makes immediately after this hook is a silent no-op. The `$name` argument is honoured, so `get_header( 'shop' )` swallows `header-shop.php` too. `override_footer()` mirrors this, calling `wp_footer()` and closing the document itself.

**Why the buffer is scanned rather than thrown away.** A theme's `header.php` ends by opening the containers that lay out the rest of the page. GeneratePress opens `#page.site.grid-container` (the 1200px content container) and `#content.site-content` (`display: flex`, which is what puts the sidebar beside the article); both are closed in `footer.php`. Discarding the file therefore removes the page layout along with the theme's header — the article stretches edge to edge and the sidebar drops underneath it.

`get_orphan_openers()` scans the captured markup and keeps the opening tags of elements the file never closes. Anything it opened *and* closed is its own header markup and goes. `html`, `head` and `body` are excluded — `Templates/header.php` printed those already. The wrappers are re-emitted **after** the Theme Builder header, so a full-width header is not clamped by the theme's container, and their tag names are remembered so `close_theme_wrappers()` can close them if the footer is replaced too (when it isn't, the theme's own `footer.php` still closes them). `eael/theme_builder/theme_wrappers` filters the list.

**Guard against a second overrider.** If `did_action( 'wp_head' )` is already true when the hook fires, another plugin (Templately's builder does exactly this, at `get_header` priority 0) has already opened the document. Emitting a second doctype/`<head>`/`<body>` would corrupt the page, so the module prints only the template markup and skips the document scaffolding.

**When only the footer matches.** Discarding `footer.php` wholesale is only safe when the header was replaced too. Replace the footer alone and the theme's own `header.php` has already opened wrappers that only `footer.php` closes — GeneratePress opens `.site.grid-container` and `.site-content`, Kadence opens `#wrapper` and `#inner-wrap`. Throw the file away and those never close, so the footer renders *inside* the content column: clamped to the container width and, since GeneratePress gives `.site-content` `display: flex`, sitting beside the content instead of beneath it.

So in that one case the file is loaded normally into a buffer instead, and `swap_theme_footer()` closes that buffer on `wp_footer` — the call the theme makes at the end of its footer, after both the closing tags and its own footer markup:

1. `get_footer` → `capture_theme_footer()` starts the buffer and hooks `wp_footer` at `-PHP_INT_MAX`.
2. The theme's `footer.php` runs in full, into the buffer.
3. Its `wp_footer()` call lands in our callback first. `get_orphan_closers()` scans the captured fragment and keeps only the closing tags with **no matching opening tag inside it** — precisely what the header opened. Everything the theme both opened and closed is its own footer, and goes.
4. The template prints there, then the rest of `wp_footer` runs and the theme closes the document itself.

Keeping *orphan* closers rather than *leading* ones matters: Kadence closes `#inner-wrap` before its footer and `#wrapper` after it, so a leading-run heuristic would leave `#wrapper` open.

`shutdown` carries a fallback for a theme that never calls `wp_footer()`, and `eael/theme_builder/swap_theme_footer` returns to the plain discard. When the header is replaced as well, nothing changes — our `header.php` never opens the theme's wrappers, so there is nothing left to close.

Both scans share `scan_tags()`, which skips void and self-closing elements, strips comments, and ignores tag-like text inside `<script>`, `<style>` and `<textarea>`. Attribute values are matched with their quotes, so a `>` inside one does not truncate a tag.

### How `hooks` mode swallows a block theme's header

A block theme has no `header.php` to discard — its header is a `core/template-part` block inside the resolved block template. `maybe_replace_template_part()` runs on `pre_render_block` and returns the matched template's markup for the first part in the `header` (or `footer`) area, which short-circuits the block: the theme's part is never rendered, and ours takes its place in the document. Every further part of the same area collapses to an empty string, so a theme that repeats its header part cannot stack two of ours.

Working out a part's area, in order:

1. `attrs.area` on the block — Twenty Twenty-Four writes it.
2. The `area` of the `wp_template_part` the block references, via `get_block_template( "{$theme}//{$slug}", 'wp_template_part' )` — Twenty Twenty-Five writes only `{"slug":"header"}`, so this is the common path. Results are memoized per request.
3. `attrs.tagName`, then the slug (`header`, or `header-*`).

**The fallback.** A block template is free to have no header part at all — a landing-page template, say. Nothing would be replaced and the header would silently never render. `prepare_block_template_fallback()` runs on `template_include` (the last hook before `wp_body_open`, and late enough that `$_wp_current_template_content` is populated), scans that content for a header-area part, and hooks the old `wp_body_open` injection when it finds none. The footer hook is registered unconditionally — the renderer prints a `single` type only once, so it is a no-op when the footer part was already replaced.

Two guards worth knowing about:

- An **empty** template — no widgets yet, or filtered to nothing — returns `null` from the filter rather than an empty string, so an empty header cannot take the theme's header off the page and leave a gap.
- `eael/theme_builder/replace_block_template_parts` turns the whole thing off, restoring injection-only behaviour alongside the theme's own header and footer.

#### The root spacing that comes with the slot

Taking the template part's place also means inheriting the spacing the block theme reserves around the root container. With `useRootPaddingAwareAlignments`, `class-wp-theme-json.php` emits:

```css
.wp-site-blocks { padding-top: var(--wp--style--root--padding-top); padding-bottom: var(--wp--style--root--padding-bottom); }
:where(.wp-site-blocks) > * { margin-block-start: <block gap>; margin-block-end: 0; }
```

The theme's own header and footer sit inside that by design. A full-bleed Elementor header or footer does not want to — the result is a strip of page background above the header and below the footer that nothing in Elementor can remove. `enqueue_block_theme_spacing_reset()` prints an inline stylesheet that pulls the wrapper back by exactly that amount:

```css
.wp-site-blocks > .eael-theme-builder--header:first-child { margin-top: calc(var(--wp--style--root--padding-top, 0px) * -1); }
.wp-site-blocks > .eael-theme-builder--footer:last-child  { margin-bottom: calc(var(--wp--style--root--padding-bottom, 0px) * -1); }
```

The `0px` fallback is what makes this safe to always print: Twenty Twenty-Four and Twenty Twenty-Five set only left/right root padding, leaving the custom property undefined, so the rules compute to zero. Twenty Twenty-Three sets `var(--wp--preset--spacing--40)` top and bottom and is the case this exists for. Selectors are generated from the type registry, so a third-party type at the header or footer location is covered. Horizontal padding needs no handling — it lands on the first `.has-global-padding` container, and `.wp-site-blocks` is not one.

Turn it off with `eael/theme_builder/block_theme_spacing_reset` when the root padding is part of a deliberate framed layout.

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
| Quick Edit, Bulk Edit | jQuery + core's `inline-edit-*` markup | Have to sit inside the list table's DOM and inherit `list-tables.css`. |
| "Add New Template" and "Display Conditions" modals, in the dashboard **and** in the Elementor editor | **React** ([`includes/templates/admin/theme-builder/`](../../includes/templates/admin/theme-builder/)) | Genuinely stateful custom UI: a cascade whose options depend on each other, an async searchable target picker, and a modal that has to hold a publish back while it is open. This is what the jQuery version was bad at. |

The app follows the same convention as [`quick-setup`](../../includes/templates/admin/quick-setup/) and `eael-dashboard`: its own `package.json` and `vite.config.js`, sources in `src/`, built into `dist/theme-builder.min.js` + `.min.css` which `Admin::enqueue_assets()` enqueues. `src/`, `index.html` and the build config are excluded from the distribution via `.distignore`; only `dist/` ships.

```bash
cd includes/templates/admin/theme-builder
npm install       # once
npm run dev       # watch — rebuilds dist/ on save, then refresh the screen
npm run build     # one-off production build
```

`npm run dev` is `vite build --watch`, the same approach the other two React apps use: it rebuilds the bundle, it does **not** serve it. WordPress still enqueues `dist/`, so there is no HMR — save, then refresh.

**The bundle is built as an IIFE** (`rollupOptions.output.format = 'iife'`), and that is not cosmetic. WordPress enqueues it as a classic script, and in a classic script every top level `var` of Vite's default ES module output becomes a global — including the one React's minified internals declare, `$e`. That is the name Elementor's editor gives its command API, so the module build replaced `window.$e` with a React internal string (`__reactFiber$…`) and took the entire editor down with it. The IIFE wrapper keeps the bundle's declarations in one function scope.

`Admin::asset_version()` versions the Theme Builder assets by `filemtime()` when `wp_get_environment_type()` is `local`/`development`, or when `WP_DEBUG`/`SCRIPT_DEBUG` is on, so a rebuild is picked up on a normal refresh instead of being masked by the cached `?ver=` from the plugin version. Production keeps `EAEL_PLUGIN_VERSION`, because `filemtime()` can differ across servers behind a load balancer and would break shared caching. Override with `eael/theme_builder/version_assets_by_mtime`.

It does **not** take over the page. `App.jsx` attaches one delegated `click` listener and reacts to the two server-rendered triggers (`.eael-tb-add-new`, `.eael-tb-edit-conditions`), reading the row's `data-conditions` payload. After a save it repaints the affected row's conditions cell and re-stamps that attribute rather than reloading the screen.

`main.jsx` picks its host at runtime: the dashboard container (`#eael-theme-builder-app`) when the list table printed one, otherwise — when `eaelThemeBuilder.editor` is present — a node it appends to the editor's document itself, rendering `EditorApp` instead of `App`.

Data comes from the `eaelThemeBuilder` global. On the dashboard it is localized onto the jQuery handle, and the React bundle declares that handle as a dependency purely to guarantee the global is printed first — the app itself does not use jQuery. In the editor there is no jQuery handle of ours, so `Integrations\Editor` localizes the same payload onto the app handle directly, with the edited template added under `editor`.

The condition builder is a cascade — **Include/Exclude → group → rule → specific target** — because the rule list grows with the site. With the dynamic layer registering every public post type and taxonomy, a single flat select stops being usable well before a typical WooCommerce install.

## Admin flow

```text
Theme Builder dashboard
        │  "Add New Template"
        ▼
Modal — type + name          → eael_theme_builder_create_template  (creates a DRAFT)
        ▼
Elementor editor  →  Publish
        ▼
Modal — display conditions   → eael_theme_builder_save_conditions
        │                        └─ conflicts? warn + "Continue"
        ▼
the held-back publish runs  →  template goes live
```

Templates are created as **drafts** on purpose: publishing immediately would put an empty header on the live site before the user has built anything. Only `publish` + `_ea_template_active = yes` templates take part in matching.

Display conditions used to be step 2 of the creation wizard. They are asked at publish time instead, because that is the click that actually puts the header or footer on the live site — at creation time the honest answer is often "not sure yet", and the answer given then was never revisited. See [Publishing from the editor](#publishing-from-the-editor).

**A template created without a name** is called `Header Template #205 (by EA)` — type, ID and an origin marker. `Template::create()` inserts with the bare type label as a placeholder (`wp_insert_post()` refuses a post with no title, no content and no excerpt, and the ID does not exist yet), then renames. Drafts get no `post_name`, so the placeholder never reaches the slug. Filter the result with `eael/theme_builder/auto_template_title`.

### Publishing from the editor

`Integrations\Editor` enqueues the React bundle on `elementor/editor/after_enqueue_scripts` when `Plugin::$instance->editor->get_post_id()` is a template the current user may edit, and `EditorApp` registers the gate.

The gate is a **data dependency hook** on `document/save/publish`:

```js
class PublishGate extends $e.modules.hookData.Dependency {
    getCommand() { return 'document/save/publish'; }
    getId() { return 'eael-theme-builder-conditions'; }
    getConditions( args ) { /* only this document */ }
    apply( args ) { /* false holds the publish back */ }
}
```

Of the four hook types Elementor exposes, `Dependency` is the only one whose callback can stop the command it is attached to — `CommandBase.onBeforeApply()` runs it, and a `false` return makes the web-cli throw a `HookBreak`, so the save request is never sent. UI and `after` hooks both run too late.

It hooks the **command, not the button**: the top bar, the panel footer and the keyboard shortcut all funnel into `document/save/publish`, while the markup around them changes between Elementor releases.

What follows from that shape:

- Holding the publish back leaves the document a **draft** — nothing was saved, so dismissing the modal is a real cancel, and the editor says so in a toast.
- After the conditions are saved, `EditorApp` flips a ref and re-runs the same command with the arguments it was called with. The ref, not state: the gate is registered once and would otherwise keep reading the values it closed over at mount.
- Only `document/save/publish` is gated. `update` (an already published template), `draft`, `pending` and every autosave run untouched — the conditions exist by then, and are edited from the dashboard.
- The modal opens on the conditions the template already has — for a new one, **none**: an empty body with "Add Condition" under it. `Post_Type::default_meta()` seeds no conditions, and the modal no longer materializes a blank row to fill the space, because at publish time a pre-selected row decides where the header goes. Rows can be removed down to zero.
- `Conditions_Manager::validate_conditions()` refuses an empty set and a set with no `include` row, so publishing with nothing chosen comes back with "Add at least one display condition…" and the template stays a draft.

### The CPT's own list screen

`edit.php?post_type=ea_theme_builder` still resolves — the post type keeps `show_ui` for the classic editor and for the row actions that link to it — but it is core's generic table: no type, conditions or platform columns, and its "Add New" lands on an empty post rather than the creation modal. `Admin::redirect_cpt_list()` sends it to the dashboard on `load-edit.php`, preserving `post_status` so a link to the trash still lands on the trash view.

### Presets — the EA button in the editor

An empty header template is a blank canvas with no obvious first move. The EA button in Elementor's add-element row is that first move: it opens a picker of ready-made headers and footers and inserts one where the button was clicked.

```text
preview iframe                         editor window
──────────────                         ─────────────
[+] [▣] [✦] [EA] ──click──▶  CustomEvent ──▶ EditorApp ──▶ PresetLibrary
                                                                │
                                        eael_theme_builder_get_preset
                                                                │
                                            $e.run( 'document/elements/create' )
```

**The button** is a Marionette behaviour registered through `views/add-section/behaviors`, the filter Elementor applies to that view — the same door Templately uses. A behaviour gets the view's lifecycle, so the button survives the re-renders that row does on every layout change, and it inherits `.elementor-add-section-area-button` styling instead of fighting it. Two things follow from where that row lives:

- The button element is created in the **preview iframe**, which has its own document and does not load this app's stylesheet — so its handful of styles are set inline.
- The behaviour itself runs in the **editor window**, which is where the React app is, so the click is passed on as a plain `CustomEvent` rather than reaching across documents.

**The elements are built per insert** (`Mega_Header::build()`, `Preset_Library::build_classic_header()`), not stored as a frozen JSON blob. A preset comes up carrying the site's own name, logo and menu, and Elementor keys every element by a unique ID — inserting the same stored blob twice would collide.

**Everything a preset uses ships with Lite.** Containers, Heading, Button, Icon, Icon List, Icon Box, Image and Text Editor are Elementor core; the navigation is EA's own Simple Menu or Mega Menu, which is what makes these headers responsive without Elementor Pro.

Three ship today:

| Preset | Type | EA widgets | Notes |
|--------|------|-----------|-------|
| Mega Menu Header | header | Mega Menu | Logo, centred menu with two ready-built mega panels, search, cart, call to action. Collapses at mobile |
| Modern Footer | footer | Info Box ×5, Feature List, Creative Button | Five columns, a highlights strip and a dark legal bar. Columns stack at tablet and mobile |
| Classic Header | header | Simple Menu | Site name, links, call to action. Collapses at tablet |

**A preset built on a widget has to check the widget is there.** `Elements::has_widget()` asks `Plugin::$instance->widgets_manager->get_widget_types()` rather than testing for a class or an experiment: a widget can be missing because Elementor is too old, because an experiment is off, *or* because the element is switched off in EA's own settings, and the widgets manager is the one answer that covers all three. A preset whose widgets are missing is not offered at all (`Mega_Header::is_available()`, `Modern_Footer::is_available()`); a widget that is a nicety rather than a dependency is checked at the point of use instead.

**A nested widget's children need `isLocked`.** Elementor's `NestedModelBase::initialize()` fills in the default children only for a widget created with none, so a preset that supplies its own panels keeps them — but `isValidChild()` then rejects any child without the `isLocked` flag that `getDefaultChildren()` would have stamped on. `Elements::nested_child()` exists to set it. The panels are also **positional**: the widget prints child *n* for repeater row *n*, so a plain link item still gets an empty container.

**Bands, not one grid.** The footer is three boxed containers stacked in one full-width wrapper, rather than one container with a background. A boxed container paints its background edge to edge while keeping its content in the site's content column, which is what lets the legal bar be dark while the rest of the footer is not — and it means each band keeps its own padding and its own wrap behaviour.

**Widths plus `flex_wrap` are the whole responsive story.** Every column carries `width` / `width_tablet` / `width_mobile`; the row wraps. Nothing is hidden at a breakpoint and nothing is duplicated for one, so what the user edits is what every device shows. (The reference design collapsed the footer's link columns into an accordion on mobile. That needs a second copy of the links for the small screen to edit separately, which is exactly the kind of hidden duplicate a preset should not ship.)

**Three widget quirks the footer preset works around**, each worth knowing before building on these widgets:

- Info Box's *Icon Position* control for the top/bottom layouts writes `align-self`, which is **horizontal** once the box is a column — its `middle` default centres a top icon over left-aligned copy. The preset sets it to `top` (labelled "Left" in the panel for that layout).
- Feature List prints its content paragraph whether or not the row has content, so a row left blank still takes that paragraph's margin. Every row in the contact list carries a second line for that reason.
- Dual Color Header hangs a 50px bottom margin off its own wrapper with no control behind it. The footer uses a plain heading for the wordmark instead — a preset has to stay editable everywhere it is visible.

**The panel's padding lives on the widget, not on the panel container.** The Mega Menu's *Submenu Panel > Padding* control writes the same `--padding-*` custom properties an Elementor container reads for its own padding, and it wins — so a padding set on the panel container is silently overwritten, a zero included. The Mega Menu preset therefore leaves the container's padding unset and sets the widget control instead.

**Colours are spelled out, transparents included.** An empty colour setting emits no rule at all, which leaves the *theme's* styling in charge — a Storefront install renders the menu as a solid bar in its link colour, inside the header. Every colour the preset depends on is therefore explicit, including the "current page" link state, which is filled by default and would otherwise paint a stray block on the one link matching the current URL.

**Responsiveness is split between the two layers that own it.** Container widths carry `_tablet` / `_mobile` values; the navigation widget collapses itself at its own breakpoint. Where the collapsed toggle *lands* is the container's problem, not the widget's — the Mega Menu preset sets `_flex_order_mobile` on the navigation column so the toggle sits with the other icons at the end of the bar instead of in the middle of it.

Add one with the `eael/theme_builder/presets` filter:

```php
add_filter( 'eael/theme_builder/presets', function ( $presets ) {
	$presets['minimal-footer'] = [
		'type'      => 'footer',
		'title'     => __( 'Minimal Footer', 'my-textdomain' ),
		'badge'     => __( 'Minimal', 'my-textdomain' ),
		'thumbnail' => plugins_url( 'img/minimal-footer.svg', __FILE__ ),
		'builder'   => 'my_minimal_footer_elements',   // returns Elementor elements
	];

	return $presets;
} );
```

### Row actions

Listed in the order they render — `row_actions()` prints the array as `get_row_actions()` built it, so that method's sequence *is* the display order:

| Action | What it does |
| --- | --- |
| Edit | The classic WordPress editor |
| Quick Edit | Inline editor for name, type, status, priority and the active flag |
| Trash / Restore / Delete Permanently | Status depending |
| View | `get_preview_post_link()`; only reachable by users who can edit the template (see the direct-access guard) |
| Duplicate | `Template::duplicate()` — copies the layout and every meta value as a new **draft** |
| Edit Conditions | Opens the condition builder modal, prefilled from `data-conditions` on the link |
| Edit with Elementor | Opens the document in the Elementor editor |

Activate / Deactivate live in Quick Edit rather than as standalone row links, and are deliberately kept out of the bulk dropdown — the flag is a per-template decision made against that template's conditions, and in bulk it is an easy mis-click that silently pulls several headers off the site. `Admin::handle_bulk_action()` still accepts both actions, so the `bulk_actions` filter can restore them.

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

Priority is validated, not clamped. `Post_Type::sanitize_priority()` still clamps to 1–100 — the right default for imports, WP-CLI and third-party writes — but a value typed into Quick Edit is range-checked by `Ajax::is_valid_priority()` and refused with a message, matching how the empty-title and invalid-date errors behave in the same panel. `assets/admin/js/theme-builder.js` mirrors the check so the error appears before the request goes out; both sides read the range from `Post_Type::PRIORITY_MIN` / `PRIORITY_MAX`, localized as `eaelThemeBuilder.priority`.

Private is a checkbox beside the password field, mirroring core: ticking it forces `post_status` to `private` and clears the password, and the status select only offers Published / Pending Review / Draft.

### Bulk Edit

Selecting **Bulk Edit** and pressing Apply opens a panel above the table instead of submitting the form — the click handler on `#doaction` / `#doaction2` stops the submit when, and only when, that action is chosen. Every other bulk action still goes through `Admin::handle_bulk_action()` as a normal POST, and `edit` is deliberately absent from that method's allow-list, so with JavaScript off the Apply is a harmless no-op rather than a half-applied edit.

It offers the four fields that are meaningful across several templates at once — Template Type, Status, Priority, Active — and **every one starts on "No change"**. That is the whole contract: a bulk edit that forced all four would flatten the differences between the selected templates the moment you wanted to change one thing. `Ajax::bulk_edit()` treats an empty value per field as "leave it alone", and refuses the request outright if all four are empty rather than reporting a success that changed nothing.

The left column lists the selected templates, one per line, each with a `×` that drops it. Removing one also unticks its row, so the panel and the checkboxes can never disagree about what is about to be edited.

The panel deviates from the shared `.eael-tb-inline-row` layout in two ways, and both are alignment: the four fields are stacked **one per row** with a fixed label column, so every control shares one left edge and one width; and the right column carries a `padding-top` the height of the `BULK EDIT` legend, so the first field starts level with the top of the list rather than with the legend above it. The label column survives the sub-782px stack — there are only four one-line fields, so there is room for it, and dropping it is what leaves the controls ragged.

Two things it does not do, both on purpose: it cannot set a trash status (Move to Trash is its own bulk action), and it does not offer the per-template fields — title, slug, date, password — which have no meaning applied to a set.

Capability is re-checked **per template** inside the loop, not once for the batch. The ID list comes from the client, so it can name a template the user cannot edit, or one deleted since the page was rendered; those are counted and reported as skipped rather than failing the whole request. `Template_Cache::flush()` runs once at the end, not per template — every write invalidates the same per-type caches.

Rows repaint in place from the response, exactly as Quick Edit does; `Ajax::row_payload()` builds that payload for both, so a row updated either way ends up in the same state, stashed inline data included.

### AJAX endpoints

All six are `wp_ajax_` only (never `nopriv`), share the `eael_theme_builder` nonce, and re-check `Theme_Builder::capability()`:

| Action | Purpose |
| --- | --- |
| `eael_theme_builder_create_template` | Create the draft, return its ID + editor URL |
| `eael_theme_builder_save_conditions` | Validate + persist conditions, return conflicts and a summary. Also checks `edit_post` on the specific template |
| `eael_theme_builder_search_objects` | Populate the object picker. Takes a **condition name** and resolves the query from the registry, so it cannot be used as a generic post/term/user query proxy |
| `eael_theme_builder_quick_edit` | Save the inline editor. Also checks `edit_post`; will not set a trash status |
| `eael_theme_builder_bulk_edit` | Apply one set of changes to many templates. Re-checks `edit_post` per template, since the selection is client-supplied; will not set a trash status |
| `eael_theme_builder_get_preset` | Build one preset's elements — the site's own name and menu, fresh element IDs — for the editor to insert |

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

Add a display condition — and put it somewhere the builder can reach it:

```php
add_filter( 'eael/theme_builder/conditions', function ( $conditions ) {
	$conditions['logged_in'] = [
		'label'    => __( 'Logged-in Users', 'my-textdomain' ),
		'priority' => 40,
		'callback' => 'is_user_logged_in',
	];

	$conditions['singular']['sub_conditions'][] = 'logged_in';

	return $conditions;
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
| `eael/theme_builder/conditions` | filter | Condition definitions — the whole registry, before normalization |
| `eael/theme_builder/excluded_post_types` | filter | Post types the dynamic layer skips |
| `eael/theme_builder/excluded_taxonomies` | filter | Taxonomies the dynamic layer skips |
| `eael/theme_builder/page_template_options` | filter | Page layouts offered in Quick Edit |
| `eael/theme_builder/row_actions` | filter | Row actions on the templates list |
| `eael/theme_builder/check_rule` | filter | Outcome of a single condition evaluation |
| `eael/theme_builder/active_template_id` | filter | The template chosen for the request |
| `eael/theme_builder/should_render` | filter | Whether to render on this request |
| `eael/theme_builder/render_mode` | filter | `replace` \| `hooks` \| `theme` |
| `eael/theme_builder/replace_block_template_parts` | filter | Whether `hooks` mode replaces the theme's header/footer template parts |
| `eael/theme_builder/swap_theme_footer` | filter | Whether a footer-only replacement swaps the theme's `footer.php` in place rather than discarding it |
| `eael/theme_builder/theme_wrappers` | filter | The theme layout wrappers re-emitted after a replaced header |
| `eael/theme_builder/block_theme_spacing_reset` | filter | Whether the block theme's root padding / block gap is neutralized around a replaced part |
| `eael/theme_builder/render` | action | Manual render entry point — pass a type slug |
| `eael/theme_builder/before_render` / `after_render` | action | Around one template, receives the type slug |
| `eael/theme_builder/before_header` / `after_header` | action | Inside `Templates/header.php` |
| `eael/theme_builder/before_footer` / `after_footer` | action | Inside `Templates/footer.php` |
| `eael/theme_builder/wrapper_classes` | filter | CSS classes on the rendered wrapper |
| `eael/theme_builder/rendered_html` | filter | Final markup of a rendered template |
| `eael/theme_builder/enqueue_assets` | action | After template assets are enqueued |
| `eael/theme_builder/cache_flushed` | action | After the caches are invalidated |
| `eael/theme_builder/template_created` | action | After a template is created |
| `eael/theme_builder/auto_template_title` | filter | Name given to a template created without one |
| `eael/theme_builder/presets` | filter | The header and footer presets offered by the EA button |
| `eael/theme_builder/preset_content` | filter | The elements one preset inserts |
| `eael/theme_builder/template_orphaned` | action | A template was deactivated because its last include condition targeted a deleted object |

## Gotchas

- **Conditional tags before `template_redirect` are unreliable.** Everything in `Rules` depends on `is_singular()`, `is_archive()` and friends, which is why resolution happens at `template_redirect` and not earlier.
- **Two full-document overriders cannot both win.** The `did_action( 'wp_head' )` guard degrades gracefully, but a site running Theme Builder alongside another header/footer builder should use only one.
- **The type registry lazily registers defaults** on first `get_types()` call, so labels are translated at use time rather than at file load.
- **`Template_Cache` keys include the language code**, because `WP_Query` is language-filtered under WPML/Polylang and a cached template list is therefore language-specific.
- **Anything enqueued into the Elementor editor must not leak globals.** The editor's whole API hangs off `window.$e`, and a bundle whose top level declarations become globals can overwrite it — which is why the React app is built as an IIFE. Check a new bundle with `typeof $e.run === 'function'` in the editor console before shipping it.
