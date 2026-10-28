# 21J-01 — OWNER DECISION: Single Primary ICHES Development Database

> **DECIDED 28 October 2026 · RD-03B (supersedes ONLY the separate `iches_unisya_test` database requirement in RD-03) · DATABASE NOT YET PROVISIONED / NO GO CODING OR SQL.** Owner said: “untuk DATABASE kita kunci menggunakan database utama. urusan nanti ketika akan production kita drop isinya itu nanti ... agar kita tidak ribet ... TRIAL/ERROR ... DEVELOP”. The intent is to reduce repeated test-schema administration during development. This document is authoritative for current DB environment policy, replacing the earlier 20E test-only provision runbooks as the default. It does **not** alter MySQL/InnoDB, UUIDv7, Laravel authentication, or the established code/merge authorization boundaries.

## Locked decision: one ordinary development database

- **Database engine:** MySQL/InnoDB, existing local MySQL service on `127.0.0.1:3306`. Only version 9.7.1 native handshake and port reachability were verified; **successful authenticated SQL login, DB ownership and privileges remain NOT VERIFIED**.
- **Primary development schema name (working canonical name): `iches_unisya`**. It must be a **separate schema devoted exclusively to ICHES**, on the user's local development MySQL server. It is **not** MySQL's `mysql` system schema, a schema of SIP-Hibah/another application, a production customer-data schema, or an existing shared database.
- **One** ICHES application schema for day-to-day local development, integration work, and synthetic manual trials. **A second persistent MySQL database named `iches_unisya_test` is NOT MANDATORY** as a prerequisite for S0-02 or development. That former proposal and separate `iches_test` account are **SUPERSEDED, not to be auto-provisioned**.
- **Application account (suggested scoped local name): `iches_dev`** (final credential created only during separately authorized setup), granted only on `iches_unisya.*`, no global grants, no existing root credential in Laravel. Store credentials only in **untracked local `.env`**, never repo docs/logs/CI/actions/chat.
- The string `iches_unisya` is an intended target **not evidence it already exists**. If an object/schema with that name exists, **STOP** and verify ICHES ownership and data state before reusing, renaming, overwriting or dropping anything. Do not enumerate/open unrelated schemas.
- `DB_CONNECTION=mysql`, `DB_DATABASE=iches_unisya` and other `DB_*` settings apply **only to explicitly authorized local development configuration**. The merged ICH-S0-01's current `config/database.php` has `connections=[]`, and its existing CI forces `DB_CONNECTION=disabled`; no application DB integration exists yet and no change to code/env is authorized by this decision alone.

## Trial/error without sacrificing safety

1. Codex can implement approved migrations `up()` and deliberately scoped `down()` and synthetic seeders once **ICH-S0-02** receives named GO; routine forward development uses **`php artisan migrate`**, never automatic `migrate:fresh` as a prerequisite.
2. No `migrate:fresh`, `migrate:reset`, `db:wipe`, `DROP DATABASE`, `DROP TABLE`, broad `TRUNCATE`, or bulk deletion on the primary schema by default. Each **destructive** test/reset needs verified sole ICHES schema ownership, disposable data, backup/restore evidence and **specific owner approval for that operation**, not merely GO CODING S0-02.
3. Use **database transactions/rollback, controlled fixtures and isolated records** for non-destructive verification. App PHPUnit/CI foundation remains **DB-free**. Do not repoint GitHub Actions to local MySQL or make destructive automated tests share the primary schema.
4. Seed only demonstrably **synthetic** people, transactions, papers, event/content records; keep a cleanup plan and avoid real participant data or real payment records. Keep immutable records/audits needed for debugging until explicitly approved purge.
5. If migration development encounters a destructive schema redesign, first prepare a safe recovery plan and request separate authorization rather than running broad drops merely for convenience.

## Before production: clean release, never blind DROP

- The owner's request to later remove trial data is accepted as a **future production cutover/cleanup gate**, **not permission to execute DROP today or on a live production database**.
- Preferred release is a **fresh, separate production environment/database** initialized from tested Laravel migrations and approved legitimate seed/reference data. It may use the same logical schema name on a different host, but must never share development credentials or application test data.
- If the owner later explicitly chooses to reuse the **same development schema** for production, this is a **separate higher-risk decision**. Before any cleanup: verify it contains only ICHES trial data, take and verify a restorable backup, inventory/preserve approved configuration and non-trial content, produce an exact table/data reset plan and obtain owner approval for target identifiers and execution. Do not delete real users, payments, audit records or production data.
- Never equate “empty development records” with “wipe every table” or “drop schema”: preserve structural migrations and whatever reference data is needed. No automatic purge or deployment.

## S0-02 gated plan — no GO issued

**Current status: `DB_POLICY_LOCKED / PRIMARY_DEV_DB_NOT_VERIFIED / ICH-S0-02 NO_GO`.** Next non-destructive step is owner-controlled phpMyAdmin access to **this one schema**, checking name collision, MySQL account privileges and InnoDB/FK/JSON compatibility, without inspecting unrelated databases and without sharing credentials. Creating schema/user requires **explicit future provisioning permission**. Codex application source/config/migrations require a separate **`GO CODING ICH-S0-02`** with a work order adapted to this one-database model. Current CI/PR #5 baseline is unchanged; no SQL was run while recording this decision.

## Supersession and traceability

- **Old RD-03** (20D): MySQL/InnoDB itself **stays locked**; its *mandatory isolated disposable test DB* clause is replaced by this **RD-03B** decision.
- Earlier `20E`, `20E-01`, `20E-02` are historical test DB setup proposals, **not active instruction to provision `iches_unisya_test` or `iches_test`**.
- [09 decision register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md), [19F current board](19F-CURRENT-SPRINT-BOARD.md), [20D technical baseline](20D-PRE-02-TECHNICAL-DECISION-PACKET.md), [19B S0-02 backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md).
