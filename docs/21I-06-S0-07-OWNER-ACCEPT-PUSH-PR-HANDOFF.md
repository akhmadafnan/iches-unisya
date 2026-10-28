# 21I-06 — Owner ACCEPT & Authorized S0-07 Codex Push / PR Handoff

> **28 October 2026 — OWNER_ACCEPTED / CODEX_PUSH_AUTHORIZED / PR_NOT_CREATED / GITHUB_CI_NOT_RUN.** Owner specifically authorized **Codex VS Code** to push only existing branch `feat/ich-s0-07-ci` with exact HEAD `c420d95175d7d25cd9fee6719180acaf0b684903`; after successful push, ChatGPT may use GitHub connector to open a PR targeting `main` and inspect actual Actions runs. **NO merge, migration, DB operation, further coding or deployment authorization.**

## Verified before push

- Canonical repo `D:\PINJAM-AFNAN\Herd\iches-unisya`; remote `https://github.com/akhmadafnan/iches-unisya.git`; `credential.helper=manager`.
- Current local branch `feat/ich-s0-07-ci`, exact HEAD `c420d95175d7d25cd9fee6719180acaf0b684903`, working tree **clean**, not tracking remote yet. GitHub feature ref **404** as of approval/preflight (not pushed).
- Local commit ancestry: approved implementation `17e335049f209110e76a03dc99bc588cc7cee925` on prior `main` base `42a8633245ad00a3eb809f03afded3f7d07955d5`; correction changes one `shivammathur/setup-php` pin to confirmed target commit `eb7c497e18156a6bbabfef1d3a82760b9eda3962`, build log append-only. Full [21I-05 independent review](21I-05-ICH-S0-07-FINAL-REVIEW.md) **REVIEW_PASS**, owner has now explicitly ACCEPTED.
- Remote `main` may have newer docs commits. **Do not** rebase/reset/amend/merge anything to align histories before push. The intended three-dot PR diff remains just `.github/workflows/ci.yml` + `harness/build-log.md`.

## Exact Codex VS Code publication-only instruction

~~~text
ICH-S0-07 — OWNER ACCEPTED / PUSH AUTHORIZED — NO CODING

Use existing canonical VS Code workspace D:\PINJAM-AFNAN\Herd\iches-unisya. Do NOT use the isolated QA worktree.

Owner authorized Codex VS Code to push ONLY branch feat/ich-s0-07-ci at exactly c420d95175d7d25cd9fee6719180acaf0b684903. Do not modify/apply fixes, amend, rebase, merge, reset, migrate, deploy or edit files.

1. Read docs/21I-05-ICH-S0-07-FINAL-REVIEW.md and docs/21I-06-S0-07-OWNER-ACCEPT-PUSH-PR-HANDOFF.md from origin/main (git show origin/main:... if local docs lag); verify remote canonical, branch name, HEAD full SHA, clean status and expected commit ancestry.
2. Verify three-dot diff against origin/main has ONLY .github/workflows/ci.yml and harness/build-log.md. Check that feature branch does not exist on origin under same name or has exactly the same expected SHA. If it already exists with unexpected SHA, STOP.
3. Use preconfigured Git Credential Manager: git push -u origin feat/ich-s0-07-ci. Do not use --force, --tags or embedded credentials. If browser auth is required, let owner complete directly; do not expose tokens.
4. Verify remote branch SHA equals c420d95175d7d25cd9fee6719180acaf0b684903, tracking established, and local Git clean. Report push exit code + exact URL/commit SHA.
5. STOP. ChatGPT will create the PR via GitHub connector after seeing a verified remote ref. No authorization to create PR independently in Codex, and NO merge or further tasks.
~~~

## After owner returns successful push evidence

ChatGPT verifies the remote head and exactly two changed file paths against current `main`, creates PR using GitHub connector (prefer **Ready for Review**, not merge), checks real workflow runs/status on `c420d951...` and PR, and reports **PASS / FAIL / RUNNING / NOT_RUN** grounded in actual Actions evidence. A green local test report is not a substitute for GitHub Ubuntu 24.04 PHP 8.4 / Node 22 check.

**Merge decision is separately owner-gated** even if CI passes. Other project tasks remain `NO_GO` unless specifically authorized.
