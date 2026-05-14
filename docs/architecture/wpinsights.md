# WPInsights integration / Plugin Usage Tracker

> Triage / planning doc for [Issue #809](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/809). Documents the telemetry pipeline: what data is collected (`get_data()`), how widget / extension usage is computed (`get_used_elements_count()`), the cron lifecycle, the diff-based send strategy with failure-retry queue, the goodbye / deactivation form, and the `wpins_*` option-key catalog. Pairs with [`consent.md`](consent.md) — read together.

## Context

The plugin reports usage data daily to WPInsights (WPDeveloper-owned analytics, endpoint `https://send.wpinsight.com/process-plugin-data`). Implementation lives in the vendored `Plugin_Usage_Tracker` (~1,270 lines, shipped via `priyomukul/wp-notice` Composer package). Telemetry is consent-gated — see [`consent.md`](consent.md) for how the opt-in decision is captured. This doc focuses on what happens *after* consent: payload shape, change detection, transport, and lifecycle hooks.

## Verified facts

All facts from the issue verified against the current codebase. Adding the payload shape, widget-usage computation, and option-key catalog the issue listed as "What's missing":

- **File:** [`includes/Classes/Plugin_Usage_Tracker.php`](../../includes/Classes/Plugin_Usage_Tracker.php) (1,274 lines)
- **`WPINS_VERSION = '3.0.3'`** ([line 22](../../includes/Classes/Plugin_Usage_Tracker.php#L22))
- **API endpoint:** `https://send.wpinsight.com/process-plugin-data` ([line 26](../../includes/Classes/Plugin_Usage_Tracker.php#L26)) — singular `wpinsight.com`
- **Cron event hook:** `put_do_weekly_action` ([line 81](../../includes/Classes/Plugin_Usage_Tracker.php#L81)) — naming says "weekly"
- **Cron recurrence:** `daily` ([line 43](../../includes/Classes/Plugin_Usage_Tracker.php#L43)) — ⚠️ **drift confirmed**: hook name "weekly", recurrence "daily". `is_time_to_track()` ([line 315](../../includes/Classes/Plugin_Usage_Tracker.php#L315)) adds a 1-day throttle on top, so effective frequency = 1 send per plugin per day even when cron fires more often.
- **Self-cron flag declared but NOT wired** — `enable_self_cron` is set when `DISABLE_WP_CRON` is true ([line 79](../../includes/Classes/Plugin_Usage_Tracker.php#L79)) but `force_tracking()` registration on `admin_init` is commented out ([line 164](../../includes/Classes/Plugin_Usage_Tracker.php#L164)). **On hosts with `DISABLE_WP_CRON`, tracking effectively does not run.** Already flagged in [`consent.md`](consent.md).
- **Activation:** `register_activation_hook` → `activate_this_plugin()` → if tracking allowed → `schedule_tracking()` → `wp_schedule_event( time(), 'daily', 'put_do_weekly_action' )` ([line 105-107](../../includes/Classes/Plugin_Usage_Tracker.php#L105))
- **Deactivation:** `register_deactivation_hook` → `deactivate_this_plugin()` → reads `wpins_deactivation_reason_<plugin>` + `_details_<plugin>` → POST with `status='Deactivated'`, `deactivated_date` → delete the two options → `wp_clear_scheduled_hook` ([line 125-154](../../includes/Classes/Plugin_Usage_Tracker.php#L125))

### Payload shape — `get_data()` ([line 335-423](../../includes/Classes/Plugin_Usage_Tracker.php#L335))

Body POSTed to `send.wpinsight.com/process-plugin-data`:

| Field | Source | Notes |
| ----- | ------ | ----- |
| `plugin_slug` | `$this->plugin_name` | basename of plugin file (`essential-addons-for-elementor-lite`) |
| `url`, `site_name`, `site_version`, `site_language`, `charset` | `get_bloginfo()` | site identity |
| `wpins_version` | `WPINS_VERSION` constant | `3.0.3` |
| `php_version` | `phpversion()` | |
| `multisite` | `is_multisite()` | |
| `file_location` | `__FILE__` | tracker's own location — debugging only |
| `email` | `wp_get_current_user()->user_email` or `admin_email` | **only when `marketing` is true** (default); see [consent.md gap](consent.md#whats-missing) — never explicitly asked |
| `marketing_method` | `$this->marketing` | bool |
| `server` | `$_SERVER['SERVER_SOFTWARE']` | Apache / Nginx / etc. |
| `active_plugins` | `get_option('active_plugins')` or network option | array of plugin paths |
| `inactive_plugins` | all installed minus active | array of plugin paths |
| `text_direction` | `is_rtl() ? 'RTL' : 'LTR'` | |
| `plugin`, `version` | `get_plugin_data($plugin_file)` | EA's own name + version from header |
| `status` | `'Active'` / `'Deactivated'` / `'NOT FOUND'` | NOT FOUND when `get_plugin_data` returns empty |
| `theme`, `theme_version` | `wp_get_theme()` | |
| `optional_data` | `get_used_elements_count()` | **the EA-specific payload** — see below |
| `deactivation_reason`, `deactivation_details`, `deactivated_date` | `wpins_deactivation_*` options | only on deactivation send |
| `country` | `http://ip-api.com/json/<remote-IP>?fields=country` lookup | **only on first send**; calls third-party `ip-api.com` over **HTTP not HTTPS** — see Gaps |
| `item_id` | `'760e8569757fa16992d8'` (EA-specific constant) | first send only |

### Widget / extension usage computation — `get_used_elements_count()` ([line 1113-1273](../../includes/Classes/Plugin_Usage_Tracker.php#L1113))

Static method. Two data paths with primary / fallback logic:

1. **Primary (since v6.3.3) — Elementor's global usage option:** reads `get_option('elementor_controls_usage')` ([line 1117](../../includes/Classes/Plugin_Usage_Tracker.php#L1117)) — Elementor itself maintains this as an aggregate per-document-type, per-element-type usage count. Tracker iterates the structure, filters elements with `eael-` prefix, and uses `Elements_Manager::replace_widget_name()` to handle legacy widget renames (e.g. `eael-pricing-table` → `price-table`).
2. **Fallback — post-meta scan:** queries `$wpdb->postmeta WHERE meta_key = '_eael_widget_elements'` ([line 1189-1191](../../includes/Classes/Plugin_Usage_Tracker.php#L1189)) — direct DB query, no caching. Per-post `_eael_widget_elements` (set by EA's `Asset_Builder` when widgets are detected) is joined with per-post `_elementor_controls_usage` for the count. Used only when primary path returns empty.
3. **Extension usage (in addition to widget counts):** `extract_extension_usage_from_controls()` ([line 1239-1273](../../includes/Classes/Plugin_Usage_Tracker.php#L1239)) walks the `controls` data recursively and detects 11 specific switch-control keys mapped to extension names:
   - `eael_particle_switch` → `eael-section-particles`
   - `eael_parallax_switcher` → `eael-section-parallax`
   - `eael_tooltip_section_enable` → `eael-tooltip-section`
   - `eael_ext_content_protection` → `eael-content-protection`
   - `eael_cl_enable` → `eael-conditional-display`
   - `eael_ext_advanced_dynamic_tags` → `eael-advanced-dynamic-tags`
   - `eael_enable_custom_cursor` → `eael-custom-cursor`
   - `eael_liquid_glass_effect_switch` → `eael-liquid-glass-effect`
   - `eael_wrapper_link_switch` → `eael-wrapper-link`
   - `eael_smooth_animation_section` → `eael-smooth-animation`
   - `eael_hover_effect_switch` → `eael-special-hover-effect`

Adding a new EA extension that should be tracked requires editing this switch — silent gap otherwise.

### Transport — `send_data()` ([line 442-539](../../includes/Classes/Plugin_Usage_Tracker.php#L442)) and `remote_post()` ([line 547-566](../../includes/Classes/Plugin_Usage_Tracker.php#L547))

- **Site ID assignment:** server responds with `siteId` on first send; stored in `wpins_<plugin>_site_id` option. Subsequent sends include site_id for server-side correlation.
- **Diff-based send:** after first send, only changed fields are POSTed. Baseline of last-sent data lives in `wpins_<plugin>_<site_id>` option; `diff()` ([line 574](../../includes/Classes/Plugin_Usage_Tracker.php#L574)) computes the delta.
- **URL change detection:** `wpins_<plugin>_original_url` option stores the URL at first registration. If current URL differs (site migration), site_id is reset and a fresh initial send runs ([line 452](../../includes/Classes/Plugin_Usage_Tracker.php#L452)).
- **Failure queue:** on `wp_remote_post` failure, payload is stored in `wpins_<plugin>_<site_id>_send_failed` option. Next cron pass attempts to send the queued failed data first; merges with new diff if both exist. **No cap on queue size; no exponential backoff** — failed payloads accumulate if the API is unreachable.
- **Transport details:** POST, 30s timeout, 5 redirects allowed, HTTP 1.1, user-agent `PUT/1.0.0; <site-url>`. Treats any non-200 response as `WP_Error`.

### Goodbye / deactivation form ([line 711-880+](../../includes/Classes/Plugin_Usage_Tracker.php#L711))

- **`deactivate_action_links()`** ([line 728](../../includes/Classes/Plugin_Usage_Tracker.php#L728)) — only runs `if( $this->is_tracking_allowed() )`. Injects an `onclick` interceptor + modal wrapper around the plugin's "Deactivate" link.
- **5 hardcoded reasons** ([line 749-770](../../includes/Classes/Plugin_Usage_Tracker.php#L749)): "no longer need", "found better plugin" (+ free-text), "couldn't get to work", "temporary deactivation", "Other" (+ textarea). Filterable via `wpins_form_text_<plugin>` ⚠️ **un-prefixed legacy hook** (no `eael/` prefix).
- **`wp_ajax_deactivation_form_<plugin>`** ([line 172](../../includes/Classes/Plugin_Usage_Tracker.php#L172)) — submission endpoint; nonce `wpins_deactivation_nonce`; stores reason in `wpins_deactivation_reason_<plugin>` + free-text in `wpins_deactivation_details_<plugin>`.
- The deactivation hook (`register_deactivation_hook`) reads those two options, includes them in the final POST as `deactivation_reason` / `deactivation_details`, then deletes them.

### `wpins_*` option-key catalog (complete)

| Option key | Purpose | Stored by | Read by |
| ---------- | ------- | --------- | ------- |
| `wpins_allow_tracking` | consent decision (array `[plugin => plugin]`) | `set_is_tracking_allowed()` | `is_tracking_allowed()` — see [`consent.md`](consent.md) |
| `wpins_block_notice` | notice suppression after decision | `update_block_notice()` | notice render | see [`consent.md`](consent.md) |
| `wpins_last_track_time` | per-plugin send throttle (array of timestamps) | `set_track_time()` | `is_time_to_track()` |
| `wpins_<plugin>_site_id` | server-assigned site ID | `send_data()` (line 475) | `send_data()` (line 447) |
| `wpins_<plugin>_original_url` | URL at first registration; resets site_id on change | `send_data()` (line 476) | `send_data()` (line 450) |
| `wpins_<plugin>_<site_id>` | baseline of last-sent data for diff computation | `send_data()` (line 477, 508, 525) | `send_data()` (line 488) |
| `wpins_<plugin>_<site_id>_send_failed` | retry queue for failed sends | `send_data()` (line 522) | `send_data()` (line 490) |
| `wpins_deactivation_reason_<plugin>` | selected reason from goodbye form | `deactivate_reasons_form_submit()` | `deactivate_this_plugin()` then deleted |
| `wpins_deactivation_details_<plugin>` | free-text from goodbye form | same | same |

## What's missing

Gaps the issue captured plus discoveries from verification — these are open follow-ups, not blockers for closing the architecture doc:

- **`put_do_weekly_action` cron-name drift unresolved.** Either rename to `put_do_daily_action`, or change recurrence to `weekly`, or document the drift as known-historical. Currently mismatched.
- **`http://ip-api.com/json/` third-party dependency over HTTP.** First-send country lookup hits a 3rd-party geolocation service over plaintext HTTP. Privacy implication: site visitor IP is exposed to `ip-api.com`. Should be HTTPS at minimum; ideally documented as a consent consideration in the disclosure copy (currently isn't).
- **Failed-send queue has no cap or backoff.** `wpins_<plugin>_<site_id>_send_failed` accumulates indefinitely if API is down. Risk: large option grows DB / autoload size unbounded.
- **`country` field captured only on first send.** Never refreshed even if site moves to a different host or hosting region. Stale by design.
- **Plugin-update behaviour undocumented.** Site_id persists across updates (option not cleared); diff-based send means the upgrade itself isn't a separate event — only changed fields (like `version`) get re-sent. No "upgrade event" hook.
- **Server-side (WPInsights) is out of scope** — data retention, deletion-request endpoint, response format beyond `siteId`, GDPR/SOC2 posture all live on the WPDeveloper SaaS side. Cross-reference needed if customers ask.
- **Email-marketing consent isn't a separate question.** Disclosure copy mentions an email for the 10% discount coupon ([`consent.md`](consent.md)), but the `email` field is included unconditionally when `marketing = true` (which defaults to true). User can't opt out of marketing while opting in to tracking. Worth a Pro / engineering ticket.
- **`extract_extension_usage_from_controls()` switch statement.** Adding a new extension that should be tracked requires editing the hardcoded 11-key switch. No registration API. Onboarding documentation gap for extension authors.
- **`wpins_form_text_<plugin>` filter is un-prefixed legacy.** Renaming requires dual-emit migration per the standard EA pattern. See `_patterns.md` legacy-hook discussion (note: `_patterns.md` is widget-side; same principle applies here).
- **No structured event tracking.** Only snapshot diffs are sent. Specific user actions (widget added, setting changed) are not events — they're observed only when the next cron snapshot detects the change. Adequate for usage analytics; insufficient for funnel / cohort analysis.

## Proposed location

`docs/architecture/wpinsights.md` — matches the issue's proposal, follows the precedent from #806 (`admin-notices.md`), #807 (`quick-setup.md`), #808 (`consent.md`).

## Acceptance — what "done" looks like

- [x] `docs/architecture/wpinsights.md` written (this doc)
- [x] Full payload shape documented (table above)
- [x] Widget/extension usage computation explained (two-path logic with 11-key extension switch documented)
- [x] `wpins_*` option-key catalog complete (9 keys)
- [x] `put_do_weekly_action` drift documented as known-historical with effective-frequency clarification
- [x] Cross-link to [`consent.md`](consent.md) and [`quick-setup.md`](quick-setup.md)
- [x] Transport details (diff-based, retry queue, site_id assignment) covered
- [x] Goodbye / deactivation form flow documented
- [x] Third-party `ip-api.com` HTTP dependency flagged
- [ ] Cron-name drift physical fix (rename or recurrence change) — engineering follow-up, not docs
- [ ] Failed-send queue cap — engineering follow-up

## Pairs with

- [`consent.md`](consent.md) — covers *why* data is sent. Storage keys (`wpins_allow_tracking`, etc.) overlap; this doc owns the transport keys (`wpins_<plugin>_site_id`, baseline, failed-queue).
- [`quick-setup.md`](quick-setup.md) — wizard's `enable_wpins_process` AJAX is one of the two consent surfaces; once consent is granted, telemetry follows the path documented here.

## Related

- Part of the cross-cutting docs initiative ([#804](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/804)–#810).
- Vendored library: `priyomukul/wp-notice` v2.x-dev ([GitHub](https://github.com/priyomukul/wp-notice)). Upstream changes to `Plugin_Usage_Tracker` affect this doc.
- Admin notices: [`admin-notices.md`](admin-notices.md) — the opt-in notice that gates this whole pipeline.

## Out of scope

- WPInsights server-side architecture, retention, deletion-request endpoint
- Rewriting the tracker to use Action Scheduler instead of `wp_schedule_event`
- Moving from option-based queues to a dedicated DB table
- Adding event-based tracking on top of snapshot diffs
- Privacy-policy / GDPR legal review (separate counsel-led ticket; see [`consent.md`](consent.md))
