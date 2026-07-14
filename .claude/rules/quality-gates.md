# Quality Gates & Feedback Loops

Read this before committing. These are the automated checks that verify your work
— use them as your feedback loop instead of guessing whether a change is correct.

## Run these locally (fast feedback)

```bash
npm run build          # compile src/ -> assets/ (ALWAYS after editing src/)
npm run lint:js        # eslint on src/**/*.js
npm run lint:css       # stylelint on src/**/*.scss
npm run format         # prettier --write src/
composer run phpcs     # PHP coding standards (WordPress-Core + security + DB sniffs)
composer run phpcbf    # auto-fix PHP standards

npm run test:e2e       # Playwright smoke suite (needs: npm run test:setup once)
npm run test:php       # PHPUnit (needs wp-env running)
bin/check-version.sh   # version consistency across the 4 sources of truth
```

`test:setup` boots wp-env + seeds; `test:reset` wipes and re-seeds.

## Pre-commit hook (automatic)

`.husky/pre-commit` runs `lint-staged` on **staged files only**:
- `*.php` -> `bin/lint-php.sh` (phpcbf auto-fix, then phpcs gate — blocks on unfixable violations)
- `src/**/*.js` -> `eslint --fix` + `prettier --write`
- `src/**/*.scss` -> `stylelint --fix` + `prettier --write`

If a commit is rejected, the reported violation is real — fix it, don't `--no-verify`.

## CI (on pull request)

Gates run on **changed files only** — the legacy tree is grandfathered, but any file
you touch must pass. See `.github/workflows/`.

| Check | Blocking? |
|-------|-----------|
| `npm run build` succeeds | ✅ blocking |
| Prettier (changed src) | ✅ blocking |
| ESLint (changed src) | ✅ blocking (legacy = warnings; real errors fail) |
| PHPCS (changed PHP) | ✅ blocking |
| Stylelint (changed scss) | ⚠️ advisory (flips to blocking after a normalization pass) |
| `assets/` matches a fresh build | ⚠️ advisory |
| Version consistency (on version-file PRs / tags) | ✅ blocking |
| Pro-review label (config.php / shared traits / Bootstrap / Asset_Builder) | annotation only |

E2E + PHPUnit run on-demand (`e2e.yml`, Actions → Run workflow), not per-PR.

## Non-negotiables

- **Never hand-edit `assets/`** — edit `src/` and `npm run build`. The drift gate
  compares committed assets against a fresh build. (See [[asset-pipeline]].)
- **Reproducible deps** — dependencies are locked (`composer.lock`, `tools/composer.lock`,
  `package-lock.json`). Use `npm ci` / `composer install`, never `update`, unless you
  intend to change and re-commit the lock. `tools/composer.json` pins `platform.php`
  so the lock resolves the same on any machine.
- **Version bump = 4 places in lockstep** — plugin header `Version:`, the
  `EAEL_PLUGIN_VERSION` constant, readme `Stable tag:`, and a changelog `= X.Y.Z =`
  entry. `bin/check-version.sh` enforces it. (See [[release-checklist]].)
- **Dev/test files never ship** — anything new that is tooling/test-only must be added
  to `.distignore` (CI deploy honours it).
