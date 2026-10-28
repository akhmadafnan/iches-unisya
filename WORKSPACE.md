# ICHES 2027 — AI Agent Workspace

> **Mulai di sini.** Repository ini adalah satu ruang kerja ICHES UNISYA; saat ini masih **blueprint**, belum aplikasi Laravel yang berjalan. **Belum ada GO CODING.** Codex VS Code hanya untuk perubahan **CODE setelah GO eksplisit per tugas**; ChatGPT menangani keputusan, spesifikasi, docs, review, dan tata kelola GitHub.

## One Master Plan (DRAFT — no GO)

- [PLANS.md](PLANS.md) — one M0–M9 execution roadmap, NOT a second status board.
- [20A Agent Execution Contract](docs/20A-MASTER-PLAN-SKILLS-AND-HARNESS.md) — skills registry, bounded authorization proposal, harness evidence ownership.
- [harness/README.md](harness/README.md) — evidence journal format. **Codex Markdown write permission is not active**.

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

**Setelah GO satu task:** owner membuka repo target di VS Code → Codex membaca `AGENTS.md`, board, work order dan paling banyak 2–4 blueprint relevan → kerja di `feat/...` (bukan `main`) → jalankan test yang tersedia → laporkan diff/test/blocker → ChatGPT review → owner putuskan merge. **Berhenti** bila muncul keputusan yang belum disetujui, data/secrets nyata, atau scope melebar.

**Prioritas paling dekat:** foundation minimal → website publik + CMS + countdown → Abstract Text+PDF. Modul lain tetap ada di backlog, tetapi **tidak boleh ditarik ke task aktif hanya agar terlihat 'selesai'**.

**Tentang target dua hari:** [19E](docs/19E-AGENT-WORKSPACE-RUNBOOK.md) memberi timebox 48 jam **setelah keputusan dan lingkungan siap**; keberhasilan berarti hasil yang dapat didemokan/dites, bukan klaim bahwa S0–S9 selesai.

## Status verifikasi awal (28 Oktober 2026)
- Target: `akhmadafnan/iches-unisya`, `main`; source aplikasi target **belum ada** saat audit.
- Repo lama `akhmadafnan/international-conference-platform` adalah referensi **selective reuse**, bukan kode yang boleh langsung disalin.
- Scope menulis code: **Codex VS Code** setelah GO; perubahan dokumen: **ChatGPT**.
- Branch docs ini adalah usulan tata kelola; **tidak memberikan GO CODING**.

> Saat memulai obrolan baru: minta ChatGPT membaca `WORKSPACE.md` + `19F board`; saat memulai Codex: berikan **satu work order yang sudah diberi GO**, bukan semua spesifikasi.
