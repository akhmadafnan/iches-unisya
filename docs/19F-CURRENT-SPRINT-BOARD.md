# 19F — Current Sprint Board & Handoff Ledger

> **Snapshot 28 Oktober 2026 · DRAFT · BELUM GO CODING.** Ini **satu sumber status operasional jangka pendek**, bukan pengganti [19B backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [19C Work Orders](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md), atau [09 Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md). Update oleh **ChatGPT** setelah verifikasi bukti GitHub/operator.

## 20A Master Plan and Harness (DRAFT)

[PLANS.md](../PLANS.md) now contains the single M0–M9 roadmap; [20A contract](20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) proposes the Skill registry and evidence workflow. The live status remains ONLY this 19F board. 20B sprint workflow and append-only evidence exception are approved as policy, but **no named GO Coding** has been issued. Per-task GO remains fallback. No application tests have run.

## Tahap 20B — Governance approved, NO GO CODING

Owner menyetujui **GO sprint terbatas**, pengecualian Codex **append-only build log**, serta dua Skills baru: Backoffice shadcn-vue dan QA. Berkas disiapkan pada Draft PR #1; **belum ada GO untuk Task ID/sprint nyata**. Pending: review/merge PR, keputusan teknis, inspeksi VS Code/toolchain, Sprint Work Order READY, GO terpisah.

## 20C — Read-only local preflight (28 Oct 2026)

- Connected Windows device verified: **Git 2.55.0, PHP 8.4.24, Composer 2.10.1, Node 26.4.0, npm.cmd 11.17.0**, PHP pdo_mysql available. No install or application test executed.
- **NOT_VERIFIED**: VS Code/Codex extension, local ICHES clone, MySQL executable/server and isolated test DB. `npm` PowerShell alias failed but `npm.cmd` worked.
- [20C device audit](20C-LOCAL-PREFLIGHT-AND-GATES.md) and [Sprint-01 DRAFT](20C-01-DRAFT-SPRINT-01-WORK-ORDER.md): `ICH-S0-01` → `ICH-S0-02` → `ICH-S0-07`; status **BLOCKED_DECISION / NO_GO**.
- Next one action: locate intended VS Code/project folder and finish PRE-02 technical approvals. No coding.

## NOW — satu prioritas yang boleh aktif
**ICH-PRE-02 / ICH-PRE-03** — Tutup pilihan teknis minimum dan inspeksi kesiapan komputer/VS Code/Codex (tanpa menulis kode). **Belum GO untuk task/sprint mana pun.**

| Work item | Status | Bukti / blocker saat snapshot | Next action / penanggung jawab |
|---|---|---|---|
| **ICH-PRE-01** Audit blueprint dan repo lama | **REVIEWED_DOCS** | 18C-05A & 19A–D tersedia; tes runtime legacy belum diulang | Revalidate reuse per task, ChatGPT |
| **Workspace governance 19E/19F** | **DRAFT_REVIEW** | Dokumentasi operasi dan papan kerja diajukan di branch docs | Owner review; ChatGPT perbaiki bila perlu |
| **ICH-PRE-02** Keputusan teknis awal | **BLOCKED_DECISION** | Repo canonical, MySQL, UUIDv7, PHP target, auth belum diberikan persetujuan final eksplisit | Owner approves/revises recommended bundle, ChatGPT update register |
| **ICH-PRE-03** Environment VS Code/Codex + toolchain | **NOT_VERIFIED** | Tidak ada bukti lokal PHP, Composer, Node, MySQL, Git, extension Codex | Inspeksi read-only melalui terminal/VS Code, owner |
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
