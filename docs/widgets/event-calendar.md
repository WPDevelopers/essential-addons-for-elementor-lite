# Event Calendar Widget

> Multi-source event calendar — Manual repeater, Google Calendar API, The Events Calendar (Tribe plugin), and EventOn (Pro). Renders as **Calendar** (FullCalendar v5 grid) OR **Table** (fancyTable sortable). DOMPurify-sanitised modal popup for event details. Largest source-integration widget in Lite.

**Class file:** [`includes/Elements/Event_Calendar.php`](../../includes/Elements/Event_Calendar.php)
**Slug:** `event-calendar` (widget id `eael-event-calendar`)
**Public docs:** <https://essential-addons.com/elementor/docs/event-calendar/>
**Pro-shared:** ✅ Yes — Pro adds **EventOn** integration (4th source), gated dually behind `apply_filters('eael/is_plugin_active', 'eventON/eventon.php')` AND `apply_filters('eael/pro_enabled', false)` at [line 758](../../includes/Elements/Event_Calendar.php#L758). Pro listens on `eael/event-calendar/integration` filter to inject EventOn events. Three additional `do_action` hooks (`activation-notice`, `source/control`, `settings` filter) host Pro extensions.

---

## Overview

Four event-source paths converge on a unified event-array shape (`id / title / start / end / color / textColor / borderColor / url / allDay / external / nofollow / description / location / category / custom_attributes / is_redirect`). PHP fetches/builds the array, json-encodes it into a `data-events` attribute, and FullCalendar v5 reads it client-side. Modal popup constructed once in PHP (`#eaelecModal`), populated per-event-click by JS. Both calendar and table layouts share the event-array pipeline; only the rendering changes. **DOMPurify is loaded as a global library specifically for sanitising user-supplied event titles, descriptions, and locations at render time** — the only Lite widget to require DOMPurify alongside its primary vendor. PHP-side `xssAttributes()` blocklists 73 `on*` handler attribute names but **the list is unused inside this class** (provided as a reference helper for shared code).

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| Manual events (Repeater) | ✅ | ✅ |
| Google Calendar API (v3 REST + transient cache) | ✅ | ✅ |
| The Events Calendar (Tribe plugin via `tribe_get_events`) | ✅ — needs plugin active | ✅ |
| EventOn integration | ❌ — control option present in SELECT but `eael_event_calendar_pro_enable_warning` shows when selected ([line 144](../../includes/Elements/Event_Calendar.php#L144)); render's `apply_filters('eael/event-calendar/integration', [], $settings)` returns `[]` when Pro inactive | ✅ — Pro plugin hooks `eael/event-calendar/integration` to return EventOn events |
| Calendar (FullCalendar grid) layout | ✅ | ✅ |
| Table (fancyTable sortable + paginated) layout | ✅ | ✅ |
| Search (calendar layout suggestions + table layout global search) | ✅ | ✅ |
| Random color rotation | ✅ via `get_random_colors()` helper | ✅ |
| Custom attribute injection on redirect anchors | ✅ via `Utils::parse_custom_attributes()` | ✅ |
| `eael_section_pro` upsell panel | ❌ — none registered; Pro discovery via the `eael_event_calendar_pro_enable_warning` RAW_HTML notice only | — |

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Event_Calendar.php`](../../includes/Elements/Event_Calendar.php) | PHP widget class (~4,519 lines) — controls, render, 3 fetcher methods (`get_manual_calendar_events`, `get_google_calendar_events`, `get_the_events_calendar_events`), `eaelec_display_table`, `eaelec_load_event_details` modal markup, `xssAttributes` reference list, `fetch_color_or_global_color`, `is_old_event`, `get_random_colors` |
| [`src/css/view/event-calendar.scss`](../../src/css/view/event-calendar.scss) | Source styles (~821 lines) — FullCalendar overrides, modal popup styling, table layout, search suggestions dropdown, thumbnail position variants (`eaelec-img-header` / `eaelec-bg-img` / `eaelec-img-inside`) |
| [`src/js/view/event-calendar.js`](../../src/js/view/event-calendar.js) | Frontend logic (~500 lines) — FullCalendar bootstrap, modal populate, search filter, fancyTable bootstrap, IntersectionObserver resize fix, `eventCalendar.reinit` cross-widget hook |
| [`config.php`](../../config.php#L1000) entry `'event-calendar'` | `Asset_Builder` deps: lib calendar-main.min.css + self CSS + dom-purify + calendar-main.min.js + locales-all.min.js + fancy-table.min.js + self JS |
| `assets/front-end/css/lib-view/full-calendar/calendar-main.min.css` | Vendor — FullCalendar v5 core styles |
| `assets/front-end/js/lib-view/full-calendar/calendar-main.min.js` | Vendor — FullCalendar v5 core (`window.FullCalendar` exposed) |
| `assets/front-end/js/lib-view/full-calendar/locales-all.min.js` | Vendor — all FullCalendar locale bundles |
| `assets/front-end/js/lib-view/dom-purify/purify.min.js` | Vendor — DOMPurify (XSS scrubber); used for title/description/location/category before injection |
| `assets/front-end/js/lib-view/fancy-table/fancy-table.min.js` | Vendor — jQuery fancyTable for Table layout (sort, paginate, search) |
| `get_script_depends() = ['moment']` | Declared dependency on `moment.js` (WP-registered handle; not bundled by EA) |

## Architecture

- **Source-branch in `render()` builds a single event-array shape** — all four sources (manual / google / the_events_calendar / eventon) feed [`render()`'s if-else chain at lines 3809-3817](../../includes/Elements/Event_Calendar.php#L3809) and each returns the same `array` shape. After branch, `apply_filters('eael/event-calendar/events', $data, $settings)` lets Pro / third-parties mutate the unified array. EventOn-only path uses `apply_filters('eael/event-calendar/integration', [], $settings)` — Lite returns `[]` (filter unhooked) so EventOn source silently produces an empty calendar without Pro.
- **Events encoded as HTML attribute, not inline JSON `<script>`** — `data-events="..."` uses `esc_attr(htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'))` at [render line 3861](../../includes/Elements/Event_Calendar.php#L3861). Two-pass encoding because Elementor's `esc_attr` is HTML-attribute safe but doesn't double-quote properly when JSON contains `&quot;` sequences — `htmlspecialchars(..., ENT_QUOTES)` first then `esc_attr` over the result yields a valid attribute that JS recovers cleanly via `$.data()`.
- **Google API path caches via transient keyed by API key + cache_time** — [line 4264](../../includes/Elements/Event_Calendar.php#L4264) builds `'eael_google_calendar_' . md5(implode('', $transient_args))`. **The transient cache TTL is panel-driven** (`eael_event_calendar_data_cache_limit` in minutes), unique among EA widgets — most hardcode cache TTLs. Cache key uses **hour-rounded** `gmdate('Y-m-d H', ...)` instead of full timestamp so consecutive renders within the same hour hit the cache even if `current_time()` advances seconds.
- **The Events Calendar path queries Tribe via `tribe_get_events()`** with `posts_per_page = $settings['eael_the_events_calendar_max_result']`. Date-range mode forwards `start_date` / `end_date` only when **`eael_event_display_layout === 'table'`** ([line 4375](../../includes/Elements/Event_Calendar.php#L4375)) — calendar layout silently ignores the date-range setting and returns all events up to max. Hidden constraint, not documented in panel.
- **Modal popup HTML is emitted ONCE per widget in PHP, populated by JS** — `eaelec_load_event_details()` builds `#eaelecModal` markup with empty placeholder spans. JS click handler fills `.eael-ec-modal-title`, `.eaelec-event-date-start/-end`, `.eaelec-modal-body` etc. on event click. **The modal id `eaelecModal` is hardcoded** — multiple Event_Calendar widgets on one page produce duplicate ids; CSS `#eaelecModal` selectors match the first one only, popups from second widget hijack the first's modal node.
- **DOMPurify wrap on every dynamic HTML write** — title, description, location, category, suggestion items, popup details button text all pass through `DOMPurify.sanitize(...)` before `.html(...)` calls ([js lines 251-252, 294, 349, 402-405](../../src/js/view/event-calendar.js#L251)). Defence-in-depth — events come from authenticated builders (manual) or trusted APIs (google/tribe), but description WYSIWYG output is user HTML that could contain `<script>` if migrated from a hostile source.
- **`xssAttributes()` PHP helper at [line 4056](../../includes/Elements/Event_Calendar.php#L4056) returns a 73-name `on*` event-handler blocklist** — **but it is NOT consumed inside this class file** (grep shows no `$this->xssAttributes()` call). The list exists as a shared reference (likely consumed by extensions or copy-pasted into a future sanitiser). JS does its own anchor `on*` scrub at [lines 115-126](../../src/js/view/event-calendar.js#L115) and [329-337](../../src/js/view/event-calendar.js#L329) by iterating `element[0].attributes` and `.removeAttr()`-ing any starting with `on`, plus rewriting `javascript:` href to `#`.
- **Two distinct date display strategies** — Calendar layout uses moment.js + locale-aware `format(popupDateFormate)` for modal popup; Table layout uses `wp_date()` with `DateTimeZone` per-event when `start_timezone`/`end_timezone` present (Google API only; manual + Tribe omit timezone), else `date_i18n()`. Table-layout date display also concatenates date + time format with a back-slash-escaped separator string at [PHP lines 3916-3922](../../includes/Elements/Event_Calendar.php#L3916) for moment-compatible custom formats.
- **`is_old_event()` gating runs at fetcher level, not in render** — each source method calls it per-event with current-date or panel-set `eael_event_calendar_default_date` to exclude past events. Three modes via `eael_old_events_hide`: empty/false (show all), `'yes'` (hide pre-today), `'start'` (hide pre-default-date). Calendar layout itself can still navigate users into the past via FullCalendar prev/next — back-fill is empty.
- **`get_random_colors()` rotates a hardcoded palette** when `eael_event_random_bg_color = 'yes'` — overrides per-event panel colors. Manual repeater colors don't participate in rotation (each manual event has its own color picker); only Google + Tribe sources rotate, because their fetchers feed a shared `$random_color_index` counter that resets per render.

## Render Output

```html
<div class="eael-event-calendar-wrapper layout-[calendar|table]">

  [?] <!-- Search box (calendar layout, when eael_event_calendar_show_search = yes) -->
  <div class="eael-event-calendar-search-wrap eael-event-calendar-search">
    <input type="search"
           id="eael-event-calendar-search-input-{widget-id}"
           class="eael-event-calendar-search-input"
           placeholder="...">
    <div class="eael-event-calendar-search-suggestions"></div>
  </div>

  <!-- Calendar layout -->
  <div id="eael-event-calendar-{widget-id}"
       class="eael-event-calendar-cls"
       data-cal_id="{widget-id}"
       data-locale="en"                                   ← FullCalendar locale code
       data-translate="{json: today / tomorrow}"          ← htmlspecialchars+json_encode+esc_attr
       data-defaultview="dayGridMonth | timeGridWeek | timeGridDay | listMonth"
       data-defaultdate="YYYY-MM-DD"
       data-time_format="yes|no"                          ← yes = 24h
       data-event_limit="3"                               ← daily event cap before "+N more"
       data-popup_date_format="MMM Do YYYY"
       data-multidays_event_day_count="0|1"
       data-monthColumnHeaderFormat="ddd"                 ← moment format string
       data-weekColumnHeaderFormat="ddd D"
       data-hideDetailsLink="yes|"
       data-detailsButtonText="View Details"              ← wp_kses + DOMPurify
       data-location-display="yes|"
       data-events="{json: array of event objects}"       ← htmlspecialchars+json_encode+esc_attr
       data-thumbnail_position="header|background|body-bg|body-left|body-right|"
       data-first_day="0..6"></div>

  <!-- Table layout (alternative) -->
  <table class="eael-event-calendar-table [ea-ec-table-paginated] ea-ec-table-sortable"
         data-items-per-page="N">
    <thead><tr>
      [?] <th>Title</th>
      [?] <th>Description</th>
      [?] <th>Date</th>
    </tr></thead>
    <tbody>
      <tr [style="display: none;"] >
        [?] <td class="eael-ec-event-title" style="background:#…;color:#…;">
          [?] <a href="event-url" [target="_blank"]>Title</a>
        </td>
        [?] <td class="eael-ec-event-description">… <a class="eael-see-more" href="…">See More</a></td>
        [?] <td class="eael-ec-event-date">
          <span class="hide">{timestamp-for-sort}</span> {start} {sep} {end}
        </td>
      </tr>
      …
    </tbody>
  </table>
  [?] <div class="eael-event-calendar-pagination ea-ec-pagination-button"></div>

  <!-- Modal popup (emitted once, populated by JS on event click) -->
  <div id="eaelecModal" class="eaelec-modal eael-zoom-in">                  ← ⚠️ hardcoded ID; collides across multiple widgets per page
    <div class="eaelec-modal-content">
      <div class="eaelec-modal-header">
        <div class="eaelec-modal-close"><span><i class="fas fa-times"></i></span></div>
        <h2 class="eael-ec-modal-title"></h2>                               ← DOMPurify(event.title)
        <span class="eaelec-event-date-start eaelec-event-popup-date"></span>
        <span class="eaelec-event-date-end eaelec-event-popup-date"></span>
        <span class="eaelec-event-location eaelec-event-popup-location" style="display: none;"></span>
      </div>
      <div class="eaelec-modal-body"></div>                                 ← DOMPurify(event.description)
      <div class="eaelec-modal-footer"><a></a></div>                        ← anchor populated with event.url + scrubbed for on* + javascript:
    </div>
  </div>

</div>
```

Notes:

- `data-events` JSON includes per-event color, URL, custom attributes, location, category, is_redirect (manual-only), allDay, external, nofollow. Sensitive considering the entire dataset is exposed as a public DOM attribute — anyone viewing source sees all events including future-scheduled drafts (no pagination on raw data).
- The `#eaelecModal` ID **does not include the widget id** — placing two Event_Calendar widgets on the same page causes the first widget's modal to be reused by the second; the second widget's modal markup is appended but never selected. Documented in Known Limitations.
- Calendar layout's `data-translate` carries only `today` + `tomorrow` strings; everything else is FullCalendar's built-in locale data via `locales-all.min.js`.
- `data-detailsButtonText` is passed through `wp_kses(... Helper::eael_allowed_tags())` server-side; JS re-runs it through `DOMPurify.sanitize()` at [line 349](../../src/js/view/event-calendar.js#L349) when refreshing the modal footer link.
- Table layout includes a hidden `<span class="hide">{unix-timestamp}</span>` inside the date column for fancyTable's text-sort to produce a chronological order instead of locale-string lexicographic.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Event_Calendar.php#L84) — ~30 sections. Selected meaningful controls:

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `eael_event_calendar_type` | SELECT | `manual` | Content → Events | Source: `manual`, `google`, `the_events_calendar`, `eventon` (Pro-only) |
| `eael_event_display_layout` | SELECT | `calendar` | Content → Events | `calendar` (FullCalendar grid) vs `table` (fancyTable) |
| `eael_event_items` | REPEATER | (1 item) | Content → Events | Manual event list — see Per-item table below |
| `eael_event_google_api_key` | TEXT | empty | Content → Google Calendar | Google API v3 key |
| `eael_event_calendar_id` | TEXT | empty | Content → Google Calendar | Google calendar ID (e.g. `you@gmail.com` or shared cal id) |
| `eael_google_calendar_start_date` / `_end_date` | DATE_TIME | now / now+6mo | Content → Google Calendar | API query window |
| `eael_google_calendar_max_result` | NUMBER | 100 | Content → Google Calendar | Google API `maxResults` param |
| `eael_event_calendar_data_cache_limit` | NUMBER | (default in section) | Content → Google Calendar | Transient TTL in minutes |
| `eael_the_events_calendar_fetch` | SELECT | `all` | Content → The Event Calendar | `all` vs `date_range` (table-only effective) |
| `eael_the_events_calendar_category` | SELECT2 (multiple) | `[]` | Content → The Event Calendar | tax_query on `tribe_events_cat` taxonomy |
| `eael_event_calendar_language` | SELECT | (browser locale) | Content → Calendar | FullCalendar locale (~60 options) |
| `eael_event_calendar_default_view` | SELECT | `dayGridMonth` | Content → Calendar | Initial FullCalendar view: month / week / day / listMonth |
| `eael_event_default_date_type` / `eael_event_calendar_default_date` | SELECT / DATE_TIME | `current` / today | Content → Calendar | Custom landing date |
| `eael_event_calendar_first_day` | SELECT | `0` (Sunday) | Content → Calendar | Week-start day |
| `eael_event_time_format` | SELECT | `yes` (24h) | Content → Calendar | Hour-12 vs 24 |
| `eael_event_limit` | NUMBER | 2 | Content → Calendar | Per-day max event rows before "+N more" |
| `eael_event_multi_days_event_day_count` | SWITCHER | empty | Content → Calendar | Append "(Day N/M)" to multi-day event titles |
| `eael_event_calendar_show_search` | SWITCHER | empty | Content → Calendar | Calendar-layout search input |
| `eael_event_calendar_search_placeholder` | TEXT | "Search Events..." | Content → Calendar | Input placeholder |
| `eael_event_popup_date_format` | TEXT | `MMM Do YYYY` | Content → Calendar | Moment format string for popup dates |
| `eael_calendar_column_heading_month` / `_week` | TEXT | (moment formats) | Content → Calendar | Per-view column header date format |
| `eael_event_details_link_hide` | SWITCHER | empty | Content → Calendar | When `yes`, omits event URL from data (popup "View Details" hidden) |
| `eael_event_details_text` | TEXT | "View Details" | Content → Calendar | Popup footer link text |
| `eael_event_location_display` | SWITCHER | empty | Content → Calendar | Show event.location in popup |
| `eael_event_show_thumbnail` / `eael_event_thumbnail_position` | SWITCHER / SELECT | empty / — | Content → Calendar | Show event thumbnail + position: `header`, `background`, `body-bg`, `body-left`, `body-right` |
| `eael_old_events_hide` | SELECT | empty | Content → Calendar | Past-event filter: empty / `yes` (pre-today) / `start` (pre-default-date) |
| `eael_event_random_bg_color` | SWITCHER | empty | Style → Events | Rotate event colors from hardcoded palette (overrides per-event colors for google/tribe) |
| `eael_event_global_bg_color` / `_text_color` / `_popup_ribbon_color` | COLOR | (defaults) | Style → Events | Fallback colors for google/tribe sources (manual events have own per-item colors) |
| Table section → `eael_ec_show_title` / `_description` / `_date` | SWITCHER × 3 | various | Content → Table | Column visibility |
| `eael_ec_show_pagination` / `_item_per_page` | SWITCHER / NUMBER | — / 1 | Content → Table | fancyTable pagination |
| `eael_ec_show_search` | SWITCHER | — | Content → Table | Table-layout search input (separate from calendar search) |
| `eael_ec_date_format` / `eael_ec_enable_custom_date_format` / `eael_ec_custom_date_format` | TEXT / SWITCHER / TEXT | various | Content → Table | PHP `date()` format for table date column |
| `eael_ec_time_format` / `eael_ec_date_time_format` / `eael_ec_date_time_separator` / `eael_ec_date_to_date_separator` | TEXT | various | Content → Table | Date+time composition |
| `eael_table_ec_default_date_type` / `eael_table_event_calendar_default_date` | SELECT / DATE_TIME | — | Content → Table | Table-layout old-event filter origin |
| `eael_ec_event_details_link` / `_title_on_new_tab` / `_desc_see_more` / `_desc_see_more_link` | SWITCHER × n + TEXT | various | Content → Table | Per-cell link decoration |
| `eael_ec_description_limit` | NUMBER | — | Content → Table | `wp_trim_words` cap with "see more" appended |
| Style → various | — | — | Style tab | ~12 style sections covering header / event cell / popup / table rows / pagination / search suggestions |

### Per-item Repeater controls (`eael_event_items`)

| ID | Type | Default | Affects |
| --- | ---- | ------- | ------- |
| `eael_event_title` | TEXT | empty | Event display title (dynamic-tags + AI enabled) |
| `eael_event_link` | URL | empty | Event link — also drives custom_attributes; `is_external` / `nofollow` passed through to anchor |
| `eael_event_redirection` | SWITCHER | empty (popup mode) | When `yes`, click goes directly to URL instead of opening popup |
| `eael_event_all_day` | SWITCHER | empty | All-day mode: hides time picker, uses date-only `eael_event_start_date_allday` / `_end_date_allday` |
| `eael_event_start_date` / `_end_date` / `_start_date_allday` / `_end_date_allday` | DATE_TIME | — | Event boundaries; all-day uses separate pair with `enableTime: false` picker option |
| `eael_event_bg_color` / `_text_color` / `_border_color` | COLOR | `#5725ff` / `#ffffff` / `#E8E6ED` | Per-event color override |
| `eael_event_location` / `_category` | TEXT | empty | Searchable metadata (calendar-layout suggestion box filters by title+location+category) |
| `eael_event_description` | WYSIWYG | empty | Popup body — sanitised via DOMPurify before injection |

## Conditional Dependencies

```text
eael_event_google_calendar section          → visible when eael_event_calendar_type == 'google'
eael_event_the_events_calendar section      → visible when eael_event_calendar_type == 'the_events_calendar' AND The Events Calendar plugin active
eael_the_event_calendar_warning_text        → visible when eael_event_calendar_type == 'the_events_calendar' AND plugin NOT active
eael_event_calendar_pro_enable_warning      → visible when eael_event_calendar_type == 'eventon' AND Pro NOT active
eael_event_calendar_section (Calendar)      → visible when eael_event_display_layout == 'calendar'
table-layout sections                       → visible when eael_event_display_layout == 'table'
eael_event_calendar_search_placeholder      → visible when eael_event_calendar_show_search == 'yes'
eael_event_calendar_default_date            → visible when eael_event_default_date_type == 'custom'
eael_event_details_text                     → visible when eael_event_details_link_hide != 'yes'
eael_event_thumbnail_position               → visible when eael_event_show_thumbnail == 'yes'
eael_the_events_calendar_start_date / _end_date → visible when eael_the_events_calendar_fetch == 'date_range'
Repeater eael_event_start_date / _end_date  → visible when eael_event_all_day != 'yes'
Repeater eael_event_start_date_allday / _end_date_allday → visible when eael_event_all_day == 'yes'
Repeater eael_event_border_color            → visible when eael_event_redirection != 'yes'
Repeater Content tab                        → visible when eael_event_redirection != 'yes'
```

No `eael_section_pro` upsell registered for this widget — Pro discovery is via the inline `eael_event_calendar_pro_enable_warning` RAW_HTML notice only.

## Hooks & Filters

| Hook | Type | Signature | Purpose |
| ---- | ---- | --------- | ------- |
| `eael/event-calendar/source` | filter (consumed in `register_controls`) | `array $sources` | Add custom source types to the SELECT (Pro adds `eventon`) — handled by Bootstrap's `event_calendar_source` callback at [Bootstrap line 193](../../includes/Classes/Bootstrap.php#L193) |
| `eael/event-calendar/activation-notice` | action (emitted in `register_controls`) | `(Widget_Base $widget)` | Pro inserts plugin-activation notices for EventOn |
| `eael/event-calendar/source/control` | action (emitted in `register_controls`) | `(Widget_Base $widget)` | Pro inserts EventOn source-specific control section |
| `eael/event-calendar/settings` | filter (emitted in `render`) | `array $settings` | Mutate settings array before fetch (e.g. force a source, override max_results) |
| `eael/event-calendar/integration` | filter (emitted in `render`, only when source = eventon) | `(array $data, array $settings)` | EventOn-source data provider — Pro returns event array; Lite no-op returns `[]` |
| `eael/event-calendar/events` | filter (emitted in `render` after source branch) | `(array $data, array $settings)` | Final mutate point — third-parties can inject/filter events regardless of source |
| `eael_calendar_column_heading_week_date_formats` | filter (emitted in `register_controls`) | `array $formats` | Customise column-header moment-format options shown in the panel |
| `eael/is_plugin_active` | filter (consumed) | `bool $active, string $plugin_basename` | EA-internal feature-detect — used twice (the_events_calendar + eventon checks) |
| `eael/pro_enabled` | filter (consumed) | `bool $enabled` | Gates EventOn source warning + section |
| `eventCalendar.reinit` | JS hook (emitted) via `eael.hooks.doAction` | — | Cross-widget reflow signal — Adv_Tabs fires this on tab-switch so calendar inside an inactive tab re-renders correctly when revealed |

## JavaScript Lifecycle

- **Trigger:** `jQuery(window).on("elementor/frontend/init", …)` registering `elementorFrontend.hooks.addAction("frontend/element_ready/eael-event-calendar.default", EventCalendar)` at [js line 491](../../src/js/view/event-calendar.js#L491). Older registration pattern (not the newer `eael.hooks.addAction("init", "ea", …)` used in image-accordion / login-register).
- **Guard:** `if (eael.elementStatusCheck('eaelEventCalendar')) return false;`
- **Vendor dependencies:**
  - `FullCalendar` v5 (global via `assets/front-end/js/lib-view/full-calendar/calendar-main.min.js`)
  - `moment.js` (WP-registered handle)
  - `DOMPurify` global (vendor at `assets/front-end/js/lib-view/dom-purify/purify.min.js`)
  - `fancyTable` jQuery plugin (table layout only)
- **Reads on init:** all `data-*` attributes from `.eael-event-calendar-cls` div — `events`, `first_day`, `locale`, `defaultview`, `defaultdate`, `event_limit`, `popup_date_format`, `monthcolumnheaderformat`, `weekcolumnheaderformat`, `thumbnail_position`, `time_format`, `cal_id`, `translate`, `multidays_event_day_count`, `location-display`.
- **Branches:**
  - `wrapper.hasClass('layout-calendar')` → FullCalendar bootstrap; else fancyTable bootstrap.
  - `event._def.extendedProps.is_redirect === 'yes'` → set `href` + `target` + `rel` + custom attributes on the element; otherwise wire click to popup modal.
  - `thumbnailPosition` 5-way switch on popup population.
  - Table layout reads `data-items-per-page` and the `.eael-ec-event-date` column index for fancyTable's sortColumn arg.
- **Runtime state:**
  - `eventAll` closure holds the full event array; search filter on calendar layout rebuilds `calendar.addEventSource(filteredEvents)` after `calendar.removeAllEvents()`.
  - `setTimeout(() => calendar.setOption('locale', locale), 100)` re-applies locale post-render (FullCalendar's init locale arg sometimes misses).
  - `IntersectionObserver` on the calendar element dispatches `window resize` event whenever the element enters/exits viewport — fixes FullCalendar's broken layout when initially hidden (inside tabs, lightboxes, sliders).
- **Custom events / API:**
  - Listens on `eael.hooks.addAction("eventCalendar.reinit", "ea", …)` to call `calendar.today()` — Adv_Tabs / Adv_Accordion fire this on activation so a calendar inside a previously-hidden panel snaps back to today's view.
  - No window globals exposed.
- **Security scrubbing in JS:**
  - On every redirect-anchor render, iterates `element[0].attributes` and `.removeAttr()` for any attribute starting with `on`. Rewrites `href` starting with `javascript:` to `#`.
  - Same scrub applied to `modalFooterLink` before showing.
  - All dynamic title / description / location / category / details-button-text writes wrapped in `DOMPurify.sanitize(...)`.

## Common Issues

### Google API path returns empty calendar without showing an error

- **Likely cause:** API key invalid, calendar ID wrong, or calendar not public. `get_google_calendar_events` returns `[]` silently when `json_decode($data)->error` is set ([line 4275](../../includes/Elements/Event_Calendar.php#L4275)) — no admin notice, no log.
- **Diagnose:** Open the page with `?eael_debug=1` and tail PHP error log. Or hit `https://www.googleapis.com/calendar/v3/calendars/{ID}/events?key={KEY}` directly in browser — Google returns JSON with `error` block describing the issue.
- **Fix:** For private calendars, set sharing to "Make available to public" in Google Calendar settings. For API quotas, check Cloud Console → API & Services → reCAPTCHA / Calendar quota.

### The Events Calendar date_range filter ignored in Calendar layout

- **Likely cause:** [Render code at line 4375](../../includes/Elements/Event_Calendar.php#L4375) only forwards `start_date` / `end_date` to `tribe_get_events()` when layout is `'table'`. Calendar layout fetches all events up to `max_result` regardless of date-range setting.
- **Diagnose:** Switch layout to Table — date range starts working.
- **Fix:** Workaround — use a high `max_result` and rely on calendar navigation. Or patch the condition to include calendar layout.

### Multiple Event_Calendar widgets on one page show the wrong popup

- **Likely cause:** `#eaelecModal` ID is hardcoded ([line 4035](../../includes/Elements/Event_Calendar.php#L4035)). The second widget's modal markup is duplicated in DOM, but JS selectors target `#eaelecModal` (first match wins). Click on second widget's event populates first widget's modal node.
- **Diagnose:** Inspect — count `#eaelecModal` matches on the page.
- **Fix:** Only one Event_Calendar per page. If you need multiple, the modal needs an instance suffix — workaround is to use Pro's Event_Calendar carousel or different widgets per region.

### FullCalendar layout collapsed (zero height) inside a tab/lightbox/popup

- **Likely cause:** FullCalendar measures container width during `render()`; when hidden (`display: none`), it gets 0 and stays collapsed when revealed.
- **Diagnose:** Calendar shows up correctly when its parent is visible on initial page load, but breaks when nested in Adv_Tabs.
- **Fix:** The widget ships with `IntersectionObserver` + `eventCalendar.reinit` hook listener for exactly this. Confirm the parent widget fires `eael.hooks.doAction("eventCalendar.reinit", ...)` on its activation. Adv_Tabs and Adv_Accordion do; third-party tabs may not. Trigger `window.dispatchEvent(new Event('resize'))` manually after activation.

### Event description showing raw HTML or stripped content

- **Likely cause:** Description WYSIWYG was sanitised through `wp_kses_post()` server-side, then again through `DOMPurify.sanitize()` client-side. If using `<style>` or `<script>` they'll be stripped (correctly). If using exotic attributes (data-bind, x-data) they may also be stripped by DOMPurify default config.
- **Diagnose:** Check the raw `data-events` JSON in DOM — confirm what HTML reached the browser. Compare to what's actually in the modal.
- **Fix:** For trusted advanced markup, hook `eael/event-calendar/events` filter to set `description` to a placeholder marker and inject after FullCalendar render via custom JS. Don't relax DOMPurify defaults — they're the security boundary.

## Known Limitations

- **`#eaelecModal` hardcoded** ([line 4035](../../includes/Elements/Event_Calendar.php#L4035)) — multiple widgets on one page produce duplicate IDs; first match wins; second widget's popup is hijacked.
- **The Events Calendar date_range is silently table-only** ([line 4375](../../includes/Elements/Event_Calendar.php#L4375)) — no panel notice that Calendar layout ignores it.
- **`xssAttributes()` reference list at [line 4056](../../includes/Elements/Event_Calendar.php#L4056) is unused inside this class** — 73 `on*` names returned but never consumed. JS does ad-hoc scrub inline. Dead code or shared-reference helper depending on caller.
- **All events embedded as a single base64-friendly attribute on the wrapper** — view-source reveals every event (descriptions, URLs, categories) regardless of any post-publish status. Future-scheduled events also visible. No widget-level access control.
- **`eventon` SELECT option is registered in Lite** but produces an empty calendar and a Pro warning notice. Users can save the widget in that broken state without realising.
- **No CSP friendliness** — DOMPurify needs `unsafe-inline` in some setups; FullCalendar v5 also uses inline style attributes on event cells. Sites with strict `style-src` will see calendar visual glitches.
- **Google API transient bucketed at hourly granularity** — `gmdate('Y-m-d H', ...)` in the cache key ([line 4253](../../includes/Elements/Event_Calendar.php#L4253)) means same-hour cache hits but a 1-second-after-the-hour render misses cache. Predictable rather than wrong, but worth noting.
- **`get_random_colors()` palette is hardcoded with no filter** — extension authors can't customise the random color rotation without overriding the entire method.
- **`is_old_event()` not applied to FullCalendar navigation** — past events excluded from initial render, but if user clicks prev-month on calendar, the empty back-fill creates a confusing "no events" appearance with no indicator that the filter is on.
- **The widget declares `font-awesome-4-shim` style depend** — but actually uses `eicon-*` (Elementor icons) for popup chrome (`.eicon-calendar`, `.eicon-map-pin`). The FA4 shim is redundant.
- **Modal scrubber removes `on*` and `javascript:` but doesn't sanitise `style` attribute** — anchor scrub at [js lines 115-126](../../src/js/view/event-calendar.js#L115) doesn't touch style; `<a style="background:url(...)" />` could exfiltrate.
- **`eael.hooks.addAction("eventCalendar.reinit", "ea", …)` adds the handler unconditionally** — multiple widgets register the same callback name without an instance suffix; when one fires, all listening calendars call `today()`.
