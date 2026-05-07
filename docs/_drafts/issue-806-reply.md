## 📋 Documentation Delivered

All four acceptance criteria met. Doc landed at [`docs/architecture/admin-notices.md`](../../docs/architecture/admin-notices.md) (592 lines, 12-section structure consistent with the sibling architecture docs added for #804).

---

### 1. ✅ Verified campaign payload source — **hardcoded in PHP**

Full-codebase audit confirms there is **no remote fetch** for any admin notice copy.

**Critical finding while documenting this:** `WPDeveloper_Notice` class (`includes/Classes/WPDeveloper_Notice.php`, 955 lines) **is never instantiated in Lite**. It's dormant infrastructure — autoloaded via PSR-4, but no code calls `new WPDeveloper_Notice(...)`. The class is shared with sister plugins (Templately, Essential Blocks) where it is wired up; Lite chose not to.

The only **active** campaign is `includes/bfcm-pointer.php` (70 lines), included from `Bootstrap.php:134`. It uses WP core's `wp-pointer` API with copy hardcoded inline.

This finding is the top pitfall in the new doc — engineers grep the codebase, see `WPDeveloper_Notice`, reasonably assume it's the active path, and waste time wiring against it.

### 2. ✅ Created `docs/architecture/admin-notices.md`

12-section structure (same template as `asset-loading.md`, `editor-data-flow.md`, etc.):

- Overview — what's active vs dormant
- Components — files / classes / 5 storage keys
- Architecture Diagram — three ASCII flows (active path, dormant path, error/diagnostic)
- Hook Timing — fire-order tables for both systems
- Data Flow — step-by-step traces
- Configuration & Extension Points — bfcm-pointer hardcoded constants + WPDeveloper_Notice property API
- Adding a New Campaign Notice — two recipes (A: BFCM-style, B: lifecycle via WPDeveloper_Notice)
- Common Pitfalls — 8 documented
- Debugging Guide — pointer-not-showing / dismissal-not-persisting troubleshooting
- Worked Example — full BFCM 2025 lifecycle trace
- Architecture Decisions — 6 ADR-style records
- Known Limitations — 8 documented
- Cross-References — links to skills, rules, sibling architecture docs

### 3. ✅ Step-by-step instructions for adding new campaign notices

Two recipes covering both patterns:

**Recipe A — Time-bound seasonal campaign** (BFCM, anniversary, holiday):
- `cp includes/bfcm-pointer.php includes/<new-name>-pointer.php`
- Update transient name, deadline, copy, page guards, anchor selector
- Wire from `Bootstrap.php:134` (replace or add alongside)
- Test: dashboard pointer appears → dismiss → confirm 30-day transient → reload → gone

**Recipe B — Lifecycle / behavioural notice** (welcome, review request, upgrade hints):
- Activate the dormant `WPDeveloper_Notice` class with full code example showing `options_args`, `message()`, `thumbnail()`, `classes()`, `upsale_args`, `init()` calls
- Pre-built dismissal AJAX, per-version tracking, time scheduling (cne_time / maybe_later_time)

### 4. ✅ Dismissal-lifecycle flow diagrams + storage key table

Three ASCII diagrams:

- **Active path** — guard chain (Pro check → time check → page check → dismiss transient → priority claim) → render → AJAX dismiss → transient set
- **Dormant path** — what would happen if `WPDeveloper_Notice` were activated (init scheduling → per-notice eligibility → admin_notices render → 3 dismissal paths)
- **Error / diagnostic path** — Elementor-not-loaded notice + `eael_admin_notices` proxy on EA settings page

Storage keys table:

| Key | Storage | Shape | Purpose |
| --- | ------- | ----- | ------- |
| `eael_bfcm25_pointer_dismiss` | `wp_options` (transient) | bool | BFCM 2025 dismiss flag, 30-day TTL |
| `_wpdeveloper_plugin_pointer_priority` | `wp_options` | int | Cross-plugin pointer coordination |
| `wpdeveloper_plugins_data` | `wp_options` | `[plugin][notice_will_show][notice]=ts` | Schedule for `WPDeveloper_Notice` |
| `wpdeveloper_notices_seen` | `wp_usermeta` | `[wpdeveloper_notice_<ver>][plugin][]=notice` | Per-version dismissal log |
| `<plugin_name>_<notice>` | `wp_usermeta` | bool | Legacy per-notice dismissal flag |

---

### Notes

- The `vendor/priyomukul/wp-notice` composer dependency is a different admin-notice library (used by `priyomukul/wp-notice` for cache + debug notices). Out of scope for this doc but called out so contributors don't confuse it with `WPDeveloper_Notice`.
- The doc follows the same conventions as #804's architecture corpus — markdown lint clean, language-specified code blocks, blank-line-padded tables, cross-links with line numbers.
