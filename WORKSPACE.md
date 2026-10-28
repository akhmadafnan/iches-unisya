# ICHES 2027 — AI Agent Workspace

> **UPDATE 28 OCT 2026 — SETUP AUTHORIZED & COMPLETED; NO GO CODING.** On connected Windows desktop the owner-authorized canonical GitHub clone now exists at `D:\PINJAM-AFNAN\iches-unisya` (hyphen), remote `akhmadafnan/iches-unisya`, clean branch `docs/19e-agent-workspace` tracking origin, initial checkout SHA `432dcd012fa49b25019ad6464e7dc3759b99cd11`. The chosen VS Code at `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (space), v1.143.1, **opened the ICHES workspace**. OpenAI Codex extension `openai.chatgpt@26.51021.22027` is installed in the shared Windows user profile; **in-app sign-in/Skills loading NOT VERIFIED**. Four repo Skills plus AGENTS/PLANS verified. Git clone and GUI launch were done with user setup approval, **not application coding GO**. PR #1 is still draft/unmerged, and disposable MySQL DB plus technical decisions are pending. Earlier lines describing the target folder as absent or GUI as unlaunched are pre-setup historical observations.

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

## PRE-03 VERIFIED / PRE-02 OWNER APPROVED

- Codex read-only handshake **PASS**: four Skills directly readable, canonical remote, docs branch, clean worktree. Automatic Skill invocation not tested.
- MySQL97 Windows service **9.7.1** running; TCP port 3306 reachable, but authenticated isolated test DB **not verified**.
- [20D technical decision packet](docs/20D-PRE-02-TECHNICAL-DECISION-PACKET.md): **RD-01–RD-06 FINAL owner-approved**, including participant passwordless OTP/Magic Link. Authenticated isolated MySQL test DB still needed; **NO GO Coding**.


## Codex readiness check

- Owner screenshot confirms visible VS Code **Codex panel and chat history** in the ICHES workspace.
- [20C-02 READ-ONLY handshake](docs/20C-02-CODEX-READ-ONLY-HANDSHAKE.md) is the next action: verify all four Skills can be read by the running agent; **NO GO CODING**.

## Lokasi kerja lokal disepakati

- **Windows:** `D:\PINJAM-AFNAN\iches-unisya` — **berhasil di-clone**, branch `docs/19e-agent-workspace`, clean, 4 Skills dan PLANS tersedia.
- **VS Code utama pilihan owner:** `D:\PINJAM AFNAN\Microsoft VS Code\new_Code.exe` (**spasi**, v1.143.1), GUI sudah membuka ICHES. OpenAI Codex extension terpasang di profil pengguna; login/Skills invocation belum diverifikasi.

## Status verifikasi awal (28 Oktober 2026)
- Target: `akhmadafnan/iches-unisya`, `main`; source aplikasi target **belum ada** saat audit.
- Repo lama `akhmadafnan/international-conference-platform` adalah referensi **selective reuse**, bukan kode yang boleh langsung disalin.
- Scope menulis code: **Codex VS Code** setelah GO; perubahan dokumen: **ChatGPT**.
- Branch docs ini adalah usulan tata kelola; **tidak memberikan GO CODING**.

> Saat chat baru: baca `WORKSPACE.md` + 19F; saat Codex: berikan **work order Task/Sprint bernama yang sudah mendapat GO aktual**, bukan semua blueprint.
