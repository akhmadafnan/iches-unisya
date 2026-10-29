# ICH-S0-05 REV-01 — real persistence evidence

29 October 2026; owner GO CODING REV-01. Machine DESKTOP-PE5D86C; workspace `D:/PINJAM-AFNAN/Herd/iches-unisya`; branch `feat/ich-s0-05-file-storage`; clean baseline/parent `4d6380073e4bd573923b5fc6a77609e1d175f248`. Work order read once from `D:/PINJAM-AFNAN/ICHES/WORK-ORDERS/ICH-S0-05-REV01-CODEX-GO.md`.

Only application-related addition: `tests/Database/PrivateFilesPersistenceTest.php`, containing one positive-path test. No production source, schema, migration, dependency or configuration changes.

The test gates on process-only `ICHES_DB_TESTS=1` and `ICHES_FILE_DB_TESTS=1`, requires local workspace APP_ENV from .env (PHPUnit itself forces testing), checks exact MySQL host/port/schema, selected database, FK checks, applied migration batch 4, zero ambient transaction and the real private storage root. Credentials/configuration contents are not printed or changed. Existing presenter@iches.test, participant@iches.test, iches-demo-local event and the presenter's active grant are read and reused. No accounts/events/grants are created or changed by the new test.

The real service stores a harmless PDF with marker `ich-s0-05-rev01-<UUIDv7>.pdf`, without Storage::fake or an outer transaction. Assertions verify UUIDv7, freshly queried owner/uploader/event/kind/MIME/size/disk/visibility/name/key/checksum metadata, physical bytes, disk and row count increases, hidden serialization fields, and committed visibility through a separate PDO connection. Real URL generation plus authenticated Laravel HTTP download returns exact bytes; the existing other demo account receives 403.

try/finally cleans only the run's exact marked row and random storage key, after verifying identity/scope and absence from the preexisting file inventory. It also verifies restored six-table counts, identical private file inventory and unchanged fingerprints of the two demo users/event/grant. Cleanup succeeded on both executions (targeted and complete MySQL suite).

## Serial verification

Process-only opt-ins were set for each MySQL command and removed in finally; no overlapping PHP tests or fake-storage runs.

| Command | Exit | Actual result |
| --- | --- | --- |
| Independent read-only PHP bootstrap/count probe before tests | 0 | APP_ENV local, exact owned target, FK on, batch 4; counts below |
| `php artisan test tests/Database/PrivateFilesPersistenceTest.php --display-warnings` with both opt-ins | 0 | 1 PASS / 72 assertions; no skips/warnings |
| `php artisan test tests/Database --display-warnings` with both opt-ins | 0 | 25 PASS / 516 assertions; no skips/warnings |
| `php artisan test --compact` without opt-ins, after MySQL suite | 0 | 22 PASS / 207 assertions |
| Independent read-only PHP bootstrap/count probe after all tests | 0 | Identical counts and physical private file count |
| `composer validate --strict` | 0 | PASS |
| `php vendor/bin/pint --test tests/Database/PrivateFilesPersistenceTest.php` | 0 | PASS |
| `git diff --check` and final staged diff check | 0 | PASS |

Independent before and after counts: users=4, events=1, role_assignments=2, auth_challenges=2, stored_files=0, migrations=4; physical private files=1 before and after. Existing migration 000004 remained batch 4. The complete existing regression suite includes its transaction-scoped seeder idempotency test; that suite's fixtures/changes rolled back and all persistent counts remained unchanged.

No blocked tests or demonstrated production defect. No migration command, reset, schema change, push, PR, merge, deployment or other-project change. TypeScript/build omitted because only a PHP test/evidence changed. Windows ACL auditing, process-crash recovery and production readiness are not claimed. A killed process can bypass finally and leave the recognizable UUID-marked row/file; reconciliation must verify exact ID/key/owner/event before removing such an artifact. SQL and filesystem remain separate systems.

Stop after exactly one additional local commit; previous commit preserved. Independent review/owner acceptance remains separate.
