# 21J-05 — GO CODING ICH-S0-02: Codex VS Code Execution

> **28 Oct 2026 · EXPLICIT OWNER GO RECEIVED / CODING AUTHORIZED.** Owner-created DB reported as **`iches_unisya` (underscore)**. This task is authorized; do not demand another GO to code S0-02 or run normal additive migrations after safe database verification. Only Codex VS Code edits application code. Stop before push/PR/merge/deployment.

## Execute on the canonical project

1. Work only in `D:\PINJAM-AFNAN\Herd\iches-unisya`, GitHub origin `akhmadafnan/iches-unisya`. Read `AGENTS.md`, `WORKSPACE.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, [21J-04 latest decision](21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md), [21J-03 work order](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md), `.agents/skills/iches-codex-engineering/SKILL.md` and `.agents/skills/iches-code-verification/SKILL.md`. Last local checkout was clean on old `feat/ich-s0-07-ci`, local `origin/main` stale. Check clean Git status and remote; `git fetch origin main`; switch to main and `git pull --ff-only origin main` only if clean. **Never reset/rebase/force push/amend accepted work.** New branch `feat/ich-s0-02-db-foundation` from current main.
2. **First do read-only MySQL authentication:** local host `127.0.0.1:3306`, owner-designated temporary root account, password stored/entered only locally without printing it. Query `SELECT DATABASE()` with exact selected `iches_unisya`; inspect tables and engine only **inside this database**. STOP if login fails, schema name differs, unexpected pre-existing tables/data appear, or ownership cannot be established. Root may access other DBs; never enumerate or change them.
3. Existing `config/database.php` intentionally has `connections=[]`. Implement Laravel 13 MySQL connection driven by `DB_*` env vars, and set only Git-ignored local `.env` with `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=iches_unisya` and owner-approved local root account. **Never commit or print password; keep CI `DB_CONNECTION=disabled` and no DB service/secrets.**
4. Minimal identity/events foundation from approved ERD: UUIDv7 primary keys and consistent FKs, users/institutions/events and scoped role/membership relation as appropriate; selective constraints/indexes and models. Participant passwordless Email OTP/Magic Link compatibility is mandatory; do not force participant passwords or build login yet. **No CMS, payments, abstracts, reviewers, finance, fake official event start hour or all 53 speculative tables.** Synthetic fixtures only.
5. Owner GO permits **normal additive `php artisan migrate`** on the verified dedicated `iches_unisya` schema. Run non-destructive verification of UUIDv7, unique/FK integrity, transactions/rollback and scoping. NO `migrate:fresh`, `db:wipe`, `migrate:reset`, DROP or broad truncation; stop if migration unexpectedly destructive. Preserve original 4 DB-free PHP smoke tests and GitHub CI PHP8.4/Node22.
6. Run `composer validate --strict`, PHP tests, Pint --test, npm typecheck/build, `php artisan migrate:status`, Herd HTTP smoke as appropriate; record **actual** exit codes and table names in append-only `harness/build-log.md` without leaking credentials. Commit **LOCALLY** on new feature branch and return commit SHA, diff, migration/test results and blockers.
7. **STOP before feature push, PR, merge, other task or deployment**; these require separate Owner consent.

## Copy into Codex VS Code

~~~text
GO CODING ICH-S0-02 — EXECUTE NOW (OWNER AUTHORIZED)
Canonical repo: D:\PINJAM-AFNAN\Herd\iches-unisya
Owner-created DB: iches_unisya (UNDERSCORE; not iches-unisya). MySQL 127.0.0.1:3306, temporary local root. Password only in ignored local .env, never log/commit.
Read origin/main:docs/21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md and origin/main:docs/21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md plus AGENTS.md, WORKSPACE.md, 19F and engineering/verification Skills.
Check clean repo; git fetch origin main; safely fast-forward main; create feat/ich-s0-02-db-foundation. First confirm authenticated SELECT DATABASE() = iches_unisya, inspect only that DB and stop if wrong/unexpected.
Implement Laravel MySQL configuration + minimal identity/events UUIDv7 migrations/models/constraints and synthetic tests without affecting DB-free CI. GO allows normal non-destructive php artisan migrate ONLY after DB check. Do not reset/drop/wipe.
Run local PHP/Pint/Composer/Node build checks and scoped DB integrity tests, append sanitized evidence, commit local, STOP before push/PR/merge/deployment. Report SHA, changes, tables and real exit codes.
~~~
