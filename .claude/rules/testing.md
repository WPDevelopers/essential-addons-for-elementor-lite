---
description: E2E testing workflow with Playwright
paths:
  - "tests/**/*"
---

# E2E Testing

## Setup & Run

```bash
npm run test:setup   # First-time setup (installs WP, seeds data)
npm run test:reset   # Clean slate (wipes DB, re-seeds)
npm run test:e2e     # Run all E2E specs
```

Test site: `http://localhost:8888` — WP admin at `/wp-admin` (`admin` / `password`)

## Directory Structure

```
tests/e2e/
├── specs/           # Playwright spec files (*.spec.ts)
├── templates/       # Elementor JSON page templates per widget
├── utils/seed.sh    # DB seeding script
├── mu-plugins/      # Must-use plugins for test environment
├── global-setup.ts  # Playwright global setup
└── playwright.config.ts
```

## Adding a Widget Test

1. Export an Elementor page with the widget as JSON → `tests/e2e/templates/{widget-slug}.json`
2. Register the page in `tests/e2e/utils/seed.sh`
3. Write a spec in `tests/e2e/specs/{widget-slug}.spec.ts`
4. Verify visually on `http://localhost:8888` before marking done

## Rule

**Always verify fixes visually on the test site before marking a task done.**
