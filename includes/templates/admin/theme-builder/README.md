# EA Theme Builder — admin app

React UI for the Theme Builder dashboard modals (create template, display
conditions). Built with Vite into `dist/theme-builder.min.js` + `.min.css`,
which `Admin::enqueue_assets()` enqueues.

The templates list itself stays a `WP_List_Table` on the PHP side — it is native
WordPress admin chrome (bulk actions, screen options, Quick Edit) and this app
only takes over the custom, stateful parts.

## Development

```bash
cd includes/templates/admin/theme-builder
npm install     # once
npm run dev     # watch: rebuilds dist/ on every save (~300ms)
```

Leave `npm run dev` running and refresh the Theme Builder screen after each
save. This is a rebuild-on-save watcher, not hot module replacement — the
bundle is enqueued by WordPress, not served by a Vite dev server.

`Admin::asset_version()` versions the enqueued files by `filemtime()` on local
and development installs (and whenever `WP_DEBUG` / `SCRIPT_DEBUG` is on), so a
rebuild busts the browser cache on a normal refresh. Production keeps
`EAEL_PLUGIN_VERSION`. Override with the
`eael/theme_builder/version_assets_by_mtime` filter.

```bash
npm run build   # one-off production build
npm run lint    # eslint, expected to pass clean
```

`dist/` is committed — it is what ships. `src/`, `index.html` and the build
config are excluded by `.distignore`.

Data comes from `window.eaelThemeBuilder`, localized in
`includes/Theme_Builder/Admin/Admin.php`.
