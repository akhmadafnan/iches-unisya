# 21K-02 — ICH-S0-02 REV-03 Independent Final Local Review PASS

> **28 October 2026 · S0-02 LOCAL REVIEW_PASS / REV-03 CLOSED / OWNER_PUSH_APPROVAL_PENDING.** Feature remains local only; **no GitHub Linux/Node22 CI run for S0-02 SHA** and no push/PR/merge/deployment authorization. ChatGPT independently inspected exact code and reran the tests after Codex correction.

## Verified local Git history and scope

- Canonical Windows repo: `D:\PINJAM-AFNAN\Herd\iches-unisya` / `https://github.com/akhmadafnan/iches-unisya.git`.
- Branch: `feat/ich-s0-02-db-foundation`, **HEAD `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`**, direct parent **`78bc6d9a904c86b334c28f645d908452295ba26e`**, with original baseline **`3ad72cfb99f4f5015203efb81f29563d49b6357c`** preserved. Clean working tree, feature has **no upstream** (not pushed).
- REV-03 commit **exactly 3 changed files, all additive (+97/-0)**: new `database/migrations/2026_10_28_000002_make_role_assignment_uniqueness_active_only.php` (+23), extended `tests/Database/DatabaseFoundationTest.php` (+41), append-only `harness/build-log.md` (+33). `git diff --check HEAD^ HEAD` exit 0.
- Previous first migration `2026_10_28_000001_create_identity_and_event_foundation.php` preserved **unchanged**; no CI, existing app models/policies, dependency manifests/lockfiles, credentials or governance files changed in this commit.
- Cumulative S0-02 implementation on feature branch is earlier **13 scoped files** plus the **new REV-03 migration**; the other two REV-03 paths were already tracked by initial implementation. Remote origin/main contains parallel **docs-only governance** commits since the original feature base, not feature source code.

## Correctness of new migration and tests

- New migration modifies only `role_assignments.institution_scope`, keeping it **nullable, stored generated** as:
  ```sql
  CASE WHEN revoked_at IS NULL THEN COALESCE(institution_id, '') ELSE NULL END
  ```
- The existing `role_assignments_scope_unique` index remains on `(user_id,event_id,role_key,institution_scope)`. MySQL allows duplicate NULL values, so historic revoked rows can repeat scope; active event-wide assignments have non-null empty string and institutional assignments have a non-null UUID, so only **one active row** per user/event/role/institution scope is accepted.
- The new regression test covers both null institution and actual institution: two revoke/new-grant cycles, preserved two historic assignments and their original revocation timestamps, one current active row, rejection of duplicate active grant and of reactivating an old row, and cross-scope read isolation. First migration, UUIDv7 entity PKs and matching UUID foreign keys unaffected.
- **Known deliberate limitation:** `down()` raises `LogicException` to prevent an unsafe automatic restoration of the old uniqueness rule after historic regrants. A future rollback would require a custom controlled data reconciliation and an owner-authorized schema change; this does not block forward-only S0-02 development and must not be misrepresented as “rollback tested PASS.” No destructive migration rollback was performed.
- Previously reported TDD RED before fix: MySQL 1062, exit 2; after fix GREEN. The independent reviewer inspected TDD evidence and reran only GREEN suite, not the historical RED reproduction.

## Independent runtime validation (actual execution)

| Check | Reviewer result |
|---|---|
| `ICHES_DB_TESTS=1 php artisan test tests/Database --display-warnings` | **exit 0: 8 PASS / 134 assertions**; transaction-wrapped synthetic rows rolled back |
| `php artisan migrate:status` | **exit 0**; first migration **batch 1 Ran**, correction **batch 2 Ran** |
| `php artisan test --display-warnings` | **exit 0: 4 PASS / 36 assertions** (DB-free original smoke) |
| `php vendor/bin/pint --test` | **exit 0: 29 files PASS** |
| `composer validate --strict` | **exit 0 / valid** |
| `npm.cmd run typecheck` | **exit 0 / vue-tsc PASS** |
| `npm.cmd run build` | **exit 0**, Vite **7.3.7**, **764 modules**, completed |
| `git diff --check HEAD^ HEAD` | **exit 0** |
| Local Git worktree after all checks | **clean** |

The reviewer reran the opt-in real MySQL integration tests only after directly inspecting that they wrap fixtures in per-test transactions and `tearDown()` rolls back all levels. These test runs did temporarily insert synthetic rows, but independently verified read-only SQL afterward confirmed **no persistent synthetic data**.

## Read-only MySQL schema audit after tests

- Authenticated only to owner-selected local `iches_unisya`; MySQL default InnoDB, FK checks enabled.
- Actual tables `users`, `institutions`, `events`, `role_assignments`: **0 rows each**; `migrations`: **2 rows**. All five are InnoDB.
- `institution_scope`: **varchar(36), nullable YES, STORED GENERATED**; live expression contains `CASE WHEN revoked_at IS NULL THEN COALESCE(institution_id,'') ELSE NULL END`. Existing unique index lists `user_id`, `event_id`, `role_key`, `institution_scope`, all unique.
- No SQL DDL, reset, DROP, TRUNCATE or migration executed by the independent reviewer. The DB test suite performed temporary transactional writes and rolled them back; read-only verification followed.
- No actual Linux CI (S0-02 commit unpublished), no claims of production authentication or future RBAC completeness. Current read/view policies are scoped foundation guards, with full role-specific RBAC still a separately named later task.

## Disposition / owner publication gate

**REV-03 technical verdict: REVIEW_PASS / CLOSED locally.** The original S0-02 database foundation is technically ready for owner acceptance and a **specific approval to push exact feature HEAD `f4eb25c31763da1e9bf2526e5a1440d5cb33f4eb`** through Codex VS Code only, with ordinary **no-force** push of **`feat/ich-s0-02-db-foundation`**. Previous S0-02 GO authorized only local coding/migrations, not publication.

Once owner authorizes push, Codex must first confirm exact SHA, branch, clean tree and canonical remote; never rebase/reset/amend, force-push or change code. After remote SHA verification, ChatGPT can open PR and inspect new Ubuntu PHP8.4/Node22 GitHub Actions on the exact SHA. **PR merge, other coding, database reset, further migrations and deployment remain separately approval-gated.**

References: [21K-01 earlier REV-03 findings](21K-01-S0-02-INDEPENDENT-REVIEW-REV03.md), [21J-05 GO contract](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md), [19F board](19F-CURRENT-SPRINT-BOARD.md).
