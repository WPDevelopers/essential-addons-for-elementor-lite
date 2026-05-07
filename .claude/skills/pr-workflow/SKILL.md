---
name: pr-workflow
description: Ship a change via a feature branch and pull request — never directly to the default branch. Use after any code change, fix, refactor, or docs update that needs to land on main. Branches are named `<card-number>-<specific-slug>` from the FluentBoards card. Commits go through the husky pre-commit hook (lint-staged); pushes go to the feature branch only; the PR body links back to the card. Hard refuses any push to main / master / trunk.
---

# PR Workflow

## When to Invoke

- After completing any change you intend to merge — fix, feature, refactor, docs, chore
- When the user says "open PR", "make a PR", "push and PR", "ship this"
- After a debug-widget fix is applied and verified
- Never invoke for local-only experiments the user hasn't asked to ship

## Required Inputs

FluentBoards card number · one-line change summary · whether `src/` was touched (build needed?) · whether shared Pro/Lite code changed (Pro coordination flag). Missing card number → ask before doing anything.

## Phase 1 — Pre-check

```bash
git status                           # working-tree state
git branch --show-current            # current branch
gh repo view --json defaultBranchRef --jq '.defaultBranchRef.name'   # default branch (don't assume "main")
gh auth status                       # confirm gh CLI logged in
```

If working tree is dirty with unrelated changes → stop, list them, ask: stash / branch / abandon. Don't bundle unrelated work into one PR.

## Phase 2 — Branch Setup

**Naming:** `<card-number>-<specific-slug>` — number first for fast `grep ^N` and tab-complete; slug as specific as possible.

| Slug quality | Example |
|--------------|---------|
| ❌ Generic — avoid | `1234-fix`, `5678-update`, `9012-bug` |
| ❌ Action-only — avoid | `1234-refactor`, `5678-improve` |
| ✅ Widget + specific change | `1234-fancy-text-pro-description-i18n` |
| ✅ Area + behavior | `5678-asset-builder-template-detection` |
| ✅ Feature + scope | `9012-marquee-text-widget-vertical-mode` |

**Slug rules:** kebab-case · widget or area name first · specific change second · ≤60 chars total branch name · no issue/type prefix (the card already has the type).

**Branch creation:**
```bash
# If currently on default branch → create feature branch
git checkout -b <number>-<specific-slug>

# If already on a feature branch matching the card → continue
# If on a different feature branch → stop, ask the user which branch this work belongs to
```

## Phase 3 — Stage Selectively

```bash
git diff                              # review all unstaged changes
git diff --stat                       # file list with line counts
git add <specific-file> ...           # stage by name — never `-A` or `.`
git diff --cached                     # confirm staged diff matches intent
```

**Reject staging if you spot:** `.env`, `*.key`, `credentials*`, large unrelated binaries, OS artifacts (`.DS_Store`), or files outside the change's scope.

## Phase 4 — Build If Needed

If anything under `src/` was modified, **before commit**:

```bash
npm run build
```

Per [.claude/rules/asset-pipeline.md](../../rules/asset-pipeline.md), the repo tracks compiled assets. Source-only commit without build = drift on production.

After build, also stage the regenerated `assets/front-end/**/*.min.{css,js}` and `languages/*.pot` if they changed.

## Phase 5 — Commit Atomically

**Message format:**
```
<type>: <imperative summary, ≤72 chars>

<body — explain WHY, not WHAT (the diff already shows what)>

Refs: #<card-number>
```

**Types:** `fix`, `feat`, `refactor`, `docs`, `chore`, `perf`, `test`, `style`

**Husky pre-commit hook** runs `npx lint-staged`. If it fails:
1. Read the lint output
2. Fix the root cause (don't `--no-verify`)
3. Re-stage the corrected files
4. **New commit** — never `git commit --amend` after a hook failure (the failed commit didn't happen; amending would modify the *previous* commit, destroying its history)

## Phase 6 — Push the Branch

```bash
git push -u origin <number>-<specific-slug>     # first push
git push                                         # subsequent pushes
```

**Hard refusal:** if the resolved branch name equals the default branch (main / master / trunk), do not push. Output:

```
REFUSED: Cannot push to default branch (<name>).
This skill enforces feature-branch-only workflow. Create a feature branch first.
```

Also refuse `git push --force` to any shared branch. `--force-with-lease` to your own feature branch is OK only with explicit user request.

## Phase 7 — Create the PR

```bash
gh pr create --title "<type>: <summary>" --body "$(cat <<'EOF'
## Summary
- <1–3 bullets — what changed and why>

## Test Plan
- [ ] <manual test step on http://localhost:8888>
- [ ] `npm run test:e2e` green
- [ ] Visually verified <widget / area> on test site

## Breaking Changes
<None — OR list with migration notes if hooks / control ids / rendered classes changed>

## Pro Repo Coordination
<None — OR: shared trait/class/hook touched. Pro PR: #XXXX>

## Related
- FluentBoards card: https://projects.startise.com/wp-admin/admin.php?page=fluent-boards#/boards/30 (card #<card-number>)
EOF
)"
```

After creation: return the PR URL to the user. Do not auto-assign reviewers, do not add labels (unless user asks).

## Hard "Never" Rules

| ❌ Never do | Why |
|------------|-----|
| `git push origin <default-branch>` | Skill's primary contract — feature-branch only |
| `git push --force` to a shared branch | Destructive, history rewrite for others |
| `git commit --no-verify` | Pre-commit hooks exist for a reason; failure = real lint/format issue |
| `git add -A` / `git add .` | Sensitive file leak risk |
| `git commit --amend` after a pushed commit | Rewrites shared history |
| `git config <anything>` | Per CLAUDE.md, never touch git config |
| Skip `npm run build` after `src/` change | Repo tracks compiled assets |
| Bundle unrelated changes into one PR | Hard to review, hard to revert |

## Edge Cases

| Situation | Handling |
|-----------|----------|
| User-uncommitted unrelated changes | Stop. List them. Ask: stash / commit-to-different-branch / abandon |
| Branch already exists on remote | Ask: continue on existing, or new branch with `-v2` suffix |
| Pre-commit hook fails | Show output, fix lint root cause, re-stage, **new commit** |
| Merge conflict during rebase | Stop. Show conflict files. User resolves manually — never auto-pick a side |
| `gh` not authenticated | `gh auth status` — instruct user to `gh auth login` |
| Card number unknown / not given | Refuse to create branch — ask the user; the FluentBoards link is the source of truth |
| Diff > 500 lines, mixed concerns | Suggest splitting into multiple atomic PRs before pushing |

## Operating Rules

1. **Default branch is sacred.** Hard refusal of any push there. No exceptions, including hotfixes — hotfixes still go through PR.
2. **Card number required.** Every branch name and PR `Related` section ties back to FluentBoards. No card → no branch.
3. **Pre-commit hooks honored.** Failure means fix the issue, not skip the hook.
4. **One PR, one logical change.** Mixed concerns → split before pushing.
5. **Slug must be specific.** "fix" or "update" alone is rejected — name the widget / area / behavior.
