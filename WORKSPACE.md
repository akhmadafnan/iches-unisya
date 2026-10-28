# ICHES 2027 — AI Agent Workspace

> **Mulai di sini.** Repository ini masih **blueprint**, belum aplikasi Laravel yang berjalan. **Belum ada GO CODING.** Setelah GO Task ID atau bounded Sprint GO eksplisit, Codex VS Code mengubah kode serta mencatat evidence terbatas; ChatGPT mengelola keputusan, spesifikasi, dokumentasi, review, dan GitHub.

## One Master Plan (DRAFT — no GO)

- [PLANS.md](PLANS.md) — one M0–M9 execution roadmap, NOT a second status board.
- [20A](docs/20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) and [20B Governance](docs/20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md) — bounded sprint model, 4 Skills, controlled evidence; NO named GO.
- [20C Local Readiness](docs/20C-LOCAL-PREFLIGHT-AND-GATES.md) — verified Windows versions and open toolchain gaps; [Sprint-01 draft](docs/20C-01-DRAFT-SPRINT-01-WORK-ORDER.md) — three bounded foundation tasks, **not authorized yet**.
- [harness/README.md](harness/README.md) — Codex append-only evidence **only after named GO CODING**.

## Empat berkas kerja harian (bukan 50+ blueprint)
1. **[WORKSPACE.md](WORKSPACE.md)** — pintu masuk dan urutan baca (berkas ini).
2. **[docs/19F-CURRENT-SPRINT-BOARD.md](docs/19F-CURRENT-SPRINT-BOARD.md)** — **satu** status tugas aktif, blocker, dan next action; jangan menciptakan status tandingan di chat.
3. **[docs/19E-AGENT-WORKSPACE-RUNBOOK.md](docs/19E-AGENT-WORKSPACE-RUNBOOK.md)** — kontrak operasional, stop conditions, handoff, dan timebox.
4. **[AGENTS.md](AGENTS.md)** — instruksi lintas tugas untuk Codex VS Code, termasuk larangan edit dokumentasi.

## Sumber otoritatif menurut kegunaan
| Kebutuhan | Buka |
|---|---|
| Keputusan produk yang disetujui, masih draft, atau open | [Register keputusan](docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md) |
| Siapa mengerjakan apa / aturan Git | [19A protokol](docs/19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) |
| Seluruh daftar task dan dependensi | [19B backlog](docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) |
| Format work order satu task untuk Codex | [19C template dan contoh](docs/19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) |
| Peta blueprint → perubahan kode → bukti test | [19D traceability](docs/19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md) |
| Aturan implementation agent | [.agents/skills/iches-codex-engineering/SKILL.md](.agents/skills/iches-codex-engineering/SKILL.md) |
| Aturan frontend / CMS (hanya task yang relevan) | [.agents/skills/iches-public-frontend/SKILL.md](.agents/skills/iches-public-frontend/SKILL.md) |

## Prosedur singkat
**Sebelum GO:** owner dan ChatGPT menutup decision gate, menguji kesiapan lingkungan secara read-only, dan mengisi satu work order. **Jangan menjalankan coding hanya dari roadmap**.

**Setelah named GO Task/Sprint:** owner membuka repo target di VS Code → Codex membaca `AGENTS.md`, board, work order dan 2–4 blueprint relevan per task → kerja di `feat/...` (bukan `main`) → tes dan append-only log → lanjut hanya ke task sprint yang tercantum dan lulus gate → ChatGPT review → owner putuskan merge. **Berhenti** bila muncul keputusan yang belum disetujui, data/secrets nyata, atau scope melebar.

**Prioritas paling dekat:** foundation minimal → website publik + CMS + countdown → Abstract Text+PDF. Modul lain tetap ada di backlog, tetapi **tidak boleh ditarik ke task aktif hanya agar terlihat 'selesai'**.

**Tentang target dua hari:** [19E](docs/19E-AGENT-WORKSPACE-RUNBOOK.md) memberi timebox 48 jam **setelah keputusan dan lingkungan siap**; keberhasilan berarti hasil yang dapat didemokan/dites, bukan klaim bahwa S0–S9 selesai.

## Lokasi kerja lokal disepakati

- **Windows:** `D:\PINJAM-AFNAN\iches-unisya` — folder belum ada pada pemeriksaan 28 Oktober 2026; **jangan clone/buat otomatis tanpa izin setup**.
- **VS Code utama pilihan owner:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (**spasi**, versi 1.143.1 dari metadata). Instalasi v1.128.0 di direktori bertanda hubung berbeda dan bukan default. Login/Skills Codex pada VS Code utama belum diverifikasi.

## Status verifikasi awal (28 Oktober 2026)
- Target: `akhmadafnan/iches-unisya`, `main`; source aplikasi target **belum ada** saat audit.
- Repo lama `akhmadafnan/international-conference-platform` adalah referensi **selective reuse**, bukan kode yang boleh langsung disalin.
- Scope menulis code: **Codex VS Code** setelah GO; perubahan dokumen: **ChatGPT**.
- Branch docs ini adalah usulan tata kelola; **tidak memberikan GO CODING**.

> Saat chat baru: baca `WORKSPACE.md` + 19F; saat Codex: berikan **work order Task/Sprint bernama yang sudah mendapat GO aktual**, bukan semua blueprint.
