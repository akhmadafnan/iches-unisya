# 21I-03 — GO CODING ICH-S0-07 — Codex VS Code Execution Handoff

> **OWNER GO RECEIVED — 28 October 2026.** Exact user message: “Wokkey sepakat. Silahkan execute `ICH-S0-07` sesuai dengan arah anda”. This is an **explicit named GO for only ICH-S0-07**, scoped by [21I-02](21I-02-ICH-S0-07-CI-WORK-ORDER.md). It does **not** authorize `ICH-S0-02`, MySQL, auth, CMS, additional code, pushing/PR/merging automatically, installing unrelated tools, or deployment.
>
> **EXECUTION STATUS: AUTHORIZED / AWAITING CODEX VS CODE RUN.** This document and ChatGPT's GitHub docs-only commit are not source implementation. The user must submit the exact prompt below to the Codex panel in VS Code because remote terminal/GitHub connector are not permitted to write application code under project protocol.

## Current verified environment and source baseline

- Canonical local folder: `D:\PINJAM-AFNAN\Herd\iches-unisya`, URL `http://iches-unisya.test/`.
- Remote: `https://github.com/akhmadafnan/iches-unisya.git`; Git Credential Manager repo-local `credential.helper=manager`.
- **Local currently checked-out branch:** `feat/ich-s0-01-foundation` at `8eba7cb5b9da0577910beb919844165869591e05`, working tree clean; **do not reset/rebase/delete/amend or write code on this accepted branch**.
- **Local main:** old baseline `4137dce48e85fab5c25c89b7f3a1fde1d0fa3b89`, **local origin/main at last read-only verification:** `1d4ce54179e99a7741902178c72928294dff5a91`; both lag remote `main`. They must be safely fast-forwarded by Codex before creating a new feature branch.
- Remote `main` had PR #4 merged at `561baf322d459d898f5f96d280f5a5f02634c59f` plus ChatGPT-only documentation commit `f0024ea80983966178fdd2def887c3f42f3cdbfa`. **Do not hard-code the current HEAD as fixed:** this GO documentation may advance remote `main` again. Always resolve and verify `origin/main` after `git fetch origin main`.
- S0-01 app stack actual: PHP 8.4, Laravel 13, Inertia Laravel 2, Vue 3/TypeScript/Tailwind 4/Vite; `composer.lock` and `package-lock.json` committed. `config/database.php` has **empty connections**, `phpunit.xml` forces `DB_CONNECTION=disabled`, tests generate ephemeral app key; never provision CI MySQL.
- Prior 21E audit ran 3 tests/22 assertions and local build PASS; no GitHub CI checks were configured for PR #4.
- Separate 21H design Skills [PR #3](https://github.com/akhmadafnan/iches-unisya/pull/3) is still open/unmerged; do **not** import that PR as a prerequisite to S0-07.

## Paste the following in **Codex VS Code**, canonical Laravel workspace

~~~text
GO CODING ICH-S0-07 — EXECUTE NOW (BOUNDED TASK ONLY)

OWNER AUTHORIZATION: The owner expressly wrote “Silahkan execute ICH-S0-07 sesuai dengan arah anda.” This authorizes only ICH-S0-07 source coding according to docs/21I-02-ICH-S0-07-CI-WORK-ORDER.md and docs/21I-03-GO-ICH-S0-07-CODEX-EXECUTION.md. It is NOT permission for another task, push/PR, merge, MySQL/migrations, auth, CMS, finance or deploy.

WORKSPACE: D:\PINJAM-AFNAN\Herd\iches-unisya (the existing canonical repository). Use VS Code Codex to write code; ChatGPT owns docs/governance. Do not use the isolated ICHES-SKILLS-READONLY QA worktree or create a second project.

PRE-FLIGHT:
1. Read AGENTS.md, WORKSPACE.md, docs/19F-CURRENT-SPRINT-BOARD.md, docs/21I-02-ICH-S0-07-CI-WORK-ORDER.md, the current 21I-03 handoff, .agents/skills/iches-codex-engineering/SKILL.md and .agents/skills/iches-code-verification/SKILL.md.
2. Verify git remote is akhmadafnan/iches-unisya, current HEAD was accepted feature 8eba7cb5b9da0577910beb919844165869591e05 and working tree clean. If unexpected, STOP.
3. Fetch origin/main. Keep accepted feature branch intact. Safely switch to local main and fast-forward ONLY with git pull --ff-only origin main when possible. Never reset, force, rebase, amend or merge feature into main. Confirm fresh main contains accepted PR #4 commit. If dirty/diverged/unexpected, STOP and report.
4. Create NEW branch feat/ich-s0-07-ci from up-to-date main. If branch already exists, inspect and STOP rather than overwriting.

IMPLEMENT:
5. Build minimal GitHub Actions CI under .github/workflows, on pull_request targeting main and scoped feature branch push (avoid duplicate runs when practical). PHP 8.4 + Node 22; composer install from lockfile (no update); npm ci from lockfile. Least privilege contents:read, no database services, no secret tokens, no deployment.
6. Run composer validate --strict, php artisan --version, php artisan test, php vendor/bin/pint --test, npm run typecheck, npm run build. Linux job must use portable commands and real exit codes; on Windows local scripts use npm.cmd if required. Preserve S0-01 DB disabled. A framework-generated ephemeral .env or APP_KEY is permitted only inside disposable CI runner, never committed, never copied from local owner's .env.
7. Only small CI-necessary test/config fixes allowed after evidence-based failure. No migration, auth/OTP, permissions, CMS, UI redesign, billing, bank, fake conference content, dependency upgrades, global tools or code from unrelated tasks. Append sanitized harness/build-log.md only where the engineering protocol allows.
8. Check negative cases: no migrate:fresh, no database services, no secrets in workflow/evidence, no changes to docs/** / AGENTS.md / PLANS.md / WORKSPACE.md / .agents/skills/**. Confirm git diff --check PASS and no deploy job. Do not execute an upstream third-party Skill script.
9. Run the checks locally first and report exact PASS/FAIL (never fabricate GitHub CI PASS). If Actions can only be tested after push/PR, state CI_NOT_RUN / PENDING_PUBLICATION.

HANDOFF:
10. Commit on feat/ich-s0-07-ci only. Return new branch/HEAD/base SHA, changed paths, exact test commands and exit codes, sanitized evidence, scope audit, dirty/clean status, and issues. STOP before push/PR/merge/deployment — those need separate owner authorization. Do NOT code the next task.

Owner explicitly authorizes implementation of this one task now; no repeat clarification is needed.
~~~

## Owner/ChatGPT workflow after Codex completes

1. User pastes Codex's actual handoff here.
2. ChatGPT independently inspects local diff, tests and evidence. CI status remains `NOT_RUN` unless a real GitHub workflow check actually executed.
3. Only after owner explicitly authorizes publishing may Codex push the new feature branch. ChatGPT can then create its PR via the GitHub connector; owner separately approves merging.
4. Next work can be prepared in docs concurrently, but **no additional source task starts without a new named GO**.

## Current task state

- **GO_GRANTED:** yes, specifically ICH-S0-07.
- **CODEX_EXECUTION_STARTED:** not verified by ChatGPT at documentation commit time.
- **CI_IMPLEMENTED/TESTED:** not yet observed; do not infer success.
- **S0-02 DATABASE:** NO_GO, test DB unverified.
- **PR3 DESIGN SKILLS:** open/unmerged; unrelated.
