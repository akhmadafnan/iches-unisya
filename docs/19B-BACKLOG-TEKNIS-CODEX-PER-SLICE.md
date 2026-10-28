# 19B — Backlog Teknis Siap Dipecah Menjadi Tugas Codex VS Code

> **CURRENT OWNER OVERRIDE RD-03C (28 Oct 2026):** Exact database name **`iches-unisya`** (hyphen), manual phpMyAdmin creation, temporary local root connection, no separate test DB. This supersedes previous `iches_unisya` names. [21J-02](21J-02-ICHES-DEV-DB-NAME-AND-LOCAL-ACCESS.md) and [21J-03](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md).

> **OWNER DECISION 21J-01 (28 OCT 2026):** DB design for `ICH-S0-02` now uses **one dedicated ICHES primary development schema `iches_unisya`** instead of mandatory `iches_unisya_test`; do not run destructive reset as default. [RD-03B final](21J-01-SINGLE-PRIMARY-DEV-DATABASE-DECISION.md). Other backlog statuses below are historical/draft until their work orders are explicitly authorized.

> **28 Oktober 2026 · BACKLOG DRAFT / CODE NOT AUTHORIZED.** Hasil mengubah dokumentasi **18B / 18C** menjadi pekerjaan teknis **ber-ID**, bukan menandai semua rekomendasi sebagai kebijakan final. **Codex VS Code satu-satunya pelaksana perubahan CODE**, ChatGPT mengelola dokumen/backlog, pemilik produk memberi GO per scope.
>
> **Baca terlebih dahulu**: [19A Protokol Pembagian Peran](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [19C Template Task Packet](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) · [19D Source Traceability](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md) · [18C-05B original slice plan](18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md).

## 0. Aturan backlog dan urutan prioritas

- Status seluruh task coding di bawah pada 28 Oktober 2026 = **PLANNED**, atau **BLOCKED_DECISION** bila pilihan teknis perlu ditutup. **Tidak ada IN_CODEX / PASS.**
- Setiap task menjadi satu Work Order berisi **Scope IN, Scope OUT, Allowed files, Sources, Dependencies, Acceptance tests, Actual evidence**. Template ada di 19C.
- Urutan rekomendasi: **S0 Foundation → S1 Public/CMS/Countdown → S2 Abstract** (sangat relevan menuju window 1–20 Des 2026). S3+ menyusul berdasarkan dependency; user tidak ingin debat hero/section satu per satu.
- **Tabel database/primary key tidak boleh diambil mentah dari 38+15 draft.** Tentukan canonical schema untuk vertical slice terlebih dahulu.
- Semua desain public visual **revisable**, meski Academic Editorial sudah dipilih. AICIS hanya referensi fitur; countdown dan CMS adalah kebutuhan wajib.
- Task IDs stabil; bila scope berubah, tingkatkan paket/versi, bukan mengganti nama semua task.

## 1. Pre-implementation oleh ChatGPT + owner (BUKAN CODEX CODING)

| ID | Deliverable | Penanggung jawab | Blocking/gate |
|---|---|---|---|
| **ICH-PRE-01** | Audit source GitHub, beda repo baru-vs-lama, daftar direct reuse/replace dan risiko data | **ChatGPT** | Audit perencanaan 18C-05A selesai; runtime re-test belum |
| **ICH-PRE-02** | Satu bundel keputusan: canonical repo, DB MySQL vs PostgreSQL, key UUIDv7 vs BIGINT, PHP/Node, auth baseline, branch policy | **ChatGPT usulkan → OWNER setujui** | **BLOCKED_DECISION** sebelum S0 migration |
| **ICH-PRE-03** | Perangkat kerja VS Code/Codex dan clone repo, akses branch/PR, ketersediaan PHP/Composer/Node/MySQL test | **Owner + terminal bila perlu** | Belum diperiksa di komputer |
| **ICH-PRE-04** | GO CODING khusus S0 dengan izin terbatas; template Work Order dikirim kepada Codex VS Code | **Owner**, ChatGPT siap dokumen | **NO GO YET** |
| **ICH-PRE-05** | Dokumen kebijakan terbuka tetap berstatus open: bank, 10 jurnal nyata, refund/multi-paper, jam countdown, scanner mode | **Owner + ChatGPT** | Tutup sebelum *slice* terkait, tidak mengarang |

## 2. S0 — Laravel Foundation (prioritas P0; work orders kecil)

| ID | Task untuk CODEX VS Code | Referensi khusus | Exit / acceptance |
|---|---|---|---|
| **ICH-S0-01** | **Port foundation secara selektif** dari repo lama ke branch repo baru: Laravel/Vue app skeleton, *tanpa* old payment business flow/secret/real participant data | 18C-05A; old `composer.json/package.json`; `AGENTS.md` | Project Laravel bootable, dependency locked, explicit provenance; `composer install`, `npm install`, `npm run build` berhasil setelah environment tersedia |
| **ICH-S0-02** | **Schema identity/events + UUIDv7**, migrations and synthetic fixtures, official event date/time **tanpa mengarang start hour** | 18C-01/01A; RD-03C/04; [21J-03 fast Work Order](21J-03-ICH-S0-02-FAST-TRACK-WORK-ORDER.md) | Owner manually creates exactly **`iches-unisya`** in phpMyAdmin; temporary local root credentials untracked; verify selected DB then additive `migrate` / FK/unique/UUID/rollback checks; no auto destructive reset; named GO required |
| **ICH-S0-03** | **Session-backed passwordless attendee OTP/Magic Link**, register/login/logout, email verification, rate limits and local demo fixture support; staff auth separately controlled | Approved NEW-003/RD-06; [21K-06 dev seed convention](21K-06-DEV-DEMO-USER-SEED-POLICY.md) | Dev users `@iches.test`, shared hashed `password` **only for local demo login if added**, idempotent seeder; no mandatory participant password, no prod demo route/credentials; tests/CSRF/session and scoped auth |
| **ICH-S0-04** | **Scoped RBAC/Policies** per Event/Institution/Paper/Session; Superadmin-only Bank Setting policy baseline; account multi-role & Switch Workspace | 18C-03, 18C-03A, 18C-03B | Test cross-event/institution denial; switch **same user_id**; role revoked denial |
| **ICH-S0-05** | **File/storage foundation** private manuscript/payment proofs vs public CMS media; signed scoped download and media validation | 18C-03, 18C-05A | No IDOR/private URL leak; public media are explicitly published/approved |
| **ICH-S0-06** | **Inertia/Vue shell + i18n EN/ID/AR RTL** and shadcn-vue Backoffice, mobile navigation, Account Switcher | 18C-04/04A; 18C-03B | Typecheck/build; AR dir/language, keyboard nav, scopes; no public UI hardcoded content |
| **ICH-S0-07** | **Automated verification pipeline**: Pest/Feature tests, PHP style/static analysis, Vue build/typecheck, unit test setup, CI on branch/PR | 18C-05B; 19C | Documented actual commands; reproducible CI; no claim “PASS” without run |
| **ICH-S0-08** | **Read-only Support View (Superadmin)** baseline once User Management exists, with real actor separate, time-bound reason & audit; all write routes denied | 18C-03B/03A | Unauthorized support rejected; no true impersonation; all POST/PUT/PATCH/DELETE and private downloads denied |

**S0 delivery:** S0-01..07 constitute minimal foundation; **S0-08 can follow later** when User Management is ready. Do not require complete Support View for mere Laravel bootstrap, but implement proper policies from the beginning.

## 3. S1 — Public Academic Editorial + Website CMS + Countdown (prioritas P0)

| ID | Task untuk CODEX VS Code | Referensi | Exit / acceptance |
|---|---|---|---|
| **ICH-S1-01** | **CMS persistence**: pages, typed blocks, immutable revisions, translations and public media bindings; minimal schema chosen after S0 | 18C-04D/E; Skill CMS reference | Pages+sections editable & reused; schema reviewed, no arbitrary JS |
| **ICH-S1-02** | **CMS authorization & publication lifecycle**: draft, private preview, atomic publish, rollback, audit and cache invalidation | 18C-04D; 18C-03 | Non-publisher denied; draft never leaks publicly/SEO, rollback reproducible |
| **ICH-S1-03** | **Backoffice Website CMS UI** shadcn-vue page list/create/edit, add/hide/duplicate/reorder section, variant/fields, media/nav/footer/SEO, EN/ID/AR | 18C-04D; 18C-04A; Skill | An Admin can create a new page/change sections without source edits/deploy |
| **ICH-S1-04** | **Extensible section registry/rendering** with typed fields, localizable content, safe domain bindings, layout variants **not locked** | Skill; 18C-04C/D | New section type can be registered once, any published page can reuse; no raw script |
| **ICH-S1-05** | **Original Academic Editorial public frontend** home + core inner pages, content priority AICIS-style feature coverage, responsive non-generic layout | Skill `iches-public-frontend`; 18C-04B/E | Desktop/mobile original hierarchy, no carousel, no fake speakers/photos/partner stats; owner visual review |
| **ICH-S1-06** | **Mandatory countdown D/H/M/S** from Event Master local date+official time in Asia/Jakarta, editable CMS display/placement | 18C-04E; Skill CMS reference | Fake-clock before/at/after, timezone test, no assumed 00:00, RTL; changes via Admin without deploy |
| **ICH-S1-07** | **CMS media/localization/SEO**: public media handling with alt/crop, EN/ID/AR content review, canonical/OG/sitemap, SSR decision compatible host | 18C-04B/D/E | Private file never public; AR proper RTL; metadata published-only; no invented translations |
| **ICH-S1-08** | **Browser QA & polish**: reviewer-facing preview desktop/tablet/mobile, loading/empty/error states, content lifecycle and performance | Quality Gates skill, 18C-04A | Actual Playwright/browser evidence or explicit blocked environment; fix visual issues via Codex not ChatGPT |

**S1 delivery condition:** CMS is **real CRUD** that can add pages and sections, not code-driven static homepage plus admin settings form. Countdown is **functional and editable**; if precise start hour missing, truthful state shows date only until set. Visual components remain subject to owner revision.

## 4. S2 — Abstract Submission/Conference Settings (prioritas P0)

| ID | Codex task | Referensi | Acceptance |
|---|---|---|---|
| **ICH-S2-01** | Edition tracks, Important Dates and CFP window Admin CRUD (source-of-truth) | 18B-02; TOR; 17 Admin | Public timeline/CTA matches actual backend state and date |
| **ICH-S2-02** | Abstract Draft & Submitted state: Text+PDF required on final submit (Draft may incomplete) | 18B-02; 18B-03 | Submit valid → Submitted; invalid text/PDF rejected server |
| **ICH-S2-03** | Paper author metadata and files linked to correct owner/event | 18B-09; 18C-01B | Coauthor without account & mononym supported, order preserved, file private |
| **ICH-S2-04** | Personal My Papers list/detail/status + author notices and form validation | 18C-04A; 18B-02 | Only own papers; clean mobile forms; no recommendation shown as final |
| **ICH-S2-05** | **Direct Reviewer Index integration** for valid Abstract Submitted | 13; 18B-02; 18B-03 | **NO administrative screening**, no admin PASS gate |
| **ICH-S2-06** | Negative/security/end-to-end tests Abstract + frontend CTA | 18C-03A; 18C-05B RDY-23..24 | Deadline/owner/protected file/COI-ready state; actual executed evidence |

**S2 launch condition:** candidate can register, create draft and submit both text+PDF securely; Reviewer Index receives the paper directly. This does not require full Finance/Publication completed.

## 5. S3–S9 implementation epics (detail task packet dibuat sebelum tiap slice)

| ID / slice | Intended engineering work | Prasyarat/gap wajib |
|---|---|---|
| **ICH-S3-01** Reviewer Index, claim/COI/recommendation | S2; reviewer assignment/claim limit rule to approve |
| **ICH-S3-02** Committee Academic Decision & **published ACCEPT** | Review complete; acceptance stamp; unlock Full Paper independent of invoice |
| **ICH-S4-01** Bank Settings Superadmin-only + institutional/personal invoice ledger | DB strategy and official account approval; no fake bank |
| **ICH-S4-02** Finance proof+credit reconciliation+manual verification+receipt | **Actual bank mutation matching required**, not old VerifyPaymentAction |
| **ICH-S4-03** Co-Host institutional enrollment, 5 rep seats/papers, no double presenter fee | Verified only; replacement/rejected sponsored paper policy open |
| **ICH-S4-04** Personal International Package one Rp1m invoice + 4 entitlements | Verified only; personal privacy from PIC |
| **ICH-S5-01** Full Paper OJS-like wizard and canonical scholarly metadata/versioned files | Published ACCEPT, no Finance gate; final file type policy open |
| **ICH-S6-01** Session/Moderator Presented vs QR | Assignment & audit, not scanner-derived |
| **ICH-S6-02** Assessor + Committee Revision Required/No Revision + Final ACC | Draft rubric/rounds still open; original manuscript preserved |
| **ICH-S7-01** Scoped online QR/check-in/services + certificate batch/public verify | Online-first tests; offline separately approved |
| **ICH-S8-01** Proceedings/10 journal candidates + OJS export/handoff | Real outlet details/target OJS staging required; Selected ≠ Accepted |
| **ICH-S9-01** Integrated QA, data privacy, deployment readiness and operator UAT | Only verified features may go live; backup/restore, staging evidence |

**Do not treat “all 55 UI candidates” as 55 implementation tickets.** Group by vertical user journey; component architecture should be reused sensibly.

## 6. First work order — recommendation and gate

**First Codex coding task after GO:** `ICH-S0-01` only, implementing **selective skeleton/foundation in a new branch**, with explicit exclusions: **NO Finance transaction coding, NO payment registration port, NO Abstract beyond skeleton, NO production deploy**. The first work packet in [19C](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) is prepared as a template, **not yet sent to Codex**.

**Before start:** approve canonical target repo, DB engine, PK convention, available runtime; confirm VS Code opened **correct repository**, not repo lama; create branch and establish clean `git status`; verify no committed `.env`/secrets.

## 7. Reporting standards for each task

Each completed Codex task must report **Task ID; branch; changed code paths; actual commands and pass/fail; screenshots for UI; migrations and risks; out-of-scope untouched; known blockers**. ChatGPT reviews code via diff/PR and updates backlog status. Owner decides accept/merge; only then proceed. No task gets `DONE` merely because Codex said “implemented”; evidence and authorization matter.

---

[19A Protocol](19A-PROTOKOL-KERJA-CHATGPT-CODEX-VSCODE-GITHUB.md) · [19C Task Prompt](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md) · [19D Traceability](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md).
