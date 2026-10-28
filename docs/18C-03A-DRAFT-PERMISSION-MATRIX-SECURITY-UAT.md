# 18C-03A — DRAFT Permission Matrix, Route Guards & Security UAT ICHES 2027

> **ADDENDUM 28 OKTOBER (MENUNGGU REVIEW):** peran multi-role perlu **Switch Workspace** tanpa ganti User ID dan opsional **Admin View as User (READ-ONLY)** dengan otorisasi khusus. Catatan 22 test SW-01..SW-22 dan guard per-route ada pada [18C-03B](18C-03B-DRAFT-SWITCH-ROLE-WORKSPACE-AND-USER.md). **52 test SEC-T01..SEC-T52 di bawah tetap berlaku dan belum diuji**.

> **27 Oktober 2026 — LAMPIRAN REVIEW 18C-03. TIDAK FINAL, TIDAK DIEKSEKUSI.** Matriks ini adalah usulan role-permission/backend guards, bukan daftar izin yang sudah di-seed ke database.
>
> [18C-03 Main Security Architecture](18C-03-DRAFT-AUTHENTICATION-RBAC-SECURITY-ARCHITECTURE.md) · [18A Role Blueprint](18A-ROLE-PERMISSION-DAN-NAVIGASI-BACKOFFICE.md) · [18C-02 Backend](18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md) · [Co-Host](18B-04-CRUD-COHOST-INSTITUSI-LIMA-PAPER.md) · [Finance](18B-06-CRUD-FINANCE-INVOICE-PEMBAYARAN-VERIFIKASI.md) · [Publication](18B-09-DRAFT-FULLPAPER-OJS-LIKE-POSTPRESENTATION-PUBLICATION.md) · [Scanner](18B-01-CRUD-ADMIN-SCANNER-DAN-SINKRONISASI.md).

## 1. Notasi permission dan scope

- **OWN**: login user sama dengan owner/pembayar/submitting author yang berhak.
- **INSTITUTION**: role PIC di-assign ke Co-Host/institusi pada event yang sama, **bukan semua institusi yang memakai email kampus sama**.
- **ASSIGNED**: paper yang di-claim, session moderator, paper yang dinilai, counter atau outlet yang didelegasikan.
- **EVENT**: seluruh record satu event dengan role yang berhak; tidak otomatis lintas event.
- **GLOBAL**: hanya Superadmin dan akses yang benar-benar diizinkan.
- **READ-MIN**: hanya identitas/status minimum untuk operasi, bukan semua kolom/file.
- **DENY**: harus ditolak server 403/404; menyembunyikan tombol saja tidak cukup.
- **STATE-GATE**: Verified / Published ACCEPT / Presented / Final ACC yang dicek dari data resmi.

**Prinsip:** role template mengizinkan *jenis aksi*, scope memutuskan *record mana*, state-gate memutuskan *kapan boleh*. Orang yang berperan ganda tetap tidak boleh melompati scope resource, meski punya beberapa permission yang berlaku untuk resource berbeda.

## 2. Permission Matrix — Admin dan operasi umum (DRAFT)

| Tindakan | Pemegang izin utama | Scope wajib | Larangan |
|---|---|---|---|
| Event master settings/track/deadline | Superadmin atau Event Admin berizin | EVENT | Edit Finance history atau keputusan reviewer langsung |
| CMS/publikasi konten publik | Event Admin / Content Editor assigned | CMS/EVENT | Buka bukti transfer/metadata confidential |
| Assignment role staff | Superadmin / Event Admin yang didelegasikan | EVENT & permitted roles | Eskalasi diri menjadi Superadmin tanpa izin |
| **Ubah bank resmi** | **Superadmin saja** | GLOBAL / single active account | Finance, Event Admin, PIC tidak boleh; **tidak ada approval kedua** |
| Edit tarif master untuk invoice baru | Superadmin/Event Admin dengan fee-settings permission | EVENT | Ubah nilai invoice Verified historis |
| Lihat semua financial reports | Finance/Superadmin berizin | EVENT | PIC download global report |
| Issue Certificate batch/revoke beralasan | Certificate Officer/Admin dengan explicit grant | EVENT/type/batch | Auto-issue saat check-in atau sebelum Presented |
| QR service settings/assignment | Service Coordinator/Admin yang ditunjuk | SERVICE/COUNTER | Operator mengubah hak/slot |
| Scan layanan | Scanner Operator assigned | DEVICE/COUNTER/SESSION | Pos lain, raw QR token/log lengkap |

## 3. Permission Matrix — Academic & Publication (DRAFT)

| Aksi | Principal | Scope dan state-gate | Yang tidak bisa |
|---|---|---|---|
| Baca Reviewer Abstract Index | Reviewer eligible / Committee | Semua valid Submitted di EVENT; hanya field yang aman | Belum Submitted, notes lain, rekening |
| Claim abstract | Reviewer | No self/coauthor COI, available slot, atomic | Claim paper sendiri, paper belum Submitted |
| Submit own recommendation | Reviewer | CLAIM milik sendiri | Publish final ACCEPT atau overwrite reviewer lain |
| Publish ACCEPT abstract | Academic Committee | Paper/event berwenang; final decision diumumkan | Reviewer rekomendasi tidak langsung membuka Full Paper |
| Baca hasil ACCEPT author | Author owner | Hanya decision yang published | Internal pending decision |
| Upload Full Paper v1 | Submitting author/author berizin | Published ACCEPT, tanpa gate Finance | Paper user lain |
| Koreksi metadata ilmiah | Author owner / Academic-Editor berizin | Snapshot/versioning dan state, bukan mass assignment | Mengubah author snapshot yang sudah diekspor diam-diam |
| Konfirmasi Presented | Moderator assigned | Session/paper yang diampu | Scanner/Reviewer/Finance melakukan |
| Upload post-presentation assessment | Assigned assessor | Paper/sesi penugasan & role valid | Final ACC secara sepihak |
| Release Revision Required/No Revision | Publication Committee | Completed assessments/published committee decision | Reviewer memberi keputusan sendiri |
| Upload Revised v2 + response | Submitting author berizin | Revision Request yang published/active | Overwrite v1, upload tanpa revision request |
| Grant Final ACC | Publication Committee | Presented, assessment, revision resolved; exception belum ditetapkan | Final ACC dari ACCEPT abstrak saja |
| Select Proceedings/Journal | Publication Team berizin | Publication-ready paper, assigned event/outlet | Mengklaim Accepted by Journal |
| Build/Download OJS Export | Publication Team berizin | Frozen snapshot, outlet mapping, READY, private file | Ekspor bank/QR/review editor-only |
| Record OJS handoff/status | Publication Team berizin | External status berdasarkan bukti penerimaan target | Auto-published DOI tanpa bukti |

**OJS jurnal penerima merupakan pihak eksternal**, bukan role internal yang otomatis boleh login ke ICHES. Jika akses mitra diperlukan, buat alur khusus setelah kebijakan dan kerja sama jelas. Nama **10 jurnal** tidak tersedia di TOR yang sudah ditelaah, sehingga outlet Admin CRUD tidak boleh menampilkan daftar fiktif.

## 4. Permission Matrix — Co-Host, Program & Finance (DRAFT)

| Aksi | Principal | Scope & guard |
|---|---|---|
| Registrasi Co-Host | PIC/registrant institusi menurut prosedur panitia | Satu invoice institusi Rp3 juta, belum membuka roster |
| Buka invoice Co-Host | PIC sendiri, Finance berizin | **OWN INSTITUTION**, bukan invoice pribadi member |
| Upload bukti Co-Host | PIC sendiri | Invoice institusinya sendiri; Pending, bukan Verified |
| Tambah/kelola 5 anggota Co-Host | PIC assigned / Admin berizin | Invoice institusi **Verified** + max 5 seat |
| Submit sponsored paper | Co-Host member dengan own account | VERIFIED institusi + assigned seat + one sponsored paper |
| Lihat status paper anggota institusi | PIC assigned | Summary minimum sponsor status saja, tidak reviewer notes/full manuscript privat secara otomatis |
| Beli Paket Internasional | User di Personal Dashboard | Satu invoice Rp1 juta PERSONAL, bukan institusi |
| Buka/unggah bukti Paket pribadi | User owner, Finance untuk pemeriksaan | Owner scope; PIC tidak boleh melihat/membayarkan anggota |
| Aktifkan 4 hak Program | Sistem saat Finance Verified | Satu enrollment + empat entitlement; tidak otomatis 4 attendance |
| Finance verify invoice | Finance berizin | Cek sumber mutasi resmi dan jumlah tagihan lengkap; audit time/actor |
| Finance request correction | Finance berizin | Wajib alasan yang terlihat pembayar, bukti lama retained |
| Unduh bukti transfer | Payer scoped / Finance berizin | File privat dan auth, tidak langsung public URL |
| Coordinator lihat program | Program Coordinator assigned | Program/activity scope; tidak melihat bank/raw reviewer notes |
| Scanner lihat hak paket | Scanner assigned | READ-MIN entitlement untuk pos terkait, tanpa invoice detail |

**Perlakuan WNA:** tarif USD hanya kategori internasional TOR yang berharga USD; biaya internasional ditanggung peserta; tidak memberi Finance izin memakai auto-FX atau mengarang harga untuk kombinasi yang tidak terdaftar.

## 5. Matriks akses dokumen-file (DRAFT)

| File | Owner/Author | PIC | Reviewer | Committee | Finance | Publication | Public |
|---|---|---|---|---|---|---|---|
| Abstract PDF valid Submitted | Own | Status/metadata terbatas | Eligible index/claimed sesuai tahap | Authorized | DENY | Hanya jika berizin | DENY |
| Full Paper v1/Revised v2 | Own authorized | DENY kecuali juga author berizin | Hanya assigned reviewer/assessor terkait | Authorized | DENY | Authorized Final ACC | DENY |
| Internal reviewer/editor-only notes | DENY | DENY | Own note/claim jika diberi izin | Authorized | DENY | Hanya bila tugas nyata | DENY |
| Author-facing published review notes | Own | DENY | Own reviewer notes | Authorized | DENY | Relevant | DENY |
| Co-Host invoice/bukti | DENY kecuali PIC | Own institution | DENY | DENY | Authorized | DENY | DENY |
| Personal invoice/receipt/bukti | Own | **DENY pada workspace PIC** | DENY | DENY | Authorized | DENY | DENY |
| Finance bank credits/reconciliation | DENY | DENY | DENY | DENY | Authorized | DENY | DENY |
| Publication snapshot/export ZIP/XML | Own metadata only if allowed | DENY | DENY | Committee as needed | DENY | Authorized outlet/team | DENY |
| Personal QR/token | Own QR display | DENY | DENY | DENY | DENY | DENY | DENY |
| Public certificate verification | Token lookup minimal | Public | Public | Public | Public | Public | Public |

**Tidak otomatis menurunkan izin dari kolom tabel**: manusia yang sama bisa punya dua role, sehingga akses file diputuskan melalui actor+resource+purpose, bukan karena user bernama sama atau terdaftar pada institusi tertentu.

## 6. Route/Action guard contract — contoh, bukan route yang sudah tersedia

| Route/fungsi konseptual | Middleware/Policy | Business precondition |
|---|---|---|
| GET own invoice | auth + InvoicePolicy:view | invoice owner person atau PIC institution |
| POST invoice/{id}/proof | auth + InvoicePolicy:uploadProof + file validation | invoice belum Verified, berada pada scope pembayar |
| POST finance/invoice/{id}/verify | auth + finance.verify + InvoicePolicy:verify | bank match sah, no duplicate allocation |
| PUT superadmin/bank-settings | auth + SuperadminOnly | account info valid; update tercatat tanpa approval kedua |
| POST cohost/{reg}/members | auth + CoHostPolicy:manageMember | PIC scope + institusi invoice Verified + seat 1..5 |
| POST papers/{paper}/abstract-submit | auth + PaperPolicy:submit | author + text+PDF valid; direct reviewer index |
| POST reviewer/claim/{paper} | auth + ReviewerClaimPolicy | event, Submitted, COI passed, slot available |
| POST academic/{paper}/publish-accept | auth + AcademicDecisionPolicy:publish | Committee and valid decision; published_at set |
| POST papers/{paper}/fullpaper | auth + PaperPolicy:submitFullPaper | Published ACCEPT only, **no payment gate** |
| POST sessions/{session}/presented/{paper} | auth + SessionPaperPolicy:confirmPresented | Moderator assigned, paper in session |
| POST assessments/{paper} | auth + AssessmentPolicy:submit | assigned assessor, session paper valid |
| POST revisions/{request}/upload | auth + RevisionPolicy:respond | author authorized + active published request |
| POST publication/{paper}/final-acc | auth + FinalAccPolicy:decide | Committee, Presented + assessment/revision resolved |
| POST publication/export/{outlet} | auth + ExportPolicy:generate | Publication Team outlet scope; snapshot Ready, version profile |
| POST certificates/batch/{batch}/publish | auth + CertificatePolicy:publish | author eligibility and explicit staff publish |
| POST scanner/{service}/fulfill | auth + ScannerPolicy:fulfill | assigned service/counter, entitlement, slot, uniqueness |
| GET verify/certificate/{token} | public + throttle | opaque token; publicly allowed minimal fields only |

**Bukti kontrak** nantinya wajib diuji dengan akun/role asli dari fixture, bukan mengandalkan visual tombol hilang di Inertia. Route binding harus memaksa event association dan hanya mengizinkan resource yang memang authorized.

## 7. Data visibility pada Inertia props (DRAFT)

~~~text
Personal:
  { personal_invoice_summaries, my_papers, my_qr_display, my_program_rights }
  NEVER { other_member_payments, raw_bank_credits, internal_reviews }

PIC:
  { own_institution, institutional_invoice, five_seat_statuses, paper_status_summaries }
  NEVER { personal_package_payment_of_member, all_cohost_invoices, private_paper_files }

Reviewer:
  { safe_submitted_abstract_index, own_claims, own_review_form }
  NEVER { all_reviewer_notes, finance, unpublished_committee_decision }

Finance:
  { invoice_queues, payment_proof_reference_scoped, bank_match_summary }
  NEVER { unpublished_review_notes, unrelated private paper file }

Scanner:
  { assigned_counter, lookup_result_name_min, service_allowed, already_fulfilled }
  NEVER { account_email_unnecessary, bank, publication_export, raw token hash }
~~~

**Security**: jangan serialize model penuh pada root shared props; larang mass assignment owner fields seperti billed_user_id atau institution_id dari request; Authorization di controller/API dan private file endpoint, bukan hanya filtering props.

## 8. Konfigurasi login/secrets dan audit baseline

| Area | DRAFT recommendation | Review question |
|---|---|---|
| Login | Email/password, verified email, reset, Laravel session | Perlu Magic Link untuk invited Co-Host? |
| MFA | Staff privileged (Superadmin/Finance/Publication) disarankan | Wajib staff atau ditunda? |
| Reset-token expiry | Token sekali pakai, TTL sesuai rekomendasi framework dan risiko | Konfigurasi final setelah SMTP |
| Session & cookie | HTTPS + secure flags + regeneration + revocation | Setelan host & shared-device scanner |
| Login throttling | Rate limit email/IP dan lockout terkendali | Nilai threshold setelah UAT |
| Audit | Actor, action, scope, time, masked before/after | Siapa boleh export audit? |
| Secrets | ENV/secret store, no repo or application logs | Host deployment & backup |
| Retention | Panitia menentukan retensi, backup encrypted + restore drill | Regulasi & kebijakan institusi masih terbuka |

**Jangan** memasukkan password admin default, token OJS atau rekening bank nyata di GitHub docs, fixtures, atau screenshot publik. Semua contoh bank dan token menggunakan placeholder di dokumen.

## 9. Security UAT (52 kasus rancangan; BELUM DIJALANKAN)

| ID | Skenario | Hasil yang wajib pada implementasi |
|---|---|---|
| SEC-T01 | User yang belum login membuka Personal Dashboard | Redirect login / 401 sesuai route |
| SEC-T02 | User login memakai kredensial sah | Satu session, regenerated session ID |
| SEC-T03 | Password salah berulang | Rate limiting berjalan, tidak mengungkap akun ada/tidak |
| SEC-T04 | Password reset token kedaluwarsa | Ditolak, tidak mengganti password |
| SEC-T05 | Password reset token dipakai dua kali | Ditolak pada kali kedua |
| SEC-T06 | Form POST tanpa CSRF pada session web | Ditolak |
| SEC-T07 | Logout lalu gunakan old session cookie | Tidak terautentikasi |
| SEC-T08 | User register memakai email yang sudah terpakai | Tidak menciptakan akun kedua diam-diam |
| SEC-T09 | Co-Host invitation link kedaluwarsa | Tidak dapat mengaitkan keanggotaan |
| SEC-T10 | Role staff dicabut ketika user masih login | Aksi berikutnya ditolak; cache role konsisten |
| SEC-T11 | Satu User menjadi reviewer dan PIC | Akses terpisah per scope; tidak perlu akun ganda |
| SEC-T12 | PIC A mengubah institution_id ke B lewat POST | Backend tolak 403/404 |
| SEC-T13 | PIC A membuka invoice institusi B | Ditolak |
| SEC-T14 | PIC membuka invoice personal anggota sendiri | Ditolak pada PIC workspace tanpa izin personal owner |
| SEC-T15 | PIC membuka private Full Paper anggota yang bukan paper miliknya | Ditolak |
| SEC-T16 | PIC Co-Host belum Finance Verified membuat roster | Ditolak |
| SEC-T17 | PIC Co-Host setelah Verified kelola seat 1..5 | Diizinkan hingga lima slot |
| SEC-T18 | PIC mencoba seat ke-6 | Ditolak |
| SEC-T19 | Content Editor menulis Bank Settings | Ditolak |
| SEC-T20 | Finance biasa mengubah nomor rekening | Ditolak; hanya Superadmin |
| SEC-T21 | Superadmin mengubah rekening resmi | Diizinkan langsung dan tercatat; tidak butuh dual approval |
| SEC-T22 | Reviewer melihat index abstract semua track | Hanya valid Submitted yang aman |
| SEC-T23 | Reviewer self-claim paper yang ia tulis | Ditolak |
| SEC-T24 | Reviewer self-claim paper sebagai coauthor tanpa linked_user_id tetapi email cocok | Ditolak/COI flagged |
| SEC-T25 | Reviewer membaca confidential comment reviewer lain | Ditolak |
| SEC-T26 | Reviewer menekan publish ACCEPT | Ditolak |
| SEC-T27 | Academic Committee publish ACCEPT | Diizinkan, Full Paper unlock author |
| SEC-T28 | Author ACCEPT published tetapi invoice Unpaid mengunggah Full Paper | Diizinkan; Finance bukan gate |
| SEC-T29 | Author lain mencoba upload Full Paper bukan miliknya | Ditolak |
| SEC-T30 | Reviewer membaca decision internal ACCEPT sebelum published_at | Ditolak |
| SEC-T31 | Upload HTML disguised .pdf sebagai proof | Validasi file menolak |
| SEC-T32 | URL bukti transfer dapat diakses anon tanpa auth | Ditolak, tidak public |
| SEC-T33 | User menebak raw stored_file_id peserta lain | Ditolak |
| SEC-T34 | Upload proof belum diverifikasi mutasi | Tidak mengaktifkan paket/Co-Host |
| SEC-T35 | PIC menyerahkan bukti yang sama untuk invoice institusi lain | Tidak auto Verified lintas owner |
| SEC-T36 | Finance berizin cocokkan transaksi bank dan verify | Verified sekali + actor/time + hak tepat |
| SEC-T37 | Finance mencoba mengubah academic decision | Ditolak |
| SEC-T38 | Finance menghapus audit bank Verified | Ditolak / append-only koreksi tercatat |
| SEC-T39 | Moderator sesi A tandai Presented paper sesi B | Ditolak |
| SEC-T40 | Scanner QR paper bertugas mengubah Presented | Ditolak |
| SEC-T41 | Assessor valid memberi NO_REVISION | Rekomendasi tercatat, bukan auto Final ACC |
| SEC-T42 | Assessor mencoba Final ACC | Ditolak; Committee berwenang |
| SEC-T43 | Author menerima Revision Required dan upload v2 | Sah, v1 tetap privat dan immutable |
| SEC-T44 | Orang bukan author upload response revisi | Ditolak |
| SEC-T45 | Publication Team generate export sebelum Final ACC | Ditolak |
| SEC-T46 | OJS export berisi payment/reviewer confidential fields | Diblokir schema/export allowlist |
| SEC-T47 | Publication Team dengan outlet A mengakses export outlet B | Ditolak jika assignment tidak sesuai |
| SEC-T48 | Reviewer/Finance akses publication export ZIP | Ditolak |
| SEC-T49 | Scanner pos FNB memakai token untuk pos KIT tanpa assignment | Ditolak |
| SEC-T50 | Dua scan layanan slot sama setelah reconnect | Maksimal satu fulfillment CONFIRMED |
| SEC-T51 | Public certificate verify memakai token tidak valid | Tidak membocorkan daftar seluruh author/email/QR |
| SEC-T52 | Certificate Officer mencoba terbitkan tanpa Presented/eligibility | Ditolak; panitia issue hanya setelah memenuhi syarat |

**Belum ada UAT dijalankan**. Test di atas adalah acceptance criteria bagi implementation stage; tidak ada klaim security audit atau penetration test telah dilaksanakan.

## 10. Handoff dan outstanding decisions

**Arah disetujui sebelumnya:** satu akun, Full Paper sesudah published ACCEPT, Finance Verified sebagai gerbang Co-Host/Paket, satu rekening Superadmin, reviewer self-claim no screening, Moderator Presented, all listed authors eligible certificate + batch issuance, jalur Full Paper OJS-like menuju Proceedings/Journal.

**Masih DRAFT untuk review:** pilihan login (password/Magic Link), detail role/permission keys, MFA staff, session policy, private file delivery options, storage provider, scanner device credentials/offline mode, audit retention, konten publik sertifikat, jumlah reviewer, dan konfigurasi OJS/outlet.

**Rekomendasi langkah berikutnya:** 18C-04 Frontend/Dashboard Architecture; sebelum coding tetapkan stack/database/PK dan selaraskan 38+15 kandidat tabel final dengan kebijakan yang sudah final. **Tidak ada GO coding atau freeze security pada dokumen ini.**

---

[Security Architecture](18C-03-DRAFT-AUTHENTICATION-RBAC-SECURITY-ARCHITECTURE.md) · [Role 18A](18A-ROLE-PERMISSION-DAN-NAVIGASI-BACKOFFICE.md) · [Backend 18C-02](18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md) · [README](../README.md).
