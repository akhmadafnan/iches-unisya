# 21I-08 — ICH-S0-07 REV-02 Local Correction: Independent Review PASS

> **28 October 2026 · REV-02 CLOSED FOR LOCAL IMPLEMENTATION / REVIEW_PASS / NEW_SHA_PUBLICATION_PENDING.** GitHub Actions still **FAILED at earlier remote SHA** `c420d95175d7d25cd9fee6719180acaf0b684903`; the corrected local commit has NOT been pushed. Do not claim Linux/Node22 CI PASS, do not merge PR #5, run migrations or deploy. No further coding task was authorized.

## Independent preflight

- Canonical repo `D:\PINJAM-AFNAN\Herd\iches-unisya`, branch `feat/ich-s0-07-ci`.
- Exact local HEAD `294a54b223676df3b6e9124480e271ee3a50bdc7`, parent `c420d95175d7d25cd9fee6719180acaf0b684903`, worktree **clean** and tracking `origin/feat/ich-s0-07-ci`.
- REV-02 diff: **exactly 3 paths**, all additive: `tests/Feature/FoundationSmokeTest.php` **+14 / -0**, `.github/workflows/ci.yml` **+3 / -0**, `harness/build-log.md` **+28 / -0**. No changes in dependencies, lockfiles, app production code, schema/migrations, security/auth/CMS, docs or Skills in this feature commit.
- `git diff --check HEAD^ HEAD` exit 0; `.env` is **not tracked**, and `git check-ignore .env` PASS. No reset/rebase/amend, push or merge.
- Historical commits `17e3350` and `c420d951` intact.

## Code change review

1. In `tests/Feature/FoundationSmokeTest.php`, `setUp()` now configures `inertia.testing.page_paths = [resource_path('js/pages')]` to match the existing `resources/js/pages/Foundation.vue`. It **does not disable** `component('Foundation')` or Inertia's default `ensure_pages_exist` test guard.
2. New `test_missing_inertia_component_is_rejected()` routes to a deliberately non-existent page and expects `AssertionFailedError` with exact missing-component message. This is a positive control for continued enforcement.
3. In `.github/workflows/ci.yml`, after installed Composer dependencies and before PHPUnit, `cp .env.example .env` creates a **temporary runner-local, public example** env file; test keys remain randomly generated in-memory, `DB_CONNECTION=disabled`, no real secrets or MySQL. No production env, `APP_KEY` or downstream pipeline change.
4. Codex's detailed troubleshooting in append-only `harness/build-log.md` shows the two warning traces came from **phpdotenv reading missing `.env`**, not an absent Vite manifest. The initial manifest hypothesis was disproved by no-manifest test experiments. The local Windows test with `.env.example` passed with no warnings.

## Independent test evidence

ChatGPT used the connected Windows machine to execute actual read-only verification:

| Command or check | Observed exit | Result |
|---|---:|---|
| `php artisan test --display-warnings` | **0** | **4 tests PASS, 36 assertions**, no warnings |
| `php vendor/bin/pint --test` | **0** | **18 files PASS**, no rewriting |
| `composer validate --strict` | **0** | Composer schema valid |
| `git diff --check HEAD^ HEAD` | **0** | Clean patch |
| `git status --porcelain` | empty | Clean after verification |
| `git diff --numstat HEAD^ HEAD -- harness/build-log.md` | `28 / 0` | Append-only |
| `git check-ignore .env` | expected match | Temporary env is ignored |

Codex's own submitted evidence also reports **actionlint**, Node TypeScript typecheck and Vite build PASS (exit 0), and 4/36 after `.env`-missing simulation. Those JS and actionlint commands were **not independently rerun** in this review. Windows Node 26.4.0 cannot substitute for Ubuntu Node 22 CI.

## Current GitHub status and next approval

- [PR #5](https://github.com/akhmadafnan/iches-unisya/pull/5) stays **OPEN / UNMERGED**, remote HEAD `c420d95175d7d25cd9fee6719180acaf0b684903`.
- Both real CI executions of the **old** SHA failed before Pint, Node22, npm, typecheck and Vite: [push 40668248799](https://github.com/akhmadafnan/iches-unisya/actions/runs/40668248799), [PR 40669157978](https://github.com/akhmadafnan/iches-unisya/actions/runs/40669157978). Earlier failure remains part of audit history.
- **REV-02 local code review verdict = REVIEW_PASS / CLOSED**. **Owner approval is still REQUIRED to push NEW HEAD** `294a54b223676df3b6e9124480e271ee3a50bdc7`. The earlier push consent explicitly specified previous SHA `c420d951...`, so it is not permission to publish a different commit.
- Once owner authorizes, **Codex VS Code only** may push `feat/ich-s0-07-ci` normally (no force). ChatGPT then verifies remote SHA, PR diff, and actual new GitHub Actions PHP/Node22 execution; **even passing CI will not authorize merge automatically**.
- Do not add a new product coding task, migrations, DB, auth, payment, CMS or deployment. [21I-07 diagnosis](21I-07-S0-07-LINUX-CI-FAILURE-REV02.md) is historical and superseded for the **local correction verdict only**; it remains accurate for prior remote CI failures.

**Reviewer conclusion:** proceed to owner acceptance and bounded publication request for the corrected local SHA, not to merge.
