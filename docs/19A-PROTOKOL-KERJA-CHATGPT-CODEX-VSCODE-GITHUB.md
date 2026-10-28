# 19A — Protokol Kerja ChatGPT × Codex VS Code × GitHub × Terminal

> **28 Oktober 2026 · DRAFT OPERASIONAL / BELUM GO CODING.** Pengguna meminta pekerjaan blueprint ICHES diubah menjadi **task teknis**, dengan **Codex VS Code eksklusif bertugas melakukan perubahan/penambahan/perbaikan kode**. Untuk hal **selain kode** (analisis, keputusan, spesifikasi, review, dokumentasi, GitHub, koordinasi, terminal bila perlu) pengguna ingin menggunakan **ChatGPT**. Ini **pembagian tanggung jawab yang diminta**, bukan izin menjalankan kode/migrasi/deploy.
>
> Navigasi: [19B Technical Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [19C Codex Task Packets](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) · [19D Traceability & Gates](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md) · [18C-05 Readiness](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md) · [Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Tanggung jawab (role clarity)

| Pekerjaan | Pelaksana utama | Batas praktis |
|---|---|---|
| Menafsirkan kebutuhan, menetapkan acceptance criteria, mengelola issues/open decisions | **ChatGPT + pemilik produk** | ChatGPT **mengusulkan**, pemilik produk menyetujui keputusan yang berdampak |
| Analisis TOR, audit repo lama, arsitektur, konsolidasi DB, backlog dan prioritas | **ChatGPT**, dapat membaca GitHub | Usulan tidak berubah otomatis menjadi keputusan final |
| Markdown/blueprint, keputusan, SKILL.md, AGENTS.md, review report, issue/PR description | **ChatGPT → GitHub docs** | **Codex tidak mengubah dokumen tata kelola** kecuali user secara terpisah mengubah pembagian kerja |
| Membuat/ubah/hapus **application code** Laravel/PHP, Vue/TS, migrations, unit/feature/browser tests, build configuration | **Codex di VS Code** setelah task + GO | Dalam **branch fitur**, hanya scope task; tidak mengubah dokumen kebijakan, menyentuh data produksi, atau merge main otomatis |
| Menjalankan terminal setup, install, lint, typecheck, migrate test database, test E2E | **Codex VS Code dan/atau terminal pemilik produk** | Perintah test/dependency boleh dijalankan ketika diotorisasi, tidak sama dengan hak deploy/ubah produksi |
| Code review dari diff/PR, menilai kepatuhan spesifikasi, keputusan revisi | **ChatGPT** membaca GitHub; **pemilik produk** sign-off | Bila kode salah, **ChatGPT membuat instruksi perbaikan untuk Codex**, tidak menulis patch kode di belakang peran Codex |
| Branch/commit/push/PR | **GitHub sebagai sumber kebenaran**; Codex dapat menyiapkan commit pada branch **jika task mengizinkan**, user/ChatGPT review | Jangan push/merge otomatis ke `main`; pemilik produk tetap penentu GO/accept |
| Deployment, bank credential, email domain, OJS staging, production DB | **Pemilik produk/operator yang diberi izin** | Selalu memerlukan gate operasional dan persetujuan terpisah |

**Definisi “kode”** pada kebijakan ini: `app/`, `bootstrap/`, `config/`, `database/`, `resources/js/`, `resources/css/`, `routes/`, `tests/`, `composer.json`, `package.json`, lockfiles, Vite/TS/Pest/test runner config, dan build/deploy script/CI yang memang termasuk task. **Dokumen `docs/`, `README.md`, `AGENTS.md`, `.agents/skills/` diurus ChatGPT.** File `.env` dan secrets **tidak boleh pernah di-commit**.

## 2. Single source of truth dan repository

**Target rekomendasi:** [`akhmadafnan/iches-unisya`](https://github.com/akhmadafnan/iches-unisya) — saat protokol dibuat, **belum ada source aplikasi**, hanya dokumen/skill.

**Sumber untuk selective reuse:** [`akhmadafnan/international-conference-platform`](https://github.com/akhmadafnan/international-conference-platform/tree/develop), `develop`. Jangan menyalin seluruh tree repo lama, data peserta, `.env`, histori branch atau verifikasi pembayaran lama tanpa audit. File audit: [18C-05A](18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md).

**Urutan otoritas produk:** pesan keputusan eksplisit pemilik produk terbaru → [Register Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) → TOR dan dokumen operasional relevan → tahap rancangan arsitektur baru → contoh implementasi repository lama (yang mungkin bertentangan). **DRAFT bukan FINAL**. Setiap konflik teknis dengan keputusan owner dibuka sebagai issue, bukan “dibetulkan” diam-diam oleh Codex.

**Aturan Git:**
- Dokumen perencanaan saat ini diperbarui oleh ChatGPT di repository; implementasi belum dibuka.
- Setelah GO coding, gunakan branch per task/slice misalnya `feat/s0-foundation`, `feat/s1-cms-countdown`, `feat/s2-abstract-intake`, bukan edit langsung `main`.
- **Satu task/PR = satu tujuan bisnis**; jangan gabung unrelated refactor, perbaikan UI, Finance dan schema dalam satu PR.
- PR mencantumkan Task ID, referensi dokumen, scope file, bukti tes yang **dijalankan**, potensi breaking migrations, dan open issues.
- Untuk branch commit/push, jalankan `git status`, `git diff --check`, cek secrets dan file yang tidak disengaja. Hindari `push --force`, `reset --hard` atau branch rewrite tanpa instruksi jelas.
- ChatGPT dapat meninjau diff dari PR dan meminta Codex revisi, tetapi **tidak mengedit kode lewat GitHub langsung** mengikuti peran yang diminta.
- **Merge ke main** dan **deployment produksi** hanya setelah review dan persetujuan pemilik produk; urutan GO coding ≠ GO release.

## 3. Siklus tugas sampai selesai

~~~mermaid
flowchart TD
  O["Pemilik produk memberi arah & keputusan"] --> A["ChatGPT menulis Work Order spesifik + acceptance tests"]
  A --> G{"GO coding & technical gate untuk task?"}
  G -->|Belum| PLAN["Dokumentasi, review & keputusan; tidak menulis kode"]
  G -->|Sudah| C["Codex VS Code: branch → coding + tests"]
  C --> V["Codex laporkan diff, commands, results & gaps"]
  V --> R["ChatGPT review terhadap blueprint dan keamanan"]
  R --> Q{"Lulus & disetujui owner?"}
  Q -->|Belum| FIX["ChatGPT susun fix-request; Codex revisi kode yang sama"] --> C
  Q -->|Ya| MERGE["PR/merge atas persetujuan owner"]
  MERGE --> N["ChatGPT update backlog/status/docs di GitHub"]
~~~

**Tidak perlu membawa seluruh 50+ blueprint ke setiap prompt Codex.** Tiap Work Order menunjuk **maksimal beberapa sumber primer relevan** dan meminta Codex membaca lokasi spesifik. `AGENTS.md` tetap singkat; Skill hanya dibaca saat tugas relevan.

## 4. Lima bentuk status yang harus konsisten

| Status | Arti | Apa yang boleh dilakukan |
|---|---|---|
| **PLANNED** | Task terurai, belum disetujui untuk implementasi | Analisis/dokumen |
| **BLOCKED_DECISION** | Perlu pilihan owner atau fasilitas teknis | Audit/read-only, tidak buat migration asumsi |
| **READY_FOR_CODEX** | Spesifikasi dan dependency siap, **tetapi bisa belum GO** | Siapkan prompt, belum perubahan |
| **IN_CODEX** | User sudah GO task; Codex sedang membangun pada branch | Perubahan kode dalam scope |
| **REVIEW / ACCEPTED** | Diff/tests ada → ditinjau → diterima | Merge hanya setelah owner sign-off |

**Pisahkan STATUS TASK dari AUTHORIZATION.** “READY_FOR_CODEX” bukan izin menulis kode. Status **PASS** hanya untuk hasil test yang benar-benar dijalankan dan dilaporkan.

## 5. Apa yang boleh dilakukan SEKARANG, sebelum GO CODING

- ChatGPT: merapikan mapping blueprint → backlog → Work Order; mengidentifikasi konflik (MySQL/UUIDv7 dan lainnya), review semantik dokumen, membangun skill/code review checklist dan menyimpan di GitHub.
- Pemilik produk: dapat menyetujui pilihan teknis bundel atau meminta revisi scope.
- Codex VS Code: **belum diberi tugas edit kode**. Jika diperlukan pemeriksaan terminal, gunakan baca-versi/inspect read-only melalui terminal pengguna; jangan jalankan migrate pada database berisi data nyata.
- Terminal yang **aman untuk inspeksi**: `git status`, `git branch --show-current`, `git log -1 --oneline`, `php -v`, `composer --version`, `node -v`, `npm -v`, `git remote -v` (hapus/redaksi token dari output bila ada). Pada repo baru yang masih blueprint, `php artisan` **belum tersedia**.

## 6. Aturan “No AI slop” dan Full CMS

Pada tugas S1, AI Codex wajib membaca [Skill khusus public frontend](../.agents/skills/iches-public-frontend/SKILL.md). UI Academic Editorial original, countdown wajib dengan Event Master starts_at (jam resmi belum ada), Website CMS harus bisa membuat/edit halaman dan section/nav/media/translations/SEO/draft/preview/publish. Jangan mengunci hero/urutan komponen. AICIS hanya inspirasi fitur, **jangan menyalin desain**.

Codex coding skill tambahan berada di [`.agents/skills/iches-codex-engineering/SKILL.md`](../.agents/skills/iches-codex-engineering/SKILL.md) dan memberikan kontrak ruang kerja & verifikasi. **Keberadaan kedua skill tidak berarti sudah ada aplikasi atau izin coding**.

## 7. Pergantian konteks/obrolan

Saat membuka chat baru, **pemilik produk cukup menyebut Task ID** (`ICH-S0-01`, dst). ChatGPT membaca [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), [19C Work Order](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md), Register Keputusan, serta evidence dari GitHub, tanpa mengulang 50 dokumen sekaligus.

**Catatan operasional yang disepakati di sini belum mengikat editor tertentu selain Codex VS Code untuk coding.** Tidak memaksa pengguna menggunakan cloud PR automation, paid CI, spesifik hosting, atau workflow yang memerlukan akses akun baru.

## 8. Titik yang masih perlu persetujuan owner

- Target canonical repo baru + selective reuse lama: rekomendasi dari 18C-05, **belum final**.
- MySQL + UUIDv7 dan PHP 8.4 target: rekomendasi, belum final sampai lingkungan nyata diuji.
- Metode login, host production, fullpaper format, finance exceptions, OJS targets, start hour Countdown: masih open.
- **GO CODING pertama (misalnya S0)** diberikan **secara eksplisit**, bukan dari percakapan “susun pekerjaan teknis”.

---

[Backlog 19B](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [Work Orders 19C](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) · [Traceability 19D](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md).
