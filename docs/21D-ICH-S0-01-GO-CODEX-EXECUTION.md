# 21D — GO CODING ICH-S0-01: Codex VS Code Execution Handoff

> **OWNER-AUTHORIZED · 28 October 2026 · LIMITED TASK GO ONLY.** Owner's exact chat instruction: **“GO CODING ICH-S0-01. Saya memberikan izin eksplisit untuk Task ICH-S0-01 sesuai Work Order 21B yang sudah READY. Implementasi kode hanya oleh Codex VS Code pada feature branch; tanpa migrasi database, autentikasi, CMS, auto-merge, atau deployment. Siapkan instruksi eksekusi final Codex.”**

**Authorization evidence:** explicit owner message in project conversation, not a general “lanjut” or an automated assumption. This record is maintained by ChatGPT in the governance docs; Codex may now work **ONLY in the named task**, within the [21B Work Order](21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md). No automatic GO for subsequent tasks.

## Repo baseline and gate record

- GitHub: `akhmadafnan/iches-unisya`.
- Authorized working directory: `D:\PINJAM-AFNAN\Herd\iches-unisya`.
- Approved governance PR #1 already merged: `275cfbfd0e431033eb05d2c519eda19282465962`.
- Last verified clean main snapshot **before GO documentation update**: `3371ac1532a7856a2bd7d248f79d563a541dac4a`.
- ChatGPT's GO-recording docs commit on `main` comes after that snapshot. The **actual feature-branch base SHA is the current, clean, synced `origin/main` HEAD after this documentation commit**; Codex must print and verify the *exact* SHA before branching. Never use the docs branch or old snapshot as a coding base.
- Current Herd Free map: `http://iches-unisya.test` → exact folder above, PHP 8.4. Before implementation: HTTP **404** because no app/public entry.
- PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm.cmd 11.17.0, Herd CLI 1.29.0. All minimum PHP extensions required by Laravel 13 checked available, **but actual install/build/test not executed**.
- Target branch **created by Codex after checks**: `feat/ich-s0-01-foundation`. No edits directly on `main`.

## Copy-and-paste one-message prompt for Codex (execute now)

```text
ICHES 2027 — GO CODING ICH-S0-01 — AUTHORIZED BY OWNER ON 28 OCT 2026

THIS IS A NAMED TASK GO FOR ICH-S0-01 ONLY. You are the SOLE application-code writer, operating through Codex in VS Code. The owner expressly authorized this task. You may make code changes after strict preflight. Do NOT work on ICH-S0-02, ICH-S0-07, the full Sprint 01 or anything else.

1) WORKSPACE & GIT PREFLIGHT
Use ONLY D:\PINJAM-AFNAN\Herd\iches-unisya. Check and report pwd, git remote -v, git branch --show-current, git status --short, git rev-parse HEAD. Expected remote: https://github.com/akhmadafnan/iches-unisya.git. The pre-GO main SHA was 3371ac1532a7856a2bd7d248f79d563a541dac4a; later ChatGPT governance doc commits are allowed. Fetch/sync origin/main with fast-forward ONLY if clean, identify the EXACT current main SHA and verify governance merge commit 275cfbfd0e431033eb05d2c519eda19282465962 is in main ancestry. Stop on unexpected divergences, wrong repo, dirty tree or a preexisting conflicting feature branch.

2) READ THE NARROW CONTRACT
Read AGENTS.md, WORKSPACE.md, PLANS.md, docs/19F-CURRENT-SPRINT-BOARD.md, docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md, docs/21D-ICH-S0-01-GO-CODEX-EXECUTION.md, and relevant RD-01..06 in docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md. Read .agents/skills/iches-codex-engineering/SKILL.md and .agents/skills/iches-code-verification/SKILL.md in full. Read 18C-05/05A only selectively as needed. Owner permission is recorded in 21D and in the current ChatGPT conversation.

3) BRANCH AFTER PREFLIGHT
From clean updated main, create feature branch feat/ich-s0-01-foundation. Work there ONLY. You may edit scoped application source, tests, dependencies and lockfiles, app boot config, .env.example and secure .gitignore entries. You may append evidence to harness/build-log.md and add sanitized harness/evidence/**. NEVER edit AGENTS.md, PLANS.md, README.md, WORKSPACE.md, docs/**, .agents/skills/**, other app folders or secret files in Git.

4) IMPLEMENT MINIMAL DB-INDEPENDENT FOUNDATION
Safely scaffold Laravel 13 WITHIN EXISTING NONEMPTY GIT REPOSITORY, preserving all governance files. Do not blindly create-project into repo root or replace its contents. Set up Inertia + Vue 3 Composition API + TypeScript + Tailwind CSS 4 + Vite with compatible locked dependencies. Provide one truthful minimal Inertia/Vue GET / landing page, a proper Laravel public/index.php entry, and DB-free HTTP smoke tests. Local site uses Herd Free http://iches-unisya.test/ (do NOT use php artisan serve as the .test solution). Use file session/cache and sync queue or other DB-free configuration for foundation only. Do not put secrets in Git.

5) STRICT EXCLUSIONS
NO MySQL connection, DATABASE CREATION, SQL, migrations, seeders, identity/event schema, database-backed tests, or production activity.
NO login/register/reset/password auth, even if an official Vue starter kit suggests it. Participant login will be OTP/Magic Link in a future task. Do not scaffold authentication UI or auth DB schema now.
NO CMS, countdown, finished public design, Admin dashboard, submission/review/finance/Co-Host/OJS functionality, fake event facts or other projects.
NO merge, deploy, force-push, direct code changes on main, or unapproved global tooling changes.

6) VERIFY WITH REAL COMMANDS
Inspect actual scripts before testing. At minimum attempt:
- composer validate --strict; php artisan --version; php artisan route:list
- DB-free FoundationSmokeTest: GET / returns 200 with Inertia page; unknown route returns 404
- npm.cmd install (or reproducible npm.cmd ci when lockfile exists); npm.cmd run build; TypeScript typecheck using actual installed script
- Curl HTTP status for http://iches-unisya.test/ (target 200, baseline before code 404)
- git status/diff scope scan, no secrets or unauthorized docs modifications
Do NOT claim PASS if a test was skipped, unavailable or failed. If a dependency/version or server issue blocks you, diagnose and fix ONLY within task boundaries; otherwise STOP and report blocker. Preserve legacy business logic exclusions.

7) HANDOFF TO CHATGPT
Return task ID ICH-S0-01, current feature branch, exact branch-point SHA, changed files, Laravel/Inertia/Vue/TS/Tailwind versions, real commands+exit codes with PASS/FAIL/BLOCKED, Herd HTTP result, test evidence, sanitized log, commit/PR link if made, and unresolved issues. Do NOT auto-merge or deploy. Owner acceptance and PR merge happen separately.

GO CODING applies to ICH-S0-01 only. Proceed now.
```

## Stop and acceptance gates

- **PASS target:** Laravel 13 reports correct version; Vite Vue3/TS/Tailwind4 builds, typecheck passes; actual Inertia page served through Herd at HTTP 200; DB-free GET/404 smoke passes.
- **STOP:** incorrect current directory, unexpected Git state, conflicting feature branch, unapproved dependency migration, a starter kit introducing required passwords/DB migrations, database access requirement, production credentials or any outside-task scope.
- **MANDATORY:** actual commands and exit status, honest blocked cases, diff scan, sanitized append-only evidence, no PR auto merge.

**After Codex reports:** ChatGPT reviews feature branch/code and test evidence, updates 19F, and asks owner before any merge. The remaining Sprint tasks are NOT authorized.
