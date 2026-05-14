---
description: Audit wp_ajax_nopriv handlers for visibility leaks and other unauthenticated-request vulnerabilities.
argument-hint: [optional-handler-or-file]
---

# Audit nopriv AJAX — $ARGUMENTS

Use the `nopriv-ajax-hardening` skill at `.claude/skills/nopriv-ajax-hardening/SKILL.md`.

If `$ARGUMENTS` is provided, focus the audit on that specific handler or file (e.g. `ajax_load_more` or `includes/Traits/Ajax_Handler.php`). Otherwise, run the full plugin sweep starting with the audit greps in the skill.

Follow the skill in order:
1. Run audit greps to enumerate `wp_ajax_nopriv_*` handlers and dangerous `WP_Query` patterns
2. For each handler, walk the 5-question audit checklist
3. Apply the fix pattern (strip-and-redefault + ID coercion) where issues are found
4. Add `SECURITY:` inline comments on every replaced `'any'`
5. Verify with a PoC request as an anonymous visitor — response must contain only public content
6. Smoke-test the legitimate public flow and the logged-in flow if the handler is dual-registered

Output: list of handlers reviewed · issues found per handler (Critical / Important / None) · fixes applied (or recommended) · PoC verification result.

Do not edit files unless the user explicitly says "fix it" / "apply" — by default this command produces an audit report.
