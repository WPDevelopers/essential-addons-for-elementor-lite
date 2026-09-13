# Essential Addons — Free + Pro Compliance Checklist

**Audited:** `essential-addons-for-elementor-lite/essential_adons_elementor.php` (Free 6.8.3) · `essential-addons-elementor/essential_adons_elementor.php` (Pro 7.0.3)
**Against:** [`plugin-audit-skill.md`](plugin-audit-skill.md) (18 guidelines + Plugin Check + security) · [`plugin-separation-audit.md`](plugin-separation-audit.md) (A1–E8 separation)
**Date:** 2026-09-10 · **Scale constraint:** ~2,000,000 active installs

---

## 0. Read this before touching anything

This plugin has 2M active sites and years of accumulated behaviour. **A compliance fix that breaks 2M sites is worse than the compliance problem.** Every step below is graded for backward-compatibility risk, and the order is chosen so that zero-risk items ship first.

### The five BC rules

1. **Never delete a public hook.** `do_action`/`apply_filters` names are third-party API. Thousands of sites have snippets bound to `eael_*` hooks. If a hook must move, fire both the old and new name for two minor versions, with `_deprecated_hook()` on the old one.
2. **Never rename an option, transient, or post-meta key** without a migration that reads the old key, writes the new, and keeps reading the old for at least one major version.
3. **Never change a front-end CSS class or DOM shape** that a theme or a customer's custom CSS could target. `.eael-*` classes are effectively public API. Add classes; do not rename or remove them.
4. **Additive-first.** Prefer *adding a guard* over *removing a branch*. A guard changes behaviour only in the broken case; removing a branch changes it for everyone.
5. **Ship behind a version gate when in doubt.** Free and Pro update independently — a user can run new Free with a year-old Pro. Every cross-plugin change needs a version check, not an assumption.

### Required test matrix for every step

| Scenario | Why it matters |
|---|---|
| Free only, clean install, `WP_DEBUG=true` | This is exactly what the WP.org reviewer runs |
| Free only, existing site with Pro widgets already on pages | The 2M-user upgrade path |
| Free current + Pro current | Normal paid user |
| Free current + Pro **one major behind** | The realistic skew — most Pro users update late |
| Free current + Pro **deactivated mid-session** | What happens when a licence lapses |

### Risk legend

| Grade | Meaning |
|---|---|
| 🟢 **ZERO** | Cannot affect a running site. Ship immediately. |
| 🟡 **LOW** | Behaviour changes only on a currently-broken path. |
| 🟠 **MEDIUM** | Touches a live code path. Needs the full test matrix. |
| 🔴 **HIGH** | Touches shared API or output. Needs deprecation + two-release runway. |

---

## 1. Verdict

**Status (2026-09-11): all 3 blockers closed; issue #897 verified and fixed — see §9.** S5, S6, S7 and S12 are **won't do** by owner decision (risk to ~2M live sites). Plugin Check on the built package: **207 → 131 rows, 151 → 72 errors** (71 of the remaining 72 are the deferred escaping pass). Nothing found is unfixable, and the two most serious items are both zero-BC-risk deletions.

| Bucket | Count | Items |
|---|---|---|
| 🔴 Blocker — WP.org removal risk | 3 | S1, S2, S4 |
| 🟠 High — fatal / security | 5 | S5, S6, S7, S9, S10 |
| 🟡 Medium — separation / hygiene | 6 | S3, S8, S11, S12, S13, S14 |
| ✅ Verified clean | 12 | see §4 |

**The good news up front:** namespaces, autoloader guards, version alignment, AJAX action uniqueness, the Pro licence updater's deserialization hardening, and the widget registry are all already correct. The separation architecture is fundamentally sound — the failures are localised, not structural.

---

## 2. Step-by-step remediation

Work top to bottom. Steps 1–4 are shippable this week with no regression surface.

---

### ✅ S1 · Add the missing GPL licence headers to Free — **DONE**

**Risk: 🟢 ZERO** · Rule: Guideline 1 · Item: E-License

**Finding.** [`essential_adons_elementor.php`](../essential_adons_elementor.php) has **no `License:` and no `License URI:` header.** Only `readme.txt` declares one (`GPLv3`). Plugin Check's `Plugin_Header_Fields_Check` reads the *main file*, not the readme.

```
Current header (lines 3–14): Plugin Name, Description, Plugin URI, Author,
Version, Author URI, Text Domain, Domain Path — no License field at all.
```

**Fix.** Add two lines to the main file docblock, matching what `readme.txt` already claims:

```php
 * Text Domain: essential-addons-for-elementor-lite
 * Domain Path: /languages
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
```

**Applied:** `License: GPLv3` + `License URI: https://opensource.org/licenses/GPL-3.0` added to the main file, matching `readme.txt` **exactly**. The licence *value* was deliberately not changed — that is a legal decision, not a code one.

> **Do not "upgrade" this to `GPL-2.0-or-later`.** The bundled Isotope v3.0.6 is *"Licensed GPLv3 for open source use or Isotope Commercial License for commercial use"* — **GPLv3-only**. A `GPL-2.0-or-later` declaration would let a downstream user choose GPLv2, which is incompatible with Isotope's GPLv3 terms. **EA's GPLv3 choice is load-bearing.** WordPress itself is "GPLv2 or later", so GPLv3 is permitted.

**Original note:** `readme.txt` says `GPLv3` with an `opensource.org` URI. Confirm GPLv3 is intentional — `GPL-2.0-or-later` is the WordPress-recommended value and is more permissive downstream. Whichever you choose, **the main file and readme must match exactly.** If you switch to GPLv2-or-later, update both in the same commit.

- [ ] Add `License:` + `License URI:` to the main plugin file
- [ ] Confirm the value matches `readme.txt` character-for-character
- [ ] Ship a `LICENSE` file at the plugin root if not present
- [ ] Re-run Plugin Check → `Plugin_Header_Fields_Check` clean

**Verify:** `grep -nE "License" essential_adons_elementor.php readme.txt`

---

### ✅ S2 · Delete the GSAP `Draggable` files — **DONE**

**Risk: 🟢 ZERO** · Rule: Guideline 1 · Item: §2.3

**Finding.** Free ships:

```
assets/front-end/js/lib-view/gsap/Draggable.js
assets/front-end/js/lib-view/gsap/Draggable.min.js
```

Whose banner reads:

```js
 * @license Copyright 2022, GreenSock. All rights reserved.
 * Subject to the terms at https://greensock.com/standard-license or for
 * Club GreenSock members, the agreement issued with that membership.
```

The **GreenSock Standard License is not GPL-compatible** — it reserves rights and restricts redistribution, and it does not appear in Plugin Check's accepted-licence pattern. Shipping it in a WP.org plugin is a Guideline 1 violation.

**Why this is free to fix:** the files are **not enqueued**. Both GSAP entries in [`config.php:1203-1210`](../config.php#L1203-L1210) are commented out:

```php
//gsap
//     'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/gsap/gsap.min.js',
//     'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/gsap/Draggable.min.js',
```

Nothing loads them. `gsap.min.js` itself is not even present. This is pure dead weight carrying a licensing liability.

- [ ] `rm -rf assets/front-end/js/lib-view/gsap/`
- [ ] Remove the commented-out GSAP block from `config.php` (dead config invites re-enabling)
- [ ] Confirm nothing else references `gsap` or `Draggable`
- [ ] Repeat the same audit in **Pro** — Pro is not bound by Guideline 1 for WP.org, but a proprietary-licensed file redistributed to customers is a commercial licensing exposure

**Verify:** `grep -rn "gsap\|Draggable" config.php includes/ assets/ --include="*.php" --include="*.js" | grep -v node_modules`

---

### ✅ S3 · Verify the built zip excludes `node_modules` — **VERIFIED CLEAN**

**Risk: 🟢 ZERO** (verification only) · Rule: Guideline 12 / `File_Type_Check` · Item: E7

**Finding.** **176 MB of `node_modules` lives inside `includes/`:**

```
includes/templates/admin/eael-dashboard/node_modules   95M
includes/templates/admin/quick-setup/node_modules      81M
includes/templates/admin/theme-builder/node_modules     0B
```

`.distignore` *does* contain a bare `node_modules` line, so this is **probably** handled — but "probably" is not good enough. `File_Type_Check` rejects `node_modules` outright, and a 176 MB directory inside `includes/` is the kind of thing that slips through when a packaging tool matches only top-level paths.

**This item is a verification, not a change.** Do not edit `.distignore` until you have proven it fails.

- [ ] Build the actual distribution zip the release process produces
- [ ] `unzip -l <zip> | grep node_modules` → must return **nothing**
- [ ] `unzip -l <zip> | grep -E "\.git|/src/|/tests/|\.map$"` → must return nothing
- [ ] Record the zip size; anything over ~30 MB deserves a second look
- [ ] If node_modules *is* present, add explicit nested paths to `.distignore` rather than relying on the bare pattern
- [ ] **Add `org-rules` to `.distignore`.** This folder now sits at the plugin root and is **not** currently excluded, so these audit documents would ship to 2M users inside the plugin zip. A file enumerating your own open compliance gaps is the last thing that should reach a WP.org reviewer. One line in `.distignore` fixes it

**Verify:** build the zip and inspect it. Never infer packaging behaviour from the ignore file alone.

---

### ✅ S4 · Guard the unguarded Pro constant — **DONE**

**Risk: 🟡 LOW** · Rule: Bucket 3 / Guideline 8 · Item: B1

**Finding.** Two call sites use `EAEL_PRO_PLUGIN_PATH` with **no `defined()` guard**:

- [`includes/Traits/Ajax_Handler.php:346`](../includes/Traits/Ajax_Handler.php#L346)
- [`includes/Traits/Ajax_Handler.php:1276`](../includes/Traits/Ajax_Handler.php#L1276)

```php
} else if ( $template_info['dir'] === 'pro' ) {
    $dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );   // ← undefined without Pro
}
```

`$template_info` is derived from `$_REQUEST['template_info']`. On a **Free-only install** — precisely what a WP.org reviewer runs — a crafted request reaches this branch and triggers a fatal on an undefined constant. This is reachable by an unauthenticated visitor.

The correct pattern already exists elsewhere in the same codebase. [`includes/Traits/Template_Query.php:112-116`](../includes/Traits/Template_Query.php#L112-L116) does it right:

```php
if ( ! apply_filters( 'eael/pro_enabled', false ) ) { return ...; }
if ( ! defined( 'EAEL_PRO_PLUGIN_PATH' ) ) { return ...; }
```

Two call sites, two different standards — that inconsistency is the bug.

**Fix (additive, per BC rule 4):**

```php
} else if ( $template_info['dir'] === 'pro' ) {
    if ( ! defined( 'EAEL_PRO_PLUGIN_PATH' ) ) {
        wp_send_json_error( 'invalid_template', 400 );   // clean rejection, not a fatal
    }
    $dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );
}
```

This changes behaviour **only** on the path that currently fatals. Pro users are unaffected — the constant is defined for them, so the guard is transparent.

- [ ] Guard line 346
- [ ] Guard line 1276
- [ ] Keep the existing `realpath()` containment check below — it is correct, do not touch it
- [ ] Test: Free-only install, POST `template_info[dir]=pro` → JSON error, no fatal
- [ ] Test: Free+Pro → Pro templates still render (regression check)

**Longer-term (schedule, do not rush):** apply pattern P3 from the separation skill — replace the constant reference with `apply_filters( 'eael/template/base_path', null, $template_info )` so Free stops naming Pro symbols entirely. That is a 🟠 MEDIUM change; the guard above is the 🟡 LOW fix that closes the hole now.

---

### 🟠 S5 · Fix the dead Pro hook listeners — **WON'T DO (owner decision: BC risk)**

**Risk: 🟡 LOW to 🟠 MEDIUM** · Rule: Bucket 5 / coupling · Item: D6

**Finding.** Free fires **434** hooks; Pro subscribes to **211**; **120** overlap and form the real API surface. But **22 hooks Pro listens for are never fired by Free.** Confirmed example:

| Pro listens for | Free actually fires | File |
|---|---|---|
| `eael/product_gallery/layout/controls` | `eael/product_**grid**/layout/controls` | [`Product_Grid.php:444`](../includes/Elements/Product_Grid.php#L444) |

**gallery** vs **grid**. WordPress fails silently on a mismatched hook name — no error, no warning, just a Pro feature that quietly does nothing. Users may have been paying for a broken feature for a long time.

Full dead-listener list (verify each before acting — Free constructs only 2 hook names dynamically, so this list is high-signal):

```
eael/advanced-data-table/table_html/integration/{database,google,remote,tablepress}
eael/login-register/after-init-{login,register}-button-style
eael/post_args
eael/product-gallery/content/reordering
eael/product_gallery/layout/controls
eael/product_gallery/product_settings/control/after_rating
eael/product_grid/product_settings/control/after_rating
eael_additional_support_links      eael_adv_accordion_styles
eael_adv_tab_styles                eael_business_profile_token_refresh
eael_fg_caption_styles             eael_liquid_glass_effect_filter
eael_manage_license_action_link    eael_pinterest_feed_token_refresh
eael_premium_support_link          eael_pricing_table_styles
eael_ticker_options
```

**Fix — and this is where BC rule 1 bites.** For each dead listener, decide which side is wrong:

- **Pro has a typo** → fix Pro's `add_action` name. 🟡 LOW, Pro-only release.
- **Free dropped the hook in a refactor** → **re-add the `do_action` in Free.** 🟡 LOW. Do *not* instead rename Pro's listener, because third parties may already bind to the Free name you removed.
- **The feature was intentionally retired** → remove Pro's listener, and document it.

- [ ] Triage all 22 — classify each as typo / dropped / retired
- [ ] Confirm `product_gallery` vs `product_grid` first; it is the confirmed one
- [ ] For any hook Free stopped firing, re-add it rather than renaming Pro
- [ ] Add a CI check that diffs Pro's listeners against Free's emitters and fails on a new orphan

**Verify:**
```bash
comm -13 <(grep -rhoE "(do_action|apply_filters)\(\s*'[a-z0-9_/-]+'" ../../includes --include="*.php" | sed "s/.*'\(.*\)'/\1/" | sort -u) \
         <(grep -rhoE "(add_action|add_filter)\(\s*'eael[a-z0-9_/-]*'" ../../../essential-addons-elementor/includes --include="*.php" | sed "s/.*'\(.*\)'/\1/" | sort -u)
```

---

### 🟠 S6 · Audit the 14 unauthenticated AJAX handlers in Free — **WON'T DO (owner decision: BC risk)**

**Risk: 🟠 MEDIUM** · Rule: Guideline 9 / security · Item: §8.1

**Finding.** Free registers **14 `wp_ajax_nopriv_` handlers** — each runs for anonymous visitors, so a capability check is impossible and the endpoint must be **safe by construction**:

```
eael_ajax_add_to_cart          eael_checkout_cart_qty_update
eael_get_token                 eael_lr_send_otp
eael_lr_verify_otp             eael_product_add_to_cart
eael_product_gallery           eael_product_grid
eael_product_quickview_popup   facebook_feed_load_more
load_more                      woo_checkout_update_order_review
woo_product_pagination         woo_product_pagination_product
```

Pro adds 7 more. **No duplicate action names across the pair** — that part is clean (C5 PASS).

**For each handler, verify in order.** This is a per-handler manual pass; grep only finds the handlers, not the verdict.

- [ ] **Nonce** checked before any work
- [ ] **`post_status` is server-controlled** — never accepted from the request. A client-supplied `post_status => 'any'` leaks drafts, pending, private, future, and trash content
- [ ] **`post_password` enforced.** Password-protected posts keep `post_status = 'publish'`, so a status check alone does **not** stop their title, price, SKU, and content reaching anonymous visitors. `eael_product_quickview_popup` and `eael_product_grid` are the highest-risk here
- [ ] **`post__in` / `author` / `meta_query` / `tax_query`** from the request cannot widen visibility
- [ ] Settings rehydrated from POST are **escaped on output** — this is the stored-XSS path in Elementor-style handlers
- [ ] Rate-limiting on `eael_lr_send_otp` (SMS/email cost abuse) and `eael_get_token`

**Priority order:** `eael_product_quickview_popup` → `eael_product_grid` → `load_more` → `eael_lr_send_otp` → the rest. The first three render post content; the fourth spends money.

**Note:** the codebase already has two dedicated skills for exactly this — `wp-ajax-nopriv-visibility` and `wp-password-protected-exposure`. Run them per handler rather than re-deriving the analysis.

---

### 🟠 S7 · Escape the unescaped output — **WON'T DO (owner decision: BC risk)**

**Risk: 🟠 MEDIUM** · Rule: `Late_Escaping_Check` · Item: §8.3

**Finding.** **77 candidate sites** of `echo $var` with no escaping function in Free:

```bash
grep -rnE "echo\s+\$[a-zA-Z_]" --include="*.php" includes/ \
  | grep -vE "esc_|wp_kses|absint|intval|wp_json_encode" | wc -l    # → 77
```

Not all 77 are vulnerabilities — many will be pre-escaped or integer-typed. But every one needs a decision, and Elementor widget settings (`$this->get_settings_for_display()`) are **user input from the editor**, which makes them a genuine XSS source in a multi-author site.

**Fix strategy for 2M users — do not bulk-sed this.** Wrapping output in `esc_html()` where `wp_kses_post()` was intended will **strip HTML users have deliberately entered** and visibly break their pages. Choose the escaper per context:

| Context | Escaper |
|---|---|
| Plain text | `esc_html()` |
| Attribute | `esc_attr()` |
| URL | `esc_url()` |
| **Rich text a user intentionally authored** | `wp_kses_post()` — **not** `esc_html()` |
| JS value | `wp_json_encode()` |

- [ ] Triage all 77 into: already-safe / needs-escaping / needs-`wp_kses_post`
- [ ] Fix the `needs-escaping` set first — those are real
- [ ] For rich-text fields, use `wp_kses_post()` and **visually diff a page before/after** on a staging copy
- [ ] Add `phpcs` `WordPress.Security.EscapeOutput` to CI so the count only goes down
- [ ] Repeat the sweep on Pro

---

### ✅ S8 · Remove the stranded Pro asset from Free — **DONE**

**Risk: 🟢 ZERO** · Rule: Bucket 2 · Item: B9

**Finding.** Free ships `assets/front-end/css/view/woo-product-slider.min.css`, but:

- The `Woo_Product_Slider` widget class exists **only in Pro**
- Pro ships **its own copy** of both the CSS and JS
- Free's only references are in the promo catalog (`Elements.php`, `Admin.php`), not an enqueue

So Free carries a stylesheet for a widget it does not implement. That is bucket-2 stranded weight, and to a reviewer it reads as evidence of a gated feature.

- [ ] Confirm Free never enqueues it: `grep -rn "woo-product-slider" config.php includes/ --include="*.php"`
- [ ] Confirm Pro's copy is the one actually loaded on a Free+Pro site
- [ ] Delete the Free copy
- [ ] Re-run the orphan sweep; `general`, `scroll-to-top`, `beehive-elements` are **false positives** (PHP-enqueued, not in `config.php`) — leave them alone

---

### ✅ S9 · Add ABSPATH guards to the 5 Pro files — **DONE**

**Risk: 🟢 ZERO** · Rule: `Direct_File_Access_Check` · Item: §8.6

**Finding.** Five Pro files can be requested directly:

```
includes/Traits/Pinterest_Feed.php
includes/Traits/Instagram_Feed.php
includes/Traits/Enqueue.php
includes/Extensions/particle-themes.php
includes/Elements/advance-gmap-themes.php
```

- [ ] Add to the top of each:
  ```php
  if ( ! defined( 'ABSPATH' ) ) { exit; }
  ```

**Free is clean here.** The 16 Free files the scan flags are all `index.php` "Silence is golden" stubs plus two files inside `node_modules` — **false positives, do not touch them.**

---

### ✅ S10 · Document the unlicensed bundled library — **DONE**

**Risk: 🟢 ZERO** · Rule: Guideline 1 · Item: §2.3

**Finding.** `assets/front-end/js/lib-view/drawsvg/drawsvg.js` has **no licence banner and no attribution.** An unlicensed file is all-rights-reserved by default.

This is **jQuery drawsvg** (Leonardo Santos), which is MIT — *not* Club GreenSock's commercial `DrawSVGPlugin`, despite the folder name sitting next to `gsap/`. So the code is fine; the documentation is missing.

- [ ] Restore the upstream MIT banner at the top of `drawsvg.js` and `drawsvg.min.js`
- [ ] Do the same audit across the other 22 `lib-view/` libraries — produce a table of **name · version · licence · source URL**
- [ ] Pay particular attention to `isotope`, `plyr`, `three`, `fancy-table`, `full-calendar` — each has a commercial tier whose build is **not** redistributable
- [ ] Keep that table in the repo; it is the fastest possible answer to a reviewer's licensing question

---

### ✅ S11 · Harden the version-skew branch — **DONE**

**Risk: 🟡 LOW** · Rule: Bucket 3 (latent) · Item: B1 / D14

**Finding.** [`includes/Traits/Elements.php:76`](../includes/Traits/Elements.php#L76):

```php
if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) {
```

The `&&` short-circuit means the constant is only read when Pro is active — **correct today**. But it couples two independent facts: that `pro_enabled` is true, and that the constant is defined. Anything that ever filters `eael/pro_enabled` to `true` without Pro loaded turns this into a fatal.

- [ ] Change to guard the constant directly:
  ```php
  if ( defined( 'EAEL_PRO_PLUGIN_VERSION' ) && version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) {
  ```
- [ ] **Then ask whether this branch can go.** It suppresses 5 widgets for Pro < 3.3.0. Pro is now at **7.0.3** — this compatibility shim is many majors stale. If telemetry shows no installs below 3.3.0, delete it. If you cannot prove that, leave it; a stale branch is cheap, a broken site is not.
- [ ] Same treatment for the `6.2.2`–`6.2.3` branch at [`Bootstrap.php:450`](../includes/Classes/Bootstrap.php#L450)

---

### 🟡 S12 · Triage the 65 shared function names — **WON'T DO (owner decision: BC risk)**

**Risk: 🔴 HIGH if done carelessly** · Rule: Bucket 4 / drift · Item: C2

**Finding.** 65 function names are defined in both codebases. **Most are false positives** — `__construct`, `render`, `get_icon`, `enqueue`, `content_template` are Elementor base-class overrides and are *supposed* to exist per widget.

The real drift surface is the `eael_*`-prefixed helpers:

```
eael_get_product_filterby_options    eael_get_product_orderby_options
eael_product_action_buttons          eael_product_badges
eael_product_view_popup_style        eael_register_acf_controls
eael_get_acf_repeater_options        eael_get_acf_repeater_sub_fields
eael_acf_extra_data_controls_style   eael_acf_notice_controls
```

I spot-checked `eael_product_badges` and `eael_get_product_filterby_options` — **the bodies already differ** between Free and Pro. That could mean legitimate per-widget variation, or it could mean they have drifted apart. Either way it is unresolved, and **it is the exact shape of the "security fix landed in Free but not Pro" problem.**

**Why this is HIGH risk to fix:** de-duplicating means deleting one implementation and repointing callers. If the two bodies genuinely diverged for good reasons, unifying them changes rendered output on live sites.

- [ ] For each `eael_*` duplicate, diff the two bodies in full
- [ ] Classify: **identical** (safe to unify) / **drifted** (decide which is correct) / **legitimately different** (rename one to end the ambiguity)
- [ ] **Before unifying anything**, check whether the Free version received a security fix the Pro copy did not — that is the finding that matters most
- [ ] Unify only the identical ones; leave the rest documented but untouched until you have a test for the rendered output
- [ ] Do **not** attempt this in the same release as any other change on this list

---

### 🟡 S13 · Confirm telemetry consent, then document it — **wizard consent + install beacon fixed in #897**

**Risk: 🟢 ZERO** (verification + readme) · Rule: Guideline 7 · Item: §6

**Finding.** `includes/Classes/Plugin_Usage_Tracker.php` gates on `is_tracking_allowed()` reading the `wpins_allow_tracking` option — **the mechanism is correct**. What needs confirming is that *every* outbound path checks it, and that the readme discloses what is collected.

Free contacts these hosts: `essential-addons.com` (383 refs), `wpdeveloper.com` (42), plus user-configured SaaS (`graph.facebook.com`, `api.twitter.com`, `recaptcha.net`, `youtube.com`).

**Classify these two categories separately — conflating them produces a wrong audit:**

- **Vendor telemetry** (`wpdeveloper.com`, `essential-addons.com`) → requires explicit, default-off, persisted opt-in
- **User-configured SaaS** (Facebook, Twitter, reCAPTCHA feeds) → consent is implied by the user entering credentials. **Not a violation.**

- [ ] Confirm no outbound vendor call fires on `activation`, `admin_init`, or cron **before** consent is stored
- [ ] Confirm the opt-in UI is default-off
- [ ] Confirm the "dismiss" path does **not** silently enable tracking
- [ ] Add a **Privacy** section to `readme.txt`: what is collected, where it goes, why, and how to opt out
- [ ] List every third-party service and link its Terms + Privacy Policy (Guideline 6 requirement)

---

### 🟡 S14 · Readme and upsell placement — **expired campaigns removed, ThinkRank scoped in #897**

**Risk: 🟢 ZERO** to 🟡 LOW · Rule: Guidelines 11, 12 · Item: E3, E6

**Finding.** `Tags:` currently holds **exactly 5** — at the hard maximum, with zero headroom. Adding one more is an instant `Plugin_Readme_Check` failure.

```
Tags: elementor, elementor addons, elementor widgets, elementor templates, elementor woocommerce
```

Separately, the `Promotion` extension injects teaser controls into **Elementor's own elements** (`elementor/element/section/...`, `elementor/element/common/...`, `elementor/element/column/...`) — roughly 15 injection points on elements EA does not own. Each is individually defensible under Guideline 5 as advertising; the aggregate is what a reviewer weighs under Guideline 11.

- [ ] Treat 5 tags as a **frozen budget** — document it so nobody adds a sixth
- [ ] Count the foreign-element teaser injection points; keep the number defensible and trending down
- [ ] Confirm every teaser is guarded by `! pro_enabled` at the **constructor** level (currently correct in `Promotion.php:13`)
- [ ] Confirm no teaser replaces a control that previously worked in Free — that would convert permitted advertising into a Guideline 5 trialware violation
- [ ] Confirm the BFCM campaign notice self-expires, is dismissible per-user, and does not re-arm on plugin update
- [ ] Confirm `readme.txt` describes what **Free** does; Pro features appear only under a clearly-labelled upgrade section

---

### 🟡 S15 · Track the 18 dual-shipped assets for drift

**Risk: 🔴 HIGH if unified carelessly** · Rule: Bucket 4 / drift · Item: C2 / B9

**Finding.** 18 front-end assets are shipped by **both** Free and Pro under the same filename:

```
CSS: advanced-accordion  advanced-tabs  creative-btn  fancy-text
     filterable-gallery  liquid-glass-effect  login-register  price-table
     progress-bar  team-members  woo-product-slider
JS:  advanced-accordion  advanced-tabs  data-table  filterable-gallery
     login-register  progress-bar  svg-draw
```

**This is not accidental duplication — it is by design.** Pro's copies are *additive supplements*, not replacements:

| Asset | Free | Pro | Reading |
|---|---|---|---|
| `filterable-gallery.min.css` | 26,808 b | 8,433 b | Pro adds Pro-only styles |
| `login-register.min.css` | 13,004 b | 6,577 b | same |
| `progress-bar.min.css` | 5,277 b | 1,546 b | same |
| `advanced-tabs.min.css` | 4,623 b | 5,582 b | Pro's is **larger** — verify why |

Pro's `config.php:549` loads **Free's** copy via `EAEL_PLUGIN_PATH`, then layers its own on top. That is the correct extender pattern.

**The risk is drift, not duplication.** A CSS fix landed in Free's `filterable-gallery.min.css` does not reach Pro's supplement. Over years the two diverge and Pro users get a subtly different widget.

**Do not "de-duplicate" these.** Merging them would break the additive-loading design and change rendered output on 2M sites.

- [ ] Document each pair: which selectors live in Free's base vs Pro's supplement
- [ ] Add a build check that fails if Pro's supplement redefines a selector Free already defines (silent override)
- [ ] Investigate `advanced-tabs.min.css` — Pro's copy is larger than Free's, inverting the supplement pattern; may be a full copy
- [ ] When fixing a bug in any of these 18, **check both sides in the same PR**
- [ ] Add the pair list to the API surface doc so it is visible at review time


---

### 🟠 S16 · Bundled `imagesloaded` duplicates a WordPress core library — NEW

> **Correction (#897 verification):** the claim below that core's imagesloaded v5 removed the jQuery API is **wrong**. `wp-includes/js/imagesloaded.min.js` (v5.0.0) still contains `makeJQueryPlugin` and defines `$.fn.imagesLoaded` when jQuery is present at load time. The real blockers are different: (1) core's `imagesloaded` handle declares **no jQuery dependency**, so load order is not guaranteed; (2) `Asset_Builder` concatenates `lib` files into cached per-post bundles in `uploads/`, so switching to a handle means changing the dependency model and regenerating cached assets on every site. Still deferred, for those reasons.

**Risk: 🔴 HIGH to fix · Rule: Guideline 13 · Found during S10 inventory**

**Finding.** Free bundles `assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js` and enqueues it from **8 separate `config.php` entries** (lines 28, 291, 325, 427, 596, 645, 992, 1295).

WordPress core already registers this library:

```php
// wp-includes/script-loader.php:990
$scripts->add( 'imagesloaded', '/wp-includes/js/imagesloaded.min.js', array(), '5.0.0', 1 );
```

Guideline 13 says plugins must use WordPress-bundled libraries rather than shipping their own copy. This is a genuine violation.

**Why this is NOT a quick fix — read before touching it.**

Core ships **v5.0.0**. The bundled copy is **v4.x**. imagesloaded **v5 removed the jQuery plugin API entirely** — it is vanilla-JS only. Essential Addons calls the jQuery form in at least 12 places across 8 source files:

```js
$facebook_gallery.imagesLoaded().progress( function () { … } )   // src/js/view/facebook-feed.js:10
$twitter_feed_gallery.imagesLoaded().progress( function () { … } ) // src/js/view/twitter-feed.js:25
$isotope_products.imagesLoaded().progress( function () { … } )   // src/js/view/woo-product-gallery.js:91
$gallery.imagesLoaded().progress( function () { … } )            // src/js/view/post-grid.js:15
// …plus load-more.js (×2), betterdocs-category-grid.js, woo-product-image.js
```

**Swapping to `wp_enqueue_script( 'imagesloaded' )` today would break every one of those widgets on 2M sites.**

**Correct sequencing:**

- [ ] Rewrite all 12 jQuery-style call sites to the vanilla v5 API (`imagesLoaded( el, cb )`) in `src/js/`
- [ ] Rebuild assets; verify each of the 8 affected widgets renders and lazy-loads correctly
- [ ] Only *then* drop the bundled copy and switch the 8 `config.php` entries to the core `imagesloaded` handle
- [ ] Note `woo-product-image.js:382` already feature-detects (`typeof $.fn.imagesLoaded === "function"`) — that call site degrades safely and can migrate first as a pilot
- [ ] Ship in its own release. Do not combine with any other item on this list

**Interim option:** if the migration cannot be scheduled soon, document in `readme.txt` why the bundled copy exists. That does not satisfy Guideline 13, but it shows intent if a reviewer asks.


---

## 3. Suggested release sequencing

Do not ship all of this at once. For 2M installs, batch by risk:

| Release | Contents | Rationale |
|---|---|---|
| **Patch — this week** | S1, S2, S8, S9, S10 | All 🟢 ZERO risk. Closes both licensing blockers and removes dead weight. Nothing can regress. |
| **Patch — next** | S4, S11 | 🟡 LOW. Closes the fatal path. Guard-only, additive. |
| **Minor** | S3 (verify), S13, S14 | Packaging + disclosure. No code-path change. |
| **Minor + runway** | S5, S6, S7 | 🟠 MEDIUM. Needs the full test matrix and a staged rollout. |
| **Major / scheduled** | S12, S4 long-term refactor | 🔴 Needs deprecation cycle and coordinated Free+Pro release. |

**Ship S1 and S2 first.** They are the two genuine WP.org removal risks, and both are zero-regression.

---

## 4. Widget & feature-level inventory

The structural audit above says *how* the two plugins connect. This section answers the separate question: **is any Free widget actually a Pro widget, or vice versa?** Every widget, extension, template directory and view asset was enumerated on both sides.

**Result: the widget-level separation is architecturally correct.** One stranded asset (S8), one drift risk (S15), nothing else.

| # | Check | Result |
|---|---|---|
| **W1** | Widget class files: Free 67 · Pro 43 | ✅ **Zero filename overlap** — no widget is implemented twice |
| **W2** | Does Free's `config.php` name a class that lives only in Pro? | ✅ **Zero.** Every class Free declares exists in Free |
| **W3** | How do Pro widgets register? | ✅ Free publishes `apply_filters('eael/registered_elements', …)` ([`Bootstrap.php:115`](../includes/Classes/Bootstrap.php#L115)); Pro injects via `add_filter` ([Pro `Bootstrap.php:52`](../../essential-addons-elementor/includes/Classes/Bootstrap.php#L52)). **Textbook extension-point pattern** |
| **W4** | 7 config keys appear in both (`adv-tabs`, `adv-accordion`, `filter-gallery`, `image-accordion`, `image-masking`, `info-box`, `svg-draw`) | ✅ Pro declares **assets only, no `class`** — legitimate enrichment of Free widgets |
| **W5** | Free `Template/` directories (11) | ✅ **All in use.** Resolved at runtime by `process_directory_name()` from `get_name()`, not by config key |
| **W6** | Free view assets not owned by any Free widget | ⚠️ **Exactly one** — `woo-product-slider.min.css` → **S8** |
| **W7** | Assets shipped by both Free and Pro | ⚠️ **18 pairs** — additive supplements, not copies → **S15** |
| **W8** | Pro's `config.php` references Free's `EAEL_PLUGIN_PATH` 35× | ✅ Safe — Pro's config loads **only** inside `eael/before_init`, a hook Free fires. Never evaluated when Free is absent |
| **W9** | Free's promo catalog advertises 41 Pro widgets | ✅ All 41 exist in Pro. **No phantom features advertised** |
| **W10** | Pro without Free | ✅ Bootstrap gated behind Free's hook + `Notice::failed_to_load()` admin notice. **Degrades cleanly, no fatal** |

### Two traps this inventory exposed

Both nearly produced false findings, and both matter for anyone re-running this audit:

**Template directories are resolved dynamically.** `process_directory_name()` ([`Template_Query.php:45`](../includes/Traits/Template_Query.php#L45)) converts a widget's `get_name()` into a directory name: `eael-post-grid` → `Post-Grid`. A static grep for the directory name finds nothing and reports 9 false orphans. **All 11 are live.**

**Legacy widget names are still load-bearing.** `Product_Grid::get_name()` returns **`'eicon-woocommerce'`** — not `product-grid` — so `includes/Template/Eicon-Woocommerce/` (16 files) is actively used today. There is also a `replace_widget_name()` BC map ([`Elements_Manager.php:220`](../includes/Classes/Elements_Manager.php#L220)) covering ~10 renamed widgets. **Deleting anything that looks orphaned by name would break pages saved on 2M sites.** Always resolve through `get_name()` and the rename map before removing a template directory or asset.

---

## 5. Verified clean — do not re-litigate

These were checked and passed. Recording them stops the next audit spending time here.

| Item | Result |
|---|---|
| **A1** Namespace separation | ✅ Free `Essential_Addons_Elementor\` · Pro `Essential_Addons_Elementor\Pro\` — distinct |
| **A2** Autoloader guards | ✅ Both `file_exists()`-guarded ([`autoload.php:30`](../autoload.php#L30)). Prefix shadowing is harmless |
| **A3** Text domains | ✅ Distinct, each matching its own slug |
| **A4** Constant prefixes | ✅ `EAEL_*` vs `EAEL_PRO_*`, no collisions |
| **B7** Registry integrity | ✅ Every class named in `config.php` exists in Free — zero missing |
| **C5** AJAX name uniqueness | ✅ No duplicate action names across the pair |
| **D1–D3** Detection contract | ✅ Free reads `apply_filters('eael/pro_enabled', false)`; Pro is sole setter ([`Bootstrap.php:49`](../../essential-addons-elementor/includes/Classes/Bootstrap.php#L49)) |
| **D5** Dependency direction | ✅ Pro imports Free's `Helper` rather than copying it — correct direction, correct mechanism |
| **G15** Version alignment | ✅ Header `6.8.3` = `Stable tag 6.8.3` = `EAEL_PLUGIN_VERSION 6.8.3` |
| **§8.5** Pro deserialization | ✅ `Updater::safe_unserialize()` uses `allowed_classes => false` — already hardened |
| **§5.2** Remote code in Free | ✅ No CDN-loaded executables, no self-updater in Free, no `eval`/`base64_decode` chains |
| **Free ABSPATH** | ✅ Clean — the 16 flagged files are `index.php` stubs + `node_modules`, all false positives |

---

## 6. Not audited — state this honestly

- **Runtime behaviour.** D10–D12 (graceful degradation) require *executing* Free-only with `WP_DEBUG`, not static analysis. **Do this before the next release** — it is the reviewer's exact scenario.
- **The built zip.** S3 is a verification step that has not been run.
- **The other 22 bundled libraries.** Only GSAP and drawsvg were licence-checked.
- **Pro's full security surface.** Pro got a targeted pass (ABSPATH, deserialization, updater); it has not had the complete §8 treatment that Free received.
- **Translations / `.pot` freshness.**
- **Third-party API response handling** for the SaaS integrations.
- **The 77 escaping candidates** were counted, not individually triaged.

---

## 7. Standing CI guards

Once the backlog is clear, keep it clear. Add these as pipeline checks so regressions fail the build rather than reaching 2M sites:

```bash
# 1. No unguarded Pro constants in Free
grep -rn "EAEL_PRO_" --include="*.php" includes/ | while IFS=: read -r f l r; do
  s=$((l>15?l-15:1)); e=$((l+3))
  sed -n "${s},${e}p" "$f" | grep -qE "defined\(\s*'?EAEL_PRO_|defined\(\s*\\\$|class_exists|pro_enabled" \
    || { echo "FAIL unguarded: $f:$l"; exit 1; }
done

# 2. No Pro listener without a Free emitter
# 3. No new readme tag beyond 5
# 4. phpcs WordPress.Security.EscapeOutput count must not increase
# 5. Built zip contains no node_modules / .git / src / *.map
# 6. Header License field matches readme License field
```

---

## 8. Bundled library licence inventory

**Superseded by [`../third-party-licenses.txt`](../third-party-licenses.txt)**, which ships in the plugin and lists every bundled library with its version, licence and upstream. It was verified against upstream repositories and npm metadata during the #897 pass, not inferred from banner grep.

The earlier table in this section contained errors, now corrected:

| Library | Earlier entry | Verified |
|---|---|---|
| code-snippet | MIT | **highlight.js 10.7.0 — BSD-3-Clause** |
| dom-purify | ISC | **DOMPurify 3.2.6 — Apache-2.0 OR MPL-2.0** |
| embed | 15.1.0, MIT | **@typeform/embed 0.16.0 — published with no licence** (see §9, T1) |
| typeform | 15.1.0, MIT | Unreferenced duplicate of `embed/` in Lite and Pro — **deleted** |
| marquee | no banner | EA code built on GreenSock's `horizontalLoop()`; **loaded by Pro** via `EAEL_PLUGIN_PATH` (Pro `config.php:1036`) — do **not** delete |
| image-zoom | no banner | EA code adapted from a public CodePen Pen — **MIT** under CodePen's licence terms |
| morphext | no banner | MIT — copyright holder is **Ian Lai** |
| inview | no banner | WTFPL (LICENSE) / ISC (package.json) |

---

## 9. Issue #897 — PM compliance audit: verification and fixes

Source: [WPDevelopers/essential-addons-for-elementor-lite#897](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/897). Every claim was checked against the code, and against **Plugin Check 2.1.0 run on the built package** (`.distignore` applied), before and after the fixes.

### Plugin Check, built package

| | Before | After |
|---|---|---|
| Total rows | 207 | **131** |
| Errors | 151 | **72** |
| `wp_function_not_compatible_with_requires_wp` | 75 | **0** |
| `missing_direct_file_access_protection` | 1 | **0** |
| `OffloadedContent` | 1 | **0** |
| `MissingTranslatorsComment` | 2 | **0** |
| `SafeRedirect` / `error_log_debug_backtrace` | 2 / 1 | **0 / 0** |
| `unexpected_markdown_file` / `missing_composer_json_file` | 1 / 1 | **0 / 0** |
| `EscapeOutput.OutputNotEscaped` | 71 | 71 — deferred (S7 decision) |

`NonPrefixedHooknameFound` rose 9 → 19. **Not a regression:** the 10 new rows are unchanged `wpdeveloper_*_for_` hooks in `WPDeveloper_Notice.php`. Plugin Check auto-detects allowed prefixes from the code, and the deleted `bfcm-pointer.php` was what supplied `wpdeveloper`. Renaming those hooks would break listeners.

### Per item

| # | PM claim | Verified | Action | BC risk |
|---|---|---|---|---|
| G5 | Trialware clean | ✅ Spot-checked `enableProSorter` (Pro-only), `Woo_Cart` early return, no licence endpoints | None | — |
| 0 | Remove `LicenseManager` shim | ✅ Exists (`Bootstrap.php`), only runs for Pro 6.2.2–6.2.3 | **Deferred** — added 2025-05-20 so those Pro versions receive updates; removing it strands them | Would affect paying users |
| 1 | No `License:` header | ✅ (fixed earlier, S1) | URI aligned to `gnu.org` in header **and** readme | None |
| 2 | GSAP non-GPL, dead | ✅ (deleted earlier, S2). Confirmed Pro never loads Lite's GSAP | — | None |
| 3 | 12 libs lack licence headers | ✅ | `third-party-licenses.txt` added (`.txt`, because Plugin Check flags unexpected root `.md`). **PM's CodePen concern is incorrect** — public Pens are MIT | None |
| 4 | Wizard consent implicit | ✅ "Proceed" doubled as consent; opt-out was a text link | Two equal-weight buttons, **Allow & Continue** / **Continue without sharing**; disclosure rewritten; bundle rebuilt (fresh build verified content-identical to committed dist before the change) | New-install onboarding only |
| 5 | Install beacon un-consented | ✅ | Gated on the same `wpins_allow_tracking` consent the tracker uses | None |
| 6 | Expired campaigns | ✅ BFCM ended 2025-12-04, summer ended 2026-06-25 | Removed `bfcm-pointer.php`, its include, the summer notice, its CSS and logo | None — neither could display |
| 7 | ThinkRank on dashboard / list screens | ✅ | Implemented the PM's "at minimum": list-screen banner removed; dashboard widgets open collapsed once per user. **Unreported:** the Gutenberg "Configure SEO" panel is the same class of surface — needs a product decision | Promo only |
| PCP | `Requires at least` | ✅ Only `wp_date`/`wp_timezone` (5.3) | **5.0 → 5.3**, not the suggested 6.0, which would also stop updates for working 5.3–5.9 sites | Sites < 5.3 already fatal in Event Calendar |
| PCP | `utf8_encode()` | ✅ (already behind an mbstring check) | Replaced with a byte-identical conversion, proven equal to `utf8_encode`/`mb_convert_encoding`/`iconv` over all 256 bytes + 5,000 random strings | None |
| PCP | Escaping, 7 files | ✅ 71 rows on the current tree | **Deferred** (S7 decision) | Output could change |
| PCP | `config.php` ABSPATH | ✅ | Guard added | None |
| PCP | `Tested up to` | Already 7.1 | — | — |
| PCP | Plugin name mismatch | ✅ | **Deferred** — the readme title is the WP.org listing name; marketing decision | — |
| PCP | Description trimmed | ❌ Not on current readme: 2,225 words vs a **2,500-word** limit (the parser counts words) | None | — |
| PCP | Angie nonce | ✅ | Left for #877 as assigned | — |
| PCP | Login/Registration nonce | ✅ False positive — WooCommerce verifies its nonce before `woocommerce_save_account_details` | Justified `phpcs:ignore` | None |
| PCP | `wp_redirect` → `wp_safe_redirect` | ✅ Flagged, **but the suggested fix breaks sites** — external logout URLs are a feature and the nonce is bound to the exact URL | Kept `wp_redirect`, justified `phpcs:ignore` | Fix as written would break users |
| PCP | `debug_backtrace` | ✅ now at `Helper.php:634` | Justified `phpcs:ignore` | None |
| 8 | No source link | ✅ | `== Development ==` added (merged into the description by the parser; still under the word limit) | None |
| 9 | `imagesloaded` → core handle | ✅ Duplicate confirmed | **Deferred** — see the S16 correction | Asset pipeline on all sites |

### New findings the issue did not cover

- **T1 — Typeform SDK is unlicensed.** `embed/embed.js` is `@typeform/embed` **0.16.0** (exact literal match), published with no licence. Loaded by the live TypeForm widget, so it cannot simply be deleted. Path: move to 0.17.x (LGPL-3.0-only, same 0.x API — verify) or 1.26+ (MIT, new API).
- **`marquee.js` is a Pro asset living in Lite** — Pro loads it through `EAEL_PLUGIN_PATH`. Moving it needs a coordinated Lite + Pro release with a version gate.
- **`issue-wpml.md` was shipping** in the zip — now in `.distignore`. **`composer.json`** now ships alongside `vendor/`.
- **`vendor/priyomukul/wp-notice` declares no licence** — declare one upstream.
- **No external-services section in the readme** for reCAPTCHA, Cloudflare Turnstile, the Facebook/Twitter feeds, Business Reviews, NFT Gallery and Typeform. WP.org reviewers routinely require one (Guidelines 6/7).
- **`Interactive_Circle.php:123` unordered placeholders** — left as is, because changing the msgid drops existing translations.
- **Changelog is 4,798 of 5,000 words** — it will start being trimmed within a few releases; move old entries to `changelog.txt`.
