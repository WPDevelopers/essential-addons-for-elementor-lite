# Consent Flow — WPInsights opt-in & data disclosure

> Triage / planning doc for [Issue #808](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/808). Documents where consent state lives, how the two surfaces (admin notice + Quick Setup wizard) record the decision, the disclosure copy shown to users, and the gaps that need follow-up (revocation UI, GDPR posture). Pairs with the WPInsights integration doc — read them together.

## Context

The plugin sends usage telemetry to WPInsights, gated on user consent. Two consent surfaces exist (admin notice via `WPDeveloper_Notice`, Quick Setup wizard step) — both ultimately write to the same WP option (`wpins_allow_tracking`). The notice copy includes a "What we collect" affordance that slides open the disclosure inline; the wizard's consent step routes through a distinct AJAX endpoint but shares the storage. Today nothing documents which option keys hold the decision, how revocation works after consent, or what the GDPR posture is.

## Verified facts

All facts from the issue verified against the current codebase. Adding the storage keys and surfaces the issue listed as "What's missing":

- **Tracker library:** vendored via Composer — `priyomukul/wp-notice` v2.x-dev ([composer.json line 12-16](../../composer.json)). The `Plugin_Usage_Tracker` class is `WP_Notice`'s shipped tracker, copied into `includes/Classes/Plugin_Usage_Tracker.php`.
- **Tracker instantiation:** `Helper::start_plugin_tracking()` in [`includes/Traits/Core.php` line 129-144](../../includes/Traits/Core.php#L129) — instantiated with `opt_in => true, goodbye_form => true, item_id => '760e8569757fa16992d8'`.
- **Default `require_optin`:** `true` ([Plugin_Usage_Tracker.php line 83](../../includes/Classes/Plugin_Usage_Tracker.php#L83)). No tracking without explicit consent.
- **API endpoint:** `https://send.wpinsight.com/process-plugin-data` ([Plugin_Usage_Tracker.php line 26](../../includes/Classes/Plugin_Usage_Tracker.php#L26)). Note singular `wpinsight.com`, not `wpinsights.com`.
- **Cron event:** `put_do_weekly_action` ([line 81](../../includes/Classes/Plugin_Usage_Tracker.php#L81)) — scheduled on consent grant, cleared on revoke / deactivation.
- **Storage keys (the issue's main "What's missing" gap):**
  - `wpins_allow_tracking` — **the consent decision**. WP option, value is an array `[plugin_name => plugin_name]`. Read in `is_tracking_allowed()` ([line 248](../../includes/Classes/Plugin_Usage_Tracker.php#L248)); written in `set_is_tracking_allowed()` ([line 290](../../includes/Classes/Plugin_Usage_Tracker.php#L290))
  - `wpins_block_notice` — **suppresses the opt-in notice** after the user decides. WP option, array `[plugin_name => plugin_name]`. Written in `update_block_notice()` ([line 705](../../includes/Classes/Plugin_Usage_Tracker.php#L705))
  - `wpins_last_track_time` — array of per-plugin last-send timestamps ([line 326](../../includes/Classes/Plugin_Usage_Tracker.php#L326))
  - `wpins_deactivation_reason_<plugin>` / `wpins_deactivation_details_<plugin>` — goodbye-form payload; deleted after send ([lines 138-147](../../includes/Classes/Plugin_Usage_Tracker.php#L138))
  - **No dedicated revocation flag** — opt-out is via *removing* the plugin entry from `wpins_allow_tracking`, OR via setting `wpins_opt_out` flag inside another plugin's options array (checked by `has_user_opted_out()` at [line 299](../../includes/Classes/Plugin_Usage_Tracker.php#L299))
- **Surface 1 — Admin notice:** rendered via `wpdeveloper_optin_notice_for_<plugin>` action ([line 166](../../includes/Classes/Plugin_Usage_Tracker.php#L166)) fired by `WPDeveloper_Notice::handle_notice()` ([WPDeveloper_Notice.php line 390](../../includes/Classes/WPDeveloper_Notice.php#L390)). Notice template at `Plugin_Usage_Tracker.php` line 627-638. Yes/No buttons are nonced URLs (`_wpnonce_optin_<plugin>`) handled by `clicked()` ([line 663-689](../../includes/Classes/Plugin_Usage_Tracker.php#L663)).
- **Surface 2 — Quick Setup wizard:** AJAX endpoint `wp_ajax_enable_wpins_process` ([WPDeveloper_Setup_Wizard.php line 23](../../includes/Classes/WPDeveloper_Setup_Wizard.php#L23)) — nonce verified (`essential-addons-elementor`), `manage_options` capability required, payload parsed, then calls `wpins_process()` which instantiates a tracker with `opt_in => true` and calls `set_is_tracking_allowed(true)`. Both surfaces converge on the same `wpins_allow_tracking` option.
- **Disclosure copy (the "What we collect" content):** hardcoded in [`Helper::start_plugin_tracking()` lines 137-141](../../includes/Traits/Core.php#L137):
  - **Notice body:** *"Want to help make Essential Addons for Elementor even more awesome? You can get a 10% discount coupon for Pro upgrade if you allow."*
  - **Extra notice (the slide-open "What we collect"):** *"We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, I promise."*
  - Both wrapped in `__()` — translatable.
- **Notice consent link:** rendered as `<a href="#" class="wpinsights-<plugin>-collect">What we collect.</a>` ([line 624](../../includes/Classes/Plugin_Usage_Tracker.php#L624)). Click is JS-only (inline script at line 637) — slides open `.wpinsights-data` block. No separate page.
- **`DISABLE_WP_CRON` interaction:** `$this->disabled_wp_cron = defined('DISABLE_WP_CRON') && DISABLE_WP_CRON == true;` ([line 78](../../includes/Classes/Plugin_Usage_Tracker.php#L78)) — when WP cron disabled, `schedule_tracking()` returns early ([line 102-104](../../includes/Classes/Plugin_Usage_Tracker.php#L102)) and `enable_self_cron` flag is set. The "self-cron" path is reserved but **the actual self-cron invocation isn't wired up in Lite** — `force_tracking()` exists ([line 200](../../includes/Classes/Plugin_Usage_Tracker.php#L200)) but its `admin_init` registration is commented out ([line 164](../../includes/Classes/Plugin_Usage_Tracker.php#L164)). On `DISABLE_WP_CRON` sites, **tracking effectively does not run** — silent gap.
- **Goodbye / deactivation form gating:** `register_deactivation_hook` → `deactivate_this_plugin()` ([line 125-154](../../includes/Classes/Plugin_Usage_Tracker.php#L125)) checks `is_tracking_allowed()` first; if consent revoked, the deactivation reason / details options are NOT sent (but they were collected and stored locally). Form itself is rendered unconditionally on `admin_footer-plugins.php` ([line 171](../../includes/Classes/Plugin_Usage_Tracker.php#L171)).

## What's missing

Gaps the issue captures plus a few discovered during verification — these need to land in a follow-up doc or close as "documented limitation":

- **No UI surface for revocation after consent is given.** Once a user clicks "Sure, I'd like to help" the notice is suppressed via `wpins_block_notice` and never re-shows. To revoke, the user must either: (a) delete the `wpins_allow_tracking[essential-addons-for-elementor-lite]` option key manually, OR (b) be a developer who knows about `wpins_opt_out` in other plugin options. **There is no admin-page setting to opt out.** Worth flagging as a GDPR weakness.
- **GDPR posture assessment.** Defaults align with GDPR (opt-in required, no tracking by default), but:
  - No banner-style explicit consent — the admin notice can be dismissed and tracking stays off, which is consent-positive
  - No data-deletion request endpoint
  - No record of *when* consent was given (no timestamp stored alongside `wpins_allow_tracking`)
  - Withdrawal pathway exists but only via admin notice re-display logic — which the `wpins_block_notice` suppression actively blocks
- **`DISABLE_WP_CRON` silent failure.** On hosts with `DISABLE_WP_CRON`, no tracking happens because `schedule_tracking()` returns early and the self-cron path isn't wired. Either the documentation should flag this as expected, or `force_tracking()` registration should be uncommented.
- **Consent state doesn't carry a timestamp.** When was consent granted? Not stored. Useful for GDPR audit trail.
- **Multi-plugin coordination.** `wpins_allow_tracking` is an array keyed by plugin name — multiple plugins using the same `WP_Notice` library write to the same option. The plugin's consent affects only its own slot (`'essential-addons-for-elementor-lite'`), but revocation logic via `wpins_opt_out` is *cross-plugin* — one plugin can opt out the user from all plugins.
- **No PHPCS / sanitisation review yet.** The `clicked()` handler reads `$_GET['plugin']` / `$_GET['plugin_action']` with nonce verification but sanitises via `sanitize_text_field()` after the nonce check — order is correct. Worth a quick security review pass.
- **`item_id` field meaning.** `'760e8569757fa16992d8'` ([Core.php line 134](../../includes/Traits/Core.php#L134)) — hex string sent to WPInsights API. What does the receiving service do with it? Documentation needed for WPInsights side.
- **Email-marketing consent is separate.** `marketing_optin` field in `clicked()` ([line 184](../../includes/Classes/Plugin_Usage_Tracker.php#L184)) is stripped from the URL before redirect — but where is its consent state stored? Likely in the `wpdeveloper_notices_options` array, separate from `wpins_allow_tracking`. The disclosure copy mentions sending an email for the discount coupon — that's a separate marketing consent that the user isn't asked about explicitly.

## Proposed location

`docs/architecture/consent.md` — matches the issue's proposal, follows the precedent from #806 (`admin-notices.md`) and #807 (`quick-setup.md`).

## Acceptance — what "done" looks like

- [ ] `docs/architecture/consent.md` written (this doc fulfills the planning role; a richer "how it works" walkthrough may follow)
- [ ] Option keys documented: `wpins_allow_tracking`, `wpins_block_notice`, `wpins_last_track_time`, `wpins_deactivation_*`, `wpins_opt_out` shared-flag mechanism — ✅ done above
- [ ] Both consent surfaces (admin notice, Quick Setup wizard) traced to the same storage write — ✅ done above
- [ ] Disclosure copy quoted with file:line reference — ✅ done above
- [ ] Revocation flow documented OR flagged as "no UI surface" — ✅ flagged
- [ ] GDPR posture assessment — ⚠️ partial; full legal review is a separate ticket
- [ ] `DISABLE_WP_CRON` silent-failure flagged — ✅ done
- [ ] `marketing_optin` separate-consent question raised — ✅ done
- [ ] Cross-link to WPInsights integration doc (once that doc lands) — pending
- [ ] Cross-link to [`quick-setup.md`](quick-setup.md) — done in this doc's intro

## Pairs with

- **WPInsights integration doc** — read together. That doc covers what data is sent, the cron schedule, response handling. This doc covers *why* the data is sent (consent).
- **`quick-setup.md`** — the wizard's consent step is documented there; that doc and this one converge on `wpins_process()` as the single write path.

## Related

- Part of the cross-cutting docs initiative ([#804](https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues/804)–#807, #808, #810).
- Vendored library: `priyomukul/wp-notice` v2.x-dev ([GitHub](https://github.com/priyomukul/wp-notice)) — upstream changes affect this doc.
- Admin notices system: [`docs/architecture/admin-notices.md`](admin-notices.md) — `WPDeveloper_Notice` is the dispatcher; the consent notice is one of its `opt_in` case branches.

## Out of scope

- Full GDPR legal review (separate ticket; consult counsel for jurisdiction-specific requirements)
- Adding a revocation UI surface (engineering follow-up, not docs)
- Migrating `wpins_opt_out` to a more discoverable per-plugin option key (would break compatibility with other `WP_Notice`-using plugins)
- Documenting the WPInsights server-side API (separate repo / out of plugin scope)
