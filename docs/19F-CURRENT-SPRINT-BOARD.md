# 19F — Current Sprint Board & Handoff Ledger

> **CURRENT LOCAL PATH (owner-directed relocation, 28 Oct 2026):** `D:\PINJAM-AFNAN\Herd\iches-unisya`. The repository was moved intact from `D:\PINJAM-AFNAN\iches-unisya`; the old directory no longer exists. Herd Free `herd links` confirms `http://iches-unisya.test/` points to the **new** path using PHP 8.4. Git remote `akhmadafnan/iches-unisya`, branch `docs/19e-agent-workspace`, HEAD `1403178c38c131bd81ab8ee48a204b5bdec2d494`, clean after move. VS Code was opened again targeting the new directory. Older references to the old path in snapshot sections are **historical** and must not be used as the current workspace. No database, source code, migrations or other Herd links were altered. **NO GO CODING.**

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

> **Snapshot 28 Oktober 2026 · DRAFT · BELUM GO CODING.** Ini **satu sumber status operasional jangka pendek**, bukan pengganti [19B backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [19C Work Orders](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md), atau [09 Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md). Update oleh **ChatGPT** setelah verifikasi bukti GitHub/operator.

## 20A Master Plan and Harness (DRAFT)

[PLANS.md](../PLANS.md) now contains the single M0–M9 roadmap; [20A contract](20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) proposes the Skill registry and evidence workflow. The live status remains ONLY this 19F board. 20B sprint workflow and append-only evidence exception are approved as policy, but **no named GO Coding** has been issued. Per-task GO remains fallback. No application tests have run.

## Tahap 20B — Governance approved, NO GO CODING

Owner menyetujui **GO sprint terbatas**, pengecualian Codex **append-only build log**, serta dua Skills baru: Backoffice shadcn-vue dan QA. Berkas disiapkan pada Draft PR #1; **belum ada GO untuk Task ID/sprint nyata**. Pending: review/merge PR, keputusan teknis, inspeksi VS Code/toolchain, Sprint Work Order READY, GO terpisah.

## 20C — Read-only local preflight (28 Oct 2026)

- Connected Windows device verified: **Git 2.55.0, PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm.cmd 11.17.0**, PHP pdo_mysql available. No install or application test executed.
- **PARTIAL**: VS Code utama v1.143.1 terdeteksi lewat metadata; Codex extension/sign-in **pada instalasi utama** belum diverifikasi, clone ICHES belum ada, MySQL server/test DB belum diuji. `npm.cmd` berjalan.
- [20C device audit](20C-LOCAL-PREFLIGHT-AND-GATES.md) and [Sprint-01 DRAFT](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md): `ICH-S0-01` → `ICH-S0-02` → `ICH-S0-07`; status **BLOCKED_DECISION / NO_GO**.
- Next action: verify Codex sign-in on owner's v1.143.1 installation, then obtain separate setup permission for project folder/clone and close PRE-02 decisions. No coding.

## Owner local workspace decision — 28 Oct 2026

**Lokasi proyek disepakati:** `D:\PINJAM-AFNAN\iches-unisya` (**pakai tanda hubung**); folder belum ada. **VS Code utama yang dipilih owner:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (**pakai spasi**), v**1.143.1** terverifikasi dari metadata. Ada instalasi lain v1.128.0 pada direktori bertanda hubung, tetapi **bukan default yang dipilih**. Ekstensi/login Codex pada v1.143.1 belum diverifikasi; `new_code.cmd` wrapper gagal karena merujuk `Code.exe` yang tidak ada. Tidak ada clone/create/merge/coding. [Detail 20C](20C-LOCAL-PREFLIGHT-AND-GATES.md). **Next:** izin setup clone terpisah lalu inspeksi remote dan kesiapan environment.

## 20C — Codex VS Code UI confirmed by owner screenshot (28 Oct 2026)

Screenshot shows ICHES folder open, `docs/19e-agent-workspace` active on VS Code status bar, Codex sidebar and prior chats visible, plus `AGENTS.md` / `PLANS.md` / `.agents`. This confirms **visible Codex UI session**, but **does NOT prove all 4 Skills can be read by the agent**. Next action: run the no-code [20C-02 Read-only Codex Handshake](20C-02-CODEX-READ-ONLY-HANDSHAKE.md) in Codex chat and review its textual output. No code/DB/install/merge/GO. PRE-02 note: NEW-003 approved participant Magic Link/OTP vs draft RD-06 password login must be reconciled before authorizing code.

## PRE-03 Codex handshake — VERIFIED (28 October 2026)

**Evidence:** owner copied the Codex VS Code agent's complete read-only response, not just a screenshot.

- **Workspace:** `D:\PINJAM-AFNAN\iches-unisya`; canonical remote `https://github.com/akhmadafnan/iches-unisya.git`.
- **Branch:** `docs/19e-agent-workspace`; handshake HEAD `c9df6d0b30d6f3fdb88d94e086555f1a5af365f8`; worktree clean, local tracking ahead/behind `0/0` (agent did not fetch).
- **Read in full:** `AGENTS.md`, `PLANS.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, `WORKSPACE.md`, `docs/19E-AGENT-WORKSPACE-RUNBOOK.md`.
- **All 4 Skills directly read by Codex:** `iches-codex-engineering`, `iches-public-frontend`, `iches-backoffice-ui`, `iches-code-verification`. Direct readability **PASS**; **automatic Skill triggering not tested**.
- **Agent behavior:** no source/docs/file changes, no install, test, commit, fetch, migration or named GO. Thus **PRE-03 Codex access/readiness PASSED**; full **G0 runtime readiness still PARTIAL** due to DB test account and owner decision gates.
- **Later SHA may differ** because ChatGPT maintains the documentation branch; snapshot SHA is intentional and historical.

**Separate non-writing database connectivity evidence:** Windows service `MySQL97` reports **Running**; executable metadata product version **9.7.1.0**; TCP connect to `127.0.0.1:3306` **succeeded**. This does **not** verify MySQL credentials, server SQL response, storage engine, migrations, or an isolated test DB. No credentials read or stored.

**Next active gate:** [20D PRE-02 Technical Decision Packet](20D-PRE-02-TECHNICAL-DECISION-PACKET.md) owner approval, then isolated test DB and final Sprint 01 readiness. **NO GO CODING.**


## 20D — RD-01–RD-06 FINAL (28 October 2026)

**Owner explicitly approved:** “Setuju paket 20D”. Technical decision register [09](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) updated for **RD-01..06**; participant **OTP/Magic Link without password** retained. [20D packet](20D-PRE-02-TECHNICAL-DECISION-PACKET.md) now marked approved. **ICH-PRE-02 decision bundle COMPLETE**; no need to ask those six choices again.

**Database preflight, nonwriting:** Windows `MySQL97` Running; TCP `127.0.0.1:3306` handshake protocol `10`, server version **9.7.1**, **no login/SQL/schema action**. Still **BLOCKED_G0**: authenticate isolated *test* database and verify InnoDB/FKs/migration permissions before any database task. Governance PR #1 still Draft and unmerged; Sprint Work Order still **NO_GO**. **Next one action:** verify safe isolated test DB environment, then review/merge documentation PR through separate approval.

## 20E — Database environment preflight (read-only)

**Actual Windows evidence 28 October 2026:**
- MySQL97 service Running; native protocol handshake version **9.7.1** and TCP localhost:3306 reachable.
- Found **native MySQL 9.7.1 command-line client** at `C:\Program Files\MySQL\MySQL Server 9.7\bin\mysql.exe`. It isn't on PATH; absolute executable works. No reinstall necessary.
- **No DB login/password, SQL, schema or data changes** were attempted. **G0-DB-01 PASS**; G0-DB-02 (test-only login) and G0-DB-03 (InnoDB/FK/scoped grants) **BLOCKED**. G0-DB-04 migration/rollback is NOT_RUN and needs named coding GO.
- [20E Test DB Readiness Runbook](20E-MYSQL-ISOLATED-TEST-DB-READINESS.md). No test schema/user assumed to exist and no provisioning authorization received. **No GO coding.**

## 20E administrator-auth checkpoint — blocked safely (28 October 2026)

Owner explicitly approved provisioning **only** a new isolated `iches_unisya_test` database and limited `iches_test` account. Attempts to confirm existing administrative access **failed safely**: a **single no-password** root read-only connection returned MySQL **1045 Access denied**; a **single default-profile** read-only connection also returned **1045**. There was **no password guessing, no inspection of existing saved passwords, no SQL DDL, no user/database created**, and no production data touched. MySQL 9.7.1 server and client remain operational.

**Next action:** the owner must type administrator password locally once into a **temporary MySQL login path**, without posting it into ChatGPT or Codex. Full instructions: [20E-01 Privileged Login Setup](20E-01-TEMPORARY-MYSQL-ADMIN-LOGIN.md). After owner reports setup complete, ChatGPT can test admin account and provision only authorized test DB/user. Remove temporary administrator login path once done. **No GO Coding or PR merge.**

## 20E-02 — Owner Herd Free/phpMyAdmin local environment CONFIRMED

- Owner clarified database management via phpMyAdmin in `D:\PINJAM-AFNAN\Herd\phpMyAdmin`; `https://phpmyadmin.test/index.php` HTTP **200** on 127.0.0.1; authenticated session not verified.
- **Herd Free 1.29.0** used: `herd link iches-unisya` SUCCESS; `herd links` lists `http://iches-unisya.test` mapped to the exact GitHub checkout folder, **PHP 8.4**. Existing other link unchanged.
- `http://iches-unisya.test/` HTTP **404** because repo is **docs-only** (`public/` and `artisan` absent); site registration is verified, not app readiness.
- [20E-02 GUI workflow](20E-02-HERD-FREE-PHPMYADMIN-WORKFLOW.md) supersedes temporary CLI login prompt as default. Owner still needs to create **only** `iches_unisya_test` and `iches_test` via normal authenticated phpMyAdmin GUI (unless collision); **not created yet**.
- G0-DB-02/03 **BLOCKED**; PR #1 still Draft, **NO_GO Coding**.


## 20F — Owner-directed ICHES folder relocation COMPLETED

**CURRENT WORKSPACE:** `D:\PINJAM-AFNAN\Herd\iches-unisya` (moved intact from `D:\PINJAM-AFNAN\iches-unisya`). Verified old folder absent, new folder present, `git status` clean, expected Git HEAD, and `herd links` resolving `http://iches-unisya.test/` to new folder using PHP 8.4. Existing `sip-hibah-v2` Herd link remained intact. Chosen VS Code `new_Code.exe` launched again with the **new** folder. **Database not touched**; user's existing Herd Free + phpMyAdmin workflow is accepted as the operating pattern. App remains documentation-only so HTTP 404 is expected until separately authorized Laravel implementation. **No GO Coding**. Historical path references above are not active instructions.

## NOW — satu prioritas yang boleh aktif
**NOW: ICH-PRE-03 G0 isolated test DB (20E)**. PRE-02 RD-01..06 FINAL, Codex Skills READABLE PASS, MySQL server/CLI PASS, **test DB authentication/permissions BLOCKED**. Next: owner-authorized **test-only** database/user setup; then authenticated read-only checks. Draft PR review/merge, Sprint readiness and named GO are still separate. **NO_GO CODING.**

| Work item | Status | Bukti / blocker saat snapshot | Next action / penanggung jawab |
|---|---|---|---|
| **ICH-PRE-01** Audit blueprint dan repo lama | **REVIEWED_DOCS** | 18C-05A & 19A–D tersedia; tes runtime legacy belum diulang | Revalidate reuse per task, ChatGPT |
| **Workspace governance 19E/19F** | **DRAFT_REVIEW** | Dokumentasi operasi dan papan kerja diajukan di branch docs | Owner review; ChatGPT perbaiki bila perlu |
| **ICH-PRE-02** Keputusan teknis RD-01..06 | **APPROVED_FINAL** | Owner: “Setuju paket 20D”; tercatat di Register 09, termasuk OTP/Magic Link | No remaining RD-01..06 decision blocker; verify test DB G0 |
| **ICH-PRE-03** Codex workspace/Skills | **READ_ONLY_HANDSHAKE_PASS** | Codex confirmed canonical remote, clean docs branch, full reading of AGENTS/PLANS/board and 4 Skills; MySQL97 Running / 3306 TCP reachable | PRE-03 Codex check complete; test DB validation remains G0 gate |
| **ICH-PRE-04** GO CODING task atau sprint pertama | **NO_GO** | Tidak ada otorisasi implementasi | Setelah PRE-02/03 green, owner memberi GO Task ID atau named Sprint ID sesuai Work Order |
| **ICH-S0-01** Laravel/Vue Foundation | **PLANNED / BLOCKED** | Work Order draf di 19C; target masih docs-only | Buka Task ID hanya setelah gate; Codex code-only |

**Tidak ada task berstatus IN_CODEX, REVIEW_PASS, ACCEPTED atau hasil PASS runtime.**

## NEXT — antrean, bukan izin mengerjakan
1. **ICH-S0-01** bootable foundation + actual test evidence; setelah mendapat GO sendiri.
2. **ICH-S0-02/03/04/05/06/07** identity/role/storage/frontend/test foundation, dipilih berdasar dependency dan per-task GO.
3. **ICH-S1-01..08** public website/CMS/countdown; aspek UI tidak perlu owner mengunci komponen satu per satu, tetapi publikasi dan data event harus benar.
4. **ICH-S2-01..06** submission Text+PDF; final valid submission langsung reviewer index tanpa screening.

**Not now:** S3–S9, full bank verification, QR offline, OJS handoff, bank credential, real participant migration, deployment. Keputusan produknya tetap ada di blueprint, bukan dibatalkan.

## Decision bundle RD-01–RD-06 — APPROVED FINAL; RD-07–RD-08 technical detail pending
| Kode | Rekomendasi | Status |
|---|---|---|
| RD-01 | `akhmadafnan/iches-unisya` canonical, selective reuse old repo | **APPROVED FINAL 20D** |
| RD-02 | Laravel 13, PHP 8.4 target; host verified later | **APPROVED FINAL 20D / HOST UNKNOWN** |
| RD-03 | MySQL/InnoDB; isolated test DB before migrations | **APPROVED FINAL 20D / TEST_DB G0 OPEN** |
| RD-04 | UUIDv7 IDs and consistent FKs | **APPROVED FINAL 20D** |
| RD-05 | Laravel Policies/Gates + role/event/institution/resource scopes | **APPROVED FINAL 20D** |
| RD-06 | Session-backed participant OTP/Magic Link **passwordless**; staff mode later | **APPROVED FINAL 20D** |
| RD-07 | One-user Switch Workspace, audited read-only Support View | **FUNCTION APPROVED / TECH PROPOSED** |
| RD-08 | Laravel typed-block versioned CMS | **FUNCTION APPROVED / TECH PROPOSED** |

Sumber detail: [18C-05 decision gates](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md). **RD-01–RD-06 recorded APPROVED in 09 and board; RD-07/RD-08 technical details remain separate.** Jam resmi acara belum ada; countdown tidak boleh mengarang 00:00.

## Template update board (ChatGPT only)
- **Date/time, branch/HEAD, task**:
- **Status before → after**:
- **Authorization evidence**:
- **Scope, PR, tests actually run, relevant negative cases**:
- **Unresolved blocker / responsible person**:
- **One next action**:

## Fast handoff ke chat baru / agent
**ChatGPT:** baca `WORKSPACE.md`, board ini, 19E, kemudian 09/19B/19C sesuai Task ID. Review diff dulu sebelum mengubah status.  
**Codex:** baca `AGENTS.md` + skill engineering + **work order ber-GO**; jika belum GO, inspection/read-only saja.  
**Owner:** cukup kirim ID tugas, link branch/PR, dan output tes. Tidak perlu menyalin seluruh blueprint.

## Catatan manajemen target dua hari
Target 48 jam adalah **timebox sejak GO coding dan environment siap**, bukan deadline pasti untuk seluruh S0–S9. Prioritas rilis selalu keamanan submission/finance dan CMS yang benar, bukan angka persentase semu.
