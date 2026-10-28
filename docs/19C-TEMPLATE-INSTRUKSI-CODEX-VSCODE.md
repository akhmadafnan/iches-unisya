# 19C — Work Order Template & Prompt Siap Pakai untuk Codex di VS Code

> **28 Oktober 2026 · DOKUMEN INSTRUKSI, BUKAN OTORISASI CODING.** Pemilik produk ingin **Codex VS Code khusus mengubah kode**; ChatGPT mengelola backlog, dokumen, review dan koordinasi repo. Semua prompt di bawah **baru boleh digunakan untuk kode setelah GO CODING eksplisit** untuk task bersangkutan.
>
> [19A Protocol](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [19D Traceability](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md) · [Codex Engineering Skill](../.agents/skills/iches-codex-engineering/SKILL.md).

## Opsi 20B — Sprint Work Order (workflow disetujui, belum GO)

Selain GO per Task ID, owner dapat memberi **GO SPRINT [ID]** untuk daftar Task IDs terbatas yang sudah disetujui, berurutan, dengan scope, branch, test dan stop conditions eksplisit. Lanjut antar-task hanya setelah PASS+evidence; task di luar daftar tetap terlarang. Gunakan [template Sprint](20B-APPROVED-SPRINT-AUTHORIZATION-AND-EXECUTION.md). Tidak ada GO Coding saat ini.

## 1. Cara bekerja dari VS Code tanpa kebingungan

**Saat belum GO:** cukup simpan dokumen ini di GitHub dan, bila ingin, lakukan inspeksi lingkungan via terminal read-only. **Tidak ada instruksi kepada Codex untuk membangun sesuatu.**

**Setelah GO pada task tertentu:**
1. Buka VS Code pada **local clone `akhmadafnan/iches-unisya`**, **bukan folder repo lama**. Pastikan remote, branch, clean worktree. Dokumen dan AI Skills berada pada repo ini.
2. Buka ekstensi **Codex** di VS Code. AI membaca root `AGENTS.md` dan skill yang diminta. Jangan hanya paste ribuan halaman konteks; cukup Work Order berisi **Task ID** + referensi.
3. Berikan satu Work Order **spesifik**. Codex memeriksa source dan branch, mengedit **kode saja**, menambahkan/memperbaiki tes yang terkait, menjalankan perintah yang benar-benar tersedia.
4. Tinjau `git diff` dan ringkasan hasil; jangan otomatis membiarkan Codex memodifikasi `docs/`, `README.md`, `AGENTS.md`, `.agents/skills/`, credential atau data produksi.
5. Bagikan **branch/commit/PR dan output tes** ke ChatGPT. ChatGPT menilai kelayakan, menyusun instruksi revisi atau acceptance report; lalu owner memutuskan merge/lanjut.
6. Jangan menandai task DONE atau GO production dari screenshot UI semata.

Codex dapat membaca dokumen yang relevan sebagai konteks. **Larangan mengedit docs** dalam pembagian ini **bukan larangan membaca docs**. Codex boleh menjalankan terminal untuk **test/build yang berkaitan langsung dengan kode yang dikerjakannya**, bukan deploy/produksi. Terminal manual juga dapat dipakai untuk meninjau `git status`/test output.

## 2. Template Work Order standar (diisi ChatGPT → dikirim user ke Codex)

~~~text
ICHES CODEX WORK ORDER — [ICH-Sx-yy] — [TITLE]
AUTHORIZATION: GO CODING untuk task ini telah diberikan owner [YA/TIDAK]
REPOSITORY: akhmadafnan/iches-unisya
BRANCH: feat/[task-slug] (buat jika belum ada; jangan edit main)
CURRENT SOURCE BASELINE: baca HEAD dan git status, jangan asumsikan ada Laravel app
OWNER-APPROVED DECISIONS: [hanya keputusan final yang berlaku]
OPEN TECHNICAL DECISIONS: [jangan diisi default tanpa persetujuan]

PRIMARY SOURCES (open exact paths):
- AGENTS.md
- .agents/skills/iches-codex-engineering/SKILL.md
- [2-4 dokumen domain relevan]

TASK GOAL (one business objective):
[Satu hasil nyata dan dapat diuji]

SCOPE IN:
- [Kode aplikasi/migration/test tepat yang boleh diubah]
SCOPE OUT:
- docs/**, PLANS.md, WORKSPACE.md, README.md, AGENTS.md, .agents/skills/**
- Exception with named GO: harness/build-log.md append-only and sanitized harness/evidence/**
- .env, secrets, production data, unrelated features, unrelated refactors
- [Modul spesifik yang belum boleh disentuh]

ALLOWED CODE PATHS (tentative; first inspect actual repo):
- app/**, resources/**, routes/**, database/**, tests/** ...
- build/dependency configs only if necessary for this task

ACCEPTANCE:
- [Given/When/Then happy path]
- [Unauthorized/cross-owner negative case]
- [Concurrency/idempotency as applicable]
- [UI mobile/RTL/CMS/readiness if relevant]
- [Source of truth, no invented policy/data]

VALIDATION:
- Inspect actual scripts in composer.json/package.json before choosing commands.
- Run relevant automated tests, typecheck, lint, build if environment supports.
- Run only on local/test DB; never migrate production.
- Report exact commands, exit codes and failed/blocked tests.

OUTPUT BACK TO CHATGPT:
- Task ID, branch, commit/PR (if made), changed code paths
- Implemented vs not implemented
- Diff summary and why
- Tests actually run, pass/fail, environment limitations
- Known risks/open decisions and screenshots for UI
- Do not modify governance/spec documents; ChatGPT updates them
STOP CONDITIONS:
- Invalid repo/branch/dirty unrelated changes
- Need undecided schema/finance policy/credentials
- Any action requiring production data, bank or deploy permissions
- Conflict with owner-approved requirement
~~~

**Pastikan `AUTHORIZATION` bukan sekadar tulisan template**: user sendiri harus memberi GO. Jika belum ada, work order dapat disimpan tetapi Codex tidak disuruh mengeksekusi.

## 3. Work Order pertama yang DIUSULKAN: ICH-S0-01 Foundation Port

**STATUS: TEMPLATE SIAP, BELUM GO.** Task pertama **bukan** seluruh backend/website sekaligus. Tujuannya project bootstrap yang bisa diaudit.

~~~text
ICHES CODEX WORK ORDER — ICH-S0-01 — Selective Laravel/Vue Foundation
AUTHORIZATION: [ISI "GO" HANYA SETELAH OWNER MENYETUJUI]
REPOSITORY: akhmadafnan/iches-unisya
BRANCH: feat/s0-foundation

READ:
AGENTS.md
.agents/skills/iches-codex-engineering/SKILL.md
docs/18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md
docs/18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md
docs/19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md

PRECONDITIONS:
- Owner approved repo canonical, DB/PK and PHP/Node runtime.
- Inspect actual repo tree; if only documentation exists, report this.
- Confirm legacy source branch and desired reuse approach; do not copy .env.

GOAL:
Build a minimal, bootable Laravel 13 + Inertia/Vue 3 + TypeScript
application foundation in the NEW repo by selective port of vetted
technical foundation from OLD repo (not business flow).

IN:
Composer/npm/lockfiles, app bootstrap, routes, base Vue entry,
test runner baseline and CI scripts necessary for this goal.
Use PHP/DB/PK choices that owner approved, not assumptions.
OUT:
No copying legacy payment/registration business logic.
No Finance/CoHost/Abstract/OJS/CMS/countdown feature yet.
No changes to docs, README, AGENTS.md or .agents/skills.
No real accounts/participant records, secrets, deployment.

ACCEPT:
Project boots locally, versions resolved; auth/ui code if already ported
remains limited to foundation; actual install/build/test evidence,
no secret leak, clean diff and scoped commit/PR.
STOP if technical choices or runtime are unavailable and report blockers.
OUTPUT: branch, exact paths, provenance, run commands and results.
~~~

**Catatan:** jika `composer.lock` tidak kompatibel environment, Codex harus mengusulkan dependency resolution, bukan mengubah versi framework sembarangan. **Database migrations** domain baru dibuat pada ICH-S0-02, bukan memaksakan 53 kandidat tabel di S0-01.

## 4. Contoh prompt tugas S1 yang akan datang — CMS (setelah S0)

~~~text
ICHES CODEX WORK ORDER — ICH-S1-03 — Backoffice Website CMS Editor
AUTHORIZATION: [GO diperlukan untuk task ini]
BRANCH: feat/s1-cms-editor
READ:
AGENTS.md
.agents/skills/iches-codex-engineering/SKILL.md
.agents/skills/iches-public-frontend/SKILL.md
docs/18C-04D-DRAFT-VISUAL-CMS-BLOCK-LIBRARY-EDITING-CONTRACT.md
docs/18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md
docs/18C-03-DRAFT-AUTHENTICATION-RBAC-SECURITY-ARCHITECTURE.md

DEPENDENCY: ICH-S1-01 CMS schema + ICH-S1-02 publish/preview guards.
GOAL: Build shadcn-vue Backoffice → Website CMS for authorized staff.
IN: list/create/edit pages, blocks add/hide/reorder/duplicate,
supported variant controls, localized content, media binding,
preview/publish UI calling server-authorized Actions.
OUT: no hardcoded main-site contents as sole source,
no frontend locked layout, no arbitrary JS/CSS injection,
no Finance/Academic mutation access, no docs edits.
ACCEPT: staff creates a page and sections without code edit,
unpublished draft not visible publicly, publish/rollback
updates live pages, unauthorized Editor cannot publish,
EN/ID/AR and mobile admin usable.
VALIDATE: actual backend/frontend tests and browser screenshots.
OUTPUT: exact diff, test results, risks and what remains.
~~~

## 5. Contoh prompt tugas S1 lainnya — Countdown Wajib (setelah Event Master)

~~~text
ICHES CODEX WORK ORDER — ICH-S1-06 — Functional CMS Countdown
AUTHORIZATION: [GO diperlukan untuk task ini]
BRANCH: feat/s1-countdown
READ:
AGENTS.md
.agents/skills/iches-codex-engineering/SKILL.md
.agents/skills/iches-public-frontend/SKILL.md
docs/18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md
.agents/skills/iches-public-frontend/references/CMS-AND-DATA-CONTRACT.md

GOAL:
A public D/H/M/S countdown whose target is the OFFICIAL event start
time (Asia/Jakarta) in Event Master, and whose placement/labels/visibility
are editable through Website CMS. It must not hardcode 00:00.
If exact hour remains unset, show a truthful date-only/TBA state.

IN: typed countdown component, event binding, localized display,
CMS editing controls and state handling, fake-clock tests.
OUT: no new invented date/hour, no standalone competing event date
in CMS rich-text, no additional payment/signup bypass, no docs edits.
ACCEPT: future positive count decreases; exact boundary 0;
never negative; changing Event Master time propagates after publish;
timezone viewer-independent; AR RTL & reduced-motion/accessibility.
REPORT: actual tests, browser screenshots, incomplete runtime support.
~~~

## 6. Contoh prompt S2 — Submission tanpa Administrative Screening

~~~text
ICHES CODEX WORK ORDER — ICH-S2-02 — Abstract Draft/Submit
AUTHORIZATION: [GO diperlukan untuk task ini]
BRANCH: feat/s2-abstract-submit
READ:
AGENTS.md
.agents/skills/iches-codex-engineering/SKILL.md
docs/18B-02-CRUD-INTERNATIONAL-CONFERENCE-SUBMISSION.md
docs/13-LINK-COHOST-DAN-SUBMISSION-TANPA-SCREENING.md
docs/18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md

GOAL:
Authenticated submitting author can Save Draft, then Submit valid
Abstract TEXT + PDF. Valid Submitted record immediately enters
Reviewer Abstract Index; NO administrative screening gate.
IN: scoped FormRequest/Policy/Action, private PDF storage,
status and date-window tests, personal form.
OUT: no final ACCEPT/reviewer decision, Finance gate, CoHost bill,
Full Paper submission, unrelated business code, no docs edits.
ACCEPT: Draft may be incomplete; Submitted requires both text and
valid PDF; owner/file authorization; direct index; no payment required.
REPORT: exact tests, 403/422 negative cases, schema migration delta.
~~~

## 7. Format balasan ke ChatGPT setelah Codex mengerjakan kode

~~~text
TASK: ICH-Sx-yy
BRANCH: feat/...
COMMIT / PR: ...
FILES CHANGED (CODE ONLY): ...
IMPLEMENTED: ...
NOT IMPLEMENTED / BLOCKERS: ...
TEST COMMANDS AND EXIT CODES: ...
MIGRATIONS / DB USED: local-test or none
SECURITY NEGATIVE CASES: ...
VISUAL SCREENSHOTS (if UI): ...
REQUEST FOR CHATGPT REVIEW: ...
~~~

ChatGPT kemudian memeriksa diff terhadap blueprint dan membuat keputusan `REVIEW_PASS` / `NEEDS_FIX` / `BLOCKED_DECISION`. Jika `NEEDS_FIX`, **hanya Codex** memperbaiki source code. ChatGPT menulis hasil review di repository dan owner memilih merge atau lanjut.

## 8. Jangan membuat tugas Codex terlalu luas

**Tidak disarankan:** “Bangun semuanya sesuai 53 dokumen sampai selesai.”  
**Disarankan:** “Kerjakan ICH-S0-01 sesuai Work Order ini, jangan mulai S0-02.”  
Selanjutnya, setelah pass: “Kerjakan ICH-S0-02, dengan schema yang sudah disetujui.”

**Prompt pertama untuk VS Code **belum dikirim/dijalankan** dalam tahap dokumentasi ini.**

---

[Backlog 19B](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md) · [Protocol 19A](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [Traceability 19D](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md).
