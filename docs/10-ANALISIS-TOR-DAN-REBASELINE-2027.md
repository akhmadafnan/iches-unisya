# 10 — Analisis TOR Warek I & Rebaseline Aplikasi ICHES UNISYA 2027

> **SUMBER PRIMER:** Dokumen yang diunggah pemilik proyek: `TOR ICHES 6 UNISYA 2027.docx`, 9 halaman, bagian A–O. Tanggal audit: **26 Oktober 2026**.  
> **PENTING:** dokumen ini merupakan analisis kebutuhan aplikasi **berdasarkan TOR**, bukan keputusan seluruh rincian biaya/fitur yang sudah disahkan untuk implementasi. TOR sumber belum diunggah ke repo publik; hanya kebutuhan relevan yang diringkas. **Tidak ada pengembangan fitur** dalam pembaruan ini.
>
> Gunakan status: **TERCANTUM DI TOR**, **SUDAH DISEPAKATI PRODUK**, **USULAN SISTEM**, atau **PERLU KONFIRMASI PANITIA/WAREK I**.

## I. Identitas kegiatan yang harus masuk konfigurasi acara

| Komponen | TOR (halaman) | Status |
|---|---|---|
| Nama | The 6th International Conference on Humanity, Education, and Society (ICHES 2027) | TERCANTUM DI TOR, hal. 1 & 3 |
| Penyelenggara/tempat utama | UNISYA Lumajang | TERCANTUM DI TOR, hal. 1 & 4 |
| Tema | ISLAMIC VALUES IN A CHANGING WORLD: EDUCATION, ACCOUNTABILITY, AND SOCIAL RESILIENCE FOR INCLUSIVE DEVELOPMENT | TERCANTUM DI TOR, hal. 3 |
| Konferensi (hari utama) | **Selasa, 5 Januari 2027** | TERCANTUM DI TOR, hal. 4 |
| Pengabdian + destinasi | **Rabu, 6 Januari 2027** | TERCANTUM DI TOR, hal. 4 |
| Mode konferensi | Hybrid: luring dan daring | TERCANTUM DI TOR, hal. 4 |
| Mode pengabdian/destinasi | Luring penuh | TERCANTUM DI TOR, hal. 4 |
| Lokasi lapangan | Teras Semeru, Desa Sidomulyo, Tumpak Sewu | TERCANTUM DI TOR, hal. 4 & 8 |

Tema dan tanggal merupakan data yang sudah tertulis dalam TOR; tetap sediakan pengaturan Admin jika pimpinan mengubah rundown.

## II. Lima rangkaian kegiatan: bukan lagi hanya tiga

TOR bagian F, hal. 3 **menetapkan lima jenis kegiatan**:

1. International Conference — plenary dan parallel session.
2. **Pembinaan Riset dan PKM** — bersama Subdit Penelitian dan PKM Kemenag RI.
3. **Penandatanganan International MoU** — kemitraan perguruan tinggi.
4. **Pengabdian Internasional** — dengan kampus mitra dan masyarakat lokal.
5. **Studi Internasional Destinasi Berkelanjutan** — kawasan Teras Semeru dan Tumpak Sewu.

### Peta aplikasinya

```mermaid
flowchart TD
  A["Satu akun + personal QR ICHES 2027"] --> C["Conference: Presenter / Participant"]
  A --> R["Pembinaan Riset dan PKM"]
  A --> M["International MoU: PIC + Delegasi"]
  A --> P["Pengabdian Internasional / ICD"]
  A --> T["Studi Destinasi Berkelanjutan"]
  C --> O["Absensi / layanan / jadwal"]
  R --> O
  M --> O
  P --> O
  T --> O
  O --> Z["Hak per jenis layanan, tanpa duplikasi"]
```

**Penting:** lima program tidak otomatis berarti lima **tagihan terpisah**. Aktivitas yang dapat diikuti independen tetap dapat didanai oleh **paket harga/bundle** sebagaimana TOR. Pisahkan entitas **activity enrollment**, **package/fee**, **service entitlement**, dan **attendance**.

**Dampak terhadap rancangan sebelumnya:** dua program yang sempat dianggap tambahan opsional, yaitu Pembinaan Riset/PKM dan Sustainable Destination Study, kini **berbasis TOR masuk lingkup acara**. Detail pendaftaran, kapasitas, dan tarif tersendiri belum dijelaskan.

## III. Delapan subtema untuk konfigurasi Conference Track

Dari TOR hal. 3:
1. Islamic Studies, Ethics, and Contemporary Civilization
2. Education, Pedagogy, and Human Development
3. Leadership, Governance, and Institutional Accountability
4. Economics, Sustainable Business, and Inclusive Development
5. Law, Human Rights, Social Justice, and Public Policy
6. Social Sciences, Humanities, Culture, and Community Resilience
7. Digital Transformation, Responsible Innovation, and Society
8. Environmental Sustainability, and Human Well-Being

**Aplikasi:** jadikan daftar delapan track ini sebagai data yang dapat diedit panitia; harus dapat dipilih ketika abstract submission. TOR tidak menyatakan batas kata, jumlah keywords, ukuran PDF atau default reviewer; ketentuan tersebut **tetap terbuka**, kecuali keputusan pemilik produk sebelumnya bahwa abstract text + **PDF saja** wajib.

## IV. Important Dates — konfigurasi awal dari TOR

| Tahap | Jadwal tertulis TOR | Implikasi aplikasi |
|---|---|---|
| Abstract submission | **1–20 Desember 2026** | Window formulir abstrak |
| Pengumuman abstrak diterima | **22 Desember 2026** | Deadline komite final decision dan notifikasi massal |
| Full Paper | **23–28 Desember 2026** | Upload terbuka setelah ACCEPT/LoA |
| Registrasi / pembayaran | **24–28 Desember 2026** | Window tagihan dan verifikasi keuangan |
| Konferensi | **5 Januari 2027** | Plenary, panel, MoU, pembinaan |
| Pengabdian dan destinasi | **6 Januari 2027** | Rombongan onsite dan check-in lokasi |
| Prosiding | **28 Januari 2027** | Target *publication handoff/monitoring*, bukan otomatis terbit di ICHES |
| Jurnal | Mengikuti jurnal tujuan | Tidak menjanjikan tanggal terbit di platform |

**PERUBAHAN PENTING:** Full Paper **mulai 23 Desember**, tetapi window pembayaran **mulai 24 Desember**. Maka **jangan mengunci unggah full paper di belakang pembayaran VERIFIED**. Untuk sistem baru: setelah Accepted + LoA, beri akses full paper dan pembayaran secara **paralel** menurut jadwal masing-masing. Pembayaran valid diperlukan untuk status administrasi/kelayakan yang ditentukan komite, namun tidak boleh memblokir pembukaan full paper 23 Desember.

**Risiko tenggat:** submission ditutup 20 Desember dan pengumuman hasil 22 Desember → rentang penilaian sangat pendek jika ada banyak paper dan revisi. TOR **tidak menetapkan** jumlah reviewer atau putaran revisi. Untuk operasional sarankan screening berjalan setiap submission masuk, review bergulir, reminder, pemantauan reviewer, dan bulk publication **hasil keputusan yang sudah disahkan**, bukan auto-accept. Tetap perlu konfirmasi apakah revisi abstrak dapat dilakukan sebelum pengumuman.

## V. Biaya dan kategori peserta persis dari TOR (hal. 5)

| No | Kategori TOR | Mode TOR | Biaya TOR |
|---|---|---|---|
| 1 | Pemakalah Reguler (Nasional) | Luring / onsite | **IDR 500.000** |
| 2 | Pemakalah Reguler (Internasional) | Luring / onsite | **USD 40** |
| 3 | Pemakalah Daring (Nasional) | Daring / online | **IDR 250.000** |
| 4 | Pemakalah Daring (Internasional) | Daring / online | **USD 20** |
| 5 | Peserta (Nasional) | Non-pemakalah, luring / onsite | **IDR 150.000** |
| 6 | Peserta (Internasional) | Non-pemakalah, daring / online | **USD 10** |
| 7 | Peserta Mahasiswa | Non-pemakalah, luring / daring | **IDR 100.000** |
| 8 | Peserta Institusi Co-Host | Pemakalah & peserta, luring / daring | **IDR 450.000** |
| 9 | MoU + Upgrading Kemenag + PKM Internasional + Destinasi Internasional | Peserta, luring / onsite | **IDR 1.000.000** |

TOR bagian J juga mengelompokkan peserta: Reguler; delegasi PTKIS Tapal Kuda; Institusi Co-Host dengan keterangan **maksimal 5 peserta** (hal. 5).

**Jangan mengasumsikan hal yang tidak dinyatakan TOR:**
- Co-Host maksimal 5 itu **per institusi atau total keseluruhan**? Perlu konfirmasi.
- Biaya Co-Host IDR 450.000 itu **per orang, per paper, atau paket institusi**? Perlu konfirmasi.
- Bundle IDR 1.000.000 itu **per orang atau per institusi**, dan apakah termasuk layanan/transport/akomodasi tertentu? Perlu konfirmasi.
- Apakah delegasi PTKIS Tapal Kuda memiliki diskon atau harga khusus? **Tidak ada tabel tarif khusus Tapal Kuda** dalam TOR.
- Bagaimana peserta nasional yang ingin *participant-only online*, peserta internasional non-pemakalah onsite, atau mahasiswa internasional ditagih? Tidak ada kategori eksplisit untuk beberapa kombinasi.
- Bagaimana harga jika orang yang sama sudah membayar Conference tetapi juga membeli bundle Rp1.000.000? Double charge / diskon / tarif bundle masih perlu diputuskan.
- **Biaya yang dibebankan sebelum ACCEPT bagi Presenter tetap tidak boleh terjadi** menurut keputusan produk; window TOR 24–28 Desember mendukung aturan itu.

**Model sistem yang direkomendasikan:** Admin mengonfigurasi kategori fee + mode onsite/online + mata uang, eligibility rules, opsi bundle, dan kewajiban pembayarannya. Setiap pembebanan biaya menyimpan snapshot kategori, nominal, mata uang, dan tujuan transfer. Tidak perlu mesin konversi USD otomatis tanpa kebijakan finansial; transfer manual dan verifikasi Finance tetap dapat dipakai.

## VI. Jadwal pelaksanaan dan implikasi operasional

### 5 Januari — pembukaan dan plenary

- Registrasi dan penyambutan: **08.00–08.45**.
- Pembukaan dilanjutkan plenary, dengan susunan sambutan dan tiga keynote; plenary tercatat berlangsung **10.28–12.33**.
- TOR juga mencantumkan pemberian hadiah *International Competition* pada pembukaan; **belum cukup alasan** untuk membangun modul kompetisi lengkap. Cukup masukkan agenda/hasil penghargaan jika panitia benar-benar memerlukan.

**Kebutuhan aplikasi:** jadwal yang dapat dipublikasikan, penanda acara plenary, informasi narasumber dan moderator, registrasi QR pagi, rekap kehadiran, dan pengaturan slot konsumsi riil.

### 5 Januari — dua agenda sore yang bertabrakan

| Agenda TOR | Jam |
|---|---|
| MoU + Pembinaan Kemenag | **13.00–17.15** (registrasi di awal; upgrading 15.15–16.30) |
| Parallel Panel | **13.15–16.30+** (presentasi 13.40–15.40; catatan reviewer 16.15–16.30) |

**Risiko besar:** seseorang bisa menjadi presenter konferensi sekaligus delegasi MoU/peserta pembinaan. Jadwal dua kegiatan sore **beririsan**, sehingga keberadaan pada salah satunya **tidak boleh otomatis menyatakan kehadiran penuh pada yang lain**. Perlu:
- tampilan **schedule conflict** pada dashboard personal;
- status siapa *actual presenter* per paper;
- QR/check-in per aktivitas khusus bila diwajibkan;
- kebijakan pengganti delegasi atau presenter apabila tugas bertabrakan;
- keputusan apakah waktu presentasi/pembinaan perlu disesuaikan oleh panitia.

Detail TOR MoU pukul **13.00–13.30** memiliki kolom kegiatan kosong (PIC Sie Registrasi), jadi sebut **slot registrasi/penyambutan sementara**, belum mengklaim deskripsi final. TOR menulis format waktu tidak seragam pada beberapa baris; normalisasi jam diperlukan sebelum input sebagai jadwal aplikasi.

### 6 Januari — Pengabdian dan Destinasi (seluruhnya onsite)

| Jam dari TOR | Kegiatan |
|---|---|
| 03.00–04.30 | Berangkat dari Lumajang |
| 04.30 | Tiba di Teras Semeru |
| 04.30–06.00 | Eksplorasi/pemandangan Semeru |
| 06.00–07.00 | Sarapan di rumah warga |
| 07.00–07.30 | Pembukaan dan pembagian kelompok PKM (Pendidikan, Ekonomi, Sosial Budaya) |
| 07.30–08.00 | Perjalanan ke Desa Sidomulyo |
| 08.00–11.30 | Pengabdian per kelompok |
| 11.30–12.30 | Perjalanan ke Tumpak Sewu |
| 12.30–13.30 | Makan siang |
| 13.30–15.00 | Kegiatan di Tumpak Sewu |
| 15.00–15.30 | Closing |

Kebutuhan sistem yang relevan **tanpa overengineering**:
- manifest peserta luring dan pembagian kelompok PKM;
- penugasan PIC kelompok/pendamping, informasi kendaraan/rombongan **hanya jika panitia membutuhkannya**;
- check-in keberangkatan/awal kegiatan dan titik yang disepakati;
- hak sarapan serta lunch pada **slot berbeda**;
- rekap siapa terdaftar, hadir dan berpartisipasi;
- catatan status kegiatan/Completed untuk laporan bila dipersyaratkan.
- **Mitigasi konektivitas lapangan:** data daftar peserta yang dibawa petugas dan prosedur pencatatan manual terbatas; jangan mengklaim QR online akan selalu bekerja di seluruh titik.

## VII. Luaran resmi TOR harus terhubung ke laporan kegiatan

TOR bagian G, hal. 3–4 menargetkan:
1. Prosiding konferensi internasional (ISBN/ISSN).
2. Artikel terpilih untuk jurnal nasional/internasional.
3. Rekomendasi kebijakan dan resolusi konferensi.
4. Penguatan jaringan kolaborasi akademik (MoU).
5. Dokumentasi serta dampak Pengabdian Internasional.
6. Laporan kegiatan kolaborasi internasional.

**Aplikasi tidak harus mengerjakan semuanya secara otomatis.** Sarankan dokumen hasil/proceedings links/status publication handoff, rekap MoU per institusi, bukti kegiatan lapangan minimum, dan ekspor ringkasan CSV/PDF untuk laporan panitia. Penerbitan jurnal tetap urusan penerbit/OJS.

## VIII. Matriks perubahan terhadap dokumentasi pra-TOR

| Rancangan sebelumnya | Bukti TOR | Penyesuaian |
|---|---|---|
| 3 jalur utama dan 2 opsional | Lima rangkaian acara bagian F | **PROMOTE** Pembinaan Riset/PKM dan Studi Destinasi menjadi cakupan acara |
| Tanggal/kegiatan sebagai contoh | Important Dates lengkap bagian K | **SET** jadwal konkret (masih dikonfirmasi saat publikasi) |
| Tipe biaya belum jelas | 9 baris fee bagian L | **IMPORT AS POLICY DRAFT**, cek unit tarif/eligibility/bundling |
| Semua memilih keikutsertaan independen | Bundle MoU + Pembinaan + PKM + Destinasi | **KEEP** enrollment terpisah; **ADD** bundle pricing sehingga tidak memalsukan registrasi |
| Full paper mengikuti pembayaran | Full Paper 23 Des, pembayaran 24 Des | **ADJUST** izinkan jalur Full Paper dan payment paralel setelah ACCEPT |
| Absensi konseptual | Registrasi, plenary, MoU, panel, dan field visits ada jamnya | **SCOPE** station/slot dan bukti hadir per kegiatan |
| Jadwal hanya akademik | Dua rangkaian sore beririsan | **ADD** conflict warning dan operator pemetaan |
| ICD generik | PKM per kelompok Pendidikan/Ekonomi/Sosial Budaya | **ADD** group allocation sederhana |
| Studi destinasi belum wajib | Hari 2 penuh onsite | **ADD** manifest dan checkpoint lapangan |
| Output hanya sertifikat/paper | 6 outputs pada bagian G | **ADD** ringkasan pelaporan, arsip tautan hasil |

## IX. Keputusan krusial yang perlu dibawa ke Warek I

1. **Kebijakan biaya:** unit pembayaran Co-Host (Rp450.000) dan bundling Rp1.000.000, serta jika satu orang ikut beberapa kegiatan.
2. **Co-Host maksimal 5:** per institusi atau total?
3. **Konflik sesi sore 5 Januari:** apakah jadwal akhir memang paralel dan bagaimana presenter sekaligus delegasi MoU ditangani?
4. **Reviewer/timeline:** apakah pengumuman 22 Desember realistis dengan reviewer 1 default, dan apakah perlu revisi abstrak terjadwal?
5. **Full Paper:** unggah mulai 23 Desember sebelum payment window 24 Desember — apakah setuju proses paralel tanpa menunggu pembayaran?
6. **Peserta Day 2:** pendaftaran mandiri/bundle, kuota, transport, pembagian kelompok, titik check-in, fasilitas.
7. **Output & sertifikat non-akademik:** sertifikat Pembinaan, MoU, PKM, Studi Destinasi dan syarat kelayakannya.
8. **Kategori tarif yang belum tercakup:** peserta internasional onsite non-pemakalah, nasional online non-pemakalah, dll.
9. **Rundown yang belum rapi:** sesi MoU 13.00–13.30 tanpa nama kegiatan; beberapa waktu tidak seragam; satu atau dua sesi dapat bertabrakan.

## X. Prioritas MVP berdasarkan tanggal acara

**Gelombang 1 — sebelum 1 Desember:** website/CfP, akun/email, profil, jalur partisipasi, 8 tracks, abstract online + PDF, konfigurasi fee awal dan important dates.

**Gelombang 2 — sebelum 20–28 Desember:** screening dan reviewer, keputusan akademik, LoA, tagihan/pembayaran, full paper, daftar peserta/delegasi, jadwal dan pengaturan stasiun/entitlements.

**Gelombang 3 — sebelum 5 Januari:** scan personal QR, registrasi on-site, slot konsumsi/kit, check-in khusus MoU, moderator dan sesi parallel, manifest dan kelompok Day 2.

**Gelombang 4 — pascaacara:** validasi Presented, penilaian lanjutan / publication handoff, publikasi sertifikat massal serta arsip laporan/output.

Ini **urutan yang direkomendasikan**, bukan janji tenggat pengembangan. Penentuan GO pembangunan dan pengujian tetap diputuskan pemilik proyek.

## XI. Aturan sumber kebenaran

- TOR memberi **fakta acara dan arah penyelenggaraan**, bukan otomatis spesifikasi semua fitur aplikasi.
- Keputusan eksplisit pemilik produk untuk mekanisme aplikasi yang **tidak bertentangan TOR** tetap berlaku.
- Bila sebuah keputusan aplikasi bertentangan dengan tanggal/biaya/rangkaian TOR, dokumentasikan konfliknya dan minta keputusan panitia/WAREK I; **jangan diam-diam memilih aturan baru**.
- Kontrak metadata dan fondasi review dari [repo lama](https://github.com/akhmadafnan/international-conference-platform/tree/develop) dapat digunakan ketika TOR tidak membahasnya.
- Semua pengembangan tetap **belum dimulai**; dokumentasi ini adalah bahan harmonisasi kebijakan dan presentasi.
