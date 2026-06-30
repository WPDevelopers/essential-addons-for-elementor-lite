# Team Member Widget

> Single-team-member card widget — avatar + name + job title + description + social-profile icon list. **Pure CSS, no JS, no AJAX.** 6 skin variants (2 Lite + 4 Pro-locked) selected via image-CHOOSE control. Render is a single template branching only on `eael-team-members-social-bottom`/`-right` skins (which call Pro-injected `do_action` markup hooks) and the description-overlay switch. **Lite ships 2 unrestricted skins (`simple` + `overlay`); the other 4 (`centered`/`circle`/`social-bottom`/`social-right`) show a "Only available in pro version!" HEADING when selected — but the SELECT still saves them and PHP/SCSS still applies the skin's prefix class.** Sibling pattern to Testimonial (same shape, no Repeater, render-cache enabled, FA4 ICONS shim, empty `content_template()`).

**Class file:** [`includes/Elements/Team_Member.php`](../../includes/Elements/Team_Member.php)
**Slug:** `team-members` (widget id `eael-team-member`) ⚠️ slug plural, widget id singular — same mismatch as Testimonial
**Public docs:** <https://essential-addons.com/elementor/docs/team-members/>
**Pro-shared:** ✅ Yes — Pro hooks `eael_team_member_style_presets_options` filter to unlock 4 additional skins (`centered`, `circle`, `social-bottom`, `social-right`) and emits markup via `do_action('eael/team_member_social_right_markup', …)` / `do_action('eael/team_member_social_botton_markup', …)` injection points. Lite shows `eael_section_pro` upsell panel when Pro inactive. Pro also injects circle-specific image controls via `do_action('eael/team_member_circle_controls', $this)`.

---

## Overview

Single-instance card widget. The skin SELECT exposes **all 6 options to the panel including the 4 Pro-only skins**; an `eael_team_members_preset_pro_alert` HEADING control appears below the SELECT when one of the 4 Pro skins is chosen ([line 118](../../includes/Elements/Team_Member.php#L118)) — but the SELECT remains writable. Saving a widget with a Pro-only skin selected applies the prefix class to the wrapper in Lite (skin SCSS is in Lite's stylesheet) but social-right and social-bottom markup is missing because `do_action('eael/team_member_social_right_markup', …)` / `…_botton_markup` (sic) have no Lite listener. Net Lite-side experience for those two skins is broken-looking (image without social column). Simple + Overlay variants work fully in Lite.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Simple skin (`eael-team-members-simple`) | ✅ | ✅ |
| Overlay skin (`eael-team-members-overlay`) | ✅ | ✅ |
| Centered skin | ❌ — selectable from panel; SCSS class applies but layout is degraded; "Only available in pro version!" HEADING shows below SELECT | ✅ |
| Circle skin | ❌ — same as Centered; Pro also injects `eael/team_member_circle_controls` action which adds circle-specific image controls (absent in Lite panel) | ✅ |
| Social-on-Bottom skin | ❌ — selectable; saves; `do_action('eael/team_member_social_botton_markup', …)` is unhooked in Lite → social-row missing from render output | ✅ |
| Social-on-Right skin | ❌ — same as Social-on-Bottom but with `…_social_right_markup` | ✅ |
| FA4 ICONS shim per social-profile item | ✅ — Repeater per-item uses `fa4compatibility => 'social'` (old `social` field auto-migrates to `social_new`) | ✅ |
| Description Overlay (simple skin only) | ✅ | ✅ |
| Per-element HTML tag picker (Name + Job title — 9 tags each: h1-h6, div, span, p) | ✅ | ✅ |
| Social profile Repeater (per-item icon + link with `is_external` / `nofollow` flags) | ✅ | ✅ |
| `eael_section_pro` upsell panel | shown | hidden |
| Pro extension hooks (`eael_team_member_style_presets_options` filter + `_style_presets_condition` filter + 3 `do_action` injection points) | ❌ — emitted, no Lite listeners | ✅ |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Team_Member.php`](../../includes/Elements/Team_Member.php) | PHP widget class (~1,118 lines) — controls, render (single inline template with branching for description-overlay + 2 social-markup hook points) |
| [`src/css/view/team-members.scss`](../../src/css/view/team-members.scss) | Source styles (~149 lines, smallest SCSS in Business/E-commerce) — per-skin layouts, social-icon list, overlay variant, image-rounded variant |
| [`config.php`](../../config.php#L156) entry `'team-members'` | `Asset_Builder` declaration — **CSS only**, no JS dependency |
| `assets/admin/images/layout-previews/team-preset-{key}.png` | Skin preview thumbnails (filename strips `eael-team-members-` prefix) |
| (no `src/js/view/team-members.js`) | — pure CSS widget |

## Architecture

- **Skin SELECT exposes Pro skins in Lite but doesn't gate the value** — `eael_team_member_style_presets_options` filter at [line 77](../../includes/Elements/Team_Member.php#L77) seeds the SELECT with 6 options regardless of Pro state. The `_style_presets_condition` filter at [line 111](../../includes/Elements/Team_Member.php#L111) returns a list of 4 Pro skin keys; an `eael_team_members_preset_pro_alert` HEADING control shows below when one of those keys is selected — purely informational. The user CAN save the widget with any value; render branches on it without checking Pro status.
- **Skin classes are written verbatim to wrapper `class` attribute** — `$team_member_classes = $this->get_settings('eael_team_members_preset')` at [line 1042](../../includes/Elements/Team_Member.php#L1042). All 6 skin SCSS classes are bundled in the Lite stylesheet at `src/css/view/team-members.scss` — selecting a Pro skin in Lite applies the visual styling, just without the Pro-injected social markup. Net effect: Centered + Circle render correctly in Lite; Social-Bottom + Social-Right render missing the social icon strip.
- **Two Pro markup hooks for social layouts** — `do_action('eael/team_member_social_right_markup', $settings, $this)` at [render line 1062](../../includes/Elements/Team_Member.php#L1062) and `do_action('eael/team_member_social_botton_markup', $settings, $this)` at [render line 1085](../../includes/Elements/Team_Member.php#L1085). **`botton` is a typo for `bottom`** — preserved verbatim in the hook name; Pro listeners must use the misspelt form. Don't fix without a dual-emit migration.
- **Default social-profile Repeater includes a `fab fa-google-plus` icon** at [line 409](../../includes/Elements/Team_Member.php#L409). Google+ shut down in April 2019 — the default value is obsolete. New widgets get a broken default brand icon; users must replace it manually.
- **FA4 ICONS shim per Repeater item** — see [_patterns.md § FA4 → FA5 icon migration shim](_patterns.md#fa4--fa5-icon-migration-shim). Field names: legacy `social`, new picker `social_new` with `fa4compatibility => 'social'`. Render at [line 1090](../../includes/Elements/Team_Member.php#L1090) branches on `__fa4_migrated` or empty-legacy.
- **`is_dynamic_content() = false`** ([line 55](../../includes/Elements/Team_Member.php#L55)) — enables Elementor render cache. Widget renders once per save and caches the HTML. Pro's `do_action` injection points fire only during the first render (when cache is empty) — if Pro is installed after a widget is cached, social-row stays missing until cache invalidates.
- **No `content_template()` stub** — no explicit empty method override; editor preview goes through full server `render()` via AJAX on every settings change. Slower editor but exact production match. Inherited from `Widget_Base` default.
- **Image alt fallback uses widget name** at [line 1035](../../includes/Elements/Team_Member.php#L1035) — when attachment has no `_wp_attachment_image_alt` meta, falls back to `$settings['eael_team_member_name']` ("John Doe" default). Accessibility-friendly behaviour but ties alt text to a content control instead of the image's actual descriptor.
- **`get_attachment_image_src` returns a string URL when called with positional args** at [line 1038](../../includes/Elements/Team_Member.php#L1038) — uses the 3-arg form `($id, 'thumbnail', $settings)` which selects the size matrix. Image size is hardcoded to `'thumbnail'` despite the Group_Control_Image_Size declaring `default => 'full'` at [line 161](../../includes/Elements/Team_Member.php#L161). **The size control is functionally dead** — render always pulls thumbnail size.
- **Description rendered in two distinct positions** — overlay-mode prints `<p class="eael-team-text eael-team-text-overlay">` INSIDE `.eael-team-image` at [line 1068](../../includes/Elements/Team_Member.php#L1068); default mode prints `<p class="eael-team-text">` INSIDE `.eael-team-content` at [line 1111](../../includes/Elements/Team_Member.php#L1111). Switching modes moves the description from one DOM container to another — CSS rules targeting `.eael-team-content .eael-team-text` will silently stop matching when overlay enabled.
- **HTML tag pickers for Name + Job Title** at [lines 200, 274](../../includes/Elements/Team_Member.php#L200) — 9 tag options each (h1, h2, h3, h4, h5, h6, div, span, p). `Helper::eael_validate_html_tag()` validates server-side before printf to prevent injection. Default name=`h2`, position=`h3`. Selecting `<p>` for both produces non-semantic markup but is panel-allowed.
- **`get_categories() = ['essential-addons-for-elementor-lite']`** at [line 34](../../includes/Elements/Team_Member.php#L34) — **wrong category slug** (should be `'essential-addons-elementor'`). This means the widget appears under a category that doesn't match the EA panel grouping in Elementor's editor. Same typo as Post_Timeline (documented in that widget's known limitations). Cosmetic — Elementor falls back to a separate "Essential Addons for Elementor Lite" section.

## Render Output

```html
<div id="eael-team-member-{widget-id}"
     class="eael-team-item
            {skin-class}                              ← one of eael-team-members-simple|-overlay|-centered|-circle|-social-bottom|-social-right
            {image-rounded-class}                     ← from eael_team_members_image_rounded
            eael-team-align-[default|left|centered|right]">   ← prefix_class from alignment SELECT
  <div class="eael-team-item-inner">

    <div class="eael-team-image">
      <figure>
        [?] <img src="{thumbnail-size-URL}" alt="{alt or fallback to name}">
      </figure>

      [?] <!-- Pro injection point: social-right markup -->
      {do_action('eael/team_member_social_right_markup', $settings, $this)}    ← only when skin == social-right; Lite has no listener (empty)

      [?] <!-- Description Overlay: simple skin only, INSIDE image div -->
      <p class="eael-team-text eael-team-text-overlay">{description}</p>
    </div>

    <div class="eael-team-content">
      <{title_tag} class="eael-team-member-name">{name}</{title_tag}>            ← h1-h6 / div / span / p
      <{position_tag} class="eael-team-member-position">{job_title}</{position_tag}>

      [?] <!-- Pro injection point: social-bottom markup -->
      {do_action('eael/team_member_social_botton_markup', $settings, $this)}    ← only when skin == social-bottom; Lite has no listener (TYPO: botton)

      [?] <!-- Default social + description (NOT social-right OR social-bottom skin) -->
      [?] <ul class="eael-team-member-social-profiles">                        ← when enable_social_profiles == 'yes' AND skin != social-right
        <li class="eael-team-member-social-link">
          <a href="{link}" [target="_blank"] [rel="nofollow"]>
            <i class="{fab fa-X}"></i>                                          ← legacy FA4 field
            -- OR --
            {Icons_Manager::render_icon($social_new)}                           ← new ICONS picker
            -- OR (SVG upload) --
            <img src="{svg url}" alt="{alt}">
          </a>
        </li>
        …
      </ul>

      [?] <p class="eael-team-text">{description}</p>                          ← when description-overlay NOT enabled
    </div>

  </div>
</div>
```

Notes:

- `botton` (sic) typo in `eael/team_member_social_botton_markup` is preserved verbatim.
- `prefix_class = 'eael-team-align-'` on alignment SELECT — default value is the literal string `eael-team-align-default` which produces an awkward `eael-team-align-eael-team-align-default` class (Elementor's prefix_class concatenates default verbatim). Likely bug at [line 563](../../includes/Elements/Team_Member.php#L563) — most prefix_class controls use plain values like `default`, `left`, etc. Inspect to confirm.
- Repeater `title_field` is `<i class="{{ social_new.value }}"></i>` — but `social_new.value` is an object `{value, library}`, not the class string. The Elementor panel may show `[object Object]` for newer items.
- Image alt falls back to `$settings['eael_team_member_name']` when attachment has no alt meta.
- All 6 skin classes ship in Lite SCSS even though only 2 are panel-supported in Lite — Pro upgrade is purely additive Repeater + Markup-injection.
- The eael_team_text overlay branch and default branch are MUTUALLY EXCLUSIVE — description text appears in exactly one location.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Team_Member.php#L68) — 7 main sections (Layout / Image / Content / Social Profiles / Pro Upsell / Content Card Style / Image Style / + more style sections).

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_team_members_preset` | CHOOSE (image picker) | `eael-team-members-simple` | Content → Layout | 1 of 6 skin variants; 2 Lite-functional + 4 Pro-only |
| `eael_team_members_preset_pro_alert` | HEADING | — | Content → Layout | Informational notice when Pro skin selected; doesn't gate value |
| `eael_team_member_image` | MEDIA | (placeholder) | Content → Image | Avatar source |
| `thumbnail` Group_Control_Image_Size | — | `full` | Content → Image | **Functionally dead** — render hardcodes `'thumbnail'` size at [line 1038](../../includes/Elements/Team_Member.php#L1038) |
| `eael_team_member_name` | TEXT (dynamic + AI) | `John Doe` | Content → Content | Name |
| `eael_team_member_name_tag` | CHOOSE | `h2` | Content → Content | HTML tag for name (9 options) |
| `eael_team_member_job_title` | TEXT (dynamic + AI) | `Software Engineer` | Content → Content | Job title |
| `eael_team_member_job_title_tag` | CHOOSE | `h3` | Content → Content | HTML tag for position |
| `eael_team_member_description` | TEXTAREA (dynamic) | (default copy) | Content → Content | Bio text |
| `eael_team_member_enable_social_profiles` | SWITCHER | `yes` | Content → Social Profiles | Toggle social UL |
| `eael_team_member_social_profile_links` | REPEATER | (FB, Twitter, **Google+**, LinkedIn) | Content → Social Profiles | Per-item: `social_new` ICONS picker + `link` URL; **Google+ default is dead service** |
| `eael_control_get_pro` | CHOOSE | `1` | Content → Go Premium for More Features | Decorative — `eael_section_pro` upsell only (hidden when Pro active) |
| `content_card_height` | SLIDER | — | Style → Content Card | Sets `min-height` on `.eael-team-content` |
| `eael_team_members_enable_text_overlay` | SWITCHER | `no` | Style → Content Card | Moves description INSIDE image div with overlay class; **simple skin only** |
| `eael_team_members_overlay_background` | COLOR | `rgba(255,255,255,0.8)` | Style → Content Card | Overlay backdrop |
| `eael_team_members_alignment` | CHOOSE | `eael-team-align-default` (⚠️ literal class as default) | Style → Content Card | `prefix_class => 'eael-team-align-'` |
| `eael_team_members_image_width` | RESPONSIVE SLIDER | 100% | Style → Image | Image width; condition excludes circle skin |
| `eael_team_members_image_rounded` | (helper control) | — | Style → Image | Image-rounded variant class |
| Style → various (Name, Position, Description, Social Icons, Overlay, etc.) | — | — | Style tab | Typography, color, padding, margin, border per element |

## Conditional Dependencies

```text
eael_team_members_preset_pro_alert        → visible when eael_team_members_preset is one of [centered, circle, social-bottom, social-right]
thumbnail (image size)                    → visible when eael_team_member_image[url] != ''
eael_team_members_enable_text_overlay     → visible when eael_team_members_preset == 'eael-team-members-simple'
eael_team_members_overlay_background      → visible when preset == 'eael-team-members-overlay' OR text_overlay == 'yes'
eael_team_members_image_width             → visible when preset != 'eael-team-members-circle'
eael_team_member_social_profile_links     → visible when eael_team_member_enable_social_profiles == 'yes'

eael_section_pro / eael_control_get_pro   → visible when Pro plugin is NOT active
```

Skin-specific style sub-sections (e.g. "Bio Text" overlay styles) are NOT conditioned on the skin SELECT — users see "dead" controls in the panel.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael_team_member_style_presets_options` | filter (emitted in `register_controls`) | `array $template_list` | Add / replace skin SELECT options (Pro injects 4 Pro-only skins as enabled here, not gated by `eael/pro_enabled`) |
| `eael_team_member_style_presets_condition` | filter (emitted in `register_controls`) | `array $pro_keys` | Set of skin keys to show "Only available in pro version!" notice for |
| `eael/team_member_circle_controls` | action (emitted in `register_controls` → image style section) | `(Widget_Base $widget)` | Pro registers circle-specific image controls (size, ratio) |
| `eael/team_member_social_right_markup` | action (emitted in `render` → inside `.eael-team-image`) | `(array $settings, Widget_Base $widget)` | Pro emits social-icon column when social-right skin selected |
| `eael/team_member_social_botton_markup` ⚠️ typo | action (emitted in `render` → inside `.eael-team-content`) | `(array $settings, Widget_Base $widget)` | Pro emits social-icon row when social-bottom skin selected — **misspelt as "botton"; preserved verbatim** |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Gates `eael_section_pro` upsell only |

No widget-emitted prefix `eael/` filter for the social Repeater itself.

## JavaScript Lifecycle

> N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`.

## Common Issues

### Pro skin saved in Lite produces broken card

- **Likely cause:** Skin SELECT doesn't gate Pro values — user can save `eael-team-members-social-bottom` (or `-social-right`) in Lite. SCSS applies the skin class but `do_action('eael/team_member_social_botton_markup', …)` has no Lite listener — social-row markup is missing from output.
- **Diagnose:** Inspect the rendered widget — `.eael-team-item.eael-team-members-social-bottom` exists but the expected social icon row is absent.
- **Fix:** Either revert to `simple` / `overlay` skin, or install Pro. For programmatic enforcement, filter `eael_team_member_style_presets_options` to remove Pro skins when `apply_filters('eael/pro_enabled', false)` is false.

### Default Google+ icon broken on new widgets

- **Likely cause:** Default Repeater value at [line 409](../../includes/Elements/Team_Member.php#L409) includes `'social_new' => ['value' => 'fab fa-google-plus', 'library' => 'fa-brands']`. Google+ service shut down April 2019; the FA icon may still render but the link target is dead.
- **Diagnose:** New widget instances show 4 default social icons including Google+; clicking goes nowhere.
- **Fix:** Replace the icon and link with a current service per-widget. To fix globally, patch the default array.

### Image size setting has no effect

- **Likely cause:** Render at [line 1038](../../includes/Elements/Team_Member.php#L1038) hardcodes `'thumbnail'` regardless of the Group_Control_Image_Size selection.
- **Diagnose:** Switch image size from full → medium in panel; saved markup still pulls thumbnail-size URL.
- **Fix:** Patch `Group_Control_Image_Size::get_attachment_image_src($id, 'thumbnail', $settings)` to `('thumbnail', $settings)` (two-arg form which respects the group control name).

### Description text moves to image area unexpectedly

- **Likely cause:** `eael_team_members_enable_text_overlay = yes` switch enabled. Description renders inside `.eael-team-image` instead of `.eael-team-content`. CSS rules targeting `.eael-team-content .eael-team-text` stop matching.
- **Diagnose:** Description visually overlays the image when text-overlay is on; selectors break.
- **Fix:** Disable text-overlay, or update CSS to target `.eael-team-text` regardless of parent.

### Pro plugin activates but widget stays in broken state

- **Likely cause:** `is_dynamic_content() = false` enables Elementor render cache. Widget HTML is cached at last save time; subsequent activations of Pro don't trigger re-render of cached pages.
- **Diagnose:** Pro is active site-wide but a specific Team Member widget instance still renders without Pro markup.
- **Fix:** Resave the Elementor page (or run `wp eval 'wp_cache_flush()'`) to invalidate cached render.

## Known Limitations

- **Skin SELECT exposes Pro skins in Lite** without gating the saved value ([lines 77, 111](../../includes/Elements/Team_Member.php#L77)) — user can save broken widget state. Only an informational HEADING shows below the SELECT.
- **`botton` typo in `eael/team_member_social_botton_markup` hook name** ([line 1085](../../includes/Elements/Team_Member.php#L1085)) — preserved verbatim for back-compat; Pro listeners must use the misspelt form.
- **Default social Repeater includes obsolete Google+ icon** ([line 409](../../includes/Elements/Team_Member.php#L409)) — service shut down April 2019; new widgets get a broken default.
- **Image size control is functionally dead** — render hardcodes `'thumbnail'` at [line 1038](../../includes/Elements/Team_Member.php#L1038); Group_Control_Image_Size selection has no effect on output URL.
- **`get_categories()` returns the wrong category slug** ([line 34](../../includes/Elements/Team_Member.php#L34)) — `'essential-addons-for-elementor-lite'` instead of `'essential-addons-elementor'`. Widget appears under a separate Elementor section. Same typo as Post_Timeline.
- **Alignment default value is the literal full class string** `'eael-team-align-default'` with `prefix_class => 'eael-team-align-'` at [line 563](../../includes/Elements/Team_Member.php#L563) — Elementor prepends the prefix again, producing `class="eael-team-align-eael-team-align-default"`. Likely bug; alignment-default state may not match expected CSS.
- **Repeater `title_field` is `<i class="{{ social_new.value }}"></i>`** at [line 421](../../includes/Elements/Team_Member.php#L421) but `social_new.value` is an object (`{value, library}`) not the class string — newer Repeater items show literal `[object Object]` in panel title bar.
- **`is_dynamic_content() = false` caches the rendered widget** — Pro's `do_action` injection points fire only when cache is empty; activating Pro after a widget is cached produces no visual change until cache invalidates.
- **`prefix_class` typo for alignment "centered" option** — value is `centered` not `center`; SCSS rules using `.eael-team-align-center` would not match (must use `.eael-team-align-centered`).
- **`eael_team_members_enable_text_overlay` is conditioned on `simple` skin only** ([line 493](../../includes/Elements/Team_Member.php#L493)) — `overlay` skin (which IS the overlay layout) has the overlay always on; the toggle is confusingly named.
- **Pure-CSS widget with `is_dynamic_content() = false`** — dynamic-tag content (Name, Job, Description, Image all support dynamic) won't refresh until cache invalidates.
- **No `do_action` injection points for non-social skins** — Pro can't extend Simple or Overlay rendering; the only Pro extension hooks target social-bottom/right markup and circle controls.
- **Default skin SELECT exposes 6 options including 4 Pro-only** — UX choice that prioritises Pro discovery over Lite-only clarity; users must trial-and-error to find which 2 skins actually work in Lite.
