# 11 — Keputusan Terbaru: Co-Host, Reviewer Self-Claim, Full Paper

> **Untuk penjelasan kepada Warek I · 26 Oktober 2026**  
> **Status:** kebijakan produk yang baru dipilih langsung oleh pemilik produk; sumber acara: TOR ICHES 6 UNISYA 2027. Belum menjadi aplikasi operasional, belum dilakukan coding.
>
> [Kembali ke presentasi besar](00-PRESENTASI-WAREK-I.md) · [Audit TOR](10-ANALISIS-TOR-DAN-REBASELINE-2027.md) · [Register keputusan](09-KEPUTUSAN-DAN-ISU-TERBUKA.md).

## 1. Lima keputusan yang sudah ditegaskan

| No | Pokok | Keputusan |
|---|---|---|
| 1 | Tarif Co-Host | **Rp3.000.000**, bukan **Rp450.000** seperti tercetak di TOR |
| 2 | Kuota Co-Host | **Maksimal lima peserta PER INSTITUSI**, bukan lima peserta seluruh acara |
| 3 | MoU/Pembinaan dan Parallel Panel | Jadwal boleh **beririsan**, tidak butuh mekanisme penanganan benturan otomatis |
| 4 | Reviewer | Reviewer berwenang dapat **mencari/filter abstract berdasarkan track** dan **Claim** sendiri tanpa menjadi anggota wajib track tertentu |
| 5 | Full Paper | Terbuka **bila keputusan final ACCEPT sudah DIUMUMKAN kepada Submitting Author**; tidak memakai tanggal sebagai gate upload |

**Belum diputuskan:** apakah nominal Rp3 juta Co-Host adalah tarif **per institusi** atau **per peserta**. Batas **lima peserta per institusi** sudah final, tetapi batas tersebut **tidak otomatis menentukan satuan tarif**. Admin belum boleh membuat asumsi saat menghitung pembayaran.

## 2. Visual: alur reviewer aktif, bukan menunggu akhir submission

```mermaid
flowchart TD
  A["1 Desember: penerimaan abstrak dibuka"] --> B["Author kirim text + PDF"]
  B --> C["Administrative Screening"]
  C -->|Perbaikan| B
  C -->|Lolos| D["Reviewer Abstract Index"]
  D --> E["Pencarian / Filter 8 Conference Tracks"]
  E --> F["Reviewer memilih sesuai kompetensinya"]
  F --> G{"Claim aman dan slot tersedia?"}
  G -->|Ya| H["Paper masuk My Claimed Reviews"]
  G -->|Tidak| D
  H --> I["Reviewer submit hasil review"]
  I --> J["Komite Akademik menetapkan keputusan"]
  J --> K["Pengumuman resmi kepada author"]
  K -->|ACCEPTED| L["Full Paper & LoA terbuka"]
  K -->|REJECTED| M["Jalur presenter ditutup"]
  K -->|REVISION| N["Perbaikan naskah"]
  N --> C
```

### Tampilan Reviewer Abstract Index yang konkret

| Paper | Conference Track | Status | Tindakan |
|---|---|---|---|
| ICHES27-001 — Digital Literacy ... | Digital Transformation ... | Available | **Claim** |
| ICHES27-002 — Community Resilience ... | Social Sciences ... | Available | **Claim** |
| ICHES27-003 — Education Innovation ... | Education, Pedagogy ... | Claimed | Lihat status |

Tabel contoh di atas berisi **data fiktif**, bukan pendaftar nyata.

- Semua reviewer yang sudah **memiliki peran Reviewer** dapat melihat paper **lolos screening** yang tersedia. **Tidak ada konfigurasi batas Reviewer A hanya Topik 1 / Reviewer B hanya Topik 2.**
- Search: judul, kode paper; filter: daftar delapan subtema TOR. Reviewer memilih sesuai keahliannya.
- **Claim sekali secara atomik**, cegah slot diambil dua reviewer melebihi kuota; blokir reviewer mengklaim paper sendiri atau konflik langsung yang diketahui. Jumlah slot mengikuti konfigurasi jumlah reviewer per paper.
- Setelah Claim, paper tampil dalam **My Reviews**. Koordinator Akademik tetap bisa melepas/reassign klaim yang macet. **Tidak perlu deadline wajib per reviewer, SLA otomatis, atau engine reminder yang rumit.**
- **Reviewer bukan pengambil keputusan final**; penilaian diberikan kepada Komite Akademik untuk keputusan dan publikasi kepada author.
- TOR memuat tanggal pengumuman **22 Desember 2026**. Tanggal ini tetap **milestone acara**, bukan alasan menahan abstract sampai akhir masa kirim 20 Desember. Review dimulai bergulir sejak submission pertama yang lolos screening.

**Yang masih terbuka:** jumlah reviewer default operasional ICHES (baseline repo lama **1, configurable**), penanganan klaim tidak dikerjakan (cukup tindakan manual koordinator), standar rubrik dan jumlah revisi. Jangan mengunci '2 reviewer wajib' tanpa keputusan baru.

## 3. Visual: Full Paper menggunakan pemicu status, bukan kalender

```mermaid
flowchart TD
  A["Reviewer selesai"] --> B["Komite final academic decision"]
  B --> C{"Keputusan sudah diumumkan ke author?"}
  C -->|Belum| D["Full Paper belum aktif"]
  C -->|Sudah: REJECTED| E["Full Paper tidak aktif"]
  C -->|Sudah: REVISION| F["Author revisi abstrak"]
  C -->|Sudah: ACCEPTED| G["LoA available + Full Paper unlocked"]
  G --> H["Upload Full Paper"]
  G --> I["Kewajiban bayar Presenter setelah ACCEPT"]
  H --> J["Siapkan presentasi"]
  I --> K["Finance verifikasi pembayaran"]
  J --> L["Status akademik dan administrasi ditampilkan terpisah"]
  K --> L
```

**Poin penting:**
- Reviewer menyatakan Accept sebagai **rekomendasi** belum membuka Full Paper.
- Komite menyimpan final Accept secara internal **belum cukup** jika keputusan belum dipublikasikan ke author.
- Saat final ACCEPT **resmi diumumkan**, sistem mengaktifkan Full Paper pada paper itu dan menyediakan LoA.
- Jika REJECTED atau REVISION REQUIRED, Full Paper tidak aktif.
- **Finance VERIFIED bukan prasyarat mengunggah Full Paper**. Pembayaran tetap diwajibkan/ditangani setelah ACCEPT menurut kategori tarif dan aturan Finance.
- TOR menulis target Full Paper **23–28 Desember**, pembayaran **24–28 Desember**, pengumuman **22 Desember**. Ini tetap boleh ditampilkan sebagai *Important Dates*, namun tanggal **23 Desember tidak menjadi validasi pembuka akses upload**.
- Detail apakah akan ada batas akhir unggah yang keras (*closing deadline*) masih perlu diputuskan; keputusan terbaru hanya menyangkut **pemicu pembukaan**.

## 4. Co-Host: dua entitas yang harus dibedakan

```mermaid
flowchart TD
  A["Institusi Co-Host"] --> B["Pendaftaran institusi"]
  B --> C["Kuota maksimal 5 peserta"]
  B --> D["Kategori biaya: Rp3.000.000"]
  C --> E["Delegasi/anggota 1-5"]
  E --> F["Akun/QR personal bila hadir"]
  D --> G{"Unit tagihan?"}
  G --> H["PER INSTITUSI atau PER PESERTA: belum final"]
```

- **Quota rule sudah final:** maksimal 5 orang untuk **setiap** institusi Co-Host.
- **Price value sudah final dalam rancangan baru:** Rp3.000.000.
- **Price unit masih terbuka:** jangan menyimpulkan otomatis Rp3 juta per institusi hanya karena kuotanya per institusi.
- Catat sumber historis Rp450.000 pada TOR halaman 5 sebagai teks yang dikoreksi pemilik produk. **Jangan gunakan** Rp450.000 untuk perhitungan tagihan baru.
- Paket MoU + Pembinaan + PKM + Destinasi **Rp1.000.000** pada TOR **tidak dikoreksi** dalam diskusi ini.

## 5. Agenda sore 5 Januari: cukup satu prinsip

MoU/Pembinaan dan Parallel Panel memang berjalan pada jam beririsan di TOR. **Tidak perlu modul konflik jadwal, mesin pencarian pengganti, atau pop-up kompleks**. Cukup agenda yang ditampilkan dengan benar, dan petugas mencatat kehadiran pada sesi masing-masing bila perlu. Status "paper Presented" **hanya** berdasarkan konfirmasi Moderator, bukan otomatis karena check-in umum ataupun kehadiran MoU.

## 6. Peta prioritas dan efek ke modul aplikasi

| Modul | Perubahan paling relevan |
|---|---|
| Backoffice Finance | Koreksi angka kategori Co-Host menjadi Rp3 juta; unit tagihan belum final |
| Institusi/Co-Host | Batas 5 orang **per institusi**, tidak global |
| Reviewer UI | Menu **Abstract Index**, **My Claimed Reviews**, filter track, tombol Claim |
| Academic Backoffice | Screening, monitor unclaimed/claimed, release claim, final decision, **Publish Decision** |
| Submitting Author Dashboard | Hasil ACCEPT diumumkan → tampil LoA dan **Upload Full Paper** |
| Jadwal/QR | Agenda paralel diterima; hanya kehadiran/status presenter yang dicatat terpisah |
| Product Documentation | Catat bahwa TOR masih menulis Rp450 ribu dan jadwal Full Paper berbasis tanggal; **produk terbaru** menggantikan kedua hal tersebut |

## 7. Satu pertanyaan yang belum perlu menghambat pembahasan

**Tarif Co-Host Rp3.000.000 ingin ditagihkan sekali kepada setiap institusi Co-Host, atau kepada masing-masing peserta Co-Host?** Ini menentukan siapa yang menjadi pembayar, bukan membatalkan keputusan nominal/kuota yang sudah Anda pilih.

**Status:** perancangan produk saja; dokumen/diagram GitHub dapat dipresentasikan. Belum coding, belum deployment dan belum ada migrasi database.
