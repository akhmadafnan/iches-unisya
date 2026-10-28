# 21K-01 — ICH-S0-02 Independent Local Code & MySQL Review: REV-03

> **28 Oct 2026 · HISTORICAL REV-03 FINDING — RESOLVED LOCALLY.** Codex corrected the defect and ChatGPT independently reviewed commit `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb` with DB tests 8/134 and SQL metadata verification PASS. Current local status is **REVIEW_PASS / owner push approval pending**. See [21K-02](21K-02-S0-02-REV03-FINAL-LOCAL-REVIEW.md). Owner GO for ICH-S0-02 was already granted. This review is **not a new task GO** and does **not authorize pushing a feature, creating a PR, merging, database reset or deployment**. One bounded integrity correction is needed on the same local feature branch; Codex VS Code remains sole source-code writer.

## Verified Git status and scope

- Canonical local repo `D:\PINJAM-AFNAN\Herd\iches-unisya`, branch `feat/ich-s0-02-db-foundation`.
- Exact HEAD **`78bc6d9a904c86b334c28f645d908452295ba26e`**, direct parent/current feature baseline **`3ad72cfb99f4f5015203efb81f29563d49b6357c`**.
- Worktree **clean**, origin `https://github.com/akhmadafnan/iches-unisya.git`, `.env` **ignored / not tracked**, `git diff --check` exit 0.
- **Exactly 13 changed files** (+545/-3): `.env.example`, `config/database.php`, four new models (`User`, `Institution`, `Event`, `RoleAssignment`), three read-only policies, one migration `2026_10_28_000001_create_identity_and_event_foundation.php`, `tests/Database/DatabaseFoundationTest.php`, `tests/Support/FoundationFixtures.php`, and append-only `harness/build-log.md`. No dependency/lockfile, CI workflow, existing smoke-test or other governance changes.
- Main domain entity PKs are UUIDv7 (`users`, `institutions`, `events`), associated FKs are UUID `char(36)`; `role_assignments.id` uses BIGINT and is **valid under RD-04 selective UUID policy**. Passwordless participant requirement remains compatible (no required password column). The future official event start time is not invented.

## Independent re-execution (no persistent DB writes)

| Verification | Observed outcome |
|---|---|
| `composer validate --strict` | exit **0**, PASS |
| `php artisan test --display-warnings` | exit **0**, **4 tests / 36 assertions**, DB-free |
| `php vendor/bin/pint --test` | exit **0**, **28 files**, PASS |
| `npm.cmd run typecheck` | exit **0**, PASS |
| `npm.cmd run build` | exit **0**, Vite 7.3.7 / 764 modules / built in 1.33 seconds |
| `git diff --check HEAD^ HEAD` | exit **0** |
| Git clean after checks | PASS |

Codex reported **7 opted-in database tests / 95 assertions**, exit 0, and actionlint PASS. The independent reviewer inspected those test bodies in full and verified read-only schema metadata, **but deliberately did not rerun integration tests that temporarily write rows**, to keep the review strictly read-only. Do not misstate 7/95 as independently rerun or claim GitHub Linux/Node22 CI for unpublished SHA.

## Independently verified MySQL persistence state (read-only PDO)

- Authenticated successfully from ignored local `.env`; `SELECT DATABASE()` = **`iches_unisya`**, server MySQL **9.7.1**, default **InnoDB**, `@@foreign_key_checks=1`.
- Actual tables, all InnoDB: `users` **0 rows**, `institutions` **0**, `events` **0**, `role_assignments` **0**, `migrations` **1**. This independently supports that synthetic fixture rows were rolled back.
- The `role_assignments` table currently has `id BIGINT UNSIGNED AUTO_INCREMENT`, relevant FKs `char(36)`, and stored generated column `institution_scope` = `coalesce(institution_id,'')`.
- Actual unique index `role_assignments_scope_unique` consists of exactly `(user_id, event_id, role_key, institution_scope)`. This **does not incorporate `revoked_at` or active assignment state**.

## REV-03 — Blocking schema lifecycle defect: regrant after revoke

**Observed in source AND live MySQL index:** a grant for the same user/event/role/institution combination permanently occupies that unique slot, **even if the row has `revoked_at` set**. Attempting to issue a new active grant later causes MySQL unique-key error **1062**, despite `scopeActive()` excluding the historic revoked row. Reopening the old record would overwrite useful revocation history; bypassing the unique constraint entirely would permit duplicate active grants. This is an inferred constraint failure based on actual unique index definition; no destructive or write-based reproduction was run by ChatGPT.

**Required smallest bounded correction under existing S0-02 authorization:**

1. Keep the original already-applied migration in history; create a **new, forward-only additive/corrective migration** that changes the uniqueness model to **only one active grant per user/event/role/institution scope**, while allowing multiple revoked historic grants of the same combination. A MySQL-compatible **generated active-only scope** whose value is NULL on revoked rows, and `coalesce(institution_id,'')` when active, is a possible strategy. **Do not merely append `revoked_at` to a unique index**, since MySQL allows repeated NULLs and this would permit duplicate active grants.
2. Verify the new unique index explicitly for both **event-wide NULL institution** and institution-specific assignments. Maintain UUID FKs, numeric assignment ID, existing `scopeActive()`, and fail-closed policies. No new app routes/auth flow.
3. Add non-destructive transaction-based tests: (a) active duplicate rejected; (b) revoke then create **new** active assignment with same scope succeeds; (c) the earlier revoked record and its timestamp remain intact; (d) second simultaneous active grant fails; (e) original cross-user/event/institution policy isolation still passes.
4. The schema correction must apply to the **already-migrated owner-created local `iches_unisya`** through normal Laravel `php artisan migrate`, after a target/affected-table preflight and checking for existing rows. Do not run `migrate:fresh`, `migrate:reset`, `db:wipe`, `DROP TABLE` or bulk `TRUNCATE`. No production DB action.
5. Repeat local DB integration suite, 4 smoke tests, Pint, Composer, typecheck/Vite, and inspect final row counts. Append actual evidence to `harness/build-log.md`; add a new local commit on `feat/ich-s0-02-db-foundation` **without amend/rebase**, preserving `78bc6d9a` history. **STOP before push/PR/merge/deploy.**

**Verdict:** S0-02 substantial implementation passes initial review; **REV-03 REQUIRED** for correct membership lifecycle before owner acceptance/publication. No second owner GO is needed for this bounded S0-02 correction; other future role-specific permission semantics remain S0-04 work and are not expanded here.

## Nonblocking observations / future scope

- Existing `EventPolicy` and `InstitutionPolicy` currently implement narrowly scoped **read/view** checks and default-deny unspecified mutations; this is appropriate for the foundation slice. Rich role-specific privileges, impersonation/role-switch security, global role administration and per-resource actions remain separate S0-04/auth tasks.
- UUIDv7 **does not itself prevent IDOR or data leaks**; policy checks must guard every future controller/API action. Assignment BIGINT IDs must not be exposed as proof of authorization.
- User-reported CI is still **NOT_RUN on Linux** for S0-02 feature SHA because this work is local only. Existing S0-07 main CI remains prior successful baseline, not a pass for unpublished new code.
