# Third-Party Integrations

How EA detects, validates against, and delegates to other plugins for ACF (Advanced Custom Fields), EmbedPress, and the ten supported form plugins (WPForms, Caldera, Gravity, Ninja, Fluent, Formstack, CF7, TypeForm, WeForms, BetterDocs Search Form). The pattern is consistent across integrations — class-existence check, asset compat shim, fallback render when the plugin is inactive — but each integration's specifics matter for debugging and extension.

If you're tracing "why doesn't my ACF gallery field show up in the Dynamic Filterable Gallery?" or "why does Gravity Forms style break inside my EA layout?" — start here.

## Overview

EA does not ship with form rendering, custom-field handling, or media-embed parsing. It hosts widgets that thinly wrap third-party plugins and degrade gracefully when those plugins are not active. The pattern repeats across every integration:

1. **Detect:** check `class_exists` / `function_exists` for the third-party plugin's signature symbol.
2. **Delegate:** call the plugin's public API (`get_field()`, `Caldera_Forms::render_form()`, `wp_get_attachment_image()`-via-EmbedPress).
3. **Compat-shim styles / scripts:** the `Enqueue` trait's [`before_enqueue_styles`](../../../includes/Traits/Enqueue.php#L11) registers each form plugin's CSS handles when the corresponding EA widget is on the page (without these shims, the form plugins lazy-load styles on direct form-page visits only — EA-embedded forms would render unstyled).
4. **Fallback:** when the third-party isn't active, render an installation prompt instead of silently emptying.
5. **No bundling:** EA never ships a copy of the third-party's code. If the plugin is missing, the feature degrades; we don't try to polyfill.

Beyond widget wrappers, EA also reads custom post meta from third-party plugins (`_eael_post_view_count` from EA's own View Counter Extension; ACF's gallery-field via `get_field`).

## Components

| Integration | EA widget(s) | Detection symbol | Source |
| ----------- | ------------ | ---------------- | ------ |
| **WPForms** | `Wpforms` | `function_exists('wpforms')` | [`Elements/WpForms.php`](../../../includes/Elements/WpForms.php), [`Enqueue.php:15`](../../../includes/Traits/Enqueue.php#L15) |
| **Caldera Forms** | `Caldera_Forms` | `class_exists('Caldera_Forms')` | [`Elements/Caldera_Forms.php`](../../../includes/Elements/Caldera_Forms.php), [`Enqueue.php:20`](../../../includes/Traits/Enqueue.php#L20), [`Helper.php:626`](../../../includes/Classes/Helper.php#L626) |
| **Gravity Forms** | `GravityForms` | `class_exists('\GFForms')` + `class_exists('\GFCommon')` | [`Elements/GravityForms.php`](../../../includes/Elements/GravityForms.php), [`Enqueue.php:25`](../../../includes/Traits/Enqueue.php#L25) |
| **Ninja Forms** | `NinjaForms` | `function_exists('Ninja_Forms')` | [`Elements/NinjaForms.php`](../../../includes/Elements/NinjaForms.php) |
| **Fluent Forms** | `FluentForm` | `class_exists('FluentForm')` | [`Elements/FluentForm.php`](../../../includes/Elements/FluentForm.php) |
| **Formstack** | `Formstack` | iframe-based; no class check | [`Elements/Formstack.php`](../../../includes/Elements/Formstack.php) |
| **Contact Form 7** | `Contact_Form_7` | `class_exists('WPCF7')` | [`Elements/Contact_Form_7.php`](../../../includes/Elements/Contact_Form_7.php) |
| **TypeForm** | `TypeForm` | iframe-based; no class check | [`Elements/TypeForm.php`](../../../includes/Elements/TypeForm.php) |
| **WeForms** | `WeForms` | `class_exists('WeForms')` | [`Elements/WeForms.php`](../../../includes/Elements/WeForms.php) |
| **BetterDocs Search** | `Betterdocs_Search_Form` | `function_exists('betterdocs')` | [`Elements/Betterdocs_Search_Form.php`](../../../includes/Elements/Betterdocs_Search_Form.php) |
| **ACF** | Multiple — Controls trait, Dynamic Filterable Gallery (Pro), advanced dynamic tags extension | `class_exists('ACF')` + `function_exists('get_field')` + `function_exists('acf_get_field_groups')` | [`Controls.php:367`](../../../includes/Traits/Controls.php#L367), [`Ajax_Handler.php:212`](../../../includes/Traits/Ajax_Handler.php#L212), [`Helper.php:1940`](../../../includes/Classes/Helper.php#L1940) |
| **EmbedPress** | `EmbedPress` | runtime `class_exists` check | [`Elements/EmbedPress.php`](../../../includes/Elements/EmbedPress.php) |
| **YITH WCWL** (wishlist) | Compat shim only | EA disables YITH AJAX during EA's own AJAX paths | [`Ajax_Handler.php:70-71`](../../../includes/Traits/Ajax_Handler.php#L70) |
| **Whols Lite** (wholesale / B2B) | Compat shim only | `function_exists('whols_lite')` | [`Helper.php:266`](../../../includes/Classes/Helper.php#L266) — see [`woocommerce-integration.md`](woocommerce-integration.md) |
| **reCAPTCHA / Cloudflare Turnstile** | Login | Register Form widget | site-key option | [`Login_Registration.php:1629-1654`](../../../includes/Traits/Login_Registration.php#L1629) — see [`login-register.md`](login-register.md) |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ DETECTION + RENDER (per-widget, on every page render)            ║
║                                                                  ║
║   Widget::render()                                               ║
║       │                                                          ║
║       ▼                                                          ║
║   if (! detection_check()) {                                     ║
║       echo render_install_prompt();                              ║
║       return;                                                    ║
║   }                                                              ║
║       │                                                          ║
║       ▼                                                          ║
║   third-party API call (get_field, render_form, etc.)            ║
║       │                                                          ║
║       ▼                                                          ║
║   Wrap output in EA-styled markup                                ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ ASSET COMPAT (Enqueue trait, on every page with relevant widget) ║
║                                                                  ║
║   eael/before_enqueue_styles fires (from Asset_Builder)          ║
║       │  carries widget slug list                                ║
║       ▼                                                          ║
║   Enqueue::before_enqueue_styles loops widgets                   ║
║       │                                                          ║
║       ▼                                                          ║
║   for each known integration:                                    ║
║     • slug present? → trigger plugin's style enqueue API         ║
║     • plugin not active? → skip (no error)                       ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ AJAX-AWARE CUSTOM FIELD QUERIES (Dynamic Filterable Gallery)     ║
║                                                                  ║
║   ajax_load_more receives request for Dynamic_Filterable_Gallery ║
║       │                                                          ║
║       ▼                                                          ║
║   if hybrid query + ACF active + acf_gallery_keys set:           ║
║     • build attachment-taxonomy map by walking parent posts'     ║
║       ACF gallery fields                                         ║
║     • adjust args to query attachment IDs from ACF galleries     ║
║     • combine with standard tax_query results                    ║
║       │                                                          ║
║       ▼                                                          ║
║   WP_Query → render → wp_send_json_success                       ║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

Third-party integrations don't introduce new hooks of their own — they participate in the existing EA hook chain documented elsewhere:

| Hook | Owner | Use |
| ---- | ----- | --- |
| `eael/before_enqueue_styles` (action) | EA | The compat-shim path triggers third-party style registration when the corresponding widget is detected |
| `eael/before_enqueue_scripts` (action) | EA | Same for scripts |
| `init` (priority 5) | EA Bootstrap | WC integration timing also covers some custom-field hooks |
| `pre_get_posts` (action, WP core) | WP | Where ACF / Whols / View Counter compat may layer their own filters |

## Per-Integration Reference

### Form integrations (the ten widgets)

Each form widget follows the same pattern:

1. **Detection** in `register_controls` — bail out with an empty controls section if the plugin is inactive.
2. **Form list control** — calls the plugin's "list all forms" API (`wpforms()->form->get`, `GFAPI::get_forms`, `Ninja_Forms()->forms()->get_forms`, etc.) to populate a SELECT.
3. **Render** — calls the plugin's render API (`wpforms_display`, `Caldera_Forms::render_form`, `gravity_form`, `Ninja_Forms()->display`, `wp_print_request_filter`).
4. **Style compat** in `Enqueue::before_enqueue_styles` — registers the plugin's stylesheet handles so the form is styled when rendered inside an EA-built page.

The Gravity Forms compat is the most extensive (registers theme-reset, theme-foundation, theme-framework styles per [`Enqueue.php:25-38`](../../../includes/Traits/Enqueue.php#L25)). WPForms is simpler: just `wpforms()->frontend->assets_css()`.

iframe-based widgets (Formstack, TypeForm) skip the integration entirely — they embed an iframe pointing at the form provider's hosted form.

Caldera Forms also has a JS handle re-registration in [`Helper.php:626`](../../../includes/Classes/Helper.php#L626) for AJAX-heavy contexts.

### ACF (Advanced Custom Fields)

ACF integration appears in three places:

**1. Field group enumeration** — [`Helper.php:1940`](../../../includes/Classes/Helper.php#L1940). When ACF is active and the user is configuring a widget that supports ACF fields, the helper walks `acf_get_field_groups()` to populate a control's options.

**2. Field controls** — [`Controls.php:367`](../../../includes/Traits/Controls.php#L367). Standard EA query controls add ACF-specific options when ACF is active (e.g. an "ACF text field" picker).

**3. Hybrid AJAX query** — [`Ajax_Handler.php:212`](../../../includes/Traits/Ajax_Handler.php#L212). Pro's Dynamic Filterable Gallery widget supports a hybrid query mode: list standard posts AND list attachment images stored in ACF gallery fields of those posts. The handler:

- Reads `eael_acf_gallery_keys` from widget settings (the ACF field keys to harvest).
- For each parent post, calls `get_field( $key, $parent_post_id )` to retrieve the ACF gallery's attachment list.
- Builds a `taxonomy_map` mapping attachment IDs to their parent's taxonomy terms (so the filter UI can target attachments by parent's category).
- Adjusts `$args` to query both attachment IDs and standard posts in one combined `WP_Query`.

This is the most complex integration in the plugin. See [`load-more-and-pagination.md § Per-Widget-Class Branches`](load-more-and-pagination.md) for the full handler trace.

### EmbedPress

[`EmbedPress.php`](../../../includes/Elements/EmbedPress.php) is a thin widget wrapper. It detects EmbedPress at render time; if absent, shows an HTML message with a link to install. When present, delegates the actual embed parsing to EmbedPress's API.

The widget's `get_custom_help_url` returns `https://embedpress.com/documentation` — sister-product cross-link for users seeking deeper docs.

### Compatibility shims (no widget, just layer)

| Shim | Purpose |
| ---- | ------- |
| YITH WCWL AJAX disable | When EA's WC AJAX flows fire (`ajax_load_more`, `eael_woo_pagination_product_ajax`), EA temporarily disables YITH wishlist's AJAX hooks to prevent double-firing. [`Ajax_Handler.php:70-71`](../../../includes/Traits/Ajax_Handler.php#L70). |
| Whols compat | Apply Whols's wholesale visibility filter to EA's product queries. See [`woocommerce-integration.md`](woocommerce-integration.md). |
| Mondial Relay | Render Mondial Relay shipping form within EA's Woo Checkout. See [`Compatibility_Support`](../../../includes/Classes/Compatibility_Support.php). |
| Beehive theme swiper | Replace Beehive theme's bundled swiper with Elementor's. [`Enqueue.php:131`](../../../includes/Traits/Enqueue.php#L131). |
| Astra theme WC loop | Restore standard WC loop wrappers when Astra is active and EA Product_Grid is on the page. See [`woocommerce-integration.md`](woocommerce-integration.md). |
| reCAPTCHA / Turnstile | Conditional script registration when Login | Register widget is on page. See [`login-register.md`](login-register.md). |

### Custom post meta read by EA

| Meta key | Source | EA usage |
| -------- | ------ | -------- |
| `_eael_post_view_count` | EA's View Counter extension writes on post view | `Helper::get_query_args` translates `orderby=most_viewed` → `meta_value_num` ordering by this key |
| `_elementor_data` | Elementor core | EA's Elements_Manager + Asset_Builder + AJAX handlers all read this; never write it |
| `_eael_widget_elements` | EA's Asset_Builder writes on save | Cached widget slug list per page |
| `_eael_custom_js` | EA's Asset_Builder writes on save | Custom-JS feature per page |
| `_elementor_template_type` | Elementor core | EA reads to identify document type (popup, header, footer, kit) |
| `eael_custom_profile_field_*` | EA's Login | Register widget writes on register | Custom profile fields surface on WP admin profile screen |
| ACF field meta keys | ACF writes via `update_field` | EA reads via `get_field` for hybrid queries and dynamic tags |

## Common Pitfalls

### Form plugin updates break style compat

Each form plugin owns its own stylesheet handle names. When upgrading WPForms / Gravity / Ninja Forms, those handle names occasionally rename. EA's [`before_enqueue_styles`](../../../includes/Traits/Enqueue.php#L11) hardcodes the names — a rename means the style compat shim silently fails and the form renders unstyled inside EA pages.

If a user reports "form looks unstyled inside EA but fine on the standalone form page", check the form plugin's recent versions for handle renames.

### ACF detection: `class_exists('ACF')` vs `function_exists('get_field')`

Some ACF forks (ACF Pro vs ACF Lite vs SCF) define different class names but the same function names. `class_exists('ACF')` may fail on a fork even though `get_field()` exists. EA mostly checks the class — but the AJAX hybrid path checks the class AND uses `get_field`. If a fork supports the function but not the class, the controls integration breaks while the AJAX path works (or vice versa) — confusing.

When supporting a new ACF fork, audit both detection sites.

### EmbedPress missing fallback re-renders on every save

EmbedPress widget without EmbedPress active emits the install prompt HTML. If the user saves the page without installing EmbedPress, the prompt is what gets cached — it appears in production. Communicate the dependency clearly in widget UX.

### YITH WCWL AJAX disable is process-scoped

The YITH AJAX disable in `Ajax_Handler` only affects the current AJAX request. If YITH's AJAX hooks fire during a non-AJAX page render that happens to overlap with EA logic, the disable doesn't apply. This shouldn't happen in practice but is worth knowing if YITH wishlist behaves oddly.

### Caldera Forms JS handle re-registration is fragile

[`Helper.php:626`](../../../includes/Classes/Helper.php#L626) re-registers Caldera's JS handle in some AJAX contexts. If Caldera updates its handle name, this breaks. Like form-plugin style compat, this is hardcoded and version-sensitive.

### `_eael_post_view_count` requires the View Counter extension

`orderby=most_viewed` translates to `meta_value_num` ordering by `_eael_post_view_count`. Posts without this meta sort as missing-meta — usually last. If the user enabled "most viewed" but never enabled View Counter, the order looks identical to default (date desc) on the surface; the user thinks the feature is broken when it's the dependency that's missing.

### iframe-based widgets ignore EA settings

Formstack and TypeForm embed iframes pointing at the provider. Most EA widget settings (margin, padding, custom CSS) apply only to the EA wrapper, not the iframe contents. Users may struggle to style the form itself.

### `class_exists` checks at register_controls vs render

A plugin can be activated between when controls register (early page lifecycle) and when render runs. Cached EA widgets may show controls that the user can interact with, but render fails because the plugin isn't found at render time. Edge case; admin-side issue mostly.

### ACF hybrid query is one of the most complex code paths in the plugin

The `eael_dfg_enable_combined_query`, `fetch_acf_image`, `fetch_acf_image_gallery`, `eael_acf_gallery_keys` flag interaction is non-trivial. When debugging, log all four flags plus the resulting `$args['post__in']` and `$args['post__not_in']` arrays before the WP_Query call.

## Debugging Guide

### Form not rendering or rendering unstyled

1. Confirm the plugin is active and the form id exists. Hardcode the form id and `var_dump( wpforms()->form->get( $form_id ) )` (or analogous).
2. Confirm EA's compat shim ran. Set a log line in [`Enqueue::before_enqueue_styles`](../../../includes/Traits/Enqueue.php#L11) inside the form-plugin branch.
3. Confirm the plugin's stylesheet handle is registered. `wp_styles()->registered['handle-name']` should not be null.
4. Compare with the standalone form page (the form plugin's own preview). If that's styled and EA's isn't, it's an EA-side compat issue. If neither is styled, it's the plugin itself.

### ACF field not appearing in widget controls

1. Confirm ACF active: `class_exists('ACF')` and `function_exists('acf_get_field_groups')` both true.
2. Confirm field group is published (not draft). ACF draft groups don't appear in `acf_get_field_groups()`.
3. Confirm the widget's control includes ACF detection: search [`Controls.php:367`](../../../includes/Traits/Controls.php#L367) for the relevant control id.

### Dynamic Filterable Gallery hybrid query empty

1. Log `$is_hybrid_query`, `class_exists('ACF')`, and `$settings['eael_acf_gallery_keys']` at [`Ajax_Handler.php:212`](../../../includes/Traits/Ajax_Handler.php#L212).
2. For each parent post id, log the result of `get_field( $key, $parent_post_id )`. Empty array = no ACF gallery configured for that post.
3. Log the resulting `$taxonomy_map['post_ids']`. If empty, the hybrid path produced no attachment ids — falls back to standard query.

### EmbedPress install prompt showing despite EmbedPress active

1. Confirm EmbedPress is active *and* its main class is loaded. Some hosts lazy-load plugin files — class may not exist at the exact moment EA checks.
2. Cache invalidation: clear EA's per-post cache via Elementor → Tools → Regenerate CSS & Data. The install prompt may have been baked into a cached bundle.

### `most_viewed` orderby returning unexpected order

1. Confirm View Counter extension is active. Search settings for "View Counter".
2. Confirm posts have `_eael_post_view_count` meta. Run `SELECT post_id, meta_value FROM wp_postmeta WHERE meta_key = '_eael_post_view_count' ORDER BY CAST(meta_value AS UNSIGNED) DESC LIMIT 10`.
3. If View Counter is active but no meta is being written, confirm the per-page integration is configured.

## Worked Example — Dynamic Filterable Gallery hybrid ACF query

The most complex third-party path. Walking through it end-to-end:

1. **Setup.** User has 5 "portfolio" posts. Each has an ACF gallery field `portfolio_images` with 3-10 image attachments. User wants the Dynamic Filterable Gallery widget to display all images across all posts, filterable by post category.
2. **Widget config.** User enables hybrid mode (`eael_dfg_enable_combined_query = 'yes'`), sets ACF field keys (`eael_acf_gallery_keys = ['portfolio_images']`).
3. **First render.** Widget render walks the 5 posts, for each calls `get_field('portfolio_images', $post_id)`, collects all attachment IDs into a flat list. Builds a `taxonomy_map` mapping each attachment id to its parent post's categories. Renders gallery items with the parent's category as the filter key on each item.
4. **User clicks load more.** Frontend JS reads existing `data-itemid` attributes (the displayed attachment ids), sends them as `exclude_ids` to AJAX.
5. **Server handler `ajax_load_more` fires.** Detects Pro `Dynamic_Filterable_Gallery` class branch.
6. **Hybrid block runs.** [`Ajax_Handler.php:212`](../../../includes/Traits/Ajax_Handler.php#L212): confirms `is_hybrid_query`, `class_exists('ACF')`, `eael_acf_gallery_keys` non-empty.
7. **`build_dfg_acf_taxonomy_map` runs.** Walks parent posts again, calls `get_field` for each ACF key, merges results, computes attachment-to-taxonomy map.
8. **`$args` adjustment.** `$args['post__in']` becomes the new attachment ids (filtered against `exclude_ids`). `$args['post_type'] = 'any'`. `$args['post_status'] = 'any'` (attachments use `inherit` status — `'any'` is needed to find them). `$args['orderby'] = 'post__in'` to preserve the order of the post__in array.
9. **WP_Query runs.** Returns the requested attachment posts.
10. **Template renders.** Each item gets the parent's category data attribute for filter targeting.
11. **Response.** JSON with rendered HTML.

The complexity is justified by the use case — a single visual gallery aggregating images from multiple "portfolio" posts, filterable by the parent post's taxonomy. No off-the-shelf plugin combination achieves this pattern.

## Architecture Decisions

### Detect, delegate, fallback — the consistent pattern

- **Context:** EA wraps a dozen third-party plugins. Each could bundle differently, fail differently, version differently. A consistent pattern reduces cognitive load.
- **Decision:** Every integration follows the same three-step shape: detection (`class_exists` / `function_exists`), delegation (third-party API call), fallback (install prompt + early return).
- **Alternatives rejected:** Per-plugin custom integration (high maintenance); polyfill missing features (defeats the purpose of integrating); fail silently (poor UX).
- **Consequences:** Adding a new third-party widget is mechanical — copy the pattern from an existing one, adjust the detection symbols. The trade-off is that new integration patterns (the hybrid ACF query) feel out-of-place in the codebase.

### No bundling of third-party code

- **Context:** Bundling Caldera or Gravity into EA would let users avoid installing them separately, but: (a) license incompatibility, (b) version drift from the plugin's main release, (c) duplicate DB writes if both are installed.
- **Decision:** EA never ships third-party code. Widgets fall back gracefully when the third-party is missing.
- **Alternatives rejected:** Bundle (license + drift); auto-install on widget activation (too aggressive); fail at activation if dependency missing (poor UX).
- **Consequences:** Users must install dependencies separately. The install-prompt fallback is a soft acceptance of this. UX trade-off accepted.

### Asset compat shims live in the `Enqueue` trait, not per-widget

- **Context:** The form widgets need their plugin's CSS to load when the form widget is on a page. Each widget could call its plugin's enqueue API directly in `render()`, but this would mean the enqueue happens during render — too late for proper handle deps.
- **Decision:** Centralise in the `Enqueue` trait, hooked on `eael/before_enqueue_styles` which fires at the right time in the asset lifecycle.
- **Alternatives rejected:** Per-widget render-time enqueue (timing issues); shared shim in Asset_Builder (couples Asset_Builder to specific third-party knowledge).
- **Consequences:** Adding a new form integration means adding a branch in `Enqueue::before_enqueue_styles`. The trait grows linearly with integrations — current 10 forms + reCAPTCHA = 11 branches. Manageable.

### ACF hybrid query is intentionally a one-off

- **Context:** The Dynamic Filterable Gallery hybrid query path is unique in its complexity. It would be tempting to generalise into "EA's pattern for combining custom-meta and standard queries".
- **Decision:** Keep it scoped to this one widget's handler. Don't generalise prematurely.
- **Alternatives rejected:** Build a "hybrid query builder" framework (over-engineering for the only known use case).
- **Consequences:** The block is one of the trickier sections in `Ajax_Handler`. New devs encountering it for the first time will need to read it carefully. Documented here and in the load-more doc as a worked example to ease first-encounter friction.

### Cookie-first persistence over server-side state for compare and view-counter

- **Context:** Anonymous visitors should be able to add to compare list / view counter increments without registering or having a session.
- **Decision:** Compare uses an `eael_compare` cookie; view counter writes per-post meta on every view (no per-user tracking).
- **Alternatives rejected:** Session-based (host-dependent and privacy-questionable); transients keyed by IP (privacy + accuracy).
- **Consequences:** Compare cookies don't sync across devices for the same logged-in user. View counter doesn't distinguish per-user views (a single user refreshing 100 times bumps the count 100 times). Acceptable for the use cases.

## Known Limitations

- **Form plugin handle names hardcoded.** Plugin updates can rename handles, breaking the compat shim silently.
- **ACF detection inconsistency.** `class_exists('ACF')` vs `function_exists('get_field')` — two checks may diverge on forks.
- **EmbedPress install-prompt cached.** Saving a page without EmbedPress installed bakes the prompt into the cache.
- **iframe-based form widgets ignore EA styles.** Formstack / TypeForm style edits don't reach the iframe contents.
- **`_eael_post_view_count` invisible dependency.** Missing View Counter extension makes "most viewed" sort look like default with no error.
- **Hybrid ACF query complexity.** Multi-flag interaction in `Ajax_Handler.php:212-254` is fragile to refactor.
- **No third-party version detection.** EA assumes the latest plugin version's API. Older versions of WPForms / Caldera can have different signatures, leading to fatal errors on call.
- **YITH AJAX disable doesn't cover all YITH features.** Only addresses YITH wishlist's AJAX hooks; YITH compare or YITH waitlist may still fire alongside EA's AJAX.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — folder index; this is "Flow 5" (third-party field resolution) in the system diagram.
- **Architecture:** [`./ajax-endpoints.md`](ajax-endpoints.md) — full AJAX handler inventory; some handlers participate in third-party integration paths.
- **Architecture:** [`./load-more-and-pagination.md`](load-more-and-pagination.md) — Dynamic Filterable Gallery hybrid query is the largest worked example using ACF integration.
- **Architecture:** [`./woocommerce-integration.md`](woocommerce-integration.md) — Whols, Astra, Mondial Relay compat shims documented there.
- **Architecture:** [`./login-register.md`](login-register.md) — reCAPTCHA / Cloudflare Turnstile integration.
- **Architecture:** [`../asset-loading.md`](../asset-loading.md) — `eael/before_enqueue_styles` hook lifecycle that asset compat shims hook into.
- **Skills:** [`debug-widget`](../../../.claude/skills/debug-widget/SKILL.md) — when a third-party feature fails, the debug skill's render or AJAX trace path leads here.
- **Skills:** [`new-widget`](../../../.claude/skills/new-widget/SKILL.md) — Phase 1 (Gather Requirements) covers vendor library decisions; this doc complements with third-party-plugin decisions.
