# Paparan Rancangan Sistem ICHES UNISYA 2027

> **Bahan presentasi untuk Wakil Rektor I — 26 Oktober 2026**  
> **Status:** konsep produk dan alur operasional untuk masukan/arah kebijakan; **belum** aplikasi operasional, belum pengesahan formal.
>
> **Gagasan utama:** satu portal konferensi, satu akun, satu QR pribadi, **lima rangkaian kegiatan menurut TOR**, dan layanan panitia yang fleksibel.

> **KEPUTUSAN TERBARU:** *Administrative Screening* setelah Submit dihapus. Author wajib lolos validasi form **dan** server; naskah yang resmi Submitted langsung tersedia di Reviewer Abstract Index untuk self-claim. **Co-Host Rp3 juta per institusi/maksimal 5 peserta sudah final**. Mekanisme menghubungkan anggota via PIC + undangan email/Magic Link adalah **rekomendasi desain**, belum keputusan final. Lihat [rancangan 13 — linking Co-Host dan submission tanpa screening](13-LINK-COHOST-DAN-SUBMISSION-TANPA-SCREENING.md).

## KOREKSI: SATU PAKET Rp1 JUTA, BELI SENDIRI DI DASHBOARD PESERTA

**Paket Program Internasional bukan fitur Co-Host.** User yang tertarik memilihnya sendiri di Dashboard pribadi dengan **satu pembayaran Rp1 juta untuk empat program**: International MoU, Upgrading Kasubdit Penelitian-PKM Kemenag RI, PKM Internasional, dan Destinasi Internasional Tumpak Sewu–Teras Semeru. **Bukan Rp1 juta × 4 program**; PIC Co-Host **tidak mengelola tagihan pribadi anggota**.

```mermaid
flowchart TD
  P["PIC Co-Host"] --> C["Institusi / Roster Conference 1–5 / Invoice Rp3 juta"]
  U["Peserta login, Co-Host ataupun reguler"] --> D["Dashboard pribadi"]
  D --> O["Pilih Paket Program Internasional"]
  O --> T["SATU tagihan user Rp1 juta"]
  T --> A["Empat akses: MoU + Upgrading + PKM + Destinasi"]
```

Lihat [alur user lengkap](15-PAKET-INTERNASIONAL-DASHBOARD-PESERTA.md).

## ATURAN CO-HOST FINAL: CONFERENCE ONLY + PAKET PENDAMPING PER ORANG

**Co-Host Rp3.000.000 berlaku sekali PER INSTITUSI untuk maksimal 5 anggota jalur paper International Conference.** Manfaat finansialnya **tidak mencakup** International PKM, Pembinaan, MoU, maupun Studi Destinasi. Bagi peserta yang memilih paket program pendamping tersebut, berlaku **Rp1.000.000 PER ORANG** secara terpisah. **Institusi yang menjadi Co-Host berhak dicantumkan sebagai institusi kolaborator penyelenggara ICHES 2027** pada identitas resmi acara; rincian logo dan media pencantuman perlu disepakati panitia.

```mermaid
flowchart TD
  I["Institusi Co-Host ICHES 2027"] --> C["Rp3 juta / institusi: khusus paper Conference"]
  I --> R["Roster maks. 5 peserta Conference"]
  I --> B["Pengakuan institusi sebagai Co-Host"]
  R --> A["User punya akun pribadi"]
  A --> X{"User sendiri memilih paket?"}
  X -->|Ya| F["Dashboard pribadi: satu tagihan Rp1 juta untuk empat akses"]
  X -->|Tidak| N["Tidak ada tagihan paket Rp1 juta"]
  B --> P["Nama dan logo pada kanal resmi, sesuai persetujuan"]
```

**Contoh:** Institusi A memiliki invoice Co-Host **Rp3 juta tetap**. Jika tiga anggotanya masing-masing membeli **satu Paket Program Internasional Rp1 juta dari Dashboard pribadi**, ketiga tagihan adalah **milik user**, tidak ditambahkan menjadi invoice institusi Rp6 juta. Satu paket Rp1 juta sudah mencakup empat akses. **Kuota lima orang bukan kuota lima paper**.

[Buka diagram dan aturan lengkap](14-CAKUPAN-COHOST-DAN-PROGRAM-PENDAMPING.md).

---

## KEPUTUSAN TERBARU UNTUK PRESENTASI WAREK I

| Keputusan | Rumusan terbaru |
|---|---|
| Co-Host | **Rp3.000.000 per institusi, satu tagihan mencakup maksimal lima peserta** (koreksi atas Rp450.000 pada TOR) |
| Agenda paralel | MoU/Pembinaan dan Panel Session boleh berjalan bersamaan; kehadiran per kegiatan tidak disamakan, tidak perlu optimasi jadwal |
| Reviewer | Sejak submission awal 1 Desember, abstract **valid disubmit** masuk **Reviewer Index**; semua reviewer mencari/filter topik dan melakukan **Claim** mandiri |
| Full Paper | Tombol unggah terbuka bagi paper yang **ACCEPTED dan keputusannya diumumkan ke author**; **REJECTED** tidak memperoleh akses |
| Pembayaran | Presenter bayar **setelah ACCEPT**, tetapi **tidak menjadi syarat membuka unggah Full Paper** |

**Kebijakan Co-Host sudah dikunci:** Rp3.000.000 dibayarkan **sekali per institusi** dan mencakup **maksimal lima peserta**, bukan ditagihkan kepada masing-masing peserta. **Paket tersebut hanya untuk paper International Conference; biaya paket program pendamping adalah Rp1 juta per orang bagi pesertanya.** Jumlah paper yang tercakup, serta jumlah reviewer per paper, masih perlu keputusan komite. Jadwal TOR 22 Desember untuk pengumuman masih menjadi **milestone**, tetapi bukan gate teknis untuk membuka review atau full paper. Tanggal 23–28 Desember untuk Full Paper tetap dicatat sebagai **rencana pada TOR**, bukan syarat unlock aplikasi.

```mermaid
flowchart TD
  A["Abstrak masuk 1–20 Desember"] --> B["Validasi form + server saat Submit"]
  B -->|Valid| C["Reviewer Abstract Index"]
  C --> D["Search / Filter Topik"]
  D --> E["Reviewer Claim Paper"]
  E --> F["Review Submitted"]
  F --> G["Komite Akademik putuskan"]
  G --> H["Keputusan diumumkan ke Author"]
  H -->|ACCEPTED| I["Full Paper Unlock + LoA"]
  H -->|REJECTED| J["Jalur Presenter Closed"]
  I --> K["Full Paper Upload"]
  I --> L["Pembayaran setelah ACCEPT"]
  K --> M["Jadwal & Presentasi"]
  L --> M
```

Catatan: Reviewer **tidak diberi larangan topik A/B/C** melalui pengaturan akun. Pemeriksaan minimal saat claim: reviewer terautentikasi, paper masih tersedia, bukan paper sendiri, dan slot review belum penuh. Koordinator Akademik dapat membatalkan claim yang tidak dikerjakan tanpa aturan deadline otomatis yang berlebihan. Keputusan final tetap di Komite Akademik.

**➡️ [Dokumen visual dan ketentuan lengkap](11-KEPUTUSAN-OPERASIONAL-26-OKT-2026.md)**

---



## REBASELINE: Temuan TOR Warek I (dibaca terlebih dahulu)

**Dokumen sumber:** `TOR ICHES 6 UNISYA 2027.docx` (9 halaman). **Acuan acara:** 5 Januari 2027 Conference hybrid; 6 Januari 2027 Pengabdian dan Destinasi onsite. Tema resmi **Islamic Values in a Changing World: Education, Accountability, and Social Resilience for Inclusive Development**.

**TOR memuat lima rangkaian kegiatan, bukan hanya tiga jalur awal:**

```mermaid
flowchart TD
  A["ICHES 2027 — UNISYA"] --> B["International Conference / 5 Jan"]
  A --> C["Pembinaan Riset dan PKM / 5 Jan"]
  A --> D["International MoU / 5 Jan"]
  A --> E["Pengabdian Internasional / 6 Jan"]
  A --> F["Studi Destinasi Berkelanjutan / 6 Jan"]
  B --> G["Satu akun + QR personal"]
  C --> G
  D --> G
  E --> G
  F --> G
```

**Jadwal kunci:** Abstrak 1–20 Desember; hasil 22 Desember; full paper 23–28 Desember; registrasi/pembayaran 24–28 Desember; prosiding ditargetkan 28 Januari 2027.

**Konflik kebijakan/operasional yang wajib dijelaskan pada Warek I:**
- **TELAH DIPUTUSKAN:** Full Paper terbuka saat keputusan ACCEPT resmi diumumkan ke author, bukan berdasarkan tanggal atau verifikasi pembayaran. Tanggal Full Paper pada TOR menjadi milestone acara.
- **TELAH DIPUTUSKAN:** MoU/Pembinaan dan Panel berjalan paralel bila diperlukan; tidak perlu fitur khusus optimasi benturan. Kehadiran tiap kegiatan dan bukti presentasi tetap terpisah.
- **Koreksi:** tarif Co-Host **Rp3.000.000**, bukan **Rp450.000** yang tertulis di TOR; batas **lima peserta PER INSTITUSI**. **Unit pembayaran sudah final: satu tagihan Rp3 juta PER INSTITUSI yang mencakup paling banyak lima peserta.** **Bundle Rp1.000.000 per orang** berlaku terpisah bagi peserta program pendamping, termasuk bila orang tersebut anggota Co-Host.
- Research Coaching/Pembinaan Riset dan **Studi Destinasi** bukan lagi modul opsional dalam cakupan acara menurut TOR; desain pendaftaran tetap sederhana.

**➡️ [Audit TOR dan matriks perubahan selengkapnya](10-ANALISIS-TOR-DAN-REBASELINE-2027.md)**

> **Catatan pembacaan:** Diagram produk pada bagian di bawah telah disesuaikan agar menampilkan **lima rangkaian dari TOR**. Detail pendaftaran, kuota, dan paket biaya tiap kegiatan masih menunggu keputusan yang belum tertulis pada TOR.

---

## 1. Masalah yang ingin diselesaikan

Penyelenggaraan konferensi internasional mencakup registrasi peserta, seleksi abstrak, institusi mitra, delegasi, kegiatan pengabdian, pembayaran, distribusi fasilitas, absensi, dan sertifikat. Jika dikelola terpisah dengan banyak form/file, panitia sulit memperoleh data yang konsisten; peserta pun mengulang identitas.

**Solusi yang diajukan:** satu platform ICHES 2027 dengan dashboard sesuai tugas dan satu sumber data identitas. Sistem tidak menggantikan OJS, sistem keuangan bank, atau perjanjian hukum.

## 2. Peta produk dalam satu gambar

```mermaid
flowchart TD
  P["PORTAL ICHES 2027"] --> U["Satu Akun + Personal QR"]
  U --> C["International Conference"]
  U --> M["International MoU"]
  U --> I["Pengabdian Internasional"]
  U --> R["Pembinaan Riset dan PKM"]
  U --> D["Studi Destinasi Berkelanjutan"]
  C --> A["Presenter / Participant + Academic Submission"]
  M --> B["PIC Institusi + Delegasi"]
  I --> O["BACKOFFICE"]
  R --> O
  D --> O
  A --> O
  B --> O
  O --> S["Scanner QR / Fasilitas / Absensi"]
  O --> T["Sertifikat Publik + URL Unik + PDF"]
```

**Prinsip:** keikutsertaan di lima rangkaian bisa terkait kategori pembayaran/paket, tetapi kehadiran dan hak fasilitas masing-masing dicatat dengan benar. Satu personal QR berlaku lintas kegiatan.

## Co-Host: satu institusi, satu tagihan, lima peserta maksimal

```mermaid
flowchart TD
  A["Institusi Co-Host"] --> B["Registrasi Co-Host institusional"]
  B --> C["Daftarkan 1 sampai 5 peserta"]
  C --> D["Satu tagihan Rp3.000.000 kepada institusi"]
  D --> E["Upload bukti pembayaran"]
  E --> F["Finance verifikasi"]
  F --> G["Status pembayaran Co-Host institusi"]
  G --> H["Kelima peserta terhubung ke institusi + QR pribadi"]
```

**Contoh:** Institusi A membawa tiga peserta = **satu tagihan Rp3.000.000**; Institusi B membawa lima peserta = **satu tagihan Rp3.000.000**. Menambah peserta keenam **tidak dibenarkan dalam paket lima orang**, dan tidak boleh otomatis menghasilkan tagihan Co-Host kedua. Paket Co-Host **khusus jalur paper International Conference**; jumlah paper dan fasilitas yang termasuk masih perlu perincian panitia. Program pendamping dikenai **Rp1 juta per peserta**, tidak termasuk Rp3 juta institusi. [Rancangan pembayaran Co-Host](12-PEMBAYARAN-COHOST-PER-INSTITUSI.md)

## 3. Contoh perjalanan peserta

```mermaid
flowchart LR
  A["Isi profil + foto"] --> B["Verifikasi email"]
  B --> C["Pilih kegiatan"]
  C --> D["Dashboard"]
  D --> E["Scan QR sesuai pos"]
  E --> F["Fasilitas / Kehadiran"]
  D --> G["Dokumen + Sertifikat"]
```

- Peserta membuat akun melalui email OTP/Magic Link **tanpa password peserta**, mengisi nomor WhatsApp dan foto profil (maks. 2 MB, crop 1:1).
- Dashboard hanya memperlihatkan kegiatan yang diikuti, statusnya, dokumen, dan **Next Action**.
- Peserta yang ikut Conference + MoU + ICD memakai **QR yang sama** tetapi hak dan kehadiran tiap kegiatan dibedakan.

## 4. Alur akademik dari abstrak ke sertifikat

```mermaid
flowchart TD
  A["Submitting Author: Abstract Text + PDF"] --> B["Validasi form + server saat Submit"]
  B -->|Correction| A
  B -->|Valid, Submitted| C["Reviewer Index: cari/filter Track"]
  C --> D["Reviewer Claim mandiri"]
  D --> E["Single-anonymous Review"]
  E --> F{"Komite Akademik: keputusan final"}
  F -->|Revision| A
  F -->|Rejected announced| X["Jalur presenter ditutup"]
  F -->|Accepted announced| G["LoA + Full Paper Unlock"]
  G --> H["Upload Full Paper"]
  G --> I["Pembayaran setelah ACCEPT"]
  H --> J["Penjadwalan + Moderator"]
  I --> J
  J --> K["Paper PRESENTED"]
  K --> L["Semua Author Eligible"]
  L --> M["Panitia Publikasi Massal"]
  M --> N["Satu PDF + URL publik unik per penulis"]
```

- **Disepakati:** text abstrak dan PDF wajib; **file abstrak PDF saja**. Submitting/Corresponding Author mengelola seluruh submission, tidak harus First Author.
- **Disepakati terbaru:** reviewer **bebas mencari/filter topik** dari indeks abstrak valid disubmit dan **Claim** mandiri; bukan menunggu penugasan manual atau akhir masa submission. Reviewer memberi rekomendasi, komite membuat final decision.
- **Baseline repo lama:** single-anonymous; satu reviewer default tetapi configurable. **Jumlah reviewer resmi ICHES masih terbuka.**
- **Disepakati terbaru:** Full Paper terbuka ketika **ACCEPT resmi diumumkan kepada author**. Rejected tidak dapat upload. Pembayaran presenter setelah ACCEPT dan proses administratifnya terpisah.
- **Disepakati:** paper yang benar-benar dipresentasikan membuat **semua author** memenuhi dasar sertifikat; panitia melakukan **publikasi massal**, satu URL dan PDF unik setiap penulis.

## 5. International MoU adalah pendaftaran institusi

```mermaid
flowchart TD
  A["PIC memasukkan institusi"] --> B["1 Pendaftaran MoU per Institusi"]
  B --> C["PIC usulkan delegasi / penandatangan"]
  C --> D["Panitia verifikasi per orang"]
  D --> E["Undangan Email Magic Link"]
  E --> F["Akun / QR Pribadi Delegasi"]
  F --> G["Check-in Khusus MoU"]
  G --> H["Status Penandatanganan Dicatat Terpisah"]
```

Delegasi yang hanya mengikuti MoU **tidak otomatis** memperoleh status Conference Presenter, hak mengikuti ICD, atau Conference Kit. Panitia dapat memberikan fasilitas tertentu melalui pengecualian individual tanpa memalsukan status keikutsertaan.

## 6. Operasional acara dan QR scanner

```mermaid
flowchart TD
  A["Admin / Koordinator Layanan"] --> B["Atur Pos, Petugas, Slot, Hak"]
  B --> C["Petugas pilih Pos + Mode Scanner"]
  C --> D["Scan QR Pribadi"]
  D --> E{"Cek hak, slot, stok, riwayat"}
  E -->|Berhak| F["Konfirmasi layanan benar-benar diberikan"]
  E -->|Tidak| G["Tampilkan alasan"]
  F --> H["Rekap dan pencegahan pengambilan ganda"]
```

- **Model C hybrid:** pos tetap teridentifikasi, scanner dapat berpindah sesuai penugasan petugas.
- **Absensi hybrid:** check-in umum dan check-in khusus untuk MoU, ICD, atau sesi akademik yang memerlukan pembuktian.
- **Konsumsi bersama:** satu orang **hanya sekali** per slot konsumsi (contoh Lunch Hari 1), meskipun ikut tiga kegiatan.
- Hak fasilitas: **Default / Grant / Revoke**, dapat diubah segera oleh Admin dan Koordinator sesuai layanan. Penyerahan lampau tidak dihapus hanya karena hak berubah.
- Scan tidak otomatis berarti barang diserahkan, peserta hadir pada sesi, atau paper sudah dipresentasikan; diperlukan konfirmasi pelayanan/Moderator.

## 7. Sertifikat publik seperti pola verifikasi Codepolitan

```mermaid
flowchart TD
  A["Moderator: paper Presented"] --> B["Seluruh Author Eligible"]
  B --> C["Panitia periksa + publikasi massal"]
  C --> D["Sertifikat PDF & kode unik per author"]
  D --> E["Dashboard Submitting Author"]
  E --> F["Download semua / salin tautan"]
  F --> G["Co-author buka URL publik tanpa login"]
  G --> H["Verifikasi status + unduh PDF"]
```

**Contoh URL ilustratif:** `https://<domain-iches>/c/<kode-acak-unik>`. Tautan dicetak pada PDF serta diwujudkan dalam QR sertifikat. Data pribadi (email/WhatsApp, pembayaran) tidak muncul pada halaman verifikasi. Dokumen yang dicabut/digantikan harus ditandai tidak valid.

## 8. Peran dan dashboard

| Aktor | Yang dikerjakan |
|---|---|
| Peserta / Submitting Author | Profil, kegiatan, abstrak, revisi, pembayaran, dokumen, sertifikat |
| PIC MoU | Pendaftaran institusi, pengusulan delegasi |
| Reviewer | Search/filter abstrak berdasarkan topik, claim mandiri, dan nilai paper yang tersedia |
| Komite Akademik | Pantau paper yang belum diklaim, review dan keputusan final, full paper, jadwal |
| Moderator | Konfirmasi pelaksanaan presentasi, bukan memutuskan ACCEPT |
| Finance | Memeriksa bukti pembayaran |
| Admin & Koordinator | Hak fasilitas, pos, slot, petugas, koreksi operasional |
| Petugas scanner | Memindai dan mengonfirmasi layanan sesuai penugasan |
| Admin Sertifikat | Pratinjau, publikasi massal, koreksi/reissue |

**Desain:** Laravel + Inertia + Vue 3 + Tailwind + shadcn-vue; Backoffice dominan **putih dan hijau polos, tanpa gradient**, responsif dan mobile-first. Referensi UI Shadcn Vue Admin. Preset visual yang diberikan: `b3G0JXXL04` (asal Next.js; hanya acuan desain, bukan template kode Vue yang langsung dipasang).

## 9. Yang dipakai dari repository terdahulu

Repository referensi: [international-conference-platform — develop](https://github.com/akhmadafnan/international-conference-platform/tree/develop).

| Dipertahankan sebagai baseline | Dirancang ulang | Belum menjadi fitur aplikasi |
|---|---|---|
| Metadata ilmiah, review, LoA, file versi, prinsip verifikasi | Multi-edition → single-event | Submission akademik, review, MoU institusional |
| Laravel/Vue/Inertia, kontrol akses, payment manual | Paket tetap → multi-keikutsertaan | Scanner hak fasilitas & slot konsumsi |
| UUID, storage privat, kode QR Event Pass | Password → Magic Link; QR personal | Sertifikat publik massal untuk seluruh author |

**Penting:** repo lama sampai **Phase 02 Registration + Payment**; spesifikasi akademik tersedia terutama sebagai dokumen, bukan bukti fitur akademik sudah berjalan.

## 10. Keputusan pimpinan yang masih diperlukan

| Area | Perlu arahan |
|---|---|
| Academic policy | Batas kata/keyword, template PDF, jumlah reviewer per paper, aturan klaim reviewer macet, siklus revisi final |
| Full paper & publikasi | Unlock Full Paper segera saat ACCEPT diumumkan; validasi pra-acara, penilaian pascapresentasi, kebijakan prosiding/jurnal |
| Keuangan | Co-Host Rp3 juta/institusi khusus paper Conference; program pendamping Rp1 juta/orang; kuota paper Co-Host dan tarif kategori lain belum final |
| Fasilitas | Conference Kit sekali per orang; souvenir MoU per institusi atau per orang; kuota delegasi |
| Sertifikat | Apakah MoU Delegate dan ICD memiliki sertifikat tersendiri; aturan kelayakan |
| Jadwal | Jadwal acara dan layanan; agenda paralel diterima tanpa modul konflik kompleks |
| Identitas visual | Logo, template resmi, finalisasi token warna/font |

## 11. Usulan tahapan kerja (belum coding)

```mermaid
flowchart LR
  A["Audit repo lama + keputusan baru"] --> B["Validasi rancangan bersama Warek I"]
  B --> C["Kunci PRD + matriks kebijakan"]
  C --> D["Desain UI / UX"]
  D --> E["Implementasi bertahap setelah izin"]
  E --> F["UAT panitia dan simulasi acara"]
```

**Inti paparan kepada pimpinan:** satu sistem yang menyatukan pengalaman peserta dan pekerjaan panitia; kegiatan tetap independen; keputusan akademik, keuangan, kehadiran, hak fasilitas, dan sertifikat tidak dicampur; fitur operasional bisa dikelola panitia melalui CRUD yang mudah.

---

*Dokumen ini adalah visualisasi rancangan yang telah dibahas, bukan klaim bahwa aplikasi sudah diproduksi atau dipakai. Komponen berlabel “belum final” tidak boleh diperlakukan sebagai kebijakan yang telah disetujui.*
