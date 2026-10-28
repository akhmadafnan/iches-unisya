# 21K-08 — ICH-S0-03 Codex VS Code Work Order: Working Passwordless Auth + Demo Access

> **28 October 2026 · READY FOR NAMED GO / NO_GO CODING.** This is an executable bounded single-task order. Explicit [20B repository governance](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md) requires the Owner to say **`GO CODING ICH-S0-03`**. Ordinary “lanjut”/“setuju” approves planning, **not** source writes. Once GO is given, Codex may implement the entire bounded S0-03 slice without repeated approvals for each coding step. ChatGPT prepares specs/reviews/docs; **Codex VS Code alone edits source**. This Work Order does not authorize a push, PR, merge, production deploy, destructive database action or S0-04.

## Primary objective

Deliver an **actually usable** Laravel 13/Inertia/Vue authentication vertical slice: participants sign in via Email OTP or one-time Magic Link; developers can log in with uniform `password` on local-only `@iches.test` accounts; authenticated `/dashboard` and logout work. Keep S0-02 schema, UUIDv7, FK constraints, Policies and DB-free CI intact.

**The complete function/acceptance contract is [21K-07](21K-07-S0-03-AUTHENTICATION-MVP-SPEC.md). Read it before coding.** All numerical lifetimes/limits there are implementation defaults, not a production SLA.

## 0 — Preflight and current code

1. Work at `D:\PINJAM-AFNAN\Herd\iches-unisya`, canonical `akhmadafnan/iches-unisya`. Read `AGENTS.md`, `WORKSPACE.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, `docs/21K-06-DEV-DEMO-USER-SEED-POLICY.md`, 21K-07/08, applicable engineering/verification Skills and Owner's named S0-03 GO.
2. Last read-only desktop state: **clean old feature `feat/ich-s0-02-db-foundation` HEAD `f4eb25c...`**, local `origin/main` stale; remote `main` had already merged PR #6 with docs follow-ups. **`git fetch origin main`**, only when clean switch `main`, `git pull --ff-only origin main`; do not reset/rebase/merge unreviewed work or update old feature commit. Create **`feat/ich-s0-03-passwordless-auth`** from the latest main. Stop if dirty/diverged or unexpected.
3. Verify actual source: no `config/auth.php`, no auth routes/flows, no `users.password` yet; `config/session.php`, `config/mail.php` available, only Foundation landing exists. Do not assume installed Breeze/Fortify auth starter or queue service. Confirm `.env` is ignored and contains the local MySQL target; never expose credentials.
4. Before migrations or seeding, authenticate read-only and verify `SELECT DATABASE() = iches_unisya`, `127.0.0.1:3306`, InnoDB, FK on, expected S0-02 migration batches 1 and 2, table schema/owned data inventory **only on ICHES DB**. Any unforeseen tables, real data, login failure or destructive DDL -> STOP and report before writes.

## 1 — Authorized code paths, subject to GO

- `config/auth.php` (new), narrow `config/session.php` / optional `config/iches-auth.php`, `config/mail.php` only if actually needed; environment-only nonsecret additions to `.env.example`; NEVER commit user `.env`.
- `app/Models/User.php` and minimal auth challenge model; `app/Http/Controllers/Auth/**`, `app/Http/Requests/Auth/**`, `app/Services/Auth/**`, `app/Notifications/**`, route names/middleware/limiters in `routes/web.php`, `bootstrap/app.php` or service provider as appropriate.
- **New additive** migrations for nullable demo password and OTP/Magic Link challenge persistence, with matching UUID FKs where appropriate. No edits to already-executed S0-02 migrations `000001`/`000002`.
- `database/seeders/IchesLocalDemoSeeder.php` or documented equivalent; make it **explicitly local-only**, opt-in, idempotent, synthetic. No DB reset or default production seeding.
- `resources/js/pages/auth/**`, minimal `resources/js/pages/dashboard/**`, shared auth components/style in existing Vue/TS stack, plus scoped tests in `tests/Feature/**`, `tests/Database/**` and test support. Only necessary starter routes/views; no full public/CMS/front end redesign.
- Append-only `harness/build-log.md` and sanitized evidence `harness/evidence/ich-s0-03/**`. **DO NOT modify** `AGENTS.md`, `PLANS.md`, `WORKSPACE.md`, `docs/**`, Skills, unrelated CI or lockfiles without specifically needed and documented dependency reasoning.

## 2 — Build order (fast vertical implementation)

1. **Authentication config and session routes:** Laravel `web` session guard, Eloquent User provider UUIDv7 support, auth middleware, guest/protected endpoint tests, login session regeneration, logout invalidation and CSRF. Preserve public `/` Foundation page.
2. **Auth challenges:** minimal new table/model/service with hashed high-entropy Magic Link token and protected six-digit OTP, normalized email, expiry, attempts, consumed marker; single-use verified with DB transaction and row lock. New users only after successful challenge, case-insensitive normalized unique email, proper email_verified_at. No user existence leaks.
3. **Mail and endpoint UX:** request/verify forms and one-time link confirmation GET/POST; `MAIL_MAILER=log` local; request cooldown and rate limits; invalid/expired/used state and resend. No mandatory external SMTP. Use Laravel 13-supported auth/session/mail facilities without unnecessary packages.
4. **Local-only demo:** guarded-by-both `APP_ENV=local` + `ICHES_DEMO_MODE=true` login path, bcrypt/Argon secure hash `Hash::make('password')` for demo users only; nullable `users.password` addition forward migration, role-descriptive @iches.test emails, synthetic event/institution and existing event-scoped role assignments, explicit repeat-safe `IchesLocalDemoSeeder`. Do not give global superadmin/bank/finance permissions merely from name. Seeder must not resurrect revoked grants.
5. **Authenticated dashboard:** minimal current account identity + explicit logout, no personal data lists, access only as authenticated User; no Switch Workspace/impersonation or full RBAC. Respect S0-02 existing fail-closed Policies.
6. **Security/regression tests:** implement 21K-07 AUTH-01..18 tests incl. replay/expiry/rate limits/CSRF/session fixation/logout/new user UUIDv7/cross-scope protection/local demo enabled/disabled/seed reruns; test transaction rollback. Real MySQL integration tests only opt-in and pointing to exact local `iches_unisya`. Keep default CI database-free and preserve existing 4/36 smoke baseline.

## 3 — Allowed DB actions *after named S0-03 GO only*

- Normal `php artisan migrate --pretend` / `php artisan migrate` on the **confirmed single owned local dev** `iches_unisya`, additive only, no reset. Execute explicit demo seeder locally **only after** verifying `APP_ENV=local`, demo flag true, safe synthetic scope and existing domain table state, and output expected synthetic row set without secrets.
- Use only scoped transactions for temporary tests, ensure domain row counts distinguish intentionally persistent *demo seed records* from transient fixtures and user-owned data; do not declare “all tables empty” after deliberately seeding. Never broadly wipe user data.
- No `migrate:fresh`, `db:wipe`, `migrate:reset`, `DROP`, broad `TRUNCATE`, modifying other Herd/MySQL schemas, or production database. Stop if migration plan changes existing S0-02 columns destructively. Preserve original 2 migration history.

## 4 — Quality acceptance and stop

**Must demonstrate**: request OTP; inspect local mail log; verify code or magic link (one-use); login lands at protected dashboard; logout invalidates old session; local demo login succeeds with `@iches.test` and `password` only under both local+flag; demo route denied with flag OFF and when nonlocal; seeder idempotent without resetting DB; passwordless ordinary participant `password=NULL`. No credentials/tokens in git or HTTP props.

- `composer validate --strict`; `php artisan test --display-warnings` (4 existing smoke tests must pass and no accidental DB in CI); opt-in `ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings` with safe transactional setup; `php vendor/bin/pint --test`; `npm.cmd run typecheck`; `npm.cmd run build`; `php artisan migrate:status`; `php artisan route:list`; local Herd smoke/auth redirects. Browser checks responsive/keyboard/clear validation. Assert actual exit codes, not assumed PASS.
- Keep GitHub Actions default no local database/credentials; if auth-dependent HTTP tests need DB, keep in isolated opt-in test suite, not default Feature tests requiring MySQL. No new fake credential in action logs. Record real evidence and security negative cases.
- New feature branch local commits only. **STOP BEFORE PUSH, PR, MERGE, DEPLOYMENT, DATABASE RESET OR ANOTHER TASK**. Handoff: exact SHA and parent, changed files, migrations, seeded demo emails (NO hashes/password logs), test counts, failures, known security limitations, local demo URL, status of untouched S0-04/PR#3.

## Copy into Codex VS Code AFTER OWNER GO

~~~text
GO CODING ICH-S0-03 — EXECUTE THE WHOLE AUTHENTICATION VERTICAL SLICE
Owner explicitly approves ICH-S0-03 only, against docs/21K-07-S0-03-AUTHENTICATION-MVP-SPEC.md and docs/21K-08-S0-03-CODEX-WORK-ORDER.md on origin/main.
Work in D:\PINJAM-AFNAN\Herd\iches-unisya. Read AGENTS, WORKSPACE, 19F, 21K-06/07/08, Codex engineering+verification Skills.
Safely fetch/ff-only main from merged S0-02, create feat/ich-s0-03-passwordless-auth; no changes to prior feature branch.
Build Laravel 13 session web guard, production-safe passwordless participant OTP/Magic Link (hashed, 10m, one-use, 5 attempts, 60s resend, rate-limited, generic responses), mail-log local, CSRF, session regeneration, logout, guarded minimal dashboard.
For local demo only: use synthetic @iches.test user emails and shared hashed password, nullable user.password new additive migration, explicit idempotent seeder, demo login enabled ONLY with APP_ENV=local and ICHES_DEMO_MODE=true, never in production. Preserve UUIDv7 primary IDs, event/institution scope and revoked role history.
Before DB writes: authenticate exact iches_unisya, inspect migrations/state, then only additive migrate and explicit opt-in demo seeder. No fresh/reset/wipe/drop or other DB.
Execute comprehensive negative tests, preserve 4 DB-free smoke tests and DB-free CI, run Pint/Composer/TS/Vite/Herd, append sanitized build log, commit LOCAL, STOP BEFORE PUSH/PR/MERGE/DEPLOY.
Do not ask owner to repeat decided demo credentials or DB name. Report actual results and blockers.
~~~
