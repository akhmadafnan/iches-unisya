# 21B — ICH-S0-01 Laravel 13 Foundation: Codex VS Code Work Order

> **PREPARED / NOT EXECUTABLE.** Separate owner message **GO CODING ICH-S0-01** required *after* governance PR #1 is approved/merged and current `main` SHA frozen. This is **ONE Task ID**, not `ICH-SPRINT-01`, `ICH-S0-02`, or authorization for other modules.

## 1. Scope, branch and ownership

| Field | Contract |
|---|---|
| Task ID | **ICH-S0-01** |
| Goal | Bootable **Laravel 13 + Inertia + Vue 3 + TypeScript + Tailwind CSS 4 + Vite** served by Herd Free |
| Canonical remote | `https://github.com/akhmadafnan/iches-unisya.git` |
| Correct Windows folder | `D:\PINJAM-AFNAN\Herd\iches-unisya` |
| Test URL | `http://iches-unisya.test/` (currently 404; no Laravel source yet) |
| PHP / tooling | PHP **8.4.24**, Composer **2.10.1**, Node **26.4.0**, npm.cmd **11.17.0**, Herd **1.29.0** |
| Coding base | `main` **AFTER governance PR #1 merge**; base SHA **NOT YET FROZEN** |
| Feature branch | `feat/ich-s0-01-foundation`, only after named GO |
| Builder | **Codex VS Code**, code-only; ChatGPT owns docs, GitHub review and board |
| Data / DB | **No database dependency, credentials, SQL, migrations, seed or auth** in this task |
| Next task | `ICH-S0-02` schema migration and `ICH-S0-07` verification require separate GO/readiness |

## 2. Mandatory preflight after GO

1. Verify absolute folder is the Herd path above, correct remote, clean Git state, **approved `main` HEAD** and explicit owner message `GO CODING ICH-S0-01`. **STOP** if any mismatch.
2. Read `AGENTS.md`, `WORKSPACE.md`, `PLANS.md`, current `docs/19F-CURRENT-SPRINT-BOARD.md`, this 21B order and approved RD-01..06 at `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md`. Consult 18C-05 and 18C-05A reuse audit selectively.
3. Read skills `iches-codex-engineering` and `iches-code-verification`; no need to execute CMS/Admin Skills for this scaffold.
4. Only after preflight, create `feat/ich-s0-01-foundation` **from clean, approved `main`**, not from the old documentation branch.

## 3. Allowed implementation

- Place Laravel 13 source and required bootstrap/public entry **inside existing Git checkout**, preserving all docs, Skills, README and governance. A Laravel scaffold command may refuse a nonempty directory: **do not overwrite this repository**. If needed, safely stage framework skeleton in a temporary folder and transfer *only authorized source/config files*; never copy legacy business/auth/payment logic.
- Integrate Inertia server/client, Vue 3 Composition API, TypeScript, Tailwind CSS 4 and Vite with compatible **locked** Composer/npm dependencies. No React, Next.js or Bootstrap. shadcn-vue Backoffice styling is **later**, not required for this minimal first task.
- Implement one minimal **GET /** Laravel→Inertia→Vue page whose copy is truthful and clearly foundation-only, not the final public CMS site. Do not fabricate speaker/venue/partner data or set an event start time.
- Use existing Herd `http://iches-unisya.test/` and PHP 8.4; **no `php artisan serve` workaround**, no moving to another folder or touching another linked site.
- For database-independent smoke, use local file/cache/session drivers and sync queue as needed. Create **untracked** local environment configuration only if required to boot, with no real DB credentials; never show/commit secrets. A generic `.env.example` can be added if sanitized.
- Add small **DB-free** feature smoke tests asserting GET / HTTP 200 with Inertia page and an unregistered route returns HTTP 404. Do not claim these pass without running them.

**Allowed application paths, strictly as needed:** `artisan`, `app/**`, `bootstrap/**`, `config/**`, `public/**`, `routes/**`, `resources/**`, `tests/**`, `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.*`, `tsconfig*.json`, `phpunit.xml`, `.env.example`, `.gitignore` (only secure exclusions) and scope-required test/build files. Evidence exception: append-only `harness/build-log.md`, sanitized `harness/evidence/**`.

## 4. Explicit exclusions / stop conditions

- **NO** MySQL connections, migration/rollback, seeding, UUIDv7 schema, `users` / `events` tables, DB permissions, real email, production changes or unrelated project directories. `ICH-S0-02` remains separately gated.
- **NO** default password auth/register/reset forms; participant auth is **passwordless OTP/Magic Link** under approved RD-06 and is **outside scope**.
- **NO** CMS, full website/countdown, dashboard/admin, academic submission, bank/finance, Co-Host, QR/OJS, production deployment or invented business data.
- **NO** editing `README.md`, `WORKSPACE.md`, `PLANS.md`, `AGENTS.md`, `docs/**`, `.agents/skills/**`; no auto-merge, force push or changes on `main`.
- If a dependency or starter kit tries to create password login/DB schema, stop and use an explicitly scoped, safer skeleton. If Node 26/package support fails, report the exact error rather than silently changing approved targets.

## 5. Real acceptance tests

| Test | Command/check | Required evidence |
|---|---|---|
| Composer | `composer validate --strict` | Exit code, no malformed manifest |
| Framework | `php artisan --version`; `php artisan route:list` | Laravel 13 and GET / route |
| PHP smoke | `php artisan test --filter=FoundationSmokeTest` (or actual test name) | Real DB-free GET / 200, unknown path 404 |
| Frontend build | `npm.cmd install` then `npm.cmd run build` | Successful Vite compiled assets; lockfile |
| TS check | `npm.cmd run typecheck` if script provided, otherwise installed `vue-tsc` equivalent | No TS error, actual command captured |
| Herd smoke | `curl.exe -sS -o NUL -w "%{http_code}" http://iches-unisya.test/` | **HTTP 200**, app's Inertia/Vue page works |
| Git scope | `git diff --name-only`; `git status --short` | No unauthorized docs/secrets/unrelated edits |

Commands are a **test plan**, not results; Codex inspects actual scripts before running. Use `npm.cmd` on PowerShell; **do not change execution policy** to work around `npm.ps1`. No DB migrations in this work order.

## 6. Required Codex handoff

Report **Task ID, GO evidence, base SHA, feature branch, changed code files, installed dependency versions, exact commands/exit codes, HTTP result/screenshot, scoped diff, unresolved blockers, sanitized log and feature PR if created**. ChatGPT reviews results and maintains the single 19F board; **only owner authorizes acceptance/merge/deployment**.

## Short prompt to paste into Codex — ONLY AFTER NAMED GO

```text
ICHES GO CODING ICH-S0-01: First verify the owner issued this exact GO, governance PR #1 has been merged, and the main base SHA is frozen in docs/21B-ICH-S0-01-FOUNDATION-WORK-ORDER.md. Work ONLY in D:\PINJAM-AFNAN\Herd\iches-unisya. Read and obey that full 21B Work Order, AGENTS.md, current board, and engineering + verification Skills. From clean approved main create feat/ich-s0-01-foundation, bootstrap DB-independent Laravel 13/Inertia/Vue 3/TS/Tailwind4/Vite, and prove the Herd URL returns HTTP 200. Do not modify docs/governance or use password auth, migrations, real DB credentials, old payment logic or other sites. Report real exit codes, branch, code diff, sanitized evidence, failures. No auto-merge or deploy; STOP if blocked.
```

**Status remains PREPARED/NO_GO until PR merge + baseline SHA + explicit owner GO.** This first DB-free task does not imply Sprint 01 or database readiness.
