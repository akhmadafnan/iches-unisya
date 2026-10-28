# 18C-04A — DRAFT Screen Inventory, Component Contracts & 60 Frontend UAT ICHES 2027

> **28 Oktober 2026 · LAMPIRAN TEKNIS TAHAP 18C-04 · DRAFT, BUKAN KODE/FINAL.**  
> Semua route, tab, nama component, wireframe dan pesan adalah rekomendasi implementasi. Server/Policies dan keputusan produk tetap menjadi sumber kebenaran. **Belum ada Vue components, screenshot aplikasi, deploy, atau UAT yang dieksekusi.**
>
> [18C-04 Main Frontend UX](18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md) · [18C-03B Switch Workspace/Support View](18C-03B-DRAFT-SWITCH-ROLE-WORKSPACE-AND-USER.md) · [18C-03A Permissions](18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md) · [18B-09 Full Paper OJS-like](18B-09-DRAFT-FULLPAPER-OJS-LIKE-POSTPRESENTATION-PUBLICATION.md) · [18B-08 Invoice/Bukti Lunas/Notifikasi](18B-08-CRUD-INVOICE-BUKTI-LUNAS-NOTIFIKASI-DAN-UAT.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Inventory layar: 55 kandidat route/page screens (DRAFT)

**Catatan:** angka 55 adalah layar/route kandidat, **bukan wajib 55 file Vue**. Banyak halaman dapat berbagi komponen dan route detail dengan tab. Prefix route **ilustrasi**, belum kontrak API. Satu `event` context server berlaku, URI tidak boleh memberi akses lintas scope.

### Public & Auth — P-01 sampai P-13

| ID | Layar | URL konseptual | Isi/CTA utama |
|---|---|---|---|
| P-01 | Public Home/Coming Soon | `/` | Hero CMS, countdown valid, About, Journey, speakers, partners; no carousel |
| P-02 | About & Theme | `/about` | Konferensi/tema, identitas ICHES |
| P-03 | Call for Papers & Tracks | `/call-for-papers` | 8 tracks/dasar TOR, CFP, Submit Abstract jika open |
| P-04 | Important Dates | `/important-dates` | Timeline event/admin settings dan WIB |
| P-05 | Programs | `/programs` | Conference, MoU session, Upgrading, PKM, destinations |
| P-06 | Speakers/Committee | `/people` | Hanya narasumber/komite yang telah disahkan CMS |
| P-07 | Partners/Co-Host | `/partners` | Logo/identitas publik hanya ketika izin publikasi |
| P-08 | Fees & Payment Guide | `/fees` | Tarif sah, manual transfer, biaya WNA, tanpa bank palsu |
| P-09 | Publication & Proceedings | `/publication` | Prosiding/jurnal pilihan, tanpa janji diterima jurnal |
| P-10 | Contact/FAQ/Policies | `/help` | FAQ/policies + kontak resmi dari CMS |
| P-11 | Certificate Public Verification | `/verify/certificates/{token}` | Status verifikasi dan metadata minimal |
| P-12 | Login | `/login` | Metode login yang disetujui kelak; session/CSRF |
| P-13 | Register/Reset/Verify Email | `/register`, `/forgot-password` ... | Auth flow; bisa komponen/route berbeda saat implementasi |

### Personal — M-01 sampai M-10

| ID | Layar | URL konseptual | Keadaan utama |
|---|---|---|---|
| M-01 | Dashboard Overview | `/dashboard` | My paper/payments/package/check-ins, notifications; empty states |
| M-02 | My Papers | `/dashboard/papers` | Filter status, New Abstract, own records |
| M-03 | Abstract Form | `/dashboard/papers/new` atau `/{paper}/edit` | Text+PDF wajib pada Submit; Draft boleh belum lengkap |
| M-04 | Paper Detail/Decision | `/dashboard/papers/{paper}` | Accepted published vs reviewer rec/private decision |
| M-05 | Full Paper OJS-like Wizard | `/dashboard/papers/{paper}/full-paper` | Checklist, file v1, metadata prefilled, confirm, history |
| M-06 | Revision Response | `/dashboard/papers/{paper}/revision` | Revisi v2+response hanya ketika Revision Required resmi |
| M-07 | My Payments/Invoice Detail | `/dashboard/payments` dan `/{invoice}` | Personal invoices, upload proof, receipt only verified |
| M-08 | International Program Package | `/dashboard/international-package` | Satu Rp1 juta, empat hak only after verified |
| M-09 | My QR/Attendance/Certificates | `/dashboard/my-access` | QR personal, check-ins dan sertifikat masing-masing |
| M-10 | Profile & Notifications | `/dashboard/profile` / `/notifications` | Own identity, account settings, notification statuses |

### Institution Workspace — I-01 sampai I-05

| ID | Layar | URL konseptual | Gate |
|---|---|---|---|
| I-01 | Institution Overview | `/institution/cohost` | PIC scoped, status institutional payment |
| I-02 | Co-Host Institutional Invoice | `/institution/cohost/payment` | **Satu** Rp3 juta, upload bukti manual, Finance Verified |
| I-03 | Manage 5 Representatives | `/institution/cohost/representatives` | **Locked sampai invoice VERIFIED** |
| I-04 | Sponsored Papers Overview | `/institution/cohost/papers` | Max 5 paper (one member/one sponsored paper), limited data |
| I-05 | Co-Host Profile & Recognition | `/institution/cohost/profile` | Edit data berizin, logo melalui approval publikasi Admin |

### Backoffice / Staff — B-01 sampai B-23

| ID | Layar | URL konseptual | Pemilik izin utama |
|---|---|---|---|
| B-01 | Event Overview | `/backoffice` | Admin scope |
| B-02 | CMS & Public Content | `/backoffice/content` | Admin/Content Editor |
| B-03 | Event/Tracks/Submission Settings | `/backoffice/conference/settings` | Admin |
| B-04 | All Submissions & Paper Detail | `/backoffice/conference/papers` | Academic/Admin sesuai izin |
| B-05 | Reviewer Index/Claim | `/backoffice/reviewer/index` | Eligible Reviewer |
| B-06 | My Reviews | `/backoffice/reviewer/my-reviews` | Reviewer own claims |
| B-07 | Academic Decisions | `/backoffice/academic/decisions` | Academic Committee |
| B-08 | Session Schedule/Moderator | `/backoffice/sessions` | Admin/Moderator scoped |
| B-09 | Presentation Assessments | `/backoffice/presentation-assessments` | Assessor assigned |
| B-10 | Revision Requests | `/backoffice/publication/revisions` | Committee/Publication |
| B-11 | Final ACC & Readiness | `/backoffice/publication/eligibility` | Publication Committee |
| B-12 | Proceedings/Journal Outlets | `/backoffice/publication/outlets` | Publication Team/Admin |
| B-13 | OJS Export Batches & Handoff | `/backoffice/publication/export` | Publication Team assigned |
| B-14 | Co-Host Registrations | `/backoffice/cohosts` | Admin |
| B-15 | Finance Overview/Invoices | `/backoffice/finance/invoices` | Finance |
| B-16 | Payment Verification | `/backoffice/finance/verification` | Finance |
| B-17 | Finance Reports | `/backoffice/finance/reports` | Finance |
| B-18 | Bank Settings | `/backoffice/settings/bank` | **Superadmin-only** |
| B-19 | Programs/Package/Entitlements | `/backoffice/programs` | Admin/Program Coordinator |
| B-20 | QR/Services/Check-in Operations | `/backoffice/operations` | Service Coordinator |
| B-21 | Certificate Batch & Verify Mgmt | `/backoffice/certificates` | Certificate Officer |
| B-22 | Users, Scoped Roles & View as User | `/backoffice/users` | Superadmin / Admin authorized (support view Superadmin default) |
| B-23 | Audit/Template/Notification Settings | `/backoffice/settings` | Superadmin/Admin authorized |

### Scanner & Support — X-01 sampai X-04

| ID | Layar | URL konseptual | Kegunaan |
|---|---|---|---|
| X-01 | Scanner Assigned Counter | `/scanner` | Mobile-first, service scoped, scan/lookup |
| X-02 | Scan Result/Service Confirmation | `/scanner/result` | Entitlement check, already-confirmed, deny and success |
| X-03 | Support View as User (read-only) | `/backoffice/support-view/{context}` | Admin real_actor + target, persistent banner & Exit |
| X-04 | Switch Workspace Account Menu | global authenticated | Dropdown/Sheet, assigned roles and scopes only |

**Inventaris:** 13 Public/Auth + 10 Personal + 5 Institution + 23 Backoffice + 4 Scanner/Support = **55 layar konsep**. Bisa digabung menjadi tab/route segments dalam implementasi. **Mode Support View tidak berarti URL full-impersonation**.

## 2. Component contracts dan definisi sumber data

| Shared component (usulan) | Props/data yang diterima | Behaviour/perlindungan |
|---|---|---|
| `AccountMenu` | `authSummary`, `allowedWorkspaces`, `activeWorkspace` | Avatar topbar, Switch Role tanpa logout, Profile, Logout |
| `WorkspaceSwitcher` | Array scoped options dari server | Ganti hint konteks, **tidak mengubah role permissions/user id** |
| `SupportViewBanner` | `real_actor`, `safe_target_label`, `expires_at`, `exit_url` | Persisten, read-only, Exit, never stores target auth credentials |
| `LanguageSwitcher` | `locale`, `supportedLocales=['en','id','ar']` | Switch label, document lang+dir, preserve navigation as feasible |
| `ResponsiveSidebar` | Authorized nav groups dan event context | Tidak membuat menu sensitive untuk role tanpa izin; overlay mobile |
| `DataTable` | Server paginated records, filters, permitted row actions | Sorting/filter query and no raw confidential fields; avoid loading all invoices client-side |
| `StatusBadge` | `domain`, `state`, `label` dari server | Domain-specific: finance vs academic vs publication; bukan warna saja |
| `PrivateFilePicker` | Allowed MIME/size/draft file role | Browser validation informatif; server tetap memeriksa jenis, kepemilikan dan gate |
| `OjsMetadataEditor` | Canonical title/locale/authors/affiliations/keywords/references prefilled | Reorder author, optional ORCID, mononim, snapshot/version history |
| `FileVersionHistory` | paper_file ids, kind, versions, permission | Original/revised v1/v2 tidak overwrite; private download berizin |
| `InvoiceCard` | Own invoice summary and public bank payment instructions | Copy Bank, upload proof, receipt only Verified; no personal invoice in PIC |
| `PaymentVerificationDrawer` | Invoice, proofs, matched bank credits, Finance decision | Verify button hanya jika authorized and bank match; reason required for correction |
| `PaperDecisionPanel` | Published decision, author-facing comments, relevant state | Reviewer recommendation/private decision hidden |
| `PresentationAssessmentForm` | Assigned session/paper, configured rubric, file/notes | Assessor recommendation only, not Final ACC |
| `PublicationReadinessChecklist` | Snapshot, missing metadata, outlet profile readiness | Selected for journal ≠ accepted by journal; XML staging test flag separate |
| `QrScannerPanel` | Assigned service/slot, minimal lookup result, permission | No raw financial/review data; duplicate scan protected server |
| `CertificatePreview` | Eligible author and approved template | Batch issuance under authorized explicit action; public verify minimal |

**TypeScript proposal:** `WorkspaceOption { key, label, scopeType, scopeId, landingUrl }`, `UserSummary { id, displayName, avatarUrl? }`, `PaymentStatus = 'UNPAID' | 'PENDING_VERIFICATION' | 'CORRECTION_REQUIRED' | 'VERIFIED'`; exact naming must align with backend before coding. Do not send raw models wholesale in Inertia shared props.

## 3. Field/group mapping — Abstract Submission tetap ringan

```text
ABSTRACT SUBMISSION (NEW ICHES FLOW)
  Paper Title
  Conference Track
  Abstract Text (required on Submit)
  Abstract PDF (required on Submit)
  Author list + corresponding author / metadata already approved
  [Save Draft]         [Submit Abstract]
  On Submit Valid → Submitted → Reviewer Index directly
  NOT PRESENT: Administrative Screening stage, Admin PASS gate, Finance Pay-before-submit
```

**Form detail** seperti validasi jumlah kata, author max, file limit dan template harus mengikuti keputusan yang tertulis; yang belum ada **configurable/terbuka**, tidak diciptakan. File upload menggunakan progress dan error, memberi kesempatan Save Draft; validasi penuh dilakukan pada Submit.

## 4. Field/group mapping — Full Paper OJS-like, bukan clone seluruh editorial OJS

| Step | Field/aksi | Default status untuk didesain |
|---|---|---|
| 1 **Checklist** | Originality/declaration, author acknowledgment, policy acknowledgment jika ditetapkan | Checklist sesuai kebijakan panitia/outlet, bukan hardcode fiktif |
| 2 **Files** | Manuscript v1 (format menunggu persetujuan), supplementary optional, file role, upload progress | Full Paper unlocked setelah published ACCEPT **tanpa finance check** |
| 3 **Metadata** | Primary locale; Title/subtitle; Abstract; Keywords per item/locale; Author order; Given/family/mononim; Email; Country; ORCID optional; multi-affiliation; References raw+optional DOI | Prefill dari Abstract; public metadata berbeda dari private contact |
| 4 **Review** | Preview metadata & files, validation summary, confirmation | Submit hanya jika server requirements terpenuhi |
| 5 **History** | Manuscript v1, Revised Article v2..., review comments, committee status, Final selected file | No Revision tidak memaksa upload ulang |
| Revision (conditional) | Author response/comment matrix + revised manuscript v2 | Hanya jika Committee sudah menerbitkan Revision Required |
| Final ACC | Read-only view of Committee decision and publication eligibility | Author tidak bisa menerbitkan Final ACC sendiri |

**Metadata/OJS:** gunakan canonical internal model; adapter menentukan struktur Native XML menurut versi OJS tujuan. **Jangan** menampilkan tombol “Import ke semua OJS otomatis” sebelum target+XSD/staging uji berhasil. Selected Journal tidak sama dengan journal acceptance.

## 5. Tabel dan detail UI yang perlu dicontohkan saat implementasi

### Finance Payment Verification (desktop)

~~~text
Finance > Payment Verification
[All] [Pending] [Correction Required] [Verified]
Search invoice / payer            [Currency IDR|USD] [Purpose] [Date]

Code        Payer        Purpose      Currency  Amount    Status
...         Institution  Co-Host      IDR       3,000,000 Pending
...         Personal     Package      IDR       1,000,000 Correction
...         Presenter    Regular      USD       40        Verified

[Open]
 Invoice header / owner / purpose / amount
 Evidence: Proof version history | bank credit match
 [VERIFY]   [REQUEST CORRECTION + reason]
 Verified receipt available only after verified
~~~

### Post-Presentation Review (desktop and tablet)

~~~text
Assessor > Assigned Paper / Session
[Presented confirmation from Moderator]  [Paper ID]
Original Full Paper v1 / latest authorized version
Quality & Presentation Rubric [configurable]
Comments to Author
Internal Comments
Recommendation:
  ( ) No Revision ( ) Minor ( ) Major ( ) Not Recommended
[Submit Recommendation] — cannot Final ACC
Committee inbox:
  [Release No Revision] or [Request Revision] or [Not Recommended]
  Later, if eligible: [Final ACC]
~~~

### Superadmin View as User

~~~text
Users & Roles → User Detail → [View as User]
Dialog: reason is required → START
Persistent:
  Viewing as [target] | Admin: [real actor] | READ-ONLY | [Exit]
Allowed: safe dashboard preview & navigation labels
Denied: submit/accept/revise/verify/issue/scan/export/bank edit,
        raw QR, proof, confidential reviewer notes, private file download
~~~

## 6. Shared state & lifecycle rules for Inertia (DRAFT)

| UI situation | Form state & response handling | Security/business result |
|---|---|---|
| Save Draft | Preserve only own input, server returns new version ID | No reviewer index until Submitted |
| Submit Abstract | Disable submit only while in flight, show field errors | Submitted only after backend success |
| Publish Accept | Full Paper tab appears when server says published ACCEPT | Invoice not checked as gate |
| Upload proof | Status changes to Pending after valid storage; show verification note | No entitlement yet |
| Finance Verified | Refresh invoice status/receipt/rights via subsequent Inertia visit | Co-Host/Package entitlement active exactly once |
| Workspace switch | Server-listed allowed workspace; preserve selected event scope; route to default page | `user_id` unchanged, no role gain |
| Support view | Distinct UI shell/context, every mutation rejected server | Admin stays real actor; only safe projection |
| Reviewer claim | Conflict/status updated after atomic claim; reflect 409/422 or appropriate error | Two simultaneous claims never oversubscribe |
| Full Paper revision | Versioned files; show previous version and current request | Never overwrite v1 silently |
| OJS export | Show READY/WARNING/BLOCKED and validated profile | No claim ready-to-import before staging UAT |
| Scanner result | Show entitled/denied/already confirmed, avoid private data | Server check prevents duplicate fulfillment |

## 7. Empty/loading/error/success states yang wajib dirancang

| Domain | Empty | Error | Success |
|---|---|---|---|
| Public | Speakers belum diumumkan | CMS unavailable (fallback text) | Sections ready |
| Personal papers | Belum ada paper; new Abstract CTA ketika open | Submit invalid text/PDF | Submission ID ditampilkan |
| Co-Host | Belum mendaftar institusi | Belum Verified (lock info, **bukan error server**) | 5 seats available setelah verified |
| Package | Belum beli Paket | Pending verification, bukan paid | 4 entitlements jika Verified |
| Finance | No invoices in selected filter | Bank credit mismatch, correction reason | Verified with receipt |
| Reviewer | No eligible Abstract | COI/claim conflict | Own claim confirmed |
| Full Paper | No Full Paper yet / locked by decision | Metadata/file validation errors | v1 submitted |
| Revision | No revision requested | Wrong file/paper/permission | v2 submitted |
| Publication | Outlet belum dikonfirmasi | XML version/profile/readiness blocked | Export prepared + staging tested (actual evidence) |
| QR | No assigned counter | No entitlement/already confirmed/sync pending | Fulfillment confirmed |
| Certificate | No Presented eligible papers | Missing permission / no batch | Issued with verify token |
| Switch workspace | Only personal role | Role revoked/invalid scope | Changed context, same account |
| Support view | No target selected | View expired/permission denied | Read-only banner + exit |

**Pesan dapat diterjemahkan** EN/ID/AR setelah copy final ditinjau. Jangan mengandalkan warna tunggal untuk status atau mengubah `Unpaid` menjadi `Verified` hanya karena upload proof berhasil.

## 8. 60 UAT frontend (RENCANA, BELUM DIEKSEKUSI)

| ID | Case | Expected UI + server contract |
|---|---|---|
| UI-T01 | Public landing mode Coming Soon | Hero dan CTA benar sesuai CMS, no carousel |
| UI-T02 | Admin mengubah status CFP open | Public CTA Submit muncul; backend mengecek deadline |
| UI-T03 | Speaker belum terbit | Tidak ada speaker fiktif |
| UI-T04 | Change EN→AR | `html[dir=rtl][lang=ar]`, labels/layout responsif |
| UI-T05 | Arabic sidebar/pagination/dropdown | RTL logical order, navigasi dan portal content tidak rusak |
| UI-T06 | EN/ID mode | LTR dan teks resmi sesuai locale |
| UI-T07 | Mobile header/keyboard navigation | Drawer accessible, focus visible |
| UI-T08 | Login error | Pesan aman, tidak bocorkan apakah email terdaftar |
| UI-T09 | User tanpa role staff login | Hanya Personal Dashboard |
| UI-T10 | User punya Reviewer+PIC roles | Account Menu menawarkan kedua scoped workspaces |
| UI-T11 | Switch Personal→Reviewer | Tanpa logout, `user_id` tetap sama |
| UI-T12 | Switch PIC A→PIC B secara sah | Header menunjukkan institusi yang benar dan data scoped |
| UI-T13 | Workspace PIC A dengan Invoice B di URL | Backend tolak, UI permission state |
| UI-T14 | Role Reviewer direvoke saat tab terbuka | Request ditolak dan fallback sesuai izin terbaru |
| UI-T15 | Superadmin memulai View as User dengan reason | Banner read-only, real actor & target terlihat |
| UI-T16 | Event Admin tanpa grant mencoba support view | Tidak ada menu / request ditolak |
| UI-T17 | Support View mencoba klik Finance Verify | Tidak ada action, server tolak mutasi |
| UI-T18 | Support View mengunduh bukti privat | Ditolak/redacted |
| UI-T19 | Support View berakhir/Exit | Kembali ke sesi Admin asli |
| UI-T20 | Abstract baru Save Draft | Incomplete boleh tersimpan, belum masuk index reviewer |
| UI-T21 | Abstract Submit tanpa text | Inline error, tidak Submitted |
| UI-T22 | Abstract Submit tanpa PDF | Inline error, tidak Submitted |
| UI-T23 | Abstract valid Submit | Confirmation + submission ID, Reviewer Index langsung |
| UI-T24 | Reviewer lihat index lintas tracks | Paper Submitted eligible terlihat |
| UI-T25 | Reviewer COI pada coauthor | Claim button unavailable/server deny |
| UI-T26 | Dua Reviewer claim bersamaan | Salah satunya menerima conflict jika slot sudah penuh |
| UI-T27 | Reviewer recommendation ACCEPT saja | Full Paper masih Locked |
| UI-T28 | Komite publish ACCEPT reguler | Full Paper unlock, invoice Presenter sesuai tarif |
| UI-T29 | Presenter reguler ACCEPT tetapi Unpaid | Full Paper wizard tetap accessible |
| UI-T30 | Co-Host paper ACCEPT | Tidak muncul presenter invoice kedua |
| UI-T31 | Full Paper dibuka | Metadata Abstract prefill, tidak mengetik ulang |
| UI-T32 | Full Paper author ORCID kosong | Tidak blocked bila optional |
| UI-T33 | Full Paper coauthor mononim/tanpa akun | Metadata valid, urutan penulis terjaga |
| UI-T34 | Full Paper format tidak sah | Inline file validation dan state unchanged |
| UI-T35 | Full Paper upload v1 | File history menampilkan versi v1 |
| UI-T36 | Moderator mengonfirmasi Presented | Paper state disajikan, bukan hasil QR otomatis |
| UI-T37 | Peserta scan QR tetapi belum Presented | UI tidak menampilkan paper Presented palsu |
| UI-T38 | Assessor No Revision | Final Check tersedia, tidak perlu upload ulang |
| UI-T39 | Assessor Minor/Major recommendation | Belum auto-revision sebelum keputusan Komite |
| UI-T40 | Komite Revision Required | Author melihat komentar dan uploader v2+response |
| UI-T41 | Author submit revised v2 | Versi v1 dan v2 tercatat tanpa overwrite |
| UI-T42 | Komite Final ACC | Publication Queue eligible, bukan Published |
| UI-T43 | Publication pilih jurnal kandidat | State Selected, bukan Accepted by Journal |
| UI-T44 | OJS profile belum ada | Handoff tidak dilabel Ready-to-import |
| UI-T45 | OJS XML gagal staging import | Status error dan perbaikan dipresentasikan |
| UI-T46 | Co-Host invoice Unpaid | Manage Seats Locked, invoice Rp3 juta satu |
| UI-T47 | Co-Host proof uploaded Pending | Manage Seats masih Locked |
| UI-T48 | Co-Host Finance Verified | Lima seat aktif, tidak ada lima tagihan Conference personal |
| UI-T49 | PIC membuka invoice anggota Paket personal | Ditolak |
| UI-T50 | User membeli Paket Rp1 juta | Satu personal invoice empat kegiatan, tidak empat invoice |
| UI-T51 | Paket hanya upload bukti | Empat hak belum aktif |
| UI-T52 | Paket Finance Verified | Empat activity entitlements aktif |
| UI-T53 | Finance Correction Required | Reason terlihat dan upload ulang pada invoice sama |
| UI-T54 | Payment Receipt click sebelum Verified | Tidak tersedia; Invoice PDF tetap bisa diunduh |
| UI-T55 | Receipt setelah Verified | Dokumen Bukti Lunas tersedia, stabil saat download ulang |
| UI-T56 | WNA presenter onsite | USD 40 berdasarkan kategori TOR, tanpa auto FX |
| UI-T57 | Mobile Scanner tidak punya assignment | Tidak boleh melihat/scan pos lain |
| UI-T58 | Dua scan konsumsi slot sama | UI “Already Confirmed”; tidak double-fulfill |
| UI-T59 | Paper Presented multiple coauthors | Semua penulis eligible; sertifikat tetap perlu batch issue |
| UI-T60 | Public certificate verify | Token tampil valid/invalid dengan informasi minimal, tanpa email/bank/QR |

**Matriks sebelumnya tetap berlaku:** 52 Security UAT, 40 Backend UAT, 22 Switch UAT, 30 Full Paper workflow dan 24 Publication-DB UAT. **Jangan menjumlahkan semua sebagai test yang telah PASS**; ini baru acceptance criteria lintas dokumen.

## 9. Prioritas layar untuk implementasi setelah persetujuan (bukan tanggal sprint)

| Phase | Screen groups | Catatan dependency |
|---|---|---|
| F0 Design foundation | Public/Layout/Auth/Account Menu/Sidebar/i18n/RTL/states | Pilih library versions dan server props auth dahulu |
| F1 Conference Abstract | M-01..M-04, B-03..B-07 | Gate reviewer index dan published ACCEPT |
| F2 Co-Host & Finance | I-01..I-05, M-07..M-08, B-14..B-18 | VERIFIED gates sesuai aturan bisnis |
| F3 Full Paper OJS-like | M-05, M-06, B-09..B-11 | Prefill, versioning, post-presentation decisions |
| F4 Publication & OJS | B-12, B-13, P-09 | Outlet mapping, snapshot, tested adapter |
| F5 Operations/Certificates | M-09, B-08, B-20..B-21, X-01..X-02 | Assignment scoped, Presented dan manual certificate issue |
| F6 Support View & polish | B-22, X-03..X-04, comprehensive UAT | **Workspace Switcher harus tersedia sejak F0**, support preview boleh dikembangkan setelah User Management |

**Catatan:** jika user menghendaki support-view menjadi MVP hari pertama, F6 dapat dinaikkan sesudah User Management; **semua permission tetap diberlakukan dari fondasi F0**, bukan belakangan.

## 10. Keputusan review yang belum ditutup

| Key | Rekomendasi UI yang perlu ditinjau | Status |
|---|---|---|
| UIA-01 | 55 layar konsep, group menjadi tab/detail agar navigasi tidak panjang | DRAFT |
| UIA-02 | Komponen reused lintas Personal/Institution/Backoffice, satu sistem desain | DRAFT |
| UIA-03 | Full Paper wizard OJS-like 5 steps, prefilled data abstract | ARAH DITERIMA, implementasi DRAFT |
| UIA-04 | Co-Host 1 invoice / 5 seats after Verified dan Paket 1 invoice / 4 rights | BUSINESS FINAL, UI DRAFT |
| UIA-05 | Switch Workspace global; Support View Superadmin read-only, tidak full impersonation | ARAH DITERIMA, desain DRAFT |
| UIA-06 | Arabic RTL/EN/ID, text copy/legal/help content perlu proofreading | DRAFT |
| UIA-07 | Mobile scanner-first, offline sync ditunda sesuai keputusan infrastruktur | DRAFT |
| UIA-08 | Setelah frontend review → 18C-05 Implementation Readiness & MVP plan → explicit GO coding | DRAFT |

**Dokumen ini melengkapi 18C-04, bukan mengganti keputusan Finance, Abstract, OJS, Certificate, dan Switch Workspace.** Lakukan revisi file spesifik yang diinginkan pengguna sebelum mengunci untuk coding.

---

[18C-04 Frontend Architecture](18C-04-DRAFT-FRONTEND-DASHBOARD-ARCHITECTURE-UX.md) · [18C-03B Switch Workspace](18C-03B-DRAFT-SWITCH-ROLE-WORKSPACE-AND-USER.md) · [README](../README.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).
