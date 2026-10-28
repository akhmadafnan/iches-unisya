# 18C-05B — DRAFT MVP Implementation Slices, Exit Gates, Risks & UAT

> **KONVERSI KE TUGAS CODEX 28 OKT 2026:** Slice S0–S9 kini memiliki paket Work Order ber-ID dalam [19B Backlog](19B-BACKLOG-TEKNIS-CODEX-PER-SLICE.md), contoh prompt yang dapat ditempel setelah GO pada [19C](19C-TEMPLATE-INSTRUKSI-CODEX-VSCODE.md), dan pemetaan keputusan→kode→UAT pada [19D](19D-MATRIKS-ACUAN-DOKUMEN-KE-KODE.md). **Codex code only**, ChatGPT docs/review. Status tes RDY masih **PLANNED, NOT RUN**.

> **28 Oktober 2026 · RENCANA UNTUK REVIEW · BELUM GO CODING, TEST ATAU DEPLOY.**
>
> Tujuan: setiap slice kelak mempunyai dependency, hasil kerja yang bisa diverifikasi, acceptance criteria, pemilik keputusan, dan bukti tes nyata. **Website CMS, countdown dan Abstract intake adalah prioritas awal**, bukan fase dekorasi terakhir.
>
> [18C-05 Main](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md) · [18C-05A Reuse](18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md) · [18C-04E CMS & Countdown](18C-04E-AICIS-FEATURES-COUNTDOWN-CMS-AND-AGENT-SKILL.md) · [AI Skill](../.agents/skills/iches-public-frontend/SKILL.md).

## 1. End-to-end user journeys yang harus dijaga

**Public → single account → Personal Dashboard → Abstract Text+PDF → Reviewer Index langsung tanpa administrative screening → self-claim reviewer → Committee publishes final ACCEPT → Full Paper OJS-like unlocked tanpa payment gate → Presented confirmation oleh Moderator → Assessor recommendation → No Revision / Revision Required by Committee → Final ACC → Proceedings atau Selected Journal → verified OJS handoff.**

Selain itu, Co-Host Rp3 juta/institusi satu invoice dengan 5 seats/papers sponsor setelah VERIFIED; Paket Internasional Rp1 juta/pribadi dengan empat entitlement setelah VERIFIED; Finance memeriksa mutasi bank nyata; satu QR tidak otomatis Presented; sertifikat untuk semua listed authors paper Presented di-issue oleh panitia.

**Public editorial & CMS:** Academic Editorial original, AICIS sebagai inspirasi fitur bukan clone UI/workflow, countdown empat unit, Website CMS mampu menambah halaman/section tanpa edit kode rutin, publikasi draft/preview/publish, EN/ID/AR dan RTL. Semua bentuk section tetap revisable.

**TOR:** Abstract Submission 1–20 Desember 2026, konferensi utama 5 Januari 2027, program hari kedua 6 Januari 2027. Jam mulai event untuk countdown **belum terkonfirmasi**, jangan seed jam fiktif.

## 2. Urutan vertical slices (usulan, bukan jadwal tanggal)

| Slice | Fitur / output | Dependency | Exit gate |
|---|---|---|---|
| **S0 Foundation** | Laravel 13, Vue/TS, DB/PK approved, auth, role policies, i18n shell EN/ID/AR, private files, CI/tests, Switch Workspace own user | GO CODING eksplisit, hosting/runtime/DB pilihan | Fresh migration, auth/role/fk/private-storage tests, npm typecheck/build, CI evidence |
| **S1 Public + CMS + Countdown** | Original Academic Editorial, public pages, page/section editor, media, nav/footer, draft/preview/publish/rollback, multilingual SEO, countdown D/H/M/S from Event Master | S0; official start hour may be missing | Editor can modify/publish without deploy; countdown mock-clock boundary, RTL/mobile/SEO, no made-up time |
| **S2 Conference Abstract** | Tracks/settings, user My Papers, Abstract Text+PDF, draft/submit, author data, direct reviewer index | S0, S1 CTA integration | Valid submit directly visible to reviewer, no screening, owner & file protected |
| **S3 Reviewer & Published ACCEPT** | Reviewer eligible index/self-claim, COI, recommendations, Committee final decision publish | S2 | Reviewer cannot publish decision; published ACCEPT unlocks Full Paper |
| **S4 Finance + Co-Host + Package** | One bank Superadmin, invoice source-based, real bank credit match, 4 statuses, receipt, Co-Host 5 seats, personal 4 entitlements | S0, S3 for regular presenter invoices | Verified only from bank evidence, no duplicate bills, correct owner scope and gates |
| **S5 Full Paper OJS-like** | Scholarly metadata prefilled from Abstract, authors/affiliations/references, private manuscript versions | S3; file format/policy chosen | Unpaid ACCEPT author can submit; version history preserved |
| **S6 Session/Assessor/Revision** | Schedule, Moderator Presented, assessor recommendation, Committee No Revision / Revision Required, version v2, Final ACC | S5 | Attendance not Presented; no-revision skips duplicate upload; reviewer cannot Final ACC |
| **S7 QR/Services/Certificates** | QR personal, online scoped scanner/counter, fulfillment, certificate batch, public verify | S4 entitlements, S6 Presented | No double fulfillment, no cross-counter privilege, eligible author ≠ issued |
| **S8 Publication/OJS** | Proceedings/journal outlets, immutable snapshots, export profiles, XML validation/staging import/handoff | S5/S6 and actual OJS target | Only target-tested XML labeled ready-to-import; journal Selected ≠ Accepted |
| **S9 Integrated Acceptance** | End-to-end staging, accessibility/mobile/RTL, monitoring, backup/restore, staff training, production readiness | Selected release scope | Evidence of tests and owner UAT; do not expose incomplete actions |

**S0 includes Switch Workspace from the beginning.** Superadmin View as User read-only may be introduced once User Management exists, but backend deny-mutation safeguards must precede activation. Some frontend pages can be developed alongside backend using synthetic fixtures, but no protected page can launch without real authorization.

## 3. Incremental releases; no unrealistic all-at-once promise

| Release category | Scope | Explicit launch rule |
|---|---|---|
| **L0 Public Preview** | S0 + S1 / limited Coming Soon and conference information | CMS published content and truthful date, countdown only if time official; no fake submit CTA |
| **L1 Abstract Open** | S2 and its reviewer index, authentication and deadline gates | Text+PDF valid, private files, real persisted data, no administrative screening |
| **L2 Academic/Finance** | S3 + S4 according to actual billing program | One account, verified finance, Co-Host/Paket rules, no double charge |
| **L3 Event Operations** | S5 + S6 + S7 appropriate to event | Tested Moderator, reassessment, QR and certificate permissions |
| **L4 Publication** | S8 actual target staging and author consent/policy | Metadata can be handed off before Native XML ready, but cannot claim import-ready |

**Tanggal rilis tidak otomatis disamakan dengan tanggal TOR.** TOR Abstract opening **1 Des 2026** adalah target kesiapan yang harus diprioritaskan, bukan jaminan semua fitur telah selesai. Jika suatu modul belum lulus, sembunyikan tombol transaksi dan tampilkan informasi yang jujur.

## 4. Per-slice PR / acceptance packet

~~~text
ICHES Implementation PR / Slice
Branch / parent commit / business intent:
Relevant owner-approved decision and scope:
DB migrations + constraints + rollback plan:
Actor, role, event/institution/paper scope:
Business invariants and abuse cases:
Source re-used from old repo + provenance:
Tests RUN with commands and actual pass/fail:
Desktop/mobile/AR screenshots if UI:
CMS/data binding and real source of truth:
Feature flag / safe rollout & rollback:
Known OPEN policies and unresolved risks:
UAT sign-off / next dependent slice:
~~~

**Merge gate:** no secret or personal prod data; test DB migrations and policies; no unintended charge; PHP/Pest + static analysis + frontend TS/build + browser checks; actor and audit preserved, asset permissions confirmed. Historical “GREEN” docs from old repo are **not** test reports in target repo.

## 5. Blocking decisions by slice

| Open decision | Blocks | Can continue safely |
|---|---|---|
| Canonical repo + DB engine/PK/runtime | S0 migrations | Documentation and AI Skill |
| SMTP/domain/SSL/queue/storage | Production/email-dependent functions | Local development with synthetic fixtures and mail sandbox |
| Official 5 Jan start hour | Live ticking countdown | Countdown UI + fake-clock tests + truthful date placeholder |
| Real bank and mutation procedure | Production Finance Verified | Synthetic bank-match tests and UI |
| Multi-paper presenter fees / unknown TOR category | Specific invoice | Abstract + CMS + other categories |
| Refund/top-up/overpay/overdue | Automated exception handling | Core payment with bounded manual corrections |
| Full Paper required file type/assessor numbers | S5/S6 final gate | Metadata/UI prototype |
| OJS version/staging/10 actual journal candidates | Export compatibility and live journal list | Outlets CRUD and canonical snapshot design |
| Offline scanner native/PWA selection | Offline sync guarantees | Online-scanner contract and duplicate guard |
| Support view session TTL/delegation | Production View as User | Personal role switch and safe User Management |

## 6. Launch operations checklist

**Required before production**: hosting PHP (Laravel 13 minimum PHP 8.3, target PHP 8.4 suggested), approved DB/transaction support, Node build + SSR worker choice, HTTPS, one official domain, real email sender, protected env secrets, backup and recovery drill, queue/scheduler, accurate Event starts_at, approved logos/photos, privacy notices, and staff operational procedures. **None of those external capabilities has been confirmed accessible in this documentation task.**

## 7. Risk register

| Risk | Level (proposed) | Mitigation |
|---|---|---|
| Abstract opening approaching (1 Dec 2026) | High | Prioritize S0/S1/S2; separate L0/L1 gates |
| Old Finance verify accepts proof without bank mutation | Critical | Rewrite verification to require bank evidence, negative tests |
| Co-Host double billing | High | Invoice source uniqueness, institutional owner and 5-seat guards |
| Website hardcoded without real CMS | High | CMS Page/section CRUD and public publish tested in S1 |
| Countdown fakes exact hour | High | Null official hour, accurate placeholder, fake-clock tests |
| Generic AI-generated look | Medium | Follow Agent Skill, original photographic editorial art direction and visual review |
| Screening stage cloned from AICIS/legacy | High | Direct reviewer index tests, prohibit admin check |
| Inertia/Private file IDOR leaks | Critical | Resource policies, negative cross-owner tests |
| OJS claims ready without import test | High | Versioned profile/XSD + actual staging import |
| Scanner offline double fulfillment | High | Online-first idempotency and separate offline sync gate |
| Arabic RTL breaks | Medium | E2E keyboard/mobile/sidebar/date tests |
| Old README and CURRENT_STATE mismatch | Medium | Review actual source and rerun tests |
| Hardcoded speakers/journals/stats | High | Approved data only, otherwise truthful empty states |

## 8. Planned readiness UAT (45 scenarios, NOT RUN)

| ID | Scenario | Expected result |
|---|---|---|
| RDY-01 | Fresh clone target repo with implementation branch | Actual app bootstraps only after GO |
| RDY-02 | Composer install | Dependencies resolve with selected PHP |
| RDY-03 | Approved DB migration fresh | No FK/unique failure |
| RDY-04 | Migrate rollback + reapply | Safe repeatable test DB |
| RDY-05 | Email/password verification/reset | Safe session and recovery |
| RDY-06 | Role scoped across two editions | Cross-event denied |
| RDY-07 | Superadmin bank settings | Update allowed only Superadmin |
| RDY-08 | Revoke role mid-session | Immediate next-request denial |
| RDY-09 | Workspace switch | Same user, different allowed scope |
| RDY-10 | Read-only Support View | Real actor/audit, all mutation denied |
| RDY-11 | Original Academic Editorial design | Owner can review and revise; not a cloned AICIS UI |
| RDY-12 | CMS create new page | Appears only after publish |
| RDY-13 | CMS reorder/disable section | Live matches published revision |
| RDY-14 | CMS EN/ID/AR | Arabic RTL and translation state valid |
| RDY-15 | CMS preview/publish/rollback | Private draft, immutable history, proper cache |
| RDY-16 | CMS XSS attempt | Sanitized/rejected |
| RDY-17 | Unapproved speaker/partner | Not published |
| RDY-18 | Countdown with official instant | Correct D/H/M/S |
| RDY-19 | Countdown without official hour | Truthful date, no guessed ticking time |
| RDY-20 | Admin updates countdown target | Public time updates without redeploy |
| RDY-21 | Clock reaches target | Stops zero, no negative |
| RDY-22 | Viewer in different timezone | Same remaining interval |
| RDY-23 | Incomplete Abstract draft | Saved draft not Submitted |
| RDY-24 | Valid Text+PDF abstract | Reviewer Index directly |
| RDY-25 | Reviewer claim across tracks | Works if eligible, slot available |
| RDY-26 | Reviewer self/coauthor COI | Denied |
| RDY-27 | Reviewer recommends ACCEPT | Full Paper not unlocked |
| RDY-28 | Committee publishes ACCEPT | Full Paper unlock even Unpaid |
| RDY-29 | Unverified Co-Host requests 5 seats | Denied |
| RDY-30 | Verified Co-Host five representatives | At most 5; no sixth or second fee |
| RDY-31 | PIC views member personal invoice | Denied |
| RDY-32 | Verified personal program package | Exactly four entitlements |
| RDY-33 | Finance verify screenshot alone | Denied without bank mutation match |
| RDY-34 | Concurrent Finance verification retries | One authoritative Verified and receipt |
| RDY-35 | TOR fee category handling | Correct currency; unknown category never guessed |
| RDY-36 | Full Paper metadata+file v1 | Prefilled & private, versioned |
| RDY-37 | Assessor NO_REVISION | Committee can check v1 without reupload |
| RDY-38 | Revision Required v2 | Original immutable, response linked |
| RDY-39 | QR check-in without Moderator | No Presented status |
| RDY-40 | Double fulfill same service | Only one confirmed |
| RDY-41 | Presented paper with multiple coauthors | All eligible, no auto-issued certificate |
| RDY-42 | Journal selected | Not journal acceptance; no secret notes exported |
| RDY-43 | OJS mismatched XML version | Not ready-to-import |
| RDY-44 | Matching OJS staging import | Authors/files/locale/section verified |
| RDY-45 | Production go/no-go drill | HTTPS, secrets, backup/restore and owners verified |

**These are proposed acceptance criteria, not tests actually executed.** Their number is not added to a “PASS” total. Tests that require policies still undecided must be finalized only with owner-approved decisions.

## 9. Go / No-Go criteria

**Before S0 coding:** exact GO CODING from owner, canonical repo, DB/PK and stack runtime choice, specific first slice scope, reviewed selective reuse method and security acceptance. **Before production:** actual successful tests, owner UI/UX acceptance, operational backup/restore, domain/SSL/SMTP, bank/finance procedural proof, privacy/security checks, and authorized publication/QR scope. No plan document is sufficient alone to call a gate PASS.

## 10. Recommended first batch after GO

Start with **S0 foundation and S1 Public Academic Editorial + full Website CMS + Countdown**, then **S2 Abstract Intake** as the first academic workflow. Reuse tested core elements from the old repository only when compatible; keep owner choices on layout free. Do not try to ship 55 screen candidates all at once. Later Finance, post-presentation and OJS slices follow domain readiness.

**Status: DRAFT and awaiting review. No source code, migrations, queue, CMS or countdown implemented in the target repository.**

---

[18C-05 Main](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md) · [18C-05A Reuse](18C-05A-DRAFT-REUSE-AUDIT-AND-MIGRATION-PLAN.md) · [SKILL](../.agents/skills/iches-public-frontend/SKILL.md).
