# 19F — Current Sprint Board & Handoff Ledger

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

## NOW — satu prioritas yang boleh aktif
**ICH-PRE-02 / ICH-PRE-03** — Tutup pilihan teknis minimum dan inspeksi kesiapan komputer/VS Code/Codex (tanpa menulis kode). **Belum GO untuk task/sprint mana pun.**

| Work item | Status | Bukti / blocker saat snapshot | Next action / penanggung jawab |
|---|---|---|---|
| **ICH-PRE-01** Audit blueprint dan repo lama | **REVIEWED_DOCS** | 18C-05A & 19A–D tersedia; tes runtime legacy belum diulang | Revalidate reuse per task, ChatGPT |
| **Workspace governance 19E/19F** | **DRAFT_REVIEW** | Dokumentasi operasi dan papan kerja diajukan di branch docs | Owner review; ChatGPT perbaiki bila perlu |
| **ICH-PRE-02** Keputusan teknis awal | **BLOCKED_DECISION** | Repo canonical, MySQL, UUIDv7, PHP target, auth belum diberikan persetujuan final eksplisit | Owner approves/revises recommended bundle, ChatGPT update register |
| **ICH-PRE-03** Environment VS Code/Codex + toolchain | **PARTIAL_VERIFIED** | Git/PHP/Composer/Node/npm, repo clone, chosen VS Code open, Codex extension installed; MySQL97 service Running. Sign-in and disposable test DB unverified | Verify in-app Codex and isolated test DB |
| **ICH-PRE-04** GO CODING task atau sprint pertama | **NO_GO** | Tidak ada otorisasi implementasi | Setelah PRE-02/03 green, owner memberi GO Task ID atau named Sprint ID sesuai Work Order |
| **ICH-S0-01** Laravel/Vue Foundation | **PLANNED / BLOCKED** | Work Order draf di 19C; target masih docs-only | Buka Task ID hanya setelah gate; Codex code-only |

**Tidak ada task berstatus IN_CODEX, REVIEW_PASS, ACCEPTED atau hasil PASS runtime.**

## NEXT — antrean, bukan izin mengerjakan
1. **ICH-S0-01** bootable foundation + actual test evidence; setelah mendapat GO sendiri.
2. **ICH-S0-02/03/04/05/06/07** identity/role/storage/frontend/test foundation, dipilih berdasar dependency dan per-task GO.
3. **ICH-S1-01..08** public website/CMS/countdown; aspek UI tidak perlu owner mengunci komponen satu per satu, tetapi publikasi dan data event harus benar.
4. **ICH-S2-01..06** submission Text+PDF; final valid submission langsung reviewer index tanpa screening.

**Not now:** S3–S9, full bank verification, QR offline, OJS handoff, bank credential, real participant migration, deployment. Keputusan produknya tetap ada di blueprint, bukan dibatalkan.

## Default decision bundle untuk owner (USULAN; TIDAK OTOMATIS APPROVED)
| Kode | Rekomendasi | Status |
|---|---|---|
| RD-01 | `akhmadafnan/iches-unisya` canonical, selective reuse old repo | **PROPOSED** |
| RD-02 | Laravel 13; PHP 8.4 target **jika supported oleh host** | **PROPOSED / HOST UNKNOWN** |
| RD-03 | MySQL InnoDB versi yang kompatibel | **PROPOSED** |
| RD-04 | UUIDv7 konsisten | **PROPOSED** |
| RD-05 | Laravel Policies + scoped roles | **PROPOSED** |
| RD-06 | Session login/email/password/verification/reset | **PROPOSED** |
| RD-07 | One-user Switch Workspace, audited read-only Support View | **FUNCTION APPROVED / TECH PROPOSED** |
| RD-08 | Laravel typed-block versioned CMS | **FUNCTION APPROVED / TECH PROPOSED** |

Sumber detail: [18C-05 decision gates](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md). **No forced approval:** keputusan owner harus tercatat di 09 dan/atau board sebelum dipakai Codex. Jam resmi acara belum ada; countdown tidak boleh mengarang 00:00.

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
