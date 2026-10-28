# 20C — Codex VS Code Readiness: Verified Local Preflight

> **28 October 2026 (Asia/Jakarta) · READ-ONLY INSPECTION COMPLETED, NO CODE GO.** This document records what was **actually checked** on one connected Windows Desktop Commander device. It does not prove the device is the final development machine, that the target repository is cloned, or that production hosting is ready.

## 1. Evidence from connected Windows desktop (read-only)

| Item | Actual result | Gate |
|---|---|---|
| Desktop Commander device | One Windows desktop reachable via user-linked device; local execution read-only | PASS (reachability only) |
| Git | `git version 2.55.0.windows.2` | PASS (CLI detected) |
| PHP | `PHP 8.4.24 (CLI)` | PASS (version detected) |
| Composer | `Composer 2.10.1`, using PHP 8.4.24 | PASS (CLI detected) |
| Node.js | `v26.4.0` | PASS (CLI detected); project compatibility NOT TESTED |
| npm | `11.17.0` via `npm.cmd --version` | PASS via .cmd; `npm --version` failed through PowerShell invocation |
| PHP extensions | `pdo_mysql`, `mysqli`, `openssl`, `mbstring`, `fileinfo`, `intl` detected | PASS (module presence only) |
| MySQL CLI | `mysql` not found in PATH | NOT_VERIFIED, not proof server unavailable |
| VS Code command | Full path `D:\PINJAM-AFNAN\Microsoft VS Code\bin\code.cmd` works; VS Code **1.128.0 x64** | PASS via explicit path; not on generic PATH |
| Codex extension | `openai.chatgpt@26.51021.22027` and `openai.codex-audio@26.1021.22027` listed | DETECTED; sign-in/skills loading NOT_VERIFIED |
| Local `iches-unisya` clone | Owner chose `D:\PINJAM-AFNAN\iches-unisya`, checked explicitly: **folder absent** | NOT_CREATED, pending setup authorization |
| Local test database | No connection attempted | NOT_VERIFIED |
| Git remote, local branch/status | No project clone path established | NOT_VERIFIED |
| Runtime test/migration | No Composer install, npm build, Laravel tests, migration or database write performed | NOT_RUN |

**Inspection scope:** read-only CLI version/extension checks, a small set of standard VS Code paths and common repository-folder candidates. The audit did **not** search all drives or inspect credentials/private files. On Windows PowerShell prefer `npm.cmd` if `npm` resolves to a blocked `npm.ps1`; do **not** change execution policy just to run npm. Node 26 is detected, but final target should follow verified framework/dependency and hosting compatibility instead of assuming newest = best.

## 2. G0 readiness — currently PARTIAL

- [x] Git/PHP/Composer/Node/npm are present and versions recorded.
- [x] PHP MySQL driver + basic extensions available.
- [x] Identify VS Code 1.128.0 path and detect OpenAI extension IDs.
- [ ] Confirm Codex VS Code sign-in and that repository Skills load in an actual workspace.
- [x] Owner-selected target path `D:\PINJAM-AFNAN\iches-unisya` and verified destination does not exist.
- [ ] With separate setup authorization, clone there; then verify remote, branch and clean Git status.
- [ ] Determine MySQL server/test DB availability; ensure a disposable isolated DB (not production) can be used.
- [ ] Approve RD-01 canonical target; RD-02 PHP/Node version range; RD-03 DB engine/version; RD-04 UUIDv7; RD-05 roles/scoping; RD-06 auth baseline in Decision Register.
- [ ] Verify composer/npm install/build/test scripts **only after coding authorization**. Never mark tests PASS based on available CLI versions.
- [ ] Confirm shadcn-vue Lyra-compatible preset/tokens before changing UI dependencies (not needed to preflight G0).

## 3. Safe actions in next preflight

**Allowed (read-only):** `git --version`, `php -v`, `composer --version`, `node --version`, `npm.cmd --version`, `git status`, `git branch --show-current`, `git remote -v` on **identified** clone, `code --list-extensions` or VS Code UI Extensions inspection if available, check MySQL service/executable presence without connecting to production.

**Not authorized:** npm/composer installs, cloning/overwriting local folders, new project scaffolding, package upgrades, DB schema changes, local config modifications, production credentials, source code, merging PR or deployment. Explicit owner GO Coding is separate.


## 20C addendum — Local project root confirmed by owner (28 Oct 2026)

**Owner-selected absolute workspace path:** `D:\PINJAM-AFNAN\iches-unisya` on the connected Windows desktop.

Read-only check:
- Drive `D:\`: **EXISTS**; parent `D:\PINJAM-AFNAN`: **EXISTS**.
- Target project folder `D:\PINJAM-AFNAN\iches-unisya`: **DOES NOT YET EXIST**. No clone/create/install attempted.
- VS Code: installed at `D:\PINJAM-AFNAN\Microsoft VS Code\Code.exe`; verified CLI `D:\PINJAM-AFNAN\Microsoft VS Code\bin\code.cmd`; version **1.128.0 (x64)**.
- VS Code extension list, default profile: `openai.chatgpt@26.51021.22027` and `openai.codex-audio@26.1021.22027`. **Extensions listed is not proof that Codex is signed in or that Skills are discoverable**.
- `code` absent from generic PATH but full `code.cmd` path worked. `npm.cmd` worked. Avoid changing PATH/PowerShell execution policy during preflight.

**Next action (separately authorized):** initialize/clone canonical GitHub repo into **exactly this target path** after owner authorizes repository setup and the destination remains unoccupied. Never clone into existing `D:\PINJAM-AFNAN\ICHES` or `iches-2027-prototype` by mistake. Checkout documentation branch only as instructed; PR #1 is still Draft and not merged. After cloning inspect `git remote -v`, branch, worktree, `AGENTS.md` and `PLANS.md` before any GO Coding.

## 4. Next concrete step

1. Owner identifies which VS Code installation/folder is intended for ICHES (if this connected desktop is not the development machine, use the correct device).
2. ChatGPT verifies the clone and Codex extension **read-only** and records facts here.
3. Owner approves minimum RD decisions; ChatGPT moves [20C-01 Sprint-01 Work Order](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md) from `BLOCKED_DECISION` to `READY` only when inputs actually exist.
4. **No GO coding yet**. A separate `GO SPRINT ICH-SPRINT-01` is needed before Codex can write any source code or execution log.

Source: [20B authorization contract](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md), [19F Current Board](19F-CURRENT-SPRINT-BOARD.md).
