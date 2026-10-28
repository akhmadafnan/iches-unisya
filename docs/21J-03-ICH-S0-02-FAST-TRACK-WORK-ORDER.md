# 21J-03 — ICH-S0-02 Fast-track Identity/Event Database Foundation — Work Order

> **LATEST RD-03D OWNER OVERRIDE:** Use `iches_unisya` (underscore), owner-created, and **GO CODING ICH-S0-02 is authorized**. Old hyphen spelling and pre-GO status below are historical. [21J-04](21J-04-ICHES-UNDERSCORE-DB-AND-S0-02-GO.md), [21J-05](21J-05-ICH-S0-02-GO-CODEX-EXECUTION.md).

> **28 October 2026 · READY FOR DATABASE CONFIRMATION / NOT GO CODING.** Source of truth: [21J-02 RD-03C](21J-02-ICHES-DEV-DB-NAME-AND-LOCAL-ACCESS.md), [19B backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [20B execution governance](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md). Keep delivery fast and avoid repeated planning, but no automatic destructive SQL or code task without a named GO.

## One-step owner preparation

Create **only** `iches-unisya` (hyphen) in local phpMyAdmin; no manual tables or SQL migrations. A database name collision or existing non-ICHES content is a STOP gate. Credentials are kept in the user's local ignored `.env`, not in public source, documentation, or ChatGPT output. Owner-selected temporary root access is permissible **locally only**. Owner message **“database iches-unisya sudah dibuat; GO CODING ICH-S0-02”** can authorize the same bounded task without further planning.

## Bounded Codex VS Code execution (after GO)

1. In canonical repository `D:\PINJAM-AFNAN\Herd\iches-unisya`, read `AGENTS.md`, `WORKSPACE.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, this Work Order and engineering/code-verification Skills. Verify clean branch and canonical remote. Safely fetch and fast-forward local `main` from latest GitHub (currently post-S0-07 merged), **do not rebase/reset/amend approved old feature branches**. Create fresh `feat/ich-s0-02-db-foundation`.
2. Confirm the exact created schema is `iches-unisya` and exclusively for ICHES; only read this database, do not enumerate unrelated user databases. Verify the active MySQL connection targets it with `SELECT DATABASE()` and check engine/InnoDB, basic FK/transaction compatibility. If missing/ambiguous/inaccessible, STOP and report. Never log credential values.
3. Update application-owned `config/database.php` (currently intentionally DB-disabled) with a real Laravel 13 MySQL connection, using environment variables and compatible defaults; create/adjust untracked **local `.env`** for the owner-approved temporary local root access. Do not commit `.env` and do not change CI database-disabled configuration. `.env.example` may contain **non-secret placeholders only**, never owner root password.
4. Implement only **minimal identity + event master** MySQL/InnoDB schema, UUIDv7 PKs and consistent FKs, constraints/indexes, and selective fully synthetic fixtures. Align the approved future auth/roles: passwordless attendee OTP/Magic Link and scoped role/event/institution memberships, but **do not implement login, CMS, payments or reviewer workflow** in this task. Event official start time must not be invented. Design final minimal tables according to current approved data model, not 53 draft tables wholesale.
5. On the confirmed empty/appropriate ICHES dev schema, run normal **`php artisan migrate`** after owner GO. If unexpected existing tables/data or risky DDL detected, STOP rather than overwrite. Run focused positive/negative FK, unique, UUIDv7 and rollback checks without automatic destructive primary database resets. No `migrate:fresh` or `db:wipe` on shared primary development DB. Ensure existing **4 DB-free PHP smoke tests** and PR #5 PHP/Node22 CI remain stable.
6. Verify Laravel Herd HTTP smoke, PHP/Pint, Composer, TypeScript/Vite as needed; commit code on feature branch only and record sanitized append-only evidence in `harness/build-log.md`. **STOP before push/PR/merge/deployment**, each needs separate owner authorization. Do not publish credentials, dump databases or touch unrelated Herd projects.

## Exit criteria

- Verified one exclusively ICHES local schema `iches-unisya` and correct selected `DATABASE()`.
- Laravel application connects, intended migrations/UUIDv7/FKs/constraints pass; retained DB-free CI still works.
- No unrelated schema, production data or secret exposure; no destructive reset; reviewable feature diff and actual test evidence.
- **NO_GO until the owner confirms manual schema creation and explicitly authorizes `GO CODING ICH-S0-02`.** DB names/temporary access decisions are not standalone coding permission.
