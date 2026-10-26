# 08 — Audit Repository Lama dan Matriks Pemanfaatan

> **Sumber yang diaudit:** [akhmadafnan/international-conference-platform — develop](https://github.com/akhmadafnan/international-conference-platform/tree/develop), pemeriksaan statis dokumentasi dan kode pada 26 Oktober 2026.  
> **Batas audit:** tidak menjalankan server, migrasi database, atau test runtime; laporan GREEN diambil dari dokumen proyek lama, bukan diuji ulang. Repo lama **tidak diubah**.

## Versi dan status pekerjaan lama

Dokumen [CURRENT_STATE](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/ai-context/CURRENT_STATE.md) mencatat **Phase 02 Registration + Payment CLOSED_GREEN**, sementara Phase 03 belum diotorisasi. Di `develop`, ditemukan kode login/password, registration intent, packages, activities, payment, Event Pass, verification token, dan file storage. **Tidak ditemukan** model/controller/migration fungsional untuk Submission, Reviewer Assignment, Academic Decision, Moderator Session, atau Certificate Publication pada tree yang diperiksa.

Dokumen akademik lama bersifat matang sebagai **spesifikasi**, bukan bukti bahwa fitur akademiknya selesai.

## Reuse decision matrix

| Domain | Bukti repo lama | Keputusan baru |
|---|---|---|
| Laravel + Vue/Inertia + shadcn-vue | composer.json, resources/js | **REUSE APPROACH**; satu stack modular monolith |
| UUID, storage privat, role/permission | migration, policies, file system baseline | **REUSE PATTERN**; otorisasi perlu scope layanan |
| Series + Editions | models ConferenceSeries/ConferenceEdition | **REPLACE DOMAIN**; ICHES 2027 single event |
| Participation Packages | ParticipationPackage, Registration, entitlements | **REDESIGN**; multi-keikutsertaan independen |
| Account password | Fortify CreateNewUser + pages/auth | **REPLACE PARTICIPANT UX** dengan OTP/Magic Link |
| Profile registration | User, EditionMembership | **REDESIGN** dengan WA, foto crop dan satu personal QR |
| Payment manual | Payment actions, VerifyPayment | **REUSE LOGIC selectively**; presenter hanya pasca-ACCEPT |
| Event Pass QR | GeneratedDocument, VerificationToken, EventPassQrController | **REUSE PRINCIPLE**, adaptasi identitas lintas kegiatan |
| Abstract metadata | SUBMISSION_SCHOLARLY_METADATA_CONTRACT_V1.md | **REUSE CONTRACT**, tambahkan text+PDF wajib |
| Review | V1_ACADEMIC_REVIEW_BASELINE.md | **REUSE BASELINE**, bukan engine generic masa depan |
| Full paper / publication | PRE_PHASE02_PRODUCT_REBASELINE | **SIMPLIFY/REUSE POLICY**, tidak menggantikan OJS |
| MoU dan ICD | lightweight concept, aktivitas paket | **NEW DESIGN**: institusi, PIC, delegasi, ICD terpisah |
| QR scanner / service rights | QR lookup baseline dan per-activity entitlement | **NEW DOMAIN**: counter, slot, rights overrides, fulfillment |
| Certificate verification | certificate governance spec; generic documents | **REUSE PRINCIPLE**, **REPLACE ELIGIBILITY** semua author |
| Admin UI | shadcn-vue/Tailwind foundation | **REUSE VISUAL PATTERN**; putih hijau, tanpa template Next.js |

## Konflik keputusan lama yang harus dihindari

**1. Payment sebelum abstrak vs setelah ACCEPT.** Dokumen lifecycle historis mengandung alur payment-before-review, tetapi dokumen [Pre-Phase02 Rebaseline](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/governance/PRE_PHASE02_PRODUCT_REBASELINE.md) secara eksplisit menggantinya dengan **Abstract → ACCEPT → payment obligation**. Itulah baseline ICHES baru.

**2. Reviewer count:** [V1 Academic Review](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/requirements/v1/V1_ACADEMIC_REVIEW_BASELINE.md) menulis **default 1** configurable. Diskusi baru pernah memberi **usulan 2**, tetapi belum disetujui. Jangan menyebut 2 sebagai keputusan.

**3. Double-anonymous full paper:** [REVIEW_STAGE_BASELINE](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/requirements/REVIEW_STAGE_BASELINE.md) menandai diri **bukan V1 implementation requirement**. Accelerated V1 memilih alur penilaian pasca-presentasi yang lebih sederhana. Jangan langsung membuat engine review generic ganda.

**4. Presenter Certificate:** [Certificate Governance lama](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/requirements/CERTIFICATE_GOVERNANCE_BASELINE.md) mensyaratkan masing-masing penerima Presented. Keputusan pemilik produk terbaru **mengganti aturan itu**: ketika **paper** Presented, **seluruh author** eligible untuk Presenter Certificate. Redaksi sertifikat harus merefleksikan fakta kepenulisan, bukan menyatakan semua author tampil langsung.

**5. Pendaftaran satu paket:** `CreateRegistrationIntentAction` lama membatasi satu registration per edition membership dan menurunkan activity dari package. Keputusan baru memisahkan Conference, MoU, dan ICD; satu akun boleh mengikuti semua tanpa paket yang mengunci pilihan.

## Aturan hierarki sumber kebenaran

1. **Keputusan eksplisit terbaru pemilik produk ICHES baru**.
2. **Dokumen lama yang secara eksplisit menggantikan (supersede) dokumen sebelumnya**.
3. Dokumen **AUTHORITATIVE V1 BASELINE** dan kontrak akademik lama sepanjang tidak bertentangan.
4. Dokumen approved historis sebagai konteks, bukan otomatis requirement MVP.
5. Usulan asisten sebagai **usulan**, bukan keputusan.

## Strategi implementasi yang disarankan

**Selective re-engineering:** adopsi kontrak metadata, pola keamanan, validasi pembayaran, arsitektur antarmuka; rancang ulang model bisnis identity/participations/MoU/ICD/service rights. Jangan fork seluruh kode tanpa audit karena struktur multi-edition dan password akan menjadi beban.

## Source pointers

- [Decision Register lama](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/governance/DECISION_REGISTER.md)
- [Current State lama](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/ai-context/CURRENT_STATE.md)
- [Academic V1](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/requirements/v1/V1_ACADEMIC_REVIEW_BASELINE.md)
- [Metadata Contract](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/metadata/SUBMISSION_SCHOLARLY_METADATA_CONTRACT_V1.md)
- [Certificate Baseline lama](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/requirements/CERTIFICATE_GOVERNANCE_BASELINE.md)
- [Pre-Phase02 Product Rebaseline](https://github.com/akhmadafnan/international-conference-platform/blob/develop/docs/governance/PRE_PHASE02_PRODUCT_REBASELINE.md)

Semua keputusan pemanfaatan di dokumen ini adalah rekomendasi migrasi desain, belum pekerjaan pemindahan kode.
