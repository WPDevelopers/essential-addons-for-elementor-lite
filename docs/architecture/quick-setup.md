# Quick Setup Wizard

The React-based onboarding flow that fires once on first activation, walks the user through enabling individual elements, optionally opting in to anonymous usage tracking, and recommending sister plugins (Templately, Essential Blocks). Powers the admin page at `admin.php?page=eael-setup-wizard`.

This doc answers the five sub-questions in [issue #807](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/807):

1. ✅ **`docs/architecture/quick-setup.md` written** — this file.
2. ✅ **React source location documented** — present at [`includes/templates/admin/quick-setup/src/`](../../includes/templates/admin/quick-setup/src/), built with Vite. Not a remote repo. Full path map below.
3. ✅ **Build instructions for the React bundle** — see § Configuration & Extension Points → Building the React Bundle.
4. ✅ **Step-by-step "how to add a new wizard step"** — see § Configuration & Extension Points → Adding a New Wizard Step.
5. ✅ **Cross-link to consent + WPInsights** — see § Cross-References. (Note: a dedicated `docs/architecture/consent-and-tracking.md` does not yet exist; this doc points to the source-of-truth class until that gap is filled.)

## Overview

The wizard is a single-page React app rendered into an empty `<section id="eael-onboard--wrapper">`. PHP serialises configuration into a `localize.eael_quick_setup_data` JS variable on script enqueue, and the React app reads it on mount. User actions (enable/disable elements, opt in to tracking, install/activate sister plugins) post back to PHP via three AJAX endpoints.

Lifecycle is option-driven via `eael_setup_wizard`:

| Option value | State | Set by | Triggers |
| ------------ | ----- | ------ | -------- |
| (missing) | First activation, before `Core::enable_setup_wizard` runs | — | Nothing yet |
| `'redirect'` | First activation completed, redirect pending | [`Core::enable_setup_wizard`](../../includes/Traits/Core.php#L164) on activation when both `eael_version` and `eael_setup_wizard` are absent | `wp_loaded` triggers `WPDeveloper_Setup_Wizard::redirect()` |
| `'init'` | User has been redirected to wizard page; class will instantiate on subsequent admin requests | [`WPDeveloper_Setup_Wizard::redirect()`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L882) before `wp_safe_redirect` | `wp_loaded` instantiates `new WPDeveloper_Setup_Wizard()` so admin hooks register |
| `'complete'` | Wizard finished | [`save_setup_wizard_data()`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L475) on final form submit | Class no longer instantiates — wizard never re-shows |

Once `'complete'` is set, the only way to re-trigger the wizard is to delete the option from the database. There is no admin UI to "reset and re-run".

## Components

| Path | Lines | Role |
| ---- | ----- | ---- |
| [`essential_adons_elementor.php`](../../essential_adons_elementor.php#L106) | 106-115 | The `wp_loaded` dispatcher that reads `eael_setup_wizard` option and triggers redirect or instantiation |
| [`includes/Traits/Core.php`](../../includes/Traits/Core.php#L164) | 164-169 | `enable_setup_wizard()` — sets the option to `'redirect'` on first activation |
| [`includes/Classes/WPDeveloper_Setup_Wizard.php`](../../includes/Classes/WPDeveloper_Setup_Wizard.php) | 937 | The PHP handler — admin menu, script enqueue, three AJAX endpoints, all data builders, lifecycle option transitions |
| [`includes/templates/admin/quick-setup/`](../../includes/templates/admin/quick-setup/) | folder | React app source + Vite config + built dist |
| [`includes/templates/admin/quick-setup/package.json`](../../includes/templates/admin/quick-setup/package.json) | 28 | npm scripts and React + Vite dependencies (separate from the plugin's root `package.json`) |
| [`includes/templates/admin/quick-setup/vite.config.js`](../../includes/templates/admin/quick-setup/vite.config.js) | 16 | Vite + `@vitejs/plugin-react`; output names locked to `quick-setup.min.js` and `quick-setup.min.css` |
| [`includes/templates/admin/quick-setup/src/main.jsx`](../../includes/templates/admin/quick-setup/src/main.jsx) | 9 | React entry point — `ReactDOM.createRoot(document.getElementById("eael-onboard--wrapper")).render(<App />)` |
| [`includes/templates/admin/quick-setup/src/components/App.jsx`](../../includes/templates/admin/quick-setup/src/components/App.jsx) | 335 | Root component — state machine, tab navigation, form submission orchestration, AJAX calls |
| `includes/templates/admin/quick-setup/src/components/MenuItems.jsx` | — | Top tab navigation (Getting Started / Configuration / Elements / Go PRO / Plugins / Integrations) |
| `includes/templates/admin/quick-setup/src/components/GettingStartedContent.jsx` | — | Tracking opt-in screen (only shown when not already opted in) |
| `includes/templates/admin/quick-setup/src/components/ConfigurationContent.jsx` | — | Preference selector (basic / advance / custom) |
| `includes/templates/admin/quick-setup/src/components/ElementsContent.jsx` | — | Per-element on/off checkboxes grouped by category |
| `includes/templates/admin/quick-setup/src/components/GoProContent.jsx` | — | Hardcoded list of Pro features as marketing slide |
| `includes/templates/admin/quick-setup/src/components/PluginsPromo.jsx` + `PluginPromoItem.jsx` | — | Conditional sister-plugin recommendations |
| `includes/templates/admin/quick-setup/src/components/IntegrationContent.jsx` | — | Templately + Essential Blocks install/activate switches |
| `includes/templates/admin/quick-setup/src/components/ModalContent.jsx` | — | Privacy / data-usage modal launched from Getting Started |
| `includes/templates/admin/quick-setup/src/utils/pluginPromoUtils.js` | — | Helpers for the plugins promo panel |
| `includes/templates/admin/quick-setup/dist/quick-setup.min.js` | built | The bundle that PHP enqueues |
| [`assets/admin/css/quick-setup.css`](../../assets/admin/css/quick-setup.css) | — | Wizard stylesheet (separate from the React-bundle CSS) |
| [`assets/admin/vendor/sweetalert2/`](../../assets/admin/vendor/sweetalert2/) | vendor | SweetAlert2 used by the wizard for confirmation dialogs |
| [`assets/admin/images/quick-setup/`](../../assets/admin/images/quick-setup/) | images | All wizard imagery (success.gif, ea-new.png, youtube-promo.png, Pro feature icons) |
| Storage option `eael_setup_wizard` | `wp_options` | Lifecycle state (`redirect` / `init` / `complete`) |
| Storage option `eael_save_settings` | `wp_options` | Persisted element on/off map |
| Storage option `wpins_allow_tracking` | `wp_options` | Per-plugin tracking opt-in flag (read by `get_is_tracking_allowed`) |

## Architecture Diagram

```text
╔══════════════════════════════════════════════════════════════════╗
║ ACTIVATION PHASE (one-time)                                      ║
║                                                                  ║
║   User activates EA Lite                                         ║
║       │                                                          ║
║       ▼                                                          ║
║   Core::enable_setup_wizard() runs                               ║
║       │ (Hooked from Bootstrap traits)                           ║
║       │                                                          ║
║       ▼                                                          ║
║   if ( ! eael_version && ! eael_setup_wizard ):                  ║
║       update_option('eael_setup_wizard', 'redirect')             ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼ next admin page request
╔══════════════════════════════════════════════════════════════════╗
║ REDIRECT PHASE (one-time)                                        ║
║                                                                  ║
║   wp_loaded fires                                                ║
║       │                                                          ║
║       ▼                                                          ║
║   essential_adons_elementor.php:106 reads eael_setup_wizard      ║
║       │                                                          ║
║       ▼ value is 'redirect'                                      ║
║   WPDeveloper_Setup_Wizard::redirect() static method:            ║
║       update_option('eael_setup_wizard', 'init')                 ║
║       wp_safe_redirect(admin_url('admin.php?page=…wizard'))      ║
╚══════════════════════════════════════════════════════════════════╝
                                │
                                ▼ user lands on wizard page
╔══════════════════════════════════════════════════════════════════╗
║ INIT PHASE (every admin request until complete)                  ║
║                                                                  ║
║   wp_loaded fires again on subsequent requests                   ║
║       │                                                          ║
║       ▼ value is 'init'                                          ║
║   new WPDeveloper_Setup_Wizard() constructed                     ║
║       │  registers admin hooks:                                  ║
║       │   - admin_enqueue_scripts → setup_wizard_scripts         ║
║       │   - admin_menu → adds eael-setup-wizard submenu          ║
║       │   - wp_ajax_save_setup_wizard_data                       ║
║       │   - wp_ajax_enable_wpins_process                         ║
║       │   - wp_ajax_save_eael_elements_data                      ║
║       │   - in_admin_header (priority 1000) → remove_notice      ║
║       │     suppresses other admin notices on the wizard page    ║
║       ▼                                                          ║
║   When the wizard page is rendered:                              ║
║   render_wizard() outputs an empty                               ║
║     <section id="eael-onboard--wrapper">                         ║
║       │                                                          ║
║       ▼                                                          ║
║   setup_wizard_scripts() enqueues:                               ║
║     - quick-setup.css (handcrafted admin styles)                 ║
║     - icons/style.css (icon font)                                ║
║     - SweetAlert2 (vendor)                                       ║
║     - quick-setup.min.js (the React bundle)                      ║
║   wp_localize_script attaches the global `localize` JS object:   ║
║     {                                                            ║
║       ajaxurl,                                                   ║
║       nonce: 'essential-addons-elementor',                       ║
║       success_image: <URL>,                                      ║
║       eael_quick_setup_data: { … 8 keys … }                      ║
║     }                                                            ║
║       │                                                          ║
║       ▼                                                          ║
║   React boots: main.jsx → ReactDOM.createRoot(…).render(<App/>)  ║
║   App reads localize.eael_quick_setup_data, sets initial state,  ║
║   renders MenuItems + the active tab's content component         ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ USER INTERACTION PHASE                                           ║
║                                                                  ║
║   Tab clicks → setActiveTab() — pure client state, no AJAX       ║
║                                                                  ║
║   Element checkbox toggles → setCheckedElements() — client only  ║
║   (final list saved on completion)                               ║
║                                                                  ║
║   "I want updates" toggle in Getting Started:                    ║
║       → fetch POST admin-ajax.php                                ║
║         action=enable_wpins_process,                             ║
║         security=<nonce>,                                        ║
║         fields=<form-encoded form data>                          ║
║       → server: enable_wpins_process()                           ║
║         security triad → wpins_process()                         ║
║         (instantiates Plugin_Usage_Tracker, set_is_tracking_     ║
║         allowed(true), do_tracking(true))                        ║
║       → wp_send_json_success                                     ║
║                                                                  ║
║   Templately / Essential Blocks install/activate toggles:        ║
║       → fetch POST admin-ajax.php                                ║
║         action=wpdeveloper_install_plugin OR                     ║
║         wpdeveloper_activate_plugin OR                           ║
║         wpdeveloper_deactivate_plugin                            ║
║       → handlers in WPDeveloper_Plugin_Installer (separate       ║
║         from this wizard but reused by it)                       ║
║                                                                  ║
║   Final "Save & Continue" on the elements step:                  ║
║       → fetch POST admin-ajax.php                                ║
║         action=save_setup_wizard_data,                           ║
║         security=<nonce>,                                        ║
║         fields=<form-encoded element checkbox state>             ║
║       → server: save_setup_wizard_data()                         ║
║         security triad                                           ║
║         if eael_user_email_address checked → wpins_process()     ║
║         update_option('eael_setup_wizard', 'complete')           ║
║         save_element_list($fields):                              ║
║           build $save_element from $GLOBALS['eael_config']       ║
║           merge with get_dummy_widget()                          ║
║           update_option('eael_save_settings', $save_element)     ║
║         wp_send_json_success({ redirect_url: '…page=eael-…' })   ║
║       → React redirects browser to settings page                 ║
╚══════════════════════════════════════════════════════════════════╝

╔══════════════════════════════════════════════════════════════════╗
║ COMPLETION (terminal)                                            ║
║                                                                  ║
║   eael_setup_wizard option = 'complete'                          ║
║   wp_loaded dispatcher no longer instantiates the class          ║
║   admin menu submenu does not register                           ║
║   AJAX endpoints do not register                                 ║
║   Wizard page is unreachable until the option is manually deleted║
╚══════════════════════════════════════════════════════════════════╝
```

## Hook Timing

| Hook | Owner | When | Handler | Purpose |
| ---- | ----- | ---- | ------- | ------- |
| Plugin activation (one-shot trigger from Bootstrap traits) | EA Core | First-time activation | `Core::enable_setup_wizard` | Sets `eael_setup_wizard => 'redirect'` |
| `wp_loaded` (priority 10) | EA entry file | Every request after WP fully loaded | inline closure in `essential_adons_elementor.php:106` | Reads `eael_setup_wizard` and dispatches redirect or `new` |
| `admin_enqueue_scripts` | WordPress core | Admin page being rendered | `setup_wizard_scripts($hook)` | Enqueues stylesheets, SweetAlert2, the React bundle, and `wp_localize_script` |
| `admin_menu` | WordPress core | Admin menu being built | `admin_menu()` | Adds the `eael-setup-wizard` hidden submenu page |
| `in_admin_header` (priority 1000) | WordPress core | Admin header rendering | `remove_notice()` | When `?page=eael-setup-wizard`, suppresses other plugins' admin notices for a clean wizard surface |
| `wp_ajax_save_setup_wizard_data` | WordPress core | AJAX | `save_setup_wizard_data()` | Final form submit — saves elements, marks complete, optionally fires WPInsights |
| `wp_ajax_enable_wpins_process` | WordPress core | AJAX | `enable_wpins_process()` | Mid-flow opt-in for WPInsights without completing the wizard |
| `wp_ajax_save_eael_elements_data` | WordPress core | AJAX | `save_eael_elements_data()` | Persist element on/off list (lighter-weight save than `save_setup_wizard_data`) |

## Data Flow

End-to-end first-install lifecycle:

1. **User activates EA Lite for the first time.** WordPress fires plugin activation; via Bootstrap traits, `Core::enable_setup_wizard()` runs. Both `eael_version` and `eael_setup_wizard` are absent → option set to `'redirect'`.
2. **User navigates to any admin page.** `wp_loaded` fires. Dispatcher in [`essential_adons_elementor.php:106`](../../essential_adons_elementor.php#L106) reads `eael_setup_wizard`, sees `'redirect'`.
3. **`WPDeveloper_Setup_Wizard::redirect()` runs.** Sets option to `'init'` and `wp_safe_redirect(admin_url('admin.php?page=eael-setup-wizard'))`. Browser follows the redirect.
4. **Wizard page request reaches the server.** `wp_loaded` fires again. Dispatcher reads `'init'` → instantiates `new WPDeveloper_Setup_Wizard()`. Constructor registers all admin hooks (enqueue, menu, AJAX, notice suppression).
5. **WordPress dispatches `admin_menu`.** `admin_menu()` registers a submenu page using `add_submenu_page` with parent `'admin.php'`. Slug is `eael-setup-wizard`, capability `manage_options`, render callback `render_wizard`.
6. **`admin_enqueue_scripts` fires** with `$hook === 'admin_page_eael-setup-wizard'`. `setup_wizard_scripts` runs:
   - `wp_enqueue_style('essential_addons_elementor-setup-wizard-css', …quick-setup.css)`
   - `wp_enqueue_style('essential_addons_elementor-setup-wizard-fonts', …icons/style.css)`
   - `wp_enqueue_style('sweetalert2-css', …)`
   - `wp_enqueue_script('sweetalert2-js', …)` and `'sweetalert2-core-js'`
   - `wp_enqueue_script('essential_addons_elementor-setup-wizard-react-js', …quick-setup.min.js)`
   - `wp_localize_script` with the JS variable name `'localize'` (note: not namespaced — collides with anything else that registers a global named `localize`) carrying:
     - `ajaxurl` — admin AJAX URL
     - `nonce` — `wp_create_nonce('essential-addons-elementor')`
     - `success_image` — completion GIF URL
     - `eael_quick_setup_data` — see § Configuration & Extension Points → Localized Data Shape
7. **WordPress emits the page HTML.** `render_wizard()` outputs `<section id="eael-onboard--wrapper"></section>` and nothing else. The empty section is the React mount point.
8. **Browser executes the React bundle.** `main.jsx` finds `#eael-onboard--wrapper` and mounts `<App />`.
9. **`<App />` reads `localize.eael_quick_setup_data`.** Initial active tab is `'getting-started'` if `is_tracking_allowed` is false, otherwise `'configuration'`. Initial `checkedElements` is computed by walking `eael_quick_setup_data.elements_content.elements_list` and marking every `preferences === 'basic'` element checked. Plugins promo panel is shown only if `Object.keys(plugins_content.plugins).length > 0`.
10. **User progresses through tabs.** Tab changes are pure React state. Element checkbox toggles update `checkedElements`. The form is one DOM element wrapping all tabs (the inactive ones use `eael-d-none` to hide).
11. **If user toggles "I want product updates":** React fires `saveWPIns()` → `fetch POST` to `admin-ajax.php` with `action=enable_wpins_process`. Handler runs `wpins_process()` which instantiates `Plugin_Usage_Tracker`, calls `set_is_tracking_allowed(true)` and `do_tracking(true)` → tracking option `wpins_allow_tracking[essential_adons_elementor] = 1`.
12. **If user toggles a sister plugin in Integrations:** React fires `handleIntegrationSwitch` → POSTs to a different action (`wpdeveloper_install_plugin` / `wpdeveloper_activate_plugin` / `wpdeveloper_deactivate_plugin`) registered by `WPDeveloper_Plugin_Installer` (separate class, reused here). Updates DOM label to "Processing…", then to the post-action label.
13. **User clicks final "Save & Continue".** React serialises the entire `<form>` via `FormData` + `URLSearchParams` and POSTs `action=save_setup_wizard_data` to admin-ajax.php.
14. **`save_setup_wizard_data()` server-side:**
    - `check_ajax_referer('essential-addons-elementor', 'security')` — security gate
    - `current_user_can('manage_options')` — capability gate
    - `wp_parse_str($_POST['fields'], $fields)` — deserialise form data
    - If `$fields['eael_user_email_address']` is truthy → call `wpins_process()` (a second time if the user opted in mid-flow; idempotent — `do_tracking(true)` either way)
    - `update_option('eael_setup_wizard', 'complete')` — terminal state
    - `save_element_list($fields)` — see element storage logic below
    - `wp_send_json_success(['redirect_url' => admin_url('admin.php?page=eael-settings')])`
15. **React redirects browser** to the EA settings page using the URL from the JSON response. Wizard never appears again until `eael_setup_wizard` option is manually deleted.

### Element storage logic

`save_element_list($fields)` builds `$save_element` from `$GLOBALS['eael_config']['elements']` (the full element registry from `config.php`) — every key gets value `1` if checked in the form, else empty string. Then merges with `get_dummy_widget()` which is a hardcoded list of widgets that should always be enabled (EmbedPress, WooCommerce Review, Career Page, Crowdfundly variants, Better Payment).

The result is stored as `eael_save_settings` option. This is the same option Asset_Builder reads via `get_settings()` in [`asset-loading.md § Components`](asset-loading.md) — meaning the wizard's element selections directly affect which widgets are loaded plugin-wide.

## Configuration & Extension Points

### Localized Data Shape (`localize.eael_quick_setup_data`)

PHP serialises this dict into the JS global `localize.eael_quick_setup_data`. React reads it on mount.

```javascript
{
    is_quick_setup: 1,                       // marker for React to detect we're inside the wizard
    menu_items: {
        templately_status: <bool>,           // is Templately plugin active?
        eblocks_status: <bool>,              // is Essential Blocks plugin active?
        wizard_column: 'four' | 'five',      // grid layout — 5 columns when at least one sister plugin is missing
        items: {
            started: 'Getting Started',
            configuration: 'Configuration',
            elements: 'Elements',
            go_pro: 'Go PRO',
            pluginspromo: 'Plugins',
            integrations: 'Integrations',
        },
        templately_local_plugin_data: <plugin info from get_plugins() or false>,
        eblocks_local_plugin_data: <same>,
        ea_pro_local_plugin_data: <same>,
    },
    getting_started_content: {
        youtube_promo_src: <URL>,
        is_tracking_allowed: <bool>,         // already opted in? skip Getting Started tab
    },
    configuration_content: {
        ea_logo_src: <URL>,
    },
    elements_content: {
        elements_list: {
            'content-elements': { title: '...', elements: [{ key, title, preferences }] },
            'creative-elements': { ... },
            'form-elements': { ... },
            … other categories
        },
    },
    go_pro_content: {
        feature_items: [{ title, link, img_src }, …],
    },
    plugins_content: {
        plugins: { … },                      // sister plugins to recommend
    },
    integrations_content: {
        … integrations metadata …
    },
    modal_content: {
        … privacy modal copy …
    },
}
```

### AJAX Endpoints

All three endpoints share the same security model: `check_ajax_referer('essential-addons-elementor', 'security')` + `current_user_can('manage_options')` + `wp_parse_str($_POST['fields'], $fields)`.

| Action | Method | Purpose | Response |
| ------ | ------ | ------- | -------- |
| `save_setup_wizard_data` | `save_setup_wizard_data()` | Final wizard submit. Saves elements, marks complete, optionally fires WPInsights tracking. | `wp_send_json_success(['redirect_url' => …])` on success |
| `enable_wpins_process` | `enable_wpins_process()` | Standalone WPInsights opt-in. Runs `wpins_process()` and returns. Used when user toggles tracking mid-flow without completing the wizard. | `wp_send_json_success()` |
| `save_eael_elements_data` | `save_eael_elements_data()` | Persist element on/off list without changing wizard state. (Defined for completeness; the active React flow uses `save_setup_wizard_data` for the final save.) | `wp_send_json_success()` |

### Storage Options

| Option key | Type | Set by | Read by | Lifetime |
| ---------- | ---- | ------ | ------- | -------- |
| `eael_setup_wizard` | string | Activation, redirect, save | Dispatcher in entry file | Persistent |
| `eael_save_settings` | array | `save_element_list` | `Asset_Builder` (via Library trait `get_settings`) | Persistent |
| `wpins_allow_tracking` | array | `Plugin_Usage_Tracker` | `get_is_tracking_allowed` | Persistent |
| `eael_version` | string | EA bootstrap | `Core::enable_setup_wizard` (used as the "fresh install?" check) | Persistent |

### Building the React Bundle

The React app has its **own `package.json` and Vite config**, separate from the plugin's root webpack pipeline. Run commands inside the wizard directory:

```bash
cd includes/templates/admin/quick-setup/
npm install
npm run build       # Vite production build → dist/quick-setup.min.js + dist/quick-setup.min.css
npm run dev         # Vite watch + preview server
npm run lint        # ESLint with react + react-hooks + react-refresh plugins
```

Vite is configured at [`vite.config.js`](../../includes/templates/admin/quick-setup/vite.config.js) to:

- Use `@vitejs/plugin-react` for JSX + Fast Refresh
- Output `quick-setup.min.js` (entry) and `quick-setup.min.css` (asset) — names locked via `rollupOptions.output.entryFileNames` / `assetFileNames` so PHP's enqueue path stays stable

Dependencies (also at [`package.json`](../../includes/templates/admin/quick-setup/package.json)):

- Runtime: `react@^18.2.0`, `react-dom@^18.2.0`, `@wordpress/i18n@^5.0.0`
- Dev: `vite@^5.2.0`, `@vitejs/plugin-react@^4.2.1`, `eslint` + react plugins

After every React source change, `npm run build` must run before testing — PHP enqueues `dist/quick-setup.min.js`, not the source. The plugin's root `npm run build` does **not** rebuild this React app; it's a separate pipeline.

The built `dist/` folder is committed to the repo (consistent with how `assets/front-end/` is committed for the rest of the plugin), so a fresh checkout works without running this build step. CI can verify the dist is up-to-date by re-running `npm run build` and diffing.

### Adding a New Wizard Step

Recipe to add (for example) a "Welcome Tour" step between Getting Started and Configuration.

1. **Add the menu item** — [`WPDeveloper_Setup_Wizard.php:125`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L125), inside `data_menu_items()`:

   ```php
   $items = [
       'started'       => __( 'Getting Started', 'essential-addons-for-elementor-lite' ),
       'welcome_tour'  => __( 'Welcome Tour', 'essential-addons-for-elementor-lite' ),  // ← NEW
       'configuration' => __( 'Configuration', 'essential-addons-for-elementor-lite' ),
       'elements'      => __( 'Elements', 'essential-addons-for-elementor-lite' ),
       'go_pro'        => __( 'Go PRO', 'essential-addons-for-elementor-lite' ),
       'pluginspromo'  => __( 'Plugins', 'essential-addons-for-elementor-lite' ),
       'integrations'  => __( 'Integrations', 'essential-addons-for-elementor-lite' ),
   ];
   ```

   The key (`welcome_tour`) becomes the `data-next` attribute used by the React tab switcher. Use snake_case to match existing keys.

2. **Add a data builder** — somewhere in `WPDeveloper_Setup_Wizard.php`:

   ```php
   public function data_welcome_tour_content() {
       return [
           'tour_video_src' => esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/tour.mp4' ),
           'tour_steps'     => [ … ],
       ];
   }
   ```

3. **Add the data builder to `eael_quick_setup_data()`** — [line 109-123](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L109):

   ```php
   $eael_quick_setup_data = [
       'is_quick_setup'          => 1,
       'menu_items'              => $this->data_menu_items(),
       'getting_started_content' => $this->data_getting_started_content(),
       'welcome_tour_content'    => $this->data_welcome_tour_content(),  // ← NEW
       'configuration_content'   => $this->data_configuration_content(),
       …
   ];
   ```

4. **Adjust column count if needed.** `data_menu_items()` line 138 sets `wizard_column` to `'four'` or `'five'` based on Templately + Essential Blocks status. If your new step changes the visible column count, update this logic.

5. **Create the React component** — `includes/templates/admin/quick-setup/src/components/WelcomeTourContent.jsx`:

   ```jsx
   import { React } from "react";

   function WelcomeTourContent({ activeTab, handleTabChange }) {
       const data = localize.eael_quick_setup_data.welcome_tour_content;
       return (
           <div>
               {/* render tour video, steps, etc. */}
               <button data-next="configuration" onClick={handleTabChange}>
                   Continue
               </button>
           </div>
       );
   }

   export default WelcomeTourContent;
   ```

6. **Wire it into `App.jsx`** — [`includes/templates/admin/quick-setup/src/components/App.jsx`](../../includes/templates/admin/quick-setup/src/components/App.jsx). Import the new component and add a conditional render block matching the existing pattern:

   ```jsx
   import WelcomeTourContent from "./WelcomeTourContent.jsx";

   // …inside the form, between GettingStarted and Configuration:
   <div className={`eael-setup-content eael-welcome-tour-content ${activeTab === "welcome_tour" ? "" : "eael-d-none"}`}>
       <WelcomeTourContent activeTab={activeTab} handleTabChange={handleTabChange} />
   </div>
   ```

7. **Update the initial active-tab logic** if appropriate. `App.jsx:14` decides the starting tab. If you want users who skipped Getting Started to land on Welcome Tour first instead of Configuration, adjust here.

8. **If your step needs server-side persistence** beyond the existing endpoints, register a new AJAX handler in the constructor following the existing pattern:

   ```php
   add_action( 'wp_ajax_save_welcome_tour_progress', [ $this, 'save_welcome_tour_progress' ] );
   ```

   Implement the handler with the standard security triad: `check_ajax_referer` + `current_user_can` + `wp_parse_str`.

9. **Build and test**:

   ```bash
   cd includes/templates/admin/quick-setup/
   npm run build
   ```

   Then on a clean install, delete the `eael_setup_wizard` option, re-activate the plugin, and walk the wizard. Verify the new step renders, navigation works, and any new server endpoint persists data.

10. **Style.** Add CSS to [`assets/admin/css/quick-setup.css`](../../assets/admin/css/quick-setup.css) following the `.eael-setup-content` + `.eael-welcome-tour-content` BEM-ish pattern.

## Common Pitfalls

### Built `dist/` not regenerated after source change

`npm run build` must run after every `.jsx` edit. The plugin enqueues the built file, not the source. A common bug is "I changed the React file and nothing changed in the browser" — check `git status includes/templates/admin/quick-setup/dist/` to confirm the build ran.

### `localize` JS global is unnamespaced

[`setup_wizard_scripts:73`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L73) calls `wp_localize_script` with the variable name `'localize'` (no prefix). The same name is used by other parts of EA (e.g. `Asset_Builder::load_commnon_asset` localises `eael-general` with `'localize'` too — see [`asset-loading.md`](asset-loading.md)). On the wizard admin page, only the wizard's localize is in scope, so it works in practice. But adding any other plugin code that registers a global `localize` on this page would collide.

### Wizard never re-shows after completion

Once `eael_setup_wizard => 'complete'`, the dispatcher in [`essential_adons_elementor.php:106`](../../essential_adons_elementor.php#L106) takes neither branch and the class never instantiates. The submenu doesn't register, the React bundle doesn't enqueue, and visiting the URL directly returns "You do not have permission to access this page". The only way to re-trigger is `delete_option('eael_setup_wizard')` then re-activate the plugin (or directly hit the redirect path).

### `eael_save_settings` is not the same as wizard data

The wizard's element checkbox state lives in DOM/React on the wizard page. Final save goes to `eael_save_settings` option, which is what Asset_Builder reads. Disabling an element in the wizard prevents that element from showing up in Elementor's panel. Some users assume "disabled in wizard" only affects the wizard and not the live editor — it affects both.

### `get_dummy_widget()` overrides user choices

[Line 924-934](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L924) returns a hardcoded set of widgets that are force-enabled regardless of wizard checkboxes (EmbedPress, woocommerce-review, career-page, three Crowdfundly variants, better-payment). Even if the user un-checks these in the wizard, they'll be enabled. This is likely intentional (these are upsell widgets), but counter-intuitive. Don't try to disable these from the wizard — modify `get_dummy_widget()` if needed.

### `is_tracking_allowed` flips the initial tab silently

`App.jsx:14` chooses `'getting-started'` or `'configuration'` based on whether tracking was already opted in. A user who opted in via another mechanism (e.g. `Plugin_Usage_Tracker` ran from elsewhere) lands on Configuration with no explanation. The Getting Started tab is then unreachable unless they manually navigate.

### `wp_parse_str` slashing on AJAX

Like the rest of the plugin's AJAX handlers (see [`ajax-endpoints.md`](dynamic-data/ajax-endpoints.md)), `wp_parse_str($_POST['fields'])` does not strip slashes. If the wizard form data ever includes user-typed text (it doesn't in the current state, but could in a new step that captures input), the saved value will be slash-prefixed. Apply `wp_unslash` early.

### React state vs server state divergence

The form's final `eael_save_settings` is computed from React's `checkedElements` state at submit time. If the user's submission is interrupted (network failure, browser close), the wizard will re-show on next admin visit — but the user's previous element selections are lost. There is no "draft" save during the wizard.

### Notice suppression spans the whole wizard page

[`remove_notice()`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L50) calls `remove_all_actions('admin_notices')` and `remove_all_actions('all_admin_notices')` at `in_admin_header` priority 1000. This is intentional (clean wizard surface), but it also suppresses **legitimate error notices** like "WordPress is updating" or security plugin warnings. If a third-party plugin's critical notice never shows, the wizard's notice suppression is the cause.

### Cross-plugin React global collisions

The wizard mounts into `#eael-onboard--wrapper` — the id is shared across WPDeveloper plugins (Templately uses similar markup). On a page that hosts both wizards (rare but possible during dev), only one mounts. The first one wins.

## Debugging Guide

### Wizard does not redirect after activation

1. Check the option: `wp_option_value('eael_setup_wizard')` — is it `'redirect'`?
2. If empty: `Core::enable_setup_wizard()` did not run. Check Bootstrap traits for the activation hook wiring.
3. If `'complete'`: wizard already done. Delete option to re-trigger.
4. If `'redirect'` but no actual redirect on next admin page: `wp_loaded` may not be firing for that admin endpoint (XML-RPC, REST). Test with a regular admin page.

### React bundle does not load

1. Inspect the page source for `<script src='…quick-setup.min.js'>`. If absent, `setup_wizard_scripts` did not run — confirm `$hook === 'admin_page_eael-setup-wizard'`.
2. If the script tag is present but the page renders the empty `<section>` and nothing else: open browser console, look for JS errors. Most commonly: the bundle is stale (`npm run build` was not run after a source change).
3. Disable browser cache; some dev environments aggressively cache `quick-setup.min.js` even with versioning.

### AJAX save fails

1. Browser Network tab → confirm the POST is going out with `action`, `security`, `fields` keys.
2. Response status 0 → `wp_ajax_save_setup_wizard_data` is not registered → class did not instantiate → option is not `'init'`.
3. Response 403 / `'failed'` text → nonce mismatch. The nonce is created on enqueue with action `'essential-addons-elementor'` and verified on AJAX with the same. Mismatch means the nonce expired (rare on wizard pages).
4. Response `success: false`, no message → either capability failure or `save_element_list` returned false (no `eael_element` field).
5. Response `success: true` with `redirect_url`, but React doesn't redirect → check `App.jsx` for the response handler; the React code must explicitly `window.location.href = response.data.redirect_url`.

### Tracking opt-in doesn't actually start tracking

1. Confirm `Plugin_Usage_Tracker` class is loadable: `class_exists('\Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker')`.
2. Inspect `wpins_allow_tracking` option after toggle. Should be an array with `essential_adons_elementor => 1`.
3. If tracking ran but no data reached the server, that's WPInsights infrastructure outside the wizard's scope — debug from `Plugin_Usage_Tracker`.

### Element saved but Asset_Builder still loads disabled widget

1. The wizard saves `eael_save_settings`. Asset_Builder reads `get_settings('elements')` (or similar) from a different option key in some legacy paths. Confirm you're checking the right key.
2. The dummy widget list (`get_dummy_widget`) force-enables seven widgets regardless. Confirm the widget you're trying to disable isn't in that list.
3. Cache: per-page asset bundles are cached. Run "Regenerate CSS & Data" or delete `EAEL_ASSET_PATH/eael-*.css` files.

## Worked Example — Adding a "Welcome Survey" step that posts user role

Concrete walkthrough of inserting a new step that asks the user for their role (Designer / Developer / Agency) and persists the answer.

1. **Menu item.** In `data_menu_items()`, add `'welcome_survey' => __('Welcome Survey', '…')` between `started` and `configuration`.

2. **Data builder.** Add to the class:
   ```php
   public function data_welcome_survey_content() {
       return [
           'roles' => [
               'designer'  => __( 'Designer', 'essential-addons-for-elementor-lite' ),
               'developer' => __( 'Developer', 'essential-addons-for-elementor-lite' ),
               'agency'    => __( 'Agency', 'essential-addons-for-elementor-lite' ),
           ],
       ];
   }
   ```

3. **Add to localized data.** In `eael_quick_setup_data()`, add `'welcome_survey_content' => $this->data_welcome_survey_content()`.

4. **AJAX handler.** Constructor: `add_action('wp_ajax_save_user_role', [$this, 'save_user_role']);`. Implement:
   ```php
   public function save_user_role() {
       check_ajax_referer( 'essential-addons-elementor', 'security' );
       if ( ! current_user_can( 'manage_options' ) ) {
           wp_send_json_error();
       }
       $role = isset( $_POST['role'] ) ? sanitize_key( wp_unslash( $_POST['role'] ) ) : '';
       if ( in_array( $role, [ 'designer', 'developer', 'agency' ], true ) ) {
           update_option( 'eael_user_role', $role );
           wp_send_json_success();
       }
       wp_send_json_error();
   }
   ```

5. **React component.** Create `src/components/WelcomeSurveyContent.jsx`:
   ```jsx
   import { React, useState } from "react";

   function WelcomeSurveyContent({ activeTab, handleTabChange }) {
       const roles = localize.eael_quick_setup_data.welcome_survey_content.roles;
       const [selected, setSelected] = useState("");

       const submit = async (e) => {
           e.preventDefault();
           if ( ! selected ) return;
           await fetch( localize.ajaxurl, {
               method: 'POST',
               headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
               body: new URLSearchParams({
                   action: 'save_user_role',
                   security: localize.nonce,
                   role: selected,
               }),
           });
           // advance to next tab
           setActiveTab( 'configuration' );
       };

       return (
           <form onSubmit={submit}>
               {Object.entries(roles).map(([key, label]) => (
                   <label key={key}>
                       <input type="radio" name="role" value={key}
                              checked={selected === key}
                              onChange={(e) => setSelected(e.target.value)} />
                       {label}
                   </label>
               ))}
               <button type="submit">Continue</button>
           </form>
       );
   }
   ```

6. **Wire into App.jsx.** Import + add the conditional render block matching the existing pattern.

7. **Build.** `npm run build` in the wizard directory.

8. **Test.** `delete_option('eael_setup_wizard');` to reset, re-activate, walk through. Verify role saves to `eael_user_role` option after selection.

The whole change touches: 1 PHP method (data builder) + 1 PHP method (AJAX handler) + 1 menu key + 1 React component + ~5 lines in App.jsx + 1 `eael_quick_setup_data` entry. No build pipeline changes.

## Architecture Decisions

### React for the wizard, jQuery + DOM for everything else

- **Context:** A multi-step interactive wizard with conditional rendering, stateful checkboxes across categories, and AJAX-driven plugin install/activate is awkward in jQuery. The rest of EA uses jQuery because Elementor uses jQuery — but the wizard is its own admin page, untethered from Elementor.
- **Decision:** Build the wizard as a self-contained React 18 app with Vite. Boot from an empty mount point.
- **Alternatives rejected:** jQuery + Underscore templates (verbose for state-heavy UI); Vue (no precedent in WPDeveloper plugins); plain JS (state management complexity).
- **Consequences:** A React-Vite stack lives separately from the plugin's webpack pipeline. Two builds to maintain. Devs need to know both. Trade-off accepted — wizard development speed is worth it.

### Vite, not webpack

- **Context:** EA's main asset pipeline is webpack 4. The wizard could have hooked into the same pipeline.
- **Decision:** Use Vite for the wizard's bundle. Separate `package.json`. Output names locked via Rollup config so PHP enqueue path is stable.
- **Alternatives rejected:** Bolt onto root webpack (mixes JSX into the otherwise-vanilla-JS pipeline; complicates build config); use webpack 5 separately (Vite's Rollup-based output is simpler for this scope).
- **Consequences:** Two `npm run build` commands. Documented in this doc. Build artifact (`dist/`) committed to the repo so production deploys don't need to run the wizard build.

### Lifecycle option (`eael_setup_wizard`) instead of just a transient

- **Context:** Need to persist wizard state across the redirect → init → complete sequence. Transients can expire mid-flow.
- **Decision:** Use a regular `wp_options` row with three string values: `'redirect'`, `'init'`, `'complete'`.
- **Alternatives rejected:** Transient (expiry); user meta (per-user, but the wizard is per-site); custom DB table (overkill).
- **Consequences:** The option survives across deactivation/reactivation. To re-trigger the wizard, the option must be explicitly deleted — not a UI-exposed action. This is documented as a Pitfall.

### Single `localize` JS global

- **Context:** WP's `wp_localize_script` requires a JS variable name. Choosing `'localize'` is short and matches what the rest of the plugin uses on frontend.
- **Decision:** Use `'localize'` for the wizard too.
- **Alternatives rejected:** `'eaelQuickSetup'` (doesn't match plugin convention).
- **Consequences:** Cross-page collision risk (documented in Pitfalls). Acceptable because the wizard runs in a single-purpose admin page where no other code registers a `localize` global in practice.

### Element checkboxes save to the same option Asset_Builder reads

- **Context:** Two valid designs: (a) wizard saves to its own option, Asset_Builder reads its existing option separately, sync logic merges; (b) wizard writes directly to Asset_Builder's option.
- **Decision:** (b) — wizard writes to `eael_save_settings` directly. Single source of truth.
- **Alternatives rejected:** Sync logic (drift risk); separate options (confusion about which wins).
- **Consequences:** Wizard checkbox is the same surface as the EA settings page's element toggles — they edit the same data. A user who completes the wizard and then visits settings can re-toggle the same elements. Trade-off accepted.

### `get_dummy_widget()` force-enables some widgets

- **Context:** Seven widgets (EmbedPress, WooCommerce Review, Career Page, three Crowdfundly variants, Better Payment) are upsell stubs. If a user disables them in the wizard, the upsell goes away.
- **Decision:** Force-enable these regardless of wizard checkbox state.
- **Alternatives rejected:** Hide them from the wizard (still loadable but invisible — confusing); honour the user's choice (loses the upsell).
- **Consequences:** Documented in Pitfalls. Worth re-evaluating: users who genuinely don't want these may find them re-enabled silently after running the wizard.

## Known Limitations

- **No "reset wizard" UI.** Once `eael_setup_wizard => 'complete'`, no admin button re-triggers the wizard. Re-running requires direct DB modification.
- **`localize` JS global is unnamespaced.** Collision risk if other admin scripts register the same global on the wizard page.
- **No incremental save.** Mid-flow data is React-only; closing the browser loses progress.
- **Build artifact divergence.** The committed `dist/` may go stale relative to the source. CI does not currently verify the dist matches a fresh `npm run build`.
- **Notice suppression is global on the wizard page.** Critical errors from third-party plugins are also hidden.
- **Wizard React stack is independent.** Cannot use plugin-wide localized data (e.g. the `eael` JS object built by `Asset_Builder`) — the wizard's `localize` is its own world.
- **Sister-plugin install errors are partially silent.** [`App.jsx:204-210`](../../includes/templates/admin/quick-setup/src/components/App.jsx) commented out the error label updates; on failure the user sees an empty state.
- **`save_eael_elements_data` AJAX endpoint exists but the React flow doesn't call it.** Possibly defunct; possibly a hook for future incremental saves.
- **`is_tracking_allowed` interlock can hide Getting Started.** A user who opted in elsewhere lands on Configuration with no way back to Getting Started.

## Cross-References

### Within architecture

- [`./README.md`](README.md) — system map; the wizard is an admin-only flow distinct from the four-phase render lifecycle.
- [`./asset-loading.md`](asset-loading.md) — Asset_Builder reads `eael_save_settings` (the wizard's element-list output).
- [`./admin-notices.md`](admin-notices.md) — the wizard explicitly suppresses admin notices on its page; admin-notices doc explains the broader notice system.
- [`./dynamic-data/ajax-endpoints.md`](dynamic-data/ajax-endpoints.md) — full AJAX inventory; the wizard's three endpoints are admin-only and listed there as well.
- [`./dynamic-data/third-party-integrations.md`](dynamic-data/third-party-integrations.md) — the wizard's plugin install/activate buttons reuse handlers documented there.

### Consent + WPInsights

The wizard's tracking opt-in delegates to `Plugin_Usage_Tracker` ([`includes/Classes/Plugin_Usage_Tracker.php`](../../includes/Classes/Plugin_Usage_Tracker.php), 1,274 lines). Until a dedicated `consent-and-tracking.md` exists in this folder, the source-of-truth references are:

- **Class:** [`Plugin_Usage_Tracker`](../../includes/Classes/Plugin_Usage_Tracker.php) — the WPInsights integration, opt-in storage, deactivation form, periodic data collection
- **Wizard hook:** [`WPDeveloper_Setup_Wizard::wpins_process()`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L900) — instantiates Plugin_Usage_Tracker with `opt_in: true`, `goodbye_form: true`, `item_id: '760e8569757fa16992d8'`
- **Read helper:** [`WPDeveloper_Setup_Wizard::get_is_tracking_allowed()`](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L913) — reads `wpins_allow_tracking` option, returns `intval` of the per-plugin flag
- **Storage:** option `wpins_allow_tracking` — array with per-plugin opt-in flags
- **External:** WPInsights is a third-party tracking SaaS used by WPDeveloper plugins; documenting that service is out of scope for this codebase.

**Gap:** A dedicated `docs/architecture/consent-and-tracking.md` would consolidate Plugin_Usage_Tracker behaviour, the WPInsights data model, opt-out flow, the deactivation form, and GDPR considerations. Filing as a follow-up to this doc.

### Skills + rules

- [`.claude/skills/debug-widget`](../../.claude/skills/debug-widget/SKILL.md) — when an admin-only flow misbehaves, the AJAX trace path applies to the wizard's three endpoints.
- [`.claude/skills/nopriv-ajax-hardening`](../../.claude/skills/nopriv-ajax-hardening/SKILL.md) — the wizard's endpoints are admin-only (no nopriv variants), but the security-triad pattern still applies.
- [`.claude/rules/php-standards.md`](../../.claude/rules/php-standards.md) — security and i18n conventions every wizard handler honours.

### Issue

- [#807](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/807) — the request that drove this doc.
