# 18C-01A — DRAFT Kamus Data, Constraints, Migration Order & UAT

> **ADDENDUM DRAFT 27 OKTOBER:** 38 tabel pada kamus ini adalah **baseline sebelum permintaan baru Full Paper ala OJS & revisi pascapresentasi**. Tambahan **15 kandidat (hingga 53 total untuk diskusi)** dan constraints/export berada pada [18C-01B](18C-01B-DRAFT-FULLPAPER-PUBLICATION-ERD-DATA-DICTIONARY.md). Schema ini **BELUM FINAL** dan jumlahnya dapat dipangkas setelah review. Jangan membuat migration dari dokumen 18C-01A secara terpisah.

> **27 Oktober 2026 · LAMPIRAN TEKNIS UNTUK REVIEW, BUKAN FINAL/BUKAN CODING.** Semua nama tabel, struktur, tipe field, primary key, DB engine, index dan urutan migration di bawah merupakan **REKOMENDASI** dan boleh direvisi oleh pengguna. Kebijakan bisnis yang sudah disetujui tetap dipertahankan. Tidak ada database, migration, SQL DDL, seeder atau test yang dijalankan.
>
> [DRAFT ERD utama 18C-01](18C-01-DRAFT-DATABASE-ARCHITECTURE-ERD.md) · [Decision Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) · [Admin CRUD](17-ADMIN-CRUD-DAN-PENGATURAN-ACARA.md) · [Finance](18B-06-CRUD-FINANCE-INVOICE-PEMBAYARAN-VERIFIKASI.md) · [QR](05-QR-ABSENSI-DAN-FASILITAS.md).

## 1. Konvensi rekomendasi (belum dikunci)

| Topik | Pilihan yang diusulkan |
|---|---|
| DB | PostgreSQL atau MySQL menurut hosting; **belum memilih** |
| PK | BIGINT ilustratif, ULID alternatif; belum dipilih |
| Time | created_at/updated_at; tampilkan tanggal ICHES dalam WIB, penyimpanan zona konsisten |
| Uang | DECIMAL(18,2) + currency ISO IDR/USD; **bukan FLOAT** |
| Status | VARCHAR + whitelist dan state transition backend; CHECK bila sesuai engine |
| Files | Storage privat (PDF/JPEG/PNG) dengan metadata tabel stored_files, bukan blob besar di DB |
| FKs | Foreign key riil, parent-child event-scope diperiksa DB+service |
| Unique | Unique index untuk aturan satu kali; strategi unique record aktif tergantung database |
| Delete | Master boleh archive; invoice/verified/decision/attendance/receipt tidak hard-delete |
| Auth | Bentuk password vs OTP/Magic Link belum final; sesuaikan keputusan tahap security |

Ada **38 tabel domain kandidat** pada 18C-01, terpisah dari tabel Laravel framework semacam jobs, sessions, password reset, cache, migrations. Angka 38 bukan jumlah tabel wajib pada MVP.

## 2. Data dictionary: 38 tabel dan field inti yang diusulkan

### A. Identity / event / storage (6)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **events** | id, slug, name, year, starts_at?, ends_at?, status | UNIQUE(slug), satu event ICHES 2027 |
| **users** | id, email, name, nationality_code?, email_verified_at?, password_hash? | UNIQUE(normalized_email); kewarganegaraan tidak ditebak dari domisili |
| **institutions** | id, official_name, display_name?, country_code?, website?, logo_file_id? | Tidak otomatis menggabungkan lembaga hanya karena nama mirip |
| **role_assignments** | user_id, event_id?, role_key, scope_kind, scope_id?, assigned_by, revoked_at? | Role multi-scope; PIC hanya institusi terkait |
| **stored_files** | id, disk, private_path, original_name, mime_type, byte_size, sha256?, uploaded_by | Pemeriksaan file server, file privat bukan direct public URL |
| **event_settings** | event_id, setting_key, value_json, updated_by | UNIQUE(event_id,setting_key); nilai tervalidasi berdasarkan jenis |

### B. Co-Host (2)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **cohost_registrations** | event_id, institution_id, pic_user_id, registration_status, registered_at, recognition_status? | UNIQUE(event_id,institution_id); invoice institusi tunggal |
| **cohost_memberships** | cohost_registration_id, user_id, seat_number, invitation_status?, linked_at?, archived_at? | Seat 1–5, UNIQUE(registration,seat), UNIQUE(registration,user); **fitur hanya setelah Verified** |

**PIC bukan otomatis seat member.** Penggantian member setelah submit, seat pada paper Rejected/Withdrawn dan paper reguler tambahan masih kebijakan TERBUKA.

### C. Conference / review / sessions (9)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **conference_tracks** | event_id, code, title, sort_order, is_active | UNIQUE(event_id,code); delapan track TOR sebagai seed editable |
| **papers** | event_id, submitter_user_id, track_id, cohost_membership_id?, title, abstract_text?, submission_status, submitted_at? | Sponsor satu paper/member. Saat SUBMITTED wajib Abstract Text dan PDF |
| **paper_authors** | paper_id, linked_user_id?, author_order, display_name, email?, affiliation_name?, is_corresponding | UNIQUE(paper_id,author_order); coauthor boleh tanpa akun |
| **paper_files** | paper_id, stored_file_id, document_kind, version_number, uploaded_by, submitted_at? | UNIQUE(paper_id,document_kind,version_number); abstract PDF/fullpaper dipisah |
| **review_claims** | paper_id, reviewer_user_id, claim_status, claimed_at, released_at? | Atomic self-claim; reviewer bukan author; max reviewer **belum final** |
| **reviews** | review_claim_id, recommendation, comments_author?, comments_committee?, submitted_at? | Reviewer merekomendasikan, tidak menetapkan keputusan akhir |
| **academic_decisions** | paper_id, decided_by, decision, decision_at, published_at?, is_current?, notes? | History; ACCEPT **published_at** adalah gate Full Paper/LoA |
| **conference_sessions** | event_id, title, location_or_link?, starts_at, ends_at, moderator_user_id?, status | Jadwal CRUD tanpa engine benturan |
| **session_papers** | session_id, paper_id, presentation_order?, presented_at?, confirmed_by?, notes? | Presented diisi Moderator, bukan scan QR |

**Jangan hardcode:** dua reviewer wajib, rubric skor, abstract max word, Full Paper gated Finance, atau invoice presenter sebelum ACCEPT resmi diumumkan.

### D. Programs / package (5)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **activities** | event_id, code, title, starts_at?, ends_at?, registration_state | Agenda/program CRUD, termasuk MoU/Upgrading/PKM/Destinasi |
| **packages** | event_id, code, name, price_amount, currency, opens_at?, deadline_at?, registration_state | Paket Program Internasional satu produk Rp1 juta; deadline editable Admin |
| **package_activities** | package_id, activity_id | UNIQUE(package_id,activity_id); empat kegiatan dalam satu paket |
| **package_orders** | event_id, package_id, user_id, order_status, ordered_at | Satu order aktif/user/package/event (usulan integritas) |
| **activity_entitlements** | package_order_id, activity_id, user_id, activated_at?, status | UNIQUE(order_id,activity_id); aktif setelah Finance Verified |

**Keempat entitlement tidak otomatis menciptakan empat invoice, absensi, atau hak menjadi penandatangan MoU.**

### E. Finance (7)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **fee_rates** | event_id, fee_code, category, mode, amount, currency, is_active | Tarif kategori TOR; Co-Host Rp3 juta pengganti Rp450 ribu TOR |
| **bank_settings** | event_id, bank_code?, account_number?, holder_name?, swift_bic?, instructions_id?, instructions_en?, updated_by | UNIQUE(event_id), satu rekening, Superadmin edit langsung |
| **invoices** | event_id, invoice_code, purpose, billed_user_id?, billed_institution_id?, cohost_registration_id?, package_order_id?, paper_id?, participant_registration_id?, amount_due, currency, bank_snapshot_json, payment_status, issued_at, due_at? | **Owner tepat satu dan source FK tepat satu**, tarif snapshot. Invoice Presenter hanya sesudah published ACCEPT |
| **payment_proofs** | invoice_id, stored_file_id, uploaded_by, proof_version, transfer_reference?, uploaded_at | Banyak versi bukti, upload tidak membuat Verified |
| **bank_credits** | event_id, invoice_id?, bank_reference?, credited_at, credited_amount, credited_currency, sender_amount?, sender_currency?, bank_memo?, entered_by | Dicocokkan manual dari mutasi bank; jangan klaim feed banking API |
| **finance_reviews** | invoice_id, finance_user_id, decision, reason?, reviewed_at, bank_reference? | Verify/Correction oleh Finance scoped dan tercatat |
| **payment_receipts** | invoice_id, receipt_number, stored_file_id?, issued_at, verified_review_id | UNIQUE(invoice_id); Bukti Lunas hanya setelah Verified |

**Invoice owner:** Co-Host = institusi; Paket, Presenter Reguler, Participant = pribadi. **Jangan auto-create tagihan Presenter bagi 5 paper sponsor Co-Host** setelah ACCEPT. Jika member Co-Host membeli paket Rp1 juta, itu personal invoice terpisah. WNA kategori TOR internasional tetap USD, paket khusus yang bertarif IDR tetap IDR, tanpa auto FX.

**Finance policy OPEN:** transfer susulan, refund, overpayment, akibat late, incoming USD bank, presenter multi-paper dan kategori tidak ada dalam TOR; tidak otomatis diaktifkan hanya karena tabel mendukungnya.

### F. Participants / QR / services (5)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **participant_registrations** | event_id, user_id, fee_category_code, attendance_mode, registered_at, status | Participant reguler sesuai tarif yang tersedia, user boleh sekaligus author |
| **personal_qrs** | event_id, user_id, token_hash, issued_at, revoked_at? | UNIQUE(event_id,user_id), QR opaque dan privat |
| **activity_checkins** | activity_id, user_id, slot_key?, confirmed_at, confirmed_by?, checkin_mode | Kehadiran CONFIRMED per aktivitas/slot, bukan hak program |
| **service_catalog** | event_id, service_code, title, time_slot_rules_json?, is_active | Pos konsumsi/kit/fasilitas diatur Admin |
| **service_fulfillments** | service_id, user_id, slot_key, confirmed_by, confirmed_at, device_reference? | UNIQUE(service,user,slot); layanan benar-benar diberikan |

**Offline scanning belum final:** jika dipilih, Pending Sync terpisah dari CONFIRMED pada server. Dua device offline penuh tidak dapat menjamin tidak ada fulfillment ganda saat terputus.

### G. Certificates (3)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **certificate_batches** | event_id, certificate_type, created_by, published_by?, published_at?, status | Penerbitan batch manual panitia |
| **certificates** | event_id, batch_id, paper_author_id?, recipient_user_id?, certificate_type, verify_token, issued_at?, status | Satu presenter certificate tiap listed author dari paper Presented; coauthor boleh tanpa akun |
| **certificate_versions** | certificate_id, version_number, stored_file_id, created_by, reason? | UNIQUE(certificate_id,version_number), jangan hapus riwayat reissue |

**QR verifikasi sertifikat publik** berbeda dari QR personal layanan. Detail data privat paper/Finance tidak muncul di halaman verifikasi publik.

### H. Notifications (1)

| Tabel | Kolom inti | Aturan |
|---|---|---|
| **notification_events** | event_id, recipient_user_id, kind, resource_type, resource_id, payload_json, read_at?, emailed_at?, dedupe_key | Dedupe, Dashboard utama; email transaksional opsional tidak membuat status Finance baru |

## 3. Constraint dan guard yang disarankan

| ID | DB constraint + backend check | Tujuan |
|---|---|---|
| C-01 | UNIQUE(cohost_registration.event_id,institution_id) | Satu pendaftaran institusi |
| C-02 | CHECK(seat_number BETWEEN 1 AND 5) | Maks. lima seat |
| C-03 | UNIQUE(cohost_registration_id,seat_number) dan UNIQUE(cohost_registration_id,user_id) | Tidak dua anggota satu seat |
| C-04 | UNIQUE(papers.cohost_membership_id) untuk non-null + submitter harus membership.user_id | Satu paper sponsor tiap wakil |
| C-05 | Backend transaction: invoice Co-Host VERIFIED sebelum roster/submit | Fitur bayar-dulu |
| C-06 | Saat paper SUBMITTED wajib text dan PDF; tidak Administrative Screening | Index reviewer langsung |
| C-07 | Reviewer conflict check listed authors + atomic slot claim | Self-claim lintas track, tanpa review diri sendiri |
| C-08 | ACCEPT published_at not null untuk unlock Full Paper dan LoA | Internal ACCEPT tidak cukup |
| C-09 | Satu active package_order tiap user/package dan satu invoice source | Tidak tagihan ganda |
| C-10 | UNIQUE(package_order_id,activity_id); aktif setelah Invoice VERIFIED | Empat akses dibuka satu kali |
| C-11 | **Invoice owner XOR:** billed_user_id non-null **atau** billed_institution_id non-null, tepat satu | Tidak membingungkan pembayar |
| C-12 | **Invoice source XOR:** tepat satu FK dari Co-Host/Package Order/Paper/Participant | Satu invoice satu kewajiban |
| C-13 | UNIQUE(invoice.cohost_registration_id) & UNIQUE(invoice.package_order_id) bila non-null | Satu invoice tiap sumber |
| C-14 | Paper sponsor Co-Host tidak boleh membuat invoice Presenter Reguler | Bebas charge Conference kedua |
| C-15 | Presenter invoice hanya sesudah Published ACCEPT, bukan reviewer rec | Bayar hanya setelah accept |
| C-16 | Proof upload tidak Verified; bank credit dan Finance decision wajib | Screenshot bukan penerimaan |
| C-17 | UNIQUE(payment_receipts.invoice_id) + receipt hanya setelah VERIFIED | Bukti Lunas tepat sekali |
| C-18 | UNIQUE(personal_qrs.event_id,user_id); opaque token | Satu QR personal |
| C-19 | UNIQUE(service_id,user_id,slot_key) untuk confirmed fulfillment | Tidak konsumsi ganda pada slot |
| C-20 | Presented hanya moderator; certificate eligible semua authors tetapi batch publish manual | Tidak sertifikat otomatis |
| C-21 | Backend scoped auth pada invoice, proofs, comments, QR, services | Menjaga privasi PIC/member |

**Engine caveat:** PostgreSQL partial index vs MySQL generated/functional index untuk UNIQUE active/nullable perlu dipilih **sesudah DB engine ditetapkan**. Constraint unik membantu tetapi gate Verified dan event-matching harus tetap di transaksi backend dengan locking.

## 4. Contoh kontrak transaksi (pseudocode DRAFT, bukan source Laravel)

~~~text
CoHostActivate:
  BEGIN TRANSACTION
  lock cohost_registration and institutional invoice
  require invoice.payment_status == VERIFIED
  allow PIC manage seats 1..5
  COMMIT

SponsoredPaperSubmit:
  BEGIN TRANSACTION
  lock registration and membership
  require cohost institutional invoice VERIFIED
  require membership.user_id == submitting_user and no sponsored paper used
  validate Abstract Text + valid PDF + authors
  save SUBMITTED; make visible to Reviewer Index directly
  COMMIT

PublishAcceptRegular:
  BEGIN TRANSACTION
  require Academic Committee permission
  publish final ACCEPT decision
  unlock LoA and Full Paper immediately
  if paper regular and fee resolution valid: generate presenter invoice once
  if CoHost sponsored: DO NOT generate presenter invoice
  COMMIT

VerifyPersonalPackage:
  BEGIN TRANSACTION
  lock invoice and order
  require Finance authorization and matched bank funds
  set VERIFIED + finance_review history
  create four entitlements with unique(order,activity), idempotently
  commit; receipt PDF and notification can retry afterward

ModeratorPresented:
  verify moderator scoped to session
  set paper presented_at once
  eligible all paper_authors for certificate
  do NOT automatically publish certificates

ServiceConfirm:
  check actual entitlement, service, user, slot, operator scope
  lock and create one CONFIRMED fulfillment
~~~

## 5. Urutan migration yang saya rekomendasikan (jika disetujui)

| Urutan | Tabel |
|---|---|
| M-01 Identity | events, users, institutions, stored_files, event_settings, role_assignments |
| M-02 Conference Core | conference_tracks, cohost_registrations, cohost_memberships, papers, paper_authors, paper_files |
| M-03 Academic | review_claims, reviews, academic_decisions, conference_sessions, session_papers |
| M-04 Programs | activities, packages, package_activities, package_orders, activity_entitlements, participant_registrations |
| M-05 Finance Core | fee_rates, bank_settings, invoices (source FKs kini tersedia) |
| M-06 Finance Transaction | payment_proofs, bank_credits, finance_reviews, payment_receipts |
| M-07 Operations | personal_qrs, activity_checkins, service_catalog, service_fulfillments |
| M-08 Documents | certificate_batches, certificates, certificate_versions |
| M-09 Notification | notification_events serta framework jobs/cache/auth tables jika dipilih |
| M-10 Quality | index khusus, seed kegiatan TOR yang sudah disetujui, fixture pengujian & test transaksi |

Jangan seed nomor rekening fiktif sebagai rekening resmi, data pribadi peserta, atau daftar harga WNA/participant yang tidak ada di TOR. Kegiatan/8 track dari TOR dapat dijadikan seed awal editable Admin **setelah** persetujuan desain dan implementasi.

## 6. Index dan keamanan ringkas

| Use case | Index/guard yang disarankan |
|---|---|
| Reviewer Index global | papers(event_id,submission_status,track_id,submitted_at) |
| User My Papers | papers(submitter_user_id,event_id), backend owner permission |
| PIC 5 seats | cohost_memberships(registration_id,seat_number), backend gate Verified |
| Finance inbox | invoices(event_id,payment_status,issued_at), invoices(purpose,currency) |
| Reconciliation | bank_credits(event_id,bank_reference), bank_credits(invoice_id) |
| Personal four entitlements | activity_entitlements(user_id,activity_id,status) |
| QR scans | unique confirmed activity_checkins / service_fulfillments based slot |
| Certificate verify | UNIQUE(certificates.verify_token); minimal public fields |
| Upload documents | file private storage + authenticated scoped download |
| Notification retries | notification_events(event_id,recipient_user_id,dedupe_key) |

Performance dan load test belum dijalankan, sehingga **tidak ada klaim kapasitas server**. Data sensitif (bukti bank, email coauthor, notes komite/reviewer) tidak disimpan di repo GitHub publik atau public storage tanpa pembatasan.

## 7. UAT integritas data — 36 skenario DIRANCANG, belum diuji

| ID | Skenario | Ekspektasi |
|---|---|---|
| DBT-01 | User PIC sekaligus author | Satu akun dengan beberapa role |
| DBT-02 | Coauthor tidak memiliki akun | Tercatat di paper_authors |
| DBT-03 | Institusi sama Co-Host didaftarkan dua kali | Satu registrasi valid |
| DBT-04 | Co-Host Unpaid mencoba roster | Ditolak |
| DBT-05 | Co-Host Pending proof mencoba submit | Ditolak |
| DBT-06 | Co-Host VERIFIED | Roster 1–5 tersedia |
| DBT-07 | PIC membuat seat 6 | Ditolak |
| DBT-08 | Dua anggota mengambil seat 3 | Maksimal satu |
| DBT-09 | Satu anggota memakai dua seat | Ditolak |
| DBT-10 | Satu member mengirim dua sponsored papers | Paper kedua ditolak |
| DBT-11 | Paper sponsor punya beberapa coauthors | Hanya satu seat yang dipakai |
| DBT-12 | Kelima paper sponsor ACCEPT | Tidak invoice Conference pribadi |
| DBT-13 | Member Co-Host membeli paket | Invoice Rp1 juta pribadi, bukan institusi |
| DBT-14 | Order Paket double-click | Satu order, satu invoice |
| DBT-15 | Paket hanya upload proof | Empat hak belum aktif |
| DBT-16 | Paket VERIFIED | Tepat empat hak aktif |
| DBT-17 | Retry Verified Paket | Tidak membuat hak kelima/receipt kedua |
| DBT-18 | Paper submit tanpa abstract text | Tolak |
| DBT-19 | Paper submit tanpa PDF valid | Tolak |
| DBT-20 | Abstract Text + PDF valid | Submitted langsung Reviewer Index |
| DBT-21 | Reviewer klaim paper sendiri | Tolak |
| DBT-22 | Reviewer klaim paper track lain | Boleh jika valid dan tanpa konflik |
| DBT-23 | Reviewer claim bersamaan pada slot terakhir | Tidak oversubscribed |
| DBT-24 | Reviewer merekomendasikan ACCEPT | Full Paper/invoice belum aktif |
| DBT-25 | Komite ACCEPT internal belum publish | Belum unlock Full Paper/invoice |
| DBT-26 | Komite publish ACCEPT regular | Full Paper unlock, invoice presenter dibuat |
| DBT-27 | Presenter ACCEPT tetapi unpaid | Full Paper tetap boleh |
| DBT-28 | Invoice punya owner institusi dan user sekaligus | Tolak |
| DBT-29 | Invoice punya dua source kewajiban | Tolak |
| DBT-30 | User membuka payment proof user lain | Ditolak |
| DBT-31 | Upload PDF bukti | Tidak otomatis Verified |
| DBT-32 | Verified Receipt diunduh berkali-kali | Nomor receipt tetap |
| DBT-33 | QR dipakai tanpa entitlement | Tidak dianggap punya hak program |
| DBT-34 | Dua scan layanan sama pada slot sama | Maks. satu fulfillment CONFIRMED |
| DBT-35 | Check-in paper belum Presented | Tidak mengisi presented_at |
| DBT-36 | Moderator Presented, belum batch publish | Semua authors eligible, belum issued |

## 8. Review checklist: belum ada persetujuan final arsitektur

| ID | Keputusan yang harus ditinjau | Status |
|---|---|---|
| D-01 | Satu database relasional, PostgreSQL/MySQL ditentukan dari hosting | **MENUNGGU REVIEW** |
| D-02 | BIGINT vs ULID untuk PK | **MENUNGGU REVIEW** |
| D-03 | Satu invoice lintas kategori dengan owner/source FK eksplisit | **MENUNGGU REVIEW** |
| D-04 | Co-Host seat 1–5 dan satu sponsored paper/member | **MENUNGGU REVIEW** |
| D-05 | Snapshot author independen akun, karena semua authors paper Presented eligible | **MENUNGGU REVIEW** |
| D-06 | Paket order dan empat entitlements terpisah dari kehadiran | **MENUNGGU REVIEW** |
| D-07 | Proof, bank credit, Finance review, Payment Receipt dipisah | **MENUNGGU REVIEW** |
| D-08 | QR personal, check-in, service fulfillment, Presented terpisah | **MENUNGGU REVIEW** |
| D-09 | Tabel scanner offline, refund, MoU signatory hanya ditambah jika dibutuhkan | **MENUNGGU REVIEW** |
| D-10 | Tabel mana yang perlu dipangkas/digabung sebelum coding | **MENUNGGU REVIEW** |

**Proses yang benar:** pengguna membaca DRAFT 18C-01 dan 18C-01A → bila ada revisi, dokumen diperbaiki → setelah pengguna menyatakan setuju baru dipilih schema final → implementasi Laravel migrations dan automated UAT. Persetujuan atas ketentuan bisnis **tidak** otomatis mengesahkan desain semua tabel.

---

[ERD DRAFT 18C-01](18C-01-DRAFT-DATABASE-ARCHITECTURE-ERD.md) · [Register](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) · [README](../README.md).
