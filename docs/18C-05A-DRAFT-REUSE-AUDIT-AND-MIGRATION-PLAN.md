# 18C-05A — DRAFT Audit Reuse Repository Lama, Compatibility Gaps & Migration Strategy

> **28 Oktober 2026 · AUDIT BERDASARKAN FILE REMOTE GITHUB · BELUM CODE REUSE / MERGE / TEST RUNTIME.**
>
> **Tujuan:** jangan membuang pekerjaan Laravel lama yang telah selesai, **tetapi jangan membawa aturan V1 lama yang berkonflik**. Repository target blueprint: [`akhmadafnan/iches-unisya`](https://github.com/akhmadafnan/iches-unisya); repository sumber terpisah: [`akhmadafnan/international-conference-platform`, branch `develop`](https://github.com/akhmadafnan/international-conference-platform/tree/develop).
>
> [18C-05 Main Readiness](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md) · [18C-05B MVP slices](18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md) · [Original legacy repo audit](08-AUDIT-REPO-LAMA.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Sumber bukti (jangan mencampur repository)

| Sumber | Yang benar-benar dibaca | Batas |
|---|---|---|
| ICHES baru `main`, 28 Okt 2026 | Recursive git tree dan spesifikasi `docs/18C-01*`, `18C-02`, `18C-03`, `18C-04E` | File repo baru **hanya dokumentasi/skills**, belum ada Laravel app |
| Lama `develop`, 28 Okt 2026 | Recursive git tree, `composer.json`, `package.json`, `README.md`, `docs/ai-context/CURRENT_STATE.md`, `docs/architecture/IMPLEMENTATION_CONVENTIONS_V1.md` | Ada code dan tes, tetapi **belum menjalankan ulang di mesin/staging sekarang** |
| Source langsung | `app/Actions/Payment/VerifyPaymentAction.php`, `app/Actions/Registration/CreateRegistrationIntentAction.php`, `app/Http/Middleware/ApplyPermissionTeamContext.php`, `database/migrations/2026_10_03_120000_create_phase02_registration_payment_tables.php` | Bukti flow yang **tidak boleh diterima mentah** sebagai ICHES 2027 terbaru |
| Runtime claimed by legacy docs | `CURRENT_STATE.md` menyebut Phase 02 CLOSED_GREEN dengan test counts dan integrated PR | **Hanya laporan historis repo lama**, tidak membuktikan hasil baru di target repo |

**Kontradiksi status pada repository lama:** README `develop` mengatakan Phase 02 IN PROGRESS, sementara `docs/ai-context/CURRENT_STATE.md` menyatakan PHASE 02 CLOSED_GREEN/INTEGRATED dan Phase 03 NOT AUTHORIZED. **Rekonsiliasi harus memakai actual HEAD source, branch/PR dan menjalankan test ulang**. Jangan menyatakan Phase 03 existing atau OJS/CMS sudah ada hanya karena roadmap lama.

## 2. Bukti teknis fondasi lama, dapat dipakai kembali dengan audit

| Path/komponen lama | Temuan berdasarkan source | Keputusan reuse rekomendasi |
|---|---|---|
| `composer.json` | `laravel/framework: ^13.17`, `inertiajs/inertia-laravel: ^3`, Fortify, Spatie Permission 8.0, Activitylog 4.12, Pest/Larastan/Pint | **REUSE SETUP CONCEPT**; verifikasi versi locked & compatibility/security. Jangan copy vendor/token |
| `package.json` | Vue 3, Vue I18n, Inertia v3, Tailwind 4, shadcn-vue primitives, Lucide/Wayfinder/Vite Plus | **REUSE DEPENDENCY BASELINE selectively**, audit `package-lock` dan hosting Node/Vite sebelum adopt |
| `app/Models/User.php` + Auth/Fortify | Satu user, UUIDv7 groundwork, auth/verified email/reset foundation yang dilaporkan GREEN | **REUSE & EXTEND**; scoped multi-role and personal/institution data new |
| `app/Http/Middleware/ApplyPermissionTeamContext.php` | Session contains active `conference_edition_id`; sets Spatie permissions team context, cleans up at request end | **REUSE PATTERN after security audit**; institution and session-specific scope **tidak boleh diasumsikan hanya edition teams** |
| `app/Support/Authorization/ActiveConferenceEditionContext.php` | Validasi UUIDv7 dan ID event active context | **REUSE concept**, verify assignment on switch and never treat session hint as authorization |
| `database/migrations/2026_10_02_163556_create_permission_tables.php` | Permission/role schema ada dengan UUID-aware conventions (per legacy docs) | **REVIEW/ADAPT** to canonical PK decision; jangan otomatis jalankan migration legacy di DB dengan data lain |
| `database/migrations/2026_10_03_120000_create_phase02_registration_payment_tables.php` | Stored files, conference series/editions/venues/dates, registrations/packages/payment tables | **SPLIT & PORT intentionally**; some dimensions useful, billing schema must rebaseline |
| `app/Models/StoredFile.php`, filesystem config | Private-by-default model and disk boundary documented/tested in old repo | **REUSE pattern**, confirm public CMS media separate, private downloads resource-scoped |
| `resources/js/components/ui/*` | Established shadcn-vue/Radix-like primitives (alerts, dialogs, inputs, nav, etc.) | **REUSE COMPONENT PRIMITIVES** after package compatibility/a11y, no reason to clone basic components |
| `resources/js/components/LocaleSwitcher.vue`, `resources/js/app.ts` | Locale switcher and Vue i18n foundation with EN/ID/AR noted in old state | **REUSE & TEST RTL**, public/editor/CMS multilingual contents must be built |
| `resources/js/components/AppSidebar.vue`, `UserMenuContent.vue` | Basic shared nav/account patterns | **REUSE SHELL IDEAS**, but **add Switch Workspace & Superadmin read-only View as User**, not unrestricted impersonation |
| `tests/Feature/Foundation/*` | Tests for auth/permissions, UUIDv7, locale, file boundary, timezone, superadmin | **PORT high-value regression**; update to new domain and new schema |
| `tests/Feature/Phase02/*` | Tests for old payment/registration and participant browser flow | **REVIEW assumptions**, do not simply copy tests with old business outcome |
| `docs/metadata/OJS_CROSSREF_ADAPTER_CONTRACT_V1.md` | OJS mapping/version-aware adapter design | **REUSE DOCUMENTATION IDEA**, not an actual implemented OJS XML import |
| `docs/requirements/v1/V1_PUBLICATION_BASELINE.md` | Post-presentation publication flow described | **MAP TO NEW 18B-09/18C-01B**, not evidence that route exists in source |

**Source priority:** latest ICHES decision register > new TOR/operational agreement > old implementation contracts. Reuse only what **passes new acceptance tests**.

## 3. Specific incompatibilities: RED — jangan reuse as-is

### R-01 Payment Verification Source lama **tidak cukup**

Path: [`app/Actions/Payment/VerifyPaymentAction.php`](https://github.com/akhmadafnan/international-conference-platform/blob/develop/app/Actions/Payment/VerifyPaymentAction.php).

File lama melakukan `Payment::lockForUpdate()`, memastikan `PaymentStatus::SUBMITTED`, dan mengecek **proof version yang aktif tersedia**; lalu mengubah status pembayaran menjadi `VERIFIED`, menandai registration `CONFIRMED`, dan membuat `EventPass`. **Dalam file itu tidak ada pemeriksaan bahwa mutasi/credit bank nyata telah dicatat atau cocok**.

**Kebijakan baru:** Finance hanya boleh `Verified` setelah bukti transfer **dicocokkan dengan mutasi bank** dan jumlah/currency yang sesuai. Bank master satu rekening Superadmin-only, invoice lintas tujuan, empat status pembayaran, receipt setelah verified. Verification action harus mendukung **bank credit match evidence record**, concurrency, invoice liability, idempotent entitlements/receipts dan strict policy.

**Reuse only:** struktur DB transaction, row lock, audit trail patterns; **reimplement/extend business guards and persistence.** Old `registration CONFIRMED` sekaligus `EventPass` tidak cocok sebagai konsekuensi universal semua tujuan invoice baru.

### R-02 Registration lama **one registration per edition/user** bukan cukup bagi Co-Host/Paket

Path: [`app/Actions/Registration/CreateRegistrationIntentAction.php`](https://github.com/akhmadafnan/international-conference-platform/blob/develop/app/Actions/Registration/CreateRegistrationIntentAction.php).

Dalam flow lama terdapat guard `$membership->registration()->exists()` yang melempar exception jika sudah ada registration untuk edition; ia membuat satu participation package dan terkait entitlements. Pada ICHES baru **satu user bisa** sebagai author/CoHost rep/PIC sekaligus pemilik Paket Internasional, dan tagihan Co-Host **bukan milik individu**.

**Rekomendasi:** pisahkan peran/person membership, institutional Co-Host registration, personal package order dan invoice business-source. Pertahankan konsep edition/membership, tetapi **jangan memaksakan invoice tunggal bagi user atau memecah sponsor institusi menjadi invoice per rep**.

### R-03 Accepted abstract dan submission lama punya fase **Administrative Screening**

Kontrak repo lama pernah memuat tahap admin screening; ICHES baru **secara eksplisit menghilangkan** tahap pasca-submit itu. **Jangan menghidupkan gate lama di migration/status/UI/testing.** Valid Abstract Text+PDF langsung Reviewer Index/self-claim. Final ACCEPT published oleh Committee membuka Full Paper tanpa payment gate.

### R-04 Old Publication dan OJS source **belum ada aplikasi final**

Walaupun dokumen lama memuat metadata canonical, post-event review, OJS adapter, versi OJS dan publication archive, git tree `develop` pada audit ini hanya menunjukkan foundation/registration-payment implementation beserta tes. **Jangan mencatat OJS Export/Full Paper Revision sebagai code yang sudah tersedia.** Jadikan metadata design reference, implementasi baru bertahap.

### R-05 Event clock dan countdown

Schema lama memiliki `conference_editions.timezone`, `starts_at`, `ends_at`, sehingga **berguna** untuk countdown. Tetapi main ICHES event date **5 Januari 2027**, associated day **6 Januari 2027**, dan **official start time belum ada**. Jangan seed jam 00:00 atau menyatakan timer aktif sebelum pengaturan resmi. CMS mengubah label/layout/section, sedangkan target event instant berasal dari Event Master.

### R-06 Privileged roles tidak boleh membuat “Support View” full impersonation

Admin `View as User` versi ICHES adalah **Superadmin READ-ONLY** untuk dukungan; actor asli tetap tertaut, tidak boleh mengubah pembayaran/submission/academic decision/file. Jika code lama punya role switching context, itu **bukan** alasan memakai `Auth::login(target)` atau mengabaikan Policy.

## 4. Kandidat reuse: rekomendasi keputusan Keep / Adapt / Replace / Not now

| Domain/fitur | Aksi readiness | Bukti minimal sebelum merge ke repo baru |
|---|---|---|
| Auth/Laravel skeleton | **KEEP/ADAPT** | Fresh install, auth feature tests, secure session/reset/verify |
| UUIDv7 | **KEEP IF APPROVED** | All morph/FKs, seed & duplicate constraints in MySQL |
| Role/Spatie edition teams | **ADAPT** | event-scope + institution/paper/session explicit policies & revoked assignment tests |
| Vue shadcn-vue primitives | **KEEP/ADAPT** | Typecheck, build, RTL keyboard semantics |
| i18n/RTL | **KEEP/EXTEND** | Content EN/ID/AR, forms/sidebar/dialogs, numeric fields RTL QA |
| private storage abstraction | **KEEP/ADAPT** | Proof/manuscript private, public CMS media permission and signed downloads |
| Event master/important_dates | **KEEP/ADAPT** | Countdown correct timezone, Admin edit, no fake start hour |
| Co-Host institutional invoice/seat | **NEW DOMAIN** | One invoice institutional, 5 seats after VERIFIED, 5 sponsored papers, no double Conference invoice |
| Paket personal 4 activities | **NEW/REMODEL** | One personal invoice and four entitlements, no entitlement prior verified |
| Bank credit matching | **REPLACE old verify contract** | Explicit bank transaction evidence, idempotent writes/receipts |
| Abstract reviewer self-claim | **NEW POLICY/FLOW** | No screening, self-claim all tracks, COI check, Committee publish |
| Full Paper OJS-like & revision | **NEW DOMAIN** | Canonical metadata & files, Published ACCEPT gate, revision history, final decision |
| CMS pages/sections/media | **NEW DOMAIN** | Create/edit/reorder/preview/publish/revert, Admin UI, no code edit routine |
| Countdown D/H/M/S | **NEW UI on event master** | Four-unit real timer, no fictional time, boundary/RTL test |
| Publication OJS/Journal | **NEW ADAPTER** | Source OJS target profile, XML staged test, no fake acceptance |
| Scanner offline | **NOT NOW until scoped decision** | Online server/entitlement contracts first, offline sync separately |
| PDF invoice/certificate | **ADAPT underlying PDF interface** | Document formats/rights/source status and queue afterCommit tested |

## 5. Proposed branch/migration workflow — no code changes now

~~~text
SOURCE (read-only during readiness)
  akhmadafnan/international-conference-platform / develop
    inspect/app-specific code, migrations, tests
      ↓ compatibility matrix & design rebaseline
TARGET (canonical proposed)
  akhmadafnan/iches-unisya / main
    existing docs, decisions and Agent Skill
      ↓ after new explicit GO CODING only
  feat/s0-foundation-reuse
    create real Laravel app skeleton or port reviewed foundation as coherent commit
    use cherry-pick/file-by-file provenance when helpful
    do NOT copy old .env, secrets, participant data or stale schema wholesale
    run composer/npm/migrate/tests
      ↓ review & proof
  PR → main, only after exit gate green
      ↓
  feature branches for Website CMS/Countdown, Abstract, Finance, etc.
~~~

**Practical risk:** Git cherry-picking old commits across unrelated repos often brings unrelated file history/conflicts. A **clean port of vetted source files with source commit/provenance notes** may be safer than blindly merging histories. The method is decided only after source branch state and target readiness verified. **No permanent fork or secret migration** assumed.

**Schema migration strategy:**
- Create baseline migrations for identity/auth/edition/storage with UUID type if approved.
- Add event date/time and CMS page/revision support early enough for public site.
- Add Abstract/author/file/review models in their vertical slice.
- Add institutional CoHost/order/invoice bank-credit matching, then later metadata/assessment/publication.
- Maintain forward and rollback checks with test database. **Never run old production migration on new live DB without dry run**.
- Do not assume existing old participants/data should be copied; there is **no data-migration authorization** in this work.

## 6. Safety gates for selective reuse

1. **Software supply chain:** `composer.lock` and `package-lock` read and audited before installs; PHP/Node package versions and CVE scan; don't install auto-updates into production unreviewed.
2. **Secrets:** no old `.env`, `APP_KEY`, DB dump, payment proof, contact, QR token, customer credentials or real participants in new repository.
3. **Migration:** fresh MySQL staging migrate/fresh rollback, constraints & idempotency, foreign keys and polymorphic UUID tested.
4. **Permissions:** verified Superadmin-only bank settings and assigned scoped PIC/Reviewer/Moderator operations; no unauthorized cross-owner access.
5. **Finance:** original proof is not proof of funds; bank mutation evidence and audit required, no shortcut verify.
6. **Frontend:** original Academic Editorial, mandatory CMS and countdown, no AICIS cloning or hardcoded event start time.
7. **Test evidence:** legacy GREEN docs are historical, rerun `php artisan test`, `vendor/bin/pint --test`, static checks, npm typecheck/build and selected Playwright/browser tests after code port; record actual results.
8. **Rollback:** can revert a feature branch commit without destroying data or shared history, migration backup before any production modifications.
9. **Scope check:** no invented bank account, journal, speaker, event hour, venue, or financial policy.
10. **Owner review:** no actual code port/merge/deploy before **explicit new GO CODING**.

## 7. Audit conclusion & open questions

**Strongest recommended reuse:** Laravel/Vue skeleton, auth/permission base, private files, edition/timezone, i18n RTL shell, standard shadcn-vue components, technical test setup. **Highest-risk reuse:** old registration/payment/business logic; old manual payment verify **cannot be accepted without real bank-mutation matching**. **Entirely new features:** dynamic Website CMS, countdown UI, new Abstract flow, OJS-like Full Paper, post-event revision, journal selection/export and Co-Host institutional billing.

**Still requiring decisions:** target repo, MySQL/UUIDv7, hosting PHP/Node readiness, email/password vs Magic Link, exact event start hour, official one bank, scope for scanner offline, publication OJS target and real journal candidates. These don't all block starting a small public/CMS/abstract slice, but the infrastructure defaults should be approved before writing migrations.

**Status:** Audit remote code and documents completed for planning purposes. **No source copied; no tests run; no implementation authorized.**

---

[18C-05 Readiness](18C-05-DRAFT-IMPLEMENTATION-READINESS-AND-DECISION-GATES.md) · [18C-05B MVP Gates](18C-05B-DRAFT-MVP-SLICES-EXIT-GATES-AND-UAT.md) · [Repo Old](https://github.com/akhmadafnan/international-conference-platform/tree/develop) · [New Target](https://github.com/akhmadafnan/iches-unisya).
