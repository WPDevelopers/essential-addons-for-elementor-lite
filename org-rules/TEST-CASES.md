# Test Cases — `wporg-compliance` (Free 6.8.3 + Pro 7.0.3)

Run 2026-09-14 on `ea-dev.local` — WP 7.1, PHP 8.5, Elementor 4.2.4, WooCommerce 11.
**Lite+Pro** = both active · **Lite only** = Pro inactive · **Older Pro** = new Lite + a Pro that does not register the new load-more filters (simulated).

## Licence & package
| ID | Steps | Expected | Result |
|---|---|---|---|
| T01 | Open `essential_adons_elementor.php` header | `License: GPLv3`, `License URI: https://www.gnu.org/licenses/gpl-3.0.html` | PASS |
| T02 | Open `readme.txt` | `Requires at least: 5.3`, same License URI, `== Development ==` with GitHub link | PASS |
| T03 | Open URLs: `lib-view/gsap/Draggable.min.js`, `lib-view/typeform/embed.min.js`, `css/view/woo-product-slider.min.css`, `includes/bfcm-pointer.php`, `assets/admin/images/eael-bfcm-logo.png` | All 404 | PASS |
| T04 | Open `third-party-licenses.txt` | Loads; lists bundled libraries | PASS |
| T05 | Build zip with `.distignore` | No `org-rules/`, `issue-wpml.md`, `node_modules/`, `src/`; has `composer.json`, `third-party-licenses.txt` | PASS |
| T06 | Open Lite `config.php` and Pro `Traits/Pinterest_Feed.php`, `Instagram_Feed.php`, `Enqueue.php`, `Load_More.php`, `Extensions/particle-themes.php`, `Elements/advance-gmap-themes.php` directly | Blank page, no PHP output | PASS |

## Lite-only safety
| ID | Steps | Expected | Result |
|---|---|---|---|
| T07 | Lite only: POST `load_more` with `template_info[dir]=pro` | JSON `Invalid template`, no fatal | PASS |
| T08 | Lite only: open front page, Dashboard, Posts, Pages, EA Dashboard, Plugins | All load, no fatal | PASS |

## Privacy (tracking consent)
| ID | Steps | Expected | Result |
|---|---|---|---|
| T09 | Site not opted in → open `admin.php?page=eael-setup-wizard` | Buttons **Allow & Continue** + **Continue without sharing**, same size; no "Skip This Step" / "Proceed to Next Step" | PASS |
| T10 | Click **Continue without sharing** | No `enable_wpins_process` AJAX; wizard goes to next step | PASS |
| T11 | Reload → click **Allow & Continue** | One `enable_wpins_process` AJAX | PASS |
| T12 | Install Templately from wizard with tracking OFF / ON | OFF: no request to `essential-addons.com/templately-install-quick-setup` · ON: request sent | PASS |

## Admin notices & promotion
| ID | Steps | Expected | Result |
|---|---|---|---|
| T13 | Dashboard (Lite only, Lite+Pro) | No Black Friday pointer, no Summer 2026 notice | PASS |
| T14 | Posts list, Pages list | No ThinkRank / xSpeed banner | PASS |
| T15 | EA Dashboard page | Page loads; ThinkRank banner may show here | PASS |
| T16 | Log in as a new admin → Dashboard | "SEO Check" + "Speed Check" widgets start collapsed | PASS |

## Load More (logic moved to Pro)
| ID | Steps | Expected | Result |
|---|---|---|---|
| T17 | Post Grid, Load More (Lite only, Lite+Pro) | Items 3 → 6, no JS error | PASS |
| T18 | Post Block, Load More (Lite+Pro) | Items 3 → 6 | PASS |
| T19 | Dynamic Gallery, Load More (Lite+Pro) | Items 3 → 6; response has `found_posts` marker | PASS |
| T20 | T18 + T19 with **Older Pro** | Works; AJAX response byte-identical to T18/T19 | PASS (simulated) |
| T21 | New Pro 7.0.4 with **old Lite 6.8.3** → Post Block + Dynamic Gallery Load More | Works (old Lite code runs, Pro handler idle) | Manual — not run |
| T22 | Unit: Pro handler vs Lite legacy copy, 6 Post Block / Dynamic Gallery input sets | Identical settings, query args, taxonomy map | PASS |

## Assets moved to Pro
| ID | Steps | Expected | Result |
|---|---|---|---|
| T23 | Woo Product Carousel, Autoplay + Slide + Marquee ON (Lite+Pro) | GSAP marquee (`eael-marquee-carousel`, no `no-gsap`), `eaelmarque` loaded, no JS error | PASS |
| T24 | Same page, Lite only | Swiper fallback (`no-gsap`), no JS error | PASS |
| T25 | 360 Degree Photo Viewer (Lite+Pro) | `THREE` loaded, viewer renders, no JS error | PASS |

## Code quality
| ID | Steps | Expected | Result |
|---|---|---|---|
| T26 | Plugin Check on built Lite zip | No new errors vs. before the work; `wp_function_not_compatible`, `missing_direct_file_access`, `OffloadedContent`, `MissingTranslatorsComment`, `SafeRedirect`, `debug_backtrace`, `unexpected_markdown_file`, `missing_composer_json_file` = 0 | PASS — 131 rows / 72 errors (was 207 / 151); remaining errors = 71 deferred escaping + 1 placeholder |
| T27 | `php -l` all changed PHP files; scan added lines for PHP > 7.0 syntax | Clean | PASS |
| T28 | Filterable Gallery `utf8_encode` replacement vs `utf8_encode`/`mb_convert_encoding`/`iconv` (256 bytes + 5,000 random strings) | Identical | PASS |
| T29 | `debug.log` during all tests | No PHP error from changed code | PASS (1 pre-existing warning, see note) |
| T30 | Login/Register logout to an external `redirect_to` URL | Still redirects there (code unchanged; comment only) | Manual — not run |

**Notes (pre-existing, not caused by this work):** `Undefined array key "orderby"` at `Ajax_Handler.php:180` (2025 code, Post Grid load more when `orderby` is not saved) · wizard page JS errors come from WooCommerce `wc-entities.js` and core `svg-painter.js` (same with the original bundle) · Google Fonts blocked by CORS on the local site.
