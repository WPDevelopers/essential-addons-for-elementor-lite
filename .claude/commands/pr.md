---
description: Ship current changes via feature branch + PR using the pr-workflow skill. Hard refuses push to main.
argument-hint: <card-number>
---

# PR Workflow — Card #$ARGUMENTS

Use the `pr-workflow` skill at `.claude/skills/pr-workflow/SKILL.md` to ship the current changes.

**Card number:** `$ARGUMENTS`

If `$ARGUMENTS` is empty, ask the user for the FluentBoards card number before doing anything — the skill requires it for the branch name and PR `Related` link.

Follow the skill's 7 phases in order:
1. Pre-check (working tree, current branch, default branch via `gh`)
2. Branch setup — name `<card-number>-<specific-slug>`
3. Stage selectively (no `git add -A`)
4. Build if `src/` was touched (`npm run build`)
5. Commit atomically (husky hook honored, `Refs: #$ARGUMENTS` footer)
6. Push branch (hard refuse to default branch)
7. Create PR with structured body and FluentBoards card link

Return the PR URL when done.
