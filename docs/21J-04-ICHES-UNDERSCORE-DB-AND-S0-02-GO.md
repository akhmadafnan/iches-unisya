# 21J-04 — RD-03D Final: iches_unisya and GO CODING ICH-S0-02

> **28 October 2026 · OWNER DECISION LOCKED · S0-02 GO AUTHORIZED.** The owner said: “Database `iches_unisya` sudah dibuat. GO CODING ICH-S0-02. Saya tadi merubah menjadi `iches_unisya` bukan `iches-unisya`.” The **underscore name is authoritative**, replacing the earlier hyphenated RD-03C setting.

## Final local development policy

- **Exact schema:** `iches_unisya` (underscore), owner reports manual creation in phpMyAdmin. This report is not independently authenticated SQL verification.
- Existing MySQL at `127.0.0.1:3306`, InnoDB; one dedicated ICHES development database, no separate permanent `iches_unisya_test` required. UUIDv7 and consistently typed foreign keys retained.
- Temporary **local** `root` account is owner-approved; password must remain only in untracked local `.env`, never in public docs, source, PR or CI. Do not use root for production or modify any other local schema.
- **GO CODING ICH-S0-02 GRANTED:** Codex VS Code may implement scoped MySQL configuration, identity/event migrations, models, UUIDv7/FK/unique constraints and synthetic fixtures; normal **additive `php artisan migrate` on verified `iches_unisya`** is within this GO. No additional GO for those authorized actions.
- **First checkpoint:** Codex must authenticate and verify read-only `SELECT DATABASE() = iches_unisya`, assess only existing tables inside that schema, and ensure it is the ICHES-owned database. A ChatGPT-side attempted read-only login did not return conclusive results; do not claim DB_AUTH_PASS yet.
- **Never automatically perform:** `migrate:fresh`, `migrate:reset`, `db:wipe`, `DROP`, bulk `TRUNCATE`, real data deletion, database operations on other applications, production deployment.
- Retain merged GitHub CI in **DB-free mode**, and keep source changes strictly through Codex VS Code. Stop after *local feature commit*; separate approvals required for push, PR and merge.

**Precedence:** RD-03 MySQL/InnoDB, RD-03B single dev database, and RD-04 UUIDv7 remain. RD-03C `iches-unisya` name is superseded by **RD-03D `iches_unisya`**. Older documents remain historical. [Codex execution contract](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md), [prior work order](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md), [19F board](19F-CURRENT-SPRINT-BOARD.md).

**STATUS: `RD-03D_FINAL / ICH-S0-02_GO_GRANTED / CODEX_EXECUTION_PENDING / DB_AUTH_CHECK_REQUIRED`.**
