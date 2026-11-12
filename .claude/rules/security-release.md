# Security Releases — Changelog & Upgrade Notice

Applies to every release that ships a security fix, whoever reported it (Patchstack, Wordfence, WPScan, internal audit, QA). Pro follows the same rule in its own repo (`essential-addons-elementor/.claude/rules/security-release.md`), where the Upgrade Notice is set in the license store instead of through trunk; when a fix spans both plugins, apply it to both releases.

## Always: one vague changelog line

Every release containing any security fix gets exactly this line in its `readme.txt` changelog entry, after the other `Improved:` lines:

```
- Improved: Security Enhancement
```

Never name the widget, parameter, role, endpoint, vulnerability class or CVE in the changelog, release notes, commit messages, PR titles or Slack announcements. The changelog is public the moment it ships, often before most sites have updated.

## High or Critical (CVSS ≥ 7.0): also add an Upgrade Notice

WordPress core shows the readme's `== Upgrade Notice ==` text next to the update on **Dashboard → Updates** and in the **View version details** popup, on sites still running an older version. It is the only in-dashboard message that reaches sites that haven't updated: we cannot add an admin notice to versions already installed, and WordPress.org forbids fetching remote notices.

Add an entry for the release version under `== Upgrade Notice ==` (the last section of `readme.txt`):

```
== Upgrade Notice ==

= X.Y.Z =
Important security update for all users. Please update now.
```

Rules for the text:
- **Vague, same as the changelog.** Say it is a security update and to update now. Never describe what was vulnerable.
- **Plain text, under 300 characters.** Core strips HTML. Keep to one or two sentences.
- **Add one action line only if the fix changes behaviour users must act on**, phrased as an instruction, not an explanation. Example from 6.8.8: `If your registration form uses a custom New User Role, open that page in Elementor and click Update once after updating.`
- **Keep only the latest three entries** in the section. Older ones are never shown.
- Medium / Low fixes: changelog line only, no Upgrade Notice.

Severity is the reporter's CVSS score. If no score was given, treat unauthenticated privilege escalation, unauthenticated SQL injection / RCE / file upload, and stored XSS reachable by unauthenticated users as High.

## If the release already shipped without it

Readme-only changes don't need a new version. Commit the Upgrade Notice to `master`, fast-forward `trunk` to `master` and push `trunk`: the "readme.txt and assets only" workflow updates SVN trunk and the Stable-tag readme. Then merge `master` back into `dev` so the next release keeps it. Confirm the trunk diff against the release tag is `readme.txt` only before pushing.

## Checklist

- [ ] `- Improved: Security Enhancement` in the changelog entry
- [ ] High/Critical → `= X.Y.Z =` entry under `== Upgrade Notice ==`, vague, under 300 characters
- [ ] No vulnerability detail in changelog, Upgrade Notice, commit messages, PR titles, GitHub release notes or Slack
- [ ] Reporter (Patchstack / Wordfence) sent the patch for verification before release, if they asked
- [ ] Any public GitHub issue describing the vulnerability moved to a private security advisory and deleted
- [ ] After release, the private advisory is marked patched with the fixed version
