---
name: wp-freemium-plugin-audit
description: "Complete audit skill for any WordPress plugin that ships as a Free (WordPress.org) + Pro (off-directory) pair. Use when asked to audit, review, or pre-flight a plugin for WordPress.org submission or update rejection risk; when checking GPL/licensing, trademark/naming, trialware & upsell boundaries, telemetry consent, remote code, bundled libraries, readme compliance; when running a security pass (nonce/capability/sanitize/escape/SQL/AJAX/SSRF/file-ops); or when verifying the architectural split between a Lite plugin and its Pro companion. Covers all 18 WordPress.org Plugin Directory guidelines, every WordPress Plugin Check automated check, WPCS coding standards, i18n, and performance/asset hygiene."
version: 1.0.0
compatibility: "WordPress 5.0+ through 7.x, PHP 7.0+. Assumes filesystem access and bash (grep/find/rg)."
sources:
  - https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/
  - https://github.com/WordPress/agent-skills (wp-plugin-directory-guidelines, wp-plugin-development)
  - https://github.com/WordPress/plugin-check
  - https://developer.wordpress.org/coding-standards/
  - https://www.gnu.org/licenses/license-list.html
---

# WordPress Freemium Plugin Audit

A single, self-contained skill for auditing a WordPress plugin — Free and Pro codebases together — against every rule that can get a plugin rejected, delisted, or flagged as a security risk.

---

## 0. Mental model: two codebases, two rulebooks

The single most common audit mistake is applying WordPress.org rules to Pro code, or failing to apply them to Free code.

| | **Free / Lite** | **Pro / Premium** |
|---|---|---|
| Distribution | WordPress.org SVN | Vendor site, license-gated download |
| All 18 Directory Guidelines | **Apply in full** | **Do not apply** (G1 licence spirit still applies if it derives from WP) |
| Trialware ban (G5) | **Hard rule** | N/A — Pro may gate anything behind a licence |
| Remote code / self-updater (G8) | **Forbidden** | **Required and expected** (`pre_set_site_transient_update_plugins`) |
| Bundled-library ban (G13) | **Hard rule** | Strongly advised, not enforced |
| Readme/tag/trademark rules | **Hard rule** | N/A |
| GPL compatibility | **Hard rule** | Still required if it is a WordPress derivative work |
| Security standards (nonce/cap/escape/SQL) | **Hard rule** | **Equally hard rule** — Pro bugs are CVEs too |
| WPCS / i18n / performance | **Hard rule** | **Equally hard rule** |

**Rule A — Free code is audited against Sections 1–9 of this skill.**
**Rule B — Pro code is audited against Sections 6–10 only.**
**Rule C — The Free↔Pro seam (Section 10) is audited on both sides at once.** Most real defects live here.

Before auditing anything, state which codebase each finding belongs to. A finding with the wrong codebase label is a false positive.

---

## 1. Phase 0 — Recon (always run first)

Identify the plugin pair, versions, and shape before reading any rule.

```bash
# Locate main plugin files (files with a Plugin Name: header)
grep -rl "^\s*\*\?\s*Plugin Name:" --include="*.php" . --exclude-dir=node_modules --exclude-dir=vendor | head

# Dump both headers
for f in <free-main>.php <pro-main>.php; do echo "### $f"; sed -n '1,30p' "$f"; done

# Readme metadata
sed -n '1,15p' readme.txt

# Size and shape
find . -type f -name "*.php" -not -path "*/node_modules/*" -not -path "*/vendor/*" | wc -l
du -sh assets/ includes/ vendor/ 2>/dev/null
```

Record into an audit header block:

- Free slug, name, version, text domain, `Requires at least`, `Requires PHP`, `Tested up to`, `Stable tag`, `License`
- Pro slug, name, version, text domain
- The filter/constant that bridges them (e.g. `apply_filters( 'eael/pro_enabled', false )`, `EAEL_PRO_PLUGIN_PATH`)
- Third-party platform dependency (Elementor, WooCommerce, …) and its tested-up-to values

If Free and Pro disagree on any shared value (text domain, min PHP, tested-up-to), that is a finding.

---

## 2. Licensing & distribution — Guidelines 1, 2, 3

### 2.1 License headers (G1)

The **main plugin file** must carry both headers. `readme.txt` alone is not sufficient.

```
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
```

```bash
# Main-file headers
grep -n "License\|License URI" <free-main>.php
# Readme headers
grep -n "^License:\|^License URI:" readme.txt
```

**Findings to raise:**

- Missing `License:` in the main plugin file while `readme.txt` declares one → **FAIL**, header/readme mismatch.
- `License:` and readme `License:` name different licences → **FAIL**.
- License URI does not resolve to the licence named → **WARNING**.
- `GPLv3` declared but the plugin depends on a GPLv2-only work → licence-chain conflict, **FAIL**.

> Note: `GPLv3` with `License URI: https://opensource.org/licenses/GPL-3.0` is accepted, but `GPL-2.0-or-later` is the recommended value because it matches WordPress core and maximises downstream compatibility.

### 2.2 Accepted licence identifiers

Plugin Check normalises and matches against this pattern:

```
GPL|GNU|LGPL|MIT|FreeBSD|New BSD|BSD-3-Clause|BSD 3 Clause|OpenLDAP|Expat|
Apache2|MPL20|ISC|CC0|Unlicense|WTFPL|Artistic|Boost|NCSA|ZLib|X11
```

| Family | Accepted | Notes |
|---|---|---|
| GPL | `GPL-2.0-or-later`, `GPLv2 or later`, `GPL-2.0+`, `GPL-2.0-only`, `GPL-3.0-or-later`, `GPL-3.0-only` | Recommended: `GPL-2.0-or-later` |
| LGPL | `LGPL-2.1`, `LGPL-3.0` | |
| Permissive | `MIT`, `Expat`, `X11`, `ISC`, `FreeBSD`, `BSD-3-Clause`, `New BSD`, `ZLib`, `Boost`, `NCSA`, `OpenLDAP` (v2.7+) | |
| Conditional | `Apache-2.0` (GPLv3 only, **not** GPLv2), `MPL-2.0` (via §3.3) | Verify the GPL version the plugin declares |
| Public domain | `CC0`, `Unlicense`, `WTFPL` | |
| Artistic | `Artistic 2.0` only | 1.0 is **not** compatible |

**Rejected — flag any of these:** Proprietary / All Rights Reserved, CC-BY-NC, CC-BY-ND, CC-BY-SA 3.0 and earlier, JSON License ("Good, not Evil"), SSPL, BSL, Commons Clause, Elastic License, original 4-clause BSD, MPL-1.0, EPL, EUPL, Artistic 1.0, OpenLDAP 2.3.

### 2.3 Bundled third-party code licensing

```bash
# Inventory bundled vendor code
find assets vendor includes -type d \( -name "lib*" -o -name "vendor" -o -name "third-party" \) -not -path "*/node_modules/*"
# Hunt for licence declarations in bundled JS
grep -rl "@license\|Licensed under\|MIT License\|Apache License" assets/ --include="*.js" | head -30
# Find bundled files with NO licence banner (the risk set)
for f in $(find assets -name "*.js" -path "*lib*" -not -name "*.min.js"); do
  head -20 "$f" | grep -qi "licen[cs]e\|copyright" || echo "NO LICENSE HEADER: $f"
done
```

Every bundled library needs: a name, a version, a licence, and a source URL. Produce a table. Any row with an unknown licence is **FAIL** until proven otherwise — unlicensed means all-rights-reserved by default.

Check specifically for: GSAP (dual-licensed; the free build is fine, "Club GreenSock" plugins such as `DrawSVGPlugin`, `MorphSVGPlugin`, `ScrollSmoother` are **commercial-only and GPL-incompatible**), Font Awesome Pro, Plyr, Isotope (Metafizzy — GPLv3 free build only; the commercial build is not redistributable), Packery/Masonry v4+ (same), highcharts, fullcalendar premium bundles.

> In the EA codebase this is a live concern: `assets/front-end/js/lib-view/` contains `gsap`, `drawsvg`, `isotope`, `plyr`, `three`. Confirm each is the GPL-compatible free build, and that no Club/commercial plugin file has been vendored.

### 2.4 Split licensing (G1)

**Forbidden:** shipping a Free plugin whose licence text or terms declare the Pro upgrade proprietary in a way that restricts the distributed code. The Pro *product* may be sold commercially; the Free *code* must not carry restrictions.

```bash
grep -rniE "may not (sell|resell|redistribute|modify)|personal use only|non-?commercial|all rights reserved|do not remove (this|the) (credit|link)" --include="*.php" --include="*.txt" --include="*.md" . --exclude-dir=node_modules | head -20
```

### 2.5 Developer responsibility (G2) & stable version (G3)

- No file re-introduced after a review-team removal.
- No API SDK bundled against its own redistribution terms.
- The WP.org SVN version must be the canonical, newest Free build. A newer Free build on the vendor site than on WP.org is **FAIL**.
- `Stable tag` in `readme.txt` must equal `Version:` in the main plugin file (also G15).

```bash
grep -n "^Stable tag:" readme.txt; grep -n "^\s*\*\s*Version:" <free-main>.php
grep -n "define(.*_VERSION" <free-main>.php
```

All three must agree. A version constant that lags the header is a real bug — asset cache-busting silently breaks.

---

## 3. Naming & trademarks — Guideline 17

### 3.1 Technical name requirements

| Rule | Detail | Plugin Check code |
|---|---|---|
| No placeholder names | `Plugin Name`, `My Basics Plugin` rejected | `plugin_header_invalid_plugin_name` |
| ≥ 5 alphanumeric characters | Latin letters/digits | `plugin_header_unsupported_plugin_name` |
| Name present in readme | `=== Name ===` required | `invalid_plugin_name` / `empty_plugin_name` |
| Names match across files | Header vs readme, entity-decoded | `mismatched_plugin_name` (warning) |
| Slug | lowercase, hyphens only, ≤ 50 chars | — |

### 3.2 Trademark placement

Trademarks and project names may appear **only after a connector**: `for`, `with`, `using`, `and`.

| Name | Verdict | Reason |
|---|---|---|
| `Pricing Rates for WooCommerce` | ✅ | Trademark after `for` |
| `WooCommerce Pricing Rates` | ❌ | Starts with a trademark |
| `Nicedev Payment Gateway with PayPal for WooCommerce` | ✅ | Correct multi-trademark structure |
| `Nicedev Paypal for WooCommerce` | ❌ | PayPal not behind a no-affiliation connector |
| `PricingPress` | ❌ | `-Press` portmanteau of WordPress |
| `Best SEO Plugin for WordPress` | ❌ | Superlative + banned terms |
| `ShipGlex Shipping` | ✅ | Invented term leads |
| `Shipping` / `Ecommerce Tracker` | ❌ | Too generic among 60,000+ plugins |

> Applied to EA: `Essential Addons for Elementor` is compliant — the trademark `Elementor` sits behind `for`, and the leading term is distinctive. `Elementor Essential Addons` would be a **FAIL**.

### 3.3 Blocked slug list (static `Trademarks_Check`)

Terms ending in `-` may not **begin** a slug; terms without `-` may not appear **anywhere** in the slug.

```
adobe-, adsense-, advanced-custom-fields-, adwords-, akismet-, all-in-one-wp-migration,
amazon-, android-, apple-, applenews-, applepay-, aws-, azon-, bbpress-, bing-,
booking-com, bootstrap-, buddypress-, chatgpt-, chat-gpt-, cloudflare-, contact-form-7-,
cpanel-, disqus-, divi-, dropbox-, easy-digital-downloads-, elementor-, envato-,
fbook, facebook, fb-, fb-messenger, fedex-, feedburner, firefox-, fontawesome-,
font-awesome-, ganalytics-, gberg, github-, givewp-, google-, googlebot-, googles-,
gravity-form-, gravity-forms-, gravityforms-, gtmetrix-, gutenberg, guten-, hubspot-,
ig-, insta-, instagram, internet-explorer-, ios-, jetpack-, macintosh-, macos-,
mailchimp-, microsoft-, ninja-forms-, oculus, onlyfans-, only-fans-, opera-, paddle-,
paypal-, pinterest-, plugin, skype-, stripe-, tiktok-, tik-tok-, trustpilot, twitch-,
twitter-, tweet, ups-, usps-, vvhatsapp, vvcommerce, vva-, vvoo, wa-, webpush-vn,
wh4tsapps, whatsapp, whats-app, watson, windows-, wocommerce, woocom-, woocommerce,
woocomerce, woo-commerce, woo-, wo-, wordpress, wordpess, wpress, wp, wc,
wp-mail-smtp-, yandex-, yahoo-, yoast, youtube-, you-tube-
```

Also blocked: **any** slug beginning with `woo` (case-insensitive) — `woopress`, `wooland`.
Exception: `woocommerce` (no dash) is permitted only as `for-woocommerce`, `with-woocommerce`, `using-woocommerce`, `and-woocommerce`.

### 3.4 Banned terms (anywhere in the name — even after `for`)

| Term | Reason |
|---|---|
| Facebook, FB, fbook, Whatsapp, WA, Instagram, Insta, Gram, INS, Threads, Oculus | Meta legal request — name, slug, **and banners** |
| WordPress, wordpess, wpress | WordPress Foundation trademark; redundant in the directory |
| WP (standalone/redundant, e.g. "for WP") | Same as above |
| Trustpilot | Trademark-holder request |
| Binance Pay | Trademark-holder request |

### 3.5 Discouraged terms (must be removed)

`plugin` (redundant, forbidden as first word) · `best`, `#1`, `First`, `Perfect`, `The most` (unverifiable superlatives) · `free` (all directory plugins are free) · `WP` / `W P` at the start or end · `Gutenberg`, `gberg`, `guten`, `berg`.

---

## 4. The freemium boundary — Guidelines 5, 6, 10, 11

This is the section that matters most for a Free+Pro product, and the one reviewers scrutinise hardest.

### 4.1 Trialware is forbidden (G5)

**Core rule:** every feature *shipped in the Free plugin* must work end-to-end without a licence key, payment, or account.

Audit each gate in the Free codebase:

```bash
# Licence/pro gates in FREE code
grep -rniE "has_paid_access|is_licensed|check_license|license_key|is_pro\b|pro_enabled|is_premium|has_pro" --include="*.php" includes/ | grep -v node_modules

# Time-bombs
grep -rnE "time\(\)\s*[-+>]|DAY_IN_SECONDS|strtotime\(.*\+\s*[0-9]+ (day|week|month)" --include="*.php" includes/ | head -20

# Artificial caps
grep -rnE "\?\s*[0-9]{2,}\s*:\s*[0-9]{1,3}\b" --include="*.php" includes/ | head -20
```

**FAIL signals:**

- `return` / `wp_die()` / a blocking screen when `has_paid_access()` is false for a **local** feature.
- Ternary limits — `$limit = $licensed ? 10000 : 100` — with no filter to extend the free cap.
- Local features expiring after N days with no external service involved.
- An admin screen entirely replaced by an upgrade prompt.

```php
// ❌ VIOLATION — local feature blocked by a paid check
if ( ! $this->has_paid_access() ) {
    echo 'Upgrade required';
    return;
}

// ❌ VIOLATION — artificial cap with no extension point
$limit = $this->has_paid_access() ? 10000 : 100;

// ✅ COMPLIANT — free path works; premium adds to it
$this->render_basic_export();
if ( $this->has_premium_addon() ) {
    do_action( 'myplugin_premium_export_options' );
}

// ✅ COMPLIANT — cap is uniform and filterable
$limit = apply_filters( 'myplugin_event_limit', 10000 );
```

**ALLOWED — do not flag:**

- A non-blocking upsell notice *alongside* a working free feature.
- Premium functionality delivered by a **separate add-on plugin** not hosted on WP.org.
- A feature gated because the **external service** itself costs money (an AI API quota, a paid data feed).
- A dismissible comparison table or upgrade button inside the plugin's own settings screen.

### 4.2 The teaser-control pattern (the freemium grey zone)

The dominant Elementor-addon pattern is to register a control section in the Free plugin whose only content is an upgrade advert:

```php
// The EA `Promotion` extension pattern — includes/Extensions/Promotion.php
$element->add_control( 'eael_ext_section_parallax_pro_required', [
    'type' => Controls_Manager::RAW_HTML,
    'raw'  => $this->teaser_template( [
        'title'    => __( 'Meet EA Parallax', '<text-domain>' ),
        'messages' => __( 'Create stunning Parallax effects…', '<text-domain>' ),
    ] ),
] );
```

**Audit rules for teasers:**

1. ✅ A teaser is acceptable **only** where the Free plugin never claimed to offer that feature. Advertising a feature that does not exist in Free is upselling, not trialware.
2. ❌ A teaser is a **G5 violation** where it *replaces* a control that previously worked in Free, or where it sits inside a workflow the user cannot otherwise complete.
3. ❌ A teaser rendered on Elementor elements the plugin does not own (`elementor/element/section/...`, `elementor/element/common/...`) is a **G11 risk** — it injects vendor advertising into a third party's UI on every element the user edits. Keep teasers on the plugin's *own* widgets; count how many foreign hook points are used and flag excess.
4. ✅ Teasers must be gated on `! pro_enabled` so Pro users never see them. Verify the guard exists at the constructor level, not per-control.
5. ⚠️ Teaser count is a soft metric — enumerate every teaser section and report the total. A double-digit count across foreign elements reads as dashboard hijacking to a reviewer.

```bash
# Enumerate teaser injection points
grep -rn "add_action( 'elementor/element/" --include="*.php" includes/Extensions/Promotion.php | wc -l
grep -rn "RAW_HTML" --include="*.php" includes/ | grep -i "pro_required\|upgrade\|teaser\|nerd" | wc -l
```

### 4.3 SaaS is permitted, licence-servers-as-features are not (G6)

**FAIL signals:**

- The external service exists solely to validate a licence key while all real processing is local.
- Code moved server-side specifically to disguise a local feature gate.
- The plugin is a storefront/checkout front-end with no actual plugin functionality.

**Requirements when a genuine SaaS is used:** document it in `readme.txt`, name the service, and link its Terms of Use and privacy policy.

```bash
# Inventory every external endpoint the FREE plugin talks to
grep -rnoE "https?://[a-zA-Z0-9.-]+" --include="*.php" includes/ | \
  grep -viE "wordpress.org|w3.org|schema.org|gnu.org|example.com" | \
  sed -E 's/.*(https?:\/\/[a-zA-Z0-9.-]+).*/\1/' | sort | uniq -c | sort -rn
```

Every host in that list must be justified as: (a) a genuine SaaS the user configured, (b) an opt-in telemetry endpoint, or (c) a violation.

### 4.4 No forced external links (G10)

```bash
grep -rniE "powered[ _-]by|designed by|created with|(<a[^>]+href=[\"'])https?://(?!.*(wordpress\.org))" --include="*.php" includes/ templates/ 2>/dev/null | head -20
grep -rn "wp_footer\|the_content\|wp_head" --include="*.php" includes/ | grep -i "credit\|powered\|backlink" | head
```

- Any front-facing credit link must default to **off** and be user-toggleable.
- Removing the credit must not disable functionality.
- Tying an upgrade or feature unlock to keeping a backlink is a combined **G5 + G10 FAIL**.
- Exception: a service may brand output it renders itself (a payment form carrying the processor's logo).

### 4.5 No admin dashboard hijacking (G11)

```bash
# Notice registration — how many, and are they scoped?
grep -rn "admin_notices\|all_admin_notices\|admin_footer\|wp_dashboard_setup" --include="*.php" includes/ | head -20
# Are they scoped to plugin screens?
grep -rn "get_current_screen\|\$hook\s*!==\|toplevel_page_\|\$pagenow" --include="*.php" includes/ | head -20
# Dismissibility
grep -rn "is-dismissible\|notice-dismiss\|update_user_meta.*dismiss\|dismissed" --include="*.php" includes/ | head -20
# Admin iframes — near-automatic FAIL
grep -rn "<iframe" --include="*.php" includes/ | head
```

**FAIL signals:**

- A site-wide notice with no dismiss control, or one that reappears on every page load.
- Upsell shown on every admin page rather than only the plugin's own screens.
- The dashboard home overridden, or full-page overlays injected.
- Ad banners or tracking pixels in wp-admin.
- Settings rendered as an external `<iframe>` instead of native admin UI (also `External_Admin_Menu_Links_Check`).

**Correct pattern:**

```php
add_action( 'admin_notices', function () {
    $screen = get_current_screen();
    if ( ! $screen || false === strpos( $screen->id, 'myplugin' ) ) {
        return;
    }
    if ( get_user_meta( get_current_user_id(), 'myplugin_notice_dismissed', true ) ) {
        return;
    }
    echo '<div class="notice notice-info is-dismissible" data-notice="myplugin">…</div>';
} );
```

Every dismissal must persist per-user (`user_meta`), not per-site (`option`) — a site-wide dismissal hides the notice from other admins who never saw it.

**Seasonal/BFCM campaign notices** (a common vendor pattern) need extra scrutiny: they must be dismissible, must self-expire on a hard date, must not re-arm on plugin update, and must not appear on non-plugin screens.

---

## 5. Code integrity — Guidelines 4, 8, 13

### 5.1 Human-readable code (G4)

```bash
# Obfuscation / dynamic execution
grep -rnE "eval\s*\(|base64_decode|gzinflate|gzuncompress|str_rot13|create_function|assert\s*\(|preg_replace\s*\(\s*['\"].*/e" --include="*.php" . --exclude-dir=node_modules --exclude-dir=vendor | head -20

# Minified JS without a source counterpart
for f in $(find assets -name "*.min.js" -not -path "*/node_modules/*"); do
  src="${f%.min.js}.js"
  [ -f "$src" ] || echo "MINIFIED, NO SIBLING SOURCE: $f"
done

# Is a build process documented?
grep -n "Development\|Build\|GitHub\|source" readme.txt | head
ls package.json webpack.config.js gulpfile.js 2>/dev/null
```

- Obfuscated PHP (ionCube, Zend Guard, packer, `eval`+`base64` chains, `$a1b2c3` naming throughout) → **FAIL, always**.
- Minified-only JS with no source in the package and no public repo linked from `readme.txt` → **FAIL**.
- Minified JS **is** acceptable when `src/` ships alongside it, or `readme.txt` links a public repo. Note this explicitly so it is not mis-flagged.

### 5.2 No remotely loaded executable code (G8) — Free only

```bash
# Scripts/styles enqueued from external hosts
grep -rnE "wp_(enqueue|register)_(script|style)\(\s*['\"][^'\"]+['\"]\s*,\s*['\"]https?://" --include="*.php" . --exclude-dir=node_modules | head

# Remote fetch + execute
grep -rnE "file_get_contents\s*\(\s*['\"]https?|curl_exec|wp_remote_get.*\.js|include\s*\(\s*['\"]https?" --include="*.php" includes/ | head

# Self-updater in the FREE plugin — FAIL
grep -rn "pre_set_site_transient_update_plugins\|site_transient_update_plugins\|plugins_api\b\|upgrader_" --include="*.php" includes/ | head
```

- Loading executable JS/CSS from a third-party CDN → **FAIL** (name the URL and file:line).
- Any runtime remote code download/execution → **FAIL**.
- A Free plugin that updates itself from a non-WP.org server → **FAIL** (also G3).
- An admin page that is entirely an external `<iframe>` → **FAIL** (also G11).

**Allowed exceptions:** web fonts from Google Fonts or similar font CDNs; the plugin's own SaaS loading its own embed script *with* consent per G7.

> Inverted for Pro: `pre_set_site_transient_update_plugins` + `plugins_api` filters are the **expected and correct** way for a Pro plugin to self-update. Audit them for security instead (Section 9.2), not for G8.

### 5.3 Use WordPress-bundled libraries (G13)

```bash
# Bundled copies of core-shipped libraries
find . -type f \( -name "jquery*.js" -o -name "underscore*.js" -o -name "backbone*.js" \
  -o -name "moment*.js" -o -name "react*.js" -o -name "react-dom*.js" \
  -o -name "lodash*.js" -o -name "codemirror*" -o -name "imagesloaded*.js" \
  -o -name "masonry*.js" -o -name "PHPMailer*" -o -name "SimplePie*" -o -name "class-phpass*" \) \
  -not -path "*/node_modules/*" | head -30

# Registering a local copy where a core handle exists
grep -rnE "wp_(register|enqueue)_script\(\s*['\"][^'\"]*(jquery|underscore|backbone|moment|imagesloaded|masonry|react)" --include="*.php" . --exclude-dir=node_modules | head
```

Core-shipped handles to depend on rather than bundle: `jquery`, `jquery-ui-core` and widgets, `underscore`, `backbone`, `moment`, `lodash`, `react`, `react-dom`, `wp-element`, `wp-i18n`, `wp-api-fetch`, `wp-hooks`, `imagesloaded`, `masonry`, `thickbox`, `wp-color-picker`, `jquery-masonry`, `hoverIntent`, `wp-polyfill`, `clipboard`.

PHP libs already in core: `PHPMailer` (use `wp_mail()`), `SimplePie` (use `fetch_feed()`), `PHPass` (use `wp_hash_password()`), `Requests` (use `wp_remote_*()`), `getID3`, `SimplePie`, `Text_Diff`, `PclZip` (use `unzip_file()`).

**Platform-provided assets are the same rule one level up.** When the plugin extends a host plugin, depend on the host's registered handles rather than re-bundling:

| Library | Registered by | Handle | Trap |
|---|---|---|---|
| Swiper 8 | Elementor | `swiper` (CSS); JS via `elementorFrontend.utils.swiper` | Container class is `.swiper`, not `.swiper-container` |
| Font Awesome 5 | Elementor | `font-awesome-5-all` | Already enqueued — never re-bundle |
| Select2 | WooCommerce | `select2` / `selectWoo` | |

```bash
# Duplicate platform libraries
find assets -iname "*swiper*" -o -iname "*font-awesome*" -o -iname "*fontawesome*" | head
grep -rn "get_style_depends\|get_script_depends" --include="*.php" includes/Elements/ | head
```

---

## 6. Privacy & telemetry — Guideline 7

*(Applies to Free with full force; applies to Pro as a legal/GDPR matter.)*

```bash
# Every outbound request
grep -rn "wp_remote_get\|wp_remote_post\|wp_remote_request\|wp_safe_remote" --include="*.php" includes/ | head -30

# Requests fired on activation or admin_init — the high-risk paths
grep -rn "register_activation_hook\|admin_init\|wp_schedule_event\|init'" --include="*.php" includes/ | head -20

# Telemetry classes
grep -rln "tracker\|telemetry\|usage_data\|insights\|analytics" --include="*.php" includes/ | head
```

**Review questions — answer each explicitly:**

1. Is any outbound request made on activation, first run, or cron **before** consent is stored?
2. Is the opt-in UI explicit, unambiguous, and **default-off**?
3. Is consent persisted and re-checked in **every** outbound path — not just the one the opt-in screen guards?
4. Are the collected fields documented in `readme.txt` under a privacy disclosure?
5. Does declining the opt-in fully stop collection, including the "dismiss" path?
6. On deactivation, is a deactivation-reason survey **optional** and skippable?

```php
// ❌ VIOLATION — sends data on activation without consent
register_activation_hook( __FILE__, function () {
    wp_remote_post( 'https://api.example.com/collect', [ 'body' => [
        'site'        => home_url(),
        'admin_email' => get_option( 'admin_email' ),
    ] ] );
} );

// ✅ COMPLIANT — explicit, persisted, re-checked opt-in gate
if ( isset( $_POST['myplugin_opt_in'] ) && '1' === $_POST['myplugin_opt_in'] ) {
    check_admin_referer( 'myplugin_opt_in' );
    update_option( 'myplugin_tracking_opt_in', 1 );
}
if ( get_option( 'myplugin_tracking_opt_in' ) ) {
    wp_remote_post( 'https://api.example.com/collect', $payload );
}
```

**Exception:** when the plugin is an interface to a *named* third-party service the user configured (Akismet, Mailchimp, Twitter/X, Instagram, a CDN), consent is implied by that configuration. Distinguish these clearly from vendor telemetry — an audit that lumps a user-configured Instagram API call together with silent usage tracking is wrong.

> In the EA codebase, `Plugin_Usage_Tracker` gates on `is_tracking_allowed()` reading the `wpins_allow_tracking` option, and the API-feed traits (`Facebook_Feed`, `Twitter_Feed`, `Business_Reviews`, `NFT_Gallery`) are user-configured SaaS calls. Audit these as two separate categories.

**Also flag:** external CDN-hosted images/fonts that leak visitor IPs, third-party ad scripts in admin or frontend, and any `home_url()`/`admin_email`/user-count payload sent without disclosure.

---

## 7. Readme & release metadata — Guidelines 12, 15, 16

```bash
sed -n '1,15p' readme.txt
grep -c "," <(grep "^Tags:" readme.txt)   # rough tag count
awk '/^== Description ==/{exit} /^[A-Za-z]/{print}' readme.txt | head
```

### 7.1 Header checklist

| Field | Rule |
|---|---|
| `Contributors` | WP.org usernames only, comma-separated |
| `Tags` | **Maximum 5.** More is a FAIL. No competitor names. No keyword stuffing |
| `Requires at least` | Must be a real WP version the plugin actually supports |
| `Tested up to` | Current or previous major WP release |
| `Requires PHP` | Must match the lowest PHP the code actually runs on — verify against syntax used |
| `Stable tag` | Must equal the main file `Version:`. `trunk` is discouraged |
| `License` / `License URI` | Must match the main plugin file |
| Short description | ≤ 150 characters, plain text, no markup |

### 7.2 Content rules (G12)

- No affiliate links without an explicit "(affiliate link)" disclosure; no cloaked/redirect affiliate URLs.
- No competitor plugin names in `Tags:`. A WooCommerce extension may tag `woocommerce`; an Akismet *alternative* may not tag `akismet`.
- No keyword stuffing in tags, description, or translation files.
- Written for humans, not crawlers.
- A `== Changelog ==` section, newest first, matching the released version.
- Screenshots in `assets/` referenced by `== Screenshots ==` numbering.

```bash
# Affiliate / redirect links
grep -niE "aff(iliate)?[=_-]|ref=|/go/|/recommend|bit\.ly|/out/" readme.txt | head
# Tag sanity
grep "^Tags:" readme.txt
```

### 7.3 Version discipline (G15) and completeness (G16)

- Bump `Version:`, `Stable tag`, and the version constant **together**, every release.
- Never ship functional changes under an existing version number.
- Tag in SVN as `tags/X.Y.Z`.
- A plugin must be feature-complete at submission — no "coming soon" screens, no placeholder functions, no slug reserved for a future product (G16).

### 7.4 Package hygiene

```bash
# Files that must never ship
cat .distignore 2>/dev/null
find . -maxdepth 2 \( -name ".git" -o -name "node_modules" -o -name "tests" -o -name "*.map" \
  -o -name ".env" -o -name "*.sql" -o -name ".DS_Store" -o -name "composer.lock" \) \
  -not -path "*/node_modules/*" | head -20
```

`File_Type_Check` rejects: VCS directories, compressed archives, application binaries, `.dll`/`.exe`, hidden files, and `node_modules`. `.distignore` must exclude `src/`, `tests/`, `node_modules/`, `.git/`, build configs, and `*.map`. Verify the actual built zip, not just the repo.

---

## 8. Security audit (both codebases — no exemptions)

This section carries the same weight for Free and Pro. Pro is not audited by WP.org, which is exactly why Pro accumulates the worse bugs.

### 8.1 AJAX handlers — the highest-yield surface

```bash
grep -rn "wp_ajax_nopriv_\|wp_ajax_" --include="*.php" includes/ | sed 's/:.*add_action( *.\(wp_ajax[a-z_]*\)/ -> \1/' | head -40
```

For **every** handler, verify in order:

1. **Nonce** — `check_ajax_referer()` / `wp_verify_nonce()` before any work.
2. **Capability** — `current_user_can()` with the narrowest capability that fits. A nonce is CSRF protection, **not** authorization.
3. **Input** — explicit keys only, `wp_unslash()` then a type-appropriate sanitiser. Never iterate `$_POST` wholesale.
4. **Output** — escape at the point of echo.

```php
// ✅ The required shape
public function handle() {
    if ( ! check_ajax_referer( 'myplugin_action', 'nonce', false ) ) {
        wp_send_json_error( 'invalid_nonce', 403 );
    }
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( 'forbidden', 403 );
    }
    $id = isset( $_POST['post_id'] ) ? absint( wp_unslash( $_POST['post_id'] ) ) : 0;
    // …
}
```

**`wp_ajax_nopriv_` handlers deserve a dedicated pass.** A `nopriv` endpoint runs for anonymous visitors, so a capability check is impossible — which means the endpoint must be safe by construction:

- It must never accept a client-supplied `post_status`, `post_type`, `author`, `post__in`, `meta_query`, or `tax_query` that widens visibility. Overriding `post_status` to `'any'` from client input leaks drafts, pending, private, future, trash, and auto-draft content.
- It must enforce `post_status => 'publish'` (or a server-side allow-list) regardless of input.
- It must respect `post_password`. **Password-protected posts keep `post_status = 'publish'`**, so a `post_status` check alone does not stop their content, title, price, SKU, or permalink from being rendered to anonymous visitors. Check `post_password_required()` or `! empty( $post->post_password )` explicitly before rendering single-post content.
- It must not echo unescaped settings supplied in the request body — Elementor-style AJAX handlers that rehydrate `$settings` from POST are a stored-XSS vector.

```bash
# nopriv handlers that build queries
grep -rn -A30 "wp_ajax_nopriv_" --include="*.php" includes/ | grep -nE "post_status|post_type|WP_Query|post__in|meta_query|'any'" | head -20
# Password-protection enforcement
grep -rn "post_password\|post_password_required" --include="*.php" includes/ | head
```

### 8.2 Nonce & capability coverage

```bash
# Superglobal reads with no nearby nonce check
grep -rn '\$_POST\|\$_GET\|\$_REQUEST' --include="*.php" includes/ | wc -l
grep -rn "wp_verify_nonce\|check_admin_referer\|check_ajax_referer" --include="*.php" includes/ | wc -l
# Privileged operations
grep -rn "update_option\|delete_option\|wp_insert_post\|wp_delete_post\|wp_update_user\|delete_user_meta" --include="*.php" includes/ | head -30
grep -rn "current_user_can\|user_can\|is_user_logged_in" --include="*.php" includes/ | wc -l
```

Ratio matters: a large superglobal count with a small nonce count is a strong smell. Then verify individually — grep gives leads, not verdicts.

Common capability mistakes: using `is_user_logged_in()` where `current_user_can()` is required; using `manage_options` for a per-post action where `edit_post` is correct; checking capability on the *settings screen* but not on the *save handler*.

### 8.3 Sanitisation & escaping

**Golden rule: sanitise/validate on input, escape on output — late, at the point of echo.**

| Input | Sanitiser |
|---|---|
| Plain text | `sanitize_text_field()` |
| Textarea | `sanitize_textarea_field()` |
| Integer | `absint()` / `intval()` |
| Float | `floatval()` |
| Email | `sanitize_email()` |
| URL | `esc_url_raw()` (storage) |
| Key/slug | `sanitize_key()`, `sanitize_title()` |
| File name | `sanitize_file_name()` |
| HTML | `wp_kses_post()` / `wp_kses()` with an explicit allow-list |
| HTML class | `sanitize_html_class()` |
| Array | `map_deep( $arr, 'sanitize_text_field' )` — never trust a nested array wholesale |

| Output context | Escaper |
|---|---|
| HTML body | `esc_html()` |
| Attribute | `esc_attr()` |
| URL | `esc_url()` |
| JS value | `esc_js()` / `wp_json_encode()` |
| Textarea | `esc_textarea()` |
| Rich HTML | `wp_kses_post()` |
| Translated string | `esc_html__()`, `esc_attr__()`, `esc_html_e()` |

```bash
# Unescaped echo of variables
grep -rnE "echo\s+\\\$[a-zA-Z_]" --include="*.php" includes/ | grep -vE "esc_|wp_kses|absint|intval|wp_json_encode" | head -30
# Interpolated variables inside HTML strings
grep -rnE "echo\s+['\"]<[^>]*\\\$" --include="*.php" includes/ | head -20
# printf-family without escaping
grep -rn "printf(\|sprintf(" --include="*.php" includes/ | grep -v "esc_" | head -20
```

Elementor-specific: `$this->get_settings_for_display()` values are **user input from the editor** and must be escaped on output. `Controls_Manager::RAW_HTML` and `wp_kses`-free `echo $settings['x']` are the two most common XSS sources in addon plugins.

### 8.4 SQL safety

```bash
grep -rn '\$wpdb->' --include="*.php" includes/ | head -30
grep -rnE '\$wpdb->(query|get_results|get_var|get_row|get_col)\s*\(\s*["\047].*\$' --include="*.php" includes/ | head -20
```

- Every query with a variable must go through `$wpdb->prepare()`.
- Never concatenate user input into SQL.
- `%i` (identifier placeholder) exists only in WP 6.2+ — do not use it if `Requires at least` is lower.
- `prepare()` does not quote identifiers or `IN()` lists — build placeholder lists explicitly:

```php
$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID IN ($placeholders)", $ids );
```

- Prefer `WP_Query`, `get_posts()`, `get_terms()`, and the meta APIs over raw SQL. Raw SQL on core tables is `Direct_DB_Queries_Check` and needs a justification comment.

### 8.5 File, path & network safety

```bash
# File operations
grep -rnE "file_get_contents|file_put_contents|fopen|unlink|rename|copy|mkdir|rmdir|move_uploaded_file" --include="*.php" includes/ | head -20
# Path traversal risk
grep -rnE "(include|require)(_once)?\s*\(?\s*[\"']?.*\\\$" --include="*.php" includes/ | head -20
# Unserialize
grep -rn "unserialize(\|maybe_unserialize(" --include="*.php" includes/ | head
# SSRF
grep -rn "wp_remote_get(\s*\$\|wp_remote_post(\s*\$\|curl_init(\s*\$" --include="*.php" includes/ | head
```

- Use `WP_Filesystem` rather than raw `fopen`/`file_put_contents` where possible; `Write_File_Check` flags direct writes.
- Any `include`/`require` built from a variable must validate against an allow-list and `realpath()`-confirm the result stays inside the plugin directory. `basename()` alone is not sufficient.
- `unserialize()` on untrusted data is object-injection. Use `json_decode()`, or at minimum `unserialize( $data, [ 'allowed_classes' => false ] )`.
- A remote URL built from user input is SSRF — use `wp_safe_remote_get()`, which blocks internal hosts.
- Uploads: validate with `wp_check_filetype_and_ext()`, never trust `$_FILES['type']`, and never allow unfiltered uploads (`No_Unfiltered_Uploads_Check` flags `ALLOW_UNFILTERED_UPLOADS`).

### 8.6 Redirects, direct access, and error output

```bash
grep -rn "wp_redirect(" --include="*.php" includes/ | head          # prefer wp_safe_redirect()
grep -rLn "defined( 'ABSPATH' )\|defined('ABSPATH')" --include="*.php" $(find includes -name "*.php" | head -50) 2>/dev/null | head
grep -rnE "error_reporting|ini_set\s*\(\s*['\"]display_errors|var_dump|print_r\s*\(|console\.log" --include="*.php" includes/ | head -20
```

- `wp_safe_redirect()` for anything derived from input; `wp_redirect()` only for hard-coded destinations (`Safe_Redirect_Check`).
- **Every** PHP file needs a direct-access guard (`Direct_File_Access_Check`):
  ```php
  if ( ! defined( 'ABSPATH' ) ) { exit; }
  ```
- No `error_reporting()`, `ini_set('display_errors')`, `var_dump`, `print_r`, or debug `console.log` in shipped code (`PHP_Error_Reporting_Check`).

### 8.7 Secrets

```bash
grep -rniE "api[_-]?key\s*=\s*['\"][A-Za-z0-9]{16,}|secret\s*=\s*['\"]|password\s*=\s*['\"][^'\"]{6,}|Bearer [A-Za-z0-9._-]{20,}" --include="*.php" --include="*.js" . --exclude-dir=node_modules | head
grep -rn "localhost\|127\.0\.0\.1\|:8888\|\.local\b" --include="*.php" includes/ | head
```

Hard-coded credentials → **FAIL**. Localhost/dev URLs in shipped code → `Localhost_Check` **FAIL**.

---

## 9. Standards, i18n, performance (both codebases)

### 9.1 Prefixing (`Prefixing_Check`)

Every global symbol needs a unique prefix ≥ 4 characters: functions, classes (unless namespaced), constants, global variables, option names, transients, post meta keys, CPT and taxonomy slugs, hook names, script/style handles, shortcode tags, and database table names.

```bash
grep -rnE "^function [a-z_]+\(" --include="*.php" includes/ | grep -viE "function (eael|<your_prefix>)" | head
grep -rn "define( '" --include="*.php" . --exclude-dir=node_modules | grep -viE "define\( '(EAEL|<YOUR_PREFIX>)" | head
grep -rn "add_shortcode(\|register_post_type(\|register_taxonomy(" --include="*.php" includes/ | head
```

Namespaced classes satisfy the class rule. Free and Pro must use **distinct** prefixes for anything either could define alone, and **shared** names only where the contract is deliberate.

### 9.2 Internationalisation (`I18n_Usage_Check`)

```bash
# Wrong text domain
grep -rn "__(\|_e(\|esc_html__(\|esc_attr__(\|_n(\|_x(" --include="*.php" includes/ | grep -v "<correct-text-domain>" | head -20
# Variables inside translation functions — always wrong
grep -rnE "(__|_e|esc_html__|esc_attr__)\(\s*\\\$" --include="*.php" includes/ | head -20
# Concatenation inside translation calls
grep -rnE "(__|_e)\(\s*['\"][^'\"]*['\"]\s*\." --include="*.php" includes/ | head
```

Rules:

- Text domain must exactly match the `Text Domain:` header **and** the plugin slug. Free and Pro have different slugs, therefore **different text domains** — a Pro file using the Lite domain (or vice versa) is a real bug that silently drops translations.
- Never pass a variable, constant, or concatenation as the string or the domain.
- Use placeholders and `sprintf()`, with a `/* translators: */` comment for any string with more than one placeholder.
- Use `_n()` for plurals, `_x()` for disambiguation.
- Load the text domain on `init` (WP 6.7+ warns when it is loaded too early); since WP 4.6, WP.org-hosted plugins do not need `load_plugin_textdomain()` at all for `.org` translations.
- HTML inside translatable strings should be minimised; never put a tag mid-sentence where translators can break it.

### 9.3 Coding standards

Run the project's own config, then WPCS:

```bash
[ -f phpcs.xml.dist ] && vendor/bin/phpcs --standard=phpcs.xml.dist --report=summary . 2>/dev/null | tail -20
vendor/bin/phpcs --standard=WordPress-Extra --extensions=php --ignore=*/node_modules/*,*/vendor/* . 2>/dev/null | tail -30
# PHP compatibility against the declared minimum
vendor/bin/phpcs --standard=PHPCompatibilityWP --runtime-set testVersion 7.0- --extensions=php includes/ 2>/dev/null | tail -20
```

Also verify the code does not use functions newer than `Requires at least` (`WP_Functions_Compatibility_Check`) — e.g. `wp_get_list_item_separator()` (6.0), `wp_admin_notice()` (6.4), `%i` in `prepare()` (6.2), `wp_trigger_error()` (6.4).

### 9.4 Performance & asset hygiene

```bash
# Are assets conditionally loaded, or global?
grep -rn "wp_enqueue_script\|wp_enqueue_style" --include="*.php" includes/ | wc -l
# Footer loading
grep -rnE "wp_enqueue_script\([^)]*\)\s*;" --include="*.php" includes/ | grep -v "true\s*)" | head
# Oversized assets
find assets -name "*.js" -size +300k -not -path "*/node_modules/*" | head
find assets -name "*.css" -size +100k -not -path "*/node_modules/*" | head
# Autoloaded options
grep -rn "add_option(\|update_option(" --include="*.php" includes/ | head -20
```

- Load scripts in the footer (`$in_footer = true`) unless there is a documented reason (`Enqueued_Scripts_In_Footer_Check`).
- Scope admin assets to the plugin's own screens; scope frontend assets to pages that actually use the widget (`Enqueued_Scripts_Scope_Check`, `Enqueued_Styles_Scope_Check`).
- Flag JS > 300 KB and CSS > 100 KB (`Enqueued_Scripts_Size_Check`).
- Large options must be created with `autoload = false`.
- `WP_Query` hygiene (`Performant_WP_Query_Params_Check`): never `posts_per_page => -1` on user-facing queries; avoid `meta_query` on unindexed keys as the sole filter; set `no_found_rows => true` when pagination is unused; avoid `post__not_in` on large sets.
- Transient/cache the results of every external API call, and never let a remote call block page render without a short timeout.

### 9.5 Lifecycle & uninstall

```bash
grep -rn "register_activation_hook\|register_deactivation_hook\|register_uninstall_hook" --include="*.php" . --exclude-dir=node_modules | head
ls uninstall.php 2>/dev/null
```

- Activation/deactivation hooks must be registered at top level in the main plugin file, never inside another hook.
- Flush rewrite rules only after registering the rules, and only on activation — never on `init`.
- `uninstall.php` must begin with `if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }`.
- Uninstall should remove the plugin's own options, transients, meta, tables, and scheduled events — and nothing else. A plugin with options and no uninstall path is a `Plugin_Uninstall_Check` warning; a plugin that deletes user *content* on uninstall without an explicit setting is worse.
- Multisite: uninstall and activation must iterate sites, or explicitly document single-site-only support.

---

## 10. The Free ↔ Pro seam

Audit both sides together. These defects are invisible when either codebase is read alone.

> **For a full seam audit, run [`plugin-separation-audit.md`](plugin-separation-audit.md).** This
> section is the summary; that file is the executable procedure — it takes the Free and Pro plugin
> names, classifies every cross-boundary reference into five buckets (upsell / stranded / fatal /
> drift / coupling), and emits a complete 49-item pass/fail checklist (A1–E8) with `file:line`
> evidence, refactor patterns, and a calibrated false-positive list.

### 10.1 Detection contract

There must be exactly one canonical way for Free to know Pro is active, and Pro must be the only thing that sets it.

```bash
grep -rn "pro_enabled\|_PRO_PLUGIN_PATH\|_PRO_PLUGIN_VERSION\|class_exists.*Pro" --include="*.php" . --exclude-dir=node_modules | head -20
```

Checklist:

- [ ] Free reads the flag through a filter with a safe default: `apply_filters( 'eael/pro_enabled', false )`.
- [ ] Pro sets it once, at bootstrap: `add_filter( 'eael/pro_enabled', '__return_true' )`.
- [ ] Free never assumes Pro's **path** — deriving a path from a constant Pro defines is correct; hard-coding `WP_PLUGIN_DIR . '/plugin-pro/'` is a bug (users rename directories).
- [ ] Every `EAEL_PRO_PLUGIN_PATH`-style constant use in Free is guarded by `defined()` first.
- [ ] Free degrades gracefully when Pro is absent — no fatals, no notices, no missing-widget errors on pages built with Pro widgets.
- [ ] Pro degrades gracefully when Free is deactivated — either a clear admin notice or a clean no-op, never a fatal.

### 10.2 Version-compatibility gate

Pro depends on Free. A version skew (here: Lite 6.8.3 / Pro 7.0.3) must be handled explicitly.

- [ ] Pro declares a minimum Free version and checks it on load.
- [ ] A mismatch produces a dismissible admin notice, not a fatal.
- [ ] Shared traits/classes that Pro extends are not renamed or signature-changed in Free without a Pro release.
- [ ] Anything Pro consumes from Free is treated as a **public API** — document it and deprecate before removing.

```bash
# What does Pro reach into Free for?
grep -rn "Essential_Addons_Elementor\\\\\(Traits\|Classes\|Elements\)" --include="*.php" ../<pro-dir>/includes/ | head -20
```

### 10.3 Code-duplication and drift

```bash
# Same-named files on both sides — candidates for silent drift
comm -12 \
  <(cd <free-dir> && find includes -name "*.php" | sed 's|.*/||' | sort -u) \
  <(cd <pro-dir>  && find includes -name "*.php" | sed 's|.*/||' | sort -u) | head -30
```

For each shared filename, diff the two. A security fix applied to the Free copy but not the Pro copy is the single most common freemium vulnerability pattern — and the one attackers look for first, because the Free source is public and the fix is a published diff.

- [ ] Every security fix landed in Free has been mirrored into any duplicated Pro copy (and vice versa).
- [ ] Helper functions duplicated across both are identical, or the Pro copy is deliberately deleted in favour of Free's.
- [ ] Shared AJAX handler names are not registered twice.

### 10.4 Licence handling (Pro only)

```bash
grep -rn "license\|activate\|deactivate" --include="*.php" <pro-dir>/includes/Classes/License/ | head -20
```

- [ ] The licence key is stored in an option, never in a file, never in a cookie, never logged.
- [ ] Licence activation/deactivation endpoints check nonce **and** `manage_options`.
- [ ] The remote licence check is cached (transient) with a sane TTL and does not run on every admin page load.
- [ ] A failed or expired licence **does not** break already-rendered content or delete data. Degrading to "no updates" is correct; white-screening a live site is not.
- [ ] Licence API responses are validated before use — never `unserialize()` a remote body; `unserialize( $body, [ 'allowed_classes' => false ] )` at minimum, `json_decode()` preferably.
- [ ] The self-updater uses HTTPS, verifies the response shape, and does not accept an arbitrary `package` URL from an unauthenticated source.
- [ ] Cron-based licence checks are idempotent and rescheduled correctly on deactivation.

### 10.5 Upsell placement rules recap

- Upsells live in Free, never in Pro.
- Every upsell is guarded by `! pro_enabled` at the highest possible level.
- Upsells never block a working free workflow (§4.1) and never colonise third-party UI beyond a defensible minimum (§4.2).

---

## 11. Severity model

| Severity | Meaning | Examples |
|---|---|---|
| **BLOCKER** | Will get the plugin rejected or removed; or is an exploitable vulnerability | Missing nonce+cap on a state-changing AJAX handler · SQLi · obfuscated PHP · trialware gate on a local feature · unconsented telemetry · GPL-incompatible bundled library |
| **HIGH** | Reviewer will reject; or a security weakness needing a specific precondition | Missing `License:` header · > 5 tags · trademark-leading name · self-updater in the Free plugin · unescaped output of editor settings · `nopriv` handler leaking drafts |
| **MEDIUM** | Guideline or standards violation that invites rejection on re-review | Undismissable notice · notice outside plugin screens · bundled jQuery · wrong text domain · `Stable tag` ≠ `Version` · missing direct-access guard |
| **LOW** | Quality, performance, maintainability | Global asset enqueue · oversized bundles · `posts_per_page => -1` · missing `translators:` comment · autoloaded large option |
| **INFO** | Context, not a defect | Documented exception · a pattern that looks wrong but is justified |

Every finding must carry: severity · codebase (Free/Pro/Both) · guideline or check reference · `file:line` · the offending snippet · a concrete fix.

---

## 12. Report format

```markdown
# Plugin Audit — <Name>

## Scope
| | Free | Pro |
|---|---|---|
| Slug / Version | | |
| Text domain | | |
| Requires WP / PHP | | |
| Files audited | | |

Audited against: WP.org Guidelines 1–18, Plugin Check (all checks), WPCS, i18n, security, performance.

## Verdict
**<SUBMISSION-READY | BLOCKED | CONDITIONAL>** — N blockers, N high, N medium, N low.

## Blockers
### B1 — <one-line title>
- **Codebase:** Free
- **Rule:** Guideline 5 (Trialware) / `Plugin_Repo\...`
- **Location:** `includes/Foo.php:123`
- **Evidence:**
  ```php
  <snippet>
  ```
- **Why it fails:** <one paragraph>
- **Fix:**
  ```php
  <corrected code>
  ```

## High / Medium / Low
<same structure>

## Verified clean
<explicit list of checks that passed — this is as important as the findings; it stops the next audit re-litigating the same ground>

## Free ↔ Pro seam
<detection contract, version gate, duplication drift, licence handling>
```

**Reporting discipline:**

- Never report a finding without reading the surrounding code. Grep produces leads; only reading produces findings.
- Never report the same defect twice under two guidelines — pick the primary rule and cross-reference the secondary.
- State explicitly what was **not** audited (runtime behaviour, the built zip, translations, third-party API responses) rather than implying full coverage.
- Distinguish "violates a guideline" from "I would write it differently". The second belongs in LOW, or not at all.

---

## 13. Fast path — the 15-minute triage

When a full audit is not warranted, run these in order and stop at the first blocker:

```bash
# 1. Licence headers present and compatible
grep -n "License" <free-main>.php readme.txt

# 2. Version alignment
grep -n "Version:\|Stable tag:\|_VERSION" <free-main>.php readme.txt | head

# 3. Tag count ≤ 5, name/trademark shape
grep "^Tags:\|^=== " readme.txt

# 4. Obfuscation / remote execution
grep -rnE "eval\s*\(|base64_decode|gzinflate|create_function" --include="*.php" includes/ | head

# 5. External executable assets + self-updater in Free
grep -rnE "wp_(enqueue|register)_(script|style)\([^)]*https?://" --include="*.php" includes/ | head
grep -rn "pre_set_site_transient_update_plugins" --include="*.php" includes/ | head

# 6. Unconsented telemetry
grep -rn "wp_remote_post\|wp_remote_get" --include="*.php" includes/ | head -20

# 7. AJAX handlers without nonce/cap
grep -rn "wp_ajax_" --include="*.php" includes/ | wc -l
grep -rn "check_ajax_referer\|wp_verify_nonce" --include="*.php" includes/ | wc -l
grep -rn "current_user_can" --include="*.php" includes/ | wc -l

# 8. Raw SQL
grep -rnE '\$wpdb->(query|get_results|get_var)\s*\(\s*["\047].*\$' --include="*.php" includes/ | head

# 9. Unescaped output
grep -rnE "echo\s+\\\$[a-zA-Z_]" --include="*.php" includes/ | grep -vE "esc_|wp_kses|absint" | head

# 10. Bundled core libraries
find . -name "jquery*.js" -o -name "underscore*.js" -o -name "PHPMailer*" | grep -v node_modules | head

# 11. Direct-access guards
find includes -name "*.php" | xargs grep -L "ABSPATH" 2>/dev/null | head

# 12. Trialware gates
grep -rniE "has_paid_access|is_licensed|check_license" --include="*.php" includes/ | head
```

---

## 14. Master checklist

### Free plugin — WordPress.org directory
- [ ] G1 · `License:` + `License URI:` in main file, matching readme, GPL-compatible
- [ ] G1 · Every bundled library inventoried with name, version, licence, source
- [ ] G1 · No split licensing, no restrictive clauses, no obfuscation
- [ ] G2 · No files the developer cannot legally distribute; no removed code re-added
- [ ] G3 · WP.org SVN carries the canonical, newest Free build
- [ ] G4 · No obfuscated PHP; minified JS has source in package or a linked public repo
- [ ] G5 · Every shipped feature works with no licence key, no payment, no account
- [ ] G5 · No time-based expiry or artificial quota on local behaviour
- [ ] G5 · No blocking/locked UI; upsells informational, non-blocking, dismissible
- [ ] G6 · Any external service provides real functionality and is documented in readme
- [ ] G6 · No licence-validation-only "service"; no storefront-only plugin
- [ ] G7 · Zero outbound telemetry before explicit, default-off, persisted opt-in
- [ ] G7 · Consent re-checked in every outbound path; privacy disclosure in readme
- [ ] G8 · All executable JS/CSS bundled locally; no CDN-loaded scripts
- [ ] G8 · No runtime remote code download or execution; no self-updater; no admin iframe
- [ ] G9 · No SEO manipulation, review gaming, resource abuse, or false compliance claims
- [ ] G10 · No front-facing credits by default; opt-in only; removal breaks nothing
- [ ] G11 · Notices dismissible, per-user, and scoped to the plugin's own screens
- [ ] G11 · No dashboard override, overlays, ads, or tracking pixels in wp-admin
- [ ] G12 · ≤ 5 relevant tags; no competitor tags; no keyword stuffing; affiliates disclosed
- [ ] G13 · No bundled copy of any WordPress- or platform-provided library
- [ ] G14 · SVN commits are release-quality; no debug code in trunk
- [ ] G15 · `Version:` = `Stable tag` = version constant; incremented every release
- [ ] G16 · Feature-complete; no placeholders or reserved slugs
- [ ] G17 · Name/slug pass naming, trademark, banned-term, and portmanteau rules
- [ ] G18 · Team aware WP.org may update rules or act at any time

### Plugin Check parity
- [ ] `Code_Obfuscation_Check` · `Direct_File_Access_Check` · `External_Admin_Menu_Links_Check`
- [ ] `File_Type_Check` · `Localhost_Check` · `Minified_Files_Check` · `No_Unfiltered_Uploads_Check`
- [ ] `Offloading_Files_Check` · `Plugin_Content_Check` · `Plugin_Header_Fields_Check`
- [ ] `Plugin_Readme_Check` · `Plugin_Uninstall_Check` · `Plugin_Updater_Check`
- [ ] `Prefixing_Check` · `Setting_Sanitization_Check` · `Trademarks_Check`
- [ ] `WP_Functions_Compatibility_Check` · `Write_File_Check`
- [ ] `Direct_DB_Check` · `Direct_DB_Queries_Check` · `Late_Escaping_Check`
- [ ] `Nonce_Verification_Check` · `Safe_Redirect_Check`
- [ ] `I18n_Usage_Check` · `PHP_Error_Reporting_Check` · `AI_Provider_Check`
- [ ] `Enqueued_Resources_Check` · `Enqueued_Scripts_In_Footer_Check` · `Enqueued_*_Scope_Check` · `Enqueued_*_Size_Check`
- [ ] `Non_Blocking_Scripts_Check` · `Performant_WP_Query_Params_Check`

### Security — Free and Pro alike
- [ ] Every AJAX handler: nonce → capability → sanitise → escape
- [ ] Every `nopriv` handler safe by construction; `post_status` server-controlled
- [ ] `post_password` enforced wherever single-post content is rendered
- [ ] No unescaped output of editor/user settings
- [ ] All SQL prepared; no concatenation; no `%i` below WP 6.2
- [ ] No path traversal in dynamic `include`/`require`
- [ ] No `unserialize()` of untrusted data
- [ ] `wp_safe_remote_*` for any URL derived from input
- [ ] `wp_safe_redirect()` for input-derived destinations
- [ ] `ABSPATH` guard in every PHP file
- [ ] No hard-coded secrets, no localhost URLs, no debug output
- [ ] Uploads validated with `wp_check_filetype_and_ext()`

### Standards, i18n, performance — Free and Pro alike
- [ ] Unique ≥ 4-char prefix on every global symbol
- [ ] Correct, per-plugin text domain everywhere; no variables in translation calls
- [ ] `translators:` comments on multi-placeholder strings; `_n()`/`_x()` used correctly
- [ ] WPCS clean against the project config; PHP-compatible with declared minimum
- [ ] Assets conditionally enqueued and footer-loaded; sizes within budget
- [ ] Queries performant; external calls cached and timeout-bounded
- [ ] Lifecycle hooks top-level; `uninstall.php` guarded and complete

### Free ↔ Pro seam
- [ ] Single canonical detection filter; Pro is its only setter
- [ ] No hard-coded Pro paths; every Pro constant `defined()`-guarded in Free
- [ ] Both degrade gracefully without the other — no fatals either direction
- [ ] Pro declares and checks a minimum Free version; mismatch shows a notice
- [ ] Free→Pro surface treated as public API, documented, deprecated before removal
- [ ] Duplicated files diffed; every security fix mirrored to both copies
- [ ] Licence key stored safely; endpoints nonce+cap protected; checks cached
- [ ] Expired licence degrades to "no updates", never breaks a live site
- [ ] Licence/update responses validated; no `unserialize()` of remote bodies
- [ ] Upsells only in Free, guarded by `! pro_enabled`, never blocking

---

## 15. Known false positives — do not report these

- **Minified JS with a `src/` directory or a linked public repo** — compliant under G4. Only flag when neither exists.
- **User-configured third-party API calls** (Instagram, Twitter/X, Facebook, Google Reviews, a maps provider) — these are SaaS under G6/G7, not telemetry. Only flag when the call fires before the user supplies credentials.
- **A self-updater in the Pro plugin** — required, not a G8 violation.
- **Licence gates in the Pro plugin** — Pro is not on WP.org; G5 does not apply.
- **A widget that requires Elementor** — a platform dependency is not G16 incompleteness, provided the readme says so and the plugin no-ops cleanly when Elementor is absent.
- **`Controls_Manager::RAW_HTML` upsell controls in Free** — permitted advertising under G5 *when* the advertised feature never existed in Free and the control blocks nothing. Flag placement (§4.2 rule 3), not existence.
- **Bundled Swiper/Font Awesome shipped as a documented fallback** for contexts where the host plugin may not load them — note it as INFO with the justification, and verify the fallback is actually conditional.
- **`$wpdb` use on the plugin's own custom tables** — not a Direct DB violation; core-table raw SQL is.

---

## 16. Escalation

When a rule is ambiguous, do not guess:

1. Licence compatibility → [GNU license list](https://www.gnu.org/licenses/license-list.html), [GPL FAQ](https://www.gnu.org/licenses/gpl-faq.html)
2. Directory guidelines → [Detailed Plugin Guidelines](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/)
3. Automated check semantics → [WordPress/plugin-check](https://github.com/WordPress/plugin-check) source
4. Coding standards → [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
5. Security APIs → [Security handbook](https://developer.wordpress.org/apis/security/)
6. Naming edge cases → the Plugin Review team, via the plugin's WP.org support channel

Record the ambiguity in the report as INFO with the question you would ask, rather than issuing a verdict you cannot defend.
