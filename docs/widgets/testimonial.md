# Testimonial Widget

> Single-testimonial display widget — quote + reviewer image + name + company + star rating + CSS quote ornament. **Pure CSS, no JS, no AJAX, no API integration**. 8 skin variants selected via image-CHOOSE control, each rendered by its own if-block in `render()` calling shared partial helpers (`render_testimonial_image`, `render_testimonial_rating`, `render_user_name_and_company`, `testimonial_desc`). Smallest widget in the Business/E-commerce category by file count (1 PHP, 1 SCSS, 0 JS).

**Class file:** [`includes/Elements/Testimonial.php`](../../includes/Elements/Testimonial.php)
**Slug:** `testimonials` (widget id `eael-testimonial`) ⚠️ slug is plural, widget id is singular — mismatch; see [`docs/architecture/asset-loading.md`](../architecture/asset-loading.md) for how Asset_Builder reconciles these
**Public docs:** <https://essential-addons.com/elementor/docs/testimonials/>
**Pro-shared:** ✅ Yes — Pro adds widget-specific features behind `apply_filters('eael/pro_enabled', false)` gate at [line 312](../../includes/Elements/Testimonial.php#L312). Lite shows the standard `eael_section_pro` upsell panel when Pro is inactive. **No widget-specific `do_action` injection points** — Pro extensions are purely via the panel-level `eael_section_pro` upsell gate; rendering is identical between Lite and Pro.

---

## Overview

Eight skin variants → eight if-blocks in `render()`. Each variant composes the same four partial helpers in different orders and with different wrapper divs. Star rating is a static 5-`<li>` list with `.testimonial-star-rating.rating-five` etc. — visual filling is **CSS-driven via the wrapper's `rating-N` class**, not a fractional star icon swap. **`content_template()` is empty** — editor preview uses server-side `render()` via AJAX. `is_dynamic_content() = false` enables Elementor's render cache for this widget. Star rating SVG choice is hardcoded `fas fa-star`; no FA4 shim consumption for stars (only declared as `get_script_depends()` for compat with other elements).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All 8 skin variants | ✅ | ✅ |
| Avatar / image | ✅ | ✅ |
| Star rating (5 fixed positions; partial fill via wrapper `rating-N` class) | ✅ | ✅ |
| CSS quote ornament | ✅ | ✅ |
| Gradient background | ✅ | ✅ |
| `eael_section_pro` upsell panel | shown | hidden |
| Widget-specific Pro extension hooks | — | — — no `do_action` injection points; rendering identical |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Testimonial.php`](../../includes/Elements/Testimonial.php) | PHP widget class (~1,127 lines) — controls, render dispatcher (8 skin if-blocks), 4 partial helpers (`render_testimonial_image`, `render_testimonial_rating`, `render_user_name_and_company`, `testimonial_desc`) |
| [`src/css/view/testimonials.scss`](../../src/css/view/testimonials.scss) | Source styles (~333 lines) — per-skin layouts, star-rating CSS, quote ornament `.eael-testimonial-quote`, image-rounded variants, rating-N partial-fill |
| [`config.php`](../../config.php#L168) entry `'testimonials'` | `Asset_Builder` declaration — **CSS only**, no JS dependency |
| `assets/admin/images/layout-previews/testimonial-{skin}.png` | Skin preview thumbnails for image-CHOOSE control |
| (no `src/js/view/testimonials.js`) | — pure CSS widget, no widget JS |

## Architecture

- **Single-instance widget — no Repeater, no carousel** — one testimonial per widget instance. To show multiple testimonials, the user places multiple Testimonial widgets in a column / inner section. Pro's Testimonial Slider is a **separate widget** (`eael-testimonial-slider`), not a layout option here.
- **8 skin if-blocks in `render()`** — each branch at [lines 985-1114](../../includes/Elements/Testimonial.php#L985) composes the four partial helpers differently. Adding a new skin requires both a new SELECT option AND a new if-block; no template dispatcher (`switch` or template-include) is used. The `simple-layout` skin reuses `icon-img-left-content.png` as its preview thumbnail at [line 130](../../includes/Elements/Testimonial.php#L130).
- **Skin SELECT mismatch with preview thumbnails** — `content-top-icon-title-inline` uses `content-bottom-icon-title-inline.png`, and `content-bottom-icon-title-inline` uses `content-top-icon-title-inline.png` ([lines 122 and 126](../../includes/Elements/Testimonial.php#L122)). **Image filenames are swapped vs option keys** — picking "Content Top | Icon Title Inline" in the panel shows the "bottom" preview image. Layout is correct at render time; only the preview is wrong.
- **Star rating is fixed 5-star with CSS-driven partial fill** — `render_testimonial_rating()` emits `<ul class="testimonial-star-rating">` with 5 `<li><i class="fas fa-star">` items unconditionally. The wrapper inherits a `rating-N` class (one of `rating-one` / `rating-two` / `rating-three` / `rating-four` / `rating-five`) from `eael_testimonial_rating_number`. SCSS targets `.rating-three .testimonial-star-rating li:nth-child(n+4) { color: gray }` or similar to dim non-filled stars. **Fractional ratings (e.g. 4.5 stars) are not supported** — only whole numbers 1-5.
- **`render_testimonial_image()` uses `get_settings()` not `get_settings_for_display()`** at [line 903](../../includes/Elements/Testimonial.php#L903) — bypasses dynamic-tag resolution for the image. The other three partial helpers (`render_testimonial_rating`, `render_user_name_and_company`, `testimonial_desc`) use `get_settings_for_display()`. Inconsistent — image control doesn't support dynamic tags as a side effect.
- **Quote ornament is a pure CSS element** — `<span class="eael-testimonial-quote"></span>` injected after all skins when `show_quote=yes`. SCSS `::before` rule on this span renders the quote glyph via CSS content. Single sprite-free quote mark for all skins.
- **Empty `content_template()` stub** ([line 1126](../../includes/Elements/Testimonial.php#L1126)) — see [_patterns.md § Empty content_template stub](_patterns.md#empty-content_template-stub). Editor preview routes through `wp-admin/admin-ajax.php` → server `render()`, which exactly mirrors production output (essential because `simple-layout` differs subtly from default and Elementor's JS template language can't easily express the 8-branch dispatch). Trade-off: slower editor on settings change.
- **`is_dynamic_content() = false`** ([line 55](../../includes/Elements/Testimonial.php#L55)) — enables Elementor's render-cache for this widget. Means the widget renders **once** per Elementor save and is cached HTML thereafter; subsequent renders bypass the 8-branch dispatch.
- **No `get_script_depends()` actually needed** — declares `['font-awesome-4-shim']` at [line 76](../../includes/Elements/Testimonial.php#L76) but the widget has no JS. FA4 shim handle is a runtime CSS / JS pair; this widget benefits only from the CSS half (already in `get_style_depends`). Script dep is stale.
- **8 skins, no Pro extension surface to add a 9th** — Pro can extend via `apply_filters('eael/pro_enabled')` gating in the panel, but no `do_action('eael/testimonial/skins')` filter exists to register a new skin from Pro. Adding a skin requires patching this class.

## Render Output

```html
<div id="eael-testimonial-{widget-id}"
     class="eael-testimonial-item clearfix
            {image-rounded-class}                       ← from eael_testimonial_image_rounded
            {skin-class}                                ← one of: default-style, classic-style, middle-style,
                                                              icon-img-left-content, icon-img-right-content,
                                                              content-top-icon-title-inline,
                                                              content-bottom-icon-title-inline, simple-layout
            [rating-one|rating-two|rating-three|rating-four|rating-five]"> ← added when rating enabled

  [?] <ul class="testimonial-star-rating">                ← rendered when rating_position == 'top' (and skin != simple-layout)
    <li><i class="fas fa-star" aria-hidden="true"></i></li>
    <li><i class="fas fa-star" aria-hidden="true"></i></li>
    <li><i class="fas fa-star" aria-hidden="true"></i></li>
    <li><i class="fas fa-star" aria-hidden="true"></i></li>
    <li><i class="fas fa-star" aria-hidden="true"></i></li>
  </ul>

  <!-- One of 8 skin branches: -->

  <!-- default-style: image, then content (desc + rating + name+company) -->
  [?] <div class="eael-testimonial-image"><figure><img src="…"></figure></div>
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">{description (parse_text_editor + wp_kses)}</div>
    [?] <ul class="testimonial-star-rating">…</ul>          ← rating_position == 'default'
    <p class="eael-testimonial-user">{name}</p>
    <p class="eael-testimonial-user-company">{company}</p>
  </div>

  <!-- classic-style: content first (desc + name+company + rating), THEN image -->
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">…</div>
    <div class="clearfix">
      <p class="eael-testimonial-user">…</p>
      <p class="eael-testimonial-user-company">…</p>
    </div>
    [?] <ul class="testimonial-star-rating">…</ul>
  </div>
  [?] <div class="eael-testimonial-image">…</div>

  <!-- middle-style: desc, then image, then name+company, then rating -->
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">…</div>
    [?] <div class="eael-testimonial-image">…</div>
    <div class="clearfix"><p>name</p><p>company</p></div>
    [?] <ul class="testimonial-star-rating">…</ul>
  </div>

  <!-- icon-img-left-content / icon-img-right-content: image + content (with .bio-text or .bio-text-right wrapping name+company) -->
  [?] <div class="eael-testimonial-image">…</div>
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">…</div>
    [?] <ul class="testimonial-star-rating">…</ul>
    <div class="bio-text clearfix">                          ← bio-text-right for right variant
      <p>name</p><p>company</p>
    </div>
  </div>

  <!-- content-top-icon-title-inline: inline-bio (image + name/company + rating), then content -->
  <div class="eael-testimonial-content eael-testimonial-inline-bio">
    [?] <div class="eael-testimonial-image">…</div>
    <div class="bio-text"><p>name</p><p>company</p></div>
    [?] <ul class="testimonial-star-rating">…</ul>
  </div>
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">…</div>
  </div>

  <!-- content-bottom-icon-title-inline: content, then inline-bio -->
  <div class="eael-testimonial-content">
    <div class="eael-testimonial-text">…</div>
  </div>
  <div class="eael-testimonial-content eael-testimonial-inline-bio">
    [?] <div class="eael-testimonial-image">…</div>
    <div class="bio-text"><p>name</p><p>company</p></div>
    [?] <ul class="testimonial-star-rating">…</ul>
  </div>

  <!-- simple-layout: image, then content (rating + h3 name + company + rating + text) -->
  [?] <div class="eael-testimonial-image">…</div>
  <div class="eael-testimonial-content">
    [?] <ul class="testimonial-star-rating">…</ul>           ← rating_position == 'top'
    <h3 class="eael-testimonial-user">{name}</h3>            ← h3 unique to simple-layout (others use <p>)
    <p class="eael-testimonial-user-company">{company}</p>
    [?] <ul class="testimonial-star-rating">…</ul>           ← rating_position == 'default'
    <div class="eael-testimonial-text">{description (wp_kses only — NO parse_text_editor)}</div>
  </div>

  [?] <span class="eael-testimonial-quote"></span>           ← when show_quote == 'yes' — CSS ::before glyph
</div>
```

Notes:

- `simple-layout` uses `<h3 class="eael-testimonial-user">` while all other skins use `<p class="eael-testimonial-user">`. Inconsistency — themes targeting `p.eael-testimonial-user` won't match in simple-layout.
- `simple-layout` description renders via `wp_kses` directly ([line 1110](../../includes/Elements/Testimonial.php#L1110)); the other 7 skins go through `$this->testimonial_desc()` which adds `parse_text_editor` wrapping. Means simple-layout strips wpautop `<p>` wrapping that the others preserve.
- `eael_testimonial_user_display_block` panel switch injects an inline `style="display: block; float: none;"` onto `<p class="eael-testimonial-user">` ([line 974](../../includes/Elements/Testimonial.php#L974)) — sole inline-style emission from PHP.
- `content-top-icon-title-inline` skin preview thumbnail is `content-bottom-icon-title-inline.png` and vice versa ([lines 122, 126](../../includes/Elements/Testimonial.php#L122)) — preview swap bug.
- The `<figure>` wrapper around `<img>` is added for semantic markup; no `<figcaption>` follows so the wrap is decorative.
- Star ratings rendered via `<i class="fas fa-star">` — assumes Font Awesome 5 active. No FA4-fallback markup despite the FA4 shim being declared as style dependency.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Testimonial.php#L84) — 6 sections.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_testimonial_style` | CHOOSE (image picker) | `default-style` | Content → Layout | 1 of 8 skin variants — drives the if-block dispatch in `render()` |
| `eael_testimonial_enable_avatar` | SWITCHER | `yes` | Content → Image | Conditional `.eael-testimonial-image` block |
| `image` | MEDIA | (placeholder image) | Content → Image | Avatar source |
| `image` GROUP_CONTROL_IMAGE_SIZE | — | `thumbnail` | Content → Image | Image size variant |
| `eael_testimonial_name` | TEXT | `John Doe` | Content → Content | Reviewer name |
| `eael_testimonial_company_title` | TEXT | `Codetic` | Content → Content | Company name |
| `eael_testimonial_description` | WYSIWYG | (default copy) | Content → Content | Quote text |
| `content_height` | RESPONSIVE SLIDER | — | Content → Content | Forces `.eael-testimonial-content` height (px/%/em) |
| `eael_testimonial_show_quote` | SWITCHER | `yes` | Content → Content | Renders `<span class="eael-testimonial-quote">` |
| `eael_testimonial_enable_rating` | SWITCHER | `yes` | Content → Content | Adds `rating-N` class to wrapper + renders the 5-star UL |
| `eael_testimonial_rating_number` | SELECT | `rating-five` | Content → Content | One of `rating-one` … `rating-five` — CSS-driven partial fill |
| `eael_testimonial_rating_position` | CHOOSE | `default` | Content → Content | `default` (inside content block) vs `top` (before all skin output) |
| `eael_control_get_pro` | CHOOSE | `1` | Content → Go Premium for More Features | Decorative — `eael_section_pro` upsell only (hidden when Pro active) |
| `eael_testimonial_is_gradient_background` | SWITCHER | empty | Style → Testimonial | Toggle plain color vs gradient |
| `eael_testimonial_background` | COLOR | empty | Style → Testimonial | Plain bg; hidden when gradient mode |
| `eael_testimonial_gradient_background` | GROUP_CONTROL_BACKGROUND | — | Style → Testimonial | Elementor Background group control |
| `eael_testimonial_image_rounded` | SELECT | (style classes) | Style | Image-rounded variant class appended to wrapper |
| `eael_testimonial_user_display_block` | SWITCHER | — | Style | Injects inline `display:block;float:none` on `.eael-testimonial-user` |
| Style → various (Image / Content / Name / Company / Rating / Quote) | — | — | Style tab | ~7 sub-sections with typography, color, padding, border, box-shadow |

## Conditional Dependencies

```text
image / image_size                  → visible when eael_testimonial_enable_avatar == 'yes'
eael_testimonial_rating_number      → visible when eael_testimonial_enable_rating == 'yes'
eael_testimonial_rating_position    → visible when eael_testimonial_enable_rating == 'yes'
eael_testimonial_background         → visible when eael_testimonial_is_gradient_background == ''
eael_testimonial_gradient_background → visible when eael_testimonial_is_gradient_background == 'yes'

eael_section_pro / eael_control_get_pro → visible when Pro plugin is NOT active
```

Skin-specific style sub-sections (e.g. "Bio Text" styles for icon-img-left-content) are NOT conditioned on the skin SELECT — all style controls show regardless of selected skin. Some apply only to certain skins by virtue of selector specificity; users see "dead" controls in the panel.

## Hooks & Filters

> N/A — the widget emits no widget-specific filter or action hooks and consumes only `eael/pro_enabled` for the `eael_section_pro` upsell gate. Extension is via CSS overrides only.

The widget has **zero `do_action` injection points**. Pro / third-party plugins cannot register a new skin, intercept the rating render, or inject content into the testimonial wrapper without subclassing or forking.

## JavaScript Lifecycle

> N/A — pure CSS widget, no JavaScript. The widget declares no JS dependency in `config.php`, registers no Elementor frontend `addAction`. All interactivity (star fill, hover state, quote ornament) is CSS-only.

The `get_script_depends() = ['font-awesome-4-shim']` declaration is stale — the widget has no widget-specific JS that needs the shim. Adds the FA4 shim handle to the script queue unnecessarily.

## Common Issues

### Preview thumbnail doesn't match what the skin actually renders

- **Likely cause:** [Lines 122 and 126](../../includes/Elements/Testimonial.php#L122) swap the preview image filenames: `content-top-icon-title-inline` uses `content-bottom-…png` as its preview, and vice versa.
- **Diagnose:** Pick "Content Top | Icon Title Inline" in the panel — preview shows bottom layout. Save and check frontend — actual render is correct.
- **Fix:** Patch the two `image` filenames to match their option keys. Render code is correct; only metadata is swapped.

### Rating filter ignores fractional values

- **Likely cause:** `eael_testimonial_rating_number` is a SELECT with whole-number options only (`rating-one` … `rating-five`). No "4.5 stars" option. CSS fills are full stars only.
- **Diagnose:** Set rating to "4" — see 4 filled + 1 outline.
- **Fix:** Use a CSS override targeting `.testimonial-star-rating li:nth-child(5)` to show a half-filled star, or fork the widget to expose a half-step SELECT.

### `simple-layout` skin strips paragraph spacing from WYSIWYG content

- **Likely cause:** [Line 1110](../../includes/Elements/Testimonial.php#L1110) renders description via `wp_kses(...)` directly. The other 7 skins use `$this->testimonial_desc()` which calls `parse_text_editor` first (adds wpautop).
- **Diagnose:** Put a multi-paragraph description in the WYSIWYG, save with `simple-layout` — paragraphs collapse to single line.
- **Fix:** Patch line 1110 to use `$this->testimonial_desc()`. Or use HTML `<p>` tags directly in the WYSIWYG source.

### Need multiple testimonials in one widget instance

- **Likely cause:** This widget is single-instance only (no Repeater).
- **Diagnose:** No Add Item button anywhere in the panel.
- **Fix:** Use the **Testimonial Slider** widget (Pro-only, `eael-testimonial-slider`) for multiple testimonials in one widget. Or place multiple Testimonial widgets in a column/inner section.

## Known Limitations

- **Preview thumbnails swapped for `content-top-` and `content-bottom-icon-title-inline`** ([lines 122, 126](../../includes/Elements/Testimonial.php#L122)) — image filenames don't match option keys.
- **`simple-layout` skin description bypasses `parse_text_editor`** ([line 1110](../../includes/Elements/Testimonial.php#L1110)) — multi-paragraph content collapses.
- **`simple-layout` uses `<h3>` for reviewer name** ([line 1102](../../includes/Elements/Testimonial.php#L1102)) while other 7 skins use `<p>`. Themes can't reliably target both with one selector.
- **`render_testimonial_image()` uses `get_settings()` not `get_settings_for_display()`** ([line 903](../../includes/Elements/Testimonial.php#L903)) — dynamic tags don't resolve for the image field.
- **Star rating is whole-number only** — no half-star or fractional fill support.
- **No `do_action` extension points** — adding a 9th skin requires patching the widget class.
- **Stale `get_script_depends() = ['font-awesome-4-shim']`** ([line 76](../../includes/Elements/Testimonial.php#L76)) — widget has no JS but declares this dependency, adding an unneeded handle to the script queue.
- **`simple-layout` reuses `icon-img-left-content.png` as its preview thumbnail** ([line 130](../../includes/Elements/Testimonial.php#L130)) — preview doesn't represent the actual simple-layout markup.
- **Skin-specific style sub-sections aren't conditioned on the active skin** — users see panels for "Bio Text" styles even when on `default-style` where that selector doesn't exist.
- **No widget-specific Pro extension hooks** — Pro can only gate the upsell panel; rendering is identical between Lite and Pro.
- **Slug `testimonials` (plural) vs widget id `eael-testimonial` (singular)** — cross-link inconsistency in [`config.php`](../../config.php#L168) entry vs `get_name()` return. Confusing for `Asset_Builder` lookup.
- **`is_dynamic_content() = false` caches the widget render** — testimonials with dynamic name/company from a CRM source won't refresh until the cache invalidates. Set to true only by patching the widget.
- **`content-height` slider applies `height`, not `min-height`** ([line 243](../../includes/Elements/Testimonial.php#L243)) — long content clips when constrained.
