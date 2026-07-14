---
description: E2E testing workflow with Playwright
paths:
  - "tests/**/*"
---

# E2E Testing

## Setup & Run

```bash
npm run test:setup   # First-time setup (boots wp-env, seeds data)
npm run test:reset   # Clean slate (wipes DB, re-seeds)
npm run test:e2e     # Run all E2E specs
npm run test:php     # Run PHPUnit (wp-env must be running)
```

Test site: `http://localhost:8888` — WP admin at `/wp-admin` (`admin` / `password`).
Broader gate reference: [[quality-gates]].

## Directory Structure

```
tests/e2e/
├── specs/           # Playwright spec files (*.spec.ts)
├── templates/       # Elementor JSON page templates per widget — <slug>.json
├── manifest.json    # data-driven smoke assertions (slug -> selector + text)
├── utils/seed.sh    # DB seeding — AUTO-DISCOVERS every templates/*.json
├── mu-plugins/      # Must-use plugins for the test environment
├── global-setup.ts  # Playwright global setup
└── playwright.config.ts
tests/php/           # PHPUnit — bootstrap.php + *Test.php (wp-phpunit)
```

## Adding a Widget Test (preferred: manifest-driven)

`seed.sh` publishes one `/<slug>-test/` page for every `templates/<slug>.json`, and
`specs/smoke.spec.ts` generates a test per `manifest.json` entry. So most widgets need
NO new spec — just two data files:

1. Export an Elementor page with the widget as JSON → `tests/e2e/templates/{slug}.json`.
   (seed.sh picks it up automatically — do NOT edit seed.sh.)
2. Add an entry to `tests/e2e/manifest.json`: `{ "slug", "selector", "contains": [...] }`.
3. Run `npm run test:reset && npm run test:e2e`; verify visually on the test site.

Write a bespoke `specs/{slug}.spec.ts` (see `info-box.spec.ts`) only when assertions
exceed "root element renders + contains text".

## Adding a PHP test

Add `tests/php/{Thing}Test.php` extending `WP_UnitTestCase`. Start with pure,
security-relevant helpers (sanitizers, escapers) — highest value, lowest fixtures.
See `HelperTest.php`.

## Rule

**Always verify fixes visually on the test site before marking a task done.**
