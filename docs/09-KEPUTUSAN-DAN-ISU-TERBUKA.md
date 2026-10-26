# 09 — Register Keputusan ICHES UNISYA 2027

> **Tujuan:** agar setiap diskusi dan presentasi berikutnya dimulai dari keputusan yang tertulis, bukan mengulang dari nol.  
> **Tanggal kompilasi:** 26 Oktober 2026.  
> **Label:** **DISETUJUI** (pemilik produk memilih eksplisit), **BASELINE LAMA** (repositori terdahulu, belum otomatis ditetapkan baru), **USULAN**, **TERBUKA**.

## Rebaseline TOR dari Warek I — sumber acara, bukan keputusan implementasi

Dokumen sumber `TOR ICHES 6 UNISYA 2027.docx` (bagian E/F/H/K/L/N) telah dianalisis. **Lima** rangkaian acara dicantumkan (Conference, Pembinaan Riset/PKM, MoU, Pengabdian Internasional, Studi Destinasi); tanggal **5–6 Januari 2027**; delapan subtema; **sembilan kategori biaya**; serta jadwal submission mulai **1 Desember 2026**. Lihat [Analisis TOR](10-ANALISIS-TOR-DAN-REBASELINE-2027.md).

- Status `TOR-FACT`: tercantum dalam TOR, bukan bukti seluruh detail telah disetujui menjadi konfigurasi aplikasi final.
- Status `NEW-*`: keputusan pemilik produk mengenai **mekanisme aplikasi**, tetap berlaku sepanjang tidak bertentangan TOR.
- **NEW-005 Diperluas oleh TOR**: tiga jalur yang semula dibahas tetap ada, ditambah **Pembinaan Riset dan PKM** serta **Studi Destinasi Berkelanjutan** karena keduanya rangkaian resmi TOR. Struktur enrollment vs bundle fee **belum final**.
- Potensi konflik TOR: Full Paper 23 Desember mulai **sebelum** window payment 24 Desember, dan MoU/Pembinaan beririsan dengan Panel pada sore 5 Januari. Tandai untuk keputusan pimpinan, jangan diam-diam mengasumsikan jadwal berubah.
- TOR **tidak menetapkan** batas kata, PDF max size, default reviewer, unit tarif Co-Host maupun bundle MoU. Usulan lama pada area ini **belum berubah status menjadi disetujui**.

## A. Keputusan yang sudah disetujui (dalam diskusi)

| ID | Topik | Kebijakan | Status |
|---|---|---|---|
| NEW-001 | Cakupan | ICHES UNISYA 2027 single-event, repository baru terpisah | DISETUJUI |
| NEW-002 | Identitas | Satu user satu akun/profil dan QR personal | DISETUJUI |
| NEW-003 | Login peserta | Email OTP / Magic Link tanpa password | DISETUJUI |
| NEW-004 | Profil | WhatsApp; foto max 2 MB; modal crop 1:1 | DISETUJUI |
| NEW-005 | Kegiatan | Satu orang dapat ikut Conference, MoU dan ICD bersamaan; **TOR menambahkan Pembinaan Riset/PKM dan Studi Destinasi** sebagai rangkaian kegiatan | DISETUJUI + TOR-FACT |
| NEW-006 | MoU | Satu pendaftaran MoU per institusi | DISETUJUI |
| NEW-007 | Delegasi | PIC mengusulkan, panitia verifikasi tiap delegasi | DISETUJUI |
| NEW-008 | Delegasi aktif | Undangan email Magic Link setelah verifikasi, akun sendiri | DISETUJUI |
| NEW-009 | Hak MoU | MoU-only bukan Conference/ICD otomatis | DISETUJUI |
| NEW-010 | QR | QR pribadi dipakai lintas layanan, hak dicek backend | DISETUJUI |
| NEW-011 | Konsumsi | Satu porsi per orang per shared slot, lintas kegiatan | DISETUJUI |
| NEW-012 | Rights | Default + override DEFAULT/GRANT/REVOKE per person/service | DISETUJUI |
| NEW-013 | Otoritas rights | Admin dan Service Coordinator dapat ubah default maupun individual dalam scope layanan | DISETUJUI |
| NEW-014 | UI scanner | Model C hybrid: pos fisik tetap, scanner fleksibel berdasar penugasan | DISETUJUI |
| NEW-015 | Absensi | Model C hybrid: umum ditambah check-in khusus kegiatan terpilih | DISETUJUI |
| NEW-016 | Submitting Author | Akun pengelola paper tidak harus first author; mengelola sertifikat semua author | DISETUJUI |
| NEW-017 | Abstract | Teks formulir **dan** file wajib | DISETUJUI |
| NEW-018 | Abstract file | PDF saja | DISETUJUI |
| NEW-019 | Presenter payment | Setelah ACCEPT | DISETUJUI |
| NEW-020 | Sertifikat author | Seluruh penulis memperoleh Presenter Certificate jika paper Presented | DISETUJUI |
| NEW-021 | Sertifikat distribution | URL publik unik, PDF per penulis, bisa buka tanpa login | DISETUJUI |
| NEW-022 | Coauthor | Tidak wajib akun; pengelola paper membagikan sertifikat | DISETUJUI |
| NEW-023 | Sertifikat publishing | Publikasi massal oleh panitia, bukan otomatis Moderator | DISETUJUI |
| NEW-024 | Admin UI | shadcn-vue / Shadcn Vue Admin, gaya putih–hijau polos, tanpa gradient | DISETUJUI |
| NEW-025 | Rujukan repo | Audit dan seleksi hasil repository lama, bukan membangun dari asumsi | DISETUJUI |

## B. Baseline repo lama yang dapat dipakai, belum semuanya dikunci ulang

| ID | Area | Referensi dan implikasi | Status |
|---|---|---|---|
| OLD-001 | Scholarly metadata | Satu paper ID, contributor order, one corresponding author, mononym, ROR manual fallback | BASELINE LAMA |
| OLD-002 | Review abstrak | Single-anonymous; default 1 reviewer configurable | BASELINE LAMA |
| OLD-003 | Revisi | 1 siklus normal; pengecualian otoritas akademik | BASELINE LAMA |
| OLD-004 | Keputusan | Reviewer advisory; Academic Authority final | BASELINE LAMA |
| OLD-005 | LoA | LoA setelah ACCEPT, tidak sama dengan publication acceptance | BASELINE LAMA, selaras keputusan |
| OLD-006 | Pembayaran | Transfer manual + verifikasi Finance | BASELINE LAMA |
| OLD-007 | Full paper | Setelah ACCEPT/LoA; pre-event validation dan penilaian pascapresentasi | BASELINE LAMA |
| OLD-008 | Publikasi | OJS/prosiding handoff, ICHES bukan penerbit jurnal | BASELINE LAMA |
| OLD-009 | QR | Scan lookup != attendance; status Present terpisah | BASELINE LAMA, selaras keputusan |
| OLD-010 | Publikasi sertifikat | Status Generated/Issued/Revoke, verifikasi unik, koreksi versi | BASELINE LAMA, dimodifikasi NEW-020–023 |

**Konflik:** old presenter certificate (hanya yang tampil), old package registration dan old password onboarding **tidak dipakai** apabila bertentangan dengan NEW-*.

## C. Usulan desain yang tidak boleh diam-diam menjadi keputusan

| ID | Usulan | Status |
|---|---|---|
| PROP-001 | Wizard 4 langkah Paper, Authors, Abstract, Submit | USULAN |
| PROP-002 | Kata abstrak 200–300; keywords 3–5 | USULAN |
| PROP-003 | File PDF abstrak max 3 MB, satu halaman A4 | USULAN |
| PROP-004 | Reviewer abstrak 2 default (berbeda dengan baseline lama 1) | USULAN |
| PROP-005 | Check-in umum satu kali per orang tiap hari | USULAN |
| PROP-006 | Conference Kit maksimal sekali per orang sepanjang event | USULAN |
| PROP-007 | ZIP unduh semua sertifikat penulis | USULAN |
| PROP-008 | Kode URL pendek `/c/<acak>` | USULAN TEKNIS |
| PROP-009 | Palet admin primer #176747, background #F7F9F7 | USULAN VISUAL |
| PROP-010 | Panel Next Action dan penugasan scanner dengan pemilih pos/mode | USULAN DETAIL UX |

## TOR-specific confirmations untuk Warek I

| Ref | Hal yang belum jelas di TOR | Prioritas |
|---|---|---|
| TOR-Q01 | Co-Host maksimal **5**: per institusi atau total? | Tinggi |
| TOR-Q02 | IDR 450.000 Co-Host dan IDR 1.000.000 bundle: **per orang / per institusi / per paper**? | Tinggi |
| TOR-Q03 | Satu orang ikut Conference + bundle: penagihan dan hak layanan bagaimana? | Tinggi |
| TOR-Q04 | MoU/Pembinaan **13.00–17.15** vs Panel **13.15–16.30** pada 5 Jan: penugasan/kehadiran rangkap? | Tinggi |
| TOR-Q05 | Full Paper buka 23 Des sedangkan pembayaran 24 Des: setuju **alur paralel**? | Tinggi |
| TOR-Q06 | Review harus selesai 22 Des: apakah 1 reviewer default cukup? Aturan revisi? | Tinggi |
| TOR-Q07 | Day 2: daftar transport/rombongan, kelompok PKM, kuota, titik check-in? | Sedang |
| TOR-Q08 | Sertifikat untuk Pembinaan, MoU, PKM, dan Destinasi? | Sedang |
| TOR-Q09 | Beberapa kombinasi tarif peserta tidak dicantumkan dalam TOR | Sedang |

## D. Isu yang perlu keputusan pimpinan/panitia

| Prioritas | Keputusan terbuka | Penanggung jawab yang disarankan |
|---|---|---|
| Tinggi | Batas kata, keywords, PDF size, bahasa submission, template abstrak | Komite Akademik |
| Tinggi | Reviewer default 1 atau 2; durasi review/revisi; rubrik | Komite Akademik |
| Tinggi | Tarif Presenter per orang/per paper; Participant FREE/PAID; waiver | Panitia & Keuangan |
| Tinggi | Tanggal dan struktur hari/sesi acara | Panitia Program |
| Tinggi | Full-paper requirement & penilaian pascapresentasi; publikasi prosiding/jurnal | Komite Akademik |
| Sedang | Kuota delegasi MoU, bukti keabsahan delegasi/signatory | Koordinator MoU |
| Sedang | Souvenir MoU per orang/per institusi; kit entitlement | Koordinator Fasilitas |
| Sedang | Syarat Completed ICD, sertifikat MoU/ICD | Koordinator terkait |
| Sedang | Kebijakan Presenter online/makeup/no-show | Panitia Akademik |
| Sedang | Otorisasi akun petugas scanner dan moderator | Admin/Tim Teknis |
| Sedang | Domain publik, template sertifikat dan pejabat penandatangan | Panitia/UNISYA |

## E. Aturan perubahan keputusan

Setiap keputusan baru dicatat dengan: ID, tanggal, pemutus, keputusan, alasan ringkas, modul yang terdampak, dan status. Jika keputusan mengganti aturan sebelumnya, tulis **supersedes ID lama**. Jangan menghapus jejak perubahan substantif pada dokumen akademik. Perubahan administrasi fasilitas boleh tetap CRUD sederhana tanpa modul audit yang berlebihan.

**Dokumentasi bukan tanda aplikasi telah dibangun.** Implementasi, pengujian, dan UAT memerlukan otorisasi pengembangan terpisah.

## F. Agenda pembahasan setelah presentasi

1. Kunci parameter akademik yang masih terbuka menggunakan referensi repo lama.
2. Finalkan registrasi independen vs struktur tarif Presenter/Participant/MoU/ICD.
3. Finalkan hak fasilitas, batas kit, souvenir dan kuota.
4. Tinjau wireframe pengguna dan Backoffice berdasarkan keputusan pimpinan.
5. Kunci PRD, arsitektur final dan *acceptance criteria* sebelum coding.
