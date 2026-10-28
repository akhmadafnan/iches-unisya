# 20E — MySQL Isolated Test Database Readiness

> **28 October 2026 · G0 PARTIAL · READ-ONLY PRE-FLIGHT COMPLETED · NO GO CODING.** This is the database readiness runbook for the owner's approved ICHES Laravel 13 / PHP 8.4 / MySQL/InnoDB / UUIDv7 baseline (RD-01–RD-06, [Decision Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md)). **Never treat TCP availability as authenticated test database readiness.**

## 1. Verified directly on user-selected Windows desktop

| Probe | Actual evidence | State |
|---|---|---|
| Windows MySQL service | `MySQL97` Running; HerdHelper also Running | PASS / service |
| Server version | MySQL 9.7.1 from native unauthenticated protocol handshake and Windows binary metadata | PASS / read-only |
| Loopback network | `127.0.0.1:3306` accepts TCP; protocol version 10 | PASS / transport |
| MySQL command-line client | `C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql.exe` version **9.7.1**, MySQL Community Server GPL | PASS / tool present |
| MySQL CLI PATH | `mysql` / `mysqlsh` were not resolved from PATH; use the absolute `mysql.exe` path; no PATH change needed | INFO |
| Authenticated test user | **No credential read or login attempted** | NOT_VERIFIED |
| Dedicated test schema | No test schema verified; no `CREATE DATABASE` run | NOT_VERIFIED |
| InnoDB, FK, DDL grants | No authenticated SQL executed; server capability is not assumed | NOT_VERIFIED |
| Migration, rollback, Laravel app tests | Not performed; still NO GO Coding | NOT_RUN |

**Safety:** No passwords/credentials inspected, read or transmitted; no SQL, database or data modifications. Production schemas and unknown databases were not enumerated.

## 2. Approved test-only policy and proposal

- **Proposed database name:** `iches_unisya_test`. This is a **reserved proposal**, NOT evidence it exists or permission to create it.
- **Proposed DB user:** `iches_test`. Must have rights limited to **only** `iches_unisya_test`; use a secret set locally, never in chat, GitHub, screenshots, commands using `--password=...`, or committed files.
- For application test migrations, use a **disposable isolated database** on localhost, with test-specific account and verified privileges. **No production data.**
- Database creation/user provisioning, privilege grants, migrations, and cleanup are **write operations** requiring an explicit future **DB setup authorization** and safe validation (different from a read-only audit). Do not run during this 20E stage.
- Later project environment variables must be stored in untracked local environment files/secrets, never README/decision logs; **no .env writes in this stage**.

## 3. First authenticated read-only verification (AFTER local test account exists)

On the owner's Windows terminal, use the installed executable by absolute path. Replace placeholders **only after** a test-only account and schema have been provisioned and confirmed. Password is typed **interactively in the terminal**; never provide it to ChatGPT or Codex chat.

```powershell
& "C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql.exe" --no-defaults --protocol=TCP --host=127.0.0.1 --port=3306 --user=iches_test --password --database=iches_unisya_test --execute="SELECT VERSION() AS server_version, DATABASE() AS selected_database, @@default_storage_engine AS default_engine, @@foreign_key_checks AS foreign_key_checks; SHOW GRANTS FOR CURRENT_USER();"
```

- **Do not run the command yet if the test user/schema has not been created.**
- Expected result: connects to **the designated test schema**; default engine `InnoDB`; FK checks enabled; privileges scoped exclusively to the test schema, without global grants.
- Record only sanitized facts: authenticated YES/NO; selected test database name; server version; engine; scoped grants YES/NO. **Never paste full grants, credentials, usernames with secrets, or database listings** into chat.
- `SHOW GRANTS` is read-only proof of configured privileges; it does **not** prove that the eventual migration/rollback itself passes. Actual migration/rollback validation occurs under **separately authorized Codex coding sprint** against that isolated test schema.

## 4. Pass/fail gates

| Gate | Acceptance criteria | Current |
|---|---|---|
| G0-DB-01 | Windows service/loopback/client verified | **PASS** |
| G0-DB-02 | Dedicated test account and DB exist; test-only authentication succeeds | **BLOCKED** |
| G0-DB-03 | InnoDB, FK setting and correctly scoped grants confirmed via authenticated read-only SQL | **BLOCKED** |
| G0-DB-04 | Laravel migration/rollback on disposable DB actually passes | **NOT_RUN; only after named Sprint GO** |

**Sprint ICH-SPRINT-01 may not perform schema changes while G0-DB-02/03 remain blocked.** A scoped sprint might separately authorize foundation setup only, but the current [Sprint 01 Work Order](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md) must remain **BLOCKED_G0 / NO_GO** until its listed prerequisites are met. Nothing here approves PR merge or source implementation.

## 20E admin credential preflight result

Admin access is **blocked** by MySQL 1045: a single no-password root connection and one default-profile read-only connection failed. This was **not** a DB schema or user creation attempt. Owner has approved provisioning the test DB/user, but **must authenticate locally without sharing password**. Follow [20E-01 Temporary Administrator Login](20E-01-TEMPORARY-MYSQL-ADMIN-LOGIN.md); do not guess or reset administrator credentials.

## 5. The only next operational decision

Once the owner explicitly permits provisioning a **new test-only DB and user** (never reuse existing production/admin credentials), provision in a separate setup step, then run the read-only checks above and close G0-DB-02/03. If no valid local test account exists, ask for setup approval rather than attempting guessed passwords, inspecting secrets or brute-force authentication.

**Progress owner:** ChatGPT updates [19F Board](19F-CURRENT-SPRINT-BOARD.md) after evidence. **Codex source-code work remains NO_GO** until the separately approved Sprint Work Order is READY and the owner issues named GO.
