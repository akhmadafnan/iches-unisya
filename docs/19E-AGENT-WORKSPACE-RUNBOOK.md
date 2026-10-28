# 19E — AI Agent Workspace: Operating Runbook & 48h Timebox

> **28 Oktober 2026 · DRAFT REVIEW · NO GO CODING.** Melengkapi, **bukan menggantikan**, 19A–19D dan AGENTS.md. Tujuannya mengubah repo dokumentasi menjadi lingkungan kerja Codex VS Code yang tidak melebar, dapat diulang setiap sesi, dan mudah diaudit.

## 20B superseding authorization note

One Task GO or finite named Sprint GO can authorize Codex code changes; append-only evidence journal allowed **only after actual named GO**. This revises earlier no-Markdown and per-task-only descriptions; see [20B](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md). 19F remains single status board. No GO Coding has been given.

## 1. Desain lingkungan kerja (minim konteks, maksimum bukti)
- **Single repository:** `akhmadafnan/iches-unisya` (rekomendasi canonical, butuh approval RD-01). Repo lama hanya sumber perbandingan selective reuse.
- **Single task in progress per operator Codex:** ID stabil dari 19B, ditulis pada 19F. Jika ada dua prioritas, antrekan; jangan buat agent paralel yang mengubah domain atau branch sama.
- **Single decision authority:** keputusan owner terbaru → `docs/09-KEPUTUSAN-DAN-ISU-TERBUKA.md` → dokumen domain → repo lama. Belum final = tetap open.
- **Single work order:** 19C satu prompt satu target; tidak ada "bangun seluruh aplikasi dari 50 dokumen".
- **Single review:** branch per task, diff/tes tertulis, PR; owner menyetujui merge, ChatGPT memperbarui board dan keputusan.
- **No duplication:** 19F menyimpan status dan pointer, 19B tetap backlog, 19C tetap template, 19D tetap matriks. Tidak memindahkan isi blueprint ke agent prompt.

## 2. Kontrak kerja per aktor
| Aktor | Boleh | Tidak boleh |
|---|---|---|
| Owner | Memutuskan, memberi GO per Task ID, menerima output & merge | Persetujuan menyusun rencana dianggap GO coding otomatis |
| ChatGPT | Audit repo/PR, merancang work order, menulis dokumen, update board dan keputusan | Mengedit source aplikasi yang telah dialokasikan ke Codex |
| Codex VS Code | Setelah GO: kode Laravel/Vue, migration/test, build dan lint yang in-scope pada branch fitur | Menulis `README.md`, `docs/**`, `AGENTS.md`, `.agents/skills/**`, secrets/data nyata, mengerjakan task lain |
| GitHub | Versi resmi blueprint, branch, PR, diff, hasil review | "Green" tanpa evidence tes aktual |
| Terminal | Inspeksi dan test yang diberi izin | Production migration, dump data, deploy tanpa otorisasi terpisah |

## 3. Start-of-session protocol
1. **ChatGPT:** periksa `main` terbaru, `WORKSPACE.md`, `docs/19F-CURRENT-SPRINT-BOARD.md`, lalu task/keputusan yang relevan. Jangan me-replan 19A–19D bila tidak ada perubahan requirement.
2. **Owner sebelum Codex:** pastikan remote repo target, `git status --short` bersih/masalah diketahui, branch bukan `main`, dan Work Order berisi `AUTHORIZATION: GO` yang diberikan secara eksplisit oleh owner untuk **ID tugas yang sama**.
3. **Codex:** baca `AGENTS.md`, `.agents/skills/iches-codex-engineering/SKILL.md`, satu Work Order dan **maksimal 2–4** sumber domain. Frontend/CMS wajib baca skill khusus. Baca source nyata sebelum edit.
4. **Codex:** tuliskan scope in/out, file yang benar-benar akan disentuh, dan test plan. Bila ambigu → `BLOCKED_DECISION`, jangan mengarang.
5. **Finish:** Codex mengembalikan ID, branch, commit/PR, changed paths, implemented/not implemented, exact test command + status, evidence screenshot bila UI, risiko. ChatGPT menilai spec/security; owner mengizinkan merge atau revisi.

## 4. Stop conditions (anti-scope-creep)
**STOP dan minta keputusan** bila: GO task belum ada; repository/branch tidak cocok; kerja tidak terkait work order; konflik dengan Register keputusan; DB/PK/hosting belum disetujui untuk migration; kode memakai verifikasi pembayaran lama berbasis screenshot; ada permintaan perubahan dokumen oleh Codex; butuh akses rahasia/akun produksi; perubahan memerlukan penghapusan data nyata; test penting gagal dan agent hendak mengklaim PASS.

**Batas perubahan:** satu PR menjawab satu tujuan bisnis. Perbaikan adjacent ditempatkan dalam queue/issue, bukan diselipkan diam-diam. Jika test gagal, Codex memperbaiki hanya kegagalan yang disebabkan task-nya, atau lapor blocker terpisah.

## 5. Definition of Ready (sebelum Work Order dikirim)
- [ ] Pemilik menyetujui pilihan teknis yang dibutuhkan task (bukan seluruh keputusan masa depan).
- [ ] ID, satu tujuan, dependency, scope in/out, allowed paths jelas.
- [ ] Accept criteria meliputi hasil nyata + minimal satu negative/security case.
- [ ] Sumber keputusan valid dan tidak bertentangan; data fixture bukan data asli.
- [ ] Git branch, toolchain dan test environment layak atau blocker tercatat.
- [ ] Ada GO CODING eksplisit untuk **task ini**. Jika belum, status tidak boleh `IN_CODEX`.

## 6. Definition of Done (bukan sekadar 'sudah coding')
- [ ] Diff sesuai scope; tidak ada perubahan dokumen atau secrets oleh Codex.
- [ ] Fitur bisa didemokan dengan data sintetis; tidak ada tombol palsu/dummy tanpa label.
- [ ] Automated test/build/typecheck/lint relevan **dijalankan** dan dilaporkan; tidak tersedia = `BLOCKED`, bukan PASS.
- [ ] Error/authorization/negative cases teruji sesuai tipe fitur.
- [ ] Reviewer ChatGPT memeriksa diff dan requirement, mencatat `REVIEW_PASS` atau `NEEDS_FIX`; owner menerima.
- [ ] PR/commit bisa ditelusuri ke Task ID; dokumen/board diperbarui oleh ChatGPT.
- [ ] **Merge/release membutuhkan keputusan terpisah**. Demo, PASS test dan merge adalah tiga hal berbeda.

## 7. Dua hari: timebox prioritas, bukan tenggat yang menjamin full aplikasi
**T0 hanya dimulai** ketika RD-01/02/03/04/06 untuk task awal telah diberi keputusan sesuai kebutuhan, perangkat lokal siap, dan owner mengucapkan GO untuk Task ID. Tidak boleh menghitung fase dokumentasi seolah coding sudah berjalan.

| Slot | Fokus satu per satu | Exit evidence | Pindah bila tidak siap |
|---|---|---|---|
| **Pra-T0** | Rapikan workspace, approve gate, audit PHP/Composer/Node/MySQL/Git/VS Code (read-only) | Board + keputusan owner + output versi | Jangan mulai kode |
| **Hari 1 (target)** | `ICH-S0-01` bootable Laravel/Vue selective reuse; cek test/build; lanjut pondasi identitas minimum **hanya bila task sebelumnya diterima dan GO berikutnya ada** | Branch/PR + boot/build/test actual, bukan screenshot | Kerjakan foundation; jangan melompat ke finance |
| **Hari 2 (target)** | Potongan S1 paling kecil yang aman: halaman publik original, inisiasi CMS typed blocks dan countdown berbasis event setting **setelah dependency siap** | Demo edit/publish + date/time truthful + tes yang relevan | Prioritaskan CMS yang benar daripada memalsukan fitur |
| **Stretch setelah gate lulus** | S2 Abstract Text+PDF dan review index minimal | Submit valid, private PDF, auth negative test | Tunda tanpa klaim selesai |

**Cut line:** jika 48 jam habis dan belum PASS, kirim versi hasil aktual + daftar blocker dan rencana lanjutan. S3–S9 (finance, Co-Host, reviewer lanjut, QR, publication) **tidak termasuk janji 48 jam**; kompleksitas/security masih perlu UAT.

## 8. Repo hygiene & PR
- Branch naming: `docs/19e-agent-workspace` untuk tata kelola ini; implementasi kelak `feat/<task-id>-slug`.
- Hindari merge otomatis; bagi perubahan dokumen dalam PR khusus docs. Untuk code gunakan [.github/PULL_REQUEST_TEMPLATE.md](../.github/PULL_REQUEST_TEMPLATE.md).
- Cek `git status`, `git diff --check`, diff scope, secret scans yang tersedia sebelum push. Tidak force push, tidak rewrite `main`.
- Hanya gunakan `.agents/skills/*` yang tersedia di repo; adanya skill file tidak membuktikan ekstensi VS Code telah mendeteksi skill. Verifikasi saat PRE-03.
- Tanpa live account, bank mutations, production database, file private user, atau metadata peserta nyata pada test.

## 9. Serah terima setiap sesi
Gunakan format singkat ini saat kembali ke ChatGPT:

```text
ICHES HANDOFF
Task ID:
Authorization (GO reference / NO GO):
Branch + HEAD + PR:
Status (PLANNED/BLOCKED_DECISION/READY_FOR_CODEX/IN_CODEX/REVIEW/ACCEPTED):
Goal + acceptance:
Changed paths (code only):
Actual test commands/results (PASS/FAIL/BLOCKED):
Risks/open owner decisions:
Next single action:
```

Selanjutnya ChatGPT merekonsiliasi dengan [19F Board](19F-CURRENT-SPRINT-BOARD.md) dan [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md). Tidak ada status ditetapkan hanya berdasarkan pesan "berhasil" tanpa diff/tes.
