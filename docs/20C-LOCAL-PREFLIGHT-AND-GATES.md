# 20C — Codex VS Code Readiness: Verified Local Preflight

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

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
| MySQL service | Windows service `MySQL97` **Running** (and `HerdHelper` Running); neither `mysql` nor `mariadb` CLI in PATH. No DB connection or disposable test DB checked | SERVICE VERIFIED; TEST_DB NOT_VERIFIED |
| **Owner's VS Code installation** | `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (v1.143.1) opened workspace, visible window `Welcome - iches-unisya` | GUI VERIFIED; Codex sign-in NOT TESTED |
| VS Code extensions / Codex | On **other** VS Code installation `D:\PINJAM-AFNAN\Microsoft VS Code\Code.exe` (1.128.0), `openai.chatgpt` and `openai.codex-audio` were listed. Owner's **space-path** installation has `bin\new_code.cmd`, but that wrapper references missing `Code.exe`; its extension list was **not verified** | OWNER INSTALL EXTENSIONS/SIGN-IN **NOT_VERIFIED** |
| Local `iches-unisya` clone | `D:\PINJAM-AFNAN\iches-unisya` cloned from GitHub; docs branch tracking origin; clean, four skills | VERIFIED / READY FOR DOC REVIEW |
| Local test database | No connection attempted | NOT_VERIFIED |
| Git remote, local branch/status | Verified origin `https://github.com/akhmadafnan/iches-unisya.git`, branch `docs/19e-agent-workspace`, clean | PASS |
| Runtime test/migration | No Composer install, npm build, Laravel tests, migration or database write performed | NOT_RUN |

**Inspection scope:** read-only CLI version/extension checks, a small set of standard VS Code paths and common repository-folder candidates. The audit did **not** search all drives or inspect credentials/private files. On Windows PowerShell prefer `npm.cmd` if `npm` resolves to a blocked `npm.ps1`; do **not** change execution policy just to run npm. Node 26 is detected, but final target should follow verified framework/dependency and hosting compatibility instead of assuming newest = best.

## 2. G0 readiness — currently PARTIAL

- [x] Git/PHP/Composer/Node/npm are present and versions recorded.
- [x] PHP MySQL driver + basic extensions available.
- [x] Identify **owner-designated** VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (1.143.1). Separate 1.128.0 install found under hyphen path; do NOT select that by default.
- [x] Open ICHES project using owner-selected VS Code and detect Codex extension in Windows user profile.
- [ ] Verify Codex signed-in/activated in UI and Skills recognized (NOT verified by installation alone).
- [x] Authorized clone into owner-selected `D:\PINJAM-AFNAN\iches-unisya`; checked remote, branch, clean worktree, AGENTS, PLANS, four Skills.
- [x] Windows service `MySQL97` detected **Running** (read-only); no client CLI in PATH.
- [ ] Confirm MySQL server connection/engine compatibility and a disposable isolated test DB (not production) before schema work.
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
- **CORRECTED OWNER DESIGNATION:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (with a space in `PINJAM AFNAN`), **version 1.143.1** from Windows executable metadata. Do not confuse with another `D:\PINJAM-AFNAN\Microsoft VS Code\Code.exe` (hyphen, v1.128.0).
- OpenAI extension listing was obtained only from the **other v1.128.0** VS Code installation. For the owner's **v1.143.1** installation, `bin\new_code.cmd` exists but points to missing `Code.exe`, so its extensions, Codex sign-in and Skills discovery remain **UNVERIFIED**.
- `code` absent from generic PATH. Owner install's `new_code.cmd --version` failed because its wrapper targets `Code.exe` rather than `new_Code.exe`; this **does not prove GUI broken**, only CLI wrapper requires verification. `npm.cmd` worked. Do not rename executables or change PATH/execution policy without separate permission.

**Next action (separately authorized):** initialize/clone canonical GitHub repo into **exactly this target path** after owner authorizes repository setup and the destination remains unoccupied. Never clone into existing `D:\PINJAM-AFNAN\ICHES` or `iches-2027-prototype` by mistake. Checkout documentation branch only as instructed; PR #1 is still Draft and not merged. After cloning inspect `git remote -v`, branch, worktree, `AGENTS.md` and `PLANS.md` before any GO Coding.

## 4. Next concrete step

1. Owner's preferred VS Code folder is confirmed. Verify the **v1.143.1 GUI** and Codex extension state safely without modifying the installation.
2. ChatGPT verifies the clone and Codex extension **read-only** and records facts here.
3. Owner approves minimum RD decisions; ChatGPT moves [20C-01 Sprint-01 Work Order](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md) from `BLOCKED_DECISION` to `READY` only when inputs actually exist.
4. **No GO coding yet**. A separate `GO SPRINT ICH-SPRINT-01` is needed before Codex can write any source code or execution log.

Source: [20B authorization contract](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md), [19F Current Board](19F-CURRENT-SPRINT-BOARD.md).
