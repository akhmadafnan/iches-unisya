# 21J-02 — LOCKED: ICHES Development Database Exact Name and Local Access

> **LATEST OWNER GO 21J-04/05:** Database `iches_unisya` (UNDERSCORE) owner reports created and **GO CODING ICH-S0-02 granted**. Earlier hyphenated `iches-unisya`/NO_GO statements are historical. [21J-04](21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md), [21J-05](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md).

> **OWNER DECISION 28 October 2026 · RD-03C — SUPERSEDES RD-03B ON DATABASE NAME & TEMPORARY LOCAL ACCOUNT.** The owner will create the database **MANUALLY** in phpMyAdmin; its exact name is `iches-unisya` (**hyphen, not underscore**). Local development may temporarily use the existing MySQL `root` account and its owner-provided local password. To avoid disclosing credentials in this PUBLIC GitHub repository, **the password value must never be written here, in PRs, logs, code examples or GitHub Actions**. This is not production credential policy and not permission to create/drop/modify DB through ChatGPT.

## Locked practical decisions

- One dedicated local primary MySQL/InnoDB development schema: **`iches-unisya`**. Replace former `iches_unisya` (21J-01); *do not also create the underscore variant*. No extra mandatory `iches_unisya_test` schema, no separate `iches_test` user.
- Owner creates the DB manually via **phpMyAdmin** (`https://phpmyadmin.test/index.php`) using **Databases → Create database** with the exact string `iches-unisya` and `utf8mb4` / a compatible default collation. Do not create tables manually; Laravel migration ownership belongs to Codex in S0-02 after GO. If a DB with this exact name already exists, STOP and verify it is exclusively ICHES before using it.
- The hyphen is legal in a MySQL schema identifier, but its name must be **quoted with backticks in manual SQL**. If using the SQL tab instead of the GUI, the intended example is:

```sql
CREATE DATABASE `iches-unisya` CHARACTER SET utf8mb4;
```

  In Laravel, `DB_DATABASE=iches-unisya` is a regular dotenv value without SQL backticks. This is only an example; **do not execute if the schema already exists**.
- Local-only development `.env` connection (to be wired by Codex later): `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, `DB_DATABASE=iches-unisya`, `DB_USERNAME=root` and `DB_PASSWORD` populated **locally using the owner-designated credential**; actual password value is intentionally not written in this public document. No printing/showing `.env` contents in logs, chat, screenshots, CI or Git.
- **Root is a time-limited local development shortcut**, not an account to ship to production. It can access other local databases, so before the first migration Codex must confirm the selected database using **read-only `SELECT DATABASE()`** and that the specific schema belongs solely to ICHES. No SQL or privilege changes until owner confirms creation and provides a separately named GO for coding. Limit MySQL/phpMyAdmin access to loopback; do not open root to LAN or the internet.
- Current merged Laravel foundation's `config/database.php` intentionally contains `connections=[]` and `.env.example` still uses `DB_CONNECTION=disabled`. **Changing `.env` alone will not activate MySQL**, and this policy does not authorize ChatGPT to edit application code. Codex must add a real MySQL connection configuration in **ICH-S0-02**, preserving GitHub CI's **DB-free** mode.

## Fast-track, not excessive bureaucracy

- Owner makes the exact database in phpMyAdmin; share only **“database `iches-unisya` sudah dibuat”**, not access credentials/screenshots that include secrets.
- One next task, `ICH-S0-02`: Codex VS Code updates the Git-ignored local `.env`, adds MySQL config/migrations for identity/events/UUIDv7, verifies schema ownership, applies normal **`php artisan migrate`** only with a named GO and after user-confirmed database creation. Synthetic test records only.
- Maintain automatic GitHub DB-free tests and add targeted non-destructive integration checks; **do not use `migrate:fresh`, `db:wipe`, `DROP` or bulk `TRUNCATE` on the one development schema** as routine test behavior. Destructive test resets require explicit per-action approval (avoids accidental loss in a shared root-capable MySQL server).
- Do not delay normal feature development for a full security bureaucracy. Before production: harden accounts/permissions, change exposed development credentials, establish backups/restore and rollback, check auth/roles/permissions/OWASP-relevant vulnerabilities, file access, CI/tests and UAT. A fresh production database is preferred. Trial-data deletion or reusing development DB in production remains **separate owner-authorized** cutover.
- No database/SQL creation, `.env` edits, source code, migrations, deployment or new task GO was performed when this decision was recorded.

## Governance precedence

- **RD-03** MySQL/InnoDB: retained.
- **RD-03B** one dedicated development schema: retained, but its `iches_unisya` name and dedicated `iches_dev` account suggestion are **superseded by RD-03C**. Keep historical text traceable rather than erasing decisions.
- **RD-03C** exact `iches-unisya`, manual phpMyAdmin creation, temporary local root connection and fast task `ICH-S0-02`: **CURRENT FINAL**.
- [21J-03 next Work Order](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md), [09 Decision Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md), [19F Sprint Board](19F-CURRENT-SPRINT-BOARD.md).
