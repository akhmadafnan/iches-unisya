# 18C-03 — DRAFT Authentication, RBAC & Security Architecture ICHES 2027

> **27 Oktober 2026 — DRAFT REKOMENDASI / MENUNGGU REVIEW / BELUM CODING.**
>
> Melanjutkan [18C-02 Backend](18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md), [18A Role Blueprint](18A-ROLE-PERMISSION-DAN-NAVIGASI-BACKOFFICE.md), [18A Operasional](18A-ALUR-OPERASIONAL-DAN-UAT-PERAN.md), [18C-01 Database](18C-01-DRAFT-DATABASE-ARCHITECTURE-ERD.md) dan [Register Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md). Matriks terperinci dan skenario uji ada di [18C-03A](18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md).
>
> **Aturan bisnis yang sudah DISETUJUI tetap berlaku; cara implementasi security di sini masih USULAN.** Tidak ada user, autentikasi, migration, source code, deployment, atau tes yang dibuat dalam tahap dokumentasi.
>
> **Referensi framework yang diperiksa:** [Laravel 13 Authentication](https://laravel.com/framework/docs/13.x/authentication), [Authorization](https://laravel.com/framework/docs/13.x/authorization), [CSRF](https://laravel.com/framework/docs/13.x/csrf), [Password Reset](https://laravel.com/framework/docs/passwords), [Private File Storage](https://laravel.com/framework/docs/filesystem). Repo sebelumnya memang memiliki Laravel 13 + MySQL + UUIDv7 + Spatie Permission dengan conference-edition teams dan private storage: [Current State repo lama](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/ai-context/CURRENT_STATE.md). Itu **bukan** bukti implementasi di repo ICHES baru.

## 1. Prinsip: aman namun tidak ribet

**Rekomendasi utama:** satu akun peserta yang dapat menerima banyak role, tetapi setiap akses data harus dicek berdasarkan **aksi + event + penugasan + kepemilikan resource + keadaan bisnis**. Tidak perlu login khusus PIC, reviewer, moderator, Finance atau publikasi. Semua memakai akun yang sama, dengan menu berdasarkan role/scope.

~~~mermaid
flowchart TD
    U["ONE User + Session Login"] --> P["Personal Dashboard: sendiri"]
    U --> C{"Role & Scope assignment tersedia?"}
    C -->|Co-Host PIC| I["Institution Workspace: own institution"]
    C -->|Reviewer| R["Reviewer Index & own claims"]
    C -->|Finance| F["Finance Verification"]
    C -->|Moderator| M["Assigned Sessions: Presented"]
    C -->|Academic or Publication| A["Decisions, Full Paper Revision, OJS Export"]
    C -->|Service Operator| S["Assigned Counter / QR Service"]
    C -->|Superadmin| B["Bank Settings & sensitive Admin"]
    P --> PE["Own paper, own invoice & package"]
    I --> CH["Own Co-Host invoice Rp3m; 5 seats after VERIFIED"]
~~~

**Tampilan menu atau penggantian workspace bukan otorisasi.** Server tetap harus menolak request ke resource orang/institusi lain walaupun user tahu URL atau ID.

## 2. Enam konsep jangan digabung

| Lapisan | Pertanyaan | Sumber kebenaran |
|---|---|---|
| Authentication | Akun ini siapa? | Satu User + session terverifikasi |
| Role & Permission | Tindakan apa boleh ia lakukan? | Role assignment / permission |
| Scope & Ownership | Pada event/institusi/paper/sesi/pos mana? | Assignment dan FK domain, dicek server |
| Payment Entitlement | Sudah berhak memakai fasilitas berbayar? | Invoice sumber Finance VERIFIED |
| Academic Eligibility | Full Paper/Final ACC boleh dibuka? | Published ACCEPT; Presented + assessment + revision saat publikasi |
| Operational Evidence | Hadir/presented/menerima layanan? | Check-in, Moderator Presented, Fulfillment yang confirmed |

**Co-Host:** satu invoice Rp3 juta institusi, fasilitas 5 anggota/5 paper hanya setelah Finance VERIFIED; tiap paper sponsor bebas invoice Conference kedua setelah ACCEPT. **Paket Internasional:** satu invoice Rp1 juta pribadi; empat hak setelah VERIFIED. **Presenter reguler:** Full Paper terbuka begitu ACCEPT resmi diumumkan, tanpa cek pembayaran. Reviewer tidak dapat memberi ACCEPT final; Moderator yang mengonfirmasi Presented; Publication Committee yang memberi Final ACC; sertifikat tiap listed author Paper Presented diterbitkan oleh panitia secara eksplisit.

## 3. Authentication MVP yang saya sarankan — BELUM FINAL

| Area | Usulan | Status dan batas |
|---|---|---|
| Login | Email + password pada Laravel session guard untuk web Inertia | **USULAN**; passwordless/Magic Link tetap opsi |
| Registrasi | Email terverifikasi sebelum tindakan penting; unique normalized email | **USULAN**; SMTP/host/email deliverability belum dipilih |
| Password | Hash dari framework, reset via token berumur pendek dan throttling | Praktik teknis; jangan kirim password plaintext |
| Sessions | HTTPS, Secure/HttpOnly/SameSite cookie yang tepat, session regenerate setelah login, invalidate saat logout | Usulan konfigurasi host |
| CSRF | Middleware Laravel untuk semua operasi web stateful; tidak mengecualikan Finance | Framework-recommended |
| Throttling | Login/reset/invite/QR verify/unduh massal dan export berizin diberi rate limit sesuai pengujian | Nilai numerik belum diputuskan |
| Privileged MFA | Direkomendasikan untuk Superadmin dan Finance/staff sensitif jika tersedia | **TERBUKA**, tidak otomatis memaksa seluruh peserta |
| Staff access | Assignment role individu dan revoke; tidak berbagi satu akun panitia | **USULAN kuat**, satu user tetap multi-role |
| Scanner Android offline | Kredensial device/operator tersendiri hanya jika mobile native/offline disetujui | **TERBUKA** pada tahap scanner |

**Sederhana:** login via session untuk web, bukan membuat REST + OAuth kompleks hanya untuk Dashboard. Bila Co-Host menggunakan invitation link, link **sekali pakai dan kedaluwarsa** adalah usulan; undangan tidak memberikan hak slot sebelum invoice VERIFIED. Jangan aktifkan identitas staf hanya dari domain email institusi.

## 4. Role templates dan scope DRAFT

| Role/template | Scope data | Tujuan utama |
|---|---|---|
| Superadmin | Sistem/event global | Event/staff policy & **satu rekening resmi Bank Settings langsung tanpa dual approval** |
| Event Admin | Event yang ditugaskan | CRUD konten/master, staf berizin, rekap; **tidak otomatis Finance Verified** |
| Academic Committee | Conference/event | Final abstract decision dan Publish ACCEPT; keputusan academic lain sesuai penugasan |
| Abstract Reviewer | Event + own claimed paper | Reviewer Index semua tracks, atomic claim & review sendiri, tidak self-review |
| Moderator | Assigned session | Konfirmasi Presented/No Show, bukan Finance/Final ACC |
| Presentation Assessor | Assigned session/paper | Penilaian pascapresentasi, komentar dan rekomendasi, bukan Final ACC |
| Publication Committee | Event/paper berizin | Revision decision / No Revision / Final ACC |
| Publication Team | Event dan assigned outlets | Metadata readiness, outlet selection, export dan OJS handoff |
| Finance | Event finance data | Cocokkan mutasi, Verified/Correction, laporan |
| Co-Host PIC | **Satu registrasi/institusi** | Invoice Rp3 juta sendiri; kelola roster/paper setelah VERIFIED |
| MoU PIC | Satu institusi MoU | Usul delegasi, bukan tanda tangan otomatis |
| Program Coordinator | Assigned activities | MoU/Upgrading/PKM/Destinasi dalam scope |
| Service Coordinator | Assigned service/counter | Hak layanan/slot/stok, grant/revoke scoped |
| Scanner Operator | Assigned device/counter/service | Konfirmasi layanan sesuai hak, tidak grant/revoke |
| Certificate Officer | Assigned batch/type/event | Terbitkan sertifikat setelah eligibility, tidak mengubah Presented |
| Content Editor | Website/CMS | Konten publik, bukan jurnal reviewer/payment data |
| Participant/Author | Own personal data/paper | Personal Dashboard, own payments, full paper dan revision berizin |

**Nama role bersifat usulan**, bukan menambah sistem login. User dapat mempunyai beberapa role. Pilih konteks workspace hanya untuk kemudahan; **backend policy selalu memverifikasi scope sebenarnya**.

**Penyesuaian dari dokumen 18A:** usulan 18A yang menggabungkan kewenangan Admin tidak boleh mengalahkan keputusan terbaru bahwa **Bank Settings hanya Superadmin**. Ini pemisahan izin, **bukan dua tahap persetujuan**.

## 5. Laravel RBAC & ABAC yang praktis

Gunakan kombinasi:
1. **Role/Permission** untuk kemampuan umum (contoh: finance.invoice.verify, conference.abstract.claim, publication.export.generate).
2. **Policy/Gate per resource** untuk owner dan scope event/institution/paper/session/outlet.
3. **Business Gate** untuk Verified, published ACCEPT, Presented, Final ACC, author response dan status kegiatan.
4. **Database constraints/transactions** untuk anti-duplikasi dan pembatasan kuota/konkurensi.

~~~text
Route → Auth Middleware → FormRequest → Laravel Policy/Gate
      → Business Action → DB Transaction & Audit → After-commit Job
~~~

**Contoh yang HARUS ditolak meski user punya banyak role:** PIC institusi A mencoba membuka invoice institusi B; PIC A melihat invoice Paket pribadi anggota; Finance mengubah academic ACCEPT; Reviewer mengakses dokumen pembayaran; Moderator menandai Presented paper pada sesi yang tidak diampu; publikasi Final ACC dari rekomendasi assessor; content editor mengubah Bank Settings; scanner menggunakan counter lain.

Jika memakai Spatie Permission teams/edition scope dari repo lama, **jangan menyamakan team=event dengan scope institution/session**. Komponen role cache harus menghapus hak ketika assignment dicabut. Pilihan paket/version dan BIGINT vs UUIDv7 dari 18C-01 tetap belum final.

## 6. Permission Matrix keputusan kritis

| Aksi | Actor authorized | Syarat dan larangan |
|---|---|---|
| Ubah Bank Settings | **Superadmin langsung** | Catat audit; Finance/Admin tidak bisa dan tidak ada approval kedua |
| Upload bukti Co-Host | PIC institusi sendiri | Hanya **1 invoice institusi**; roster/submit sponsor tunggu VERIFIED |
| Upload bukti Paket | Pemilik akun pribadi | Tagihan pribadi saja; PIC tidak mengakses milik anggota |
| Finance Verify | Finance berizin | Cocokkan transaksi mutasi; upload screenshot saja tidak cukup |
| Submit Abstract | Author/paper owner | Abstract Text+PDF valid; **tidak ada post-submit screening** |
| Claim review | Reviewer eligible | Seluruh tracks dapat dicari; klaim terbatasi slot/COI author/coauthor |
| Publish ACCEPT | Academic Committee | Recommendation reviewer bukan final; setelah publish unlock Full Paper tanpa finance gate |
| Upload Full Paper | Submitting author/authorized editor | Sesudah published ACCEPT, versioned private manuscript |
| Confirm Presented | Moderator assigned | QR attendance tidak membuat Presented |
| Assessment post-event | Assessor assigned | Reviewer hanya rekomendasi No/Minor/Major/Not Recommended |
| Decide revision / Final ACC | Publication/Academic Committee berwenang | No Revision tidak otomatis Published; revisi v2 tidak overwrite v1 |
| OJS export & handoff | Publication Team scoped outlet/event | Final ACC + snapshot; journal selection bukan Acceptance |
| Issue certificate | Certificate Officer/Admin dengan grant khusus | Presented → semua authors eligible; manual issue/batch |
| Grant/revoke service | Service Coordinator assigned | Scanner cuma fulfill assigned service |
| QR scan fulfill | Scanner Operator assigned | Server cek entitlement+slot dan duplicate |
| Public certificate verify | Public | Token acak + metadata minimal; bukan daftar data peserta |

Rincian resource/endpoint dan **52 test positif/negatif** dijabarkan dalam [18C-03A](18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md).

## 7. Private data dan file: jangan bocor dari Inertia, export dan URL

| Kelas | Contoh | Akses |
|---|---|---|
| Public | Agenda, speaker/partners yang diizinkan, verifikasi sertifikat dengan token | Tidak perlu login, tampilkan data minimal |
| Personal | Email, profil, own QR, own paper & invoice | User sendiri + staff berizin |
| Academic Restricted | Full Paper, revisi, coauthor data, review hasil | Author/Reviewer assigned/Committee menurut tahap |
| Confidential Academic | Reviewer identity bila reviewer blind, internal editor notes, unreleased decision, assessor internal comments | Committee dan pihak yang berwenang; jangan diekspos |
| Financial Restricted | Bukti transfer, bank receipt/credits, invoice, referensi transfer, receipt | Pembayar scoped + Finance; bank master Superadmin |
| Operational Restricted | QR token, operator device credential, check-in/fulfillment | Scope minimum untuk operasional |
| Secrets | APP_KEY, SMTP, DB password, OJS credential, API/device key | Environment/secret manager, **tidak pernah** di repo, email, logs, Inertia props |

**Upload:** allowlist tipe dan MIME, cek signature file sesuai konteks (proof: JPG/JPEG/PNG/PDF; Abstract: PDF; Full Paper: jenis berkas menunggu kebijakan), ukuran maksimum dari setting, filename acak, checksum, private disk, download melalui authenticated controller + Policy. Bila pakai temporary URL, **policy diperiksa sebelum URL diterbitkan** dan URL harus pendek masa berlaku. Validasi XML OJS harus mencegah XML External Entity, XSS/unsafe output, dan rahasia review ikut ekspor.

**Khusus reviewer:** desain publikasi/anonimitas reviewer harus menyesuaikan kebijakan konferensi; jangan memberi akses ke komentar Reviewer lain maupun editor-only notes. Reviewer Index boleh menyediakan informasi metadata abstrak yang diperlukan untuk claim dan cek konflik, tetapi metadata privat, full paper sebelum berhak, dan keputusan belum published tetap terlindungi.

**Export OJS Proceedings atau jurnal:** hanya canonical scholarly metadata dan naskah final yang memang boleh ditransfer. Bukan data pembayaran, QR, scoring/komentar confidential, rekening bank, data kontak tak perlu, atau log internal. Versi OJS dan target nyata belum dipilih; jangan klaim kompatibilitas universal.

## 8. QR, operator dan offline mode

Aturan bisnis yang diterima: satu QR per person, entitlement vs attendance vs Presented berbeda, operator hanya assigned counter, server mencegah confirmed fulfillment ganda. **Offline Android/PWA belum final.**

**Bila kelak offline dipilih (USULAN):** device registration, device credential scoped+revocable, pending queue dengan data seminimal mungkin, replay/duplicate detection melalui idempotency key, sinkronisasi server. **Pending Sync bukan Confirmed**. Dua smartphone sepenuhnya offline tidak bisa menjamin single-use lintas device saat tidak saling berkomunikasi. Kehilangan perangkat → revoke credential dan pastikan data lokal terlindungi.

**Jangan** mengembangkan perangkat scanner, IAM token khusus, atau database sync besar sekarang hanya karena desain ini menuliskan kemungkinan tersebut.

## 9. Audit, backup dan operasional aman yang ringan

| Peristiwa | Log minimum (USULAN) |
|---|---|
| Login gagal berulang / reset | Waktu, account ref, hasil, tanpa password/token |
| Assign/revoke role atau PIC | Actor/time, event, scope, perubahan, alasan bila koreksi |
| Superadmin bank update | Actor/time, ringkasan perubahan nomor rekening tersamarkan |
| Finance Verified/Correction | Invoice, actor, timestamp, alasan/mutasi yang diverifikasi |
| Reviewer claim / final decision publish | Paper, actor, review claim/decision, publication timestamp |
| Moderator Presented | Session/Paper, actor/time, koreksi beralasan |
| Revision / Final ACC | Paper, version, actor, decision/reason |
| OJS export/handoff | Outlet/version, batch, actor, checksum, downstream ref |
| Certificate issue/reissue/revoke | Batch/recipient, actor, version/reason |
| Service fulfill/sync conflict | Service slot/operator/device, confirmed/pending/conflict |

**Go-live hygiene (rekomendasi dasar):** HTTPS, APP_DEBUG=false, secrets di lingkungan aman, file privat, akses database terbatas, perlindungan terhadap mass-assignment, dependency/CI checks, backup terpisah yang terenkripsi serta uji restore, pencatatan error tanpa bocor email/nomor rekening/QR raw token. Jadwal backup dan masa retensi data **belum kebijakan final**; perlu ditetapkan panitia sebelum produksi. Tidak perlu SIEM/microservice security berbayar untuk MVP.

## 10. Threat model ICHES — realistis dan dapat diuji

| Ancaman | Dampak | Penanganan |
|---|---|---|
| Tebak invoice/paper ID orang lain | Kebocoran data | Per-resource Policy dan event/owner scoped query |
| PIC mengubah institution_id request | Mengakses tenant lain | Scope dari assignment terpercaya, bukan payload |
| Reviewer mereview diri (coauthor tanpa akun) | COI akademik | Cocokkan linked user dan normalized email/author metadata; jalur konflik manual |
| Inertia serialize model lengkap | Data sensitif sampai browser | Minimal typed props yang sesuai scope |
| Upload file menyamar PDF/gambar | Malware, XSS | Validate type/signature, private storage, force attachment bila diperlukan |
| Screenshot transfer dianggap Verified | Hak tidak sah | Finance cocokkan mutasi bank |
| Session hijack/password stuffing | Account takeover | Secure session, throttle, reset, MFA staff jika diadopsi |
| Role multi-capability tidak scoped | Confused deputy | Role+owner+event+business gate per request |
| Scanner offline replay/double scan | Layanan ganda | Unique confirmed, idempotency, server conflict logic |
| Publication XML salah versi atau bocor notes | Kegagalan import dan data leak | Adapter profile + validation & minimal export |
| Public verify token enumerated | Data peserta bocor | Random opaque tokens, minimal fields, rate limit |

## 11. Minimal security MVP: jangan dibuat rumit

~~~text
SEC-S01 One-account Laravel session auth, verified email, reset, throttle
SEC-S02 Event-scoped RBAC + resource policies (institution/session/outlet/owner)
SEC-S03 Superadmin-only one bank settings; Finance-only Verify action
SEC-S04 Reviewer COI & committee Publish ACCEPT authority
SEC-S05 Full Paper private/versioned; Presentation/Final ACC separated
SEC-S06 Co-Host institutional vs personal invoice isolation
SEC-S07 QR/entitlement/attendance/presented separation
SEC-S08 Publication outlet/export scoped; selected journal != accepted
SEC-S09 Audit sensitive actions, private file downloads, backups
SEC-S10 Negative security UAT including cross-role and cross-institution
~~~

**Tidak disarankan sebagai syarat MVP:** SSO wajib, enterprise IAM, microservices, sistem KYC identitas nasional, payment gateway, barcode berisi data sensitif, blockchain certificate, automatic auto-refund, double sign-off rekening. MFA untuk staf sensitif **direkomendasikan**, namun tetap perlu keputusan; tidak boleh dipakai sebagai alasan menghapus keamanan dasar.

## 12. Review Gate — status rekomendasi, bukan final

| Kode | Rekomendasi yang perlu Anda tinjau | Status |
|---|---|---|
| SEC-01 | Email/password + session Laravel, verified email & password reset; Magic Link alternatif | DRAFT |
| SEC-02 | Satu User multi-role; Policies server dengan event/institution/paper/session/outlet scopes | DRAFT |
| SEC-03 | Bank Settings tetap Superadmin-only **tanpa approval ganda** | KEBIJAKAN FINAL, penerapan teknis DRAFT |
| SEC-04 | MFA Superadmin/Finance/Publication staff sensitif bila tersedia | TERBUKA |
| SEC-05 | Detail anonimitas reviewer dan akses reviewer notes diselaraskan kebijakan konferensi | TERBUKA |
| SEC-06 | Private storage, scoped file downloads, no financial data in OJS export | DRAFT |
| SEC-07 | Mode scanner online/native/offline, device credentials, conflict sync | TERBUKA |
| SEC-08 | Audit minimal, backup enkripsi dan uji restore, retention ditetapkan panitia | DRAFT |
| SEC-09 | 52 scenario Security UAT di lampiran, belum eksekusi | DRAFT |
| SEC-10 | Lanjut ke 18C-04 Frontend/Dashboard Architecture setelah review | REKOMENDASI |

**Tahap berikutnya:** 18C-04 Frontend & Dashboard Architecture. **18C-03 BELUM FINAL dan tidak berarti GO CODING.** Sekalipun arah arsitektur diterima, detail login/MFA/PK/database/retensi dan offline scanner tetap perlu diputuskan.

---

[18C-03A — Permission Matrix & 52 UAT](18C-03A-DRAFT-PERMISSION-MATRIX-SECURITY-UAT.md) · [18C-02 Backend](18C-02-DRAFT-LARAVEL-BACKEND-ARCHITECTURE-BUSINESS-LOGIC.md) · [Register Keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md) · [README](../README.md).
