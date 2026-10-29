# ICH-S0-05 local verification — 29 October 2026

Owner authorization: GO CODING ICH-S0-05; additive migration authoring only. No GO APPLY MIGRATION. Work order read from `D:/PINJAM-AFNAN/ICHES/WORK-ORDERS/ICH-S0-05-WORK-ORDER.md`. Current owner GO supersedes the work order's preparation-only historical status.

Verified machine `DESKTOP-PE5D86C`; repository `D:/PINJAM-AFNAN/Herd/iches-unisya`; remote `akhmadafnan/iches-unisya`. Clean main at `010150bf5ffd4d18802d1aef1c1764d2a7cb61bd`, matching `git ls-remote origin refs/heads/main`. Feature branch `feat/ich-s0-05-file-storage`. PHP 8.4.24, Node 26.4.0. No dependencies changed.

## Implementation

- Guarded UUIDv7 StoredFile metadata; immutable model updates, hidden disk/key/checksum, event/owner/uploader relations. Draft forward-only migration with restricted FKs, unique random key, enum kind/private disk/visibility, composite scope index. Prior migrations unchanged.
- Internal intake only: current actor owns uploads; fresh selected event grant required. No generic upload or public publication endpoint, no polymorphic resource claims. Limits default to PDF 10 MiB and proof/media 5 MiB; per-kind overrides via `filesystems.private_file_limits`. Full Paper formats remain deferred.
- Fileinfo MIME plus extension/header/image checks, nonempty size, valid PHP upload, random private keys, sanitized attachment names, SHA-256 verified after write and before delivery. No client-controlled disk/path/owner/visibility. Symlink paths rejected on intake/read.
- Storage/persistence failures clean bytes; cleanup failures emit an error with file UUID only. Intake rejects ambient DB transactions: an outer rollback after return could otherwise orphan bytes. Callers must invoke intake outside a resource transaction; downstream domain binding remains a future policy task.
- Owner + active selected event grant + private metadata policy; no staff override. Authenticated, actor-bound temporary signed URLs default to 300 seconds (configurable downwards with `filesystems.private_download_ttl`). URL generation reloads metadata; delivery route binds fresh metadata and rechecks grants. Copying links, stale grants, Personal or other-event workspace cannot authorize delivery. Streamed attachment uses octet-stream, no-store/private, nosniff, and no-referrer inherited from AuthPrivacy.
- No support impersonation exists or was added. Real resource authorization must be supplied when Submission, Finance and CMS domains exist. These standalone owner files are not evidence those domain workflows are complete. Malware scanning is not implemented.

## Executed checks

| Command | Result |
| --- | --- |
| `php artisan test --compact` | Exit 0; 22 tests / 207 assertions PASS, including previous auth/RBAC DB-free tests |
| `php artisan test --compact tests/Feature/PrivateFilesTest.php` (before last additional case) | Exit 0; 9 tests / 69 assertions PASS; final complete suite includes 10 file tests |
| `php artisan test --compact tests/Database/StoredFileTest.php` | Exit 0; 3 SKIPPED, 0 assertions; no DB opt-in, connection or migration |
| `composer validate --strict` | Exit 0, PASS |
| `npm.cmd run typecheck` | Exit 0, PASS |
| `npm.cmd run build` | Exit 0, PASS, Vite 7.3.7 |
| `vendor/bin/pint --test` on all 8 task PHP paths | Exit 0, PASS |
| `php -l` on all 8 task PHP paths | Exit 0, PASS |
| `git diff --check` | Exit 0, PASS |
| Whole-repository `vendor/bin/pint --test` | FAIL: 22 unchanged baseline files with CRLF and/or existing formatting differences; task files clean. No unrelated formatting changes made |

Initial `npm run` commands failed because PowerShell blocks npm.ps1; using npm.cmd succeeded without modifying execution policy. Initial private visibility assertion failed because Windows lacks POSIX chmod semantics. Final test checks private/public root separation and disabled local serving on Windows; POSIX visibility assertion is retained for non-Windows execution. Native Windows ACL isolation was not audited.

DB-free tests replace metadata persistence/binding and grant-query results with in-memory doubles while exercising real validation, Gate policies, Laravel HTTP/session/signature middleware and fake filesystem I/O. Coverage: UUIDv7, immutable unique file versions, hidden paths, valid PNG/PDF, spoof/extension/empty/oversize/disallowed kind rejection, filename sanitization, denied intake, failed write, metadata failure cleanup, ambient transaction guard, login, signature tamper/expiry/missing signature, URL reload/expiry, copied account URL, owner/event/workspace/revocation denial, no public writes, same-length checksum corruption, truncated/missing file and unsafe path/disk denial. No real DB persistence, FK, SQL rollback or browser end-to-end success is claimed.

## Pending gates and limitations

- Migration `database/migrations/2026_10_29_000004_create_stored_files_table.php` is DRAFT ONLY. No migrate, pretend, seed, schema changes, SQL writes, or DB test opt-in performed.
- MySQL persistence, FKs/indexes, actual role revocation/IDOR, transactional fixture rollback, real local-storage end-to-end and concurrent successful intake remain unverified until separate GO APPLY MIGRATION and authorized integration checks. The three opt-in Database tests never migrate and use rollback for their fixtures. Successful intake commit and concurrency still need additional integration evidence.
- Existing auth/RBAC MySQL tests were not run; their DB-free regression tests pass. Linux PHP 8.4/Node 22 CI was not run, as no push/PR is authorized.
- Filesystem and SQL cannot share an atomic transaction. Cleanup is attempted on observed failure, with UUID-only error logging if cleanup itself fails; a process crash/storage outage still needs operational orphan reconciliation before production.
- No UI changed. No screenshots or completed CMS/Finance/Submission workflow claimed.
- Stop after one local commit. No push, PR, merge, migration execution or deployment.
