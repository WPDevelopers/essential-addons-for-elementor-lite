# Admin Notices

How admin-area notices, campaign banners, and the Black Friday / Cyber Monday pointer reach the user — what's currently active, what's dormant, where the copy is stored, how dismissals work, and how to add a new campaign.

This doc answers the four sub-questions in [issue #806](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/806):

1. ✅ **Where does the actual campaign copy come from? Hardcoded or remote-fetched?** — **Hardcoded in PHP. There is no remote fetch.** Confirmed by full-codebase audit.
2. ✅ This `docs/architecture/admin-notices.md` exists.
3. ✅ Step-by-step instructions for adding new campaign notices — see § Adding a New Campaign Notice.
4. ✅ Dismissal-lifecycle flow diagrams — see § Architecture Diagram.

## Overview

The plugin has **two parallel admin-notice systems**, only one of which is currently active:

| System | Status in Lite | Used for | Code surface |
| ------ | -------------- | -------- | ------------ |
| **`bfcm-pointer.php`** | ✅ Active (the only active campaign at present) | Time-bound seasonal sales (BFCM 2025) | 70 lines, included from `Bootstrap` |
| **`WPDeveloper_Notice` class** | ⚠️ Dormant — class file exists, infrastructure complete, **but never instantiated in Lite** | Cross-WPDev-plugin notice framework (Templately, Essential Blocks use it; Lite does not) | 955 lines, no active caller |

Both are relevant: the active path is what users see today; the dormant class is what an engineer would *expect* to see and would look at first when wiring a new notice. Documenting both prevents confused contributors from spending hours wiring `WPDeveloper_Notice` when adding a new BFCM-style campaign should follow `bfcm-pointer.php`'s pattern instead.

There is also a small set of **error / diagnostic notices** (Elementor-not-loaded, EA-settings-page proxy) that follow neither system — they hook `admin_notices` directly. Documented at the bottom.

## Components

| File / Symbol | Lines | Status | Role |
| ------------- | ----- | ------ | ---- |
| [`includes/bfcm-pointer.php`](../../includes/bfcm-pointer.php) | 70 | **Active** | Currently-running BFCM 2025 campaign — WP core `wp-pointer` API, hardcoded copy, time-gated auto-disable, transient-based dismissal |
| [`includes/Classes/WPDeveloper_Notice.php`](../../includes/Classes/WPDeveloper_Notice.php) | 955 | **Dormant** | Generic cross-plugin notice framework; class file is autoloaded via PSR-4 but no code calls `new WPDeveloper_Notice(...)` in this codebase |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L134) | line 134 | Active | `include_once` for `bfcm-pointer.php` — the wiring that makes BFCM run |
| [`includes/Classes/Bootstrap.php`](../../includes/Classes/Bootstrap.php#L305) | lines 305-306 | Active | Elementor-not-loaded error notice — hooks both `admin_notices` and `eael_admin_notices` |
| [`includes/Traits/Helper.php`](../../includes/Traits/Helper.php#L593) | lines 593-600 | Active | EA-settings-page proxy: removes vanilla `admin_notices` and re-fires `eael_admin_notices` instead |
| [`includes/Classes/Plugin_Usage_Tracker.php`](../../includes/Classes/Plugin_Usage_Tracker.php) | — | Hooks `wpdeveloper_optin_notice_for_<plugin>` action — but only fires if `WPDeveloper_Notice` is instantiated, which it isn't |
| [`vendor/priyomukul/wp-notice`](../../vendor/priyomukul/wp-notice/) | composer dep | Active in scope, separate from this doc | Independent admin-notice library bundled as a dependency; renders cache / debug notices via its own `admin_notices` hook |

### What `WPDeveloper_Notice` would manage if active

The class is well-designed and ships with five notice types out of the box:

| Notice key | Purpose |
| ---------- | ------- |
| `opt_in` | First-time consent / data-share opt-in |
| `first_install` | Welcome / onboarding after activation |
| `update` | "What's new" after plugin upgrade |
| `review` | Rating / review request (with a maybe-later) |
| `upsale` | Cross-promote a sister plugin (e.g. Templately) — auto-suppresses if the sister plugin is installed |

Each notice has its own copy (set on the class instance via property assignment), its own thumbnail, its own list of action links, and its own time scheduling.

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ ACTIVE PATH — bfcm-pointer.php                                   ║
║                                                                  ║
║   Bootstrap::__construct() runs                                  ║
║       │                                                          ║
║       ▼                                                          ║
║   include_once 'includes/bfcm-pointer.php' (Bootstrap.php:134)   ║
║       │                                                          ║
║       ▼                                                          ║
║   add_action('in_admin_header', closure)                         ║
║       │                                                          ║
║       ▼  for every admin page request                            ║
║   GUARDS (in order):                                             ║
║     1. Pro plugin active?            → bail                      ║
║     2. Past Dec 4, 2025 21:59:59?    → bail                      ║
║     3. Page is dashboard or          → bail (only show           ║
║        toplevel_page_eael-settings?    on these two pages)       ║
║     4. Dismiss transient set?        → bail                      ║
║     5. Pointer priority claimed?     → bail                      ║
║       │                                                          ║
║       ▼ (all guards pass)                                        ║
║   wp_enqueue_script('jquery')                                    ║
║   wp_enqueue_style('wp-pointer')                                 ║
║   wp_enqueue_script('wp-pointer')                                ║
║       │                                                          ║
║       ▼                                                          ║
║   Set _wpdeveloper_plugin_pointer_priority option = 1            ║
║   (cross-plugin coordination — first plugin to claim it owns     ║
║    this admin session's pointer)                                 ║
║       │                                                          ║
║       ▼                                                          ║
║   Inline <script> renders pointer pointing at                    ║
║   #toplevel_page_eael-settings menu item:                        ║
║     - heading: "Essential Addons: Black Friday Sale"             ║
║     - body: "Unlock the full power of Elementor with 110+        ║
║       advanced elements…"                                        ║
║     - CTA button: "Save $120" → essential-addons.com/bfcm-…      ║
║     - close handler: jQuery.post(ajaxurl, dismiss-wp-pointer)    ║
║                                                                  ║
║ DISMISSAL (separate add_action('admin_init', closure)):          ║
║   $_POST['action'] === 'dismiss-wp-pointer'                      ║
║   AND $_POST['pointer'] === 'eael'                               ║
║       │                                                          ║
║       ▼                                                          ║
║   set_transient('eael_bfcm25_pointer_dismiss', true,             ║
║                  DAY_IN_SECONDS * 30)                            ║
║   delete_option('_wpdeveloper_plugin_pointer_priority')          ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ DORMANT PATH — WPDeveloper_Notice class                          ║
║ (Class exists, autoloaded, but nothing calls `new` on it.        ║
║  Documented for context; this is what would happen if it were    ║
║  activated.)                                                     ║
║                                                                  ║
║   Hypothetical activator:                                        ║
║     $notice = new WPDeveloper_Notice($plugin_file, $version)     ║
║     $notice->options_args = [                                    ║
║         'first_install' => 'pending',                            ║
║         'version' => '6.6.3',                                    ║
║         'notice_will_show' => [                                  ║
║             'opt_in'        => 0,                                ║
║             'first_install' => 0,                                ║
║             'update'        => 0,                                ║
║             'review'        => 0,                                ║
║             'upsale'        => 0,                                ║
║         ],                                                       ║
║     ];                                                           ║
║     $notice->message('first_install', 'Welcome! …');             ║
║     $notice->thumbnail('first_install', 'https://…/thumb.png');  ║
║     $notice->classes('first_install', 'notice notice-info');     ║
║     $notice->upsale_args = [                                     ║
║         'slug'      => 'templately',                             ║
║         'file'      => 'templately.php',                         ║
║         'condition' => ['by' => 'class',                         ║
║                         'class' => 'Templately'],                ║
║         'btn_text'  => 'Install Now',                            ║
║         'page_slug' => 'templately',                             ║
║     ];                                                           ║
║     $notice->init();                                             ║
║       │                                                          ║
║       ▼ schedules notices on init priority 10                    ║
║   For each notice in time-sorted notice_will_show order:         ║
║     1. deserve_notice() — has user already dismissed via         ║
║        wpdeveloper_notice_<version_underscores> meta?            ║
║     2. notice_time vs current time vs 2-day cne_time window      ║
║     3. if upsale: condition check (sister plugin already         ║
║        installed?) → suppress                                    ║
║     4. add_action('admin_notices', 'admin_notices')              ║
║     5. add_action('eael_admin_notices', 'admin_notices')         ║
║       │                                                          ║
║       ▼                                                          ║
║   Render: thumbnail + message + dismiss links + AJAX dismiss     ║
║   button JS                                                      ║
║                                                                  ║
║ DISMISSAL paths (3):                                             ║
║   a) Click "Don't show again" link → ?plugin=…&dismiss=true      ║
║      → clicked() handler updates user meta + options             ║
║   b) Click "Maybe later" link → ?plugin=…&later=true             ║
║      → notice_will_show[notice] = current + 7 days               ║
║   c) Click WP "X" dismiss button → AJAX                          ║
║      wp_ajax_wpdeveloper_notice_dissmiss_for_<plugin_name>       ║
║      → update user meta + options                                ║
║                                                                  ║
║ STORAGE keys:                                                    ║
║   option:    wpdeveloper_plugins_data                            ║
║              [plugin_name][notice_will_show][notice] = timestamp ║
║   user meta: wpdeveloper_notices_seen                            ║
║              [wpdeveloper_notice_<ver>][plugin_name][] = notice  ║
║   user meta: <plugin_name>_<notice>  = true (legacy/dismissed)   ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ ERROR / DIAGNOSTIC PATH                                          ║
║                                                                  ║
║   Bootstrap.php:305 — Elementor not loaded                       ║
║       add_action('admin_notices', 'elementor_not_loaded')        ║
║       add_action('eael_admin_notices', 'elementor_not_loaded')   ║
║                                                                  ║
║   Helper trait:598 — EA settings page proxy                      ║
║       remove_all_actions('admin_notices')                        ║
║       remove_all_actions('all_admin_notices')                    ║
║       remove_all_actions('network_admin_notices')                ║
║       add_action('admin_notices', fn() =>                        ║
║                  do_action('eael_admin_notices'))                ║
║                                                                  ║
║   → Why both hooks: vanilla admin_notices is what WP fires       ║
║     globally; eael_admin_notices is the relay used on the        ║
║     EA settings page (where vanilla is suppressed).              ║
║     Notices wanting to show in BOTH contexts must hook both.     ║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

### bfcm-pointer.php hooks

| Hook | Priority | Phase | Handler | Purpose |
| ---- | -------- | ----- | ------- | ------- |
| `in_admin_header` | 10 | Admin page request | closure | Render pointer when guards pass |
| `admin_init` | 10 | Admin init | closure | Handle `dismiss-wp-pointer` POST |

### WPDeveloper_Notice hooks (would fire if class were instantiated)

| Hook | Priority | Phase | Handler | Purpose |
| ---- | -------- | ----- | ------- | ------- |
| `init` | 10 | Plugin init | `first_install_track` | Persist plugin install timestamp + version |
| `init` | 10 | Plugin init | `hooks` | Register all per-notice action handlers |
| `deactivate_<plugin_file>` | 10 | Plugin deactivate | `first_install_end` | Clean up `wpdeveloper_plugins_data` option |
| `wpdeveloper_notice_clicked_for_<plugin>` | 10 | URL ?plugin= present | `clicked` | Record link click + dismiss / later |
| `wp_ajax_wpdeveloper_notice_dissmiss_for_<plugin>` | 10 | AJAX | `notice_dissmiss` | Process WP "X" dismiss button |
| `wp_ajax_wpdeveloper_upsale_notice_dissmiss_for_<plugin>` | 10 | AJAX | `upsale_notice_dissmiss` | Same for upsale |
| `wpdeveloper_before_notice_for_<plugin>` | 10 | Inside `admin_notices` | `before` | Open `<div class="notice ...">` |
| `wpdeveloper_after_notice_for_<plugin>` | 10 | Inside `admin_notices` | `after` | Close `</div>` |
| `wpdeveloper_notices_for_<plugin>` (action) | 10 | Inside `admin_notices` | `content` | Dispatch per-notice rendering |
| `wpdeveloper_<notice>_notice_for_<plugin>` (per type) | — | Inside `content()` | (extension hook) | Pre-render hook for each notice type |
| `admin_notices` | 10 | WP admin | `admin_notices` | Standard admin notice slot |
| `eael_admin_notices` | 10 | EA settings page | `admin_notices` | EA settings page proxy slot |

### Error / diagnostic hooks (always-on)

| Hook | Owner | Purpose |
| ---- | ----- | ------- |
| `admin_notices` | Bootstrap | Elementor-not-loaded notice (`Bootstrap.php:305`) |
| `eael_admin_notices` | Helper | EA-settings-page proxy relay (`Helper.php:598-600`) |

## Data Flow

### Active path: bfcm-pointer.php on a typical admin pageview

1. **WordPress fires `in_admin_header`** for every admin page after main query.
2. **Closure runs.** Reads `$this->pro_enabled` from the closure's binding context (set when bfcm-pointer.php was included by Bootstrap).
3. **Time gate check.** `time() > strtotime('09:59:59pm 4th December, 2025')` — past December 4, 2025 the closure returns immediately. Self-disabling — no admin action needed to retire the campaign.
4. **Page gate check.** Only WordPress dashboard (`pagenow === 'index.php'`) or EA settings (`get_current_screen()->id === 'toplevel_page_eael-settings'`) are valid. Any other admin page returns immediately.
5. **Dismissed check.** `get_transient('eael_bfcm25_pointer_dismiss')` — if the user dismissed within the last 30 days, returns immediately.
6. **Pointer priority claim.** `_wpdeveloper_plugin_pointer_priority` option is checked; if empty or > 1, set to 1. This is cross-plugin coordination: if Templately, Essential Blocks, and EA Lite all run BFCM pointers, only the first one to claim priority 1 actually shows. Others wait their turn.
7. **WP pointer assets enqueued.** `jquery` + `wp-pointer` style + `wp-pointer` script.
8. **Inline `<script>` rendered** that calls `jQuery('#toplevel_page_eael-settings').pointer({...}).pointer('open')` with hardcoded campaign copy — heading text, body text, CTA URL `https://essential-addons.com/bfcm-wp-admin-pointer`.
9. **User clicks the pointer's close button.** `close` callback in the pointer config fires `jQuery.post(ajaxurl, { pointer: 'eael', action: 'dismiss-wp-pointer' })`.
10. **Separate `admin_init` closure** processes the AJAX. Verifies `$_POST['action']` is `'dismiss-wp-pointer'` and `$_POST['pointer']` is `'eael'`. Sets `eael_bfcm25_pointer_dismiss` transient (30-day) and deletes `_wpdeveloper_plugin_pointer_priority` so the next plugin can claim it.

The whole pipeline executes in ~10ms per pageview when guards bail (which is almost always).

### Dormant path: what would happen if `WPDeveloper_Notice` were activated

1. Activator code instantiates `new WPDeveloper_Notice($plugin_file, $version)` and configures `options_args`, `message()`, `thumbnail()`, `classes()`, `upsale_args`, then calls `init()`.
2. `init()` runs:
   - `migration()` — handles legacy data shape (lines 116-130 — only relevant for upgrades from EA 3.7.2)
   - `add_action('init', 'first_install_track')` — captures install timestamp + version on first install
   - `add_action('deactivate_<plugin>', 'first_install_end')` — clears options on deactivation
   - `add_action('init', 'hooks')` — registers all notice handlers
3. **`hooks()` runs on `init`.** Reads `wpdeveloper_plugins_data` option to find scheduled notices and the notice currently due. Decides whether to enqueue an `admin_notices` callback for this pageview.
4. **Per-notice eligibility check** — `deserve_notice()` consults `wpdeveloper_notices_seen` user meta keyed by `wpdeveloper_notice_<version_underscores>`. If the user has already dismissed this notice for this version, skip.
5. **Time window check** — `cne_time` (default 2 days) is the lifetime of one notice; after expiry, fall through to the next scheduled notice. `maybe_later_time` (default 7 days) is what "Maybe Later" defers by.
6. **Upsale conditional suppression** — if the notice is an upsale and the sister plugin is already installed (per `upsale_args.condition.by` of either `class` or `function`), the notice is removed from the schedule entirely.
7. **`admin_notices` callback** fires when WP gets to admin output. The `before` / `content` / `after` action chain renders thumbnail + message + dismiss links + JS for AJAX dismissal.
8. **User dismisses.** Three paths:
   - Click "Don't show again" link → URL has `?plugin=<plugin>&dismiss=true` → `clicked()` records dismissal in user meta + clears the schedule slot.
   - Click "Maybe later" link → URL has `?plugin=<plugin>&later=true` → `clicked()` updates `notice_will_show[notice]` to current time + `maybe_later_time` (7 days).
   - Click WP "X" close button → JS POST to `wp_ajax_wpdeveloper_notice_dissmiss_for_<plugin>` → `notice_dissmiss()` records dismissal.

Storage keys to remember:

| Key | Storage | Shape | Purpose |
| --- | ------- | ----- | ------- |
| `wpdeveloper_plugins_data` | `wp_options` | `[plugin_name][notice_will_show][notice] = timestamp` | Schedule of when each notice is next eligible |
| `wpdeveloper_plugins_data` | `wp_options` | `[plugin_name][version]` | Last-tracked plugin version |
| `wpdeveloper_notices_seen` | `wp_usermeta` | `[wpdeveloper_notice_<v>][plugin_name][] = notice` | Per-version dismissal log |
| `<plugin_name>_<notice>` | `wp_usermeta` | bool | Legacy / per-notice dismissal flag |
| `eael_notice_migration` | `wp_options` | bool | One-shot migration flag from EA 3.7.2 user-meta layout |

## Configuration & Extension Points

### Active campaign (bfcm-pointer.php) — what's configurable

The current campaign is fully hardcoded. To customise:

| What | Where | How |
| ---- | ----- | --- |
| Campaign deadline | `bfcm-pointer.php:9` | Edit `strtotime('09:59:59pm 4th December, 2025')` |
| Pages where it shows | `bfcm-pointer.php:9` | Edit page slug list in the guard |
| Heading text | `bfcm-pointer.php:31-32` | Edit inline JavaScript string |
| Body text | `bfcm-pointer.php:32` | Edit inline JavaScript string |
| CTA button text | `bfcm-pointer.php:33` | Edit inline JavaScript string |
| CTA link target | `bfcm-pointer.php:33` | Edit inline JavaScript string |
| Dismiss duration | `bfcm-pointer.php:69` | Edit `DAY_IN_SECONDS * 30` |
| Pro detection bypass | `bfcm-pointer.php:9` | Already gated on `$this->pro_enabled` |

There are no filters or actions for third-party extension. Each campaign is its own file replacement.

### Dormant infrastructure (WPDeveloper_Notice) — what's configurable

If activated, the class would expose this set of extension points:

#### Properties (settable via `__set` magic method)

| Property | Shape | Purpose |
| -------- | ----- | ------- |
| `$message` | `[notice_key => HTML string]` | Notice body, set via `$instance->message('first_install', '<p>Welcome!</p>')` |
| `$thumbnail` | `[notice_key => image URL]` | Notice thumbnail |
| `$links` | `[notice_key => [link config arrays]]` | Action / dismiss links beneath the message |

#### Methods (callable via `__call`)

| Method | Args | Purpose |
| ------ | ---- | ------- |
| `message($notice, $html)` | notice key, HTML string | Sets `$message[$notice]` |
| `thumbnail($notice, $url)` | notice key, image URL | Sets `$thumbnail[$notice]` |
| `classes($notice, $css)` | notice key, CSS classes | Sets the `<div class="...">` for that notice |

#### Public properties

| Property | Type | Purpose |
| -------- | ---- | ------- |
| `$cne_time` | string | Current-notice-end time, default `'2 day'` |
| `$maybe_later_time` | string | "Maybe Later" defer, default `'7 day'` |
| `$finish_time` | array | Per-notice absolute end date — `[$notice_key => 'YYYY-MM-DD']` |
| `$options_args` | array | Initial schedule (`notice_will_show` keys + first-install metadata) |
| `$upsale_args` | array | `slug`, `file`, `condition`, `btn_text`, `page_slug` for cross-plugin install upsale |

#### Actions emitted (per notice instance)

| Action | When | Use to |
| ------ | ---- | ------ |
| `wpdeveloper_first_install_notice_for_<plugin>` | Inside `content()` for `first_install` notice | Inject custom HTML |
| `wpdeveloper_update_notice_for_<plugin>` | Same for `update` | Same |
| `wpdeveloper_review_notice_for_<plugin>` | Same for `review` | Same |
| `wpdeveloper_optin_notice_for_<plugin>` | Same for `opt_in` | Same |
| `wpdeveloper_upsale_notice_for_<plugin>` | Same for `upsale` | Same |
| `wpdeveloper_before_notice_for_<plugin>` | Just before notice render | Open custom wrapper |
| `wpdeveloper_after_notice_for_<plugin>` | Just after | Close custom wrapper |
| `wpdeveloper_before_upsale_notice_for_<plugin>` | Before upsale render | Same for upsale |
| `wpdeveloper_after_upsale_notice_for_<plugin>` | After upsale | Same |
| `wpdeveloper_notices_for_<plugin>` | The main render hook | What `content()` itself listens to |

## Adding a New Campaign Notice

The choice of which pattern to follow depends on what the campaign is:

### A. Time-bound seasonal campaign (BFCM, anniversary, holiday) → follow `bfcm-pointer.php`

This is the right pattern for short-lived campaigns where:
- The copy doesn't need to evolve based on user behaviour
- Self-disabling after a deadline is desirable
- Cross-plugin priority coordination is needed
- WP pointer style fits visually

**Recipe:**

1. **Copy the existing file as a starter:**
   ```bash
   cp includes/bfcm-pointer.php includes/anniversary-2026-pointer.php
   ```

2. **Edit the new file:**
   - Replace `eael_bfcm25_pointer_dismiss` with a new transient name (e.g. `eael_anniv26_pointer_dismiss`)
   - Update the deadline `strtotime(...)` to the campaign's end date
   - Update the page guard list if needed (default: dashboard + EA settings)
   - Update the inline `<script>` heading, body, CTA text, CTA URL
   - Update the pointer's anchor selector if pointing at a different menu item
   - The dismiss-handler closure on `admin_init` must update the matching new transient name

3. **Wire it from `Bootstrap.php`** — find the existing `include_once` call for `bfcm-pointer.php` (line 134) and either:
   - Replace it (BFCM is over) — change the path
   - Add alongside it (run both campaigns in parallel) — append a new `include_once` line. Cross-plugin priority coordination already handles the case of multiple pointers.

4. **Test:**
   - Activate plugin on a clean WP install. Visit dashboard. Pointer should appear.
   - Click dismiss. Confirm transient is set (`SELECT * FROM wp_options WHERE option_name LIKE '_transient_eael_anniv26_%'`). Reload — pointer should be gone.
   - Set the dismiss transient manually before opening the page, confirm pointer doesn't show.
   - Mock the date past the deadline (`strtotime` with an older date temporarily) and confirm the pointer auto-disables.

### B. Lifecycle / behavioural campaign (welcome, review request, upgrade hints) → activate `WPDeveloper_Notice`

This is the right pattern when:
- The notice should fire based on plugin lifecycle events (install, update, day N after install)
- Multiple notices need to co-exist with priorities
- Per-version "don't show again" tracking is needed
- Cross-plugin upsales are involved
- A standard WP admin notice style fits

**Recipe (starting from scratch since the class is currently dormant):**

1. **Add a wiring call** in `Bootstrap.php` somewhere appropriate (typically in `__construct` or a dedicated init method):
   ```php
   $notice = new \Essential_Addons_Elementor\Classes\WPDeveloper_Notice(
       EAEL_PLUGIN_BASENAME,    // e.g. 'essential-addons-for-elementor-lite/essential_adons_elementor.php'
       EAEL_PLUGIN_VERSION
   );
   $notice->options_args = [
       'first_install'    => 'pending',
       'time'             => time(),
       'version'          => EAEL_PLUGIN_VERSION,
       'notice_will_show' => [
           'opt_in'        => 0,
           'first_install' => 0,
           'update'        => 0,
           'review'        => 0,
           // 'upsale'     => 0,  // include only if you set upsale_args
       ],
   ];

   $notice->message(
       'first_install',
       '<p>' . esc_html__( 'Welcome to Essential Addons! Get started with our setup wizard.', 'essential-addons-for-elementor-lite' ) . '</p>'
   );
   $notice->thumbnail( 'first_install', EAEL_PLUGIN_URL . 'assets/admin/images/welcome.png' );
   $notice->classes( 'first_install', 'notice notice-info put-dismiss-notice' );

   $notice->init();
   ```

2. **Add custom action links** if the notice should have "Get Started" / "Don't show again" links instead of just a body:
   ```php
   $notice->__set( 'links', [
       'first_install' => [
           [
               'label'      => 'Get Started',
               'link'       => admin_url( 'admin.php?page=eael-settings' ),
               'link_class' => [ 'button', 'button-primary' ],
           ],
           [
               'label'     => 'Don\'t show again',
               'link'      => admin_url(),
               'data_args' => [ 'dismiss' => 'true' ],
           ],
       ],
   ] );
   ```

3. **Run a smoke test:**
   - Fresh install: notice should appear on next admin page after `init` fires.
   - Click "Don't show again": confirm `wpdeveloper_notices_seen` user meta now contains the notice.
   - Reload: notice should not reappear.
   - Update plugin to a new version: behavior depends on `set_args_on_update()` — generally clears the dismissal so the new version's "update" notice can fire.

### What you do **not** need to do

- ❌ Set up remote API endpoints — copy is hardcoded by design.
- ❌ Build a custom dismissal AJAX handler — `WPDeveloper_Notice` ships one (`wp_ajax_wpdeveloper_notice_dissmiss_for_<plugin>`).
- ❌ Build cross-plugin priority logic for `WPDeveloper_Notice` — it just shows in admin notices alongside others.
- ❌ Worry about user-meta cleanup on uninstall — handled by the migration code.

## Common Pitfalls

### Trying to wire `WPDeveloper_Notice` and forgetting to call `init()`

The class file is autoloaded via PSR-4, but **constructing the object alone does nothing** — you must call `init()` to actually register the hooks. Most "I added the class but my notice doesn't show" reports are this.

### Forgetting the `eael_admin_notices` hook on the EA settings page

[`Helper.php:593-600`](../../includes/Traits/Helper.php#L593) removes vanilla `admin_notices` on the EA settings page and re-fires `eael_admin_notices` instead. If you only hook `admin_notices`, your notice won't show on EA's own settings page. Hook **both** — see how `Bootstrap.php:305-306` does it for the Elementor-not-loaded notice and how `WPDeveloper_Notice.php:232-233` does it for its admin notices.

### `WPDeveloper_Notice` `notice_id` collisions across versions

The notice id is derived from the plugin version: `wpdeveloper_notice_6_6_3` for 6.6.3. Dismissals are tracked per notice id, which means **dismissing on version 6.6.3 does not carry forward to 6.6.4** — the user sees the same notice again after a plugin update. This is intentional (lets you re-show "what's new" after each update) but surprising if you didn't expect it.

### bfcm-pointer.php uses `$this->pro_enabled` from closure binding

The opening guard `$this->pro_enabled` looks like a syntax error in a top-level closure but is intentional: `bfcm-pointer.php` is `include_once`d **inside `Bootstrap::__construct()`** ([line 134](../../includes/Classes/Bootstrap.php#L134)), so `$this` refers to the Bootstrap instance. If you copy this pattern, the new file must also be included from a method context — including from `essential_adons_elementor.php` directly would break this reference.

### Pointer priority option not always cleaned up

The `_wpdeveloper_plugin_pointer_priority` option is set when the pointer renders but only deleted on dismissal. If the user navigates away without dismissing and the campaign deadline passes, the option lingers in the database forever — harmless but worth knowing for cleanup audits.

### `$this->pro_enabled` is set elsewhere (Bootstrap)

Whatever determines `$this->pro_enabled` lives in Bootstrap or a trait — the pointer file just reads it. If Pro detection logic changes (e.g. a new way to detect Pro plugin), the pointer's bypass behaviour follows automatically. But if Bootstrap changes how it sets the property, the pointer can silently start showing for Pro users.

### `WPDeveloper_Notice` upsale auto-suppress edge case

If the upsale `condition.by = 'class'` and the sister plugin's class doesn't load until later in the page lifecycle, the upsale may show on this pageview (because the class doesn't exist yet at `init` time when scheduling fires). Refresh the page and it suppresses correctly. Race condition, low-impact.

### `vendor/priyomukul/wp-notice` is a different system

The composer dependency at `vendor/priyomukul/wp-notice/` is a separate admin-notice library used by `priyomukul/wp-notice` for cache and debug notices. It hooks `admin_notices` directly with its own logic. It is **not** related to the `WPDeveloper_Notice` class in this codebase, despite the similar name. Don't confuse them.

## Debugging Guide

### Pointer not showing when expected

1. Confirm `bfcm-pointer.php` is included — check `Bootstrap.php:134` for the `include_once` line.
2. Confirm Pro plugin is not active — `$this->pro_enabled` would bypass.
3. Check the deadline — `time() > strtotime('09:59:59pm 4th December, 2025')` — current time past deadline = bypass.
4. Check the page — only dashboard or EA settings page render the pointer.
5. Check the dismiss transient — `SELECT * FROM wp_options WHERE option_name = '_transient_eael_bfcm25_pointer_dismiss'`.
6. Check the priority option — `SELECT * FROM wp_options WHERE option_name = '_wpdeveloper_plugin_pointer_priority'`. If another plugin claimed priority 1, EA's pointer waits.
7. Inspect the page source — search for the inline `<script>` containing `eael_bfcm25` or the pointer's heading text.

### Pointer showing when it shouldn't

1. Did the dismiss transient expire? It's only 30 days. After that, the pointer can re-show until the deadline passes.
2. Did `_wpdeveloper_plugin_pointer_priority` get reset? Dismissal deletes it; subsequent admin pageviews can re-claim and re-show.
3. Cache plugin issue — admin pages aren't usually cached, but some hosts cache WP admin assets including inline scripts.

### `WPDeveloper_Notice` hypothetical: notice not showing

1. Confirm the class is instantiated — search the codebase for `new WPDeveloper_Notice`. **In Lite today, the answer is: nothing instantiates it.**
2. Confirm `init()` was called on the instance.
3. Check the notice has `message()` set — `is_ok()` returns false for missing keys.
4. Check `wpdeveloper_plugins_data` option — does `notice_will_show[<notice>]` exist with a timestamp ≤ now?
5. Check user meta `wpdeveloper_notices_seen` — has this user already dismissed for this version?
6. Check user meta `<plugin_name>_<notice>` — legacy dismissal flag.
7. For upsale specifically: check the `condition` — sister plugin already installed?

### Dismissal not persisting

1. For BFCM: check the transient option after dismiss — `SELECT * FROM wp_options WHERE option_name LIKE '_transient_eael_bfcm25_%'`.
2. For WPDeveloper_Notice: check user meta — `SELECT * FROM wp_usermeta WHERE user_id = <id> AND meta_key = 'wpdeveloper_notices_seen'`.
3. AJAX handler nonce — for WPDeveloper_Notice's WP "X" button, nonce action is `'wpdeveloper_notice_dissmiss'`. If JS is sending wrong nonce, the AJAX returns `'failed'`.

### Notice appears on every admin page (annoying users)

The `eael_admin_notices` proxy in `Helper.php:600` dispatches once per admin page request. If a notice shows everywhere when it should only show on EA pages, the issue is hooking `admin_notices` directly without checking the screen — should use `get_current_screen()` to restrict.

## Worked Example — BFCM 2025 Campaign Lifecycle

Trace a single user's interaction with the active campaign:

1. **Day 1 (Nov 1, 2025):** User installs EA Lite, opens dashboard. `bfcm-pointer.php` runs.
   - Pro check: not Pro → continue.
   - Time check: Nov 1 < Dec 4 → continue.
   - Page check: `index.php` (dashboard) → continue.
   - Dismiss transient: not set → continue.
   - Priority claim: option empty → set to 1.
   - Pointer renders pointing at Essential Addons menu item with "Save $120" CTA.
2. **User clicks the close X.** Pointer's close callback fires `jQuery.post(ajaxurl, {pointer:'eael', action:'dismiss-wp-pointer'})`.
3. **Server-side `admin_init` closure** processes the POST. Sets `eael_bfcm25_pointer_dismiss` transient with 30-day TTL. Deletes `_wpdeveloper_plugin_pointer_priority`.
4. **User refreshes dashboard.** Closure runs again. Dismiss transient is set → bail. No pointer.
5. **30 days later (Dec 1, 2025):** transient expires. User opens dashboard. Pointer shows again (3 days before deadline).
6. **User clicks the CTA "Save $120".** Browser navigates to `https://essential-addons.com/bfcm-wp-admin-pointer`. Pointer is **not auto-dismissed** by the click — only the X button dismisses. Next admin pageview shows it again.
7. **Dec 4, 2025 22:00:00:** deadline passes. Closure now hits the time guard and bails on every admin pageview. The pointer never shows again. The transient and priority option remain in the database (cleanup deferred indefinitely).
8. **In subsequent versions:** developer either deletes `bfcm-pointer.php` for the next release or replaces its contents with a new campaign. The dead code is harmless because the time guard ensures it never runs again.

## Architecture Decisions

### Two parallel systems, only one active

- **Context:** `WPDeveloper_Notice` is a generic framework shared across WPDeveloper plugins (Templately, Essential Blocks). When BFCM 2025 came around, the team needed something simpler than the full `WPDeveloper_Notice` ceremony — just a pointer banner with a deadline.
- **Decision:** Ship `bfcm-pointer.php` as a one-off implementation alongside the dormant `WPDeveloper_Notice` class. Don't deactivate the class (it might be re-activated later); don't force every campaign through the class (it's overkill for time-bound pointers).
- **Alternatives rejected:** Activate `WPDeveloper_Notice` and add a new "pointer" notice type (significant refactor of a stable class); delete `WPDeveloper_Notice` entirely (loses the infrastructure for future lifecycle notices).
- **Consequences:** Engineers encountering the codebase first see `WPDeveloper_Notice` (autoloaded, well-named) and assume it's the path. Documenting both clearly is the mitigation.

### Hardcoded copy, not remote-fetched

- **Context:** Some plugins fetch campaign copy from a remote server (`wpdeveloper.com`) so marketing can iterate without releasing a plugin update.
- **Decision:** EA Lite hardcodes campaign copy in PHP. No remote calls.
- **Alternatives rejected:** Remote fetch (privacy concerns, additional HTTP request on every admin page, fallback complexity, GDPR audit burden); WP transient cache of remote copy (still requires the remote, just batched).
- **Consequences:** Marketing changes require a plugin release. The trade-off is no third-party data exposure and no admin-page latency. For BFCM-style campaigns where copy is fixed for a few weeks, the trade-off is correct.

### Time-gated auto-disable

- **Context:** Old campaigns (e.g. BFCM 2024) shouldn't show in 2025. Manually deleting old campaign code on each release is error-prone.
- **Decision:** Each campaign's PHP file includes its own `time() > strtotime(deadline)` guard. After the deadline, the file becomes dead code that bails on every invocation.
- **Alternatives rejected:** Track active campaigns in an option (admin setup burden); branch in shared code on date (one file per campaign is cleaner); forget about it (BFCM 2024 still showing on dashboards in 2025 = embarrassing).
- **Consequences:** Old campaign files remain in the codebase until manually deleted (cosmetic, harmless). A "purge dead campaigns" cleanup is a routine release-prep task.

### Cross-plugin pointer priority coordination

- **Context:** Templately + Essential Blocks + EA Lite + others may all run BFCM pointers. Showing all of them simultaneously on dashboard would be annoying.
- **Decision:** First-to-claim-priority-1 owns the dashboard pointer; others wait their turn (next admin pageview after dismissal).
- **Alternatives rejected:** No coordination (annoying); fixed plugin order (arbitrary); AJAX rotation (complexity).
- **Consequences:** A user with all plugins installed sees one pointer at a time. Dismissing one frees the priority for the next. Acceptable UX trade-off.

### Per-version dismissal in `WPDeveloper_Notice`

- **Context:** "What's new in 6.6.3?" notice should re-show after upgrade to 6.6.4 if there's a "What's new in 6.6.4?" notice scheduled.
- **Decision:** Notice id includes plugin version (`wpdeveloper_notice_6_6_3`). Dismissal is keyed on this id, so a new version's notices look like fresh notices.
- **Alternatives rejected:** Per-notice id without version (dismissals carry forever — users miss new "what's new" content); per-major-version (complicates semver semantics).
- **Consequences:** Lifecycle notices feel fresh on each plugin update. Some users perceive this as "EA keeps spamming me" if every release ships a new update notice — moderate the frequency.

### `WPDeveloper_Notice` autoloaded but not instantiated

- **Context:** The class file is in PSR-4 path, gets autoloaded on first reference (which never happens), but the file's mere presence costs nothing meaningful at runtime.
- **Decision:** Leave it. Reactivation cost is just one `new WPDeveloper_Notice(...)` line.
- **Alternatives rejected:** Move to a `removed/` folder (signals intent to delete; might get pruned in a release we don't want); delete (loses ~4 hours of activation work whenever the next lifecycle notice is needed).
- **Consequences:** Engineers grep the codebase, see the class, reasonably assume it's active, waste time wiring against it. Documented above as the top pitfall.

## Known Limitations

- **`WPDeveloper_Notice` instantiated nowhere.** The class works (it's used by sister plugins), but in Lite it's dead infrastructure. Documented context only.
- **No remote-config.** Campaign copy changes require a plugin release.
- **No A/B testing.** Single hardcoded copy per campaign; no variation tracking.
- **Pointer priority option leaks.** Set on first show, deleted only on dismissal. Survives campaign deadline as residual data.
- **Dismiss transient is per-browser.** Dismissing on Chrome doesn't dismiss on Firefox for the same WP user (transients are server-side but the dismiss action requires interaction; if a user uses two browsers, they'll see two dismisses).
- **No per-user-role gating in bfcm-pointer.** Shows for any admin who reaches dashboard or EA settings page — even editors / authors who can see those screens. (Pro auto-bypass is the only role-ish gate.)
- **No analytics on pointer interactions.** No dispatched event when shown, dismissed, or clicked. Hard to measure effectiveness.
- **`WPDeveloper_Notice` legacy migration was one-shot for EA 3.7.2.** Anyone updating from older versions skips the migration and may have stale user meta — low impact since modern installs are far past 3.7.2.
- **`vendor/priyomukul/wp-notice` package is undocumented here.** Separate library, separate concerns (cache + debug notices), out of scope for this doc.

## Cross-References

- **Architecture:** [`./README.md`](README.md) — system map; admin notices fire during the page render phase.
- **Architecture:** [`./asset-loading.md`](asset-loading.md) — `wp_enqueue_scripts` priority 100 fires before admin notices; relevant if a notice depends on a localized object from EA's own enqueue.
- **Architecture:** [`./editor-data-flow.md`](editor-data-flow.md) — `eael_admin_notices` is part of the same render-time hook pattern.
- **Skills:** [`new-widget`](../../.claude/skills/new-widget/SKILL.md) — when adding a new widget, no new admin notice is typically needed; this doc clarifies why.
- **Skills:** [`debug-widget`](../../.claude/skills/debug-widget/SKILL.md) — when an admin-area widget has a setup notice, debugging follows the asset / render path; admin-notices are separate from widget render.
- **Rules:** [`php-standards.md`](../../.claude/rules/php-standards.md) — security and i18n conventions; campaigns should still wrap copy in `__()` for translation even when hardcoded.
- **Issue:** [#806](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/806) — the request that drove this doc.
