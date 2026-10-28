# 20E-02 — Herd Free & phpMyAdmin: ICHES Local Workflow

> **28 October 2026 · DEFAULT LOCAL WORKFLOW · NO GO CODING.** Replaces the earlier temporary MySQL CLI login-path proposal as the normal administration route. No test database/user has been created or authenticated yet.

## Verified local setup

| Item | Verified result |
|---|---|
| Project directory | `D:\PINJAM-AFNAN\iches-unisya` — canonical Git checkout |
| Herd edition & CLI | Herd Free, CLI v1.29.0 |
| Herd link executed | `herd link iches-unisya` succeeded, with no app source changes |
| Herd site mapping | `http://iches-unisya.test/` → `D:\PINJAM-AFNAN\iches-unisya`, PHP 8.4 |
| ICHES HTTP response | HTTP **404** until Laravel app is implemented; `artisan` and `public/` are not present |
| phpMyAdmin directory | `D:\PINJAM-AFNAN\Herd\phpMyAdmin`, `index.php` present |
| phpMyAdmin URL | `https://phpmyadmin.test/index.php` returns HTTP **200**, but an authenticated phpMyAdmin session has **not** been verified |
| Database server | **Independent MySQL97 service**, MySQL 9.7.1, 127.0.0.1:3306; NOT a Herd Pro managed DB |

Herd Free supports linking a directory outside the parked root via `herd link`; no Pro license or duplicate project folder is needed. Reference: [Official Herd Windows Applications](https://herd.laravel.com/docs/windows/getting-started/applications). Existing site `sip-hibah-v2` was not changed.

## Preferred MySQL test-only setup (phpMyAdmin GUI)

The owner previously authorized creating **only** `iches_unisya_test` and **only** local DB account `iches_test`. The authenticated browser UI is not accessible to this ChatGPT tool session; owner performs these GUI steps locally, without posting credentials:

1. Open `https://phpmyadmin.test/index.php`; sign in using your already-authorized local administrator account.
2. **Databases** → if `iches_unisya_test` already exists, **STOP and report collision**; otherwise create only that name using `utf8mb4` with a MySQL-9.7-compatible collation.
3. **User accounts** → if account `iches_test` for the intended local-only host already exists, **STOP**, never overwrite/reset other accounts; otherwise create it with a strong locally stored password.
4. Give `iches_test` **database-specific** rights on **only** `iches_unisya_test`, sufficient for test data + migration/rollback (CRUD, table DDL/ALTER, INDEX, REFERENCES), **never global `*.*` privileges**.
5. Verify test account can access `iches_unisya_test` and no unrelated schemas. Do not share passwords, complete grants, admin details, cookie/session content or screenshots with secrets. Send only `test DB created: YES/NO; test user created: YES/NO; privileges limited: YES/NO`.

This manual GUI setup is distinct from **GO CODING**. After confirmation, ChatGPT may update board G0 status using evidence; Laravel migrations, build/tests and server-side coding remain **NOT_RUN until the separately approved named Sprint GO**.

## Codex foundation implementation contract (later)

- Source stays **only** at `D:\PINJAM-AFNAN\iches-unisya`; don't scaffold a duplicate project under `D:\PINJAM-AFNAN\Herd`.
- When Sprint 01 is authorized, Codex adds Laravel and correct `public/index.php`, and Herd serves via `http://iches-unisya.test/` without `php artisan serve`.
- DB account details belong to untracked local test configuration, **not** GitHub, documentation, command-line arguments or Codex prompts.
- **Current status:** Herd link PASS; phpMyAdmin endpoint PASS; dedicated authenticated test DB/user BLOCKED; ICHES HTTP Laravel app NOT_READY; NO_GO Coding.

Related: [20E Test DB Readiness](20E-MYSQL-ISOLATED-TEST-DB-READINESS.md), [19F Board](19F-CURRENT-SPRINT-BOARD.md).
