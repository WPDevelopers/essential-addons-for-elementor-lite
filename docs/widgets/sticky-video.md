# Sticky Video Widget

> Plyr-driven video player (YouTube, Vimeo, self-hosted) that converts to a sticky floating player in one of four screen corners when the user scrolls past the widget. Includes click-to-play image overlay, autoplay-with-mute support, and a close button on the sticky variant.

**Class file:** [`includes/Elements/Sticky_Video.php`](../../includes/Elements/Sticky_Video.php)
**Slug:** `sticky-video` (widget id `eael-sticky-video`)
**Public docs:** <https://essential-addons.com/elementor/docs/sticky-video/>
**Pro-shared:** ❌ — Lite-only widget. No `eael_section_pro` upsell panel, no `pro_enabled` filter check, no `do_action` / `apply_filters` calls. Pro neither subclasses nor references this widget.

---

## Overview

Sticky Video renders a Plyr-driven video player that converts to a floating sticky player in one of four screen corners when the user scrolls past the widget's normal position. Supports YouTube, Vimeo, and self-hosted sources via Plyr's standard provider system. Three modes: just-sticky (auto-stick on scroll once played), sticky-with-autoplay (autoplays on load, muted, then sticks), and overlay click-to-play (custom image overlay with play icon, click reveals player). Pure Plyr handles the video; widget JS only manages scroll-based sticky positioning, the overlay click-to-play interaction, and editor-side width/height aspect-ratio sync.

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Sticky behaviour with four corner positions | ✅ | ✅ |
| YouTube / Vimeo / self-hosted sources | ✅ | ✅ |
| Image overlay with click-to-play | ✅ | ✅ |
| Autoplay (with required mute) | ✅ | ✅ |
| Custom Plyr interface styling | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Sticky_Video.php`](../../includes/Elements/Sticky_Video.php) | PHP widget class — controls, render, three player loaders, URL ID extraction |
| [`src/css/view/sticky-video.scss`](../../src/css/view/sticky-video.scss) | Source styles — overlay, sticky corner positioning, close button, in/out transitions |
| [`src/js/view/sticky-video.js`](../../src/js/view/sticky-video.js) | Frontend logic — Plyr init, scroll-to-sticky, editor aspect-ratio sync |
| [`config.php`](../../config.php#L971) entry `'sticky-video'` | `Asset_Builder` dependency declaration (CSS + JS + Plyr lib) |
| `assets/front-end/css/lib-view/plyr/plyr.min.css` | Vendor — Plyr player styling |
| `assets/front-end/js/lib-view/plyr/plyr.min.js` | Vendor — Plyr v3 (video player library) |
| `assets/front-end/css/view/sticky-video.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/sticky-video.min.js` | Built output (do not edit) |

## Architecture

- **Plyr drives the player; widget JS only handles the sticky behaviour** — `new Plyr("#eaelsv-player-<id>")` constructs the player from `data-plyr-provider` / `data-plyr-embed-id` attributes (YouTube, Vimeo) or `<video>` element (self-hosted). Widget JS adds scroll-based sticky positioning on top.
- **Three player-loader methods in PHP** — `eaelsv_load_player_youtube()`, `_vimeo()`, `_self_hosted()` ([lines 825-900](../../includes/Elements/Sticky_Video.php#L825)). Each emits the Plyr DOM that the JS picks up. Plyr handles its own iframe/HTML5 video lifecycle.
- **URL ID extraction via `explode()`** — `eaelsv_get_url_id()` ([line 902](../../includes/Elements/Sticky_Video.php#L902)) parses YouTube via `wp_parse_url(..., PHP_URL_QUERY)` + `explode('=')`; Vimeo via `explode('/')`. Brittle on non-standard URLs (YouTube Shorts, `youtu.be/<id>?si=...`, Vimeo `/channels/...`). Documented in Known Limitations.
- **Editor aspect-ratio sync** — JS adds `change:eaelsv_sticky_width` and `change:eaelsv_sticky_height` listeners ([lines 17-48 of sticky-video.js](../../src/js/view/sticky-video.js#L17)) that keep the two values in 1.78 ratio (16:9). Debounced 250 ms. Editor-only — fires inside `panel/open_editor/widget/eael-sticky-video` Elementor hook.
- **Global state for scroll handling** — `eaelsvPosition`, `eaelsvWidth`, `eaelsvHeight`, `eaelsvDomHeight`, `videoIsActive`, `eaelMakeItSticky`, `scrollHeight`, `players[]` are all window-scoped globals ([lines 1-8 of sticky-video.js](../../src/js/view/sticky-video.js#L1)). Multiple sticky videos on a page race on these globals — last-played widget wins. Documented as a known limitation.
- **No `elementStatusCheck` guard** — JS registers via `elementorFrontend.hooks.addAction(...)` without the standard EA guard. Re-fired `frontend/init` (popups, SPA nav) can double-register and double-init Plyr on the same DOM, producing two player instances.

## Render Output

```html
<div class="eael-sticky-video-wrapper eaelsv-overlay-visibility-{yes|no}">
  [?] <div class="eaelsv-overlay [eaelsv-overlay-ignore]"
           style="background-image: url('overlay-image.jpg');">
        <div class="eaelsv-overlay-icon">
          <i class="eicon-play"></i>     <!-- or custom icon, or uploaded image -->
        </div>
      </div>

  <div class="eael-sticky-video-player2"
       data-sticky="yes"
       data-position="bottom-right"
       data-sheight="169"
       data-swidth="300"
       data-scroll_height="50"
       data-autoplay="no"
       data-overlay="yes">

    <!-- One of three player DOM shapes: -->

    <!-- YouTube -->
    <div id="eaelsv-player-<widget-id>"
         data-plyr-provider="youtube"
         data-plyr-embed-id="uuyXfUDqRZM"
         data-plyr-config='{"storage":...,"autoplay":0,"muted":0,"loop":{"active":false}}'></div>

    <!-- Vimeo -->
    <div id="eaelsv-player-<widget-id>"
         data-plyr-provider="vimeo"
         data-plyr-embed-id="235215203"
         data-plyr-config='{...}'></div>

    <!-- Self-hosted -->
    <video id="eaelsv-player-<widget-id>" class="eaelsv-player"
           playsinline controls
           data-plyr-config='{"autoplay":0,"muted":0,"loop":{"active":false}}'>
      <source src="<url>#t=<start>,<end>" type="video/mp4" />
    </video>

    <span class="eaelsv-sticky-player-close">
      <i class="fas fa-times-circle"></i>
    </span>
  </div>
</div>
```

Notes:

- `.eael-sticky-video-wrapper` is the outer container — JS reads its `style` `height` to maintain layout when the inner player goes sticky.
- `.eael-sticky-video-player2` carries all the runtime config in `data-*` attributes; JS reads sticky settings on first init.
- Plyr replaces the `<div data-plyr-provider="…">` with its own iframe-based player after `new Plyr(...)` runs. The placeholder `id` survives so DOM queries continue to find it.
- When scrolled past `eaelsvDomHeight`, JS adds `.out` class to `.eael-sticky-video-player2` and writes inline `top/left/right/bottom: 40px; width: …px; height: …px;` styles to absolute-position the player.
- Close button (`.eaelsv-sticky-player-close`) is hidden by default; only appears when the player has `.out` (sticky) state.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Sticky_Video.php#L91) is the truth — this table orients.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eaelsv_is_sticky` | SWITCHER | `yes` | Content → Sticky Options | `data-sticky`; JS sticky branch |
| `eaelsv_sticky_position` | SELECT | `bottom-right` | Content → Sticky Options | `data-position`; JS corner placement |
| `eael_video_source` | SELECT | `youtube` | Content → Video | Branches `render()` between three player loaders |
| `eaelsv_link_youtube` | TEXT (dynamic) | sample URL | Content → Video | YouTube URL — `eaelsv_get_url_id()` parses via `wp_parse_url` + `explode('=')` |
| `eaelsv_link_vimeo` | TEXT (dynamic) | sample URL | Content → Video | Vimeo URL — parsed via `explode('/')` |
| `eaelsv_link_external` | SWITCHER | empty | Content → Video | Toggle between MEDIA upload and external URL (self-hosted only) |
| `eaelsv_hosted_url` | MEDIA | empty | Content → Video | Self-hosted video media library pick |
| `eaelsv_external_url` | TEXT (dynamic) | empty | Content → Video | Self-hosted external URL |
| `eaelsv_start_time` / `eaelsv_end_time` | NUMBER (seconds) | empty | Content → Video | Self-hosted only — appended to `<source src>` as `#t=start,end` |
| `eaelsv_autopaly` | SWITCHER | `no` | Content → Video | Autoplay (typo: `autopaly` not `autoplay`); requires mute |
| `eaelsv_mute` | SWITCHER | `no` | Content → Video | Mute (auto-forced when autoplay is on); hidden when autoplay is on |
| `eaelsv_loop` | SWITCHER | `no` | Content → Video | Plyr `loop.active` |
| `eaelsv_sh_show_bar` | SWITCHER | `yes` | Content → Video | Plyr controls bar visibility |
| `eaelsv_overlay_options` | SWITCHER | `no` | Content → Image Overlay | Renders the click-to-play overlay |
| `eaelsv_overlay_image` | MEDIA | placeholder | Content → Image Overlay | `background-image` on `.eaelsv-overlay` |
| `eaelsv_overlay_play_icon` | SWITCHER | empty | Content → Image Overlay | Renders the play icon over the overlay |
| `eaelsv_icon_new` | ICONS | `eicon-play` | Content → Image Overlay | Play icon glyph |
| `eaelsv_sticky_width` / `eaelsv_sticky_height` | NUMBER | `300` / `169` | Content → Sticky Options | `data-swidth` / `data-sheight`; locked to 1.78 ratio via editor JS |
| `eaelsv_scroll_height_display_sticky` | SLIDER (%) | empty | Content → Sticky Options | `data-scroll_height`; how far the user must scroll before sticking |
| Style → Sticky Video Interface | various | — | Style tab | Plyr control colours, hover states, padding (visible when `is_sticky === 'yes'`) |
| Style → Player / Interface / Bar | various | — | Style tab | Plyr root, control bar, buttons, progress styling |

## Conditional Dependencies

```text
eaelsv_sticky_position           → visible when eaelsv_is_sticky == 'yes'

# Video source branching
eaelsv_link_youtube              → visible when eael_video_source == 'youtube'
eaelsv_link_vimeo                → visible when eael_video_source == 'vimeo'
eaelsv_link_external             → visible when eael_video_source == 'self_hosted'
eaelsv_hosted_url                → visible when eael_video_source == 'self_hosted'
                                   AND eaelsv_link_external != 'yes'
eaelsv_external_url              → visible when eael_video_source == 'self_hosted'
                                   AND eaelsv_link_external == 'yes'
eaelsv_start_time / eaelsv_end_time
                                 → visible when eael_video_source == 'self_hosted'

# Autoplay / mute
eaelsv_autopaly_description      → visible when eaelsv_autopaly == 'yes'
eaelsv_mute                      → visible when eaelsv_autopaly != 'yes'
                                   (autoplay forces mute internally)

# Overlay
eaelsv_overlay_image / _overlay_play_icon / _icon_new
                                 → visible when eaelsv_overlay_options == 'yes'

# Style sections
Style → Sticky Video Interface   → visible when eaelsv_is_sticky == 'yes'
```

No `eael_section_pro` upsell panel.

## Hooks & Filters

N/A — the widget emits no widget-specific filter or action hooks and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides only.

Plyr's own API (`player.play()`, `player.on('play', …)`, etc.) is available globally via the `Plyr` constructor; third parties can hook the player instances stored in `window.players[]` array.

## JavaScript Lifecycle

- **Trigger:** `elementorFrontend.hooks.addAction('frontend/element_ready/eael-sticky-video.default', handler)` plus a global `jQuery(window).scroll(...)` handler (registered once for the whole page)
- **Editor trigger:** `elementor.hooks.addAction('panel/open_editor/widget/eael-sticky-video', ...)` adds debounced width ↔ height aspect-ratio sync (1.78 = 16:9)
- **Guard:** **none** — no `elementStatusCheck`. Re-fired `frontend/init` can double-register the handler and double-init Plyr.
- **Reads on init:** `.eael-sticky-video-player2`'s `data-sticky / data-autoplay / data-position / data-sheight / data-swidth / data-overlay / data-scroll_height`.
- **Plyr init:** `var playerAbc = new Plyr("#eaelsv-player-" + $scope.data("id"));` — pushed onto the global `players[]` array.
- **Three init branches:**
  - **Sticky-only** (`sticky === 'yes'`, `overlay === 'no'`): assigns `id="videobox"` to the player, sets `videoIsActive = 'on'`, binds `PlayerPlay(player, element)`.
  - **Sticky + autoplay with overlay** (`overlay === 'yes'`, `autoplay === 'yes'`): hides overlay, calls `playerAbc.play()`, then same as sticky-only.
  - **Overlay click-to-play** (`overlay === 'yes'`, `autoplay !== 'yes'`): binds click on overlay element; on click hides overlay and plays.
- **Scroll handler:** global `jQuery(window).scroll(...)` ([line 154 of sticky-video.js](../../src/js/view/sticky-video.js#L154)) — when `scrollTop >= eaelsvDomHeight` AND `videoIsActive == 'on'`, swaps `.in` → `.out` class on `#videobox` and absolute-positions to the chosen corner via `PositionStickyPlayer()`.
- **Pause-others on play:** `PlayerPlay()` iterates `players[]` and pauses every non-active player. Single-active-player guarantee across multiple Sticky Video widgets on the same page.
- **Close button:** removes `.out`, adds `.in`, clears inline style, sets `videoIsActive = 'off'`.
- **Runtime state:** global window vars (`eaelsvPosition`, `eaelsvWidth`, etc.) are reassigned every time a player starts playing — last-played widget wins for sticky positioning if multiple widgets are on the page.

## Common Issues

### Sticky behaviour doesn't trigger when scrolling past the video

- **Likely cause:** `videoIsActive` is `'off'` — the player hasn't been played yet. Sticky only kicks in for the currently-playing video
- **Diagnose:** in console run `videoIsActive` — should be `'on'` after clicking play
- **Fix:** play the video before scrolling; or set `eaelsv_autopaly` (and accept the required mute)

### YouTube URL with `?si=...` tracking parameter doesn't load

- **Likely cause:** `eaelsv_get_url_id()` parses YouTube URLs via `wp_parse_url(..., PHP_URL_QUERY)` then `explode('=', $query)`. URLs like `https://youtu.be/uuyXfUDqRZM?si=abc123` produce `['si', 'abc123']` instead of `['v', 'uuyXfUDqRZM']` — the extracted "id" is the tracking token
- **Diagnose:** inspect the rendered `data-plyr-embed-id` value — does it match the video ID?
- **Fix:** use the `watch?v=<id>` form of YouTube URLs, not the short `youtu.be/<id>?si=...` form; or remove the `?si=...` parameter manually

### YouTube Shorts URL fails

- **Likely cause:** YouTube Shorts URLs are `/shorts/<id>` — `eaelsv_get_url_id()` splits on `/` and grabs `$short_link[3]`, which for `https://www.youtube.com/shorts/<id>` is `<id>`. Should work; but the Plyr embed iframe may render the Shorts video in portrait mode at desktop player dimensions
- **Diagnose:** does the video play but look squashed?
- **Fix:** use a regular YouTube URL; Shorts URLs are not the documented use case

### Multiple Sticky Video widgets on the same page conflict

- **Likely cause:** the JS uses window-scoped globals (`eaelsvPosition`, `eaelsvWidth`, etc.) that get overwritten by whichever player was last interacted with — `PositionStickyPlayer()` then uses the last widget's settings for the active video's sticky corner / dimensions
- **Diagnose:** play one video, scroll to make it sticky; play another video — does it stick to the corner of the first one?
- **Fix:** known limitation; only one Sticky Video per page recommended

### Autoplay doesn't start

- **Likely cause:** modern browsers block autoplay on videos that aren't muted; the widget's `eaelsv_autopaly_description` alert in the panel reminds users that mute is required — but the panel forces mute via the visibility condition on `eaelsv_mute`
- **Diagnose:** check the rendered `data-plyr-config` — is `"muted":1` present?
- **Fix:** ensure autoplay implies mute (default behaviour); if still blocked, the browser may have user-gesture-required policy — user must interact first

### Overlay image doesn't appear

- **Likely cause:** `eaelsv_overlay_options` is on but `eaelsv_overlay_image.url` is empty — the wrapper gets `.eaelsv-overlay-ignore` class instead of `.eaelsv-overlay` ([line 787](../../includes/Elements/Sticky_Video.php#L787)) and the play icon is also omitted
- **Diagnose:** inspect the overlay div class — `eaelsv-overlay-ignore` means no image picked
- **Fix:** upload an overlay image; or turn off the overlay option

### Close button doesn't appear on the sticky player

- **Likely cause:** close button is hidden by default (`$(".eaelsv-sticky-player-close", $scope).hide()` at JS init); only shown when player has `.out` class — and only on first scroll-past
- **Diagnose:** does the player have `.out` class after scrolling?
- **Fix:** scroll past the video to trigger the sticky state

### After Elementor panel re-open, width / height aspect-ratio drifts

- **Likely cause:** the editor change handlers use `setTimeout(..., 250)` debounce; rapid typing can produce overlapping timers
- **Diagnose:** type a width slowly; the height should update after 250ms
- **Fix:** known minor UX issue; works correctly with normal typing speed

## Known Limitations

- **Window-scoped global state** — `eaelsvPosition`, `eaelsvWidth`, `eaelsvHeight`, `eaelsvDomHeight`, `videoIsActive`, `players[]` etc. are all `var`-declared at module top. Multiple Sticky Video widgets on the same page race on these. Documented as "only one per page" use case.
- **No `elementStatusCheck` guard** — re-fired `elementor/frontend/init` can double-register the handler and double-init Plyr on the same widget, producing two iframe players stacked. Adding `eael.elementStatusCheck('eaelStickyVideo')` would fix this.
- **YouTube URL parsing breaks on tracking params** — `eaelsv_get_url_id()` uses `explode('=')` on the query string; `?si=abc123` becomes the extracted ID instead of `?v=<id>&si=<tracker>` being parsed correctly.
- **Vimeo URL parsing breaks on channel URLs** — `explode('/')` and grabbing `$link[3]` works for `https://vimeo.com/<id>` but fails for `https://vimeo.com/channels/staffpicks/<id>` (extracts `channels` instead of `<id>`).
- **Control name typo `eaelsv_autopaly`** (sic, "autopaly" not "autoplay") — visible in widget data; renaming would break saved widgets.
- **Self-hosted `#t=start,end` time fragment is appended to URL even when start/end are empty** — produces `video.mp4#t=,` which most browsers ignore but is non-standard.
- **Dailymotion control present but the source option doesn't include Dailymotion** — `eaelsv_link_dailymotion` control exists ([line 196](../../includes/Elements/Sticky_Video.php#L196)) but `eael_video_source` only offers `youtube`, `vimeo`, `self_hosted`. Dead code; control never visible.
- **`PositionStickyPlayer()` overwrites every `.eael-sticky-video-player2.out`** — uses a class selector (not scoped to `$scope`), so positioning one widget repositions all sticky widgets on the page. Compounds the multi-widget conflict.
- **Plyr loads unconditionally** — ~30 KB JS + CSS even on pages with self-hosted video where native `<video>` would suffice.
- **Scroll handler runs on every scroll event** — no debounce or `requestAnimationFrame`; fast scrolls produce 60+ handler invocations per second.
- **`videoIsActive` is a single boolean** — represents "is some video active" not "is THIS video active". When two videos exist and one plays then pauses while the other is still playing, the flag may be wrong.
