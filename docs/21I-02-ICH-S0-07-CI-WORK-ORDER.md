# 21I-02 — ICH-S0-07 DB-Free GitHub CI Work Order

> **GO CODING ICH-S0-07 GRANTED — 28 Oct 2026; CODEX EXECUTION PENDING.** Owner explicitly said “Wokkey sepakat. Silahkan execute `ICH-S0-07` sesuai dengan arah anda”. This authorizes Codex VS Code to implement **only** this bounded CI task. [21I-03 execution handoff](21I-03-GO-ICH-S0-07-CODEX-EXECUTION.md) has actual preflight and next actions. No coding by ChatGPT, no database/migration, no automatic push/PR/merge/deploy.

## Authorization & boundary

**Owner explicitly issued the named GO** for `ICH-S0-07` in this conversation on 28 Oct 2026. This single task is now `GO_AUTHORIZED / CODEX_EXECUTION_PENDING`; the two-day target and prior S0-01 merge never authorize other tasks. No S0-02 schema, S0-03 auth, CMS, payments, migrations, or deployment. Use [21I-03 Codex execution prompt](21I-03-GO-ICH-S0-07-CODEX-EXECUTION.md).

## Branch, files and safety

- Canonical project `D:\PINJAM-AFNAN\Herd\iches-unisya`, remote `akhmadafnan/iches-unisya`.
- `main` contains merged `ICH-S0-01` (PR #4 merge `561baf322d459d898f5f96d280f5a5f02634c59f`), followed potentially by newer docs commits.
- Preflight after GO: check local working tree/branch and remote; `git fetch origin main`. Switch to local `main` and `git pull --ff-only origin main` **only if clean and safe**. Never reset, rebase, overwrite, amend or delete the accepted `feat/ich-s0-01-foundation` branch. If branch divergence or dirty files, STOP.
- Create feature `feat/ich-s0-07-ci` from the verified latest clean `main`; do not code on main/QA worktree. Read `AGENTS.md`, `WORKSPACE.md`, board, native engineering/verification Skills and this work order.
- Allowed files: minimal `.github/workflows/**` CI workflow, necessary test/config fixes under narrowly proven runner failures, and append-only sanitized `harness/build-log.md`. No third-party Skills/docs/AGENTS/PLANS edits by Codex.

## Implement only after GO

1. GitHub Actions on PRs to `main` and appropriate feature pushes, with least-privilege `contents: read`, stable timeout and no deploy job.
2. PHP 8.4, Composer exact `composer.lock` via `composer install`; Node 22, `npm ci` via `package-lock.json`. Do not update lockfile or change dependency major versions.
3. Checks: `composer validate --strict`, `php artisan --version`, `php artisan test` (FoundationSmokeTest), `php vendor/bin/pint --test`, `npm run typecheck`, `npm run build`.
4. DB-free environment: no MySQL/Redis, no migrations, no production secret, no tracked `.env`; file cache/session, sync queue. Workflow must not attempt bank/account access, secret export or deployment.
5. If exact PHP/Node runner incompatibility appears, use smallest correction with real reproduction, no new app feature. If correction would alter business logic or DB, STOP and escalate.

## Acceptance and negative tests

- Demonstrate local exit codes and actual GitHub Actions run if actions enabled; **local PASS ≠ CI PASS**. If Actions disabled, state `CI_NOT_RUN` and reason.
- `git diff --check`, branch scope, no `docs/**`, governance, DB migrations, auth changes, deployment steps, secrets or unreviewed dependency changes.
- Verify PR triggers, no production side effects and no accidental `migrate:fresh`.
- Handoff: base SHA, branch HEAD, changed paths, real local commands + exit codes, GitHub check URL/status if published, evidence and blockers; Git remains clean.

**Publication gate:** Coding GO alone does **not** authorize push, PR creation or merge. Owner can separately authorize publication and review; ChatGPT independently audits; final merge always explicit owner action.
